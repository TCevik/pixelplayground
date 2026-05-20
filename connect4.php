<?php
require_once 'config.php';
session_start();
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vier op een rij</title>
    <link rel="stylesheet" href="style/style.css">
    <style>
        .c4-board { display: grid; grid-template-columns: repeat(7, 50px); grid-template-rows: repeat(6, 50px); gap: 5px; justify-content: center; margin: 2rem auto; background: var(--card-bg); padding: 10px; border-radius: 10px; width: fit-content; }
        .c4-cell { background: var(--bg-color); border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: background 0.3s; }
        .c4-cell:hover { opacity: 0.8; }
        .c4-cell.red { background: #ff4757; }
        .c4-cell.yellow { background: #eccc68; }
        #message { font-size: 1.5rem; font-weight: bold; margin: 1rem 0; color: var(--accent-color); }
        .controls { margin-top: 2rem; display: flex; justify-content: center; gap: 10px; }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    <main>
        <h1>Vier op een rij</h1>
        <div id="message">Jouw beurt (Rood)</div>
        <div class="c4-board" id="c4-board">
            <!-- Genereer in JS voor overzichtelijkheid of hier -->
            <?php for($i=0; $i<42; $i++): ?>
                <div class="c4-cell" data-index="<?php echo $i; ?>"></div>
            <?php endfor; ?>
        </div>
        <div class="controls" style="margin-bottom: 1rem; align-items: center;">
            <label style="cursor: pointer;"><input type="radio" name="gameMode" value="1" checked onchange="changeMode()"> 1 Speler (vs AI)</label>
            <label style="cursor: pointer;"><input type="radio" name="gameMode" value="2" onchange="changeMode()"> 2 Spelers</label>
        </div>
        <div class="controls">
            <button class="pp-btn-cta" onclick="resetGame()" style="background: var(--btn-secondary); margin:0;">Opnieuw</button>
        </div>
    </main>
    <script src="lib/connect4.js"></script>
</body>
</html>
