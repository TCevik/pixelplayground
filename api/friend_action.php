<?php
require_once '../config.php';
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Niet ingelogd']);
    exit();
}

$user_id = $_SESSION['user_id'];
$action = $_POST['action'] ?? '';

if ($action === 'add' && isset($_POST['friend_id'])) {
    $friend_id = (int)$_POST['friend_id'];
    
    // Controleer of er al een relatie is
    $check = $conn->prepare("SELECT id FROM vrienden WHERE (user_id = ? AND friend_id = ?) OR (user_id = ? AND friend_id = ?)");
    $check->bind_param("iiii", $user_id, $friend_id, $friend_id, $user_id);
    $check->execute();
    if ($check->get_result()->num_rows === 0) {
        $stmt = $conn->prepare("INSERT INTO vrienden (user_id, friend_id, status) VALUES (?, ?, 'pending')");
        $stmt->bind_param("ii", $user_id, $friend_id);
        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Database fout']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Verzoek bestaat al of je bent al vrienden']);
    }
} elseif ($action === 'accept' && isset($_POST['request_id'])) {
    $req_id = (int)$_POST['request_id'];
    $stmt = $conn->prepare("UPDATE vrienden SET status = 'accepted' WHERE id = ? AND friend_id = ?");
    $stmt->bind_param("ii", $req_id, $user_id);
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
        // Controleer evt voor een badge hier
    } else {
        echo json_encode(['success' => false, 'error' => 'Kon niet accepteren']);
    }
} elseif ($action === 'decline' && isset($_POST['request_id'])) {
    $req_id = (int)$_POST['request_id'];
    $stmt = $conn->prepare("DELETE FROM vrienden WHERE id = ? AND friend_id = ?");
    $stmt->bind_param("ii", $req_id, $user_id);
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Kon niet weigeren']);
    }
} elseif ($action === 'remove' && isset($_POST['friend_id'])) {
    $friend_id = (int)$_POST['friend_id'];
    $stmt = $conn->prepare("DELETE FROM vrienden WHERE (user_id = ? AND friend_id = ?) OR (user_id = ? AND friend_id = ?)");
    $stmt->bind_param("iiii", $user_id, $friend_id, $friend_id, $user_id);
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Kon niet verwijderen']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Ongeldige actie']);
}
?>
