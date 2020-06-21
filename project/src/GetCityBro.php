<?php
require_once('../config.php');
try {
    $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

   // $_GET['ISO'] = 'IT';
    $sql = 'SELECT GeoNameID,AsciiName from geocities JOIN travelimage ON travelimage.CityCode = GeoNameID WHERE geocities.Country_RegionCodeISO = :iso GROUP BY GeoNameID ORDER BY Population DESC';
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
