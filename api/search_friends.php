<?php
require_once '../config.php';
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode([]);
    exit();
}

if (isset($_GET['q'])) {
    $q = $_GET['q'] . '%'; // begins with
    $user_id = $_SESSION['user_id'];
    
    // Zoek gebruikers die niet onszelf zijn en waar we nog geen vrienden mee zijn/verzoek open hebben
    $stmt = $conn->prepare("
        SELECT id, username FROM gebruikers 
        WHERE username LIKE ? AND id != ?
        AND id NOT IN (
            SELECT friend_id FROM vrienden WHERE user_id = ?
            UNION 
            SELECT user_id FROM vrienden WHERE friend_id = ?
        )
        LIMIT 10
    ");
    $stmt->bind_param("siii", $q, $user_id, $user_id, $user_id);
    $stmt->execute();
    $res = $stmt->get_result();
    
    $users = [];
    while ($row = $res->fetch_assoc()) {
        $users[] = $row;
    }
    
    echo json_encode($users);
} else {
    echo json_encode([]);
}
?>
