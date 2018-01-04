<html>
<head>
    <title>Taqweem</title>
    <?php include_once("menu.php");?>
</head>
<body>
<div class="container">

    <?php

    require_once('Connections/SQL.php');
    $inputEmail = $_POST["inputEmail"];
    $inputPassword = $_POST["inputPassword"];
    $inputID = $_POST["inputID"];
    $sql = "INSERT INTO USER (EMAIL_ADDRESS, PASSWORD, MASJID_ID) VALUES('$inputEmail','$inputPassword','$inputID')";
    $result = $conn->query($sql);

    ?>

<h3>Registered Successfully</h3>

</div>
</body>
</html>
