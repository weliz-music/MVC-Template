<?php
  /*
   * User Model.
   *
   * This model extends the Controller where needed with specific functions for that controller.
   */
  class User {
    private $db;
    public function __construct() {
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
    public function register($data) {
      $this->db->query('INSERT INTO users (name, email, password, level) VALUES (:name, :email, :password, :level)');
      // Bind values
      $this->db->bind(':name', $data['userName']);
      $this->db->bind(':email', $data['userEmail']);
      $this->db->bind(':password', $data['userPass']);
      $this->db->bind(':level', 'user');

      // Execute the query
      if($this->db->execute()) {
        // Send activation mail
        $recipient = $data['userName'];
        $recipientMail = $data['userEmail'];
        $subject = 'Activate your account.';
        $body = Mail::activationMail($recipient);
        $altbody = strip_tags($body);

        $mail = new Mail($recipient, $recipientMail, $subject, $body, $altbody);
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
    public function login($login, $password) {
      $this->db->query('SELECT * FROM users WHERE email = :login OR name = :login');
      $this->db->bind(':login', $login);

      // Get the user
      $user = $this->db->fetchSingle();

      // Check if the user exists.
      if($user) {
        $hashedPassword = $user->password;
        if (password_verify($password, $hashedPassword)) {
          // Return the user.
          return $user;
        }
      }
      // If nothing is found, return false.
      return FALSE;
    }


    /*
     * addToHistory()
     *
     * This function adds a few values to the logins table in the database.
     */
    function addToHistory($id){
      // Set query.
      $this->db->query('INSERT INTO logins (userId, ip, country, city) VALUES (:userId, :ip, :country, :city)');
      // Get the data
      // Set ip
      if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
      } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
      } else {
        $ip = $_SERVER['REMOTE_ADDR'];
      }
      $loginData = json_decode(file_get_contents("http://api.ipstack.com/".$ip.'&access_key=2436a92b33638c51f3d14bc2b36ad5d0?fields=ip,country_name,city'));
      //die(print_r($loginData));
      // Bind the values.
      $bindArray = array(
        ':userId' => $id,
        ':ip' => $loginData->ip,
        ':country' => $loginData->country_name,
        ':city' => $loginData->city,
      );
      foreach ($bindArray as $bind => $value){
        $this->db->bind($bind, $value);
      }
      // Execute the query.
      if($this->db->execute()){
        return TRUE;
      }
      return FALSE;
    }


    /*
     * getPastLogins()
     *
     * This function gets all past login history from the specified user.
     */
    function getPastLogins($userId){
      // Set query.
      $this->db->query('SELECT * FROM logins WHERE userId = :userId ORDER BY id DESC');
      // Bind the value.
      $this->db->bind(':userId', $userId);
      // Store the query in a value.
      $logins = $this->db->fetchAll();
      // Check and return.
      if($logins){
        return $logins;
      }
      return FALSE;
    }


    /*
     * findByEmail()
     *
     * This function searches for an user by their email.
     *
     * Usage (In the Controller):
     *    $this->model->findByEmail($userEmail);
     */
    public function findByEmail($email) {
      $this->db->query('SELECT * FROM users WHERE email = :email');
      $this->db->bind(':email', $email);

      $user = $this->db->fetchSingle();

      // Check row
      if($this->db->rowCount() > 0) {
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
    public function findByUserName($userName) {
      $this->db->query('SELECT * FROM users WHERE name = :userName');
      $this->db->bind(':userName', $userName);

      $user = $this->db->fetchSingle();

      // Check row
      if($this->db->rowCount() > 0) {
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
    public function findByUsernameAndEmail($userName, $userEmail) {
      $this->db->query('SELECT name, email FROM users WHERE name = :userName AND email = :userEmail');

      // Bind parameters.
      $this->db->bind(':userName', $userName);
      $this->db->bind(':userEmail', $userEmail);

      $user = $this->db->fetchSingle();

      // check if the row is correct.
      if($this->db->rowCount() > 0) {
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
    public function validatePass($password) {
      $upperCase = '/[A-Z]/';
      $lowerCase = '/[a-z]/';
      $specialChar = '/[!@#$%^&*]/';
      $number = '/[0-9]/';
      $returnValue = '';
      if(strlen($password) <= 10) {
        $returnValue .= "Password must be at least 10 characters!<br>";
      }
      if(strlen($password) >= 100) {
        $returnValue .= "Password must be less than 100 characters!<br>";
      }
      if(preg_match_all($upperCase, $password, $o) < 2) {
        $returnValue .= 'Missing atleast 2 uppercase characters.<br>';
      }
      if(preg_match_all($lowerCase, $password, $o) < 2) {
        $returnValue .= 'Missing atleast 2 lowercase characters.<br>';
      }
      if(preg_match_all($specialChar, $password, $o) < 1) {
        $returnValue .= 'Missing atleast 1 special character. Usable characters: !@#$%^&*<br>';
      }
      if(preg_match_all($number, $password, $o) < 2) {
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
    public function createSession($user) {
      if(!$this->getUserSetting('tfa_auth', $user->id)){
        //die('something went wrong!');
        $_SESSION['loggedIn'] = TRUE;
        // Add login to login history.
        $this->addToHistory($user->id);
      }

      // Set session variables.
      $_SESSION['userId'] = $user->id;
      $_SESSION['userName'] = $user->name;
      $_SESSION['userEmail'] = $user->email;
      $_SESSION['userLevel'] = $user->level;
      return;
    }

    /*
     * auth()
     *
     * This function will authenticate the user.
     */
    public function auth($tfaToken){
      // Set query
      $this->db->query('SELECT name FROM users WHERE id = :id AND tfaToken = :tfaToken');
      // Bind values
      $this->db->bind(':id', $_SESSION['userId']);
      $this->db->bind(':tfaToken', $tfaToken);
      // Store value
      $result = $this->db->fetchSingle();
      // Delete the record from the database.
      $this->db->query('UPDATE users SET tfaToken = NULL WHERE id = :id');
      $this->db->bind(':id', $_SESSION['userId']);
      $this->db->execute();

      if($result){
        // Add login to login history.
        $this->addToHistory($_SESSION['userId']);

        // Set other session variables.
        $_SESSION['loggedIn'] = TRUE;
        return TRUE;
      }
      flash('userDeleteSuccess', 'There was an error with your Authentication code. <br>Please retry logging in.', 'alert alert-danger');
      return FALSE;
    }

    /*
     * getTfaToken()
     *
     * This function will fetch the 2 factor authentication token from the database. If there is none, return false.
     */
    public function getTfaToken(){
      // Set query
      $this->db->query('SELECT tfaToken FROM users WHERE id = :id');
      // Bind value
      $this->db->bind(':id', $_SESSION['userId']);
      // Execute
      $tfaToken = $this->db->fetchSingle();
      // Check and return.
      if($tfaToken){
        return $tfaToken;
      }
      return FALSE;
    }

    /*
     * sendTfaMail()
     *
     * This function will send the 2 factor authentication mail.
     */
    public function sendTfaMail(){
      // Set 2FA code.
      $tfaToken = strtoupper(bin2hex(openssl_random_pseudo_bytes(4)));
      // Send activation mail
      $recipient = $_SESSION['userName'];
      $recipientMail = $_SESSION['userEmail'];
      $subject = 'Authentication code.';
      $body = Mail::tfa($recipient, $tfaToken);
      $altbody = strip_tags($body);

      // Create and send the mail.
      $mail = new Mail($recipient, $recipientMail, $subject, $body, $altbody);
      $mail->send();

      // Set query
      $this->db->query('UPDATE users SET tfaToken = :tfaToken WHERE id = :id');
      // Bind values.
      $this->db->bind(':tfaToken', $tfaToken);
      $this->db->bind(':id', $_SESSION['userId']);

      if($this->db->execute()){
        return TRUE;
      }
      return FALSE;
    }

    /*
     * checkPassword()
     *
     * This function will check if the given password is correct.
     */
    public function checkPassword($pass) {
      $this->db->query('SELECT password FROM users where email = :email');
      $this->db->bind(':email', $_SESSION['userEmail']);

      $user = $this->db->fetchSingle();

      $hashedPassword = $user->password;
      if(password_verify($pass, $hashedPassword)) {
        return TRUE;
      } else {
        return FALSE;
      }
    }

    /*
     * changePassword()
     *
     * This function will change the user's password.
     */
    public function changePassword($userId, $password) {
      // Hash user Password
      $password = password_hash($password, PASSWORD_DEFAULT);
      $this->db->query('UPDATE users SET password = :password WHERE id = :id');

      // Bind parameters.
      $this->db->bind(':password', $password);
      $this->db->bind(':id', $userId);

      // Execute query.
      if($this->db->execute()) {
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

    /*
     * changeEmail()
     *
     * This function will change the user's email address.
     */
    public function changeEmail($userId, $email) {
      $this->db->query('UPDATE users SET email = :email WHERE id = :id');

      // Bind parameters.
      $this->db->bind(':email', $email);
      $this->db->bind(':id', $userId);

      // Execute query.
      if($this->db->execute()) {
        flash('userEmailChangeSuccess', 'Your email has been changed!');
        return TRUE;
      } else {
        return FALSE;
      }
    }

    /*
     * changeUserName()
     *
     * This function will change the user's name.
     */
    public function changeUserName($userId, $userName) {
      $this->db->query('UPDATE users SET name = :userName WHERE id = :id');

      // Bind parameters.
      $this->db->bind(':userName', $userName);
      $this->db->bind(':id', $userId);

      // Execute query
      if($this->db->execute()) {
        flash('userNameChangeSuccess', 'Your username has been changed!');
        return TRUE;
      } else {
        return FALSE;
      }
    }


    /*
     * delete()
     *
     * This function will delete the user.
     */
    public function delete($userId) {
      $this->db->query('DELETE FROM users WHERE id = :id');

      // Bind parameter
      $this->db->bind(':id', $userId);

      // Destroy session and restart it for flash messages.
      session_destroy();
      session_start();

      // Execute query
      if($this->db->execute()) {
        flash('userDeleteSuccess', 'Your account and all data of you has been deleted!');
        return TRUE;
      } else {
        return FALSE;
      }
    }

    /*
     * sendToken()
     *
     * This function will send a resetToken for if the user has forgotten / needs to reset their password.
     */
    public function sendToken($userEmail, $userName) {
      // Create the random token
      $resetToken = bin2hex(random_bytes(50));

      // Add resetToken to the user
      $this->db->query('UPDATE users SET resetToken = :resetToken WHERE email = :userEmail');

      // Bind values
      $this->db->bind(':resetToken', $resetToken);
      $this->db->bind(':userEmail', $userEmail);

      // Execute query
      if($this->db->execute()) {
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

    /*
     * checkToken()
     *
     * This function will check if the given resetToken is correct. If it is not, it will restart the process.
     */
    public function checkToken($token, $userName, $userEmail) {
      $this->db->query('SELECT * FROM users WHERE resetToken = :token AND name = :userName AND email = :userEmail');
      $this->db->bind(':token', $token);
      $this->db->bind(':userName', $userName);
      $this->db->bind(':userEmail', $userEmail);

      $this->db->fetchSingle();

      // check if the row is correct.
      if($this->db->rowCount() > 0) {
        // User found.
        return TRUE;
      } else {
        // User is not found.
        return FALSE;
      }
    }

    /*
     * removeToken()
     *
     * This function will remove the resetToken from the database.
     */
    public function removeToken($token) {
      // Get the user from the token for a failed email.
      $this->db->query('SELECT * FROM users WHERE resetToken = :token');
      $this->db->bind(':token', $token);

      // Fetch the user
      $user = $this->db->fetchSingle();
      if(!isset($user->resetToken)) {
        return FALSE;
      }
      if($user->resetToken === $token) {
        // Set email variables
        $recipient = $user->name;
        $recipientEmail = $user->email;
        $subject = 'Password reset failed!';
        $body = Mail::resetFailed($user->name);
        $altBody = strip_tags($body);

        $mail = new Mail($recipient, $recipientEmail, $subject, $body, $altBody);
        // If the mail has not been sent, send false to die script.
        if(!$mail->send()) {
          return FALSE;
        }
      } else {
        return FALSE;
      }
      // Delete the token from the account.
      $this->db->query('UPDATE users SET resetToken = :newToken WHERE resetToken = :token');
      $this->db->bind(':token', $token);
      $this->db->bind(':newToken', NULL);

      if($this->db->execute()) {
        return TRUE;
      } else {
        return FALSE;
      }
    }

    /*
     * checkTokenDb()
     *
     * This function will check of the given token exists in the database.
     */
    public function checkTokenDb($token) {
      $this->db->query('SELECT * FROM users WHERE resetToken = :token');
      $this->db->bind(':token', $token);

      // Get one object
      $this->db->fetchSingle();

      // Check if there is more than one object. If yes, return true.
      if($this->db->rowCount() > 0) {
        return TRUE;
      } else {
        return FALSE;
      }
    }

    /*
     * resetPassword()
     *
     * This function will reset the password of the user.
     */
    public function resetPassword($password, $token, $userEmail, $userName) {
      $this->db->query('UPDATE users SET password = :password WHERE resetToken = :token');
      $this->db->bind(':password', $password);
      $this->db->bind(':token', $token);

      // Check if the query completed.
      if($this->db->execute()) {
        $this->db->query('UPDATE users SET resetToken = :newToken WHERE resetToken = :token');
        $this->db->bind(':token', $token);
        $this->db->bind(':newToken', NULL);

        if($this->db->execute()) {
          flash('resetPasswordSuccess', 'Your password has been successfully reset! Please log in below.');
          // Set email variables
          $recipient = $userName;
          $recipientEmail = $userEmail;
          $subject = 'Password has been reset.';
          $body = Mail::passwordReset($userName);
          $altBody = strip_tags($body);

          $mail = new Mail($recipient, $recipientEmail, $subject, $body, $altBody);
          // If the mail has not been sent, send false to die script.
          if($mail->send()) {
            return TRUE;
          }
        }
      } else {
        return FALSE;
      }
    }

    /*
     * isActivated()
     *
     * This will check if the user who's trying to log in, has been activated already.
     */
    public function isActivated($login) {
      $this->db->query('SELECT activated, password FROM users WHERE name = :login OR email = :login');
      $this->db->bind(':login', $login);

      // Get one object
      $user = $this->db->fetchSingle();

      // Check if there are more than 0 objects.
      if($this->db->rowCount() > 0) {
        if($user->activated) {
          return TRUE;
        }
      }
      // User is not activated
      return FALSE;
    }

    /*
     * activatePass()
     *
     * This function checks if the given user and password in the activation process are correct.
     */
    public function activatePass($user, $pass) {
      $hashedPass = $user->password;
      if(password_verify($pass, $hashedPass)) {
        return TRUE;
      }
      return FALSE;
    }

    /*
     * activateAccount()
     *
     * This function will update the account in the database to reflect that the account is now activated.
     */
    public function activateAccount($userName) {
      $this->db->query('UPDATE users SET activated = :value WHERE name = :userName');
      $this->db->bind(':value', TRUE);
      $this->db->bind(':userName', $userName);
      if($this->db->execute()) {
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
    public function fetchAllUserData($userId) {
      // Get the user first.
      $this->db->query('SELECT name, email, created_at FROM users WHERE id = :id');
      $this->db->bind(':id', $userId);
      $user['user'] = $this->db->fetchSingle();
      if($user){
        // Logins
        $this->db->query('SELECT ip, country, city, date FROM logins WHERE userId = :userId');
        $this->db->bind(':userId', $_SESSION['userId']);
        $user['logins'] = $this->db->fetchAll();

        return $user;
      }
      return FALSE;
    }


    /*
     * getUserSetting()
     *
     * This function will get a specific setting.
     */
    public function getUserSetting($name, $userId){
      // check for new settings first and update them.
      $_SESSION['userId'] = $userId;
      $this->setDefaultSettings();
      // Set query.
      $this->db->query('SELECT value FROM settings WHERE name = :name AND userId = :userId');
      // Bind the values.
      $this->db->bind(':name', $name);
      $this->db->bind(':userId', $userId);
      // Execute and store.
      $value = $this->db->fetchSingle();
      // Check.
      if($value){
        return $value->value;
      }
      return FALSE;
    }


    /*
     * getSettings()
     *
     * This function will send all user settings back as separate objects.
     */
    public function getSettings($userId = NULL){
      // check for new settings first and update them.
      $this->setDefaultSettings();
      // Set query
      $this->db->query('SELECT name, value FROM settings WHERE userId = :userId');
      // Bind the value.
      if($userId) {
        $this->db->bind(':userId', $userId);
      } else {
        $this->db->bind(':userId', $_SESSION['userId']);
      }
      // Store the result in a variable.
      $settings = $this->db->fetchAll();
      // Check the variable.

      if($settings){
        // Get the descriptions of the settings.
        $i = 0;
        foreach ($settings as $setting){
          $setting->description = $this->getSettingDescription($setting->name)->description;
          $i++;
        }
        // Return the settings.
        return $settings;
      }
    }


    /*
     * getSettingDescription()
     *
     * This function will fetch the description for the specified setting.
     */
    public function getSettingDescription($name){
      // Set the query.
      $this->db->query('SELECT description FROM settings_default WHERE name = :name');
      // Bind the value.
      $this->db->bind(':name', $name);
      // Fetch the result.
      $description = $this->db->fetchSingle();
      // Check the result and return it.
      if($description){
        return $description;
      }
      return FALSE;
    }


    /*
     * updateSettings()
     *
     * This function will update the user settings.
     */
    public function updateSettings(){
      $settings = $_POST;
      // Process all settings.
      foreach ($settings as $key => $value) {
        // Set query
        $this->db->query('UPDATE settings SET value = :value WHERE name = :name AND userId = :userId');
        // Bind values.
        $this->db->bind(':value', $value);
        $this->db->bind(':name', $key);
        $this->db->bind(':userId', $_SESSION['userId']);

        $this->db->execute();
      }
      return TRUE;
    }


    /*
     * setDefaultSettings()
     *
     * This function will set all settings to default, if they do not exist.
     */
    public function setDefaultSettings(){
      // Get all settings from the table.
      $this->db->query('SELECT name, default_value FROM settings_default');
      $result = $this->db->fetchAll();

      // Set all settings in an array.
      $settings = array();
      foreach ($result as $setting){
        $settings[$setting->name] = $setting->default_value;
      }

      // Go through every setting and check accordingly.
      foreach ($settings as $name => $value){
        // Check if a record exists.
        $this->db->query('SELECT name FROM settings WHERE name = :name AND userId = :userId');
        $this->db->bind(':name', $name);
        $this->db->bind(':userId', $_SESSION['userId']);
        $result = $this->db->fetchSingle();
        // Check the result and then add the setting.
        if(!$result){
          // Set query.
          $this->db->query('INSERT INTO settings (userId, name, value) VALUES (:userId, :name, :value)');
          $this->db->bind(':userId', $_SESSION['userId']);
          $this->db->bind(':name', $name);
          $this->db->bind(':value', $value);

          $this->db->execute();
        }
      }
    }
  }