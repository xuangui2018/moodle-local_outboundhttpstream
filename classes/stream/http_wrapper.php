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

/**
 * A file system instrumentation stream wrapper
 *
 * @package    local_outboundhttpstream
 * @author     Xuan Gui <xuangui@catalyst-au.net>
 * @copyright  2021 Catalyst IT
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_outboundhttpstream\stream;

use \local_outboundhttpstream\stream\stream_wrapper_base;


/**
 * This http stream wrapper tracks the Moodle outbound http traffic.
 */
class http_wrapper extends stream_wrapper_base
{

    /** @var array to store performance stats */
    static private $perf = [];

    /**
     * Return collected stats around file path usage
     *
     * @return array of stats for each path
     */
    public static function get_perf_stats(): array
    {
        return self::$perf;
    }

    /**
     * Collect stats for a single file operation
     *
     * @param string $operation the type of file operation
     * @param string $path the absolute file path operated on
     * @param int $size how much to increment
     */
    public static function record_operation(string $operation, string $path, int $size = 1)
    {

    }

    /**
     * Collect data on opened files
     *
     * @param string $path
     * @param string $mode
     * @param int $options
     * @param string $openedpath
     */
    public function stream_open(string $path, string $mode, int $options, ?string &$openedpath): bool
    {
        $url = parse_url($path);
        var_dump($url);

        if ($mode === 'r' || $mode === 'rb') {
            self::record_operation('read', $path);
        } else {
            self::record_operation('write', $path);
        }
        return parent::stream_open($path, $mode, $options, $openedpath);
    }

    /**
     * stream_read
     * @param int $count
     */
    public function stream_read(int $count)
    {
        print "count is $count";
        $chunk = parent::stream_read($count);
        self::record_operation('bytes', $this->path, strlen($chunk));
        return $chunk;
    }

    /**
     * stream_write
     * @param string $data
     */
    public function stream_write(string $data)
    {
        self::record_operation('bytes', $this->path, strlen($data));
        return parent::stream_write($data);
    }

    /**
     * Collect data on file_exists
     *
     * @param string $path
     * @param int $flags
     */
    public function url_stat(string $path, int $flags)
    {
        print "I am in url_stat";
        $stat = parent::url_stat($path, $flags);
        if (empty($stat)) {
            self::record_operation('miss', $path);
        } else {
            self::record_operation('stat', $path);
        }
        return $stat;
    }
}
