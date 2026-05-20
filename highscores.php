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
                <li><button class="game-btn active" onclick="switchGame(1)">Game 1</button></li>
                <li><button class="game-btn" onclick="switchGame(2)">Game 2</button></li>
                <li><button class="game-btn" onclick="switchGame(3)">Game 3</button></li>
                <li><button class="game-btn" onclick="switchGame(4)">Game 4</button></li>
                <li><button class="game-btn" onclick="switchGame(5)">Game 5</button></li>
            </ul>
        </nav>

        <h2 id="current-game-title">Leaderboard: Game 1</h2>
        <table class="highscore-table">
            <thead>
                <tr>
                    <th class="rank">Positie</th>
                    <th>Gebruikersnaam</th>
                    <th>Score</th>
                </tr>
            </thead>
            <tbody id="leaderboard-data">
                </tbody>
        </table>
    </main>

    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #16213e;
            color: white;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        header h1 {
            margin: 0;
            font-size: 1.5rem;
        }

        nav a {
            color: white;
            text-decoration: none;
            margin-left: 20px;
            font-weight: 500;
        }

        nav a:hover {
            text-decoration: underline;
        }

        main {
            padding: 4rem 2rem;
        }

