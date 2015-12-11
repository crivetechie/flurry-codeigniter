<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once __DIR__.'/../libraries/Bazu/Flurry/Flurry.php';
require_once __DIR__.'/../libraries/Bazu/Flurry/Types.php';

class Flurry extends CI_Controller {

  private $loader = null;

  public function __construct() {
    parent::__construct();

    $gen_dir = realpath(dirname(__FILE__));

    $this->loader = new \Thrift\ClassLoader\ThriftClassLoader();
    $this->loader->registerNamespace('Thrift', __DIR__ );
    $this->loader->registerDefinition('shared', $gen_dir);
    $this->loader->registerDefinition('tutorial', $gen_dir);
    $this->loader->register();    
 
  }

  public function index() {
    $flurryhost = $this->config->item('flurry_server')["host"];
    $flurryport = $this->config->item('flurry_server')["port"];

    try {
      $socket = new \Thrift\Transport\TSocket($flurryhost, $flurryport);
      $transport = new \Thrift\Transport\TBufferedTransport($socket, 1024, 1024);
      $protocol = new \Thrift\Protocol\TBinaryProtocol($transport);
      $client = new \Bazu\Flurry\FlurryClient($protocol);

      $transport->open();

      $timer_start = microtime(TRUE);
      $id = $client->get_id();
      $usec_taken = ((microtime(TRUE) - $timer_start)*1000);
      $workerid = $client->get_worker_id();
      print "flurry server: $flurryhost:$flurryport<br>";
      print "workerid: $workerid<br>";
      print "id: $id<br>";
      print "took $usec_taken usecs";

      $transport->close();

    } catch (TException $tx) {
      print 'TException: '.$tx->getMessage()."\n";
    }
  }
}
?>
