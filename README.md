>fritzco
=======

a set of scripts to add functionality to a Cisco 99xx phone connected to fritzboxes

Requirements:
-------------
* Webserver
* PHP >=5.3

The documentation can be found at: https://github.com/SkyhawkXava/fritzco/wiki

How to Install:
---------------
1. Put all files on your webserver
2. Add write permission to folder "/books" and "/log"
3. Change credentials in "/config/*.config.inc.php"
4. Add this to your SEP<MAC>.cnf
```xml
	    <phoneServices>
	        <provisioning>2</provisioning>
	        <phoneService  type="1" category="0">
	            <name>Telefonbuch</name>
	            <url>http://yourServer.abc/directory.php</url>
	            <vendor></vendor>
	            <version></version>
	        </phoneService>
	    </phoneServices>
```
