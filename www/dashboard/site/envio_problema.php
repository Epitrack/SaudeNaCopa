<?php
@session_start();

//echo "<pre>";
// var_dump($_SESSION);
// var_dump($_POST);

if( isset($_POST['mensagem'])) {
   if($_SESSION['security_code'] == $_POST['security_code'] && !empty($_SESSION['security_code']) ) {
        //se o capicha estiver funcionando vai enviar o email
        //so vai entrar aqui se a sessao foi igua ao que o ususario digitou e não vazia

    unset($_SESSION);
    extract($_POST);
    $mens = "
            <meta content=\"text/html; charset=utf-8\">
                        <table width='665' border='0' cellspacing='0' cellpadding='0' align='center'>
            <tr>
                <td bgcolor='#e4f1f9'>&nbsp;</td>
            </tr>
            <tr>
                <td bgcolor='#e4f1f9' align='center'>
                &nbsp;&nbsp;&nbsp;&nbsp;
                    <a href='http://saudenacopa.epitrack.com.br'>
                        <img border='0' alt='' width='270' height='212' src='http://saudenacopa.epitrack.com.br/imagens/marca.png'  />
                    </a>
                </td>
            </tr>
            <tr>
                <td bgcolor='#e4f1f9'>
                <div style='width:620px; margin:10px auto; text-align:justify; font-family:Arial, Helvetica, sans-serif; font-size:12px; line-height:18px'>
                <table width='620' border='0' cellspacing='3' cellpadding='10' align='center'>

                        <tr>
                            <td align='left' valign='top' style='border: solid 1px #ccc; background:#fff' bgcolor='#FFFFFF'>

                                <b>Problema:</b> ".$mensagem."<br /><br />
                            </td>
                        </tr>


                        </table>
                            <center>


                <br />

                </center></div>
                </td>
            </tr>
            <tr>
                <td bgcolor='#e4f1f9' width='665' height='85'>
                </td>
            </tr>

    </table>
    ";



    if ($_SERVER[HTTP_HOST]) {
        $emailsender= 'contato@saudenacopa.epitrack.com.br'; // Substitua essa linha pelo seu e-mail@seudominio
    } else {
        $emailsender= 'contato@saudenacopa.epitrack.com.br';
        // $emailsender = $email;

    }



    if(PATH_SEPARATOR == ";") $quebra_linha = "\r\n"; //Se for Windows
    else $quebra_linha = "\n"; //Se "não for Windows"

    $cabecalho = "From: $nome <$emailsender> $quebra_linha";
    $cabecalho .= "Reply-To: $nome <$email> $quebra_linha";
    $cabecalho .= "MIME-Version: 1.0$quebra_linha";
    $cabecalho .= "Content-type: text/html; charset=uft-8$quebra_linha";

    $envia = mail("contato@saudenacopa.epitrack.com.br","Vigilância Participativa - PROBLEMA",utf8_decode($mens),$cabecalho,"-r".$emailsender);
    // $envia .= mail("contato@saudenacopa.epitrack.com.br","Vigilância Participativa - PROBLEMA",utf8_decode($mens),$cabecalho,"-r".$emailsender);

    if($envia){
        echo "Problema enviado com sucesso!";
        die;
    }else{
        echo "Erro ao enviar o problema, tente novamente em alguns minutos!";
        die;
    }

  } else {
    echo "Para enviar o problema, é necessário digitar o captcha, tente novamente.";
    die;
   }
} else {
    echo "Informe corretamente o captcha.";
    die;
}


?>