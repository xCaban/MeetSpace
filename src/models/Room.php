<?php

class Room {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function findAll() {
        $stmt = $this->pdo->query("SELECT * FROM rooms ORDER BY name");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM rooms WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $stmt = $this->pdo->prepare("
            INSERT INTO rooms (name, capacity, description, equipment) 
            VALUES (:name, :capacity, :description, :equipment)
        ");
        
        return $stmt->execute([
            'name' => $data['name'],
            'capacity' => $data['capacity'],
            'description' => $data['description'],
            'equipment' => $data['equipment']
        ]);
    }

    public function update($id, $data) {
        $stmt = $this->pdo->prepare("
            UPDATE rooms 
            SET name = :name, 
                capacity = :capacity, 
                description = :description, 
                equipment = :equipment
            WHERE id = :id
        ");
        
        return $stmt->execute([
            'id' => $id,
            'name' => $data['name'],
            'capacity' => $data['capacity'],
            'description' => $data['description'],
            'equipment' => $data['equipment']
        ]);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM rooms WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function getUpcomingReservations($id) {
        $stmt = $this->pdo->prepare("
            SELECT r.*, u.name as user_name 
            FROM reservations r 
            JOIN users u ON r.user_id = u.id 
            WHERE r.room_id = :room_id 
            AND r.start_time >= CURRENT_DATE 
            AND r.start_time <= CURRENT_DATE + INTERVAL '7 days'
            ORDER BY r.start_time
        ");
        $stmt->execute(['room_id' => $id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function isAvailable($id, $startTime, $endTime) {
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
            'room_id' => $id,
            'start_time' => $startTime,
            'end_time' => $endTime
        ]);
        return $stmt->fetchColumn() === 0;
    }

    public function isCurrentlyOccupied($id) {
        $stmt = $this->pdo->prepare("
            SELECT COUNT(*) FROM reservations 
            WHERE room_id = :room_id 
            AND CURRENT_TIMESTAMP BETWEEN start_time AND end_time
        ");
        $stmt->execute(['room_id' => $id]);
        return $stmt->fetchColumn() > 0;
    }

    public function getCurrentReservation($id) {
        $stmt = $this->pdo->prepare("
            SELECT r.*, u.name as user_name 
            FROM reservations r 
            JOIN users u ON r.user_id = u.id 
            WHERE r.room_id = :room_id 
            AND CURRENT_TIMESTAMP BETWEEN r.start_time AND r.end_time
            LIMIT 1
        ");
        $stmt->execute(['room_id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
} 