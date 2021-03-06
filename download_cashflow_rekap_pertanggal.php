<?php // Fungsi header dengan mengirimkan raw data excel
header("Content-type: application/vnd-ms-excel");
 
// Mendefinisikan nama file ekspor "hasil-export.xls"
header("Content-Disposition: attachment; filename=cashflow_rekap_pertanggal.xls");

include 'db.php';
include 'sanitasi.php';

$kas = stringdoang($_GET['kasnya']);
$tanggal = stringdoang($_GET['tanggalnya']);


$select_nama_kas = $db->query("SELECT nama_daftar_akun FROM daftar_akun WHERE kode_daftar_akun = '$kas'");
$out_kas = mysqli_fetch_array($select_nama_kas);



$select_masuk_jumlah = $db->query("SELECT SUM(debit) AS masuk_jumlah FROM jurnal_trans WHERE DATE(waktu_jurnal) = '$tanggal' AND kode_akun_jurnal = '$kas' AND debit != '0' AND jenis_transaksi != 'Kas Mutasi'");
$out_masuk_jumlah = mysqli_fetch_array($select_masuk_jumlah);
$masuk = $out_masuk_jumlah['masuk_jumlah'];
//END QUERY MASUK


$select_keluar_jumlah = $db->query("SELECT SUM(kredit) AS keluar_jumlah FROM jurnal_trans WHERE DATE(waktu_jurnal) = '$tanggal' AND kode_akun_jurnal = '$kas' AND kredit != '0' AND jenis_transaksi != 'Kas Mutasi'");
$out_keluar_jumlah = mysqli_fetch_array($select_keluar_jumlah);
$keluar = $out_keluar_jumlah['keluar_jumlah'];
// END QUERY KELUAR

$g_mutasi_masuk = $db->query("SELECT SUM(debit) AS jumlah_mutasi_masuk FROM jurnal_trans WHERE DATE(waktu_jurnal) = '$tanggal' AND kode_akun_jurnal = '$kas' AND debit != '0' AND jenis_transaksi = 'Kas Mutasi'");
$jumlah_mutasi_masuk = mysqli_fetch_array($g_mutasi_masuk);
$mutasi_masuk = $jumlah_mutasi_masuk['jumlah_mutasi_masuk'];
// END QUERY MUTASI MASUK


$take_mutasi_keluar = $db->query("SELECT SUM(kredit) AS jumlah_mutasi_keluar FROM jurnal_trans WHERE DATE(waktu_jurnal) = '$tanggal' AND kode_akun_jurnal = '$kas' AND kredit != '0' AND jenis_transaksi = 'Kas Mutasi'");
$jumlah_mutasi_keluar = mysqli_fetch_array($take_mutasi_keluar);
$mutasi_keluar = $jumlah_mutasi_keluar['jumlah_mutasi_keluar'];
// END QUERY MUTASI KELUAR





				$sum_saldo1 = $db->query("SELECT SUM(debit) AS saldo1 FROM jurnal_trans WHERE DATE(waktu_jurnal) < '$tanggal' AND kode_akun_jurnal = '$kas'");
				$cek_saldo1 = mysqli_fetch_array($sum_saldo1);
				$saldo1 = $cek_saldo1['saldo1'];

				$sum_saldo2 = $db->query("SELECT SUM(kredit) AS saldo2 FROM jurnal_trans WHERE DATE(waktu_jurnal) < '$tanggal' AND kode_akun_jurnal = '$kas'");
				$cek_saldo2 = mysqli_fetch_array($sum_saldo2);
				$saldo2 = $cek_saldo2['saldo2'];

				$saldo = $saldo1 - $saldo2;





$total_keluar = $keluar + $mutasi_keluar;
$total_masuk = $masuk + $mutasi_masuk;

$saldo_akhir = $saldo + $total_masuk - $total_keluar;
$perubahan_saldo = $saldo + $saldo_akhir - $saldo - $saldo;

if($saldo_akhir == 0 OR $saldo_akhir == '')
{
	$perubahan_saldo = 0;
}

if($masuk == 0 OR $masuk == '')
{
	$masuk = 0;
}

if($keluar == 0 OR $keluar == '')
{
	$keluar = 0;
}

if($mutasi_keluar == 0 OR $mutasi_keluar == '')
{
	$mutasi_keluar = 0;
}

if($mutasi_masuk == 0 OR $mutasi_masuk == '')
{
	$mutasi_masuk = 0;
}


?>


<div class="container"><!--container table-->


<div class="table-responsive"><!--membuat agar ada garis pada tabel disetiap kolom-->
<h4><b>Kas Masuk <u>Rp. <?php echo rp($masuk) ?></u></b> </h4>
<table id="table_masuk" class="table table-hover table-sm">
		<thead>
			<th style="background-color: #4CAF50; color: white;"> Tanggal </th>
			<th style="background-color: #4CAF50; color: white;"> Dari Akun </th>
			<th style="background-color: #4CAF50; color: white;"> Ke Akun </th>
			<th style="background-color: #4CAF50; color: white;"> Total </th>
						
		</thead>
		<tbody class="tbody_masuk">
			 <?php

             //QUERY KAS MASUK
            $select_masuk = $db->query("SELECT js.no_faktur,sum(js.debit) as masuk,da.nama_daftar_akun AS nama_dari_akun,dk.kode_akun_jurnal AS dari_akun_jurnal ,js.jenis_transaksi,da.nama_daftar_akun,js.keterangan_jurnal FROM jurnal_trans js LEFT JOIN daftar_akun da ON js.kode_akun_jurnal = da.kode_daftar_akun LEFT JOIN jurnal_trans dk ON js.no_faktur = dk.no_faktur LEFT JOIN daftar_akun daf ON daf.kode_daftar_akun = dk.kode_akun_jurnal WHERE DATE(js.waktu_jurnal) = '$tanggal' AND js.kode_akun_jurnal = '$kas' AND dk.kode_akun_jurnal != js.kode_akun_jurnal AND js.debit != '0' AND dk.kredit != '0'  AND js.jenis_transaksi != 'Kas Mutasi' AND js.debit = dk.kredit GROUP BY dari_akun_jurnal");

            //menyimpan data sementara yang ada pada $perintah
            while ($out_masuk = mysqli_fetch_array($select_masuk))
            {
                //$select = $db->query("SELECT SUM(kredit) AS masuk FROM jurnal_trans WHERE DATE(waktu_jurnal) = '$tanggal' AND kode_akun_jurnal = '$out_masuk[dari_akun_jurnal]' AND kredit != 0 ");
                //$datadariakun = mysqli_fetch_array($select);

            echo "<tr>
                <td>". $tanggal ."</td>
                <td>". $out_masuk['jenis_transaksi'] ."</td>
                <td>". $out_masuk['nama_daftar_akun'] ."</td>
                <td>". $out_masuk['masuk'] ."</td>
            <tr>";

            }
			?>
		</tbody>

	</table>
</div> <!--/ responsive-->
<hr>

<!--Table Kas Keluar-->
<div class="table-responsive"><!--membuat agar ada garis pada tabel disetiap kolom-->
<h4><b>Kas Keluar <u>Rp. <?php echo rp($keluar) ?></u> </b></h4>
<table id="table_keluar" class="table table-hover table-sm">
		<thead>
			<th style="background-color: #4CAF50; color: white;"> Tanggal </th>
			<th style="background-color: #4CAF50; color: white;"> Dari Akun </th>
			<th style="background-color: #4CAF50; color: white;"> Ke Akun </th>
			<th style="background-color: #4CAF50; color: white;"> Total </th>
						
		</thead>
		<tbody class="tbody_keluar">
			 <?php
             //START QUERY KELUAR

            //$select_keluar = $db->query("SELECT daf.nama_daftar_akun AS kode_ke_akun ,dk.kode_akun_jurnal AS ke_akun,js.jenis_transaksi,da.nama_daftar_akun,js.keterangan_jurnal , js.kode_akun_jurnal FROM jurnal_trans js LEFT JOIN daftar_akun da ON js.kode_akun_jurnal = da.kode_daftar_akun LEFT JOIN jurnal_trans dk ON js.no_faktur = dk.no_faktur LEFT JOIN daftar_akun daf ON daf.kode_daftar_akun = dk.kode_akun_jurnal WHERE DATE(js.waktu_jurnal) = '$tanggal' AND js.kode_akun_jurnal = '$kas' AND dk.kode_akun_jurnal != '$kas' AND js.kredit != '0' AND dk.debit != '0' AND js.jenis_transaksi != 'Kas Mutasi' GROUP BY dk.kode_akun_jurnal");
             $select_keluar = $db->query("SELECT js.no_faktur,sum(js.kredit) as keluar,da.nama_daftar_akun AS nama_dari_akun,dk.kode_akun_jurnal AS dari_akun_jurnal ,js.jenis_transaksi,da.nama_daftar_akun,js.keterangan_jurnal FROM jurnal_trans js LEFT JOIN daftar_akun da ON js.kode_akun_jurnal = da.kode_daftar_akun LEFT JOIN jurnal_trans dk ON js.no_faktur = dk.no_faktur LEFT JOIN daftar_akun daf ON daf.kode_daftar_akun = dk.kode_akun_jurnal WHERE DATE(js.waktu_jurnal) = '$tanggal' AND js.kode_akun_jurnal = '$kas' AND dk.kode_akun_jurnal != js.kode_akun_jurnal AND js.kredit != '0' AND dk.debit != '0'  AND js.jenis_transaksi != 'Kas Mutasi' AND js.kredit = dk.debit GROUP BY dk.kode_akun_jurnal ");
            //menyimpan data sementara yang ada pada $perintah
            while ($out_keluar = mysqli_fetch_array($select_keluar))
            {

                //$select = $db->query("SELECT SUM(debit) AS keluar FROM jurnal_trans WHERE DATE(waktu_jurnal) = '$tanggal' AND kode_akun_jurnal = '$out_keluar[ke_akun]' AND debit != 0 ");
                //$datakeakun = mysqli_fetch_array($select);


            echo "<tr>
                <td>". $tanggal ."</td>
                <td>". $out_keluar['nama_daftar_akun'] ."</td>
                <td>". $out_keluar['jenis_transaksi'] ."</td>
                <td>". $out_keluar['keluar'] ."</td>
            <tr>";

            }
			?>

		</tbody>

	</table>
</div> <!--/ responsive-->
<hr>

<!--TABLE MUTASI MASUK-->
<div class="table-responsive"><!--membuat agar ada garis pada tabel disetiap kolom-->
<h4><b>Kas Mutasi (Masuk) <u>Rp. <?php echo rp($mutasi_masuk) ?></u> </b></h4>
<table id="table_mutasi_masuk" class="table table-hover table-sm">
    <thead>
      <th style="background-color: #4CAF50; color: white;"> Tanggal </th>
      <th style="background-color: #4CAF50; color: white;"> Dari Akun </th>
      <th style="background-color: #4CAF50; color: white;"> Ke Akun </th>
      <th style="background-color: #4CAF50; color: white;"> Total </th>
            
    </thead>
   <tbody class="tbody_mutasi_masuk">
		<?php
        //START QUERY MUTASI MASUK

            $select_mutasi_masuk = $db->query("SELECT daf.nama_daftar_akun AS nama_dari_akun ,dk.kode_akun_jurnal AS dari_akun_jurnal , js.jenis_transaksi,da.nama_daftar_akun,js.keterangan_jurnal FROM jurnal_trans js LEFT JOIN daftar_akun da ON js.kode_akun_jurnal = da.kode_daftar_akun LEFT JOIN jurnal_trans dk ON js.no_faktur = dk.no_faktur LEFT JOIN daftar_akun daf ON daf.kode_daftar_akun = dk.kode_akun_jurnal WHERE DATE(js.waktu_jurnal) = '$tanggal' AND js.kode_akun_jurnal = '$kas' AND dk.kode_akun_jurnal != js.kode_akun_jurnal AND js.debit != '0' AND js.jenis_transaksi = 'Kas Mutasi' GROUP BY dk.kode_akun_jurnal");

            //menyimpan data sementara yang ada pada $perintah
            while ($out_mutasi_masuk = mysqli_fetch_array($select_mutasi_masuk))
            {

            $select = $db->query("SELECT SUM(js.kredit) AS mutasi_masuk FROM jurnal_trans js LEFT JOIN jurnal_trans jkt ON js.no_faktur = jkt.no_faktur WHERE DATE(js.waktu_jurnal) = '$tanggal' AND js.kode_akun_jurnal = '$out_mutasi_masuk[dari_akun_jurnal]' AND js.kredit != 0 AND js.jenis_transaksi = 'Kas Mutasi' AND jkt.kode_akun_jurnal = '$kas' ");
            $datadariakun = mysqli_fetch_array($select);

            echo "<tr>
                <td>". $tanggal ."</td>
                <td>". $out_mutasi_masuk['nama_dari_akun'] ."</td>
                <td>". $out_mutasi_masuk['nama_daftar_akun'] ."</td>
                <td>". $datadariakun['mutasi_masuk'] ."</td>
            <tr>";

            }
		?>

    </tbody>

  </table>
</div> <!--/ responsive-->
<hr>


<div class="table-responsive"><!--membuat agar ada garis pada tabel disetiap kolom-->
<h4><b>Kas Mutasi (Keluar) <u>Rp. <?php echo rp($mutasi_keluar) ?></u> </b></h4>
<table id="table_mutasi" class="table table-hover table-sm">
		<thead>
			<th style="background-color: #4CAF50; color: white;"> Tanggal </th>
			<th style="background-color: #4CAF50; color: white;"> Dari Akun </th>
			<th style="background-color: #4CAF50; color: white;"> Ke Akun </th>
			<th style="background-color: #4CAF50; color: white;"> Total </th>
						
		</thead>
		<tbody class="tbody_mutasi">

			<?php
            //START QUERY MUTASI KELUAR
            $select_mutasi_keluar = $db->query("SELECT daf.nama_daftar_akun AS nama_dari_akun ,dk.kode_akun_jurnal AS dari_akun_jurnal , js.jenis_transaksi,da.nama_daftar_akun,js.keterangan_jurnal FROM jurnal_trans js LEFT JOIN daftar_akun da ON js.kode_akun_jurnal = da.kode_daftar_akun LEFT JOIN jurnal_trans dk ON js.no_faktur = dk.no_faktur LEFT JOIN daftar_akun daf ON daf.kode_daftar_akun = dk.kode_akun_jurnal WHERE DATE(js.waktu_jurnal) = '$tanggal' AND js.kode_akun_jurnal = '$kas' AND dk.kode_akun_jurnal != js.kode_akun_jurnal AND js.kredit != '0' AND js.jenis_transaksi = 'Kas Mutasi' GROUP BY dk.kode_akun_jurnal");

            //menyimpan data sementara yang ada pada $perintah
            while ($out_mutasi_keluar = mysqli_fetch_array($select_mutasi_keluar))
            {

                $select = $db->query("SELECT SUM(js.debit) AS mutasi_keluar FROM jurnal_trans js LEFT JOIN jurnal_trans jkt ON js.no_faktur = jkt.no_faktur WHERE DATE(js.waktu_jurnal) = '$tanggal' AND js.kode_akun_jurnal = '$out_mutasi_keluar[dari_akun_jurnal]' AND js.debit != 0 AND js.jenis_transaksi = 'Kas Mutasi' AND jkt.kode_akun_jurnal = '$kas' ");
                $datadariakun = mysqli_fetch_array($select);

            echo "<tr>
                <td>". $tanggal ."</td>
                <td>". $out_mutasi_keluar['nama_daftar_akun'] ."</td>
                <td>". $out_mutasi_keluar['nama_dari_akun'] ."</td>
                <td>". $datadariakun['mutasi_keluar'] ."</td>
            <tr>";

            }
            mysqli_close($db); 
		?>
		</tbody>
	</table>
</div> <!--/ responsive-->
<hr>

<!--FOTTER TOTAL DAN TTD-->
<div class="row">
<div class="col-sm-8">
		
<table style="font-size: 25">
<h3><b>Total Cashflow </b></h3>
<h3>
  <h4><tr> <td> Saldo Awal</td>   <td >:</td>  <td>Rp.</td> <td> <?php echo rp($saldo) ?></td> </tr>  </h4>
 <h4> <tr> <td> Perubahan Saldo</td><td>:</td> <td>Rp.</td> <td> <?php echo rp($perubahan_saldo) ?></td></tr></h4>
  <h4><tr> <td> Saldo Akhir</td>  <td>:</td>  <td>Rp.</td> <td> <?php echo rp($saldo_akhir) ?></td> </tr></h4>

</table>

</div><!--SM 8-->

<div class="col-sm-1">
</div>

<div class="col-sm-3">
</div>

</div>
<!--ENDING FOTTER TOTAL DAN TTD-->

	
</div><!--end container table-->