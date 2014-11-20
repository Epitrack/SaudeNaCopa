<?php
/**
 * Amber
 * @package principal
 * @author César Martins
 * @version 1.0
 */
@session_start();
include_once("class.MySQL.php");
//header("Content-type: text/plain; charset=utf8");


class Ocorrencias {
	public $nomeDaTabela;
	public $horario;
	public $nome;
	public $total;
	protected $db;

	public function __construct($id="") {
		$this->db = new MySQL();
		$this->db->ExecuteSQL("SET NAMES 'utf8'");
		$this->nome = $this->getNome();
		$this->total = $this->total();
		$this->totalVigilancia = $this->total($id);
		$this->horario = $this->horario($id);
	}

	public function horario($id="") {
		$query = "SELECT 
		CONCAT(DATE_FORMAT(data_hora, '%d/%m/%Y %H'), 'h - ',DATE_FORMAT(data_hora, '%H'),':59h') as nome,
		count(q.id) as qtd, 
		ROUND(((count(q.id)*100)/t.contagem),2) as percent 
		FROM ($this->nomeDaTabela q, 
			(SELECT count(a.id) as contagem FROM $this->nomeDaTabela a 
			WHERE a.data_hora BETWEEN '" . $_SESSION["data_inicio"] . " 00:00:00' AND '" . $_SESSION["data_final"] . " 23:59:59'
			AND a.ativo = 1";
		$query .= ($id != "")? " and locais_id = $id ) t)" : ") t) ";
		$query .= ($id != "")? " where locais_id = $id" : " where 0=0 ";
		$query .= " AND q.data_hora BETWEEN '" . $_SESSION["data_inicio"] . " 00:00:00' AND '" . $_SESSION["data_final"] . " 23:59:59'
		AND q.ativo = 1
		GROUP BY CONCAT(DATE_FORMAT(data_hora, '%Y-%m-%d %H'), ':00:00.000')
		ORDER BY data_hora DESC";
		$this->db->ExecuteSQL($query);
		return $this->db->ArrayResults($query);
	}

	public function contagemChaves($coluna) {
		$campoTabela = $this->relacaoColunaXTabela($coluna);
		$campo = $campoTabela['campo'];
		$tabela = $campoTabela['tabela'];


		$queryTabelaEstrangeira = "SELECT id as rel, nome, '0' as qtd FROM $tabela";
		$this->db->ExecuteSQL($queryTabelaEstrangeira);
		$linhasEstrangeiras = $this->db->ArrayResultsWithKey("rel");

		$queryGeral = "SELECT * FROM $this->nomeDaTabela";
		$this->db->ExecuteSQL($queryGeral);
		$linhasGerais = $this->db->ArrayResults();

		$total = 0;
		foreach($linhasGerais as $linha) {
			$referencias = array_filter(explode("#",$linha[$coluna]));
			foreach($referencias as $referencia) {
				if(isset($linhasEstrangeiras[$referencia])) {
					$linhasEstrangeiras[$referencia]['qtd'] += 1;
					$total += 1;
				}
			}
		}

		for($i=0; $i< count($linhasEstrangeiras); $i++) {
			if(isset($linhasEstrangeiras[$i]['qtd']) && $linhasEstrangeiras[$i]['qtd'] > 0) {
			$linhasEstrangeiras[$i]['percent'] = round(($linhasEstrangeiras[$i]['qtd']*100)/$total,2);
			} else {
				$linhasEstrangeiras[$i] = "";
			}
		}

		$linhasEstrangeiras['extra'] = $total;

		//print_r(array_filter($linhasEstrangeiras));

		return array_filter($linhasEstrangeiras);
	}

	public function contagemProdutosVigilancia($id){
		
		$query = "SELECT e.nome as nome,
					 q.vigilancia_sanitaria as rel, 
					count(q.vigilancia_sanitaria) as qtd, 
					ROUND(((count(q.vigilancia_sanitaria) * 100)/t.contagem),2) as percent 
					FROM (vigilancia_sanitaria q, 
							(SELECT count(q.id) as contagem 
							FROM vigilancia_sanitaria q 
							WHERE locais_id = $id
							AND data_hora BETWEEN '" . $_SESSION["data_inicio"] . " 00:00:00' AND '" . $_SESSION["data_final"] . " 23:59:59'
				  			AND ativo = 1
							) t) 

						LEFT JOIN produtos_vigilancia_sanitaria e ON e.id = q.vigilancia_sanitaria
					WHERE q.locais_id = $id		 
					AND q.data_hora BETWEEN '" . $_SESSION["data_inicio"] . " 00:00:00' AND '" . $_SESSION["data_final"] . " 23:59:59'
				  	AND q.ativo = 1
					GROUP BY q.vigilancia_sanitaria";
		$this->db->ExecuteSQL($query);
		return $this->db->ArrayResults();	
		
	}
	
	public function contagemInfracoes($id){
		
		$query = "select ivs.nome as nome, count(vs.id) as qtd, 
					ROUND(((count(vs.id) * 100)/t.contagem),2) as percent
					from vigilancia_sanitaria vs, vigilancia_sanitaria_infracao vsi, infracao_vigilancia_sanitaria ivs,
						(select count(vigilancia_sanitaria_id) as contagem 
						from vigilancia_sanitaria_infracao, vigilancia_sanitaria
						where vigilancia_sanitaria.id = vigilancia_sanitaria_infracao.vigilancia_sanitaria_id
						AND vigilancia_sanitaria.data_hora BETWEEN '" . $_SESSION["data_inicio"] . " 00:00:00' AND '" . $_SESSION["data_final"] . " 23:59:59'
				  		AND vigilancia_sanitaria.ativo = 1
						) t 
					where vs.locais_id = $id
					and vs.id = vsi.vigilancia_sanitaria_id
					and ivs.id = vsi.infracao_id
					AND vs.data_hora BETWEEN '" . $_SESSION["data_inicio"] . " 00:00:00' AND '" . $_SESSION["data_final"] . " 23:59:59'
				  	AND vs.ativo = 1
					GROUP by ivs.nome ";
		$this->db->ExecuteSQL($query);
		return $this->db->ArrayResults();
		
	}
	
	public function contagemMedidaAplicadas($id){
		
		$query = "select ivs.nome as nome, count(vs.id) as qtd, 
					ROUND(((count(vs.id) * 100)/t.contagem),2) as percent
					from vigilancia_sanitaria vs, vigilancia_sanitaria_medida_aplicadas vsi, medidas_aplicadas ivs,
						(select count(medida_aplicadas_id) as contagem 
						from vigilancia_sanitaria_medida_aplicadas, vigilancia_sanitaria 
						where vigilancia_sanitaria.id = vigilancia_sanitaria_medida_aplicadas.vigilancia_sanitaria_id
						AND vigilancia_sanitaria.data_hora BETWEEN '" . $_SESSION["data_inicio"] . " 00:00:00' AND '" . $_SESSION["data_final"] . " 23:59:59'
				  		AND vigilancia_sanitaria.ativo = 1 
						 ) t 
					where vs.locais_id = $id
					and vs.id = vsi.vigilancia_sanitaria_id
					and ivs.id = vsi.medida_aplicadas_id
					AND vs.data_hora BETWEEN '" . $_SESSION["data_inicio"] . " 00:00:00' AND '" . $_SESSION["data_final"] . " 23:59:59'
				  	AND vs.ativo = 1
					GROUP by ivs.nome ";
		$this->db->ExecuteSQL($query);
		return $this->db->ArrayResults();
		
	}
	
	public function getNome() {
		$query = "SELECT *  FROM tabelas_questionarios WHERE tabela = '".$this->nomeDaTabela."'";
		$this->db->ExecuteSQL($query);
		$resultado = $this->db->ArrayResult();
		return $resultado['nome'];
	}

	public function total($vigilancia = false) {
		$query = "SELECT count(id) as total FROM $this->nomeDaTabela q
				  WHERE q.data_hora BETWEEN '" . $_SESSION["data_inicio"] . " 00:00:00' AND '" . $_SESSION["data_final"] . " 23:59:59'
				  AND q.ativo = 1";
		$query .= ($vigilancia)? " and locais_id = " . $vigilancia : "";
		$this->db->ExecuteSQL($query);
		$res = $this->db->ArrayResult();
		return $res['total'];
	}

	function relacaoColunaXTabela($coluna) {
		$campo = 'nome';

		switch ($coluna) {
			case 'medidas_aplicadas':
				$tabela = 'medidas_aplicadas';
			break;

			case 'produtos_id':
				$tabela = 'produtos_vigilancia_sanitaria';
			break;

			case 'produtos_tipo':
				$tabela = 'produtos_servicos_alimentacao';
			break;
			
			case 'infracao_id':
				$tabela = 'infracao_vigilancia_sanitaria';
			break;
			
			case 'local_ocorencia':
				$tabela = 'local_procedencia';
				$campo = "descricao";
			break;

			case 'num_pessoas_envolvidas':
				$tabela = 'pessoas_envolvidas';
			break;
			
			case 'instalacoes_fisicas':
				$tabela = 'instalacoes_fisicas';
			break;

			case 'equipamento':
				$tabela = 'equipamentos_materiais';

			case 'veiculos':
				$tabela = 'veiculo';
			break;
			
			case 'recurso_humanos':
				$tabela = 'recursos_humanos';
			break;
			
			default:
				# code...
				break;
		}

		return array('campo'=>$campo, 'tabela'=>$tabela);
	}

	public function contagemSurto(){
		
		$sql = "SELECT COUNT( id ) as qtd, observacao as nome, CONCAT('ID ' s.id) as percent FROM  surto s WHERE 1";
		$this->db->ExecuteSQL($sql);
		return $this->db->ArrayResults();	
		
	}
	
	public function contagemProvavelLocal(){
		
		$sql = "select count(sle.local_exposicao_id) as qtd,
				le.local_exposicao_desc as nome,
				ROUND(((count(sle.local_exposicao_id) * 100)/t.contagem),2) as percent
				from surto s, 
					surto_local_exposicao sle, 
					local_exposicao le, 
					(select count(surto_local_exposicao.surto_id) as contagem from surto_local_exposicao, local_exposicao 
						where surto_local_exposicao.local_exposicao_id = local_exposicao.local_exposicao_id ) t
				where sle.local_exposicao_id = le.local_exposicao_id
				AND s.id = sle.surto_id
				AND s.data_hora BETWEEN '" . $_SESSION["data_inicio"] . " 00:00:00' AND '" . $_SESSION["data_final"] . " 23:59:59'
				AND s.ativo = 1
				GROUP BY local_exposicao_desc";
		$this->db->ExecuteSQL($sql);
		return $this->db->ArrayResults();
		
	}
	
	public function contagemProvavelContaminacao(){
		
		$sql = "select count(sfc.fonte_contaminacao_id) as qtd,
				fc.fonte_contaminacao_desc as nome,
				ROUND(((count(sfc.fonte_contaminacao_id) * 100)/t.contagem),2) as percent
				from surto s, 
					surto_fonte_contaminacao sfc, 
					fonte_contaminacao fc, 
					(select count(surto_fonte_contaminacao.surto_id) as contagem from surto_fonte_contaminacao, fonte_contaminacao 
						where surto_fonte_contaminacao.fonte_contaminacao_id = fonte_contaminacao.fonte_contaminacao_id ) t
				where sfc.fonte_contaminacao_id = fc.fonte_contaminacao_id
				AND s.id = sfc.surto_id
				AND s.data_hora BETWEEN '" . $_SESSION["data_inicio"] . " 00:00:00' AND '" . $_SESSION["data_final"] . " 23:59:59'
				AND s.ativo = 1
				GROUP BY fc.fonte_contaminacao_desc";
		$this->db->ExecuteSQL($sql);
		return $this->db->ArrayResults();
		
	}
	
	public function contagemEnvolvidas(){
		
		$sql = "SELECT q.envolvidos as nome, 
				count(q.envolvidos) as qtd, 
				ROUND(((count(q.envolvidos) * 100)/t.contagem),2) as percent 
				FROM (surto q, 
					(SELECT count(q.id) as contagem FROM surto q
					 WHERE q.data_hora BETWEEN '" . $_SESSION["data_inicio"] . " 00:00:00' AND '" . $_SESSION["data_final"] . " 23:59:59'
					 AND q.ativo = 1
					) t) 
				LEFT JOIN surto e ON e.id = q.envolvidos
				AND q.data_hora BETWEEN '" . $_SESSION["data_inicio"] . " 00:00:00' AND '" . $_SESSION["data_final"] . " 23:59:59'
				AND q.ativo = 1 
				GROUP BY q.envolvidos";
		$this->db->ExecuteSQL($sql);
		return $this->db->ArrayResults();
		
	}
	
	public function pegaNomeLocal($id){
		
		$sql = "SELECT tq.nome, count(vs.id) as total 
				FROM tabelas_questionarios tq, vigilancia_sanitaria vs
				where tq.id = $id and tq.id = vs.locais_id"; 
		$this->db->ExecuteSQL($sql);
		return $this->db->ArrayResult();
		
	}
	
	public function contagemAssociativa($coluna) {
		$campoTabela = $this->relacaoColunaXTabela($coluna);
		$campo = $campoTabela['campo'];
		$tabela = $campoTabela['tabela'];


		echo $query = "SELECT 
			IFNULL(e.$campo, CONCAT('ID ', q.$coluna)) as nome,
			q.$coluna as rel, 
			count(q.$coluna) as qtd, 
			ROUND(((count(q.$coluna) * 100)/t.contagem),2) as percent 
		FROM 
			($this->nomeDaTabela q,

			(SELECT 
				count(q.id) as contagem
			FROM 
				$this->nomeDaTabela q) t)

		LEFT JOIN $tabela e 
			ON e.id = q.$coluna

		GROUP BY 
			q.$coluna";

		$this->db->ExecuteSQL($query);
		return $this->db->ArrayResults();
	}

	public function html() {

		$plural = $this->total == 1 ? '' : 's'; 
		$html = "<h1>".$this->nome." <br><small>$this->total ocorrência$plural</small></h1><br>";
		return $html;

	}
	
	public function htmlVigilancia() {

		$plural = $this->totalVigilancia == 1 ? '' : 's'; 
		$html = "<h1>".$this->nome." <br><small>$this->totalVigilancia ocorrência$plural</small></h1><br>";
		return $html;

	}


	public function geraTabelaQP($titulo, $dados, $extra="") {
		if(isset($dados['extra'])) {
			$extra = $dados['extra'];
		}
		$cod = !$extra ? "<h2>$titulo</h2>" : "<h2>$titulo <small>$extra</small></h2>";
		if(isset($dados['extra'])) {
			unset($dados['extra']);
		}
		$cod .= '<table class="table table-striped"><tbody>';
			$cod .= "<thead><tr><th>Referência</th><th>Quantidade</th><th>Porcentagem</th></tr></thead>";

			foreach($dados as $dado) {
				$cod .= '<tr>';
					$cod .= "<th>".$dado['nome']."</th><td>".$dado['qtd']."</td><td>".$dado['percent']."% </td>";
				$cod .= '</tr>';
			}

		$cod .= '</tbody><table><br>';

		return $cod;
	}

}



class OcorrenciasVigilanciaSanitaria extends Ocorrencias {
	public $nomeDaTabela = "vigilancia_sanitaria";
	public $produtos;
	public $infracoes;
	public $medidasAplicadas;

	public function __construct($localId) {
		parent::__construct($localId);
		
		$pegaNomeLocal 			= $this->pegaNomeLocal($localId);
		$this->nome				= $pegaNomeLocal["nome"];
		$this->total			= $pegaNomeLocal["total"];	 	
		
		
		//$this->produtos 		= $this->contagemAssociativa("produtos_id");
		$this->produtos 		= $this->contagemProdutosVigilancia($localId);
		//$this->infracoes 		= $this->contagemChaves("infracao_id");
		$this->infracoes 		= $this->contagemInfracoes($localId);
		$this->medidasAplicadas = $this->contagemMedidaAplicadas($localId);
	}

	public function html() {
		$html = parent::htmlVigilancia();
		$html .= $this->geraTabelaQP("Produtos", $this->produtos);
		$html .= $this->geraTabelaQP("Infrações", $this->infracoes);
		$html .= $this->geraTabelaQP("Medidas Aplicadas", $this->medidasAplicadas);
		$html .= $this->geraTabelaQP("Horário", $this->horario);
		return $html;
	}
}

class OcorrenciasServicoDeAlimentacao extends Ocorrencias {
	public $nomeDaTabela = "questionario_servicos_alimentacao";
	public $produtos;
	public $infracoes;
	public $medidasAplicadas;

	public function __construct() {
		parent::__construct();

		$this->produtos 		= $this->contagemAssociativa("produtos_tipo");
		$this->infracoes 		= $this->contagemChaves("infracao_id");
		$this->medidasAplicadas = $this->contagemChaves("medidas_aplicadas");
	}

	public function html() {
		$html = parent::html();
		$html .= $this->geraTabelaQP("Produtos", $this->produtos);
		$html .= $this->geraTabelaQP("Infrações", $this->infracoes);
		$html .= $this->geraTabelaQP("Medidas Aplicadas", $this->medidasAplicadas);
		$html .= $this->geraTabelaQP("Horário", $this->horario);
		return $html;
	}
}


class OcorrenciasSurto extends Ocorrencias {
	public $nomeDaTabela = "surto";
	public $localOcorrencia;
	public $pessoasEnvolvidas;
	public $provavelLocal;
	public $provavelContaminacao;
	
	public function __construct() {
		parent::__construct();
		$this->provavelLocal = $this->contagemProvavelLocal();
		$this->provavelContaminacao = $this->contagemProvavelContaminacao();
		$this->pessoasEnvolvidas = $this->contagemEnvolvidas();
	}

	public function html() {
		$html = parent::html();
		$html .= $this->geraTabelaQP("Provável local da exposição", $this->provavelLocal);
		$html .= $this->geraTabelaQP("Provável fonte da contaminação", $this->provavelContaminacao);
		$html .= $this->geraTabelaQP("Número de pessoas envolvidas", $this->pessoasEnvolvidas);
		return $html;
	}
}

class OcorrenciasPostoDeSaude extends Ocorrencias {
	public $nomeDaTabela = "questionario_posto_saude";
	public $instalacoesFisicas;
	public $equipamentosMateriais;
	public $medidasAplicadas;

	public function __construct() {
		parent::__construct();
		$this->instalacoesFisicas 		= $this->contagemChaves("instalacoes_fisicas");
		$this->equipamentosMateriais 	= $this->contagemChaves("equipamento");
		$this->medidasAplicadas 		= $this->contagemChaves("medidas_aplicadas");
	}

	public function html() {
		$html = parent::html();
		$html .= $this->geraTabelaQP("Instalações Físicas", $this->instalacoesFisicas);
		$html .= $this->geraTabelaQP("Equipamentos e Materiais", $this->equipamentosMateriais);
		$html .= $this->geraTabelaQP("Medidas Aplicadas", $this->medidasAplicadas);
		$html .= $this->geraTabelaQP("Horário", $this->horario);
		return $html;
	}
}

class OcorrenciasTransporte extends Ocorrencias {
	public $nomeDaTabela = "questionario_transporte";
	public $veiculos;
	public $recursosHumanos;
	public $medidasAplicadas;

	public function __construct() {
		parent::__construct();

		$this->contagemChaves("veiculos");
		$this->veiculos 		= $this->contagemChaves("veiculos");
		$this->recursosHumanos 	= $this->contagemChaves("recurso_humanos");
		$this->medidasAplicadas = $this->contagemChaves("medidas_aplicadas");
	}

	public function html() {
		$html = parent::html();
		$html .= $this->geraTabelaQP("Veículos", $this->veiculos);
		$html .= $this->geraTabelaQP("Recursos Humanos", $this->recursosHumanos);
		$html .= $this->geraTabelaQP("Medidas Aplicadas", $this->medidasAplicadas);
		$html .= $this->geraTabelaQP("Horário", $this->horario);
		return $html;
	}
}


//$oc = new OcorrenciasTransporte();
//print_r($oc);

//ASSISTENCIA

class Entidade {
	public $nome;
	public $id;
	public $idUnidade;
	protected $db;
	protected $nomeTabela;
	protected $campo = "nome";

	public function __construct($id) {
		//Conecta ao banco de dados
		$this->db = new MySQL();
		$this->db->ExecuteSQL("SET NAMES 'utf8'");

		//Associa o id
		$this->id = $id;

		//Pega o nome da subunidade
		$this->getNome($this->id);
	}

	public function getNome($id) {
		$query = "SELECT  
					$this->campo 
				FROM 
					$this->nomeTabela 
				WHERE
					ativo = 1 and 
					id = '".$id."'";
		$this->db->ExecuteSQL($query);
		$resultado = $this->db->ArrayResult();
		$this->idUnidade = utf8_encode($resultado['idUnidade']);
		$this->nome = utf8_encode($resultado[$this->campo]);
	}
}
class SubUnidade extends Entidade  {
	public $locaisDeProcedencia;
	public $necessidadesAtendimento;
	public $sexos;
	public $idadesMedia;
	public $idades;
	public $causasExternas;
	public $causasClinicas;
	public $doencaDNCI;
	public $foliaoTrabalhador;
	public $horario;
	public $total;
	public $hipoteseDiagnostica;
	public $sinaisSintomas;
	public $encaminhamentos;
	public $cidades;

	protected $nomeTabela = "sentimentos";

	public function getNome($id) {
		$query = "SELECT 
					$this->campo 
				FROM 
					$this->nomeTabela 
				WHERE 
					ativo = 1 and
					id = '".$id."'";
		$this->db->ExecuteSQL($query);
		$resultado = $this->db->ArrayResult();
		$this->nome = utf8_encode($resultado[$this->campo]);
	}

	public function total() {
		//$query = "SELECT count(a.sub_unidade_id) as total FROM questionarios_assistencia a WHERE a.sub_unidade_id = $this->id";
		$query = "select count(us.sentimento_id) as total
				  from usuario_sentimento us where us.sentimento_id = " . $this->id;
		$this->db->ExecuteSQL($query);
		$res = $this->db->ArrayResult();
		return $res['total'];
	}

	public function __construct($id) {
		parent::__construct($id);

		//Contagem associativa
		//$this->locaisDeProcedencia		= $this->contagemAssociativa("publico_id");
		//$this->necessidadesAtendimento 	= $this->contagemAssociativa("necessidade_atendimento");
		//$this->hipoteseDiagnostica = $this->contagemHipoteseDiagnostica();
		//$this->sinaisSintomas = $this->contagemSinaisSintomas();
		
		//$this->encaminhamentos = $this->contagemEncaminhamentos();
		
		//$this->causasExternas			= $this->contagemAssociativa("causas_externas");
		//$this->causasClinicas			= $this->contagemAssociativa("causas_clinicas");
		//$this->doenca_dnci 				= $this->contagemDNCI();//$this->contagemAssociativa("doenca_dnci");

		//Contagem com rótulos para valores predefinidos
		//$this->sexos = $this->contagemRotulo("sexo", array('valor'=>'1', 'true'=>'Homem', 'false'=>'Mulher'));
		//$this->sexos = $this->retornaValorSexo();  
		
		//$this->foliaoTrabalhador = $this->contagemRotulo("publico_id", array('valor'=>'1', 'true'=>'Expectador', 'false'=>'Trabalhador'));
		$this->cidades = $this->retornaCidade($id);
		
		//Contagem padrão
		//$this->idades = $this->contagem("idade");  

		//Média de valor
		//$this->idadesMedia = $this->contagemMedia("idade");  
		
		//Horário
		//$this->horario = $this->horario(); 
		
		//Total
		$this->total = $this->total();  
	}

	public function html() {

		$plural = $this->total == 1 ? '' : 's'; 
		$html = "<h1>".utf8_decode($this->nome)." <br><small>$this->total ocorrência$plural</small></h1><br>";

		$html .= $this->geraTabelaQP("Cidades", $this->cidades);
		//$html .= $this->geraTabelaQP("Hipótese Diagnóstica", $this->hipoteseDiagnostica);
		//$html .= $this->geraTabelaQP("Sinais e Sintomas", $this->sinaisSintomas);
		//if(count($this->doenca_dnci) > 0){
		//	$html .= $this->geraTabelaQP("Suspeita de DNCI", $this->doenca_dnci);
		//}
		//$html .= $this->geraTabelaQP("Encaminhamentos", $this->encaminhamentos);
		//$html .= $this->geraTabelaQP("Sexo", $this->sexos);
		//$html .= $this->geraTabelaQP("Idades", $this->idades, "Média ".$this->idadesMedia." anos");
		//$html .= $this->geraTabelaQP("Horário", $this->horario);
		
		return $html;

	}

	public function geraTabelaQP($titulo, $dados, $extra="") {
		$cod = !$extra ? "<h2>$titulo</h2>" : "<h2>$titulo <small>$extra</small></h2>";

		$cod .= '<table class="table table-striped"><tbody>';
			$cod .= "<thead><tr><th>Nome</th><th>Quantidade</th><th>Porcentagem</th></tr></thead>";

			foreach($dados as $dado) {
				
				$cod .= '<tr>';
					$cod .= "<th>".$dado['nome']."</th><td>".$dado['qtd']."</td><td>".$dado['percent']."% </td>";
				$cod .= '</tr>';
			}

		$cod .= '</tbody><table><br>';

		return $cod;
	}

	public function retornaCidade($id){
		
		$query = "SELECT c.nome as nome, count(us.sentimento_id) as qtd, ROUND(((count(us.sentimento_id) * 100)/t.contagem),2) as percent 
						FROM usuario_sentimento us, (select count(u.sentimento_id) as contagem from usuario_sentimento u where u.sentimento_id = $id) t, cidade c 
						where us.sentimento_id = $id and c.id = us.cidade_id
						group by us.cidade_id order by percent desc";
		$this->db->ExecuteSQL($query);
		return $this->db->ArrayResults();
		
	}
	
	
	public function contagemRotulo($coluna,$rotulo) {
		/*
		$query = "SELECT IF($coluna = '".$rotulo['valor']."', '".$rotulo['true']."', '".$rotulo['false']."') as nome, $coluna as rel, count($coluna) as qtd, ROUND(((count($coluna)*100)/t.contagem),2) as percent
					FROM questionarios_assistencia q,
					(SELECT count(sub_unidade_id) contagem from questionarios_assistencia where sub_unidade_id = $this->id) t
					
					WHERE q.sub_unidade_id = $this->id
					GROUP BY q.$coluna";
		*/
		//$query = "SELECT IF($coluna = '".$rotulo['valor']."', '".$rotulo['true']."', '".$rotulo['false']."') as nome,
		$query = "SELECT IF($coluna = '1', 'Expectador', IF($coluna = '2','Trabalhador','Outros')) as nome, 
					$coluna as rel, count($coluna) as qtd, ROUND(((count($coluna)*100)/t.contagem),2) as percent 
			FROM assistencia q,
				(SELECT count(local_id) contagem from assistencia 
				where local_id = $this->id
				and data_hora BETWEEN '" . $_SESSION["data_inicio"] . " 00:00:00' AND '" . $_SESSION["data_final"] . " 23:59:59'
				and ativo = 1
				) t 
			 
			WHERE q.local_id = $this->id
			and q.data_hora BETWEEN '" . $_SESSION["data_inicio"] . " 00:00:00' AND '" . $_SESSION["data_final"] . " 23:59:59'
			and q.ativo = 1
			GROUP BY q.$coluna";			
		//echo $query;
		$this->db->ExecuteSQL($query);

		return $this->db->ArrayResults();
	}
	
	
	public function retornaValorSexo(){
		
		$sql = "select sexo from assistencia a 
				where a.local_id = $this->id
				and a.data_hora BETWEEN '" . $_SESSION["data_inicio"] . " 00:00:00' AND '" . $_SESSION["data_final"] . " 23:59:59'
				and a.ativo = 1";
		$this->db->ExecuteSQL($sql);
		$listagem = $this->db->ArrayResults();
		
		$array = array();
		$contadorHomem=0;
		$contadorMulher=0;
		$contadorIgnorado=0;
		$total = count($listagem);
		
		foreach ($listagem as $lista){
			if($lista["sexo"] == 1){
				$array[0]["nome"] = "Homem";	
				$array[0]["qtd"] = ++$contadorHomem;
				$array[0]["percent"] = number_format( ($contadorHomem / $total)*100,2);
				
			}elseif($lista["sexo"] == 2){
				$array[1]["nome"] = "Mulher";
				$array[1]["qtd"] = ++$contadorMulher;	
				$array[1]["percent"] = number_format( ($contadorMulher / $total)*100,2);
				
			}else{
				$array[2]["nome"] = "Ignorado";
				$array[2]["qtd"] = ++$contadorIgnorado;
				$array[2]["percent"] = number_format( ($contadorIgnorado / $total)*100,2);
			}
		}
		return $array;   
		
	}

	public function contagemHipoteseDiagnostica(){
		
			$query = "select hd.hip_dia_desc as nome,
					count(ahd.assistencia_id) as qtd, 
					ROUND(((count(ahd.assistencia_id) * 100)/t.contagem),2) as percent 
					from hipotese_diagnostica hd, 
					assistencia_hipotese_diagnostica ahd,
					assistencia a,
						(SELECT count(q.assistencia_id) as contagem FROM assistencia_hipotese_diagnostica q , assistencia a
						WHERE a.local_id = " . $this->id . " and a.assistencia_id = q.assistencia_id
						and a.data_hora BETWEEN '" . $_SESSION["data_inicio"] . " 00:00:00' AND '" . $_SESSION["data_final"] . " 23:59:59'
				  		and a.ativo = 1
						) t
					where hd.hip_dia_id = ahd.hip_dia_id
					and a.assistencia_id = ahd.assistencia_id
					and local_id = " . $this->id . "
					and a.data_hora BETWEEN '" . $_SESSION["data_inicio"] . " 00:00:00' AND '" . $_SESSION["data_final"] . " 23:59:59'
				  	and a.ativo = 1
					GROUP by hd.hip_dia_desc
					ORDER by hd.hip_dia_desc";			
		//echo $query;
		$this->db->ExecuteSQL($query);
		return $this->db->ArrayResults();
	}
	
	public function contagemSinaisSintomas(){
		
		$query = "select ss.sinais_desc as nome,
					count(ass.assistencia_id) as qtd, 
					ROUND(((count(ass.assistencia_id) * 100)/t.contagem),2) as percent 
					from sinais_sintomas ss, 
					assistencia_sinais_saude ass,
					assistencia a,
						(SELECT count(q.assistencia_id) as contagem FROM assistencia_sinais_saude q , assistencia a
						WHERE a.local_id = " . $this->id . " and a.assistencia_id = q.assistencia_id
						and a.data_hora BETWEEN '" . $_SESSION["data_inicio"] . " 00:00:00' AND '" . $_SESSION["data_final"] . " 23:59:59'
				  		and a.ativo = 1) t
					where ss.sinais_id = ass.sinais_id
					and a.assistencia_id = ass.assistencia_id
					and a.local_id = " . $this->id . "
					and a.data_hora BETWEEN '" . $_SESSION["data_inicio"] . " 00:00:00' AND '" . $_SESSION["data_final"] . " 23:59:59'
				  	and a.ativo = 1
					GROUP by ss.sinais_desc
					ORDER by ss.sinais_desc";			
		//echo $query;
		$this->db->ExecuteSQL($query);
		return $this->db->ArrayResults();
		
	}
	
	public function contagemEncaminhamentos(){

		$query = "SELECT IF(encaminhamentos = '1', 'Alta', IF(encaminhamentos = '2','Óbito','Transferência')) as nome, 
			count(encaminhamentos) as qtd, 
			ROUND(((count(a.assistencia_id) * 100)/t.contagem),2) as percent 
			from assistencia a, 
				(SELECT count(a.assistencia_id) as contagem FROM assistencia a 
				WHERE a.local_id = " . $this->id . "
				AND a.data_hora BETWEEN '" . $_SESSION["data_inicio"] . " 00:00:00' AND '" . $_SESSION["data_final"] . " 23:59:59'
				AND a.ativo = 1
				) t
			where a.local_id = " . $this->id . "
			and a.data_hora BETWEEN '" . $_SESSION["data_inicio"] . " 00:00:00' AND '" . $_SESSION["data_final"] . " 23:59:59'
			and a.ativo = 1
			GROUP by encaminhamentos
			ORDER by encaminhamentos";
		$this->db->ExecuteSQL($query);
		return $this->db->ArrayResults();
		
	}
	
	public function contagemDNCI(){
		
		$query = "select ss.descricao as nome,
					count(ass.assistencia_id) as qtd, 
					ROUND(((count(ass.assistencia_id) * 100)/t.contagem),2) as percent 
					from suspeita_dnci ss, 
					assistencia_dnci ass,
					assistencia a,
					(SELECT count(q.assistencia_id) as contagem FROM assistencia_dnci q , assistencia a
								WHERE a.local_id = " . $this->id . " and a.assistencia_id = q.assistencia_id) t
					where ss.id = ass.dnci_id
					and a.assistencia_id = ass.assistencia_id
					and a.local_id = " . $this->id . "
					and a.data_hora BETWEEN '" . $_SESSION["data_inicio"] . " 00:00:00' AND '" . $_SESSION["data_final"] . " 23:59:59'
				  	and a.ativo = 1
					GROUP by ss.descricao
					ORDER by ss.descricao";			
		//echo $query;
		$this->db->ExecuteSQL($query);
		return $this->db->ArrayResults();
		
	}
	
	public function contagem($coluna) {
		/*
		$query = "SELECT $coluna as nome, count($coluna) as qtd, ROUND(((count($coluna)*100)/t.contagem),2) as percent
					FROM questionarios_assistencia q,
					(SELECT count(sub_unidade_id) contagem from questionarios_assistencia where sub_unidade_id = $this->id) t
					
					WHERE q.sub_unidade_id = $this->id
					GROUP BY q.$coluna";
		*/
		$query = "SELECT $coluna as nome, count($coluna) as qtd, ROUND(((count($coluna)*100)/t.contagem),2) as percent
					FROM assistencia q,
						(SELECT count(local_id) contagem from assistencia 
						where local_id = $this->id
						and data_hora BETWEEN '" . $_SESSION["data_inicio"] . " 00:00:00' AND '" . $_SESSION["data_final"] . " 23:59:59'
				  		and ativo = 1
						) t
					WHERE q.local_id = $this->id
					and q.data_hora BETWEEN '" . $_SESSION["data_inicio"] . " 00:00:00' AND '" . $_SESSION["data_final"] . " 23:59:59'
				  	and q.ativo = 1
					GROUP BY q.$coluna";			
		//echo $query;
		$this->db->ExecuteSQL($query);

		return $this->db->ArrayResults();
	}

	public function horario() {
		/*
		$query = "SELECT 
					CONCAT(DATE_FORMAT(data_hora, '%d/%m/%Y %H'), 'h - ',DATE_FORMAT(data_hora, '%H'),':59h') as nome,
					count(q.sub_unidade_id) as qtd, 
					ROUND(((count(q.sub_unidade_id)*100)/t.contagem),2) as percent 
					
					FROM (questionarios_assistencia q, (SELECT count(a.sub_unidade_id) as contagem FROM questionarios_assistencia a WHERE a.sub_unidade_id = $this->id) t)
					
					WHERE q.sub_unidade_id = $this->id
					
					GROUP BY CONCAT(DATE_FORMAT(data_hora, '%Y-%m-%d %H'), ':00:00.000')
					ORDER BY data_hora DESC";
		*/
		$query = "SELECT 
					CONCAT(DATE_FORMAT(data_hora, '%d/%m/%Y %H'), 'h - ',DATE_FORMAT(data_hora, '%H'),':59h') as nome,
					count(q.local_id) as qtd, 
					ROUND(((count(q.local_id)*100)/t.contagem),2) as percent 
					
					
					FROM (assistencia q, 
						 (SELECT count(a.local_id) as contagem 
						 	FROM assistencia a 
						 	WHERE a.local_id = $this->id
						 	and a.data_hora BETWEEN '" . $_SESSION["data_inicio"] . " 00:00:00' AND '" . $_SESSION["data_final"] . " 23:59:59'
				  			and a.ativo = 1
						 	) t)
					
					WHERE q.local_id = $this->id
					and q.data_hora BETWEEN '" . $_SESSION["data_inicio"] . " 00:00:00' AND '" . $_SESSION["data_final"] . " 23:59:59'
				  	and q.ativo = 1
					GROUP BY CONCAT(DATE_FORMAT(data_hora, '%Y-%m-%d %H'), ':00:00.000')
					ORDER BY data_hora DESC";

		$this->db->ExecuteSQL($query);
		return $this->db->ArrayResults();
	}



	public function contagemMedia($coluna) {
		//$query = "SELECT ROUND(AVG($coluna),2) as media FROM questionarios_assistencia WHERE sub_unidade_id = ".$this->id;
		$query = "SELECT ROUND(AVG($coluna),2) as media 
				  FROM assistencia a 
				  WHERE a.data_hora BETWEEN '" . $_SESSION["data_inicio"] . " 00:00:00' AND '" . $_SESSION["data_final"] . " 23:59:59'
				  	and a.ativo = 1 
				  and a.local_id = ".$this->id;
		$this->db->ExecuteSQL($query);

		$res = $this->db->ArrayResult();
		return $res['media'];
	}

	public function contagemAssociativa($coluna) {
		$id = $this->id;
		$campo = 'nome';

		switch ($coluna) {
			case 'local_procedencia':
				$tabela = 'local_procedencia';
				$campo = "descricao";
			break;

			case 'causas_externas':
				$tabela = 'causa_externas';
			break;

			case 'causas_clinicas':
				$tabela = 'causa_clinicas';
			break;

			case 'necessidade_atendimento':
				$tabela = 'necessidade_atendimento';
			break;

			case 'doenca_dnci':
				$tabela = 'suspeita_dnci';
				$campo = 'descricao';
			break;
			
			default:
				# code...
				break;
		}
	$query = "SELECT 
			IFNULL(e.$campo, CONCAT('ID ', q.$coluna)) as nome,
			q.$coluna as rel, 
			count(q.$coluna) as qtd, 
			ROUND(((count(q.$coluna) * 100)/t.contagem),2) as percent 
		FROM 
			(assistencia q,

			(SELECT 
				count(q.local_id) as contagem
			FROM 
				assistencia q 
			WHERE 
				q.local_id = $id) t)

		LEFT JOIN $tabela e 
			ON e.id = q.$coluna

		WHERE 
			q.local_id = $id 
		GROUP BY 
			q.$coluna";

			//echo "\n\n\n".$query."\n\n\n";

		$this->db->ExecuteSQL($query);
		return $this->db->ArrayResults();
	}
}


//LocalDeProcedencia
class LocalDeProcedencia extends Entidade {
	protected $nomeTabela = "local_procedencia";
	protected $campo = "descricao";
}

//Causas Externas
class CausaExterna extends Entidade {
	protected $nomeTabela = "causa_externas";
}

//Causas Clínicas
class CausaClinica extends Entidade {
	protected $nomeTabela = "causa_clinicas";
}

//Infracao Vigilancia Sanitaria
class InfracaoVigilanciaSanitaria extends Entidade {
	protected $nomeTabela = "infracao_vigilancia_sanitaria";
}

//Equipamentos e Materiais
class EquipamentoEMaterial extends Entidade {
	protected $nomeTabela = "equipamentos_materiais";
}

//MedidaAplicaca
class MedidaAplicada extends Entidade {
	protected $nomeTabela = "medidas_aplicadas";
}

//Recurso Humano
class RecursoHumano extends Entidade {
	protected $nomeTabela = "recursos_humanos";
}

//Suspeitas DNCI
class SuspeitaDNCI extends Entidade {
	protected $nomeTabela = "suspeita_dnci";
	protected $campo = "descricao";
}

//Veículo
class Veiculo extends Entidade {
	protected $nomeTabela = "veiculo";
}
?>