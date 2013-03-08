<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title><?php
echo $_obj['name'];
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

  <div class="main">
    <div class="mainmenu">
		<span><a href="<?php echo Application::$app->urlManager->createUrl(array('module'=>'site','action'=>'index'));?>">HOME</a></span>
		<span><a href="<?php echo Application::$app->urlManager->createUrl(array('module'=>'site','action'=>'intro'));?>">ABOUT</a></span>
    </div>
	<div class="content"><?php
echo $_obj['content'];
?>
</div>
  </div>
  <div class="footer">
    <span class="notice">Copyright 2013@scriptPHP </span>
  </div>
</body>
</html>