<?php

namespace cipxml;

final class Key {
    private function __construct() {}
    
    const KeyPad0 = 1;
    const KeyPad1 = 2;
    const KeyPad2 = 3;
    const KeyPad3 = 4;
    const KeyPad4 = 5;
    const KeyPad5 = 6;
    const KeyPad6 = 7;
    const KeyPad7 = 8;
    const KeyPad8 = 9;
    const KeyPad9 = 10;
    const KeyPadStar = 11;
    const KeyPadPound = 12;
    const NavUp = 13;
    const NavDown = 14;
    const NavLeft = 15;
    const NavRight = 16;
    const NavSelect = 17;
    const NavBack = 18;
    const PushToTalk = 19;
    
    const _max = Key::PushToTalk;
    
    public static function string($key){
        switch($key){
            case Key::KeyPad0: return 'KeyPad0';
            case Key::KeyPad1: return 'KeyPad1';
            case Key::KeyPad2: return 'KeyPad2';
            case Key::KeyPad3: return 'KeyPad3';
            case Key::KeyPad4: return 'KeyPad4';
            case Key::KeyPad5: return 'KeyPad5';
            case Key::KeyPad6: return 'KeyPad6';
            case Key::KeyPad7: return 'KeyPad7';
            case Key::KeyPad8: return 'KeyPad8';
            case Key::KeyPad9: return 'KeyPad9';
            case Key::KeyPadStar: return 'KeyPadStar';
            case Key::KeyPadPound: return 'KeyPadPound';
            case Key::NavUp: return 'NavUp';
            case Key::NavDown: return 'NavDown';
            case Key::NavLeft: return 'NavLeft';
            case Key::NavRight: return 'NavRight';
            case Key::NavSelect: return 'NavSelect';
            case Key::NavBack: return 'NavBack';
            case Key::PushToTalk: return 'PushToTalk';
        }
    }
}

class KeyItem extends XMLElement{
    protected $key = null;
    protected $url = null;
    protected $url_down = null;

    public function __construct($key=null, $url=null, $url_down=null) {
        $this->setKey($key);
        $this->setURL($url);
        $this->setURLDown($url_down);
    }
    
    public function setKey($key) {
        if($key && $key>Key::_max){
            throw new \InvalidArgumentException('no valid key, please use ENUM Key to specify key (e.g. Key::KeyPadStar)');
        }
        $this->key = $key;
    }
    
    public function getKey() {
        return $this->key;
    }
    
    public function setURL($url) {
        if($url && strlen($url)>256){
            throw new \LengthException('URL must have not more than 256 characters');
        }
        $this->url = $url;
    }
    
    public function setURLDown($url_down) {
        if($url_down && strlen($url_down)>256){
            throw new \LengthException('URL must have not more than 256 characters');
        }
        $this->url_down = $url_down;
    }


    public function toXML(\DOMNode $domNode){
        $root = $domNode->ownerDocument->createElement('KeyItem');
        $domNode->appendChild($root);
        
        if($this->key){
            $key = $root->ownerDocument->createElement('Key');
            $key_text = $domNode->ownerDocument->createTextNode(Key::string($this->key));
            $key->appendChild($key_text);
            $root->appendChild($key);
        }
        if($this->url){
            $url = $root->ownerDocument->createElement('URL');
            $url_text = $domNode->ownerDocument->createTextNode($this->url);
            $url->appendChild($url_text);
            $root->appendChild($url);
        }
        if($this->url_down){
            $url_down = $root->ownerDocument->createElement('URLDown');
            $url_down_text = $domNode->ownerDocument->createTextNode($this->url_down);
            $url_down->appendChild($url_down_text);
            $root->appendChild($url_down);
        }
        return $domNode;
    }
}
