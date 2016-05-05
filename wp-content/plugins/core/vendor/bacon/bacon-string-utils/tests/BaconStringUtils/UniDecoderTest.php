<?php
/**
 * BaconStringUtils
 *
 * @link      http://github.com/Bacon/BaconStringUtils For the canonical source repository
 * @copyright 2011-2013 Ben Scholzen 'DASPRiD'
 * @license   http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */

namespace BaconStringUtils;

use BaconStringUtils\UniDecoder;
use PHPUnit_Framework_TestCase as TestCase;

class UniDecoderTest extends TestCase
{
    protected $decoder;

    public function setUp()
    {
        $this->decoder = new UniDecoder();
    }

    public function testAscii()
    {
        for ($i = 0; $i < 128; $i++) {
            $char = $this->uniChar($i);

            $this->assertEquals($char, $this->decoder->decode($char));
        }
    }

    public function testBmp()
    {
        // Just check that it doesn't throw an exception
        for ($i = 0; $i < 0x10000; $i++) {
            $this->decoder->decode($this->uniChar($i));
        }
    }

    public function testCircledLatin()
    {
        // 1 sequence of a-z
        for ($i = 0; $i < 26; $i++) {
            $a = $this->uniChar(ord('a') + $i);
            $b = $this->decoder->decode($this->uniChar(0x24d0 + $i));

            $this->assertEquals($a, $b);
        }
    }

    public function testMathematicalLatin()
    {
        // 13 consecutive sequences of A-Z, a-z with some codepoints
        // undefined. We just count the undefined ones and don't check
        // positions.
        $empty = 0;

        for ($i = 0x1d400; $i < 0x1d6a4; $i++) {
            if ($i % 52 < 26) {
                $a = $this->uniChar(ord('A') + $i % 26);
            } else {
                $a = $this->uniChar(ord('a') + $i % 26);
            }

            $b = $this->decoder->decode($this->uniChar($i));

            if ($b === '') {
                $empty++;
            } else {
                $this->assertEquals($a, $b);
            }
        }

        $this->assertEquals(24, $empty);
    }

    public function testMathematicalDigits()
    {
        // 5 consecutive sequences of 0-9
        for ($i = 0x1d7ce; $i < 0x1d800; $i++) {
            $a = $this->uniChar(ord('0') + ($i - 0x1d7ce) % 10);
            $b = $this->decoder->decode($this->uniChar($i));

            $this->assertEquals($a, $b);
        }
    }

    public function testSpecific()
    {
        $tests = array(
            array(
                'Hello, World!',
                'Hello, World!'
            ),
            array(
                "'\"\r\n",
                "'\"\r\n"
            ),
            array(
                'ČŽŠčžš',
                'CZSczs'
            ),
            array(
                'ア',
                'a'
            ),
            array(
                'α',
                'a'
            ),
            array(
                'а',
                'a'
            ),
            array(
                'ch' . $this->uniChar(0xe2) . 'teau',
                'chateau'
            ),
            array(
                'vi' . $this->uniChar(0xf1) . 'edos',
                'vinedos'
            ),
            array(
                $this->uniChar(0x5317) . $this->uniChar(0x4eb0),
                'Bei Jing '
            ),
            array(
                'Efﬁcient',
                'Efficient'
            ),
            array(
                'příliš žluťoučký kůň pěl ďábelské ódy',
                'prilis zlutoucky kun pel dabelske ody'
            ),
            array(
                'PŘÍLIŠ ŽLUŤOUČKÝ KŮŇ PĚL ĎÁBELSKÉ ÓDY',
                'PRILIS ZLUTOUCKY KUN PEL DABELSKE ODY'
            ),
            // Table that doesn't exist
            array(
                $this->uniChar(0xa500),
                ''
            ),
            // Table that has less than 256 entries
            array(
                $this->uniChar(0x1eff),
                ''
            ),
        );

        foreach ($tests as $test) {
            $this->assertEquals($test[1], $this->decoder->decode($test[0]));
        }
    }

    public function testSpecificWide()
    {
        $tests = array(
            // Non-BMP character
            array(
                $this->uniChar(0x0001d5a0),
                'A'
            ),
            // Mathematical
            array(
                $this->uniChar(0x0001d5c4) . $this->uniChar(0x0001d5c6) . '/' . $this->uniChar(0x0001d5c1),
                'km/h'
            ),
        );

        foreach ($tests as $test) {
            $this->assertEquals($test[1], $this->decoder->decode($test[0]));
        }
    }

    /**
     * Convert a unicode codepoint to an UTF-8 character.
     *
     * @param  integer $code
     * @return string
     */
    protected function uniChar($code)
    {
        if ($code <= 0x7f) {
            $char = chr($code);
        } else if ($code <= 0x7ff) {
            $char = chr(0xc0 | $code >> 6)
                  . chr(0x80 | $code & 0x3f);
        } else if ($code <= 0xffff) {
            $char =  chr(0xe0 | $code >> 12)
                  . chr(0x80 | $code >> 6 & 0x3f)
                  . chr(0x80 | $code & 0x3f);
        } else if ($code <= 0x10ffff) {
            $char =  chr(0xf0 | $code >> 18)
                  . chr(0x80 | $code >> 12 & 0x3f)
                  . chr(0x80 | $code >> 6 & 0x3f)
                  . chr(0x80 | $code & 0x3f);
        } else {
            return null;
        }

        return $char;
    }
}