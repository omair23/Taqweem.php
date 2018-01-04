<?php
session_start();
require_once('Connections/SQL.php');
$MASJID_ID = $_SESSION['MasjidID'];
$inputName = $_POST["inputName"];
$inputTown = $_POST["inputTown"];
$inputCountry = $_POST["inputCountry"];
$inputLatitude = $_POST["inputLatitude"];
$inputLongitude = $_POST["inputLongitude"];
$inputTimeZone = $_POST["inputTimeZone"];
$inputAddress = $_POST["inputAddress"];
$inputContact = $_POST["inputContact"];
$inputGeneral = $_POST["inputGeneral"];
/*$inputJummah = $_POST["inputJummah"];*/

if(isset($_POST["inputLadies"]))
{$inputLadies = "Yes";}
else{$inputLadies = "No";}

if (isset($_POST["inputReg"]))
{$inputReg = "1";}
else{$inputReg = "0";}

if (isset($_POST["inputJummah"]))
{$inputJummah = "1";}
else{$inputJummah = "0";}

$sql = "UPDATE MASJID SET NAME='$inputName', TOWN='$inputTown', COUNTRY='$inputCountry', LATITUDE='$inputLatitude', LONGITUDE='$inputLongitude',TIMEZONE='$inputTimeZone', LADIES_FACILITY='$inputLadies', PHYSICAL_ADDRESS='$inputAddress', CONTACT='$inputContact', GENERAL_INFO='$inputGeneral', ALLOW_REG='$inputReg', JUMMAH='$inputJummah' WHERE ID='$MASJID_ID'";
$result = $conn->query($sql);

header("Location: index.php");
die();

?>