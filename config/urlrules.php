<?php
return array(
    '/index.php'=>'site/index',
    '/index.html'=>'busi/index',
    '/store.html'=>'store/index',
    '/manager.html'=>'base/index',

    //business
    '/busi/<_a:(index|login|query|profile|register|search)>.html'=>'busi/<_a>',
    '/busi/search-<page:\w+>.html'=>'busi/<_a>',

    //site
    '/site/<_a:(index|login|authPic|logout|lockAccount|newLogincode|upload)>.html'=>'site/<_a>',
    '/site/index-<id:\w+>-<page:\w+>.html'=>'site/index',
    '/site/login-<id:\w+>-<page:\w+>.html'=>'site/login',

    //region
    '/region/<_a:(index|supplier|operation|cancel)>.html'=>'region/<_a>',
    
    //base
    '/base/<_a:(index|supplier|operation|cancel|newBranchAccount|search)>.html'=>'base/<_a>',
    //'/base/<_a:(index|login|operation)>-<id:\w+>.html'=>'base/<_a>',

    //store
    //'/store/<_a:(index|query|authCode|getProduct|saveRecord|search|login)>.html'=>'store/<_a>',
    '/store/<_a:(index|stat|cancel|confirm|recycle|authCode|getProduct|saveRecord)>.html'=>'store/<_a>',

    //center
    '/center/<_a:(index|operation|sponsor|company|setup|activity|newAccount|review|closeset)>.html'=>'center/<_a>',
    '/center/<_a:(index|operation|company)>-<do:\w+>.html' => 'center/<_a>',
    //branch
    //'/branch/<_a:(index|supplier|operation|setup|storeSearch|supplierSearch|newLogincode|opSearch)>.html'=>'branch/<_a>',
    '/branch/<_a:(index|supplier|operation|cancel|supplierset|storeset|departmentset|closeset|newAccount|allreturn|allcancel)>.html'=>'branch/<_a>',
);