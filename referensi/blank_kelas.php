<?
 ?>
<? 
require_once('../include/sessioninfo.php');
require_once('../include/common.php');
require_once('../include/config.php');
require_once('../include/db_functions.php');
?>
<html>
<head>
<title>Untitled Document</title>
<link rel="stylesheet" type="text/css"  href="../style/style.css">
</head>
<body>
<table width="100%" border="0" height="100%">
<tr>
    <!--<td align="center" valign="middle" background="../images/ico/b_kelas.png"
    style="background-repeat:no-repeat;" width="20%"><br><br>
    </td>-->
    <td align="center">
    <? 	OpenDb();		
		$sql = "SELECT * FROM departemen";    
		$result = QueryDb($sql);
		if (@mysqli_num_rows($result) > 0){
	?>	
        <font size="2" color="#757575"><b>Klik pada icon <img src="../images/ico/view_x.png" border="0"> di atas untuk melihat kelas sesuai dengan Departemen, Tingkat dan Tahun Ajaran terpilih</b></font>    
   	<? } else { ?>
    	<font size = "2" color ="red"><b>Belum ada data Departemen.
        <br />Silahkan isi terlebih dahulu di menu Departemen pada bagian Referensi.
        </b></font>  
    <? } ?>     
   	</td>
</tr>
</table>
</body>
</html>