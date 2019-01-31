<?php if(empty($data['viewPart'])): ?>
<div class="row">
  <div class="col-md-6 mx-auto">
    <div class="card card-body bg-light mt-5">
      <?=flash('register_success');?>
      <?=flash('logout_success');?>
      <?=flash('userDeleteSuccess');?>
      <?=flash('resetPasswordSuccess');?>
      <h2>Login</h2>
      <p>Please fill out your credentials</p>
      <form action="<?=URL_ROOT;?>/users/login" method="POST">
        <div class="form-group">
          <label for="userLogin">Username or Email address:<sup>*</sup></label>
          <input type="text" class="form-control <?php echo (!empty($data['userLoginError'])) ? 'is-invalid' : '' ?>"
                 name="userLogin" id="userLogin" value="<?=$data['userLogin'];?>" autofocus>
          <span class="invalid-feedback"><?=$data['userLoginError'];?></span>
        </div>
        <div class="form-group">
          <label for="userPass">Password:<sup>*</sup></label>
          <input type="password" class="form-control <?php echo (!empty($data['userPassError'])) ? 'is-invalid' : '' ?>"
                 name="userPass" id="userPass" value="<?=$data['userPass'];?>">
          <span class="invalid-feedback"><?=$data['userPassError'];?></span>
        </div>
        <div class="row">
          <div class="col">
            <input type="submit" class="btn btn-success btn-block" value="Login">
          </div>
          <div class="col">
            <?=actionLink('/users/register', 'No account yet?', 'btn btn-primary btn-block');?>
          </div>
        </div>
        <?=actionLink('/users/resetPassword', 'Forgot password?', 'btn btn-outline-secondary btn-block mt-3');?>
      </form>
    </div>
  </div>
</div>
<?php elseif($data['viewPart'] == 'noActivation'): ?>
  <div class="row">
    <div class="col-md-6 mx-auto">
      <div class="card card-body bg-light border-danger mt-5">
        <h2>Account not activated or suspended!</h2>
        <p>
          The account that you are trying to use has not been activated or has been suspended.
        </p>
        <p>
          If you think this error is incorrect, please
          contact us at <a href="mailto:<?=EMAIL_ADDR;?>?subject=Activation%20%20or%20other%20issues."><?=EMAIL_ADDR;?></a>
        </p>
        <p>Click <?=actionLink('/users/login', 'here');?> to return to the login page.</p>
      </div>
    </div>
  </div>
<?php endif; ?>