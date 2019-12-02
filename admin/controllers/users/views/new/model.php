<?php
defined('CMSPATH') or die; // prevent unauthorized access

// any variables created here will be available to the view

$user = new User();
$all_groups = $user->get_all_groups();
//$all_users = $user->get_all_users();