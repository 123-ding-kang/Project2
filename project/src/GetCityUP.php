<?php
require_once('../config.php');
try {
    $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = 'SELECT GeoNameID,AsciiName from geocities WHERE Country_RegionCodeISO = :iso ORDER BY Population DESC ';
    $statement = $pdo->prepare($sql);
    $statement->bindValue(':iso',$_GET['ISO']);
    $statement->execute();
    while($row = $statement->fetch()){
        $arr[] = $row;
    }
    $str = json_encode($arr);
    echo $str;

    $pdo = null;
}catch (PDOException $e) {
    die( $e->getMessage() );
}

?>

