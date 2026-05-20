const wordDisplay = document.getElementById('word-display');
const keyboardDiv = document.getElementById('keyboard');
const message = document.getElementById('message');
const drawing = document.getElementById('drawing');
const hintText = document.getElementById('hint-text');

let wordList = [];
let currentWord = "";
let currentHint = "";
let guessedLetters = [];
let mistakes = 0;
let gameActive = true;
const maxMistakes = 6;

const hangmanStages = [
`
  +---+
  |   |
      |
      |
      |
      |
=========`,
`
  +---+
  |   |
  O   |
      |
      |
      |
=========`,
`
  +---+
  |   |
  O   |
  |   |
      |
      |
=========`,
`
  +---+
  |   |
  O   |
 /|   |
      |
      |
=========`,
`
  +---+
  |   |
  O   |
 /|\\  |
      |
      |
=========`,
`
  +---+
  |   |
  O   |
 /|\\  |
 /    |
      |
=========`,
`
  +---+
  |   |
  O   |
 /|\\  |
 / \\  |
      |
=========`
];

// Load words from JSON
fetch('lib/woordenlijst.json')
    .then(res => res.json())
    .then(data => {
        wordList = data;
        initKeyboard();
        loadGameState();
    });

function loadGameState() {
    const saved = localStorage.getItem('hangmanState');
    if (saved) {
        const state = JSON.parse(saved);
        currentWord = state.currentWord;
        currentHint = state.currentHint;
        guessedLetters = state.guessedLetters;
        mistakes = state.mistakes;
        gameActive = state.gameActive;
        if (state.hintShown) hintText.innerText = `Hint: ${currentHint}`;
        updateUI();
    } else {
        resetGame();
    }
}

function saveGameState() {
    const hintShown = hintText.innerText !== '';
    localStorage.setItem('hangmanState', JSON.stringify({
        currentWord, currentHint, guessedLetters, mistakes, gameActive, hintShown
    }));
}

function initKeyboard() {
    keyboardDiv.innerHTML = '';
    for (let i = 65; i <= 90; i++) {
        const letter = String.fromCharCode(i);
        const btn = document.createElement('button');
        btn.innerText = letter;
        btn.className = 'key-btn';
        btn.id = `key-${letter}`;
        btn.onclick = () => handleGuess(letter);
        keyboardDiv.appendChild(btn);
    }
}

function getHint() {
    if (gameActive && currentHint) {
        hintText.innerText = `Hint: ${currentHint}`;
        saveGameState();
    }
}

function giveUp() {
    localStorage.removeItem('hangmanState');
    resetGame();
}

function resetGame() {
    if (wordList.length === 0) return;
    const rand = wordList[Math.floor(Math.random() * wordList.length)];
    currentWord = rand.word;
    currentHint = rand.hint;
    guessedLetters = [];
    mistakes = 0;
    gameActive = true;
    hintText.innerText = '';
    message.innerText = 'Kies een letter';
    
    document.querySelectorAll('.key-btn').forEach(btn => btn.disabled = false);
    
    updateUI();
    saveGameState();
}

function handleGuess(letter) {
    if (!gameActive || guessedLetters.includes(letter)) return;

    guessedLetters.push(letter);
    document.getElementById(`key-${letter}`).disabled = true;

    if (!currentWord.includes(letter)) {
        mistakes++;
    }

    updateUI();
    checkWinOrLose();
    saveGameState();
}

function updateUI() {
    // Update word
    let display = "";
    for (let char of currentWord) {
        if (guessedLetters.includes(char)) display += char;
        else display += "_";
    }
    wordDisplay.innerText = display;

    // Update drawing
    drawing.innerText = hangmanStages[mistakes];

    // Update keyboard based on guessed letters (needed for when loading from storage)
    guessedLetters.forEach(letter => {
        const btn = document.getElementById(`key-${letter}`);
        if(btn) btn.disabled = true;
    });
}

function sendScore(score) {
    fetch('api/save_score.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ game_name: 'Galgje', score: score })
    });
}

function checkWinOrLose() {
    let won = true;
    for (let char of currentWord) {
        if (!guessedLetters.includes(char)) won = false;
    }

    if (won) {
        message.innerText = 'Gefeliciteerd! Je hebt het woord geraden!';
        gameActive = false;
        sendScore(150 - (mistakes * 10)); // Score based on fewer mistakes
    } else if (mistakes >= maxMistakes) {
        message.innerText = `Helaas! Het woord was: ${currentWord}`;
        gameActive = false;
    }
}
