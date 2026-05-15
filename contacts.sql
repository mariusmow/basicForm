-- -------------------------------------------------------------
-- TablePlus 6.9.0(668)
--
-- https://tableplus.com/
--
-- Database: weconnectu
-- Generation Time: 2026-05-15 17:08:40.5800
-- -------------------------------------------------------------


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


DROP TABLE IF EXISTS `contacts`;
CREATE TABLE `contacts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(12) DEFAULT NULL,
  `message` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

INSERT INTO `contacts` (`id`, `name`, `email`, `phone`, `message`, `created_at`) VALUES
(1, 'Marius Odendaal', 'mariusmow@gmail.com', '0828289662', 'Message One', '2026-05-12 10:03:52'),
(2, 'John Venter', 'johan@test.com', '0828289663', 'Message Two', '2026-05-12 10:03:52'),
(8, 'Sarie Botha', 'sarie@test.com', '0828289664', 'Message Three', '2026-05-12 15:57:01'),
(9, 'Chris Wessel', 'chris@test.com', '0828289665', 'Message Four', '2026-05-12 16:01:24'),
(10, 'Chane Coetzee', 'chane@test.com', '0828289666', 'Message Five', '2026-05-12 16:05:36'),
(11, 'Ben Twine', 'ben@test.com', '0828289667', 'Message Six', '2026-05-12 16:06:11'),
(12, 'Chantel LeRoux', 'chantel@test.com', '0828289668', 'Message Seven', '2026-05-12 16:07:05'),
(13, 'Fran Hoek', 'fran@test.com', '0828289669', 'Message Eight', '2026-05-12 16:07:32'),
(14, 'Mary Pets', 'mary@test.com', '0828289610', 'Message Nine', '2026-05-12 16:07:42'),
(15, 'Donald Duck', 'donald@test.com', '0828289611', 'Message Ten', '2026-05-12 16:07:56'),
(16, 'Joan Goosen', 'Joan@test.com', '0828289612', 'Message Eleven', '2026-05-12 16:08:11');


/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;