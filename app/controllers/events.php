<?php

class events extends connectData {

    use jsonCovert;

    function getContent( $parans = null, $debug = false ) {

        if(empty($parans))
            return null;

        $col = "item_id, nome, indice, valor";
        $whe = "status=:status and item_id IN (2 , 3)";

        $sql = "select $col from personalizacao_atributo where $whe";

        $results = $this->exQuery( $sql , $parans , $debug );

        switch (array_key_exists('exe' , $results  )) {

            case false :
                $results["vals"] = $this->unifiqueArray($results["vals"]);
                echo $results = $this->encodeJson($results);
                break;

            default :
                echo ($this->singleEncode($results));
                break;
        }

    }

    //UNIFIQUE ARRAY - RETURN ARRAY/CLEAR
    private function unifiqueArray( $out ) {



        if(array_key_exists('indice' , $out[0]  ) === false)
            return $out;

        $results = [];
        $countItens = [];


        foreach ( $out as $val) {
            $countItens[] = $val['item_id'];
        }

        for ($i = 1; $i <= max(array_unique($countItens)); $i++) {
            $temp = [];
            foreach ( $out as $item ) {
                if($item['item_id'] == $i) :
                    $temp[$item['nome']] = $item['valor'];
                    if(json_decode($item['valor']) == true) {
                        $obj =  json_decode( $item['valor'], false );
                        $temp['link'] = $obj->link;
                        $temp['tipo_link'] = $obj->tipo_link;
                    }
                endif;
            }
            array_push($results, $temp);
        }

        foreach($results as $key => $value)
            if(empty($value))
                unset($results[$key]);

        $results = array_values($results);

        return $results;
    }


}
