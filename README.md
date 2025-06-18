# MeetSpace - System Rezerwacji Sal Konferencyjnych

MeetSpace to nowoczesna aplikacja webowa do zarządzania rezerwacjami sal konferencyjnych. System umożliwia łatwe przeglądanie dostępnych sal, sprawdzanie ich dostępności w czasie rzeczywistym oraz zarządzanie rezerwacjami.

## Funkcjonalności

### Dla użytkowników
- Przeglądanie listy dostępnych sal konferencyjnych
- Sprawdzanie szczegółów sal (pojemność, wyposażenie)
- Tworzenie i zarządzanie rezerwacjami
- Przeglądanie historii własnych rezerwacji
- Anulowanie oczekujących rezerwacji

### Dla administratorów
- Zarządzanie salami konferencyjnymi (dodawanie, edycja, usuwanie)
- Przeglądanie wszystkich rezerwacji w systemie
- Zatwierdzanie lub odrzucanie rezerwacji
- Zarządzanie statusem sal (aktywna/nieaktywna)

## Wymagania techniczne

- PHP 8.0+
- PostgreSQL
- Nginx
- Docker

## Instalacja

1. Sklonuj repozytorium:
```bash
git clone https://github.com/xCaban/MeetSpace.git
cd MeetSpace
```

### Instalacja z użyciem Dockera

1. Zbuduj i uruchom kontenery:
```bash
docker-compose up -d
```

2. W folderze config, w pliku database.example.php znajduje się login i hasło do bazy. Zmień je na bezpieczne i zmień nazwe pliku na database.php

3. Aplikacja będzie dostępna pod adresem `http://localhost:8081`

## Struktura projektu

```
MeetSpace/
├── src/
│   ├── controllers/    # Kontrolery aplikacji
│   │   ├── AdminController.php
│   │   ├── AuthController.php
│   │   ├── DefaultController.php
│   │   ├── ReservationController.php
│   │   └── RoomController.php
│   ├── models/         # Modele danych
│   │   ├── Room.php
│   │   ├── User.php
│   │   └── Reservation.php
│   ├── views/          # Widoki
│   │   ├── admin/      # Panel administracyjny
│   │   ├── rooms/      # Widoki sal
│   │   ├── default/    # Strona główna![rooms](https://github.com/user-attachments/assets/53b50568-e9e1-4298-9299-ede9a8ce7eff)
![main](https://github.com/user-attachments/assets/c34dbe09-9713-4923-9dce-999640b0fd84)
![login](https://github.com/user-attachments/assets/228dff64-b221-4423-ba62-bdd8f74d6a2b)
![admin-rooms](https://github.com/user-attachments/assets/935586e9-ced7-4b73-bc77-d47bedc304e0)

│   │   ├── reservations/ # Widoki rezerwacji
│   │   ├── security/   # Logowanie i rejestracja
│   │   └── layout.php/ # Szablon layoutu
├── public/             # Pliki publiczne (CSS, JS, obrazy)
├── config/             # Pliki konfiguracyjne dla bazy
└── docker/             # Konfiguracja Dockera i bazy
```

# Schemat ERD

```mermaid
erDiagram
    users {
        serial id PK
        varchar(255) email UK "NOT NULL"
        varchar(255) password "NOT NULL"
        varchar(255) name "NOT NULL"
        varchar(50) role "NOT NULL DEFAULT 'user'"
        timestamp created_at "DEFAULT CURRENT_TIMESTAMP"
    }

    rooms {
        serial id PK
        varchar(255) name "NOT NULL"
        integer capacity "NOT NULL"
        text description
        text equipment
        timestamp created_at "DEFAULT CURRENT_TIMESTAMP"
    }

    reservations {
        serial id PK
        integer room_id FK "REFERENCES rooms(id) ON DELETE CASCADE"
        integer user_id FK "REFERENCES users(id) ON DELETE CASCADE"
        timestamp start_time "NOT NULL"
        timestamp end_time "NOT NULL"
        text purpose
        timestamp created_at "DEFAULT CURRENT_TIMESTAMP"
    }

    users ||--o{ reservations : "rezerwuje"
    rooms ||--o{ reservations : "jest rezerwowana"
```
## Opis relacji

1. **users - reservations**
   - Jeden użytkownik może mieć wiele rezerwacji (relacja 1:N)
   - Każda rezerwacja musi być przypisana do jednego użytkownika
   - Usunięcie użytkownika powoduje usunięcie wszystkich jego rezerwacji (ON DELETE CASCADE)

2. **rooms - reservations**
   - Jedna sala może mieć wiele rezerwacji (relacja 1:N)
   - Każda rezerwacja musi być przypisana do jednej sali
   - Usunięcie sali powoduje usunięcie wszystkich jej rezerwacji (ON DELETE CASCADE)

## Atrybuty tabel

### users
- `id`: Klucz główny, automatycznie inkrementowany
- `email`: Unikalny adres email (NOT NULL)
- `password`: Zahaszowane hasło (NOT NULL)
- `name`: Imię i nazwisko użytkownika (NOT NULL)
- `role`: Rola użytkownika (NOT NULL, domyślnie 'user')
- `created_at`: Data utworzenia konta (domyślnie CURRENT_TIMESTAMP)

### rooms
- `id`: Klucz główny, automatycznie inkrementowany
- `name`: Nazwa sali (NOT NULL)
- `capacity`: Maksymalna liczba osób (NOT NULL)
- `description`: Opis sali
- `equipment`: Wyposażenie sali
- `created_at`: Data dodania sali (domyślnie CURRENT_TIMESTAMP)

### reservations
- `id`: Klucz główny, automatycznie inkrementowany
- `room_id`: Klucz obcy do tabeli rooms (ON DELETE CASCADE)
- `user_id`: Klucz obcy do tabeli users (ON DELETE CASCADE)
- `start_time`: Data i godzina rozpoczęcia (NOT NULL)
- `end_time`: Data i godzina zakończenia (NOT NULL)
- `purpose`: Cel rezerwacji
- `created_at`: Data utworzenia rezerwacji (domyślnie CURRENT_TIMESTAMP)


## Bezpieczeństwo

- Wszystkie hasła są przechowywane w formie zahaszowanej
- Implementacja mechanizmu sesji
- Walidacja danych wejściowych
- Zabezpieczenie przed SQL Injection
- Kontrola dostępu do funkcji administracyjnych

# Screeny z aplikacji
![admin-rooms](https://github.com/user-attachments/assets/1bafb1d7-f2c4-45b6-9a47-efafa3bb0f19)
![rooms](https://github.com/user-attachments/assets/601519cc-d228-44df-8924-735dc3769d41)
![main](https://github.com/user-attachments/assets/60b4497c-5a51-421b-8d3e-e483af04c9b0)
![login](https://github.com/user-attachments/assets/b24a8ed2-f405-496c-bc47-668a6cce12e0)

