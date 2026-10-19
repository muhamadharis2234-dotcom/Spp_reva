<?php
include_once "../src/koneksi.php";

// ======================================================
// PROSES SIMPAN DATA SISWA
// ======================================================
if (isset($_POST['btnSimpan'])) {

    $nisn     = $_POST['nisn'];
    $nis      = $_POST['nis'];
    $nama     = $_POST['nama'];
    $id_kelas = $_POST['id_kelas'];
    $alamat   = $_POST['alamat'];
    $no_telp  = $_POST['no_telp'];
    $id_spp   = $_POST['id_spp'];

    // Daftar bulan tagihan
    $arraybulan = array(
        "Juli",
        "Agustus",
        "September",
        "Oktober",
        "Nopember",
        "Desember",
        "Januari",
        "Februari",
        "Maret",
        "April",
        "Mei",
        "Juni"
    );

    // ==================================================
    // VALIDASI
    // ==================================================
    $pesanError = array();

    if (trim($nisn) == "") {
        $pesanError[] = "Data <b>NISN</b> tidak boleh kosong!";
    }

    if (trim($nis) == "") {
        $pesanError[] = "Data <b>NIS</b> tidak boleh kosong!";
    }

    if (trim($nama) == "") {
        $pesanError[] = "Data <b>NAMA</b> tidak boleh kosong!";
    }

    if (trim($id_kelas) == "" || $id_kelas == "Kosong") {
        $pesanError[] = "Data <b>KELAS</b> harus dipilih!";
    }

    if (trim($alamat) == "") {
        $pesanError[] = "Data <b>ALAMAT</b> tidak boleh kosong!";
    }

    if (trim($no_telp) == "") {
        $pesanError[] = "Data <b>NOMOR TELEPON</b> tidak boleh kosong!";
    }

    if (trim($id_spp) == "" || $id_spp == "Kosong") {
        $pesanError[] = "Data <b>SPP</b> harus dipilih!";
    }

    // ==================================================
    // JIKA ADA ERROR
    // ==================================================
    if (count($pesanError) >= 1) {

        $noPesan = 0;

        foreach ($pesanError as $pesan_tampil) {
            $noPesan++;

            echo $noPesan . ". " . $pesan_tampil . "<br>";
        }

        echo "<br>";
    }

    // ==================================================
    // JIKA TIDAK ADA ERROR
    // ==================================================
    else {

        // Simpan data siswa
        $sql = mysqli_query(
            $koneksi,
            "INSERT INTO siswa
            (nisn, nis, nama, id_kelas, alamat, no_telp, id_spp)
            VALUES
            ('$nisn', '$nis', '$nama', '$id_kelas', '$alamat', '$no_telp', '$id_spp')"
        );

        // ==================================================
        // JIKA BERHASIL DISIMPAN
        // ==================================================
        if ($sql) {

            // Tahun sekarang
            $tahunsekarang = date('Y');

            // Membuat tagihan 12 bulan
            for ($i = 0; $i < 12; $i++) {

                $bulan = $arraybulan[$i];

                // Januari sampai Juni masuk tahun berikutnya
                if ($i == 6) {
                    $tahunsekarang++;
                }

                mysqli_query(
                    $koneksi,
                    "INSERT INTO pembayaran
                    (nisn, bulan_tagihan, tahun_tagihan, id_spp)
                    VALUES
                    ('$nisn', '$bulan', '$tahunsekarang', '$id_spp')"
                );
            }

            // ==================================================
            // SETELAH BERHASIL -> DATA SISWA
            // ==================================================
            echo "
            <script>
                alert('Data siswa berhasil disimpan!');
                window.location.href = 'halaman_admin.php?open=data_siswa';
            </script>
            ";

            exit;
        }

        // ==================================================
        // JIKA GAGAL DISIMPAN
        // ==================================================
        else {

            echo "
            <script>
                alert('Data siswa gagal disimpan!');
                window.location.href = 'halaman_admin.php?open=tambah_siswa';
            </script>
            ";

            exit;
        }
    }
}
?>

<!-- ======================================================
     FORM TAMBAH SISWA
     ====================================================== -->

<form action="halaman_admin.php?open=tambah_siswa" method="post">

<table cellspacing="1" cellpadding="3">

    <tr>
        <td bgcolor="#CCCCCC" colspan="3">
            <strong>TAMBAH DATA SISWA</strong>
        </td>
    </tr>

    <tr>
        <td>NISN</td>
        <td>:</td>
        <td>
            <input
                name="nisn"
                type="text"
                size="10"
                maxlength="10"
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
                size="20"
                maxlength="10"
            />
        </td>
    </tr>

    <tr>
        <td>NAMA</td>
        <td>:</td>
        <td>
            <input
                name="nama"
                type="text"
                size="50"
                maxlength="50"
            />
        </td>
    </tr>

    <tr>
        <td>KELAS</td>
        <td>:</td>
        <td>

            <select name="id_kelas">

                <option value="Kosong">
                    ......
                </option>

                <?php

                $tampilkanKelas = mysqli_query(
                    $koneksi,
                    "SELECT * FROM kelas"
                );

                while ($bacaData = mysqli_fetch_array($tampilkanKelas)) {

                    echo "
                    <option value='{$bacaData['id_kelas']}'>
                        {$bacaData['nama_kelas']}
                    </option>
                    ";
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
                size="50"
                maxlength="50"
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
                size="50"
                maxlength="50"
            />
        </td>
    </tr>

    <tr>
        <td>Nominal</td>
        <td>:</td>
        <td>

            <select name="id_spp">

                <option value="Kosong">
                    ......
                </option>

                <?php

                $tampilkanSPP = mysqli_query(
                    $koneksi,
                    "SELECT * FROM spp"
                );

                while ($bacaData = mysqli_fetch_array($tampilkanSPP)) {

                    echo "
                    <option value='{$bacaData['id_spp']}'>
                        {$bacaData['nominal']}
                    </option>
                    ";
                }

                ?>

            </select>

        </td>
    </tr>

    <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>

            <input
                name="btnSimpan"
                type="submit"
                value="Simpan"
            />

        </td>
    </tr>

</table>

</form>