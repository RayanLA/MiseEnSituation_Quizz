
<html>

    <?php

  include 'header.php';

  if (isset($_POST['idQuizz']) && isset($_POST['nomQuizz']) && isset($_POST['numquestion']) && isset($_POST['nomcat'])&&isset($_SESSION['data'])){

    $idQuizz = $_POST['idQuizz'];
    //$nomQuizz = $_POST['nomQuizz'];
    $numquestion = $_POST['numquestion'];
    //$nomcat = $_POST['nomcat'];
    $repUser = $_SESSION['data'];
    unset($_SESSION['data']);

    if (count($repUser)!=($numquestion -1)){
      echo("Page introuvable.");
    } else {

    ?>
  
  <div class="col-md-12 blog-main">
      <h3 class="pb-4 mb-4 border-bottom">
      <?php
        echo($_POST['nomQuizz']);
      ?>
      </h3>
  </div>  

  <div>
  <?php
    $bd = OpenCon();
    $result = $bd->query("SELECT question, id FROM questions WHERE questions.id_quizz = $idQuizz ORDER BY questions.id");

    $count = 0;
    $score = 0;

    while (($row = $result->fetch_assoc())) {
      $array = $repUser[$count];
      $count++;
      if ($count == $numquestion){
        echo("Une erreur s'est produite...");
        break;
      }
      echo("<h3 class=\"mb-0\"> Question ".$count." : ".$row["question"]."</h3>");

      $result2 = $bd->query("SELECT reponse, correct FROM reponses WHERE  reponses.id_question = ".$row['id']." ORDER BY reponses.id");

      $answcount = 0;
      $questionok = 1;

      while (($row2 = $result2->fetch_assoc())) {
        $answcount++;
        $coche = 0;
        if(!empty($array)) {    
          foreach($array as $value){
            if ($value == $answcount){
              $coche = 1;
            }
          }
        }
        if ($coche){
          echo("coché   ");
          if ($row2["correct"]){
            echo("correct  ");
          } else {
            $questionok = 0;
          }
        } else {
          if ($row2["correct"]){
            echo("correct  ");
            $questionok = 0;
          } else {
            
          }
        }

        echo("<div class=\"mb-1 text-muted\">".$row2["reponse"]."</div>");
      
      }
      $result2->free();

      if ($questionok){
        echo("Vous avez bien répondu à cette question.");
        $score++;
      } else {
        echo("Vous n'avez pas bien répondu à cette question.");
      }

    }
    $result->free();

    if ($count !== count($repUser)){
      echo("probleme nombre de réponse différent");
    } else {
      echo("Vous avez obtenu un score de ".$score."/".$count.".");
      //TODO : ajouter ou modifier ligne dans table score SI utilisateur connecté sur son profil
    }

    CloseCon($bd);

  ?>
    
  </div>



<?php
    }
  } else {
    echo("Page introuvable");
  }

      include 'footer.php'
?>