<?php
/**
 * PHP SDK for Factom Open API
 * @author De Facto <team@de-facto.pro>
 */
class FactomOpenAPI
{
    const VERSION = 'v1';
    protected
        $endpoint = null,
        $api_key = null,
        $response = null;
        
    /**
    * Open API declaration
    */
    public function __construct($endpoint, $api_key)
    {
        $this->endpoint = $endpoint."/".self::VERSION;
	    $this->api_key = $api_key;
    }
    /**
    * Create a new chain
    * $extIds, array — Tags that can be used to identify your chain
    * $content, string — (optional) This is the data that will make up the first entry in your new chain
    */
    public function createChain($extIds, $content="")
    {	
	    $chain["extIds"] = $this->helper_base64_encode($extIds);
        $chain["content"] = $this->helper_base64_encode($content);
        $res = $this->make_request('/chains', $chain, 'POST');
        if (isset($res["result"])) {
            $res["result"]["extIds"] = $this->helper_base64_decode($res["result"]["extIds"]);
        }
        return $res;
    }

    /**
    * Create a new entry for the selected chain
    * $chainId, string — ChainID of Factom chain
    * $extIds, array — (optional) Tags that can be used to identify your entry
    * $content, string — (optional) This is the data that will be stored directly on the blockchain
    */
    public function createEntry($chainId, $extIds=NULL, $content="")
    {
	    $entry["chainId"] = $chainId;
	    $entry["content"] = $this->helper_base64_encode($content);
	    if (isset($extIds)) {
	    	$entry["extIds"] = $this->helper_base64_encode($extIds);	    
        }
        $res = $this->make_request('/entries', $entry, 'POST');
        if (isset($res["result"])) {
            if (isset($res["result"]["content"])) {
                $res["result"]["content"] = $this->helper_base64_decode($res["result"]["content"]);
            }
            if (isset($res["result"]["extIds"])) {
                $res["result"]["extIds"] = $this->helper_base64_decode($res["result"]["extIds"]);
            }
        }
	    return $res;
    }

    /**
    * Create a new entry for the selected chain
    * $extIds, array — Tags that can be used to identify your entry
    */
    public function searchChains($extIds, $start=0, $limit=0, $status=NULL, $sort=NULL)
    {
        $queryParams = "";
        if (($start > 0) || ($limit>0) || $sort) {
            $queryParams .= "?";
            if ($start > 0) {
                $queryParams .= "start=";
                $queryParams .= $start;
                $queryParams .= "&";
            }
            if ($limit > 0) {
                $queryParams .= "limit=";
                $queryParams .= $limit;
                $queryParams .= "&";
            }
            if ($status) {
                $queryParams .= "status=";
                $queryParams .= $status;
                $queryParams .= "&";
            }
            if ($sort) {
                $queryParams .= "sort=";
                $queryParams .= $sort;
                $queryParams .= "&";
            }
        }
        $chain["extIds"] = $this->helper_base64_encode($extIds);
        $res = $this->make_request('/chains/search'.$queryParams, $chain, 'POST');
        if (isset($res["result"])) {
            if (sizeof($res["result"])>0) {
                foreach ($res["result"] as &$i) {
                    $i["extIds"] = $this->helper_base64_decode($i["extIds"]);
                }
            }
        }
	    return $res;
    }

    /**
    * Create a new entry for the selected chain
    * $extIds, array — Tags that can be used to identify your entry
    */
    public function searchChainEntries($chainId, $extIds, $start=0, $limit=0, $status=NULL, $sort=NULL)
    {
        $queryParams = "";
        if (($start > 0) || ($limit>0) || $sort) {
            $queryParams .= "?";
            if ($start > 0) {
                $queryParams .= "start=";
                $queryParams .= $start;
                $queryParams .= "&";
            }
            if ($limit > 0) {
                $queryParams .= "limit=";
                $queryParams .= $limit;
                $queryParams .= "&";
            }
            if ($status) {
                $queryParams .= "status=";
                $queryParams .= $status;
                $queryParams .= "&";
            }
            if ($sort) {
                $queryParams .= "sort=";
                $queryParams .= $sort;
                $queryParams .= "&";
            }
        }
        $entry["chainId"] = $chainId;
        $entry["extIds"] = $this->helper_base64_encode($extIds);
        $res = $this->make_request('/chains/'.$chainId.'/entries/search'.$queryParams, $entry, 'POST');
        if (isset($res["result"])) {
            if (sizeof($res["result"])>0) {
                foreach ($res["result"] as &$i) {
                    if (isset($i["content"])) {
                        $i["content"] = $this->helper_base64_decode($i["content"]);
                    }
                    if (isset($i["extIds"])) {
                        $i["extIds"] = $this->helper_base64_decode($i["extIds"]);
                    }
                }
            }
        }
	    return $res;
    }

    /**
    * Create a new entry for the selected chain
    * $extIds, array — Tags that can be used to identify your entry
    */
    public function factomd($method, $params="")
    {
        return $this->make_request('/factomd/'.$method, $params, 'POST');
    }

    /**
    * Returns information about a specific entry on Factom
    */
    public function getChains($start=0, $limit=0, $status=NULL, $sort=NULL)
    {	    
        $queryParams = "";
        if (($start > 0) || ($limit>0) || $sort) {
            $queryParams .= "?";
            if ($start > 0) {
                $queryParams .= "start=";
                $queryParams .= $start;
                $queryParams .= "&";
            }
            if ($limit > 0) {
                $queryParams .= "limit=";
                $queryParams .= $limit;
                $queryParams .= "&";
            }
            if ($status) {
                $queryParams .= "status=";
                $queryParams .= $status;
                $queryParams .= "&";
            }
            if ($sort) {
                $queryParams .= "sort=";
                $queryParams .= $sort;
                $queryParams .= "&";
            }
        }
        $res = $this->make_request('/chains'.$queryParams);
        if (isset($res["result"])) {
            if (sizeof($res["result"])>0) {
                foreach ($res["result"] as &$i) {
                    $i["extIds"] = $this->helper_base64_decode($i["extIds"]);
                }
            }
        }
        return $res;
    }

    /**
    * Returns information about a specific entry on Factom
    */
    public function getChain($chainId)
    {	    
        $res = $this->make_request('/chains/'.$chainId);
        if (isset($res["result"])) {
            $res["result"]["extIds"] = $this->helper_base64_decode($res["result"]["extIds"]);
        }
        return $res;
    }

    /**
    * Returns information about a specific entry on Factom
    */
    public function getChainEntries($chainId, $start=0, $limit=0, $status=NULL, $sort=NULL)
    {	    
        $queryParams = "";
        if (($start > 0) || ($limit>0) || $sort) {
            $queryParams .= "?";
            if ($start > 0) {
                $queryParams .= "start=";
                $queryParams .= $start;
                $queryParams .= "&";
            }
            if ($limit > 0) {
                $queryParams .= "limit=";
                $queryParams .= $limit;
                $queryParams .= "&";
            }
            if ($status) {
                $queryParams .= "status=";
                $queryParams .= $status;
                $queryParams .= "&";
            }
            if ($sort) {
                $queryParams .= "sort=";
                $queryParams .= $sort;
                $queryParams .= "&";
            }
        }
        $res = $this->make_request('/chains/'.$chainId.'/entries'.$queryParams);
        if (isset($res["result"])) {
            if (sizeof($res["result"])>0) {
                foreach ($res["result"] as &$i) {
                    if (isset($i["content"])) {
                        $i["content"] = $this->helper_base64_decode($i["content"]);
                    }
                    if (isset($i["extIds"])) {
                        $i["extIds"] = $this->helper_base64_decode($i["extIds"]);
                    }
                }
            }
        }
        return $res;
    }

    /**
    * Returns information about a specific entry on Factom
    */
    public function getEntry($entryHash)
    {	    
        $res = $this->make_request('/entries/'.$entryHash);
        if (isset($res["result"])) {
            if (isset($res["result"]["content"])) {
                $res["result"]["content"] = $this->helper_base64_decode($res["result"]["content"]);
            }
            if (isset($res["result"]["extIds"])) {
                $res["result"]["extIds"] = $this->helper_base64_decode($res["result"]["extIds"]);
            }
        }
        return $res;
    }

    /**
    * Returns information about a specific entry on Factom
    */
    public function getChainFirstEntry($chainId)
    {	    
        $res = $this->make_request('/chains/'.$chainId.'/entries/first');
        if (isset($res["result"])) {
            if (isset($res["result"]["content"])) {
                $res["result"]["content"] = $this->helper_base64_decode($res["result"]["content"]);
            }
            if (isset($res["result"]["extIds"])) {
                $res["result"]["extIds"] = $this->helper_base64_decode($res["result"]["extIds"]);
            }
        }
        return $res;
    }


    /**
    * Returns information about a specific entry on Factom
    */
    public function getChainLastEntry($chainId)
    {	    
        $res = $this->make_request('/chains/'.$chainId.'/entries/last');
        if (isset($res["result"])) {
            if (isset($res["result"]["content"])) {
                $res["result"]["content"] = $this->helper_base64_decode($res["result"]["content"]);
            }
            if (isset($res["result"]["extIds"])) {
                $res["result"]["extIds"] = $this->helper_base64_decode($res["result"]["extIds"]);
            }
        }
        return $res;
    }

    /**
    * Returns information about API usage & limits
    */
    public function getUser()
    {	    
        return $this->make_request('/user');
    }
    
    /**
    * Make the request to Factom Open API
    */
    protected function make_request($path, $data = NULL, $type = "GET")
    {
        $data = json_encode($data);
        
        if (function_exists('curl_init')) {
		$ch = curl_init($this->endpoint.$path);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $type);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
		curl_setopt($ch, CURLOPT_TIMEOUT, 3);
		if ($data) {
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		}
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Authorization: Bearer ' . $this->api_key,                                                                          
			'Content-Type: application/json',
			'Content-Length: ' . strlen($data))
		);
		$response = curl_exec($ch);
		curl_close($ch);
        }
	else {
		throw new Exception("CURL PHP extension required for this script");
	}
        
        return json_decode($response, true);
    }

    /**
     * Helper base64_encode
     */
    protected function helper_base64_encode($input)
    {

        if (is_array($input)) {
            foreach ($input as &$i) {
                $i = base64_encode($i);
            }
        } else {
            $input = base64_encode($input);
        }

        return $input;

    }

    /**
     * Helper base64_decode
     */
    protected function helper_base64_decode($input)
    {

        if (is_array($input)) {
            foreach ($input as &$i) {
                $i = base64_decode($i);
            }
        } else {
            $input = base64_decode($input);
        }
        
        return $input;

    }
}