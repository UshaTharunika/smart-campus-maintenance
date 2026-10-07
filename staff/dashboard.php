<?php require '../includes/auth.php';require_role('staff');$sid=staff_id($db);
$st=$db->prepare('SELECT c.*,u.name uname FROM complaints c JOIN assignments a ON a.complaint_id=c.id JOIN users u ON u.id=c.user_id WHERE a.staff_id=? AND a.id=(SELECT MAX(id) FROM assignments WHERE complaint_id=c.id) ORDER BY c.id DESC');
$st->bind_param('i',$sid);$st->execute();$rows=$st->get_result()->fetch_all(MYSQLI_ASSOC);
$n=['t'=>count($rows),'a'=>0,'ip'=>0,'d'=>0];foreach($rows as $r){if($r['status']==='Assigned')$n['a']++;elseif($r['status']==='In Progress')$n['ip']++;else$n['d']++;}
$title='Staff Dashboard';include '../includes/header.php';?>
<h3 class="mb-3">Welcome, <?=e($_SESSION['name'])?></h3><div class="row g-3 mb-4"><?php foreach([['Assigned Complaints',$n['t']],['Pending Work',$n['a']],['In Progress',$n['ip']],['Completed',$n['d']]] as $c):?>
<div class="col-6 col-md-3"><div class="card p-3"><small class="text-muted"><?=$c[0]?></small><div class="stat"><?=$c[1]?></div></div></div><?php endforeach;?></div>
<div class="card p-3"><div class="table-responsive"><table class="table align-middle"><thead><tr><th>ID</th><th>Issue</th><th>Location</th><th>Priority</th><th>Status</th><th></th></tr></thead><tbody>
<?php foreach($rows as $r):?><tr><td><?=cid($r)?></td><td><?=e($r['title'])?><br><small class="text-muted"><?=e(mb_strimwidth($r['description'],0,70,'...'))?></small></td><td><?=e($r['location'])?></td><td><?=badge($r['priority'])?></td><td><?=badge($r['status'])?></td><td><a class="btn btn-sm btn-primary" href="update_complaint.php?id=<?=$r['id']?>">Update</a></td></tr><?php endforeach;?></tbody></table></div></div>
<?php include '../includes/footer.php';?>
