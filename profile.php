<?php
require_once 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
    $old_pass = $_POST['old_password'];
    $new_pass = $_POST['new_password'];
    $conf_pass = $_POST['confirm_password'];

    $stmt = $conn->prepare("SELECT password FROM gebruikers WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    if (password_verify($old_pass, $row['password'])) {
        if ($new_pass === $conf_pass) {
            $hashed = password_hash($new_pass, PASSWORD_DEFAULT);
            $update = $conn->prepare("UPDATE gebruikers SET password = ? WHERE id = ?");
            $update->bind_param("si", $hashed, $user_id);
            if ($update->execute()) {
                $msg = "<p style='color: green;'>Wachtwoord succesvol gewijzigd!</p>";
            } else {
                $msg = "<p style='color: red;'>Fout bij opslaan.</p>";
            }
        } else {
            $msg = "<p style='color: red;'>Nieuwe wachtwoorden komen niet overeen.</p>";
        }
    } else {
        $msg = "<p style='color: red;'>Oud wachtwoord is onjuist.</p>";
    }
}

// Fetch badges
$badges = [];
$badge_stmt = $conn->prepare("SELECT badge_name FROM badges WHERE user_id = ?");
$badge_stmt->bind_param("i", $user_id);
$badge_stmt->execute();
$b_res = $badge_stmt->get_result();
while ($b = $b_res->fetch_assoc()) {
    $badges[] = $b['badge_name'];
}

// Fetch highscores
$highscores = [];
$score_stmt = $conn->prepare("SELECT game_name, score, created_at FROM highscores WHERE username = ? ORDER BY score DESC LIMIT 10");
$score_stmt->bind_param("s", $username);
$score_stmt->execute();
$s_res = $score_stmt->get_result();
while ($s = $s_res->fetch_assoc()) {
    $highscores[] = $s;
}

?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profiel - <?php echo htmlspecialchars($username); ?></title>
    <link rel="stylesheet" href="style/style.css">
    <style>
        .profile-container { max-width: 800px; margin: 2rem auto; background: var(--card-bg); padding: 2rem; border-radius: 10px; }
        .badge { display: inline-block; background: gold; color: black; padding: 5px 10px; border-radius: 20px; font-weight: bold; margin: 5px; }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    <main>
        <div class="profile-container">
            <h2>Welkom, <?php echo htmlspecialchars($username); ?>!</h2>
            <hr>
            
            <h3>Wachtwoord Wijzigen</h3>
            <?php echo $msg; ?>
            <form method="POST" style="display: flex; flex-direction: column; gap: 10px; max-width: 300px;">
                <input type="password" name="old_password" placeholder="Huidig Wachtwoord" required style="padding: 8px;">
                <input type="password" name="new_password" placeholder="Nieuw Wachtwoord" required style="padding: 8px;">
                <input type="password" name="confirm_password" placeholder="Bevestig Nieuw" required style="padding: 8px;">
                <button type="submit" name="change_password" class="pp-btn-cta" style="margin-top:0;">Opslaan</button>
            </form>

            <hr style="margin: 2rem 0;">
            
            <h3>Jouw Badges</h3>
            <?php if (empty($badges)): ?>
                <p>Nog geen badges verdiend. Ga spellen spelen of voeg vrienden toe!</p>
            <?php else: ?>
                <?php foreach ($badges as $badge): ?>
                    <span class="badge">🏆 <?php echo htmlspecialchars($badge); ?></span>
                <?php endforeach; ?>
            <?php endif; ?>

            <hr style="margin: 2rem 0;">

            <h3>Jouw Highscores</h3>
            <?php if (empty($highscores)): ?>
                <p>Je hebt nog geen highscores.</p>
            <?php else: ?>
                <table style="width: 100%; text-align: left; border-collapse: collapse;">
                    <tr style="border-bottom: 1px solid var(--text-color);">
                        <th>Game</th><th>Score</th><th>Datum</th>
                    </tr>
                    <?php foreach ($highscores as $score): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($score['game_name']); ?></td>
                            <td><?php echo (int)$score['score']; ?></td>
                            <td><?php echo date('d-m-Y', strtotime($score['created_at'])); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>
