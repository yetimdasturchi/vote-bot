<?php

class LanguageLoader {
    function initialize() {
        $ci =& get_instance();
        $language = get_cookie('language') ? get_cookie('language') : getDefaultLanguage();
        $ci->config->set_item('language',  $language );
        $ci->lang->pack('system');
    }
}