<?php
/**
 * BaconStringUtils
 *
 * @link      http://github.com/Bacon/BaconStringUtils For the canonical source repository
 * @copyright 2011-2014 Ben Scholzen 'DASPRiD'
 * @license   http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */

namespace BaconStringUtils;

class Slugifier
{
    /**
     * @var UniDecoder
     */
    protected $uniDecoder;

    /**
     * Slugifies a string.
     *
     * @param  string $string
     * @return string
     */
    public function slugify($string)
    {
        $decoder = $this->getUniDecoder();

        if (null !== $decoder) {
            $string = $decoder->decode($string);
        }

        $string = strtolower($string);
        $string = str_replace("'", '', $string);
        $string = preg_replace('([^a-zA-Z0-9_-]+)', '-', $string);
        $string = preg_replace('(-{2,})', '-', $string);
        $string = trim($string, '-');

        return $string;
    }

    /**
     * @return UniDecoder
     */
    public function getUniDecoder()
    {
        return $this->uniDecoder;
    }

    /**
     * @param UniDecoder $decoder
     */
    public function setUniDecoder(UniDecoder $decoder)
    {
        $this->uniDecoder = $decoder;
    }
}
