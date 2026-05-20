<?php
require_once 'config.php';
$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    // Basic PHP Validation
    if (empty($username) || empty($password) || empty($confirm_password)) {
        $error = "Vul alle velden in.";
    } elseif ($password !== $confirm_password) {
        $error = "Wachtwoorden komen niet overeen.";
    } else {
        // Controleer op verboden tekens
        $forbidden_chars = ['}', '{', '[', '*', 'A', '%', ';'];
        $has_forbidden = false;
        foreach ($forbidden_chars as $char) {
            if (strpos($password, $char) !== false || strpos($username, $char) !== false) {
                $has_forbidden = true;
                break;
            }
        }

        if ($has_forbidden) {
            $error = "Verboden tekens gebruikt in gebruikersnaam of wachtwoord.";
        } else {
            // Check of user al bestaat
            $stmt = $conn->prepare("SELECT id FROM gebruikers WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $error = "Deze gebruikersnaam is al in gebruik.";
            } else {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $insert_stmt = $conn->prepare("INSERT INTO gebruikers (username, password) VALUES (?, ?)");
                $insert_stmt->bind_param("ss", $username, $hashed_password);
                if ($insert_stmt->execute()) {
                    $success = "Registratie succesvol! Je kunt nu inloggen.";
                } else {
                    $error = "Er ging iets mis tijdens de registratie.";
                }
                $insert_stmt->close();
            }
            $stmt->close();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PixelPlayground - Registreren</title>
    <link rel="stylesheet" href="style/style.css">
    <script>
        function validateForm(event) {
            const username = document.forms["regForm"]["username"].value;
            const pass = document.forms["regForm"]["password"].value;
            const conf = document.forms["regForm"]["confirm_password"].value;
            const errorDiv = document.getElementById("error-msg");
            
            const forbidden = ['}', '{', '[', '*', 'A', '%', ';'];
            let found = forbidden.filter(char => pass.includes(char) || username.includes(char));

            if (found.length > 0) {
                errorDiv.innerText = "De volgende symbolen zijn niet toegestaan: " + found.join(" ");
                event.preventDefault();
                return false;
            }

            if (pass !== conf) {
                errorDiv.innerText = "Wachtwoorden komen niet overeen.";
                event.preventDefault();
                return false;
            }
            return true;
        }
    </script>
</head>
<body>
    <?php include 'header.php'; ?>
    <main>
        <section class="pp-hero-section" style="max-width: 500px; padding: 2rem;">
            <h2>Account Aanmaken</h2>
            
            <?php if ($error): ?>
                <p style="color: #ff4757; background: rgba(0,0,0,0.5); padding: 10px; border-radius: 5px;"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>
            <?php if ($success): ?>
                <p style="color: #2ecc71; background: rgba(0,0,0,0.5); padding: 10px; border-radius: 5px;"><?php echo htmlspecialchars($success); ?> <a href="login.php" style="color: white;">Login hier</a></p>
            <?php endif; ?>
            
            <p id="error-msg" style="color: #ff4757; font-weight: bold;"></p>

            <form name="regForm" method="POST" onsubmit="validateForm(event)" style="display: flex; flex-direction: column; gap: 15px;">
                <input type="text" name="username" placeholder="Gebruikersnaam" required style="padding: 10px; border-radius: 5px; border: none; font-size: 1rem;">
                <input type="password" name="password" placeholder="Wachtwoord" required style="padding: 10px; border-radius: 5px; border: none; font-size: 1rem;">
                <input type="password" name="confirm_password" placeholder="Bevestig Wachtwoord" required style="padding: 10px; border-radius: 5px; border: none; font-size: 1rem;">
                <button type="submit" class="pp-btn-cta" style="border: none; cursor: pointer; width: 100%;">Registreren</button>
            </form>
            <p style="margin-top: 20px;">Nog geen account? <a href="register.php" style="color: var(--accent-color);">Registreer hier!</a></p>
            <!-- Oops, the text should be 'Al een account? Login hier' because we are on register page -->
            <p style="margin-top: 20px;">Al een account? <a href="login.php" style="color: var(--accent-color);">Login hier!</a></p>
        </section>
    </main>
</body>
</html>
