<?php
require_once('Connections/SQL.php');

$uid=$_GET['ID'];

if ( is_numeric( $uid ) )	{
    $uid= $uid;
}
// return False if we don't get a number
else
{
    echo "<script type='text/javascript'>
	window.location = 'index.php';
                                    </script>";
}

function isPast($time)
{
    return (strtotime($time) < time());
}

function isFuture($time)
{
    return (strtotime($time) > time());
}

function isToday($time) // midnight second
{
    return (strtotime($time) === strtotime('today'));
}

$sql = "SELECT * FROM MASJID WHERE ID=" . $_GET['ID'];
$result = $conn->query($sql);
if ($result->num_rows > 0) {

    while ($row = @mysqli_fetch_assoc($result)){
        $NAME = $row["NAME"];
		        $LATITUDE = $row["LATITUDE"];
        $LONGITUDE = $row["LONGITUDE"];
        $TIMEZONE = $row["TIMEZONE"];
        $HASL = $row["HASL"];
        $JURISTIC_METHOD = $row["JURISTIC_METHOD"];
    }
} else {
    echo "0 results";
}
?>
<!DOCTYPE html>
<head>
    <title>Taqweem</title>
    <?php include_once("menu.php");?>
</head>

<body>
<div class="container">
    <center>
        <h1>Salaah Times Calendar</h1>
        <h2>For The Year <?php echo date("Y"); ?></h2>
        <h3><?php echo $NAME; ?></h3></center>
		
		<center><a href="#" class="export">Export to CSV</a></center>

			<div id="dvData">
	 <table class="table table-striped table-sm" id="perp" style="width:90%">
        <thead class='thead-inverse'><tr><th colspan='12' ><center>January</center></th></tr></thead>
        <tr><th>Date</th><th>Fajr Adhaan</th><th>Fajr Salaah</th><th>Dhuhr Adhaan</th><th>Dhuhr Salaah</th>
            <th>Asr Adhaan</th><th>Asr Salaah</th><th>Maghrib</th><th>Isha Adhaan</th><th>Isha Adhaan</th></tr>

        <?php
        include('SalaahTime.php');
        $ParamDayNumber = 0;
        $RealDate;
        $PrevDate = "2015-12-31";
        for ($i = 1; $i <= 366; $i++){
            $sql = "SELECT * FROM MASJID_TIME WHERE MASJID_ID='$uid' AND DAY_NUMBER='$i'";
            $result = $conn->query($sql);

            $ParamDayNumber += 1;
            $Date =  "2015-12-31";
            $RealDate = date('Y-m-d', strtotime($Date. ' + ' . $ParamDayNumber .' days'));
            $Date += (($ParamDayNumber - 1) * 24 * 60 * 60);
			
			$salaahTime = new SalaahTime();
			$salaahTime->SetData($ParamDayNumber, $Date, $LATITUDE, $LONGITUDE, $TIMEZONE,$HASL, $JURISTIC_METHOD);
			$Zawaal = $salaahTime->CalcZawaal();
			$Sunrise = $salaahTime->CalcSunrise();
			$Sunset = $salaahTime->CalcSunset();
			$Maghrib = $salaahTime->CalcMaghrib();

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
				
                    $FAJR_ADHAAN = date('H:i',strtotime($row["FAJR_ADHAAN"]));
                    $FAJR_SALAAH = date('H:i',strtotime($row["FAJR_SALAAH"]));
                    $DHUHR_ADHAAN = date('H:i',strtotime($row["DHUHR_ADHAAN"]));
                    $DHUHR_SALAAH = date('H:i',strtotime($row["DHUHR_SALAAH"]));
                    $ASAR_ADHAAN = date('H:i',strtotime($row["ASAR_ADHAAN"]));
                    $ASAR_SALAAH = date('H:i',strtotime($row["ASAR_SALAAH"]));
                    $ISHA_ADHAAN = date('H:i',strtotime($row["ISHA_ADHAAN"]));
                    $ISHA_SALAAH = date('H:i',strtotime($row["ISHA_SALAAH"]));
                }


            if ((date("m",strtotime($RealDate)) - date("m",strtotime($PrevDate))) == 1){
                echo "<thead class='thead-inverse'><tr><th colspan='12' ><center>" . date("F",strtotime($RealDate)) ."</center></th></tr></thead>
 <tr><th>Date</th><th>Fajr Adhaan</th><th>Fajr Salaah</th><th>Dhuhr Adhaan</th><th>Dhuhr Salaah</th>
            <th>Asr Adhaan</th><th>Asr Salaah</th><th>Maghrib</th><th>Isha Adhaan</th><th>Isha Adhaan</th></tr>";

            }
            $PrevDate = $RealDate;

            if (isToday($RealDate)){
                echo "<tr style='background-color: yellow;'>";
            }else {echo "<tr>";}
            echo "<td>". date("j",strtotime($RealDate)) . "</td>" .
                "<td>". $FAJR_ADHAAN . "</td>" .
                "<td>". $FAJR_SALAAH . "</td>" .
                "<td>". $DHUHR_ADHAAN . "</td>" .
                "<td>". $DHUHR_SALAAH . "</td>" .
                "<td>". $ASAR_ADHAAN . "</td>" .
                "<td>". $ASAR_SALAAH . "</td>" .
                "<td>". $Maghrib  . "</td>" .//$Maghrib . "</td>" .
                "<td>". $ISHA_ADHAAN . "</td>" .
                "<td>". $ISHA_SALAAH . "</td>" .

                "</tr>";
            }
        }
        ?>
    </table>
</div>
</div>

	<script>
	$(document).ready(function () {
    function exportTableToCSV($table, filename) {
        var $rows = $table.find('tr:has(td)'),
            // Temporary delimiter characters unlikely to be typed by keyboard
            // This is to avoid accidentally splitting the actual contents
            tmpColDelim = String.fromCharCode(11), // vertical tab character
            tmpRowDelim = String.fromCharCode(0), // null character
            // actual delimiter characters for CSV format
            colDelim = '","',
            rowDelim = '"\r\n"',
            // Grab text from table into CSV formatted string
            csv = '"' + $rows.map(function (i, row) {
                var $row = $(row),
                    $cols = $row.find('td');

                return $cols.map(function (j, col) {
                    var $col = $(col),
                        text = $col.text();

                    return text.replace(/"/g, '""'); // escape double quotes

                }).get().join(tmpColDelim);

            }).get().join(tmpRowDelim)
                .split(tmpRowDelim).join(rowDelim)
                .split(tmpColDelim).join(colDelim) + '"',

            // Data URI
            csvData = 'data:application/csv;charset=utf-8,' + encodeURIComponent(csv);

        $(this)
            .attr({
            'download': filename,
                'href': csvData,
                'target': '_blank'
        });
    }

    // This must be a hyperlink
    $(".export").on('click', function (event) {
        // CSV
        exportTableToCSV.apply(this, [$('#dvData>table'), 'export.csv']);
        
        // IF CSV, don't do event.preventDefault() or return false
        // We actually need this to be a typical hyperlink
    });
});
	</script>

</body>
</html>