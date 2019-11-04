<div class="row">
  <div class="col-md-6 mx-auto">
    <div class="card card-body bg-light mt-5">
      <h2>Authenticate</h2>
      <p>We have sent you an email with an authentication code. Please enter this code below.</p>
      <form action="<?=URL_ROOT; ?>/users/auth" method="POST">
        <div class="form-group">
          <label for="userName">Username:</label>
          <input type="text" class="form-control" disabled value="<?=ucfirst($_SESSION['userName']);?>">
        </div>
        <div class="form-group">
          <label for="auth">Authentication code:<sup>*</sup></label>
          <input type="text" class="form-control <?php echo (!empty($data['authError'])) ? 'is-invalid' : '' ?>"
                 name="auth" id="userName" value="<?=$data['auth']; ?>" autofocus>
          <span class="invalid-feedback"><?=$data['authError']; ?></span>
        </div>
        <div class="row">
          <div class="col">
            <input type="submit" class="btn btn-success btn-block" value="Authenticate">
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
