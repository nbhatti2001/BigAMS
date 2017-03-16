<?php
session_start(); 
if(!isset($_SESSION['user']))
    header("Location:login.php",true);
else
    header("Location:list_student.php",true);
?>