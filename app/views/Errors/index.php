<?php if(APP_DEBUG): ?>
  <div class="jumbotron">
    <p class="text-center border p-3 border-dark rounded">DEBUG MODE IS ON: The Controller, Model or View you requested does not exist! Please check your files accordingly!</p>
    <p class="mt-3">If you don't want to see this debug message again, please set APP_DEBUG to FALSE.</p>
  </div>
<?php else: ?>
  <div class="jumbotron">
    <h3 class="text-center">404 - Page/File not found!</h3>
  </div>
<?php endif; ?>