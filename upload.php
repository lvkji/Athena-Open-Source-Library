<?php
session_start();
require_once("database.php");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Các đường dẫn lưu file
$pdfUploadDir = '/home/group5-sp25/public_html/uploads/';
$imageUploadDir = '/home/group5-sp25/public_html/images/';
$maxFileSize = 10 * 1024 * 1024; // 10 MB

$uploadStatus = null;

// Kết nối CSDL
$mysqli = Database::dbConnect();
$mysqli->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Tạo thư mục nếu chưa tồn tại
if (!file_exists($pdfUploadDir)) {
    mkdir($pdfUploadDir, 0755, true);
}
if (!file_exists($imageUploadDir)) {
    mkdir($imageUploadDir, 0755, true);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // echo '<pre>';
    // print_r($_FILES);
    // echo '</pre>';
    // Lấy dữ liệu từ form
    $title  = $_POST['title'];
    $author = $_POST['author'];
    $isbn   = $_POST['isbn'];
    
    $pdfDestPath = "";
    $imgDestPath = "";
    
    // Xử lý file PDF (chỉ nhận file .pdf)
    if (isset($_FILES['book_file']) && $_FILES['book_file']['error'] === UPLOAD_ERR_OK) {
        $pdfTmpPath  = $_FILES['book_file']['tmp_name'];
        $pdfFileName = $_FILES['book_file']['name'];
        $pdfFileSize = $_FILES['book_file']['size'];
        $pdfFileExt  = strtolower(pathinfo($pdfFileName, PATHINFO_EXTENSION));
        $allowedPdfExtensions = ['pdf'];
        
        if (in_array($pdfFileExt, $allowedPdfExtensions)) {
            if ($pdfFileSize <= $maxFileSize) {
                $newPdfFileName = uniqid('book_', true) . '.' . $pdfFileExt;
                $pdfDestPath = $pdfUploadDir . $newPdfFileName;
                if (!move_uploaded_file($pdfTmpPath, $pdfDestPath)) {
                    $uploadStatus = "pdf_upload_error";
                }
            } else {
                $uploadStatus = "pdf_size_error";
            }
        } else {
            $uploadStatus = "pdf_type_error";
        }
    } else {
        $uploadStatus = "pdf_upload_error";
    }
    
    // Xử lý file ảnh (chỉ nhận file .img)
    if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
        $imgTmpPath  = $_FILES['cover_image']['tmp_name'];
        $imgFileName = $_FILES['cover_image']['name'];
        $imgFileSize = $_FILES['cover_image']['size'];
        $imgFileExt  = strtolower(pathinfo($imgFileName, PATHINFO_EXTENSION));
        $allowedImgExtensions = ['img', 'jpg', 'png']; // Nếu cần hỗ trợ các định dạng khác (jpg, png, ...) thì cập nhật mảng này.
        
        if (in_array($imgFileExt, $allowedImgExtensions)) {
            if ($imgFileSize <= $maxFileSize) {
                $newImgFileName = uniqid('cover_', true) . '.' . $imgFileExt;
                $imgDestPath = $imageUploadDir . $newImgFileName;
                if (!move_uploaded_file($imgTmpPath, $imgDestPath)) {
                    $uploadStatus = "img_upload_error";
                    // Nếu file PDF đã được upload thì xóa đi
                    if (file_exists($pdfDestPath)) {
                        unlink($pdfDestPath);
                    }
                }
            } else {
                $uploadStatus = "img_size_error";
                if (file_exists($pdfDestPath)) {
                    unlink($pdfDestPath);
                }
            }
        } else {
            $uploadStatus = "img_type_error";
            if (file_exists($pdfDestPath)) {
                unlink($pdfDestPath);
            }
        }
    } else {
        $uploadStatus = "img_upload_error";
        if (file_exists($pdfDestPath)) {
            unlink($pdfDestPath);
        }
    }
    
    // Nếu không có lỗi trong quá trình upload file thì insert dữ liệu vào bảng Books
    if ($uploadStatus === null) {
        try {
            // Giả sử bảng Books có các cột: Title, Author, ISBN, FilePath, ImagePath
            $stmt = $mysqli->prepare("INSERT INTO Books (Title, Author, ISBN, `FilePath`, Cover) VALUES (?, ?, ?, ?, ?)");
            $stmt->bindParam(1, $title, PDO::PARAM_STR);
            $stmt->bindParam(2, $author, PDO::PARAM_STR);
            $stmt->bindParam(3, $isbn, PDO::PARAM_STR);
            $stmt->bindParam(4, $pdfDestPath, PDO::PARAM_STR);
            $stmt->bindParam(5, $imgDestPath, PDO::PARAM_STR);
            
            if ($stmt->execute()) {
                $uploadStatus = "success";
            } else {
                $uploadStatus = "db_error";
                unlink($pdfDestPath);
                unlink($imgDestPath);
            }
        } catch (PDOException $e) {
            $uploadStatus = "db_error";
            unlink($pdfDestPath);
            unlink($imgDestPath);
            error_log("Database error: " . $e->getMessage());
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en" class="bg-black text-white font-serif">
<head>
  <meta charset="UTF-8" />
  <link rel="icon" type="image/x-icon" href="AthensAcropolis.ico">
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Upload Book - Athena</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex flex-col min-h-screen">
  <header class="bg-red-950 py-6">
    <div class="container mx-auto text-center">
      <h1 class="text-3xl font-bold tracking-wide text-white">Athena - Open Source Library</h1>
      <nav class="mt-4 space-x-6">
        <a href="search.php" class="text-white font-semibold hover:underline">Search</a>
        <a href="upload.php" class="text-white font-semibold hover:underline">Upload</a>
      </nav>
    </div>
  </header>
  <main class="flex-grow">
    <section class="max-w-xl mx-auto py-16 px-6 bg-gray-900 rounded-lg shadow-lg mt-10">
      <h2 class="text-2xl font-bold-serif mb-4 text-center">Upload Your Book</h2>
      <p class="text-gray-300 text-center mb-8">Share your educational resources with the Athena community.</p>
      <?php if ($uploadStatus === "success"): ?>
        <p class="text-green-400 font-semibold-serif text-center mb-6">✅ Upload successful!</p>
      <?php elseif ($uploadStatus !== null): ?>
        <p class="text-red-400 font-semibold-serif text-center mb-6">
          <?php 
            switch ($uploadStatus) {
              case "pdf_type_error": echo "❌ Invalid PDF file type. Only PDF allowed."; break;
              case "pdf_size_error": echo "❌ PDF file size exceeds the limit."; break;
              case "pdf_upload_error": echo "❌ Error uploading PDF file."; break;
              case "img_type_error": echo "❌ Invalid image file type. Only IMG allowed."; break;
              case "img_size_error": echo "❌ Image file size exceeds the limit."; break;
              case "img_upload_error": echo "❌ Error uploading image file."; break;
              case "db_error": echo "❌ Database error. Upload failed."; break;
              default: echo "❌ An unknown error occurred.";
            }
          ?>
        </p>
      <?php endif; ?>
      <form action="upload.php" method="post" enctype="multipart/form-data" class="space-y-6">
        <div>
          <label for="title" class="block mb-2 font-semibold-serif text-white">Book Title</label>
          <input type="text" id="title" name="title" placeholder="Enter book title"
                 class="w-full p-3 rounded bg-gray-800 border border-gray-600 text-white" required>
        </div>
        <div>
          <label for="author" class="block mb-2 font-semibold-serif text-white">Author</label>
          <input type="text" id="author" name="author" placeholder="Enter author name"
                 class="w-full p-3 rounded bg-gray-800 border border-gray-600 text-white" required>
        </div>
        <div>
          <label for="isbn" class="block mb-2 font-semibold-serif text-white">ISBN</label>
          <input type="text" id="isbn" name="isbn" placeholder="Enter ISBN"
                 class="w-full p-3 rounded bg-gray-800 border border-gray-600 text-white">
        </div>
        <div>
          <label for="book_file" class="block mb-2 font-semibold-serif text-white">Select Book File (PDF)</label>
          <input type="file" id="book_file" name="book_file"
                 class="w-full text-gray-300 file:bg-blue-600 file:text-white file:rounded file:px-4 file:py-2 file:border-none" 
                 required accept=".pdf">
        </div>
        <div>
          <label for="cover_image" class="block mb-2 font-semibold-serif text-white">Select Cover Image (.img)</label>
          <input type="file" id="cover_image" name="cover_image"
                 class="w-full text-gray-300 file:bg-blue-600 file:text-white file:rounded file:px-4 file:py-2 file:border-none" 
                 required accept=".img">
        </div>
        <div class="text-center">
          <button type="submit"
                  class="bg-blue-600 hover:bg-blue-500 text-white font-semibold-serif px-6 py-2 rounded-lg transition">
            Upload Book
          </button>
        </div>
      </form>
    </section>
  </main>
  <footer class="bg-red-950 text-center text-white py-4">
    <p>&copy; 2025 Athena Library System | All rights reserved</p>
    <p>Contact us at <a href="mailto:support@athena.org" class="underline">support@athena.org</a></p>
  </footer>
</body>
</html>