<!DOCTYPE HTML>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta http-equiv="Cache-Control" content="no-cache" />
        <title>File_Catalog_Find</title>
    </head>
    <body>
	
		<style>			
			body{width:90%;}
			.red{color:Brown;}
			.gray{color:#696969;}
			.border{border-top: 1px solid gray;
					margin-top: 17px;
					margin-bottom: 17px;}
			.searchform{border: 1px solid blue;
						border-radius: 5px 5px 5px 5px;
						padding: 17px 17px 17px 17px;
						width:600px;
						text-align:right;
						background: lightgray;}
			.status_button{	border: 1px solid blue;
							color:#696969;
							margin: right;}		
		</style>
		
		<?php 
			//Подставим введённые пользователем значения по умолчанию в форму.
			//User values as default in form
			if(!empty($_REQUEST['dir'])){$Dir_post_val = $_REQUEST['dir'];}else{$Dir_post_val = 'D:/';}
			//if(isset($_REQUEST['sub_dir'])){$Sub_dir_post_val = 'checked=true';}else{$Sub_dir_post_val = '';}
			if(isset($_REQUEST['dir_only'])){$Dir_only_post_val = 'checked=true';}else{$Dir_only_post_val = '';}
			if(!empty($_REQUEST['extension'])){$Extension_post_val = $_REQUEST['extension'];}else{$Extension_post_val = '*';}
			if(!empty($_REQUEST['file_name'])){$File_name_post_val = $_REQUEST['file_name'];}else{$File_name_post_val = '*';}
		?>
		
		<form action="<?php echo $_SERVER['SCRIPT_NAME'];?>" method="post" class="searchform" name="searchform">
				   <span class="status_button" id="status_button">Status string.</span><br />
			Dir :       <input type=text  id = "Dir_Path" size="77" name="dir" value="<?php echo $Dir_post_val;?>" onkeyup="get_Dir_Status(this.value)" onchange="get_Dir_Status(this.value)" ontimeupdate="get_Dir_Status(this.value)" /><br /><br />   
			Sub_dir :   <input type=checkbox checked="true" name="sub_dir" value="" /><br /><br />
			Dir_only :  <input type=checkbox <?php echo $Dir_only_post_val;?> name="dir_only" value="" /><br /><br />
			<div class="gray">Case-sensetive! Регистрозависимо! Wörterverzeichnis abhängig!</div>
			Extension:  <input type=text  size="77" name="extension" value="<?php echo $Extension_post_val;?>" /><br /><br />
			<div class="gray">Case-sensetive! Регистрозависимо! Wörterverzeichnis abhängig!</div>
			File_name:  <input type=text  size="77" name="file_name" value="<?php echo $File_name_post_val;?>" /><br /><br />
			<input type=submit id="submit_but" name="Find" value="Find. Поиск. Suche." /><br /><br />
		</form>
		<div class="border"></div>
		
		<?php if (isset($_REQUEST['Find'])): ?>
			<?php
				//Обработка POST данных
				//Treatment of POST of data
				if(!empty($_REQUEST['dir'])){$dir = mb_convert_encoding((string)($_REQUEST['dir']),'CP1251','UTF-8');}
					else{$dir = 'D:/';}
				
				if(isset($_REQUEST['sub_dir'])){$sub_dir = 1;}
					else{$sub_dir = 0;}
				
				if(isset($_REQUEST['dir_only'])){$dir_only = 1;}
					else{$dir_only = 0;}
				
				if(!empty($_REQUEST['extension']) && $_REQUEST['extension'] !=='*'){$extension = mb_convert_encoding((string)($_REQUEST['extension']),'CP1251','UTF-8');}
					else{$extension = '*';}
				
				if(!empty($_REQUEST['file_name']) && $_REQUEST['file_name'] !=='*'){$file_name = mb_convert_encoding((string)($_REQUEST['file_name']),'CP1251','UTF-8');}
					else{$file_name = '*';}
				
				require_once "File_Catalog_Find.Class.php";
				
				//Получение, вывод данных
				$file_find= new File_Catalog_Find;
				$file_catalog_array = $file_find->catalog_find($dir,$sub_dir,$dir_only,$extension,$file_name);
				echo "<div class=\"red\">Всего Найдено; All Found; Alle Gefunden - ".count($file_catalog_array)."<br></div>";
				$file_find->result_to_table_html($file_catalog_array);
				
			?>
		<?php endif ?>

		<script>
			function get_Dir_Status(Dir_Path_Val){
				ajax_data = "str=" + Dir_Path_Val;
				request = new XMLHttpRequest();
				request.open("POST", "get_dir_find.php", true);
				request.setRequestHeader("Content-type","application/x-www-form-urlencoded");
				request.setRequestHeader("Content-length", ajax_data.length);
				request.setRequestHeader("Connection", "close");
				
				request.onreadystatechange = function(){
					if (this.readyState == 4){
						if (this.status == 200){
							if (this.responseText != null){
									Check_St(this.responseText);
									//console.log(document.getElementById('submit_but'));
							}
						else alert("Achtung! Ajax Error!")
						}
					else alert( "Achtung! Ajax Error!: " + this.statusText)
					}
				}
				request.send(ajax_data)
			}
			
			function Check_St(responseText){
				if (responseText === '1'){
					document.getElementById('status_button').style.color = 'green';
					document.getElementById('status_button').innerHTML = 'Found! Каталог Имеется! Katalog ist gefunden!';
					document.getElementById('submit_but').disabled = false;
				}
				else if (responseText === '0'){
					document.getElementById('status_button').style.color = 'Brown';
					document.getElementById('status_button').innerHTML = 'Not Found! Каталог не найден! Katalog ist nicht gefunden'
					document.getElementById('submit_but').disabled = true;
				}
			}
		</script>
		
    </body>
</html>
