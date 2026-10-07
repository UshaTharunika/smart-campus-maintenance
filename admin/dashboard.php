<?php require '../includes/auth.php';require_role('admin');
$s=$db->query("SELECT COUNT(*) t,SUM(status='Pending') p,SUM(status='Assigned') a,SUM(status='In Progress') ip,SUM(status IN('Resolved','Closed')) r,SUM(priority='HIGH') h FROM complaints")->fetch_assoc();
function grp($db,$col){$o=[];$r=$db->query("SELECT $col k,COUNT(*) n FROM complaints GROUP BY $col");while($x=$r->fetch_assoc())$o[$x['k']]=(int)$x['n'];return $o;}
$g=['cat'=>grp($db,'category'),'st'=>grp($db,'status'),'pr'=>grp($db,'priority')];
$rows=$db->query('SELECT c.*,u.name uname FROM complaints c JOIN users u ON u.id=c.user_id ORDER BY c.id DESC LIMIT 6');
$title='Admin Dashboard';include '../includes/header.php';?>
<h3 class="mb-3">Admin Dashboard</h3><div class="row g-3 mb-4"><?php foreach([['Total',$s['t']],['Pending',$s['p']],['Assigned',$s['a']],['In Progress',$s['ip']],['Resolved',$s['r']],['High Priority',$s['h']]] as $c):?>
<div class="col-6 col-md-2"><div class="card p-3"><small class="text-muted"><?=$c[0]?></small><div class="stat"><?=(int)$c[1]?></div></div></div><?php endforeach;?></div>
<div class="row g-3 mb-4"><?php foreach(['cat'=>'By Category','st'=>'By Status','pr'=>'By Priority'] as $k=>$l):?><div class="col-md-4"><div class="card p-3"><h6><?=$l?></h6><canvas id="ch_<?=$k?>"></canvas></div></div><?php endforeach;?></div>
<div class="card p-3"><div class="d-flex justify-content-between"><h5>Recent Complaints</h5><a href="complaints.php" class="btn btn-sm btn-primary">Manage All</a></div><div class="table-responsive"><table class="table"><tbody>
<?php while($r=$rows->fetch_assoc()):?><tr><td><?=cid($r)?></td><td><?=e($r['title'])?></td><td><?=e($r['uname'])?></td><td><?=badge($r['priority'])?></td><td><?=badge($r['status'])?></td></tr><?php endwhile;?></tbody></table></div></div>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script>const G=<?=json_encode($g)?>,T={cat:'bar',st:'doughnut',pr:'pie'};
for(const k in G)new Chart(document.getElementById('ch_'+k),{type:T[k],data:{labels:Object.keys(G[k]),datasets:[{data:Object.values(G[k]),backgroundColor:['#1565c0','#e53935','#fbc02d','#43a047','#8e24aa','#00acc1','#fb8c00','#6d4c41','#546e7a','#d81b60']}]},options:{plugins:{legend:{display:k!=='cat'}}}});</script>
<?php include '../includes/footer.php';?>
