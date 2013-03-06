<?php
class Utils {
    public static function Model() {
        return new Utils();
    }
    
    public function getSepLinkString($allcount) {
        $perPage = Application::$app->perPage;
        $link_num_to_show = Application::$app->itemToShow;
        $totalPage = ceil($allcount / $perPage);
        if (!$totalPage)$totalPage = 1;
        include BASE_PATH . 'include/page-separator.php';
        if ($_REQUEST['page'] && is_numeric($_REQUEST['page'])) $currPage = $_REQUEST['page'];
        else $currPage = 1;
        return getSepLinksString($currPage,$totalPage, $perPage, $link_num_to_show);
    }

    protected function getSinglePageData($data) {
       $currPage = 1;
       if ($_REQUEST['page'] && is_numeric($_REQUEST['page'])) $currPage = $_REQUEST['page'];
       $perPage = Application::$app->perPage;
       $start = ($currPage - 1) * $perPage;
       return array_slice($data,$start,$perPage);
    }
}