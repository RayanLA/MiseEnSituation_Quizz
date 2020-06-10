<html>
  <?php
  include 'header.php';

  if(isset($_POST['isGuest']) && $_POST['isGuest']) $_SESSION['isGuest']=$_POST['isGuest'];

  if (isset($_POST['idQuizz']) && isset($_POST['nomQuizz']) && isset($_POST['numquestion']) && isset($_POST['nomcat'])){
    $idQuizz = $_POST['idQuizz'];
    $nomQuizz = $_POST['nomQuizz'];
    $numquestion = $_POST['numquestion'];
    $nomcat = $_POST['nomcat'];
  ?>
  
  

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
      if($count == $numquestion){

  ?>
    <div class="col-md-12 blog-main">
      <h3 class="pb-4 mb-4 border-bottom">
        <?php echo($nomcat.' - '.$nomQuizz); ?>
      </h3>
    </div>  

  <div>
        <form action="questionchange.php" method="post">
        <input type="hidden" name="numquestion" id="numquestion" value="<?php echo($numquestion); ?>"/>
        <input type="hidden" name="idQuizz" id="idQuizz" value="<?php echo($idQuizz); ?>"/>
        <input type="hidden" name="nomQuizz" id="nomQuizz" value="<?php echo($nomQuizz); ?>"/>
        <input type="hidden" name="nomcat" id="nomcat" value="<?php echo($nomcat); ?>"/>

  <?php
      
        echo("<h3 class=\"mb-0\"> Question ".$count." : ".$row["question"]."</h3>");
        echo("</div>");
        
        if($result2 = $bd->query("SELECT reponse FROM reponses WHERE  reponses.id_question = ".$row['id']." ORDER BY reponses.id")){
        
          $answcount = 0;
        
          while ($row2 = $result2->fetch_assoc()) {
            $answcount++;
            
            echo '
                <div class="input-group mb-3" onclick="checkInput('.$answcount.')">
                  <div class="input-group-prepend">
                    <div class="input-group-text" id="grText_'.$answcount.'"></div>
                  </div>
                  <input readonly="readonly" type="text" class="form-control questionInput simplebox" value="'.$row2["reponse"].'" >
                </div>
                <input type="checkbox" name="question[]" value="'.$answcount.'" id="checkbox_'.$answcount.'" class="hide">
            ';
      
          }   
          $result2->free();
  ?>

              <br/>

              <div class="form-group">
                <button type="submit" class="btn btn-primary">Valider</button>
              </div>
            </form>
          </div>

  <?php
        }
      } else {
  ?>

            <form action="resultat.php" method="post" id="autoclick">
              <input type="hidden" name="numquestion" id="numquestion" value="<?php echo($numquestion); ?>"/>
              <input type="hidden" name="idQuizz" id="idQuizz" value="<?php echo($idQuizz); ?>"/>
              <input type="hidden" name="nomQuizz" id="nomQuizz" value="<?php echo($nomQuizz); ?>"/>
              <input type="hidden" name="nomcat" id="nomcat" value="<?php echo($nomcat); ?>"/>
              <br/>
              <button type="submit" class="aucunAffichage btn btn-primary">Valider</button>
            </form>
          </div>

  <script type="text/javascript">
    document.getElementById("autoclick").submit();
  </script>
      
<?php
      }
      $result->free();
    }
    Closecon($bd);
      

  } else {
    echo("Page introuvable");
  }
?>

<?php
      include 'footer.php'
?>