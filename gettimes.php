<?php
require("Connections/phpsqlajax_dbinfo.php");
// Start XML file, create parent node
$dom = new DOMDocument("1.0");
$node = $dom->createElement("times");
$parnode = $dom->appendChild($node);

require_once('Connections/SQL.php');
$sql = "SELECT * FROM MASJID WHERE ID=" . $_GET['ID'];
$result = $conn->query($sql);
$row = @mysqli_fetch_assoc($result);
$LATITUDE = $row["LATITUDE"];
$LONGITUDE = $row["LONGITUDE"];
$TIMEZONE = $row["TIMEZONE"];
$HASL = $row["HASL"];
$JURISTIC_METHOD = $row["JURISTIC_METHOD"];
$ParamDayNumber = date("z") + 1;
include('SalaahTime.php');
$Date =  "2015-12-31";
$Date += (($ParamDayNumber - 1) * 24 * 60 * 60);
$salaahTime = new SalaahTime();
$salaahTime->SetData($ParamDayNumber, $Date, $LATITUDE, $LONGITUDE, $TIMEZONE,$HASL, $JURISTIC_METHOD);
$Zawaal = $salaahTime->CalcZawaal();
$Dhuhr =  $salaahTime->CalcDhuhr();
$Sunrise = $salaahTime->CalcSunrise();
$Sunset = $salaahTime->CalcSunset();
$Maghrib = $salaahTime->CalcMaghrib();

$sql = "SELECT * FROM MASJID_TIME WHERE MASJID_ID=" . $_GET['ID']. " AND DAY_NUMBER=$ParamDayNumber";
$result = $conn->query($sql);
header("Content-type: text/xml");
// ADD TO XML DOCUMENT NODE
$node = $dom->createElement("time");
$newnode = $parnode->appendChild($node);
$newnode->setAttribute("Masjid_ID",$_GET['ID']);
$newnode->setAttribute("Day",$ParamDayNumber);
$newnode->setAttribute("Maghrib",$Maghrib);

if ($result->num_rows > 0) {
    while ($row = @mysqli_fetch_assoc($result)){
        $newnode->setAttribute("FajrAdhaan",$row["FAJR_ADHAAN"]);
        $newnode->setAttribute("FajrSalaah",$row["FAJR_SALAAH"]);
        $newnode->setAttribute("DhuhrAdhaan",$row["DHUHR_ADHAAN"]);
        $newnode->setAttribute("DhuhrSalaah",$row["DHUHR_SALAAH"]);
        $newnode->setAttribute("AsarAdhaan",$row["ASAR_ADHAAN"]);
        $newnode->setAttribute("AsarSalaah",$row["ASAR_SALAAH"]);
        $newnode->setAttribute("IshaAdhaan",$row["ISHA_ADHAAN"]);
        $newnode->setAttribute("IshaSalaah",$row["ISHA_SALAAH"]);
    }
}
echo $dom->saveXML();

$conn->close();

?>