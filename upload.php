<!DOCTYPE html>
<html>

<head>
  <title>Quản lý tệp tin</title>
</head>

<body>
  <h1>Quản lý tệp tin</h1>

  <?php
  // Đường dẫn đến thư mục upload
  $uploadDir = 'C:/xampp/htdocs/web-nang-cao/session-login/dowload/';

  // Xử lý khi người dùng tải lên tệp tin
  if (isset($_FILES['myfile'])) {
    $file = $_FILES['myfile'];

    // Kiểm tra kích thước tệp tin
    if ($file['size'] > 2097152) {
      echo "Kích thước tệp tin vượt quá giới hạn.";
    } else {
      // Tạo tên mới cho tệp tin
      $filename = generateFileName($file['name']);
      $uploadPath = $uploadDir . $filename;

      // Di chuyển tệp tin vào thư mục upload
      if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
        // Lưu thông tin tệp tin vào CSDL
        saveFileInfo($filename, $file['type'], $file['size']);

        echo "Tệp tin đã được tải lên thành công.";
      } else {
        echo "Đã xảy ra lỗi khi tải lên tệp tin.";
      }
    }
  }

  // Hiển thị danh sách tệp tin đã tải lên
  $files = getUploadedFiles();
  if (count($files) > 0) {
    echo "<h2>Danh sách tệp tin đã tải lên:</h2>";
    echo "<table>";
    echo "<tr><th><a href='?sort=name'>Tên tệp tin</a></th><th><a href='?sort=upload_date'>Ngày tải lên</a></th><th>Loại</th><th>Kích thước</th><th>Xóa</th></tr>";

    // Sắp xếp danh sách tệp tin nếu người dùng nhấp vào tiêu đề
    $sort = isset($_GET['sort']) ? $_GET['sort'] : '';
    if ($sort == 'name') {
      usort($files, function ($a, $b) {
        return strcmp($a['filename'], $b['filename']);
      });
    } elseif ($sort == 'upload_date') {
      usort($files, function ($a, $b) {
        return strtotime($b['upload_date']) - strtotime($a['upload_date']);
      });
    }

    foreach ($files as $file) {
      echo "<tr>";
      echo "<td>{$file['filename']}</td>";
      echo "<td>{$file['upload_date']}</td>";
      echo "<td>{$file['filetype']}</td>";
      echo "<td>{$file['filesize']}</td>";
      echo "<td><a href='delete.php?filename={$file['filename']}'>Xóa</a></td>";
      echo "</tr>";
    }

    echo "</table>";
  } else {
    echo "<p>Chưa có tệp tin nào được tải lên.</p>";
  }

  // Hàm tạo tên mới cho tệp tin
  function generateFileName($originalName)
  {
    $timestamp = date('ymdHis');
    $randomString = substr(md5(rand()), 0, 8);

    $extension = pathinfo($originalName, PATHINFO_EXTENSION);
    $newFileName = $timestamp . '_' . $randomString . '.' . $extension;

    return $newFileName;
  }

  // Hàm kết nối đến CSDL
  function connectToDatabase()
  {
    $servername  = 'localhost';
    $username = 'root';
    $password = '';
    $database = 'upload_file';

    $conn = new mysqli($servername , $username, $password, $database);

    if ($conn->connect_error) {
      die("Kết nối CSDL thất bại: " . $conn->connect_error);
    }

    return $conn;
  }

  // Hàm lưu thông tin tệp tin vào CSDL
  function saveFileInfo($filename, $filetype, $filesize)
  {
    $conn = connectToDatabase();

    $upload_date = date("Y-m-d");  // Ngày hiện tại
  
    $sql = "INSERT INTO files (filename, filetype, upload_date, filesize) VALUES ('$filename', '$filetype', '$upload_date', '$filesize')";

    if ($conn->query($sql) === TRUE) {
      echo "Thông tin tệp tin đã được lưu vào CSDL.";
    } else {
      echo "Lỗi khi lưu thông tin tệp tin vào CSDL: " . $conn->error;
    }

    $conn->close();
  }

  // Hàm lấy danh sách tệp tin đã tải lên từ CSDL
  function getUploadedFiles()
  {
    $conn = connectToDatabase();

    $sql = "SELECT * FROM files";
    $result = $conn->query($sql);

    $files = array();

    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        $file = array(
          'id' => $row['id'],
          'filename' => $row['filename'],
          'filetype' => $row['filetype'],
          'upload_date' => $row['upload_date'],
          'filesize' => $row['filesize']
        );

        $files[] = $file;
      }
    }

    $conn->close();

    return $files;
  }

  // Xử lý khi người dùng yêu cầu xóa tệp tin
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
  ?>

  <h2>Tải lên tệp tin</h>
    <form action="" method="post" enctype="multipart/form-data">
      <input type="file" name="myfile">
      <input type="submit" value="Tải lên">
    </form>

</body>

</html>