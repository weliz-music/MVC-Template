<div class="row">
  <div class="col-lg-6 mx-auto">
    <div class="card card-body bg-light mt-5 border-primary">
      <h2>Change your current email address</h2>
      <p>Please fill out this form to change your email address</p>
      <form action="<?=URL_ROOT;?>/users/changeEmail" method="POST">
        <div class="form-group">
          <label for="currEmail">Current email address:</label>
          <input type="text" class="form-control" value="<?=$_SESSION['userEmail'];?>" disabled>
        </div>
        <div class="form-group">
          <label for="userNewEmail">New email address:<sup>*</sup></label>
          <input type="email" class="form-control <?php echo (!empty($data['userNewEmailError'])) ? 'is-invalid' : '' ?>"
                 name="userNewEmail" id="userNewEmail" value="<?=$data['userNewEmail'];?>" autofocus>
          <span class="invalid-feedback"><?=$data['userNewEmailError'];?></span>
        </div>
        <div class="form-group">
          <label for="userPass">Your password:<sup>*</sup></label>
          <input type="password" class="form-control <?php echo (!empty($data['userPassError'])) ? 'is-invalid' : '' ?>"
                 name="userPass" id="userPass" value="<?=$data['userPass'];?>" autofocus>
          <span class="invalid-feedback"><?=$data['userPassError'];?></span>
        </div>
        <div class="row">
          <div class="col">
            <?=actionLink('/users', 'Return to settings', 'btn btn-outline-primary btn-block mt-3');?>
          </div>
          <div class="col">
            <input type="submit" class="btn btn-success btn-block mt-3" value="Change your email address">
          </div>
        </div>
      </form>
    </div>
  </div>
</div>