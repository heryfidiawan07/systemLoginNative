<?php

$db = mysqli_connect('localhost', 'root', 'root', 'system_login_native');

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
	$email = $data['email'];
	$password = $data['password'];
	echo $email;
}
  

?>