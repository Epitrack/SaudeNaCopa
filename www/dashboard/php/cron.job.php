<?php
/**
 * Amber
 * @package principal
 * @author César Martins
 * @version 1.0
 */
include "class.MySQL.php";
$conn = new MySQL();
		
		$conn->ExecuteSQL("select * from usuario_sentimento where atualizado = 0 order by id asc ");
		$num_rows = $conn->ArrayResults();
		
		$total = count($num_rows);
		  echo "$total";

		if($total > 2) {
			 
			 //foreach($num_rows as $row){
//count($num_rows)
			 for ($i = 0; $i < $total; $i++) {	
			   $row = $num_rows[$i];

			 	$url = "http://maps.google.com/maps/api/geocode/json?latlng=$row[latitude],$row[longitude]&sensor=false";
			    $data = json_decode(file_get_contents($url));
  

			    for($j=0;$j<count($data->results[0]->address_components);$j++){
			    	if( $data->results[0]->address_components[$j]->types[0] == "administrative_area_level_2" ){
			    		$cidade = $data->results[0]->address_components[$j]->long_name;
			    		break;
			    	}
			    }

			    	

			    


			    echo $cidade;
			    echo "<br>";

			    if(!$cidade){
			    	echo "erro";
			    	break;
			    }
				 /*
			    $sql = "select * from cidades where nome like '$cidade'";
			    $conn->ExecuteSQL($sql);
			    var_dump($conn);

			    */
			    $listaCidade = $cidade;//$conn->ArrayResult();

			    $cidadeSede = getRegiaoMetropolitana($cidade);
			    

        
				$upSQL =  "update usuario_sentimento 	set atualizado = 1, cidade_id = '$listaCidade', cidade_regiao_metro = '$cidadeSede' 	where id = $row[id]";

//print_r($upSQL);
		
			    $sql = $conn->ExecuteSQL($upSQL);

			    
				
			 }

		}else 
			echo "Não ha dados a serem atualizados"; 
		


function getRegiaoMetropolitana($cidade){


$cidadesSede = array("Fernando de Noronha" => "Recife",
    "Araçoiaba" => "Recife", "Igarassu" => "Recife", "Itamaracá" => "Recife", "Itapissuma" => "Recife",
    "Abreu e Lima" => "Recife", "Camaragibe" => "Recife", "Jaboatão dos Guararapes" => "Recife", "Moreno" => "Recife",
    "Olinda" => "Recife", "Paulista" => "Recife", "Recife" => "Recife", "São Lourenço da Mata" => "Recife",
    "Cabo de Santo Agostinho" => "Recife", "Ipojuca" => "Recife", "Autazes" => "Manaus", "Careiro" => "Manaus",
    "Careiro da Várzea" => "Manaus", "Iranduba" => "Manaus", "Manacapuru" => "Manaus", "Manaquiri" => "Manaus",
    "Manaus" => "Manaus", "Aquiraz" => "Fortaleza", "Caucaia" => "Fortaleza", "Eusébio" => "Fortaleza",
    "Fortaleza" => "Fortaleza", " Guaiúba" => "Fortaleza", "Itaitinga" => "Fortaleza", "Maracanaú" => "Fortaleza",
    "Maranguape" => "Fortaleza", "Pacatuba" => "Fortaleza", "Horizonte" => "Fortaleza", "Pacajús" => "Fortaleza",
    "Natal" => "Natal", "Ceará-Mirim" => "Natal", "Extremoz" => "Natal", "Macaíba" => "Natal", "Monte Alegre" => "Natal",
    "Nísia Floresta" => "Natal", "Parnamirim" => "Natal", "São Gonçalo do Amarante" => "Natal",
    "São José de Mipibu" => "Natal", "Vera Cruz" => "Natal", "Vera Cruz" => "Salvador",
    "São Sebastião do Passé" => "Salvador", "São Francisco do Conde" => "Salvador", "Simões Filho" => "Salvador",
    "Salvador" => "Salvador", "Pojuca" => "Salvador", "Mata de São João" => "Salvador", "Madre de Deus" => "Salvador",
    "Lauro de Freitas" => "Salvador", "Itaparica" => "Salvador", "Dias d Ávila" => "Salvador", "Candeias" => "Salvador", 
    "Camaçari" => "Salvador", "Distrito Federal" => "Brasília","Brasilia" => "Brasília", "Abadiânia" => "Brasília", "Água Fria de Goiás" => "Brasília", 
    "Águas Lindas de Goiás" => "Brasília", "Alexânia" => "Brasília", "Cabeceiras" => "Brasília", "Cidade Ocidental" => "Brasília", "Cocalzinho de Goiás" => "Brasília", "Corumbá de Goiás" => "Brasília", "Cristalina" => "Brasília", "Formosa" => "Brasília", "Luziânia" => "Brasília", "Mimoso de Goiás" => "Brasília", "Novo Gama" => "Brasília", "Padre Bernardo" => "Brasília", "Pirenópolis" => "Brasília", "Planaltina" => "Brasília", "Santo Antônio do Descoberto" => "Brasília", "Valparaíso de Goiás" => "Brasília", "Vila Boa" => "Brasília", "Buritis" => "Brasília", "Cabeceira Grande" => "Brasília", "Unaí" => "Brasília", "Cuiabá" => "Cuiabá", "Várzea Grande" => "Cuiabá", "Santo Antônio do Leverger" => "Cuiabá", "Nossa Senhora do Livramento " => "Cuiabá", "Acorizal" => "Cuiabá", "Barão de Melgaço" => "Cuiabá", "Chapada dos Guimarães" => "Cuiabá", "Jangada" => "Cuiabá", "Nobres" => "Cuiabá", "Nova Brasilândia" => "Cuiabá", "Planalto da Serra" => "Cuiabá", "Poconé" => "Cuiabá", "Rosário Oeste" => "Cuiabá", "Baldim" => "Belo Horizonte", "Belo Horizonte" => "Belo Horizonte", "Betim" => "Belo Horizonte", "Brumadinho" => "Belo Horizonte", "Caeté" => "Belo Horizonte", "Capim Branco" => "Belo Horizonte", "Confins" => "Belo Horizonte", "Contagem" => "Belo Horizonte", "Esmeraldas" => "Belo Horizonte", "Florestal" => "Belo Horizonte", "Ibirité" => "Belo Horizonte", "Igarapé" => "Belo Horizonte", "Itaguara" => "Belo Horizonte", "Itatiaiuçu" => "Belo Horizonte", "Jaboticatubas" => "Belo Horizonte", "Nova União" => "Belo Horizonte", "Juatuba" => "Belo Horizonte", "Lagoa Santa" => "Belo Horizonte", "Mário Campos" => "Belo Horizonte", "Mateus Leme" => "Belo Horizonte", "Matozinhos" => "Belo Horizonte", "Nova Lima" => "Belo Horizonte", "Pedro Leopoldo" => "Belo Horizonte", "Raposos" => "Belo Horizonte", "Ribeirão das Neves" => "Belo Horizonte", "Rio Acima" => "Belo Horizonte", "Rio Manso" => "Belo Horizonte", "Sabará" => "Belo Horizonte", "Santa Luzia" => "Belo Horizonte", "São Joaquim de Bicas" => "Belo Horizonte", "São José da Lapa" => "Belo Horizonte", "Sarzedo" => "Belo Horizonte", "Taquaraçu de Minas" => "Belo Horizonte", "Vespasiano" => "Belo Horizonte", "Arujá" => "São Paulo", "Barueri" => "São Paulo", "Biritiba Mirim" => "São Paulo", "Caieiras" => "São Paulo", "Cajamar" => "São Paulo", "Carapicuíba" => "São Paulo", "Cotia" => "São Paulo", "Diadema" => "São Paulo", "Embu das Artes" => "São Paulo", "Embu-Guaçu" => "São Paulo", "Ferraz de Vasconcelos" => "São Paulo", "Francisco Morato" => "São Paulo", "Franco da Rocha" => "São Paulo", "Guararema" => "São Paulo", "Guarulhos" => "São Paulo", "Itapevi" => "São Paulo", "Itapecerica da Serra" => "São Paulo", "Itaquaquecetuba" => "São Paulo", "Jandira" => "São Paulo", "Juquitiba" => "São Paulo", "Mairiporã" => "São Paulo", "Mauá" => "São Paulo", "Mogi das Cruzes" => "São Paulo", "Osasco" => "São Paulo", "Pirapora do Bom Jesus" => "São Paulo", "Poá" => "São Paulo", "Ribeirão Pires" => "São Paulo", "Rio Grande da Serra" => "São Paulo", "Salesópolis" => "São Paulo", "Santa Isabel" => "São Paulo", "Santana de Parnaíba" => "São Paulo", "Santo André" => "São Paulo", "São Bernardo do Campo" => "São Paulo", "São Caetano do Sul" => "São Paulo", "São Lourenço da Serra" => "São Paulo", "São Paulo" => "São Paulo", "Suzano" => "São Paulo", "Taboão da Serra" => "São Paulo", "Vargem Grande Paulista" => "São Paulo", "Belford Roxo" => "Rio de Janeiro", "Cachoeiras de Macacu" => "Rio de Janeiro", "Duque de Caxias" => "Rio de Janeiro", "Guapimirim" => "Rio de Janeiro", "Itaboraí" => "Rio de Janeiro", "Itaguaí" => "Rio de Janeiro", "Japeri" => "Rio de Janeiro", "Magé" => "Rio de Janeiro", "Maricá" => "Rio de Janeiro", "Mesquita" => "Rio de Janeiro", "Nilópolis" => "Rio de Janeiro", "Niterói" => "Rio de Janeiro", "Nova Iguaçu" => "Rio de Janeiro", "Paracambi" => "Rio de Janeiro", "Queimados" => "Rio de Janeiro", "Rio Bonito" => "Rio de Janeiro", "Rio de Janeiro" => "Rio de Janeiro", "São Gonçalo" => "Rio de Janeiro", "São João de Meriti" => "Rio de Janeiro", "Seropédica" => "Rio de Janeiro", "Tanguá" => "Rio de Janeiro", "Adrianópolis" => "Curitiba", "Agudos do Sul" => "Curitiba", "Almirante Tamandaré" => "Curitiba", "Araucária" => "Curitiba", " Balsa Nova" => "Curitiba", "Bocaiúva do Sul" => "Curitiba", " Campina Grande do Sul" => "Curitiba", " Campo do Tenente" => "Curitiba", " Campo Largo" => "Curitiba", " Campo Magro" => "Curitiba", "Cerro Azul" => "Curitiba", "Colombo" => "Curitiba", "Contenda" => "Curitiba", "Curitiba" => "Curitiba", " Doutor Ulysses" => "Curitiba", "Fazenda Rio Grande" => "Curitiba", "Itaperuçu" => "Curitiba", "Lapa" => "Curitiba", "Mandirituba" => "Curitiba", " Piên" => "Curitiba", " Pinhais" => "Curitiba", "Piraquara" => "Curitiba", " Quatro Barras" => "Curitiba", " Quitandinha" => "Curitiba", "Rio Branco do Sul" => "Curitiba", "Rio Negro" => "Curitiba", "São José dos Pinhais" => "Curitiba", "Parana" =>"Curitiba",  "Tijucas do Sul" => "Curitiba", "Tunas do Paraná" => "Curitiba", " Alvorada" => "Porto Alegre", "Cachoeirinha" => "Porto Alegre", "Campo Bom" => "Porto Alegre", "Canoas" => "Porto Alegre", "Estância Velha" => "Porto Alegre", "Esteio" => "Porto Alegre", "Gravataí" => "Porto Alegre", "Guaíba" => "Porto Alegre", "Novo Hamburgo" => "Porto Alegre", "Porto Alegre" => "Porto Alegre", "São Leopoldo" => "Porto Alegre", "Sapiranga" => "Porto Alegre", "Sapucaia do Sul" => "Porto Alegre", "Viamão" => "Porto Alegre", "Dois Irmãos" => "Porto Alegre", "Eldorado do Sul" => "Porto Alegre", "Glorinha" => "Porto Alegre", "Ivoti" => "Porto Alegre", "Nova Hartz" => "Porto Alegre", "Parobé" => "Porto Alegre", "Portão" => "Porto Alegre", "Triunfo" => "Porto Alegre", "Charqueadas" => "Porto Alegre", "Araricá" => "Porto Alegre", " Nova Santa Rita" => "Porto Alegre", " Montenegro" => "Porto Alegre", "Taquara" => "Porto Alegre", "São Jerônimo" => "Porto Alegre", "Arroio dos Ratos" => "Porto Alegre", "Santo Antônio da Patrulha" => "Porto Alegre", "Capela de Santana" => "Porto Alegre", "Rolante" => "Porto Alegre", "Igrejinha" => "Porto Alegre");



/*
Ceara
	Edit	Delete	Auckland
	Edit	Delete	Parana
	Edit	Delete	Rio Grande do Norte
	Edit	Delete	Maryland
	Edit	Delete	Federal District
	Edit	Delete	Bahia
	Edit	Delete	Roraima
	Edit	Delete	Pernambuco
	Edit	Delete	Santa Catarina
	Edit	Delete	Rio de Janeiro
	Edit	Delete	California
	Edit	Delete	SÃ£o Paulo

*/

	$reg = ($cidadesSede[$cidade]) ? $cidadesSede[$cidade]: "";
	return $reg;
}



