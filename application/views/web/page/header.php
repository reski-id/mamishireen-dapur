

<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="description" content="<?php echo web('meta_deskripsi'); ?>">
    <meta name="keywords" content="<?php echo web('meta_keyword'); ?>">
    <meta name="author" content="<?php echo web('meta_author'); ?>">
    <base href="<?php echo base_url(); ?>">
    <noscript><meta http-equiv="refresh" content="0;url=web/noscript.html"></noscript>
    <link rel="icon" href="<?php echo web('favicon'); ?>">
    <link href="assets/fonts/google/css.css?family=Rubik:300,400,500,600%7CIBM+Plex+Sans:300,400,500,600,700" rel="stylesheet">
    <!-- BEGIN: Theme CSS-->

    <link rel="stylesheet" type="text/css" href="assets/css/costume.css">
    <link rel="stylesheet" type="text/css" href="assets/vendors/css/charts/apexcharts.css">
    <link rel="stylesheet" type="text/css" href="assets/vendors/css/extensions/swiper.min.css">


    <link rel="stylesheet" type="text/css" href="assets/vendors/css/vendors.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap-extended.css">
    <link rel="stylesheet" type="text/css" href="assets/css/colors.css">
    <link rel="stylesheet" type="text/css" href="assets/css/components.css">
    <link rel="stylesheet" type="text/css" href="assets/plugin/lightbox/lightbox.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Courgette&display=swap" rel="stylesheet">      
    <link rel="stylesheet" href="css/lightbox.min.css">

    <title>Dapur Mami Shireen</title>
  </head>
  <body>
    <nav class="navbar navbar-expand-lg navbar-light fixed-top" id="mainNav" >
      <img src="<?php echo base_url() . 'assets/images/logo/dms.png'; ?>" id="gambarlogo" alt="DapurMamiShireen" width="200px" style="text-align: center !important;">
    </nav>
    
    <div class="container-fluid">
    <div class="text-center mb-4 mt-4">
        <br>
    <div class="container-fluid" id="home">
        <div class="row">
            <div class="col-md-12 mb-4 col-12">
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mt-4 col-sm-6">
                <div class="text-center">
                <h2 class="text-center mb-4">
                    <br>
                   <h2 class="cinnamon mb-3">Cinnamon <br> roll mix</h2> 
                <h6 class="text-center mb-3">
                <div class="rasa">Ada tiga rasa di dalamnya </div></h6>
                <br>
                <div class="pasanbtn">
                <button type="button" class="btn btn-outline-dark" data-toggle="modal" data-target="#exampleModal" id="btnPesan">
                    Pesan
                  </button>
                 </div>
            </div>
            </div>
            
            <div class="col-md-6 mt-3 col-xs-12" id="gambarcinamon">
                <img alt=" Cinnamon roll mix" width="80%" src="<?php echo base_url() . 'assets/images/cinnamonrm.jpg'; ?>" class="img-fluid"/>
            </div>
        </div>
    </div>
</section>
<br>

<!-- modal tombol pesan start -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
<div class="modal-body">
<div class="container-fluid">
<div class="row">
    <div class="col-md-12">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title text-center" id="exampleModalLabel" > <img src="<?php echo base_url() . 'assets/images/logo/dms.png'; ?>"  width="45%"  class="img-fluid"> <br></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                    <div class="infobtn"> 
                        <button type="button" class="btn btn-outline-info btn-lg btn-block">
                        <a class="text-success" href="https://shopee.co.id/dapurmamishireen_4?smtt=0.0.9" target="_blank" rel="noopener noreferrer" style="text-decoration:none">Shopee</a>
                        </button>
                        <button type="button" class="btn btn-outline-info btn-lg btn-block">
                        <a  class="text-success" href="https://api.whatsapp.com/send/?phone=%2B6289678904086&text&app_absent=0" target="_blank" style="text-decoration:none"> Order via WA kota Jakarta</a>
                        </button>
                        <button type="button" class="btn btn-outline-info btn-lg btn-block">
                        <a class="text-success" href="https://api.whatsapp.com/send/?phone=%2B62895336311429&text&app_absent=0" target="_blank" style="text-decoration:none"> Order via WA kota Tangerang dan Depok</a>
                        </button>
                        <button type="button" class="btn btn-outline-info btn-lg btn-block">
                        <a class="text-success" href="https://api.whatsapp.com/send/?phone=%2B6289678904081&text&app_absent=0" target="_blank" style="text-decoration:none"> Order via WA kota Bogor dan Bekasi</a>
                        </button>
                        <button type="button" class="btn btn-outline-info btn-lg btn-block">
                        <a class="text-success" href="https://www.facebook.com/DapurMamiShireen" target="_blank" style="text-decoration:none"> Order via WA kota Jakarta</a>
                        </button>
                    </div>
                </div>
                <div class="modal-footer">
                </div>
            </div>
            </div>
    </div>
</div>
</div>
</div>
</div>
<!-- modal end -->
