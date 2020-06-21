<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>登录页</title>
    <link href="../CSS/reset.css" rel="stylesheet">
    <link href ="../CSS/LogIn.css" rel="stylesheet">
</head>
<body>
<header>
    <h1 class = "title"></h1>
    <div class = "slogan"></div>
</header>
<?php
session_start();
require_once('../phpass/PasswordHash.php');

$row = null;
function validLogin(){
    global $row;
    $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);
    $hasher = new PasswordHash(8, false);

//very simple (and insecure) check of valid credentials.
    $sql = 'SELECT * FROM traveluser WHERE UserName = :user';
    $statement = $pdo->prepare($sql);
    $statement->bindValue(':user',$_POST['username']);
    $statement->execute();
    $row = $statement->fetch();
    if($row){
        if($hasher->CheckPassword($_POST['password'], $row['Pass'])){
            return true;
        }else{
            return false;
        }
    }else{
        return false;
    }

}
function getLoginForm(){
    return '<form action="" method="post" role="form">
        <fieldset>
            <legend>Login Now</legend>
            <div class="user">
                <label><input type="text" name="username" required placeholder="User Name"></label>
            </div>
            <div class="password">
                <label><input type="password" name="password" required placeholder="Password"></label>
            </div>
            <input type="submit" name="login" value="LOG IN">
        </fieldset>
    </form>
    <div class="registerIntro">
        <a href="register.php">Join us if you don\'t have an account right now！</a>
    </div>';
}
?>
<?php
require_once('../config.php');
if(isset($_SESSION['Username'])){
    echo "<script rel='script'> window.location.href='../index.php';</script>";
}elseif($_SERVER["REQUEST_METHOD"] == "POST"){
    if(validLogin()){
        global $row;
        // add 1 day to the current time for expiry time
        $expiryTime = time()+60*60*24;
        $_SESSION['Username']=$_POST['username'];
        $_SESSION['UID'] = $row['UID'];
        $_SESSION['TimeOut']= $expiryTime;
        echo "<script rel='script'> window.location.href='../index.php';</script>";
    }
    else{
        echo "<script rel='script'> alert('wrong username or password!');</script>";
    }
}
?>
<main>
    <?php
    if (!isset($_SESSION['Username'])){
        echo getLoginForm();
    }
    else {
        echo "<script rel='script'> window.location.href='../index.php';</script>";
    }
    ?>
</main>
<footer>
    <a> email:19302010048@fudan.edu.cn</a><br>
    <a>phone number:15516718796</a>
</footer>
</body>
</html>
