<?php
/*
	说明:例
	$links = getSepLinksString(1,10);
	$tpl->assign('pageLinks', $links);
	$tpl->output('---');
	在template中:
	<div id="vipCenter">
		<div id="sepLinks">
			{pageLinks}
		</div>
	</div>
	css定义:
	#sepLinks {
		display: block;
		width: 500px;
		margin: 0 auto;
		margin-top: 20px;
	}
	#sepLinks a {
		display: inline-block;
		width: 20px;
		margin: 0 5px 0 5px;
		float: right;
		height: 20px;
		line-height: 20px;
		text-align: center;
		border: 1px solid #AAAAAA;
	}
	#sepLinks a:hover {
		text-decoration: none;
	}
	#sepLinks .edage {
	    width: 50px;
	}
	#sepLinks span {
		float: right;
		display: inline-block;
	}
	#sepLinks .active {
	    display: inline-block;
	    width: 20px;
	    margin: 0 5px 0 5px;
	    float: right;
	    height: 20px;
	    line-height: 20px;
	    text-align: center;
	    border: 0px solid #AAAAAA;
	}
	
	Js定义:
	<script type="text/javascript">
	$("#sepLinks a").bind('click',function(){
		var page = this.innerHTML;
		var activePage = $("#sepLinks .active").html();

		if (page == '上一页') {
			if (this.className != 'edage disable') {
				page = parseInt(activePage) - 1;
			}
			else return;
		}
		else if (page == '下一页') {
			if (this.className != 'edage disable') {
				page = parseInt(activePage) + 1;
			}
			else return;
		}

		$.post('member.php?action=footmark&page=' + page, function(data){
			$("#sepLinks a").unbind("click");
			$(".vipCenter").html(data);
		});
	});
    </script>
 */
function getStartPage($currPage, $totalPage, $link_num_to_show) {
	/*if ($currPage <= 0)return 1;
	if ($currPage > $totalPage) return $totalPage;
	if ($currPage > 2) $currPage = $currPage - 2;
	else if ($currPage <= 2) return 1;
	if (($totalPage - $currPage) <= $link_num_to_show){
		if (($totalPage - $link_num_to_show) < 0) return 1;
		else return ($totalPage - $link_num_to_show) + 1;
	}
	return $currPage;*/
	if (($currPage<$link_num_to_show) || $totalPage <= $link_num_to_show)return 1;
	return $currPage - $link_num_to_show + 2;
}
function getSepLinksString($currPage,$totalPage, $perPage=10, $link_num_to_show=5) {
	if (!$totalPage)$totalPage = 1;
	$startPage = getStartPage($currPage,$totalPage,$link_num_to_show);

    //设置当前激活项的样式.
	if ($currPage == 1) {
		$sepStr = '&lt;<a href="#" class="front edge">上一页</a><i>|</i>';
	}
	else {
		$sepStr = '&lt;<a href="#" class="front">上一页</a><i>|</i>';
	}

	$items_to_show = 0; //显示项的个数计数。
	for ($i = $startPage; $i <= $totalPage; $i++) {
		if ($items_to_show >= $link_num_to_show) {
			$sepStr = $sepStr . '<a href="#" class="page">……</a>';
			$sepStr = $sepStr . '<a href="#" class="page">' . $totalPage . '</a>&nbsp;';
			break;
		}
		if ($i == $currPage) {
			$sepStr = $sepStr . '<a href="#" class="page active">' . $i . '</a>&nbsp;';// active
		}
		else {
			$sepStr = $sepStr . '<a href="#" class="page">' . $i . '</a>&nbsp;';
		}
		$items_to_show++;
	}
	if ($currPage == $totalPage) {
		$sepStr =  $sepStr.'<i>|</i><a href="#" class="next edge">下一页</a>&gt;'; 
	}
	else {
		$sepStr = $sepStr . '<i>|</i><a href="#" class="next">下一页</a>&gt;'; 
	}
	return $sepStr;
}