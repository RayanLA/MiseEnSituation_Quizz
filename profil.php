<?php
    include 'header.php';
    if(!isset($_SESSION['login'])){
        header('Location:  index.php');
    }
    
    $nbPlayedQuizzPerCategorie = getNbPlayedQuizzPerCategorie();
    /*$playedQuizzScore = getPlayedQuizzScore();
    $nbOfCreatedQuizz = getNbOfCreatedQuizz();
    $infoCreatedQuizz = getInfoCreatedQuizz();*/
            

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
                        <input type="password" class="form-control" id="password3" name="password3" onchange=" if(document.getElementById('password3').value== document.getElementById('password2').value){
                                                                                                                    document.getElementById('checkPwd').innerHTML = 'Confirmation mot de passe validée'; 
                                                                                                                    document.getElementById('checkPwd').className = 'text-success'; 
                                                                                                                    document.getElementById('btn_valid').disabled = false;    
                                                                                                                }else{
                                                                                                                    document.getElementById('checkPwd').innerHTML = 'Confirmation mot de passe invalide';
                                                                                                                    document.getElementById('checkPwd').className = 'text-danger';     
                                                                                                                }">
                        <small id="checkPwd" class="form-text text-muted"></small>
                    </div>
                    <div class="text-center">
                    <button type="submit" class="btn btn-primary" id="btn_valid" disabled>Enregistrer</button>
                    </div>
                </form>
            
            <!--<h5 class="card-title">Tab Card One</h5>
            <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
            <a href="#" class="btn btn-primary">Go somewhere</a> -->             
          </div>

          <div class="tab-pane fade p-3" id="two" role="tabpanel" aria-labelledby="two-tab"> 
            <!-- Le nb de quizz joué par catégorie
             Le nombre de bonne réponse par quizz
             Le nombre de quizz créé
             Le nb de joueur à ses quizz -->
             <?php 
             echo '<div class="row">';
             foreach ($nbPlayedQuizzPerCategorie as $key => $value) {
                echo '<div class="col-sm-4">';
                echo    '<div class="card" >
                            <div class="card-body">';
                echo          '<h5 class="card-title">'.$key.'</h5>';
                echo          '<p class="card-text">A été joué <span class="titleImitation">'.$value.'</span> fois !</b></p>';
                echo        '</div>
                        </div>';
                echo  '</div>';
             }      
             echo "</div>";
             ?>

          </div>

          <div class="tab-pane fade p-3" id="three" role="tabpanel" aria-labelledby="three-tab">
            <?php
             $sql = "   SELECT categories.nom as cnom,score,quizz.nom as qnom,COUNT(questions.question) as nbQuestion 
                        FROM categories,scores,quizz JOIN questions ON questions.id_quizz = quizz.id 
                        WHERE id_utilisateur = 1 AND categories.id = quizz.id_categorie AND scores.id_quizz = quizz.id 
                        GROUP BY questions.id_quizz ORDER BY scores.id DESC LIMIT 12";
             $con = OpenCon();
             $res = $con->query($sql);     
             //var_dump($res);       
             echo '<div class="row">';
             while (($row = $res->fetch_assoc())) {
                echo '<div class="col-sm-4">';
                echo    '<div class="card" >
                            <div class="card-body">';
                echo          '<h5 class="card-title">'.$row["qnom"].'</h5>';
                echo          '<h6 class="card-subtitle mb-2 text-muted">Catégorie : '.$row["cnom"].'</h6>';
                echo          '<p class="card-text">Score : <b>'.$row["score"].'/'.$row["nbQuestion"].'</b></p>';
                echo        '</div>
                        </div>';
                echo  '</div>';
             }      
             echo "</div>";
              CloseCon($con);
            ?>
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

