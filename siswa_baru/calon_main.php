<?php
// =========================================================================
// INIT & INCLUDE FILES
// =========================================================================
require_once('../include/errorhandler.php');
require_once('../include/sessioninfo.php');
require_once('../include/common.php');
require_once('../include/config.php');
require_once('../include/db_functions.php');
require_once('../include/rupiah.php');
require_once('../library/departemen.php');
require_once('../cek.php');

OpenDb();

// =========================================================================
// PENANGKAPAN PARAMETER
// =========================================================================
$departemen = isset($_REQUEST['departemen']) ? $_REQUEST['departemen'] : '';
$proses     = isset($_REQUEST['proses']) ? $_REQUEST['proses'] : '';
$kelompok   = isset($_REQUEST['kelompok']) ? $_REQUEST['kelompok'] : '';
$action     = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';

$varbaris = isset($_REQUEST['varbaris']) ? $_REQUEST['varbaris'] : 20;
$page     = isset($_REQUEST['page']) ? $_REQUEST['page'] : 0;
$hal      = isset($_REQUEST['hal']) ? $_REQUEST['hal'] : 0;
$urut     = isset($_REQUEST['urut']) ? $_REQUEST['urut'] : "nama";	
$urutan   = isset($_REQUEST['urutan']) ? $_REQUEST['urutan'] : "ASC";	

// =========================================================================
// PROSES AKSI
// =========================================================================
$op = isset($_REQUEST['op']) ? $_REQUEST['op'] : '';

// Aksi: Ubah Status Aktif
if ($op == "dw8dxn8w9ms8zs22") {
	$sql = "UPDATE calonsiswa SET aktif = '$_REQUEST[newaktif]' WHERE replid = '$_REQUEST[replid]' ";
	QueryDb($sql);
} 
// Aksi: Hapus Calon Siswa
else if ($op == "xm8r389xemx23xb2378e23") {
    $sql = "SELECT nopendaftaran FROM calonsiswa WHERE replid = '$_REQUEST[replid]'";
    $res = QueryDb($sql);
    $row = mysqli_fetch_row($res);
    $no = $row[0];

    $sql = "DELETE FROM tambahandatacalon WHERE nopendaftaran = '$no'";
    QueryDb($sql);

    // --- v31 - 2025-05-26
    $sql = "DELETE FROM riwayatfoto WHERE nic = '$no'";
    QueryDb($sql);

	$sql = "DELETE FROM calonsiswa WHERE replid = '$_REQUEST[replid]'"; 	
	QueryDb($sql);

	$page = 0;
	$hal = 0;
}

// Data Pendukung untuk Header
$namaproses = "";
$proses_id_aktif = "";
if (!empty($departemen)) {
    $sql_p = "SELECT replid, proses FROM prosespenerimaansiswa WHERE aktif=1 AND departemen='$departemen'";				
    $result_p = QueryDb($sql_p);
    if (mysqli_num_rows($result_p) > 0) {
        $row_p = mysqli_fetch_array($result_p);
        $proses_id_aktif = $row_p['replid'];
        $namaproses = $row_p['proses'];
    }
}
if (empty($proses)) $proses = $proses_id_aktif;

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendataan Calon Siswa</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <script type="text/javascript">
        var base_url = "calon_main.php";

        function change_dep() {
            var departemen = document.getElementById("departemen").value;
            window.location.href = base_url + "?departemen=" + encodeURIComponent(departemen);
        }

        function change_kelompok() {	
            var departemen = document.getElementById("departemen").value;
            var proses = document.getElementById("proses").value;
            var kelompok = document.getElementById("kelompok").value;	
            window.location.href = base_url + "?departemen=" + encodeURIComponent(departemen) + "&proses=" + proses + "&kelompok=" + kelompok;
        }

        function show_calon() {	
            var departemen = document.getElementById("departemen").value;
            var proses = document.getElementById("proses").value;
            var kelompok = document.getElementById("kelompok").value;	
            
            if (proses == "") {
                alert('Pastikan Proses Penerimaan ada dan statusnya aktif!');	
                return false;
            }	
            if (kelompok == "") {
                alert('Kelompok Calon Siswa tidak boleh kosong!');	
                return false;
            }	
            window.location.href = base_url + "?action=view&departemen=" + encodeURIComponent(departemen) + "&proses=" + proses + "&kelompok=" + kelompok;
        }

        function refresh_content() {
            show_calon();
        }

        function tambah() {
            var departemen = document.getElementById('departemen').value;
            var proses = document.getElementById('proses').value;
            var kelompok = document.getElementById('kelompok').value;
            window.open('calon_add.php?departemen='+departemen+'&proses='+proses+'&kelompok='+kelompok, 'TambahCalonSiswa', 'width=825,height=650,resizable=1,scrollbars=1');
        }

        function edit(replid) {
            window.open('calon_edit.php?replid='+replid, 'UbahPendataanCalonSiswa', 'width=825,height=650,resizable=1,scrollbars=1');
        }

        function setaktif(replid, aktif) {
            var departemen = document.getElementById('departemen').value;	
            var proses = document.getElementById('proses').value;	
            var kelompok = document.getElementById('kelompok').value;
            var msg, newaktif;
            
            if (aktif == 1) {
                msg = "Apakah anda yakin akan mengubah calon siswa ini menjadi TIDAK AKTIF?";
                newaktif = 0;
            } else {	
                msg = "Apakah anda yakin akan mengubah calon siswa ini menjadi AKTIF?";
                newaktif = 1;
            }
            
            if (confirm(msg)) 
                window.location.href = base_url + "?action=view&op=dw8dxn8w9ms8zs22&replid="+replid+"&newaktif="+newaktif+"&departemen="+departemen+"&proses="+proses+"&kelompok="+kelompok+"&urut=<?=$urut?>&urutan=<?=$urutan?>&page=<?=$page?>&hal=<?=$hal?>&varbaris=<?=$varbaris?>";
        }

        function hapus(replid) {
            var departemen = document.getElementById('departemen').value;
            var proses = document.getElementById('proses').value;	
            var kelompok = document.getElementById('kelompok').value;
            
            if (confirm("Apakah anda yakin akan menghapus calon siswa ini?"))
                window.location.href = base_url + "?action=view&op=xm8r389xemx23xb2378e23&replid="+replid+"&departemen="+departemen+"&proses="+proses+"&kelompok="+kelompok+"&urut=<?=$urut?>&urutan=<?=$urutan?>&page=<?=$page?>&hal=<?=$hal?>&varbaris=<?=$varbaris?>";
        }

        function change_urut(urut_baru, urutan_lama) {			
            var departemen = document.getElementById("departemen").value;	
            var proses = document.getElementById("proses").value;
            var kelompok = document.getElementById("kelompok").value;
            var varbaris = document.getElementById("varbaris").value;
            var urutan_baru = (urutan_lama == "ASC") ? "DESC" : "ASC";
            
            window.location.href = base_url + "?action=view&departemen="+encodeURIComponent(departemen)+"&proses="+proses+"&kelompok="+kelompok+"&urut="+urut_baru+"&urutan="+urutan_baru+"&page=<?=$page?>&hal=<?=$hal?>&varbaris="+varbaris;
        }

        function cetak() {
            var departemen = document.getElementById('departemen').value;
            var proses = document.getElementById('proses').value;	
            var kelompok = document.getElementById('kelompok').value;
            var total = document.getElementById("total") ? document.getElementById("total").value : 0;
            
            window.open('calon_cetak.php?departemen='+departemen+'&proses='+proses+'&kelompok='+kelompok+'&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris=<?=$varbaris?>&page=<?=$page?>&total='+total, 'CetakCalonSiswa', 'width=790,height=650,resizable=1,scrollbars=1');
        }

        function cetak_excel() {	
            var departemen = document.getElementById('departemen').value;	
            var proses = document.getElementById('proses').value;
            var kelompok = document.getElementById('kelompok').value;
            window.open('calon_cetak_excel.php?departemen='+departemen+'&proses='+proses+'&kelompok='+kelompok+'&urut=<?=$urut?>&urutan=<?=$urutan?>', 'CetakCalonSiswaExcel', 'width=790,height=650,resizable=1,scrollbars=1');
        }

        function tampil_detail(replid) {
            window.open('../library/detail_calon.php?replid='+replid, 'DetailCalonSiswa'+replid, 'width=790,height=610,resizable=1,scrollbars=1');
        }

        function cetak_detail_pribadi(replid) {
            window.open('calon_cetak_detail.php?replid='+replid, 'CetakDetailCalonSiswa', 'width=790,height=650,resizable=1,scrollbars=1');
        }

        function change_hal() {
            var departemen = document.getElementById("departemen").value;
            var proses = document.getElementById("proses").value;
            var kelompok = document.getElementById("kelompok").value;
            var hal = document.getElementById("hal").value;
            var varbaris = document.getElementById("varbaris").value;
            window.location.href = base_url + "?action=view&departemen="+encodeURIComponent(departemen)+"&proses="+proses+"&kelompok="+kelompok+"&page="+hal+"&hal="+hal+"&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris="+varbaris;
        }

        function change_baris() {
            var departemen = document.getElementById("departemen").value;
            var proses = document.getElementById("proses").value;
            var kelompok = document.getElementById("kelompok").value;
            var varbaris = document.getElementById("varbaris").value;
            window.location.href = base_url + "?action=view&departemen="+encodeURIComponent(departemen)+"&proses="+proses+"&kelompok="+kelompok+"&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris="+varbaris;
        }

        function tampil_kelompok() {
            var departemen = document.getElementById("departemen").value;
            var proses = document.getElementById("proses").value;
            window.open('kelompok_tampil.php?departemen='+departemen+'&proses='+proses, 'tampilKelompok', 'width=750,height=450,resizable=1,scrollbars=1');
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
                <i class="fas fa-address-book text-emerald-500"></i> Pendataan Calon Siswa
            </h2>
            <div class="text-sm text-gray-500 hidden sm:block">
                PPDB <i class="fas fa-chevron-right text-xs mx-1"></i> Pendataan
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
            <!-- Departemen -->
            <div class="md:col-span-3">
                <label class="block text-sm font-semibold text-gray-700 mb-1">Departemen</label>
                <select name="departemen" id="departemen" onchange="change_dep()" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition">
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

            <!-- Proses Penerimaan -->
            <div class="md:col-span-3">
                <label class="block text-sm font-semibold text-gray-700 mb-1">Proses Penerimaan</label>
                <input type="text" readonly class="w-full bg-gray-50 border border-gray-300 text-gray-600 rounded-md px-3 py-2 text-sm cursor-not-allowed font-medium" value="<?=$namaproses?>"/>
                <input type="hidden" name="proses" id="proses" value="<?=$proses?>">
            </div>

            <!-- Kelompok -->
            <div class="md:col-span-4">
                <label class="block text-sm font-semibold text-gray-700 mb-1">Kelompok</label>
                <div class="flex gap-2">
                    <select name="kelompok" id="kelompok" onchange="change_kelompok()" class="flex-1 border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition">
                        <?php 
                        $sql_k = "SELECT replid, kelompok, kapasitas FROM kelompokcalonsiswa WHERE idproses = '$proses' ORDER BY kelompok";
                        $result_k = QueryDb($sql_k);	
                        while ($row_k = @mysqli_fetch_array($result_k)) {
                            if ($kelompok == "") $kelompok = $row_k['replid'];
                            
                            $sql_isi = "SELECT COUNT(replid) FROM calonsiswa WHERE idkelompok = '$row_k[replid]' AND aktif = 1";
                            $result_isi = QueryDb($sql_isi);				
                            $row_isi = mysqli_fetch_row($result_isi);
                            $selected = ($row_k['replid'] == $kelompok) ? "selected" : "";
                        ?>
                            <option value="<?=urlencode($row_k['replid'])?>" <?=$selected?> >
                                <?=$row_k['kelompok']?> (Kap: <?=$row_k['kapasitas']?>, Isi: <?=$row_isi[0]?>)
                            </option>
                        <?php } ?>
                    </select>
                    <?php if (SI_USER_LEVEL() != $SI_USER_STAFF) { ?>
                    <button onclick="tampil_kelompok()" class="bg-gray-100 hover:bg-gray-200 p-2 rounded-md border border-gray-300 transition text-emerald-600" title="Kelola Kelompok">
                        <i class="fas fa-plus-circle"></i>
                    </button>
                    <?php } ?>
                </div>
            </div>

            <!-- Tombol Tampilkan -->
            <div class="md:col-span-2">
                <button type="button" onclick="show_calon()" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-medium py-2 px-4 rounded-md shadow-sm transition duration-150 ease-in-out flex items-center justify-center gap-2">
                    <i class="fas fa-search"></i> Tampilkan
                </button>
            </div>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- BAGIAN 2: CONTENT AREA                                                    -->
    <!-- ========================================================================= -->
    <div class="bg-white rounded-lg shadow-sm border border-emerald-100 p-5">
        <?php if ($action == 'view' && !empty($kelompok)) { ?>
            <!-- ===================== MODE: TAMPILKAN DATA ===================== -->
            
            <?php
            // Query Total Data
            $sql_tot = "SELECT c.replid FROM calonsiswa c WHERE c.idproses = '$proses' AND c.idkelompok = '$kelompok'";
            $result_tot = QueryDb($sql_tot);
            $jumlah = @mysqli_num_rows($result_tot);
            $total = ceil($jumlah / (int)$varbaris);
            
            // Query Data & Settings
            $sql_calon = "SELECT nopendaftaran, nama, sum1, sum2, ujian1, ujian2, ujian3, ujian4, ujian5, ujian6, ujian7, ujian8, ujian9, ujian10, aktif, replid, replidsiswa, nisn, info3, pinsiswa FROM calonsiswa WHERE idproses = '$proses' AND idkelompok = '$kelompok' ORDER BY $urut $urutan LIMIT ".(int)$page*(int)$varbaris.",$varbaris";
            $result_calon = QueryDb($sql_calon);

            // Ambil Info Kelompok
            $sql_infokel = "SELECT kapasitas, keterangan FROM kelompokcalonsiswa WHERE replid = '$kelompok'";
            $result_infokel = QueryDb($sql_infokel);
            $row_infokel = mysqli_fetch_array($result_infokel);
            $kapasitas = $row_infokel['kapasitas'];
            
            $sql_isikel = "SELECT COUNT(*) FROM calonsiswa WHERE idkelompok = '$kelompok' AND aktif = 1";
            $result_isikel = QueryDb($sql_isikel);
            $row_isikel = mysqli_fetch_row($result_isikel);
            $isi = $row_isikel[0];

            // Ambil Judul Kolom dari Setting PSB
            $sqlset = "SELECT * FROM settingpsb WHERE idproses = '$proses'";
            $resset = QueryDb($sqlset);
            $rowset = (@mysqli_num_rows($resset) > 0) ? mysqli_fetch_array($resset) : [];
            $cols = ['sum1','sum2','ujian1','ujian2','ujian3','ujian4','ujian5','ujian6','ujian7','ujian8','ujian9','ujian10'];
            $col_labels = [];
            foreach ($cols as $c) $col_labels[$c] = isset($rowset['kd'.$c]) ? $rowset[$c=='sum1'||$c=='sum2'?'kd'.$c:'kd'.$c] : strtoupper($c);
            ?>

            <input type="hidden" name="total" id="total" value="<?=$total?>"/>

            <!-- Info Bar -->
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-4 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div class="text-sm">
                    <span class="font-semibold text-gray-700">Keterangan Kelompok:</span>
                    <p class="text-gray-500 italic mt-1"><?=empty($row_infokel['keterangan']) ? "-" : $row_infokel['keterangan']?></p>
                </div>
                <div class="flex gap-4 text-xs">
                    <div class="text-center bg-white border border-gray-200 p-2 rounded shadow-sm min-w-[80px]">
                        <p class="text-gray-400 uppercase font-bold mb-1">Kapasitas</p>
                        <p class="text-lg font-bold text-gray-800"><?=$kapasitas?></p>
                    </div>
                    <div class="text-center bg-white border border-gray-200 p-2 rounded shadow-sm min-w-[80px]">
                        <p class="text-gray-400 uppercase font-bold mb-1">Terisi</p>
                        <p class="text-lg font-bold text-emerald-600"><?=$isi?></p>
                    </div>
                    <div class="text-center bg-white border border-gray-200 p-2 rounded shadow-sm min-w-[80px]">
                        <p class="text-gray-400 uppercase font-bold mb-1">Sisa</p>
                        <p class="text-lg font-bold text-orange-500"><?=max(0, $kapasitas - $isi)?></p>
                    </div>
                </div>
            </div>

            <!-- Action Toolbar -->
            <div class="flex flex-wrap gap-2 mb-4 justify-between items-center">
                <div class="text-sm text-gray-600">
                    Ditemukan <span class="font-bold text-gray-900"><?=$jumlah?></span> calon siswa.
                </div>
                <div class="flex flex-wrap gap-2">
                    <button onclick="refresh_content()" class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium py-1.5 px-3 rounded shadow-sm border border-gray-300 transition flex items-center gap-1">
                        <i class="fas fa-sync-alt"></i> Refresh
                    </button>
                    <button onclick="cetak_excel()" class="bg-green-600 hover:bg-green-700 text-white text-sm font-medium py-1.5 px-3 rounded shadow-sm transition flex items-center gap-1">
                        <i class="fas fa-file-excel"></i> Excel
                    </button>
                    <button onclick="cetak()" class="bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium py-1.5 px-3 rounded shadow-sm transition flex items-center gap-1">
                        <i class="fas fa-print"></i> Cetak
                    </button>
                    <?php if ($kapasitas > $isi && SI_USER_LEVEL() != $SI_USER_STAFF) { ?>
                    <button onclick="tambah()" class="bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium py-1.5 px-3 rounded shadow-sm transition flex items-center gap-1">
                        <i class="fas fa-plus"></i> Tambah Data
                    </button>
                    <?php } ?>
                </div>
            </div>

            <!-- Table Data (Scrollable Horizontal) -->
            <div class="overflow-x-auto rounded-lg border border-gray-200">
                <table class="min-w-full divide-y divide-gray-200 text-xs">
                    <thead class="bg-emerald-50">
                        <tr class="divide-x divide-emerald-100">        
                            <th rowspan="2" class="px-3 py-2 text-center font-bold text-emerald-800 uppercase w-10">No</th>
                            <th rowspan="2" class="px-3 py-2 text-center font-bold text-emerald-800 uppercase w-20">Aksi</th>
                            <th rowspan="2" class="px-3 py-2 text-left font-bold text-emerald-800 uppercase cursor-pointer hover:bg-emerald-100" onClick="change_urut('nopendaftaran','<?=$urutan?>')">No Daftar <i class="fas fa-sort text-emerald-300 ml-1"></i></th>
                            <th rowspan="2" class="px-3 py-2 text-left font-bold text-emerald-800 uppercase">PIN</th>
                            <th rowspan="2" class="px-3 py-2 text-left font-bold text-emerald-800 uppercase cursor-pointer hover:bg-emerald-100" onClick="change_urut('nama','<?=$urutan?>')">Nama Siswa <i class="fas fa-sort text-emerald-300 ml-1"></i></th>
                            <th colspan="2" class="px-3 py-2 text-center font-bold text-emerald-800 uppercase bg-emerald-100/50">Sumbangan</th>
                            <th colspan="10" class="px-3 py-2 text-center font-bold text-emerald-800 uppercase bg-emerald-100/20">Ujian/Nilai</th>
                            <th rowspan="2" class="px-3 py-2 text-center font-bold text-emerald-800 uppercase w-20 cursor-pointer hover:bg-emerald-100" onClick="change_urut('aktif','<?=$urutan?>')">Status <i class="fas fa-sort text-emerald-300 ml-1"></i></th>
                        </tr>
                        <tr class="divide-x divide-emerald-100 bg-emerald-50/50">
                            <th class="px-2 py-1 text-center text-[10px] text-emerald-600 truncate max-w-[80px]"><?=$col_labels['sum1']?></th>
                            <th class="px-2 py-1 text-center text-[10px] text-emerald-600 truncate max-w-[80px]"><?=$col_labels['sum2']?></th>
                            <?php for($i=1;$i<=10;$i++) { ?>
                                <th class="px-2 py-1 text-center text-[10px] text-emerald-600"><?=$col_labels['ujian'.$i]?></th>
                            <?php } ?>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php 
                        $cnt = ($page == 0) ? 1 : ((int)$page * (int)$varbaris + 1);
                        while ($row = mysqli_fetch_array($result_calon)) {
                            $siswa_info = "";
                            if ($row["replidsiswa"] != 0) {
                                $sql_s = "SELECT nis FROM siswa WHERE replid = '$row[replidsiswa]'";
                                $res_s = QueryDb($sql_s);
                                $row_s = @mysqli_fetch_array($res_s);
                                $siswa_info = " (Siswa: ".$row_s['nis'].")";
                            }
                        ?>    
                        <tr class="hover:bg-emerald-50 transition duration-150 divide-x divide-gray-100">                    
                            <td class="px-3 py-2 text-center text-gray-500"><?=$cnt?></td>
                            <td class="px-3 py-2 text-center">
                                <div class="flex justify-center gap-1.5">
                                    <button onclick="tampil_detail(<?=$row['replid']?>)" class="text-blue-500 hover:text-blue-700 transition" title="Detail"><i class="fas fa-eye"></i></button>
                                    <button onclick="edit(<?=$row['replid']?>)" class="text-amber-500 hover:text-amber-700 transition" title="Ubah"><i class="fas fa-edit"></i></button>
                                    <?php if (SI_USER_LEVEL() != $SI_USER_STAFF) { ?>
                                        <button onclick="hapus(<?=$row['replid']?>)" class="text-red-500 hover:text-red-700 transition" title="Hapus"><i class="fas fa-trash-alt"></i></button>
                                    <?php } ?>
                                </div>
                            </td>
                            <td class="px-3 py-2 font-mono text-gray-700"><?=$row['nopendaftaran']?></td>
                            <td class="px-3 py-2 text-gray-500"><?=$row['pinsiswa']?></td>
                            <td class="px-3 py-2 font-bold text-gray-900"><?=$row['nama']?></td>
                            <td class="px-2 py-2 text-right text-gray-600"><?=FormatRupiah($row['sum1'])?></td>
                            <td class="px-2 py-2 text-right text-gray-600"><?=FormatRupiah($row['sum2'])?></td>
                            <?php for($i=1;$i<=10;$i++) { ?>
                                <td class="px-2 py-2 text-center text-gray-700"><?=$row['ujian'.$i]?></td>
                            <?php } ?>
                            <td class="px-3 py-2 text-center">
                                <?php 
                                if (SI_USER_LEVEL() == $SI_USER_STAFF) {  
                                    if ($row['aktif'] == 1) {
                                        echo '<span class="px-2 py-1 inline-flex text-[10px] leading-4 font-semibold rounded-full bg-emerald-100 text-emerald-800" title="Aktif'.$siswa_info.'">Aktif</span>';
                                    } else {
                                        echo '<span class="px-2 py-1 inline-flex text-[10px] leading-4 font-semibold rounded-full bg-red-100 text-red-800" title="Tidak Aktif'.$siswa_info.'">Non Aktif</span>';
                                    }
                                } else { 
                                    if ($row['aktif'] == 1) { ?>
                                        <button onclick="setaktif(<?=$row['replid']?>, <?=$row['aktif']?>)" class="px-2 py-1 inline-flex text-[10px] leading-4 font-semibold rounded-full bg-emerald-100 text-emerald-800 hover:bg-emerald-200 transition" title="Klik untuk Non-Aktifkan<?=$siswa_info?>">Aktif</button>
                                    <?php } else { 
                                        if ($kapasitas > $isi) { ?>
                                            <button onclick="setaktif(<?=$row['replid']?>, <?=$row['aktif']?>)" class="px-2 py-1 inline-flex text-[10px] leading-4 font-semibold rounded-full bg-gray-100 text-gray-600 hover:bg-gray-200 transition" title="Klik untuk Aktifkan<?=$siswa_info?>">Non Aktif</button>
                                        <?php } else { ?>
                                            <span class="px-2 py-1 inline-flex text-[10px] leading-4 font-semibold rounded-full bg-red-100 text-red-800 cursor-not-allowed" title="Kapasitas Penuh">Non Aktif</span>
                                        <?php }
                                    }
                                } ?>            
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
                    <select name="hal" id="hal" onChange="change_hal()" class="border border-gray-300 rounded px-2 py-1 bg-white focus:ring-2 focus:ring-emerald-500 outline-none text-xs">
                    <?php for ($m=0; $m<$total; $m++) {?>
                        <option value="<?=$m?>" <?=IntIsSelected($hal,$m)?>><?=$m+1?></option>
                    <?php } ?>
                    </select>
                    <span class="text-gray-600 text-xs">dari <?=$total?> halaman</span>
                </div>
                
                <div class="flex items-center gap-2">
                    <span class="text-gray-600 text-xs">Tampilkan</span>
                    <select name="varbaris" id="varbaris" onChange="change_baris()" class="border border-gray-300 rounded px-2 py-1 bg-white focus:ring-2 focus:ring-emerald-500 outline-none text-xs">
                    <?php for ($m=10; $m <= 100; $m=$m+10) { ?>
                        <option value="<?=$m?>" <?=IntIsSelected($varbaris,$m)?>><?=$m?></option>
                    <?php } ?>
                    </select>
                    <span class="text-gray-600 text-xs">baris per halaman</span>
                </div>
            </div>

        <?php } else { ?>
            <!-- Mode: Blank State -->
            <div class="flex flex-col items-center justify-center p-16 text-center bg-emerald-50 rounded-lg border border-emerald-100">
                <i class="fas fa-users text-6xl text-emerald-200 mb-4"></i>
                <h3 class="text-xl font-bold text-emerald-800 mb-2">Pilih Kriteria Calon Siswa</h3>
                <p class="text-emerald-600 max-w-md leading-relaxed">
                    Silakan tentukan <strong>Departemen, Proses Penerimaan,</strong> dan <strong>Kelompok</strong> pada form di atas, lalu klik tombol <strong class="text-emerald-800"><i class="fas fa-search"></i> Tampilkan</strong> untuk melihat daftar calon siswa.
                </p>
            </div>
        <?php } ?>
    </div>

</div>

<?php CloseDb(); ?>
</body>
</html>