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
    <link rel="stylesheet" href="<?php echo base_url('assets/contest/main.css?v=12');?>" />
    <title><?php echo lang('contest_nominations');?></title>

    <script src="https://telegram.org/js/telegram-web-app.js"></script>

    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm"
      crossorigin="anonymous"
    ></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js" integrity="sha512-3gJwYpMe3QewGELv8k/BX9vcqhryRdzRMxVfq6ngyWXwo03GFEzjsUm8Q7RZcHPHksttq7/GFoxjCVUjkjvPdw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script src="<?php echo base_url('assets/contest/js/app.js');?>"></script>

  </head>
  <body>
    <h1 class="title text-center mt-5 mb-4"><?php echo lang('contest_nominations');?></h1>
    <div class="container">
      <div class="row" style="margin-bottom: 100px;">
        <?php
          $language = getDefaultLanguage();
          foreach ($nominations as $nomination) {
            
            $name = $nomination[ 'name' ];

            if ( !empty( $nomination[ 'name_'.$language ] ) ) {
              $name = $nomination[ 'name_'.$language ];
            }
            
        ?>
        <div class="col-sm-12 col-md-6 col-xl-3 mb-3">
          <button
            data-location="<?php echo base_url('contest/' . $contest_id . '/' . $nomination['id'] . '?' . http_build_query( $this->input->get() ) );?>"
            class="btn btn-light btn-hsize d-flex justify-content-between align-items-center w-100 <?php if( in_array($nomination['id'], $votes_id['nomination']) ) echo "voted";?>"
          >
            <span><?php echo $name;?></span><img src="<?php echo base_url('assets/contest/icons/arrow-right.svg');?>" alt="arrow" class="arrow" />
          </button>
        </div>
        <?php
          }
        ?>
        
      </div>
    </div>
    <footer><img src="<?php echo base_url('assets/contest/img/logo.svg');?>" alt="logo" /></footer>
    <!-- <div class="container">.row>.col-sm-12.col-md-6.col-xl-3.mb-3*15>button.btn.btn-light.btn-hsize.w-100>img+span>{lorem}+img</div> -->

  </body>
</html>
