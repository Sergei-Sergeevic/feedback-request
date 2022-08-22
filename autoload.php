<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Feedback Request Plugin autoload.
 * @param $class
 */
function fdbckrqst_loader( $class ) {
    if ( preg_match( '/^FeedbackRequest\\\\(.+)?([^\\\\]+)$/U', ltrim( $class, '\\' ), $match ) ) {
        $file = __DIR__ . DIRECTORY_SEPARATOR
                . strtolower( str_replace( '\\', DIRECTORY_SEPARATOR, preg_replace( '/([a-z])([A-Z])/', '$1_$2', $match[1] ) ) )
                . $match[2]
                . '.php';
        if ( is_readable( $file ) ) {
            require_once $file;
        }
    }
}
spl_autoload_register( 'fdbckrqst_loader', true, true );

require_once 'lib/Formr/class.formr.php';