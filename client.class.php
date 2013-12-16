<?php

/**
 * Client identification class
 *
 * @author Sidney Liebrand <sidneyliebrand@gmail.com>
 * @version 1.0.0
 *
 * This class initiates itself, all you have to do is call it's methods e.g. Client::$Browser->Name e.t.c.'
 * 
 */

	class Client {
		
		public static $Browser = null;
		
		public static $System = null;
		//Benchmarking for the class, shows microtime and memory in bytes for preformance optimalisation.
		public static $Execution = null;
		
		protected static $Compatibility = 0;
		//Multiple matches will be in the array below, first will be the browser name, everything after will be an altName
		protected static $nameMatches = array();
		//Arrays with browsers can be added here according to the UA string, this is useful if you want to expand the list of browsers.
		protected static $browsers = array(
			'Opera' => array( //Browser patterns / definitions.
				'name' => '/(OPR)|(Opera)/i', //Regex to check browser name.
				'version' => '/(\sOPR\/([0-9\.]+))|(\sVersion\/([0-9\.]+))|(\sOpera\/([0-9\.]+))|(\sOpera\s([0-9\.]+))/i', //Regex pattern to determine the browsers version.
				'mobile' => '/mobi|mini/i', //Patterns to check if this has a mobile version, fill in null if there is no mobile browser.
				'engine' => array( //Engine patterns / definitions.
					'name' => '/(\sGecko)|((\s|)Presto)|(\sAppleWebKit)/i', //Regex to check which engine the browser is using.
					'version' => '/(\sAppleWebKit\/([0-9\.]+)\s)|(\srv:([0-9\.]+))|((\s|)Presto\/([0-9\.]+)\s)/i'  //Engine version regex.
				)
			),
			'Msie' => array(
				'name' => '/MSIE|uZardWeb|Crazy\sBrowser|GreenBrowser|Trident/i', 
				'version' => '/(\s|)MSIE\s([0-9\.b]+)|\srv:([0-9\.]+)/',
				'mobile' => '/IEMobile/',
				'engine' => array(
					'name' => '=Trident', //If the engine is known or always the same, you can use an = (equal) sign followed by the name, to skip the usage of regex.
					'version' => '/\sTrident\/([0-9\.]+)(;|\s|)/i'
				)
			),
			'Maxthon' => array(
				'name' => '/Maxthon/i',
				'version' => '/\sMaxthon(\/|[\s]{1})([0-9\.]+)/i',
				'mobile' => null,
				'engine' => array(
					'name' => '/(\sGecko)|(\sPresto)|(\sAppleWebKit)|(Trident)/i',
					'version' => '/(\sTrident\/([0-9\.]+))|(\sAppleWebKit\/([0-9\.]+))/i'
				)
			),
			'Firefox' => array(
				'name' => '/Firefox|Iceweasel|Firebird|IceCat|Netscape|SeaMonkey|Conkeror|Kazehakase|EnigmaFox|Fennec|Maemo|Minimo/i',
				'version' => '/\sFirefox\/([0-9\.]+)|Iceweasel\/([0-9\.]+)|Firebird\/([0-9\.]+)|IceCat\/([0-9\.]+)|Netscape\/([0-9\.]+)|SeaMonkey(\/[0-9\.]+)|Conkeror(\/[0-9\.]+)|Kazehakase(\/[0-9\.]+)/i',
				'mobile' => '/mobile|tablet/i',
				'engine' => array(
					'name' => '=Gecko',
					'version' => '/\srv:([0-9\.]+)/i'
				)
			),
			'Konqueror' => array(
				'name' => '/Konqueror/',
				'version' => '/Konqueror\/([0-9\.]+)/',
				'mobile' => null,
				'engine' => array(
					'name' => '=Gecko',
					'version' => '/KHTML\/([0-9\.]+)/i'
				)
			),
			'Rockmelt' => array(
				'name' => '/RockMelt/i',
				'version' => '/\sRockMelt\/([0-9\.]+)/i',
				'mobile' => null,
				'engine' => array(
					'name' => '=Webkit',
					'version' => '/\sAppleWebKit\/([0-9\.]+)/i'
				)
			),
			'Chrome' => array(
				'name' => '/ChromePlus|Chrome|Comodo|Iron/i',
				'version' => '/\sChrome\/([0-9\.]+)/i',
				'mobile' => '/mobile/i',
				'engine' => array(
					'name' => '=Webkit',
					'version' => '/\sAppleWebKit\/([0-9\.]+)/i'
				)
			),
			'Facebook' => array(
				'name' => '/FB/i',
				'version' => '/\;FBAV\/([0-9\.]+)/i',
				'mobile' => '/mobile/i',
				'engine' => array(
					'name' => '=Webkit',
					'version' => '/\sAppleWebKit\/([0-9\.]+)/i'
				)
			),
			'Safari' => array(
				'name' => '/Safari|Dorothy|Iris|Skyfire|TeaShark|Bolt|iCab|OmniWeb|Arora/i',
				'version' => '/\sVersion\/([0-9\.]+)|\sSafari\/([0-9\.]+)/i',
				'mobile' => '/mobile/i',
				'engine' => array(
					'name' => '=Webkit',
					'version' => '/\sAppleWebKit\/([0-9\.]+)/i'
				)
			),
			'Lynx' => array(
				'name' => '/Lynx/i',
				'version' => '/Lynx\/([0-9a-z\.]+)/i',
				'mobile' => null,
				'engine' => array(
					'name' => null,
					'version' => null
				)
			),
			'Amaya' => array(
				'name' => '/Amaya/i',
				'version' => '/Amaya\/([0-9a-z\.]+)/i',
				'mobile' => null,
				'engine' => array(
					'name' => null,
					'version' => null
				)
			),
			'Netpositive' => array(
				'name' => '/NetPositive/i',
				'version' => '/NetPositive\/([0-9\.]+)/i',
				'mobile' => null,
				'engine' => array(
					'name' => null,
					'version' => null
				)
			)
		);
		
		//This function calls the initialisation methods and created empty objects to be filled.
		public static function init() {
	
			//Benchmarking
			self::$Execution = new Stdclass;
			self::$Execution->Time = new Stdclass();
			self::$Execution->Memory = new Stdclass();

			//Benchmark, start.
			self::setExecutionStart();

			self::$Browser = new Stdclass;
			self::$Browser->Engine = new Stdclass;
			self::$Browser->Encoding = new Stdclass;
			self::$Browser->ChromeFrame = new Stdclass;
			self::$System = new Stdclass;
			//getUserAgent(); function can take any useragent between the brackets to test regexes etc.
			self::getUserAgent();
			//Here we do the actual information gathering which will fill the objects.
			self::setInfo();

			//Benchmark, end.
			self::setExecutionEnd();
		}

		//Check if browser checks encoding.
		public static function acceptsEncoding($mtd = 'gzip') {
			return in_array($mtd, self::$Browser->Encoding->Options) ? true : false;
		}

		//Cloning this class is quite useless as well as constructing it.
		//Static classes are slightly faster since they are only initialized once.
		protected function __construct() {}
		protected function __clone() {}

		protected static function getUserAgent($userAgent = null) {
			$pattern = '/Mozilla\/[0-9\.]+(\s|)/'; //Stript legacy mozilla + version tag this has become useless.
			if ($userAgent === null || strlen($userAgent) === 0)
				$userAgent = $_SERVER['HTTP_USER_AGENT'];
			self::$Browser->UserAgent = preg_replace($pattern, '', $userAgent);
		}

		protected static function setInfo() {
			//Execute all the functions.
			self::setCompatibility();
			self::setBrowserName();
			self::setAltBrowserName(); //This function gets secondary names, e.g. Waterfox as an alternate to Firefox.
			self::setBrowserVersion();
			self::setEngineName();
			self::setEngineVersion();
			self::setChromeFrame(); //Checks if ChromeFrame is enabled (deprecated and soon to be removed as it's no longer being developed.)
			self::setChromeFrameVersion();
			self::setBrowserEncoding();
			self::setBrowserLanguage();
			self::setClientSystem(); //Estimated guess of used OS and architecture (x64 / x32)
			self::setMobile();
			self::setHTMLClasses(); //Has all the gathered information formatted as classes.
		}

		protected static function setExecutionStart() {
			self::$Execution->Time->Start = microtime(true);
			self::$Execution->Memory->Start = memory_get_usage();
		}

		protected static function setExecutionEnd() {
			self::$Execution->Time->End = microtime(true);
			self::$Execution->Memory->End = memory_get_usage();

			self::$Execution->Time->Total = (self::$Execution->Time->End - self::$Execution->Time->Start);
			self::$Execution->Memory->Total = (self::$Execution->Memory->End - self::$Execution->Memory->Start);
		}

		protected static function setCompatibility() {
			self::$Compatibility = preg_match('/compatible(;|)/i', self::$Browser->UserAgent) ? 1 : 0;
		}

		protected static function setMobile() {
			self::$Browser->Mobile = 0;
			if (isset(self::$browsers[self::$Browser->Name])) {
				$pattern = self::$browsers[self::$Browser->Name]['mobile'];
				if ($pattern === null)
					return;
				if (preg_match($pattern, self::$Browser->UserAgent))
					self::$Browser->Mobile = 1;
			}
		}

		protected static function setBrowserName() {
			$detected = false;
			while ($detected === false && list($name, $pattern) = each(self::$browsers)) {
				if (preg_match_all($pattern['name'], self::$Browser->UserAgent, $mts))
					$detected = true;
			}
			self::$nameMatches = self::arrayClean(array_unique($mts[0]));
			self::$Browser->Name = $detected ? ucfirst(strtolower($name)) : null;
		}

		protected static function setAltBrowserName() {
			self::$Browser->AltName = null;
			$alts = self::$nameMatches;
			if (count($alts) > 1) {
				foreach ($alts as $alt)
					if (ucfirst(strtolower($alt)) !== self::$Browser->Name)
						self::$Browser->AltName .= $alt .' ';
			}
			self::$Browser->AltName = substr(self::$Browser->AltName, 0, -1);
		}

		protected static function setBrowserVersion() {
			self::$Browser->Version = null;
			self::$Browser->MajorVersion = null;
			$pattern = isset(self::$browsers[self::$Browser->Name]['version']) ? self::$browsers[self::$Browser->Name]['version'] : null;
			if ($pattern === null)
				return null;

			preg_match($pattern, self::$Browser->UserAgent, $version);
			$diff = (self::$Browser->Name === 'Opera');
			$version = self::arrayClean($version);
			self::$Browser->Version = isset($version[($diff ? 2 : 1)]) ? $version[($diff ? 2 : 1)] : (isset($version[0]) ? $version[0] : null);
			self::$Browser->MajorVersion = self::setMajorVersion(self::$Browser->Version);
		}

		protected static function setEngineName() {
			$engine = isset(self::$browsers[self::$Browser->Name]['engine']['name']) ? self::$browsers[self::$Browser->Name]['engine']['name'] : null;
			
			self::$Browser->Engine->Name = null;
			if ($engine === null)
				return null;

			if (self::firstCharIs('=', $engine))
				self::$Browser->Engine->Name = substr($engine, 1);
			else {
				preg_match($engine, self::$Browser->UserAgent, $engArr);
				$engArr = arrayClean($engArr[1]);
				$ENGINE = isset($engArr) ? self::$engArr : null;
				self::$Browser->Engine->Name = $ENGINE;
			}
		}

		protected static function setEngineVersion() {
			self::$Browser->Engine->Version = null;
			self::$Browser->Engine->MajorVersion = null;
			$pattern = isset(self::$browsers[self::$Browser->Name]['engine']['version']) ? self::$browsers[self::$Browser->Name]['engine']['version'] : null;

			if (self::$Browser->Name === 'Msie' && self::$Browser->MajorVersion == 7) {
				self::$Browser->Engine->Version = 3.1;
				self::$Browser->Engine->MajorVersion = 3;
				return true;
			} else if ($pattern === null)
				return null;

			preg_match($pattern, self::$Browser->UserAgent, $engineVersion);
			$diff = (self::$Browser->Name === 'Opera' || self::$Browser->Name === 'Maxthon');
			$ev = self::arrayClean($engineVersion);
			$engineVersion = (isset($ev[($diff ? 2 : 1)]) ? $ev[($diff ? 2 : 1)] : null);
			self::$Browser->Engine->Version = isset($engineVersion) ? $engineVersion : null;
			self::$Browser->Engine->MajorVersion = self::setMajorVersion(self::$Browser->Engine->Version);
		}

		protected static function setBrowserLanguage() {
			self::$Browser->Lang = null;
			if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE']))
				self::$Browser->Lang = strtoupper(substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2));
		}

		protected static function setBrowserEncoding() {
			self::$Browser->Encoding->Accept = 0;
			self::$Browser->Encoding->Options = null;
			if (isset($_SERVER['HTTP_ACCEPT_ENCODING'])) {
				$mtd = explode(',', str_replace(' ', '', $_SERVER['HTTP_ACCEPT_ENCODING']));
				self::$Browser->Encoding->Accept = 1;
				self::$Browser->Encoding->Options = $mtd;
			}
		}

		protected static function setClientSystem() {
			$ua = self::$Browser->UserAgent;
			if (self::$Compatibility == 1)
				$ua = str_replace('compatible', '', self::$Browser->UserAgent);

			preg_match('/(\s|)([a-zA-Z][a-zA-Z][a-z]+[a-zA-Z]+)(;|(\s|\)))/', $ua, $os);
			self::$System->CPU = self::setClientCpuClass();
			self::$System->IP = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : null;
			$os = self::arrayClean($os);
			self::$System->ID = isset($os[1]) ? $os[1] : null;
			self::$System->Port = isset($_SERVER['REMOTE_PORT']) ? $_SERVER['REMOTE_PORT'] : null;
			self::$System->Name = gethostname();
		}

		protected static function setClientCpuClass() {
			$x64 = '/(x64)|(x86_64)|(WOW64)|(Win64)/i';
			if (preg_match($x64, self::$Browser->UserAgent))
				return 64;
			return 32;
		}

		protected static function setChromeFrame() {
			if (preg_match('/Chromeframe/i', self::$Browser->UserAgent))
				self::$Browser->ChromeFrame->Enabled = 1;
			else
				self::$Browser->ChromeFrame->Enabled = 0;
			self::setChromeFrameVersion();
		}

		protected static function setChromeFrameVersion() {
			self::$Browser->ChromeFrame->Version = null;
			self::$Browser->ChromeFrame->MajorVersion = null;
			if (self::$Browser->ChromeFrame->Enabled === 0)
				return null;
			preg_match('/Chromeframe\/([0-9\.]+)/i', self::$Browser->UserAgent, $matches);
			self::$Browser->ChromeFrame->Version = $matches[1];
			self::$Browser->ChromeFrame->MajorVersion = self::setMajorVersion($matches[1]);
		}

		protected static function setHTMLClasses() {
			$classStr = 'no-js';
			if (self::$Browser->ChromeFrame->Enabled === 1) {
				$classStr .= ' cf ';
				if (self::$Browser->ChromeFrame->MajorVersion !== null)
					$classStr .= 'cf'. self::$Browser->ChromeFrame->MajorVersion .' ';
			}
			$classStr .= ' '. self::$Browser->Name;
			$classStr .= ' '. self::$Browser->Name.self::$Browser->MajorVersion;
			$classStr .= strlen(self::$Browser->AltName) > 0 ? ' '. str_replace(' ', '', self::$Browser->AltName) : '';
			$classStr .= ' '. self::$Browser->Engine->Name;
			$classStr .= ' '. self::$Browser->Engine->Name.self::$Browser->Engine->MajorVersion;
			$classStr .= self::$Browser->Mobile == 1 ? ' Mobile' : '';
			if(self::$System->ID) {
				$sysID = self::$System->ID;
				if (preg_match('/(mac|win)/i', $sysID))
					$sysID = substr($sysID, 0, 3);
				$classStr .= ' '. $sysID;
				$classStr .= ' '. $sysID.self::$System->CPU;
			}
			$classStr .= (self::$Browser->Lang !== null ? ' '.self::$Browser->Lang : '');
			$classStr = implode(' ', array_unique(explode(' ', $classStr)));
			$classStr = preg_replace('/[\s]+/', ' ', $classStr);
			self::$Browser->HTMLClasses = preg_replace('/Unknown[a-z0-9]+\s/i', '', strtolower(trim($classStr)));
		}

		//Hieronder custom functies voor het formatteren van Data binnen deze class.
		protected static function setMajorVersion($fullversion) {
			preg_match('/([0-9a-zA-Z]+)\./i', $fullversion, $versionArray);
			return isset($versionArray[1]) ? $versionArray[1] : null;
		}

		protected static function firstCharIs($char, $string) {
			return (substr($string, 0, 1) === $char) ? true : false;
		}

		protected static function arrayClean($array = null) {
			if (!is_array($array))
				return null;
			foreach ($array as $key => $value)
				$array[$key] = trim($value);
			$array = array_filter($array);
			$array = array_values($array);
			return $array;
		}
	}
	Client::init(); //Initializes itself, you won't have to do this anymore as all the variables will be set.
