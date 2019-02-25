<!-- view digunakan untuk memanggil view yang ingin digunakan controller dan memberikan data yang didapat ke view -->
<?php 
	
	class View
	{
		public $viewName = NULL;
		public $data = array();
		public $isRender = FALSE;

		public function __construct($view)
		{
			$this->viewName = $view;
		}
		// bind() - untuk memberikan data pada view
		public function bind($name, $value = '')
		{
			if (is_array($name)) {
				foreach ($name as $attr => $val) {
					$this->data[$attr] = $val;
				}
			} else {
				$this->data[$name] = $value;
			}
		}
		
		public function forceRender()
		{
			$this->isRender = TRUE;
			// extract() - Impor variabel ke tabel simbol saat ini dari array
			extract($this->data);
			$view = ROOT . DS . 'modules' . DS . 'views' . DS . $this->viewName . '.view.php';

			if (file_exists($view)) require_once $view;
			else echo ("View Not Found !");
		}

		public function __destruct()
		{
			if (! $this->isRender) $this->forceRender();
		}
}