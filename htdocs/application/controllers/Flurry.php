<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/*
require_once __DIR__.'/../libraries/Thrift/ClassLoader/ThriftClassLoader.php';
require_once __DIR__.'/../libraries/Thrift/Protocol/TProtocol.php';
require_once __DIR__.'/../libraries/Thrift/Protocol/TBinaryProtocol.php';
require_once __DIR__.'/../libraries/Thrift/Transport/TTransport.php';
require_once __DIR__.'/../libraries/Thrift/Transport/TSocket.php';
require_once __DIR__.'/../libraries/Thrift/Transport/THttpClient.php';
require_once __DIR__.'/../libraries/Thrift/Transport/TBufferedTransport.php';
require_once __DIR__.'/../libraries/Thrift/Exception/TException.php';
require_once __DIR__.'/../libraries/Thrift/Type/TMessageType.php';
require_once __DIR__.'/../libraries/Thrift/Factory/TStringFuncFactory.php';
require_once __DIR__.'/../libraries/Thrift/StringFunc/Core.php';
require_once __DIR__.'/../libraries/Thrift/StringFunc/TStringFunc.php';
*/
require_once __DIR__.'/../libraries/Bazu/Flurry/Flurry.php';
require_once __DIR__.'/../libraries/Bazu/Flurry/Types.php';

/*
use \Thrift\ClassLoader\ThriftClassLoader;
use \Thrift\Protocol\TBinaryProtocol;
use \Thrift\Transport\TSocket;
use \Thrift\Transport\THttpClient;
use \Thrift\Transport\TBufferedTransport;
use \Thrift\Exception\TException;
*/


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

  /*
  use Thrift\Protocol\TBinaryProtocol;
  use Thrift\Transport\TSocket;
  use Thrift\Transport\THttpClient;
  use Thrift\Transport\TBufferedTransport;
  use Thrift\Exception\TException;
  */

  public function index() {
    try {
      $socket = new \Thrift\Transport\TSocket('localhost', 9090);
      $transport = new \Thrift\Transport\TBufferedTransport($socket, 1024, 1024);
      $protocol = new \Thrift\Protocol\TBinaryProtocol($transport);
      $client = new \Bazu\Flurry\FlurryClient($protocol);

      $transport->open();

      $result = $client->get_id();
      print "$result";

      $transport->close();

    } catch (TException $tx) {
      print 'TException: '.$tx->getMessage()."\n";
    }
  }
}
?>
