<?php
// =========================================================================
// INIT & INCLUDE FILES
// =========================================================================
require_once('../include/errorhandler.php');
require_once('../include/sessioninfo.php');
require_once('../include/common.php');
require_once('../include/config.php');
require_once('../include/db_functions.php');
require_once('../library/departemen.php');
require_once('../cek.php');

OpenDb();

// =========================================================================
// PENANGKAPAN PARAMETER
// =========================================================================
$departemen = isset($_REQUEST['departemen']) ? $_REQUEST['departemen'] : '';
$varbaris = isset($_REQUEST['varbaris']) ? $_REQUEST['varbaris'] : 10;
$page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 0;
$hal = isset($_REQUEST['hal']) ? $_REQUEST['hal'] : 0;
$urut = isset($_REQUEST['urut']) ? $_REQUEST['urut'] : "tahunajaran";	
$urutan = isset($_REQUEST['urutan']) ? $_REQUEST['urutan'] : "DESC";	
$op = isset($_REQUEST['op']) ? $_REQUEST['op'] : '';

$ERROR_MSG = "";

// =========================================================================
// PROSES AKSI (LOGIKA PHP ASLI)
// =========================================================================

// Aksi: Add / Update
if (isset($_REQUEST['action'])) {
	$tglmulai = TglDb($_REQUEST['tglmulai']);
	$tglakhir = TglDb($_REQUEST['tglakhir']);
	$filter = "";
	if ($_REQUEST['action'] == "update") {
		$replid = $_REQUEST['replid'];
		$filter = "AND replid <> '$replid'";
	}
	
	$sql = "SELECT * FROM tahunajaran WHERE departemen = '$_REQUEST[departemen]' AND tahunajaran='$_REQUEST[tahunajaran]' $filter";
	$result = QueryDb($sql);
	
	if (mysqli_num_rows($result) > 0) {
		$ERROR_MSG = "Gagal menyimpan data Tahun Ajaran $_REQUEST[tahunajaran] sudah digunakan!";
	} else {
		if ($_REQUEST['action'] == "add") {
			$sql = "INSERT INTO tahunajaran SET tahunajaran='".CQ($_REQUEST['tahunajaran'])."',departemen='$_REQUEST[departemen]',tglmulai='$tglmulai',tglakhir='$tglakhir',keterangan='".CQ($_REQUEST['keterangan'])."'";
			$result = QueryDb($sql);
			$sql1 = "SELECT LAST_INSERT_ID(replid) FROM tahunajaran ORDER BY replid DESC LIMIT 1";		
			$result1 = QueryDb($sql1);		
			$row1 = mysqli_fetch_row($result1);
			$replid = $row1[0];	 
			
			$sql2 = "UPDATE tahunajaran SET aktif = 0 WHERE replid <> '$replid' AND departemen = '$_REQUEST[departemen]'";
			QueryDb($sql2);	
			
		} else {
			$sql = "UPDATE tahunajaran SET tahunajaran = '".CQ($_REQUEST['tahunajaran'])."', tglmulai = '$tglmulai', tglakhir = '$tglakhir', keterangan = '".CQ($_REQUEST['keterangan'])."' WHERE replid = '$replid'";
			$result = QueryDb($sql);			
		}
		
		if ($result) {
			echo "<script>window.location.href = 'tahunajaran.php?departemen=".urlencode($_REQUEST['departemen'])."';</script>";
            exit;
		}
	}
}

// Aksi: Set Aktif
if ($op == "dw8dxn8w9ms8zs22") {
	$sql = "UPDATE tahunajaran SET aktif = '$_REQUEST[newaktif]' WHERE replid = '$_REQUEST[replid]' ";
	QueryDb($sql);
	$sql1 = "UPDATE tahunajaran SET aktif = 0 WHERE replid <> '$_REQUEST[replid]' AND departemen = '$_REQUEST[departemen]'";
	QueryDb($sql1);
} 
// Aksi: Hapus
else if ($op == "hapus") {
	$sql = "DELETE FROM tahunajaran WHERE replid = '$_REQUEST[replid]'";
	QueryDb($sql);
    $page = 0;
    $hal = 0;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tahun Ajaran</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <script type="text/javascript">
        var base_url = "tahunajaran.php";

        function change_departemen() {
            var departemen = document.getElementById("departemen").value;
            window.location.href = base_url + "?departemen=" + encodeURIComponent(departemen);
        }

        function tambah() {
            var departemen = document.getElementById("departemen").value;
            window.open('tahunajaran_add.php?departemen='+encodeURIComponent(departemen), 'Tambahtahunajaran', 'width=500,height=340,resizable=1,scrollbars=1');
        }

        function refresh() {
            var departemen = document.getElementById("departemen").value;
            window.location.href = base_url + "?departemen=" + encodeURIComponent(departemen);
        }

        function setaktif(replid, aktif) {
            var departemen = document.getElementById("departemen").value;
            var msg, newaktif;
            
            if (aktif == 1) {
                msg = "Apakah anda yakin akan mengubah Tahun Ajaran ini menjadi TIDAK AKTIF?";
                newaktif = 0;
            } else {	
                msg = "Apakah anda yakin akan mengubah Tahun Ajaran ini menjadi AKTIF?";
                newaktif = 1;
            }
            
            if (confirm(msg)) 
                window.location.href = base_url + "?op=dw8dxn8w9ms8zs22&replid="+replid+"&newaktif="+newaktif+"&departemen="+encodeURIComponent(departemen)+"&page=<?=$page?>&hal=<?=$hal?>&varbaris=<?=$varbaris?>&urut=<?=$urut?>&urutan=<?=$urutan?>";
        }

        function edit(replid) {
            window.open('tahunajaran_edit.php?replid='+replid, 'UbahTahunAjaran', 'width=500,height=340,resizable=1,scrollbars=1');
        }

        function hapus(replid) {
            var departemen = document.getElementById("departemen").value;
            if (confirm("Apakah anda yakin akan menghapus tahun ajaran ini?"))
                window.location.href = base_url + "?op=hapus&replid="+replid+'&departemen='+encodeURIComponent(departemen)+"&urut=<?=$urut?>&urutan=<?=$urutan?>&page=<?=$page?>&hal=<?=$hal?>&varbaris=<?=$varbaris?>";
        }

        function cetak() {
            var departemen = document.getElementById("departemen").value;
            var total = document.getElementById("total").value;
            window.open('tahunajaran_cetak.php?departemen='+encodeURIComponent(departemen)+'&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris=<?=$varbaris?>&page=<?=$page?>&total='+total, 'CetakTahunAjaran', 'width=790,height=650,resizable=1,scrollbars=1');
        }

        function change_urut(urut_baru, urutan_lama) {	
            var departemen = document.getElementById('departemen').value;	
            var varbaris = document.getElementById("varbaris").value;
            var urutan_baru = (urutan_lama == "ASC") ? "DESC" : "ASC";
            window.location.href = base_url + "?departemen="+encodeURIComponent(departemen)+"&urut="+urut_baru+"&urutan="+urutan_baru+"&page=<?=$page?>&hal=<?=$hal?>&varbaris="+varbaris;
        }

        function change_hal() {
            var departemen = document.getElementById("departemen").value;
            var hal = document.getElementById("hal").value;
            var varbaris = document.getElementById("varbaris").value;
            window.location.href = base_url + "?departemen="+encodeURIComponent(departemen)+"&page="+hal+"&hal="+hal+"&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris="+varbaris;
        }

        function change_baris() {
            var departemen = document.getElementById("departemen").value;
            var varbaris = document.getElementById("varbaris").value;
            window.location.href = base_url + "?departemen="+encodeURIComponent(departemen)+"&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris="+varbaris;
        }
    </script>
</head>
<body class="bg-gray-100 font-sans p-4" onload="document.getElementById('departemen').focus()">

<div class="max-w-7xl mx-auto space-y-4">

    <!-- ========================================================================= -->
    <!-- BAGIAN 1: HEADER / FILTER                                                 -->
    <!-- ========================================================================= -->
    <div class="bg-white rounded-lg shadow-sm border border-emerald-100 p-5">
        
        <!-- Breadcrumb / Title -->
        <div class="flex justify-between items-center mb-4 border-b border-gray-100 pb-3">
            <h2 class="text-xl font-bold text-emerald-900 flex items-center gap-2">
                <i class="fas fa-calendar-check text-emerald-500"></i> Tahun Ajaran
            </h2>
            <div class="text-sm text-gray-500 hidden sm:block">
                Referensi <i class="fas fa-chevron-right text-xs mx-1"></i> Tahun Ajaran
            </div>
        </div>

        <div class="flex flex-col md:flex-row justify-between items-end gap-4">
            <!-- Departemen -->
            <div class="w-full md:w-1/3">
                <label class="block text-sm font-semibold text-gray-700 mb-1">Departemen</label>
                <select name="departemen" id="departemen" onchange="change_departemen()" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition">
                    <?php    
                    $dep = getDepartemen(SI_USER_ACCESS());    
                    foreach($dep as $value) {
                        if ($departemen == "") $departemen = $value; 
                        $selected = ($value == $departemen) ? "selected" : "";
                    ?>
                        <option value="<?=$value?>" <?=$selected?>><?=$value?></option>
                    <?php } ?>
                </select>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-wrap gap-2 w-full md:w-auto">
                <button onclick="refresh()" class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium py-1.5 px-3 rounded shadow-sm border border-gray-300 transition flex items-center justify-center gap-2">
                    <i class="fas fa-sync-alt"></i> Refresh
                </button>
                <button onclick="cetak()" class="bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium py-1.5 px-3 rounded shadow-sm transition flex items-center justify-center gap-2">
                    <i class="fas fa-print"></i> Cetak
                </button>
                <?php if (SI_USER_LEVEL() != $SI_USER_STAFF) { ?>
                <button onclick="tambah()" class="bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium py-1.5 px-3 rounded shadow-sm transition flex items-center justify-center gap-2">
                    <i class="fas fa-plus"></i> Tambah Tahun Ajaran
                </button>
                <?php } ?>
            </div>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- BAGIAN 2: CONTENT AREA                                                    -->
    <!-- ========================================================================= -->
    <div class="bg-white rounded-lg shadow-sm border border-emerald-100 p-5">
        <?php
        if ($departemen != "") {
            // Query Total
            $sql_tot = "SELECT COUNT(*) FROM tahunajaran WHERE departemen='$departemen'";
            $result_tot = QueryDb($sql_tot);
            $row_tot = mysqli_fetch_row($result_tot);
            $jumlah = $row_tot[0];
            $total = ceil($jumlah / (int)$varbaris);
            
            // Query Data
            $sql = "SELECT * FROM tahunajaran WHERE departemen='$departemen' ORDER BY $urut $urutan LIMIT ".(int)$page*(int)$varbaris.",$varbaris";
            $result = QueryDb($sql);

            if ($jumlah > 0) {
            ?>
                <input type="hidden" name="total" id="total" value="<?=$total?>"/>
                
                <div class="text-sm text-gray-600 mb-4">
                    Menampilkan <span class="font-bold text-gray-900"><?=$jumlah?></span> data tahun ajaran.
                </div>

                <!-- Table Data -->
                <div class="overflow-x-auto rounded-lg border border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-emerald-50">
                            <tr>        
                                <th class="px-4 py-3 text-center text-xs font-bold text-emerald-800 uppercase tracking-wider w-12">No</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-emerald-800 uppercase tracking-wider cursor-pointer hover:bg-emerald-100 transition" onClick="change_urut('tahunajaran','<?=$urutan?>')">
                                    Tahun Ajaran <i class="fas fa-sort text-emerald-300 ml-1"></i>
                                </th>
                                <th class="px-4 py-3 text-center text-xs font-bold text-emerald-800 uppercase tracking-wider cursor-pointer hover:bg-emerald-100 transition" onClick="change_urut('tglmulai','<?=$urutan?>')">
                                    Tgl Mulai <i class="fas fa-sort text-emerald-300 ml-1"></i>
                                </th>
                                <th class="px-4 py-3 text-center text-xs font-bold text-emerald-800 uppercase tracking-wider cursor-pointer hover:bg-emerald-100 transition" onClick="change_urut('tglakhir','<?=$urutan?>')">
                                    Tgl Akhir <i class="fas fa-sort text-emerald-300 ml-1"></i>
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-emerald-800 uppercase tracking-wider">Keterangan</th>
                                <th class="px-4 py-3 text-center text-xs font-bold text-emerald-800 uppercase tracking-wider w-32">Status</th>
                                <th class="px-4 py-3 text-center text-xs font-bold text-emerald-800 uppercase tracking-wider w-24">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php 
                            $cnt = ($page == 0) ? 1 : ((int)$page * (int)$varbaris + 1);
                            while ($row = mysqli_fetch_array($result)) {
                            ?>    
                            <tr class="hover:bg-emerald-50 transition duration-150">                    
                                <td class="px-4 py-2 text-center text-gray-500"><?=$cnt?></td>
                                <td class="px-4 py-2 text-gray-900 font-bold"><?=$row['tahunajaran']?></td>
                                <td class="px-4 py-2 text-center text-gray-600"><?=format_tgl($row['tglmulai'])?></td>
                                <td class="px-4 py-2 text-center text-gray-600"><?=format_tgl($row['tglakhir'])?></td>
                                <td class="px-4 py-2 text-gray-600"><?=$row['keterangan']?></td>
                                
                                <!-- Kolom Status -->
                                <td class="px-4 py-2 text-center">
                                    <?php 
                                    if (SI_USER_LEVEL() == $SI_USER_STAFF) {  
                                        if ($row['aktif'] == 1) {
                                            echo '<span class="px-2 py-1 inline-flex text-xs leading-4 font-semibold rounded-full bg-emerald-100 text-emerald-800">Aktif</span>';
                                        } else {
                                            echo '<span class="px-2 py-1 inline-flex text-xs leading-4 font-semibold rounded-full bg-red-100 text-red-800">Tidak Aktif</span>';
                                        }
                                    } else { 
                                        if ($row['aktif'] == 1) { ?>
                                            <button onclick="setaktif(<?=$row['replid']?>, <?=$row['aktif']?>)" class="px-2 py-1 inline-flex text-xs leading-4 font-semibold rounded-full bg-emerald-100 text-emerald-800 hover:bg-emerald-200 transition" title="Klik untuk Non-Aktifkan">Aktif</button>
                                        <?php } else { ?>
                                            <button onclick="setaktif(<?=$row['replid']?>, <?=$row['aktif']?>)" class="px-2 py-1 inline-flex text-xs leading-4 font-semibold rounded-full bg-gray-100 text-gray-600 hover:bg-gray-200 transition" title="Klik untuk Aktifkan">Tidak Aktif</button>
                                        <?php }
                                    } ?>            
                                </td>
                                
                                <!-- Kolom Aksi -->
                                <td class="px-4 py-2 text-center flex justify-center gap-3">
                                    <?php if (SI_USER_LEVEL() != $SI_USER_STAFF) { ?>                
                                        <button onclick="edit(<?=$row['replid']?>)" class="text-amber-500 hover:text-amber-700 transition" title="Ubah">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button onclick="hapus(<?=$row['replid']?>)" class="text-red-500 hover:text-red-700 transition" title="Hapus">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    <?php } ?>
                                </td>
                            </tr>
                            <?php $cnt++; } ?>            
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Area -->
                <div class="mt-4 flex flex-col sm:flex-row items-center justify-between gap-4 text-sm bg-gray-50 p-3 rounded-lg border border-gray-200">
                    <div class="flex items-center gap-2">
                        <span class="text-gray-600">Halaman</span>
                        <select name="hal" id="hal" onChange="change_hal()" class="border border-gray-300 rounded px-2 py-1 bg-white focus:ring-2 focus:ring-emerald-500 outline-none">
                        <?php for ($m=0; $m<$total; $m++) {?>
                            <option value="<?=$m?>" <?=IntIsSelected($hal,$m)?>><?=$m+1?></option>
                        <?php } ?>
                        </select>
                        <span class="text-gray-600">dari <?=$total?> halaman</span>
                    </div>
                    
                    <div class="flex items-center gap-2">
                        <span class="text-gray-600">Tampilkan</span>
                        <select name="varbaris" id="varbaris" onChange="change_baris()" class="border border-gray-300 rounded px-2 py-1 bg-white focus:ring-2 focus:ring-emerald-500 outline-none">
                        <?php for ($m=5; $m <= 100; $m=$m+5) { ?>
                            <option value="<?=$m?>" <?=IntIsSelected($varbaris,$m)?>><?=$m?></option>
                        <?php } ?>
                        </select>
                        <span class="text-gray-600">baris per halaman</span>
                    </div>
                </div>

            <?php } else { ?>
                <!-- Empty State Data Kosong -->
                <div class="flex flex-col items-center justify-center p-16 text-center bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
                    <i class="fas fa-calendar-times text-6xl text-gray-300 mb-4"></i>
                    <h3 class="text-xl font-bold text-gray-700 mb-2">Data Tidak Ditemukan</h3>
                    <p class="text-gray-500 max-w-md mb-6">
                        Tidak ditemukan data tahun ajaran untuk departemen <strong><?=$departemen?></strong>.
                    </p>
                    <?php if (SI_USER_LEVEL() != $SI_USER_STAFF) { ?>
                    <button onclick="tambah()" class="bg-emerald-600 hover:bg-emerald-700 text-white font-medium py-2 px-6 rounded shadow transition flex items-center gap-2">
                        <i class="fas fa-plus"></i> Isi Data Baru
                    </button>
                    <?php } ?>
                </div>
            <?php } 
        } else { ?>
            <!-- Departemen Belum Terisi -->
            <div class="flex flex-col items-center justify-center p-16 text-center bg-amber-50 rounded-lg border border-amber-100">
                <i class="fas fa-exclamation-triangle text-6xl text-amber-200 mb-4"></i>
                <h3 class="text-xl font-bold text-amber-800 mb-2">Departemen Belum Ada</h3>
                <p class="text-amber-600 max-w-md">
                    Belum ada data Departemen. Silakan isi terlebih dahulu di menu <strong>Departemen</strong> pada bagian Referensi.
                </p>
            </div>
        <?php } ?>
    </div>

</div>

<?php if (strlen($ERROR_MSG) > 0) { ?>
<script language="javascript">
	alert('<?=$ERROR_MSG?>');
</script>
<?php } ?>

<?php CloseDb(); ?>
</body>
</html>