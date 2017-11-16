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

    public function __construct(string $nome = "",float $valorInicial = 0,Usuario $dono = null,bool $encerrado = false,bool $usado = false)
    {
        $this->nome = $nome;
        $this->dataAbertura = new DateTime();
        $this->dono = $dono;
        $this->encerrado = $encerrado;
        $this->valorInicial = $valorInicial;
        $this->usado = $usado;
        $this->lances = array();
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
    
    public function isEncerrado(): bool
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
    
    public function getId(): int
    {
        return $this->id;
    }

    public function getNome(): string
    {
        return $this->nome;
    }

    public function getValorInicial(): float
    {
        return $this->valorInicial;
    }

    public function getDono(): Usuario
    {
        return $this->dono;
    }

    public function getDataAbertura(): DateTime
    {
        return $this->dataAbertura;
    }

    public function getUsado(): bool
    {
        return $this->usado;
    }

    public function getEncerrado(): bool
    {
        return $this->encerrado;
    }

    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function setNome(string $nome)
    {
        $this->nome = $nome;
    }

    public function setValorInicial(float $valorInicial)
    {
        $this->valorInicial = $valorInicial;
    }

    public function setDono(Usuario $dono)
    {
        $this->dono = $dono;
    }

    public function setDataAbertura(DateTime $dataAbertura)
    {
        $this->dataAbertura = $dataAbertura;
    }

    public function setUsado(bool $usado)
    {
        $this->usado = $usado;
    }

    public function setEncerrado(bool $encerrado)
    {
        $this->encerrado = $encerrado;
    }

    public function setLances(array $lances)
    {
        $this->lances = $lances;
    }
}
	