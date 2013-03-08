<?php
return array(
    '/index.html'=>'site/index',
	'/site/intro.html'=>'site/intro',
	'/site/<_a:(index|intro)>.html'=>'site/<_a>',
	'/site/<_a:(index|intro)>/<test:\w+>.html'=>'site/<_a>',//here w+ -- word; d+ -- number
	
);