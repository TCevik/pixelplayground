const cells = document.querySelectorAll('.c4-cell');
const message = document.getElementById('message');
let board = Array(42).fill('');
let currentPlayer = 'red'; // red or yellow
let gameActive = true;

const cols = 7;
const rows = 6;

// Load from LocalStorage
function loadGameState() {
    const savedState = localStorage.getItem('connect4State');
    if (savedState) {
        const state = JSON.parse(savedState);
        board = state.board;
        currentPlayer = state.currentPlayer;
        gameActive = state.gameActive;
        updateBoardUI();
        if (gameActive) message.innerText = `Jouw beurt (${currentPlayer === 'red' ? 'Rood' : 'Geel'})`;
        else checkWin(true);
    }
}

function saveGameState() {
    localStorage.setItem('connect4State', JSON.stringify({ board, currentPlayer, gameActive }));
}

function updateBoardUI() {
    cells.forEach((cell, index) => {
        cell.className = 'c4-cell'; // reset
        if (board[index] !== '') {
            cell.classList.add(board[index]);
        }
    });
}

function giveUp() {
    localStorage.removeItem('connect4State');
    resetGame();
}

function resetGame() {
    board = Array(42).fill('');
    currentPlayer = 'red';
    gameActive = true;
    message.innerText = `Jouw beurt (Rood)`;
    updateBoardUI();
    saveGameState();
}

function handleCellClick(e) {
    if (!gameActive) return;
    
    const index = parseInt(e.target.getAttribute('data-index'));
    const col = index % cols;
    
    // Find lowest available row in this column
    let targetIndex = -1;
    for (let r = rows - 1; r >= 0; r--) {
        const checkIndex = r * cols + col;
        if (board[checkIndex] === '') {
            targetIndex = checkIndex;
            break;
        }
    }

    if (targetIndex === -1) return; // Column full

    board[targetIndex] = currentPlayer;
    updateBoardUI();
    
    if (!checkWin()) {
        currentPlayer = currentPlayer === 'red' ? 'yellow' : 'red';
        message.innerText = `Jouw beurt (${currentPlayer === 'red' ? 'Rood' : 'Geel'})`;
        saveGameState();
    }
}

function sendScore(score) {
    fetch('api/save_score.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ game_name: 'Connect4', score: score })
    }).then(res => res.json()).then(data => {
        if(data.success) console.log('Score saved!');
    });
}

function checkWin(recheck = false) {
    // Check horizontal, vertical, diagonal
    const checkWinLines = () => {
        // Horizontal
        for (let r = 0; r < rows; r++) {
            for (let c = 0; c < cols - 3; c++) {
                const i = r * cols + c;
                if (board[i] && board[i] === board[i+1] && board[i] === board[i+2] && board[i] === board[i+3]) return board[i];
            }
        }
        // Vertical
        for (let c = 0; c < cols; c++) {
            for (let r = 0; r < rows - 3; r++) {
                const i = r * cols + c;
                if (board[i] && board[i] === board[i+cols] && board[i] === board[i+cols*2] && board[i] === board[i+cols*3]) return board[i];
            }
        }
        // Diagonal Right
        for (let r = 0; r < rows - 3; r++) {
            for (let c = 0; c < cols - 3; c++) {
                const i = r * cols + c;
                if (board[i] && board[i] === board[i+cols+1] && board[i] === board[i+(cols+1)*2] && board[i] === board[i+(cols+1)*3]) return board[i];
            }
        }
        // Diagonal Left
        for (let r = 0; r < rows - 3; r++) {
            for (let c = 3; c < cols; c++) {
                const i = r * cols + c;
                if (board[i] && board[i] === board[i+cols-1] && board[i] === board[i+(cols-1)*2] && board[i] === board[i+(cols-1)*3]) return board[i];
            }
        }
        return null;
    };

    const winner = checkWinLines();

    if (winner) {
        message.innerText = `${winner === 'red' ? 'Rood' : 'Geel'} Heeft Gewonnen!`;
        gameActive = false;
        saveGameState();
        if (!recheck && winner === 'red') {
            sendScore(200); // 200 points for red win
        }
        return true;
    }

    if (!board.includes('')) {
        message.innerText = "Gelijkspel!";
        gameActive = false;
        saveGameState();
        if (!recheck) sendScore(50);
        return true;
    }

    return false;
}

cells.forEach(cell => cell.addEventListener('click', handleCellClick));
document.addEventListener('DOMContentLoaded', loadGameState);
