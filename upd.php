<?php
$keyStr = $_GET['k'];
$keyFile = "full_path_to_your_secrets.json";
$scriptFile = "full_path_to_your_output_script.ps1";

if(!file_exists($keyFile)){
	return;
}
if(!file_exists($scriptFile)){
	touch($scriptFile);
}
$keyContent=file_get_contents($keyFile);
$keys = json_decode($keyContent, true);
$dmCfg = $keys[$keyStr];
if(!$dmCfg){
	echo "Wrong Key";
	return;
}
$ip=$_SERVER['REMOTE_ADDR'];
try{
	$cmd0 = "Remove-DnsServerResourceRecord -ZoneName {$dmCfg['zone']} -Name {$dmCfg['domain']} -RRType {$dmCfg['type']} -Force\n";
	file_put_contents($scriptFile, $cmd0, FILE_APPEND);
} catch (Exception $e) {
	echo "Error {$e->getMessage()}";
}
if(filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)){
	if ($dmCfg['type'] == "A") {
		echo "Wrong Type";
		return;
	}
	try{
		$cmd1=("Add-DnsServerResourceRecord -ZoneName {$dmCfg['zone']} -Name {$dmCfg['domain']} -AAAA -IPv6Address {$ip}\n");
		file_put_contents($scriptFile, $cmd1, FILE_APPEND);
		echo "OK {$ip}";
	} catch (Exception $e) {
		echo "Error {$e->getMessage()}";
	}
} else if(filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
	if ($dmCfg['type'] == "AAAA") {
		echo "Wrong Type";
		return;
	}
	try{
		$cmd2 = "Add-DnsServerResourceRecord -ZoneName {$dmCfg['zone']} -Name {$dmCfg['domain']} -A -IPv4Address {$ip}\n";
		file_put_contents($scriptFile, $cmd2, FILE_APPEND);
		echo "OK {$ip}";
	} catch (Exception $e) {
		echo "Error {$e->getMessage()}";
	}
} else {
	echo "Neither v4 nor v6";
}
