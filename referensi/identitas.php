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
$departemen = isset($_REQUEST['departemen']) ? $_REQUEST['departemen'] : '';
	
$title = "Sekolah";
if ($departemen == 'yayasan') {
	$title = "";
}

// =========================================================================
// PROSES AKSI (HAPUS DATA)
// =========================================================================
$op = isset($_REQUEST['op']) ? $_REQUEST['op'] : '';

if ($op == "delheader") {
	$sql = "SELECT foto FROM identitas WHERE departemen='$departemen'";
	$result = QueryDb($sql);
	$row = @mysqli_fetch_row($result);
	if ($row[0] != '') {
		$sql = "UPDATE identitas SET nama=NULL, situs=NULL, email=NULL, alamat1=NULL, 
					   alamat2=NULL, telp1=NULL, telp2=NULL, telp3=NULL, telp4=NULL, fax1=NULL, fax2=NULL 
				 WHERE departemen = '$departemen'";
    } else {
		$sql = "DELETE FROM identitas WHERE departemen = '$departemen'";
    }
	QueryDb($sql);		
}

if ($op == "dellogo") {
	$sql = "SELECT nama FROM identitas WHERE departemen='$departemen'";
	$result = QueryDb($sql);
	$row = @mysqli_fetch_row($result);
	if ($row[0] != '') {
		$sql = "UPDATE identitas SET foto=NULL WHERE departemen = '$departemen'";
    } else {
		$sql = "DELETE FROM identitas WHERE departemen = '$departemen'";
    }
	QueryDb($sql);		
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Identitas Sekolah</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <script type="text/javascript">
        function tambah_logo() {
            var departemen = document.getElementById('departemen').value;
            window.open('logo2.php?departemen='+departemen, 'InputLogoSekolah','width=550,height=305,resizable=1,scrollbars=1');
        }

        function tambah() {
            var departemen = document.getElementById('departemen').value;
            window.open('identitas_add.php?departemen='+departemen, 'InputIdentitasSekolah','width=675,height=430,resizable=1,scrollbars=1');
        }

        function getfresh() {
            var departemen = document.getElementById('departemen').value;
            window.location.href = "identitas.php?departemen="+departemen;
        }

        function edit() {
            var departemen = document.getElementById('departemen').value;
            window.open('identitas_edit.php?departemen='+departemen, 'UbahIdentitasSekolah','width=675,height=430,resizable=1,scrollbars=1');
        }

        function hapus(bagian) {
            var departemen = document.getElementById('departemen').value;
            if (bagian == 'header') {
                if (confirm("Apakah anda yakin akan menghapus identitas sekolah ini?"))
                    window.location.href = "identitas.php?op=delheader&departemen="+departemen;
            } else if (bagian == 'logo') {
                if (confirm("Apakah anda yakin akan menghapus identitas sekolah ini?"))
                    window.location.href = "identitas.php?op=dellogo&departemen="+departemen;
            }
        }

        function chg_dep() {
            var departemen = document.getElementById('departemen').value;
            window.location.href = "identitas.php?departemen="+departemen;
        }

        function cetak() {
            var departemen = document.getElementById('departemen').value;
            window.open('kop_cetak.php?departemen='+departemen, 'CetakHeader','width=790,height=650,resizable=1,scrollbars=1');
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
                <i class="fas fa-id-badge text-emerald-500"></i> Identitas Sekolah
            </h2>
            <div class="text-sm text-gray-500 hidden sm:block">
                Referensi <i class="fas fa-chevron-right text-xs mx-1"></i> Identitas Sekolah
            </div>
        </div>

        <div class="flex flex-col md:flex-row justify-between items-end gap-4">
            <!-- Departemen -->
            <div class="w-full md:w-1/3">
                <label class="block text-sm font-semibold text-gray-700 mb-1">Departemen</label>
                <select name="departemen" id="departemen" onchange="chg_dep()" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition">
                    <option value="yayasan" <?=StringIsSelected($departemen,'yayasan')?>>Umum</option>
                    <?php
                    $res = QueryDb("SELECT departemen FROM departemen WHERE aktif=1 ORDER BY urutan");
                    while ($r = @mysqli_fetch_array($res)) {
                        if ($departemen == "") $departemen = $r['departemen'];
                    ?>
                        <option value="<?=$r['departemen']?>" <?=StringIsSelected($departemen, $r['departemen'])?>><?=$r['departemen']?></option>
                    <?php } ?>
                </select>
            </div>

            <!-- Tombol Cetak -->
            <div class="w-full md:w-auto">
                <button onclick="cetak()" class="w-full md:w-auto bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-md shadow-sm transition duration-150 ease-in-out flex items-center justify-center gap-2 text-sm">
                    <i class="fas fa-print"></i> Cetak KOP Surat
                </button>
            </div>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- BAGIAN 2: CONTENT AREA                                                    -->
    <!-- ========================================================================= -->
    <div class="bg-white rounded-lg shadow-sm border border-emerald-100 p-8">
        <?php
        $replid = 0;
        $sql = "SELECT * FROM identitas WHERE departemen='$departemen' ORDER BY replid DESC LIMIT 1";
        $result = QueryDb($sql);
        $row = @mysqli_fetch_array($result);
        if (mysqli_num_rows($result) > 0) {
            $replid = $row['replid'];
        }
        ?>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <!-- Kolom Logo -->
            <div class="md:col-span-1 flex flex-col items-center gap-4">
                <h3 class="text-sm font-bold text-emerald-800 uppercase tracking-wider mb-2">Logo <?=$title?></h3>
                
                <div class="w-full aspect-square bg-gray-50 border-2 border-dashed border-gray-200 rounded-xl flex items-center justify-center overflow-hidden p-4">
                    <?php if (empty($row['foto'])) { ?>
                        <div class="text-center p-4">
                            <i class="fas fa-image text-4xl text-gray-300 mb-2"></i>
                            <p class="text-xs text-red-500 leading-tight">
                                <a href="javascript:tambah_logo()" class="text-emerald-600 font-bold hover:underline">Klik di sini</a><br>
                                untuk memasukkan logo.
                            </p>
                        </div>
                    <?php } else { ?>
                        <img src="../library/gambar.php?replid=<?=$replid?>&table=identitas" class="max-w-full max-h-full object-contain" alt="Logo Sekolah" />
                    <?php } ?>
                </div>

                <?php if (!empty($row['foto']) && SI_USER_LEVEL() != $SI_USER_STAFF) { ?>
                    <div class="flex gap-2">
                        <button onclick="tambah_logo()" class="bg-amber-100 hover:bg-amber-200 text-amber-700 text-xs font-bold py-1.5 px-3 rounded-full transition flex items-center gap-1" title="Ubah Logo">
                            <i class="fas fa-edit"></i> Ubah
                        </button>
                        <button onclick="hapus('logo')" class="bg-red-100 hover:bg-red-200 text-red-700 text-xs font-bold py-1.5 px-3 rounded-full transition flex items-center gap-1" title="Hapus Logo">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </div>
                <?php } ?>
            </div>

            <!-- Kolom Header / Detail -->
            <div class="md:col-span-3">
                <h3 class="text-sm font-bold text-emerald-800 uppercase tracking-wider mb-4 border-b border-emerald-50 pb-2 text-center md:text-left">Informasi Header</h3>
                
                <?php if (!empty($row['nama'])) { ?>
                    <div class="bg-emerald-50 rounded-xl p-6 border border-emerald-100 space-y-4">
                        <h1 class="text-2xl md:text-3xl font-extrabold text-emerald-900"><?=$row['nama']?></h1>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm text-gray-700">
                            <!-- Lokasi 1 -->
                            <div class="space-y-1">
                                <p class="font-bold text-emerald-800"><i class="fas fa-map-marker-alt w-5 text-emerald-500"></i> Alamat:</p>
                                <p class="pl-5 leading-relaxed"><?=$row['alamat1']?></p>
                                <?php if (!empty($row['telp1']) || !empty($row['telp2'])) { ?>
                                    <p class="pl-5"><span class="font-semibold">Telp.</span> <?=implode(", ", array_filter([$row['telp1'], $row['telp2']]))?></p>
                                <?php } ?>
                                <?php if (!empty($row['fax1'])) { ?>
                                    <p class="pl-5"><span class="font-semibold">Fax.</span> <?=$row['fax1']?></p>
                                <?php } ?>
                            </div>

                            <!-- Lokasi 2 -->
                            <?php if (!empty($row['alamat2'])) { ?>
                            <div class="space-y-1">
                                <p class="font-bold text-emerald-800"><i class="fas fa-map-marker-alt w-5 text-emerald-500"></i> Lokasi 2:</p>
                                <p class="pl-5 leading-relaxed"><?=$row['alamat2']?></p>
                                <?php if (!empty($row['telp3']) || !empty($row['telp4'])) { ?>
                                    <p class="pl-5"><span class="font-semibold">Telp.</span> <?=implode(", ", array_filter([$row['telp3'], $row['telp4']]))?></p>
                                <?php } ?>
                                <?php if (!empty($row['fax2'])) { ?>
                                    <p class="pl-5"><span class="font-semibold">Fax.</span> <?=$row['fax2']?></p>
                                <?php } ?>
                            </div>
                            <?php } ?>

                            <!-- Kontak Digital -->
                            <div class="sm:col-span-2 pt-2 border-t border-emerald-200 mt-2 grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <?php if (!empty($row['situs'])) { ?>
                                    <p><i class="fas fa-globe w-5 text-emerald-500"></i> <span class="font-semibold text-emerald-800">Website:</span> <a href="http://<?=$row['situs']?>" target="_blank" class="text-blue-600 hover:underline"><?=$row['situs']?></a></p>
                                <?php } ?>
                                <?php if (!empty($row['email'])) { ?>
                                    <p><i class="fas fa-envelope w-5 text-emerald-500"></i> <span class="font-semibold text-emerald-800">Email:</span> <a href="mailto:<?=$row['email']?>" class="text-blue-600 hover:underline"><?=$row['email']?></a></p>
                                <?php } ?>
                            </div>
                        </div>
                    </div>

                    <?php if (SI_USER_LEVEL() != $SI_USER_STAFF) { ?>
                        <div class="mt-4 flex justify-end gap-3">
                            <button onclick="edit()" class="bg-amber-500 hover:bg-amber-600 text-white font-medium py-1.5 px-4 rounded shadow-sm transition flex items-center gap-2">
                                <i class="fas fa-edit"></i> Ubah Header
                            </button>
                            <button onclick="hapus('header')" class="bg-red-500 hover:bg-red-600 text-white font-medium py-1.5 px-4 rounded shadow-sm transition flex items-center gap-2">
                                <i class="fas fa-trash-alt"></i> Hapus Header
                            </button>
                        </div>
                    <?php } ?>

                <?php } else { ?>
                    <!-- Empty State -->
                    <div class="flex flex-col items-center justify-center p-12 text-center bg-gray-50 rounded-xl border-2 border-dashed border-gray-200">
                        <i class="fas fa-file-invoice text-5xl text-gray-300 mb-4"></i>
                        <p class="text-lg font-medium text-gray-600">Data identitas belum diisi.</p>
                        <p class="text-sm text-gray-400 mb-6">Silakan lengkapi informasi nama, alamat, dan kontak sekolah.</p>
                        <button onclick="tambah()" class="bg-emerald-600 hover:bg-emerald-700 text-white font-medium py-2 px-6 rounded-md shadow transition flex items-center gap-2">
                            <i class="fas fa-plus"></i> Isi Data Baru
                        </button>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>

</div>

<?php CloseDb(); ?>
</body>
</html>