<div>
  <li><span>活动名称:</span><input type="text" value="<?php
echo $_obj['ac']['name'];
?>
" id="name"/></li>
  <li><span>活动广告图:</span><span id="showPic"><?php
echo $_obj['ac']['showname'];
?>
</span><a href="<?php
echo $_obj['ac']['pic'];
?>
" id="pic" target="_blank">查看</a>&nbsp;<a href="#" id="uploadacPic">上传</a>&nbsp;&nbsp;&nbsp;&nbsp;<span>图片尺寸W000 *  H000px</span></li>
</div>
<div>
  <span>补贴比例设置:</span>
  补贴<input type="text" id="rate" value="<?php
echo $_obj['ac']['storerate'];
?>
"/>%, 运营提成 <input type="text" id="oprate" value="<?php
echo $_obj['ac']['operationrate'];
?>
"/>%
</div>
<div>
  <span>采购频次设置:<span>
  <ul>
    <?php
if (!empty($_obj['applyrule'])){
if (!is_array($_obj['applyrule']))
$_obj['applyrule']=array(array('applyrule'=>$_obj['applyrule']));
$_tmp_arr_keys=array_keys($_obj['applyrule']);
if ($_tmp_arr_keys[0]!='0')
$_obj['applyrule']=array(0=>$_obj['applyrule']);
$_stack[$_stack_cnt++]=$_obj;
foreach ($_obj['applyrule'] as $rowcnt=>$applyrule) {
$applyrule['ROWCNT']=$rowcnt;
$applyrule['ALTROW']=$rowcnt%2;
$applyrule['ROWBIT']=$rowcnt%2;
$_obj=&$applyrule;
?>
    <li><?php
echo $_obj['intro'];
?>
<input type="text"  id="rule<?php
echo $_obj['id'];
?>
" value="<?php
echo $_obj['distance'];
?>
"/>天/次</li>
     <?php
}
$_obj=$_stack[--$_stack_cnt];}
?>
  </ul>
<li>
</div>
<div>
  <span>活动说明:<span>
  <ul>
    <li><textarea id="intro"><?php
echo $_obj['ac']['intro'];
?>
</textarea></li>
  </ul>
<li>
</div>
<input type="button" value="保存" id="acSave"/>
<iframe name="activity"  src="/site/upload.html?do=activityPic" width=0 height=0 style="border:0px;" scrolling=no frameborder=0></iframe>