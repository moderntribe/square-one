<?php
/**
 * BaconStringUtils
 *
 * @link      http://github.com/Bacon/BaconStringUtils For the canonical source repository
 * @copyright 2011-2013 Ben Scholzen 'DASPRiD'
 * @license   http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */


namespace BaconStringUtils;

/**
 * Slugifier.
 */
class Slugifier
{
    /**
     * Default UniDecoder instance.
     *
     * @var UniDecode
     */
    protected static $defaultUniDecoder;

    /**
     * UniDecoder instance.
     *
     * @var UniDecode
     */
    protected $uniDecoder;

    /**
     * Slugify a string.
     *
     * @param  string $string
     * @return string
     */
    public function slugify($string)
    {
        $string = $this->uniDecoder()->decode($string);
        $string = strtolower($string);
        $string = str_replace("'", '', $string);
        $string = preg_replace('([^a-zA-Z0-9_-]+)', '-', $string);
        $string = preg_replace('(-{2,})', '-', $string);
        $string = trim($string, '-');

        return $string;
    }

    /**
     * Get the uni decoder.
     *
     * @return UniDecode
     */
    public function uniDecoder()
    {
        if ($this->uniDecoder === null) {
            if (self::$defaultUniDecoder === null) {
                self::$defaultUniDecoder = new UniDecoder();
            }

            $this->uniDecoder = self::$defaultUniDecoder;
        }

        return $this->uniDecoder;
    }

    /**
     * Set the uni decoder.
     *
     * @param  UniDecode $decoder
     * @return void
     */
    public function setUniDecoder(UniDecode $decoder)
    {
        $this->uniDecoder = $decoder;
    }

    /**
     * Set the default uni decoder.
     *
     * @param  UniDecode $decoder
     * @return void
     */
    public static function setDefaultUniDecoder(UniDecode $decoder)
    {
        self::$defaultUniDecoder = $decoder;
    }
}
