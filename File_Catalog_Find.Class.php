<?php

/**
 
 * Поиск каталогов, подкаталогов и файлов в них by sergioart https://github.com/sergioart/. Win. case-sensetive.
 * Search directory subdirectories and files in them by sergioart https://github.com/sergioart/. Win. case - sensetive.
 * Suche Verzeichnis Unterverzeichnisse und Dateien in ihnen by sergioart https://github.com/sergioart/. Win. case - sensetive.
 *
 * File_Catalog_Find->catalog_find(	  path, 	   sub_dir,   catalog_only,       extension,           file_name   ).
 * File_Catalog_Find->catalog_find('C:/Windows',    true,         false, 		'htm,html,pdf',      'index,glava*').
 *
 * @author      Sergii https://github.com/sergioart/
 * @license     All. Worldwide.
 *
 */
class File_Catalog_Find{

		/**
		 *Поиск каталогов и передача списка в функцию поиска файлов, затем возврат либо списка каталогов либо списка файлов.
		 *Find dir and return the result to find file function.
		 *
		 * str $path           путь
		 * bool $sub_dir        искать во всех  подкаталогах либо только в текущем.
		 * bool $catalog_only   флаг выбора только каталогов,
		 * str $extension      расширение или часть расширения с маской *(несколько через запятую, необязательный параметр)
		 * str $file_name      имя файла или часть имени с маской *(несколько через запятую, необязательный параметр).
		 * 
		 * @return array с названиями каталогов либо файлов, в зависимости от установки флага "catalog_only".
		 */
		public function catalog_find($path, $sub_dir, $catalog_only, $extension='*', $file_name='*'){     
				
				//Поиск всех поддиректорий. Либо передача одного имени директории для поиска файлов. 
				if ($sub_dir == true){
				
					$catalogs = glob($path.'/*',GLOB_ONLYDIR);

					for ($i = 0; $i < count($catalogs); $i++){
						$add = glob($catalogs[$i] . '/*' , GLOB_ONLYDIR);
						$catalogs = array_merge($catalogs, $add);   
					}
					array_unshift($catalogs, $path);//добавим корневую директорию в начало массива.
				}   
				else{
					$catalogs = array();
				}
				
				if ($catalog_only == true){
					return($catalogs);
				}
				else{
					return($this->file_find($catalogs,$extension,$file_name));
				}		
		}
		
		
		/**
		 * Вывод в Html Table результирующего массива.
		 * Result Array To Html Table.
		 */
		public function result_to_table_html($data_array) {
			if(is_array($data_array) && !empty($data_array)){
			
					echo('
						<style>
						
							#to_html_table{border-collapse: collapse;}
							
							td,th{padding:1px; border:1px solid black; text-align:left; font: 10pt \'Times New Roman\'}
							
							th{background: #b0e0e6;}
							
						</style>
						<br />
						<table id="to_html_table">');	
					echo('<tr><th>№</th>	<th>Name</th></tr>');
						$n=1;
						foreach($data_array as $row)
						{
								$row = mb_convert_encoding($row,'UTF-8','CP1251');
								echo('<tr>	<td>'.$n++.'</td>	<td>'.$row.'</td>	</tr>');
						}
					echo('</table><br />');	
			}
			else{
				//echo('Files or cat not found!');
			}
		}
		
		
		/**
		*Поиск файлов в каталогах из списка, и возврат результата.
		*Find File From Dir List, and returns the result.
		*/
		private function file_find($catalogs,$extension,$file_name){
		
				//Поиск файлов в массиве директорий $catalogs	
				$files = array();

				foreach($catalogs as $key => $value){
				
					$add = glob($value.'/{'.$file_name.'}.{'.$extension.'}' , GLOB_BRACE);
					$files = array_merge($files, $add);
				}
				return($files);
		}

		
		/**
		 *Обработка Errors.
		 */
		function __get($name){echo("Ahtung! no argument.".$name);} 
		function __set($name, $value){echo("Ahtung! no argument input.".$name);} 
		function __call($name, $args){echo("Ahtung! no procedure.".$name);}
}	
 
?>
