<?php 
include 'db.php';

$query = $db->query("SELECT COUNT(*) as jumlah FROM barang ");
$data = $query->fetch_array();

echo $jumlah_db = $data['jumlah'];


 ?>

<!DOCTYPE html>
<html>
<head>
	<title></title>

	   <script src="jquery/jquery.js"></script>
	   <link rel="stylesheet" href="chosen/chosen.css">
<script src="chosen/chosen.jquery.js"></script>


<script src="http://cdn.jsdelivr.net/alasql/0.3/alasql.min.js"></script>


</head>
<body>
<button id="btnRefresh">Refresh Cache</button>

<button id="btnChosen">Refresh Chosen</button>
<table border="1">
<thead><th>Kode Barang</th><th>Nama Barang</th></thead>
	<tbody id="">
		


	</tbody>

</table>

<select id="badan" class="chosen">
	

</select>



<p id="text"></p>


<script type="text/javascript">
	
   $(document).ready(function(){

	// cek index db apakah sudah ada data nya 
      var jumlah_data = "";

	    alasql('CREATE INDEXEDDB DATABASE IF NOT EXISTS geo;\
        ATTACH INDEXEDDB DATABASE geo; \
        USE geo;  CREATE TABLE IF NOT EXISTS  barang; '
        ,function(){

        // Select data from IndexedDB
        alasql.promise('SELECT COUNT(*) AS jumlah FROM barang ')
              .then(function(res){

              	var i = 0;
              	var text = "";
              	jumlah_data = res[i]['jumlah'];
              	console.log(jumlah_data);

			      if (jumlah_data != <?php echo $jumlah_db ?> ) {
									 
				  		//ambil data dari server dan masukan ke index db
						$.getJSON("proses_contoh_json.php",function(data){						   

							hapus_semua();

							for (k in data.barang){

								tambah_data(data.barang[k].kode_barang,data.barang[k].nama_barang);

								$("#badan").prepend("<option>"+data.barang[k].kode_barang+" "+data.barang[k].nama_barang+"</option>");

							}
						}); 


			      }
			      else { 		 
						tampil_data();
			      }           


        }); // END Select data from IndexedDB


    }); // END alasql('CREATE INDEXEDDB DATABASE IF NOT EXISTS geo;\ ATTACH INDEXEDDB DATABASE geo; \ USE geo;  CREATE TABLE IF NOT EXISTS  barang; ' ,function(){


	$("#badan").focus(function(){

	      $(".chosen").chosen({no_results_text: "Maaf, Data Tidak Ada!",search_contains:true}); 

	});


	$("#btnChosen").click(function(){

	         $("#badan").trigger("chosen:updated");
	});

 

	$("#btnRefresh").click(function(){
	    	hapus_semua();
	    	var jumlah_data = "";

	    alasql('CREATE INDEXEDDB DATABASE IF NOT EXISTS geo;\
        ATTACH INDEXEDDB DATABASE geo; \
        USE geo;  CREATE TABLE IF NOT EXISTS  barang; '
        ,function(){

        // Select data from IndexedDB
        alasql.promise('SELECT COUNT(*) AS jumlah FROM barang ').then(function(res){
        	console.log(res);

                var i = 0;
                var text = "";
                jumlah_data = res[i]['jumlah'];
                console.log(jumlah_data);

                	if (jumlah_data != <?php echo $jumlah_db ?> ) {
                		//ambil data dari server dan masukan ke index db
						$.getJSON("proses_contoh_json.php",function(data){

							for (k in data.barang){

								tambah_data(data.barang[k].kode_barang,data.barang[k].nama_barang);

								$("#badan").prepend("<option value='"+ data.barang[k].kode_barang+"'>"+data.barang[k].kode_barang+" "+data.barang[k].nama_barang+"</option>");

						 	} // for (k in data.barang){


						}); // $.getJSON("proses_contoh_json.php",function(data){


				     } // if (jumlah_data != <?php #echo $jumlah_db ?> ) {
				     else {

						tampil_data();

				     }

           


	        }); // alasql.promise('SELECT COUNT(*) AS jumlah FROM barang ').then(function(res){console.log(res);

	    }); // alasql('CREATE INDEXEDDB DATABASE IF NOT EXISTS geo;\ ATTACH INDEXEDDB DATABASE geo; \ USE geo;  CREATE TABLE IF NOT EXISTS  barang; ' ,function(){

	}); // $("#btnRefresh").click(function(){
  
	    function tambah_data(city,population) {
	    	// body...

	    	  alasql('ATTACH INDEXEDDB DATABASE geo;\
            USE geo;\
            INSERT INTO barang(kode_barang,nama_barang,hapus) VALUES("'+city+'","'+population+'",1);\
            ',function(){});
	    } // function tambah_data(city,population) {


  function hapus_semua() {
	    	// body...

	  alasql('ATTACH INDEXEDDB DATABASE geo; USE geo; DELETE FROM barang WHERE hapus = 1;',
        function(){});

	    } // function hapus_semua() {
	function tampil_data() {
		// body...

		      alasql('CREATE INDEXEDDB DATABASE IF NOT EXISTS geo;\
	        ATTACH INDEXEDDB DATABASE geo; \
	        USE geo;  CREATE TABLE IF NOT EXISTS  barang; '
	        ,function(){

	        // Select data from IndexedDB
	        alasql.promise('SELECT * FROM barang ORDER BY kode_barang DESC')
	              .then(function(res){
	                 console.log(res);

	                 var i = 0;
	                 var text = "";

	                  for (;res[i];) {
	             

	                     text += "<option value='"+res[i]['kode_barang'] +"'>"+res[i]['kode_barang'] +" "+res[i]['nama_barang'] +"</option>";

	                      i++;


	                    } //end for

	                     $('#badan').append(text);

	 


	        });
	    });

	} // function tampil_data() {

	  
});// end document ready
</script>



      <script type="text/javascript">
      
  
      
      </script>

</body>
</html>