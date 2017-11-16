<?php
namespace src\br\com\caelum\leilao\dominio;

use DateTime;

class Lance
{
    private $id;
    private $usuario;
    private $data;
    private $valor;
    private $leilao;
    
    public function __construct(Usuario $usuario, float $valor, Leilao $leilao)
    {
        $this->usuario = $usuario;
        $this->valor = $valor;
        $this->data = new DateTime();
        $this->leilao = $leilao;
    }
    
    public function getId(): int
    {
        return $this->id;
    }
    
    public function getUsuario(): Usuario
    {
        return $this->usuario;
    }
    
    public function getData(): DateTime
    {
        return $this->data;
    }
    
    public function getValor(): float
    {
        return $this->valor;
    }
    
    public function getLeilao(): Leilao
    {
        return $this->leilao;
    }
    
    public function setId(int $id)
    {
        $this->id = $id;
    }
    
    public function setUsuario(Usuario $usuario)
    {
        $this->usuario = $usuario;
    }
    
    public function setData(DateTime $data)
    {
        $this->data = $data;
    }
    
    public function setValor(float $valor)
    {
        $this->valor = $valor;
    }
    
    public function setLeilao(Leilao $leilao)
    {
        $this->leilao = $leilao;
    }
}
