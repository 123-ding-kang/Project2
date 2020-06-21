<?php
function outputPageLink($nowPage,$totalPage,$kind,$query){
    echo '<nav class="pageNumber">';
    echo constructPageLink(1,'<img class="icon" src="../images/icons/到首页.png">',$kind,$query);
    $lastPage = ($nowPage == 1)?1:$nowPage-1;
    echo constructPageLink($lastPage,'<img class="icon" src="../images/icons/上一页.png">',$kind,$query);
    if($nowPage > 2){
        echo constructPageLink($nowPage-2,$nowPage-2,$kind,$query);
    }
    if($nowPage > 1){
        echo constructPageLink($nowPage-1,$nowPage-1,$kind,$query);
    }
    echo constructPageLink($nowPage,'<Strong>'.$nowPage.'</Strong>',$kind,$query);
    if($totalPage > $nowPage){
        echo constructPageLink($nowPage+1,$nowPage+1,$kind,$query);
    }
    if($totalPage > ($nowPage+1)){
        echo constructPageLink($nowPage+2,$nowPage+2,$kind,$query);
    }
    if($nowPage == 1 && $totalPage >= 4){
        echo constructPageLink(4,4,$kind,$query);
    }
    if($nowPage <= 2 && $totalPage >= 5){
        echo constructPageLink(5,5,$kind,$query);
    }
    if($nowPage==$totalPage||$totalPage==0){
        $nextPage = $nowPage;
    }
    else{
        $nextPage = $nowPage+1;
    }
    echo constructPageLink($nextPage,'<img class="icon" src="../images/icons/下一页.png">',$kind,$query);
    if($totalPage == 0){
        echo constructPageLink(1,'<img class="icon" src="../images/icons/到尾页.png">',$kind,$query);
    }
    else{
        echo constructPageLink($totalPage,'<img class="icon" src="../images/icons/到尾页.png">',$kind,$query);
    }
    echo '</nav>';
}
function constructPageLink($href,$content,$kind,$query){
    $link = '<a href="Browser.php?way='.$kind.'&query='.$query.'&page='.$href.'">';
    $link .= $content;
    $link .= '</a>';
    return $link;
}

function outputMulPage($nowPage,$totalPage,$content,$CityCode,$ISO){
    echo '<nav class="pageNumber">';
    echo outputMulPageLink(1,'<img class="icon" src="../images/icons/到首页.png">',$content,$CityCode,$ISO);
    $lastPage = ($nowPage == 1)?1:$nowPage-1;
    echo outputMulPageLink($lastPage,'<img class="icon" src="../images/icons/上一页.png">',$content,$CityCode,$ISO);
    if($nowPage > 2){
        echo outputMulPageLink($nowPage-2,$nowPage-2,$content,$CityCode,$ISO);
    }
    if($nowPage > 1){
        echo outputMulPageLink($nowPage-1,$nowPage-1,$content,$CityCode,$ISO);
    }
    echo outputMulPageLink($nowPage,'<Strong>'.$nowPage.'</Strong>',$content,$CityCode,$ISO);
    if($totalPage > $nowPage){
        echo outputMulPageLink($nowPage+1,$nowPage+1,$content,$CityCode,$ISO);
    }
    if($totalPage > ($nowPage+1)){
        echo outputMulPageLink($nowPage+2,$nowPage+2,$content,$CityCode,$ISO);
    }
    if($nowPage == 1 && $totalPage >= 4){
        echo outputMulPageLink(4,4,$content,$CityCode,$ISO);
    }
    if($nowPage <= 2 && $totalPage >= 5){
        echo outputMulPageLink(5,5,$content,$CityCode,$ISO);
    }
    if($nowPage==$totalPage||$totalPage==0){
        $nextPage = $nowPage;
    }
    else{
        $nextPage = $nowPage+1;
    }
    echo outputMulPageLink($nextPage,'<img class="icon" src="../images/icons/下一页.png">',$content,$CityCode,$ISO);
    if($totalPage == 0){
        echo outputMulPageLink(1,'<img class="icon" src="../images/icons/到尾页.png">',$content,$CityCode,$ISO);
    }
    else{
        echo outputMulPageLink($totalPage,'<img class="icon" src="../images/icons/到尾页.png">',$content,$CityCode,$ISO);
    }
    echo '</nav>';
}
function outputMulPageLink($href,$label,$content,$CityCode,$ISO){
    $link = '<a href="Browser.php?Content='.$content.'&CouRegs='.$ISO.'&Cities='.$CityCode.'&page='.$href.'">';
    $link .= $label;
    $link .= '</a>';
    return $link;
}
?>