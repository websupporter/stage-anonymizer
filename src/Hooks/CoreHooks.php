<?php
declare(strict_types = 1);

namespace Websupporter\StageAnonymizer\Hooks;

use Inpsyde\Assets\Asset;
use Inpsyde\Assets\AssetManager;
use Inpsyde\Assets\Script;
use Websupporter\StageAnonymizer\Controller\AnonymizeController;
use Websupporter\StageAnonymizer\Controller\EmailListController;
use Websupporter\StageAnonymizer\Repository\EmailList;
use Websupporter\StageAnonymizer\Repository\Eraser;

class CoreHooks
{

    private $rootFile;

    private $wpdb;

    public function __construct(string $rootFile, \wpdb $wpdb)
    {

        $this->rootFile = $rootFile;
        $this->wpdb     = $wpdb;
    }

    public function setup() : bool
    {

        $eraserRepository = new Eraser();
        $success = $this->assets($eraserRepository) && $this->controller($eraserRepository);

        return $success;
    }

    private function assets( Eraser $eraserRepository ) : bool
    {

        $success = (bool)add_action(
            AssetManager::ACTION_SETUP,
            function (AssetManager $assetManager) use($eraserRepository) {

                $script = new Script(
                    'stage-anonymizer',
                    plugins_url('/assets/js/dist/view.js', $this->rootFile),
                    Asset::BACKEND,
                    [
                        'dependencies' => ['jquery'],
                        'enqueue'      => function () {

                            return isset(get_current_screen()->base) && get_current_screen()->base === 'tools_page_remove_personal_data';
                        },
                        'localize'     => [
                            'StageAnonymizerVars' => [
                                'emailList'  => [
                                    'endpoint' => admin_url('admin-ajax.php'),
                                    'data'     => [
                                        'action'                        => EmailListController::ACTION,
                                        EmailListController::NONCE_NAME => wp_create_nonce(EmailListController::NONCE),
                                    ],
                                    'text'     => [
                                        'finished' => __('Anonymization done.', 'stage-anonymizer'),
                                    ],
                                ],
                                'anonymizer' => [
                                    'endpoint'    => admin_url('admin-ajax.php'),
                                    'data'        => [
                                        'action'                        => AnonymizeController::ACTION,
                                        AnonymizeController::NONCE_NAME => wp_create_nonce(AnonymizeController::NONCE),
                                    ],
                                    'eraserCount' => count($eraserRepository->allErasers()),
                                ],
                            ],
                        ],
                    ]
                );

                $assetManager->register(
                    $script
                );
            }
        );

        return $success;
    }

    private function controller( Eraser $eraserRepository ) : bool
    {

        $success = (bool)add_action(
            'wp_ajax_' . EmailListController::ACTION,
            function () {

                $repository = new EmailList($this->wpdb);
                (new EmailListController($repository))->listen();
            }
        );
        $success = (bool)add_action(
            'wp_ajax_' . AnonymizeController::ACTION,
            function () use($eraserRepository){

                (new AnonymizeController($eraserRepository))->listen();
            }
        )
        && $success;

        return $success;
    }
}