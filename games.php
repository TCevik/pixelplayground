<?php
require_once 'config.php';
session_start();
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PixelPlayground - Games</title>
    <link rel="stylesheet" href="style/style.css">
    <style>
        .games-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; max-width: 800px; margin: 2rem auto; }
        .game-card { background: var(--card-bg); padding: 2rem; border-radius: 15px; border-top: 4px solid var(--accent-color); text-decoration: none; color: var(--text-color); transition: transform 0.3s; display: block; text-align: center; }
        .game-card:hover { transform: translateY(-10px); }
        .game-icon { font-size: 3rem; margin-bottom: 1rem; }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    <main>
        <h1>Kies een Game</h1>
        <div class="games-grid">
            <a href="tictactoe.php" class="game-card">
                <div class="game-icon">❌⭕</div>
                <h2>TicTacToe</h2>
                <p>Klassiek boter-kaas-en-eieren.</p>
            </a>
            <a href="connect4.php" class="game-card">
                <div class="game-icon">🔴🟡</div>
                <h2>Vier op een rij</h2>
                <p>Verbind vier stukken van dezelfde kleur.</p>
            </a>
            <a href="hangman.php" class="game-card">
                <div class="game-icon">🪢</div>
                <h2>Galgje</h2>
                <p>Raad het woord voordat je hangt.</p>
            </a>
            <a href="wordle.php" class="game-card">
                <div class="game-icon">🟩🟨⬜</div>
                <h2>Wordle</h2>
                <p>Raad het 5-letter woord in 6 beurten.</p>
            </a>
            <a href="flappybird.php" class="game-card">
                <div class="game-icon">🐦</div>
                <h2>Flappybird</h2>
                <p>Ontwijk de buizen en scoor punten.</p>
            </a>
            <a href="pacman.php" class="game-card">
                <div class="game-icon">🟡👻</div>
                <h2>Pac Man</h2>
                <p>Eet de bolletjes, ontwijk de spoken.</p>
            </a>
        </div>
    </main>
</body>
</html>
