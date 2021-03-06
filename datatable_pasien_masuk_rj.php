<?php include 'session_login.php';
/* Database connection start */
include 'db.php';
include 'sanitasi.php';



$otoritas_laboratorium = $db->query("SELECT input_jasa_lab, input_hasil_lab FROM otoritas_laboratorium WHERE id_otoritas = '$_SESSION[otoritas_id]'");
$take_lab = mysqli_fetch_array($otoritas_laboratorium);
$input_jasa_lab = $take_lab['input_jasa_lab'];
$input_hasil_lab = $take_lab['input_hasil_lab'];

$pilih_akses_penjualan = $db->query("SELECT penjualan_tambah FROM otoritas_penjualan WHERE id_otoritas = '$_SESSION[otoritas_id]'");
$penjualan = mysqli_fetch_array($pilih_akses_penjualan);


$pilih_akses_rekam_medik = $db->query("SELECT rekam_medik_rj_lihat FROM otoritas_rekam_medik WHERE id_otoritas = '$_SESSION[otoritas_id]'");
$rekam_medik = mysqli_fetch_array($pilih_akses_rekam_medik);

$pilih_akses_registrasi_rj = $db->query("SELECT registrasi_rj_lihat, registrasi_rj_edit, registrasi_rj_hapus FROM otoritas_registrasi WHERE id_otoritas = '$_SESSION[otoritas_id]'");
$registrasi_rj = mysqli_fetch_array($pilih_akses_registrasi_rj);

// storing  request (ie, get/post) global array to a variable  
$requestData= $_REQUEST;


$columns = array( 
// datatable column index  => database column name
	0 =>'no_urut', 
	1 => 'poli',
	2 => 'dokter',
	3 => 'no_reg',
	4 => 'no_rm',
	5 => 'tanggal',
	6 => 'nama_pasien',
	7 => 'penjamin',
	8 => 'umur_pasien',
	9 => 'jenis_kelamin',
	10 => 'keterangan',
	20 => 'id'




);

// getting total number records without any search


$sql = "SELECT reg.no_urut, reg.poli, reg.dokter, reg.no_reg, reg.no_rm, reg.tanggal, reg.nama_pasien, reg.penjamin, reg.umur_pasien, reg.jenis_kelamin, reg.keterangan, reg.id, reg.status, reg.jam, rek.tanggal_periksa ";
$sql.=" FROM registrasi reg LEFT JOIN rekam_medik rek ON reg.no_reg = rek.no_reg WHERE reg.jenis_pasien = 'Rawat Jalan' AND  (reg.status = 'Proses' OR reg.status = 'Rujuk Keluar Ditangani')";
$query=mysqli_query($conn, $sql) or die("datatable_pasien_masuk_rj_1.php: get employees");
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.


$sql = "SELECT reg.no_urut, reg.poli, reg.dokter, reg.no_reg, reg.no_rm, reg.tanggal, reg.nama_pasien, reg.penjamin, reg.umur_pasien, reg.jenis_kelamin, reg.keterangan, reg.id, reg.status, reg.jam, rek.tanggal_periksa ";
$sql.=" FROM registrasi reg LEFT JOIN rekam_medik rek ON reg.no_reg = rek.no_reg WHERE reg.jenis_pasien = 'Rawat Jalan' AND  (reg.status = 'Proses' OR reg.status = 'Rujuk Keluar Ditangani')";
if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
	$sql.=" AND ( reg.no_urut LIKE '".$requestData['search']['value']."%' ";    
	$sql.=" OR reg.no_rm LIKE '".$requestData['search']['value']."%' ";
  $sql.=" OR reg.no_reg LIKE '".$requestData['search']['value']."%' ";
	$sql.=" OR reg.nama_pasien LIKE '".$requestData['search']['value']."%' ";
	$sql.=" OR reg.penjamin LIKE '".$requestData['search']['value']."%' )";

}
$query=mysqli_query($conn, $sql) or die("datatable_pasien_masuk_rj_2.php: get employees");
$totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result. 

$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
/* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */

$query=mysqli_query($conn, $sql) or die("datatable_pasien_masuk_rj_3.php: get employees");
$data = array();

while( $row=mysqli_fetch_array($query) ) {  // preparing an array
	$nestedData=array();

$penjual = $db->query("SELECT status FROM penjualan WHERE no_reg = '$row[no_reg]' ");
$sttus = mysqli_num_rows($penjual);

$query_id_user = $db->query("SELECT id AS id_dokter FROM user WHERE nama = '$row[dokter]' ");
$data_id_user = mysqli_fetch_array($query_id_user);



/*
$query_z = $db->query("SELECT p.status,p.no_faktur,p.nama,p.kode_gudang,g.nama_gudang FROM penjualan p INNER JOIN gudang g ON p.kode_gudang = g.kode_gudang WHERE p.no_reg = '$row[no_reg]' ");
$data_z = mysqli_fetch_array($query_z);



if ($penjualan['penjualan_tambah'] > 0) {


  /*if ($data_z['status'] == 'Simpan Sementara') {

       $nestedData[] = "
       			<a href='proses_pesanan_barang_raja.php?no_faktur=".$data_z['no_faktur']."&no_reg=".$row['no_reg']."&no_rm=".$row['no_rm']."&nama_pasien=".$data_z['nama']."&kode_gudang=".$data_z['kode_gudang']."&nama_gudang=".$data_z['nama_gudang']."'class='btn btn-floating btn-small btn btn-info'><i class='fa fa-credit-card'></i></a>
       			"; 
      }
      else
      {
      $nestedData[] = "";
      }*/
/*}
else{

       $nestedData[] = "";

}*/
      
 
if ($registrasi_rj['registrasi_rj_lihat'] > 0) {
  if ($row['status'] == 'Rujuk Keluar Ditangani')
  {
    $nestedData[] = "<p style='color:red'>Silakan Transaksi Penjualan</p>";
  } 
  else
  {
    $nestedData[] = "
    			<button class='btn btn-floating btn-small btn-info pilih1' data-reg='". $row['no_reg']."' data-id='". $row['id']."' ><i class='fa fa-bus '></i></button>
    			";
  }

if ($sttus > 0 )
{
  $nestedData[] = "";
}
else
{
   $nestedData[] = "
 				<button class='btn btn-floating btn-small btn-info pilih12' data-reg='". $row['no_reg']."' data-id='". $row['id']."'><i class='fa fa-cab'></i></button>
 				";
}
 
  $nestedData[] = "
		   <button class='btn btn-floating btn-small btn-info rujuk_ri' data-reg='".$row['no_reg']."'><i class='fa fa-hotel'></i></button>
		   ";


/* if ($data_z['status'] == 'Simpan Sementara') {

    $nestedData[] = "<a href='form_simpan_rj_penjualan_lab.php?no_rm=".$row['no_rm']."&nama=".$row['nama_pasien']."&no_reg=".$row['no_reg']."&dokter=".$row['dokter']."&jenis_penjualan=Simpan Rawat Jalan' class='btn btn-floating btn-small btn-info'><i class='fa fa-stethoscope'></i></a>
       ";
}
else
{
    $nestedData[] = "<a href='form_penjualan_lab.php?no_rm=".$row['no_rm']."&nama=".$row['nama_pasien']."&no_reg=".$row['no_reg']."&dokter=".$row['dokter']."&jenis_penjualan=Rawat Jalan&rujukan=Rujuk Rawat Jalan' class='btn btn-floating btn-small btn-info'><i class='fa fa-stethoscope'></i></a>
       ";

    

}*/


}

$nestedData[] = "<a href='form_pemeriksaan_radiologi.php?no_rm=".$row['no_rm']."&nama=".$row['nama_pasien']."&no_reg=".$row['no_reg']."&dokter=".$data_id_user['id_dokter']."&jenis_penjualan=Rawat Jalan&rujukan=Rujuk Rawat Jalan&penjamin=".$row['penjamin']."' class='btn btn-floating btn-small btn-info'><i class='fa fa-universal-access'></i></a>";

// untuk input jasa lab
if ($input_jasa_lab > 0) {

    $nestedData[] = "<a href='form_penjualan_lab.php?no_rm=".$row['no_rm']."&nama=".$row['nama_pasien']."&no_reg=".$row['no_reg']."&dokter=".$data_id_user['id_dokter']."&jenis_kelamin=".$row['jenis_kelamin']."&jenis_penjualan=Rawat Jalan&rujukan=Rujuk Rawat Jalan' class='btn btn-floating btn-small btn-info'>
    	<i class='fa fa-stethoscope'></i></a>
       ";
    }

// untuk input hasil lab
if ($input_hasil_lab > 0) {
	$show = $db->query("SELECT COUNT(*) AS jumlah FROM tbs_penjualan WHERE no_reg = '$row[no_reg]' AND lab = 'Laboratorium' ");
	$take = mysqli_fetch_array($show);
		if ($take['jumlah'] > 0)
		{
			$query_cek_setting = $db->query("SELECT nama FROM setting_laboratorium");
			$data_cek_setting = mysqli_fetch_array($query_cek_setting);
			$angka_setting_lab = $data_cek_setting['nama'];
			if($angka_setting_lab == 0){
			$nestedData[] = "<p style='color:red'>Cek Setting Laboratorium</p>";
			}
			else{
			$nestedData[] = "<a id='input_hasil' href='cek_input_hasil_lab.php?no_rm=".$row['no_rm']."&nama=".$row['nama_pasien']."&no_reg=".$row['no_reg']."&jenis_penjualan=Rawat Jalan' class='btn btn-floating btn-small btn-info'><i class='fa fa-pencil'></i></a>";
			}
		}
		else
		{
		  $nestedData[] = "<p style='color:red'>Input Laboratorium</p>";
		}
}
// end untuk input hasil lab

if ($rekam_medik['rekam_medik_rj_lihat'] > 0) {
  $nestedData[] = "<a href='input_rekammedik_raja.php?no_reg=".$row['no_reg']."&tgl=".$row['tanggal_periksa']."&jam=".$row['jam']."' class='btn-floating btn-info btn-small'><i class='fa fa-medkit '></i></a>";
}

if ($registrasi_rj['registrasi_rj_edit'] > 0) {  
	$nestedData[] = "<a href='edit_registrasi_rawat_jalan.php?no_reg=". $row['no_reg']."&status_registrasi=pasien_masuk' class='btn btn-floating btn-small btn-success'><i class='fa fa-edit'> </i></a>";
}	


if ($registrasi_rj['registrasi_rj_hapus'] > 0) {

  if ($sttus > 0 )
{
  $nestedData[] = "";
}
else
{
  $nestedData[] = "<button class='btn btn-floating btn-small btn-info pilih2' data-id='". $row['id']."' data-reg='".$row['no_reg']."'><b> X </b></button>
  ";
}

}


	$nestedData[] = $row["no_urut"];
	$nestedData[] = $row["poli"];
	$nestedData[] = $row["dokter"];	
	$nestedData[] = $row["no_reg"];
	$nestedData[] = $row["no_rm"];
	$nestedData[] = tanggal_terbalik($row["tanggal"]);	
	$nestedData[] = $row["nama_pasien"];
	$nestedData[] = $row["penjamin"];
	$nestedData[] = $row["umur_pasien"];	
	$nestedData[] = $row["jenis_kelamin"];
	$nestedData[] = $row["keterangan"];
	$nestedData[] = $row["id"];
	
	$data[] = $nestedData;
}



$json_data = array(
			"draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
			"recordsTotal"    => intval( $totalData ),  // total number of records
			"recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
			"data"            => $data   // total data array
			);

echo json_encode($json_data);  // send data as json format

?>
