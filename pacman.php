<?php
require_once 'config.php';
session_start();
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pac Man</title>
    <link rel="stylesheet" href="style/style.css">
    <style>
        .pacman-container { max-width: 600px; margin: 2rem auto; text-align: center; }
        canvas { background: #000; border: 4px solid var(--accent-color); border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.5); display: block; margin: 0 auto; }
        #message { font-size: 1.5rem; font-weight: bold; margin: 1rem 0; color: var(--accent-color); height: 30px;}
        .controls { display: flex; justify-content: center; gap: 10px; margin-top: 1rem; }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    <main>
        <h1>Pac Man</h1>
        <div class="pacman-container">
            <div id="message">Gebruik de pijltjestoetsen om te bewegen</div>
            <canvas id="pacCanvas" width="420" height="420"></canvas>
            <div class="controls">
                <button class="pp-btn-cta" id="startBtn" style="background: var(--btn-secondary); margin:0;">Start / Opnieuw</button>
            </div>
        </div>
    </main>
    <script src="lib/pacman.js"></script>
</body>
</html>
