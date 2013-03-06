<?php
return array(
    array('type'=>CENTER_TYPE,
          'items'=>array(
              array('action'=>'index','name'=>'补贴统计','link'=>'/center/index.html'),
              //array('action'=>'supplier','name'=>'供应商补贴统计','link'=>'/center/supplier.html'),
              array('action'=>'operation','name'=>'运营费用统计','link'=>'/center/operation.html'),
              array('action'=>'activity','name'=>'活动设置','link'=>'/center/activity.html'),
              array('action'=>'company','name'=>'企业管理','link'=>'/center/company.html'),
              array('action'=>'setup','name'=>'管理设置','link'=>'/center/setup.html'),
              
              array('action'=>'closeset','name'=>'节假日设置','link'=>'/center/closeset.html'),
              array('action'=>'storeblacklist','name'=>'门店黑名单','link'=>'/center/storeblacklist.html'),
              array('action'=>'companyblacklist','name'=>'企业黑名单','link'=>'/center/companyblacklist.html'),
          ),
    ),
    array('type'=>BASE_TYPE,
              'items'=>array(
                  array('action'=>'index','name'=>'补贴统计','link'=>'/base/index.html'),
                  array('action'=>'operation','name'=>'运营费用统计','link'=>'/base/operation.html'),
                  array('action'=>'supplier','name'=>'供应商统计','link'=>'/base/supplier.html'),
                  array('action'=>'cancel','name'=>'退货统计','link'=>'/base/cancel.html')
              ),
    ),
    array('type'=>REGION_TYPE,
              'items'=>array(
                  array('action'=>'index','name'=>'补贴统计','link'=>'/region/index.html'),
                  array('action'=>'operation','name'=>'运营费用统计','link'=>'/region/operation.html'),
                  array('action'=>'supplier','name'=>'供应商统计','link'=>'/region/supplier.html'),
                  array('action'=>'cancel','name'=>'退货统计','link'=>'/region/cancel.html')
              ),
    ),
        array('type'=>BRANCH_TYPE,
             'items'=> array(
                 array('mtype'=>OPERATION_ACCOUNT,
                     'items'=>array(
                         array('action'=>'index','name'=>'补贴统计','link'=>'/branch/index.html'),
                         array('action'=>'operation','name'=>'运营费用统计','link'=>'/branch/operation.html'),
                         array('action'=>'cancel','name'=>'退货统计','link'=>'/branch/cancel.html'),
                         array('action'=>'supplierset','name'=>'供应商设置','link'=>'/branch/supplierset.html'),
                         array('action'=>'supplier','name'=>'供应商统计','link'=>'/branch/supplier.html'),
                         array('action'=>'storeset','name'=>'门店管理','link'=>'/branch/storeset.html'),
                         array('action'=>'departmentset','name'=>'部门管理','link'=>'/branch/departmentset.html'),
                         array('action'=>'closeset','name'=>'国定假日关闭','link'=>'/branch/closeset.html')
                     ),
                 ),
                 array('mtype'=>PURCHASE_ACCOUNT,
                     'items'=>array(
                         array('action'=>'operation','name'=>'运营费用统计','link'=>'/branch/operation.html'),
                         array('action'=>'cancel','name'=>'退货统计','link'=>'/branch/cancel.html'),
                         array('action'=>'supplier','name'=>'供应商统计','link'=>'/branch/supplier.html'),
                     ),
                 ),
                 array('mtype'=>FINANCE_ACCOUNT,
                     'items'=>array(
                         array('action'=>'allreturn','name'=>'补贴发放汇总','link'=>'/branch/allreturn.html'),
                         array('action'=>'allcancel','name'=>'退货回收汇总','link'=>'/branch/allcancel.html'),
                         array('action'=>'operation','name'=>'运营费用统计','link'=>'/branch/operation.html'),
                         array('action'=>'supplier','name'=>'供应商统计','link'=>'/branch/supplier.html')
                     ),
                 ),
                 array('mtype'=>DEPARTMENT_ACCOUNT,
                     'items'=>array(
                         array('action'=>'index','name'=>'补贴统计','link'=>'/branch/index.html'),
                         array('action'=>'operation','name'=>'运营费用统计','link'=>'/branch/operation.html'),
                         array('action'=>'supplier','name'=>'供应商统计','link'=>'/branch/supplier.html'),
                         array('action'=>'cancel','name'=>'退货统计','link'=>'/branch/cancel.html')
                     ),
                 ),
             ),
        ),
        array('type'=>STORE_TYPE,
             'items'=> array(
                 array('mtype'=>STORE_SERVICE_ACCOUNT,
                     'items'=>array(
                         array('action'=>'index','name'=>'补贴发放登记','link'=>'/store/index.html')
                     ),
                 ),
                 array('mtype'=>STORE_BOSS_ACCOUNT,
                     'items'=>array(
                         array('action'=>'stat','name'=>'补贴统计','link'=>'/store/stat.html'),
                         array('action'=>'cancel','name'=>'退货统计','link'=>'/store/cancel.html')
                     ),
                 ),
                 array('mtype'=>STORE_FINANCE_ACCOUNT,
                     'items'=>array(
                         array('action'=>'confirm','name'=>'补贴发放确认','link'=>'/store/confirm.html'),
                         array('action'=>'recycle','name'=>'退货回收','link'=>'/store/recycle.html')
                     ),
                 ),
             ),
        ),
        array('type'=>BUSI_TYPE,
              'items'=>array(
                  array('action'=>'index','name'=>'代码采购申请','link'=>'/busi/index.html'),
                  array('action'=>'query','name'=>'购买统计','link'=>'/busi/query.html'),
                  array('action'=>'profile','name'=>'企业档案','link'=>'/busi/profile.html')
              ),
        ),
   );