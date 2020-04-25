<?php

class courses extends connectData {

    use jsonCovert;

    function getMyCourses( $parans = null, $debug = false ) {

        if(empty($parans))
            return null;

        //DEPENDENCIES
        $parans['status'] = 1;

        //COLUMNS
        $col = "b.id, b.nome, b.avatar, b.hub_id, b.descricao, b.link_curso";

        //JOIN
        $joi = 'inner join servico as b on ( b.id = a.servico_id )';

        //CLEAR ARRAY
        $offset = isset($parans["offset"]) ? $parans["offset"] : 0;
        $clientid = isset($parans["idcliente"]) ? $parans["idcliente"] : null;
        unset($parans);

        //WHERE
        $whe = "b.status = 1 and ";
        $whe .= "b.hub_id > 0 and ";
        $whe .= "a.cliente_id = $clientid and ";
        $whe .= "a.status = 'paid' ";

        //SELECT
        $sql = "select $col from cliente_servico as a $joi where $whe group by a.servico_id order by b.id DESC limit 5 OFFSET $offset";

        //RESULTS
        $results = $this->exQuery( $sql, null , $debug );

        switch (array_key_exists('exe' , $results  )) {

            case false :
                echo $results = $this->encodeJson($results);
                break;

            default :
                echo ($this->singleEncode($results));
                break;
        }

    }

    function getOpenForPremium (){

        $results = array( 'query' => true);

        $results['vals'][] = array(
            'avatar' => "/avatar/hub_81.png" ,
            'hub_id' => "81",
            'nome' => "Insta para Psicólogos"
        );

		$results['vals'][] = array(
            'avatar' => "/avatar/hub_75.jpg" ,
            'hub_id' => "75",
            'nome' => "Planejamento Estratégico para Psicólogos"
        );

		$results['vals'][] = array(
            'avatar' => "/avatar/hub_83.png" ,
            'hub_id' => "83",
            'nome' => "Palográfico Descomplicado"
        );

        $results['vals'][] = array(
            'avatar' => "https://cursos.psico.club/uploads/2019/09/hub_external_express.jpg" ,
            'hub_id' => "https://cursos.psico.club/empreendedorismo/psicologo-empreendedor-express",
            'nome' => "Psicólogo Empreendedor – Express",
            'external' => true
        );

        echo $this->singleEncode($results);

    }

    function getOpenForSubscriptions( $parans = null, $debug = false ) {

        //DEPENDENCIES
        $parans['status'] = 1;
        $now = strtotime(str_replace('/', '-', date("Y-m-d") ));

        //COLUMNS
        $col = "id, nome, avatar, hub_id";

        //WHERE
        $whe  = "a.expiracao >= $now AND ";
        $whe .= "a.status=:status and ";
        $whe .= "a.hub_id > 0";

        //SELECT
        $sql = "select $col from servico as a where $whe Order By Hub_id DESC limit 1000";

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

    function getCheckUserAccess( $parans = null, $debug = false ) {

        //DEPENDENCIES
        $parans['status'] = 'paid';

        //COLUMNS
        $col = "b.hub_id";

        //JOIN
        $joi = 'left join servico as b on ( b.id = a.servico_id )';

        //WHERE
        $whe = "a.servico_id=:idServico and ";
        $whe .= "a.cliente_id=:idcliente and ";
        $whe .= "a.status=:status";

        //CLEAR ARRAY
        unset($parans['offset']);

        //SELECT
        $sql = "select $col from cliente_servico as a $joi where $whe Order By Hub_id DESC limit 1";

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
