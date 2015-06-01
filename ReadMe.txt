Usage:

Also see: https://www.homegear.eu/index.php/Homegear_Reference

= Built-in script engine =

Visit https://www.homegear.eu/index.php/Homegear_Reference#Homegear_0.6 for more information.

See Test.php for an example.

= RPC calls over TCP socket connection =

Require the file "Client.php" somewhere at the top of your PHP file:

	require_once("Client.php");

Create a new instance of the client with:

	With SSL:
	$Client = new \XMLRPC\Client(HOSTNAME, PORT, true);

	Without SSL:
	$Client = new \XMLRPC\Client(HOSTNAME, PORT, false);

SSL options:
	To enable/disable certificate verification:
		$Client->setSSLVerifyPeer(true);
	
	To set the path to your CA certificate (needed when certificate verification is enabled):
		$Client->setCAFile("/path/to/ca.crt");
		
	To set your Homegear username and password:
		$Client->setUsername(USERNAME);
		$Client->setPassword(PASSWORD);
	
And then invoke XML RPC methods with:

	$Client->send("METHODNAME", array(PARAMETERS));
	
See Test.php for an example.
