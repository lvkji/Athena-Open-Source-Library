<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login_form.php");
    exit();
}

require_once '/home/group5-sp25/connect.php';

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
$email = $_SESSION['email'];
$role = $_SESSION['role'];

try {
    $stmt = $conn->prepare("SELECT created_at FROM users WHERE userid = :userid");
    $stmt->bindParam(':userid', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $userData = $stmt->fetch(PDO::FETCH_ASSOC);
    $created_at = date("F j, Y", strtotime($userData['created_at'] ?? 'now'));
} catch (Exception $e) {
    $created_at = 'Unavailable';
}

try {
    $stmt = $conn->prepare("SELECT COUNT(*) FROM uploads WHERE userid = :userid");
    $stmt->bindParam(':userid', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $uploadCount = $stmt->fetchColumn();
} catch (Exception $e) {
    $uploadCount = 0;
}

$ip = $_SERVER['REMOTE_ADDR'];
$browser = $_SERVER['HTTP_USER_AGENT'];
$isMobile = preg_match('/Mobile|Android|iPhone/', $browser) ? "Mobile" : "Desktop";
?>
<!DOCTYPE html>
<html lang="en" class="bg-black text-white">
<head>
  <link rel="icon" type="image/x-icon" href="AthensAcropolis.ico">
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Your Profile - Athena</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex flex-col min-h-screen font-serif">

<!-- Header -->
<header class="bg-red-950 py-6 text-center text-white text-3xl font-bold">
  Athena - <?= htmlspecialchars($role) ?> Profile
</header>

<!-- Main Content -->
<main class="flex-grow bg-gray-900 flex flex-col items-center justify-center text-center p-6 space-y-10">

  <!-- Profile Card -->
  <div class="bg-gray-800 p-6 rounded-lg w-full max-w-md text-left text-lg shadow-lg hover:shadow-xl transition">
    <div class="flex items-center space-x-4 mb-4">
      <img src="https://ui-avatars.com/api/?name=<?= urlencode($username) ?>&background=random" alt="Avatar" class="w-16 h-16 rounded-full border-2 border-white shadow" />
      <div>
        <h2 class="text-2xl font-bold"><?= htmlspecialchars($username) ?></h2>
        <p class="mt-1 text-sm text-gray-400"><?= htmlspecialchars($email) ?></p>
        <span class="inline-block px-3 py-1 mt-2 rounded-full text-sm font-semibold
            <?= match (strtolower($role)) {
              'student' => 'bg-blue-700 text-blue-100',
              'author' => 'bg-green-700 text-green-100',
              'administrator' => 'bg-red-700 text-red-100',
              default => 'bg-gray-600 text-gray-100',
            } ?>">
            <?= htmlspecialchars($role) ?>
        </span>
      </div>
    </div>
    <p><strong>Member Since:</strong> <?= htmlspecialchars($created_at) ?></p>
  </div>

  <!-- Smart Stats -->
  <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-2xl w-full">
    <div class="bg-gray-800 p-4 rounded-lg text-center shadow">
      <p class="text-xl font-bold text-indigo-400"><?= $uploadCount ?></p>
      <p class="text-sm text-gray-300">Uploaded Documents</p>
    </div>
    <div class="bg-gray-800 p-4 rounded-lg text-center shadow">
      <p class="text-sm font-semibold text-gray-300">Current Device</p>
      <p class="text-gray-200"><?= htmlspecialchars($isMobile) ?></p>
    </div>
    <div class="bg-gray-800 p-4 rounded-lg text-center shadow">
      <p class="text-sm font-semibold text-gray-300">Current Time</p>
      <p class="text-gray-200" id="current-time">Loading...</p>
    </div>
    <div class="bg-gray-800 p-4 rounded-lg text-center shadow">
      <p class="text-sm font-semibold text-gray-300">IP Address</p>
      <p class="text-gray-200"><?= htmlspecialchars($ip) ?></p>
    </div>
    <div class="bg-gray-800 p-4 rounded-lg text-center col-span-1 md:col-span-2">
      <p class="text-sm font-semibold text-gray-300 mb-1">Browser Info</p>
      <p class="text-gray-400 text-xs break-words"><?= htmlspecialchars($browser) ?></p>
    </div>
  </div>

  <!-- Centered Logout Button -->
  <section class="w-full max-w-2xl mt-8">
    <div class="flex justify-center">
      <a href="logout.php" class="bg-red-600 hover:bg-red-500 px-6 py-3 rounded text-white font-semibold shadow text-center transition">
        Logout
      </a>
    </div>
  </section>

</main>

<!-- Footer -->
<footer class="bg-red-950 text-white text-center py-4 mt-10">
  <p>&copy; 2025 Athena Library System | All rights reserved</p>
  <p>Contact us at <a href="mailto:support@athena.org" class="underline">support@athena.org</a></p>
</footer>

<!-- JavaScript: Current Time Updater -->
<script>
  function updateTime() {
    const now = new Date();
    const options = { year: 'numeric', month: 'long', day: 'numeric', hour: 'numeric', minute: '2-digit', hour12: true };
    document.getElementById("current-time").textContent = now.toLocaleString('en-US', options);
  }
  updateTime();
  setInterval(updateTime, 60000); // Refresh every minute
</script>

</body>
</html>
