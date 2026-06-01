function switchGame(gameName, event) {
    document.getElementById('current-game-title').innerText = 'Leaderboard: ' + gameName;
    
    if (event) {
        document.querySelectorAll('.game-btn').forEach(btn => btn.classList.remove('active', 'pp-btn-login'));
        event.target.classList.add('active', 'pp-btn-login');
    }

    fetch('api/get_scores.php?game=' + encodeURIComponent(gameName))
        .then(res => res.json())
        .then(data => {
            const tbody = document.getElementById('leaderboard-data');
            tbody.innerHTML = '';
            
            if (data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="3">Nog geen scores voor dit spel.</td></tr>';
                return;
            }

            data.forEach(score => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>#${score.rank}</td>
                    <td>${score.username}</td>
                    <td>${score.score}</td>
                `;
                tbody.appendChild(tr);
            });
        });
}

document.addEventListener('DOMContentLoaded', () => switchGame('TicTacToe', null));
