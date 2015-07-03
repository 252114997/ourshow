<?php

/**
 * @brief reference https://github.com/firefly2442/phpdhcpd
 *
 */
class ParserDhcpdLeasesClass
{
	//Create a 2-dimensional table for the dhcplease file
	public $dhcptable = array(array());
	//Number of entries to display after filtering
	public $filtered_number_display = 0;

	public function parser($open_file)
	{
		$line_number = 1;
		$row_array = array();
		while (!feof($open_file))
		{	
			$read_line = fgets($open_file, 4096);
			if (substr($read_line, 0, 1) != "#") //check for comment (skip)
			{
				$tok = strtok($read_line, " ");
				if ($tok == "lease")
				{
					$row_array[$line_number] = $this->initialize_array();
					$row_array[$line_number]["id"] = $line_number;
					$row_array[$line_number][$tok] = strtok(" ");
				}
				else if ($tok == "starts")
				{
					$day = $this->intToDay(strtok(" "));
					$row_array[$line_number][$tok] = strtok(" ") . " ";
					$time = strtok(" ");
					$time = str_replace(array(";", "\n"), "", $time);
					$row_array[$line_number][$tok] = $row_array[$line_number][$tok].$time;
					//$row_array[$line_number][$tok] = $row_array[$line_number][$tok]."(".$day.")";
				}
				else if ($tok == "ends")
				{
					$day = $this->intToDay(strtok(" "));
					$row_array[$line_number][$tok] = strtok(" ") . " ";
					$time = strtok(" ");
					$time = str_replace(array(";", "\n"), "", $time);
					$row_array[$line_number][$tok] = $row_array[$line_number][$tok].$time;
					//$row_array[$line_number][$tok] = $row_array[$line_number][$tok]."(".$day.")";
				}	
				else if ($tok == "tstp")
				{
					$day = $this->intToDay(strtok(" "));
					$row_array[$line_number][$tok] = strtok(" ") . " ";
					$time = strtok(" ");
					$time = str_replace(array(";", "\n"), "", $time);
					$row_array[$line_number][$tok] = $row_array[$line_number][$tok].$time;
					//$row_array[$line_number][$tok] = $row_array[$line_number][$tok]."(".$day.")";
				}
				else if ($tok == "hardware")
				{
					$row_array[$line_number][$tok] = strtok(" ") . " - ";
					$MAC = strtok(" ");
					$MAC = str_replace(array(";", "\n", "ethernet - "), "", $MAC);
					$MAC = strtoupper($MAC);
				
					$row_array[$line_number][$tok] = $MAC;
					//$row_array[$line_number][$tok] .= " (".$this->getmacvendor($MAC).")";
				}
				else if ($tok == "uid")
				{
					$uid = strtok(" ");
					$replace = array(".", "\n", "\"", ";");
					$uid = str_replace($replace, "", $uid);
					$row_array[$line_number][$tok] = $uid;
				}
				else if ($tok == "client-hostname")
				{
					$hostname = strtok(" ");
					$replace = array("\"", "\n", ";");
					$hostname = str_replace($replace, "", $hostname);
					$row_array[$line_number][$tok] = $hostname;
				}
				else if ($tok == "}\n")
				{
					//$row_array[$line_number][6] = $row_array[$line_number][6];
					$line_number++;
				}
			}
		}
	
		$this->dhcptable = $row_array;
		// echo(json_encode($row_array));
		return $row_array;
	}


	private function intToDay($integer)
	{
		if ($integer == 0)
		return "Sunday";
		else if ($integer == 1)
		return "Monday";
		else if ($integer == 2)
		return "Tuesday";
		else if ($integer == 3)
		return "Wednesday";
		else if ($integer == 4)
		return "Thursday";
		else if ($integer == 5)
		return "Friday";
		else
		return "Saturday";
	}

	private function initialize_array()
	{
		$row_array = array();
		$key_array = array("lease", "client-hostname", "ends", "starts", "tstp", "uid", "hardware");
		foreach($key_array as $key) {
			$row_array[$key] = "-";
		}
		return $row_array;
	}

	private function compare_ip($a, $b) 
	{
		return strnatcmp($a[0], $b[0]);
	}

	private function compare_start_time($a, $b) 
	{
		return strnatcmp($a[1], $b[1]);
	}

	private function compare_end_time($a, $b) 
	{
		return strnatcmp($a[2], $b[2]);
	}

	private function compare_lease_expire($a, $b) 
	{
		return strnatcmp($a[3], $b[3]);
	}

	private function compare_mac($a, $b)
	{ 
		return strnatcmp($a[4], $b[4]);
	}

	private function compare_uid($a, $b)
	{
		return strnatcmp($a[5], $b[5]);
	}

	private function compare_hostname($a, $b)
	{
		return strnatcmp($a[6], $b[6]);
	}

	private function getmacvendor($mac_unformated)
	{
		return "Unknown device"; // TODO use vendor file

		require("config.php");
		if ($mac_vendor == true)
		{
			//Can be retrived on nmap http://nmap.org/book/nmap-mac-prefixes.html
			//or via http://standards.ieee.org/develop/regauth/oui/oui.txt
			//Location of the mac vendor list file
			$mac_vendor_file = "./nmap-mac-prefixes";
			$mac_vendor_file_cache = "./nmap-mac-prefixes_cache";

			$mac = substr(strtoupper(str_replace(array(":"," ","-"), "", $mac_unformated)),0,6);

			if ($cache_vendor_results) {
				// Open the MAC VENDOR CACHE file
				$open_file_cache = fopen($mac_vendor_file_cache, "r") or die("Unable to open MAC VENDOR CACHE file.");

				// First try to lookup the vendor in the cache file
				if ($open_file_cache) {
					while (!feof($open_file_cache)) {
						 $read_line = fgets($open_file_cache, 4096);
						 if (substr($read_line, 0, 6) == $mac) {
							return substr($read_line, 7, -1);
						 }
					}
					fclose($open_file_cache);
				}
			}

			// Second do regular lookup in the main file
			$open_file = fopen($mac_vendor_file, "r") or die("Unable to open MAC VENDOR file.");
			if ($open_file) {
				//open vendor cache file for writing (appending)
				if ($cache_vendor_results && is_writable($mac_vendor_file_cache)) {
					$open_file_cache_a = fopen($mac_vendor_file_cache, "a") or die("Unable to open MAC VENDOR CACHE file for writing.");
				}
				while (!feof($open_file)) {
					 $read_line = fgets($open_file, 4096);
					 if (substr($read_line, 0, 6) == $mac) {
						if ($cache_vendor_results && is_writable($mac_vendor_file_cache)) {
							//write the "hit" to the cache file
							fwrite($open_file_cache_a, $read_line);
						}
						return substr($read_line, 7, -1);
					 }
				}
				fclose($open_file);
				fclose($open_file_cache_a);
			}
			return "Unknown device";
		} else {
			return "Vendor Check Disabled";
		}
	}

	private function checkActiveLease($dhcp_line)
	{
		//Returns true or false depending on if the lease is currently active or not
		$leaseStart = strtotime(substr($dhcp_line[1], 0, strpos($dhcp_line[1], "(")));
		$leaseEnd = strtotime(substr($dhcp_line[2], 0, strpos($dhcp_line[2], "(")));

		if (time() >= $leaseStart && time() <= $leaseEnd) {
		  return true;
		} else {
			return false;
		}
	}

}
?>