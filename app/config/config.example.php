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
   * Debug value, this value is used for error checking when developing. Put this on FALSE when you think you're done
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

  /*
  * COOKIE constants.
  *
  * These constants are used to set and display a cookie-notice when needed.
  */
  define('COOKIE_NOTICE_ENABLED', 'true');
  define('COOKIE_NAME', 'notice');
  define('COOKIE_MESSAGE', 'This webpage uses cookies to optimize visits from users. You can click <a href="'.URL_ROOT.'/pages/acceptCookie">Accept</a> or just continue on this website.');
  /*
   * COOKIE_LOCATION deserves some more explanation. You can put 3 options in here. 'top', 'bottom' or leave it empty.
   * If you set it to top, it will "hide" the top navigation. So I would not choose this option, unless you want to
   * use it as a cookie=wall.
   * If you set it to bottom, it will be on the bottom of the page, period.
   * If you keep the value empty, it will shift the navigation menu a bit down, to fit the notice there.
   */
  define('COOKIE_LOCATION', 'bottom');