<?php
define('ROOT', dirname(__FILE__));
require_once ROOT . '/core/config/config.php';
require_once ROOT . '/core/db/MySQLConnection.class.php';
@ob_start();
session_start();
$errors = array();
if (count($_POST))
{
    extract($_POST);
    $password = md5($loginPassword);
    $query = "SELECT * FROM users WHERE username='$loginUsername' AND password='$password'";
    $sqlConn = MySQLConnection::getInstance('mysql');
    try
    {
      $resultData = $sqlConn->executeQuery($query);
    }
    catch (Exception $e)
    {
      array_push($errors, "Wrong username/password combination");
    }

    if (count($resultData) == 1) {
      $_SESSION['username'] = $loginUsername;
      $_SESSION['created'] = time();
      $_SESSION['success'] = "You are now logged in";
      header('location: index.php');
    }
    else {
      array_push($errors, "Wrong username/password combination");
    }
}

if (isset($_GET['session_out']))
{
  array_push($errors, "Your session has timed out. Please sign in again.");
}

 ?>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>DC Inventory Management</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="robots" content="all,follow">
    <!-- Bootstrap CSS-->
    <link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome CSS-->
    <link rel="stylesheet" href="vendor/font-awesome/css/font-awesome.min.css">
    <!-- Fontastic Custom icon font-->
    <link rel="stylesheet" href="css/fontastic.css">
    <!-- Google fonts - Poppins -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,700">
    <!-- theme stylesheet-->
    <link rel="stylesheet" href="css/style.default.css" id="theme-stylesheet">
    <!-- Custom stylesheet - for your changes-->
    <link rel="stylesheet" href="css/custom.css">
    <!-- Favicon-->
    <link rel="shortcut icon" href="img/logo.png">
    <!-- Tweaks for older IEs--><!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script><![endif]-->
  </head>
  <body>
    <div class="page login-page">
      <div class="container d-flex align-items-center">
        <div class="form-holder has-shadow">
          <div class="row">
            <!-- Logo & Information Panel-->
            <div class="col-lg-6">
              <div class="info d-flex align-items-center">
                <div class="content">
                  <div class="logo">
                    <h1>DC Inventory Management</h1>
                  </div>
                </div>
              </div>
            </div>
            <!-- Form Panel    -->
            <div class="col-lg-6 bg-white">
              <div class="form d-flex align-items-center">
                <div class="content">
                  <form method="post" class="form-validate" action="login.php">
                    <div class="form-group">
                      <input id="login-username" type="text" name="loginUsername" required data-msg="Please enter your username" class="input-material">
                      <label for="login-username" class="label-material">User Name</label>
                    </div>
                    <div class="form-group">
                      <input id="login-password" type="password" name="loginPassword" required data-msg="Please enter your password" class="input-material">
                      <label for="login-password" class="label-material">Password</label>
                    </div>
                    <!-- <a id="login" href="login.php" class="btn btn-primary">Login</a> -->
                    <button type="Login" class="btn btn-primary" name="login">Login</button>
                    <?php  if (count($errors) > 0) : ?>
                    	<div class="error">
                    		<?php foreach ($errors as $error) : ?>
                    			<p><?php echo $error ?></p>
                    		<?php endforeach ?>
                    	</div>
                    <?php  endif ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="copyrights text-center">
        <p>Design by <a href="www.tatacommunications.com" class="external">TATA Communications</a>
        </p>
      </div>
    </div>
    <!-- JavaScript files-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/popper.js/umd/popper.min.js"> </script>
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="vendor/jquery.cookie/jquery.cookie.js"> </script>
    <script src="vendor/chart.js/Chart.min.js"></script>
    <script src="vendor/jquery-validation/jquery.validate.min.js"></script>
    <!-- Main File-->
    <script src="js/front.js"></script>
  </body>
</html>
