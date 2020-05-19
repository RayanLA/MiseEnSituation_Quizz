<?php
    include 'header.php';
    if(!isset($_SESSION['login'])){
        header('Location:  index.php');
    }
    

?>
<hr/>
<div class="card">
  <div class="card-header">
    <ul class="nav nav-tabs card-header-tabs">
      <li class="nav-item">
        <a class="nav-link" id="one-tab" data-toggle="tab" href="#one" role="tab" aria-controls="One" aria-selected="true">Changer le mot de passe</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" id="two-tab" data-toggle="tab" href="#two" role="tab" aria-controls="Two" aria-selected="false">Statistiques</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" id="two-tab" data-toggle="tab" href="#three" role="tab" aria-controls="Two" aria-selected="false">Derniers résultat</a>
      </li>
    </ul>
  </div>
  <div class="tab-content" id="myTabContent">
          <div class="tab-pane fade show active p-3" id="one" role="tabpanel" aria-labelledby="one-tab">
          
                <form action="#" method="post">
                    <div class="form-group">
                        <label for="password1">Ancien mot de passe</label>
                        <input type="password" class="form-control" id="password1" name="password1" aria-describedby="emailHelp">
                        
                    </div>
                    <div class="form-group">
                        <label for="password2">Nouveau mot de passe</label>
                        <input type="password" class="form-control" id="password2" name="password2">
                    </div>
                    <div class="form-group">
                        <label for="password3">Confirmation nouveau mot de passe</label>
                        <input type="password" class="form-control" id="password3" name="password3">
                    </div>
                    <div class="text-center">
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                    </div>
                </form>
            
            <!--<h5 class="card-title">Tab Card One</h5>
            <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
            <a href="#" class="btn btn-primary">Go somewhere</a> -->             
          </div>
          <div class="tab-pane fade p-3" id="two" role="tabpanel" aria-labelledby="two-tab">
            <h5 class="card-title">Tab Card Two</h5>
            <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
            <a href="#" class="btn btn-primary">Go somewhere</a>              
          </div>
          <div class="tab-pane fade p-3" id="three" role="tabpanel" aria-labelledby="three-tab">
            <h5 class="card-title">Tab Card Three</h5>
            <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
            <a href="#" class="btn btn-primary">Go somewhere</a>              
          </div>

        </div>
</div>
<br/>

<?php
      echo 'HERE0';
      if(isset($_POST['password1'])){
        echo 'HERE1';
        $bd = OpenCon();
        if ($stmt = $bd->prepare("SELECT COUNT(id) FROM utilisateurs WHERE login=? AND mdp=?")){
          echo 'here2';
          $stmt->bind_param("ss", $_SESSION['login'], $_POST['password1']);
          //echo('SELECT COUNT(id) FROM utilisateurs WHERE login='.$_SESSION['login'].' AND mdp='.$_POST['password1']);
          $stmt->execute();
          $count_result = 0; 
          $stmt->bind_result($count_result);
          $stmt->fetch();

          if ($count_result==1){
            //connexion
            echo 'here3';
            
            //$update = $bd->prepare("UPDATE utilisateurs SET mdp = '$_POST['password2']'  WHERE login = '$_SESSION['login']'");
            //$result = $update->execute(array($_POST['password2'],$_SESSION['login']));
            $pwd = $_POST['password2'];
            $login = $_SESSION['login'];
            echo "UPDATE utilisateurs SET mdp = '".$pwd."',now()  WHERE login = '".$login."'";
            $bd->query("UPDATE utilisateurs SET mdp = '".$pwd."',now()  WHERE login = '".$login."'");
            
            echo 'here4';
            
          }

        }
        CloseCon($bd);
      }
      
      include 'footer.php'
?>

