<input type="button" id="getcode" value="申请采购代码" />
<div id="applyres">
距下次可申请还有<?php
echo $_obj['leftdays'];
?>
天限期<br />
<?php
if (!empty($_obj['codes'])){
if (!is_array($_obj['codes']))
$_obj['codes']=array(array('codes'=>$_obj['codes']));
$_tmp_arr_keys=array_keys($_obj['codes']);
if ($_tmp_arr_keys[0]!='0')
$_obj['codes']=array(0=>$_obj['codes']);
$_stack[$_stack_cnt++]=$_obj;
foreach ($_obj['codes'] as $rowcnt=>$codes) {
$codes['ROWCNT']=$rowcnt;
$codes['ALTROW']=$rowcnt%2;
$codes['ROWBIT']=$rowcnt%2;
$_obj=&$codes;
?>
<?php
if (!empty($_obj['status'])){
?>
<?php
echo $_obj['ctime'];
?>
采购代码:&nbsp;<?php
echo $_obj['buycode'];
?>
&nbsp;已使用<br />
<?php
} else {
?>
<?php
echo $_obj['ctime'];
?>
采购代码:&nbsp;<?php
echo $_obj['buycode'];
?>
&nbsp;未使用<br />
<?php
}
?>
<?php
}
$_obj=$_stack[--$_stack_cnt];}
?>
</div>