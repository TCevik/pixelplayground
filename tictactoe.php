<?php
require_once 'config.php';
session_start();
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TicTacToe</title>
    <link rel="stylesheet" href="style/style.css">
    <style>
        .tictactoe-board { display: grid; grid-template-columns: repeat(3, 100px); grid-template-rows: repeat(3, 100px); gap: 5px; justify-content: center; margin: 2rem auto; }
        .cell { background: var(--card-bg); display: flex; align-items: center; justify-content: center; font-size: 3rem; cursor: pointer; border: 2px solid var(--text-color); border-radius: 10px; }
        .cell:hover { background: rgba(255,255,255,0.1); }
        #message { font-size: 1.5rem; font-weight: bold; margin: 1rem 0; color: var(--accent-color); }
        .controls { margin-top: 2rem; display: flex; justify-content: center; gap: 10px; }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    <main>
        <h1>TicTacToe</h1>
        <div id="message">Jouw beurt (X)</div>
        <div class="tictactoe-board" id="board">
            <div class="cell" data-index="0"></div>
            <div class="cell" data-index="1"></div>
            <div class="cell" data-index="2"></div>
            <div class="cell" data-index="3"></div>
            <div class="cell" data-index="4"></div>
            <div class="cell" data-index="5"></div>
            <div class="cell" data-index="6"></div>
            <div class="cell" data-index="7"></div>
            <div class="cell" data-index="8"></div>
        </div>
        <div class="controls">
            <button class="pp-btn-cta" onclick="resetGame()" style="background: var(--btn-secondary); margin:0;">Opnieuw</button>
            <button class="pp-btn-cta" onclick="giveUp()" style="background: red; margin:0;">Give Up</button>
        </div>
    </main>
    <script src="lib/tictactoe.js"></script>
</body>
</html>
