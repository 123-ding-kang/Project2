$(document).ready((function(){
    let myPics = document.getElementsByClassName('myPic');
    let favorPics = document.getElementsByClassName('favoritePic');
    if(myPics.length !== 0){
        $(".NoMyPic").remove();
    }
    if(favorPics.length !== 0){
        $(".NoFavorPic").remove();
    }
}));

