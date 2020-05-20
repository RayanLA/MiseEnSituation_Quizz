<?php

session_start();
include ('header.php');


/*require_once ("databaseRequests.php");*/

$conn = OpenCon();
if ($stmt = $conn->prepare("SELECT COUNT(id), id FROM utilisateurs WHERE login=? AND mdp=?")){

    $stmt->bind_param("ss", $_POST['login'], $_POST['password']);
    echo('SELECT COUNT(id) FROM utilisateurs WHERE login='.$_POST['login'].' AND mdp='.$_POST['password']);
    $stmt->execute();
    $count_result = 0; 
    $idLogin;
    $stmt->bind_result($count_result,$idLogin);
    $stmt->fetch();

    $_SESSION['idUtilisateur'] = $idLogin;


    if ($count_result==1){
    //connexion
    $res = mysqli_fetch_row($result);

    $_SESSION['login'] = $_POST['login'];

    header("Location:index.php?f=1");
    }
    else {
        $err = 0;
        CloseCon($conn);
        header("Location:index.php?f=$err");
    }
    $stmt->close();
}

else {
    //login ou mdp incorrect    
    $err = 0;
    CloseCon($conn);
    header("Location:index.php?f=$err");
}
CloseCon($conn);
?>