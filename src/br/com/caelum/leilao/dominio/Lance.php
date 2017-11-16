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
    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return \br\com\caelum\leilao\dominio\Usuario
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return \br\com\caelum\leilao\dominio\float
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * @return mixed
     */
    public function getLeilao()
    {
        return $this->leilao;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @param \br\com\caelum\leilao\dominio\Usuario $usuario
     */
    public function setUsuario($usuario)
    {
        $this->usuario = $usuario;
    }

    /**
     * @param mixed $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @param \br\com\caelum\leilao\dominio\float $valor
     */
    public function setValor($valor)
    {
        $this->valor = $valor;
    }

    /**
     * @param mixed $leilao
     */
    public function setLeilao($leilao)
    {
        $this->leilao = $leilao;
    }

    
    
}


