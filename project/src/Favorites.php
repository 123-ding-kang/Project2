<?php
session_start();
require_once('../config.php');
include_once('outputNavLink.php');
include_once('outputPage.php');
if(!isset($_SESSION['UID'])){
    echo '<script type="text/javascript">location.replace("../index.php")</script>';
}
function outputFavorPics() {
    try {
        $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        if(isset($_GET['page']) ){
            $page = intval( $_GET['page'] );
        }
        else{
            $page = 1;
        }
        $PageSize = 6;
        $sql = 'SELECT COUNT(*) AS amount from travelimage JOIN travelimagefavor ON travelimagefavor.UID = '.$_SESSION['UID'].' WHERE travelimage.ImageID = travelimagefavor.ImageID';
        $result = $pdo->query($sql);
        $row = $result->fetch();
        $amount = $row['amount'];
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
        $startNum = 6*($page-1);
        $sql = 'select travelimage.ImageID,Title,Description,PATH from travelimage JOIN travelimagefavor ON travelimagefavor.UID = :id WHERE travelimage.ImageID = travelimagefavor.ImageID LIMIT '.$startNum.',6';
        $id =  $_SESSION['UID'];
        $statement = $pdo->prepare($sql);
        $statement->bindValue(':id', $id);
        $statement->execute();

        echo '<ul id="FavoritesList">';
        while($row = $statement->fetch()){
            if($row['Description'] == null){
                $row['Description'] = "The author is so lazy that he/she doesn't give any detailed description about this Photo.TUT";
            }
            outputSinglePic($row);
        }
        echo '</ul>';
        outputPageLink($page,$totalPage,'Favorites');
        $pdo = null;
    }catch (PDOException $e) {
        die( $e->getMessage() );
    }
}
function outputSinglePic($row) {
    echo '<li>';
    echo '<div class="favoritePic">';
    echo '<figure>';
    echo '<div class="PicWrapper">';
    $img = '<img class="normalPic" src="../images/normal/medium/'.$row['PATH'].'">';
    echo constructPicLink($row['ImageID'], $img);
    echo '</div><figcaption><div class="favorPicTopic">';
    echo $row['Title'];
    echo '</div><div class="description">';
    echo $row['Description'];
    echo '</div></figcaption>';
    echo '<input class="removeFavor" type="button" name="Remove" value="remove" alt="'.$row['ImageID'].'">';
    echo '</figure></div></li>';
}

/*
  Constructs a link given the genre id and a label (which could
  be a name or even an image tag
*/
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>收藏页</title>
    <link href="../CSS/reset.css" rel="stylesheet">
    <link href="../CSS/HeaderNavMainFooterPic.css" rel="stylesheet">
    <link href="../CSS/pageNavFooter.css" rel="stylesheet">
    <link href="../CSS/Favorites.css" rel="stylesheet">
</head>
<body onresize="squareClip()" onload="squareClip()">
<header>
    <nav>
        <ul id="navPublic">
            <li><a href="../index.php">Home</a></li>
            <li><a href="Browser.php">Browser</a></li>
            <li><a href="Search.php">Search</a></li>
        </ul>
    </nav>
</header>
<main>
    <div class="NoFavorPic">
        <p>
            You haven't added any photo to your favorites, why not visit the browser?
        </p>
    </div>
    <?php outputFavorPics(); ?>

</main>
<footer>
    <a> email:19302010048@fudan.edu.cn</a><br>
    <a>phone number:15516718796</a>
</footer>
<script type="text/javascript" src="../JavaScript/jquery.js"></script>
<script type="text/javascript" src="../JavaScript/ImgClip.js"></script>
<script type="text/javascript" src="../JavaScript/WhenNothing.js"></script>
<script type="text/javascript" src="../JavaScript/RemoveLikePic.js"></script>
</body>
</html>
