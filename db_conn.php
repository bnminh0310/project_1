<?php 

# tên server
$sName = "localhost";
# tên user
$uName = "root";
# mật khẩu
$pass = "";

# tên CSDL
$db_name = "online_book_store_db";

/**
tạo kết nối CSDL sử dụng PHP Data Objects (PDO)
**/
try {
    $conn = new PDO("mysql:host=$sName;dbname=$db_name", 
                    $uName, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}catch(PDOException $e){
  echo "Connection failed : ". $e->getMessage();
}