<?php 

$conn   = mysqli_connect("localhost","root","","new_ypm");
date_default_timezone_set('Asia/Jakarta');

$date   = date('Y-m-d');

$array_hr= array(1=>"Senin","Selasa","Rabu","Kamis","Jumat","Sabtu","Minggu");
$hr = $array_hr[date('N')];
$timestamps   = date('Y-m-d H:i:s');

?>