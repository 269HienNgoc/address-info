<?php 

class HDinitialization {

    public function GetPerfix (){
        global $wpdb;
        return $wpdb->prefix;
    }

    public function GetCharset(){
        global $wpdb;
        return $wpdb->get_charset_collate();
    }

}