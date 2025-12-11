<?php
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
width: 100%;
padding: 12px;
border-radius: 8px;
border: none;
margin-bottom: 15px;
}
button {
width: 100%;
padding: 12px;
background: #6a5af9;
border: none;
border-radius: 8px;
color: #fff;
font-weight: bold;
cursor: pointer;
}
button:hover {
background: #8577ff;
}
.result {
margin-top: 20px;
padding: 10px;
background: #222;
border-radius: 8px;
word-break: break-word;
}
</style>
</head>
<body>
<div class="box">
<h1>Pendekkan URL Anda</h1>


<form method="POST">
<input type="url" name="url" placeholder="Masukkan URL lengkap..." required>
<button type="submit">Pendekkan</button>
</form>


<?php if ($shortURL): ?>
<div class="result">
<strong>Short URL:</strong><br>
<a href="<?= $shortURL ?>" style="color:#8e7bff;"><?= $shortURL ?></a>
</div>
<?php endif; ?>
</div>
</body>
</html>
