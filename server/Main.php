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
    }

    /**
     * As a teacher connects to the server, initialize a new container for the teacher within $this->clients
     * which can be traced by teacher's resource_id
     * As a student connects to the server, enter the provided room or return an error message: question is not ready yet
     */
    private function onConnect(ConnectionInterface $from, $msg)
    {
        $role = (isset($msg->role)) ? $msg->role : null;
        $quiz_id = (isset($msg->quiz_id)) ? $msg->quiz_id : null;
        $list_of_students = ($msg->role === "teacher") ? json_decode($msg->list_of_students) : null;
        $resource_id = $from->resourceId;
        $u = new User($resource_id);
        $u->importUserInfo($msg->from_id, $msg->username, $msg->role);
        //initialize chambers[teacher's resource id]
        if (strcmp($role, "teacher") == 0 && empty($this->chambers[$quiz_id])) {
            $this->chambers[$quiz_id] = [];
            $this->chambers[$quiz_id]['teacher'] = $u;
            $this->chambers[$quiz_id]['list_of_students'] = $list_of_students;
            $this->chambers[$quiz_id]['summary']= [];
        } elseif (strcmp($role, "student") == 0) {
            foreach ($this->chambers as $quiz_id => $chamber) {
                $list_of_students = json_decode(json_encode($chamber['list_of_students']), true);
                $found = array_search($msg->from_id, $list_of_students);

                if ($found !== FALSE) { //quiz room found
                    if ($msg->current_site !== "questions/student") { //notify the student that the quiz is up
                        $response_text = array("cmd" => "notification", "quiz_id" => $quiz_id);
                        $this->clients[$resource_id]->send(json_encode($response_text));
                        $question_id = null;
                        if(!empty($this->chambers[$quiz_id]['question_list'])) {
                            $question_id = end($this->chambers[$quiz_id]['question_list']);
                        }

                        $response_text = ["cmd"=>"start", "question_id"=>$question_id];
                        $this->clients[$resource_id]->send(json_encode($response_text));
                    } else {
                        $this->chambers[$quiz_id]['student'][$found] = $u;
                    }
                }
            }
        } elseif($role === "summary" && !empty($this->chambers[$quiz_id])) {
            $this->chambers[$quiz_id]['summary'] = $u;
            print_r($this->chambers);
        }
    }
    /**
     * Start the quiz as a teacher, broadcast all connections in 'student' with 
     * quiz_id, question info
     */
    private function onClassMessage($msg)
    {
        if($msg->cmd == "start") {
            //start question, store question id
            $this->chambers[$msg->quiz_id]['question_list'][] = $msg->question_id;
        }
        if (strcmp($msg->role, "teacher") == 0) {
            $students = $this->chambers[$msg->quiz_id]['student'];
            if (empty($students)) return;
            foreach ($students as $student) {
                $resource_id = $student->get_resource_id();
                $this->clients[$resource_id]->send(json_encode($msg));
            }
            // $summary = $this->chambers[$msg->quiz_id]['summary'];
            // $resource_id = $summary->get_resource_id();
            // $this->clients[$resource_id]->send(json_encode($msg));
        } elseif($msg->role === "student" && $msg->cmd === "submit") { // student submit answe
            // $summary = $this->chambers[$msg->quiz_id]['summary'];
            // $resource_id = $summary->get_resource_id();
            // $this->clients[$resource_id]->send(json_encode($msg));
            print_r($msg);
            // echo "quiz id: {$msg->quiz_id}";
            // print_r($this->chambers[$msg->quiz_id]['summary']->get_resource_id());
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
            $this->onClassMessage($msg);
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
