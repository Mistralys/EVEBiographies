<?php

namespace EVEBiographies;

use PHPMailer\PHPMailer\PHPMailer;

require_once 'Mailer/Recipient.php';

class Mailer
{
    const ERROR_EMPTY_MAIL_BODY = 37001;

    const ERROR_SMTP_SEND_FAILED = 37002;

   /**
    * @var Mailer_Recipient[]
    */
    protected $recipients = array();

    protected $subject;

   /**
    * The HTML body of the mail
    * @var string
    */
    protected $body;

    public function __construct(Mailer_Recipient $recipient, $subject)
    {
        $this->subject = $subject;

        $this->addRecipient($recipient);
    }

    public function addRecipient(Mailer_Recipient $recipient)
    {
        $this->recipients[] = $recipient;
        return $this;
    }

    public function setSubject($subject)
    {
        $this->subject = $subject;
        return $this;
    }

    public function setHTMLBody($body)
    {
        $this->body = $body;
        return $this;
    }

    static public function createRecipient($email, string $name='')
    {
        return new Mailer_Recipient($email, $name);
    }

    public function send()
    {
        if(empty($this->body))
        {
            throw new Website_Exception(
                'Empty mail body',
                self::ERROR_EMPTY_MAIL_BODY
            );
        }

        $mail = new PHPMailer(true);

        try
        {
            //$mail->SMTPDebug = 2;
            $mail->isSMTP();
            $mail->Host = APP_SMTP_HOST;
            $mail->SMTPAuth = true;
            $mail->Username = APP_SMTP_USERNAME;
            $mail->Password = APP_SMTP_PASSWORD;
            $mail->SMTPSecure = APP_SMTP_CRYPT;
            $mail->Port = APP_SMTP_PORT;

            $mail->setFrom(APP_SMTP_FROM_EMAIL, APP_SMTP_FROM_NAME);

            foreach($this->recipients as $recipient)
            {
                $mail->addAddress($recipient->getEmail(), $recipient->getName());
            }

            $mail->isHTML(true);
            $mail->Subject = $this->subject;
            $mail->Body = $this->body;

            $mail->send();
        }
        catch (\Exception $e)
        {
             throw new Website_Exception(
                 'Sending email via SMTP failed',
                 self::ERROR_SMTP_SEND_FAILED,
                 'Mailer error message:'.$mail->ErrorInfo,
                 $e
             );
        }
    }
}
