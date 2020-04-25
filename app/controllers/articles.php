<?php

require_once ( __API__ . '/helpers/plugins/vimeo/autoload.php');

class articles extends connectData {

    use jsonCovert;

    function getRecent( $parans = null, $debug = false ) {

        if(empty($parans))
            return null;

        $col = "a.hub_id as \"idCanal\" , 
                c.id as \"idConteudo\", 
                a.autor_id as \"idAutor\", 
                c.titulo as \"tituloConteudo\", 
                c.data_publicacao, 
                c.avatar AS banner, 
                c.resumo, 
                c.assinante, 
                (SELECT COUNT(id) FROM comentario WHERE conteudo_id = c.id) AS comentarios, 
                h.titulo AS hub_titulo, 
                CONCAT(COALESCE(aut.nome,' '),' ',COALESCE(aut.sobrenome,' ')) autor,
                aut.avatar as AvatarAutor";

        $whe = "TRIM(h.titulo) NOT Like 'Curso%' and
                c.status=:status and h.status=:status";

        $joi = "RIGHT JOIN hub AS h ON (a.hub_id = h.id)
                RIGHT JOIN conteudo AS c ON (a.conteudo_id = c.id)
                LEFT JOIN cliente AS aut ON (a.autor_id = aut.id)";

        $grp = "tituloConteudo";

        $ord = "c.data_publicacao DESC";

        $lim = $parans["limit"];

        $sql = "select $col from conteudo_hub as a $joi where $whe group by $grp order by $ord limit $lim";

        $results = $this->exQuery( $sql , $parans , $debug );

        return $this->returnJson( $results );

    }

    function getTimeLine( $parans = null, $debug = false ) {

        if(empty($parans))
            return null;

        $col = "a.hub_id as 'idCanal',
                c.id as 'idConteudo', 
                a.autor_id as 'idAutor', 
                c.titulo, 
                c.data_publicacao, 
                c.avatar as banner, 
                c.resumo, 
                h.titulo as hub_titulo, 
                Concat( cc.nome, ' ', cc.sobrenome) as NomedoAutor, 
                cc.avatar as AvatarAutor, 
                c.assinante";


        $whe = "c.status=:status and 
                h.status=:status and ";

        //filter for hub / id
        $whe .= isset($parans['hubID']) ? "a.hub_id=".$parans['hubID']." ": "a.hub_id not in (select hub_id from servico group by hub_id) ";
        unset($parans['hubID']);

        //filter text search / title
        $whe .= isset($parans['textSearch']) ? "and c.titulo like '%".$parans['textSearch']."%'" : '';
        unset($parans['textSearch']);

        //only free account
        $whe .= isset( $parans['onlyFreeAccount'] ) ? "and c.assinante = 0" : '';
        unset($parans['onlyFreeAccount']);

        $joi = "RIGHT JOIN conteudo AS c ON (a.conteudo_id = c.id) 
                RIGHT JOIN hub AS h ON (a.hub_id = h.id) 
                RIGHT JOIN cliente AS cc ON (a.autor_id = cc.id)";

        $grp = "titulo";

        $ord = "c.data_publicacao";

        $off = $parans['offset']; unset($parans['offset']);

        $lim = $parans['limit'];

        $sql = "select $col from conteudo_hub as a $joi where $whe group by $grp order by $ord desc limit $lim offset $off";

        $results = $this->exQuery( $sql , $parans , $debug );

        return $this->returnJson( $results );

    }

    function getContent( $parans = null, $debug = false ) {

        if(empty($parans))
            return null;

        $parans['status'] = 1;

        $col = "ch.autor_id as \"autorID\", 
                ch.hub_id as \"canalID\", 
                ch.conteudo_id as \"conteudoID\", 
                cc.assinante, 
                cc.titulo, 
                cc.data_publicacao, 
                cc.avatar as \"banner\", 
                cc.descricao, 
                cc.visitas, 
                COUNT(fav.id) as qunatFav,
                h.avatar as \"capa_canal\",
                h.titulo as \"titulo_canal\",
                auth.avatar as \"avatar_autor\",
                LEFT(auth.minibio, 100) as \"mini_bio\",
                auth.tipo as \"tipo_ass\",
                CONCAT(auth.nome, \" \", auth.sobrenome) as \"nome_autor\"";

        $whe = "ch.conteudo_id=:contentID AND ch.hub_id=:hubID and cc.status= :status";

        $joi = "LEFT JOIN conteudo AS cc ON (ch.conteudo_id = cc.id) 
                LEFT JOIN hub AS h ON (ch.hub_id = h.id) 
                LEFT JOIN cliente AS auth ON ( ch.autor_id = auth.id ) 
                LEFT JOIN conteudo_favorito AS fav ON ( ch.conteudo_id = fav.conteudo_id )";

        unset($parans['limit']);

        $sql = "select $col from conteudo_hub as ch $joi where $whe limit 1";

        $results = $this->exQuery( $sql , $parans , $debug );

        return $this->returnJson( $results );

    }

    function getVimeo( $parans = null ) {

        $filterResult = [];

        if(empty($parans['id']))
            return null;

        $clientID = "bf8c1ac1de8d083531ba48e6dd4a83b59cdbaa4e";
        $clientSecret = "BVS5Kkhsk9chVJN8TTu5RxJqJyUSi1XRXMv0DmxhGTqLFkAXcM4nchHOBpXhfOD6EUaSxwQ4C3GjYS54AN0gSl7Y5+kPH7H4w1PjhhduCl+iI460gVv+blph7Jd0ZKak";
        $accessToken = "a24425abf29b92d7f8f5431bf7da9540";

        $pluginVimeo = new \Vimeo\Vimeo( $clientID , $clientSecret, $accessToken);

        $request = $pluginVimeo->request( "/videos/$parans[id]" );

        switch (array_key_exists('error' , $request  )) {
            case false :
                $request = $request['body'];
                $filterResult['duration'] = $request["duration"];
                $filterResult['thumb'] =  $request['pictures']['sizes'][4]['link'];
                $filterResult['link'] =  $request['files'][0]['link'];
                break;
            default :
                $filterResult['exe'] = false;
                break;
        }

        return $this->returnJson( $filterResult );

    }

    function getComments( $parans = null, $debug = false ){

        if (empty($parans['contentID']) && empty($parans['hubID']))
            return null;

        unset($parans['limit']);
        unset($parans['status']);


        $tab3 = "UNION ALL select 
                b.cliente_id, 
                null AS 'comentario', 
                DATE_FORMAT(b.time, '%d/%m/%Y') as data 
                from conteudo_favorito as b 
                where b.conteudo_id =:contentID) as a 
                join cliente as c on c.id = a.cliente_id order by data ASC";

        $tab2 = "(select 
                    a.cliente_id, 
                    a.comentario, 
                    DATE_FORMAT(a.time, '%d/%m/%Y') AS data 
                    from comentario as a where a.conteudo_id = :contentID and a.hub_id = :hubID ";

        $tab1 = "select 
                    a.cliente_id,
                    a.comentario, 
                    a.data, 
                    c.avatar as 'avatar_autor', 
                    CONCAT(c.nome, ' ', c.sobrenome) as 'nome_autor' from $tab2 $tab3 " ;

        $results = $this->exQuery( $tab1 , $parans , $debug );

        return $this->returnJson( $results );
    }

    //Return Json to parent
    private function returnJson( $results ){

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
