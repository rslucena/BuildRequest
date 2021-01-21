<?php

    declare(strict_types = 1);

    namespace app\entities;

    use app\providers\HtmlProvider;
    use app\providers\RequestProvider;


    class GeolocationEntity extends RequestProvider
    {

        public $ip = array();

        /**
         * Captures a country's state list
         *
         * @param string $selected;
         *
         * @return array
         */
        public static function states( $selected = ""): array
        {

            $request = RequestProvider::request(
                    'GET',
                    '/localidades/estados',
                    null,
                    null,
                    API_GEOLOCATION
            );

            $states = $request['origin'];
            $filter = array();

            if (!empty($states)) {

                foreach ($states as $state) {

                    $filter[] = array(
                            'name' => $state['sigla'],
                            'text' => $state['nome'],
                            'selected' => $selected === $state['sigla']
                    );

                }

            }

            return self::build($filter);

        }


        /**
         * Returns when requesting
         *
         *@param array  $itens
         *
         * @return array
         */

        public static function build( $itens = array() ): array
        {
            $result = array( 'itens' => $itens, 'html' => '');

            if( !empty( $itens ) ){
                $result['html'] = HtmlProvider::option( $itens );
            }

            return $result;

        }

    }