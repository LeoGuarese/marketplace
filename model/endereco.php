<?php
class endereco {
    
    private $rua;
    private $numero;
    private $complemento;
    private $bairro;
    private $cep;
    private $cidade;
    private $estado;


    public function __construct( $rua, $numero, $complemento, $bairro, $cep, $cidade, $estado)
    {
        $this->rua=$rua;
        $this->numero=$numero;
        $this->complemento=$complemento;
        $this->bairro=$bairro;
        $this->cep=$cep;
        $this->cidade=$cidade;
        $this->estado=$estado;

    }

}
?>