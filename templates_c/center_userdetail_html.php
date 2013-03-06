<div class="profile" id="details" usercode="<?php
echo $_obj['data']['usercode'];
?>
">
  <div class="title">基本信息</div>
  <ul>
    <li class="note">企业名称:</li>
    <li><input id="companyname" value="<?php
echo $_obj['data']['companyname'];
?>
" type="text" /></li>
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
    <li><input id="mobile" value="<?php
echo $_obj['data']['cellphone'];
?>
" type="text" /></li>
  </ul>
  <ul>
    <li class="note">电话:</li>
    <li><input id="phone" value="<?php
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
    <li ><span id="liName"><?php
echo $_obj['data']['licenseShow'];
?>
<a href='<?php
echo $_obj['data']['licensepic'];
?>
' id="licensePic" target='_blank'>查看</a></span></li>
    <li><input type="button" id="upLi" value="变更" /></li>
    <li><input type="text" id="licenseCode" value="<?php
echo $_obj['data']['licensecode'];
?>
" /></li>
  </ul>
  <ul>
    <li class="note">组织机构代码:</li>
    <li id="orgName" ><span><?php
echo $_obj['data']['orgShow'];
?>
<a href='<?php
echo $_obj['data']['orgpic'];
?>
' id="orgPic" target='_blank'>查看</a></span></li>
    <li><input type="button" id="upOrg" value="变更" /></li>
    <li><input type="text" id="orgCode" value="<?php
echo $_obj['data']['orgcode'];
?>
" /></li>
  </ul>
  <ul>
    <li class="note">注册资本:</li>
    <li>
      <select id="capitallevel">
        <?php
if (!empty($_obj['capitals'])){
if (!is_array($_obj['capitals']))
$_obj['capitals']=array(array('capitals'=>$_obj['capitals']));
$_tmp_arr_keys=array_keys($_obj['capitals']);
if ($_tmp_arr_keys[0]!='0')
$_obj['capitals']=array(0=>$_obj['capitals']);
$_stack[$_stack_cnt++]=$_obj;
foreach ($_obj['capitals'] as $rowcnt=>$capitals) {
$capitals['ROWCNT']=$rowcnt;
$capitals['ALTROW']=$rowcnt%2;
$capitals['ROWBIT']=$rowcnt%2;
$_obj=&$capitals;
?>
          <?php
if ($_obj['level'] == $_stack[$_stack_cnt-1]['data']['capitallevel']){
?>
            <option value="<?php
echo $_obj['level'];
?>
" selected><?php
echo $_obj['text'];
?>
</option>
          <?php
} else {
?>
            <option value="<?php
echo $_obj['level'];
?>
"><?php
echo $_obj['text'];
?>
</option>
          <?php
}
?>
        <?php
}
$_obj=$_stack[--$_stack_cnt];}
?>
      </select>
    </li>
  </ul>
</div>
<div class="profile">
  <div class="title">企业归类</div>
  <ul>
    <li class="note">企业性质:</li>
    <li>
      <select id="companyType">
        <option value="0">--请选择--</option>
        <?php
if (!empty($_obj['company_type'])){
if (!is_array($_obj['company_type']))
$_obj['company_type']=array(array('company_type'=>$_obj['company_type']));
$_tmp_arr_keys=array_keys($_obj['company_type']);
if ($_tmp_arr_keys[0]!='0')
$_obj['company_type']=array(0=>$_obj['company_type']);
$_stack[$_stack_cnt++]=$_obj;
foreach ($_obj['company_type'] as $rowcnt=>$company_type) {
$company_type['ROWCNT']=$rowcnt;
$company_type['ALTROW']=$rowcnt%2;
$company_type['ROWBIT']=$rowcnt%2;
$_obj=&$company_type;
?>
            <?php
if ($_obj['level'] == $_stack[$_stack_cnt-1]['data']['type']){
?>
            <option value="<?php
echo $_obj['level'];
?>
" selected><?php
echo $_obj['text'];
?>
</option>
            <?php
} else {
?>
            <option value="<?php
echo $_obj['level'];
?>
"><?php
echo $_obj['text'];
?>
</option>
            <?php
}
?>
         <?php
}
$_obj=$_stack[--$_stack_cnt];}
?>
       </select>
    </li>
  </ul>
  <ul>
    <li class="note">所属行业:</li>
    <li>
      <select id="companyIndus">
        <option value="0">--请选择--</option>
        <?php
if (!empty($_obj['indus'])){
if (!is_array($_obj['indus']))
$_obj['indus']=array(array('indus'=>$_obj['indus']));
$_tmp_arr_keys=array_keys($_obj['indus']);
if ($_tmp_arr_keys[0]!='0')
$_obj['indus']=array(0=>$_obj['indus']);
$_stack[$_stack_cnt++]=$_obj;
foreach ($_obj['indus'] as $rowcnt=>$indus) {
$indus['ROWCNT']=$rowcnt;
$indus['ALTROW']=$rowcnt%2;
$indus['ROWBIT']=$rowcnt%2;
$_obj=&$indus;
?>
            <?php
if ($_obj['id'] == $_stack[$_stack_cnt-1]['data']['industry']){
?>
            <option value="<?php
echo $_obj['id'];
?>
" selected><?php
echo $_obj['name'];
?>
</option>
            <?php
} else {
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
?>
         <?php
}
$_obj=$_stack[--$_stack_cnt];}
?>
       </select>
    </li>
  </ul>
</div>
<input  value="通过" type="button" id="approve"/>
<input id="reject" value="拒绝" type="button" />

<iframe name="upload"  src="/site/upload.html?do=license" width=0 height=0 style="border:0px;" scrolling=no frameborder=0></iframe>
<iframe name="org"  src="/site/upload.html?do=org" width=0 height=0 style="border:0px;" scrolling=no frameborder=0></iframe>