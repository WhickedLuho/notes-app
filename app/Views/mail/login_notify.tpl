<!DOCTYPE html>
<html lang="hu">
<head>
  <meta charset="UTF-8">
  <title>Bejelentkezés értesítés Notes App</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f6f6f6;
      margin: 0;
      padding: 0;
    }
    .container {
      max-width: 600px;
      margin: 30px auto;
      background-color: #ffffff;
      border-radius: 6px;
      padding: 30px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.05);
    }
    .header {
      font-size: 22px;
      font-weight: bold;
      margin-bottom: 20px;
      color: #333;
    }
    .content {
      font-size: 16px;
      color: #444;
      line-height: 1.6;
    }
    .footer {
      margin-top: 30px;
      font-size: 12px;
      color: #999;
      text-align: center;
    }
    .info-box {
      background-color: #f2f2f2;
      border-radius: 4px;
      padding: 15px;
      margin: 15px 0;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="header">Bejelentkezés észlelve a fiókodban</div>
    <div class="content">
      Kedves <strong>{$user.fullname} / {$user.nickname}</strong>,<br><br>

      Értesítünk, hogy új bejelentkezés történt a fiókodba a következő adatokkal:

      {* <div class="info-box">
        <strong>📅 Dátum és idő:</strong> {$login_time}<br>
        <strong>🌍 IP cím:</strong> {$ip_address}<br>
        <strong>🖥️ Eszköz / Böngésző:</strong> {$device_info}
      </div> *}

      Ha ez a bejelentkezés tőled származik, nincs további teendőd.<br>
      Ha nem te voltál, <strong>haladéktalanul változtasd meg a jelszavad</strong>, és lépj kapcsolatba az ügyfélszolgálatunkkal.
{* 
      <br><br>
      Üdvözlettel,<br>
      <strong>{$site_name} csapata</strong> *}
    </div>

    <div class="footer">
      Ez egy automatikus üzenet – kérjük, ne válaszolj rá.<br>
      Ha nem szeretnél ilyen értesítéseket kapni, módosíthatod az e-mail beállításaidat a fiókodban.
    </div>
  </div>
</body>
</html>
