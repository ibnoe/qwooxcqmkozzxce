<?php
// memasukan file db.php
include 'db.php';
include 'sanitasi.php';

// mengirim data no faktur menggunakan metode POST

 $no_faktur = stringdoang($_POST['no_faktur']);
 $total_akhir = angkadoang($_POST['total']); 
 $diskon = angkadoang($_POST['potongan']);
 
 /*$pajak = angkadoang($_POST['tax']);*/

 $biaya_admin = angkadoang($_POST['biaya_adm']);



// menampilakn hasil penjumlah subtotal ALIAS total penjualan dari tabel tbs_penjualan berdasarkan data no faktur
 $query = $db->query("SELECT SUM(subtotal) AS total_penjualan FROM tbs_penjualan WHERE (no_reg IS NULL OR no_reg = '') AND no_faktur = '$no_faktur' AND lab IS NULL");
 $data = mysqli_fetch_array($query);
 $total_ss = $data['total_penjualan'];


 $total_tbs = ($total_ss - $diskon) + $biaya_admin;


if ($total_akhir == round($total_tbs)) {
		echo 1;
	}
	else{
		echo 0;
	}

//Untuk Memutuskan Koneksi Ke Database
mysqli_close($db); 

?>
