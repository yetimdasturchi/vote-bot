<?php

class TelegramException extends Exception{}

class Telegram {

    private $url;
    private $bot_url = "/bot";
    private $file_url = "/file";
    private $token = null;
    private $error_reporting = true;
    private $parse_mode = 'html';
    private $action = '';
    private $settings = [];

    private $chat_id;
    public $result;
    private $request;
    private $channel_id;
    private $reply_markup;
    
    function __construct( $set = [] ){

        $this->url = isset( $GLOBALS['system_config']['telegram_api_url'] ) ? $GLOBALS['system_config']['telegram_api_url']  : "https://api.telegram.org";
        $this->token = isset( $GLOBALS['system_config']['bot_token'] ) ? $GLOBALS['system_config']['bot_token']  : "";

        $this->settings = $set;
        if ( count( $this->settings ) > 0 ) {
            foreach( $set as $key => $val ){
                $this->$key = $val;
            }
        };
        
        if ( is_null( $this->token ) ) {
           throw new TelegramException( 'Required "token" key not supplied' );
        }
    }

    public function reset($v = false)
    {
        if ($v) {
            $this->$v = null;
            return $this;
        }

        $this->chat_id = null;
        $this->result = null;
        $this->request = null;
        $this->channel_id = null;
        $this->reply_markup = null;

        return $this;
    }

    public function set_webhook($url, $certificate = null)
    {
        if (filter_var($url, FILTER_VALIDATE_URL) === false) {
            if ($this->error_reporting) {
                throw new TelegramException('Invalid URL provided');
            }
            return false;
        }
        if (parse_url($url, PHP_URL_SCHEME) !== 'https') {
            if ($this->error_reporting) {
                throw new TelegramException('Invalid URL, it should be a HTTPS url.');
            }
            return false;
        }
        $this->result = $this->request('setWebhook', compact('url', 'certificate'));
        return $this;
    }

    public function set_chatId( $chat_id='' ){
        $this->chat_id = $chat_id;
        return $this;    
    }

    public function set_inlineKeyboard($arr=[])
    {
        $this->reply_markup['inline_keyboard'] = $arr;
        return $this;
    }

    public function set_replyKeyboard($arr=[], $placeholder = "", $remove_keyboard = false, $resize_keyboard = true)
    {
        $this->reply_markup['keyboard'] = $arr;
        $this->reply_markup['resize_keyboard'] = $resize_keyboard;
        $this->reply_markup['remove_keyboard'] = $remove_keyboard;
        $this->reply_markup['input_field_placeholder'] = $placeholder;
        return $this;
    }

    public function remove_replyKeyboard()
    {
        unset( $this->reply_markup['inline_keyboard'] );
        return $this;
    }

    public function remove_inlineKeyboard()
    {
        unset( $this->reply_markup['keyboard'] );
        unset( $this->reply_markup['resize_keyboard'] );
        $this->reply_markup['remove_keyboard'] = true;
        return $this;
    }

    public function send_chatAction($action, $chat_id=''){
        $chat_id = ($chat_id != '') ? $chat_id : $this->chat_id;
        $actions = array(
            'typing',
            'upload_photo',
            'record_video',
            'upload_video',
            'record_audio',
            'upload_audio',
            'upload_document',
            'find_location',
        );
        if (isset($action) && in_array($action, $actions)) {
            $this->result = $this->request('sendChatAction', compact('chat_id', 'action'));
            return $this;
        }
        throw new TelegramException('Invalid Action! Accepted value: '.implode(', ', $actions));
    }

    public function send_message($text, $chat_id = null, $disable_web_page_preview = true, $parse_mode = null){
        $params['text'] = $text;
        $params['chat_id'] = (!is_null($chat_id)) ? $chat_id : $this->chat_id;
        $params['parse_mode'] = (!is_null($parse_mode)) ? $parse_mode : $this->parse_mode ;
        $params['disable_web_page_preview'] = $disable_web_page_preview;

        return $this->request('sendMessage', $params);
        return $this;
    }

    public function send_photo($photo, $caption = null, $chat_id = null)
    {
        $chat_id = (!is_null($chat_id)) ? $chat_id : $this->chat_id;
        $data = compact('chat_id', 'photo', 'caption');
        return $this->request('sendPhoto', $data);
    }

    public function send_audio($audio, $caption = null, $chat_id = null, $duration = null, $performer = null, $title = null)
    {
        $chat_id = (!is_null($chat_id)) ? $chat_id : $this->chat_id;
        $data = compact('chat_id', 'audio', 'duration', 'performer', 'title');
        return $this->request('sendAudio', $data);
    }

    public function send_document($document, $caption = null, $chat_id = null)
    {
        $chat_id = (!is_null($chat_id)) ? $chat_id : $this->chat_id;
        $data = compact('chat_id', 'caption', 'document');
        return $this->request('sendDocument', $data);
    }

    public function send_video($video, $caption = null, $chat_id=null, $duration = null)
    {
        $chat_id = (!is_null($chat_id)) ? $chat_id : $this->chat_id;
        $data = compact('chat_id', 'video', 'duration', 'caption');

        return $this->request('sendVideo', $data);
    }

    public function send_animation($animation, $caption = null, $chat_id=null, $duration = null)
    {
        $chat_id = (!is_null($chat_id)) ? $chat_id : $this->chat_id;
        $data = compact('chat_id', 'animation', 'duration', 'caption');

        return $this->request('sendAnimation', $data);
    }

    public function send_voice($voice, $caption = null, $chat_id=null, $duration = null) {
        $chat_id = (!is_null($chat_id)) ? $chat_id : $this->chat_id;
        $data = compact('voice', 'chat_id', 'caption', 'duration');
        return $this->request('sendVoice', $data);    }

    public function send_location($latitude, $longitude, $chat_id = null)
    {
        $chat_id = (!is_null($chat_id)) ? $chat_id : $this->chat_id;
        $params = compact('chat_id', 'latitude', 'longitude');
        $this->result = $this->request('sendLocation', $params);
        return $this;
    }

    public function send_contact($phone_number, $first_name, $last_name = null, $chat_id = null)
    {
        $chat_id = (!is_null($chat_id)) ? $chat_id : $this->chat_id;
        $params = compact('chat_id', 'phone_number', 'first_name', 'last_name');
        $this->result = $this->request('sendContact', $params);
        return $this;
    }

    public function request($action, $content = []){

        $content['parse_mode'] = $this->parse_mode;

        if( !is_null($this->reply_markup) ){
            $content['reply_markup'] = $this->reply_markup;
        }

        if( !is_null($this->reply_markup) && array_key_exists('inline_keyboard', $content['reply_markup']) && count( $content['reply_markup']['inline_keyboard'] ) > 0){
            unset( $content['reply_markup']['keyboard'] );
        }
        $url = $this->url . $this->bot_url . $this->token . '/' . $action;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
        //curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type:multipart/form-data"));
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($content));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($ch);
        curl_close($ch);
        $this->request = json_decode($result, TRUE);
        if ( $this->error_reporting && !$this->request['ok']) {
           //error_log($this->request['description']);
           //throw new TelegramException( $this->request['description'] );
        }
        return $this->request;
    }

    public function uploadFile($method, $data, $cext='', $nfilename='')
    {
        $key = array(
            'sendPhoto'    => 'photo',
            'sendAudio'    => 'audio',
            'sendDocument' => 'document',
            'sendSticker'  => 'sticker',
            'sendVideo'    => 'video',
            'setWebhook'   => 'certificate'
        );
        if (filter_var($data[$key[$method]], FILTER_VALIDATE_URL)) {
            $file = '/tmp/'. md5(mt_rand(0, 9999));
            $url = true;
            file_put_contents($file, file_get_contents($data[$key[$method]]));
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime_type = finfo_file($finfo, $file);
            $extensions = array(
                'image/jpeg'  => '.jpg',
                'image/png'   =>  '.png',
                'image/gif'   =>  '.gif',
                'image/bmp'   =>  '.bmp',
                'image/tiff'  =>  '.tif',
                'audio/ogg'   =>  '.ogg',
                'audio/mpeg'  =>  '.mp3',
                'video/mp4'   =>  '.mp4',
                'image/webp'  =>  '.webp'
            );
            if ($method != 'sendDocument') {
                if (!array_key_exists($mime_type, $extensions)) {
                    unlink($file);
                    throw new TelegramException('Bad file type/extension');
                }
            }
            $newFile = $file . ( (isset($extensions[$mime_type])) ? $extensions[$mime_type] : '.'.$cext );
            rename($file, $newFile);
            $data[$key[$method]] = new CurlFile($newFile, $mime_type, ( !empty($nfilename) ? $nfilename.'.'.$cext : $newFile ));
        } else {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime_type = finfo_file($finfo, $data[$key[$method]]);
            $data[$key[$method]] = new CurlFile($data[$key[$method]], $mime_type, $data[$key[$method]]);
        }
        $url = $this->url . $this->bot_url . $this->token . '/' . $method;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        /*curl_setopt($ch, CURLOPT_NOPROGRESS, false);
        curl_setopt($ch, CURLOPT_PROGRESSFUNCTION, function($resource, $downloadSize, $downloaded, $uploadSize, $uploaded) use ($method, $data){
            progress_upload($resource, $downloadSize, $downloaded, $uploadSize, $uploaded, $method, $data);
        });*/
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);
        curl_close($ch);
         
        $this->request = json_decode($result, TRUE);
        if ($url) {@unlink($newFile);}
        if ( $this->error_reporting && !$this->request['ok']) {
           
        }
        return $this->request;
    }

    public function progress_upload($resource, $downloadSize, $downloaded, $uploadSize, $uploaded, $method, $data)
    {
        $key = array(
            'sendPhoto'    => 'upload_photo',
            'sendAudio'    => 'upload_audio',
            'sendVoice'    => 'record_voice',
            'sendDocument' => 'upload_document',
            'sendSticker'  => 'typing',
            'sendVideo'    => 'upload_video',
        );

        if (array_key_exists($method, $key)) {
            $action = $key[$method];
        }else{
            $action = 'typing';
        }

        $this->send_chatAction($action, $data['chat_id']);
    }


    private function get_http_response_code($url) {
        $headers = get_headers($url);
        return substr($headers[0], 9, 3);
    }

    public function get_me(){
        $this->result = $this->request('getMe');
        return $this->result();
    }

    public function get_chatId(){
        return $this->chat_id;
    }

    public function get_chatMember($chat_id='', $user_id='')
    {
        $user_id = ($user_id != '') ? $user_id : $this->chat_id;
        $chat_id = ($chat_id != '') ? $chat_id : $this->channel_id;
        return $this->request('getChatMember', compact('chat_id', 'user_id'));
        //return $this;

    }

    public function get_userProfilePhotos($user_id = null, $offset = null, $limit = null)
    {
        $user_id = ( !is_null($user_id) ) ? $user_id : $this->chat_id;
        $params = compact('user_id', 'offset', 'limit');
        $this->result = $this->request('getUserProfilePhotos', $params);
        return $this;
    }

    public function get_file($file_id)
    {
        $this->result = $this->request('getFile', compact('file_id'));
        return $this;
    }

    public function get_filecontent($file_id, $ret=FALSE)
    {
        $this->request('getFile', compact('file_id'));
        if ($this->request['ok']) {
            $url = $this->url . $this->file_url .$this->bot_url . $this->token . '/'.$this->request['result']['file_path'];
            if ( $this->get_http_response_code( $url ) == "200") {
                return $ret ? file_get_contents( $url ) : $url;
            }
            return false;
        }

        return false;
    }

    public function get_chat($chat_id = null)
    {
        $chat_id = ( !is_null($chat_id) ) ? $chat_id : $this->chat_id;
        $this->result = $this->request('getChat', compact('chat_id'));
        return $this;
    }

    public function get_chatAdministrators($chat_id = null)
    {
        $chat_id = ( !is_null($chat_id) ) ? $chat_id : $this->chat_id;
        $this->result = $this->request('getChatAdministrators', compact('chat_id'));
        return $this;
    }

    public function get_chatMembersCount( $chat_id = null )
    {
        $chat_id = ( !is_null($chat_id) ) ? $chat_id : $this->chat_id;
        $this->result = $this->request('getChatMembersCount', compact('chat_id'));
        return $this;
    }

    public function get_updates($offset=null)
    {
        $res = $this->request('getUpdates', compact('offset'));
        if (array_key_exists('result', $res)) {
            return $res['result'];
        }
        return false;
    }    

    public function get_webhookUpdates()
    {
        $body = json_decode(file_get_contents('php://input'), true);
        return $body;
    }

    public function leave_chat($chat_id)
    {
        $this->result = $this->request('leaveChat', compact('chat_id'));
        return $this;
    }

    public function result( $k = null ){
        if(array_key_exists('result', $this->result)){
            if ( !is_null($k) ) {
                if(array_key_exists($k, $this->result)){
                    return $this->result['result'][$k];
                }
                return false;
            }
            return $this->result['result'];
        }

        return $this->result;
    }
}

?>