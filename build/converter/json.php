<?php

namespace Build\Converter;

class json {

    private $return;

    //Single convert Json
    public function singleEncode( $json ){

        return json_encode($json, JSON_PRETTY_PRINT);

    }

    //Encode Safe Json Encode
    public function encodeJson($json , $options = 0, $depth = 512, $utfErrorFlag = false) {

        $encoded = json_encode($json, $options, $depth);

        switch (json_last_error() ) {

            case JSON_ERROR_NONE:
                $this->return = $encoded;
                break;

            case JSON_ERROR_DEPTH:
                $this->return = 'Profundidade máxima da pilha excedida';
                break;

            case JSON_ERROR_STATE_MISMATCH:
                $this->return = 'Underflow ou a incompatibilidade de modos';
                break;

            case JSON_ERROR_CTRL_CHAR:
                $this->return = 'Caractere de controle inesperado encontrado';
                break;

            case JSON_ERROR_SYNTAX:
                $this->return = 'Erro de sintaxe, JSON malformado';
                break;

            case JSON_ERROR_UTF8:

                $clean = $this->utf8ize($json);

                if ($utfErrorFlag)
                    $this->return = 'Erro de codificação UTF8';

                $this->return = $this->encodeJson($clean, $options, $depth, true);
                break;

            default:
                $this->return = 'Unknown error';

        }

        return $this->return;

    }

    // Force UTF8
    private function utf8ize($mixed) {
        if (is_array($mixed)) {
            foreach ($mixed as $key => $value) {
                $mixed[$key] = $this->utf8ize($value);
            }
        } else if (is_string ($mixed)) {
            return utf8_encode($mixed);
        }
        return $mixed;
    }

}