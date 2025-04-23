<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Administrator') {
    header("Location: login_form.php");
    exit();
}

$username = $_SESSION['username'] ?? 'Admin';

include '/home/group5-sp25/connect.php';

// Fetch recent book uploads joined with user info
$uploads = [];
try {
  $stmt = $conn->prepare("
  SELECT 
      Books.Title AS book_title,
      uploads.uploaddate,
      users.username,
      users.email
  FROM uploads
  JOIN Books ON uploads.documentid = Books.BookID
  JOIN users ON uploads.userid = users.userid
  WHERE uploads.documenttype = 'Book'
  ORDER BY uploads.uploaddate DESC
  LIMIT 50
");

  $stmt->execute();
  $uploads = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  $uploads[] = [
      'book_title' => 'Error loading books: ' . $e->getMessage(), // Add error details for debugging
      'uploaddate' => '',
      'username' => '',
      'email' => '',
  ];
}

?>
<!DOCTYPE html>
<html lang="en" class="bg-black text-white font-serif">
<head>
  <link rel="icon" type="image/x-icon" href="AthensAcropolis.ico">
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Athena - Admin Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex flex-col min-h-screen">

<!-- Header -->
<header class="bg-red-950 py-6 relative">
  <div class="absolute top-6 right-8 space-x-4">
    <a href="profile.php" class="text-indigo-400 font-semibold hover:underline">Profile</a>
  </div>
  <div class="container mx-auto text-center">
    <h1 class="text-3xl font-bold tracking-wide text-white">Athena - Admin Dashboard</h1>
    <nav class="mt-4 flex justify-center space-x-6">
      <a href="pong.php" class="text-white font-semibold hover:underline">Verify Submissions</a>
      <a href="search.php" class="text-white font-semibold hover:underline">Search Library</a>
      <a href="upload.php" class="text-white font-semibold hover:underline">Upload Content</a>
    </nav>
  </div>
</header>

<!-- Main Content -->
<main class="flex-grow bg-gray-900 py-16 px-4 text-center">
  <h2 class="text-4xl font-bold mb-6">Welcome, <?= htmlspecialchars($username) ?></h2>
  <p class="text-gray-300 max-w-xl mx-auto mb-8">
    Oversee platform content, verify user submissions, and maintain quality control across Athena.
  </p>

  <!-- Admin Highlights -->
  <section class="grid md:grid-cols-2 gap-6 max-w-5xl mx-auto text-left">
    <div class="bg-gray-800 p-6 rounded-lg shadow hover:shadow-xl transition">
      <h3 class="text-2xl font-semibold mb-2 text-white">Pending Approvals</h3>
      <p class="text-gray-300">Review books and articles submitted by users that await verification.</p>
    </div>
    <div class="bg-gray-800 p-6 rounded-lg shadow hover:shadow-xl transition">
      <h3 class="text-2xl font-semibold mb-2 text-white">Recent Book Uploads</h3>
      <div class="mt-3 max-h-60 overflow-y-auto text-sm text-gray-300 bg-gray-700 p-4 rounded">
        <ul class="space-y-1 list-disc pl-5">
          <?php foreach ($uploads as $upload): ?>
            <li>
              <?= htmlspecialchars($upload['book_title']) ?> 
              (uploaded on <?= htmlspecialchars($upload['uploaddate']) ?> by 
              <?= htmlspecialchars($upload['username'] ?: $upload['email']) ?>)
            </li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>
  </section>

  <a href="pong.php" class="mt-10 inline-block bg-blue-600 hover:bg-blue-500 text-white font-semibold py-3 px-6 rounded-lg transition">Start Verifying</a>
</main>

<!-- Footer -->
<footer class="bg-red-950 text-white text-center py-4">
  <p>&copy; 2025 Athena Library System | All rights reserved</p>
  <p>Contact us at <a href="mailto:support@athena.org" class="underline">support@athena.org</a></p>
</footer>

</body>
</html>
