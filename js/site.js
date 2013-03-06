$(document).ready(function(){
    $("#changeAuth").click(function(){
        var time = Date.parse(new Date());
        $("#authpic").attr('src', '/authpic.php?'+ time);
    });
    $("#login").click(function(){
        var username = $("#username").val();
          var authcode = $("#authcode").val();
        if (username == '') {
            alert("请输入用户名!");
            return false;
        }
        else if (authcode == '') {
            alert("请输入验证码!");
            return false;
        }
        $.post('/site/login.html',{'username':username,'authcode':authcode},function(data){
            if (data == -1) {
                alert('登录码不存在!');
            }
            else if (data == -3) {
                alert('该用户已被锁定!');
            }
            else if (data == -4) {
                alert('验证码不正确!');
            }
            else if (data == 1) {
                window.location.href='/site/login.html';
            }
        });
    });
    $("#logout").click(function(){
        $.post("/site/logout.html",function(){
            window.location.href="/site/login.html";
        });
    })
})