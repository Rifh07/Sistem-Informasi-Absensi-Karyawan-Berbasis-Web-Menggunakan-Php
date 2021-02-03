<?php


require ("class/title.php");
require ("config/database.php");
require ("class/database.php");
require ("class/users.php");
require ("class/mesin.php");
require ("class/absensi.php");

$title = new Title();
$users = new Users();
$mesin = new Mesin();
$absensi = new Absensi();