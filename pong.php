<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pong Game</title>
  <style>
    body {
      margin: 0;
      background: #0e0e0e;
      color: #fff;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      height: 100vh;
      font-family: 'Segoe UI', sans-serif;
    }
    canvas {
      background: linear-gradient(to bottom, #111, #222);
      border: 2px solid #fff;
      box-shadow: 0 0 10px #fff;
    }
    h1 {
      margin-bottom: 20px;
    }
  </style>
</head>
<body>
  <h1>Madara Uchiha should have won the 4th Shinobi World War Man</h1>
  <canvas id="gameCanvas" width="800" height="500"></canvas>
  <script>
    const canvas = document.getElementById("gameCanvas");
    const ctx = canvas.getContext("2d");

    const paddleWidth = 10, paddleHeight = 100;
    const player = { x: 10, y: canvas.height / 2 - paddleHeight / 2, width: paddleWidth, height: paddleHeight, color: '#00ffcc' };
    const cpu = { x: canvas.width - paddleWidth - 10, y: canvas.height / 2 - paddleHeight / 2, width: paddleWidth, height: paddleHeight, color: '#ff3c3c' };
    const ball = { x: canvas.width / 2, y: canvas.height / 2, radius: 8, speed: 5, dx: 5, dy: 5, color: '#fff' };

    let playerScore = 0, cpuScore = 0;
    const maxScore = 5;

    function drawRect(x, y, w, h, color) {
      ctx.fillStyle = color;
      ctx.fillRect(x, y, w, h);
    }

    function drawCircle(x, y, r, color) {
      ctx.fillStyle = color;
      ctx.beginPath();
      ctx.arc(x, y, r, 0, Math.PI * 2);
      ctx.closePath();
      ctx.fill();
    }

    function drawText(text, x, y) {
      ctx.fillStyle = '#fff';
      ctx.font = '32px Arial';
      ctx.fillText(text, x, y);
    }

    function resetBall() {
      ball.x = canvas.width / 2;
      ball.y = canvas.height / 2;
      ball.dx *= -1;
      ball.dy = 5 * (Math.random() > 0.5 ? 1 : -1);
    }

    function update() {
      // Move ball
      ball.x += ball.dx;
      ball.y += ball.dy;

      // Wall collision
      if (ball.y - ball.radius < 0 || ball.y + ball.radius > canvas.height) {
        ball.dy *= -1;
      }

      // Paddle collision
      let playerPaddle = { top: player.y, bottom: player.y + player.height, left: player.x, right: player.x + player.width };
      let cpuPaddle = { top: cpu.y, bottom: cpu.y + cpu.height, left: cpu.x, right: cpu.x + cpu.width };

      if (ball.x - ball.radius < playerPaddle.right && ball.y > playerPaddle.top && ball.y < playerPaddle.bottom) {
        ball.dx *= -1;
      }

      if (ball.x + ball.radius > cpuPaddle.left && ball.y > cpuPaddle.top && ball.y < cpuPaddle.bottom) {
        ball.dx *= -1;
      }

      // Score update
      if (ball.x - ball.radius < 0) {
        cpuScore++;
        resetBall();
      } else if (ball.x + ball.radius > canvas.width) {
        playerScore++;
        resetBall();
      }

      // CPU AI
      if (cpu.y + cpu.height / 2 < ball.y) {
        cpu.y += 4;
      } else {
        cpu.y -= 4;
      }

      // Boundaries
      cpu.y = Math.max(Math.min(cpu.y, canvas.height - cpu.height), 0);
      player.y = Math.max(Math.min(player.y, canvas.height - player.height), 0);
    }

    function render() {
      drawRect(0, 0, canvas.width, canvas.height, '#111');

      drawText(`${playerScore}`, canvas.width / 4, 50);
      drawText(`${cpuScore}`, 3 * canvas.width / 4, 50);

      drawRect(player.x, player.y, player.width, player.height, player.color);
      drawRect(cpu.x, cpu.y, cpu.width, cpu.height, cpu.color);
      drawCircle(ball.x, ball.y, ball.radius, ball.color);
    }

    function game() {
      update();
      render();
    }

    canvas.addEventListener("mousemove", (e) => {
      const rect = canvas.getBoundingClientRect();
      player.y = e.clientY - rect.top - player.height / 2;
    });

    setInterval(game, 1000 / 60);
  </script>
</body>
</html>
