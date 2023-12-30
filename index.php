<?php
// Retrieve the URL from the 'q' GET parameter
$url = isset($_GET['q']) ? $_GET['q'] : '';
?>
<meta id="viewport" name="viewport" content="width=device-width, initial-scale=1, maximum-scale=2, shrink-to-fit=no"/>
<meta property="og:type" content="article"/>
<meta property="og:site_name" content="Bluesky"/>
<?php


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
    'twitter:card' => "//meta[@name='twitter:card']",
];

// Extract and echo the HTML tags
foreach ($tags as $tag => $query) {
    $elements = $xpath->query($query);
    if ($elements->length > 0) {
        $element = $elements->item(0);

        if ($tag === 'twitter:card') {
            $cardValue = $element->getAttribute('content');
            
            // Check if twitter:card is not "summary_large_image" and change it to "summary"
            if ($cardValue !== 'summary_large_image') {
                $element->setAttribute('content', 'summary');
            }
        }

        // Echo the modified or original tag
        echo $dom->saveHTML($element) . "\n";
    } else {
        echo $tag . " tag not found.\n";
    }
}

// Retrieve og:image only when twitter:card is "summary_large_image"
if (isset($cardValue) && $cardValue === 'summary_large_image') {
    $ogImageQuery = "//meta[@property='og:image']";
    $ogImageElements = $xpath->query($ogImageQuery);
    if ($ogImageElements->length > 0) {
        $ogImageElement = $ogImageElements->item(0);
        echo $dom->saveHTML($ogImageElement) . "\n";
    } else {
        echo "og:image tag not found.\n";
    }
}

?>
<link href="<?php echo "$url"; ?>" type="application/activity+json"/>
<link rel="canonical" href="<?php echo "$url"; ?>"/>
 <script type="text/javascript">
    window.open("<?php echo "$url"; ?>", "_self");
    window.open("", "_self");
    setTimeout("window.close()",1000) 


  

</script>

