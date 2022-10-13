/**
 * Creates a new FIDO2 registration
 * @returns {undefined}
 */
function newRegistration() {
	if (!window.fetch || !navigator.credentials || !navigator.credentials.create) {
		window.alert('Your browser is not supported. Look for one with WebAuthn or Passkey support.')
		return
	}

	// Get default args
	window.fetch(
		'webauthn.php?fn=createArgs',
		{method:'GET',cache:'no-cache'}
	).then(function(response) {
		return response.json()

	// Convert Base64 to ArrayBuffer
	}).then(function(json) {
		// Error handling
		if (json.success === false) {
			throw new Error(json.msg);
		}

		// Replace binary Base64 data with ArrayBuffer.
		// Another way to do this is the reviver function of JSON.parse()
		recursiveBase64StrToArrayBuffer(json);
		return json;

	// Create credentials
	}).then(function(createCredentialArgs) {
		//console.log(createCredentialArgs);
		return navigator.credentials.create(createCredentialArgs);

	// Convert to base64
	}).then(function(cred) {
		return {
			clientDataJSON:
				cred.response.clientDataJSON
				? arrayBufferToBase64(cred.response.clientDataJSON)
				: null,
			attestationObject:
				cred.response.attestationObject
				? arrayBufferToBase64(cred.response.attestationObject)
				: null,
		};

	// Transfer to server
	}).then(function(AuthenticatorAttestationResponse) {
		AuthenticatorAttestationResponse.masterPwd = document.getElementById('password').value
		AuthenticatorAttestationResponse = JSON.stringify(AuthenticatorAttestationResponse)

		return window.fetch('webauthn.php?fn=processCreate', {method:'POST', body: AuthenticatorAttestationResponse, cache:'no-cache'})

	// Convert to JSON
	}).then(function(response) {
		return response.json();

	// Analyze response
	}).then(function(json) {
		 if (json.success) {
			 //reloadServerPreview();
			 window.alert(json.msg || 'Registration success');
			 //console.log(json)
			 // TODO: Redirect to somewhere else
		 } else {
			 throw new Error(json.msg);
		 }

	// Catch errors
	}).catch(function(err) {
		//reloadServerPreview();
		window.alert(err.message || 'Unknown error occured');
	});
}

/**
 * checks a FIDO2 registration
 * @returns {undefined}
 */
function checkRegistration() {
	if (!window.fetch || !navigator.credentials || !navigator.credentials.create) {
		window.alert('Your browser is not supported. Look for one with WebAuthn or Passkey support.')
		return;
	}

	// Get default args
	window.fetch('webauthn.php?fn=getGetArgs', { method: 'GET', cache: 'no-cache' }).then(function (response) {
		//console.log(response.body)
		return response.json();

	// Convert Base64 to ArrayBuffer
	}).then(function(json) {

		// Error handling
		if (json.success === false) {
			throw new Error(json.msg)
		}

		// Replace binary base64 data with ArrayBuffer. a other way to do this
		// is the reviver function of JSON.parse()
		recursiveBase64StrToArrayBuffer(json)
		console.log(json)
		return json

	// Create credentials
	}).then(function(getCredentialArgs) {
		return navigator.credentials.get(getCredentialArgs);

	// Convert to Base64
	}).then(function(cred) {
		console.log(cred);
		return {
			id: cred.rawId ? arrayBufferToBase64(cred.rawId) : null,
			clientDataJSON: cred.response.clientDataJSON  ? arrayBufferToBase64(cred.response.clientDataJSON) : null,
			authenticatorData: cred.response.authenticatorData ? arrayBufferToBase64(cred.response.authenticatorData) : null,
			signature: cred.response.signature ? arrayBufferToBase64(cred.response.signature) : null,
			userHandle: cred.response.userHandle ? arrayBufferToBase64(cred.response.userHandle) : null
		};

	// Transfer to server
	}).then(JSON.stringify).then(function (AuthenticatorAttestationResponse) {
		return window.fetch('webauthn.php?fn=processGet', {method:'POST', body: AuthenticatorAttestationResponse, cache:'no-cache'});

	// Convert to json
	}).then(function(response) {
		return response.json();

	// Analyze response
	}).then(function(json) {
		 if (json.success) {
			 //window.alert(json.msg || 'Login success');
			 window.location.replace('index.php');
		 } else {
			 throw new Error(json.msg);
		 }

	// Catch errors
	}).catch(function(err) {
		window.alert(err.message || 'unknown error occured');
	});
}

/**
 * Convert RFC 1342-like Base64 strings to ArrayBuffer
 * @param {mixed} obj
 * @returns {undefined}
 */
function recursiveBase64StrToArrayBuffer(obj) {
	let prefix = '=?BINARY?B?';
	let suffix = '?=';
	if (typeof obj === 'object') {
		for (let key in obj) {
			if (typeof obj[key] === 'string') {
				let str = obj[key];
				if (str.substring(0, prefix.length) === prefix && str.substring(str.length - suffix.length) === suffix) {
					str = str.substring(prefix.length, str.length - suffix.length);

					let binary_string = window.atob(str);
					let len = binary_string.length;
					let bytes = new Uint8Array(len);
					for (let i = 0; i < len; i++)        {
						bytes[i] = binary_string.charCodeAt(i);
					}
					obj[key] = bytes.buffer;
				}
			} else {
				recursiveBase64StrToArrayBuffer(obj[key]);
			}
		}
	}
}

/**
 * Convert a ArrayBuffer to Base64
 * @param {ArrayBuffer} buffer
 * @returns {String}
 */
function arrayBufferToBase64(buffer) {
	let binary = '';
	let bytes = new Uint8Array(buffer);
	let len = bytes.byteLength;
	for (let i = 0; i < len; i++) {
		binary += String.fromCharCode(bytes[i]);
	}
	return window.btoa(binary);
}