<?
// v012b

//$input_file_name="!_SAMPLE_descript_16gbflash.ion";
if(!isSet($input_file_name))$input_file_name="!_SAMPLE_descript_16gbflash.ion";
$flag_copy_description_file=false;
//$dir_initial='T:/!_image/_/';
if(!isSet($dir_initial))$dir_initial='W:/images2describe/__MERGED_/';

if(!isSet($MERGEDresult))$MERGEDresult='W:/images2describe/__MERGED_/';
$dir_sub=$MERGEDresult;

//$dir_of_textfile='W:/images2describe/';
if(!isSet($dir_of_textfile))$dir_of_textfile='W:/images2describe/';
//$dir    = 'c:/xampp/htdocs/my_scripts_/';
$dir    = $dir_initial.$dir_sub;//.'/';
echo "<h2>dir :".$dir."</h2>";
//$files_list = scandir($dir);
$files_list_array = glob($dir ."*.{jpg,JP*,gif,GIF,png,PNG,bmp,BMP}", GLOB_BRACE) ;
var_dump($files_list_array);

$file_to_write = 'descript_'.DATE("mds").'.ion';
echo $file_to_write;
//$csvfile = "descript.ion";
//$file_handle = fopen($csvfile, "r");
//$line_of_text = array();

foreach ($files_list_array as $value) {
 $value=str_replace($dir,"",$value);

$a_random_line = RandomLine1(); 
while(strlen($a_random_line) <5 )
{
	$a_random_line = RandomLine1();
}


$line_text='"'.$value.'" ' .$a_random_line;
echo "<h2>line_text :".$line_text."</h2>";

file_put_contents($dir_initial.$file_to_write, $line_text, FILE_APPEND | LOCK_EX);
echo $line_text;
echo "<br>\n";
echo $input_file_name;
echo "<br>\n";

}
echo "<h2>Writing file :".$file_to_write."</h2>";

if($flag_copy_description_file){
echo "<h2>trying to copy file :".$dir_initial.$file_to_write."</h2>";

copy($file_to_write,$dir_initial.$file_to_write);
}

function RandomLine1() {
	global $input_file_name,$dir,$dir_of_textfile;
	$textfile = $dir_of_textfile.$input_file_name;
	echo "<h2>textfile :".$textfile."</h2>";
		if(file_exists($textfile)){ 
			$sites =file($textfile); 
			$string = $sites[array_rand($sites)];
		} else {
			$string = "Error";
		}
		
	return $string;
}
