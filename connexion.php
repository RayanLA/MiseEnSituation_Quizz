<?php

session_start();
include ('header.php');

$passe = md5($_POST['password']);
$login = ucfirst(strtolower($_POST['login']));
/*require_once ("databaseRequests.php");*/

$conn = OpenCon();
if ($stmt = $conn->prepare("SELECT COUNT(id), id FROM utilisateurs WHERE login=? AND mdp=?")){
    
    $stmt->bind_param("ss", $login, $passe);
    //echo('SELECT COUNT(id) FROM utilisateurs WHERE login='.$_POST['login'].' AND mdp='.$passe);
    $stmt->execute();
    $count_result = 0; 
    $idLogin;
    $stmt->bind_result($count_result,$idLogin);
    $stmt->fetch();

    $_SESSION['idUtilisateur'] = $idLogin;

    if ($count_result==1){
    //connexion
    $res = mysqli_fetch_row($result);

    $_SESSION['login']   = $_POST['login'];
    $_SESSION['isGuest'] = false;
    $_SESSION['justConnected'] = true;
    
    JS_Redirect("index.php");

    }
    else {
        $err = 0;
        CloseCon($conn);
        JS_Redirect("index.php?f=$err");
    }
    $stmt->close();
}

else {
    //login ou mdp incorrect    
    $err = 0;
    CloseCon($conn);
    JS_Redirect("index.php?f=$err");
}
CloseCon($conn);
?>