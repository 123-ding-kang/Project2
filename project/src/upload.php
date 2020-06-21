<?php
session_start();
require_once('../config.php');
include_once('outputNavLink.php');
include_once('outputCouReg.php');
if(!isset($_SESSION['UID'])){
    echo '<script type="text/javascript">location.replace("../index.php")</script>';
}
if(isset($_GET['ImageID'])){
    try {
        $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = 'SELECT travelimage.ImageID, Title, Description,Content,PATH,geocountries_regions.Country_RegionName,travelimage.Country_RegionCodeISO,CityCode from travelimage NATURAL JOIN traveluser JOIN geocountries_regions ON geocountries_regions.ISO= travelimage.Country_RegionCodeISO WHERE travelimage.ImageId =:id';
        $statement = $pdo->prepare($sql);
        $statement->bindValue(':id',$_GET['ImageID']);
        $statement->execute();
        $row = $statement->fetch();
        $pdo = null;
    }catch (PDOException $e) {
        die( $e->getMessage() );
    }
}
function outputButton(){
    if(isset($_GET['ImageID'])){
        echo '<input type="submit" value="MODIFY" name="UploadSubmit">';
    }
    else echo '<input type="submit" value="SUBMIT" name="UploadSubmit">';
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>上传界面</title>
    <link href="../CSS/reset.css" rel="stylesheet">
    <link href="../CSS/HeaderNavMainFooterPic.css" rel="stylesheet">
    <link href="../CSS/upload.css" rel="stylesheet">
</head>
<body>
<header>
    <nav>
        <ul id="navPublic">
            <li><a href="../index.php">Home</a></li>
            <li><a href="Browser.php">Browser</a></li>
            <li><a href="Search.php">Search</a></li>
            <?php LoginOrOut('upload');?>
        </ul>
    </nav>
</header>
<main>
    <?php
    if(isset($_GET['ImageID'])){
        echo '<form id="formMod" action="modify.php" method="post" role="form" enctype="multipart/form-data">';
    }else{
        echo '<form id="formUP" action="add.php" method="post" role="form" enctype="multipart/form-data">';
    }
    ?>
        <fieldset>
            <?php
            if(isset($_GET['ImageID'])){
                echo '<input type="hidden" name="ImageID" value="'.$_GET['ImageID'].'">';
            }
            ?>
            <legend>Upload New Picture</legend>
            <div class="uploadPic">
                <?php
                if(isset($_GET['ImageID'])){
                    echo '<img id="PicFromUser" src="../images/normal/medium/'.$row['PATH'].'"/>';
                }else{
                    echo '<img id="PicFromUser" src=""/><p id="placeholder">No Picture Uploaded</p>';
                }
                ?>
            </div>
            <div class="wrap">
                <span>UPLOAD</span>
                <?php
                if(isset($_GET['ImageID'])){
                    echo '<input type="file" name="photoUpload" id="file" required>';
                } else{
                   echo '<input type="file" name="photoUpload" id="file" required>';
                }
                ?>
            </div>
            <label id="uploadPicTitle">Picture Title:
                <?php
                if(isset($_GET['ImageID'])){
                    echo '<input type="text" name="UploadPhotoTitle" value="'.$row['Title'].'" required>';
                }else{
                    echo '<input type="text" name="UploadPhotoTitle" required>';
                }
                ?>
                </label>
            <label id ="uploadPicContent">Content:
                <select name="Content" required>
                    <?php
                    if(isset($_GET['ImageID'])){
                        $contentARR = array('default','scenery','city','people','animal','building','wonder','other');
                        for($i = 1;$i < 8;$i++){
                            if($row['Content'] == $contentARR[$i]){
                                echo '<option value="'.$contentARR[$i].'" selected>'.$contentARR[$i].'</option>';
                            }else{
                                echo '<option value="'.$contentARR[$i].'">'.$contentARR[$i].'</option>';
                            }
                        }
                    }
                    else{
                        echo '<option value="scenery">Scenery</option>
                    <option value="city">City</option>
                    <option value="people">People</option>
                    <option value="animal">Animal</option>
                    <option value="building">Building</option>
                    <option value="wonder">Wonder</option>
                    <option value="other">Other</option>';
                    }
                    ?>
                </select>
            </label>
            <label id="uploadDep">Description:
                <?php
                if(isset($_GET['ImageID'])){
                    echo '<textarea name="UploadPhotoDescription">'.$row['Description'].'</textarea>';
                }else{
                    echo '<textarea name="UploadPhotoDescription"></textarea>';
                }
                ?>
            </label>
            <label id="uploadCountry">Country:
                <?php
                    if(isset($_GET['ImageID'])){
                        $mode = $row['CityCode'];
                        echo '<select name="Countries" onchange="setCity(this,this.form.Cities)" id="Countries" required class="a'.$row['CityCode'].'">';
                        outputCouRegMod($row);
                    }else{
                        $mode = 'up';
                        echo '<select name="Countries" onchange="setCity(this,this.form.Cities)" id="Countries" required class="up">';
                        outputCouRegUP();
                    }
                ?>
                </select>
            </label>
            <label id="uploadCity">City:
                <select name="Cities" id="Cities">
                    <option value="default" selected>-Cities-</option>
                </select>
            </label>
            <?php outputButton();?>
        </fieldset>
    </form>
</main>
<footer>
    <div class="information">YoYa Zhang版权所有 &copy 2019-2020</div>
    <div class="easterEgg">沪公网安备19302010074号</div>
    <address>Email: <a href = "http://mail.fudan.edu.cn/">19302010074@fudan.edu.cn</a></address>
</footer>
<script type="text/javascript" src="../jquery-3.3.1.min.js"></script>
<script type="text/javascript" src="../JavaScript/upload.js"></script>
<script type="text/javascript" src="../JavaScript/TwoLevel.js"></script>

</body>
</html>