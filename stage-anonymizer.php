<?php # -*- coding: utf-8 -*-

/**
 * Plugin Name: Stage Anonymizer
 * Description: Anonymize user data in your stage environment.
 * Plugin URI:  TODO
 * Author:      Inpsyde GmbH
 * Author URI:  http://inpsyde.com/
 * Version:     dev-master
 * License:     GPL-2.0
 * Text Domain: stage-anonymizer
 */

namespace Websupporter\StageAnonymizer;

use Websupporter\StageAnonymizer\Hooks\CoreHooks;

add_action(
    'plugins_loaded',
    function() {

        try {
            if ( ! class_exists(CoreHooks::class)) {
                require_once __DIR__ . '/vendor/autoload.php';
            }

            global $wpdb;
            ( new CoreHooks( __FILE__, $wpdb ) )->setup();

        } catch( \Throwable $error ) {
            add_action( 'stage-anonymizer.error', $error );
            if( defined( 'WP_DEBUG' ) ) {
                throw $error;
            }
        }
    }
);
