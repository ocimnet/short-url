<?php
// index.php — Short URL Frontend (AJAX)
?>
<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Short URL — AJAX Version</title>
<style>
  :root { --bg:#0f0f15; --card:#141421; --accent:#6a5af9; --muted:#aab; color-scheme: dark; }
  *{box-sizing:border-box} body{margin:0;font-family:Inter,system-ui,Roboto;background:linear-gradient(180deg,var(--bg),#12121a);color:#fff;min-height:100vh;display:flex;align-items:center;justify-content:center;padding:32px}
  .card{width:100%;max-width:560px;background:linear-gradient(180deg,var(--card),#10101a);padding:28px;border-radius:14px;box-shadow:0 8px 40px rgba(50,40,80,0.35)}
  h1{margin:0 0 8px;font-size:22px;color:#fff}
  p.lead{margin:0 0 18px;color:var(--muted)}
  .input-wrap{display:grid;grid-template-columns:1fr auto;gap:12px}
  input[type=url]{padding:12px 14px;border-radius:10px;border:1px solid rgba(255,255,255,0.07);background:#0b0b10;color:#fff;font-size:16px}
  button{padding:12px 18px;background:var(--accent);border:none;border-radius:10px;color:#fff;font-weight:600;cursor:pointer}
  .result,.error{margin-top:16px;padding:12px;border-radius:10px;word-break:break-all}
  .result{background:rgba(255,255,255,0.05)}
  .error{background:rgba(255,0,0,0.08);color:#ff9a9a}
  @media(max-width:520px){ .input-wrap{grid-template-columns:1fr;} }
</style>
</head>
<body>
  <div class="card">
    <h1>Short URL (AJAX)</h1>
    <p class="lead">Masukkan URL lengkap, sistem otomatis membuat link pendek.</p>

    <div class="input-wrap">
      <input id="url" type="url" placeholder="https://example.com/..." required>
      <button onclick="shorten()">Pendekkan</button>
    </div>

    <div id="output"></div>

  </div>

<script>
function shorten() {
  const url = document.getElementById('url').value.trim();
  const output = document.getElementById('output');
  output.innerHTML = "Memproses...";

  fetch('api.php', {
    method: 'POST',
    headers: {'Content-Type':'application/x-www-form-urlencoded'},
    body: 'url=' + encodeURIComponent(url)
  })
  .then(r => r.json())
  .then(d => {
      if (d.error) {
        output.innerHTML = `<div class="error">${d.error}</div>`;
      } else {
        output.innerHTML = `
          <div class="result">
             <strong>Short URL:</strong><br>
             <a href="${d.short}" target="_blank" style="color:var(--accent)">${d.short}</a>
          </div>`;
      }
  })
  .catch(() => output.innerHTML = `<div class="error">Terjadi kesalahan.</div>`);
}
</script>
</body>
</html>
