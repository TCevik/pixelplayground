<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PixelPlayground</title>
    <link rel="stylesheet" href="style/style.css">
</head>
<body>
    <?php include 'header.php'; ?>

    <main id="pp-main-content">
        <section class="pp-hero-section">
            <h1>Welkom bij Pixelplayground</h1>
            <p>De ultieme plek voor klassieke games, uitdagingen en de hoogste scores!</p>
            <a href="games.php" class="pp-btn-cta">Start met Spelen!</a>
        </section>

        <section class="pp-games-slideshow-section">
            <h2>Onze Games</h2>
            <div class="pp-slideshow-container">
                <div class="pp-slide pp-fade">
                    <img src="images/tic_tac_toe.png" alt="Tic Tac Toe">
                    <div class="pp-slide-text">Tic Tac Toe</div>
                </div>
                <div class="pp-slide pp-fade">
                    <img src="images/hangman.png" alt="Galgje">
                    <div class="pp-slide-text">Galgje</div>
                </div>
                <div class="pp-slide pp-fade">
                    <img src="images/wordle.png" alt="Wordle">
                    <div class="pp-slide-text">Wordle</div>
                </div>
                <div class="pp-slide pp-fade">
                    <img src="images/connect4.png" alt="4 op een rij">
                    <div class="pp-slide-text">4 op een rij</div>
                </div>
                <div class="pp-slide pp-fade">
                    <img src="images/flappybird.png" alt="Flappybird">
                    <div class="pp-slide-text">Flappybird</div>
                </div>
                <div class="pp-slide pp-fade">
                    <img src="images/pacman.png" alt="Pac Man">
                    <div class="pp-slide-text">Pac Man</div>
                </div>

                <a class="pp-prev" onclick="ppPlusSlides(-1)">&#10094;</a>
                <a class="pp-next" onclick="ppPlusSlides(1)">&#10095;</a>
            </div>
            <br>
            <div style="text-align:center">
                <span class="pp-dot" onclick="ppCurrentSlide(1)"></span> 
                <span class="pp-dot" onclick="ppCurrentSlide(2)"></span> 
                <span class="pp-dot" onclick="ppCurrentSlide(3)"></span> 
                <span class="pp-dot" onclick="ppCurrentSlide(4)"></span> 
                <span class="pp-dot" onclick="ppCurrentSlide(5)"></span> 
                <span class="pp-dot" onclick="ppCurrentSlide(6)"></span> 
            </div>
        </section>

        <section class="pp-features-section">
            <h2>Waarom PixelPlayground?</h2>
            <div class="pp-features-grid">
                <div class="pp-feature-card">
                    <div class="pp-feature-icon">🎮</div>
                    <h3>Klassieke Retro Games</h3>
                    <p>Geniet van de beste arcade klassiekers rechtstreeks in je browser, van Pac Man tot Flappybird.</p>
                </div>
                <div class="pp-feature-card">
                    <div class="pp-feature-icon">🏆</div>
                    <h3>Strijd voor Highscores</h3>
                    <p>Verbeter je vaardigheden, verdien punten en beland bovenaan het wereldwijde leaderboard.</p>
                </div>
                <div class="pp-feature-card">
                    <div class="pp-feature-icon">⚡</div>
                    <h3>Speel Direct</h3>
                    <p>Geen downloads of installaties nodig. Maak simpelweg een account aan en speel direct!</p>
                </div>
            </div>
        </section>
    </main>

    <script src="lib/script.js"></script>
</body>
</html>