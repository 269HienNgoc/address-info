<?php

/**
 * Summary of AdminMenu
 */
if (!class_exists('AdminMenu')) {
    final class AdminMenu
    {
        private static $instance = null;
        private $mess = '';
        /**
         * Summary of __construct
         */
        public function __construct()
        {
            add_action('init', [$this, 'Register']);
        }
        /**
         * Summary of GetInstance
         * @return AdminMenu
         */
        public static function GetInstance()
        {
            if (self::$instance === null) {
                self::$instance = new AdminMenu;
            }
            return self::$instance;
        }

        /**
         * Summary of Register
         * @return void
         */
        public function Register()
        {
            //Add menu
            add_action('admin_menu', [$this, 'AddMenuAdmin']);
            //Register CSS, JS
            add_action('admin_enqueue_scripts', [$this, 'RegisterScript']);
        }
        /**
         * Summary of AddMenuAdmin
         * Add Admin Menu QL Địa chỉ
         * @return void
         */
        public function AddMenuAdmin()
        {
            add_menu_page(
                'Address Manager',
                'Address Manager',
                'manage_options',
                'hd-manager-address',
                [$this, 'CreateInterfaceAdmin'],
                'dashicons-list-view',
                10
            );
            add_submenu_page(
                'hd-manager-address',
                'Add New',
                'Add New',
                'manage_options',
                'hd-add-address',
                [$this, 'CreateNewAddress']
            );
            add_submenu_page(
                'hd-manager-address',
                'Province',
                'Province',
                'manage_options',
                'hd-add-province',
                [$this, 'CreateNewProvice']
            );
        }
        /**
         * Summary of CreateInterfaceAdmin
         * @return void
         */
        public function CreateInterfaceAdmin()
        {
            require_once HDMC__PLUGIN_DIR . 'view/admin/component/list-table.php';
        }
        /**
         * Summary of CreateNewCustomer
         * @return void
         */
        public function CreateNewAddress()
        {
            $this->SaveAddress();

            $response = $this->mess;

            $province = $this->GetValueProvince();

            ob_start();
            $the_contents = require_once HDMC__PLUGIN_DIR . 'view/admin/component/add-news.php';
            $the_contents = ob_get_contents();
            ob_end_clean();
            echo $the_contents;
        }

        private function SaveAddress()
        {
            global $wpdb;

            $perfix  =  $wpdb->prefix;

            if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_REQUEST['submit-btn'])) {
                //Sanitize
                $avatar =   sanitize_text_field($_REQUEST['avatarAddress']) ?? '';
                $nameAddress =   sanitize_text_field($_REQUEST['nameAddress']) ?? '';
                $address_ =   sanitize_text_field($_REQUEST['address_']) ?? '';
                $province_id = isset($_REQUEST['province']) ? intval($_REQUEST['province']) : 0; 
                $maps =   sanitize_text_field($_REQUEST['maps']) ?? '';
                $phone =   sanitize_text_field($_REQUEST['phone']) ?? '';

                $wpdb->insert("{$perfix}hd_address", [
                    'name_address' => $nameAddress,
                    'address_line' => $address_,
                    'img_url' => $avatar,
                    'phone' => $phone,
                    'province_id' => $province_id,
                ]);

                $address_id = $wpdb->insert_id;

                if ($address_id > 0) {
                    $this->mess = "Thêm thành công...";
                } else {
                    $this->mess = "Tạo thất bại...";
                }
            }
        }
        public function CreateNewProvice()
        {
            $this->SaveProvince();

            $response = $this->mess;

            ob_start();
            $the_content = require_once HDMC__PLUGIN_DIR . 'view/admin/component/category-province.php';
            $the_content = ob_get_contents();
            ob_end_clean();
            echo $the_content;
        }

        private function SaveProvince()
        {
            global $wpdb;
            $perfix  =  $wpdb->prefix;

            if ( $_SERVER["REQUEST_METHOD"] == "POST" && isset($_REQUEST["submit-btn-province"])) {

                $nameProvince = sanitize_text_field($_REQUEST['nameProvince']) ?? '';
                
                if (empty($nameProvince) || $nameProvince == '') {
                    $this->mess = "Tên Tỉnh Thành/Thành Phố không được để trống";
                    return;
                }

                try {
                    $wpdb->insert("{$perfix}hd_province", [
                        'province_name' => $nameProvince,
                    ]);
    
                    $province_id = $wpdb->insert_id;
    
                    if ($province_id > 0) {
                        $this->mess = "Thêm thành công...";
                    } else {
                        $this->mess = "Thêm không thành công...";
                    }

                } catch (\Throwable $th) {
                    //throw $th;
                    $this->mess = "Error when insert data: " . $th->getMessage();
                }
            }
        }

        private function GetValueProvince(){
            global $wpdb;
            $results = $wpdb->get_results("SELECT id, province_name FROM {$wpdb->prefix}hd_province");
            return $results;
        }

        public function RegisterScript($hook)
        {
            //Register CSS/JS
            wp_register_style('manager-style', HDMC__PLUGIN_URL . 'asset/css/manager.css', array(), 'all');
            wp_register_style('add-news-style', HDMC__PLUGIN_URL . 'asset/css/add-news.css', array(), 'all');
            wp_register_style('provice-style', HDMC__PLUGIN_URL . 'asset/css/province.css', array(), 'all');

            wp_register_script('main-js', HDMC__PLUGIN_URL . 'asset/js/addnew.js', array('jquery'), HDMC_VERSION_CSS_JS, false);

            if ('toplevel_page_hd-manager-address' == $hook) {
                wp_enqueue_style('manager-style');
            }
            if ('address-manager_page_hd-add-address' == $hook) {
                wp_enqueue_media();
                wp_enqueue_style('add-news-style');
                wp_enqueue_script('main-js');
            }

            if ('address-manager_page_hd-add-province' == $hook) {
                wp_enqueue_style('add-news-style');
            }
        }
    }
    AdminMenu::GetInstance();
}
