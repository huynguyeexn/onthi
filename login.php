<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title></title>
	<link rel="stylesheet" href="style.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<?php 
if(isset($_POST['ok']))
{
	$user = $_POST['email'];
	$pass = (trim(md5($_POST['password'])));
	$connect = mysqli_connect("localhost","root","","onthi") or die("Kết nối thất bại");
	mysqli_set_charset($connect,"utf8");
	$sql = "select * from accounts where username='$user' and password='$pass'";
	$result = mysqli_query($connect,$sql);
	if(mysqli_num_rows($result)>0)
	{
		$success = "đăng nhập thành công";
		$data = mysqli_fetch_assoc($result);
		$_SESSION['user'] = $user;
		$_SESSION['dn']=true;
		header("location:admin.php");
	}
	else
	{
		$error = "Email hoặc mật khẩu không tồn tại";
	}
}
?>
<body>
	<div class="container">
		<div class="row">
			<div class="col-6 m-auto">
				<form action="" method="post" accept-charset="utf-8" class="login-form">
					<h5 class="login-header">Xin vui lòng đăng nhập</h5>
					<?php

					if(isset($error)){
						echo '<div class="alert alert-danger">'.$error.'</div>';
						unset($error);
					}if(isset($success)){
						echo '<div class="alert alert-success">'.$success.'</div>';
						unset($success);
					}

					?>
					<div class="form-group">
						<input type="text" class="form-control" name="email" aria-describedby="emailHelp" placeholder="Emai/Tên tài khoản"required="">
					</div>
					<div class="form-group">
						<input type="password" class="form-control" name="password" placeholder="Mật khẩu" required="">
					</div>
					<button type="submit" class="w-100 btn btn-primary b-g" name="ok">Đăng nhập</button>
				</form>
			</div>
		</div>
	</div>
</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.15.0/umd/popper.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.min.js"></script>
</html>