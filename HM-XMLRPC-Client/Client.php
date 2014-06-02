<?php

namespace XMLRPC;

/**
 * XMLRPCException The XML RPC Exception class
 */
class XMLRPCException extends \Exception
{
}

/**
 * Client The XML RPC client class
 *
 * 
 *
 * @version 1.0
 * @author sathya
 */
class Client
{
    /**
     * IP address of the XML RPC server
     * @var string
     */
    private $host = "";
    
    /**
     * Port number of the XML RPC server
     * @var int
     */
    private $port = 2001;
    
    /**
     * Enable SSL
     * @var bool
     */
    private $ssl = false;
    
    /**
     * Enable certificate verification
     * @var bool
     */
    private $sslVerifyPeer = true;
    
    /**
     * Enable certificate verification
     * @param $value bool
     */
    public function setSSLVerifyPeer($value)
    {
        $this->sslVerifyPeer = $value;
    }
    
    /**
     * Path to the certificate cuthority's certificate
     * @var string
     */
    private $caFile = "";
    
    /**
     * Set the path to the certificate cuthority's certificate
     * @param $value string
     */
    public function setCAFile($value)
    {
        $this->caFile = $value;
    }
    
    /**
     * Username for basic auth
     * @var string
     */
    private $username = "";
    
    /**
     * Set username for basic auth
     * @param $value string
     */
    public function setUsername($value)
    {
        $this->username = $value;
    }
    
    /**
     * Password for basic auth
     * @var string
     */
    private $password = "";
    
    /**
     * Set password for basic auth
     * @param $value string
     */
    public function setPassword($value)
    {
        $this->password = $value;
    }
    
    /**
    
    /**
     * Default constructor
     * @param $host string IP address of the XML RPC server
     * @param $port int Port number of the XML RPC server
     * @param $ssl bool Enable SSL
     */
    public function __construct($host, $port = 2001, $ssl = false)
    {
        $this->host = $host;
        $this->port = $port;
        $this->ssl = $ssl;
    }
    
    /**
     * Sends a XML RPC request to XML RPC server
     * @param $request string Request generated by xmlrpc_encode_request
     * @return string The returned XML string
     */
    private function sendRequest($request)
    {
        $response = '';
	    $retries = 0;
	    $startTime = time();
	    while(!$response && $retries < 5)
	    {
	        $socket = @stream_socket_client("tcp://".$this->host.":".$this->port, $errorNumber, $errorString, 10);
        	if(!$socket) throw new XMLRPCException("Could not open socket. Host: ".$this->host." Port: ".$this->port." Error: $errorString ($errorNumber)");
            if($this->ssl)
            {
                stream_set_blocking($socket, true);
                stream_context_set_option($socket, 'ssl', 'SNI_enabled', true);
                if($this->caFile) stream_context_set_option($socket, 'ssl', 'cafile', $this->caFile);
                stream_context_set_option($socket, 'ssl', 'verify_peer', $this->sslVerifyPeer);
                $secure = stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT);
                if(!$secure) 
                {
                    @fclose($socket);
                    throw new XMLRPCException("XMLRPC error: Failed to enable SSL.");
                }
                stream_set_blocking($socket, false);
            }

		    $response = '';
        	$query = "POST / HTTP/1.0\nUser_Agent: HM-XMLRPC-Client\nHost: ".$this->host."\nContent-Type: text/xml\n";
            
            if($this->username)
            {
                $query .= "Authorization: Basic ".base64_encode($this->username.":".$this->password)."\n";
            }
            
            $query .= "Content-Length: ".strlen($request)."\n\n".$request."\n";

        	if (!@fputs($socket, $query, strlen($query)))
		    {
			    if($retries == 4) throw new XMLRPCException("Error sending data to server.");
			    else
			    {
				    @fclose($socket);
				    $retries++;
				    usleep(50);
				    continue;
			    }
		    }

        	while (!feof($socket) && (time() - $startTime) < 30)
        	{
            		$response .= @fgets($socket);
        	}
        	@fclose($socket);
		    $retries++;
	    }
        if(strncmp($response, "HTTP/1.1 200 OK", 15) === 0) return substr($response, strpos($response, "<"));
        if($response) throw new XMLRPCException("XMLRPC error:\r\n".$response); else throw new XMLRPCException("XMLRPC error: Response was empty.");
    }
    
    /**
     * Sends an XML RPC request to the XML RPC server
     * @param $methodName string Name of the XML RPC method to call
     * @param $params array Array of parameters to pass to the XML RPC method. Type is detected automatically. For a struct just encode an array within $params.
     * @return array Array of returned parameters
     */
    public function send($methodName, $params)
    {
        $request = xmlrpc_encode_request($methodName, $params);
        $response = $this->sendRequest($request);
        $response = xmlrpc_decode(trim($response)); //Without the trim function returns null
        return $response;
    }
}

?>