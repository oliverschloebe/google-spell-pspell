<?php
/**
* This is the spell checker class library
*
* @author Sabin Iacob (m0n5t3r) <iacobs@m0n5t3r.info>
* @copyright Copyright &copy; 2006 Sabin Iacob
* @version 0.6
* @license http://creativecommons.org/licenses/GPL/2.0/ GNU General Public License
*/

class GenericSpellChecker {
	//{{{ private properties
	/**
	* @var string HTML to check for spelling errors
	* @access private
	*/
	var $_html;

	/**
	* @var array Plain text &rarr; HTML offset translation table
	* @access private
	*/
	var $_offsets;

	/**
	* @var array Associative array containing spelling suggestions and offset data
	* @access private
	*/
	var $_suggestions;

	/**
	* @var int Length of checked text
	* @access private
	*/
	var $_chars;

	/**
	* @var string Language to check spelling for
	* @access private
	*/
	var $_lang;

	/**
	* @var bool Whether to consider run-rogether words like Wordpress valid
	* @access private
	*/
	var $_runTogether;

	/**
	* @var string Custom personal word list path
	* @access private
	*/
	var $_personal;

	/**
	* @var string Custom replacement list path
	* @access private
	*/
	var $_repl;

	/**
	* @var int Maximum number of suggestions to return
	* @access private
	*/
	var $_maxSuggestions;

	/**
	* @var bool Whether to use a custom main dictionary location
	* @access private
	*/
	var $_customDict;

	/**
	* @var string Custom main dictionary location
	* @access private
	*/
	var $_customDictLocation;

	/**
	* @var string Character set to use
	* @access private
	*/
	var $_charset;

	/**
	* @var string aspell executable path
	* @access private
	*/
	var $_aspellPath;

	/**
	* @var array ISO 639 language codes
	* @access private
	*/
	var $_languageCodes;

	/**
	* @var array supported languages
	* @access private
	*/
	var $_supportedLanguages;
	//}}}

	//{{{ public function GenericSpellChecker($text, &$options)
	/**
	* Decorates __construct for PHP4 compatibility
	* @see __construct()
	*/
	function GenericSpellChecker($text, &$options) {
		$this->__construct();
	}
	//}}}

	//{{{ public function __construct($text, &$options)
	/**
	* @param int $text Text to check for spelling errors (can be HTML)
	* @param array &$options Array containing pairs "option"=>value, where options can be:
	* <ul>
	* <li>string "lang", default "en"</li>
	* <li>bool "runTogether", default true</li>
	* <li>string "personal"</li>
	* <li>string "repl"</li>
	* <li>int "maxSuggestions", default 5</li>
	* <li>bool "customDict"</li>
	* <li>string "customDictLocation"</li>
	* <li>string "charset", default "utf-8"</li>
	* <li>string "aspellPath", default "/usr/bin/aspell"</li>
	* </ul>
	*/
	function __construct($text, &$options){
		$this->_lang = "en";
		$this->_runTogether = true;
		$this->_maxSuggestions = 5;
		$this->_html = $text;
		$this->_offsets = array();
		$this->_suggestions = array();
		$this->_charset = "utf-8";
		$this->_supportedLanguages = array();
		$this->_languageCodes = array	(
		//{{{ language codes, loooong list
										"aa" => "Afar",
										"ab" => "Abkhazian",
										"af" => "Afrikaans",
										"am" => "Amharic",
										"ar" => "Arabic",
										"as" => "Assamese",
										"ay" => "Aymara",
										"az" => "Azerbaijani",
										"ba" => "Bashkir",
										"be" => "Byelorussian",
										"bg" => "Bulgarian",
										"bh" => "Bihari",
										"bi" => "Bislama",
										"bn" => "Bengali; Bangla",
										"bo" => "Tibetan",
										"br" => "Breton",
										"ca" => "Catalan",
										"co" => "Corsican",
										"cs" => "Czech",
										"cy" => "Welsh",
										"da" => "Danish",
										"de" => "German",
										"dz" => "Bhutani",
										"el" => "Greek",
										"en" => "English",
										"eo" => "Esperanto",
										"es" => "Spanish",
										"et" => "Estonian",
										"eu" => "Basque",
										"fa" => "Persian",
										"fi" => "Finnish",
										"fj" => "Fiji",
										"fo" => "Faroese",
										"fr" => "French",
										"fy" => "Frisian",
										"ga" => "Irish",
										"gd" => "Scots Gaelic",
										"gl" => "Galician",
										"gn" => "Guarani",
										"gu" => "Gujarati",
										"ha" => "Hausa",
										"he" => "Hebrew",
										"hi" => "Hindi",
										"hr" => "Croatian",
										"hu" => "Hungarian",
										"hy" => "Armenian",
										"ia" => "Interlingua",
										"id" => "Indonesian",
										"ie" => "Interlingue",
										"ik" => "Inupiak",
										"is" => "Icelandic",
										"it" => "Italian",
										"iu" => "Inuktitut",
										"ja" => "Japanese",
										"jw" => "Javanese",
										"ka" => "Georgian",
										"kk" => "Kazakh",
										"kl" => "Greenlandic",
										"km" => "Cambodian",
										"kn" => "Kannada",
										"ko" => "Korean",
										"ks" => "Kashmiri",
										"ku" => "Kurdish",
										"ky" => "Kirghiz",
										"la" => "Latin",
										"ln" => "Lingala",
										"lo" => "Laothian",
										"lt" => "Lithuanian",
										"lv" => "Latvian, Lettish",
										"mg" => "Malagasy",
										"mi" => "Maori",
										"mk" => "Macedonian",
										"ml" => "Malayalam",
										"mn" => "Mongolian",
										"mo" => "Moldavian",
										"mr" => "Marathi",
										"ms" => "Malay",
										"mt" => "Maltese",
										"my" => "Burmese",
										"na" => "Nauru",
										"ne" => "Nepali",
										"nl" => "Dutch",
										"no" => "Norwegian",
										"oc" => "Occitan",
										"om" => "(Afan) Oromo",
										"or" => "Oriya",
										"pa" => "Punjabi",
										"pl" => "Polish",
										"ps" => "Pashto, Pushto",
										"pt" => "Portuguese",
										"qu" => "Quechua",
										"rm" => "Rhaeto-Romance",
										"rn" => "Kirundi",
										"ro" => "Romanian",
										"ru" => "Russian",
										"rw" => "Kinyarwanda",
										"sa" => "Sanskrit",
										"sd" => "Sindhi",
										"sg" => "Sangro",
										"sh" => "Serbo-Croatian",
										"si" => "Sinhalese",
										"sk" => "Slovak",
										"sl" => "Slovenian",
										"sm" => "Samoan",
										"sn" => "Shona",
										"so" => "Somali",
										"sq" => "Albanian",
										"sr" => "Serbian",
										"ss" => "Siswati",
										"st" => "Sesotho",
										"su" => "Sundanese",
										"sv" => "Swedish",
										"sw" => "Swahili",
										"ta" => "Tamil",
										"te" => "Telugu",
										"tg" => "Tajik",
										"th" => "Thai",
										"ti" => "Tigrinya",
										"tk" => "Turkmen",
										"tl" => "Tagalog",
										"tn" => "Setswana",
										"to" => "Tonga",
										"tr" => "Turkish",
										"ts" => "Tsonga",
										"tt" => "Tatar",
										"tw" => "Twi",
										"ug" => "Uighur",
										"uk" => "Ukrainian",
										"ur" => "Urdu",
										"uz" => "Uzbek",
										"vi" => "Vietnamese",
										"vo" => "Volapuk",
										"wo" => "Wolof",
										"xh" => "Xhosa",
										"yi" => "Yiddish",
										"yo" => "Yoruba",
										"za" => "Zhuang",
										"zh" => "Chinese",
										"zu" => "Zulu"
		//}}}
										);

		foreach($options as $var => $value){
			$var = "_$var";
			$this->$var = $value;
		}

		mb_internal_encoding($this->_charset);
		mb_regex_encoding($this->_charset);
		mb_regex_set_options("z");//pcre syntax

		if($text)
			$this->_buildOffsetTable();
	}
	//}}}

	//{{{ public abstract function checkSpelling()
	/**
	* Checks spelling of the supplied text and builds the suggestions array
	* @abstract
	* @access public
	*/
	function checkSpelling() { trigger_error(__CLASS__ . "::" . __FUNCTION__ . " is an abstract method, implementation required"); }
	//}}}

	//{{{ public abstract function storeReplacement()
	/**
	* Stores a replacement pair in the custom replacement list
	* @abstract
	* @access public
	*/
	function storeReplacement() { trigger_error(__CLASS__ . "::" . __FUNCTION__ . " is an abstract method, implementation required"); }
	//}}}

	//{{{ public abstract function addWord()
	/**
	* Adds a word to the word list
	* @abstract
	* @access public
	*/
	function addWord() { trigger_error(__CLASS__ . "::" . __FUNCTION__ . " is an abstract method, implementation required"); }
	//}}}

	//{{{ protected abstract function _buildSupportedLanguages()
	/**
	* Build the supported languages array
	* @abstract
	* @access protected
	*/
	function _buildSupportedLanguages() { trigger_error(__CLASS__ . "::" . __FUNCTION__ . " is an abstract method, implementation required"); }
	//}}}

	//{{{ public function toJSArray()
	/**
	* Returns a JavaScript object representation of the suggestions array
	* @return string
	* @access public
	*/
	function toJSArray() {
		$js = "new Array(";
		foreach($this->_suggestions as $sug){
			$val = addslashes(join(",",$sug["value"]));
			$val = "'".str_replace(",","','",$val)."'"; //make strings javascript compatible
			$js .= "{o: $sug[o],l: $sug[l],s: $sug[s],value: new Array($val)},";
		}
		$js = (count($this->_suggestions) > 0 ? substr($js,0,strlen($js)-1) : $js).")";
		return $js;
	}
	//}}}

	//{{{ public function toPHPArray()
	/**
	* Returns the suggestions array
	* @return array
	* @access public
	*/
	function toPHPArray() {
		return $this->_suggestions;
	}
	//}}}

	//{{{ public function toXML()
	/**
	* Returns an XML representation of the suggestions array, compatible with
	* the Google toolbar spell checking service output
	* @return string
	* @access public
	*/
	function toXML(){
		$xml = '<spellresult error="0" clipped="0" charschecked="'.$this->_chars.'">';
		foreach($this->_suggestions as $sug){
			$xml .= "<c o=\"$sug[o]\" l=\"$sug[l]\" s=\"$sug[s]\">".join("\t",$sug["value"])."</c>";
		}
		$xml .= '</spellresult>';
		return $xml;
	}
	//}}}

	//{{{ public function languageName()
	/**
	* Given a language code, return the language name
	* @param string $code ISO 639 language code
	* @return string|bool language name or false
	* @access public
	*/
	function languageName($code){
		if(isset($this->_languageCodes[$code]))
			return $this->_languageCodes[$code];
		return false;
	}
	//}}}

	//{{{ public function supportedLanguages()
	/**
	* Return an array containing available dictionaries
	* @return array
	* @access public
	*/
	function supportedLanguages(){
		return $this->_supportedLanguages;
	}
	//}}}

	//{{{ protected function _updateOffsets()
	/**
	* Updates the offsets from the spell sugestions array to fit the input text
	* word positions
	* @access protected
	*/
	function _updateOffsets() {
		for($i = 0; $i < count($this->_suggestions); $i++)
			$this->_suggestions[$i]["o"] = $this->_offsets[$this->_suggestions[$i]["o"]];
	}
	//}}}

	//{{{ private function _buildOffsetTable()
	/**
	* Creates the plain text &rarr; HTML offset translation table
	* @access private
	*/
	function _buildOffsetTable(){
		$offsets = array();
		$notag_offsets = array();
		$off = 0;
		$offsets[] = 0;
		mb_ereg_search_init($this->_html,"((?:<[^>]+>)+|(?:&.+;)+)");
		while(($word = @mb_ereg_search_pos())){
			$word[0] = mb_strlen(mb_strcut($this->_html,0,$word[0])); //hack for a wirdness in mb_ereg_search_pos returning bad offsets
			$off += $word[1];
			$offsets[] = $word[0] + $word[1];
			$notag_offsets[] = $word[0] + $word[1] - $off;
		}
		$off = 0;
		$notag_offsets[] = 10000;
		$this->_chars = mb_ereg_search_getpos();
		$cnt = 0;
		$oadd = -1;
		for($i = 0; $i < $this->_chars && $cnt < count($offsets); $i++){
			if($i < $notag_offsets[$cnt]){
				$oadd++;
				$this->_offsets[$i] = $oadd + $offsets[$cnt];
			} else {
				$cnt++;
				$oadd = 0;
				$this->_offsets[$i] = $offsets[$cnt];
			}
		}
	}
	//}}}
}
//}}}

//{{{ class PspellSpellChecker
/**
* Spell checker class that uses the Pspell PHP library functions
* @package AjaxSpellChecker
* @subpackage WebService
*/
class PspellSpellChecker extends GenericSpellChecker {
	/**
	* Resource ID for the pspell dictionary link
	* @access private
	*/
	var $_pspell;

	//{{{ public function PspellSpellChecker($text, &$options)
	/**
	* Decorates __construct for PHP4 compatibility
	* @see __construct()
	*/
	function PspellSpellChecker($text, &$options) {
		$this->__construct($text, $options);
	}
	//}}}

	//{{{ public function __construct($text, &$options)
	/**
	* @see parent::__construct()
	*/
	function __construct($text, &$options) {
		parent::__construct($text, $options);

		$config = pspell_config_create($this->_lang, "", "", $this->_charset);
		pspell_config_mode($config, PSPELL_FAST);
		pspell_config_runtogether($config, $this->_runTogether);
		if($this->_personal)
			pspell_config_personal($config, $this->_personal);
		if($this->_repl)
			pspell_config_repl($config, $this->_repl);
		if($this->_customDict && function_exists("pspell_config_dict_dir"))
			pspell_config_dict_dir($this->_customDictLocation);

		$this->_pspell = pspell_new_config($config);
		if($text){
			$this->checkSpelling();
		} else {
			$this->_buildSupportedLanguages();
		}
	}
	//}}}

	//{{{ public function checkSpelling()
	function checkSpelling() {
		$text = strip_tags($this->_html);
		$text = html_entity_decode($text);
		$text = mb_ereg_replace("[~&\"#{(\[_\\^@)\]=+,.;/:!%*[:space:][:blank:]]"," ", $text);
		$words = mb_split("\s", $text);
		$off = 0;
		foreach($words as $word) {
			$l = mb_strlen($word);
			if(!pspell_check($this->_pspell, $word)) {
				$sug = array_slice(pspell_suggest($this->_pspell, $word), 0, $this->_maxSuggestions);
				$o = $off;
				$s = 0;
				for($i = 0; $i < count($sug); $i++) {
					if(levenshtein($word,$sug[$i]) == 1) {
						$s = $i + 1;
						break;
					}
				}
				$this->_suggestions[] = array("o" => $o, "l" => $l, "s" => $s, "value" => $sug);
			}
			$off += ($l+1);
		}
		$this->_updateOffsets();
	}
	//}}}

	//{{{ public function storeReplacement($wrong, $right)
	/**
	* @param string $wrong
	* @param string $right
	*
	* the replacement pair to be stored
	*/
	function storeReplacement($wrong, $right){
		pspell_store_replacement($this->_pspell, $wrong, $right);
		pspell_save_wordlist($this->_pspell);
	}
	//}}}

	//{{{ public function addWord($word)
	/**
	* @param string $word the word to be added to custom word list
	*/
	function addWord($word){
		pspell_add_to_personal($this->_pspell, $word);
		pspell_save_wordlist($this->_pspell);
	}
	//}}}

	//{{{ protected function _buildSupportedLanguages()
	/**
	*
	* Pspell provides no way of listing available dictionaries, so we need to do it the hard way.
	* Use of caching on the returned array is highly encouraged.
	*/
	function _buildSupportedLanguages() {
		foreach($this->_languageCodes as $lc=>$ln) {
			$tmp_config = pspell_config_create($lc);
			$tmp_link = @pspell_new_config($tmp_config);
			if($tmp_link !== false)
				$this->_supportedLanguages[$lc] = $ln;
			unset($tmp_config);
			unset($tmp_link);
		}
	}
	//}}}
}
//}}}

//{{{ class AspellSpellChecker
/**
* Checks spelling by passing text to the aspell executable.
*
* Only suited for low traffic sites, as there is an aspell fork for every request
* @package AjaxSpellChecker
* @subpackage WebService
*/
class AspellSpellChecker extends GenericSpellChecker {
	//{{{ private properties
	/**
	* @var array Pipes for communication with aspell
	* @access private
	*/
	var $_pipes;

	/**
	* @var int aspell process resource ID
	* @access private
	*/
	var $_proc;
	//}}}

	//{{{ public function AspellSpellChecker($text, &$options)
	/**
	* Decorates __construct for PHP4 compatibility
	* @see __construct()
	*/
	function AspellSpellChecker($text, &$options) {
		$this->__construct($text, $options);
	}
	//}}}

	//{{{ public function __construct($text, &$options)
	/**
	* @see parent::__construct()
	*/
	function __construct($text, &$options) {
		parent::__construct($text, $options);

		$cmd  = "{$this->_aspellPath} -a";
		$cmd .= " --lang=" . escapeshellarg($this->_lang);
		$cmd .= " --sug-mode=fast";
		if($this->_runTogether)
			$cmd .=" --run-together";
		if($this->_personal)
			$cmd .= " --personal=" . escapeshellarg($this->_personal);
		if($this->_repl)
			$cmd .= " --save-repl --repl=" . escapeshellarg($this->_repl);
		if($this->_customDict)
			$cmd .= " --dict-dir=" . escapeshellarg($this->_customDictLocation);

		$desc = array(	0 => array("pipe","r"),
						1 => array("pipe","w"),
						2 => array("file","/dev/null","w"));

		$this->_proc = proc_open($cmd,$desc,$this->_pipes);

		if(!is_resource($this->_proc))
			trigger_error("Problem running aspell");

		$str = trim(fread($this->_pipes[1],8192));
		stream_set_blocking($this->_pipes[1], false);

		if($text){
			$this->checkSpelling();
			$this->_updateOffsets();
		} else {
			$this->_cleanUp();
			$this->_buildSupportedLanguages();
		}
	}
	//}}}

	//{{{ public function checkSpelling()
	function checkSpelling() {
		$text = strip_tags($this->_html);
		$text = html_entity_decode($text);
		$text = preg_replace("/[^[:alnum:]']/"," ",$text);

		$words = preg_split("/\s/", $text, -1, PREG_SPLIT_OFFSET_CAPTURE);
		foreach($words as $word){
			fwrite($this->_pipes[0], $word[0]."\n");
			fflush($this->_pipes[0]);
			$str = "";

			usleep(100000); //it looks like stream_select is buggy in php4, returning too fast

			stream_select($read = array($this->_pipes[1]), $write = NULL, $except = NULL, 0, 200000);
			$str = trim(fread($this->_pipes[1],8192));

			if(empty($str))
				continue;

			$o = $word[1];
			$l = strlen($word[0]);

			switch($str[0]) {
				case "#":
					$s = 0;
					$this->_suggestions[] = array("o" => $o, "l" => $l, "s" => $s, "value" => array());
					break;
				case "&":
					preg_match("/^& \w+ [0-9]+ ([0-9]+):(.+)$/", $str, $matches);
					$s = $matches[1] + 1;
					$sug = array_slice(explode(", ",$matches[2]), 0, $this->_maxSuggestions);
					$this->_suggestions[] = array("o" => $o, "l" => $l, "s" => $s, "value" => $sug);
					break;
				default:
					continue;
			}
			$str = "";
		}
	}
	//}}}

	//{{{ public function storeReplacement($wrong, $right)
	/**
	* @param string $wrong
	* @param string $right
	*
	* the replacement pair to be stored
	*/
	function storeReplacement($wrong, $right){
		fwrite($this->_pipes[0], "\$\$ra $wrong,$right\n#\n");
		fflush($this->_pipes[0]);
		$this->_cleanUp();
	}
	//}}}

	//{{{ public function addWord($word)
	/**
	* @param string $word the word to be added to custom word list
	*/
	function addWord($word){
		fwrite($this->_pipes[0], "*$word\n#\n");
		fflush($this->_pipes[0]);
		$this->_cleanUp();
	}
	//}}}

	//{{{ protected function _buildSupportedLanguages()
	function _buildSupportedLanguages() {
		$pipes = array();
		$desc = array(	0 => array("pipe","r"),
						1 => array("pipe","w"),
						2 => array("file","/dev/null","w"));

		$cmd = "{$this->_aspellPath} dump dicts";
		$proc = proc_open($cmd, $desc, $pipes);
		$str = "";
		if(is_resource($proc)){
			$str = trim(fread($pipes[1],8192));
			$dicts = explode("\n", $str);
			foreach($dicts as $d){
				$d = trim($d);
				if(strlen($d) == 2) { //keep only main language, no dialect/jargon etc
					$this->_supportedLanguages[$d] = $this->_languageCodes[$d];
				}
			}
		}
	}
	//}}}

	//{{{ private function _cleanUp() method
	/**
	* Close pipes and end aspell process
	* @access private
	*/
	function _cleanUp() {
		fclose($this->_pipes[0]);
		fclose($this->_pipes[1]);
		proc_close($this->_proc);
	}
	//}}}
}
//}}}

//{{{ class GoogleSpellChecker
/**
* Checks spelling using the google toolbar web service
* @package AjaxSpellChecker
* @subpackage WebService
*/
class GoogleSpellChecker extends GenericSpellChecker {
	//{{{ private variables
	/**
	* @var array google supported language codes
	* @access private
	*/
	var $_google_lang;
	//}}}

	//{{{ public function GoogleSpellChecker($text, &$options)
	/**
	* Decorates __construct for PHP4 compatibility
	* @see __construct()
	*/
	function GoogleSpellChecker($text, &$options) {
		$this->__construct($text, &$options);
	}
	//}}}

	//{{{ public function __construct($text, &$options)
	/**
	* @see parent::__construct()
	*/
	function __construct($text, &$options) {
		parent::__construct($text, $options);
		$this->_google_lang = array("da", "de", "en", "es", "fr", "it", "nl", "pl", "pt", "fi", "sv");
		if(!in_array($this->_lang,$this->_google_lang))
			$this->_lang = "en";
		if($text){
			$this->checkSpelling();
			$this->_updateOffsets();
		} else {
			$this->_buildSupportedLanguages();
		}
	}
	//}}}

	//{{{ public function checkSpelling()
	function checkSpelling() {
		$words = strip_tags($this->_html);
		$words = html_entity_decode($words);
		$words = preg_replace("/[^[:alnum:]']/"," ",$words);
		$words = "<spellrequest textalreadyclipped=\"0\" ignoredups=\"1\" ignoredigits=\"1\" ignoreallcaps=\"0\"><text>" . $words . "</text></spellrequest>";
		$server = "www.google.com";
		$port = 443;

		$path = "/tbproxy/spell?lang=".$this->_lang."&hl=".$_this->_lang;
		$host = "www.google.com";

		$url = "https://" . $server;
		$page = $path;

		$post_string = $words;

		$header= "POST ".$page." HTTP/1.0 \r\n";
		$header .= "MIME-Version: 1.0 \r\n";
		$header .= "Content-type: application/PTI26 \r\n";
		$header .= "Content-length: ".strlen($post_string)." \r\n";
		$header .= "Content-transfer-encoding: text \r\n";
		$header .= "Request-number: 1 \r\n";
		$header .= "Document-type: Request \r\n";
		$header .= "Interface-Version: Test 1.4 \r\n";
		$header .= "Connection: close \r\n\r\n";
		$header .= $post_string;

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 120);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $header);

		$data = curl_exec($ch);
		if(curl_errno($ch))
			error_log(curl_error($ch));
		else
			curl_close($ch);

		$vals = array();
		$index = array();

		$xml_parser = xml_parser_create();
		xml_parser_set_option($xml_parser,XML_OPTION_CASE_FOLDING,0);
		xml_parser_set_option($xml_parser,XML_OPTION_SKIP_WHITE,1);
		xml_parse_into_struct($xml_parser, $data, $vals, $index);
		xml_parser_free($xml_parser);

		if(is_array($index["c"])){
			foreach($index["c"] as $idx){
				$val = $vals[$idx]["value"];
				$this->_suggestions[] = array_merge($vals[$idx]["attributes"], array("value" => explode("\t",$val)));
			}
		}
	}
	//}}}

	/**
	* Custom replacement lists not supported with google
	*/
	function storeReplacement($wrong, $right){ /* personal dictionaries not supported*/ }

	/**
	* Custom word lists not supported with google
	*/
	function addWord($word){ /* personal dictionaries not supported*/ }

	//{{{ protected function _buildSupportedLanguages()
	function _buildSupportedLanguages() {
		foreach($this->_google_lang as $l)
			$this->_supportedLanguages[$l] = $this->_languageCodes[$l];
	}
	//}}}

}
//}}}

//{{{ class SpellChecker
/**
* Factory class
*
* to get a spell checker with the default options:
* <pre><code>
* speller = (new SpellChecker())->create($text);
* $suggestions_php = $speller->toPHPArray();
* $suggestions_js = $speller->toJSArray();
* $suggestions_xml = $speller->toXML();
* </code></pre>
*
* @package AjaxSpellChecker
* @subpackage WebService
*/
class SpellChecker {
	//{{{ private properties
	/**
	* @var array options
	* @see GenericSpellChecker::__construct()
	* @access private
	*/
	var $_options;

	/**
	* @var array errors
	* @access private
	*/
	var $_err;

	/**
	* @var array supported backends
	* @access private
	*/
	var $_backends;
	//}}}

	//{{{ public function SpellChecker($params = array()
	/**
	* decorates __construct for PHP4 compatibility
	* @see __construct()
	*/
	function SpellChecker($params = array()) {
		$this->__construct($params);
	}
	//}}}

	//{{{ public function __construct($params = array())
	/**
	* @param array $params
	* for params array contents, {@see GenericSpellChecker::__construct()}
	*/
	function __construct($params = array()) {
		$this->_options = $params;
		$this->_err = array();
		$this->_backends = array("Pspell" => true, "Aspell" => true, "Google" => true);

		if(!is_readable($params["personal"]) && is_writable(dirname($params["personal"]))) {
			$fp = fopen($params["personal"],"w");
			fwrite($fp,"personal_ws-1.1 $params[lang] 0\n");
			fclose($fp);
		}
		if(!is_readable($params["repl"]) && is_writable(dirname($params["repl"]))) {
			$fp = fopen($params["repl"],"w");
			fwrite($fp,"personal_repl-1.1 $params[lang] 0\n");
			fclose($fp);
		}
	}
	//}}}

	//{{{ public function create($text, $backend = false)
	/**
	* Spell checker factory
	*
	* This functions checks for necessary features and selectd the spell
	* checking backend accordingly
	* @param string $text Text to check for spelling mistakes
	* @return object|bool a spell checker implementing the GenericSpellChecker interface or false
	*/
	function create($text, $backend = false) {
		if(!is_object($this)){
			$factory = new SpellChecker();
			return $factory->create($text, $backend);
		}

		//{{{ check for pspell
		if(!extension_loaded("pspell")){
			$this->_err["Can't use Pspell"] = array("The pspell extension is not loaded");
			$this->_backends["Pspell"] = false;
		}
		//}}}

		//{{{ check for the aspell executable
		$aspell_ok = true;
		$aspell_found = false;
		$tmp_err = array();

		if(isset($this->_options["aspellPath"])) {
			if(!is_executable($this->_options["aspellPath"])){
				$tmp_err[] = "Provided aspell does not exist or is not executable, trying to guess...";
			} else {
				$aspell_found = true;
			}
		} else {
			$tmp_err[] = "Path to aspell not provided, trying to guess...";
		}

		if(!$aspell_found){
			$aspell_ok = false;
			$path = explode(":",$_ENV["PATH"]);
			foreach($path as $p){
				$ap_unix = "$p/aspell";
				$ap_win = "$p/aspell.exe";
				if(is_executable($ap_unix)){
					$this->_options["aspellPath"] = $ap_unix;
					$aspell_found = true;
					break;
				}

				if(is_executable($ap_win)){
					$this->_options["aspellPath"] = $ap_win;
					$aspell_found = true;
					break;
				}
			}
		}

		if(!$aspell_found)
			$tmp_err[] = "No executable program called aspell or aspell.exe was found in PATH";

		$aspell_ok = $aspell_found;

		if(!function_exists("proc_open")) {
			$tmp_err[] = "The proc_open() function doesn't exist";
			$aspell_ok = false;
		}

		if(stristr(ini_get("disable_functions"),"proc_open") !== false) {
			$tmp_err[] = "The proc_open() function is disabled.";
			$aspell_ok = false;
		}

		if(!$aspell_ok){
			$this->_err["Can't use aspell"] = $tmp_err;
			$this->_backends["Aspell"] = false;
		}
		//}}}

		//{{{ see if we can contact google
		$google_ok = true;
		$tmp_err = array();

		if(!extension_loaded("curl")) {
			$google_ok = false;
			$tmp_err[] = "The curl extension is not loaded";
		}

		if(!extension_loaded("xml")) {
			$google_ok = false;
			$tmp_err[] = "The xml extension is not loaded";
		}

		if(stristr(ini_get("disable_functions"),"curl") !== false) {
			$google_ok = false;
			$tmp_err[] = "The curl functions are disabled";
		}

		if(!$google_ok){
			$this->_err["Can't use google"] = $tmp_err;
			$this->_backends["Google"] = false;
		}
		//}}}

		if($backend && $this->_backends[$backend]) {
			$class = "{$backend}SpellChecker";
			return new $class($text, $this->_options);
		}

		foreach($this->_backends as $b=>$v){
			if($v){
				$class = "{$b}SpellChecker";
				return new $class($text, $this->_options);
			}
		}

		return false;
	}
	//}}}

	//{{{ public function errorLog()
	/**
	* Return an array containing errors encountered.
	*
	* The array format is <code>array("error"=>array("reason","reason",...),...)</code>
	* @return array
	*/
	function errorLog() {
		return $this->_err;
	}
	//}}}

	//{{{ public function backends()
	/**
	* Return an array containing supported backends and their availability status
	* @return array
	*/
	function backends() {
		return $this->_backends;
	}
	//}}}
}


/* USAGE */

$content = "";
$options = array(
	"lang"					=> 'en',
	"maxSuggestions"		=> 10,
	"customDict"			=> 0,
	"charset"				=> 'utf-8'
);
$factory = new SpellChecker($options);

$spell = $factory->create(trim(""));

header('Content-Type: text/xml; charset=UTF-8');
echo $spell->toXML();
?>