<?php
require_once("Common.php");
require_once("Util.php");
require_once "HTTP/Request.php"; // FROM PEAR.php.net -- install by command $> pear install HTTP_Request

class BaseAPI {
    protected $resource_base = "";
    protected $resource_uri = "";
    protected $api_version = "";
    protected $response_type = "";
    
    protected $output_array = true;
    
    //Properties set-response-type
    public function set_response_type($response_type) {
        $this->response_type = $response_type;
    }
    
    //Properties get-response-type
    public function get_response_type() {
        return $this->response_type;
    }
    
    //Properties set-output-array
    public function set_output_array($output_array) {
        $this->output_array = $output_array;
    }
    
    //Properties get-output-array
    public function get_output_array() {
        return $this->output_array;
    }
    
    
    //Properties set-api-version
    public function set_api_version($version) {
        $this->api_version = $version;
    }
    
    //Properties get-api-version
    public function get_api_version() {
        return $this->api_version;
    }
    
    //DO - get
    public function _do_get($resource_request, $headers) {
        if (function_exists('curl_init')) {
            $curl = curl_init();
            
            //TODO: add headers
            //curl_setopt($curl, CURLOPT_HEADER, 0);
            curl_setopt($curl, CURLOPT_URL, $resource_request);
            curl_setopt($curl, CURLOPT_USERAGENT, CommonConstants::$LIB_NAME . ' ' . CommonConstants::$LIB_VERSION . ' (curl) ' . phpversion());
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            
            $response_content = curl_exec($curl);
            $response_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            
            //echo $response_code;
            
            if ($response_code && $response_code >= 400 ) {
                throw new Exception($response_code . ' ' . $response_content);
            }
            
            curl_close($curl);
        } else {
            $request =& new HTTP_Request($resource_request);
            
            //$request->setMethod(HTTP_REQUEST_METHOD_GET;
            
            if ($headers != null) {
                foreach($headers as $header_name => $header_value) {
                    $request->addHeader($header_name, $header_value);
                }                
            }
            
            if (!PEAR::isError($request->sendRequest())) {
                $response_content = $request->getResponseBody();
            } else {
                die($response->getMessage());
            }
            
            //echo $client->status;
            
            if ($request->getResponseCode() >= 400) {
                throw new Exception($request->getResponseCode() . ' ' . $response_content);
            }            
        }
        
        if ($this->output_array) {
            $response_content = ArrayUtil::to_array($response_content, $this->get_response_type());
        }
        
        return $response_content;
    }

    //DO - post -- $post_data should be in the following format array('name' => 'Some Name', 'email' => 'email@example.com'));
    public function _do_post($resource_request, $post_data, $headers) {
        if (function_exists('curl_init')) {
            $curl = curl_init();
            
            //TODO: add headers
            //curl_setopt($curl, CURLOPT_HEADER, 0);
            curl_setopt($curl, CURLOPT_URL, $resource_request);
            curl_setopt($curl, CURLOPT_USERAGENT, CommonConstants::$LIB_NAME . ' ' . CommonConstants::$LIB_VERSION . ' (curl) ' . phpversion());
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

            curl_setopt($ch, CURLOPT_POST, 1);

            curl_setopt($ch, CURLOPT_POSTFIELDS, $this->convert_post_data($post_data));
            
            $response_content = curl_exec($curl);
            $reponse_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            
            curl_close($curl);
        } else {
            $request =& new HTTP_Request($resource_request);
            
            $request->setMethod(HTTP_REQUEST_METHOD_POST);

            if ($headers != null) {
                foreach($headers as $header_name => $header_value) {
                    $request->addHeader($header_name, $header_value);
                }                
            }
            
            if ($post_data != null) {
                foreach($post_data as $post_data_name => $post_name_value) {
                    $request->addPostData($post_data_name, $post_name_value);
                }
            }
            //$request->setBody('<user xsi:schemaLocation="https://api.myspace.com/myspace.xsd" xmlns="api-v1.myspace.com" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema"><userid>264730435</userid><uri>http://api.msappspace.com/v1/users/264730435</uri><mood>none</mood></user>');
            
            //$request->setBody("\r\n");
            
            if (!PEAR::isError($request->sendRequest())) {
                $response_content = $request->getResponseBody();
            } else {
                die($response->getMessage());
            }
            
            //echo $client->status;
            
            if ($request->getResponseCode() >= 400) {
                throw new Exception($request->getResponseCode() . ' ' . $response_content);
            }
        }
        
        return $response_content;
    }    

    //DO - put -- $put_data should be in the following format array('name' => 'Some Name', 'email' => 'email@example.com'));
    public function _do_put($resource_request, $put_data, $headers) {
        $put_override_header = array(CommonConstants::$X_HTTP_METHOD_OVERRIDE_HEADER => 'PUT');
        
        if ($headers != null) {
            $headers = array_merge($headers, $put_override_header);
        } else {
            $headers = $put_override_header;
        }
        
        return $this->_do_post($resource_request, $put_data, $headers);
    }    
}
?>