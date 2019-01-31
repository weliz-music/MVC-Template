<div class="jumbotron display-4">
  <h4>This page is for requesting your user data.</h4>
  <p class="lead">You can find all user data that we have collected on this page.</p>
</div>
<p>To use this page: Select the data you want to save, and safe this in a file to your liking.</p>

<p>Userdata:</p>
<table class="table table-bordered table-active table-hover">
  <thead class="thead-dark">
    <tr>
      <th scope="col">Username:</th>
      <th scope="col">Email:</th>
      <th scope="col">Created At:</th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td><?=$data['userData']['user']->name;?></td>
      <td><?=$data['userData']['user']->email;?></td>
      <td><?=$data['userData']['user']->created_at;?></td>
    </tr>
  </tbody>
</table>