<?php 
date_default_timezone_set('Asia/Jakarta');

$conn   = mysqli_connect("localhost", "root", "", "new_ypm") or die(mysqli_error());
$conn_OOP = new mysqli("localhost", "root", "", "new_ypm");
if ($conn_OOP->connect_error) {
    die("Connection failed: " . $conn_OOP->connect_error);
}

<<<<<<< HEAD
$conn_Server = new mysqli("192.168.1.1", "root", "yes4531203", "new_ypm");
// Check connection
if ($conn_Server->connect_error) {
    die("Connection failed: " . $conn_Server->connect_error);
}
=======
// $conn_Server = new mysqli("192.168.1.1", "root", "yes4531203", "new_ypm");
// if ($conn_Server->connect_error) {
//     die("Connection failed: " . $conn_Server->connect_error);
// }
>>>>>>> b26fa2fd3ef507ea1d1ef37d2c4ca05797e59d05

$date   = date('Y-m-d');
$months = date('Y-m');

$array_hr= array(1=>"Senin","Selasa","Rabu","Kamis","Jumat","Sabtu","Minggu");
$hr = $array_hr[date('N')];
$timestamps   = date('Y-m-d H:i:s');

?>