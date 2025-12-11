<?php
// ----------------------------
// Short URL PHP Satu Halaman
// ----------------------------


// File penyimpanan
$dataFile = 'urls.json';
if (!file_exists($dataFile)) file_put_contents($dataFile, '{}');
$data = json_decode(file_get_contents($dataFile), true);


// ----------------------------
// Redirect otomatis jika punya kode
// ----------------------------
$req = trim($_SERVER['REQUEST_URI'], '/');
if ($req !== '' && !isset($_GET['shorten'])) {
if (isset($data[$req])) {
header('Location: ' . $data[$req]);
exit;
}
}


// ----------------------------
// Proses buat short URL
// ----------------------------
$shortURL = '';
if (isset($_POST['url'])) {
$url = trim($_POST['url']);
if (filter_var($url, FILTER_VALIDATE_URL)) {
$code = substr(md5(uniqid(true)), 0, 5);
$data[$code] = $url;
file_put_contents($dataFile, json_encode($data, JSON_PRETTY_PRINT));
$domain = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'];
$shortURL = "$domain/$code";
} else {
$shortURL = 'URL tidak valid.';
}
}
?>


<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Short URL</title>
<style>
body {
font-family: Arial;
background: #0f0f0f;
color: #fff;
display: flex;
justify-content: center;
align-items: center;
height: 100vh;
margin: 0;
}
.box {
background: #1b1b1b;
padding: 35px;
width: 92%;
max-width: 450px;
text-align: center;
border-radius: 14px;
box-shadow: 0 0 20px rgba(255,255,255,0.06);
}
h1 {
margin-bottom: 20px;
font-size: 26px;
}
input {
</html>
