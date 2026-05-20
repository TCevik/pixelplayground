// Dummy data voor de 5 games
const dummyScores = {
    1: [
        { name: "ProGamer99", score: 2500 },
        { name: "ShadowNinja", score: 2100 },
        { name: "PixelMaster", score: 1850 }
    ],
    2: [
        { name: "SpeedRunner", score: 5430 },
        { name: "LootGoblin", score: 4900 },
        { name: "NoobSaibot", score: 3200 }
    ],
    3: [
        { name: "CyberKnight", score: 9999 },
        { name: "GlitchArt", score: 8500 },
        { name: "RetroFan", score: 7200 }
    ],
    4: [
        { name: "MatrixCode", score: 150 },
        { name: "EchoVibe", score: 135 },
        { name: "AlphaWolf", score: 110 }
    ],
    5: [
        { name: "VictoryRoyale", score: 880 },
        { name: "ChugJug", score: 740 },
        { name: "BushCamper", score: 620 }
    ]
};

function switchGame(gameId) {
    
    const buttons = document.querySelectorAll('.game-btn');
    buttons.forEach((btn, index) => {
        if (index === (gameId - 1)) {
            btn.classList.add('active');
        } else {
            btn.classList.remove('active');
        }
    });

    
    document.getElementById('current-game-title').innerText = `Leaderboard: Game ${gameId}`;

    
    const scores = dummyScores[gameId];
    const tbody = document.getElementById('leaderboard-data');
    
    
    tbody.innerHTML = "";

    
    scores.forEach((player, index) => {
        const row = `
            <tr>
                <td class="rank">#${index + 1}</td>
                <td>${player.name}</td>
                <td>${player.score}</td>
            </tr>
        `;
        tbody.innerHTML += row;
    });
}

// Laad standaard Game 1 in als de pagina opstart
window.onload = function() {
    switchGame(1);
};  