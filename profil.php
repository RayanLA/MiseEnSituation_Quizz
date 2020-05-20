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
    try {
      /*echo 'HERE0';*/
      if(isset($_POST['password1'])){
      $pwdIsCorrect = verifyIdentity($_SESSION['login'], $_POST['password1']);
      if($pwdIsCorrect){
        $pwd = $_POST['password2'];
        $login = $_SESSION['login'];
        $requestSQL = "UPDATE utilisateurs SET mdp = '".$pwd."'  WHERE login = '".$login."'";

        $conn1 = OpenCon();
        $conn1->query($requestSQL);
        CloseCon($conn1);
      }

      }
    } catch (Exception $e) {
      var_dump($e);
    }
      
      
      include 'footer.php'
?>

