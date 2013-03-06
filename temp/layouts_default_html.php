<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title><?php
echo $_obj['title'];
?>
</title>
<link rel="stylesheet" type="text/css" href="/css/main.css"/>
<script src="/js/jquery.min.js"></script>
<script src="/js/site.js"></script>
<script src="/js/separator.js"></script>
<?php
echo $_obj['css'];
?>

<?php
echo $_obj['js'];
?>

</head>
<body>
  <div class="header">
	<img alt="scriptPHP" src="/images/logo.png"/>
  </div>
  <div class="mainmenu">
	<?php
if (!empty($_obj['sysmenu'])){
?>
	  <?php
if (!empty($_obj['sysmenu'])){
if (!is_array($_obj['sysmenu']))
$_obj['sysmenu']=array(array('sysmenu'=>$_obj['sysmenu']));
$_tmp_arr_keys=array_keys($_obj['sysmenu']);
if ($_tmp_arr_keys[0]!='0')
$_obj['sysmenu']=array(0=>$_obj['sysmenu']);
$_stack[$_stack_cnt++]=$_obj;
foreach ($_obj['sysmenu'] as $rowcnt=>$sysmenu) {
$sysmenu['ROWCNT']=$rowcnt;
$sysmenu['ALTROW']=$rowcnt%2;
$sysmenu['ROWBIT']=$rowcnt%2;
$_obj=&$sysmenu;
?>
			<span><a href="<?php
echo $_obj['link'];
?>
"><?php
echo $_obj['name'];
?>
</a></span>
      <?php
}
$_obj=$_stack[--$_stack_cnt];}
?>
    <?php
}
?>
  </div>
  <div class="main"><?php
echo $_obj['content'];
?>
</div>
  <div class="footer">
    <span class="notice">Copyright 2013@scriptPHP </span>
  </div>
</body>
</html>