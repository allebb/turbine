<?php

/**
 * Just a few util static functions.
 */
class TurbineUtil
{

    /**
     * Build a string of addresses with HTML links if they are valid hostnames (as opposed to a default OR wildcard)
     * @param string $address The plain text 'origin_address'
     * @return string
     */
    public static function LinkOriginAddresses($address)
    {
        $linked_hosts = array();
        foreach (explode(' ', $address) as $single_adderess) {
            switch ($single_adderess) {
                case str_contains($single_adderess, '*'):
                    $linked_hosts[] = $single_adderess;
                    break;
                case '_';
                    $linked_hosts[] = $single_adderess . ' (Catch-all)';
                    break;
                default:
                    $linked_hosts[] = '<a href="http://' . $single_adderess . '" target="_blank">' . $single_adderess . '</a>';
            }
        }
        return implode(' ', $linked_hosts);
    }

}

?>
