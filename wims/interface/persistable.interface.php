<?php
interface Persistable {
	public function insert($params);
	public function delete($params);
	public function update($params);
	public function find($params);
}