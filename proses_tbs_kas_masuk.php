<?php session_start();

    //memasukkan file db.php
    include 'sanitasi.php';
    include 'db.php';

    $session_id = $_POST['session_id'];
    $tahun_sekarang = date('Y');
    $bulan_sekarang = date('m');
    $tanggal_sekarang = date('Y-m-d');
    $jam_sekarang = date('H:i:s');
    $tahun_terakhir = substr($tahun_sekarang, 2);
    $tanggal = stringdoang($_POST['tanggal']);
    $waktu = $tanggal." ".$jam_sekarang;
   
   $keterangan = stringdoang($_POST['keterangan']);
        $dari_akun = stringdoang($_POST['dari_akun']);
        $ke_akun = stringdoang($_POST['ke_akun']);
        $jumlah = angkadoang($_POST['jumlah']);
        $user = $_SESSION['nama'];
        
        $perintah = $db->prepare("INSERT INTO tbs_kas_masuk (session_id,keterangan,dari_akun,ke_akun, jumlah,tanggal,jam,user) VALUES (?,?,?,?,?,?,?,?)");

        $perintah->bind_param("ssssisss",
          $session_id, $keterangan, $dari_akun, $ke_akun, $jumlah, $tanggal_sekarang, $jam_sekarang,
          $user);

        $perintah->execute();
        

if (!$perintah) {
   die('Query Error : '.$db->errno.
   ' - '.$db->error);
}
else {
}
    
//Untuk Memutuskan Koneksi Ke Database 
    ?>
<?php

    //menampilkan semua data yang ada pada tabel tbs kas masuk dalam DB
$perintah = $db->query("SELECT km.id, km.session_id, km.keterangan, km.ke_akun, km.dari_akun, km.jumlah, km.tanggal, km.jam, km.user, da.nama_daftar_akun FROM tbs_kas_masuk km INNER JOIN daftar_akun da ON km.ke_akun = da.kode_daftar_akun WHERE km.session_id = '$session_id' ORDER BY km.id DESC LIMIT 1 ");

      //menyimpan data sementara yang ada pada $perintah
$data1 = mysqli_fetch_array($perintah);
 
        $perintah1 = $db->query("SELECT km.id, km.session_id, km.keterangan, km.dari_akun, km.jumlah, km.tanggal, km.jam, km.user, da.nama_daftar_akun FROM tbs_kas_masuk km INNER JOIN daftar_akun da ON km.dari_akun = da.kode_daftar_akun WHERE km.dari_akun = '$data1[dari_akun]'");
        $data10 = mysqli_fetch_array($perintah1);

        //menampilkan data
      echo "<tr class='tr-id-".$data1['id']."'>
      <td data-dari-akun ='".$data10['nama_daftar_akun']."'>". $data10['nama_daftar_akun'] ."</td>
      <td>". $data1['nama_daftar_akun'] ."</td>

      <td class='edit-jumlah' data-id='".$data1['id']."'><span id='text-jumlah-".$data1['id']."'>". rp($data1['jumlah']) ."</span> <input type='hidden' id='input-jumlah-".$data1['id']."' value='".$data1['jumlah']."' class='input-jumlah' data-id='".$data1['id']."' autofocus='' data-jumlah='".$data1['jumlah']."'> </td>   

      <td>". $data1['tanggal'] ."</td>
      <td>". $data1['jam'] ."</td>
      <td>". $data1['keterangan'] ."</td>
      <td>". $data1['user'] ."</td>

      <td> <button class='btn btn-danger btn-hapus-tbs' id='btn-hapus-".$data1['id']."' data-id='". $data1['id'] ."' data-jumlah='".$data1['jumlah']."' data-dari='". $data1['dari_akun'] ."'> <span class='glyphicon glyphicon-trash'> </span> Hapus </button> </td> 
      
      </tr>";

//Untuk Memutuskan Koneksi Ke Database

mysqli_close($db); 
    ?>


    
                                  <script type="text/javascript">
                               
                                  $(document).ready(function(){
                                  
                                  //fungsi hapus data 
                                  $(".btn-hapus-tbs").click(function(){
                                  var id = $(this).attr("data-id");
                                  var jumlah = $(this).attr("data-jumlah");
                                  var total = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#jumlahtotal").val()))));
                                 

                                  
                                  if (total == '') 
                                  {
                                  total = 0;
                                  }
                                  else if(jumlah == '')
                                  {
                                  jumlah = 0;
                                  };
                                  var subtotal = parseInt(total,10) - parseInt(jumlah,10);
                                  
                                  
                                  if (subtotal == 0) 
                                  {
                                  subtotal = 0;
                                  $("#keakun").attr("disabled", false);
                                  }



                                  $("#jumlahtotal").val(tandaPemisahTitik(subtotal))


                                  $.post("hapus_tbs_kas_masuk.php",{id:id},function(data){

                                  if (data != '') {
                                  $(".tr-id-"+id+"").remove();
                                  }
         
                                  });
                                  
                                  });
                                  
                                  
                                  //end fungsi hapus data

                                  
                                  $('form').submit(function(){
                                  
                                  return false;
                                  });
                                  });
                                  
                                  
                                  function tutupalert() {
                                  $("#alert").html("")
                                  }
                                  
                                  function tutupmodal() {
                                  $("#modal_edit").modal("hide")
                                  }
                                  
                                  </script>



                                  <script type="text/javascript">
                                    
                                    $(".edit-jumlah").dblclick(function(){
                                    
                                    var id = $(this).attr("data-id");
                                    
                                    var input_jumlah = $("#text-jumlah-"+id+"").text();
                                    
                                    $("#text-jumlah-"+id+"").hide();
                                    
                                    $("#input-jumlah-"+id+"").attr("type", "text");
                                    
                                    });
                                    
                                    $(".input-jumlah").blur(function(){
                                    
                                    var id = $(this).attr("data-id");
                                    var input_jumlah = $(this).val();
                                    
                                    var jumlah_lama = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($(this).attr("data-jumlah")))));
                                    var total_lama = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#jumlahtotal").val()))));
                                    
                                    
                                    
                                    if (total_lama == '') 
                                    {
                                    total_lama = 0;
                                    }
                                    
                                    var subtotal = parseInt(total_lama,10) - parseInt(jumlah_lama,10) + parseInt(input_jumlah,10);
                                    
                                    
                                    $.post("update_tbs_kas_masuk.php",{id:id,input_jumlah:input_jumlah,jenis_edit:"jumlah"},function(data){
                                   
                                    $("#input-jumlah-"+id).attr("data-jumlah", input_jumlah);
                                    $("#btn-hapus-"+id).attr("data-jumlah", input_jumlah);
                                    $("#text-jumlah-"+id+"").show();
                                    $("#text-jumlah-"+id+"").text(tandaPemisahTitik(input_jumlah));
                                    $("#jumlahtotal").val(tandaPemisahTitik(subtotal));
                                    $("#jumlah").val(tandaPemisahTitik(subtotal));
                                    $("#input-jumlah-"+id+"").attr("type", "hidden");           
                                    
                                    });
                                    
                                    
                                    
                                    });
                                    
                                    </script>
