<?php
include "db.php";

$file = $_POST['namafile'];

$sql1 = "select * from dokumen where namafile = '$file';";
//echo $sql1;
$sql = mysqli_query($conn,$sql1);
                 while($row=mysqli_fetch_array($sql))
                    {
                        $jumlah_view = $row['jumlah_view'];
                    }
                    $jumlah_view = $jumlah_view+1;
  $sql = mysqli_query($conn,"update dokumen SET jumlah_view =$jumlah_view where namafile = '$file';");    



  ?>