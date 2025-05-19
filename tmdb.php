
<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$apiKey = "32b3d5b5785e49c4b136e83d719be1b6";
$baseUrl = "https://api.themoviedb.org/3/search/movie";
$imgBase = "https://image.tmdb.org/t/p/w500";

if (!isset($_GET['query'])) {
  echo json_encode(["poster" => ""]);
  exit;
}

$query = urlencode($_GET['query']);
$url = "$baseUrl?api_key=$apiKey&query=$query&language=es";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (VimishIPTV PHPBot)');
$response = curl_exec($ch);
curl_close($ch);

$data = json_decode($response, true);

if (isset($data['results'][0]['poster_path'])) {
  echo json_encode(["poster" => $imgBase . $data['results'][0]['poster_path']]);
} else {
  echo json_encode(["poster" => ""]);
}
?>
