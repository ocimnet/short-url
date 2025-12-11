<?php
// api.php — Short URL Generator (tanpa database)

// File penyimpanan
$FILE = __DIR__ . '/urls.json';

// JSON response
header("Content-Type: application/json; charset=utf-8");

// Validasi request method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["error" => "Method harus POST."]);
    exit;
}

// Validasi input
$url = trim($_POST['url'] ?? '');

if (!$url || !filter_var($url, FILTER_VALIDATE_URL)) {
    echo json_encode(["error" => "URL tidak valid."]);
    exit;
}

// Pastikan file ada
if (!file_exists($FILE)) {
    file_put_contents($FILE, "{}");
}

// Buka file & kunci
$fp = fopen($FILE, 'r+');
if (!$fp) {
    echo json_encode(["error" => "Gagal membuka database."]);
    exit;
}

flock($fp, LOCK_EX);

// Baca isi file
$raw = stream_get_contents($fp);
$data = json_decode($raw, true);

// Jika file rusak → reset
if (!is_array($data)) $data = [];

// Generator kode unik
function genCode($length = 6) {
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $out = '';
    for ($i = 0; $i < $length; $i++) {
        $out .= $chars[random_int(0, strlen($chars) - 1)];
    }
    return $out;
}

// Buat kode unik
$code = genCode();
while (isset($data[$code])) {
    $code = genCode();
}

// Tambahkan ke database
$data[$code] = $url;

// Simpan kembali (atomic save)
ftruncate($fp, 0);
rewind($fp);
fwrite($fp, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

// Unlock
flock($fp, LOCK_UN);
fclose($fp);

// Base URL
$base = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? "https://" : "http://") . $_SERVER['HTTP_HOST'] . "/";

// Output sukses
echo json_encode([
    "short" => $base . $code
]);
