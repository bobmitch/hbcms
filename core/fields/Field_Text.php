<?php
defined('CMSPATH') or die; // prevent unauthorized access

class Field_Text extends Field {
	public function display() {
		echo "<label class='label'>Text Field Label</label>";
		echo "<p>Hello, I am a field!</p>";
	}


	public function inject_designer_javascript() {
		?>
		<script>
			window.Field_Text = {};
			// template is what gets injected when the field 'insert new' button gets clicked
			window.Field_Text.designer_template = `
			<div class="field">
				<h2 class='heading title'>Text Field</h2>	

				<label class="label">Label</label>
				<div class="control has-icons-left has-icons-right">
					<input required name="label" class="input iss-success" type="label" placeholder="Label" value="">
				</div>

				<label class="label">Required</label>
				<div class="control has-icons-left has-icons-right">
					<input name="required" class="checkbox iss-success" type="checkbox"  value="">
				</div>
			</div>`;
		</script>
		<?php 
	}

	public function designer_display() {

	}
}