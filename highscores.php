<?php
session_start();
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PixelPlayground - High Scores</title>
    <link rel="stylesheet" href="style/style.css">
</head>
<body>
    <?php include 'header.php'; ?>

    <main class="leaderboard-container">
        <h1>High Scores</h1>
        <p>Bekijk de beste scores per game!</p>

        <nav class="game-switcher">
            <ul class="game-list">
                <li><button class="game-btn active pp-btn-cta" onclick="switchGame('TicTacToe', event)">TicTacToe</button></li>
                <li><button class="game-btn pp-btn-cta" onclick="switchGame('Connect4', event)">Vier op een rij</button></li>
                <li><button class="game-btn pp-btn-cta" onclick="switchGame('Galgje', event)">Galgje</button></li>
                <li><button class="game-btn pp-btn-cta" onclick="switchGame('Wordle', event)">Wordle</button></li>
                <li><button class="game-btn pp-btn-cta" onclick="switchGame('Flappybird', event)">Flappybird</button></li>
                <li><button class="game-btn pp-btn-cta" onclick="switchGame('PacMan', event)">Pac Man</button></li>
            </ul>
        </nav>

        <h2 id="current-game-title">Leaderboard: TicTacToe</h2>
        <table class="highscore-table">
            <thead>
                <tr>
                    <th class="rank">Positie</th>
                    <th>Gebruikersnaam</th>
                    <th>Score</th>
                </tr>
            </thead>
            <tbody id="leaderboard-data">
                <!-- Data loaded via JS -->
            </tbody>
        </table>
    </main>

    <script src="lib/highscores.js"></script>
</body>
</html>
