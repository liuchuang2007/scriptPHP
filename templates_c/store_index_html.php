<div id="info">
补贴代码:<input type="text" id="code" /><span id="codetxt"></span><br />
<input type="button" value="使用" id="use"/><input type="button" value="取消使用" id="cuse"/>
</div>
<div class="title"><span id="company"></span>&nbsp;<span id="acname"></span><br />
<span>日期:<?php
echo $_obj['currdate'];
?>
</span>
</div>
<div id="product">
<li>商品ID:<input type="text" id="pid" disabled="disabled"/></li>
<li>品名:<span id="pname"></span></li>
<li>数量:<input type="text" id="num" disabled="disabled"/></li>
<li>单价:<input type="text" id="price"/></li>
<li>发票价:<input type="text" id="billmoney"/></li>
<li>金额:<span id="total"></span></li>
<li>发票号:<input type="text" id="pbill"/></li>
<input type="button" value="添加商品" id="add"/>
</div>
<div id="inputlist">
<div class="head">
<ul>
<li class="proid">商品ID</li>
<li class="proname">品名</li>
<li class="procount">数量</li>
<li class="probillmoney">发票价</li>
<li class="proprice">单价</li>
<li class="prototal">金额</li>
<li class="probill">发票号</li>
</ul>
</div>
<div class="lcontent"></div>
<input type="button" id="submit" value="提交清单"/>
</div>