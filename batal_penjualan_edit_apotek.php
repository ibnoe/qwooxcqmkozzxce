<?php session_start();
    // memasukan file db.php
    include 'db.php';
    include 'sanitasi.php';

    $no_faktur = stringdoang($_GET['no_faktur']);
    $session_id = session_id();

    // menghapus data pada tabel tbs_pembelian berdasarkan no_faktur 
    $query = $db->query("DELETE FROM tbs_penjualan WHERE no_faktur = '$no_faktur'");
    $query2 = $db->query("DELETE FROM tbs_fee_produk WHERE no_faktur = '$no_faktur'");

    // logika $query => jika $query benar maka akan menuju ke formpemebelain.php
    // dan jika salah maka akan menampilkan kalimat failed
    
    if ($query == TRUE)
    {
    echo '<META HTTP-EQUIV="Refresh" Content="0; URL=lap_penjualan.php">';
    }
    else
    {
        echo "failed";
    }

        //Untuk Memutuskan Koneksi Ke Database

        mysqli_close($db);
        
    ?>