<?php 
	// class database ini digunakan sebagai koneksi ke database sesuai dengan config.php dan juga mengeksekusi sintak query yang akan dijalankan pada website.
	class Database
	{
		// Menggunakan private property karena hanya ingin diakses oleh class Database saja
		private $instance; // property $instance digunakan sebagai koneksi ke database 
		private $sql; // property $sql digunakan sebagai query koneksi ke database

		public function __construct()
		{
			// ROOT -> directory/folder, DS -> pemisah folder/directory
			require_once ROOT . DS . 'library' . DS . 'resultset.class.php';
			$this->instance = mysqli_connect(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

			// mysqli_connect_errno() mengembalikan fungsi kode kesalahan angka dari kesalahan koneksi terakhir, jika ada.
			if (mysqli_connect_errno()) {
			 	// mysqli_connect_error - Mengembalikan deskripsi string dari kesalahan koneksi terakhir
			 	echo "Failed to connect MySQL: " . mysqli_connect_error();

			 } 
		}
		// method query($sql) berperan sebagai setter atau pengatur nilai dari property $sql
		public function query($sql)
		{
			$this->sql = $sql;
		}
		// method getAll($tableName) digunakan untuk mengambil semua data dalam propert $tableName dimana akan dieksekusi ke property $sql atau datanya akan masuk ke method query($sql) 
		public function getAll($tableName)
		{
			$this->sql = "SELECT * FROM " . $tableName;
			// Mengembalikan nilai dari method execute()
			return $this->execute();
		}
		// method getWhere() digunakan untuk mengambil data sesuai dengan kondisi pemilihan data
		public function getWhere($tableName, $where = array())
		{
			$this->sql = "SELECT * FROM " . $tableName;

			// is_array - Menemukan apakah suatu variabel adalah array
			if (is_array($where)) {
				// penggunaan WHERE untuk kondisi pemilihan data yang diinginkan 
				$this->sql .= " WHERE ";
				// dimana jika data $i nol maka $i akan selalu bertambah dalam array $where 
				$i = 0;
				// foreach hanya berfungsi pada array dan objek, dan akan mengeluarkan kesalahan ketika Anda mencoba menggunakannya pada variabel dengan tipe data yang berbeda atau variabel yang tidak diinisialisasi.
				// Karena $where adalah array maka untuk memudahkan dalam proses pengaksesan/pemanggilan digantilah nama atau alias
				foreach ($where as $key => $value) {
					$i++;
					$this->sql .= $key . "='" . $value . "' ";

					// Jika $i kurang dari array $where maka dbutuhkan AND untuk kondisi yang lain 
					if ($i < count($where)) $this->sql .= " AND ";
				}
			}
			
			return $this->execute();
		}

		public function delete($tableName, $where = array())
		{
			$this->sql = "DELETE FROM " . $tableName;

			if (is_array($where)) {
				$this->sql .= " WHERE ";
				$i = 0;
				foreach ($where as $key => $value) {
					$i++;
					$this->sql .= $key . "='" . $value . "' ";
				
					// count - Menghitung semua elemen dalam array, atau sesuatu dalam suatu objek
					if ($i < count($where)) $this->sql .= " AND ";
				}
			}

			return $this->execute();
		}

		public function insert($tableName, $params = array())
		{
			$this->sql = "INSERT INTO " . $tableName . "(";

			$total = count($params);
			$i = 0;
			foreach ($params as $key => $value) {
					$i++;

					$this->sql = $this->sql . $key ;

					if ($i < $total) {
						$this->sql = $this->sql . ',';
					}
			}
			$this->sql = $this->sql .") VALUES(";

			$i = 0;
			foreach ($params as $key => $value) {
				$i++;

				$this->sql = $this->sql . "'" . $value . "'";

				if ($i < $total) {
					$this->sql = $this->sql . ',';
				}
			}
			$this->sql = $this->sql . ")";
			return $this->execute();
		}

		public function update($tableName, $data = array(), $where = array())
		{
			$this->sql = "UPDATE " . $tableName . " SET ";

			$total = count($data);
			$i = 0;
			foreach ($data as $key => $value) {
			 	$i++;

			 	$this->sql = $this->sql . $key . " = '" . $value . "'";

			 	if ($i < $total) {

			 	 	$this->sql = $this->sql . ',';
			 	 } 
			 } 

			 if (is_array($where) && count($where) > 0) {
			 	
			 	$this->sql .= " WHERE ";
			 	$i = 0;

			 	foreach ($where as $key => $value) {
			 		$i++;
			 		$this->sql . $key . " = '" . $value . "'";

			 		if ($i < count($where)) $this->sql .= " AND ";
			 	}
			 }
			 return $this->execute();
		}

		public function bindParams($values)
		{
			if (is_array($values)) {
				foreach ($values as $v) {
					// Mengambil nilai dari method replaceParam($v)
					$this->replaceParam($v);
				}
			} else {
				$this->replaceParam($values);
			}
		}

		public function execute()
		{
			$query = mysqli_query($this->instance, $this->sql);
			return new ResultSet($query);
		}

		private function replaceParam($v)
		{
			// strlen - Dapatkan panjang string
			for ($i=0; $i < strlen($this->sql); $i++) { 
				if ($this->sql[$i] == '?') {
					// substr_replace - Ganti teks dalam bagian string
					// mysqli :: real_escape_string - mysqli :: escape_string - mysqli_real_escape_string - Mengosongkan karakter khusus dalam string untuk digunakan dalam pernyataan SQL, dengan mempertimbangkan charset koneksi saat ini
					$this->sql = substr_replace($this->sql, mysqli_escape_string($v), $i, 1);
					break;
				}
			}
		}
	}