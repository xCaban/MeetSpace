<?php
require_once __DIR__ . "/../../Database.php";
require_once __DIR__ . "/../models/Room.php";
require_once __DIR__ . "/../models/Reservation.php";

class ReservationController {
    private $roomModel;
    private $reservationModel;

    public function __construct() {
        $db = Database::getInstance()->getConnection();
        $this->roomModel = new Room($db);
        $this->reservationModel = new Reservation($db);
    }

    private function checkAuth() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
    }

    public function listReservations() {
        $this->checkAuth();
        $reservations = $this->reservationModel->findByUserId($_SESSION['user_id']);
        require_once __DIR__ . "/../views/reservations/list.php";
    }

    public function createForm($roomId) {
        $this->checkAuth();
        $room = $this->roomModel->findById($roomId);
        if (!$room) {
            $_SESSION['error'] = "Nie znaleziono sali.";
            header('Location: /rooms');
            exit;
        }
        require_once __DIR__ . "/../views/reservations/create.php";
    }

    public function createReservation() {
        $this->checkAuth();

        try {
            $roomId = $_POST['room_id'];
            $startTime = $_POST['start_time'];
            $endTime = $_POST['end_time'];
            $purpose = $_POST['purpose'];

            // Sprawdź czy sala jest dostępna
            if (!$this->roomModel->isAvailable($roomId, $startTime, $endTime)) {
                $_SESSION['error'] = "Sala jest już zarezerwowana w wybranym terminie.";
                header('Location: /reservations/create/' . $roomId);
                exit;
            }

            // Utwórz rezerwację
            $this->reservationModel->create([
                'room_id' => $roomId,
                'user_id' => $_SESSION['user_id'],
                'start_time' => $startTime,
                'end_time' => $endTime,
                'purpose' => $purpose
            ]);

            $_SESSION['success'] = "Rezerwacja została utworzona pomyślnie.";
            header('Location: /reservations');
        } catch (PDOException $e) {
            $_SESSION['error'] = "Wystąpił błąd podczas tworzenia rezerwacji.";
            header('Location: /reservations/create/' . $roomId);
        }
    }

    public function showReservation($id) {
        $this->checkAuth();
        $reservation = $this->reservationModel->findByIdAndUserId($id, $_SESSION['user_id']);
        if (!$reservation) {
            $_SESSION['error'] = "Nie znaleziono rezerwacji.";
            header('Location: /reservations');
            exit;
        }
        require_once __DIR__ . "/../views/reservations/show.php";
    }

    public function deleteReservation($id) {
        $this->checkAuth();

        try {
            $this->reservationModel->deleteByIdAndUserId($id, $_SESSION['user_id']);
            $_SESSION['success'] = "Rezerwacja została usunięta pomyślnie.";
        } catch (PDOException $e) {
            $_SESSION['error'] = "Wystąpił błąd podczas usuwania rezerwacji.";
        }

        header('Location: /reservations');
    }

    public function cancelReservation($id) {
        $this->checkAuth();

        try {
            $reservation = $this->reservationModel->findByIdAndUserId($id, $_SESSION['user_id']);
            if (!$reservation) {
                $_SESSION['error'] = "Nie znaleziono rezerwacji.";
                header('Location: /reservations');
                exit;
            }

            // Sprawdź czy rezerwacja jest w stanie "pending"
            if ($reservation['status'] !== 'pending') {
                $_SESSION['error'] = "Nie można anulować rezerwacji, która została już zatwierdzona lub odrzucona.";
                header('Location: /reservations');
                exit;
            }

            $this->reservationModel->deleteByIdAndUserId($id, $_SESSION['user_id']);
            $_SESSION['success'] = "Rezerwacja została anulowana pomyślnie.";
        } catch (PDOException $e) {
            $_SESSION['error'] = "Wystąpił błąd podczas anulowania rezerwacji.";
        }

        header('Location: /reservations');
    }
} 