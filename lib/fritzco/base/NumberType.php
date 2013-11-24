<?php

namespace fritzco\base;

final class NumberType {
    private function __construct() {}
    
    const NONE = 0;
    const OTHER = 1;
    const HOME = 2;
    const WORK = 3;
    const MOBILE = 4;
    const FAX = 5;
    const FAX_WORK = 6;
    
    const _max = NumberType::FAX_WORK;
    
    public static function string($type){
        switch($type){
            case NumberType::NONE: return 'NONE';
            case NumberType::OTHER: return 'OTHER';
            case NumberType::HOME: return 'HOME';
            case NumberType::WORK: return 'WORK';
            case NumberType::MOBILE: return 'MOBILE';
            case NumberType::FAX: return 'FAX';
            case NumberType::FAX_WORK: return 'FAX_WORK';
        }
    }
}

?>
