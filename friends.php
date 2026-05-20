<?php
require_once 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Haal vrienden op
$friends = [];
$f_stmt = $conn->prepare("
    SELECT g.id, g.username 
    FROM vrienden v 
    JOIN gebruikers g ON (v.friend_id = g.id OR v.user_id = g.id) 
    WHERE (v.user_id = ? OR v.friend_id = ?) 
    AND v.status = 'accepted' AND g.id != ?
");
$f_stmt->bind_param("iii", $user_id, $user_id, $user_id);
$f_stmt->execute();
$res = $f_stmt->get_result();
while ($r = $res->fetch_assoc()) $friends[] = $r;

// Haal inkomende verzoeken op
$requests = [];
$r_stmt = $conn->prepare("
    SELECT v.id as request_id, g.username 
    FROM vrienden v 
    JOIN gebruikers g ON v.user_id = g.id 
    WHERE v.friend_id = ? AND v.status = 'pending'
");
$r_stmt->bind_param("i", $user_id);
$r_stmt->execute();
$r_res = $r_stmt->get_result();
while ($r = $r_res->fetch_assoc()) $requests[] = $r;

?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vrienden</title>
    <link rel="stylesheet" href="style/style.css">
    <style>
        .friends-container { max-width: 800px; margin: 2rem auto; background: var(--card-bg); padding: 2rem; border-radius: 10px; }
        .friend-item { display: flex; justify-content: space-between; align-items: center; padding: 10px; border-bottom: 1px solid var(--text-color); }
        .search-results { margin-top: 10px; background: rgba(0,0,0,0.1); border-radius: 5px; }
        .search-item { padding: 10px; display: flex; justify-content: space-between; align-items: center; }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    <main>
        <div class="friends-container">
            <h2>Vrienden Zoeken</h2>
            <input type="text" id="friend-search" placeholder="Zoek op gebruikersnaam..." style="width: 100%; padding: 10px; font-size: 1rem;">
            <div id="search-results" class="search-results"></div>

            <hr style="margin: 2rem 0;">

            <h2>Vriendschapsverzoeken (<?php echo count($requests); ?>)</h2>
            <?php if (empty($requests)): ?>
                <p>Geen openstaande verzoeken.</p>
            <?php else: ?>
                <?php foreach ($requests as $req): ?>
                    <div class="friend-item" id="req-<?php echo $req['request_id']; ?>">
                        <span><?php echo htmlspecialchars($req['username']); ?></span>
                        <div>
                            <button onclick="handleRequest(<?php echo $req['request_id']; ?>, 'accept')" class="pp-btn-cta" style="margin:0; padding: 5px 15px;">Accepteer</button>
                            <button onclick="handleRequest(<?php echo $req['request_id']; ?>, 'decline')" style="background: red; color: white; border: none; padding: 5px 15px; border-radius: 15px; cursor: pointer;">Weiger</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

            <hr style="margin: 2rem 0;">

            <h2>Mijn Vrienden</h2>
            <?php if (empty($friends)): ?>
                <p>Je hebt nog geen vrienden toegevoegd.</p>
            <?php else: ?>
                <?php foreach ($friends as $friend): ?>
                    <div class="friend-item" id="friend-<?php echo $friend['id']; ?>">
                        <a href="profile.php?u=<?php echo htmlspecialchars($friend['username']); ?>" style="color: var(--accent-color); font-weight: bold;"><?php echo htmlspecialchars($friend['username']); ?></a>
                        <button onclick="removeFriend(<?php echo $friend['id']; ?>)" style="background: red; color: white; border: none; padding: 5px 15px; border-radius: 15px; cursor: pointer;">Verwijder</button>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </main>

    <template id="search-result-template">
        <div class="search-item">
            <span class="username"></span>
            <button class="add-btn pp-btn-cta" style="margin:0; padding: 5px 15px;">Toevoegen</button>
        </div>
    </template>

    <script>
        // Realtime vrienden zoeken
        document.getElementById('friend-search').addEventListener('input', function() {
            let query = this.value;
            let resultsDiv = document.getElementById('search-results');
            
            if (query.length < 2) {
                resultsDiv.innerHTML = '';
                return;
            }

            fetch('api/search_friends.php?q=' + encodeURIComponent(query))
                .then(res => res.json())
                .then(data => {
                    resultsDiv.innerHTML = '';
                    let template = document.getElementById('search-result-template');
                    
                    data.forEach(user => {
                        let clone = template.content.cloneNode(true);
                        clone.querySelector('.username').textContent = user.username;
                        let btn = clone.querySelector('.add-btn');
                        btn.onclick = () => addFriend(user.id, user.username);
                        resultsDiv.appendChild(clone);
                    });
                });
        });

        function addFriend(id, name) {
            fetch('api/friend_action.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'action=add&friend_id=' + id
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert('Verzoek naar ' + name + ' gestuurd!');
                    document.getElementById('friend-search').value = '';
                    document.getElementById('search-results').innerHTML = '';
                } else {
                    alert(data.error);
                }
            });
        }

        function handleRequest(reqId, action) {
            if (action === 'decline' && !confirm("Weet je zeker dat je dit verzoek wilt weigeren?")) return;
            
            fetch('api/friend_action.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'action=' + action + '&request_id=' + reqId
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('req-' + reqId).remove();
                    if (action === 'accept') {
                        location.reload(); // Reload to show in friends list
                    }
                }
            });
        }

        function removeFriend(friendId) {
            if (confirm("Weet je zeker dat je deze vriend wilt verwijderen?")) {
                fetch('api/friend_action.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: 'action=remove&friend_id=' + friendId
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        alert("Vriend verwijderd.");
                        document.getElementById('friend-' + friendId).remove();
                    }
                });
            }
        }
    </script>
</body>
</html>
