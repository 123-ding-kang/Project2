<?php
session_start();
require_once('../config.php');
include_once('outputNavLink.php');
include_once('outputPage.php');
if(!isset($_SESSION['UID'])){
    echo '<script type="text/javascript">location.replace("../index.php")</script>';
}
function outputMyPics() {
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

        $sql = 'SELECT COUNT(*) AS amount FROM travelimage WHERE travelimage.UID = '.$_SESSION['UID'];
        $result = $pdo->query($sql);

        $row = $result->fetch();
        $amount = $row['amount'];
        if($amount){
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
        $sql = 'select ImageID,Title, Description,PATH from travelimage WHERE travelimage.UID = :id LIMIT '.$startNum.',6';
        $id =  $_SESSION['UID'];
        $statement = $pdo->prepare($sql);
        $statement->bindValue(':id', $id);
        $statement->execute();

        echo '<ul id="MyPhotoList">';
        while($row = $statement->fetch()){
            if($row['Description'] == null){
                $row['Description'] = "The author is so lazy that he/she doesn't give any detailed description about this Photo.TUT";
            }
            outputSinglePic($row);
        }
        echo '</ul>';
        //打印页码
        outputPageLink($page,$totalPage,'MyPhotos');
        $pdo = null;
    }catch (PDOException $e) {
        die( $e->getMessage() );
    }
}
function outputSinglePic($row) {
    echo '<li>';
    echo '<div class="myPic">';
    echo '<figure>';
    echo '<div class="PicWrapper">';
    $img = '<img class="normalPic" src="../images/normal/medium/'.$row['PATH'].'">';
    echo constructPicLink($row['ImageID'], $img);
    echo '</div><figcaption><div class="myPicTopic">';
    echo $row['Title'];
    echo '</div><div class="description">';
    echo $row['Description'];
    echo '</div>';
    echo '<input type="button" name="delete" value="DELETE" class="deletePic" alt="'.$row['ImageID'].'">';
    echo '<input type="button" name="modify" value="MODIFY" class="modifyPic" alt="'.$row['ImageID'].'">';
    echo '</figcaption></figure></div></li>';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>我的照片</title>
    <link href="../CSS/reset.css" rel="stylesheet">
    <link href="../CSS/HeaderNavMainFooterPic.css" rel="stylesheet">
    <link href="../CSS/pageNavFooter.css" rel="stylesheet">
    <link href="../CSS/MyPhotos.css" rel="stylesheet">
</head>
<body onload="squareClip()" onresize="squareClip()">
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
    <div class="NoMyPic">
        <p>
            No photo has been uploaded~ Just click the upload button and upload your first photo!
        </p>
    </div>
    <?php outputMyPics();?>
</main>
<footer>
    <a>版权所有,保留一切权利</a><br>
    <a>联系我们 </a><br>
    <a> email:19302010048@fudan.edu.cn</a><br>
    <a>phone number:15516718796</a>
</footer>
<script type="text/javascript" src="../JavaScript/jquery.js"></script>
<script type="text/javascript" src="../JavaScript/ImgClip.js"></script>
<script type="text/javascript" src="../JavaScript/WhenNothing.js"></script>
<script type="text/javascript" src="../JavaScript/ArrangeMyPhoto.js"></script>
</body>
</html>
