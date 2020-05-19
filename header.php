<?php
  session_start();
  include ('databaseRequests.php');
  
  updateImages();
?>
<head>
  <link rel="stylesheet" href="bootstrap/css/bootstrap.css"/>
  <title>QUIZZ</title>

  <!-- Custom styles for this template -->
  <link href="https://fonts.googleapis.com/css?family=Playfair+Display:700,900" rel="stylesheet">
  <!-- Custom styles for this template -->
  <link href="css/blog.css" rel="stylesheet">
</head>
<body>
  <div class="container">
    <header class="blog-header py-3">
      <div class="row flex-nowrap justify-content-between align-items-center">
        <div class="col-4 pt-1">

        </div>
        <div class="col-4 text-center">
          <a class="blog-header-logo text-dark" href="index.php">Quizzio</a>
        </div>
        <div class="col-4 d-flex justify-content-end align-items-center">
          <a class="text-muted" href="#" aria-label="Search">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="mx-3" role="img" viewBox="0 0 24 24" focusable="false"><title>Search</title><circle cx="10.5" cy="10.5" r="7.5"/><path d="M21 21l-5.2-5.2"/></svg>
          </a>
          <?php
            if(!isset($_SESSION['login'])){
              echo '<a class="btn btn-sm btn-outline-secondary" href="#" data-toggle="modal" data-target="#loginModal">S\'identifier</a>';
            }else{
              echo $_SESSION['login'];
              echo '<a href="deconnexion.php">  Deconnexion  </a>';
            }
          ?>
          </div>
          </div>
          </header>
          <div class="nav-scroller py-1 mb-2">
            <nav class="nav d-flex justify-content-between">
              <?php

              $bd = OpenCon();
              $result = $bd->query("SELECT * FROM categories");
              while (($row = $result->fetch_assoc())) {
                echo("<a class=\"p-2 text-muted\" href=\"#".$row["id"]."\">".$row["nom"]."</a>");
              }

              CloseCon($bd);

              ?>

            </nav>
          </div>