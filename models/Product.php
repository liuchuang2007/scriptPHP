<?php
class Product extends AgentDB {
    public function getProductById($pcode) {
        return $this->query("select * from product where pcode = '$pcode'",'DEFAULT','assoc');
    }
}