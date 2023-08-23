<?php  
session_start();

# Nếu admin đã đăng nhập
if (isset($_SESSION['user_id']) &&
    isset($_SESSION['user_email'])) {

	# Kết nối với CSDL
	include "../db_conn.php";


    /** 
	  kiểm tra xem book id đã được đặt
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
             # lấy sách từ CSDL
			 $sql2  = "SELECT * FROM books
			          WHERE id=?";
			 $stmt2 = $conn->prepare($sql2);
			 $stmt2->execute([$id]);
			 $the_book = $stmt2->fetch();

			 if($stmt2->rowCount() > 0){
                # xóa sách từ CSDL
				$sql  = "DELETE FROM books
				         WHERE id=?";
				$stmt = $conn->prepare($sql);
				$res  = $stmt->execute([$id]);

				/**
		      Nếu không có lỗi khi xóa dữ liệu
			    **/
			     if ($res) {
			     	# Xóa file và bìa sách hiện tại
                    $cover = $the_book['cover'];
                    $file  = $the_book['file'];
                    $c_b_c = "../uploads/cover/$cover";
                    $c_f = "../uploads/files/$cover";
                    
                    unlink($c_b_c);
                    unlink($c_f);


			     	# thông báo thành công
		     	  $sm = "Xóa thành công!";
					header("Location: ../admin.php?success=$sm");
		            exit;
			     }else{
			     	# thông báo lỗi
		     	  $em = "Xảy ra lỗi!";
					header("Location: ../admin.php?error=$em");
		            exit;
			     }
			 }else {
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