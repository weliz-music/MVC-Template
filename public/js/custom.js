function passToggle(){
  var pass = document.getElementById("userPass");
  var img = document.getElementById("pw-toggle");
  var root = '<?=URL_ROOT;?>';
  //alert(root);
  if (pass.type === "password") {
    pass.type = "text";
    img.src = "../public/assets/eye.png";
  } else {
    pass.type = "password";
    img.src = "../public/assets/hide.png";
  }
}