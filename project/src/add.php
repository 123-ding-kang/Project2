<?php
require_once('../config.php');
session_start();
try {
    $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $file = $_FILES['photoUpload'];
    $name = $file['name'];
    $allow_type = array("jpg", "gif", "png", "bmp");//允许选择的图片类型
    $type = strtolower(substr($name,strrpos($name,'.')+1));
    if(!in_array($type, $allow_type)){
        //如果不被允许，则直接停止程序运行
        return ;
    }
        //判断是否是通过HTTP POST上传的
    if(!is_uploaded_file($file['tmp_name'])){
        //如果不是通过HTTP POST上传的
        return ;
    }
    $upload_path = '../images/normal/medium/'; //上传文件的存放路径
    do{
        $new_name = get_file_name(10).'.'.$type;
        $path='../images/normal/medium/'.$new_name;//upload为目标文件夹
    }while (file_exists($path));//检查图片是否存在文件夹，存在返回ture,否则false
    $temp_file = $file['tmp_name'];//获取服务器里图片
    $sqlNumber = 'SELECT MAX(ImageID) AS lastDigit FROM travelimage';
    $statement = $pdo->query($sqlNumber);
    $numRes = $statement->fetch();

    $sql = "INSERT INTO travelimage (ImageID,Title, Description, CityCode,Country_RegionCodeISO,UID,PATH,Content) VALUES (:ImageID,:title,:description,:citycode,:iso,:uid,:path,:content)";
    $result = $pdo->prepare($sql);
    $result->bindValue(':ImageID',$numRes['lastDigit']+1);
    $result->bindValue(':title',$_POST['UploadPhotoTitle']);
    $result->bindValue(':description',$_POST['UploadPhotoDescription']);
    if($_POST['Cities'] == 'default'){
        $result->bindValue(':citycode',null);
    }else{
        $result->bindValue(':citycode',$_POST['Cities']);
    }

    $result->bindValue(':iso',$_POST['Countries']);
    $result->bindValue(':uid',$_SESSION['UID']);
    $result->bindValue(':path',$new_name);
    $result->bindValue(':content',$_POST['Content']);

    $result->execute();
    if ($result){
        move_uploaded_file($temp_file,$path);//移动临时文件到目标路径
        $arr['flag']=1;
    }
    echo '<script>location.replace("MyPhotos.php");</script>';
    $pdo = null;
}catch (PDOException $e) {
    die( $e->getMessage() );
}

function get_file_name($len)//获取一串随机数字，用于做上传到数据库中文件的名字
{
    $new_file_name = '';
    $chars = "1234567890";//随机生成图片名
    for ($i = 0; $i < $len; $i++) {
        $new_file_name .= $chars[mt_rand(0, strlen($chars) - 1)];
    }
    return $new_file_name;
}
?>