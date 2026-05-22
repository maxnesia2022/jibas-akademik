<?php
// =========================================================================
// INIT & INCLUDE FILES
// =========================================================================
require_once('../include/errorhandler.php');
require_once('../include/db_functions.php');
require_once('../include/sessioninfo.php');
require_once('../include/common.php');
require_once('../include/config.php');
require_once('../cek.php');

OpenDb();

// =========================================================================
// PENANGKAPAN PARAMETER
// =========================================================================
$bagian = isset($_REQUEST["bagian"]) ? $_REQUEST["bagian"] : "-1";
$varbaris = isset($_REQUEST['varbaris']) ? $_REQUEST['varbaris'] : 20;
$page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 0;
$hal = isset($_REQUEST['hal']) ? $_REQUEST['hal'] : 0;
$op = isset($_REQUEST['op']) ? $_REQUEST['op'] : '';
$urut = isset($_REQUEST['urut']) ? $_REQUEST['urut'] : "nama";	
$urutan = isset($_REQUEST['urutan']) ? $_REQUEST['urutan'] : "ASC";	

// =========================================================================
// PROSES AKSI
// =========================================================================

// Aksi: Ubah Status Aktif
if ($op == "dw8dxn8w9ms8zs22") {
	$sql = "UPDATE pegawai SET aktif = '$_REQUEST[newaktif]' WHERE replid = '$_REQUEST[replid]' ";
	QueryDb($sql);
}
// Aksi: Hapus Pegawai
else if ($op == "xm8r389xemx23xb2378e23") {
    // -- v31 -- 2025-05-26
    $sql = "DELETE FROM riwayatfoto WHERE nip = (SELECT nip FROM pegawai WHERE replid = '$_REQUEST[replid]')";
    QueryDb($sql);

    // -- v31 -- 2025-05-26
    $sql = "DELETE FROM tambahandatapegawai WHERE nip = (SELECT nip FROM pegawai WHERE replid = '$_REQUEST[replid]')";
    QueryDb($sql);

	$sql = "DELETE FROM pegawai WHERE replid = '$_REQUEST[replid]'";
	QueryDb($sql);

	$page = 0;
	$hal = 0;
}
// Aksi: Ganti PIN
else if ($op == "fdgfde342ft45tgwer34rfwef") {
	$pin = random(5);
	$sql = "UPDATE pegawai SET `$_REQUEST[field]` = '$pin' WHERE nip = '$_REQUEST[nip]'";
	QueryDb($sql);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kepegawaian</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <script type="text/javascript">
        var base_url = "pegawai.php";

        function refresh() {
            var bagian = document.getElementById("bagian").value;
            window.location.href = base_url + "?bagian=" + encodeURIComponent(bagian) + "&page=<?=$page?>&hal=<?=$hal?>&varbaris=<?=$varbaris?>&urut=<?=$urut?>&urutan=<?=$urutan?>";
        }

        function change_bagian() {
            var bagian = document.getElementById("bagian").value;
            window.location.href = base_url + "?bagian=" + encodeURIComponent(bagian) + "&varbaris=<?=$varbaris?>";
        }

        function setaktif(replid, aktif) {
            var bagian = document.getElementById("bagian").value;
            var msg, newaktif;
            
            if (aktif == 1) {
                msg = "Apakah anda yakin akan mengubah status pegawai ini menjadi TIDAK AKTIF?";
                newaktif = 0;
            } else {	
                msg = "Apakah anda yakin akan mengubah status pegawai ini menjadi AKTIF?";
                newaktif = 1;
            }
            
            if (confirm(msg)) 
                window.location.href = base_url + "?op=dw8dxn8w9ms8zs22&replid="+replid+"&newaktif="+newaktif+"&bagian="+encodeURIComponent(bagian)+"&page=<?=$page?>&hal=<?=$hal?>&varbaris=<?=$varbaris?>&urut=<?=$urut?>&urutan=<?=$urutan?>";
        }

        function hapus(replid) {
            var bagian = document.getElementById("bagian").value;
            if (confirm("Apakah anda yakin akan menghapus pegawai ini?"))
                window.location.href = base_url + "?op=xm8r389xemx23xb2378e23&replid="+replid+"&bagian="+encodeURIComponent(bagian)+"&page=<?=$page?>&hal=<?=$hal?>&varbaris=<?=$varbaris?>&urut=<?=$urut?>&urutan=<?=$urutan?>";
        }

        function change_urut(urut_baru, urutan_lama) {	
            var bagian = document.getElementById("bagian").value;
            var varbaris = document.getElementById("varbaris").value;
            var urutan_baru = (urutan_lama == "ASC") ? "DESC" : "ASC";
            
            window.location.href = base_url + "?bagian="+encodeURIComponent(bagian)+"&urut="+urut_baru+"&urutan="+urutan_baru+"&page=<?=$page?>&hal=<?=$hal?>&varbaris="+varbaris;
        }

        function tambah() {
            var bagian = document.getElementById("bagian").value;
            window.open('pegawai_add.php?bagian='+encodeURIComponent(bagian), 'TambahPegawai', 'width=500,height=650,resizable=1,scrollbars=1');
        }

        function lihat(replid) {	
            window.open('pegawai_view.php?replid='+replid, 'LihatPegawai', 'width=790,height=610,resizable=0,scrollbars=1');
        }

        function edit(replid) {
            window.open('pegawai_edit.php?replid='+replid, 'UbahPegawai', 'width=535,height=650,resizable=1,scrollbars=1');
        }

        function cetak() {
            var bagian = document.getElementById("bagian").value;
            var total = document.getElementById("total").value;
            window.open('pegawai_cetak.php?bagian='+encodeURIComponent(bagian)+'&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris=<?=$varbaris?>&page=<?=$page?>&total='+total, 'CetakPegawai', 'width=790,height=650,resizable=1,scrollbars=1');
        }

        function cetak_detail(replid) {
            window.open('pegawai_cetak_detail.php?replid='+replid, 'CetakDetailPegawai', 'width=790,height=650,resizable=1,scrollbars=1');
        }

        function change_hal() {
            var bagian = document.getElementById("bagian").value;
            var hal = document.getElementById("hal").value;
            var varbaris = document.getElementById("varbaris").value;
            window.location.href = base_url + "?bagian="+encodeURIComponent(bagian)+"&page="+hal+"&hal="+hal+"&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris="+varbaris;
        }

        function change_baris() {
            var bagian = document.getElementById("bagian").value;
            var varbaris = document.getElementById("varbaris").value;
            window.location.href = base_url + "?bagian="+encodeURIComponent(bagian)+"&urut=<?=$urut?>&urutan=<?=$urutan?>&varbaris="+varbaris;
        }

        function gantipin(field, nip) {
            if (confirm("Apakah anda yakin akan mengganti PIN ini?")) {
                var bagian = document.getElementById("bagian").value;
                var hal = document.getElementById("hal").value;
                var varbaris = document.getElementById("varbaris").value;
                window.location.href = base_url + "?op=fdgfde342ft45tgwer34rfwef&bagian="+encodeURIComponent(bagian)+"&page=<?=$page?>&hal="+hal+"&varbaris="+varbaris+"&urut=<?=$urut?>&urutan=<?=$urutan?>&field="+field+"&nip="+nip;
            }	
        }

        function exel() {
            window.open('pegawai_excel.php', 'ExcelPegawai', 'width=790,height=650,resizable=1,scrollbars=1');
        }
    </script>
</head>
<body class="bg-gray-100 font-sans p-4" onload="document.getElementById('bagian').focus()">

<div class="max-w-7xl mx-auto space-y-4">

    <!-- ========================================================================= -->
    <!-- BAGIAN 1: HEADER / FILTER                                                 -->
    <!-- ========================================================================= -->
    <div class="bg-white rounded-lg shadow-sm border border-emerald-100 p-5">
        
        <!-- Breadcrumb / Title -->
        <div class="flex justify-between items-center mb-4 border-b border-gray-100 pb-3">
            <h2 class="text-xl font-bold text-emerald-900 flex items-center gap-2">
                <i class="fas fa-user-tie text-emerald-500"></i> Kepegawaian
            </h2>
            <div class="text-sm text-gray-500 hidden sm:block">
                Referensi <i class="fas fa-chevron-right text-xs mx-1"></i> Kepegawaian
            </div>
        </div>

        <div class="flex flex-col md:flex-row justify-between items-end gap-4">
            <!-- Bagian Filter -->
            <div class="w-full md:w-1/3">
                <label class="block text-sm font-semibold text-gray-700 mb-1">Bagian</label>
                <select name="bagian" id="bagian" onchange="change_bagian()" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition">
                    <option value="-1" <?=($bagian == "-1") ? "selected" : ""?>>Semua Bagian</option>
                    <?php
                    $sql_bag = "SELECT bagian FROM bagianpegawai ORDER BY urutan";    
                    $result_bag = QueryDB($sql_bag);
                    while ($row_bag = @mysqli_fetch_array($result_bag)) {
                    ?>
                        <option value="<?=$row_bag['bagian']?>" <?=StringIsSelected($row_bag['bagian'], $bagian)?>>
                            <?=$row_bag['bagian']?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-wrap gap-2 w-full md:w-auto">
                <button onclick="refresh()" class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium py-1.5 px-3 rounded shadow-sm border border-gray-300 transition flex items-center gap-2">
                    <i class="fas fa-sync-alt"></i> Refresh
                </button>
                <button onclick="exel()" class="bg-green-600 hover:bg-green-700 text-white text-sm font-medium py-1.5 px-3 rounded shadow-sm transition flex items-center gap-2">
                    <i class="fas fa-file-excel"></i> Excel
                </button>
                <button onclick="cetak()" class="bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium py-1.5 px-3 rounded shadow-sm transition flex items-center gap-2">
                    <i class="fas fa-print"></i> Cetak
                </button>
                <?php if (SI_USER_LEVEL() != $SI_USER_STAFF) { ?>
                <button onclick="tambah()" class="bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium py-1.5 px-3 rounded shadow-sm transition flex items-center gap-2">
                    <i class="fas fa-plus"></i> Tambah Pegawai
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
        $where_bagian = ($bagian != "-1") ? "WHERE bagian='$bagian'" : "";
        
        // Query Total
        $sql_tot = "SELECT COUNT(*) FROM pegawai $where_bagian";
        $result_tot = QueryDb($sql_tot);
        $row_tot = mysqli_fetch_row($result_tot);
        $jumlah = $row_tot[0];
        $total = ceil($jumlah / (int)$varbaris);

        // Query Data
        $sql_pegawai = "SELECT * FROM pegawai $where_bagian ORDER BY $urut $urutan LIMIT ".(int)$page*(int)$varbaris.",$varbaris";
        $result_pegawai = QueryDb($sql_pegawai);

        if ($jumlah > 0) {
        ?>
            <input type="hidden" name="total" id="total" value="<?=$total?>"/>
            
            <div class="text-sm text-gray-600 mb-4">
                Menampilkan <span class="font-bold text-gray-900"><?=$jumlah?></span> pegawai.
            </div>

            <!-- Table Data -->
            <div class="overflow-x-auto rounded-lg border border-gray-200">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-emerald-50">
                        <tr>        
                            <th class="px-4 py-3 text-center text-xs font-bold text-emerald-800 uppercase tracking-wider w-12 text-center">No</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-emerald-800 uppercase tracking-wider cursor-pointer hover:bg-emerald-100 transition" onClick="change_urut('nip','<?=$urutan?>')">
                                NIP <i class="fas fa-sort text-emerald-300 ml-1"></i>
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-emerald-800 uppercase tracking-wider cursor-pointer hover:bg-emerald-100 transition" onClick="change_urut('nama','<?=$urutan?>')">
                                Nama <i class="fas fa-sort text-emerald-300 ml-1"></i>
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-emerald-800 uppercase tracking-wider cursor-pointer hover:bg-emerald-100 transition" onClick="change_urut('tmplahir','<?=$urutan?>')">
                                Tempat, Tanggal Lahir <i class="fas fa-sort text-emerald-300 ml-1"></i>
                            </th>
                            <th class="px-4 py-3 text-center text-xs font-bold text-emerald-800 uppercase tracking-wider cursor-pointer hover:bg-emerald-100 transition" onClick="change_urut('pinpegawai','<?=$urutan?>')">
                                PIN <i class="fas fa-sort text-emerald-300 ml-1"></i>
                            </th>
                            <th class="px-4 py-3 text-center text-xs font-bold text-emerald-800 uppercase tracking-wider cursor-pointer hover:bg-emerald-100 transition" onClick="change_urut('aktif','<?=$urutan?>')">
                                Status <i class="fas fa-sort text-emerald-300 ml-1"></i>
                            </th>
                            <th class="px-4 py-3 text-center text-xs font-bold text-emerald-800 uppercase tracking-wider w-36">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php 
                        $cnt = ($page == 0) ? 1 : ((int)$page * (int)$varbaris + 1);
                        while ($row_pegawai = mysqli_fetch_array($result_pegawai)) {
                        ?>    
                        <tr class="hover:bg-emerald-50 transition duration-150">                    
                            <td class="px-4 py-2 text-center text-gray-500"><?=$cnt?></td>
                            <td class="px-4 py-2 text-gray-800 font-medium"><?=$row_pegawai['nip']?></td>
                            <td class="px-4 py-2 text-gray-900 font-bold"><?=$row_pegawai['nama']?></td>
                            <td class="px-4 py-2 text-gray-600">
                                <?=$row_pegawai['tmplahir']?>, <?=format_tgl($row_pegawai['tgllahir'])?>
                            </td>
                            <td class="px-4 py-2 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <span class="font-mono text-emerald-700"><?=$row_pegawai['pinpegawai']?></span>
                                    <?php if (SI_USER_LEVEL() != $SI_USER_STAFF) { ?>
                                        <button onclick="gantipin('pinpegawai','<?=$row_pegawai['nip']?>')" class="text-gray-400 hover:text-emerald-600 transition" title="Ganti PIN">
                                            <i class="fas fa-sync-alt text-xs"></i>
                                        </button>
                                    <?php } ?>
                                </div>
                            </td>
                            
                            <!-- Kolom Status -->
                            <td class="px-4 py-2 text-center">
                                <?php 
                                if (SI_USER_LEVEL() == $SI_USER_STAFF) {  
                                    if ($row_pegawai['aktif'] == 1) {
                                        echo '<span class="px-2 py-1 inline-flex text-xs leading-4 font-semibold rounded-full bg-emerald-100 text-emerald-800">Aktif</span>';
                                    } else {
                                        echo '<span class="px-2 py-1 inline-flex text-xs leading-4 font-semibold rounded-full bg-red-100 text-red-800">Tidak Aktif</span>';
                                    }
                                } else { 
                                    if ($row_pegawai['aktif'] == 1) { ?>
                                        <button onclick="setaktif(<?=$row_pegawai['replid']?>, <?=$row_pegawai['aktif']?>)" class="px-2 py-1 inline-flex text-xs leading-4 font-semibold rounded-full bg-emerald-100 text-emerald-800 hover:bg-emerald-200 transition" title="Klik untuk Non-Aktifkan">Aktif</button>
                                    <?php } else { ?>
                                        <button onclick="setaktif(<?=$row_pegawai['replid']?>, <?=$row_pegawai['aktif']?>)" class="px-2 py-1 inline-flex text-xs leading-4 font-semibold rounded-full bg-gray-100 text-gray-600 hover:bg-gray-200 transition" title="Klik untuk Aktifkan">Tidak Aktif</button>
                                    <?php }
                                } ?>            
                            </td>
                            
                            <!-- Kolom Aksi -->
                            <td class="px-4 py-2 text-center flex justify-center gap-3">
                                <button onclick="lihat(<?=$row_pegawai['replid']?>)" class="text-blue-500 hover:text-blue-700 transition" title="Detail Data Pegawai">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <?php if (SI_USER_LEVEL() != $SI_USER_STAFF) { ?>                
                                    <button onclick="cetak_detail(<?=$row_pegawai['replid']?>)" class="text-gray-500 hover:text-gray-700 transition" title="Cetak Detail">
                                        <i class="fas fa-print"></i>
                                    </button>
                                    <button onclick="edit(<?=$row_pegawai['replid']?>)" class="text-amber-500 hover:text-amber-700 transition" title="Ubah Data">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button onclick="hapus(<?=$row_pegawai['replid']?>)" class="text-red-500 hover:text-red-700 transition" title="Hapus Data">
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
                    <?php for ($m=10; $m <= 100; $m=$m+10) { ?>
                        <option value="<?=$m?>" <?=IntIsSelected($varbaris,$m)?>><?=$m?></option>
                    <?php } ?>
                    </select>
                    <span class="text-gray-600">baris per halaman</span>
                </div>
            </div>

        <?php } else { ?>
            <!-- Empty State -->
            <div class="flex flex-col items-center justify-center p-16 text-center bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
                <i class="fas fa-users-slash text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-xl font-bold text-gray-700 mb-2">Data Tidak Ditemukan</h3>
                <p class="text-gray-500 max-w-md mb-6">
                    Tidak ditemukan data pegawai untuk kriteria bagian ini.
                </p>
                <?php if (SI_USER_LEVEL() != $SI_USER_STAFF) { ?>
                <button onclick="tambah()" class="bg-emerald-600 hover:bg-emerald-700 text-white font-medium py-2 px-6 rounded shadow transition flex items-center gap-2">
                    <i class="fas fa-plus"></i> Isi Data Baru
                </button>
                <?php } ?>
            </div>
        <?php } ?>
    </div>

</div>

<?php CloseDb(); ?>
</body>
</html>