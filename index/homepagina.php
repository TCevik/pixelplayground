<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PixelPlayground</title>
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #1a1a2e;
            color: #e0e0e0;
        }

        header {
            background-color: #16213e;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #ff4757;
        }

        .logo {
            font-size: 1.5rem;
            font-weight: bold;
            color: #ff4757;
            text-decoration: none;
        }

        nav {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .nav-links {
            display: flex;
            gap: 15px;
        }

        .nav-links a {
            text-decoration: none;
            color: #e0e0e0;
            font-weight: 500;
            transition: color 0.2s;
        }

        .nav-links a:hover {
            color: #ff4757;
        }

        .auth-buttons {
            display: flex;
            gap: 10px;
        }

        .btn-auth {
            text-decoration: none;
            padding: 8px 15px;
            border-radius: 5px;
            font-weight: 500;
            transition: background-color 0.2s;
        }

        .btn-login {
            background-color: #ff4757;
            color: white;
        }

        .btn-login:hover {
            background-color: #ff6b81;
        }

        .btn-register {
            background-color: #0f3460;
            color: white;
            border: 1px solid #0f3460;
        }

        .btn-register:hover {
            background-color: #16213e;
            border-color: #ff4757;
        }

        main {
            padding: 4rem 2rem;
            text-align: center;
        }

        .hero {
            max-width: 800px;
            margin: 0 auto;
            padding: 3rem 2rem;
            background: linear-gradient(135deg, #0f3460, #ff4757);
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.4);
        }

        .hero h1 {
            margin-top: 0;
            font-size: 2.5rem;
            color: white;
        }

        .hero p {
            font-size: 1.2rem;
            color: #f0f0f0;
        }
    </style>
</head>
<body>

    <header>
        <a href="index.php" class="logo">🎮 PixelPlayground</a>
        <nav>
            <div class="nav-links">
                <a href="index.php">Home</a>
                <a href="games.php">Games</a>
                <a href="highscores.php">Highscores</a>
                <a href="profile.php">Profiel</a>
            </div>
            
            <div class="auth-buttons">
                <a href="login.php" class="btn-auth btn-login">Inloggen</a>
                <a href="register.php" class="btn-auth btn-register">Registreren</a>
            </div>
        </nav>
    </header>

    <main>
        <section class="hero">
            <h1>Welkom bij Pixelplayground</h1>
            <p>De ultieme plek voor klassieke games, uitdagingen en de hoogste scores!</p>
        </section>
    </main>

</body>
</html>