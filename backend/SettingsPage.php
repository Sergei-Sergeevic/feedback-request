<?php

namespace FeedbackRequest\Backend;

use FeedbackRequest\Lib\Plugin;
use FeedbackRequest\Lib\FeedbackRequestDataTable;

class SettingsPage {
    public function __construct() {
        add_action( 'admin_menu', array( $this, 'register_settings_page' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );

        add_action( 'wp_ajax_fdbckrqst_feedbacks_table', array( $this, 'render_table_data' ) );
        add_action( 'wp_ajax_fdbckrqst_delete_feedback', array( $this, 'delete_feedback' ) );
        

    }

    public function register_settings_page() {
        add_menu_page(
            __( 'Feedback requests', 'fdbckrqst' ),
            __( 'Feedback requests', 'fdbckrqst' ),
            'manage_options',
            'feedback_requests',
            array( $this, 'render' ),
            'dashicons-feedback',
            4 
        );
    }

    public function render() { ?>
        <div class="wrap">
			<h1><?php echo get_admin_page_title() ?></h1>
            <table class="feedback-requests">
                <thead>
                    <tr>
                        <th>
                            <?php _e( 'Name', 'fdbckrqst' ); ?>
                        </th>
                        <th>
                            <?php _e( 'Email', 'fdbckrqst' ); ?>
                        </th>
                        <th>
                            <?php _e( 'Phone', 'fdbckrqst' ); ?>
                        </th>
                        <th>
                            <?php _e( 'Date', 'fdbckrqst' ); ?>
                        </th>
                        <th>
                            <?php _e( 'Action', 'fdbckrqst' ); ?>
                        </th>
                    </tr>
                </thead>
            </table>
		</div>
    <?php }

    public function render_table_data() {
        $json_data = array(
            'draw'              => intval($_REQUEST['draw']),
            'recordsTotal'      => FeedbackRequestDataTable::total_count(),
            'recordsFiltered'   => FeedbackRequestDataTable::filtered_count( $_REQUEST ),
            'data'              => FeedbackRequestDataTable::get_all( $_REQUEST ),
        );
        echo json_encode( $json_data );
        wp_die();
    }

    public function delete_feedback() {
        if( !empty( $_REQUEST['id'] ) ) {
            return FeedbackRequestDataTable::delete( trim( $_REQUEST['id'] ) );
            wp_die();
        }
        return false;
        wp_die();
    }

    public function admin_enqueue_scripts( $hook ) {
        if( 'toplevel_page_feedback_requests' == $hook ) {
            wp_enqueue_style( 'feedback-request-data-tables', plugins_url( 'inc/css/dataTables/jquery.dataTables.css', Plugin::get_main_file() ) );
            wp_enqueue_script( 'feedback-request-data-tables', plugins_url( 'inc/js/dataTables/jquery.dataTables.js', Plugin::get_main_file() ), array( 'jquery' ) );
            wp_enqueue_script( 'feedback-request-admin', plugins_url( 'inc/js/admin.js', Plugin::get_main_file() ), array( 'feedback-request-data-tables' ) );

            wp_localize_script( 'feedback-request-admin',
                'fdbckrqstValues',
                array(
                    'delete' => __( 'Delete', 'fdbckrqst' ),
                )
            );
        }
    }
}