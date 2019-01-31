<div class="row">
  <div class="col-md-6 mx-auto">
    <div class="card card-body bg-light mt-5 border-dark">
      <h2>Change your current password</h2>
      <p>Please fill out this form to change your password.</p>
      <form action="<?=URL_ROOT;?>/users/changePassword" method="POST">
        <div class="form-group">
          <label for="userCurrentPass">Current password:<sup>*</sup></label>
          <input type="password" class="form-control <?php echo (!empty($data['userCurrentPassError'])) ? 'is-invalid' : '' ?>"
                 name="userCurrentPass" id="userCurrentPass" value="<?=$data['userCurrentPass'];?>" autofocus>
          <span class="invalid-feedback"><?=$data['userCurrentPassError'];?></span>
        </div>
        <div class="form-group">
          <label for="userNewPass">New password:<sup>*</sup></label>
          <input type=password class="form-control <?php echo (!empty($data['userNewPassError'])) ? 'is-invalid' : '' ?>"
                 name="userNewPass" id="userNewPass" value="<?=$data['userNewPass'];?>">
          <span class="invalid-feedback"><?=$data['userNewPassError'];?></span>
        </div>
        <div class="form-group">
          <label for="userNewPassConfirm">Repeat new password:<sup>*</sup></label>
          <input type="password" class="form-control <?php echo (!empty($data['userNewPassConfirmError'])) ? 'is-invalid' : '' ?>"
                 name="userNewPassConfirm" id="userNewPassConfirm" value="<?=$data['userNewPassConfirm'];?>">
          <span class="invalid-feedback"><?=$data['userNewPassConfirmError'];?></span>
        </div>

        <div class="row">
          <div class="col">
            <?=actionLink('/users', 'Return to settings', 'btn btn-outline-primary btn-block mt-3');?>
          </div>
          <div class="col">
            <input type="submit" class="btn btn-success btn-block mt-3"" value="Change your password">
          </div>
        </div>
      </form>
    </div>
  </div>
</div>