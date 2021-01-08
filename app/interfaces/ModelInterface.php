<?php

    declare(strict_types = 1);

    namespace app\interfaces;

    use PDO;
    use PDOException;

    class ModelInterface
    {

        private $connection;
        private $debug = false;

        /**
         * Enables debug mode to
         * debug model actions
         */
        public function enableDebug(): void
        {
            $this->debug = true;
        }

        /**
         * Disable debug mode to
         * debug model actions
         */
        public function disableDebug(): void
        {
            $this->debug = false;
        }

        /**
         * Fetch data from the database
         *
         * @param string  $table
         * @param string  $column
         * @param array  $props
         *
         * @return array
         */
        public function getData(string $table, string $column = "a.*", $props = array()): array
        {

            $this->connect();

            $sql = "SELECT $column "."FROM `$table` as a ";

            //JOIN
            if (array_key_exists('join', $props)) {

                if (is_array($props['join'])) {
                    foreach ($props['join'] as $key => $value) {
                        $sql .= " $value ";
                    }
                } else {
                    $sql .= " {$props['join']} ";
                }
            }

            //if it is an array iterates the joins
            $sql .= "WHERE a.id >= 1 ";

            //Filter
            if (array_key_exists('filter', $props)) {
                $sql .= $this->makeFilters($props['filter']);
            }

            //Group by
            if (array_key_exists('groupby', $props)) {

                $sql .= " group by {$props['groupby']} ";

            }

            //Having
            if (array_key_exists('having', $props)) {
                $sql .= " having {$props['having']} ";
            }

            //Order by
            if (array_key_exists('orderby', $props)) {
                $sql .= "ORDER BY {$props['orderby']} ";
            }

            //Limits
            if (array_key_exists('limits', $props)) {

                $sql .= 'Limit ';

                if (!empty($props['limits'][0])) {
                    $sql .= $props['limits'][0];
                }

                if (!empty($props['limits'][1])) {
                    $sql .= ", {$props['limits'][1]} ";
                }

            }

            $sql .= ";";

            if ($this->debug === true) {
                echo "\n $sql \n\n";
            }

            try {

                $query = $this->connection->query($sql);

                $data = $query->fetchAll(PDO::FETCH_ASSOC);

                $res = $this->buildReturn(false, $data);

            } catch (PDOException $e) {

                logInterface::save('Connection failed', $e->getMessage());

                $res = $this->buildReturn(true);

            }

            $this->disconnect();

            return $res;

        }

        /**
         * Create database connection
         */
        public function connect(): void
        {

            $server = DB_SERVE;
            $database = DB_NAME;
            $username = DB_USER;
            $password = DB_PASS;
            $charset = DB_CHARSET;

            try {
                $dsn = "mysql:host=$server;dbname=$database;charset=$charset";
                $this->connection = new PDO($dsn, $username, $password);
                $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            } catch (PDOException $e) {

                logInterface::save('Connection failed', $e->getMessage().PHP_EOL);
                die();

            }

        }

        /**
         * Function responsible for creating sql filters
         *
         * @param $filters
         *
         * @return string
         */
        private function makeFilters($filters)
        {
            $sql = '';
            if ($filters != '') {
                foreach ($filters as $key => $value) {
                    //LIKE
                    if (substr_count($key, 'like') == 1) {

                        if (substr(trim($key), 0, 10) == 'likeAfter ') {
                            $key = str_replace('likeAfter', '', $key);
                            $sql .= "AND $key like '$value%' ";
                        } else {
                            if (substr(trim($key), 0, 11) == 'likeBefore ') {
                                $key = str_replace('likeBefore', '', $key);
                                $sql .= "AND $key like '%$value' ";
                            } elseif (substr(trim($key), 0, 8) == 'like or ') {
                                $close = "";
                                if (substr(trim($key), -1) == ')') {
                                    $key = str_replace(')', '', $key);
                                    $close = ')';
                                }
                                $key = str_replace('like or ', '', $key);
                                $sql .= "OR $key like '%$value%' $close";
                            } elseif (substr(trim($key), 0, 8) == 'notlike ') {
                                $key = str_replace('notlike ', '', $key);
                                $sql .= "AND $key NOT LIKE '$value' ";
                            } else {
                                $key = str_replace('like ', '', $key);
                                $sql .= "AND $key like '%$value%' ";
                            }
                        }
                    } // OR
                    elseif (substr(trim($key), 0, 3) == 'or ') {
                        $key = str_replace('or ', '', $key);
                        $sql .= "OR $key = '$value' ";
                    } // IN
                    elseif (substr(trim($key), 0, 3) == 'in ') {
                        $key = str_replace('in ', '', $key);
                        $sql .= "AND $key in $value ";
                    } // IS
                    elseif (substr(trim($key), 0, 3) == 'is ') {
                        $key = str_replace('is ', '', $key);
                        $sql .= "AND $key IS $value ";
                    } // NOTIN
                    elseif (substr(trim($key), 0, 6) == 'notin ') {
                        $key = str_replace('notin ', '', $key);
                        $sql .= "AND $key not in $value ";
                    } // DIF
                    elseif (substr(trim($key), 0, 4) == 'dif ') {
                        $key = str_replace('dif', '', $key);
                        $sql .= "AND $key != '$value' ";
                    } // <=
                    elseif (substr(trim($key), 0, 2) == '<=') {
                        $key = str_replace('<=', '', $key);
                        $sql .= "AND $key <= $value ";
                    } // >=
                    elseif (substr(trim($key), 0, 2) == '>=') {
                        $key = str_replace('>=', '', $key);
                        $sql .= "AND $key >= $value ";
                    } // <
                    elseif (substr(trim($key), 0, 1) == '<') {
                        $key = str_replace('<', '', $key);
                        $sql .= "AND $key < $value ";
                    } // >
                    elseif (substr(trim($key), 0, 1) == '>') {
                        $key = str_replace('>', '', $key);
                        $sql .= "AND $key > $value ";
                    } //SQL
                    elseif (substr(trim($key), 0, 3) == 'sql') {
                        $key = str_replace('sql', '', $key);
                        $sql .= " $value ";
                    } // =
                    else {
                        if (is_string($value)) {
                            $sql .= "AND $key = '$value' ";
                        } else {
                            $sql .= "AND $key = $value ";
                        }
                    }
                }
            }

            return $sql;
        }

        /**
         * Create return model
         * for executions
         *
         * @param bool  $is_error
         * @param array  $base
         *
         * @return array
         */
        private function buildReturn($is_error = false, $base = array())
        {

            $response = array();

            $response['origin'] = array(
                    "data" => array(),
                    "status" => "success"
            );
            $response['Router'] = $_REQUEST;

            if ($is_error) {
                $response['origin']['status'] = 'error';
            }

            if (!empty($base)) {
                $response['origin']['data'] = $base;
            }

            return $response;

        }

        /**
         * Disconect database connection
         */
        public function disconnect(): void
        {
            $this->connection = null;

        }

    }