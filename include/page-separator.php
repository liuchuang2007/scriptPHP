<?php
function getStartPage($currPage, $totalPage, $link_num_to_show) {
	if (($currPage<$link_num_to_show) || $totalPage <= $link_num_to_show)return 1;
	return $currPage - $link_num_to_show + 2;
}
function getSepLinksString($currPage,$totalPage, $perPage=10, $link_num_to_show=5) {
	$baseurl = trim($_SERVER[SCRIPT_NAME]);
	if ($totalPage < 2)return '';
    $startPage = getStartPage($currPage,$totalPage,$link_num_to_show);
    //设置当前激活项的样式.
	if ($currPage == 1) {
		$sepStr = '<a class="edage disable">上一页</a>';
	}
	else {
		$last = $currPage - 1;
		$sepStr = '<a class="edage" href="'.$baseurl.'?page='.$last.'">上一页</a>';
	}

	$items_to_show = 0; //显示项的个数计数。
	for ($i = $startPage; $i <= $totalPage; $i++) {
		if ($items_to_show >= $link_num_to_show) {
			$sepStr = '<span>……</span>'. $sepStr;
			$sepStr = '<a>' . $totalPage . '</a>'. $sepStr;
			break;
		}
		if ($i == $currPage) {
			$sepStr = '<a class="active" href="'.$baseurl.'?page='.$i.'">' . $i . '</a>'. $sepStr;
		}
		else {
			$sepStr = '<a href="'.$baseurl.'?page='.$i.'">' . $i . '</a>'. $sepStr;
		}
		$items_to_show++;
	}
	if ($currPage == $totalPage) {
		$sepStr = '<a class="edage disable">下一页</a>' . $sepStr; 
	}
	else {
		$next = $currPage + 1;
		$sepStr = '<a class="edage" href="'.$baseurl.'?page='.$next.'">下一页</a>' . $sepStr; 
	}
	return $sepStr;
}