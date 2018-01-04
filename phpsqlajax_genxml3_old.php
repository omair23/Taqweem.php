<?php  

require("Connections/phpsqlajax_dbinfo.php");

// Start XML file, create parent node

$dom = new DOMDocument("1.0");
$node = $dom->createElement("musjids");
$parnode = $dom->appendChild($node);


require_once('Connections/SQL.php');
$sql = "SELECT * FROM MASJID WHERE 1";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
  header("Content-type: text/xml");

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
    $newnode->setAttribute("Distance", "");
  }

  echo $dom->saveXML();
} else {
  echo "0 results";
}
$conn->close();

?>