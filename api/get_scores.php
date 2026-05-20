<?php
require_once '../config.php';
header('Content-Type: application/json');

$game_name = $_GET['game'] ?? '';

if (empty($game_name)) {
    echo json_encode([]);
    exit();
}

$stmt = $conn->prepare("SELECT username, score FROM highscores WHERE game_name = ? ORDER BY score DESC LIMIT 5");
$stmt->bind_param("s", $game_name);
$stmt->execute();
$res = $stmt->get_result();

$scores = [];
$rank = 1;
while ($row = $res->fetch_assoc()) {
    $row['rank'] = $rank++;
    $scores[] = $row;
}

echo json_encode($scores);
?>
