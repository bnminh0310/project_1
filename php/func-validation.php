<?php  

# xác thực form
function is_empty($var, $text, $location, $ms, $data){
   if (empty($var)) {
   	 # thông báo lỗi
   	 $em = "The ".$text." is required";
   	 header("Location: $location?$ms=$em&$data");
   	 exit;
   }
   return 0;
}