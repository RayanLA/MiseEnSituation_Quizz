<?php
  session_start();
  include("databaseRequests.php");
  //Pour tester 
  //testCo();
?>

<html>
    <?php

    include 'header.php';
    ?>
  
  <div class="col-md-12 blog-main">
      <h3 class="pb-4 mb-4 border-bottom">
      S'inscrire
      </h3>
  </div>  

  <br/>
<form method="post">
  <div class="form-group">
    <label for="pseudo">Pseudo</label>
    <input type="text" class="form-control" id="pseudo" name="pseudo" placeholder="Votre pseudo" value=
        "<?php echo isset($_POST['pseudo'])?$_POST['pseudo']:"";?>"
  >
  </div>
  <div class="form-group">
    <label for="mdp">Mot de Passe</label>
    <input type="password" class="form-control" id="mdp"  name="passe" placeholder="8 à 16 caractères alphanumériques">
  </div>
  <div class="form-group">
    <label for="confirmationmdp">Confirmation du mot de passe</label>
    <input type="password" class="form-control" id="confirmationmdp" name="passe2" placeholder="8 à 16 caractères alphanumériques">
  </div>
    <button type="submit" class="btn btn-primary">Valider</button>
</form>


<?php
  if(!empty($_POST['pseudo']))
  {
    $bd = OpenCon();
    
    $pseudo = strtolower(htmlspecialchars($_POST['pseudo']));

    if(preg_match('/[\s]/i', $pseudo)){
      echo "Les espaces sont interdits dans un pseudo.";
    } else {
      try {
       
        $result = $bd->query("SELECT * FROM utilisateurs WHERE login = '$pseudo'");
      } catch (PDOException $e) {
        echo $e->getMessage();
      }
    
      if ($utilisateur = $result->fetch_assoc()) {
        echo "Pseudo déjà utilisé, choisissez un autre pseudo svp.";
      } else {
        $passe = htmlspecialchars($_POST['passe']);

        if (strlen($passe) > 16 || strlen($passe) < 8 || preg_match('/[^a-z0-9]/i', $passe)) {
          echo "Le mot de passe ne respecte pas les instructions : il doit faire 8 à 16 caractères alphanumériques.";
        } else {
          $passe2 = htmlspecialchars($_POST['passe2']);
            
          if($passe == $passe2) {
            try {
              $sql5 = "INSERT INTO `utilisateurs` (`login`, `mdp`) VALUES ('$pseudo', '$passe')";
              $stmt = $bd->query($sql5);
              echo "Demande d'inscription réussie ! Connectez-vous pour que votre pseudo apparaisse dans les scores.";
              header("Refresh:3; index.php");
            } catch(PDOException $e) {
              echo $e->getMessage();
            }
          } else {
            echo 'Les deux mots de passe ne correspondent pas.';
          }
        }
      }
      $result->free();
    }

    CloseCon($bd);
  }
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