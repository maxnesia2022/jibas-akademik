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
$departemen  = isset($_REQUEST['departemen']) ? $_REQUEST['departemen'] : '';
$tahunajaran = isset($_REQUEST['tahunajaran']) ? $_REQUEST['tahunajaran'] : '';
$tingkat     = isset($_REQUEST['tingkat']) ? $_REQUEST['tingkat'] : '';
$action      = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';

$varbaris = isset($_REQUEST['varbaris']) ? $_REQUEST['varbaris'] : 10;
$page     = isset($_REQUEST['page']) ? $_REQUEST['page'] : 0;
$hal      = isset($_REQUEST['hal']) ? $_REQUEST['hal'] : 0;
$urut     = isset($_REQUEST['urut']) ? $_REQUEST['urut'] : "kelas";	
$urutan   = isset($_REQUEST['urutan']) ? $_REQUEST['urutan'] : "ASC";	

// =========================================================================
// PROSES AKSI
// =========================================================================
$op = isset($_REQUEST['op']) ? $_REQUEST['op'] : '';

// Aksi: Ubah Status Aktif
if ($op == "dw8dxn8w9ms8zs22") {
	$sql = "UPDATE kelas SET aktif = '$_REQUEST[newaktif]' WHERE replid = '$_REQUEST[replid]' ";
	QueryDb($sql);
} 
// Aksi: Hapus Kelas
else if ($op == "xm8r389xemx23xb2378e23") {
	$sql = "DELETE FROM kelas WHERE replid = '$_REQUEST[replid]'";
	QueryDb($sql);
}

// Data Pendukung untuk Header
$nama_tingkat = "";
if (!empty($tingkat)) {
    $sql_get_tingkat = "SELECT tingkat FROM tingkat WHERE replid='$tingkat'";
    $result_get_tingkat = QueryDB($sql_get_tingkat);
    $row_get_tingkat = @mysqli_fetch_row($result_get_tingkat);
    $nama_tingkat = $row_get_tingkat[0];
}

$nama_tahunajaran = "";
if (!empty($tahunajaran)) {
    $sql_get_tahunajaran = "SELECT tahunajaran FROM tahunajaran WHERE replid='$tahunajaran'";  
    $result_get_tahunajaran = QueryDB($sql_get_tahunajaran);
    $row_get_tahunajaran = @mysqli_fetch_row($result_get_tahunajaran);
    $nama_tahunajaran = $row_get_tahunajaran[0];
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Kelas</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <script type="text/javascript">
        var base_url = "kelas.php";

        function change_departemen() {
            var departemen = document.getElementById("departemen").value;
            window.location.href = base_url + "?departemen=" + encodeURIComponent(departemen);
        }

        function change_filter() {
            var departemen = document.getElementById("departemen").value;
            var tingkat = document.getElementById("tingkat").value;
            var tahunajaran = document.getElementById("tahunajaran").value;
            window.location.href = base_url + "?departemen=" + encodeURIComponent(departemen) + "&tingkat=" + encodeURIComponent(tingkat) + "&tahunajaran=" + encodeURIComponent(tahunajaran);
        }

        function show_kelas() {
            var departemen = document.getElementById("departemen").value;
            var tingkat = document.getElementById("tingkat").value;
            var tahunajaran = document.getElementById("tahunajaran").value;
            
            if (departemen == "" || tingkat == "" || tahunajaran == "") {
                alert('Silakan pilih Departemen, Tahun Ajaran, dan Tingkat terlebih dahulu.');
                return false;
            }
            
            window.location.href = base_url + "?action=view&departemen=" + encodeURIComponent(departemen) + "&tingkat=" + encodeURIComponent(tingkat) + "&tahunajaran=" + encodeURIComponent(tahunajaran);
        }

        function carisiswa(replid) {	
            window.open('../library/lihatsiswa.php?replid='+replid, 'LihatSiswa', 'width=790,height=650,resizable=1,scrollbars=1');
        }

        function tambah() {
            var departemen = document.getElementById('departemen').value;
            var tahunajaran = document.getElementById('tahunajaran').value;
            var tingkat = document.getElementById('tingkat').value;
            window.open('kelas_add.php?departemen='+encodeURIComponent(departemen)+'&tingkat='+encodeURIComponent(tingkat)+'&tahunajaran='+encodeURIComponent(tahunajaran), 'TambahKelas', 'width=500,height=395,resizable=1,scrollbars=1');
        }

        function refresh_content() {
            show_kelas();
        }

        function setaktif(replid, aktif) {
            var departemen = document.getElementById('departemen').value;
            var tahunajaran = document.getElementById('tahunajaran').value;
            var tingkat = document.getElementById('tingkat').value;
            var urut = "<?=$urut?>";
            var urutan = "<?=$urutan?>";
            
            var msg, newaktif;
            if (aktif == 1) {
                msg = "Apakah anda yakin akan mengubah kelas ini menjadi TIDAK AKTIF?";
                newaktif = 0;
            } else {	
                msg = "Apakah anda yakin akan mengubah kelas ini menjadi AKTIF?";
                newaktif = 1;
            }
            
            if (confirm(msg)) 
                window.location.href = base_url + "?action=view&op=dw8dxn8w9ms8zs22&replid="+replid+"&newaktif="+newaktif+'&departemen='+encodeURIComponent(departemen)+'&tingkat='+encodeURIComponent(tingkat)+'&tahunajaran='+encodeURIComponent(tahunajaran)+'&urut='+urut+'&urutan='+urutan+"&page=<?=$page?>&hal=<?=$hal?>&varbaris=<?=$varbaris?>";
        }

        function edit(replid) {
            var departemen = document.getElementById('departemen').value;
            var tahunajaran = document.getElementById('tahunajaran').value;
            var tingkat = document.getElementById('tingkat').value;
            window.open('kelas_edit.php?replid='+replid+'&departemen='+encodeURIComponent(departemen)+'&tingkat='+encodeURIComponent(tingkat)+'&tahunajaran='+encodeURIComponent(tahunajaran), 'UbahKelas', 'width=500,height=395,resizable=1,scrollbars=1');
        }

        function hapus(replid) {
            var departemen = document.getElementById('departemen').value;
            var tahunajaran = document.getElementById('tahunajaran').value;
            var tingkat = document.getElementById('tingkat').value;
            
            if (confirm("Apakah anda yakin akan menghapus kelas ini?"))
                window.location.href = base_url + "?action=view&op=xm8r389xemx23xb2378e23&replid="+replid+"&departemen="+encodeURIComponent(departemen)+"&tahunajaran="+encodeURIComponent(tahunajaran)+"&tingkat="+encodeURIComponent(tingkat)+"&page=<?=$page?>&hal=<?=$hal?>&varbaris=<?=$varbaris?>";
        }

        function change_urut(urut_baru, urutan_lama) {		
            var departemen = document.getElementById('departemen').value;
            var tahunajaran = document.getElementById('tahunajaran').value;
            var tingkat = document.getElementById('tingkat').value;
            var varbaris = document.getElementById("varbaris").value;
            var urutan_baru = (urutan_lama == "ASC") ? "DESC" : "ASC";
            
            window.location.href = base_url + "?action=view&departemen="+encodeURIComponent(departemen)+"&tahunajaran="+encodeURIComponent(tahunajaran)+"&tingkat="+encodeURIComponent(tingkat)+"&urut="+urut_baru+"&urutan="+urutan_baru+"&page=<?=$page?>&hal=<?=$hal?>&varbaris="+varbaris;
        }

        function cetak() {
            var departemen = document.getElementById('departemen').value;
            var tahunajaran = document.getElementById('tahunajaran').value;
            var tingkat = document.getElementById('tingkat').value;
            var namatahunajaran = "<?=urlencode($nama_tahunajaran)?>";
            var namatingkat = "<?=urlencode($nama_tingkat)?>";
            var total = document.getElementById("total") ? document.getElementById("total").value : 0;
            
            window.open('kelas_cetak.php?departemen='+encodeURIComponent(departemen)+'&tingkat='+encodeURIComponent(tingkat)+'&tahunajaran='+encodeURIComponent(tahunajaran)+'&namatahunajaran='+namatahunajaran+'&namatingkat='+namatingkat+'&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris=<?=$varbaris?>&page=<?=$page?>&total='+total, 'CetakKelas', 'width=790,height=650,resizable=1,scrollbars=1');
        }

        function change_hal() {
            var departemen = document.getElementById('departemen').value;
            var tahunajaran = document.getElementById('tahunajaran').value;
            var tingkat = document.getElementById('tingkat').value;
            var hal = document.getElementById("hal").value;
            var varbaris = document.getElementById("varbaris").value;
            window.location.href = base_url + "?action=view&departemen="+encodeURIComponent(departemen)+"&tahunajaran="+encodeURIComponent(tahunajaran)+"&tingkat="+encodeURIComponent(tingkat)+"&page="+hal+"&hal="+hal+"&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris="+varbaris;
        }

        function change_baris() {
            var departemen = document.getElementById('departemen').value;
            var tahunajaran = document.getElementById('tahunajaran').value;
            var tingkat = document.getElementById('tingkat').value;
            var varbaris = document.getElementById("varbaris").value;
            window.location.href = base_url + "?action=view&departemen="+encodeURIComponent(departemen)+"&tahunajaran="+encodeURIComponent(tahunajaran)+"&tingkat="+encodeURIComponent(tingkat)+"&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris="+varbaris;
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
                <i class="fas fa-chalkboard text-emerald-500"></i> Manajemen Kelas
            </h2>
            <div class="text-sm text-gray-500 hidden sm:block">
                Referensi <i class="fas fa-chevron-right text-xs mx-1"></i> Kelas
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            <!-- Departemen -->
            <div>
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

            <!-- Tahun Ajaran -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Tahun Ajaran</label>
                <select name="tahunajaran" id="tahunajaran" onchange="change_filter()" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition">
                    <?php 
                    $sql_tahunajaran = "SELECT * FROM tahunajaran where departemen='$departemen' ORDER BY aktif DESC, tglmulai DESC";
                    $result_tahunajaran = QueryDb($sql_tahunajaran);
                    while ($row_tahunajaran = @mysqli_fetch_array($result_tahunajaran)) {
                        if ($tahunajaran == "") $tahunajaran = $row_tahunajaran['replid'];
                        $ada = $row_tahunajaran['aktif'] ? "(Aktif)" : "";	
                    ?>
                        <option value="<?=urlencode($row_tahunajaran['replid'])?>" <?=IntIsSelected($row_tahunajaran['replid'], $tahunajaran)?> >
                            <?=$row_tahunajaran['tahunajaran']." ".$ada?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <!-- Tingkat -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Tingkat</label>
                <select name="tingkat" id="tingkat" onchange="change_filter()" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition">
                    <?php 
                    $sql_tingkat = "SELECT * FROM tingkat where departemen='$departemen' AND aktif=1 ORDER BY urutan";
                    $result_tingkat = QueryDb($sql_tingkat);
                    while ($row_tingkat = @mysqli_fetch_array($result_tingkat)) {
                        if ($tingkat == "") $tingkat = $row_tingkat['replid'];
                    ?>
                        <option value="<?=urlencode($row_tingkat['replid'])?>" <?=IntIsSelected($row_tingkat['replid'], $tingkat)?> >
                            <?=$row_tingkat['tingkat']?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <!-- Tombol Tampilkan -->
            <div>
                <button type="button" onclick="show_kelas()" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-medium py-2 px-4 rounded-md shadow-sm transition duration-150 ease-in-out flex items-center justify-center gap-2">
                    <i class="fas fa-search"></i> Tampilkan
                </button>
            </div>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- BAGIAN 2: CONTENT AREA                                                    -->
    <!-- ========================================================================= -->
    <div class="bg-white rounded-lg shadow-sm border border-emerald-100 p-5">
        <?php if ($action == 'view' && !empty($tahunajaran) && !empty($tingkat)) { ?>
            <!-- ===================== MODE: TAMPILKAN DATA ===================== -->
            
            <?php
            // Query Total Data
            $sql_tot = "SELECT k.replid FROM kelas k, tahunajaran t, pegawai p WHERE t.replid='$tahunajaran' AND k.idtahunajaran=t.replid AND k.nipwali=p.nip AND t.departemen='$departemen' AND k.idtingkat='$tingkat' GROUP BY k.replid";
            $result_tot = QueryDb($sql_tot);
            $jumlah = @mysqli_num_rows($result_tot);
            $total = ceil($jumlah / (int)$varbaris);
            
            // Query Data
            $sql_kelas = "SELECT k.replid, k.kelas, k.idtahunajaran, k.kapasitas, k.nipwali, k.aktif, k.keterangan, t.replid, t.tahunajaran, t.departemen, p.nama 
                          FROM kelas k, tahunajaran t, pegawai p 
                          WHERE t.replid='$tahunajaran' AND k.idtahunajaran=t.replid AND k.nipwali=p.nip AND t.departemen='$departemen' AND k.idtingkat='$tingkat' 
                          GROUP BY k.replid ORDER BY $urut $urutan LIMIT ".(int)$page*(int)$varbaris.",$varbaris";
            $result_kelas = QueryDb($sql_kelas);

            if ($jumlah > 0) {
            ?>
                <input type="hidden" name="total" id="total" value="<?=$total?>"/>

                <!-- Action Toolbar -->
                <div class="flex flex-col md:flex-row justify-between items-center mb-4 gap-4">
                    <div class="text-sm text-gray-600">
                        Ditemukan <span class="font-bold text-gray-900"><?=$jumlah?></span> kelas pada <span class="text-emerald-700 font-semibold"><?=$nama_tingkat?> (<?=$nama_tahunajaran?>)</span>
                    </div>
                    
                    <div class="flex flex-wrap gap-2 w-full md:w-auto">
                        <button onclick="refresh_content()" class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium py-1.5 px-3 rounded shadow-sm border border-gray-300 transition flex items-center gap-2">
                            <i class="fas fa-sync-alt"></i> Refresh
                        </button>
                        <button onclick="cetak()" class="bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium py-1.5 px-3 rounded shadow-sm transition flex items-center gap-2">
                            <i class="fas fa-print"></i> Cetak
                        </button>
                        <?php if (SI_USER_LEVEL() != $SI_USER_STAFF) { ?>
                        <button onclick="tambah()" class="bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium py-1.5 px-3 rounded shadow-sm transition flex items-center gap-2">
                            <i class="fas fa-plus"></i> Tambah Kelas
                        </button>
                        <?php } ?>
                    </div>
                </div>

                <!-- Table Data -->
                <div class="overflow-x-auto rounded-lg border border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-emerald-50">
                            <tr>        
                                <th class="px-4 py-3 text-center text-xs font-bold text-emerald-800 uppercase tracking-wider w-12">No</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-emerald-800 uppercase tracking-wider cursor-pointer hover:bg-emerald-100 transition" onClick="change_urut('kelas','<?=$urutan?>')">
                                    Kelas <i class="fas fa-sort text-emerald-300 ml-1"></i>
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-emerald-800 uppercase tracking-wider cursor-pointer hover:bg-emerald-100 transition" onClick="change_urut('p.nama','<?=$urutan?>')">
                                    Wali Kelas <i class="fas fa-sort text-emerald-300 ml-1"></i>
                                </th>
                                <th class="px-4 py-3 text-center text-xs font-bold text-emerald-800 uppercase tracking-wider w-24">Kapasitas</th>
                                <th class="px-4 py-3 text-center text-xs font-bold text-emerald-800 uppercase tracking-wider w-24">Terisi</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-emerald-800 uppercase tracking-wider">Keterangan</th>
                                <th class="px-4 py-3 text-center text-xs font-bold text-emerald-800 uppercase tracking-wider w-32 cursor-pointer hover:bg-emerald-100 transition" onClick="change_urut('k.aktif','<?=$urutan?>')">
                                    Status <i class="fas fa-sort text-emerald-300 ml-1"></i>
                                </th>
                                <th class="px-4 py-3 text-center text-xs font-bold text-emerald-800 uppercase tracking-wider w-24">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php 
                            $cnt = ($page == 0) ? 1 : ((int)$page * (int)$varbaris + 1);
                            while ($row_kelas = @mysqli_fetch_row($result_kelas)) {
                                // Hitung jumlah siswa terisi
                                $sql_get_jumsiswa = "SELECT COUNT(*) FROM siswa s WHERE s.idkelas='$row_kelas[0]' AND s.aktif=1";
                                $result_get_jumsiswa = QueryDB($sql_get_jumsiswa);
                                $row_get_jumsiswa = mysqli_fetch_row($result_get_jumsiswa);
                                $terisi = $row_get_jumsiswa[0];
                            ?>    
                            <tr class="hover:bg-emerald-50 transition duration-150">                    
                                <td class="px-4 py-2 text-center text-gray-500"><?=$cnt?></td>
                                <td class="px-4 py-2 text-gray-900 font-bold text-lg"><?=$row_kelas[1]?></td>
                                <td class="px-4 py-2 text-gray-700">
                                    <div class="flex flex-col">
                                        <span class="font-medium"><?=$row_kelas[10]?></span>
                                        <span class="text-xs text-gray-400"><?=$row_kelas[4]?></span>
                                    </div>
                                </td>
                                <td class="px-4 py-2 text-center text-gray-800 font-semibold"><?=$row_kelas[3]?></td>
                                <td class="px-4 py-2 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <span class="text-orange-600 font-bold"><?=$terisi?></span>
                                        <?php if ($terisi > 0) { ?>
                                            <button onclick="carisiswa(<?=$row_kelas[0]?>)" class="text-blue-500 hover:text-blue-700 transition" title="Lihat Daftar Siswa">
                                                <i class="fas fa-users text-xs"></i>
                                            </button>
                                        <?php } ?>
                                    </div>
                                </td>
                                <td class="px-4 py-2 text-gray-600 text-xs italic"><?=$row_kelas[6]?></td>
                                
                                <!-- Kolom Status -->
                                <td class="px-4 py-2 text-center">
                                    <?php 
                                    if (SI_USER_LEVEL() == $SI_USER_STAFF) {  
                                        if ($row_kelas[5] == 1) {
                                            echo '<span class="px-2 py-1 inline-flex text-xs leading-4 font-semibold rounded-full bg-emerald-100 text-emerald-800">Aktif</span>';
                                        } else {
                                            echo '<span class="px-2 py-1 inline-flex text-xs leading-4 font-semibold rounded-full bg-red-100 text-red-800">Tidak Aktif</span>';
                                        }
                                    } else { 
                                        if ($row_kelas[5] == 1) { ?>
                                            <button onclick="setaktif(<?=$row_kelas[0]?>, <?=$row_kelas[5]?>)" class="px-2 py-1 inline-flex text-xs leading-4 font-semibold rounded-full bg-emerald-100 text-emerald-800 hover:bg-emerald-200 transition" title="Klik untuk Non-Aktifkan">Aktif</button>
                                        <?php } else { ?>
                                            <button onclick="setaktif(<?=$row_kelas[0]?>, <?=$row_kelas[5]?>)" class="px-2 py-1 inline-flex text-xs leading-4 font-semibold rounded-full bg-gray-100 text-gray-600 hover:bg-gray-200 transition" title="Klik untuk Aktifkan">Tidak Aktif</button>
                                        <?php }
                                    } ?>            
                                </td>
                                
                                <!-- Kolom Aksi -->
                                <td class="px-4 py-2 text-center flex justify-center gap-3">
                                    <?php if (SI_USER_LEVEL() != $SI_USER_STAFF) { ?>                
                                        <button onclick="edit(<?=$row_kelas[0]?>)" class="text-amber-500 hover:text-amber-700 transition" title="Ubah">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button onclick="hapus(<?=$row_kelas[0]?>)" class="text-red-500 hover:text-red-700 transition" title="Hapus">
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
                    <i class="fas fa-chalkboard text-6xl text-gray-300 mb-4"></i>
                    <h3 class="text-xl font-bold text-gray-700 mb-2">Data Tidak Ditemukan</h3>
                    <p class="text-gray-500 max-w-md mb-6">
                        Tidak ditemukan data kelas untuk kriteria yang dipilih.
                    </p>
                    <?php if (SI_USER_LEVEL() != $SI_USER_STAFF) { ?>
                    <button onclick="tambah()" class="bg-emerald-600 hover:bg-emerald-700 text-white font-medium py-2 px-6 rounded shadow transition flex items-center gap-2">
                        <i class="fas fa-plus"></i> Isi Data Baru
                    </button>
                    <?php } ?>
                </div>
            <?php } ?>

        <?php } else { ?>
            <!-- Mode: Blank State -->
            <div class="flex flex-col items-center justify-center p-16 text-center bg-emerald-50 rounded-lg border border-emerald-100">
                <i class="fas fa-chalkboard-teacher text-6xl text-emerald-200 mb-4"></i>
                <h3 class="text-xl font-bold text-emerald-800 mb-2">Pilih Kriteria Kelas</h3>
                <p class="text-emerald-600 max-w-md">
                    Silakan tentukan <strong>Departemen, Tahun Ajaran,</strong> dan <strong>Tingkat</strong> pada form di atas, lalu klik tombol <strong class="text-emerald-800"><i class="fas fa-search"></i> Tampilkan</strong> untuk melihat daftar kelas.
                </p>
            </div>
        <?php } ?>
    </div>

</div>

<?php CloseDb(); ?>
</body>
</html>