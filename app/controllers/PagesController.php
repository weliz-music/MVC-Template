<?php
  /*
   * Pages Controller.
   *
   * This controller handles every request after URL/pages/*.
   *
   * This controller exists for displaying simple and preferably static content.
   */
  class Pages extends Controller {
    public function __construct(){
    
    }
    
    public function index(){
      $data = array(
        'title' => 'Home - '.APP_NAME,
      );
      $this->render('Pages/index', $data);
    }
  
    public function about(){
      $data = array(
        'title' => 'About Us - '. APP_NAME,
      );
      $this->render('Pages/about', $data);
    }

    public function privacy(){
      $data = array(
        'title' =>'Privacy notice - '. APP_NAME,
      );
      $this->render('pages/privacy', $data);
    }
  }