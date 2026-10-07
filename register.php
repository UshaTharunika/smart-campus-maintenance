<?php require 'includes/auth.php';$err='';
if($_SERVER['REQUEST_METHOD']==='POST'){
 $n=trim($_POST['name']??'');$em=trim($_POST['email']??'');$pw=$_POST['password']??'';$ph=trim($_POST['phone']??'');$d=trim($_POST['department']??'');
 if(!$n||!$em||!$pw||!$d)$err='All fields are required.';
 elseif(!filter_var($em,FILTER_VALIDATE_EMAIL))$err='Enter a valid email address.';
 elseif(strlen($pw)<6)$err='Password must be at least 6 characters.';
 else{$c=$db->prepare('SELECT id FROM users WHERE email=?');$c->bind_param('s',$em);$c->execute();
  if($c->get_result()->num_rows)$err='This email is already registered.';
  else{$h=password_hash($pw,PASSWORD_DEFAULT);$i=$db->prepare("INSERT INTO users(name,email,password,phone,department,role) VALUES(?,?,?,?,?,'user')");
   $i->bind_param('sssss',$n,$em,$h,$ph,$d);$i->execute();header('Location: login.php');exit;}}}
$title='Register';include 'includes/header.php';?>
<div class="row justify-content-center"><div class="col-md-6"><div class="card p-4"><h3 class="mb-3">Create Account</h3>
<?php if($err):?><div class="alert alert-danger"><?=e($err)?></div><?php endif;?>
<form method="post"><input class="form-control mb-3" name="name" placeholder="Full name" required>
<input class="form-control mb-3" type="email" name="email" placeholder="Email" required>
<input class="form-control mb-3" name="phone" placeholder="Phone">
<input class="form-control mb-3" name="department" placeholder="Department" required>
<input class="form-control mb-3" type="password" name="password" placeholder="Password (min 6)" required>
<button class="btn btn-primary w-100">Register</button></form></div></div></div>
<?php include 'includes/footer.php';?>
