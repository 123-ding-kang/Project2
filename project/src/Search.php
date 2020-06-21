<?php
session_start();
require_once('../config.php');
include_once('outputNavLink.php');
include_once('outputPage.php');
function outputSearchPics() {
    try{
        $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        //搜索条件限制
        $keywords = $_POST['realQuery'];
        if($keywords == ''){
            echo '<script type="text/javascript">alert("I cannot help you find photos without keywords! QAQ");</script>';
            return;
        }
        if($_POST['SearchMethod'] == "SearchByTitle"){
            $searchMethod = "Title";
        }
        elseif($_POST['SearchMethod'] == "SearchByDescription"){
            $searchMethod = "Description";
        }

        //页码设置
        if(isset($_GET['page']) ){
            $page = intval( $_GET['page'] );
        }
        else{
            $page = 1;
        }
        $PageSize = 6;
        $sql = "select COUNT(*) AS amount from travelimage WHERE ".$searchMethod." like '%".$keywords."%'";
        $result = $pdo->query($sql);

        $row = $result->fetch();

        $amount = $row['amount'];
        if($amount == 0){
            echo '<script type="text/javascript">alert("Oops! Nothing is found,change some keywords and try again");</script>';
        }
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

        $sql = "select ImageID,Title, Description,PATH from travelimage WHERE ".$searchMethod." like '%".$keywords."%' LIMIT ".$startNum.",6";

        $result = $pdo->query($sql);

        echo '<ul id="SearchList">';
        while($row = $result->fetch()){
            if($row['Description'] == null){
                $row['Description'] = "The author is so lazy that he/she doesn't give any detailed description about this Photo.TUT";
            }
            outputSinglePic($row);
        }
        outputPageLink($page,$totalPage,'Search');
        echo '</ul>';
        $pdo = null;
    }catch (PDOException $e) {
        die( $e->getMessage() );
    }
}
function outputSinglePic($row) {
    echo '<li>';
    echo '<div class="SearchPic">';
    echo '<figure>';
    echo '<div class="PicWrapper">';
    $img = '<img class="normalPic" src="../images/normal/medium/'.$row['PATH'].'">';
    echo constructPicLink($row['ImageID'], $img);
    echo '</div><figcaption><div class="SearchPicTopic">';
    echo $row['Title'];
    echo '</div><div class="description">';
    echo $row['Description'];
    echo '</div></figcaption>';
    echo '</figure></div></li>';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>搜索页</title>
    <link href="../CSS/reset.css" rel="stylesheet">
    <link href="../CSS/HeaderNavMainFooterPic.css" rel="stylesheet">
    <link href="../CSS/pageNavFooter.css" rel="stylesheet">
    <link href="../CSS/Search.css" rel="stylesheet">
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
<section id="SearchArea">
    <form action="Search.php" method="post" role="form" id="SearchForm">
        <fieldset>
            <div class="writingArea">
                <label class="TitleSearch">
                    <input type="radio" name="SearchMethod" value="SearchByTitle" id="TitleSearch" checked>
                    Search by Title:<input type="text" name="Title" autofocus>
                </label>
                <label class="DescriptionSearch">
                    <input type="radio" name="SearchMethod" value="SearchByDescription" id="DescriptionSearch">
                    Search by Description:<textarea name="Description"></textarea>
                </label>
            </div>
            <input type="hidden" name="realQuery" value="" id="realQuery">
            <input type="button" name="SearchSubmit" value="SEARCH">
        </fieldset>
    </form>
</section>
<main>
    <?php
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        outputSearchPics();
    }
    ?>
</main>
<footer>
    <div class="Information">
        <a> email:19302010048@fudan.edu.cn</a><br>
        <a>phone number:15516718796</a>
    </div>
</footer>
<script type="text/javascript" src="../JavaScript/jquery.js"></script>
<script type="text/javascript" src="../JavaScript/ImgClip.js"></script>
<script type="text/javascript" src="../JavaScript/Search.js"></script>
</body>
</html>
