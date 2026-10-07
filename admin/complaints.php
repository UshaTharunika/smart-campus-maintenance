<?php require '../includes/auth.php';require_role('admin');$msg='';
if($_SERVER['REQUEST_METHOD']==='POST'){
 $id=(int)$_POST['id'];
 if(($_POST['action']??'')==='delete'){$d=$db->prepare('DELETE FROM complaints WHERE id=?');$d->bind_param('i',$id);$d->execute();$msg='Complaint deleted.';}
 else{$pr=$_POST['priority'];$stt=$_POST['status'];$sid=(int)($_POST['staff_id']??0);
  if(in_array($pr,['LOW','MEDIUM','HIGH'])&&in_array($stt,['Pending','Assigned','In Progress','Resolved','Closed'])){
   if($sid){$ex=$db->query("SELECT staff_id FROM assignments WHERE complaint_id=$id ORDER BY id DESC LIMIT 1")->fetch_assoc();
    if(!$ex||$ex['staff_id']!=$sid){$a=$db->prepare('INSERT INTO assignments(complaint_id,staff_id) VALUES(?,?)');$a->bind_param('ii',$id,$sid);$a->execute();if($stt==='Pending')$stt='Assigned';}}
   $u=$db->prepare('UPDATE complaints SET priority=?,status=? WHERE id=?');$u->bind_param('ssi',$pr,$stt,$id);$u->execute();$msg='Complaint updated.';}}}
$w=['1=1'];$ty='';$pa=[];
if(($q=trim($_GET['q']??''))!==''){$w[]='(u.name LIKE ? OR c.title LIKE ? OR c.location LIKE ? OR c.category LIKE ? OR CONCAT("CMP-",YEAR(c.created_at),"-",LPAD(c.id,4,"0")) LIKE ?)';$ty.='sssss';array_push($pa,...array_fill(0,5,"%$q%"));}
foreach(['category'=>'c.category','priority'=>'c.priority','status'=>'c.status'] as $k=>$col)if(!empty($_GET[$k])){$w[]="$col=?";$ty.='s';$pa[]=$_GET[$k];}
if(!empty($_GET['date'])){$w[]='DATE(c.created_at)=?';$ty.='s';$pa[]=$_GET['date'];}
$st=$db->prepare('SELECT c.*,u.name uname,(SELECT staff_id FROM assignments WHERE complaint_id=c.id ORDER BY id DESC LIMIT 1) sid FROM complaints c JOIN users u ON u.id=c.user_id WHERE '.implode(' AND ',$w).' ORDER BY c.id DESC');
if($pa)$st->bind_param($ty,...$pa);$st->execute();$rows=$st->get_result();$staff=$db->query("SELECT id,name,specialization FROM staff WHERE status='Active'")->fetch_all(MYSQLI_ASSOC);
$cats=['Electrical','Plumbing','Cleaning','Furniture','AC/Fan','Computer/Lab','Network/Internet','Classroom','Water','Other'];
function opts($a,$sel){foreach($a as $x)echo '<option '.($sel===$x?'selected':'').'>'.e($x).'</option>';}
$title='Manage Complaints';include '../includes/header.php';?>
<h4>Manage Complaints</h4><?php if($msg):?><div class="alert alert-success"><?=$msg?></div><?php endif;?>
<form class="row g-2 mb-3"><div class="col-md-3"><input class="form-control" name="q" placeholder="ID, user, location..." value="<?=e($q)?>"></div>
<div class="col-md-2"><select class="form-select" name="category"><option value="">Category</option><?php opts($cats,$_GET['category']??'')?></select></div>
<div class="col-md-2"><select class="form-select" name="priority"><option value="">Priority</option><?php opts(['HIGH','MEDIUM','LOW'],$_GET['priority']??'')?></select></div>
<div class="col-md-2"><select class="form-select" name="status"><option value="">Status</option><?php opts(['Pending','Assigned','In Progress','Resolved','Closed'],$_GET['status']??'')?></select></div>
<div class="col-md-2"><input type="date" class="form-control" name="date" value="<?=e($_GET['date']??'')?>"></div><div class="col-md-1"><button class="btn btn-primary w-100">Go</button></div></form>
<div class="card p-2"><div class="table-responsive"><table class="table align-middle small"><thead><tr><th>ID</th><th>Complaint</th><th>User</th><th>Assign / Priority / Status</th><th></th></tr></thead><tbody>
<?php while($r=$rows->fetch_assoc()):?><tr><td><?=cid($r)?></td><td><b><?=e($r['title'])?></b><br><?=e($r['category'])?> &middot; <?=e($r['location'])?></td><td><?=e($r['uname'])?></td>
<td><form method="post" class="d-flex gap-1 flex-wrap"><input type="hidden" name="id" value="<?=$r['id']?>">
<select name="staff_id" class="form-select form-select-sm w-auto"><option value="">Unassigned</option><?php foreach($staff as $s):?><option value="<?=$s['id']?>" <?=$r['sid']==$s['id']?'selected':''?>><?=e($s['name'])?> (<?=e($s['specialization'])?>)</option><?php endforeach;?></select>
<select name="priority" class="form-select form-select-sm w-auto"><?php opts(['HIGH','MEDIUM','LOW'],$r['priority'])?></select>
<select name="status" class="form-select form-select-sm w-auto"><?php opts(['Pending','Assigned','In Progress','Resolved','Closed'],$r['status'])?></select>
<button class="btn btn-sm btn-primary">Save</button></form></td>
<td><form method="post" onsubmit="return confirm('Delete this complaint?')"><input type="hidden" name="id" value="<?=$r['id']?>"><input type="hidden" name="action" value="delete"><button class="btn btn-sm btn-outline-danger"><i class="fa fa-trash"></i></button></form></td></tr><?php endwhile;?></tbody></table></div></div>
<?php include '../includes/footer.php';?>
