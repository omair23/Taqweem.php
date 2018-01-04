<?php
session_start();
session_unset();
session_destroy();

require_once('../Connections/SQL.php');

$inputEmail = $_POST["inputEmail"];
$inputPassword = $_POST["inputPassword"];
$stoken = $_POST["inputST"];

$UserLogged = 0;
$sql = "SELECT * FROM USER WHERE EMAIL_ADDRESS='$inputEmail' AND PASSWORD='$inputPassword'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
        $UserLogged = 1;
}
else{
    header("Location: index.php?Error=1");
    die();
}

if ($UserLogged == 1) {
	$sql = "SELECT * FROM  PARAMETERS WHERE NAME='SECURITY_TOKEN' AND VALUE='$stoken'";
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			session_start();
			$_SESSION['SecurityTokenAdmin'] = 777;
			$_SESSION['UserLogged'] = 'Yes';
			$_SESSION['inputEmail'] = $inputEmail;
		}
		header("Location: home.php");
		die();
	}
	else{
		header("Location: index.php?Error=9");
		die();
	}
}	else{
		header("Location: index.php?Error=3");
		die();
	}

?>
