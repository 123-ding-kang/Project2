<?php
session_start();
require_once('config.php');
function outputHomePics() {
    try {
        $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = 'SELECT Title, Description,PATH,travelimage.ImageID, COUNT(travelimagefavor.ImageID) AS instnum FROM travelimage LEFT JOIN travelimagefavor ON travelimage.ImageID =travelimagefavor.ImageID GROUP BY travelimage.ImageID ORDER BY instnum DESC LIMIT 0,9';
        $result = $pdo->query($sql);
        for($i = 0;$i < 3; $i++){
            echo '<tr>';
            for($j = 0;$j < 3;$j++){
                $row = $result->fetch();
                if($row['Description'] == null){
                    $row['Description'] = "The author is so lazy that he/she doesn't give any detailed description about this Photo.TUT";
                }
                outputSinglePic($row);
            }
            echo '</tr>';
        }

        $pdo = null;
    }catch (PDOException $e) {
        die( $e->getMessage() );
    }
}
function outputRandomPics(){
    try{
        $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = 'select * from travelimage  order by rand() limit 9';
        $result = $pdo->query($sql);

        for($i = 0;$i < 3; $i++){
            echo '<tr>';
            for($j = 0;$j < 3;$j++){
                $row = $result->fetch();
                if($row['Description'] == null){
                    $row['Description'] = "The author is so lazy that he/she doesn't give any detailed description about this Photo.TUT";
                }
                outputSinglePic($row);
            }
            echo '</tr>';
        }
        $pdo = null;
    }catch (PDOException $e) {
        die( $e->getMessage() );
    }
}
function outputSinglePic($row) {
    echo '<td>';
    echo '<figure>';
    $img = '<img class="normalPic" src="images/normal/medium/'.$row['PATH'].'">';
    echo constructPicLink($row['ImageID'], $img);
    echo '<figcaption>';
    echo '<h3>'.$row['Title'].'</h3>';
    echo '<div class="details">'.$row['Description'].'</div>';
    echo '</figcaption></figure></td>';
}

/*
  Constructs a link given the genre id and a label (which could
  be a name or even an image tag
*/
function constructPicLink($id, $label) {
    $link = '<a href="src/PictureInformation.php?id='.$id.'">';
    $link .= $label;
    $link .= '</a>';
    return $link;
}

function LoginOrOut(){
    if(isset($_SESSION['Username'])){
        echo '<li id="theDoor"><h2 id="myZone">MyZone</h2>';
        echo '<ul id="navPersonal">';
        echo '<li><a href="src/upload.php?id='.$_SESSION['UID'].'">Upload</a></li>';
        echo '<li><a href="src/MyPhotos.php?id='.$_SESSION['UID'].'">MyPhotos</a></li>';
        echo '<li><a href="src/Favorites.php?id='.$_SESSION['UID'].'">MyFavorites</a></li>';
        echo '<li><a href="src/logout.php">LogOut</a></li>';
        echo '</ul>';
        echo '</li>';
    }
    else{
        echo '<li><a href="src/login.php">LogIn</a></li>';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>PhotoLife</title>
    <script type="text/javascript" src="jquery-3.3.1.min.js"></script>
    <script src="bootstrap-3.3.7-dist/js/bootstrap.min.js"></script>

    <link href="bootstrap-3.3.7-dist/css/bootstrap.css" rel="stylesheet">
    <link href="CSS/reset.css" rel="stylesheet">
    <link href="CSS/HeaderNavMainFooterPic.css" rel="stylesheet">
    <link href="CSS/Home.css" rel="stylesheet">

</head>
<body onload="squareClip()" onresize="squareClip()">
<header>
    <nav>
        <ul id="navPublic">
            <li><a href="index.php" id="currentPage">Home</a></li>
            <li><a href="src/Browser.php">Browser</a></li>
            <li><a href="src/Search.php">Search</a></li>
            <?php LoginOrOut();?>
        </ul>
    </nav>
</header>
<main>

        <div class="container">
            <div id="myCarousel" class="carousel slide">
                <ol class="carousel-indicators">
                    <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                    <li data-target="#myCarousel" data-slide-to="1"></li>
                    <li data-target="#myCarousel" data-slide-to="2"></li>
                </ol>
                <div class="carousel-inner">
                    <div class="item active">
                        <a href="src/PictureInformation.php?id=41"><img src="images/normal/medium/222222.jpg" alt="First slide"></a>
                        <div class="carousel-caption">
                            <a href="src/PictureInformation.php?id=41"></a>
                        </div>
                    </div>
                    <div class="item">
                        <a href="src/PictureInformation.php?id=49"><img src="images/normal/medium/6592317633.jpg" alt="Second slide"></a>
                        <div class="carousel-caption">
                            <a href="src/PictureInformation.php?id=49"> </a></div>
                    </div>
                    <div class="item">
                        <a href="src/PictureInformation.php?id=7"><img src="images/normal/medium/6115548152.jpg" alt="Third slide"></a>
                        <div class="carousel-caption">
                            <a href="src/PictureInformation.php?id=7"> </a>
                        </div>
                    </div>
                </div>
                <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
                    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
                    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>
        </div>

    <section class="homePics">
        <table>
            <?php
            if(isset($_GET['mode']) && $_GET['mode']==1){
                outputRandomPics();
            }
            else{
                outputHomePics();
            }
            ?>
        </table>
    </section>
    <aside>
        <button id="toTop"><a href="#navPublic"><img src="images/icons/向上.png"></a></button>
        <button id="refresh"><img src="images/icons/刷新.png"></button>
    </aside>
</main>
<footer>
    <a>版权所有,保留一切权利</a><br>
    <a>联系我们 </a><br>
    <a> email:19302010048@fudan.edu.cn</a><br>
    <a>phone number:15516718796</a>
</footer>
<script type="text/javascript" src="JavaScript/ImgClip.js"></script>

<script type="text/javascript" src="JavaScript/GetRandomPic.js"></script>
</body>
</html>
