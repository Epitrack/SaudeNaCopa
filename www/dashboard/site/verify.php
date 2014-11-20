<?php
  require_once('recaptchalib.php');
  $privatekey = "6LejyfESAAAAAANfiZvqnvCgMyi6s2j5ECE-YPCa";
  $resp = recaptcha_check_answer ($privatekey,
                                $_SERVER["REMOTE_ADDR"],
                                $_POST["recaptcha_challenge_field"],
                                $_POST["recaptcha_response_field"]);

  if (!$resp->is_valid) {
    // What happens when the CAPTCHA was entered incorrectly
    die ("The reCAPTCHA wasn't entered correctly. Go back and try it again." .
         "(reCAPTCHA said: " . $resp->error . ")");
  } else {
    $emailsender = $email;
    // Your code here to handle a successful verification
  }


// extract($_POST);

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

                            <b>Nome:</b> ".$nome."<br /><br />
                            <b>Email:</b> ".$email."<br /><br />
                            <b>Telefone:</b> ".$telefone."<br /><br />
                            <b>Assunto:</b> ".$assunto."<br /><br />
                            <b>Mensagem:</b> ".$mensagem."<br /><br />
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



// if ($_SERVER[HTTP_HOST]) {
//         $emailsender= $email; // Substitua essa linha pelo seu e-mail@seudominio
// } else {
//         $emailsender = $email;

// }



if(PATH_SEPARATOR == ";") $quebra_linha = "\r\n"; //Se for Windows
else $quebra_linha = "\n"; //Se "não for Windows"

$cabecalho = "From: $nome <$emailsender> $quebra_linha";
$cabecalho .= "Reply-To: $nome <$email> $quebra_linha";
$cabecalho .= "MIME-Version: 1.0$quebra_linha";
$cabecalho .= "Content-type: text/html; charset=uft-8$quebra_linha";

$envia = mail("thulioph@gmail.com","Vigilância Participativa - CONTATO",utf8_decode($mens),$cabecalho,"-r".$emailsender);
// $envia .= mail("contato@saudenacopa.com","Vigilância Participativa - CONTATO",utf8_decode($mens),$cabecalho,"-r".$emailsender);


if($envia){
echo "Email enviado com sucesso!";
}else{
echo "Erro ao enviar email!";
}

  ?>