<div class="row">
  <div class="col-md-6 mx-auto">
    <div class="card card-body bg-light mt-5">
      <h2>Create new account</h2>
      <p>Please fill out this form to register your account for this application.</p>
      <form action="<?=URL_ROOT;?>/users/register" method="POST">
        <div class="form-group">
          <label for="userName">Username:<sup>*</sup></label>
          <input type="text" class="form-control <?php echo (!empty($data['userNameError'])) ? 'is-invalid' : '' ?>"
                 name="userName" id="userName" value="<?=$data['userName'];?>" autofocus>
          <span class="invalid-feedback"><?=$data['userNameError'];?></span>
        </div>
        <div class="form-group">
          <label for="userEmail">Email:<sup>*</sup><br>
            <small class="text-danger">Use a real email address, otherwise you won't be able to reset your password!</small>
          </label>
          <input type="email" class="form-control <?php echo (!empty($data['userEmailError'])) ? 'is-invalid' : '' ?>"
                 name="userEmail" id="userEmail" value="<?=$data['userEmail'];?>">
          <span class="invalid-feedback"><?=$data['userEmailError'];?></span>
        </div>
        <div class="form-group">
          <label for="userPass">Password:<sup>*</sup></label>
          <input type="password" class="form-control <?php echo (!empty($data['userPassError'])) ? 'is-invalid' : '' ?>"
                 name="userPass" id="userPass" value="<?=$data['userPass'];?>">
          <span class="invalid-feedback"><?=$data['userPassError'];?></span>
        </div>
        <div class="form-group">
          <label for="userPassConfirm">Repeat Password:<sup>*</sup></label>
          <input type="password" class="form-control <?php echo (!empty($data['userPassConfirmError'])) ? 'is-invalid' : '' ?>"
                 name="userPassConfirm" id="userPassConfirm" value="<?=$data['userPassConfirm'];?>">
          <span class="invalid-feedback"><?=$data['userPassConfirmError'];?></span>
        </div>
        <div class="form-group">
          <label for="userPrivacy">Type 'yes' to confirm that you have read and accept the <?=actionLink('/pages/privacy', 'Privacy Policy', '', '_blank');?>:<sup>*</sup></label>
          <input type="text" class="form-control <?php echo (!empty($data['userPrivacyError'])) ? 'is-invalid' : '' ?>"
                 name="userPrivacy" id="userPrivacy" value="<?=$data['userPrivacy'];?>">
          <span class="invalid-feedback"><?=$data['userPrivacyError'];?></span>
        </div>

        <div class="row">
          <div class="col">
            <input type="submit" class="btn btn-success btn-block" value="Register">
          </div>
          <div class="col">
            <?=actionLink('/users/login', 'Existing account?', 'btn btn-primary btn-block');?>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>