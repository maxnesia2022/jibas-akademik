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
$proses = isset($_REQUEST['proses']) ? $_REQUEST['proses'] : '';
$varbaris = isset($_REQUEST['varbaris']) ? $_REQUEST['varbaris'] : 10;
$page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 0;
$hal = isset($_REQUEST['hal']) ? $_REQUEST['hal'] : 0;
$urut = isset($_REQUEST['urut']) ? $_REQUEST['urut'] : "kelompok";	
$urutan = isset($_REQUEST['urutan']) ? $_REQUEST['urutan'] : "ASC";	
$op = isset($_REQUEST['op']) ? $_REQUEST['op'] : '';

// =========================================================================
// PROSES AKSI
// =========================================================================

// Aksi: Hapus Kelompok
if ($op == "xm8r389xemx23xb2378e23") {
	$sql = "DELETE FROM kelompokcalonsiswa WHERE replid = '$_REQUEST[replid]'";
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
    <title>Kelompok PPDB</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <script type="text/javascript">
        var base_url = "kelompok.php";

        function tambah() {
            var departemen = document.getElementById('departemen').value;
            var id = document.getElementById('proses').value;
            window.open('kelompok_add.php?departemen='+encodeURIComponent(departemen)+'&id='+id, 'TambahKelompokCalonSiswa', 'width=500,height=340,resizable=1,scrollbars=1');
        }

        function refresh() {
            var departemen = document.getElementById('departemen').value;
            var proses = document.getElementById('proses').value;
            window.location.href = base_url + "?departemen=" + encodeURIComponent(departemen) + "&proses=" + proses;	
        }

        function tampil() {
            var departemen = document.getElementById('departemen').value;
            window.location.href = base_url + "?departemen=" + encodeURIComponent(departemen) + "&varbaris=<?=$varbaris?>";
        }

        function edit(replid) {
            window.open('kelompok_edit.php?replid='+replid, 'UbahKelompokCalonSiswa', 'width=500,height=340,resizable=1,scrollbars=1');
        }

        function hapus(replid) {
            var departemen = document.getElementById('departemen').value;
            var proses = document.getElementById('proses').value;
            if (confirm("Apakah anda yakin akan menghapus kelompok ini?"))
                window.location.href = base_url + "?op=xm8r389xemx23xb2378e23&replid="+replid+"&departemen="+encodeURIComponent(departemen)+"&proses="+proses+"&urut=<?=$urut?>&urutan=<?=$urutan?>&page=<?=$page?>&hal=<?=$hal?>&varbaris=<?=$varbaris?>";
        }

        function lihat(replid) {
            var departemen = document.getElementById('departemen').value;
            var proses = document.getElementById('proses').value;
            window.open('kelompok_detail.php?replid='+replid+'&departemen='+encodeURIComponent(departemen)+'&proses='+proses, 'DaftarCalonSiswa', 'width=790,height=650,resizable=1,scrollbars=1');
        }

        function cetak() {
            var departemen = document.getElementById('departemen').value;
            var proses = document.getElementById('proses').value;
            var total = document.getElementById("total").value;
            window.open('kelompok_cetak.php?departemen='+encodeURIComponent(departemen)+'&proses='+proses+'&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris=<?=$varbaris?>&page=<?=$page?>&total='+total, 'CetakKelompokCalonSiswa', 'width=790,height=650,resizable=1,scrollbars=1');
        }

        function change_urut(urut_baru, urutan_lama) {		
            var departemen = document.getElementById('departemen').value;
            var proses = document.getElementById('proses').value;
            var varbaris = document.getElementById("varbaris").value;
            var urutan_baru = (urutan_lama == "ASC") ? "DESC" : "ASC";
            window.location.href = base_url + "?departemen="+encodeURIComponent(departemen)+"&proses="+proses+"&urut="+urut_baru+"&urutan="+urutan_baru+"&page=<?=$page?>&hal=<?=$hal?>&varbaris="+varbaris;
        }

        function change_hal() {
            var departemen = document.getElementById("departemen").value;
            var proses = document.getElementById('proses').value;
            var hal = document.getElementById("hal").value;
            var varbaris = document.getElementById("varbaris").value;
            window.location.href = base_url + "?departemen="+encodeURIComponent(departemen)+"&proses="+proses+"&page="+hal+"&hal="+hal+"&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris="+varbaris;
        }

        function change_baris() {
            var departemen = document.getElementById("departemen").value;
            var proses = document.getElementById('proses').value;
            var varbaris = document.getElementById("varbaris").value;
            window.location.href = base_url + "?departemen="+encodeURIComponent(departemen)+"&proses="+proses+"&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris="+varbaris;
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
                <i class="fas fa-users-cog text-emerald-500"></i> Kelompok Calon Siswa
            </h2>
            <div class="text-sm text-gray-500 hidden sm:block">
                PPDB <i class="fas fa-chevron-right text-xs mx-1"></i> Kelompok
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
            <!-- Departemen -->
            <div class="md:col-span-4">
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

            <!-- Proses Penerimaan -->
            <div class="md:col-span-4">
                <label class="block text-sm font-semibold text-gray-700 mb-1">Proses Penerimaan</label>
                <?php	
                $sql_proses = "SELECT replid, proses FROM prosespenerimaansiswa WHERE aktif=1 AND departemen='$departemen'";				
                $result_proses = QueryDb($sql_proses);
                $nama_proses = "";
                $proses_id = "";
                if (@mysqli_num_rows($result_proses) > 0) {
                    $row_p = mysqli_fetch_array($result_proses);
                    $proses_id = $row_p['replid'];
                    $nama_proses = $row_p['proses'];
                }
                if (empty($proses)) $proses = $proses_id;
                ?>
                <div class="relative">
                    <input type="text" readonly class="w-full bg-gray-50 border border-gray-300 text-gray-600 rounded-md px-3 py-2 text-sm cursor-not-allowed font-medium" value="<?=$nama_proses?>"/>
                    <input type="hidden" name="proses" id="proses" value="<?=$proses?>">
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="md:col-span-4 flex flex-wrap gap-2 justify-end">
                <button onclick="refresh()" class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium py-1.5 px-3 rounded shadow-sm border border-gray-300 transition flex items-center gap-1">
                    <i class="fas fa-sync-alt"></i> Refresh
                </button>
                <button onclick="cetak()" class="bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium py-1.5 px-3 rounded shadow-sm transition flex items-center gap-1">
                    <i class="fas fa-print"></i> Cetak
                </button>
                <?php if (!empty($proses) && SI_USER_LEVEL() != $SI_USER_STAFF) { ?>
                <button onclick="tambah()" class="bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium py-1.5 px-3 rounded shadow-sm transition flex items-center gap-1">
                    <i class="fas fa-plus"></i> Tambah Kelompok
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
        if (!empty($proses)) {
            // Query Total
            $sql_tot = "SELECT COUNT(*) FROM kelompokcalonsiswa WHERE idproses='$proses'";
            $result_tot = QueryDb($sql_tot);
            $row_tot = mysqli_fetch_row($result_tot);
            $jumlah = $row_tot[0];
            $total = ceil($jumlah / (int)$varbaris);
            
            // Query Data
            $sql = "SELECT replid, kelompok, kapasitas, keterangan FROM kelompokcalonsiswa WHERE idproses='$proses' ORDER BY $urut $urutan LIMIT ".(int)$page*(int)$varbaris.",$varbaris";
            $result = QueryDb($sql);

            if ($jumlah > 0) {
            ?>
                <input type="hidden" name="total" id="total" value="<?=$total?>"/>
                
                <div class="text-sm text-gray-600 mb-4">
                    Ditemukan <span class="font-bold text-gray-900"><?=$jumlah?></span> kelompok calon siswa.
                </div>

                <!-- Table Data -->
                <div class="overflow-x-auto rounded-lg border border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-emerald-50">
                            <tr>        
                                <th class="px-4 py-3 text-center text-xs font-bold text-emerald-800 uppercase tracking-wider w-12">No</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-emerald-800 uppercase tracking-wider cursor-pointer hover:bg-emerald-100 transition" onClick="change_urut('kelompok','<?=$urutan?>')">
                                    Kelompok <i class="fas fa-sort text-emerald-300 ml-1"></i>
                                </th>
                                <th class="px-4 py-3 text-center text-xs font-bold text-emerald-800 uppercase tracking-wider w-24 cursor-pointer hover:bg-emerald-100 transition" onClick="change_urut('kapasitas','<?=$urutan?>')">
                                    Kapasitas <i class="fas fa-sort text-emerald-300 ml-1"></i>
                                </th>
                                <th class="px-4 py-3 text-center text-xs font-bold text-emerald-800 uppercase tracking-wider w-24">Terisi</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-emerald-800 uppercase tracking-wider">Keterangan</th>
                                <th class="px-4 py-3 text-center text-xs font-bold text-emerald-800 uppercase tracking-wider w-24">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php 
                            $cnt = ($page == 0) ? 1 : ((int)$page * (int)$varbaris + 1);
                            while ($row = mysqli_fetch_array($result)) {
                                $sql1 = "SELECT COUNT(*) FROM calonsiswa WHERE idkelompok='$row[replid]' AND aktif = 1";
                                $result1 = QueryDb($sql1);
                                $row1 = @mysqli_fetch_row($result1);
                                $terisi = $row1[0];
                            ?>    
                            <tr class="hover:bg-emerald-50 transition duration-150">                    
                                <td class="px-4 py-2 text-center text-gray-500"><?=$cnt?></td>
                                <td class="px-4 py-2 text-gray-900 font-bold"><?=$row['kelompok']?></td>
                                <td class="px-4 py-2 text-center text-gray-800 font-semibold"><?=$row['kapasitas']?></td>
                                <td class="px-4 py-2 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <span class="bg-orange-50 text-orange-700 py-1 px-2.5 rounded-lg font-bold text-xs">
                                            <?=$terisi?>
                                        </span>
                                        <?php if ($terisi > 0) { ?>
                                            <button onclick="lihat(<?=$row['replid']?>)" class="text-blue-500 hover:text-blue-700 transition" title="Lihat Daftar Calon Siswa">
                                                <i class="fas fa-eye text-xs"></i>
                                            </button>
                                        <?php } ?>
                                    </div>
                                </td>
                                <td class="px-4 py-2 text-gray-600 italic text-xs"><?=$row['keterangan']?></td>
                                
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
                    <i class="fas fa-users-cog text-6xl text-gray-300 mb-4"></i>
                    <h3 class="text-xl font-bold text-gray-700 mb-2">Data Tidak Ditemukan</h3>
                    <p class="text-gray-500 max-w-md mb-6">
                        Tidak ditemukan data kelompok untuk proses penerimaan ini.
                    </p>
                    <?php if (SI_USER_LEVEL() != $SI_USER_STAFF) { ?>
                    <button onclick="tambah()" class="bg-emerald-600 hover:bg-emerald-700 text-white font-medium py-2 px-6 rounded shadow transition flex items-center gap-2">
                        <i class="fas fa-plus"></i> Isi Data Baru
                    </button>
                    <?php } ?>
                </div>
            <?php } 
        } else { ?>
            <!-- Departemen / Proses Belum Terisi -->
            <div class="flex flex-col items-center justify-center p-16 text-center bg-amber-50 rounded-lg border border-amber-100">
                <i class="fas fa-exclamation-triangle text-6xl text-amber-200 mb-4"></i>
                <h3 class="text-xl font-bold text-amber-800 mb-2">Proses PPDB Belum Aktif</h3>
                <p class="text-amber-600 max-w-md">
                    Belum ada Proses Penerimaan Siswa Baru yang aktif untuk Departemen <strong><?=$departemen?></strong>. Silakan isi terlebih dahulu di menu <strong>Proses Penerimaan</strong> pada bagian PPDB.
                </p>
            </div>
        <?php } ?>
    </div>

</div>

<?php CloseDb(); ?>
</body>
</html>