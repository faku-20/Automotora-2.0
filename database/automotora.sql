-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 09, 2026 at 12:20 AM
-- Server version: 10.11.16-MariaDB
-- PHP Version: 8.3.36

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `automotora`
--

-- --------------------------------------------------------

--
-- Table structure for table `autos`
--

CREATE TABLE `autos` (
  `id` int(11) NOT NULL,
  `marca` varchar(20) NOT NULL,
  `modelo` varchar(20) NOT NULL,
  `anio` int(4) NOT NULL,
  `color` varchar(20) NOT NULL,
  `kilometros` int(10) UNSIGNED NOT NULL,
  `precio` decimal(12,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `autos`
--

INSERT INTO `autos` (`id`, `marca`, `modelo`, `anio`, `color`, `kilometros`, `precio`) VALUES
(1, 'Toyota', 'Corolla', 2022, 'Blanco', 35000, 22500.00),
(2, 'Chevrolet', 'Onix', 2021, 'Gris', 42000, 16800.00),
(3, 'Volkswagen', 'Polo', 2023, 'Negro', 18000, 21000.00),
(4, 'Fiat', 'Cronos', 2022, 'Rojo', 30000, 18500.00),
(5, 'Hyundai', 'HB20', 2024, 'Azul', 10000, 23500.00),
(6, 'Renault', 'Sandero', 2020, 'Blanco', 55000, 14500.00),
(7, 'Peugeot', '208', 2023, 'Gris', 22000, 22000.00),
(8, 'Nissan', 'Versa', 2021, 'Plateado', 40000, 19500.00);

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `usuario` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nombre` varchar(20) NOT NULL,
  `apellido` varchar(20) NOT NULL,
  `tipo_usuario` enum('administrador','comprador') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `usuarios`
--

INSERT INTO `usuarios` (`id`, `usuario`, `password`, `nombre`, `apellido`, `tipo_usuario`) VALUES
(1, 'admin01', '$2y$10$thHNtNMjIg753Ofys2Cnv.qcwBD/AUjYjfB4qYSBRKY5CFOOcuQR6', 'Carlos', 'Perez', 'administrador'),
(2, 'admin02', '$2y$10$Pune37kq9wK9BhwZ/0wJqu9aCNkql/FExMI.Thx6gXSjFJex1fRe.', 'Maria', 'Rodriguez', 'administrador'),
(3, 'admin03', '$2y$10$Blf8GhVN4w.1QawxC.UFSuadwYFuZp2QzYWmpxqRyebQHQ2paGAuq', 'Juan', 'Gonzalez', 'administrador'),
(4, 'admin04', '$2y$10$AGgM6wGneCRHwSU3hX1Gau1ep42rkP6E20H6kyvioRF./aOkkJazS', 'Lucia', 'Fernandez', 'administrador'),
(5, 'admin05', '$2y$10$YWPUouO7nIZ3pkEHK.87eOp3zj7j2PQFryuKu8zohhTsS5dn4vP9q', 'Diego', 'Martinez', 'administrador'),
(6, 'admin06', '$2y$10$6V4iZC7kGKIzkEPA3KCqdu/eibH29BbpkDYB84yasWQGkHxZxX9TG', 'Sofia', 'Lopez', 'administrador'),
(7, 'admin07', '$2y$10$JF2fPOk0mo4SZWQgryusCusiE2l4E7HWimM8KdVKlTH0wgSoUoJKa', 'Martin', 'Silva', 'administrador'),
(8, 'admin08', '$2y$10$Zm242bj06gvIjmNZDoDpm.3Wn2pP4tGNeurx0b0GFw/g8ph6tIiBu', 'Valentina', 'Suarez', 'administrador'),
(9, 'admin09', '$2y$10$CrvBgYzSaji2HudZQYBGa.ArUBrVOvOJUY/zmnE7BOrqM/wIT9YJG', 'Andres', 'Romero', 'administrador'),
(10, 'admin10', '$2y$10$T30acxBb9tPX9xeSx6Y6ie3fqWbAFqqd1LhMws6.EKHZTK5c26r.y', 'Camila', 'Torres', 'administrador'),
(11, 'comprador01', '$2y$10$jNlp5rBYjGDsZQD9.AIQCugj3Y8Z8VuzmfYsCOAjiFWZFwl8BHOEC', 'Federico', 'Garcia', 'comprador'),
(12, 'comprador02', '$2y$10$a4qZ1BjmzI72FG7DEvFFzu9v1aT.dMC7DA4R0fSwOGWLbdhq0xcpK', 'Ana', 'Mendez', 'comprador'),
(13, 'comprador03', '$2y$10$ylHF8KbD.IbIutC/a6488.TXjxXbwf0Hn584uSc.7aHH8CLGBTZqm', 'Nicolas', 'Pereira', 'comprador'),
(14, 'comprador04', '$2y$10$0dixtryFdDV4PSDI4XOz4O0YShT1nNw0sSPKjnCfwF9eZYMBaTgNO', 'Florencia', 'Diaz', 'comprador'),
(15, 'comprador05', '$2y$10$HvKVZQtjAfkTtB4sW8oJjuh8UsJrFvD8gWsUxz.Tg/gdZ0fMxmPvW', 'Santiago', 'Viera', 'comprador'),
(16, 'comprador06', '$2y$10$TbyjR.qBknR1TrrLLtrczOeVj75AJSooTeWso.QHyk.BhwKOIUW.K', 'Agustina', 'Castro', 'comprador'),
(17, 'comprador07', '$2y$10$/8ujc6QOGLc6LbxwlpweA.FdZdrmQG9iZpFmgynTf77m9GX5UZuSi', 'Facundo', 'Mendez', 'comprador'),
(18, 'comprador08', '$2y$10$VltyAbR/SJa6SHoGItiwV.FnPwIcN2hoqBN0f1Lwd0JmsZy/Ro8Ju', 'Julieta', 'Cabrera', 'comprador'),
(19, 'comprador09', '$2y$10$h3qarVs3NuUC/6824dY6zOS12hcqZ4nRrSCuWOczB7yHCW0pD6XOK', 'Bruno', 'Olivera', 'comprador'),
(20, 'comprador10', '$2y$10$Z01AQzQ5dkT/.9oegJuNIuM5kbehUdEJmfSl4tjUPQjZtfNkNsVUi', 'Victoria', 'Morales', 'comprador');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `autos`
--
ALTER TABLE `autos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario` (`usuario`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `autos`
--
ALTER TABLE `autos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
