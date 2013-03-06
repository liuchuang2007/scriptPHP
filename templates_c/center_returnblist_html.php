<div>
城市:<span><?php
echo $_obj['all']['cityname'];
?>
</span>&nbsp;
品类:<span><?php
echo $_obj['all']['catename'];
?>
</span>
品牌:<select id="brand">
  <option value="0">全部品牌</option>
  <?php
if (!empty($_obj['brands'])){
if (!is_array($_obj['brands']))
$_obj['brands']=array(array('brands'=>$_obj['brands']));
$_tmp_arr_keys=array_keys($_obj['brands']);
if ($_tmp_arr_keys[0]!='0')
$_obj['brands']=array(0=>$_obj['brands']);
$_stack[$_stack_cnt++]=$_obj;
foreach ($_obj['brands'] as $rowcnt=>$brands) {
$brands['ROWCNT']=$rowcnt;
$brands['ALTROW']=$rowcnt%2;
$brands['ROWBIT']=$rowcnt%2;
$_obj=&$brands;
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
<input type="button" value="载入数据" id="returnbSearch" citycode="<?php
echo $_obj['all']['citycode'];
?>
" stype="<?php
echo $_obj['all']['stype'];
?>
" dcode="<?php
echo $_obj['all']['dcode'];
?>
" cate="<?php
echo $_obj['all']['cate'];
?>
" brand="<?php
echo $_obj['all']['brand'];
?>
" />
</div>
<div id="list">
  <div id="query">
    <div id="qlist">
      <div></div>
      <div class="head">
        <ul>
         <li class="proid">品牌</li>
         <li class="proname">采购金额</li>
         <li class="procount">补贴金额</li>
         <li class="proprice">退货金额</li>
        </ul>
      </div>
  </div>
</div>
</div>