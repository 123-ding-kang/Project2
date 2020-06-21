<?php
require_once('../config.php');

function LoginOrOut($nowPage){
    if(isset($_SESSION['Username'])){
        echo '<li id="theDoor"><h2 id="myZone">MyZone</h2>';
        echo '<ul id="navPersonal">';
        if($nowPage == 'upload'){
            echo '<li><a href="upload.php?uid='.$_SESSION['UID'].'" id="currentPage">Upload</a></li>';
        }
        else{
            echo '<li><a href="upload.php?uid='.$_SESSION['UID'].'">Upload</a></li>';
        }
        if($nowPage == 'MyPhotos'){
            echo '<li><a href="MyPhotos.php?uid='.$_SESSION['UID'].'" id="currentPage">MyPhotos</a></li>';
        }
        else{
            echo '<li><a href="MyPhotos.php?uid='.$_SESSION['UID'].'">MyPhotos</a></li>';
        }
        if($nowPage == 'Favorites'){
            echo '<li><a href="Favorites.php?uid='.$_SESSION['UID'].'" id="currentPage">MyFavorites</a></li>';
        }
        else{
            echo '<li><a href="Favorites.php?uid='.$_SESSION['UID'].'">MyFavorites</a></li>';
        }
        echo '<li><a href="logout.php">LogOut</a></li>';
        echo '</ul>';
        echo '</li>';
    }
    else{
        echo '<li><a href="login.php">LogIn</a></li>';
    }
}
function constructPicLink($id, $label) {
    $link = '<a href="../src/PictureInformation.php?id='.$id.'">';
    $link .= $label;
    $link .= '</a>';
    return $link;
}
?>
