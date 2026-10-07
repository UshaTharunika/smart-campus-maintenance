<?php require '../includes/auth.php';require_role('user');
$q='%'.trim($_GET['q']??'').'%';$s=$_GET['status']??'';
$sql='SELECT * FROM complaints WHERE user_id=? AND (title LIKE ? OR category LIKE ? OR location LIKE ? OR CONCAT("CMP-",YEAR(created_at),"-",LPAD(id,4,"0")) LIKE ?)';$ty='issss';$pr=[$_SESSION['uid'],$q,$q,$q,$q];
if($s){$sql.=' AND status=?';$ty.='s';$pr[]=$s;}
$st=$db->prepare($sql.' ORDER BY id DESC');$st->bind_param($ty,...$pr);$st->execute();$rows=$st->get_result();
$title='My Complaints';include '../includes/header.php';?>
<h4>My Complaints</h4><form class="row g-2 mb-3"><div class="col-md-6"><input class="form-control" name="q" placeholder="Search ID, title, category, location" value="<?=e($_GET['q']??'')?>"></div>
<div class="col-md-3"><select class="form-select" name="status"><option value="">All statuses</option><?php foreach(['Pending','Assigned','In Progress','Resolved','Closed'] as $x):?><option <?=$s===$x?'selected':''?>><?=$x?></option><?php endforeach;?></select></div>
<div class="col-md-3"><button class="btn btn-primary">Filter</button></div></form>
<div class="row g-3"><?php if(!$rows->num_rows):?><p class="text-muted">No complaints found.</p><?php endif;while($r=$rows->fetch_assoc()):?>
<div class="col-md-6"><div class="card p-3"><div class="d-flex justify-content-between"><b><?=cid($r)?></b><?=badge($r['priority'])?></div><h6 class="mt-2"><?=e($r['title'])?></h6><small class="text-muted"><?=e($r['category'])?> &middot; <?=e($r['location'])?></small>
<div class="mt-2 d-flex justify-content-between"><?=badge($r['status'])?><a href="complaint_details.php?id=<?=$r['id']?>" class="btn btn-sm btn-outline-primary">View</a></div></div></div><?php endwhile;?></div>
<?php include '../includes/footer.php';?>
