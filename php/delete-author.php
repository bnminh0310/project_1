<?php  
session_start();

# Nếu admin đã đăng nhập
if (isset($_SESSION['user_id']) &&
    isset($_SESSION['user_email'])) {

	# Kết nối với CSDL
	include "../db_conn.php";


    /** 
	  kiểm tra xem author id đã được đặt
	**/
	if (isset($_GET['id'])) {
		/** 
		lấy dữ liệu từ POST request và lưu trong var
		**/
		$id = $_GET['id'];

		# xác thực form
		if (empty($id)) {
			$em = "Xảy ra lỗi!";
			header("Location: ../admin.php?error=$em");
            exit;
		}else {
            # xóa author từ CSDL
			$sql  = "DELETE FROM authors
			         WHERE id=?";
			$stmt = $conn->prepare($sql);
			$res  = $stmt->execute([$id]);

			/**
		      Nếu không có lỗi khi xóa dữ liệu
		    **/
		     if ($res) {
		     	# thông báo thành công
		     	$sm = "Xóa thành công!";
				header("Location: ../admin.php?success=$sm");
	            exit;
			 }else {
			 	  # thông báo lỗi
		     	$em = "Xảy ra lỗi!";
		    header("Location: ../admin.php?error=$em");
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