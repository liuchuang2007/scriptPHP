<script src="/js/city.js"></script>
<div id="page">
卖场总部名称：<input id="name" type="text">
<div>
  <div>统计城市：</div>
  <div id="province">
  </div>
  <div id="cities"></div>
  <div class="clear"></div>
</div>
<input id="newBaseAcc" value="生成操作账号" type="button"/>
<hr />
<div id="list">
  <div id="query">
    <div id="qlist">
      <div class="head">
        <ul>
          <li>账号名称</li>
          <li>账号</li>
          <li class="op">操作</li>
        </ul>
      </div>
      <div class="lcontent">
        <?php
if (!empty($_obj['data'])){
if (!is_array($_obj['data']))
$_obj['data']=array(array('data'=>$_obj['data']));
$_tmp_arr_keys=array_keys($_obj['data']);
if ($_tmp_arr_keys[0]!='0')
$_obj['data']=array(0=>$_obj['data']);
$_stack[$_stack_cnt++]=$_obj;
foreach ($_obj['data'] as $rowcnt=>$data) {
$data['ROWCNT']=$rowcnt;
$data['ALTROW']=$rowcnt%2;
$data['ROWBIT']=$rowcnt%2;
$_obj=&$data;
?>
        <ul>
          <li><?php
echo $_obj['name'];
?>
</li>
          <li><span id="code"><?php
echo $_obj['logincode'];
?>
</span></li>
          <li class="op" mcode="<?php
echo $_obj['mcode'];
?>
">
            <?php
if (!empty($_obj['status'])){
?>
            <a href="#" class="lock" status="1">锁定</a>&nbsp;&nbsp;
            <?php
} else {
?>
            <a href="#" class="lock" status="2">解锁</a>&nbsp;&nbsp;
            <?php
}
?>
            <a href="#" class="newCode">生成新账号</a>&nbsp;&nbsp;
            <a href="#" class="region">大区管理</a>&nbsp;&nbsp;
          </li>
        </ul>
        <?php
}
$_obj=$_stack[--$_stack_cnt];}
?>
      </div>
    </div>
  </div>
</div>
</div>
<script>
var chosen = [];
var provinces = city.getSubItems(1);
for (var key in provinces) {
    var org = $("#province").html();
    var newli = '<li><input type="checkbox" class="pro" pcode="'+ provinces[key]['code'] +'"/>' + provinces[key]['name']+"</li>";
    $("#province").html( org + newli); 
}
</script>
