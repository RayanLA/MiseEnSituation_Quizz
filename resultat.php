<?php
  session_start();
?>

<html>
    <?php

    include 'header.php';
    ?>
  
  <div class="col-md-12 blog-main">
      <h3 class="pb-4 mb-4 border-bottom">
      <?php
        $_POST['idQuizz'] = 1;
        $_POST['nomQuizz'] = 'Quiz';
        $idq = $_POST['idQuizz'];
        echo($_POST['nomQuizz']);
      ?>
      </h3>
  </div>  

  <div>
  <?php

    $bd = OpenCon();
    $result = $bd->query("SELECT question, id FROM questions WHERE questions.id_quizz = $idq ORDER BY questions.id");

    $count = 1;

    while (($row = $result->fetch_assoc())) {
      echo("<h3 class=\"mb-0\"> Question ".$count." : ".$row["question"]."</h3>");

      $result2 = $bd->query("SELECT reponse, correct FROM reponses WHERE  reponses.id_question = ".$row['id']." ORDER BY reponses.id");


      while (($row2 = $result2->fetch_assoc())) {

      echo("<div class=\"mb-1 text-muted\">".$row2["reponse"]."</div>");
      echo("<p class=\"mb-auto\">".$row2["correct"]."</p>");
      
      }
      $result2->free();
      $count ++;

    }
    $result->free();

    CloseCon($bd);

  ?>
    
  </div>



<div id="loginModal" class="modal fade" role="dialog">
  <!--<span onclick="document.getElementById('id01').style.display='none'" class="close" title="Close Modal">&times;</span>-->
  <div class="modal-dialog form-group">
    <form class="modal-content" action="#">
      <div class="container">
        <div class="modal-header">
          <h5 class="modal-title" id="title" style="text-align: center">S'identifier</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <label for="email"><b>Identifiant</b></label>
          <input type="text" placeholder="Pseudo" class="form-control" name="email" required>

          <label for="psw"><b>Mot de passe</b></label>
          <input type="password"  class="form-control" placeholder="Enter Password" name="psw" required>
          <br/>
          Pas encore inscrit ? <a href="inscription.php">Inscription</a>
          

        </div>
        <div class="modal-footer">
        <!--<p>By creating an account you agree to our <a href="#" style="color:dodgerblue">Terms & Privacy</a>.</p>-->
          <div class="clearfix">
            <button type="submit" class="btn btn-secondary">Connexion</button>
          </div>
          
        </div>
      </div>
    </form>
  </div>
</div>

<footer class="blog-footer">
  <p>Quizzio by RILAR.</p>
  <p>
    <a href="#">Retournez en haut</a>
  </p>
</footer>
</body>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
      <script>window.jQuery || document.write('<script src="../assets/js/vendor/jquery.slim.min.js"><\/script>')</script><script src="bootstrap/js/bootstrap.bundle.js"></script></body>
</html>

</html>