<?php 

# upload file
function upload_file($files, $allowed_exs, $path){
   # lấy dữ liệu và lưu trong var
   $file_name = $files['name'];
   $tmp_name  = $files['tmp_name'];
   $error     = $files['error'];

   # nếu không có lỗi khi upload
   if ($error === 0) {
   	  
   	  # lấy file extension lưu nó trong var
   	  $file_ex = pathinfo($file_name, PATHINFO_EXTENSION);

   	  /** 
		chuyển file extension into lower case và lưu nó trong var 
	  **/
	  $file_ex_lc = strtolower($file_ex);

	  /** 
		kiểm tra xem file extension đã tồn tại $allowed_exs array
	  **/
		if (in_array($file_ex_lc, $allowed_exs)) {
			/** 
			đổi tên file với strings ngẫu nhiên
			**/
			$new_file_name = uniqid("",true).'.'.$file_ex_lc;

			# nhập đường dẫn upload
			$file_upload_path = '../uploads/'.$path.'/'.$new_file_name;
			/** 
			  chuyển file đã upload tới thư mục root directory upload/$path 
			**/
			move_uploaded_file($tmp_name, $file_upload_path);

			/**
            Tạo associative array thông báo thành công với trạng thái và dữ liệu keys đã được đặt tên
            **/
            $sm['status'] = 'success';
	        $sm['data']   = $new_file_name;

	        #  quay về sm array
	        return $sm;
            
		}else{
		 /**
            Tạo associative array thông báo thành công với trạng thái và dữ liệu keys đã được đặt tên
	     **/
	      $em['status'] = 'error';
	      $em['data']   = "You can't upload files of this type";

	      #  quay về em array
	      return $em;
		}
   }else {
   	 /**
            Tạo associative array thông báo thành công với trạng thái và dữ liệu keys đã được đặt tên
     **/
      $em['status'] = 'error';
      $em['data']   = 'Error occurred while uploading!';

      #  quay về em array
      return $em;
   }
}