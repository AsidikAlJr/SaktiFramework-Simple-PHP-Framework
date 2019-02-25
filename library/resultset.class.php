<!-- Library Resultset ini digunakan untuk menampilkan data atau mendapatkan data dari query yang sudah dijalankan -->
<?php

	class ResultSet
	{
		private $query;

		public function __construct($queryName)
		{
			$this->query = $queryName;
		}
		// Untuk mendapatkan data dalam bentuk array
		public function toArray()
		{
			$data = array();

			if ($this->query) {
				// hasil dari mysqli_fetch_array itu 2 jenis, yaitu array['nama_field'] dan array[urutan_field]. Sedangkan mysqli_fetch_assoc hanya berupa array['nama_field'] saja.
				while ($record = mysqli_fetch_assoc($this->query)) {
					// array_push - Dorong satu atau lebih elemen ke ujung array
					// Catatan: Jika Anda menggunakan array_push () untuk menambahkan satu elemen ke array, lebih baik menggunakan $ array [] = karena dengan cara itu tidak ada overhead memanggil fungsi.
					// Catatan: array_push () akan memunculkan peringatan jika argumen pertama bukan array. Ini berbeda dari perilaku $ var [] di mana array baru dibuat.
					array_push($data, $record);
				
				}
			}

			return $data;
		}
		// Untuk membuat data dalam bentuk object
		public function toObject()
		{
			$data = array();

			if ($this->query) {
				// mysqli_fetch_object - Mengembalikan baris saat ini dari hasil yang ditetapkan sebagai objek
				while ($record = mysqli_fetch_object($this->query)) {
					array_push($data, $record);
				}
			}

			return $data;
		}

		public function numRows()
		{
			// mysql_num_rows - Dapatkan jumlah baris dalam hasil
			return mysqli_num_rows($this->query);
		}
	}
?>