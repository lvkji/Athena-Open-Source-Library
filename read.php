<?php
session_start();

// Lấy đường dẫn thật của thư mục chứa file sách
$uploadsDir = realpath('/home/group5-sp25/public_html/uploads');
if (!$uploadsDir) {
    die("Thư mục uploads không tồn tại.");
}
$uploadsDir = rtrim($uploadsDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

/**
 * Kiểm tra tính hợp lệ của file PDF.
 *
 * @param string $filePath Đường dẫn đầy đủ đến file cần kiểm tra.
 * @param string $uploadsDir Thư mục chứa file được phép.
 * @return bool Trả về true nếu file hợp lệ, ngược lại false.
 */
function isValidPdf($filePath, $uploadsDir) {
    // Lấy đường dẫn thật của file
    $realPath = realpath($filePath);
    if ($realPath === false) {
        return false;
    }
    // Đảm bảo file nằm trong thư mục uploads (so sánh chuỗi đường dẫn)
    if (strpos($realPath, $uploadsDir) !== 0) {
        return false;
    }
    // Kiểm tra phần mở rộng của file (chỉ cho phép PDF)
    $extension = strtolower(pathinfo($realPath, PATHINFO_EXTENSION));
    if ($extension !== 'pdf') {
        return false;
    }
    return true;
}

// Kiểm tra xem có tham số 'file' trên URL hay không
if (!isset($_GET['file'])) {
    echo "Không có file nào được chỉ định.";
    exit;
}

// Giải mã tên file (có thể chứa ký tự đặc biệt)
$fileParam = urldecode($_GET['file']);

// Sử dụng basename() để đảm bảo file luôn nằm trực tiếp trong thư mục uploads
$filename = basename($fileParam);
$fullPath = $uploadsDir . $filename;

// Kiểm tra tính hợp lệ của file
if (!isValidPdf($fullPath, $uploadsDir)) {
    echo "File sách không hợp lệ. Vui lòng kiểm tra lại.";
    exit;
}

// Kiểm tra sự tồn tại của file
if (!file_exists($fullPath)) {
    echo "File không tồn tại.";
    exit;
}

// Thiết lập các header để hiển thị file PDF inline trong trình duyệt
header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="' . basename($fullPath) . '"');
header('Content-Length: ' . filesize($fullPath));

// Đọc và gửi nội dung file PDF tới trình duyệt
readfile($fullPath);
exit;
?>
