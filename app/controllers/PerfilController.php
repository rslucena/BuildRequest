<?php

    declare(strict_types = 1);

    namespace app\controllers;

    use app\Bootstrap\Builder;
    use app\entities\UserEntity;
    use app\interfaces\AuthInterface;
    use app\providers\FileProvider;
    use app\providers\RequestProvider;

    class ProfileController extends Builder
    {

        public function __construct()
        {

            $this->keys['head']['title'] = "Profile - ".APP_NAME;
            $this->keys['head']['description'] = APP_DESCRIPTION;

            $this->keys['header'] = $this->requestView('snippets', "header");
            $this->keys['footer'] = $this->requestView('snippets', 'footer');
            $this->keys['copyright'] = $this->requestView('snippets', 'copyright');

            if (!AuthInterface::is_login()) {
                $this->redirect('/login');
            }

            $user = RequestProvider::request(
                    'POST',
                    '/client/',
                    null,
                    true)['origin']['data'];

            $this->keys['user'] = (new userEntity($user))->build();
        }

        /**
         * Creates the login page
         *
         * @return array
         */
        public function index(): array
        {

            return $this->keys;

        }

        /**
         * Logout
         * Exit the system in a simplified way
         *
         * @return void
         */
        public function logout(): void {

            AuthInterface::logout();

            $this->redirect('/');

        }


        /**
         * No access page
         * Blocked content based on user access type
         *
         * @return array
         */
        public function noaccess(): array {

            if (AuthInterface::is_access() === true) {
                $this->redirect('/profile');
            }

            $this->keys['addons'] = FileProvider::enqueue(null, array( 'pages' => array('error')));

            $this->keys['head']['title'] = "Profile: No access - " . APP_NAME;

            return $this->keys;

        }

    }
