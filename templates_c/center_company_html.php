<div id="menu">
  <span class="active"><a href="/center/company.html">企业认证审核</a></span>
  <span id="haveGot">已有企业查询</span>
</div>
<div id="subContent">
<div id="list">
  <div id="query">
    <div id="qlist">
      <div class="head">
        <ul>
          <li>企业名称</li>
          <li>提交时间</li>
          <li>状态</li>
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
echo $_obj['companyname'];
?>
</li>
          <li><?php
echo $_obj['time'];
?>
</li>
          <li><?php
echo $_obj['status'];
?>
</li>
          <li class="op"><a href="#" class="reviewDetail" code="<?php
echo $_obj['usercode'];
?>
">审核</a></li>
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