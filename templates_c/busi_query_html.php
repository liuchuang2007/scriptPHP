<div id="cancel">
购买统计查询:
  <div id="append_parent" style="position: fix">
  <script type="text/javascript" src="/js/script_calendar.js" charset="utf-8"  ></script>
  <input type="text" name="starttime" id="starttime" value="<?php
echo $_obj['starttime'];
?>
"  onclick="showcalendar(event,this,1,'<?php
echo $_obj['timestamp1'];
?>
','<?php
echo $_obj['timestamp2'];
?>
')" />
  至<input type="text" name="starttime" id="endtime" value="<?php
echo $_obj['endtime'];
?>
"  onclick="showcalendar(event,this,1,'<?php
echo $_obj['timestamp1'];
?>
','<?php
echo $_obj['timestamp2'];
?>
')" />
 <button id="search">载入数据</button>
 </div>
</div>
<div id="list">
  <div id="query">
    <div id="qlist">
      <div class="head">
        <ul>
          <li>采购代码</li>
          <li>申请日期</li>
          <li>使用日期</li>
          <li>采购门店</li>
          <li>采购金额</li>
          <li>补贴金额</li>
          <li>采购详单</li>
        </ul>
      </div>

    </div>
  </div>
</div>