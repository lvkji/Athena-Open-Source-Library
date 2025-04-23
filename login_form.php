<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en" class="text-white">
<head>
  <link rel="icon" type="image/x-icon" href="AthensAcropolis.ico">
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Athena</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    body {
      background-image: url('loginPhoto.jpg'); /* Make sure this is in the same directory */
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
    <h2 class="text-2xl font-bold mb-6 text-center text-white">Login to Athena</h2>

    <!-- ✅ Inline Error Message -->
    <?php if (!empty($_SESSION['error_message'])) : ?>
      <p class="bg-red-600 text-white p-2 mb-4 rounded"><?php echo $_SESSION['error_message']; ?></p>
      <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>

    <form action="login.php" method="post" class="space-y-6">
      <div>
        <label for="email" class="block mb-2 font-semibold text-white">Email</label>
        <input type="email" id="email" name="email" required
               class="w-full p-3 rounded bg-gray-800 border border-gray-600 text-white">
      </div>
      <div>
        <label for="password" class="block mb-2 font-semibold text-white">Password</label>
        <div class="relative">
          <input type="password" id="password" name="password" required
                 class="w-full p-3 rounded bg-gray-800 border border-gray-600 text-white pr-10">
          <button type="button" onclick="togglePassword()"
                  class="absolute inset-y-0 right-3 flex items-center text-gray-400">👁️</button>
        </div>
      </div>
      <button type="submit"
              class="w-full bg-blue-600 hover:bg-blue-500 text-white font-semibold py-2 px-4 rounded">
        Login
      </button>
    </form>

    <p class="mt-6 text-center text-sm text-gray-300">
      Don't have an account?
      <a href="register.php" class="underline text-blue-400">Register</a>
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
