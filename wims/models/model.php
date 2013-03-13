<?php
abstract class Model {
	private $properties;
	private $table;
	private $cursor;
	
	/*
	 * 
	 */
	function getTable() {
		return $this->table;
	}
	
	public function cursor($cursor = null) {
		if (isset($cursor) && $cursor != null)
			$this->cursor = $cursor;
		return $this->cursor;
	}
	/*
	 * Set properties list
	 * @param array_of_string list of all properties' name
	 * @return void
	 */
	function setPropertiesList($propertiesList) {
		$this->properties = $propertiesList;
	} 
	
	/*
	 * Set property
	 *
	 * @param string property name that will be set
	 * @param mixed value that property will be set to
	 * @return void
	 */
	public function setProperty($propertyName, $value) {
		$this->$propertyName = $value;
	}
	
	/*
	 * Check if current object has a particular property or not
	 * @param string propery name 
	 * 
	 * @return boolean existence of property in object
	 */
	public function hasProperty($propertyName) {
		return in_array($propertyName, $this->properties);
	}
}
?>	