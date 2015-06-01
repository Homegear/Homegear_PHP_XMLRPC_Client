# Homegear PHP XMLRPC Client

Also see: https://www.homegear.eu/index.php/Homegear_Reference

## Built-in script engine

See Example.php.

Visit https://www.homegear.eu/index.php/Homegear_Reference#Homegear_0.6 for more information.

## RPC calls over TCP socket connection

### Import the XML RPC client class

Require the file "Client.php" somewhere at the top of your PHP file:

```PHP
require_once("Client.php");
```

### Create a new instance of the client

#### With SSL
```PHP
$Client = new \XMLRPC\Client(HOSTNAME, PORT, true);
```

#### Without SSL
```PHP
$Client = new \XMLRPC\Client(HOSTNAME, PORT, false);
```

#### SSL options
To enable/disable certificate verification:
```PHP
$Client->setSSLVerifyPeer(true);
```
	
To set the path to your CA certificate (needed when certificate verification is enabled):
```PHP
$Client->setCAFile("/path/to/ca.crt");
```
		
To set your Homegear username and password:
```PHP
$Client->setUsername(USERNAME);
$Client->setPassword(PASSWORD);
```
	
### Call XML RPC methods

After creating the XML RPC client object, you can invoke XML RPC methods with:
```PHP
$Client->send("METHODNAME", array(PARAMETERS));
```

Also see Example.php.
