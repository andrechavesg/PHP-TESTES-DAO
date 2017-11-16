<?php
namespace src\br\com\caelum\leilao\dao;

use \PDO;
use \DateTime;
use \DateInterval;
use src\br\com\caelum\leilao\dominio\Leilao;
use src\br\com\caelum\leilao\dominio\Lance;
use src\br\com\caelum\leilao\dominio\Usuario;

class LeilaoDao {

    private $con;
    
    public function __construct(PDO $con) {
        $this->con = $con;
    }
    
    public function salvar(Leilao $leilao) {
        $stmt = $this->con->prepare("insert into Leilao(nome,valorInicial,dono,dataAbertura,usado,encerrado) values(':nome',:valorInicial,:dono,':dataAbertura',:usado,:encerrado)");
        
        $stmt->bindParam("nome", $leilao->getNome());
        $stmt->bindParam("valorInicial", $leilao->getValorInicial());
        $stmt->bindParam("dono", $leilao->getDono()->getId());
        $stmt->bindParam("dataAbertura", $leilao->getDataAbertura()->format("Y-mm-dd"));
        $stmt->bindParam("usado",$leilao->getUsado());
        $stmt->bindParam("encerrado",$leilao->isEncerrado());
        
        $stmt->execute();
        
        foreach ($leilao->getLances() as $lance){
            $this->salvarLance($lance);
        }
    }
	
	private function salvarLance(Lance $lance) {
	    $stmt = $this->con->prepare("insert into Lance(usuario,data,valor,leilao) values(:usuario,':data',:valor,:leilao)");
	    
	    $stmt->bindParam("usuario",$lance->getUsuario()->getId());
	    $stmt->bindParam("data",$lance->getData()->format("Y-mm-dd"));
	    $stmt->bindParam("valor",$lance->getValor());
	    $stmt->bindParam("leilao",$lance->getLeilao()->getId());
	    
	    $stmt->execute();
	}
	
	public function porId(int $id) : Leilao {
		$stmt = $this->con->prepare("select * from Leilao where id = :id");
		$stmt->bindParam("id",$id);
	    $stmt->execute();
		
		$leilao = $stmt->fetchOject(Leilao::class);
		
	    return $leilao;
	}
	
	public function novos() : array {
	    $stmt = $this->con->prepare("select * from Leilao where usado = :usado");
	    $stmt->bindParam("usado",false);
	    $stmt->execute();
	    
	    $novos = $stmt->fetchAll(PDO::FETCH_OBJ,Leilao::class);
	    
		return $novos;
	}
	
	public function antigos() : array {
		$seteDiasAtras = new DateTime();
		$seteDiasAtras->sub(new DateInterval('P7D'));
		
		$stmt = $this->con->prepare("select * from Leilao where dataAbertura < ':dataAbertura'");
		$stmt->bindParam("dataAbertura",$seteDiasAtras->format("Y-mm-dd"));
		$stmt->execute();
		
		$antigos  = $stmt->fetchAll(PDO::FETCH_OBJ,Leilao::class);
		
		return $antigos;
	}
	
	public function porPeriodo(DateTime $inicio, DateTime $fim) : array {
	    $stmt = $this->con->prepare("select * from Leilao where dataAbertura < ':fim' and dataAbertura > ':inicio'");
	    $stmt->bindParam("inicio",$inicio->format("Y-mm-dd"));
	    $stmt->bindParam("fim",$fim->format("Y-mm-dd"));
	    $stmt->execute();
	    
	    $antigos  = $stmt->fetchAll(PDO::FETCH_OBJ,Leilao::class);
	    
	    return $antigos;
	}
	
	public function disputadosEntre(float $inicio, float $fim) : array {
	    $stmt = $this->con->prepare("select le.*,COUNT(la.id) as lances from Leilao as le JOIN Lance as la ON le.id = la.leilao where le.dataAbertura < ':fim' and le.dataAbertura > ':inicio' GROUP BY la.id HAVING lances > 0");
	    $stmt->bindParam("inicio",$inicio->format("Y-mm-dd"));
	    $stmt->bindParam("fim",$fim->format("Y-mm-dd"));
	    $stmt->execute();	    
	    
	    $disputados = $stmt->fetchAll(PDO::FETCH_OBJ,Leilao::class);
	    
	    return $disputados;
	}
	
	public function total() {
		$stmt = $this->con->prepare("select Count(*) from Leilao where encerrado = :encerrado");
		$stmt->bindParam("encerrado",false);
	    $stmt->execute();
	    
	    return $stmt->fetchColumn();
	}
	
	public function atualiza(Leilao $leilao) {
		$stmt = $this->con->prepare("update Leilao set nome = ':nome',valorInicial = :valorInicial,dono = :dono,usado = :usado,encerrado = :encerrado");
		$stmt->bindParam("usuario",$lance->getUsuario()->getId());
		$stmt->bindParam("data",$lance->getData()->format("Y-mm-dd"));
		$stmt->bindParam("valor",$lance->getValor());
		$stmt->bindParam("leilao",$lance->getLeilao()->getId());
		
	    $stmt->execute();
	}
	
	public function deleta(Leilao $leilao) {
		$stmt = $this->con->preapre("delete from Leilao where id = :id");
		$stmt->bindParam("id",$leilao->getId());
		
		$stmt->execute();
	}
	
	public function deletaEncerrados() {
	    $stmt = $this->con->prepare("delete from Leilao where encerrado = :encerrado");
	    $stmt->bindParam("encerrado",$leilao->getEncerrado());
	    
	    $stmt->execute();
	}
	
	public function listaLeiloesDoUsuario(Usuario $usuario) {
		$stmt = $this->con->prepare("select le.* from Leilao as le LEFT JOIN Lance la ON la.leilao = le.id Left Join Usuario u ON la.usuario = :usuario");
        $stmt->bindParam("usuario",$usuario->getId());
        $stmt->execute();
        
        $doUsuario = $stmt->fetchAll(PDO::FETCH_OBJ,Leilao::class);
        
        return $doUsuario;
	}
	
	public function getValorInicialMedioDoUsuario(Usuario $usuario) {
	    $stmt = $this->con->prepare("select avg(le.valorInicial) from Leilao as le LEFT JOIN Lance la ON la.leilao = le.id Left Join Usuario u ON la.usuario = :usuario");
	    $stmt->bindParam("usuario",$usuario);
	    $stmt->execute();
	    
	    $valor = $stmt->fetchColumn();
	    
	    return $valor;
	}
}
