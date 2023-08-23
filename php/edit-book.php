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
	if (isset($_POST['book_id'])          &&
        isset($_POST['book_title'])       &&
        isset($_POST['book_description']) &&
        isset($_POST['book_author'])      &&
        isset($_POST['book_category'])    &&
        isset($_FILES['book_cover'])      &&
        isset($_FILES['file'])            &&
        isset($_POST['current_cover'])    &&
        isset($_POST['current_file'])) {

		/** 
		lấy dữ liệu từ POST request và lưu trong var
		**/
		$id          = $_POST['book_id'];
		$title       = $_POST['book_title'];
		$description = $_POST['book_description'];
		$author      = $_POST['book_author'];
		$category    = $_POST['book_category'];
        
         /** 
	      Lấy bìa sách và file hiện tại từ POST request và lưu trong var
	    **/

        $current_cover = $_POST['current_cover'];
        $current_file  = $_POST['current_file'];

        # xác thực form
        $text = "Tên sách";
        $location = "../edit-book.php";
        $ms = "id=$id&error";
		is_empty($title, $text, $location, $ms, "");

		$text = "Thông tin sách";
        $location = "../edit-book.php";
        $ms = "id=$id&error";
		is_empty($description, $text, $location, $ms, "");

		$text = "Tác giả";
        $location = "../edit-book.php";
        $ms = "id=$id&error";
		is_empty($author, $text, $location, $ms, "");

		$text = "Thể loại";
        $location = "../edit-book.php";
        $ms = "id=$id&error";
		is_empty($category, $text, $location, $ms, "");

        /**
          nếu admin cập nhật bìa sách
        **/
          if (!empty($_FILES['book_cover']['name'])) {
          	  /**
		          nếu admin cập nhật cả 2
		      **/
		      if (!empty($_FILES['file']['name'])) {
		      	# cập nhật cả 2

		        # upload bìa sách
		        $allowed_image_exs = array("jpg", "jpeg", "png");
		        $path = "cover";
		        $book_cover = upload_file($_FILES['book_cover'], $allowed_image_exs, $path);

		        # upload bìa sách
		        $allowed_file_exs = array("pdf", "docx", "pptx");
		        $path = "files";
		        $file = upload_file($_FILES['file'], $allowed_file_exs, $path);
                
                /**
				    Nếu có lỗi khi upload
				**/
		        if ($book_cover['status'] == "error" || 
		            $file['status'] == "error") {

			    	$em = $book_cover['data'];

			    	/**
			    	  Chuyển hướng về '../edit-book.php' và hiện thông báo lỗi & the id
			    	**/
			    	header("Location: ../edit-book.php?error=$em&id=$id");
			    	exit;
			    }else {
                  # đường dẫn bìa sách hiện tại
			      $c_p_book_cover = "../uploads/cover/$current_cover";

			      # đường dẫn file hiện tại
			      $c_p_file = "../uploads/files/$current_file";

			      # xóa khỏi server
			      unlink($c_p_book_cover);
			      unlink($c_p_file);

			      /**
		              lấy tên file và bìa sách mới 
		          **/
		           $file_URL = $file['data'];
		           $book_cover_URL = $book_cover['data'];

		            # chỉ cập nhật dữ liệu
		          	$sql = "UPDATE books
		          	        SET title=?,
		          	            author_id=?,
		          	            description=?,
		          	            category_id=?,
		          	            cover=?,
		          	            file=?
		          	        WHERE id=?";
		          	$stmt = $conn->prepare($sql);
					$res  = $stmt->execute([$title, $author, $description, $category,$book_cover_URL, $file_URL, $id]);

				    /**
		      Nếu không có lỗi khi cập nhật dữ liệu
				    **/
				     if ($res) {
				     	# thông báo thành công
		     	    $sm = "Cập nhật thành công!";
						header("Location: ../edit-book.php?success=$sm&id=$id");
			            exit;
				     }else{
				     	# thông báo lỗi
		     	    $em = "Xảy ra lỗi";
						header("Location: ../edit-book.php?error=$em&id=$id");
			            exit;
				     }


			    }
		      }else {
		      	# chỉ cập nhật bìa sách

		      	# upload bìa sách
		        $allowed_image_exs = array("jpg", "jpeg", "png");
		        $path = "cover";
		        $book_cover = upload_file($_FILES['book_cover'], $allowed_image_exs, $path);
                
                /**
				    Nếu có lỗi khi upload
				**/
		        if ($book_cover['status'] == "error") {

			    	$em = $book_cover['data'];

			    	/**
			    	  Chuyển hướng về '../edit-book.php' và hiện thông báo lỗi & the id
			    	**/
			    	header("Location: ../edit-book.php?error=$em&id=$id");
			    	exit;
			    }else {
                  # đường dẫn bìa sách hiện tại
			      $c_p_book_cover = "../uploads/cover/$current_cover";

			      # Xóa khỏi server
			      unlink($c_p_book_cover);

			      /**
		              lấy tên file và bìa sách mới
		          **/
		           $book_cover_URL = $book_cover['data'];

		            # chỉ cập nhật dữ liệu
		          	$sql = "UPDATE books
		          	        SET title=?,
		          	            author_id=?,
		          	            description=?,
		          	            category_id=?,
		          	            cover=?
		          	        WHERE id=?";
		          	$stmt = $conn->prepare($sql);
					$res  = $stmt->execute([$title, $author, $description, $category,$book_cover_URL, $id]);

				    /**
		      Nếu không có lỗi khi cập nhật dữ liệu
				    **/
				     if ($res) {
				     	# thông báo thành công
		     	    $sm = "Cập nhật thành công!";
						header("Location: ../edit-book.php?success=$sm&id=$id");
			            exit;
				     }else{
				     	# thông báo lỗi
		     	    $em = "Xảy ra lỗi";
						header("Location: ../edit-book.php?error=$em&id=$id");
			            exit;
				     }


			    }
		      }
          }
          /**
          nếu admin chỉ cập nhật file

          **/
          else if(!empty($_FILES['file']['name'])){
          	# chỉ cập nhật file
            
            # upload bìa sách
	        $allowed_file_exs = array("pdf", "docx", "pptx");
	        $path = "files";
	        $file = upload_file($_FILES['file'], $allowed_file_exs, $path);
            
            /**
			    nếu có lỗi khi upload
			**/
	        if ($file['status'] == "error") {

		    	$em = $file['data'];

		    	/**
		    	  Chuyển hướng về '../edit-book.php' và hiện thông báo lỗi & the id
		    	**/
		    	header("Location: ../edit-book.php?error=$em&id=$id");
		    	exit;
		    }else {
              # đường dẫn bìa sách hiện tại
		      $c_p_file = "../uploads/files/$current_file";

		      # Xóa khỏi server
		      unlink($c_p_file);

		      /**
		        lấy tên file và bìa sách mới 
	          **/
	           $file_URL = $file['data'];

          	# chỉ cập nhật dữ liệu
	          	$sql = "UPDATE books
	          	        SET title=?,
	          	            author_id=?,
	          	            description=?,
	          	            category_id=?,
	          	            file=?
	          	        WHERE id=?";
	          	$stmt = $conn->prepare($sql);
				$res  = $stmt->execute([$title, $author, $description, $category, $file_URL, $id]);

			    /**
		      Nếu không có lỗi khi cập nhật dữ liệu
			    **/
			     if ($res) {
			     	# thông báo thành công
		       	$sm = "Cập nhật thành công!";
					header("Location: ../edit-book.php?success=$sm&id=$id");
		            exit;
			     }else{
			     	# thông báo lỗi
		     	$em = "Xảy ra lỗi";
					header("Location: ../edit-book.php?error=$em&id=$id");
		            exit;
			     }


		    }
	      
          }else {
          	# chỉ cập nhật dữ liệu
          	$sql = "UPDATE books
          	        SET title=?,
          	            author_id=?,
          	            description=?,
          	            category_id=?
          	        WHERE id=?";
          	$stmt = $conn->prepare($sql);
			$res  = $stmt->execute([$title, $author, $description, $category, $id]);

		    /**
		      Nếu không có lỗi khi cập nhật dữ liệu
		    **/
		     if ($res) {
		     	# thông báo thành công
		     	$sm = "Cập nhật thành công!";
				header("Location: ../edit-book.php?success=$sm&id=$id");
	            exit;
		     }else{
		     	# thông báo lỗi
		     	$em = "Xảy ra lỗi";
				header("Location: ../edit-book.php?error=$em&id=$id");
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