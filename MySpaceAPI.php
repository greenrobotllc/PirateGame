<?php

require_once("Common.php");
require_once("Config.php");
require_once("OAuth.php");
require_once("BaseAPI.php");

class MySpaceAPI extends BaseAPI {
    private $oauth_token = "";
    private $oauth_consumer = "";
    
    private $application_key = "";
    private $application_secret = "";
    
    //ctor
    public function __construct($application_key, $application_secret) {
        $this->application_key = $application_key;
        $this->application_secret = $application_secret;
        
        $this->oauth_consumer = new OAuthConsumer($this->application_key, $this->application_secret);
        $this->oauth_token = new OAuthToken(null, null);
        
        $this->api_version = ApiVersionType::$VERSION1;
        
        $this->resource_base = CommonConstants::$URL_ROOT_API;
        $this->response_type = ResponseType::$XML;
    }
    
    // GETS get-user
    public function get_user($user_id) {
        $resource = sprintf('users%s%s', CommonConstants::$URL_SEPERATOR, $user_id);
        $result = $this->do_get($resource, null);
        return $result;
    }
    
    //GETS get-profile
    public function get_profile($user_id) {
        $resource = sprintf('users%s%s%sprofile', CommonConstants::$URL_SEPERATOR, $user_id, CommonConstants::$URL_SEPERATOR);
        $result = $this->do_get($resource, null);
        return $result;
    }
    
    //GETS get-friends
    public function get_friends($user_id, $page = null, $page_size = null, $list = null) {
        $resource = sprintf('users%s%s%sfriends', CommonConstants::$URL_SEPERATOR, $user_id, CommonConstants::$URL_SEPERATOR);
        $paging = "";
        
        if ($page !== null) {
            $paging .= 'page=' . $page . '&';
        }
        
        if ($page_size !== null) {
            $paging .= 'page_size=' . $page_size . '&';
        }
        
        if ($list !== null) {
            $paging .= 'list=' . $list . '&';
        }
        
        if (strlen($paging) > 0) {
            $paging = '?' . substr($paging, 0, strlen($paging)-1);            
        }
        
        return $this->do_get($resource . $paging, null);
    }
    
    //GETS get-friendship
    public function get_friendship($user_id, $array_ids) {
        $resource = sprintf('users%s%s%sfriends%s%s', CommonConstants::$URL_SEPERATOR, $user_id, CommonConstants::$URL_SEPERATOR, CommonConstants::$URL_SEPERATOR, implode($array_ids, ';'));
        $result = $this->do_get($resource, null);
        return $result;
    }
    
    //GETS get-albums
    public function get_albums($user_id) {
        $resource = sprintf('users%s%s%salbums', CommonConstants::$URL_SEPERATOR, $user_id, CommonConstants::$URL_SEPERATOR);
        $result = $this->do_get($resource, null);
        return $result;
    }
    
    //GETS get-album
    public function get_album($user_id, $album_id) {
        $resource = sprintf('users%s%s%salbums%s%s%sphotos', CommonConstants::$URL_SEPERATOR, $user_id, CommonConstants::$URL_SEPERATOR, CommonConstants::$URL_SEPERATOR, $album_id, CommonConstants::$URL_SEPERATOR);
        $result = $this->do_get($resource, null);
        return $result;
    }
    
    //GETS get-photos
    public function get_photos($user_id) {
        $resource = sprintf('users%s%s%sphotos', CommonConstants::$URL_SEPERATOR, $user_id, CommonConstants::$URL_SEPERATOR);
        $result = $this->do_get($resource, null);
        return $result;
    }
    
    //GETS get-photo
    public function get_photo($user_id, $photo_id) {
        $resource = sprintf('users%s%s%sphotos%s%s', CommonConstants::$URL_SEPERATOR, $user_id, CommonConstants::$URL_SEPERATOR, CommonConstants::$URL_SEPERATOR, $photo_id);
        $result = $this->do_get($resource, null);
        return $result;
    }
    
    //GETS get-interests
    public function get_interests($user_id) {
        $resource = sprintf('users%s%s%sinterests', CommonConstants::$URL_SEPERATOR, $user_id, CommonConstants::$URL_SEPERATOR);
        $result = $this->do_get($resource, null);
        return $result;
    }
    
    //GETS get-details
    public function get_details($user_id) {
        $resource = sprintf('users%s%s%sdetails', CommonConstants::$URL_SEPERATOR, $user_id, CommonConstants::$URL_SEPERATOR);
        $result = $this->do_get($resource, null);
        return $result;
    }
    
    //GETS get-videos
    public function get_videos($user_id) {
        $resource = sprintf('users%s%s%svideos', CommonConstants::$URL_SEPERATOR, $user_id, CommonConstants::$URL_SEPERATOR);
        $result = $this->do_get($resource, null);
        return $result;
    }

    //GETS get-video
    public function get_video($user_id, $video_id) {
        $resource = sprintf('users%s%s%svideos%s%s', CommonConstants::$URL_SEPERATOR, $user_id, CommonConstants::$URL_SEPERATOR, CommonConstants::$URL_SEPERATOR, $video_id);
        $result = $this->do_get($resource, null);
        return $result;
    }
    
    //GETS get-groups
    public function get_groups($user_id) {
        $resource = sprintf('users%s%s%sgroups', CommonConstants::$URL_SEPERATOR, $user_id, CommonConstants::$URL_SEPERATOR);
        $result = $this->do_get($resource, null);
        return $result;
    }
    
    //GETS get-status
    public function get_status($user_id) {
        $resource = sprintf('users%s%s%sstatus', CommonConstants::$URL_SEPERATOR, $user_id, CommonConstants::$URL_SEPERATOR);
        $result = $this->do_get($resource, null);
        return $result;
    }
    
    //GETS get-mood
    public function get_mood($user_id) {
        $resource = sprintf('users%s%s%smood', CommonConstants::$URL_SEPERATOR, $user_id, CommonConstants::$URL_SEPERATOR);
        $result = $this->do_get($resource, null);
        return $result;
    }
    
    //GETS get-comments
    public function get_comments($user_id) {
        $resource = sprintf('users%s%s%scomments', CommonConstants::$URL_SEPERATOR, $user_id, CommonConstants::$URL_SEPERATOR);
        $result = $this->do_get($resource, null);
        return $result;
    }
    
    //PUTS put-mood
    public function put_mood($user_id, $mood) {
        $resource = sprintf('users%s%s%smood', CommonConstants::$URL_SEPERATOR, $user_id, CommonConstants::$URL_SEPERATOR);
        $result = $this->do_put($resource, array('mood' => $mood), null);
        return $result;
    }
    
    public function do_get($resource, $headers) {
        $this->resource_uri = CommonConstants::$URL_SEPERATOR . $this->api_version . CommonConstants::$URL_SEPERATOR . $resource . '.' . $this->get_response_type();
        $sha1 = new OAuthSignatureMethod_HMAC_SHA1();
        $req = OAuthRequest::from_consumer_and_token($this->oauth_consumer, $this->oauth_token, HttpMethodType::$GET, $this->resource_base . $this->resource_uri, $headers);
        $req->sign_request($sha1, $this->oauth_consumer, null);
        $resource_request = $req->to_url();
        return parent::_do_get($resource_request, $headers);
    }
    
    public function do_post($resource, $post_data, $headers) {
        $this->resource_uri = CommonConstants::$URL_SEPERATOR . $this->api_version . CommonConstants::$URL_SEPERATOR . $resource . '.' . $this->get_response_type();
        $sha1 = new OAuthSignatureMethod_HMAC_SHA1();
        $req = OAuthRequest::from_consumer_and_token($this->oauth_consumer, $this->oauth_token, HttpMethodType::$GET, $this->resource_base . $this->resource_uri, $headers);
        $req->sign_request($sha1, $this->oauth_consumer, null);
        $resource_request = $req->to_url();
        return parent::_do_post($resource_request, $post_data, $headers);
    }

    public function do_put($resource, $put_data, $headers) {
        $this->resource_uri = CommonConstants::$URL_SEPERATOR . $this->api_version . CommonConstants::$URL_SEPERATOR . $resource . '.' . $this->get_response_type();
        $sha1 = new OAuthSignatureMethod_HMAC_SHA1();
        $req = OAuthRequest::from_consumer_and_token($this->oauth_consumer, $this->oauth_token, HttpMethodType::$GET, $this->resource_base . $this->resource_uri, $headers);
        $req->sign_request($sha1, $this->oauth_consumer, null);
        $resource_request = $req->to_url();
        return parent::_do_put($resource_request, $put_data, $headers);
    }    
    
    public function validate_oauth($oauth_signature) {
        //TODO:
    }
}
?>