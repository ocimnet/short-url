<?php
// ==============================
// SHORT URL GENERATOR (1 FILE)
// ==============================

$FILE = __DIR__ . '/urls.json';

// Jika tombol submit ditekan
$shortened = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $url = trim($_POST['url']);

    if (!filter_var($url, FILTER_VALIDATE_URL)) {
        $error = "URL tidak valid!";
    } else {
        // Pastikan file json ada
        if (!file_exists($FILE)) {
            file_put_contents($FILE, "{}");
        }

        // Buka file + kunci
        $fp = fopen($FILE, 'c+');
        flock($fp, LOCK_EX);

        // Baca isi database json
        $raw = stream_get_contents($fp);
        $data = json_decode($raw, true);
        if (!is_array($data)) $data = [];

        // Generator kode acak
        function genCode($length = 6) {
            $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            $out = '';
            for ($i = 0; $i < $length; $i++) {
                $out .= $chars[random_int(0, strlen($chars)-1)];
            }
            return $out;
        }

        // Buat kode unik
        $code = genCode();
        while (isset($data[$code])) {
            $code = genCode();
        }

        // Simpan URL asli
        $data[$code] = $url;

        // Simpan ke file json
        ftruncate($fp, 0);
        rewind($fp);
        fwrite($fp, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        flock($fp, LOCK_UN);
        fclose($fp);

        // Buat short URL
        $base = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? "https://" : "http://") . $_SERVER['HTTP_HOST'] . "/";

        $shortened = $base . $code;
    }
}

// ==============================
// Redirect (jika user membuka /abc123)
// ==============================
$req = trim($_SERVER['REQUEST_URI'], "/");

if ($req !== "" && $req !== basename(__FILE__)) {

    if (file_exists($FILE)) {
        $json = json_decode(file_get_contents($FILE), true);

        if (isset($json[$req])) {
            header("Location: " . $json[$req], true, 301);
            exit;
        }
    }

    // Jika kode tidak ditemukan
    http_response_code(404);
    echo "<h2>404 — Short URL tidak ditemukan</h2>";
    exit;
}

// ==============================
// HTML FORM
// ==============================
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Short URL</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            padding-top: 60px;
        }
        input {
            width: 350px;
            padding: 10px;
            margin-bottom: 10px;
        }
        button {
            padding: 10px 25px;
        }
        .box {
            margin: 20px auto;
            width: 400px;
        }
    </style>
</head>
<body>

<h2>Short URL Generator</h2>

<div class="box">
    <form method="POST">
        <input type="text" name="url" placeholder="Masukkan URL lengkap..." required>
        <br>
        <button type="submit">Shorten</button>
    </form>
</div>

<?php if ($shortened): ?>
    <p><strong>Short URL:</strong></p>
    <p><a href="<?= $shortened ?>"><?= $shortened ?></a></p>
<?php endif; ?>

<?php if ($error): ?>
    <p style="color:red;"><?= $error ?></p>
<?php endif; ?>

</body>
</html>
