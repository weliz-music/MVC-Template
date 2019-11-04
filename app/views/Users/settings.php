<div class="jumbotron jumbotron-fluid">
  <div class="container">
    <h1 class="display-6">User Settings</h1>
    <p class="lead">
      You can change more specific settings on this page.
    </p>
  </div>
</div>
<?=flash('message');?>
<div class="container">
  <form action="<?=URL_ROOT; ?>/users/settings" method="POST" class="form">
    <?php if(isset($data['user_settings'])): ?>
      <?php foreach ($data['user_settings'] as $setting): ?>
        <div class="row">
          <div class="col-lg-10 col-8 border p-3 m-0">
            <label class="m-0"><?=$setting->description;?></label>
          </div>
          <div class="col-lg-2 col-4 border p-2 m-0">
            <select name="<?=$setting->name;?>" id="<?=$setting->name;?>" class="form-control">
              <option value="1" <?php if($setting->value){echo 'selected';}?>>On</option>
              <option value="0" <?php if(!$setting->value){echo 'selected';}?>>Off</option>
            </select>

          </div>
        </div>
      <?php endforeach; ?>
    <?php endif;?>
    <div class="row">
      <div class="col">
        <?=actionLink('/users', 'Return to settings', 'btn btn-outline-primary btn-block mt-3'); ?>
      </div>
      <div class="col">
        <input type="submit" class="btn btn-success btn-block mt-3"" value="Save your settings">
      </div>
    </div>
  </form>
</div>
