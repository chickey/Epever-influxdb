<?php
/*
 * PHP EpSolar Tracer Class (PhpEpsolarTracer) v0.9
 *
 */
 
//EPEver tracer php library
require_once 'PhpEpsolarTracer.php';
//influxDB php client library
require 'vendor/autoload.php';

//Define IP of influxDB
$host = 'localhost';

$tracer = new PhpEpsolarTracer('/dev/ttyUSB21');
$client = new InfluxDB\Client($host,8086,"root","root");

$db = $client->selectDB("logger");

//'http://localhost:8086/write?db=mydb' --data-binary 'cpu_load_short,host=server01,region=us-west value=0.64 1434055562000000000'

Print "\n Realtime Data\n";

if ($tracer->getRealtimeData()) {
for ($i = 0; $i < count($tracer->realtimeData); $i++) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,            "http://localhost:8086/write?db=powerwall" );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt($ch, CURLOPT_POST,           1 );
        $Item = $tracer->realtimeData[$i];
	$Key_Name = str_replace(" ","-",$tracer->realtimeKey[$i]);
        Print str_pad($i, 2, '0', STR_PAD_LEFT)." ".$Key_Name." ".$Item."\n";
        curl_setopt($ch, CURLOPT_POSTFIELDS,     "$Key_Name,unit=Realtime value=$Item" );
        curl_setopt($ch, CURLOPT_HTTPHEADER,     array('Content-Type: text/plain'));
        $result=curl_exec ($ch);
}
} else print "Cannot get RealTime Data\n";

Print "\n Statistical Data\n";

if ($tracer->getStatData()) {
for ($i = 0; $i < count($tracer->statData); $i++) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,            "http://localhost:8086/write?db=powerwall" );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt($ch, CURLOPT_POST,           1 );
        $Item = $tracer->statData[$i];
        $Key_Name = str_replace(" ","-",$tracer->statKey[$i]);
        Print str_pad($i, 2, '0', STR_PAD_LEFT)." ".$Key_Name." ".$Item."\n";
        curl_setopt($ch, CURLOPT_POSTFIELDS,     "$Key_Name,unit=statData value=$Item" );
        curl_setopt($ch, CURLOPT_HTTPHEADER,     array('Content-Type: text/plain'));
        $result=curl_exec ($ch);
}
} else print "Cannot get Statistical Data\n";


?>
