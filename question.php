<html>
    <?php

    include 'header.php';
    if (isset($_POST['idQuizz']) && isset($_POST['nomQuizz']) && isset($_POST['numquestion']) && isset($_POST['nomcat'])){
      $idQuizz = $_POST['idQuizz'];
      $nomQuizz = $_POST['nomQuizz'];
      $numquestion = $_POST['numquestion'];
      $nomcat = $_POST['nomcat'];
    ?>
  
  <div class="col-md-12 blog-main">
      <h3 class="pb-4 mb-4 border-bottom">
        <?php echo($nomQuizz);
              echo("\n".$nomcat); ?>
      </h3>
  </div>  

  <div>

      <?php
        $bd = OpenCon();
        
            if($result = $bd->query("SELECT question, id FROM questions WHERE questions.id_quizz = $idQuizz ORDER BY questions.id")){
             $count = 0;

        while ($row = $result->fetch_assoc()){
          $count++;
          if ($count == $numquestion){
            break;
          } 
        }
        if($count == $numquestion){ ?>
          <form action="questionchange.php" method="post">
            <input name="numquestion" id="numquestion" value="<?php echo($numquestion); ?>"/>
            <input name="idQuizz" id="idQuizz" value="<?php echo($idQuizz); ?>"/>
            <input name="nomQuizz" id="nomQuizz" value="<?php echo($nomQuizz); ?>"/>
            <input name="nomcat" id="nomcat" value="<?php echo($nomcat); ?>"/>

          <?php
      
          echo("<h3 class=\"mb-0\"> Question ".$count." : ".$row["question"]."</h3>");
          echo("</div>");
        
          if($result2 = $bd->query("SELECT reponse FROM reponses WHERE  reponses.id_question = ".$row['id']." ORDER BY reponses.id")){
              $answcount = 0;
        
              while (($row2 = $result2->fetch_assoc())) {
                $answcount++;
                echo("<div class=\"form-check\" >");
                echo("<input class=\"form-check-input\" type=\"checkbox\" name=\"question[]\" value=\"".$answcount."\" id=\"defaultCheck1\">");
                echo("<label class=\"form-check-label\" for=\"defaultCheck1\">".$row2["reponse"]."</label>");
                echo("</div>");
      
                }   
          $result2->free();
          }
          $result->free();
        }
        
          CloseCon($bd);

      ?>

      <br/>

      <div class="form-group">
        <button type="submit" class="btn btn-primary">Valider</button>
      </div>
    </form>
  </div>
      <?php
        } else {
      ?>
      <form action="resultat.php" method="post" id="autoclick">
        <input name="numquestion" id="numquestion" value="<?php echo($numquestion); ?>"/>
        <input name="idQuizz" id="idQuizz" value="<?php echo($idQuizz); ?>"/>
        <input name="nomQuizz" id="nomQuizz" value="<?php echo($nomQuizz); ?>"/>
        <input name="nomcat" id="nomcat" value="<?php echo($nomcat); ?>"/>
        <br/>

      <div class="form-group">
        <button type="submit" class="btn btn-primary">Valider</button>
      </div>
    </form>
  </div>

  <script type="text/javascript">
    document.getElementById("autoclick").submit();
  </script>
      
<?php
    }
  } else {
    echo("Page introuvable");
  }
?>

<?php
      include 'footer.php'
?>
