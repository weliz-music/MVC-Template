<div class="jumbotron jumbotron-fluid">
  <div class="container">
    <h1 class="display-6">Userprofile</h1>
    <p class="lead">
      <?=$data['description'];?>
    </p>
  </div>
</div>
<div class="row">
  <div class="col-lg-8 mx-auto">
    <?=flash('userNameChangeSuccess');?>
    <?=flash('userEmailChangeSuccess');?>
    <?=flash('passwordChangeSuccess');?>
    <?=flash('message');?>
    <label>Current username: </label>
    <div class="input-group mb-3">
      <input type="text" class="form-control disabled border-primary" value="<?=$data['userName'];?>" disabled>
      <div class="input-group-append">
        <?=actionLink('/users/changeUserName', 'Change Username', 'btn btn-outline-primary end-btn');?>
      </div>
    </div>
    <label>Current email address: </label>
    <div class="input-group mb-3">
      <input type="text" class="form-control disabled border-primary" value="<?=$data['userEmail'];?>" disabled>
      <div class="input-group-append">
        <?=actionLink('/users/changeEmail', 'Change Email', 'btn btn-outline-primary end-btn');?>
      </div>
    </div>
    <label>Current login: </label>
    <div class="input-group mb-3">
      <input type="text" class="form-control disabled border-success" value="<?=$data['ip'];?>" disabled>
      <div class="input-group-append">
        <?=actionLink('/users/loginHistory', 'Check login history', 'btn btn-outline-success end-btn');?>
      </div>
    </div>
    <label>Current password: </label>
    <div class="input-group mb-3">
      <input type="password" class="form-control disabled border-dark" value="Haha, you really think I would put useful data here?" disabled>
      <div class="input-group-append">
        <?=actionLink('/users/changePassword', 'Change Password', 'btn btn-outline-dark end-btn');?>
      </div>
    </div>
    <label>Other options:</label>
    <div class="row">
      <div class="col-lg-4 mt-3">
        <?=actionLink('/users/settings', 'Change your settings', 'btn btn-outline-warning btn-block');?>
      </div>
      <div class="col-lg-4 mt-3">
        <?=actionLink('/users/requestData', 'Request your data', 'btn btn-outline-info btn-block');?>
      </div>
      <div class="col-lg-4 mt-3">
        <?=actionLink('/users/delete', 'Delete account', 'btn btn-outline-danger btn-block');?>
      </div>
    </div>
  </div>
</div>
