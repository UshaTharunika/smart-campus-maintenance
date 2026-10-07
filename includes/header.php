<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title><?=e($title??'Smart Campus')?> | Smart Campus Maintenance</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
<link href="<?=BASE?>assets/css/style.css" rel="stylesheet"></head><body>
<nav class="navbar navbar-expand-lg navbar-dark app-nav"><div class="container">
<a class="navbar-brand fw-bold" href="<?=BASE?>index.php"><i class="fa-solid fa-screwdriver-wrench me-2"></i>Smart Campus</a>
<button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#nv"><span class="navbar-toggler-icon"></span></button>
<div class="collapse navbar-collapse" id="nv"><ul class="navbar-nav ms-auto align-items-lg-center">
<?php $r=$_SESSION['role']??''; if($r==='user'):?>
<li class="nav-item"><a class="nav-link" href="<?=BASE?>user/dashboard.php">Dashboard</a></li>
<li class="nav-item"><a class="nav-link" href="<?=BASE?>user/submit_complaint.php">Report Issue</a></li>
<li class="nav-item"><a class="nav-link" href="<?=BASE?>user/complaints.php">My Complaints</a></li>
<?php elseif($r==='staff'):?>
<li class="nav-item"><a class="nav-link" href="<?=BASE?>staff/dashboard.php">My Work</a></li>
<?php elseif($r==='admin'):?>
<li class="nav-item"><a class="nav-link" href="<?=BASE?>admin/dashboard.php">Dashboard</a></li>
<li class="nav-item"><a class="nav-link" href="<?=BASE?>admin/complaints.php">Complaints</a></li>
<?php endif; if($r):?>
<li class="nav-item"><a class="btn btn-light btn-sm ms-lg-3" href="<?=BASE?>logout.php">Logout (<?=e($_SESSION['name'])?>)</a></li>
<?php else:?>
<li class="nav-item"><a class="nav-link" href="<?=BASE?>login.php">Login</a></li>
<li class="nav-item"><a class="btn btn-light btn-sm ms-lg-3" href="<?=BASE?>register.php">Register</a></li>
<?php endif;?></ul></div></div></nav><main class="container py-4">
