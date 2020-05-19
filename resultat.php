
<html>
    <?php

    include 'header.php';
    ?>
  
  <div class="col-md-12 blog-main">
      <h3 class="pb-4 mb-4 border-bottom">
      <?php
        $answer[] = 1;
        $_POST['repUser'] = $answer;
        $_POST['idQuizz'] = 1;
        $_POST['nomQuizz'] = 'Quiz';
        $idq = $_POST['idQuizz'];
        echo($_POST['nomQuizz']);
      ?>
      </h3>
  </div>  

  <div>
  <?php
    $repUser = $_POST['repUser'];


    $bd = OpenCon();
    $result = $bd->query("SELECT question, id FROM questions WHERE questions.id_quizz = $idq ORDER BY questions.id");

    $count = 0;

    while (($row = $result->fetch_assoc())) {
      $count++;
      echo("<h3 class=\"mb-0\"> Question ".$count." : ".$row["question"]."</h3>");

      $result2 = $bd->query("SELECT reponse, correct FROM reponses WHERE  reponses.id_question = ".$row['id']." ORDER BY reponses.id");


      while (($row2 = $result2->fetch_assoc())) {

      echo("<div class=\"mb-1 text-muted\">".$row2["reponse"]."</div>");
      echo("<p class=\"mb-auto\">".$row2["correct"]."</p>");
      
      }
      $result2->free();

    }
    $result->free();
    if ($count !== count($repUser)){
      echo("probleme nombre de réponse différent");
    }

    CloseCon($bd);

  ?>
    
  </div>



<?php
      include 'footer.php'
?>