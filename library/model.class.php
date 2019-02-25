<?php
	
	class Model
	{
		public $db;
		protected $tableName;

		public function __construct()
		{
			$this->db = new Database();
		}

		public function model($modelName)
		{
			require_once ROOT . DS . 'modules' . DS . 'models' . DS . $modelName . 'Model.php';
			$className = ucfirst($modelName) . 'Model';
			$this->$modelName = new $className();
		}
		// get() - untuk mendptkan data, apabila ada data spesifik yang ingin didapatnya tinggal memberikan parameter dalam bentuk array
		public function get($params = "")
		{
			$sql = "SELECT * FROM " . $this->tableName;

			if (is_array($params)) {
				if (isset($params["limit"])) {
					$sql .= " LIMIT " . $params["limit"];
				}
			}

			$this->db->query($sql);
			return $this->db->execute()->toObject();
		}
		// rows() - mendapatkan total dari data yang diinginkan
		public function rows()
		{
			return $this->db->getAll($this->tableName)->numRows();
		}
		// getWhere() - mendapatkan data dengan where/kondisi, juga bisa dari satu kondisi, caranya dengan memberikan parameter array dan isinya adalah apa saja yang ingin dikondisikan
		public function getWhere($params)
		{
			return $this->db->getWhere($this->tableName, $params)->toObject();
		}
		// delete() - menghapus data, data yang dihapus dpt dikondisikan dengan memberikan parameter array
		public function delete($where = array())
		{
			return $this->db->delete($this->tableName, $where);
		}
		// getJoin() - mendapatkan data dari dua tabel atau bisa dsb join tabel
		public function getJoin($tableJoin, $params, $join = "JOIN", $where = "")
		{
			$sql = "SELECT * FROM " . $this->tableName;

			if (is_array($tableJoin)) {
				foreach ($tableJoin as $table) {
					$sql .= " " . $join . " " . $table . " ";
				}
			} else {
				$sql .= " " .$join . " " . $tableJoin . " ";
			}

			foreach ($params as $key => $value) {
				$sql .= " ON " . $key . " = " . $value . " ";
			}

			if ($where && is_array($where)) {
				
				$sql .= " WHERE ";
				$i = 0;

				foreach ($where as $key => $value) {
					$sql .= " " . $key ."='" . $value . "' ";

					$i++;
					if ($i < count($where)) {
						$sql .= " AND ";
					}
				}
			}
			$this->db->query($sql);
			return $this->db->execute()->toObject();
		}

		// insert() - melakukan input data ke database, isi dari field diinputkan bisa berupa parameter array
		public function insert($data = array())
		{
			$insert = $this->db->insert($this->tableName, $data);

			if ($insert) {
				return true;
			}
			return false;
		}
		// update() - untuk melakukan update data di database, isi dari field diubah bisa dgn memberikan paramete array
		public function update($data = array(), $where = array())
		{
			$update = $this->db->update($this->tableName, $data, $where);

			if ($update) {
				return true;
			}

			return false;
		}

	}