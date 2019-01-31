<?php
  /*
   * Core()
   *
   * This is the core of the app. This creates the url and selects the controller and method based on that url.
   *
   * Url format:
   *    URL_ROOT/controller/method/parameters
   */
  class Core {
    // If no controller is specified, "Pages" controller should be loaded.
    protected $currController = "Pages";
    // If no method/function is specified, "index" method/function should be loaded.
    protected $currMethod = "index";
    // Parameters for the pages.
    protected $params = array();


    /*
     * __construct()
     *
     * this function instantiates the url and the controller needed to display the information that is needed.
     */
    public function __construct(){
      //print_r($this->getUrl());
      $url = $this->getUrl();

      // check if the Controller is empty
      if(empty($url[0])){
        $url[0] = $this->currController;
      }
      
      // Look in controllers for the requested controller.
      if (file_exists('../app/controllers/'.ucwords($url[0]).'Controller.php')){
        // If exists, make it the controller.
        $this->currController = ucwords($url[0]);
      } else {
        // Set controller to error
        $this->currController = 'Errors';
        $GLOBALS['error'] = TRUE;
      }
      // unset $url[0]
      unset($url[0]);

      
      // Require the controller
      require_once '../app/controllers/'.$this->currController.'Controller.php';
      
      // Instantiate controller
      $this->currController = new $this->currController;
      
      // Check for second part of url for the function/method.
      if(isset($url[1])){
        // check to see if method exists in controller.
        if(method_exists($this->currController, $url[1])){
          $this->currMethod = $url[1];
        } else {
          // Set Controller to error.
          $this->currController = 'Errors';
          require_once '../app/controllers/'.$this->currController.'Controller.php';
          $this->currController = new $this->currController;
          $GLOBALS['error'] = TRUE;
        }
        // Unset $url[1]
        unset($url[1]);
      }
      
      // Get parameters
      $this->params = $url ? array_values($url) : [];
      
      // Call a callback with array of parameters.
      call_user_func_array([$this->currController, $this->currMethod], $this->params);
    }

    /*
     * getUrl()
     *
     * This function returns an array with the url components inside.
     *
     * Usage: $this->getUrl();
     */
    public function getUrl(){
      if(isset($_GET['url'])){
        $url = rtrim($_GET['url'], '/');
        $url = filter_var($url, FILTER_SANITIZE_URL);
        $url = explode('/', $url);
        return $url;
      }
    }
  }