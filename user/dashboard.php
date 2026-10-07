<?php require '../includes/auth.php';require_role('user');$u=$_SESSION['uid'];
$st=$db->prepare("SELECT COUNT(*) t,SUM(status='Pending') p,SUM(status IN('Assigned','In Progress')) ip,SUM(status IN('Resolved','Closed')) r FROM complaints WHERE user_id=?");
$st->bind_param('i',$u);$st->execute();$s=$st->get_result()->fetch_assoc();
$st=$db->prepare('SELECT * FROM complaints WHERE user_id=? ORDER BY id DESC LIMIT 5');$st->bind_param('i',$u);$st->execute();$rows=$st->get_result();
$title='Dashboard';include '../includes/header.php';?>
<div class="d-flex justify-content-between align-items-center mb-4"><h3>Welcome, <?=e($_SESSION['name'])?></h3><a class="btn btn-primary" href="submit_complaint.php">+ Report New Issue</a></div>
<div class="row g-3 mb-4"><?php foreach([['Total Complaints',$s['t']],['Pending',$s['p']],['In Progress',$s['ip']],['Resolved',$s['r']]] as $c):?>
<div class="col-6 col-md-3"><div class="card p-3"><div class="text-muted"><?=$c[0]?></div><div class="stat"><?=(int)$c[1]?></div></div></div><?php endforeach;?></div>
<div class="card p-3"><h5>Recent Complaints</h5><div class="table-responsive"><table class="table align-middle"><thead><tr><th>ID</th><th>Title</th><th>Location</th><th>Priority</th><th>Status</th><th>Date</th><th></th></tr></thead><tbody>
<?php while($r=$rows->fetch_assoc()):?><tr><td><?=cid($r)?></td><td><?=e($r['title'])?></td><td><?=e($r['location'])?></td><td><?=badge($r['priority'])?></td><td><?=badge($r['status'])?></td><td><?=date('d M Y',strtotime($r['created_at']))?></td><td><a class="btn btn-sm btn-outline-primary" href="complaint_details.php?id=<?=$r['id']?>">View</a></td></tr><?php endwhile;?></tbody></table></div></div>
<?php include '../includes/footer.php';?>
