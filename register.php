<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title></title>
	<link rel="stylesheet" href="">
</head>
<?php 
if(isset($_POST['ok']))
{
	$user = $_POST['user'];
	$pass = (trim($_POST['pass']));
	$retypepass = (trim($_POST['retypepass']));
	$connect = mysqli_connect("localhost","root","","onthi") or die("Kết nối thất bại");
	    // khai báo tiếng việt mã unicode
	mysqli_set_charset($connect,"utf8");
	$sql = "select * from accounts where username='$user'";
	$kq = mysqli_query($connect,$sql);
	$data = mysqli_fetch_assoc($kq);
	if(isset($data)>0)
	{
		echo "Tên tài khoản đã tồn tại";
	}
	else if($pass!=$retypepass){
		echo "Xác nhận mật khẩu không đúng";
		die;
	}
	else
	{
		$sql = "insert into accounts(username,password) value('$user','".md5($pass)."')";
		$result = mysqli_query($connect,$sql);
		if(mysqli_affected_rows($connect)==1)
		{
			echo "đăng ký thành công";
			$_SESSION['user'] = $user; // lưu giá trị vào session masv
			$_SESSION['dn']=true;
		}
		else
		{
			echo "đăng ký thất bại";
		}
	}
}
?>
<body>
	<div class="box">
		<h1>Login</h1>
		<form action="#" method="post" accept-charset="utf-8">
			<input type="text" name="user" required="" value="<?php if(isset($user)) echo $user;?>">
			<label>Username</label>
			<input type="password" name="pass" required="">
			<label>Password</label>
			<input type="password" name="retypepass" required="">
			<label>Retype Password</label>
			<input type="submit" name="ok" value="Login">
		</form>
	</div>
</body>
</html>