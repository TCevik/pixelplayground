const board = document.getElementById('board');
const message = document.getElementById('message');

const wordList = ['PIXEL', 'GAMES', 'SCORE', 'BOARD', 'MOUSE', 'CLOCK', 'WATER', 'NIGHT', 'LIGHT', 'SUPER'];
let targetWord = "";
let currentRow = 0;
let currentTile = 0;
let isGameOver = false;

let guesses = [
    ['', '', '', '', ''],
    ['', '', '', '', ''],
    ['', '', '', '', ''],
    ['', '', '', '', ''],
    ['', '', '', '', ''],
    ['', '', '', '', '']
];

// Initialize Board UI
function initBoard() {
    board.innerHTML = '';
    for (let r = 0; r < 6; r++) {
        let rowDiv = document.createElement('div');
        rowDiv.classList.add('wordle-row');
        rowDiv.id = `row-${r}`;
        for (let c = 0; c < 5; c++) {
            let tileDiv = document.createElement('div');
            tileDiv.classList.add('wordle-cell');
            tileDiv.id = `tile-${r}-${c}`;
            rowDiv.appendChild(tileDiv);
        }
        board.appendChild(rowDiv);
    }
}

// Keyboard input setup
document.querySelectorAll('.key-btn').forEach(btn => {
    if (btn.id.startsWith('key-')) {
        btn.addEventListener('click', () => {
            addLetter(btn.innerText);
        });
    }
});

document.addEventListener('keydown', (e) => {
    if (isGameOver) return;
    if (e.key === 'Enter') submitGuess();
    if (e.key === 'Backspace') deleteLetter();
    if (/^[a-zA-Z]$/.test(e.key)) {
        addLetter(e.key.toUpperCase());
    }
});

function addLetter(letter) {
    if (isGameOver) return;
    if (currentTile < 5 && currentRow < 6) {
        guesses[currentRow][currentTile] = letter;
        const tile = document.getElementById(`tile-${currentRow}-${currentTile}`);
        tile.innerText = letter;
        currentTile++;
        saveGameState();
    }
}

function deleteLetter() {
    if (isGameOver) return;
    if (currentTile > 0) {
        currentTile--;
        guesses[currentRow][currentTile] = '';
        const tile = document.getElementById(`tile-${currentRow}-${currentTile}`);
        tile.innerText = '';
        saveGameState();
    }
}

function submitGuess() {
    if (isGameOver) return;
    if (currentTile !== 5) {
        message.innerText = 'Woord is te kort!';
        return;
    }

    const guess = guesses[currentRow].join('');
    checkGuess(guess);
}

function checkGuess(guess, isReplay = false) {
    const rowDiv = document.getElementById(`row-${currentRow}`);
    let targetArr = targetWord.split('');
    let guessArr = guess.split('');
    
    // First pass: mark correct
    for (let i = 0; i < 5; i++) {
        const tile = document.getElementById(`tile-${currentRow}-${i}`);
        if (guessArr[i] === targetArr[i]) {
            tile.classList.add('correct');
            document.getElementById(`key-${guessArr[i]}`).classList.add('correct');
            targetArr[i] = null; // consume
            guessArr[i] = null; // consume
        }
    }

    // Second pass: mark present or absent
    for (let i = 0; i < 5; i++) {
        if (guessArr[i] === null) continue; // Already correct

        const tile = document.getElementById(`tile-${currentRow}-${i}`);
        const keyBtn = document.getElementById(`key-${guessArr[i]}`);
        
        let foundIndex = targetArr.indexOf(guessArr[i]);
        if (foundIndex !== -1) {
            tile.classList.add('present');
            if (!keyBtn.classList.contains('correct')) keyBtn.classList.add('present');
            targetArr[foundIndex] = null; // consume
        } else {
            tile.classList.add('absent');
            if (!keyBtn.classList.contains('correct') && !keyBtn.classList.contains('present')) {
                keyBtn.classList.add('absent');
            }
        }
    }

    if (!isReplay) {
        if (guess === targetWord) {
            message.innerText = 'Geweldig! Je hebt het woord geraden!';
            isGameOver = true;
            sendScore(300 - (currentRow * 30)); // Score based on rows used
        } else {
            if (currentRow >= 5) {
                isGameOver = true;
                message.innerText = `Game Over! Het woord was ${targetWord}`;
            } else {
                currentRow++;
                currentTile = 0;
            }
        }
        saveGameState();
    }
}

function sendScore(score) {
    fetch('api/save_score.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ game_name: 'Wordle', score: score })
    });
}

function loadGameState() {
    initBoard();
    const saved = localStorage.getItem('wordleState');
    if (saved) {
        const state = JSON.parse(saved);
        targetWord = state.targetWord;
        guesses = state.guesses;
        currentRow = state.currentRow;
        currentTile = state.currentTile;
        isGameOver = state.isGameOver;
        
        // Replay visually
        for (let r = 0; r < 6; r++) {
            let guess = guesses[r].join('');
            for (let c = 0; c < 5; c++) {
                if(guesses[r][c] !== '') {
                    document.getElementById(`tile-${r}-${c}`).innerText = guesses[r][c];
                }
            }
            if (r < currentRow || (isGameOver && guess !== '')) {
                const tempRow = currentRow; // cache current row to fake it for checking
                currentRow = r;
                checkGuess(guess, true);
                currentRow = tempRow;
            }
        }
        
        if (isGameOver) {
            if (guesses[currentRow > 5 ? 5 : (currentRow-1 === -1 ? 0 : currentRow-1)].join('') === targetWord || guesses[currentRow].join('') === targetWord) {
                message.innerText = 'Geweldig! Je hebt het woord geraden!';
            } else {
                message.innerText = `Game Over! Het woord was ${targetWord}`;
            }
        }
    } else {
        resetGame();
    }
}

function saveGameState() {
    localStorage.setItem('wordleState', JSON.stringify({
        targetWord, guesses, currentRow, currentTile, isGameOver
    }));
}

function resetGame() {
    targetWord = wordList[Math.floor(Math.random() * wordList.length)];
    guesses = [
        ['', '', '', '', ''],
        ['', '', '', '', ''],
        ['', '', '', '', ''],
        ['', '', '', '', ''],
        ['', '', '', '', ''],
        ['', '', '', '', '']
    ];
    currentRow = 0;
    currentTile = 0;
    isGameOver = false;
    message.innerText = 'Raad het woord in 6 beurten!';
    
    // reset UI keys
    document.querySelectorAll('.key-btn').forEach(btn => {
        btn.classList.remove('correct', 'present', 'absent');
    });
    
    initBoard();
    saveGameState();
}

function giveUp() {
    localStorage.removeItem('wordleState');
    resetGame();
}

document.addEventListener('DOMContentLoaded', loadGameState);
