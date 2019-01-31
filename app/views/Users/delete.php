<div class="row">
  <div class="col-lg-6 mx-auto">
    <div class="card card-body bg-light mt-5 border-danger">
      <h2>Delete your user account</h2>
      <p>Please fill out delete your account from this website.</p>
      <form action="<?=URL_ROOT;?>/users/delete" method="POST">
        <div class="form-group">
          <label for="userEmail">Your email address:<sup>*</sup></label>
          <input type="email" class="form-control <?php echo (!empty($data['userEmailError'])) ? 'is-invalid' : '' ?>"
                 name="userEmail" id="userEmail" value="<?=$data['userEmail'];?>" autofocus>
          <span class="invalid-feedback"><?=$data['userEmailError'];?></span>
        </div>
        <div class="form-group">
          <label for="userPass">Your password:<sup>*</sup></label>
          <input type="password" class="form-control <?php echo (!empty($data['userPassError'])) ? 'is-invalid' : '' ?>"
                 name="userPass" id="userPass" value="<?=$data['userPass'];?>" autofocus>
          <span class="invalid-feedback"><?=$data['userPassError'];?></span>
        </div>
        <div class="form-group">
          <label for="userConfirm">Type 'yes' to confirm that you want to delete your account:<sup>*</sup></label>
          <input type="text" class="form-control <?php echo (!empty($data['userConfirmError'])) ? 'is-invalid' : '' ?>"
                 name="userConfirm" id="userConfirm" value="<?=$data['userConfirm'];?>" autofocus>
          <span class="invalid-feedback"><?=$data['userConfirmError'];?></span>
        </div>
        <div class="row">
          <div class="col">
            <?=actionLink('/users', 'Return to settings', 'btn btn-outline-primary btn-block mt-3');?>
          </div>
          <div class="col">
            <input type="submit" class="btn btn-outline-danger btn-block mt-3" value="Delete account">
          </div>
        </div>
      </form>
    </div>
  </div>
</div>