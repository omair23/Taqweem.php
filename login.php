<!DOCTYPE html>
<head>
    <title>Login - Taqweem</title>
    <?php include_once("menu.php");?>
</head>
<body>

<div class="container">

    <form class="form-signin" style="width:30%" method="post" action="validate_login.php">
        <h2 class="form-signin-heading">Sign In</h2>

        <?php if (isset($_GET['Error'])){ echo "The Email Address or Password you provided is incorrect";}?>

        <label for="inputEmail" class="sr-only">Email address</label>
        <input name="inputEmail" type="email" id="inputEmail" class="form-control" placeholder="Email address" required autofocus>
        <label for="inputPassword" class="sr-only">Password</label>
        <input name="inputPassword" type="password" id="inputPassword" class="form-control" placeholder="Password" required>
        <div class="checkbox">
            <label>
                <input type="checkbox" value="remember-me"> Remember me
            </label>
        </div>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Sign In</button>
    </form>

</div>

</body>
</html>