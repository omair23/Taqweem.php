<?php
require_once('Connections/SQL.php');
$inputName = $_POST["inputName"];
$inputTown = $_POST["inputTown"];
$inputCountry = $_POST["inputCountry"];
$inputLatitude = $_POST["inputLatitude"];
$inputLongitude = $_POST["inputLongitude"];
$inputTimeZone = $_POST["inputTimeZone"];
$inputAddress = $_POST["inputAddress"];
$inputContact = $_POST["inputContact"];
$inputGeneral = $_POST["inputGeneral"];

if(isset($_POST["inputLadies"]))
{$inputLadies = "Yes";}
else{$inputLadies = "No";}

$sql = "INSERT INTO MASJID (NAME,TOWN,COUNTRY,LATITUDE,LONGITUDE,TIMEZONE,PHYSICAL_ADDRESS,CONTACT,GENERAL_INFO, LADIES_FACILITY)
VALUES ('$inputName','$inputTown','$inputCountry','$inputLatitude','$inputLongitude','$inputTimeZone','$inputAddress','$inputContact','$inputGeneral','$inputLadies')";
$result = $conn->query($sql);

header("Location: masajid.php");
die();

?>