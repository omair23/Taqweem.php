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
$conn->close();
?>
<!DOCTYPE html>
<head>
    <title>Taqweem</title>
	    <?php include_once("menu.php");?>
</head>

<body>
<div class="container">
<center>
    <h1>Perpetual Times Calendar</h1>
    <h2>For The Year <?php echo date("Y"); ?></h2>
    <h3><?php echo $NAME; ?></h3></center>
	
	<center><a href="#" class="export">Export to CSV</a></center>
	
	<div id="dvData">
    <table class="table table-striped table-sm" id="perp" style="width:90%">
        <thead class='thead-inverse'><tr><th colspan='12' ><center>January</center></th></tr></thead>
        <center><tr><th>Date</th><th>Sehri-Ends</th><th>Fajr</th><th>Sunrise</th><th>Ishraaq</th><th>Zawaal</th><th>Dhuhr</th><th>Asar-Shaafi</th><th>Asar-Hanafi</th><th>Sunset</th><th>Maghrib</th><th>Isha</th></tr><center>

<?php
    include('SalaahTime.php');
    $ParamDayNumber = 0;
    $RealDate;
    $PrevDate = "2015-12-31";
    for ($i = 1; $i <= 366; $i++){
        $ParamDayNumber += 1;
        $Date =  "2015-12-31";
        $RealDate = date('Y-m-d', strtotime($Date. ' + ' . $ParamDayNumber .' days'));
        $Date += (($ParamDayNumber - 1) * 24 * 60 * 60);
        $salaahTime = new SalaahTime();
        $salaahTime->SetData($ParamDayNumber, $Date, $LATITUDE, $LONGITUDE, $TIMEZONE,$HASL, $JURISTIC_METHOD);
        $Zawaal = $salaahTime->CalcZawaal();
        $Dhuhr =  $salaahTime->CalcDhuhr();
        $Sunrise = $salaahTime->CalcSunrise();
        $Sunset = $salaahTime->CalcSunset();
        $Ishraaq = $salaahTime->CalcIshraaq();
        $Maghrib = $salaahTime->CalcMaghrib();
        $SehriEnds = $salaahTime->CalcSehriEnds();
        $Asar1 = $salaahTime->CalcAsar1();
        $Asar2 = $salaahTime->CalcAsar2();
        $Fajr = $salaahTime->CalcFajr();
        $Isha = $salaahTime->CalcIsha();

        if ((date("m",strtotime($RealDate)) - date("m",strtotime($PrevDate))) == 1){
            echo "<thead class='thead-inverse'><tr><th colspan='12' ><center>" . date("F",strtotime($RealDate)) ."</center></th></tr></thead>
<tr><th>Date</th><th>Sehri-Ends</th><th>Fajr</th><th>Sunrise</th><th>Ishraaq</th><th>Zawaal</th><th>Dhuhr</th><th>Asar-Shaafi</th><th>Asar-Hanafi</th><th>Sunset</th><th>Maghrib</th><th>Isha</th></tr>";
        }
        $PrevDate = $RealDate;

        if (isToday($RealDate)){
            echo "<tr style='background-color: yellow;'>";
        }else {echo "<tr>";}

        echo "<td>". date("j",strtotime($RealDate)) . "</td>" .
            "<td>". $SehriEnds . "</td>" .
            "<td>". $Fajr . "</td>" .
            "<td>". $Sunrise . "</td>" .
            "<td>". $Ishraaq . "</td>" .
            "<td>". $Zawaal . "</td>" .
            "<td>". $Dhuhr . "</td>" .
            "<td>". $Asar1 . "</td>" .
            "<td>". $Asar2 . "</td>" .
            "<td>". $Sunset . "</td>" .
            "<td>". $Maghrib . "</td>" .
            "<td>". $Isha . "</td>" .
            "</tr>";

    }
?>
    </table>

    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>

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