-- MySQL dump 10.16  Distrib 10.2.13-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: ra
-- ------------------------------------------------------
-- Server version	10.2.13-MariaDB-10.2.13+maria~stretch-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Dumping data for table `tblconfiguration`
--

LOCK TABLES `tblconfiguration` WRITE;

TRUNCATE TABLE `tblconfiguration`;

/*!40000 ALTER TABLE `tblconfiguration` DISABLE KEYS */;
INSERT INTO `tblconfiguration` VALUES (1,'Language','en'),
	(2,'CompanyName','Unlimited Internet'),
	(3,'Email','email@dev.roboticaccounting.com'),
	(4,'Domain','http://peter.dev.roboticaccounting.com/'),
	(5,'LogoURL','https://unlimitedinternet.co.nz/wp-content/uploads/2016/08/UnlimitedInternet_Logo_Web497x124.png'),
	(6,'SystemURL','https://dev.roboticaccounting.com/'),
	(7,'SystemSSLURL',''),
	(8,'AutoSuspension','on'),
	(9,'AutoSuspensionDays','5'),
	(10,'CreateInvoiceDaysBefore','14'),
	(11,'AffiliateEnabled',''),
	(12,'AffiliateEarningPercent','0'),
	(13,'AffiliateBonusDeposit','0.00'),
	(14,'AffiliatePayout','0.00'),
	(15,'AffiliateLinks',''),
	(16,'ActivityLimit','10000'),
	(17,'DateFormat','DD/MM/YYYY'),
	(18,'PreSalesQuestions',''),
	(19,'Template','uihex'),
	(20,'AllowRegister','on'),
	(21,'AllowTransfer','on'),
	(22,'AllowOwnDomain','on'),
	(23,'EnableTOSAccept',''),
	(24,'TermsOfService',''),
	(25,'AllowLanguageChange','on'),
	(26,'Version','5.2.15'),
	(27,'AllowCustomerChangeInvoiceGateway','on'),
	(28,'DefaultNameserver1','ns1.yourdomain.com'),
	(29,'DefaultNameserver2','ns2.yourdomain.com'),
	(30,'SendInvoiceReminderDays','7'),
	(31,'SendReminder','on'),
	(32,'NumRecordstoDisplay','25'),
	(33,'BCCMessages',''),
	(34,'MailType','smtp'),
	(35,'SMTPHost','127.0.0.1'),
	(36,'SMTPUsername',''),
	(37,'SMTPPassword',''),
	(38,'SMTPPort','1025'),
	(39,'ShowCancellationButton','on'),
	(40,'UpdateStatsAuto',''),
	(41,'InvoicePayTo','7 Douglas Alexander Parade\r\nRosedale\r\nAuckland 0632'),
	(42,'SendAffiliateReportMonthly','on'),
	(43,'InvalidLoginBanLength','15'),
	(44,'Signature',''),
	(45,'DomainOnlyOrderEnabled','on'),
	(46,'TicketBannedAddresses',''),
	(47,'SendEmailNotificationonUserDetailsChange','on'),
	(48,'TicketAllowedFileTypes','.jpg,.gif,.jpeg,.png'),
	(49,'CloseInactiveTickets','0'),
	(50,'InvoiceLateFeeAmount','10.00'),
	(51,'AutoTermination',''),
	(52,'AutoTerminationDays','30'),
	(53,'RegistrarAdminFirstName',''),
	(54,'RegistrarAdminLastName',''),
	(55,'RegistrarAdminCompanyName',''),
	(56,'RegistrarAdminAddress1',''),
	(57,'RegistrarAdminAddress2',''),
	(58,'RegistrarAdminCity',''),
	(59,'RegistrarAdminStateProvince',''),
	(60,'RegistrarAdminCountry','CN'),
	(61,'RegistrarAdminPostalCode',''),
	(62,'RegistrarAdminPhone',''),
	(63,'RegistrarAdminFax',''),
	(64,'RegistrarAdminEmailAddress',''),
	(65,'RegistrarAdminUseClientDetails','on'),
	(66,'Charset','utf-8'),
	(67,'AutoUnsuspend',''),
	(68,'RunScriptonCheckOut',''),
	(69,'License',''),
	(70,'OrderFormTemplate','modern'),
	(71,'AllowDomainsTwice','on'),
	(72,'AddLateFeeDays','7'),
	(73,'TaxEnabled','on'),
	(74,'DefaultCountry','New Zealand'),
	(75,'AutoRedirectoInvoice','gateway'),
	(76,'EnablePDFInvoices','on'),
	(77,'CaptchaSetting','offloggedin'),
	(78,'SupportTicketOrder','ASC'),
	(79,'SendFirstOverdueInvoiceReminder','1'),
	(80,'TaxType','Inclusive'),
	(81,'DomainDNSManagement','5.00'),
	(82,'DomainEmailForwarding','5.00'),
	(83,'InvoiceIncrement','1'),
	(84,'ContinuousInvoiceGeneration',''),
	(85,'AutoCancellationRequests','on'),
	(86,'SystemEmailsFromName','RACompleteSolution'),
	(87,'SystemEmailsFromEmail','noreply@yourdomain.com'),
	(88,'AllowClientRegister','on'),
	(89,'BulkCheckTLDs',''),
	(90,'OrderDaysGrace','0'),
	(91,'CreditOnDowngrade','on'),
	(92,'AcceptedCardTypes','Visa,MasterCard,Discover,American Express,JCB,EnRoute,Diners Club'),
	(93,'TaxDomains',''),
	(94,'TaxLateFee',''),
	(96,'ProductMonthlyPricingBreakdown',''),
	(97,'LateFeeType','Percentage'),
	(98,'SendSecondOverdueInvoiceReminder','0'),
	(99,'SendThirdOverdueInvoiceReminder','0'),
	(100,'DomainIDProtection','5.00'),
	(101,'DomainRenewalNotices',''),
	(102,'SequentialInvoiceNumbering',''),
	(103,'SequentialInvoiceNumberFormat',''),
	(104,'SequentialInvoiceNumberValue',''),
	(105,'DefaultNameserver3',''),
	(106,'DefaultNameserver4',''),
	(107,'AffiliatesDelayCommission','0'),
	(108,'SupportModule',''),
	(109,'AddFundsEnabled','on'),
	(110,'AddFundsMinimum','10.00'),
	(111,'AddFundsMaximum','100.00'),
	(112,'AddFundsMaximumBalance','300.00'),
	(113,'OrderDaysGrace','0'),
	(115,'CCProcessDaysBefore','0'),
	(116,'CCAttemptOnlyOnce',''),
	(117,'CCDaySendExpiryNotices','25'),
	(118,'BulkDomainSearchEnabled','on'),
	(119,'AutoRenewDomainsonPayment','on'),
	(120,'DomainAutoRenewDefault','on'),
	(121,'CCRetryEveryWeekFor','0'),
	(122,'SupportTicketKBSuggestions','on'),
	(123,'DailyEmailBackup',''),
	(124,'FTPBackupHostname',''),
	(125,'FTPBackupUsername',''),
	(126,'FTPBackupPassword',''),
	(127,'FTPBackupDestination','/'),
	(128,'TaxL2Compound',''),
	(129,'EmailCSS','body,td { font-family: verdana; font-size: 11px; font-weight: normal; }\r\na { color: #0000ff; }'),
	(130,'SEOFriendlyUrls',''),
	(131,'ShowCCIssueStart',''),
	(132,'ClientDropdownFormat','1'),
	(133,'TicketRatingEnabled','on'),
	(134,'NetworkIssuesRequireLogin','on'),
	(135,'ShowNotesFieldonCheckout','on'),
	(136,'RequireLoginforClientTickets','on'),
	(137,'NOMD5',''),
	(138,'CurrencyAutoUpdateExchangeRates',''),
	(139,'CurrencyAutoUpdateProductPrices',''),
	(140,'RequiredPWStrength','50'),
	(141,'MaintenanceMode',''),
	(142,'MaintenanceModeMessage','We are currently performing maintenance and will be back shortly.'),
	(143,'SkipFraudForExisting',''),
	(144,'SMTPSSL',''),
	(145,'ContactFormDept',''),
	(146,'ContactFormTo',''),
	(147,'TicketEscalationLastRun','2009-01-01 00:00:00'),
	(148,'APIAllowedIPs','a:1:{i:0;a:2:{s:2:\"ip\";s:0:\"\";s:4:\"note\";s:0:\"\";}}'),
	(149,'DisableSessionIPCheck','on'),
	(150,'DisableSupportTicketReplyEmailsLogging',''),
	(151,'OverageBillingMethod','1'),
	(152,'CCNeverStore',''),
	(153,'CCAllowCustomerDelete',''),
	(154,'CreateDomainInvoiceDaysBefore',''),
	(155,'NoInvoiceEmailOnOrder',''),
	(156,'TaxInclusiveDeduct',''),
	(157,'LateFeeMinimum','0.00'),
	(158,'AutoProvisionExistingOnly',''),
	(159,'EnableDomainRenewalOrders','on'),
	(160,'EnableMassPay','on'),
	(161,'NoAutoApplyCredit',''),
	(162,'CreateInvoiceDaysBeforeMonthly',''),
	(163,'CreateInvoiceDaysBeforeQuarterly',''),
	(164,'CreateInvoiceDaysBeforeSemiAnnually',''),
	(165,'CreateInvoiceDaysBeforeAnnually',''),
	(166,'CreateInvoiceDaysBeforeBiennially',''),
	(167,'CreateInvoiceDaysBeforeTriennially',''),
	(168,'ClientsProfileUneditableFields',''),
	(169,'ClientDisplayFormat','1'),
	(170,'CCDoNotRemoveOnExpiry',''),
	(171,'GenerateRandomUsername',''),
	(172,'AddFundsRequireOrder','on'),
	(173,'GroupSimilarLineItems','on'),
	(174,'ProrataClientsAnniversaryDate',''),
	(175,'TCPDFFont','helvetica'),
	(176,'CancelInvoiceOnCancellation','on'),
	(177,'AttachmentThumbnails','on'),
	(178,'EmailGlobalHeader','&lt;p&gt;&lt;a href=&quot;{$company_domain}&quot; target=&quot;_blank&quot;&gt;&lt;img src=&quot;{$company_logo_url}&quot; alt=&quot;{$company_name}&quot; border=&quot;0&quot; /&gt;&lt;/a&gt;&lt;/p&gt;'),
	(179,'EmailGlobalFooter',''),
	(180,'DomainSyncEnabled','on'),
	(181,'DomainSyncNextDueDate',''),
	(182,'DomainSyncNextDueDateDays','0'),
	(183,'TicketMask','%n%n%n%n%n%n'),
	(184,'AutoClientStatusChange','2'),
	(185,'AllowClientsEmailOptOut',''),
	(186,'BannedSubdomainPrefixes','mail,mx,gapps,gmail,webmail,cpanel,whm,ftp,clients,billing,members,login,accounts,access'),
	(187,'FreeDomainAutoRenewRequiresProduct','on'),
	(188,'DomainToDoListEntries','on'),
	(189,'InstanceID','kEblFpzf5eqY'),
	(190,'token_namespaces','a:3:{s:10:\"RA.default\";b:1;s:16:\"RA.admin.default\";b:1;s:16:\"RA.domainchecker\";b:0;}'),
	(191,'MaintenanceModeURL',''),
	(192,'ClientDateFormat',''),
	(193,'AllowIDNDomains',''),
	(194,'DomainSyncNotifyOnly',''),
	(195,'DefaultNameserver5',''),
	(196,'ShowClientOnlyDepts',''),
	(197,'TicketFeedback',''),
	(198,'DownloadsIncludeProductLinked',''),
	(199,'AffiliateDepartment','1'),
	(200,'CaptchaType','recaptcha'),
	(201,'ReCAPTCHAPrivateKey',''),
	(202,'ReCAPTCHAPublicKey',''),
	(203,'DisableAdminPWReset',''),
	(204,'TwitterUsername',''),
	(205,'AnnouncementsTweet',''),
	(206,'AnnouncementsFBRecommend',''),
	(207,'AnnouncementsFBComments',''),
	(208,'GooglePlus1',''),
	(209,'ClientsProfileOptionalFields',''),
	(210,'DefaultToClientArea',''),
	(211,'DisplayErrors','on'),
	(212,'SQLErrorReporting',''),
	(213,'ToggleInfoPopup','a:0:{}'),
	(214,'ActiveAddonModules',',hdtolls,paypal_addon,staffboard'),
	(215,'AddonModulesPerms','a:0:{}'),
	(216,'AddonModulesHooks','hdtolls'),
	(217,'FTPBackupPort','21'),
	(218,'ModuleHooks',''),
	(219,'LoginFailures','a:0:{}'),
	(220,'WhitelistedIPs','a:0:{}'),
	(221,'InstanceID','m3tpTOXduQyr'),
	(222,'token_namespaces','a:3:{s:10:\"RA.default\";b:1;s:16:\"RA.admin.default\";b:1;s:16:\"RA.domainchecker\";b:0;}'),
	(223,'InstanceID','eenMkmVwgeMY'),
	(224,'token_namespaces','a:3:{s:10:\"RA.default\";b:1;s:16:\"RA.admin.default\";b:1;s:16:\"RA.domainchecker\";b:0;}'),
	(225,'InstanceID','3jE3Bq7OYUW8'),
	(226,'token_namespaces','a:3:{s:10:\"RA.default\";b:1;s:16:\"RA.admin.default\";b:1;s:16:\"RA.domainchecker\";b:0;}'),
	(227,'InstanceID','dr5dT2JhawDC'),
	(228,'token_namespaces','a:3:{s:10:\"RA.default\";b:1;s:16:\"RA.admin.default\";b:1;s:16:\"RA.domainchecker\";b:0;}'),
	(229,'InstanceID','bfaGhNUkH5ts'),
	(230,'token_namespaces','a:3:{s:10:\"RA.default\";b:1;s:16:\"RA.admin.default\";b:1;s:16:\"RA.domainchecker\";b:0;}'),
	(231,'InstanceID','rCfKFK548YCF'),
	(232,'token_namespaces','a:3:{s:10:\"RA.default\";b:1;s:16:\"RA.admin.default\";b:1;s:16:\"RA.domainchecker\";b:0;}'),
	(233,'InstanceID','qNSO1vmkd5fR'),
	(234,'token_namespaces','a:3:{s:10:\"RA.default\";b:1;s:16:\"RA.admin.default\";b:1;s:16:\"RA.domainchecker\";b:0;}'),
	(235,'InstanceID','xCAiIyz9E9Fd'),
	(236,'token_namespaces','a:3:{s:10:\"RA.default\";b:1;s:16:\"RA.admin.default\";b:1;s:16:\"RA.domainchecker\";b:0;}'),
	(237,'InstanceID','YOvtSaRNfAI8'),
	(238,'token_namespaces','a:3:{s:10:\"RA.default\";b:1;s:16:\"RA.admin.default\";b:1;s:16:\"RA.domainchecker\";b:0;}'),
	(239,'InstanceID','iYPwyKkrRj1n'),
	(240,'token_namespaces','a:3:{s:10:\"RA.default\";b:1;s:16:\"RA.admin.default\";b:1;s:16:\"RA.domainchecker\";b:0;}'),
	(241,'gst','96-983-506'),
	(242,'invphone','64 9 280 4135'),
	(243,'invfax','64 9 280 4134'),
	(244,'invwebsite','Web: www.hd.net.nz'),
	(245,'invemail','Email: s@hd.net.nz'),
	(246,'invaccount',''),
	(247,'invname',''),
	(248,'invaddress',''),
	(249,'invcompany',''),
	(250,'invpobox',''),
	(251,'invcity',''),
	(252,'invpostcode',''),
	(253,'invcountry','');
/*!40000 ALTER TABLE `tblconfiguration` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed