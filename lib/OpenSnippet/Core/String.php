<?php

namespace OpenSnippet\Core;

/**
 * Class to manipulate the content of your application
 *
 * Cette classe est une dérivée de la classe SLStringPeer
 * 
 * @author    Leblanc Simon <contact@leblanc-simon.eu>
 * @license   Licence MIT <http://www.opensource.org/licenses/mit-license.php>
 * @version   1.3.0
 */
class String
{  
  protected static $map = null;
  
  
  /**
   * Convert a string with accent to a string without accent
   *
   * @param   String  $string     The string to convert
   * @return  String              The string converting
   * @access  public
   * @static
   */
  public static function noAccent($string)
  {
    $map = self::getMap();
    
    $accent     = array_merge($map['accent_lower'], $map['accent_upper']);
    $no_accent  = array_merge($map['no_accent_lower'], $map['no_accent_upper']);
    return str_replace($accent, $no_accent, $string);
  }
  
  
  /**
   * Delete all specials characters of a string
   *
   * @param   String  $string     The string to modified
   * @param   String  $replace    The characters for replace the specials characters
   * @return  String              The string modified
   * @access  public
   * @static
   */
  public static function noSpecialCharacters($string, $replace = '')
  {
    $specials_characters = array('"', '&', '€', '‚', 'ƒ', '„', '…', '†', '‡', 'ˆ', '‰', '<', '‘', '’',
                                 '“', '”', '•', '–', '—', '˜', '™', '›', '¡', '¢', '£', '¤', '¥', '¦',
                                 '§', '¨', '©', 'ª', '«', '¬', '­', '®', '¯', '°', '±', '²', '³', '´',
                                 'µ', '¶', '·', '¸', '¹', 'º', '»', '¼', '½', '¾', '¿', '/', '\\', '+',
                                 '*', ';', '!', '?', ',', '.', ':', '%', 'µ', '^', '¨', '}', ')', '@',
                                 '_', '`', '|', '(', '{', '#', '~', '[', ']', '=', ' ', "'", "\r\n",
                                 "\r", "\n", "\t");
    
    return str_replace($specials_characters, $replace, $string);
  }
  
  
  /**
   * Convert a string to lowercase (compatible utf-8 not like strtoupper)
   *
   * @param   String  $string     The string to convert
   * @return  String              The string converting
   * @access  public
   * @static
   */
  public static function toLower($string)
  {
    return mb_strtolower($string, mb_detect_encoding($string));
  }
  
  
  /**
   * transforme une chaine de caractères en chaine utilisable pour un label
   * @param   String  $string   La chaine a transformer
   * @return  String            La chaine transformée
   * @access  public
   * @static
   */
  public static function labelize($string)
  {
    $string = self::noAccent($string);
    $string = self::toLower($string);
    $string = self::noSpecialCharacters($string, '-');
    $string = preg_replace('#-{2,}#', '-', $string);
    while (preg_match('/.*-$/', $string) > 0) {
      $string = substr($string, 0, -1);
    }
    
    return $string;
  }
  
  
  /**
   * Initialize the map character if neccesary and return it
   *
   * @return  Array       The map with characters
   * @static
   * @access  protected
   */
  protected static function getMap()
  {
    if(self::$map === null){
      // Array struct : array('no_accent_lower', 'no_accent_upper', 'accent_lower', 'accent_upper',);
      self::$map = array(
        'no_accent_lower' => array(
          'a', 'a', 'a', 'a', 'a', 'a', 'ae',
          'c',
          'e', 'e', 'e', 'e',
          'i', 'i', 'i', 'i',
          'eth',
          'n',
          'o', 'o', 'o', 'o', 'o', 'o', 'oe',
          'u', 'u', 'u', 'u',
          'y',
          'sz',
          'thorn',
        ),
        'no_accent_upper' => array(
          'A', 'A', 'A', 'A', 'A', 'A', 'AE',
          'C',
          'E', 'E', 'E', 'E',
          'I', 'I', 'I', 'I',
          'ETH',
          'N',
          'O', 'O', 'O', 'O', 'O', 'O', 'OE',
          'U', 'U', 'U', 'U',
          'Y',
          'SZ',
          'THORN',
        ),
        'accent_lower' => array(
          'à', 'á', 'â', 'ã', 'ä', 'å', 'æ',
          'ç',
          'è', 'é', 'ê', 'ë',
          'ì', 'í', 'î', 'ï',
          'ð',
          'ñ',
          'ò', 'ó', 'ô', 'õ', 'ö', 'ø', 'œ',
          'ù', 'ú', 'û', 'ü',
          'ý',
          'ß',
          'þ',
        ),
        'accent_upper' => array(
          'À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ',
          'Ç',
          'È', 'É', 'Ê', 'Ë',
          'Ì', 'Í', 'Î', 'Ï',
          'Ð',
          'Ñ',
          'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', 'Œ',
          'Ù', 'Ú', 'Û', 'Ü',
          'Ý',
          'ß',
          'Þ',
        ),
      );
    }
    
    return self::$map;
  }
}