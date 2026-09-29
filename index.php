<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Iframe Embed</title>

  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: Arial, sans-serif;
      background: #f4f4f4;
      color: #222;
    }

    .header {
      background: #111;
      color: white;
      padding: 15px;
      text-align: center;
    }

    .header h1 {
      font-size: 20px;
    }

    .container {
      max-width: 1200px;
      margin: 20px auto;
      padding: 0 15px;
    }

    .form-box {
      background: white;
      padding: 15px;
      border-radius: 8px;
      margin-bottom: 15px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }

    .form-box input {
      width: 100%;
      padding: 12px;
      border: 1px solid #ddd;
      border-radius: 5px;
      margin-bottom: 10px;
      font-size: 14px;
    }

    .buttons {
      display: flex;
      gap: 8px;
      flex-wrap: wrap;
    }

    button {
      border: none;
      padding: 11px 18px;
      border-radius: 5px;
      cursor: pointer;
      font-size: 14px;
      background: #111;
      color: white;
    }

    button:hover {
      opacity: 0.85;
    }

    .embed-box {
      background: white;
      padding: 5px;
      border-radius: 8px;
      overflow: hidden;
    }

    iframe {
      display: block;
      width: 100%;
      height: 650px;
      border: none;
      background: white;
    }

    .notice {
      text-align: center;
      color: #777;
      font-size: 13px;
      padding: 15px;
    }

    @media (max-width: 600px) {
      iframe {
        height: 500px;
      }

      .header h1 {
        font-size: 18px;
      }
    }
  </style>
</head>

<body>

  <div class="header">
    <h1>Iframe Embed</h1>
  </div>

  <div class="container">

    <div class="form-box">
      <input
        type="url"
        id="urlInput"
        placeholder="Masukkan URL yang ingin ditampilkan..."
      >

      <div class="buttons">
        <button onclick="loadIframe()">Tampilkan</button>
        <button onclick="fullscreenIframe()">Fullscreen</button>
      </div>
    </div>

    <div class="embed-box" id="embedBox">
      <iframe
        id="myIframe"
        src="about:blank"
        allowfullscreen>
      </iframe>
    </div>

    <div class="notice">
      Pastikan website tujuan mengizinkan penggunaan iframe.
    </div>

  </div>

  <script>
    function loadIframe() {
      const input = document.getElementById("urlInput");
      const iframe = document.getElementById("myIframe");

      let url = input.value.trim();

      if (!url) {
        alert("Silakan masukkan URL terlebih dahulu.");
        return;
      }

      if (!url.startsWith("http://") && !url.startsWith("https://")) {
        url = "https://" + url;
      }

      iframe.src = url;
    }

    function fullscreenIframe() {
      const iframe = document.getElementById("myIframe");

      if (iframe.requestFullscreen) {
        iframe.requestFullscreen();
      } else if (iframe.webkitRequestFullscreen) {
        iframe.webkitRequestFullscreen();
      } else {
        alert("Browser Anda tidak mendukung fullscreen.");
      }
    }
  </script>

</body>
</html>
