<?php  
session_start();

# Nếu admin đã đăng nhập
if (isset($_SESSION['user_id']) &&
    isset($_SESSION['user_email'])) {

	# Nếu book ID chưa được đặt
	if (!isset($_GET['id'])) {
		# Chuyển hướng về trang admin.php 
        header("Location: admin.php");
        exit;
	}

	$id = $_GET['id'];

	# Kết nối với CSDL
	include "db_conn.php";

    # function book
	include "php/func-book.php";
    $book = get_book($conn, $id);
    
    # Nếu ID không khả dụng
    if ($book == 0) {
    	#Redirect to admin.php page
        header("Location: admin.php");
        exit;
    }

    # function category
	include "php/func-category.php";
    $categories = get_all_categories($conn);

    # function author
	include "php/func-author.php";
    $authors = get_all_author($conn);

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Sửa sách</title>

    <!-- bootstrap 5 CDN-->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">

    <!-- bootstrap 5 Js bundle CDN-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>

</head>
<body>
	<div class="container">
		<nav class="navbar navbar-expand-lg navbar-light bg-light">
		  <div class="container-fluid">
		    <a class="navbar-brand" href="admin.php">Admin</a>
		    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
		      <span class="navbar-toggler-icon"></span>
		    </button>
		    <div class="collapse navbar-collapse" 
		         id="navbarSupportedContent">
		      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
		        <li class="nav-item">
		          <a class="nav-link" 
		             aria-current="page" 
		             href="index.php">Thư viện</a>
		        </li>
		        <li class="nav-item">
		          <a class="nav-link" 
		             href="add-book.php">Thêm sách</a>
		        </li>
		        <li class="nav-item">
		          <a class="nav-link" 
		             href="add-category.php">Thêm thể loại</a>
		        </li>
		        <li class="nav-item">
		          <a class="nav-link" 
		             href="add-author.php">Thêm tác giả</a>
		        </li>
		        <li class="nav-item">
		          <a class="nav-link" 
		             href="logout.php">Đăng xuất</a>
		        </li>
		      </ul>
		    </div>
		  </div>
		</nav>
     <form action="php/edit-book.php"
           method="post"
           enctype="multipart/form-data" 
           class="shadow p-4 rounded mt-5"
           style="width: 90%; max-width: 50rem;">

     	<h1 class="text-center pb-5 display-4 fs-3">
     		Sửa sách
     	</h1>
     	<?php if (isset($_GET['error'])) { ?>
          <div class="alert alert-danger" role="alert">
			  <?=htmlspecialchars($_GET['error']); ?>
		  </div>
		<?php } ?>
		<?php if (isset($_GET['success'])) { ?>
          <div class="alert alert-success" role="alert">
			  <?=htmlspecialchars($_GET['success']); ?>
		  </div>
		<?php } ?>
     	<div class="mb-3">
		    <label class="form-label">
		           Tên sách
		           </label>
		    <input type="text" 
		           hidden
		           value="<?=$book['id']?>" 
		           name="book_id">

		    <input type="text" 
		           class="form-control"
		           value="<?=$book['title']?>" 
		           name="book_title">
		</div>

		<div class="mb-3">
		    <label class="form-label">
		           Thông tin sách
		           </label>
		    <input type="text" 
		           class="form-control" 
		           value="<?=$book['description']?>"
		           name="book_description">
		</div>

		<div class="mb-3">
		    <label class="form-label">
		           Tác giả
		           </label>
		    <select name="book_author"
		            class="form-control">
		    	    <option value="0">
		    	    	Chọn tác giả
		    	    </option>
		    	    <?php 
                    if ($authors == 0) {
                    	# không làm gì
                    }else{
		    	    foreach ($authors as $author) { 
		    	    	if ($book['author_id'] == $author['id']) { ?>
		    	    	<option 
		    	    	  selected
		    	    	  value="<?=$author['id']?>">
		    	    	  <?=$author['name']?>
		    	        </option>
		    	        <?php }else{ ?>
						<option 
							value="<?=$author['id']?>">
							<?=$author['name']?>
						</option>
		    	   <?php }} } ?>
		    </select>
		</div>

		<div class="mb-3">
		    <label class="form-label">
		           Thể loại
		           </label>
		    <select name="book_category"
		            class="form-control">
		    	    <option value="0">
		    	    	Chọn thể loại
		    	    </option>
		    	    <?php 
                    if ($categories == 0) {
                    	# không làm gì
                    }else{
		    	    foreach ($categories as $category) { 
		    	    	if ($book['category_id'] == $category['id']) { ?>
		    	    	<option 
		    	    	  selected
		    	    	  value="<?=$category['id']?>">
		    	    	  <?=$category['name']?>
		    	        </option>
		    	        <?php }else{ ?>
						<option 
							value="<?=$category['id']?>">
							<?=$category['name']?>
						</option>
		    	   <?php }} } ?>
		    </select>
		</div>

		<div class="mb-3">
		    <label class="form-label">
		           Bìa sách
		           </label>
		    <input type="file" 
		           class="form-control" 
		           name="book_cover">

		     <input type="text" 
		           hidden
		           value="<?=$book['cover']?>" 
		           name="current_cover">

		    <a href="uploads/cover/<?=$book['cover']?>"
		       class="link-dark">Bìa sách hiện tại</a>
		</div>

		<div class="mb-3">
		    <label class="form-label">
		           File
		           </label>
		    <input type="file" 
		           class="form-control" 
		           name="file">

		     <input type="text" 
		           hidden
		           value="<?=$book['file']?>" 
		           name="current_file">

		    <a href="uploads/files/<?=$book['file']?>"
		       class="link-dark">File hiện tại</a>
		</div>

	    <button type="submit" 
	            class="btn btn-primary">
	            Cập nhật</button>
     </form>
	</div>
</body>
</html>

<?php }else{
  header("Location: login.php");
  exit;
} ?>