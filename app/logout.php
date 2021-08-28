<?php
session_start();
unset($_SESSION['nom'],$_SESSION['prenom'],$_SESSION['admin']);
header('Location:login.php');



