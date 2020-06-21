<?php
require_once('../config.php');
function outputCouRegMod($picRow){
    try {
        $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = 'SELECT ISO,Country_RegionName from geocountries_regions';
        $statement = $pdo->query($sql);

        while($row = $statement->fetch()){
            if($picRow['Country_RegionCodeISO'] == $row['ISO'])
            echo '<option value="'.$row['ISO'].'" selected>'.$row['Country_RegionName'].'</option>';
            else{
                echo '<option value="'.$row['ISO'].'">'.$row['Country_RegionName'].'</option>';
            }
        }
        $pdo = null;
    }catch (PDOException $e) {
        die( $e->getMessage() );
    }
}
function outputCouRegUP(){
    try {
        $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = 'SELECT ISO,Country_RegionName from geocountries_regions';
        $statement = $pdo->query($sql);

        while($row = $statement->fetch()){
            echo '<option value="'.$row['ISO'].'">'.$row['Country_RegionName'].'</option>';
        }
        $pdo = null;
    }catch (PDOException $e) {
        die( $e->getMessage() );
    }
}
function outputCouRegBro(){
    try {
        $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = 'SELECT ISO,Country_RegionName from geocountries_regions JOIN travelimage ON travelimage.Country_RegionCodeISO = ISO GROUP BY ISO';
        $statement = $pdo->query($sql);

        while($row = $statement->fetch()){
            echo '<option value="'.$row['ISO'].'">'.$row['Country_RegionName'].'</option>';
        }
        $pdo = null;
    }catch (PDOException $e) {
        die( $e->getMessage() );
    }
}
?>
