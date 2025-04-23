<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn = new mysqli('turing2.cs.olemiss.edu', 'username', 'password', 'athenaDB');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO Users (Email, Password, UserType) VALUES ('$email', '$hashed_password', '$role')";

    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
        header("Location: login.php");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Athena - Sign Up</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Welcome to Athena</h1>
        <nav>
            <a href="index.php">Home</a>
            <a href="search.php">Search</a>
            <a href="upload.php">Upload</a>
            <a href="aboutUs.php">About Us</a>
            <a href="login.php">Login</a>
            <a href="signup.php">Sign-up</a>
        </nav>
    </header>

    <main>
        <h2>Sign Up</h2>
        <form action="signup.php" method="post">
            <label for="email">Email:</label><br>
            <input type="email" id="email" name="email" required><br><br>
            <label for="username">Username:</label><br>
            <input type="text" id="username" name="username" required><br><br>
            <label for="password">Password:</label><br>
            <input type="password" id="password" name="password" required><br><br>
            <label for="role">Role:</label><br>
            <select id="role" name="role" required>
                <option value="student">Student</option>
                <option value="author">Author</option>
                </select><br><br>
            <button type="submit">Sign Up</button>
        </form>
        <p>Already have an account? <a href="login.php">Login</a></p>
    </main>

    <footer>
        <p>&copy; 2025 Athena Library System</p>
    </footer>
</body>
</html>