<?php
require_once 'config.php';
session_start();
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wordle</title>
    <link rel="stylesheet" href="style/style.css">
    <style>
        .wordle-board { display: grid; grid-template-rows: repeat(6, 60px); gap: 5px; justify-content: center; margin: 2rem auto; }
        .wordle-row { display: grid; grid-template-columns: repeat(5, 60px); gap: 5px; }
        .wordle-cell { border: 2px solid var(--text-color); border-radius: 5px; display: flex; align-items: center; justify-content: center; font-size: 2rem; font-weight: bold; text-transform: uppercase; background: var(--card-bg); }
        .wordle-cell.correct { background: #538d4e; color: white; border-color: #538d4e; }
        .wordle-cell.present { background: #b59f3b; color: white; border-color: #b59f3b; }
        .wordle-cell.absent { background: #3a3a3c; color: white; border-color: #3a3a3c; }
        
        .keyboard { display: flex; flex-direction: column; gap: 5px; align-items: center; margin-bottom: 2rem; }
        .key-row { display: flex; gap: 5px; }
        .key-btn { padding: 15px; border-radius: 5px; border: none; background: var(--btn-secondary); color: var(--text-color); font-weight: bold; cursor: pointer; }
        .key-btn.correct { background: #538d4e; color: white; }
        .key-btn.present { background: #b59f3b; color: white; }
        .key-btn.absent { background: #3a3a3c; color: white; }
        #message { font-size: 1.5rem; font-weight: bold; margin: 1rem 0; color: var(--accent-color); }
        .controls { display: flex; justify-content: center; gap: 10px; }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    <main>
        <h1>Wordle</h1>
        <div id="message">Raad het woord in 6 beurten!</div>
        
        <div class="wordle-board" id="board">
            <!-- Genereer 6 rijen van 5 kolommen in JS -->
        </div>

        <div class="keyboard" id="keyboard">
            <div class="key-row">
                <button class="key-btn" id="key-Q">Q</button><button class="key-btn" id="key-W">W</button><button class="key-btn" id="key-E">E</button><button class="key-btn" id="key-R">R</button><button class="key-btn" id="key-T">T</button><button class="key-btn" id="key-Y">Y</button><button class="key-btn" id="key-U">U</button><button class="key-btn" id="key-I">I</button><button class="key-btn" id="key-O">O</button><button class="key-btn" id="key-P">P</button>
            </div>
            <div class="key-row">
                <button class="key-btn" id="key-A">A</button><button class="key-btn" id="key-S">S</button><button class="key-btn" id="key-D">D</button><button class="key-btn" id="key-F">F</button><button class="key-btn" id="key-G">G</button><button class="key-btn" id="key-H">H</button><button class="key-btn" id="key-J">J</button><button class="key-btn" id="key-K">K</button><button class="key-btn" id="key-L">L</button>
            </div>
            <div class="key-row">
                <button class="key-btn" onclick="submitGuess()">ENTER</button>
                <button class="key-btn" id="key-Z">Z</button><button class="key-btn" id="key-X">X</button><button class="key-btn" id="key-C">C</button><button class="key-btn" id="key-V">V</button><button class="key-btn" id="key-B">B</button><button class="key-btn" id="key-N">N</button><button class="key-btn" id="key-M">M</button>
                <button class="key-btn" onclick="deleteLetter()">⌫</button>
            </div>
        </div>

        <div class="controls">
            <button class="pp-btn-cta" onclick="resetGame()" style="background: var(--btn-secondary); margin:0;">Nieuw Potje</button>
            <button class="pp-btn-cta" onclick="giveUp()" style="background: red; margin:0;">Give Up</button>
        </div>
    </main>
    <script src="lib/wordle.js"></script>
</body>
</html>
