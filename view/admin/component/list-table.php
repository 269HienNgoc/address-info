<?php
if (!class_exists('WP_List_Table')) {
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
            // 'name_address' => array('name_address', true),
            // 'address_line' => array('address_line', false),
            // 'img_url' => array('img_url', false),
            // 'phone' => array('phone', false),
            'province_name' => array('province_id', true),
        );
        return $sortable_columns;
    }

    public function prepare_items()
    {
        global $wpdb;
        $this->_column_headers = [$this->get_columns(), [], $this->get_sortable_columns()];

        echo $this->current_action();

        $table = $wpdb->prefix . 'hd_address';

        //Sort
        $orderBy = $_POST['orderby'] ?? 'id';
        $order = $_POST['order'] ?? 'ASC';

        //Paginate
        $per_page     = 20; // Số bản ghi trên mỗi trang
        $current_page = $this->get_pagenum();

        // Search KeyWork
        $search = $_POST['s'] ?? false;

        if ($search) {

            $total_items  = $wpdb->get_var("SELECT COUNT(*)
                                                    FROM $table a
                                                    LEFT JOIN {$wpdb->prefix}hd_province p ON a.province_id = p.id
                                                    WHERE a.name_address LIKE '%{$search}%' 
                                                    OR p.province_name LIKE '%{$search}%'"); // Đếm tổng số bản ghi
            // Lấy dữ liệu từ database
            $this->items = $wpdb->get_results("SELECT 
                                                a.*,
                                                p.province_name 
                                            FROM
                                                $table a
                                            LEFT JOIN 
                                                {$wpdb->prefix}hd_province p ON a.province_id = p.id
                                            WHERE
                                                 a.name_address LIKE '%{$search}%' 
                                                OR p.province_name LIKE '%{$search}%'
                                            ORDER BY a.id
                                            LIMIT {$per_page}", ARRAY_A);
        } else {
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
                                               {$orderBy} {$order}
                                            LIMIT {$per_page} OFFSET " . ($current_page - 1) * $per_page, ARRAY_A);
            //Setup output save in array
        }

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
    // Add Column Table.
    public function single_row_columns( $item ) {
        list( $columns, $hidden ) = $this->get_column_info();
    
        foreach ( $columns as $column_name => $column_display_name ) {
            // ... (giữ nguyên phần xử lý các cột khác) ...
            $class = "class='$column_name column-$column_name'";

            $style = '';
            if ( in_array( $column_name, $hidden ) )
                $style = ' style="display:none;"';
    
            $attributes = "$class$style";
    
            switch ( $column_name ) {

                case 'cb':
                    echo "<td $attributes>". sprintf('<input type="checkbox" name="my_list_table[]" value="%s" />', $item['id']) . "</td>";
                    break;

                case 'id':
                    echo "<td $attributes>" . $item['id'] . "</td>";
                    break;

                case 'name_address':
                    echo "<td class='$column_name column-$column_name'>";

                    echo $item['name_address'] . '<br>' ;
                    
                    // Nút "Xem"
                    echo '<a href="?page=my_page&action=view&id=' . $item['id'] . '">Xem</a> |';
    
                    // Nút "Sửa"
                    echo '<a href="?page=my_page&action=edit&id=' . $item['id'] . '">Sửa</a> |';
    
                    // Nút "Xóa"
                    echo '<a href="?page=hd-manager-address&action=delete&id=' . $item['id'] . '" onclick="return confirm(\'Bạn có chắc chắn muốn xóa?\')">Xóa</a>';
    
                    echo "</td>";
                    break;

                case 'address_line':
                    echo "<td $attributes>" . $item['address_line'] . "</td>";
                    break;

                case 'img_url':
                    echo "<td $attributes> <img src='". $item['img_url'] . "' width='100px' height='100px'></td>";
                    break;

                case 'phone':
                    echo "<td $attributes>" . $item['phone'] . "</td>";
                    break;

                case 'province_name':
                    echo "<td $attributes>" . $item['province_name'] . "</td>";
                    break;
            }
        }
    }

    public function ModifyActionBtn( $actions, $id){

        if ( isset( $actions ) ?? null || isset($id) ?? null ) {
            switch ( $actions ) {
                case 'view':
                    // Xử lý hành động "Xem"
                    break;
                case 'edit':
                    // Xử lý hành động "Sửa"
                    break;
                case 'delete':
                    // Xử lý hành động "Xóa"
            break;
            }
        }


    }
}
