<div class="row">
  <div class="col-lg-6 mx-auto">
    <div class="card card-body bg-light mt-5 border-primary">
      <h2>Change your current username</h2>
      <p>Please fill out this form to change your username</p>
      <form action="<?=URL_ROOT;?>/users/changeUserName" method="POST">
        <div class="form-group">
          <label for="currUserName">Current username:</label>
          <input type="text" class="form-control" value="<?=$_SESSION['userName'];?>" disabled>
        </div>
        <div class="form-group">
          <label for="userName">New username:<sup>*</sup></label>
          <input type="text" class="form-control <?php echo (!empty($data['userNameError'])) ? 'is-invalid' : '' ?>"
                 name="userName" id="userName" value="<?=$data['userName'];?>" autofocus>
          <span class="invalid-feedback"><?=$data['userNameError'];?></span>
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
            <input type="submit" class="btn btn-success btn-block mt-3"" value="Change your username">
          </div>
        </div>
      </form>
    </div>
  </div>
</div>