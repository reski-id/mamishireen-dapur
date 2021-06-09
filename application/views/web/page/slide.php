<!-- foto kue tampilan deskop start -->
<div class="container-fluid" id="fotokue">
        <?php
            $this->db->select('nama, photo');
            $this->db->order_by('id_photo', 'DESC');
            // $this->db->limit('3');
            $get_photo = get('photo', array('status'=>1));
        ?>
    <div class="row">
        <?php foreach ($get_photo->result() as $key => $value):
        $img   = $value->foto_photo;
        $img_default = 'img/photo/default.png';
        $photo = $value->photo; ?>
        <div class="col-md-3">
            <a class="example-image-link" href="<?php echo base_url() . $photo; ?>" data-lightbox="example-set">
                <img src="<?php echo base_url() . $photo; ?>" class="img-fluid" class="img-thumbnail" style="width:500px !important"></a>
        </div>
        <?php endforeach; ?>
         
    </div>
</div>
<!-- foto kue end -->
<br>

<div class="container-fluid">
<!-- images carousel untuk mobile start -->
<div id="carouselMobile" class="carousel slide" data-ride="carousel">
    <ol class="carousel-indicators">
      <li data-target="#carouselMobile" data-slide-to="0" class="active"></li>
      <li data-target="#carouselMobile" data-slide-to="1"></li>
      <li data-target="#carouselMobile" data-slide-to="2"></li>
    </ol>
    <div class="carousel-inner">

    <?php foreach ($get_photo->result() as $key => $value):
         $img   = $value->foto_photo;
         $img_default = 'img/photo/default.png';
         $photo = $value->photo; ?>
         
      <div class="carousel-item active <?= ($key==0) ? 'active':''; ?>">
        <img src="<?php echo base_url() . $photo; ?>" class="d-block w-100">
        <div class="carousel-caption d-none d-md-block">
          <h5>Gambar</h5>
          <p>Keterangan Gambar</p>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <a class="carousel-control-prev" href="#carouselMobile" role="button" data-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#carouselMobile" role="button" data-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="sr-only">Next</span>
    </a>
  </div>
  <br>
  </div>
<!-- images carousel untuk mobile end -->