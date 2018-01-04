<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Taqweem Admin Panel</title>

    <!-- Bootstrap core CSS -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="signin.css" rel="stylesheet">
  </head>

  <body>

    <div class="container">		
      <form class="form-signin" style="width:30%" method="post" action="validate_login.php">
	  
		<?php if (isset($_GET['Error'])){ echo "The Email Address or Password you provided is incorrect";}?>
			  
        <h1 class="form-signin-heading">Taqweem Admin</h1>
		<h3 class="form-signin-heading">Please sign in</h3>
		
        <label for="inputEmail" class="sr-only">Email address</label>
        <input name="inputEmail" type="email" id="inputEmail" class="form-control" placeholder="Email address" required autofocus>
		
        <label for="inputPassword" class="sr-only">Password</label>
        <input name="inputPassword" type="password" id="inputPassword" class="form-control" placeholder="Password" required>
		
        <label for="inputST" class="sr-only">Security Token</label>
        <input name="inputST" type="text" id="inputST" class="form-control" placeholder="Security Token" required>		

		<button class="btn btn-lg btn-primary btn-block" type="submit">Submit</button>
      </form>

    </div> <!-- /container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="../js/ie10-viewport-bug-workaround.js"></script>
  </body>
</html>
