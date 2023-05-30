<!--?php
var_dump($this->session->userdata());
die;
?-->
<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <link rel="icon" type="image/x-icon" href="<?= base_url() ?>assets/images/favicon.ico">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
  <link href="<?= base_url() ?>assets/css/sb-admin-2.css" rel="stylesheet">
  <link href="<?= base_url() ?>assets/DataTables-1.10.18/css/dataTables.bootstrap4.min.css" rel="stylesheet">
  <link href="<?= base_url() ?>assets/jquery-ui-1.12.1.custom/jquery-ui.min.css" rel="stylesheet">
  <link href="<?= base_url() ?>assets/css/style.css" rel="stylesheet">
  <link href="<?= base_url() ?>assets/css/style.css" rel="stylesheet">
  <link href="<?= base_url() ?>assets/css/filterCetak.css" rel="stylesheet">
  <link href="<?= base_url() ?>assets/datepicker-range/css/datepicker.css" rel="stylesheet">
  
  <title>Kasir</title>
  <style>
    @media print{
      #wrapper {
        display:none;
      }
      .modal-footer, .modal-header {
        display:none;
      }
      title{
        display: none;
      }
    }
  </style>
</head>

<body id="page-top">
  <div id="wrapper">

    <?php $this->load->view('component/sidebar')?>

    <div id="content-wrapper" class="d-flex flex-column">
      <div id="content">

        <?php $this->load->view('component/header')?>
        
        <div class="container-fluid">
            <div class="header">
                <form id="filter" action="<?= base_url('option/fetch_laporan_penjualan') ?>">
                    <div class="form-group">
                        <label for="tanggal_filter" class="col-form-label">Tanggal</label>
                        <input id="tanggal_filter" name="tanggal_filter" type="text" class="form-control">
                    </div>
                    <div class="form-group">
                        <button type="submit" class=" btn btn-primary">Filter</button>
                        <a href="#" data-type="excel" class="btn btn-primary previewBtn"> <i class="fa fa-download" style="margin-right: 3px;"></i>Download Excel</a>
                    </div>
                </form>
            </div>
            <div class="content-print-wrapper">
                <div id="loader-container">
                    <!-- <div class="loader" style="font-size:16px;color:red;">
                        Mohon Tunggu...
                    </div> -->
                    <div class="loader">
                        <img src="https://media.giphy.com/media/sxJ1nCeUoNSfe/giphy.gif" width="100px" alt="" srcset="">
                    </div>
                </div>
                <div id="print-wrapper">
                </div>
            </div>
    
        </div>
      </div>
      <?php $this->load->view('component/footer')?>
    </div>
  </div>

  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <script src="<?= base_url() ?>assets/jquery/jquery-3.2.1.min.js"></script>
  <script src="<?= base_url() ?>assets/bootstrap-4.1.3/js/bootstrap.min.js"></script>
  <script src="<?= base_url() ?>assets/js/sb-admin-2.js"></script>
  <script src="<?= base_url() ?>assets/DataTables-1.10.18/js/jquery.dataTables.min.js"></script>
  <script src="<?= base_url() ?>assets/datepicker-range/js/fetcha.js"></script>
  <script src="<?= base_url() ?>assets/datepicker-range/js/datepicker.min.js"></script>
  <script src="<?= base_url() ?>assets/jquery-ui-1.12.1.custom/jquery-ui.min.js"></script>
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
  <script src="<?= base_url() ?>assets/js/filterCetak.js"></script>

  <script>
    var hdpkr = new HotelDatepicker(document.getElementById('tanggal_filter'));
  </script>
  
  
      
</body>

</html>
