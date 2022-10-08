<?php
/*
 * Copyright (C) 2018 Lukas Buchs
 * license https://github.com/lbuchs/WebAuthn/blob/master/LICENSE MIT
 *
 * Server test script for WebAuthn library. Saves new registrations in session.
 *
 *            JAVASCRIPT            |          SERVER
 * ------------------------------------------------------------
 *
 *               REGISTRATION
 *
 *      window.fetch  ----------------->     getCreateArgs
 *                                                |
 *   navigator.credentials.create   <-------------'
 *           |
 *           '------------------------->     processCreate
 *                                                |
 *         alert ok or fail      <----------------'
 *
 * ------------------------------------------------------------
 *
 *              VALIDATION
 *
 *      window.fetch ------------------>      getGetArgs
 *                                                |
 *   navigator.credentials.get   <----------------'
 *           |
 *           '------------------------->      processGet
 *                                                |
 *         alert ok or fail      <----------------'
 *
 * ------------------------------------------------------------
 */

require_once 'vendor/autoload.php';

define('TIMEOUT', 20);

try {
	session_start();

	// Read get argument and post body
	$fn = filter_input(INPUT_GET, 'fn');

	$post = trim(file_get_contents('php://input'));
	if ($post) {
		$post = json_decode($post);
	}

	if ($fn !== 'getStoredDataHtml') {
		$formats = array();
		$formats[] = 'android-key';
		$formats[] = 'android-safetynet';
		$formats[] = 'apple';
		$formats[] = 'packed';
		$formats[] = 'tpm';

		// Types selected on front end
		$typeUsb = true;
		$typeNfc = true;
		$typeBle = true;
		$typeInt = true;

		// Cross-platform: true, if type internal is not allowed
		//                 false, if only internal is allowed
		//                 null, if internal and cross-platform is allowed
		$crossPlatformAttachment = null;
		if (($typeUsb || $typeNfc || $typeBle) && !$typeInt) {
			$crossPlatformAttachment = true;

		} else if (!$typeUsb && !$typeNfc && !$typeBle && $typeInt) {
			$crossPlatformAttachment = false;
		}

		// Relying party
		$rpId = 'eapl.mx';

		// New Instance of the server library. Make sure that $rpId is the domain name.
		$WebAuthn = new lbuchs\WebAuthn\WebAuthn('WebAuthn Library', $rpId, $formats);
	}

	// ------------------------------------
	// Request for create arguments
	// ------------------------------------
	if ($fn === 'createArgs') {
		// This is a random userId
		$userId = '6f333511393d6e784e550218b66c3e09';
		$userName = 'master';
		$userDisplayName = 'master';
		$requiresResidentKey = false; // Client-side discoverable Credential
		$userVerification = true;
		$crossPlatformAttachment = null;

		// Check parameters here:
		// https://github.com/lbuchs/WebAuthn/blob/master/src/WebAuthn.php#L125
		$createArgs = $WebAuthn->getCreateArgs(
			\hex2bin($userId), $userName, $userDisplayName, TIMEOUT, $requireResidentKey, $userVerification, $crossPlatformAttachment
		);

		header('Content-Type: application/json');
		print(json_encode($createArgs));

		// Save challange to session. You have to deliver it to processGet later.
		$_SESSION['challenge'] = $WebAuthn->getChallenge();

	// ------------------------------------
	// Request for get arguments
	// ------------------------------------
	} else if ($fn === 'getGetArgs') {
		$ids = array();

		$data = unserialize(file_get_contents('registration.bin'));
		$ids[] = $data->credentialId;

		$userVerification = true;

		$getArgs = $WebAuthn->getGetArgs(
			$ids, TIMEOUT, $typeUsb, $typeNfc, $typeBle, $typeInt, $userVerification
		);

		header('Content-Type: application/json');
		print(json_encode($getArgs));

		// Save challange to session. you have to deliver it to processGet later.
		$_SESSION['challenge'] = $WebAuthn->getChallenge();

	// ------------------------------------
	// Process create
	// ------------------------------------
	} else if ($fn === 'processCreate') {
		// Check parameters here:
		// https://github.com/lbuchs/WebAuthn/blob/master/src/WebAuthn.php#L277
		$clientDataJSON = base64_decode($post->clientDataJSON);
		$attestationObject = base64_decode($post->attestationObject);
		$challenge = $_SESSION['challenge'];

		$requireUserVerification = true;
		$requireUserPresent = true;
		$failIfRootMismatch = false;

		// processCreate returns data to be stored for future logins.
		// in this example we store it in the PHP session.

		// Normaly you have to store the data in a database connected
		// with the user name.
		$data = $WebAuthn->processCreate(
			$clientDataJSON, $attestationObject, $challenge,
			$requireUserVerification, $requireUserPresent, $failIfRootMismatch
		);

		// Add user info
		//$data->userId = $userId;
		//$data->userName = $userName;
		//$data->userDisplayName = $userDisplayName;

		/*
		// Data in $data
		// See https://github.com/lbuchs/WebAuthn/blob/master/src/WebAuthn.php#L357
		$data = new \stdClass();
		$data->rpId = $this->_rpId;
		$data->attestationFormat = $attestationObject->getAttestationFormatName();
		$data->credentialId = $attestationObject->getAuthenticatorData()->getCredentialId();
		$data->credentialPublicKey = $attestationObject->getAuthenticatorData()->getPublicKeyPem();
		$data->certificateChain = $attestationObject->getCertificateChain();
		$data->certificate = $attestationObject->getCertificatePem();
		$data->certificateIssuer = $attestationObject->getCertificateIssuer();
		$data->certificateSubject = $attestationObject->getCertificateSubject();
		$data->signatureCounter = $this->_signatureCounter;
		$data->AAGUID = $attestationObject->getAuthenticatorData()->getAAGUID();
		$data->rootValid = $rootValid;
		$data->userPresent = $userPresent;
		$data->userVerified = $userVerified;
		*/

		/*
		// Store this info in a .json file
		$dataToSave = array(
			'credentialId' => chunk_split(bin2hex($data->credentialId), 64),
			'credentialPublicKey' => $data->credentialPublicKey,
		);

		// TODO: Change this debug file
		//$filename = 'registration.json';
		//file_put_contents($filename, json_encode($dataToSave));
		*/
		file_put_contents('registration.bin', serialize($data));

		$msg = 'Registration success.';
		if ($data->rootValid === false) {
			$msg = 'Registration OK, but certificate does not match any of the selected root CA.';
		}

		$return = new stdClass();
		$return->success = true;
		$return->msg = $msg;

		header('Content-Type: application/json');
		print(json_encode($return));

	// ------------------------------------
	// Proccess get
	// ------------------------------------
	} else if ($fn === 'processGet') {
		$clientDataJSON = base64_decode($post->clientDataJSON);
		$authenticatorData = base64_decode($post->authenticatorData);
		$signature = base64_decode($post->signature);
		$userHandle = base64_decode($post->userHandle);
		$id = base64_decode($post->id);
		$challenge = $_SESSION['challenge'];
		$credentialPublicKey = null;

		// Looking up correspondending public key of the credential id
		// you should also validate that only ids of the given user name
		// are taken for the login.
		/*
		if (is_array($_SESSION['registrations'])) {
			foreach ($_SESSION['registrations'] as $reg) {
				if ($reg->credentialId === $id) {
					$credentialPublicKey = $reg->credentialPublicKey;
					break;
				}
			}
		}
		*/

		$data = unserialize(file_get_contents('registration.bin'));
		$credentialPublicKey = $data->credentialPublicKey;

		if ($credentialPublicKey === null) {
			throw new Exception('Public Key for credential ID not found!');
		}

		// If we have resident key, we have to verify that the userHandle is the provided userId at registration
		if ($requireResidentKey && $userHandle !== hex2bin($reg->userId)) {
			throw new \Exception('userId doesnt match (is ' . bin2hex($userHandle) . ' but expect ' . $reg->userId . ')');
		}

		// Process the get request. throws WebAuthnException if it fails
		$WebAuthn->processGet($clientDataJSON, $authenticatorData, $signature, $credentialPublicKey, $challenge, null, $userVerification === 'required');

		$return = new stdClass();
		$return->success = true;

		$_SESSION['valid_session'] = true;

		header('Content-Type: application/json');
		print(json_encode($return));

	// ------------------------------------
	// Proccess clear registrations
	// ------------------------------------
	} else if ($fn === 'clearRegistrations') {
		$_SESSION['registrations'] = null;
		$_SESSION['challenge'] = null;

		$return = new stdClass();
		$return->success = true;
		$return->msg = 'all registrations deleted';

		header('Content-Type: application/json');
		print(json_encode($return));

	// ------------------------------------
	// Display stored data as HTML
	// ------------------------------------
	} else if ($fn === 'getStoredDataHtml') {
		$html = '<!DOCTYPE html>' . "\n";
		$html .= '<html><head><style>tr:nth-child(even){background-color: #f2f2f2;}</style></head>';
		$html .= '<body style="font-family:sans-serif">';
		if (isset($_SESSION['registrations']) && is_array($_SESSION['registrations'])) {
			$html .= '<p>There are ' . count($_SESSION['registrations']) . ' registrations in this session:</p>';
			foreach ($_SESSION['registrations'] as $reg) {
				$html .= '<table style="border:1px solid black;margin:10px 0;">';
				foreach ($reg as $key => $value) {

					if (is_bool($value)) {
						$value = $value ? 'yes' : 'no';
					} else if (is_null($value)) {
						$value = 'null';
					} else if (is_object($value)) {
						$value = chunk_split(strval($value), 64);
					} else if (is_string($value) && strlen($value) > 0 && htmlspecialchars($value) === '') {
						$value = chunk_split(bin2hex($value), 64);
					}

					if ($key === 'credentialId' || $key === 'AAGUID') {
						$value = chunk_split(bin2hex($value), 64);
					}

					$html .= '<tr><td>' . htmlspecialchars($key) . '</td><td style="font-family:monospace;">' . nl2br(htmlspecialchars($value)) . '</td>';
				}
				$html .= '</table>';
			}
		} else {
			$html .= '<p>There are no registrations in this session.</p>';
		}
		$html .= '</body></html>';

		header('Content-Type: text/html');
		print $html;
	}
} catch (Throwable $ex) {
	$return = new stdClass();
	$return->success = false;
	$return->msg = $ex->getMessage();

	header('Content-Type: application/json');
	print(json_encode($return));
}