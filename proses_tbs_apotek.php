<?php 
include 'db.php';
include_once 'sanitasi.php';

session_start();
$session_id = session_id();
$tipe = stringdoang($_POST['tipe_barang']);
$penjamin = stringdoang($_POST['penjamin']);
$apoteker = stringdoang($_POST['apoteker']);
$analis = stringdoang($_POST['analis']);
$no_rm  = stringdoang($_POST['no_rm']);
$petugas = $_SESSION['nama'];
$waktu = date("Y-m-d H:i:s");
$bulan_php = date('m');
$tahun_php = date('Y');
  
      $kode = stringdoang($_POST['kode_barang']);
      $satuan = stringdoang($_POST['satuan']);
      $harga = angkadoang($_POST['harga']);
      $jumlah = angkadoang($_POST['jumlah_barang']);
      $nama = stringdoang($_POST['nama_barang']);
      $user = $_SESSION['nama'];
      $potongan = angkadoang($_POST['potongan']);
      $a = $harga * $jumlah;
      $tahun_sekarang = date('Y');
      $bulan_sekarang = date('m');
      $tanggal_sekarang = date('Y-m-d');
      $jam_sekarang = date('H:i:s');
      $tahun_terakhir = substr($tahun_sekarang, 2);
      $ppn = stringdoang($_POST['ppn']);
 $pajak = stringdoang($_POST['tax']); 

$pilih_akses_tombol = $db->query("SELECT * FROM otoritas_penjualan_apotek WHERE id_otoritas = '$_SESSION[otoritas_id]' ");
$otoritas_tombol = mysqli_fetch_array($pilih_akses_tombol);

$select_produk = $db->query("SELECT nama_barang FROM barang WHERE kode_barang = '$kode' ");
$data_produk = mysqli_fetch_array($select_produk);

if ($nama == "") {
  $nama = $data_produk['nama_barang'];
}
else{  
 $nama = stringdoang($_POST['nama_barang']);
}

$id_userr = $db->query("SELECT id FROM user WHERE nama = '$user'");
$data_id = mysqli_fetch_array($id_userr);
$id_kasir = $data_id['id'];

          if(strpos($potongan, "%") !== false)
          {
              $potongan_jadi = $a * $potongan / 100;
              $potongan_tampil = $potongan_jadi;
          }
          else{

             $potongan_jadi = $potongan;
             $potongan_tampil = $potongan;
          }


if ($ppn == 'Exclude')
{

 $a = $harga * $jumlah;

 $x = $a - $potongan_tampil;

   $tax_persen = $x * $pajak / 100;

}
elseif ($ppn == 'Include') 
{

          $a = $harga * $jumlah;

            $satu = 1;

              $x = $a - $potongan_tampil;

              $hasil_tax = $satu + ($pajak / 100);

              $hasil_tax2 = $x / $hasil_tax;

              $tax_persen = $x - $hasil_tax2;

}
else
{
  $tax_persen = 0;
}



          $query0 = $db->query("SELECT * FROM tbs_penjualan WHERE kode_barang = '$kode' AND session_id = '$session_id' AND no_reg = '' ");
          $cek    = mysqli_num_rows($query0);
   

if ($ppn == 'Exclude') {
  # code...
              $subtotal1 = $harga * $jumlah;
              $xyz = $subtotal1 - $potongan_jadi;

              $cari_pajak = $xyz * $pajak / 100;

              $subtotal = $harga * $jumlah - $potongan_jadi + round($cari_pajak); 


}

else

{

$subtotal = $harga * $jumlah - $potongan_jadi; 

} 


// STARETTO HARGA BELI 1

          if ($cek > 0 )

          {

  
                  $xml = $db->prepare("UPDATE tbs_penjualan SET jumlah_barang = jumlah_barang + ?, subtotal = subtotal + ?, potongan = ? WHERE kode_barang = ? AND session_id = ? AND no_reg = ''");

                  $xml->bind_param("iisss",
                      $jumlah, $subtotal, $potongan_tampil, $kode, $session_id);

                  $xml->execute();


                $cek_persen_apoteker1 = $db->query("SELECT * FROM fee_produk WHERE nama_petugas = '$apoteker' AND kode_produk = '$kode'");
                $data_persen_apoteker1 = mysqli_fetch_array($cek_persen_apoteker1);

                if ($data_persen_apoteker1['jumlah_prosentase'] != 0 AND $data_persen_apoteker1['jumlah_uang'] == 0 )
                {

                $hasil_hitung_fee_persen_apoteker_harga1 = $subtotal * $data_persen_apoteker1['jumlah_prosentase'] / 100;
                $query_persen_apoteker3 = $db->query("UPDATE tbs_fee_produk SET jumlah_fee = jumlah_fee + '$hasil_hitung_fee_persen_apoteker_harga1' WHERE nama_petugas = '$apoteker' AND kode_produk = '$kode'");

                }
                // AKHIR PSERSENTASE APOTEKER HARGA 1

                else

                // HITUNGAN NOMINAL APOTEKER HARGA 1
                {

                $hasil_hitung_fee_nominal_apoteker_harga1 = $data_persen_apoteker1['jumlah_uang'] * $jumlah;
                $query_nominal_apoteker3 = $db->query("UPDATE tbs_fee_produk SET jumlah_fee = jumlah_fee + '$hasil_hitung_fee_nominal_apoteker_harga1' WHERE nama_petugas = '$apoteker' AND kode_produk = '$kode'");

                }
                // ENDING NOMINAL APOTEKER HARGA 1

                // MULAI PERSENTASE UNTUK PETUGAS 1
                $cek_persen_petugas_harga1 = $db->query("SELECT * FROM fee_produk WHERE nama_petugas = '$id_kasir' AND kode_produk = '$kode' ");
                $data_persen_petugas_harga1 = mysqli_fetch_array($cek_persen_petugas_harga1);
                 if ($data_persen_petugas_harga1['jumlah_prosentase'] != 0 AND $data_persen_petugas_harga1['jumlah_uang'] == 0 )
                {

                $hasil_hitung_fee_persen_petugas_harga1 = $subtotal * $data_persen_petugas_harga1['jumlah_prosentase'] / 100;
                $query_persen_petugas1 = $db->query("UPDATE tbs_fee_produk SET jumlah_fee = jumlah_fee + '$hasil_hitung_fee_persen_petugas_harga1' WHERE nama_petugas = '$id_kasir' AND kode_produk = '$kode'");

                }
                // AKHIR PERSENTASE UNTUK PETUGAS 1

                else

                // START NOMINAL UNTUK PETUGAS 1
                {

                $hasil_hitung_fee_nominal_petugas_harga1 = $data_persen_petugas_harga1['jumlah_uang'] * $jumlah;
                $query_nominal_petugas1 = $db->query("UPDATE tbs_fee_produk SET jumlah_fee = jumlah_fee + '$hasil_hitung_fee_nominal_petugas_harga1' WHERE nama_petugas = '$id_kasir' AND kode_produk = '$kode'");

                }
                // ENDING UNTUK PETUGAS 1

                // MULAI PERSENTASE UNTUK ANALIS 1
                $cek_persen_petugas_harga1 = $db->query("SELECT * FROM fee_produk WHERE nama_petugas = '$analis' AND kode_produk = '$kode'");
                $data_persen_petugas_harga1 = mysqli_fetch_array($cek_persen_petugas_harga1);
                 if ($data_persen_petugas_harga1['jumlah_prosentase'] != 0 AND $data_persen_petugas_harga1['jumlah_uang'] == 0 )
                {

                $hasil_hitung_fee_persen_petugas_harga1 = $subtotal * $data_persen_petugas_harga1['jumlah_prosentase'] / 100;
                $query_persen_petugas1 = $db->query("UPDATE tbs_fee_produk SET jumlah_fee = jumlah_fee + '$hasil_hitung_fee_persen_petugas_harga1' WHERE nama_petugas = '$analis' AND kode_produk = '$kode'");

                }
                // AKHIR PERSENTASE UNTUK ANALIS 1

                else

                // START NOMINAL UNTUK ANALIS 1
                {

                $hasil_hitung_fee_nominal_petugas_harga1 = $data_persen_petugas_harga1['jumlah_uang'] * $jumlah;
                $query_nominal_petugas1 = $db->query("UPDATE tbs_fee_produk SET jumlah_fee = jumlah_fee + '$hasil_hitung_fee_nominal_petugas_harga1' WHERE nama_petugas = '$analis' AND kode_produk = '$kode'");

                }
                // ENDING UNTUK ANALIS 1


          }//dont touch me 

          else
                         
          {
                         
  
                     
          // PERHITUNGAN UNTUK FEE APOTEKER
          $cek_apoteker = $db->query("SELECT * FROM fee_produk WHERE nama_petugas = '$apoteker' AND kode_produk = '$kode'");
          $cek_fee_apoteker1 = mysqli_num_rows($cek_apoteker);
          $dataui_apoteker = mysqli_fetch_array($cek_apoteker);

          if ($cek_fee_apoteker1 > 0){
          if ($dataui_apoteker['jumlah_prosentase'] != 0 AND $dataui_apoteker['jumlah_uang'] == 0 )

          {  
          $hasil_hitung_fee_persen_apoteker = $subtotal * $dataui_apoteker['jumlah_prosentase'] / 100;

          $insert_apoteker = "INSERT INTO tbs_fee_produk 
          (session_id,no_rm,nama_petugas,kode_produk,nama_produk,jumlah_fee,tanggal,jam) VALUES 
          ('$session_id','$no_rm','$apoteker','$kode','$nama','$hasil_hitung_fee_persen_apoteker','$tanggal_sekarang','$jam_sekarang')";
          if ($db->query($insert_apoteker) === TRUE) {

            } 
          else 
                {
              echo "Error: " . $insert_apoteker . "<br>" . $db->error;
                }

            }

          else

          {

          $hasil_hitung_fee_nominal_apoteker = $dataui_apoteker['jumlah_uang'] * $jumlah;

          $insert2_apoteker = "INSERT INTO tbs_fee_produk 
          (session_id,no_rm,nama_petugas,kode_produk,nama_produk,jumlah_fee,tanggal,jam) VALUES 
          ('$session_id','$no_rm','$apoteker','$kode','$nama','$hasil_hitung_fee_nominal_apoteker','$tanggal_sekarang','$jam_sekarang')";
          if ($db->query($insert2_apoteker) === TRUE) 
          {
            
              } 
          else
                {
              echo "Error: " . $insert2_apoteker . "<br>" . $db->error;
                }
            }
          } // penutup if apoteker di harga1 > 0
          // ENDING PERHITUNGAN UNTUK FEE APOTEKER


          // PERHITUNGAN UNTUK FEE PETUGASS
          $cek_petugas = $db->query("SELECT * FROM fee_produk WHERE nama_petugas = '$id_kasir' AND kode_produk = '$kode'");
          $cek_fee_petugas1 = mysqli_num_rows($cek_petugas);
          $dataui_petugas = mysqli_fetch_array($cek_petugas);

          if ($cek_fee_petugas1 > 0) 
          {

          if ($dataui_petugas['jumlah_prosentase'] != 0 AND $dataui_petugas['jumlah_uang'] == 0 )

          {  
          $hasil_hitung_fee_persen_petugas = $subtotal * $dataui_petugas['jumlah_prosentase'] / 100;

          $insert1_petugas = "INSERT INTO tbs_fee_produk 
          (session_id,no_rm,nama_petugas,kode_produk,nama_produk,jumlah_fee,tanggal,jam) VALUES 
          ('$session_id','$no_rm','$id_kasir','$kode','$nama','$hasil_hitung_fee_persen_petugas','$tanggal_sekarang','$jam_sekarang')";
          if ($db->query($insert1_petugas) === TRUE) 
          {
            
          } 
          else 
                  {
              echo "Error: " . $insert1_petugas . "<br>" . $db->error;
                  }
          }

          else
          {
          $hasil_hitung_fee_nominal_petugas = $dataui_petugas['jumlah_uang'] * $jumlah;

          $insert2_petugas = "INSERT INTO tbs_fee_produk 
          (session_id,no_rm,nama_petugas,kode_produk,nama_produk,jumlah_fee,tanggal,jam) VALUES 
          ('$session_id','$no_rm','$id_kasir','$kode','$nama','$hasil_hitung_fee_nominal_petugas','$tanggal_sekarang','$jam_sekarang')";
          if ($db->query($insert2_petugas) === TRUE) 
          {
            
            } 
          else 
              {
              echo "Error: " . $insert2_petugas . "<br>" . $db->error;
              }
           }
          } // penutup if petugas di harga 1 > 0
          // ENDING PERHITUNGAN UNTUK FEE PETUGAS  



          // PERHITUNGAN UNTUK FEE ANALIS
          $cek_petugas = $db->query("SELECT * FROM fee_produk WHERE nama_petugas = '$analis' AND kode_produk = '$kode'");
          $cek_fee_petugas1 = mysqli_num_rows($cek_petugas);
          $dataui_petugas = mysqli_fetch_array($cek_petugas);

          if ($cek_fee_petugas1 > 0) 
          {

          if ($dataui_petugas['jumlah_prosentase'] != 0 AND $dataui_petugas['jumlah_uang'] == 0 )

          {  
          $hasil_hitung_fee_persen_petugas = $subtotal * $dataui_petugas['jumlah_prosentase'] / 100;

          $insert1_petugas = "INSERT INTO tbs_fee_produk 
          (session_id,no_rm,nama_petugas,kode_produk,nama_produk,jumlah_fee,tanggal,jam) VALUES 
          ('$session_id','$no_rm','$analis','$kode','$nama','$hasil_hitung_fee_persen_petugas','$tanggal_sekarang','$jam_sekarang')";
          if ($db->query($insert1_petugas) === TRUE) 
          {
            
          } 
          else 
                  {
              echo "Error: " . $insert1_petugas . "<br>" . $db->error;
                  }
          }

          else
          {
          $hasil_hitung_fee_nominal_petugas = $dataui_petugas['jumlah_uang'] * $jumlah;

          $insert2_petugas = "INSERT INTO tbs_fee_produk 
          (session_id,no_rm,nama_petugas,kode_produk,nama_produk,jumlah_fee,tanggal,jam) VALUES 
          ('$session_id','$no_rm','$analis','$kode','$nama','$hasil_hitung_fee_nominal_petugas','$tanggal_sekarang','$jam_sekarang')";
          if ($db->query($insert2_petugas) === TRUE) 
          {
            
            } 
          else 
              {
              echo "Error: " . $insert2_petugas . "<br>" . $db->error;
              }
           }
          } // penutup if petugas di harga 1 > 0
          // ENDING PERHITUNGAN UNTUK FEE ANALIS  



          $query6 = " INSERT INTO tbs_penjualan (session_id,kode_barang,nama_barang,jumlah_barang,satuan,harga,subtotal,tipe_barang,potongan,tax,tanggal,jam) VALUES ('$session_id','$kode','$nama','$jumlah','$satuan','$harga','$subtotal','$tipe','$potongan_tampil','$tax_persen','$tanggal_sekarang','$jam_sekarang')";

          if ($db->query($query6) === TRUE)
          { 
                         
          } 
          else 
          {

          echo "Error: " . $query6 . "<br>" . $db->error;

          }


          }                     
               

 
 
?>
<?php
  //menampilkan semua data yang ada pada tabel tbs penjualan dalam DB
                $perintah = $db->query("SELECT tp.id,tp.kode_barang,tp.satuan,tp.nama_barang,tp.jumlah_barang,tp.harga,tp.subtotal,tp.potongan,tp.tax,tp.jam,tp.tipe_barang,s.nama FROM tbs_penjualan tp INNER JOIN satuan s ON tp.satuan = s.id WHERE tp.session_id = '$session_id' AND (tp.no_reg IS NULL OR  tp.no_reg = '' ) AND tp.lab IS NULL ORDER BY tp.id DESC LIMIT 1 ");
                
                //menyimpan data sementara yang ada pada $perintah
                
                $data1 = mysqli_fetch_array($perintah);
                echo "<tr class='tr-kode-". $data1['kode_barang'] ." tr-id-". $data1['id'] ."' data-kode-barang='".$data1['kode_barang']."'>
                <td style='font-size:15px'>". $data1['kode_barang'] ."</td>
                <td style='font-size:15px;'>". $data1['nama_barang'] ."</td>";

                $kd = $db->query("SELECT f.nama_petugas, u.nama FROM tbs_fee_produk f INNER JOIN user u ON f.nama_petugas = u.id WHERE f.kode_produk = '$data1[kode_barang]' AND f.no_reg is NULL ");
                
                $kdD = $db->query("SELECT f.nama_petugas, u.nama FROM tbs_fee_produk f INNER JOIN user u ON f.nama_petugas = u.id WHERE f.kode_produk = '$data1[kode_barang]' AND f.no_reg is NULL "); 
                    
                $nu = mysqli_fetch_array($kd);

                  if ($nu['nama'] != '')
                  {

                  echo "<td style='font-size:15px;'>";
                   while($nur = mysqli_fetch_array($kdD))
                  {
                    echo $nur['nama']." ,";
                  }
                   echo "</td>";

                  }
                  else
                  {
                    echo "<td></td>";
                  }
                  
              if ($otoritas_tombol['hapus_produk_apotek'] > 0) {

                echo"<td style='font-size:15px' align='right' class='edit-jumlah' data-id='".$data1['id']."'><span id='text-jumlah-".$data1['id']."'>". $data1['jumlah_barang'] ."</span> <input type='hidden' id='input-jumlah-".$data1['id']."' value='".$data1['jumlah_barang']."' class='input_jumlah' data-id='".$data1['id']."' autofocus='' data-kode='".$data1['kode_barang']."' data-tipe='".$data1['tipe_barang']."' data-harga='".$data1['harga']."' data-satuan='".$data1['satuan']."' data-tipe='".$data1['tipe_barang']."' > </td>";
              }
              else{
                echo"<td style='font-size:15px' align='right' class='tidak_punya_otoritas' data-id='".$data1['id']."'><span id='text-jumlah-".$data1['id']."'>". $data1['jumlah_barang'] ."</span> <input type='hidden' id='input-jumlah-".$data1['id']."' value='".$data1['jumlah_barang']."' class='input_jumlah' data-id='".$data1['id']."' autofocus='' data-kode='".$data1['kode_barang']."' data-tipe='".$data1['tipe_barang']."' data-harga='".$data1['harga']."' data-satuan='".$data1['satuan']."' data-tipe='".$data1['tipe_barang']."' > </td>";
              }


                echo "<td style='font-size:15px'>". $data1['nama'] ."</td>
                <td style='font-size:15px' align='right'>". rp($data1['harga']) ."</td>
                <td style='font-size:15px' align='right'><span id='text-subtotal-".$data1['id']."'>". rp($data1['subtotal']) ."</span></td>
                <td style='font-size:15px' align='right'><span id='text-potongan-".$data1['id']."'>". rp($data1['potongan']) ."</span></td>
                <td style='font-size:15px' align='right'><span id='text-tax-".$data1['id']."'>". rp($data1['tax']) ."</span></td>";

              if ($otoritas_tombol['hapus_produk_apotek'] > 0) {

                echo "<td style='font-size:15px'> <button class='btn btn-danger btn-sm btn-hapus-tbs' id='btn-hapus-id-".$data1['id']."' data-id='". $data1['id'] ."' data-kode-barang='". $data1['kode_barang'] ."' data-barang='". $data1['nama_barang'] ."' data-subtotal='". $data1['subtotal'] ."'>Hapus</button> </td>";
              }
              else{
                echo "<td style='font-size:15px; color:red'> Tidak Ada Otoritas </td>";
              }
               

                echo "</tr>";


//Untuk Memutuskan Koneksi Ke Database
mysqli_close($db);   
    ?>


                  