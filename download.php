
<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include "db.php";


// if(isset($_SESSION['details']) && $_SESSION['details']==true)
//    { /*do nothing since query has ran once*/}
//    else
//    {
//    }
    /*run query since query has not been run*/
    $file = $_GET['file'];
   $sql1 = "select * from dokumen where namafile = '$file';";
//echo $sql1;
$sql = mysqli_query($conn,$sql1);
                 $row0 = mysqli_fetch_array($sql);
                 // Dokumen yang dinonaktifkan dari admin tidak boleh dibuka/diunduh
                 if ($row0 && (int)$row0['active'] !== 1) {
                     http_response_code(403);
                     echo "Dokumen ini sedang nonaktif.";
                     exit();
                 }
                 $jumlah_view = $row0 ? $row0['jumlah_view'] : 0;
                    $jumlah_view = $jumlah_view+1;
  $sql = mysqli_query($conn,"update dokumen SET jumlah_view =$jumlah_view where namafile = '$file';");
     // $_SESSION['details']=true; //you set a session variable to true when query runs the first time.
    


          
 //  $dir="H:/uploads/";
 //  $filename=$_GET['file'];
 //  $file_path=$dir.$filename;
 //  $ctype="application/octet-stream";
 //  //
 //  if(!empty($file_path) && file_exists($file_path)){ //check keberadaan file
 //  header("Pragma:public");
 //  header("Expired:0");
 //   header("Cache-Control:must-revalidate");
 //   header("Content-Control:public");
 //   header("Content-Description: File Transfer");
 //   header("Content-Type: $ctype");
 //   header("Content-Disposition:attachment; filename=\"".basename($file_path)."\"");
 //   header("Content-Transfer-Encoding:binary");
 //   header("Content-Length:".filesize($file_path));
 //   flush();
 //   readfile($file_path);
 //   exit();
 // }else{
 //   echo "The File does not exist.";
 // }



// Store the file name into variable
// $file = $_GET['file'];
// $filename = 'H:/uploads/$file.pdf';
  
// // Header content type
// header('Content-type: application/pdf');
  
// header('Content-Disposition: inline; filename="' . $filename . '"');
  
// header('Content-Transfer-Encoding: binary');
  
// header('Accept-Ranges: bytes');
  
// // Read the file
// @readfile($filename);




$path = "File_Dok_Lib/uploads/"; // change the path to fit your websites document structure
$fullPath = $path.$_GET['file'];

if ($fd = fopen ($fullPath, "r")) {
$fsize = filesize($fullPath);
$path_parts = pathinfo($fullPath);
$ext = strtolower($path_parts["extension"]);
switch ($ext) {
    case "pdf":
    header("Content-type: application/pdf"); // add here more headers for diff. extensions
    header("Content-Disposition: inline; filename=\"".$path_parts["basename"]."\"" );     
    break;
    default;
    header("Content-type: application/octet-stream");
    header("Content-Disposition: filename=\"".$path_parts["basename"]."\"");
}
header("Content-length: $fsize");
header("Cache-control: private"); //use this to open files directly
while(!feof($fd)) {
    $buffer = fread($fd, 2048);
    echo $buffer;
}
}
fclose ($fd);
exit;


 ?>



 
