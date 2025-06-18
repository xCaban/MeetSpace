<?php
require_once __DIR__ . "/../../Database.php";
require_once __DIR__ . "/../models/Room.php";

class RoomController {
    private $roomModel;

    public function __construct() {
        $db = Database::getInstance()->getConnection();
        $this->roomModel = new Room($db);
    }

    public function listRooms() {
        $rooms = $this->roomModel->findAll();
        require_once __DIR__ . "/../views/rooms/list.php";
    }

    public function showRoom($id) {
        $room = $this->roomModel->findById($id);

        if (!$room) {
            header('Location: /rooms');
            return;
        }

        $reservations = $this->roomModel->getUpcomingReservations($id);
        require_once __DIR__ . "/../views/rooms/show.php";
    }
} 