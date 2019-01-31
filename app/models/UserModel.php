<?php
  /*
   * User Model.
   *
   * This model extends the Controller where needed with specific functions for that controller.
   */
  class User{
    private $db;
    public function __construct(){
      $this->db = new Database();
    }

    /*
     * register()
     *
     * This function registers an user and sends them an email.
     *
     * Usage (In the Controller):
     *    $this->model->register($data);
     */
    public function register($data){
      $this->db->query('INSERT INTO users (name, email, password, level) VALUES (:name, :email, :password, :level)');
      // Bind values
      $this->db->bind(':name', $data['userName']);
      $this->db->bind(':email', $data['userEmail']);
      $this->db->bind(':password', $data['userPass']);
      $this->db->bind(':level', 'user');

      // Execute the query
      if($this->db->execute()){
        // Send activation mail
        $sender = APP_NAME;
        $senderAddress = EMAIL_ADDR;
        $recipient = $data['userName'];
        $recipientMail = $data['userEmail'];
        $subject = 'Activate your account.';
        $body = Mail::activationMail($recipient, EMAIL_ADDR);
        $altbody = strip_tags($body);

        $mail = new Mail($sender, $senderAddress, $recipient, $recipientMail, $subject, $body, $altbody);
        $mail->send();
        flash('register_success', 'We have sent you an email with activation instructions!');
        return TRUE;
      } else {
        return FALSE;
      }
    }

    /*
     * login()
     *
     * This function logs the user in.
     *
     * Usage (In the Controller):
     *    $user = $this->model->login($userLogin, $userPassword);
     */
    public function login($login, $password){
      $this->db->query('SELECT * FROM users WHERE email = :login OR name = :login');
      $this->db->bind(':login', $login);

      $user = $this->db->fetchSingle();

      $hashedPassword = $user->password;
      if(password_verify($password, $hashedPassword)){
        return $user;
      } else {
        return FALSE;
      }
    }

    /*
     * findByEmail()
     *
     * This function searches for an user by their email.
     *
     * Usage (In the Controller):
     *    $this->model->findByEmail($userEmail);
     */
    public function findByEmail($email){
      $this->db->query('SELECT * FROM users WHERE email = :email');
      $this->db->bind(':email', $email);

      $user = $this->db->fetchSingle();

      // Check row
      if($this->db->rowCount() > 0){
        // Email is found.
        return $user;
      } else {
        // Email is not found.
        return FALSE;
      }
    }

    /*
     * findByUserName()
     *
     * This function searches for an user by their username.
     *
     * Usage (In the Controller):
     *    $user = $this->model->findByUserName($userName);
     */
    public function findByUserName($userName){
      $this->db->query('SELECT * FROM users WHERE name = :userName');
      $this->db->bind(':userName', $userName);

      $user = $this->db->fetchSingle();

      // Check row
      if($this->db->rowCount() > 0){
        // userName is found.
        return $user;
      } else {
        // userName is not found.
        return FALSE;
      }
    }

    /*
     * findByUsernameAndEmail()
     *
     * This function searches for an user where the username and userEmail should be correct. This function is only used
     * for sending the reset password token.
     *
     * Usage (In the Controller):
     *    $user = findByUsernameAndEmail($userName, $userEmail);
     */
    public function findByUsernameAndEmail($userName, $userEmail){
      $this->db->query('SELECT name, email FROM users WHERE name = :userName AND email = :userEmail');

      // Bind parameters.
      $this->db->bind(':userName', $userName);
      $this->db->bind(':userEmail', $userEmail);

      $user = $this->db->fetchSingle();

      // check if the row is correct.
      if($this->db->rowCount() > 0){
        // User found.
        return $user;
      } else {
        // User is not found.
        return FALSE;
      }
    }

    /*
     * validatePass()
     *
     * This function validates a password for security reasons. If the password does not meet these requirements, the
     * function will return a string with the needed requirements.
     *
     * Usage(In the Controller/Model):
     *    $data['userPassError'] = $this->model->validatePass($data['userPass']);
     */
    public function validatePass($password){
      $upperCase='/[A-Z]/';
      $lowerCase='/[a-z]/';
      $specialChar='/[!@#$%^&*]/';
      $number='/[0-9]/';
      $returnValue = '';
      if(strlen($password)<=10){
        $returnValue .= "Password must be at least 10 characters!<br>";
      }
      if(strlen($password)>=100){
        $returnValue .= "Password must be less than 100 characters!<br>";
      }
      if(preg_match_all($upperCase,$password, $o)<2){
        $returnValue .= 'Missing atleast 2 uppercase characters.<br>';
      }
      if(preg_match_all($lowerCase,$password, $o)<2){
        $returnValue .= 'Missing atleast 2 lowercase characters.<br>';
      }
      if(preg_match_all($specialChar,$password, $o)<1){
        $returnValue .= 'Missing atleast 1 special character. Usable characters: !@#$%^&*<br>';
      }
      if(preg_match_all($number,$password, $o)<2){
        $returnValue .= 'Missing atleast 2 numbers.<br>';
      }
      if(empty($returnValue)) {
        return NULL;
      } else {
        return $returnValue;
      }
    }

    /*
     * createSession()
     *
     * This function creates a session with the given user.
     *
     * Usage (In the Controller):
     *    $this->model->createSession($user);
     */
    public function createSession($user){
      $_SESSION['userId'] = $user->id;
      $_SESSION['userName'] = $user->name;
      $_SESSION['userEmail'] = $user->email;
      $_SESSION['userLevel'] = $user->level;
    }

    // Check if the password given is correct.
    public function checkPassword($pass){
      $this->db->query('SELECT password FROM users where email = :email');
      $this->db->bind(':email', $_SESSION['userEmail']);

      $user = $this->db->fetchSingle();

      $hashedPassword = $user->password;
      if(password_verify($pass, $hashedPassword)){
        return TRUE;
      } else {
        return FALSE;
      }
    }

    // Change the user's password
    public function changePassword($userId, $password){
      // Hash user Password
      $password = password_hash($password, PASSWORD_DEFAULT);
      $this->db->query('UPDATE users SET password = :password WHERE id = :id');

      // Bind parameters.
      $this->db->bind(':password', $password);
      $this->db->bind(':id', $userId);

      // Execute query.
      if($this->db->execute()){
        // Set mail variables
        $recipient = $_SESSION['userName'];
        $recipientMail = $_SESSION['userEmail'];
        $subject = 'Password has been changed.';
        $body = Mail::passwordChange($recipient);
        $altbody = strip_tags($body);

        $mail = new Mail($recipient, $recipientMail, $subject, $body, $altbody);
        $mail->send();

        flash('passwordChangeSuccess', 'Your password has been changed!');
        return TRUE;
      } else {
        return FALSE;
      }
    }

    // Change the user's email
    public function changeEmail($userId, $email){
      $this->db->query('UPDATE users SET email = :email WHERE id = :id');

      // Bind parameters.
      $this->db->bind(':email', $email);
      $this->db->bind(':id', $userId);

      // Execute query.
      if($this->db->execute()){
        flash('userEmailChangeSuccess', 'Your email has been changed!');
        return TRUE;
      } else {
        return FALSE;
      }
    }

    // Change the user's username
    public function changeUserName($userId, $userName){
      $this->db->query('UPDATE users SET name = :userName WHERE id = :id');

      // Bind parameters.
      $this->db->bind(':userName', $userName);
      $this->db->bind(':id', $userId);

      // Execute query
      if($this->db->execute()){
        flash('userNameChangeSuccess', 'Your username has been changed!');
        return TRUE;
      } else {
        return FALSE;
      }
    }

    // Delete the current user. Please note that when you expand this mvc project, you also
    // should add more deletion queries to this function, so that all userData gets deleted!
    public function delete($userId){
      $this->db->query('DELETE FROM users WHERE id = :id');

      // Bind parameter
      $this->db->bind(':id', $userId);

      // Destroy session and restart it for flash messages.
      session_destroy();
      session_start();

      // Execute query
      if($this->db->execute()){
        flash('userDeleteSuccess', 'Your account and all data of you has been deleted!');
        return TRUE;
      } else {
        return FALSE;
      }
    }

    // Add resetToken to the user's data and send the email to the user.
    public function sendToken($userEmail, $userName){
      // Create the random token
      $resetToken = bin2hex(random_bytes(50));

      // Add resetToken to the user
      $this->db->query('UPDATE users SET resetToken = :resetToken WHERE email = :userEmail');

      // Bind values
      $this->db->bind(':resetToken', $resetToken);
      $this->db->bind(':userEmail', $userEmail);

      // Execute query
      if($this->db->execute()){
        // Set mail variables
        $recipient = $userName;
        $recipientMail = $userEmail;
        $subject = 'Password reset token.';
        $body = Mail::tokenMail($recipient, $resetToken);
        $altbody = strip_tags($body);

        $mail = new Mail($recipient, $recipientMail, $subject, $body, $altbody);
        $mail->send();
      } else {
        die('Something went wrong.');
      }
    }

    // Check the token with username and email, if this is not correct, the token will be deleted and the user needs to
    // start again with the process.
    public function checkToken($token, $userName, $userEmail){
      $this->db->query('SELECT * FROM users WHERE resetToken = :token AND name = :userName AND email = :userEmail');
      $this->db->bind(':token', $token);
      $this->db->bind(':userName', $userName);
      $this->db->bind(':userEmail', $userEmail);

      $this->db->fetchSingle();

      // check if the row is correct.
      if($this->db->rowCount() > 0){
        // User found.
        return TRUE;
      } else {
        // User is not found.
        return FALSE;
      }
    }

    // Remove the resetToken from the associated user account.
    public function removeToken($token){
      // Get the user from the token for a failed email.
      $this->db->query('SELECT * FROM users WHERE resetToken = :token');
      $this->db->bind(':token', $token);

      // Fetch the user
      $user = $this->db->fetchSingle();
      if(!isset($user->resetToken)){
        return FALSE;
      }
      if($user->resetToken === $token){
        // Set email variables
        $recipient = $user->name;
        $recipientEmail = $user->email;
        $subject = 'Password reset failed!';
        $body = Mail::resetFailed($user->name);
        $altBody = strip_tags($body);

        $mail = new Mail($recipient, $recipientEmail, $subject, $body, $altBody);
        // If the mail has not been sent, send false to die script.
        if(!$mail->send()){
          return FALSE;
        }
      } else {
        return FALSE;
      }
      // Delete the token from the account.
      $this->db->query('UPDATE users SET resetToken = :newToken WHERE resetToken = :token');
      $this->db->bind(':token', $token);
      $this->db->bind(':newToken', NULL);

      if($this->db->execute()){
        return TRUE;
      } else {
        return FALSE;
      }
    }

    // Check if the token exists in the database.
    public function checkTokenDb($token){
      $this->db->query('SELECT * FROM users WHERE resetToken = :token');
      $this->db->bind(':token', $token);

      // Get one object
      $this->db->fetchSingle();

      // Check if there is more than one object. If yes, return true.
      if($this->db->rowCount() > 0){
        return TRUE;
      } else {
        return FALSE;
      }
    }

    // Reset the password
    public function resetPassword($password, $token, $userEmail, $userName){
      $this->db->query('UPDATE users SET password = :password WHERE resetToken = :token');
      $this->db->bind(':password', $password);
      $this->db->bind(':token', $token);

      // Check if the query completed.
      if($this->db->execute()){
        $this->db->query('UPDATE users SET resetToken = :newToken WHERE resetToken = :token');
        $this->db->bind(':token', $token);
        $this->db->bind(':newToken', NULL);

        if($this->db->execute()){
          flash('resetPasswordSuccess', 'Your password has been successfully reset! Please log in below.');
          // Set email variables
          $recipient = $userName;
          $recipientEmail = $userEmail;
          $subject = 'Password has been reset.';
          $body = Mail::passwordReset($userName);
          $altBody = strip_tags($body);

          $mail = new Mail($recipient, $recipientEmail, $subject, $body, $altBody);
          // If the mail has not been sent, send false to die script.
          if($mail->send()){
            return TRUE;
          }
        }
      } else {
        return FALSE;
      }
    }

    // Check if the user is activated or not.
    public function isActivated($login){
      $this->db->query('SELECT activated, password FROM users WHERE name = :login OR email = :login');
      $this->db->bind(':login',$login);

      // Get one object
      $user = $this->db->fetchSingle();

      // Check if there are more than 0 objects.
      if($this->db->rowCount() > 0){
        if($user->activated){
          return TRUE;
        }
      }
      // User is not activated
      return FALSE;
    }

    // Check if password corresponds with the username
    public function activatePass($user, $pass){
      $hashedPass = $user->password;
      if(password_verify($pass, $hashedPass)){
        return TRUE;
      }
      return FALSE;
    }

    // Activate the account.
    public function activateAccount($userName){
      $this->db->query('UPDATE users SET activated = :value WHERE name = :userName');
      $this->db->bind(':value', TRUE);
      $this->db->bind(':userName', $userName);
      if($this->db->execute()){
        return TRUE;
      }
      return FALSE;
    }

    /*
     * fetchAllUserData()
     *
     * This function is not complete when you create your own app. Please update this function with ALL other userdata
     * you are planning to use in your application.
     */
    public function fetchAllUserData($userId){
      $this->db->query('SELECT name, email, created_at FROM users WHERE id = :id');
      $this->db->bind(':id', $userId);
      $user['user'] = $this->db->fetchSingle();
      if($user){
        return $user;
      }
      return FALSE;
    }
  }