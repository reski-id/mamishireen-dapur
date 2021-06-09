<!--video testimonial start -->
<div class="container-fluid-testimonial" id="testimonial" style="background: rgb(220 219 219);box-shadow: 5px 0px 18px 5px #f7f7f7">
    <br>
    <h4 class="text-center">Testimonials</h4>
    <div class="row" id="cardvidio">
          
        <div class="col-md-12" >
        <?php
            foreach ($query->result() as $key => $value):
            $video   = $value->video;
            $judul = $value->judul;
        ?>
                <div class="card" style="width: 15rem;" id="boxvideo">
                    <div class="embed-responsive embed-responsive-1by1">
                        <video width='300px' height='300px' id='video' controls><source src="<?php echo base_url() .$video; ?>"></video>
                    </div>
                    <p class="txtNamaVideo"><?= $judul; ?>
                    </p>
                </div>
        <?php endforeach;?>
        
        </div>
   
    </div>
    <br>
        <?php echo "<div class='col-md-12'>$halaman</div>";?>

    <nav aria-label="Page navigation example" id="paginate">
        <ul class="pagination">
          <li class="page-item">
            <a class="page-link" href="#" aria-label="Previous">
              <span aria-hidden="true">&laquo;</span>
            </a>
          </li>
          <li class="page-item"><a class="page-link" href="#">1</a></li>
          <li class="page-item"><a class="page-link" href="#">2</a></li>
          <li class="page-item"><a class="page-link" href="#">3</a></li>
          <li class="page-item">
            <a class="page-link" href="#" aria-label="Next">
              <span aria-hidden="true">&raquo;</span>
            </a>
          </li>
        </ul>
      </nav>
    <br>
</div>
<!-- testimonial end -->
<br>
