<meta id="viewport" name="viewport" content="width=device-width, initial-scale=1, maximum-scale=2, shrink-to-fit=no"/>
<meta property="og:type" content="article"/>
<link href="" type="application/activity+json"/>
<meta name="twitter:card" content="summary_large_image"/>
<?php
// Retrieve the URL from the 'p' GET parameter
$url = isset($_GET['q']) ? $_GET['q'] : '';

// Validate the URL
if (filter_var($url, FILTER_VALIDATE_URL) === FALSE) {
    echo "Invalid URL.";
    exit;
}

// Use file_get_contents to fetch the content of the URL
$html = file_get_contents($url);

if ($html === false) {
    echo "Error fetching the page.";
    exit;
}

// Create a new DOMDocument and load the HTML content
$dom = new DOMDocument;
libxml_use_internal_errors(true); // Suppress parsing errors/warnings
$dom->loadHTML($html);
libxml_clear_errors();

// Create a new XPath object
$xpath = new DOMXPath($dom);

// Define the tags to extract
$tags = [
    'og:title' => "//meta[@property='og:title']",
    'og:description' => "//meta[@property='og:description']",
    'og:url' => "//meta[@property='og:url']",
    'og:image' => "//meta[@property='og:image']"
];

// Extract and echo the HTML tags
foreach ($tags as $tag => $query) {
    $elements = $xpath->query($query);
    if ($elements->length > 0) {
        $element = $elements->item(0);
        echo $dom->saveHTML($element) . "\n";
    } else {
        echo $tag . " tag not found.\n";
    }
}
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
