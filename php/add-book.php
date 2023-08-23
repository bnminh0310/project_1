<?php  
session_start();

# Nếu admin đã đăng nhập
if (isset($_SESSION['user_id']) &&
    isset($_SESSION['user_email'])) {

	# Kết nối với CSDL
	include "../db_conn.php";

    # function xác thực
    include "func-validation.php";

    # function upload file
    include "func-file-upload.php";


    /** 
	  Nếu tất cả chỗ trống được điền
	**/
	if (isset($_POST['book_title'])       &&
        isset($_POST['book_description']) &&
        isset($_POST['book_author'])      &&
        isset($_POST['book_category'])    &&
        isset($_FILES['book_cover'])      &&
        isset($_FILES['file'])) {
		/** 
		lấy dữ liệu từ POST request và lưu trong var
		**/
		$title       = $_POST['book_title'];
		$description = $_POST['book_description'];
		$author      = $_POST['book_author'];
		$category    = $_POST['book_category'];

		# tạo URL
		$user_input = 'title='.$title.'&category_id='.$category.'&desc='.$description.'&author_id='.$author;

		# xác thực form

        $text = "Book title";
        $location = "../add-book.php";
        $ms = "error";
		is_empty($title, $text, $location, $ms, $user_input);

		$text = "Book description";
        $location = "../add-book.php";
        $ms = "error";
		is_empty($description, $text, $location, $ms, $user_input);

		$text = "Book author";
        $location = "../add-book.php";
        $ms = "error";
		is_empty($author, $text, $location, $ms, $user_input);

		$text = "Book category";
        $location = "../add-book.php";
        $ms = "error";
		is_empty($category, $text, $location, $ms, $user_input);
        
        # upload bìa sách
        $allowed_image_exs = array("jpg", "jpeg", "png");
        $path = "cover";
        $book_cover = upload_file($_FILES['book_cover'], $allowed_image_exs, $path);

        /**
	    nếu có lỗi khi upload bìa sách
	    **/
	    if ($book_cover['status'] == "error") {
	    	$em = $book_cover['data'];

	    	/**
	    	  Chuyển hướng về '../add-book.php' và hiện thông báo lỗi & user_input
	    	**/
	    	header("Location: ../add-book.php?error=$em&$user_input");
	    	exit;
	    }else {
	    	# upload file 
            $allowed_file_exs = array("pdf", "docx", "pptx");
            $path = "files";
            $file = upload_file($_FILES['file'], $allowed_file_exs, $path);

            /**
		    Nếu có lỗi khi upload file
		    **/
		    if ($file['status'] == "error") {
		    	$em = $file['data'];

		    	/**
		    	  Chuyển hướng về '../add-book.php' và hiện thông báo lỗi & user_input
		    	**/
		    	header("Location: ../add-book.php?error=$em&$user_input");
		    	exit;
		    }else {
		    	/**
		          lấy tên file và bìa sách mới
		        **/
		        $file_URL = $file['data'];
		        $book_cover_URL = $book_cover['data'];
                
                # Nhập vào CSDL
                $sql  = "INSERT INTO books (title,
                                            author_id,
                                            description,
                                            category_id,
                                            cover,
                                            file)
                         VALUES (?,?,?,?,?,?)";
                $stmt = $conn->prepare($sql);
			    $res  = $stmt->execute([$title, $author, $description, $category, $book_cover_URL, $file_URL]);

			/**
		      Nếu không có lỗi khi nhập vào CSDL
		    **/
		     if ($res) {
		     	# thông báo thành công
		     	$sm = "Khởi tạo thành công!";
				header("Location: ../add-book.php?success=$sm");
	            exit;
		     }else{
		     	# thông báo lỗi
		     	$em = "Xảy ra lỗi!";
				header("Location: ../add-book.php?error=$em");
	            exit;
		     }

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