-- AUTOMOTORA 2.0 - SQL COMPLETO BASADO EN MER REVISADO CON EL PROFESOR
-- VERSION: 2026-11-23 | MOTOR: MariaDB/MySQL (InnoDB/utf8mb4)
-- BASE AISLADA: automotora_mer_v2_pruebas (NO toca automotora ni automotora_bd_pruebas)
-- IMPORTAR SOLO UNA VEZ EN UNA BASE VACIA. No hay DROP TABLE / TRUNCATE.
-- 30 usuarios, 50 autos, 5 configuraciones, 120 simulaciones = 205 registros.
-- Datos de demostracion, no constituyen ventas ni operaciones reales.
-- Conserva los hashes de contrasena de los 20 usuarios originales.
-- LAS CUENTAS SQL DE DCL SON DE DEMOSTRACION: ROTAR CLAVES ANTES DE USARLAS.
--
-- MAPEADO DEL MER CHEN:
-- * USUARIO ISA ADMINISTRADOR / COMPRADOR: una tabla `usuarios` con tipo_usuario.
--   Especializacion total y disjunta representada mediante ENUM obligatorio.
-- * ORGANIZA: autos.administrador_id -> usuarios.id (debe ser administrador).
-- * CONFIGURA: configuracion_financiacion.administrador_id -> usuarios.id.
-- * REALIZA: simulaciones.comprador_id -> usuarios.id (debe ser comprador).
-- * TIENE: relacion identificadora; simulaciones es entidad debil de autos:
--   PK (auto_id, numero_simulacion), con numero_simulacion parcial.
-- * UTILIZA: simulaciones.configuracion_id nullable, solo para automotora.
-- NOTA: las FK aseguran existencia; verificar el subtipo del usuario en PHP.
--       El SQL de muestra no agrega triggers para no complicar el obligatorio.
--
-- El MER no solicita una tabla ventas: la simulacion no da por vendido al auto.

CREATE DATABASE IF NOT EXISTS `automotora_mer_v2_pruebas`
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `automotora_mer_v2_pruebas`;

-- ================================================================
-- 1. DDL, CLAVES, RESTRICCIONES E INDICES (RUBRICA ITEMS 2 Y 3)
-- ================================================================
CREATE TABLE `usuarios` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `usuario` VARCHAR(50) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `nombre` VARCHAR(100) NOT NULL,
  `apellido` VARCHAR(100) NOT NULL,
  `tipo_usuario` ENUM('administrador','comprador') NOT NULL,
  CONSTRAINT `pk_usuarios` PRIMARY KEY (`id`),
  CONSTRAINT `uq_usuarios_usuario` UNIQUE (`usuario`),
  CONSTRAINT `chk_usuario_no_vacio` CHECK (CHAR_LENGTH(TRIM(`usuario`)) > 0),
  CONSTRAINT `chk_nombre_no_vacio` CHECK (CHAR_LENGTH(TRIM(`nombre`)) > 0),
  CONSTRAINT `chk_apellido_no_vacio` CHECK (CHAR_LENGTH(TRIM(`apellido`)) > 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `autos` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `administrador_id` INT NOT NULL,
  `marca` VARCHAR(80) NOT NULL,
  `modelo` VARCHAR(80) NOT NULL,
  `anio` SMALLINT NOT NULL,
  `color` VARCHAR(50) NOT NULL,
  `kilometros` INT NOT NULL,
  `precio` DECIMAL(12,2) NOT NULL,
  CONSTRAINT `pk_autos` PRIMARY KEY (`id`),
  CONSTRAINT `fk_autos_admin` FOREIGN KEY (`administrador_id`)
    REFERENCES `usuarios` (`id`) ON UPDATE RESTRICT ON DELETE RESTRICT,
  CONSTRAINT `chk_autos_anio` CHECK (`anio` BETWEEN 1886 AND 2100),
  CONSTRAINT `chk_autos_km` CHECK (`kilometros` >= 0),
  CONSTRAINT `chk_autos_precio` CHECK (`precio` > 0),
  INDEX `idx_autos_admin` (`administrador_id`),
  INDEX `idx_autos_marca_modelo` (`marca`,`modelo`),
  FULLTEXT INDEX `ft_autos_marca_modelo` (`marca`,`modelo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `configuracion_financiacion` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `administrador_id` INT NOT NULL,
  `tasa_interes_mensual` DECIMAL(5,2) NOT NULL,
  `fecha_actualizacion` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT `pk_configuracion` PRIMARY KEY (`id`),
  CONSTRAINT `fk_configuracion_admin` FOREIGN KEY (`administrador_id`)
    REFERENCES `usuarios` (`id`) ON UPDATE RESTRICT ON DELETE RESTRICT,
  CONSTRAINT `chk_tasa_no_negativa` CHECK (`tasa_interes_mensual` >= 0),
  INDEX `idx_configuracion_admin_fecha` (`administrador_id`,`fecha_actualizacion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Debilidad POR IDENTIFICACION: numero_simulacion se numera por cada auto.
-- Por ejemplo: (auto_id=1, numero_simulacion=1) y (auto_id=2, numero_simulacion=1).
-- Al borrar un auto se impide el borrado si tiene simulaciones historicas.
-- No usar CASCADE aqui porque no queremos destruir su historial.
CREATE TABLE `simulaciones` (
  `auto_id` INT NOT NULL,
  `numero_simulacion` INT NOT NULL,
  `comprador_id` INT NOT NULL,
  `configuracion_id` INT NULL,
  `metodo_pago` ENUM('tarjeta','automotora') NOT NULL,
  `precio_auto` DECIMAL(12,2) NOT NULL,
  `entrega_inicial` DECIMAL(12,2) NOT NULL DEFAULT 0,
  `cantidad_cuotas` SMALLINT NOT NULL,
  `fecha_simulacion` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT `pk_simulaciones` PRIMARY KEY (`auto_id`,`numero_simulacion`),
  CONSTRAINT `fk_simulaciones_auto` FOREIGN KEY (`auto_id`)
    REFERENCES `autos` (`id`) ON UPDATE RESTRICT ON DELETE RESTRICT,
  CONSTRAINT `fk_simulaciones_comprador` FOREIGN KEY (`comprador_id`)
    REFERENCES `usuarios` (`id`) ON UPDATE RESTRICT ON DELETE RESTRICT,
  CONSTRAINT `fk_simulaciones_configuracion` FOREIGN KEY (`configuracion_id`)
    REFERENCES `configuracion_financiacion` (`id`) ON UPDATE RESTRICT ON DELETE RESTRICT,
  CONSTRAINT `chk_numero_simulacion` CHECK (`numero_simulacion` >= 1),
  CONSTRAINT `chk_precio_simulado` CHECK (`precio_auto` > 0),
  CONSTRAINT `chk_entrega_valida` CHECK (`entrega_inicial` >= 0 AND `entrega_inicial` < `precio_auto`),
  CONSTRAINT `chk_cuotas_validas` CHECK (`cantidad_cuotas` BETWEEN 1 AND 100),
  CONSTRAINT `chk_metodo_y_configuracion` CHECK (
    (`metodo_pago` = 'automotora' AND `configuracion_id` IS NOT NULL)
    OR (`metodo_pago` = 'tarjeta' AND `configuracion_id` IS NULL
        AND `entrega_inicial` = 0 AND `cantidad_cuotas` <= 12)
  ),
  INDEX `idx_simulaciones_comprador_fecha` (`comprador_id`,`fecha_simulacion`),
  INDEX `idx_simulaciones_configuracion` (`configuracion_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- RESTRICCIONES NO ESTRUCTURALES PARA LA DEFENSA:
-- Solo administradores pueden ORGANIZAR autos o CONFIGURAR tasas.
-- Solo compradores pueden REALIZAR simulaciones.
-- Una simulacion no confirma la venta del auto.
-- Una tasa historica no se actualiza: cada cambio hace un nuevo INSERT.
-- Una simulacion de tarjeta no referencia configuracion financiera.
-- Las reglas de calculo/redondeo deben conservarse para reconstruir resultados.

-- ================================================================
-- 2. REGISTROS DE DEMOSTRACION (RUBRICA ITEM 4)
-- ================================================================


-- USUARIOS (20 originales + 10 nuevos)
INSERT INTO `usuarios` (`id`,`usuario`,`password`,`nombre`,`apellido`,`tipo_usuario`) VALUES
(1,'admin01','$2y$12$uOM6vnN9Xrcpt8JIO95sqOAk7Pm4dTJgqfCGlR6jXeqD7fnye5BNa','Alma','Rivas','administrador'),
(2,'admin02','$2y$12$xv0ttuCwt.qiBQqG1Ud6ieWF6QQ3xCwiWnb69AlqByCf7YFBInxIm','Bruno','Lagos','administrador'),
(3,'admin03','$2y$12$v7B1mdX1fjTgHnYfx32eneFN2ly.hG.6GITn6Fmtw7HDzJdeTAPYy','Clara','Nadal','administrador'),
(4,'admin04','$2y$12$.TGjt9FeQm72UNi34oJIOejFYHPftbsL0F2SwxZxT3jx8hesLfJrG','Dario','Mora','administrador'),
(5,'admin05','$2y$12$l7sd2lg7JMlBJiNR2hSKHe1rPlksPfpoO8vMcq8H4Hu4K8z.SktGy','Elena','Vidal','administrador'),
(6,'admin06','$2y$12$QnLLCjDabiTB3Zra4wWyTOJ.T22BnkVa/pd.BamY83iHZxrahdbLm','Fabian','Soler','administrador'),
(7,'admin07','$2y$12$IDR5ltIAhvFexFJtfEWyaO8WomW.fBRqx0Asu0GCkvlm6Utx02FRC','Greta','Acosta','administrador'),
(8,'admin08','$2y$12$6V35QzKQNQjVeUHszR3KR.LkGs53yrjOOabFSxZdno0tr1kED43DS','Hugo','Paz','administrador'),
(9,'admin09','$2y$12$un/MAtzBHuyWZh3appVVCupEh95v10Yn5tPSISS1vPFdxYOzPPN6K','Ines','Costa','administrador'),
(10,'admin10','$2y$12$o6RgA0F/WOkSVMVlQAGDHOzz2.ZvA8.BhKW0MEo.8tAu821H.0CD.','Joaquin','Rey','administrador'),
(11,'comprador01','$2y$12$TdidcCnffgh5p.tNxvEvnOScrzYkAKEkMqPMITdGRkjVoqQB2x4DC','Kiara','Vega','comprador'),
(12,'comprador02','$2y$12$NYVhQIP6QvFTYTWYIO2Ro.tPm/pMHsKYyTTz5hbm9plBJXEZuWzEK','Lautaro','Gil','comprador'),
(13,'comprador03','$2y$12$y6T9EW0Ap9dX8clSgg3sGeMYM/CDKeo5GHopgPIyM7vg9uRhmZGsO','Mara','Cano','comprador'),
(14,'comprador04','$2y$12$MJdnUNfYn4UQ.lOUIw6eDOb6FE84I/5ktfGakIsH0Ztecn97pItVC','Nereo','Luna','comprador'),
(15,'comprador05','$2y$12$Sz8zfZ.zag/pXlvHtqlSRu1X5Bn2mkB65tRPnVBXEXNI/AfMSGYaG','Olivia','Rios','comprador'),
(16,'comprador06','$2y$12$B7Awo.Uvtcyol4xxBPyhGu3lTJN6.g3ZM9zzGa5ucB2u7iTN88ClK','Pablo','Farias','comprador'),
(17,'comprador07','$2y$12$8otVW/9vhVn/KdVRMmf92eaNPdBNuW7qjeSR20IUhbVL1QduMiIjC','Rocio','Pardo','comprador'),
(18,'comprador08','$2y$12$Y8Stdk1CplwU3KXu79DYiOuos26.8ifIzyuAiVP2q5cMXwR45kuk.','Simon','Vera','comprador'),
(19,'comprador09','$2y$12$QdpW7e29Heo23Jy97y84N.0ZshSF/kdvAu1aCEG5f1qke8yOiBiF2','Tania','Sosa','comprador'),
(20,'comprador10','$2y$12$Kji1OMcydNK4IGKdpb14vOC5N1EJXSobQryNvGI1rI54tZgnZFL/O','Ulises','Neri','comprador');

INSERT INTO `usuarios` (`id`,`usuario`,`password`,`nombre`,`apellido`,`tipo_usuario`) VALUES
(21,'comprador11','$2y$12$KIbxpklpMUi9k.TfrBtOVODsysVkMrLpohXCuy6U/33X/XE7/FZdq','Valentina','Pereira','comprador'),
(22,'comprador12','$2y$12$nl3zu7FlpJqb.ojuTC7dYO1zdPcqSfPucG21BbtKK2/szdAcsND4C','Mateo','Fernandez','comprador'),
(23,'comprador13','$2y$12$lhXIScAyhvOrDTlWhOiLYO6s8vNdV0htfBAxffjlcOcCqQYznENDC','Camila','Rodriguez','comprador'),
(24,'comprador14','$2y$12$8xg4kQ/LuKTmrg7UoATf7.G8Pfj4mVJhs01vv7eGtBfpsI6Mpbj4y','Thiago','Silva','comprador'),
(25,'comprador15','$2y$12$QeP6F192Lurz44WYoUbOMuu.2MPDf/Ov2EZBNSvl8uY76GqndAljK','Florencia','Martinez','comprador'),
(26,'comprador16','$2y$12$UuDy5kk1ivyJoo/sF8b/sOfK0lgYptxWGFpcAPLkPk9SGQ9VmeglG','Agustin','Suarez','comprador'),
(27,'comprador17','$2y$12$fpiDvKPSN/z.cJPpJ/Yzeed4D955aw5WAsRtvZhyQL9m4mQlBU7/W','Julieta','Gonzalez','comprador'),
(28,'comprador18','$2y$12$MYI6LxZtSAz3ZuD6s0SMFOp2yDNPQBST4c7B3FuEVOGCh74OYsmc6','Santiago','Torres','comprador'),
(29,'comprador19','$2y$12$OErWmsA6ahq6Kni3X7VnDO51RipsBdg6rSjyFYgnY.M8YX2QrabAe','Emilia','Castro','comprador'),
(30,'comprador20','$2y$12$2.qf21h6Si6SYvAAO3Q/ouasnJRtcJjaU2r4NPqAvtuHJQj74zjh2','Benjamin','Lopez','comprador');

-- AUTOS (50, asignaciones de administradores demo)
INSERT INTO `autos` (`id`,`administrador_id`,`marca`,`modelo`,`anio`,`color`,`kilometros`,`precio`) VALUES
(1,1,'Toyota','Corolla XEI',2022,'Blanco perlado',32000,28900.00),
(2,2,'Volkswagen','T-Cross',2023,'Gris plata',21000,31500.00),
(3,3,'Chevrolet','Onix Premier',2022,'Azul oscuro',28500,22400.00),
(4,4,'Hyundai','Creta',2024,'Negro',9800,36400.00),
(5,5,'Nissan','Kicks',2021,'Rojo',41700,24800.00),
(6,6,'Peugeot','208 Allure',2023,'Gris grafito',15600,26700.00),
(7,7,'Renault','Duster',2020,'Blanco',58700,21900.00),
(8,8,'Fiat','Cronos Precision',2024,'Plateado',7400,25500.00),
(9,9,'Honda','HR-V',2022,'Bordó',26300,34200.00),
(10,10,'Ford','Territory',2023,'Azul marino',19400,41200.00),
(11,1,'Suzuki','Vitara',2021,'Verde oliva',38800,27600.00),
(12,2,'Jeep','Renegade Sport',2022,'Negro',30100,33700.00),
(13,3,'Kia','Seltos',2024,'Blanco',6200,38900.00),
(14,4,'Mazda','CX-30',2023,'Rojo intenso',12700,40500.00),
(15,5,'Subaru','Crosstrek',2024,'Gris oscuro',4900,43800.00),
(16,6,'Toyota','Yaris',2021,'Blanco',39512,41656.00),
(17,7,'Volkswagen','Polo',2022,'Gris',33219,45447.00),
(18,8,'Chevrolet','Tracker',2023,'Azul',26926,16738.00),
(19,9,'Hyundai','HB20',2024,'Negro',20633,20529.00),
(20,10,'Nissan','Versa',2022,'Rojo',35940,24320.00),
(21,1,'Peugeot','2008',2021,'Plata',44047,28111.00),
(22,2,'Renault','Kwid',2023,'Verde',30554,31902.00),
(23,3,'Fiat','Argo',2022,'Bordó',38661,35693.00),
(24,4,'Honda','Civic',2020,'Blanco',53968,39484.00),
(25,5,'Ford','Ranger',2021,'Gris',36675,43275.00),
(26,6,'Suzuki','Swift',2024,'Azul',15982,14566.00),
(27,7,'Jeep','Compass',2022,'Negro',31289,18357.00),
(28,8,'Kia','Sportage',2023,'Rojo',24996,22148.00),
(29,9,'Mazda','Mazda3',2021,'Plata',40303,25939.00),
(30,10,'Subaru','Forester',2022,'Verde',34010,29730.00),
(31,1,'Toyota','Hilux',2020,'Bordó',49317,33521.00),
(32,2,'Volkswagen','Nivus',2024,'Blanco',21424,37312.00),
(33,3,'Chevrolet','Spin',2021,'Gris',43931,41103.00),
(34,4,'Hyundai','Tucson',2022,'Azul',37638,44894.00),
(35,5,'Nissan','Sentra',2023,'Negro',31345,16185.00),
(36,6,'Peugeot','3008',2020,'Rojo',53852,19976.00),
(37,7,'Renault','Sandero',2021,'Plata',36559,23767.00),
(38,8,'Fiat','Pulse',2024,'Verde',15866,27558.00),
(39,9,'Honda','Fit',2019,'Bordó',52773,31349.00),
(40,10,'Ford','EcoSport',2020,'Blanco',46480,35140.00),
(41,1,'Suzuki','S-Cross',2023,'Gris',25787,38931.00),
(42,2,'Jeep','Wrangler',2018,'Azul',62694,42722.00),
(43,3,'Kia','Rio',2021,'Negro',42001,14013.00),
(44,4,'Mazda','CX-5',2022,'Rojo',35708,17804.00),
(45,5,'Subaru','Impreza',2020,'Plata',51015,21595.00),
(46,6,'Toyota','RAV4',2023,'Verde',30322,25386.00),
(47,7,'Volkswagen','Virtus',2024,'Bordó',24029,29177.00),
(48,8,'Chevrolet','Montana',2023,'Blanco',32136,32968.00),
(49,9,'Renault','Oroch',2022,'Gris',29243,36759.00),
(50,10,'Fiat','Strada',2024,'Azul',15750,40550.00);

-- TASAS HISTORICAS (5, asignaciones de administradores demo)
INSERT INTO `configuracion_financiacion` (`id`,`administrador_id`,`tasa_interes_mensual`,`fecha_actualizacion`) VALUES
(1,1,2.00,'2026-11-17 01:15:21'),
(2,2,1.75,'2026-11-18 10:00:00'),
(3,3,2.25,'2026-11-19 10:00:00'),
(4,4,1.90,'2026-11-20 10:00:00'),
(5,5,2.50,'2026-11-21 10:00:00');

-- SIMULACIONES (120, clave debil por auto)
INSERT INTO `simulaciones` (`auto_id`,`numero_simulacion`,`comprador_id`,`configuracion_id`,`metodo_pago`,`precio_auto`,`entrega_inicial`,`cantidad_cuotas`,`fecha_simulacion`) VALUES
(14,1,18,2,'automotora',40500.00,6075.00,24,'2026-11-21 11:07:00'),
(27,1,25,3,'automotora',18357.00,3671.40,36,'2026-11-19 10:14:00'),
(40,1,12,4,'automotora',35140.00,8785.00,48,'2026-11-20 11:21:00'),
(3,1,19,NULL,'tarjeta',22400.00,0.00,12,'2026-11-21 12:28:00'),
(16,1,26,1,'automotora',41656.00,6248.40,72,'2026-11-17 13:35:00'),
(29,1,13,2,'automotora',25939.00,5187.80,84,'2026-11-18 14:42:00'),
(42,1,20,3,'automotora',42722.00,10680.50,100,'2026-11-19 15:49:00'),
(5,1,27,NULL,'tarjeta',24800.00,0.00,9,'2026-11-20 16:56:00'),
(18,1,14,5,'automotora',16738.00,2510.70,24,'2026-11-21 17:03:00'),
(31,1,21,1,'automotora',33521.00,6704.20,36,'2026-11-17 18:10:00'),
(44,1,28,2,'automotora',17804.00,4451.00,48,'2026-11-18 19:17:00'),
(7,1,15,NULL,'tarjeta',21900.00,0.00,6,'2026-11-19 08:24:00'),
(20,1,22,4,'automotora',24320.00,3648.00,72,'2026-11-21 11:31:00'),
(33,1,29,5,'automotora',41103.00,8220.60,84,'2026-11-21 10:38:00'),
(46,1,16,1,'automotora',25386.00,6346.50,100,'2026-11-17 11:45:00'),
(9,1,23,NULL,'tarjeta',34200.00,0.00,3,'2026-11-18 12:52:00'),
(22,1,30,3,'automotora',31902.00,4785.30,24,'2026-11-19 13:59:00'),
(35,1,17,4,'automotora',16185.00,3237.00,36,'2026-11-20 14:06:00'),
(48,1,24,5,'automotora',32968.00,8242.00,48,'2026-11-21 15:13:00'),
(11,1,11,NULL,'tarjeta',27600.00,0.00,1,'2026-11-17 16:20:00'),
(24,1,18,2,'automotora',39484.00,5922.60,72,'2026-11-18 17:27:00'),
(37,1,25,3,'automotora',23767.00,4753.40,84,'2026-11-19 18:34:00'),
(50,1,12,4,'automotora',40550.00,10137.50,100,'2026-11-20 19:41:00'),
(13,1,19,NULL,'tarjeta',38900.00,0.00,12,'2026-11-21 08:48:00'),
(26,1,26,1,'automotora',14566.00,2184.90,24,'2026-11-17 09:55:00'),
(39,1,13,2,'automotora',31349.00,6269.80,36,'2026-11-18 10:02:00'),
(2,1,20,3,'automotora',31500.00,7875.00,48,'2026-11-19 11:09:00'),
(15,1,27,NULL,'tarjeta',43800.00,0.00,9,'2026-11-20 12:16:00'),
(28,1,14,5,'automotora',22148.00,3322.20,72,'2026-11-21 13:23:00'),
(41,1,21,1,'automotora',38931.00,7786.20,84,'2026-11-17 14:30:00'),
(4,1,28,2,'automotora',36400.00,9100.00,100,'2026-11-18 15:37:00'),
(17,1,15,NULL,'tarjeta',45447.00,0.00,6,'2026-11-19 16:44:00'),
(30,1,22,4,'automotora',29730.00,4459.50,24,'2026-11-20 17:51:00'),
(43,1,29,5,'automotora',14013.00,2802.60,36,'2026-11-21 18:58:00'),
(6,1,16,1,'automotora',26700.00,6675.00,48,'2026-11-17 19:05:00'),
(19,1,23,NULL,'tarjeta',20529.00,0.00,3,'2026-11-18 08:12:00'),
(32,1,30,3,'automotora',37312.00,5596.80,72,'2026-11-21 11:19:00'),
(45,1,17,4,'automotora',21595.00,4319.00,84,'2026-11-20 10:26:00'),
(8,1,24,5,'automotora',25500.00,6375.00,100,'2026-11-21 11:33:00'),
(21,1,11,NULL,'tarjeta',28111.00,0.00,1,'2026-11-17 12:40:00'),
(34,1,18,2,'automotora',44894.00,6734.10,24,'2026-11-18 13:47:00'),
(47,1,25,3,'automotora',29177.00,5835.40,36,'2026-11-19 14:54:00'),
(10,1,12,4,'automotora',41200.00,10300.00,48,'2026-11-20 15:01:00'),
(23,1,19,NULL,'tarjeta',35693.00,0.00,12,'2026-11-21 16:08:00'),
(36,1,26,1,'automotora',19976.00,2996.40,72,'2026-11-17 17:15:00'),
(49,1,13,2,'automotora',36759.00,7351.80,84,'2026-11-18 18:22:00'),
(12,1,20,3,'automotora',33700.00,8425.00,100,'2026-11-19 19:29:00'),
(25,1,27,NULL,'tarjeta',43275.00,0.00,9,'2026-11-20 08:36:00'),
(38,1,14,5,'automotora',27558.00,4133.70,24,'2026-11-21 11:43:00'),
(1,1,21,1,'automotora',28900.00,5780.00,36,'2026-11-17 10:50:00'),
(14,2,28,2,'automotora',40500.00,10125.00,48,'2026-11-18 11:57:00'),
(27,2,15,NULL,'tarjeta',18357.00,0.00,6,'2026-11-19 12:04:00'),
(40,2,22,4,'automotora',35140.00,5271.00,72,'2026-11-20 13:11:00'),
(3,2,29,5,'automotora',22400.00,4480.00,84,'2026-11-21 14:18:00'),
(16,2,16,1,'automotora',41656.00,10414.00,100,'2026-11-17 15:25:00'),
(29,2,23,NULL,'tarjeta',25939.00,0.00,3,'2026-11-18 16:32:00'),
(42,2,30,3,'automotora',42722.00,6408.30,24,'2026-11-19 17:39:00'),
(5,2,17,4,'automotora',24800.00,4960.00,36,'2026-11-20 18:46:00'),
(18,2,24,5,'automotora',16738.00,4184.50,48,'2026-11-21 19:53:00'),
(31,2,11,NULL,'tarjeta',33521.00,0.00,1,'2026-11-17 08:00:00'),
(44,2,18,2,'automotora',17804.00,2670.60,72,'2026-11-21 11:07:00'),
(7,2,25,3,'automotora',21900.00,4380.00,84,'2026-11-19 10:14:00'),
(20,2,12,4,'automotora',24320.00,6080.00,100,'2026-11-20 11:21:00'),
(33,2,19,NULL,'tarjeta',41103.00,0.00,12,'2026-11-21 12:28:00'),
(46,2,26,1,'automotora',25386.00,3807.90,24,'2026-11-17 13:35:00'),
(9,2,13,2,'automotora',34200.00,6840.00,36,'2026-11-18 14:42:00'),
(22,2,20,3,'automotora',31902.00,7975.50,48,'2026-11-19 15:49:00'),
(35,2,27,NULL,'tarjeta',16185.00,0.00,9,'2026-11-20 16:56:00'),
(48,2,14,5,'automotora',32968.00,4945.20,72,'2026-11-21 17:03:00'),
(11,2,21,1,'automotora',27600.00,5520.00,84,'2026-11-17 18:10:00'),
(24,2,28,2,'automotora',39484.00,9871.00,100,'2026-11-18 19:17:00'),
(37,2,15,NULL,'tarjeta',23767.00,0.00,6,'2026-11-19 08:24:00'),
(50,2,22,4,'automotora',40550.00,6082.50,24,'2026-11-21 11:31:00'),
(13,2,29,5,'automotora',38900.00,7780.00,36,'2026-11-21 10:38:00'),
(26,2,16,1,'automotora',14566.00,3641.50,48,'2026-11-17 11:45:00'),
(39,2,23,NULL,'tarjeta',31349.00,0.00,3,'2026-11-18 12:52:00'),
(2,2,30,3,'automotora',31500.00,4725.00,72,'2026-11-19 13:59:00'),
(15,2,17,4,'automotora',43800.00,8760.00,84,'2026-11-20 14:06:00'),
(28,2,24,5,'automotora',22148.00,5537.00,100,'2026-11-21 15:13:00'),
(41,2,11,NULL,'tarjeta',38931.00,0.00,1,'2026-11-17 16:20:00'),
(4,2,18,2,'automotora',36400.00,5460.00,24,'2026-11-18 17:27:00'),
(17,2,25,3,'automotora',45447.00,9089.40,36,'2026-11-19 18:34:00'),
(30,2,12,4,'automotora',29730.00,7432.50,48,'2026-11-20 19:41:00'),
(43,2,19,NULL,'tarjeta',14013.00,0.00,12,'2026-11-21 08:48:00'),
(6,2,26,1,'automotora',26700.00,4005.00,72,'2026-11-17 09:55:00'),
(19,2,13,2,'automotora',20529.00,4105.80,84,'2026-11-18 10:02:00'),
(32,2,20,3,'automotora',37312.00,9328.00,100,'2026-11-19 11:09:00'),
(45,2,27,NULL,'tarjeta',21595.00,0.00,9,'2026-11-20 12:16:00'),
(8,2,14,5,'automotora',25500.00,3825.00,24,'2026-11-21 13:23:00'),
(21,2,21,1,'automotora',28111.00,5622.20,36,'2026-11-17 14:30:00'),
(34,2,28,2,'automotora',44894.00,11223.50,48,'2026-11-18 15:37:00'),
(47,2,15,NULL,'tarjeta',29177.00,0.00,6,'2026-11-19 16:44:00'),
(10,2,22,4,'automotora',41200.00,6180.00,72,'2026-11-20 17:51:00'),
(23,2,29,5,'automotora',35693.00,7138.60,84,'2026-11-21 18:58:00'),
(36,2,16,1,'automotora',19976.00,4994.00,100,'2026-11-17 19:05:00'),
(49,2,23,NULL,'tarjeta',36759.00,0.00,3,'2026-11-18 08:12:00'),
(12,2,30,3,'automotora',33700.00,5055.00,24,'2026-11-21 11:19:00'),
(25,2,17,4,'automotora',43275.00,8655.00,36,'2026-11-20 10:26:00'),
(38,2,24,5,'automotora',27558.00,6889.50,48,'2026-11-21 11:33:00'),
(1,2,11,NULL,'tarjeta',28900.00,0.00,1,'2026-11-17 12:40:00'),
(14,3,18,2,'automotora',40500.00,6075.00,72,'2026-11-18 13:47:00'),
(27,3,25,3,'automotora',18357.00,3671.40,84,'2026-11-19 14:54:00'),
(40,3,12,4,'automotora',35140.00,8785.00,100,'2026-11-20 15:01:00'),
(3,3,19,NULL,'tarjeta',22400.00,0.00,12,'2026-11-21 16:08:00'),
(16,3,26,1,'automotora',41656.00,6248.40,24,'2026-11-17 17:15:00'),
(29,3,13,2,'automotora',25939.00,5187.80,36,'2026-11-18 18:22:00'),
(42,3,20,3,'automotora',42722.00,10680.50,48,'2026-11-19 19:29:00'),
(5,3,27,NULL,'tarjeta',24800.00,0.00,9,'2026-11-20 08:36:00'),
(18,3,14,5,'automotora',16738.00,2510.70,72,'2026-11-21 11:43:00'),
(31,3,21,1,'automotora',33521.00,6704.20,84,'2026-11-17 10:50:00'),
(44,3,28,2,'automotora',17804.00,4451.00,100,'2026-11-18 11:57:00'),
(7,3,15,NULL,'tarjeta',21900.00,0.00,6,'2026-11-19 12:04:00'),
(20,3,22,4,'automotora',24320.00,3648.00,24,'2026-11-20 13:11:00'),
(33,3,29,5,'automotora',41103.00,8220.60,36,'2026-11-21 14:18:00'),
(46,3,16,1,'automotora',25386.00,6346.50,48,'2026-11-17 15:25:00'),
(9,3,23,NULL,'tarjeta',34200.00,0.00,3,'2026-11-18 16:32:00'),
(22,3,30,3,'automotora',31902.00,4785.30,72,'2026-11-19 17:39:00'),
(35,3,17,4,'automotora',16185.00,3237.00,84,'2026-11-20 18:46:00'),
(48,3,24,5,'automotora',32968.00,8242.00,100,'2026-11-21 19:53:00'),
(11,3,11,NULL,'tarjeta',27600.00,0.00,1,'2026-11-17 08:00:00');

-- ================================================================
-- 3. CONSULTAS COMPLEJAS DE NEGOCIO (RUBRICA ITEM 6)
-- ================================================================
-- Compradores con al menos dos simulaciones (no son ventas).
SELECT u.usuario, CONCAT(u.nombre,' ',u.apellido) AS comprador,
       COUNT(*) AS cantidad_simulaciones,
       SUM(s.precio_auto) AS valor_simulado
FROM usuarios u
JOIN simulaciones s ON s.comprador_id = u.id
WHERE u.tipo_usuario = 'comprador'
GROUP BY u.id,u.usuario,u.nombre,u.apellido
HAVING COUNT(*) >= 2
ORDER BY cantidad_simulaciones DESC, valor_simulado DESC;

-- Marcas consultadas al menos dos veces.
SELECT a.marca, COUNT(*) AS simulaciones,
       COUNT(DISTINCT a.id) AS autos_distintos,
       SUM(s.precio_auto) AS valor_total_consultado
FROM autos a
JOIN simulaciones s ON s.auto_id = a.id
GROUP BY a.marca
HAVING COUNT(*) >= 2
ORDER BY simulaciones DESC;

-- Configuraciones financieras utilizadas en al menos dos simulaciones.
SELECT c.id AS configuracion_id, c.tasa_interes_mensual,
       COUNT(*) AS cantidad_simulaciones,
       SUM(s.precio_auto-s.entrega_inicial) AS capital_simulado
FROM configuracion_financiacion c
JOIN simulaciones s ON s.configuracion_id = c.id
WHERE s.metodo_pago = 'automotora'
GROUP BY c.id,c.tasa_interes_mensual
HAVING COUNT(*) >= 2
ORDER BY cantidad_simulaciones DESC;

-- ================================================================
-- 4. VISTAS VDL (RUBRICA ITEM 8)
-- ================================================================
-- Catalogo sin datos personales; incluye administrador responsable.
CREATE VIEW `vista_catalogo_autos` AS
SELECT a.id AS auto_id,a.marca,a.modelo,a.anio,a.color,a.kilometros,a.precio
FROM autos a;

-- Ultima configuracion insertada (historial inmutable).
CREATE VIEW `vista_tasa_vigente` AS
SELECT c.id AS configuracion_id,c.tasa_interes_mensual,c.fecha_actualizacion
FROM configuracion_financiacion c
WHERE c.id = (SELECT MAX(id) FROM configuracion_financiacion);

CREATE VIEW `vista_interes_por_marca` AS
SELECT a.marca,COUNT(DISTINCT a.id) AS autos_en_catalogo,
       COUNT(s.numero_simulacion) AS cantidad_simulaciones,
       COALESCE(SUM(s.precio_auto),0.00) AS valor_total_simulado
FROM autos a
LEFT JOIN simulaciones s ON s.auto_id = a.id
GROUP BY a.marca;

CREATE VIEW `vista_resumen_financiacion` AS
SELECT c.id AS configuracion_id,c.tasa_interes_mensual,c.fecha_actualizacion,
       COUNT(s.numero_simulacion) AS cantidad_simulaciones,
       COALESCE(SUM(s.precio_auto-s.entrega_inicial),0.00) AS capital_total_simulado
FROM configuracion_financiacion c
LEFT JOIN simulaciones s ON s.configuracion_id=c.id AND s.metodo_pago='automotora'
GROUP BY c.id,c.tasa_interes_mensual,c.fecha_actualizacion;

-- Vista adicional: gestion de autos por administrador sin mostrar hashes.
CREATE VIEW `vista_autos_por_administrador` AS
SELECT u.id AS administrador_id,u.usuario AS administrador,
       COUNT(a.id) AS autos_organizados
FROM usuarios u
LEFT JOIN autos a ON a.administrador_id=u.id
WHERE u.tipo_usuario='administrador'
GROUP BY u.id,u.usuario;

-- Vista adicional: historico de tasas y administrador que las configuro.
CREATE VIEW `vista_historial_tasas` AS
SELECT c.id AS configuracion_id,c.tasa_interes_mensual,c.fecha_actualizacion,
       u.usuario AS administrador
FROM configuracion_financiacion c
JOIN usuarios u ON u.id=c.administrador_id;

-- PRUEBAS CONSULTABLES EN PHPMYADMIN
SELECT 'usuarios' AS tabla,COUNT(*) AS registros FROM usuarios
UNION ALL SELECT 'autos',COUNT(*) FROM autos
UNION ALL SELECT 'configuracion_financiacion',COUNT(*) FROM configuracion_financiacion
UNION ALL SELECT 'simulaciones',COUNT(*) FROM simulaciones;

SELECT * FROM vista_catalogo_autos LIMIT 5;
SELECT * FROM vista_tasa_vigente;
SELECT * FROM vista_interes_por_marca LIMIT 5;
SELECT * FROM vista_resumen_financiacion LIMIT 5;
SELECT * FROM vista_autos_por_administrador;
SELECT * FROM vista_historial_tasas;

-- Verificacion de relaciones reales:
SELECT TABLE_NAME,COLUMN_NAME,REFERENCED_TABLE_NAME,REFERENCED_COLUMN_NAME
FROM information_schema.KEY_COLUMN_USAGE
WHERE TABLE_SCHEMA='automotora_mer_v2_pruebas' AND REFERENCED_TABLE_NAME IS NOT NULL;

-- ================================================================
-- 5. DCL: MINIMO PRIVILEGIO (RUBRICA ITEM 5)
-- Ejecutar bajo un usuario administrador del servidor MariaDB.
-- La seccion DCL sigue incluida en ESTE MISMO archivo.
-- ================================================================
CREATE USER IF NOT EXISTS 'automotora_mer_app_demo'@'localhost'
  IDENTIFIED BY 'CAMBIAR_ANTES_DE_USAR_MER_APP_2026';
CREATE USER IF NOT EXISTS 'automotora_mer_reportes_demo'@'localhost'
  IDENTIFIED BY 'CAMBIAR_ANTES_DE_USAR_MER_REPORTES_2026';

GRANT SELECT,INSERT,UPDATE ON `automotora_mer_v2_pruebas`.`usuarios`
  TO 'automotora_mer_app_demo'@'localhost';
GRANT SELECT,INSERT,UPDATE,DELETE ON `automotora_mer_v2_pruebas`.`autos`
  TO 'automotora_mer_app_demo'@'localhost';
GRANT SELECT,INSERT ON `automotora_mer_v2_pruebas`.`configuracion_financiacion`
  TO 'automotora_mer_app_demo'@'localhost';
GRANT SELECT,INSERT ON `automotora_mer_v2_pruebas`.`simulaciones`
  TO 'automotora_mer_app_demo'@'localhost';

-- REVOKE explicito para demostrar eliminacion de un privilegio.
GRANT SELECT ON `automotora_mer_v2_pruebas`.`autos`
  TO 'automotora_mer_reportes_demo'@'localhost';
REVOKE SELECT ON `automotora_mer_v2_pruebas`.`autos`
  FROM 'automotora_mer_reportes_demo'@'localhost';

GRANT SELECT ON `automotora_mer_v2_pruebas`.`vista_catalogo_autos`
  TO 'automotora_mer_reportes_demo'@'localhost';
GRANT SELECT ON `automotora_mer_v2_pruebas`.`vista_tasa_vigente`
  TO 'automotora_mer_reportes_demo'@'localhost';
GRANT SELECT ON `automotora_mer_v2_pruebas`.`vista_interes_por_marca`
  TO 'automotora_mer_reportes_demo'@'localhost';
GRANT SELECT ON `automotora_mer_v2_pruebas`.`vista_resumen_financiacion`
  TO 'automotora_mer_reportes_demo'@'localhost';
GRANT SELECT ON `automotora_mer_v2_pruebas`.`vista_autos_por_administrador`
  TO 'automotora_mer_reportes_demo'@'localhost';
GRANT SELECT ON `automotora_mer_v2_pruebas`.`vista_historial_tasas`
  TO 'automotora_mer_reportes_demo'@'localhost';

-- IMPORTANTE: En tu MariaDB, FLUSH PRIVILEGES genero error Aria 176
-- (Read page with wrong checksum). No se ejecuta automaticamente para
-- evitar repetir la falla. GRANT y REVOKE se aplican de inmediato.
-- La rubrica pide demostrar FLUSH PRIVILEGES: DESPUES de diagnosticar
-- y reparar el problema de Aria se debe ejecutar, con permisos adecuados:
-- FLUSH PRIVILEGES;

SHOW GRANTS FOR 'automotora_mer_app_demo'@'localhost';
SHOW GRANTS FOR 'automotora_mer_reportes_demo'@'localhost';

-- ================================================================
-- 6. CONSULTAS DE OTROS GRUPOS (RUBRICA ITEM 7)
-- PENDIENTE: incorporar DOS consultas que de verdad hayan propuesto
-- otros grupos y adaptarlas a este esquema. No inventar su origen.
-- ================================================================

-- COMPATIBILIDAD PHP:
-- El proyecto PHP actual usa `automotora` con autos sin administrador_id
-- y simulaciones con id global. Este archivo NO es compatible sin cambios
-- a Conexion.php, Auto.php, Financiacion.php y la persistencia de simulaciones.
-- No cambiar las credenciales PHP ni apuntarlo a esta base hasta adaptar.
-- Al crear una simulacion: numero_simulacion debe asignarse por auto de
-- forma concurrentemente segura dentro de una transaccion con bloqueo.
