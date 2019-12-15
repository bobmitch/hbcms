<?php
defined('CMSPATH') or die; // prevent unauthorized access

class Field_Checkbox extends Field {

	public function display() {
		echo "<div class='field'>";
			echo "<label class='label'>{$this->label}</label>";
			echo "<div class='control'>";
				$required="";
				if ($this->required) {$required=" required ";}
				echo "<input maxlength={$this->maxlength} minlength={$this->minlength} class='input' {$required} type='text' id='{$this->id}' name='{$this->name}'>";
			echo "</div>";
			if ($this->description) {
				echo "<p class='help'>" . $this->description . "</p>";
			}
		echo "</div>";
	}


	public function inject_designer_javascript() {
		?>
		<script>
			window.Field_Checkbox = {};
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

	public function load_from_config($config) {
		$this->name = $config->name ?? 'error!!!';
		$this->id = $config->id ?? $this->name;
		$this->label = $config->label ?? '';
		$this->required = $config->required ?? false;
		$this->description = $config->description ?? '';
		$this->filter = $config->filter ?? 'NUMBER';
		$this->missingconfig = $config->missingconfig ?? false;
	}

	public function validate() {
		if ($this->is_missing()) {
			return false;
		}
		return true;
	}
}