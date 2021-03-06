	<?php include 'session_login.php';
/* Database connection start */
include 'db.php';
/* Database connection end */
include 'sanitasi.php';

$pilih_akses_item_keluar = $db->query("SELECT item_keluar_edit,
item_keluar_hapus FROM otoritas_item_keluar WHERE id_otoritas = '$_SESSION[otoritas_id]'");
$otoritas_item_keluar = mysqli_fetch_array($pilih_akses_item_keluar);

// storing  request (ie, get/post) global array to a variable  
$requestData= $_REQUEST;

$columns = array( 
// datatable column index  => database column name
	0 =>'no_faktur', 
	1 => 'tanggal',
	2 => 'jam',
	3 => 'user',
	4 => 'user_edit',
	5 => 'tanggal_edit',
  6 => 'keterangan',
  7 => 'total',
  8 => 'id'
);

// getting total number records without any search
$sql = "SELECT COUNT(*) AS jumlah_data ";
$sql.=" FROM item_keluar ik LEFT JOIN user u ON ik.user = u.username LEFT JOIN  user uu ON ik.user_edit = uu.username";
$query=mysqli_query($conn, $sql) or die("datatable_item_keluar.php: get employees");
$query_data = mysqli_fetch_array($query);
$totalData = $query_data['jumlah_data'];
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.



$sql = "SELECT ik.no_faktur, ik.tanggal, ik.jam, ik.user, ik.user_edit, ik.tanggal_edit, ik.keterangan, ik.total, ik.id, u.nama, uu.nama AS nama_edit ";
$sql.=" FROM item_keluar ik LEFT JOIN user u ON ik.user = u.username LEFT JOIN  user uu ON ik.user_edit = uu.username  WHERE 1=1";
if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter


	$sql.=" AND ( no_faktur LIKE '".$requestData['search']['value']."%' "; 
	$sql.=" OR tanggal LIKE '".$requestData['search']['value']."%' ";    
	$sql.=" OR keterangan LIKE '".$requestData['search']['value']."%' )";

}
$query=mysqli_query($conn, $sql) or die("datatable_item_keluar.phpppp: get employees");
$totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result. 


$sql.= " ORDER BY tanggal,jam DESC LIMIT ".$requestData['start']." ,".$requestData['length']."   ";

/* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */	
$query=mysqli_query($conn, $sql) or die("employee-grid-data.php: get employees");

$data = array();
while( $row=mysqli_fetch_array($query) ) {  // preparing an array
	$nestedData=array(); 

    	$nestedData[] = $row['no_faktur'];
      $nestedData[] = $row['tanggal'];
      $nestedData[] = $row['jam'];
      $nestedData[] = $row['nama'];
      $nestedData[] = $row['nama_edit'];
      $nestedData[] = $row['tanggal_edit'];
      $nestedData[] = $row['keterangan'];
      $nestedData[] = rp($row['total']);



      $nestedData[] = "<button class='btn detail btn-info' no_faktur='". $row['no_faktur'] ."'> <i class='fa fa-th-list'></i> Detail </button>";

if ($otoritas_item_keluar['item_keluar_edit'] > 0) {
    $nestedData[] = "<a href='proses_edit_item_keluar.php?no_faktur=". $row['no_faktur']."' class='btn btn-success'> <i class='fa fa-edit'></i> Edit  </a>";
       }

if ($otoritas_item_keluar['item_keluar_hapus'] > 0) {

   $nestedData[] = "<button class='btn btn-hapus btn-danger' data-item='". $row['no_faktur'] ."' data-id='". $row['id'] ."'> <i class='fa fa-trash'> </i> Hapus </button>"; 
       } 


	  $nestedData[] = "<a href='cetak_item_keluar.php?no_faktur=". $row['no_faktur']."&keterangan=".$row["keterangan"]."' class='btn btn-warning' target='blank'> <i class='fa fa-print'></i> Cetak  </a>";

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

