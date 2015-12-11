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
      $data['host'] = $flurryhost;
      $data['port'] = $flurryport;
      $data['id'] = $client->get_id();
      $data['msec_taken'] = round(((microtime(TRUE) - $timer_start)*1000),3);
      $data['workerid'] = $client->get_worker_id();
      
      print_r($data);

      $transport->close();

      $this->load->view('flurry', $data);

    } catch (TException $tx) {
      print 'TException: '.$tx->getMessage()."\n";
    }
  }
}
?>
