<?php
session_start();
require_once("database.php");

$loginAlert = false; // Khởi tạo biến cờ cho modal

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['query']) && !empty(trim($_GET['query']))) {
    if (!isset($_SESSION['user_id'])) {
        // Nếu chưa đăng nhập, đặt cờ để hiển thị modal
        $loginAlert = true;
    }
}

// Các xử lý khác, ví dụ: kết nối database, truy vấn tìm kiếm, v.v...
$conn = Database::dbConnect();
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$searchResults = [];
$searchQuery = isset($_GET['query']) ? trim($_GET['query']) : '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && !empty($searchQuery) && !$loginAlert) {
    // Chỉ xử lý tìm kiếm nếu có query và người dùng đã đăng nhập
    $searchType = isset($_GET['search_type']) ? $_GET['search_type'] : 'title';

    try {
        if ($searchType === 'title') {
            $querySQL = "SELECT BookID, Title, `FilePath`, Cover FROM Books WHERE Title LIKE :search";
        } elseif ($searchType === 'isbn') {
            $querySQL = "SELECT BookID, Title, `FilePath`, Cover FROM Books WHERE ISBN LIKE :search";
        } elseif ($searchType === 'author') {
            $querySQL = "SELECT B.BookID, B.Title, B.`FilePath`, B.Cover, B.Author
FROM Books B
WHERE B.Author LIKE :search";
        } else {
            $querySQL = "SELECT BookID, Title, `FilePath`, Cover FROM Books WHERE Title LIKE :search";
        }

        $stmt = $conn->prepare($querySQL);
        $stmt->execute([':search' => '%' . $searchQuery . '%']);
        $searchResults = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($searchResults) === 0) {
            $command = "python3 /home/group5-sp25/public_html/athenaScrap.py " . escapeshellarg($searchQuery);
            $output = shell_exec($command);
            sleep(10);

            $stmt->execute([':search' => '%' . $searchQuery . '%']);
            $searchResults = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    } catch (PDOException $e) {
        $error = "Search failed: " . $e->getMessage();
        error_log($error);
    }
}

// Include file view (front end)
require 'search_view.php';
?>