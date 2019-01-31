
<?php if(empty($data['viewPart'])): ?>
  <div class="row">
    <div class="col-md-6 mx-auto">
      <div class="card card-body bg-light mt-5">
        <h2>Reset password</h2>
        <p>Please fill in the form below to reset your password.</p>
        <form action="<?=URL_ROOT;?>/users/resetPassword" method="POST">
          <div class="form-group">
            <label for="userName">Username:<sup>*</sup></label>
            <input type="text" class="form-control <?php echo (!empty($data['userNameError'])) ? 'is-invalid' : '' ?>"
                   name="userName" id="userName" value="<?=$data['userName'];?>" autofocus>
            <span class="invalid-feedback"><?=$data['userNameError'];?></span>
          </div>
          <div class="form-group">
            <label for="userEmail">Email address:<sup>*</sup></label>
            <input type="email" class="form-control <?php echo (!empty($data['userEmailError'])) ? 'is-invalid' : '' ?>"
                   name="userEmail" id="userEmail" value="<?=$data['userEmail'];?>">
            <span class="invalid-feedback"><?=$data['userEmailError'];?></span>
          </div>
          <div class="row">
            <div class="col">
              <input type="submit" class="btn btn-outline-danger btn-block" value="Reset Password">
            </div>
        </form>
      </div>
    </div>
  </div>
<?php elseif($data['viewPart'] == 'sent'): ?>
  <div class="row">
    <div class="col-md-6 mx-auto">
      <div class="card card-body bg-light border-success mt-5">
        <h2>Reset mail has been sent.</h2>
        <p>
          We have sent you an email with your reset token if your username and email correspond to the ones in our
          system.
        </p>
        <p>
          Please check your spam if you can't find the email in your mailbox. Please note, it can take several minutes
          for the email to arrive.
        </p>
        <p>Click <a href="<?=URL_ROOT;?>/users/login">here</a> to return to the login page.</p>
      </div>
    </div>
  </div>
<?php elseif($data['viewPart'] == 'token'): ?>
  <div class="row">
    <div class="col-md-6 mx-auto">
      <div class="card card-body bg-light mt-5">
        <h2>Reset password</h2>
        <p>Please fill in the form below to reset your password.</p>
        <form action="<?=URL_ROOT;?>/users/resetPassword?token=<?=$data['token'];?>" method="POST">
          <div class="form-group">
            <label for="token">Reset Token:</label>
            <input type="text" class="form-control" value="<?=$data['token'];?>" disabled>
          </div>
          <div class="form-group">
            <label for="userName">Username:<sup>*</sup></label>
            <input type="text" class="form-control <?php echo (!empty($data['userNameError'])) ? 'is-invalid' : '' ?>"
                   name="userName" id="userName" value="<?=$data['userName'];?>" autofocus>
            <span class="invalid-feedback"><?=$data['userNameError'];?></span>
          </div>
          <div class="form-group">
            <label for="userEmail">Email address:<sup>*</sup></label>
            <input type="email" class="form-control <?php echo (!empty($data['userEmailError'])) ? 'is-invalid' : '' ?>"
                   name="userEmail" id="userEmail" value="<?=$data['userEmail'];?>">
            <span class="invalid-feedback"><?=$data['userEmailError'];?></span>
          </div>
          <div class="form-group">
            <label for="userNewPass">New password:<sup>*</sup></label>
            <input type="password" class="form-control <?php echo (!empty($data['userNewPassError'])) ? 'is-invalid' : '' ?>"
                   name="userNewPass" id="userNewPass" value="<?=$data['userNewPass'];?>">
            <span class="invalid-feedback"><?=$data['userNewPassError'];?></span>
          </div>
          <div class="form-group">
            <label for="userNewPassConfirm">Confirm password:<sup>*</sup></label>
            <input type="password" class="form-control <?php echo (!empty($data['userNewPassConfirmError'])) ? 'is-invalid' : '' ?>"
                   name="userNewPassConfirm" id="userNewPassConfirm" value="<?=$data['userNewPassConfirm'];?>">
            <span class="invalid-feedback"><?=$data['userNewPassConfirmError'];?></span>
          </div>
          <div class="row">
            <div class="col">
              <input type="submit" class="btn btn-outline-danger btn-block" value="Reset Password">
            </div>
        </form>
      </div>
    </div>
  </div>
<?php elseif($data['viewPart'] == 'error'): ?>
  <div class="row">
    <div class="col-md-6 mx-auto">
      <div class="card card-body bg-light border-danger mt-5">
        <h2>Password reset failed!</h2>
        <p>
          The used reset token is invalid or has expired. There is also the possibility you have entered the wrong
          username or email address.
        </p>
        <p>
          If you indeed made a mistake, we have sent you an email with further steps you can take to still reset your
          password.
        </p>
        <p>
          If you think this error is incorrect and you are sure that you have filled in all details correctly, please
          contact us at <a href="mailto:<?=EMAIL_ADDR;?>?subject=Password%20reset%20or%20other%20issues."><?=EMAIL_ADDR;?></a>
        </p>
        <p>
          If you still want to reset your password, please try again <a href="<?=URL_ROOT;?>/users/resetPassword">here</a>.
        </p>
        <p>Click <a href="<?=URL_ROOT;?>/users/login">here</a> to return to the login page.</p>
      </div>
    </div>
  </div>
<?php endif; ?>