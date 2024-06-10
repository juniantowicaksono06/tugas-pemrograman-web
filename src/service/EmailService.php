<?php 
use PHPMailer\PHPMailer\PHPMailer;  
use PHPMailer\PHPMailer\SMTP;  
use PHPMailer\PHPMailer\Exception;  
class EmailService {
    private $mail;
    public function __construct() {
        // var_dump($_ENV['ENV']);exit;
        $this->mail = new PHPMailer(false);
        $this->mail->SMTPDebug = 0;
        $this->mail->IsSMTP();
        $this->mail->Host = "smtp.gmail.com";
        $this->mail->SMTPAuth = true;
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $this->mail->Username = $_ENV['ENV']['GMAIL_USER_MAIL'];
        $this->mail->Password = $_ENV['ENV']['GMAIL_APP_KEY'];
        $this->mail->setFrom($_ENV['ENV']['GMAIL_USER_MAIL'], $_ENV['ENV']['GMAIL_USERNAME']);
        $this->mail->isHTML(true);
        $this->mail->Port = $_ENV['ENV']['GMAIL_PORT'];
        $this->mail->Timeout = 10;
    }

    public function setAddress($address, $name = "") {
        if($name === null) {
            $name = "";
        }
        $this->mail->addAddress($address, $name);
        return $this;
    }
    public function setContent($content) {
        $this->mail->Body = $content;
        return $this;
    }
    public function setSubject($subject) {
        $this->mail->Subject = $subject;
        return $this;
    }

    public function send() {
        $sendMail = $this->mail->send();
        if($sendMail) {
            return true;
        }
        return false;
    }

    public function close() {
        $this->mail->smtpClose();
    }
}