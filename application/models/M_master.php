<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_master extends CI_Model
{
  
  var $tabel_video        = 'video';
  var $tabel_photo        = 'photo';
  var $approval           = 'approval';
  var $approval_tipe      = 'approval_tipe';

  function ajax_failed($pesan='Failed', $stt=0)
  {
    echo json_encode(array('stt'=>$stt, 'pesan'=>$pesan));
    exit;
  }

  function ajax_permission($aksi='', $url='')
  {
    if (!check_permission('view', $aksi, $url)) {
      echo json_encode(array("stt"=>0, 'pesan'=>"Permission Denied!"));
      exit;
    }
  }

  public function add_set_fee($id='')
  {
    if (!check_permission('view', 'update', 'master/set_fee')) {
      echo json_encode(array("stt"=>0, 'pesan'=>"Permission Denied!"));
      exit;
    }
    $tbl = $this->set_fee; $pesan='';
    $max_fee = khususAngka(post('max_fee'));
    update_data($tbl, array('value_in'=>$max_fee, 'tgl_update'=>tgl_now()), array("tipe_in"=>0, "tipe_out"=>0));
    $this->db->where('tipe_in!=0 AND tipe_out!=0');
    $this->db->order_by('id_set_fee', 'ASC');
    foreach (get('set_fee')->result() as $key => $value)
    {
      $id = $value->id_set_fee;
      $post['value_in']   = khususAngka(post($id.'_in'));
      $post['value_out']  = khususAngka(post($id.'_out'));
      $post['tgl_update'] = tgl_now();
      $total = $post['value_in'] + $post['value_out'];
      if ($total > $max_fee) {
        $pesan = 'Fee tidak boleh melebihi '.format_angka($max_fee);
        break;
      }else {
        $simpan = update_data($tbl, $post, array("id_$tbl"=>$id));
        if (!$simpan) { break; }
      }
    }

    if ($simpan) {
      $this->db->trans_commit();
      $stt=1; $pesan='Data berhasil disimpan';
    }else {
      $this->db->trans_rollback();
      $stt=0; if ($pesan==''){ $pesan='Gagal Simpan, silahkan coba lagi!'; }
    }
    echo json_encode(array('stt'=>$stt, 'pesan'=>$pesan));
    exit;
  }

  function add_approval_tipe($id='')
  {
    $id_userx = get_session('id_user');
    $nama_input = "$id_userx - ".user('nama_lengkap');
    $tgl_input = tgl_now();

    $tbl     = $this->approval_tipe;
    $tbl_app = $this->approval;
    $approval_tipe = post('nama_approval_tipe');
    $post['nama_'.$tbl] = $approval_tipe;
    $post['ket']    = post('ket');
    $post['status'] = 1;

    if (count(post('id_user', 1))==0)          { $this->ajax_failed('User Wajib diisi!'); }
    if (count(post('id_user_approval', 1))==0) { $this->ajax_failed('User Approval Wajib diisi!'); }
    $id_usernya          = array_unique(json_decode(html_entity_decode($this->input->post('id_user'))));
    $id_user_approvalnya = array_unique(json_decode(html_entity_decode($this->input->post('id_user_approvalnya'))));
    // log_r($id_usernya);
    if ($id!='') { //jika edit & dihapus item
      if (!empty($id_user_approvalnya)) {
        $this->db->where('id_approval_tipe', $id);
        $this->db->where_not_in('id_user', $id_usernya);
        // $this->db->where_not_in('id_user_approval', $id_user_approvalnya);
        $this->db->delete($tbl_app);
      }
    }

    if ($id=='') {
      if (!check_permission('view', 'create', 'master/'.$tbl)) {
        echo json_encode(array("stt"=>0, 'pesan'=>"Permission Denied!"));
        exit;
      }
      $this->db->select('nama_'.$tbl);
      $get = get($tbl, array('nama_'.$tbl=>$approval_tipe))->row();
      if (!empty($get)) {
        echo json_encode(array("stt"=>0, 'pesan'=>"Nama Approval Tipe '$approval_tipe' sudah ada!"));
        exit;
      }
      $post['tgl_input'] = tgl_now();
      $post['input_by']  = get_session('id_user');
      $simpan = add_data($tbl, $post);
      $id_approval_tipe = $this->db->insert_id();
    }else{
      if (!check_permission('view', 'update', 'master/'.$tbl)) {
        echo json_encode(array("stt"=>0, 'pesan'=>"Permission Denied!"));
        exit;
      }
      $this->db->select('nama_'.$tbl);
      $get_old = get_field($tbl, array('id_'.$tbl=>$id))['nama_'.$tbl];
      $this->db->select('nama_'.$tbl);
      $get = get($tbl, array('nama_'.$tbl=>$approval_tipe, "nama_$tbl !="=>$get_old))->row();
      if (!empty($get)) {
        echo json_encode(array("stt"=>0, 'pesan'=>"Nama Approval Tipe '$approval_tipe' sudah ada!"));
        exit;
      }
      $post['tgl_update'] = tgl_now();
      $post['update_by']  = get_session('id_user');
      $simpan = update_data($tbl,$post, array("id_$tbl"=>$id));
      $id_approval_tipe = $id;
    }
    if ($simpan) {
      $post1=array(); $post1_1=array();
      $post1_save=false; $i=0;
      foreach ($id_usernya as $k => $id_user) {
        foreach ($id_user_approvalnya as $key => $value) {
          $id_user_approval = $value;
          if ($id=='') {
            $post1[$i]['id_approval_tipe'] = $id_approval_tipe;
            $post1[$i]['id_user']          = $id_user;
            $post1[$i]['id_user_approval'] = $id_user_approval;
            $post1[$i]['tgl_input']  = $tgl_input;
            $post1[$i]['input_by']   = $nama_input;
            $post1_save=true;
          }else {
            $this->db->select('id_approval');
            $cek_app = get_field($tbl_app, array('id_approval_tipe'=>$id_approval_tipe, 'id_user'=>$id_user, 'id_user_approval'=>$id_user_approval));
            if (empty($cek_app)) { //jika tidak ada
              $post1[$i]['id_approval_tipe'] = $id_approval_tipe;
              $post1[$i]['id_user']          = $id_user;
              $post1[$i]['id_user_approval'] = $id_user_approval;
              $post1[$i]['tgl_input']  = $tgl_input;
              $post1[$i]['input_by']   = $nama_input;
              $post1_save=true;
            }else {
              $post1_1[$i]['id_approval']      = $cek_app['id_approval'];
              $post1_1[$i]['id_approval_tipe'] = $id_approval_tipe;
              $post1_1[$i]['id_user']          = $id_user;
              $post1_1[$i]['id_user_approval'] = $id_user_approval;
              $post1_1[$i]['tgl_update']  = $tgl_input;
              $post1_1[$i]['update_by']   = $nama_input;
              $post1_save=true;
            }
          }
          $i++;
        }
      }
      $simpan=false;
      if ($post1_save) {
        if ($id=='') {
          if (!empty($post1)) { $simpan = add_batch($tbl_app, $post1); }
        }else{
          if (!empty($post1)) { $simpan = add_batch($tbl_app, $post1); }else{ $simpan = true; }
          if ($simpan) {
            if (!empty($post1_1)) {
              $simpan = update_batch($tbl_app, $post1_1, "id_approval");
            }else {
              $simpan = true;
            }
          }
        }
      }
    }

    if ($simpan) {
      $this->db->trans_commit();
      $stt=1; $pesan='Data berhasil disimpan';
    }else {
      $this->db->trans_rollback();
      $stt=0; $pesan='Gagal Simpan, silahkan coba lagi!';
    }
    echo json_encode(array('stt'=>$stt, 'pesan'=>$pesan));
    exit;
  }

  function add_approval($id_user='', $id='')
  {
    $id_userx = get_session('id_user');
    $nama_input = "$id_userx - ".user('nama_lengkap');
    // log_r($nama_input);
    $tgl_input = tgl_now();
    $tbl = 'approval';

    if ($id=='') {
      $this->ajax_permission('create', 'user_management/data_user');
      $id_approval_tipe = post('id_approval_tipe');
    }else {
      $this->ajax_permission('update', 'user_management/data_user');
      $id_approval_tipe = $id;
    }

    if ($id_approval_tipe=='')    { $this->ajax_failed('Tipe Approval Wajib diisi!'); }
    if (count(post('id_user_approval', 1))==0) { $this->ajax_failed('User Approval Wajib diisi!'); }

    $id_user_approvalnya = array_unique(json_decode(html_entity_decode($this->input->post('id_user_approvalnya'))));
    if ($id!='') { //jika edit & dihapus item
      if (!empty($id_user_approvalnya)) {
        $this->db->where('id_approval_tipe', $id_approval_tipe);
        $this->db->where('id_user', $id_user);
        $this->db->where_not_in('id_user_approval', $id_user_approvalnya);
        $this->db->delete($tbl);
      }
    }

    $post=array(); $post_1=array();
    $post_save=false;
    foreach ($id_user_approvalnya as $key => $value) {
      $id_user_approval = $value;
      if ($id=='') {
        $post[$key]['id_approval_tipe'] = $id_approval_tipe;
        $post[$key]['id_user']          = $id_user;
        $post[$key]['id_user_approval'] = $id_user_approval;
        $post[$key]['tgl_input']  = $tgl_input;
        $post[$key]['input_by']   = $nama_input;
        $post_save=true;
      }else {
        $this->db->select('id_approval');
        $cek_app = get_field($tbl, array('id_approval_tipe'=>$id_approval_tipe, 'id_user'=>$id_user, 'id_user_approval'=>$id_user_approval));
        if (empty($cek_app)) { //jika tidak ada
          $post[$key]['id_approval_tipe'] = $id_approval_tipe;
          $post[$key]['id_user']          = $id_user;
          $post[$key]['id_user_approval'] = $id_user_approval;
          $post[$key]['tgl_input']  = $tgl_input;
          $post[$key]['input_by']   = $nama_input;
          $post_save=true;
        }else {
          $post_1[$key]['id_approval']      = $cek_app['id_approval'];
          $post_1[$key]['id_approval_tipe'] = $id_approval_tipe;
          $post_1[$key]['id_user']          = $id_user;
          $post_1[$key]['id_user_approval'] = $id_user_approval;
          $post_1[$key]['tgl_update']  = $tgl_input;
          $post_1[$key]['update_by']   = $nama_input;
          $post_save=true;
        }
      }
    }
    $simpan=false;
    if ($post_save) {
      if ($id=='') {
        if (!empty($post)) { $simpan = add_batch($tbl, $post); }
      }else{
        if (!empty($post)) { $simpan = add_batch($tbl, $post); }else{ $simpan = true; }
        if ($simpan) {
          if (!empty($post_1)) {
            $simpan = update_batch($tbl, $post_1, "id_approval");
          }else {
            $simpan = true;
          }
        }
      }
    }
    if ($simpan) {
      $this->db->trans_commit();
      echo json_encode(array('stt'=>1, 'pesan'=>''));
      exit;
    }else{
      $this->db->trans_rollback();
      $this->ajax_failed('Gagal, Silahkan dicoba lagi!');
    }
  }

  function approval_hapus($id_user='')
  {
    $this->ajax_permission('delete', 'user_management/data_user');
    $tbl = 'approval';
    $this->db->trans_begin();
    $hapus = delete_data($tbl, array('id_approval_tipe'=>decode(post('id')), 'id_user'=>decode($id_user)));
    if ($hapus) {
      $this->db->trans_commit();
      echo json_encode(array('stt'=>1, 'pesan'=>''));
      exit;
    }else{
      $this->db->trans_rollback();
      $this->ajax_failed('Gagal, Silahkan dicoba lagi!');
    }
  }



//START VIDEO
public function video_simpan($id='')
{
  $this->ajax_permission('create', 'master/video');
  $id_user = get_session('id_user');
  $nama_input = "$id_user - ".user('nama_lengkap');
  $tgl_now = tgl_now();
  $tbl = $this->tabel_video;
  $judul = post('judul');
  $post['video']  = post('video');
  $videolama = '';
  if ($id!='') { //jika edit
    $this->db->select("video");
    $data_lama = get_field($tbl, array("id_$tbl"=>$id));
    $videolama = $data_lama['video'];
  }
  $path = 'assets/video';
  createPath($path, 0777);
  upload_config($path,'20','avi|flv|wmv|mp3|mp4');

  $video = upload_file('video',$path,'ajax', $videolama, 1);
  if (!empty($video['pesan'])) {
    $this->ajax_failed($video['pesan']);
  }else{
    $post['video'] = $video;
  }

  $post['judul'] = $judul;
  


  if ($id=='') {
    $post['tgl_input'] = $tgl_now;
    $post['status']   = 1;
    $post['input_by'] = $nama_input;
    $simpan = add_data($tbl,$post);
  }else{
    $post['status']    = post('status');
    $post['tgl_update'] = $tgl_now;
    $post['update_by'] = $nama_input;
    $simpan = update_data($tbl,$post, array("id_$tbl"=>$id));
  }
  if ($simpan) {
    $this->db->trans_commit();
    $stt=1; $pesan='Data berhasil disimpan';
  }else {
    $this->db->trans_rollback();
    $stt=0; $pesan='Gagal Simpan, silahkan coba lagi!';
  }
  echo json_encode(array('stt'=>$stt, 'pesan'=>$pesan));
  exit;
}

function video_up_status($id='')
  {
    $stt = post('id');
    if (!in_array($stt, array(0,1))) { $this->ajax_failed(); }
    $this->ajax_permission('update', 'master/video');
    $tbl = $this->tabel_video;

    $this->db->select("id_$tbl");
    $get_data = get_field($tbl, array("id_$tbl"=>$id));
    if (empty($get_data)) { $this->ajax_failed(); }

    $id_user = get_session('id_user');
    $nama_input = "$id_user - ".user('nama_lengkap');

    $where = array("id_$tbl"=>$id);
    $post  = array('status'=>$stt, 'tgl_update'=>tgl_now(), 'update_by'=>$nama_input);
    $simpan = update_data($tbl, $post, $where);
    if ($simpan) {
      $this->db->trans_commit();
      echo json_encode(array('stt'=>1, 'pesan'=>''));
      exit;
    }else {
      $this->db->trans_rollback();
      $this->ajax_failed('Gagal, Silahkan dicoba lagi!');
    }
}
  //END VIDEO


//START PHOTO
public function photo_simpan($id='')
{
  $this->ajax_permission('create', 'master/photo');
  $id_user = get_session('id_user');
  $nama_input = "$id_user - ".user('nama_lengkap');
  $tgl_now = tgl_now();
  $tbl = $this->tabel_photo;
  $post['nama']  = post('nama');

  if ($id!='') { //jika edit
    $this->db->select("photo");
    $data_lama = get_field($tbl, array("id_$tbl"=>$id));
    $old_foto1=$data_lama['photo'];
  }
  $tgl = tgl_now('tgl');
  $path_tgl = tgl_format($tgl,'Y').'/'.tgl_format($tgl,'m');
  $path  = get_path_img('custom', "photo/$path_tgl");
  createPath($path, 0777);
  upload_config($path,'5','jpeg|jpg|png|gif|bmp');

  $foto = upload_file('photo',$path,'ajax', $old_foto1, 1);
  if (!empty($foto['pesan'])) {
    $this->ajax_failed($foto['pesan']);
  }else{
    $stt=1; $post['photo'] = $foto;
    $cek_resize = resizeImage($foto,$path);
    if ($cek_resize!=1) { $this->ajax_failed('Maaf, Upload Foto Gagal!'); }
  }

  if ($id=='') {
    $post['tgl_input'] = $tgl_now;
    $post['status']   = 1;
    $post['input_by'] = $nama_input;
    $simpan = add_data($tbl,$post);
  }else{
    $post['status']    = post('status');
    $post['tgl_update'] = $tgl_now;
    $post['update_by'] = $nama_input;
    $simpan = update_data($tbl,$post, array("id_$tbl"=>$id));
  }
  if ($simpan) {
    $this->db->trans_commit();
    $stt=1; $pesan='Data berhasil disimpan';
  }else {
    $this->db->trans_rollback();
    $stt=0; $pesan='Gagal Simpan, silahkan coba lagi!';
  }
  echo json_encode(array('stt'=>$stt, 'pesan'=>$pesan));
  exit;
}

function photo_up_status($id='')
  {
    $stt = post('id');
    if (!in_array($stt, array(0,1))) { $this->ajax_failed(); }
    $this->ajax_permission('update', 'master/photo');
    $tbl = $this->tabel_photo;

    $this->db->select("id_$tbl");
    $get_data = get_field($tbl, array("id_$tbl"=>$id));
    if (empty($get_data)) { $this->ajax_failed(); }

    $id_user = get_session('id_user');
    $nama_input = "$id_user - ".user('nama_lengkap');

    $where = array("id_$tbl"=>$id);
    $post  = array('status'=>$stt, 'tgl_update'=>tgl_now(), 'update_by'=>$nama_input);
    $simpan = update_data($tbl, $post, $where);
    if ($simpan) {
      $this->db->trans_commit();
      echo json_encode(array('stt'=>1, 'pesan'=>''));
      exit;
    }else {
      $this->db->trans_rollback();
      $this->ajax_failed('Gagal, Silahkan dicoba lagi!');
    }
  }
  //END PHOTO

}
?>
