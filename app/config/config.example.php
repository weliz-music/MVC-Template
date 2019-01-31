<?php
  /*
   * Database constants.
   *
   * These constants are used for connecting with the database.
   */
  define('DB_HOST', 'localhost');
  define('DB_USER', 'dbUser');
  define('DB_PASS', 'dbPass');
  define('DB_NAME', 'dbName');

  /*
   * ROOT constants.
   *
   * These constants are used from within the app for various inclusions and more.
   *
   * EG: require_once APPROOT.'/controllers/PagesController.php';
   */
  define('APP_ROOT', dirname(dirname(__FILE__)));
  define('URL_ROOT', 'https://example.com'); //Full path to your website eg https://example.com

  /*
   * App constants.
   *
   * These constants are used for everything in this template. From the navigation menu to the mailing system.
   */
  define('APP_NAME', 'appName');
  define('APP_VERSION', 'appVer');
  define('APP_AUTHOR', 'appAuth');
  define('APP_URL', 'appUrl');
  define('APP_LOCATION', 'appLocation');

  /*
   * Debug value, this value is used for error checking when developing. Put this on FALSE when you thing you're done
   * with developing.
   */
  define('APP_DEBUG', TRUE);

  /*
   * EMAIL constants.
   *
   * These constants are used for the email functionality of this MVC Template.
   */
  define('EMAIL_HOST', 'smtp.gmail.com');
  define('EMAIL_PORT', 587);
  define('EMAIL_ADDR', 'yourEmail@gmail.com');
  define('EMAIL_PASS', 'emailPass');