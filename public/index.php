<?php
session_start();

require_once __DIR__ . "/../Routing.php";
require_once __DIR__ . "/../src/controllers/DefaultController.php";
require_once __DIR__ . "/../src/controllers/SecurityController.php";
require_once __DIR__ . "/../src/controllers/RoomController.php";
require_once __DIR__ . "/../src/controllers/ReservationController.php";
require_once __DIR__ . "/../src/controllers/AdminController.php";

$routing = new Routing();

// Default routes
$routing->get("", "DefaultController@index");
$routing->get("rooms", "RoomController@listRooms");
$routing->get("room/{id}", "RoomController@showRoom");

// Authentication routes
$routing->get("login", "SecurityController@login");
$routing->post("login", "SecurityController@authenticate");
$routing->get("register", "SecurityController@register");
$routing->post("register", "SecurityController@registerUser");
$routing->get("logout", "SecurityController@logout");

// Reservation routes
$routing->get("reservations", "ReservationController@listReservations");
$routing->get("reservations/create/{id}", "ReservationController@createForm");
$routing->post("reservations/create", "ReservationController@createReservation");
$routing->get("reservation/{id}", "ReservationController@showReservation");
$routing->post("reservation/{id}/delete", "ReservationController@deleteReservation");
$routing->get("reservations/cancel/{id}", "ReservationController@cancelReservation");

// Admin routes
$routing->get("admin/rooms", "AdminController@listRooms");
$routing->get("admin/rooms/create", "AdminController@createRoomForm");
$routing->post("admin/rooms/create", "AdminController@createRoom");
$routing->get("admin/rooms/edit/{id}", "AdminController@editRoomForm");
$routing->post("admin/rooms/edit/{id}", "AdminController@updateRoom");
$routing->post("admin/rooms/delete/{id}", "AdminController@deleteRoom");
$routing->get("admin/reservations", "AdminController@listReservations");
$routing->post("admin/reservations/{id}/delete", "AdminController@deleteReservation");

$routing->run(); 