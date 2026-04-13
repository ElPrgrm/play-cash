<?php
function enviarCorreo($para, $asunto, $mensaje) {
    $para_alias = $para_email = $para;

    if (is_array($para)) {
        $para_alias = $para[0] ?? '';
        $para_email = $para[1] ?? '';
    }

    if (!filter_var($para_email, FILTER_VALIDATE_EMAIL)) {
        return false;
    }

    $de_alias = getenv('MAIL_FROM_NAME') ?: 'Play & Cash';
    $de_email = getenv('MAIL_FROM_EMAIL') ?: '';
    $smtp_user = getenv('MAIL_USERNAME') ?: $de_email;
    $smtp_password = getenv('MAIL_PASSWORD') ?: '';
    $smtp_host = getenv('MAIL_HOST') ?: 'smtp.gmail.com';
    $smtp_port = (int) (getenv('MAIL_PORT') ?: 465);

    if ($de_email === '' || $smtp_password === '') {
        return false;
    }

    $mensaje = '<!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>' . htmlspecialchars($asunto, ENT_QUOTES, 'UTF-8') . '</title>
    </head>
    <body>' . $mensaje . '</body>
    </html>';

    require_once __DIR__ . '/PHPMailer/src/Exception.php';
    require_once __DIR__ . '/PHPMailer/src/PHPMailer.php';
    require_once __DIR__ . '/PHPMailer/src/SMTP.php';

    $mail = new PHPMailer\PHPMailer\PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->SMTPDebug = 0;
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS;
        $mail->Host = $smtp_host;
        $mail->Port = $smtp_port;
        $mail->Username = $smtp_user;
        $mail->Password = $smtp_password;

        $mail->setFrom($de_email, $de_alias);
        $mail->addAddress($para_email, $para_alias ?: $para_email);
        $mail->isHTML(true);
        $mail->Subject = $asunto;
        $mail->Body = $mensaje;
        $mail->send();
        return true;
    } catch (Throwable $e) {
        return false;
    }
}
?>
