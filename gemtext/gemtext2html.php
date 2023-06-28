<?php
require_once('../functions.php');

function gemtextToHtml($gemtext) {
	$lines = explode("\n", $gemtext);
	//print_r($lines);
	$html = '';

	$inPreformatted = false;

	foreach ($lines as $line) {
		//print_r($line);
		//$line = str_replace("\n", '', $line);
		//print_r($line);
		$line = trim($line);

		// Preformatted text
		if (str_starts_with($line, "​```")) {
			if (!$inPreformatted) {
				$html .= '<pre>';
				$inPreformatted = true;
			} else {
				$html .= '</pre>';
				$inPreformatted = false;
			}
		} elseif ($inPreformatted) {
			//$html .= htmlspecialchars($line) . "\n";
			$html .= htmlspecialchars($line);
		} else {
			// Headings
			if (preg_match('/^#+ /', $line)) {
				$level = strspn($line, '#');
				$text = trim(substr($line, $level));
				$html .= "<h{$level}>{$text}</h{$level}>";
			}
			// Links
			elseif (preg_match('/^=>\s*(\S+)(?:\s+(.*))?$/', $line, $matches)) {
				$url = $matches[1];
				$text = isset($matches[2]) ? $matches[2] : $url;
				$html .= "<p><a href=\"{$url}\">{$text}</a></p>";
			}
			elseif (preg_match('/^\[(.*?)\]\((.*?)\)$/', $line, $matches)) {
				$text = $matches[1];
				$url = $matches[2];
				$html .= "<p><a href=\"{$url}\">{$text}</a></p>";
			}
			// Lists
			elseif (preg_match('/^(?:[*]\s|\d\.\s|:\s)(.*)/', $line, $matches)) {
				$listType = '';
				$listItem = $matches[1];

				if (strpos($line, '*') === 0) {
					$listType = 'ul';
				} elseif (strpos($line, ':') === 0) {
					$listType = 'dl';
					$listItem = explode(':', $listItem);
					$html .= "<dt>{$listItem[0]}</dt><dd>{$listItem[1]}</dd>";
				} else {
					$listType = 'ol';
				}

				if ($listType === 'ul' || $listType === 'ol') {
					$html .= "<{$listType}>\n\t<li>{$listItem}</li>\n</{$listType}>";
				}
			}
			// Preformatted text
			elseif (preg_match('/^```$/', $line)) {
				$html .= "<pre>";
				while (($line = array_shift($lines)) !== NULL && $line !== "```") {
					$html .= htmlspecialchars($line) . "\n";
				}
				$html .= "</pre>";
			}
			// Quotes
			elseif (preg_match('/^>\s(.*)/', $line, $matches)) {
				$quote = $matches[1];
				$html .= "<blockquote>{$quote}</blockquote>";
			}
			// Inline text formatting (bold and italic)
			elseif (preg_match('/[*_]/', $line)) {
				$line = preg_replace('/([*_])(.*?)\1/', '<\1>\2</\1>', $line);
				$html .= $line;
			}
			// Horizontal rule
			elseif (preg_match('/^(---|\*\*\*)$/', $line)) {
				$html .= "<hr>";
			}
			// Normal paragraph
			else {
				if ($line === '') {
					$html .= '<br>';
				} else {
					$html .= "<p>{$line}</p>";
				}
			}
		}

		$html .= "\n";
	}

	return $html;
}

$gemtext = file_get_contents('example.gmi');
$html = gemtextToHtml($gemtext);
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>twtxt</title>
	<meta name="viewport" content="width=device-width,minimum-scale=1,initial-scale=1">
	<link rel="stylesheet" type="text/css" href="../style.css">
</head>
<body>
<?= $html ?>
</body>
</html>