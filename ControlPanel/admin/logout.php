<?
/*session_start();
session_unset();
session_destroy();
unset($_SESSION['UserLogged']);*/

session_start();
setcookie(session_name(), '', 100);
session_unset();
session_destroy();
$_SESSION = array();


header("location: ../index.php");
exit();
?>
