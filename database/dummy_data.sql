-- Hapus data yang ada (opsional, gunakan hanya jika ingin membersihkan data yang ada)
-- DELETE FROM items;
-- DELETE FROM rooms;
-- DELETE FROM locations;
-- DELETE FROM categories;

-- Categories
INSERT INTO categories (id, cat_name, cat_code, created_at, updated_at) VALUES
(1, 'Furniture', 'FRN', NOW(), NOW()),
(2, 'Electronics', 'ELC', NOW(), NOW()),
(3, 'Office Supplies', 'OFS', NOW(), NOW()),
(4, 'IT Equipment', 'ITE', NOW(), NOW()),
(5, 'Kitchen Appliances', 'KAP', NOW(), NOW());

-- Locations
INSERT INTO locations (id, name, created_at, updated_at) VALUES
(1, 'Main Building', NOW(), NOW()),
(2, 'Annex Building', NOW(), NOW()),
(3, 'Research Center', NOW(), NOW());

-- Rooms
INSERT INTO rooms (id, name, location_id, length, width, area, photo, status, created_at, updated_at) VALUES
(1, 'Meeting Room A101', 1, 8, 6, 48, 'room_a101.jpg', 'available', NOW(), NOW()),
(2, 'Office 201', 1, 5, 4, 20, 'office_201.jpg', 'available', NOW(), NOW()),
(3, 'Lab Room B103', 2, 10, 8, 80, 'lab_b103.jpg', 'available', NOW(), NOW()),
(4, 'Conference Hall', 3, 15, 12, 180, 'conference_hall.jpg', 'available', NOW(), NOW());

-- Items
INSERT INTO items (id, cat_id, room_id, item_name, qty, good_qty, broken_qty, lost_qty, photo, is_borrowable, created_at, updated_at) VALUES
(1, 1, 1, 'Conference Table', 2, 2, 0, 0, 'conf_table.jpg', 0, NOW(), NOW()),
(2, 1, 1, 'Office Chair', 12, 10, 2, 0, 'office_chair.jpg', 1, NOW(), NOW()),
(3, 2, 1, 'Projector', 1, 1, 0, 0, 'projector.jpg', 1, NOW(), NOW()),
(4, 4, 2, 'Desktop Computer', 5, 4, 1, 0, 'desktop.jpg', 0, NOW(), NOW()),
(5, 4, 2, 'Laptop', 3, 3, 0, 0, 'laptop.jpg', 1, NOW(), NOW()),
(6, 3, 2, 'Printer', 2, 2, 0, 0, 'printer.jpg', 0, NOW(), NOW()),
(7, 4, 3, 'Lab Computer', 8, 7, 1, 0, 'lab_computer.jpg', 0, NOW(), NOW()),
(8, 2, 3, 'Microscope', 6, 5, 0, 1, 'microscope.jpg', 1, NOW(), NOW()),
(9, 5, 4, 'Coffee Machine', 2, 2, 0, 0, 'coffee_machine.jpg', 0, NOW(), NOW()),
(10, 2, 4, 'Sound System', 1, 1, 0, 0, 'sound_system.jpg', 0, NOW(), NOW());
