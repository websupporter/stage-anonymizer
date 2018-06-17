<?php
declare(strict_types = 1);

namespace Websupporter\StageAnonymizer\Repository;

use Websupporter\StageAnonymizer\Anonymizer\WpUserAnonymizer;
use Websupporter\StageAnonymizer\Exceptions\RuntimeException;

class Eraser
{

    const ERASER_FOR_STAGE_ANONYMIZATION = 'stage-anonymizer.eraser';

    private $eraser = [];
    /**
     * @param int $index
     *
     * @return array
     * @throws RuntimeException
     */
    public function byIndex( int $index ) : array {

        if( $index < 1 ) {
            throw new RuntimeException('Eraser index must be at least 1.');
        }
        $index--;
        $erasers = $this->allErasers();
        if( $index > count( $erasers ) ) {
            throw new RuntimeException('Eraser index exceeds number of erasers.');
        }

        $indices = array_keys( $erasers );
        if( ! isset( $indices[ $index ] ) ) {
            throw new RuntimeException('Eraser not found.');
        }

        $key = $indices[ $index ];
        $eraser = $erasers[ $key ];
        if(! is_array($eraser) || ! isset( $eraser['eraser_friendly_name'] ) || ! isset( $eraser['callback'] ) || ! is_callable($eraser['callback'] ) ) {
            throw new RuntimeException('Malformed eraser.');
        }
        return $eraser;
    }

    public function allErasers() : array
    {

        if( count( $this->eraser ) ) {
            return $this->eraser;
        }
        $erasers = apply_filters( 'wp_privacy_personal_data_erasers', array() );

        $wpUserAnonymizer = new WpUserAnonymizer();
        $erasers[$wpUserAnonymizer->id()] = [
            'eraser_friendly_name' => $wpUserAnonymizer->name(),
            'callback' => [ $wpUserAnonymizer, 'callback' ],
        ];
        $this->eraser = (array) apply_filters( self::ERASER_FOR_STAGE_ANONYMIZATION, $erasers );
        return $this->eraser;
    }
}
