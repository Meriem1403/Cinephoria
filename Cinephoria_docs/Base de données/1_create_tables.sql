
-- 1_create_tables.sql

CREATE TABLE Role (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    description TEXT
);

CREATE TABLE User (
    id INT AUTO_INCREMENT PRIMARY KEY,
    firstName VARCHAR(100) NOT NULL,
    lastName VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password TEXT NOT NULL,
    birthDate DATE,
    phone VARCHAR(20),
    address TEXT,
    city VARCHAR(100),
    postalCode VARCHAR(20),
    country VARCHAR(100),
    createdAt DATETIME DEFAULT CURRENT_TIMESTAMP,
    lastLogin DATETIME,
    isActive BOOLEAN DEFAULT TRUE,
    role_id INT,
    FOREIGN KEY (role_id) REFERENCES Role(id)
);

CREATE TABLE Movie (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    duration INT NOT NULL,
    releaseDate DATE,
    language VARCHAR(255),
    ageRating VARCHAR(10),
    genre VARCHAR(100),
    posterUrl TEXT,
    isFavorite BOOLEAN DEFAULT FALSE,
    rating FLOAT,
    createdAt DATETIME DEFAULT CURRENT_TIMESTAMP,
    atCinema BOOLEAN DEFAULT TRUE
);

CREATE TABLE Cinema (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    city VARCHAR(100),
    address TEXT,
    postalCode VARCHAR(20),
    country VARCHAR(100),
    phone VARCHAR(20),
    email VARCHAR(100)
);

CREATE TABLE Room (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cinema_id INT,
    name VARCHAR(100),
    capacity INT,
    projectionEquipment VARCHAR(255),
    notes TEXT,
    FOREIGN KEY (cinema_id) REFERENCES Cinema(id)
);

CREATE TABLE Seat (
    id INT AUTO_INCREMENT PRIMARY KEY,
    room_id INT,
    rowLabel VARCHAR(10),
    seatNumber INT,
    isPMR BOOLEAN DEFAULT FALSE,
    isReserved BOOLEAN DEFAULT FALSE,
    isBroken BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (room_id) REFERENCES Room(id)
);

CREATE TABLE Showtime (
    id INT AUTO_INCREMENT PRIMARY KEY,
    movie_id INT,
    room_id INT,
    date DATE NOT NULL,
    startTime TIME NOT NULL,
    endTime TIME NOT NULL,
    language VARCHAR(50),
    projectionType VARCHAR(50),
    status VARCHAR(50),
    availableSeats INT,
    pmrSeats INT,
    price FLOAT,
    specialPrice BOOLEAN,
    label VARCHAR(100),
    notes TEXT,
    incident_id INT,
    FOREIGN KEY (movie_id) REFERENCES Movie(id),
    FOREIGN KEY (room_id) REFERENCES Room(id),
    FOREIGN KEY (incident_id) REFERENCES Incident(id)
);

CREATE TABLE Reservation (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    showtime_id INT,
    reservationDate DATETIME DEFAULT CURRENT_TIMESTAMP,
    status VARCHAR(50),
    totalPrice FLOAT,
    FOREIGN KEY (user_id) REFERENCES User(id),
    FOREIGN KEY (showtime_id) REFERENCES Showtime(id)
);

CREATE TABLE ReservationSeat (
    id INT AUTO_INCREMENT PRIMARY KEY,
    reservation_id INT,
    seat_id INT,
    price FLOAT,
    isPMR BOOLEAN DEFAULT FALSE,
    isValid BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (reservation_id) REFERENCES Reservation(id),
    FOREIGN KEY (seat_id) REFERENCES Seat(id)
);

CREATE TABLE Review (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    movie_id INT,
    rating FLOAT NOT NULL,
    comment TEXT,
    createdAt DATETIME DEFAULT CURRENT_TIMESTAMP,
    isApproved BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (user_id) REFERENCES User(id),
    FOREIGN KEY (movie_id) REFERENCES Movie(id)
);

CREATE TABLE Incident (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    showtime_id INT,
    room_id INT,
    seat_id INT,
    title VARCHAR(255),
    description TEXT,
    status VARCHAR(50),
    createdAt DATETIME DEFAULT CURRENT_TIMESTAMP,
    resolvedAt DATETIME,
    FOREIGN KEY (user_id) REFERENCES User(id),
    FOREIGN KEY (showtime_id) REFERENCES Showtime(id),
    FOREIGN KEY (room_id) REFERENCES Room(id),
    FOREIGN KEY (seat_id) REFERENCES Seat(id)
);

CREATE TABLE CinemaEmployee (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    cinema_id INT,
    jobTitle VARCHAR(100),
    assignedSince DATE,
    isActive BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (user_id) REFERENCES User(id),
    FOREIGN KEY (cinema_id) REFERENCES Cinema(id)
);
