<?php

session_start(); 
if(isset($_GET['id']))
{
	$id = $_GET['id'];

	$connect = mysqli_connect("localhost", "root", "", "onthi");
	mysqli_set_charset($connect,"utf8");
	$sql_id = "select * from product where id='$id'";
	$result = mysqli_query($connect, $sql_id);
	$row = mysqli_fetch_array($result);
}
if(isset($_POST['xoa'])){
	$sql = "DELETE FROM product WHERE id='$id'";
	$result = mysqli_query($connect, $sql);
	if(mysqli_affected_rows($connect)==1)
	{
		$success ="Xoá thành công";
		$_SESSION['success'] = $success;
		unset($_POST);
		header("location:admin.php?danhsach=sanpham");
	}
	else
	{
		$error = "Xoá thất bại";
		$_SESSION['error'] = $error;
		unset($_POST);
		header("location:admin.php?danhsach=sanpham");
	}
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Xoá</title>
	<link rel="stylesheet" href="style.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.min.css">
	<link rel="stylesheet" href="">
</head>
<body>
	<div class="container">
		<div class="row">
			<div class="col-9 text-center mx-auto mt-5">
				<form action="" method="post">
					<h2>Bạn muốn xoá sản phẩm có mã: <?php echo $row['id']?></h2>
					<button type="submit" name="xoa" class="btn btn-secondary">Xác nhận</button>
					<button type="submit" name="huy" class="btn btn-secondary">Huỷ bỏ</button>
				</form>
			</div>
		</div>
	</div>
</body>
</html>