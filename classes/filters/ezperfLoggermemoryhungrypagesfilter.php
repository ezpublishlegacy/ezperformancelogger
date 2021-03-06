<?php
/**
 * @author G. Giunta
 * @copyright (C) eZ Systems AS 2013-2016
 * @license Licensed under GNU General Public License v2.0. See file license.txt
 */
class eZPerfLoggerMemoryhungrypagesFilter implements eZPerfLoggerFilter
{
    public static function shouldLog( array $data, $output )
    {
        if ( !isset( $data['mem_usage'] ) || $data['mem_usage'] >= eZPerfLoggerINI::variable( 'LogFilterSettings', 'MemoryhungrypagesFilter' ) )
        {
            return true;
        }
        return false;
    }
}
