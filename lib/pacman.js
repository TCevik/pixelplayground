const canvas = document.getElementById('pacCanvas');
const ctx = canvas.getContext('2d');
const message = document.getElementById('message');
const startBtn = document.getElementById('startBtn');

const tileSize = 30;
const cols = canvas.width / tileSize; // 14
const rows = canvas.height / tileSize; // 14

// 1 = Wall, 0 = Dot, 2 = Empty
const mapTemplate = [
    [1,1,1,1,1,1,1,1,1,1,1,1,1,1],
    [1,0,0,0,0,0,1,1,0,0,0,0,0,1],
    [1,0,1,1,1,0,1,1,0,1,1,1,0,1],
    [1,0,1,1,1,0,0,0,0,1,1,1,0,1],
    [1,0,0,0,0,0,2,2,0,0,0,0,0,1],
    [1,1,1,0,1,1,2,2,1,1,0,1,1,1],
    [2,2,1,0,1,2,2,2,2,1,0,1,2,2],
    [1,1,1,0,1,1,1,1,1,1,0,1,1,1],
    [1,0,0,0,0,0,1,1,0,0,0,0,0,1],
    [1,0,1,1,1,0,1,1,0,1,1,1,0,1],
    [1,0,0,0,1,0,0,0,0,1,0,0,0,1],
    [1,1,1,0,1,0,1,1,0,1,0,1,1,1],
    [1,0,0,0,0,0,1,1,0,0,0,0,0,1],
    [1,1,1,1,1,1,1,1,1,1,1,1,1,1]
];

let map = [];
let score = 0;
let isGameOver = false;
let isPlaying = false;
let animationId;
let totalDots = 0;

const pacman = {
    x: 1, y: 1,
    pixelX: 0, pixelY: 0,
    dir: {x: 0, y: 0},
    nextDir: {x: 0, y: 0},
    speed: 2,
    draw: function() {
        ctx.fillStyle = 'yellow';
        ctx.beginPath();
        ctx.arc(this.pixelX + tileSize/2, this.pixelY + tileSize/2, tileSize/2.5, 0.2 * Math.PI, 1.8 * Math.PI);
        ctx.lineTo(this.pixelX + tileSize/2, this.pixelY + tileSize/2);
        ctx.fill();
    },
    update: function() {
        // Snap to grid
        if (this.pixelX % tileSize === 0 && this.pixelY % tileSize === 0) {
            this.x = this.pixelX / tileSize;
            this.y = this.pixelY / tileSize;

            // Eat dot
            if (map[this.y][this.x] === 0) {
                map[this.y][this.x] = 2;
                score += 10;
                totalDots--;
                message.innerText = `Score: ${score}`;
                if (totalDots === 0) winGame();
            }

            // Check if can turn nextDir
            if (this.nextDir.x !== 0 || this.nextDir.y !== 0) {
                let checkX = this.x + this.nextDir.x;
                let checkY = this.y + this.nextDir.y;
                if (checkX >= 0 && checkX < cols && checkY >= 0 && checkY < rows && map[checkY][checkX] !== 1) {
                    this.dir.x = this.nextDir.x;
                    this.dir.y = this.nextDir.y;
                    this.nextDir = {x: 0, y: 0};
                }
            }

            // Stop if hit wall in current dir
            let nextX = this.x + this.dir.x;
            let nextY = this.y + this.dir.y;
            
            // Portal wrapping (optional, simplified)
            if (nextX < 0) { nextX = cols - 1; this.pixelX = nextX * tileSize; }
            if (nextX >= cols) { nextX = 0; this.pixelX = 0; }

            if (map[nextY] && map[nextY][nextX] === 1) {
                this.dir.x = 0;
                this.dir.y = 0;
            }
        }

        this.pixelX += this.dir.x * this.speed;
        this.pixelY += this.dir.y * this.speed;
    }
};

const ghost = {
    x: 7, y: 6,
    pixelX: 7*tileSize, pixelY: 6*tileSize,
    dir: {x: 1, y: 0},
    speed: 1.5,
    draw: function() {
        ctx.fillStyle = 'red';
        ctx.beginPath();
        ctx.arc(this.pixelX + tileSize/2, this.pixelY + tileSize/2, tileSize/2.5, Math.PI, 0);
        ctx.lineTo(this.pixelX + tileSize - 5, this.pixelY + tileSize - 5);
        ctx.lineTo(this.pixelX + 5, this.pixelY + tileSize - 5);
        ctx.fill();
    },
    update: function() {
        if (Math.abs(this.pixelX - Math.round(this.pixelX)) < 1) this.pixelX = Math.round(this.pixelX);
        if (Math.abs(this.pixelY - Math.round(this.pixelY)) < 1) this.pixelY = Math.round(this.pixelY);

        if (this.pixelX % tileSize === 0 && this.pixelY % tileSize === 0) {
            this.x = this.pixelX / tileSize;
            this.y = this.pixelY / tileSize;

            // Simple random AI at intersections
            const possibleDirs = [
                {x: 1, y: 0}, {x: -1, y: 0}, {x: 0, y: 1}, {x: 0, y: -1}
            ].filter(d => {
                let nx = this.x + d.x;
                let ny = this.y + d.y;
                return (nx >= 0 && nx < cols && ny >= 0 && ny < rows && map[ny][nx] !== 1 && !(d.x === -this.dir.x && d.y === -this.dir.y));
            });

            if (possibleDirs.length > 0) {
                this.dir = possibleDirs[Math.floor(Math.random() * possibleDirs.length)];
            } else {
                this.dir = {x: -this.dir.x, y: -this.dir.y}; // Dead end
            }
        }
        this.pixelX += this.dir.x * this.speed;
        this.pixelY += this.dir.y * this.speed;
    }
};

function drawMap() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    for (let r = 0; r < rows; r++) {
        for (let c = 0; c < cols; c++) {
            if (map[r][c] === 1) {
                ctx.fillStyle = '#1919A6';
                ctx.fillRect(c * tileSize, r * tileSize, tileSize, tileSize);
                ctx.strokeStyle = '#fff';
                ctx.strokeRect(c * tileSize, r * tileSize, tileSize, tileSize);
            } else if (map[r][c] === 0) {
                ctx.fillStyle = '#ffb8ae';
                ctx.beginPath();
                ctx.arc(c * tileSize + tileSize/2, r * tileSize + tileSize/2, 3, 0, Math.PI * 2);
                ctx.fill();
            }
        }
    }
}

function checkCollision() {
    let dist = Math.hypot(pacman.pixelX - ghost.pixelX, pacman.pixelY - ghost.pixelY);
    if (dist < tileSize - 5) {
        gameOver();
    }
}

function loop() {
    if (!isPlaying) return;
    
    drawMap();
    pacman.update();
    pacman.draw();
    ghost.update();
    ghost.draw();
    checkCollision();
    
    if (!isGameOver) {
        animationId = requestAnimationFrame(loop);
    }
}

function sendScore(finalScore) {
    if(finalScore <= 0) return;
    fetch('api/save_score.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ game_name: 'PacMan', score: finalScore })
    });
}

function gameOver() {
    isGameOver = true;
    isPlaying = false;
    message.innerText = `Game Over! Score: ${score}`;
    sendScore(score);
    startBtn.style.display = 'block';
}

function winGame() {
    isGameOver = true;
    isPlaying = false;
    message.innerText = `Gefeliciteerd! Alles gegeten! Score: ${score}`;
    sendScore(score);
    startBtn.style.display = 'block';
}

function resetGame() {
    map = JSON.parse(JSON.stringify(mapTemplate));
    totalDots = 0;
    for(let r=0; r<rows; r++) {
        for(let c=0; c<cols; c++) {
            if(map[r][c] === 0) totalDots++;
        }
    }
    
    pacman.x = 1; pacman.y = 1;
    pacman.pixelX = tileSize; pacman.pixelY = tileSize;
    pacman.dir = {x: 0, y: 0}; pacman.nextDir = {x: 0, y: 0};
    
    ghost.x = 7; ghost.y = 6;
    ghost.pixelX = 7*tileSize; ghost.pixelY = 6*tileSize;
    ghost.dir = {x: 1, y: 0};

    score = 0;
    isGameOver = false;
    message.innerText = "Gebruik pijltjes om te starten";
    startBtn.style.display = 'none';
    drawMap();
    pacman.draw();
    ghost.draw();
}

document.addEventListener('keydown', (e) => {
    if (isGameOver) return;
    
    let started = false;
    if (e.key === 'ArrowUp') { pacman.nextDir = {x: 0, y: -1}; started = true; e.preventDefault(); }
    if (e.key === 'ArrowDown') { pacman.nextDir = {x: 0, y: 1}; started = true; e.preventDefault(); }
    if (e.key === 'ArrowLeft') { pacman.nextDir = {x: -1, y: 0}; started = true; e.preventDefault(); }
    if (e.key === 'ArrowRight') { pacman.nextDir = {x: 1, y: 0}; started = true; e.preventDefault(); }
    
    if (started && !isPlaying) {
        isPlaying = true;
        message.innerText = `Score: ${score}`;
        loop();
    }
});

startBtn.addEventListener('click', resetGame);
resetGame();
