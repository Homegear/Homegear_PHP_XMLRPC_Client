Usage:

Also see: https://www.homegear.eu/index.php/Homegear_Reference

= Built-in script engine =

Execute "hg_invoke" to call Homegear's RPC methods:

	hg_invoke("setInterface", 142, "My-CRC");
	
The following shortcut functions are available: hg_set_system, hg_get_system, hg_set_meta, hg_get_meta, hg_set_value, hg_get_value.

To execute you can either use the RPC method "runScript" or "homegear -e runScript YourScript.php".

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
