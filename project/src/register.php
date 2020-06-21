<?php
session_start();
require_once('../config.php');
require_once('../phpass/PasswordHash.php');

function validRegister(){
    $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = 'SELECT UserName FROM traveluser';
    $result = $pdo->query($sql);
    while($row = $result->fetch()){
        if($row['UserName'] == $_POST['username']){
            echo '<script type="text/javascript">
              alert("已经有人用过这个名字啦，换一个吧~");
              </script>';
            return false;
        }
    }
    $pdo = null;
    return true;
}

function addUser(){
    $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //count number of users
    $sqlNumber = 'SELECT COUNT(*) AS UserNum FROM traveluser';
    $result = $pdo->query($sqlNumber);
    $numRes = $result->fetch();
    $hasher = new PasswordHash(8, false);
    $hashedPassword = $hasher->HashPassword($_POST['password']);

    $sqlAddUser = "INSERT INTO traveluser VALUES (:UID,:email,:username,:pass,'1',:dateJoined,:lastModified)";
    $stmAddUser = $pdo->prepare($sqlAddUser);
    $stmAddUser->bindValue(':UID',$numRes['UserNum']+1);
    $stmAddUser->bindValue(':username',$_POST['username']);
    $stmAddUser->bindValue(':pass',$hashedPassword);
    $stmAddUser->bindValue(':email',$_POST['email']);
    $presentDate = date("Y-m-d H:i:s");
    $lastModifiedDate = date("Y-m-d H:i:s");
    $stmAddUser->bindValue(':dateJoined',$presentDate);
    $stmAddUser->bindValue(':lastModified',$lastModifiedDate);
    $stmAddUser->execute();

    $pdo = null;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>注册界面</title>
    <link href="../CSS/reset.css" rel="stylesheet">
    <link href="../CSS/Registry.css" rel="stylesheet">
</head>
<body>
<header>
    <h1></h1>
    <div class = "slogan"></div>
</header>
<?php
if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(validRegister()){
        addUser();
        echo '<script type="text/javascript">
              alert("注册成功！");
              window.location.href="login.php";
              </script>';
    }
}
?>
<main>
    <h2>Register now</h2>
    <form method="post" action="" role="form">
        <fieldset>
            <legend>REGISTER</legend>
            <div class="user">
                <label>
                    USER:
                    <input type="text" name="username" required>
                </label>
            </div>
            <div class="email">
                <label>
                    EMAIL:
                    <input type="email" name="email" required>
                </label>
            </div>
            <div class="password">
                <label>
                    PASSWORD:
                    <input type="password" name="password" required>
                </label><br>
            </div>
            <div class="password">
                <label>
                    PASSWORD DOUBLE-CHECK:
                    <input type="password" name="password2" required>
                </label>
            </div>
            <input type="submit" name="registrySubmit" value="SUBMIT">
        </fieldset>
    </form>
</main>
<footer>
    <a> email:19302010048@fudan.edu.cn</a><br>
    <a>phone number:15516718796</a>
</footer>
<script type="text/javascript" src="../JavaScript/jquery.js"></script>
<script type="text/javascript" src="../JavaScript/verifyRegistry.js"></script>
</body>
</html>
