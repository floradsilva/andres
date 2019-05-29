<?php

class SNP_NHP_Options_cats_select extends SNP_NHP_Options
{
	public function __construct($field = array(), $value ='', $parent)
	{
		parent::__construct($parent->sections, $parent->args, $parent->extra_tabs);

		$this->field = $field;
		$this->value = $value;
	}

	public function render()
	{
		$class = (isset($this->field['class'])) ? 'class="'.$this->field['class'].'" ' : '';
		
		echo '<select id="'.$this->field['id'].'" name="'.$this->args['opt_name'].'['.$this->field['id'].']" '.$class.' >';
		
		$args = wp_parse_args($this->field['args'], array());
			
		$cats = get_categories($args); 
		foreach ($cats as $cat) {
			echo '<option value="'.$cat->term_id.'"'.selected($this->value, $cat->term_id, false).'>'.$cat->name.'</option>';
		}

		echo '</select>';

		echo (isset($this->field['desc']) && !empty($this->field['desc'])) ? ' <span class="description">'.$this->field['desc'].'</span>' : '';	
	}
}