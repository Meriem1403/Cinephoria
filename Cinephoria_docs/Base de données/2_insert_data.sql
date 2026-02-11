
-- 2_insert_data.sql

-- Roles
INSERT INTO Role (name, description) VALUES 
('admin', 'Full access'),
('employee', 'Cinema staff'),
('user', 'Regular customer');

-- Users
INSERT INTO User (firstName, lastName, email, password, role_id) VALUES
('Meriem', 'Zahzouh', 'admin@cinephoria.com', 'hashed_admin_pwd', 1),
('Alice', 'Dupont', 'alice@cinephoria.com', 'hashed_user_pwd', 3),
('Jean', 'Martin', 'jean@cinephoria.com', 'hashed_user_pwd', 3),
('Sophie', 'Durand', 'sophie@cinephoria.com', 'hashed_emp_pwd', 2);

-- Cinemas
INSERT INTO Cinema (name, city, address, postalCode, country, phone, email) VALUES
('Cinéphoria Marseille', 'Marseille', '12 rue du cinéma', '13000', 'France', '0491000000', 'marseille@cinephoria.com'),
('Cinéphoria Lyon', 'Lyon', '24 avenue Lumière', '69000', 'France', '0472000000', 'lyon@cinephoria.com');

-- Rooms
INSERT INTO Room (cinema_id, name, capacity, projectionEquipment, notes) VALUES
(1, 'Salle Alpha', 120, 'Dolby Atmos', NULL),
(1, 'Salle Beta', 80, '4K Laser', NULL),
(2, 'Salle Gamma', 100, 'Dolby Vision', NULL);

-- Seats
INSERT INTO Seat (room_id, rowLabel, seatNumber, isPMR) VALUES
(1, 'A', 1, FALSE),
(1, 'A', 2, FALSE),
(1, 'B', 1, TRUE),
(2, 'A', 1, FALSE),
(2, 'A', 2, FALSE),
(3, 'C', 1, FALSE);

-- Movies
INSERT INTO Movie (title, description, duration, releaseDate, language, ageRating, genre, posterUrl) VALUES
('Inception', 'Dream inside a dream', 148, '2010-07-16', 'EN,FR', 'PG-13', 'Sci-Fi', 'inception.jpg'),
('The Matrix', 'Neo discovers the Matrix', 136, '1999-03-31', 'EN', 'R', 'Action', 'matrix.jpg');

-- Showtimes
INSERT INTO Showtime (movie_id, room_id, date, startTime, endTime, language, status, availableSeats, price) VALUES
(1, 1, '2025-06-01', '20:00:00', '22:28:00', 'FR', 'active', 117, 10.50),
(2, 2, '2025-06-02', '18:00:00', '20:16:00', 'EN', 'active', 78, 9.50);

-- Reservations
INSERT INTO Reservation (user_id, showtime_id, status, totalPrice) VALUES
(2, 1, 'confirmed', 21.00),
(3, 2, 'pending', 9.50);

-- ReservationSeats
INSERT INTO ReservationSeat (reservation_id, seat_id, price) VALUES
(1, 1, 10.50),
(1, 2, 10.50),
(2, 4, 9.50);

-- Reviews
INSERT INTO Review (user_id, movie_id, rating, comment, isApproved) VALUES
(2, 1, 5, 'Un chef d'œuvre.', TRUE),
(3, 2, 4.5, 'Toujours aussi culte.', TRUE);

-- Incidents
INSERT INTO Incident (user_id, room_id, seat_id, title, description, status) VALUES
(4, 1, 2, 'Problème de siège', 'Le siège A2 est défectueux.', 'open'),
(4, 2, NULL, 'Climatisation en panne', 'Salle trop chaude.', 'resolved');

-- Employees
INSERT INTO CinemaEmployee (user_id, cinema_id, jobTitle) VALUES
(4, 1, 'Technicien'),
(4, 2, 'Responsable');
