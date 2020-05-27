<html>
    <head>
        
        <link rel="stylesheet" href="css/blog.css"/>
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
    <?php
      include 'header.php';

      if(!isset($_POST['idCategorie'])) JS_Redirect("index.php");
    ?>
  </header>
  
  <div class="row m-2 ">
    <?php
      echo '<button type="button" class="btn btn-primary shareCategorie" onclick="openModalShare(\'C\', '.$_POST['idCategorie'].', null, \''.$_POST['nomCategorie'].'\')">Partager cette catégorie</button> ';
    ?>
  </div>

  <div class="row mb-2">
    <?php 
    $conn = OpenCon();
    try {

      $requestSQL = "
              SELECT nom as qnom, date_creation as crea, 
                     id as id_quizz, description as description, 
                     url as url
              FROM quizz 
              WHERE id_categorie=".$_POST['idCategorie'];

      if($result = $conn->query($requestSQL)){
         while ($row = $result->fetch_assoc()) {

          $row['id_categorie'] = $_POST['idCategorie'];
          $row['cnom'] = $_POST['nomCategorie'];

          generateCardQuizz($row);

        }
        $result->free();
      }
    } catch (Exception $e) {
      echo $e;
    }
    CloseCon($conn);
    ?>
   


  </div><!-- /.row -->

</main><!-- /.container -->

<?php
      include 'footer.php'
?>
