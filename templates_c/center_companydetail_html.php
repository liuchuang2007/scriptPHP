<div class="profile" id="details" usercode="<?php
echo $_obj['data']['usercode'];
?>
">
  <div class="title">基本信息</div>
  <ul>
    <li class="note">企业名称:</li>
    <li><?php
echo $_obj['data']['companyname'];
?>
</li>
  </ul>
  <ul>
    <li class="note">联系人:</li>
    <li><?php
echo $_obj['data']['contactname'];
?>
</li>
  </ul>
  <ul>
    <li class="note">手机:</li>
    <li><?php
echo $_obj['data']['cellphone'];
?>
</li>
  </ul>
  <ul>
    <li class="note">电话:</li>
    <li><?php
echo $_obj['data']['telephone'];
?>
</li>
  </ul>
  <ul>
    <li class="note">电子邮件:</li>
    <li><?php
echo $_obj['data']['email'];
?>
</li>
  </ul>
  <ul>
    <li class="note">联系地址:</li>
    <li><?php
echo $_obj['data']['addr'];
?>
</li>
  </ul>
</div>
<div class="profile">
  <div class="title">企业认证</div>
  <ul>
    <li class="note">营业执照号:</li>
    <li ><?php
echo $_obj['data']['licenseShow'];
?>
<a href='<?php
echo $_obj['data']['licensepic'];
?>
' id="licensePic" target='_blank'>查看</a></li>
  </ul>
  <ul>
    <li class="note">组织机构代码:</li>
    <li id="orgName" ><?php
echo $_obj['data']['orgShow'];
?>
<a href='<?php
echo $_obj['data']['orgpic'];
?>
' id="orgPic" target='_blank'>查看</a></li>
  </ul>
  <ul>
    <li class="note">注册资本:</li>
    <li><?php
echo $_obj['data']['capitaltext'];
?>
</li>
  </ul>
</div>
<div class="profile">
  <div class="title">企业归类</div>
  <ul>
    <li class="note">企业性质:</li>
    <li><?php
echo $_obj['data']['typetext'];
?>
</li>
  </ul>
  <ul>
    <li class="note">所属行业:</li>
    <li><?php
echo $_obj['data']['industext'];
?>
</li>
  </ul>
</div>
<input  value="返回" type="button"/>

<iframe name="upload"  src="/site/upload.html?do=license" width=0 height=0 style="border:0px;" scrolling=no frameborder=0></iframe>
<iframe name="org"  src="/site/upload.html?do=org" width=0 height=0 style="border:0px;" scrolling=no frameborder=0></iframe>