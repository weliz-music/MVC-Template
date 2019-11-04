<div class="jumbotron jumbotron-fluid">
  <div class="container">
    <h1 class="display-6">Past login history</h1>
    <p class="lead">
      Check your login history here. If something is not right, please notify info@welizmusic.com immediately.
    </p>
  </div>
</div>
<?php if(!empty($data['logins'])): ?>
  <div class="table-responsive">
    <table class="table table-bordered table-active table-hover">
      <thead class="thead-dark">
      <tr>
        <th scope="col">Ip:</th>
        <th scope="col">Country:</th>
        <th scope="col">City:</th>
        <th scope="col">Date:</th>
      </tr>
      </thead>
      <tbody>
      <?php foreach ($data['logins'] as $login): ?>
        <tr>
          <td><?=$login->ip;?></td>
          <td><?=$login->country;?></td>
          <td><?=$login->city;?></td>
          <td><?=$login->date;?></td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
<?php endif; ?>
