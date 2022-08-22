<?php

namespace FeedbackRequest\Frontend;
use FeedbackRequest\Lib\FeedbackRequestDataTable;
use FeedbackRequest\Lib\Plugin;

class Shortcode {
    public function __construct() {
        add_shortcode( 'feedback_request', array( $this, 'render' ) );
        add_action( 'wp_ajax_fdbckrqst_submit_form', array( $this, 'render' ) );
        add_action( 'wp_ajax_nopriv_fdbckrqst_submit_form', array( $this, 'render' ) );

        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
    }

    public function render() {
        $form = new \Formr\Formr();
        $form->required = '*';

        if( $form->submitted() ) {
            $name = sanitize_text_field( $form->post( 'full-name', 'Name', array( 'required' ) ) );
            $email = sanitize_text_field( $form->post( 'email', 'Email', array( 'valid_email', 'required' ) ) );
            $phone = sanitize_text_field( $form->post( 'tel', 'Phone|Wrong Number', array( 'required', 'not_regex[/^\\+?\\d{1,4}?[-.\\s]?\\(?\\d{1,3}?\\)?[-.\\s]?\\d{1,4}[-.\\s]?\\d{1,4}[-.\\s]?\\d{1,9}$/]' ) ) );

            if($form->ok()) {
                FeedbackRequestDataTable::insert( $name, $email, $phone );
                ob_start();
                $form->success_message('Thank you for submitting our form!');
                if(  wp_doing_ajax() ) {
                    echo ob_get_clean();
                    wp_die();
                }
                return ob_get_clean();
            }
            
        }
        ob_start();

        $form->open( 'feedback-request-form', '', get_the_permalink(), 'POST', 'class="feedback-request-form"' ) ;
        $form->text( 'full-name', __( 'Your Name', 'fdbckrqst' ) ) ;
        $form->error( 'full-name' );

        $form->email( 'email', __( 'Your Email', 'fdbckrqst' ) ) ;
        $form->error( 'email' );

        $form->tel( 'tel', __( 'Your Phone Number', 'fdbckrqst' ) ) ;
        $form->error( 'tel' );

        $form->submit_button( __( 'Submit', 'fdbckrqst' ) ) ;

        $form->hidden('action', 'fdbckrqst_submit_form');
        $form->close();

        if(  wp_doing_ajax() ) {
            echo ob_get_clean();
            wp_die();
        }
        return ob_get_clean();
    }

    public function enqueue_scripts() {
        wp_enqueue_script( 'feedback-request-front', plugins_url( 'inc/js/front.js', Plugin::get_main_file() ), array( 'jquery' ) );
        wp_localize_script( 'feedback-request-front',
            'fdbckrqstValues',
            array(
                'ajaxurl' => admin_url( 'admin-ajax.php' ),
            )
        );
    }


}