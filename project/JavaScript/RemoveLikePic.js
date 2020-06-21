$(".removeFavor").click(function () {
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
    xmlhttp.open("POST","../src/ArrangeFavorPics.php",true);
    xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
    xmlhttp.send("id="+id);
});