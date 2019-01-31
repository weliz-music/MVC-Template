<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- Load in core bootstrap stylesheet. -->
    <link rel="stylesheet" href="<?=URL_ROOT;?>/public/css/bootstrap.min.css">
    <!-- Load in custom stylesheet. -->
    <link rel="stylesheet" href="<?=URL_ROOT;?>/public/css/custom.css">
    <title><?=$data['title'];?></title>
  </head>
  <body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-secondary">
    <div class="container">
      <a class="navbar-brand" href="<?=URL_ROOT;?>/"><?=APP_NAME;?></a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar" aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbar">
        <ul class="navbar-nav mr-auto">
          <?=navLink('/', 'Home', TRUE);?>
          <?=navLink('/pages/about', 'About Us', TRUE);?>
          <?=navLink('/pages/privacy', 'Privacy notice', TRUE);?>
        </ul>
        <ul class="navbar-nav ml-auto">
          <?php if(isset($_SESSION['userId'])) : ?>
            <?=navLink('/users', $_SESSION['userName'], TRUE);?>
            <?=navLink('/users/logout', 'Logout');?>
          <?php else : ?>
            <?=navLink('/users/register', 'Register', TRUE);?>
            <?=navLink('/users/login', 'Login', TRUE);?>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </nav>

  <div class="container">
    <!-- Content comes below -->
    <?php require_once '../app/views/'.$view.'.php'; ?>
    <!-- End of content -->
  </div>

  <!-- Load in jQuery -->
  <script src="<?=URL_ROOT;?>/public/js/jquery.min.js"></script>
  <!-- Load in popper.js -->
  <script src="<?=URL_ROOT;?>/public/js/popper.min.js"></script>
  <!-- Load in Bootstrap.min.js -->
  <script src="<?=URL_ROOT;?>/public/js/bootstrap.min.js"></script>
  <!-- Load in custom javascript -->
  <script src="<?=URL_ROOT;?>/public/js/custom.js"></script>

  </body>
</html>
