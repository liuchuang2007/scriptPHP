<div class="profile">
  <div class="title">基本信息</div>
  <ul>
    <li class="note">企业名称:</li>
    <li><span><?php
echo $_obj['data']['companyname'];
?>
</span></li>
  </ul>
  <ul>
    <li class="note">联系人:</li>
    <li><input id="contact" value="<?php
echo $_obj['data']['contactname'];
?>
" type="text" /></li>
  </ul>
  <ul>
    <li class="note">手机:</li>
    <li><input id="cellphone" value="<?php
echo $_obj['data']['cellphone'];
?>
" type="text" /></li>
  </ul>
  <ul>
    <li class="note">电话:</li>
    <li><input id="tel" value="<?php
echo $_obj['data']['telephone'];
?>
" type="text" /></li>
  </ul>
  <ul>
    <li class="note">电子邮件:</li>
    <li><input id="email" value="<?php
echo $_obj['data']['email'];
?>
" type="text" /></li>
  </ul>
  <ul>
    <li class="note">联系地址:</li>
    <li><input id="addr" value="<?php
echo $_obj['data']['addr'];
?>
" type="text" /></li>
  </ul>
</div>
<div class="profile">
  <div class="title">企业认证</div>
  <ul>
    <li class="note">营业执照号:</li>
    <li id="license"><span id="liName"><?php
echo $_obj['data']['licenseShow'];
?>
<a href='<?php
echo $_obj['data']['licensepic'];
?>
' target='_blank'>查看</a></span></li>
    <li><input type="button" id="upLi" value="变更" /></li>
  </ul>
  <ul>
    <li class="note">组织机构代码:</li>
    <li><span id="orgName"><?php
echo $_obj['data']['orgShow'];
?>
<a href='<?php
echo $_obj['data']['orgpic'];
?>
' target='_blank'>查看</a></span></li>
    <li><input type="button" id="upOrg" value="变更" /></li>
  </ul>
  <ul>
    <li class="note">注册资本:</li>
    <li><?php
echo $_obj['data']['capitallevel'];
?>
</li>
  </ul>
</div>
<div class="profile">
  <div class="title">账户信息</div>
  <ul>
    <li class="note">账号:</li>
    <li><span><?php
echo $_obj['data']['loginname'];
?>
</span></li>
  </ul>
  <ul>
    <li class="note">原密码:</li>
    <li><input id="oldpwd"  type="text" /></li>
  </ul>
  <ul>
    <li class="note">新密码:</li>
    <li><input id="newpwd"  type="text" /></li>
  </ul>
  <ul>
    <li class="note">确认密码:</li>
    <li><input id="confirmpwd" type="text" /></li>
  </ul>
</div>
<input id="save" value="保存" type="button" />
<iframe name="upload"  src="/site/upload.html?do=updateli" width=0 height=0 style="border:0px;" scrolling=no frameborder=0></iframe>
<iframe name="org"  src="/site/upload.html?do=activityPic" width=0 height=0 style="border:0px;" scrolling=no frameborder=0></iframe>