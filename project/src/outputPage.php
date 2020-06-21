<?php
require_once('../config.php');
function outputPageLink($nowPage,$totalPage,$kind){
    echo '<nav class="pageNumber">';
    echo constructPageLink(1,'<img class="icon" src="../images/icons/到首页.png">',$kind);
    $lastPage = ($nowPage == 1)?1:$nowPage-1;
    echo constructPageLink($lastPage,'<img class="icon" src="../images/icons/上一页.png">',$kind);
    if($nowPage > 2){
        echo constructPageLink($nowPage-2,$nowPage-2,$kind);
    }
    if($nowPage > 1){
        echo constructPageLink($nowPage-1,$nowPage-1,$kind);
    }
    echo constructPageLink($nowPage,'<Strong>'.$nowPage.'</Strong>',$kind);
    if($totalPage > $nowPage){
        echo constructPageLink($nowPage+1,$nowPage+1,$kind);
    }
    if($totalPage > ($nowPage+1)){
        echo constructPageLink($nowPage+2,$nowPage+2,$kind);
    }
    if($nowPage == 1 && $totalPage >= 4){
        echo constructPageLink(4,4,$kind);
    }
    if($nowPage <= 2 && $totalPage >= 5){
        echo constructPageLink(5,5,$kind);
    }
    if($nowPage==$totalPage||$totalPage==0){
        $nextPage = $nowPage;
    }
    else{
        $nextPage = $nowPage+1;
    }
    echo constructPageLink($nextPage,'<img class="icon" src="../images/icons/下一页.png">',$kind);
    if($totalPage == 0){
        echo constructPageLink(1,'<img class="icon" src="../images/icons/到尾页.png">',$kind);
    }
    else{
        echo constructPageLink($totalPage,'<img class="icon" src="../images/icons/到尾页.png">',$kind);
    }
    echo '</nav>';
}
function constructPageLink($href,$content,$kind){
    $link = '<a href="'.$kind.'.php?uid='.$_SESSION['UID'].'&page='.$href.'">';
    $link .= $content;
    $link .= '</a>';
    return $link;
}
?>
