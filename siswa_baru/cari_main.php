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
$jenis      = isset($_REQUEST['jenis']) ? $_REQUEST['jenis'] : 'nama';
$cari       = isset($_REQUEST['cari']) ? $_REQUEST['cari'] : '';
$action     = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';

$varbaris = isset($_REQUEST['varbaris']) ? $_REQUEST['varbaris'] : 20;
$page     = isset($_REQUEST['page']) ? $_REQUEST['page'] : 0;
$hal      = isset($_REQUEST['hal']) ? $_REQUEST['hal'] : 0;
$urut     = isset($_REQUEST['urut']) ? $_REQUEST['urut'] : "nopendaftaran";	
$urutan   = isset($_REQUEST['urutan']) ? $_REQUEST['urutan'] : "ASC";	

$tipe_pencarian = array(
    array("nopendaftaran","No. Pendaftaran"),
    array("nisn","N I S N"), 
    array("nama","Nama"), 
    array("panggilan","Nama Panggilan"), 
    array("agama","Agama"), 
    array("suku","Suku"), 
    array("status","Status"), 
    array("kondisi","Kondisi Siswa"), 
    array("darah","Golongan Darah"), 
    array("alamatsiswa","Alamat Siswa"), 
    array("asalsekolah","Asal Sekolah"), 
    array("namaayah","Nama Ayah"), 
    array("namaibu","Nama Ibu"), 
    array("alamatortu","Alamat Orang Tua"), 
    array("keterangan","Keterangan")
);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pencarian Calon Siswa</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <script type="text/javascript">
        var base_url = "cari_main.php";

        function change_filter() {
            var departemen = document.getElementById('departemen').value;
            var jenis = document.getElementById('jenis').value;
            var cari = document.getElementById("cari") ? document.getElementById("cari").value : "";	
            window.location.href = base_url + "?departemen=" + encodeURIComponent(departemen) + "&jenis=" + jenis;	
        }

        function do_search() {		
            var departemen = document.getElementById("departemen").value;
            var jenis = document.getElementById("jenis").value;		
            var cari = document.getElementById("cari").value;	
            
            if (cari == "") {
                alert('Keyword pencarian tidak boleh kosong');
                document.getElementById("cari").focus();	
                return false;
            }
            
            if (jenis != 'kondisi' && jenis != 'status' && jenis != 'agama' && jenis != 'suku' && jenis != 'darah'){
                if (cari.length < 3 ){
                    alert('Keyword pencarian minimal 3 karakter');
                    return false;
                }	
            }
            window.location.href = base_url + "?action=search&departemen=" + encodeURIComponent(departemen) + "&jenis=" + jenis + "&cari=" + encodeURIComponent(cari);
        }

        function refresh_content() {
            do_search();
        }

        function tampil_detail(replid) {
            window.open('../library/detail_calon.php?replid='+replid, 'DetailCalonSiswa'+replid, 'width=790,height=610,resizable=1,scrollbars=1');
        }

        function edit(replid) {
            window.open('calon_edit.php?replid='+replid, 'UbahPendataanCalonSiswa', 'width=825,height=650,resizable=1,scrollbars=1');
        }

        function change_urut(urut_baru, urutan_lama) {		
            var departemen = document.getElementById('departemen').value;
            var jenis = document.getElementById('jenis').value;
            var cari = document.getElementById('cari').value;
            var varbaris = document.getElementById("varbaris").value;
            var urutan_baru = (urutan_lama == "ASC") ? "DESC" : "ASC";
            
            window.location.href = base_url + "?action=search&departemen="+encodeURIComponent(departemen)+"&jenis="+jenis+"&cari="+encodeURIComponent(cari)+"&urut="+urut_baru+"&urutan="+urutan_baru+"&page=<?=$page?>&hal=<?=$hal?>&varbaris="+varbaris;
        }

        function cetak() {
            var departemen = document.getElementById('departemen').value;
            var jenis = document.getElementById('jenis').value;
            var cari = document.getElementById('cari').value;
            var total = document.getElementById("total") ? document.getElementById("total").value : 0;
            
            window.open('cari_cetak.php?departemen='+encodeURIComponent(departemen)+'&jenis='+jenis+'&cari='+encodeURIComponent(cari)+'&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris=<?=$varbaris?>&page=<?=$page?>&total='+total, 'CetakCariCalonSiswa', 'width=790,height=650,resizable=1,scrollbars=1');
        }

        function cetak_excel() {	
            var departemen = document.getElementById('departemen').value;	
            var jenis = document.getElementById('jenis').value;
            var cari = document.getElementById('cari').value;
            window.open('cari_cetak_excel.php?departemen='+encodeURIComponent(departemen)+'&jenis='+jenis+'&cari='+encodeURIComponent(cari)+'&urut=<?=$urut?>&urutan=<?=$urutan?>', 'CetakCariCalonSiswaExcel', 'width=790,height=650,resizable=1,scrollbars=1');
        }

        function change_hal() {
            var departemen = document.getElementById('departemen').value;
            var jenis = document.getElementById('jenis').value;
            var cari = document.getElementById('cari').value;
            var hal = document.getElementById("hal").value;
            var varbaris = document.getElementById("varbaris").value;
            window.location.href = base_url + "?action=search&departemen="+encodeURIComponent(departemen)+"&jenis="+jenis+"&cari="+encodeURIComponent(cari)+"&page="+hal+"&hal="+hal+"&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris="+varbaris;
        }

        function change_baris() {
            var departemen = document.getElementById('departemen').value;
            var jenis = document.getElementById('jenis').value;
            var cari = document.getElementById('cari').value;
            var varbaris = document.getElementById("varbaris").value;
            window.location.href = base_url + "?action=search&departemen="+encodeURIComponent(departemen)+"&jenis="+jenis+"&cari="+encodeURIComponent(cari)+"&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris="+varbaris;
        }

        function handle_enter(e) {
            if (e.keyCode === 13) {
                do_search();
                return false;
            }
        }
    </script>
</head>
<body class="bg-gray-100 font-sans p-4" onload="document.getElementById('cari').focus()">

<div class="max-w-7xl mx-auto space-y-4">

    <!-- ========================================================================= -->
    <!-- BAGIAN 1: HEADER / FILTER                                                 -->
    <!-- ========================================================================= -->
    <div class="bg-white rounded-lg shadow-sm border border-emerald-100 p-5">
        
        <!-- Breadcrumb / Title -->
        <div class="flex justify-between items-center mb-4 border-b border-gray-100 pb-3">
            <h2 class="text-xl font-bold text-emerald-900 flex items-center gap-2">
                <i class="fas fa-search text-emerald-500"></i> Pencarian Calon Siswa
            </h2>
            <div class="text-sm text-gray-500 hidden sm:block">
                PPDB <i class="fas fa-chevron-right text-xs mx-1"></i> Pencarian
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
            <!-- Departemen -->
            <div class="md:col-span-3">
                <label class="block text-sm font-semibold text-gray-700 mb-1">Departemen</label>
                <select name="departemen" id="departemen" onchange="change_filter()" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition">
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

            <!-- Kriteria -->
            <div class="md:col-span-3">
                <label class="block text-sm font-semibold text-gray-700 mb-1">Berdasarkan</label>
                <select name="jenis" id="jenis" onchange="change_filter()" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition">
                    <?php foreach($tipe_pencarian as $t) { ?>
                        <option value="<?=$t[0]?>" <?=StringIsSelected($t[0], $jenis)?>><?=$t[1]?></option>
                    <?php } ?>
                </select>
            </div>

            <!-- Kata Kunci -->
            <div class="md:col-span-4">
                <label class="block text-sm font-semibold text-gray-700 mb-1">Kata Kunci / Pilihan</label>
                <?php
                if ($jenis == 'darah') {
                    $darah_opt = array('A','O','B','AB');
                ?>
                    <select name="cari" id="cari" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition">
                        <?php foreach($darah_opt as $d) { ?>
                            <option value="<?=$d?>" <?=StringIsSelected($d, $cari)?>><?=$d?></option>
                        <?php } ?>
                    </select>
                <?php
                } elseif (in_array($jenis, ['kondisi', 'status', 'agama', 'suku'])) {
                    if ($jenis == 'kondisi') $query = "SELECT kondisi FROM kondisisiswa ORDER BY kondisi";
                    elseif ($jenis == 'status') $query = "SELECT status FROM statussiswa ORDER BY status";
                    elseif ($jenis == 'suku') $query = "SELECT suku FROM suku ORDER BY suku";
                    elseif ($jenis == 'agama') $query = "SELECT agama FROM agama ORDER BY urutan";
                    $res_opt = QueryDb($query);
                ?>
                    <select name="cari" id="cari" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition">
                        <?php while ($r_opt = mysqli_fetch_row($res_opt)) { ?>
                            <option value="<?=$r_opt[0]?>" <?=StringIsSelected($r_opt[0], $cari)?>><?=$r_opt[0]?></option>
                        <?php } ?>
                    </select>
                <?php
                } else {
                ?>
                    <div class="relative">
                        <input type="text" name="cari" id="cari" value="<?=htmlspecialchars($cari)?>" onkeydown="return handle_enter(event)" class="w-full border border-gray-300 rounded-md pl-10 pr-3 py-2 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition" placeholder="Cari...">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400 text-xs"></i>
                        </div>
                    </div>
                <?php } ?>
            </div>

            <!-- Tombol Cari -->
            <div class="md:col-span-2">
                <button type="button" onclick="do_search()" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-medium py-2 px-4 rounded-md shadow-sm transition duration-150 ease-in-out flex items-center justify-center gap-2">
                    <i class="fas fa-search"></i> Cari Data
                </button>
            </div>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- BAGIAN 2: CONTENT AREA                                                    -->
    <!-- ========================================================================= -->
    <div class="bg-white rounded-lg shadow-sm border border-emerald-100 p-5">
        <?php if ($action == 'search' && !empty($cari)) { ?>
            <!-- ===================== MODE: TAMPILKAN HASIL ===================== -->
            
            <?php
            $where_clause = "";
            if (in_array($jenis, ["kondisi", "status", "agama", "suku", "darah"])) {
                $where_clause = "c.$jenis = '$cari'";
            } else {
                $where_clause = "c.$jenis LIKE '%$cari%'";
            }
            
            $sql_base = "FROM calonsiswa c, kelompokcalonsiswa k, prosespenerimaansiswa p WHERE $where_clause AND p.departemen='$departemen' AND c.idkelompok = k.replid AND c.idproses = p.replid AND p.replid = k.idproses";
            
            // Query Total
            $sql_tot = "SELECT COUNT(*) " . $sql_base;
            $result_tot = QueryDb($sql_tot);
            $row_tot = mysqli_fetch_row($result_tot);
            $jumlah = $row_tot[0];
            $total = ceil($jumlah / (int)$varbaris);
            
            // Query Data
            $sql = "SELECT c.replid, c.nopendaftaran, c.nama, k.kelompok, c.aktif, c.nisn " . $sql_base . " ORDER BY $urut $urutan, nama ASC LIMIT ".(int)$page*(int)$varbaris.",$varbaris";
            $result = QueryDb($sql);

            if ($jumlah > 0) {
            ?>
                <input type="hidden" name="total" id="total" value="<?=$total?>"/>

                <!-- Action Toolbar -->
                <div class="flex flex-wrap gap-2 mb-4 justify-between items-center">
                    <div class="text-sm text-gray-600">
                        Hasil pencarian: <span class="font-bold text-gray-900"><?=$jumlah?></span> data ditemukan.
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
                    </div>
                </div>

                <!-- Table Data -->
                <div class="overflow-x-auto rounded-lg border border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-emerald-50">
                            <tr>        
                                <th class="px-4 py-3 text-center text-xs font-bold text-emerald-800 uppercase tracking-wider w-12">No</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-emerald-800 uppercase tracking-wider cursor-pointer hover:bg-emerald-100 transition" onClick="change_urut('nopendaftaran','<?=$urutan?>')">
                                    No. Daftar <i class="fas fa-sort text-emerald-300 ml-1"></i>
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-emerald-800 uppercase tracking-wider cursor-pointer hover:bg-emerald-100 transition" onClick="change_urut('nisn','<?=$urutan?>')">
                                    NISN <i class="fas fa-sort text-emerald-300 ml-1"></i>
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-emerald-800 uppercase tracking-wider cursor-pointer hover:bg-emerald-100 transition" onClick="change_urut('nama','<?=$urutan?>')">
                                    Nama Calon Siswa <i class="fas fa-sort text-emerald-300 ml-1"></i>
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-emerald-800 uppercase tracking-wider cursor-pointer hover:bg-emerald-100 transition" onClick="change_urut('kelompok','<?=$urutan?>')">
                                    Kelompok <i class="fas fa-sort text-emerald-300 ml-1"></i>
                                </th>
                                <th class="px-4 py-3 text-center text-xs font-bold text-emerald-800 uppercase tracking-wider w-32 cursor-pointer hover:bg-emerald-100 transition" onClick="change_urut('aktif','<?=$urutan?>')">
                                    Status <i class="fas fa-sort text-emerald-300 ml-1"></i>
                                </th>
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
                                <td class="px-4 py-2 font-mono text-emerald-700"><?=$row['nopendaftaran']?></td>
                                <td class="px-4 py-2 text-gray-600"><?=$row['nisn']?></td>
                                <td class="px-4 py-2 font-bold text-gray-900"><?=$row['nama']?></td>
                                <td class="px-4 py-2 text-gray-700"><?=$row['kelompok']?></td>
                                <td class="px-4 py-2 text-center">
                                    <?php if ($row['aktif'] == 1) { ?>
                                        <span class="px-2 py-1 inline-flex text-xs leading-4 font-semibold rounded-full bg-emerald-100 text-emerald-800">Aktif</span>
                                    <?php } else { ?>
                                        <span class="px-2 py-1 inline-flex text-xs leading-4 font-semibold rounded-full bg-red-100 text-red-800">Tidak Aktif</span>
                                    <?php } ?>
                                </td>
                                <td class="px-4 py-2 text-center flex justify-center gap-2">
                                    <button onclick="edit(<?=$row['replid']?>)" class="text-amber-500 hover:text-amber-700 transition" title="Ubah">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button onclick="tampil_detail(<?=$row['replid']?>)" class="text-blue-500 hover:text-blue-700 transition" title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php $cnt++; } ?>            
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Area -->
                <div class="mt-4 flex flex-col sm:flex-row items-center justify-between gap-4 text-sm bg-gray-50 p-3 rounded-lg border border-gray-200">
                    <div class="flex items-center gap-2">
                        <span class="text-gray-600 text-xs">Halaman</span>
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
                <!-- No Results State -->
                <div class="flex flex-col items-center justify-center p-16 text-center bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
                    <i class="fas fa-search-minus text-6xl text-gray-300 mb-4"></i>
                    <h3 class="text-xl font-bold text-gray-700 mb-2">Data Tidak Ditemukan</h3>
                    <p class="text-gray-500 max-w-md">
                        Tidak ditemukan calon siswa dengan kriteria yang Anda masukkan. Silakan coba dengan kata kunci lain.
                    </p>
                </div>
            <?php } ?>

        <?php } else { ?>
            <!-- Mode: Empty Search -->
            <div class="flex flex-col items-center justify-center p-20 text-center bg-emerald-50 rounded-lg border border-emerald-100">
                <i class="fas fa-search text-6xl text-emerald-200 mb-6"></i>
                <h3 class="text-xl font-bold text-emerald-800 mb-2">Cari Calon Siswa</h3>
                <p class="text-emerald-600 max-w-md leading-relaxed">
                    Masukkan kriteria pencarian dan kata kunci pada form di atas, lalu klik tombol <strong class="text-emerald-800"><i class="fas fa-search"></i> Cari Data</strong>.
                </p>
            </div>
        <?php } ?>
    </div>

</div>

<?php CloseDb(); ?>
</body>
</html>