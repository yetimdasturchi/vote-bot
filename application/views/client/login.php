<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!doctype html>
<html lang="en">
  <head>
        <meta charset="utf-8" />
        <title>Tizimga kirish</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        
        <!-- App favicon -->
        <link rel="shortcut icon" href="<?php echo base_url('assets/images/favicon.ico');?>">

        <!-- Bootstrap Css -->
        <link href="<?php echo base_url('assets/css/bootstrap.min.css');?>" id="bootstrap-style" rel="stylesheet" type="text/css" />
        <!-- Icons Css -->
        <link href="<?php echo base_url('assets/css/icons.min.css');?>" rel="stylesheet" type="text/css" />
        <!-- App Css-->
        <link href="<?php echo base_url('assets/css/app.min.css');?>" id="app-style" rel="stylesheet" type="text/css" />
    </head>
    <body>
        <div class="account-pages my-5 pt-sm-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8 col-lg-6 col-xl-5">
                        <div class="card overflow-hidden">
                            <div class="bg-primary bg-soft">
                                <div class="row">
                                    <div class="col-7">
                                        <div class="text-primary p-4">
                                            <h5 class="text-primary">Xush kelibsiz!</h5>
                                            <p>Davom etish uchun tizimga kiring.</p>
                                        </div>
                                    </div>
                                    <div class="col-5 align-self-end">
                                        <img src="<?php echo base_url('assets/images/profile-img.png');?>" alt="" class="img-fluid">
                                    </div>
                                </div>
                            </div>
                            <div class="card-body pt-0"> 
                                <div class="p-2">
                                    <div class="form-horizontal">
                                        <div class="mt-3 d-grid">
                                            <button class="btn btn-primary waves-effect waves-light" id="login" type="submit">Telegram orqali kirish</button>
                                            <style type="text/css">
                                                iframe{
                                                    display: none;
                                                }
                                            </style>
                                            <script async src="https://telegram.org/js/telegram-widget.js?19" data-size="small" data-userpic="false" data-request-access="write"></script>
                                        </div>
                                    </div>
                                </div>
            
                            </div>
                        </div>
                        <div class="mt-5 text-center">
                            
                            <div>
                                <p>© <script>document.write(new Date().getFullYear())</script> <i class="mdi mdi-heart text-danger"></i> <a href="https://manu.uz">@yetimdasturchi</a></p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <!-- JAVASCRIPT -->
        <script src="<?php echo base_url('assets/libs/jquery.min.js');?>"></script>
        <script src="<?php echo base_url('assets/libs/bootstrap.bundle.min.js');?>"></script>
        <script src="<?php echo base_url('assets/libs/metisMenu.min.js');?>"></script>
        <script src="<?php echo base_url('assets/libs/simplebar.min.js');?>"></script>
        <script src="<?php echo base_url('assets/libs/waves.min.js');?>"></script>
        <script src="<?php echo base_url('assets/libs/jquery.growl.js');?>"></script>
        
        <?php
            $token = ( isset($GLOBALS['system_config']['bot_token']) ? $GLOBALS['system_config']['bot_token'] : '' );
            $bot_id = explode(':', $token);
            $bot_id = ( !empty($bot_id[0]) ) ? $bot_id[0] : '';
        ?>

        <script type="text/javascript">
            setTimeout(()=>{
                document.getElementById('login').addEventListener("click", () => {
                  window.Telegram.Login.auth(
                        { bot_id: '<?php echo $bot_id;?>', request_access: true }, (data) => {
                            if (!data){
                                $.growl.error({ title:"", message: "<?php echo lang('login_error');?>", location: "tc" });
                            }else{
                                fetch("<?php echo base_url('client/login')?>", {
                                    method: "POST",
                                    headers: {
                                        "Content-Type": "application/x-www-form-urlencoded"
                                    },
                                    body: new URLSearchParams(data)
                                }).then((response) => response.json()).then( (res) => { 
                                    if (res.status) {
                                        location.reload();
                                    }else {
                                        $.growl.error({ title:"", message: res.message, location: "tc" });
                                    }
                                });
                            }
                        }
                    );
                });
            }, 100);
        </script>
    </body>
</html>