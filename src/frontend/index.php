<?php
ob_start(); // Start output buffering

include('header.php');
include('profile.php');
include('projects.php');
include('blog.php');
include('letschat.php');
include('footer.php');

ob_end_flush(); // Flush the output buffer
?>
