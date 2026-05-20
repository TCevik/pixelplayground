<?php
require_once '../config.php';
session_start();

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if (!$data) {
    echo json_encode(['success' => false, 'error' => 'Geen data ontvangen']);
    exit();
}

$game_name = $data['game_name'] ?? '';
$score = (int)($data['score'] ?? 0);
$username = 'anoniem';
$user_id = null;

if (isset($_SESSION['user_id'])) {
    $username = $_SESSION['username'];
    $user_id = $_SESSION['user_id'];
}

if (empty($game_name)) {
    echo json_encode(['success' => false, 'error' => 'Geen game opgegeven']);
    exit();
}

$stmt = $conn->prepare("INSERT INTO highscores (game_name, score, username) VALUES (?, ?, ?)");
$stmt->bind_param("sis", $game_name, $score, $username);

if ($stmt->execute()) {
    // Check for badges if logged in
    if ($user_id) {
        // Badge for playing first game
        $badge_check = $conn->prepare("SELECT id FROM badges WHERE user_id = ? AND badge_name = 'First Blood'");
        $badge_check->bind_param("i", $user_id);
        $badge_check->execute();
        if ($badge_check->get_result()->num_rows === 0) {
            $insert_badge = $conn->prepare("INSERT INTO badges (user_id, badge_name) VALUES (?, 'First Blood')");
            $insert_badge->bind_param("i", $user_id);
            $insert_badge->execute();
        }
    }
    
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Fout bij opslaan in database']);
}
?>
