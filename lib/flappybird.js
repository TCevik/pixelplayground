const canvas = document.getElementById('gameCanvas');
const ctx = canvas.getContext('2d');
const message = document.getElementById('message');
const startBtn = document.getElementById('startBtn');

let frames = 0;
let score = 0;
let isGameOver = false;
let isPlaying = false;
let animationId;

// Bird
const bird = {
    x: 50,
    y: 150,
    w: 30,
    h: 30,
    velocity: 0,
    gravity: 0.5,
    jump: -8,
    draw: function() {
        ctx.fillStyle = 'yellow';
        ctx.beginPath();
        ctx.arc(this.x + this.w/2, this.y + this.h/2, this.w/2, 0, Math.PI * 2);
        ctx.fill();
        ctx.fillStyle = 'black';
        ctx.beginPath();
        ctx.arc(this.x + this.w/2 + 5, this.y + this.h/2 - 5, 3, 0, Math.PI * 2);
        ctx.fill();
        ctx.fillStyle = 'orange';
        ctx.fillRect(this.x + this.w/2 + 10, this.y + this.h/2, 10, 5);
    },
    update: function() {
        this.velocity += this.gravity;
        this.y += this.velocity;
        
        // Floor collision
        if (this.y + this.h >= canvas.height) {
            this.y = canvas.height - this.h;
            gameOver();
        }
        // Ceiling collision
        if (this.y <= 0) {
            this.y = 0;
            this.velocity = 0;
        }
    }
};

// Pipes
const pipes = {
    position: [],
    w: 50,
    gap: 120,
    dx: 3,
    draw: function() {
        for(let i=0; i<this.position.length; i++) {
            let p = this.position[i];
            let topYPos = p.y;
            let bottomYPos = p.y + this.gap;
            
            ctx.fillStyle = 'green';
            // Top pipe
            ctx.fillRect(p.x, 0, this.w, topYPos);
            // Bottom pipe
            ctx.fillRect(p.x, bottomYPos, this.w, canvas.height - bottomYPos);
        }
    },
    update: function() {
        // Add new pipe every 100 frames
        if (frames % 100 === 0) {
            this.position.push({
                x: canvas.width,
                y: Math.random() * (canvas.height - this.gap - 100) + 50,
                passed: false
            });
        }
        for(let i=0; i<this.position.length; i++) {
            let p = this.position[i];
            
            // Collision detection
            let bottomPipeYPos = p.y + this.gap;
            
            // Bird hits top pipe
            if (bird.x + bird.w > p.x && bird.x < p.x + this.w && bird.y < p.y) gameOver();
            // Bird hits bottom pipe
            if (bird.x + bird.w > p.x && bird.x < p.x + this.w && bird.y + bird.h > bottomPipeYPos) gameOver();
            
            p.x -= this.dx;
            
            // Score
            if(p.x + this.w < bird.x && !p.passed) {
                score += 10;
                p.passed = true;
                message.innerText = `Score: ${score}`;
            }
            
            // Remove off-screen pipes
            if(p.x + this.w <= 0) {
                this.position.shift();
            }
        }
    }
};

function drawBackground() {
    ctx.fillStyle = '#70c5ce';
    ctx.fillRect(0,0, canvas.width, canvas.height);
    // Draw simple ground moving
    ctx.fillStyle = '#ded895';
    ctx.fillRect(0, canvas.height - 20, canvas.width, 20);
}

function loop() {
    if (!isPlaying) return;
    
    drawBackground();
    pipes.draw();
    pipes.update();
    bird.draw();
    bird.update();
    
    frames++;
    if (!isGameOver) {
        animationId = requestAnimationFrame(loop);
    }
}

function flap() {
    if (isGameOver) return;
    if (!isPlaying) {
        isPlaying = true;
        message.innerText = `Score: ${score}`;
        loop();
    }
    bird.velocity = bird.jump;
}

function sendScore(finalScore) {
    if(finalScore <= 0) return;
    fetch('api/save_score.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ game_name: 'Flappybird', score: finalScore })
    });
}

function gameOver() {
    isGameOver = true;
    isPlaying = false;
    message.innerText = `Game Over! Score: ${score}`;
    sendScore(score);
    startBtn.style.display = 'block';
}

function resetGame() {
    bird.y = 150;
    bird.velocity = 0;
    pipes.position = [];
    score = 0;
    frames = 0;
    isGameOver = false;
    isPlaying = false;
    message.innerText = "Klik of spatie om te starten";
    startBtn.style.display = 'none';
    drawBackground();
    bird.draw();
}

// Input listeners
canvas.addEventListener('mousedown', flap);
document.addEventListener('keydown', (e) => {
    if(e.code === 'Space') {
        e.preventDefault();
        flap();
    }
});
startBtn.addEventListener('click', () => {
    resetGame();
    flap(); // auto start on btn click
});

// Initial draw
resetGame();
