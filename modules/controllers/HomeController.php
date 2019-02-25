<?php 

	class HomeController extends Controller
	{
		
		public function index()
		{
			// $this-view('welcome') - digunakan untuk memanggil view bernama welcome.class.php
			$this->view('welcome');
		}
	}
?>