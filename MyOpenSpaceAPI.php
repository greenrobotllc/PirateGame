<?php

require_once("Common.php");
require_once("Config.php");
require_once("BaseAPI.php");

class MyOpenSpaceAPI extends BaseAPI {
    
    private $opensocial_token = "";
    private $opensocial_view = "";
    private $opensocial_detail = "";
    private $context = "";
    
    public function set_opensocial_view($view) {
        $this->opensocial_view = $view;
    }
    
    public function set_opensocial_detail($detail) {
        $this->opensocial_detail = $detail;
    }
    
    public function set_openocial_token($token) {
        $this->opensocial_token = $token;
    }
    
    public function set_context($context) {
        $this->context = $context;
    }
    
    //ctor
    public function __construct($opensocial_token, $opensocial_view, $opensocial_detail) {
        $this->opensocial_token = $opensocial_token;
        $this->api_version = ApiVersionType::$VERSION1;
        
        $this->resource_base = CommonConstants::$URL_OPENSOCIAL_ROOT_API;
        $this->response_type = ResponseType::$XML;
        $this->context = ContextType::$VIEWER;
    }

    // GETS get_profile
    public function get_profile() {
        $resource = 'profile';
        $result = $this->do_get($this->context, $resource, null);
        return $result;
    }

    // GETS get_friends
    public function get_friends($page = null, $page_size = null, $list = null) {
        $resource = 'friends';
        $result = $this->do_get($this->context, $resource, null);
        return $result;
    }
    
    // GETS get_albums
    public function get_albums($album_id = null) {
        $resource = 'albums';
        
        if ($album_id != null) {
            $resource .= CommonConstants::$URL_SEPERATOR . $album_id;
        }
        
        $result = $this->do_get($this->context, $resource, null);
        return $result;
    }
    
    // GETS get_albums_photos
    public function get_albums_photos($album_id) {
        $resource = 'albums';
        
        $resource .= CommonConstants::$URL_SEPERATOR . $album_id;
        $resource .= CommonConstants::$URL_SEPERATOR . 'photos';
        
        $result = $this->do_get($this->context, $resource, null);
        return $result;
    }
    
    // GETS get_photos
    public function get_photos($photo_id = null) {
        $resource = 'photos';
        
        if ($album_id != null) {
            $resource .= CommonConstants::$URL_SEPERATOR . $album_id;
        }
        
        $result = $this->do_get($this->context, $resource, null);
        return $result;
    }
    // GETS get_videos
    public function get_videos($video_id = null) {
        $resource = 'videos';
        
        if ($video_id != null) {
            $resource .= CommonConstants::$URL_SEPERATOR . $video_id;
        }
        
        $result = $this->do_get($this->context, $resource, null);
        return $result;
    }
    
    // GETS get_global_appdata
    public function get_global_appdata($key = null) {
        $resource = 'appdata/global';
        
        if ($key != null) {
            $resource .= CommonConstants::$URL_SEPERATOR . $key;
        }
        
        $result = $this->do_get($this->context, $resource, null);
        return $result;
    }
    
    // GETS get_appdata
    public function get_appdata($key = null) {
        $resource = 'appdata';
        
        if ($key != null) {
            $resource .= CommonConstants::$URL_SEPERATOR . $key;
        }
        
        $result = $this->do_get($this->context, $resource, null);
        return $result;
    }

    // GETS get_friends_appdata
    public function get_friends_appdata($key = null) {
        $resource = 'friends/appdata';
        
        if ($key != null) {
            $resource .= CommonConstants::$URL_SEPERATOR . $key;
        }
        
        $result = $this->do_get($this->context, $resource, null);
        return $result;
    }
    
    //DO
    public function do_get($context, $resource, $headers) {
        $resource_request  = $this->resource_base . CommonConstants::$URL_SEPERATOR;
        $resource_request .= $this->api_version . CommonConstants::$URL_SEPERATOR;
        
        $resource_request .= $context . CommonConstants::$URL_SEPERATOR;
        $resource_request .= $resource . "." . $this->get_response_type();
        
        $resource_request .= "?";
        $resource_request .= OpenSocialQueryStringList::$QS_OPEN_SOCIAL_TOKEN . "=" . $this->opensocial_token;
        $resource_request .= "&";
        $resource_request .= OpenSocialQueryStringList::$QS_OPEN_SOCIAL_VIEW . "=" + $this->opensocial_view;
        $resource_request .= "&";
        $resource_request .= OpenSocialQueryStringList::$QS_DETAIL_TYPE . "=" + $this->opensocial_detail;
        
        return parent::_do_get($resource_request, $headers);
    }
    
    //DO
    public function do_post($resource, $post_data, $headers) {
        //TODO:
        return parent::_do_post($resource_request, $post_data, $headers);
    }

    //DO
    public function do_put($resource, $put_data, $headers) {
        //TODO:
        return parent::_do_put($resource_request, $put_data, $headers);
    }    
    
    //DO
    public function validate_oauth($oauth_signature) {
        //TODO:
    }
}
?>