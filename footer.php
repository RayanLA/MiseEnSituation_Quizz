
<div id="loginModal" class="modal fade" role="dialog">
  <!--<span onclick="document.getElementById('id01').style.display='none'" class="close" title="Close Modal">&times;</span>-->
  <div class="modal-dialog form-group">
    <form class="modal-content" action="connexion.php" method="post">
      <div class="container">
        <div class="modal-header">
          <h5 class="modal-title alignCenter" id="title">S'identifier</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <span class="hide mb-2 text-primary" id="inscriptionMessage">Votre inscription est terminée, veuillez vous identifiez :) </span>
          <label for="email"><b>Identifiant</b></label>
          <input type="text" placeholder="Pseudo" class="form-control" name="login" required>

          <label for="psw"><b>Mot de passe</b></label>
          <input type="password"  class="form-control" placeholder="Enter Password" name="password" required>
          <br/>
          <span class="hide mb-2 text-danger" id="IncorrectPsw"> Login ou mot de passe incorrect ! </span>

          <span id="inscrivezVous"> Pas encore inscrit ? <a href="inscription.php">Inscription</a></span>
          

        </div>
        <div class="modal-footer">
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

</html>