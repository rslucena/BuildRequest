<?php

    declare(strict_types = 1);

    namespace app\controllers;

    use app\Bootstrap\Builder;
    use app\interfaces\authInterface;
    use app\providers\fileProvider;

    class perfilController extends Builder
    {

        public function __construct()
        {

            $this->keys['head']['title'] = "Perfil - ".APP_NAME;
            $this->keys['head']['description'] = APP_DESCRIPTION;

            $this->keys['header'] = $this->requestView('snippets', "header");
            $this->keys['footer'] = $this->requestView('snippets', 'footer');
            $this->keys['copyright'] = $this->requestView('snippets', 'copyright');

        }

        /**
         * Creates the login page
         *
         * @return array
         */
        public function index(): array
        {

            if (authInterface::is_login()) {
                $this->redirect('/');
            }

            $this->keys['addons'] = fileProvider::enqueue(
                    [
                            'addons' => ['toasts'],
                            'pages' => ['login']
                    ],
                    ['pages' => ['login']]
            );

            return $this->keys;

        }

    }
