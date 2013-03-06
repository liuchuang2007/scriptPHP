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
    <div class="lcontent" id="return">
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
echo $_obj['name'];
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
    </ul>
    <?php
}
$_obj=$_stack[--$_stack_cnt];}
?>
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