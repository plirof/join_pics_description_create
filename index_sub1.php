<?php
$input_file_name="ads3.txt";

//$dir_initial='T:/images2describe/_0321a/';
$dir_initial='T:/images2describe/_0321a/';
foreach(glob($dir_initial.'*', GLOB_ONLYDIR) as $dir_sub) {
    $dir_sub = str_replace($dir_initial, '', $dir_sub);
			//$dir_sub='beast';
			//$dir    = 'c:/xampp/htdocs/my_scripts_/';
			$dir    = $dir_initial.$dir_sub.'/';
			//$files_list = scandir($dir);
			$files_list_array = glob($dir ."*.{jpg,JP*,gif,png,bmp,php}", GLOB_BRACE) ;
			//var_dump($files_list_array);

			$file_to_write = 'descript.ion'.$dir_sub;
			//$csvfile = "descript.ion";
			//$file_handle = fopen($csvfile, "r");
			//$line_of_text = array();

			foreach ($files_list_array as $value) {
			 $value=str_replace($dir,"",$value);

			$a_random_line = RandomLine(); 
			while(strlen($a_random_line) <5 )
			{
			$a_random_line = RandomLine();
			}


			$line_text='"'.$value.'" ' .$a_random_line;

			file_put_contents($file_to_write, $line_text, FILE_APPEND | LOCK_EX);
			//echo $line_text;
			//echo "<br>\n";

}
}


function RandomLine() {
	global $input_file_name;
	$textfile = 'T:/images2describe/'.$input_file_name;
		if(file_exists($textfile)){ 
			$sites =file($textfile); 
			$string = $sites[array_rand($sites)];
		} else {
			$string = "Error";
		}
		
	return $string;
}
