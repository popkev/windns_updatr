# windns_updatr
Generates DNS record update script in powershell by GET request. Written in PHP.
## Requirements
* Windows Server with DNS Serivce installed, domain zone created.
* Target directory for generated powershell script file shall be writable by IUSR.
* Also pre-create an empty powershell script file.
* Schedule task to run the powershell script and Clear-Content of it.
* Tested under PHP 5.6.40
## Notes
* DB file in JSON format, easy to maintain but __remember to keep from public__.
