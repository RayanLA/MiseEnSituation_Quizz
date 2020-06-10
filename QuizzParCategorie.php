<html>
    <head>
        
        <link rel="stylesheet" href="css/blog.css"/>
        <link rel="stylesheet" href="bootstrap/css/bootstrap.css"/>
        <title>QUIZZ</title>
    <!-- Custom styles for this template -->
    <link href="https://fonts.googleapis.com/css?family=Playfair+Display:700,900" rel="stylesheet">
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
         if ($row = $result->fetch_assoc()) {
          $row['id_categorie'] = $_POST['idCategorie'];
            $row['cnom'] = $_POST['nomCategorie'];

            generateCardQuizz($row);
          while ($row = $result->fetch_assoc()) {

            $row['id_categorie'] = $_POST['idCategorie'];
            $row['cnom'] = $_POST['nomCategorie'];

            generateCardQuizz($row);

          }

        } else {

          echo'
          <div class="col-md-12 blog-main">

          <h3 class="text-center">Il n\'y a pas encore de Quizz dans cette catégorie...
          </h3>
          <h2 class="pb-4 mb-4 text-center">Voulez-vous être le premier à en créer ?</h2>';
        

    echo '<script type="text/javascript">
      function validateForm(e){e.closest("form").submit();}
      function openAuthModal(){ $(function(){$("modalAuth").click();}); }
    </script>';
      if(isset($_SESSION) && isset($_SESSION['login'])){
         echo '
         <div class="d-flex justify-content-center">
        <button class="btn btn-lg btn-primary" onclick="creationQuizz.php">Créer un Quizz</button>
        </div>
        </br>
        </br>
        </br>
        <div>
      ';
  }else{
    echo ' 
        <div class="row p-4 justify-content-center align-self-center">
          <div class="col d-flex justify-content-end tailleMaxBoutonConnexion">
            <button class="btn btn-lg btn-primary" onclick="openAuthModal()">Se connecter pour créer un Quizz</button>
          </div>
          <div class="col d-flex justify-content-start">
          </br>
            <a class="p-2 text-center positionJeuAnonyme" href="inscription.php">Ou s\'inscrire</a>
          </div>
        </div>
        </br>
        </br>
        <div>';
  }
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
