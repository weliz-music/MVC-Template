<?php
  /*
   * Base controller.
   * This loads the models and views
   */
  class Controller {
    /*
     * model()
     *
     * This function selects and instantiates the selected model.
     *
     * Usage: __construct(){$this->model = model('Users');}
     */
    public function model($model) {
      // require model file
      require_once '../app/models/'.$model.'Model.php';
      
      // instantiate model
      return new $model();
    }
    
    /*
     * render();
     *
     * This function renders a given view. If the view does not exist, the page will return a 404 error.
     *
     * Usage(In Controller):
     *    $this->render('user/index');
     */
    public function render($viewFile, $data = array()) {
      $viewFile = ucfirst($viewFile);
      $view = 'Errors/index';
      // Check if the view exists
      if(file_exists('../app/views/'.$viewFile.'.php')) {
        $view = ucfirst($viewFile);
      }
      require_once '../app/views/_layout.php';
    }
  }