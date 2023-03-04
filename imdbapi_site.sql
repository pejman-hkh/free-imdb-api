-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Mar 04, 2023 at 01:36 PM
-- Server version: 10.4.20-MariaDB
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `imdbapi_site`
--

-- --------------------------------------------------------

--
-- Table structure for table `actors`
--

CREATE TABLE `actors` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_persian_ci NOT NULL,
  `const` varchar(15) COLLATE utf8_persian_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `akas`
--

CREATE TABLE `akas` (
  `id` int(11) NOT NULL,
  `titleId` varchar(255) COLLATE utf8_persian_ci NOT NULL,
  `ordering` varchar(255) COLLATE utf8_persian_ci NOT NULL,
  `title` varchar(255) COLLATE utf8_persian_ci NOT NULL,
  `region` varchar(255) COLLATE utf8_persian_ci NOT NULL,
  `language` varchar(255) COLLATE utf8_persian_ci NOT NULL,
  `types` varchar(255) COLLATE utf8_persian_ci NOT NULL,
  `attributes` varchar(255) COLLATE utf8_persian_ci NOT NULL,
  `isOriginalTitle` varchar(255) COLLATE utf8_persian_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `basics`
--

CREATE TABLE `basics` (
  `id` int(11) NOT NULL,
  `tconst` varchar(15) COLLATE utf8_persian_ci NOT NULL,
  `titleType` varchar(30) COLLATE utf8_persian_ci NOT NULL,
  `primaryTitle` varchar(255) COLLATE utf8_persian_ci NOT NULL,
  `originalTitle` varchar(255) COLLATE utf8_persian_ci NOT NULL,
  `isAdult` tinyint(1) NOT NULL,
  `startYear` int(4) NOT NULL,
  `endYear` int(4) NOT NULL,
  `runtimeMinutes` smallint(4) NOT NULL,
  `genres` varchar(255) COLLATE utf8_persian_ci NOT NULL,
  `averageRating` tinyint(1) NOT NULL,
  `numVotes` bigint(20) NOT NULL,
  `rateOrder` bigint(20) NOT NULL,
  `movieid` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `countcache`
--

CREATE TABLE `countcache` (
  `id` int(11) NOT NULL,
  `nsql` text COLLATE utf8_persian_ci NOT NULL,
  `bind` varchar(2000) COLLATE utf8_persian_ci NOT NULL,
  `md5` varchar(50) COLLATE utf8_persian_ci NOT NULL,
  `ncount` int(11) NOT NULL,
  `date` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE `countries` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_persian_ci NOT NULL,
  `short` varchar(5) COLLATE utf8_persian_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `crew`
--

CREATE TABLE `crew` (
  `id` int(11) NOT NULL,
  `tconst` varchar(255) COLLATE utf8_persian_ci NOT NULL,
  `directors` varchar(255) COLLATE utf8_persian_ci NOT NULL,
  `writers` varchar(255) COLLATE utf8_persian_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `directors`
--

CREATE TABLE `directors` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_persian_ci NOT NULL,
  `const` varchar(255) COLLATE utf8_persian_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `episodes`
--

CREATE TABLE `episodes` (
  `id` int(11) NOT NULL,
  `tconst` varchar(255) COLLATE utf8_persian_ci NOT NULL,
  `parentTconst` varchar(255) COLLATE utf8_persian_ci NOT NULL,
  `seasonNumber` varchar(255) COLLATE utf8_persian_ci NOT NULL,
  `episodeNumber` varchar(255) COLLATE utf8_persian_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `genres`
--

CREATE TABLE `genres` (
  `id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8_persian_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `languages`
--

CREATE TABLE `languages` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_persian_ci NOT NULL,
  `short` varchar(10) COLLATE utf8_persian_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `movies`
--

CREATE TABLE `movies` (
  `id` int(11) NOT NULL,
  `twriters` text COLLATE utf8_persian_ci NOT NULL,
  `tdirectors` text COLLATE utf8_persian_ci NOT NULL,
  `tgenres` varchar(255) COLLATE utf8_persian_ci NOT NULL,
  `tactors` text COLLATE utf8_persian_ci NOT NULL,
  `tcountries` text COLLATE utf8_persian_ci NOT NULL,
  `tlanguages` text COLLATE utf8_persian_ci NOT NULL,
  `storyLine` text COLLATE utf8_persian_ci NOT NULL,
  `summery` text COLLATE utf8_persian_ci NOT NULL,
  `src` varchar(255) COLLATE utf8_persian_ci NOT NULL,
  `srcset` varchar(1000) COLLATE utf8_persian_ci NOT NULL,
  `code` varchar(30) COLLATE utf8_persian_ci NOT NULL,
  `datan1` longtext COLLATE utf8_persian_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `names`
--

CREATE TABLE `names` (
  `id` int(11) NOT NULL,
  `nconst` varchar(255) COLLATE utf8_persian_ci NOT NULL,
  `primaryName` varchar(255) COLLATE utf8_persian_ci NOT NULL,
  `birthYear` varchar(255) COLLATE utf8_persian_ci NOT NULL,
  `deathYear` varchar(255) COLLATE utf8_persian_ci NOT NULL,
  `primaryProfession` varchar(255) COLLATE utf8_persian_ci NOT NULL,
  `knownForTitles` varchar(2000) COLLATE utf8_persian_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `principals`
--

CREATE TABLE `principals` (
  `id` int(11) NOT NULL,
  `tconst` varchar(255) COLLATE utf8_persian_ci NOT NULL,
  `ordering` varchar(255) COLLATE utf8_persian_ci NOT NULL,
  `nconst` varchar(255) COLLATE utf8_persian_ci NOT NULL,
  `category` varchar(255) COLLATE utf8_persian_ci NOT NULL,
  `job` varchar(255) COLLATE utf8_persian_ci NOT NULL,
  `characters` varchar(255) COLLATE utf8_persian_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ratings`
--

CREATE TABLE `ratings` (
  `id` int(11) NOT NULL,
  `tconst` varchar(15) COLLATE utf8_persian_ci NOT NULL,
  `averageRating` tinyint(2) NOT NULL,
  `numVotes` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `writers`
--

CREATE TABLE `writers` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_persian_ci NOT NULL,
  `const` varchar(255) COLLATE utf8_persian_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `actors`
--
ALTER TABLE `actors`
  ADD PRIMARY KEY (`id`),
  ADD KEY `name` (`name`);

--
-- Indexes for table `akas`
--
ALTER TABLE `akas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `titleId` (`titleId`),
  ADD KEY `region` (`region`);

--
-- Indexes for table `basics`
--
ALTER TABLE `basics`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tconst` (`tconst`),
  ADD KEY `averageRating` (`averageRating`),
  ADD KEY `numVotes` (`numVotes`),
  ADD KEY `rateOrder` (`rateOrder`);

--
-- Indexes for table `countcache`
--
ALTER TABLE `countcache`
  ADD PRIMARY KEY (`id`),
  ADD KEY `md5` (`md5`);

--
-- Indexes for table `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `name` (`name`);

--
-- Indexes for table `crew`
--
ALTER TABLE `crew`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tconst` (`tconst`);

--
-- Indexes for table `directors`
--
ALTER TABLE `directors`
  ADD PRIMARY KEY (`id`),
  ADD KEY `name` (`name`);

--
-- Indexes for table `episodes`
--
ALTER TABLE `episodes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tconst` (`tconst`),
  ADD KEY `parentTconst` (`parentTconst`);

--
-- Indexes for table `genres`
--
ALTER TABLE `genres`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `languages`
--
ALTER TABLE `languages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `name` (`name`);

--
-- Indexes for table `movies`
--
ALTER TABLE `movies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `names`
--
ALTER TABLE `names`
  ADD PRIMARY KEY (`id`),
  ADD KEY `nconst` (`nconst`);

--
-- Indexes for table `principals`
--
ALTER TABLE `principals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tconst` (`tconst`);

--
-- Indexes for table `ratings`
--
ALTER TABLE `ratings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tconst` (`tconst`),
  ADD KEY `averageRating` (`averageRating`),
  ADD KEY `numVotes` (`numVotes`),
  ADD KEY `averageRating_2` (`averageRating`,`numVotes`);

--
-- Indexes for table `writers`
--
ALTER TABLE `writers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `name` (`name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `actors`
--
ALTER TABLE `actors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `akas`
--
ALTER TABLE `akas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `basics`
--
ALTER TABLE `basics`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `countcache`
--
ALTER TABLE `countcache`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `countries`
--
ALTER TABLE `countries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `crew`
--
ALTER TABLE `crew`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `directors`
--
ALTER TABLE `directors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `episodes`
--
ALTER TABLE `episodes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `genres`
--
ALTER TABLE `genres`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `languages`
--
ALTER TABLE `languages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `movies`
--
ALTER TABLE `movies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `names`
--
ALTER TABLE `names`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `principals`
--
ALTER TABLE `principals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ratings`
--
ALTER TABLE `ratings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `writers`
--
ALTER TABLE `writers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
