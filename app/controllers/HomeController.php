<?php

    declare(strict_types = 1);

    namespace app\controllers;

    use app\Bootstrap\Builder;
    use app\entities\taxonomiaEntity;
    use app\entities\userEntity;
    use app\interfaces\authInterface;
    use app\providers\fileProvider;
    use app\providers\htmlProvider;
    use app\providers\RequestProvider;

    class homeController extends Builder
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
                    '/clientes/loadupdatecliente',
                    null,
                    true)['origin']['data'];

            $this->keys['user'] = (new userEntity($user))->build();

            if (!authInterface::is_access()) {
                $this->redirect('/noaccess');
            }

            $content = new taxonomiaEntity();

            $this->keys['addons'] = fileProvider::enqueue(
                    array( 'addons' => array('svg', 'slider') ),
                    array( 'pages' => array('home'),
                           'components' => array('trade', 'slider') ));

            //Swing Trade
            $swing = RequestProvider::request(
                    'POST',
                    '/swingtrade/loadswingtrade',
                    null,
                    true);

            $code = htmlProvider::titles("Swing Trade", "3");
            $code .= htmlProvider::a("/trades/", "Ver todos",
                    array('class' => 'bold t-1 r-0 absolute btn bt-border btn-circle c-primary'));
            $code .= htmlProvider::hr(array('style' => "margin-left:0", 'class' => 'm-b m-t'), 'c-primary', 10);
            $code .= htmlProvider::div($content->buildTrade($swing, 'Swing Trade'),
                    array('class' => 'flex slider'));
            $this->keys['swingtrade'] = htmlProvider::div($code, array('class' => 'w-6 p-l relative'));


            //Day Trade
            $dayt = RequestProvider::request(
                    'POST',
                    '/relatoriosdoaplicativo/loadrelatorio',
                    array('categoria_id' => '44'),
                    true);

            $code = htmlProvider::titles("Day Trade", "3");
            $code .= htmlProvider::a("/relatorios/filtrar/categoria/44", "Ver todos",
                    array('class' => 'bold t-1 r-0 absolute btn bt-border btn-circle c-primary'));
            $code .= htmlProvider::hr(array('style' => "margin-left:0", 'class' => 'm-b m-t'), 'c-primary', 10);
            $code .= htmlProvider::div($content->buildReports($dayt, 'Day Trade'),
                    array('class' => 'flex slider'));
            $this->keys['daytrade'] = htmlProvider::div($code, array('class' => 'w-6 p-r relative'));

            //Benndorf News
            $News = RequestProvider::request(
                    'POST',
                    '/relatoriosdoaplicativo/loadrelatorio',
                    array('categoria_id' => '41'),
                    true);

            $code = htmlProvider::titles("Benndorf News", "3");
            $code .= htmlProvider::a("/relatorios/filtrar/categoria/41", "Ver todos",
                    array('class' => 'bold t-1 r-0 absolute btn bt-border btn-circle c-primary'));
            $code .= htmlProvider::hr(array('style' => "margin-left:0", 'class' => 'm-b m-t'), 'c-primary', 10);
            $code .= htmlProvider::div($content->buildReports($News, 'Benndorf News'),
                    array('class' => 'flex slider'));
            $this->keys['news'] = htmlProvider::div($code, array('class' => 'w-6 p-r relative'));


            //Benndorf Academy
            $academy = RequestProvider::request(
                    'POST',
                    '/relatoriosdoaplicativo/loadrelatorio',
                    array('categoria_id' => '40'),
                    true);

            $this->keys['academy'] = $content->buildReports($academy, 'BenndorfAcademy');

            $code = htmlProvider::titles("Benndorf Academy", "3");
            $code .= htmlProvider::a("/relatorios/filtrar/categoria/40", "Ver todos",
                    array('class' => 'bold t-1 r-0 absolute btn bt-border btn-circle c-primary'));
            $code .= htmlProvider::hr(array('style' => "margin-left:0", 'class' => 'm-b m-t'), 'c-primary', 10);
            $code .= htmlProvider::div($content->buildReports($academy, 'Benndorf Academy'),
                    array('class' => 'flex slider'));
            $this->keys['academy'] = htmlProvider::div($code, array('class' => 'w-6 p-l relative'));


            //Carteiras
            $wallet = RequestProvider::request(
                    'POST',
                    '/relatoriosdoaplicativo/loadrelatorio',
                    array('categoria_id' => '37'),
                    true);

            $code = htmlProvider::titles("Carteiras", "3");
            $code .= htmlProvider::a("/relatorios/filtrar/categoria/37", "Ver todos",
                    array('class' => 'bold t-1 r-0 absolute btn bt-border btn-circle c-primary'));
            $code .= htmlProvider::hr(array('style' => "margin-left:0", 'class' => 'm-b m-t'), 'c-primary', 10);
            $code .= htmlProvider::div($content->buildReports($wallet, 'Carteiras'),
                    array('class' => 'flex slider'));
            $this->keys['wallet'] = htmlProvider::div($code, array('class' => 'w-6 p-r relative'));

            //Relatorios
            $reports = RequestProvider::request(
                    'POST',
                    '/relatoriosdoaplicativo/loadrelatorio',
                    array('categoria_id' => '42'),
                    true);

            $code = htmlProvider::titles("Relatórios", "3");
            $code .= htmlProvider::a("/relatorios/filtrar/categoria/42", "Ver todos",
                    array('class' => 'bold t-1 r-0 absolute btn bt-border btn-circle c-primary'));
            $code .= htmlProvider::hr(array('style' => "margin-left:0", 'class' => 'm-b m-t'), 'c-primary', 10);
            $code .= htmlProvider::div($content->buildReports($reports, 'Relatórios'),
                    array('class' => 'flex slider'));
            $this->keys['reports'] = htmlProvider::div($code, array('class' => 'w-6 p-l relative'));

            return $this->keys;


        }

        /**
         * Error page
         * 404 error
         *
         * @return array
         */
        public function error404(): array {

            $this->keys['addons'] = fileProvider::enqueue(null, array( 'pages' => array('error')));

            $this->keys['head']['title'] = "Erro 404 - " . APP_NAME;

            return $this->keys;

        }


    }
