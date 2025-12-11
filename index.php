<?php
// --- FILE: index.php ---

// Lokasi file penyimpanan
$storage = __DIR__ . "/urls.json";

// Jika file tidak ada, buat baru
if (!file_exists($storage)) {
    file_put_contents($storage, "{}");
}

// Muat data
$data = json_decode(file_get_contents($storage), true);

// **************************
//  REDIRECT MODE
// **************************
$path = trim($_SERVER["REQUEST_URI"], "/");

if ($path !== "" && $path !== "index.php") {
    if (isset($data[$path])) {
        header("Location: " . $data[$path], true, 302);
        exit;
    } else {
        http_response_code(404);
        echo "<h1>404 - Short URL tidak ditemukan</h1>";
        exit;
    }
}

// **************************
//  SHORTENER MODE (FORM UTAMA)
// **************************
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $url = trim($_POST["url"]);
    $custom = trim($_POST["custom"]);

    // Validasi URL
    if (!filter_var($url, FILTER_VALIDATE_URL)) {
        $error = "URL tidak valid!";
    } else {
        // Jika custom kosong, buat random
        if ($custom === "") {
            $custom = substr(md5(time()), 0, 6);
        }

        // Cek jika code sudah ada
        if (isset($data[$custom])) {
            $error = "Kode short sudah digunakan!";
        } else {
            // Simpan data
            $data[$custom] = $url;
            file_put_contents($storage, json_encode($data, JSON_PRETTY_PRINT));

            $base = (isset($_SERVER['HTTPS']) ? "https" : "http") . 
                    "://" . $_SERVER['HTTP_HOST'] . "/";

            $short_url = $base . $custom;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Short URL</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
    body {
        font-family: Arial, sans-serif;
        background: #111;
        color: #fff;
        padding: 30px;
        text-align: center;
    }
    .box {
        max-width: 400px;
        margin: auto;
        padding: 20px;
        background: #1e1e1e;
        border-radius: 10px;
    }
    input, button {
        width: 100%;
        padding: 12px;
        margin: 8px 0;
        border-radius: 8px;
        border: none;
        outline: none;
    }
    input { background: #333; color: #fff; }
    button { background: #4CAF50; color: white; cursor: pointer; }
    button:hover { background: #45A049; }
    .short-link {
        background: #222;
        padding: 10px;
        border-radius: 8px;
        margin-top: 10px;
        display: block;
        word-break: break-all;
    }
</style>
</head>
<body>

<h2>Short URL Sederhana</h2>
<div class="box">

<?php if (isset($error)): ?>
    <div style="color: red"><?= $error ?></div>
<?php endif; ?>

<?php if (isset($short_url)): ?>
    <h3>Short URL:</h3>
    <a class="short-link" href="<?= $short_url ?>" target="_blank">
        <?= $short_url ?>
    </a>
<?php else: ?>

<form method="POST">
    <input type="text" name="url" placeholder="Masukkan URL lengkap..." required>
    <input type="text" name="custom" placeholder="Custom kode (opsional)">
    <button type="submit">Generate Short URL</button>
</form>

<?php endif; ?>

</div>
</body>
</html>
