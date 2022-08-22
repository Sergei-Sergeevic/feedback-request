<?php

namespace FeedbackRequest\Lib;

class FeedbackRequestDataTable {

    static $table_name;
    static $collumns_names;

    public static function create_table() {
        global $wpdb;
        $wpdb_collate = $wpdb->collate;
        $sql  = 'CREATE TABLE `' . self::get_table_name() . '` (';
        $sql .= ' `id` int(11) unsigned NOT NULL auto_increment, ';
        $sql .= ' `name` varchar(255) NOT NULL, ';
        $sql .= ' `email` varchar(255) NOT NULL, ';
        $sql .= ' `phone` varchar(255) NOT NULL, ';
        $sql .= ' `date` timestamp default CURRENT_TIMESTAMP NOT NULL, '; 
        $sql .= ' PRIMARY KEY (`id`)';
        $sql .= ' ) COLLATE ' . $wpdb_collate;

        require_once( ABSPATH . '/wp-admin/includes/upgrade.php' );

        maybe_create_table( $feedback_request_table, $sql );
    }

    public static function delete_table() {
        global $wpdb;
        $sql  = 'DROP TABLE IF EXISTS ' . self::get_table_name();
        $wpdb->query( $sql );
    }

    public static function get_all( $args = array() ) {
        global $wpdb;
        $collumns_names = self::get_collumns_names();
        $select = 'SELECT * FROM ' . self::get_table_name();
        $where = '';
        if( !empty( $args['search']['value'] ) ) {
            $search = trim( $args['search']['value'] );
            $where_conditions = array();
            foreach( $collumns_names as $collumn_name) {
                $where_conditions[] = '`' . $collumn_name . '` LIKE \'%' . $search . '%\'';
            }
            $where = ' WHERE ' . implode(' OR ', $where_conditions);
        }
        
        $order_by = '';
        if( isset( $args['order'] ) ) {
            $order_by_conditions = array();
            foreach( $args['order'] as $request_order ) {
                if( isset( $request_order['column'] ) ) {
                    $order_by_dir = !empty( $request_order['dir'] ) ? $request_order['dir'] : 'asc';
                    $order_by_conditions[] = $collumns_names[$request_order['column']] . ' ' . $order_by_dir;
                }
            }
            $order_by = ' ORDER BY ' . implode( ', ', $order_by_conditions );
        }

        $limit = '';

        if( !empty( $args['length'] ) && '-1' != $args['length'] ) {
            $start = !empty( $args['start'] ) ? $args['start'] : 0;
            $limit = " LIMIT $start, {$args['length']}";
        }

        $sql = $select . $where . $order_by . $limit;
        return $wpdb->get_results( $sql );
    }

    public static function total_count() {
        global $wpdb;
        $sql = 'SELECT COUNT(*) FROM ' . self::get_table_name();
        return $wpdb->get_var( $sql );
    }

    public static function filtered_count( $args ) {
        if( empty( $args['search']['value'] ) ) {
            return self::total_count();
        }
        global $wpdb;

        $collumns_names = self::get_collumns_names();
        $select = 'SELECT COUNT(*) FROM ' . self::get_table_name();
        $where = '';
        $search = trim( $args['search']['value'] );
        $where_conditions = array();
        foreach( $collumns_names as $collumn_name) {
            $where_conditions[] = '`' . $collumn_name . '` LIKE \'%' . $search . '%\'';
        }
        $where = ' WHERE ' . implode(' OR ', $where_conditions);
        $sql = $select . $where;
        return $wpdb->get_var( $sql );
    }

    public static function insert( $name, $email, $phone ) {
        global $wpdb;
        $data = compact( 'name', 'email', 'phone' );
        return $wpdb->insert( self::get_table_name(), $data );
    }

    public static function delete( $id ) {
        global $wpdb;
        return $wpdb->delete( self::get_table_name(), array( 'id' => $id ) );
    }

    public static function empty() {

    }

    public static function get_table_name() {
        if( !empty( self::$table_name ) ) {
            return self::$table_name;
        }
        global $table_prefix;
        return self::$table_name = $table_prefix . 'feedback_requests';
    }

    public static function get_collumns_names() {
        if( !empty( self::$collumns_names ) ) {
            return self::$collumns_names;
        }
        return self::$collumns_names = array(
            'name',
            'email',
            'phone',
            'date',
        );
    }
}

