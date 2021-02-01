<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

namespace local_outboundhttpstream\external;

global $CFG;
require_once($CFG->dirroot . '/local/guzzle/extlib/vendor/autoload.php');

defined('MOODLE_INTERNAL') || die;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Exception\RequestException;

/**
 * Class guzzle_client
 *
 * @package   local_outboundhttpstream
 * @author    Xuan Gui <xuangui@catalyst-au.net>
 * @copyright  2020 Catalyst IT Australia
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class guzzle_client
{
    /**
     * @var $guzzleclient
     */
    public static Client $guzzleclient;

    /**
     * Get guzzle client.
     *
     * @return Client
     * @throws \dml_exception
     */
    public static function get_guzzle_client() : Client {

        if (isset(self::$guzzleclient) && self::$guzzleclient) {
            return self::$guzzleclient;
        }

        $apibaseurl = "https://api.spoonacular.com/recipes/";

        // Create guzzle client.
        $guzzleclient = new Client([
            'base_uri' => $apibaseurl,
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ]
        ]);

        self::$guzzleclient = $guzzleclient;

        return $guzzleclient;
    }
}
