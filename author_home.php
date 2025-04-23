<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Author') {
    header("Location: login_form.php");
    exit();
}
$username = $_SESSION['username'] ?? 'Author';
?>
<!DOCTYPE html>
<html lang="en" class="bg-black text-white font-serif">
<head>
<link rel="icon" type="image/x-icon" href="AthensAcropolis.ico">
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Athena - Author Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex flex-col min-h-screen">

<!-- Header -->
<header class="bg-red-950 py-6 relative">
  <div class="absolute top-6 right-8 space-x-4">
    <a href="profile.php" class="text-indigo-400 font-semibold hover:underline">Profile</a>
  </div>
  <div class="container mx-auto text-center">
    <h1 class="text-3xl font-bold tracking-wide text-white">Athena - Author Dashboard</h1>
    <nav class="mt-4 flex justify-center space-x-6">
      <a href="upload.php" class="text-white font-semibold hover:underline">Upload Work</a>
      <a href="search.php" class="text-white font-semibold hover:underline">Search Library</a>
    </nav>
  </div>
</header>

<!-- Main Content -->
<main class="flex-grow bg-gray-900 py-16 px-4 text-center">
  <h2 class="text-4xl font-bold mb-6">Welcome, <?= htmlspecialchars($username) ?></h2>
  <p class="text-gray-300 max-w-xl mx-auto mb-8">
    Manage your uploads, improve the learning community, and contribute to the Athena knowledge base.
  </p>

  <!-- Dashboard Highlights -->
  <section class="grid md:grid-cols-2 gap-6 max-w-5xl mx-auto text-left">
    <div class="bg-gray-800 p-6 rounded-lg shadow hover:shadow-xl transition">
      <h3 class="text-2xl font-semibold mb-2 text-white">Submission Stats</h3>
      <p class="text-gray-300">Track the number of verified vs. pending documents you’ve uploaded.</p>
    </div>
    <div class="bg-gray-800 p-6 rounded-lg shadow hover:shadow-xl transition">
      <h3 class="text-2xl font-semibold mb-2 text-white">Editor Toolbox</h3>
      <p class="text-gray-300">Access tools to revise, resubmit, or organize your drafts and content versions.</p>
    </div>
  </section>

  <a href="upload.php" class="mt-10 inline-block bg-blue-600 hover:bg-blue-500 text-white font-semibold py-3 px-6 rounded-lg transition">Add Content to Library</a>

  <!-- Cool Logo Footer Section -->

</main>

<!-- Footer -->
<footer class="bg-red-950 text-white text-center py-4">
  <p>&copy; 2025 Athena Library System | All rights reserved</p>
  <p>Contact us at <a href="mailto:support@athena.org" class="underline">support@athena.org</a></p>
</footer>

</body>
</html>
