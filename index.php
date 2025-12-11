<?php
$shortUrl = '';
$originalUrl = '';


/* --- CREATE/GENERATE SHORT URL --- */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['url'])) {
$url = trim($_POST['url']);


if (filter_var($url, FILTER_VALIDATE_URL)) {
$data = json_decode(file_get_contents($storage), true);


// generate code 5 chars
$code = substr(md5(uniqid(rand(), true)), 0, 5);


$data[$code] = $url;
file_put_contents($storage, json_encode($data));


$generated = true;
$originalUrl = $url;
$shortUrl = $_SERVER['HTTP_HOST'] . '/' . $code;
}
}
?>


<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Short URL Generator</title>
<style>
* { margin: 0; padding: 0; box-sizing: border-box; font-family: Arial, sans-serif; }
body { background: #0f0f15; color: #fff; display: flex; justify-content: center; align-items: center; height: 100vh; padding: 20px; }
.container { background: #1b1b28; padding: 30px; border-radius: 20px; width: 100%; max-width: 450px; text-align: center; box-shadow: 0 0 20px rgba(120,80,255,0.3); }
input { width: 100%; padding: 12px; margin: 12px 0; border: none; border-radius: 10px; font-size: 16px; }
button { width: 100%; padding: 12px; background: #7a5af8; border: none; border-radius: 10px; color: #fff; font-size: 18px; cursor: pointer; transition: 0.3s; }
button:hover { background: #9876ff; }
.result { margin-top: 20px; padding: 12px; background: #11111a; border-radius: 12px; word-wrap: break-word; }
a { color: #7a5af8; }
</style>
</head>
<body>
<div class="container">
<h2>Pembuat Short URL</h2>
<form method="POST">
<input type="url" name="url" placeholder="Masukkan URL lengkap" required>
<button type="submit">Generate</button>
</form>


<?php if ($generated): ?>
<div class="result">
<p><b>URL Asli:</b><br><?= htmlspecialchars($originalUrl) ?></p>
<p><b>Short URL:</b><br>
<a href="https://<?= htmlspecialchars($shortUrl) ?>" target="_blank">https://<?= htmlspecialchars($shortUrl) ?></a>
</p>
</div>
<?php endif; ?>
</div>
</body>
</html>


<?php
/* =====================================================
AUTO REDIRECT HANDLER (CLEAN URL) - redirect.php merged
This block only runs when redirect mode is triggered
===================================================== */


if (php_sapi_name() !== 'cli') {
// check if accessing /code but NOT posting URL
$req = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');


// skip if accessing root or index.php
if ($req !== '' && $req !== 'index.php') {
$data = json_decode(file_get_contents($storage), true);


// sanitize
$code = preg_replace('/[^a-zA-Z0-9]/', '', $req);


if (isset($data[$code])) {
$url = $data[$code];
$parsed = parse_url($url);
$allowed = ['http', 'https'];


if (isset($parsed['scheme']) && in_array(strtolower($parsed['scheme']), $allowed)) {
header('Location: ' . $url, true, 302);
exit;
} else {
http_response_code(400);
echo "URL tidak aman untuk diarahkan.";
exit;
}
} else {
http_response_code(404);
echo "Short URL tidak ditemukan.";
exit;
}
}
}
?>
