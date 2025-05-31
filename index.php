<?php
// Redirect langsung ke halaman utama
// header("Location: pages/home.php");
// exit;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Qurban made easy.</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div id="nav">
        <div>Home</div>
        <div>FAQ</div>
        <div>Dashboard</div>
        <br>
        <div>Login</div>
        <div>Register</div>
    </div>
    <div id="background"></div>
    <div class="overlay"></div>
    <div id="container">
        <div id="main">
            <div id="h1">
                <h1>Qurban made easy.</h1>
            </div>
            <p>
                Keep track of your qurban, or <br>
                keep track of your share of qurban, easily.
            </p>
        </div>
    </div>
    <div class="white shadow">
        <div id="section-2" class="sections">
            <div id="image">
                <img src="data:image/gif;base64,R0lGODdhSgBKAIAAAAAAAP///ywAAAAASgBKAAAC/4yPqcvtD6OctNqLs968+w+G4kiWZgKk6roiKfOigMLWKmXb7rzEO51j4Xgw4qElMyB/DV/ECQRCA7Hl0RidVIVUbLJb3Cq5U8cWWb6Ow7zzDaxtk73WnXvdRXvNcr10n9cnN+YXR/gmphaIZ+WWKOE4CMeIKMhUgwcJmAnXKOiUxgkROtn5RunJNxRUSelqesi6yiqZaFtLS6pRpwi6efJXxKn74cmLCXv12GF8qtxaWrgrGfxsl8vFpClq3R2LPUWsLRN5Sf2sJ/5VjfwarrNYkXs9/s1drSnrXWrP3+M8ClAONu/uxRtCECA6OufoXWj3gNQyXxDzKRTW5BzFbOlP5hwKeOsOuV9/QpKMJ/LYxY4VD/YyolIdSI4fv2zEtwHZvJH9HHLQSYsdTZc/f7WcCDOpzIx8VA5L6tNQN2n7fKFaGdAh1ZpPry6d+aqeKaQtLRQqaBPat6/+1kI7C25o1n8Dx+KKizWnQn3cbo6ICa+v0pPX9KXTCA+pWWw1qdZVLM+owL24skxTRZdhWEWXAy2LatXzHLbl2roMbTgvRqAGU3uVG/FT5cys7ZaN3S9SysCc13XMLXv30ckImU5lnGzzNuONkUMmjbyw5MPKWQYdKXH0bGDcu3v/Dj68+PHky5s/jz5BAQA7" alt="">
            </div>
            <div id="text">
                <h1>Ease of give, ease of take</h1>
                <p>
                    To take your share of qurban'd produce, take your QR code from the dashboard, download it (and print it if you need), and show it to the guys.
                </p>
            </div>
        </div>
    </div>
    <script>
  const bg = document.getElementById('background');

  window.addEventListener('scroll', () => {
    const scrollY = window.scrollY;
    const parallaxSpeed = 0.5; // adjust: 0.3 = slower, 1.0 = same speed as scroll
    bg.style.transform = `scaleX(-1) translateY(-${scrollY * parallaxSpeed}px)`;
  });
</script>
</body>
</html>


$router = require 'routes/api.php';
$router->run();
