<?php

/**
 * Display Text
 *
 * Cisco XML application toolkit.
 *
 * @author Till Steinbach <till.steinbach@gmx.de>
 * @copyright (c) Till Steinbach
 * @license BSD (two clause)
 */

namespace ciscoxml;

use ciscoxml\common\TAttrTitle;
use ciscoxml\common\TAttrPrompt;
use ciscoxml\common\TAttrSoftKeyItems;

class CiscoIpPhoneText {
    use TAttrTitle;
    use TAttrPrompt;
    use TAttrSoftKeyItems;

    protected $text;

    public function __construct($title, $prompt, $text) {
        $this->setTitle($title)
             ->setPrompt($prompt)
             ->setText($text);
    }

    public function setText($text) {
        $this->text = $text;
        return $this;
    }

    public function __toString() {
        $soft_key_items = '';
        foreach ($this->soft_key_items as $soft_key_item) {
            $soft_key_items .= (string) $soft_key_item;
        }

        return '<CiscoIPPhoneText>'
             .     "<Title>{$this->title}</Title>"
             .     "<Prompt>{$this->prompt}</Prompt>"
             .     "<Text>{$this->text}</Text>"
             .     $soft_key_items
             . '</CiscoIPPhoneText>';
    }
}
