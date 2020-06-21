let str = location.href;
function getID(){
    let hasID = false;
    let charList = [];
    for(let i = 0;i < str.length;i++){
        charList[i] = str.substring(i,i+1);
    }
    let index = 0;
    for(let i = 0;i < charList.length-2;i++){
        if(charList[i] === "i"&&charList[i+1]==="d"){
            hasID = true;
            index = i+3;
        }
    }
    if(hasID){
        return str.substring(index);
    }
}

$("#kudos").click(function () {
    location.replace("../src/PictureInformation.php?status=1&id="+getID());

});
$("#collected").click(function () {
    location.replace("../src/PictureInformation.php?status=0&id="+getID());
    location.replace("../src/PictureInformation.php?id="+getID());
});