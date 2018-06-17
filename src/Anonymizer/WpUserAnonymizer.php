<?php
declare(strict_types = 1);

namespace Websupporter\StageAnonymizer\Anonymizer;

class WpUserAnonymizer
{
    const EXCLUDE_USER = 'stage-anonymizer.exclude_user';

    public function id() : string
    {
        return 'wp-user-anonymizer';
    }

    public function name() : string
    {
        return 'WPUser Anonymizer';
    }

    public function callback( $email ) {

        global $wpdb;
        if ( empty( $email ) ) {
            return [
                'items_removed'  => false,
                'items_retained' => false,
                'messages'       => [],
                'done'           => true,
            ];
        }

        $user = get_user_by( 'email', $email );
        if( ! $user ) {
                return [
                    'items_removed'  => false,
                    'items_retained' => false,
                    'messages'       => [],
                    'done'           => true,
                ];
        }

        $exclude = in_array( 'administrator', $user->roles ) || (int) $user->ID === (int) get_current_user_id();
        $exclude = apply_filters( self::EXCLUDE_USER, $exclude, $user );
        if( $exclude ) {

            return [
                'items_removed'  => false,
                'items_retained' => false,
                'messages'       => [],
                'done'           => true,
            ];
        }

        $userData = [
            'ID' => $user->ID,
            'user_login' => 'user' . $user->ID,
            'user_nicename' => 'User ' . $user->ID,
            'display_name' => 'User ' . $user->ID,
            'user_email' => 'user-' . $user->ID . '@example.com',
            'user_url' => home_url(),
            'last_name' => 'Lastname',
            'first_name' => 'First name',
            'description' => '',
        ];
        add_filter( 'send_email_change_email', '__return_false' );
        $success = wp_update_user( $userData );
        if( is_wp_error( $success ) ) {
            return $success;
        }

        $sql = $wpdb->prepare(
            'update ' . $wpdb->users . ' set user_login = %s where ID =%d',
            'user' . $user->ID,
            $user->ID
        );
        $wpdb->query($sql);
        return [
            'items_removed'  => false,
            'items_retained' => false,
            'messages'       => [],
            'done'           => true,
        ];
    }
}