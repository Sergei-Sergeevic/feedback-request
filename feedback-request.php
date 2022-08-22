<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/*
Plugin Name: Feedback request
Plugin URI: https://github.com/Sergei-Sergeevic/feedback-request
Description: Feedback request system
Version: 1.0.0
Author: Serhii Semeniuk
Author URI: https://github.com/Sergei-Sergeevic
License: GPLv2 or later
Text Domain: fdbckrqst
*/
include_once __DIR__ . '/autoload.php';

FeedbackRequest\Lib\Plugin::init();