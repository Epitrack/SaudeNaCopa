<?php 
include("../php/class.MySQL.php");
$db = new MySQL();

    $contador=0;
    $listagem = array();


        $dataIni = $_GET['data1'];
        $dataFin = $_GET['data2'];

        if($_GET){
            $range = " data_cadastro BETWEEN '$dataIni 00:00:00' AND '$dataFin 23:59:00' ";
        } 
        
        $subUnidade = "SELECT distinct(usuario_id) as id FROM usuario_sentimento us 
                       where usuario_id <> \"\" and usuario_id <> 0";
       // $db->ExecuteSQL($subUnidade);
        //$usuario_sentimento = $db->ArrayResults();  
        
        //0 = muito mal
        //1 = mal
        //2 = quemmm
        //3 = bem
        //5 = muito bem
        $semimentos = array("muitobem", "bem", "normal", "mal", "muitomal");
        
        $assistencias = array();

      //  foreach ($usuario_sentimento as $lista_sentimento){
            


/*

SELECT 
    distinct(us.id) as Id, 
    us.latitude as Latitude, 
    us.longitude as Longitude,  
    us.sentimento as Icone, us.data_cadastro as data_cadastro,
    campo1, campo2, campo3, campo4, campo5, campo6, campo7, 
    campo8, campo9, campo10, cidade_regiao_metro as regiao_metro
FROM 
    usuario_sentimento us
where 
    data_cadastro BETWEEN '2014-06-12 00:00:00' AND '2014-06-14 23:59:00' 
group by us.id

*/



            /*
            $subUnidade = "SELECT us.id as Id, us.latitude as Latitude, us.longitude as Longitude,  us.sentimento as Icone,
                       campo1, campo2, campo3, campo4, campo5, campo6, campo7, campo8, campo9, campo10
                        FROM usuario_sentimento us";
            $db->ExecuteSQL($subUnidade);
            
            18/04/2014 - query
            $sub = "SELECT us.id as Id, us.latitude as Latitude, us.longitude as Longitude,  us.sentimento as Icone,
                           campo1, campo2, campo3, campo4, campo5, campo6, campo7, campo8, campo9, campo10, cidade_regiao_metro as regiao_metro
                           FROM usuario_sentimento us
                           where usuario_id = " . $lista_sentimento["id"] . " and cidade_regiao_metro <> ''
                           order by us.data_cadastro desc limit 0,1";
            
            */

             /*              
            $sub = "SELECT us.id as Id, us.latitude as Latitude, us.longitude as Longitude,  us.sentimento as Icone, us.data_cadastro as data_cadastro,
                           campo1, campo2, campo3, campo4, campo5, campo6, campo7, campo8, campo9, campo10, cidade_regiao_metro as regiao_metro
                           FROM usuario_sentimento us
                           where usuario_id = " . 
                           $lista_sentimento["id"] .
                            $range.
                           " order by us.data_cadastro desc limit 0,1";
*/


            $sub = "SELECT 
                        distinct(us.id) as Id, 
                        us.latitude as Latitude, 
                        us.longitude as Longitude,  
                        us.sentimento as Icone, us.data_cadastro as data_cadastro,
                        campo1, campo2, campo3, campo4, campo5, campo6, campo7, 
                        campo8, campo9, campo10, cidade_regiao_metro as regiao_metro
                    FROM 
                        usuario_sentimento us
                    where 
                        $range 
                    group by us.id
                    limit 0, 10000" ;              
 

            $db->ExecuteSQL($sub);

            $result = $db->ArrayResults();
            $assistencias = $result;
             
            
       // }

             
        
        $contador=0;
        $listagem = array();
        $listagem["locais"] = array();
        foreach ($assistencias as $lista){
            
            $listagem["locais"][$contador]["Id"] = $lista["Id"];
            $listagem["locais"][$contador]["Latitude"] = $lista["Latitude"];
            $listagem["locais"][$contador]["Longitude"] = $lista["Longitude"];
            $listagem["locais"][$contador]["regiao_metro"] = $lista["regiao_metro"];
            
            $data = new DateTime($lista["data_cadastro"]);
            $dataEncontrado = $data->format("d-m-Y");
            $horaEncontrado = $data->format("H:i:s");
            
            $listagem["locais"][$contador]["data"] = $dataEncontrado;
            $listagem["locais"][$contador]["hora"] = $horaEncontrado;
            
            if($lista["Icone"] != "2"){
                $listagem["locais"][$contador]["Icone"] = $semimentos[$lista["Icone"]];
            }
            
            if($lista["campo1"] == 1){
                $listagem["locais"][$contador]["Sintoma"][] = "febre";  
            }
            if($lista["campo2"] == 1){
                $listagem["locais"][$contador]["Sintoma"][] = "tosse";  
            }
            if($lista["campo3"] == 1){
                $listagem["locais"][$contador]["Sintoma"][] = "DordeGarganta";  
            }
            if($lista["campo4"] == 1){
                $listagem["locais"][$contador]["Sintoma"][] = "FaltadeAr";  
            }
            if($lista["campo5"] == 1){
                $listagem["locais"][$contador]["Sintoma"][] = "NauseaeVomitos"; 
            }
            if($lista["campo6"] == 1){
                $listagem["locais"][$contador]["Sintoma"][] = "Diarreia";   
            }
            if($lista["campo7"] == 1){
                $listagem["locais"][$contador]["Sintoma"][] = "DorNasArticulacoes"; 
            }
            if($lista["campo8"] == 1){
                $listagem["locais"][$contador]["Sintoma"][] = "DordeCabeca";    
            }
            if($lista["campo9"] == 1){
                $listagem["locais"][$contador]["Sintoma"][] = "Sangramento";    
            }
            if($lista["campo10"] == 1){
                $listagem["locais"][$contador]["Sintoma"][] = "ManchasVermelhasnoCorpo";    
            }
            $contador++;
        }
        echo json_encode($listagem);
?>