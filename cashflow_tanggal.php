<?php include_once 'session_login.php';
// memasukan file session login,  header, navbar, db.php,
include 'header.php';
include 'navbar.php';
include 'db.php';
include 'sanitasi.php';
 ?>

<div class="container">
<h3><b>Cashflow Per Tanggal</b></h3>

<div class="dropdown">
    <!--Trigger-->
    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenu4" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Jenis Cashflow</button>

    <!--Menu-->
    <div class="dropdown-menu dropdown-secondary" aria-labelledby="dropdownMenu4" data-dropdown-in="fadeIn" data-dropdown-out="fadeOut">
        <a class="dropdown-item" id="nav_detail" href="#">Cashflow Detail</a>
        <a class="dropdown-item" id="nav_rekap" href="#">Cashflow Rekap</a>
    </div>
</div>
<br>


<div class="row">
<span id="show_detail" style="display: none">
	<div class="col-sm-6" >
	<div class="card card-block">
	<center><h4><b>DETAIL</b></h4></center>
<form role="form" id="form_detail" >
        <div class="form-group"> 
 		<label style="font-size:15px"> Kas </label><br>
            <select type="text" name="kas_detail" id="kas_detail" class="form-control"  style="font-size: 15px" >
                <option value=""> Silahkan Pilih </option>
                    <?php 
                    $sett_akun = $db->query("SELECT sa.kas, da.nama_daftar_akun FROM setting_akun sa INNER JOIN daftar_akun da ON sa.kas = da.kode_daftar_akun");
                    $data_sett = mysqli_fetch_array($sett_akun);

                    echo "<option selected value='".$data_sett['kas']."'>".$data_sett['nama_daftar_akun'] ."</option>";
                         
                    $query = $db->query("SELECT nama_daftar_akun, kode_daftar_akun FROM daftar_akun WHERE tipe_akun = 'Kas & Bank'");
                    while($data = mysqli_fetch_array($query))
                        {
                         
                        echo "<option value='".$data['kode_daftar_akun']."'>".$data['nama_daftar_akun'] ."</option>";
                                                  
                        }
                    ?>
                      
            </select>
		</div>


        <div class="form-group"> 
  			<input type="text" name="tanggal_detail" id="tanggal_detail" autocomplete="off" class="form-control tanggal_cari" placeholder="Tanggal">
		</div>

		<center><button type="submit" name="submit" id="submit_detail" class="btn btn-primary" > <i class="fa fa-send"></i> Show </button></center>
</form>
	</div>
	</div>
</span>


<span id="show_rekap" style="display: none">
	<div class="col-sm-6">
	<div class="card card-block">

	<center><h4><b>REKAP</b></h4></center>
	<form role="form" id="form_rekap" >

        <div class="form-group"> 
 		<label style="font-size:15px"> Kas </label><br>
            <select type="text" name="kas_rekap" id="kas_rekap" class="form-control"  style="font-size: 15px" >
                <option value=""> Silahkan Pilih </option>
                    <?php 
                    $sett_akun = $db->query("SELECT sa.kas, da.nama_daftar_akun FROM setting_akun sa INNER JOIN daftar_akun da ON sa.kas = da.kode_daftar_akun");
                    $data_sett = mysqli_fetch_array($sett_akun);

                    echo "<option selected value='".$data_sett['kas']."'>".$data_sett['nama_daftar_akun'] ."</option>";
                         
                    $query = $db->query("SELECT nama_daftar_akun, kode_daftar_akun FROM daftar_akun WHERE tipe_akun = 'Kas & Bank'");
                    while($data = mysqli_fetch_array($query))
                        {
                         
                        echo "<option value='".$data['kode_daftar_akun']."'>".$data['nama_daftar_akun'] ."</option>";
                                                  
                        }
                    ?>
                      
            </select>
		</div>


        <div class="form-group"> 
  			<input type="text" name="tanggal_rekap" id="tanggal_rekap" autocomplete="off" class="form-control tanggal_cari" placeholder="Tanggal">
		</div>

		<center><button type="submit" name="submit" id="submit_rekap" class="btn btn-primary" > <i class="fa fa-send"></i> Show </button></center>
	</form>
	</div>
	</div>
</span>


</div><!--closed row-->
<hr>
<span id="show_total" style="display: none">

<!--START DETAIL SHOW TABLE-->
<span id="show_table_detail">
<!--TABLE KAS MASUK-->
<span id="result_detail_masuk">
<div class="table-responsive"><!--membuat agar ada garis pada tabel disetiap kolom-->
<h4><b>Kas Masuk</b></h4>
<table id="detail_masuk" class="table table-hover table-sm">
    <thead>
      <th style="background-color: #4CAF50; color: white;"> No Faktur </th>
      <th style="background-color: #4CAF50; color: white;"> Keterangan </th>
      <th style="background-color: #4CAF50; color: white;"> Dari Akun </th>
      <th style="background-color: #4CAF50; color: white;"> Ke Akun </th>
      <th style="background-color: #4CAF50; color: white;"> Total </th>
      <th style="background-color: #4CAF50; color: white;"> Petugas </th>
      <th style="background-color: #4CAF50; color: white;"> Petugas Edit </th>
      <th style="background-color: #4CAF50; color: white;"> Waktu</th>
            
    </thead>
    <tbody class="tbody_masuk">
      

    </tbody>

  </table>
</div> <!--/ responsive-->
<hr>
</span>

<!--TABLE KAS KELUAR-->
<span id="result_detail_keluar">
<div class="table-responsive"><!--membuat agar ada garis pada tabel disetiap kolom-->
<h4><b>Kas Keluar</b></h4>
<table id="detail_keluar" class="table table-hover table-sm">
    <thead>
      
      <th style="background-color: #4CAF50; color: white;"> No Faktur </th>
      <th style="background-color: #4CAF50; color: white;"> Keterangan </th>
      <th style="background-color: #4CAF50; color: white;"> Dari Akun </th>
      <th style="background-color: #4CAF50; color: white;"> Ke Akun </th>
      <th style="background-color: #4CAF50; color: white;"> Total </th>
      <th style="background-color: #4CAF50; color: white;"> Petugas </th>
      <th style="background-color: #4CAF50; color: white;"> Petugas Edit </th>
      <th style="background-color: #4CAF50; color: white;"> Waktu</th>
            
    </thead>
    <tbody class="tbody_keluar">
      

    </tbody>

  </table>
</div> <!--/ responsive-->
<hr>
</span>
<!--TABLE KAS MUTASI-->
<span id="result_detail_mutasi">
<div class="table-responsive"><!--membuat agar ada garis pada tabel disetiap kolom-->
<h4><b>Kas Mutasi</b></h4>
<table id="detail_mutasi" class="table table-hover table-sm">
    <thead>
      <th style="background-color: #4CAF50; color: white;"> No Faktur </th>
      <th style="background-color: #4CAF50; color: white;"> Keterangan </th>
      <th style="background-color: #4CAF50; color: white;"> Dari Akun </th>
      <th style="background-color: #4CAF50; color: white;"> Ke Akun </th>
      <th style="background-color: #4CAF50; color: white;"> Total </th>
      <th style="background-color: #4CAF50; color: white;"> Petugas </th>
      <th style="background-color: #4CAF50; color: white;"> Petugas Edit </th>
      <th style="background-color: #4CAF50; color: white;"> Waktu</th>
            
    </thead>
    <tbody class="tbody_mutasi">

      

    </tbody>

  </table>
</div> <!--/ responsive-->
<hr>
</span>
</span>
<!--ENDING DETAIL SHO TABLE-->



<!--START REKAP SHOW TABLE-->
<span id="show_table_rekap">
<!--TABLE KAS MASUK-->
<span id="result_masuk">
<div class="table-responsive"><!--membuat agar ada garis pada tabel disetiap kolom-->
<h4><b>Kas Masuk</b></h4>
<table id="table_masuk" class="table table-hover table-sm">
		<thead>
			<th style="background-color: #4CAF50; color: white;"> Tanggal </th>
			<th style="background-color: #4CAF50; color: white;"> Dari Akun </th>
			<th style="background-color: #4CAF50; color: white;"> Ke Akun </th>
			<th style="background-color: #4CAF50; color: white;"> Total </th>
						
		</thead>
		<tbody class="tbody_masuk">
			

		</tbody>

	</table>
</div> <!--/ responsive-->
<hr>
</span>

<!--TABLE KAS KELUAR-->
<span id="result_keluar">
<div class="table-responsive"><!--membuat agar ada garis pada tabel disetiap kolom-->
<h4><b>Kas Keluar</b></h4>
<table id="table_keluar" class="table table-hover table-sm">
		<thead>
			<th style="background-color: #4CAF50; color: white;"> Tanggal </th>
			<th style="background-color: #4CAF50; color: white;"> Dari Akun </th>
			<th style="background-color: #4CAF50; color: white;"> Ke Akun </th>
			<th style="background-color: #4CAF50; color: white;"> Total </th>
						
		</thead>
		<tbody class="tbody_keluar">
			

		</tbody>

	</table>
</div> <!--/ responsive-->
<hr>
</span>
<!--TABLE KAS MUTASI-->
<span id="result_mutasi">
<div class="table-responsive"><!--membuat agar ada garis pada tabel disetiap kolom-->
<h4><b>Kas Mutasi</b></h4>
<table id="table_mutasi" class="table table-hover table-sm">
		<thead>
			<th style="background-color: #4CAF50; color: white;"> Tanggal </th>
			<th style="background-color: #4CAF50; color: white;"> Dari Akun </th>
			<th style="background-color: #4CAF50; color: white;"> Ke Akun </th>
			<th style="background-color: #4CAF50; color: white;"> Total </th>
						
		</thead>
		<tbody class="tbody_mutasi">

			

		</tbody>

	</table>
</div> <!--/ responsive-->
<hr>
</span>
</span>
<!--END TABLE REKAP-->

<table style="font-size: 25">
<h3><b>Total Cashflow </b></h3>
<h3>
  <h4><tr> <td> Saldo Awal</td>   <td >:</td>  <td>Rp.</td> <td id="saldo_awal" > </td> </tr>  </h4>
 <h4> <tr> <td> Perubahan Saldo</td>   <td>:</td> <td>Rp.</td> <td id="perubahan_saldo" > </td> </tr></h4>
  <h4><tr> <td> Saldo Akhir</td>  <td>:</td>  <td>Rp.</td> <td id="saldo_akhir" > </td> </tr></h4>

</table>

</span>





</div><!--close container-->


<!--SCRIPT PROSES DETAIL-->
<script type="text/javascript" language="javascript" >
   $(document).ready(function() {
    $(document).on('click','#submit_detail',function(e) {

//proses for hasil
var kas_detail = $("#kas_detail").val();
var tanggal_detail = $("#tanggal_detail").val();



$.getJSON('proses_cashflow_tanggal_detail.php',{kas_detail:kas_detail,tanggal_detail:tanggal_detail},function(json){

    $("#saldo_awal").html(tandaPemisahTitik(json.keterangan));
      $("#perubahan_saldo").html(tandaPemisahTitik(json.provinsi));
      $("#saldo_akhir").html(tandaPemisahTitik(json.petugas));
                $("#show_total").show();

  });

// end proses for hasil




//untuk tampilkan table kas masuk
     $('#detail_masuk').DataTable().destroy();
          var dataTable = $('#detail_masuk').DataTable( {
          "processing": true,
          "serverSide": true,
          "info":     true,
          "language": {
        "emptyTable":     "My Custom Message On Empty Table"
    },
          "ajax":{
            url :"proses_cashflow_detail_masuk.php", // json datasource
             "data": function ( d ) {
                d.kas_detail = $("#kas_detail").val();
                d.tanggal_detail = $("#tanggal_detail").val();
                // d.custom = $('#myInput').val();
                // etc
            },
                type: "post",  // method  , by default get
            error: function(){  // error handling
              $(".tbody_masuk").html("");
              $("#detail_masuk").append('<tbody class="tbody_masuk"><tr><th colspan="3"></th></tr></tbody>');
              $("#detail_masuk_processing").css("display","none");
                       
            }
          }
    
        });
//Ending untuk tampilkan table kas masuk


//untuk tampilkan table kas KELUAR
     $('#detail_keluar').DataTable().destroy();
          var dataTable = $('#detail_keluar').DataTable( {
          "processing": true,
          "serverSide": true,
          "info":     true,
          "language": {
        "emptyTable":     "My Custom Message On Empty Table"
    },
          "ajax":{
            url :"proses_cashflow_detail_keluar.php", // json datasource
             "data": function ( d ) {
                d.kas_detail = $("#kas_detail").val();
                d.tanggal_detail = $("#tanggal_detail").val();
                // d.custom = $('#myInput').val();
                // etc
            },
                type: "post",  // method  , by default get
            error: function(){  // error handling
              $(".tbody_keluar").html("");
              $("#detail_keluar").append('<tbody class="tbody_keluar"><tr><th colspan="3"></th></tr></tbody>');
              $("#detail_keluar_processing").css("display","none");
              
         
            }
          }
    
        });
//Ending untuk tampilkan table kas KELUAR



//untuk tampilkan table kas MUTASI
     $('#detail_mutasi').DataTable().destroy();
          var dataTable = $('#detail_mutasi').DataTable( {
          "processing": true,
          "serverSide": true,
          "info":     true,
          "language": {
        "emptyTable":     "My Custom Message On Empty Table"
    },
          "ajax":{
            url :"proses_cashflow_detail_mutasi.php", // json datasource
             "data": function ( d ) {
                d.kas_detail = $("#kas_detail").val();
                d.tanggal_detail = $("#tanggal_detail").val();
                // d.custom = $('#myInput').val();
                // etc
            },
                type: "post",  // method  , by default get
            error: function(){  // error handling
              $(".tbody_keluar").html("");
              $("#detail_mutasi").append('<tbody class="tbody_keluar"><tr><th colspan="3"></th></tr></tbody>');
              $("#detail_mutasi_processing").css("display","none");
              
         
            }
          }
    
        });
//Ending untuk tampilkan table kas MUTASI


// show table id resultnya and input form jadi kosong
          $("#result_detail_masuk").show();
          $("#result_detail_keluar").show();
          $("#result_detail_mutasi").show();
          $("#show_detail").show();
$("#show_table_rekap").hide();
$("#show_table_detail").show();

   });  

  $("#form_detail").submit(function(){
      return false;
  });
  function clearInput(){
      $("#form_detail :input").each(function(){
          $(this).val('');
      });
    };
  } );
 
 </script>
 <!--ENDING SCRIPT DETAIL-->




<!--SCRIPT PROSES REKAP-->
<script type="text/javascript" language="javascript" >
   $(document).ready(function() {
		$(document).on('click','#submit_rekap',function(e) {

//proses for hasil
var kas_rekap = $("#kas_rekap").val();
var tanggal_rekap = $("#tanggal_rekap").val();



$.getJSON('proses_cashflow_tanggal_rekap.php',{kas_rekap:kas_rekap,tanggal_rekap:tanggal_rekap},function(json){

    $("#saldo_awal").html(tandaPemisahTitik(json.keterangan));
      $("#perubahan_saldo").html(tandaPemisahTitik(json.provinsi));
      $("#saldo_akhir").html(tandaPemisahTitik(json.petugas));
                $("#show_total").show();

  });

// end proses for hasil

//untuk tampilkan table kas masuk
     $('#table_masuk').DataTable().destroy();
          var dataTable = $('#table_masuk').DataTable( {
          "processing": true,
          "serverSide": true,
          "info":     true,
          "language": {
        "emptyTable":     "My Custom Message On Empty Table"
    },
          "ajax":{
            url :"proses_cashflow_rekap_masuk.php", // json datasource
             "data": function ( d ) {
                d.kas_rekap = $("#kas_rekap").val();
                d.tanggal_rekap = $("#tanggal_rekap").val();
                // d.custom = $('#myInput').val();
                // etc
            },
                type: "post",  // method  , by default get
            error: function(){  // error handling
              $(".tbody_masuk").html("");
              $("#table_masuk").append('<tbody class="tbody_masuk"><tr><th colspan="3"></th></tr></tbody>');
              $("#table_masuk_processing").css("display","none");
              
         
            }
          }
    
        });
//Ending untuk tampilkan table kas masuk


//untuk tampilkan table kas KELUAR
     $('#table_keluar').DataTable().destroy();
          var dataTable = $('#table_keluar').DataTable( {
          "processing": true,
          "serverSide": true,
          "info":     true,
          "language": {
        "emptyTable":     "My Custom Message On Empty Table"
    },
          "ajax":{
            url :"proses_cashflow_rekap_keluar.php", // json datasource
             "data": function ( d ) {
                d.kas_rekap = $("#kas_rekap").val();
                d.tanggal_rekap = $("#tanggal_rekap").val();
                // d.custom = $('#myInput').val();
                // etc
            },
                type: "post",  // method  , by default get
            error: function(){  // error handling
              $(".tbody_keluar").html("");
              $("#table_keluar").append('<tbody class="tbody_keluar"><tr><th colspan="3"></th></tr></tbody>');
              $("#table_keluar_processing").css("display","none");
              
         
            }
          }
    
        });
//Ending untuk tampilkan table kas KELUAR



//untuk tampilkan table kas MUTASI
     $('#table_mutasi').DataTable().destroy();
          var dataTable = $('#table_mutasi').DataTable( {
          "processing": true,
          "serverSide": true,
          "info":     true,
          "language": {
        "emptyTable":     "My Custom Message On Empty Table"
    },
          "ajax":{
            url :"proses_cashflow_rekap_mutasi.php", // json datasource
             "data": function ( d ) {
                d.kas_rekap = $("#kas_rekap").val();
                d.tanggal_rekap = $("#tanggal_rekap").val();
                // d.custom = $('#myInput').val();
                // etc
            },
                type: "post",  // method  , by default get
            error: function(){  // error handling
              $(".tbody_keluar").html("");
              $("#table_mutasi").append('<tbody class="tbody_keluar"><tr><th colspan="3"></th></tr></tbody>');
              $("#table_mutasi_processing").css("display","none");
              
         
            }
          }
    
        });
//Ending untuk tampilkan table kas MUTASI


// show table id resultnya and input form jadi kosong
          $("#result_masuk").show();
          $("#result_keluar").show();
          $("#result_mutasi").show();
          $("#show_rekap").show();

$("#show_table_rekap").show();
$("#show_table_detail").hide();
   } );  

  $("#form_rekap").submit(function(){
      return false;
  });
  function clearInput(){
      $("#form_rekap :input").each(function(){
          $(this).val('');
      });
  	};
  } );
 
 </script>
 <!--ENDING SCRIPT REKAP-->



    <script>
    $(function() {
    $( "#tanggal_detail" ).datepicker({dateFormat: "yy-mm-dd"});
    });
    </script>

    <script>
    $(function() {
    $( "#tanggal_rekap" ).datepicker({dateFormat: "yy-mm-dd"});
    });
    </script>


<script type="text/javascript">
$(document).ready(function(){
    $("#nav_detail").click(function(){    
    $("#show_detail").show();
    $("#show_rekap").hide();
    $("#show_total").hide();


    });

    $("#nav_rekap").click(function(){    
    $("#show_rekap").show();  
    $("#show_detail").hide();
    $("#show_total").hide();

    });

});
</script>



<?php include 'footer.php'; ?>