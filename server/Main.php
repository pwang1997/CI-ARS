<?php

namespace EchoBot;

require dirname(__DIR__) . '/vendor/autoload.php';
require dirname(__DIR__) . '/server/User.php';

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
        $this->clients = [];
        $this->chambers = [];
    }


    public function onOpen(ConnectionInterface $conn)
    {
        // Store the new connection to send messages to later
        $this->clients[$conn->resourceId] = $conn;

        echo "New connection! ({$conn->resourceId})\n";
    }
    /**
     * 
     */
    public function onMessage(ConnectionInterface $from, $msg)
    {
        $decoded_msg = json_decode($msg);

        //ENUM: [connect,start, pause, resume, close, submit]
        $cmd = $decoded_msg->cmd;
        if ($cmd == "connect") {
            $this->onConnect($from, $decoded_msg);
        } else if (
            $cmd == "start" || $cmd == "pause" || $cmd == "resume"
            || $cmd == "close" || $cmd == "submit" || $cmd == "update_remaining_time"
            || $cmd == "display_answer" || $cmd == "hide_answer"
        ) {
            $this->onClassMessage($decoded_msg);
        } else if ($cmd == "closing_connection") {
            $this->onCloseConnection($decoded_msg);
        }

        // echo $cmd;
        //         $response_text = array("cmd"=>$decoded_msg->cmd, "username"=>$decoded_msg->username, "role"=>$decoded_msg->role,"from_id"=>$decoded_msg->from_id,
        //     "question_id"=>$decoded_msg->question_id, "answers"=>$decoded_msg->answers,"question_instance_id"=>$decoded_msg->question_instance_id, "targeted_time"=>$decoded_msg->targeted_time,
        // "question_status"=>$decoded_msg->question_status, "remaining_time"=>$decoded_msg->remaining_time);

        // $response_text = json_encode($response_text);

        // echo sprintf(
        //     'Connection %d sending message "%s" to %d other connection%s' . "\n\n",
        //     $from->resourceId,
        //     $response_text,
        //     $numRecv,
        //     $numRecv == 1 ? '' : 's'
        // );

        // $this->broadcast($from, $response_text);
    }

    /**
     * As a teacher connects to the server, initialize a new container for the teacher within $this->clients
     * which can be traced by teacher's resource_id
     * As a student connects to the server, enter the provided room or return an error message: question is not ready yet
     */
    private function onConnect(ConnectionInterface $from, $msg)
    {
        $role = $msg->role;
        $quiz_id = $msg->quiz_id;
        $list_of_students = json_decode($msg->list_of_students);
        $resource_id = $from->resourceId;
        $u = new User($resource_id);
        $u->importUserInfo($msg->from_id, $msg->username, $msg->role);
        //initialize chambers[teacher's resource id]
        if (strcmp($role, "teacher") == 0 && empty($this->chambers[$quiz_id])) {
            $this->chambers[$quiz_id] = [];
            $this->chambers[$quiz_id]['teacher'] = $u;
            $this->chambers[$quiz_id]['list_of_students'] = $list_of_students;
        } elseif (strcmp($role, "student") == 0) {
            foreach ($this->chambers as $quiz_id => $chamber) {
                $list_of_students = json_decode(json_encode($chamber['list_of_students']), true);
                $found = array_search($msg->from_id, $list_of_students);

                if ($found !== FALSE) { //quiz room found
                    if ($msg->current_site !== "questions/student") { //notify the student that the quiz is up
                        $response_text = array("cmd" => "notification", "quiz_id" => $quiz_id);
                        $this->clients[$resource_id]->send(json_encode($response_text));
                    } else {
                        $this->chambers[$quiz_id]['student'][$found] = $u;
                    }
                }
            }
        }
    }
    /**
     * Start the quiz as a teacher, broadcast all connections in 'student' with 
     * quiz_id, question info
     */
    private function onClassMessage($msg)
    {
        if (strcmp($msg->role, "teacher") == 0) {
            $students = $this->chambers[$msg->quiz_id]['student'];
            if (empty($students)) return;
            foreach ($students as $student) {
                $resource_id = $student->get_resource_id();
                $this->clients[$resource_id]->send(json_encode($msg));
            }
        } else { // student submit answer

        }
    }

    /**
     * close all connections within the class if it's a teacher
     * otherwise, unset its own connection
     */
    private function onCloseConnection($msg)
    {
        $role = $msg->role;
        $quiz_id = $msg->quiz_id;
        if (strcmp($role, "teacher") == 0) {
            //close classroom connection
            unset($this->chambers[$quiz_id]);
        }
    }

    public function onClose(ConnectionInterface $conn)
    {
        // The connection is closed, remove it, as we can no longer send it messages
        unset($this->clients[$conn->resourecId]);

        echo "\nConnection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
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
