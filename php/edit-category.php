<?php  
session_start();

# Nếu admin đã đăng nhập
if (isset($_SESSION['user_id']) &&
    isset($_SESSION['user_email'])) {

	# Kết nối với CSDL
	include "../db_conn.php";


    /** 
	  	  kiểm tra tên thể loại đã được nhập chưa
	**/
	if (isset($_POST['category_name']) &&
        isset($_POST['category_id'])) {
		/** 
		lấy dữ liệu từ POST request và lưu trong var
		**/
		$name = $_POST['category_name'];
		$id = $_POST['category_id'];

		# xác thực form
		if (empty($name)) {
			$em = "The category name is required";
			header("Location: ../edit-category.php?error=$em&id=$id");
            exit;
		}else {
			# Cập nhật CSDL
			$sql  = "UPDATE categories 
			         SET name=?
			         WHERE id=?";
			$stmt = $conn->prepare($sql);
			$res  = $stmt->execute([$name, $id]);

			/**
		      Nếu không có lỗi khi nhập dữ liệu
		    **/
		     if ($res) {
		     	# thông báo thành công
		     	$sm = "Cập nhật thành công!";
				header("Location: ../edit-category.php?success=$sm&id=$id");
	            exit;
		     }else{
		     	# thông báo lỗi
		     	$em = "Xảy ra lỗi";
				header("Location: ../edit-category.php?error=$em&id=$id");
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