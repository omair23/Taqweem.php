<!DOCTYPE html>
<head>
    <title>Register - Taqweem</title>
    <?php include_once("menu.php");?>
</head>
<body>

<div class="container">

    <?php
    require_once('Connections/SQL.php');
    $ID = $_POST["inputID"];
    $sql = "SELECT * FROM MASJID WHERE ID=$ID";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $NAME = $row["NAME"];
            $ALLOW_REG = $row["ALLOW_REG"];
        }
    }
    $conn->close();

    if ($ALLOW_REG == "0"){
        header("Location: index.php");
        die();
    }
?>

    <form class="form-signin" style="width:30%" method="post" action="register_user.php">
        <h2 class="form-signin-heading">Register - <?php echo $NAME; ?></h2>

        <input name="inputID" type="hidden" id="inputID" value="<?php echo $_POST["inputID"]; ?>">
        <label for="inputEmail" class="sr-only">Email address</label>
        <input name="inputEmail" type="email" id="inputEmail" class="form-control" placeholder="Email address" required autofocus>
        <label for="inputPassword" class="sr-only">Password</label>
        <input name="inputPassword" type="password" id="inputPassword" class="form-control" placeholder="Password" required>
        <div class="checkbox">
            <label>
                <input type="checkbox" value="remember-me"> Remember me
            </label>
        </div>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Register</button>
    </form>
<br><br><br><br>
</div>

</body>
</html>