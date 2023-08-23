<?php  
session_start();

# Nếu admin đã đăng nhập
if (isset($_SESSION['user_id']) &&
    isset($_SESSION['user_email'])) {

	# Kết nối với CSDL
	include "../db_conn.php";


    /** 
	  kiểm tra tên tác giả đã được nhập chưa
	**/
	if (isset($_POST['author_name'])) {
		/** 
		lấy dữ liệu từ POST request và lưu trong var
		**/
		$name = $_POST['author_name'];

		# xác thực form
		if (empty($name)) {
			$em = "Cần điền tên tác giả";
			header("Location: ../add-author.php?error=$em");
            exit;
		}else {
			# Nhập vào CSDL
			$sql  = "INSERT INTO authors (name)
			         VALUES (?)";
			$stmt = $conn->prepare($sql);
			$res  = $stmt->execute([$name]);

			/**
		      Nếu không có lỗi khi nhập dữ liệu
		    **/
		     if ($res) {
		     	# thông báo thành công
		     	$sm = "Khởi tạo thành công!";
				header("Location: ../add-author.php?success=$sm");
	            exit;
		     }else{
		     	# thông báo lỗi
		     	$em = "Xảy ra lỗi!";
				header("Location: ../add-author.php?error=$em");
	            exit;
		     }
		}
	}else {
      header("Location: ../admin.php");
      exit;
	}

}else{
  header("Location: ../login.php");
  exit;
}