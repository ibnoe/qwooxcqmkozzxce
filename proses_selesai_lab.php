<?php 
include 'db.php';
include 'sanitasi.php';

$nama = stringdoang($_POST['nama']);
$no_rm = stringdoang($_POST['no_rm']);
echo $no_reg = stringdoang($_POST['no_reg']);
$jenis_penjualan = stringdoang($_POST['jenis_penjualan']);
$analis = stringdoang($_POST['analis']);
$dokter = stringdoang($_POST['dokter']);

$tanggal = date('Y-m-d');
$jam = date('H:m:s');

$perintah3 = $db->query("SELECT * FROM hasil_lab WHERE no_reg = '$no_reg' ");
$data1 = mysqli_num_rows($perintah3);

if ($data1 > 0)
{
      $perintah2 = $db->query("DELETE FROM hasil_lab WHERE no_reg = '$no_reg' AND no_faktur IS NULL");
}


$select = $db->query("SELECT * FROM tbs_hasil_lab WHERE no_reg = '$no_reg' AND no_rm = '$no_rm'");
while($out = mysqli_fetch_array($select))
{

	$input = "INSERT INTO hasil_lab (id_pemeriksaan, nama_pemeriksaan, hasil_pemeriksaan, nilai_normal_lk, nilai_normal_pr, status_abnormal, status_pasien, status, no_rm, no_reg,nama_pasien,tanggal,
		jam,model_hitung,satuan_nilai_normal,dokter,petugas_analis,
		id_sub_header,nilai_normal_lk2,nilai_normal_pr2,kode_barang) VALUES 
		('$out[id_pemeriksaan]','$out[nama_pemeriksaan]',
		'$out[hasil_pemeriksaan]','$out[nilai_normal_lk]',
		'$out[nilai_normal_pr]','$out[status_abnormal]',
		'$jenis_penjualan','Selesai','$no_rm','$no_reg','$nama','$tanggal',
		'$jam','$out[model_hitung]','$out[satuan_nilai_normal]',
		'$dokter','$analis','$out[id_sub_header]','$out[normal_lk2]',
		'$out[normal_pr2]','$out[kode_barang]')";

	  if ($db->query($input) === TRUE)
      {

		
      } 
      else 
      { 
     
      }

}

$delete = $db->query("DELETE FROM tbs_hasil_lab WHERE no_reg = '$no_reg'");


?>