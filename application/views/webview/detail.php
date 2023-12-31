<?php
$language = getDefaultLanguage();

$name = $member[ 'name' ];

if ( !empty( $member[ 'name_'.$language ] ) ) {
  $name = $member[ 'name_'.$language ];
}

$description = $member[ 'description' ];

if ( !empty( $member[ 'description_'.$language ] ) ) {
  $description = $member[ 'description_'.$language ];
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
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9"
      crossorigin="anonymous"
    />
    <link rel="stylesheet" href="<?php echo base_url('assets/contest/jquery.growl.css');?>" />
    <link rel="stylesheet" href="<?php echo base_url('assets/contest/main.css');?>" />
    <title><?php echo $name;?></title>
    <script src="https://telegram.org/js/telegram-web-app.js"></script>

    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm"
      crossorigin="anonymous"
    ></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js" integrity="sha512-3gJwYpMe3QewGELv8k/BX9vcqhryRdzRMxVfq6ngyWXwo03GFEzjsUm8Q7RZcHPHksttq7/GFoxjCVUjkjvPdw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script src="<?php echo base_url('assets/contest/js/jquery.growl.js');?>"></script>

    <script src="<?php echo base_url('assets/contest/js/appa.js');?>"></script>
  </head>
  <body class="white">
    <div class="container d-flex flex-column justify-content-around align-items-center content pb-3">
      <img class="detail-brand" src="<?php echo $image;?>" alt="brand" />
      <h1 class="title-black text-center"><?php echo $name;?></h1>
      <div class="d-flex justify-content-around container-btn">
      <?php
        if ( !empty( $member[ 'buttons' ] ) ) {
          $member[ 'buttons' ] = json_decode( $member[ 'buttons' ], TRUE );
          $member[ 'buttons' ] = array_values( array_filter($member[ 'buttons' ],function($v,$k) use ( $language ){
            return ( $language == $v[ 'language' ] );
          }, ARRAY_FILTER_USE_BOTH));

          $x = 0;
          foreach ($member[ 'buttons' ] as $button) {
            if ( $button['type'] == 'url' ) {
              if ( $x == 2 ) {
                $x = 0;
                echo "</div><div class=\"d-flex justify-content-around container-btn\">";
              }
        ?>
          <button class="btn btn-danger btn-red" data-openurl="<?php echo $button['value'];?>" ><?php echo $button['name'];?></button>
        <?php
              $x++;
            }
          }
        }
      ?>
      </div>
      <?php
        if ( !in_array($nomination_id, $votes_id['nomination']) ) :
          if ( empty( $contest_query['expire'] ) || $contest_query['expire'] > time() && !in_array($member['id'], $votes_id['member']) ) {
        ?>
          <button class="btn btn-success btn-vote mt-2" data-vote data-contest="<?php echo $contest_id;?>" data-nomination="<?php echo $nomination_id;?>" data-member="<?php echo $member['id'];?>"><?php echo lang('contest_vote');?></button>
        <?php
          }
        endif;
        ?>

        <div class="growl growl-notice growl-medium <?php if( !in_array($member['id'], $votes_id['member']) ) echo "d-none";?>">
          <div class="growl-close"></div>
          <div class="growl-title"></div>
          <div class="growl-message text-center vote-success-message"><?php echo lang('contest_voted_successfully_check');?></div>
        </div>
      <button data-location="<?php echo base_url('contest/' . $contest_id . '/' . $nomination_id . '?' . http_build_query( $this->input->get() ) );?>" class="btn btn-danger btn-red d-none back-button"><?php echo lang('contest_back');?></button>
      <div class="container d-flex flex-column justify-content-around align-items-center content mt-4 pb-3">
        <?php echo str_replace("\\", "", stripslashes( $description ));?>
      </div>
    </div>
    <footer>
      <div class="footer-container">
        <img class="footer-logo" src="<?php echo base_url('assets/contest/img/logo.svg');?>" alt="logo" />
      </div>
    </footer>    
  </body>
</html>
