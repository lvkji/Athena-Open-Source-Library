<?php
include_once '/home/group5-sp25/connect.php';

function signup($email, $password, $role, $username)
{
    try {
        global $conn;

        // Check if email already exists
        $emailCheckQuery = "SELECT 1 FROM users WHERE email = :email";
        $stmt = $conn->prepare($emailCheckQuery);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return ["success" => false, "message" => "Email already exists."];
        }

        // Check for duplicate username
        $usernameCheckQuery = "SELECT 1 FROM users WHERE username = :username";
        $stmt = $conn->prepare($usernameCheckQuery);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return ["success" => false, "message" => "Username already taken."];
        }

        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Insert user into correct column names
        $insertQuery = "INSERT INTO users (email, password, role, username) VALUES (:email, :password, :usertype, :username)";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':usertype', $role);  // same variable, just for consistency
        $stmt->bindParam(':username', $username);

        if ($stmt->execute()) {
            return ["success" => true, "message" => "Registration successful!"];
        } else {
            return ["success" => false, "message" => "Error occurred during registration."];
        }

    } catch (PDOException $e) {
        return ["success" => false, "message" => "Database error: " . $e->getMessage()];
    }
}
