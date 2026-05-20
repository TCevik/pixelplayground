<?php
require_once 'config.php';
session_start();

if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = $_POST["password"];

    if (empty($username) || empty($password)) {
        $error = "Vul alle velden in.";
    } else {
        $stmt = $conn->prepare("SELECT id, username, password FROM gebruikers WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            if (password_verify($password, $row['password'])) {
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['username'] = $row['username'];
                
                // Set a cookie so JS knows we are logged in (optional, but good for UI changes if needed)
                setcookie("logged_in", "true", time() + (86400 * 30), "/");

                header("Location: index.php");
                exit();
            } else {
                $error = "Ongeldig wachtwoord.";
            }
        } else {
            $error = "Gebruikersnaam niet gevonden.";
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PixelPlayground - Inloggen</title>
    <link rel="stylesheet" href="style/style.css">
</head>
<body>
    <?php include 'header.php'; ?>
    <main>
        <section class="pp-hero-section" style="max-width: 500px; padding: 2rem;">
            <h2>Inloggen</h2>
            
            <?php if ($error): ?>
                <p style="color: #ff4757; background: rgba(0,0,0,0.5); padding: 10px; border-radius: 5px;"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>

            <form method="POST" style="display: flex; flex-direction: column; gap: 15px;">
                <input type="text" name="username" placeholder="Gebruikersnaam" required style="padding: 10px; border-radius: 5px; border: none; font-size: 1rem;">
                <input type="password" name="password" placeholder="Wachtwoord" required style="padding: 10px; border-radius: 5px; border: none; font-size: 1rem;">
                <button type="submit" class="pp-btn-cta" style="border: none; cursor: pointer; width: 100%;">Inloggen</button>
            </form>
            
            <p style="margin-top: 20px;">Nog geen account? <a href="register.php" style="color: var(--accent-color);">Registreer hier!</a></p>
        </section>
    </main>
</body>
</html>
