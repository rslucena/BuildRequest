<?php

class user extends connectData {

    use jsonCovert;

    public function login( $parans = null, $debug = false ) {

        if(empty($parans))
            return null;

        $parans["senha"] = md5($parans["senha"]);

        $col = "id, avatar, concat( nome, ' ', sobrenome) as nome, email, username, tipo";
        $whe = 'email=:email and senha=:senha';

        $sql = "select $col from cliente where $whe limit 1";

        $outp = $this->exQuery( $sql , $parans , $debug );

        echo $this->singleEncode($outp);

    }

}
