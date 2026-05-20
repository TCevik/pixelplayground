<?php
require_once 'config.php';
session_start();
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galgje</title>
    <link rel="stylesheet" href="style/style.css">
    <style>
        .game-container { max-width: 600px; margin: 2rem auto; background: var(--card-bg); padding: 2rem; border-radius: 15px; text-align: center; border-top: 4px solid var(--accent-color); }
        .word-display { font-size: 3rem; letter-spacing: 15px; margin: 2rem 0; font-family: monospace; }
        .keyboard { display: flex; flex-wrap: wrap; gap: 10px; justify-content: center; margin-bottom: 1.5rem; }
        .key-btn { padding: 10px 15px; font-size: 1.2rem; cursor: pointer; background: var(--btn-secondary); color: var(--text-color); border: none; border-radius: 5px; }
        .key-btn:disabled { opacity: 0.5; cursor: not-allowed; background: #555; }
        .hangman-drawing { font-family: monospace; white-space: pre; font-size: 1.5rem; line-height: 1.2; text-align: left; display: inline-block; background: var(--bg-color); padding: 10px; border-radius: 10px;}
        #message { font-size: 1.5rem; font-weight: bold; margin: 1rem 0; color: var(--accent-color); }
        .controls { display: flex; justify-content: center; gap: 10px; }
        #hint-text { margin-bottom: 10px; font-style: italic; color: #aaa; }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    <main>
        <h1>Galgje</h1>
        <div class="game-container">
            <div class="hangman-drawing" id="drawing">
  +---+
  |   |
      |
      |
      |
      |
=========
            </div>
            <div id="hint-text"></div>
            <div class="word-display" id="word-display">_ _ _ _</div>
            <div id="message">Kies een letter</div>
            
            <div class="keyboard" id="keyboard">
                <!-- Wordt gevuld via JS -->
            </div>

            <div class="controls">
                <button class="pp-btn-cta" onclick="getHint()" style="background: #eccc68; color: black; margin:0;">Hint</button>
                <button class="pp-btn-cta" onclick="resetGame()" style="background: var(--btn-secondary); margin:0;">Nieuw Woord</button>
                <button class="pp-btn-cta" onclick="giveUp()" style="background: red; margin:0;">Give Up</button>
            </div>
        </div>
    </main>
    <script src="lib/hangman.js"></script>
</body>
</html>
