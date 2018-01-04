<?php
session_start();
session_unset();
session_destroy();

require_once('Connections/SQL.php');
$inputEmail = $_POST["inputEmail"];
$_SESSION['inputEmail'] = $inputEmail;
$inputPassword = $_POST["inputPassword"];
$sql = "SELECT * FROM USER WHERE EMAIL_ADDRESS='$inputEmail' AND PASSWORD='$inputPassword'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        session_start();
        $_SESSION['UserLogged'] = 'Yes';
        $_SESSION['inputEmail'] = $inputEmail;
        $_SESSION['MasjidID'] = $row["MASJID_ID"];
    }
    header("Location: ControlPanel/index.php");
    die();
}
else{
    header("Location: login.php?Error=1");
    die();
}

?>
