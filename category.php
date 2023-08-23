<?php 
session_start();

# Nếu category ID chưa được đặt
if (!isset($_GET['id'])) {
	header("Location: index.php");
	exit;
}

# Lấy category ID từ GET request
$id = $_GET['id'];

# Kết nối với CSDL
include "db_conn.php";

# function book
include "php/func-book.php";
$books = get_books_by_category($conn, $id);

# function author
include "php/func-author.php";
$authors = get_all_author($conn);

# function category
include "php/func-category.php";
$categories = get_all_categories($conn);
$current_category = get_category($conn, $id);

 ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?=$current_category['name']?></title>

    <!-- bootstrap 5 CDN-->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">

    <!-- bootstrap 5 Js bundle CDN-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="css/style.css">

</head>
<body>
	<div class="container">
		<nav class="navbar navbar-expand-lg navbar-light bg-light">
		  <div class="container-fluid">
		    <a class="navbar-brand" href="index.php">Thư Viện Online</a>
		    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
		      <span class="navbar-toggler-icon"></span>
		    </button>
		    <div class="collapse navbar-collapse" 
		         id="navbarSupportedContent">
		      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
		        <li class="nav-item">
		          <a class="nav-link active" 
		             aria-current="page" 
		             href="index.php">Thư viện</a>
		        </li>
		        <li class="nav-item">
		          <a class="nav-link" 
		             href="#">Liên hệ</a>
		        </li>
		        <li class="nav-item">
		          <a class="nav-link" 
		             href="#">Thông tin</a>
		        </li>
		        <li class="nav-item">
		          <?php if (isset($_SESSION['user_id'])) {?>
		          	<a class="nav-link" 
		             href="admin.php">Admin</a>
		          <?php }else{ ?>
		          <a class="nav-link" 
		             href="login.php">Đăng nhập</a>
		          <?php } ?>

		        </li>
		      </ul>
		    </div>
		  </div>
		</nav>
		<h1 class="display-4 p-3 fs-3"> 
			<a href="index.php"
			   class="nd">
				<img src="img/back-arrow.PNG" 
				     width="35">
			</a>
		   <?=$current_category['name']?>
		</h1>
		<div class="d-flex pt-3">
			<?php if ($books == 0){ ?>
				<div class="alert alert-warning 
        	            text-center p-5" 
        	     role="alert">
        	     <img src="img/empty.png" 
        	          width="100">
        	     <br>
			    Nếu không có sách trong CSDL
		       </div>
			<?php }else{ ?>
			<div class="pdf-list d-flex flex-wrap">
				<?php foreach ($books as $book) { ?>
				<div class="card m-1">
					<img src="uploads/cover/<?=$book['cover']?>"
					     class="card-img-top">
					<div class="card-body">
						<h5 class="card-title">
							<?=$book['title']?>
						</h5>
						<p class="card-text">
							<i><b>Tác giả:
								<?php foreach($authors as $author){ 
									if ($author['id'] == $book['author_id']) {
										echo $author['name'];
										break;
									}
								?>

								<?php } ?>
							<br></b></i>
							<?=$book['description']?>
							<br><i><b>Thể loại:
								<?php foreach($categories as $category){ 
									if ($category['id'] == $book['category_id']) {
										echo $category['name'];
										break;
									}
								?>

								<?php } ?>
							<br></b></i>
						</p>
                       <a href="uploads/files/<?=$book['file']?>"
                          class="btn btn-success">Mở</a>

                        <a href="uploads/files/<?=$book['file']?>"
                          class="btn btn-primary"
                          download="<?=$book['title']?>">Tải về</a>
					</div>
				</div>
				<?php } ?>
			</div>
		<?php } ?>

		<div class="category">
			<!-- Danh mục thể loại -->
			<div class="list-group">
				<?php if ($categories == 0){
					// không làm gì
				}else{ ?>
				<a href="#"
				   class="list-group-item list-group-item-action active">Thể loại</a>
				   <?php foreach ($categories as $category ) {?>
				  
				   <a href="category.php?id=<?=$category['id']?>"
				      class="list-group-item list-group-item-action">
				      <?=$category['name']?></a>
				<?php } } ?>
			</div>

			<!-- Danh mục tác giả -->
			<div class="list-group mt-5">
				<?php if ($authors == 0){
					// không làm gì
				}else{ ?>
				<a href="#"
				   class="list-group-item list-group-item-action active">Tác giả</a>
				   <?php foreach ($authors as $author ) {?>
				  
				   <a href="author.php?id=<?=$author['id']?>"
				      class="list-group-item list-group-item-action">
				      <?=$author['name']?></a>
				<?php } } ?>
			</div>
		</div>
		</div>
	</div>
</body>
</html>