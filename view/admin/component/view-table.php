<?php 
if(!class_exists('List_table')){
    include_once HDMC__PLUGIN_DIR . 'view/admin/component/list-table.php';
}
function my_list_table() {
    $my_list_table = new List_table();
    $my_list_table->prepare_items(); 
    ?>
        <div class="wrap">
        <form id="my-list-table" method="get">
            <?php 
            $my_list_table->display(); 
            ?>
        </form>
        </div>
    <?php
}
my_list_table();