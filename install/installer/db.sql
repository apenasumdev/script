-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 18, 2020 at 02:49 PM
-- Server version: 5.7.24-log
-- PHP Version: 7.2.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db`
--

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

DROP TABLE IF EXISTS `$PREFIX$pages`;
CREATE TABLE `$PREFIX$pages` (
  `id` int(11) NOT NULL,
  `title` text CHARACTER SET utf8mb4 NOT NULL,
  `slug` text NOT NULL,
  `excerpt` mediumtext NOT NULL,
  `body` longtext CHARACTER SET utf8mb4 NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pages`
--

INSERT INTO `$PREFIX$pages` (`id`, `title`, `slug`, `excerpt`, `body`) VALUES
(1, 'How to download TikTok Videos ?', 'how-to-download', 'It is one of the best TikTok Downloaders available online to download TikTok videos without a watermark. You are not required to install any software on your computer...', '<p class=\"ql-align-justify\">It is one of the best TikTok Downloaders available online to download TikTok videos without a watermark. You are not required to install any software on your computer or mobile phone, all that you need is a TikTok video link, and all the processing is done on our side so you can be one click away from downloading videos to your devices.</p><h3 class=\"ql-align-justify\">Key features:</h3><ul><li class=\"ql-align-justify\">No watermark for better quality, which most of the tools out there can\'t.</li><li class=\"ql-align-justify\">Download TikTok videos, Musically videos on any devices that you want: mobile, PC, or tablet. TikTok only allows users to download videos by its application and downloaded videos contain the watermark.</li><li class=\"ql-align-justify\">Download by using your browsers: We want to keep things simple for you. No need to download or install any software. We make an application for this purpose as well but you can only install whenever you like.</li><li class=\"ql-align-justify\">It\'s always free. We only place some ads, which support maintaining our services, and further development.</li></ul><h3 class=\"ql-align-justify\">How to download TikTok Videos without a watermark?</h3><ol><li class=\"ql-align-justify\">Open the TikTok application on your phone.</li><li class=\"ql-align-justify\">Choose whatever video you want to download.</li><li class=\"ql-align-justify\">Click to the&nbsp;<strong>Share</strong>&nbsp;button at the right bottom.</li><li class=\"ql-align-justify\">Click the&nbsp;<strong>Copy Link</strong>&nbsp;button.</li><li class=\"ql-align-justify\">Go back to TikTok Downloader and paste your download link to the field above then click to the Download button</li><li class=\"ql-align-justify\">Wait for our server to do its job and then, save the video to your device.</li></ol><h3 class=\"ql-align-justify\">How to get the TikTok video download link?</h3><ol><li class=\"ql-align-justify\">Open your TikTok application</li><li class=\"ql-align-justify\">Choose the TikTok video that you want to download</li><li class=\"ql-align-justify\">Click&nbsp;<strong>Share</strong>&nbsp;and at the&nbsp;Share&nbsp;options, find&nbsp;<strong>Copy Link</strong>&nbsp;button</li><li class=\"ql-align-justify\">Your download URL is ready on the clipboard.</li></ol><h3 class=\"ql-align-justify\">Where are TikTok videos saved after being downloaded?</h3><p class=\"ql-align-justify\"><span style=\"color: rgb(60, 72, 88);\">When you\'re downloading files, they are usually saved into whatever folder you have set as your default. Your browser normally sets this folder for you. In browser settings, you can change and choose manually the destination folder for your downloaded TikTok videos.</span></p><h3 class=\"ql-align-justify\">Does TikTok Downloader store downloaded videos or keep a copy of videos?</h3><p class=\"ql-align-justify\"><span style=\"color: rgb(60, 72, 88);\">TikTok Downloader doesn\'t store videos, neither do we keep copies of downloaded videos. All videos are hosted on TikTok\'s servers. Also, we don\'t keep track of the download histories of our users, thus making using TikTok Downloader totally anonymous.</span></p><h3 class=\"ql-align-justify\">Do I have to pay to download TikTok Videos?</h3><p class=\"ql-align-justify\">No, you don\'t have to pay for anything because our software is always free. You can support us by turning off your ad blocks or making donations. It supports our further development.</p><p><br></p>'),
(2, 'Privacy Policy', 'privacy-policy', 'TikTok Downloader (“us”, “we”, or “our”) operates https://www.TikTok Downloader.com (the “Site/App”). This page informs you of our policies regarding the collection, use and disclosure of Personal...', '<p class=\"ql-align-justify\">TikTok Downloader (“us”, “we”, or “our”) operates https://www.TikTok Downloader.com (the “Site/App”). This page informs you of our policies regarding the collection, use and disclosure of Personal Information we receive from users of the Site. We use your Personal Information only for providing and improving the Site. By using the Site, you agree to the collection and use of information in accordance with this policy.</p><p class=\"ql-align-justify\"><br></p><h3 class=\"ql-align-justify\">Information Collection And Use</h3><p class=\"ql-align-justify\">While using our Site, we may ask you to provide us with certain personally identifiable information that can be used to contact or identify you. Personally, identifiable information may include but is not limited to your name (“Personal Information”).</p><h3 class=\"ql-align-justify\">Log Data</h3><p class=\"ql-align-justify\">Like many site operators, we collect information that your browser sends whenever you visit our Site (“Log Data”). This Log Data may include information such as your computer’s Internet Protocol (“IP”) address, browser type, browser version, the pages of our Site that you visit, the time and date of your visit, the time spent on those pages and other statistics.</p><h3 class=\"ql-align-justify\">Cookies</h3><p class=\"ql-align-justify\">Cookies are files with small amount of data, which may include an anonymous unique identifier. Cookies are sent to your browser from a website and stored on your computer’s hard drive. Like many sites, we use “cookies” to collect information. You can instruct your browser to refuse all cookies or to indicate when a cookie is being sent. However, if you do not accept cookies, you may not be able to use some portions of our Site.</p><h3 class=\"ql-align-justify\">Security</h3><p class=\"ql-align-justify\">The security of your Personal Information is important to us, but remember that no method of transmission over the Internet, or method of electronic storage, is 100% secure. While we strive to use commercially acceptable means to protect your Personal Information, we cannot guarantee its absolute security.</p><h3 class=\"ql-align-justify\">Changes To This Privacy Policy</h3><p class=\"ql-align-justify\">TikTok Downloader may update this Privacy Policy from time to time. We will notify you of any changes by posting the new Privacy Policy on the Site. You are advised to review this Privacy Policy periodically for any changes.</p><h3 class=\"ql-align-justify\">Contact Us</h3><p class=\"ql-align-justify\">If you have any questions about this Privacy Policy, please contact us</p>'),
(3, 'Terms of Services', 'terms-of-services', 'BY ACCESSING OR USING THIS WEB APPLICATION, YOU AGREE TO THE TERMS OF SERVICE. IF YOU ARE ACCEPTING THESE TERMS ON BEHALF OF ANOTHER PERSON OR COMPANY...', '<p class=\"ql-align-justify\">BY ACCESSING OR USING THIS WEB APPLICATION, YOU AGREE TO THE TERMS OF SERVICE. IF YOU ARE ACCEPTING THESE TERMS ON BEHALF OF ANOTHER PERSON OR COMPANY OR OTHER LEGAL ENTITY, YOU REPRESENT AND WARRANT THAT YOU HAVE FULL AUTHORITY TO BIND THAT PERSON, COMPANY OR LEGAL ENTITY TO THESE TERMS.</p><p class=\"ql-align-justify\">We’re not using the official Instagram API which is available on Instagram Developer Center as it’s very limited. So we are using a different API. On the backend, the script behaves like the official Android app of the Instagram. We have taken all security measures to reduce the ban rate. If you don’t publish spammy posts or don’t try to send massive amount of the requests to the Instagram from the same account, there shouldn’t be any problem.</p><p class=\"ql-align-justify\">This script is in no way affiliated with, authorized, maintained, sponsored or endorsed by Instagram or any of its affiliates or subsidiaries.</p><p class=\"ql-align-justify\">By using TikTok Downloader, you automatically agree to these Terms, so you should first read them carefully. If you don’t wish to accept all Terms then please don’t use our service.</p><ol><li class=\"ql-align-justify\">We don’t store your password. No one has been banned for using TikTok Downloader, but we wouldn’t be responsible in the case. Don’t mix it with other automation tools. We will never sell any of your information to third parties.</li><li class=\"ql-align-justify\">TikTok Downloader is not affiliated with Instagram, Facebook or any third-party partners in any way.</li><li class=\"ql-align-justify\">It is your sole responsibility to comply with Instagram rules and any legislation that you are subject to. You use TikTok Downloader at your own risk.</li><li class=\"ql-align-justify\">TikTok Downloader is meant to be the only software being used in an account. If you mix it with other automation software, there’s a chance your account will get banned.</li><li class=\"ql-align-justify\">We are not responsible for your actions and their consequences. We are not to blame in the unlikely case of your accounts getting banned for any reason.</li><li class=\"ql-align-justify\">We require your Instagram API access to obtain required information for interacting with the APIs. We don’t store, give away, or otherwise distribute your information to any third parties.</li><li class=\"ql-align-justify\">The expected amount of followers and likes depends on the quality of your content. We can’t guarantee any amount of interaction from using TikTok Downloader</li><li class=\"ql-align-justify\">We bring the attention of real users to your feed, but we can’t protect you from spam, fake, inactive followers. It’s not possible to stop them, but you can remove unwanted followers by yourself or with special services.</li><li class=\"ql-align-justify\">We can’t guarantee the continuous, uninterrupted or error-free operability of the services.</li><li class=\"ql-align-justify\">Before you make a purchase decision you are advised to try TikTok Downloader with our 3 Days free Trial on Early Bird package.</li><li class=\"ql-align-justify\">You agree that upon purchasing our service, that you clearly understand and agree what you are purchasing and will not file a fraudulent dispute.</li><li class=\"ql-align-justify\">We do not offer refunds.</li><li class=\"ql-align-justify\">We reserve the right to modify, suspend or withdraw the whole or any part of our service or any of its content at any time without notice and without incurring any liability.</li><li class=\"ql-align-justify\">It is your sole responsibility to check whether the Terms have changed.</li></ol><p><br></p>');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `$PREFIX$users`;
CREATE TABLE `$PREFIX$users` (
  `id` int(11) NOT NULL,
  `fname` text NOT NULL,
  `lname` text NOT NULL,
  `email` text NOT NULL,
  `password` text NOT NULL,
  `role` text,
  `token` text,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Table structure for table `videos`
--

DROP TABLE IF EXISTS `$PREFIX$videos`;
CREATE TABLE `$PREFIX$videos` (
  `id` bigint(20) NOT NULL,
  `video_id` text NOT NULL,
  `title` text CHARACTER SET utf8mb4 NOT NULL,
  `caption` text CHARACTER SET utf8mb4,
  `cover` text NOT NULL,
  `url` text NOT NULL,
  `url_nwm` text,
  `user` mediumtext DEFAULT NULL,
  `music` mediumtext DEFAULT NULL,
  `stats` mediumtext DEFAULT NULL,
  `uploaded_at` text,
  `dl_count` bigint(20) NOT NULL DEFAULT '1',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `pages`
--
ALTER TABLE `$PREFIX$pages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`(11));

--
-- Indexes for table `users`
--
ALTER TABLE `$PREFIX$users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`(55),`token`(55));

--
-- Indexes for table `videos`
--
ALTER TABLE `$PREFIX$videos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `video_id` (`video_id`(255));

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `pages`
--
ALTER TABLE `$PREFIX$pages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `$PREFIX$users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT for table `videos`
--
ALTER TABLE `$PREFIX$videos`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
