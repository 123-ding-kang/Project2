function squareClip(){
    
    let img = document.getElementsByClassName("normalPic");
    for(let i = 0;i < img.length;i++){
        let width = parseInt(window.getComputedStyle(img[i]).width);
        img[i].style.height = width.toString() +'px';
    }
}