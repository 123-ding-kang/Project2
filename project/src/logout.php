<?php
session_start();
require_once('../config.php');
try {
    $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $lastModifiedDate = date("Y-m-d H:i:s");
    $sql = 'UPDATE traveluser SET DateLastModified = :currentDate WHERE UID = :uid';
    $statement = $pdo->prepare($sql);
    $statement->bindValue(':currentDate',$lastModifiedDate);
    $statement->bindValue(':uid',$_SESSION['UID']);
    $statement->execute();
    $pdo = null;
}catch (PDOException $e) {
    die( $e->getMessage() );
}
unset($_SESSION);
header("Location: login.php");
?>

