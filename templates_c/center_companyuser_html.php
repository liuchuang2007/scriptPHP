<div id="company">
企业名称:<input type="input" name="condition" id="cname" />&nbsp;
企业性质:<select name="condition" id="type">
        <?php
if (!empty($_obj['company_type'])){
if (!is_array($_obj['company_type']))
$_obj['company_type']=array(array('company_type'=>$_obj['company_type']));
$_tmp_arr_keys=array_keys($_obj['company_type']);
if ($_tmp_arr_keys[0]!='0')
$_obj['company_type']=array(0=>$_obj['company_type']);
$_stack[$_stack_cnt++]=$_obj;
foreach ($_obj['company_type'] as $rowcnt=>$company_type) {
$company_type['ROWCNT']=$rowcnt;
$company_type['ALTROW']=$rowcnt%2;
$company_type['ROWBIT']=$rowcnt%2;
$_obj=&$company_type;
?>
        <option value="<?php
echo $_obj['level'];
?>
"><?php
echo $_obj['text'];
?>
</option>
        <?php
}
$_obj=$_stack[--$_stack_cnt];}
?>
        </select>&nbsp;
所属行业:<select name="condition" id="indus">
        <?php
if (!empty($_obj['indus'])){
if (!is_array($_obj['indus']))
$_obj['indus']=array(array('indus'=>$_obj['indus']));
$_tmp_arr_keys=array_keys($_obj['indus']);
if ($_tmp_arr_keys[0]!='0')
$_obj['indus']=array(0=>$_obj['indus']);
$_stack[$_stack_cnt++]=$_obj;
foreach ($_obj['indus'] as $rowcnt=>$indus) {
$indus['ROWCNT']=$rowcnt;
$indus['ALTROW']=$rowcnt%2;
$indus['ROWBIT']=$rowcnt%2;
$_obj=&$indus;
?>
        <option value="<?php
echo $_obj['id'];
?>
"><?php
echo $_obj['name'];
?>
</option>
        <?php
}
$_obj=$_stack[--$_stack_cnt];}
?>
        </select>&nbsp;
采购状态:<select name="condition" id="status">
        <option value="1" selected>已采购</option>
        <option value="0">未采购</option>
        </select>&nbsp;

<input type="button" value="载入数据" id="userSearch" />
</div>
<div id="list">
  <div id="query">
    <div id="qlist">
      <div class="head">
        <ul>
          <li>企业名称</li>
          <li>企业性质</li>
          <li>所属行业</li>
          <li class="narrow">最近登录</li>
          <li class="narrow">申请代码次数</li>
          <li class="narrow">使用代码次数</li>
          <li class="narrow">累计采购金额</li>
          <li class="narrow">累计补贴</li>
          <li>操作</li>
        </ul>
      </div>
      <div class="lcontent" id="userList">
      </div>
    </div>
  </div>
</div>