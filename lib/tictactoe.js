const cells = document.querySelectorAll('.cell');
const message = document.getElementById('message');
let board = ['', '', '', '', '', '', '', '', ''];
let currentPlayer = 'X';
let gameActive = true;
let gameMode = 1; // 1 = vs AI, 2 = 2 Players

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
        gameMode = state.gameMode !== undefined ? state.gameMode : 1;
        
        // Update radio button
        const radio = document.querySelector(`input[name="gameMode"][value="${gameMode}"]`);
        if (radio) radio.checked = true;

        updateBoardUI();
        if (gameActive) message.innerText = `Jouw beurt (${currentPlayer})`;
        else checkWin(true); // Re-check win to show message
    }
}

function saveGameState() {
    localStorage.setItem('tictactoeState', JSON.stringify({ board, currentPlayer, gameActive, gameMode }));
}

function updateBoardUI() {
    cells.forEach((cell, index) => {
        cell.innerText = board[index];
    });
}

function changeMode() {
    const radios = document.getElementsByName('gameMode');
    for (let r of radios) {
        if (r.checked) gameMode = parseInt(r.value);
    }
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
    if (gameMode === 1 && currentPlayer === 'O') return;
    
    const cell = e.target;
    const index = cell.getAttribute('data-index');

    if (board[index] !== '' || !gameActive) return;

    board[index] = currentPlayer;
    updateBoardUI();
    
    if (!checkWin()) {
        currentPlayer = currentPlayer === 'X' ? 'O' : 'X';
        message.innerText = `Jouw beurt (${currentPlayer})`;
        saveGameState();
        
        if (gameMode === 1 && currentPlayer === 'O' && gameActive) {
            setTimeout(makeAIMove, 500); // slight delay for AI
        }
    }
}

function makeAIMove() {
    if (!gameActive || currentPlayer !== 'O') return;
    
    let bestScore = -Infinity;
    let bestMove = -1;

    for (let i = 0; i < 9; i++) {
        if (board[i] === '') {
            board[i] = 'O';
            let score = minimax(board, 0, false);
            board[i] = '';
            if (score > bestScore) {
                bestScore = score;
                bestMove = i;
            }
        }
    }

    if (bestMove !== -1) {
        board[bestMove] = 'O';
        updateBoardUI();
        
        if (!checkWin()) {
            currentPlayer = 'X';
            message.innerText = `Jouw beurt (${currentPlayer})`;
            saveGameState();
        }
    }
}

function minimax(tempBoard, depth, isMaximizing) {
    const winner = checkBoardWinner(tempBoard);
    if (winner === 'O') return 10 - depth;
    if (winner === 'X') return depth - 10;
    if (!tempBoard.includes('')) return 0;

    if (isMaximizing) {
        let bestScore = -Infinity;
        for (let i = 0; i < 9; i++) {
            if (tempBoard[i] === '') {
                tempBoard[i] = 'O';
                let score = minimax(tempBoard, depth + 1, false);
                tempBoard[i] = '';
                bestScore = Math.max(score, bestScore);
            }
        }
        return bestScore;
    } else {
        let bestScore = Infinity;
        for (let i = 0; i < 9; i++) {
            if (tempBoard[i] === '') {
                tempBoard[i] = 'X';
                let score = minimax(tempBoard, depth + 1, true);
                tempBoard[i] = '';
                bestScore = Math.min(score, bestScore);
            }
        }
        return bestScore;
    }
}

function checkBoardWinner(b) {
    for (let i = 0; i < winPatterns.length; i++) {
        const [x, y, z] = winPatterns[i];
        if (b[x] && b[x] === b[y] && b[x] === b[z]) {
            return b[x];
        }
    }
    return null;
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
        if (!recheck && winningPlayer === 'X' && gameMode === 1) {
            // Player wins (assuming player is X)
            sendScore(100); 
        }
        return true;
    }

    if (!board.includes('')) {
        message.innerText = "Gelijkspel!";
        gameActive = false;
        saveGameState();
        if (!recheck && gameMode === 1) sendScore(50); // Draw points
        return true;
    }

    return false;
}

cells.forEach(cell => cell.addEventListener('click', handleCellClick));
document.addEventListener('DOMContentLoaded', loadGameState);
