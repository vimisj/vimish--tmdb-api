
<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$apiKey = "32b3d5b5785e49c4b136e83d719be1b6";
$baseUrl = "https://api.themoviedb.org/3/search/movie";
$genreUrl = "https://api.themoviedb.org/3/genre/movie/list?api_key=$apiKey&language=es";
$imgBase = "https://image.tmdb.org/t/p/w500";

if (!isset($_GET['query'])) {
  echo json_encode(["poster" => "", "genre" => "Otros"]);
  exit;
}

$query = urlencode($_GET['query']);
$searchUrl = "$baseUrl?api_key=$apiKey&query=$query&language=es";

function getApiData($url) {
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (VimishIPTV PHPBot)');
  $response = curl_exec($ch);
  curl_close($ch);
  return json_decode($response, true);
}

$data = getApiData($searchUrl);
$genresData = getApiData($genreUrl);
$genres = [];

if (isset($genresData['genres'])) {
  foreach ($genresData['genres'] as $g) {
    $genres[$g['id']] = $g['name'];
  }
}

$poster = "";
$genreName = "Otros";

if (isset($data['results'][0])) {
  $movie = $data['results'][0];
  if (isset($movie['poster_path'])) {
    $poster = $imgBase . $movie['poster_path'];
  }
  if (!empty($movie['genre_ids'][0])) {
    $gid = $movie['genre_ids'][0];
    $genreName = $genres[$gid] ?? "Otros";
  }
}

echo json_encode(["poster" => $poster, "genre" => $genreName]);
?>
