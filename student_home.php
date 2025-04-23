<?php
session_start(); // Handles session_start + login check
// Role-based restriction: only Students allowed here
if ($_SESSION['role'] !== 'Student') {
    header("Location: login_form.php");
    exit();
}

$username = $_SESSION['username'] ?? 'Student';
?>
<!DOCTYPE html>
<html lang="en" class="bg-black text-white font-serif">
<head>
  <link rel="icon" type="image/x-icon" href="AthensAcropolis.ico">
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Athena - Student Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex flex-col min-h-screen">

<!-- Header -->
<header class="bg-red-950 py-6 relative">
  <div class="absolute top-6 right-8 space-x-4">
    <a href="profile.php" class="text-indigo-400 font-semibold hover:underline">Profile</a>
  </div>
  <div class="container mx-auto text-center">
    <h1 class="text-3xl font-bold tracking-wide text-white">Athena - Student Dashboard</h1>
    <nav class="mt-4 flex justify-center space-x-6">
      <a href="search.php" class="text-white font-semibold hover:underline">Search Library</a>
      <a href="upload.php" class="text-white font-semibold hover:underline">Upload Content</a>
    </nav>
  </div>
</header>

<!-- Main Content -->
<main class="flex-grow bg-gray-900 text-center px-4 py-16">
  <div class="animate-fadeIn">
    <h2 class="text-4xl font-bold mb-4">Welcome, <?= htmlspecialchars($username) ?>! 🎓</h2>
    <p class="text-gray-300 max-w-xl mx-auto mb-6">
      Explore books and articles, track your learning, and enjoy a curated academic journey on Athena.
    </p>
    <a href="search.php" class="inline-block bg-blue-600 hover:bg-blue-500 text-white px-6 py-3 rounded-lg font-semibold transition">
      Browse Library
    </a>
  </div>

  <!-- Quick Highlights -->
  <section class="mt-16">
    <h3 class="text-3xl font-bold mb-8">📚 Your Academic Tools</h3>
    <div class="grid md:grid-cols-3 gap-8 max-w-5xl mx-auto text-left">
      <div class="bg-gray-800 p-6 rounded-lg shadow hover:shadow-lg transition duration-300">
        <h4 class="text-xl font-semibold text-white mb-2">🔍 Smart Search</h4>
        <p class="text-gray-300">Search by title, genre, or author to find exactly what you need.</p>
      </div>
      <div class="bg-gray-800 p-6 rounded-lg shadow hover:shadow-lg transition duration-300">
        <h4 class="text-xl font-semibold text-white mb-2">📤 Effortless Upload</h4>
        <p class="text-gray-300">Easily upload your open-source materials to contribute to the library.</p>
      </div>
      <div class="bg-gray-800 p-6 rounded-lg shadow hover:shadow-lg transition duration-300">
        <h4 class="text-xl font-semibold text-white mb-2">📈 Progress Matters</h4>
        <p class="text-gray-300">Keep track of what you’ve explored, uploaded, and liked.</p>
      </div>
    </div>
  </section>

  <!-- Motivational Quote -->
  <section class="mt-20 max-w-2xl mx-auto">
    <blockquote class="italic text-gray-400 text-xl">"I would rather get caught with her than without her"</blockquote>
    <cite class="block mt-4 text-gray-500">— Playboi Carti</cite>
  </section>

  <!-- SVG Animation -->
  <section class="mt-20">
    <svg class="w-32 h-32 mx-auto animate-pulse text-blue-500" fill="currentColor" viewBox="0 0 20 20">
      <path d="M13 7H7v6h6V7z" />
      <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm4-11a1 1 0 10-2 0v6a1 1 0 002 0V7zM8 7a1 1 0 10-2 0v6a1 1 0 002 0V7z" clip-rule="evenodd" />
    </svg>
    <p class="text-sm text-gray-500 mt-2">Empowering students since <?= date('Y') ?>.</p>
  </section>
</main>

<!-- Footer -->
<footer class="bg-red-950 text-white text-center py-4">
  <p>&copy; <?= date('Y') ?> Athena Library System | All rights reserved</p>
  <p>Contact us at <a href="mailto:support@athena.org" class="underline">support@athena.org</a></p>
</footer>

<!-- Animation Utility -->
<style>
  .animate-fadeIn {
    animation: fadeIn 1.5s ease-in-out both;
  }
  @keyframes fadeIn {
    from { opacity: 0; transform: translateY(15px); }
    to { opacity: 1; transform: translateY(0); }
  }
</style>

</body>
</html>
