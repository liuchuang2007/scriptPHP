<div id="confirm">
补贴统计查询:
<input type="radio" id="code" />当天&nbsp;
<input type="radio" value="button" class="search" />本周&nbsp;
<input type="radio" value="button" class="search" />上周&nbsp;
<input type="radio" value="button" class="search" />上月&nbsp;
<input type="radio" value="button" class="search" />历月&nbsp;
<input type="button" value="载入数据" class="载入数据" />
</div>
<div id="list">
  <div id="query">
    <div id="qlist">
      <div><?php
echo $_obj['store']['name'];
?>
&nbsp;<?php
echo $_obj['all']['acname'];
?>
清单</div>
      <div class="head">
        <ul>
         <li class="proid">采购日期</li>
         <li class="proname">采购金额</li>
         <li class="procount">补贴金额</li>
         <li class="proprice">退货金额</li>
         <li class="prototal">详单</li>
        </ul>
      </div>
      <div class="lcontent">
        <ul>
         <li class="proid">合计</li>
         <li class="proname"><?php
echo $_obj['all']['total'];
?>
</li>
         <li class="procount"><?php
echo $_obj['all']['return'];
?>
</li>
         <li class="proprice"><?php
echo $_obj['all']['cancel'];
?>
</li>
         <li class="prototal">----</li>
       </ul>
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
         <li class="proid"><?php
echo $_obj['date'];
?>
</li>
         <li class="proname"><?php
echo $_obj['total'];
?>
</li>
         <li class="procount"><?php
echo $_obj['return'];
?>
</li>
         <li class="proprice"><?php
echo $_obj['cancel'];
?>
</li>
         <li class="prototal"><a dtime="<?php
echo $_obj['dtime'];
?>
" class="blist" href="#">详情</a></li>
       </ul>
       <?php
}
$_obj=$_stack[--$_stack_cnt];}
?>
   </div>
  </div>
</div>
</div>