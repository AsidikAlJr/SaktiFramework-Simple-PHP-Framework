<?php 
	session_start();
	// define configurations
	// define - Menentukan konstanta atau nilai tetap bernama
	// define(name, value[, case_insensitive])
	// dirname - Mengembalikan jalur direktori induk
	// __FILE__ adalah konstanta yang berisi nilai nama lengkap file beserta nama foldernya misal, file cek-akses.php ada di folder C:\xampp\htdocs\multi-login maka __FILE__ akan berisi C:\xampphtdocsmulti-logincek-akses.php.
	define('ROOT', dirname(__FILE__));
	// DIRECTORY_SEPARATOR adalah konstanta yang berisi nilai 'pemisah folder', di windows nilainya \ dan di linux /.
	define('DS', DIRECTORY_SEPARATOR); 

	// Buat require file	
	require_once "config.php";
	require_once "library/database.class.php";
	require_once "library/model.class.php";
	require_once "library/view.class.php";
	require_once "library/controller.class.php";

	// function __autoload digunakan untuk memuat kelas yang tidak ditentukan atau dapat didefiniskan untuk mengaktifkan kelas autoloading __autoload(class)
	function __autoload($className)
	{
		// str_replace - Ganti semua kemunculan string pencarian dengan string pengganti
		// str_replace(search, replace, subject[, count])
		// Fungsi ini mengembalikan string atau array dengan semua kemunculan searchin subject digantikan dengan nilai yang diberikan replace.
		$fileName = str_replace("\\", DS, $className) . '.php'; // berarti mengubah "\\" -> DS
		// file_exists - Memeriksa apakah ada file atau direktori
		// file_exists(filename){}
		if (!file_exists($fileName)) {
			return false;
		}
		include $fileName;
	}

	// Buat MVC
	// isset() digunakan untuk menyatakan variabel sudah diset atau tidak. Jika variabel sudah diset makan variabel akan mengembalikan nilai true, sebaliknya akan bernilai false (memesan tempat di memori) 
	$page = (isset($_GET['page']) && $_GET['page']) ? $_GET['page'] : 'Home';
	$controller = ROOT . DS . 'modules' . DS . 'controllers' . DS . $page .'Controller.php'; 

	if (file_exists($controller)) {
		
		require_once $controller;
		$action = (isset($_GET['action']) && $_GET['action']) ? $_GET['action'] : 'index';
		// Untuk fungsi dari ucfirst() itu sendiri adalah untuk menampilkan huruf pada awal paragraf menjadi huruf besar, sedangkan untuk ucwords() digunakan untuk mengubah setiap huruf pada awal kata menjadi huruf besar. 
		$controllerName = ucfirst($page). 'Controller';

		$obj = new $controllerName();
		// method_exist() untuk memastikan bahwa method yang dipilih saat ini ada atau tidak?
		if (method_exists($obj, $action)) {
			
			$args = array();
			// count - Menghitung semua elemen dalam array, atau sesuatu dalam suatu objek
			if (count($_GET) > 2) {
				// array_slice () mengembalikan urutan elemen dari arrayarrayseperti yang ditentukan oleh offsetdanlength parameter.
				$parts = array_slice($_GET, 2);
				// foreach hanya berfungsi pada array dan objek, dan akan mengeluarkan kesalahan ketika Anda mencoba menggunakannya pada variabel dengan tipe data yang berbeda atau variabel yang tidak diinisialisasi.
				foreach ($parts as $part) {
					// array_push - Dorong satu atau lebih elemen ke ujung array
					array_push($args, $part);
				}
			}
			// call_user_func_array - Memanggil panggilan balik dengan berbagai parameter
			call_user_func_array(array($obj, $action), $args);
		} else die('Action Not Found !');
	} else die('Controller Not Found !');
