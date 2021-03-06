<?php
/**
 * Same as cronjob, for one-shot runs
 *
 * @author G. Giunta
 * @copyright (C) eZ Systems AS 2012-2016
 * @license Licensed under GNU General Public License v2.0. See file license.txt
 */

require 'autoload.php';

$cli = eZCLI::instance();
$script = eZScript::instance( array( 'description' => '...',
                                     'use-session' => false,
                                     'use-modules' => true,
                                     'use-extensions' => true ) );
$script->startup();
$options = $script->getOptions(
    '',
    '', //'[logfile]',
    array() );
$script->initialize();

set_time_limit( 0 );

if ( $script->verboseOutputLevel() > 0 )
    $cli->output( "Updating perf counters..."  );

$dt = new eZDateTime();
/*$year = $dt->year();
$month = date( 'M', time() );
$day = $dt->day();
$hour = $dt->hour();
$minute = $dt->minute();
$second = $dt->second();
$startTime = $day . "/" . $month . "/" . $year . ":" . $hour . ":" . $minute . ":" . $second;*/
$cli->output( "Started at " . $dt->toString()  );

$contentArray = array();
$logFilePath = '';
$plIni = eZINI::instance( 'ezperformancelogger.ini' );
$logTo = $plIni->variable( 'GeneralSettings', 'LogMethods' );
if ( in_array( 'apache', $logTo ) && !in_array( 'logfile', $logTo ) )
{
    $logFileIni = eZINI::instance( 'logfile.ini' );
    $logFilePath = $logFileIni->variable( 'AccessLogFileSettings', 'StorageDir' ) . '/' . $logFileIni->variable( 'AccessLogFileSettings', 'LogFileName' );
}
else if ( !in_array( 'apache', $logTo ) && in_array( 'logfile', $logTo ) )
{
    $logFilePath = $plIni->variable( 'logfileSettings', 'FileName' );
}
else
{
    $cli->error( "Cannot decide which log-file to open for reading, please enable either apache-based logging or file-based logging." );
    $script->shutdown( 1 );
}

if ( $logFilePath != '' )
{
    $cli->output( "Parsing file " . $logFilePath  );

    $storageClass = $plIni->variable( 'ParsingSettings', 'StorageClass' );
    $excludeRegexps = $plIni->variable( 'ParsingSettings', 'ExcludeUrls' );
    $ok = eZPerfLoggerLogManager::updateStatsFromLogFile( $logFilePath, 'eZPerfLoggerApacheLogger', $storageClass, 'updateperfstats.log', $excludeRegexps );
    if ( $ok === false )
    {
        $cli->output( "Error parsing file $logFilePath. Please run script in debug mode for more info" );
    }
    else
    {
        $cli->output( "{$ok['counted']} lines containing data found" );
    }
}

$dt = new eZDateTime();
$cli->output( "Finished at " . $dt->toString() . "\n"  );
if ( $script->verboseOutputLevel() > 0 )
    $cli->output( "Perf counters have been updated\n" );

$script->shutdown();
