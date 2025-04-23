<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en" class="bg-black text-white font-serif">
<head>
<link rel="icon" type="image/x-icon" href="AthensAcropolis.ico">
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>About Us - Athena</title>
  <script src="https://cdn.tailwindcss.com"></script>
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

  <!-- Main Content -->
  <main class="flex-grow">
    <section class="py-16 px-6 max-w-5xl mx-auto">
      <h2 class="text-3xl font-bold mb-6 text-center">Our Mission</h2>
      <p class="text-gray-300 text-center mb-12">
        Athena is dedicated to fostering an open and collaborative learning environment by providing access to verified educational resources.
        Our mission is to empower students, authors, and administrators with a platform that facilitates seamless knowledge sharing while maintaining academic integrity.
      </p>

      <h2 class="text-3xl font-bold mb-6 text-center">Why Choose Athena?</h2>
      <div class="grid md:grid-cols-2 gap-8 text-gray-300 mb-12">
        <div class="bg-gray-800 p-6 rounded shadow">
          <h3 class="text-xl font-semibold text-white mb-2">Access a Growing Library</h3>
          <p>Find a curated collection of educational books, articles, and resources freely available to students and educators.</p>
        </div>
        <div class="bg-gray-800 p-6 rounded shadow">
          <h3 class="text-xl font-semibold text-white mb-2">Verified and Trusted Materials</h3>
          <p>All uploads go through a review process to ensure that only accurate, high-quality content is published on the platform.</p>
        </div>
        <div class="bg-gray-800 p-6 rounded shadow">
          <h3 class="text-xl font-semibold text-white mb-2">Global Collaboration</h3>
          <p>Connect with learners, researchers, and educators from around the world to exchange knowledge and academic resources.</p>
        </div>
        <div class="bg-gray-800 p-6 rounded shadow">
          <h3 class="text-xl font-semibold text-white mb-2">User-Friendly Experience</h3>
          <p>Our platform is easy to navigate, making it simple to contribute, search, and manage educational materials.</p>
        </div>
      </div>

      <h2 class="text-3xl font-bold mb-6 text-center">Our Vision</h2>
      <p class="text-gray-300 text-center max-w-3xl mx-auto">
        We envision a world where education is freely accessible to all. By integrating technology and community-driven contributions,
        Athena seeks to bridge the gap in educational resources and make learning more inclusive for everyone.
      </p>
    </section>
  </main>

    <!-- Footer -->
    <footer class="bg-red-950 text-center text-white py-4">
    <p>&copy; 2025 Athena Library System | All rights reserved</p>
    <p>Contact us at <a href="mailto:support@athena.org" class="underline">support@athena.org</a></p>
  </footer>

</body>
</html>
