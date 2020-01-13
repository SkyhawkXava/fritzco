<?php

namespace logging;

class logFile{
	public $logFormat = "txt";
	public $path = null;
	public $bookFolderWritable = true;
	public $logActivated = true;

	public function __construct($logswitch, $logformat, $logpath) {
		$this->logActivated = $logswitch;
		$this->logFormat = $logformat;

		if (is_writable($logpath)) {
			$this->path = $logpath;
		} else {
			$this->path = null;
			$this->bookFolderWritable = false;
		}
		$this->introduceEntry();
	}

	public function introduceEntry() {
		$this->newEntry("--- START LOGGING ---");

		// Log name of operating system
		$this->newEntry("operation system: ".php_uname());

		// Log name of webserver
		$this->newEntry("webserver - name: ".$_SERVER['SERVER_SIGNATURE']);

		// Log user of webserver
		If (ini_get('safe_mode')) {
			$this->newEntry("PHP safe mode is activated");
			if (function_exists('posix_getpwuid')) {
				$webserver_username = posix_getpwuid(posix_geteuid());
			} else {
				$webserver_username = getenv('USERNAME');
			}
		} else {
			$webserver_username = exec('whoami');
		}
		$this->newEntry("webserver - user: ".$webserver_username);
	}

	public function newEntry($msg) {
		if ($this->bookFolderWritable && $this->logActivated) {
			$date_time = date("d.m.Y H:i:s");
			$filename=$this->path."fritzco.log";
			$header = array("Datum", "Message");
			$infos = array($date_time, $msg);

			if($this->logFormat == "csv") {
				$entry= '"'.implode('", "', $infos).'"';
			} else {
				$entry = implode("\t", $infos);
			}

			$write_header = !file_exists($filename);
			$file=fopen($filename,"a");
			if($write_header) {
				if($this->logFormat == "csv") {
					$header_line = '"'.implode('", "', $header).'"';
				} else {
					$header_line = implode("\t", $header);
				}

				fputs($file, $header_line."\n");
			}

			fputs($file,$entry."\n");
			fclose($file);
		}
	}
}
