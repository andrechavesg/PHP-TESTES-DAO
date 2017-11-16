<?php
namespace src\br\com\caelum\leilao\dominio;

class Usuario
{

    private $id;
    private $nome;
    private $email;
    

    public function __construct(string $nome = "", string $email = "",$id = 0)
    {
        $this->nome = $nome;
        $this->email = $email;
        $this->id = $id;
    }

    public function getNome(): string
    {
        return $this->nome;
    }

    public function getId(): int
    {
        return $this->id;
    }
    
    public function getEmail()
    {
        return $this->email;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setNome($nome)
    {
        $this->nome = $nome;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }
}

	