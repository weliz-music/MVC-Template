<?php if($data['viewPart'] == 'activate'): ?>
  <div class="row">
    <div class="col-md-6 mx-auto">
      <div class="card card-body bg-light mt-5">
        <h2>Activate account</h2>
        <p>Please fill in the form below to activate your user account.</p>
        <form action="<?=URL_ROOT;?>/users/activate?name=<?=$data['userName'];?>" method="POST">
          <div class="form-group">
            <label for="userName">Username:</label>
            <input type="text" class="form-control" value="<?=$data['userName'];?>" disabled>
          </div>
          <div class="form-group">
            <label for="userPass">Password:<sup>*</sup></label>
            <input type="password" class="form-control <?php echo (!empty($data['userPassError'])) ? 'is-invalid' : '' ?>"
                   name="userPass" id="userPass" value="<?=$data['userPass'];?>">
            <span class="invalid-feedback"><?=$data['userPassError'];?></span>
          </div>
          <div class="row">
            <div class="col">
              <input type="submit" class="btn btn-outline-primary btn-block" value="Activate your account">
            </div>
        </form>
      </div>
    </div>
  </div>
<?php elseif($data['viewPart'] == 'success'): ?>
  <div class="row">
    <div class="col-md-6 mx-auto">
      <div class="card card-body bg-light border-success mt-5">
        <h2>Activation succeeded!</h2>
        <p>
          Your account is now activated and you are able to use this application.
        </p>
        <p>Click <a href="<?=URL_ROOT;?>/users/login">here</a> to return to the login page.</p>
      </div>
    </div>
  </div>
<?php elseif($data['viewPart'] == 'error'): ?>
  <div class="row">
    <div class="col-md-6 mx-auto">
      <div class="card card-body bg-light border-danger mt-5">
        <h2>Activation failed!</h2>
        <p>
          The username you are trying to activate is already activated or has been suspended.
        </p>
        <p>
          If you think this error is incorrect and you are sure that you have filled in all details correctly, please
          contact us at <a href="mailto:<?=EMAIL_ADDR;?>?subject=Activation%20%20or%20other%20issues."><?=EMAIL_ADDR;?></a>
        </p>
        <p>
          If you still want to activate your account, please try again with the instructions that were sent by mail previously.
        </p>
        <p>Click <a href="<?=URL_ROOT;?>/users/login">here</a> to return to the login page.</p>
      </div>
    </div>
  </div>
<?php endif; ?>