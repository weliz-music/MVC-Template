<?php
  class Errors extends Controller{
    public function index(){
      if(empty($GLOBALS['error'])){
        redirect('/');
      }
      $data['title'] = 'ERROR 404';
      $this->render('Errors/index', $data);
    }
  }