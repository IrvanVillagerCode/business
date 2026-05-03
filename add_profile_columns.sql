-- Tambahkan kolom baru ke tabel users untuk fitur profil
ALTER TABLE `users` 
ADD COLUMN `email` VARCHAR(100) DEFAULT NULL AFTER `role`,
ADD COLUMN `no_hp` VARCHAR(15) DEFAULT NULL AFTER `email`,
ADD COLUMN `alamat` TEXT DEFAULT NULL AFTER `no_hp`,
ADD COLUMN `foto_profil` VARCHAR(255) DEFAULT NULL AFTER `alamat`;
