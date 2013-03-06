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
<div id="ssss"></div>
  <div class="header">
  <img src="/images/logo.png"/><span id="logout">退出</span>
  <div id="departname"><h2><?php
echo $_obj['depart']['name'];
?>
</h2></div>
  <hr class="line">
  </div>
  <div class="main">
  <div id="menu">
  <?php
if (!empty($_obj['menu'])){
?>
  <?php
if (!empty($_obj['menu'])){
if (!is_array($_obj['menu']))
$_obj['menu']=array(array('menu'=>$_obj['menu']));
$_tmp_arr_keys=array_keys($_obj['menu']);
if ($_tmp_arr_keys[0]!='0')
$_obj['menu']=array(0=>$_obj['menu']);
$_stack[$_stack_cnt++]=$_obj;
foreach ($_obj['menu'] as $rowcnt=>$menu) {
$menu['ROWCNT']=$rowcnt;
$menu['ALTROW']=$rowcnt%2;
$menu['ROWBIT']=$rowcnt%2;
$_obj=&$menu;
?>
  <?php
if (!empty($_obj['flag'])){
?>
    <span class="active"><a href="<?php
echo $_obj['link'];
?>
"><?php
echo $_obj['name'];
?>
</a></span>
  <?php
} else {
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
?>
  <?php
}
$_obj=$_stack[--$_stack_cnt];}
?>
  <hr class="line">
  <?php
}
?>
  </div>
  <?php
echo $_obj['content'];
?>

  </div>
  <div class="footer">
    <hr class="line">
    <span class="notice">Copyright @2011 Ji2ji.com Inc. All Rights Reserved. 城市消费 版权所有</span>
  </div>
</body>
</html>