<?php
/**
 * This program's functions.
 */

/**
 * Main function.
 *
 * @param array $whitelist
 * @param string $destination_pass
 * @param string $destination_fail
 */
function main($whitelist, $destination_pass, $destination_fail)
{
    // get the request's IP address
    $client_ip = _get_client_ip();

    // if the client's IP cannot be determined then do not let pass.
    // better safe than sorry.
    if (false === $client_ip) {
        _redirect($destination_fail);
        return;
    }

    // convert the client IP to a long integer for further processing downstream.
    $client_ip = ip2long($client_ip);

    // invalid client IP. do not let pass.
    if (false === $client_ip) {
        _redirect($destination_fail);
        return;
    }

    $whitelisted = _matches_list($client_ip, $whitelist);

    $destination = $whitelisted ? $destination_pass : $destination_fail;

    _redirect($destination);

}

/**
 * Returns the client's IP address.
 *
 * @return string|bool The client's IP, or FALSE if none could be determined.
 */
function _get_client_ip()
{
    $vars = array('HTTP_X_FORWARDED_FOR', 'HTTP_CLIENT_IP', 'REMOTE_ADDR');
    $client_ip = false;

    foreach ($vars as $var) {
        if (isset($_SERVER[$var])) {
            $client_ip = $_SERVER[$var];
            break;
        }
    }

    return $client_ip;
}

/**
 * Matches a given IP address against a given list of IP addresses.
 *
 * @param int $client_ip The client IP address.
 * @param string $list A comma separated list of IP addresses and IP ranges to match the client IP against.
 *
 * @return bool TRUE if a any match was found, FALSE otherwise.
 */
function _matches_list($client_ip, $list = '')
{

    $matches = false;
    $parts = explode(',', $list);
    while (!$matches && !empty($parts)) {
        $part = array_shift($parts);

        if ($part === '') {
            continue;
        }

        if (strpos($part, '-')) {
            $range = explode('-', $part, 2);
            $start = trim($range[0]);
            $end = trim($range[1]);
            $matches = _is_in_range($client_ip, $start, $end);
        } else {
            $matches = _matches_ip($client_ip, $part);
        }
    }

    return $matches;
}

/**
 * Checks if a given IP address is within a given IP range.
 *
 * @param int $client_ip An integer representation of the client's IP address.
 * @param string $start IP address at the start of the range.
 * @param string $end IP address at the end of the range.
 *
 * @return bool TRUE if client IP is within range, FALSE otherwise.
 */
function _is_in_range($client_ip, $start, $end)
{
    $start = ip2long($start);

    if (false === $start) {
        return false;
    }

    $end = ip2long($end);

    if (false === $end) {
        return false;
    }

    return ($client_ip >= $start && $client_ip <= $end);
}

/**
 * Checks if two given IP addresses match.
 *
 * @param int $client_ip An integer representation of the client's IP address.
 * @param string $ip IP address to compare to.
 *
 * @return bool TRUE if both IP addresses match, FALSE otherwise.
 */
function _matches_ip($client_ip, $ip)
{
    $ip = ip2long($ip);

    if (false === $ip) {
        return false;
    }

    return ($ip === $client_ip);
}

/**
 * Redirects the user to a given destination.
 *
 * @param string $destination The target URL
 */
function _redirect($destination)
{
    header("Location: ${destination}", TRUE, 307);
}
