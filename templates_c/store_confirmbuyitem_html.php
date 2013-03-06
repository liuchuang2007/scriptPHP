<div class="intro">
<span>采购单位:<?php
echo $_obj['info']['company'];
?>
</span>&nbsp;<span>采购日期:<?php
echo $_obj['info']['date'];
?>
</span> 
</div>
<div id="list">
  <div id="query">
    <div id="qlist">
      <div class="head">
        <ul>
          <li>品名</li>
          <li>数量</li>
          <li>发票价</li>
          <li>金额</li>
          <li>补贴金额</li>
          <li>发票号</li>
          <li>发票号确认</li>
        </ul>
      </div>
      <div class="lcontent">
        <ul>
          <li>合计</li>
          <li><?php
echo $_obj['all']['count'];
?>
</li>
          <li>----</li>
          <li><?php
echo $_obj['all']['total'];
?>
</li>
          <li><?php
echo $_obj['all']['money'];
?>
</li>
          <li>----</li>
          <li>----</li>
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
          <li><?php
echo $_obj['pname'];
?>
</li>
          <li><?php
echo $_obj['count'];
?>
</li>
          <li><?php
echo $_obj['billmoney'];
?>
</li>
          <li><?php
echo $_obj['total'];
?>
</li>
          <li><?php
echo $_obj['money'];
?>
</li>
          <li><?php
echo $_obj['billcode'];
?>
</li>
          <li><a class="right">确认</a>&nbsp;<a class="save" style="display:none;">保存</a>&nbsp;<a class="modi">修改</a></li>
        </ul>
        <?php
}
$_obj=$_stack[--$_stack_cnt];}
?>
      </div>
    </div>
  </div>
</div>
<div><input type="button" id="confirmBill" code="<?php
echo $_obj['code'];
?>
" value="补贴发放确认"/>补贴发放必须在发票盖5%补贴章</div>