<?php

  /*
   * navLink()
   *
   * This function is for creating links in the navbar.
   *
   * Usage: <?=navLink('/pages/index', 'Home', TRUE);?>
   */
  function navLink($location = '', $text, $beActive = FALSE){
    // Set url root.
    $root = URL_ROOT;

    // Set url to be the most like the location.
    $url = '/'.implode('/', array_slice(explode('/', explode('?', $_SERVER['REQUEST_URI'])[0]), 1, 2));

    // Check if url and location are the same, and if $beActive is set.
    if($url == $location && $beActive){
      $active = ' active';
    } else {
      $active = '';
    }

    // Return the navLink.
    return "<li class='nav-item'><a class='nav-link{$active}' href='{$root}{$location}'>{$text}</a></li>";
  }

  /*
   * actionLink()
   *
   * This function creates easy links for your views.
   *
   * Usage: <?=actionLink('/pages/index', 'Home', 'btn btn-default', '_self');?>
   */
  function actionLink($location = '', $text = 'submit', $class = '', $target = '_self'){
    // Set url root.
    $root = URL_ROOT;

    // Return the button with link
    return "<a href='{$root}{$location}' class='{$class}' target='{$target}'>{$text}</a>";
  }

  /*
   * redirect()
   *
   * This function eliminates the need to retype "header('Location: ')" each time when you want to redirect someone in
   * your models/controllers.
   *
   * Usage: redirect('/pages/index');
   */
  function redirect($location){
    header('Location: '.URL_ROOT.$location);
  }

  /*
   * isLoggedIn()
   *
   * This function checks if a user is logged in or not.
   *
   * Usage: if(isLoggedIn()){// Code}
   */
  function isLoggedIn(){
    if(isset($_SESSION['userId'])){
      return TRUE;
    } else {
      return FALSE;
    }
  }

  /*
   * isAdmin()
   *
   * this function checks if the user is an admin or not.
   *
   * Usage: if(isAdmin()){// Code}
   */
  function isAdmin(){
    if($_SESSION['userLevel'] == 'admin'){
      return TRUE;
    } else {
      return FALSE;
    }
  }


  /*
   * flash()
   *
   * This function sets messages in the controller/model, and shows the messages then in the view.
   *
   * To set the flashMessage (In the Controller/Model):
   *    flash('logoutSuccess', 'You are now logged out.', 'alert alert-success')
   *
   * To display the flashMessage (In the View):
   *     <?=flash('logoutSuccess');?>
   */
  function flash($name = '', $message = '', $class = 'alert alert-success'){
    if(!empty($name)){
      if(!empty($message) && empty($_SESSION[$name])){
        if(!empty($_SESSION[$name])){
          unset($_SESSION[$name]);
        }

        if(!empty($_SESSION[$name. '_class'])){
          unset($_SESSION[$name. '_class']);
        }

        $_SESSION[$name] = $message;
        $_SESSION[$name. '_class'] = $class;
      } elseif(empty($message) && !empty($_SESSION[$name])){
        $class = !empty($_SESSION[$name. '_class']) ? $_SESSION[$name. '_class'] : '';
        echo '
          <div class="'.$class.'" id="msg-flash" role="alert">
            '.$_SESSION[$name].'
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
          </div>';
        unset($_SESSION[$name]);
        unset($_SESSION[$name. '_class']);
      }
    }
  }