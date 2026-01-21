CREATE DATABASE sistem_akademik;
USE sistem_akademik;

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(20),
  password VARCHAR(255),
  role ENUM('mahasiswa','dosen'),
  status ENUM('aktif','pending')
);

CREATE TABLE mahasiswa (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nim VARCHAR(20),
  nama VARCHAR(100),
  tgl_lahir DATE,
  tahun_masuk YEAR,
  email VARCHAR(100)
);

CREATE TABLE dosen (
  id INT AUTO_INCREMENT PRIMARY KEY,
  id_dosen VARCHAR(20),
  nama VARCHAR(100),
  tgl_lahir DATE,
  tahun_diterima YEAR,
  email VARCHAR(100)
);

-- Tabel Matakuliah
CREATE TABLE matakuliah (
  id INT AUTO_INCREMENT PRIMARY KEY,
  kode_mk VARCHAR(20) NOT NULL,
  nama_mk VARCHAR(100) NOT NULL,
  sks INT NOT NULL,
  kelas VARCHAR(10),
  semester VARCHAR(50)
);

-- Tabel Nilai
CREATE TABLE nilai (
  id INT AUTO_INCREMENT PRIMARY KEY,
  id_mahasiswa INT NOT NULL,
  id_matakuliah INT NOT NULL,
  nilai DECIMAL(4,2),
  nilai_bobot DECIMAL(3,2) NULL,
  FOREIGN KEY (id_mahasiswa) REFERENCES mahasiswa(id) ON DELETE CASCADE,
  FOREIGN KEY (id_matakuliah) REFERENCES matakuliah(id) ON DELETE CASCADE
);


CREATE TABLE krs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_mahasiswa INT NOT NULL,
    id_matakuliah INT NOT NULL,
    semester VARCHAR(20) NOT NULL,
    tahun_akademik VARCHAR(20) NOT NULL,
    tanggal_pengisian DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_mahasiswa) REFERENCES mahasiswa(id) ON DELETE CASCADE,
    FOREIGN KEY (id_matakuliah) REFERENCES matakuliah(id) ON DELETE CASCADE
);

CREATE TABLE krs_detail (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_krs INT NOT NULL,
    id_matakuliah INT NOT NULL,
    FOREIGN KEY (id_krs) REFERENCES krs(id) ON DELETE CASCADE,
    FOREIGN KEY (id_matakuliah) REFERENCES matakuliah(id) ON DELETE CASCADE
);

ALTER TABLE krs 
ADD COLUMN status ENUM('disetujui','pending') NOT NULL DEFAULT 'pending';

ALTER TABLE nilai 
MODIFY nilai VARCHAR(2);

--tambahan
CREATE TABLE dosen_mahasiswa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_dosen VARCHAR(20),
    nim VARCHAR(20)
);

DS2002
DS2002@0604200
litnil wkwkwkkw oyiow  
