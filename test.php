<?php

require_once(__DIR__ . '/../../config.php');

use \local_outboundhttpstream\stream\http_wrapper;

http_wrapper::enable();

// ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// fopen

$fp = fopen("https://api.spoonacular.com/recipes/complexSearch?apiKey=e26e8819824a4c34a97f7a5ca2f6292c", "r");

print "response from fopen<br>";

while ($content = fgets($fp)) {
    print_r($content);
    print "<br><br>";
}

fclose($fp);

// ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// curl

$url = "https://api.spoonacular.com/recipes/complexSearch?apiKey=e26e8819824a4c34a97f7a5ca2f6292c";

print "response from curl<br>";
$handle = curl_init();

curl_setopt_array($handle,
    array(
        CURLOPT_URL => $url,
        CURLOPT_HEADER => 0
    )
);

$data = curl_exec($handle);
curl_close($handle);
echo $data;

// ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// guzzle

use \local_outboundhttpstream\external\guzzle_client;

$endpoint = "complexSearch?apiKey=e26e8819824a4c34a97f7a5ca2f6292c";

$guzzleclient = guzzle_client::get_guzzle_client();
$response = $guzzleclient->request('GET', $endpoint, ['http_errors' => true]);
$data = json_decode($response->getBody());

mtrace('<br><br>Response code from Guzzle is: ' . $response->getStatusCode(). "<br>\n");
mtrace("response data is " . print_r($data, true). "<br>\n");


