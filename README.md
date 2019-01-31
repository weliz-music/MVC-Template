# MVC Template by @LiquitoX
This template is made by @LiquitoX (Me) to eliminate the need to create a framework 
for every site I'm going to build, or that I'm currently building. I've made this 
to form the building blocks for my upcoming projects and how to improve this MVC 
template to be better and better in the future.

## License
I don't have a license in mind. But when you use this framework/template, I'd like a 
mention that you are using this framework. It is forbidden to use this framework for 
commercial purposes, unless you have written permission from me.

## About this framework
This framework is very bare-bones. It only comes with some pretty basic things you
will probably need when you start your new project. I've built this with the mindset
that people who want to use this, only want a bit more than the basic things, and 
like it the most when they program mainly  from scratch. This is by no means a 
professional framework, nor am I trying to be one. I just like to code the most of 
what I do, myself. The things you can expect from this framework:
- Simple PagesController, for serving mostly static pages.
- Simple ErrorsController, for all url's that are invalid. 
- Complete UsersController, complete with back-end mail system (Provided by
PHPMailer).

## Usage
You can use this framework in any project you like, unless the project has commercial
purposes (Included, but not limited to: Making profits). 

## 1.) How to use this framework
This framework is by no means perfect and you will still have a lot to do with your
own hands. But I think this is a more fun way than doing eveything with a command-line
tool like 'npm' or similar. 

### 1.1) Conventions used

#### 1.1.1) Naming
In this framework I use a rather simple naming convention. Controllers have 
`Controller` in their filename, and Models have `Model` in their filename. New 
controllers should be named `myNewController.php` and a new model should be named
`myNewModel.php`

An example file structure would be:
```
Project/
  app/
    controllers/
      ErrorsController.php
      PagesController.php
      UsersController.php
    models/
      UserModel.php
    views/
      Errors/
        index.php
      Pages/
        index.php
        about.php
      Users/
        login.php
        register.php
```
Please note that the app will scream at you if you fail to follow this convention.

#### 1.1.2) Pages and functions
I wanted to keep things uniform when developing apps in this framework, so please 
keep the following things in mind.
- Controllers should only contain functions that can display actual views. (EG: 
`PagesController.php` can contain `function index()`. But they cannot contain
supporting functions like `isLoggedIn()` in their root functions.)
- Models should have all functions __RELATED__ to the used controller. (EG: 
`PageModel.php` should have supporting functions for that controller.)

See the code below for a better example:

```
<?php // PagesController.php
  class Pages extends Controller{
    public function __construct(){
      // This will load /app/models/PageModel.php
      $this->pageModel = $this->model('Page');
    }
    
    public function index(){ // Page that can be visisted (/pages/index)
      $data = $this->pageModel->createData();
      $this->render('pages/index');
    }
  }
?>
```
```
<?php // PageModel.php
  class Page{
    public function createData(){
      return array('Title' => 'Welcome');
    }
  }
?>
```
### 1.2) Built-in functions
These functions are made to aid me in better development and for keeping things
consistent across projects. These functions can be found in 
`/app/libraries/globalFunctions.php` and can be extended with your own functions.
These functions can be run from anywhere, in Controllers, Models and even your Views.
I have the following functions for now:
- navLink()
  - This function is for creating navigation links in the navBar. 
- actionLink()
  - this function is for creating links anywhere in your application.
- redirect()
  - This should be self-explanatory.
- isLoggedIn()
  - I'm not even going to bother explaining this.
- isAdmin()
  - This also, should be obvious.


### 1.3) Changing the config file
Please copy the example config file to `/app/config/config.php`. This MVC will scream if
you don't do this, as it has no configuration options to use. Don't be worried, as the 
config file you made won't end up in the repository, unless you remove the `/app/config/config.php`
from `.gitignore`. The following values should be changed when you have copied this file:
- DB_HOST
- DB_USER
- DB_PASS
- DB_NAME
- URL_ROOT
- APP_NAME
- APP_AUTHOR
- APP_URL
- APP_LOCATION
- EMAIL_ADDR
- EMAIL_PASS

If you use something other than Gmail, please change the following values too:
- EMAIL_HOST
- EMAIL_PORT

### 1.4) Adding a new Controller and a new Model
I'll use 'tasks' as controller name and model name in the upcoming examples. <br><br>
You can add a new controller by creating a new php class with the following contents in 
`/app/controllers`:<br>
Filename: `TasksController.php`

```
<?php
  /*
  * Tasks Controller.
  *
  * This Controller does x and y
  */
  class Tasks extends Controller {
  private $taskModel;
    public function __construct(){
    }
    
    public function index(){
      $data = array('title' => 'Tasks - Index.');
      
      $this->render('tasks/index');
    }
  }
```
The code above will be shown on `example.com/tasks`. <br> 
Note how we are already rendering a view which does not exist. This does not matter for 
now. <br>

When you have added the controller, you should add the index view right after.
- Create a folder named `Tasks`  in `/app/views`.
- Create a new php file named `index.php` in the `Tasks` folder in the previous step.
- Add HTML code to your liking.

You now have a small working Controller with one simple view.

If you want some extended functionality within this Controller, you should add a model. You 
can do this by creating a file named `TaskModel.php` in `/app/models/`. You should also add
the model in your `__construct` function in your Controller. I guess that you know how you
should do this, otherwise you would not use this framework in the first place. But if you 
don't know to do this, add `$this->taskModel = $this->model('task');` to your constructor.
It should have the following contents before you continue:
Filename: `TaskModel.php`
```
<?php
  class Task{
    // If the class should have access to an database, uncomment the things below.
    //private $db
    //public function __construct(){
    //  $this->db = new Database();
    //}
    
    // Your own supporting code.
  }
```

That is pretty much the gist of how you can use this MVC. If I've forgotten some things, 
don't hesitate to shoot me a message.