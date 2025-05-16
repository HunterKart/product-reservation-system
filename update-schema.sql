-- Update the reservations table schema for better date handling

-- Option 1: If you want to keep using TIMESTAMP but allow NULL values:
-- This is less disruptive to existing data but keeps time information
ALTER TABLE reservations 
MODIFY COLUMN date TIMESTAMP NULL;

-- Option 2: If you want to change to DATE type (recommended for reservation dates):
-- This requires data conversion but is more accurate for the use case
-- WARNING: This will remove time information from existing records

-- First, create a backup of the current data
CREATE TABLE reservations_backup AS SELECT * FROM reservations;

-- Then modify the column type
ALTER TABLE reservations
MODIFY COLUMN date DATE NULL;

-- IMPORTANT: Choose only ONE of the options above based on your preference.
-- After running the chosen option, you might need to update your code if you chose Option 2.

-- If using Option 2 (DATE type), you may want to explicitly format dates when inserting:
-- Example: INSERT INTO reservations (..., date, ...) VALUES (..., STR_TO_DATE('2023-11-01', '%Y-%m-%d'), ...);

-- If you want to keep existing data but update the schema going forward, use Option 1.
-- If date precision (without time) is more important, use Option 2. 