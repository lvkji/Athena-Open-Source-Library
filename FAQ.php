<!DOCTYPE html>
<html lang="en" class="bg-black text-white font-serif">
<head>
  <meta charset="UTF-8" />
  <link rel="icon" type="image/x-icon" href="AthensAcropolis.ico">
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>FAQ - Athena</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    .rainbow-text {
      background: linear-gradient(90deg, #ff6ec4, #7873f5, #4ade80, #facc15, #f472b6);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }
    .faq-box:hover {
      transform: scale(1.03);
      transition: transform 0.3s ease-in-out;
    }
    .tic-box:hover {
      background-color: #3b82f6;
    }
  </style>
</head>
<body class="flex flex-col min-h-screen">

<!-- Header -->
<header class="bg-red-950 py-6">
  <div class="container mx-auto text-center">
    <!-- Centered Title -->
    <h1 class="text-3xl font-bold tracking-wide text-white">Athena - Open Source Library</h1>
    
    <!-- Centered Navigation -->
    <nav class="mt-4 flex justify-center space-x-6">
  <a href="index.php" class="text-white font-semibold hover:underline">Home</a>
  <a href="aboutUs.php" class="text-white font-semibold hover:underline">About Us</a>
  <a href="FAQ.php" class="text-white font-semibold hover:underline">FAQ</a>
</nav>


    <!-- Top-Right Auth Links -->
    <div class="absolute top-12 right-8 space-x-4">
      <a href="login_form.php" class="text-indigo-400 font-semibold hover:underline">Login</a>
      <a href="register.php" class="text-indigo-400 font-semibold hover:underline">Register</a>
    </div>
  </div>
</header>

<!-- Main FAQ -->
<main class="flex-grow bg-black py-16 px-4 text-center">
  <h2 class="text-4xl font-bold mb-12 rainbow-text">Frequently Asked Questions</h2>
  <div class="space-y-8 max-w-4xl mx-auto">

    <?php
    $faqs = [
      "What kind of content can I upload?" => "You can upload non-copyrighted books, academic articles, open-source educational resources, and any original material that contributes to learning and knowledge sharing.",
      "Do I need an account to access materials?" => "No account is needed to browse and search materials, but you must sign up to upload, comment, or verify content.",
      "Who verifies the content?" => "Authors review submitted materials for academic quality. Administrators ensure system integrity and flag inappropriate or low-quality uploads.",
      "How do I become an author or admin?" => "When registering, select your role. Administrators may promote verified authors or trusted users based on contribution and activity.",
      "Is my data secure?" => "Yes! All data is encrypted and stored securely. We prioritize user privacy and information security across our platform."
    ];

    foreach ($faqs as $q => $a) {
      echo "
      <div class='bg-gray-800 p-6 rounded-lg shadow-md faq-box'>
        <h3 class='text-xl font-bold mb-2 rainbow-text'>$q</h3>
        <p class='text-gray-300'>$a</p>
      </div>";
    }
    ?>
  </div>

  <!-- Tic Tac Toe Game -->
  <div class="mt-20 text-center">
    <h3 class="text-3xl font-bold mb-4 rainbow-text">🎮 Take a Break: Play Tic-Tac-Toe!</h3>
    <div id="tic-tac-toe" class="grid grid-cols-3 gap-4 w-48 mx-auto mt-6">
      <?php for ($i = 0; $i < 9; $i++): ?>
        <div onclick="makeMove(this)" class="tic-box w-16 h-16 bg-gray-700 text-white text-3xl flex items-center justify-center rounded cursor-pointer shadow"></div>
      <?php endfor; ?>
    </div>
    <p id="gameStatus" class="mt-4 text-xl text-blue-400 font-semibold"></p>
  </div>
</main>

<!-- Footer -->
<footer class="bg-red-950 text-white text-center py-4">
  <p>&copy; 2025 Athena Library System | All rights reserved</p>
  <p>Contact us at <a href="mailto:support@athena.org" class="underline">support@athena.org</a></p>
</footer>

<script>
  let currentPlayer = 'X';
  let gameOver = false;

  function makeMove(cell) {
    if (gameOver || cell.textContent !== '') return;
    cell.textContent = currentPlayer;
    checkWin();
    currentPlayer = currentPlayer === 'X' ? 'O' : 'X';
  }

  function checkWin() {
    const cells = Array.from(document.querySelectorAll('.tic-box')).map(cell => cell.textContent);
    const winCombos = [
      [0,1,2], [3,4,5], [6,7,8], // rows
      [0,3,6], [1,4,7], [2,5,8], // cols
      [0,4,8], [2,4,6]           // diagonals
    ];

    for (const combo of winCombos) {
      const [a,b,c] = combo;
      if (cells[a] && cells[a] === cells[b] && cells[b] === cells[c]) {
        document.getElementById('gameStatus').textContent = `${cells[a]} wins! 🎉`;
        gameOver = true;
        return;
      }
    }

    if (cells.every(cell => cell)) {
      document.getElementById('gameStatus').textContent = "It's a draw!";
      gameOver = true;
    }
  }
</script>

</body>
</html>
