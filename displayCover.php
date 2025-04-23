<?php
// displayCover.php
// File này phục vụ ảnh bìa dựa trên filePath được truyền qua GET

if (isset($_GET['filePath'])) {
    $filePath = $_GET['filePath'];

    // Tùy chọn: bạn có thể thực hiện các bước validate/sanitize filePath ở đây

    if (file_exists($filePath)) {
        $imageInfo = getimagesize($filePath);
        if ($imageInfo) {
            header("Content-Type: " . $imageInfo['mime']);
            readfile($filePath);
            exit;
        } else {
            echo "File không phải là ảnh hợp lệ.";
        }
    } else {
        echo "File ảnh không tồn tại tại: " . htmlspecialchars($filePath);
    }
} else {
    echo "Chưa truyền filePath.";
}
?>
