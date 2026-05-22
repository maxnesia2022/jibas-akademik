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
$op = isset($_REQUEST['op']) ? $_REQUEST['op'] : '';

// =========================================================================
// PROSES AKSI
// =========================================================================

// Aksi: Ubah Status Aktif
if ($op == "dw8dxn8w9ms8zs22") {
	$sql = "UPDATE tingkat SET aktif = '$_REQUEST[newaktif]' WHERE replid = '$_REQUEST[replid]' ";
	QueryDb($sql);
} 
// Aksi: Hapus Tingkat
else if ($op == "xm8r389xemx23xb2378e23") {
	$sql = "DELETE FROM tingkat WHERE replid = '$_REQUEST[replid]'";
	QueryDb($sql);
}	
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tingkat</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <script type="text/javascript">
        function tambah() {
            var departemen = document.getElementById('departemen').value;
            window.open('tingkat_add.php?departemen='+encodeURIComponent(departemen), 'TambahTingkat', 'width=500,height=310,resizable=1,scrollbars=1');
        }

        function refresh() {	
            window.location.reload();
        }

        function tampil() {
            var departemen = document.getElementById('departemen').value;
            window.location.href = "tingkat.php?departemen=" + encodeURIComponent(departemen);
        }

        function setaktif(replid, aktif) {
            var departemen = document.getElementById('departemen').value;
            var msg, newaktif;
            
            if (aktif == 1) {
                msg = "Apakah anda yakin akan mengubah tingkat ini menjadi TIDAK AKTIF?";
                newaktif = 0;
            } else {	
                msg = "Apakah anda yakin akan mengubah tingkat ini menjadi AKTIF?";
                newaktif = 1;
            }
            
            if (confirm(msg)) 
                window.location.href = "tingkat.php?op=dw8dxn8w9ms8zs22&replid="+replid+"&newaktif="+newaktif+"&departemen="+encodeURIComponent(departemen);
        }

        function edit(replid) {
            window.open('tingkat_edit.php?replid='+replid, 'UbahTingkat', 'width=500,height=310,resizable=1,scrollbars=1');
        }

        function hapus(replid) {
            var departemen = document.getElementById('departemen').value;
            if (confirm("Apakah anda yakin akan menghapus tingkat ini?"))
                window.location.href = "tingkat.php?op=xm8r389xemx23xb2378e23&replid="+replid+"&departemen="+encodeURIComponent(departemen);
        }

        function cetak() {
            var departemen = document.getElementById('departemen').value;
            window.open('tingkat_cetak.php?departemen='+encodeURIComponent(departemen), 'CetakTingkat', 'width=790,height=650,resizable=1,scrollbars=1');
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
                <i class="fas fa-sitemap text-emerald-500"></i> Tingkat
            </h2>
            <div class="text-sm text-gray-500 hidden sm:block">
                Referensi <i class="fas fa-chevron-right text-xs mx-1"></i> Tingkat
            </div>
        </div>

        <div class="flex flex-col md:flex-row justify-between items-end gap-4">
            <!-- Departemen -->
            <div class="w-full md:w-1/3">
                <label class="block text-sm font-semibold text-gray-700 mb-1">Departemen</label>
                <select name="departemen" id="departemen" onchange="tampil()" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition">
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
                    <i class="fas fa-plus"></i> Tambah Tingkat
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
            $sql = "SELECT replid, tingkat, keterangan, aktif, urutan FROM tingkat WHERE departemen='$departemen' ORDER BY urutan";    
            $result = QueryDb($sql);
            $jumlah = @mysqli_num_rows($result);

            if ($jumlah > 0) {
            ?>
                <!-- Table Data -->
                <div class="overflow-x-auto rounded-lg border border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-emerald-50">
                            <tr>        
                                <th class="px-4 py-3 text-center text-xs font-bold text-emerald-800 uppercase tracking-wider w-12">No</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-emerald-800 uppercase tracking-wider w-32 text-center">Tingkat</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-emerald-800 uppercase tracking-wider">Keterangan</th>
                                <th class="px-4 py-3 text-center text-xs font-bold text-emerald-800 uppercase tracking-wider w-32">Status</th>
                                <th class="px-4 py-3 text-center text-xs font-bold text-emerald-800 uppercase tracking-wider w-24">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php 
                            $cnt = 0;
                            while ($row = @mysqli_fetch_array($result)) {
                            ?>    
                            <tr class="hover:bg-emerald-50 transition duration-150">                    
                                <td class="px-4 py-2 text-center text-gray-500"><?=++$cnt?></td>
                                <td class="px-4 py-2 text-gray-900 font-bold text-center text-lg"><?=$row['tingkat']?></td>
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
                            <?php } ?>            
                        </tbody>
                    </table>
                </div>
            <?php } else { ?>
                <!-- Empty State Data Kosong -->
                <div class="flex flex-col items-center justify-center p-16 text-center bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
                    <i class="fas fa-sitemap text-6xl text-gray-300 mb-4"></i>
                    <h3 class="text-xl font-bold text-gray-700 mb-2">Data Tidak Ditemukan</h3>
                    <p class="text-gray-500 max-w-md mb-6">
                        Tidak ditemukan data tingkat untuk departemen <strong><?=$departemen?></strong>.
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

<?php CloseDb(); ?>
</body>
</html>