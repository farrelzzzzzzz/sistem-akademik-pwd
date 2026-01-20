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
