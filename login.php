<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include '/home/group5-sp25/connect.php'; // ✅ Update if hosted elsewhere

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (!empty($email) && !empty($password)) {
        try {
            $stmt = $conn->prepare("SELECT userid, password, role FROM users WHERE email = :email");
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                // ✅ Uncomment this block to debug user info if needed
                /*
                echo "<pre>DEBUG: User fetched from database:\n";
                print_r($user);
                echo "\nProvided password: " . htmlspecialchars($password) . "</pre>";
                exit();
                */

                if (password_verify($password, $user['password'])) {
                    // ✅ Set essential session variables
                    $_SESSION['user_id'] = $user['userid'];
                    $_SESSION['email'] = $email;
                    $_SESSION['role'] = $user['role'];
                    $_SESSION['username'] = explode('@', $email)[0]; // Fallback username

                    // ✅ Normalize and route user based on role
                    switch (strtolower($user['role'])) {
                        case 'student':
                            header("Location: student_home.php");
                            break;
                        case 'author':
                            header("Location: author_home.php");
                            break;
                        case 'administrator':
                            header("Location: admin_home.php");
                            break;
                        default:
                            $_SESSION['error_message'] = "Unrecognized role.";
                            header("Location: login_form.php");
                            break;
                    }
                    exit();
                } else {
                    $_SESSION['error_message'] = "Invalid password.";
                }
            } else {
                $_SESSION['error_message'] = "Email not found.";
            }
        } catch (PDOException $e) {
            $_SESSION['error_message'] = "Database error: " . $e->getMessage();
        }
    } else {
        $_SESSION['error_message'] = "Please fill in all required fields.";
    }

    // ❌ If we reach here, login failed
    header("Location: login_form.php");
    exit();
}
?>
