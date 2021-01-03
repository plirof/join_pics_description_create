<?php
/*
Merges 2 images
Changes:
v012b

v012a
*/
//$input_file_name="!_SAMPLE_descript_16gbflash.ion";
$input_file_name="ads3.txt"; //One-liners source file.
ini_set("memory_limit","1024M"); // to avoid memory errors. may need to be setup to 1024M

$counter="M000";

$flag_call_index_description_create=true;
$flag_swap_src_with_dest=true; // Swap src & dest spots (Left-Right selection)
$flag_delete_copy_used_file=false;

#$dir_initial='/opt/lampp/htdocs/description_create_/';
$dir_initial='./'; //Script path
$dir_sub='_source1w_';
//$dir    = 'c:/xampp/htdocs/my_scripts_/';
$dir    = $dir_initial.$dir_sub.'/';
//$files_list = scandir($dir);
$files_list_array = glob($dir ."*.{jp*,JP*,PNG,png,BMP,bmp}", GLOB_BRACE) ; //Grab all images of this type to join
//print_r($files_list_array);

$dir1=$dir_initial.'_source1w_'.'/'; //w

$dir2=$dir_initial.'_source2m_/'; //m

$dir_of_textfile='./';// Result path of descript.ion

$MERGEDresult="__MERGED_/"; //path of MERGED images result



#echo $dir1."<hR>";
#echo $dir2."<hR>";
//var_dump(glob($dir1 ."*.{jpg,jpeg,JP*}", GLOB_BRACE) );
//echo "<HR>";

$files_list_array1 = glob($dir1 ."*.{jpg,jpeg,JP*}", GLOB_BRACE) ;//Grab all images of this type to join
$files_list_array2 = glob($dir2 ."*.{jpg,jpeg,JP*}", GLOB_BRACE) ;//Grab all images of this type to join


$random_file1=RandomFile($files_list_array1);
$random_file2=RandomFile($files_list_array2);



foreach ($files_list_array1 as $random_file1) {
set_time_limit(60);  // reset time limit
$random_file2=RandomFile($files_list_array2);
$dest = imagecreatefromjpeg($random_file1);
$src = imagecreatefromjpeg($random_file2);
//echo "<BR> DEST : (".imagesx($dest)*imagesy($dest)." ,        SRC .".imagesx($src)*imagesy($src);
//if 10 times bigger try again
$flag_sizes_near=false;
$max_tries=15;

$dest_pixels=imagesx($dest)*imagesy($dest);
//ECHO "<H2>DIVIDE : ".((imagesx($dest)*imagesy($dest)) /(imagesx($src)*imagesy($src)))."</H2>" ;
while ($flag_sizes_near==false 
		&& $max_tries>0 
		&& (
			($dest_pixels 					> 5*imagesx($src)*imagesy($src)) 
			|| (imagesx($src)*imagesy($src) > 5*$dest_pixels )) 
		){
//		if ((imagesx($dest)*imagesy($dest) >5*imagesx($src)*imagesy($src)) || (5*imagesx($dest)*imagesy($dest) <imagesx($src)*imagesy($src)) )
		//{ 
			echo "<br>AGAINNNNNNNNNNNNNNNNNNNNNNNNNN try $max_tries , Flag_sizes_near=$flag_sizes_near ,";
		    set_time_limit(60);  // reset time limit
			imagedestroy($src);
			$random_file2=RandomFile($files_list_array2);			
			$src = imagecreatefromjpeg($random_file2);
			//$flag_sizes_near=true;
			$max_tries--;
		//}
		echo "<br>end OF  AGAINNNNNNNNNNNNNNNNNNNNNNNNNN try $max_tries , Flag_sizes_near=$flag_sizes_near ,";
}//end of while ($flag_sizes_near=false){

ECHO "<h3>random_file1=$random_file1 <br> random_file2=$random_file2  <br> dir=$dir </h3>";

//Switch image side
if ($flag_swap_src_with_dest){
	$tmp=$src; $src=$dest; $dest=$tmp; //Switch image side
}
//imagealphablending($dest, false);
//imagesavealpha($dest, true);

list($width1, $height1, $type1, $attr1) = getimagesize($random_file1);
list($width2, $height2, $type2, $attr2) = getimagesize($random_file2);
//$iOut = imagecreate ($width1+$width2,max($height1,$height2));
$iOut = imagecreatetruecolor($width1+$width2,max($height1,$height2));

$substring_random_file1=
substr($random_file1,strlen($dir));

// 
#ECHO "<br>DEL \"$substring_random_file1\"";
if($flag_delete_copy_used_file){
//if (!copy($dir1.$substring_random_file1,$dir1."ok_used_/".$substring_random_file1)) {
//    echo ("<h2>Could not create a backup copy of the file ".$dir1.$substring_random_file1." to ".$dir1."ok_used_/".$substring_random_file1."</h2>"); 
//} 
if (!copy($dir1.$substring_random_file1,$dir1."ok_used_/".$substring_random_file1)) {
    echo ("<h2>Could not create a backup copy of the file ".$dir1.$substring_random_file1." to ".$dir1."ok_used_/".$substring_random_file1."</h2>"); 
} 


unlink ($dir1.$substring_random_file1);
} //end of if($flag_delete_copy_used_file{


//bool imagecopy ( resource $dst_im , resource $src_im , int $dst_x , int $dst_y , int $src_x , int $src_y , int $src_w , int $src_h )
//Copy a part of src_im onto dst_im starting at the x,y coordinates src_x, src_y with a width of src_w and a height of src_h. The portion defined will be copied onto the x,y coordinates, dst_x and dst_y.

imagecopy ($iOut,$dest,0,0,0,0,imagesx($dest),imagesy($dest)); 
imagecopy ($iOut,$src,imagesx($dest),0,0,0,imagesx($src),imagesy($src)); 
//imagecopymerge($dest, $src, 10, 9, 0, 0, 181, 180, 100); //have to play with these numbers for it to work for you, etc.
//header('Content-Type: image/png');
//imagepng($iOut,'P:/hello.jpg');
$name1 = substr($random_file1, -20);
$name2 = substr($random_file2, -20);

imagejpeg($iOut,$dir_initial.$MERGEDresult.$counter.$name1.$name2.'_MERGED.jpg'); //Images are saved in the "__MERGED_" dir with "*_MERGEDpostfix"
$counter++;
imagedestroy($dest);
imagedestroy($src);
imagedestroy($iOut);
UNSET($dest);UNSET($src);UNSET($iOut);
}

if ($flag_call_index_description_create) include "index_description_create.php";

function RandomFile(Array $files_list_array) {
	//global $input_file_name;
	//$textfile = 'T:/!_image/'.$input_file_name;
		//if(file_exists($textfile)){ 
			//$sites =file($textfile); 
			$string =$files_list_array[ array_rand($files_list_array)];
		//} else {
		//	$string = "Error";
		//}
		
	return $string;
}



function RandomLine() {
	global $input_file_name;
	//$textfile = 'W:/imagetext_/'.$input_file_name;
	#$textfile = '/opt/lampp/htdocs/description_create_/'.$input_file_name;
	$textfile = './'.$input_file_name;
		if(file_exists($textfile)){ 
			$sites =file($textfile); 
			$string = $sites[array_rand($sites)];
		} else {
			$string = "Error";
		}
		
	return $string;
}
