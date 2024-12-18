<?php
require HDMC__PLUGIN_DIR . 'database/initialization.php';
if (!class_exists('CreateDB')) {
    class CreateDB extends HDinitialization
    {
        private static $instance = null;
        public function __construct()
        {
            $this->CreateTableProvince();
            $this->CreateTableAddress();
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
            global $wpdb;
            $table_provice = $this->GetPerfix() . 'hd_province';
            $tableName  =  $this->GetPerfix() . 'hd_address';
            $charset_collect = $this->GetCharset();

            $table_exists = $wpdb->get_var("SHOW TABLES LIKE '$tableName'");

            if (!$table_exists) {

                $escaped_tablename = $wpdb->_escape($tableName);
                $escaped_tableprovince = $wpdb->_escape($table_provice);

                try {
                    $wpdb->query('SET FOREIGN_KEY_CHECKS = 0;');

                    $sql = "CREATE TABLE IF NOT EXISTS $escaped_tablename ( 
                            id INT(11) AUTO_INCREMENT PRIMARY KEY,
                            name_address VARCHAR(255) NOT NULL,
                            address_line VARCHAR(255) NOT NULL,
                            img_url VARCHAR(255) NOT NULL,
                            phone VARCHAR(255) NOT NULL,
                            province_id INT NOT NULL,
                            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                            FOREIGN KEY (province_id) REFERENCES $escaped_tableprovince(id) ON DELETE CASCADE,
                            INDEX (province_id)
                        )$charset_collect;";

                    // $prepare_ = $wpdb->prepare($sql, $tableName, $table_provice, $charset_collect);
                    require_once ABSPATH . '/wp-admin/includes/upgrade.php';

                    dbDelta($sql);
                } finally {
                    $wpdb->query('SET FOREIGN_KEY_CHECKS = 1;');
                }
            }
        }

        public function CreateTableProvince()
        {
            global $wpdb;
            $tableName  =  $this->GetPerfix() . 'hd_province';
            $charset_collect = $this->GetCharset();
            $table_exists = $wpdb->get_var("SHOW TABLES LIKE '$tableName'");
            if (!$table_exists) {

                $escaped_tableprovince = $wpdb->_escape($tableName);

                $sql = "CREATE TABLE IF NOT EXISTS  $escaped_tableprovince (
                            id INT(11) AUTO_INCREMENT PRIMARY KEY,
                            province_name VARCHAR(255) NOT NULL UNIQUE
                    ) $charset_collect;";

                // $prepare_ = $wpdb->prepare($sql, $tableName, $charset_collect);
                require_once ABSPATH . '/wp-admin/includes/upgrade.php';

                dbDelta($sql);
            }
        }
    }
    CreateDB::GetInstance();
}
