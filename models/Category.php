<?php
class Category extends AgentDB {
    public static function Model() {
         return new Category();
    }

    public function getAllCategory() {
        return $this->query("select * from category where type = 1",'DEFAULT','all');
    }
    public function getBaseInfo($id) {
        return $this->query("select * from category where id = '$id'",'DEFAULT','assoc');
    }

    public function getAllBrands() {
       return $this->query("select * from category where type = 2",'DEFAULT','all');
    }

    public function getSubBrands($pid) {
       return $this->query("select * from category where pid = '$pid'",'DEFAULT','all');
    }
}