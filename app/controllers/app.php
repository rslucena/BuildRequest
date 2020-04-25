<?php

class app{

    use jsonCovert;

    public function getCurrentVersion() {

        $maintenance = false;

        $outp = array('query' => true);
        $outp += array( 'vals' => array('version' => '2.1.4', 'message' => 'Uma nova versão está disponivel. É necessário que o app seja atualizado.' ));

        if($maintenance == true){
            $outp['vals']['maintenance'] = true;
            $outp['vals']['message'] = 'O servidor está no momento em manutenção. Favor tente mais tarde.';
        }

        echo $this->singleEncode($outp);

    }

}
