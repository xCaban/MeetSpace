<?php

class Reservation {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function findAll() {
        $stmt = $this->pdo->query("
            SELECT r.*, rm.name as room_name, u.name as user_name 
            FROM reservations r 
            JOIN rooms rm ON r.room_id = rm.id 
            JOIN users u ON r.user_id = u.id 
            ORDER BY r.start_time DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findByUserId($userId) {
        $stmt = $this->pdo->prepare("
            SELECT r.*, rm.name as room_name 
            FROM reservations r 
            JOIN rooms rm ON r.room_id = rm.id 
            WHERE r.user_id = :user_id 
            ORDER BY r.start_time DESC
        ");
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findByIdAndUserId($id, $userId) {
        $stmt = $this->pdo->prepare("
            SELECT r.*, rm.name as room_name, u.name as user_name 
            FROM reservations r 
            JOIN rooms rm ON r.room_id = rm.id 
            JOIN users u ON r.user_id = u.id 
            WHERE r.id = :id AND r.user_id = :user_id
        ");
        $stmt->execute([
            'id' => $id,
            'user_id' => $userId
        ]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $stmt = $this->pdo->prepare("
            INSERT INTO reservations (room_id, user_id, start_time, end_time, purpose) 
            VALUES (:room_id, :user_id, :start_time, :end_time, :purpose)
        ");
        
        return $stmt->execute([
            'room_id' => $data['room_id'],
            'user_id' => $data['user_id'],
            'start_time' => $data['start_time'],
            'end_time' => $data['end_time'],
            'purpose' => $data['purpose']
        ]);
    }

    public function deleteByIdAndUserId($id, $userId) {
        if ($userId === null) {
            // Dla admina - usuń bez sprawdzania user_id
            $stmt = $this->pdo->prepare("DELETE FROM reservations WHERE id = :id");
            return $stmt->execute(['id' => $id]);
        } else {
            // Dla użytkownika - usuń tylko swoje rezerwacje
            $stmt = $this->pdo->prepare("DELETE FROM reservations WHERE id = :id AND user_id = :user_id");
            return $stmt->execute([
                'id' => $id,
                'user_id' => $userId
            ]);
        }
    }

    public function deleteByRoomId($roomId) {
        $stmt = $this->pdo->prepare("DELETE FROM reservations WHERE room_id = :room_id");
        return $stmt->execute(['room_id' => $roomId]);
    }

    public function isRoomAvailable($roomId, $startTime, $endTime) {
        $stmt = $this->pdo->prepare("
            SELECT COUNT(*) FROM reservations 
            WHERE room_id = :room_id 
            AND (
                (start_time <= :start_time AND end_time > :start_time)
                OR (start_time < :end_time AND end_time >= :end_time)
                OR (start_time >= :start_time AND end_time <= :end_time)
            )
        ");
        $stmt->execute([
            'room_id' => $roomId,
            'start_time' => $startTime,
            'end_time' => $endTime
        ]);
        return $stmt->fetchColumn() === 0;
    }
} 