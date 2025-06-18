<?php
require_once __DIR__ . "/../../Database.php";
require_once __DIR__ . "/../models/Room.php";
require_once __DIR__ . "/../models/Reservation.php";

class AdminController {
    private $roomModel;
    private $reservationModel;

    public function __construct() {
        $db = Database::getInstance()->getConnection();
        $this->roomModel = new Room($db);
        $this->reservationModel = new Reservation($db);
    }

    private function checkAdmin() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            header('Location: /login');
            exit;
        }
    }

    public function listRooms() {
        $this->checkAdmin();
        $rooms = $this->roomModel->findAll();
        require_once __DIR__ . "/../views/admin/rooms/list.php";
    }

    public function createRoomForm() {
        $this->checkAdmin();
        require_once __DIR__ . "/../views/admin/rooms/create.php";
    }

    public function createRoom() {
        $this->checkAdmin();

        try {
            $this->roomModel->create([
                'name' => $_POST['name'],
                'capacity' => $_POST['capacity'],
                'description' => $_POST['description'],
                'equipment' => $_POST['equipment']
            ]);
            $_SESSION['success'] = "Sala została dodana pomyślnie.";
            header('Location: /admin/rooms');
        } catch (PDOException $e) {
            $_SESSION['error'] = "Wystąpił błąd podczas dodawania sali.";
            header('Location: /admin/rooms');
        }
    }

    public function editRoomForm($id) {
        $this->checkAdmin();
        $room = $this->roomModel->findById($id);
        if (!$room) {
            $_SESSION['error'] = "Nie znaleziono sali.";
            header('Location: /admin/rooms');
            exit;
        }
        require_once __DIR__ . "/../views/admin/rooms/edit.php";
    }

    public function updateRoom($id) {
        $this->checkAdmin();

        try {
            $this->roomModel->update($id, [
                'name' => $_POST['name'],
                'capacity' => $_POST['capacity'],
                'description' => $_POST['description'],
                'equipment' => $_POST['equipment']
            ]);
            $_SESSION['success'] = "Sala została zaktualizowana pomyślnie.";
            header('Location: /admin/rooms');
        } catch (PDOException $e) {
            $_SESSION['error'] = "Wystąpił błąd podczas aktualizacji sali.";
            header('Location: /admin/rooms');
        }
    }

    public function deleteRoom($id) {
        $this->checkAdmin();

        try {
            // Najpierw usuń wszystkie rezerwacje dla tej sali
            $this->reservationModel->deleteByRoomId($id);
            // Następnie usuń salę
            $this->roomModel->delete($id);
            $_SESSION['success'] = "Sala została usunięta pomyślnie.";
            header('Location: /admin/rooms');
        } catch (PDOException $e) {
            $_SESSION['error'] = "Wystąpił błąd podczas usuwania sali.";
            header('Location: /admin/rooms');
        }
    }

    public function listReservations() {
        $this->checkAdmin();
        $reservations = $this->reservationModel->findAll();
        require_once __DIR__ . "/../views/admin/reservations/list.php";
    }

    public function deleteReservation($id) {
        $this->checkAdmin();

        try {
            $this->reservationModel->deleteByIdAndUserId($id, null); // null oznacza, że nie sprawdzamy user_id
            $_SESSION['success'] = "Rezerwacja została usunięta.";
        } catch (PDOException $e) {
            $_SESSION['error'] = "Wystąpił błąd podczas usuwania rezerwacji.";
        }

        header('Location: /admin/reservations');
    }
} 