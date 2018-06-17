<?php
declare(strict_types = 1);

namespace Websupporter\StageAnonymizer\Controller;

use Websupporter\StageAnonymizer\Exceptions\RuntimeException;
use Websupporter\StageAnonymizer\Repository\Eraser;

class AnonymizeController
{

    const ACTION = 'anonymizeEmail';
    const NONCE = 'doAnonymizeEmail';
    const NONCE_NAME = '_doAnonymizeEmailNonce';

    private $repository;

    public function __construct( Eraser $eraserRepository )
    {
        $this->repository = $eraserRepository;
    }

    public function listen() : bool {

        if ( ! isset($_POST[ self::NONCE_NAME ])
            || ! current_user_can('erase_others_personal_data') || ! current_user_can('delete_users')
            || ! wp_verify_nonce($_POST[ self::NONCE_NAME ], self::NONCE)
        ) {
            wp_send_json_error([
                'message'   => __('Not authenticated.', 'stage-anonymizer'),
                'emailList' => [],
            ], 401);

            return false;
        }

        $page = (isset($_POST['page'])) ? absint( wp_unslash( $_POST['page'] ) ) : 1;
        $eraserIndex = (isset($_POST['eraserIndex'])) ? absint( wp_unslash( $_POST['eraserIndex'] ) ) : 0;
        $email = (isset($_POST['email'])) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';

        try {
            $eraser = $this->repository->byIndex($eraserIndex);
            $response = call_user_func( $eraser['callback'], $email, $page );
        } catch( RuntimeException $error ) {
            wp_send_json_error(['message' => $error->getMessage(), 'code' => $error->getCode() ], 400 );
            return false;
        }

        if( is_wp_error($response)) {
            wp_send_json_error($response, 400);
            return false;
        }

        if ( ! is_array( $response ) ) {
            wp_send_json_error(
                sprintf(
                /* translators: 1: eraser friendly name, 2: array index */
                    __( 'Did not receive array from %1$s eraser (index %2$d).' ),
                    esc_html( $eraser['eraser_friendly_name'] ),
                    $eraserIndex
                ), 400
            );
        }
        wp_send_json_success( $response );
    }

    private function eraserList() : array {
        return apply_filters('wp_privacy_personal_data_erasers',[]);
    }

}