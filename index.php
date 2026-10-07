<?php require 'config/database.php';$title='Home';include 'includes/header.php';?>
<section class="hero text-center mb-5"><h1 class="display-4 fw-bold">Report. Resolve. Improve.</h1>
<p class="lead">Smart Campus Maintenance &amp; Complaint Management System</p>
<a class="btn btn-light btn-lg m-1" href="<?=BASE?>user/submit_complaint.php">Report an Issue</a>
<a class="btn btn-outline-light btn-lg m-1" href="<?=BASE?>user/complaints.php">Track Complaint</a></section>
<div class="row g-4"><?php foreach([['bolt','Quick Complaint Reporting'],['brain','Smart Priority'],['bars-progress','Real-Time Status Tracking'],['user-gear','Maintenance Staff Assignment'],['star','Feedback & Rating'],['chart-pie','Admin Analytics']] as $f):?>
<div class="col-md-4"><div class="card h-100 text-center p-4"><i class="fa-solid fa-<?=$f[0]?> fa-2x text-primary mb-3"></i><h5><?=$f[1]?></h5></div></div><?php endforeach;?></div>
<?php include 'includes/footer.php';?>
