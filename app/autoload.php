<?php
  // check if config exists.
  if(file_exists('../app/config/config.php')) {
    // Load config
    require_once 'config/config.php';
  } else {
    // Display error
    die('No config file found. Please check your example file for instructions!');
  }

  // Start session
  session_start();


  // Load in functions that can be used everywhere
  require_once 'libraries/globalFunctions.php';

  // Autoload all needed Libraries
  spl_autoload_register(function($className){
    require_once 'libraries/'.$className.'.php';
  });
