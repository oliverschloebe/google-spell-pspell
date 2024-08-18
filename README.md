![GitHub License](https://img.shields.io/github/license/oliverschloebe/google-spell-pspell)


# google-spell-pspell

A PHP Pspell substitute for Google Spell Check XML API. Pspell is required in order to work on your server.

## Why?

Google obviously shut down their Spell checking API (www.google.com/tbproxy/spell) on July 9th 2013, see [here](http://productforums.google.com/forum/#!topic/chat/CPb0PYllbE8). This PHP class is a PHP Pspell substitute for Google Spell Check XML API using the same XML structure.

## Usage
```php
require_once 'spell-check-library.php';
$content = "";
$options = array(
	"lang"				=> 'en',
	"maxSuggestions"		=> 10,
	"customDict"			=> 0,
	"charset"			=> 'utf-8'
);
$factory = new SpellChecker($options);

$spell = $factory->create(trim("Ths is a tst"));

header('Content-Type: text/xml; charset=UTF-8');
echo $spell->toXML();
```
Echoes Google-style XML like this:
```xml
<spellresult error="0" clipped="0" charschecked="12">
    <c o="0" l="3" s="1">This Th's Thus Th HS</c>
    <c o="9" l="3" s="1">test tat ST St st</c>
</spellresult>
```

... which you can use with your existing spell checking script such as GoogieSpell that was expecting XML structured data back from Google.

## Bugs/Suggestions

If you find a bug, or would like to contribute to the project please use the [Issue Tracker](https://github.com/AlphawolfWMP/google-spell-pspell/issues) over at my GitHub project page.

## Credits

Mad props to [Sabin Iacob (m0n5t3r)](http://m0n5t3r.info). Code basically from [here](http://plugins.svn.wordpress.org/ajax-spell-checker/trunk/service/spell-check-library.php), but I removed the Aspell and Google API parts and made it standalone-ready.
