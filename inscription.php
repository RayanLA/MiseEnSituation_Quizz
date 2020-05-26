<html>
    <?php
    include 'header.php';
    if(isset($_SESSION["login"])){
      JS_Redirect("index.php");
    }
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
        $hashedPasse = md5($passe);
        
        if (strlen($passe) > 16 || strlen($passe) < 8 || preg_match('/[^a-z0-9]/i', $passe)) {
          echo "Le mot de passe ne respecte pas les instructions : il doit faire 8 à 16 caractères alphanumériques.";
        } else {
          $passe2 = htmlspecialchars($_POST['passe2']);
            
          if($passe == $passe2) {
            try {
              $sql5 = "INSERT INTO `utilisateurs` (`login`, `mdp`) VALUES ('$pseudo', '$hashedPasse')";
              $stmt = $bd->query($sql5);
              echo "Demande d'inscription réussie ! Connectez-vous pour que votre pseudo apparaisse dans les scores.";
              JS_RedirectSubscription("index.php");
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


<?php
      include 'footer.php'
?>