<?php
$domain = ""; 

if ($domain == "localhost:8097"): 
    error_reporting(E_ALL); 
    ini_set('display_errors',1); 
else: 
    @include_once dirname(__FILE__)."/routes.php"; 
endif; 
?>