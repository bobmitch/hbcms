<?php
defined('CMSPATH') or die; // prevent unauthorized access
?>
<h1 class='title is-1'>
	Home
</h1>
<h5 class='title'>Testing Forms</h5>
<form method="POST" action="">
<?php
$test_form->display_front_end();
?>
<button class='submit button' type='submit'>Submit</button>
</form>