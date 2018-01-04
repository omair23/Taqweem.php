<!DOCTYPE html>
<head>
    <title>Taqweem</title>
    <?php include_once("menu.php");
    include("sessiontest2.php");?>
</head>

<body>
<div class="container">

    <?php
    require_once('../Connections/SQL.php');
    if ( isset($_POST["submit"]) ) {

        if ( isset($_FILES["file"])) {

            //if there was an error uploading the file
            if ($_FILES["file"]["error"] > 0) {
                echo "Return Code: " . $_FILES["file"]["error"] . "<br />";

            }
            else {

                //if file already exists
                if (file_exists("upload/" . $_FILES["file"]["name"])) {
                    echo $_FILES["file"]["name"] . " already exists. ";
                }
                else {
                    //Store file in directory "upload" with the name of "uploaded_file.txt"
                    $storagename = "uploaded_file.txt";
                    move_uploaded_file($_FILES["file"]["tmp_name"], $storagename);
                    echo "Stored in: " . "upload/" . $_FILES["file"]["name"] . "<br />";
                }
            }
        } else {
            echo "No file selected <br />";
        }


        if ( $file = fopen( $storagename ,"r") ) {
            //echo "File opened.<br />";
            $firstline = fgets($file, 4096);
            //Gets the number of fields, in CSV-files the names of the fields are mostly given in the first line
            $num = strlen($firstline) - strlen(str_replace(";", "", $firstline));
            //save the different fields of the firstline in an array called fields
            $fields = array();
            $fields = explode(";", $firstline, ($num + 1));
            $line = array();
            $i = 0;
            //CSV: one line is one record and the cells/fields are seperated by ";"
            //so $dsatz is an two dimensional array saving the records like this: $dsatz[number of record][number of cell]
            while ($line[$i] = fgets($file, 4096)) {
                $dsatz[$i] = array();
                $dsatz[$i] = explode(";", $line[$i], ($num + 1));
                $i++;
            }


            $DayNumber = 0;
            $isCalendar = false;
            //$MasjidID = $_SESSION['MasjidID'];
            $MasjidID = 0;

            foreach ($dsatz as $key => $number) {
                //new table row for every record
                echo "<tr>";
                foreach ($number as $k => $content) {
                    $TimeZone = "2";
                    $Country = "South Africa";
                    //new table cell for every field of the record
                    $rowArray = explode(',', $number[0]);
                    if ($rowArray[1] == '"MusjidName"' or $rowArray[1] == 'MusjidName') {
                        $Name = ReplaceOutput($rowArray[2]);
                    } elseif ($rowArray[1] == '"Location"' or $rowArray[1] == 'Location') {
                        $Town = ReplaceOutput($rowArray[2]);
                    } elseif ($rowArray[1] == '"Country"' or $rowArray[1] == 'Country') {
                        $Country = "South Africa";
                    } elseif ($rowArray[1] == '"Latitude"' or $rowArray[1] == 'Latitude') {
                        $Latitude = ReplaceOutput($rowArray[2]);
                    } elseif ($rowArray[1] == '"Longitude"' or $rowArray[1] == 'Longitude') {
                        $Longitude = ReplaceOutput($rowArray[2]);
                    } elseif ($rowArray[1] == '"Timezone"' or $rowArray[1] == 'Timezone') {
                        $TimeZone = "2";
                    } elseif ($rowArray[1] == '"JumuahSalaah"' or $rowArray[1] == 'JumuahSalaah') {
                        $Jummah = ReplaceOutput($rowArray[2]);
                        if ($Jummah == "Active") {
                            $Jummah = "1";
                        } else {
                            $Jummah = "0";
                        }
                    }
                }
            }
            echo $Name;
            $Query = "INSERT INTO MASJID (NAME,TOWN,COUNTRY,LATITUDE,LONGITUDE,TIMEZONE,JUMMAH) VALUES('$Name', '$Town', '$Country', '$Latitude', '$Longitude', '$TimeZone', '$Jummah')";
            $result = $conn->query($Query);

            /*$Query = "SELECT * FROM MASJID WHERE LATITUDE=$Latitude";
            $result = $conn->query($Query);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $MasjidID = $row["ID"];
                }
            }*/
            $MasjidID = mysqli_insert_id($conn);

                $Query = "DELETE FROM MASJID_TIME WHERE MASJID_ID=" . $MasjidID;
                $result = $conn->query($Query);

                foreach ($dsatz as $key => $number) {
                    //new table row for every record
                    echo "<tr>";
                    foreach ($number as $k => $content) {
                        //new table cell for every field of the record
                        $rowArray = explode(',', $number[0]);

                        if ($isCalendar == true) {
                            $rowArray[10] = ReplaceOutput($rowArray[10]);
                            $rowArray[11] = ReplaceOutput($rowArray[11]);
                            $rowArray[12] = ReplaceOutput($rowArray[12]);
                            $rowArray[13] = ReplaceOutput($rowArray[13]);
                            $rowArray[14] = ReplaceOutput($rowArray[14]);
                            $rowArray[15] = ReplaceOutput($rowArray[15]);
                            $rowArray[18] = ReplaceOutput($rowArray[18]);
                            $rowArray[19] = ReplaceOutput($rowArray[19]);

                            $DayNumber += 1;
                            if ($DayNumber <= 366) {
                                $Query = "INSERT INTO MASJID_TIME VALUES('$MasjidID', '$DayNumber', '$rowArray[10]', '$rowArray[11]','$rowArray[12]','$rowArray[13]','$rowArray[14]','$rowArray[15]','$rowArray[18]','$rowArray[19]')";
                                $result = $conn->query($Query);
                            }
                        }

                        if ($rowArray[0] == '"#####"' or $rowArray[0] == '#####') {
                            $isCalendar = true;
                        }
                    }
                }
                $conn->close();
                //echo "</table>";
                echo "<h1>Upload Successful</h1>";
            }
    }

    function ReplaceOutput($String){
        $Output = str_replace('"', "", $String);
        return $Output;
    }
    ?>

    <table width="600">
        <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" enctype="multipart/form-data">

            <tr>
                <td width="20%">Select file</td>
                <td width="80%"><input type="file" name="file" id="file" /></td>
            </tr>

            <tr>
                <td>Submit</td>
                <td><input type="submit" name="submit" /></td>
            </tr>

        </form>
    </table>

</div>
</body>
</html>
