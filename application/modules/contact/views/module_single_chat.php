<?php
    if ( !empty( $first_name ) ) {
        $name =  $first_name . ' ' . $last_name;
    }else if ( !empty( $username ) ) {
        $name = "@" . $username;
    }else{
        $name = lang('chat_undefined'); 
    }
?>
<li>
    <a href="javascript: void(0);" data-chat="<?php echo base_url( $module_name . '/get_chat_window/' . $chat_id );?>">
        <div class="d-flex">
            <div class="flex-shrink-0 align-self-center me-3">
                <div class="avatar-xs">
                    <span class="avatar-title rounded-circle bg-primary bg-soft text-primary"><?php echo mb_substr($name, 0, 1);?></span>
                </div>
            </div>
            <div class="flex-grow-1 overflow-hidden">
                <h5 class="text-truncate font-size-14 mb-1"><?php echo $name;?></h5>
                <p class="text-truncate mb-0">
                    <?php
                        if ( !empty( $contact_message ) ) {
                            echo character_limiter($contact_message) . '...';
                        }else if ( !empty( $contact_file ) ) {
                            echo "<i class=\"bx bx-file\"></i>" . lang( 'chat_file' );
                        }else{
                            echo lang('chat_undefined');
                        }
                    ?>
                </p>
            </div>
            <?php
                if($unread > 0){
            ?>
                <div><span class="badge bg-info rounded-pill"><?php echo $unread;?></span></div>
            <?php
                }else{
            ?>
                <div class="font-size-11">24 min</div>
            <?php
                }
            ?>
        </div>
    </a>
</li>