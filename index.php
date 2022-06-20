<?php
$server = 'localhost';
$dbName = 'catatan';
$dbUsername = 'root';
$dbPassword = '';

$koneksi    = mysqli_connect($server, $dbName, $dbUsername, $dbPassword);
if (!$koneksi) { //cek koneksi
    die("Tidak bisa terkoneksi ke database");
}
$sinyal     = "";
$level      = "";
$tanggal    = "";
$hargaidr   = "";
$hargausdt  = "";
$volidr     = "";
$volusdt    = "";
$lastbuy    = "";
$lastsell   = "";
$jenis      = "";
$sukses     = "";
$error      = "";

$jumlahDataPerHalaman = 100;
$jumlahData = mysqli_query($koneksi, "SELECT * FROM btc");
$totalData = mysqli_num_rows($jumlahData);
$halaman = ceil($totalData / $jumlahDataPerHalaman);

if (!isset ($_GET['page']) ) {  
    $page = 1;  
} else {  
    $page = $_GET['page'];  
}  

$halamanPertama = ($page-1) * $jumlahDataPerHalaman;


// $jum = mysqli_fetch_array($jumlahData);
// $jumlahHalaman = ceil($jumlahData/$jumlahDataPerHalaman);
// $halamanAktif = (isset($_GET["halaman"])) ? $_GET["halaman"] : 1;
// $awalData = ($jumlahDataPerHalaman * $halamanAktif) - $jumlahDataPerHalaman;
// $btc = $query("SELECT * FROM btc LIMIT $awalData, $jumlahDataPerHalaman");

if (isset($_GET['op'])) {
    $op = $_GET['op'];
} else {
    $op = "";
}
if ($op == 'delete') {
    $id         = $_GET['id'];
    $sql1       = "delete from btc where id = '$id'";
    $q1         = mysqli_query($koneksi, $sql1);
    if ($q1) {
        $sukses = "Berhasil hapus data";
    } else {
        $error  = "Gagal melakukan delete data";
    }
}
if ($op == 'edit') {
    $id         = $_GET['id'];
    $sql1       = "select * from btc where id = '$id'";
    $q1         = mysqli_query($koneksi, $sql1);
    $r1         = mysqli_fetch_array($q1);
    $sinyal     = $r1['sinyal'];
    $level      = $r1['level'];
    $tanggal    = $r1['tanggal'];
    $hargaidr   = $r1['hargaidr'];
    $hargausdt  = $r1['hargausdt'];
    $volidr     = $r1['volidr'];
    $volusdt    = $r1['volusdt'];
    $lastbuy    = $r1['lastbuy'];
    $lastsell   = $r1['lastsell'];
    $jenis      = $r1['jenis'];

    if ($id == '') {
        $error = "Data tidak ditemukan";
    }
}
if (isset($_POST['simpan'])) { //untuk create
    $sinyal     = $_POST['sinyal'];
    $level      = $_POST['level'];
    $tanggal    = $_POST['tanggal'];
    $hargaidr   = $_POST['hargaidr'];
    $hargausdt  = $_POST['hargausdt'];
    $volidr     = $_POST['volidr'];
    $volusdt    = $_POST['volusdt'];
    $lastbuy    = $_POST['lastbuy'];
    $lastsell   = $_POST['lastsell'];
    $jenis      = $_POST['jenis'];

    if ($sinyal && $level && $tanggal && $hargaidr && $hargausdt && $volidr && $volusdt && $lastbuy && $lastsell && $jenis) {
        if ($op == 'edit') { //untuk update
            $sql1       = "update btc set sinyal = '$sinyal', level='$level', tanggal='$tanggal', hargaidr='$hargaidr', hargausdt='$hargausdt', volidr='$volidr', volusdt='$volusdt', lastbuy='$lastbuy', lastsell='$lastsell', jenis='$jenis' where id = '$id'";
            $q1         = mysqli_query($koneksi, $sql1);
            if ($q1) {
                $sukses = "Data berhasil diupdate";
            } else {
                $error  = "Data gagal diupdate";
            }
        } else { //untuk insert
            $sql1   = "insert into btc(sinyal, level, tanggal, hargaidr, hargausdt, volidr, volusdt, lastbuy, lastsell, jenis) values ('$sinyal','$level','$tanggal','$hargaidr','$hargausdt','$volidr','$volusdt','$lastbuy','$lastsell','$jenis')";
            $q1     = mysqli_query($koneksi, $sql1);
            if ($q1) {
                $sukses     = "Berhasil memasukkan data baru";
            } else {
                $error      = "Gagal memasukkan data";
            }
        }
    } else {
        $error = "Silakan masukkan semua data";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="logo/bitcoin.png" />
    <title>Bitcoin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>

        .card {
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- untuk memasukkan data -->
        <div class="card">
            <div class="card-header">
                Create / Edit Data
            </div>
            <div class="card-body">
                <?php
                if ($error) {
                ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $error ?>
                    </div>
                <?php
                    header("refresh:5;url=index.php"); //5 : detik
                }
                ?>
                <?php
                if ($sukses) {
                ?>
                    <div class="alert alert-success" role="alert">
                        <?php echo $sukses ?>
                    </div>
                <?php
                    header("refresh:5;url=index.php");
                }
                ?>
                <form action="" method="POST">
                    <div class="mb-3 row">
                        <label for="sinyal" class="col-sm-2 col-form-label">Sinyal</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="sinyal" name="sinyal" value="<?php echo $sinyal ?>">
                        </div>
                    </div>
                    <div class="mb-3 row ">
                        <label for="level" class="col-sm-2 col-form-label">Level</label>
                        <div class="col-sm-10">
                            <select class="form-select" name="level" id="level">
                                <option selected>- Pilih Level -</option>
                                <option value="sama" <?php if ($level == "sama") echo "selected" ?>>Sama</option>
                                <option value="recover" <?php if ($level == "recover") echo "selected" ?>>Recover</option>
                                <option value="crash" <?php if ($level == "crash") echo "selected" ?>>Crash</option>
                                <option value="supercrash" <?php if ($level == "supercrash") echo "selected" ?>>SuperCrash</option>
                                <option value="megacrash" <?php if ($level == "megacrash") echo "selected" ?>>MegaCrash</option>
                                <option value="ultracrash" <?php if ($level == "ultracrash") echo "selected" ?>>UltraCrash</option>
                                <option value="goldencrash" <?php if ($level == "goldencrash") echo "selected" ?>>GoldenCrash</option>
                                <option value="diamondcrash" <?php if ($level == "diamondcrash") echo "selected" ?>>DiamondCrash</option>
                                <option value="moon" <?php if ($level == "moon") echo "selected" ?>>Moon</option>
                                <option value="supermoon" <?php if ($level == "supermoon") echo "selected" ?>>SuperMoon</option>
                                <option value="megamoon" <?php if ($level == "megamoon") echo "selected" ?>>MegaMoon</option>
                                <option value="ultramoon" <?php if ($level == "ultramoon") echo "selected" ?>>UltraMoon</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="tanggal" class="col-sm-2 col-form-label">Tanggal</label>
                        <div class="col-sm-10">
                            <input type="date"  class="form-control" id="tanggal" name="tanggal" value="<?php echo $tanggal ?>">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="hargaidr" class="col-sm-2 col-form-label">Harga IDR</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="hargaidr" name="hargaidr" value="<?php echo $hargaidr ?>">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="hargausdt" class="col-sm-2 col-form-label">Harga USDT</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="hargausdt" name="hargausdt" value="<?php echo $hargausdt ?>">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="volidr" class="col-sm-2 col-form-label">Volume IDR</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="volidr" name="volidr" value="<?php echo $volidr ?>">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="volusdt" class="col-sm-2 col-form-label">Volume USDT</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="volusdt" name="volusdt" value="<?php echo $volusdt ?>">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="lastbuy" class="col-sm-2 col-form-label">Last Buy</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="lastbuy" name="lastbuy" value="<?php echo $lastbuy ?>">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="lastsell" class="col-sm-2 col-form-label">Last Sell</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="lastsell" name="lastsell" value="<?php echo $lastsell ?>">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="jenis" class="col-sm-2 col-form-label">Jenis</label>
                        <div class="col-sm-10">
                            <select class="form-select" name="jenis" id="jenis">
                                <option selected>- Pilih Jenis -</option>
                                <option value="crash" <?php if ($jenis == "crash") echo "selected" ?>>Crash</option>
                                <option value="moon" <?php if ($jenis == "moon") echo "selected" ?>>Moon</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-12">
                        <input type="submit" name="simpan" value="Simpan Data" class="btn btn-primary" />
                    </div>
                </form>
            </div>
        </div>

        <!-- untuk mengeluarkan data -->
        <div class="table">
            <div class="card-header text-white bg-secondary">
                Penambangan Sinyal Harian Indodax
            </div>
            <div class="">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Sinyal</th>
                            <th scope="col">Level</th>
                            <th scope="col">Tanggal</th>
                            <th scope="col">Harga IDR</th>
                            <th scope="col">Harga USDT</th>
                            <th scope="col">Volume IDR</th>
                            <th scope="col">Volume USDT</th>
                            <th scope="col">Last Buy</th>
                            <th scope="col">Last Sell</th>
                            <th scope="col">Jenis</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // $sql2   = "SELECT * FROM btc ORDER BY id DESC LIMIT 500";
                        // $q2     = mysqli_query($koneksi, $sql2);
                    
                        $query = "SELECT * FROM btc LIMIT " . $halamanPertama.','. $jumlahDataPerHalaman;
                        $data = mysqli_query($koneksi, $query);
                        while ($r2 = mysqli_fetch_array($data)) {
                        ?>
                            <tr>
                                <td scope="row"><?= $r2['id'] ?></td>
                                <td scope="row"><?= $r2['sinyal'] ?></td>
                                <td scope="row"><?= $r2['level'] ?></td>
                                <td scope="row"><?= $r2['tanggal'] ?></td>
                                <td scope="row"><?= $r2['hargaidr'] ?></td>
                                <td scope="row"><?= $r2['hargausdt'] ?></td>
                                <td scope="row"><?= $r2['volidr'] ?></td>
                                <td scope="row"><?= $r2['volusdt'] ?></td>
                                <td scope="row"><?= $r2['lastbuy'] ?></td>
                                <td scope="row"><?= $r2['lastsell'] ?></td>
                                <td scope="row"><?= $r2['jenis'] ?></td>
                                <td scope="row">
                                    <a href="index.php?op=edit&id=<?php echo $id ?>"><button type="button" class="btn btn-warning">Edit</button></a>
                                    <a href="index.php?op=delete&id=<?php echo $id ?>" onclick="return confirm('Yakin mau delete data?')"><button type="button" class="btn btn-danger">Delete</button></a>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                        
                    </tbody>

                </table>
                
                <ul class="pagination overflow-auto">
                    <?php 
                        for($page = 1; $page <= $halaman; $page++){
                    ?>				
                    <li class="page-item"><a href="?page=<?= $page; ?>" class="page-link"><?= $page; ?></a></li>
                    <?php 
                        }
                    ?>
                </ul>
            </div>
        </div>
    </div>
</body>

</html>