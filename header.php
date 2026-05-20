<header id="pp-main-header">
    <a href="index.php" class="pp-logo"><img src="images/logo.png" id="pp-header-logo-img" alt="Logo">
        PixelPlayground</a>
    <nav id="pp-main-nav">
        <div class="pp-nav-links">
            <a href="index.php">Home</a>
            <a href="games.php">Games</a>
            <a href="highscores.php">Highscores</a>
            <a href="profile.php">Profiel</a>
        </div>

        <div class="pp-auth-buttons">
            <button id="theme-toggle" class="pp-btn-auth" style="background: none; border: 1px solid var(--nav-text); color: var(--nav-text); cursor: pointer;">🌙</button>
            <?php if(isset($_SESSION['user_id'])): ?>
                <a href="logout.php" class="pp-btn-auth pp-btn-register">Uitloggen</a>
            <?php else: ?>
                <a href="login.php" class="pp-btn-auth pp-btn-login">Inloggen</a>
                <a href="register.php" class="pp-btn-auth pp-btn-register">Registreren</a>
            <?php endif; ?>
        </div>
    </nav>
</header>
<script src="lib/script.js" defer></script>