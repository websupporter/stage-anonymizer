<?php
declare(strict_types = 1);

namespace Websupporter\StageAnonymizer\Controller;

use Websupporter\StageAnonymizer\Repository\EmailList;

class EmailListController
{

    const ACTION = 'emailList';
    const NONCE = 'getEmailList';
    const NONCE_NAME = '_getEmailListNonce';

    private $emailList;

    public function __construct(
        EmailList $emailList
    ) {

        $this->emailList = $emailList;
    }

    public function listen() : bool
    {

        if ( ! isset($_GET[ self::NONCE_NAME ])
            || ! current_user_can('erase_others_personal_data') || ! current_user_can('delete_users')
            || ! wp_verify_nonce($_GET[ self::NONCE_NAME ], self::NONCE)
        ) {
            wp_send_json_error([
                'message'   => __('Not authenticated.', 'stage-anonymizer'),
                'emailList' => [],
            ], 401);

            return false;
        }

        wp_send_json_success([
            'message'   => __('OK.', 'stage-anonymizer'),
            'emailList' => $this->emailList->all(),
        ]);

        return true;
    }
}