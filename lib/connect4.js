const cells = document.querySelectorAll('.c4-cell');
const message = document.getElementById('message');
let board = Array(42).fill('');
let currentPlayer = 'red'; // red or yellow
let gameActive = true;
let gameMode = 1; // 1 = vs AI, 2 = 2 Players

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
        gameMode = state.gameMode !== undefined ? state.gameMode : 1;
        
        const radio = document.querySelector(`input[name="gameMode"][value="${gameMode}"]`);
        if (radio) radio.checked = true;

        updateBoardUI();
        if (gameActive) message.innerText = `Jouw beurt (${currentPlayer === 'red' ? 'Rood' : 'Geel'})`;
        else checkWin(true);
    }
}

function saveGameState() {
    localStorage.setItem('connect4State', JSON.stringify({ board, currentPlayer, gameActive, gameMode }));
}

function updateBoardUI() {
    cells.forEach((cell, index) => {
        cell.className = 'c4-cell'; // reset
        if (board[index] !== '') {
            cell.classList.add(board[index]);
        }
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
    board = Array(42).fill('');
    currentPlayer = 'red';
    gameActive = true;
    message.innerText = `Jouw beurt (Rood)`;
    updateBoardUI();
    saveGameState();
}

function handleCellClick(e) {
    if (!gameActive) return;
    if (gameMode === 1 && currentPlayer === 'yellow') return;
    
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
        
        if (gameMode === 1 && currentPlayer === 'yellow' && gameActive) {
            setTimeout(makeAIMove, 500);
        }
    }
}

function makeAIMove() {
    if (!gameActive || currentPlayer !== 'yellow') return;
    
    const bestCol = getConnect4AIMove();
    if (bestCol !== -1) {
        let targetIndex = -1;
        for (let r = rows - 1; r >= 0; r--) {
            const checkIndex = r * cols + bestCol;
            if (board[checkIndex] === '') {
                targetIndex = checkIndex;
                break;
            }
        }
        
        if (targetIndex !== -1) {
            board[targetIndex] = 'yellow';
            updateBoardUI();
            
            if (!checkWin()) {
                currentPlayer = 'red';
                message.innerText = `Jouw beurt (Rood)`;
                saveGameState();
            }
        }
    }
}

function getConnect4AIMove() {
    // 1. Can we win in one move?
    for (let c = 0; c < cols; c++) {
        const r = getLowestEmptyRow(board, c);
        if (r !== -1) {
            const idx = r * cols + c;
            board[idx] = 'yellow';
            const wins = checkBoardWinForPlayer(board, 'yellow');
            board[idx] = '';
            if (wins) return c;
        }
    }

    // 2. Can player win in one move? (Block them!)
    for (let c = 0; c < cols; c++) {
        const r = getLowestEmptyRow(board, c);
        if (r !== -1) {
            const idx = r * cols + c;
            board[idx] = 'red';
            const wins = checkBoardWinForPlayer(board, 'red');
            board[idx] = '';
            if (wins) return c;
        }
    }

    // 3. Avoid giving the player a win on the next turn!
    const safeCols = [];
    const validCols = getValidCols();
    for (let c of validCols) {
        const r = getLowestEmptyRow(board, c);
        if (r !== -1) {
            const idx = r * cols + c;
            board[idx] = 'yellow';
            
            let opponentCanWin = false;
            const rAbove = getLowestEmptyRow(board, c);
            if (rAbove !== -1) {
                const idxAbove = rAbove * cols + c;
                board[idxAbove] = 'red';
                if (checkBoardWinForPlayer(board, 'red')) {
                    opponentCanWin = true;
                }
                board[idxAbove] = '';
            }
            
            board[idx] = '';
            if (!opponentCanWin) {
                safeCols.push(c);
            }
        }
    }

    const candidateCols = safeCols.length > 0 ? safeCols : validCols;

    // 4. Prefer center columns
    const preference = [3, 2, 4, 1, 5, 0, 6];
    for (let prefCol of preference) {
        if (candidateCols.includes(prefCol)) {
            return prefCol;
        }
    }

    if (candidateCols.length > 0) {
        return candidateCols[0];
    }
    
    return -1;
}

function getLowestEmptyRow(tempBoard, col) {
    for (let r = rows - 1; r >= 0; r--) {
        if (tempBoard[r * cols + col] === '') {
            return r;
        }
    }
    return -1;
}

function getValidCols() {
    const list = [];
    for (let c = 0; c < cols; c++) {
        if (board[c] === '') list.push(c);
    }
    return list;
}

function checkBoardWinForPlayer(tempBoard, player) {
    // Horizontal
    for (let r = 0; r < rows; r++) {
        for (let c = 0; c < cols - 3; c++) {
            const i = r * cols + c;
            if (tempBoard[i] === player && tempBoard[i+1] === player && tempBoard[i+2] === player && tempBoard[i+3] === player) return true;
        }
    }
    // Vertical
    for (let c = 0; c < cols; c++) {
        for (let r = 0; r < rows - 3; r++) {
            const i = r * cols + c;
            if (tempBoard[i] === player && tempBoard[i+cols] === player && tempBoard[i+cols*2] === player && tempBoard[i+cols*3] === player) return true;
        }
    }
    // Diagonal Right
    for (let r = 0; r < rows - 3; r++) {
        for (let c = 0; c < cols - 3; c++) {
            const i = r * cols + c;
            if (tempBoard[i] === player && tempBoard[i+cols+1] === player && tempBoard[i+(cols+1)*2] === player && tempBoard[i+(cols+1)*3] === player) return true;
        }
    }
    // Diagonal Left
    for (let r = 0; r < rows - 3; r++) {
        for (let c = 3; c < cols; c++) {
            const i = r * cols + c;
            if (tempBoard[i] === player && tempBoard[i+cols-1] === player && tempBoard[i+(cols-1)*2] === player && tempBoard[i+(cols-1)*3] === player) return true;
        }
    }
    return false;
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
        if (!recheck && winner === 'red' && gameMode === 1) {
            sendScore(200); // 200 points for red win
        }
        return true;
    }

    if (!board.includes('')) {
        message.innerText = "Gelijkspel!";
        gameActive = false;
        saveGameState();
        if (!recheck && gameMode === 1) sendScore(50);
        return true;
    }

    return false;
}

cells.forEach(cell => cell.addEventListener('click', handleCellClick));
document.addEventListener('DOMContentLoaded', loadGameState);
