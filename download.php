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
    $realPath = realpath($filePath);
    if ($realPath === false) {
        return false;
    }
    if (strpos($realPath, $uploadsDir) !== 0) {
        return false;
    }
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

$fileParam = urldecode($_GET['file']);
$filename = basename($fileParam);
$fullPath = $uploadsDir . $filename;

// Kiểm tra tính hợp lệ và sự tồn tại của file
if (!isValidPdf($fullPath, $uploadsDir)) {
    echo "File sách không hợp lệ. Vui lòng kiểm tra lại.";
    exit;
}
if (!file_exists($fullPath)) {
    echo "File không tồn tại.";
    exit;
}

// Thiết lập header để tải file về máy
header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="' . basename($fullPath) . '"');
header('Content-Length: ' . filesize($fullPath));

readfile($fullPath);
exit;
?>
