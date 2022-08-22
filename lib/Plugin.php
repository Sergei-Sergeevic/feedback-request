<?php

namespace FeedbackRequest\Lib;

use FeedbackRequest\Frontend\Shortcode, FeedbackRequest\Backend\SettingsPage;

class Plugin {
    public static function init() {
        $main_file = self::get_main_file();
        register_activation_hook( $main_file, array( static::class, 'activate' ) );
        register_uninstall_hook( $main_file, array( static::class, 'uninstall' ) );

        self::backend();

        self::add_shortcodes();
    }

    public static function get_main_file()
    {
        return dirname( __DIR__ ) . '/feedback-request.php';
    }

    public static function activate() {
        FeedbackRequestDataTable::create_table();
    }

    public static function uninstall() {
        FeedbackRequestDataTable::delete_table();
    }

    private static function backend() {
        new SettingsPage();
    }

    private static function add_shortcodes() {
        new Shortcode();
    }
}

