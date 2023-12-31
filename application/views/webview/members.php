<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9"
      crossorigin="anonymous"
    />
    <link rel="stylesheet" href="<?php echo base_url('assets/contest/main.css');?>" />
    <title><?php echo lang('contest_members');?></title>

    <script src="https://telegram.org/js/telegram-web-app.js"></script>

    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm"
      crossorigin="anonymous"
    ></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js" integrity="sha512-3gJwYpMe3QewGELv8k/BX9vcqhryRdzRMxVfq6ngyWXwo03GFEzjsUm8Q7RZcHPHksttq7/GFoxjCVUjkjvPdw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script src="<?php echo base_url('assets/contest/js/jquery.growl.js');?>"></script>

    <script src="<?php echo base_url('assets/contest/js/app.js');?>"></script>

  </head>
  <body>
    <h1 class="title text-center mt-5 mb-4"><?php echo lang('contest_members');?></h1>
    <div class="container">
      <div class="row" style="margin-bottom: 100px;">
        <?php
          $language = getDefaultLanguage();
          foreach ($members as $member) {
            $name = $member[ 'name' ];

            if ( !empty( $member[ 'name_'.$language ] ) ) {
              $name = $member[ 'name_'.$language ];
            }

            $image = base_url( 'uploads/members/noimage.png' );

            if ( !empty( $member['image'] ) ) {
              if ( filter_var($member['image'], FILTER_VALIDATE_URL) ){
                $image = $member['image'];
              }else if( file_exists( FCPATH . 'uploads/members/' . $member['image'] ) ) {
                $image = base_url( 'uploads/members/' . $member['image'] );
              }
            }
            
        ?>
        <div class="col-sm-12 col-md-6 col-xl-3 mb-4">
          <button data-ismember="true" data-location="<?php echo base_url('contest/' . $member['contest'] . '/' . $nomination_id. '/' .$member['id'] . '?' . http_build_query( $this->input->get() ) );?>" class="btn btn-light d-flex justify-content-between align-item-center w-100 vertical-center <?php if( in_array( $member['id'], $votes_id['member'] ) && ( array_key_exists( $member['id'], $votes_id['thismember'] ) && $votes_id['thismember'][ $member['id'] ] == $nomination_id ) ) echo "voted";?>">
            <div class="vertical-center text-left">
              <img src="<?php echo $image;?>" alt="brand" class="rounded" width="50px" heigth="50px" /><span><?php echo $name;?></span
              >
            </div>
            <img src="<?php echo base_url('assets/contest/icons/arrow-right.svg');?>" alt="arrow" class="arrow ms-auto" />
          </button>
        </div>
        <?php
          }
        ?>
      </div>
    </div>
    <button data-location="<?php echo base_url('contest/' . $member['contest'] . '?' . http_build_query( $this->input->get() ) );?>" class="btn btn-danger btn-red d-none back-button"><?php echo lang('contest_back');?></button>
    <footer><img src="<?php echo base_url('assets/contest/img/logo.svg');?>" alt="logo" /></footer>
    <!-- <div class="container">.row>.col-sm-12.col-md-6.col-xl-3.mb-3*15>button.btn.btn-light.btn-hsize.w-100>img+span>{lorem}+img</div> -->
    
  </body>
</html>
