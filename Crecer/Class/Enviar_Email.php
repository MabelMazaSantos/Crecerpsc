<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP; 
use PHPMailer\PHPMailer\Exception;

class Mailer {
    function Enviar_Email($email, $asunto, $cuerpo) {
        require_once 'Config/Configuracion.php';
        require 'PhpMailer/src/PHPMailer.php';
        require 'PhpMailer/src/SMTP.php';
        require 'PhpMailer/src/Exception.php';

        // Create an instance; passing `true` enables exceptions
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->SMTPDebug = SMTP::DEBUG_OFF; // Cambiar a SMTP::DEBUG_SERVER  cuando no se necesite depuración
            $mail->isSMTP();                                            
            $mail->Host       = MAIL_HOST;                     
            $mail->SMTPAuth   = true;                                   
            $mail->Username   = MAIL_USER;                     
            $mail->Password   = MAIL_PASS;                               
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // O usar PHPMailer::ENCRYPTION_SMTPS para el puerto 587          
            $mail->Port       = MAIL_PORT; // Cambia a 587 si usas STARTTLS

            // Recipients
            $mail->setFrom(MAIL_USER, 'Crecer');
            $mail->addAddress($email);

            // Content
            $mail->isHTML(true); // Set email format to HTML
            $mail->Subject = $asunto;
            $mail->Body    = mb_convert_encoding($cuerpo, 'ISO-8859-1','UTF-8');
            $mail->setLanguage('es', 'PhpMailer/language/phpmailer.lang-es.php');

            if ($mail->send()) {
                return true;
            } else {
                return false;
            }
            
        } catch (Exception $e) {
            echo "Error al momento de enviar el mensaje: {$mail->ErrorInfo}";
            return false;
        }
    }
}
?>