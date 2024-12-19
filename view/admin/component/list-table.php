<?php
if(!class_exists('WP_List_Table')){
    include_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}
class List_table extends WP_List_Table
{
    public function get_columns()
    {
        $columns = array(
            'cb'        => '<input type="checkbox" />', //Render a checkbox instead of text
            'id'    => 'ID',
            'name_address'  => 'Tên Chi Nhánh',
            'address_line'  => 'Địa chỉ',
            'img_url'  => 'URL Hình',
            'phone'  => 'SĐT',
            'province_name'  => 'Tỉnh/Thành Phố',
        );
        return $columns;
    }

    public function get_sortable_columns()
    {
        $sortable_columns = array(
            'id'  => array('id', true),  //true means it's already sorted
            'name_address' => array('name_address', false),
            'address_line' => array('address_line', false),
            'img_url' => array('img_url', false),
            'phone' => array('phone', false),
            'province_name' => array('province_id', false),
        );
        return $sortable_columns;
    }

    public function prepare_items()
    {
        global $wpdb;
        $this->_column_headers = [$this->get_columns()];

        $table = $wpdb->prefix . 'hd_address';

        $per_page     = 20; // Số bản ghi trên mỗi trang
        $current_page = $this->get_pagenum();

        $total_items  = $wpdb->get_var("SELECT COUNT(*) FROM $table"); // Đếm tổng số bản ghi

        // Lấy dữ liệu từ database
        $this->items = $wpdb->get_results("SELECT 
                                            a.*, 
                                            p.province_name 
                                        FROM 
                                            $table a
                                        LEFT JOIN 
                                            {$wpdb->prefix}hd_province p ON a.province_id = p.id
                                        ORDER BY 
                                            a.id 
                                        LIMIT {$per_page} OFFSET " . ($current_page - 1) * $per_page, ARRAY_A);
                                        //Setup output save in array

        $this->set_pagination_args(array(
            'total_items' => $total_items,
            'per_page'    => $per_page,
            'total_pages' => ceil($total_items / $per_page)
        ));
    }

    public function column_default($item, $column_name)
    {   
        // var_dump($item);
        switch ($column_name) {
            case 'id':
            case 'name_address':
            case 'address_line':
            case 'img_url':
            case 'phone':
            case 'province_name':
                return $item[$column_name];
            default:
                return print_r($item, true); //Hiển thị toàn bộ object nếu không tìm thấy cột
        }
    }

    public function column_cb( $item) {
        // var_dump($item);
        return sprintf(
          '<input type="checkbox" name="my_list_table[]" value="%s" />', $item['id']
        );
      }
}