<?php

class channels extends connectData {

    use jsonCovert;

    function getRecent( $parans = null, $debug = false ) {

        if(empty($parans))
            return null;

        $parans['status'] = 1;

        //COLUMNS
        $col = "id , avatar, titulo, resumo";

        //WHERE
        $whe = isset($parans['textSearch']) ? "hub.titulo like '%".$parans['textSearch']."%' AND " : '';
        $whe .= "hub.status=:status AND ";
        $whe .= "hub.titulo not like 'curso%' and hub.id  not in (select hub_id from servico group by hub_id) ";

        //FAVORITES E CLIENT
        $joi = '';
        if(isset($parans['onlyFavorites']) && isset($parans['clientID'])){
            $whe .= "AND hub_acao.acao='favorito'";
            $joi = "LEFT JOIN hub_acao ON ( hub_acao.hub_id=hub.id AND hub_acao.cliente_id=".$parans['clientID']." ) ";
        }

        //LIMIT
        $lim = $parans["limit"];

        //OFFSET
        $ofs = isset($parans["offset"]) ? $parans["offset"] : 0;

        //ORDERBY
        $parans["orderby"] = isset($parans["orderby"]) ? $parans["orderby"] : 'rand()';

        //CLEAR ARRAY
        unset($parans["offset"]);
        unset($parans['textSearch']);
        unset($parans["onlyFavorites"]);
        unset($parans["limit"]);
        unset($parans["clientID"]);


        //SELECT
        $sql = "select $col from hub $joi where $whe order by :orderby limit $lim offset $ofs";

        //RESULTS
        $results = $this->exQuery( $sql , $parans , $debug );

        switch (array_key_exists('exe' , $results  )) {

            case false :
                echo $results = $this->encodeJson($results);
                break;

            default :
                echo ($this->singleEncode($results));
                break;
        }

    }

}
