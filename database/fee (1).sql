-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 14, 2019 at 01:22 AM
-- Server version: 10.1.38-MariaDB
-- PHP Version: 7.3.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `fee`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `CopyInvoices` (IN `enterprise_id` INT UNSIGNED)  BEGIN
	INSERT INTO enterprise_invoces(AC_code, AC_name, AC_fname, AC_type, AC_group, enterprise_id)
SELECT AC_code, AC_name, AC_fname, AC_type, AC_group, enterprise_id
FROM invoices;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `account_transactions`
--

CREATE TABLE `account_transactions` (
  `AT_id` int(11) NOT NULL,
  `AT_createuser` int(11) NOT NULL,
  `AT_createdatetime` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  `AT_lastupdateuser` int(11) DEFAULT NULL,
  `AT_lastupdatetime` datetime(3) DEFAULT NULL,
  `AT_transactiondatetime` datetime(3) NOT NULL,
  `AT_transactionyear` int(11) NOT NULL,
  `AT_transactionficheno` int(11) NOT NULL,
  `AT_type` int(11) NOT NULL,
  `AT_code` varchar(50) NOT NULL,
  `AT_listno` int(11) NOT NULL DEFAULT '0',
  `AT_transactiondescription` varchar(250) DEFAULT NULL,
  `AT_amount` int(11) NOT NULL,
  `AT_tradingrecid` int(11) NOT NULL DEFAULT '0',
  `AT_projectcode` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `enterprise`
--

CREATE TABLE `enterprise` (
  `name` varchar(100) NOT NULL,
  `address` varchar(45) DEFAULT NULL,
  `lastname` varchar(45) NOT NULL,
  `secondname` varchar(45) DEFAULT NULL,
  `inn` varchar(15) NOT NULL,
  `fincode` varchar(10) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `enterprise`
--

INSERT INTO `enterprise` (`name`, `address`, `lastname`, `secondname`, `inn`, `fincode`, `user_id`, `updated_at`, `created_at`) VALUES
('MTS', 'Baku', 'Mobile', 'LLC', '100099921000000', '80090020', 1, '2019-05-13 15:04:36', '2019-05-11 08:56:33'),
('dsfdf', '546546', 'dsfsdfds', 'fdsfsfd', '546546', '34564654', 2, '2019-05-13 13:09:01', '2019-05-13 13:09:01');

-- --------------------------------------------------------

--
-- Table structure for table `enterprise_invoces`
--

CREATE TABLE `enterprise_invoces` (
  `AC_id` int(11) NOT NULL,
  `AC_code` varchar(50) NOT NULL,
  `AC_name` varchar(150) NOT NULL,
  `AC_fname` varchar(150) DEFAULT NULL,
  `AC_type` int(11) NOT NULL DEFAULT '0',
  `AC_group` int(11) NOT NULL DEFAULT '0',
  `enterprise_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `parent_id` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `enterprise_invoces`
--

INSERT INTO `enterprise_invoces` (`AC_id`, `AC_code`, `AC_name`, `AC_fname`, `AC_type`, `AC_group`, `enterprise_id`, `created_at`, `updated_at`, `parent_id`) VALUES
(150, '900', '555', '555', 555, 555, 1, '2019-05-13 21:47:16', '2019-05-13 17:47:16', 0),
(151, '6.6', 'FFFF', NULL, 6, 6, 1, '2019-05-13 19:09:48', '2019-05-13 19:09:48', 0);

-- --------------------------------------------------------

--
-- Table structure for table `fiche_types`
--

CREATE TABLE `fiche_types` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `fiche_types`
--

INSERT INTO `fiche_types` (`id`, `name`) VALUES
(1, 'Offsetting Eff');

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `AC_id` int(11) NOT NULL,
  `AC_code` varchar(50) NOT NULL,
  `AC_name` varchar(150) NOT NULL,
  `AC_fname` varchar(150) NOT NULL,
  `AC_type` int(11) NOT NULL,
  `AC_group` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `invoices`
--

INSERT INTO `invoices` (`AC_id`, `AC_code`, `AC_name`, `AC_fname`, `AC_type`, `AC_group`) VALUES
(2, '1', 'Uzunmüddətli aktivlər', 'Uzunmüddətli aktivlər', 0, 0),
(3, '10', 'Qeyri-maddi aktivlər', '', 0, 0),
(4, '101', 'Qeyri-maddi aktivlər - Dəyər', '', 0, 0),
(5, '102', 'Qeyri-maddi aktivlər - Amortizasiya', '', 0, 1),
(6, '103', 'Qeyri-maddi aktivlərlə bağlı məsrəflərin kapitallaşdırılması', '', 0, 0),
(7, '11', 'Torpaq; tikili və avadanlıqlar', '', 0, 0),
(8, '111', 'Torpaq; tikili və avadanlıqlar - Dəyər', '', 0, 0),
(9, '112', 'Torpaq; tikili və avadanlıqlar - Amortizasiya', '', 0, 1),
(10, '113', 'Torpaq; tikili və avadanlıqlarla bağlı məsrəflərin kapitallaşdırılması', '', 0, 0),
(11, '12', 'Daşınmaz əmlaka investisiyalar', '', 0, 0),
(12, '121', 'Daşınmaz əmlaka investisiyalar - Dəyər', '', 0, 0),
(13, '122', 'Daşınmaz əmlaka investisiyalar - Amortizasiya', '', 0, 1),
(14, '13', 'Bioloji aktivlər', '', 0, 0),
(15, '131', 'Bioloji aktivlər - Dəyər', '', 0, 0),
(16, '132', 'Bioloji aktivlər - Amortizasiya', '', 0, 1),
(17, '14', 'Təbii sərvətlər', '', 0, 0),
(18, '141', 'Təbii sərvətlər- Dəyər', '', 0, 0),
(19, '142', 'Təbii sərvətlər - Tükənməsi', '', 0, 1),
(20, '15', 'İştirak payı metodu ilə uçota alınmış investisiyalar', '', 0, 0),
(21, '151', 'Asılı müəssisələrə investisiyalar', '', 0, 0),
(22, '152', 'Birgə müəssisələrə investisiyalar', '', 0, 0),
(23, '16', 'Təxirə salınmış vergi aktivləri', '', 0, 0),
(24, '161', 'Mənfəət vergisi üzrə təxirə salınmış vergi aktivlərı', '', 0, 0),
(25, '162', 'Digər təxirə salınmış vergi aktivlərı', '', 0, 0),
(26, '17', 'Uzunmüddətli debitor borcları', '', 0, 0),
(27, '171', 'Alıcılar və sifarişçilərin uzunmüddətli debitor borcları', '', 0, 0),
(28, '172', 'Törəmə(asılı) müəssisələrin uzunmüddətli debitor borcları', '', 0, 0),
(29, '173', 'Əsas idarəetmə heyətinin uzunmüddətli debitor borcları', '', 0, 0),
(30, '174', 'İcarə üzrə uzunmüddətli debitor borcları', '', 0, 0),
(31, '175', 'Tikinti müqavilələri üzrə uzunmüddətli debitor borcları', '', 0, 0),
(32, '176', 'Faizlər üzrə uzunmüddətli debitor borcları', '', 0, 0),
(33, '177', 'Digər uzunmüddətli debitor borcları', '', 0, 0),
(34, '18', 'Sair uzunmüddətli maliyyə aktivləri', '', 0, 0),
(35, '181', 'Ödənişə qədər saxlanılan uzunmüddətli investisiyalar', '', 0, 0),
(36, '182', 'Uzunmüddətli verilmiş borclar', '', 0, 0),
(37, '183', 'Digər uzunmüddətli investisiyalar', '', 0, 0),
(38, '184', 'Sair uzunmüddətli maliyyə aktivlərinin dəyərinin azalmasına görə düzəlişlər', '', 0, 1),
(39, '19', 'Sair uzunmüddətli aktivlər', '', 0, 0),
(40, '191', 'Gələcək hesabat dövrlərinin xərcləri', '', 0, 0),
(41, '192', 'Verilmiş uzunmüddətli avanslar', '', 0, 0),
(42, '193', 'Digər uzunmüddətli aktivlər', '', 0, 0),
(43, '2', 'Qısamüddətli aktivlər', '', 0, 0),
(44, '20', 'Ehtiyatlar', '', 0, 0),
(45, '201', 'Material ehtiyatları', '', 0, 0),
(46, '202', 'Istehsalat məsrəfləri', '', 0, 0),
(47, '203', 'Tikinti müqavilələri üzrə bitməmış tikinti işləri', '', 0, 0),
(48, '204', 'Hazır məhsul', '', 0, 0),
(49, '205', 'Mallar', '', 0, 0),
(50, '206', 'Satış məqsədilə saxlanılan digər aktivlər', '', 0, 0),
(51, '207', 'Digər ehtiyatlar', '', 0, 0),
(52, '208', 'Ehtiyatların dəyərinin azalmasına görə düzəlişlər', '', 0, 1),
(53, '21', 'Qısamüddətli debitor borcları', '', 0, 0),
(54, '211', 'Alıcılar və sifarişçilərin qısamüddətli debitor borcları', '', 0, 0),
(55, '212', 'Törəmə (asılı) müəssisələrin qısamüddətli debitor borcları', '', 0, 0),
(56, '213', 'Əsas idarəetmə heyətin qısamüddətli debitor borcları', '', 0, 0),
(57, '214', 'İcarə üzrə qısamüddətli debitor borcları', '', 0, 0),
(58, '215', 'Tikinti müqavilələri üzrə qısamüddətli debitor borcları', '', 0, 0),
(59, '216', 'Faizlər üzrə qısamüddətli debitor borcları', '', 0, 0),
(60, '217', 'Digər qısamüddətli debitor borcları', '', 0, 0),
(61, '218', 'Şübhəli borclar üzrə düzəlişlər', '', 0, 1),
(62, '22', 'Pul vəsaitləri və onların ekvivalentlərı', '', 0, 0),
(63, '221', 'Kassa', '', 0, 0),
(64, '222', 'Yolda olan pul köçürmələri', '', 0, 0),
(65, '223', 'Bank hesablaşma hesabları', '', 0, 0),
(66, '224', 'Tələblərə əsasən verilən digər bank hesabları', '', 0, 0),
(67, '225', 'Pul vəsaitlərinin ekvivalentlərı', '', 0, 0),
(68, '23', 'Sair qısamüddətli maliyyə aktivləri', '', 0, 0),
(69, '231', 'Satış məqsədilə saxlanılan qısamüddətli investisiyalar', '', 0, 0),
(70, '232', 'Ödənişə qədər saxlanılan qısamüddətli investisiyalar', '', 0, 0),
(71, '233', 'Qısamüddətli verilmiş borclar', '', 0, 0),
(72, '234', 'Digər qısamüddətli investisiyalar', '', 0, 0),
(73, '235', 'Sair qısamüddətli maliyyə aktivlərinin dəyərinin azalmasına görə düzəlişlər', '', 0, 1),
(74, '24', 'Sair qısamüddətli aktivlər', '', 0, 0),
(75, '241', 'Əvəzləşdirilən əlavə dəyər vergisi', '', 0, 0),
(76, '242', 'Gələcək hesabat dövrünün xərcləri', '', 0, 0),
(77, '243', 'Verilmiş qısamüddətli avanslar', '', 0, 0),
(78, '244', 'Təhtəlhesab məbləğlər', '', 0, 0),
(79, '245', 'Digər qısamüddətli aktivlər', '', 0, 0),
(80, '3', 'Kapital', '', 1, 1),
(81, '30', 'Ödənilmiş nominal(nizamnamə) kapital', '', 1, 1),
(82, '301', 'Nominal(nizamnamə) kapital', '', 1, 1),
(83, '302', 'Nominal(nizamnamə) kapitalın ödənilməmiş hissəsi', '', 1, 0),
(84, '31', 'Emissiya gəliri', '', 1, 1),
(85, '311', 'Emissiya gəliri', '', 1, 1),
(86, '32', 'Geri alınmış kapital (səhmlər)', '', 1, 1),
(87, '321', 'Geri alınmış kapital (səhmlər)', '', 1, 1),
(88, '33', 'Kapital ehtiyatları', '', 1, 1),
(89, '331', 'Yenidən giymətləndirilmə üzrə ehtiyat', '', 1, 1),
(90, '332', 'Məzənnə fərgləri üzrə ehtiyat', '', 1, 1),
(91, '333', 'Qanunvericilik üzrə ehtiyat', '', 1, 1),
(92, '334', 'Nizamnamə üzrə ehtiyat', '', 1, 1),
(93, '335', 'Digər ehtiyatlar', '', 1, 1),
(94, '34', 'Bölüşdürülməmiş mənfəət (ödənilməmiş zərər)', '', 1, 2),
(95, '341', 'Hesabat dövründə xalis mənfəət (zərər)', '', 1, 2),
(96, '342', 'Mühasibat uçotu siyasətində dəyişikliklər bağlı mənfəətin (zərərin) düzəlişi', '', 1, 2),
(97, '343', 'Keçmiş illər üzrə bölüşdürülməmiş mənfəət (ödənilməmiş zərər)', '', 1, 2),
(98, '344', 'Elan edilmiş dividendlər', '', 1, 1),
(99, '4', 'Uzunmüddətli öhdəliklər', '', 1, 1),
(100, '40', 'Uzunmüddətli faiz xərcləri yaradan öhdəliklər', '', 1, 1),
(101, '401', 'Uzunmüddətli bank kreditləri', '', 1, 1),
(102, '402', 'İşçilər üçün uzunmüddətli bank kreditləri', '', 1, 1),
(103, '403', 'Uzunmüddətli konvertasiya olunan istiqrazlar', '', 1, 1),
(104, '404', 'Uzunmüddətli borclar', '', 1, 1),
(105, '405', 'Geri alınan məhdud tədavül müddətli imtiyazlı səhmlər (uzunmüddətli)', '', 1, 1),
(106, '406', 'Maliyyə icarəsi üzrə uzunmüddətli öhdəliklər', '', 1, 1),
(107, '407', 'Törəmə(asılı) müəssisələrə uzunmüddətli faiz xərcləri yaradan öhdəliklər', '', 1, 1),
(108, '408', 'Digər uzunmüddətli faiz xərcləri yaradan öhdəliklər', '', 1, 1),
(109, '41', 'Uzunmüddətli qiymətləndirilmiş öhdəliklər', '', 1, 1),
(110, '411', 'İşdən azad olma ilə bağlı uzunmüddətli müavinətlər və öhdəliklər', '', 1, 1),
(111, '412', 'Uzunmüddətli zəmanət öhdəlikləri', '', 1, 1),
(112, '413', 'Uzunmüddətli hüquqi öhdəliklər', '', 1, 1),
(113, '414', 'Digər uzunmüddətli qiymətləndirilmiş öhdəliklər', '', 1, 1),
(114, '42', 'Təxirə salınmış vergi öhdəlikləri', '', 1, 1),
(115, '421', 'Mənfəət vergisi üzrə təxirə salınmış vergi öhdəlikləri', '', 1, 1),
(116, '422', 'Digər təxirə salınmış vergi öhdəliklər', '', 1, 1),
(117, '43', 'Uzunmüddətli kreditor borcları', '', 1, 1),
(118, '431', 'Malsatan və podratçılara uzunmüddətli kreditor borcları', '', 1, 1),
(119, '432', 'Törəmə(asılı) müəssisələrə uzunmüddətli kreditor borcları', '', 1, 1),
(120, '433', 'Tikinti müqavilələri üzrə uzunmüddətli kreditor borcları', '', 1, 1),
(121, '434', 'Faizlər üzrə uzunmüddətli kreditor borcları', '', 1, 1),
(122, '435', 'Digər uzunmüddətli kreditor borcları', '', 1, 1),
(123, '44', 'Sair uzunmüddətli öhdəliklər', '', 1, 1),
(124, '441', 'Uzunmüddətli pensiya öhdəlikləri', '', 1, 1),
(125, '442', 'Gələcək hesabat dövrlərin gəlirləri', '', 1, 1),
(126, '443', 'Alınmış uzunmüddətli avanslar', '', 1, 1),
(127, '444', 'Uzunmüddətli məqsədli maliyyələşmələr və daxilolmalar', '', 1, 1),
(128, '445', 'Digər uzunmüddətli öhdəliklər', '', 1, 1),
(129, '5', 'Qısamüddətli öhdəliklər', '', 1, 1),
(130, '50', 'Qısamüddətli faiz xərcləri yaradan öhdəliklər', '', 1, 1),
(131, '501', 'Qısamüddətli bank kreditləri', '', 1, 1),
(132, '502', 'İşçilər üçün qısamüddətli bank kreditləri', '', 1, 1),
(133, '503', 'Qısamüddətli konvertasiya olunan istiqrazlar', '', 1, 1),
(134, '504', 'Qısamüddətli borclar', '', 1, 1),
(135, '505', 'Geri alınan məhdud tədavül müddətli imtiyazlı səhmlər (qısamüddətli)', '', 1, 1),
(136, '506', 'Törəmə(asılı) müəssisələrə qısamüddətli faiz xərcləri yaradan öhdəliklər', '', 1, 1),
(137, '507', 'Digər qısamüddətli faiz xərcləri yaradan öhdəliklər', '', 1, 1),
(138, '51', 'Qısamüddətli qiymətləndirilmiş öhdəliklər', '', 1, 1),
(139, '511', 'İşdən azad olunması ilə bağlı qısamüddətli müavinətlər öhdəliklər', '', 1, 1),
(140, '512', 'Qısamüddətli zəmanət öhdəlikləri', '', 1, 1),
(141, '513', 'Qısamüddətli hüquqi öhdəliklər', '', 1, 1),
(142, '514', 'Mənfəətdə iştirak planı və müavinət planları', '', 1, 1),
(143, '515', 'Digər qısamüddətli qiymətləndirilmiş öhdəliklər', '', 1, 1),
(144, '52', 'Vergi və sair məcburi ödənişlər üzrə öhdəliklər', '', 1, 1),
(145, '521', 'Vergi öhdəlikləri', '', 1, 1),
(146, '522', 'Sosial sığorta və təminat üzrə öhdəliklər', '', 1, 1),
(147, '523', 'Digər məcburi ödənişlər üzrə öhdəliklər', '', 1, 1),
(148, '53', 'Qısamüddətli kreditor borcları', '', 1, 1),
(149, '531', 'Malsatan və podratçılara qısamüddətli kreditor borcları', '', 1, 1),
(150, '532', 'Törəmə(asılı) müəssisələrə qısamüddətli kreditor borcları', '', 1, 1),
(151, '533', 'Əməyin ödənişi üzrə işçi heyətinə olan borclar', '', 1, 1),
(152, '534', 'Dividendlərin ödənilməsi üzrə təsisçilərə kreditor borcları', '', 1, 1),
(153, '535', 'İcarə üzrə qısamüddətli kreditor borcları', '', 1, 1),
(154, '536', 'Tikinti müqavilələri üzrə qısamüddətli kreditor borcları', '', 1, 1),
(155, '537', 'Faizlər üzrə qısamüddətli kreditor borcları', '', 1, 1),
(156, '538', 'Digər qısamüddətli kreditor borcları', '', 1, 1),
(157, '54', 'Sair qısamüddətli öhdəliklər', '', 1, 1),
(158, '541', 'Qısamüddətli pensiya öhdəlikləri', '', 1, 1),
(159, '542', 'Gələcək hesabat dövrünün gəlirləri', '', 1, 1),
(160, '543', 'Alınmış qısamüddətli avanslar', '', 1, 1),
(161, '544', 'Qisamüddətli məqsədli maliyyələşmələr və daxilolmalar', '', 1, 1),
(162, '545', 'Digər qısamüddətli öhdəliklər', '', 1, 1),
(163, '6', 'Gəlirlər', '', 2, 1),
(164, '60', 'Əsas əməliyyat gəliri', '', 2, 1),
(165, '601', 'Satış', '', 2, 1),
(166, '602', 'Satılmış malların qaytarılması və ucuzlaşdırılması', '', 3, 0),
(167, '603', 'Verilmiş güzəştlər', '', 3, 0),
(168, '61', 'Sair əməliyyat gəlirləri', '', 2, 1),
(169, '611', 'Sair əməliyyat gəlirləri', '', 2, 1),
(170, '62', 'Fəaliyyətin dayandırılmasından mənfəətlər', '', 2, 1),
(171, '621', 'Fəaliyyətin dayandırılmasından mənfəətlər', '', 2, 1),
(172, '63', 'Maliyyə gəlirləri', '', 2, 1),
(173, '631', 'Maliyyə gəlirləri', '', 2, 1),
(174, '64', 'Fövqəladə gəlirlər', '', 2, 1),
(175, '641', 'Fövqəladə gəlirlər', '', 2, 1),
(176, '7', 'Xərclər', '', 3, 0),
(177, '70', 'Satışın maya dəyəri', '', 3, 0),
(178, '701', 'Satışın maya dəyəri', '', 3, 0),
(179, '71', 'Kommersiya xərcləri', '', 3, 0),
(180, '711', 'Kommersiya xərcləri', '', 3, 0),
(181, '72', 'İnzibati xərclər', '', 3, 0),
(182, '721', 'İnzibati xərclər', '', 3, 0),
(183, '73', 'Sair əməliyyat xərcləri', '', 3, 0),
(184, '731', 'Sair əməliyyat xərcləri', '', 3, 0),
(185, '74', 'Fəaliyyətin dayandırılmasından zərərlər', '', 3, 0),
(186, '741', 'Fəaliyyətin dayandırılmasından zərərlər', '', 3, 0),
(187, '75', 'Maliyyə xərcləri', '', 3, 0),
(188, '751', 'Maliyyə xərcləri', '', 3, 0),
(189, '76', 'Fövqəladə xərclər', '', 3, 0),
(190, '761', 'Fövqəladə xərclər', '', 3, 0),
(191, '8', 'Mənfəətlər (zərərlər)', '', 3, 0),
(192, '80', 'Ümumi mənfəət (zərər)', '', 3, 0),
(193, '801', 'Ümumi mənfəət (zərər)', '', 3, 0),
(194, '81', 'Asılı və birgə müəssisələrin mənfəətlərində(zərərlərində) pay', '', 3, 0),
(195, '811', 'Asılı və birgə müəssisələrin mənfəətlərində(zərərlərində) pay', '', 3, 0),
(196, '9', 'Mənfəət vergisi', '', 3, 0),
(197, '90', 'Mənfəət vergisi', '', 3, 0),
(198, '901', 'Cari mənfəət vergisi üzrə xərclər', '', 3, 0),
(199, '902', 'Təxirə salınmış mənfəət vergisi üzrə xərclər', '', 3, 0);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2016_06_01_000001_create_oauth_auth_codes_table', 1),
(4, '2016_06_01_000002_create_oauth_access_tokens_table', 1),
(5, '2016_06_01_000003_create_oauth_refresh_tokens_table', 1),
(6, '2016_06_01_000004_create_oauth_clients_table', 1),
(7, '2016_06_01_000005_create_oauth_personal_access_clients_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `oauth_access_tokens`
--

CREATE TABLE `oauth_access_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `client_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_auth_codes`
--

CREATE TABLE `oauth_auth_codes` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  `client_id` int(10) UNSIGNED NOT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_clients`
--

CREATE TABLE `oauth_clients` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `secret` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `redirect` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `personal_access_client` tinyint(1) NOT NULL,
  `password_client` tinyint(1) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `oauth_clients`
--

INSERT INTO `oauth_clients` (`id`, `user_id`, `name`, `secret`, `redirect`, `personal_access_client`, `password_client`, `revoked`, `created_at`, `updated_at`) VALUES
(1, NULL, 'Laravel Personal Access Client', 'ok1ti6u656mWSIgl4fObFsccMQOsrijiuyOJQu9Z', 'http://localhost', 1, 0, 0, '2019-04-20 14:46:52', '2019-04-20 14:46:52'),
(2, NULL, 'Laravel Password Grant Client', 'cqgJ2mRLHTCDj4wLxER5kNT549No4v3qzCaOyiMc', 'http://localhost', 0, 1, 0, '2019-04-20 14:46:52', '2019-04-20 14:46:52');

-- --------------------------------------------------------

--
-- Table structure for table `oauth_personal_access_clients`
--

CREATE TABLE `oauth_personal_access_clients` (
  `id` int(10) UNSIGNED NOT NULL,
  `client_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `oauth_personal_access_clients`
--

INSERT INTO `oauth_personal_access_clients` (`id`, `client_id`, `created_at`, `updated_at`) VALUES
(1, 1, '2019-04-20 14:46:52', '2019-04-20 14:46:52');

-- --------------------------------------------------------

--
-- Table structure for table `oauth_refresh_tokens`
--

CREATE TABLE `oauth_refresh_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `access_token_id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transactions_type`
--

CREATE TABLE `transactions_type` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `transactions_type`
--

INSERT INTO `transactions_type` (`id`, `name`) VALUES
(1, 'Открытие счета'),
(2, 'Закрытие счета');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `password` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `email_verified_at`, `remember_token`, `created_at`, `updated_at`, `status`, `password`) VALUES
(1, 'mama@mail.com', NULL, NULL, '2019-05-11 08:56:33', '2019-05-11 08:56:33', 1, '$2y$10$XmgMSALSdTNhPlzrLS55j.HoDWOq1lijfoza5doUOU.JE1.LT77lS'),
(2, 'papa@mail.com', NULL, NULL, '2019-05-13 13:09:01', '2019-05-13 13:09:01', 1, '$2y$10$exdY9dh.UEeDNDe6/JvaguNgLJn1P6MbVK.mDYt9y/rGQzgqgcxMi');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account_transactions`
--
ALTER TABLE `account_transactions`
  ADD PRIMARY KEY (`AT_id`);

--
-- Indexes for table `enterprise`
--
ALTER TABLE `enterprise`
  ADD UNIQUE KEY `inn_UNIQUE` (`inn`),
  ADD UNIQUE KEY `fincode_UNIQUE` (`fincode`),
  ADD KEY `fk_user_id` (`user_id`);

--
-- Indexes for table `enterprise_invoces`
--
ALTER TABLE `enterprise_invoces`
  ADD PRIMARY KEY (`AC_id`),
  ADD UNIQUE KEY `AC_code` (`AC_code`),
  ADD UNIQUE KEY `AC_name` (`AC_name`);

--
-- Indexes for table `fiche_types`
--
ALTER TABLE `fiche_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`AC_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `oauth_access_tokens`
--
ALTER TABLE `oauth_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_access_tokens_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_auth_codes`
--
ALTER TABLE `oauth_auth_codes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `oauth_clients`
--
ALTER TABLE `oauth_clients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_clients_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_personal_access_clients`
--
ALTER TABLE `oauth_personal_access_clients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_personal_access_clients_client_id_index` (`client_id`);

--
-- Indexes for table `oauth_refresh_tokens`
--
ALTER TABLE `oauth_refresh_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_refresh_tokens_access_token_id_index` (`access_token_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `transactions_type`
--
ALTER TABLE `transactions_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `account_transactions`
--
ALTER TABLE `account_transactions`
  MODIFY `AT_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `enterprise_invoces`
--
ALTER TABLE `enterprise_invoces`
  MODIFY `AC_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=152;

--
-- AUTO_INCREMENT for table `fiche_types`
--
ALTER TABLE `fiche_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `AC_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=200;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `oauth_clients`
--
ALTER TABLE `oauth_clients`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `oauth_personal_access_clients`
--
ALTER TABLE `oauth_personal_access_clients`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `transactions_type`
--
ALTER TABLE `transactions_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `enterprise`
--
ALTER TABLE `enterprise`
  ADD CONSTRAINT `fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
