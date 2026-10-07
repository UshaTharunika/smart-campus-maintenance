<?php require '../includes/auth.php';require_role('user');$id=(int)($_GET['id']??0);$msg='';
$st=$db->prepare('SELECT c.*,u.name uname FROM complaints c JOIN users u ON u.id=c.user_id WHERE c.id=? AND c.user_id=?');$st->bind_param('ii',$id,$_SESSION['uid']);$st->execute();$c=$st->get_result()->fetch_assoc();
if(!$c){$title='Not found';include '../includes/header.php';echo '<div class="alert alert-warning">Complaint not found.</div>';include '../includes/footer.php';exit;}
if($_SERVER['REQUEST_METHOD']==='POST'&&in_array($c['status'],['Resolved','Closed'])){
 $rt=(int)$_POST['rating'];$cm=trim($_POST['comments']??'');
 if($rt>=1&&$rt<=5){$f=$db->prepare('INSERT INTO feedback(complaint_id,user_id,rating,comments) SELECT ?,?,?,? FROM DUAL WHERE NOT EXISTS(SELECT 1 FROM feedback WHERE complaint_id=?)');
  $f->bind_param('iiisi',$id,$_SESSION['uid'],$rt,$cm,$id);$f->execute();$db->query("UPDATE complaints SET status='Closed' WHERE id=$id");$c['status']='Closed';$msg='Thank you for your feedback!';}}
$a=$db->query("SELECT s.name FROM assignments a JOIN staff s ON s.id=a.staff_id WHERE a.complaint_id=$id ORDER BY a.id DESC LIMIT 1")->fetch_assoc();
$ups=$db->query("SELECT * FROM updates WHERE complaint_id=$id ORDER BY id DESC");
$fb=$db->query("SELECT 1 FROM feedback WHERE complaint_id=$id")->num_rows;
$steps=['Pending'=>'Submitted','Assigned'=>'Assigned','In Progress'=>'Work Started','Resolved'=>'Resolved','Closed'=>'Closed'];$cur=array_search($c['status'],array_keys($steps));
$title='Complaint Details';include '../includes/header.php';?>
<div class="card p-4"><div class="d-flex justify-content-between"><h4><?=cid($c)?> &mdash; <?=e($c['title'])?></h4><?=badge($c['priority'])?></div>
<ul class="timeline"><?php $i=0;foreach($steps as $l):?><li class="<?=$i<$cur?'done':($i==$cur?'done now':'')?>"><div class="dot"><?=$i<=$cur?'&#10003;':'&#9675;'?></div><?=$l?></li><?php $i++;endforeach;?></ul>
<div class="row"><div class="col-md-6"><p><b>Category:</b> <?=e($c['category'])?><br><b>Location:</b> <?=e($c['location'])?><br><b>Severity:</b> <?=e($c['severity'])?><br><b>Submitted by:</b> <?=e($c['uname'])?><br><b>Date:</b> <?=$c['created_at']?><br><b>Assigned staff:</b> <?=e($a['name']??'Not yet assigned')?><br><b>Status:</b> <?=badge($c['status'])?></p>
<p><?=nl2br(e($c['description']))?></p></div><div class="col-md-6"><?php if($c['image']):?><img class="img-fluid rounded" src="<?=BASE?>uploads/complaints/<?=e($c['image'])?>"><?php endif;?></div></div>
<h6>Work Updates</h6><?php while($u=$ups->fetch_assoc()):?><div class="border-start ps-3 mb-2"><?=badge($u['status'])?> <small class="text-muted"><?=$u['updated_at']?></small><br><?=e($u['notes'])?></div><?php endwhile;?>
<?php if($msg):?><div class="alert alert-success mt-3"><?=$msg?></div><?php endif;?>
<?php if(in_array($c['status'],['Resolved','Closed'])&&!$fb):?><hr><h6>Give Feedback</h6><form method="post"><select class="form-select mb-2 w-auto" name="rating"><?php for($i=5;$i>0;$i--):?><option value="<?=$i?>"><?=$i?> star<?=$i>1?'s':''?></option><?php endfor;?></select><textarea class="form-control mb-2" name="comments" placeholder="Comments"></textarea><button class="btn btn-success">Submit Feedback</button></form><?php endif;?></div>
<?php include '../includes/footer.php';?>
