$(document).ready(function(){
    $("#login").click(function(){
        var username = $("#username").val();
        var password = $("#password").val();
        var authcode = $("#authcode").val();
        if (username == '') {
            alert("请输入用户名!");
            return false;
        }
        else if (password == '') {
            alert("请输入密码!");
            return false;
        }
        else if (authcode == '') {
            alert("请输入验证码!");
            return false;
        }
        $.post('/busi/login.html',{'username':username,'password':password,'authcode':authcode},function(data){
            if (data == -1) {
                alert('用户名不存在!');
            }
            else if (data == -2) {
                alert('密码不正确!');
            }
            else if (data == -3) {
                alert('用户暂未通过审核!');
            }
            else if (data == -4) {
                alert('验证码不正确!');
            }
            else if (data == 1) {
                window.location.href='/busi/index.html';
            }
        });
    });
    $("#logout").click(function(){
        $.post('/site/logout.html',function(){
            window.location.href="/busi/index.html";
        });
    });
    $("#changeAuth").click(function(){
        var time = Date.parse(new Date());
        $("#authpic").attr('src', '/authpic.php?'+ time);
    });
   
    $("#getcode").click(function(){
        $.post('/busi/index.html',{'do':'getcode'},function(data){
            if (data != -1) {
                window.location.href = '/busi/index.html';
            }
        });
    });

    //apply
    $("#apply").click(function(){
        var data = {};
        data.company = $("#companyname").val();
        data.contact = $("#contact").val();
        data.mobile = $("#mobile").val();
        data.auth = $("#auth").val();
        data.phone = $("#phone").val();
        data.email = $("#email").val();
        data.addr = $("#addr").val();
        data.lecense = $("#license").val();
        data.code = $("#code").val();
        data.capitallevel  = $("#capitallevel").val();
        data.licensepic = $("#liName a").attr("href");
        data.orgpic = $("#orgName a").attr("href");
        $.post("/busi/register.html",data,function(data){
            alert("注册成功!");
        });
    });

    $("#upLi").click(function(){
        upload.document.frm.uploadFile.click();
    });
    $("#upOrg").click(function(){
        org.document.frm.uploadFile.click();
    });
//----query history--------
    $("#search").live("click",function(data){
        var start = $("#starttime").val();
        var end = $("#endtime").val();
        $.post("/busi/query.html",{'do':'search','start':start,'end':end},function(data){
            if (data == -11)window.location.href="/busi/index.html";
            $("#list").html(data);
        });
    });
    $(".plist").live("click",function(data){
        var code = $(this).attr("code");
        $.post("/busi/query.html",{'do':'plist','buycode':code},function(data){
            if (data == -11)window.location.href="/busi/index.html";
            $("#list").html(data);
        });
    });
//----end query history -----
    $("#save").click(function(){
        var data = {};
        data.contact = $("#contact").val();
        data.mobile = $("#cellphone").val();
        data.phone = $("#tel").val();
        data.email = $("#email").val();
        data.addr = $("#addr").val();
        data.oldpwd = $("#oldpwd").val();
        data.newpwd = $("#newpwd").val();
        data.confirmpwd = $("#confirmpwd").val();
        if (data.oldpwd != "" || data.newpwd != "" || data.confirmpwd != "") {
            if(data.oldpwd == "") {
                alert("请输入原始密码!");return false;
            }
            else if (data.newpwd == "") {
                alert("请输入新密码!");return false;
            }
            else if (data.confirmpwd == "") {
                alert("请输入确认密码!");return false;
            }
            else if (data.newpwd != data.confirmpwd) {
                alert("两次输入的密码不一致!");return false;
            }
        }
        
        $.post("/busi/profile.html",data,function(data){
            if (data == -1) {
                alert("请输入原始密码!");return false;
            }
            else if (data == -2) {
                alert("请输入新密码!");return false;
            }
            else if (data == -3) {
                alert("请输入确认密码!");return false;
            }
            else if (data == -4) {
                alert("两次输入的密码不一致!");return false;
            }
            else if (data == -5) {
                alert("输入的原始密码不正确!");return false;
            }
            else {
                alert("保存成功!");
                window.location.href="/busi/profile.html";
                return false;
            }
        });
    });
});