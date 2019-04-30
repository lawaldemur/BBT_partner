<?php
class db {
	public $config = false;
	public $where = false;
	public $insert = false;
	public $update = false;
	public $table = false;
	public $conn = false;


	public function __construct($server, $login, $pass, $database)
	{
		$this->config = [$server, $login, $pass, $database];
	}

	public function connect()
	{
		$this->conn = new mysqli($this->config[0], $this->config[1], $this->config[2], $this->config[3]);
		if ($this->conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
			return;
		}
		$this->conn->set_charset("utf8");
		return $this->conn;
	}

	public function select($types='', $cols=['*'], $other='')
	{
		$query = "SELECT ".implode(', ', $cols)." FROM `{$this->table}`";
		$vars = [];
		$vars_tmp = [];
		if ($this->where) {
			$y = 0;
			foreach ($this->where as $key => $value) {
				if ($key == 'date')
					$query .= $y == 0 ? " WHERE $value" : " AND $value";
				elseif ($key == 'like') {
					$query .= $y == 0 ? " WHERE ".$value[0] : " AND ".$value[0];
					$vars_tmp[] = $value[1];
					$vars_tmp[] = $value[1];
				} elseif ($key == 'not_equal') {
					$query .= $y == 0 ? " WHERE {$value[0]} <> ?" : " AND {$value[0]} <> ?";
					$vars_tmp[] = $value[1];
				} elseif ($key == 'date_more_equal') {
					$query .= $y == 0 ? " WHERE date >= ?" : " AND date >= ?";
					$vars_tmp[] = $value;
				} else {
					$query .= $y == 0 ? " WHERE $key = ?" : " AND $key = ?";
					$vars_tmp[] = $value;
				}
				$y++;
			}
			if ($vars_tmp) {
				for ($i=0; $i < count($vars_tmp); $i++)
					$vars[] = &$vars_tmp[$i];
			}
		}
		$query .= $other;

		$conn = $this->connect();
		if ($sth = $conn->prepare($query)) {
			if ($vars) {
				array_unshift($vars, $types);
				call_user_func_array(array($sth, 'bind_param'), $vars);
			}
				
			$sth->execute();
			$result = $sth->get_result();

			$conn->close();
		 	return $result;
		}
	}

	public function update($types='')
	{
		$query = "UPDATE `{$this->table}`";
		$vars = [];
		$vars_tmp = [];
		if ($this->update) {
			$y = 0;
			foreach ($this->update as $key => $value) {
				$query .= $y == 0 ? " SET $key = ?" : " AND $key = ?";
				$vars_tmp[] = $value;
				$y++;
			}
		}
		if ($this->where) {
			$y = 0;
			foreach ($this->where as $key => $value) {
				if ($key == 'date')
					$query .= $y == 0 ? " WHERE $value" : " AND $value";
				else {
					$query .= $y == 0 ? " WHERE $key = ?" : " AND $key = ?";
					$vars_tmp[] = $value;
				}
				$y++;
			}
		}

		if ($vars_tmp) {
			for ($i=0; $i < count($vars_tmp); $i++)
				$vars[] = &$vars_tmp[$i];
		}

		$conn = $this->connect();
		if ($sth = $conn->prepare($query)) {
			if ($vars) {
				array_unshift($vars, $types);
				call_user_func_array(array($sth, 'bind_param'), $vars);
			}
				
			$status = $sth->execute();
			$conn->close();
		}
	}

	public function insert($types='')
	{
		$query = "INSERT INTO {$this->table}";
		$vars = [];
		$vars_tmp = [];
		if ($this->insert) {
			$y = 0;
			$keys = [];
			$values = [];

			foreach ($this->insert as $key => $value) {
				$keys[] = $key;
				$values[] = '?';
			}
			
			$vars_tmp = array_values($this->insert);
			for ($i=0; $i < count($vars_tmp); $i++)
				$vars[] = &$vars_tmp[$i];

			$query .= ' ('.implode(', ', $keys).') VALUES ('.implode(', ', $values).')';
		}


		$conn = $this->connect();
		if ($sth = $conn->prepare($query)) {
			if ($vars) {
				array_unshift($vars, $types);
				call_user_func_array(array($sth, 'bind_param'), $vars);
			}
				
			$sth->execute();
			$conn->close();
		}
	}

	public function delete($types='')
	{
		$query = "DELETE FROM `{$this->table}`";
		$vars = [];
		$vars_tmp = [];
		if ($this->where) {
			$y = 0;
			foreach ($this->where as $key => $value) {
				if ($key == 'date')
					$query .= $y == 0 ? " WHERE $value" : " AND $value";
				elseif ($key == 'like') {
					$query .= $y == 0 ? " WHERE ".$value[0] : " AND ".$value[0];
					$vars_tmp[] = $value[1];
					$vars_tmp[] = $value[1];
				} else {
					$query .= $y == 0 ? " WHERE $key = ?" : " AND $key = ?";
					$vars_tmp[] = $value;
				}
				$y++;
			}
			if ($vars_tmp) {
				for ($i=0; $i < count($vars_tmp); $i++)
					$vars[] = &$vars_tmp[$i];
			}
		}

		$conn = $this->connect();
		if ($sth = $conn->prepare($query)) {
			if ($vars) {
				array_unshift($vars, $types);
				call_user_func_array(array($sth, 'bind_param'), $vars);
			}
				
			$sth->execute();
			$conn->close();
		}
	}

	public function set_where($array)
	{
		$this->where = $array;
	}

	public function set_update($array)
	{
		$this->update = $array;
	}

	public function set_insert($array)
	{
		$this->insert = $array;
	}

	public function set_table($table)
	{
		$this->table = $table;
	}
}
