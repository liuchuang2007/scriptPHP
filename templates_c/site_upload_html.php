<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<script>
window.onload = function(){
   var obj = document.frm.uploadFile;
   obj.onchange=function(){
       if (obj.value == "") return;
       var path = obj.value;
       var ext = path.substr(path.lastIndexOf('.')+1);
       var extension = ext.toLowerCase();
       if (extension == 'png' ||extension == 'gif'||extension == 'jpeg'||extension == 'jpg' ) {
           document.frm.submit();
       }
       else {
          alert('文件类型有误');
       }
   };
};
</script>
</head>
<body>
<form name="frm" action="/site/upload.html<?php
echo $_obj['param'];
?>
" method="post" enctype="multipart/form-data">
<input type="file" id="uploadFile" name="uploadFile" />
</form>
</body>
</html>