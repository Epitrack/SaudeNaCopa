<?php 
@session_start();
include("../bibliotecas/php/class.MySQL.php");
$db = new MySQL();

if(isset($_GET["assistencia"]) == 1){

	$db->ExecuteSQL("SELECT * FROM sub_unidades where ativo=1");
	$subUnidade = $db->ArrayResults();
	
	$listagem = array();
	foreach($subUnidade as $listaSub){
		
		 $subUnidade = "SELECT sub_unidades.latitude AS latitude, sub_unidades.longitude AS longitude
						FROM assistencia, sub_unidades
						WHERE assistencia.local_id = sub_unidades.id
						AND assistencia.local_id = " . $listaSub["id"] 
						. " AND assistencia.data_hora BETWEEN '" . $_SESSION["data_inicio"] . " 00:00:00' AND '" . $_SESSION["data_final"] . " 23:59:59'";
						
		$db->ExecuteSQL($subUnidade);
		$assistencias = $db->ArrayResults();	
		
		$listagem[$listaSub["id"]]["nome"] = $listaSub["nome"];
		$listagem[$listaSub["id"]]["locais"] = $assistencias;
		
	}
	
}

if(isset($_GET["vigilancia"]) == 1){
	
	$sql = "SELECT * FROM vigilancia_sanitaria where ativo=1 and data_hora BETWEEN '" . $_SESSION["data_inicio"] . " 00:00:00' AND '" . $_SESSION["data_final"] . " 23:59:59'";
	$db->ExecuteSQL($sql);
	$vigilancia_sanitaria = $db->ArrayResults();
	
	$listagem = array();
	foreach ($vigilancia_sanitaria as $lista){

		$locais = array("","Vigilancia Sanitaria","Servico de Alimentacao","Posto de Saude","Transporte");
		
		$listagem[$lista["id"]]["nome"] = $locais[$lista["locais_id"]];
		$listagem[$lista["id"]]["locais"] = $vigilancia_sanitaria;
		
	}
	
}	
	
?>
<!DOCTYPE html>
  <head>
    <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
    <script type="text/javascript" src="http://google-maps-utility-library-v3.googlecode.com/svn/tags/markermanager/1.0/src/markermanager.js"></script>
    <script type="text/javascript" src="http://google-maps-utility-library-v3.googlecode.com/svn/trunk/markerclusterer/src/markerclusterer_compiled.js"></script>
    <script type="text/javascript">
        
        var markers = {
            'countries': [
              {
                'name': 'Brasil',
                'location': [-8.05719, -34.882388]
              }
            ],
            'places': [
              {
                'name': 'Brasil',
                'location': [-8.05719, -34.882387]
              }
            ],
            'locations': [

               <?php
               		$resultado=""; 
               		$contadorListagem=0;
               		foreach($listagem as $lista){
               
               			foreach($lista["locais"] as $locais){
               					$resultado .= "{
					                		'name': '" . $lista["nome"] . "',
					                		'location': [" . $locais["latitude"] . "," . $locais["longitude"] . "]
					              		   },";
               			$contadorListagem++;	
               			}
               		$contadorListagem=0;		
               		}
               		echo $resultado;	
               	?>           
            ]
          };
    </script>
    <script type="text/javascript" src="functions.js"></script>
    <style type="text/css">
    #map {
      width: 820px;
      height: 470px;
    }
    #controls {
      margin: 0;
      list-style: none;
    }
    #controls li {
      display: inline;
      margin-left: 42px;
      font-family: Sans-Serif;
      font-size: 10pt;
    }
    #fusion-hm-li {
      visibility: hidden;
      margin-left: 8px;
    }
    </style>
  </head>
  <body>
    <div id="map"></div>
    <ul id="controls">
      <li>
        <label for="mgr-cb">Mostrar Marcadores</label>
        <input type="checkbox" id="mgr-cb" name="mgr-cb" />
      </li>
      <li>
        <label for="mc-cb">Mostrar Grupos</label>
        <input type="checkbox" id="mc-cb" name="mc-cb" />
      </li>
      <!-- 
	      <li>
	        <label for="fusion-cb">Fusion Table Layer</label>
	        <input type="checkbox" id="fusion-cb" name="fusion-cb" />
	      </li>
	      <li id="fusion-hm-li">
	        <label for="fusion-hm-cb">with heatmap</label>
	        <input type="checkbox" id="fusion-hm-cb" name="fusion-hm-cb" />
	      </li>
	      <li>
	        <label for="kml-cb">KML Layer</label>
	        <input type="checkbox" id="kml-cb" name="kml-cb" />
	      </li>
       -->
    </ul>
  </body>
</html>