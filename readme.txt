=== Admin Block Country ===
Contributors: MMDeveloper, The Marketing Mix Osborne Park Perth
Donate link: 
Tags: security, block, country
Requires at least: 3.3
Tested up to: 4.0
Stable tag: 6.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Block access to your admin pages by country.

== Description ==

Easy to use plugin, that blocks access to your wp-admin area by country. Uses geoip-api-php as the library to work out the visitor's country.

Built by The Marketing Mix Perth: http://www.marketingmix.com.au

== Installation ==

1) Install WordPress 4.0 or higher

2) Download the latest from:

http://wordpress.org/extend/plugins/admin-block-country

3) Login to WordPress admin, click on Plugins / Add New / Upload, then upload the zip file you just downloaded.

4) Activate the plugin.


Built by The Marketing Mix Perth: http://www.marketingmix.com.au


== Changelog ==

= 6.0 =

* I removed Who country service. You can now upload your own local ip database from maxmind. I've added in the instructions.

= 5.2 =

* New IP to country service - utrace. Example http://xml.utrace.de/?query=183.60.244.29

= 5.0 =

* Removed Tom M8te dependency.

= 4.3 =

* Fixed bug with Admin "Select All" checkbox. Played havac with service selector. Never noticed it before.

= 4.2 =

* Fixed bug with geoplugin.

= 4.1 =

* Added in ipcountry.marketingmix.com.au ip to country service which is a server that I own. I noticed that the existing 2 have failed atleast once.

= 4.0 =

* Tried Maxmind in version 3.0, but 2 of my clients couldn't use it, so I've ditched it and now I use two external services: http://who.is, http://www.geoplugin.net.

= 3.0 =

* Uses another method for discovering the country of an ip address. 

= 2.0 =

* Used a different method for discovering the country of an ip address. Seems to be less memory intensive.

= 1.0 =

* Initial Commit

== Upgrade notice ==

= 6.0 =

* I removed Who country service. You can now upload your own local ip database from maxmind. I've added in the instructions.

= 5.0 =

* Removed Tom M8te dependency.

= 4.3 =

* Fixed bug with Admin "Select All" checkbox. Played havac with service selector. Never noticed it before.

= 4.2 =

* Fixed bug with geoplugin.

= 4.1 =

* Added in ipcountry.marketingmix.com.au ip to country service which is a server that I own. I noticed that the existing 2 have failed atleast once.

= 4.0 =

* Tried Maxmind in version 3.0, but 2 of my clients couldn't use it, so I've ditched it and now I use two external services: http://who.is, http://www.geoplugin.net.

= 3.0 =

* Uses another method for discovering the country of an ip address. 

= 2.0 =

* Used a different method for discovering the country of an ip address. Seems to be less memory intensive.

= 1.0 =

* Initial Commit