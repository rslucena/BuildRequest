<?php

    declare(strict_types = 1);

    namespace app\controllers;

    use app\Bootstrap\Builder;
    use app\entities\UserEntity;
    use app\interfaces\AuthInterface;
    use app\providers\FileProvider;
    use app\providers\HtmlProvider;
    use app\providers\RequestProvider;

    class HomeController extends Builder
    {

        public function __construct()
        {

            $this->keys['head']['title'] = APP_NAME;
            $this->keys['head']['description'] = APP_DESCRIPTION;

            $this->keys['header'] = $this->requestView('snippets', "header");
            $this->keys['footer'] = $this->requestView('snippets', 'footer');
            $this->keys['navbar'] = $this->requestView('snippets', 'navbar');

            $this->keys['sidebar'] = $this->requestView('snippets', 'sidebar');
            $this->keys['copyright'] = $this->requestView('snippets', 'copyright');

        }

        /**
         * Main page
         *
         * @return array
         */
        public function index(): array
        {

            if (!authInterface::is_login()) {
                $this->redirect('/login');
            }

            $user = RequestProvider::request(
                    'POST',
                    '/client',
                    null,
                    true)['origin']['data'];

            $this->keys['user'] = (new userEntity($user))->build();

            if (authInterface::is_access() === false) {
                $this->redirect('/noaccess');
            }


            return $this->keys;


        }

        /**
         * Error page
         * 404 error
         *
         * @return array
         */
        public function error404(): array
        {

            $this->keys['addons'] = fileProvider::enqueue(null, array('pages' => array('error')));

            $this->keys['head']['title'] = "Erro 404 - ".APP_NAME;

            return $this->keys;

        }

    }
