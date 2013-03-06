var chosenY = 0;
var chosenM = 0;
var week = [0,1,2,3,4,5,6];
var globalChoice = [];
function getMonthStr(year,month,type) {
    if (type == 'next') {
        month = month + 1;
    }
    else if (type == 'prev') {
        month = month - 1;
    }
    else {
        newM = month;
    }
    var date = new Date(year,month,1);
    chosenM = date.getMonth();
    chosenY = date.getFullYear();

    month = chosenM + 1; 
    return chosenY + '年' + month + '月';
}
function initDaysInCurrMonth(year,month) {
    var days = [];
    var date1 = new Date(year,month,1);
    var date2 = new Date(year,month + 1,1,-1);
    var start = date1.getDate();
    var end = date2.getDate();
    for (;start <= end; start++) {
        days.push(start);
    }

    //write to right location.
    var day = date1.getDay();
    for (var item in week) {
        if (week[item] != day) {
            var html = $("#calendar .daylist").html() + '<span class="day"></span>';
            $("#calendar .daylist").html(html);
        }
        else break;
    }
    for (var day in days) {
         var html = $("#calendar .daylist").html() + '<span class="day">'+days[day]+'</span>';
         $("#calendar .daylist").html(html);
    }
}
function initCalendar(id) {
    var date = new Date();
    var month = date.getMonth();
    var year = date.getFullYear();
    var str = getMonthStr(year,month);
    var html = '<div id="calendar">'+
               '<div class="date">' +
                  '<span class="prev"><<</span><span class="curr">' + str + '</span><span class="next">>></span>' +
               '</div><div class="clear"></div>'+
               '<div class="week">' +
                   '<ul><li>星期日</li><li>星期一</li><li>星期二</li><li>星期三</li><li>星期四</li><li>星期五</li><li>星期六</li></ul>' +
                '</div><div class="clear"></div>'+
                '<div class="daylist"></div>' +
                '</div>';
    $("#" +id).html(html);
    initDaysInCurrMonth(year,month);
}

$(document).ready(function(){
    initCalendar("calendar1");
    $("#calendar .date span").live("click",function(){
        if (this.className == 'prev' || this.className == 'next') {
            $("#calendar .daylist").html("");
            if (this.className == 'prev')
                var str = getMonthStr(chosenY,chosenM,'prev');
            else {
                var str = getMonthStr(chosenY,chosenM,'next');
            }
            $("#calendar .date .curr").html(str);
            initDaysInCurrMonth(chosenY,chosenM);
        }

        return false;
    });
    $("#calendar .daylist .day").live("click",function(){
        var day = $(this).html();
        var date = new Date(chosenY,chosenM,day);
        var time = date.getTime()/1000;
        if (!$(this).hasClass("chosen")) {
            $(this).addClass("chosen");
            if (!hasChosen(time)) {
                globalChoice.push(time);
            }
        }
        else {
            $(this).removeClass("chosen")
            removeChosen(time);
        }

        return false;
    });
    function hasChosen(item) {
        for (var i in globalChoice){
            if (globalChoice[i] == item)return true;
        }
        return false;
    };
    function removeChosen(item) {
        for (var i in globalChoice){
            if (globalChoice[i] == item) {
                globalChoice.splice(i,1);
            }
        }
        return false;
    };
});

