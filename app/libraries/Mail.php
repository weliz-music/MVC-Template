<?php
  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\Exception;
  require APP_ROOT.'/libraries/MailSrc/Exception.php';
  require APP_ROOT.'/libraries/MailSrc/PHPMailer.php';
  require APP_ROOT.'/libraries/MailSrc/SMTP.php';
  class Mail{
    // declare variables
    private $sendName = APP_NAME;
    private $sendAddress = EMAIL_ADDR;
    private $recipientName;
    private $recipientMail;
    private $subject;
    private $body;
    private $altBody;

    /*
     * __construct()
     *
     * This function creates the new mail and sets all variables.
     *
     * Usage (In the Controller/Model):
     *    $mail = new Mail($sendName, $sendAddress, $recipientName, $recipientMail, $subject, $body, $altBody);
     */
    public function __construct($recipientName, $recipientMail, $subject, $body, $altBody){
      $this->recipientName = $recipientName;
      $this->recipientMail = $recipientMail;
      $this->subject = $subject;
      $this->body = $body;
      $this->altBody = $altBody;
    }

    /*
     * send()
     *
     * This function sends the mail.
     *
     * Usage (In the Controller/Model):
     *    $mail->send();
     */
    public function send(){
      $mail = new PHPMailer(true);

      try {
        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->Host = EMAIL_HOST;
        $mail->SMTPAuth = true;
        $mail->Username = EMAIL_ADDR;
        $mail->Password = EMAIL_PASS;
        $mail->SMTPSecure = 'tls';
        $mail->Port = EMAIL_PORT;

        //Recipients
        $mail->setFrom($this->sendAddress, $this->sendName);
        $mail->addAddress($this->recipientMail, $this->recipientName);
        $mail->addReplyTo($this->sendAddress, $this->sendName);


        //Content
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = $this->subject;
        $mail->Body    = $this->body;
        $mail->AltBody = $this->altBody;

        $mail->send();
        return TRUE;
      } catch (Exception $e) {
        return FALSE;
      }
    }

    /*
     * activationMail()
     *
     * This function sets the body of the mail to the one of an activation mail.
     *
     * Usage (In the Controller/Model):
     *    $body = Mail::activationMail('John Doe', EMAIL_ADDR);
     */
    public static function activationMail($userName){
      return '
<html>
  <head>
    <style>
      body{
        margin:0px;
        padding:0px;
      }
      .red{
        color: red;
      }
    </style>
  </head>
  <body>
    <div>
      <h3>Activate your account on '.APP_NAME.'.</h3>
      <p>Hi, '.$userName.'. Click the link below to activate your account directly.</p>
      <p>You will need your password to activate your account.</p>
      <a href="'.URL_ROOT.'/users/activate?name='.$userName.'">Activate your account.</a>
      <p>If the link did not work, please paste the following in your webbrowser:</p>
      <p class="red">'.URL_ROOT.'/users/activate?name='.$userName.'</p>
      <br>
      <p>If you are having issues, please contact us as <a href="mailto:'.EMAIL_ADDR.'?subject=Activation%20or%20other%20issues.">'.EMAIL_ADDR.'</a></p>
      <p>Kind regards,</p>
      <p>~ LiquitoX</p>
    </div>
  </body>
</html>';
    }

    /*
     * tokenMail()
     *
     * This function sets the body of the mail to the one of an token mail.
     *
     * Usage (In the Controller/Model):
     *    $body = Mail::tokenMail('John Doe', $token, EMAIL_ADDR);
     */
    public static function tokenMail($userName, $token){
      return '
<html>
  <head>
    <style>
      body{
        margin:0px;
        padding:0px;
      }
      .red{
        color: red;
      }
    </style>
  </head>
  <body>
    <div>
      <h3>Your requested password reset token.</h3>
      <p>Hi, '.$userName.'. Click the link below to reset your password directly.</p>
      <p>You will need your username and your email to reset your password.</p>
      <a href="'.URL_ROOT.'/users/resetPassword?token='.$token.'">Reset your password</a>.
      <p>If the link did not work, please paste the following in your webbrowser:</p>
      <p class="red">'.URL_ROOT.'/users/resetPassword?token='.$token.'</p>
      <br>
      <p>If you are having issues, please contact us at <a href="mailto:'.EMAIL_ADDR.'?subject=Password%20reset%20or%20other%20issues.">'.EMAIL_ADDR.'</a>.</p>
      <p>Kind regards,</p>
      <p>~ LiquitoX</p>
    </div>
  </body>
</html>';
    }

    /*
     * resetFailed()
     *
     * This function sets the body of the mail to the one of an reset failed mail.
     *
     * Usage (In the Controller/Model):
     *    $body = Mail::resetFailed('John Doe', EMAIL_ADDR);
     */
    public static function resetFailed($userName){
      return '
<html>
  <head>
    <style>
      body{
        margin:0px;
        padding:0px;
      }
      .red{
        color: red;
      }
    </style>
  </head>
  <body>
    <div>
      <h3>Password reset failed!</h3>
      <p>
        Hi, '.$userName.'. We believe that someone unauthorised tried to reset your password on our site. <br>
        We have taken the following steps to ensure that your data is safe:
        <ul>
          <li>Removed the reset token from your account.</li>
          <li>Sent you this email.</li>
          <li>Deactivated the reset token.</li>
        </ul>
      </p>
      <p>You can still reset your password <a href="'.URL_ROOT.'/users/resetPassword">here</a> if you made a mistake.</p>
       <p>If the link did not work, please paste the following in your webbrowser:</p>
      <p class="red">'.URL_ROOT.'/users/resetPassword</p>
      <p>If you believe this was a mistake, please email us at <a href="mailto:'.EMAIL_ADDR.'?subject=Password%20reset%20failed.">'.EMAIL_ADDR.'</a>.</p>
      <p>Kind regards,</p>
      <p>~ LiquitoX</p>
      </div>
  </body>
</html>';
    }

    /*
     * passwordReset()
     *
     * This function sets the body of the mail to the one of an password reset mail.
     *
     * Usage (In the Controller/Model):
     *    $body = Mail::passwordReset('John Doe', EMAIL_ADDR);
     */
    public static function passwordReset($userName){
      return '
<html>
  <head>
    <style>
      body{
        margin:0px;
        padding:0px;
      }
      .red{
        color: red;
      }
    </style>
  </head>
  <body>
    <div>
      <h3>Password has been reset.</h3>
      <p>
        Hi, '.$userName.'. Your password has just been reset with your reset token and your details. If you did not 
        request a password reset, please email us immediately with the email below.
      <p>If you believe this was a mistake, please email us at <a href="mailto:'.EMAIL_ADDR.'?subject=Password%20reset%20.">'.EMAIL_ADDR.'</a>.</p>
      <p>Kind regards,</p>
      <p>~ LiquitoX</p>
      </div>
  </body>
</html>';
    }
    /*
 * passwordReset()
 *
 * This function sets the body of the mail to the one of an password reset mail.
 *
 * Usage (In the Controller/Model):
 *    $body = Mail::passwordReset('John Doe', EMAIL_ADDR);
 */
    public static function passwordChange($userName){
      return '
<html>
  <head>
    <style>
      body{
        margin:0px;
        padding:0px;
      }
      .red{
        color: red;
      }
    </style>
  </head>
  <body>
    <div>
      <h3>Password has been changed.</h3>
      <p>
        Hi, '.$userName.'. Your password has just been changed with your account details. If you did not 
        request a password change, please email us immediately with the email below.
      <p>If you believe this was a mistake, please email us at <a href="mailto:'.EMAIL_ADDR.'?subject=Password%20reset%20.">'.EMAIL_ADDR.'</a>.</p>
      <p>Kind regards,</p>
      <p>~ LiquitoX</p>
      </div>
  </body>
</html>';
    }
  }


