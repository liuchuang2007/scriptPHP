$(document).ready(function(){
    $("#use").click(function(){
        var code = $("#code").val();
        if (code == '') {
            alert('采购代码不能为空!');
            return false;
        }
        $.post("/store/authCode.html",{'code':code},function(data){
            if (data == -11) window.location.href="/store/index.html";
            if (data == -1) {
                alert("请输入有效的采购代码!");
            }
            else if (data == -2) {
                alert("该采购代码已过期!");
            }
            else if (data == -3) {
                alert("该采购代码已使用,请输入有效的采购代码!");
            }
            else {
               var obj = $.parseJSON(data);
               $("#code").hide();
               $("#codetxt").html(code);
               $("#codetxt").show();
               $("#company").html(obj.company);
               $("#acname").html(obj.acname);
               $("#pid").attr("disabled",false);
               $("#num").attr("disabled",false);
            }
        });
        return false;
    });
    $("#cuse").click(function(){
        $("#code").show();
        $("#codetxt").hide();
        $("#codetxt"),html("");
        $("#company").html("");
        $("#acname").html("");
    });
    $("#pid").blur(function(){
        $.post("/store/getProduct.html",{'pcode':this.value},function(data){
            if (data == -11)window.location.href="/store/index.html";
            else if (data == -1) {
                alert("请输入有效的商品编号!");
                return false;
            }
            else {
                var obj = $.parseJSON(data);
                $("#pname").html(obj.pname);
                $("#price").val(obj.price);
            }
        });
    });
    $("#num").blur(function(){
        if (this.value == "") {
            alert("请输入数量");
            return false;
        }
        else if(isNaN(this.value)) {
            alert("请输入数字");
            return false;
        }
        else {
            var price = $("#price").val();
            var total =  price * this.value;
            $("#total").html(total + '');
        }
    });
    $("#add").click(function(){
        var pid = $("#pid").val();
        var pname = $("#pname").html();
        var price = $("#price").val();
        var count = $("#num").val();
        var total = $("#total").html();
        var bill = $("#pbill").val();
        var billmoney = $("#billmoney").val();
        //if buycode is valid.
        var code = $("#codetxt").html();
        if (code == "") {
            alert("请输入有效的采购代码!");
            return false;
        }
        //if product added yet.
        var objs = $(".lcontent .row .proid");
        for (var i=0;i<objs.length;i++) {
            if (objs[i].innerHTML == pid) {
                alert("该商品已在列表中!");
                return false;
            }
        }
        var str = '<ul class="row"><li class="proid">' + pid + '</li>' +
            '<li class="proname">' + pname + '</li>' +
            '<li class="procount">' + count + '</li>' +
            '<li class="probillmoney">' + billmoney + '</li>' +
            '<li class="proprice">' + price + '</li>' +
            '<li class="prototal">' + total + '</li>' +
            '<li class="probill">' + bill + '</li></ul>';
        $(".lcontent").html($(".lcontent").html() + str);
        var pid = $("#pid").val("");
        var pname = $("#pname").html("");
        var price = $("#price").val("");
        var count = $("#num").val("");
        var total = $("#total").html("");
        var bill = $("#pbill").val("");
    });
    $("#submit").click(function(){
        var data = Array();
        var code = $("#codetxt").html();
        var pid = $(".lcontent .row .proid");
        var pname = $(".lcontent .row .proname");
        var price = $(".lcontent .row .proprice");
        var count = $(".lcontent .row .procount");
        var billmoney = $(".lcontent .row .probillmoney");
        var total = $(".lcontent .row .prototal");
        var bill = $(".lcontent .row .probill");
        for (var i = 0; i < pid.length;i++) {
            data[i] = {};
            data[i].pcode = pid[i].innerHTML;
            data[i].name = pname[i].innerHTML;
            data[i].price = price[i].innerHTML;
            data[i].billmoney = billmoney[i].innerHTML;
            data[i].count = count[i].innerHTML;
            data[i].total = total[i].innerHTML;
            data[i].bill = bill[i].innerHTML;
        }
        $.post("/store/saveRecord.html",{'data':data,'buycode':code},function(data){
            if (data == -11)window.location.href="/store/index.html";
            if (data == -1) {
                alert("该采购代码已使用过!");
                return false;
            }
            else if (data == -2) {
                alert("录入商品错误!");
                return false;
            }
            //window.location.href="/store/index.html";
        });
    });

    //store boss 
    $(".blist,.backtoday").live("click",function(){
        var time = $(this).attr("dtime");
        $.post("/store/stat.html",{'do':'blist','time':time},function(data){
             if (data == -11)window.location.href="/store/index.html";
             if (data == 1) {
                 alert("查询失败!");
             }
             else {
                 $("#list").html(data);
             }
        });
    });
    $(".bdetails").live("click",function() {
        var code = $(this).attr("code");
        $.post("/store/stat.html",{'do':'bdetails','code':code},function(data){
            if (data == -11)window.location.href="/store/index.html";
            if (data == -1) {
                alert("请求失败!");
            }
            else {
                $("#list").html(data);
            }
        });
        return false;
    });

    //store boss cancel stat.
    $(".cblist").live("click",function() {
    	var time = $(this).attr("dtime");
        $.post("/store/cancel.html",{'do':'blist','time':time},function(data){
            if (data == -11)window.location.href="/store/index.html";
            if (data == -1) {
                alert("请求失败!");
            }
            else {
                $("#list").html(data);
            }
        });
        return false;
    });
    
//---------------------------confirm--
    $("#confirm .search").click(function(){
        var buycode = $("#code").val();
        if (buycode == "") {
            alert("请输入采购代码");
        }
        else {
            $.post("/store/confirm.html",{'do':'search','buycode' : buycode},function(data){
                if (data == -11)window.location.href="/store/index.html";
                if (data == -1) {
                    alert("未找到相关记录");
                }
                else {
                    $("#result").html(data);
                }
            });
        }
        return false;
    });
    var itemConfirmed = 0;
    $(".right").live("click",function(){
        var obj = $(this.parentNode.parentNode.parentNode);
        var ulobjs = $(obj).children("ul");
        itemConfirmed ++;
        
        alert(ulobjs.length);
    });
    $("#confirmBill").live("click",function(){
        var buycode = $(this).attr("code");
        $.post("/store/confirm.html",{'do':'confirmed','buycode' : buycode},function(data){
            if (data == -11)window.location.href="/store/index.html";
            if (data == -1) {
                alert("确认失败!");
            }
            else {
                alert("确认成功!");
            }
        });
    });
//------------------------------recycle
    $("#recycle .search").click(function(){
        var buycode = $("#code").val();
        if (buycode == "") {
            alert("请输入采购代码");
        }
        else {
            $.post("/store/recycle.html",{'do':'search','buycode' : buycode},function(data){
                if (data == -11)window.location.href="/store/index.html";
                if (data == -1) {
                    alert("未找到相关记录");
                }
                else {
                    $("#result").html(data);
                }
            });
        }
        return false;
    });
    $(".cancelrecord").live("click",function(){
        var id = $(this).attr("code");
        $.post("/store/recycle.html",{'do':'cancel','code' : id},function(data){
            if (data == -11)window.location.href="/store/index.html";
            if (data == -1) {
                alert("未找到相关记录");
            }
            else {
            	$("#recycle .search").click();
            }
        });
    });
});