
<?php
echo $_obj['department']['name'];
?>
大区名称：<input id="name" type="text">
<div>
  <div>统计城市：</div>
  <div id="regionpro">
  <?php
if (!empty($_obj['province'])){
if (!is_array($_obj['province']))
$_obj['province']=array(array('province'=>$_obj['province']));
$_tmp_arr_keys=array_keys($_obj['province']);
if ($_tmp_arr_keys[0]!='0')
$_obj['province']=array(0=>$_obj['province']);
$_stack[$_stack_cnt++]=$_obj;
foreach ($_obj['province'] as $rowcnt=>$province) {
$province['ROWCNT']=$rowcnt;
$province['ALTROW']=$rowcnt%2;
$province['ROWBIT']=$rowcnt%2;
$_obj=&$province;
?>
    <li><input type="checkbox" class="pro" pcode="<?php
echo $_obj['pcode'];
?>
"/><?php
echo $_obj['name'];
?>
</li>
  <?php
}
$_obj=$_stack[--$_stack_cnt];}
?>
  </div>
  <div id="cities"></div>
  <div class="clear"></div>
</div>
<input id="newRegionAcc" mcode="<?php
echo $_obj['mcode'];
?>
" value="生成操作账号" type="button"/>
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
            <a href="#" class="branch">分部管理</a>&nbsp;&nbsp;
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
<script>var regionCity = <?php
echo $_obj['city'];
?>
;</script>