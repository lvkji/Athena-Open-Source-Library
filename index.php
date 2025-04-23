<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en" class="bg-black text-white font-serif">
<head>
  <link rel="icon" type="image/x-icon" href="AthensAcropolis.ico">
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>AthenaLMS</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    .rainbow-text {
      background: linear-gradient(90deg, #ff6ec4, #7873f5, #4ade80, #facc15, #f472b6);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }
  </style>
</head>
<body class="flex flex-col min-h-screen font-serif">

<!-- Header -->
<header class="bg-red-950 py-6">
  <div class="container mx-auto text-center relative">
    <!-- Centered Title -->
    <h1 class="text-3xl font-bold tracking-wide text-white">Athena - Open Source Library</h1>

    <!-- Centered Navigation -->
    <nav class="mt-4 flex justify-center space-x-6">
      <a href="index.php" class="text-white font-semibold hover:underline">Home</a>
      <a href="aboutUs.php" class="text-white font-semibold hover:underline">About Us</a>
      <a href="FAQ.php" class="text-white font-semibold hover:underline">FAQ</a>
    </nav>

    <!-- Top-Right Auth Links -->
    <div class="absolute top-6 right-8 space-x-4">
      <a href="login_form.php" class="text-indigo-400 font-semibold hover:underline">Login</a>
      <a href="register.php" class="text-indigo-400 font-semibold hover:underline">Register</a>
    </div>
  </div>
</header>

<!-- Main Content -->
<main class="flex-grow">
  <!-- Hero Section -->
  <section class="bg-gray-900 py-16 px-6 text-center">
    <h2 class="text-4xl font-bold mb-4 rainbow-text">Welcome to Athena</h2>
    <p class="max-w-2xl mx-auto mb-4 rainbow-text">
      Your centralized platform for discovering, sharing, and verifying high-quality academic content.
    </p>
    <p class="max-w-2xl mx-auto rainbow-text">
      Whether you're a student looking for study materials, an author contributing your expertise, or an administrator ensuring quality—Athena supports your educational journey.
    </p>
    <a href="search.php" class="mt-6 inline-block bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-500 transition">Browse Library</a>
  </section>

  <!-- Highlights -->
  <section class="py-16 px-6 bg-black text-center">
    <h2 class="text-3xl font-bold mb-10">Platform Highlights</h2>
    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6 max-w-6xl mx-auto">
      <div class="bg-gray-800 p-6 rounded-lg shadow hover:shadow-lg">
        <h3 class="text-xl font-semibold mb-2 text-white">Search & Discover</h3>
        <p class="text-gray-300">Quickly find open educational resources across a variety of subjects, from textbooks to research articles.</p>
      </div>
      <div class="bg-gray-800 p-6 rounded-lg shadow hover:shadow-lg">
        <h3 class="text-xl font-semibold mb-2 text-white">Upload & Share</h3>
        <p class="text-gray-300">Contribute original works and help expand our collection of free academic content for learners everywhere.</p>
      </div>
      <div class="bg-gray-800 p-6 rounded-lg shadow hover:shadow-lg">
        <h3 class="text-xl font-semibold mb-2 text-white">Verified by Experts</h3>
        <p class="text-gray-300">Our author and admin verification system ensures all content meets academic integrity standards.</p>
      </div>
      <div class="bg-gray-800 p-6 rounded-lg shadow hover:shadow-lg">
        <h3 class="text-xl font-semibold mb-2 text-white">Built for Collaboration</h3>
        <p class="text-gray-300">Connect with students, educators, and professionals working to democratize access to education.</p>
      </div>
    </div>
  </section>

  <!-- How It Works -->
  <section class="bg-gray-900 py-16 px-6">
    <h2 class="text-3xl font-bold text-center mb-8">How It Works</h2>
    <ol class="list-decimal list-inside max-w-3xl mx-auto text-gray-300 space-y-3 text-left">
      <li><span class="font-semibold text-white">Create an account</span> as a student, author, or administrator.</li>
      <li><span class="font-semibold text-white">Search or contribute</span> educational resources with built-in filters and upload tools.</li>
      <li><span class="font-semibold text-white">Authors verify</span> submitted materials to ensure accuracy and reliability.</li>
      <li><span class="font-semibold text-white">Admins moderate</span> and maintain system quality and user access.</li>
    </ol>
  </section>

  <!-- Testimonials -->
  <section class="bg-black text-center py-12 px-6">
    <h2 class="text-3xl font-bold mb-6">Motivation for Athena</h2>
    <blockquote class="italic text-gray-300 max-w-2xl mx-auto">"Knowledge is power, but it is only when shared with others that it becomes a force for good."</blockquote>
    <cite class="block mt-4 text-gray-400">– Andrew Dylan Ignatius, Altruist</cite>
  </section>
</main>

<!-- Footer -->
<footer class="bg-red-950 text-center text-white py-4">
  <p>&copy; 2025 Athena Library System | All rights reserved</p>
  <p>Contact us at <a href="mailto:support@athena.org" class="underline">support@athena.org</a></p>
</footer>

</body>
</html>
