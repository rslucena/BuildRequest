<?php

    declare(strict_types = 1);

    namespace app\controllers;

    use app\Bootstrap\Builder;
    use app\interfaces\authInterface;
    use app\providers\fileProvider;
    use app\providers\RequestProvider;
    use app\providers\ResultProvider;

    class loginController extends Builder
    {

        public function __construct()
        {

            $this->keys['head']['title'] = "Login - ".APP_NAME;
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

        /**
         * Register and log in user
         *
         * @param ? $_REQUEST
         *
         * @return void
         */
        public function createlogin(): void
        {

            $params = RequestProvider::buildInput();

            if (!RequestProvider::validate($params, "required")) {
                ResultProvider::result();
            }

            $valid[] = RequestProvider::validate($params['current-email'], "required|email");
            $valid[] = RequestProvider::validate($params['current-password'], "required|min:7");

            if (in_array(false, $valid)) {
                ResultProvider::result(0);
            }

            $props = array(
                    'current_mail' => $params['current-email'],
                    'current_pass' => $params['current-password'],
                    'current_networks' => false
            );

            $result = RequestProvider::request(
                    'POST',
                    '/clientes/createtokenaccess',
                    $props
            )['origin'];

            if ($result['status'] === 'error') {
                ResultProvider::result(0, $result);
            }

            //Login process
            $aut = authInterface::auth($result['data']['access_token']);

            if ($aut) {
                ResultProvider::result(2, $result);
            }

            ResultProvider::result(0, $result);

        }


    }
