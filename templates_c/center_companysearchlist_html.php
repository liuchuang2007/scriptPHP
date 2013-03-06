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
      <ul>
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
        <li><?php
echo $_obj['companyname'];
?>
</li>
        <li><?php
echo $_obj['type'];
?>
</li>
        <li><?php
echo $_obj['indus'];
?>
</li>
        <li class="narrow"><?php
echo $_obj['last_login'];
?>
</li>
        <li class="narrow"><?php
echo $_obj['apply_count'];
?>
</li>
        <li class="narrow"><?php
echo $_obj['use_count'];
?>
</li>
        <li class="narrow"><?php
echo $_obj['total'];
?>
</li>
        <li class="narrow"><?php
echo $_obj['return'];
?>
</li>
        <li usercode="<?php
echo $_obj['usercode'];
?>
"><span class="details">详情</span>&nbsp;
            <?php
if (!empty($_obj['locked'])){
?>
            <span class="cunlock">解锁</span>
            <span class="clock" style="display:none">锁定</span>
            <?php
} else {
?>
            <span class="clock">锁定</span>
            <span class="cunlock" style="display:none">解锁</span>
            <?php
}
?>
        </li>
      <?php
}
$_obj=$_stack[--$_stack_cnt];}
?>
      </ul>
    </div>
    <div id="sepLinks"><?php
echo $_obj['all']['links'];
?>
</div>
  </div>
</div>
<script type="text/javascript">
var para = <?php
echo $_obj['all']['param'];
?>
;
bindSeparatorEvent(para,'list','center/index.html');
</script>

