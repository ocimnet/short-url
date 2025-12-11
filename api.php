<?php
// api.php — Short URL API (tanpa DB)

$FILE = __DIR__ . '/urls.json';
$LENGTH = 6;

// ensure data file exists
if (!file_exists($FILE)) file_put_contents($FILE, json_encode(new stdClass()));

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'POST only']);
    exit;
}

$url = trim($_POST['url'] ?? '');

if (!filter_var($url, FILTER_VALIDATE_URL)) {
    echo json_encode(['error' => 'URL tidak valid. Harus http:// atau https://']);
    exit;
}

// open file for locking
$fp = fopen($FILE, 'c+');
if (!$fp) {
    echo json_encode(['error' => 'Server error membuka file data.']);
    exit;
}

flock($fp, LOCK_EX);

// load existing
$data = json_decode(stream_get_contents($fp) ?: "{}", true);
if (!is_array($data)) $data = [];

// generate unique key
function randcode($n){
  $c='abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
  $s='';
  for($i=0;$i<$n;$i++) $s.=$c[random_int(0, strlen($c)-1)];
  return $s;
}

$code = randcode($LENGTH);
while(isset($data[$code])) $code = randcode($LENGTH);

// save
$data[$code] = $url;

ftruncate($fp, 0);
rewind($fp);
fwrite($fp, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

flock($fp, LOCK_UN);
fclose($fp);

// build URL
$base = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https://' : 'http://')
      . $_SERVER['HTTP_HOST'] . '/';

echo json_encode([
    'short' => $base . $code
]);
