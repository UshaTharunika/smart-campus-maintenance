<?php
require_once __DIR__.'/../config/database.php';
function require_role($role){
 if(($_SESSION['role']??'')!==$role){header('Location: '.BASE.'login.php?error=unauthorized');exit;}}
