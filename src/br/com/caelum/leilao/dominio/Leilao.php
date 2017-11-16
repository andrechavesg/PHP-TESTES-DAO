<?php
namespace src\br\com\caelum\leilao\dominio;

use DateTime;

class Leilao
{

    private $id;
    private $nome;
    private $valorInicial;
    private $dono;
    private $dataAbertura;
    private $usado;
    private $encerrado;
    private $lances;

    public function __construct(string $descricao,float $valorInicial)
    {
        $this->descricao = $descricao;
        $this->dataAbertura = new DateTime();
        $this->encerrado = false;
        $this->valorInicial = $valorInicial;
    }

    public function getDescricao(): string
    {
        return $this->descricao;
    }

    public function getLances(): array
    {
        return $this->lances;
    }

    public function propoe(Lance $lance)
    {
        if (empty($this->lances) || $this->podeDarLance($lance->getUsuario())) {
            $this->lances[] = $lance;
        }
    }
    
    public function isEncerrado()
    {
        return $this->encerrado;
    }

    public function encerra()
    {
        $this->encerrado = true;
    }

    private function ultimoLanceDado(): Lance
    {
        return $this->lances[count($this->lances) - 1];
    }

    private function qtdDelancesDo(Usuario $usuario): int
    {
        $total = 0;
        
        foreach ($this->lances as $l) {
            if ($l->getUsuario() == $usuario)
                $total ++;
        }
        
        return $total;
    }

    private function podeDarLance(Usuario $usuario): bool
    {
        return $this->ultimoLanceDado()->getUsuario() != $usuario && $this->qtdDelancesDo($usuario) < 5;
    }
    
    public function getId()
    {
        return $this->id;
    }

    public function getNome()
    {
        return $this->nome;
    }

    public function getValorInicial()
    {
        return $this->valorInicial;
    }

    public function getDono()
    {
        return $this->dono;
    }

    public function getDataAbertura() : DateTime
    {
        return $this->dataAbertura;
    }

    public function getUsado()
    {
        return $this->usado;
    }

    public function getEncerrado()
    {
        return $this->encerrado;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setNome($nome)
    {
        $this->nome = $nome;
    }

    public function setValorInicial($valorInicial)
    {
        $this->valorInicial = $valorInicial;
    }

    public function setDono($dono)
    {
        $this->dono = $dono;
    }

    public function setDataAbertura(DateTime $dataAbertura)
    {
        $this->dataAbertura = $dataAbertura;
    }

    public function setUsado($usado)
    {
        $this->usado = $usado;
    }

    public function setEncerrado($encerrado)
    {
        $this->encerrado = $encerrado;
    }

    public function setLances($lances)
    {
        $this->lances = $lances;
    }
}