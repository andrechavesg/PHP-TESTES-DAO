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
        $nome = $leilao->getNome();
        $valorInicial = $leilao->getValorInicial();
        $donoId = $leilao->getDono()->getId();
        $dataAbertura = $leilao->getDataAbertura()->format("Y-m-d");
        $usado = $leilao->getUsado();
        $encerrado = $leilao->isEncerrado();
        
        $stmt = $this->con->prepare("insert into Leilao(nome,valorInicial,dono,dataAbertura,usado,encerrado) values(:nome,:valorInicial,:dono,:dataAbertura,:usado,:encerrado)");
        
        $stmt->bindParam("nome", $nome);
        $stmt->bindParam("valorInicial", $valorInicial);
        $stmt->bindParam("dono", $donoId);
        $stmt->bindParam("dataAbertura", $dataAbertura);
        $stmt->bindParam("usado",$usado,PDO::PARAM_BOOL);
        $stmt->bindParam("encerrado",$encerrado,PDO::PARAM_BOOL);
        
        $stmt->execute();
        
        $leilao->setId($this->con->lastInsertId());
        
        foreach ($leilao->getLances() as $lance){
            $this->salvarLance($lance);
        }
    }
	
	private function salvarLance(Lance $lance) {
	    $id = $lance->getUsuario()->getId();
	    $data = $lance->getData()->format("Y-m-d");
	    $valor = $lance->getValor();
	    $leilaoId = $lance->getLeilao()->getId();
	    
	    $stmt = $this->con->prepare("insert into Lance(usuario,data,valor,leilao) values(:usuario,:data,:valor,:leilao)");
	    
	    $stmt->bindParam("usuario",$id);
        $stmt->bindParam("data",$data);
        $stmt->bindParam("valor",$valor);
        $stmt->bindParam("leilao",$leilaoId);
	    
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
	    $usado = false;
	    
	    $stmt = $this->con->prepare("select * from Leilao where usado = :usado");
	    $stmt->bindParam("usado",$usado);
	    $stmt->execute();
	    
	    $novos = $stmt->fetchAll(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE,Leilao::class);
	    
		return $novos;
	}
	
	public function antigos() : array {
		$seteDiasAtras = new DateTime();
		$seteDiasAtras->sub(new DateInterval('P7D'));
		$seteDiasAtras = $seteDiasAtras->format("Y-m-d");
		
		$stmt = $this->con->prepare("select * from Leilao where dataAbertura <= :dataAbertura");
		$stmt->bindParam("dataAbertura",$seteDiasAtras);
		$stmt->execute();
		
		$antigos  = $stmt->fetchAll(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE,Leilao::class);
		
		return $antigos;
	}
	
	public function porPeriodo(DateTime $inicio, DateTime $fim) : array {
	    $inicio = $inicio->format("Y-m-d");
	    $fim = $fim->format("Y-m-d");
	    
	    $stmt = $this->con->prepare("select * from Leilao where dataAbertura < :fim and dataAbertura > :inicio");
	    $stmt->bindParam("inicio",$inicio);
	    $stmt->bindParam("fim",$fim);
	    $stmt->execute();
	    
	    $porPeriodo = $stmt->fetchAll(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE,Leilao::class);
	    
	    return $porPeriodo;
	}
	
	public function disputadosEntre(float $inicio, float $fim) : array {
	    $inicio = $inicio->format("Y-m-d");
	    $fim = $fim->format("Y-m-d");
	    
	    $stmt = $this->con->prepare("select le.*,COUNT(la.id) as lances from Leilao as le JOIN Lance as la ON le.id = la.leilao where le.dataAbertura < :fim and le.dataAbertura > :inicio GROUP BY la.id HAVING lances > 0");
	    $stmt->bindParam("inicio",$inicio);
	    $stmt->bindParam("fim",$fim);
	    $stmt->execute();	    
	    
	    $disputados = $stmt->fetchAll(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE,Leilao::class);
	    
	    return $disputados;
	}
	
	public function total() {
	    $encerrado = false;
	    
		$stmt = $this->con->prepare("SELECT COUNT(*) FROM Leilao WHERE encerrado = :encerrado");
		$stmt->bindParam("encerrado",$encerrado);
	    $stmt->execute();
	    
	    return $stmt->fetchColumn();
	}
	
	public function atualiza(Leilao $leilao) {
	    $nome = $leilao->getNome();
	    $valorInicial = $leilao->getValorInicial();
	    $donoId = $leilao->getDono()->getId();
	    $dataAbertura = $leilao->getDataAbertura()->format("Y-m-d");
	    $usado = $leilao->getUsado();
	    $encerrado = $leilao->isEncerrado();
	    $id = $leilao->getId();
	    
		$stmt = $this->con->prepare("update Leilao set nome = :nome,valorInicial = :valorInicial,dono = :dono,usado = :usado,encerrado = :encerrado where id = :id");
		$stmt->bindParam("nome", $nome);
		$stmt->bindParam("valorInicial", $valorInicial);
		$stmt->bindParam("dono", $donoId);
		$stmt->bindParam("dataAbertura", $dataAbertura);
		$stmt->bindParam("usado",$usado);
		$stmt->bindParam("encerrado",$encerrado);
		$stmt->bindParam("id", $id);
		
	    $stmt->execute();
	}
	
	public function deleta(Leilao $leilao) {
	    $id = $leilao->getId();
	    
	    $stmt = $this->con->prepare("delete from Leilao where id = :id");
		$stmt->bindParam("id",$id);
		
		$stmt->execute();
	}
	
	public function deletaEncerrados() {
	    $encerrado = $leilao->getEncerrado();
	    
	    $stmt = $this->con->prepare("delete from Leilao where encerrado = :encerrado");
	    $stmt->bindParam("encerrado",$encerrado);
	    
	    $stmt->execute();
	}
	
	public function listaLeiloesDoUsuario(Usuario $usuario) {
	    $usuarioId = $usuario->getId();
	    
	    $stmt = $this->con->prepare("select le.* from Leilao as le LEFT JOIN Lance la ON la.leilao = le.id Left Join Usuario u ON la.usuario = :usuario");
        $stmt->bindParam("usuario",$usuarioId);
        $stmt->execute();
        
        $doUsuario = $stmt->fetchAll(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE,Leilao::class);
        
        return $doUsuario;
	}
	
	public function getValorInicialMedioDoUsuario(Usuario $usuario) {
	    $usuarioId = $usuario->getId();
	    $stmt = $this->con->prepare("select avg(le.valorInicial) from Leilao as le LEFT JOIN Lance la ON la.leilao = le.id Left Join Usuario u ON la.usuario = :usuario");
	    $stmt->bindParam("usuario",$usuarioId);
	    $stmt->execute();
	    
	    $valor = $stmt->fetchColumn();
	    
	    return $valor;
	}
}