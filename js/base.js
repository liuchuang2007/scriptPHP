$(document).ready(function(){
	//====== return stat ============
    $("#returnSearch").live("click",function(){
        var choice = $("input[name=condition]:checked");
        var val =  $(choice).val();
        $.post("/base/index.html",{'do':'search','stype':val},function(data){
           if (data == -11)window.location.href="/base/index.html";
            $("#list").html(data);
        });
    });
    $("#return ul li .monthdtails").live("click",function(){//stores of branches in specified day stat list of region.
        var time = $(this).attr("time");
        $.post("/base/index.html",{'do':'search','dtime':time,'stype':'onemonth'},function(data){
            if (data == -11)window.location.href="/base/index.html";
            $("#list").html(data);
        });
    });
    $("#return ul li .rlist").live("click",function(){//region branches.
        var time = $(this).attr("time");
        $.post("/base/index.html",{'do':'rlist','dtime':time},function(data){
            if (data == -11)window.location.href="/base/index.html";
            $("#list").html(data);
        });
    });
    $("#return ul li .rblist").live("click",function(){//region branches.
        var time = $(this).attr("time");
        var rcode = $(this).attr("rcode");
        $.post("/base/index.html",{'do':'rblist','dtime':time,'rcode':rcode},function(data){
            if (data == -11)window.location.href="/base/index.html";
            $("#list").html(data);
        });
    });
    $("#return ul li .storelist").live("click",function(){//region branches.
        var time = $(this).attr("time");
        var bcode = $(this).attr("bcode");
        $.post("/base/index.html",{'do':'storelist','dtime':time,'bcode':bcode},function(data){
            if (data == -11)window.location.href="/base/index.html";
            $("#list").html(data);
        });
    });
    $("#return ul li .plist").live("click",function(){//stores of branches in specified day stat list of region.
        var time = $(this).attr("dtime");
        var storecode = $(this).attr("store");
        $.post("/base/index.html",{'do':'plist','dtime':time,'storecode':storecode},function(data){
            if (data == -11)window.location.href="/base/index.html";
            $("#list").html(data);
        });
    });
    $("#return ul li .lrlist").live("click",function(){//branches last weeek stat.
        $.post("/base/index.html",{'do':'lrlist'},function(data){
            if (data == -11)window.location.href="/base/index.html";
            $("#list").html(data);
        });
    });
    $("#return ul li .lblist").live("click",function(){//branches last weeek stat.
        var rcode = $(this).attr("rcode");
        $.post("/base/index.html",{'do':'lblist','rcode':rcode},function(data){
            if (data == -11)window.location.href="/base/index.html";
            $("#list").html(data);
        });
    });
    $("#return ul li .lslist").live("click",function(){//store last weeek stat.
        var bcode = $(this).attr("bcode");
        $.post("/base/index.html",{'do':'lslist','bcode':bcode},function(data){
            if (data == -11)window.location.href="/base/index.html";
            $("#list").html(data);
        });
    });
    $("#return ul li .lsdetails").live("click",function(){//stores details in daily last week.
        var storecode = $(this).attr("store");
        $.post("/base/index.html",{'do':'lsdetails','storecode':storecode},function(data){
            if (data == -11)window.location.href="/base/index.html";
            $("#list").html(data);
        });
    });
//====== end return stat ========
//====== operation stat ============
    $("#opSearch").live("click",function(){
        var choice = $("input[name=condition]:checked");
        var val =  $(choice).val();
        $.post("/base/operation.html",{'do':'search','stype':val},function(data){
            if (data == -11)window.location.href="/base/index.html";
            $("#list").html(data);
        });
    });
    $("#operation ul li .branchlist").live("click",function(){
        var start = $("#operation").attr("start");
        var end = $("#operation").attr("end");
        var rcode =  $(this).attr("rcode");
        $.post("/base/operation.html",{'do':'branchlist','start':start,'end':end,'rcode':rcode},function(data){
            if (data == -11)window.location.href="/base/index.html";
            $("#list").html(data);
        });
    });
    $("#operation ul li .opclist").live("click",function(){
        var start = $("#operation").attr("start");
        var end = $("#operation").attr("end");
        var bcode =  $(this).attr("bcode");
        $.post("/base/operation.html",{'do':'opclist','start':start,'end':end,'bcode':bcode},function(data){
            if (data == -11)window.location.href="/base/index.html";
            $("#list").html(data);
        });
    });
    $("#operation ul li .opblist").live("click",function(){
        var start = $("#operation").attr("start");
        var end = $("#operation").attr("end");
        var bcode =  $("#operation").attr("bcode");
        var cid =  $(this).attr("cid");
        $.post("/base/operation.html",{'do':'opblist','start':start,'end':end,'bcode':bcode,'cid':cid},function(data){
            if (data == -11)window.location.href="/base/index.html";
            $("#list").html(data);
        });
    });
    $("#operation ul li .plist").live("click",function(){
        var start = $("#operation").attr("start");
        var end = $("#operation").attr("end");
        var cid =  $("#operation").attr("cid");
        var bcode =  $("#operation").attr("bcode");
        var bid =  $(this).attr("bid");
        $.post("/base/operation.html",{'do':'plist','start':start,'end':end,'cid':cid,'bid':bid,'bcode':bcode},function(data){
            if (data == -11)window.location.href="/base/index.html";
            $("#list").html(data);
        });
    });
//====== end operation stat ========
//====== supplier stat ============
    $("#supplier ul li .sdlist").live("click",function(){//one day all suppliers stat list
        var time = $(this).attr("time");
        var bcode = $(this).attr("bcode");
        $.post("/base/supplier.html",{'do':'sdlist','dtime':time,'bcode':bcode},function(data){
            if (data == -11)window.location.href="/base/index.html";
            $("#list").html(data);
        });
    });
    $("#supplier ul li .lrlist").live("click",function(){//last week region list
        $.post("/base/supplier.html",{'do':'lrlist'},function(data){
            if (data == -11)window.location.href="/base/index.html";
            $("#list").html(data);
        });
    });
    $("#supplier ul li .lblist").live("click",function(){//last week branch list
        var rcode = $(this).attr("rcode");
        $.post("/base/supplier.html",{'do':'lblist','rcode':rcode},function(data){
            if (data == -11)window.location.href="/base/index.html";
            $("#list").html(data);
        });
    });
    $("#supplier ul li .slblist").live("click",function(){//the supplier last week stat list of choosed branch.
        var bcode = $(this).attr("bcode");
        $.post("/base/supplier.html",{'do':'slblist','bcode':bcode},function(data){
            if (data == -11)window.location.href="/base/index.html";
            $("#list").html(data);
        });
    });
    $("#supplier ul li .sldlist").live("click",function(){// the choosed supplier's everyday of last week stat.
        var scode = $(this).attr("scode");
        $.post("/base/supplier.html",{'do':'sldlist','scode':scode},function(data){
            if (data == -11)window.location.href="/base/index.html";
            $("#list").html(data);
        });
    });
    $("#supplier ul li .sclist").live("click",function(){// the choosed day stat of supllier.
        var scode = $(this).attr("scode");
        var time = $(this).attr("time");
        $.post("/base/supplier.html",{'do':'sclist','scode':scode,'time':time},function(data){
            if (data == -11)window.location.href="/base/index.html";
            $("#list").html(data);
        });
    });
    $("#supplier ul li .sblist").live("click",function(){//branch brand stat list in specified day
        var time = $("#supplier").attr("dtime");
        var scode = $("#supplier").attr("scode");
        var cid = $(this).attr("cid");
        $.post("/base/supplier.html",{'do':'sblist','dtime':time,'scode':scode,'cid':cid},function(data){
            if (data == -11)window.location.href="/base/index.html";
            $("#list").html(data);
        });
    });
    $("#supplier ul li .plist").live("click",function(){//product list rblist
        var time = $("#supplier").attr("dtime");
        var scode = $("#supplier").attr("scode");
        var cid = $("#supplier").attr("cid");
        var bid = $(this).attr("bid");
        $.post("/base/supplier.html",{'do':'plist','dtime':time,'scode':scode,'bid':bid,'cid':cid},function(data){
            if (data == -11)window.location.href="/base/index.html";
            $("#list").html(data);
        });
    });
    $("#supplier ul li .rlist").live("click",function(){//branches specified day stat list of region.
        var time = $(this).attr("time");
        $.post("/base/supplier.html",{'do':'rlist','dtime':time},function(data){
            if (data == -11)window.location.href="/base/index.html";
            $("#list").html(data);
        });
    });
    $("#supplier ul li .rblist").live("click",function(){//branches specified day stat list of region.
        var time = $(this).attr("time");
        var rcode = $(this).attr("rcode");
        $.post("/base/supplier.html",{'do':'rblist','dtime':time,'rcode':rcode},function(data){
            if (data == -11)window.location.href="/base/index.html";
            $("#list").html(data);
        });
    });
    $("#supplierSearch").live("click",function(){
        var choice = $("input[name=condition]:checked");
        var val =  $(choice).val();
        $.post("/base/supplier.html",{'do':'search','stype':val},function(data){
            if (data == -11)window.location.href="/base/index.html";
            $("#list").html(data);
        });
    });
    $("#supplier ul li .monthdtails").live("click",function(){//stores of branches in specified day stat list of region.
        var time = $(this).attr("time");
        $.post("/base/supplier.html",{'do':'search','dtime':time,'stype':'onemonth'},function(data){
            if (data == -11)window.location.href="/base/index.html";
            $("#list").html(data);
        });
    });
//====== end supplier stat ========
//====== cancel stat ============
    $("#cancelSearch").live("click",function(){
        var choice = $("input[name=condition]:checked");
        var val =  $(choice).val();
        var key =  $("#key").val();
        $.post("/base/cancel.html",{'do':'search','stype':val,'key':key},function(data){
            if (data == -11)window.location.href="/base/index.html";
            $("#list").html(data);
        });
    });
    $("#cancelM ul li .regionlist").live("click",function(){//branches specified day stat list of region.
        var time = $(this).attr("time");
        $.post("/base/cancel.html",{'do':'regionlist','dtime':time},function(data){
            if (data == -11)window.location.href="/base/index.html";
            $("#list").html(data);
        });
    });
    $("#cancelM ul li .branchlist").live("click",function(){//branches specified day stat list of region.
        var time = $("#cancelM").attr("dtime");
        var rcode = $(this).attr("rcode");
        $.post("/base/cancel.html",{'do':'branchlist','dtime':time,'rcode':rcode},function(data){
            if (data == -11)window.location.href="/base/index.html";
            $("#list").html(data);
        });
    });
    $("#cancelM ul li .storelist").live("click",function(){//stores of branches in specified day stat list of region.
        var time = $("#cancelM").attr("dtime");
        var bcode = $(this).attr("bcode");
        $.post("/base/cancel.html",{'do':'storelist','dtime':time,'bcode':bcode},function(data){
            if (data == -11)window.location.href="/base/index.html";
            $("#list").html(data);
        });
    });
    $("#cancelM ul li .plist").live("click",function(){//stores of branches in specified day stat list of region.
        var time = $("#cancelM").attr("dtime");
        var storecode = $(this).attr("store");
        $.post("/base/cancel.html",{'do':'plist','dtime':time,'storecode':storecode},function(data){
            if (data == -11)window.location.href="/base/index.html";
            $("#list").html(data);
        });
    });
    $("#cancelM ul li .monthdtails").live("click",function(){//stores of branches in specified day stat list of region.
        var time = $(this).attr("time");
        $.post("/base/cancel.html",{'do':'search','dtime':time,'stype':'onemonth'},function(data){
            if (data == -11)window.location.href="/base/index.html";
            $("#list").html(data);
        });
    });
//====== end cancel stat ========
    //setup.
    /*$("#addBranch").click(function(){
        var name = $("#branchName").val();
        var str = "";
        $(".choices").each(function(){
            if (this.checked == true) {
                str = str + $(this).attr("value") + ",";
            }
        });
        if (name == "") {
            alert("请填写分部名称");
            return false;
        }
        if (str == "") {
            alert("请选择城市!");
            return false;
        }
        $.post("/base/newBranchAccount.html",{'name':name,'city':str},function(data){
            if (data == -11)window.location.href="/base/index.html";
            if (data != -1) {
                window.location.href="/base/setup.html";
            }
        });
    });
    $("#lock").live("click",function(){
        var obj = this.parentNode;
        var code = $(obj).attr("mcode");
        var status = $(this).attr("status");
        var currobj = this;
        $.post("/site/lockAccount.html",{'type':'manager','mcode':code,'status':status},function(data){
            if (data == -11)window.location.href="/base/index.html";
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
    $("#newCode").live("click",function(){
        var op = this.id;
        var obj = this.parentNode;
        var code = $(obj).attr("mcode");
        $.post("/site/newLogincode.html",{'do':'base','mcode':code},function(data){
            if (data == -11)window.location.href="/base/index.html";
            if (data != -1) {
                var rowobj = obj.parentNode;
                $(rowobj).children("li").children("#code").html(data);
            }
        });
    });*/
});