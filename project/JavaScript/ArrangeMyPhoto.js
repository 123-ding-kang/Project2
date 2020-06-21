$(".deletePic").click(function () {
    if(!window.confirm("您确定要删除照片吗？")){
        return;
    }
    let lastURL = window.location.href;
    let id = $(this).attr("alt");
    let xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange=function()
    {
        if (xmlhttp.readyState===4 && xmlhttp.status===200)
        {
            location.replace(lastURL);
        }
    }
    xmlhttp.open("POST","../src/DeleteMyPics.php",true);
    xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
    xmlhttp.send("id="+id);
});

$(".modifyPic").click(function () {
    let id = $(this).attr("alt");
    location.replace("../src/upload.php?ImageID="+id);
});
