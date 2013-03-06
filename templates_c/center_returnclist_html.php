<div>
城市:<span><?php
echo $_obj['all']['cityname'];
?>
</span>
品类:<select id="cate">
  <option value="0">全部品类</option>
  <?php
if (!empty($_obj['cates'])){
if (!is_array($_obj['cates']))
$_obj['cates']=array(array('cates'=>$_obj['cates']));
$_tmp_arr_keys=array_keys($_obj['cates']);
if ($_tmp_arr_keys[0]!='0')
$_obj['cates']=array(0=>$_obj['cates']);
$_stack[$_stack_cnt++]=$_obj;
foreach ($_obj['cates'] as $rowcnt=>$cates) {
$cates['ROWCNT']=$rowcnt;
$cates['ALTROW']=$rowcnt%2;
$cates['ROWBIT']=$rowcnt%2;
$_obj=&$cates;
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
</select>
<input type="button" value="载入数据" id="returncSearch" citycode="<?php
echo $_obj['all']['citycode'];
?>
" stype="<?php
echo $_obj['all']['stype'];
?>
" dcode="<?php
echo $_obj['all']['dcode'];
?>
"/>
</div>
<div id="list">
  <div id="query">
    <div id="qlist">
      <div></div>
      <div class="head">
        <ul>
         <li class="proid">品类</li>
         <li class="proname">采购金额</li>
         <li class="procount">补贴金额</li>
         <li class="proprice">退货金额</li>
         <li class="prototal">明细</li>
        </ul>
      </div>
      <div class="lcontent" id="return">
        <!--  <ul>
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
         <li class="prototal"><a href="#" class="lastw">供应商明细</a></li>
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
       <!-- <ul>
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
         <li class="prototal"><a time="<?php
echo $_obj['dtime'];
?>
" class="sdlist" href="#">详情</a></li>
       </ul>-->
       <?php
}
$_obj=$_stack[--$_stack_cnt];}
?>
   </div>
  </div>
</div>
</div>