<?php
header("Content-Type: application/json");

if (!isset($_GET["q"])) {
    echo json_encode(["error" => "No search term provided"]);
    exit;
}

$query = urlencode($_GET["q"]);

$url = "https://world.openfoodfacts.org/cgi/search.pl?search_terms=$query&json=1";

$response = file_get_contents($url);

echo $response;
?>
