<html>
<head>
    <title>Taqweem Masajid</title>

<?php include_once("menu.php");?>
    <style>

        .list {
            font-family:sans-serif;
        }
        td {
            padding:10px;
            border:solid 1px #eee;
        }

        input {
            border:solid 1px #ccc;
            border-radius: 5px;
            padding:7px 14px;
            margin-bottom:10px
        }
        input:focus {
            outline:none;
            border-color:#aaa;
        }
        .sort {
            padding:10px;
			margin:5px;
			width:225px;
			
            border-radius: 6px;
            border:none;
            display:inline-block;
            color:#fff;
            text-decoration: none;
            background-color: #5DCA88;
            
        }
        .sort:hover {
            text-decoration: none;
            background-color:grey;
        }
        .sort:focus {
            outline:none;
        }
        .sort:after {
            display:inline-block;
            width: 0;
            height: 0;
            border-left: 5px solid transparent;
            border-right: 5px solid transparent;
            border-bottom: 5px solid transparent;
            content:"";
            position: relative;
            top:-10px;
            right:-5px;
        }
        .sort.asc:after {
            width: 0;
            height: 0;
            border-left: 5px solid transparent;
            border-right: 5px solid transparent;
            border-top: 5px solid #fff;
            content:"";
            position: relative;
            top:4px;
            right:-5px;
        }
        .sort.desc:after {
            width: 0;
            height: 0;
            border-left: 5px solid transparent;
            border-right: 5px solid transparent;
            border-bottom: 5px solid #fff;
            content:"";
            position: relative;
            top:-4px;
            right:-5px;
        }

    </style>

</head>
<body>

<div class="container">
<div id="users">

	<div class = "search-filtering">
		<input class="search" placeholder="Search" />
		<button class="sort" data-sort="name">
			Sort by Name
		</button>
		<button class="sort" data-sort="town">
			Sort by Town
		</button>
		<button class="sort" data-sort="country">
			Sort by Country
		</button>
	</div>

<?php require_once('Connections/SQL.php');?>
<?php
$sql = "SELECT * FROM MASJID";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<table>
        <!-- IMPORTANT, class=\"list\" have to be at tbody -->
        <tbody class=\"list\">
        <tr><th>Name</th><th>Town</th><th>Country</th></tr>";
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo "<tr><td class=\"name\"><a href='viewmasjid.php?ID=" . $row["ID"]. "'>" .$row["NAME"].
            "</td><td class=\"town\">".$row["TOWN"].
            "</td><td class=\"country\">".$row["COUNTRY"]."</td></tr>";
    }
    echo "        </tbody>
    </table>";
} else {
    echo "0 results";
}
$conn->close();
?>

</div>
<script src="http://listjs.com/no-cdn/list.js"></script>
<script>
    var options = {
        valueNames: [ 'name', 'town', 'country' ]
    };

    var userList = new List('users', options);
</script>


    <br>
</div>
</body>
</html>