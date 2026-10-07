<?php require '../includes/auth.php';require_role('user');$err='';$ok='';
$cats=['Electrical','Plumbing','Cleaning','Furniture','AC/Fan','Computer/Lab','Network/Internet','Classroom','Water','Other'];
if($_SERVER['REQUEST_METHOD']==='POST'){
 $t=trim($_POST['title']??'');$c=$_POST['category']??'';$d=trim($_POST['description']??'');$sev=$_POST['severity']??'';
 $b=trim($_POST['building']??'');$fl=trim($_POST['floor']??'');$rm=trim($_POST['room']??'');$loc="$b, Floor $fl, Room $rm";$img=null;
 if(!$t||!$d||!$b||!$fl||!$rm)$err='Please fill in all required fields.';
 elseif(!in_array($c,$cats)||!in_array($sev,['Low','Medium','High']))$err='Invalid category or severity.';
 elseif(strlen($d)<10)$err='Description must be at least 10 characters.';
 elseif(!empty($_FILES['image']['name'])){
  $f=$_FILES['image'];$ext=strtolower(pathinfo($f['name'],PATHINFO_EXTENSION));
  $mime=$f['error']===0?mime_content_type($f['tmp_name']):'';
  if($f['error']!==0||!in_array($ext,['jpg','jpeg','png'])||!in_array($mime,['image/jpeg','image/png'])||$f['size']>2097152)$err='Invalid image. Use JPG/PNG up to 2MB.';
  else{$img=uniqid('img_').'.'.$ext;move_uploaded_file($f['tmp_name'],'../uploads/complaints/'.$img);}}
 if(!$err){$p=priority_for($c,$sev);$st=$db->prepare('INSERT INTO complaints(user_id,category,title,description,location,severity,priority,image) VALUES(?,?,?,?,?,?,?,?)');
  $st->bind_param('isssssss',$_SESSION['uid'],$c,$t,$d,$loc,$sev,$p,$img);$st->execute();
  $id=$db->insert_id;$ok='Complaint submitted! Your ID is CMP-'.date('Y').'-'.str_pad($id,4,'0',STR_PAD_LEFT).' (Priority: '.$p.')';}}
$title='Report Issue';include '../includes/header.php';?>
<div class="card p-4"><h4>Report a Maintenance Issue</h4>
<?php if($err):?><div class="alert alert-danger"><?=e($err)?></div><?php endif;?>
<?php if($ok):?><div class="alert alert-success"><?=e($ok)?> <a href="complaints.php">Track it</a></div><?php endif;?>
<form id="complaintForm" method="post" enctype="multipart/form-data" novalidate><div class="row g-3">
<div class="col-md-8"><label class="form-label">Title *</label><input class="form-control" name="title" required></div>
<div class="col-md-4"><label class="form-label">Category *</label><select class="form-select" name="category" required><option value="">Select</option><?php foreach($cats as $x):?><option><?=e($x)?></option><?php endforeach;?></select></div>
<div class="col-12"><label class="form-label">Description *</label><textarea class="form-control" name="description" rows="3" required></textarea></div>
<div class="col-md-4"><label class="form-label">Building *</label><input class="form-control" name="building" required></div>
<div class="col-md-4"><label class="form-label">Floor *</label><input class="form-control" name="floor" required></div>
<div class="col-md-4"><label class="form-label">Room *</label><input class="form-control" name="room" required></div>
<div class="col-md-4"><label class="form-label">Severity *</label><select class="form-select" name="severity" required><option value="">Select</option><option>Low</option><option>Medium</option><option>High</option></select></div>
<div class="col-md-4"><label class="form-label">Smart Priority</label><div class="form-control bg-light fw-bold" id="prioPreview">--</div></div>
<div class="col-md-4"><label class="form-label">Image (JPG/PNG)</label><input class="form-control" type="file" name="image" accept=".jpg,.jpeg,.png"></div>
<div class="col-12"><button class="btn btn-primary">Submit Complaint</button></div></div></form></div>
<?php include '../includes/footer.php';?>
