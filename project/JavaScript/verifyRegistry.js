$(":submit").click(function checkAndAlert() {
    let pass1 = document.getElementsByName("password")[0];
    let pass2 = document.getElementsByName("password2")[0];
    let email = document.getElementsByName("email")[0];
    let username = document.getElementsByName("username")[0];
    let usernamePattern = /^[a-zA-Z0-9_]{4,16}$/g;
    let emailPattern = /^[A-Za-z\d]+([-_.][A-Za-z\d]+)*@([A-Za-z\d]+[-.])+[A-Za-z\d]{2,4}$/g;
    let passwordPattern =/^(?=.{7,})(((?=.*[A-Z])(?=.*[a-z]))|((?=.*[A-Z])(?=.*[0-9]))|((?=.*[a-z])(?=.*[0-9]))).*$/g;
    if(!usernamePattern.test(username.value)){
        username.setCustomValidity("非法用户名");
    }
    else{
        username.setCustomValidity("");
    }
    if(!emailPattern.test(email.value)){
        email.setCustomValidity("邮箱无效！");
    }
    else{
        email.setCustomValidity("");
    }
    if(!passwordPattern.test(pass1.value)){
        pass1.setCustomValidity("密码太弱啦~请输入7位及以上并至少包含数字、大小写字母中的两种");
    }
    else{
        pass1.setCustomValidity("");
    }
    if(pass1.value !== pass2.value)
        pass2.setCustomValidity("两次输入的密码不匹配");
    else{
        pass2.setCustomValidity("");
    }
});