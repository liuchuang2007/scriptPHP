/**
 *	@name	bindSeparatorEvent	删除关注
 *	@access	public
 *	@param	object  target  如果指定div对象则将新页填充到div中.
 */
function bindSeparatorEvent(url) {
    $(".pagebutton span a").bind('click',function(){
        var page = this.innerHTML;

        var activePage = $(".pagebutton span .active").html();
        if (page == '上一页') {
            if (this.className != 'front edge') {
                page = parseInt(activePage) - 1;
            }
            else return false;
        }
        else if (page == '下一页') {
            if (this.className != 'next edge') {
                page = parseInt(activePage) + 1;
            }
            else return false;
        }

        $.post(url + page, function(data){
            $(".pagebutton span a").unbind("click");
            if (data == 0) {alert('加载数据失败');}
            else $("#items").html(data);
        });
        
        return false;
   });
}