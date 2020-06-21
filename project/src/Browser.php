<?php
session_start();
require_once('../config.php');
include_once('outputNavLink.php');
include_once('outputPageBrowser.php');
include_once('outputCouReg.php');

function outputBrowserPics() {
    if(isset($_GET['query'])){
        $query = $_GET['query'];
        $mode = 'single';
    }
    else{
        $CityCode = $_GET['Cities'];
        $ISO = $_GET['CouRegs'];
        $content = $_GET['Content'];
        $mode = 'multiple';
        if($CityCode == 'default'&&$ISO == 'default'&&$content=='default'){
            return;
        }
    }

    try {
        $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        if(isset($_GET['page']) ){
            $page = intval( $_GET['page'] );
        }
        else{
            $page = 1;
        }
        if($mode == 'single'){
            if($_GET['way']== 'city'){
                $numSQL ='select COUNT(*) AS amount from travelimage WHERE CityCode = :query';
            }elseif($_GET['way']== 'countryRegion'){
                $numSQL ='select COUNT(*) AS amount from travelimage WHERE Country_RegionCodeISO = :query';
            }elseif($_GET['way']== 'content'){
                $numSQL ='select COUNT(*) AS amount from travelimage WHERE Content = :query';
            }elseif($_GET['way'] == 'Filter'){
                $numSQL ='select COUNT(*) AS amount from travelimage WHERE Title like "%'.$query.'%"';
            }
        }else{
            $numSQL ='select COUNT(*) AS amount from travelimage WHERE ';
            $numSQL = getSQL($numSQL,$content,$CityCode,$ISO);

        }

        $numRes = $pdo->prepare($numSQL);
        if($mode == 'single' && $_GET['way']!='Filter'){
            $numRes->bindValue(':query',$query);
        }

        $numRes->execute();
        $row = $numRes->fetch();
        $amount = $row['amount'];

        $PageSize = 9;
        $rowSize = 3;
        if( $amount ){
            if( $amount % $PageSize ){                                  //取总数据量除以每页数的余数
                $totalPage = (int)($amount / $PageSize) + 1;           //如果有余数，则页数等于总数据量除以每页数的结果取整再加一
            }else{
                $totalPage = $amount / $PageSize;                      //如果没有余数，则页数等于总数据量除以每页数的结果
            }
        }
        else{
            $totalPage = 0;
        }
        if($amount == 0){
            echo '<script type="text/javascript">alert("Oops! Nothing is found,change some keywords and try again");</script>';
            return;
        }
        $currentPageAmount =($page != $totalPage)?9:($amount - ($page-1)*9);
        if($page == $totalPage){
            if( $currentPageAmount % $rowSize ){
                $totalRow = (int)($currentPageAmount / $rowSize) + 1;
            }else{
                $totalRow = $currentPageAmount / $rowSize;
            }
            $moreNum = $currentPageAmount % $rowSize;
        }
        else{
            if( $currentPageAmount % $rowSize ){
                $totalRow = (int)(9 / $rowSize) + 1;
            }else{
                $totalRow = 9 / $rowSize;
            }
            $moreNum = 9 % $rowSize;
        }
        $startNum = 9*($page-1);
        if($mode == 'single'){
            if($_GET['way']== 'city'){
                $sql ='select ImageID,PATH from travelimage WHERE CityCode = :query LIMIT '.$startNum.',9';
            }elseif($_GET['way']== 'countryRegion'){
                $sql ='select ImageID,PATH from travelimage WHERE Country_RegionCodeISO = :query LIMIT '.$startNum.',9';
            }elseif($_GET['way']== 'content'){
                $sql ='select ImageID,PATH from travelimage WHERE Content = :query LIMIT '.$startNum.',9';
            }elseif($_GET['way'] == 'Filter'){
                $sql ='select ImageID,PATH from travelimage WHERE Title like "%'.$query.'%" LIMIT '.$startNum.',9';
            }
        }
        else{
            $sql ='select ImageID,PATH from travelimage WHERE ';
            $sql = getSQL($sql,$content,$CityCode,$ISO);
            $sql .= ' LIMIT '.$startNum.',9';
        }

        $result = $pdo->prepare($sql);
        if($mode == 'single'&&$_GET['way']!='Filter'){
            $result->bindValue(':query',$query);
        }

        $result->execute();

        outputTable($totalRow,$moreNum,$result);
        if($mode == 'single'){
            outputPageLink($page,$totalPage,$_GET['way'],$query);
        }else{
            outputMulPage($page,$totalPage,$content,$CityCode,$ISO);
        }
        $pdo = null;
    }catch (PDOException $e) {
        die( $e->getMessage() );
    }
}

function getSQL($originalSQL,$content,$CityCode,$ISO){
    if($content != 'default'){
        $originalSQL .= 'Content = "'.$content.'"';
        if($ISO != 'default'){
            $originalSQL .= ' AND Country_RegionCodeISO = "'.$ISO.'"';
            if($CityCode != 'default'){
                $originalSQL .= ' AND CityCode = "'.$CityCode.'"';
            }
        }
        elseif($CityCode != 'default'){
            $originalSQL .= ' AND CityCode = "'.$CityCode.'"';
        }
    }
    else{
        $originalSQL .= 'Country_RegionCodeISO = "'.$ISO.'"';
        if($CityCode != 'default'){
            $originalSQL .= ' AND CityCode = "'.$CityCode.'"';
        }
    }
    return $originalSQL;
}
function outputTable($totalRow,$moreNum,$result){
    echo '<section class="results">';
    echo '<table id="BrowserPic">';
    for($i = 0;$i < $totalRow-1; $i++){
        echo '<tr>';
        for($j = 0;$j < 3;$j++){
            $row = $result->fetch();
            outputSinglePic($row);
        }
        echo '</tr>';
    }
    if($moreNum != 0){
        echo '<tr>';
        for($j = 0;$j < $moreNum;$j++){
            $row = $result->fetch();
            outputSinglePic($row);
        }
        echo '</tr>';
    }
    else{
        echo '<tr>';
        for($j = 0;$j < 3;$j++){
            $row = $result->fetch();
            outputSinglePic($row);
        }
        echo '</tr>';
    }
    echo '</table>';
    echo '</section>';
}
function outputSinglePic($row) {
    echo '<td>';
    $img = '<img class="normalPic" src="../images/normal/medium/'.$row['PATH'].'">';
    echo constructPicLink($row['ImageID'], $img);
    echo '</td>';
}
function outputHotContent(){
    try {
        $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = 'SELECT Content,COUNT(Content) AS instnum FROM travelimage GROUP BY Content ORDER BY instnum DESC LIMIT 0,5';
        $result = $pdo->query($sql);

        while($row = $result->fetch()){
            echo '<li>';
            echo  constructFilterLink('content',$row['Content'],$row['Content']);
            echo '</li>';
        }

        $pdo = null;
    }catch (PDOException $e) {
        die( $e->getMessage() );
    }
}
function outputHotCities(){
    try {
        $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = 'SELECT AsciiName,GeoNameID,COUNT(travelimage.CityCode) AS instnum FROM geocities  LEFT JOIN travelimage ON travelimage.CityCode =geocities.GeoNameID GROUP BY GeoNameID ORDER BY instnum DESC LIMIT 0,6';
        $result = $pdo->query($sql);

        while($row = $result->fetch()){
            echo '<li>';
            echo  constructFilterLink('city',$row['GeoNameID'],$row['AsciiName']);
            echo '</li>';
        }

        $pdo = null;
    }catch (PDOException $e) {
        die( $e->getMessage() );
    }
}
function outputHotCouReg(){
    try {
        $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = 'SELECT Country_RegionName,ISO,COUNT(travelimage.Country_RegionCodeISO) AS instnum FROM geocountries_regions LEFT JOIN travelimage ON travelimage.Country_RegionCodeISO =geocountries_regions.ISO GROUP BY ISO ORDER BY instnum DESC LIMIT 0,4';
        $result = $pdo->query($sql);
        while($row = $result->fetch()){
            echo '<li>';
            echo  constructFilterLink('countryRegion',$row['ISO'],$row['Country_RegionName']);
            echo '</li>';
        }
        $pdo = null;
    }catch (PDOException $e) {
        die( $e->getMessage() );
    }
}

function constructFilterLink($way,$query,$label){
    $link = '<a href="../src/Browser.php?way='.$way.'&query='.$query.'">';
    $link .= $label;
    $link .= '</a>';
    return $link;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>浏览页</title>
    <link href="../CSS/reset.css" rel="stylesheet">
    <link href="../CSS/HeaderNavMainFooterPic.css" rel="stylesheet">
    <link href="../CSS/Browser.css" rel="stylesheet">
</head>
<body onload="squareClip()" onresize="squareClip()">
<header>
    <nav>
        <ul id="navPublic">
            <li><a href="../index.php" id="currentPage">Home</a></li>
            <li><a href="Browser.php">Browser</a></li>
            <li><a href="Search.php">Search</a></li>

        </ul>
    </nav>
</header>
<main>
    <aside>
        <div id="FilterSearch">
            <form>
                <h2>Filter</h2>
                <input type="text" name="query" id="nameText"><input type="submit" name="way" value="Filter" id="nameSearch">
            </form>
        </div>
        <div id="HotContent">
            <h2>Hot Content</h2>
            <ul><?php outputHotContent();?></ul>
        </div>
        <div id="HotCountry">
            <h2>Hot Countries</h2>
            <ul><?php outputHotCouReg();?></ul>
        </div>
        <div id="HotCity">
            <h2>Hot Cities</h2>
            <ul><?php outputHotCities();?></ul>
        </div>
    </aside>
    <div class="FilterResults">
        <section class="filter">
            <form action="" method="get" role="form">
                <fieldset>
                    <legend>Filter</legend>
                    <select name="Content" required>
                        <option value="default" selected>-Content-</option>
                        <option value="scenery">Scenery</option>
                        <option value="city">City</option>
                        <option value="people">People</option>
                        <option value="animal">Animal</option>
                        <option value="building">Building</option>
                        <option value="wonder">Wonder</option>
                        <option value="other">Other</option>
                    </select>
                    <select name="CouRegs" id="Countries" onchange="setCity(this, this.form.Cities)" required>
                        <option value="default" selected>-Countries-</option>
                        <?php outputCouRegBro();?>
                    </select>
                    <select name="Cities" id="Cities">
                        <option value="default" selected>-Cities-</option>
                    </select>
                    <input type="submit" name="filter" value="FILTER">
                </fieldset>
            </form>
        </section>
        <?php if(isset($_GET['query']) || isset($_GET['Cities'])) outputBrowserPics(); ?>
    </div>
</main>
<footer>
    <div class="Information">
        <<a> email:19302010048@fudan.edu.cn</a><br>
        <a>phone number:15516718796</a>
    </div>
</footer>
<script type="text/javascript" src="../JavaScript/jquery.js"></script>
<script type="text/javascript" src="../JavaScript/ImgClip.js"></script>
<script type="text/javascript" src="../JavaScript/Filter.js"></script>
</body>
</html>
