<?php

require_once(__DIR__ . '/../../config.php');
require_once($CFG->libdir . '/filelib.php');

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
// file_get_contents

$data = file_get_contents("https://api.spoonacular.com/recipes/complexSearch?apiKey=e26e8819824a4c34a97f7a5ca2f6292c", "r");

print "response from file_get_contents<br>";

var_dump($data);


// ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// download_file_content from moodle file api

print "response from moodle file api - download_file_content<br>";

$data = download_file_content("https://api.spoonacular.com/recipes/complexSearch?apiKey=e26e8819824a4c34a97f7a5ca2f6292c");

var_dump($data);


// ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// raw curl

$url = "https://api.spoonacular.com/recipes/complexSearch?apiKey=e26e8819824a4c34a97f7a5ca2f6292c";

print "<br><br>response from raw curl<br>";
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
// moodle curl api

$url = "https://api.spoonacular.com/recipes/complexSearch?apiKey=e26e8819824a4c34a97f7a5ca2f6292c";

$curl = new curl();
$curl->setopt(array('CURLOPT_TIMEOUT' => 10, 'CURLOPT_CONNECTTIMEOUT' => 10));

$response = @json_decode($curl->get($url), true);
print "<br><br>response from moodle curl api<br>";
var_dump($response);

// ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// guzzle

use \local_outboundhttpstream\external\guzzle_client;

$endpoint = "complexSearch?apiKey=e26e8819824a4c34a97f7a5ca2f6292c";

$guzzleclient = guzzle_client::get_guzzle_client();
$response = $guzzleclient->request('GET', $endpoint, ['http_errors' => true]);
$data = json_decode($response->getBody());

mtrace('<br><br>Response code from Guzzle is: ' . $response->getStatusCode(). "<br>\n");
mtrace("response data is " . print_r($data, true). "<br>\n");


