<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en" class="bg-black text-white font-serif">

<head>
  <meta charset="UTF-8" />
  <link rel="icon" type="image/x-icon" href="AthensAcropolis.ico">
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Search Books - Athena</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />

  <style>
    /* Hiệu ứng modal và zoomIn đã có, giữ nguyên như bạn đang dùng */
    .alert-overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100vw;
      height: 100vh;
      background-color: rgba(0, 0, 0, 0.5);
      display: flex;
      justify-content: center;
      align-items: center;
      z-index: 9999;
    }
    .alert-box {
      position: relative;
      background-color: #3b2a20;
      border: 2px solid #d1a869;
      border-radius: 8px;
      padding: 20px 40px 30px;
      text-align: center;
      color: #d1b589;
      font-family: "Times New Roman", serif;
      min-width: 280px;
      animation: zoomIn 0.5s ease-out;
    }
    @keyframes zoomIn {
      from {
        transform: scale(0);
        opacity: 0;
      }
      60% {
        transform: scale(1.1);
        opacity: 1;
      }
      to {
        transform: scale(1);
        opacity: 1;
      }
    }
    .alert-header {
      position: absolute;
      top: -15px;
      left: 50%;
      transform: translateX(-50%);
      background-color: #7d3c1d;
      width: 34px;
      height: 34px;
      border: 2px solid #d1a869;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .exclamation-icon {
      color: #d1b589;
      font-weight: bold;
      font-size: 1.2em;
    }
    .alert-message {
      margin: 30px 0 20px;
      font-size: 1.2em;
    }
    .alert-button {
      background-color: #d1b589;
      color: #3b2a20;
      padding: 10px 20px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-weight: bold;
    }
    .alert-button:hover {
      background-color: #c2a378;
    }
  </style>
</head>

<body class="flex flex-col min-h-screen font-serif">

  <header class="bg-red-950 py-6 relative">
    <div class="container mx-auto text-center">
      <h1 class="text-3xl font-bold tracking-wide text-white">Athena - Open Source Library</h1>
      <nav class="mt-4 space-x-6 inline-block">
        <a href="search.php" class="text-white font-semibold hover:underline">Search Library</a>
        <a href="upload.php" class="text-white font-semibold hover:underline">Upload Content</a>
      </nav>
    </div>

    <?php if (!isset($_SESSION['user_id'])): ?>
      <a href="login_form.php"
         class="absolute top-4 right-4 bg-transparent border-2 border-white text-white font-semibold 
                px-4 py-2 rounded transition-colors duration-300 hover:bg-white hover:text-red-950">
        Login
      </a>
    <?php else: ?>
      <div class="absolute top-4 right-4 inline-block">
        <!-- Button Profile -->
        <button id="profileButton"
                class="bg-transparent border-2 border-transparent text-white font-semibold 
                       px-4 py-2 rounded transition-colors duration-300 flex items-center gap-2">
                       <i class="fa-solid fa-user"></i>
        </button>
        <!-- Dropdown (ẩn ban đầu) -->
        <div id="profileDropdown"
             class="origin-top-right absolute right-0 mt-2 w-48 hidden rounded-md shadow-lg 
                    bg-white ring-1 ring-black ring-opacity-5 text-left">
          <div class="py-1" role="menu" aria-orientation="vertical">
            <a href="profile.php"
               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900"
               role="menuitem">
              Profile
            </a>
            <a href="logout.php"
               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900"
               role="menuitem">
              Logout
            </a>
          </div>
        </div>
      </div>

      <!-- Script toggle dropdown bằng click -->
      <script>
        const profileButton = document.getElementById('profileButton');
        const profileDropdown = document.getElementById('profileDropdown');

        profileButton.addEventListener('click', function (e) {
          e.stopPropagation();
          profileDropdown.classList.toggle('hidden');
        });

        // Ẩn dropdown khi click ra ngoài
        document.addEventListener('click', function (e) {
          if (!profileButton.contains(e.target)) {
            profileDropdown.classList.add('hidden');
          }
        });
      </script>
    <?php endif; ?>
  </header>

  <!-- Main Search Area -->
  <main class="flex-grow py-16 px-6">
    <section>
      <!-- Search Form -->
      <form action="search.php" method="get"
            class="flex flex-col items-center gap-6 p-6 bg-[#1c0f0a] rounded-lg shadow-lg border border-[#5e2c2c] max-w-2xl mx-auto font-serif">
        <h2 class="text-2xl font-bold text-[#f3e5ab] tracking-wide">📜 Find Books</h2>
        <div class="flex flex-col sm:flex-row w-full gap-4">
          <div class="relative w-full sm:w-2/3">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-[#c2b280]">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                   stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z" />
              </svg>
            </span>
            <input type="text" name="query" value="<?= htmlspecialchars($searchQuery) ?>"
                   placeholder="Search books..."
                   class="w-full pl-10 pr-4 py-2 rounded-md bg-[#3a1f1f] text-[#fdf6e3] 
                          border border-[#865c3c] focus:outline-none focus:ring-2 focus:ring-[#d1bfa7] 
                          placeholder-[#c2b280]"
                   required>
          </div>
          <div class="flex gap-2 w-full sm:w-auto justify-center">
            <div class="relative">
              <select name="search_type"
                      class="appearance-none bg-[#6d2e2e] hover:bg-[#8a3f3f] text-[#f3e5ab] font-medium 
                             pl-4 pr-8 py-2 rounded-md transition cursor-pointer">
                <option value="title"  <?= (isset($_GET['search_type']) && $_GET['search_type'] === 'title')  ? 'selected' : '' ?>>Title</option>
                <option value="isbn"   <?= (isset($_GET['search_type']) && $_GET['search_type'] === 'isbn')   ? 'selected' : '' ?>>ISBN</option>
                <option value="author" <?= (isset($_GET['search_type']) && $_GET['search_type'] === 'author') ? 'selected' : '' ?>>Author</option>
              </select>
              <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-[#f3e5ab]">
                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                  <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" />
                </svg>
              </div>
            </div>
            <button type="submit"
                    class="bg-[#6d2e2e] hover:bg-[#8a3f3f] text-[#f3e5ab] font-medium px-4 py-2 rounded-md transition flex items-center justify-center">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                   viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z" />
              </svg>
            </button>
          </div>
        </div>
      </form>

      <?php if (!empty($searchQuery) && !$loginAlert): ?>
        <div class="mt-8">
          <h3 class="text-xl font-semibold mb-4 text-[#f3e5ab]">
            Results <?= $searchQuery ? 'for "' . htmlspecialchars($searchQuery) . '"' : '' ?>
          </h3>
          <?php if (count($searchResults) > 0): ?>
            <ul class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
              <?php foreach ($searchResults as $result): ?>
                <li class="bg-[#3a1f1f] p-4 rounded-lg shadow-lg border border-[#5e2c2c] 
                           flex flex-col items-center hover:border-[#d1bfa7] transition-colors">
                  <div class="w-full h-40 mb-4 flex items-center justify-center overflow-hidden rounded-lg bg-[#2a1515]">
                    <?php if (!empty($result['Cover']) && file_exists($result['Cover'])): ?>
                      <img src="displayCover.php?filePath=<?= urlencode($result['Cover']) ?>"
                           alt="<?= htmlspecialchars($result['Title']) ?>"
                           class="max-h-full max-w-full object-contain">
                    <?php else: ?>
                      <img src="defaultCover.jpg" alt="Default Cover"
                           class="max-h-full max-w-full object-contain">
                    <?php endif; ?>
                  </div>
                  <h4 class="text-lg font-bold text-center mb-2 text-[#fdf6e3]">
                    <?= htmlspecialchars($result['Title']) ?>
                  </h4>
                  <div class="flex gap-2 w-full justify-center">
                    <a href="read.php?file=<?= urlencode($result['FilePath']) ?>"
                       class="bg-[#6d2e2e] hover:bg-[#8a3f3f] text-[#f3e5ab] font-semibold 
                              px-4 py-2 rounded-md transition flex-1 text-center">
                      Read
                    </a>
                    <a href="download.php?file=<?= urlencode($result['FilePath']) ?>"
                       class="bg-[#5e2c2c] hover:bg-[#7a3a3a] text-[#f3e5ab] font-semibold 
                              px-4 py-2 rounded-md transition flex-1 text-center">
                      Download
                    </a>
                  </div>
                </li>
              <?php endforeach; ?>
            </ul>
          <?php else: ?>
            <p class="text-[#d1a3a3] italic">No books found matching your criteria.</p>
          <?php endif; ?>
        </div>
      <?php endif; ?>

      <?php if (!empty($error)): ?>
        <p class="text-[#d1a3a3] mt-4 italic"><?= htmlspecialchars($error) ?></p>
      <?php endif; ?>
    </section>
  </main>

  <?php if (isset($loginAlert) && $loginAlert): ?>
    <div class="alert-overlay" id="loginModal">
      <div class="alert-box">
        <div class="alert-header">
          <span class="exclamation-icon">!</span>
        </div>
        <p class="alert-message">You must login first!</p>
        <button class="alert-button"
                onclick="document.getElementById('loginModal').style.display='none';">
          OK
        </button>
      </div>
    </div>
  <?php endif; ?>

  <!-- Footer -->
  <footer class="bg-red-950 text-center text-white py-4">
    <p>&copy; 2025 Athena Library System | All rights reserved</p>
    <p>Contact us at <a href="mailto:support@athena.org" class="underline">support@athena.org</a></p>
  </footer>
</body>
</html>
