<?php

$db = mysqli_connect('localhost', 'root', '', 'system_login_native');

if( !$db ){
    die("Gagal terhubung dengan database: " . mysqli_connect_error());
}

function register ($data){
	global $db;

	$email = strtolower(stripslashes($data['email']));
	$password = mysqli_real_escape_string($db, $data['password']);
	
	//Check email duplicate
	$result = mysqli_query($db, "SELECT email FROM users WHERE email = '$email'");
	if (mysqli_fetch_assoc($result)) {
		echo "<script>
		        alert('Email sudah terdaftar !')
		     </script>";
	      return false;
	}
	
	//Check password confirm
	if ($data['password'] != $data['confirmPassword']) {
		echo "<script>
		        alert('Konfirmasi password tidak sesuai !')
		     </script>";
	    return false;
	}
	
	//Enkripsi password
	$password = password_hash($password, PASSWORD_DEFAULT);
	// Insert to database
	mysqli_query($db, "INSERT INTO users VALUES('','$email', '$password')");
	return mysqli_affected_rows($db);
}


function login ($data){
	global $db;

	$email = $data['email'];
	$password = $data['password'];

	$result = mysqli_query($db, "SELECT * FROM users WHERE email = '$email'");
	//Cek email
	if (mysqli_num_rows($result) === 1) {
		//Cek password
		$row = mysqli_fetch_assoc($result);
		if (password_verify($password, $row['password'])){
			//Set session
			session_start();
			$_SESSION['login'] = true;

			//Remember me
			if (isset($data['remember'])) {
				//Set cookie
				setcookie('key', $row['id'], time()+60);
				setcookie('val', hash('sha256',$row['email']) , time()+60);
			}

			header("Location: index.php");
			exit;
		}
	}
	//Error
	$error = true;
}
  

?>