<?php
require HDMC__PLUGIN_DIR . 'database/initialization.php';
if (!class_exists('CreateDB')) {
    class CreateDB extends HDinitialization
    {
        private static $instance = null;
        public function __construct()
        {
            $this->CreateTableAddress();
            $this->CreateTableProvince();
        }

        public static function GetInstance()
        {
            if (self::$instance === null) {
                self::$instance = new CreateDB();
            }
            return self::$instance;
        }

        public function CreateTableAddress()
        {
            $table_provice = $this->GetPerfix() . 'hd_province';
            $tableName  =  $this->GetPerfix() . 'hd_address';
            $charset_collect = $this->GetCharset();

            $sql = "CREATE TABLE IF NOT EXISTS $tableName (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name_address VARCHAR(255) NOT NULL,
                address_line VARCHAR(255) NOT NULL,
                img_url VARCHAR(255) NOT NULL,
                phone VARCHAR(255) NOT NULL,
                province_id INT NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (province_id) REFERENCES $table_provice(id) ON DELETE CASCADE
            ) $charset_collect;";

            require_once ABSPATH . '/wp-admin/includes/upgrade.php';
            dbDelta($sql);
        }

        public function CreateTableProvince()
        {
            $tableName  =  $this->GetPerfix() . 'hd_province';
            $charset_collect = $this->GetCharset();

            $sql = "CREATE TABLE IF NOT EXISTS $tableName (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    province_name VARCHAR(255) NOT NULL UNIQUE
                    ) $charset_collect;";

            require_once ABSPATH . '/wp-admin/includes/upgrade.php';
            dbDelta($sql);
        }
    }
    CreateDB::GetInstance();
}
