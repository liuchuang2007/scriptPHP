$(document).ready(function(){
//-----return stat start---------
    $("#returnSearch").click(function(){
        var choice = $("input[name=condition]:checked");
        var val =  $(choice).val();
        var code =  $("#city").val();
        $.post("/center/index-search.html",{'citycode':code,'stype':val},function(data){
            if (data == -11)window.location.href="/center/index.html";
            $("#list").html(data);
        });
    });
    $("#return ul li .clist").live("click",function(data){
        var stype = $("#return").attr("stype");
        var citycode = $("#return").attr("citycode");
        var dcode = $(this).attr("dcode");
        $.post("/center/index-clist.html",{'stype':stype,'citycode':citycode,'dcode':dcode},function(data){
            if (data == -11)window.location.href="/center/index.html";
            $("#main").html(data);
        });
    });
    $("#returncSearch").live("click",function(){
        var cate =  $("#cate").val();
        var dcode = $(this).attr("dcode");
        var citycode = $(this).attr("citycode");
        var stype = $(this).attr("stype");
        $.post("/center/index-csearch.html",{'citycode':citycode,'dcode':dcode,'stype':stype,'cate':cate},function(data){
            if (data == -11)window.location.href="/center/index.html";
            $("#list").html(data);
        });
    });
    $("#return ul li .blist").live("click",function(data){
        var stype = $("#return").attr("stype");
        var citycode = $("#return").attr("citycode");
        var dcode = $("#return").attr("dcode");
        var cate = $(this).attr("cate");
        $.post("/center/index-blist.html",{'stype':stype,'citycode':citycode,'dcode':dcode,'cate':cate},function(data){
            if (data == -11)window.location.href="/center/index.html";
            $("#main").html(data);
        });
    });
    $("#returnbSearch").live("click",function(){
        var brand =  $("#brand").val();
        var dcode = $(this).attr("dcode");
        var citycode = $(this).attr("citycode");
        var stype = $(this).attr("stype");
        var cate = $(this).attr("cate");
        $.post("/center/index-bsearch.html",{'citycode':citycode,'dcode':dcode,'stype':stype,'cate':cate,'brand':brand},function(data){
            if (data == -11)window.location.href="/center/index.html";
            $("#list").html(data);
        });
    });
//-----return stat end---------

    //supplier
    $("#supplierlist").live("click",function(data){
        $.post("/center/search.html",{'do':'supplierlist'},function(data){
            if (data == -11)window.location.href="/center/index.html";
            $("#list").html(data);
        });
    });
    $(".supplierdetails").live("click",function(data){
        var citycode = $(this).attr("citycode");
        var scode = $(this).attr("scode");
        $.post("/center/search.html",{'do':'supplierdetails','citycode':citycode,'scode':scode},function(data){
            if (data == -11)window.location.href="/center/index.html";
            $("#list").html(data);
        });
    });

    //operation
    $("#oplist").live("click",function(data){
        $.post("/center/search.html",{'do':'oplist'},function(data){
            if (data == -11)window.location.href="/center/index.html";
            $("#list").html(data);
        });
    });

//----company user management start ----
    $(".reviewDetail").live("click",function(){
        var usercode = $(this).attr("code");
        $.post("/center/company-review.html",{'code':usercode},function(data){
            if (data == -11)window.location.href="/center/index.html";
            $("#list").html(data);
        });
    });
    $("#upLi").live("click",function(){
        upload.document.frm.uploadFile.click();
    });
    $("#upOrg").live("click",function(){
        org.document.frm.uploadFile.click();
    });
    $("#approve").live("click",function(){
        var data = {};
        data.ucode = $("#details").attr("usercode");
        data.company = $("#companyname").val();
        data.contact = $("#contact").val();
        data.mobile = $("#mobile").val();
        data.auth = $("#auth").val();
        data.phone = $("#phone").val();
        data.email = $("#email").val();
        data.addr = $("#addr").val();
        data.licensepic = $("#licensePic").attr("href");
        data.licensecode = $("#licenseCode").val();
        data.capitallevel  = $("#capitallevel").val();
        data.orgpic = $("#orgPic").attr("href");
        data.orgcode = $("#orgCode").val();
        data.type = $("#companyType").val();
        data.industry = $("#companyIndus").val();
        
        //data filter.
        var isReturn = false;
        if (data.company == '') {
            alert('请输入公司名称');
            isReturn = true;
        }
        else if (data.contact == '') {
            alert('请输入联系人名称');
            isReturn = true;
        }
        else if (data.mobile == '') {
            alert('请输入联系人手机号码');
            isReturn = true;
        }
        else if (data.phone == '') {
            alert('请输入联系人电话号码');
            isReturn = true;
        }
        else if (data.addr == '') {
            alert('请输入公司地址');
            isReturn = true;
        }
        else if (data.licensecode == '') {
            alert('请输入营业执照号码');
            isReturn = true;
        }
        else if (data.orgcode == '') {
            alert('请输入组织机构代码');
            isReturn = true;
        }
        else if (data.type == 0) {
            alert('请选择公司属性');
            isReturn = true;
        }
        else if (data.industry == 0) {
            alert('请选择公司所属行业');
            isReturn = true;
        }
        else if (data.licensepic == '') {
            alert('请上传营业执照图片');
            isReturn = true;
        }
        else if (data.orgpic == '') {
            alert('请上传组织机构图片');
            isReturn = true;
        }
        if(isReturn) return false;
        $.post("/center/company-approve.html",data,function(data){
            if (data == -11)window.location.href="/center/index.html";
            else if (data == -1) {
                alert("用户不存在");
            }
            else {
                window.location.href="/center/company.html";
            }
        });
    });
    $("#reject").live("click",function(){
        
    });
    $("#haveGot").click(function(){
        $.post("/center/company-validuser.html",function(data){
            if (data == -11)window.location.href="/center/index.html";
            $("#subContent").html(data);
        });
    });
    $("#userSearch").live("click",function(){
        var condition = {};
        condition.name = $("#cname").val();
        condition.type = $("#type").val();
        condition.indus = $("#indus").val();
        condition.status = $("#status").val();
        $.post("/center/company-search.html",condition,function(data){
            if (data == -11)window.location.href="/center/index.html";
            $("#list").html(data);
        });
    });
    
    $("#userList ul li .clock").live("click",function(){
        var parent = this.parentNode;
        var usercode = $(parent).attr("usercode");
        $.post("/center/company-lock.html",{'ucode':usercode},function(data){
            if (data == -11)window.location.href="/center/index.html";
        });
        $(this).hide();
        $(parent).children(".cunlock").show();
    });
    $("#userList ul li .cunlock").live("click",function(){
        var parent = this.parentNode;
        var usercode = $(parent).attr("usercode");
        $.post("/center/company-lock.html",{'ucode':usercode},function(data){
            if (data == -11)window.location.href="/center/index.html";
        });
        $(this).hide();
        $(parent).children(".clock").show();
    });
    $("#userList ul li .details").live("click",function(){
        var parent = this.parentNode;
        var usercode = $(parent).attr("usercode");
        $.post("/center/company-details.html",{'ucode':usercode},function(data){
            if (data == -11)window.location.href="/center/index.html";
            $("#subContent").html(data);
        });
    });
//----company user management start ----

//-------activity setting start -------
    $("#acSave").click(function(){
        var obj = {};
        obj.do = 'save';
        obj.name = $("#name").val();
        obj.pic = $("#pic").attr("href");
        obj.intro = $("#intro").val();
        obj.storerate = $("#rate").val();
        obj.oprate = $("#oprate").val();
        obj.rule = new Object();
        obj.rule.rule1 = $("#rule1").val();
        obj.rule.rule2 = $("#rule2").val();
        obj.rule.rule3 = $("#rule3").val();
        obj.rule.rule4 = $("#rule4").val();
        $.post("/center/activity.html",obj,function(data){
            if (data == -11)window.location.href="/center/index.html";
            if (data == 1) {
                window.location.href="/center/activity.html";
            }
            else alert("设置失败!");
        });
        return false;
    });
    $("#uploadacPic").live("click",function(){
        activity.document.frm.uploadFile.click();
    });
//-------activity setting end -------
    $(".lock").live("click",function(){
        var obj = this.parentNode;
        var code = $(obj).attr("mcode");
        var status = $(this).attr("status");
        var currobj = this;
        $.post("/site/lockAccount.html",{'type':'manager','mcode':code,'status':status},function(data){
            if (data == -11)window.location.href="/center/index.html";
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
    $(".newCode").live("click",function(){
        var op = this.id;
        var obj = this.parentNode;
        var code = $(obj).attr("mcode");
        $.post("/site/newLogincode.html",{'mcode':code},function(data){
            if (data == -11)window.location.href="/center/index.html";
            if (data != -1) {
                var rowobj = obj.parentNode;
                $(rowobj).children("li").children("#code").html(data);
                return false;
            }
        });
    });
    $("#newBaseAcc").click(function(){
        var name = $("#name").val();
        $.post("/center/newAccount.html",{'name':name,'city':chosen,'type':'2'},function(data){
            if (data == -11)window.location.href="/center/index.html";
            if (data == -1) {
               alert("请输入名称");
            }
            else if (data == -2) {
                alert("请选择城市");
            }
            else {
                window.location.href="/center/setup.html";
            }
        });
    });
    $(".region").live("click",function(){
        var obj = this.parentNode;
        var code = $(obj).attr("mcode");
        chosen.splice(0,chosen.length);
        $.post("/center/setup.html",{'code':code,'do':'region'},function(data){
            if (data == -11)window.location.href="/center/index.html";
            $("#page").html(data);
         });
    });
    
    //select province and city center
    $("#province .pro").live("click",function(){
        var pcode = $(this).attr("pcode");
        var cities = city.getSubItems(pcode);
        cities = initCities(chosen,cities);
        $("#cities").html("");
        for (var key in cities) {
            var org = $("#cities").html();
            if (cities[key]['checked']) {
               var newli = '<li><input type="checkbox" class="licity" checked="true" code="'+ cities[key]['code'] +'"/>' + cities[key]['name']+"</li>";
            }
            else var newli = '<li><input type="checkbox" class="licity" code="'+ cities[key]['code'] +'"/>' + cities[key]['name']+"</li>";
            $("#cities").html( org + newli); 
        }
    });

    $(".licity").live("click",function(){
        var code = $(this).attr("code");
        if (this.checked == true) {
            for (var key in chosen) {
                if (chosen[key] == code) {
                    return;
                }
            }
            chosen.push(code);
        }
        else {
            for (var key in chosen) {
                if (chosen[key] == code) {
                    chosen.splice(key,1); 
                }
            }
        }
    });
    //end select province and city center

    //start big region.
    $("#newRegionAcc").live("click",function(){
        var name = $("#name").val();
        var mcode = $(this).attr("mcode");
        $.post("/center/newAccount.html",{'name':name,'city':chosen,'type':'3','mcode':mcode},function(data){
            if (data == -11)window.location.href="/center/index.html";
            if (data == -1) {
               alert("请输入名称");
            }
            else if (data == -2) {
                alert("请选择城市");
            }
            else {
                window.location.href="/center/setup.html";
            }
        });
    });
    $("#regionpro .pro").live("click",function(){
        var pcode = $(this).attr("pcode");
        var cities = city.getSubItems(pcode);
        cities = initCities(chosen,cities);
        $("#cities").html("");
        for (var key in regionCity) {
            for (var i in cities) {
                if(regionCity[key]['pcode'] == pcode && regionCity[key]['citycode'] == cities[i]['code'] ) {
                    var org = $("#cities").html();
                    if (cities[i]['checked']) {
                       var newli = '<li><input type="checkbox" class="licity" checked="true" code="'+ cities[i]['code'] +'"/>' + cities[i]['name']+"</li>";
                    }
                    else var newli = '<li><input type="checkbox" class="licity" code="'+ cities[i]['code'] +'"/>' + cities[i]['name']+"</li>";
                    $("#cities").html( org + newli); 
                }
            }
        }
    });
    $(".branch").live("click",function(){
        var obj = this.parentNode;
        var code = $(obj).attr("mcode");
        chosen.splice(0,chosen.length);
        $.post("/center/setup.html",{'code':code,'do':'branch'},function(data){
            if (data == -11)window.location.href="/center/index.html";
            $("#page").html(data);
         });
    });
    //end region.

    //start branch
    $("#newBranchAcc").live("click",function(){
        var name = $("#name").val();
        var mcode = $(this).attr("mcode");
       // console.log(chosen);
        $.post("/center/newAccount.html",{'name':name,'city':chosen,'type':'4','mcode':mcode},function(data){
            if (data == -11)window.location.href="/center/index.html";
            if (data == -1) {
               alert("请输入名称");
            }
            else if (data == -2) {
                alert("请选择城市");
            }
            else {
                //window.location.href="/center/setup.html";
            }
        });
    });

    $("#branchpro .pro").live("click",function(){
        var pcode = $(this).attr("pcode");
        var cities = city.getSubItems(pcode);
        cities = initCities(chosen,cities);
        $("#cities").html("");
        //console.log(cities);
        for (var key in branchCity) {
            for (var i in cities) {
                if(regionCity[key]['pcode'] == pcode) {
                    var org = $("#cities").html();
                    if (cities[i]['checked']) {
                       var newli = '<li><input type="checkbox" class="licity" checked="true" code="'+ cities[i]['code'] +'"/>' + cities[i]['name']+"</li>";
                    }
                    else var newli = '<li><input type="checkbox" class="licity" code="'+ cities[i]['code'] +'"/>' + cities[i]['name']+"</li>";
                    $("#cities").html( org + newli); 
                }
            }
        }
    });
    $(".newop, .newp, .newf").live("click",function(){
        var op = this.id;
        var obj = this.parentNode;
        var code = $(this).attr("code");
        var currobj = this;
        $.post("/site/newLogincode.html",{'do':'branch','mcode':code},function(data){
            if (data == -11)window.location.href="/center/index.html";
            if (data != -1) {
                var rowobj = obj.parentNode;
                if (currobj.class == 'newop') {
                    $(rowobj).children(".opcode").html(data);
                }
                else if (currobj.class == 'newp') {
                    $(rowobj).children(".pcode").html(data);
                }
                else if (currobj.class == 'newf') {alert("sadfasdf");
                    $(rowobj).children(".fcode").html(data);
                }
                return false;
            }
        });
    });
    function initCities(chosen,cities) {
        for (var key in cities) {
            for(var i in chosen) {
                if (chosen[i] == cities[key]['code']) {
                    cities[key]['checked'] = true;
                }
            }
        }

        return cities;
    }
});