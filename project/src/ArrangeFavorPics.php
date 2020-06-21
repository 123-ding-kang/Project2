<?php
require_once('../config.php');
session_start();
try {
    $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = 'DELETE FROM travelimagefavor WHERE ImageID = :id and UID = :uid';
    $statement = $pdo->prepare($sql);
    $statement->bindValue(':id',$_POST['id']);
    $statement->bindValue(':uid',$_SESSION['UID']);
    $statement->execute();
    $pdo = null;
}
catch (PDOException $e) {
    die( $e->getMessage() );
}
?>
