<?php
include_once "../src/koneksi.php";

// SKRIP TOMBOL SIMPAN DIKLIK
if (isset($_POST['btnSimpan'])) {

    $nisn       = $_POST['nisn'];
    $nis        = $_POST['nis'];
    $nama       = $_POST['nama'];
    $id_kelas   = $_POST['id_kelas'];
    $alamat     = $_POST['alamat'];
    $no_telp    = $_POST['no_telp'];

    // Validasi Form Inputs
    $pesanError = array();

    if (trim($nisn) == "") {
        $pesanError[] = "Data <b>NISN</b> tidak boleh kosong !";
    }

    if (trim($nis) == "") {
        $pesanError[] = "Data <b>NIS</b> tidak boleh kosong !";
    }

    if (trim($nama) == "") {
        $pesanError[] = "Data <b>NAMA SISWA</b> tidak boleh kosong !";
    }

    if (trim($id_kelas) == "") {
        $pesanError[] = "Data <b>KELAS</b> tidak boleh kosong !";
    }

    if (trim($alamat) == "") {
        $pesanError[] = "Data <b>ALAMAT</b> tidak boleh kosong !";
    }

    if (trim($no_telp) == "") {
        $pesanError[] = "Data <b>NO. TELEPON</b> tidak boleh kosong !";
    }

    // Menampilkan Pesan Error dari Form
    if (count($pesanError) >= 1) {

        $noPesan = 0;

        foreach ($pesanError as $indeks => $pesan_tampil) {
            $noPesan++;
            echo "&nbsp; $noPesan. $pesan_tampil <br>";
        }

        echo "<br>";

    } else {

        // Skrip Edit data ke Database
        $sqlEdit = mysqli_query(
            $koneksi,
            "UPDATE siswa SET
            nis='$nis',
            nama='$nama',
            id_kelas='$id_kelas',
            alamat='$alamat',
            no_telp='$no_telp'
            WHERE nisn='$nisn'"
        );

        if ($sqlEdit) {

            echo "<center>
                    <b>
                        <font color='red' size='4'>
                            <p>Data Berhasil Diubah</p>
                        </font>
                    </b>
                  </center>
                  <br>";

            echo "<meta http-equiv='refresh' content='2; url=?open=data_siswa'>";

        } else {

            echo "<center>
                    <b>
                        <font color='red' size='4'>
                            <p>Data Gagal Diubah!</p>
                        </font>
                    </b>
                  </center>
                  <br>";

            echo "<meta http-equiv='refresh' content='2; url=?open=data_siswa'>";
        }

        exit;
    }
}


// MEMBACA DATA DARI DATABASE UNTUK DI EDIT
$nisn = $_GET['nisn'];

$sql = mysqli_query(
    $koneksi,
    "SELECT * FROM siswa WHERE nisn='$nisn'"
);

$data = mysqli_fetch_array($sql);

?>

<form action="<?php $_SERVER['PHP_SELF']; ?>" method="post">

    <table cellspacing="1" cellpadding="3">

        <tr>
            <td bgcolor="#CCCCCC">
                <strong>EDIT DATA SISWA</strong>
            </td>
            <td bgcolor="#CCCCCC">&nbsp;</td>
            <td bgcolor="#CCCCCC">&nbsp;</td>
        </tr>

        <tr>
            <td>NISN</td>
            <td>:</td>
            <td>
                <input
                    name="nisn"
                    type="text"
                    value="<?php echo $data['nisn']; ?>"
                    size="20"
                    maxlength="20"
                    readonly="true"
                />
            </td>
        </tr>

        <tr>
            <td>NIS</td>
            <td>:</td>
            <td>
                <input
                    name="nis"
                    type="text"
                    value="<?php echo $data['nis']; ?>"
                    size="20"
                    maxlength="20"
                />
            </td>
        </tr>

        <tr>
            <td>Nama Siswa</td>
            <td>:</td>
            <td>
                <input
                    name="nama"
                    type="text"
                    value="<?php echo $data['nama']; ?>"
                    size="50"
                    maxlength="50"
                />
            </td>
        </tr>

        <tr>
            <td>Kelas</td>
            <td>:</td>
            <td>

                <select name="id_kelas">

                    <option value="">
                        -- Pilih Kelas --
                    </option>

                    <?php
                    $queryKelas = mysqli_query(
                        $koneksi,
                        "SELECT * FROM kelas ORDER BY nama_kelas ASC"
                    );

                    while ($kelas = mysqli_fetch_array($queryKelas)) {

                        if ($kelas['id_kelas'] == $data['id_kelas']) {
                            $selected = "selected";
                        } else {
                            $selected = "";
                        }
                    ?>

                        <option
                            value="<?php echo $kelas['id_kelas']; ?>"
                            <?php echo $selected; ?>
                        >
                            <?php echo $kelas['nama_kelas']; ?>
                        </option>

                    <?php
                    }
                    ?>

                </select>

            </td>
        </tr>

        <tr>
            <td>Alamat</td>
            <td>:</td>
            <td>
                <input
                    name="alamat"
                    type="text"
                    value="<?php echo $data['alamat']; ?>"
                    size="50"
                    maxlength="100"
                />
            </td>
        </tr>

        <tr>
            <td>No. Telepon</td>
            <td>:</td>
            <td>
                <input
                    name="no_telp"
                    type="text"
                    value="<?php echo $data['no_telp']; ?>"
                    size="30"
                    maxlength="15"
                />
            </td>
        </tr>

        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>
                <input
                    name="btnSimpan"
                    type="submit"
                    id="btnSimpan"
                    value="Simpan"
                />
            </td>
        </tr>

    </table>

</form>