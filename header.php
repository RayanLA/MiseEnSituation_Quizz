<?php
  if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
  include ('databaseRequests.php');
  
  updateImages();
?>
<head>
  <link rel="stylesheet" href="bootstrap/css/bootstrap.css"/>
  <link rel="icon" href="img/icone.png" />
  <title>QUIZZIO</title>


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
          <a href="index.php"><img class="logo" src='img/logo.png'></img></a>
        <?php
            if(isset($_SESSION['login'])){
            echo '<a class="p-2 text-muted" href="creationQuizz.php">Créer un quizz</a>';
            }
        ?>
        </div>    
        <div class="col-4 text-center">
          <a class="blog-header-logo text-dark" href="index.php">Quizzio</a>
        </div>
        <div class="col-4 d-flex justify-content-end align-items-center">
          <!--<a class="text-muted" href="#" aria-label="Search">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="mx-3" role="img" viewBox="0 0 24 24" focusable="false"><title>Search</title><circle cx="10.5" cy="10.5" r="7.5"/><path d="M21 21l-5.2-5.2"/></svg>
          </a>-->

              <?php
                if(isset($_SESSION['login'])){
                  echo '<div class="dropdown">';
                    echo '<button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                      echo '<svg class="bi bi-person-fill" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                              <path fill-rule="evenodd" d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/>
                            </svg>';
                      echo "  ".$_SESSION['login'];
                    echo '</button>';
                    
                    echo '<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">';
                      echo '<a class="dropdown-item" href="profil.php">Profil</a>';
                      echo '<a class="dropdown-item" href="deconnexion.php">  Deconnexion  <svg class="bi bi-box-arrow-in-left" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                      <path fill-rule="evenodd" d="M7.854 11.354a.5.5 0 000-.708L5.207 8l2.647-2.646a.5.5 0 10-.708-.708l-3 3a.5.5 0 000 .708l3 3a.5.5 0 00.708 0z" clip-rule="evenodd"/>
                      <path fill-rule="evenodd" d="M15 8a.5.5 0 00-.5-.5h-9a.5.5 0 000 1h9A.5.5 0 0015 8z" clip-rule="evenodd"/>
                      <path fill-rule="evenodd" d="M2.5 14.5A1.5 1.5 0 011 13V3a1.5 1.5 0 011.5-1.5h8A1.5 1.5 0 0112 3v1.5a.5.5 0 01-1 0V3a.5.5 0 00-.5-.5h-8A.5.5 0 002 3v10a.5.5 0 00.5.5h8a.5.5 0 00.5-.5v-1.5a.5.5 0 011 0V13a1.5 1.5 0 01-1.5 1.5h-8z" clip-rule="evenodd"/>
                      </svg></a>';
                    echo '</div>';
                  echo '</div>';
                }elseif(!isset($_SESSION['login']) && !( (isset($_SESSION['isGuest']) && $_SESSION['isGuest'] )
                   || ( isset($_POST['isGuest']) && $_POST['isGuest']) )) {
                  echo '<a class="btn btn-sm btn-outline-secondary" href="#" data-toggle="modal" data-target="#loginModal" id="modalAuth">S\'identifier</a>';
                }

                if( (isset($_SESSION['isGuest']) && $_SESSION['isGuest'] )
                   || ( isset($_POST['isGuest']) && $_POST['isGuest']) ){
                  echo '<a class="btn btn-sm btn-outline-secondary" href="#" data-toggle="modal" data-target="#loginModal" id="">Invité</a>';
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
                echo("<a class=\"p-2 text-muted\" href=\"QuizzParCategorie.php?idCategorie=".$row["id"]."\">".$row["nom"]."</a>");
              }
              CloseCon($bd);
              ?>
            </nav>
          </div>