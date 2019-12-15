<?php
defined('CMSPATH') or die; // prevent unauthorized access
?>
<h5>Choose Blog Options</h5>

<h3>Tag</h3>
<div class="field">
		<div class='control'>
			<div class='select'>
<select required name='view_options[]'>
	<option value=''>Choose</option>
<option <?php if ($options_array[0]=='a') { echo " selected ";}?> value='a'>None</option>
<option <?php if ($options_array[0]=='b') { echo " selected ";}?> value='b'>blah</option>
<option <?php if ($options_array[0]=='c') { echo " selected ";}?> value='c'>foo</option>
</select>
</div></div></div>

<h3>Items Per Page</h3>
<div class="field">
		<div class='control'>
			<div class='select'>
<select required name='view_options[]'>
<option value=''>Choose</option>
<option <?php if ($options_array[1]=='d') { echo " selected ";}?> value='d'>1</option>
<option <?php if ($options_array[1]=='e') { echo " selected ";}?> value='e'>2</option>
<option <?php if ($options_array[1]=='f') { echo " selected ";}?> value='f'>3</option>
</select>
</div></div></div>