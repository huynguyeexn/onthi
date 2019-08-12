<?php
session_start(); 
if(!isset($_SESSION['user'])){
	header("location:login.php");
} 
if(isset($_SESSION['success'])){
	$success = $_SESSION['success'];
	unset($_SESSION['success']);
}
if(isset($_SESSION['error'])){
	$error = $_SESSION['error'];
	unset($_SESSION['error']);
}

$username = $_SESSION['user'];
$connect = mysqli_connect("localhost", "root", "", "onthi");
mysqli_set_charset($connect,"utf8");

$query ="SELECT a.id,b.ten,a.mota,a.gia,a.hinh FROM product a,category b WHERE a.cate_id=b.id ORDER BY a.ID DESC";  
$result = mysqli_query($connect, $query);

$theloai ="SELECT id,ten FROM category";  
$result_theloai = mysqli_query($connect, $theloai);

if(isset($_POST['lammoi'])){
	header("Refresh:0");
}

if(isset($_GET["sua"])){
	$suaid = $_GET["id"];
	$query ="SELECT * FROM product where id='$suaid'";  
	$result = mysqli_query($connect, $query);
	$row_sua = mysqli_fetch_array($result);
}


if(isset($_POST['luu'])){
	$theloai = $_POST['theloai'];
	$mota = $_POST['mota'];
	$chitiet = $_POST['chitiet'];
	$gia = $_POST['gia'];

	if(isset($_FILES["hinh"])){
		$target_dir = "uploads/";
		$target_file = $target_dir . date("mdy").date("-his-").basename($_FILES["hinh"]["name"]);
		$uploadOk = 1;
		$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));


		$check = getimagesize($_FILES["hinh"]["tmp_name"]);
		if($check !== false) {
			$uploadOk = 1;
			// Check file size
			if ($_FILES["hinh"]["size"] > 5000000) {
				$error = "File tải lên quá lớn (>5MB)";
				$uploadOk = 0;
			}
			else if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
				&& $imageFileType != "gif" )
			{
				$error = "Chỉ được tải lên các file JPG, JPEG, PNG & GIF";
				$uploadOk = 0;
			}
			else if ($uploadOk == 1)
			{
				if (move_uploaded_file($_FILES["hinh"]["tmp_name"], $target_file))
				{
					$success = "The file ".date("mdy").date("-his-"). basename( $_FILES["hinh"]["name"]). " has been uploaded.";
				}
				else
				{
					$error = "Sorry, there was an error uploading your file.";
				}
			}
		} else {
			$error = "File không phải là hình ảnh.";
			$uploadOk = 0;
		}
	}
	if(!isset($target_file))
	{
		$target_file="";
	}
	$sql_id = "select max(id) from product";
	$result = mysqli_query($connect, $sql_id);
	$row = mysqli_fetch_array($result);
	$id = $row["max(id)"]+ 1;


	$sql = "insert into product value('$id','$theloai','$mota','$chitiet','$target_file','".str_replace(",", "", $gia)."','".date("y-m-d").date(" h-i-s")."','".date("y-m-d").date(" h:i:s")."')";
	$result = mysqli_query($connect, $sql);
	if(mysqli_affected_rows($connect)==1)
	{
		$success ="Lưu thành công";
		$_SESSION['success'] = $success;
		unset($_POST);
		header("location:admin.php?them");
	}
	else
	{
		$error = "Lưu thất bại";
	}
}
if(isset($_POST['sua'])){
	$theloai = $_POST['theloai'];
	$mota = $_POST['mota'];
	$chitiet = $_POST['chitiet'];
	$gia = $_POST['gia'];

	if($_FILES["hinh"]["error"]==0){

		$target_dir = "uploads/";
		$target_file = $target_dir . date("mdy").date("-his-").basename($_FILES["hinh"]["name"]);
		$uploadOk = 1;
		$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
		$check = getimagesize($_FILES["hinh"]["tmp_name"]);
		

		if($check !== false) {
			$uploadOk = 1;
			// Check file size
			if ($_FILES["hinh"]["size"] > 5000000) {
				$error = "File tải lên quá lớn (>5MB)";
				$uploadOk = 0;
			}
			else if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
				&& $imageFileType != "gif" )
			{
				$error = "Chỉ được tải lên các file JPG, JPEG, PNG & GIF";
				$uploadOk = 0;
			}
			else if ($uploadOk == 1)
			{
				if (move_uploaded_file($_FILES["hinh"]["tmp_name"], $target_file))
				{
					$success = "The file ".date("mdy").date("-his-"). basename( $_FILES["hinh"]["name"]). " has been uploaded.";
				}
				else
				{
					$error = "Sorry, there was an error uploading your file.";
				}
			}
		} else {
			$error = "File không phải là hình ảnh.";
			$uploadOk = 0;
		}
	}
	else
	{
		$target_file=$row_sua["hinh"];
	}

	$sql_id = "select max(id) from product";
	$result = mysqli_query($connect, $sql_id);
	$row = mysqli_fetch_array($result);



	$sql = "update product set id='$suaid',cate_id='$theloai',mota='$mota',chitiet='$chitiet',hinh='$target_file',gia='".str_replace(",", "", $gia)."',created_at='".$row_sua["created_at"]."',updated_at='".date("y-m-d").date(" h:i:s")."' where id='$suaid'";
	$result = mysqli_query($connect, $sql);
	if(mysqli_affected_rows($connect)==1)
	{
		if(isset($row_sua['hinh']) && $row_sua['hinh']!=$target_file)
		{
			echo $myFile = $row_sua['hinh'];
			unlink($myFile);
		}
		$success ="Sửa thành công";
		$_SESSION['success'] = $success;
		unset($_POST);
		header("location:admin.php?sua&id=$suaid");
	}
	else
	{
		$error = "Sửa thất bại";
	}
}

?>


<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title></title>
	<link rel="stylesheet" href="style.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.min.css">
	<link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" /> 
	<script src="https://cdn.ckeditor.com/4.11.2/standard/ckeditor.js"></script>
</head>
<body>
	<div class="container">
		<?php

		if(isset($error)){
			echo '<div class="alert alert-danger"><a href="" class="close" data-dismiss="alert" aria-label="close">&times;</a>'.$error.'</div>';
			unset($error);
		}if(isset($success)){
			echo '<div class="alert alert-success"><a href="" class="close" data-dismiss="alert" aria-label="close">&times;</a>'.$success.'</div>';
			unset($success);
		}

		?>
		<div class="nav-bar">
			<div class="row">
				<div class="col-3">Quản trị hệ thống</div>
				<div class="col-9 text-right">
					<div class="user-link">
						<a href=""><?php
						if(isset($username)){
							echo $username;
						}
						?></a> |
						<a href="logout.php">Thoát</a>
					</div>				
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-3">
				<div class="side-bar">
					<ul>
						<li><a href="">Dashboard</a></li>
						<li><a href="">Thể loại</a></li>
						<li>
							<a href=".drop-side-bar" data-toggle="collapse" class="d-flex justify-content-between align-items-center">Sản phẩm <i class="fas fa-angle-down text-right"></i></a>
							<div class="drop-side-bar collapse">
								<ul>
									<li>
										<a href="admin.php?danhsach=sanpham">Danh sách</a>
									</li>
									<li>
										<a href="admin.php?them">Thêm</a>
									</li>
								</ul>
							</div>
						</li>
						<li><a href="">Tài khoản</a></li>
					</ul>
				</div>
			</div>
			<div class="col-9">
				<?php
				if(isset($_GET['danhsach'])&&$_GET['danhsach']=='sanpham'){
					echo '<h2 class="table-name">Sản phẩm</h2>
					<div class="my-table">
					<table id="example" class="table table-striped table-bordered" style="width:100%">
					<thead>
					<tr>
					<th>ID</th>
					<th>Thể loại</th>
					<th>Mô tả</th>
					<th>Giá</th>
					<th>Hình</th>
					<th></th>
					</tr>
					</thead>
					<tbody>'; 
					while($row = mysqli_fetch_array($result))  
					{  
						echo '
						<tr>  
						<td>'.$row["id"].'</td>  
						<td>'.$row["ten"].'</td>  
						<td>'.$row["mota"].'</td>  
						<td>'.$row["gia"].'</td>  
						<td><img class="hinh" src="'.$row["hinh"].'" alt="" style="
						max-width: 50px;
						max-height: 50px;
						"></td>  
						<td><a href="admin.php?sua&id='.$row["id"].'">Sửa</a> 
						<a href="xoa.php?id='.$row["id"].'">Xoá</a></td>  
						</tr>  
						';  
					}
					echo'
					</tbody>
					</table>
					</div>';
				}

				if(isset($_GET['them'])){
					echo'				<h2 class="table-name">Thêm Sản phẩm</h2>
					<div class="my-table">
					<form action="" method="post" enctype="multipart/form-data">
					<div class="form-group">
					<h4><strong>Thể Loại</strong></h4>
					<select class="custom-select" name="theloai" required>
					<option value="" selected>Thể loại...</option>
					';
					while($row = mysqli_fetch_array($result_theloai))  
					{  
						echo '<option value="'.$row["id"].'">'.$row["ten"].'</option>';  
					}
					echo'
					</select>
					</div>
					<div class="form-group">
					<h4><strong>Mô tả</strong></h4>
					<textarea name="mota" id="editor-mota" rows="10" cols="80" required=""></textarea>
					</div>
					<div class="form-group">
					<h4><strong>Chi tiết</strong></h4>
					<textarea name="chitiet" id="editor-chitiet" rows="10" cols="80" required=""></textarea>
					</div>
					<div class="form-group">
					<h4><strong>Giá</strong></h4>
					<input class="form-control" name="gia" required type="text" name="currency-field" id="currency-field" data-type="currency" placeholder="">
					</div>
					<div class="form-group">
					<h4><strong>Chọn ảnh đại diện</strong></h4>
					<div class="input-group">
					<div class="custom-file">
					<input required="" type="file" class="custom-file-input" id="customFile" name="hinh">
					<label class="custom-file-label" for="customFile">Chọn file</label>
					</div>
					</div>
					</div>
					<button type="submit" name="luu"class="btn btn-primary">Lưu</button>
					<button type="submit" name="lammoi" class="btn btn-secondary">Làm mới</button>
					</form>
					</div>
					';
				}
				if(isset($_GET['sua'])){

					echo'
					<h2 class="table-name">Sửa Sản phẩm</h2>
					<div class="my-table">
					<form action="" method="post" enctype="multipart/form-data">
					<div class="form-group">
					<h4><strong>Thể Loại</strong></h4>
					<select class="custom-select" name="theloai" required>
					<option value="" selected>Thể loại...</option>
					';
					while($row = mysqli_fetch_array($result_theloai))  
					{  
						echo '<option value="'.$row["id"].'"

						';if($row["id"] == $row_sua["cate_id"]) {
							echo 'selected';
						}
						echo'
						>'.$row["ten"].'</option>';  
					}
					echo'
					</select>
					</div>
					<div class="form-group">
					<h4><strong>Mô tả</strong></h4>
					<textarea name="mota" id="editor-mota" rows="10" cols="80" >'.$row_sua['mota'].'</textarea>
					</div>
					<div class="form-group">
					<h4><strong>Chi tiết</strong></h4>
					<textarea name="chitiet" id="editor-chitiet" rows="10" cols="80" >'.$row_sua['chitiet'].'</textarea>
					</div>
					<div class="form-group">
					<h4><strong>Giá</strong></h4>
					<input class="form-control" name="gia"  type="text" name="currency-field" id="currency-field" data-type="currency" value="'.$row_sua['gia'].'">
					</div>
					<div class="form-group">
					<h4><strong>Chọn ảnh đại diện</strong></h4>
					<div class="input-group">
					<div class="custom-file">
					<input type="file" class="custom-file-input" id="customFile" name="hinh">
					<label class="custom-file-label" for="customFile">Chọn file</label>
					</div>
					</div>
					</div>
					<button type="submit" name="sua"class="btn btn-primary">Sửa</button>
					<button type="submit" name="lammoi" class="btn btn-secondary">Làm mới</button>
					</form>
					</div>
					';
				}
				?>
			</div>
		</div>
	</div>
</div>
</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.15.0/umd/popper.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
<script>
	$(document).ready(function() {
		$('#example').DataTable();
	} );
	CKEDITOR.replace( 'editor-mota' );
	CKEDITOR.replace( 'editor-chitiet' );
	// Add the following code if you want the name of the file appear on select
	$(".custom-file-input").on("change", function() {
		var fileName = $(this).val().split("\\").pop();
		$(this).siblings(".custom-file-label").addClass("selected").html(fileName);
	});

	// Jquery Dependency

	$("input[data-type='currency']").on({
		keyup: function() {
			formatCurrency($(this));
		},
		blur: function() { 
			formatCurrency($(this), "blur");
		}
	});


	function formatNumber(n) {
  // format number 1000000 to 1,234,567
  return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",")
}


function formatCurrency(input, blur) {
  // appends $ to value, validates decimal side
  // and puts cursor back in right position.
  
  // get input value
  var input_val = input.val();
  
  // don't validate empty input
  if (input_val === "") { return; }
  
  // original length
  var original_len = input_val.length;

  // initial caret position 
  var caret_pos = input.prop("selectionStart");
  
  // check for decimal
  if (input_val.indexOf(".") >= 0) {

    // get position of first decimal
    // this prevents multiple decimals from
    // being entered
    var decimal_pos = input_val.indexOf(".");

    // split number by decimal point
    var left_side = input_val.substring(0, decimal_pos);
    var right_side = input_val.substring(decimal_pos);

    // add commas to left side of number
    left_side = formatNumber(left_side);

    // validate right side
    right_side = formatNumber(right_side);
    
    // On blur make sure 2 numbers after decimal
    if (blur === "blur") {
    	right_side += "00";
    }
    
    // Limit decimal to only 2 digits
    right_side = right_side.substring(0, 2);

    // join number by .
    input_val = "$" + left_side + "." + right_side;

} else {
    // no decimal entered
    // add commas to number
    // remove all non-digits
    input_val = formatNumber(input_val);

}

  // send updated string to input
  input.val(input_val);

  // put caret back in the right position
  var updated_len = input_val.length;
  caret_pos = updated_len - original_len + caret_pos;
  input[0].setSelectionRange(caret_pos, caret_pos);
}

</script>
</html>