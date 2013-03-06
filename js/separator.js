/**
 *	@name	bindSeparatorEvent	绑定分页
 *	@access	public
 *	@param	object  target  如果指定div对象则将新页填充到div中.
 */
function bindSeparatorEvent(data,fillTo,jump) {
    $( "#sepLinks a" ).die("click");
    $( "#sepLinks a" ).live('click',function(){
        var link = this.href;
        if (!link) {
            return false;
        }

        $.post(link,data, function(data){
            if (data == -11) {window.location.href=jump;}
            else $("#"+fillTo).html(data);
            return false;
        });
        return false;
   });
}