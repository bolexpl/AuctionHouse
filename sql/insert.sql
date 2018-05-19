use auctions;

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT = @@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS = @@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION = @@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;


--
-- Zrzut danych tabeli `users`
--

INSERT INTO `users` (`login`, `password`, `is_admin`, `email`, `telephone`) VALUES
  ('admin', '$2y$10$JMcCMss7RIX7br.62H1KQeSVsbkbZgKyAJ4zzphHU2e/cifthPFtu', 1, 'admin@ad.min', 'admin'),
  ('test', '$2y$10$iduJz76LVMKypPO75tfSeuKrLBtqwN7f/bdtqwfLVgyJOmRGNgzbS', 0, 'test@te.st', '984094123141'),
  ('nowy', '$2y$10$yaQ0pg0BbeBj9O2tXRhDpuIPkVt8pejsKm0RsMwkpG/2t.rqotvqO', 0, 'no@w.y', '98765432'),
  ('nikt', '$2y$10$Ukvt/82NlUQy01b1C0lOG.LA/c7yZ768qSILYZEQ7kTBgL9C0e/8K', 0, 'ni@k.t', '123456789'),
  ('sprzedawca', '$2y$10$6mQsSu2ZL7TdlRrpow5VvuOOWsICiMsl1e55Ea1ECAqO0OdU7XoM6', 0, 'sprze@daw.ca', '78965321'),
  ('kupiec', '$2y$10$SdyFl35Y4.BTqk/JrNnQo.hzYHc.WEfxnBY4hwgxG4uxsx5a6Udge', 0, 'kupiec@ku.piec', '654987321');

--
-- Zrzut danych tabeli `categories`
--

INSERT INTO `categories` (`nazwa`) VALUES
  ('Meble'),
  ('Sprzęty');

--
-- Zrzut danych tabeli `subcategories`
--

INSERT INTO `subcategories` (`parent_category_id`, `nazwa`) VALUES
  (1, 'Krzesła'),
  (1, 'Stoły'),
  (1, 'Szafy');

--
-- Zrzut danych tabeli `auctions`
--

INSERT INTO `auctions` (`user_id`, `description`, `title`, `subcategory_id`, `date`, `completed`) VALUES
  (5, 'taki oto zestaw', 'zestaw', 1, '2018-05-18 19:42:47', 0),
  (5, 'opis', 'nowa aukcja', 1, '2018-05-18 19:43:32', 0),
  (5, 'jest zakończona', 'zakończona', 1, '2018-05-18 19:44:02', 1),
  (5, 'st&oacute;ł', 'st&oacute;ł', 2, '2018-05-18 19:48:58', 0),
  (5, 'szafy', 'szafy', 3, '2018-05-18 19:49:13', 0),
  (5, 'dużo', 'dużo ofert', 1, '2018-05-18 19:53:04', 1),
  (2, 'test', 'test', 1, '2018-05-18 19:59:59', 0);

--
-- Zrzut danych tabeli `comments`
--

INSERT INTO `comments` (`seller_id`, `user_id`, `content`, `date`) VALUES
  (2, 5, 'Nie wiem co tu pisać.', '2018-05-18 20:21:44'),
  (2, 5, 'drugi komentarz', '2018-05-18 20:23:37'),
  (5, 6, 'żadnych opinii?', '2018-05-18 20:24:10'),
  (1, 6, 'Jeden komentarz', '2018-05-18 20:30:05');

--
-- Zrzut danych tabeli `offers`
--

INSERT INTO `offers` (`auction_id`, `price`, `date`, `customer_id`) VALUES
  (1, '200.00', '2018-05-18 19:53:51', 2),
  (2, '300.00', '2018-05-18 19:53:58', 2),
  (3, '250.40', '2018-05-18 19:54:11', 2),
  (6, '100.00', '2018-05-18 19:54:38', 2),
  (6, '100.01', '2018-05-18 19:55:35', 4),
  (2, '400.00', '2018-05-18 19:55:44', 4),
  (1, '250.00', '2018-05-18 19:56:00', 4),
  (6, '100.50', '2018-05-18 19:58:05', 2),
  (6, '500.00', '2018-05-18 19:58:33', 6),
  (2, '500.00', '2018-05-18 19:58:42', 6);

--
-- Zrzut danych tabeli `products`
--

INSERT INTO `products` (`auction_id`, `name`) VALUES
  (1, 'krzesło1'),
  (1, 'krzesło'),
  (1, 'krzesło3'),
  (2, 'jeden'),
  (2, 'dwa'),
  (3, 'całe nic'),
  (4, 'st&oacute;ł'),
  (5, 'szafa duża'),
  (5, 'szafa mała'),
  (6, 'nic');


--
-- Zrzut danych tabeli `transactions`
--

INSERT INTO `transactions` (`offer_id`, `date`) VALUES
  (3, '2018-05-18 20:03:31'),
  (9, '2018-05-18 20:03:26');

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT = @OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS = @OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION = @OLD_COLLATION_CONNECTION */;


