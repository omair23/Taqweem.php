<?php
header("Content-type: text/xml");
require("Connections/phpsqlajax_dbinfo.php");
include('SalaahTime.php');
// Start XML file, create parent node

$dom = new DOMDocument("1.0");
$node = $dom->createElement("musjids");
$parnode = $dom->appendChild($node);


require_once('Connections/SQL.php');
$sql = "SELECT * FROM MASJID WHERE 1";
$result = $conn->query($sql);
if ($result->num_rows > 0) {

// Iterate through the rows, adding XML nodes for each
  while ($row = @mysqli_fetch_assoc($result)){
    // ADD TO XML DOCUMENT NODE
    $node = $dom->createElement("marker");
    $newnode = $parnode->appendChild($node);
    $newnode->setAttribute("Name",$row['NAME']);
    $newnode->setAttribute("Town",$row['TOWN']);
    $newnode->setAttribute("Country",$row['COUNTRY']);
    $newnode->setAttribute("ID",$row['ID']);
    $newnode->setAttribute("PhysicalAddress", $row['PHYSICAL_ADDRESS']);
    $newnode->setAttribute("Latitude", $row['LATITUDE']);
    $newnode->setAttribute("Longitude", $row['LONGITUDE']);
    $newnode->setAttribute("LadiesFacility", $row['LADIES_FACILITY']);
	$newnode->setAttribute("Jummah", $row['JUMMAH']);
    $newnode->setAttribute("Distance", "");

    $ID = $row['ID'];
    $LATITUDE = $row["LATITUDE"];
    $LONGITUDE = $row["LONGITUDE"];
    $TIMEZONE = $row["TIMEZONE"];
    $HASL = $row["HASL"];
    $JURISTIC_METHOD = $row["JURISTIC_METHOD"];
    $ParamDayNumber = date("z") + 1;
    $Date =  "2015-12-31";
    $Date += (($ParamDayNumber - 1) * 24 * 60 * 60);
    $salaahTime = new SalaahTime();
    $salaahTime->SetData($ParamDayNumber, $Date, $LATITUDE, $LONGITUDE, $TIMEZONE,$HASL, $JURISTIC_METHOD);
    $Zawaal = $salaahTime->CalcZawaal();
    $Dhuhr =  $salaahTime->CalcDhuhr();
    $Sunrise = $salaahTime->CalcSunrise();
    $Sunset = $salaahTime->CalcSunset();
    $Maghrib = $salaahTime->CalcMaghrib();
    $newnode->setAttribute("Maghrib",$Maghrib);
    $sql2 = "SELECT * FROM MASJID_TIME WHERE MASJID_ID=" . $ID . " AND DAY_NUMBER=$ParamDayNumber";
    $result2 = $conn->query($sql2);
    if ($result2->num_rows > 0) {
      while ($row2 = @mysqli_fetch_assoc($result2)){
        $newnode->setAttribute("FajrAdhaan", date('H:i',strtotime($row2["FAJR_ADHAAN"])));
        $newnode->setAttribute("FajrSalaah", date('H:i',strtotime($row2["FAJR_SALAAH"])));
        $newnode->setAttribute("DhuhrAdhaan", date('H:i',strtotime($row2["DHUHR_ADHAAN"])));
        $newnode->setAttribute("DhuhrSalaah", date('H:i',strtotime($row2["DHUHR_SALAAH"])));
        $newnode->setAttribute("AsarAdhaan", date('H:i',strtotime($row2["ASAR_ADHAAN"])));
        $newnode->setAttribute("AsarSalaah",date('H:i',strtotime($row2["ASAR_SALAAH"])));
        $newnode->setAttribute("IshaAdhaan", date('H:i',strtotime($row2["ISHA_ADHAAN"])));
        $newnode->setAttribute("IshaSalaah",date('H:i',strtotime($row2["ISHA_SALAAH"])));
      }
    }
  }
}

echo $dom->saveXML();
$conn->close();
?>