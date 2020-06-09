
<html>

    <?php

  include 'header.php';
  

  if (isset($_POST['idQuizz']) && isset($_POST['nomQuizz']) && isset($_POST['numquestion']) && isset($_POST['nomcat'])&&isset($_SESSION['data'])){

    $idQuizz = $_POST['idQuizz'];
    $nomQuizz = $_POST['nomQuizz'];
    $numquestion = $_POST['numquestion'];
    $nomcat = $_POST['nomcat'];
    $repUser = $_SESSION['data'];
    unset($_SESSION['data']);

    if (count($repUser)!=($numquestion -1)){
      echo("Page introuvable.");
    } else {

    ?>
  
  <div class="col-md-12 blog-main">
      <h3 class="pb-4 mb-4 border-bottom">
        <?php echo('Résultats : '.$nomcat.' - '.$nomQuizz); ?>
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
      echo("<h3 class=\"mb-0\"> Question ".$count." : ".$row["question"]."</h3> </br>");

      $result2 = $bd->query("SELECT reponse, correct FROM reponses WHERE  reponses.id_question = ".$row['id']." ORDER BY reponses.id");

      $answcount = 0;
      $questionok = 1;

      while (($row2 = $result2->fetch_assoc())) 
      {
        echo('<div class="input-group mb-3">
                <div class="input-group-prepend">');
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
          if ($row2["correct"]){
            echo('<div class="input-group-text bg-success">
              <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
                  </div>');
            echo ('</div>
                <input readonly="readonly" type="text" class="form-control questionInput simplebox font-weight-bold" value="'.$row2["reponse"].'" >
              </div>');
          } else {
            echo('<div class="input-group-text bg-danger">
                  </div>');
            echo ('</div>
                <input readonly="readonly" type="text" class="form-control questionInput simplebox" value="'.$row2["reponse"].'" >
              </div>');
            $questionok = 0;

          }
          
        } else {

          if ($row2["correct"]){
            echo('
              <div class="input-group-text">
                  </div>');
            echo ('</div>
                <input readonly="readonly" type="text" class="form-control questionInput simplebox font-weight-bold" value="'.$row2["reponse"].'" >
                <div class="input-group-append">
              <input readonly="readonly" type="text" class=" form-control questionInput simplebox text-success font-weight-bold" value="Bonne réponse" >
              </div>
              </div>
              ');
            $questionok = 0;
          } else {
            echo('<div class="input-group-text">
                  </div>');
            echo ('</div>
                <input readonly="readonly" type="text" class="form-control questionInput simplebox" value="'.$row2["reponse"].'" >
              </div>');
          }
        }
      
      }
      $result2->free();

      if ($questionok){
        echo("Vous avez bien répondu à cette question.");
        $score++;
      } else {
        echo("Vous n'avez pas bien répondu à cette question.");
      }
      echo('</br>');
      echo('</br>');
      echo('</br>');

    }
    $result->free();

    if ($count !== count($repUser)){
      echo("probleme nombre de réponse différent");
    } else {
      echo('<h2 class="text-center pb-2">');
      if ($score > $count/2){
        echo('Bravo');
      } else {
        echo('Vous pouvez faire mieux');
      }
      if(isset($_SESSION) && isset($_SESSION['login'])){
        echo(', '.ucfirst($_SESSION['login']));
      }
      echo(' !
      Vous avez obtenu un score de '.$score.'/'.$count.'.
      </h2>');
      
      if(isset($_SESSION) && isset($_SESSION['login'])){
        $idU = $_SESSION['idUtilisateur'];
        if($scoresearch = $bd->query('SELECT * FROM scores WHERE id_quizz='.$idQuizz.' AND id_utilisateur='.$_SESSION['idUtilisateur'])){
          if ($rowscore = $scoresearch->fetch_assoc()){
            if ($rowscore['score'] < $score){
              if (!($stmt = $bd->query("UPDATE scores SET score=$score WHERE id_quizz=$idQuizz AND id_utilisateur=$idU"))){
                echo('<h3 class="text-center pb-4 mb-4">Une erreur s\'est produite, votre score n\'a pas pu être enregistré !</h3>');
              } else {
                echo('<h3 class="text-center pb-4 mb-4">Vous avez amélioré votre score !</h3>');
              }
            } else if ($rowscore['score'] > $score) {
              echo('<h3 class="text-center pb-4 mb-4">Ce score n\'est pas votre meilleure performance, il ne sera donc pas enregistré.</h3>');
            } else {
              echo('<h3 class="text-center pb-4 mb-4">Vous avez reproduit votre meilleur score !</h3>');
              if (!($stmt = $bd->query("UPDATE scores SET score=$score WHERE id_quizz=$idQuizz AND id_utilisateur=$idU"))){
                echo('<h3 class="text-center pb-4 mb-4">Une erreur s\'est produite, votre score n\'a pas pu être enregistré !</h3>');
              }
            }
          } else {
          if (!($stmt = $bd->query("INSERT INTO scores(score, id_utilisateur, id_quizz) VALUES ($score, $idU, $idQuizz)"))){
              echo('<h3 class="text-center pb-4 mb-4">Une erreur s\'est produite, votre score n\'a pas pu être enregistré !</h3>');
          } else {
              echo('<h3 class="text-center pb-4 mb-4">C\'est la première fois que vous finissez ce quizz, votre score a été enregistré !</h3>');
          }
          
        }
          $scoresearch->free();
        } 
        
      } else {
        echo('<h3 class="text-center pb-4 mb-4">Vous n\'êtes pas connecté, votre score se perd dans les limbes...</h3>');
      }
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