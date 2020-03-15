<?php

namespace EchoBot;
require dirname(__DIR__).'/vendor/autoload.php';
// require dirname(__DIR__) . '/src/User.php';

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;


class EchoBot implements MessageComponentInterface
{
    protected $clients;

    public function __construct()
    {
        echo "server started...\n";
        $this->clients = array();
        $this->clients[0] = new \SplObjectStorage; //public room
    }

    public function onOpen(ConnectionInterface $conn)
    {
        // Store the new connection to send messages to later
        $this->clients[0]->attach($conn);
        echo "New connection! ({$conn->resourceId})\n";
    }
    /**
     * 
     */
    public function onMessage(ConnectionInterface $from, $msg)
    {
        $numRecv = count($this->clients) - 1;
        $response_text = "";

        $decoded_msg = json_decode($msg);

        //ENUM: [connect,start, pause, resume, close, submit]
        $cmd = $decoded_msg->cmd;
        if($cmd == "connect") {
            $this->onConnect($from, $decoded_msg);
        }
        // if ($cmd == "connect") {
        //     $this->onConnect($from, $decoded_msg);
        // } else if ($cmd == "start") {
        //     $this->onStart();
        // } else if ($cmd == "pause") {
        //     $this->onPause();
        // } else if ($cmd == "resume") {
        //     $this->onResume();
        // } else if ($cmd == "close") {
        //     $this->onCloseQuestion();
        // } else if ($cmd == "submit") {
        //     $this->onSubmit();
        // } else if ($cmd == "closing_connection") {
        //     $this->onCloseConnection();
        // }

        // echo $cmd;
//         $response_text = array("cmd"=>$decoded_msg->cmd, "username"=>$decoded_msg->username, "role"=>$decoded_msg->role,"from_id"=>$decoded_msg->from_id,
//     "question_id"=>$decoded_msg->question_id, "answers"=>$decoded_msg->answers,"question_instance_id"=>$decoded_msg->question_instance_id, "targeted_time"=>$decoded_msg->targeted_time,
// "question_status"=>$decoded_msg->question_status, "remaining_time"=>$decoded_msg->remaining_time);

//         $response_text = json_encode($response_text);

//         echo sprintf(
//             'Connection %d sending message "%s" to %d other connection%s' . "\n\n",
//             $from->resourceId,
//             $response_text,
//             $numRecv,
//             $numRecv == 1 ? '' : 's'
//         );

//         $this->broadcast($from, $response_text);
    }

    /**
     * As a teacher connects to the server, initialize a new container for the teacher within $this->clients
     * which can be traced by teacher's resource_id
     * As a student connects to the server, enter the provided room or return an error message: question is not ready yet
     */
    private function onConnect(ConnectionInterface $from, $msg)
    {
        $role = $msg->role;
        $resource_id = $from->resourceId;
        //initialize chambers[teacher's resource id]
        if (strcmp($role, "teacher") == 0 && empty($this->clients[$resource_id])) {
            $this->clients[$resource_id] = array();
            $this->clients[$resource_id]['teacher']->attach($from);
            $this->clients[0]->dettach($from);
            print_r($this->clients[$resource_id]);
        } else if (strcmp($role, "student") == 0 && empty($this->clients[$msg->teacher_resource_id])) {
        } else if (strcmp($role, "student")) {
        }
    }
    /**
     * 
     */
    private function onStart()
    {
    }

    private function onPause()
    {
    }

    private function onResume()
    {
    }

    private function onCloseQuestion()
    {
    }

    private function onSubmit()
    {
    }

    private function onCloseConnection()
    {
    }

    public function onClose(ConnectionInterface $conn)
    {
        // The connection is closed, remove it, as we can no longer send it messages
        $this->clients[0]->detach($conn);

        echo "\nConnection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }

    private function broadcast($from, $msg)
    {
        foreach ($this->clients[0] as $client) {
            if ($from !== $client) {
                // The sender is not the receiver, send to each client connected
                echo $msg;
                $client->send($msg);
            }
        }
    }

    private function sendTo($to, $msg) {
        $to->send($msg);
    }
}


$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new EchoBot()
        )
    ),
    8080
);

$server->run();
