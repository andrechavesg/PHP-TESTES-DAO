<?php
namespace src\br\com\caelum\leilao\dominio;

class Lance
{
    private $id;
    private $usuario;
    private $data;
    private $valor;
    private $leilao;
    
    public function __construct(Usuario $usuario, float $valor)
    {
        $this->usuario = $usuario;
        $this->valor = $valor;
    }
    
    public function getId()
    {
        return $this->id;
    }

    public function getUsuario()
    {
        return $this->usuario;
    }

    public function getData()
    {
        return $this->data;
    }

    public function getValor()
    {
        return $this->valor;
    }

    public function getLeilao()
    {
        return $this->leilao;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setUsuario($usuario)
    {
        $this->usuario = $usuario;
    }

    public function setData($data)
    {
        $this->data = $data;
    }

    public function setValor($valor)
    {
        $this->valor = $valor;
    }

    public function setLeilao($leilao)
    {
        $this->leilao = $leilao;
    }

    
    
}


