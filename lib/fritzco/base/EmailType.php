<?php

namespace fritzco\base;

final class EmailType {
    private function __construct() {}
    
    const NONE = 0;
    const OTHER = 1;
    const PRIVATE_ = 2;
    const BUSINESS = 3;
    
    const _max = EmailType::BUSINESS;
    
    public static function string($type){
        switch($type){
            case EmailType::NONE: return 'NONE';
            case EmailType::OTHER: return 'OTHER';
            case EmailType::PRIVATE_: return 'PRIVATE_';
            case EmailType::BUSINESS: return 'BUSINESS';
        }
    }
}

?>
