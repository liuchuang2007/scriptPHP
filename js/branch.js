$(document).ready(function(){
//======return stat==============
    $("#return ul li .storelist").live("click",function(data){
        var time = $(this).attr("time");
        var key = $("#return").attr("key");
        $.post("/branch/index.html",{'do':'storelist','time':time,'key':key},function(data){
            if (data == -11)window.location.href="/branch/index.html";
            $("#list").html(data);
        });
    });
    $("#return ul li .bclist").live("click",function(data){//buycodelist
        //condition storecode,dtime
        var date = $(this).attr("dtime");
        var dcode = $(this).attr("store");
        $.post("/branch/index.html",{'do':'bclist','time':date,'dcode':dcode},function(data){
            if (data == -11)window.location.href="/branch/index.html";
            $("#list").html(data);
        });
    });
    $("#index ul li .plist").live("click",function(data){
        //condition buycode
        var buycode = $(this).attr("buycode");
        $.post("/branch/index.html",{'do':'plist','buycode':buycode},function(data){
            if (data == -11)window.location.href="/branch/index.html";
            $("#list").html(data);
        });
    });
    $("#return ul li .lslist").live("click",function(data){
        //condition buycode
        var key = $("#key").val();
        $.post("/branch/index.html",{'do':'lslist','key':key},function(data){
            if (data == -11)window.location.href="/branch/index.html";
            $("#list").html(data);
        });
    });
    $("#return ul li .lsdetails").live("click",function(data){
        //condition buycode
        var storecode = $(this).attr("store");
        $.post("/branch/index.html",{'do':'lsdetails','dcode':storecode},function(data){
            if (data == -11)window.location.href="/branch/index.html";
            $("#list").html(data);
        });
    });
    $("#returnSearch").live("click",function(data){
        var choice = $("input[name=condition]:checked");
        var key = $("#key").val();
        var stype =  $(choice).val();
        $.post("/branch/index.html",{'do':'search','stype':stype,'key':key},function(data){
            if (data == -11)window.location.href="/branch/index.html";
            $("#list").html(data);
        });
    });
    $("#return ul li .monthdtails").live("click",function(){//stores of branches in specified day stat list of region.
        var time = $(this).attr("time");
        $.post("/branch/index.html",{'do':'search','dtime':time,'stype':'onemonth'},function(data){
            if (data == -11)window.location.href="/region/index.html";
            $("#list").html(data);
        });
    });
//====== end return stat ==========
//====== supplier stat ============
    $("#supplier ul li .sllist").live("click",function(){// last week all suppliers stat list
        var key = $("#supplier").attr("key");
        $.post("/branch/supplier.html",{'do':'sllist','key':key},function(data){
            if (data == -11)window.location.href="/branch/index.html";
            $("#list").html(data);
        });
    });
    $("#supplier ul li .sldlist").live("click",function(){// last week all suppliers stat list
        var scode = $(this).attr("scode");
        $.post("/branch/supplier.html",{'do':'sldlist','scode':scode},function(data){
            if (data == -11)window.location.href="/branch/index.html";
            $("#list").html(data);
        });
    });
    $("#supplier ul li .sclist").live("click",function(){//suppliers one day details
        var time = $(this).attr("time");
        var scode = $(this).attr("scode");
        $.post("/branch/supplier.html",{'do':'sclist','dtime':time,'scode':scode},function(data){
            if (data == -11)window.location.href="/branch/index.html";
            $("#list").html(data);
        });
    });
    $("#supplier ul li .sblist").live("click",function(){
        var time = $("#supplier").attr("dtime");
        var scode = $("#supplier").attr("scode");
        var cid = $(this).attr("cid");
        console.log(time);
        $.post("/branch/supplier.html",{'do':'sblist','dtime':time,'scode':scode,'cid':cid},function(data){
            if (data == -11)window.location.href="/branch/index.html";
            $("#list").html(data);
        });
    });
    $("#supplier ul li .sdlist").live("click",function(){//one day all suppliers stat list
        var time = $(this).attr("time");
        $.post("/branch/supplier.html",{'do':'sdlist','dtime':time},function(data){
            if (data == -11)window.location.href="/branch/index.html";
            $("#list").html(data);
        });
    });
    $("#supplier ul li .plist").live("click",function(){
        var time = $("#supplier").attr("dtime");
        var scode = $("#supplier").attr("scode");
        var bid = $(this).attr("bid");
        console.log(time);
        $.post("/branch/supplier.html",{'do':'plist','dtime':time,'scode':scode,'bid':bid},function(data){
            if (data == -11)window.location.href="/branch/index.html";
            $("#list").html(data);
        });
    });
    $("#supplierSearch").click(function(){
        var choice = $("input[name=condition]:checked");
        var val =  $(choice).val();
        $.post("/branch/supplier.html",{'do':'search','stype':val},function(data){
            if (data == -11)window.location.href="/branch/index.html";
            $("#list").html(data);
        });
    });
//====== end supplier stat ========
//====== operation stat ===========
    $("#operation ul li .opblist").live("click",function(data){
        //condition cid
        var cid = $(this).attr("cid");
        var start = $("#operation").attr("start");
        var end = $("#operation").attr("end");
        $.post("/branch/operation.html",{'do':'opblist','cid':cid,'end':end,'start':start},function(data){
            if (data == -11)window.location.href="/branch/index.html";
            $("#list").html(data);
        });
    });
    $("#operation ul li .bdetails").live("click",function(data){
        //condition bid
        var bid = $(this).attr("bid");
        $.post("/branch/operation.html",{'do':'bdetails','bid':bid},function(data){
            if (data == -11)window.location.href="/branch/index.html";
            $("#list").html(data);
        });
    });
    $("#opSearch").live("click",function(data){
        var choice = $("input[name=condition]:checked");
        var key = $("#key").val();
        var stype =  $(choice).val();
        $.post("/branch/operation.html",{'do':'search','stype':stype,'key':key},function(data){
            if (data == -11)window.location.href="/branch/index.html";
            $("#list").html(data);
        });
    });
//====== end operation stat =======
//======== cancel stat ============
    $("#cancelM ul li .storelist").live("click",function(data){
        var time = $(this).attr("time");
        var key = $("#cancelM").attr("key");
        $.post("/branch/cancel.html",{'do':'storelist','dtime':time,'key':key},function(data){
            if (data == -11)window.location.href="/branch/index.html";
            $("#list").html(data);
        });
    });
    $("#cancelM ul li .plist").live("click",function(data){
        //condition store dtime
        var time = $(this).attr("time");
        var code = $(this).attr("code");
        $.post("/branch/cancel.html",{'do':'plist','dtime':time,'dcode':code},function(data){
            if (data == -11)window.location.href="/branch/index.html";
            $("#list").html(data);
        });
    });
    $("#cancelSearch").live("click",function(data){
        var choice = $("input[name=condition]:checked");
        var key = $("#key").val();
        var stype =  $(choice).val();
        $.post("/branch/cancel.html",{'do':'search','stype':stype,'key':key},function(data){
            if (data == -11)window.location.href="/branch/index.html";
            $("#list").html(data);
        });
    });
    $("#cancelM ul li .monthdtails").live("click",function(){//stores of branches in specified day stat list of region.
        var time = $(this).attr("time");
        $.post("/branch/cancel.html",{'do':'search','dtime':time,'stype':'onemonth'},function(data){
            if (data == -11)window.location.href="/branch/index.html";
            $("#list").html(data);
        });
    });
//======== end cancel stat ========
//======== supplier set ========
    $("#addsupplier").click(function(){
        var name = $("#addname").val();
        if (name == "") {
            alert("请输入供应商名称!");return false;
        }
        if (schoice.length == 0) {
            alert("请选择供应商品牌!");return false;
        }

        $.post("/branch/supplierset.html",{'do':'addnew','brand':schoice,'name':name},function(data){
            if (data == -11)window.location.href="/branch/index.html";
            if (data == 1) {
                window.location.href="/branch/supplierset.html";
            }
            else {
                alert("添加失败!");
            }
        });
    });
    $("#category .cateitems").live("click",function(){
        var val = this.value;
        $("#brand").html("");
        for (var i = 0;i < brands.length;i++) {
            if (brands[i].pid == val) {
                $("#brand").html($("#brand").html() + '<li><input type="checkbox" class="bitems" value="'+brands[i].id+'"/>'+brands[i].name+'</li>');
            }
        }
    });
    var schoice = [];
    $("#brand .bitems").live("click",function(){
        var val = this.value;
        var checked = this.checked;
        if (checked) {
            for (var i = 0;i < schoice.length;i++) {
                if (schoice[i] == val) {
                    return;
                }
            }
            schoice.push(val);
        }
        else {
            for (var i = 0;i < schoice.length;i++) {
                if (schoice[i] == val) {
                    schoice.splice(i,1);
                }
            }
        }
    });
//======== end supplier set ========adddepart
//======== department set ========
    var departchosen = [];
    $("#adddepart").click(function(){
        var name = $("#addname").val();
        if (name == "") {
            alert("请输入供应商名称!");return false;
        }
        if (departchosen.length == 0) {
            alert("请选择供应商品牌!");return false;
        }

        $.post("/branch/departmentset.html",{'do':'newDepart','brand':departchosen,'name':name},function(data){
            if (data == -11)window.location.href="/branch/index.html";
            if (data == 1) {
                window.location.href="/branch/departmentset.html";
                departchosen = [];
            }
            else {
                alert("添加失败!");
            }
        });
        console.log(departchosen);
    });
    $("#allbrand .items").click(function(){
        var val = this.value;
        var checked = this.checked;
        if (checked) {
            for (var i = 0;i < departchosen.length;i++) {
                if (departchosen[i] == val) {
                    return;
                }
            }
            departchosen.push(val);
        }
        else {
            for (var i = 0;i < schoice.length;i++) {
                if (departchosen[i] == val) {
                    departchosen.splice(i,1);
                }
            }
        }
    });
    $("#department ul li .newcode").click(function(){
        var obj = this.parentNode;
        var code = $(obj).attr("code");
        $.post("/branch/departmentset.html",{'do':'newcode','mcode':code},function(data){
            if (data == -11)window.location.href="/branch/index.html";
            var rowobj = obj.parentNode;
            $(rowobj).children(".code").html(data);
            return false;
        });
    });
    $("#department ul li .lock").live("click",function(){
        var obj = this.parentNode;
        var code = $(obj).attr("mcode");
        var status = $(this).attr("status");
        var currobj = this;
        $.post("/site/lockAccount.html",{'type':'store','mcode':code,'status':status},function(data){
            if (data == -11)window.location.href="/branch/index.html";
            if ( data == 1 && status == 1) {
                $(currobj).html("解锁");
                $(currobj).attr("status","2");
            }
            else if (data == 1 && status == 2) {
                $(currobj).html("锁定");
                $(currobj).attr("status","1");
            }
        });
    });
//======== end department set ========
//======== store management ========
    /*$("#lock").live("click",function(){
        var obj = this.parentNode;
        var code = $(obj).attr("mcode");
        var status = $(this).attr("status");
        var currobj = this;
        $.post("/site/lockAccount.html",{'type':'store','mcode':code,'status':status},function(data){
            if (data == -11)window.location.href="/branch/index.html";
            if ( data == 1 && status == 1) {
                $(currobj).html("解锁");
                $(currobj).attr("status","2");
            }
            else if (data == 1 && status == 2) {
                $(currobj).html("锁定");
                $(currobj).attr("status","1");
            }
        });
    });*/
//-------------store management
    $(".op a").live("click",function(){
        var op = this.className;
        var obj = this.parentNode;
        var code = $(this).attr("mcode");
        $.post("/site/newLogincode.html",{'mcode':code},function(data){
            if (data == -11)window.location.href="/branch/index.html";
            if (data != -1) {
               var rowobj = obj.parentNode;
                if (op == 'news') {
                    $(rowobj).children(".codes").html(data);
                }
                else if (op == 'newf') {
                    $(rowobj).children(".codef").html(data);
                }
                else if (op == 'newb') {
                    $(rowobj).children(".codeb").html(data);
                }
            }
        });
    });
    $(".clist").live("click",function(data){
        $.post("/branch/opSearch.html",{'do':'clist'},function(data){
            if (data == -11)window.location.href="/branch/index.html";
            $("#list").html(data);
        });
    });
    $("#newStore").click(function(){
        var name = $("#name").val();
        var citycode = $("#city").val();
        $.post("/branch/newAccount.html",{'name':name,'citycode':citycode},function(data){
            if (data == -11)window.location.href="/branch/index.html";
            if (data == -1) {
               alert("请输入名称");
            }
            else if (data == -2) {
                alert("请选择城市");
            }
            else {
                window.location.href="/branch/storeset.html";
            }
        });
    });
//end store management.
// ----------- close on date
    $("#close").click(function(){
        if (!globalChoice.length) {
            alert("请选择要关闭的日期!");
            return false;
        }
        $.post("/branch/closeset.html",{'do':'close','date':globalChoice},function(data){
            if (data == -11)window.location.href="/branch/index.html";
            if (data != -1) {
               alert("选中日期已关闭");
               //window.location.href="/branch/closeset.html";
            }
        });
    });
// ----------- end close on date
// -----------  all return stat
    $("#allReturnSearch").live("click",function(data){
        var choice = $("input[name=condition]:checked");
        var key = $("#key").val();
        var stype =  $(choice).val();
        $.post("/branch/allreturn.html",{'do':'search','stype':stype,'key':key},function(data){
            if (data == -11)window.location.href="/branch/allreturn.html";
            $("#list").html(data);
        });
    });
    $("#allReturn ul li .plist").live("click",function(data){
        var dcode = $(this).attr("dcode");
        var start =  $("#allReturn").attr("start");
        var end =  $("#allReturn").attr("end");
        $.post("/branch/allreturn.html",{'do':'plist','dcode':dcode,'start':start,'end':end},function(data){
            if (data == -11)window.location.href="/branch/allreturn.html";
            $("#list").html(data);
        });
    });
// ----------- end all return stat
// -----------  all cancel stat
    $("#allCancelSearch").live("click",function(data){
        var choice = $("input[name=condition]:checked");
        var key = $("#key").val();
        var stype =  $(choice).val();
        $.post("/branch/allcancel.html",{'do':'search','stype':stype,'key':key},function(data){
            if (data == -11)window.location.href="/branch/allreturn.html";
            $("#list").html(data);
        });
    });
    $("#allCancel ul li .plist").live("click",function(data){
        var dcode = $(this).attr("dcode");
        var start =  $("#allCancel").attr("start");
        var end =  $("#allCancel").attr("end");
        $.post("/branch/allcancel.html",{'do':'plist','dcode':dcode,'start':start,'end':end},function(data){
            if (data == -11)window.location.href="/branch/allreturn.html";
            $("#list").html(data);
        });
    });
// ----------- end all cancel stat
});