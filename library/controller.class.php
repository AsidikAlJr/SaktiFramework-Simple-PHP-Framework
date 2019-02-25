<!-- controller.class.php digunakan untuk memanggil view dan model -->
<?php

	class Controller
	{
		// view() untuk memanggil view
		protected function view($viewName)
		{
			$view = new View($viewName);
			return $view;
		}
		// model() untuk memanggil model
		protected function model($modelName)
		{
			require_once ROOT . DS . 'modules' . DS . 'models' . DS . $modelName . 'Model.php';
			// Untuk fungsi dari ucfirst() itu sendiri adalah untuk menampilkan huruf pada awal paragraf menjadi huruf besar, sedangkan untuk ucwords() digunakan untuk mengubah setiap huruf pada awal kata menjadi huruf besar. 
			$className = ucfirst($modelName) . 'Model';
			$this->modelName = new $className();
		}
		// template() untuk memanggil view di template yang sudah dibuat
		protected function template($viewName, $data = array())
		{
			$view = $this->view('template');
			// bind adalah suatu proses pengiriman data setelah di prepare
			$view->bind('viewName', $viewName);
			$view->bind('data', $data);
		}

		public function back()
		{
			// history.go(-1) digunakan untuk kembali ke satu halaman sebelumnya
			// misal : history.go(-2) berarti kembali ke dua halaman sebelumnya
			echo "<script>history.go(-1);</script>";
		}

		public function redirect($url = "")
		{
			header("Location:" . $url);
		}
		// validate() untuk validasi data supaya tidak ada content yang tidak diinginkan ikut didapatkan atau ditampilkan 
		protected function validate($data)
		{
			// htmlentities() digunakan untuk menampilkan karakter yang dipesan dalam HTML.
			// Fungsi trim () menghilangkan spasi putih dan karakter standar lainnya dari kedua sisi string.
			return htmlentities(trim(strip_tags($data)));
		}
	}
?>