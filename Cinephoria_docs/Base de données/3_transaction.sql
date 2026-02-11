
-- 3_transaction.sql

-- Objectif : Réserver 2 sièges (id 1 et 2) pour l'utilisateur 2 sur la séance 1
-- Si un des sièges est déjà réservé (isReserved = TRUE), on annule tout avec ROLLBACK

START TRANSACTION;

-- Vérification : les sièges doivent être libres
SET @seat1 := 1;
SET @seat2 := 2;

-- Check si les deux sièges sont libres
SELECT isReserved INTO @seat1_reserved FROM Seat WHERE id = @seat1;
SELECT isReserved INTO @seat2_reserved FROM Seat WHERE id = @seat2;

-- Condition de rollback
IF @seat1_reserved OR @seat2_reserved THEN
  ROLLBACK;
ELSE
  -- Insertion de la réservation
  INSERT INTO Reservation (user_id, showtime_id, status, totalPrice) 
  VALUES (2, 1, 'confirmed', 21.00);
  SET @reservationId = LAST_INSERT_ID();

  -- Lier les sièges à la réservation
  INSERT INTO ReservationSeat (reservation_id, seat_id, price) 
  VALUES 
  (@reservationId, @seat1, 10.50),
  (@reservationId, @seat2, 10.50);

  -- Marquer les sièges comme réservés
  UPDATE Seat SET isReserved = TRUE WHERE id IN (@seat1, @seat2);

  COMMIT;
END IF;
