<?php session_start();
    // memasukan file yang ada pada db.php
include 'sanitasi.php';
include 'db.php';
    // mengirim data sesuai variabel yang ada dengan menggunakan metode POST
    $kode_barang = stringdoang($_POST['kode_barang']);
    $nama_barang = stringdoang($_POST['nama_barang']);
    $satuan = stringdoang($_POST['satuan']);
    $jumlah_fisik = stringdoang($_POST['fisik']);
        $no_faktur = stringdoang($_POST['no_faktur']);

      $session_id = session_id();


        $query9 = $db->prepare("UPDATE barang SET stok_opname = 'ya' WHERE kode_barang = ?");

        $query9->bind_param("s", $kode_barang);

        $query9->execute();


        $query1 = $db->query("SELECT SUM(jumlah_barang) AS masuk FROM detail_pembelian WHERE kode_barang = '$kode_barang'");
        $hasil1 = mysqli_fetch_array($query1);
        $jumlah_masuk_pembelian = $hasil1['masuk'];


        $query3 = $db->query("SELECT SUM(jumlah) AS masuk FROM detail_item_masuk WHERE kode_barang = '$kode_barang'");
        $hasil2 = mysqli_fetch_array($query3);
        $jumlah_masuk_item_masuk = $hasil2['masuk'];



        $hasil_masuk = $jumlah_masuk_pembelian + $jumlah_masuk_item_masuk;



        $query4 = $db->query("SELECT SUM(jumlah_barang) AS keluar FROM detail_penjualan WHERE kode_barang = '$kode_barang'");
        $hasil3 = mysqli_fetch_array($query4);
        $jumlah_keluar_penjualan = $hasil3['keluar'];


        $query5 = $db->query("SELECT SUM(jumlah) AS keluar FROM detail_item_keluar WHERE kode_barang = '$kode_barang'");
        $hasil4 = mysqli_fetch_array($query5);
        $jumlah_keluar_item_keluar = $hasil4['keluar'];

        $hasil_keluar = $jumlah_keluar_penjualan + $jumlah_keluar_item_keluar;



        $query6 = $db->query("SELECT * FROM barang WHERE kode_barang = '$kode_barang'");

        // mencari jumlah Barang
           
  $select = $db->query("SELECT SUM(sisa) AS jumlah_barang FROM hpp_masuk WHERE kode_barang = '$kode_barang'");
    $ambil_sisa = mysqli_fetch_array($select);
 
            
            
            $jumlah_stok_komputer =$ambil_sisa['jumlah_barang'];
            
            
            
            $selisih_fisik = $jumlah_fisik - $jumlah_stok_komputer;

    if ($selisih_fisik < 0) {

       // HARGA DARI HPP MASUK

       $pilih_hpp = $db->query("SELECT harga_unit FROM hpp_masuk WHERE kode_barang = '$kode_barang' ORDER BY id DESC LIMIT 1");
       $ambil_hpp = mysqli_fetch_array($pilih_hpp);
       $jumlah_hpp = $ambil_hpp['harga_unit'];


       // SAMPAI SINI

    } 

    else {

              // HARGA DARI DETAIL PEMBELIAN ATAU BARANG
        
        $select2 = $db->query("SELECT harga FROM detail_pembelian WHERE kode_barang = '$kode_barang' ORDER BY id DESC LIMIT 1");
        $num_rows = mysqli_num_rows($select2);
        $fetc_array = mysqli_fetch_array($select2);
        
        $select3 = $db->query("SELECT harga_beli FROM barang WHERE kode_barang = '$kode_barang' ORDER BY id DESC LIMIT 1");
        $ambil_barang = mysqli_fetch_array($select3);
        
        if ($num_rows == 0) {
        
         $jumlah_hpp = $ambil_barang['harga_beli'];
        
        
        } 
        
        else {
        
         $jumlah_hpp = $fetc_array['harga'];

        
        }
        
        // SAMPAI SINI


    }
    

        
     $selisih_harga = $jumlah_hpp * $selisih_fisik;      
        
        
        
        $cek = $db->query("SELECT * FROM stok_awal WHERE kode_barang = '$kode_barang' ");
        $data = mysqli_fetch_array($cek);
        $num = mysqli_num_rows($cek);

        if ($num > 0 )
        {
                  $stok_awal = $data['jumlah_awal'];

        }
        else {
          $stok_awal = 0;
        }
  



        $query = "INSERT INTO tbs_stok_opname (no_faktur, kode_barang, nama_barang, satuan, awal, masuk, keluar, stok_sekarang, fisik, selisih_fisik, selisih_harga, harga, hpp) VALUES ('$no_faktur', '$kode_barang','$nama_barang','$satuan','$stok_awal','$hasil_masuk','$hasil_keluar','$jumlah_stok_komputer','$jumlah_fisik','$selisih_fisik','$selisih_harga','$jumlah_hpp','$jumlah_hpp')";      

        
        if ($db->query($query) === TRUE)
        {

        }
        else
        {
            echo "Error: " . $query . "<br>" . $db->error;
        }
  
    ?>

 <?php
                  
                  
                  $perintah = $db->query("SELECT tio.no_faktur,tio.kode_barang,tio.nama_barang,s.nama,tio.id,tio.stok_sekarang,tio.fisik,tio.selisih_fisik,tio.harga,tio.selisih_harga,tio.hpp FROM tbs_stok_opname tio LEFT JOIN satuan s ON tio.satuan = s.id WHERE tio.no_faktur = '$no_faktur' ORDER BY tio.id DESC");
                  
                  //menyimpan data sementara yang ada pada $perintah
                  while ($data1 = mysqli_fetch_array($perintah))
                  {
                  
                  
                  echo "<tr class='tr-id-".$data1['id']."'>
                  
                  <td>". $data1['kode_barang'] ."</td>
                  <td>". $data1['nama_barang'] ."</td>
                  <td>". $data1['nama'] ."</td>
                  <td><span id='text-stok-sekarang-".$data1['id']."'>". rp($data1['stok_sekarang']) ."</span></td>";

     $pilih = $db->query("SELECT no_faktur FROM hpp_masuk WHERE no_faktur = '$data1[no_faktur]' AND kode_barang = '$data1[kode_barang]' AND sisa != jumlah_kuantitas");
        $row_alert = mysqli_num_rows($pilih);

                  if ($row_alert > 0){
                  
                  echo "<td class='btn-alert' data-kode-barang='". $data1['kode_barang'] ."' data-faktur='". $data1['no_faktur'] ."' >". $data1['fisik'] ."  </td>";
                  }
                  
                  else{
                  
                  echo "<td class='edit-jumlah' data-id='".$data1['id']."'><span id='text-jumlah-".$data1['id']."'>". $data1['fisik'] ."</span> <input type='hidden' id='input-jumlah-".$data1['id']."' value='".$data1['fisik']."' class='input_jumlah' data-id='".$data1['id']."' autofocus='' data-faktur='".$data1['no_faktur']."' data-harga='".$data1['harga']."' data-kode='".$data1['kode_barang']."' data-selisih-fisik='".$data1['selisih_fisik']."' data-stok-sekarang='".$data1['stok_sekarang']."'> </td>";
                      }


                  echo "<td><span id='text-selisih-fisik-".$data1['id']."'>". rp($data1['selisih_fisik']) ."</span></td>
                  <td><span id='text-hpp-".$data1['id']."'>". rp($data1['hpp']) ."</span></td>
                  <td><span id='text-selisih-".$data1['id']."'>". rp($data1['selisih_harga']) ."</span></td>
                  <td>". rp($data1['harga']) ."</td>";

             
                  
                  if ($row_alert > 0) {
                  
                  echo "<td> <button class='btn btn-danger btn-alert' data-id='". $data1['id'] ."' data-kode-barang='". $data1['kode_barang'] ."' data-faktur='". $data1['no_faktur'] ."' data-nama-barang='". $data1['nama_barang'] ."'> <span class='glyphicon glyphicon-trash'> </span> Hapus </button> </td> ";
                }
                else{
                  echo "<td> <button class='btn btn-danger btn-hapus btn-sm' data-id='". $data1['id'] ."' data-kode-barang='". $data1['kode_barang'] ."' data-nama-barang='". $data1['nama_barang'] ."'> <span class='glyphicon glyphicon-trash'> </span> Hapus </button> </td>";
                }


                 echo "</tr>";
                  }

                  //Untuk Memutuskan Koneksi Ke Database
                  mysqli_close($db);   
                  ?>

<script>
                  // untuk memunculkan data tabel 
                  $(document).ready(function(){
                  $("#tableuser").dataTable();

                  });
</script>

<script type="text/javascript">

                                  
//fungsi hapus data 
    $(".btn-hapus").click(function(){
    var nama_barang = $(this).attr("data-nama-barang");
    var kode_barang = $(this).attr("data-kode-barang");
    var id = $(this).attr("data-id");
                  
                  $.get("cek_total_selisih_harga.php",function(data){
                     data = data.replace(/\s+/g, '');
                  $("#total_selisih_harga").val(data);
                  });

    $(".tr-id-"+id).remove();
    $.post("hapus_tbs_stok_opname.php",{kode_barang:kode_barang},function(data){
   
    
    });
    
    });
// end fungsi hapus data
 </script>

    <script type="text/javascript">
                                 
                                 $(".edit-jumlah").dblclick(function(){

                                    var id = $(this).attr("data-id");

                                    $("#text-jumlah-"+id+"").hide();

                                    $("#input-jumlah-"+id+"").attr("type", "text");

                                 });


                                 $(".input_jumlah").blur(function(){

                                    var id = $(this).attr("data-id");
                                    var jumlah_baru = $(this).val();
                                    var harga = $(this).attr("data-harga");
                                    var kode_barang = $(this).attr("data-kode");

                                    var stok_sekarang = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#text-stok-sekarang-"+id+"").text()))));
                                    var hpp = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#text-hpp-"+id+"").text()))));

                                    var selisih_fisik = parseInt(jumlah_baru,10) - parseInt(stok_sekarang,10);
                                    var selisih_harga = parseInt(selisih_fisik,10) * parseInt(hpp,10);


                              
                                  $.post("update_tbs_stok_opname.php", {jumlah_baru:jumlah_baru,id:id,kode_barang:kode_barang,selisih_harga:selisih_harga,selisih_fisik:selisih_fisik}, function(info){


                                    $("#text-jumlah-"+id+"").show();
                                    $("#text-jumlah-"+id+"").text(jumlah_baru);
                                    $("#text-selisih-"+id+"").text(tandaPemisahTitik(selisih_harga));
                                    $("#text-selisih-fisik-"+id+"").text(tandaPemisahTitik(selisih_fisik));
                                    $("#input-jumlah-"+id+"").attr("type", "hidden");        
                                    
                                    
                                    });
                                    
                           

                                   
                                   $("#kode_barang").focus();

                                 });

                             </script>



<script type="text/javascript">
  
    $(document).on('click', '.btn-alert', function (e) {
    var no_faktur = $(this).attr("data-faktur");
    var kode_barang = $(this).attr("data-kode-barang");

    $.post('modal_alert_hapus_data_edit_stok_opname.php',{no_faktur:no_faktur,kode_barang:kode_barang},function(data){


    $("#modal_alert").modal('show');
    $("#modal-alert").html(data);

    });

    
    });

</script>
