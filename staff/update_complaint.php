<?php require '../includes/auth.php';require_role('staff');$sid=staff_id($db);$id=(int)($_GET['id']??0);$msg='';
$st=$db->prepare('SELECT c.* FROM complaints c JOIN assignments a ON a.complaint_id=c.id WHERE c.id=? AND a.staff_id=? LIMIT 1');$st->bind_param('ii',$id,$sid);$st->execute();$c=$st->get_result()->fetch_assoc();
if(!$c){header('Location: dashboard.php');exit;}
if($_SERVER['REQUEST_METHOD']==='POST'){
 $s=$_POST['status']??'';$n=trim($_POST['notes']??'');
 if(in_array($s,['In Progress','Resolved'])&&$n!==''){
  $i=$db->prepare('INSERT INTO updates(complaint_id,staff_id,status,notes) VALUES(?,?,?,?)');$i->bind_param('iiss',$id,$sid,$s,$n);$i->execute();
  $u=$db->prepare('UPDATE complaints SET status=? WHERE id=?');$u->bind_param('si',$s,$id);$u->execute();$c['status']=$s;$msg='Update saved.';}
 else $msg='Choose a status and enter work notes.';}
$title='Update Complaint';include '../includes/header.php';?>
<div class="card p-4"><h4><?=cid($c)?> &mdash; <?=e($c['title'])?></h4><p><?=badge($c['priority'])?> <?=badge($c['status'])?><br><b>Location:</b> <?=e($c['location'])?></p><p><?=nl2br(e($c['description']))?></p>
<?php if($msg):?><div class="alert alert-info"><?=e($msg)?></div><?php endif;?>
<form method="post"><select class="form-select mb-3 w-auto" name="status"><option>In Progress</option><option>Resolved</option></select>
<textarea class="form-control mb-3" name="notes" rows="3" placeholder="Work notes / resolution details" required></textarea>
<button class="btn btn-primary">Save Update</button> <a class="btn btn-outline-secondary" href="dashboard.php">Back</a></form></div>
<?php include '../includes/footer.php';?>
