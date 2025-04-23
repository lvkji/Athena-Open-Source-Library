<?php
session_start();
include_once 'signup_function.php'; // Make sure this path is correct

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $role = trim($_POST['role'] ?? '');

    if (!empty($email) && !empty($username) && !empty($password) && !empty($role)) {
        $result = signup($email, $password, $role, $username);

        $_SESSION['message'] = $result['message'];

        if ($result['success']) {
            header("Location: login_form.php");
            exit();
        }
    } else {
        $_SESSION['message'] = "All fields are required.";
    }
}
?>
<!DOCTYPE html>
<html lang="en" class="text-white">
<head>
  <link rel="icon" type="image/x-icon" href="AthensAcropolis.ico">
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register - Athena</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    body {
      background-image: url('registerPhoto.jpg');
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
    }
    .overlay {
      background-color: rgba(0, 0, 0, 0.75);
      backdrop-filter: blur(2px);
    }
  </style>
</head>
<body class="flex items-center justify-center min-h-screen">
  <div class="overlay p-8 rounded-lg shadow-lg w-full max-w-md">
    <h2 class="text-2xl font-bold mb-6 text-center text-white">Register to Athena</h2>

    <?php if (!empty($_SESSION['message'])): ?>
      <div class="mb-4 p-3 text-center text-white font-semibold <?= strpos($_SESSION['message'], 'successful') !== false ? 'bg-green-600' : 'bg-red-600' ?> rounded-lg">
        <?= $_SESSION['message']; unset($_SESSION['message']); ?>
      </div>
    <?php endif; ?>

    <form action="register.php" method="POST" class="space-y-6">
      <div>
        <label for="email" class="block mb-2 font-semibold text-white">Email</label>
        <input type="email" id="email" name="email" required class="w-full p-3 rounded bg-gray-800 border border-gray-600 text-white">
      </div>
      <div>
        <label for="username" class="block mb-2 font-semibold text-white">Username</label>
        <input type="text" id="username" name="username" required class="w-full p-3 rounded bg-gray-800 border border-gray-600 text-white">
      </div>
      <div>
        <label for="password" class="block mb-2 font-semibold text-white">Password</label>
        <div class="relative">
          <input type="password" id="password" name="password" required class="w-full p-3 rounded bg-gray-800 border border-gray-600 text-white pr-10">
          <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-3 flex items-center text-gray-400">👁️</button>
        </div>
      </div>
      <div>
        <label for="role" class="block mb-2 font-semibold text-white">Role</label>
        <select id="role" name="role" class="w-full p-3 rounded bg-gray-800 border border-gray-600 text-white">
          <option value="Student">Student</option>
          <option value="Author">Author</option>
          <option value="Administrator">Administrator</option>
        </select>
      </div>
      <button type="submit" class="w-full bg-blue-600 hover:bg-blue-500 text-white font-semibold py-2 px-4 rounded">
        Sign Up
      </button>
    </form>

    <p class="mt-6 text-center text-sm text-gray-300">
      Already have an account?
      <a href="login_form.php" class="underline text-blue-400">Login</a>
    </p>
  </div>

  <script>
    function togglePassword() {
      const passwordInput = document.getElementById("password");
      passwordInput.type = passwordInput.type === "password" ? "text" : "password";
    }
  </script>
</body>
</html>
