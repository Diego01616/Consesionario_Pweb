<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


//Load Composer's autoloader (created by composer, not included with PHPMailer)
require '../vendor/autoload.php';
//$rest = "";
//$rest = Envio_correo::enviar_correo();

class Envio_correo

{
    public static function enviar_correo()
    {
        $rcorreo =($_POST['correo']);
        //Create an instance; passing `true` enables exceptions
        $codigoAleatorio = rand(100000, 999999);
        $_SESSION['codigo_rec']=$codigoAleatorio;
        //para eliminar la variable de sesion se puede hacer unset($_SESSION['codigo_rec']);
        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = 'usuariodiegobautista@gmail.com';       //SMTP username
            $mail->Password   = 'drzqespwabjfdftd';                     //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
            $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            $mail->CharSet = 'UTF-8';   //códificación para que reconozca las tildes
            $mail->Encoding = 'base64';
            //Recipients
            $mail->setFrom('usuariodiegobautista@gmail.com', 'Consesionario web');
            $mail->addAddress($rcorreo, 'Diego');     //Add a recipient
        

            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = 'Código de confirmación';
            $mail->Body = 'Tu código de verificación para el superconcesionario es: <b>' . $codigoAleatorio . '</b>';
            $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            $mail->send();
            echo 'envio exitoso';
            
        } catch (Exception $e) {
            echo "No se pudo enviar {$mail->ErrorInfo}";
        }
    }
}
