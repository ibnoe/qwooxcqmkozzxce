<?php include 'session_login.php';

//memasukkan file session login, header, navbar, db.php
include 'header.php';
include 'navbar.php';
include 'sanitasi.php';
include 'db.php';


//menampilkan seluruh data yang ada pada tabel pembelian dalan DB
$perintah = $db->query("SELECT * FROM stok_opname");


 ?>


<style>
	tr:nth-child(even){background-color: #f2f2f2}
</style>

<div class="container"> <!--start of container-->

<h3><b> DATA STOK OPNAME </b></h3><hr>

<!-- Modal Hapus data -->
<div id="modal_hapus" class="modal fade" role="dialog">
  <div class="modal-dialog">



    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Konfirmasi Hapus Data Satuan</h4>
      </div>

      <div class="modal-body">
   
   <p>Apakah Anda yakin Ingin Menghapus Data ini ?</p>
   <form >
    <div class="form-group">
    <label> Nomor Faktur :</label>
     <input type="text" id="data_faktur" class="form-control" readonly=""> 
    </div>
   
   </form>
   
  <div class="alert alert-success" style="display:none">
   <strong>Berhasil!</strong> Data berhasil Di Hapus
  </div>
 

     </div>

      <div class="modal-footer">
        <button type="button" data-id="" class="btn btn-info" data-id="" id="btn_jadi_hapus"> <span class='glyphicon glyphicon-ok-sign'> </span> Ya</button>
        <button type="button" class="btn btn-warning" data-dismiss="modal"> <span class='glyphicon glyphicon-remove-sign'> </span> Batal</button>
      </div>
    </div>

  </div>
</div><!-- end of modal hapus data  -->

<div id="modal_alert" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h3 style="color:orange" class="modal-title"><span class="glyphicon glyphicon-info-sign">Info</span></h3>
        
      </div>

      <div class="modal-body">
      <div class="table-responsive">
      <span id="modal-alert">
       </span>
      </div>

     </div>

      <div class="modal-footer">
        <h6 style="text-align: left"><i> * jika ingin menghapus atau mengedit data, 
        silahkan hapus terlebih dahulu<br> Transaksi Penjualan.</i></h6>
        <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<div id="modal_detail" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <center><h4 class="modal-title"><b>Detail Stok Opname</b></h4></center>
      </div>

      <div class="modal-body">
      <div class="table-responsive">
      <span id="modal-detail"> </span>
      </div>

  <table id="table_modal_detail" class="table table-bordered table-sm">
  <thead> <!-- untuk memberikan nama pada kolom tabel -->

          <th> No Faktur </th>
          <th> Kode Barang </th>
          <th> Nama Barang </th>
          <th> Stok Komputer </th>
          <th> Fisik </th>
          <th> Selisih Fisik </th>
          <th> HPP </th>
          <th> Selisih Harga </th>

  </thead> <!-- tag penutup tabel -->
  </table>

     </div>

      <div class="modal-footer">
        
    <center><button type="button" class="btn btn-danger" data-dismiss="modal"><i class='fa fa-close'></i></button></center> 
      </div>
    </div>

  </div>
</div>


<!--membuat link-->

<?php
include 'db.php';

$pilih_akses_stok_opname = $db->query("SELECT * FROM otoritas_stok_opname WHERE id_otoritas = '$_SESSION[otoritas_id]'");
$stok_opname = mysqli_fetch_array($pilih_akses_stok_opname);

if ($stok_opname['stok_opname_tambah'] > 0) {

echo '<a href="form_stok_opname.php"  class="btn btn-info" > <i class="fa fa-plus"> </i> STOK OPNAME</a>';

}

?>
<br><br>
<button type="submit" name="submit" id="filter_1" class="btn btn-primary" > Filter Faktur </button>
<button type="submit" name="submit" id="filter_2" class="btn btn-primary" > Filter Detail </button>

  <input type="hidden" name="no_faktur_detail" class="form-control " id="no_faktur_detail" placeholder="no_faktur  "/>
<!--START FILTER FAKTUR-->
<span id="fil_faktur">
<form class="form-inline" action="show_filter_stok_opname.php" method="get" role="form">
					
					<div class="form-group"> 
					
					<input type="text" name="dari_tanggal" id="dari_tanggal" class="form-control" placeholder="Dari Tanggal" required="">
					</div>
					
					<div class="form-group"> 
					
					<input type="text" name="sampai_tanggal" id="sampai_tanggal" class="form-control" placeholder="Sampai Tanggal" value="<?php echo date("Y-m-d"); ?>" required="">
					</div>
					
					<button type="submit" name="submit" id="submit_filter_1" class="btn btn-success" ><i class="fa fa-eye"> </i> Lihat Faktur </button>

					
</form>
<span id="result"></span>  
</span>
<!--END FILTER FAKTUR-->

<!--START FILTER DETAIl-->
<span id="fil_detail">
<form class="form-inline" action="show_filter_stok_opname_detail.php" method="get" role="form">
					
					<div class="form-group"> 
					
					<input type="text" name="dari_tanggal" id="dari_tanggal2" class="form-control" placeholder="Dari Tanggal" required="">
					</div>
					
					<div class="form-group"> 
					
					<input type="text" name="sampai_tanggal" id="sampai_tanggal2" class="form-control" placeholder="Sampai Tanggal" value="<?php echo date("Y-m-d"); ?>" required="">
					</div>
					
					<button type="submit" name="submit" id="submit_filter_2" class="btn btn-success" ><i class="fa fa-eye"> </i> Lihat Detail </button>

					
</form>
<span id="result"></span>  
</span>
<!--END FILTER DETAIl-->

<div class="table-responsive">
<span id="tabel_baru">
<table id="table_stok_opname" class="table table-bordered">
		<thead>
			<th style='background-color: #4CAF50; color:white'> Nomor Faktur </th>
			<th style='background-color: #4CAF50; color:white'> Tanggal </th>
			<th style='background-color: #4CAF50; color:white'> Jam </th>
			<th style='background-color: #4CAF50; color:white'> Status </th>
			<th style='background-color: #4CAF50; color:white'> Total Selisih</th>
			<th style='background-color: #4CAF50; color:white'> User </th>
      <th style='background-color: #4CAF50; color:white'> Keterangan </th>
			<th style='background-color: #4CAF50; color:white'> Detail </th>
			<?php 

				if ($stok_opname['stok_opname_edit'] > 0) {
					echo "<th style='background-color: #4CAF50; color:white'> Edit </th>";
				}
							 ?>

				<?php
				include 'db.php';

				if ($stok_opname['stok_opname_hapus'] > 0) {

								echo "<th style='background-color: #4CAF50; color:white'> Hapus </th>";
							}
			?>
			
			<th style='background-color: #4CAF50; color:white'> Download </th>
			
		</thead>
		
	</table>
</span>
</div>

<br>
	<button type="submit" id="submit_close" class="glyphicon glyphicon-remove btn btn-danger" style="display:none"></button> 
</div><!--end of container-->
		<span id="demo"> </span>

<!--DATA TABLE MENGGUNAKAN AJAX-->
<script type="text/javascript" language="javascript" >
      $(document).ready(function() {

          var dataTable = $('#table_stok_opname').DataTable( {
          "processing": true,
          "serverSide": true,
          "ajax":{
            url :"datatable_stok_opname.php", // json datasource
           
            type: "post",  // method  , by default get
            error: function(){  // error handling
              $(".employee-grid-error").html("");
              $("#table_stok_opname").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
              $("#employee-grid_processing").css("display","none");
            }
        },
            
            "fnCreatedRow": function( nRow, aData, iDataIndex ) {
                $(nRow).attr('class','tr-id-'+aData[10]+'');
            },
        });

        $("#form").submit(function(){
        return false;
        });
        

      } );
    </script>
<!--/DATA TABLE MENGGUNAKAN AJAX-->


<!--Start Ajax Modal DETAIL-->
<script type="text/javascript" language="javascript" >
   $(document).ready(function() {

    $(document).on('click', '.detail', function (e) {
    $("#modal_detail").modal('show');

    var no_faktur = $(this).attr("no_faktur");
  
            $('#table_modal_detail').DataTable().destroy();

        var dataTable = $('#table_modal_detail').DataTable( {
          "processing": true,
          "serverSide": true,
          "ajax":{
            url :"show_detail_stok_opname.php", // json datasource
             "data": function ( d ) {
                  d.no_faktur = no_faktur;
                  // d.custom = $('#myInput').val();
                  // etc
              },
            type: "post",  // method  , by default get
            error: function(){  // error handling
              $(".employee-grid-error").html("");
              $("#table_modal_detail").append('<tbody class="employee-grid-error"><tr><th colspan="3">Data Tidak Ditemukan.. !!</th></tr></tbody>');
              $("#employee-grid_processing").css("display","none");
              
            }
          },

         

        });  
  
     }); 
  });
 </script>
<!--Ending Ajax Modal Detail-->



<script type="text/javascript">
	
	//fungsi hapus data 
		$(document).on('click','.btn-hapus',function(e){
		var no_faktur = $(this).attr("data-faktur");
		var id = $(this).attr("data-id");
		
		$("#data_faktur").val(no_faktur);
		$("#modal_hapus").modal('show');
		$("#btn_jadi_hapus").attr("data-id", id);
		
		
		});
// end fungsi hapus data

</script>

<script type="text/javascript">
     $(document).on('click', '#btn_jadi_hapus', function (e) {    
					var no_faktur = $("#data_faktur").val();
					var id = $(this).attr("data-id");
                    
                    
                    $("#modal_hapus").modal('hide');
                    
                    $.post("hapus_data_stok_opname.php",{no_faktur:no_faktur},function(data){
                      $('#table_stok_opname').DataTable().destroy();
     
                  var dataTable = $('#table_stok_opname').DataTable( {
                      "processing": true,
                      "serverSide": true,
                      "ajax":{
                        url :"datatable_stok_opname.php", // json datasource
                        type: "post",  // method  , by default get
                        error: function(){  // error handling
                          $(".employee-grid-error").html("");
                          $("#table_stok_opname").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                          $("#employee-grid_processing").css("display","none");
                          }
                      },
                         "fnCreatedRow": function( nRow, aData, iDataIndex ) {

                          $(nRow).attr('class','tr-id-'+aData[12]+'');         

                      }
                    });
                    });

                    
        }); 

     
</script>

<script type="text/javascript">
	
	$(document).on('click', '.btn-alert', function (e) {    

		var no_faktur = $(this).attr("data-faktur");

		$.post('modal_alert_hapus_data_stok_opname.php',{no_faktur:no_faktur},function(data){


		$("#modal_alert").modal('show');
		$("#modal-alert").html(data);

		});

		
		});

</script>



<script>
    $(function() {
    $( "#dari_tanggal" ).datepicker({dateFormat: "yy-mm-dd"});
    });
    </script>


    <script>
    $(function() {
    $( "#sampai_tanggal" ).datepicker({dateFormat: "yy-mm-dd"});
    });
    </script>

    <script>
    $(function() {
    $( "#dari_tanggal2" ).datepicker({dateFormat: "yy-mm-dd"});
    });
    </script>


    <script>
    $(function() {
    $( "#sampai_tanggal2" ).datepicker({dateFormat: "yy-mm-dd"});
    });
    </script>

    <script type="text/javascript">
//fil FAKTUR
$("#submit_filter_1").click(function() {
$.post($("#formtanggal").attr("action"), $("#formtanggal :input").serializeArray(), function(info) { $("#dataabsen").html(info); });
    
});

$("#formtanggal").submit(function(){
    return false;
});

function clearInput(){
    $("#formtanggal :input").each(function(){
        $(this).val('');
    });
};



</script>

<script type="text/javascript">
//fill DETAIL
$("#submit_filter_2").click(function() {
$.post($("#formtanggal").attr("action"), $("#formtanggal :input").serializeArray(), function(info) { $("#dataabsen").html(info); });
    
});

$("#formtanggal").submit(function(){
    return false;
});

function clearInput(){
    $("#formtanggal :input").each(function(){
        $(this).val('');
    });
};



</script>

<script type="text/javascript">
		$(document).ready(function(){
			$("#fil_faktur").hide();
			$("#fil_detail").hide();
	});
</script>


<script type="text/javascript">
		$(document).ready(function(){
				$("#filter_1").click(function(){		
			$("#fil_faktur").show();
			$("#filter_2").show();
			$("#filter_1").hide();	
			$("#fil_detail").hide();
			});

				$("#filter_2").click(function(){		
			$("#fil_detail").show();	
			$("#fil_faktur").hide();
			$("#filter_2").hide();
			$("#filter_1").show();
			});

	});
</script>

<?php 
include 'footer.php';
 ?>