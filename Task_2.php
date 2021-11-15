<?php

if (isset($argv) && isset($argv[1]) && isset($argv[2])) {
    $categoryName = $argv[1];
    $resultLimit = $argv[2];
} else {
    print_r("\n\nRequired arguments not passed. \n\n");
    print_r("Try running the following: php index.php Animals 5\n\n");
    die();
}

//predefined endpoint
$endpoint = "https://api.publicapis.org/entries";

//making API call to fetch the data
$data = makeApiCall($endpoint);

//converting the json response to array
$itemsArray = json_decode($data, true);

// sorting the entries alphabetically based on API values
$keys = array_column($itemsArray['entries'], 'API');
array_multisort($keys, SORT_ASC, $itemsArray['entries']);


$result = array_slice(filterByCategory($itemsArray['entries'], $categoryName), 0, $resultLimit);

if (count($result) == 0) {
    print_r("\n\nNo results\n\n");
} else {
    print_r($result);
}


/** API call GET Method to the endpoint
 * @param $url //endpoint URL
 */
function makeApiCall($url)
{
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $output = curl_exec($ch);

    curl_close($ch);

    return $output;
}

/** API call GET Method to the endpoint
 * @param $itemsArray //items array
 * @param $categoryName //category name (string)
 */
function filterByCategory($itemsArray, $categoryName)
{
    $filtered = array_filter($itemsArray, function ($item) use ($categoryName) {
        return ($item['Category'] == $categoryName);
    });

    return $filtered;
}
