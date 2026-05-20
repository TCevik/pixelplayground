const cells = document.querySelectorAll('.cell');
const message = document.getElementById('message');
let board = ['', '', '', '', '', '', '', '', ''];
let currentPlayer = 'X';
let gameActive = true;

const winPatterns = [
    [0,1,2], [3,4,5], [6,7,8], // Rows
    [0,3,6], [1,4,7], [2,5,8], // Cols
    [0,4,8], [2,4,6] // Diags
];

// Load from LocalStorage
function loadGameState() {
    const savedState = localStorage.getItem('tictactoeState');
    if (savedState) {
        const state = JSON.parse(savedState);
        board = state.board;
        currentPlayer = state.currentPlayer;
        gameActive = state.gameActive;
        updateBoardUI();
        if (gameActive) message.innerText = `Jouw beurt (${currentPlayer})`;
        else checkWin(true); // Re-check win to show message
    }
}

function saveGameState() {
    localStorage.setItem('tictactoeState', JSON.stringify({ board, currentPlayer, gameActive }));
}

function updateBoardUI() {
    cells.forEach((cell, index) => {
        cell.innerText = board[index];
    });
}

function giveUp() {
    localStorage.removeItem('tictactoeState');
    resetGame();
}

function resetGame() {
    board = ['', '', '', '', '', '', '', '', ''];
    currentPlayer = 'X';
    gameActive = true;
    message.innerText = `Jouw beurt (${currentPlayer})`;
    updateBoardUI();
    saveGameState();
}

function handleCellClick(e) {
    const cell = e.target;
    const index = cell.getAttribute('data-index');

    if (board[index] !== '' || !gameActive) return;

    board[index] = currentPlayer;
    updateBoardUI();
    
    if (!checkWin()) {
        currentPlayer = currentPlayer === 'X' ? 'O' : 'X';
        message.innerText = `Jouw beurt (${currentPlayer})`;
        saveGameState();
    }
}

function sendScore(score) {
    fetch('api/save_score.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ game_name: 'TicTacToe', score: score })
    }).then(res => res.json()).then(data => {
        if(data.success) console.log('Score saved!');
    });
}

function checkWin(recheck = false) {
    let roundWon = false;
    let winningPlayer = '';

    for (let i = 0; i < winPatterns.length; i++) {
        const [a, b, c] = winPatterns[i];
        if (board[a] && board[a] === board[b] && board[a] === board[c]) {
            roundWon = true;
            winningPlayer = board[a];
            break;
        }
    }

    if (roundWon) {
        message.innerText = `Speler ${winningPlayer} Heeft Gewonnen!`;
        gameActive = false;
        saveGameState();
        if (!recheck && winningPlayer === 'X') {
            // Player wins (assuming player is X)
            sendScore(100); 
        }
        return true;
    }

    if (!board.includes('')) {
        message.innerText = "Gelijkspel!";
        gameActive = false;
        saveGameState();
        if (!recheck) sendScore(50); // Draw points
        return true;
    }

    return false;
}

cells.forEach(cell => cell.addEventListener('click', handleCellClick));
document.addEventListener('DOMContentLoaded', loadGameState);
