<?php
	//Проверка существования каталога
	//Verification of isset of catalogue
	//Versuch des Bestehens des Katalogs
	if (isset($_POST['str'])){

		$dir = $_POST['str'];
		
		if (file_exists($dir)){
			echo 1;
		}
		else{
			echo 0;
		}
	};
?>
