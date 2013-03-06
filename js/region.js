$(document).ready(function(){
	
//====== operation stat ============
    $("#opSearch").live("click",function(){
        var choice = $("input[name=condition]:checked");
        $.post("/region/operation.html",{'do':'search','stype':val},function(data){
            if (data == -11)window.location.href="/region/index.html";
            $("#list").html(data);
        });
    });
    $("#operation ul li .opclist").live("click",function(){
        var start = $("#operation").attr("start");
        var end = $("#operation").attr("end");
        var bcode =  $(this).attr("bcode");
        $.post("/region/operation.html",{'do':'opclist','start':start,'end':end,'bcode':bcode},function(data){
            if (data == -11)window.location.href="/region/index.html";
            $("#list").html(data);
        });
    });
    $("#operation ul li .opblist").live("click",function(){
        var start = $("#operation").attr("start");
        var end = $("#operation").attr("end");
        var bcode =  $("#operation").attr("bcode");
        var cid =  $(this).attr("cid");
        $.post("/region/operation.html",{'do':'opblist','start':start,'end':end,'bcode':bcode,'cid':cid},function(data){
            if (data == -11)window.location.href="/region/index.html";
            $("#list").html(data);
        });
    });
    $("#operation ul li .plist").live("click",function(){
        var start = $("#operation").attr("start");
        var end = $("#operation").attr("end");
        var cid =  $("#operation").attr("cid");
        var bcode =  $("#operation").attr("bcode");
        var bid =  $(this).attr("bid");
        $.post("/region/operation.html",{'do':'plist','start':start,'end':end,'cid':cid,'bid':bid,'bcode':bcode},function(data){
            if (data == -11)window.location.href="/region/index.html";
            $("#list").html(data);
        });
    });
//====== end operation stat ========
//====== supplier stat ============

    $("#supplier ul li .sdlist").live("click",function(){//one day all suppliers stat list
        var time = $(this).attr("time");
        var bcode = $(this).attr("bcode");
        $.post("/region/supplier.html",{'do':'sdlist','dtime':time,'bcode':bcode},function(data){
            if (data == -11)window.location.href="/region/index.html";
            $("#list").html(data);
        });
    });
    $("#supplier ul li .lblist").live("click",function(){//branches last week stat.
        $.post("/region/supplier.html",{'do':'lblist'},function(data){
            if (data == -11)window.location.href="/region/index.html";
            $("#list").html(data);
        });
    });
    $("#supplier ul li .slblist").live("click",function(){//the supplier last week stat list of choosed branch.
        var bcode = $(this).attr("bcode");
        $.post("/region/supplier.html",{'do':'slblist','bcode':bcode},function(data){
            if (data == -11)window.location.href="/region/index.html";
            $("#list").html(data);
        });
    });
    $("#supplier ul li .sldlist").live("click",function(){// the choosed supplier's everyday of last week stat.
        var scode = $(this).attr("scode");
        $.post("/region/supplier.html",{'do':'sldlist','scode':scode},function(data){
            if (data == -11)window.location.href="/region/index.html";
            $("#list").html(data);
        });
    });
    $("#supplier ul li .sclist").live("click",function(){// the choosed day stat of supllier.
        var scode = $(this).attr("scode");
        var time = $(this).attr("time");
        $.post("/region/supplier.html",{'do':'sclist','scode':scode,'time':time},function(data){
            if (data == -11)window.location.href="/region/index.html";
            $("#list").html(data);
        });
    });
    $("#supplier ul li .sblist").live("click",function(){//branch brand stat list in specified day
        var time = $("#supplier").attr("dtime");
        var scode = $("#supplier").attr("scode");
        var cid = $(this).attr("cid");
        $.post("/region/supplier.html",{'do':'sblist','dtime':time,'scode':scode,'cid':cid},function(data){
            if (data == -11)window.location.href="/region/index.html";
            $("#list").html(data);
        });
    });
    $("#supplier ul li .plist").live("click",function(){//product list rblist
        var time = $("#supplier").attr("dtime");
        var scode = $("#supplier").attr("scode");
        var cid = $("#supplier").attr("cid");
        var bid = $(this).attr("bid");
        $.post("/region/supplier.html",{'do':'plist','dtime':time,'scode':scode,'bid':bid,'cid':cid},function(data){
            if (data == -11)window.location.href="/region/index.html";
            $("#list").html(data);
        });
    });
    $("#supplier ul li .rblist").live("click",function(){//branches specified day stat list of region.
        var time = $(this).attr("time");
        $.post("/region/supplier.html",{'do':'rblist','dtime':time},function(data){
            if (data == -11)window.location.href="/region/index.html";
            $("#list").html(data);
        });
    });
    $("#supplierSearch").live("click",function(){
        var choice = $("input[name=condition]:checked");
        var val =  $(choice).val();
        $.post("/region/supplier.html",{'do':'search','stype':val},function(data){
            if (data == -11)window.location.href="/region/index.html";
            $("#list").html(data);
        });
    });
    $("#supplier ul li .monthdtails").live("click",function(){//stores of branches in specified day stat list of region.
        var time = $(this).attr("time");
        $.post("/region/supplier.html",{'do':'search','dtime':time,'stype':'onemonth'},function(data){
            if (data == -11)window.location.href="/region/index.html";
            $("#list").html(data);
        });
    });
//====== end supplier stat ========
//====== cancel stat ============
    $("#cancelSearch").live("click",function(){
        var choice = $("input[name=condition]:checked");
        var val =  $(choice).val();
        var key =  $("#key").val();
        $.post("/region/cancel.html",{'do':'search','stype':val,'key':key},function(data){
            if (data == -11)window.location.href="/region/index.html";
            $("#list").html(data);
        });
    });
    $("#cancelM ul li .branchlist").live("click",function(){//branches specified day stat list of region.
        var time = $(this).attr("time");
        $.post("/region/cancel.html",{'do':'branchlist','dtime':time},function(data){
            if (data == -11)window.location.href="/region/index.html";
            $("#list").html(data);
        });
    });
    $("#cancelM ul li .storelist").live("click",function(){//stores of branches in specified day stat list of region.
        var time = $("#cancelM").attr("dtime");
        var bcode = $(this).attr("bcode");
        $.post("/region/cancel.html",{'do':'storelist','dtime':time,'bcode':bcode},function(data){
            if (data == -11)window.location.href="/region/index.html";
            $("#list").html(data);
        });
    });
    $("#cancelM ul li .plist").live("click",function(){//stores of branches in specified day stat list of region.
        var time = $("#cancelM").attr("dtime");
        var storecode = $(this).attr("store");
        $.post("/region/cancel.html",{'do':'plist','dtime':time,'storecode':storecode},function(data){
            if (data == -11)window.location.href="/region/index.html";
            $("#list").html(data);
        });
    });
    $("#cancelM ul li .monthdtails").live("click",function(){//stores of branches in specified day stat list of region.
        var time = $(this).attr("time");
        $.post("/region/cancel.html",{'do':'search','dtime':time,'stype':'onemonth'},function(data){
            if (data == -11)window.location.href="/region/index.html";
            $("#list").html(data);
        });
    });
//====== end cancel stat ========
//====== return stat ============
    $("#returnSearch").live("click",function(){
        var choice = $("input[name=condition]:checked");
        var val =  $(choice).val();
        $.post("/region/index.html",{'do':'search','stype':val},function(data){
            if (data == -11)window.location.href="/region/index.html";
            $("#list").html(data);
        });
    });
    $("#return ul li .monthdtails").live("click",function(){//stores of branches in specified day stat list of region.
        var time = $(this).attr("time");
        $.post("/region/index.html",{'do':'search','dtime':time,'stype':'onemonth'},function(data){
            if (data == -11)window.location.href="/region/index.html";
            $("#list").html(data);
        });
    });
    $("#return ul li .rblist").live("click",function(){//region branches.
        var time = $(this).attr("time");
        $.post("/region/index.html",{'do':'rblist','dtime':time},function(data){
            if (data == -11)window.location.href="/region/index.html";
            $("#list").html(data);
        });
    });
    $("#return ul li .storelist").live("click",function(){//region branches.
        var time = $(this).attr("time");
        var bcode = $(this).attr("bcode");
        $.post("/region/index.html",{'do':'storelist','dtime':time,'bcode':bcode},function(data){
            if (data == -11)window.location.href="/region/index.html";
            $("#list").html(data);
        });
    });
    $("#return ul li .plist").live("click",function(){//stores of branches in specified day stat list of region.
        var time = $(this).attr("dtime");
        var storecode = $(this).attr("store");
        $.post("/region/index.html",{'do':'plist','dtime':time,'storecode':storecode},function(data){
            if (data == -11)window.location.href="/region/index.html";
            $("#list").html(data);
        });
    });
    $("#return ul li .lblist").live("click",function(){//branches last weeek stat.
        $.post("/region/index.html",{'do':'lblist'},function(data){
            if (data == -11)window.location.href="/region/index.html";
            $("#list").html(data);
        });
    });
    $("#return ul li .lslist").live("click",function(){//store last weeek stat.
        var bcode = $(this).attr("bcode");
        $.post("/region/index.html",{'do':'lslist','bcode':bcode},function(data){
            if (data == -11)window.location.href="/region/index.html";
            $("#list").html(data);
        });
    });
    $("#return ul li .lsdetails").live("click",function(){//stores details in daily last week.
        var storecode = $(this).attr("store");
        $.post("/region/index.html",{'do':'lsdetails','storecode':storecode},function(data){
            if (data == -11)window.location.href="/region/index.html";
            $("#list").html(data);
        });
    });
//====== end return stat ========

});