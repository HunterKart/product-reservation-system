-- Update the reservations table to add new status values

-- First, make a backup of the current table
CREATE TABLE reservations_backup AS SELECT * FROM reservations;

-- Now alter the status enum to include the new values
ALTER TABLE reservations
MODIFY COLUMN status ENUM('pending', 'confirmed', 'canceled', 'delivered', 'returned') DEFAULT 'pending';

-- Note: If you're using MySQL 5.7+ or MariaDB, the above command should work.
-- For older versions, you might need to drop and recreate the column:

-- Alternative approach for older MySQL versions:
-- ALTER TABLE reservations 
-- MODIFY COLUMN status VARCHAR(15) DEFAULT 'pending';

-- Remember to update any application logic to handle these new status values! 