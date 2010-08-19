<?php

require_once("Common.php");

class ArrayUtil {
    public static function to_array($content, $response_type) {
        if ($response_type == ResponseType::$JSON) {
          return json_decode($content);
        } else if ($response_type == ResponseType::$XML) {
          $xml = @simplexml_load_string($content);
          return self::xml_to_array($xml);
        }
        
        throw new Exception('ResponseType : ' . $response_type . ' Not Supported.');
    }

    public static function xml_to_array($xml) {
        $arr = array();
        
        if ($xml) {
            foreach ($xml as $key => $value) {
                if ($xml['count']) {
                    $arr[] = self::xml_to_array($value);
                } else {
                    $arr[$key] = self::xml_to_array($value);
                }
            }
        }
        
        if (sizeof($arr) > 0) {
            return $arr;
        } else {
            return (string)$xml;
        }
    }
}

class HttpUtil {
    public static function get_server($uri) {
        //TODO: do something better
        $uri = preg_replace('/http(s)?:\/\//', '', strtolower($uri));
        return $uri;
    }
    
    public static function get_protocol_port($uri) {
        //TODO: do something better
        if (substr($uri, 0, 5) == 'https') {
            return 443;
        } else if (substr($uri, 0, 4) == 'http') {
            return 80;
        }
        
        return 80;
    }
    
    public static function convert_post_data($arr) {
        $post_result = "";
        
        foreach ($arr as $key => $value) {
            $post_result .= $key . '=' . $value . '&';
        }
        
        if (strlen($post_result) > 0) {
            $post_result = substr($post_result, 0, strlen($post_result)-1);            
        }
        
        return $post_result;
    }
}

?>