<?php

if (!class_exists( 'WP_List_Table')){
	require_once(ABSPATH.'wp-admin/includes/class-wp-list-table.php');
}
class Customers_List_Table extends WP_List_Table
{
    /**
     * Prepare the items for the table to process
     *
     * @return Void
     */
    public function prepare_items()
    {
        $columns = $this->get_columns();
        $hidden = $this->get_hidden_columns();
        $sortable = $this->get_sortable_columns();
        $data = $this->table_data();
        usort( $data, array( &$this, 'sort_data' ) );
        $perPage = 15;
        $currentPage = $this->get_pagenum();
        $totalItems = count($data);
        $this->set_pagination_args( array(
            'total_items' => $totalItems,
            'per_page'    => $perPage
        ) );
        $data = array_slice($data,(($currentPage-1)*$perPage),$perPage);
        $this->_column_headers = array($columns, $hidden, $sortable);
        $this->items = $data;
    }
    /**
     * Override the parent columns method. Defines the columns to use in your listing table
     *
     * @return Array
     */
    public function get_columns()
    {
        $columns = array(
            'id'           => 'ID',
            'date'         => 'Дата',
            'name'         => 'Имя',
            'phone_number' => 'Номер телефона'
        );
        return $columns;
    }
    /**
     * Define which columns are hidden
     *
     * @return Array
     */
    public function get_hidden_columns()
    {
        return array();
    }
    /**
     * Define the sortable columns
     *
     * @return Array
     */
    public function get_sortable_columns()
    {
        return array('id' => array('id', false), 'date' => array('date', false));
    }
    /**
     * Get the table data
     *
     * @return Array
     */
    private function table_data()
    {
        $data = array();
        global $wpdb;
        $sql = "SELECT * FROM wp_customers_list";
        $result = $wpdb->get_results( $sql, 'ARRAY_A' );
        
        for ($i = 0; $i < count($result); $i++){
            $data[] = array(
                'id'           => $result[$i]['customer_id'],
                'date'         => $result[$i]['customer_date'],
                'name'         => $result[$i]['customer_name'],
                'phone_number' => $result[$i]['customer_phone_number'],
            );
        }
        return $data;
    }
    /**
     * Define what data to show on each column of the table
     *
     * @param  Array $item        Data
     * @param  String $column_name - Current column name
     *
     * @return Mixed
     */
    public function column_default( $item, $column_name )
    {
        switch( $column_name ) {
            case 'id':
            case 'date':
            case 'name':
            case 'phone_number':
                return $item[ $column_name ];
            default:
                return print_r( $item, true ) ;
        }
    }
    /**
     * Allows you to sort the data by the variables set in the $_GET
     *
     * @return Mixed
     */
    private function sort_data( $a, $b )
    {
        // Set defaults
        $orderby = 'id';
        $order = 'asc';
        // If orderby is set, use this as the sort column
        if(!empty($_GET['orderby']))
        {
            $orderby = $_GET['orderby'];
        }
        // If order is set use this as the order
        if(!empty($_GET['order']))
        {
            $order = $_GET['order'];
        }
        $result = strcmp( $a[$orderby], $b[$orderby] );
        if($order === 'asc')
        {
            return $result;
        }
        return -$result;
    }
}

class Init_List_Table
{
    /**
     * Display the list table page
     *
     * @return Void
     */
    public function __construct()
    {
        $customersListTable = new Customers_List_Table();
        $customersListTable->prepare_items();
        ?>
            <div class="wrap">
                <div id="icon-users" class="icon32"></div>
                <h2>Список пользователей, оформлявших заказ</h2>
                <?php $customersListTable->display(); ?>
            </div>
        <?php
    }
}
new Init_List_Table();
?>