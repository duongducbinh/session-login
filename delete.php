<?php
function connectToDatabase()
{
    $servername = 'localhost';
    $username = 'root';
    $password = '';
    $database = 'upload_file';

    $conn = new mysqli($servername, $username, $password, $database);

    if ($conn->connect_error) {
        die("Kết nối CSDL thất bại: " . $conn->connect_error);
    }

    return $conn;
}

if (isset($_GET['filename'])) {
    $filename = $_GET['filename'];

    // Xóa tệp tin từ hệ thống tệp tin
    $uploadDir = 'C:/xampp/htdocs/web-nang-cao/session-login/dowload/'; // Đường dẫn đến thư mục upload
    $filePath = $uploadDir . $filename;

    if (file_exists($filePath)) {
        if (unlink($filePath)) {
            // Xóa thông tin tệp tin từ CSDL
            $conn = connectToDatabase();

            $sql = "DELETE FROM files WHERE filename = '$filename'";

            if ($conn->query($sql) === TRUE) {
                echo "Tệp tin đã được xóa thành công.";
            } else {
                echo "Lỗi khi xóa thông tin tệp tin từ CSDL: " . $conn->error;
            }

            $conn->close();
        } else {
            echo "Lỗi khi xóa tệp tin từ hệ thống tệp tin.";
        }
    } else {
        echo "Tệp tin không tồn tại trong hệ thống tệp tin.";
    }

}
header("Location: upload.php");
exit();
?>