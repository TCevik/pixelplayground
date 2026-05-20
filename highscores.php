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
    <style>
        table th, table td {
            padding: 10px;
            border-bottom: 1px solid var(--text-color);
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>

    <main class="leaderboard-container">
        <h1>High Scores</h1>
        <p>Bekijk de beste scores per game!</p>

        <nav class="game-switcher">
            <ul class="game-list" style="list-style:none; display:flex; flex-wrap:wrap; gap:10px; padding:0; justify-content:center;">
                <li><button class="game-btn active pp-btn-cta" onclick="switchGame('TicTacToe', event)" style="margin-top:0;">TicTacToe</button></li>
                <li><button class="game-btn pp-btn-cta" onclick="switchGame('Connect4', event)" style="margin-top:0;">Vier op een rij</button></li>
                <li><button class="game-btn pp-btn-cta" onclick="switchGame('Galgje', event)" style="margin-top:0;">Galgje</button></li>
                <li><button class="game-btn pp-btn-cta" onclick="switchGame('Wordle', event)" style="margin-top:0;">Wordle</button></li>
                <li><button class="game-btn pp-btn-cta" onclick="switchGame('Flappybird', event)" style="margin-top:0;">Flappybird</button></li>
                <li><button class="game-btn pp-btn-cta" onclick="switchGame('PacMan', event)" style="margin-top:0;">Pac Man</button></li>
            </ul>
        </nav>

        <h2 id="current-game-title">Leaderboard: TicTacToe</h2>
        <table class="highscore-table" style="width:100%; max-width:600px; margin:auto; background:var(--card-bg); padding:20px; border-radius:10px;">
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

    <script>
        function switchGame(gameName, event) {
            document.getElementById('current-game-title').innerText = 'Leaderboard: ' + gameName;
            
            if (event) {
                document.querySelectorAll('.game-btn').forEach(btn => btn.classList.remove('active', 'pp-btn-login'));
                event.target.classList.add('active', 'pp-btn-login');
            }

            fetch('api/get_scores.php?game=' + encodeURIComponent(gameName))
                .then(res => res.json())
                .then(data => {
                    const tbody = document.getElementById('leaderboard-data');
                    tbody.innerHTML = '';
                    
                    if (data.length === 0) {
                        tbody.innerHTML = '<tr><td colspan="3">Nog geen scores voor dit spel.</td></tr>';
                        return;
                    }

                    data.forEach(score => {
                        const tr = document.createElement('tr');
                        tr.innerHTML = `
                            <td>#${score.rank}</td>
                            <td>${score.username}</td>
                            <td>${score.score}</td>
                        `;
                        tbody.appendChild(tr);
                    });
                });
        }

        document.addEventListener('DOMContentLoaded', () => switchGame('TicTacToe', null));
    </script>
</body>
</html>
