<?php
require 'vendor/autoload.php'; // Cargar las dependencias de Ratchet

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class ActiveUsers implements MessageComponentInterface {
    protected $clients;
    protected $users;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
        $this->users = [];
    }

    public function onOpen(ConnectionInterface $conn) {
       
        $this->clients->attach($conn);
        $this->users[$conn->resourceId] = true;
        $this->notifyActiveUsers();
    }

    public function onMessage(ConnectionInterface $from, $msg) {
    
    }

    public function onClose(ConnectionInterface $conn) {
     
        $this->clients->detach($conn);
        unset($this->users[$conn->resourceId]);
        $this->notifyActiveUsers();
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        $conn->close();
    }

    private function notifyActiveUsers() {
        $activeUsers = count($this->users);

        foreach ($this->clients as $client) {
            $client->send(json_encode(['activeUsers' => $activeUsers]));
        }
    }
}


$server = new Ratchet\App('localhost', 8080); 
$server->route('/users', new ActiveUsers, ['*']);
$server->run();
