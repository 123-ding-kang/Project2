<?php
require_once('../config.php');
include_once('outputNavLink.php');
include_once('outputPage.php');
try {
    $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //搜索条件限制
    $keywords = $_POST['realQuery'];
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
    $sql = "select COUNT(*) AS amount from travelimage WHERE :method like '%".$keywords."%'";
    $result = $pdo->prepare($sql);
    $result->bindValue(':method',$searchMethod);
    $row = $result->fetch();
    $amount = $row['amount'];
    if( $amount ){
        if( $amount < $PageSize ){
            $totalPage = 1;
        }               //如果总数据量小于$PageSize，那么只有一页
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
    if($keywords == ''){
        $sql = 'select * from travelimage  order by rand() LIMIT '.$startNum.',6';
    }else{
        $sql = "select ImageId,Title, Description,PATH from travelimage WHERE ".$searchMethod." like '%".$keywords."%' LIMIT ".$startNum.",6";
    }

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