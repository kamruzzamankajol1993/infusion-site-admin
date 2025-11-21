-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 21, 2025 at 06:26 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ifushionadmin`
--

-- --------------------------------------------------------

--
-- Table structure for table `about_us`
--

CREATE TABLE `about_us` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `our_story` text DEFAULT NULL,
  `team_image` varchar(255) DEFAULT NULL,
  `mission` text DEFAULT NULL,
  `vision` text DEFAULT NULL,
  `mission_vision_image` varchar(255) DEFAULT NULL,
  `founder_quote` text DEFAULT NULL,
  `founder_image` varchar(255) DEFAULT NULL,
  `founder_name` varchar(255) DEFAULT NULL,
  `founder_designation` varchar(255) DEFAULT NULL,
  `trade_license` varchar(255) DEFAULT NULL,
  `bin` varchar(255) DEFAULT NULL,
  `tin` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `about_us`
--

INSERT INTO `about_us` (`id`, `our_story`, `team_image`, `mission`, `vision`, `mission_vision_image`, `founder_quote`, `founder_image`, `founder_name`, `founder_designation`, `trade_license`, `bin`, `tin`, `created_at`, `updated_at`) VALUES
(1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-22 00:25:13', '2025-11-02 03:42:23');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('laravel_cache_spatie.permission.cache', 'a:3:{s:5:\"alias\";a:5:{s:1:\"a\";s:2:\"id\";s:1:\"b\";s:10:\"group_name\";s:1:\"c\";s:4:\"name\";s:1:\"d\";s:10:\"guard_name\";s:1:\"r\";s:5:\"roles\";}s:11:\"permissions\";a:215:{i:0;a:5:{s:1:\"a\";i:1;s:1:\"b\";s:4:\"role\";s:1:\"c\";s:8:\"roleView\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:1;a:5:{s:1:\"a\";i:2;s:1:\"b\";s:4:\"role\";s:1:\"c\";s:7:\"roleAdd\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:2;a:5:{s:1:\"a\";i:3;s:1:\"b\";s:4:\"role\";s:1:\"c\";s:8:\"roleEdit\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:3;a:5:{s:1:\"a\";i:4;s:1:\"b\";s:4:\"role\";s:1:\"c\";s:10:\"roleDelete\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:4;a:5:{s:1:\"a\";i:5;s:1:\"b\";s:4:\"user\";s:1:\"c\";s:7:\"userAdd\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:5;a:5:{s:1:\"a\";i:6;s:1:\"b\";s:4:\"user\";s:1:\"c\";s:8:\"userView\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:6;a:5:{s:1:\"a\";i:7;s:1:\"b\";s:4:\"user\";s:1:\"c\";s:10:\"userDelete\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:7;a:5:{s:1:\"a\";i:8;s:1:\"b\";s:4:\"user\";s:1:\"c\";s:10:\"userUpdate\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:8;a:5:{s:1:\"a\";i:9;s:1:\"b\";s:10:\"permission\";s:1:\"c\";s:13:\"permissionAdd\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:9;a:5:{s:1:\"a\";i:10;s:1:\"b\";s:10:\"permission\";s:1:\"c\";s:14:\"permissionView\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:10;a:5:{s:1:\"a\";i:11;s:1:\"b\";s:10:\"permission\";s:1:\"c\";s:16:\"permissionDelete\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:11;a:5:{s:1:\"a\";i:12;s:1:\"b\";s:10:\"permission\";s:1:\"c\";s:16:\"permissionUpdate\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:12;a:5:{s:1:\"a\";i:13;s:1:\"b\";s:7:\"profile\";s:1:\"c\";s:11:\"profileView\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:13;a:5:{s:1:\"a\";i:14;s:1:\"b\";s:7:\"profile\";s:1:\"c\";s:14:\"profileSetting\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:14;a:5:{s:1:\"a\";i:15;s:1:\"b\";s:9:\"dashboard\";s:1:\"c\";s:13:\"dashboardView\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:15;a:5:{s:1:\"a\";i:20;s:1:\"b\";s:11:\"designation\";s:1:\"c\";s:14:\"designationAdd\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:16;a:5:{s:1:\"a\";i:21;s:1:\"b\";s:11:\"designation\";s:1:\"c\";s:15:\"designationView\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:17;a:5:{s:1:\"a\";i:22;s:1:\"b\";s:11:\"designation\";s:1:\"c\";s:17:\"designationDelete\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:18;a:5:{s:1:\"a\";i:23;s:1:\"b\";s:11:\"designation\";s:1:\"c\";s:17:\"designationUpdate\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:19;a:5:{s:1:\"a\";i:24;s:1:\"b\";s:12:\"panelSetting\";s:1:\"c\";s:15:\"panelSettingAdd\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:20;a:5:{s:1:\"a\";i:25;s:1:\"b\";s:12:\"panelSetting\";s:1:\"c\";s:16:\"panelSettingView\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:21;a:5:{s:1:\"a\";i:26;s:1:\"b\";s:12:\"panelSetting\";s:1:\"c\";s:18:\"panelSettingDelete\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:22;a:5:{s:1:\"a\";i:27;s:1:\"b\";s:12:\"panelSetting\";s:1:\"c\";s:18:\"panelSettingUpdate\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:23;a:5:{s:1:\"a\";i:30;s:1:\"b\";s:8:\"category\";s:1:\"c\";s:11:\"categoryAdd\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:24;a:5:{s:1:\"a\";i:31;s:1:\"b\";s:8:\"category\";s:1:\"c\";s:12:\"categoryView\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:25;a:5:{s:1:\"a\";i:32;s:1:\"b\";s:8:\"category\";s:1:\"c\";s:14:\"categoryDelete\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:26;a:5:{s:1:\"a\";i:33;s:1:\"b\";s:8:\"category\";s:1:\"c\";s:14:\"categoryUpdate\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:27;a:5:{s:1:\"a\";i:106;s:1:\"b\";s:9:\"extraPage\";s:1:\"c\";s:12:\"extraPageAdd\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:28;a:5:{s:1:\"a\";i:107;s:1:\"b\";s:9:\"extraPage\";s:1:\"c\";s:13:\"extraPageView\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:29;a:5:{s:1:\"a\";i:108;s:1:\"b\";s:9:\"extraPage\";s:1:\"c\";s:15:\"extraPageDelete\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:30;a:5:{s:1:\"a\";i:109;s:1:\"b\";s:9:\"extraPage\";s:1:\"c\";s:15:\"extraPageUpdate\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:31;a:5:{s:1:\"a\";i:118;s:1:\"b\";s:10:\"socialLink\";s:1:\"c\";s:13:\"socialLinkAdd\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:32;a:5:{s:1:\"a\";i:119;s:1:\"b\";s:10:\"socialLink\";s:1:\"c\";s:14:\"socialLinkView\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:33;a:5:{s:1:\"a\";i:120;s:1:\"b\";s:10:\"socialLink\";s:1:\"c\";s:16:\"socialLinkDelete\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:34;a:5:{s:1:\"a\";i:121;s:1:\"b\";s:10:\"socialLink\";s:1:\"c\";s:16:\"socialLinkUpdate\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:35;a:5:{s:1:\"a\";i:122;s:1:\"b\";s:8:\"customer\";s:1:\"c\";s:11:\"customerAdd\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:36;a:5:{s:1:\"a\";i:123;s:1:\"b\";s:8:\"customer\";s:1:\"c\";s:12:\"customerView\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:37;a:5:{s:1:\"a\";i:124;s:1:\"b\";s:8:\"customer\";s:1:\"c\";s:14:\"customerDelete\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:38;a:5:{s:1:\"a\";i:125;s:1:\"b\";s:8:\"customer\";s:1:\"c\";s:14:\"customerUpdate\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:39;a:5:{s:1:\"a\";i:208;s:1:\"b\";s:10:\"department\";s:1:\"c\";s:13:\"departmentAdd\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:40;a:5:{s:1:\"a\";i:209;s:1:\"b\";s:10:\"department\";s:1:\"c\";s:14:\"departmentView\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:41;a:5:{s:1:\"a\";i:210;s:1:\"b\";s:10:\"department\";s:1:\"c\";s:16:\"departmentDelete\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:42;a:5:{s:1:\"a\";i:211;s:1:\"b\";s:10:\"department\";s:1:\"c\";s:16:\"departmentUpdate\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:43;a:5:{s:1:\"a\";i:230;s:1:\"b\";s:11:\"aboutUsView\";s:1:\"c\";s:11:\"aboutUsView\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:44;a:5:{s:1:\"a\";i:237;s:1:\"b\";s:7:\"country\";s:1:\"c\";s:10:\"countryAdd\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:45;a:5:{s:1:\"a\";i:238;s:1:\"b\";s:7:\"country\";s:1:\"c\";s:11:\"countryView\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:46;a:5:{s:1:\"a\";i:239;s:1:\"b\";s:7:\"country\";s:1:\"c\";s:13:\"countryDelete\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:47;a:5:{s:1:\"a\";i:240;s:1:\"b\";s:7:\"country\";s:1:\"c\";s:13:\"countryUpdate\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:48;a:5:{s:1:\"a\";i:241;s:1:\"b\";s:6:\"client\";s:1:\"c\";s:9:\"clientAdd\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:49;a:5:{s:1:\"a\";i:242;s:1:\"b\";s:6:\"client\";s:1:\"c\";s:10:\"clientView\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:50;a:5:{s:1:\"a\";i:243;s:1:\"b\";s:6:\"client\";s:1:\"c\";s:12:\"clientDelete\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:51;a:5:{s:1:\"a\";i:244;s:1:\"b\";s:6:\"client\";s:1:\"c\";s:12:\"clientUpdate\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:52;a:5:{s:1:\"a\";i:277;s:1:\"b\";s:6:\"slider\";s:1:\"c\";s:9:\"sliderAdd\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:53;a:5:{s:1:\"a\";i:278;s:1:\"b\";s:6:\"slider\";s:1:\"c\";s:10:\"sliderView\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:54;a:5:{s:1:\"a\";i:279;s:1:\"b\";s:6:\"slider\";s:1:\"c\";s:12:\"sliderDelete\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:55;a:5:{s:1:\"a\";i:280;s:1:\"b\";s:6:\"slider\";s:1:\"c\";s:12:\"sliderUpdate\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:56;a:5:{s:1:\"a\";i:281;s:1:\"b\";s:12:\"iifcStrength\";s:1:\"c\";s:15:\"iifcStrengthAdd\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:57;a:5:{s:1:\"a\";i:282;s:1:\"b\";s:12:\"iifcStrength\";s:1:\"c\";s:16:\"iifcStrengthView\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:58;a:5:{s:1:\"a\";i:283;s:1:\"b\";s:12:\"iifcStrength\";s:1:\"c\";s:18:\"iifcStrengthDelete\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:59;a:5:{s:1:\"a\";i:284;s:1:\"b\";s:12:\"iifcStrength\";s:1:\"c\";s:18:\"iifcStrengthUpdate\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:60;a:5:{s:1:\"a\";i:285;s:1:\"b\";s:9:\"contactUs\";s:1:\"c\";s:12:\"contactUsAdd\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:61;a:5:{s:1:\"a\";i:286;s:1:\"b\";s:9:\"contactUs\";s:1:\"c\";s:13:\"contactUsView\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:62;a:5:{s:1:\"a\";i:287;s:1:\"b\";s:9:\"contactUs\";s:1:\"c\";s:15:\"contactUsDelete\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:63;a:5:{s:1:\"a\";i:288;s:1:\"b\";s:9:\"contactUs\";s:1:\"c\";s:15:\"contactUsUpdate\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:64;a:5:{s:1:\"a\";i:310;s:1:\"b\";s:17:\"importantLinkView\";s:1:\"c\";s:17:\"importantLinkView\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:65;a:5:{s:1:\"a\";i:311;s:1:\"b\";s:17:\"importantLinkView\";s:1:\"c\";s:16:\"importantLinkAdd\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:66;a:5:{s:1:\"a\";i:312;s:1:\"b\";s:17:\"importantLinkView\";s:1:\"c\";s:19:\"importantLinkDelete\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:67;a:5:{s:1:\"a\";i:313;s:1:\"b\";s:17:\"importantLinkView\";s:1:\"c\";s:19:\"importantLinkUpdate\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:68;a:5:{s:1:\"a\";i:322;s:1:\"b\";s:14:\"headerLinkView\";s:1:\"c\";s:14:\"headerLinkView\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:69;a:5:{s:1:\"a\";i:323;s:1:\"b\";s:14:\"headerLinkView\";s:1:\"c\";s:16:\"headerLinkUpdate\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:70;a:5:{s:1:\"a\";i:330;s:1:\"b\";s:8:\"solution\";s:1:\"c\";s:11:\"solutionAdd\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:71;a:5:{s:1:\"a\";i:331;s:1:\"b\";s:8:\"solution\";s:1:\"c\";s:12:\"solutionView\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:72;a:5:{s:1:\"a\";i:332;s:1:\"b\";s:8:\"solution\";s:1:\"c\";s:14:\"solutionDelete\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:73;a:5:{s:1:\"a\";i:333;s:1:\"b\";s:8:\"solution\";s:1:\"c\";s:14:\"solutionUpdate\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:74;a:5:{s:1:\"a\";i:334;s:1:\"b\";s:5:\"whyUs\";s:1:\"c\";s:8:\"whyUsAdd\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:75;a:5:{s:1:\"a\";i:335;s:1:\"b\";s:5:\"whyUs\";s:1:\"c\";s:9:\"whyUsView\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:76;a:5:{s:1:\"a\";i:336;s:1:\"b\";s:5:\"whyUs\";s:1:\"c\";s:11:\"whyUsDelete\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:77;a:5:{s:1:\"a\";i:337;s:1:\"b\";s:5:\"whyUs\";s:1:\"c\";s:11:\"whyUsUpdate\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:78;a:5:{s:1:\"a\";i:342;s:1:\"b\";s:4:\"team\";s:1:\"c\";s:7:\"teamAdd\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:79;a:5:{s:1:\"a\";i:343;s:1:\"b\";s:4:\"team\";s:1:\"c\";s:8:\"teamView\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:80;a:5:{s:1:\"a\";i:344;s:1:\"b\";s:4:\"team\";s:1:\"c\";s:10:\"teamDelete\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:81;a:5:{s:1:\"a\";i:345;s:1:\"b\";s:4:\"team\";s:1:\"c\";s:10:\"teamUpdate\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:82;a:5:{s:1:\"a\";i:346;s:1:\"b\";s:11:\"whyChooseUs\";s:1:\"c\";s:17:\"whyChooseUsUpdate\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:83;a:5:{s:1:\"a\";i:347;s:1:\"b\";s:11:\"whyChooseUs\";s:1:\"c\";s:15:\"whyChooseUsView\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:84;a:5:{s:1:\"a\";i:348;s:1:\"b\";s:11:\"whyChooseUs\";s:1:\"c\";s:14:\"whyChooseUsAdd\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:85;a:5:{s:1:\"a\";i:349;s:1:\"b\";s:11:\"whyChooseUs\";s:1:\"c\";s:17:\"whyChooseUsDelete\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:86;a:5:{s:1:\"a\";i:350;s:1:\"b\";s:5:\"media\";s:1:\"c\";s:8:\"mediaAdd\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:87;a:5:{s:1:\"a\";i:351;s:1:\"b\";s:5:\"media\";s:1:\"c\";s:9:\"mediaView\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:88;a:5:{s:1:\"a\";i:352;s:1:\"b\";s:5:\"media\";s:1:\"c\";s:11:\"mediaDelete\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:89;a:5:{s:1:\"a\";i:353;s:1:\"b\";s:5:\"media\";s:1:\"c\";s:11:\"mediaUpdate\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:90;a:5:{s:1:\"a\";i:354;s:1:\"b\";s:20:\"digitalMarketingPage\";s:1:\"c\";s:24:\"digitalMarketingPageView\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:91;a:5:{s:1:\"a\";i:355;s:1:\"b\";s:20:\"digitalMarketingPage\";s:1:\"c\";s:23:\"digitalMarketingPageAdd\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:92;a:5:{s:1:\"a\";i:356;s:1:\"b\";s:20:\"digitalMarketingPage\";s:1:\"c\";s:26:\"digitalMarketingPageDelete\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:93;a:5:{s:1:\"a\";i:357;s:1:\"b\";s:20:\"digitalMarketingPage\";s:1:\"c\";s:26:\"digitalMarketingPageUpdate\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:94;a:5:{s:1:\"a\";i:358;s:1:\"b\";s:22:\"digitalMarketingGrowth\";s:1:\"c\";s:26:\"digitalMarketingGrowthView\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:95;a:5:{s:1:\"a\";i:359;s:1:\"b\";s:22:\"digitalMarketingGrowth\";s:1:\"c\";s:25:\"digitalMarketingGrowthAdd\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:96;a:5:{s:1:\"a\";i:360;s:1:\"b\";s:22:\"digitalMarketingGrowth\";s:1:\"c\";s:28:\"digitalMarketingGrowthUpdate\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:97;a:5:{s:1:\"a\";i:361;s:1:\"b\";s:22:\"digitalMarketingGrowth\";s:1:\"c\";s:28:\"digitalMarketingGrowthDelete\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:98;a:5:{s:1:\"a\";i:362;s:1:\"b\";s:24:\"digitalMarketingSolution\";s:1:\"c\";s:28:\"digitalMarketingSolutionView\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:99;a:5:{s:1:\"a\";i:363;s:1:\"b\";s:24:\"digitalMarketingSolution\";s:1:\"c\";s:27:\"digitalMarketingSolutionAdd\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:100;a:5:{s:1:\"a\";i:364;s:1:\"b\";s:24:\"digitalMarketingSolution\";s:1:\"c\";s:30:\"digitalMarketingSolutionDelete\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:101;a:5:{s:1:\"a\";i:365;s:1:\"b\";s:24:\"digitalMarketingSolution\";s:1:\"c\";s:30:\"digitalMarketingSolutionUpdate\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:102;a:5:{s:1:\"a\";i:366;s:1:\"b\";s:17:\"graphicDesignPage\";s:1:\"c\";s:20:\"graphicDesignPageAdd\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:103;a:5:{s:1:\"a\";i:367;s:1:\"b\";s:17:\"graphicDesignPage\";s:1:\"c\";s:21:\"graphicDesignPageView\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:104;a:5:{s:1:\"a\";i:368;s:1:\"b\";s:17:\"graphicDesignPage\";s:1:\"c\";s:23:\"graphicDesignPageDelete\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:105;a:5:{s:1:\"a\";i:369;s:1:\"b\";s:17:\"graphicDesignPage\";s:1:\"c\";s:23:\"graphicDesignPageUpdate\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:106;a:5:{s:1:\"a\";i:370;s:1:\"b\";s:22:\"graphicDesignChecklist\";s:1:\"c\";s:26:\"graphicDesignChecklistView\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:107;a:5:{s:1:\"a\";i:371;s:1:\"b\";s:22:\"graphicDesignChecklist\";s:1:\"c\";s:25:\"graphicDesignChecklistAdd\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:108;a:5:{s:1:\"a\";i:372;s:1:\"b\";s:22:\"graphicDesignChecklist\";s:1:\"c\";s:28:\"graphicDesignChecklistDelete\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:109;a:5:{s:1:\"a\";i:373;s:1:\"b\";s:22:\"graphicDesignChecklist\";s:1:\"c\";s:28:\"graphicDesignChecklistUpdate\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:110;a:5:{s:1:\"a\";i:374;s:1:\"b\";s:21:\"graphicDesignSolution\";s:1:\"c\";s:25:\"graphicDesignSolutionView\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:111;a:5:{s:1:\"a\";i:375;s:1:\"b\";s:21:\"graphicDesignSolution\";s:1:\"c\";s:24:\"graphicDesignSolutionAdd\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:112;a:5:{s:1:\"a\";i:376;s:1:\"b\";s:21:\"graphicDesignSolution\";s:1:\"c\";s:27:\"graphicDesignSolutionDelete\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:113;a:5:{s:1:\"a\";i:377;s:1:\"b\";s:21:\"graphicDesignSolution\";s:1:\"c\";s:27:\"graphicDesignSolutionUpdate\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:114;a:5:{s:1:\"a\";i:385;s:1:\"b\";s:19:\"webSolutionCareItem\";s:1:\"c\";s:23:\"webSolutionCareItemView\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:115;a:5:{s:1:\"a\";i:386;s:1:\"b\";s:19:\"webSolutionCareItem\";s:1:\"c\";s:22:\"webSolutionCareItemAdd\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:116;a:5:{s:1:\"a\";i:387;s:1:\"b\";s:19:\"webSolutionCareItem\";s:1:\"c\";s:25:\"webSolutionCareItemDelete\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:117;a:5:{s:1:\"a\";i:388;s:1:\"b\";s:19:\"webSolutionCareItem\";s:1:\"c\";s:25:\"webSolutionCareItemUpdate\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:118;a:5:{s:1:\"a\";i:389;s:1:\"b\";s:20:\"webSolutionChecklist\";s:1:\"c\";s:24:\"webSolutionChecklistView\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:119;a:5:{s:1:\"a\";i:390;s:1:\"b\";s:20:\"webSolutionChecklist\";s:1:\"c\";s:23:\"webSolutionChecklistAdd\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:120;a:5:{s:1:\"a\";i:391;s:1:\"b\";s:20:\"webSolutionChecklist\";s:1:\"c\";s:26:\"webSolutionChecklistDelete\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:121;a:5:{s:1:\"a\";i:392;s:1:\"b\";s:20:\"webSolutionChecklist\";s:1:\"c\";s:26:\"webSolutionChecklistUpdate\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:122;a:5:{s:1:\"a\";i:393;s:1:\"b\";s:18:\"webSolutionInclude\";s:1:\"c\";s:22:\"webSolutionIncludeView\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:123;a:5:{s:1:\"a\";i:394;s:1:\"b\";s:18:\"webSolutionInclude\";s:1:\"c\";s:21:\"webSolutionIncludeAdd\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:124;a:5:{s:1:\"a\";i:395;s:1:\"b\";s:18:\"webSolutionInclude\";s:1:\"c\";s:24:\"webSolutionIncludeUpdate\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:125;a:5:{s:1:\"a\";i:396;s:1:\"b\";s:18:\"webSolutionInclude\";s:1:\"c\";s:24:\"webSolutionIncludeDelete\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:126;a:5:{s:1:\"a\";i:397;s:1:\"b\";s:15:\"webSolutionPage\";s:1:\"c\";s:19:\"webSolutionPageView\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:127;a:5:{s:1:\"a\";i:398;s:1:\"b\";s:20:\"webSolutionProviding\";s:1:\"c\";s:24:\"webSolutionProvidingView\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:128;a:5:{s:1:\"a\";i:399;s:1:\"b\";s:20:\"webSolutionProviding\";s:1:\"c\";s:23:\"webSolutionProvidingAdd\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:129;a:5:{s:1:\"a\";i:400;s:1:\"b\";s:20:\"webSolutionProviding\";s:1:\"c\";s:26:\"webSolutionProvidingUpdate\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:130;a:5:{s:1:\"a\";i:401;s:1:\"b\";s:20:\"webSolutionProviding\";s:1:\"c\";s:26:\"webSolutionProvidingDelete\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:131;a:5:{s:1:\"a\";i:402;s:1:\"b\";s:23:\"webSolutionWorkCategory\";s:1:\"c\";s:27:\"webSolutionWorkCategoryView\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:132;a:5:{s:1:\"a\";i:403;s:1:\"b\";s:23:\"webSolutionWorkCategory\";s:1:\"c\";s:26:\"webSolutionWorkCategoryAdd\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:133;a:5:{s:1:\"a\";i:404;s:1:\"b\";s:23:\"webSolutionWorkCategory\";s:1:\"c\";s:29:\"webSolutionWorkCategoryDelete\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:134;a:5:{s:1:\"a\";i:405;s:1:\"b\";s:23:\"webSolutionWorkCategory\";s:1:\"c\";s:29:\"webSolutionWorkCategoryUpdate\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:135;a:5:{s:1:\"a\";i:406;s:1:\"b\";s:19:\"webSolutionWorkItem\";s:1:\"c\";s:23:\"webSolutionWorkItemView\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:136;a:5:{s:1:\"a\";i:407;s:1:\"b\";s:19:\"webSolutionWorkItem\";s:1:\"c\";s:22:\"webSolutionWorkItemAdd\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:137;a:5:{s:1:\"a\";i:408;s:1:\"b\";s:19:\"webSolutionWorkItem\";s:1:\"c\";s:25:\"webSolutionWorkItemDelete\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:138;a:5:{s:1:\"a\";i:409;s:1:\"b\";s:19:\"webSolutionWorkItem\";s:1:\"c\";s:25:\"webSolutionWorkItemUpdate\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:139;a:5:{s:1:\"a\";i:410;s:1:\"b\";s:19:\"facebookAdsCampaign\";s:1:\"c\";s:23:\"facebookAdsCampaignView\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:140;a:5:{s:1:\"a\";i:411;s:1:\"b\";s:19:\"facebookAdsCampaign\";s:1:\"c\";s:22:\"facebookAdsCampaignAdd\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:141;a:5:{s:1:\"a\";i:412;s:1:\"b\";s:19:\"facebookAdsCampaign\";s:1:\"c\";s:25:\"facebookAdsCampaignDelete\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:142;a:5:{s:1:\"a\";i:413;s:1:\"b\";s:19:\"facebookAdsCampaign\";s:1:\"c\";s:25:\"facebookAdsCampaignUpdate\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:143;a:5:{s:1:\"a\";i:414;s:1:\"b\";s:14:\"facebookAdsFaq\";s:1:\"c\";s:18:\"facebookAdsFaqView\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:144;a:5:{s:1:\"a\";i:415;s:1:\"b\";s:14:\"facebookAdsFaq\";s:1:\"c\";s:17:\"facebookAdsFaqAdd\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:145;a:5:{s:1:\"a\";i:416;s:1:\"b\";s:14:\"facebookAdsFaq\";s:1:\"c\";s:20:\"facebookAdsFaqDelete\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:146;a:5:{s:1:\"a\";i:417;s:1:\"b\";s:14:\"facebookAdsFaq\";s:1:\"c\";s:20:\"facebookAdsFaqUpdate\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:147;a:5:{s:1:\"a\";i:418;s:1:\"b\";s:18:\"facebookAdsFeature\";s:1:\"c\";s:22:\"facebookAdsFeatureView\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:148;a:5:{s:1:\"a\";i:419;s:1:\"b\";s:18:\"facebookAdsFeature\";s:1:\"c\";s:21:\"facebookAdsFeatureAdd\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:149;a:5:{s:1:\"a\";i:420;s:1:\"b\";s:18:\"facebookAdsFeature\";s:1:\"c\";s:24:\"facebookAdsFeatureDelete\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:150;a:5:{s:1:\"a\";i:421;s:1:\"b\";s:18:\"facebookAdsFeature\";s:1:\"c\";s:24:\"facebookAdsFeatureUpdate\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:151;a:5:{s:1:\"a\";i:422;s:1:\"b\";s:15:\"facebookAdsPage\";s:1:\"c\";s:19:\"facebookAdsPageView\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:152;a:5:{s:1:\"a\";i:423;s:1:\"b\";s:26:\"facebookAdsPricingCategory\";s:1:\"c\";s:30:\"facebookAdsPricingCategoryView\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:153;a:5:{s:1:\"a\";i:424;s:1:\"b\";s:26:\"facebookAdsPricingCategory\";s:1:\"c\";s:32:\"facebookAdsPricingCategoryDelete\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:154;a:5:{s:1:\"a\";i:425;s:1:\"b\";s:26:\"facebookAdsPricingCategory\";s:1:\"c\";s:32:\"facebookAdsPricingCategoryUpdate\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:155;a:5:{s:1:\"a\";i:426;s:1:\"b\";s:26:\"facebookAdsPricingCategory\";s:1:\"c\";s:29:\"facebookAdsPricingCategoryAdd\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:156;a:5:{s:1:\"a\";i:427;s:1:\"b\";s:25:\"facebookAdsPricingPackage\";s:1:\"c\";s:29:\"facebookAdsPricingPackageView\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:157;a:5:{s:1:\"a\";i:428;s:1:\"b\";s:25:\"facebookAdsPricingPackage\";s:1:\"c\";s:28:\"facebookAdsPricingPackageAdd\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:158;a:5:{s:1:\"a\";i:429;s:1:\"b\";s:25:\"facebookAdsPricingPackage\";s:1:\"c\";s:31:\"facebookAdsPricingPackageDelete\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:159;a:5:{s:1:\"a\";i:430;s:1:\"b\";s:25:\"facebookAdsPricingPackage\";s:1:\"c\";s:31:\"facebookAdsPricingPackageUpdate\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:160;a:5:{s:1:\"a\";i:431;s:1:\"b\";s:19:\"facebookMoreService\";s:1:\"c\";s:23:\"facebookMoreServiceView\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:161;a:5:{s:1:\"a\";i:432;s:1:\"b\";s:19:\"facebookMoreService\";s:1:\"c\";s:22:\"facebookMoreServiceAdd\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:162;a:5:{s:1:\"a\";i:433;s:1:\"b\";s:19:\"facebookMoreService\";s:1:\"c\";s:25:\"facebookMoreServiceDelete\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:163;a:5:{s:1:\"a\";i:434;s:1:\"b\";s:19:\"facebookMoreService\";s:1:\"c\";s:25:\"facebookMoreServiceUpdate\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:164;a:5:{s:1:\"a\";i:435;s:1:\"b\";s:12:\"facebookPage\";s:1:\"c\";s:16:\"facebookPageView\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:165;a:5:{s:1:\"a\";i:436;s:1:\"b\";s:15:\"facebookPackage\";s:1:\"c\";s:19:\"facebookPackageView\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:166;a:5:{s:1:\"a\";i:437;s:1:\"b\";s:15:\"facebookPackage\";s:1:\"c\";s:18:\"facebookPackageAdd\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:167;a:5:{s:1:\"a\";i:438;s:1:\"b\";s:15:\"facebookPackage\";s:1:\"c\";s:21:\"facebookPackageDelete\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:168;a:5:{s:1:\"a\";i:439;s:1:\"b\";s:15:\"facebookPackage\";s:1:\"c\";s:21:\"facebookPackageUpdate\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:169;a:5:{s:1:\"a\";i:441;s:1:\"b\";s:16:\"ukPricingPackage\";s:1:\"c\";s:20:\"ukPricingPackageView\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:170;a:5:{s:1:\"a\";i:442;s:1:\"b\";s:16:\"ukPricingPackage\";s:1:\"c\";s:19:\"ukPricingPackageAdd\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:171;a:5:{s:1:\"a\";i:443;s:1:\"b\";s:16:\"ukPricingPackage\";s:1:\"c\";s:22:\"ukPricingPackageDelete\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:172;a:5:{s:1:\"a\";i:444;s:1:\"b\";s:16:\"ukPricingPackage\";s:1:\"c\";s:22:\"ukPricingPackageUpdate\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:173;a:5:{s:1:\"a\";i:445;s:1:\"b\";s:16:\"ukReviewPlatform\";s:1:\"c\";s:20:\"ukReviewPlatformView\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:174;a:5:{s:1:\"a\";i:446;s:1:\"b\";s:16:\"ukReviewPlatform\";s:1:\"c\";s:22:\"ukReviewPlatformDelete\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:175;a:5:{s:1:\"a\";i:447;s:1:\"b\";s:16:\"ukReviewPlatform\";s:1:\"c\";s:22:\"ukReviewPlatformUpdate\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:176;a:5:{s:1:\"a\";i:448;s:1:\"b\";s:16:\"ukReviewPlatform\";s:1:\"c\";s:19:\"ukReviewPlatformAdd\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:177;a:5:{s:1:\"a\";i:449;s:1:\"b\";s:13:\"ukTestimonial\";s:1:\"c\";s:17:\"ukTestimonialView\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:178;a:5:{s:1:\"a\";i:450;s:1:\"b\";s:13:\"ukTestimonial\";s:1:\"c\";s:16:\"ukTestimonialAdd\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:179;a:5:{s:1:\"a\";i:451;s:1:\"b\";s:13:\"ukTestimonial\";s:1:\"c\";s:19:\"ukTestimonialDelete\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:180;a:5:{s:1:\"a\";i:452;s:1:\"b\";s:13:\"ukTestimonial\";s:1:\"c\";s:19:\"ukTestimonialUpdate\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:181;a:5:{s:1:\"a\";i:453;s:1:\"b\";s:11:\"vpsCategory\";s:1:\"c\";s:15:\"vpsCategoryView\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:182;a:5:{s:1:\"a\";i:454;s:1:\"b\";s:11:\"vpsCategory\";s:1:\"c\";s:14:\"vpsCategoryAdd\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:183;a:5:{s:1:\"a\";i:455;s:1:\"b\";s:11:\"vpsCategory\";s:1:\"c\";s:17:\"vpsCategoryUpdate\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:184;a:5:{s:1:\"a\";i:456;s:1:\"b\";s:11:\"vpsCategory\";s:1:\"c\";s:17:\"vpsCategoryDelete\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:185;a:5:{s:1:\"a\";i:457;s:1:\"b\";s:10:\"vpsPackage\";s:1:\"c\";s:14:\"vpsPackageView\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:186;a:5:{s:1:\"a\";i:458;s:1:\"b\";s:10:\"vpsPackage\";s:1:\"c\";s:13:\"vpsPackageAdd\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:187;a:5:{s:1:\"a\";i:459;s:1:\"b\";s:10:\"vpsPackage\";s:1:\"c\";s:16:\"vpsPackageUpdate\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:188;a:5:{s:1:\"a\";i:460;s:1:\"b\";s:10:\"vpsPackage\";s:1:\"c\";s:16:\"vpsPackageDelete\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:189;a:5:{s:1:\"a\";i:461;s:1:\"b\";s:7:\"vpsPage\";s:1:\"c\";s:11:\"vpsPageView\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:190;a:5:{s:1:\"a\";i:462;s:1:\"b\";s:13:\"ukCompanyPage\";s:1:\"c\";s:17:\"ukCompanyPageView\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:191;a:5:{s:1:\"a\";i:463;s:1:\"b\";s:15:\"storeSideBanner\";s:1:\"c\";s:19:\"storeSideBannerView\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:192;a:5:{s:1:\"a\";i:464;s:1:\"b\";s:15:\"storeSideBanner\";s:1:\"c\";s:18:\"storeSideBannerAdd\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:193;a:5:{s:1:\"a\";i:465;s:1:\"b\";s:15:\"storeSideBanner\";s:1:\"c\";s:21:\"storeSideBannerDelete\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:194;a:5:{s:1:\"a\";i:466;s:1:\"b\";s:15:\"storeSideBanner\";s:1:\"c\";s:21:\"storeSideBannerUpdate\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:195;a:5:{s:1:\"a\";i:467;s:1:\"b\";s:15:\"storeMainBanner\";s:1:\"c\";s:19:\"storeMainBannerView\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:196;a:5:{s:1:\"a\";i:468;s:1:\"b\";s:15:\"storeMainBanner\";s:1:\"c\";s:18:\"storeMainBannerAdd\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:197;a:5:{s:1:\"a\";i:469;s:1:\"b\";s:15:\"storeMainBanner\";s:1:\"c\";s:21:\"storeMainBannerDelete\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:198;a:5:{s:1:\"a\";i:470;s:1:\"b\";s:15:\"storeMainBanner\";s:1:\"c\";s:21:\"storeMainBannerUpdate\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:199;a:5:{s:1:\"a\";i:471;s:1:\"b\";s:7:\"product\";s:1:\"c\";s:10:\"productAdd\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:200;a:5:{s:1:\"a\";i:472;s:1:\"b\";s:7:\"product\";s:1:\"c\";s:11:\"productView\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:201;a:5:{s:1:\"a\";i:473;s:1:\"b\";s:7:\"product\";s:1:\"c\";s:13:\"productDelete\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:202;a:5:{s:1:\"a\";i:474;s:1:\"b\";s:7:\"product\";s:1:\"c\";s:13:\"productUpdate\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:203;a:5:{s:1:\"a\";i:475;s:1:\"b\";s:5:\"order\";s:1:\"c\";s:8:\"orderAdd\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:204;a:5:{s:1:\"a\";i:476;s:1:\"b\";s:5:\"order\";s:1:\"c\";s:9:\"orderView\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:205;a:5:{s:1:\"a\";i:477;s:1:\"b\";s:5:\"order\";s:1:\"c\";s:11:\"orderDelete\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:206;a:5:{s:1:\"a\";i:478;s:1:\"b\";s:5:\"order\";s:1:\"c\";s:11:\"orderUpdate\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:207;a:5:{s:1:\"a\";i:479;s:1:\"b\";s:6:\"review\";s:1:\"c\";s:9:\"reviewAdd\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:208;a:5:{s:1:\"a\";i:480;s:1:\"b\";s:6:\"review\";s:1:\"c\";s:10:\"reviewView\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:209;a:5:{s:1:\"a\";i:481;s:1:\"b\";s:6:\"review\";s:1:\"c\";s:12:\"reviewDelete\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:210;a:5:{s:1:\"a\";i:482;s:1:\"b\";s:6:\"review\";s:1:\"c\";s:12:\"reviewUpdate\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:211;a:5:{s:1:\"a\";i:483;s:1:\"b\";s:6:\"coupon\";s:1:\"c\";s:9:\"couponAdd\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:212;a:5:{s:1:\"a\";i:484;s:1:\"b\";s:6:\"coupon\";s:1:\"c\";s:10:\"couponView\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:213;a:5:{s:1:\"a\";i:485;s:1:\"b\";s:6:\"coupon\";s:1:\"c\";s:12:\"couponDelete\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:214;a:5:{s:1:\"a\";i:486;s:1:\"b\";s:6:\"coupon\";s:1:\"c\";s:12:\"couponUpdate\";s:1:\"d\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}}s:5:\"roles\";a:2:{i:0;a:3:{s:1:\"a\";i:1;s:1:\"c\";s:5:\"Admin\";s:1:\"d\";s:3:\"web\";}i:1;a:3:{s:1:\"a\";i:2;s:1:\"c\";s:11:\"shareHolder\";s:1:\"d\";s:3:\"web\";}}}', 1763720852),
('laravel_cache_system_settings', 'O:28:\"App\\Models\\SystemInformation\":32:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:18:\"system_information\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:17:{s:2:\"id\";i:1;s:8:\"ins_name\";s:14:\"OPTIFUSION INC\";s:4:\"logo\";s:42:\"public/uploads/logo_176318476620251115.png\";s:16:\"rectangular_logo\";s:47:\"public/uploads/rect_logo_176318826820251115.png\";s:4:\"icon\";s:42:\"public/uploads/icon_176318477320251115.png\";s:7:\"address\";s:8:\"dhaka,bd\";s:11:\"address_two\";N;s:5:\"email\";s:22:\"support@optifusion.com\";s:9:\"email_two\";N;s:5:\"phone\";s:14:\"+1 234 567 890\";s:9:\"phone_two\";N;s:8:\"main_url\";s:31:\"http://localhost/ifushionAdmin/\";s:9:\"front_url\";s:25:\"https://iifc.resnova.dev/\";s:11:\"description\";s:155:\"We are dedicated to providing the best products with the best service. Our mission is to bring joy to our customers through a seamless shopping experience.\";s:10:\"develop_by\";s:11:\"Kamruzzaman\";s:10:\"created_at\";s:19:\"2025-05-07 16:43:40\";s:10:\"updated_at\";s:19:\"2025-11-15 06:31:08\";}s:11:\"\0*\0original\";a:17:{s:2:\"id\";i:1;s:8:\"ins_name\";s:14:\"OPTIFUSION INC\";s:4:\"logo\";s:42:\"public/uploads/logo_176318476620251115.png\";s:16:\"rectangular_logo\";s:47:\"public/uploads/rect_logo_176318826820251115.png\";s:4:\"icon\";s:42:\"public/uploads/icon_176318477320251115.png\";s:7:\"address\";s:8:\"dhaka,bd\";s:11:\"address_two\";N;s:5:\"email\";s:22:\"support@optifusion.com\";s:9:\"email_two\";N;s:5:\"phone\";s:14:\"+1 234 567 890\";s:9:\"phone_two\";N;s:8:\"main_url\";s:31:\"http://localhost/ifushionAdmin/\";s:9:\"front_url\";s:25:\"https://iifc.resnova.dev/\";s:11:\"description\";s:155:\"We are dedicated to providing the best products with the best service. Our mission is to bring joy to our customers through a seamless shopping experience.\";s:10:\"develop_by\";s:11:\"Kamruzzaman\";s:10:\"created_at\";s:19:\"2025-05-07 16:43:40\";s:10:\"updated_at\";s:19:\"2025-11-15 06:31:08\";}s:10:\"\0*\0changes\";a:0:{}s:8:\"\0*\0casts\";a:0:{}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:14:{i:0;s:8:\"ins_name\";i:1;s:4:\"logo\";i:2;s:16:\"rectangular_logo\";i:3;s:11:\"description\";i:4;s:10:\"develop_by\";i:5;s:4:\"icon\";i:6;s:7:\"address\";i:7;s:11:\"address_two\";i:8;s:5:\"email\";i:9;s:9:\"email_two\";i:10;s:5:\"phone\";i:11;s:9:\"phone_two\";i:12;s:8:\"main_url\";i:13;s:9:\"front_url\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}', 2078550630);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1=Active, 0=Inactive',
  `parent_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

CREATE TABLE `clients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `image_shape` varchar(255) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `clients`
--

INSERT INTO `clients` (`id`, `image_shape`, `name`, `logo`, `created_at`, `updated_at`) VALUES
(2, 'square', 'Bangladesh Power Development Board (BPDB)', 'public/uploads/clients/1761311466_68fb7aeaa96f1.png', '2025-10-22 00:18:37', '2025-10-24 07:11:06'),
(3, 'rectangular', 'Bangladesh Govt. Ministries & Excuting Agencies', 'public/uploads/clients/1761311397_68fb7aa5110ee.png', '2025-10-24 06:43:51', '2025-10-24 07:09:57'),
(4, 'square', 'Prime Minister of Bangladesh', 'public/uploads/clients/1761311370_68fb7a8a82774.png', '2025-10-24 06:46:08', '2025-10-24 07:09:30'),
(5, 'square', 'Bangladesh Election Commission', 'public/uploads/clients/1761311565_68fb7b4d330e2.png', '2025-10-24 07:12:45', '2025-10-24 07:12:45'),
(6, 'square', 'Bangladesh Bank', 'public/uploads/clients/1761311708_68fb7bdce2634.png', '2025-10-24 07:15:08', '2025-10-24 07:15:08'),
(7, 'square', 'Bangladesh Railway', 'public/uploads/clients/1761311858_68fb7c72e07c6.png', '2025-10-24 07:17:38', '2025-10-24 07:17:38'),
(8, 'square', 'Bangladesh Bridge Authority', 'public/uploads/clients/1761312038_68fb7d269da9e.jpg', '2025-10-24 07:20:38', '2025-10-24 07:20:38'),
(9, 'square', 'Bangladesh Economic Zones Authorit', 'public/uploads/clients/1761312058_68fb7d3aa5570.png', '2025-10-24 07:20:58', '2025-10-24 07:20:58'),
(10, 'square', 'rading Corporation of Bangladesh', 'public/uploads/clients/1761312084_68fb7d54ea70d.jpg', '2025-10-24 07:21:25', '2025-10-24 07:21:25'),
(11, 'square', 'Bangladesh Land Port Authority', 'public/uploads/clients/1761312113_68fb7d71c9248.jpg', '2025-10-24 07:21:53', '2025-10-24 07:21:53'),
(12, 'square', 'Mongla Port Authority', 'public/uploads/clients/1761312145_68fb7d91d7dd3.jpg', '2025-10-24 07:22:25', '2025-10-24 07:22:25'),
(13, 'square', 'Bangladesh Road Transport Authority (BRTA)', 'public/uploads/clients/1761312179_68fb7db313b62.png', '2025-10-24 07:22:59', '2025-10-24 07:22:59'),
(14, 'square', 'National Skills Development Authority', 'public/uploads/clients/1761312814_68fb802e00ee6.png', '2025-10-24 07:33:37', '2025-10-24 07:33:37'),
(15, 'square', 'Bangladesh Inland Water Transport Authority', 'public/uploads/clients/1761312869_68fb806556063.png', '2025-10-24 07:34:29', '2025-10-24 07:34:29'),
(16, 'square', 'Bangladesh Police', 'public/uploads/clients/1761312900_68fb8084279b4.png', '2025-10-24 07:35:00', '2025-10-24 07:35:00'),
(17, 'square', 'Bangladesh Institute of Marine Technology (BIMT)', 'public/uploads/clients/1761313213_68fb81bd76e5c.jpg', '2025-10-24 07:40:13', '2025-10-24 07:40:13'),
(18, 'square', 'Civil Aviation Authority', 'public/uploads/clients/1761313363_68fb8253ee871.png', '2025-10-24 07:42:44', '2025-10-24 07:42:44'),
(19, NULL, 'Ministry of ICT', NULL, '2025-10-28 11:40:13', '2025-10-28 11:40:13'),
(113, NULL, 'Bangladesh Petroleum Exploration and Production Co. Ltd. (BAPEX)', NULL, '2025-10-29 20:49:23', '2025-10-29 20:49:23'),
(114, NULL, 'Roads and Highways Department (RHD)', NULL, '2025-10-29 20:49:23', '2025-10-29 20:49:23'),
(115, NULL, 'Ministry of Youth and Sports (MoYS)', NULL, '2025-10-29 20:49:23', '2025-10-29 20:49:23'),
(116, NULL, 'Maheshkhali Integrated Development Authority (MIDA)', NULL, '2025-10-29 20:49:23', '2025-10-29 20:49:23'),
(117, NULL, 'Rupantarita Prakritik Gas Company Limited (RPGCL)', NULL, '2025-10-29 20:49:23', '2025-10-29 20:49:23'),
(118, NULL, 'North-West Power Generation Company Limited (NWPGCL)', NULL, '2025-10-29 20:49:23', '2025-10-29 20:49:23'),
(119, NULL, 'Bangladesh Chemical Industries Corporation (BCIC)', NULL, '2025-10-29 20:49:23', '2025-10-29 20:49:23'),
(120, NULL, 'Bangladesh Gas Fields Company Limited (BGFCL)', NULL, '2025-10-29 20:49:23', '2025-10-29 20:49:23'),
(121, NULL, 'Legislative and Parliamentary Affairs Division\nMinistry of Law, Justice and Parliamentary Affaires', NULL, '2025-10-29 20:49:23', '2025-10-29 20:49:23'),
(122, NULL, 'Bangladesh Hi-Tech Park Authority (BHTPA)', NULL, '2025-10-29 20:49:23', '2025-10-29 20:49:23'),
(123, NULL, 'Economic Relations Division (ERD)', NULL, '2025-10-29 20:49:23', '2025-10-29 20:49:23'),
(124, NULL, 'Education Engineering Department', NULL, '2025-10-29 20:49:23', '2025-10-29 20:49:23'),
(125, NULL, 'Islamic Foundation', NULL, '2025-10-29 20:49:23', '2025-10-29 20:49:23'),
(126, NULL, 'Bangladesh Shipping Corporation (BSC)', NULL, '2025-10-29 20:49:23', '2025-10-29 20:49:23'),
(127, NULL, 'Bangladesh Tourism Board (BTB)', NULL, '2025-10-29 20:49:23', '2025-10-29 20:49:23'),
(128, NULL, 'Bangladesh Rural Electrification Board (BREB)', NULL, '2025-10-29 20:49:23', '2025-10-29 20:49:23'),
(129, NULL, 'Local Government Engineering Department (LGED)', NULL, '2025-10-29 20:49:23', '2025-10-29 20:49:23'),
(130, NULL, 'Bangladesh Small and Cottage Industries Corporation (BSCIC)', NULL, '2025-10-29 20:49:23', '2025-10-29 20:49:23'),
(131, NULL, 'Chattogram Port Authority (CPA)', NULL, '2025-10-29 20:49:23', '2025-10-29 20:49:23'),
(132, NULL, 'German Agency for International Cooperation (GIZ)', NULL, '2025-10-29 20:49:23', '2025-10-29 20:49:23'),
(133, NULL, 'Bangladesh Bridge Authority (BBA)', NULL, '2025-10-29 20:49:23', '2025-10-29 20:49:23'),
(134, NULL, 'Electricity Generation Company of Bangladesh (EGCB)', NULL, '2025-10-29 20:49:23', '2025-10-29 20:49:23'),
(135, NULL, 'Power Grid Bangladesh PLC', NULL, '2025-10-29 20:49:23', '2025-10-29 20:49:23'),
(136, NULL, 'National Academy for Primary Education (NAPE)', NULL, '2025-10-29 20:49:23', '2025-10-29 20:49:23'),
(137, NULL, 'Planning Commission', NULL, '2025-10-29 20:49:23', '2025-10-29 20:49:23'),
(138, NULL, 'Bangladesh Institute of Management (BIM)', NULL, '2025-10-29 20:49:23', '2025-10-29 20:49:23'),
(139, NULL, 'Department of Information and Communication Technology (DoICT)', NULL, '2025-10-29 20:49:23', '2025-10-29 20:49:23'),
(140, NULL, 'Gas Transmission Company Ltd (GTCL)', NULL, '2025-10-29 20:49:23', '2025-10-29 20:49:23'),
(141, NULL, 'Dhaka South City Corporation (DSCC)', NULL, '2025-10-29 20:49:23', '2025-10-29 20:49:23'),
(142, NULL, 'Sunamganj Science and Technology University', NULL, '2025-10-29 20:49:23', '2025-10-29 20:49:23'),
(143, NULL, 'Dhaka North City Corporation (DNCC)', NULL, '2025-10-29 20:49:23', '2025-10-29 20:49:23'),
(144, NULL, 'Department of Fisheries', NULL, '2025-10-29 20:49:23', '2025-10-29 20:49:23'),
(145, NULL, 'DEPARTMENT OF PUBLIC HEALTH ENGINEERING (DPHE)', NULL, '2025-10-29 20:49:23', '2025-10-29 20:49:23'),
(146, NULL, 'Bangladesh Railway (BR)', NULL, '2025-10-29 20:49:23', '2025-10-29 20:49:23'),
(147, NULL, 'Bangladesh Industrial Technical Assistance Center (BITAC)', NULL, '2025-10-29 20:49:23', '2025-10-29 20:49:23'),
(148, NULL, 'B-R Powergen Limited (BRPL)', NULL, '2025-10-29 20:49:23', '2025-10-29 20:49:23'),
(149, NULL, 'Civil Aviation Authority of Bangladesh (CAAB)', NULL, '2025-10-29 20:49:23', '2025-10-29 20:49:23'),
(150, NULL, 'BSCIC Leather Industrial Park', NULL, '2025-10-29 20:49:23', '2025-10-29 20:49:23'),
(151, NULL, 'Ashuganj Power Station Company Ltd.', NULL, '2025-10-29 20:49:23', '2025-10-29 20:49:23'),
(152, NULL, 'National Skills Development Authority (NSDA)', NULL, '2025-10-29 20:49:23', '2025-10-29 20:49:23'),
(153, NULL, 'Ministry of Primary and Mass Education', NULL, '2025-10-29 20:49:23', '2025-10-29 20:49:23'),
(154, NULL, 'CABI International', NULL, '2025-10-29 20:49:23', '2025-10-29 20:49:23'),
(155, NULL, 'Trading Corporation of Bangladesh (TCB)', NULL, '2025-10-29 20:49:23', '2025-10-29 20:49:23'),
(156, NULL, 'Jalalabad Gas Transmission and Distribution System Ltd', NULL, '2025-10-29 20:49:23', '2025-10-29 20:49:23'),
(157, NULL, 'Karnaphuli Gas Distribution Company Limited (KGDCL)', NULL, '2025-10-29 20:49:23', '2025-10-29 20:49:23'),
(158, NULL, 'Ministry of Religious Affairs (MoRA)', NULL, '2025-10-29 20:49:23', '2025-10-29 20:49:23'),
(159, NULL, 'Misc.', NULL, '2025-10-29 20:49:23', '2025-10-29 20:49:23'),
(160, NULL, 'National University (NU)', NULL, '2025-10-29 20:49:23', '2025-10-29 20:49:23'),
(161, NULL, 'Ministry of Power, Energy and Mineral Resources', NULL, '2025-10-29 20:49:23', '2025-10-29 20:49:23'),
(162, NULL, 'Bangladesh Food Safety Authority (STIRC) Project', NULL, '2025-10-29 20:49:23', '2025-10-29 20:49:23'),
(163, NULL, 'PGCL Franchise Area', NULL, '2025-10-29 20:49:23', '2025-10-29 20:49:23'),
(164, NULL, 'BRTA Office cum Motor Driving Testing, Training & Multipurpose Center (BMDTTMC)', NULL, '2025-10-29 20:49:23', '2025-10-29 20:49:23'),
(165, NULL, 'Ministry of Social Welfare', NULL, '2025-10-29 20:49:23', '2025-10-29 20:49:23'),
(166, NULL, 'Atlas Bangladesh Limited (ABL), Tongi, Gazipur', NULL, '2025-10-29 20:49:23', '2025-10-29 20:49:23'),
(167, NULL, 'DESCO', NULL, '2025-10-29 20:49:23', '2025-10-29 20:49:23'),
(168, NULL, 'NGO Affairs Bureau (NGOAB)', NULL, '2025-10-29 20:49:23', '2025-10-29 20:49:23'),
(169, NULL, 'Petrobangla', NULL, '2025-10-29 20:49:23', '2025-10-29 20:49:23'),
(170, NULL, 'Karnafuly Ship Builders Limited. (KSBL)', NULL, '2025-10-29 20:49:23', '2025-10-29 20:49:23'),
(171, NULL, 'Maddhapara Granite Mining Company Limited (MGMCL)', NULL, '2025-10-29 20:49:23', '2025-10-29 20:49:23'),
(172, NULL, 'Bangladesh Forest Industries Development Corporation (BFIDC)', NULL, '2025-10-29 20:49:23', '2025-10-29 20:49:23'),
(173, NULL, 'Bangladesh National Museum', NULL, '2025-10-29 20:49:23', '2025-10-29 20:49:23'),
(174, NULL, 'Titas Gas Transmission and Distribution Company Limited (TGTDCL)', NULL, '2025-10-29 20:49:23', '2025-10-29 20:49:23'),
(175, NULL, 'Bangladesh Export Processing Zone Authority (BEPZA)', NULL, '2025-10-29 20:49:23', '2025-10-29 20:49:23'),
(176, NULL, 'National Housing Authority (NHA)', NULL, '2025-10-29 20:49:23', '2025-10-29 20:49:23'),
(177, NULL, 'Power Division, Ministry of Power, Energy and Mineral Resources', NULL, '2025-10-29 20:49:24', '2025-10-29 20:49:24'),
(178, NULL, 'Bangladesh Economic  Zones Authority (BEZA)', NULL, '2025-10-29 20:49:24', '2025-10-29 20:49:24'),
(179, NULL, 'National Museum of Science and Technology (NMST)', NULL, '2025-10-29 20:49:24', '2025-10-29 20:49:24'),
(180, NULL, 'Power Grid Company of Bangladesh Limited', NULL, '2025-10-29 20:49:24', '2025-10-29 20:49:24'),
(181, NULL, 'Bangladesh Telecommunication  Company Limited (BTCL)', NULL, '2025-10-29 20:49:24', '2025-10-29 20:49:24'),
(182, NULL, 'Global Rural Environment Socitety (GRES)', NULL, '2025-10-29 20:49:24', '2025-10-29 20:49:24'),
(183, NULL, 'Directorate General of Health Services (DGHS)', NULL, '2025-10-29 20:49:24', '2025-10-29 20:49:24'),
(184, NULL, 'Container Company of Bangladesh Ltd. (CCBL)', NULL, '2025-10-29 20:49:24', '2025-10-29 20:49:24'),
(185, NULL, 'City Group', NULL, '2025-10-29 20:49:24', '2025-10-29 20:49:24'),
(186, NULL, 'Bangabandhu Sheikh Mujibur Rahman Maritime University (BSMRMU)', NULL, '2025-10-29 20:49:24', '2025-10-29 20:49:24'),
(187, NULL, 'Payra Port Authority (PPA)', NULL, '2025-10-29 20:49:24', '2025-10-29 20:49:24'),
(188, NULL, 'Tannery Industry & Estate Dhaka, TIED (BSCIC)', NULL, '2025-10-29 20:49:24', '2025-10-29 20:49:24'),
(189, NULL, 'Dockyard & Engineering Works Ltd.', NULL, '2025-10-29 20:49:24', '2025-10-29 20:49:24'),
(190, NULL, 'Sundarban Gas Company Limited (SGCL)', NULL, '2025-10-29 20:49:24', '2025-10-29 20:49:24'),
(191, NULL, 'World Bank (WB)', NULL, '2025-10-29 20:49:24', '2025-10-29 20:49:24'),
(192, NULL, 'Confidence Power Holdings Ltd.', NULL, '2025-10-29 20:49:24', '2025-10-29 20:49:24'),
(193, NULL, 'SNV Netherlands Development Organization', NULL, '2025-10-29 20:49:24', '2025-10-29 20:49:24'),
(194, NULL, 'Bangladesh Inland Water Transport Corporation (BIWTC)', NULL, '2025-10-29 20:49:24', '2025-10-29 20:49:24'),
(195, NULL, 'Dhaka Transport Coordination Authority (DTCA)', NULL, '2025-10-29 20:49:24', '2025-10-29 20:49:24'),
(196, NULL, 'Bangladesh Textile Mills Corporation (BTMC)', NULL, '2025-10-29 20:49:24', '2025-10-29 20:49:24'),
(197, NULL, 'Bangladesh Steel Engineering Corporation (BSEC)', NULL, '2025-10-29 20:49:24', '2025-10-29 20:49:24'),
(198, NULL, 'Bangladesh Land Port Authority (BLPA)', NULL, '2025-10-29 20:49:24', '2025-10-29 20:49:24'),
(199, NULL, 'Bangladesh Rural Development Board (BRDB)', NULL, '2025-10-29 20:49:24', '2025-10-29 20:49:24'),
(200, NULL, 'Bangladesh Bureau of Educational Information and Statistics (BANBEIS)', NULL, '2025-10-29 20:49:24', '2025-10-29 20:49:24'),
(201, NULL, 'Pashchimanchal Gas Company Limited (PGCL)', NULL, '2025-10-29 20:49:24', '2025-10-29 20:49:24'),
(202, NULL, 'General Electric Manufacturing Company Limited (GEMCO)', NULL, '2025-10-29 20:49:24', '2025-10-29 20:49:24'),
(203, NULL, 'Public Private Partnership Authority (PPPA)', NULL, '2025-10-29 20:49:24', '2025-10-29 20:49:24'),
(204, NULL, 'Evaluation Sector, IMED, Ministry of Planning', NULL, '2025-10-29 20:49:24', '2025-10-29 20:49:24'),
(205, NULL, 'Hamid Real Estate Construction Ltd.', NULL, '2025-10-29 20:49:24', '2025-10-29 20:49:24'),
(206, NULL, 'Department Of Textiles', NULL, '2025-10-29 20:49:24', '2025-10-29 20:49:24'),
(207, NULL, 'Barapukuria Coal Mining Company Ltd. (BCMCL)', NULL, '2025-10-29 20:49:24', '2025-10-29 20:49:24'),
(208, NULL, 'Central Procurement Technical Unit, IMED, Ministry of Planning', NULL, '2025-10-29 20:49:24', '2025-10-29 20:49:24'),
(209, NULL, 'Asian Development Bank (ADB)', NULL, '2025-10-29 20:49:24', '2025-10-29 20:49:24'),
(210, NULL, 'Bangladesh Municipal Development Fund', NULL, '2025-10-29 20:49:24', '2025-10-29 20:49:24'),
(211, NULL, 'Ricardo-AEA Ltd., United Kingdom', NULL, '2025-10-29 20:49:24', '2025-10-29 20:49:24'),
(212, NULL, 'Finance Division, Ministry of Finance', NULL, '2025-10-29 20:49:24', '2025-10-29 20:49:24'),
(213, NULL, 'Deloitte, India', NULL, '2025-10-29 20:49:24', '2025-10-29 20:49:24'),
(214, NULL, 'Ministry of Telecommunications and Postal Services\n(Ministry of Transport, Roads and Bridges), South Sudan', NULL, '2025-10-29 20:49:24', '2025-10-29 20:49:24'),
(215, NULL, 'China Harbour Engineering Company Ltd. (CHEC)', NULL, '2025-10-29 20:49:24', '2025-10-29 20:49:24'),
(216, NULL, 'Federal Ministry of Communications Technology, \nAbuja, Nigeria', NULL, '2025-10-29 20:49:24', '2025-10-29 20:49:24'),
(217, NULL, 'Prime Minister\'s Office', NULL, '2025-10-29 20:49:24', '2025-10-29 20:49:24'),
(218, NULL, 'IMC Worldwide Limited Ltd/DFID', NULL, '2025-10-29 20:49:24', '2025-10-29 20:49:24'),
(219, NULL, 'SAARC Energy Centre, Pakistan', NULL, '2025-10-29 20:49:24', '2025-10-29 20:49:24'),
(220, NULL, 'Korea Global Development Consulting Center (KGDC), KOICA Bangladesh', NULL, '2025-10-29 20:49:24', '2025-10-29 20:49:24'),
(221, NULL, 'Millennium Challenge Account-Indonesia (MCA-I)', NULL, '2025-10-29 20:49:24', '2025-10-29 20:49:24'),
(222, NULL, 'Kenya Ferry Services Limited', NULL, '2025-10-29 20:49:24', '2025-10-29 20:49:24'),
(223, NULL, 'Bangladesh Infrastructure Finance Fund Ltd (BIFFL)', NULL, '2025-10-29 20:49:24', '2025-10-29 20:49:24'),
(224, NULL, 'Kigali Institute of Science and Technology (KIST), Rwanda', NULL, '2025-10-29 20:49:24', '2025-10-29 20:49:24'),
(225, NULL, 'Advance SCS', NULL, '2025-10-29 20:49:24', '2025-10-29 20:49:24'),
(226, NULL, 'Ministry of Information and Communication Technology (MoICT)', NULL, '2025-10-29 20:49:24', '2025-10-29 20:49:24'),
(227, NULL, 'Energy, Water and Sanitation Authority (EWSA) Rwanda', NULL, '2025-10-29 20:49:24', '2025-10-29 20:49:24'),
(228, NULL, 'IFC-Bangladesh Investment Climate Fund (BICF)', NULL, '2025-10-29 20:49:24', '2025-10-29 20:49:24'),
(229, NULL, 'Chittagong Dry Dock Ltd., Bangladesh', NULL, '2025-10-29 20:49:24', '2025-10-29 20:49:24'),
(230, NULL, 'Hydrocarbon Unit (HCU), Ministry of Power, Energy and Mineral Resources', NULL, '2025-10-29 20:49:24', '2025-10-29 20:49:24'),
(231, NULL, 'Japan Embassy', NULL, '2025-10-29 20:49:24', '2025-10-29 20:49:24'),
(232, NULL, 'Arctas Capital Group, USA', NULL, '2025-10-29 20:49:24', '2025-10-29 20:49:24'),
(233, NULL, 'Bangladesh Computer Council (BCC)', NULL, '2025-10-29 20:49:24', '2025-10-29 20:49:24'),
(234, NULL, 'Civil Service Academy, Bangladesh', NULL, '2025-10-29 20:49:24', '2025-10-29 20:49:24'),
(235, NULL, 'BRAC University', NULL, '2025-10-29 20:49:24', '2025-10-29 20:49:24'),
(236, NULL, 'UNDP', NULL, '2025-10-29 20:49:24', '2025-10-29 20:49:24'),
(237, NULL, 'UNESCAP', NULL, '2025-10-29 20:49:24', '2025-10-29 20:49:24'),
(238, NULL, 'Bangladesh Telecommunication  Regulatory Commission (BTRC)', NULL, '2025-10-29 20:49:24', '2025-10-29 20:49:24'),
(239, NULL, 'DFID, UK', NULL, '2025-10-29 20:49:24', '2025-10-29 20:49:24'),
(240, NULL, 'K&M Engineering and Consulting LLC, USA', NULL, '2025-10-29 20:49:24', '2025-10-29 20:49:24'),
(241, NULL, 'Malancha Holdings Ltd., Bangladesh', NULL, '2025-10-29 20:49:24', '2025-10-29 20:49:24'),
(242, NULL, 'Japan Bank for International Cooperation (JBIC)', NULL, '2025-10-29 20:49:24', '2025-10-29 20:49:24'),
(243, NULL, 'Dhaka City Corporation, Bangladesh (DCC)', NULL, '2025-10-29 20:49:24', '2025-10-29 20:49:24'),
(244, NULL, 'Board of Investment, Bangladesh', NULL, '2025-10-29 20:49:24', '2025-10-29 20:49:24'),
(245, NULL, 'Export Promotion Bureau, Bangladesh', NULL, '2025-10-29 20:49:24', '2025-10-29 20:49:24'),
(246, NULL, 'Rajdhani Unnayan Katripakkha, Bangladesh', NULL, '2025-10-29 20:49:24', '2025-10-29 20:49:24'),
(247, NULL, 'PHP-Essar Power Consortium', NULL, '2025-10-29 20:49:24', '2025-10-29 20:49:24'),
(248, NULL, 'Uganda Rural Electrification Board (UREB)', NULL, '2025-10-29 20:49:24', '2025-10-29 20:49:24'),
(249, NULL, 'Rafiul Karim and Associates, Bangladesh', NULL, '2025-10-29 20:49:24', '2025-10-29 20:49:24'),
(250, NULL, 'Dulamia Cotton Spinning Mills Ltd., Bangladesh', NULL, '2025-10-29 20:49:24', '2025-10-29 20:49:24'),
(251, NULL, 'Bangladesh Road Transport Corporation (BRTC)', NULL, '2025-10-29 20:49:24', '2025-10-29 20:49:24'),
(252, NULL, 'Local Government Division (LGD), Bangladesh', NULL, '2025-10-29 20:49:24', '2025-10-29 20:49:24'),
(253, NULL, 'Ministry of Communication, Bangladesh', NULL, '2025-10-29 20:49:24', '2025-10-29 20:49:24'),
(254, NULL, 'Mongla Port Authority, Bangladesh', NULL, '2025-10-29 20:49:24', '2025-10-29 20:49:24'),
(255, NULL, 'Ministry of Water Resources, Bangladesh', NULL, '2025-10-29 20:49:24', '2025-10-29 20:49:24'),
(256, NULL, 'Bangladesh  Shipping Corporation, Bangladesh', NULL, '2025-10-29 20:49:25', '2025-10-29 20:49:25'),
(257, NULL, 'Biman Bangladesh Airlines, Bangladesh', NULL, '2025-10-29 20:49:25', '2025-10-29 20:49:25'),
(258, NULL, 'Ministry of Posts and Telecommunications, Bangladesh', NULL, '2025-10-29 20:49:25', '2025-10-29 20:49:25'),
(259, NULL, 'Ministry of Energy and Mineral Resources, Bangladesh', NULL, '2025-10-29 20:49:25', '2025-10-29 20:49:25'),
(260, 'square', 'Ministry of Shipping, Bangladesh', NULL, '2025-10-29 20:49:25', '2025-11-02 04:57:45');

-- --------------------------------------------------------

--
-- Table structure for table `client_says`
--

CREATE TABLE `client_says` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `des` longtext DEFAULT NULL,
  `youtube_video_link` longtext NOT NULL,
  `status` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `phone_one` varchar(255) NOT NULL,
  `phone_two` varchar(255) NOT NULL,
  `email_one` varchar(255) NOT NULL,
  `email_two` varchar(255) NOT NULL,
  `address_one` longtext NOT NULL,
  `address_two` longtext NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contact_us_messages`
--

CREATE TABLE `contact_us_messages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `mobilenumber` varchar(255) DEFAULT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `contact_us_messages`
--

INSERT INTO `contact_us_messages` (`id`, `fullname`, `email`, `mobilenumber`, `message`, `created_at`, `updated_at`) VALUES
(5, 'demo', 'rakinhasan.badhan33@gmail.com', '017111111111', 'test', '2025-10-26 09:08:23', '2025-10-26 09:08:23');

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE `countries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `iso3` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`id`, `name`, `iso3`, `status`, `created_at`, `updated_at`) VALUES
(14, 'Bangladesh', 'BGD', 1, '2025-10-21 10:32:25', '2025-11-05 23:06:31'),
(77, 'India', 'IND', 1, '2025-10-21 10:32:25', '2025-11-05 23:06:46'),
(78, 'Indonesia', 'IDN', 1, '2025-10-21 10:32:25', '2025-11-05 23:07:15'),
(88, 'Kenya', 'KEN', 1, '2025-10-21 10:32:25', '2025-11-05 23:07:43'),
(129, 'Nigeria', 'NGA', 1, '2025-10-21 10:32:25', '2025-10-21 10:32:25'),
(133, 'Pakistan', 'PAK', 1, '2025-10-21 10:32:25', '2025-10-21 10:32:25'),
(146, 'Rwanda', 'RWA', 1, '2025-10-21 10:32:25', '2025-10-21 10:32:25'),
(157, 'Sierra Leone', 'SLE', 1, '2025-10-21 10:32:25', '2025-10-21 10:32:25'),
(164, 'South Sudan', 'SSD', 1, '2025-10-21 10:32:25', '2025-10-21 10:32:25'),
(166, 'Sri Lanka', 'LKA', 1, '2025-10-21 10:32:25', '2025-10-21 10:32:25');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `old_customer_id` varchar(255) DEFAULT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `user_id` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT 'normal',
  `source` varchar(255) NOT NULL DEFAULT 'admin',
  `reward_points` int(20) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `name`, `email`, `phone`, `password`, `address`, `old_customer_id`, `slug`, `user_id`, `type`, `source`, `reward_points`, `status`, `created_at`, `updated_at`) VALUES
(7599, 'Kamruzzaman kajol', NULL, '01646735102', NULL, NULL, NULL, NULL, NULL, 'normal', 'admin', NULL, 1, '2025-10-04 04:23:05', '2025-10-04 04:23:05'),
(7600, 'Kamruzzaman kajol', NULL, '01646739100', NULL, 'Rajshahi', NULL, NULL, NULL, 'normal', 'admin', NULL, 1, '2025-10-10 08:59:48', '2025-10-10 08:59:48'),
(7601, 'Kamruzzaman kajol', NULL, '016467351068', NULL, 'Rajshahi', NULL, NULL, NULL, 'normal', 'website', NULL, 1, '2025-10-12 04:09:31', '2025-10-12 04:09:31'),
(7602, 'Kamruzzaman kajol', 'wc@gmail.com', '01646735100', '$2y$12$.1HXNZX7kL72Riid5ayDg.YcYsGjOWVTkCWUL1GCrHgPwF1OTcp5q', NULL, NULL, 'kamruzzaman-kajol-68ef5e8f41b1c', '3858', 'normal', 'website', NULL, 1, '2025-10-15 02:42:55', '2025-10-15 02:42:55');

-- --------------------------------------------------------

--
-- Table structure for table `customer_addresses`
--

CREATE TABLE `customer_addresses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `address` text NOT NULL,
  `address_type` varchar(255) DEFAULT NULL COMMENT 'e.g., Home, Office',
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customer_addresses`
--

INSERT INTO `customer_addresses` (`id`, `customer_id`, `address`, `address_type`, `is_default`, `created_at`, `updated_at`) VALUES
(3, 48, 'Rajshahi', 'Home', 0, '2025-08-14 02:39:47', '2025-08-14 02:39:47'),
(4, 7594, 'Rajshahi, Batiaghata, Khulna', 'billing', 0, '2025-09-09 01:04:02', '2025-09-09 18:48:06'),
(5, 7594, 'Rajshahi, Batiaghata, Khulna', 'shipping', 1, '2025-09-09 01:04:02', '2025-09-09 18:48:07'),
(7, 7595, 'Rajshahi, Gaibandha Sadar, Gaibandha', 'billing', 0, '2025-09-10 23:59:51', '2025-09-10 23:59:51'),
(8, 7595, 'Rajshahi, Gaibandha Sadar, Gaibandha', 'shipping', 1, '2025-09-10 23:59:51', '2025-09-10 23:59:51'),
(10, 7597, 'dad, Gobindaganj ( Gaibandha ), Gaibandha', 'Home', 1, '2025-09-13 01:28:26', '2025-09-13 01:28:26'),
(12, 7599, 'Rajshahi', 'Home', 1, '2025-10-10 07:35:58', '2025-10-10 07:35:58');

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `name`, `status`, `created_at`, `updated_at`) VALUES
(1, 'IIFC', NULL, '2025-10-20 03:10:59', '2025-10-20 07:47:27'),
(3, 'Economic Relations Division (ERD)', NULL, '2025-10-20 07:47:43', '2025-10-20 07:47:43'),
(4, 'Power Division', NULL, '2025-10-20 07:48:05', '2025-10-20 07:48:05'),
(5, '(Asia, JEC and F&F Wing), ERD', NULL, '2025-10-20 07:48:35', '2025-10-20 07:48:35'),
(6, 'FBCCI', NULL, '2025-10-20 07:49:22', '2025-10-20 07:49:22'),
(7, 'PRAN-RFL Group', NULL, '2025-10-20 07:50:15', '2025-10-20 07:50:15'),
(8, 'Apex Footwear Ltd', NULL, '2025-10-20 07:51:07', '2025-10-20 07:51:07'),
(9, 'Ministry of Shipping', NULL, '2025-10-20 07:51:41', '2025-10-20 07:51:41'),
(10, 'Bangladesh Economic Zones Authority (BEZA)', NULL, '2025-10-20 07:52:15', '2025-10-20 07:52:15'),
(11, 'ICT Division', NULL, '2025-10-20 07:52:34', '2025-10-20 07:52:34'),
(12, 'Ministry of Railways', NULL, '2025-10-20 07:53:02', '2025-10-20 07:53:02'),
(13, 'Bridges Division', NULL, '2025-10-20 07:54:22', '2025-10-20 07:54:22'),
(14, 'World Bank Wing', NULL, '2025-10-20 07:55:32', '2025-10-20 07:55:32'),
(15, 'Finance & HR', NULL, '2025-10-20 07:56:16', '2025-10-20 07:56:16'),
(16, 'Physical Infrastructure', NULL, '2025-10-20 07:57:01', '2025-10-20 07:57:01'),
(17, 'Industry, Power & Energy', NULL, '2025-10-20 07:57:36', '2025-10-20 07:57:36'),
(18, 'Finance & Accounts', NULL, '2025-10-20 07:58:02', '2025-10-20 07:58:02'),
(19, 'HR', NULL, '2025-10-20 07:58:10', '2025-10-20 07:58:10'),
(20, 'IT', NULL, '2025-10-20 07:58:42', '2025-10-20 07:58:42');

-- --------------------------------------------------------

--
-- Table structure for table `department_infos`
--

CREATE TABLE `department_infos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `officer_id` bigint(20) UNSIGNED NOT NULL,
  `designation_id` bigint(20) UNSIGNED DEFAULT NULL,
  `department_id` bigint(20) UNSIGNED DEFAULT NULL,
  `additional_text` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `department_infos`
--

INSERT INTO `department_infos` (`id`, `officer_id`, `designation_id`, `department_id`, `additional_text`, `created_at`, `updated_at`) VALUES
(70, 43, 22, 1, NULL, '2025-10-24 11:39:31', '2025-10-24 11:39:31'),
(87, 9, 8, 1, NULL, '2025-10-29 18:18:49', '2025-10-29 18:18:49'),
(88, 10, 8, 1, NULL, '2025-10-29 18:21:28', '2025-10-29 18:21:28'),
(89, 11, 8, 1, NULL, '2025-10-29 18:22:23', '2025-10-29 18:22:23'),
(90, 44, 8, 1, NULL, '2025-10-29 18:23:03', '2025-10-29 18:23:03'),
(91, 45, 8, 1, NULL, '2025-10-29 18:23:52', '2025-10-29 18:23:52'),
(92, 46, 8, 1, NULL, '2025-10-29 18:24:45', '2025-10-29 18:24:45'),
(93, 47, 8, 1, NULL, '2025-10-29 18:25:33', '2025-10-29 18:25:33'),
(94, 48, 8, 1, NULL, '2025-10-29 18:26:17', '2025-10-29 18:26:17'),
(95, 49, 8, 1, NULL, '2025-10-29 18:26:59', '2025-10-29 18:26:59'),
(96, 50, 8, 1, NULL, '2025-10-29 18:27:50', '2025-10-29 18:27:50'),
(97, 51, 8, 1, NULL, '2025-10-29 18:28:33', '2025-10-29 18:28:33'),
(98, 52, 8, 1, NULL, '2025-10-29 18:29:27', '2025-10-29 18:29:27'),
(99, 53, 8, 1, NULL, '2025-10-29 18:30:11', '2025-10-29 18:30:11'),
(100, 54, 8, 1, NULL, '2025-10-29 18:30:57', '2025-10-29 18:30:57'),
(135, 7, NULL, NULL, 'Executive Director (Finance & HR) and Company Secretary', '2025-10-29 19:10:18', '2025-10-29 19:10:18'),
(136, 21, NULL, NULL, 'Additional Director', '2025-10-29 19:10:42', '2025-10-29 19:10:42'),
(137, 30, NULL, NULL, 'Deputy Director', '2025-10-29 19:10:53', '2025-10-29 19:10:53'),
(138, 31, NULL, NULL, 'Deputy Director', '2025-10-29 19:11:05', '2025-10-29 19:11:05'),
(139, 22, NULL, NULL, 'Deputy Director (Finance & Accounts)', '2025-10-29 19:11:16', '2025-10-29 19:11:16'),
(140, 25, NULL, NULL, 'Senior Assistant Director', '2025-10-29 19:11:28', '2025-10-29 19:11:28'),
(141, 26, NULL, NULL, 'Senior Assistant Director', '2025-10-29 19:11:41', '2025-10-29 19:11:41'),
(142, 34, NULL, NULL, 'Senior Assistant Director', '2025-10-29 19:11:53', '2025-10-29 19:11:53'),
(143, 27, NULL, NULL, 'Assistant Director (IT)', '2025-10-29 19:12:04', '2025-10-29 19:12:04'),
(144, 23, NULL, NULL, 'Assistant Director (HR)', '2025-10-29 19:12:17', '2025-10-29 19:12:17'),
(145, 36, NULL, NULL, 'Assistant Director', '2025-10-29 19:12:28', '2025-10-29 19:12:28'),
(146, 37, NULL, NULL, 'Assistant Director', '2025-10-29 19:12:39', '2025-10-29 19:12:39'),
(147, 24, NULL, NULL, 'Assistant Director (Finance & Accounts)', '2025-10-29 19:12:50', '2025-10-29 19:12:50'),
(148, 38, NULL, NULL, 'Admin Officer', '2025-10-29 19:13:03', '2025-10-29 19:13:03'),
(149, 39, NULL, NULL, 'Receptionist', '2025-10-29 19:13:16', '2025-10-29 19:13:16'),
(150, 41, NULL, NULL, 'Project Officer', '2025-10-29 19:13:28', '2025-10-29 19:13:28'),
(153, 28, NULL, NULL, 'Joint Director', '2025-10-29 19:17:09', '2025-10-29 19:17:09'),
(154, 32, NULL, NULL, 'Senior Assistant Director', '2025-10-29 19:17:19', '2025-10-29 19:17:19'),
(155, 35, NULL, NULL, 'Assistant Director', '2025-10-29 19:17:30', '2025-10-29 19:17:30'),
(156, 40, NULL, NULL, 'Project Officer', '2025-10-29 19:17:44', '2025-10-29 19:17:44'),
(157, 42, NULL, NULL, 'Project Officer', '2025-10-29 19:17:55', '2025-10-29 19:17:55'),
(158, 56, NULL, NULL, 'Project Officer', '2025-10-29 19:18:11', '2025-10-29 19:18:11'),
(159, 19, NULL, NULL, 'Executive Director (Industry, Power & Energy)', '2025-10-29 19:18:27', '2025-10-29 19:18:27'),
(160, 29, NULL, NULL, 'Joint Director', '2025-10-29 19:18:37', '2025-10-29 19:18:37'),
(161, 33, NULL, NULL, 'Senior Assistant Director', '2025-10-29 19:18:51', '2025-10-29 19:18:51'),
(162, 55, NULL, NULL, 'Project Officer', '2025-10-29 19:19:15', '2025-10-29 19:19:15'),
(167, 13, NULL, NULL, 'Director, IIFC and Chairman & CEO, PRAN-RFL Group', '2025-10-29 20:18:57', '2025-10-29 20:18:57'),
(168, 14, NULL, NULL, 'Director, IIFC and Managing Director, Apex Footwear Ltd.', '2025-10-29 20:19:18', '2025-10-29 20:19:18'),
(173, 5, NULL, NULL, 'ember, IIFC and Executive Chairman, Bangladesh Economic Zones Authority (BEZA) (Ex-officio)', '2025-10-29 20:21:48', '2025-10-29 20:21:48'),
(175, 16, NULL, NULL, 'Member, IIFC and Secretary, Ministry of Railways (Ex-officio)', '2025-10-29 20:22:44', '2025-10-29 20:22:44'),
(176, 17, NULL, NULL, 'Member, IIFC and Secretary, Bridges Division (Ex-officio)', '2025-10-29 20:23:03', '2025-10-29 20:23:03'),
(177, 18, NULL, NULL, 'Member, IIFC and Additional Secretary (World Bank Wing), ERD (Ex-officio)', '2025-10-29 20:23:20', '2025-10-29 20:23:20'),
(178, 20, NULL, NULL, 'Additional Director', '2025-10-30 06:18:15', '2025-10-30 06:18:15'),
(180, 2, NULL, NULL, 'Director, IIFC and Secretary, Power Division', '2025-10-30 06:25:21', '2025-10-30 06:25:21'),
(181, 3, NULL, NULL, 'Director, IIFC and Additional Secretary (Asia, JEC and F&F Wing), ERD', '2025-10-30 06:26:05', '2025-10-30 06:26:05'),
(182, 12, NULL, NULL, 'Director, IIFC and Former Additional Secretary & Administrator, FBCCI', '2025-10-30 06:26:48', '2025-10-30 06:26:48'),
(183, 4, NULL, NULL, 'Member, IIFC and Senior Secretary, Ministry of Shipping (Ex-officio)', '2025-10-30 06:33:22', '2025-10-30 06:33:22'),
(184, 15, NULL, NULL, 'Member, IIFC and Secretary, ICT Division (Ex-officio)', '2025-10-30 06:33:32', '2025-10-30 06:33:32'),
(185, 1, NULL, NULL, 'Chairman, IIFC and Secretary, Economic Relations Division (ERD)', '2025-11-02 01:15:26', '2025-11-02 01:15:26'),
(186, 57, NULL, NULL, 'Interim ED & CEO', '2025-11-02 03:21:33', '2025-11-02 03:21:33'),
(187, 58, NULL, NULL, 'ED & CEO and Managing Director', '2025-11-02 03:23:16', '2025-11-02 03:23:16'),
(188, 59, NULL, NULL, 'Managing Director (Additional Charge)', '2025-11-02 03:24:17', '2025-11-02 03:24:17'),
(189, 60, NULL, NULL, 'Managing Director', '2025-11-02 03:25:19', '2025-11-02 03:25:19'),
(190, 61, NULL, NULL, 'Managing Director (Additional Charge)', '2025-11-02 03:26:19', '2025-11-02 03:26:19'),
(194, 6, NULL, NULL, 'Managing Director', '2025-11-13 03:01:37', '2025-11-13 03:01:37');

-- --------------------------------------------------------

--
-- Table structure for table `designations`
--

CREATE TABLE `designations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `designations`
--

INSERT INTO `designations` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Managing Director', '2025-05-08 04:18:12', '2025-10-20 07:30:28'),
(3, 'Executive Director', '2025-10-20 07:30:48', '2025-10-20 07:30:48'),
(4, 'Additional Director', '2025-10-20 07:31:03', '2025-10-20 07:31:03'),
(5, 'Deputy Director', '2025-10-20 07:31:20', '2025-10-20 07:31:20'),
(6, 'Assistant Director', '2025-10-20 07:31:33', '2025-10-20 07:31:33'),
(7, 'Senior Assistant Director', '2025-10-20 07:31:57', '2025-10-20 07:31:57'),
(8, 'Chairman', '2025-10-20 07:33:14', '2025-10-20 07:33:14'),
(9, 'Member', '2025-10-20 07:33:37', '2025-10-20 07:33:37'),
(10, 'Secretary', '2025-10-20 07:34:28', '2025-10-20 07:34:28'),
(11, 'Executive Chairman', '2025-10-20 07:34:40', '2025-10-20 07:34:40'),
(12, 'Senior Secretary', '2025-10-20 07:35:03', '2025-10-20 07:35:36'),
(13, 'Additional Secretary', '2025-10-20 07:36:30', '2025-10-20 07:36:30'),
(14, 'Director', '2025-10-20 07:37:14', '2025-10-20 07:37:14'),
(15, 'Former Additional Secretary', '2025-10-20 07:38:10', '2025-10-20 07:38:10'),
(16, 'Administrator', '2025-10-20 07:49:32', '2025-10-20 07:49:32'),
(17, 'Chairman & CEO', '2025-10-20 07:50:44', '2025-10-20 07:50:44'),
(18, 'Former Additional Secretary and Administrator', '2025-10-24 09:42:11', '2025-10-24 09:42:24'),
(19, 'Joint Director', '2025-10-24 10:45:37', '2025-10-24 10:45:37'),
(20, 'Admin Officer', '2025-10-24 11:33:29', '2025-10-24 11:33:29'),
(21, 'Receptionist', '2025-10-24 11:33:40', '2025-10-24 11:33:40'),
(22, 'Project Officer', '2025-10-24 11:34:40', '2025-10-24 11:34:40');

-- --------------------------------------------------------

--
-- Table structure for table `digital_marketing_growth_items`
--

CREATE TABLE `digital_marketing_growth_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `digital_marketing_page`
--

CREATE TABLE `digital_marketing_page` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `hero_title` varchar(255) DEFAULT NULL,
  `hero_description` text DEFAULT NULL,
  `hero_button_text` varchar(255) DEFAULT NULL,
  `intro_image` varchar(255) DEFAULT NULL,
  `intro_title` varchar(255) DEFAULT NULL,
  `intro_description` text DEFAULT NULL,
  `intro_button_text` varchar(255) DEFAULT NULL,
  `consultant_title` varchar(255) DEFAULT NULL,
  `consultant_description` text DEFAULT NULL,
  `consultant_button_text` varchar(255) DEFAULT NULL,
  `growth_title` varchar(255) DEFAULT NULL,
  `growth_description` text DEFAULT NULL,
  `solutions_subtitle` varchar(255) DEFAULT NULL,
  `solutions_title` varchar(255) DEFAULT NULL,
  `solutions_description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `digital_marketing_solutions`
--

CREATE TABLE `digital_marketing_solutions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `icon` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `downloads`
--

CREATE TABLE `downloads` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `pdf_file` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `downloads`
--

INSERT INTO `downloads` (`id`, `title`, `date`, `pdf_file`, `created_at`, `updated_at`) VALUES
(1, 'sdsad', '2025-10-08', 'uploads/downloads/1761820043_69033d8b9aff1.pdf', '2025-10-30 04:27:23', '2025-10-30 04:27:23');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `time` varchar(255) DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `title`, `slug`, `start_date`, `end_date`, `time`, `description`, `status`, `image`, `created_at`, `updated_at`) VALUES
(3, 'hghfghgtrttr', 'hghfghgtrttr', '2025-11-30', NULL, NULL, '<ul><li>trytyt</li></ul>', 1, 'public/uploads/events/1762750444_69116fec41a7a.png', '2025-11-09 22:54:04', '2025-11-12 22:41:52'),
(4, 'gfhghfgh', 'gfhghfgh', '2025-11-14', '2025-11-15', '10.00 Am - 4.00Pm', '<ul><li>fghfgh</li><li>hfdhfghfgjhn</li></ul><p>dfgdfgdfgfdgfdgfdgfdg</p>', 1, 'public/uploads/events/1763008876_6915616c9201a.png', '2025-11-12 22:41:23', '2025-11-12 22:41:23');

-- --------------------------------------------------------

--
-- Table structure for table `extra_pages`
--

CREATE TABLE `extra_pages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `privacy_policy` longtext DEFAULT NULL,
  `term_condition` longtext DEFAULT NULL,
  `return_policy` longtext DEFAULT NULL,
  `warranty_policy` text DEFAULT NULL,
  `payment_term` text DEFAULT NULL,
  `delivery_policy` text DEFAULT NULL,
  `refund_policy` text DEFAULT NULL,
  `faq` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `extra_pages`
--

INSERT INTO `extra_pages` (`id`, `privacy_policy`, `term_condition`, `return_policy`, `warranty_policy`, `payment_term`, `delivery_policy`, `refund_policy`, `faq`, `created_at`, `updated_at`) VALUES
(1, '<p>#</p>', '<p>#</p>', '<p>#</p>', '<p>#</p>', '<p>#</p>', '<p>#</p>', '<p>#</p>', NULL, '2025-10-13 09:09:35', '2025-10-13 09:09:35');

-- --------------------------------------------------------

--
-- Table structure for table `facebook_ads_campaigns`
--

CREATE TABLE `facebook_ads_campaigns` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `facebook_ads_faqs`
--

CREATE TABLE `facebook_ads_faqs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `question` varchar(255) NOT NULL,
  `answer` text NOT NULL,
  `order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `facebook_ads_features`
--

CREATE TABLE `facebook_ads_features` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `icon_name` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `facebook_ads_page`
--

CREATE TABLE `facebook_ads_page` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `hero_title` varchar(255) DEFAULT NULL,
  `hero_description` text DEFAULT NULL,
  `hero_button_text` varchar(255) DEFAULT 'Get Free Consultation',
  `hero_image` varchar(255) DEFAULT NULL,
  `stats_partner_logo` varchar(255) DEFAULT NULL,
  `stats_partner_title` varchar(255) NOT NULL DEFAULT 'Meta Business Partner',
  `stats_exp_number` varchar(255) NOT NULL DEFAULT '10+ Years',
  `stats_exp_title` varchar(255) NOT NULL DEFAULT 'Facebook Ads Experience',
  `stats_client_number` varchar(255) NOT NULL DEFAULT '400+',
  `stats_client_title` varchar(255) NOT NULL DEFAULT 'Client Successes',
  `stats_revenue_number` varchar(255) NOT NULL DEFAULT '$80 M',
  `stats_revenue_title` varchar(255) NOT NULL DEFAULT 'Revenue Generated',
  `campaign_section_title` varchar(255) NOT NULL DEFAULT 'Which Type Campaign We Manage?',
  `campaign_image` varchar(255) DEFAULT NULL,
  `pricing_section_title` varchar(255) NOT NULL DEFAULT 'CHOOSE YOUR PACKAGE',
  `faq_section_title` varchar(255) NOT NULL DEFAULT 'Facebook Advertising FAQs',
  `cta_title` varchar(255) NOT NULL DEFAULT 'Unleash The Full Potential Of Your Facebook Marketing Campaigns',
  `cta_button_text` varchar(255) NOT NULL DEFAULT 'GET FREE PROPOSAL',
  `cta_button_link` varchar(255) NOT NULL DEFAULT '#',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `facebook_ads_pricing_categories`
--

CREATE TABLE `facebook_ads_pricing_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `facebook_ads_pricing_packages`
--

CREATE TABLE `facebook_ads_pricing_packages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `price` varchar(255) NOT NULL,
  `price_suffix` varchar(255) DEFAULT NULL,
  `features` text NOT NULL,
  `button_text` varchar(255) NOT NULL DEFAULT 'Order Now',
  `button_link` varchar(255) NOT NULL DEFAULT '#',
  `order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `facebook_more_services`
--

CREATE TABLE `facebook_more_services` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `icon_name` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `link_text` varchar(255) NOT NULL DEFAULT 'Buy Now &rarr;',
  `link_url` varchar(255) NOT NULL DEFAULT '#',
  `order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `facebook_page`
--

CREATE TABLE `facebook_page` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `header_title` varchar(255) NOT NULL DEFAULT 'FACEBOOK PAGE SETUP',
  `intro_title` varchar(255) DEFAULT NULL,
  `intro_description` text DEFAULT NULL,
  `intro_image` varchar(255) DEFAULT NULL,
  `pricing_title` varchar(255) NOT NULL DEFAULT 'Pricing Table',
  `pricing_description` text DEFAULT NULL,
  `more_services_title` varchar(255) NOT NULL DEFAULT 'More Facebook Services',
  `more_services_description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `facebook_pricing_packages`
--

CREATE TABLE `facebook_pricing_packages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `price` varchar(255) NOT NULL,
  `features` text NOT NULL,
  `button_text` varchar(255) NOT NULL DEFAULT 'Order Now',
  `button_link` varchar(255) NOT NULL DEFAULT '#',
  `order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `featured_categories`
--

CREATE TABLE `featured_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `key` varchar(255) NOT NULL,
  `value` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `featured_categories`
--

INSERT INTO `featured_categories` (`id`, `key`, `value`, `created_at`, `updated_at`) VALUES
(1, 'first_row_category', '\"new\"', '2025-09-16 09:33:18', '2025-09-16 09:33:18'),
(2, 'second_row_category', '\"discount\"', '2025-09-16 09:33:18', '2025-09-16 09:33:18'),
(3, 'first_row_status', '1', '2025-09-16 11:26:25', '2025-09-16 11:27:02'),
(4, 'second_row_status', '1', '2025-09-16 11:26:25', '2025-09-16 11:26:25');

-- --------------------------------------------------------

--
-- Table structure for table `galleries`
--

CREATE TABLE `galleries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `short_description` varchar(255) DEFAULT NULL,
  `type` enum('image','video') NOT NULL DEFAULT 'image',
  `image_file` varchar(255) DEFAULT NULL,
  `youtube_link` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `galleries`
--

INSERT INTO `galleries` (`id`, `short_description`, `type`, `image_file`, `youtube_link`, `created_at`, `updated_at`) VALUES
(8, 'Contract Signing Ceremony with DTCA', 'image', 'public/uploads/gallery/1761767362_69026fc2ca0d1.jpeg', NULL, '2025-10-29 19:49:22', '2025-10-29 19:49:22'),
(9, 'Contract Signing Ceremony with DTCA', 'image', 'public/uploads/gallery/1761767375_69026fcfa2202.jpeg', NULL, '2025-10-29 19:49:35', '2025-10-29 19:49:35'),
(10, 'Contract Signing Ceremony with DTCA', 'image', 'public/uploads/gallery/1761767383_69026fd7dcaf8.jpeg', NULL, '2025-10-29 19:49:44', '2025-10-29 19:49:44'),
(11, 'Contract Signing Ceremony with DTCA', 'image', 'public/uploads/gallery/1761767395_69026fe3869cd.jpeg', NULL, '2025-10-29 19:49:55', '2025-10-29 19:49:55'),
(12, 'Closing Ceremony of Training on Project Management', 'image', 'public/uploads/gallery/1761767404_69026feced9f3.jpg', NULL, '2025-10-29 19:50:05', '2025-11-02 11:47:40'),
(13, 'Bakhkhali', 'image', 'public/uploads/gallery/1762057511_6906dd27dbf70.jpeg', NULL, '2025-11-02 04:25:12', '2025-11-02 04:25:12'),
(14, 'Bakhkhali', 'image', 'public/uploads/gallery/1762057520_6906dd303c818.jpeg', NULL, '2025-11-02 04:25:20', '2025-11-02 04:25:20'),
(15, 'Chokhkhom', 'image', 'public/uploads/gallery/1762057549_6906dd4d1e659.jpeg', NULL, '2025-11-02 04:25:49', '2025-11-02 04:25:49'),
(16, 'Chokhkhom', 'image', 'public/uploads/gallery/1762057555_6906dd539f5af.jpeg', NULL, '2025-11-02 04:25:55', '2025-11-02 04:25:55'),
(17, 'Jhankar', 'image', 'public/uploads/gallery/1762057717_6906ddf56dba4.jpeg', NULL, '2025-11-02 04:28:37', '2025-11-02 04:28:37'),
(18, 'Jhankar', 'image', 'public/uploads/gallery/1762057726_6906ddfed1725.jpeg', NULL, '2025-11-02 04:28:47', '2025-11-02 04:28:47'),
(19, 'Maini', 'image', 'public/uploads/gallery/1762057742_6906de0ef01ed.jpeg', NULL, '2025-11-02 04:29:03', '2025-11-02 04:29:03'),
(20, 'Maini', 'image', 'public/uploads/gallery/1762057750_6906de1695252.jpeg', NULL, '2025-11-02 04:29:10', '2025-11-02 04:29:10'),
(21, 'Matamohuri', 'image', 'public/uploads/gallery/1762057892_6906dea463400.jpeg', NULL, '2025-11-02 04:31:32', '2025-11-02 04:31:32'),
(22, 'Matamohuri', 'image', 'public/uploads/gallery/1762057907_6906deb398ee0.jpeg', NULL, '2025-11-02 04:31:47', '2025-11-02 04:31:47'),
(23, 'Rowangchori', 'image', 'public/uploads/gallery/1762057951_6906dedf6c4af.jpeg', NULL, '2025-11-02 04:32:31', '2025-11-02 04:32:31'),
(24, 'Rowangchori', 'image', 'public/uploads/gallery/1762057959_6906dee700ccd.jpeg', NULL, '2025-11-02 04:32:39', '2025-11-02 04:32:39'),
(25, 'Shilak', 'image', 'public/uploads/gallery/1762057974_6906def6e9852.jpeg', NULL, '2025-11-02 04:32:55', '2025-11-02 04:32:55'),
(26, 'Shilak', 'image', 'public/uploads/gallery/1762057982_6906defe1accd.jpeg', NULL, '2025-11-02 04:33:02', '2025-11-02 04:33:02'),
(27, 'Cumilla', 'image', 'public/uploads/gallery/1762058132_6906df9472701.webp', NULL, '2025-11-02 04:35:32', '2025-11-02 04:35:32'),
(28, 'Cumilla', 'image', 'public/uploads/gallery/1762058141_6906df9da77c6.webp', NULL, '2025-11-02 04:35:41', '2025-11-02 04:35:41'),
(29, 'Cumilla', 'image', 'public/uploads/gallery/1762058264_6906e0182b2c5.webp', NULL, '2025-11-02 04:37:44', '2025-11-02 04:37:44'),
(30, 'Cumilla', 'image', 'public/uploads/gallery/1762058277_6906e025cc1ca.webp', NULL, '2025-11-02 04:37:58', '2025-11-02 04:37:58'),
(31, 'Cumilla', 'image', 'public/uploads/gallery/1762058287_6906e02f5f4a1.webp', NULL, '2025-11-02 04:38:07', '2025-11-02 04:38:07'),
(32, 'Cumilla', 'image', 'public/uploads/gallery/1762058297_6906e039a2236.webp', NULL, '2025-11-02 04:38:17', '2025-11-02 04:38:17'),
(33, 'Cumilla', 'image', 'public/uploads/gallery/1762058307_6906e04354f9b.webp', NULL, '2025-11-02 04:38:27', '2025-11-02 04:38:27'),
(34, 'Cumilla', 'image', 'public/uploads/gallery/1762058318_6906e04e8ca3b.webp', NULL, '2025-11-02 04:38:38', '2025-11-02 04:38:38'),
(35, 'Cumilla', 'image', 'public/uploads/gallery/1762058327_6906e057a467a.webp', NULL, '2025-11-02 04:38:47', '2025-11-02 04:38:47'),
(36, 'Cumilla', 'image', 'public/uploads/gallery/1762058334_6906e05e87a58.webp', NULL, '2025-11-02 04:38:55', '2025-11-02 04:38:55'),
(43, 'BRTA Driver Training, Testing and Multipurpose Center, Mymenshing', 'image', 'public/uploads/gallery/1762058425_6906e0b910b22.webp', NULL, '2025-11-02 04:40:25', '2025-11-02 12:03:47'),
(44, 'BRTA Driver Training, Testing and Multipurpose Center, Mymenshing', 'image', 'public/uploads/gallery/1762058440_6906e0c81f3a2.webp', NULL, '2025-11-02 04:40:40', '2025-11-02 12:03:40'),
(46, 'BRTA Driver Training, Testing and Multipurpose Center, Mymenshing', 'image', 'public/uploads/gallery/1762058455_6906e0d7845d5.webp', NULL, '2025-11-02 04:40:56', '2025-11-02 12:03:28'),
(49, 'BRTA Driver Training, Testing and Multipurpose Center, Noakhali', 'image', 'public/uploads/gallery/1762058495_6906e0ff7eb1f.webp', NULL, '2025-11-02 04:41:35', '2025-11-02 12:00:39'),
(50, 'BRTA Driver Training, Testing and Multipurpose Center, Noakhali', 'image', 'public/uploads/gallery/1762058503_6906e10706389.webp', NULL, '2025-11-02 04:41:43', '2025-11-02 12:00:31'),
(52, 'BRTA Driver Training, Testing and Multipurpose Center, Noakhali', 'image', 'public/uploads/gallery/1762058517_6906e115b8ba3.webp', NULL, '2025-11-02 04:41:58', '2025-11-02 12:00:24');

-- --------------------------------------------------------

--
-- Table structure for table `graphic_design_checklists`
--

CREATE TABLE `graphic_design_checklists` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `graphic_design_page`
--

CREATE TABLE `graphic_design_page` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `hero_title` varchar(255) DEFAULT NULL,
  `hero_description` text DEFAULT NULL,
  `hero_button_text` varchar(255) DEFAULT NULL,
  `intro_image` varchar(255) DEFAULT NULL,
  `intro_title` varchar(255) DEFAULT NULL,
  `intro_description` text DEFAULT NULL,
  `intro_button_text` varchar(255) DEFAULT NULL,
  `consultant_title` varchar(255) DEFAULT NULL,
  `consultant_description` text DEFAULT NULL,
  `consultant_button_text` varchar(255) DEFAULT NULL,
  `checklist_title` varchar(255) DEFAULT NULL,
  `checklist_description` text DEFAULT NULL,
  `solutions_subtitle` varchar(255) DEFAULT NULL,
  `solutions_title` varchar(255) DEFAULT NULL,
  `solutions_description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `graphic_design_solutions`
--

CREATE TABLE `graphic_design_solutions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `icon` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hero_sections`
--

CREATE TABLE `hero_sections` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `left_image` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`left_image`)),
  `top_right_image` varchar(255) NOT NULL,
  `bottom_right_image` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hero_sections`
--

INSERT INTO `hero_sections` (`id`, `left_image`, `top_right_image`, `bottom_right_image`, `created_at`, `updated_at`) VALUES
(1, '[\"hero\\/8755ab0a-7786-4aea-b197-2cfd9ef767ae.webp\",\"hero\\/04d8dfdf-7328-40c4-9a59-ffd504e0f7b2.webp\"]', 'hero/c53a01c7-d14e-43b1-9a6e-fdda5f33d0ff.webp', 'hero/59e746b5-a022-4060-9a93-9c9b9261595a.webp', '2025-09-30 04:22:56', '2025-10-12 22:34:37');

-- --------------------------------------------------------

--
-- Table structure for table `iifc_strengths`
--

CREATE TABLE `iifc_strengths` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `projects` int(11) NOT NULL DEFAULT 0,
  `products` int(11) NOT NULL DEFAULT 0,
  `experts` int(11) NOT NULL DEFAULT 0,
  `countries` int(11) NOT NULL DEFAULT 0,
  `happy_clients` int(11) NOT NULL DEFAULT 0,
  `yrs_experienced` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `iifc_strengths`
--

INSERT INTO `iifc_strengths` (`id`, `projects`, `products`, `experts`, `countries`, `happy_clients`, `yrs_experienced`, `created_at`, `updated_at`) VALUES
(1, 140, 40, 30, 20, 9, 9, NULL, '2025-11-15 00:41:39');

-- --------------------------------------------------------

--
-- Table structure for table `important_links`
--

CREATE TABLE `important_links` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `link` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `important_links`
--

INSERT INTO `important_links` (`id`, `title`, `link`, `created_at`, `updated_at`) VALUES
(1, 'Economic Relations Division (ERD)', 'https://erd.gov.bd/', '2025-10-28 23:21:14', '2025-10-28 23:21:14'),
(3, 'Infrastructure Development Company Limited (IDCOL)', 'http://www.idcol.org/', '2025-10-28 23:22:41', '2025-10-30 06:00:46'),
(4, 'Bangladesh Infrastructure Finance Fund Limited (BIFFL)', 'https://www.biffl.org.bd/', '2025-10-28 23:23:10', '2025-10-30 06:00:27'),
(5, 'Public Private Partnership Authority', 'https://www.pppo.gov.bd/', '2025-10-28 23:23:33', '2025-10-30 06:04:33'),
(6, 'Bangladesh Investment Development Authority (BIDA)', 'https://www.investbangladesh.gov.bd/', '2025-10-28 23:23:51', '2025-10-30 06:01:42'),
(7, 'Bangladesh Export Processing Zones Authority (BEPZA)', 'https://bepza.gov.bd/', '2025-10-28 23:24:09', '2025-10-30 05:58:53'),
(8, 'Bangladesh Economic Zones Authority (BEZA)', 'https://beza.portal.gov.bd/', '2025-10-28 23:24:23', '2025-10-30 06:02:16');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_applicants`
--

CREATE TABLE `job_applicants` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `job_id` bigint(20) UNSIGNED NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone_number` varchar(255) NOT NULL,
  `qualification` text NOT NULL,
  `date_of_birth` date DEFAULT NULL,
  `educational_background` text DEFAULT NULL,
  `working_experience` text DEFAULT NULL,
  `total_year_of_experience` text DEFAULT NULL,
  `address` text DEFAULT NULL,
  `cv` varchar(255) NOT NULL,
  `additional_information` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `job_applicants`
--

INSERT INTO `job_applicants` (`id`, `job_id`, `full_name`, `email`, `phone_number`, `qualification`, `date_of_birth`, `educational_background`, `working_experience`, `total_year_of_experience`, `address`, `cv`, `additional_information`, `created_at`, `updated_at`) VALUES
(2, 1, 'Kamruzzaman kajol', 'kkajol428@gmail.com', '01646735100', 'rterter', '2017-01-28', 'terter', 'ertret', '5', 'Rajshahi', 'uploads/cvs/1762875865_691359d91c3c6.pdf', 'rtertre', '2025-11-11 09:44:25', '2025-11-11 09:44:25');

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `log_activities`
--

CREATE TABLE `log_activities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `subject` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `method` varchar(255) NOT NULL,
  `ip` varchar(255) NOT NULL,
  `agent` varchar(255) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `activity_time` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `log_activities`
--

INSERT INTO `log_activities` (`id`, `subject`, `url`, `method`, `ip`, `agent`, `user_id`, `activity_time`, `created_at`, `updated_at`) VALUES
(1255, 'LoginPage View', 'http://localhost/computer-laptop-sell', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 1, '02:17:27 pm', '2025-09-20 02:17:27', '2025-09-20 02:17:27'),
(1256, 'permissionView', 'http://localhost/computer-laptop-sell/permissions', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 1, '04:12:23 pm', '2025-09-20 04:12:23', '2025-09-20 04:12:23'),
(1257, 'permissionStore', 'http://localhost/computer-laptop-sell/permissions', 'POST', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 1, '04:13:41 pm', '2025-09-20 04:13:41', '2025-09-20 04:13:41'),
(1258, 'permissionView', 'http://localhost/computer-laptop-sell/permissions', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 1, '04:13:42 pm', '2025-09-20 04:13:42', '2025-09-20 04:13:42'),
(1259, 'permissionedit', 'http://localhost/computer-laptop-sell/permissions/197/edit', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 1, '04:13:48 pm', '2025-09-20 04:13:48', '2025-09-20 04:13:48'),
(1260, 'permissionUpdate', 'http://localhost/computer-laptop-sell/permissions/attribute', 'PUT', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 1, '04:15:55 pm', '2025-09-20 04:15:55', '2025-09-20 04:15:55'),
(1261, 'permissionView', 'http://localhost/computer-laptop-sell/permissions', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 1, '04:15:56 pm', '2025-09-20 04:15:56', '2025-09-20 04:15:56'),
(1262, 'role-list', 'http://localhost/computer-laptop-sell/roles', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 1, '04:16:32 pm', '2025-09-20 04:16:32', '2025-09-20 04:16:32'),
(1263, 'panelSettingView', 'http://localhost/computer-laptop-sell/systemInformation', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 1, '04:16:47 pm', '2025-09-20 04:16:47', '2025-09-20 04:16:47'),
(1264, 'permissionView', 'http://localhost/computer-laptop-sell/permissions', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 1, '04:16:52 pm', '2025-09-20 04:16:52', '2025-09-20 04:16:52'),
(1265, 'role-list', 'http://localhost/computer-laptop-sell/roles', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 1, '04:16:55 pm', '2025-09-20 04:16:55', '2025-09-20 04:16:55'),
(1266, 'role-edit', 'http://localhost/computer-laptop-sell/roles/1/edit', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 1, '04:16:58 pm', '2025-09-20 04:16:58', '2025-09-20 04:16:58'),
(1267, 'role-update', 'http://localhost/computer-laptop-sell/roles/1', 'PUT', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 1, '04:17:05 pm', '2025-09-20 04:17:05', '2025-09-20 04:17:05'),
(1268, 'role-list', 'http://localhost/computer-laptop-sell/roles', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 1, '04:17:07 pm', '2025-09-20 04:17:07', '2025-09-20 04:17:07'),
(1269, 'LoginPage View', 'http://localhost/computer-laptop-sell', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 1, '11:20:46 am', '2025-09-20 23:20:46', '2025-09-20 23:20:46'),
(1270, 'LoginPage View', 'http://localhost/computer-laptop-sell', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 1, '12:51:46 pm', '2025-09-25 00:51:47', '2025-09-25 00:51:47'),
(1271, 'panelSettingView', 'http://localhost/computer-laptop-sell/systemInformation', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 1, '01:11:04 pm', '2025-09-25 01:11:04', '2025-09-25 01:11:04'),
(1272, 'panelSettingUpdate', 'http://localhost/computer-laptop-sell/systemInformation/1/edit', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 1, '01:11:13 pm', '2025-09-25 01:11:13', '2025-09-25 01:11:13'),
(1273, 'panelSettingUpdate', 'http://localhost/computer-laptop-sell/systemInformation/1', 'PUT', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 1, '01:11:45 pm', '2025-09-25 01:11:45', '2025-09-25 01:11:45'),
(1274, 'panelSettingView', 'http://localhost/computer-laptop-sell/systemInformation', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 1, '01:11:54 pm', '2025-09-25 01:11:54', '2025-09-25 01:11:54'),
(1275, 'panelSettingView', 'http://localhost/computer-laptop-sell/systemInformation', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 1, '01:14:06 pm', '2025-09-25 01:14:06', '2025-09-25 01:14:06'),
(1276, 'panelSettingView', 'http://localhost/computer-laptop-sell/systemInformation', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 1, '01:21:27 pm', '2025-09-25 01:21:27', '2025-09-25 01:21:27'),
(1277, 'panelSettingAdd', 'http://localhost/computer-laptop-sell/systemInformation/create', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 1, '01:59:05 pm', '2025-09-25 01:59:05', '2025-09-25 01:59:05'),
(1278, 'panelSettingView', 'http://localhost/computer-laptop-sell/systemInformation', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 1, '02:01:16 pm', '2025-09-25 02:01:16', '2025-09-25 02:01:16'),
(1279, 'panelSettingUpdate', 'http://localhost/computer-laptop-sell/systemInformation/1/edit', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 1, '02:01:25 pm', '2025-09-25 02:01:25', '2025-09-25 02:01:25'),
(1280, 'panelSettingUpdate', 'http://localhost/computer-laptop-sell/systemInformation/1', 'PUT', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 1, '02:12:33 pm', '2025-09-25 02:12:33', '2025-09-25 02:12:33'),
(1281, 'panelSettingView', 'http://localhost/computer-laptop-sell/systemInformation', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 1, '02:12:36 pm', '2025-09-25 02:12:36', '2025-09-25 02:12:36'),
(1282, 'panelSettingUpdate', 'http://localhost/computer-laptop-sell/systemInformation/1/edit', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 1, '02:12:43 pm', '2025-09-25 02:12:43', '2025-09-25 02:12:43'),
(1283, 'panelSettingUpdate', 'http://localhost/computer-laptop-sell/systemInformation/1', 'PUT', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 1, '02:12:57 pm', '2025-09-25 02:12:57', '2025-09-25 02:12:57'),
(1284, 'panelSettingView', 'http://localhost/computer-laptop-sell/systemInformation', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 1, '02:12:57 pm', '2025-09-25 02:12:57', '2025-09-25 02:12:57'),
(1285, 'panelSettingUpdate', 'http://localhost/computer-laptop-sell/systemInformation/1/edit', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 1, '02:13:03 pm', '2025-09-25 02:13:03', '2025-09-25 02:13:03'),
(1286, 'panelSettingUpdate', 'http://localhost/computer-laptop-sell/systemInformation/1/edit', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 1, '02:13:34 pm', '2025-09-25 02:13:34', '2025-09-25 02:13:34'),
(1287, 'panelSettingUpdate', 'http://localhost/computer-laptop-sell/systemInformation/1', 'PUT', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 1, '02:13:46 pm', '2025-09-25 02:13:46', '2025-09-25 02:13:46'),
(1288, 'panelSettingView', 'http://localhost/computer-laptop-sell/systemInformation', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 1, '02:13:46 pm', '2025-09-25 02:13:46', '2025-09-25 02:13:46'),
(1289, 'panelSettingView', 'http://localhost/computer-laptop-sell/systemInformation', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 1, '02:17:02 pm', '2025-09-25 02:17:02', '2025-09-25 02:17:02'),
(1290, 'panelSettingView', 'http://localhost/computer-laptop-sell/systemInformation', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 1, '02:22:13 pm', '2025-09-25 02:22:13', '2025-09-25 02:22:13'),
(1291, 'LoginPage View', 'http://localhost/computer-laptop-sell', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 1, '02:22:23 pm', '2025-09-25 02:22:23', '2025-09-25 02:22:23'),
(1292, 'LoginPage View', 'http://localhost/computer-laptop-sell', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 1, '02:22:26 pm', '2025-09-25 02:22:26', '2025-09-25 02:22:26'),
(1293, 'LoginPage View', 'http://localhost/computer-laptop-sell', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 1, '02:23:06 pm', '2025-09-25 02:23:06', '2025-09-25 02:23:06'),
(1294, 'LoginPage View', 'http://localhost/computer-laptop-sell', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 1, '02:23:37 pm', '2025-09-25 02:23:37', '2025-09-25 02:23:37'),
(1295, 'LoginPage View', 'http://localhost/computer-laptop-sell', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 1, '02:23:56 pm', '2025-09-25 02:23:56', '2025-09-25 02:23:56'),
(1296, 'LoginPage View', 'http://localhost/computer-laptop-sell', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 1, '02:24:30 pm', '2025-09-25 02:24:30', '2025-09-25 02:24:30'),
(1297, 'LoginPage View', 'http://localhost/computer-laptop-sell', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 1, '07:23:47 pm', '2025-09-25 07:23:47', '2025-09-25 07:23:47'),
(1298, 'LoginPage View', 'http://localhost/computer-laptop-sell', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 1, '08:29:39 pm', '2025-09-25 08:29:39', '2025-09-25 08:29:39'),
(1299, 'LoginPage View', 'http://localhost/computer-laptop-sell', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 1, '03:14:37 pm', '2025-09-27 03:14:37', '2025-09-27 03:14:37'),
(1300, 'LoginPage View', 'http://localhost/computer-laptop-sell', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 1, '10:36:32 am', '2025-09-27 22:36:32', '2025-09-27 22:36:32'),
(1301, 'LoginPage View', 'http://localhost/computer-laptop-sell', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 1, '03:56:37 pm', '2025-09-28 03:56:37', '2025-09-28 03:56:37'),
(1302, 'LoginPage View', 'http://localhost/computer-laptop-sell', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 1, '08:27:50 pm', '2025-09-28 08:27:51', '2025-09-28 08:27:51'),
(1303, 'LoginPage View', 'http://localhost/computer-laptop-sell', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 1, '11:32:06 am', '2025-09-28 23:32:07', '2025-09-28 23:32:07'),
(1304, 'LoginPage View', 'http://localhost/computer-laptop-sell', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 1, '03:41:26 pm', '2025-09-30 03:41:26', '2025-09-30 03:41:26'),
(1305, 'LoginPage View', 'http://localhost/computer-laptop-sell', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 1, '10:21:13 pm', '2025-10-01 10:21:13', '2025-10-01 10:21:13'),
(1306, 'LoginPage View', 'http://localhost/computer-laptop-sell', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 1, '08:58:18 am', '2025-10-01 20:58:18', '2025-10-01 20:58:18'),
(1307, 'LoginPage View', 'http://localhost/computer-laptop-sell', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 1, '08:58:18 am', '2025-10-01 20:58:18', '2025-10-01 20:58:18'),
(1308, 'LoginPage View', 'http://localhost/computer-laptop-sell', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 1, '07:50:30 pm', '2025-10-02 07:50:30', '2025-10-02 07:50:30'),
(1309, 'LoginPage View', 'http://localhost/computer-laptop-sell', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 1, '10:49:31 am', '2025-10-02 22:49:31', '2025-10-02 22:49:31'),
(1310, 'LoginPage View', 'http://localhost/computer-laptop-sell', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 1, '11:20:23 am', '2025-10-03 23:20:24', '2025-10-03 23:20:24'),
(1311, 'LoginPage View', 'http://localhost/computer-laptop-sell', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 1, '04:58:37 pm', '2025-10-04 04:58:37', '2025-10-04 04:58:37'),
(1312, 'LoginPage View', 'http://localhost/computer-laptop-sell', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 1, '08:18:56 pm', '2025-10-04 08:18:56', '2025-10-04 08:18:56'),
(1313, 'LoginPage View', 'http://localhost/computer-laptop-sell', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 1, '08:18:56 pm', '2025-10-04 08:18:56', '2025-10-04 08:18:56'),
(1314, 'panelSettingView', 'http://localhost/computer-laptop-sell/systemInformation', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 1, '09:03:27 pm', '2025-10-04 09:03:27', '2025-10-04 09:03:27'),
(1315, 'panelSettingUpdate', 'http://localhost/computer-laptop-sell/systemInformation/1/edit', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 1, '09:03:37 pm', '2025-10-04 09:03:37', '2025-10-04 09:03:37'),
(1316, 'panelSettingUpdate', 'http://localhost/computer-laptop-sell/systemInformation/1/edit', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 1, '09:03:38 pm', '2025-10-04 09:03:38', '2025-10-04 09:03:38'),
(1317, 'LoginPage View', 'http://localhost/computer-laptop-sell', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 1, '11:02:27 am', '2025-10-08 23:02:28', '2025-10-08 23:02:28'),
(1318, 'LoginPage View', 'http://localhost/computer-laptop-sell', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 1, '11:39:26 am', '2025-10-08 23:39:26', '2025-10-08 23:39:26'),
(1319, 'LoginPage View', 'http://localhost/computer-laptop-sell', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 1, '10:33:10 pm', '2025-10-09 10:33:11', '2025-10-09 10:33:11'),
(1320, 'LoginPage View', 'http://localhost/computer-laptop-sell', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 1, '10:57:13 am', '2025-10-09 22:57:13', '2025-10-09 22:57:13'),
(1321, 'user page view', 'http://localhost/computer-laptop-sell/users', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 1, '02:23:41 pm', '2025-10-10 02:23:41', '2025-10-10 02:23:41'),
(1322, 'role-update', 'http://localhost/computer-laptop-sell/roles/1', 'PUT', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 1, '04:21:19 pm', '2025-10-10 04:21:19', '2025-10-10 04:21:19'),
(1323, 'role-update', 'http://localhost/computer-laptop-sell/roles/1', 'PUT', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 1, '04:26:04 pm', '2025-10-10 04:26:04', '2025-10-10 04:26:04'),
(1324, 'role-update', 'http://localhost/computer-laptop-sell/roles/2', 'PUT', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 1, '04:26:34 pm', '2025-10-10 04:26:34', '2025-10-10 04:26:34'),
(1325, 'LoginPage View', 'http://localhost/computer-laptop-sell', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 1, '10:23:18 pm', '2025-10-10 10:23:18', '2025-10-10 10:23:18'),
(1326, 'LoginPage View', 'http://localhost/computer-laptop-sell', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 1, '01:42:57 pm', '2025-10-12 01:42:58', '2025-10-12 01:42:58'),
(1327, 'LoginPage View', 'http://localhost/computer-laptop-sell', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 1, '09:51:00 am', '2025-10-12 21:51:00', '2025-10-12 21:51:00'),
(1328, 'LoginPage View', 'http://localhost/computer-laptop-sell', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 1, '05:08:08 pm', '2025-10-13 05:08:08', '2025-10-13 05:08:08'),
(1329, 'LoginPage View', 'http://localhost/computer-laptop-sell', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 1, '05:13:23 pm', '2025-10-13 05:13:23', '2025-10-13 05:13:23'),
(1330, 'LoginPage View', 'http://localhost/computer-laptop-sell', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 1, '06:05:39 pm', '2025-10-13 06:05:39', '2025-10-13 06:05:39'),
(1331, 'LoginPage View', 'http://localhost/computer-laptop-sell', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 1, '07:03:57 pm', '2025-10-13 07:03:57', '2025-10-13 07:03:57'),
(1332, 'LoginPage View', 'http://localhost/computer-laptop-sell', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 1, '10:32:19 am', '2025-10-14 22:32:19', '2025-10-14 22:32:19'),
(1333, 'LoginPage View', 'http://localhost/iifc_admin_api_panel', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 1, '05:15:11 pm', '2025-10-19 05:15:11', '2025-10-19 05:15:11'),
(1334, 'LoginPage View', 'http://localhost/iifc_admin_api_panel', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 1, '05:27:20 pm', '2025-10-19 05:27:20', '2025-10-19 05:27:20'),
(1335, 'LoginPage View', 'http://localhost/iifc_admin_api_panel', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 1, '12:34:34 pm', '2025-10-20 00:34:36', '2025-10-20 00:34:36'),
(1336, 'LoginPage View', 'http://localhost/iifc_admin_api_panel', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 1, '01:02:14 pm', '2025-10-20 01:02:15', '2025-10-20 01:02:15'),
(1337, 'LoginPage View', 'http://localhost/iifc_admin_api_panel', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 1, '01:21:40 pm', '2025-10-20 01:21:40', '2025-10-20 01:21:40'),
(1338, 'user edit', 'http://localhost/iifc_admin_api_panel/users/3857/edit', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 1, '01:58:46 pm', '2025-10-20 01:58:46', '2025-10-20 01:58:46'),
(1339, 'user edit', 'http://localhost/iifc_admin_api_panel/users/3857/edit', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 1, '01:58:49 pm', '2025-10-20 01:58:49', '2025-10-20 01:58:49'),
(1340, 'role-update', 'http://localhost/iifc_admin_api_panel/roles/1', 'PUT', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 1, '03:10:30 pm', '2025-10-20 03:10:30', '2025-10-20 03:10:30'),
(1341, 'LoginPage View', 'http://localhost/iifc_admin_api_panel', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 1, '05:33:41 pm', '2025-10-20 05:33:42', '2025-10-20 05:33:42'),
(1342, 'LoginPage View', 'http://localhost/iifc_admin_api_panel', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 1, '05:51:29 pm', '2025-10-20 05:51:29', '2025-10-20 05:51:29'),
(1343, 'LoginPage View', 'http://localhost/iifc_admin_api_panel', 'GET', '::1', 'Mozilla/5.0 (Linux; Android 7.0; SM-G950U Build/NRD90M) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Mobile Safari/537.36', 1, '05:55:14 pm', '2025-10-20 05:55:14', '2025-10-20 05:55:14'),
(1344, 'role-update', 'http://localhost/iifc_admin_api_panel/roles/1', 'PUT', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 1, '06:35:34 pm', '2025-10-20 06:35:34', '2025-10-20 06:35:34'),
(1345, 'role-update', 'http://localhost/iifc_admin_api_panel/roles/1', 'PUT', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 1, '06:37:05 pm', '2025-10-20 06:37:05', '2025-10-20 06:37:05'),
(1346, 'LoginPage View', 'http://localhost/iifc_admin_api_panel', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 1, '06:51:19 pm', '2025-10-20 06:51:19', '2025-10-20 06:51:19'),
(1347, 'LoginPage View', 'http://localhost/iifc_admin_api_panel', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 1, '10:58:04 am', '2025-10-20 22:58:04', '2025-10-20 22:58:04'),
(1348, 'role-update', 'http://localhost/iifc_admin_api_panel/roles/1', 'PUT', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 1, '11:02:56 am', '2025-10-20 23:02:56', '2025-10-20 23:02:56'),
(1349, 'role-update', 'http://localhost/iifc_admin_api_panel/roles/1', 'PUT', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 1, '02:44:11 pm', '2025-10-21 02:44:11', '2025-10-21 02:44:11'),
(1350, 'role-update', 'http://localhost/iifc_admin_api_panel/roles/1', 'PUT', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 1, '02:56:59 pm', '2025-10-21 02:56:59', '2025-10-21 02:56:59'),
(1351, 'LoginPage View', 'http://localhost/iifc_admin_api_panel', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 1, '03:38:29 pm', '2025-10-21 03:38:30', '2025-10-21 03:38:30'),
(1352, 'role-update', 'http://localhost/iifc_admin_api_panel/roles/1', 'PUT', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 1, '03:46:42 pm', '2025-10-21 03:46:42', '2025-10-21 03:46:42'),
(1353, 'role-update', 'http://localhost/iifc_admin_api_panel/roles/1', 'PUT', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 1, '05:32:56 pm', '2025-10-21 05:32:56', '2025-10-21 05:32:56'),
(1354, 'role-update', 'http://localhost/iifc_admin_api_panel/roles/1', 'PUT', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 1, '06:31:49 pm', '2025-10-21 06:31:49', '2025-10-21 06:31:49'),
(1355, 'role-update', 'http://localhost/iifc_admin_api_panel/roles/1', 'PUT', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 1, '07:11:58 pm', '2025-10-21 07:11:58', '2025-10-21 07:11:58'),
(1356, 'role-update', 'http://localhost/iifc_admin_api_panel/roles/1', 'PUT', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 1, '08:36:27 pm', '2025-10-21 08:36:27', '2025-10-21 08:36:27'),
(1357, 'LoginPage View', 'http://localhost/iifc_admin_api_panel', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 1, '10:18:30 pm', '2025-10-21 10:18:30', '2025-10-21 10:18:30'),
(1358, 'role-update', 'http://localhost/iifc_admin_api_panel/roles/1', 'PUT', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 1, '12:10:51 am', '2025-10-21 12:10:52', '2025-10-21 12:10:52'),
(1359, 'LoginPage View', 'http://localhost/iifc_admin_api_panel', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 1, '12:49:34 am', '2025-10-21 12:49:34', '2025-10-21 12:49:34'),
(1360, 'LoginPage View', 'http://localhost/iifc_admin_api_panel', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 1, '07:52:21 am', '2025-10-21 19:52:21', '2025-10-21 19:52:21'),
(1361, 'LoginPage View', 'http://localhost/iifc_admin_api_panel', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 1, '08:26:31 am', '2025-10-21 20:26:31', '2025-10-21 20:26:31'),
(1362, 'LoginPage View', 'http://localhost/iifc_admin_api_panel', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 1, '08:45:06 am', '2025-10-21 20:45:06', '2025-10-21 20:45:06'),
(1363, 'LoginPage View', 'http://localhost/iifc_admin_api_panel', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 1, '08:45:27 am', '2025-10-21 20:45:27', '2025-10-21 20:45:27'),
(1364, 'LoginPage View', 'http://localhost/iifc_admin_api_panel', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 1, '11:59:58 am', '2025-10-21 23:59:58', '2025-10-21 23:59:58'),
(1365, 'LoginPage View', 'http://localhost/iifc_admin_api_panel', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 1, '02:15:42 pm', '2025-10-22 02:15:42', '2025-10-22 02:15:42'),
(1366, 'LoginPage View', 'http://localhost/iifc_admin_api_panel', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 1, '10:25:01 pm', '2025-10-22 10:25:01', '2025-10-22 10:25:01'),
(1367, 'LoginPage View', 'http://localhost/iifc_admin_api_panel', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 1, '10:28:03 am', '2025-10-22 22:28:03', '2025-10-22 22:28:03'),
(1368, 'LoginPage View', 'http://localhost/iifc_admin_api_panel', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 1, '01:44:36 pm', '2025-10-23 01:44:36', '2025-10-23 01:44:36'),
(1369, 'LoginPage View', 'http://localhost/iifc_admin_api_panel', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 1, '05:29:38 pm', '2025-10-24 05:29:39', '2025-10-24 05:29:39'),
(1370, 'LoginPage View', 'https://iifcadmin.resnova.dev', 'GET', '103.213.236.43', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 1, '04:22:45 pm', '2025-10-25 10:22:45', '2025-10-25 10:22:45'),
(1371, 'LoginPage View', 'https://iifcadmin.resnova.dev', 'GET', '35.204.3.32', 'Scrapy/2.13.3 (+https://scrapy.org)', 1, '04:22:59 pm', '2025-10-25 10:22:59', '2025-10-25 10:22:59'),
(1372, 'LoginPage View', 'https://iifcadmin.resnova.dev', 'GET', '103.213.236.43', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 1, '05:11:30 pm', '2025-10-25 11:11:30', '2025-10-25 11:11:30'),
(1373, 'LoginPage View', 'http://iifcadmin.resnova.dev', 'GET', '169.150.203.200', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36', 1, '07:50:54 pm', '2025-10-25 13:50:54', '2025-10-25 13:50:54'),
(1374, 'LoginPage View', 'http://iifcadmin.resnova.dev', 'GET', '169.150.203.200', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36', 1, '07:50:55 pm', '2025-10-25 13:50:55', '2025-10-25 13:50:55'),
(1375, 'LoginPage View', 'http://iifcadmin.resnova.dev', 'GET', '169.150.203.200', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36', 1, '07:50:55 pm', '2025-10-25 13:50:55', '2025-10-25 13:50:55'),
(1376, 'LoginPage View', 'https://iifcadmin.resnova.dev', 'GET', '103.213.236.43', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 1, '09:57:05 pm', '2025-10-25 15:57:05', '2025-10-25 15:57:05'),
(1377, 'LoginPage View', 'http://iifcadmin.resnova.dev', 'GET', '159.203.61.180', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', 1, '03:00:59 am', '2025-10-25 21:00:59', '2025-10-25 21:00:59'),
(1378, 'LoginPage View', 'https://iifcadmin.resnova.dev', 'GET', '159.203.61.180', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', 1, '03:00:59 am', '2025-10-25 21:00:59', '2025-10-25 21:00:59'),
(1379, 'LoginPage View', 'https://iifcadmin.resnova.dev', 'GET', '103.87.249.118', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 1, '11:55:55 am', '2025-10-26 05:55:55', '2025-10-26 05:55:55'),
(1380, 'LoginPage View', 'https://iifcadmin.resnova.dev', 'GET', '115.127.158.250', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:145.0) Gecko/20100101 Firefox/145.0', 1, '02:26:38 pm', '2025-10-26 08:26:38', '2025-10-26 08:26:38'),
(1381, 'LoginPage View', 'https://iifcadmin.resnova.dev', 'GET', '115.127.158.250', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:145.0) Gecko/20100101 Firefox/145.0', 1, '02:31:40 pm', '2025-10-26 08:31:40', '2025-10-26 08:31:40'),
(1382, 'LoginPage View', 'https://iifcadmin.resnova.dev', 'GET', '103.213.236.43', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 1, '02:36:50 pm', '2025-10-26 08:36:50', '2025-10-26 08:36:50'),
(1383, 'LoginPage View', 'https://iifcadmin.resnova.dev', 'GET', '103.213.236.43', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 1, '03:40:33 pm', '2025-10-26 09:40:33', '2025-10-26 09:40:33'),
(1384, 'LoginPage View', 'http://iifcadmin.resnova.dev', 'GET', '45.148.10.250', 'python-httpx/0.28.1', 1, '04:38:45 pm', '2025-10-26 10:38:45', '2025-10-26 10:38:45'),
(1385, 'LoginPage View', 'https://iifcadmin.resnova.dev', 'GET', '103.213.236.43', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 1, '05:13:05 pm', '2025-10-26 11:13:05', '2025-10-26 11:13:05'),
(1386, 'LoginPage View', 'https://iifcadmin.resnova.dev', 'GET', '103.55.145.120', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 1, '02:11:03 pm', '2025-10-27 08:11:03', '2025-10-27 08:11:03'),
(1387, 'LoginPage View', 'https://iifcadmin.resnova.dev', 'GET', '103.213.236.43', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 1, '08:04:33 pm', '2025-10-27 14:04:33', '2025-10-27 14:04:33'),
(1388, 'LoginPage View', 'http://iifcadmin.resnova.dev', 'GET', '161.35.151.135', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36', 1, '01:44:38 pm', '2025-10-28 07:44:38', '2025-10-28 07:44:38'),
(1389, 'LoginPage View', 'http://iifcadmin.resnova.dev', 'GET', '161.35.151.135', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36', 1, '01:44:38 pm', '2025-10-28 07:44:38', '2025-10-28 07:44:38'),
(1390, 'LoginPage View', 'http://iifcadmin.resnova.dev', 'GET', '161.35.151.135', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36', 1, '01:44:41 pm', '2025-10-28 07:44:41', '2025-10-28 07:44:41'),
(1391, 'LoginPage View', 'https://iifcadmin.resnova.dev', 'GET', '115.127.158.250', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:145.0) Gecko/20100101 Firefox/145.0', 1, '04:28:28 pm', '2025-10-28 10:28:28', '2025-10-28 10:28:28'),
(1392, 'LoginPage View', 'https://iifcadmin.resnova.dev', 'GET', '103.213.236.43', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 1, '05:39:25 pm', '2025-10-28 11:39:25', '2025-10-28 11:39:25'),
(1393, 'role-update', 'https://iifcadmin.resnova.dev/roles/1', 'PUT', '103.213.236.43', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 1, '05:52:55 pm', '2025-10-28 11:52:55', '2025-10-28 11:52:55'),
(1394, 'LoginPage View', 'https://iifcadmin.resnova.dev', 'GET', '103.213.236.43', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 1, '07:42:06 am', '2025-10-29 01:42:06', '2025-10-29 01:42:06'),
(1395, 'LoginPage View', 'https://iifcadmin.resnova.dev', 'GET', '103.213.236.43', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 1, '12:30:14 pm', '2025-10-29 06:30:14', '2025-10-29 06:30:14'),
(1396, 'role-update', 'https://iifcadmin.resnova.dev/roles/1', 'PUT', '103.213.236.43', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 1, '12:31:50 pm', '2025-10-29 06:31:50', '2025-10-29 06:31:50'),
(1397, 'LoginPage View', 'https://iifcadmin.resnova.dev', 'GET', '103.213.237.81', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 1, '02:54:56 pm', '2025-10-29 08:54:56', '2025-10-29 08:54:56'),
(1398, 'LoginPage View', 'https://iifcadmin.resnova.dev', 'GET', '103.213.237.81', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 1, '05:17:47 pm', '2025-10-29 11:17:47', '2025-10-29 11:17:47'),
(1399, 'LoginPage View', 'https://iifcadmin.resnova.dev', 'GET', '103.213.237.81', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 1, '05:18:37 pm', '2025-10-29 11:18:37', '2025-10-29 11:18:37'),
(1400, 'LoginPage View', 'https://iifcadmin.resnova.dev', 'GET', '114.130.92.41', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 1, '10:15:47 pm', '2025-10-29 16:15:47', '2025-10-29 16:15:47'),
(1401, 'LoginPage View', 'https://iifcadmin.resnova.dev', 'GET', '114.130.92.41', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 1, '11:00:02 pm', '2025-10-29 17:00:02', '2025-10-29 17:00:02'),
(1402, 'LoginPage View', 'https://iifcadmin.resnova.dev', 'GET', '114.130.92.41', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 1, '11:28:01 pm', '2025-10-29 17:28:01', '2025-10-29 17:28:01'),
(1403, 'LoginPage View', 'https://iifcadmin.resnova.dev', 'GET', '103.106.119.167', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 1, '12:17:12 am', '2025-10-29 18:17:12', '2025-10-29 18:17:12'),
(1404, 'LoginPage View', 'https://iifcadmin.resnova.dev', 'GET', '103.106.119.167', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 1, '12:32:54 am', '2025-10-29 18:32:54', '2025-10-29 18:32:54'),
(1405, 'LoginPage View', 'https://iifcadmin.resnova.dev', 'GET', '114.130.92.41', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 1, '02:24:05 am', '2025-10-29 20:24:05', '2025-10-29 20:24:05'),
(1406, 'LoginPage View', 'https://iifcadmin.resnova.dev', 'GET', '103.106.119.167', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 1, '02:34:38 am', '2025-10-29 20:34:38', '2025-10-29 20:34:38'),
(1407, 'LoginPage View', 'https://iifcadmin.resnova.dev', 'GET', '114.130.92.41', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 1, '02:43:49 am', '2025-10-29 20:43:49', '2025-10-29 20:43:49'),
(1408, 'LoginPage View', 'https://iifcadmin.resnova.dev', 'GET', '114.130.92.41', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 1, '08:44:04 am', '2025-10-30 02:44:04', '2025-10-30 02:44:04'),
(1409, 'LoginPage View', 'https://iifcadmin.resnova.dev', 'GET', '114.130.92.41', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 1, '12:11:55 pm', '2025-10-30 06:11:55', '2025-10-30 06:11:55'),
(1410, 'LoginPage View', 'https://iifcadmin.resnova.dev', 'GET', '114.130.92.41', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 1, '12:52:35 pm', '2025-10-30 06:52:35', '2025-10-30 06:52:35'),
(1411, 'LoginPage View', 'https://iifcadmin.resnova.dev', 'GET', '114.130.92.41', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 1, '02:01:46 pm', '2025-10-30 08:01:46', '2025-10-30 08:01:46'),
(1412, 'LoginPage View', 'https://iifcadmin.resnova.dev', 'GET', '114.130.92.41', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 1, '04:06:00 pm', '2025-10-30 10:06:00', '2025-10-30 10:06:00'),
(1413, 'role-update', 'https://iifcadmin.resnova.dev/roles/1', 'PUT', '114.130.92.41', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 1, '04:08:24 pm', '2025-10-30 10:08:24', '2025-10-30 10:08:24'),
(1414, 'LoginPage View', 'https://iifcadmin.resnova.dev', 'GET', '74.7.227.230', 'Mozilla/5.0 AppleWebKit/537.36 (KHTML, like Gecko; compatible; GPTBot/1.2; +https://openai.com/gptbot)', 1, '08:37:18 pm', '2025-10-31 14:37:18', '2025-10-31 14:37:18'),
(1415, 'forgate password page View', 'https://iifcadmin.resnova.dev/password/reset', 'GET', '74.7.227.230', 'Mozilla/5.0 AppleWebKit/537.36 (KHTML, like Gecko; compatible; GPTBot/1.2; +https://openai.com/gptbot)', 1, '08:37:38 pm', '2025-10-31 14:37:38', '2025-10-31 14:37:38'),
(1416, 'LoginPage View', 'https://iifcadmin.resnova.dev', 'GET', '114.130.92.41', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 1, '01:05:37 pm', '2025-11-01 07:05:37', '2025-11-01 07:05:37'),
(1417, 'LoginPage View', 'https://iifcadmin.resnova.dev', 'GET', '114.130.92.41', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 1, '01:39:07 pm', '2025-11-01 07:39:07', '2025-11-01 07:39:07'),
(1418, 'LoginPage View', 'https://iifcadmin.resnova.dev', 'GET', '114.130.92.41', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 1, '08:18:35 pm', '2025-11-01 14:18:35', '2025-11-01 14:18:35'),
(1419, 'LoginPage View', 'http://iifcadmin.resnova.dev', 'GET', '205.210.31.150', NULL, 1, '10:45:17 pm', '2025-11-01 16:45:17', '2025-11-01 16:45:17'),
(1420, 'LoginPage View', 'https://iifcadmin.resnova.dev', 'GET', '114.130.92.41', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 1, '05:33:57 am', '2025-11-01 23:33:57', '2025-11-01 23:33:57'),
(1421, 'role-update', 'https://iifcadmin.resnova.dev/roles/1', 'PUT', '114.130.92.41', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 1, '05:41:30 am', '2025-11-01 23:41:30', '2025-11-01 23:41:30'),
(1422, 'LoginPage View', 'https://iifcadmin.resnova.dev', 'GET', '114.130.92.41', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 1, '06:37:00 am', '2025-11-02 00:37:00', '2025-11-02 00:37:00'),
(1423, 'LoginPage View', 'https://iifcadmin.resnova.dev', 'GET', '114.130.92.41', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 1, '06:40:15 am', '2025-11-02 00:40:15', '2025-11-02 00:40:15'),
(1424, 'LoginPage View', 'https://iifcadmin.resnova.dev', 'GET', '114.130.92.41', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 1, '07:12:56 am', '2025-11-02 01:12:56', '2025-11-02 01:12:56'),
(1425, 'LoginPage View', 'https://iifcadmin.resnova.dev', 'GET', '103.106.119.167', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Mobile Safari/537.36', 1, '08:54:47 am', '2025-11-02 02:54:47', '2025-11-02 02:54:47'),
(1426, 'LoginPage View', 'https://iifcadmin.resnova.dev', 'GET', '114.130.92.41', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 1, '10:48:27 am', '2025-11-02 04:48:27', '2025-11-02 04:48:27'),
(1427, 'LoginPage View', 'https://iifcadmin.resnova.dev', 'GET', '114.130.92.41', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 1, '12:11:21 pm', '2025-11-02 06:11:21', '2025-11-02 06:11:21'),
(1428, 'LoginPage View', 'https://iifcadmin.resnova.dev', 'GET', '114.130.92.41', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 1, '02:17:26 pm', '2025-11-02 08:17:26', '2025-11-02 08:17:26'),
(1429, 'LoginPage View', 'https://iifcadmin.resnova.dev', 'GET', '114.130.92.41', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 1, '03:32:43 pm', '2025-11-02 09:32:43', '2025-11-02 09:32:43'),
(1430, 'LoginPage View', 'https://iifcadmin.resnova.dev', 'GET', '114.130.92.41', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 1, '11:02:44 pm', '2025-11-02 17:02:44', '2025-11-02 17:02:44'),
(1431, 'LoginPage View', 'http://localhost/iifcoldcode', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 1, '11:33:37 pm', '2025-11-02 11:33:37', '2025-11-02 11:33:37'),
(1432, 'LoginPage View', 'http://localhost/iifcoldcode', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 1, '12:03:31 pm', '2025-11-03 00:03:32', '2025-11-03 00:03:32'),
(1433, 'LoginPage View', 'http://localhost/iifcoldcode', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 1, '04:25:19 pm', '2025-11-03 04:25:19', '2025-11-03 04:25:19'),
(1434, 'LoginPage View', 'http://localhost/iifcoldcode', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 1, '04:25:39 pm', '2025-11-03 04:25:39', '2025-11-03 04:25:39'),
(1435, 'LoginPage View', 'http://localhost/iifc_admin_api_panel', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 1, '10:47:32 am', '2025-11-03 22:47:32', '2025-11-03 22:47:32'),
(1436, 'LoginPage View', 'http://localhost/iifc_admin_api_panel', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 1, '10:21:12 am', '2025-11-05 22:21:13', '2025-11-05 22:21:13'),
(1437, 'LoginPage View', 'http://localhost/iifc_admin_api_panel', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 1, '02:35:52 pm', '2025-11-06 02:35:53', '2025-11-06 02:35:53'),
(1438, 'LoginPage View', 'http://localhost/iifc_admin_api_panel', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 1, '09:13:44 am', '2025-11-07 21:13:45', '2025-11-07 21:13:45'),
(1439, 'LoginPage View', 'http://localhost/iifc_admin_api_panel', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 1, '03:23:08 pm', '2025-11-09 03:23:08', '2025-11-09 03:23:08'),
(1440, 'LoginPage View', 'http://localhost/iifc_admin_api_panel', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 1, '10:46:10 pm', '2025-11-09 10:46:10', '2025-11-09 10:46:10'),
(1441, 'LoginPage View', 'http://localhost/iifc_admin_api_panel', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 1, '10:51:35 pm', '2025-11-09 10:51:35', '2025-11-09 10:51:35'),
(1442, 'role-update', 'http://localhost/iifc_admin_api_panel/roles/1', 'PUT', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 1, '11:12:44 pm', '2025-11-09 11:12:44', '2025-11-09 11:12:44'),
(1443, 'LoginPage View', 'http://localhost/iifc_admin_api_panel', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 1, '09:46:36 am', '2025-11-09 21:46:36', '2025-11-09 21:46:36'),
(1444, 'LoginPage View', 'http://localhost/iifc_admin_api_panel', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 1, '05:15:25 pm', '2025-11-10 05:15:26', '2025-11-10 05:15:26');
INSERT INTO `log_activities` (`id`, `subject`, `url`, `method`, `ip`, `agent`, `user_id`, `activity_time`, `created_at`, `updated_at`) VALUES
(1445, 'LoginPage View', 'http://localhost/iifc_admin_api_panel', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 1, '09:10:40 pm', '2025-11-11 09:10:40', '2025-11-11 09:10:40'),
(1446, 'LoginPage View', 'http://localhost/iifc_admin_api_panel', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 1, '08:55:53 am', '2025-11-12 20:55:54', '2025-11-12 20:55:54'),
(1447, 'LoginPage View', 'http://localhost/iifc_admin_api_panel', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 1, '02:58:49 pm', '2025-11-13 02:58:50', '2025-11-13 02:58:50'),
(1448, 'LoginPage View', 'http://localhost/ifushionAdmin', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 1, '11:19:51 am', '2025-11-14 23:19:52', '2025-11-14 23:19:52'),
(1449, 'LoginPage View', 'http://localhost/ifushionAdmin', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 1, '11:22:26 am', '2025-11-14 23:22:26', '2025-11-14 23:22:26'),
(1450, 'LoginPage View', 'http://localhost/ifushionAdmin', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 1, '11:23:31 am', '2025-11-14 23:23:31', '2025-11-14 23:23:31'),
(1451, 'LoginPage View', 'http://localhost/ifushionAdmin', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 1, '11:23:33 am', '2025-11-14 23:23:33', '2025-11-14 23:23:33'),
(1452, 'LoginPage View', 'http://localhost/ifushionAdmin', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 1, '11:23:40 am', '2025-11-14 23:23:40', '2025-11-14 23:23:40'),
(1453, 'LoginPage View', 'http://localhost/ifushionAdmin', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 1, '11:26:23 am', '2025-11-14 23:26:23', '2025-11-14 23:26:23'),
(1454, 'LoginPage View', 'http://localhost/ifushionAdmin', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 1, '11:26:58 am', '2025-11-14 23:26:58', '2025-11-14 23:26:58'),
(1455, 'LoginPage View', 'http://localhost/ifushionAdmin', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 1, '11:27:58 am', '2025-11-14 23:27:58', '2025-11-14 23:27:58'),
(1456, 'LoginPage View', 'http://localhost/ifushionAdmin', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 1, '01:10:13 pm', '2025-11-15 01:10:13', '2025-11-15 01:10:13'),
(1457, 'role-update', 'http://localhost/ifushionAdmin/roles/1', 'PUT', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 1, '02:12:45 pm', '2025-11-15 02:12:45', '2025-11-15 02:12:45'),
(1458, 'role-update', 'http://localhost/ifushionAdmin/roles/1', 'PUT', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 1, '03:14:07 pm', '2025-11-15 03:14:07', '2025-11-15 03:14:07'),
(1459, 'role-update', 'http://localhost/ifushionAdmin/roles/1', 'PUT', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 1, '04:38:13 pm', '2025-11-15 04:38:13', '2025-11-15 04:38:13'),
(1460, 'role-update', 'http://localhost/ifushionAdmin/roles/1', 'PUT', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 1, '04:41:10 pm', '2025-11-15 04:41:10', '2025-11-15 04:41:10'),
(1461, 'role-update', 'http://localhost/ifushionAdmin/roles/1', 'PUT', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 1, '04:42:05 pm', '2025-11-15 04:42:05', '2025-11-15 04:42:05'),
(1462, 'LoginPage View', 'http://localhost/ifushionAdmin', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 1, '09:37:01 am', '2025-11-15 21:37:01', '2025-11-15 21:37:01'),
(1463, 'role-update', 'http://localhost/ifushionAdmin/roles/1', 'PUT', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 1, '10:30:34 am', '2025-11-15 22:30:34', '2025-11-15 22:30:34'),
(1464, 'role-update', 'http://localhost/ifushionAdmin/roles/1', 'PUT', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 1, '12:32:31 pm', '2025-11-16 00:32:31', '2025-11-16 00:32:31'),
(1465, 'LoginPage View', 'http://localhost/ifushionAdmin', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 1, '07:48:52 pm', '2025-11-16 07:48:54', '2025-11-16 07:48:54'),
(1466, 'role-update', 'http://localhost/ifushionAdmin/admin/roles/1', 'PUT', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 1, '08:32:05 pm', '2025-11-16 08:32:05', '2025-11-16 08:32:05'),
(1467, 'role-update', 'http://localhost/ifushionAdmin/admin/roles/1', 'PUT', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 1, '08:36:18 pm', '2025-11-16 08:36:18', '2025-11-16 08:36:18'),
(1468, 'role-update', 'http://localhost/ifushionAdmin/admin/roles/1', 'PUT', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 1, '10:04:33 pm', '2025-11-16 10:04:33', '2025-11-16 10:04:33'),
(1469, 'LoginPage View', 'http://localhost/ifushionAdmin', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 1, '11:13:25 am', '2025-11-16 23:13:26', '2025-11-16 23:13:26'),
(1470, 'role-update', 'http://localhost/ifushionAdmin/admin/roles/1', 'PUT', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 1, '11:15:30 am', '2025-11-16 23:15:30', '2025-11-16 23:15:30'),
(1471, 'LoginPage View', 'http://localhost/ifushionAdmin', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 1, '10:21:09 am', '2025-11-19 22:21:10', '2025-11-19 22:21:10'),
(1472, 'LoginPage View', 'http://localhost/ifushionAdmin', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 1, '04:09:40 pm', '2025-11-20 04:09:40', '2025-11-20 04:09:40'),
(1473, 'role-update', 'http://localhost/ifushionAdmin/admin/roles/1', 'PUT', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 1, '04:27:30 pm', '2025-11-20 04:27:30', '2025-11-20 04:27:30'),
(1474, 'LoginPage View', 'http://localhost/ifushionAdmin', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 1, '10:23:09 am', '2025-11-20 22:23:11', '2025-11-20 22:23:11'),
(1475, 'LoginPage View', 'http://localhost/ifushionAdmin', 'GET', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 1, '10:28:05 am', '2025-11-20 22:28:05', '2025-11-20 22:28:05');

-- --------------------------------------------------------

--
-- Table structure for table `main_sliders`
--

CREATE TABLE `main_sliders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `media`
--

CREATE TABLE `media` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `youtube_link` varchar(255) NOT NULL,
  `video_id` varchar(20) DEFAULT NULL,
  `order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `menu_items`
--

CREATE TABLE `menu_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `route` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT 'category',
  `order` int(11) NOT NULL DEFAULT 0,
  `is_visible` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `menu_items`
--

INSERT INTO `menu_items` (`id`, `name`, `route`, `type`, `order`, `is_visible`, `created_at`, `updated_at`) VALUES
(1, 'Shirt Collection', '/category/shirt-collection', 'category', 1, 1, '2025-08-12 04:32:13', '2025-08-21 21:10:02'),
(2, 'Bottom Wear', '/category/bottom-wear', 'category', 7, 0, '2025-08-12 04:32:13', '2025-08-21 21:10:03'),
(3, 'Winter Collection', '/category/winter-collection', 'category', 8, 0, '2025-08-12 04:32:13', '2025-08-21 21:10:03'),
(4, 'Denim Collection', '/category/denim-collection', 'category', 6, 1, '2025-08-12 04:32:13', '2025-08-21 21:10:03'),
(5, 'T-Shirt Collection', '/category/t-shirt-collection', 'category', 2, 1, '2025-08-12 04:32:13', '2025-08-21 21:10:02'),
(6, 'Jersey Collection', '/category/jersey-collection', 'category', 4, 1, '2025-08-12 04:32:13', '2025-08-21 21:10:03'),
(7, 'Kids Collection', '/category/kids-collection', 'category', 9, 0, '2025-08-12 04:32:13', '2025-08-21 21:10:03'),
(8, 'Panjabi Collection', '/category/panjabi-collection', 'category', 10, 0, '2025-08-12 04:32:14', '2025-08-21 21:10:03'),
(9, 'Women Collection', '/category/women-collection', 'category', 11, 0, '2025-08-12 04:32:14', '2025-08-21 21:10:03'),
(10, 'Shoes Collection', '/category/shoes-collection', 'category', 12, 0, '2025-08-12 04:32:14', '2025-08-21 21:10:03'),
(11, 'Jewelry & Accessories', '/category/jewelry-accessories', 'category', 13, 0, '2025-08-12 04:32:14', '2025-08-21 21:10:03'),
(12, 'Cosplay Products', '/category/cosplay-products', 'category', 3, 1, '2025-08-12 04:32:14', '2025-08-21 21:10:02'),
(13, 'Others', '/category/others', 'category', 14, 0, '2025-08-12 04:32:14', '2025-08-21 21:10:03'),
(16, 'Bundle Offer', '/offer/bundle-offer', 'Summer T-Shirt Deal', 0, 1, '2025-08-21 20:54:32', '2025-08-21 21:10:02'),
(17, 'Mega Deal', '/offer/mega-deal', 'Jersey Deal', 5, 0, '2025-08-21 20:54:32', '2025-08-21 21:10:03'),
(18, 'Laptop', '/category/laptop', 'category', 0, 1, '2025-10-04 02:01:22', '2025-10-04 02:01:22'),
(19, 'Desktop', '/category/desktop', 'category', 0, 1, '2025-10-04 02:01:22', '2025-10-04 02:01:22'),
(20, 'Monitor', '/category/monitor', 'category', 0, 1, '2025-10-04 02:01:22', '2025-10-04 02:01:22'),
(21, 'Processor', '/category/processor', 'category', 0, 1, '2025-10-04 02:01:23', '2025-10-04 02:01:23'),
(22, 'Motherboard', '/category/motherboard', 'category', 0, 1, '2025-10-04 02:01:23', '2025-10-04 02:01:23'),
(23, 'Graphics Card', '/category/graphics-card', 'category', 0, 1, '2025-10-04 02:01:23', '2025-10-04 02:01:23'),
(24, 'Internal HDD', '/category/internal-hdd', 'category', 0, 1, '2025-10-04 02:01:23', '2025-10-04 02:01:23'),
(25, 'Internal SSD', '/category/internal-ssd', 'category', 0, 1, '2025-10-04 02:01:23', '2025-10-04 02:01:23'),
(26, 'Optical Disk Driver', '/category/optical-disk-driver', 'category', 0, 1, '2025-10-04 02:01:23', '2025-10-04 02:01:23'),
(27, 'Desktop Ram', '/category/desktop-ram', 'category', 0, 1, '2025-10-04 02:01:23', '2025-10-04 02:01:23'),
(28, 'Laptop Ram', '/category/laptop-ram', 'category', 0, 1, '2025-10-04 02:01:23', '2025-10-04 02:01:23'),
(29, 'CPU Cooler', '/category/cpu-cooler', 'category', 0, 1, '2025-10-04 02:01:23', '2025-10-04 02:01:23'),
(30, 'Power Supply', '/category/power-supply', 'category', 0, 1, '2025-10-04 02:01:24', '2025-10-04 02:01:24'),
(31, 'Casing', '/category/casing', 'category', 0, 1, '2025-10-04 02:01:24', '2025-10-04 02:01:24'),
(32, 'Casing Fan', '/category/casing-fan', 'category', 0, 1, '2025-10-04 02:01:24', '2025-10-04 02:01:24'),
(33, 'Camera', '/category/camera', 'category', 0, 1, '2025-10-04 02:01:24', '2025-10-04 02:01:24'),
(34, 'Printer', '/category/printer', 'category', 0, 1, '2025-10-04 02:01:24', '2025-10-04 02:01:24'),
(35, 'Security Camera', '/category/security-camera', 'category', 0, 1, '2025-10-04 02:01:24', '2025-10-04 02:01:24'),
(36, 'Smart Phone', '/category/smart-phone', 'category', 0, 1, '2025-10-04 02:01:24', '2025-10-04 02:01:24');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `msg` longtext NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2024_11_13_082412_create_system_information_table', 1),
(5, '2024_11_16_104315_create_designations_table', 1),
(6, '2024_11_17_062502_create_branches_table', 1),
(7, '2024_11_19_073342_create_log_activities_table', 1),
(8, '2025_05_06_073002_create_permission_tables', 1),
(9, '2025_06_20_044750_create_customers_table', 1),
(13, '2025_06_25_082218_create_client_says_table', 1),
(14, '2025_06_25_092814_create_news_and_media_table', 2),
(15, '2025_06_25_093403_create_blogs_table', 2),
(16, '2025_06_25_094932_create_extra_pages_table', 2),
(17, '2025_06_25_095027_create_messages_table', 2),
(18, '2025_06_25_095156_create_social_links_table', 2),
(21, '2025_07_13_040456_create_default_locations_table', 2),
(22, '2025_07_26_025305_create_coupons_table', 2),
(23, '2025_07_26_030042_create_coupon_user_table', 2),
(34, '2025_08_09_081950_create_size_chart_entries_table', 2),
(40, '2025_08_12_095121_create_menu_items_table', 2),
(41, '2025_08_12_103956_create_settings_table', 2),
(42, '2025_08_12_112747_create_bundle_offers_table', 3),
(43, '2025_08_12_112801_create_bundle_offer_tiers_table', 3),
(44, '2025_08_12_112831_create_bundle_offer_products_table', 3),
(45, '2025_08_13_005042_create_sidebar_menus_table', 3),
(46, '2025_08_13_084408_create_offersection_settings_table', 3),
(48, '2025_08_14_072941_create_customer_addresses_table', 3),
(49, '2025_08_14_133807_create_orders_table', 3),
(50, '2025_08_14_133825_create_order_details_table', 3),
(51, '2025_08_17_123603_create_payments_table', 3),
(52, '2025_08_17_165644_create_reward_point_settings_table', 3),
(53, '2025_08_17_165706_create_reward_points_table', 3),
(54, '2025_08_18_022712_create_expense_categories_table', 3),
(55, '2025_08_18_022828_create_expenses_table', 3),
(56, '2025_08_27_104840_create_stock_histories_table', 3),
(57, '2025_08_27_113641_create_suppliers_table', 3),
(58, '2025_08_27_171126_create_purchases_table', 3),
(59, '2025_08_27_171156_create_purchase_details_table', 3),
(60, '2025_08_28_025645_create_analytic_settings_table', 3),
(72, '2025_08_29_075148_create_shareholder_deposits_table', 3),
(73, '2025_08_29_080658_create_shareholder_withdraws_table', 3),
(75, '2025_08_31_051627_create_offer_products_table', 3),
(76, '2025_08_31_054953_create_offer_banners_table', 3),
(77, '2025_08_31_093003_create_main_sliders_table', 3),
(78, '2025_09_04_074006_create_order_trackings_table', 3),
(81, '2025_09_13_104555_create_highlight_products_table', 3),
(82, '2025_09_15_073708_add_category_id_to_bundle_offer_product_table', 3),
(83, '2025_09_16_145036_create_featured_categories_table', 3),
(84, '2025_09_16_154527_create_homepage_sections_table', 4),
(85, '2025_09_17_062018_create_gateway_settings_table', 5),
(86, '2025_08_29_123026_create_review_images_table', 6),
(94, '2025_09_20_131413_create_products_table', 8),
(95, '2025_09_20_131454_create_product_images_table', 8),
(96, '2025_09_20_131523_create_stocks_table', 8),
(97, '2025_09_20_133507_create_product_attribute_values_table', 9),
(98, '2025_09_21_060148_rename_price_columns_in_products_table', 10),
(99, '2025_09_21_061057_add_offer_price_to_products_table', 11),
(100, '2025_09_25_093113_create_attribute_category_table', 12),
(101, '2025_09_25_093158_remove_category_id_from_attributes_table', 12),
(102, '2025_09_29_061833_add_is_required_to_attribute_category_table', 13),
(103, '2025_09_30_095718_create_hero_sections_table', 14),
(104, '2025_09_30_103514_create_home_page_descriptions_table', 15),
(105, '2025_10_01_161442_rename_return_pollicy_in_extra_pages_table', 16),
(106, '2025_10_01_161510_add_policy_columns_to_extra_pages_table', 16),
(108, '2025_10_02_063328_add_group_name_to_attribute_category_table', 18),
(109, '2025_10_09_063353_create_flash_sales_table', 19),
(110, '2025_10_09_073507_create_offers_table', 20),
(111, '2025_10_09_085846_add_image_and_category_id_to_offers_table', 20),
(112, '2025_10_10_070654_create_purchase_payments_table', 21),
(113, '2025_10_10_091706_create_product_reviews_table', 22),
(114, '2025_10_10_091750_create_product_review_images_table', 22),
(115, '2025_10_10_103540_create_payment_gateway_settings_table', 23),
(116, '2025_10_11_031319_add_source_to_customers_table', 24),
(117, '2025_10_13_040159_change_left_image_to_json_in_hero_sections_table', 25),
(118, '2025_10_13_105633_add_is_featured_to_categories_table', 26),
(119, '2025_10_13_125847_create_frontend_controls_table', 27),
(120, '2025_10_19_110425_update_system_information_table', 28),
(121, '2024_11_17_062502_create_departments_table', 29),
(122, '2025_10_20_121922_create_officer_categories_table', 30),
(123, '2025_10_20_131412_add_parent_id_to_officer_categories_table', 31),
(124, '2025_10_20_141436_create_officers_table', 32),
(125, '2025_10_20_141442_create_department_infos_table', 32),
(126, '2025_10_20_141507_create_officer_officer_category_table', 32),
(127, '2025_10_20_141811_create_officer_social_links_table', 32),
(128, '2025_10_21_074536_add_order_column_to_officers_table', 33),
(129, '2025_10_21_080214_add_order_column_to_officer_officer_category_table', 34),
(130, '2025_06_25_095250_create_contacts_table', 35),
(131, '2025_08_08_135221_create_brands_table', 35),
(132, '2025_10_21_083542_create_about_us_table', 35),
(133, '2025_06_23_110027_create_services_table', 36),
(134, '2025_10_21_085258_create_contact_us_messages_table', 36),
(135, '2025_10_21_094020_create_service_keypoints_table', 36),
(136, '2025_10_21_101327_create_countries_table', 37),
(137, '2025_10_21_102321_create_clients_table', 37),
(138, '2025_10_21_103749_create_project_categories_table', 38),
(139, '2025_10_21_105315_create_projects_table', 38),
(140, '2025_10_21_105327_create_project_galleries_table', 38),
(141, '2025_10_21_115301_create_training_categories_table', 39),
(142, '2025_10_21_121130_create_trainings_table', 39),
(143, '2025_10_21_121139_create_training_skills_table', 39),
(144, '2025_10_21_125707_create_notice_categories_table', 40),
(145, '2025_10_21_131303_create_notices_table', 40),
(146, '2025_10_21_135008_create_publications_table', 40),
(147, '2025_06_25_074502_create_galleries_table', 41),
(148, '2025_10_21_162916_create_events_table', 42),
(149, '2025_10_21_164614_create_press_releases_table', 42),
(150, '2025_10_21_170308_create_careers_table', 42),
(151, '2025_10_21_172240_create_job_applicants_table', 42),
(152, '2025_10_21_174037_create_sliders_table', 42),
(154, '2025_10_22_082714_create_personal_access_tokens_table', 43),
(155, '2025_10_22_095314_add_is_flagship_to_projects_table', 44),
(156, '2025_10_21_175055_create_iifc_strengths_table', 45),
(157, '2025_11_15_071635_create_solutions_table', 46),
(158, '2025_11_15_090317_create_why_us_table', 47),
(159, '2025_11_15_092923_update_about_us_table_with_new_fields', 47),
(160, '2025_11_15_102529_create_teams_table', 47),
(161, '2025_11_16_035211_create_why_choose_us_table', 48),
(162, '2025_11_16_041748_create_media_table', 48),
(163, '2025_11_16_044951_add_video_id_to_media_table', 49),
(164, '2025_11_16_054047_create_digital_marketing_page_table', 50),
(165, '2025_11_16_054048_create_digital_marketing_growth_items_table', 50),
(166, '2025_11_16_054055_create_digital_marketing_solutions_table', 50),
(167, '2025_11_16_071522_create_web_solution_page_table', 51),
(168, '2025_11_16_071524_create_web_solution_checklists_table', 51),
(169, '2025_11_16_071524_create_web_solution_includes_table', 51),
(170, '2025_11_16_071525_create_web_solution_providings_table', 51),
(171, '2025_11_16_071525_create_web_solution_work_categories_table', 51),
(172, '2025_11_16_071526_create_web_solution_work_items_table', 51),
(173, '2025_11_16_071540_create_web_solution_care_items_table', 51),
(174, '2025_11_16_083255_create_graphic_design_checklists_table', 52),
(175, '2025_11_16_083255_create_graphic_design_page_table', 52),
(176, '2025_11_16_083256_create_graphic_design_solutions_table', 52),
(177, '2025_11_16_093214_create_facebook_page_table', 53),
(178, '2025_11_16_093215_create_facebook_pricing_packages_table', 53),
(179, '2025_11_16_093216_create_facebook_more_services_table', 53),
(180, '2025_11_16_095737_create_facebook_ads_page_table', 54),
(181, '2025_11_16_095738_create_facebook_ads_features_table', 54),
(182, '2025_11_16_095739_create_facebook_ads_campaigns_table', 54),
(183, '2025_11_16_095740_create_facebook_ads_pricing_categories_table', 54),
(184, '2025_11_16_095741_create_facebook_ads_pricing_packages_table', 54),
(185, '2025_11_16_095754_create_facebook_ads_faqs_table', 54),
(186, '2025_11_16_105146_create_uk_company_page_table', 55),
(187, '2025_11_16_105147_create_uk_pricing_packages_table', 55),
(188, '2025_11_16_105148_create_uk_testimonials_table', 55),
(189, '2025_11_16_105153_create_uk_review_platforms_table', 55),
(192, '2025_11_16_113502_create_vps_page_table', 56),
(193, '2025_11_16_113503_create_vps_package_categories_table', 56),
(194, '2025_11_16_113514_create_vps_packages_table', 56),
(195, '2025_11_16_145801_create_categories_table', 57),
(196, '2025_11_16_152322_create_store_main_banners_table', 58),
(197, '2025_11_16_152327_create_store_side_banners_table', 58),
(198, '2025_11_17_044701_create_products_table', 58),
(199, '2025_11_17_044707_create_product_packages_table', 58),
(200, '2025_11_20_083124_create_orders_table', 59),
(201, '2025_11_20_083128_create_order_items_table', 59),
(202, '2025_11_20_083330_create_product_reviews_table', 59);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1),
(1, 'App\\Models\\User', 2),
(1, 'App\\Models\\User', 3857),
(2, 'App\\Models\\User', 3847),
(2, 'App\\Models\\User', 3848);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_number` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `shipping_address` text DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `zip_code` varchar(255) DEFAULT NULL,
  `sub_total` decimal(10,2) NOT NULL DEFAULT 0.00,
  `tax` decimal(10,2) NOT NULL DEFAULT 0.00,
  `shipping_cost` decimal(10,2) NOT NULL DEFAULT 0.00,
  `discount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `grand_total` decimal(10,2) NOT NULL DEFAULT 0.00,
  `payment_method` varchar(255) NOT NULL DEFAULT 'cod',
  `payment_status` varchar(255) NOT NULL DEFAULT 'unpaid',
  `order_status` varchar(255) NOT NULL DEFAULT 'pending',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_name` varchar(255) NOT NULL,
  `variation_name` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `group_name` varchar(255) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `group_name`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'role', 'roleView', 'web', '2025-05-06 02:07:07', '2025-05-06 02:07:07'),
(2, 'role', 'roleAdd', 'web', '2025-05-06 02:07:07', '2025-05-06 02:07:07'),
(3, 'role', 'roleEdit', 'web', '2025-05-06 02:07:08', '2025-05-06 02:07:08'),
(4, 'role', 'roleDelete', 'web', '2025-05-06 02:07:08', '2025-05-06 02:07:08'),
(5, 'user', 'userAdd', 'web', '2025-05-06 02:07:09', '2025-05-06 02:07:09'),
(6, 'user', 'userView', 'web', '2025-05-06 02:07:09', '2025-05-06 02:07:09'),
(7, 'user', 'userDelete', 'web', '2025-05-06 02:07:09', '2025-05-06 02:07:09'),
(8, 'user', 'userUpdate', 'web', '2025-05-06 02:07:10', '2025-05-06 02:07:10'),
(9, 'permission', 'permissionAdd', 'web', '2025-05-06 02:07:10', '2025-05-06 02:07:10'),
(10, 'permission', 'permissionView', 'web', '2025-05-06 02:07:10', '2025-05-06 02:07:10'),
(11, 'permission', 'permissionDelete', 'web', '2025-05-06 02:07:11', '2025-05-06 02:07:11'),
(12, 'permission', 'permissionUpdate', 'web', '2025-05-06 02:07:11', '2025-05-06 02:07:11'),
(13, 'profile', 'profileView', 'web', '2025-05-06 02:07:12', '2025-05-06 02:07:12'),
(14, 'profile', 'profileSetting', 'web', '2025-05-06 02:07:12', '2025-05-06 02:07:12'),
(15, 'dashboard', 'dashboardView', 'web', '2025-05-06 02:07:12', '2025-05-06 02:07:12'),
(20, 'designation', 'designationAdd', 'web', NULL, NULL),
(21, 'designation', 'designationView', 'web', NULL, NULL),
(22, 'designation', 'designationDelete', 'web', NULL, NULL),
(23, 'designation', 'designationUpdate', 'web', NULL, NULL),
(24, 'panelSetting', 'panelSettingAdd', 'web', NULL, NULL),
(25, 'panelSetting', 'panelSettingView', 'web', NULL, NULL),
(26, 'panelSetting', 'panelSettingDelete', 'web', NULL, NULL),
(27, 'panelSetting', 'panelSettingUpdate', 'web', NULL, NULL),
(30, 'category', 'categoryAdd', 'web', NULL, NULL),
(31, 'category', 'categoryView', 'web', NULL, NULL),
(32, 'category', 'categoryDelete', 'web', NULL, NULL),
(33, 'category', 'categoryUpdate', 'web', NULL, NULL),
(106, 'extraPage', 'extraPageAdd', 'web', NULL, NULL),
(107, 'extraPage', 'extraPageView', 'web', NULL, NULL),
(108, 'extraPage', 'extraPageDelete', 'web', NULL, NULL),
(109, 'extraPage', 'extraPageUpdate', 'web', NULL, NULL),
(118, 'socialLink', 'socialLinkAdd', 'web', NULL, NULL),
(119, 'socialLink', 'socialLinkView', 'web', NULL, NULL),
(120, 'socialLink', 'socialLinkDelete', 'web', NULL, NULL),
(121, 'socialLink', 'socialLinkUpdate', 'web', NULL, NULL),
(122, 'customer', 'customerAdd', 'web', NULL, NULL),
(123, 'customer', 'customerView', 'web', NULL, NULL),
(124, 'customer', 'customerDelete', 'web', NULL, NULL),
(125, 'customer', 'customerUpdate', 'web', NULL, NULL),
(208, 'department', 'departmentAdd', 'web', '2025-10-20 03:09:58', '2025-10-20 03:09:58'),
(209, 'department', 'departmentView', 'web', '2025-10-20 03:09:58', '2025-10-20 03:09:58'),
(210, 'department', 'departmentDelete', 'web', '2025-10-20 03:09:58', '2025-10-20 03:09:58'),
(211, 'department', 'departmentUpdate', 'web', '2025-10-20 03:09:58', '2025-10-20 03:09:58'),
(230, 'aboutUsView', 'aboutUsView', 'web', '2025-10-21 02:43:41', '2025-10-21 02:43:41'),
(237, 'country', 'countryAdd', 'web', '2025-10-21 05:26:32', '2025-10-21 05:26:32'),
(238, 'country', 'countryView', 'web', '2025-10-21 05:26:32', '2025-10-21 05:26:32'),
(239, 'country', 'countryDelete', 'web', '2025-10-21 05:26:32', '2025-10-21 05:26:32'),
(240, 'country', 'countryUpdate', 'web', '2025-10-21 05:26:32', '2025-10-21 05:26:32'),
(241, 'client', 'clientAdd', 'web', '2025-10-21 05:28:15', '2025-10-21 05:28:15'),
(242, 'client', 'clientView', 'web', '2025-10-21 05:28:15', '2025-10-21 05:28:15'),
(243, 'client', 'clientDelete', 'web', '2025-10-21 05:28:15', '2025-10-21 05:28:15'),
(244, 'client', 'clientUpdate', 'web', '2025-10-21 05:28:15', '2025-10-21 05:28:15'),
(277, 'slider', 'sliderAdd', 'web', '2025-10-21 12:03:57', '2025-10-21 12:03:57'),
(278, 'slider', 'sliderView', 'web', '2025-10-21 12:03:57', '2025-10-21 12:03:57'),
(279, 'slider', 'sliderDelete', 'web', '2025-10-21 12:03:57', '2025-10-21 12:03:57'),
(280, 'slider', 'sliderUpdate', 'web', '2025-10-21 12:03:57', '2025-10-21 12:03:57'),
(281, 'iifcStrength', 'iifcStrengthAdd', 'web', '2025-10-21 12:04:51', '2025-10-21 12:04:51'),
(282, 'iifcStrength', 'iifcStrengthView', 'web', '2025-10-21 12:04:51', '2025-10-21 12:04:51'),
(283, 'iifcStrength', 'iifcStrengthDelete', 'web', '2025-10-21 12:04:51', '2025-10-21 12:04:51'),
(284, 'iifcStrength', 'iifcStrengthUpdate', 'web', '2025-10-21 12:04:51', '2025-10-21 12:04:51'),
(285, 'contactUs', 'contactUsAdd', 'web', '2025-10-21 12:05:58', '2025-10-21 12:05:58'),
(286, 'contactUs', 'contactUsView', 'web', '2025-10-21 12:05:58', '2025-10-21 12:05:58'),
(287, 'contactUs', 'contactUsDelete', 'web', '2025-10-21 12:05:58', '2025-10-21 12:05:58'),
(288, 'contactUs', 'contactUsUpdate', 'web', '2025-10-21 12:05:58', '2025-10-21 12:05:58'),
(310, 'importantLinkView', 'importantLinkView', 'web', '2025-10-29 06:31:36', '2025-10-29 06:31:36'),
(311, 'importantLinkView', 'importantLinkAdd', 'web', '2025-10-29 06:31:36', '2025-10-29 06:31:36'),
(312, 'importantLinkView', 'importantLinkDelete', 'web', '2025-10-29 06:31:36', '2025-10-29 06:31:36'),
(313, 'importantLinkView', 'importantLinkUpdate', 'web', '2025-10-29 06:31:36', '2025-10-29 06:31:36'),
(322, 'headerLinkView', 'headerLinkView', 'web', '2025-11-01 23:41:15', '2025-11-01 23:41:15'),
(323, 'headerLinkView', 'headerLinkUpdate', 'web', '2025-11-01 23:41:15', '2025-11-01 23:41:15'),
(330, 'solution', 'solutionAdd', 'web', '2025-11-15 02:12:16', '2025-11-15 02:12:16'),
(331, 'solution', 'solutionView', 'web', '2025-11-15 02:12:16', '2025-11-15 02:12:16'),
(332, 'solution', 'solutionDelete', 'web', '2025-11-15 02:12:16', '2025-11-15 02:12:16'),
(333, 'solution', 'solutionUpdate', 'web', '2025-11-15 02:12:16', '2025-11-15 02:12:16'),
(334, 'whyUs', 'whyUsAdd', 'web', '2025-11-15 03:13:48', '2025-11-15 03:13:48'),
(335, 'whyUs', 'whyUsView', 'web', '2025-11-15 03:13:48', '2025-11-15 03:13:48'),
(336, 'whyUs', 'whyUsDelete', 'web', '2025-11-15 03:13:48', '2025-11-15 03:13:48'),
(337, 'whyUs', 'whyUsUpdate', 'web', '2025-11-15 03:13:48', '2025-11-15 03:13:48'),
(342, 'team', 'teamAdd', 'web', '2025-11-15 04:41:39', '2025-11-15 04:41:39'),
(343, 'team', 'teamView', 'web', '2025-11-15 04:41:39', '2025-11-15 04:41:39'),
(344, 'team', 'teamDelete', 'web', '2025-11-15 04:41:39', '2025-11-15 04:41:39'),
(345, 'team', 'teamUpdate', 'web', '2025-11-15 04:41:39', '2025-11-15 04:41:39'),
(346, 'whyChooseUs', 'whyChooseUsUpdate', 'web', '2025-11-15 22:16:47', '2025-11-15 22:16:47'),
(347, 'whyChooseUs', 'whyChooseUsView', 'web', '2025-11-15 22:16:47', '2025-11-15 22:16:47'),
(348, 'whyChooseUs', 'whyChooseUsAdd', 'web', '2025-11-15 22:16:47', '2025-11-15 22:16:47'),
(349, 'whyChooseUs', 'whyChooseUsDelete', 'web', '2025-11-15 22:16:47', '2025-11-15 22:16:47'),
(350, 'media', 'mediaAdd', 'web', '2025-11-15 22:30:03', '2025-11-15 22:30:03'),
(351, 'media', 'mediaView', 'web', '2025-11-15 22:30:03', '2025-11-15 22:30:03'),
(352, 'media', 'mediaDelete', 'web', '2025-11-15 22:30:03', '2025-11-15 22:30:03'),
(353, 'media', 'mediaUpdate', 'web', '2025-11-15 22:30:03', '2025-11-15 22:30:03'),
(354, 'digitalMarketingPage', 'digitalMarketingPageView', 'web', '2025-11-16 00:30:24', '2025-11-16 00:30:24'),
(355, 'digitalMarketingPage', 'digitalMarketingPageAdd', 'web', '2025-11-16 00:30:24', '2025-11-16 00:30:24'),
(356, 'digitalMarketingPage', 'digitalMarketingPageDelete', 'web', '2025-11-16 00:30:24', '2025-11-16 00:30:24'),
(357, 'digitalMarketingPage', 'digitalMarketingPageUpdate', 'web', '2025-11-16 00:30:24', '2025-11-16 00:30:24'),
(358, 'digitalMarketingGrowth', 'digitalMarketingGrowthView', 'web', '2025-11-16 00:31:20', '2025-11-16 00:31:20'),
(359, 'digitalMarketingGrowth', 'digitalMarketingGrowthAdd', 'web', '2025-11-16 00:31:20', '2025-11-16 00:31:20'),
(360, 'digitalMarketingGrowth', 'digitalMarketingGrowthUpdate', 'web', '2025-11-16 00:31:20', '2025-11-16 00:31:20'),
(361, 'digitalMarketingGrowth', 'digitalMarketingGrowthDelete', 'web', '2025-11-16 00:31:20', '2025-11-16 00:31:20'),
(362, 'digitalMarketingSolution', 'digitalMarketingSolutionView', 'web', '2025-11-16 00:32:17', '2025-11-16 00:32:17'),
(363, 'digitalMarketingSolution', 'digitalMarketingSolutionAdd', 'web', '2025-11-16 00:32:17', '2025-11-16 00:32:17'),
(364, 'digitalMarketingSolution', 'digitalMarketingSolutionDelete', 'web', '2025-11-16 00:32:17', '2025-11-16 00:32:17'),
(365, 'digitalMarketingSolution', 'digitalMarketingSolutionUpdate', 'web', '2025-11-16 00:32:17', '2025-11-16 00:32:17'),
(366, 'graphicDesignPage', 'graphicDesignPageAdd', 'web', '2025-11-16 08:05:50', '2025-11-16 08:05:50'),
(367, 'graphicDesignPage', 'graphicDesignPageView', 'web', '2025-11-16 08:05:50', '2025-11-16 08:05:50'),
(368, 'graphicDesignPage', 'graphicDesignPageDelete', 'web', '2025-11-16 08:05:50', '2025-11-16 08:05:50'),
(369, 'graphicDesignPage', 'graphicDesignPageUpdate', 'web', '2025-11-16 08:05:50', '2025-11-16 08:05:50'),
(370, 'graphicDesignChecklist', 'graphicDesignChecklistView', 'web', '2025-11-16 08:07:24', '2025-11-16 08:07:24'),
(371, 'graphicDesignChecklist', 'graphicDesignChecklistAdd', 'web', '2025-11-16 08:07:24', '2025-11-16 08:07:24'),
(372, 'graphicDesignChecklist', 'graphicDesignChecklistDelete', 'web', '2025-11-16 08:07:24', '2025-11-16 08:07:24'),
(373, 'graphicDesignChecklist', 'graphicDesignChecklistUpdate', 'web', '2025-11-16 08:07:24', '2025-11-16 08:07:24'),
(374, 'graphicDesignSolution', 'graphicDesignSolutionView', 'web', '2025-11-16 08:08:24', '2025-11-16 08:08:24'),
(375, 'graphicDesignSolution', 'graphicDesignSolutionAdd', 'web', '2025-11-16 08:08:24', '2025-11-16 08:08:24'),
(376, 'graphicDesignSolution', 'graphicDesignSolutionDelete', 'web', '2025-11-16 08:08:24', '2025-11-16 08:08:24'),
(377, 'graphicDesignSolution', 'graphicDesignSolutionUpdate', 'web', '2025-11-16 08:08:24', '2025-11-16 08:08:24'),
(385, 'webSolutionCareItem', 'webSolutionCareItemView', 'web', '2025-11-16 08:10:17', '2025-11-16 08:10:17'),
(386, 'webSolutionCareItem', 'webSolutionCareItemAdd', 'web', '2025-11-16 08:10:17', '2025-11-16 08:10:17'),
(387, 'webSolutionCareItem', 'webSolutionCareItemDelete', 'web', '2025-11-16 08:10:17', '2025-11-16 08:10:17'),
(388, 'webSolutionCareItem', 'webSolutionCareItemUpdate', 'web', '2025-11-16 08:10:17', '2025-11-16 08:10:17'),
(389, 'webSolutionChecklist', 'webSolutionChecklistView', 'web', '2025-11-16 08:11:32', '2025-11-16 08:11:32'),
(390, 'webSolutionChecklist', 'webSolutionChecklistAdd', 'web', '2025-11-16 08:11:32', '2025-11-16 08:11:32'),
(391, 'webSolutionChecklist', 'webSolutionChecklistDelete', 'web', '2025-11-16 08:11:32', '2025-11-16 08:11:32'),
(392, 'webSolutionChecklist', 'webSolutionChecklistUpdate', 'web', '2025-11-16 08:11:32', '2025-11-16 08:11:32'),
(393, 'webSolutionInclude', 'webSolutionIncludeView', 'web', '2025-11-16 08:12:35', '2025-11-16 08:12:35'),
(394, 'webSolutionInclude', 'webSolutionIncludeAdd', 'web', '2025-11-16 08:12:35', '2025-11-16 08:12:35'),
(395, 'webSolutionInclude', 'webSolutionIncludeUpdate', 'web', '2025-11-16 08:12:35', '2025-11-16 08:12:35'),
(396, 'webSolutionInclude', 'webSolutionIncludeDelete', 'web', '2025-11-16 08:12:35', '2025-11-16 08:12:35'),
(397, 'webSolutionPage', 'webSolutionPageView', 'web', '2025-11-16 08:13:04', '2025-11-16 08:13:04'),
(398, 'webSolutionProviding', 'webSolutionProvidingView', 'web', '2025-11-16 08:13:53', '2025-11-16 08:13:53'),
(399, 'webSolutionProviding', 'webSolutionProvidingAdd', 'web', '2025-11-16 08:13:53', '2025-11-16 08:13:53'),
(400, 'webSolutionProviding', 'webSolutionProvidingUpdate', 'web', '2025-11-16 08:13:53', '2025-11-16 08:13:53'),
(401, 'webSolutionProviding', 'webSolutionProvidingDelete', 'web', '2025-11-16 08:13:53', '2025-11-16 08:13:53'),
(402, 'webSolutionWorkCategory', 'webSolutionWorkCategoryView', 'web', '2025-11-16 08:15:02', '2025-11-16 08:15:02'),
(403, 'webSolutionWorkCategory', 'webSolutionWorkCategoryAdd', 'web', '2025-11-16 08:15:02', '2025-11-16 08:15:02'),
(404, 'webSolutionWorkCategory', 'webSolutionWorkCategoryDelete', 'web', '2025-11-16 08:15:02', '2025-11-16 08:15:02'),
(405, 'webSolutionWorkCategory', 'webSolutionWorkCategoryUpdate', 'web', '2025-11-16 08:15:02', '2025-11-16 08:15:02'),
(406, 'webSolutionWorkItem', 'webSolutionWorkItemView', 'web', '2025-11-16 08:16:07', '2025-11-16 08:16:07'),
(407, 'webSolutionWorkItem', 'webSolutionWorkItemAdd', 'web', '2025-11-16 08:16:07', '2025-11-16 08:16:07'),
(408, 'webSolutionWorkItem', 'webSolutionWorkItemDelete', 'web', '2025-11-16 08:16:07', '2025-11-16 08:16:07'),
(409, 'webSolutionWorkItem', 'webSolutionWorkItemUpdate', 'web', '2025-11-16 08:16:07', '2025-11-16 08:16:07'),
(410, 'facebookAdsCampaign', 'facebookAdsCampaignView', 'web', '2025-11-16 08:18:58', '2025-11-16 08:18:58'),
(411, 'facebookAdsCampaign', 'facebookAdsCampaignAdd', 'web', '2025-11-16 08:18:58', '2025-11-16 08:18:58'),
(412, 'facebookAdsCampaign', 'facebookAdsCampaignDelete', 'web', '2025-11-16 08:18:58', '2025-11-16 08:18:58'),
(413, 'facebookAdsCampaign', 'facebookAdsCampaignUpdate', 'web', '2025-11-16 08:18:58', '2025-11-16 08:18:58'),
(414, 'facebookAdsFaq', 'facebookAdsFaqView', 'web', '2025-11-16 08:19:59', '2025-11-16 08:19:59'),
(415, 'facebookAdsFaq', 'facebookAdsFaqAdd', 'web', '2025-11-16 08:19:59', '2025-11-16 08:19:59'),
(416, 'facebookAdsFaq', 'facebookAdsFaqDelete', 'web', '2025-11-16 08:19:59', '2025-11-16 08:19:59'),
(417, 'facebookAdsFaq', 'facebookAdsFaqUpdate', 'web', '2025-11-16 08:19:59', '2025-11-16 08:19:59'),
(418, 'facebookAdsFeature', 'facebookAdsFeatureView', 'web', '2025-11-16 08:21:02', '2025-11-16 08:21:02'),
(419, 'facebookAdsFeature', 'facebookAdsFeatureAdd', 'web', '2025-11-16 08:21:02', '2025-11-16 08:21:02'),
(420, 'facebookAdsFeature', 'facebookAdsFeatureDelete', 'web', '2025-11-16 08:21:02', '2025-11-16 08:21:02'),
(421, 'facebookAdsFeature', 'facebookAdsFeatureUpdate', 'web', '2025-11-16 08:21:02', '2025-11-16 08:21:02'),
(422, 'facebookAdsPage', 'facebookAdsPageView', 'web', '2025-11-16 08:21:21', '2025-11-16 08:21:21'),
(423, 'facebookAdsPricingCategory', 'facebookAdsPricingCategoryView', 'web', '2025-11-16 08:22:10', '2025-11-16 08:22:10'),
(424, 'facebookAdsPricingCategory', 'facebookAdsPricingCategoryDelete', 'web', '2025-11-16 08:22:10', '2025-11-16 08:22:10'),
(425, 'facebookAdsPricingCategory', 'facebookAdsPricingCategoryUpdate', 'web', '2025-11-16 08:22:10', '2025-11-16 08:22:10'),
(426, 'facebookAdsPricingCategory', 'facebookAdsPricingCategoryAdd', 'web', '2025-11-16 08:22:10', '2025-11-16 08:22:10'),
(427, 'facebookAdsPricingPackage', 'facebookAdsPricingPackageView', 'web', '2025-11-16 08:22:57', '2025-11-16 08:22:57'),
(428, 'facebookAdsPricingPackage', 'facebookAdsPricingPackageAdd', 'web', '2025-11-16 08:22:57', '2025-11-16 08:22:57'),
(429, 'facebookAdsPricingPackage', 'facebookAdsPricingPackageDelete', 'web', '2025-11-16 08:22:57', '2025-11-16 08:22:57'),
(430, 'facebookAdsPricingPackage', 'facebookAdsPricingPackageUpdate', 'web', '2025-11-16 08:22:57', '2025-11-16 08:22:57'),
(431, 'facebookMoreService', 'facebookMoreServiceView', 'web', '2025-11-16 08:24:00', '2025-11-16 08:24:00'),
(432, 'facebookMoreService', 'facebookMoreServiceAdd', 'web', '2025-11-16 08:24:00', '2025-11-16 08:24:00'),
(433, 'facebookMoreService', 'facebookMoreServiceDelete', 'web', '2025-11-16 08:24:00', '2025-11-16 08:24:00'),
(434, 'facebookMoreService', 'facebookMoreServiceUpdate', 'web', '2025-11-16 08:24:00', '2025-11-16 08:24:00'),
(435, 'facebookPage', 'facebookPageView', 'web', '2025-11-16 08:24:23', '2025-11-16 08:24:23'),
(436, 'facebookPackage', 'facebookPackageView', 'web', '2025-11-16 08:25:10', '2025-11-16 08:25:10'),
(437, 'facebookPackage', 'facebookPackageAdd', 'web', '2025-11-16 08:25:10', '2025-11-16 08:25:10'),
(438, 'facebookPackage', 'facebookPackageDelete', 'web', '2025-11-16 08:25:10', '2025-11-16 08:25:10'),
(439, 'facebookPackage', 'facebookPackageUpdate', 'web', '2025-11-16 08:25:10', '2025-11-16 08:25:10'),
(441, 'ukPricingPackage', 'ukPricingPackageView', 'web', '2025-11-16 08:26:59', '2025-11-16 08:26:59'),
(442, 'ukPricingPackage', 'ukPricingPackageAdd', 'web', '2025-11-16 08:26:59', '2025-11-16 08:26:59'),
(443, 'ukPricingPackage', 'ukPricingPackageDelete', 'web', '2025-11-16 08:26:59', '2025-11-16 08:26:59'),
(444, 'ukPricingPackage', 'ukPricingPackageUpdate', 'web', '2025-11-16 08:26:59', '2025-11-16 08:26:59'),
(445, 'ukReviewPlatform', 'ukReviewPlatformView', 'web', '2025-11-16 08:27:41', '2025-11-16 08:27:41'),
(446, 'ukReviewPlatform', 'ukReviewPlatformDelete', 'web', '2025-11-16 08:27:41', '2025-11-16 08:27:41'),
(447, 'ukReviewPlatform', 'ukReviewPlatformUpdate', 'web', '2025-11-16 08:27:41', '2025-11-16 08:27:41'),
(448, 'ukReviewPlatform', 'ukReviewPlatformAdd', 'web', '2025-11-16 08:27:41', '2025-11-16 08:27:41'),
(449, 'ukTestimonial', 'ukTestimonialView', 'web', '2025-11-16 08:28:33', '2025-11-16 08:28:33'),
(450, 'ukTestimonial', 'ukTestimonialAdd', 'web', '2025-11-16 08:28:33', '2025-11-16 08:28:33'),
(451, 'ukTestimonial', 'ukTestimonialDelete', 'web', '2025-11-16 08:28:33', '2025-11-16 08:28:33'),
(452, 'ukTestimonial', 'ukTestimonialUpdate', 'web', '2025-11-16 08:28:33', '2025-11-16 08:28:33'),
(453, 'vpsCategory', 'vpsCategoryView', 'web', '2025-11-16 08:30:11', '2025-11-16 08:30:11'),
(454, 'vpsCategory', 'vpsCategoryAdd', 'web', '2025-11-16 08:30:11', '2025-11-16 08:30:11'),
(455, 'vpsCategory', 'vpsCategoryUpdate', 'web', '2025-11-16 08:30:11', '2025-11-16 08:30:11'),
(456, 'vpsCategory', 'vpsCategoryDelete', 'web', '2025-11-16 08:30:11', '2025-11-16 08:30:11'),
(457, 'vpsPackage', 'vpsPackageView', 'web', '2025-11-16 08:31:12', '2025-11-16 08:31:12'),
(458, 'vpsPackage', 'vpsPackageAdd', 'web', '2025-11-16 08:31:12', '2025-11-16 08:31:12'),
(459, 'vpsPackage', 'vpsPackageUpdate', 'web', '2025-11-16 08:31:12', '2025-11-16 08:31:12'),
(460, 'vpsPackage', 'vpsPackageDelete', 'web', '2025-11-16 08:31:12', '2025-11-16 08:31:12'),
(461, 'vpsPage', 'vpsPageView', 'web', '2025-11-16 08:31:33', '2025-11-16 08:31:33'),
(462, 'ukCompanyPage', 'ukCompanyPageView', 'web', '2025-11-16 08:36:00', '2025-11-16 08:36:00'),
(463, 'storeSideBanner', 'storeSideBannerView', 'web', '2025-11-16 10:03:14', '2025-11-16 10:03:14'),
(464, 'storeSideBanner', 'storeSideBannerAdd', 'web', '2025-11-16 10:03:14', '2025-11-16 10:03:14'),
(465, 'storeSideBanner', 'storeSideBannerDelete', 'web', '2025-11-16 10:03:14', '2025-11-16 10:03:14'),
(466, 'storeSideBanner', 'storeSideBannerUpdate', 'web', '2025-11-16 10:03:14', '2025-11-16 10:03:14'),
(467, 'storeMainBanner', 'storeMainBannerView', 'web', '2025-11-16 10:04:06', '2025-11-16 10:04:06'),
(468, 'storeMainBanner', 'storeMainBannerAdd', 'web', '2025-11-16 10:04:06', '2025-11-16 10:04:06'),
(469, 'storeMainBanner', 'storeMainBannerDelete', 'web', '2025-11-16 10:04:06', '2025-11-16 10:04:06'),
(470, 'storeMainBanner', 'storeMainBannerUpdate', 'web', '2025-11-16 10:04:06', '2025-11-16 10:04:06'),
(471, 'product', 'productAdd', 'web', '2025-11-16 23:15:10', '2025-11-16 23:15:10'),
(472, 'product', 'productView', 'web', '2025-11-16 23:15:10', '2025-11-16 23:15:10'),
(473, 'product', 'productDelete', 'web', '2025-11-16 23:15:10', '2025-11-16 23:15:10'),
(474, 'product', 'productUpdate', 'web', '2025-11-16 23:15:10', '2025-11-16 23:15:10'),
(475, 'order', 'orderAdd', 'web', '2025-11-20 04:14:31', '2025-11-20 04:14:31'),
(476, 'order', 'orderView', 'web', '2025-11-20 04:14:31', '2025-11-20 04:14:31'),
(477, 'order', 'orderDelete', 'web', '2025-11-20 04:14:31', '2025-11-20 04:14:31'),
(478, 'order', 'orderUpdate', 'web', '2025-11-20 04:14:31', '2025-11-20 04:14:31'),
(479, 'review', 'reviewAdd', 'web', '2025-11-20 04:21:35', '2025-11-20 04:21:35'),
(480, 'review', 'reviewView', 'web', '2025-11-20 04:21:35', '2025-11-20 04:21:35'),
(481, 'review', 'reviewDelete', 'web', '2025-11-20 04:21:35', '2025-11-20 04:21:35'),
(482, 'review', 'reviewUpdate', 'web', '2025-11-20 04:21:35', '2025-11-20 04:21:35'),
(483, 'coupon', 'couponAdd', 'web', '2025-11-20 04:27:15', '2025-11-20 04:27:15'),
(484, 'coupon', 'couponView', 'web', '2025-11-20 04:27:15', '2025-11-20 04:27:15'),
(485, 'coupon', 'couponDelete', 'web', '2025-11-20 04:27:15', '2025-11-20 04:27:15'),
(486, 'coupon', 'couponUpdate', 'web', '2025-11-20 04:27:15', '2025-11-20 04:27:15');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` text NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `sku` varchar(255) DEFAULT NULL COMMENT 'Stock Keeping Unit',
  `stock_quantity` int(11) NOT NULL DEFAULT 0,
  `buying_price` decimal(10,2) DEFAULT NULL,
  `selling_price` decimal(10,2) NOT NULL,
  `discount_price` decimal(10,2) DEFAULT NULL,
  `is_top_selling_product` tinyint(1) NOT NULL DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1=Active, 0=Inactive',
  `order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_packages`
--

CREATE TABLE `product_packages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `variation_name` varchar(255) NOT NULL,
  `additional_price` decimal(10,2) NOT NULL DEFAULT 0.00 COMMENT 'Added to product selling_price',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_reviews`
--

CREATE TABLE `product_reviews` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `rating` int(11) NOT NULL COMMENT '1 to 5',
  `review` text DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0=Pending, 1=Approved',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `publications`
--

CREATE TABLE `publications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `pdf_file` varchar(255) NOT NULL,
  `description` longtext DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `publications`
--

INSERT INTO `publications` (`id`, `title`, `date`, `pdf_file`, `description`, `image`, `created_at`, `updated_at`) VALUES
(3, 'A New Milestone in China-Bangladesh Economic Partnership', '2025-10-31', 'uploads/publications/pdfs/1761812463_69031fefb3c01.pdf', '<p>lorem ipsum</p>', 'public/uploads/publications/images/1761812306_69031f528c258.jpg', '2025-10-30 08:18:26', '2025-10-30 08:23:37');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'web', '2025-05-06 02:08:26', '2025-05-06 02:08:26'),
(2, 'shareHolder', 'web', '2025-08-28 22:49:05', '2025-08-28 22:49:05');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 1),
(7, 1),
(8, 1),
(9, 1),
(10, 1),
(11, 1),
(12, 1),
(13, 1),
(13, 2),
(14, 1),
(14, 2),
(15, 1),
(15, 2),
(20, 1),
(21, 1),
(22, 1),
(23, 1),
(24, 1),
(25, 1),
(26, 1),
(27, 1),
(30, 1),
(31, 1),
(32, 1),
(33, 1),
(106, 1),
(107, 1),
(108, 1),
(109, 1),
(118, 1),
(119, 1),
(120, 1),
(121, 1),
(122, 1),
(123, 1),
(124, 1),
(125, 1),
(170, 2),
(171, 2),
(172, 2),
(173, 2),
(208, 1),
(209, 1),
(210, 1),
(211, 1),
(230, 1),
(237, 1),
(238, 1),
(239, 1),
(240, 1),
(241, 1),
(242, 1),
(243, 1),
(244, 1),
(277, 1),
(278, 1),
(279, 1),
(280, 1),
(281, 1),
(282, 1),
(283, 1),
(284, 1),
(285, 1),
(286, 1),
(287, 1),
(288, 1),
(310, 1),
(311, 1),
(312, 1),
(313, 1),
(322, 1),
(323, 1),
(330, 1),
(331, 1),
(332, 1),
(333, 1),
(334, 1),
(335, 1),
(336, 1),
(337, 1),
(342, 1),
(343, 1),
(344, 1),
(345, 1),
(346, 1),
(347, 1),
(348, 1),
(349, 1),
(350, 1),
(351, 1),
(352, 1),
(353, 1),
(354, 1),
(355, 1),
(356, 1),
(357, 1),
(358, 1),
(359, 1),
(360, 1),
(361, 1),
(362, 1),
(363, 1),
(364, 1),
(365, 1),
(366, 1),
(367, 1),
(368, 1),
(369, 1),
(370, 1),
(371, 1),
(372, 1),
(373, 1),
(374, 1),
(375, 1),
(376, 1),
(377, 1),
(385, 1),
(386, 1),
(387, 1),
(388, 1),
(389, 1),
(390, 1),
(391, 1),
(392, 1),
(393, 1),
(394, 1),
(395, 1),
(396, 1),
(397, 1),
(398, 1),
(399, 1),
(400, 1),
(401, 1),
(402, 1),
(403, 1),
(404, 1),
(405, 1),
(406, 1),
(407, 1),
(408, 1),
(409, 1),
(410, 1),
(411, 1),
(412, 1),
(413, 1),
(414, 1),
(415, 1),
(416, 1),
(417, 1),
(418, 1),
(419, 1),
(420, 1),
(421, 1),
(422, 1),
(423, 1),
(424, 1),
(425, 1),
(426, 1),
(427, 1),
(428, 1),
(429, 1),
(430, 1),
(431, 1),
(432, 1),
(433, 1),
(434, 1),
(435, 1),
(436, 1),
(437, 1),
(438, 1),
(439, 1),
(441, 1),
(442, 1),
(443, 1),
(444, 1),
(445, 1),
(446, 1),
(447, 1),
(448, 1),
(449, 1),
(450, 1),
(451, 1),
(452, 1),
(453, 1),
(454, 1),
(455, 1),
(456, 1),
(457, 1),
(458, 1),
(459, 1),
(460, 1),
(461, 1),
(462, 1),
(463, 1),
(464, 1),
(465, 1),
(466, 1),
(467, 1),
(468, 1),
(469, 1),
(470, 1),
(471, 1),
(472, 1),
(473, 1),
(474, 1),
(475, 1),
(476, 1),
(477, 1),
(478, 1),
(479, 1),
(480, 1),
(481, 1),
(482, 1),
(483, 1),
(484, 1),
(485, 1),
(486, 1);

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('2AA2WYQItwbu6UiSoFL9ugkSnNqRVGqTvxliDAhp', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiUDBaWWY3ZVRiQWxPWVFYNDhtUVRpa0FXTEd3YUUzUld2QjZDUjBFMSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDM6Imh0dHA6Ly9sb2NhbGhvc3QvaWZ1c2hpb25BZG1pbi9hZG1pbi9jb3Vwb24iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO3M6NDoiYXV0aCI7YToxOntzOjIxOiJwYXNzd29yZF9jb25maXJtZWRfYXQiO2k6MTc2MzYzMzU4MDt9fQ==', 1763634548),
('6qttxYrf61deY3BIsfjFQyVCXXhnM7DVCEgLEpAI', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoic05CeURPVVNPUjlTVFdDN0dlVEttM21FZDRIOXA5dlFUUGhvem1RNSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly9sb2NhbGhvc3QvaWZ1c2hpb25BZG1pbi9ob21lIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTtzOjQ6ImF1dGgiO2E6MTp7czoyMToicGFzc3dvcmRfY29uZmlybWVkX2F0IjtpOjE3NjM2OTkzMTE7fX0=', 1763701031),
('g7f21whgpKq2IkmrRgNMkF9j23L1tEl8YTAZmYij', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiQkR3V1ljQmdCWFVmUE1JSzZDV3dRd0poYThNdzVRMTBZVFdBYzZuRiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDQ6Imh0dHA6Ly9sb2NhbGhvc3QvaWZ1c2hpb25BZG1pbi9hZG1pbi9wcm9kdWN0Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTtzOjQ6ImF1dGgiO2E6MTp7czoyMToicGFzc3dvcmRfY29uZmlybWVkX2F0IjtpOjE3NjMzNTY0MTk7fX0=', 1763356668),
('rxYzJ43Sq7sIKflENyRapSUbwVY5PbHYKayCLKnU', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiTmpJRlcxYTJvWkZweFY3aGJFd3FaOHowcjVFNW05TnNneXJXREZRTiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDQ6Imh0dHA6Ly9sb2NhbGhvc3QvaWZ1c2hpb25BZG1pbi9hZG1pbi9wcm9kdWN0Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTtzOjQ6ImF1dGgiO2E6MTp7czoyMToicGFzc3dvcmRfY29uZmlybWVkX2F0IjtpOjE3NjM2MTI1NDY7fX0=', 1763612649);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `key` varchar(255) NOT NULL,
  `value` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `key`, `value`, `created_at`, `updated_at`) VALUES
(1, 'header_color', '#2e433f', '2025-08-12 05:02:07', '2025-08-12 05:02:07'),
(2, 'menu_limit', '5', '2025-08-12 05:02:07', '2025-08-12 05:02:07');

-- --------------------------------------------------------

--
-- Table structure for table `sidebar_menus`
--

CREATE TABLE `sidebar_menus` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `route` varchar(255) DEFAULT NULL,
  `order` int(11) NOT NULL DEFAULT 0,
  `is_visible` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sidebar_menus`
--

INSERT INTO `sidebar_menus` (`id`, `name`, `route`, `order`, `is_visible`, `created_at`, `updated_at`) VALUES
(1, 'Shirt Collection', '/category/shirt-collection', 0, 1, '2025-08-12 19:02:26', '2025-08-12 19:03:29'),
(2, 'Bottom Wear', '/category/bottom-wear', 2, 0, '2025-08-12 19:02:27', '2025-08-12 19:03:31'),
(3, 'Winter Collection', '/category/winter-collection', 3, 0, '2025-08-12 19:02:28', '2025-08-12 19:03:31'),
(4, 'Denim Collection', '/category/denim-collection', 4, 1, '2025-08-12 19:02:29', '2025-08-12 19:03:31'),
(5, 'T-Shirt Collection', '/category/t-shirt-collection', 1, 1, '2025-08-12 19:02:29', '2025-08-12 19:03:30'),
(6, 'Jersey Collection', '/category/jersey-collection', 5, 1, '2025-08-12 19:02:29', '2025-08-12 19:03:31'),
(7, 'Kids Collection', '/category/kids-collection', 6, 0, '2025-08-12 19:02:29', '2025-08-12 19:03:32'),
(8, 'Panjabi Collection', '/category/panjabi-collection', 7, 0, '2025-08-12 19:02:30', '2025-08-12 19:03:32'),
(9, 'Women Collection', '/category/women-collection', 8, 0, '2025-08-12 19:02:30', '2025-08-12 19:03:32'),
(10, 'Shoes Collection', '/category/shoes-collection', 9, 0, '2025-08-12 19:02:30', '2025-08-12 19:03:32'),
(11, 'Jewelry & Accessories', '/category/jewelry-accessories', 10, 0, '2025-08-12 19:02:31', '2025-08-12 19:03:33'),
(12, 'Cosplay Products', '/category/cosplay-products', 11, 1, '2025-08-12 19:02:31', '2025-08-12 19:03:33'),
(13, 'Others', '/category/others', 12, 0, '2025-08-12 19:02:31', '2025-08-12 19:03:33');

-- --------------------------------------------------------

--
-- Table structure for table `site_settings`
--

CREATE TABLE `site_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `key` varchar(255) NOT NULL,
  `value` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `site_settings`
--

INSERT INTO `site_settings` (`id`, `key`, `value`, `created_at`, `updated_at`) VALUES
(1, 'nav_home', 'Home', '2025-11-01 09:14:52', '2025-11-02 09:14:11'),
(2, 'nav_about_iifc', 'About IIFC', '2025-11-01 09:14:53', '2025-11-02 09:14:11'),
(3, 'nav_services', 'Services', '2025-11-01 09:14:53', '2025-11-02 09:14:11'),
(4, 'nav_projects', 'Projects', '2025-11-01 09:14:53', '2025-11-02 09:14:11'),
(5, 'nav_training', 'Training', '2025-11-01 09:14:53', '2025-11-02 09:14:11'),
(6, 'nav_resources', 'Resources', '2025-11-01 09:14:53', '2025-11-02 09:14:11'),
(7, 'nav_notice', 'Notice', '2025-11-01 09:14:54', '2025-11-02 09:14:11'),
(8, 'nav_upcomming_training', 'Upcoming Training', '2025-11-01 09:14:54', '2025-11-02 09:14:11'),
(9, 'nav_all_training', 'All Training', '2025-11-01 09:14:54', '2025-11-02 09:14:11'),
(10, 'nav_about_us', 'About Us', '2025-11-01 09:14:54', '2025-11-02 09:14:11'),
(11, 'nav_board', 'Board Of Directors', '2025-11-01 09:14:55', '2025-11-02 09:14:11'),
(12, 'nav_subscriber', 'Subscriber Members', '2025-11-01 09:14:55', '2025-11-02 09:14:11'),
(13, 'nav_experts', 'Our Experts', '2025-11-01 09:14:55', '2025-11-02 09:14:11'),
(14, 'nav_officers', 'Officers', '2025-11-01 09:14:55', '2025-11-02 09:14:11'),
(15, 'nav_past-chairmen', 'Past Chairmen', '2025-11-01 09:14:55', '2025-11-02 09:13:06'),
(16, 'nav_past-mds', 'Past Mds', '2025-11-01 09:14:56', '2025-11-02 11:25:42'),
(17, 'nav_contact_us', 'Contact Us', '2025-11-01 09:14:56', '2025-11-02 09:14:11'),
(18, 'nav_career', 'Career', '2025-11-01 09:14:56', '2025-11-02 09:14:11'),
(19, 'nav_publication', 'Publications', '2025-11-01 09:14:56', '2025-11-02 09:14:17'),
(20, 'nav_press-release', 'Press Release', '2025-11-01 09:14:56', '2025-11-02 09:14:11'),
(21, 'nav_events', 'Events', '2025-11-01 09:14:56', '2025-11-02 11:25:42'),
(22, 'nav_gallery', 'Gallery', '2025-11-01 09:14:56', '2025-11-02 09:14:11'),
(23, 'nav_download', 'Download', '2025-11-01 09:14:56', '2025-11-02 09:14:11');

-- --------------------------------------------------------

--
-- Table structure for table `sliders`
--

CREATE TABLE `sliders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `subtitle` varchar(255) DEFAULT NULL,
  `short_description` text DEFAULT NULL,
  `image` varchar(255) NOT NULL,
  `display_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sliders`
--

INSERT INTO `sliders` (`id`, `title`, `subtitle`, `short_description`, `image`, `display_order`, `created_at`, `updated_at`) VALUES
(1, 'Dhaka Elevated Expressway PPP Project', NULL, 'The first-ever successful PPP project in Bangladesh, with IIFC serving as the Transaction Adviser.', 'public/uploads/sliders/1761756944_69024710ad92b.jpg', 1, '2025-10-21 20:01:23', '2025-11-06 00:03:56'),
(2, 'Matamohuri Bridge 3D at Chattagram', NULL, 'Detailed design and 3D model prepared by IIFC under the Roads and Highways Department (RHD).', 'public/uploads/sliders/1761755971_69024343396ed.jpeg', 2, '2025-10-24 05:34:48', '2025-11-06 00:03:56'),
(3, 'BRTA Testing, Training & Multipurpose Center 3D Model', NULL, 'Bangladesh Road Transport Authority (BRTA) Office cum Motor Driving Testing, Training & Multipurpose Center (BMDTTMC) at Mymensingh — 3D model developed by IIFC.', 'public/uploads/sliders/1761756008_69024368c0c5c.jpg', 3, '2025-10-24 05:36:29', '2025-11-06 00:03:56'),
(4, 'Birol Railway Station 3D Model', NULL, 'Birol Railway Station, located near Birol Land Port under Bangladesh Railway — 3D model developed by IIFC.', 'public/uploads/sliders/1762053225_6906cc6946ac3.png', 4, '2025-10-29 16:40:37', '2025-11-06 00:03:56');

-- --------------------------------------------------------

--
-- Table structure for table `social_links`
--

CREATE TABLE `social_links` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `link` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `social_links`
--

INSERT INTO `social_links` (`id`, `title`, `link`, `created_at`, `updated_at`) VALUES
(1, 'Facebook', 'https://www.facebook.com/iifcbd', '2025-10-13 08:54:16', '2025-11-02 04:15:55'),
(2, 'YouTube', 'https://www.youtube.com/', '2025-10-13 08:54:29', '2025-10-13 08:54:29'),
(4, 'LinkedIn', 'https://www.linkedin.com/in/iifcbdofficial/', '2025-10-13 08:55:07', '2025-11-02 04:16:08');

-- --------------------------------------------------------

--
-- Table structure for table `solutions`
--

CREATE TABLE `solutions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `solutions`
--

INSERT INTO `solutions` (`id`, `name`, `image`, `created_at`, `updated_at`) VALUES
(1, 'Digital Marketing', 'public/uploads/solutions/1763196753_69183f51b5927.png', '2025-11-15 02:52:34', '2025-11-15 02:52:34');

-- --------------------------------------------------------

--
-- Table structure for table `store_main_banners`
--

CREATE TABLE `store_main_banners` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `image` varchar(255) NOT NULL,
  `link` varchar(255) NOT NULL DEFAULT '#',
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1=Active, 0=Inactive',
  `order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `store_side_banners`
--

CREATE TABLE `store_side_banners` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `top_image` varchar(255) DEFAULT NULL,
  `top_link` varchar(255) NOT NULL DEFAULT '#',
  `bottom_image` varchar(255) DEFAULT NULL,
  `bottom_link` varchar(255) NOT NULL DEFAULT '#',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `system_information`
--

CREATE TABLE `system_information` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ins_name` varchar(255) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `rectangular_logo` varchar(255) DEFAULT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `address_two` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `email_two` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `phone_two` varchar(20) DEFAULT NULL,
  `main_url` varchar(255) DEFAULT NULL,
  `front_url` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `develop_by` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `system_information`
--

INSERT INTO `system_information` (`id`, `ins_name`, `logo`, `rectangular_logo`, `icon`, `address`, `address_two`, `email`, `email_two`, `phone`, `phone_two`, `main_url`, `front_url`, `description`, `develop_by`, `created_at`, `updated_at`) VALUES
(1, 'OPTIFUSION INC', 'public/uploads/logo_176318476620251115.png', 'public/uploads/rect_logo_176318826820251115.png', 'public/uploads/icon_176318477320251115.png', 'dhaka,bd', NULL, 'support@optifusion.com', NULL, '+1 234 567 890', NULL, 'http://localhost/ifushionAdmin/', 'https://iifc.resnova.dev/', 'We are dedicated to providing the best products with the best service. Our mission is to bring joy to our customers through a seamless shopping experience.', 'Kamruzzaman', '2025-05-07 10:43:40', '2025-11-15 00:31:08');

-- --------------------------------------------------------

--
-- Table structure for table `teams`
--

CREATE TABLE `teams` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `designation` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `top_header_links`
--

CREATE TABLE `top_header_links` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `link` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `top_header_links`
--

INSERT INTO `top_header_links` (`id`, `title`, `link`, `created_at`, `updated_at`) VALUES
(1, 'Outsource', 'https://iifc.resnova.dev/', '2025-11-01 23:43:50', '2025-11-01 23:43:50'),
(2, 'Accounts', 'https://iifc.resnova.dev/', '2025-11-01 23:43:50', '2025-11-01 23:43:50');

-- --------------------------------------------------------

--
-- Table structure for table `uk_company_page`
--

CREATE TABLE `uk_company_page` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `hero_subtitle_top` varchar(255) NOT NULL,
  `hero_title` varchar(255) NOT NULL,
  `hero_description` text DEFAULT NULL,
  `hero_button_text` varchar(255) NOT NULL,
  `hero_button_link` varchar(255) NOT NULL DEFAULT '#',
  `hero_image` varchar(255) DEFAULT NULL,
  `carbon_badge_text` varchar(255) NOT NULL DEFAULT 'We Are Proud To Be A Certified Carbon Neutral Business 2024',
  `pricing_title` varchar(255) NOT NULL DEFAULT 'Quick Guide To Our Company Formation Packages',
  `pricing_description` text DEFAULT NULL,
  `testimonial_title` varchar(255) NOT NULL DEFAULT 'What Our Customers Say',
  `review_title` varchar(255) NOT NULL DEFAULT 'What Our Customers Say',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `uk_pricing_packages`
--

CREATE TABLE `uk_pricing_packages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `price` varchar(255) NOT NULL,
  `features` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`features`)),
  `button_text` varchar(255) NOT NULL DEFAULT 'Order Now',
  `button_link` varchar(255) NOT NULL DEFAULT '#',
  `order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `uk_review_platforms`
--

CREATE TABLE `uk_review_platforms` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `rating_text` varchar(255) NOT NULL,
  `review_link` varchar(255) NOT NULL DEFAULT '#',
  `order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `uk_testimonials`
--

CREATE TABLE `uk_testimonials` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `designation` varchar(255) DEFAULT NULL,
  `quote` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `rating` int(11) NOT NULL DEFAULT 5,
  `order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` int(20) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `department_id` varchar(255) DEFAULT NULL,
  `designation_id` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `signature` varchar(255) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `user_type` varchar(255) DEFAULT NULL,
  `viewpassword` varchar(255) DEFAULT NULL,
  `dob` varchar(255) DEFAULT NULL,
  `gender` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `customer_id`, `name`, `department_id`, `designation_id`, `image`, `phone`, `address`, `email`, `signature`, `email_verified_at`, `password`, `remember_token`, `status`, `user_type`, `viewpassword`, `dob`, `gender`, `created_at`, `updated_at`) VALUES
(1, NULL, 'super admin', '1', NULL, NULL, '01646735111', 'Rajshahi', 'admin@gmail.com', NULL, NULL, '$2y$12$m.Z15yjiQaCiSjT25OpTBuaDU7JO31KkejBPnjaRUItIt1Wm2KPVK', NULL, NULL, '2', NULL, NULL, NULL, '2025-05-06 02:08:26', '2025-05-07 01:41:35'),
(2, NULL, 'admin', '1', '1', 'public/uploads/profileImage175504739720250813user1.png', '0', 'Rajshahi', 'adminOne@gmail.com', NULL, NULL, '$2y$12$Gin3dLC8l0Qoi7kS/oSvHexK2ZdYKVtjP5nbS1AAkygfqKfQ89JW2', NULL, '1', '2', '123456', NULL, NULL, '2025-05-08 05:54:51', '2025-08-12 19:10:47'),
(3, NULL, 'test customer', NULL, NULL, NULL, NULL, NULL, 'testCustomer@gmail.com', NULL, NULL, '$2y$12$tckTUyu1fszGbz8pSthMkugM4aWNWkaj0M7FcWYZncgYs8aOfVvf2', NULL, NULL, '2', NULL, NULL, NULL, '2025-08-14 02:34:53', '2025-08-14 02:34:53'),
(3857, NULL, 'Kamruzzaman kajol', '2', '1', NULL, '016467351087', 'Rajshahi', 'admind@gmail.com', NULL, NULL, '$2y$12$Azl9S59vH8E9GLdqjuzeZ.o5VM3ifpwgSt1XzfdZoLdnVKaqZcxtK', NULL, '1', '2', '12345678', NULL, NULL, '2025-10-10 04:49:41', '2025-10-10 04:49:41'),
(3858, 7602, 'Kamruzzaman kajol', NULL, NULL, NULL, '01646735100', NULL, 'wc@gmail.com', NULL, '2025-10-15 02:42:54', '$2y$12$0ez0zoGRM1lDBRsCDQkmfu4VTSigYpea0p9x7adcev/wkNtoKSzXS', NULL, '1', '1', '12345678', NULL, NULL, '2025-10-15 02:42:54', '2025-10-15 02:42:55');

-- --------------------------------------------------------

--
-- Table structure for table `vps_packages`
--

CREATE TABLE `vps_packages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `price` varchar(255) NOT NULL,
  `price_subtitle` varchar(255) NOT NULL DEFAULT 'Starting at',
  `features` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`features`)),
  `button_text` varchar(255) NOT NULL DEFAULT 'Buy Now',
  `button_link` varchar(255) NOT NULL DEFAULT '#',
  `is_stocked_out` tinyint(1) NOT NULL DEFAULT 0,
  `order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vps_package_categories`
--

CREATE TABLE `vps_package_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vps_page`
--

CREATE TABLE `vps_page` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `hero_title` varchar(255) NOT NULL DEFAULT 'Cheapest RDP/VPS',
  `hero_features` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`hero_features`)),
  `hero_button_text` varchar(255) NOT NULL DEFAULT 'Get Now',
  `hero_button_link` varchar(255) NOT NULL DEFAULT '#',
  `hero_image` varchar(255) DEFAULT NULL,
  `category_1_title` varchar(255) NOT NULL DEFAULT 'Browser RDP',
  `category_2_title` varchar(255) NOT NULL DEFAULT 'Starter RDP Plan',
  `category_3_title` varchar(255) NOT NULL DEFAULT 'Private RDP Plan',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `web_solution_care_items`
--

CREATE TABLE `web_solution_care_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `image` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `button_text` varchar(255) NOT NULL DEFAULT 'BOOK NOW',
  `button_link` varchar(255) NOT NULL DEFAULT '#',
  `order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `web_solution_checklists`
--

CREATE TABLE `web_solution_checklists` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `web_solution_includes`
--

CREATE TABLE `web_solution_includes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `icon_name` varchar(255) NOT NULL DEFAULT 'mdi:check-circle',
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `web_solution_page`
--

CREATE TABLE `web_solution_page` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `hero_title` varchar(255) DEFAULT NULL,
  `hero_description` text DEFAULT NULL,
  `hero_button_text` varchar(255) DEFAULT NULL,
  `intro_image` varchar(255) DEFAULT NULL,
  `intro_title` varchar(255) DEFAULT NULL,
  `intro_description` text DEFAULT NULL,
  `intro_button_text` varchar(255) DEFAULT NULL,
  `pro_title` varchar(255) DEFAULT NULL,
  `pro_description` text DEFAULT NULL,
  `pro_button_text` varchar(255) DEFAULT NULL,
  `checklist_title` varchar(255) DEFAULT NULL,
  `checklist_description` text DEFAULT NULL,
  `includes_subtitle` varchar(255) DEFAULT NULL,
  `includes_title` varchar(255) DEFAULT NULL,
  `includes_description` text DEFAULT NULL,
  `providing_subtitle` varchar(255) DEFAULT NULL,
  `providing_title` varchar(255) DEFAULT NULL,
  `providing_description` text DEFAULT NULL,
  `work_subtitle` varchar(255) DEFAULT NULL,
  `work_title` varchar(255) DEFAULT NULL,
  `work_description` text DEFAULT NULL,
  `cta_title` varchar(255) DEFAULT NULL,
  `cta_description` text DEFAULT NULL,
  `cta_button_text` varchar(255) DEFAULT NULL,
  `care_subtitle` varchar(255) DEFAULT NULL,
  `care_title` varchar(255) DEFAULT NULL,
  `care_description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `web_solution_providings`
--

CREATE TABLE `web_solution_providings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `image` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `button_text` varchar(255) NOT NULL DEFAULT 'Order Now',
  `button_link` varchar(255) NOT NULL DEFAULT '#',
  `order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `web_solution_work_categories`
--

CREATE TABLE `web_solution_work_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `web_solution_work_items`
--

CREATE TABLE `web_solution_work_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `image` varchar(255) NOT NULL,
  `visit_link` varchar(255) NOT NULL DEFAULT '#',
  `order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `why_choose_us`
--

CREATE TABLE `why_choose_us` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `why_us`
--

CREATE TABLE `why_us` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `about_us`
--
ALTER TABLE `about_us`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_slug_unique` (`slug`),
  ADD KEY `categories_parent_id_foreign` (`parent_id`);

--
-- Indexes for table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `client_says`
--
ALTER TABLE `client_says`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contact_us_messages`
--
ALTER TABLE `contact_us_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `countries_name_unique` (`name`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `customers_email_unique` (`email`);

--
-- Indexes for table `customer_addresses`
--
ALTER TABLE `customer_addresses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_addresses_customer_id_foreign` (`customer_id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `department_infos`
--
ALTER TABLE `department_infos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `department_infos_officer_id_foreign` (`officer_id`),
  ADD KEY `department_infos_designation_id_foreign` (`designation_id`),
  ADD KEY `department_infos_department_id_foreign` (`department_id`);

--
-- Indexes for table `designations`
--
ALTER TABLE `designations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `digital_marketing_growth_items`
--
ALTER TABLE `digital_marketing_growth_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `digital_marketing_growth_items_order_index` (`order`);

--
-- Indexes for table `digital_marketing_page`
--
ALTER TABLE `digital_marketing_page`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `digital_marketing_solutions`
--
ALTER TABLE `digital_marketing_solutions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `digital_marketing_solutions_order_index` (`order`);

--
-- Indexes for table `downloads`
--
ALTER TABLE `downloads`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `events_slug_unique` (`slug`);

--
-- Indexes for table `extra_pages`
--
ALTER TABLE `extra_pages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `facebook_ads_campaigns`
--
ALTER TABLE `facebook_ads_campaigns`
  ADD PRIMARY KEY (`id`),
  ADD KEY `facebook_ads_campaigns_order_index` (`order`);

--
-- Indexes for table `facebook_ads_faqs`
--
ALTER TABLE `facebook_ads_faqs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `facebook_ads_faqs_order_index` (`order`);

--
-- Indexes for table `facebook_ads_features`
--
ALTER TABLE `facebook_ads_features`
  ADD PRIMARY KEY (`id`),
  ADD KEY `facebook_ads_features_order_index` (`order`);

--
-- Indexes for table `facebook_ads_page`
--
ALTER TABLE `facebook_ads_page`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `facebook_ads_pricing_categories`
--
ALTER TABLE `facebook_ads_pricing_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `facebook_ads_pricing_categories_slug_unique` (`slug`),
  ADD KEY `facebook_ads_pricing_categories_order_index` (`order`);

--
-- Indexes for table `facebook_ads_pricing_packages`
--
ALTER TABLE `facebook_ads_pricing_packages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `facebook_ads_pricing_packages_category_id_foreign` (`category_id`),
  ADD KEY `facebook_ads_pricing_packages_order_index` (`order`);

--
-- Indexes for table `facebook_more_services`
--
ALTER TABLE `facebook_more_services`
  ADD PRIMARY KEY (`id`),
  ADD KEY `facebook_more_services_order_index` (`order`);

--
-- Indexes for table `facebook_page`
--
ALTER TABLE `facebook_page`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `facebook_pricing_packages`
--
ALTER TABLE `facebook_pricing_packages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `facebook_pricing_packages_order_index` (`order`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `featured_categories`
--
ALTER TABLE `featured_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `featured_categories_key_unique` (`key`);

--
-- Indexes for table `galleries`
--
ALTER TABLE `galleries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `graphic_design_checklists`
--
ALTER TABLE `graphic_design_checklists`
  ADD PRIMARY KEY (`id`),
  ADD KEY `graphic_design_checklists_order_index` (`order`);

--
-- Indexes for table `graphic_design_page`
--
ALTER TABLE `graphic_design_page`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `graphic_design_solutions`
--
ALTER TABLE `graphic_design_solutions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `graphic_design_solutions_order_index` (`order`);

--
-- Indexes for table `hero_sections`
--
ALTER TABLE `hero_sections`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `iifc_strengths`
--
ALTER TABLE `iifc_strengths`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `important_links`
--
ALTER TABLE `important_links`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_applicants`
--
ALTER TABLE `job_applicants`
  ADD PRIMARY KEY (`id`),
  ADD KEY `job_applicants_job_id_foreign` (`job_id`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `log_activities`
--
ALTER TABLE `log_activities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `main_sliders`
--
ALTER TABLE `main_sliders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `media`
--
ALTER TABLE `media`
  ADD PRIMARY KEY (`id`),
  ADD KEY `media_order_index` (`order`);

--
-- Indexes for table `menu_items`
--
ALTER TABLE `menu_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `orders_order_number_unique` (`order_number`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_items_order_id_foreign` (`order_id`),
  ADD KEY `order_items_product_id_foreign` (`product_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  ADD KEY `personal_access_tokens_expires_at_index` (`expires_at`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `products_slug_unique` (`slug`),
  ADD UNIQUE KEY `products_sku_unique` (`sku`),
  ADD KEY `products_category_id_foreign` (`category_id`),
  ADD KEY `products_order_index` (`order`);

--
-- Indexes for table `product_packages`
--
ALTER TABLE `product_packages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_packages_product_id_foreign` (`product_id`);

--
-- Indexes for table `product_reviews`
--
ALTER TABLE `product_reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_reviews_user_id_foreign` (`user_id`),
  ADD KEY `product_reviews_product_id_foreign` (`product_id`);

--
-- Indexes for table `publications`
--
ALTER TABLE `publications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `settings_key_unique` (`key`);

--
-- Indexes for table `sidebar_menus`
--
ALTER TABLE `sidebar_menus`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `site_settings`
--
ALTER TABLE `site_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `site_settings_key_unique` (`key`);

--
-- Indexes for table `sliders`
--
ALTER TABLE `sliders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `social_links`
--
ALTER TABLE `social_links`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `solutions`
--
ALTER TABLE `solutions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `store_main_banners`
--
ALTER TABLE `store_main_banners`
  ADD PRIMARY KEY (`id`),
  ADD KEY `store_main_banners_order_index` (`order`);

--
-- Indexes for table `store_side_banners`
--
ALTER TABLE `store_side_banners`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `system_information`
--
ALTER TABLE `system_information`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `teams`
--
ALTER TABLE `teams`
  ADD PRIMARY KEY (`id`),
  ADD KEY `teams_order_index` (`order`);

--
-- Indexes for table `top_header_links`
--
ALTER TABLE `top_header_links`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `uk_company_page`
--
ALTER TABLE `uk_company_page`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `uk_pricing_packages`
--
ALTER TABLE `uk_pricing_packages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uk_pricing_packages_order_index` (`order`);

--
-- Indexes for table `uk_review_platforms`
--
ALTER TABLE `uk_review_platforms`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uk_review_platforms_order_index` (`order`);

--
-- Indexes for table `uk_testimonials`
--
ALTER TABLE `uk_testimonials`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uk_testimonials_order_index` (`order`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `vps_packages`
--
ALTER TABLE `vps_packages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vps_packages_category_id_foreign` (`category_id`),
  ADD KEY `vps_packages_order_index` (`order`);

--
-- Indexes for table `vps_package_categories`
--
ALTER TABLE `vps_package_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vps_package_categories_order_index` (`order`);

--
-- Indexes for table `vps_page`
--
ALTER TABLE `vps_page`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `web_solution_care_items`
--
ALTER TABLE `web_solution_care_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `web_solution_care_items_order_index` (`order`);

--
-- Indexes for table `web_solution_checklists`
--
ALTER TABLE `web_solution_checklists`
  ADD PRIMARY KEY (`id`),
  ADD KEY `web_solution_checklists_order_index` (`order`);

--
-- Indexes for table `web_solution_includes`
--
ALTER TABLE `web_solution_includes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `web_solution_includes_order_index` (`order`);

--
-- Indexes for table `web_solution_page`
--
ALTER TABLE `web_solution_page`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `web_solution_providings`
--
ALTER TABLE `web_solution_providings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `web_solution_providings_order_index` (`order`);

--
-- Indexes for table `web_solution_work_categories`
--
ALTER TABLE `web_solution_work_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `web_solution_work_categories_slug_unique` (`slug`),
  ADD KEY `web_solution_work_categories_order_index` (`order`);

--
-- Indexes for table `web_solution_work_items`
--
ALTER TABLE `web_solution_work_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `web_solution_work_items_category_id_foreign` (`category_id`),
  ADD KEY `web_solution_work_items_order_index` (`order`);

--
-- Indexes for table `why_choose_us`
--
ALTER TABLE `why_choose_us`
  ADD PRIMARY KEY (`id`),
  ADD KEY `why_choose_us_order_index` (`order`);

--
-- Indexes for table `why_us`
--
ALTER TABLE `why_us`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `about_us`
--
ALTER TABLE `about_us`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `clients`
--
ALTER TABLE `clients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=262;

--
-- AUTO_INCREMENT for table `client_says`
--
ALTER TABLE `client_says`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contact_us_messages`
--
ALTER TABLE `contact_us_messages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `countries`
--
ALTER TABLE `countries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=201;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7603;

--
-- AUTO_INCREMENT for table `customer_addresses`
--
ALTER TABLE `customer_addresses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `department_infos`
--
ALTER TABLE `department_infos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=195;

--
-- AUTO_INCREMENT for table `designations`
--
ALTER TABLE `designations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `digital_marketing_growth_items`
--
ALTER TABLE `digital_marketing_growth_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `digital_marketing_page`
--
ALTER TABLE `digital_marketing_page`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `digital_marketing_solutions`
--
ALTER TABLE `digital_marketing_solutions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `downloads`
--
ALTER TABLE `downloads`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `extra_pages`
--
ALTER TABLE `extra_pages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `facebook_ads_campaigns`
--
ALTER TABLE `facebook_ads_campaigns`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `facebook_ads_faqs`
--
ALTER TABLE `facebook_ads_faqs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `facebook_ads_features`
--
ALTER TABLE `facebook_ads_features`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `facebook_ads_page`
--
ALTER TABLE `facebook_ads_page`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `facebook_ads_pricing_categories`
--
ALTER TABLE `facebook_ads_pricing_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `facebook_ads_pricing_packages`
--
ALTER TABLE `facebook_ads_pricing_packages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `facebook_more_services`
--
ALTER TABLE `facebook_more_services`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `facebook_page`
--
ALTER TABLE `facebook_page`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `facebook_pricing_packages`
--
ALTER TABLE `facebook_pricing_packages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `featured_categories`
--
ALTER TABLE `featured_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `galleries`
--
ALTER TABLE `galleries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `graphic_design_checklists`
--
ALTER TABLE `graphic_design_checklists`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `graphic_design_page`
--
ALTER TABLE `graphic_design_page`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `graphic_design_solutions`
--
ALTER TABLE `graphic_design_solutions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hero_sections`
--
ALTER TABLE `hero_sections`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `iifc_strengths`
--
ALTER TABLE `iifc_strengths`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `important_links`
--
ALTER TABLE `important_links`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `job_applicants`
--
ALTER TABLE `job_applicants`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `log_activities`
--
ALTER TABLE `log_activities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1476;

--
-- AUTO_INCREMENT for table `main_sliders`
--
ALTER TABLE `main_sliders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `media`
--
ALTER TABLE `media`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `menu_items`
--
ALTER TABLE `menu_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=203;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=487;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_packages`
--
ALTER TABLE `product_packages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_reviews`
--
ALTER TABLE `product_reviews`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `publications`
--
ALTER TABLE `publications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `sidebar_menus`
--
ALTER TABLE `sidebar_menus`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `site_settings`
--
ALTER TABLE `site_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `sliders`
--
ALTER TABLE `sliders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `social_links`
--
ALTER TABLE `social_links`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `solutions`
--
ALTER TABLE `solutions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `store_main_banners`
--
ALTER TABLE `store_main_banners`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `store_side_banners`
--
ALTER TABLE `store_side_banners`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `system_information`
--
ALTER TABLE `system_information`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `teams`
--
ALTER TABLE `teams`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `top_header_links`
--
ALTER TABLE `top_header_links`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `uk_company_page`
--
ALTER TABLE `uk_company_page`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `uk_pricing_packages`
--
ALTER TABLE `uk_pricing_packages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `uk_review_platforms`
--
ALTER TABLE `uk_review_platforms`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `uk_testimonials`
--
ALTER TABLE `uk_testimonials`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3859;

--
-- AUTO_INCREMENT for table `vps_packages`
--
ALTER TABLE `vps_packages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vps_package_categories`
--
ALTER TABLE `vps_package_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vps_page`
--
ALTER TABLE `vps_page`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `web_solution_care_items`
--
ALTER TABLE `web_solution_care_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `web_solution_checklists`
--
ALTER TABLE `web_solution_checklists`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `web_solution_includes`
--
ALTER TABLE `web_solution_includes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `web_solution_page`
--
ALTER TABLE `web_solution_page`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `web_solution_providings`
--
ALTER TABLE `web_solution_providings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `web_solution_work_categories`
--
ALTER TABLE `web_solution_work_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `web_solution_work_items`
--
ALTER TABLE `web_solution_work_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `why_choose_us`
--
ALTER TABLE `why_choose_us`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `why_us`
--
ALTER TABLE `why_us`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `department_infos`
--
ALTER TABLE `department_infos`
  ADD CONSTRAINT `department_infos_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `department_infos_designation_id_foreign` FOREIGN KEY (`designation_id`) REFERENCES `designations` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `department_infos_officer_id_foreign` FOREIGN KEY (`officer_id`) REFERENCES `officers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `facebook_ads_pricing_packages`
--
ALTER TABLE `facebook_ads_pricing_packages`
  ADD CONSTRAINT `facebook_ads_pricing_packages_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `facebook_ads_pricing_categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `job_applicants`
--
ALTER TABLE `job_applicants`
  ADD CONSTRAINT `job_applicants_job_id_foreign` FOREIGN KEY (`job_id`) REFERENCES `careers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `product_packages`
--
ALTER TABLE `product_packages`
  ADD CONSTRAINT `product_packages_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_reviews`
--
ALTER TABLE `product_reviews`
  ADD CONSTRAINT `product_reviews_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_reviews_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vps_packages`
--
ALTER TABLE `vps_packages`
  ADD CONSTRAINT `vps_packages_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `vps_package_categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `web_solution_work_items`
--
ALTER TABLE `web_solution_work_items`
  ADD CONSTRAINT `web_solution_work_items_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `web_solution_work_categories` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
