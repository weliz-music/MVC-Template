<?php
  /*
   * Users Controller.
   *
   * This controller handles every request after URL/users/*.
   *
   * This controller has the function of registering users, logging them in and doing various alterations on them such
   * as changing their email or their password.
   *
   */
  class Users extends Controller
  {
    private $userModel;

    public function __construct(){
      $this->userModel = $this->model('User');
    }

    /*
     * /Users/index
     *
     * Main landing page for the users.
     */
    public function index(){
      // Check if the user is logged in, if not, send them to the login form.
      if (!isLoggedIn()) {
        redirect('/users/login');
      }

      // Initialize data.
      $data = array(
        'title' => 'User Settings - ' . APP_NAME,
        'description' => 'You can change various settings on this page.',
        'userName' => $_SESSION['userName'],
        'userEmail' => $_SESSION['userEmail'],
      );

      // Render the view.
      $this->render('users/index', $data);
    }

    /*
     * /Users/register
     *
     * Page for the users to register themselves on this site.
     */
    public function register(){
      // Initialize default data.
      $data = array(
        'title' => 'Register - ' . APP_NAME,
        'userName' => '',
        'userEmail' => '',
        'userPass' => '',
        'userPassConfirm' => '',
        'userPrivacy' => '',
        'userNameError' => '',
        'userEmailError' => '',
        'userPassError' => '',
        'userPassConfirmError' => '',
        'userPrivacyError' => '',
      );

      // Check for POST
      if ($_SERVER['REQUEST_METHOD'] == "POST") {
        // Process the form

        // Sanitize POST Data
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

        // Update data with submitted data
        $data['userName'] = trim($_POST['userName']);
        $data['userEmail'] = trim($_POST['userEmail']);
        $data['userPass'] = trim($_POST['userPass']);
        $data['userPassConfirm'] = trim($_POST['userPassConfirm']);
        $data['userPrivacy'] = trim($_POST['userPrivacy']);

        // Validate Name.
        if (empty($data['userName'])) {
          $data['userNameError'] = 'Please enter an username!';
        } elseif (strlen($data['userName']) > 100){
          $data['userNameError'] = 'Username is too long!';
        } elseif ($this->userModel->findByUserName($data['userName'])) {
          $data['userNameError'] = 'Username is already taken!';
        }

        // Validate Email.
        if (empty($data['userEmail'])) {
          $data['userEmailError'] = 'Please enter an email address!';
        } elseif (strlen($data['userEmail']) > 100){
          $data['userEmailError'] = 'Email is too long!';
        } elseif ($this->userModel->findByEmail($data['userEmail'])) {
          $data['userEmailError'] = 'Email already exists!';
        }

        // Validate Password.
        $passError = $this->userModel->validatePass($data['userPass']);
        if (!empty($passError)) {
          $data['userPassError'] = $passError;
        }

        // Validate Confirm Password.
        if (empty($data['userPassConfirm'])) {
          $data['userPassConfirmError'] = 'Please enter your password again!';
        } elseif ($data['userPass'] != $data['userPassConfirm']) {
          $data['userPassConfirmError'] = 'Passwords do not match!';
        }

        // Validate the privacy notice.
        if (empty($data['userPrivacy'])) {
          $data['userPrivacyError'] = 'You must accept our privacy policy to make use of this site!';
        } elseif ($data['userPrivacy'] != 'yes') {
          $data['userPrivacyError'] = 'Please type "yes" into the box above to accept our policy!';
        }

        // Make sure that the errors are empty.
        if (empty($data['userNameError']) && empty($data['userEmailError']) && empty($data['userPassError']) && empty($data['userPassConfirmError'])) {
          // Validated.
          // Hash password.
          $data['userPass'] = password_hash($data['userPass'], PASSWORD_DEFAULT);

          // Register User
          if ($this->userModel->register($data)) {
            // If the process went correctly, redirect them to the login page.
            redirect('/users/login');
          }
        }
      }
      // Load view
      $this->render('users/register', $data);
    }

    /*
     * /Users/login
     *
     * This page will log the user into the app and show relevant error messages.
     */
    public function login(){
      // Initialize default data
      $data = array(
        'title' => 'Login - '. APP_NAME,
        'userLogin' => '',
        'userPass' => '',
        'userLoginError' => '',
        'userPassError' => '',
      );

      // Check for POST
      if($_SERVER['REQUEST_METHOD'] == "POST") {
        // Process the form

        // Sanitize POST Data
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

        // Set data variables.
        $data['userLogin'] = trim($_POST['userLogin']);
        $data['userPass'] = trim($_POST['userPass']);

        // Validate if username is not empty
        if(empty($data['userLogin'])){
          $data['userLoginError'] = "Wrong username and/or password!";
          $data['userPassError'] = "Wrong username and/or password!";
        }

        // Make sure errors are empty
        if (empty($data['userLoginError']) && empty($data['userPassError'])) {
          // Validated
          // Check and set logged user.
          $user = $this->userModel->login($data['userLogin'], $data['userPass']);
          if ($user) {
            // Check if the user had been activated
            if (!$this->userModel->isActivated($data['userLogin'])) {
              $data['viewPart'] = 'noActivation';
              $this->render('users/login', $data);
            } else {
              // Create session.
              $this->userModel->createSession($user);
              redirect('/users');
            }
          } else {
            $data['userLoginError'] = "Wrong username and/or password!";
            $data['userPassError'] = "Wrong username and/or password!";
          }
        }
      }
      $this->render('users/login', $data);
    }

    /*
     * /Users/logout
     *
     * This is only a small redirect function in which the session gets destroyed.
     */
    public function logout(){
      session_destroy();
      session_start();
      flash('logout_success', 'You have been logged out!');
      redirect('/users/login');
    }

    /*
     * /Users/changePassword
     *
     * This page is so that users can change their passwords.
     */
    public function changePassword(){
      // This page should only be accessible by users who are logged in.
      if(!isLoggedIn()){
        redirect('/users/login');
      }

      // Initialize default data.
      $data = array(
        'title' => 'Change your password - '. APP_NAME,
        'userCurrentPass' => '',
        'userCurrentPassError' => '',
        'userNewPass' => '',
        'userNewPassError' => '',
        'userNewPassConfirm' => '',
        'userNewPassConfirmError' => '',
      );

      // Check for POST
      if($_SERVER['REQUEST_METHOD'] == "POST") {
        // Process the form

        // Sanitize POST Data
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

        // update data with POST data.
        $data['userCurrentPass'] = trim($_POST['userCurrentPass']);
        $data['userNewPass'] = trim($_POST['userNewPass']);
        $data['userNewPassConfirm'] = trim($_POST['userNewPassConfirm']);

        // Validate current password
        if (!$this->userModel->checkPassword($data['userCurrentPass'])) {
          $data['userCurrentPassError'] = "Password is not correct!";
        }

        // Validate if the new password has enough security.
        $passError = $this->userModel->validatePass($data['userNewPass']);
        if (!empty($passError)) {
          $data['userNewPassError'] = $passError;
        } // Check if the password is not the same as the one before.
        elseif ($data['userCurrentPass'] == $data['userNewPass']) {
          $data['userNewPassError'] = 'Password cannot be the same as your last used password!';
        }

        // Check if the repeated password is not empty and if it's the same as the new password.
        if (empty($data['userNewPassConfirm'])) {
          $data['userNewPassConfirmError'] = 'Please enter your password again!';
        } elseif ($data['userNewPass'] != $data['userNewPassConfirm']) {
          $data['userNewPassConfirmError'] = 'Passwords do not match';
        }

        // Check if there are no errors.
        if (empty($data['userCurrentPassError']) && empty($data['userNewPassError']) && empty($data['userNewPassConfirmError'])) {
          if ($this->userModel->changePassword($_SESSION['userId'], $data['userNewPass'])) {
            redirect('/users');
          } else {
            die("Learn to code better");
          }
        }
      }
      // Load view
      $this->render('users/changePassword', $data);
    }


    /*
     * /Users/changeEmail
     *
     * This page is so that users can change their email when needed.
     */
    public function changeEmail(){
      // This page should only be accessible by users who are logged in.
      if(!isLoggedIn()){
        redirect('/users/login');
      }

      // Initialize default data.
      $data = array(
        'title' => 'Change your email - ' . APP_NAME,
        'description' => 'Please fill in the form below to change your email address.',
        'userNewEmail' => '',
        'userNewEmailError' => '',
        'userPass' => '',
        'userPassError' => '',
      );

      // Check for POST
      if($_SERVER['REQUEST_METHOD'] == "POST") {
        // Process the form

        // Sanitize POST Data
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

        // Update data with POST data.
        $data['userNewEmail'] = trim($_POST['userNewEmail']);
        $data['userPass'] = trim($_POST['userPass']);

        // Validate email
        if(empty($data['userNewEmail'])) {
          $data['userNewEmailError'] = "Please enter a new email address!";
        } elseif ($this->userModel->findByEmail($data['userNewEmail'])){
          $data['userNewEmailError'] = "This email is already taken!";
        } elseif(strlen($data['userNewEmail']) > 100){
          $data['userNewEmailError'] = 'New email address is too long!';
        }

        // Validate password
        if(!$this->userModel->checkPassword($data['userPass'])){
          $data['userPassError'] = "Password is not correct!";
        }

        // Check if all errors are empty
        if(empty($data['userNewEmailError']) && empty($data['userPassError'])){
          // Update the new email
          if($this->userModel->changeEmail($_SESSION['userId'], $data['userNewEmail'])){
            // Update session variable
            $_SESSION['userEmail'] = $data['userNewEmail'];
            // Redirect to user settings.
            redirect('/users');
          } else {
            die('You really should learn how to code better!');
          }
        }
      }
      // Load view
      $this->render('users/changeEmail', $data);
    }

    /*
     * /Users/changeUserName
     *
     * This page is so that users can change their chosen username.
     */
    public function changeUserName(){
      // This page should only be accessible by users who are logged in.
      if(!isLoggedIn()){
        redirect('/users/login');
      }

      // Initialize default data
      $data = array(
        'title' => 'Change your username - '.APP_NAME,
        'userName' => '',
        'userNameError' => '',
        'userPass' => '',
        'userPassError' => '',
      );

      // Check for POST
      if($_SERVER['REQUEST_METHOD'] == "POST"){
        // Process the form.

        // Sanitize POST Data
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

        // Update data with POST data
        $data['userName'] = trim($_POST['userName']);
        $data['userPass'] = trim($_POST['userPass']);

        // Validate userName.
        if(empty($data['userName'])){
          $data['userNameError'] = 'Please enter a new username!';
        } elseif($this->userModel->findByUserName($data['userName'])){
          $data['userNameError'] = 'Username already taken!';
        } elseif(strlen($data['userName']) > 100){
          $data['userNameError'] = 'Username is too long!';
        }

        // Validate password.
        if(!$this->userModel->checkPassword($data['userPass'])){
          $data['userPassError'] = "Password is not correct!";
        }

        // Check if there are no errors.
        if(empty($data['userNameError']) && empty($data['userPassError'])){
          // Update the username.
          if($this->userModel->changeUserName($_SESSION['userId'], $data['userName'])){
            // Update session variable.
            $_SESSION['userName'] = $data['userName'];
            // Redirect user to settings page.
            redirect('/users');
          } else {
            die('You broke it again, didn\'t you?');
          }
        }
      }
      // Render the view.
      $this->render('users/changeUserName', $data);
    }

    /*
     * /Users/delete
     *
     * This page is so that users can delete their own accounts.
     */
    public function delete(){
      // Check if the user is logged in
      if(!isLoggedIn()){
        redirect('/users/login');
      }

      // Initialize default data
      // Initialize data
      $data = array(
        'title' => 'Delete account - ' . APP_NAME,
        'userEmail' => '',
        'userEmailError' => '',
        'userPass' => '',
        'userPassError'=> '',
        'userConfirm' => '',
        'userConfirmError' => '',
      );

      // Check for POST data
      if($_SERVER['REQUEST_METHOD'] == "POST"){
        // Process form

        // Sanitize POST Data
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

        // Update data with POST data
        $data['userEmail'] = trim($_POST['userEmail']);
        $data['userPass'] = trim($_POST['userPass']);
        $data['userConfirm'] = trim($_POST['userConfirm']);

        // Validate username
        // Check if user exists in the database.
        if(!$this->userModel->findByEmail($data['userEmail'])){
          $data['userEmailError'] = 'You have entered the wrong email!';
        }
        // Check if the entered email is the same as the logged in email.
        elseif ($data['userEmail'] != $_SESSION['userEmail']){
          $data['userEmailError'] = 'You have entered the wrong email!';
        }

        // Validate password
        if(!$this->userModel->checkPassword($data['userPass'])){
          $data['userPassError'] = 'Password is not correct!';
        }

        // Validate confirmation
        if($data['userConfirm'] != 'yes'){
          $data['userConfirmError'] = 'You have not entered \'yes\'. Please try again!';
        }

        // Check if the errors are empty
        if(empty($data['userEmailError']) && empty($data['userPassError']) && empty($data['userConfirmError'])){
          // Delete the user and redirect to the login page.
          if($this->userModel->delete($_SESSION['userId'])){
            redirect('/users/login');
          } else {
            die('You can\'t even delete shit properly, stop coding!');
          }
        }
      }
      // Render the view.
      $this->render('users/delete', $data);
    }

    /*
     * /Users/activate
     *
     * This page is so that users can activate their account before they can use them.
     */
    public function activate(){
      // Init default data.
      $data = array(
        'title' => 'Activate account - '.APP_NAME,
        'viewPart' => 'error',
        'userPass' => '',
        'userPassError' => ''
      );
      // check if the username is set.
      if(isset($_GET['name'])){
        // Filter the get parameter
        $userName = filter_var($_GET['name'], FILTER_SANITIZE_STRING);

        // Check if the username is set.
        if(!empty($userName)){
          // Check if the username exists
          $user = $this->userModel->findByUserName($userName);
          if($user){
            // Check if the user is already activated.
            if(!$this->userModel->isActivated($userName)){
              $data['viewPart'] = 'activate';
              $data['userName'] = $userName;
              // Check for post data
              if($_SERVER['REQUEST_METHOD'] == 'POST') {
                // Sanitize $_POST
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

                // Set password to POST data
                $data['userPass'] = $_POST['userPass'];

                // Validate if password matches the password for the userAccount.
                if($this->userModel->activatePass($user, $data['userPass'])){
                  // Activate the account.
                  if($this->userModel->activateAccount($userName)){
                    $data['viewPart'] = 'success';
                  }
                } else {
                  $data['viewPart'] = 'error';
                }
              }
            }
          }
        }
      }
      $this->render('users/activate', $data);
    }

    /*
     * /Users/resetPassword
     *
     * This page is so that users can reset their password when they have forgotten this.
     */
    public function resetPassword(){
      // The user cannot come here when they have a valid session.
      if(isLoggedIn()){
        redirect('/users');
      }

      // Initialize default data.
      // Init data.
      $data = array(
        'title' => 'Reset Password - '.APP_NAME,
        'viewPart' => 'token',
        'token' => '',
        'userName' => '',
        'userNameError' => '',
        'userEmail' => '',
        'userEmailError' => '',
        'userNewPass' => '',
        'userNewPassError' => '',
        'userNewPassConfirm' => '',
        'userNewPassConfirmError' => '',
      );

      // Check if a resetToken has been set.
      if(isset($_GET['token'])){
        // Code for handling the token

        // Set default values for errors
        $data['title'] = 'Password reset - '.APP_NAME;
        $data['viewPart'] = 'error';

        // Sanitize token
        $token = filter_var($_GET['token'], FILTER_SANITIZE_STRING);

        // check if the token has the required length
        if(strlen($token) != 100){
          // Check if the length for the token is enough.
          $this->render('users/resetPassword', $data);
        } elseif(!$this->userModel->checkTokenDb($token)){
          // Check if the token does not exists in the database.
          $this->render('users/resetPassword', $data);
        } else {
          // Token exists and is valid.
          $data['viewPart'] = 'token';
          $data['token'] = $token;
          // Check for post data
          if($_SERVER['REQUEST_METHOD'] == 'POST'){
            // Sanitize $_POST
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // Update data with POST Data
            $data['userName'] = trim($_POST['userName']);
            $data['userEmail'] = trim($_POST['userEmail']);
            $data['userNewPass'] = trim($_POST['userNewPass']);
            $data['userNewPassConfirm'] = trim($_POST['userNewPassConfirm']);

            // Validate username
            if(empty($data['userName'])){
              $data['userNameError'] = 'Please enter your username!';
            }

            // Validate email
            if(empty($data['userEmail'])){
              $data['userEmailError'] = 'Please enter your email address!';
            }

            // Validate if the new password has enough security.
            $passError = $this->userModel->validatePass($data['userNewPass']);
            if (!empty($passError)){
              $data['userNewPassError'] = $passError;
            }

            // Check if the repeated password is not empty and if it's the same as the new password.
            if(empty($data['userNewPassConfirm'])){
              $data['userNewPassConfirmError'] = 'Please enter your password again!';
            } elseif($data['userNewPass'] != $data['userNewPassConfirm']){
              $data['userNewPassConfirmError'] = 'Passwords do not match';
            }

            // check if all errors are empty, if yes, start validating the token.
            if(empty($data['userNameError']) && empty($data['userEmailError']) && empty($data['userNewPassError']) && empty($data['userNewPassConfirmError'])){
              // Validate token with the username and email
              if($this->userModel->checkToken($data['token'], $data['userName'], $data['userEmail'])){
                // Hash the password
                $data['userNewPass'] = password_hash($data['userNewPass'], PASSWORD_DEFAULT);

                // Update the password.
                if($this->userModel->resetPassword($data['userNewPass'], $data['token'], $data['userEmail'], $data['userName'])){
                  redirect('/users/login');
                } else {
                  die('Password reset went wrong.');
                }
              } else {
                // If this is not correct, remove the token and show errors.
                $this->userModel->removeToken($data['token']);
                $data['viewPart'] = 'error';
              }
            }
          }
        }
      } else {
        // There is nothing to reset, so show empty form.
        $data['viewPart'] = '';
        // Check if the reset form has been submitted
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
          // Process form

          // Sanitize POST Data
          $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

          // Init data for checking
          $userName = trim($_POST['userName']);
          $userEmail = trim($_POST['userEmail']);
          // Check if either the username or email are empty
          if(empty($userName) || empty($userEmail)){
            // Redirect them to the reset form with no errors, this is for security.
            redirect('/users/resetPassword');
          } elseif($this->userModel->findByUsernameAndEmail($userName, $userEmail)){
            // If the user and email is found, sent them the email and add the token to their data.
            $this->userModel->sendToken($userEmail, $userName);
          }

          // email has been 'sent' if the details were correct.
          $data['viewPart'] = 'sent';

          // sleep a few seconds to make it look like the mail has been sent.
          sleep(3);
        }
      }
      // Render the view.
      $this->render('users/resetPassword', $data);
    }

    /*
     * requestData()
     *
     * this function exists to get all data in this application. This is to comply with the GPDR in the EU.
     * Please update this function when you add more userdata to this application.
     */
    public function requestData(){
      // Check if the user is logged in.
      if(!isLoggedIn()){
        redirect('/users/login');
      }

      // Initialize $data array
      $data = array(
        'title' => 'Request your User Data - '.APP_NAME,
        'userData' => $this->userModel->fetchAllUserData($_SESSION['userId']),
      );

      // Render the view
      $this->render('users/requestData', $data);
    }
  }