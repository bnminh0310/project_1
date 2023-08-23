<?php 
session_start();

if (isset($_POST['email']) && 
	isset($_POST['password'])) {
    
	# Kết nối với CSDL
	include "../db_conn.php";
    
    # xác thực form
	include "func-validation.php";
	
	/** 
	 lấy dữ liệu từ POST request và lưu trong var

	**/

	$email = $_POST['email'];
	$password = $_POST['password'];

	# xác thực form

	$text = "Địa chỉ Email";
	$location = "../login.php";
	$ms = "có lỗi";
    is_empty($email, $text, $location, $ms, "");

    $text = "Mật khẩu";
	$location = "../login.php";
	$ms = "có lỗi";
    is_empty($password, $text, $location, $ms, "");

    # tìm kiếm email
    $sql = "SELECT * FROM admin 
            WHERE email=?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$email]);

    # nếu email tồn tại
    if ($stmt->rowCount() === 1) {
    	$user = $stmt->fetch();

    	$user_id = $user['id'];
    	$user_email = $user['email'];
    	$user_password = $user['password'];
    	if ($email === $user_email) {
    		if (password_verify($password, $user_password)) {
    			$_SESSION['user_id'] = $user_id;
    			$_SESSION['user_email'] = $user_email;
    			header("Location: ../admin.php");
    		}else {
    			# Thông báo lỗi
    	        $em = "email hoặc mật khẩu không chính xác";
    	        header("Location: ../login.php?error=$em");
    		}
    	}else {
    		# Thông báo lỗi
    	    $em = "email hoặc mật khẩu không chính xác";
    	    header("Location: ../login.php?error=$em");
    	}
    }else{
    	# Thông báo lỗi
    	$em = "email hoặc mật khẩu không chính xác";
    	header("Location: ../login.php?error=$em");
    }

}else {
	# Chuyển hướng về "../login.php"
	header("Location: ../login.php");
}