<?php
session_start();
define('BASE','/smart-campus-maintenance/');
mysqli_report(MYSQLI_REPORT_OFF);
$db=@new mysqli('localhost','root','','smart_campus_maintenance');
if($db->connect_errno){die('Service temporarily unavailable. Please check database settings in config/database.php.');}
$db->set_charset('utf8mb4');
function e($s){return htmlspecialchars((string)$s,ENT_QUOTES,'UTF-8');}
function cid($c){return 'CMP-'.date('Y',strtotime($c['created_at'])).'-'.str_pad($c['id'],4,'0',STR_PAD_LEFT);}
function priority_for($cat,$sev){
 $b=['Electrical'=>3,'Network/Internet'=>3,'Water'=>3,'Computer/Lab'=>2,'Plumbing'=>2,'AC/Fan'=>2,'Classroom'=>2];
 $s=($b[$cat]??1)+['Low'=>0,'Medium'=>1,'High'=>3][$sev];
 return $s>=5?'HIGH':($s>=3?'MEDIUM':'LOW');}
function badge($t){$m=['HIGH'=>'danger','MEDIUM'=>'warning text-dark','LOW'=>'success','Pending'=>'secondary','Assigned'=>'info text-dark','In Progress'=>'primary','Resolved'=>'success','Closed'=>'dark'];
 return '<span class="badge bg-'.($m[$t]??'secondary').'">'.e($t).'</span>';}
function staff_id($db){$st=$db->prepare('SELECT s.id FROM staff s JOIN users u ON u.email=s.email WHERE u.id=?');$st->bind_param('i',$_SESSION['uid']);$st->execute();$r=$st->get_result()->fetch_assoc();return $r['id']??0;}
