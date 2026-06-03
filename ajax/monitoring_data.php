<?php

include '../config/koneksi.php';

$result = mysqli_query($conn,"

SELECT
a.*,
u.nama,
e.nama_ujian

FROM active_sessions a

LEFT JOIN users u
ON a.user_id=u.id

LEFT JOIN exam_list e
ON a.exam_id=e.id

ORDER BY a.last_ping DESC

");

$data=[];

while($row=mysqli_fetch_assoc($result)){

$status='';

if($row['status']=='online'){
$status='<span class="badge badge-success">Online</span>';
}

if($row['status']=='offline'){
$status='<span class="badge badge-secondary">Offline</span>';
}

if($row['status']=='blocked'){
$status='<span class="badge badge-danger">Blocked</span>';
}

$data[]=[

$row['nama'],
$row['nama_ujian'],
$status,
$row['violation_count'],
$row['last_ping'],

'
<a href="block_siswa.php?id='.$row['user_id'].'"
class="btn btn-warning btn-sm">

Block

</a>

<a href="unblock_siswa.php?id='.$row['user_id'].'"
class="btn btn-success btn-sm">

Unblock

</a>

'

];

}

echo json_encode([
"data"=>$data
]);