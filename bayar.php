<!DOCTYPE html>
<html>

<head>

    <title>SPP ONLINE</title>

    <link rel="stylesheet"
          type="text/css"
          href="src/style.css">

</head>

<body>

<br>
<br>

<header>

    <h1 class="judul">
        PEMBAYARAN SPP ONLINE SMK NEGERI 3 BANJARBARU
    </h1>

</header>

<div class="wrap">

    <div class="blog">

        <div class="contendo">

            <h3>Transaksi Pembayaran SPP</h3>

            <form method="get" action="">

                nisn:

                <input type="text"
                       name="nisn">

                <input type="hidden"
                       id="open"
                       name="open"
                       value="data_pembayaran">

                <input type="submit"
                       name="cari"
                       value="Cari Siswa">

            </form>


<?php

include_once "src/koneksi.php";


/* Mengecek apakah NISN sudah dimasukkan */
if (isset($_GET['nisn']) && $_GET['nisn'] != '') {

    $nisn_input = $_GET['nisn'];


    /* Mencari data siswa */
    $sqlSiswa = mysqli_query(
        $koneksi,

        "SELECT *
        FROM kelas
        JOIN siswa
        ON kelas.id_kelas = siswa.id_kelas
        JOIN spp
        ON siswa.id_spp = spp.id_spp
        WHERE siswa.nisn='$nisn_input'"
    );


    /* Mengambil data siswa */
    $data = mysqli_fetch_array($sqlSiswa);


    /* Mengecek apakah data siswa ditemukan */
    if ($data) {

?>

<h3>Biodata Siswa</h3>

<table>

    <tr>

        <td>nisn</td>

        <td>:</td>

        <td>
            <?php echo $data['nisn']; ?>
        </td>

    </tr>


    <tr>

        <td>Nama Siswa</td>

        <td>:</td>

        <td>
            <?php echo $data['nama']; ?>
        </td>

    </tr>


    <tr>

        <td>Kelas</td>

        <td>:</td>

        <td>
            <?php echo $data['nama_kelas']; ?>
        </td>

    </tr>

</table>


<h3>Tagihan SPP Siswa</h3>

<table border="1">

    <tr>

        <th>No</th>

        <th>Bulan</th>

        <th>Tahun</th>

        <th>No. Bayar</th>

        <th>Tgl. Bayar</th>

        <th>Jumlah</th>

        <th>Keterangan</th>

    </tr>


<?php

    /* Mengambil data pembayaran siswa */

    $sql = mysqli_query(
        $koneksi,

        "SELECT *
        FROM pembayaran
        JOIN spp
        ON pembayaran.id_spp = spp.id_spp
        WHERE pembayaran.nisn='$nisn_input'"
    );


    $no = 1;


    /* Mengecek apakah ada data pembayaran */

    if (mysqli_num_rows($sql) > 0) {

        while ($dataBayar = mysqli_fetch_array($sql)) {

            echo "<tr>";

            echo "<td>" . $no . "</td>";

            echo "<td>" . $dataBayar['bulan_tagihan'] . "</td>";

            echo "<td>" . $dataBayar['tahun_tagihan'] . "</td>";

            echo "<td>" . $dataBayar['no_bayar'] . "</td>";

            echo "<td>" . $dataBayar['tgl_pembayaran'] . "</td>";

            echo "<td>" . $dataBayar['nominal'] . "</td>";

            echo "<td>" . $dataBayar['keterangan'] . "</td>";

            echo "</tr>";

            $no++;

        }

    } else {

        echo "<tr>";

        echo "<td colspan='7'>";

        echo "Belum ada data pembayaran.";

        echo "</td>";

        echo "</tr>";

    }

?>

</table>


<?php

    } else {

        /* Jika NISN tidak ditemukan */

        echo "<p>";

        echo "Data siswa dengan NISN <b>" .
             htmlspecialchars($nisn_input) .
             "</b> tidak ditemukan.";

        echo "</p>";

    }

}

?>

        </div>

    </div>

</div>

</body>

</html>