<?php 
  $logged_in = false;

  if(isset($_SESSION["user"]) && $_SESSION["user"] !== "")
    $logged_in = true;
  ?>
<!DOCTYPE html>
<html lang="en">

  <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Pr(emed)onostiek</title>

    <!-- Bootstrap core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <style>
      body {
        padding-top: 54px;
      }
      @media (min-width: 992px) {
        body {
          padding-top: 56px;
        }
      }

    </style>

  </head>

   <?php if (!$logged_in): ?>
    <?php endif; ?>
  <body>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
      <div class="container">
        <a class="navbar-brand" href="#">Pr(emed)onostiek</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
          <ul class="navbar-nav ml-auto">
            <li class="nav-item active">
              <a class="nav-link" href="/">Home
                <span class="sr-only">(current)</span>
              </a>
            </li>
            <li class="nav-item active">
                <a class="nav-link" href="reglement.php">Reglement</a>
              </li>
            <?php if ($logged_in): ?>
              <li class="nav-item active">
                <a class="nav-link" href="scoreboard.php">Scorebord</a>
              </li>
             <?php if (isset($_SESSION["data"]["admin"]) && $_SESSION["data"]["admin"] == true): ?>
               <li class="nav-item active">
                <a class="nav-link" href="admin.php">Admin</a>
              </li>
             <?php endif;?>
              <li class="nav-item active">
                <a class="nav-link" href="logout.php">Uitloggen</a>
              </li>
            <?php endif; ?>
          </ul>
        </div>
      </div>
    </nav>

     
