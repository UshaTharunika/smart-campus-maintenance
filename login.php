<?php require 'includes/auth.php';$err=isset($_GET['error'])?'Please log in with an authorised account.':'';
if($_SERVER['REQUEST_METHOD']==='POST'){
 $email=trim($_POST['email']??'');$pw=$_POST['password']??'';
 if($email===''||$pw===''){$err='Please fill in all fields.';}else{
 $st=$db->prepare('SELECT id,name,password,role FROM users WHERE email=?');$st->bind_param('s',$email);$st->execute();$u=$st->get_result()->fetch_assoc();
 if($u&&password_verify($pw,$u['password'])){session_regenerate_id(true);$_SESSION=['uid'=>$u['id'],'name'=>$u['name'],'role'=>$u['role']];
  header('Location: '.BASE.$u['role'].'/dashboard.php');exit;}
 $err='Invalid email or password.';}}
$title='Login';include 'includes/header.php';?>
<div class="row justify-content-center"><div class="col-md-5"><div class="card p-4"><h3 class="mb-3">Login</h3>
<?php if($err):?><div class="alert alert-danger"><?=e($err)?></div><?php endif;?>
<form method="post"><input class="form-control mb-3" type="email" name="email" placeholder="Email" required>
<input class="form-control mb-3" type="password" name="password" placeholder="Password" required>
<button class="btn btn-primary w-100">Login</button></form>
<p class="mt-3 mb-0 small">New here? <a href="register.php">Register</a></p></div></div></div>
<?php include 'includes/footer.php';?>
