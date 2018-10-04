-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 03, 2018 at 11:09 AM
-- Server version: 5.6.41-84.1-log
-- PHP Version: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `netellcm_business`
--

-- --------------------------------------------------------

--
-- Table structure for table `accountgroups`
--

CREATE TABLE `accountgroups` (
  `groupname` char(30) NOT NULL DEFAULT '',
  `sectioninaccounts` int(11) NOT NULL DEFAULT '0',
  `pandl` tinyint(4) NOT NULL DEFAULT '1',
  `sequenceintb` smallint(6) NOT NULL DEFAULT '0',
  `parentgroupname` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `accountgroups`
--

INSERT INTO `accountgroups` (`groupname`, `sectioninaccounts`, `pandl`, `sequenceintb`, `parentgroupname`) VALUES
('Cost of Goods Sold', 2, 1, 5000, ''),
('Current Assets', 20, 0, 1000, ''),
('Financed', 50, 0, 3000, ''),
('Fixed Assets', 10, 0, 500, ''),
('Giveaways', 5, 1, 6000, 'Promotions'),
('Income Tax', 5, 1, 9000, ''),
('Liabilities', 30, 0, 2000, ''),
('Marketing Expenses', 5, 1, 6000, ''),
('Operating Expenses', 5, 1, 7000, ''),
('Other Revenue and Expenses', 5, 1, 8000, ''),
('Outward Freight', 2, 1, 5000, 'Cost of Goods Sold'),
('Promotions', 5, 1, 6000, 'Marketing Expenses'),
('Revenue', 1, 1, 4000, ''),
('Sales', 1, 1, 10, '');

-- --------------------------------------------------------

--
-- Table structure for table `accountsection`
--

CREATE TABLE `accountsection` (
  `sectionid` int(11) NOT NULL DEFAULT '0',
  `sectionname` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `accountsection`
--

INSERT INTO `accountsection` (`sectionid`, `sectionname`) VALUES
(1, 'Income'),
(2, 'Cost Of Sales'),
(5, 'Overheads'),
(10, 'Fixed Assets'),
(15, 'Inventory'),
(20, 'Amounts Receivable'),
(25, 'Cash'),
(30, 'Amounts Payable'),
(50, 'Financed By');

-- --------------------------------------------------------

--
-- Table structure for table `areas`
--

CREATE TABLE `areas` (
  `areacode` char(3) NOT NULL,
  `areadescription` varchar(25) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `areas`
--

INSERT INTO `areas` (`areacode`, `areadescription`) VALUES
('1', 'South'),
('2', 'West'),
('3', 'East'),
('4', 'North');

-- --------------------------------------------------------

--
-- Table structure for table `assetmanager`
--

CREATE TABLE `assetmanager` (
  `id` int(11) NOT NULL,
  `stockid` varchar(20) NOT NULL DEFAULT '',
  `serialno` varchar(30) NOT NULL DEFAULT '',
  `location` varchar(15) NOT NULL DEFAULT '',
  `cost` double NOT NULL DEFAULT '0',
  `depn` double NOT NULL DEFAULT '0',
  `datepurchased` date NOT NULL DEFAULT '0000-00-00',
  `disposalvalue` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `audittrail`
--

CREATE TABLE `audittrail` (
  `transactiondate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `userid` varchar(20) NOT NULL DEFAULT '',
  `querystring` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `audittrail`
--

INSERT INTO `audittrail` (`transactiondate`, `userid`, `querystring`) VALUES
('2018-08-27 13:56:28', 'admin', 'DELETE FROM locationusers WHERE userid=\'accounts\''),
('2018-08-27 13:56:28', 'admin', 'DELETE FROM glaccountusers WHERE userid=\'accounts\''),
('2018-08-27 13:56:28', 'admin', 'DELETE FROM www_users WHERE userid=\'accounts\''),
('2018-08-27 17:15:20', 'admin', 'UPDATE companies SET coyname=\'Netelity Websolutions Pvt.Ltd\',\r\n									companynumber = \'U72333KA5553PTC07449\',\r\n									gstno=\'29SSECN4476Q1ZQ\',\r\n									regoffice1=\'#1005, 3rd A main\',\r\n									regoffice2=\'E block, Subramanya Nagar\',\r\n									regoffice3=\'Bengaluru - 560010\',\r\n									regoffice4=\'Karnataka\',\r\n									regoffice5=\'\',\r\n									regoffice6=\'\',\r\n									telephone=\'8213452789\',\r\n									fax=\'\',\r\n									email=\'nERP@nERPdemo.com\',\r\n									currencydefault=\'INR\',\r\n									debtorsact=\'1100\',\r\n									pytdiscountact=\'4900\',\r\n									creditorsact=\'2100\',\r\n									payrollact=\'2400\',\r\n									grnact=\'2150\',\r\n									exchangediffact=\'4200\',\r\n									purchasesexchangediffact=\'5200\',\r\n									retainedearnings=\'3500\',\r\n									gllink_debtors=\'1\',\r\n									gllink_creditors=\'1\',\r\n									gllink_stock=\'1\',\r\n									freightact=\'5600\'\r\n								WHERE coycode=1'),
('2018-08-28 11:42:15', 'admin', 'UPDATE currencies SET rate=\'0.014252110312044\'\n																			WHERE currabrev=\'USD\''),
('2018-08-28 11:42:15', 'admin', 'UPDATE config SET confvalue = \'2018-08-28\' WHERE confname=\'UpdateCurrencyRatesDaily\''),
('2018-08-28 11:43:18', 'admin', 'UPDATE stockcategory SET stocktype = \'F\',\r\n									 categorydescription = \'Smart Phones\',\r\n									 defaulttaxcatid = \'6\',\r\n									 stockact = \'1460\',\r\n									 adjglact = \'5700\',\r\n									 issueglact = \'5700\',\r\n									 purchpricevaract = \'5000\',\r\n									 materialuseagevarac = \'5200\',\r\n									 wipact = \'1440\'\r\n									 WHERE\r\n									 categoryid = \'2\''),
('2018-08-28 11:43:18', 'admin', 'INSERT INTO stockcatproperties (categoryid,\r\n														label,\r\n														controltype,\r\n														defaultvalue,\r\n														minimumvalue,\r\n														maximumvalue,\r\n														numericvalue,\r\n														reqatsalesorder)\r\n											VALUES (\'2\',\r\n													\'RAM\',\r\n													0,\r\n													\'\',\r\n													\'1\',\r\n													\'8\',\r\n													\'1\',\r\n													0)'),
('2018-08-28 11:44:53', 'admin', 'INSERT INTO stockcatproperties (categoryid,\r\n														label,\r\n														controltype,\r\n														defaultvalue,\r\n														minimumvalue,\r\n														maximumvalue,\r\n														numericvalue,\r\n														reqatsalesorder)\r\n											VALUES (\'2\',\r\n													\'Memory\',\r\n													1,\r\n													\'16,32,64,128,256\',\r\n													\'1\',\r\n													\'256\',\r\n													\'0\',\r\n													0)'),
('2018-08-28 11:45:33', 'admin', 'UPDATE stockcatproperties SET label =\'Memory\',\r\n													  controltype = 1,\r\n													  defaultvalue = \'16,32,64,128,256\',\r\n													  minimumvalue = \'1\',\r\n													  maximumvalue = \'256\',\r\n													  numericvalue = \'1\',\r\n													  reqatsalesorder = 0\r\n												WHERE stkcatpropid =2'),
('2018-08-28 11:45:33', 'admin', 'INSERT INTO stockcatproperties (categoryid,\r\n														label,\r\n														controltype,\r\n														defaultvalue,\r\n														minimumvalue,\r\n														maximumvalue,\r\n														numericvalue,\r\n														reqatsalesorder)\r\n											VALUES (\'2\',\r\n													\'Screen\',\r\n													2,\r\n													\'4,5,6,7,8\',\r\n													\'1\',\r\n													\'8\',\r\n													\'1\',\r\n													0)'),
('2018-08-28 11:46:08', 'admin', 'UPDATE stockcategory SET stocktype = \'L\',\r\n									 categorydescription = \'Assembly Line\',\r\n									 defaulttaxcatid = \'2\',\r\n									 stockact = \'5500\',\r\n									 adjglact = \'5700\',\r\n									 issueglact = \'5700\',\r\n									 purchpricevaract = \'5900\',\r\n									 materialuseagevarac = \'5500\',\r\n									 wipact = \'1440\'\r\n									 WHERE\r\n									 categoryid = \'3\''),
('2018-08-28 11:46:27', 'admin', 'UPDATE stockcategory SET stocktype = \'M\',\r\n									 categorydescription = \'Electronics\',\r\n									 defaulttaxcatid = \'1\',\r\n									 stockact = \'1420\',\r\n									 adjglact = \'5700\',\r\n									 issueglact = \'5700\',\r\n									 purchpricevaract = \'5000\',\r\n									 materialuseagevarac = \'5000\',\r\n									 wipact = \'1440\'\r\n									 WHERE\r\n									 categoryid = \'4\''),
('2018-08-29 12:52:55', 'admin', 'UPDATE currencies SET rate=\'0.014263701863051\'\n																			WHERE currabrev=\'USD\''),
('2018-08-29 12:52:55', 'admin', 'UPDATE config SET confvalue = \'2018-08-29\' WHERE confname=\'UpdateCurrencyRatesDaily\''),
('2018-08-30 10:03:12', 'admin', 'UPDATE currencies SET rate=\'0.014160710707368\'\n																			WHERE currabrev=\'USD\''),
('2018-08-30 10:03:12', 'admin', 'UPDATE config SET confvalue = \'2018-08-30\' WHERE confname=\'UpdateCurrencyRatesDaily\''),
('2018-08-30 13:41:09', 'admin', 'UPDATE scripts SET pagesecurity=\'0\' WHERE script=\'PcAuthorizeCash.php\''),
('2018-08-30 13:41:09', 'admin', 'UPDATE scripts SET pagesecurity=\'0\' WHERE script=\'PcAuthorizeExpenses.php\''),
('2018-08-30 13:41:09', 'admin', 'UPDATE scripts SET pagesecurity=\'0\' WHERE script=\'PcClaimExpensesFromTab.php\''),
('2018-08-31 17:16:15', 'admin', 'UPDATE currencies SET rate=\'0.014135283805839\'\n																			WHERE currabrev=\'USD\''),
('2018-08-31 17:16:15', 'admin', 'UPDATE config SET confvalue = \'2018-08-31\' WHERE confname=\'UpdateCurrencyRatesDaily\''),
('2018-08-31 17:42:15', 'admin', 'INSERT INTO securitygroups (secroleid,\r\n											tokenid)\r\n									VALUES (\'8\',\r\n											\'21\' )'),
('2018-08-31 17:42:19', 'admin', 'INSERT INTO securitygroups (secroleid,\r\n											tokenid)\r\n									VALUES (\'8\',\r\n											\'22\' )'),
('2018-08-31 17:42:24', 'admin', 'INSERT INTO securitygroups (secroleid,\r\n											tokenid)\r\n									VALUES (\'8\',\r\n											\'23\' )'),
('2018-08-31 17:49:27', 'admin', 'UPDATE securitytokens SET tokenname=\'HR-Ops\' WHERE tokenid=\'22\''),
('2018-08-31 17:49:47', 'admin', 'UPDATE securitytokens SET tokenname=\'HR-Reports\' WHERE tokenid=\'23\''),
('2018-08-31 17:51:29', 'admin', 'INSERT INTO securityroles (secrolename) VALUES (\'HR-Executive\')'),
('2018-08-31 17:51:38', 'admin', 'INSERT INTO securityroles (secrolename) VALUES (\'HR-Manager\')'),
('2018-08-31 17:51:55', 'admin', 'UPDATE securitytokens SET tokenname=\'Employees\' WHERE tokenid=\'21\''),
('2018-08-31 17:57:16', 'admin', 'UPDATE scripts SET pagesecurity=\'7\' WHERE script=\'CustomerWitholdingTax.php\''),
('2018-08-31 18:14:30', 'admin', 'UPDATE scripts SET pagesecurity=\'23\' WHERE script=\'HrApproveLoan.php\''),
('2018-08-31 18:14:30', 'admin', 'UPDATE scripts SET pagesecurity=\'22\' WHERE script=\'HrAttendanceRegister.php\''),
('2018-08-31 18:14:30', 'admin', 'UPDATE scripts SET pagesecurity=\'23\' WHERE script=\'HrDeductablesReports.php\''),
('2018-08-31 18:14:30', 'admin', 'UPDATE scripts SET pagesecurity=\'20\' WHERE script=\'HrEmployeeCategories.php\''),
('2018-08-31 18:14:30', 'admin', 'UPDATE scripts SET pagesecurity=\'20\' WHERE script=\'HrEmployeeGrades.php\''),
('2018-08-31 18:14:30', 'admin', 'UPDATE scripts SET pagesecurity=\'22\' WHERE script=\'HrEmployeeLoans.php\''),
('2018-08-31 18:14:30', 'admin', 'UPDATE scripts SET pagesecurity=\'23\' WHERE script=\'HrEmployeePayslips.php\''),
('2018-08-31 18:14:30', 'admin', 'UPDATE scripts SET pagesecurity=\'20\' WHERE script=\'HrEmployeePositions.php\''),
('2018-08-31 18:14:30', 'admin', 'UPDATE scripts SET pagesecurity=\'22\' WHERE script=\'HrEmployees.php\''),
('2018-08-31 18:14:30', 'admin', 'UPDATE scripts SET pagesecurity=\'22\' WHERE script=\'HrGenerateAttendanceReport.php\''),
('2018-08-31 18:14:30', 'admin', 'UPDATE scripts SET pagesecurity=\'23\' WHERE script=\'HrGenerateEmployeePay.php\''),
('2018-08-31 18:14:30', 'admin', 'UPDATE scripts SET pagesecurity=\'23\' WHERE script=\'HrGenerateEstimatedSalary.php\''),
('2018-08-31 18:14:30', 'admin', 'UPDATE scripts SET pagesecurity=\'23\' WHERE script=\'HrGeneratePayroll.php\''),
('2018-08-31 18:14:30', 'admin', 'UPDATE scripts SET pagesecurity=\'20\' WHERE script=\'HrGlSettings.php\''),
('2018-08-31 18:14:30', 'admin', 'UPDATE scripts SET pagesecurity=\'21\' WHERE script=\'HrLeaveApplications.php\''),
('2018-08-31 18:14:30', 'admin', 'UPDATE scripts SET pagesecurity=\'20\' WHERE script=\'HrLeaveGroups.php\''),
('2018-08-31 18:14:30', 'admin', 'UPDATE scripts SET pagesecurity=\'20\' WHERE script=\'HrLeaveTypes.php\''),
('2018-08-31 18:14:30', 'admin', 'UPDATE scripts SET pagesecurity=\'20\' WHERE script=\'HrLoanTypes.php\''),
('2018-08-31 18:14:30', 'admin', 'UPDATE scripts SET pagesecurity=\'21\' WHERE script=\'HrMyLeave.php\''),
('2018-08-31 18:14:30', 'admin', 'UPDATE scripts SET pagesecurity=\'20\' WHERE script=\'HrPayrollCategories.php\''),
('2018-08-31 18:14:30', 'admin', 'UPDATE scripts SET pagesecurity=\'20\' WHERE script=\'HrPayrollGroups.php\''),
('2018-08-31 18:14:30', 'admin', 'UPDATE scripts SET pagesecurity=\'20\' WHERE script=\'HrPayrollMode.php\''),
('2018-08-31 18:14:30', 'admin', 'UPDATE scripts SET pagesecurity=\'20\' WHERE script=\'HrPayslipSettings.php\''),
('2018-08-31 18:14:30', 'admin', 'UPDATE scripts SET pagesecurity=\'22\' WHERE script=\'HrPrintPayslip.php\''),
('2018-08-31 18:14:30', 'admin', 'UPDATE scripts SET pagesecurity=\'22\' WHERE script=\'HrSelectEmployee.php\''),
('2018-08-31 18:14:30', 'admin', 'UPDATE scripts SET pagesecurity=\'22\' WHERE script=\'HrSelectLeave.php\''),
('2018-08-31 18:14:30', 'admin', 'UPDATE scripts SET pagesecurity=\'20\' WHERE script=\'HrWorkingDays.php\''),
('2018-08-31 18:14:47', 'admin', 'INSERT INTO securitygroups (secroleid,\r\n											tokenid)\r\n									VALUES (\'17\',\r\n											\'0\' )'),
('2018-08-31 18:14:57', 'admin', 'INSERT INTO securitygroups (secroleid,\r\n											tokenid)\r\n									VALUES (\'17\',\r\n											\'21\' )'),
('2018-08-31 18:15:00', 'admin', 'INSERT INTO securitygroups (secroleid,\r\n											tokenid)\r\n									VALUES (\'17\',\r\n											\'22\' )'),
('2018-08-31 18:15:06', 'admin', 'INSERT INTO securitygroups (secroleid,\r\n											tokenid)\r\n									VALUES (\'17\',\r\n											\'23\' )'),
('2018-08-31 18:15:13', 'admin', 'DELETE FROM securitygroups\r\n					WHERE secroleid = \'17\'\r\n					AND tokenid = \'23\''),
('2018-08-31 18:15:25', 'admin', 'INSERT INTO securitygroups (secroleid,\r\n											tokenid)\r\n									VALUES (\'18\',\r\n											\'0\' )'),
('2018-08-31 18:15:30', 'admin', 'INSERT INTO securitygroups (secroleid,\r\n											tokenid)\r\n									VALUES (\'18\',\r\n											\'21\' )'),
('2018-08-31 18:15:34', 'admin', 'INSERT INTO securitygroups (secroleid,\r\n											tokenid)\r\n									VALUES (\'18\',\r\n											\'22\' )'),
('2018-08-31 18:15:37', 'admin', 'INSERT INTO securitygroups (secroleid,\r\n											tokenid)\r\n									VALUES (\'18\',\r\n											\'23\' )'),
('2018-08-31 18:15:53', 'admin', 'INSERT INTO securityroles (secrolename) VALUES (\'Employees\')'),
('2018-08-31 18:16:07', 'admin', 'INSERT INTO securitygroups (secroleid,\r\n											tokenid)\r\n									VALUES (\'19\',\r\n											\'0\' )'),
('2018-08-31 18:16:12', 'admin', 'INSERT INTO securitygroups (secroleid,\r\n											tokenid)\r\n									VALUES (\'19\',\r\n											\'21\' )'),
('2018-08-31 18:20:40', 'admin', 'DELETE FROM hrpayrollcategories WHERE payroll_category_id=\'1\''),
('2018-08-31 18:21:55', 'admin', 'DELETE FROM hremployeeloans WHERE loan_id =\'1\''),
('2018-09-01 11:40:01', 'admin', 'UPDATE currencies SET rate=\'0.014084098423079\'\n																			WHERE currabrev=\'USD\''),
('2018-09-01 11:40:01', 'admin', 'UPDATE config SET confvalue = \'2018-09-01\' WHERE confname=\'UpdateCurrencyRatesDaily\''),
('2018-09-01 12:11:33', 'admin', 'UPDATE companies SET coyname=\'Netelity Websolutions Pvt.Ltd\',\r\n									companynumber = \'U72333KA5553PTC07449\',\r\n									gstno=\'29SSECN4476Q1ZQ\',\r\n									regoffice1=\'#1005, 3rd A main\',\r\n									regoffice2=\'E block, Subramanya Nagar\',\r\n									regoffice3=\'Bengaluru - 560010\',\r\n									regoffice4=\'Karnataka\',\r\n									regoffice5=\'\',\r\n									regoffice6=\'\',\r\n									telephone=\'8213452789\',\r\n									fax=\'\',\r\n									email=\'nERP@nERPdemo.com\',\r\n									currencydefault=\'INR\',\r\n									debtorsact=\'1100\',\r\n									pytdiscountact=\'4900\',\r\n									creditorsact=\'2100\',\r\n									payrollact=\'2400\',\r\n									grnact=\'2150\',\r\n									exchangediffact=\'2320\',\r\n									purchasesexchangediffact=\'5200\',\r\n									retainedearnings=\'3500\',\r\n									gllink_debtors=\'1\',\r\n									gllink_creditors=\'1\',\r\n									gllink_stock=\'1\',\r\n									witholdingtaxexempted=\'0\',\r\n									witholdingtaxglaccount=\'2330\',\r\n									supplier_returns_location=\'BAN\',\r\n									freightact=\'5600\'\r\n								WHERE coycode=1'),
('2018-09-01 12:12:37', 'admin', 'UPDATE config SET confvalue = \'2\' WHERE confname = \'StandardCostDecimalPlaces\''),
('2018-09-01 12:12:37', 'admin', 'UPDATE config SET confvalue = \'companies/netelity/part_pics\' WHERE confname = \'part_pics_dir\''),
('2018-09-01 12:12:37', 'admin', 'UPDATE config SET confvalue = \'companies/netelity/reports\' WHERE confname = \'reports_dir\''),
('2018-09-01 12:35:35', 'admin', 'INSERT INTO stockcategory (categoryid,\r\n											stocktype,\r\n											categorydescription,\r\n											defaulttaxcatid,\r\n											stockact,\r\n											adjglact,\r\n											issueglact,\r\n											purchpricevaract,\r\n											materialuseagevarac,\r\n											wipact)\r\n										VALUES (\'5\',\'M\',\'Display\',\'1\',\'1420\',\'5700\',\'5700\',\'5000\',\'5000\',\'1440\')'),
('2018-09-01 12:37:17', 'admin', 'INSERT INTO departments (description,\r\n											 authoriser )\r\n					VALUES (\'Purchase\',\r\n							\'admin\')'),
('2018-09-01 12:37:25', 'admin', 'INSERT INTO departments (description,\r\n											 authoriser )\r\n					VALUES (\'HR\',\r\n							\'admin\')'),
('2018-09-01 12:38:05', 'admin', 'INSERT INTO internalstockcatrole (secroleid,\r\n												categoryid)\r\n										VALUES (\'1\',\r\n												\'2\')'),
('2018-09-01 12:38:13', 'admin', 'INSERT INTO internalstockcatrole (secroleid,\r\n												categoryid)\r\n										VALUES (\'1\',\r\n												\'4\')'),
('2018-09-01 12:38:19', 'admin', 'INSERT INTO internalstockcatrole (secroleid,\r\n												categoryid)\r\n										VALUES (\'1\',\r\n												\'5\')'),
('2018-09-01 12:38:39', 'admin', 'INSERT INTO internalstockcatrole (secroleid,\r\n												categoryid)\r\n										VALUES (\'8\',\r\n												\'5\')'),
('2018-09-01 12:38:43', 'admin', 'INSERT INTO internalstockcatrole (secroleid,\r\n												categoryid)\r\n										VALUES (\'8\',\r\n												\'3\')'),
('2018-09-01 12:39:02', 'admin', 'INSERT INTO internalstockcatrole (secroleid,\r\n												categoryid)\r\n										VALUES (\'8\',\r\n												\'1\')'),
('2018-09-01 12:39:46', 'admin', 'UPDATE areas SET areadescription=\'South\'\r\n								WHERE areacode = \'1\''),
('2018-09-01 12:39:54', 'admin', 'INSERT INTO areas (areacode,\r\n									areadescription\r\n								) VALUES (\r\n									\'2\',\r\n									\'West\'\r\n								)'),
('2018-09-01 12:40:03', 'admin', 'INSERT INTO areas (areacode,\r\n									areadescription\r\n								) VALUES (\r\n									\'3\',\r\n									\'East\'\r\n								)'),
('2018-09-01 12:40:13', 'admin', 'INSERT INTO areas (areacode,\r\n									areadescription\r\n								) VALUES (\r\n									\'4\',\r\n									\'North\'\r\n								)'),
('2018-09-01 12:45:10', 'admin', 'INSERT INTO hremployeeleavetypes\r\n						(leavetype_name,\r\n						leavetype_code,leavetype_leavecount,leavetype_status,\r\n						carry_forward,lop_enabled,max_carry_forward_leaves,reset_date\r\n					)\r\n					VALUES (\'Casual Leave\',\r\n\'CL\',\r\n\'5\',\r\n\'1\',\r\n\'1\',\r\n\'1\',\r\n\'2\',\r\n\'2018-09-01\'\r\n)'),
('2018-09-01 12:53:04', 'admin', 'INSERT INTO hremployeeleavetypes\r\n						(leavetype_name,\r\n						leavetype_code,leavetype_leavecount,leavetype_status,\r\n						carry_forward,lop_enabled,max_carry_forward_leaves,reset_date\r\n					)\r\n					VALUES (\'Sick Leave\',\r\n\'SL\',\r\n\'5\',\r\n\'1\',\r\n\'1\',\r\n\'1\',\r\n\'2\',\r\n\'2018-09-01\'\r\n)'),
('2018-09-01 13:10:11', 'admin', 'INSERT INTO hrpayrollcategories\r\n						(payroll_category_name,\r\n							payroll_category_code,\r\n							payroll_category_value,\r\n							payroll_category_type,\r\n							additional_condition,\r\n							general_ledger_account_id)\r\n					VALUES (\'Basic&amp;DA\',\r\n					\'BDA\',\r\n					\'5000\',\r\n					\'1\',\r\n					\'Nothing\',\r\n					\'1050\'\r\n					)'),
('2018-09-01 13:12:49', 'admin', 'INSERT INTO hrpayrollcategories\r\n						(payroll_category_name,\r\n							payroll_category_code,\r\n							payroll_category_value,\r\n							payroll_category_type,\r\n							additional_condition,\r\n							general_ledger_account_id)\r\n					VALUES (\'HRA\',\r\n					\'HRA\',\r\n					\'3500\',\r\n					\'1\',\r\n					\'Nothing\',\r\n					\'1050\'\r\n					)'),
('2018-09-01 13:13:23', 'admin', 'INSERT INTO hrpayrollcategories\r\n						(payroll_category_name,\r\n							payroll_category_code,\r\n							payroll_category_value,\r\n							payroll_category_type,\r\n							additional_condition,\r\n							general_ledger_account_id)\r\n					VALUES (\'Conveyance\',\r\n					\'CON\',\r\n					\'1500\',\r\n					\'1\',\r\n					\'Nothing\',\r\n					\'1050\'\r\n					)'),
('2018-09-01 13:14:31', 'admin', 'INSERT INTO hrpayrollcategories\r\n						(payroll_category_name,\r\n							payroll_category_code,\r\n							payroll_category_value,\r\n							payroll_category_type,\r\n							additional_condition,\r\n							general_ledger_account_id)\r\n					VALUES (\'PF\',\r\n					\'PF\',\r\n					\'600\',\r\n					\'0\',\r\n					\'% of BDA\',\r\n					\'2340\'\r\n					)'),
('2018-09-01 13:16:06', 'admin', 'INSERT INTO hrpayrollcategories\r\n						(payroll_category_name,\r\n							payroll_category_code,\r\n							payroll_category_value,\r\n							payroll_category_type,\r\n							additional_condition,\r\n							general_ledger_account_id)\r\n					VALUES (\'Loan\',\r\n					\'LN\',\r\n					\'-\',\r\n					\'0\',\r\n					\'\',\r\n					\'1350\'\r\n					)'),
('2018-09-01 13:17:59', 'admin', 'INSERT INTO hrpayrollgroups\r\n						(payrollgroup_name,\r\n							payment_frequency,\r\n							generation_date,\r\nenable_lop,lop_value,bank_account_to_use,gl_posting_account,currency )\r\n					VALUES (\'Executive\',\r\n\'\',\r\n\'01/09/2018\',\r\n\'1\',\r\n\'2\',\r\n\'\',\r\n\'1050\',\r\n\'INR\'\r\n)'),
('2018-09-01 13:17:59', 'admin', 'INSERT INTO hrpayroll_groups_payroll_categories\r\n						(payroll_group_id,\r\n							payroll_category_id,\r\n							sort_order )\r\n					VALUES (\'1\',\r\n					\'1\',\r\n					\'1\'\r\n					)'),
('2018-09-01 13:17:59', 'admin', 'INSERT INTO hrpayroll_groups_payroll_categories\r\n						(payroll_group_id,\r\n							payroll_category_id,\r\n							sort_order )\r\n					VALUES (\'1\',\r\n					\'2\',\r\n					\'2\'\r\n					)'),
('2018-09-01 13:17:59', 'admin', 'INSERT INTO hrpayroll_groups_payroll_categories\r\n						(payroll_group_id,\r\n							payroll_category_id,\r\n							sort_order )\r\n					VALUES (\'1\',\r\n					\'3\',\r\n					\'3\'\r\n					)'),
('2018-09-01 13:17:59', 'admin', 'INSERT INTO hrpayroll_groups_payroll_categories\r\n						(payroll_group_id,\r\n							payroll_category_id,\r\n							sort_order )\r\n					VALUES (\'1\',\r\n					\'4\',\r\n					\'4\'\r\n					)'),
('2018-09-01 13:17:59', 'admin', 'INSERT INTO hrpayroll_groups_payroll_categories\r\n						(payroll_group_id,\r\n							payroll_category_id,\r\n							sort_order )\r\n					VALUES (\'1\',\r\n					\'5\',\r\n					\'5\'\r\n					)'),
('2018-09-01 13:19:59', 'admin', 'INSERT INTO hremployeecategories\r\n						(category_name,\r\n						category_prefix,status)\r\n					VALUES (\'Executive\',\r\n\'EX\',\r\n\'1\'\r\n)'),
('2018-09-01 13:20:18', 'admin', 'INSERT INTO hremployeecategories\r\n						(category_name,\r\n						category_prefix,status)\r\n					VALUES (\'Manager\',\r\n\'MAN\',\r\n\'1\'\r\n)'),
('2018-09-01 13:21:43', 'admin', 'INSERT INTO hremployeegradings\r\n						(grading_name,\r\n						priority,grading_description,grading_status)\r\n					VALUES (\'A\',\r\n\'1\',\r\n\'sdfsdfadsfadf\',\r\n\'1\'\r\n)'),
('2018-09-01 13:22:51', 'admin', 'INSERT INTO hremployeegradings\r\n						(grading_name,\r\n						priority,grading_description,grading_status)\r\n					VALUES (\'B\',\r\n\'2\',\r\n\'sdfsdfadsfadf\',\r\n\'1\'\r\n)'),
('2018-09-01 13:23:34', 'admin', 'INSERT INTO hremployeepositions\r\n						(position_name,\r\n						employee_category_id,position_status)\r\n					VALUES (\'Junior\',\r\n\'1\',\r\n\'1\'\r\n)'),
('2018-09-01 13:23:51', 'admin', 'INSERT INTO hremployeepositions\r\n						(position_name,\r\n						employee_category_id,position_status)\r\n					VALUES (\'Senior\',\r\n\'2\',\r\n\'1\'\r\n)'),
('2018-09-01 13:29:09', 'admin', 'INSERT INTO hremployees (employee_id,\r\n						user_id,\r\n						joining_date,\r\n						first_name,\r\n						middle_name,\r\n						last_name,\r\n						gender,\r\n						employee_position,\r\n						employee_grade_id,\r\n						job_title,\r\n						resume,\r\n						employee_department,\r\n						date_of_birth,\r\n						marital_status,\r\n						father_name,\r\n						mother_name,\r\n						nationality,\r\n						national_id,\r\n						passport_no,\r\n						home_address,\r\n						mobile_phone,\r\n						manager_id,\r\n						email,\r\n						spouse_name,\r\n						spouse_phone_no,\r\n						bank_name,\r\n						social_security_no,\r\n						bank_account_no)\r\n    						VALUES (\r\n									\'1\',\r\n									\'steve\',\r\n									\'2018-09-01\',\r\n									\'Steve\',\r\n									\'\',\r\n									\'Jobs\',\r\n									\'male\',\r\n									\'1\',\r\n									\'2\',\r\n    							\'Accounts Executive\',\r\n    							\'\',\r\n    							\'1\',\r\n									\'2018-09-01\',\r\n									\'single\',\r\n									\'\',\r\n									\'\',\r\n									\'India\',\r\n    							\'\',\r\n    							\'\',\r\n    							\'324523452345sdasfdsfa\',\r\n									\'3542345234\',\r\n									\'0\',\r\n    							\'\',\r\n									\'\',\r\n									\'\',\r\n									\'asdfasd\',\r\n									\'\',\r\n									\'32423423\'\r\n									 )'),
('2018-09-02 11:49:36', 'admin', 'UPDATE config SET confvalue = \'2018-09-02\' WHERE confname=\'UpdateCurrencyRatesDaily\''),
('2018-09-02 11:51:04', 'admin', 'INSERT INTO pctypetabs\r\n						(typetabcode,\r\n			 			 typetabdescription)\r\n				VALUES (\'Travel\',\r\n					\'Travelling Expenses\')'),
('2018-09-02 11:51:17', 'admin', 'INSERT INTO pctypetabs\r\n						(typetabcode,\r\n			 			 typetabdescription)\r\n				VALUES (\'Vehicle\',\r\n					\'Vehicles\')'),
('2018-09-02 11:57:32', 'admin', 'INSERT INTO tags values(NULL, \'Sales\')'),
('2018-09-02 11:57:39', 'admin', 'INSERT INTO tags values(NULL, \'Production\')'),
('2018-09-02 11:58:51', 'admin', 'INSERT INTO bankaccounts (accountcode,\r\n										bankaccountname,\r\n										bankaccountcode,\r\n										bankaccountnumber,\r\n										bankaddress,\r\n										currcode,\r\n										invoice,\r\n										importformat\r\n									) VALUES (\'1030\',\r\n										\'Vijaya Bank\',\r\n										\'ASFDA123\',\r\n										\'21341324322234\',\r\n										\'thane\',\r\n										\'INR\',\r\n										\'1\',\r\n										\'\' )'),
('2018-09-02 12:00:15', 'admin', 'INSERT INTO bankaccountusers (accountcode,\r\n												userid)\r\n										VALUES (\'1030\',\r\n												\'admin\')'),
('2018-09-02 12:06:09', 'admin', 'INSERT INTO pctabs	(tabcode,\r\n							 			 usercode,\r\n										 typetabcode,\r\n										 currency,\r\n										 tablimit,\r\n										 assigner,\r\n										 authorizer,\r\n										 authorizerexpenses,\r\n										 glaccountassignment,\r\n										 glaccountpcash,\r\n										 defaulttag,\r\n										 taxgroupid)\r\n								VALUES (\'Tickets\',\r\n									\'admin\',\r\n									\'Travel\',\r\n									\'INR\',\r\n									\'10000\',\r\n									\'admin\',\r\n									\'admin\',\r\n									\'admin\',\r\n									\'1030\',\r\n									\'1010\',\r\n									\'1\',\r\n									\'1\'\r\n								)'),
('2018-09-02 12:06:51', 'admin', 'INSERT INTO pctabs	(tabcode,\r\n							 			 usercode,\r\n										 typetabcode,\r\n										 currency,\r\n										 tablimit,\r\n										 assigner,\r\n										 authorizer,\r\n										 authorizerexpenses,\r\n										 glaccountassignment,\r\n										 glaccountpcash,\r\n										 defaulttag,\r\n										 taxgroupid)\r\n								VALUES (\'Fuel\',\r\n									\'admin\',\r\n									\'Vehicle\',\r\n									\'INR\',\r\n									\'10000\',\r\n									\'admin\',\r\n									\'admin\',\r\n									\'admin\',\r\n									\'1030\',\r\n									\'1010\',\r\n									\'1\',\r\n									\'1\'\r\n								)'),
('2018-09-02 12:09:33', 'admin', 'INSERT INTO pcexpenses\r\n						(codeexpense,\r\n			 			 description,\r\n			 			 glaccount,\r\n			 			 tag,\r\n			 			 taxcatid)\r\n				VALUES (\'BT\',\r\n						\'Bus Tickets\',\r\n						\'1010\',\r\n						\'1\',\r\n						\'6\'\r\n						)'),
('2018-09-02 12:10:30', 'admin', 'INSERT INTO pcexpenses\r\n						(codeexpense,\r\n			 			 description,\r\n			 			 glaccount,\r\n			 			 tag,\r\n			 			 taxcatid)\r\n				VALUES (\'Petrol\',\r\n						\'Petrol\',\r\n						\'1010\',\r\n						\'1\',\r\n						\'4\'\r\n						)'),
('2018-09-02 12:10:51', 'admin', 'INSERT INTO pctabexpenses (typetabcode,\r\n												codeexpense)\r\n										VALUES (\'TRAVEL\',\r\n												\'BT\')'),
('2018-09-02 12:11:58', 'admin', 'INSERT INTO pctabexpenses (typetabcode,\r\n												codeexpense)\r\n										VALUES (\'VEHICLE\',\r\n												\'Petrol\')'),
('2018-09-02 12:24:28', 'admin', 'INSERT INTO fixedassetcategories (categoryid,\r\n												categorydescription,\r\n												costact,\r\n												depnact,\r\n												disposalact,\r\n												accumdepnact)\r\n								VALUES (\'COMP\',\r\n										\'Computers\',\r\n										\'1720\',\r\n										\'7750\',\r\n										\'7700\',\r\n										\'1730\')'),
('2018-09-02 12:26:00', 'admin', 'INSERT INTO fixedassetlocations\r\n				VALUES (\'HO\',\r\n						\'Head Office\',\r\n						\'\')'),
('2018-09-02 12:28:03', 'admin', 'INSERT INTO fixedassets (description,\r\n											longdescription,\r\n											assetcategoryid,\r\n											assetlocation,\r\n											depntype,\r\n											depnrate,\r\n											barcode,\r\n											serialno)\r\n						VALUES (\r\n							\'Lenevo5310\',\r\n							\'Lenevo Laptop 4GB\',\r\n							\'COMP\',\r\n							\'HO\',\r\n							\'0\',\r\n							\'30\',\r\n							\'\',\r\n							\'1234354678\' )'),
('2018-09-02 12:28:40', 'admin', 'INSERT INTO fixedassets (description,\r\n											longdescription,\r\n											assetcategoryid,\r\n											assetlocation,\r\n											depntype,\r\n											depnrate,\r\n											barcode,\r\n											serialno)\r\n						VALUES (\r\n							\'Lenevo5310\',\r\n							\'Lenevo Laptop 4GB\',\r\n							\'COMP\',\r\n							\'HO\',\r\n							\'0\',\r\n							\'30.00\',\r\n							\'\',\r\n							\'1234354678\' )'),
('2018-09-02 12:29:28', 'admin', 'INSERT INTO fixedassettasks (assetid,\r\n											taskdescription,\r\n											frequencydays,\r\n											userresponsible,\r\n											manager,\r\n											lastcompleted)\r\n						VALUES( \'1\',\r\n								\'Annual Maintenance\',\r\n								\'30\',\r\n								\'admin\',\r\n								\'admin\',\r\n								\'2018-09-02\' )'),
('2018-09-02 12:32:32', 'admin', 'INSERT INTO qatests (name,\r\n						method,\r\n						groupby,\r\n						units,\r\n						type,\r\n						defaultvalue,\r\n						numericvalue,\r\n						showoncert,\r\n						showonspec,\r\n						showontestplan,\r\n						active)\r\n				VALUES (\'Display LED\',\r\n					\'ISO\',\r\n					\'\',\r\n					\'candelas (cd) per sq\',\r\n					\'4\',\r\n					\'450-500\',\r\n					\'1\',\r\n					\'1\',\r\n					\'1\',\r\n					\'1\',\r\n					\'1\'\r\n					)'),
('2018-09-02 12:33:04', 'admin', 'INSERT INTO qatests (name,\r\n						method,\r\n						groupby,\r\n						units,\r\n						type,\r\n						defaultvalue,\r\n						numericvalue,\r\n						showoncert,\r\n						showonspec,\r\n						showontestplan,\r\n						active)\r\n				VALUES (\'Ram Speed\',\r\n					\'ISO\',\r\n					\'\',\r\n					\'RPM\',\r\n					\'4\',\r\n					\'1200-1500\',\r\n					\'1\',\r\n					\'1\',\r\n					\'1\',\r\n					\'1\',\r\n					\'1\'\r\n					)'),
('2018-09-02 12:35:14', 'admin', 'INSERT INTO prodspecs\r\n							(keyval,\r\n							testid,\r\n							defaultvalue,\r\n							targetvalue,\r\n							rangemin,\r\n							rangemax,\r\n							showoncert,\r\n							showonspec,\r\n							showontestplan,\r\n							active)\r\n						SELECT \'DISPLAY SIZE\',\r\n								testid,\r\n								defaultvalue,\r\n								\'475\',\r\n								\'450\',\r\n								\'500\',\r\n								showoncert,\r\n								showonspec,\r\n								showontestplan,\r\n								active\r\n						FROM qatests WHERE testid=\'1\''),
('2018-09-02 12:35:14', 'admin', 'INSERT INTO prodspecs\r\n							(keyval,\r\n							testid,\r\n							defaultvalue,\r\n							targetvalue,\r\n							rangemin,\r\n							rangemax,\r\n							showoncert,\r\n							showonspec,\r\n							showontestplan,\r\n							active)\r\n						SELECT \'DISPLAY SIZE\',\r\n								testid,\r\n								defaultvalue,\r\n								\'1300\',\r\n								\'1200\',\r\n								\'1500\',\r\n								showoncert,\r\n								showonspec,\r\n								showontestplan,\r\n								active\r\n						FROM qatests WHERE testid=\'2\''),
('2018-09-02 12:41:47', 'admin', 'INSERT INTO qasamples (prodspeckey,\n											lotkey,\n											identifier,\n											comments,\n											cert,\n											createdby,\n											sampledate)\n								VALUES(\'DISPLAY SIZE\',\n										\'123\',\n										\'sadfsadfas\',\n										\'ssafdasdfasd\',\n										\'1\',\n										\'admin\',\n										\'2018-09-02\')'),
('2018-09-02 12:41:47', 'admin', 'INSERT INTO sampleresults (sampleid,\n											testid,\n											defaultvalue,\n											targetvalue,\n											rangemin,\n											rangemax,\n											showoncert,\n											showontestplan)\n								SELECT \'1\',\n											testid,\n											defaultvalue,\n											targetvalue,\n											rangemin,\n											rangemax,\n											showoncert,\n											showontestplan\n											FROM prodspecs WHERE keyval=\'DISPLAY SIZE\'\n											AND prodspecs.active=\'1\''),
('2018-09-02 12:43:14', 'admin', 'INSERT INTO qasamples (prodspeckey,\n											lotkey,\n											identifier,\n											comments,\n											cert,\n											createdby,\n											sampledate)\n								VALUES(\'DISPLAY SIZE\',\n										\'123\',\n										\'sadfsadfas\',\n										\'ssafdasdfasd\',\n										\'1\',\n										\'admin\',\n										\'2018-09-02\')'),
('2018-09-02 12:43:14', 'admin', 'INSERT INTO sampleresults (sampleid,\n											testid,\n											defaultvalue,\n											targetvalue,\n											rangemin,\n											rangemax,\n											showoncert,\n											showontestplan)\n								SELECT \'2\',\n											testid,\n											defaultvalue,\n											targetvalue,\n											rangemin,\n											rangemax,\n											showoncert,\n											showontestplan\n											FROM prodspecs WHERE keyval=\'DISPLAY SIZE\'\n											AND prodspecs.active=\'1\''),
('2018-09-02 17:19:45', 'admin', 'INSERT INTO qasamples (prodspeckey,\n											lotkey,\n											identifier,\n											comments,\n											cert,\n											createdby,\n											sampledate)\n								VALUES(\'DISPLAY SIZE\',\n										\'456\',\n										\'asdfasdfas\',\n										\'sdafsdaadsfasas\',\n										\'1\',\n										\'admin\',\n										\'2018-09-02\')'),
('2018-09-02 17:19:45', 'admin', 'INSERT INTO sampleresults (sampleid,\n											testid,\n											defaultvalue,\n											targetvalue,\n											rangemin,\n											rangemax,\n											showoncert,\n											showontestplan)\n								SELECT \'3\',\n											testid,\n											defaultvalue,\n											targetvalue,\n											rangemin,\n											rangemax,\n											showoncert,\n											showontestplan\n											FROM prodspecs WHERE keyval=\'DISPLAY SIZE\'\n											AND prodspecs.active=\'1\''),
('2018-09-03 17:38:23', 'admin', 'UPDATE config SET confvalue = \'2018-09-03\' WHERE confname=\'UpdateCurrencyRatesDaily\''),
('2018-09-03 18:55:24', 'admin', 'UPDATE systypes SET typeno = typeno + 1 WHERE typeid = \'500\''),
('2018-09-03 18:55:24', 'admin', 'INSERT INTO debtorsmaster (\r\n							debtorno,\r\n							name,\r\n							address1,\r\n							address2,\r\n							address3,\r\n							address4,\r\n							address5,\r\n							address6,\r\n							currcode,\r\n							clientsince,\r\n							holdreason,\r\n							paymentterms,\r\n							discount,\r\n							discountcode,\r\n							pymtdiscount,\r\n							creditlimit,\r\n							salestype,\r\n							invaddrbranch,\r\n							taxref,\r\n							customerpoline,\r\n							typeid,\r\n							language_id)\r\n				VALUES (\'1\',\r\n						\'Apple Computers\',\r\n						\'asdffa\',\r\n						\'sadfasdfa\',\r\n						\'\',\r\n						\'\',\r\n						\'23423432\',\r\n						\'India\',\r\n						\'INR\',\r\n						\'2018-09-03\',\r\n						\'1\',\r\n						\'20\',\r\n						\'0.05\',\r\n						\'\',\r\n						\'0.02\',\r\n						\'10000000\',\r\n						\'De\',\r\n						\'0\',\r\n						\'ADSFSD2423423ADS\',\r\n						\'1\',\r\n						\'4\',\r\n						\'en_IN.utf8\')'),
('2018-09-03 18:57:57', 'admin', 'INSERT INTO custbranch (branchcode,\r\n						debtorno,\r\n						brname,\r\n						braddress1,\r\n						braddress2,\r\n						braddress3,\r\n						braddress4,\r\n						braddress5,\r\n						braddress6,\r\n						lat,\r\n						lng,\r\n 						specialinstructions,\r\n						estdeliverydays,\r\n						fwddate,\r\n						salesman,\r\n						phoneno,\r\n						faxno,\r\n						contactname,\r\n						area,\r\n						email,\r\n						taxgroupid,\r\n						defaultlocation,\r\n						brpostaddr1,\r\n						brpostaddr2,\r\n						brpostaddr3,\r\n						brpostaddr4,\r\n						brpostaddr5,\r\n						disabletrans,\r\n						defaultshipvia,\r\n						custbranchcode,\r\n						deliverblind)\r\n				VALUES (\'1\',\r\n					\'1\',\r\n					\'Apple1\',\r\n					\'asdffa\',\r\n					\'sadfasdfa\',\r\n					\'\',\r\n					\'\',\r\n					\'23423432\',\r\n					\'India\',\r\n					\'0\',\r\n					\'0\',\r\n					\'\',\r\n					\'15\',\r\n					\'20\',\r\n					\'1\',\r\n					\'3425342\',\r\n					\'3425432\',\r\n					\'asdfadsf\',\r\n					\'1\',\r\n					\'admin@admin.com\',\r\n					\'1\',\r\n					\'BAN\',\r\n					\'\',\r\n					\'\',\r\n					\'\',\r\n					\'\',\r\n					\'\',\r\n					\'0\',\r\n					\'1\',\r\n					\'\',\r\n					\'1\')'),
('2018-09-03 18:59:34', 'admin', 'INSERT INTO custbranch (branchcode,\r\n						debtorno,\r\n						brname,\r\n						braddress1,\r\n						braddress2,\r\n						braddress3,\r\n						braddress4,\r\n						braddress5,\r\n						braddress6,\r\n						lat,\r\n						lng,\r\n 						specialinstructions,\r\n						estdeliverydays,\r\n						fwddate,\r\n						salesman,\r\n						phoneno,\r\n						faxno,\r\n						contactname,\r\n						area,\r\n						email,\r\n						taxgroupid,\r\n						defaultlocation,\r\n						brpostaddr1,\r\n						brpostaddr2,\r\n						brpostaddr3,\r\n						brpostaddr4,\r\n						brpostaddr5,\r\n						disabletrans,\r\n						defaultshipvia,\r\n						custbranchcode,\r\n						deliverblind)\r\n				VALUES (\'2\',\r\n					\'1\',\r\n					\'AppleW\',\r\n					\'asfads\',\r\n					\'asdfads\',\r\n					\'asdfadsfads\',\r\n					\'\',\r\n					\'\',\r\n					\'India\',\r\n					\'0\',\r\n					\'0\',\r\n					\'Don&#039;t Deliver without PO\',\r\n					\'15\',\r\n					\'0\',\r\n					\'2\',\r\n					\'3425342\',\r\n					\'3425432\',\r\n					\'asdfadsf\',\r\n					\'4\',\r\n					\'admin1@admin.com\',\r\n					\'2\',\r\n					\'MUM\',\r\n					\'\',\r\n					\'\',\r\n					\'\',\r\n					\'\',\r\n					\'\',\r\n					\'0\',\r\n					\'1\',\r\n					\'\',\r\n					\'1\')'),
('2018-09-03 18:59:45', 'admin', 'UPDATE custbranch SET brname = \'AppleS\',\r\n						braddress1 = \'asdffa\',\r\n						braddress2 = \'sadfasdfa\',\r\n						braddress3 = \'\',\r\n						braddress4 = \'\',\r\n						braddress5 = \'23423432\',\r\n						braddress6 = \'India\',\r\n						lat = \'0\',\r\n						lng = \'0\',\r\n						specialinstructions = \'\',\r\n						phoneno=\'3425342\',\r\n						faxno=\'3425432\',\r\n						fwddate= \'20\',\r\n						contactname=\'asdfadsf\',\r\n						salesman= \'1\',\r\n						area=\'1\',\r\n						estdeliverydays =\'15\',\r\n						email=\'admin@admin.com\',\r\n						taxgroupid=\'1\',\r\n						defaultlocation=\'BAN\',\r\n						brpostaddr1 = \'\',\r\n						brpostaddr2 = \'\',\r\n						brpostaddr3 = \'\',\r\n						brpostaddr4 = \'\',\r\n						brpostaddr5 = \'\',\r\n						disabletrans=\'0\',\r\n						defaultshipvia=\'1\',\r\n						custbranchcode=\'\',\r\n						deliverblind=\'1\'\r\n					WHERE branchcode = \'1\' AND debtorno=\'1\''),
('2018-09-03 19:05:10', 'admin', 'UPDATE systypes SET typeno = typeno + 1 WHERE typeid = \'600\''),
('2018-09-03 19:05:10', 'admin', 'INSERT INTO suppliers (supplierid,\r\n										suppname,\r\n										address1,\r\n										address2,\r\n										address3,\r\n										address4,\r\n										address5,\r\n										address6,\r\n										telephone,\r\n										fax,\r\n										email,\r\n										url,\r\n										supptype,\r\n										currcode,\r\n										suppliersince,\r\n										paymentterms,\r\n										bankpartics,\r\n										bankref,\r\n										bankact,\r\n										remittance,\r\n										taxgroupid,\r\n										factorcompanyid,\r\n										lat,\r\n										lng,\r\n										taxref)\r\n								 VALUES (\'1\',\r\n								 	\'Samsung Electronics\',\r\n									\'asdf\',\r\n									\'asdf\',\r\n									\'asdf\',\r\n									\'asdf\',\r\n									\'2342343\',\r\n									\'India\',\r\n									\'2423324\',\r\n									\'342343\',\r\n									\'dummy@dummy.com\',\r\n									\'\',\r\n									\'1\',\r\n									\'USD\',\r\n									\'2018-09-03\',\r\n									\'30\',\r\n									\'ICICI\',\r\n									\'2341234123\',\r\n									\'234143214214\',\r\n									\'1\',\r\n									\'2\',\r\n									\'0\',\r\n									\'0\',\r\n									\'0\',\r\n									\'SAFDDSFDS2344234ASD\')'),
('2018-09-04 09:39:53', 'admin', 'UPDATE currencies SET rate=\'0.014041984432738\'\n																			WHERE currabrev=\'USD\''),
('2018-09-04 09:39:53', 'admin', 'UPDATE config SET confvalue = \'2018-09-04\' WHERE confname=\'UpdateCurrencyRatesDaily\''),
('2018-09-04 09:51:48', 'admin', 'INSERT INTO stockmaster (stockid,\r\n												description,\r\n												longdescription,\r\n												hsn_code,\r\n												categoryid,\r\n												units,\r\n												mbflag,\r\n												eoq,\r\n												discontinued,\r\n												controlled,\r\n												serialised,\r\n												perishable,\r\n												volume,\r\n												grossweight,\r\n												netweight,\r\n												barcode,\r\n												discountcategory,\r\n												taxcatid,\r\n												decimalplaces,\r\n												shrinkfactor,\r\n												pansize)\r\n							VALUES (\'W123\',\r\n								\'sadfsdfasdfasdfasdassadfasf\',\r\n								\'sadfsdfasdfasdfasdassadfasfsadfsdfasdfasdfasdassadfasfsadfsdfasdfasdfasdassadfasf\',\r\n								\'35243523\',\r\n								\'4\',\r\n								\'each\',\r\n								\'B\',\r\n								\'0\',\r\n								\'0\',\r\n								\'0\',\r\n								\'0\',\r\n								\'0\',\r\n								\'0\',\r\n								\'0\',\r\n								\'0\',\r\n								\'\',\r\n								\'\',\r\n								\'1\',\r\n								\'0\',\r\n								\'0\',\r\n								\'0\')'),
('2018-09-04 09:51:48', 'admin', 'INSERT INTO locstock (loccode,\r\n													stockid)\r\n										SELECT locations.loccode,\r\n										\'W123\'\r\n										FROM locations'),
('2018-09-04 10:37:01', 'admin', 'INSERT INTO stockmaster (stockid,\r\n												description,\r\n												longdescription,\r\n												hsn_code,\r\n												categoryid,\r\n												units,\r\n												mbflag,\r\n												eoq,\r\n												discontinued,\r\n												controlled,\r\n												serialised,\r\n												perishable,\r\n												volume,\r\n												grossweight,\r\n												netweight,\r\n												barcode,\r\n												discountcategory,\r\n												taxcatid,\r\n												decimalplaces,\r\n												shrinkfactor,\r\n												pansize)\r\n							VALUES (\'RAM4\',\r\n								\'4GB RAM \',\r\n								\'4GB RAM 4GB RAM 4GB RAM 4GB RAM 4GB RAM \',\r\n								\'2314234\',\r\n								\'4\',\r\n								\'each\',\r\n								\'B\',\r\n								\'10\',\r\n								\'0\',\r\n								\'1\',\r\n								\'1\',\r\n								\'0\',\r\n								\'0\',\r\n								\'0\',\r\n								\'0\',\r\n								\'\',\r\n								\'\',\r\n								\'1\',\r\n								\'0\',\r\n								\'0\',\r\n								\'0\')'),
('2018-09-04 10:37:01', 'admin', 'INSERT INTO locstock (loccode,\r\n													stockid)\r\n										SELECT locations.loccode,\r\n										\'RAM4\'\r\n										FROM locations'),
('2018-09-04 10:37:17', 'admin', 'UPDATE stockmaster SET	materialcost=\'1200\',\r\n										labourcost=\'0\',\r\n										overheadcost=\'0\',\r\n										lastcost=\'0\',\r\n										lastcostupdate =\'2018-09-04\'\r\n								WHERE stockid=\'RAM4\''),
('2018-09-04 10:45:27', 'admin', 'INSERT INTO prices (stockid,\r\n									typeabbrev,\r\n									currabrev,\r\n									startdate,\r\n									enddate,\r\n									price)\r\n							VALUES (\'RAM4\',\r\n								\'De\',\r\n								\'INR\',\r\n								\'2018-09-04\',\r\n								\'9999-12-31\',\r\n								\'1500\')'),
('2018-09-04 11:05:07', 'admin', 'UPDATE stockcatproperties SET label =\'Screen\',\r\n													  controltype = 1,\r\n													  defaultvalue = \'4,5,6,7,8\',\r\n													  minimumvalue = \'1\',\r\n													  maximumvalue = \'8\',\r\n													  numericvalue = \'1\',\r\n													  reqatsalesorder = 0\r\n												WHERE stkcatpropid =3'),
('2018-09-04 11:05:15', 'admin', 'INSERT INTO stockmaster (stockid,\r\n												description,\r\n												longdescription,\r\n												hsn_code,\r\n												categoryid,\r\n												units,\r\n												mbflag,\r\n												eoq,\r\n												discontinued,\r\n												controlled,\r\n												serialised,\r\n												perishable,\r\n												volume,\r\n												grossweight,\r\n												netweight,\r\n												barcode,\r\n												discountcategory,\r\n												taxcatid,\r\n												decimalplaces,\r\n												shrinkfactor,\r\n												pansize)\r\n							VALUES (\'S9\',\r\n								\'Galaxy S9\',\r\n								\'4 GB RAM,128GB HDD\',\r\n								\'adsfafasdfads\',\r\n								\'2\',\r\n								\'each\',\r\n								\'M\',\r\n								\'0\',\r\n								\'0\',\r\n								\'1\',\r\n								\'1\',\r\n								\'0\',\r\n								\'0\',\r\n								\'0\',\r\n								\'0\',\r\n								\'\',\r\n								\'\',\r\n								\'2\',\r\n								\'0\',\r\n								\'0\',\r\n								\'0\')'),
('2018-09-04 11:05:15', 'admin', 'INSERT INTO stockitemproperties (stockid,\r\n													stkcatpropid,\r\n													value)\r\n													VALUES (\'S9\',\r\n														\'1\',\r\n														\'4\')'),
('2018-09-04 11:05:15', 'admin', 'INSERT INTO stockitemproperties (stockid,\r\n													stkcatpropid,\r\n													value)\r\n													VALUES (\'S9\',\r\n														\'2\',\r\n														\'128\')'),
('2018-09-04 11:05:15', 'admin', 'INSERT INTO stockitemproperties (stockid,\r\n													stkcatpropid,\r\n													value)\r\n													VALUES (\'S9\',\r\n														\'3\',\r\n														\'1\')'),
('2018-09-04 11:05:15', 'admin', 'INSERT INTO locstock (loccode,\r\n													stockid)\r\n										SELECT locations.loccode,\r\n										\'S9\'\r\n										FROM locations'),
('2018-09-04 11:05:51', 'admin', 'INSERT INTO prices (stockid,\r\n									typeabbrev,\r\n									currabrev,\r\n									startdate,\r\n									enddate,\r\n									price)\r\n							VALUES (\'S9\',\r\n								\'2\',\r\n								\'INR\',\r\n								\'2018-09-04\',\r\n								\'9999-12-31\',\r\n								\'45000\')'),
('2018-09-04 11:06:46', 'admin', 'UPDATE stockmaster SET	materialcost=\'5000\',\r\n										labourcost=\'2500\',\r\n										overheadcost=\'5000\',\r\n										lastcost=\'0\',\r\n										lastcostupdate =\'2018-09-04\'\r\n								WHERE stockid=\'S9\''),
('2018-09-04 11:07:35', 'admin', 'DELETE FROM locstock WHERE stockid=\'W123\''),
('2018-09-04 11:07:35', 'admin', 'DELETE FROM stockmaster WHERE stockid=\'W123\''),
('2018-09-04 11:10:20', 'admin', 'INSERT INTO stockmaster (stockid,\r\n												description,\r\n												longdescription,\r\n												hsn_code,\r\n												categoryid,\r\n												units,\r\n												mbflag,\r\n												eoq,\r\n												discontinued,\r\n												controlled,\r\n												serialised,\r\n												perishable,\r\n												volume,\r\n												grossweight,\r\n												netweight,\r\n												barcode,\r\n												discountcategory,\r\n												taxcatid,\r\n												decimalplaces,\r\n												shrinkfactor,\r\n												pansize)\r\n							VALUES (\'HDD128\',\r\n								\'HDD128\',\r\n								\'HDD128HDD128HDD128HDD128\',\r\n								\'434234234\',\r\n								\'4\',\r\n								\'each\',\r\n								\'B\',\r\n								\'0\',\r\n								\'0\',\r\n								\'1\',\r\n								\'1\',\r\n								\'0\',\r\n								\'0\',\r\n								\'0\',\r\n								\'0\',\r\n								\'\',\r\n								\'\',\r\n								\'1\',\r\n								\'0\',\r\n								\'0\',\r\n								\'0\')'),
('2018-09-04 11:10:20', 'admin', 'INSERT INTO locstock (loccode,\r\n													stockid)\r\n										SELECT locations.loccode,\r\n										\'HDD128\'\r\n										FROM locations'),
('2018-09-04 11:10:44', 'admin', 'UPDATE stockmaster SET	materialcost=\'2500\',\r\n										labourcost=\'0\',\r\n										overheadcost=\'0\',\r\n										lastcost=\'0\',\r\n										lastcostupdate =\'2018-09-04\'\r\n								WHERE stockid=\'HDD128\''),
('2018-09-04 11:10:59', 'admin', 'INSERT INTO prices (stockid,\r\n									typeabbrev,\r\n									currabrev,\r\n									startdate,\r\n									enddate,\r\n									price)\r\n							VALUES (\'HDD128\',\r\n								\'De\',\r\n								\'INR\',\r\n								\'2018-09-04\',\r\n								\'9999-12-31\',\r\n								\'3000\')'),
('2018-09-04 11:11:42', 'admin', 'INSERT INTO stockmaster (stockid,\r\n												description,\r\n												longdescription,\r\n												hsn_code,\r\n												categoryid,\r\n												units,\r\n												mbflag,\r\n												eoq,\r\n												discontinued,\r\n												controlled,\r\n												serialised,\r\n												perishable,\r\n												volume,\r\n												grossweight,\r\n												netweight,\r\n												barcode,\r\n												discountcategory,\r\n												taxcatid,\r\n												decimalplaces,\r\n												shrinkfactor,\r\n												pansize)\r\n							VALUES (\'SCREEN6\',\r\n								\'Screen6\',\r\n								\'Screen6Screen6Screen6Screen6\',\r\n								\'32423425432\',\r\n								\'5\',\r\n								\'each\',\r\n								\'B\',\r\n								\'0\',\r\n								\'0\',\r\n								\'1\',\r\n								\'0\',\r\n								\'0\',\r\n								\'0\',\r\n								\'0\',\r\n								\'0\',\r\n								\'\',\r\n								\'\',\r\n								\'1\',\r\n								\'0\',\r\n								\'0\',\r\n								\'0\')'),
('2018-09-04 11:11:42', 'admin', 'INSERT INTO locstock (loccode,\r\n													stockid)\r\n										SELECT locations.loccode,\r\n										\'SCREEN6\'\r\n										FROM locations'),
('2018-09-04 11:11:52', 'admin', 'UPDATE stockmaster SET	materialcost=\'1000\',\r\n										labourcost=\'0\',\r\n										overheadcost=\'0\',\r\n										lastcost=\'0\',\r\n										lastcostupdate =\'2018-09-04\'\r\n								WHERE stockid=\'SCREEN6\''),
('2018-09-04 11:12:05', 'admin', 'INSERT INTO prices (stockid,\r\n									typeabbrev,\r\n									currabrev,\r\n									startdate,\r\n									enddate,\r\n									price)\r\n							VALUES (\'SCREEN6\',\r\n								\'De\',\r\n								\'INR\',\r\n								\'2018-09-04\',\r\n								\'9999-12-31\',\r\n								\'1500\')'),
('2018-09-04 11:22:58', 'admin', 'INSERT INTO workcentres (code,\r\n										location,\r\n										description,\r\n										overheadrecoveryact,\r\n										overheadperhour)\r\n					VALUES (\'1s9\',\r\n						\'BAN\',\r\n						\'Assembly S9\',\r\n						\'5000\',\r\n						\'100\'\r\n						)'),
('2018-09-04 11:25:09', 'admin', 'INSERT INTO bom (sequence,\r\n									digitals,\r\n											parent,\r\n											component,\r\n											workcentreadded,\r\n											loccode,\r\n											quantity,\r\n											effectiveafter,\r\n											effectiveto,\r\n											autoissue,\r\n											remark)\r\n							VALUES (\'0\',\r\n								\'0\',\r\n								\'S9\',\r\n								\'RAM4\',\r\n								\'1s9\',\r\n								\'BAN\',\r\n								1,\r\n								\'2018-09-03\',\r\n								\'2020-01-01\',\r\n								0,\r\n								\'\')'),
('2018-09-04 11:25:26', 'admin', 'INSERT INTO bom (sequence,\r\n									digitals,\r\n											parent,\r\n											component,\r\n											workcentreadded,\r\n											loccode,\r\n											quantity,\r\n											effectiveafter,\r\n											effectiveto,\r\n											autoissue,\r\n											remark)\r\n							VALUES (\'1\',\r\n								\'0\',\r\n								\'S9\',\r\n								\'HDD128\',\r\n								\'1s9\',\r\n								\'BAN\',\r\n								1,\r\n								\'2018-09-03\',\r\n								\'2020-01-01\',\r\n								0,\r\n								\'\')');
INSERT INTO `audittrail` (`transactiondate`, `userid`, `querystring`) VALUES
('2018-09-04 11:25:39', 'admin', 'INSERT INTO bom (sequence,\r\n									digitals,\r\n											parent,\r\n											component,\r\n											workcentreadded,\r\n											loccode,\r\n											quantity,\r\n											effectiveafter,\r\n											effectiveto,\r\n											autoissue,\r\n											remark)\r\n							VALUES (\'2\',\r\n								\'0\',\r\n								\'S9\',\r\n								\'SCREEN6\',\r\n								\'1s9\',\r\n								\'BAN\',\r\n								1,\r\n								\'2018-09-03\',\r\n								\'2020-01-01\',\r\n								0,\r\n								\'\')'),
('2018-09-04 11:33:42', 'admin', 'INSERT INTO prices (stockid,\r\n									typeabbrev,\r\n									currabrev,\r\n									startdate,\r\n									enddate,\r\n									price)\r\n							VALUES (\'S9\',\r\n								\'De\',\r\n								\'INR\',\r\n								\'2018-09-04\',\r\n								\'9999-12-31\',\r\n								\'50000\')'),
('2018-09-04 11:47:02', 'admin', 'UPDATE systypes SET typeno = typeno + 1 WHERE typeid = \'30\''),
('2018-09-04 11:47:02', 'admin', 'INSERT INTO salesorders (\r\n								orderno,\r\n								debtorno,\r\n								branchcode,\r\n								customerref,\r\n								comments,\r\n								orddate,\r\n								ordertype,\r\n								shipvia,\r\n								deliverto,\r\n								deladd1,\r\n								deladd2,\r\n								deladd3,\r\n								deladd4,\r\n								deladd5,\r\n								deladd6,\r\n								contactphone,\r\n								contactemail,\r\n								salesperson,\r\n								freightcost,\r\n								fromstkloc,\r\n								deliverydate,\r\n								quotedate,\r\n								confirmeddate,\r\n								quotation,\r\n								deliverblind)\r\n							VALUES (\r\n								\'1\',\r\n								\'1\',\r\n								\'1\',\r\n								\'\',\r\n								\'\',\r\n								\'2018-09-04\',\r\n								\'De\',\r\n								\'1\',\r\n								\'AppleS\',\r\n								\'asdffa\',\r\n								\'sadfasdfa\',\r\n								\'\',\r\n								\'\',\r\n								\'23423432\',\r\n								\'India\',\r\n								\'3425342\',\r\n								\'admin@admin.com\',\r\n								\'1\',\r\n								\'0\',\r\n								\'BAN\',\r\n								\'2018-09-04\',\r\n								\'2018-09-04\',\r\n								\'2018-09-04\',\r\n								\'1\',\r\n								\'1\'\r\n								)'),
('2018-09-04 11:47:02', 'admin', 'INSERT INTO salesorderdetails (\r\n											orderlineno,\r\n											orderno,\r\n											stkcode,\r\n											unitprice,\r\n											quantity,\r\n											discountpercent,\r\n											narrative,\r\n											poline,\r\n											itemdue)\r\n										VALUES (\r\n					\'0\',\r\n					\'1\',\r\n					\'S9\',\r\n					\'50000.00\',\r\n					\'10\',\r\n					\'0.12\',\r\n					\'asdfhasdjfjkdas234232\',\r\n					\'12312asdsd\',\r\n					\'2018-09-04\'\r\n				)'),
('2018-09-04 12:22:14', 'admin', 'UPDATE salesorders SET debtorno = \'1\',\r\n										branchcode = \'1\',\r\n										customerref = \'asdfasdf\',\r\n										comments = \'sadfadsfasdfasdfadsfsdafas\',\r\n										ordertype = \'De\',\r\n										shipvia = \'1\',\r\n										deliverydate = \'2018-09-04\',\r\n										quotedate = \'2018-09-04\',\r\n										confirmeddate = \'2018-09-04\',\r\n										deliverto = \'AppleS\',\r\n										deladd1 = \'asdffa\',\r\n										deladd2 = \'sadfasdfa\',\r\n										deladd3 = \'\',\r\n										deladd4 = \'\',\r\n										deladd5 = \'23423432\',\r\n										deladd6 = \'India\',\r\n										contactphone = \'3425342\',\r\n										contactemail = \'admin@admin.com\',\r\n										salesperson = \'1\',\r\n										freightcost = \'0\',\r\n										fromstkloc = \'BAN\',\r\n										printedpackingslip = \'0\',\r\n										quotation = \'1\',\r\n										deliverblind = \'1\'\r\n						WHERE salesorders.orderno=\'1\''),
('2018-09-05 09:57:15', 'admin', 'UPDATE currencies SET rate=\'0.013969419994805\'\n																			WHERE currabrev=\'USD\''),
('2018-09-05 09:57:15', 'admin', 'UPDATE config SET confvalue = \'2018-09-05\' WHERE confname=\'UpdateCurrencyRatesDaily\''),
('2018-09-05 09:57:54', 'admin', 'UPDATE www_users SET realname=\'Super Admin\',\r\n						customerid=\'\',\r\n						phone=\'\',\r\n						email=\'sales@netelity.com\',\r\n						password=\'$2y$10$nzSdf/BWnM4Wkp2bk02zqOXJUBibgM8lA83UujOeOle.vFSjYm/aW\',\r\n						branchcode=\'\',\r\n						supplierid=\'\',\r\n						salesman=\'\',\r\n						pagesize=\'A4\',\r\n						fullaccess=\'8\',\r\n						cancreatetender=\'1\',\r\n						theme=\'fluid\',\r\n						language =\'en_IN.utf8\',\r\n						defaultlocation=\'BAN\',\r\n						modulesallowed=\'1,1,1,1,1,1,1,1,1,1,1,1,\',\r\n						showdashboard=\'1\',\r\n						showpagehelp=\'0\',\r\n						showfieldhelp=\'0\',\r\n						blocked=\'0\',\r\n						pdflanguage=\'0\',\r\n						department=\'0\'\r\n					WHERE userid = \'admin\''),
('2018-09-05 10:01:05', 'admin', 'INSERT INTO locationusers (loccode,\r\n													userid,\r\n													canview,\r\n													canupd\r\n												) VALUES (\r\n													\'BAN\',\r\n													\'demo\',\r\n													1,\r\n													1\r\n												)'),
('2018-09-05 10:01:05', 'admin', 'INSERT INTO glaccountusers (userid, accountcode, canview, canupd)\r\n						 SELECT \'demo\', chartmaster.accountcode,1,1\r\n						 FROM chartmaster;	'),
('2018-09-05 10:01:05', 'admin', 'INSERT INTO www_users (\r\n					userid,\r\n					realname,\r\n					customerid,\r\n					branchcode,\r\n					supplierid,\r\n					salesman,\r\n					password,\r\n					phone,\r\n					email,\r\n					pagesize,\r\n					fullaccess,\r\n					cancreatetender,\r\n					defaultlocation,\r\n					modulesallowed,\r\n					showdashboard,\r\n					showpagehelp,\r\n					showfieldhelp,\r\n					displayrecordsmax,\r\n					theme,\r\n					language,\r\n					pdflanguage,\r\n					department)\r\n				VALUES (\'demo\',\r\n					\'Demo User\',\r\n					\'\',\r\n					\'\',\r\n					\'\',\r\n					\'\',\r\n					\'$2y$10$1f2JjAI.0NabFnT9mRxeX.bDC8HwxFPuz3boQXv8CEtqW.F72wbh.\',\r\n					\'\',\r\n					\'sales@netelity.com\',\r\n					\'A4\',\r\n					\'8\',\r\n					\'1\',\r\n					\'BAN\',\r\n					\'1,1,1,1,1,1,1,1,1,1,1,0,\',\r\n					\'1\',\r\n					\'0\',\r\n					\'0\',\r\n					\'50\',\r\n					\'fluid\',\r\n					\'en_IN.utf8\',\r\n					\'0\',\r\n					\'0\')'),
('2018-09-05 10:01:51', 'admin', 'DELETE FROM taxgrouptaxes\r\n					WHERE taxgroupid = \'1\'\r\n					AND taxauthid = \'11\''),
('2018-09-05 10:01:58', 'admin', 'DELETE FROM taxauthrates WHERE taxauthority= \'11\''),
('2018-09-05 10:01:58', 'admin', 'DELETE FROM taxauthorities WHERE taxid= \'11\''),
('2018-09-05 10:02:15', 'admin', 'UPDATE taxauthorities\r\n					SET taxglcode =\'2300\',\r\n					purchtaxglaccount =\'2310\',\r\n					description = \'GST\',\r\n					bank = \'\',\r\n					bankacctype = \'\',\r\n					bankacc = \'\',\r\n					bankswift = \'\'\r\n				WHERE taxid = \'5\''),
('2018-09-05 10:02:38', 'admin', 'UPDATE taxauthrates SET taxrate=0.05\r\n						WHERE taxcatid = \'1\'\r\n						AND dispatchtaxprovince = \'1\'\r\n						AND taxauthority = \'5\''),
('2018-09-05 10:02:38', 'admin', 'UPDATE taxauthrates SET taxrate=0.12\r\n						WHERE taxcatid = \'2\'\r\n						AND dispatchtaxprovince = \'1\'\r\n						AND taxauthority = \'5\''),
('2018-09-05 10:02:38', 'admin', 'UPDATE taxauthrates SET taxrate=0.18\r\n						WHERE taxcatid = \'6\'\r\n						AND dispatchtaxprovince = \'1\'\r\n						AND taxauthority = \'5\''),
('2018-09-05 10:02:38', 'admin', 'UPDATE taxauthrates SET taxrate=0.28\r\n						WHERE taxcatid = \'7\'\r\n						AND dispatchtaxprovince = \'1\'\r\n						AND taxauthority = \'5\''),
('2018-09-05 10:05:58', 'admin', 'UPDATE salesorders SET debtorno = \'1\',\r\n										branchcode = \'1\',\r\n										customerref = \'asdfasdf\',\r\n										comments = \'sadfadsfasdfasdfadsfsdafas\',\r\n										ordertype = \'De\',\r\n										shipvia = \'1\',\r\n										deliverydate = \'2018-09-04\',\r\n										quotedate = \'2018-09-04\',\r\n										confirmeddate = \'2018-09-04\',\r\n										deliverto = \'AppleS\',\r\n										deladd1 = \'asdffa\',\r\n										deladd2 = \'sadfasdfa\',\r\n										deladd3 = \'\',\r\n										deladd4 = \'\',\r\n										deladd5 = \'23423432\',\r\n										deladd6 = \'India\',\r\n										contactphone = \'3425342\',\r\n										contactemail = \'admin@admin.com\',\r\n										salesperson = \'1\',\r\n										freightcost = \'0\',\r\n										fromstkloc = \'BAN\',\r\n										printedpackingslip = \'0\',\r\n										quotation = \'0\',\r\n										deliverblind = \'1\'\r\n						WHERE salesorders.orderno=\'1\''),
('2018-09-07 12:58:19', 'admin', 'UPDATE currencies SET rate=\'0.013896736046824\'\n																			WHERE currabrev=\'USD\''),
('2018-09-07 12:58:19', 'admin', 'UPDATE config SET confvalue = \'2018-09-07\' WHERE confname=\'UpdateCurrencyRatesDaily\''),
('2018-09-11 17:07:43', 'demo', 'UPDATE currencies SET rate=\'0.013801699716714\'\n																			WHERE currabrev=\'USD\''),
('2018-09-11 17:07:43', 'demo', 'UPDATE config SET confvalue = \'2018-09-11\' WHERE confname=\'UpdateCurrencyRatesDaily\''),
('2018-09-18 13:53:02', 'demo', 'UPDATE currencies SET rate=\'0.013789721807043\'\n																			WHERE currabrev=\'USD\''),
('2018-09-18 13:53:02', 'demo', 'UPDATE config SET confvalue = \'2018-09-18\' WHERE confname=\'UpdateCurrencyRatesDaily\''),
('2018-10-01 10:30:31', 'admin', 'UPDATE currencies SET rate=\'0.01379474712808\'\n																			WHERE currabrev=\'USD\''),
('2018-10-01 10:30:31', 'admin', 'UPDATE config SET confvalue = \'2018-10-01\' WHERE confname=\'UpdateCurrencyRatesDaily\'');

-- --------------------------------------------------------

--
-- Table structure for table `bankaccounts`
--

CREATE TABLE `bankaccounts` (
  `accountcode` varchar(20) NOT NULL DEFAULT '0',
  `currcode` char(3) NOT NULL,
  `invoice` smallint(2) NOT NULL DEFAULT '0',
  `bankaccountcode` varchar(50) NOT NULL DEFAULT '',
  `bankaccountname` char(50) NOT NULL DEFAULT '',
  `bankaccountnumber` char(50) NOT NULL DEFAULT '',
  `bankaddress` char(50) DEFAULT NULL,
  `importformat` varchar(10) NOT NULL DEFAULT ''''''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `bankaccounts`
--

INSERT INTO `bankaccounts` (`accountcode`, `currcode`, `invoice`, `bankaccountcode`, `bankaccountname`, `bankaccountnumber`, `bankaddress`, `importformat`) VALUES
('1030', 'INR', 1, 'ASFDA123', 'Vijaya Bank', '21341324322234', 'thane', '');

-- --------------------------------------------------------

--
-- Table structure for table `bankaccountusers`
--

CREATE TABLE `bankaccountusers` (
  `accountcode` varchar(20) NOT NULL COMMENT 'Bank account code',
  `userid` varchar(20) NOT NULL COMMENT 'User code'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `bankaccountusers`
--

INSERT INTO `bankaccountusers` (`accountcode`, `userid`) VALUES
('1030', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `banktrans`
--

CREATE TABLE `banktrans` (
  `banktransid` bigint(20) NOT NULL,
  `type` smallint(6) NOT NULL DEFAULT '0',
  `transno` bigint(20) NOT NULL DEFAULT '0',
  `bankact` varchar(20) NOT NULL DEFAULT '0',
  `ref` varchar(50) NOT NULL DEFAULT '',
  `amountcleared` double NOT NULL DEFAULT '0',
  `exrate` double NOT NULL DEFAULT '1' COMMENT 'From bank account currency to payment currency',
  `functionalexrate` double NOT NULL DEFAULT '1' COMMENT 'Account currency to functional currency',
  `transdate` date NOT NULL DEFAULT '0000-00-00',
  `banktranstype` varchar(30) NOT NULL DEFAULT '',
  `amount` double NOT NULL DEFAULT '0',
  `currcode` char(3) NOT NULL DEFAULT '',
  `chequeno` varchar(16) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `bom`
--

CREATE TABLE `bom` (
  `parent` char(20) NOT NULL DEFAULT '',
  `sequence` int(11) NOT NULL DEFAULT '0',
  `component` char(20) NOT NULL DEFAULT '',
  `workcentreadded` char(5) NOT NULL DEFAULT '',
  `loccode` char(5) NOT NULL DEFAULT '',
  `effectiveafter` date NOT NULL DEFAULT '0000-00-00',
  `effectiveto` date NOT NULL DEFAULT '9999-12-31',
  `quantity` double NOT NULL DEFAULT '1',
  `autoissue` tinyint(4) NOT NULL DEFAULT '0',
  `remark` varchar(500) NOT NULL DEFAULT '',
  `digitals` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `bom`
--

INSERT INTO `bom` (`parent`, `sequence`, `component`, `workcentreadded`, `loccode`, `effectiveafter`, `effectiveto`, `quantity`, `autoissue`, `remark`, `digitals`) VALUES
('S9', 1, 'HDD128', '1s9', 'BAN', '2018-09-03', '2020-01-01', 1, 0, '', 0),
('S9', 0, 'RAM4', '1s9', 'BAN', '2018-09-03', '2020-01-01', 1, 0, '', 0),
('S9', 2, 'SCREEN6', '1s9', 'BAN', '2018-09-03', '2020-01-01', 1, 0, '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `chartdetails`
--

CREATE TABLE `chartdetails` (
  `accountcode` varchar(20) NOT NULL DEFAULT '0',
  `period` smallint(6) NOT NULL DEFAULT '0',
  `budget` double NOT NULL DEFAULT '0',
  `actual` double NOT NULL DEFAULT '0',
  `bfwd` double NOT NULL DEFAULT '0',
  `bfwdbudget` double NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `chartdetails`
--

INSERT INTO `chartdetails` (`accountcode`, `period`, `budget`, `actual`, `bfwd`, `bfwdbudget`) VALUES
('1', -15, 0, 0, 0, 0),
('1', -14, 0, 0, 0, 0),
('1', -13, 0, 0, 0, 0),
('1', -12, 0, 0, 0, 0),
('1', -11, 0, 0, 0, 0),
('1', -10, 0, 0, 0, 0),
('1', -9, 0, 0, 0, 0),
('1', -8, 0, 0, 0, 0),
('1', -7, 0, 0, 0, 0),
('1', -6, 0, 0, 0, 0),
('1', -5, 0, 0, 0, 0),
('1', -4, 0, 0, 0, 0),
('1', -3, 0, 0, 0, 0),
('1', -2, 0, 0, 0, 0),
('1', -1, 0, 0, 0, 0),
('1', 0, 0, 0, 0, 0),
('1', 1, 0, 0, 0, 0),
('1', 2, 0, 0, 0, 0),
('1', 3, 0, 0, 0, 0),
('1', 4, 0, 0, 0, 0),
('1', 5, 0, 0, 0, 0),
('1', 6, 0, 0, 0, 0),
('1', 7, 0, 0, 0, 0),
('1', 8, 0, 0, 0, 0),
('1', 9, 0, 0, 0, 0),
('1', 10, 0, 0, 0, 0),
('1', 11, 0, 0, 0, 0),
('1', 12, 0, 0, 0, 0),
('1', 13, 0, 0, 0, 0),
('1', 14, 0, 0, 0, 0),
('1', 15, 0, 0, 0, 0),
('1', 16, 0, 0, 0, 0),
('1', 17, 0, 0, 0, 0),
('1', 18, 0, 0, 0, 0),
('1', 19, 0, 0, 0, 0),
('1', 20, 0, 0, 0, 0),
('1', 21, 0, 0, 0, 0),
('1', 22, 0, 0, 0, 0),
('1', 23, 0, 0, 0, 0),
('1', 24, 0, 0, 0, 0),
('1', 25, 0, 0, 0, 0),
('1', 26, 0, 0, 0, 0),
('1', 27, 0, 0, 0, 0),
('1', 28, 0, 0, 0, 0),
('1', 29, 0, 0, 0, 0),
('1', 30, 0, 0, 0, 0),
('1', 31, 0, 0, 0, 0),
('1', 32, 0, 0, 0, 0),
('1', 33, 0, 0, 0, 0),
('1', 34, 0, 0, 0, 0),
('1', 35, 0, 0, 0, 0),
('1', 36, 0, 0, 0, 0),
('1', 37, 0, 0, 0, 0),
('1', 38, 0, 0, 0, 0),
('1', 39, 0, 0, 0, 0),
('1', 40, 0, 0, 0, 0),
('1', 41, 0, 0, 0, 0),
('1', 42, 0, 0, 0, 0),
('1', 43, 0, 0, 0, 0),
('1', 44, 0, 0, 0, 0),
('1', 45, 0, 0, 0, 0),
('1', 46, 0, 0, 0, 0),
('1', 47, 0, 0, 0, 0),
('1', 48, 0, 0, 0, 0),
('1', 49, 0, 0, 0, 0),
('1', 50, 0, 0, 0, 0),
('1', 51, 0, 0, 0, 0),
('1', 52, 0, 0, 0, 0),
('1', 53, 0, 0, 0, 0),
('1', 54, 0, 0, 0, 0),
('1', 55, 0, 0, 0, 0),
('1', 56, 0, 0, 0, 0),
('1', 57, 0, 0, 0, 0),
('1', 58, 0, 0, 0, 0),
('1', 59, 0, 0, 0, 0),
('1010', -15, 0, 0, 0, 0),
('1010', -14, 0, 0, 0, 0),
('1010', -13, 0, 0, 0, 0),
('1010', -12, 0, 0, 0, 0),
('1010', -11, 0, 0, 0, 0),
('1010', -10, 0, 0, 0, 0),
('1010', -9, 0, 0, 0, 0),
('1010', -8, 0, 0, 0, 0),
('1010', -7, 0, 0, 0, 0),
('1010', -6, 0, 0, 0, 0),
('1010', -5, 0, 0, 0, 0),
('1010', -4, 0, 0, 0, 0),
('1010', -3, 0, 0, 0, 0),
('1010', -2, 0, 0, 0, 0),
('1010', -1, 0, 0, 0, 0),
('1010', 0, 0, 0, 0, 0),
('1010', 1, 0, 0, 0, 0),
('1010', 2, 0, 0, 0, 0),
('1010', 3, 0, 0, 0, 0),
('1010', 4, 0, 0, 0, 0),
('1010', 5, 0, 0, 0, 0),
('1010', 6, 0, 0, 0, 0),
('1010', 7, 0, 0, 0, 0),
('1010', 8, 0, 0, 0, 0),
('1010', 9, 0, 0, 0, 0),
('1010', 10, 0, 0, 0, 0),
('1010', 11, 0, 0, 0, 0),
('1010', 12, 0, 0, 0, 0),
('1010', 13, 0, 0, 0, 0),
('1010', 14, 0, 0, 0, 0),
('1010', 15, 0, 0, 0, 0),
('1010', 16, 0, 0, 0, 0),
('1010', 17, 0, 0, 0, 0),
('1010', 18, 0, 0, 0, 0),
('1010', 19, 0, 0, 0, 0),
('1010', 20, 0, 0, 0, 0),
('1010', 21, 0, 0, 0, 0),
('1010', 22, 0, 0, 0, 0),
('1010', 23, 0, 0, 0, 0),
('1010', 24, 0, 0, 0, 0),
('1010', 25, 0, 0, 0, 0),
('1010', 26, 0, 0, 0, 0),
('1010', 27, 0, 0, 0, 0),
('1010', 28, 0, 0, 0, 0),
('1010', 29, 0, 0, 0, 0),
('1010', 30, 0, 0, 0, 0),
('1010', 31, 0, 0, 0, 0),
('1010', 32, 0, 0, 0, 0),
('1010', 33, 0, 0, 0, 0),
('1010', 34, 0, 0, 0, 0),
('1010', 35, 0, 0, 0, 0),
('1010', 36, 0, 0, 0, 0),
('1010', 37, 0, 0, 0, 0),
('1010', 38, 0, 0, 0, 0),
('1010', 39, 0, 0, 0, 0),
('1010', 40, 0, 0, 0, 0),
('1010', 41, 0, 0, 0, 0),
('1010', 42, 0, 0, 0, 0),
('1010', 43, 0, 0, 0, 0),
('1010', 44, 0, 0, 0, 0),
('1010', 45, 0, 0, 0, 0),
('1010', 46, 0, 0, 0, 0),
('1010', 47, 0, 0, 0, 0),
('1010', 48, 0, 0, 0, 0),
('1010', 49, 0, 0, 0, 0),
('1010', 50, 0, 0, 0, 0),
('1010', 51, 0, 0, 0, 0),
('1010', 52, 0, 0, 0, 0),
('1010', 53, 0, 0, 0, 0),
('1010', 54, 0, 0, 0, 0),
('1010', 55, 0, 0, 0, 0),
('1010', 56, 0, 0, 0, 0),
('1010', 57, 0, 0, 0, 0),
('1010', 58, 0, 0, 0, 0),
('1010', 59, 0, 0, 0, 0),
('1020', -15, 0, 0, 0, 0),
('1020', -14, 0, 0, 0, 0),
('1020', -13, 0, 0, 0, 0),
('1020', -12, 0, 0, 0, 0),
('1020', -11, 0, 0, 0, 0),
('1020', -10, 0, 0, 0, 0),
('1020', -9, 0, 0, 0, 0),
('1020', -8, 0, 0, 0, 0),
('1020', -7, 0, 0, 0, 0),
('1020', -6, 0, 0, 0, 0),
('1020', -5, 0, 0, 0, 0),
('1020', -4, 0, 0, 0, 0),
('1020', -3, 0, 0, 0, 0),
('1020', -2, 0, 0, 0, 0),
('1020', -1, 0, 0, 0, 0),
('1020', 0, 0, 0, 0, 0),
('1020', 1, 0, 0, 0, 0),
('1020', 2, 0, 0, 0, 0),
('1020', 3, 0, 0, 0, 0),
('1020', 4, 0, 0, 0, 0),
('1020', 5, 0, 0, 0, 0),
('1020', 6, 0, 0, 0, 0),
('1020', 7, 0, 0, 0, 0),
('1020', 8, 0, 0, 0, 0),
('1020', 9, 0, 0, 0, 0),
('1020', 10, 0, 0, 0, 0),
('1020', 11, 0, 0, 0, 0),
('1020', 12, 0, 0, 0, 0),
('1020', 13, 0, 0, 0, 0),
('1020', 14, 0, 0, 0, 0),
('1020', 15, 0, 0, 0, 0),
('1020', 16, 0, 0, 0, 0),
('1020', 17, 0, 0, 0, 0),
('1020', 18, 0, 0, 0, 0),
('1020', 19, 0, 0, 0, 0),
('1020', 20, 0, 0, 0, 0),
('1020', 21, 0, 0, 0, 0),
('1020', 22, 0, 0, 0, 0),
('1020', 23, 0, 0, 0, 0),
('1020', 24, 0, 0, 0, 0),
('1020', 25, 0, 0, 0, 0),
('1020', 26, 0, 0, 0, 0),
('1020', 27, 0, 0, 0, 0),
('1020', 28, 0, 0, 0, 0),
('1020', 29, 0, 0, 0, 0),
('1020', 30, 0, 0, 0, 0),
('1020', 31, 0, 0, 0, 0),
('1020', 32, 0, 0, 0, 0),
('1020', 33, 0, 0, 0, 0),
('1020', 34, 0, 0, 0, 0),
('1020', 35, 0, 0, 0, 0),
('1020', 36, 0, 0, 0, 0),
('1020', 37, 0, 0, 0, 0),
('1020', 38, 0, 0, 0, 0),
('1020', 39, 0, 0, 0, 0),
('1020', 40, 0, 0, 0, 0),
('1020', 41, 0, 0, 0, 0),
('1020', 42, 0, 0, 0, 0),
('1020', 43, 0, 0, 0, 0),
('1020', 44, 0, 0, 0, 0),
('1020', 45, 0, 0, 0, 0),
('1020', 46, 0, 0, 0, 0),
('1020', 47, 0, 0, 0, 0),
('1020', 48, 0, 0, 0, 0),
('1020', 49, 0, 0, 0, 0),
('1020', 50, 0, 0, 0, 0),
('1020', 51, 0, 0, 0, 0),
('1020', 52, 0, 0, 0, 0),
('1020', 53, 0, 0, 0, 0),
('1020', 54, 0, 0, 0, 0),
('1020', 55, 0, 0, 0, 0),
('1020', 56, 0, 0, 0, 0),
('1020', 57, 0, 0, 0, 0),
('1020', 58, 0, 0, 0, 0),
('1020', 59, 0, 0, 0, 0),
('1030', -15, 0, 0, 0, 0),
('1030', -14, 0, 0, 0, 0),
('1030', -13, 0, 0, 0, 0),
('1030', -12, 0, 0, 0, 0),
('1030', -11, 0, 0, 0, 0),
('1030', -10, 0, 0, 0, 0),
('1030', -9, 0, 0, 0, 0),
('1030', -8, 0, 0, 0, 0),
('1030', -7, 0, 0, 0, 0),
('1030', -6, 0, 0, 0, 0),
('1030', -5, 0, 0, 0, 0),
('1030', -4, 0, 0, 0, 0),
('1030', -3, 0, 0, 0, 0),
('1030', -2, 0, 0, 0, 0),
('1030', -1, 0, 0, 0, 0),
('1030', 0, 0, 0, 0, 0),
('1030', 1, 0, 0, 0, 0),
('1030', 2, 0, 0, 0, 0),
('1030', 3, 0, 0, 0, 0),
('1030', 4, 0, 0, 0, 0),
('1030', 5, 0, 0, 0, 0),
('1030', 6, 0, 0, 0, 0),
('1030', 7, 0, 0, 0, 0),
('1030', 8, 0, 0, 0, 0),
('1030', 9, 0, 0, 0, 0),
('1030', 10, 0, 0, 0, 0),
('1030', 11, 0, 0, 0, 0),
('1030', 12, 0, 0, 0, 0),
('1030', 13, 0, 0, 0, 0),
('1030', 14, 0, 0, 0, 0),
('1030', 15, 0, 0, 0, 0),
('1030', 16, 0, 0, 0, 0),
('1030', 17, 0, 0, 0, 0),
('1030', 18, 0, 0, 0, 0),
('1030', 19, 0, 0, 0, 0),
('1030', 20, 0, 0, 0, 0),
('1030', 21, 0, 0, 0, 0),
('1030', 22, 0, 0, 0, 0),
('1030', 23, 0, 0, 0, 0),
('1030', 24, 0, 0, 0, 0),
('1030', 25, 0, 0, 0, 0),
('1030', 26, 0, 0, 0, 0),
('1030', 27, 0, 0, 0, 0),
('1030', 28, 0, 0, 0, 0),
('1030', 29, 0, 0, 0, 0),
('1030', 30, 0, 0, 0, 0),
('1030', 31, 0, 0, 0, 0),
('1030', 32, 0, 0, 0, 0),
('1030', 33, 0, 0, 0, 0),
('1030', 34, 0, 0, 0, 0),
('1030', 35, 0, 0, 0, 0),
('1030', 36, 0, 0, 0, 0),
('1030', 37, 0, 0, 0, 0),
('1030', 38, 0, 0, 0, 0),
('1030', 39, 0, 0, 0, 0),
('1030', 40, 0, 0, 0, 0),
('1030', 41, 0, 0, 0, 0),
('1030', 42, 0, 0, 0, 0),
('1030', 43, 0, 0, 0, 0),
('1030', 44, 0, 0, 0, 0),
('1030', 45, 0, 0, 0, 0),
('1030', 46, 0, 0, 0, 0),
('1030', 47, 0, 0, 0, 0),
('1030', 48, 0, 0, 0, 0),
('1030', 49, 0, 0, 0, 0),
('1030', 50, 0, 0, 0, 0),
('1030', 51, 0, 0, 0, 0),
('1030', 52, 0, 0, 0, 0),
('1030', 53, 0, 0, 0, 0),
('1030', 54, 0, 0, 0, 0),
('1030', 55, 0, 0, 0, 0),
('1030', 56, 0, 0, 0, 0),
('1030', 57, 0, 0, 0, 0),
('1030', 58, 0, 0, 0, 0),
('1030', 59, 0, 0, 0, 0),
('1040', -15, 0, 0, 0, 0),
('1040', -14, 0, 0, 0, 0),
('1040', -13, 0, 0, 0, 0),
('1040', -12, 0, 0, 0, 0),
('1040', -11, 0, 0, 0, 0),
('1040', -10, 0, 0, 0, 0),
('1040', -9, 0, 0, 0, 0),
('1040', -8, 0, 0, 0, 0),
('1040', -7, 0, 0, 0, 0),
('1040', -6, 0, 0, 0, 0),
('1040', -5, 0, 0, 0, 0),
('1040', -4, 0, 0, 0, 0),
('1040', -3, 0, 0, 0, 0),
('1040', -2, 0, 0, 0, 0),
('1040', -1, 0, 0, 0, 0),
('1040', 0, 0, 0, 0, 0),
('1040', 1, 0, 0, 0, 0),
('1040', 2, 0, 0, 0, 0),
('1040', 3, 0, 0, 0, 0),
('1040', 4, 0, 0, 0, 0),
('1040', 5, 0, 0, 0, 0),
('1040', 6, 0, 0, 0, 0),
('1040', 7, 0, 0, 0, 0),
('1040', 8, 0, 0, 0, 0),
('1040', 9, 0, 0, 0, 0),
('1040', 10, 0, 0, 0, 0),
('1040', 11, 0, 0, 0, 0),
('1040', 12, 0, 0, 0, 0),
('1040', 13, 0, 0, 0, 0),
('1040', 14, 0, 0, 0, 0),
('1040', 15, 0, 0, 0, 0),
('1040', 16, 0, 0, 0, 0),
('1040', 17, 0, 0, 0, 0),
('1040', 18, 0, 0, 0, 0),
('1040', 19, 0, 0, 0, 0),
('1040', 20, 0, 0, 0, 0),
('1040', 21, 0, 0, 0, 0),
('1040', 22, 0, 0, 0, 0),
('1040', 23, 0, 0, 0, 0),
('1040', 24, 0, 0, 0, 0),
('1040', 25, 0, 0, 0, 0),
('1040', 26, 0, 0, 0, 0),
('1040', 27, 0, 0, 0, 0),
('1040', 28, 0, 0, 0, 0),
('1040', 29, 0, 0, 0, 0),
('1040', 30, 0, 0, 0, 0),
('1040', 31, 0, 0, 0, 0),
('1040', 32, 0, 0, 0, 0),
('1040', 33, 0, 0, 0, 0),
('1040', 34, 0, 0, 0, 0),
('1040', 35, 0, 0, 0, 0),
('1040', 36, 0, 0, 0, 0),
('1040', 37, 0, 0, 0, 0),
('1040', 38, 0, 0, 0, 0),
('1040', 39, 0, 0, 0, 0),
('1040', 40, 0, 0, 0, 0),
('1040', 41, 0, 0, 0, 0),
('1040', 42, 0, 0, 0, 0),
('1040', 43, 0, 0, 0, 0),
('1040', 44, 0, 0, 0, 0),
('1040', 45, 0, 0, 0, 0),
('1040', 46, 0, 0, 0, 0),
('1040', 47, 0, 0, 0, 0),
('1040', 48, 0, 0, 0, 0),
('1040', 49, 0, 0, 0, 0),
('1040', 50, 0, 0, 0, 0),
('1040', 51, 0, 0, 0, 0),
('1040', 52, 0, 0, 0, 0),
('1040', 53, 0, 0, 0, 0),
('1040', 54, 0, 0, 0, 0),
('1040', 55, 0, 0, 0, 0),
('1040', 56, 0, 0, 0, 0),
('1040', 57, 0, 0, 0, 0),
('1040', 58, 0, 0, 0, 0),
('1040', 59, 0, 0, 0, 0),
('1050', -15, 0, 0, 0, 0),
('1050', -14, 0, 0, 0, 0),
('1050', -13, 0, 0, 0, 0),
('1050', -12, 0, 0, 0, 0),
('1050', -11, 0, 0, 0, 0),
('1050', -10, 0, 0, 0, 0),
('1050', -9, 0, 0, 0, 0),
('1050', -8, 0, 0, 0, 0),
('1050', -7, 0, 0, 0, 0),
('1050', -6, 0, 0, 0, 0),
('1050', -5, 0, 0, 0, 0),
('1050', -4, 0, 0, 0, 0),
('1050', -3, 0, 0, 0, 0),
('1050', -2, 0, 0, 0, 0),
('1050', -1, 0, 0, 0, 0),
('1050', 0, 0, 0, 0, 0),
('1050', 1, 0, 0, 0, 0),
('1050', 2, 0, 0, 0, 0),
('1050', 3, 0, 0, 0, 0),
('1050', 4, 0, 0, 0, 0),
('1050', 5, 0, 0, 0, 0),
('1050', 6, 0, 0, 0, 0),
('1050', 7, 0, 0, 0, 0),
('1050', 8, 0, 0, 0, 0),
('1050', 9, 0, 0, 0, 0),
('1050', 10, 0, 0, 0, 0),
('1050', 11, 0, 0, 0, 0),
('1050', 12, 0, 0, 0, 0),
('1050', 13, 0, 0, 0, 0),
('1050', 14, 0, 0, 0, 0),
('1050', 15, 0, 0, 0, 0),
('1050', 16, 0, 0, 0, 0),
('1050', 17, 0, 0, 0, 0),
('1050', 18, 0, 0, 0, 0),
('1050', 19, 0, 0, 0, 0),
('1050', 20, 0, 0, 0, 0),
('1050', 21, 0, 0, 0, 0),
('1050', 22, 0, 0, 0, 0),
('1050', 23, 0, 0, 0, 0),
('1050', 24, 0, 0, 0, 0),
('1050', 25, 0, 0, 0, 0),
('1050', 26, 0, 0, 0, 0),
('1050', 27, 0, 0, 0, 0),
('1050', 28, 0, 0, 0, 0),
('1050', 29, 0, 0, 0, 0),
('1050', 30, 0, 0, 0, 0),
('1050', 31, 0, 0, 0, 0),
('1050', 32, 0, 0, 0, 0),
('1050', 33, 0, 0, 0, 0),
('1050', 34, 0, 0, 0, 0),
('1050', 35, 0, 0, 0, 0),
('1050', 36, 0, 0, 0, 0),
('1050', 37, 0, 0, 0, 0),
('1050', 38, 0, 0, 0, 0),
('1050', 39, 0, 0, 0, 0),
('1050', 40, 0, 0, 0, 0),
('1050', 41, 0, 0, 0, 0),
('1050', 42, 0, 0, 0, 0),
('1050', 43, 0, 0, 0, 0),
('1050', 44, 0, 0, 0, 0),
('1050', 45, 0, 0, 0, 0),
('1050', 46, 0, 0, 0, 0),
('1050', 47, 0, 0, 0, 0),
('1050', 48, 0, 0, 0, 0),
('1050', 49, 0, 0, 0, 0),
('1050', 50, 0, 0, 0, 0),
('1050', 51, 0, 0, 0, 0),
('1050', 52, 0, 0, 0, 0),
('1050', 53, 0, 0, 0, 0),
('1050', 54, 0, 0, 0, 0),
('1050', 55, 0, 0, 0, 0),
('1050', 56, 0, 0, 0, 0),
('1050', 57, 0, 0, 0, 0),
('1050', 58, 0, 0, 0, 0),
('1050', 59, 0, 0, 0, 0),
('1060', -15, 0, 0, 0, 0),
('1060', -14, 0, 0, 0, 0),
('1060', -13, 0, 0, 0, 0),
('1060', -12, 0, 0, 0, 0),
('1060', -11, 0, 0, 0, 0),
('1060', -10, 0, 0, 0, 0),
('1060', -9, 0, 0, 0, 0),
('1060', -8, 0, 0, 0, 0),
('1060', -7, 0, 0, 0, 0),
('1060', -6, 0, 0, 0, 0),
('1060', -5, 0, 0, 0, 0),
('1060', -4, 0, 0, 0, 0),
('1060', -3, 0, 0, 0, 0),
('1060', -2, 0, 0, 0, 0),
('1060', -1, 0, 0, 0, 0),
('1060', 0, 0, 0, 0, 0),
('1060', 1, 0, 0, 0, 0),
('1060', 2, 0, 0, 0, 0),
('1060', 3, 0, 0, 0, 0),
('1060', 4, 0, 0, 0, 0),
('1060', 5, 0, 0, 0, 0),
('1060', 6, 0, 0, 0, 0),
('1060', 7, 0, 0, 0, 0),
('1060', 8, 0, 0, 0, 0),
('1060', 9, 0, 0, 0, 0),
('1060', 10, 0, 0, 0, 0),
('1060', 11, 0, 0, 0, 0),
('1060', 12, 0, 0, 0, 0),
('1060', 13, 0, 0, 0, 0),
('1060', 14, 0, 0, 0, 0),
('1060', 15, 0, 0, 0, 0),
('1060', 16, 0, 0, 0, 0),
('1060', 17, 0, 0, 0, 0),
('1060', 18, 0, 0, 0, 0),
('1060', 19, 0, 0, 0, 0),
('1060', 20, 0, 0, 0, 0),
('1060', 21, 0, 0, 0, 0),
('1060', 22, 0, 0, 0, 0),
('1060', 23, 0, 0, 0, 0),
('1060', 24, 0, 0, 0, 0),
('1060', 25, 0, 0, 0, 0),
('1060', 26, 0, 0, 0, 0),
('1060', 27, 0, 0, 0, 0),
('1060', 28, 0, 0, 0, 0),
('1060', 29, 0, 0, 0, 0),
('1060', 30, 0, 0, 0, 0),
('1060', 31, 0, 0, 0, 0),
('1060', 32, 0, 0, 0, 0),
('1060', 33, 0, 0, 0, 0),
('1060', 34, 0, 0, 0, 0),
('1060', 35, 0, 0, 0, 0),
('1060', 36, 0, 0, 0, 0),
('1060', 37, 0, 0, 0, 0),
('1060', 38, 0, 0, 0, 0),
('1060', 39, 0, 0, 0, 0),
('1060', 40, 0, 0, 0, 0),
('1060', 41, 0, 0, 0, 0),
('1060', 42, 0, 0, 0, 0),
('1060', 43, 0, 0, 0, 0),
('1060', 44, 0, 0, 0, 0),
('1060', 45, 0, 0, 0, 0),
('1060', 46, 0, 0, 0, 0),
('1060', 47, 0, 0, 0, 0),
('1060', 48, 0, 0, 0, 0),
('1060', 49, 0, 0, 0, 0),
('1060', 50, 0, 0, 0, 0),
('1060', 51, 0, 0, 0, 0),
('1060', 52, 0, 0, 0, 0),
('1060', 53, 0, 0, 0, 0),
('1060', 54, 0, 0, 0, 0),
('1060', 55, 0, 0, 0, 0),
('1060', 56, 0, 0, 0, 0),
('1060', 57, 0, 0, 0, 0),
('1060', 58, 0, 0, 0, 0),
('1060', 59, 0, 0, 0, 0),
('1070', -15, 0, 0, 0, 0),
('1070', -14, 0, 0, 0, 0),
('1070', -13, 0, 0, 0, 0),
('1070', -12, 0, 0, 0, 0),
('1070', -11, 0, 0, 0, 0),
('1070', -10, 0, 0, 0, 0),
('1070', -9, 0, 0, 0, 0),
('1070', -8, 0, 0, 0, 0),
('1070', -7, 0, 0, 0, 0),
('1070', -6, 0, 0, 0, 0),
('1070', -5, 0, 0, 0, 0),
('1070', -4, 0, 0, 0, 0),
('1070', -3, 0, 0, 0, 0),
('1070', -2, 0, 0, 0, 0),
('1070', -1, 0, 0, 0, 0),
('1070', 0, 0, 0, 0, 0),
('1070', 1, 0, 0, 0, 0),
('1070', 2, 0, 0, 0, 0),
('1070', 3, 0, 0, 0, 0),
('1070', 4, 0, 0, 0, 0),
('1070', 5, 0, 0, 0, 0),
('1070', 6, 0, 0, 0, 0),
('1070', 7, 0, 0, 0, 0),
('1070', 8, 0, 0, 0, 0),
('1070', 9, 0, 0, 0, 0),
('1070', 10, 0, 0, 0, 0),
('1070', 11, 0, 0, 0, 0),
('1070', 12, 0, 0, 0, 0),
('1070', 13, 0, 0, 0, 0),
('1070', 14, 0, 0, 0, 0),
('1070', 15, 0, 0, 0, 0),
('1070', 16, 0, 0, 0, 0),
('1070', 17, 0, 0, 0, 0),
('1070', 18, 0, 0, 0, 0),
('1070', 19, 0, 0, 0, 0),
('1070', 20, 0, 0, 0, 0),
('1070', 21, 0, 0, 0, 0),
('1070', 22, 0, 0, 0, 0),
('1070', 23, 0, 0, 0, 0),
('1070', 24, 0, 0, 0, 0),
('1070', 25, 0, 0, 0, 0),
('1070', 26, 0, 0, 0, 0),
('1070', 27, 0, 0, 0, 0),
('1070', 28, 0, 0, 0, 0),
('1070', 29, 0, 0, 0, 0),
('1070', 30, 0, 0, 0, 0),
('1070', 31, 0, 0, 0, 0),
('1070', 32, 0, 0, 0, 0),
('1070', 33, 0, 0, 0, 0),
('1070', 34, 0, 0, 0, 0),
('1070', 35, 0, 0, 0, 0),
('1070', 36, 0, 0, 0, 0),
('1070', 37, 0, 0, 0, 0),
('1070', 38, 0, 0, 0, 0),
('1070', 39, 0, 0, 0, 0),
('1070', 40, 0, 0, 0, 0),
('1070', 41, 0, 0, 0, 0),
('1070', 42, 0, 0, 0, 0),
('1070', 43, 0, 0, 0, 0),
('1070', 44, 0, 0, 0, 0),
('1070', 45, 0, 0, 0, 0),
('1070', 46, 0, 0, 0, 0),
('1070', 47, 0, 0, 0, 0),
('1070', 48, 0, 0, 0, 0),
('1070', 49, 0, 0, 0, 0),
('1070', 50, 0, 0, 0, 0),
('1070', 51, 0, 0, 0, 0),
('1070', 52, 0, 0, 0, 0),
('1070', 53, 0, 0, 0, 0),
('1070', 54, 0, 0, 0, 0),
('1070', 55, 0, 0, 0, 0),
('1070', 56, 0, 0, 0, 0),
('1070', 57, 0, 0, 0, 0),
('1070', 58, 0, 0, 0, 0),
('1070', 59, 0, 0, 0, 0),
('1080', -15, 0, 0, 0, 0),
('1080', -14, 0, 0, 0, 0),
('1080', -13, 0, 0, 0, 0),
('1080', -12, 0, 0, 0, 0),
('1080', -11, 0, 0, 0, 0),
('1080', -10, 0, 0, 0, 0),
('1080', -9, 0, 0, 0, 0),
('1080', -8, 0, 0, 0, 0),
('1080', -7, 0, 0, 0, 0),
('1080', -6, 0, 0, 0, 0),
('1080', -5, 0, 0, 0, 0),
('1080', -4, 0, 0, 0, 0),
('1080', -3, 0, 0, 0, 0),
('1080', -2, 0, 0, 0, 0),
('1080', -1, 0, 0, 0, 0),
('1080', 0, 0, 0, 0, 0),
('1080', 1, 0, 0, 0, 0),
('1080', 2, 0, 0, 0, 0),
('1080', 3, 0, 0, 0, 0),
('1080', 4, 0, 0, 0, 0),
('1080', 5, 0, 0, 0, 0),
('1080', 6, 0, 0, 0, 0),
('1080', 7, 0, 0, 0, 0),
('1080', 8, 0, 0, 0, 0),
('1080', 9, 0, 0, 0, 0),
('1080', 10, 0, 0, 0, 0),
('1080', 11, 0, 0, 0, 0),
('1080', 12, 0, 0, 0, 0),
('1080', 13, 0, 0, 0, 0),
('1080', 14, 0, 0, 0, 0),
('1080', 15, 0, 0, 0, 0),
('1080', 16, 0, 0, 0, 0),
('1080', 17, 0, 0, 0, 0),
('1080', 18, 0, 0, 0, 0),
('1080', 19, 0, 0, 0, 0),
('1080', 20, 0, 0, 0, 0),
('1080', 21, 0, 0, 0, 0),
('1080', 22, 0, 0, 0, 0),
('1080', 23, 0, 0, 0, 0),
('1080', 24, 0, 0, 0, 0),
('1080', 25, 0, 0, 0, 0),
('1080', 26, 0, 0, 0, 0),
('1080', 27, 0, 0, 0, 0),
('1080', 28, 0, 0, 0, 0),
('1080', 29, 0, 0, 0, 0),
('1080', 30, 0, 0, 0, 0),
('1080', 31, 0, 0, 0, 0),
('1080', 32, 0, 0, 0, 0),
('1080', 33, 0, 0, 0, 0),
('1080', 34, 0, 0, 0, 0),
('1080', 35, 0, 0, 0, 0),
('1080', 36, 0, 0, 0, 0),
('1080', 37, 0, 0, 0, 0),
('1080', 38, 0, 0, 0, 0),
('1080', 39, 0, 0, 0, 0),
('1080', 40, 0, 0, 0, 0),
('1080', 41, 0, 0, 0, 0),
('1080', 42, 0, 0, 0, 0),
('1080', 43, 0, 0, 0, 0),
('1080', 44, 0, 0, 0, 0),
('1080', 45, 0, 0, 0, 0),
('1080', 46, 0, 0, 0, 0),
('1080', 47, 0, 0, 0, 0),
('1080', 48, 0, 0, 0, 0),
('1080', 49, 0, 0, 0, 0),
('1080', 50, 0, 0, 0, 0),
('1080', 51, 0, 0, 0, 0),
('1080', 52, 0, 0, 0, 0),
('1080', 53, 0, 0, 0, 0),
('1080', 54, 0, 0, 0, 0),
('1080', 55, 0, 0, 0, 0),
('1080', 56, 0, 0, 0, 0),
('1080', 57, 0, 0, 0, 0),
('1080', 58, 0, 0, 0, 0),
('1080', 59, 0, 0, 0, 0),
('1090', -15, 0, 0, 0, 0),
('1090', -14, 0, 0, 0, 0),
('1090', -13, 0, 0, 0, 0),
('1090', -12, 0, 0, 0, 0),
('1090', -11, 0, 0, 0, 0),
('1090', -10, 0, 0, 0, 0),
('1090', -9, 0, 0, 0, 0),
('1090', -8, 0, 0, 0, 0),
('1090', -7, 0, 0, 0, 0),
('1090', -6, 0, 0, 0, 0),
('1090', -5, 0, 0, 0, 0),
('1090', -4, 0, 0, 0, 0),
('1090', -3, 0, 0, 0, 0),
('1090', -2, 0, 0, 0, 0),
('1090', -1, 0, 0, 0, 0),
('1090', 0, 0, 0, 0, 0),
('1090', 1, 0, 0, 0, 0),
('1090', 2, 0, 0, 0, 0),
('1090', 3, 0, 0, 0, 0),
('1090', 4, 0, 0, 0, 0),
('1090', 5, 0, 0, 0, 0),
('1090', 6, 0, 0, 0, 0),
('1090', 7, 0, 0, 0, 0),
('1090', 8, 0, 0, 0, 0),
('1090', 9, 0, 0, 0, 0),
('1090', 10, 0, 0, 0, 0),
('1090', 11, 0, 0, 0, 0),
('1090', 12, 0, 0, 0, 0),
('1090', 13, 0, 0, 0, 0),
('1090', 14, 0, 0, 0, 0),
('1090', 15, 0, 0, 0, 0),
('1090', 16, 0, 0, 0, 0),
('1090', 17, 0, 0, 0, 0),
('1090', 18, 0, 0, 0, 0),
('1090', 19, 0, 0, 0, 0),
('1090', 20, 0, 0, 0, 0),
('1090', 21, 0, 0, 0, 0),
('1090', 22, 0, 0, 0, 0),
('1090', 23, 0, 0, 0, 0),
('1090', 24, 0, 0, 0, 0),
('1090', 25, 0, 0, 0, 0),
('1090', 26, 0, 0, 0, 0),
('1090', 27, 0, 0, 0, 0),
('1090', 28, 0, 0, 0, 0),
('1090', 29, 0, 0, 0, 0),
('1090', 30, 0, 0, 0, 0),
('1090', 31, 0, 0, 0, 0),
('1090', 32, 0, 0, 0, 0),
('1090', 33, 0, 0, 0, 0),
('1090', 34, 0, 0, 0, 0),
('1090', 35, 0, 0, 0, 0),
('1090', 36, 0, 0, 0, 0),
('1090', 37, 0, 0, 0, 0),
('1090', 38, 0, 0, 0, 0),
('1090', 39, 0, 0, 0, 0),
('1090', 40, 0, 0, 0, 0),
('1090', 41, 0, 0, 0, 0),
('1090', 42, 0, 0, 0, 0),
('1090', 43, 0, 0, 0, 0),
('1090', 44, 0, 0, 0, 0),
('1090', 45, 0, 0, 0, 0),
('1090', 46, 0, 0, 0, 0),
('1090', 47, 0, 0, 0, 0),
('1090', 48, 0, 0, 0, 0),
('1090', 49, 0, 0, 0, 0),
('1090', 50, 0, 0, 0, 0),
('1090', 51, 0, 0, 0, 0),
('1090', 52, 0, 0, 0, 0),
('1090', 53, 0, 0, 0, 0),
('1090', 54, 0, 0, 0, 0),
('1090', 55, 0, 0, 0, 0),
('1090', 56, 0, 0, 0, 0),
('1090', 57, 0, 0, 0, 0),
('1090', 58, 0, 0, 0, 0),
('1090', 59, 0, 0, 0, 0),
('1100', -15, 0, 0, 0, 0),
('1100', -14, 0, 0, 0, 0),
('1100', -13, 0, 0, 0, 0),
('1100', -12, 0, 0, 0, 0),
('1100', -11, 0, 0, 0, 0),
('1100', -10, 0, 0, 0, 0),
('1100', -9, 0, 0, 0, 0),
('1100', -8, 0, 0, 0, 0),
('1100', -7, 0, 0, 0, 0),
('1100', -6, 0, 0, 0, 0),
('1100', -5, 0, 0, 0, 0),
('1100', -4, 0, 0, 0, 0),
('1100', -3, 0, 0, 0, 0),
('1100', -2, 0, 0, 0, 0),
('1100', -1, 0, 0, 0, 0),
('1100', 0, 0, 0, 0, 0),
('1100', 1, 0, 0, 0, 0),
('1100', 2, 0, 0, 0, 0),
('1100', 3, 0, 0, 0, 0),
('1100', 4, 0, 0, 0, 0),
('1100', 5, 0, 0, 0, 0),
('1100', 6, 0, 0, 0, 0),
('1100', 7, 0, 0, 0, 0),
('1100', 8, 0, 0, 0, 0),
('1100', 9, 0, 0, 0, 0),
('1100', 10, 0, 0, 0, 0),
('1100', 11, 0, 0, 0, 0),
('1100', 12, 0, 0, 0, 0),
('1100', 13, 0, 0, 0, 0),
('1100', 14, 0, 0, 0, 0),
('1100', 15, 0, 0, 0, 0),
('1100', 16, 0, 0, 0, 0),
('1100', 17, 0, 0, 0, 0),
('1100', 18, 0, 0, 0, 0),
('1100', 19, 0, 0, 0, 0),
('1100', 20, 0, 0, 0, 0),
('1100', 21, 0, 0, 0, 0),
('1100', 22, 0, 0, 0, 0),
('1100', 23, 0, 0, 0, 0),
('1100', 24, 0, 0, 0, 0),
('1100', 25, 0, 0, 0, 0),
('1100', 26, 0, 0, 0, 0),
('1100', 27, 0, 0, 0, 0),
('1100', 28, 0, 0, 0, 0),
('1100', 29, 0, 0, 0, 0),
('1100', 30, 0, 0, 0, 0),
('1100', 31, 0, 0, 0, 0),
('1100', 32, 0, 0, 0, 0),
('1100', 33, 0, 0, 0, 0),
('1100', 34, 0, 0, 0, 0),
('1100', 35, 0, 0, 0, 0),
('1100', 36, 0, 0, 0, 0),
('1100', 37, 0, 0, 0, 0),
('1100', 38, 0, 0, 0, 0),
('1100', 39, 0, 0, 0, 0),
('1100', 40, 0, 0, 0, 0),
('1100', 41, 0, 0, 0, 0),
('1100', 42, 0, 0, 0, 0),
('1100', 43, 0, 0, 0, 0),
('1100', 44, 0, 0, 0, 0),
('1100', 45, 0, 0, 0, 0),
('1100', 46, 0, 0, 0, 0),
('1100', 47, 0, 0, 0, 0),
('1100', 48, 0, 0, 0, 0),
('1100', 49, 0, 0, 0, 0),
('1100', 50, 0, 0, 0, 0),
('1100', 51, 0, 0, 0, 0),
('1100', 52, 0, 0, 0, 0),
('1100', 53, 0, 0, 0, 0),
('1100', 54, 0, 0, 0, 0),
('1100', 55, 0, 0, 0, 0),
('1100', 56, 0, 0, 0, 0),
('1100', 57, 0, 0, 0, 0),
('1100', 58, 0, 0, 0, 0),
('1100', 59, 0, 0, 0, 0),
('1150', -15, 0, 0, 0, 0),
('1150', -14, 0, 0, 0, 0),
('1150', -13, 0, 0, 0, 0),
('1150', -12, 0, 0, 0, 0),
('1150', -11, 0, 0, 0, 0),
('1150', -10, 0, 0, 0, 0),
('1150', -9, 0, 0, 0, 0),
('1150', -8, 0, 0, 0, 0),
('1150', -7, 0, 0, 0, 0),
('1150', -6, 0, 0, 0, 0),
('1150', -5, 0, 0, 0, 0),
('1150', -4, 0, 0, 0, 0),
('1150', -3, 0, 0, 0, 0),
('1150', -2, 0, 0, 0, 0),
('1150', -1, 0, 0, 0, 0),
('1150', 0, 0, 0, 0, 0),
('1150', 1, 0, 0, 0, 0),
('1150', 2, 0, 0, 0, 0),
('1150', 3, 0, 0, 0, 0),
('1150', 4, 0, 0, 0, 0),
('1150', 5, 0, 0, 0, 0),
('1150', 6, 0, 0, 0, 0),
('1150', 7, 0, 0, 0, 0),
('1150', 8, 0, 0, 0, 0),
('1150', 9, 0, 0, 0, 0),
('1150', 10, 0, 0, 0, 0),
('1150', 11, 0, 0, 0, 0),
('1150', 12, 0, 0, 0, 0),
('1150', 13, 0, 0, 0, 0),
('1150', 14, 0, 0, 0, 0),
('1150', 15, 0, 0, 0, 0),
('1150', 16, 0, 0, 0, 0),
('1150', 17, 0, 0, 0, 0),
('1150', 18, 0, 0, 0, 0),
('1150', 19, 0, 0, 0, 0),
('1150', 20, 0, 0, 0, 0),
('1150', 21, 0, 0, 0, 0),
('1150', 22, 0, 0, 0, 0),
('1150', 23, 0, 0, 0, 0),
('1150', 24, 0, 0, 0, 0),
('1150', 25, 0, 0, 0, 0),
('1150', 26, 0, 0, 0, 0),
('1150', 27, 0, 0, 0, 0),
('1150', 28, 0, 0, 0, 0),
('1150', 29, 0, 0, 0, 0),
('1150', 30, 0, 0, 0, 0),
('1150', 31, 0, 0, 0, 0),
('1150', 32, 0, 0, 0, 0),
('1150', 33, 0, 0, 0, 0),
('1150', 34, 0, 0, 0, 0),
('1150', 35, 0, 0, 0, 0),
('1150', 36, 0, 0, 0, 0),
('1150', 37, 0, 0, 0, 0),
('1150', 38, 0, 0, 0, 0),
('1150', 39, 0, 0, 0, 0),
('1150', 40, 0, 0, 0, 0),
('1150', 41, 0, 0, 0, 0),
('1150', 42, 0, 0, 0, 0),
('1150', 43, 0, 0, 0, 0),
('1150', 44, 0, 0, 0, 0),
('1150', 45, 0, 0, 0, 0),
('1150', 46, 0, 0, 0, 0),
('1150', 47, 0, 0, 0, 0),
('1150', 48, 0, 0, 0, 0),
('1150', 49, 0, 0, 0, 0),
('1150', 50, 0, 0, 0, 0),
('1150', 51, 0, 0, 0, 0),
('1150', 52, 0, 0, 0, 0),
('1150', 53, 0, 0, 0, 0),
('1150', 54, 0, 0, 0, 0),
('1150', 55, 0, 0, 0, 0),
('1150', 56, 0, 0, 0, 0),
('1150', 57, 0, 0, 0, 0),
('1150', 58, 0, 0, 0, 0),
('1150', 59, 0, 0, 0, 0),
('1200', -15, 0, 0, 0, 0),
('1200', -14, 0, 0, 0, 0),
('1200', -13, 0, 0, 0, 0),
('1200', -12, 0, 0, 0, 0),
('1200', -11, 0, 0, 0, 0),
('1200', -10, 0, 0, 0, 0),
('1200', -9, 0, 0, 0, 0),
('1200', -8, 0, 0, 0, 0),
('1200', -7, 0, 0, 0, 0),
('1200', -6, 0, 0, 0, 0),
('1200', -5, 0, 0, 0, 0),
('1200', -4, 0, 0, 0, 0),
('1200', -3, 0, 0, 0, 0),
('1200', -2, 0, 0, 0, 0),
('1200', -1, 0, 0, 0, 0),
('1200', 0, 0, 0, 0, 0),
('1200', 1, 0, 0, 0, 0),
('1200', 2, 0, 0, 0, 0),
('1200', 3, 0, 0, 0, 0),
('1200', 4, 0, 0, 0, 0),
('1200', 5, 0, 0, 0, 0),
('1200', 6, 0, 0, 0, 0),
('1200', 7, 0, 0, 0, 0),
('1200', 8, 0, 0, 0, 0),
('1200', 9, 0, 0, 0, 0),
('1200', 10, 0, 0, 0, 0),
('1200', 11, 0, 0, 0, 0),
('1200', 12, 0, 0, 0, 0),
('1200', 13, 0, 0, 0, 0),
('1200', 14, 0, 0, 0, 0),
('1200', 15, 0, 0, 0, 0),
('1200', 16, 0, 0, 0, 0),
('1200', 17, 0, 0, 0, 0),
('1200', 18, 0, 0, 0, 0),
('1200', 19, 0, 0, 0, 0),
('1200', 20, 0, 0, 0, 0),
('1200', 21, 0, 0, 0, 0),
('1200', 22, 0, 0, 0, 0),
('1200', 23, 0, 0, 0, 0),
('1200', 24, 0, 0, 0, 0),
('1200', 25, 0, 0, 0, 0),
('1200', 26, 0, 0, 0, 0),
('1200', 27, 0, 0, 0, 0),
('1200', 28, 0, 0, 0, 0),
('1200', 29, 0, 0, 0, 0),
('1200', 30, 0, 0, 0, 0),
('1200', 31, 0, 0, 0, 0),
('1200', 32, 0, 0, 0, 0),
('1200', 33, 0, 0, 0, 0),
('1200', 34, 0, 0, 0, 0),
('1200', 35, 0, 0, 0, 0),
('1200', 36, 0, 0, 0, 0),
('1200', 37, 0, 0, 0, 0),
('1200', 38, 0, 0, 0, 0),
('1200', 39, 0, 0, 0, 0),
('1200', 40, 0, 0, 0, 0),
('1200', 41, 0, 0, 0, 0),
('1200', 42, 0, 0, 0, 0),
('1200', 43, 0, 0, 0, 0),
('1200', 44, 0, 0, 0, 0),
('1200', 45, 0, 0, 0, 0),
('1200', 46, 0, 0, 0, 0),
('1200', 47, 0, 0, 0, 0),
('1200', 48, 0, 0, 0, 0),
('1200', 49, 0, 0, 0, 0),
('1200', 50, 0, 0, 0, 0),
('1200', 51, 0, 0, 0, 0),
('1200', 52, 0, 0, 0, 0),
('1200', 53, 0, 0, 0, 0),
('1200', 54, 0, 0, 0, 0),
('1200', 55, 0, 0, 0, 0),
('1200', 56, 0, 0, 0, 0),
('1200', 57, 0, 0, 0, 0),
('1200', 58, 0, 0, 0, 0),
('1200', 59, 0, 0, 0, 0),
('1250', -15, 0, 0, 0, 0),
('1250', -14, 0, 0, 0, 0),
('1250', -13, 0, 0, 0, 0),
('1250', -12, 0, 0, 0, 0),
('1250', -11, 0, 0, 0, 0),
('1250', -10, 0, 0, 0, 0),
('1250', -9, 0, 0, 0, 0),
('1250', -8, 0, 0, 0, 0),
('1250', -7, 0, 0, 0, 0),
('1250', -6, 0, 0, 0, 0),
('1250', -5, 0, 0, 0, 0),
('1250', -4, 0, 0, 0, 0),
('1250', -3, 0, 0, 0, 0),
('1250', -2, 0, 0, 0, 0),
('1250', -1, 0, 0, 0, 0),
('1250', 0, 0, 0, 0, 0),
('1250', 1, 0, 0, 0, 0),
('1250', 2, 0, 0, 0, 0),
('1250', 3, 0, 0, 0, 0),
('1250', 4, 0, 0, 0, 0),
('1250', 5, 0, 0, 0, 0),
('1250', 6, 0, 0, 0, 0),
('1250', 7, 0, 0, 0, 0),
('1250', 8, 0, 0, 0, 0),
('1250', 9, 0, 0, 0, 0),
('1250', 10, 0, 0, 0, 0),
('1250', 11, 0, 0, 0, 0),
('1250', 12, 0, 0, 0, 0),
('1250', 13, 0, 0, 0, 0),
('1250', 14, 0, 0, 0, 0),
('1250', 15, 0, 0, 0, 0),
('1250', 16, 0, 0, 0, 0),
('1250', 17, 0, 0, 0, 0),
('1250', 18, 0, 0, 0, 0),
('1250', 19, 0, 0, 0, 0),
('1250', 20, 0, 0, 0, 0),
('1250', 21, 0, 0, 0, 0),
('1250', 22, 0, 0, 0, 0),
('1250', 23, 0, 0, 0, 0),
('1250', 24, 0, 0, 0, 0),
('1250', 25, 0, 0, 0, 0),
('1250', 26, 0, 0, 0, 0),
('1250', 27, 0, 0, 0, 0),
('1250', 28, 0, 0, 0, 0),
('1250', 29, 0, 0, 0, 0),
('1250', 30, 0, 0, 0, 0),
('1250', 31, 0, 0, 0, 0),
('1250', 32, 0, 0, 0, 0),
('1250', 33, 0, 0, 0, 0),
('1250', 34, 0, 0, 0, 0),
('1250', 35, 0, 0, 0, 0),
('1250', 36, 0, 0, 0, 0),
('1250', 37, 0, 0, 0, 0),
('1250', 38, 0, 0, 0, 0),
('1250', 39, 0, 0, 0, 0),
('1250', 40, 0, 0, 0, 0),
('1250', 41, 0, 0, 0, 0),
('1250', 42, 0, 0, 0, 0),
('1250', 43, 0, 0, 0, 0),
('1250', 44, 0, 0, 0, 0),
('1250', 45, 0, 0, 0, 0),
('1250', 46, 0, 0, 0, 0),
('1250', 47, 0, 0, 0, 0),
('1250', 48, 0, 0, 0, 0),
('1250', 49, 0, 0, 0, 0),
('1250', 50, 0, 0, 0, 0),
('1250', 51, 0, 0, 0, 0),
('1250', 52, 0, 0, 0, 0),
('1250', 53, 0, 0, 0, 0),
('1250', 54, 0, 0, 0, 0),
('1250', 55, 0, 0, 0, 0),
('1250', 56, 0, 0, 0, 0),
('1250', 57, 0, 0, 0, 0),
('1250', 58, 0, 0, 0, 0),
('1250', 59, 0, 0, 0, 0),
('1300', -15, 0, 0, 0, 0),
('1300', -14, 0, 0, 0, 0),
('1300', -13, 0, 0, 0, 0),
('1300', -12, 0, 0, 0, 0),
('1300', -11, 0, 0, 0, 0),
('1300', -10, 0, 0, 0, 0),
('1300', -9, 0, 0, 0, 0),
('1300', -8, 0, 0, 0, 0),
('1300', -7, 0, 0, 0, 0),
('1300', -6, 0, 0, 0, 0),
('1300', -5, 0, 0, 0, 0),
('1300', -4, 0, 0, 0, 0),
('1300', -3, 0, 0, 0, 0),
('1300', -2, 0, 0, 0, 0),
('1300', -1, 0, 0, 0, 0),
('1300', 0, 0, 0, 0, 0),
('1300', 1, 0, 0, 0, 0),
('1300', 2, 0, 0, 0, 0),
('1300', 3, 0, 0, 0, 0),
('1300', 4, 0, 0, 0, 0),
('1300', 5, 0, 0, 0, 0),
('1300', 6, 0, 0, 0, 0),
('1300', 7, 0, 0, 0, 0),
('1300', 8, 0, 0, 0, 0),
('1300', 9, 0, 0, 0, 0),
('1300', 10, 0, 0, 0, 0),
('1300', 11, 0, 0, 0, 0),
('1300', 12, 0, 0, 0, 0),
('1300', 13, 0, 0, 0, 0),
('1300', 14, 0, 0, 0, 0),
('1300', 15, 0, 0, 0, 0),
('1300', 16, 0, 0, 0, 0),
('1300', 17, 0, 0, 0, 0),
('1300', 18, 0, 0, 0, 0),
('1300', 19, 0, 0, 0, 0),
('1300', 20, 0, 0, 0, 0),
('1300', 21, 0, 0, 0, 0),
('1300', 22, 0, 0, 0, 0),
('1300', 23, 0, 0, 0, 0),
('1300', 24, 0, 0, 0, 0),
('1300', 25, 0, 0, 0, 0),
('1300', 26, 0, 0, 0, 0),
('1300', 27, 0, 0, 0, 0),
('1300', 28, 0, 0, 0, 0),
('1300', 29, 0, 0, 0, 0),
('1300', 30, 0, 0, 0, 0),
('1300', 31, 0, 0, 0, 0),
('1300', 32, 0, 0, 0, 0),
('1300', 33, 0, 0, 0, 0),
('1300', 34, 0, 0, 0, 0),
('1300', 35, 0, 0, 0, 0),
('1300', 36, 0, 0, 0, 0),
('1300', 37, 0, 0, 0, 0),
('1300', 38, 0, 0, 0, 0),
('1300', 39, 0, 0, 0, 0),
('1300', 40, 0, 0, 0, 0),
('1300', 41, 0, 0, 0, 0),
('1300', 42, 0, 0, 0, 0),
('1300', 43, 0, 0, 0, 0),
('1300', 44, 0, 0, 0, 0),
('1300', 45, 0, 0, 0, 0),
('1300', 46, 0, 0, 0, 0),
('1300', 47, 0, 0, 0, 0),
('1300', 48, 0, 0, 0, 0),
('1300', 49, 0, 0, 0, 0),
('1300', 50, 0, 0, 0, 0),
('1300', 51, 0, 0, 0, 0),
('1300', 52, 0, 0, 0, 0),
('1300', 53, 0, 0, 0, 0),
('1300', 54, 0, 0, 0, 0),
('1300', 55, 0, 0, 0, 0),
('1300', 56, 0, 0, 0, 0),
('1300', 57, 0, 0, 0, 0),
('1300', 58, 0, 0, 0, 0),
('1300', 59, 0, 0, 0, 0),
('1350', -15, 0, 0, 0, 0),
('1350', -14, 0, 0, 0, 0),
('1350', -13, 0, 0, 0, 0),
('1350', -12, 0, 0, 0, 0),
('1350', -11, 0, 0, 0, 0),
('1350', -10, 0, 0, 0, 0),
('1350', -9, 0, 0, 0, 0),
('1350', -8, 0, 0, 0, 0),
('1350', -7, 0, 0, 0, 0),
('1350', -6, 0, 0, 0, 0),
('1350', -5, 0, 0, 0, 0),
('1350', -4, 0, 0, 0, 0),
('1350', -3, 0, 0, 0, 0),
('1350', -2, 0, 0, 0, 0),
('1350', -1, 0, 0, 0, 0),
('1350', 0, 0, 0, 0, 0),
('1350', 1, 0, 0, 0, 0),
('1350', 2, 0, 0, 0, 0),
('1350', 3, 0, 0, 0, 0),
('1350', 4, 0, 0, 0, 0),
('1350', 5, 0, 0, 0, 0),
('1350', 6, 0, 0, 0, 0),
('1350', 7, 0, 0, 0, 0),
('1350', 8, 0, 0, 0, 0),
('1350', 9, 0, 0, 0, 0),
('1350', 10, 0, 0, 0, 0),
('1350', 11, 0, 0, 0, 0),
('1350', 12, 0, 0, 0, 0),
('1350', 13, 0, 0, 0, 0),
('1350', 14, 0, 0, 0, 0),
('1350', 15, 0, 0, 0, 0),
('1350', 16, 0, 0, 0, 0),
('1350', 17, 0, 0, 0, 0),
('1350', 18, 0, 0, 0, 0),
('1350', 19, 0, 0, 0, 0),
('1350', 20, 0, 0, 0, 0),
('1350', 21, 0, 0, 0, 0),
('1350', 22, 0, 0, 0, 0),
('1350', 23, 0, 0, 0, 0),
('1350', 24, 0, 0, 0, 0),
('1350', 25, 0, 0, 0, 0),
('1350', 26, 0, 0, 0, 0),
('1350', 27, 0, 0, 0, 0),
('1350', 28, 0, 0, 0, 0),
('1350', 29, 0, 0, 0, 0),
('1350', 30, 0, 0, 0, 0),
('1350', 31, 0, 0, 0, 0),
('1350', 32, 0, 0, 0, 0),
('1350', 33, 0, 0, 0, 0),
('1350', 34, 0, 0, 0, 0),
('1350', 35, 0, 0, 0, 0),
('1350', 36, 0, 0, 0, 0),
('1350', 37, 0, 0, 0, 0),
('1350', 38, 0, 0, 0, 0),
('1350', 39, 0, 0, 0, 0),
('1350', 40, 0, 0, 0, 0),
('1350', 41, 0, 0, 0, 0),
('1350', 42, 0, 0, 0, 0),
('1350', 43, 0, 0, 0, 0),
('1350', 44, 0, 0, 0, 0),
('1350', 45, 0, 0, 0, 0),
('1350', 46, 0, 0, 0, 0),
('1350', 47, 0, 0, 0, 0),
('1350', 48, 0, 0, 0, 0),
('1350', 49, 0, 0, 0, 0),
('1350', 50, 0, 0, 0, 0),
('1350', 51, 0, 0, 0, 0),
('1350', 52, 0, 0, 0, 0),
('1350', 53, 0, 0, 0, 0),
('1350', 54, 0, 0, 0, 0),
('1350', 55, 0, 0, 0, 0),
('1350', 56, 0, 0, 0, 0),
('1350', 57, 0, 0, 0, 0),
('1350', 58, 0, 0, 0, 0),
('1350', 59, 0, 0, 0, 0),
('1400', -15, 0, 0, 0, 0),
('1400', -14, 0, 0, 0, 0),
('1400', -13, 0, 0, 0, 0),
('1400', -12, 0, 0, 0, 0),
('1400', -11, 0, 0, 0, 0),
('1400', -10, 0, 0, 0, 0),
('1400', -9, 0, 0, 0, 0),
('1400', -8, 0, 0, 0, 0),
('1400', -7, 0, 0, 0, 0),
('1400', -6, 0, 0, 0, 0),
('1400', -5, 0, 0, 0, 0),
('1400', -4, 0, 0, 0, 0),
('1400', -3, 0, 0, 0, 0),
('1400', -2, 0, 0, 0, 0),
('1400', -1, 0, 0, 0, 0),
('1400', 0, 0, 0, 0, 0),
('1400', 1, 0, 0, 0, 0),
('1400', 2, 0, 0, 0, 0),
('1400', 3, 0, 0, 0, 0),
('1400', 4, 0, 0, 0, 0),
('1400', 5, 0, 0, 0, 0),
('1400', 6, 0, 0, 0, 0),
('1400', 7, 0, 0, 0, 0),
('1400', 8, 0, 0, 0, 0),
('1400', 9, 0, 0, 0, 0),
('1400', 10, 0, 0, 0, 0),
('1400', 11, 0, 0, 0, 0),
('1400', 12, 0, 0, 0, 0),
('1400', 13, 0, 0, 0, 0),
('1400', 14, 0, 0, 0, 0),
('1400', 15, 0, 0, 0, 0),
('1400', 16, 0, 0, 0, 0),
('1400', 17, 0, 0, 0, 0),
('1400', 18, 0, 0, 0, 0),
('1400', 19, 0, 0, 0, 0),
('1400', 20, 0, 0, 0, 0),
('1400', 21, 0, 0, 0, 0),
('1400', 22, 0, 0, 0, 0),
('1400', 23, 0, 0, 0, 0),
('1400', 24, 0, 0, 0, 0),
('1400', 25, 0, 0, 0, 0),
('1400', 26, 0, 0, 0, 0),
('1400', 27, 0, 0, 0, 0),
('1400', 28, 0, 0, 0, 0),
('1400', 29, 0, 0, 0, 0),
('1400', 30, 0, 0, 0, 0),
('1400', 31, 0, 0, 0, 0),
('1400', 32, 0, 0, 0, 0),
('1400', 33, 0, 0, 0, 0),
('1400', 34, 0, 0, 0, 0),
('1400', 35, 0, 0, 0, 0),
('1400', 36, 0, 0, 0, 0),
('1400', 37, 0, 0, 0, 0),
('1400', 38, 0, 0, 0, 0),
('1400', 39, 0, 0, 0, 0),
('1400', 40, 0, 0, 0, 0),
('1400', 41, 0, 0, 0, 0),
('1400', 42, 0, 0, 0, 0),
('1400', 43, 0, 0, 0, 0),
('1400', 44, 0, 0, 0, 0),
('1400', 45, 0, 0, 0, 0),
('1400', 46, 0, 0, 0, 0),
('1400', 47, 0, 0, 0, 0),
('1400', 48, 0, 0, 0, 0),
('1400', 49, 0, 0, 0, 0),
('1400', 50, 0, 0, 0, 0),
('1400', 51, 0, 0, 0, 0),
('1400', 52, 0, 0, 0, 0),
('1400', 53, 0, 0, 0, 0),
('1400', 54, 0, 0, 0, 0),
('1400', 55, 0, 0, 0, 0),
('1400', 56, 0, 0, 0, 0),
('1400', 57, 0, 0, 0, 0),
('1400', 58, 0, 0, 0, 0),
('1400', 59, 0, 0, 0, 0),
('1420', -15, 0, 0, 0, 0),
('1420', -14, 0, 0, 0, 0),
('1420', -13, 0, 0, 0, 0),
('1420', -12, 0, 0, 0, 0),
('1420', -11, 0, 0, 0, 0),
('1420', -10, 0, 0, 0, 0),
('1420', -9, 0, 0, 0, 0),
('1420', -8, 0, 0, 0, 0),
('1420', -7, 0, 0, 0, 0),
('1420', -6, 0, 0, 0, 0),
('1420', -5, 0, 0, 0, 0),
('1420', -4, 0, 0, 0, 0),
('1420', -3, 0, 0, 0, 0),
('1420', -2, 0, 0, 0, 0),
('1420', -1, 0, 0, 0, 0),
('1420', 0, 0, 0, 0, 0),
('1420', 1, 0, 0, 0, 0),
('1420', 2, 0, 0, 0, 0),
('1420', 3, 0, 0, 0, 0),
('1420', 4, 0, 0, 0, 0),
('1420', 5, 0, 0, 0, 0),
('1420', 6, 0, 0, 0, 0),
('1420', 7, 0, 0, 0, 0),
('1420', 8, 0, 0, 0, 0),
('1420', 9, 0, 0, 0, 0),
('1420', 10, 0, 0, 0, 0),
('1420', 11, 0, 0, 0, 0),
('1420', 12, 0, 0, 0, 0),
('1420', 13, 0, 0, 0, 0),
('1420', 14, 0, 0, 0, 0),
('1420', 15, 0, 0, 0, 0),
('1420', 16, 0, 0, 0, 0),
('1420', 17, 0, 0, 0, 0),
('1420', 18, 0, 0, 0, 0),
('1420', 19, 0, 0, 0, 0),
('1420', 20, 0, 0, 0, 0),
('1420', 21, 0, 0, 0, 0),
('1420', 22, 0, 0, 0, 0),
('1420', 23, 0, 0, 0, 0),
('1420', 24, 0, 0, 0, 0),
('1420', 25, 0, 0, 0, 0),
('1420', 26, 0, 0, 0, 0),
('1420', 27, 0, 0, 0, 0),
('1420', 28, 0, 0, 0, 0),
('1420', 29, 0, 0, 0, 0),
('1420', 30, 0, 0, 0, 0),
('1420', 31, 0, 0, 0, 0),
('1420', 32, 0, 0, 0, 0),
('1420', 33, 0, 0, 0, 0),
('1420', 34, 0, 0, 0, 0),
('1420', 35, 0, 0, 0, 0),
('1420', 36, 0, 0, 0, 0),
('1420', 37, 0, 0, 0, 0),
('1420', 38, 0, 0, 0, 0),
('1420', 39, 0, 0, 0, 0),
('1420', 40, 0, 0, 0, 0),
('1420', 41, 0, 0, 0, 0),
('1420', 42, 0, 0, 0, 0),
('1420', 43, 0, 0, 0, 0),
('1420', 44, 0, 0, 0, 0),
('1420', 45, 0, 0, 0, 0),
('1420', 46, 0, 0, 0, 0),
('1420', 47, 0, 0, 0, 0),
('1420', 48, 0, 0, 0, 0),
('1420', 49, 0, 0, 0, 0),
('1420', 50, 0, 0, 0, 0),
('1420', 51, 0, 0, 0, 0),
('1420', 52, 0, 0, 0, 0),
('1420', 53, 0, 0, 0, 0),
('1420', 54, 0, 0, 0, 0),
('1420', 55, 0, 0, 0, 0),
('1420', 56, 0, 0, 0, 0),
('1420', 57, 0, 0, 0, 0),
('1420', 58, 0, 0, 0, 0),
('1420', 59, 0, 0, 0, 0),
('1440', -15, 0, 0, 0, 0),
('1440', -14, 0, 0, 0, 0),
('1440', -13, 0, 0, 0, 0),
('1440', -12, 0, 0, 0, 0),
('1440', -11, 0, 0, 0, 0),
('1440', -10, 0, 0, 0, 0),
('1440', -9, 0, 0, 0, 0),
('1440', -8, 0, 0, 0, 0),
('1440', -7, 0, 0, 0, 0),
('1440', -6, 0, 0, 0, 0),
('1440', -5, 0, 0, 0, 0),
('1440', -4, 0, 0, 0, 0),
('1440', -3, 0, 0, 0, 0),
('1440', -2, 0, 0, 0, 0),
('1440', -1, 0, 0, 0, 0),
('1440', 0, 0, 0, 0, 0),
('1440', 1, 0, 0, 0, 0),
('1440', 2, 0, 0, 0, 0),
('1440', 3, 0, 0, 0, 0),
('1440', 4, 0, 0, 0, 0),
('1440', 5, 0, 0, 0, 0),
('1440', 6, 0, 0, 0, 0),
('1440', 7, 0, 0, 0, 0),
('1440', 8, 0, 0, 0, 0),
('1440', 9, 0, 0, 0, 0),
('1440', 10, 0, 0, 0, 0),
('1440', 11, 0, 0, 0, 0),
('1440', 12, 0, 0, 0, 0),
('1440', 13, 0, 0, 0, 0),
('1440', 14, 0, 0, 0, 0),
('1440', 15, 0, 0, 0, 0),
('1440', 16, 0, 0, 0, 0),
('1440', 17, 0, 0, 0, 0),
('1440', 18, 0, 0, 0, 0),
('1440', 19, 0, 0, 0, 0),
('1440', 20, 0, 0, 0, 0),
('1440', 21, 0, 0, 0, 0),
('1440', 22, 0, 0, 0, 0),
('1440', 23, 0, 0, 0, 0),
('1440', 24, 0, 0, 0, 0),
('1440', 25, 0, 0, 0, 0),
('1440', 26, 0, 0, 0, 0),
('1440', 27, 0, 0, 0, 0),
('1440', 28, 0, 0, 0, 0),
('1440', 29, 0, 0, 0, 0),
('1440', 30, 0, 0, 0, 0),
('1440', 31, 0, 0, 0, 0),
('1440', 32, 0, 0, 0, 0),
('1440', 33, 0, 0, 0, 0),
('1440', 34, 0, 0, 0, 0),
('1440', 35, 0, 0, 0, 0),
('1440', 36, 0, 0, 0, 0),
('1440', 37, 0, 0, 0, 0),
('1440', 38, 0, 0, 0, 0),
('1440', 39, 0, 0, 0, 0),
('1440', 40, 0, 0, 0, 0),
('1440', 41, 0, 0, 0, 0),
('1440', 42, 0, 0, 0, 0),
('1440', 43, 0, 0, 0, 0),
('1440', 44, 0, 0, 0, 0),
('1440', 45, 0, 0, 0, 0),
('1440', 46, 0, 0, 0, 0),
('1440', 47, 0, 0, 0, 0),
('1440', 48, 0, 0, 0, 0),
('1440', 49, 0, 0, 0, 0),
('1440', 50, 0, 0, 0, 0),
('1440', 51, 0, 0, 0, 0),
('1440', 52, 0, 0, 0, 0),
('1440', 53, 0, 0, 0, 0),
('1440', 54, 0, 0, 0, 0),
('1440', 55, 0, 0, 0, 0),
('1440', 56, 0, 0, 0, 0),
('1440', 57, 0, 0, 0, 0),
('1440', 58, 0, 0, 0, 0),
('1440', 59, 0, 0, 0, 0),
('1460', -15, 0, 0, 0, 0),
('1460', -14, 0, 0, 0, 0),
('1460', -13, 0, 0, 0, 0),
('1460', -12, 0, 0, 0, 0),
('1460', -11, 0, 0, 0, 0),
('1460', -10, 0, 0, 0, 0),
('1460', -9, 0, 0, 0, 0),
('1460', -8, 0, 0, 0, 0),
('1460', -7, 0, 0, 0, 0),
('1460', -6, 0, 0, 0, 0),
('1460', -5, 0, 0, 0, 0),
('1460', -4, 0, 0, 0, 0),
('1460', -3, 0, 0, 0, 0),
('1460', -2, 0, 0, 0, 0),
('1460', -1, 0, 0, 0, 0),
('1460', 0, 0, 0, 0, 0),
('1460', 1, 0, 0, 0, 0),
('1460', 2, 0, 0, 0, 0),
('1460', 3, 0, 0, 0, 0),
('1460', 4, 0, 0, 0, 0),
('1460', 5, 0, 0, 0, 0),
('1460', 6, 0, 0, 0, 0),
('1460', 7, 0, 0, 0, 0),
('1460', 8, 0, 0, 0, 0),
('1460', 9, 0, 0, 0, 0),
('1460', 10, 0, 0, 0, 0),
('1460', 11, 0, 0, 0, 0),
('1460', 12, 0, 0, 0, 0),
('1460', 13, 0, 0, 0, 0),
('1460', 14, 0, 0, 0, 0),
('1460', 15, 0, 0, 0, 0),
('1460', 16, 0, 0, 0, 0),
('1460', 17, 0, 0, 0, 0),
('1460', 18, 0, 0, 0, 0),
('1460', 19, 0, 0, 0, 0),
('1460', 20, 0, 0, 0, 0),
('1460', 21, 0, 0, 0, 0),
('1460', 22, 0, 0, 0, 0),
('1460', 23, 0, 0, 0, 0),
('1460', 24, 0, 0, 0, 0),
('1460', 25, 0, 0, 0, 0),
('1460', 26, 0, 0, 0, 0),
('1460', 27, 0, 0, 0, 0),
('1460', 28, 0, 0, 0, 0),
('1460', 29, 0, 0, 0, 0),
('1460', 30, 0, 0, 0, 0),
('1460', 31, 0, 0, 0, 0),
('1460', 32, 0, 0, 0, 0),
('1460', 33, 0, 0, 0, 0),
('1460', 34, 0, 0, 0, 0),
('1460', 35, 0, 0, 0, 0),
('1460', 36, 0, 0, 0, 0),
('1460', 37, 0, 0, 0, 0),
('1460', 38, 0, 0, 0, 0),
('1460', 39, 0, 0, 0, 0),
('1460', 40, 0, 0, 0, 0),
('1460', 41, 0, 0, 0, 0),
('1460', 42, 0, 0, 0, 0),
('1460', 43, 0, 0, 0, 0),
('1460', 44, 0, 0, 0, 0),
('1460', 45, 0, 0, 0, 0),
('1460', 46, 0, 0, 0, 0),
('1460', 47, 0, 0, 0, 0),
('1460', 48, 0, 0, 0, 0),
('1460', 49, 0, 0, 0, 0),
('1460', 50, 0, 0, 0, 0),
('1460', 51, 0, 0, 0, 0),
('1460', 52, 0, 0, 0, 0),
('1460', 53, 0, 0, 0, 0),
('1460', 54, 0, 0, 0, 0),
('1460', 55, 0, 0, 0, 0),
('1460', 56, 0, 0, 0, 0),
('1460', 57, 0, 0, 0, 0),
('1460', 58, 0, 0, 0, 0),
('1460', 59, 0, 0, 0, 0),
('1500', -15, 0, 0, 0, 0),
('1500', -14, 0, 0, 0, 0),
('1500', -13, 0, 0, 0, 0),
('1500', -12, 0, 0, 0, 0),
('1500', -11, 0, 0, 0, 0),
('1500', -10, 0, 0, 0, 0),
('1500', -9, 0, 0, 0, 0),
('1500', -8, 0, 0, 0, 0),
('1500', -7, 0, 0, 0, 0),
('1500', -6, 0, 0, 0, 0),
('1500', -5, 0, 0, 0, 0),
('1500', -4, 0, 0, 0, 0),
('1500', -3, 0, 0, 0, 0),
('1500', -2, 0, 0, 0, 0),
('1500', -1, 0, 0, 0, 0),
('1500', 0, 0, 0, 0, 0),
('1500', 1, 0, 0, 0, 0),
('1500', 2, 0, 0, 0, 0),
('1500', 3, 0, 0, 0, 0),
('1500', 4, 0, 0, 0, 0),
('1500', 5, 0, 0, 0, 0),
('1500', 6, 0, 0, 0, 0),
('1500', 7, 0, 0, 0, 0),
('1500', 8, 0, 0, 0, 0),
('1500', 9, 0, 0, 0, 0),
('1500', 10, 0, 0, 0, 0),
('1500', 11, 0, 0, 0, 0),
('1500', 12, 0, 0, 0, 0),
('1500', 13, 0, 0, 0, 0),
('1500', 14, 0, 0, 0, 0),
('1500', 15, 0, 0, 0, 0),
('1500', 16, 0, 0, 0, 0),
('1500', 17, 0, 0, 0, 0),
('1500', 18, 0, 0, 0, 0),
('1500', 19, 0, 0, 0, 0),
('1500', 20, 0, 0, 0, 0),
('1500', 21, 0, 0, 0, 0),
('1500', 22, 0, 0, 0, 0),
('1500', 23, 0, 0, 0, 0),
('1500', 24, 0, 0, 0, 0),
('1500', 25, 0, 0, 0, 0),
('1500', 26, 0, 0, 0, 0),
('1500', 27, 0, 0, 0, 0),
('1500', 28, 0, 0, 0, 0),
('1500', 29, 0, 0, 0, 0),
('1500', 30, 0, 0, 0, 0),
('1500', 31, 0, 0, 0, 0),
('1500', 32, 0, 0, 0, 0),
('1500', 33, 0, 0, 0, 0),
('1500', 34, 0, 0, 0, 0),
('1500', 35, 0, 0, 0, 0),
('1500', 36, 0, 0, 0, 0),
('1500', 37, 0, 0, 0, 0),
('1500', 38, 0, 0, 0, 0),
('1500', 39, 0, 0, 0, 0),
('1500', 40, 0, 0, 0, 0),
('1500', 41, 0, 0, 0, 0),
('1500', 42, 0, 0, 0, 0),
('1500', 43, 0, 0, 0, 0),
('1500', 44, 0, 0, 0, 0),
('1500', 45, 0, 0, 0, 0),
('1500', 46, 0, 0, 0, 0),
('1500', 47, 0, 0, 0, 0),
('1500', 48, 0, 0, 0, 0),
('1500', 49, 0, 0, 0, 0),
('1500', 50, 0, 0, 0, 0),
('1500', 51, 0, 0, 0, 0),
('1500', 52, 0, 0, 0, 0),
('1500', 53, 0, 0, 0, 0),
('1500', 54, 0, 0, 0, 0),
('1500', 55, 0, 0, 0, 0),
('1500', 56, 0, 0, 0, 0),
('1500', 57, 0, 0, 0, 0),
('1500', 58, 0, 0, 0, 0),
('1500', 59, 0, 0, 0, 0),
('1550', -15, 0, 0, 0, 0),
('1550', -14, 0, 0, 0, 0),
('1550', -13, 0, 0, 0, 0),
('1550', -12, 0, 0, 0, 0),
('1550', -11, 0, 0, 0, 0),
('1550', -10, 0, 0, 0, 0),
('1550', -9, 0, 0, 0, 0),
('1550', -8, 0, 0, 0, 0),
('1550', -7, 0, 0, 0, 0),
('1550', -6, 0, 0, 0, 0),
('1550', -5, 0, 0, 0, 0),
('1550', -4, 0, 0, 0, 0),
('1550', -3, 0, 0, 0, 0),
('1550', -2, 0, 0, 0, 0),
('1550', -1, 0, 0, 0, 0),
('1550', 0, 0, 0, 0, 0),
('1550', 1, 0, 0, 0, 0),
('1550', 2, 0, 0, 0, 0),
('1550', 3, 0, 0, 0, 0),
('1550', 4, 0, 0, 0, 0),
('1550', 5, 0, 0, 0, 0),
('1550', 6, 0, 0, 0, 0),
('1550', 7, 0, 0, 0, 0),
('1550', 8, 0, 0, 0, 0),
('1550', 9, 0, 0, 0, 0),
('1550', 10, 0, 0, 0, 0),
('1550', 11, 0, 0, 0, 0),
('1550', 12, 0, 0, 0, 0),
('1550', 13, 0, 0, 0, 0),
('1550', 14, 0, 0, 0, 0),
('1550', 15, 0, 0, 0, 0),
('1550', 16, 0, 0, 0, 0),
('1550', 17, 0, 0, 0, 0),
('1550', 18, 0, 0, 0, 0),
('1550', 19, 0, 0, 0, 0),
('1550', 20, 0, 0, 0, 0),
('1550', 21, 0, 0, 0, 0),
('1550', 22, 0, 0, 0, 0),
('1550', 23, 0, 0, 0, 0),
('1550', 24, 0, 0, 0, 0),
('1550', 25, 0, 0, 0, 0),
('1550', 26, 0, 0, 0, 0),
('1550', 27, 0, 0, 0, 0),
('1550', 28, 0, 0, 0, 0),
('1550', 29, 0, 0, 0, 0),
('1550', 30, 0, 0, 0, 0),
('1550', 31, 0, 0, 0, 0),
('1550', 32, 0, 0, 0, 0),
('1550', 33, 0, 0, 0, 0),
('1550', 34, 0, 0, 0, 0),
('1550', 35, 0, 0, 0, 0),
('1550', 36, 0, 0, 0, 0),
('1550', 37, 0, 0, 0, 0),
('1550', 38, 0, 0, 0, 0),
('1550', 39, 0, 0, 0, 0),
('1550', 40, 0, 0, 0, 0),
('1550', 41, 0, 0, 0, 0),
('1550', 42, 0, 0, 0, 0),
('1550', 43, 0, 0, 0, 0),
('1550', 44, 0, 0, 0, 0),
('1550', 45, 0, 0, 0, 0),
('1550', 46, 0, 0, 0, 0),
('1550', 47, 0, 0, 0, 0),
('1550', 48, 0, 0, 0, 0),
('1550', 49, 0, 0, 0, 0),
('1550', 50, 0, 0, 0, 0),
('1550', 51, 0, 0, 0, 0),
('1550', 52, 0, 0, 0, 0),
('1550', 53, 0, 0, 0, 0),
('1550', 54, 0, 0, 0, 0),
('1550', 55, 0, 0, 0, 0),
('1550', 56, 0, 0, 0, 0),
('1550', 57, 0, 0, 0, 0),
('1550', 58, 0, 0, 0, 0),
('1550', 59, 0, 0, 0, 0),
('1600', -15, 0, 0, 0, 0),
('1600', -14, 0, 0, 0, 0),
('1600', -13, 0, 0, 0, 0),
('1600', -12, 0, 0, 0, 0),
('1600', -11, 0, 0, 0, 0),
('1600', -10, 0, 0, 0, 0),
('1600', -9, 0, 0, 0, 0),
('1600', -8, 0, 0, 0, 0),
('1600', -7, 0, 0, 0, 0),
('1600', -6, 0, 0, 0, 0),
('1600', -5, 0, 0, 0, 0),
('1600', -4, 0, 0, 0, 0),
('1600', -3, 0, 0, 0, 0),
('1600', -2, 0, 0, 0, 0),
('1600', -1, 0, 0, 0, 0),
('1600', 0, 0, 0, 0, 0),
('1600', 1, 0, 0, 0, 0),
('1600', 2, 0, 0, 0, 0),
('1600', 3, 0, 0, 0, 0),
('1600', 4, 0, 0, 0, 0),
('1600', 5, 0, 0, 0, 0),
('1600', 6, 0, 0, 0, 0),
('1600', 7, 0, 0, 0, 0),
('1600', 8, 0, 0, 0, 0),
('1600', 9, 0, 0, 0, 0),
('1600', 10, 0, 0, 0, 0),
('1600', 11, 0, 0, 0, 0),
('1600', 12, 0, 0, 0, 0),
('1600', 13, 0, 0, 0, 0),
('1600', 14, 0, 0, 0, 0),
('1600', 15, 0, 0, 0, 0),
('1600', 16, 0, 0, 0, 0),
('1600', 17, 0, 0, 0, 0),
('1600', 18, 0, 0, 0, 0),
('1600', 19, 0, 0, 0, 0),
('1600', 20, 0, 0, 0, 0),
('1600', 21, 0, 0, 0, 0),
('1600', 22, 0, 0, 0, 0),
('1600', 23, 0, 0, 0, 0),
('1600', 24, 0, 0, 0, 0),
('1600', 25, 0, 0, 0, 0),
('1600', 26, 0, 0, 0, 0),
('1600', 27, 0, 0, 0, 0),
('1600', 28, 0, 0, 0, 0),
('1600', 29, 0, 0, 0, 0),
('1600', 30, 0, 0, 0, 0),
('1600', 31, 0, 0, 0, 0),
('1600', 32, 0, 0, 0, 0),
('1600', 33, 0, 0, 0, 0),
('1600', 34, 0, 0, 0, 0),
('1600', 35, 0, 0, 0, 0),
('1600', 36, 0, 0, 0, 0),
('1600', 37, 0, 0, 0, 0),
('1600', 38, 0, 0, 0, 0),
('1600', 39, 0, 0, 0, 0),
('1600', 40, 0, 0, 0, 0),
('1600', 41, 0, 0, 0, 0),
('1600', 42, 0, 0, 0, 0),
('1600', 43, 0, 0, 0, 0),
('1600', 44, 0, 0, 0, 0),
('1600', 45, 0, 0, 0, 0),
('1600', 46, 0, 0, 0, 0),
('1600', 47, 0, 0, 0, 0),
('1600', 48, 0, 0, 0, 0),
('1600', 49, 0, 0, 0, 0),
('1600', 50, 0, 0, 0, 0),
('1600', 51, 0, 0, 0, 0),
('1600', 52, 0, 0, 0, 0),
('1600', 53, 0, 0, 0, 0),
('1600', 54, 0, 0, 0, 0),
('1600', 55, 0, 0, 0, 0),
('1600', 56, 0, 0, 0, 0),
('1600', 57, 0, 0, 0, 0),
('1600', 58, 0, 0, 0, 0),
('1600', 59, 0, 0, 0, 0),
('1620', -15, 0, 0, 0, 0),
('1620', -14, 0, 0, 0, 0),
('1620', -13, 0, 0, 0, 0),
('1620', -12, 0, 0, 0, 0),
('1620', -11, 0, 0, 0, 0),
('1620', -10, 0, 0, 0, 0),
('1620', -9, 0, 0, 0, 0),
('1620', -8, 0, 0, 0, 0),
('1620', -7, 0, 0, 0, 0),
('1620', -6, 0, 0, 0, 0),
('1620', -5, 0, 0, 0, 0),
('1620', -4, 0, 0, 0, 0),
('1620', -3, 0, 0, 0, 0),
('1620', -2, 0, 0, 0, 0),
('1620', -1, 0, 0, 0, 0),
('1620', 0, 0, 0, 0, 0),
('1620', 1, 0, 0, 0, 0),
('1620', 2, 0, 0, 0, 0),
('1620', 3, 0, 0, 0, 0),
('1620', 4, 0, 0, 0, 0),
('1620', 5, 0, 0, 0, 0),
('1620', 6, 0, 0, 0, 0),
('1620', 7, 0, 0, 0, 0),
('1620', 8, 0, 0, 0, 0),
('1620', 9, 0, 0, 0, 0),
('1620', 10, 0, 0, 0, 0),
('1620', 11, 0, 0, 0, 0),
('1620', 12, 0, 0, 0, 0),
('1620', 13, 0, 0, 0, 0),
('1620', 14, 0, 0, 0, 0),
('1620', 15, 0, 0, 0, 0),
('1620', 16, 0, 0, 0, 0),
('1620', 17, 0, 0, 0, 0),
('1620', 18, 0, 0, 0, 0),
('1620', 19, 0, 0, 0, 0),
('1620', 20, 0, 0, 0, 0),
('1620', 21, 0, 0, 0, 0),
('1620', 22, 0, 0, 0, 0),
('1620', 23, 0, 0, 0, 0),
('1620', 24, 0, 0, 0, 0),
('1620', 25, 0, 0, 0, 0),
('1620', 26, 0, 0, 0, 0),
('1620', 27, 0, 0, 0, 0),
('1620', 28, 0, 0, 0, 0),
('1620', 29, 0, 0, 0, 0),
('1620', 30, 0, 0, 0, 0),
('1620', 31, 0, 0, 0, 0),
('1620', 32, 0, 0, 0, 0),
('1620', 33, 0, 0, 0, 0),
('1620', 34, 0, 0, 0, 0),
('1620', 35, 0, 0, 0, 0),
('1620', 36, 0, 0, 0, 0),
('1620', 37, 0, 0, 0, 0),
('1620', 38, 0, 0, 0, 0),
('1620', 39, 0, 0, 0, 0),
('1620', 40, 0, 0, 0, 0),
('1620', 41, 0, 0, 0, 0),
('1620', 42, 0, 0, 0, 0),
('1620', 43, 0, 0, 0, 0),
('1620', 44, 0, 0, 0, 0),
('1620', 45, 0, 0, 0, 0),
('1620', 46, 0, 0, 0, 0),
('1620', 47, 0, 0, 0, 0),
('1620', 48, 0, 0, 0, 0),
('1620', 49, 0, 0, 0, 0),
('1620', 50, 0, 0, 0, 0),
('1620', 51, 0, 0, 0, 0),
('1620', 52, 0, 0, 0, 0),
('1620', 53, 0, 0, 0, 0),
('1620', 54, 0, 0, 0, 0),
('1620', 55, 0, 0, 0, 0),
('1620', 56, 0, 0, 0, 0),
('1620', 57, 0, 0, 0, 0),
('1620', 58, 0, 0, 0, 0),
('1620', 59, 0, 0, 0, 0),
('1650', -15, 0, 0, 0, 0),
('1650', -14, 0, 0, 0, 0),
('1650', -13, 0, 0, 0, 0),
('1650', -12, 0, 0, 0, 0),
('1650', -11, 0, 0, 0, 0),
('1650', -10, 0, 0, 0, 0),
('1650', -9, 0, 0, 0, 0),
('1650', -8, 0, 0, 0, 0),
('1650', -7, 0, 0, 0, 0),
('1650', -6, 0, 0, 0, 0),
('1650', -5, 0, 0, 0, 0),
('1650', -4, 0, 0, 0, 0),
('1650', -3, 0, 0, 0, 0),
('1650', -2, 0, 0, 0, 0),
('1650', -1, 0, 0, 0, 0),
('1650', 0, 0, 0, 0, 0),
('1650', 1, 0, 0, 0, 0),
('1650', 2, 0, 0, 0, 0),
('1650', 3, 0, 0, 0, 0),
('1650', 4, 0, 0, 0, 0),
('1650', 5, 0, 0, 0, 0),
('1650', 6, 0, 0, 0, 0),
('1650', 7, 0, 0, 0, 0),
('1650', 8, 0, 0, 0, 0),
('1650', 9, 0, 0, 0, 0),
('1650', 10, 0, 0, 0, 0),
('1650', 11, 0, 0, 0, 0),
('1650', 12, 0, 0, 0, 0),
('1650', 13, 0, 0, 0, 0),
('1650', 14, 0, 0, 0, 0),
('1650', 15, 0, 0, 0, 0),
('1650', 16, 0, 0, 0, 0),
('1650', 17, 0, 0, 0, 0),
('1650', 18, 0, 0, 0, 0),
('1650', 19, 0, 0, 0, 0),
('1650', 20, 0, 0, 0, 0),
('1650', 21, 0, 0, 0, 0),
('1650', 22, 0, 0, 0, 0),
('1650', 23, 0, 0, 0, 0),
('1650', 24, 0, 0, 0, 0),
('1650', 25, 0, 0, 0, 0),
('1650', 26, 0, 0, 0, 0),
('1650', 27, 0, 0, 0, 0),
('1650', 28, 0, 0, 0, 0),
('1650', 29, 0, 0, 0, 0),
('1650', 30, 0, 0, 0, 0),
('1650', 31, 0, 0, 0, 0),
('1650', 32, 0, 0, 0, 0),
('1650', 33, 0, 0, 0, 0),
('1650', 34, 0, 0, 0, 0),
('1650', 35, 0, 0, 0, 0),
('1650', 36, 0, 0, 0, 0),
('1650', 37, 0, 0, 0, 0),
('1650', 38, 0, 0, 0, 0),
('1650', 39, 0, 0, 0, 0),
('1650', 40, 0, 0, 0, 0),
('1650', 41, 0, 0, 0, 0),
('1650', 42, 0, 0, 0, 0),
('1650', 43, 0, 0, 0, 0),
('1650', 44, 0, 0, 0, 0),
('1650', 45, 0, 0, 0, 0),
('1650', 46, 0, 0, 0, 0),
('1650', 47, 0, 0, 0, 0),
('1650', 48, 0, 0, 0, 0),
('1650', 49, 0, 0, 0, 0),
('1650', 50, 0, 0, 0, 0),
('1650', 51, 0, 0, 0, 0),
('1650', 52, 0, 0, 0, 0),
('1650', 53, 0, 0, 0, 0),
('1650', 54, 0, 0, 0, 0),
('1650', 55, 0, 0, 0, 0),
('1650', 56, 0, 0, 0, 0),
('1650', 57, 0, 0, 0, 0),
('1650', 58, 0, 0, 0, 0),
('1650', 59, 0, 0, 0, 0),
('1670', -15, 0, 0, 0, 0),
('1670', -14, 0, 0, 0, 0),
('1670', -13, 0, 0, 0, 0),
('1670', -12, 0, 0, 0, 0),
('1670', -11, 0, 0, 0, 0),
('1670', -10, 0, 0, 0, 0),
('1670', -9, 0, 0, 0, 0),
('1670', -8, 0, 0, 0, 0),
('1670', -7, 0, 0, 0, 0),
('1670', -6, 0, 0, 0, 0),
('1670', -5, 0, 0, 0, 0),
('1670', -4, 0, 0, 0, 0),
('1670', -3, 0, 0, 0, 0),
('1670', -2, 0, 0, 0, 0),
('1670', -1, 0, 0, 0, 0),
('1670', 0, 0, 0, 0, 0),
('1670', 1, 0, 0, 0, 0),
('1670', 2, 0, 0, 0, 0),
('1670', 3, 0, 0, 0, 0),
('1670', 4, 0, 0, 0, 0),
('1670', 5, 0, 0, 0, 0),
('1670', 6, 0, 0, 0, 0),
('1670', 7, 0, 0, 0, 0),
('1670', 8, 0, 0, 0, 0),
('1670', 9, 0, 0, 0, 0),
('1670', 10, 0, 0, 0, 0),
('1670', 11, 0, 0, 0, 0),
('1670', 12, 0, 0, 0, 0),
('1670', 13, 0, 0, 0, 0),
('1670', 14, 0, 0, 0, 0),
('1670', 15, 0, 0, 0, 0),
('1670', 16, 0, 0, 0, 0),
('1670', 17, 0, 0, 0, 0),
('1670', 18, 0, 0, 0, 0),
('1670', 19, 0, 0, 0, 0),
('1670', 20, 0, 0, 0, 0),
('1670', 21, 0, 0, 0, 0),
('1670', 22, 0, 0, 0, 0),
('1670', 23, 0, 0, 0, 0),
('1670', 24, 0, 0, 0, 0),
('1670', 25, 0, 0, 0, 0),
('1670', 26, 0, 0, 0, 0),
('1670', 27, 0, 0, 0, 0),
('1670', 28, 0, 0, 0, 0),
('1670', 29, 0, 0, 0, 0),
('1670', 30, 0, 0, 0, 0),
('1670', 31, 0, 0, 0, 0),
('1670', 32, 0, 0, 0, 0),
('1670', 33, 0, 0, 0, 0),
('1670', 34, 0, 0, 0, 0),
('1670', 35, 0, 0, 0, 0),
('1670', 36, 0, 0, 0, 0),
('1670', 37, 0, 0, 0, 0),
('1670', 38, 0, 0, 0, 0),
('1670', 39, 0, 0, 0, 0),
('1670', 40, 0, 0, 0, 0),
('1670', 41, 0, 0, 0, 0),
('1670', 42, 0, 0, 0, 0),
('1670', 43, 0, 0, 0, 0),
('1670', 44, 0, 0, 0, 0),
('1670', 45, 0, 0, 0, 0),
('1670', 46, 0, 0, 0, 0),
('1670', 47, 0, 0, 0, 0),
('1670', 48, 0, 0, 0, 0),
('1670', 49, 0, 0, 0, 0),
('1670', 50, 0, 0, 0, 0),
('1670', 51, 0, 0, 0, 0),
('1670', 52, 0, 0, 0, 0),
('1670', 53, 0, 0, 0, 0),
('1670', 54, 0, 0, 0, 0),
('1670', 55, 0, 0, 0, 0),
('1670', 56, 0, 0, 0, 0),
('1670', 57, 0, 0, 0, 0),
('1670', 58, 0, 0, 0, 0),
('1670', 59, 0, 0, 0, 0),
('1700', -15, 0, 0, 0, 0),
('1700', -14, 0, 0, 0, 0),
('1700', -13, 0, 0, 0, 0),
('1700', -12, 0, 0, 0, 0),
('1700', -11, 0, 0, 0, 0),
('1700', -10, 0, 0, 0, 0),
('1700', -9, 0, 0, 0, 0),
('1700', -8, 0, 0, 0, 0),
('1700', -7, 0, 0, 0, 0),
('1700', -6, 0, 0, 0, 0),
('1700', -5, 0, 0, 0, 0),
('1700', -4, 0, 0, 0, 0),
('1700', -3, 0, 0, 0, 0),
('1700', -2, 0, 0, 0, 0),
('1700', -1, 0, 0, 0, 0),
('1700', 0, 0, 0, 0, 0),
('1700', 1, 0, 0, 0, 0),
('1700', 2, 0, 0, 0, 0),
('1700', 3, 0, 0, 0, 0),
('1700', 4, 0, 0, 0, 0),
('1700', 5, 0, 0, 0, 0),
('1700', 6, 0, 0, 0, 0),
('1700', 7, 0, 0, 0, 0),
('1700', 8, 0, 0, 0, 0),
('1700', 9, 0, 0, 0, 0),
('1700', 10, 0, 0, 0, 0),
('1700', 11, 0, 0, 0, 0),
('1700', 12, 0, 0, 0, 0),
('1700', 13, 0, 0, 0, 0),
('1700', 14, 0, 0, 0, 0),
('1700', 15, 0, 0, 0, 0),
('1700', 16, 0, 0, 0, 0),
('1700', 17, 0, 0, 0, 0),
('1700', 18, 0, 0, 0, 0),
('1700', 19, 0, 0, 0, 0),
('1700', 20, 0, 0, 0, 0),
('1700', 21, 0, 0, 0, 0),
('1700', 22, 0, 0, 0, 0),
('1700', 23, 0, 0, 0, 0),
('1700', 24, 0, 0, 0, 0),
('1700', 25, 0, 0, 0, 0),
('1700', 26, 0, 0, 0, 0),
('1700', 27, 0, 0, 0, 0),
('1700', 28, 0, 0, 0, 0),
('1700', 29, 0, 0, 0, 0),
('1700', 30, 0, 0, 0, 0),
('1700', 31, 0, 0, 0, 0),
('1700', 32, 0, 0, 0, 0),
('1700', 33, 0, 0, 0, 0),
('1700', 34, 0, 0, 0, 0),
('1700', 35, 0, 0, 0, 0),
('1700', 36, 0, 0, 0, 0),
('1700', 37, 0, 0, 0, 0),
('1700', 38, 0, 0, 0, 0),
('1700', 39, 0, 0, 0, 0),
('1700', 40, 0, 0, 0, 0),
('1700', 41, 0, 0, 0, 0),
('1700', 42, 0, 0, 0, 0),
('1700', 43, 0, 0, 0, 0),
('1700', 44, 0, 0, 0, 0),
('1700', 45, 0, 0, 0, 0),
('1700', 46, 0, 0, 0, 0),
('1700', 47, 0, 0, 0, 0),
('1700', 48, 0, 0, 0, 0),
('1700', 49, 0, 0, 0, 0),
('1700', 50, 0, 0, 0, 0),
('1700', 51, 0, 0, 0, 0),
('1700', 52, 0, 0, 0, 0),
('1700', 53, 0, 0, 0, 0),
('1700', 54, 0, 0, 0, 0),
('1700', 55, 0, 0, 0, 0),
('1700', 56, 0, 0, 0, 0),
('1700', 57, 0, 0, 0, 0),
('1700', 58, 0, 0, 0, 0),
('1700', 59, 0, 0, 0, 0),
('1710', -15, 0, 0, 0, 0),
('1710', -14, 0, 0, 0, 0),
('1710', -13, 0, 0, 0, 0),
('1710', -12, 0, 0, 0, 0),
('1710', -11, 0, 0, 0, 0),
('1710', -10, 0, 0, 0, 0),
('1710', -9, 0, 0, 0, 0),
('1710', -8, 0, 0, 0, 0),
('1710', -7, 0, 0, 0, 0),
('1710', -6, 0, 0, 0, 0),
('1710', -5, 0, 0, 0, 0),
('1710', -4, 0, 0, 0, 0),
('1710', -3, 0, 0, 0, 0),
('1710', -2, 0, 0, 0, 0),
('1710', -1, 0, 0, 0, 0),
('1710', 0, 0, 0, 0, 0),
('1710', 1, 0, 0, 0, 0),
('1710', 2, 0, 0, 0, 0),
('1710', 3, 0, 0, 0, 0),
('1710', 4, 0, 0, 0, 0),
('1710', 5, 0, 0, 0, 0),
('1710', 6, 0, 0, 0, 0),
('1710', 7, 0, 0, 0, 0),
('1710', 8, 0, 0, 0, 0),
('1710', 9, 0, 0, 0, 0),
('1710', 10, 0, 0, 0, 0),
('1710', 11, 0, 0, 0, 0),
('1710', 12, 0, 0, 0, 0),
('1710', 13, 0, 0, 0, 0),
('1710', 14, 0, 0, 0, 0),
('1710', 15, 0, 0, 0, 0),
('1710', 16, 0, 0, 0, 0),
('1710', 17, 0, 0, 0, 0),
('1710', 18, 0, 0, 0, 0),
('1710', 19, 0, 0, 0, 0),
('1710', 20, 0, 0, 0, 0),
('1710', 21, 0, 0, 0, 0),
('1710', 22, 0, 0, 0, 0),
('1710', 23, 0, 0, 0, 0),
('1710', 24, 0, 0, 0, 0),
('1710', 25, 0, 0, 0, 0),
('1710', 26, 0, 0, 0, 0),
('1710', 27, 0, 0, 0, 0),
('1710', 28, 0, 0, 0, 0),
('1710', 29, 0, 0, 0, 0),
('1710', 30, 0, 0, 0, 0),
('1710', 31, 0, 0, 0, 0),
('1710', 32, 0, 0, 0, 0),
('1710', 33, 0, 0, 0, 0),
('1710', 34, 0, 0, 0, 0),
('1710', 35, 0, 0, 0, 0),
('1710', 36, 0, 0, 0, 0),
('1710', 37, 0, 0, 0, 0),
('1710', 38, 0, 0, 0, 0),
('1710', 39, 0, 0, 0, 0),
('1710', 40, 0, 0, 0, 0),
('1710', 41, 0, 0, 0, 0),
('1710', 42, 0, 0, 0, 0),
('1710', 43, 0, 0, 0, 0),
('1710', 44, 0, 0, 0, 0),
('1710', 45, 0, 0, 0, 0),
('1710', 46, 0, 0, 0, 0),
('1710', 47, 0, 0, 0, 0),
('1710', 48, 0, 0, 0, 0),
('1710', 49, 0, 0, 0, 0),
('1710', 50, 0, 0, 0, 0),
('1710', 51, 0, 0, 0, 0),
('1710', 52, 0, 0, 0, 0);
INSERT INTO `chartdetails` (`accountcode`, `period`, `budget`, `actual`, `bfwd`, `bfwdbudget`) VALUES
('1710', 53, 0, 0, 0, 0),
('1710', 54, 0, 0, 0, 0),
('1710', 55, 0, 0, 0, 0),
('1710', 56, 0, 0, 0, 0),
('1710', 57, 0, 0, 0, 0),
('1710', 58, 0, 0, 0, 0),
('1710', 59, 0, 0, 0, 0),
('1720', -15, 0, 0, 0, 0),
('1720', -14, 0, 0, 0, 0),
('1720', -13, 0, 0, 0, 0),
('1720', -12, 0, 0, 0, 0),
('1720', -11, 0, 0, 0, 0),
('1720', -10, 0, 0, 0, 0),
('1720', -9, 0, 0, 0, 0),
('1720', -8, 0, 0, 0, 0),
('1720', -7, 0, 0, 0, 0),
('1720', -6, 0, 0, 0, 0),
('1720', -5, 0, 0, 0, 0),
('1720', -4, 0, 0, 0, 0),
('1720', -3, 0, 0, 0, 0),
('1720', -2, 0, 0, 0, 0),
('1720', -1, 0, 0, 0, 0),
('1720', 0, 0, 0, 0, 0),
('1720', 1, 0, 0, 0, 0),
('1720', 2, 0, 0, 0, 0),
('1720', 3, 0, 0, 0, 0),
('1720', 4, 0, 0, 0, 0),
('1720', 5, 0, 0, 0, 0),
('1720', 6, 0, 0, 0, 0),
('1720', 7, 0, 0, 0, 0),
('1720', 8, 0, 0, 0, 0),
('1720', 9, 0, 0, 0, 0),
('1720', 10, 0, 0, 0, 0),
('1720', 11, 0, 0, 0, 0),
('1720', 12, 0, 0, 0, 0),
('1720', 13, 0, 0, 0, 0),
('1720', 14, 0, 0, 0, 0),
('1720', 15, 0, 0, 0, 0),
('1720', 16, 0, 0, 0, 0),
('1720', 17, 0, 0, 0, 0),
('1720', 18, 0, 0, 0, 0),
('1720', 19, 0, 0, 0, 0),
('1720', 20, 0, 0, 0, 0),
('1720', 21, 0, 0, 0, 0),
('1720', 22, 0, 0, 0, 0),
('1720', 23, 0, 0, 0, 0),
('1720', 24, 0, 0, 0, 0),
('1720', 25, 0, 0, 0, 0),
('1720', 26, 0, 0, 0, 0),
('1720', 27, 0, 0, 0, 0),
('1720', 28, 0, 0, 0, 0),
('1720', 29, 0, 0, 0, 0),
('1720', 30, 0, 0, 0, 0),
('1720', 31, 0, 0, 0, 0),
('1720', 32, 0, 0, 0, 0),
('1720', 33, 0, 0, 0, 0),
('1720', 34, 0, 0, 0, 0),
('1720', 35, 0, 0, 0, 0),
('1720', 36, 0, 0, 0, 0),
('1720', 37, 0, 0, 0, 0),
('1720', 38, 0, 0, 0, 0),
('1720', 39, 0, 0, 0, 0),
('1720', 40, 0, 0, 0, 0),
('1720', 41, 0, 0, 0, 0),
('1720', 42, 0, 0, 0, 0),
('1720', 43, 0, 0, 0, 0),
('1720', 44, 0, 0, 0, 0),
('1720', 45, 0, 0, 0, 0),
('1720', 46, 0, 0, 0, 0),
('1720', 47, 0, 0, 0, 0),
('1720', 48, 0, 0, 0, 0),
('1720', 49, 0, 0, 0, 0),
('1720', 50, 0, 0, 0, 0),
('1720', 51, 0, 0, 0, 0),
('1720', 52, 0, 0, 0, 0),
('1720', 53, 0, 0, 0, 0),
('1720', 54, 0, 0, 0, 0),
('1720', 55, 0, 0, 0, 0),
('1720', 56, 0, 0, 0, 0),
('1720', 57, 0, 0, 0, 0),
('1720', 58, 0, 0, 0, 0),
('1720', 59, 0, 0, 0, 0),
('1730', -15, 0, 0, 0, 0),
('1730', -14, 0, 0, 0, 0),
('1730', -13, 0, 0, 0, 0),
('1730', -12, 0, 0, 0, 0),
('1730', -11, 0, 0, 0, 0),
('1730', -10, 0, 0, 0, 0),
('1730', -9, 0, 0, 0, 0),
('1730', -8, 0, 0, 0, 0),
('1730', -7, 0, 0, 0, 0),
('1730', -6, 0, 0, 0, 0),
('1730', -5, 0, 0, 0, 0),
('1730', -4, 0, 0, 0, 0),
('1730', -3, 0, 0, 0, 0),
('1730', -2, 0, 0, 0, 0),
('1730', -1, 0, 0, 0, 0),
('1730', 0, 0, 0, 0, 0),
('1730', 1, 0, 0, 0, 0),
('1730', 2, 0, 0, 0, 0),
('1730', 3, 0, 0, 0, 0),
('1730', 4, 0, 0, 0, 0),
('1730', 5, 0, 0, 0, 0),
('1730', 6, 0, 0, 0, 0),
('1730', 7, 0, 0, 0, 0),
('1730', 8, 0, 0, 0, 0),
('1730', 9, 0, 0, 0, 0),
('1730', 10, 0, 0, 0, 0),
('1730', 11, 0, 0, 0, 0),
('1730', 12, 0, 0, 0, 0),
('1730', 13, 0, 0, 0, 0),
('1730', 14, 0, 0, 0, 0),
('1730', 15, 0, 0, 0, 0),
('1730', 16, 0, 0, 0, 0),
('1730', 17, 0, 0, 0, 0),
('1730', 18, 0, 0, 0, 0),
('1730', 19, 0, 0, 0, 0),
('1730', 20, 0, 0, 0, 0),
('1730', 21, 0, 0, 0, 0),
('1730', 22, 0, 0, 0, 0),
('1730', 23, 0, 0, 0, 0),
('1730', 24, 0, 0, 0, 0),
('1730', 25, 0, 0, 0, 0),
('1730', 26, 0, 0, 0, 0),
('1730', 27, 0, 0, 0, 0),
('1730', 28, 0, 0, 0, 0),
('1730', 29, 0, 0, 0, 0),
('1730', 30, 0, 0, 0, 0),
('1730', 31, 0, 0, 0, 0),
('1730', 32, 0, 0, 0, 0),
('1730', 33, 0, 0, 0, 0),
('1730', 34, 0, 0, 0, 0),
('1730', 35, 0, 0, 0, 0),
('1730', 36, 0, 0, 0, 0),
('1730', 37, 0, 0, 0, 0),
('1730', 38, 0, 0, 0, 0),
('1730', 39, 0, 0, 0, 0),
('1730', 40, 0, 0, 0, 0),
('1730', 41, 0, 0, 0, 0),
('1730', 42, 0, 0, 0, 0),
('1730', 43, 0, 0, 0, 0),
('1730', 44, 0, 0, 0, 0),
('1730', 45, 0, 0, 0, 0),
('1730', 46, 0, 0, 0, 0),
('1730', 47, 0, 0, 0, 0),
('1730', 48, 0, 0, 0, 0),
('1730', 49, 0, 0, 0, 0),
('1730', 50, 0, 0, 0, 0),
('1730', 51, 0, 0, 0, 0),
('1730', 52, 0, 0, 0, 0),
('1730', 53, 0, 0, 0, 0),
('1730', 54, 0, 0, 0, 0),
('1730', 55, 0, 0, 0, 0),
('1730', 56, 0, 0, 0, 0),
('1730', 57, 0, 0, 0, 0),
('1730', 58, 0, 0, 0, 0),
('1730', 59, 0, 0, 0, 0),
('1740', -15, 0, 0, 0, 0),
('1740', -14, 0, 0, 0, 0),
('1740', -13, 0, 0, 0, 0),
('1740', -12, 0, 0, 0, 0),
('1740', -11, 0, 0, 0, 0),
('1740', -10, 0, 0, 0, 0),
('1740', -9, 0, 0, 0, 0),
('1740', -8, 0, 0, 0, 0),
('1740', -7, 0, 0, 0, 0),
('1740', -6, 0, 0, 0, 0),
('1740', -5, 0, 0, 0, 0),
('1740', -4, 0, 0, 0, 0),
('1740', -3, 0, 0, 0, 0),
('1740', -2, 0, 0, 0, 0),
('1740', -1, 0, 0, 0, 0),
('1740', 0, 0, 0, 0, 0),
('1740', 1, 0, 0, 0, 0),
('1740', 2, 0, 0, 0, 0),
('1740', 3, 0, 0, 0, 0),
('1740', 4, 0, 0, 0, 0),
('1740', 5, 0, 0, 0, 0),
('1740', 6, 0, 0, 0, 0),
('1740', 7, 0, 0, 0, 0),
('1740', 8, 0, 0, 0, 0),
('1740', 9, 0, 0, 0, 0),
('1740', 10, 0, 0, 0, 0),
('1740', 11, 0, 0, 0, 0),
('1740', 12, 0, 0, 0, 0),
('1740', 13, 0, 0, 0, 0),
('1740', 14, 0, 0, 0, 0),
('1740', 15, 0, 0, 0, 0),
('1740', 16, 0, 0, 0, 0),
('1740', 17, 0, 0, 0, 0),
('1740', 18, 0, 0, 0, 0),
('1740', 19, 0, 0, 0, 0),
('1740', 20, 0, 0, 0, 0),
('1740', 21, 0, 0, 0, 0),
('1740', 22, 0, 0, 0, 0),
('1740', 23, 0, 0, 0, 0),
('1740', 24, 0, 0, 0, 0),
('1740', 25, 0, 0, 0, 0),
('1740', 26, 0, 0, 0, 0),
('1740', 27, 0, 0, 0, 0),
('1740', 28, 0, 0, 0, 0),
('1740', 29, 0, 0, 0, 0),
('1740', 30, 0, 0, 0, 0),
('1740', 31, 0, 0, 0, 0),
('1740', 32, 0, 0, 0, 0),
('1740', 33, 0, 0, 0, 0),
('1740', 34, 0, 0, 0, 0),
('1740', 35, 0, 0, 0, 0),
('1740', 36, 0, 0, 0, 0),
('1740', 37, 0, 0, 0, 0),
('1740', 38, 0, 0, 0, 0),
('1740', 39, 0, 0, 0, 0),
('1740', 40, 0, 0, 0, 0),
('1740', 41, 0, 0, 0, 0),
('1740', 42, 0, 0, 0, 0),
('1740', 43, 0, 0, 0, 0),
('1740', 44, 0, 0, 0, 0),
('1740', 45, 0, 0, 0, 0),
('1740', 46, 0, 0, 0, 0),
('1740', 47, 0, 0, 0, 0),
('1740', 48, 0, 0, 0, 0),
('1740', 49, 0, 0, 0, 0),
('1740', 50, 0, 0, 0, 0),
('1740', 51, 0, 0, 0, 0),
('1740', 52, 0, 0, 0, 0),
('1740', 53, 0, 0, 0, 0),
('1740', 54, 0, 0, 0, 0),
('1740', 55, 0, 0, 0, 0),
('1740', 56, 0, 0, 0, 0),
('1740', 57, 0, 0, 0, 0),
('1740', 58, 0, 0, 0, 0),
('1740', 59, 0, 0, 0, 0),
('1750', -15, 0, 0, 0, 0),
('1750', -14, 0, 0, 0, 0),
('1750', -13, 0, 0, 0, 0),
('1750', -12, 0, 0, 0, 0),
('1750', -11, 0, 0, 0, 0),
('1750', -10, 0, 0, 0, 0),
('1750', -9, 0, 0, 0, 0),
('1750', -8, 0, 0, 0, 0),
('1750', -7, 0, 0, 0, 0),
('1750', -6, 0, 0, 0, 0),
('1750', -5, 0, 0, 0, 0),
('1750', -4, 0, 0, 0, 0),
('1750', -3, 0, 0, 0, 0),
('1750', -2, 0, 0, 0, 0),
('1750', -1, 0, 0, 0, 0),
('1750', 0, 0, 0, 0, 0),
('1750', 1, 0, 0, 0, 0),
('1750', 2, 0, 0, 0, 0),
('1750', 3, 0, 0, 0, 0),
('1750', 4, 0, 0, 0, 0),
('1750', 5, 0, 0, 0, 0),
('1750', 6, 0, 0, 0, 0),
('1750', 7, 0, 0, 0, 0),
('1750', 8, 0, 0, 0, 0),
('1750', 9, 0, 0, 0, 0),
('1750', 10, 0, 0, 0, 0),
('1750', 11, 0, 0, 0, 0),
('1750', 12, 0, 0, 0, 0),
('1750', 13, 0, 0, 0, 0),
('1750', 14, 0, 0, 0, 0),
('1750', 15, 0, 0, 0, 0),
('1750', 16, 0, 0, 0, 0),
('1750', 17, 0, 0, 0, 0),
('1750', 18, 0, 0, 0, 0),
('1750', 19, 0, 0, 0, 0),
('1750', 20, 0, 0, 0, 0),
('1750', 21, 0, 0, 0, 0),
('1750', 22, 0, 0, 0, 0),
('1750', 23, 0, 0, 0, 0),
('1750', 24, 0, 0, 0, 0),
('1750', 25, 0, 0, 0, 0),
('1750', 26, 0, 0, 0, 0),
('1750', 27, 0, 0, 0, 0),
('1750', 28, 0, 0, 0, 0),
('1750', 29, 0, 0, 0, 0),
('1750', 30, 0, 0, 0, 0),
('1750', 31, 0, 0, 0, 0),
('1750', 32, 0, 0, 0, 0),
('1750', 33, 0, 0, 0, 0),
('1750', 34, 0, 0, 0, 0),
('1750', 35, 0, 0, 0, 0),
('1750', 36, 0, 0, 0, 0),
('1750', 37, 0, 0, 0, 0),
('1750', 38, 0, 0, 0, 0),
('1750', 39, 0, 0, 0, 0),
('1750', 40, 0, 0, 0, 0),
('1750', 41, 0, 0, 0, 0),
('1750', 42, 0, 0, 0, 0),
('1750', 43, 0, 0, 0, 0),
('1750', 44, 0, 0, 0, 0),
('1750', 45, 0, 0, 0, 0),
('1750', 46, 0, 0, 0, 0),
('1750', 47, 0, 0, 0, 0),
('1750', 48, 0, 0, 0, 0),
('1750', 49, 0, 0, 0, 0),
('1750', 50, 0, 0, 0, 0),
('1750', 51, 0, 0, 0, 0),
('1750', 52, 0, 0, 0, 0),
('1750', 53, 0, 0, 0, 0),
('1750', 54, 0, 0, 0, 0),
('1750', 55, 0, 0, 0, 0),
('1750', 56, 0, 0, 0, 0),
('1750', 57, 0, 0, 0, 0),
('1750', 58, 0, 0, 0, 0),
('1750', 59, 0, 0, 0, 0),
('1760', -15, 0, 0, 0, 0),
('1760', -14, 0, 0, 0, 0),
('1760', -13, 0, 0, 0, 0),
('1760', -12, 0, 0, 0, 0),
('1760', -11, 0, 0, 0, 0),
('1760', -10, 0, 0, 0, 0),
('1760', -9, 0, 0, 0, 0),
('1760', -8, 0, 0, 0, 0),
('1760', -7, 0, 0, 0, 0),
('1760', -6, 0, 0, 0, 0),
('1760', -5, 0, 0, 0, 0),
('1760', -4, 0, 0, 0, 0),
('1760', -3, 0, 0, 0, 0),
('1760', -2, 0, 0, 0, 0),
('1760', -1, 0, 0, 0, 0),
('1760', 0, 0, 0, 0, 0),
('1760', 1, 0, 0, 0, 0),
('1760', 2, 0, 0, 0, 0),
('1760', 3, 0, 0, 0, 0),
('1760', 4, 0, 0, 0, 0),
('1760', 5, 0, 0, 0, 0),
('1760', 6, 0, 0, 0, 0),
('1760', 7, 0, 0, 0, 0),
('1760', 8, 0, 0, 0, 0),
('1760', 9, 0, 0, 0, 0),
('1760', 10, 0, 0, 0, 0),
('1760', 11, 0, 0, 0, 0),
('1760', 12, 0, 0, 0, 0),
('1760', 13, 0, 0, 0, 0),
('1760', 14, 0, 0, 0, 0),
('1760', 15, 0, 0, 0, 0),
('1760', 16, 0, 0, 0, 0),
('1760', 17, 0, 0, 0, 0),
('1760', 18, 0, 0, 0, 0),
('1760', 19, 0, 0, 0, 0),
('1760', 20, 0, 0, 0, 0),
('1760', 21, 0, 0, 0, 0),
('1760', 22, 0, 0, 0, 0),
('1760', 23, 0, 0, 0, 0),
('1760', 24, 0, 0, 0, 0),
('1760', 25, 0, 0, 0, 0),
('1760', 26, 0, 0, 0, 0),
('1760', 27, 0, 0, 0, 0),
('1760', 28, 0, 0, 0, 0),
('1760', 29, 0, 0, 0, 0),
('1760', 30, 0, 0, 0, 0),
('1760', 31, 0, 0, 0, 0),
('1760', 32, 0, 0, 0, 0),
('1760', 33, 0, 0, 0, 0),
('1760', 34, 0, 0, 0, 0),
('1760', 35, 0, 0, 0, 0),
('1760', 36, 0, 0, 0, 0),
('1760', 37, 0, 0, 0, 0),
('1760', 38, 0, 0, 0, 0),
('1760', 39, 0, 0, 0, 0),
('1760', 40, 0, 0, 0, 0),
('1760', 41, 0, 0, 0, 0),
('1760', 42, 0, 0, 0, 0),
('1760', 43, 0, 0, 0, 0),
('1760', 44, 0, 0, 0, 0),
('1760', 45, 0, 0, 0, 0),
('1760', 46, 0, 0, 0, 0),
('1760', 47, 0, 0, 0, 0),
('1760', 48, 0, 0, 0, 0),
('1760', 49, 0, 0, 0, 0),
('1760', 50, 0, 0, 0, 0),
('1760', 51, 0, 0, 0, 0),
('1760', 52, 0, 0, 0, 0),
('1760', 53, 0, 0, 0, 0),
('1760', 54, 0, 0, 0, 0),
('1760', 55, 0, 0, 0, 0),
('1760', 56, 0, 0, 0, 0),
('1760', 57, 0, 0, 0, 0),
('1760', 58, 0, 0, 0, 0),
('1760', 59, 0, 0, 0, 0),
('1770', -15, 0, 0, 0, 0),
('1770', -14, 0, 0, 0, 0),
('1770', -13, 0, 0, 0, 0),
('1770', -12, 0, 0, 0, 0),
('1770', -11, 0, 0, 0, 0),
('1770', -10, 0, 0, 0, 0),
('1770', -9, 0, 0, 0, 0),
('1770', -8, 0, 0, 0, 0),
('1770', -7, 0, 0, 0, 0),
('1770', -6, 0, 0, 0, 0),
('1770', -5, 0, 0, 0, 0),
('1770', -4, 0, 0, 0, 0),
('1770', -3, 0, 0, 0, 0),
('1770', -2, 0, 0, 0, 0),
('1770', -1, 0, 0, 0, 0),
('1770', 0, 0, 0, 0, 0),
('1770', 1, 0, 0, 0, 0),
('1770', 2, 0, 0, 0, 0),
('1770', 3, 0, 0, 0, 0),
('1770', 4, 0, 0, 0, 0),
('1770', 5, 0, 0, 0, 0),
('1770', 6, 0, 0, 0, 0),
('1770', 7, 0, 0, 0, 0),
('1770', 8, 0, 0, 0, 0),
('1770', 9, 0, 0, 0, 0),
('1770', 10, 0, 0, 0, 0),
('1770', 11, 0, 0, 0, 0),
('1770', 12, 0, 0, 0, 0),
('1770', 13, 0, 0, 0, 0),
('1770', 14, 0, 0, 0, 0),
('1770', 15, 0, 0, 0, 0),
('1770', 16, 0, 0, 0, 0),
('1770', 17, 0, 0, 0, 0),
('1770', 18, 0, 0, 0, 0),
('1770', 19, 0, 0, 0, 0),
('1770', 20, 0, 0, 0, 0),
('1770', 21, 0, 0, 0, 0),
('1770', 22, 0, 0, 0, 0),
('1770', 23, 0, 0, 0, 0),
('1770', 24, 0, 0, 0, 0),
('1770', 25, 0, 0, 0, 0),
('1770', 26, 0, 0, 0, 0),
('1770', 27, 0, 0, 0, 0),
('1770', 28, 0, 0, 0, 0),
('1770', 29, 0, 0, 0, 0),
('1770', 30, 0, 0, 0, 0),
('1770', 31, 0, 0, 0, 0),
('1770', 32, 0, 0, 0, 0),
('1770', 33, 0, 0, 0, 0),
('1770', 34, 0, 0, 0, 0),
('1770', 35, 0, 0, 0, 0),
('1770', 36, 0, 0, 0, 0),
('1770', 37, 0, 0, 0, 0),
('1770', 38, 0, 0, 0, 0),
('1770', 39, 0, 0, 0, 0),
('1770', 40, 0, 0, 0, 0),
('1770', 41, 0, 0, 0, 0),
('1770', 42, 0, 0, 0, 0),
('1770', 43, 0, 0, 0, 0),
('1770', 44, 0, 0, 0, 0),
('1770', 45, 0, 0, 0, 0),
('1770', 46, 0, 0, 0, 0),
('1770', 47, 0, 0, 0, 0),
('1770', 48, 0, 0, 0, 0),
('1770', 49, 0, 0, 0, 0),
('1770', 50, 0, 0, 0, 0),
('1770', 51, 0, 0, 0, 0),
('1770', 52, 0, 0, 0, 0),
('1770', 53, 0, 0, 0, 0),
('1770', 54, 0, 0, 0, 0),
('1770', 55, 0, 0, 0, 0),
('1770', 56, 0, 0, 0, 0),
('1770', 57, 0, 0, 0, 0),
('1770', 58, 0, 0, 0, 0),
('1770', 59, 0, 0, 0, 0),
('1780', -15, 0, 0, 0, 0),
('1780', -14, 0, 0, 0, 0),
('1780', -13, 0, 0, 0, 0),
('1780', -12, 0, 0, 0, 0),
('1780', -11, 0, 0, 0, 0),
('1780', -10, 0, 0, 0, 0),
('1780', -9, 0, 0, 0, 0),
('1780', -8, 0, 0, 0, 0),
('1780', -7, 0, 0, 0, 0),
('1780', -6, 0, 0, 0, 0),
('1780', -5, 0, 0, 0, 0),
('1780', -4, 0, 0, 0, 0),
('1780', -3, 0, 0, 0, 0),
('1780', -2, 0, 0, 0, 0),
('1780', -1, 0, 0, 0, 0),
('1780', 0, 0, 0, 0, 0),
('1780', 1, 0, 0, 0, 0),
('1780', 2, 0, 0, 0, 0),
('1780', 3, 0, 0, 0, 0),
('1780', 4, 0, 0, 0, 0),
('1780', 5, 0, 0, 0, 0),
('1780', 6, 0, 0, 0, 0),
('1780', 7, 0, 0, 0, 0),
('1780', 8, 0, 0, 0, 0),
('1780', 9, 0, 0, 0, 0),
('1780', 10, 0, 0, 0, 0),
('1780', 11, 0, 0, 0, 0),
('1780', 12, 0, 0, 0, 0),
('1780', 13, 0, 0, 0, 0),
('1780', 14, 0, 0, 0, 0),
('1780', 15, 0, 0, 0, 0),
('1780', 16, 0, 0, 0, 0),
('1780', 17, 0, 0, 0, 0),
('1780', 18, 0, 0, 0, 0),
('1780', 19, 0, 0, 0, 0),
('1780', 20, 0, 0, 0, 0),
('1780', 21, 0, 0, 0, 0),
('1780', 22, 0, 0, 0, 0),
('1780', 23, 0, 0, 0, 0),
('1780', 24, 0, 0, 0, 0),
('1780', 25, 0, 0, 0, 0),
('1780', 26, 0, 0, 0, 0),
('1780', 27, 0, 0, 0, 0),
('1780', 28, 0, 0, 0, 0),
('1780', 29, 0, 0, 0, 0),
('1780', 30, 0, 0, 0, 0),
('1780', 31, 0, 0, 0, 0),
('1780', 32, 0, 0, 0, 0),
('1780', 33, 0, 0, 0, 0),
('1780', 34, 0, 0, 0, 0),
('1780', 35, 0, 0, 0, 0),
('1780', 36, 0, 0, 0, 0),
('1780', 37, 0, 0, 0, 0),
('1780', 38, 0, 0, 0, 0),
('1780', 39, 0, 0, 0, 0),
('1780', 40, 0, 0, 0, 0),
('1780', 41, 0, 0, 0, 0),
('1780', 42, 0, 0, 0, 0),
('1780', 43, 0, 0, 0, 0),
('1780', 44, 0, 0, 0, 0),
('1780', 45, 0, 0, 0, 0),
('1780', 46, 0, 0, 0, 0),
('1780', 47, 0, 0, 0, 0),
('1780', 48, 0, 0, 0, 0),
('1780', 49, 0, 0, 0, 0),
('1780', 50, 0, 0, 0, 0),
('1780', 51, 0, 0, 0, 0),
('1780', 52, 0, 0, 0, 0),
('1780', 53, 0, 0, 0, 0),
('1780', 54, 0, 0, 0, 0),
('1780', 55, 0, 0, 0, 0),
('1780', 56, 0, 0, 0, 0),
('1780', 57, 0, 0, 0, 0),
('1780', 58, 0, 0, 0, 0),
('1780', 59, 0, 0, 0, 0),
('1790', -15, 0, 0, 0, 0),
('1790', -14, 0, 0, 0, 0),
('1790', -13, 0, 0, 0, 0),
('1790', -12, 0, 0, 0, 0),
('1790', -11, 0, 0, 0, 0),
('1790', -10, 0, 0, 0, 0),
('1790', -9, 0, 0, 0, 0),
('1790', -8, 0, 0, 0, 0),
('1790', -7, 0, 0, 0, 0),
('1790', -6, 0, 0, 0, 0),
('1790', -5, 0, 0, 0, 0),
('1790', -4, 0, 0, 0, 0),
('1790', -3, 0, 0, 0, 0),
('1790', -2, 0, 0, 0, 0),
('1790', -1, 0, 0, 0, 0),
('1790', 0, 0, 0, 0, 0),
('1790', 1, 0, 0, 0, 0),
('1790', 2, 0, 0, 0, 0),
('1790', 3, 0, 0, 0, 0),
('1790', 4, 0, 0, 0, 0),
('1790', 5, 0, 0, 0, 0),
('1790', 6, 0, 0, 0, 0),
('1790', 7, 0, 0, 0, 0),
('1790', 8, 0, 0, 0, 0),
('1790', 9, 0, 0, 0, 0),
('1790', 10, 0, 0, 0, 0),
('1790', 11, 0, 0, 0, 0),
('1790', 12, 0, 0, 0, 0),
('1790', 13, 0, 0, 0, 0),
('1790', 14, 0, 0, 0, 0),
('1790', 15, 0, 0, 0, 0),
('1790', 16, 0, 0, 0, 0),
('1790', 17, 0, 0, 0, 0),
('1790', 18, 0, 0, 0, 0),
('1790', 19, 0, 0, 0, 0),
('1790', 20, 0, 0, 0, 0),
('1790', 21, 0, 0, 0, 0),
('1790', 22, 0, 0, 0, 0),
('1790', 23, 0, 0, 0, 0),
('1790', 24, 0, 0, 0, 0),
('1790', 25, 0, 0, 0, 0),
('1790', 26, 0, 0, 0, 0),
('1790', 27, 0, 0, 0, 0),
('1790', 28, 0, 0, 0, 0),
('1790', 29, 0, 0, 0, 0),
('1790', 30, 0, 0, 0, 0),
('1790', 31, 0, 0, 0, 0),
('1790', 32, 0, 0, 0, 0),
('1790', 33, 0, 0, 0, 0),
('1790', 34, 0, 0, 0, 0),
('1790', 35, 0, 0, 0, 0),
('1790', 36, 0, 0, 0, 0),
('1790', 37, 0, 0, 0, 0),
('1790', 38, 0, 0, 0, 0),
('1790', 39, 0, 0, 0, 0),
('1790', 40, 0, 0, 0, 0),
('1790', 41, 0, 0, 0, 0),
('1790', 42, 0, 0, 0, 0),
('1790', 43, 0, 0, 0, 0),
('1790', 44, 0, 0, 0, 0),
('1790', 45, 0, 0, 0, 0),
('1790', 46, 0, 0, 0, 0),
('1790', 47, 0, 0, 0, 0),
('1790', 48, 0, 0, 0, 0),
('1790', 49, 0, 0, 0, 0),
('1790', 50, 0, 0, 0, 0),
('1790', 51, 0, 0, 0, 0),
('1790', 52, 0, 0, 0, 0),
('1790', 53, 0, 0, 0, 0),
('1790', 54, 0, 0, 0, 0),
('1790', 55, 0, 0, 0, 0),
('1790', 56, 0, 0, 0, 0),
('1790', 57, 0, 0, 0, 0),
('1790', 58, 0, 0, 0, 0),
('1790', 59, 0, 0, 0, 0),
('1800', -15, 0, 0, 0, 0),
('1800', -14, 0, 0, 0, 0),
('1800', -13, 0, 0, 0, 0),
('1800', -12, 0, 0, 0, 0),
('1800', -11, 0, 0, 0, 0),
('1800', -10, 0, 0, 0, 0),
('1800', -9, 0, 0, 0, 0),
('1800', -8, 0, 0, 0, 0),
('1800', -7, 0, 0, 0, 0),
('1800', -6, 0, 0, 0, 0),
('1800', -5, 0, 0, 0, 0),
('1800', -4, 0, 0, 0, 0),
('1800', -3, 0, 0, 0, 0),
('1800', -2, 0, 0, 0, 0),
('1800', -1, 0, 0, 0, 0),
('1800', 0, 0, 0, 0, 0),
('1800', 1, 0, 0, 0, 0),
('1800', 2, 0, 0, 0, 0),
('1800', 3, 0, 0, 0, 0),
('1800', 4, 0, 0, 0, 0),
('1800', 5, 0, 0, 0, 0),
('1800', 6, 0, 0, 0, 0),
('1800', 7, 0, 0, 0, 0),
('1800', 8, 0, 0, 0, 0),
('1800', 9, 0, 0, 0, 0),
('1800', 10, 0, 0, 0, 0),
('1800', 11, 0, 0, 0, 0),
('1800', 12, 0, 0, 0, 0),
('1800', 13, 0, 0, 0, 0),
('1800', 14, 0, 0, 0, 0),
('1800', 15, 0, 0, 0, 0),
('1800', 16, 0, 0, 0, 0),
('1800', 17, 0, 0, 0, 0),
('1800', 18, 0, 0, 0, 0),
('1800', 19, 0, 0, 0, 0),
('1800', 20, 0, 0, 0, 0),
('1800', 21, 0, 0, 0, 0),
('1800', 22, 0, 0, 0, 0),
('1800', 23, 0, 0, 0, 0),
('1800', 24, 0, 0, 0, 0),
('1800', 25, 0, 0, 0, 0),
('1800', 26, 0, 0, 0, 0),
('1800', 27, 0, 0, 0, 0),
('1800', 28, 0, 0, 0, 0),
('1800', 29, 0, 0, 0, 0),
('1800', 30, 0, 0, 0, 0),
('1800', 31, 0, 0, 0, 0),
('1800', 32, 0, 0, 0, 0),
('1800', 33, 0, 0, 0, 0),
('1800', 34, 0, 0, 0, 0),
('1800', 35, 0, 0, 0, 0),
('1800', 36, 0, 0, 0, 0),
('1800', 37, 0, 0, 0, 0),
('1800', 38, 0, 0, 0, 0),
('1800', 39, 0, 0, 0, 0),
('1800', 40, 0, 0, 0, 0),
('1800', 41, 0, 0, 0, 0),
('1800', 42, 0, 0, 0, 0),
('1800', 43, 0, 0, 0, 0),
('1800', 44, 0, 0, 0, 0),
('1800', 45, 0, 0, 0, 0),
('1800', 46, 0, 0, 0, 0),
('1800', 47, 0, 0, 0, 0),
('1800', 48, 0, 0, 0, 0),
('1800', 49, 0, 0, 0, 0),
('1800', 50, 0, 0, 0, 0),
('1800', 51, 0, 0, 0, 0),
('1800', 52, 0, 0, 0, 0),
('1800', 53, 0, 0, 0, 0),
('1800', 54, 0, 0, 0, 0),
('1800', 55, 0, 0, 0, 0),
('1800', 56, 0, 0, 0, 0),
('1800', 57, 0, 0, 0, 0),
('1800', 58, 0, 0, 0, 0),
('1800', 59, 0, 0, 0, 0),
('1850', -15, 0, 0, 0, 0),
('1850', -14, 0, 0, 0, 0),
('1850', -13, 0, 0, 0, 0),
('1850', -12, 0, 0, 0, 0),
('1850', -11, 0, 0, 0, 0),
('1850', -10, 0, 0, 0, 0),
('1850', -9, 0, 0, 0, 0),
('1850', -8, 0, 0, 0, 0),
('1850', -7, 0, 0, 0, 0),
('1850', -6, 0, 0, 0, 0),
('1850', -5, 0, 0, 0, 0),
('1850', -4, 0, 0, 0, 0),
('1850', -3, 0, 0, 0, 0),
('1850', -2, 0, 0, 0, 0),
('1850', -1, 0, 0, 0, 0),
('1850', 0, 0, 0, 0, 0),
('1850', 1, 0, 0, 0, 0),
('1850', 2, 0, 0, 0, 0),
('1850', 3, 0, 0, 0, 0),
('1850', 4, 0, 0, 0, 0),
('1850', 5, 0, 0, 0, 0),
('1850', 6, 0, 0, 0, 0),
('1850', 7, 0, 0, 0, 0),
('1850', 8, 0, 0, 0, 0),
('1850', 9, 0, 0, 0, 0),
('1850', 10, 0, 0, 0, 0),
('1850', 11, 0, 0, 0, 0),
('1850', 12, 0, 0, 0, 0),
('1850', 13, 0, 0, 0, 0),
('1850', 14, 0, 0, 0, 0),
('1850', 15, 0, 0, 0, 0),
('1850', 16, 0, 0, 0, 0),
('1850', 17, 0, 0, 0, 0),
('1850', 18, 0, 0, 0, 0),
('1850', 19, 0, 0, 0, 0),
('1850', 20, 0, 0, 0, 0),
('1850', 21, 0, 0, 0, 0),
('1850', 22, 0, 0, 0, 0),
('1850', 23, 0, 0, 0, 0),
('1850', 24, 0, 0, 0, 0),
('1850', 25, 0, 0, 0, 0),
('1850', 26, 0, 0, 0, 0),
('1850', 27, 0, 0, 0, 0),
('1850', 28, 0, 0, 0, 0),
('1850', 29, 0, 0, 0, 0),
('1850', 30, 0, 0, 0, 0),
('1850', 31, 0, 0, 0, 0),
('1850', 32, 0, 0, 0, 0),
('1850', 33, 0, 0, 0, 0),
('1850', 34, 0, 0, 0, 0),
('1850', 35, 0, 0, 0, 0),
('1850', 36, 0, 0, 0, 0),
('1850', 37, 0, 0, 0, 0),
('1850', 38, 0, 0, 0, 0),
('1850', 39, 0, 0, 0, 0),
('1850', 40, 0, 0, 0, 0),
('1850', 41, 0, 0, 0, 0),
('1850', 42, 0, 0, 0, 0),
('1850', 43, 0, 0, 0, 0),
('1850', 44, 0, 0, 0, 0),
('1850', 45, 0, 0, 0, 0),
('1850', 46, 0, 0, 0, 0),
('1850', 47, 0, 0, 0, 0),
('1850', 48, 0, 0, 0, 0),
('1850', 49, 0, 0, 0, 0),
('1850', 50, 0, 0, 0, 0),
('1850', 51, 0, 0, 0, 0),
('1850', 52, 0, 0, 0, 0),
('1850', 53, 0, 0, 0, 0),
('1850', 54, 0, 0, 0, 0),
('1850', 55, 0, 0, 0, 0),
('1850', 56, 0, 0, 0, 0),
('1850', 57, 0, 0, 0, 0),
('1850', 58, 0, 0, 0, 0),
('1850', 59, 0, 0, 0, 0),
('1900', -15, 0, 0, 0, 0),
('1900', -14, 0, 0, 0, 0),
('1900', -13, 0, 0, 0, 0),
('1900', -12, 0, 0, 0, 0),
('1900', -11, 0, 0, 0, 0),
('1900', -10, 0, 0, 0, 0),
('1900', -9, 0, 0, 0, 0),
('1900', -8, 0, 0, 0, 0),
('1900', -7, 0, 0, 0, 0),
('1900', -6, 0, 0, 0, 0),
('1900', -5, 0, 0, 0, 0),
('1900', -4, 0, 0, 0, 0),
('1900', -3, 0, 0, 0, 0),
('1900', -2, 0, 0, 0, 0),
('1900', -1, 0, 0, 0, 0),
('1900', 0, 0, 0, 0, 0),
('1900', 1, 0, 0, 0, 0),
('1900', 2, 0, 0, 0, 0),
('1900', 3, 0, 0, 0, 0),
('1900', 4, 0, 0, 0, 0),
('1900', 5, 0, 0, 0, 0),
('1900', 6, 0, 0, 0, 0),
('1900', 7, 0, 0, 0, 0),
('1900', 8, 0, 0, 0, 0),
('1900', 9, 0, 0, 0, 0),
('1900', 10, 0, 0, 0, 0),
('1900', 11, 0, 0, 0, 0),
('1900', 12, 0, 0, 0, 0),
('1900', 13, 0, 0, 0, 0),
('1900', 14, 0, 0, 0, 0),
('1900', 15, 0, 0, 0, 0),
('1900', 16, 0, 0, 0, 0),
('1900', 17, 0, 0, 0, 0),
('1900', 18, 0, 0, 0, 0),
('1900', 19, 0, 0, 0, 0),
('1900', 20, 0, 0, 0, 0),
('1900', 21, 0, 0, 0, 0),
('1900', 22, 0, 0, 0, 0),
('1900', 23, 0, 0, 0, 0),
('1900', 24, 0, 0, 0, 0),
('1900', 25, 0, 0, 0, 0),
('1900', 26, 0, 0, 0, 0),
('1900', 27, 0, 0, 0, 0),
('1900', 28, 0, 0, 0, 0),
('1900', 29, 0, 0, 0, 0),
('1900', 30, 0, 0, 0, 0),
('1900', 31, 0, 0, 0, 0),
('1900', 32, 0, 0, 0, 0),
('1900', 33, 0, 0, 0, 0),
('1900', 34, 0, 0, 0, 0),
('1900', 35, 0, 0, 0, 0),
('1900', 36, 0, 0, 0, 0),
('1900', 37, 0, 0, 0, 0),
('1900', 38, 0, 0, 0, 0),
('1900', 39, 0, 0, 0, 0),
('1900', 40, 0, 0, 0, 0),
('1900', 41, 0, 0, 0, 0),
('1900', 42, 0, 0, 0, 0),
('1900', 43, 0, 0, 0, 0),
('1900', 44, 0, 0, 0, 0),
('1900', 45, 0, 0, 0, 0),
('1900', 46, 0, 0, 0, 0),
('1900', 47, 0, 0, 0, 0),
('1900', 48, 0, 0, 0, 0),
('1900', 49, 0, 0, 0, 0),
('1900', 50, 0, 0, 0, 0),
('1900', 51, 0, 0, 0, 0),
('1900', 52, 0, 0, 0, 0),
('1900', 53, 0, 0, 0, 0),
('1900', 54, 0, 0, 0, 0),
('1900', 55, 0, 0, 0, 0),
('1900', 56, 0, 0, 0, 0),
('1900', 57, 0, 0, 0, 0),
('1900', 58, 0, 0, 0, 0),
('1900', 59, 0, 0, 0, 0),
('2010', -15, 0, 0, 0, 0),
('2010', -14, 0, 0, 0, 0),
('2010', -13, 0, 0, 0, 0),
('2010', -12, 0, 0, 0, 0),
('2010', -11, 0, 0, 0, 0),
('2010', -10, 0, 0, 0, 0),
('2010', -9, 0, 0, 0, 0),
('2010', -8, 0, 0, 0, 0),
('2010', -7, 0, 0, 0, 0),
('2010', -6, 0, 0, 0, 0),
('2010', -5, 0, 0, 0, 0),
('2010', -4, 0, 0, 0, 0),
('2010', -3, 0, 0, 0, 0),
('2010', -2, 0, 0, 0, 0),
('2010', -1, 0, 0, 0, 0),
('2010', 0, 0, 0, 0, 0),
('2010', 1, 0, 0, 0, 0),
('2010', 2, 0, 0, 0, 0),
('2010', 3, 0, 0, 0, 0),
('2010', 4, 0, 0, 0, 0),
('2010', 5, 0, 0, 0, 0),
('2010', 6, 0, 0, 0, 0),
('2010', 7, 0, 0, 0, 0),
('2010', 8, 0, 0, 0, 0),
('2010', 9, 0, 0, 0, 0),
('2010', 10, 0, 0, 0, 0),
('2010', 11, 0, 0, 0, 0),
('2010', 12, 0, 0, 0, 0),
('2010', 13, 0, 0, 0, 0),
('2010', 14, 0, 0, 0, 0),
('2010', 15, 0, 0, 0, 0),
('2010', 16, 0, 0, 0, 0),
('2010', 17, 0, 0, 0, 0),
('2010', 18, 0, 0, 0, 0),
('2010', 19, 0, 0, 0, 0),
('2010', 20, 0, 0, 0, 0),
('2010', 21, 0, 0, 0, 0),
('2010', 22, 0, 0, 0, 0),
('2010', 23, 0, 0, 0, 0),
('2010', 24, 0, 0, 0, 0),
('2010', 25, 0, 0, 0, 0),
('2010', 26, 0, 0, 0, 0),
('2010', 27, 0, 0, 0, 0),
('2010', 28, 0, 0, 0, 0),
('2010', 29, 0, 0, 0, 0),
('2010', 30, 0, 0, 0, 0),
('2010', 31, 0, 0, 0, 0),
('2010', 32, 0, 0, 0, 0),
('2010', 33, 0, 0, 0, 0),
('2010', 34, 0, 0, 0, 0),
('2010', 35, 0, 0, 0, 0),
('2010', 36, 0, 0, 0, 0),
('2010', 37, 0, 0, 0, 0),
('2010', 38, 0, 0, 0, 0),
('2010', 39, 0, 0, 0, 0),
('2010', 40, 0, 0, 0, 0),
('2010', 41, 0, 0, 0, 0),
('2010', 42, 0, 0, 0, 0),
('2010', 43, 0, 0, 0, 0),
('2010', 44, 0, 0, 0, 0),
('2010', 45, 0, 0, 0, 0),
('2010', 46, 0, 0, 0, 0),
('2010', 47, 0, 0, 0, 0),
('2010', 48, 0, 0, 0, 0),
('2010', 49, 0, 0, 0, 0),
('2010', 50, 0, 0, 0, 0),
('2010', 51, 0, 0, 0, 0),
('2010', 52, 0, 0, 0, 0),
('2010', 53, 0, 0, 0, 0),
('2010', 54, 0, 0, 0, 0),
('2010', 55, 0, 0, 0, 0),
('2010', 56, 0, 0, 0, 0),
('2010', 57, 0, 0, 0, 0),
('2010', 58, 0, 0, 0, 0),
('2010', 59, 0, 0, 0, 0),
('2020', -15, 0, 0, 0, 0),
('2020', -14, 0, 0, 0, 0),
('2020', -13, 0, 0, 0, 0),
('2020', -12, 0, 0, 0, 0),
('2020', -11, 0, 0, 0, 0),
('2020', -10, 0, 0, 0, 0),
('2020', -9, 0, 0, 0, 0),
('2020', -8, 0, 0, 0, 0),
('2020', -7, 0, 0, 0, 0),
('2020', -6, 0, 0, 0, 0),
('2020', -5, 0, 0, 0, 0),
('2020', -4, 0, 0, 0, 0),
('2020', -3, 0, 0, 0, 0),
('2020', -2, 0, 0, 0, 0),
('2020', -1, 0, 0, 0, 0),
('2020', 0, 0, 0, 0, 0),
('2020', 1, 0, 0, 0, 0),
('2020', 2, 0, 0, 0, 0),
('2020', 3, 0, 0, 0, 0),
('2020', 4, 0, 0, 0, 0),
('2020', 5, 0, 0, 0, 0),
('2020', 6, 0, 0, 0, 0),
('2020', 7, 0, 0, 0, 0),
('2020', 8, 0, 0, 0, 0),
('2020', 9, 0, 0, 0, 0),
('2020', 10, 0, 0, 0, 0),
('2020', 11, 0, 0, 0, 0),
('2020', 12, 0, 0, 0, 0),
('2020', 13, 0, 0, 0, 0),
('2020', 14, 0, 0, 0, 0),
('2020', 15, 0, 0, 0, 0),
('2020', 16, 0, 0, 0, 0),
('2020', 17, 0, 0, 0, 0),
('2020', 18, 0, 0, 0, 0),
('2020', 19, 0, 0, 0, 0),
('2020', 20, 0, 0, 0, 0),
('2020', 21, 0, 0, 0, 0),
('2020', 22, 0, 0, 0, 0),
('2020', 23, 0, 0, 0, 0),
('2020', 24, 0, 0, 0, 0),
('2020', 25, 0, 0, 0, 0),
('2020', 26, 0, 0, 0, 0),
('2020', 27, 0, 0, 0, 0),
('2020', 28, 0, 0, 0, 0),
('2020', 29, 0, 0, 0, 0),
('2020', 30, 0, 0, 0, 0),
('2020', 31, 0, 0, 0, 0),
('2020', 32, 0, 0, 0, 0),
('2020', 33, 0, 0, 0, 0),
('2020', 34, 0, 0, 0, 0),
('2020', 35, 0, 0, 0, 0),
('2020', 36, 0, 0, 0, 0),
('2020', 37, 0, 0, 0, 0),
('2020', 38, 0, 0, 0, 0),
('2020', 39, 0, 0, 0, 0),
('2020', 40, 0, 0, 0, 0),
('2020', 41, 0, 0, 0, 0),
('2020', 42, 0, 0, 0, 0),
('2020', 43, 0, 0, 0, 0),
('2020', 44, 0, 0, 0, 0),
('2020', 45, 0, 0, 0, 0),
('2020', 46, 0, 0, 0, 0),
('2020', 47, 0, 0, 0, 0),
('2020', 48, 0, 0, 0, 0),
('2020', 49, 0, 0, 0, 0),
('2020', 50, 0, 0, 0, 0),
('2020', 51, 0, 0, 0, 0),
('2020', 52, 0, 0, 0, 0),
('2020', 53, 0, 0, 0, 0),
('2020', 54, 0, 0, 0, 0),
('2020', 55, 0, 0, 0, 0),
('2020', 56, 0, 0, 0, 0),
('2020', 57, 0, 0, 0, 0),
('2020', 58, 0, 0, 0, 0),
('2020', 59, 0, 0, 0, 0),
('2050', -15, 0, 0, 0, 0),
('2050', -14, 0, 0, 0, 0),
('2050', -13, 0, 0, 0, 0),
('2050', -12, 0, 0, 0, 0),
('2050', -11, 0, 0, 0, 0),
('2050', -10, 0, 0, 0, 0),
('2050', -9, 0, 0, 0, 0),
('2050', -8, 0, 0, 0, 0),
('2050', -7, 0, 0, 0, 0),
('2050', -6, 0, 0, 0, 0),
('2050', -5, 0, 0, 0, 0),
('2050', -4, 0, 0, 0, 0),
('2050', -3, 0, 0, 0, 0),
('2050', -2, 0, 0, 0, 0),
('2050', -1, 0, 0, 0, 0),
('2050', 0, 0, 0, 0, 0),
('2050', 1, 0, 0, 0, 0),
('2050', 2, 0, 0, 0, 0),
('2050', 3, 0, 0, 0, 0),
('2050', 4, 0, 0, 0, 0),
('2050', 5, 0, 0, 0, 0),
('2050', 6, 0, 0, 0, 0),
('2050', 7, 0, 0, 0, 0),
('2050', 8, 0, 0, 0, 0),
('2050', 9, 0, 0, 0, 0),
('2050', 10, 0, 0, 0, 0),
('2050', 11, 0, 0, 0, 0),
('2050', 12, 0, 0, 0, 0),
('2050', 13, 0, 0, 0, 0),
('2050', 14, 0, 0, 0, 0),
('2050', 15, 0, 0, 0, 0),
('2050', 16, 0, 0, 0, 0),
('2050', 17, 0, 0, 0, 0),
('2050', 18, 0, 0, 0, 0),
('2050', 19, 0, 0, 0, 0),
('2050', 20, 0, 0, 0, 0),
('2050', 21, 0, 0, 0, 0),
('2050', 22, 0, 0, 0, 0),
('2050', 23, 0, 0, 0, 0),
('2050', 24, 0, 0, 0, 0),
('2050', 25, 0, 0, 0, 0),
('2050', 26, 0, 0, 0, 0),
('2050', 27, 0, 0, 0, 0),
('2050', 28, 0, 0, 0, 0),
('2050', 29, 0, 0, 0, 0),
('2050', 30, 0, 0, 0, 0),
('2050', 31, 0, 0, 0, 0),
('2050', 32, 0, 0, 0, 0),
('2050', 33, 0, 0, 0, 0),
('2050', 34, 0, 0, 0, 0),
('2050', 35, 0, 0, 0, 0),
('2050', 36, 0, 0, 0, 0),
('2050', 37, 0, 0, 0, 0),
('2050', 38, 0, 0, 0, 0),
('2050', 39, 0, 0, 0, 0),
('2050', 40, 0, 0, 0, 0),
('2050', 41, 0, 0, 0, 0),
('2050', 42, 0, 0, 0, 0),
('2050', 43, 0, 0, 0, 0),
('2050', 44, 0, 0, 0, 0),
('2050', 45, 0, 0, 0, 0),
('2050', 46, 0, 0, 0, 0),
('2050', 47, 0, 0, 0, 0),
('2050', 48, 0, 0, 0, 0),
('2050', 49, 0, 0, 0, 0),
('2050', 50, 0, 0, 0, 0),
('2050', 51, 0, 0, 0, 0),
('2050', 52, 0, 0, 0, 0),
('2050', 53, 0, 0, 0, 0),
('2050', 54, 0, 0, 0, 0),
('2050', 55, 0, 0, 0, 0),
('2050', 56, 0, 0, 0, 0),
('2050', 57, 0, 0, 0, 0),
('2050', 58, 0, 0, 0, 0),
('2050', 59, 0, 0, 0, 0),
('2100', -15, 0, 0, 0, 0),
('2100', -14, 0, 0, 0, 0),
('2100', -13, 0, 0, 0, 0),
('2100', -12, 0, 0, 0, 0),
('2100', -11, 0, 0, 0, 0),
('2100', -10, 0, 0, 0, 0),
('2100', -9, 0, 0, 0, 0),
('2100', -8, 0, 0, 0, 0),
('2100', -7, 0, 0, 0, 0),
('2100', -6, 0, 0, 0, 0),
('2100', -5, 0, 0, 0, 0),
('2100', -4, 0, 0, 0, 0),
('2100', -3, 0, 0, 0, 0),
('2100', -2, 0, 0, 0, 0),
('2100', -1, 0, 0, 0, 0),
('2100', 0, 0, 0, 0, 0),
('2100', 1, 0, 0, 0, 0),
('2100', 2, 0, 0, 0, 0),
('2100', 3, 0, 0, 0, 0),
('2100', 4, 0, 0, 0, 0),
('2100', 5, 0, 0, 0, 0),
('2100', 6, 0, 0, 0, 0),
('2100', 7, 0, 0, 0, 0),
('2100', 8, 0, 0, 0, 0),
('2100', 9, 0, 0, 0, 0),
('2100', 10, 0, 0, 0, 0),
('2100', 11, 0, 0, 0, 0),
('2100', 12, 0, 0, 0, 0),
('2100', 13, 0, 0, 0, 0),
('2100', 14, 0, 0, 0, 0),
('2100', 15, 0, 0, 0, 0),
('2100', 16, 0, 0, 0, 0),
('2100', 17, 0, 0, 0, 0),
('2100', 18, 0, 0, 0, 0),
('2100', 19, 0, 0, 0, 0),
('2100', 20, 0, 0, 0, 0),
('2100', 21, 0, 0, 0, 0),
('2100', 22, 0, 0, 0, 0),
('2100', 23, 0, 0, 0, 0),
('2100', 24, 0, 0, 0, 0),
('2100', 25, 0, 0, 0, 0),
('2100', 26, 0, 0, 0, 0),
('2100', 27, 0, 0, 0, 0),
('2100', 28, 0, 0, 0, 0),
('2100', 29, 0, 0, 0, 0),
('2100', 30, 0, 0, 0, 0),
('2100', 31, 0, 0, 0, 0),
('2100', 32, 0, 0, 0, 0),
('2100', 33, 0, 0, 0, 0),
('2100', 34, 0, 0, 0, 0),
('2100', 35, 0, 0, 0, 0),
('2100', 36, 0, 0, 0, 0),
('2100', 37, 0, 0, 0, 0),
('2100', 38, 0, 0, 0, 0),
('2100', 39, 0, 0, 0, 0),
('2100', 40, 0, 0, 0, 0),
('2100', 41, 0, 0, 0, 0),
('2100', 42, 0, 0, 0, 0),
('2100', 43, 0, 0, 0, 0),
('2100', 44, 0, 0, 0, 0),
('2100', 45, 0, 0, 0, 0),
('2100', 46, 0, 0, 0, 0),
('2100', 47, 0, 0, 0, 0),
('2100', 48, 0, 0, 0, 0),
('2100', 49, 0, 0, 0, 0),
('2100', 50, 0, 0, 0, 0),
('2100', 51, 0, 0, 0, 0),
('2100', 52, 0, 0, 0, 0),
('2100', 53, 0, 0, 0, 0),
('2100', 54, 0, 0, 0, 0),
('2100', 55, 0, 0, 0, 0),
('2100', 56, 0, 0, 0, 0),
('2100', 57, 0, 0, 0, 0),
('2100', 58, 0, 0, 0, 0),
('2100', 59, 0, 0, 0, 0),
('2150', -15, 0, 0, 0, 0),
('2150', -14, 0, 0, 0, 0),
('2150', -13, 0, 0, 0, 0),
('2150', -12, 0, 0, 0, 0),
('2150', -11, 0, 0, 0, 0),
('2150', -10, 0, 0, 0, 0),
('2150', -9, 0, 0, 0, 0),
('2150', -8, 0, 0, 0, 0),
('2150', -7, 0, 0, 0, 0),
('2150', -6, 0, 0, 0, 0),
('2150', -5, 0, 0, 0, 0),
('2150', -4, 0, 0, 0, 0),
('2150', -3, 0, 0, 0, 0),
('2150', -2, 0, 0, 0, 0),
('2150', -1, 0, 0, 0, 0),
('2150', 0, 0, 0, 0, 0),
('2150', 1, 0, 0, 0, 0),
('2150', 2, 0, 0, 0, 0),
('2150', 3, 0, 0, 0, 0),
('2150', 4, 0, 0, 0, 0),
('2150', 5, 0, 0, 0, 0),
('2150', 6, 0, 0, 0, 0),
('2150', 7, 0, 0, 0, 0),
('2150', 8, 0, 0, 0, 0),
('2150', 9, 0, 0, 0, 0),
('2150', 10, 0, 0, 0, 0),
('2150', 11, 0, 0, 0, 0),
('2150', 12, 0, 0, 0, 0),
('2150', 13, 0, 0, 0, 0),
('2150', 14, 0, 0, 0, 0),
('2150', 15, 0, 0, 0, 0),
('2150', 16, 0, 0, 0, 0),
('2150', 17, 0, 0, 0, 0),
('2150', 18, 0, 0, 0, 0),
('2150', 19, 0, 0, 0, 0),
('2150', 20, 0, 0, 0, 0),
('2150', 21, 0, 0, 0, 0),
('2150', 22, 0, 0, 0, 0),
('2150', 23, 0, 0, 0, 0),
('2150', 24, 0, 0, 0, 0),
('2150', 25, 0, 0, 0, 0),
('2150', 26, 0, 0, 0, 0),
('2150', 27, 0, 0, 0, 0),
('2150', 28, 0, 0, 0, 0),
('2150', 29, 0, 0, 0, 0),
('2150', 30, 0, 0, 0, 0),
('2150', 31, 0, 0, 0, 0),
('2150', 32, 0, 0, 0, 0),
('2150', 33, 0, 0, 0, 0),
('2150', 34, 0, 0, 0, 0),
('2150', 35, 0, 0, 0, 0),
('2150', 36, 0, 0, 0, 0),
('2150', 37, 0, 0, 0, 0),
('2150', 38, 0, 0, 0, 0),
('2150', 39, 0, 0, 0, 0),
('2150', 40, 0, 0, 0, 0),
('2150', 41, 0, 0, 0, 0),
('2150', 42, 0, 0, 0, 0),
('2150', 43, 0, 0, 0, 0),
('2150', 44, 0, 0, 0, 0),
('2150', 45, 0, 0, 0, 0),
('2150', 46, 0, 0, 0, 0),
('2150', 47, 0, 0, 0, 0),
('2150', 48, 0, 0, 0, 0),
('2150', 49, 0, 0, 0, 0),
('2150', 50, 0, 0, 0, 0),
('2150', 51, 0, 0, 0, 0),
('2150', 52, 0, 0, 0, 0),
('2150', 53, 0, 0, 0, 0),
('2150', 54, 0, 0, 0, 0),
('2150', 55, 0, 0, 0, 0),
('2150', 56, 0, 0, 0, 0),
('2150', 57, 0, 0, 0, 0),
('2150', 58, 0, 0, 0, 0),
('2150', 59, 0, 0, 0, 0),
('2200', -15, 0, 0, 0, 0),
('2200', -14, 0, 0, 0, 0),
('2200', -13, 0, 0, 0, 0),
('2200', -12, 0, 0, 0, 0),
('2200', -11, 0, 0, 0, 0),
('2200', -10, 0, 0, 0, 0),
('2200', -9, 0, 0, 0, 0),
('2200', -8, 0, 0, 0, 0),
('2200', -7, 0, 0, 0, 0),
('2200', -6, 0, 0, 0, 0),
('2200', -5, 0, 0, 0, 0),
('2200', -4, 0, 0, 0, 0),
('2200', -3, 0, 0, 0, 0),
('2200', -2, 0, 0, 0, 0),
('2200', -1, 0, 0, 0, 0),
('2200', 0, 0, 0, 0, 0),
('2200', 1, 0, 0, 0, 0),
('2200', 2, 0, 0, 0, 0),
('2200', 3, 0, 0, 0, 0),
('2200', 4, 0, 0, 0, 0),
('2200', 5, 0, 0, 0, 0),
('2200', 6, 0, 0, 0, 0),
('2200', 7, 0, 0, 0, 0),
('2200', 8, 0, 0, 0, 0),
('2200', 9, 0, 0, 0, 0),
('2200', 10, 0, 0, 0, 0),
('2200', 11, 0, 0, 0, 0),
('2200', 12, 0, 0, 0, 0),
('2200', 13, 0, 0, 0, 0),
('2200', 14, 0, 0, 0, 0),
('2200', 15, 0, 0, 0, 0),
('2200', 16, 0, 0, 0, 0),
('2200', 17, 0, 0, 0, 0),
('2200', 18, 0, 0, 0, 0),
('2200', 19, 0, 0, 0, 0),
('2200', 20, 0, 0, 0, 0),
('2200', 21, 0, 0, 0, 0),
('2200', 22, 0, 0, 0, 0),
('2200', 23, 0, 0, 0, 0),
('2200', 24, 0, 0, 0, 0),
('2200', 25, 0, 0, 0, 0),
('2200', 26, 0, 0, 0, 0),
('2200', 27, 0, 0, 0, 0),
('2200', 28, 0, 0, 0, 0),
('2200', 29, 0, 0, 0, 0),
('2200', 30, 0, 0, 0, 0),
('2200', 31, 0, 0, 0, 0),
('2200', 32, 0, 0, 0, 0),
('2200', 33, 0, 0, 0, 0),
('2200', 34, 0, 0, 0, 0),
('2200', 35, 0, 0, 0, 0),
('2200', 36, 0, 0, 0, 0),
('2200', 37, 0, 0, 0, 0),
('2200', 38, 0, 0, 0, 0),
('2200', 39, 0, 0, 0, 0),
('2200', 40, 0, 0, 0, 0),
('2200', 41, 0, 0, 0, 0),
('2200', 42, 0, 0, 0, 0),
('2200', 43, 0, 0, 0, 0),
('2200', 44, 0, 0, 0, 0),
('2200', 45, 0, 0, 0, 0),
('2200', 46, 0, 0, 0, 0),
('2200', 47, 0, 0, 0, 0),
('2200', 48, 0, 0, 0, 0),
('2200', 49, 0, 0, 0, 0),
('2200', 50, 0, 0, 0, 0),
('2200', 51, 0, 0, 0, 0),
('2200', 52, 0, 0, 0, 0),
('2200', 53, 0, 0, 0, 0),
('2200', 54, 0, 0, 0, 0),
('2200', 55, 0, 0, 0, 0),
('2200', 56, 0, 0, 0, 0),
('2200', 57, 0, 0, 0, 0),
('2200', 58, 0, 0, 0, 0),
('2200', 59, 0, 0, 0, 0),
('2230', -15, 0, 0, 0, 0),
('2230', -14, 0, 0, 0, 0),
('2230', -13, 0, 0, 0, 0),
('2230', -12, 0, 0, 0, 0),
('2230', -11, 0, 0, 0, 0),
('2230', -10, 0, 0, 0, 0),
('2230', -9, 0, 0, 0, 0),
('2230', -8, 0, 0, 0, 0),
('2230', -7, 0, 0, 0, 0),
('2230', -6, 0, 0, 0, 0),
('2230', -5, 0, 0, 0, 0),
('2230', -4, 0, 0, 0, 0),
('2230', -3, 0, 0, 0, 0),
('2230', -2, 0, 0, 0, 0),
('2230', -1, 0, 0, 0, 0),
('2230', 0, 0, 0, 0, 0),
('2230', 1, 0, 0, 0, 0),
('2230', 2, 0, 0, 0, 0),
('2230', 3, 0, 0, 0, 0),
('2230', 4, 0, 0, 0, 0),
('2230', 5, 0, 0, 0, 0),
('2230', 6, 0, 0, 0, 0),
('2230', 7, 0, 0, 0, 0),
('2230', 8, 0, 0, 0, 0),
('2230', 9, 0, 0, 0, 0),
('2230', 10, 0, 0, 0, 0),
('2230', 11, 0, 0, 0, 0),
('2230', 12, 0, 0, 0, 0),
('2230', 13, 0, 0, 0, 0),
('2230', 14, 0, 0, 0, 0),
('2230', 15, 0, 0, 0, 0),
('2230', 16, 0, 0, 0, 0),
('2230', 17, 0, 0, 0, 0),
('2230', 18, 0, 0, 0, 0),
('2230', 19, 0, 0, 0, 0),
('2230', 20, 0, 0, 0, 0),
('2230', 21, 0, 0, 0, 0),
('2230', 22, 0, 0, 0, 0),
('2230', 23, 0, 0, 0, 0),
('2230', 24, 0, 0, 0, 0),
('2230', 25, 0, 0, 0, 0),
('2230', 26, 0, 0, 0, 0),
('2230', 27, 0, 0, 0, 0),
('2230', 28, 0, 0, 0, 0),
('2230', 29, 0, 0, 0, 0),
('2230', 30, 0, 0, 0, 0),
('2230', 31, 0, 0, 0, 0),
('2230', 32, 0, 0, 0, 0),
('2230', 33, 0, 0, 0, 0),
('2230', 34, 0, 0, 0, 0),
('2230', 35, 0, 0, 0, 0),
('2230', 36, 0, 0, 0, 0),
('2230', 37, 0, 0, 0, 0),
('2230', 38, 0, 0, 0, 0),
('2230', 39, 0, 0, 0, 0),
('2230', 40, 0, 0, 0, 0),
('2230', 41, 0, 0, 0, 0),
('2230', 42, 0, 0, 0, 0),
('2230', 43, 0, 0, 0, 0),
('2230', 44, 0, 0, 0, 0),
('2230', 45, 0, 0, 0, 0),
('2230', 46, 0, 0, 0, 0),
('2230', 47, 0, 0, 0, 0),
('2230', 48, 0, 0, 0, 0),
('2230', 49, 0, 0, 0, 0),
('2230', 50, 0, 0, 0, 0),
('2230', 51, 0, 0, 0, 0),
('2230', 52, 0, 0, 0, 0),
('2230', 53, 0, 0, 0, 0),
('2230', 54, 0, 0, 0, 0),
('2230', 55, 0, 0, 0, 0),
('2230', 56, 0, 0, 0, 0),
('2230', 57, 0, 0, 0, 0),
('2230', 58, 0, 0, 0, 0),
('2230', 59, 0, 0, 0, 0),
('2250', -15, 0, 0, 0, 0),
('2250', -14, 0, 0, 0, 0),
('2250', -13, 0, 0, 0, 0),
('2250', -12, 0, 0, 0, 0),
('2250', -11, 0, 0, 0, 0),
('2250', -10, 0, 0, 0, 0),
('2250', -9, 0, 0, 0, 0),
('2250', -8, 0, 0, 0, 0),
('2250', -7, 0, 0, 0, 0),
('2250', -6, 0, 0, 0, 0),
('2250', -5, 0, 0, 0, 0),
('2250', -4, 0, 0, 0, 0),
('2250', -3, 0, 0, 0, 0),
('2250', -2, 0, 0, 0, 0),
('2250', -1, 0, 0, 0, 0),
('2250', 0, 0, 0, 0, 0),
('2250', 1, 0, 0, 0, 0),
('2250', 2, 0, 0, 0, 0),
('2250', 3, 0, 0, 0, 0),
('2250', 4, 0, 0, 0, 0),
('2250', 5, 0, 0, 0, 0),
('2250', 6, 0, 0, 0, 0),
('2250', 7, 0, 0, 0, 0),
('2250', 8, 0, 0, 0, 0),
('2250', 9, 0, 0, 0, 0),
('2250', 10, 0, 0, 0, 0),
('2250', 11, 0, 0, 0, 0),
('2250', 12, 0, 0, 0, 0),
('2250', 13, 0, 0, 0, 0),
('2250', 14, 0, 0, 0, 0),
('2250', 15, 0, 0, 0, 0),
('2250', 16, 0, 0, 0, 0),
('2250', 17, 0, 0, 0, 0),
('2250', 18, 0, 0, 0, 0),
('2250', 19, 0, 0, 0, 0),
('2250', 20, 0, 0, 0, 0),
('2250', 21, 0, 0, 0, 0),
('2250', 22, 0, 0, 0, 0),
('2250', 23, 0, 0, 0, 0),
('2250', 24, 0, 0, 0, 0),
('2250', 25, 0, 0, 0, 0),
('2250', 26, 0, 0, 0, 0),
('2250', 27, 0, 0, 0, 0),
('2250', 28, 0, 0, 0, 0),
('2250', 29, 0, 0, 0, 0),
('2250', 30, 0, 0, 0, 0),
('2250', 31, 0, 0, 0, 0),
('2250', 32, 0, 0, 0, 0),
('2250', 33, 0, 0, 0, 0),
('2250', 34, 0, 0, 0, 0),
('2250', 35, 0, 0, 0, 0),
('2250', 36, 0, 0, 0, 0),
('2250', 37, 0, 0, 0, 0),
('2250', 38, 0, 0, 0, 0),
('2250', 39, 0, 0, 0, 0),
('2250', 40, 0, 0, 0, 0),
('2250', 41, 0, 0, 0, 0),
('2250', 42, 0, 0, 0, 0),
('2250', 43, 0, 0, 0, 0),
('2250', 44, 0, 0, 0, 0),
('2250', 45, 0, 0, 0, 0),
('2250', 46, 0, 0, 0, 0),
('2250', 47, 0, 0, 0, 0),
('2250', 48, 0, 0, 0, 0),
('2250', 49, 0, 0, 0, 0),
('2250', 50, 0, 0, 0, 0),
('2250', 51, 0, 0, 0, 0),
('2250', 52, 0, 0, 0, 0),
('2250', 53, 0, 0, 0, 0),
('2250', 54, 0, 0, 0, 0),
('2250', 55, 0, 0, 0, 0),
('2250', 56, 0, 0, 0, 0),
('2250', 57, 0, 0, 0, 0),
('2250', 58, 0, 0, 0, 0),
('2250', 59, 0, 0, 0, 0),
('2300', -15, 0, 0, 0, 0),
('2300', -14, 0, 0, 0, 0),
('2300', -13, 0, 0, 0, 0),
('2300', -12, 0, 0, 0, 0),
('2300', -11, 0, 0, 0, 0),
('2300', -10, 0, 0, 0, 0),
('2300', -9, 0, 0, 0, 0),
('2300', -8, 0, 0, 0, 0),
('2300', -7, 0, 0, 0, 0),
('2300', -6, 0, 0, 0, 0),
('2300', -5, 0, 0, 0, 0),
('2300', -4, 0, 0, 0, 0),
('2300', -3, 0, 0, 0, 0),
('2300', -2, 0, 0, 0, 0),
('2300', -1, 0, 0, 0, 0),
('2300', 0, 0, 0, 0, 0),
('2300', 1, 0, 0, 0, 0),
('2300', 2, 0, 0, 0, 0),
('2300', 3, 0, 0, 0, 0),
('2300', 4, 0, 0, 0, 0),
('2300', 5, 0, 0, 0, 0),
('2300', 6, 0, 0, 0, 0),
('2300', 7, 0, 0, 0, 0),
('2300', 8, 0, 0, 0, 0),
('2300', 9, 0, 0, 0, 0),
('2300', 10, 0, 0, 0, 0),
('2300', 11, 0, 0, 0, 0),
('2300', 12, 0, 0, 0, 0),
('2300', 13, 0, 0, 0, 0),
('2300', 14, 0, 0, 0, 0),
('2300', 15, 0, 0, 0, 0),
('2300', 16, 0, 0, 0, 0),
('2300', 17, 0, 0, 0, 0),
('2300', 18, 0, 0, 0, 0),
('2300', 19, 0, 0, 0, 0),
('2300', 20, 0, 0, 0, 0),
('2300', 21, 0, 0, 0, 0),
('2300', 22, 0, 0, 0, 0),
('2300', 23, 0, 0, 0, 0),
('2300', 24, 0, 0, 0, 0),
('2300', 25, 0, 0, 0, 0),
('2300', 26, 0, 0, 0, 0),
('2300', 27, 0, 0, 0, 0),
('2300', 28, 0, 0, 0, 0),
('2300', 29, 0, 0, 0, 0),
('2300', 30, 0, 0, 0, 0),
('2300', 31, 0, 0, 0, 0),
('2300', 32, 0, 0, 0, 0),
('2300', 33, 0, 0, 0, 0),
('2300', 34, 0, 0, 0, 0),
('2300', 35, 0, 0, 0, 0),
('2300', 36, 0, 0, 0, 0),
('2300', 37, 0, 0, 0, 0),
('2300', 38, 0, 0, 0, 0),
('2300', 39, 0, 0, 0, 0),
('2300', 40, 0, 0, 0, 0),
('2300', 41, 0, 0, 0, 0),
('2300', 42, 0, 0, 0, 0),
('2300', 43, 0, 0, 0, 0),
('2300', 44, 0, 0, 0, 0),
('2300', 45, 0, 0, 0, 0),
('2300', 46, 0, 0, 0, 0),
('2300', 47, 0, 0, 0, 0),
('2300', 48, 0, 0, 0, 0),
('2300', 49, 0, 0, 0, 0),
('2300', 50, 0, 0, 0, 0),
('2300', 51, 0, 0, 0, 0),
('2300', 52, 0, 0, 0, 0),
('2300', 53, 0, 0, 0, 0),
('2300', 54, 0, 0, 0, 0),
('2300', 55, 0, 0, 0, 0),
('2300', 56, 0, 0, 0, 0),
('2300', 57, 0, 0, 0, 0),
('2300', 58, 0, 0, 0, 0),
('2300', 59, 0, 0, 0, 0),
('2310', -15, 0, 0, 0, 0),
('2310', -14, 0, 0, 0, 0),
('2310', -13, 0, 0, 0, 0),
('2310', -12, 0, 0, 0, 0),
('2310', -11, 0, 0, 0, 0),
('2310', -10, 0, 0, 0, 0),
('2310', -9, 0, 0, 0, 0),
('2310', -8, 0, 0, 0, 0),
('2310', -7, 0, 0, 0, 0),
('2310', -6, 0, 0, 0, 0),
('2310', -5, 0, 0, 0, 0),
('2310', -4, 0, 0, 0, 0),
('2310', -3, 0, 0, 0, 0),
('2310', -2, 0, 0, 0, 0),
('2310', -1, 0, 0, 0, 0),
('2310', 0, 0, 0, 0, 0),
('2310', 1, 0, 0, 0, 0),
('2310', 2, 0, 0, 0, 0),
('2310', 3, 0, 0, 0, 0),
('2310', 4, 0, 0, 0, 0),
('2310', 5, 0, 0, 0, 0),
('2310', 6, 0, 0, 0, 0),
('2310', 7, 0, 0, 0, 0),
('2310', 8, 0, 0, 0, 0),
('2310', 9, 0, 0, 0, 0),
('2310', 10, 0, 0, 0, 0),
('2310', 11, 0, 0, 0, 0),
('2310', 12, 0, 0, 0, 0),
('2310', 13, 0, 0, 0, 0),
('2310', 14, 0, 0, 0, 0),
('2310', 15, 0, 0, 0, 0),
('2310', 16, 0, 0, 0, 0),
('2310', 17, 0, 0, 0, 0),
('2310', 18, 0, 0, 0, 0),
('2310', 19, 0, 0, 0, 0),
('2310', 20, 0, 0, 0, 0),
('2310', 21, 0, 0, 0, 0),
('2310', 22, 0, 0, 0, 0),
('2310', 23, 0, 0, 0, 0),
('2310', 24, 0, 0, 0, 0),
('2310', 25, 0, 0, 0, 0),
('2310', 26, 0, 0, 0, 0),
('2310', 27, 0, 0, 0, 0),
('2310', 28, 0, 0, 0, 0),
('2310', 29, 0, 0, 0, 0),
('2310', 30, 0, 0, 0, 0),
('2310', 31, 0, 0, 0, 0),
('2310', 32, 0, 0, 0, 0),
('2310', 33, 0, 0, 0, 0),
('2310', 34, 0, 0, 0, 0),
('2310', 35, 0, 0, 0, 0),
('2310', 36, 0, 0, 0, 0),
('2310', 37, 0, 0, 0, 0),
('2310', 38, 0, 0, 0, 0),
('2310', 39, 0, 0, 0, 0),
('2310', 40, 0, 0, 0, 0),
('2310', 41, 0, 0, 0, 0),
('2310', 42, 0, 0, 0, 0),
('2310', 43, 0, 0, 0, 0),
('2310', 44, 0, 0, 0, 0),
('2310', 45, 0, 0, 0, 0),
('2310', 46, 0, 0, 0, 0),
('2310', 47, 0, 0, 0, 0),
('2310', 48, 0, 0, 0, 0),
('2310', 49, 0, 0, 0, 0),
('2310', 50, 0, 0, 0, 0),
('2310', 51, 0, 0, 0, 0),
('2310', 52, 0, 0, 0, 0),
('2310', 53, 0, 0, 0, 0),
('2310', 54, 0, 0, 0, 0),
('2310', 55, 0, 0, 0, 0),
('2310', 56, 0, 0, 0, 0),
('2310', 57, 0, 0, 0, 0),
('2310', 58, 0, 0, 0, 0),
('2310', 59, 0, 0, 0, 0),
('2320', -15, 0, 0, 0, 0),
('2320', -14, 0, 0, 0, 0),
('2320', -13, 0, 0, 0, 0),
('2320', -12, 0, 0, 0, 0),
('2320', -11, 0, 0, 0, 0),
('2320', -10, 0, 0, 0, 0),
('2320', -9, 0, 0, 0, 0),
('2320', -8, 0, 0, 0, 0),
('2320', -7, 0, 0, 0, 0),
('2320', -6, 0, 0, 0, 0),
('2320', -5, 0, 0, 0, 0),
('2320', -4, 0, 0, 0, 0),
('2320', -3, 0, 0, 0, 0),
('2320', -2, 0, 0, 0, 0),
('2320', -1, 0, 0, 0, 0),
('2320', 0, 0, 0, 0, 0),
('2320', 1, 0, 0, 0, 0),
('2320', 2, 0, 0, 0, 0),
('2320', 3, 0, 0, 0, 0),
('2320', 4, 0, 0, 0, 0),
('2320', 5, 0, 0, 0, 0),
('2320', 6, 0, 0, 0, 0),
('2320', 7, 0, 0, 0, 0),
('2320', 8, 0, 0, 0, 0),
('2320', 9, 0, 0, 0, 0),
('2320', 10, 0, 0, 0, 0),
('2320', 11, 0, 0, 0, 0),
('2320', 12, 0, 0, 0, 0),
('2320', 13, 0, 0, 0, 0),
('2320', 14, 0, 0, 0, 0),
('2320', 15, 0, 0, 0, 0),
('2320', 16, 0, 0, 0, 0),
('2320', 17, 0, 0, 0, 0),
('2320', 18, 0, 0, 0, 0),
('2320', 19, 0, 0, 0, 0),
('2320', 20, 0, 0, 0, 0),
('2320', 21, 0, 0, 0, 0),
('2320', 22, 0, 0, 0, 0),
('2320', 23, 0, 0, 0, 0),
('2320', 24, 0, 0, 0, 0),
('2320', 25, 0, 0, 0, 0),
('2320', 26, 0, 0, 0, 0),
('2320', 27, 0, 0, 0, 0),
('2320', 28, 0, 0, 0, 0),
('2320', 29, 0, 0, 0, 0),
('2320', 30, 0, 0, 0, 0),
('2320', 31, 0, 0, 0, 0),
('2320', 32, 0, 0, 0, 0),
('2320', 33, 0, 0, 0, 0),
('2320', 34, 0, 0, 0, 0),
('2320', 35, 0, 0, 0, 0),
('2320', 36, 0, 0, 0, 0),
('2320', 37, 0, 0, 0, 0),
('2320', 38, 0, 0, 0, 0),
('2320', 39, 0, 0, 0, 0),
('2320', 40, 0, 0, 0, 0),
('2320', 41, 0, 0, 0, 0),
('2320', 42, 0, 0, 0, 0),
('2320', 43, 0, 0, 0, 0),
('2320', 44, 0, 0, 0, 0),
('2320', 45, 0, 0, 0, 0),
('2320', 46, 0, 0, 0, 0),
('2320', 47, 0, 0, 0, 0),
('2320', 48, 0, 0, 0, 0),
('2320', 49, 0, 0, 0, 0),
('2320', 50, 0, 0, 0, 0),
('2320', 51, 0, 0, 0, 0),
('2320', 52, 0, 0, 0, 0),
('2320', 53, 0, 0, 0, 0),
('2320', 54, 0, 0, 0, 0),
('2320', 55, 0, 0, 0, 0),
('2320', 56, 0, 0, 0, 0),
('2320', 57, 0, 0, 0, 0),
('2320', 58, 0, 0, 0, 0),
('2320', 59, 0, 0, 0, 0),
('2330', -15, 0, 0, 0, 0),
('2330', -14, 0, 0, 0, 0),
('2330', -13, 0, 0, 0, 0),
('2330', -12, 0, 0, 0, 0),
('2330', -11, 0, 0, 0, 0),
('2330', -10, 0, 0, 0, 0),
('2330', -9, 0, 0, 0, 0),
('2330', -8, 0, 0, 0, 0),
('2330', -7, 0, 0, 0, 0),
('2330', -6, 0, 0, 0, 0),
('2330', -5, 0, 0, 0, 0),
('2330', -4, 0, 0, 0, 0),
('2330', -3, 0, 0, 0, 0),
('2330', -2, 0, 0, 0, 0),
('2330', -1, 0, 0, 0, 0),
('2330', 0, 0, 0, 0, 0),
('2330', 1, 0, 0, 0, 0),
('2330', 2, 0, 0, 0, 0),
('2330', 3, 0, 0, 0, 0),
('2330', 4, 0, 0, 0, 0),
('2330', 5, 0, 0, 0, 0),
('2330', 6, 0, 0, 0, 0),
('2330', 7, 0, 0, 0, 0),
('2330', 8, 0, 0, 0, 0),
('2330', 9, 0, 0, 0, 0),
('2330', 10, 0, 0, 0, 0),
('2330', 11, 0, 0, 0, 0),
('2330', 12, 0, 0, 0, 0),
('2330', 13, 0, 0, 0, 0),
('2330', 14, 0, 0, 0, 0),
('2330', 15, 0, 0, 0, 0),
('2330', 16, 0, 0, 0, 0),
('2330', 17, 0, 0, 0, 0),
('2330', 18, 0, 0, 0, 0),
('2330', 19, 0, 0, 0, 0),
('2330', 20, 0, 0, 0, 0),
('2330', 21, 0, 0, 0, 0),
('2330', 22, 0, 0, 0, 0),
('2330', 23, 0, 0, 0, 0),
('2330', 24, 0, 0, 0, 0),
('2330', 25, 0, 0, 0, 0),
('2330', 26, 0, 0, 0, 0),
('2330', 27, 0, 0, 0, 0),
('2330', 28, 0, 0, 0, 0),
('2330', 29, 0, 0, 0, 0),
('2330', 30, 0, 0, 0, 0),
('2330', 31, 0, 0, 0, 0),
('2330', 32, 0, 0, 0, 0),
('2330', 33, 0, 0, 0, 0),
('2330', 34, 0, 0, 0, 0),
('2330', 35, 0, 0, 0, 0),
('2330', 36, 0, 0, 0, 0),
('2330', 37, 0, 0, 0, 0),
('2330', 38, 0, 0, 0, 0),
('2330', 39, 0, 0, 0, 0),
('2330', 40, 0, 0, 0, 0),
('2330', 41, 0, 0, 0, 0),
('2330', 42, 0, 0, 0, 0),
('2330', 43, 0, 0, 0, 0),
('2330', 44, 0, 0, 0, 0),
('2330', 45, 0, 0, 0, 0),
('2330', 46, 0, 0, 0, 0),
('2330', 47, 0, 0, 0, 0),
('2330', 48, 0, 0, 0, 0),
('2330', 49, 0, 0, 0, 0),
('2330', 50, 0, 0, 0, 0),
('2330', 51, 0, 0, 0, 0),
('2330', 52, 0, 0, 0, 0),
('2330', 53, 0, 0, 0, 0),
('2330', 54, 0, 0, 0, 0),
('2330', 55, 0, 0, 0, 0),
('2330', 56, 0, 0, 0, 0),
('2330', 57, 0, 0, 0, 0),
('2330', 58, 0, 0, 0, 0),
('2330', 59, 0, 0, 0, 0),
('2340', -15, 0, 0, 0, 0),
('2340', -14, 0, 0, 0, 0),
('2340', -13, 0, 0, 0, 0),
('2340', -12, 0, 0, 0, 0),
('2340', -11, 0, 0, 0, 0),
('2340', -10, 0, 0, 0, 0),
('2340', -9, 0, 0, 0, 0),
('2340', -8, 0, 0, 0, 0),
('2340', -7, 0, 0, 0, 0),
('2340', -6, 0, 0, 0, 0),
('2340', -5, 0, 0, 0, 0),
('2340', -4, 0, 0, 0, 0),
('2340', -3, 0, 0, 0, 0),
('2340', -2, 0, 0, 0, 0),
('2340', -1, 0, 0, 0, 0),
('2340', 0, 0, 0, 0, 0),
('2340', 1, 0, 0, 0, 0),
('2340', 2, 0, 0, 0, 0),
('2340', 3, 0, 0, 0, 0),
('2340', 4, 0, 0, 0, 0),
('2340', 5, 0, 0, 0, 0),
('2340', 6, 0, 0, 0, 0),
('2340', 7, 0, 0, 0, 0),
('2340', 8, 0, 0, 0, 0),
('2340', 9, 0, 0, 0, 0),
('2340', 10, 0, 0, 0, 0),
('2340', 11, 0, 0, 0, 0),
('2340', 12, 0, 0, 0, 0),
('2340', 13, 0, 0, 0, 0),
('2340', 14, 0, 0, 0, 0),
('2340', 15, 0, 0, 0, 0),
('2340', 16, 0, 0, 0, 0),
('2340', 17, 0, 0, 0, 0),
('2340', 18, 0, 0, 0, 0),
('2340', 19, 0, 0, 0, 0),
('2340', 20, 0, 0, 0, 0),
('2340', 21, 0, 0, 0, 0),
('2340', 22, 0, 0, 0, 0),
('2340', 23, 0, 0, 0, 0),
('2340', 24, 0, 0, 0, 0),
('2340', 25, 0, 0, 0, 0),
('2340', 26, 0, 0, 0, 0),
('2340', 27, 0, 0, 0, 0),
('2340', 28, 0, 0, 0, 0),
('2340', 29, 0, 0, 0, 0),
('2340', 30, 0, 0, 0, 0),
('2340', 31, 0, 0, 0, 0),
('2340', 32, 0, 0, 0, 0),
('2340', 33, 0, 0, 0, 0),
('2340', 34, 0, 0, 0, 0),
('2340', 35, 0, 0, 0, 0),
('2340', 36, 0, 0, 0, 0),
('2340', 37, 0, 0, 0, 0),
('2340', 38, 0, 0, 0, 0),
('2340', 39, 0, 0, 0, 0),
('2340', 40, 0, 0, 0, 0),
('2340', 41, 0, 0, 0, 0),
('2340', 42, 0, 0, 0, 0),
('2340', 43, 0, 0, 0, 0),
('2340', 44, 0, 0, 0, 0),
('2340', 45, 0, 0, 0, 0),
('2340', 46, 0, 0, 0, 0),
('2340', 47, 0, 0, 0, 0),
('2340', 48, 0, 0, 0, 0),
('2340', 49, 0, 0, 0, 0),
('2340', 50, 0, 0, 0, 0),
('2340', 51, 0, 0, 0, 0),
('2340', 52, 0, 0, 0, 0),
('2340', 53, 0, 0, 0, 0),
('2340', 54, 0, 0, 0, 0),
('2340', 55, 0, 0, 0, 0),
('2340', 56, 0, 0, 0, 0),
('2340', 57, 0, 0, 0, 0),
('2340', 58, 0, 0, 0, 0),
('2340', 59, 0, 0, 0, 0),
('2350', -15, 0, 0, 0, 0),
('2350', -14, 0, 0, 0, 0),
('2350', -13, 0, 0, 0, 0),
('2350', -12, 0, 0, 0, 0),
('2350', -11, 0, 0, 0, 0),
('2350', -10, 0, 0, 0, 0),
('2350', -9, 0, 0, 0, 0),
('2350', -8, 0, 0, 0, 0),
('2350', -7, 0, 0, 0, 0),
('2350', -6, 0, 0, 0, 0),
('2350', -5, 0, 0, 0, 0),
('2350', -4, 0, 0, 0, 0),
('2350', -3, 0, 0, 0, 0),
('2350', -2, 0, 0, 0, 0),
('2350', -1, 0, 0, 0, 0),
('2350', 0, 0, 0, 0, 0),
('2350', 1, 0, 0, 0, 0),
('2350', 2, 0, 0, 0, 0),
('2350', 3, 0, 0, 0, 0),
('2350', 4, 0, 0, 0, 0),
('2350', 5, 0, 0, 0, 0),
('2350', 6, 0, 0, 0, 0),
('2350', 7, 0, 0, 0, 0),
('2350', 8, 0, 0, 0, 0),
('2350', 9, 0, 0, 0, 0),
('2350', 10, 0, 0, 0, 0),
('2350', 11, 0, 0, 0, 0),
('2350', 12, 0, 0, 0, 0),
('2350', 13, 0, 0, 0, 0),
('2350', 14, 0, 0, 0, 0),
('2350', 15, 0, 0, 0, 0),
('2350', 16, 0, 0, 0, 0),
('2350', 17, 0, 0, 0, 0),
('2350', 18, 0, 0, 0, 0),
('2350', 19, 0, 0, 0, 0),
('2350', 20, 0, 0, 0, 0),
('2350', 21, 0, 0, 0, 0),
('2350', 22, 0, 0, 0, 0),
('2350', 23, 0, 0, 0, 0),
('2350', 24, 0, 0, 0, 0),
('2350', 25, 0, 0, 0, 0),
('2350', 26, 0, 0, 0, 0),
('2350', 27, 0, 0, 0, 0),
('2350', 28, 0, 0, 0, 0),
('2350', 29, 0, 0, 0, 0),
('2350', 30, 0, 0, 0, 0),
('2350', 31, 0, 0, 0, 0),
('2350', 32, 0, 0, 0, 0),
('2350', 33, 0, 0, 0, 0),
('2350', 34, 0, 0, 0, 0),
('2350', 35, 0, 0, 0, 0),
('2350', 36, 0, 0, 0, 0),
('2350', 37, 0, 0, 0, 0),
('2350', 38, 0, 0, 0, 0),
('2350', 39, 0, 0, 0, 0),
('2350', 40, 0, 0, 0, 0),
('2350', 41, 0, 0, 0, 0),
('2350', 42, 0, 0, 0, 0),
('2350', 43, 0, 0, 0, 0),
('2350', 44, 0, 0, 0, 0),
('2350', 45, 0, 0, 0, 0),
('2350', 46, 0, 0, 0, 0),
('2350', 47, 0, 0, 0, 0),
('2350', 48, 0, 0, 0, 0),
('2350', 49, 0, 0, 0, 0),
('2350', 50, 0, 0, 0, 0),
('2350', 51, 0, 0, 0, 0),
('2350', 52, 0, 0, 0, 0),
('2350', 53, 0, 0, 0, 0),
('2350', 54, 0, 0, 0, 0),
('2350', 55, 0, 0, 0, 0),
('2350', 56, 0, 0, 0, 0),
('2350', 57, 0, 0, 0, 0),
('2350', 58, 0, 0, 0, 0),
('2350', 59, 0, 0, 0, 0),
('2360', -15, 0, 0, 0, 0),
('2360', -14, 0, 0, 0, 0),
('2360', -13, 0, 0, 0, 0),
('2360', -12, 0, 0, 0, 0),
('2360', -11, 0, 0, 0, 0),
('2360', -10, 0, 0, 0, 0),
('2360', -9, 0, 0, 0, 0),
('2360', -8, 0, 0, 0, 0),
('2360', -7, 0, 0, 0, 0),
('2360', -6, 0, 0, 0, 0),
('2360', -5, 0, 0, 0, 0),
('2360', -4, 0, 0, 0, 0),
('2360', -3, 0, 0, 0, 0),
('2360', -2, 0, 0, 0, 0),
('2360', -1, 0, 0, 0, 0),
('2360', 0, 0, 0, 0, 0),
('2360', 1, 0, 0, 0, 0),
('2360', 2, 0, 0, 0, 0),
('2360', 3, 0, 0, 0, 0),
('2360', 4, 0, 0, 0, 0),
('2360', 5, 0, 0, 0, 0),
('2360', 6, 0, 0, 0, 0),
('2360', 7, 0, 0, 0, 0),
('2360', 8, 0, 0, 0, 0),
('2360', 9, 0, 0, 0, 0),
('2360', 10, 0, 0, 0, 0),
('2360', 11, 0, 0, 0, 0),
('2360', 12, 0, 0, 0, 0),
('2360', 13, 0, 0, 0, 0),
('2360', 14, 0, 0, 0, 0),
('2360', 15, 0, 0, 0, 0),
('2360', 16, 0, 0, 0, 0),
('2360', 17, 0, 0, 0, 0),
('2360', 18, 0, 0, 0, 0),
('2360', 19, 0, 0, 0, 0),
('2360', 20, 0, 0, 0, 0),
('2360', 21, 0, 0, 0, 0),
('2360', 22, 0, 0, 0, 0),
('2360', 23, 0, 0, 0, 0),
('2360', 24, 0, 0, 0, 0),
('2360', 25, 0, 0, 0, 0),
('2360', 26, 0, 0, 0, 0),
('2360', 27, 0, 0, 0, 0),
('2360', 28, 0, 0, 0, 0),
('2360', 29, 0, 0, 0, 0),
('2360', 30, 0, 0, 0, 0),
('2360', 31, 0, 0, 0, 0),
('2360', 32, 0, 0, 0, 0),
('2360', 33, 0, 0, 0, 0),
('2360', 34, 0, 0, 0, 0),
('2360', 35, 0, 0, 0, 0),
('2360', 36, 0, 0, 0, 0),
('2360', 37, 0, 0, 0, 0),
('2360', 38, 0, 0, 0, 0),
('2360', 39, 0, 0, 0, 0),
('2360', 40, 0, 0, 0, 0),
('2360', 41, 0, 0, 0, 0),
('2360', 42, 0, 0, 0, 0),
('2360', 43, 0, 0, 0, 0),
('2360', 44, 0, 0, 0, 0),
('2360', 45, 0, 0, 0, 0),
('2360', 46, 0, 0, 0, 0),
('2360', 47, 0, 0, 0, 0),
('2360', 48, 0, 0, 0, 0),
('2360', 49, 0, 0, 0, 0),
('2360', 50, 0, 0, 0, 0),
('2360', 51, 0, 0, 0, 0),
('2360', 52, 0, 0, 0, 0),
('2360', 53, 0, 0, 0, 0),
('2360', 54, 0, 0, 0, 0),
('2360', 55, 0, 0, 0, 0),
('2360', 56, 0, 0, 0, 0),
('2360', 57, 0, 0, 0, 0),
('2360', 58, 0, 0, 0, 0),
('2360', 59, 0, 0, 0, 0),
('2400', -15, 0, 0, 0, 0),
('2400', -14, 0, 0, 0, 0),
('2400', -13, 0, 0, 0, 0),
('2400', -12, 0, 0, 0, 0),
('2400', -11, 0, 0, 0, 0),
('2400', -10, 0, 0, 0, 0),
('2400', -9, 0, 0, 0, 0),
('2400', -8, 0, 0, 0, 0),
('2400', -7, 0, 0, 0, 0),
('2400', -6, 0, 0, 0, 0),
('2400', -5, 0, 0, 0, 0),
('2400', -4, 0, 0, 0, 0),
('2400', -3, 0, 0, 0, 0),
('2400', -2, 0, 0, 0, 0),
('2400', -1, 0, 0, 0, 0),
('2400', 0, 0, 0, 0, 0),
('2400', 1, 0, 0, 0, 0),
('2400', 2, 0, 0, 0, 0),
('2400', 3, 0, 0, 0, 0),
('2400', 4, 0, 0, 0, 0),
('2400', 5, 0, 0, 0, 0),
('2400', 6, 0, 0, 0, 0),
('2400', 7, 0, 0, 0, 0),
('2400', 8, 0, 0, 0, 0),
('2400', 9, 0, 0, 0, 0),
('2400', 10, 0, 0, 0, 0),
('2400', 11, 0, 0, 0, 0),
('2400', 12, 0, 0, 0, 0),
('2400', 13, 0, 0, 0, 0),
('2400', 14, 0, 0, 0, 0),
('2400', 15, 0, 0, 0, 0),
('2400', 16, 0, 0, 0, 0),
('2400', 17, 0, 0, 0, 0),
('2400', 18, 0, 0, 0, 0),
('2400', 19, 0, 0, 0, 0),
('2400', 20, 0, 0, 0, 0),
('2400', 21, 0, 0, 0, 0),
('2400', 22, 0, 0, 0, 0),
('2400', 23, 0, 0, 0, 0),
('2400', 24, 0, 0, 0, 0),
('2400', 25, 0, 0, 0, 0),
('2400', 26, 0, 0, 0, 0),
('2400', 27, 0, 0, 0, 0),
('2400', 28, 0, 0, 0, 0),
('2400', 29, 0, 0, 0, 0),
('2400', 30, 0, 0, 0, 0),
('2400', 31, 0, 0, 0, 0),
('2400', 32, 0, 0, 0, 0),
('2400', 33, 0, 0, 0, 0),
('2400', 34, 0, 0, 0, 0),
('2400', 35, 0, 0, 0, 0),
('2400', 36, 0, 0, 0, 0),
('2400', 37, 0, 0, 0, 0),
('2400', 38, 0, 0, 0, 0),
('2400', 39, 0, 0, 0, 0),
('2400', 40, 0, 0, 0, 0),
('2400', 41, 0, 0, 0, 0),
('2400', 42, 0, 0, 0, 0),
('2400', 43, 0, 0, 0, 0),
('2400', 44, 0, 0, 0, 0),
('2400', 45, 0, 0, 0, 0),
('2400', 46, 0, 0, 0, 0),
('2400', 47, 0, 0, 0, 0),
('2400', 48, 0, 0, 0, 0),
('2400', 49, 0, 0, 0, 0),
('2400', 50, 0, 0, 0, 0),
('2400', 51, 0, 0, 0, 0),
('2400', 52, 0, 0, 0, 0),
('2400', 53, 0, 0, 0, 0),
('2400', 54, 0, 0, 0, 0),
('2400', 55, 0, 0, 0, 0),
('2400', 56, 0, 0, 0, 0),
('2400', 57, 0, 0, 0, 0),
('2400', 58, 0, 0, 0, 0),
('2400', 59, 0, 0, 0, 0),
('2410', -15, 0, 0, 0, 0),
('2410', -14, 0, 0, 0, 0),
('2410', -13, 0, 0, 0, 0),
('2410', -12, 0, 0, 0, 0),
('2410', -11, 0, 0, 0, 0),
('2410', -10, 0, 0, 0, 0),
('2410', -9, 0, 0, 0, 0),
('2410', -8, 0, 0, 0, 0),
('2410', -7, 0, 0, 0, 0),
('2410', -6, 0, 0, 0, 0),
('2410', -5, 0, 0, 0, 0),
('2410', -4, 0, 0, 0, 0),
('2410', -3, 0, 0, 0, 0),
('2410', -2, 0, 0, 0, 0),
('2410', -1, 0, 0, 0, 0),
('2410', 0, 0, 0, 0, 0),
('2410', 1, 0, 0, 0, 0),
('2410', 2, 0, 0, 0, 0),
('2410', 3, 0, 0, 0, 0),
('2410', 4, 0, 0, 0, 0),
('2410', 5, 0, 0, 0, 0),
('2410', 6, 0, 0, 0, 0),
('2410', 7, 0, 0, 0, 0),
('2410', 8, 0, 0, 0, 0),
('2410', 9, 0, 0, 0, 0),
('2410', 10, 0, 0, 0, 0),
('2410', 11, 0, 0, 0, 0),
('2410', 12, 0, 0, 0, 0),
('2410', 13, 0, 0, 0, 0),
('2410', 14, 0, 0, 0, 0),
('2410', 15, 0, 0, 0, 0),
('2410', 16, 0, 0, 0, 0),
('2410', 17, 0, 0, 0, 0),
('2410', 18, 0, 0, 0, 0),
('2410', 19, 0, 0, 0, 0),
('2410', 20, 0, 0, 0, 0),
('2410', 21, 0, 0, 0, 0),
('2410', 22, 0, 0, 0, 0),
('2410', 23, 0, 0, 0, 0),
('2410', 24, 0, 0, 0, 0),
('2410', 25, 0, 0, 0, 0),
('2410', 26, 0, 0, 0, 0),
('2410', 27, 0, 0, 0, 0),
('2410', 28, 0, 0, 0, 0),
('2410', 29, 0, 0, 0, 0),
('2410', 30, 0, 0, 0, 0),
('2410', 31, 0, 0, 0, 0),
('2410', 32, 0, 0, 0, 0),
('2410', 33, 0, 0, 0, 0),
('2410', 34, 0, 0, 0, 0),
('2410', 35, 0, 0, 0, 0);
INSERT INTO `chartdetails` (`accountcode`, `period`, `budget`, `actual`, `bfwd`, `bfwdbudget`) VALUES
('2410', 36, 0, 0, 0, 0),
('2410', 37, 0, 0, 0, 0),
('2410', 38, 0, 0, 0, 0),
('2410', 39, 0, 0, 0, 0),
('2410', 40, 0, 0, 0, 0),
('2410', 41, 0, 0, 0, 0),
('2410', 42, 0, 0, 0, 0),
('2410', 43, 0, 0, 0, 0),
('2410', 44, 0, 0, 0, 0),
('2410', 45, 0, 0, 0, 0),
('2410', 46, 0, 0, 0, 0),
('2410', 47, 0, 0, 0, 0),
('2410', 48, 0, 0, 0, 0),
('2410', 49, 0, 0, 0, 0),
('2410', 50, 0, 0, 0, 0),
('2410', 51, 0, 0, 0, 0),
('2410', 52, 0, 0, 0, 0),
('2410', 53, 0, 0, 0, 0),
('2410', 54, 0, 0, 0, 0),
('2410', 55, 0, 0, 0, 0),
('2410', 56, 0, 0, 0, 0),
('2410', 57, 0, 0, 0, 0),
('2410', 58, 0, 0, 0, 0),
('2410', 59, 0, 0, 0, 0),
('2420', -15, 0, 0, 0, 0),
('2420', -14, 0, 0, 0, 0),
('2420', -13, 0, 0, 0, 0),
('2420', -12, 0, 0, 0, 0),
('2420', -11, 0, 0, 0, 0),
('2420', -10, 0, 0, 0, 0),
('2420', -9, 0, 0, 0, 0),
('2420', -8, 0, 0, 0, 0),
('2420', -7, 0, 0, 0, 0),
('2420', -6, 0, 0, 0, 0),
('2420', -5, 0, 0, 0, 0),
('2420', -4, 0, 0, 0, 0),
('2420', -3, 0, 0, 0, 0),
('2420', -2, 0, 0, 0, 0),
('2420', -1, 0, 0, 0, 0),
('2420', 0, 0, 0, 0, 0),
('2420', 1, 0, 0, 0, 0),
('2420', 2, 0, 0, 0, 0),
('2420', 3, 0, 0, 0, 0),
('2420', 4, 0, 0, 0, 0),
('2420', 5, 0, 0, 0, 0),
('2420', 6, 0, 0, 0, 0),
('2420', 7, 0, 0, 0, 0),
('2420', 8, 0, 0, 0, 0),
('2420', 9, 0, 0, 0, 0),
('2420', 10, 0, 0, 0, 0),
('2420', 11, 0, 0, 0, 0),
('2420', 12, 0, 0, 0, 0),
('2420', 13, 0, 0, 0, 0),
('2420', 14, 0, 0, 0, 0),
('2420', 15, 0, 0, 0, 0),
('2420', 16, 0, 0, 0, 0),
('2420', 17, 0, 0, 0, 0),
('2420', 18, 0, 0, 0, 0),
('2420', 19, 0, 0, 0, 0),
('2420', 20, 0, 0, 0, 0),
('2420', 21, 0, 0, 0, 0),
('2420', 22, 0, 0, 0, 0),
('2420', 23, 0, 0, 0, 0),
('2420', 24, 0, 0, 0, 0),
('2420', 25, 0, 0, 0, 0),
('2420', 26, 0, 0, 0, 0),
('2420', 27, 0, 0, 0, 0),
('2420', 28, 0, 0, 0, 0),
('2420', 29, 0, 0, 0, 0),
('2420', 30, 0, 0, 0, 0),
('2420', 31, 0, 0, 0, 0),
('2420', 32, 0, 0, 0, 0),
('2420', 33, 0, 0, 0, 0),
('2420', 34, 0, 0, 0, 0),
('2420', 35, 0, 0, 0, 0),
('2420', 36, 0, 0, 0, 0),
('2420', 37, 0, 0, 0, 0),
('2420', 38, 0, 0, 0, 0),
('2420', 39, 0, 0, 0, 0),
('2420', 40, 0, 0, 0, 0),
('2420', 41, 0, 0, 0, 0),
('2420', 42, 0, 0, 0, 0),
('2420', 43, 0, 0, 0, 0),
('2420', 44, 0, 0, 0, 0),
('2420', 45, 0, 0, 0, 0),
('2420', 46, 0, 0, 0, 0),
('2420', 47, 0, 0, 0, 0),
('2420', 48, 0, 0, 0, 0),
('2420', 49, 0, 0, 0, 0),
('2420', 50, 0, 0, 0, 0),
('2420', 51, 0, 0, 0, 0),
('2420', 52, 0, 0, 0, 0),
('2420', 53, 0, 0, 0, 0),
('2420', 54, 0, 0, 0, 0),
('2420', 55, 0, 0, 0, 0),
('2420', 56, 0, 0, 0, 0),
('2420', 57, 0, 0, 0, 0),
('2420', 58, 0, 0, 0, 0),
('2420', 59, 0, 0, 0, 0),
('2450', -15, 0, 0, 0, 0),
('2450', -14, 0, 0, 0, 0),
('2450', -13, 0, 0, 0, 0),
('2450', -12, 0, 0, 0, 0),
('2450', -11, 0, 0, 0, 0),
('2450', -10, 0, 0, 0, 0),
('2450', -9, 0, 0, 0, 0),
('2450', -8, 0, 0, 0, 0),
('2450', -7, 0, 0, 0, 0),
('2450', -6, 0, 0, 0, 0),
('2450', -5, 0, 0, 0, 0),
('2450', -4, 0, 0, 0, 0),
('2450', -3, 0, 0, 0, 0),
('2450', -2, 0, 0, 0, 0),
('2450', -1, 0, 0, 0, 0),
('2450', 0, 0, 0, 0, 0),
('2450', 1, 0, 0, 0, 0),
('2450', 2, 0, 0, 0, 0),
('2450', 3, 0, 0, 0, 0),
('2450', 4, 0, 0, 0, 0),
('2450', 5, 0, 0, 0, 0),
('2450', 6, 0, 0, 0, 0),
('2450', 7, 0, 0, 0, 0),
('2450', 8, 0, 0, 0, 0),
('2450', 9, 0, 0, 0, 0),
('2450', 10, 0, 0, 0, 0),
('2450', 11, 0, 0, 0, 0),
('2450', 12, 0, 0, 0, 0),
('2450', 13, 0, 0, 0, 0),
('2450', 14, 0, 0, 0, 0),
('2450', 15, 0, 0, 0, 0),
('2450', 16, 0, 0, 0, 0),
('2450', 17, 0, 0, 0, 0),
('2450', 18, 0, 0, 0, 0),
('2450', 19, 0, 0, 0, 0),
('2450', 20, 0, 0, 0, 0),
('2450', 21, 0, 0, 0, 0),
('2450', 22, 0, 0, 0, 0),
('2450', 23, 0, 0, 0, 0),
('2450', 24, 0, 0, 0, 0),
('2450', 25, 0, 0, 0, 0),
('2450', 26, 0, 0, 0, 0),
('2450', 27, 0, 0, 0, 0),
('2450', 28, 0, 0, 0, 0),
('2450', 29, 0, 0, 0, 0),
('2450', 30, 0, 0, 0, 0),
('2450', 31, 0, 0, 0, 0),
('2450', 32, 0, 0, 0, 0),
('2450', 33, 0, 0, 0, 0),
('2450', 34, 0, 0, 0, 0),
('2450', 35, 0, 0, 0, 0),
('2450', 36, 0, 0, 0, 0),
('2450', 37, 0, 0, 0, 0),
('2450', 38, 0, 0, 0, 0),
('2450', 39, 0, 0, 0, 0),
('2450', 40, 0, 0, 0, 0),
('2450', 41, 0, 0, 0, 0),
('2450', 42, 0, 0, 0, 0),
('2450', 43, 0, 0, 0, 0),
('2450', 44, 0, 0, 0, 0),
('2450', 45, 0, 0, 0, 0),
('2450', 46, 0, 0, 0, 0),
('2450', 47, 0, 0, 0, 0),
('2450', 48, 0, 0, 0, 0),
('2450', 49, 0, 0, 0, 0),
('2450', 50, 0, 0, 0, 0),
('2450', 51, 0, 0, 0, 0),
('2450', 52, 0, 0, 0, 0),
('2450', 53, 0, 0, 0, 0),
('2450', 54, 0, 0, 0, 0),
('2450', 55, 0, 0, 0, 0),
('2450', 56, 0, 0, 0, 0),
('2450', 57, 0, 0, 0, 0),
('2450', 58, 0, 0, 0, 0),
('2450', 59, 0, 0, 0, 0),
('2460', -15, 0, 0, 0, 0),
('2460', -14, 0, 0, 0, 0),
('2460', -13, 0, 0, 0, 0),
('2460', -12, 0, 0, 0, 0),
('2460', -11, 0, 0, 0, 0),
('2460', -10, 0, 0, 0, 0),
('2460', -9, 0, 0, 0, 0),
('2460', -8, 0, 0, 0, 0),
('2460', -7, 0, 0, 0, 0),
('2460', -6, 0, 0, 0, 0),
('2460', -5, 0, 0, 0, 0),
('2460', -4, 0, 0, 0, 0),
('2460', -3, 0, 0, 0, 0),
('2460', -2, 0, 0, 0, 0),
('2460', -1, 0, 0, 0, 0),
('2460', 0, 0, 0, 0, 0),
('2460', 1, 0, 0, 0, 0),
('2460', 2, 0, 0, 0, 0),
('2460', 3, 0, 0, 0, 0),
('2460', 4, 0, 0, 0, 0),
('2460', 5, 0, 0, 0, 0),
('2460', 6, 0, 0, 0, 0),
('2460', 7, 0, 0, 0, 0),
('2460', 8, 0, 0, 0, 0),
('2460', 9, 0, 0, 0, 0),
('2460', 10, 0, 0, 0, 0),
('2460', 11, 0, 0, 0, 0),
('2460', 12, 0, 0, 0, 0),
('2460', 13, 0, 0, 0, 0),
('2460', 14, 0, 0, 0, 0),
('2460', 15, 0, 0, 0, 0),
('2460', 16, 0, 0, 0, 0),
('2460', 17, 0, 0, 0, 0),
('2460', 18, 0, 0, 0, 0),
('2460', 19, 0, 0, 0, 0),
('2460', 20, 0, 0, 0, 0),
('2460', 21, 0, 0, 0, 0),
('2460', 22, 0, 0, 0, 0),
('2460', 23, 0, 0, 0, 0),
('2460', 24, 0, 0, 0, 0),
('2460', 25, 0, 0, 0, 0),
('2460', 26, 0, 0, 0, 0),
('2460', 27, 0, 0, 0, 0),
('2460', 28, 0, 0, 0, 0),
('2460', 29, 0, 0, 0, 0),
('2460', 30, 0, 0, 0, 0),
('2460', 31, 0, 0, 0, 0),
('2460', 32, 0, 0, 0, 0),
('2460', 33, 0, 0, 0, 0),
('2460', 34, 0, 0, 0, 0),
('2460', 35, 0, 0, 0, 0),
('2460', 36, 0, 0, 0, 0),
('2460', 37, 0, 0, 0, 0),
('2460', 38, 0, 0, 0, 0),
('2460', 39, 0, 0, 0, 0),
('2460', 40, 0, 0, 0, 0),
('2460', 41, 0, 0, 0, 0),
('2460', 42, 0, 0, 0, 0),
('2460', 43, 0, 0, 0, 0),
('2460', 44, 0, 0, 0, 0),
('2460', 45, 0, 0, 0, 0),
('2460', 46, 0, 0, 0, 0),
('2460', 47, 0, 0, 0, 0),
('2460', 48, 0, 0, 0, 0),
('2460', 49, 0, 0, 0, 0),
('2460', 50, 0, 0, 0, 0),
('2460', 51, 0, 0, 0, 0),
('2460', 52, 0, 0, 0, 0),
('2460', 53, 0, 0, 0, 0),
('2460', 54, 0, 0, 0, 0),
('2460', 55, 0, 0, 0, 0),
('2460', 56, 0, 0, 0, 0),
('2460', 57, 0, 0, 0, 0),
('2460', 58, 0, 0, 0, 0),
('2460', 59, 0, 0, 0, 0),
('2480', -15, 0, 0, 0, 0),
('2480', -14, 0, 0, 0, 0),
('2480', -13, 0, 0, 0, 0),
('2480', -12, 0, 0, 0, 0),
('2480', -11, 0, 0, 0, 0),
('2480', -10, 0, 0, 0, 0),
('2480', -9, 0, 0, 0, 0),
('2480', -8, 0, 0, 0, 0),
('2480', -7, 0, 0, 0, 0),
('2480', -6, 0, 0, 0, 0),
('2480', -5, 0, 0, 0, 0),
('2480', -4, 0, 0, 0, 0),
('2480', -3, 0, 0, 0, 0),
('2480', -2, 0, 0, 0, 0),
('2480', -1, 0, 0, 0, 0),
('2480', 0, 0, 0, 0, 0),
('2480', 1, 0, 0, 0, 0),
('2480', 2, 0, 0, 0, 0),
('2480', 3, 0, 0, 0, 0),
('2480', 4, 0, 0, 0, 0),
('2480', 5, 0, 0, 0, 0),
('2480', 6, 0, 0, 0, 0),
('2480', 7, 0, 0, 0, 0),
('2480', 8, 0, 0, 0, 0),
('2480', 9, 0, 0, 0, 0),
('2480', 10, 0, 0, 0, 0),
('2480', 11, 0, 0, 0, 0),
('2480', 12, 0, 0, 0, 0),
('2480', 13, 0, 0, 0, 0),
('2480', 14, 0, 0, 0, 0),
('2480', 15, 0, 0, 0, 0),
('2480', 16, 0, 0, 0, 0),
('2480', 17, 0, 0, 0, 0),
('2480', 18, 0, 0, 0, 0),
('2480', 19, 0, 0, 0, 0),
('2480', 20, 0, 0, 0, 0),
('2480', 21, 0, 0, 0, 0),
('2480', 22, 0, 0, 0, 0),
('2480', 23, 0, 0, 0, 0),
('2480', 24, 0, 0, 0, 0),
('2480', 25, 0, 0, 0, 0),
('2480', 26, 0, 0, 0, 0),
('2480', 27, 0, 0, 0, 0),
('2480', 28, 0, 0, 0, 0),
('2480', 29, 0, 0, 0, 0),
('2480', 30, 0, 0, 0, 0),
('2480', 31, 0, 0, 0, 0),
('2480', 32, 0, 0, 0, 0),
('2480', 33, 0, 0, 0, 0),
('2480', 34, 0, 0, 0, 0),
('2480', 35, 0, 0, 0, 0),
('2480', 36, 0, 0, 0, 0),
('2480', 37, 0, 0, 0, 0),
('2480', 38, 0, 0, 0, 0),
('2480', 39, 0, 0, 0, 0),
('2480', 40, 0, 0, 0, 0),
('2480', 41, 0, 0, 0, 0),
('2480', 42, 0, 0, 0, 0),
('2480', 43, 0, 0, 0, 0),
('2480', 44, 0, 0, 0, 0),
('2480', 45, 0, 0, 0, 0),
('2480', 46, 0, 0, 0, 0),
('2480', 47, 0, 0, 0, 0),
('2480', 48, 0, 0, 0, 0),
('2480', 49, 0, 0, 0, 0),
('2480', 50, 0, 0, 0, 0),
('2480', 51, 0, 0, 0, 0),
('2480', 52, 0, 0, 0, 0),
('2480', 53, 0, 0, 0, 0),
('2480', 54, 0, 0, 0, 0),
('2480', 55, 0, 0, 0, 0),
('2480', 56, 0, 0, 0, 0),
('2480', 57, 0, 0, 0, 0),
('2480', 58, 0, 0, 0, 0),
('2480', 59, 0, 0, 0, 0),
('2500', -15, 0, 0, 0, 0),
('2500', -14, 0, 0, 0, 0),
('2500', -13, 0, 0, 0, 0),
('2500', -12, 0, 0, 0, 0),
('2500', -11, 0, 0, 0, 0),
('2500', -10, 0, 0, 0, 0),
('2500', -9, 0, 0, 0, 0),
('2500', -8, 0, 0, 0, 0),
('2500', -7, 0, 0, 0, 0),
('2500', -6, 0, 0, 0, 0),
('2500', -5, 0, 0, 0, 0),
('2500', -4, 0, 0, 0, 0),
('2500', -3, 0, 0, 0, 0),
('2500', -2, 0, 0, 0, 0),
('2500', -1, 0, 0, 0, 0),
('2500', 0, 0, 0, 0, 0),
('2500', 1, 0, 0, 0, 0),
('2500', 2, 0, 0, 0, 0),
('2500', 3, 0, 0, 0, 0),
('2500', 4, 0, 0, 0, 0),
('2500', 5, 0, 0, 0, 0),
('2500', 6, 0, 0, 0, 0),
('2500', 7, 0, 0, 0, 0),
('2500', 8, 0, 0, 0, 0),
('2500', 9, 0, 0, 0, 0),
('2500', 10, 0, 0, 0, 0),
('2500', 11, 0, 0, 0, 0),
('2500', 12, 0, 0, 0, 0),
('2500', 13, 0, 0, 0, 0),
('2500', 14, 0, 0, 0, 0),
('2500', 15, 0, 0, 0, 0),
('2500', 16, 0, 0, 0, 0),
('2500', 17, 0, 0, 0, 0),
('2500', 18, 0, 0, 0, 0),
('2500', 19, 0, 0, 0, 0),
('2500', 20, 0, 0, 0, 0),
('2500', 21, 0, 0, 0, 0),
('2500', 22, 0, 0, 0, 0),
('2500', 23, 0, 0, 0, 0),
('2500', 24, 0, 0, 0, 0),
('2500', 25, 0, 0, 0, 0),
('2500', 26, 0, 0, 0, 0),
('2500', 27, 0, 0, 0, 0),
('2500', 28, 0, 0, 0, 0),
('2500', 29, 0, 0, 0, 0),
('2500', 30, 0, 0, 0, 0),
('2500', 31, 0, 0, 0, 0),
('2500', 32, 0, 0, 0, 0),
('2500', 33, 0, 0, 0, 0),
('2500', 34, 0, 0, 0, 0),
('2500', 35, 0, 0, 0, 0),
('2500', 36, 0, 0, 0, 0),
('2500', 37, 0, 0, 0, 0),
('2500', 38, 0, 0, 0, 0),
('2500', 39, 0, 0, 0, 0),
('2500', 40, 0, 0, 0, 0),
('2500', 41, 0, 0, 0, 0),
('2500', 42, 0, 0, 0, 0),
('2500', 43, 0, 0, 0, 0),
('2500', 44, 0, 0, 0, 0),
('2500', 45, 0, 0, 0, 0),
('2500', 46, 0, 0, 0, 0),
('2500', 47, 0, 0, 0, 0),
('2500', 48, 0, 0, 0, 0),
('2500', 49, 0, 0, 0, 0),
('2500', 50, 0, 0, 0, 0),
('2500', 51, 0, 0, 0, 0),
('2500', 52, 0, 0, 0, 0),
('2500', 53, 0, 0, 0, 0),
('2500', 54, 0, 0, 0, 0),
('2500', 55, 0, 0, 0, 0),
('2500', 56, 0, 0, 0, 0),
('2500', 57, 0, 0, 0, 0),
('2500', 58, 0, 0, 0, 0),
('2500', 59, 0, 0, 0, 0),
('2550', -15, 0, 0, 0, 0),
('2550', -14, 0, 0, 0, 0),
('2550', -13, 0, 0, 0, 0),
('2550', -12, 0, 0, 0, 0),
('2550', -11, 0, 0, 0, 0),
('2550', -10, 0, 0, 0, 0),
('2550', -9, 0, 0, 0, 0),
('2550', -8, 0, 0, 0, 0),
('2550', -7, 0, 0, 0, 0),
('2550', -6, 0, 0, 0, 0),
('2550', -5, 0, 0, 0, 0),
('2550', -4, 0, 0, 0, 0),
('2550', -3, 0, 0, 0, 0),
('2550', -2, 0, 0, 0, 0),
('2550', -1, 0, 0, 0, 0),
('2550', 0, 0, 0, 0, 0),
('2550', 1, 0, 0, 0, 0),
('2550', 2, 0, 0, 0, 0),
('2550', 3, 0, 0, 0, 0),
('2550', 4, 0, 0, 0, 0),
('2550', 5, 0, 0, 0, 0),
('2550', 6, 0, 0, 0, 0),
('2550', 7, 0, 0, 0, 0),
('2550', 8, 0, 0, 0, 0),
('2550', 9, 0, 0, 0, 0),
('2550', 10, 0, 0, 0, 0),
('2550', 11, 0, 0, 0, 0),
('2550', 12, 0, 0, 0, 0),
('2550', 13, 0, 0, 0, 0),
('2550', 14, 0, 0, 0, 0),
('2550', 15, 0, 0, 0, 0),
('2550', 16, 0, 0, 0, 0),
('2550', 17, 0, 0, 0, 0),
('2550', 18, 0, 0, 0, 0),
('2550', 19, 0, 0, 0, 0),
('2550', 20, 0, 0, 0, 0),
('2550', 21, 0, 0, 0, 0),
('2550', 22, 0, 0, 0, 0),
('2550', 23, 0, 0, 0, 0),
('2550', 24, 0, 0, 0, 0),
('2550', 25, 0, 0, 0, 0),
('2550', 26, 0, 0, 0, 0),
('2550', 27, 0, 0, 0, 0),
('2550', 28, 0, 0, 0, 0),
('2550', 29, 0, 0, 0, 0),
('2550', 30, 0, 0, 0, 0),
('2550', 31, 0, 0, 0, 0),
('2550', 32, 0, 0, 0, 0),
('2550', 33, 0, 0, 0, 0),
('2550', 34, 0, 0, 0, 0),
('2550', 35, 0, 0, 0, 0),
('2550', 36, 0, 0, 0, 0),
('2550', 37, 0, 0, 0, 0),
('2550', 38, 0, 0, 0, 0),
('2550', 39, 0, 0, 0, 0),
('2550', 40, 0, 0, 0, 0),
('2550', 41, 0, 0, 0, 0),
('2550', 42, 0, 0, 0, 0),
('2550', 43, 0, 0, 0, 0),
('2550', 44, 0, 0, 0, 0),
('2550', 45, 0, 0, 0, 0),
('2550', 46, 0, 0, 0, 0),
('2550', 47, 0, 0, 0, 0),
('2550', 48, 0, 0, 0, 0),
('2550', 49, 0, 0, 0, 0),
('2550', 50, 0, 0, 0, 0),
('2550', 51, 0, 0, 0, 0),
('2550', 52, 0, 0, 0, 0),
('2550', 53, 0, 0, 0, 0),
('2550', 54, 0, 0, 0, 0),
('2550', 55, 0, 0, 0, 0),
('2550', 56, 0, 0, 0, 0),
('2550', 57, 0, 0, 0, 0),
('2550', 58, 0, 0, 0, 0),
('2550', 59, 0, 0, 0, 0),
('2560', -15, 0, 0, 0, 0),
('2560', -14, 0, 0, 0, 0),
('2560', -13, 0, 0, 0, 0),
('2560', -12, 0, 0, 0, 0),
('2560', -11, 0, 0, 0, 0),
('2560', -10, 0, 0, 0, 0),
('2560', -9, 0, 0, 0, 0),
('2560', -8, 0, 0, 0, 0),
('2560', -7, 0, 0, 0, 0),
('2560', -6, 0, 0, 0, 0),
('2560', -5, 0, 0, 0, 0),
('2560', -4, 0, 0, 0, 0),
('2560', -3, 0, 0, 0, 0),
('2560', -2, 0, 0, 0, 0),
('2560', -1, 0, 0, 0, 0),
('2560', 0, 0, 0, 0, 0),
('2560', 1, 0, 0, 0, 0),
('2560', 2, 0, 0, 0, 0),
('2560', 3, 0, 0, 0, 0),
('2560', 4, 0, 0, 0, 0),
('2560', 5, 0, 0, 0, 0),
('2560', 6, 0, 0, 0, 0),
('2560', 7, 0, 0, 0, 0),
('2560', 8, 0, 0, 0, 0),
('2560', 9, 0, 0, 0, 0),
('2560', 10, 0, 0, 0, 0),
('2560', 11, 0, 0, 0, 0),
('2560', 12, 0, 0, 0, 0),
('2560', 13, 0, 0, 0, 0),
('2560', 14, 0, 0, 0, 0),
('2560', 15, 0, 0, 0, 0),
('2560', 16, 0, 0, 0, 0),
('2560', 17, 0, 0, 0, 0),
('2560', 18, 0, 0, 0, 0),
('2560', 19, 0, 0, 0, 0),
('2560', 20, 0, 0, 0, 0),
('2560', 21, 0, 0, 0, 0),
('2560', 22, 0, 0, 0, 0),
('2560', 23, 0, 0, 0, 0),
('2560', 24, 0, 0, 0, 0),
('2560', 25, 0, 0, 0, 0),
('2560', 26, 0, 0, 0, 0),
('2560', 27, 0, 0, 0, 0),
('2560', 28, 0, 0, 0, 0),
('2560', 29, 0, 0, 0, 0),
('2560', 30, 0, 0, 0, 0),
('2560', 31, 0, 0, 0, 0),
('2560', 32, 0, 0, 0, 0),
('2560', 33, 0, 0, 0, 0),
('2560', 34, 0, 0, 0, 0),
('2560', 35, 0, 0, 0, 0),
('2560', 36, 0, 0, 0, 0),
('2560', 37, 0, 0, 0, 0),
('2560', 38, 0, 0, 0, 0),
('2560', 39, 0, 0, 0, 0),
('2560', 40, 0, 0, 0, 0),
('2560', 41, 0, 0, 0, 0),
('2560', 42, 0, 0, 0, 0),
('2560', 43, 0, 0, 0, 0),
('2560', 44, 0, 0, 0, 0),
('2560', 45, 0, 0, 0, 0),
('2560', 46, 0, 0, 0, 0),
('2560', 47, 0, 0, 0, 0),
('2560', 48, 0, 0, 0, 0),
('2560', 49, 0, 0, 0, 0),
('2560', 50, 0, 0, 0, 0),
('2560', 51, 0, 0, 0, 0),
('2560', 52, 0, 0, 0, 0),
('2560', 53, 0, 0, 0, 0),
('2560', 54, 0, 0, 0, 0),
('2560', 55, 0, 0, 0, 0),
('2560', 56, 0, 0, 0, 0),
('2560', 57, 0, 0, 0, 0),
('2560', 58, 0, 0, 0, 0),
('2560', 59, 0, 0, 0, 0),
('2600', -15, 0, 0, 0, 0),
('2600', -14, 0, 0, 0, 0),
('2600', -13, 0, 0, 0, 0),
('2600', -12, 0, 0, 0, 0),
('2600', -11, 0, 0, 0, 0),
('2600', -10, 0, 0, 0, 0),
('2600', -9, 0, 0, 0, 0),
('2600', -8, 0, 0, 0, 0),
('2600', -7, 0, 0, 0, 0),
('2600', -6, 0, 0, 0, 0),
('2600', -5, 0, 0, 0, 0),
('2600', -4, 0, 0, 0, 0),
('2600', -3, 0, 0, 0, 0),
('2600', -2, 0, 0, 0, 0),
('2600', -1, 0, 0, 0, 0),
('2600', 0, 0, 0, 0, 0),
('2600', 1, 0, 0, 0, 0),
('2600', 2, 0, 0, 0, 0),
('2600', 3, 0, 0, 0, 0),
('2600', 4, 0, 0, 0, 0),
('2600', 5, 0, 0, 0, 0),
('2600', 6, 0, 0, 0, 0),
('2600', 7, 0, 0, 0, 0),
('2600', 8, 0, 0, 0, 0),
('2600', 9, 0, 0, 0, 0),
('2600', 10, 0, 0, 0, 0),
('2600', 11, 0, 0, 0, 0),
('2600', 12, 0, 0, 0, 0),
('2600', 13, 0, 0, 0, 0),
('2600', 14, 0, 0, 0, 0),
('2600', 15, 0, 0, 0, 0),
('2600', 16, 0, 0, 0, 0),
('2600', 17, 0, 0, 0, 0),
('2600', 18, 0, 0, 0, 0),
('2600', 19, 0, 0, 0, 0),
('2600', 20, 0, 0, 0, 0),
('2600', 21, 0, 0, 0, 0),
('2600', 22, 0, 0, 0, 0),
('2600', 23, 0, 0, 0, 0),
('2600', 24, 0, 0, 0, 0),
('2600', 25, 0, 0, 0, 0),
('2600', 26, 0, 0, 0, 0),
('2600', 27, 0, 0, 0, 0),
('2600', 28, 0, 0, 0, 0),
('2600', 29, 0, 0, 0, 0),
('2600', 30, 0, 0, 0, 0),
('2600', 31, 0, 0, 0, 0),
('2600', 32, 0, 0, 0, 0),
('2600', 33, 0, 0, 0, 0),
('2600', 34, 0, 0, 0, 0),
('2600', 35, 0, 0, 0, 0),
('2600', 36, 0, 0, 0, 0),
('2600', 37, 0, 0, 0, 0),
('2600', 38, 0, 0, 0, 0),
('2600', 39, 0, 0, 0, 0),
('2600', 40, 0, 0, 0, 0),
('2600', 41, 0, 0, 0, 0),
('2600', 42, 0, 0, 0, 0),
('2600', 43, 0, 0, 0, 0),
('2600', 44, 0, 0, 0, 0),
('2600', 45, 0, 0, 0, 0),
('2600', 46, 0, 0, 0, 0),
('2600', 47, 0, 0, 0, 0),
('2600', 48, 0, 0, 0, 0),
('2600', 49, 0, 0, 0, 0),
('2600', 50, 0, 0, 0, 0),
('2600', 51, 0, 0, 0, 0),
('2600', 52, 0, 0, 0, 0),
('2600', 53, 0, 0, 0, 0),
('2600', 54, 0, 0, 0, 0),
('2600', 55, 0, 0, 0, 0),
('2600', 56, 0, 0, 0, 0),
('2600', 57, 0, 0, 0, 0),
('2600', 58, 0, 0, 0, 0),
('2600', 59, 0, 0, 0, 0),
('2700', -15, 0, 0, 0, 0),
('2700', -14, 0, 0, 0, 0),
('2700', -13, 0, 0, 0, 0),
('2700', -12, 0, 0, 0, 0),
('2700', -11, 0, 0, 0, 0),
('2700', -10, 0, 0, 0, 0),
('2700', -9, 0, 0, 0, 0),
('2700', -8, 0, 0, 0, 0),
('2700', -7, 0, 0, 0, 0),
('2700', -6, 0, 0, 0, 0),
('2700', -5, 0, 0, 0, 0),
('2700', -4, 0, 0, 0, 0),
('2700', -3, 0, 0, 0, 0),
('2700', -2, 0, 0, 0, 0),
('2700', -1, 0, 0, 0, 0),
('2700', 0, 0, 0, 0, 0),
('2700', 1, 0, 0, 0, 0),
('2700', 2, 0, 0, 0, 0),
('2700', 3, 0, 0, 0, 0),
('2700', 4, 0, 0, 0, 0),
('2700', 5, 0, 0, 0, 0),
('2700', 6, 0, 0, 0, 0),
('2700', 7, 0, 0, 0, 0),
('2700', 8, 0, 0, 0, 0),
('2700', 9, 0, 0, 0, 0),
('2700', 10, 0, 0, 0, 0),
('2700', 11, 0, 0, 0, 0),
('2700', 12, 0, 0, 0, 0),
('2700', 13, 0, 0, 0, 0),
('2700', 14, 0, 0, 0, 0),
('2700', 15, 0, 0, 0, 0),
('2700', 16, 0, 0, 0, 0),
('2700', 17, 0, 0, 0, 0),
('2700', 18, 0, 0, 0, 0),
('2700', 19, 0, 0, 0, 0),
('2700', 20, 0, 0, 0, 0),
('2700', 21, 0, 0, 0, 0),
('2700', 22, 0, 0, 0, 0),
('2700', 23, 0, 0, 0, 0),
('2700', 24, 0, 0, 0, 0),
('2700', 25, 0, 0, 0, 0),
('2700', 26, 0, 0, 0, 0),
('2700', 27, 0, 0, 0, 0),
('2700', 28, 0, 0, 0, 0),
('2700', 29, 0, 0, 0, 0),
('2700', 30, 0, 0, 0, 0),
('2700', 31, 0, 0, 0, 0),
('2700', 32, 0, 0, 0, 0),
('2700', 33, 0, 0, 0, 0),
('2700', 34, 0, 0, 0, 0),
('2700', 35, 0, 0, 0, 0),
('2700', 36, 0, 0, 0, 0),
('2700', 37, 0, 0, 0, 0),
('2700', 38, 0, 0, 0, 0),
('2700', 39, 0, 0, 0, 0),
('2700', 40, 0, 0, 0, 0),
('2700', 41, 0, 0, 0, 0),
('2700', 42, 0, 0, 0, 0),
('2700', 43, 0, 0, 0, 0),
('2700', 44, 0, 0, 0, 0),
('2700', 45, 0, 0, 0, 0),
('2700', 46, 0, 0, 0, 0),
('2700', 47, 0, 0, 0, 0),
('2700', 48, 0, 0, 0, 0),
('2700', 49, 0, 0, 0, 0),
('2700', 50, 0, 0, 0, 0),
('2700', 51, 0, 0, 0, 0),
('2700', 52, 0, 0, 0, 0),
('2700', 53, 0, 0, 0, 0),
('2700', 54, 0, 0, 0, 0),
('2700', 55, 0, 0, 0, 0),
('2700', 56, 0, 0, 0, 0),
('2700', 57, 0, 0, 0, 0),
('2700', 58, 0, 0, 0, 0),
('2700', 59, 0, 0, 0, 0),
('2720', -15, 0, 0, 0, 0),
('2720', -14, 0, 0, 0, 0),
('2720', -13, 0, 0, 0, 0),
('2720', -12, 0, 0, 0, 0),
('2720', -11, 0, 0, 0, 0),
('2720', -10, 0, 0, 0, 0),
('2720', -9, 0, 0, 0, 0),
('2720', -8, 0, 0, 0, 0),
('2720', -7, 0, 0, 0, 0),
('2720', -6, 0, 0, 0, 0),
('2720', -5, 0, 0, 0, 0),
('2720', -4, 0, 0, 0, 0),
('2720', -3, 0, 0, 0, 0),
('2720', -2, 0, 0, 0, 0),
('2720', -1, 0, 0, 0, 0),
('2720', 0, 0, 0, 0, 0),
('2720', 1, 0, 0, 0, 0),
('2720', 2, 0, 0, 0, 0),
('2720', 3, 0, 0, 0, 0),
('2720', 4, 0, 0, 0, 0),
('2720', 5, 0, 0, 0, 0),
('2720', 6, 0, 0, 0, 0),
('2720', 7, 0, 0, 0, 0),
('2720', 8, 0, 0, 0, 0),
('2720', 9, 0, 0, 0, 0),
('2720', 10, 0, 0, 0, 0),
('2720', 11, 0, 0, 0, 0),
('2720', 12, 0, 0, 0, 0),
('2720', 13, 0, 0, 0, 0),
('2720', 14, 0, 0, 0, 0),
('2720', 15, 0, 0, 0, 0),
('2720', 16, 0, 0, 0, 0),
('2720', 17, 0, 0, 0, 0),
('2720', 18, 0, 0, 0, 0),
('2720', 19, 0, 0, 0, 0),
('2720', 20, 0, 0, 0, 0),
('2720', 21, 0, 0, 0, 0),
('2720', 22, 0, 0, 0, 0),
('2720', 23, 0, 0, 0, 0),
('2720', 24, 0, 0, 0, 0),
('2720', 25, 0, 0, 0, 0),
('2720', 26, 0, 0, 0, 0),
('2720', 27, 0, 0, 0, 0),
('2720', 28, 0, 0, 0, 0),
('2720', 29, 0, 0, 0, 0),
('2720', 30, 0, 0, 0, 0),
('2720', 31, 0, 0, 0, 0),
('2720', 32, 0, 0, 0, 0),
('2720', 33, 0, 0, 0, 0),
('2720', 34, 0, 0, 0, 0),
('2720', 35, 0, 0, 0, 0),
('2720', 36, 0, 0, 0, 0),
('2720', 37, 0, 0, 0, 0),
('2720', 38, 0, 0, 0, 0),
('2720', 39, 0, 0, 0, 0),
('2720', 40, 0, 0, 0, 0),
('2720', 41, 0, 0, 0, 0),
('2720', 42, 0, 0, 0, 0),
('2720', 43, 0, 0, 0, 0),
('2720', 44, 0, 0, 0, 0),
('2720', 45, 0, 0, 0, 0),
('2720', 46, 0, 0, 0, 0),
('2720', 47, 0, 0, 0, 0),
('2720', 48, 0, 0, 0, 0),
('2720', 49, 0, 0, 0, 0),
('2720', 50, 0, 0, 0, 0),
('2720', 51, 0, 0, 0, 0),
('2720', 52, 0, 0, 0, 0),
('2720', 53, 0, 0, 0, 0),
('2720', 54, 0, 0, 0, 0),
('2720', 55, 0, 0, 0, 0),
('2720', 56, 0, 0, 0, 0),
('2720', 57, 0, 0, 0, 0),
('2720', 58, 0, 0, 0, 0),
('2720', 59, 0, 0, 0, 0),
('2740', -15, 0, 0, 0, 0),
('2740', -14, 0, 0, 0, 0),
('2740', -13, 0, 0, 0, 0),
('2740', -12, 0, 0, 0, 0),
('2740', -11, 0, 0, 0, 0),
('2740', -10, 0, 0, 0, 0),
('2740', -9, 0, 0, 0, 0),
('2740', -8, 0, 0, 0, 0),
('2740', -7, 0, 0, 0, 0),
('2740', -6, 0, 0, 0, 0),
('2740', -5, 0, 0, 0, 0),
('2740', -4, 0, 0, 0, 0),
('2740', -3, 0, 0, 0, 0),
('2740', -2, 0, 0, 0, 0),
('2740', -1, 0, 0, 0, 0),
('2740', 0, 0, 0, 0, 0),
('2740', 1, 0, 0, 0, 0),
('2740', 2, 0, 0, 0, 0),
('2740', 3, 0, 0, 0, 0),
('2740', 4, 0, 0, 0, 0),
('2740', 5, 0, 0, 0, 0),
('2740', 6, 0, 0, 0, 0),
('2740', 7, 0, 0, 0, 0),
('2740', 8, 0, 0, 0, 0),
('2740', 9, 0, 0, 0, 0),
('2740', 10, 0, 0, 0, 0),
('2740', 11, 0, 0, 0, 0),
('2740', 12, 0, 0, 0, 0),
('2740', 13, 0, 0, 0, 0),
('2740', 14, 0, 0, 0, 0),
('2740', 15, 0, 0, 0, 0),
('2740', 16, 0, 0, 0, 0),
('2740', 17, 0, 0, 0, 0),
('2740', 18, 0, 0, 0, 0),
('2740', 19, 0, 0, 0, 0),
('2740', 20, 0, 0, 0, 0),
('2740', 21, 0, 0, 0, 0),
('2740', 22, 0, 0, 0, 0),
('2740', 23, 0, 0, 0, 0),
('2740', 24, 0, 0, 0, 0),
('2740', 25, 0, 0, 0, 0),
('2740', 26, 0, 0, 0, 0),
('2740', 27, 0, 0, 0, 0),
('2740', 28, 0, 0, 0, 0),
('2740', 29, 0, 0, 0, 0),
('2740', 30, 0, 0, 0, 0),
('2740', 31, 0, 0, 0, 0),
('2740', 32, 0, 0, 0, 0),
('2740', 33, 0, 0, 0, 0),
('2740', 34, 0, 0, 0, 0),
('2740', 35, 0, 0, 0, 0),
('2740', 36, 0, 0, 0, 0),
('2740', 37, 0, 0, 0, 0),
('2740', 38, 0, 0, 0, 0),
('2740', 39, 0, 0, 0, 0),
('2740', 40, 0, 0, 0, 0),
('2740', 41, 0, 0, 0, 0),
('2740', 42, 0, 0, 0, 0),
('2740', 43, 0, 0, 0, 0),
('2740', 44, 0, 0, 0, 0),
('2740', 45, 0, 0, 0, 0),
('2740', 46, 0, 0, 0, 0),
('2740', 47, 0, 0, 0, 0),
('2740', 48, 0, 0, 0, 0),
('2740', 49, 0, 0, 0, 0),
('2740', 50, 0, 0, 0, 0),
('2740', 51, 0, 0, 0, 0),
('2740', 52, 0, 0, 0, 0),
('2740', 53, 0, 0, 0, 0),
('2740', 54, 0, 0, 0, 0),
('2740', 55, 0, 0, 0, 0),
('2740', 56, 0, 0, 0, 0),
('2740', 57, 0, 0, 0, 0),
('2740', 58, 0, 0, 0, 0),
('2740', 59, 0, 0, 0, 0),
('2760', -15, 0, 0, 0, 0),
('2760', -14, 0, 0, 0, 0),
('2760', -13, 0, 0, 0, 0),
('2760', -12, 0, 0, 0, 0),
('2760', -11, 0, 0, 0, 0),
('2760', -10, 0, 0, 0, 0),
('2760', -9, 0, 0, 0, 0),
('2760', -8, 0, 0, 0, 0),
('2760', -7, 0, 0, 0, 0),
('2760', -6, 0, 0, 0, 0),
('2760', -5, 0, 0, 0, 0),
('2760', -4, 0, 0, 0, 0),
('2760', -3, 0, 0, 0, 0),
('2760', -2, 0, 0, 0, 0),
('2760', -1, 0, 0, 0, 0),
('2760', 0, 0, 0, 0, 0),
('2760', 1, 0, 0, 0, 0),
('2760', 2, 0, 0, 0, 0),
('2760', 3, 0, 0, 0, 0),
('2760', 4, 0, 0, 0, 0),
('2760', 5, 0, 0, 0, 0),
('2760', 6, 0, 0, 0, 0),
('2760', 7, 0, 0, 0, 0),
('2760', 8, 0, 0, 0, 0),
('2760', 9, 0, 0, 0, 0),
('2760', 10, 0, 0, 0, 0),
('2760', 11, 0, 0, 0, 0),
('2760', 12, 0, 0, 0, 0),
('2760', 13, 0, 0, 0, 0),
('2760', 14, 0, 0, 0, 0),
('2760', 15, 0, 0, 0, 0),
('2760', 16, 0, 0, 0, 0),
('2760', 17, 0, 0, 0, 0),
('2760', 18, 0, 0, 0, 0),
('2760', 19, 0, 0, 0, 0),
('2760', 20, 0, 0, 0, 0),
('2760', 21, 0, 0, 0, 0),
('2760', 22, 0, 0, 0, 0),
('2760', 23, 0, 0, 0, 0),
('2760', 24, 0, 0, 0, 0),
('2760', 25, 0, 0, 0, 0),
('2760', 26, 0, 0, 0, 0),
('2760', 27, 0, 0, 0, 0),
('2760', 28, 0, 0, 0, 0),
('2760', 29, 0, 0, 0, 0),
('2760', 30, 0, 0, 0, 0),
('2760', 31, 0, 0, 0, 0),
('2760', 32, 0, 0, 0, 0),
('2760', 33, 0, 0, 0, 0),
('2760', 34, 0, 0, 0, 0),
('2760', 35, 0, 0, 0, 0),
('2760', 36, 0, 0, 0, 0),
('2760', 37, 0, 0, 0, 0),
('2760', 38, 0, 0, 0, 0),
('2760', 39, 0, 0, 0, 0),
('2760', 40, 0, 0, 0, 0),
('2760', 41, 0, 0, 0, 0),
('2760', 42, 0, 0, 0, 0),
('2760', 43, 0, 0, 0, 0),
('2760', 44, 0, 0, 0, 0),
('2760', 45, 0, 0, 0, 0),
('2760', 46, 0, 0, 0, 0),
('2760', 47, 0, 0, 0, 0),
('2760', 48, 0, 0, 0, 0),
('2760', 49, 0, 0, 0, 0),
('2760', 50, 0, 0, 0, 0),
('2760', 51, 0, 0, 0, 0),
('2760', 52, 0, 0, 0, 0),
('2760', 53, 0, 0, 0, 0),
('2760', 54, 0, 0, 0, 0),
('2760', 55, 0, 0, 0, 0),
('2760', 56, 0, 0, 0, 0),
('2760', 57, 0, 0, 0, 0),
('2760', 58, 0, 0, 0, 0),
('2760', 59, 0, 0, 0, 0),
('2800', -15, 0, 0, 0, 0),
('2800', -14, 0, 0, 0, 0),
('2800', -13, 0, 0, 0, 0),
('2800', -12, 0, 0, 0, 0),
('2800', -11, 0, 0, 0, 0),
('2800', -10, 0, 0, 0, 0),
('2800', -9, 0, 0, 0, 0),
('2800', -8, 0, 0, 0, 0),
('2800', -7, 0, 0, 0, 0),
('2800', -6, 0, 0, 0, 0),
('2800', -5, 0, 0, 0, 0),
('2800', -4, 0, 0, 0, 0),
('2800', -3, 0, 0, 0, 0),
('2800', -2, 0, 0, 0, 0),
('2800', -1, 0, 0, 0, 0),
('2800', 0, 0, 0, 0, 0),
('2800', 1, 0, 0, 0, 0),
('2800', 2, 0, 0, 0, 0),
('2800', 3, 0, 0, 0, 0),
('2800', 4, 0, 0, 0, 0),
('2800', 5, 0, 0, 0, 0),
('2800', 6, 0, 0, 0, 0),
('2800', 7, 0, 0, 0, 0),
('2800', 8, 0, 0, 0, 0),
('2800', 9, 0, 0, 0, 0),
('2800', 10, 0, 0, 0, 0),
('2800', 11, 0, 0, 0, 0),
('2800', 12, 0, 0, 0, 0),
('2800', 13, 0, 0, 0, 0),
('2800', 14, 0, 0, 0, 0),
('2800', 15, 0, 0, 0, 0),
('2800', 16, 0, 0, 0, 0),
('2800', 17, 0, 0, 0, 0),
('2800', 18, 0, 0, 0, 0),
('2800', 19, 0, 0, 0, 0),
('2800', 20, 0, 0, 0, 0),
('2800', 21, 0, 0, 0, 0),
('2800', 22, 0, 0, 0, 0),
('2800', 23, 0, 0, 0, 0),
('2800', 24, 0, 0, 0, 0),
('2800', 25, 0, 0, 0, 0),
('2800', 26, 0, 0, 0, 0),
('2800', 27, 0, 0, 0, 0),
('2800', 28, 0, 0, 0, 0),
('2800', 29, 0, 0, 0, 0),
('2800', 30, 0, 0, 0, 0),
('2800', 31, 0, 0, 0, 0),
('2800', 32, 0, 0, 0, 0),
('2800', 33, 0, 0, 0, 0),
('2800', 34, 0, 0, 0, 0),
('2800', 35, 0, 0, 0, 0),
('2800', 36, 0, 0, 0, 0),
('2800', 37, 0, 0, 0, 0),
('2800', 38, 0, 0, 0, 0),
('2800', 39, 0, 0, 0, 0),
('2800', 40, 0, 0, 0, 0),
('2800', 41, 0, 0, 0, 0),
('2800', 42, 0, 0, 0, 0),
('2800', 43, 0, 0, 0, 0),
('2800', 44, 0, 0, 0, 0),
('2800', 45, 0, 0, 0, 0),
('2800', 46, 0, 0, 0, 0),
('2800', 47, 0, 0, 0, 0),
('2800', 48, 0, 0, 0, 0),
('2800', 49, 0, 0, 0, 0),
('2800', 50, 0, 0, 0, 0),
('2800', 51, 0, 0, 0, 0),
('2800', 52, 0, 0, 0, 0),
('2800', 53, 0, 0, 0, 0),
('2800', 54, 0, 0, 0, 0),
('2800', 55, 0, 0, 0, 0),
('2800', 56, 0, 0, 0, 0),
('2800', 57, 0, 0, 0, 0),
('2800', 58, 0, 0, 0, 0),
('2800', 59, 0, 0, 0, 0),
('2900', -15, 0, 0, 0, 0),
('2900', -14, 0, 0, 0, 0),
('2900', -13, 0, 0, 0, 0),
('2900', -12, 0, 0, 0, 0),
('2900', -11, 0, 0, 0, 0),
('2900', -10, 0, 0, 0, 0),
('2900', -9, 0, 0, 0, 0),
('2900', -8, 0, 0, 0, 0),
('2900', -7, 0, 0, 0, 0),
('2900', -6, 0, 0, 0, 0),
('2900', -5, 0, 0, 0, 0),
('2900', -4, 0, 0, 0, 0),
('2900', -3, 0, 0, 0, 0),
('2900', -2, 0, 0, 0, 0),
('2900', -1, 0, 0, 0, 0),
('2900', 0, 0, 0, 0, 0),
('2900', 1, 0, 0, 0, 0),
('2900', 2, 0, 0, 0, 0),
('2900', 3, 0, 0, 0, 0),
('2900', 4, 0, 0, 0, 0),
('2900', 5, 0, 0, 0, 0),
('2900', 6, 0, 0, 0, 0),
('2900', 7, 0, 0, 0, 0),
('2900', 8, 0, 0, 0, 0),
('2900', 9, 0, 0, 0, 0),
('2900', 10, 0, 0, 0, 0),
('2900', 11, 0, 0, 0, 0),
('2900', 12, 0, 0, 0, 0),
('2900', 13, 0, 0, 0, 0),
('2900', 14, 0, 0, 0, 0),
('2900', 15, 0, 0, 0, 0),
('2900', 16, 0, 0, 0, 0),
('2900', 17, 0, 0, 0, 0),
('2900', 18, 0, 0, 0, 0),
('2900', 19, 0, 0, 0, 0),
('2900', 20, 0, 0, 0, 0),
('2900', 21, 0, 0, 0, 0),
('2900', 22, 0, 0, 0, 0),
('2900', 23, 0, 0, 0, 0),
('2900', 24, 0, 0, 0, 0),
('2900', 25, 0, 0, 0, 0),
('2900', 26, 0, 0, 0, 0),
('2900', 27, 0, 0, 0, 0),
('2900', 28, 0, 0, 0, 0),
('2900', 29, 0, 0, 0, 0),
('2900', 30, 0, 0, 0, 0),
('2900', 31, 0, 0, 0, 0),
('2900', 32, 0, 0, 0, 0),
('2900', 33, 0, 0, 0, 0),
('2900', 34, 0, 0, 0, 0),
('2900', 35, 0, 0, 0, 0),
('2900', 36, 0, 0, 0, 0),
('2900', 37, 0, 0, 0, 0),
('2900', 38, 0, 0, 0, 0),
('2900', 39, 0, 0, 0, 0),
('2900', 40, 0, 0, 0, 0),
('2900', 41, 0, 0, 0, 0),
('2900', 42, 0, 0, 0, 0),
('2900', 43, 0, 0, 0, 0),
('2900', 44, 0, 0, 0, 0),
('2900', 45, 0, 0, 0, 0),
('2900', 46, 0, 0, 0, 0),
('2900', 47, 0, 0, 0, 0),
('2900', 48, 0, 0, 0, 0),
('2900', 49, 0, 0, 0, 0),
('2900', 50, 0, 0, 0, 0),
('2900', 51, 0, 0, 0, 0),
('2900', 52, 0, 0, 0, 0),
('2900', 53, 0, 0, 0, 0),
('2900', 54, 0, 0, 0, 0),
('2900', 55, 0, 0, 0, 0),
('2900', 56, 0, 0, 0, 0),
('2900', 57, 0, 0, 0, 0),
('2900', 58, 0, 0, 0, 0),
('2900', 59, 0, 0, 0, 0),
('3100', -15, 0, 0, 0, 0),
('3100', -14, 0, 0, 0, 0),
('3100', -13, 0, 0, 0, 0),
('3100', -12, 0, 0, 0, 0),
('3100', -11, 0, 0, 0, 0),
('3100', -10, 0, 0, 0, 0),
('3100', -9, 0, 0, 0, 0),
('3100', -8, 0, 0, 0, 0),
('3100', -7, 0, 0, 0, 0),
('3100', -6, 0, 0, 0, 0),
('3100', -5, 0, 0, 0, 0),
('3100', -4, 0, 0, 0, 0),
('3100', -3, 0, 0, 0, 0),
('3100', -2, 0, 0, 0, 0),
('3100', -1, 0, 0, 0, 0),
('3100', 0, 0, 0, 0, 0),
('3100', 1, 0, 0, 0, 0),
('3100', 2, 0, 0, 0, 0),
('3100', 3, 0, 0, 0, 0),
('3100', 4, 0, 0, 0, 0),
('3100', 5, 0, 0, 0, 0),
('3100', 6, 0, 0, 0, 0),
('3100', 7, 0, 0, 0, 0),
('3100', 8, 0, 0, 0, 0),
('3100', 9, 0, 0, 0, 0),
('3100', 10, 0, 0, 0, 0),
('3100', 11, 0, 0, 0, 0),
('3100', 12, 0, 0, 0, 0),
('3100', 13, 0, 0, 0, 0),
('3100', 14, 0, 0, 0, 0),
('3100', 15, 0, 0, 0, 0),
('3100', 16, 0, 0, 0, 0),
('3100', 17, 0, 0, 0, 0),
('3100', 18, 0, 0, 0, 0),
('3100', 19, 0, 0, 0, 0),
('3100', 20, 0, 0, 0, 0),
('3100', 21, 0, 0, 0, 0),
('3100', 22, 0, 0, 0, 0),
('3100', 23, 0, 0, 0, 0),
('3100', 24, 0, 0, 0, 0),
('3100', 25, 0, 0, 0, 0),
('3100', 26, 0, 0, 0, 0),
('3100', 27, 0, 0, 0, 0),
('3100', 28, 0, 0, 0, 0),
('3100', 29, 0, 0, 0, 0),
('3100', 30, 0, 0, 0, 0),
('3100', 31, 0, 0, 0, 0),
('3100', 32, 0, 0, 0, 0),
('3100', 33, 0, 0, 0, 0),
('3100', 34, 0, 0, 0, 0),
('3100', 35, 0, 0, 0, 0),
('3100', 36, 0, 0, 0, 0),
('3100', 37, 0, 0, 0, 0),
('3100', 38, 0, 0, 0, 0),
('3100', 39, 0, 0, 0, 0),
('3100', 40, 0, 0, 0, 0),
('3100', 41, 0, 0, 0, 0),
('3100', 42, 0, 0, 0, 0),
('3100', 43, 0, 0, 0, 0),
('3100', 44, 0, 0, 0, 0),
('3100', 45, 0, 0, 0, 0),
('3100', 46, 0, 0, 0, 0),
('3100', 47, 0, 0, 0, 0),
('3100', 48, 0, 0, 0, 0),
('3100', 49, 0, 0, 0, 0),
('3100', 50, 0, 0, 0, 0),
('3100', 51, 0, 0, 0, 0),
('3100', 52, 0, 0, 0, 0),
('3100', 53, 0, 0, 0, 0),
('3100', 54, 0, 0, 0, 0),
('3100', 55, 0, 0, 0, 0),
('3100', 56, 0, 0, 0, 0),
('3100', 57, 0, 0, 0, 0),
('3100', 58, 0, 0, 0, 0),
('3100', 59, 0, 0, 0, 0),
('3200', -15, 0, 0, 0, 0),
('3200', -14, 0, 0, 0, 0),
('3200', -13, 0, 0, 0, 0),
('3200', -12, 0, 0, 0, 0),
('3200', -11, 0, 0, 0, 0),
('3200', -10, 0, 0, 0, 0),
('3200', -9, 0, 0, 0, 0),
('3200', -8, 0, 0, 0, 0),
('3200', -7, 0, 0, 0, 0),
('3200', -6, 0, 0, 0, 0),
('3200', -5, 0, 0, 0, 0),
('3200', -4, 0, 0, 0, 0),
('3200', -3, 0, 0, 0, 0),
('3200', -2, 0, 0, 0, 0),
('3200', -1, 0, 0, 0, 0),
('3200', 0, 0, 0, 0, 0),
('3200', 1, 0, 0, 0, 0),
('3200', 2, 0, 0, 0, 0),
('3200', 3, 0, 0, 0, 0),
('3200', 4, 0, 0, 0, 0),
('3200', 5, 0, 0, 0, 0),
('3200', 6, 0, 0, 0, 0),
('3200', 7, 0, 0, 0, 0),
('3200', 8, 0, 0, 0, 0),
('3200', 9, 0, 0, 0, 0),
('3200', 10, 0, 0, 0, 0),
('3200', 11, 0, 0, 0, 0),
('3200', 12, 0, 0, 0, 0),
('3200', 13, 0, 0, 0, 0),
('3200', 14, 0, 0, 0, 0),
('3200', 15, 0, 0, 0, 0),
('3200', 16, 0, 0, 0, 0),
('3200', 17, 0, 0, 0, 0),
('3200', 18, 0, 0, 0, 0),
('3200', 19, 0, 0, 0, 0),
('3200', 20, 0, 0, 0, 0),
('3200', 21, 0, 0, 0, 0),
('3200', 22, 0, 0, 0, 0),
('3200', 23, 0, 0, 0, 0),
('3200', 24, 0, 0, 0, 0),
('3200', 25, 0, 0, 0, 0),
('3200', 26, 0, 0, 0, 0),
('3200', 27, 0, 0, 0, 0),
('3200', 28, 0, 0, 0, 0),
('3200', 29, 0, 0, 0, 0),
('3200', 30, 0, 0, 0, 0),
('3200', 31, 0, 0, 0, 0),
('3200', 32, 0, 0, 0, 0),
('3200', 33, 0, 0, 0, 0),
('3200', 34, 0, 0, 0, 0),
('3200', 35, 0, 0, 0, 0),
('3200', 36, 0, 0, 0, 0),
('3200', 37, 0, 0, 0, 0),
('3200', 38, 0, 0, 0, 0),
('3200', 39, 0, 0, 0, 0),
('3200', 40, 0, 0, 0, 0),
('3200', 41, 0, 0, 0, 0),
('3200', 42, 0, 0, 0, 0),
('3200', 43, 0, 0, 0, 0),
('3200', 44, 0, 0, 0, 0),
('3200', 45, 0, 0, 0, 0),
('3200', 46, 0, 0, 0, 0),
('3200', 47, 0, 0, 0, 0),
('3200', 48, 0, 0, 0, 0),
('3200', 49, 0, 0, 0, 0),
('3200', 50, 0, 0, 0, 0),
('3200', 51, 0, 0, 0, 0),
('3200', 52, 0, 0, 0, 0),
('3200', 53, 0, 0, 0, 0),
('3200', 54, 0, 0, 0, 0),
('3200', 55, 0, 0, 0, 0),
('3200', 56, 0, 0, 0, 0),
('3200', 57, 0, 0, 0, 0),
('3200', 58, 0, 0, 0, 0),
('3200', 59, 0, 0, 0, 0),
('3300', -15, 0, 0, 0, 0),
('3300', -14, 0, 0, 0, 0),
('3300', -13, 0, 0, 0, 0),
('3300', -12, 0, 0, 0, 0),
('3300', -11, 0, 0, 0, 0),
('3300', -10, 0, 0, 0, 0),
('3300', -9, 0, 0, 0, 0),
('3300', -8, 0, 0, 0, 0),
('3300', -7, 0, 0, 0, 0),
('3300', -6, 0, 0, 0, 0),
('3300', -5, 0, 0, 0, 0),
('3300', -4, 0, 0, 0, 0),
('3300', -3, 0, 0, 0, 0),
('3300', -2, 0, 0, 0, 0),
('3300', -1, 0, 0, 0, 0),
('3300', 0, 0, 0, 0, 0),
('3300', 1, 0, 0, 0, 0),
('3300', 2, 0, 0, 0, 0),
('3300', 3, 0, 0, 0, 0),
('3300', 4, 0, 0, 0, 0),
('3300', 5, 0, 0, 0, 0),
('3300', 6, 0, 0, 0, 0),
('3300', 7, 0, 0, 0, 0),
('3300', 8, 0, 0, 0, 0),
('3300', 9, 0, 0, 0, 0),
('3300', 10, 0, 0, 0, 0),
('3300', 11, 0, 0, 0, 0),
('3300', 12, 0, 0, 0, 0),
('3300', 13, 0, 0, 0, 0),
('3300', 14, 0, 0, 0, 0),
('3300', 15, 0, 0, 0, 0),
('3300', 16, 0, 0, 0, 0),
('3300', 17, 0, 0, 0, 0),
('3300', 18, 0, 0, 0, 0),
('3300', 19, 0, 0, 0, 0),
('3300', 20, 0, 0, 0, 0),
('3300', 21, 0, 0, 0, 0),
('3300', 22, 0, 0, 0, 0),
('3300', 23, 0, 0, 0, 0),
('3300', 24, 0, 0, 0, 0),
('3300', 25, 0, 0, 0, 0),
('3300', 26, 0, 0, 0, 0),
('3300', 27, 0, 0, 0, 0),
('3300', 28, 0, 0, 0, 0),
('3300', 29, 0, 0, 0, 0),
('3300', 30, 0, 0, 0, 0),
('3300', 31, 0, 0, 0, 0),
('3300', 32, 0, 0, 0, 0),
('3300', 33, 0, 0, 0, 0),
('3300', 34, 0, 0, 0, 0),
('3300', 35, 0, 0, 0, 0),
('3300', 36, 0, 0, 0, 0),
('3300', 37, 0, 0, 0, 0),
('3300', 38, 0, 0, 0, 0),
('3300', 39, 0, 0, 0, 0),
('3300', 40, 0, 0, 0, 0),
('3300', 41, 0, 0, 0, 0),
('3300', 42, 0, 0, 0, 0),
('3300', 43, 0, 0, 0, 0),
('3300', 44, 0, 0, 0, 0),
('3300', 45, 0, 0, 0, 0),
('3300', 46, 0, 0, 0, 0),
('3300', 47, 0, 0, 0, 0),
('3300', 48, 0, 0, 0, 0),
('3300', 49, 0, 0, 0, 0),
('3300', 50, 0, 0, 0, 0),
('3300', 51, 0, 0, 0, 0),
('3300', 52, 0, 0, 0, 0),
('3300', 53, 0, 0, 0, 0),
('3300', 54, 0, 0, 0, 0),
('3300', 55, 0, 0, 0, 0),
('3300', 56, 0, 0, 0, 0),
('3300', 57, 0, 0, 0, 0),
('3300', 58, 0, 0, 0, 0),
('3300', 59, 0, 0, 0, 0),
('3400', -15, 0, 0, 0, 0),
('3400', -14, 0, 0, 0, 0),
('3400', -13, 0, 0, 0, 0),
('3400', -12, 0, 0, 0, 0),
('3400', -11, 0, 0, 0, 0),
('3400', -10, 0, 0, 0, 0),
('3400', -9, 0, 0, 0, 0),
('3400', -8, 0, 0, 0, 0),
('3400', -7, 0, 0, 0, 0),
('3400', -6, 0, 0, 0, 0),
('3400', -5, 0, 0, 0, 0),
('3400', -4, 0, 0, 0, 0),
('3400', -3, 0, 0, 0, 0),
('3400', -2, 0, 0, 0, 0),
('3400', -1, 0, 0, 0, 0),
('3400', 0, 0, 0, 0, 0),
('3400', 1, 0, 0, 0, 0),
('3400', 2, 0, 0, 0, 0),
('3400', 3, 0, 0, 0, 0),
('3400', 4, 0, 0, 0, 0),
('3400', 5, 0, 0, 0, 0),
('3400', 6, 0, 0, 0, 0),
('3400', 7, 0, 0, 0, 0),
('3400', 8, 0, 0, 0, 0),
('3400', 9, 0, 0, 0, 0),
('3400', 10, 0, 0, 0, 0),
('3400', 11, 0, 0, 0, 0),
('3400', 12, 0, 0, 0, 0),
('3400', 13, 0, 0, 0, 0),
('3400', 14, 0, 0, 0, 0),
('3400', 15, 0, 0, 0, 0),
('3400', 16, 0, 0, 0, 0),
('3400', 17, 0, 0, 0, 0),
('3400', 18, 0, 0, 0, 0),
('3400', 19, 0, 0, 0, 0),
('3400', 20, 0, 0, 0, 0),
('3400', 21, 0, 0, 0, 0),
('3400', 22, 0, 0, 0, 0),
('3400', 23, 0, 0, 0, 0),
('3400', 24, 0, 0, 0, 0),
('3400', 25, 0, 0, 0, 0),
('3400', 26, 0, 0, 0, 0),
('3400', 27, 0, 0, 0, 0),
('3400', 28, 0, 0, 0, 0),
('3400', 29, 0, 0, 0, 0),
('3400', 30, 0, 0, 0, 0),
('3400', 31, 0, 0, 0, 0),
('3400', 32, 0, 0, 0, 0),
('3400', 33, 0, 0, 0, 0),
('3400', 34, 0, 0, 0, 0),
('3400', 35, 0, 0, 0, 0),
('3400', 36, 0, 0, 0, 0),
('3400', 37, 0, 0, 0, 0),
('3400', 38, 0, 0, 0, 0),
('3400', 39, 0, 0, 0, 0),
('3400', 40, 0, 0, 0, 0),
('3400', 41, 0, 0, 0, 0),
('3400', 42, 0, 0, 0, 0),
('3400', 43, 0, 0, 0, 0),
('3400', 44, 0, 0, 0, 0),
('3400', 45, 0, 0, 0, 0),
('3400', 46, 0, 0, 0, 0),
('3400', 47, 0, 0, 0, 0),
('3400', 48, 0, 0, 0, 0),
('3400', 49, 0, 0, 0, 0),
('3400', 50, 0, 0, 0, 0),
('3400', 51, 0, 0, 0, 0),
('3400', 52, 0, 0, 0, 0),
('3400', 53, 0, 0, 0, 0),
('3400', 54, 0, 0, 0, 0),
('3400', 55, 0, 0, 0, 0),
('3400', 56, 0, 0, 0, 0),
('3400', 57, 0, 0, 0, 0),
('3400', 58, 0, 0, 0, 0),
('3400', 59, 0, 0, 0, 0),
('3500', -15, 0, 0, 0, 0),
('3500', -14, 0, 0, 0, 0),
('3500', -13, 0, 0, 0, 0),
('3500', -12, 0, 0, 0, 0),
('3500', -11, 0, 0, 0, 0),
('3500', -10, 0, 0, 0, 0),
('3500', -9, 0, 0, 0, 0),
('3500', -8, 0, 0, 0, 0),
('3500', -7, 0, 0, 0, 0),
('3500', -6, 0, 0, 0, 0),
('3500', -5, 0, 0, 0, 0),
('3500', -4, 0, 0, 0, 0),
('3500', -3, 0, 0, 0, 0),
('3500', -2, 0, 0, 0, 0),
('3500', -1, 0, 0, 0, 0),
('3500', 0, 0, 0, 0, 0),
('3500', 1, 0, 0, 0, 0),
('3500', 2, 0, 0, 0, 0),
('3500', 3, 0, 0, 0, 0),
('3500', 4, 0, 0, 0, 0),
('3500', 5, 0, 0, 0, 0),
('3500', 6, 0, 0, 0, 0),
('3500', 7, 0, 0, 0, 0),
('3500', 8, 0, 0, 0, 0),
('3500', 9, 0, 0, 0, 0),
('3500', 10, 0, 0, 0, 0),
('3500', 11, 0, 0, 0, 0),
('3500', 12, 0, 0, 0, 0),
('3500', 13, 0, 0, 0, 0),
('3500', 14, 0, 0, 0, 0),
('3500', 15, 0, 0, 0, 0),
('3500', 16, 0, 0, 0, 0),
('3500', 17, 0, 0, 0, 0),
('3500', 18, 0, 0, 0, 0),
('3500', 19, 0, 0, 0, 0),
('3500', 20, 0, 0, 0, 0),
('3500', 21, 0, 0, 0, 0),
('3500', 22, 0, 0, 0, 0),
('3500', 23, 0, 0, 0, 0),
('3500', 24, 0, 0, 0, 0),
('3500', 25, 0, 0, 0, 0),
('3500', 26, 0, 0, 0, 0),
('3500', 27, 0, 0, 0, 0),
('3500', 28, 0, 0, 0, 0),
('3500', 29, 0, 0, 0, 0),
('3500', 30, 0, 0, 0, 0),
('3500', 31, 0, 0, 0, 0),
('3500', 32, 0, 0, 0, 0),
('3500', 33, 0, 0, 0, 0),
('3500', 34, 0, 0, 0, 0),
('3500', 35, 0, 0, 0, 0),
('3500', 36, 0, 0, 0, 0),
('3500', 37, 0, 0, 0, 0),
('3500', 38, 0, 0, 0, 0),
('3500', 39, 0, 0, 0, 0),
('3500', 40, 0, 0, 0, 0),
('3500', 41, 0, 0, 0, 0),
('3500', 42, 0, 0, 0, 0),
('3500', 43, 0, 0, 0, 0),
('3500', 44, 0, 0, 0, 0),
('3500', 45, 0, 0, 0, 0),
('3500', 46, 0, 0, 0, 0),
('3500', 47, 0, 0, 0, 0),
('3500', 48, 0, 0, 0, 0),
('3500', 49, 0, 0, 0, 0),
('3500', 50, 0, 0, 0, 0),
('3500', 51, 0, 0, 0, 0),
('3500', 52, 0, 0, 0, 0),
('3500', 53, 0, 0, 0, 0),
('3500', 54, 0, 0, 0, 0),
('3500', 55, 0, 0, 0, 0),
('3500', 56, 0, 0, 0, 0),
('3500', 57, 0, 0, 0, 0),
('3500', 58, 0, 0, 0, 0),
('3500', 59, 0, 0, 0, 0),
('4100', -15, 0, 0, 0, 0),
('4100', -14, 0, 0, 0, 0),
('4100', -13, 0, 0, 0, 0),
('4100', -12, 0, 0, 0, 0),
('4100', -11, 0, 0, 0, 0),
('4100', -10, 0, 0, 0, 0),
('4100', -9, 0, 0, 0, 0),
('4100', -8, 0, 0, 0, 0),
('4100', -7, 0, 0, 0, 0),
('4100', -6, 0, 0, 0, 0),
('4100', -5, 0, 0, 0, 0),
('4100', -4, 0, 0, 0, 0),
('4100', -3, 0, 0, 0, 0),
('4100', -2, 0, 0, 0, 0),
('4100', -1, 0, 0, 0, 0),
('4100', 0, 0, 0, 0, 0),
('4100', 1, 0, 0, 0, 0),
('4100', 2, 0, 0, 0, 0),
('4100', 3, 0, 0, 0, 0),
('4100', 4, 0, 0, 0, 0),
('4100', 5, 0, 0, 0, 0),
('4100', 6, 0, 0, 0, 0),
('4100', 7, 0, 0, 0, 0),
('4100', 8, 0, 0, 0, 0),
('4100', 9, 0, 0, 0, 0),
('4100', 10, 0, 0, 0, 0),
('4100', 11, 0, 0, 0, 0),
('4100', 12, 0, 0, 0, 0),
('4100', 13, 0, 0, 0, 0),
('4100', 14, 0, 0, 0, 0),
('4100', 15, 0, 0, 0, 0),
('4100', 16, 0, 0, 0, 0),
('4100', 17, 0, 0, 0, 0),
('4100', 18, 0, 0, 0, 0),
('4100', 19, 0, 0, 0, 0),
('4100', 20, 0, 0, 0, 0),
('4100', 21, 0, 0, 0, 0),
('4100', 22, 0, 0, 0, 0),
('4100', 23, 0, 0, 0, 0),
('4100', 24, 0, 0, 0, 0),
('4100', 25, 0, 0, 0, 0),
('4100', 26, 0, 0, 0, 0),
('4100', 27, 0, 0, 0, 0),
('4100', 28, 0, 0, 0, 0),
('4100', 29, 0, 0, 0, 0),
('4100', 30, 0, 0, 0, 0),
('4100', 31, 0, 0, 0, 0),
('4100', 32, 0, 0, 0, 0),
('4100', 33, 0, 0, 0, 0),
('4100', 34, 0, 0, 0, 0),
('4100', 35, 0, 0, 0, 0),
('4100', 36, 0, 0, 0, 0),
('4100', 37, 0, 0, 0, 0),
('4100', 38, 0, 0, 0, 0),
('4100', 39, 0, 0, 0, 0),
('4100', 40, 0, 0, 0, 0),
('4100', 41, 0, 0, 0, 0),
('4100', 42, 0, 0, 0, 0),
('4100', 43, 0, 0, 0, 0),
('4100', 44, 0, 0, 0, 0),
('4100', 45, 0, 0, 0, 0),
('4100', 46, 0, 0, 0, 0),
('4100', 47, 0, 0, 0, 0),
('4100', 48, 0, 0, 0, 0),
('4100', 49, 0, 0, 0, 0),
('4100', 50, 0, 0, 0, 0),
('4100', 51, 0, 0, 0, 0),
('4100', 52, 0, 0, 0, 0),
('4100', 53, 0, 0, 0, 0),
('4100', 54, 0, 0, 0, 0),
('4100', 55, 0, 0, 0, 0),
('4100', 56, 0, 0, 0, 0),
('4100', 57, 0, 0, 0, 0),
('4100', 58, 0, 0, 0, 0),
('4100', 59, 0, 0, 0, 0),
('4200', -15, 0, 0, 0, 0),
('4200', -14, 0, 0, 0, 0),
('4200', -13, 0, 0, 0, 0),
('4200', -12, 0, 0, 0, 0),
('4200', -11, 0, 0, 0, 0),
('4200', -10, 0, 0, 0, 0),
('4200', -9, 0, 0, 0, 0),
('4200', -8, 0, 0, 0, 0),
('4200', -7, 0, 0, 0, 0),
('4200', -6, 0, 0, 0, 0),
('4200', -5, 0, 0, 0, 0),
('4200', -4, 0, 0, 0, 0),
('4200', -3, 0, 0, 0, 0),
('4200', -2, 0, 0, 0, 0),
('4200', -1, 0, 0, 0, 0),
('4200', 0, 0, 0, 0, 0),
('4200', 1, 0, 0, 0, 0),
('4200', 2, 0, 0, 0, 0),
('4200', 3, 0, 0, 0, 0),
('4200', 4, 0, 0, 0, 0),
('4200', 5, 0, 0, 0, 0),
('4200', 6, 0, 0, 0, 0),
('4200', 7, 0, 0, 0, 0),
('4200', 8, 0, 0, 0, 0),
('4200', 9, 0, 0, 0, 0),
('4200', 10, 0, 0, 0, 0),
('4200', 11, 0, 0, 0, 0),
('4200', 12, 0, 0, 0, 0),
('4200', 13, 0, 0, 0, 0),
('4200', 14, 0, 0, 0, 0),
('4200', 15, 0, 0, 0, 0),
('4200', 16, 0, 0, 0, 0),
('4200', 17, 0, 0, 0, 0),
('4200', 18, 0, 0, 0, 0),
('4200', 19, 0, 0, 0, 0),
('4200', 20, 0, 0, 0, 0),
('4200', 21, 0, 0, 0, 0),
('4200', 22, 0, 0, 0, 0),
('4200', 23, 0, 0, 0, 0),
('4200', 24, 0, 0, 0, 0),
('4200', 25, 0, 0, 0, 0),
('4200', 26, 0, 0, 0, 0),
('4200', 27, 0, 0, 0, 0),
('4200', 28, 0, 0, 0, 0),
('4200', 29, 0, 0, 0, 0),
('4200', 30, 0, 0, 0, 0),
('4200', 31, 0, 0, 0, 0),
('4200', 32, 0, 0, 0, 0),
('4200', 33, 0, 0, 0, 0),
('4200', 34, 0, 0, 0, 0),
('4200', 35, 0, 0, 0, 0),
('4200', 36, 0, 0, 0, 0),
('4200', 37, 0, 0, 0, 0),
('4200', 38, 0, 0, 0, 0),
('4200', 39, 0, 0, 0, 0),
('4200', 40, 0, 0, 0, 0),
('4200', 41, 0, 0, 0, 0),
('4200', 42, 0, 0, 0, 0),
('4200', 43, 0, 0, 0, 0),
('4200', 44, 0, 0, 0, 0),
('4200', 45, 0, 0, 0, 0),
('4200', 46, 0, 0, 0, 0),
('4200', 47, 0, 0, 0, 0),
('4200', 48, 0, 0, 0, 0),
('4200', 49, 0, 0, 0, 0),
('4200', 50, 0, 0, 0, 0),
('4200', 51, 0, 0, 0, 0),
('4200', 52, 0, 0, 0, 0),
('4200', 53, 0, 0, 0, 0),
('4200', 54, 0, 0, 0, 0),
('4200', 55, 0, 0, 0, 0),
('4200', 56, 0, 0, 0, 0),
('4200', 57, 0, 0, 0, 0),
('4200', 58, 0, 0, 0, 0),
('4200', 59, 0, 0, 0, 0),
('4500', -15, 0, 0, 0, 0),
('4500', -14, 0, 0, 0, 0),
('4500', -13, 0, 0, 0, 0),
('4500', -12, 0, 0, 0, 0),
('4500', -11, 0, 0, 0, 0),
('4500', -10, 0, 0, 0, 0),
('4500', -9, 0, 0, 0, 0),
('4500', -8, 0, 0, 0, 0),
('4500', -7, 0, 0, 0, 0),
('4500', -6, 0, 0, 0, 0),
('4500', -5, 0, 0, 0, 0),
('4500', -4, 0, 0, 0, 0),
('4500', -3, 0, 0, 0, 0),
('4500', -2, 0, 0, 0, 0),
('4500', -1, 0, 0, 0, 0),
('4500', 0, 0, 0, 0, 0),
('4500', 1, 0, 0, 0, 0),
('4500', 2, 0, 0, 0, 0),
('4500', 3, 0, 0, 0, 0),
('4500', 4, 0, 0, 0, 0),
('4500', 5, 0, 0, 0, 0),
('4500', 6, 0, 0, 0, 0),
('4500', 7, 0, 0, 0, 0),
('4500', 8, 0, 0, 0, 0),
('4500', 9, 0, 0, 0, 0),
('4500', 10, 0, 0, 0, 0),
('4500', 11, 0, 0, 0, 0),
('4500', 12, 0, 0, 0, 0),
('4500', 13, 0, 0, 0, 0),
('4500', 14, 0, 0, 0, 0),
('4500', 15, 0, 0, 0, 0),
('4500', 16, 0, 0, 0, 0),
('4500', 17, 0, 0, 0, 0),
('4500', 18, 0, 0, 0, 0),
('4500', 19, 0, 0, 0, 0),
('4500', 20, 0, 0, 0, 0),
('4500', 21, 0, 0, 0, 0),
('4500', 22, 0, 0, 0, 0),
('4500', 23, 0, 0, 0, 0),
('4500', 24, 0, 0, 0, 0),
('4500', 25, 0, 0, 0, 0),
('4500', 26, 0, 0, 0, 0),
('4500', 27, 0, 0, 0, 0),
('4500', 28, 0, 0, 0, 0),
('4500', 29, 0, 0, 0, 0),
('4500', 30, 0, 0, 0, 0),
('4500', 31, 0, 0, 0, 0),
('4500', 32, 0, 0, 0, 0),
('4500', 33, 0, 0, 0, 0),
('4500', 34, 0, 0, 0, 0),
('4500', 35, 0, 0, 0, 0),
('4500', 36, 0, 0, 0, 0),
('4500', 37, 0, 0, 0, 0),
('4500', 38, 0, 0, 0, 0),
('4500', 39, 0, 0, 0, 0),
('4500', 40, 0, 0, 0, 0),
('4500', 41, 0, 0, 0, 0),
('4500', 42, 0, 0, 0, 0),
('4500', 43, 0, 0, 0, 0),
('4500', 44, 0, 0, 0, 0),
('4500', 45, 0, 0, 0, 0),
('4500', 46, 0, 0, 0, 0),
('4500', 47, 0, 0, 0, 0),
('4500', 48, 0, 0, 0, 0),
('4500', 49, 0, 0, 0, 0),
('4500', 50, 0, 0, 0, 0),
('4500', 51, 0, 0, 0, 0),
('4500', 52, 0, 0, 0, 0),
('4500', 53, 0, 0, 0, 0),
('4500', 54, 0, 0, 0, 0),
('4500', 55, 0, 0, 0, 0),
('4500', 56, 0, 0, 0, 0),
('4500', 57, 0, 0, 0, 0),
('4500', 58, 0, 0, 0, 0),
('4500', 59, 0, 0, 0, 0),
('4600', -15, 0, 0, 0, 0),
('4600', -14, 0, 0, 0, 0),
('4600', -13, 0, 0, 0, 0),
('4600', -12, 0, 0, 0, 0),
('4600', -11, 0, 0, 0, 0),
('4600', -10, 0, 0, 0, 0),
('4600', -9, 0, 0, 0, 0),
('4600', -8, 0, 0, 0, 0),
('4600', -7, 0, 0, 0, 0),
('4600', -6, 0, 0, 0, 0),
('4600', -5, 0, 0, 0, 0),
('4600', -4, 0, 0, 0, 0),
('4600', -3, 0, 0, 0, 0),
('4600', -2, 0, 0, 0, 0),
('4600', -1, 0, 0, 0, 0),
('4600', 0, 0, 0, 0, 0),
('4600', 1, 0, 0, 0, 0),
('4600', 2, 0, 0, 0, 0),
('4600', 3, 0, 0, 0, 0),
('4600', 4, 0, 0, 0, 0),
('4600', 5, 0, 0, 0, 0),
('4600', 6, 0, 0, 0, 0),
('4600', 7, 0, 0, 0, 0),
('4600', 8, 0, 0, 0, 0),
('4600', 9, 0, 0, 0, 0),
('4600', 10, 0, 0, 0, 0),
('4600', 11, 0, 0, 0, 0),
('4600', 12, 0, 0, 0, 0),
('4600', 13, 0, 0, 0, 0),
('4600', 14, 0, 0, 0, 0),
('4600', 15, 0, 0, 0, 0),
('4600', 16, 0, 0, 0, 0),
('4600', 17, 0, 0, 0, 0),
('4600', 18, 0, 0, 0, 0),
('4600', 19, 0, 0, 0, 0),
('4600', 20, 0, 0, 0, 0),
('4600', 21, 0, 0, 0, 0),
('4600', 22, 0, 0, 0, 0),
('4600', 23, 0, 0, 0, 0),
('4600', 24, 0, 0, 0, 0),
('4600', 25, 0, 0, 0, 0),
('4600', 26, 0, 0, 0, 0),
('4600', 27, 0, 0, 0, 0),
('4600', 28, 0, 0, 0, 0),
('4600', 29, 0, 0, 0, 0),
('4600', 30, 0, 0, 0, 0),
('4600', 31, 0, 0, 0, 0),
('4600', 32, 0, 0, 0, 0),
('4600', 33, 0, 0, 0, 0),
('4600', 34, 0, 0, 0, 0),
('4600', 35, 0, 0, 0, 0),
('4600', 36, 0, 0, 0, 0),
('4600', 37, 0, 0, 0, 0),
('4600', 38, 0, 0, 0, 0),
('4600', 39, 0, 0, 0, 0),
('4600', 40, 0, 0, 0, 0),
('4600', 41, 0, 0, 0, 0),
('4600', 42, 0, 0, 0, 0),
('4600', 43, 0, 0, 0, 0),
('4600', 44, 0, 0, 0, 0),
('4600', 45, 0, 0, 0, 0),
('4600', 46, 0, 0, 0, 0),
('4600', 47, 0, 0, 0, 0),
('4600', 48, 0, 0, 0, 0),
('4600', 49, 0, 0, 0, 0),
('4600', 50, 0, 0, 0, 0),
('4600', 51, 0, 0, 0, 0),
('4600', 52, 0, 0, 0, 0),
('4600', 53, 0, 0, 0, 0),
('4600', 54, 0, 0, 0, 0),
('4600', 55, 0, 0, 0, 0),
('4600', 56, 0, 0, 0, 0),
('4600', 57, 0, 0, 0, 0),
('4600', 58, 0, 0, 0, 0),
('4600', 59, 0, 0, 0, 0),
('4700', -15, 0, 0, 0, 0),
('4700', -14, 0, 0, 0, 0),
('4700', -13, 0, 0, 0, 0),
('4700', -12, 0, 0, 0, 0),
('4700', -11, 0, 0, 0, 0),
('4700', -10, 0, 0, 0, 0),
('4700', -9, 0, 0, 0, 0),
('4700', -8, 0, 0, 0, 0),
('4700', -7, 0, 0, 0, 0),
('4700', -6, 0, 0, 0, 0),
('4700', -5, 0, 0, 0, 0),
('4700', -4, 0, 0, 0, 0),
('4700', -3, 0, 0, 0, 0),
('4700', -2, 0, 0, 0, 0),
('4700', -1, 0, 0, 0, 0),
('4700', 0, 0, 0, 0, 0),
('4700', 1, 0, 0, 0, 0),
('4700', 2, 0, 0, 0, 0),
('4700', 3, 0, 0, 0, 0),
('4700', 4, 0, 0, 0, 0),
('4700', 5, 0, 0, 0, 0),
('4700', 6, 0, 0, 0, 0),
('4700', 7, 0, 0, 0, 0),
('4700', 8, 0, 0, 0, 0),
('4700', 9, 0, 0, 0, 0),
('4700', 10, 0, 0, 0, 0),
('4700', 11, 0, 0, 0, 0),
('4700', 12, 0, 0, 0, 0),
('4700', 13, 0, 0, 0, 0),
('4700', 14, 0, 0, 0, 0),
('4700', 15, 0, 0, 0, 0),
('4700', 16, 0, 0, 0, 0),
('4700', 17, 0, 0, 0, 0),
('4700', 18, 0, 0, 0, 0),
('4700', 19, 0, 0, 0, 0),
('4700', 20, 0, 0, 0, 0),
('4700', 21, 0, 0, 0, 0),
('4700', 22, 0, 0, 0, 0),
('4700', 23, 0, 0, 0, 0),
('4700', 24, 0, 0, 0, 0),
('4700', 25, 0, 0, 0, 0),
('4700', 26, 0, 0, 0, 0),
('4700', 27, 0, 0, 0, 0),
('4700', 28, 0, 0, 0, 0),
('4700', 29, 0, 0, 0, 0),
('4700', 30, 0, 0, 0, 0),
('4700', 31, 0, 0, 0, 0),
('4700', 32, 0, 0, 0, 0),
('4700', 33, 0, 0, 0, 0),
('4700', 34, 0, 0, 0, 0),
('4700', 35, 0, 0, 0, 0),
('4700', 36, 0, 0, 0, 0),
('4700', 37, 0, 0, 0, 0),
('4700', 38, 0, 0, 0, 0),
('4700', 39, 0, 0, 0, 0),
('4700', 40, 0, 0, 0, 0),
('4700', 41, 0, 0, 0, 0),
('4700', 42, 0, 0, 0, 0),
('4700', 43, 0, 0, 0, 0),
('4700', 44, 0, 0, 0, 0),
('4700', 45, 0, 0, 0, 0),
('4700', 46, 0, 0, 0, 0),
('4700', 47, 0, 0, 0, 0),
('4700', 48, 0, 0, 0, 0),
('4700', 49, 0, 0, 0, 0),
('4700', 50, 0, 0, 0, 0),
('4700', 51, 0, 0, 0, 0),
('4700', 52, 0, 0, 0, 0),
('4700', 53, 0, 0, 0, 0),
('4700', 54, 0, 0, 0, 0),
('4700', 55, 0, 0, 0, 0),
('4700', 56, 0, 0, 0, 0),
('4700', 57, 0, 0, 0, 0),
('4700', 58, 0, 0, 0, 0),
('4700', 59, 0, 0, 0, 0),
('4800', -15, 0, 0, 0, 0),
('4800', -14, 0, 0, 0, 0),
('4800', -13, 0, 0, 0, 0),
('4800', -12, 0, 0, 0, 0),
('4800', -11, 0, 0, 0, 0),
('4800', -10, 0, 0, 0, 0),
('4800', -9, 0, 0, 0, 0),
('4800', -8, 0, 0, 0, 0),
('4800', -7, 0, 0, 0, 0),
('4800', -6, 0, 0, 0, 0),
('4800', -5, 0, 0, 0, 0),
('4800', -4, 0, 0, 0, 0),
('4800', -3, 0, 0, 0, 0),
('4800', -2, 0, 0, 0, 0),
('4800', -1, 0, 0, 0, 0),
('4800', 0, 0, 0, 0, 0),
('4800', 1, 0, 0, 0, 0),
('4800', 2, 0, 0, 0, 0),
('4800', 3, 0, 0, 0, 0),
('4800', 4, 0, 0, 0, 0),
('4800', 5, 0, 0, 0, 0),
('4800', 6, 0, 0, 0, 0),
('4800', 7, 0, 0, 0, 0),
('4800', 8, 0, 0, 0, 0),
('4800', 9, 0, 0, 0, 0),
('4800', 10, 0, 0, 0, 0),
('4800', 11, 0, 0, 0, 0),
('4800', 12, 0, 0, 0, 0),
('4800', 13, 0, 0, 0, 0),
('4800', 14, 0, 0, 0, 0),
('4800', 15, 0, 0, 0, 0),
('4800', 16, 0, 0, 0, 0),
('4800', 17, 0, 0, 0, 0),
('4800', 18, 0, 0, 0, 0),
('4800', 19, 0, 0, 0, 0),
('4800', 20, 0, 0, 0, 0),
('4800', 21, 0, 0, 0, 0),
('4800', 22, 0, 0, 0, 0),
('4800', 23, 0, 0, 0, 0),
('4800', 24, 0, 0, 0, 0),
('4800', 25, 0, 0, 0, 0),
('4800', 26, 0, 0, 0, 0),
('4800', 27, 0, 0, 0, 0),
('4800', 28, 0, 0, 0, 0),
('4800', 29, 0, 0, 0, 0),
('4800', 30, 0, 0, 0, 0),
('4800', 31, 0, 0, 0, 0),
('4800', 32, 0, 0, 0, 0),
('4800', 33, 0, 0, 0, 0),
('4800', 34, 0, 0, 0, 0),
('4800', 35, 0, 0, 0, 0),
('4800', 36, 0, 0, 0, 0),
('4800', 37, 0, 0, 0, 0),
('4800', 38, 0, 0, 0, 0),
('4800', 39, 0, 0, 0, 0),
('4800', 40, 0, 0, 0, 0),
('4800', 41, 0, 0, 0, 0),
('4800', 42, 0, 0, 0, 0),
('4800', 43, 0, 0, 0, 0),
('4800', 44, 0, 0, 0, 0),
('4800', 45, 0, 0, 0, 0),
('4800', 46, 0, 0, 0, 0),
('4800', 47, 0, 0, 0, 0),
('4800', 48, 0, 0, 0, 0),
('4800', 49, 0, 0, 0, 0),
('4800', 50, 0, 0, 0, 0),
('4800', 51, 0, 0, 0, 0),
('4800', 52, 0, 0, 0, 0),
('4800', 53, 0, 0, 0, 0),
('4800', 54, 0, 0, 0, 0),
('4800', 55, 0, 0, 0, 0),
('4800', 56, 0, 0, 0, 0),
('4800', 57, 0, 0, 0, 0),
('4800', 58, 0, 0, 0, 0),
('4800', 59, 0, 0, 0, 0),
('4900', -15, 0, 0, 0, 0),
('4900', -14, 0, 0, 0, 0),
('4900', -13, 0, 0, 0, 0),
('4900', -12, 0, 0, 0, 0),
('4900', -11, 0, 0, 0, 0),
('4900', -10, 0, 0, 0, 0),
('4900', -9, 0, 0, 0, 0),
('4900', -8, 0, 0, 0, 0),
('4900', -7, 0, 0, 0, 0),
('4900', -6, 0, 0, 0, 0),
('4900', -5, 0, 0, 0, 0),
('4900', -4, 0, 0, 0, 0),
('4900', -3, 0, 0, 0, 0),
('4900', -2, 0, 0, 0, 0),
('4900', -1, 0, 0, 0, 0),
('4900', 0, 0, 0, 0, 0),
('4900', 1, 0, 0, 0, 0),
('4900', 2, 0, 0, 0, 0),
('4900', 3, 0, 0, 0, 0),
('4900', 4, 0, 0, 0, 0),
('4900', 5, 0, 0, 0, 0),
('4900', 6, 0, 0, 0, 0),
('4900', 7, 0, 0, 0, 0),
('4900', 8, 0, 0, 0, 0),
('4900', 9, 0, 0, 0, 0),
('4900', 10, 0, 0, 0, 0),
('4900', 11, 0, 0, 0, 0),
('4900', 12, 0, 0, 0, 0),
('4900', 13, 0, 0, 0, 0),
('4900', 14, 0, 0, 0, 0),
('4900', 15, 0, 0, 0, 0),
('4900', 16, 0, 0, 0, 0),
('4900', 17, 0, 0, 0, 0),
('4900', 18, 0, 0, 0, 0),
('4900', 19, 0, 0, 0, 0),
('4900', 20, 0, 0, 0, 0),
('4900', 21, 0, 0, 0, 0),
('4900', 22, 0, 0, 0, 0),
('4900', 23, 0, 0, 0, 0),
('4900', 24, 0, 0, 0, 0),
('4900', 25, 0, 0, 0, 0),
('4900', 26, 0, 0, 0, 0),
('4900', 27, 0, 0, 0, 0),
('4900', 28, 0, 0, 0, 0),
('4900', 29, 0, 0, 0, 0),
('4900', 30, 0, 0, 0, 0),
('4900', 31, 0, 0, 0, 0),
('4900', 32, 0, 0, 0, 0),
('4900', 33, 0, 0, 0, 0),
('4900', 34, 0, 0, 0, 0),
('4900', 35, 0, 0, 0, 0),
('4900', 36, 0, 0, 0, 0),
('4900', 37, 0, 0, 0, 0),
('4900', 38, 0, 0, 0, 0),
('4900', 39, 0, 0, 0, 0),
('4900', 40, 0, 0, 0, 0),
('4900', 41, 0, 0, 0, 0),
('4900', 42, 0, 0, 0, 0),
('4900', 43, 0, 0, 0, 0),
('4900', 44, 0, 0, 0, 0),
('4900', 45, 0, 0, 0, 0),
('4900', 46, 0, 0, 0, 0),
('4900', 47, 0, 0, 0, 0),
('4900', 48, 0, 0, 0, 0),
('4900', 49, 0, 0, 0, 0),
('4900', 50, 0, 0, 0, 0),
('4900', 51, 0, 0, 0, 0),
('4900', 52, 0, 0, 0, 0),
('4900', 53, 0, 0, 0, 0),
('4900', 54, 0, 0, 0, 0),
('4900', 55, 0, 0, 0, 0),
('4900', 56, 0, 0, 0, 0),
('4900', 57, 0, 0, 0, 0),
('4900', 58, 0, 0, 0, 0),
('4900', 59, 0, 0, 0, 0),
('5000', -15, 0, 0, 0, 0),
('5000', -14, 0, 0, 0, 0),
('5000', -13, 0, 0, 0, 0),
('5000', -12, 0, 0, 0, 0),
('5000', -11, 0, 0, 0, 0),
('5000', -10, 0, 0, 0, 0),
('5000', -9, 0, 0, 0, 0),
('5000', -8, 0, 0, 0, 0),
('5000', -7, 0, 0, 0, 0),
('5000', -6, 0, 0, 0, 0),
('5000', -5, 0, 0, 0, 0),
('5000', -4, 0, 0, 0, 0),
('5000', -3, 0, 0, 0, 0),
('5000', -2, 0, 0, 0, 0),
('5000', -1, 0, 0, 0, 0),
('5000', 0, 0, 0, 0, 0),
('5000', 1, 0, 0, 0, 0),
('5000', 2, 0, 0, 0, 0),
('5000', 3, 0, 0, 0, 0),
('5000', 4, 0, 0, 0, 0),
('5000', 5, 0, 0, 0, 0),
('5000', 6, 0, 0, 0, 0),
('5000', 7, 0, 0, 0, 0),
('5000', 8, 0, 0, 0, 0),
('5000', 9, 0, 0, 0, 0),
('5000', 10, 0, 0, 0, 0),
('5000', 11, 0, 0, 0, 0),
('5000', 12, 0, 0, 0, 0),
('5000', 13, 0, 0, 0, 0),
('5000', 14, 0, 0, 0, 0),
('5000', 15, 0, 0, 0, 0),
('5000', 16, 0, 0, 0, 0),
('5000', 17, 0, 0, 0, 0),
('5000', 18, 0, 0, 0, 0),
('5000', 19, 0, 0, 0, 0),
('5000', 20, 0, 0, 0, 0),
('5000', 21, 0, 0, 0, 0),
('5000', 22, 0, 0, 0, 0),
('5000', 23, 0, 0, 0, 0),
('5000', 24, 0, 0, 0, 0),
('5000', 25, 0, 0, 0, 0),
('5000', 26, 0, 0, 0, 0),
('5000', 27, 0, 0, 0, 0),
('5000', 28, 0, 0, 0, 0),
('5000', 29, 0, 0, 0, 0),
('5000', 30, 0, 0, 0, 0),
('5000', 31, 0, 0, 0, 0),
('5000', 32, 0, 0, 0, 0),
('5000', 33, 0, 0, 0, 0),
('5000', 34, 0, 0, 0, 0),
('5000', 35, 0, 0, 0, 0),
('5000', 36, 0, 0, 0, 0),
('5000', 37, 0, 0, 0, 0),
('5000', 38, 0, 0, 0, 0),
('5000', 39, 0, 0, 0, 0),
('5000', 40, 0, 0, 0, 0),
('5000', 41, 0, 0, 0, 0),
('5000', 42, 0, 0, 0, 0),
('5000', 43, 0, 0, 0, 0),
('5000', 44, 0, 0, 0, 0),
('5000', 45, 0, 0, 0, 0),
('5000', 46, 0, 0, 0, 0),
('5000', 47, 0, 0, 0, 0),
('5000', 48, 0, 0, 0, 0),
('5000', 49, 0, 0, 0, 0),
('5000', 50, 0, 0, 0, 0),
('5000', 51, 0, 0, 0, 0),
('5000', 52, 0, 0, 0, 0),
('5000', 53, 0, 0, 0, 0),
('5000', 54, 0, 0, 0, 0),
('5000', 55, 0, 0, 0, 0),
('5000', 56, 0, 0, 0, 0),
('5000', 57, 0, 0, 0, 0),
('5000', 58, 0, 0, 0, 0),
('5000', 59, 0, 0, 0, 0),
('5100', -15, 0, 0, 0, 0),
('5100', -14, 0, 0, 0, 0),
('5100', -13, 0, 0, 0, 0),
('5100', -12, 0, 0, 0, 0),
('5100', -11, 0, 0, 0, 0),
('5100', -10, 0, 0, 0, 0),
('5100', -9, 0, 0, 0, 0),
('5100', -8, 0, 0, 0, 0),
('5100', -7, 0, 0, 0, 0),
('5100', -6, 0, 0, 0, 0),
('5100', -5, 0, 0, 0, 0),
('5100', -4, 0, 0, 0, 0),
('5100', -3, 0, 0, 0, 0),
('5100', -2, 0, 0, 0, 0),
('5100', -1, 0, 0, 0, 0),
('5100', 0, 0, 0, 0, 0),
('5100', 1, 0, 0, 0, 0),
('5100', 2, 0, 0, 0, 0),
('5100', 3, 0, 0, 0, 0),
('5100', 4, 0, 0, 0, 0),
('5100', 5, 0, 0, 0, 0),
('5100', 6, 0, 0, 0, 0),
('5100', 7, 0, 0, 0, 0),
('5100', 8, 0, 0, 0, 0),
('5100', 9, 0, 0, 0, 0),
('5100', 10, 0, 0, 0, 0),
('5100', 11, 0, 0, 0, 0),
('5100', 12, 0, 0, 0, 0),
('5100', 13, 0, 0, 0, 0),
('5100', 14, 0, 0, 0, 0),
('5100', 15, 0, 0, 0, 0),
('5100', 16, 0, 0, 0, 0),
('5100', 17, 0, 0, 0, 0),
('5100', 18, 0, 0, 0, 0);
INSERT INTO `chartdetails` (`accountcode`, `period`, `budget`, `actual`, `bfwd`, `bfwdbudget`) VALUES
('5100', 19, 0, 0, 0, 0),
('5100', 20, 0, 0, 0, 0),
('5100', 21, 0, 0, 0, 0),
('5100', 22, 0, 0, 0, 0),
('5100', 23, 0, 0, 0, 0),
('5100', 24, 0, 0, 0, 0),
('5100', 25, 0, 0, 0, 0),
('5100', 26, 0, 0, 0, 0),
('5100', 27, 0, 0, 0, 0),
('5100', 28, 0, 0, 0, 0),
('5100', 29, 0, 0, 0, 0),
('5100', 30, 0, 0, 0, 0),
('5100', 31, 0, 0, 0, 0),
('5100', 32, 0, 0, 0, 0),
('5100', 33, 0, 0, 0, 0),
('5100', 34, 0, 0, 0, 0),
('5100', 35, 0, 0, 0, 0),
('5100', 36, 0, 0, 0, 0),
('5100', 37, 0, 0, 0, 0),
('5100', 38, 0, 0, 0, 0),
('5100', 39, 0, 0, 0, 0),
('5100', 40, 0, 0, 0, 0),
('5100', 41, 0, 0, 0, 0),
('5100', 42, 0, 0, 0, 0),
('5100', 43, 0, 0, 0, 0),
('5100', 44, 0, 0, 0, 0),
('5100', 45, 0, 0, 0, 0),
('5100', 46, 0, 0, 0, 0),
('5100', 47, 0, 0, 0, 0),
('5100', 48, 0, 0, 0, 0),
('5100', 49, 0, 0, 0, 0),
('5100', 50, 0, 0, 0, 0),
('5100', 51, 0, 0, 0, 0),
('5100', 52, 0, 0, 0, 0),
('5100', 53, 0, 0, 0, 0),
('5100', 54, 0, 0, 0, 0),
('5100', 55, 0, 0, 0, 0),
('5100', 56, 0, 0, 0, 0),
('5100', 57, 0, 0, 0, 0),
('5100', 58, 0, 0, 0, 0),
('5100', 59, 0, 0, 0, 0),
('5200', -15, 0, 0, 0, 0),
('5200', -14, 0, 0, 0, 0),
('5200', -13, 0, 0, 0, 0),
('5200', -12, 0, 0, 0, 0),
('5200', -11, 0, 0, 0, 0),
('5200', -10, 0, 0, 0, 0),
('5200', -9, 0, 0, 0, 0),
('5200', -8, 0, 0, 0, 0),
('5200', -7, 0, 0, 0, 0),
('5200', -6, 0, 0, 0, 0),
('5200', -5, 0, 0, 0, 0),
('5200', -4, 0, 0, 0, 0),
('5200', -3, 0, 0, 0, 0),
('5200', -2, 0, 0, 0, 0),
('5200', -1, 0, 0, 0, 0),
('5200', 0, 0, 0, 0, 0),
('5200', 1, 0, 0, 0, 0),
('5200', 2, 0, 0, 0, 0),
('5200', 3, 0, 0, 0, 0),
('5200', 4, 0, 0, 0, 0),
('5200', 5, 0, 0, 0, 0),
('5200', 6, 0, 0, 0, 0),
('5200', 7, 0, 0, 0, 0),
('5200', 8, 0, 0, 0, 0),
('5200', 9, 0, 0, 0, 0),
('5200', 10, 0, 0, 0, 0),
('5200', 11, 0, 0, 0, 0),
('5200', 12, 0, 0, 0, 0),
('5200', 13, 0, 0, 0, 0),
('5200', 14, 0, 0, 0, 0),
('5200', 15, 0, 0, 0, 0),
('5200', 16, 0, 0, 0, 0),
('5200', 17, 0, 0, 0, 0),
('5200', 18, 0, 0, 0, 0),
('5200', 19, 0, 0, 0, 0),
('5200', 20, 0, 0, 0, 0),
('5200', 21, 0, 0, 0, 0),
('5200', 22, 0, 0, 0, 0),
('5200', 23, 0, 0, 0, 0),
('5200', 24, 0, 0, 0, 0),
('5200', 25, 0, 0, 0, 0),
('5200', 26, 0, 0, 0, 0),
('5200', 27, 0, 0, 0, 0),
('5200', 28, 0, 0, 0, 0),
('5200', 29, 0, 0, 0, 0),
('5200', 30, 0, 0, 0, 0),
('5200', 31, 0, 0, 0, 0),
('5200', 32, 0, 0, 0, 0),
('5200', 33, 0, 0, 0, 0),
('5200', 34, 0, 0, 0, 0),
('5200', 35, 0, 0, 0, 0),
('5200', 36, 0, 0, 0, 0),
('5200', 37, 0, 0, 0, 0),
('5200', 38, 0, 0, 0, 0),
('5200', 39, 0, 0, 0, 0),
('5200', 40, 0, 0, 0, 0),
('5200', 41, 0, 0, 0, 0),
('5200', 42, 0, 0, 0, 0),
('5200', 43, 0, 0, 0, 0),
('5200', 44, 0, 0, 0, 0),
('5200', 45, 0, 0, 0, 0),
('5200', 46, 0, 0, 0, 0),
('5200', 47, 0, 0, 0, 0),
('5200', 48, 0, 0, 0, 0),
('5200', 49, 0, 0, 0, 0),
('5200', 50, 0, 0, 0, 0),
('5200', 51, 0, 0, 0, 0),
('5200', 52, 0, 0, 0, 0),
('5200', 53, 0, 0, 0, 0),
('5200', 54, 0, 0, 0, 0),
('5200', 55, 0, 0, 0, 0),
('5200', 56, 0, 0, 0, 0),
('5200', 57, 0, 0, 0, 0),
('5200', 58, 0, 0, 0, 0),
('5200', 59, 0, 0, 0, 0),
('5500', -15, 0, 0, 0, 0),
('5500', -14, 0, 0, 0, 0),
('5500', -13, 0, 0, 0, 0),
('5500', -12, 0, 0, 0, 0),
('5500', -11, 0, 0, 0, 0),
('5500', -10, 0, 0, 0, 0),
('5500', -9, 0, 0, 0, 0),
('5500', -8, 0, 0, 0, 0),
('5500', -7, 0, 0, 0, 0),
('5500', -6, 0, 0, 0, 0),
('5500', -5, 0, 0, 0, 0),
('5500', -4, 0, 0, 0, 0),
('5500', -3, 0, 0, 0, 0),
('5500', -2, 0, 0, 0, 0),
('5500', -1, 0, 0, 0, 0),
('5500', 0, 0, 0, 0, 0),
('5500', 1, 0, 0, 0, 0),
('5500', 2, 0, 0, 0, 0),
('5500', 3, 0, 0, 0, 0),
('5500', 4, 0, 0, 0, 0),
('5500', 5, 0, 0, 0, 0),
('5500', 6, 0, 0, 0, 0),
('5500', 7, 0, 0, 0, 0),
('5500', 8, 0, 0, 0, 0),
('5500', 9, 0, 0, 0, 0),
('5500', 10, 0, 0, 0, 0),
('5500', 11, 0, 0, 0, 0),
('5500', 12, 0, 0, 0, 0),
('5500', 13, 0, 0, 0, 0),
('5500', 14, 0, 0, 0, 0),
('5500', 15, 0, 0, 0, 0),
('5500', 16, 0, 0, 0, 0),
('5500', 17, 0, 0, 0, 0),
('5500', 18, 0, 0, 0, 0),
('5500', 19, 0, 0, 0, 0),
('5500', 20, 0, 0, 0, 0),
('5500', 21, 0, 0, 0, 0),
('5500', 22, 0, 0, 0, 0),
('5500', 23, 0, 0, 0, 0),
('5500', 24, 0, 0, 0, 0),
('5500', 25, 0, 0, 0, 0),
('5500', 26, 0, 0, 0, 0),
('5500', 27, 0, 0, 0, 0),
('5500', 28, 0, 0, 0, 0),
('5500', 29, 0, 0, 0, 0),
('5500', 30, 0, 0, 0, 0),
('5500', 31, 0, 0, 0, 0),
('5500', 32, 0, 0, 0, 0),
('5500', 33, 0, 0, 0, 0),
('5500', 34, 0, 0, 0, 0),
('5500', 35, 0, 0, 0, 0),
('5500', 36, 0, 0, 0, 0),
('5500', 37, 0, 0, 0, 0),
('5500', 38, 0, 0, 0, 0),
('5500', 39, 0, 0, 0, 0),
('5500', 40, 0, 0, 0, 0),
('5500', 41, 0, 0, 0, 0),
('5500', 42, 0, 0, 0, 0),
('5500', 43, 0, 0, 0, 0),
('5500', 44, 0, 0, 0, 0),
('5500', 45, 0, 0, 0, 0),
('5500', 46, 0, 0, 0, 0),
('5500', 47, 0, 0, 0, 0),
('5500', 48, 0, 0, 0, 0),
('5500', 49, 0, 0, 0, 0),
('5500', 50, 0, 0, 0, 0),
('5500', 51, 0, 0, 0, 0),
('5500', 52, 0, 0, 0, 0),
('5500', 53, 0, 0, 0, 0),
('5500', 54, 0, 0, 0, 0),
('5500', 55, 0, 0, 0, 0),
('5500', 56, 0, 0, 0, 0),
('5500', 57, 0, 0, 0, 0),
('5500', 58, 0, 0, 0, 0),
('5500', 59, 0, 0, 0, 0),
('5600', -15, 0, 0, 0, 0),
('5600', -14, 0, 0, 0, 0),
('5600', -13, 0, 0, 0, 0),
('5600', -12, 0, 0, 0, 0),
('5600', -11, 0, 0, 0, 0),
('5600', -10, 0, 0, 0, 0),
('5600', -9, 0, 0, 0, 0),
('5600', -8, 0, 0, 0, 0),
('5600', -7, 0, 0, 0, 0),
('5600', -6, 0, 0, 0, 0),
('5600', -5, 0, 0, 0, 0),
('5600', -4, 0, 0, 0, 0),
('5600', -3, 0, 0, 0, 0),
('5600', -2, 0, 0, 0, 0),
('5600', -1, 0, 0, 0, 0),
('5600', 0, 0, 0, 0, 0),
('5600', 1, 0, 0, 0, 0),
('5600', 2, 0, 0, 0, 0),
('5600', 3, 0, 0, 0, 0),
('5600', 4, 0, 0, 0, 0),
('5600', 5, 0, 0, 0, 0),
('5600', 6, 0, 0, 0, 0),
('5600', 7, 0, 0, 0, 0),
('5600', 8, 0, 0, 0, 0),
('5600', 9, 0, 0, 0, 0),
('5600', 10, 0, 0, 0, 0),
('5600', 11, 0, 0, 0, 0),
('5600', 12, 0, 0, 0, 0),
('5600', 13, 0, 0, 0, 0),
('5600', 14, 0, 0, 0, 0),
('5600', 15, 0, 0, 0, 0),
('5600', 16, 0, 0, 0, 0),
('5600', 17, 0, 0, 0, 0),
('5600', 18, 0, 0, 0, 0),
('5600', 19, 0, 0, 0, 0),
('5600', 20, 0, 0, 0, 0),
('5600', 21, 0, 0, 0, 0),
('5600', 22, 0, 0, 0, 0),
('5600', 23, 0, 0, 0, 0),
('5600', 24, 0, 0, 0, 0),
('5600', 25, 0, 0, 0, 0),
('5600', 26, 0, 0, 0, 0),
('5600', 27, 0, 0, 0, 0),
('5600', 28, 0, 0, 0, 0),
('5600', 29, 0, 0, 0, 0),
('5600', 30, 0, 0, 0, 0),
('5600', 31, 0, 0, 0, 0),
('5600', 32, 0, 0, 0, 0),
('5600', 33, 0, 0, 0, 0),
('5600', 34, 0, 0, 0, 0),
('5600', 35, 0, 0, 0, 0),
('5600', 36, 0, 0, 0, 0),
('5600', 37, 0, 0, 0, 0),
('5600', 38, 0, 0, 0, 0),
('5600', 39, 0, 0, 0, 0),
('5600', 40, 0, 0, 0, 0),
('5600', 41, 0, 0, 0, 0),
('5600', 42, 0, 0, 0, 0),
('5600', 43, 0, 0, 0, 0),
('5600', 44, 0, 0, 0, 0),
('5600', 45, 0, 0, 0, 0),
('5600', 46, 0, 0, 0, 0),
('5600', 47, 0, 0, 0, 0),
('5600', 48, 0, 0, 0, 0),
('5600', 49, 0, 0, 0, 0),
('5600', 50, 0, 0, 0, 0),
('5600', 51, 0, 0, 0, 0),
('5600', 52, 0, 0, 0, 0),
('5600', 53, 0, 0, 0, 0),
('5600', 54, 0, 0, 0, 0),
('5600', 55, 0, 0, 0, 0),
('5600', 56, 0, 0, 0, 0),
('5600', 57, 0, 0, 0, 0),
('5600', 58, 0, 0, 0, 0),
('5600', 59, 0, 0, 0, 0),
('5700', -15, 0, 0, 0, 0),
('5700', -14, 0, 0, 0, 0),
('5700', -13, 0, 0, 0, 0),
('5700', -12, 0, 0, 0, 0),
('5700', -11, 0, 0, 0, 0),
('5700', -10, 0, 0, 0, 0),
('5700', -9, 0, 0, 0, 0),
('5700', -8, 0, 0, 0, 0),
('5700', -7, 0, 0, 0, 0),
('5700', -6, 0, 0, 0, 0),
('5700', -5, 0, 0, 0, 0),
('5700', -4, 0, 0, 0, 0),
('5700', -3, 0, 0, 0, 0),
('5700', -2, 0, 0, 0, 0),
('5700', -1, 0, 0, 0, 0),
('5700', 0, 0, 0, 0, 0),
('5700', 1, 0, 0, 0, 0),
('5700', 2, 0, 0, 0, 0),
('5700', 3, 0, 0, 0, 0),
('5700', 4, 0, 0, 0, 0),
('5700', 5, 0, 0, 0, 0),
('5700', 6, 0, 0, 0, 0),
('5700', 7, 0, 0, 0, 0),
('5700', 8, 0, 0, 0, 0),
('5700', 9, 0, 0, 0, 0),
('5700', 10, 0, 0, 0, 0),
('5700', 11, 0, 0, 0, 0),
('5700', 12, 0, 0, 0, 0),
('5700', 13, 0, 0, 0, 0),
('5700', 14, 0, 0, 0, 0),
('5700', 15, 0, 0, 0, 0),
('5700', 16, 0, 0, 0, 0),
('5700', 17, 0, 0, 0, 0),
('5700', 18, 0, 0, 0, 0),
('5700', 19, 0, 0, 0, 0),
('5700', 20, 0, 0, 0, 0),
('5700', 21, 0, 0, 0, 0),
('5700', 22, 0, 0, 0, 0),
('5700', 23, 0, 0, 0, 0),
('5700', 24, 0, 0, 0, 0),
('5700', 25, 0, 0, 0, 0),
('5700', 26, 0, 0, 0, 0),
('5700', 27, 0, 0, 0, 0),
('5700', 28, 0, 0, 0, 0),
('5700', 29, 0, 0, 0, 0),
('5700', 30, 0, 0, 0, 0),
('5700', 31, 0, 0, 0, 0),
('5700', 32, 0, 0, 0, 0),
('5700', 33, 0, 0, 0, 0),
('5700', 34, 0, 0, 0, 0),
('5700', 35, 0, 0, 0, 0),
('5700', 36, 0, 0, 0, 0),
('5700', 37, 0, 0, 0, 0),
('5700', 38, 0, 0, 0, 0),
('5700', 39, 0, 0, 0, 0),
('5700', 40, 0, 0, 0, 0),
('5700', 41, 0, 0, 0, 0),
('5700', 42, 0, 0, 0, 0),
('5700', 43, 0, 0, 0, 0),
('5700', 44, 0, 0, 0, 0),
('5700', 45, 0, 0, 0, 0),
('5700', 46, 0, 0, 0, 0),
('5700', 47, 0, 0, 0, 0),
('5700', 48, 0, 0, 0, 0),
('5700', 49, 0, 0, 0, 0),
('5700', 50, 0, 0, 0, 0),
('5700', 51, 0, 0, 0, 0),
('5700', 52, 0, 0, 0, 0),
('5700', 53, 0, 0, 0, 0),
('5700', 54, 0, 0, 0, 0),
('5700', 55, 0, 0, 0, 0),
('5700', 56, 0, 0, 0, 0),
('5700', 57, 0, 0, 0, 0),
('5700', 58, 0, 0, 0, 0),
('5700', 59, 0, 0, 0, 0),
('5800', -15, 0, 0, 0, 0),
('5800', -14, 0, 0, 0, 0),
('5800', -13, 0, 0, 0, 0),
('5800', -12, 0, 0, 0, 0),
('5800', -11, 0, 0, 0, 0),
('5800', -10, 0, 0, 0, 0),
('5800', -9, 0, 0, 0, 0),
('5800', -8, 0, 0, 0, 0),
('5800', -7, 0, 0, 0, 0),
('5800', -6, 0, 0, 0, 0),
('5800', -5, 0, 0, 0, 0),
('5800', -4, 0, 0, 0, 0),
('5800', -3, 0, 0, 0, 0),
('5800', -2, 0, 0, 0, 0),
('5800', -1, 0, 0, 0, 0),
('5800', 0, 0, 0, 0, 0),
('5800', 1, 0, 0, 0, 0),
('5800', 2, 0, 0, 0, 0),
('5800', 3, 0, 0, 0, 0),
('5800', 4, 0, 0, 0, 0),
('5800', 5, 0, 0, 0, 0),
('5800', 6, 0, 0, 0, 0),
('5800', 7, 0, 0, 0, 0),
('5800', 8, 0, 0, 0, 0),
('5800', 9, 0, 0, 0, 0),
('5800', 10, 0, 0, 0, 0),
('5800', 11, 0, 0, 0, 0),
('5800', 12, 0, 0, 0, 0),
('5800', 13, 0, 0, 0, 0),
('5800', 14, 0, 0, 0, 0),
('5800', 15, 0, 0, 0, 0),
('5800', 16, 0, 0, 0, 0),
('5800', 17, 0, 0, 0, 0),
('5800', 18, 0, 0, 0, 0),
('5800', 19, 0, 0, 0, 0),
('5800', 20, 0, 0, 0, 0),
('5800', 21, 0, 0, 0, 0),
('5800', 22, 0, 0, 0, 0),
('5800', 23, 0, 0, 0, 0),
('5800', 24, 0, 0, 0, 0),
('5800', 25, 0, 0, 0, 0),
('5800', 26, 0, 0, 0, 0),
('5800', 27, 0, 0, 0, 0),
('5800', 28, 0, 0, 0, 0),
('5800', 29, 0, 0, 0, 0),
('5800', 30, 0, 0, 0, 0),
('5800', 31, 0, 0, 0, 0),
('5800', 32, 0, 0, 0, 0),
('5800', 33, 0, 0, 0, 0),
('5800', 34, 0, 0, 0, 0),
('5800', 35, 0, 0, 0, 0),
('5800', 36, 0, 0, 0, 0),
('5800', 37, 0, 0, 0, 0),
('5800', 38, 0, 0, 0, 0),
('5800', 39, 0, 0, 0, 0),
('5800', 40, 0, 0, 0, 0),
('5800', 41, 0, 0, 0, 0),
('5800', 42, 0, 0, 0, 0),
('5800', 43, 0, 0, 0, 0),
('5800', 44, 0, 0, 0, 0),
('5800', 45, 0, 0, 0, 0),
('5800', 46, 0, 0, 0, 0),
('5800', 47, 0, 0, 0, 0),
('5800', 48, 0, 0, 0, 0),
('5800', 49, 0, 0, 0, 0),
('5800', 50, 0, 0, 0, 0),
('5800', 51, 0, 0, 0, 0),
('5800', 52, 0, 0, 0, 0),
('5800', 53, 0, 0, 0, 0),
('5800', 54, 0, 0, 0, 0),
('5800', 55, 0, 0, 0, 0),
('5800', 56, 0, 0, 0, 0),
('5800', 57, 0, 0, 0, 0),
('5800', 58, 0, 0, 0, 0),
('5800', 59, 0, 0, 0, 0),
('5900', -15, 0, 0, 0, 0),
('5900', -14, 0, 0, 0, 0),
('5900', -13, 0, 0, 0, 0),
('5900', -12, 0, 0, 0, 0),
('5900', -11, 0, 0, 0, 0),
('5900', -10, 0, 0, 0, 0),
('5900', -9, 0, 0, 0, 0),
('5900', -8, 0, 0, 0, 0),
('5900', -7, 0, 0, 0, 0),
('5900', -6, 0, 0, 0, 0),
('5900', -5, 0, 0, 0, 0),
('5900', -4, 0, 0, 0, 0),
('5900', -3, 0, 0, 0, 0),
('5900', -2, 0, 0, 0, 0),
('5900', -1, 0, 0, 0, 0),
('5900', 0, 0, 0, 0, 0),
('5900', 1, 0, 0, 0, 0),
('5900', 2, 0, 0, 0, 0),
('5900', 3, 0, 0, 0, 0),
('5900', 4, 0, 0, 0, 0),
('5900', 5, 0, 0, 0, 0),
('5900', 6, 0, 0, 0, 0),
('5900', 7, 0, 0, 0, 0),
('5900', 8, 0, 0, 0, 0),
('5900', 9, 0, 0, 0, 0),
('5900', 10, 0, 0, 0, 0),
('5900', 11, 0, 0, 0, 0),
('5900', 12, 0, 0, 0, 0),
('5900', 13, 0, 0, 0, 0),
('5900', 14, 0, 0, 0, 0),
('5900', 15, 0, 0, 0, 0),
('5900', 16, 0, 0, 0, 0),
('5900', 17, 0, 0, 0, 0),
('5900', 18, 0, 0, 0, 0),
('5900', 19, 0, 0, 0, 0),
('5900', 20, 0, 0, 0, 0),
('5900', 21, 0, 0, 0, 0),
('5900', 22, 0, 0, 0, 0),
('5900', 23, 0, 0, 0, 0),
('5900', 24, 0, 0, 0, 0),
('5900', 25, 0, 0, 0, 0),
('5900', 26, 0, 0, 0, 0),
('5900', 27, 0, 0, 0, 0),
('5900', 28, 0, 0, 0, 0),
('5900', 29, 0, 0, 0, 0),
('5900', 30, 0, 0, 0, 0),
('5900', 31, 0, 0, 0, 0),
('5900', 32, 0, 0, 0, 0),
('5900', 33, 0, 0, 0, 0),
('5900', 34, 0, 0, 0, 0),
('5900', 35, 0, 0, 0, 0),
('5900', 36, 0, 0, 0, 0),
('5900', 37, 0, 0, 0, 0),
('5900', 38, 0, 0, 0, 0),
('5900', 39, 0, 0, 0, 0),
('5900', 40, 0, 0, 0, 0),
('5900', 41, 0, 0, 0, 0),
('5900', 42, 0, 0, 0, 0),
('5900', 43, 0, 0, 0, 0),
('5900', 44, 0, 0, 0, 0),
('5900', 45, 0, 0, 0, 0),
('5900', 46, 0, 0, 0, 0),
('5900', 47, 0, 0, 0, 0),
('5900', 48, 0, 0, 0, 0),
('5900', 49, 0, 0, 0, 0),
('5900', 50, 0, 0, 0, 0),
('5900', 51, 0, 0, 0, 0),
('5900', 52, 0, 0, 0, 0),
('5900', 53, 0, 0, 0, 0),
('5900', 54, 0, 0, 0, 0),
('5900', 55, 0, 0, 0, 0),
('5900', 56, 0, 0, 0, 0),
('5900', 57, 0, 0, 0, 0),
('5900', 58, 0, 0, 0, 0),
('5900', 59, 0, 0, 0, 0),
('6100', -15, 0, 0, 0, 0),
('6100', -14, 0, 0, 0, 0),
('6100', -13, 0, 0, 0, 0),
('6100', -12, 0, 0, 0, 0),
('6100', -11, 0, 0, 0, 0),
('6100', -10, 0, 0, 0, 0),
('6100', -9, 0, 0, 0, 0),
('6100', -8, 0, 0, 0, 0),
('6100', -7, 0, 0, 0, 0),
('6100', -6, 0, 0, 0, 0),
('6100', -5, 0, 0, 0, 0),
('6100', -4, 0, 0, 0, 0),
('6100', -3, 0, 0, 0, 0),
('6100', -2, 0, 0, 0, 0),
('6100', -1, 0, 0, 0, 0),
('6100', 0, 0, 0, 0, 0),
('6100', 1, 0, 0, 0, 0),
('6100', 2, 0, 0, 0, 0),
('6100', 3, 0, 0, 0, 0),
('6100', 4, 0, 0, 0, 0),
('6100', 5, 0, 0, 0, 0),
('6100', 6, 0, 0, 0, 0),
('6100', 7, 0, 0, 0, 0),
('6100', 8, 0, 0, 0, 0),
('6100', 9, 0, 0, 0, 0),
('6100', 10, 0, 0, 0, 0),
('6100', 11, 0, 0, 0, 0),
('6100', 12, 0, 0, 0, 0),
('6100', 13, 0, 0, 0, 0),
('6100', 14, 0, 0, 0, 0),
('6100', 15, 0, 0, 0, 0),
('6100', 16, 0, 0, 0, 0),
('6100', 17, 0, 0, 0, 0),
('6100', 18, 0, 0, 0, 0),
('6100', 19, 0, 0, 0, 0),
('6100', 20, 0, 0, 0, 0),
('6100', 21, 0, 0, 0, 0),
('6100', 22, 0, 0, 0, 0),
('6100', 23, 0, 0, 0, 0),
('6100', 24, 0, 0, 0, 0),
('6100', 25, 0, 0, 0, 0),
('6100', 26, 0, 0, 0, 0),
('6100', 27, 0, 0, 0, 0),
('6100', 28, 0, 0, 0, 0),
('6100', 29, 0, 0, 0, 0),
('6100', 30, 0, 0, 0, 0),
('6100', 31, 0, 0, 0, 0),
('6100', 32, 0, 0, 0, 0),
('6100', 33, 0, 0, 0, 0),
('6100', 34, 0, 0, 0, 0),
('6100', 35, 0, 0, 0, 0),
('6100', 36, 0, 0, 0, 0),
('6100', 37, 0, 0, 0, 0),
('6100', 38, 0, 0, 0, 0),
('6100', 39, 0, 0, 0, 0),
('6100', 40, 0, 0, 0, 0),
('6100', 41, 0, 0, 0, 0),
('6100', 42, 0, 0, 0, 0),
('6100', 43, 0, 0, 0, 0),
('6100', 44, 0, 0, 0, 0),
('6100', 45, 0, 0, 0, 0),
('6100', 46, 0, 0, 0, 0),
('6100', 47, 0, 0, 0, 0),
('6100', 48, 0, 0, 0, 0),
('6100', 49, 0, 0, 0, 0),
('6100', 50, 0, 0, 0, 0),
('6100', 51, 0, 0, 0, 0),
('6100', 52, 0, 0, 0, 0),
('6100', 53, 0, 0, 0, 0),
('6100', 54, 0, 0, 0, 0),
('6100', 55, 0, 0, 0, 0),
('6100', 56, 0, 0, 0, 0),
('6100', 57, 0, 0, 0, 0),
('6100', 58, 0, 0, 0, 0),
('6100', 59, 0, 0, 0, 0),
('6150', -15, 0, 0, 0, 0),
('6150', -14, 0, 0, 0, 0),
('6150', -13, 0, 0, 0, 0),
('6150', -12, 0, 0, 0, 0),
('6150', -11, 0, 0, 0, 0),
('6150', -10, 0, 0, 0, 0),
('6150', -9, 0, 0, 0, 0),
('6150', -8, 0, 0, 0, 0),
('6150', -7, 0, 0, 0, 0),
('6150', -6, 0, 0, 0, 0),
('6150', -5, 0, 0, 0, 0),
('6150', -4, 0, 0, 0, 0),
('6150', -3, 0, 0, 0, 0),
('6150', -2, 0, 0, 0, 0),
('6150', -1, 0, 0, 0, 0),
('6150', 0, 0, 0, 0, 0),
('6150', 1, 0, 0, 0, 0),
('6150', 2, 0, 0, 0, 0),
('6150', 3, 0, 0, 0, 0),
('6150', 4, 0, 0, 0, 0),
('6150', 5, 0, 0, 0, 0),
('6150', 6, 0, 0, 0, 0),
('6150', 7, 0, 0, 0, 0),
('6150', 8, 0, 0, 0, 0),
('6150', 9, 0, 0, 0, 0),
('6150', 10, 0, 0, 0, 0),
('6150', 11, 0, 0, 0, 0),
('6150', 12, 0, 0, 0, 0),
('6150', 13, 0, 0, 0, 0),
('6150', 14, 0, 0, 0, 0),
('6150', 15, 0, 0, 0, 0),
('6150', 16, 0, 0, 0, 0),
('6150', 17, 0, 0, 0, 0),
('6150', 18, 0, 0, 0, 0),
('6150', 19, 0, 0, 0, 0),
('6150', 20, 0, 0, 0, 0),
('6150', 21, 0, 0, 0, 0),
('6150', 22, 0, 0, 0, 0),
('6150', 23, 0, 0, 0, 0),
('6150', 24, 0, 0, 0, 0),
('6150', 25, 0, 0, 0, 0),
('6150', 26, 0, 0, 0, 0),
('6150', 27, 0, 0, 0, 0),
('6150', 28, 0, 0, 0, 0),
('6150', 29, 0, 0, 0, 0),
('6150', 30, 0, 0, 0, 0),
('6150', 31, 0, 0, 0, 0),
('6150', 32, 0, 0, 0, 0),
('6150', 33, 0, 0, 0, 0),
('6150', 34, 0, 0, 0, 0),
('6150', 35, 0, 0, 0, 0),
('6150', 36, 0, 0, 0, 0),
('6150', 37, 0, 0, 0, 0),
('6150', 38, 0, 0, 0, 0),
('6150', 39, 0, 0, 0, 0),
('6150', 40, 0, 0, 0, 0),
('6150', 41, 0, 0, 0, 0),
('6150', 42, 0, 0, 0, 0),
('6150', 43, 0, 0, 0, 0),
('6150', 44, 0, 0, 0, 0),
('6150', 45, 0, 0, 0, 0),
('6150', 46, 0, 0, 0, 0),
('6150', 47, 0, 0, 0, 0),
('6150', 48, 0, 0, 0, 0),
('6150', 49, 0, 0, 0, 0),
('6150', 50, 0, 0, 0, 0),
('6150', 51, 0, 0, 0, 0),
('6150', 52, 0, 0, 0, 0),
('6150', 53, 0, 0, 0, 0),
('6150', 54, 0, 0, 0, 0),
('6150', 55, 0, 0, 0, 0),
('6150', 56, 0, 0, 0, 0),
('6150', 57, 0, 0, 0, 0),
('6150', 58, 0, 0, 0, 0),
('6150', 59, 0, 0, 0, 0),
('6200', -15, 0, 0, 0, 0),
('6200', -14, 0, 0, 0, 0),
('6200', -13, 0, 0, 0, 0),
('6200', -12, 0, 0, 0, 0),
('6200', -11, 0, 0, 0, 0),
('6200', -10, 0, 0, 0, 0),
('6200', -9, 0, 0, 0, 0),
('6200', -8, 0, 0, 0, 0),
('6200', -7, 0, 0, 0, 0),
('6200', -6, 0, 0, 0, 0),
('6200', -5, 0, 0, 0, 0),
('6200', -4, 0, 0, 0, 0),
('6200', -3, 0, 0, 0, 0),
('6200', -2, 0, 0, 0, 0),
('6200', -1, 0, 0, 0, 0),
('6200', 0, 0, 0, 0, 0),
('6200', 1, 0, 0, 0, 0),
('6200', 2, 0, 0, 0, 0),
('6200', 3, 0, 0, 0, 0),
('6200', 4, 0, 0, 0, 0),
('6200', 5, 0, 0, 0, 0),
('6200', 6, 0, 0, 0, 0),
('6200', 7, 0, 0, 0, 0),
('6200', 8, 0, 0, 0, 0),
('6200', 9, 0, 0, 0, 0),
('6200', 10, 0, 0, 0, 0),
('6200', 11, 0, 0, 0, 0),
('6200', 12, 0, 0, 0, 0),
('6200', 13, 0, 0, 0, 0),
('6200', 14, 0, 0, 0, 0),
('6200', 15, 0, 0, 0, 0),
('6200', 16, 0, 0, 0, 0),
('6200', 17, 0, 0, 0, 0),
('6200', 18, 0, 0, 0, 0),
('6200', 19, 0, 0, 0, 0),
('6200', 20, 0, 0, 0, 0),
('6200', 21, 0, 0, 0, 0),
('6200', 22, 0, 0, 0, 0),
('6200', 23, 0, 0, 0, 0),
('6200', 24, 0, 0, 0, 0),
('6200', 25, 0, 0, 0, 0),
('6200', 26, 0, 0, 0, 0),
('6200', 27, 0, 0, 0, 0),
('6200', 28, 0, 0, 0, 0),
('6200', 29, 0, 0, 0, 0),
('6200', 30, 0, 0, 0, 0),
('6200', 31, 0, 0, 0, 0),
('6200', 32, 0, 0, 0, 0),
('6200', 33, 0, 0, 0, 0),
('6200', 34, 0, 0, 0, 0),
('6200', 35, 0, 0, 0, 0),
('6200', 36, 0, 0, 0, 0),
('6200', 37, 0, 0, 0, 0),
('6200', 38, 0, 0, 0, 0),
('6200', 39, 0, 0, 0, 0),
('6200', 40, 0, 0, 0, 0),
('6200', 41, 0, 0, 0, 0),
('6200', 42, 0, 0, 0, 0),
('6200', 43, 0, 0, 0, 0),
('6200', 44, 0, 0, 0, 0),
('6200', 45, 0, 0, 0, 0),
('6200', 46, 0, 0, 0, 0),
('6200', 47, 0, 0, 0, 0),
('6200', 48, 0, 0, 0, 0),
('6200', 49, 0, 0, 0, 0),
('6200', 50, 0, 0, 0, 0),
('6200', 51, 0, 0, 0, 0),
('6200', 52, 0, 0, 0, 0),
('6200', 53, 0, 0, 0, 0),
('6200', 54, 0, 0, 0, 0),
('6200', 55, 0, 0, 0, 0),
('6200', 56, 0, 0, 0, 0),
('6200', 57, 0, 0, 0, 0),
('6200', 58, 0, 0, 0, 0),
('6200', 59, 0, 0, 0, 0),
('6250', -15, 0, 0, 0, 0),
('6250', -14, 0, 0, 0, 0),
('6250', -13, 0, 0, 0, 0),
('6250', -12, 0, 0, 0, 0),
('6250', -11, 0, 0, 0, 0),
('6250', -10, 0, 0, 0, 0),
('6250', -9, 0, 0, 0, 0),
('6250', -8, 0, 0, 0, 0),
('6250', -7, 0, 0, 0, 0),
('6250', -6, 0, 0, 0, 0),
('6250', -5, 0, 0, 0, 0),
('6250', -4, 0, 0, 0, 0),
('6250', -3, 0, 0, 0, 0),
('6250', -2, 0, 0, 0, 0),
('6250', -1, 0, 0, 0, 0),
('6250', 0, 0, 0, 0, 0),
('6250', 1, 0, 0, 0, 0),
('6250', 2, 0, 0, 0, 0),
('6250', 3, 0, 0, 0, 0),
('6250', 4, 0, 0, 0, 0),
('6250', 5, 0, 0, 0, 0),
('6250', 6, 0, 0, 0, 0),
('6250', 7, 0, 0, 0, 0),
('6250', 8, 0, 0, 0, 0),
('6250', 9, 0, 0, 0, 0),
('6250', 10, 0, 0, 0, 0),
('6250', 11, 0, 0, 0, 0),
('6250', 12, 0, 0, 0, 0),
('6250', 13, 0, 0, 0, 0),
('6250', 14, 0, 0, 0, 0),
('6250', 15, 0, 0, 0, 0),
('6250', 16, 0, 0, 0, 0),
('6250', 17, 0, 0, 0, 0),
('6250', 18, 0, 0, 0, 0),
('6250', 19, 0, 0, 0, 0),
('6250', 20, 0, 0, 0, 0),
('6250', 21, 0, 0, 0, 0),
('6250', 22, 0, 0, 0, 0),
('6250', 23, 0, 0, 0, 0),
('6250', 24, 0, 0, 0, 0),
('6250', 25, 0, 0, 0, 0),
('6250', 26, 0, 0, 0, 0),
('6250', 27, 0, 0, 0, 0),
('6250', 28, 0, 0, 0, 0),
('6250', 29, 0, 0, 0, 0),
('6250', 30, 0, 0, 0, 0),
('6250', 31, 0, 0, 0, 0),
('6250', 32, 0, 0, 0, 0),
('6250', 33, 0, 0, 0, 0),
('6250', 34, 0, 0, 0, 0),
('6250', 35, 0, 0, 0, 0),
('6250', 36, 0, 0, 0, 0),
('6250', 37, 0, 0, 0, 0),
('6250', 38, 0, 0, 0, 0),
('6250', 39, 0, 0, 0, 0),
('6250', 40, 0, 0, 0, 0),
('6250', 41, 0, 0, 0, 0),
('6250', 42, 0, 0, 0, 0),
('6250', 43, 0, 0, 0, 0),
('6250', 44, 0, 0, 0, 0),
('6250', 45, 0, 0, 0, 0),
('6250', 46, 0, 0, 0, 0),
('6250', 47, 0, 0, 0, 0),
('6250', 48, 0, 0, 0, 0),
('6250', 49, 0, 0, 0, 0),
('6250', 50, 0, 0, 0, 0),
('6250', 51, 0, 0, 0, 0),
('6250', 52, 0, 0, 0, 0),
('6250', 53, 0, 0, 0, 0),
('6250', 54, 0, 0, 0, 0),
('6250', 55, 0, 0, 0, 0),
('6250', 56, 0, 0, 0, 0),
('6250', 57, 0, 0, 0, 0),
('6250', 58, 0, 0, 0, 0),
('6250', 59, 0, 0, 0, 0),
('6300', -15, 0, 0, 0, 0),
('6300', -14, 0, 0, 0, 0),
('6300', -13, 0, 0, 0, 0),
('6300', -12, 0, 0, 0, 0),
('6300', -11, 0, 0, 0, 0),
('6300', -10, 0, 0, 0, 0),
('6300', -9, 0, 0, 0, 0),
('6300', -8, 0, 0, 0, 0),
('6300', -7, 0, 0, 0, 0),
('6300', -6, 0, 0, 0, 0),
('6300', -5, 0, 0, 0, 0),
('6300', -4, 0, 0, 0, 0),
('6300', -3, 0, 0, 0, 0),
('6300', -2, 0, 0, 0, 0),
('6300', -1, 0, 0, 0, 0),
('6300', 0, 0, 0, 0, 0),
('6300', 1, 0, 0, 0, 0),
('6300', 2, 0, 0, 0, 0),
('6300', 3, 0, 0, 0, 0),
('6300', 4, 0, 0, 0, 0),
('6300', 5, 0, 0, 0, 0),
('6300', 6, 0, 0, 0, 0),
('6300', 7, 0, 0, 0, 0),
('6300', 8, 0, 0, 0, 0),
('6300', 9, 0, 0, 0, 0),
('6300', 10, 0, 0, 0, 0),
('6300', 11, 0, 0, 0, 0),
('6300', 12, 0, 0, 0, 0),
('6300', 13, 0, 0, 0, 0),
('6300', 14, 0, 0, 0, 0),
('6300', 15, 0, 0, 0, 0),
('6300', 16, 0, 0, 0, 0),
('6300', 17, 0, 0, 0, 0),
('6300', 18, 0, 0, 0, 0),
('6300', 19, 0, 0, 0, 0),
('6300', 20, 0, 0, 0, 0),
('6300', 21, 0, 0, 0, 0),
('6300', 22, 0, 0, 0, 0),
('6300', 23, 0, 0, 0, 0),
('6300', 24, 0, 0, 0, 0),
('6300', 25, 0, 0, 0, 0),
('6300', 26, 0, 0, 0, 0),
('6300', 27, 0, 0, 0, 0),
('6300', 28, 0, 0, 0, 0),
('6300', 29, 0, 0, 0, 0),
('6300', 30, 0, 0, 0, 0),
('6300', 31, 0, 0, 0, 0),
('6300', 32, 0, 0, 0, 0),
('6300', 33, 0, 0, 0, 0),
('6300', 34, 0, 0, 0, 0),
('6300', 35, 0, 0, 0, 0),
('6300', 36, 0, 0, 0, 0),
('6300', 37, 0, 0, 0, 0),
('6300', 38, 0, 0, 0, 0),
('6300', 39, 0, 0, 0, 0),
('6300', 40, 0, 0, 0, 0),
('6300', 41, 0, 0, 0, 0),
('6300', 42, 0, 0, 0, 0),
('6300', 43, 0, 0, 0, 0),
('6300', 44, 0, 0, 0, 0),
('6300', 45, 0, 0, 0, 0),
('6300', 46, 0, 0, 0, 0),
('6300', 47, 0, 0, 0, 0),
('6300', 48, 0, 0, 0, 0),
('6300', 49, 0, 0, 0, 0),
('6300', 50, 0, 0, 0, 0),
('6300', 51, 0, 0, 0, 0),
('6300', 52, 0, 0, 0, 0),
('6300', 53, 0, 0, 0, 0),
('6300', 54, 0, 0, 0, 0),
('6300', 55, 0, 0, 0, 0),
('6300', 56, 0, 0, 0, 0),
('6300', 57, 0, 0, 0, 0),
('6300', 58, 0, 0, 0, 0),
('6300', 59, 0, 0, 0, 0),
('6400', -15, 0, 0, 0, 0),
('6400', -14, 0, 0, 0, 0),
('6400', -13, 0, 0, 0, 0),
('6400', -12, 0, 0, 0, 0),
('6400', -11, 0, 0, 0, 0),
('6400', -10, 0, 0, 0, 0),
('6400', -9, 0, 0, 0, 0),
('6400', -8, 0, 0, 0, 0),
('6400', -7, 0, 0, 0, 0),
('6400', -6, 0, 0, 0, 0),
('6400', -5, 0, 0, 0, 0),
('6400', -4, 0, 0, 0, 0),
('6400', -3, 0, 0, 0, 0),
('6400', -2, 0, 0, 0, 0),
('6400', -1, 0, 0, 0, 0),
('6400', 0, 0, 0, 0, 0),
('6400', 1, 0, 0, 0, 0),
('6400', 2, 0, 0, 0, 0),
('6400', 3, 0, 0, 0, 0),
('6400', 4, 0, 0, 0, 0),
('6400', 5, 0, 0, 0, 0),
('6400', 6, 0, 0, 0, 0),
('6400', 7, 0, 0, 0, 0),
('6400', 8, 0, 0, 0, 0),
('6400', 9, 0, 0, 0, 0),
('6400', 10, 0, 0, 0, 0),
('6400', 11, 0, 0, 0, 0),
('6400', 12, 0, 0, 0, 0),
('6400', 13, 0, 0, 0, 0),
('6400', 14, 0, 0, 0, 0),
('6400', 15, 0, 0, 0, 0),
('6400', 16, 0, 0, 0, 0),
('6400', 17, 0, 0, 0, 0),
('6400', 18, 0, 0, 0, 0),
('6400', 19, 0, 0, 0, 0),
('6400', 20, 0, 0, 0, 0),
('6400', 21, 0, 0, 0, 0),
('6400', 22, 0, 0, 0, 0),
('6400', 23, 0, 0, 0, 0),
('6400', 24, 0, 0, 0, 0),
('6400', 25, 0, 0, 0, 0),
('6400', 26, 0, 0, 0, 0),
('6400', 27, 0, 0, 0, 0),
('6400', 28, 0, 0, 0, 0),
('6400', 29, 0, 0, 0, 0),
('6400', 30, 0, 0, 0, 0),
('6400', 31, 0, 0, 0, 0),
('6400', 32, 0, 0, 0, 0),
('6400', 33, 0, 0, 0, 0),
('6400', 34, 0, 0, 0, 0),
('6400', 35, 0, 0, 0, 0),
('6400', 36, 0, 0, 0, 0),
('6400', 37, 0, 0, 0, 0),
('6400', 38, 0, 0, 0, 0),
('6400', 39, 0, 0, 0, 0),
('6400', 40, 0, 0, 0, 0),
('6400', 41, 0, 0, 0, 0),
('6400', 42, 0, 0, 0, 0),
('6400', 43, 0, 0, 0, 0),
('6400', 44, 0, 0, 0, 0),
('6400', 45, 0, 0, 0, 0),
('6400', 46, 0, 0, 0, 0),
('6400', 47, 0, 0, 0, 0),
('6400', 48, 0, 0, 0, 0),
('6400', 49, 0, 0, 0, 0),
('6400', 50, 0, 0, 0, 0),
('6400', 51, 0, 0, 0, 0),
('6400', 52, 0, 0, 0, 0),
('6400', 53, 0, 0, 0, 0),
('6400', 54, 0, 0, 0, 0),
('6400', 55, 0, 0, 0, 0),
('6400', 56, 0, 0, 0, 0),
('6400', 57, 0, 0, 0, 0),
('6400', 58, 0, 0, 0, 0),
('6400', 59, 0, 0, 0, 0),
('6500', -15, 0, 0, 0, 0),
('6500', -14, 0, 0, 0, 0),
('6500', -13, 0, 0, 0, 0),
('6500', -12, 0, 0, 0, 0),
('6500', -11, 0, 0, 0, 0),
('6500', -10, 0, 0, 0, 0),
('6500', -9, 0, 0, 0, 0),
('6500', -8, 0, 0, 0, 0),
('6500', -7, 0, 0, 0, 0),
('6500', -6, 0, 0, 0, 0),
('6500', -5, 0, 0, 0, 0),
('6500', -4, 0, 0, 0, 0),
('6500', -3, 0, 0, 0, 0),
('6500', -2, 0, 0, 0, 0),
('6500', -1, 0, 0, 0, 0),
('6500', 0, 0, 0, 0, 0),
('6500', 1, 0, 0, 0, 0),
('6500', 2, 0, 0, 0, 0),
('6500', 3, 0, 0, 0, 0),
('6500', 4, 0, 0, 0, 0),
('6500', 5, 0, 0, 0, 0),
('6500', 6, 0, 0, 0, 0),
('6500', 7, 0, 0, 0, 0),
('6500', 8, 0, 0, 0, 0),
('6500', 9, 0, 0, 0, 0),
('6500', 10, 0, 0, 0, 0),
('6500', 11, 0, 0, 0, 0),
('6500', 12, 0, 0, 0, 0),
('6500', 13, 0, 0, 0, 0),
('6500', 14, 0, 0, 0, 0),
('6500', 15, 0, 0, 0, 0),
('6500', 16, 0, 0, 0, 0),
('6500', 17, 0, 0, 0, 0),
('6500', 18, 0, 0, 0, 0),
('6500', 19, 0, 0, 0, 0),
('6500', 20, 0, 0, 0, 0),
('6500', 21, 0, 0, 0, 0),
('6500', 22, 0, 0, 0, 0),
('6500', 23, 0, 0, 0, 0),
('6500', 24, 0, 0, 0, 0),
('6500', 25, 0, 0, 0, 0),
('6500', 26, 0, 0, 0, 0),
('6500', 27, 0, 0, 0, 0),
('6500', 28, 0, 0, 0, 0),
('6500', 29, 0, 0, 0, 0),
('6500', 30, 0, 0, 0, 0),
('6500', 31, 0, 0, 0, 0),
('6500', 32, 0, 0, 0, 0),
('6500', 33, 0, 0, 0, 0),
('6500', 34, 0, 0, 0, 0),
('6500', 35, 0, 0, 0, 0),
('6500', 36, 0, 0, 0, 0),
('6500', 37, 0, 0, 0, 0),
('6500', 38, 0, 0, 0, 0),
('6500', 39, 0, 0, 0, 0),
('6500', 40, 0, 0, 0, 0),
('6500', 41, 0, 0, 0, 0),
('6500', 42, 0, 0, 0, 0),
('6500', 43, 0, 0, 0, 0),
('6500', 44, 0, 0, 0, 0),
('6500', 45, 0, 0, 0, 0),
('6500', 46, 0, 0, 0, 0),
('6500', 47, 0, 0, 0, 0),
('6500', 48, 0, 0, 0, 0),
('6500', 49, 0, 0, 0, 0),
('6500', 50, 0, 0, 0, 0),
('6500', 51, 0, 0, 0, 0),
('6500', 52, 0, 0, 0, 0),
('6500', 53, 0, 0, 0, 0),
('6500', 54, 0, 0, 0, 0),
('6500', 55, 0, 0, 0, 0),
('6500', 56, 0, 0, 0, 0),
('6500', 57, 0, 0, 0, 0),
('6500', 58, 0, 0, 0, 0),
('6500', 59, 0, 0, 0, 0),
('6550', -15, 0, 0, 0, 0),
('6550', -14, 0, 0, 0, 0),
('6550', -13, 0, 0, 0, 0),
('6550', -12, 0, 0, 0, 0),
('6550', -11, 0, 0, 0, 0),
('6550', -10, 0, 0, 0, 0),
('6550', -9, 0, 0, 0, 0),
('6550', -8, 0, 0, 0, 0),
('6550', -7, 0, 0, 0, 0),
('6550', -6, 0, 0, 0, 0),
('6550', -5, 0, 0, 0, 0),
('6550', -4, 0, 0, 0, 0),
('6550', -3, 0, 0, 0, 0),
('6550', -2, 0, 0, 0, 0),
('6550', -1, 0, 0, 0, 0),
('6550', 0, 0, 0, 0, 0),
('6550', 1, 0, 0, 0, 0),
('6550', 2, 0, 0, 0, 0),
('6550', 3, 0, 0, 0, 0),
('6550', 4, 0, 0, 0, 0),
('6550', 5, 0, 0, 0, 0),
('6550', 6, 0, 0, 0, 0),
('6550', 7, 0, 0, 0, 0),
('6550', 8, 0, 0, 0, 0),
('6550', 9, 0, 0, 0, 0),
('6550', 10, 0, 0, 0, 0),
('6550', 11, 0, 0, 0, 0),
('6550', 12, 0, 0, 0, 0),
('6550', 13, 0, 0, 0, 0),
('6550', 14, 0, 0, 0, 0),
('6550', 15, 0, 0, 0, 0),
('6550', 16, 0, 0, 0, 0),
('6550', 17, 0, 0, 0, 0),
('6550', 18, 0, 0, 0, 0),
('6550', 19, 0, 0, 0, 0),
('6550', 20, 0, 0, 0, 0),
('6550', 21, 0, 0, 0, 0),
('6550', 22, 0, 0, 0, 0),
('6550', 23, 0, 0, 0, 0),
('6550', 24, 0, 0, 0, 0),
('6550', 25, 0, 0, 0, 0),
('6550', 26, 0, 0, 0, 0),
('6550', 27, 0, 0, 0, 0),
('6550', 28, 0, 0, 0, 0),
('6550', 29, 0, 0, 0, 0),
('6550', 30, 0, 0, 0, 0),
('6550', 31, 0, 0, 0, 0),
('6550', 32, 0, 0, 0, 0),
('6550', 33, 0, 0, 0, 0),
('6550', 34, 0, 0, 0, 0),
('6550', 35, 0, 0, 0, 0),
('6550', 36, 0, 0, 0, 0),
('6550', 37, 0, 0, 0, 0),
('6550', 38, 0, 0, 0, 0),
('6550', 39, 0, 0, 0, 0),
('6550', 40, 0, 0, 0, 0),
('6550', 41, 0, 0, 0, 0),
('6550', 42, 0, 0, 0, 0),
('6550', 43, 0, 0, 0, 0),
('6550', 44, 0, 0, 0, 0),
('6550', 45, 0, 0, 0, 0),
('6550', 46, 0, 0, 0, 0),
('6550', 47, 0, 0, 0, 0),
('6550', 48, 0, 0, 0, 0),
('6550', 49, 0, 0, 0, 0),
('6550', 50, 0, 0, 0, 0),
('6550', 51, 0, 0, 0, 0),
('6550', 52, 0, 0, 0, 0),
('6550', 53, 0, 0, 0, 0),
('6550', 54, 0, 0, 0, 0),
('6550', 55, 0, 0, 0, 0),
('6550', 56, 0, 0, 0, 0),
('6550', 57, 0, 0, 0, 0),
('6550', 58, 0, 0, 0, 0),
('6550', 59, 0, 0, 0, 0),
('6590', -15, 0, 0, 0, 0),
('6590', -14, 0, 0, 0, 0),
('6590', -13, 0, 0, 0, 0),
('6590', -12, 0, 0, 0, 0),
('6590', -11, 0, 0, 0, 0),
('6590', -10, 0, 0, 0, 0),
('6590', -9, 0, 0, 0, 0),
('6590', -8, 0, 0, 0, 0),
('6590', -7, 0, 0, 0, 0),
('6590', -6, 0, 0, 0, 0),
('6590', -5, 0, 0, 0, 0),
('6590', -4, 0, 0, 0, 0),
('6590', -3, 0, 0, 0, 0),
('6590', -2, 0, 0, 0, 0),
('6590', -1, 0, 0, 0, 0),
('6590', 0, 0, 0, 0, 0),
('6590', 1, 0, 0, 0, 0),
('6590', 2, 0, 0, 0, 0),
('6590', 3, 0, 0, 0, 0),
('6590', 4, 0, 0, 0, 0),
('6590', 5, 0, 0, 0, 0),
('6590', 6, 0, 0, 0, 0),
('6590', 7, 0, 0, 0, 0),
('6590', 8, 0, 0, 0, 0),
('6590', 9, 0, 0, 0, 0),
('6590', 10, 0, 0, 0, 0),
('6590', 11, 0, 0, 0, 0),
('6590', 12, 0, 0, 0, 0),
('6590', 13, 0, 0, 0, 0),
('6590', 14, 0, 0, 0, 0),
('6590', 15, 0, 0, 0, 0),
('6590', 16, 0, 0, 0, 0),
('6590', 17, 0, 0, 0, 0),
('6590', 18, 0, 0, 0, 0),
('6590', 19, 0, 0, 0, 0),
('6590', 20, 0, 0, 0, 0),
('6590', 21, 0, 0, 0, 0),
('6590', 22, 0, 0, 0, 0),
('6590', 23, 0, 0, 0, 0),
('6590', 24, 0, 0, 0, 0),
('6590', 25, 0, 0, 0, 0),
('6590', 26, 0, 0, 0, 0),
('6590', 27, 0, 0, 0, 0),
('6590', 28, 0, 0, 0, 0),
('6590', 29, 0, 0, 0, 0),
('6590', 30, 0, 0, 0, 0),
('6590', 31, 0, 0, 0, 0),
('6590', 32, 0, 0, 0, 0),
('6590', 33, 0, 0, 0, 0),
('6590', 34, 0, 0, 0, 0),
('6590', 35, 0, 0, 0, 0),
('6590', 36, 0, 0, 0, 0),
('6590', 37, 0, 0, 0, 0),
('6590', 38, 0, 0, 0, 0),
('6590', 39, 0, 0, 0, 0),
('6590', 40, 0, 0, 0, 0),
('6590', 41, 0, 0, 0, 0),
('6590', 42, 0, 0, 0, 0),
('6590', 43, 0, 0, 0, 0),
('6590', 44, 0, 0, 0, 0),
('6590', 45, 0, 0, 0, 0),
('6590', 46, 0, 0, 0, 0),
('6590', 47, 0, 0, 0, 0),
('6590', 48, 0, 0, 0, 0),
('6590', 49, 0, 0, 0, 0),
('6590', 50, 0, 0, 0, 0),
('6590', 51, 0, 0, 0, 0),
('6590', 52, 0, 0, 0, 0),
('6590', 53, 0, 0, 0, 0),
('6590', 54, 0, 0, 0, 0),
('6590', 55, 0, 0, 0, 0),
('6590', 56, 0, 0, 0, 0),
('6590', 57, 0, 0, 0, 0),
('6590', 58, 0, 0, 0, 0),
('6590', 59, 0, 0, 0, 0),
('6600', -15, 0, 0, 0, 0),
('6600', -14, 0, 0, 0, 0),
('6600', -13, 0, 0, 0, 0),
('6600', -12, 0, 0, 0, 0),
('6600', -11, 0, 0, 0, 0),
('6600', -10, 0, 0, 0, 0),
('6600', -9, 0, 0, 0, 0),
('6600', -8, 0, 0, 0, 0),
('6600', -7, 0, 0, 0, 0),
('6600', -6, 0, 0, 0, 0),
('6600', -5, 0, 0, 0, 0),
('6600', -4, 0, 0, 0, 0),
('6600', -3, 0, 0, 0, 0),
('6600', -2, 0, 0, 0, 0),
('6600', -1, 0, 0, 0, 0),
('6600', 0, 0, 0, 0, 0),
('6600', 1, 0, 0, 0, 0),
('6600', 2, 0, 0, 0, 0),
('6600', 3, 0, 0, 0, 0),
('6600', 4, 0, 0, 0, 0),
('6600', 5, 0, 0, 0, 0),
('6600', 6, 0, 0, 0, 0),
('6600', 7, 0, 0, 0, 0),
('6600', 8, 0, 0, 0, 0),
('6600', 9, 0, 0, 0, 0),
('6600', 10, 0, 0, 0, 0),
('6600', 11, 0, 0, 0, 0),
('6600', 12, 0, 0, 0, 0),
('6600', 13, 0, 0, 0, 0),
('6600', 14, 0, 0, 0, 0),
('6600', 15, 0, 0, 0, 0),
('6600', 16, 0, 0, 0, 0),
('6600', 17, 0, 0, 0, 0),
('6600', 18, 0, 0, 0, 0),
('6600', 19, 0, 0, 0, 0),
('6600', 20, 0, 0, 0, 0),
('6600', 21, 0, 0, 0, 0),
('6600', 22, 0, 0, 0, 0),
('6600', 23, 0, 0, 0, 0),
('6600', 24, 0, 0, 0, 0),
('6600', 25, 0, 0, 0, 0),
('6600', 26, 0, 0, 0, 0),
('6600', 27, 0, 0, 0, 0),
('6600', 28, 0, 0, 0, 0),
('6600', 29, 0, 0, 0, 0),
('6600', 30, 0, 0, 0, 0),
('6600', 31, 0, 0, 0, 0),
('6600', 32, 0, 0, 0, 0),
('6600', 33, 0, 0, 0, 0),
('6600', 34, 0, 0, 0, 0),
('6600', 35, 0, 0, 0, 0),
('6600', 36, 0, 0, 0, 0),
('6600', 37, 0, 0, 0, 0),
('6600', 38, 0, 0, 0, 0),
('6600', 39, 0, 0, 0, 0),
('6600', 40, 0, 0, 0, 0),
('6600', 41, 0, 0, 0, 0),
('6600', 42, 0, 0, 0, 0),
('6600', 43, 0, 0, 0, 0),
('6600', 44, 0, 0, 0, 0),
('6600', 45, 0, 0, 0, 0),
('6600', 46, 0, 0, 0, 0),
('6600', 47, 0, 0, 0, 0),
('6600', 48, 0, 0, 0, 0),
('6600', 49, 0, 0, 0, 0),
('6600', 50, 0, 0, 0, 0),
('6600', 51, 0, 0, 0, 0),
('6600', 52, 0, 0, 0, 0),
('6600', 53, 0, 0, 0, 0),
('6600', 54, 0, 0, 0, 0),
('6600', 55, 0, 0, 0, 0),
('6600', 56, 0, 0, 0, 0),
('6600', 57, 0, 0, 0, 0),
('6600', 58, 0, 0, 0, 0),
('6600', 59, 0, 0, 0, 0),
('6700', -15, 0, 0, 0, 0),
('6700', -14, 0, 0, 0, 0),
('6700', -13, 0, 0, 0, 0),
('6700', -12, 0, 0, 0, 0),
('6700', -11, 0, 0, 0, 0),
('6700', -10, 0, 0, 0, 0),
('6700', -9, 0, 0, 0, 0),
('6700', -8, 0, 0, 0, 0),
('6700', -7, 0, 0, 0, 0),
('6700', -6, 0, 0, 0, 0),
('6700', -5, 0, 0, 0, 0),
('6700', -4, 0, 0, 0, 0),
('6700', -3, 0, 0, 0, 0),
('6700', -2, 0, 0, 0, 0),
('6700', -1, 0, 0, 0, 0),
('6700', 0, 0, 0, 0, 0),
('6700', 1, 0, 0, 0, 0),
('6700', 2, 0, 0, 0, 0),
('6700', 3, 0, 0, 0, 0),
('6700', 4, 0, 0, 0, 0),
('6700', 5, 0, 0, 0, 0),
('6700', 6, 0, 0, 0, 0),
('6700', 7, 0, 0, 0, 0),
('6700', 8, 0, 0, 0, 0),
('6700', 9, 0, 0, 0, 0),
('6700', 10, 0, 0, 0, 0),
('6700', 11, 0, 0, 0, 0),
('6700', 12, 0, 0, 0, 0),
('6700', 13, 0, 0, 0, 0),
('6700', 14, 0, 0, 0, 0),
('6700', 15, 0, 0, 0, 0),
('6700', 16, 0, 0, 0, 0),
('6700', 17, 0, 0, 0, 0),
('6700', 18, 0, 0, 0, 0),
('6700', 19, 0, 0, 0, 0),
('6700', 20, 0, 0, 0, 0),
('6700', 21, 0, 0, 0, 0),
('6700', 22, 0, 0, 0, 0),
('6700', 23, 0, 0, 0, 0),
('6700', 24, 0, 0, 0, 0),
('6700', 25, 0, 0, 0, 0),
('6700', 26, 0, 0, 0, 0),
('6700', 27, 0, 0, 0, 0),
('6700', 28, 0, 0, 0, 0),
('6700', 29, 0, 0, 0, 0),
('6700', 30, 0, 0, 0, 0),
('6700', 31, 0, 0, 0, 0),
('6700', 32, 0, 0, 0, 0),
('6700', 33, 0, 0, 0, 0),
('6700', 34, 0, 0, 0, 0),
('6700', 35, 0, 0, 0, 0),
('6700', 36, 0, 0, 0, 0),
('6700', 37, 0, 0, 0, 0),
('6700', 38, 0, 0, 0, 0),
('6700', 39, 0, 0, 0, 0),
('6700', 40, 0, 0, 0, 0),
('6700', 41, 0, 0, 0, 0),
('6700', 42, 0, 0, 0, 0),
('6700', 43, 0, 0, 0, 0),
('6700', 44, 0, 0, 0, 0),
('6700', 45, 0, 0, 0, 0),
('6700', 46, 0, 0, 0, 0),
('6700', 47, 0, 0, 0, 0),
('6700', 48, 0, 0, 0, 0),
('6700', 49, 0, 0, 0, 0),
('6700', 50, 0, 0, 0, 0),
('6700', 51, 0, 0, 0, 0),
('6700', 52, 0, 0, 0, 0),
('6700', 53, 0, 0, 0, 0),
('6700', 54, 0, 0, 0, 0),
('6700', 55, 0, 0, 0, 0),
('6700', 56, 0, 0, 0, 0),
('6700', 57, 0, 0, 0, 0),
('6700', 58, 0, 0, 0, 0),
('6700', 59, 0, 0, 0, 0),
('6800', -15, 0, 0, 0, 0),
('6800', -14, 0, 0, 0, 0),
('6800', -13, 0, 0, 0, 0),
('6800', -12, 0, 0, 0, 0),
('6800', -11, 0, 0, 0, 0),
('6800', -10, 0, 0, 0, 0),
('6800', -9, 0, 0, 0, 0),
('6800', -8, 0, 0, 0, 0),
('6800', -7, 0, 0, 0, 0),
('6800', -6, 0, 0, 0, 0),
('6800', -5, 0, 0, 0, 0),
('6800', -4, 0, 0, 0, 0),
('6800', -3, 0, 0, 0, 0),
('6800', -2, 0, 0, 0, 0),
('6800', -1, 0, 0, 0, 0),
('6800', 0, 0, 0, 0, 0),
('6800', 1, 0, 0, 0, 0),
('6800', 2, 0, 0, 0, 0),
('6800', 3, 0, 0, 0, 0),
('6800', 4, 0, 0, 0, 0),
('6800', 5, 0, 0, 0, 0),
('6800', 6, 0, 0, 0, 0),
('6800', 7, 0, 0, 0, 0),
('6800', 8, 0, 0, 0, 0),
('6800', 9, 0, 0, 0, 0),
('6800', 10, 0, 0, 0, 0),
('6800', 11, 0, 0, 0, 0),
('6800', 12, 0, 0, 0, 0),
('6800', 13, 0, 0, 0, 0),
('6800', 14, 0, 0, 0, 0),
('6800', 15, 0, 0, 0, 0),
('6800', 16, 0, 0, 0, 0),
('6800', 17, 0, 0, 0, 0),
('6800', 18, 0, 0, 0, 0),
('6800', 19, 0, 0, 0, 0),
('6800', 20, 0, 0, 0, 0),
('6800', 21, 0, 0, 0, 0),
('6800', 22, 0, 0, 0, 0),
('6800', 23, 0, 0, 0, 0),
('6800', 24, 0, 0, 0, 0),
('6800', 25, 0, 0, 0, 0),
('6800', 26, 0, 0, 0, 0),
('6800', 27, 0, 0, 0, 0),
('6800', 28, 0, 0, 0, 0),
('6800', 29, 0, 0, 0, 0),
('6800', 30, 0, 0, 0, 0),
('6800', 31, 0, 0, 0, 0),
('6800', 32, 0, 0, 0, 0),
('6800', 33, 0, 0, 0, 0),
('6800', 34, 0, 0, 0, 0),
('6800', 35, 0, 0, 0, 0),
('6800', 36, 0, 0, 0, 0),
('6800', 37, 0, 0, 0, 0),
('6800', 38, 0, 0, 0, 0),
('6800', 39, 0, 0, 0, 0),
('6800', 40, 0, 0, 0, 0),
('6800', 41, 0, 0, 0, 0),
('6800', 42, 0, 0, 0, 0),
('6800', 43, 0, 0, 0, 0),
('6800', 44, 0, 0, 0, 0),
('6800', 45, 0, 0, 0, 0),
('6800', 46, 0, 0, 0, 0),
('6800', 47, 0, 0, 0, 0),
('6800', 48, 0, 0, 0, 0),
('6800', 49, 0, 0, 0, 0),
('6800', 50, 0, 0, 0, 0),
('6800', 51, 0, 0, 0, 0),
('6800', 52, 0, 0, 0, 0),
('6800', 53, 0, 0, 0, 0),
('6800', 54, 0, 0, 0, 0),
('6800', 55, 0, 0, 0, 0),
('6800', 56, 0, 0, 0, 0),
('6800', 57, 0, 0, 0, 0),
('6800', 58, 0, 0, 0, 0),
('6800', 59, 0, 0, 0, 0),
('6900', -15, 0, 0, 0, 0),
('6900', -14, 0, 0, 0, 0),
('6900', -13, 0, 0, 0, 0),
('6900', -12, 0, 0, 0, 0),
('6900', -11, 0, 0, 0, 0),
('6900', -10, 0, 0, 0, 0),
('6900', -9, 0, 0, 0, 0),
('6900', -8, 0, 0, 0, 0),
('6900', -7, 0, 0, 0, 0),
('6900', -6, 0, 0, 0, 0),
('6900', -5, 0, 0, 0, 0),
('6900', -4, 0, 0, 0, 0),
('6900', -3, 0, 0, 0, 0),
('6900', -2, 0, 0, 0, 0),
('6900', -1, 0, 0, 0, 0),
('6900', 0, 0, 0, 0, 0),
('6900', 1, 0, 0, 0, 0),
('6900', 2, 0, 0, 0, 0),
('6900', 3, 0, 0, 0, 0),
('6900', 4, 0, 0, 0, 0),
('6900', 5, 0, 0, 0, 0),
('6900', 6, 0, 0, 0, 0),
('6900', 7, 0, 0, 0, 0),
('6900', 8, 0, 0, 0, 0),
('6900', 9, 0, 0, 0, 0),
('6900', 10, 0, 0, 0, 0),
('6900', 11, 0, 0, 0, 0),
('6900', 12, 0, 0, 0, 0),
('6900', 13, 0, 0, 0, 0),
('6900', 14, 0, 0, 0, 0),
('6900', 15, 0, 0, 0, 0),
('6900', 16, 0, 0, 0, 0),
('6900', 17, 0, 0, 0, 0),
('6900', 18, 0, 0, 0, 0),
('6900', 19, 0, 0, 0, 0),
('6900', 20, 0, 0, 0, 0),
('6900', 21, 0, 0, 0, 0),
('6900', 22, 0, 0, 0, 0),
('6900', 23, 0, 0, 0, 0),
('6900', 24, 0, 0, 0, 0),
('6900', 25, 0, 0, 0, 0),
('6900', 26, 0, 0, 0, 0),
('6900', 27, 0, 0, 0, 0),
('6900', 28, 0, 0, 0, 0),
('6900', 29, 0, 0, 0, 0),
('6900', 30, 0, 0, 0, 0),
('6900', 31, 0, 0, 0, 0),
('6900', 32, 0, 0, 0, 0),
('6900', 33, 0, 0, 0, 0),
('6900', 34, 0, 0, 0, 0),
('6900', 35, 0, 0, 0, 0),
('6900', 36, 0, 0, 0, 0),
('6900', 37, 0, 0, 0, 0),
('6900', 38, 0, 0, 0, 0),
('6900', 39, 0, 0, 0, 0),
('6900', 40, 0, 0, 0, 0),
('6900', 41, 0, 0, 0, 0),
('6900', 42, 0, 0, 0, 0),
('6900', 43, 0, 0, 0, 0),
('6900', 44, 0, 0, 0, 0),
('6900', 45, 0, 0, 0, 0),
('6900', 46, 0, 0, 0, 0),
('6900', 47, 0, 0, 0, 0),
('6900', 48, 0, 0, 0, 0),
('6900', 49, 0, 0, 0, 0),
('6900', 50, 0, 0, 0, 0),
('6900', 51, 0, 0, 0, 0),
('6900', 52, 0, 0, 0, 0),
('6900', 53, 0, 0, 0, 0),
('6900', 54, 0, 0, 0, 0),
('6900', 55, 0, 0, 0, 0),
('6900', 56, 0, 0, 0, 0),
('6900', 57, 0, 0, 0, 0),
('6900', 58, 0, 0, 0, 0),
('6900', 59, 0, 0, 0, 0),
('7020', -15, 0, 0, 0, 0),
('7020', -14, 0, 0, 0, 0),
('7020', -13, 0, 0, 0, 0),
('7020', -12, 0, 0, 0, 0),
('7020', -11, 0, 0, 0, 0),
('7020', -10, 0, 0, 0, 0),
('7020', -9, 0, 0, 0, 0),
('7020', -8, 0, 0, 0, 0),
('7020', -7, 0, 0, 0, 0),
('7020', -6, 0, 0, 0, 0),
('7020', -5, 0, 0, 0, 0),
('7020', -4, 0, 0, 0, 0),
('7020', -3, 0, 0, 0, 0),
('7020', -2, 0, 0, 0, 0),
('7020', -1, 0, 0, 0, 0),
('7020', 0, 0, 0, 0, 0),
('7020', 1, 0, 0, 0, 0),
('7020', 2, 0, 0, 0, 0),
('7020', 3, 0, 0, 0, 0),
('7020', 4, 0, 0, 0, 0),
('7020', 5, 0, 0, 0, 0),
('7020', 6, 0, 0, 0, 0),
('7020', 7, 0, 0, 0, 0),
('7020', 8, 0, 0, 0, 0),
('7020', 9, 0, 0, 0, 0),
('7020', 10, 0, 0, 0, 0),
('7020', 11, 0, 0, 0, 0),
('7020', 12, 0, 0, 0, 0),
('7020', 13, 0, 0, 0, 0),
('7020', 14, 0, 0, 0, 0),
('7020', 15, 0, 0, 0, 0),
('7020', 16, 0, 0, 0, 0),
('7020', 17, 0, 0, 0, 0),
('7020', 18, 0, 0, 0, 0),
('7020', 19, 0, 0, 0, 0),
('7020', 20, 0, 0, 0, 0),
('7020', 21, 0, 0, 0, 0),
('7020', 22, 0, 0, 0, 0),
('7020', 23, 0, 0, 0, 0),
('7020', 24, 0, 0, 0, 0),
('7020', 25, 0, 0, 0, 0),
('7020', 26, 0, 0, 0, 0),
('7020', 27, 0, 0, 0, 0),
('7020', 28, 0, 0, 0, 0),
('7020', 29, 0, 0, 0, 0),
('7020', 30, 0, 0, 0, 0),
('7020', 31, 0, 0, 0, 0),
('7020', 32, 0, 0, 0, 0),
('7020', 33, 0, 0, 0, 0),
('7020', 34, 0, 0, 0, 0),
('7020', 35, 0, 0, 0, 0),
('7020', 36, 0, 0, 0, 0),
('7020', 37, 0, 0, 0, 0),
('7020', 38, 0, 0, 0, 0),
('7020', 39, 0, 0, 0, 0),
('7020', 40, 0, 0, 0, 0),
('7020', 41, 0, 0, 0, 0),
('7020', 42, 0, 0, 0, 0),
('7020', 43, 0, 0, 0, 0),
('7020', 44, 0, 0, 0, 0),
('7020', 45, 0, 0, 0, 0),
('7020', 46, 0, 0, 0, 0),
('7020', 47, 0, 0, 0, 0),
('7020', 48, 0, 0, 0, 0),
('7020', 49, 0, 0, 0, 0),
('7020', 50, 0, 0, 0, 0),
('7020', 51, 0, 0, 0, 0),
('7020', 52, 0, 0, 0, 0),
('7020', 53, 0, 0, 0, 0),
('7020', 54, 0, 0, 0, 0),
('7020', 55, 0, 0, 0, 0),
('7020', 56, 0, 0, 0, 0),
('7020', 57, 0, 0, 0, 0),
('7020', 58, 0, 0, 0, 0),
('7020', 59, 0, 0, 0, 0),
('7030', -15, 0, 0, 0, 0),
('7030', -14, 0, 0, 0, 0),
('7030', -13, 0, 0, 0, 0),
('7030', -12, 0, 0, 0, 0),
('7030', -11, 0, 0, 0, 0),
('7030', -10, 0, 0, 0, 0),
('7030', -9, 0, 0, 0, 0),
('7030', -8, 0, 0, 0, 0),
('7030', -7, 0, 0, 0, 0),
('7030', -6, 0, 0, 0, 0),
('7030', -5, 0, 0, 0, 0),
('7030', -4, 0, 0, 0, 0),
('7030', -3, 0, 0, 0, 0),
('7030', -2, 0, 0, 0, 0),
('7030', -1, 0, 0, 0, 0),
('7030', 0, 0, 0, 0, 0),
('7030', 1, 0, 0, 0, 0),
('7030', 2, 0, 0, 0, 0),
('7030', 3, 0, 0, 0, 0),
('7030', 4, 0, 0, 0, 0),
('7030', 5, 0, 0, 0, 0),
('7030', 6, 0, 0, 0, 0),
('7030', 7, 0, 0, 0, 0),
('7030', 8, 0, 0, 0, 0),
('7030', 9, 0, 0, 0, 0),
('7030', 10, 0, 0, 0, 0),
('7030', 11, 0, 0, 0, 0),
('7030', 12, 0, 0, 0, 0),
('7030', 13, 0, 0, 0, 0),
('7030', 14, 0, 0, 0, 0),
('7030', 15, 0, 0, 0, 0),
('7030', 16, 0, 0, 0, 0),
('7030', 17, 0, 0, 0, 0),
('7030', 18, 0, 0, 0, 0),
('7030', 19, 0, 0, 0, 0),
('7030', 20, 0, 0, 0, 0),
('7030', 21, 0, 0, 0, 0),
('7030', 22, 0, 0, 0, 0),
('7030', 23, 0, 0, 0, 0),
('7030', 24, 0, 0, 0, 0),
('7030', 25, 0, 0, 0, 0),
('7030', 26, 0, 0, 0, 0),
('7030', 27, 0, 0, 0, 0),
('7030', 28, 0, 0, 0, 0),
('7030', 29, 0, 0, 0, 0),
('7030', 30, 0, 0, 0, 0),
('7030', 31, 0, 0, 0, 0),
('7030', 32, 0, 0, 0, 0),
('7030', 33, 0, 0, 0, 0),
('7030', 34, 0, 0, 0, 0),
('7030', 35, 0, 0, 0, 0),
('7030', 36, 0, 0, 0, 0),
('7030', 37, 0, 0, 0, 0),
('7030', 38, 0, 0, 0, 0),
('7030', 39, 0, 0, 0, 0),
('7030', 40, 0, 0, 0, 0),
('7030', 41, 0, 0, 0, 0),
('7030', 42, 0, 0, 0, 0),
('7030', 43, 0, 0, 0, 0),
('7030', 44, 0, 0, 0, 0),
('7030', 45, 0, 0, 0, 0),
('7030', 46, 0, 0, 0, 0),
('7030', 47, 0, 0, 0, 0),
('7030', 48, 0, 0, 0, 0),
('7030', 49, 0, 0, 0, 0),
('7030', 50, 0, 0, 0, 0),
('7030', 51, 0, 0, 0, 0),
('7030', 52, 0, 0, 0, 0),
('7030', 53, 0, 0, 0, 0),
('7030', 54, 0, 0, 0, 0),
('7030', 55, 0, 0, 0, 0),
('7030', 56, 0, 0, 0, 0),
('7030', 57, 0, 0, 0, 0),
('7030', 58, 0, 0, 0, 0),
('7030', 59, 0, 0, 0, 0),
('7040', -15, 0, 0, 0, 0),
('7040', -14, 0, 0, 0, 0),
('7040', -13, 0, 0, 0, 0),
('7040', -12, 0, 0, 0, 0),
('7040', -11, 0, 0, 0, 0),
('7040', -10, 0, 0, 0, 0),
('7040', -9, 0, 0, 0, 0),
('7040', -8, 0, 0, 0, 0),
('7040', -7, 0, 0, 0, 0),
('7040', -6, 0, 0, 0, 0),
('7040', -5, 0, 0, 0, 0),
('7040', -4, 0, 0, 0, 0),
('7040', -3, 0, 0, 0, 0),
('7040', -2, 0, 0, 0, 0),
('7040', -1, 0, 0, 0, 0),
('7040', 0, 0, 0, 0, 0),
('7040', 1, 0, 0, 0, 0),
('7040', 2, 0, 0, 0, 0),
('7040', 3, 0, 0, 0, 0),
('7040', 4, 0, 0, 0, 0),
('7040', 5, 0, 0, 0, 0),
('7040', 6, 0, 0, 0, 0),
('7040', 7, 0, 0, 0, 0),
('7040', 8, 0, 0, 0, 0),
('7040', 9, 0, 0, 0, 0),
('7040', 10, 0, 0, 0, 0),
('7040', 11, 0, 0, 0, 0),
('7040', 12, 0, 0, 0, 0),
('7040', 13, 0, 0, 0, 0),
('7040', 14, 0, 0, 0, 0),
('7040', 15, 0, 0, 0, 0),
('7040', 16, 0, 0, 0, 0),
('7040', 17, 0, 0, 0, 0),
('7040', 18, 0, 0, 0, 0),
('7040', 19, 0, 0, 0, 0),
('7040', 20, 0, 0, 0, 0),
('7040', 21, 0, 0, 0, 0),
('7040', 22, 0, 0, 0, 0),
('7040', 23, 0, 0, 0, 0),
('7040', 24, 0, 0, 0, 0),
('7040', 25, 0, 0, 0, 0),
('7040', 26, 0, 0, 0, 0),
('7040', 27, 0, 0, 0, 0),
('7040', 28, 0, 0, 0, 0),
('7040', 29, 0, 0, 0, 0),
('7040', 30, 0, 0, 0, 0),
('7040', 31, 0, 0, 0, 0),
('7040', 32, 0, 0, 0, 0),
('7040', 33, 0, 0, 0, 0),
('7040', 34, 0, 0, 0, 0),
('7040', 35, 0, 0, 0, 0),
('7040', 36, 0, 0, 0, 0),
('7040', 37, 0, 0, 0, 0),
('7040', 38, 0, 0, 0, 0),
('7040', 39, 0, 0, 0, 0),
('7040', 40, 0, 0, 0, 0),
('7040', 41, 0, 0, 0, 0),
('7040', 42, 0, 0, 0, 0),
('7040', 43, 0, 0, 0, 0),
('7040', 44, 0, 0, 0, 0),
('7040', 45, 0, 0, 0, 0),
('7040', 46, 0, 0, 0, 0),
('7040', 47, 0, 0, 0, 0),
('7040', 48, 0, 0, 0, 0),
('7040', 49, 0, 0, 0, 0),
('7040', 50, 0, 0, 0, 0),
('7040', 51, 0, 0, 0, 0),
('7040', 52, 0, 0, 0, 0),
('7040', 53, 0, 0, 0, 0),
('7040', 54, 0, 0, 0, 0),
('7040', 55, 0, 0, 0, 0),
('7040', 56, 0, 0, 0, 0),
('7040', 57, 0, 0, 0, 0),
('7040', 58, 0, 0, 0, 0),
('7040', 59, 0, 0, 0, 0),
('7050', -15, 0, 0, 0, 0),
('7050', -14, 0, 0, 0, 0),
('7050', -13, 0, 0, 0, 0),
('7050', -12, 0, 0, 0, 0),
('7050', -11, 0, 0, 0, 0),
('7050', -10, 0, 0, 0, 0),
('7050', -9, 0, 0, 0, 0),
('7050', -8, 0, 0, 0, 0),
('7050', -7, 0, 0, 0, 0),
('7050', -6, 0, 0, 0, 0),
('7050', -5, 0, 0, 0, 0),
('7050', -4, 0, 0, 0, 0),
('7050', -3, 0, 0, 0, 0),
('7050', -2, 0, 0, 0, 0),
('7050', -1, 0, 0, 0, 0),
('7050', 0, 0, 0, 0, 0),
('7050', 1, 0, 0, 0, 0),
('7050', 2, 0, 0, 0, 0),
('7050', 3, 0, 0, 0, 0),
('7050', 4, 0, 0, 0, 0),
('7050', 5, 0, 0, 0, 0),
('7050', 6, 0, 0, 0, 0),
('7050', 7, 0, 0, 0, 0),
('7050', 8, 0, 0, 0, 0),
('7050', 9, 0, 0, 0, 0),
('7050', 10, 0, 0, 0, 0),
('7050', 11, 0, 0, 0, 0),
('7050', 12, 0, 0, 0, 0),
('7050', 13, 0, 0, 0, 0),
('7050', 14, 0, 0, 0, 0),
('7050', 15, 0, 0, 0, 0),
('7050', 16, 0, 0, 0, 0),
('7050', 17, 0, 0, 0, 0),
('7050', 18, 0, 0, 0, 0),
('7050', 19, 0, 0, 0, 0),
('7050', 20, 0, 0, 0, 0),
('7050', 21, 0, 0, 0, 0),
('7050', 22, 0, 0, 0, 0),
('7050', 23, 0, 0, 0, 0),
('7050', 24, 0, 0, 0, 0),
('7050', 25, 0, 0, 0, 0),
('7050', 26, 0, 0, 0, 0),
('7050', 27, 0, 0, 0, 0),
('7050', 28, 0, 0, 0, 0),
('7050', 29, 0, 0, 0, 0),
('7050', 30, 0, 0, 0, 0),
('7050', 31, 0, 0, 0, 0),
('7050', 32, 0, 0, 0, 0),
('7050', 33, 0, 0, 0, 0),
('7050', 34, 0, 0, 0, 0),
('7050', 35, 0, 0, 0, 0),
('7050', 36, 0, 0, 0, 0),
('7050', 37, 0, 0, 0, 0),
('7050', 38, 0, 0, 0, 0),
('7050', 39, 0, 0, 0, 0),
('7050', 40, 0, 0, 0, 0),
('7050', 41, 0, 0, 0, 0),
('7050', 42, 0, 0, 0, 0),
('7050', 43, 0, 0, 0, 0),
('7050', 44, 0, 0, 0, 0),
('7050', 45, 0, 0, 0, 0),
('7050', 46, 0, 0, 0, 0),
('7050', 47, 0, 0, 0, 0),
('7050', 48, 0, 0, 0, 0),
('7050', 49, 0, 0, 0, 0),
('7050', 50, 0, 0, 0, 0),
('7050', 51, 0, 0, 0, 0),
('7050', 52, 0, 0, 0, 0),
('7050', 53, 0, 0, 0, 0),
('7050', 54, 0, 0, 0, 0),
('7050', 55, 0, 0, 0, 0),
('7050', 56, 0, 0, 0, 0),
('7050', 57, 0, 0, 0, 0),
('7050', 58, 0, 0, 0, 0),
('7050', 59, 0, 0, 0, 0),
('7060', -15, 0, 0, 0, 0),
('7060', -14, 0, 0, 0, 0),
('7060', -13, 0, 0, 0, 0),
('7060', -12, 0, 0, 0, 0),
('7060', -11, 0, 0, 0, 0),
('7060', -10, 0, 0, 0, 0),
('7060', -9, 0, 0, 0, 0),
('7060', -8, 0, 0, 0, 0),
('7060', -7, 0, 0, 0, 0),
('7060', -6, 0, 0, 0, 0),
('7060', -5, 0, 0, 0, 0),
('7060', -4, 0, 0, 0, 0),
('7060', -3, 0, 0, 0, 0),
('7060', -2, 0, 0, 0, 0),
('7060', -1, 0, 0, 0, 0),
('7060', 0, 0, 0, 0, 0),
('7060', 1, 0, 0, 0, 0),
('7060', 2, 0, 0, 0, 0),
('7060', 3, 0, 0, 0, 0),
('7060', 4, 0, 0, 0, 0),
('7060', 5, 0, 0, 0, 0),
('7060', 6, 0, 0, 0, 0),
('7060', 7, 0, 0, 0, 0),
('7060', 8, 0, 0, 0, 0),
('7060', 9, 0, 0, 0, 0),
('7060', 10, 0, 0, 0, 0),
('7060', 11, 0, 0, 0, 0),
('7060', 12, 0, 0, 0, 0),
('7060', 13, 0, 0, 0, 0),
('7060', 14, 0, 0, 0, 0),
('7060', 15, 0, 0, 0, 0),
('7060', 16, 0, 0, 0, 0),
('7060', 17, 0, 0, 0, 0),
('7060', 18, 0, 0, 0, 0),
('7060', 19, 0, 0, 0, 0),
('7060', 20, 0, 0, 0, 0),
('7060', 21, 0, 0, 0, 0),
('7060', 22, 0, 0, 0, 0),
('7060', 23, 0, 0, 0, 0),
('7060', 24, 0, 0, 0, 0),
('7060', 25, 0, 0, 0, 0),
('7060', 26, 0, 0, 0, 0),
('7060', 27, 0, 0, 0, 0),
('7060', 28, 0, 0, 0, 0),
('7060', 29, 0, 0, 0, 0),
('7060', 30, 0, 0, 0, 0),
('7060', 31, 0, 0, 0, 0),
('7060', 32, 0, 0, 0, 0),
('7060', 33, 0, 0, 0, 0),
('7060', 34, 0, 0, 0, 0),
('7060', 35, 0, 0, 0, 0),
('7060', 36, 0, 0, 0, 0),
('7060', 37, 0, 0, 0, 0),
('7060', 38, 0, 0, 0, 0),
('7060', 39, 0, 0, 0, 0),
('7060', 40, 0, 0, 0, 0),
('7060', 41, 0, 0, 0, 0),
('7060', 42, 0, 0, 0, 0),
('7060', 43, 0, 0, 0, 0),
('7060', 44, 0, 0, 0, 0),
('7060', 45, 0, 0, 0, 0),
('7060', 46, 0, 0, 0, 0),
('7060', 47, 0, 0, 0, 0),
('7060', 48, 0, 0, 0, 0),
('7060', 49, 0, 0, 0, 0),
('7060', 50, 0, 0, 0, 0),
('7060', 51, 0, 0, 0, 0),
('7060', 52, 0, 0, 0, 0),
('7060', 53, 0, 0, 0, 0),
('7060', 54, 0, 0, 0, 0),
('7060', 55, 0, 0, 0, 0),
('7060', 56, 0, 0, 0, 0),
('7060', 57, 0, 0, 0, 0),
('7060', 58, 0, 0, 0, 0),
('7060', 59, 0, 0, 0, 0),
('7070', -15, 0, 0, 0, 0),
('7070', -14, 0, 0, 0, 0),
('7070', -13, 0, 0, 0, 0),
('7070', -12, 0, 0, 0, 0),
('7070', -11, 0, 0, 0, 0),
('7070', -10, 0, 0, 0, 0),
('7070', -9, 0, 0, 0, 0),
('7070', -8, 0, 0, 0, 0),
('7070', -7, 0, 0, 0, 0),
('7070', -6, 0, 0, 0, 0),
('7070', -5, 0, 0, 0, 0),
('7070', -4, 0, 0, 0, 0),
('7070', -3, 0, 0, 0, 0),
('7070', -2, 0, 0, 0, 0),
('7070', -1, 0, 0, 0, 0),
('7070', 0, 0, 0, 0, 0),
('7070', 1, 0, 0, 0, 0),
('7070', 2, 0, 0, 0, 0),
('7070', 3, 0, 0, 0, 0),
('7070', 4, 0, 0, 0, 0),
('7070', 5, 0, 0, 0, 0),
('7070', 6, 0, 0, 0, 0),
('7070', 7, 0, 0, 0, 0),
('7070', 8, 0, 0, 0, 0),
('7070', 9, 0, 0, 0, 0),
('7070', 10, 0, 0, 0, 0),
('7070', 11, 0, 0, 0, 0),
('7070', 12, 0, 0, 0, 0),
('7070', 13, 0, 0, 0, 0),
('7070', 14, 0, 0, 0, 0),
('7070', 15, 0, 0, 0, 0),
('7070', 16, 0, 0, 0, 0),
('7070', 17, 0, 0, 0, 0),
('7070', 18, 0, 0, 0, 0),
('7070', 19, 0, 0, 0, 0),
('7070', 20, 0, 0, 0, 0),
('7070', 21, 0, 0, 0, 0),
('7070', 22, 0, 0, 0, 0),
('7070', 23, 0, 0, 0, 0),
('7070', 24, 0, 0, 0, 0),
('7070', 25, 0, 0, 0, 0),
('7070', 26, 0, 0, 0, 0),
('7070', 27, 0, 0, 0, 0),
('7070', 28, 0, 0, 0, 0),
('7070', 29, 0, 0, 0, 0),
('7070', 30, 0, 0, 0, 0),
('7070', 31, 0, 0, 0, 0),
('7070', 32, 0, 0, 0, 0),
('7070', 33, 0, 0, 0, 0),
('7070', 34, 0, 0, 0, 0),
('7070', 35, 0, 0, 0, 0),
('7070', 36, 0, 0, 0, 0),
('7070', 37, 0, 0, 0, 0),
('7070', 38, 0, 0, 0, 0),
('7070', 39, 0, 0, 0, 0),
('7070', 40, 0, 0, 0, 0),
('7070', 41, 0, 0, 0, 0),
('7070', 42, 0, 0, 0, 0),
('7070', 43, 0, 0, 0, 0),
('7070', 44, 0, 0, 0, 0),
('7070', 45, 0, 0, 0, 0),
('7070', 46, 0, 0, 0, 0),
('7070', 47, 0, 0, 0, 0),
('7070', 48, 0, 0, 0, 0),
('7070', 49, 0, 0, 0, 0),
('7070', 50, 0, 0, 0, 0),
('7070', 51, 0, 0, 0, 0),
('7070', 52, 0, 0, 0, 0),
('7070', 53, 0, 0, 0, 0),
('7070', 54, 0, 0, 0, 0),
('7070', 55, 0, 0, 0, 0),
('7070', 56, 0, 0, 0, 0),
('7070', 57, 0, 0, 0, 0),
('7070', 58, 0, 0, 0, 0),
('7070', 59, 0, 0, 0, 0),
('7080', -15, 0, 0, 0, 0),
('7080', -14, 0, 0, 0, 0),
('7080', -13, 0, 0, 0, 0),
('7080', -12, 0, 0, 0, 0),
('7080', -11, 0, 0, 0, 0),
('7080', -10, 0, 0, 0, 0),
('7080', -9, 0, 0, 0, 0),
('7080', -8, 0, 0, 0, 0),
('7080', -7, 0, 0, 0, 0),
('7080', -6, 0, 0, 0, 0),
('7080', -5, 0, 0, 0, 0),
('7080', -4, 0, 0, 0, 0),
('7080', -3, 0, 0, 0, 0),
('7080', -2, 0, 0, 0, 0),
('7080', -1, 0, 0, 0, 0),
('7080', 0, 0, 0, 0, 0),
('7080', 1, 0, 0, 0, 0),
('7080', 2, 0, 0, 0, 0),
('7080', 3, 0, 0, 0, 0),
('7080', 4, 0, 0, 0, 0),
('7080', 5, 0, 0, 0, 0),
('7080', 6, 0, 0, 0, 0),
('7080', 7, 0, 0, 0, 0),
('7080', 8, 0, 0, 0, 0),
('7080', 9, 0, 0, 0, 0),
('7080', 10, 0, 0, 0, 0),
('7080', 11, 0, 0, 0, 0),
('7080', 12, 0, 0, 0, 0),
('7080', 13, 0, 0, 0, 0),
('7080', 14, 0, 0, 0, 0),
('7080', 15, 0, 0, 0, 0),
('7080', 16, 0, 0, 0, 0),
('7080', 17, 0, 0, 0, 0),
('7080', 18, 0, 0, 0, 0),
('7080', 19, 0, 0, 0, 0),
('7080', 20, 0, 0, 0, 0),
('7080', 21, 0, 0, 0, 0),
('7080', 22, 0, 0, 0, 0),
('7080', 23, 0, 0, 0, 0),
('7080', 24, 0, 0, 0, 0),
('7080', 25, 0, 0, 0, 0),
('7080', 26, 0, 0, 0, 0),
('7080', 27, 0, 0, 0, 0),
('7080', 28, 0, 0, 0, 0),
('7080', 29, 0, 0, 0, 0),
('7080', 30, 0, 0, 0, 0),
('7080', 31, 0, 0, 0, 0),
('7080', 32, 0, 0, 0, 0),
('7080', 33, 0, 0, 0, 0),
('7080', 34, 0, 0, 0, 0),
('7080', 35, 0, 0, 0, 0),
('7080', 36, 0, 0, 0, 0),
('7080', 37, 0, 0, 0, 0),
('7080', 38, 0, 0, 0, 0),
('7080', 39, 0, 0, 0, 0),
('7080', 40, 0, 0, 0, 0),
('7080', 41, 0, 0, 0, 0),
('7080', 42, 0, 0, 0, 0),
('7080', 43, 0, 0, 0, 0),
('7080', 44, 0, 0, 0, 0),
('7080', 45, 0, 0, 0, 0),
('7080', 46, 0, 0, 0, 0),
('7080', 47, 0, 0, 0, 0),
('7080', 48, 0, 0, 0, 0),
('7080', 49, 0, 0, 0, 0),
('7080', 50, 0, 0, 0, 0),
('7080', 51, 0, 0, 0, 0),
('7080', 52, 0, 0, 0, 0),
('7080', 53, 0, 0, 0, 0),
('7080', 54, 0, 0, 0, 0),
('7080', 55, 0, 0, 0, 0),
('7080', 56, 0, 0, 0, 0),
('7080', 57, 0, 0, 0, 0),
('7080', 58, 0, 0, 0, 0),
('7080', 59, 0, 0, 0, 0),
('7090', -15, 0, 0, 0, 0),
('7090', -14, 0, 0, 0, 0),
('7090', -13, 0, 0, 0, 0),
('7090', -12, 0, 0, 0, 0),
('7090', -11, 0, 0, 0, 0),
('7090', -10, 0, 0, 0, 0),
('7090', -9, 0, 0, 0, 0),
('7090', -8, 0, 0, 0, 0),
('7090', -7, 0, 0, 0, 0),
('7090', -6, 0, 0, 0, 0),
('7090', -5, 0, 0, 0, 0),
('7090', -4, 0, 0, 0, 0),
('7090', -3, 0, 0, 0, 0),
('7090', -2, 0, 0, 0, 0),
('7090', -1, 0, 0, 0, 0),
('7090', 0, 0, 0, 0, 0),
('7090', 1, 0, 0, 0, 0),
('7090', 2, 0, 0, 0, 0),
('7090', 3, 0, 0, 0, 0),
('7090', 4, 0, 0, 0, 0),
('7090', 5, 0, 0, 0, 0),
('7090', 6, 0, 0, 0, 0),
('7090', 7, 0, 0, 0, 0),
('7090', 8, 0, 0, 0, 0),
('7090', 9, 0, 0, 0, 0),
('7090', 10, 0, 0, 0, 0),
('7090', 11, 0, 0, 0, 0),
('7090', 12, 0, 0, 0, 0),
('7090', 13, 0, 0, 0, 0),
('7090', 14, 0, 0, 0, 0),
('7090', 15, 0, 0, 0, 0),
('7090', 16, 0, 0, 0, 0),
('7090', 17, 0, 0, 0, 0),
('7090', 18, 0, 0, 0, 0),
('7090', 19, 0, 0, 0, 0),
('7090', 20, 0, 0, 0, 0),
('7090', 21, 0, 0, 0, 0),
('7090', 22, 0, 0, 0, 0),
('7090', 23, 0, 0, 0, 0),
('7090', 24, 0, 0, 0, 0),
('7090', 25, 0, 0, 0, 0),
('7090', 26, 0, 0, 0, 0),
('7090', 27, 0, 0, 0, 0),
('7090', 28, 0, 0, 0, 0),
('7090', 29, 0, 0, 0, 0),
('7090', 30, 0, 0, 0, 0),
('7090', 31, 0, 0, 0, 0),
('7090', 32, 0, 0, 0, 0),
('7090', 33, 0, 0, 0, 0),
('7090', 34, 0, 0, 0, 0),
('7090', 35, 0, 0, 0, 0),
('7090', 36, 0, 0, 0, 0),
('7090', 37, 0, 0, 0, 0),
('7090', 38, 0, 0, 0, 0),
('7090', 39, 0, 0, 0, 0),
('7090', 40, 0, 0, 0, 0),
('7090', 41, 0, 0, 0, 0),
('7090', 42, 0, 0, 0, 0),
('7090', 43, 0, 0, 0, 0),
('7090', 44, 0, 0, 0, 0),
('7090', 45, 0, 0, 0, 0),
('7090', 46, 0, 0, 0, 0),
('7090', 47, 0, 0, 0, 0),
('7090', 48, 0, 0, 0, 0),
('7090', 49, 0, 0, 0, 0),
('7090', 50, 0, 0, 0, 0),
('7090', 51, 0, 0, 0, 0),
('7090', 52, 0, 0, 0, 0),
('7090', 53, 0, 0, 0, 0),
('7090', 54, 0, 0, 0, 0),
('7090', 55, 0, 0, 0, 0),
('7090', 56, 0, 0, 0, 0),
('7090', 57, 0, 0, 0, 0),
('7090', 58, 0, 0, 0, 0),
('7090', 59, 0, 0, 0, 0),
('7100', -15, 0, 0, 0, 0),
('7100', -14, 0, 0, 0, 0),
('7100', -13, 0, 0, 0, 0),
('7100', -12, 0, 0, 0, 0),
('7100', -11, 0, 0, 0, 0),
('7100', -10, 0, 0, 0, 0),
('7100', -9, 0, 0, 0, 0),
('7100', -8, 0, 0, 0, 0),
('7100', -7, 0, 0, 0, 0),
('7100', -6, 0, 0, 0, 0),
('7100', -5, 0, 0, 0, 0),
('7100', -4, 0, 0, 0, 0),
('7100', -3, 0, 0, 0, 0),
('7100', -2, 0, 0, 0, 0),
('7100', -1, 0, 0, 0, 0),
('7100', 0, 0, 0, 0, 0),
('7100', 1, 0, 0, 0, 0);
INSERT INTO `chartdetails` (`accountcode`, `period`, `budget`, `actual`, `bfwd`, `bfwdbudget`) VALUES
('7100', 2, 0, 0, 0, 0),
('7100', 3, 0, 0, 0, 0),
('7100', 4, 0, 0, 0, 0),
('7100', 5, 0, 0, 0, 0),
('7100', 6, 0, 0, 0, 0),
('7100', 7, 0, 0, 0, 0),
('7100', 8, 0, 0, 0, 0),
('7100', 9, 0, 0, 0, 0),
('7100', 10, 0, 0, 0, 0),
('7100', 11, 0, 0, 0, 0),
('7100', 12, 0, 0, 0, 0),
('7100', 13, 0, 0, 0, 0),
('7100', 14, 0, 0, 0, 0),
('7100', 15, 0, 0, 0, 0),
('7100', 16, 0, 0, 0, 0),
('7100', 17, 0, 0, 0, 0),
('7100', 18, 0, 0, 0, 0),
('7100', 19, 0, 0, 0, 0),
('7100', 20, 0, 0, 0, 0),
('7100', 21, 0, 0, 0, 0),
('7100', 22, 0, 0, 0, 0),
('7100', 23, 0, 0, 0, 0),
('7100', 24, 0, 0, 0, 0),
('7100', 25, 0, 0, 0, 0),
('7100', 26, 0, 0, 0, 0),
('7100', 27, 0, 0, 0, 0),
('7100', 28, 0, 0, 0, 0),
('7100', 29, 0, 0, 0, 0),
('7100', 30, 0, 0, 0, 0),
('7100', 31, 0, 0, 0, 0),
('7100', 32, 0, 0, 0, 0),
('7100', 33, 0, 0, 0, 0),
('7100', 34, 0, 0, 0, 0),
('7100', 35, 0, 0, 0, 0),
('7100', 36, 0, 0, 0, 0),
('7100', 37, 0, 0, 0, 0),
('7100', 38, 0, 0, 0, 0),
('7100', 39, 0, 0, 0, 0),
('7100', 40, 0, 0, 0, 0),
('7100', 41, 0, 0, 0, 0),
('7100', 42, 0, 0, 0, 0),
('7100', 43, 0, 0, 0, 0),
('7100', 44, 0, 0, 0, 0),
('7100', 45, 0, 0, 0, 0),
('7100', 46, 0, 0, 0, 0),
('7100', 47, 0, 0, 0, 0),
('7100', 48, 0, 0, 0, 0),
('7100', 49, 0, 0, 0, 0),
('7100', 50, 0, 0, 0, 0),
('7100', 51, 0, 0, 0, 0),
('7100', 52, 0, 0, 0, 0),
('7100', 53, 0, 0, 0, 0),
('7100', 54, 0, 0, 0, 0),
('7100', 55, 0, 0, 0, 0),
('7100', 56, 0, 0, 0, 0),
('7100', 57, 0, 0, 0, 0),
('7100', 58, 0, 0, 0, 0),
('7100', 59, 0, 0, 0, 0),
('7150', -15, 0, 0, 0, 0),
('7150', -14, 0, 0, 0, 0),
('7150', -13, 0, 0, 0, 0),
('7150', -12, 0, 0, 0, 0),
('7150', -11, 0, 0, 0, 0),
('7150', -10, 0, 0, 0, 0),
('7150', -9, 0, 0, 0, 0),
('7150', -8, 0, 0, 0, 0),
('7150', -7, 0, 0, 0, 0),
('7150', -6, 0, 0, 0, 0),
('7150', -5, 0, 0, 0, 0),
('7150', -4, 0, 0, 0, 0),
('7150', -3, 0, 0, 0, 0),
('7150', -2, 0, 0, 0, 0),
('7150', -1, 0, 0, 0, 0),
('7150', 0, 0, 0, 0, 0),
('7150', 1, 0, 0, 0, 0),
('7150', 2, 0, 0, 0, 0),
('7150', 3, 0, 0, 0, 0),
('7150', 4, 0, 0, 0, 0),
('7150', 5, 0, 0, 0, 0),
('7150', 6, 0, 0, 0, 0),
('7150', 7, 0, 0, 0, 0),
('7150', 8, 0, 0, 0, 0),
('7150', 9, 0, 0, 0, 0),
('7150', 10, 0, 0, 0, 0),
('7150', 11, 0, 0, 0, 0),
('7150', 12, 0, 0, 0, 0),
('7150', 13, 0, 0, 0, 0),
('7150', 14, 0, 0, 0, 0),
('7150', 15, 0, 0, 0, 0),
('7150', 16, 0, 0, 0, 0),
('7150', 17, 0, 0, 0, 0),
('7150', 18, 0, 0, 0, 0),
('7150', 19, 0, 0, 0, 0),
('7150', 20, 0, 0, 0, 0),
('7150', 21, 0, 0, 0, 0),
('7150', 22, 0, 0, 0, 0),
('7150', 23, 0, 0, 0, 0),
('7150', 24, 0, 0, 0, 0),
('7150', 25, 0, 0, 0, 0),
('7150', 26, 0, 0, 0, 0),
('7150', 27, 0, 0, 0, 0),
('7150', 28, 0, 0, 0, 0),
('7150', 29, 0, 0, 0, 0),
('7150', 30, 0, 0, 0, 0),
('7150', 31, 0, 0, 0, 0),
('7150', 32, 0, 0, 0, 0),
('7150', 33, 0, 0, 0, 0),
('7150', 34, 0, 0, 0, 0),
('7150', 35, 0, 0, 0, 0),
('7150', 36, 0, 0, 0, 0),
('7150', 37, 0, 0, 0, 0),
('7150', 38, 0, 0, 0, 0),
('7150', 39, 0, 0, 0, 0),
('7150', 40, 0, 0, 0, 0),
('7150', 41, 0, 0, 0, 0),
('7150', 42, 0, 0, 0, 0),
('7150', 43, 0, 0, 0, 0),
('7150', 44, 0, 0, 0, 0),
('7150', 45, 0, 0, 0, 0),
('7150', 46, 0, 0, 0, 0),
('7150', 47, 0, 0, 0, 0),
('7150', 48, 0, 0, 0, 0),
('7150', 49, 0, 0, 0, 0),
('7150', 50, 0, 0, 0, 0),
('7150', 51, 0, 0, 0, 0),
('7150', 52, 0, 0, 0, 0),
('7150', 53, 0, 0, 0, 0),
('7150', 54, 0, 0, 0, 0),
('7150', 55, 0, 0, 0, 0),
('7150', 56, 0, 0, 0, 0),
('7150', 57, 0, 0, 0, 0),
('7150', 58, 0, 0, 0, 0),
('7150', 59, 0, 0, 0, 0),
('7200', -15, 0, 0, 0, 0),
('7200', -14, 0, 0, 0, 0),
('7200', -13, 0, 0, 0, 0),
('7200', -12, 0, 0, 0, 0),
('7200', -11, 0, 0, 0, 0),
('7200', -10, 0, 0, 0, 0),
('7200', -9, 0, 0, 0, 0),
('7200', -8, 0, 0, 0, 0),
('7200', -7, 0, 0, 0, 0),
('7200', -6, 0, 0, 0, 0),
('7200', -5, 0, 0, 0, 0),
('7200', -4, 0, 0, 0, 0),
('7200', -3, 0, 0, 0, 0),
('7200', -2, 0, 0, 0, 0),
('7200', -1, 0, 0, 0, 0),
('7200', 0, 0, 0, 0, 0),
('7200', 1, 0, 0, 0, 0),
('7200', 2, 0, 0, 0, 0),
('7200', 3, 0, 0, 0, 0),
('7200', 4, 0, 0, 0, 0),
('7200', 5, 0, 0, 0, 0),
('7200', 6, 0, 0, 0, 0),
('7200', 7, 0, 0, 0, 0),
('7200', 8, 0, 0, 0, 0),
('7200', 9, 0, 0, 0, 0),
('7200', 10, 0, 0, 0, 0),
('7200', 11, 0, 0, 0, 0),
('7200', 12, 0, 0, 0, 0),
('7200', 13, 0, 0, 0, 0),
('7200', 14, 0, 0, 0, 0),
('7200', 15, 0, 0, 0, 0),
('7200', 16, 0, 0, 0, 0),
('7200', 17, 0, 0, 0, 0),
('7200', 18, 0, 0, 0, 0),
('7200', 19, 0, 0, 0, 0),
('7200', 20, 0, 0, 0, 0),
('7200', 21, 0, 0, 0, 0),
('7200', 22, 0, 0, 0, 0),
('7200', 23, 0, 0, 0, 0),
('7200', 24, 0, 0, 0, 0),
('7200', 25, 0, 0, 0, 0),
('7200', 26, 0, 0, 0, 0),
('7200', 27, 0, 0, 0, 0),
('7200', 28, 0, 0, 0, 0),
('7200', 29, 0, 0, 0, 0),
('7200', 30, 0, 0, 0, 0),
('7200', 31, 0, 0, 0, 0),
('7200', 32, 0, 0, 0, 0),
('7200', 33, 0, 0, 0, 0),
('7200', 34, 0, 0, 0, 0),
('7200', 35, 0, 0, 0, 0),
('7200', 36, 0, 0, 0, 0),
('7200', 37, 0, 0, 0, 0),
('7200', 38, 0, 0, 0, 0),
('7200', 39, 0, 0, 0, 0),
('7200', 40, 0, 0, 0, 0),
('7200', 41, 0, 0, 0, 0),
('7200', 42, 0, 0, 0, 0),
('7200', 43, 0, 0, 0, 0),
('7200', 44, 0, 0, 0, 0),
('7200', 45, 0, 0, 0, 0),
('7200', 46, 0, 0, 0, 0),
('7200', 47, 0, 0, 0, 0),
('7200', 48, 0, 0, 0, 0),
('7200', 49, 0, 0, 0, 0),
('7200', 50, 0, 0, 0, 0),
('7200', 51, 0, 0, 0, 0),
('7200', 52, 0, 0, 0, 0),
('7200', 53, 0, 0, 0, 0),
('7200', 54, 0, 0, 0, 0),
('7200', 55, 0, 0, 0, 0),
('7200', 56, 0, 0, 0, 0),
('7200', 57, 0, 0, 0, 0),
('7200', 58, 0, 0, 0, 0),
('7200', 59, 0, 0, 0, 0),
('7210', -15, 0, 0, 0, 0),
('7210', -14, 0, 0, 0, 0),
('7210', -13, 0, 0, 0, 0),
('7210', -12, 0, 0, 0, 0),
('7210', -11, 0, 0, 0, 0),
('7210', -10, 0, 0, 0, 0),
('7210', -9, 0, 0, 0, 0),
('7210', -8, 0, 0, 0, 0),
('7210', -7, 0, 0, 0, 0),
('7210', -6, 0, 0, 0, 0),
('7210', -5, 0, 0, 0, 0),
('7210', -4, 0, 0, 0, 0),
('7210', -3, 0, 0, 0, 0),
('7210', -2, 0, 0, 0, 0),
('7210', -1, 0, 0, 0, 0),
('7210', 0, 0, 0, 0, 0),
('7210', 1, 0, 0, 0, 0),
('7210', 2, 0, 0, 0, 0),
('7210', 3, 0, 0, 0, 0),
('7210', 4, 0, 0, 0, 0),
('7210', 5, 0, 0, 0, 0),
('7210', 6, 0, 0, 0, 0),
('7210', 7, 0, 0, 0, 0),
('7210', 8, 0, 0, 0, 0),
('7210', 9, 0, 0, 0, 0),
('7210', 10, 0, 0, 0, 0),
('7210', 11, 0, 0, 0, 0),
('7210', 12, 0, 0, 0, 0),
('7210', 13, 0, 0, 0, 0),
('7210', 14, 0, 0, 0, 0),
('7210', 15, 0, 0, 0, 0),
('7210', 16, 0, 0, 0, 0),
('7210', 17, 0, 0, 0, 0),
('7210', 18, 0, 0, 0, 0),
('7210', 19, 0, 0, 0, 0),
('7210', 20, 0, 0, 0, 0),
('7210', 21, 0, 0, 0, 0),
('7210', 22, 0, 0, 0, 0),
('7210', 23, 0, 0, 0, 0),
('7210', 24, 0, 0, 0, 0),
('7210', 25, 0, 0, 0, 0),
('7210', 26, 0, 0, 0, 0),
('7210', 27, 0, 0, 0, 0),
('7210', 28, 0, 0, 0, 0),
('7210', 29, 0, 0, 0, 0),
('7210', 30, 0, 0, 0, 0),
('7210', 31, 0, 0, 0, 0),
('7210', 32, 0, 0, 0, 0),
('7210', 33, 0, 0, 0, 0),
('7210', 34, 0, 0, 0, 0),
('7210', 35, 0, 0, 0, 0),
('7210', 36, 0, 0, 0, 0),
('7210', 37, 0, 0, 0, 0),
('7210', 38, 0, 0, 0, 0),
('7210', 39, 0, 0, 0, 0),
('7210', 40, 0, 0, 0, 0),
('7210', 41, 0, 0, 0, 0),
('7210', 42, 0, 0, 0, 0),
('7210', 43, 0, 0, 0, 0),
('7210', 44, 0, 0, 0, 0),
('7210', 45, 0, 0, 0, 0),
('7210', 46, 0, 0, 0, 0),
('7210', 47, 0, 0, 0, 0),
('7210', 48, 0, 0, 0, 0),
('7210', 49, 0, 0, 0, 0),
('7210', 50, 0, 0, 0, 0),
('7210', 51, 0, 0, 0, 0),
('7210', 52, 0, 0, 0, 0),
('7210', 53, 0, 0, 0, 0),
('7210', 54, 0, 0, 0, 0),
('7210', 55, 0, 0, 0, 0),
('7210', 56, 0, 0, 0, 0),
('7210', 57, 0, 0, 0, 0),
('7210', 58, 0, 0, 0, 0),
('7210', 59, 0, 0, 0, 0),
('7220', -15, 0, 0, 0, 0),
('7220', -14, 0, 0, 0, 0),
('7220', -13, 0, 0, 0, 0),
('7220', -12, 0, 0, 0, 0),
('7220', -11, 0, 0, 0, 0),
('7220', -10, 0, 0, 0, 0),
('7220', -9, 0, 0, 0, 0),
('7220', -8, 0, 0, 0, 0),
('7220', -7, 0, 0, 0, 0),
('7220', -6, 0, 0, 0, 0),
('7220', -5, 0, 0, 0, 0),
('7220', -4, 0, 0, 0, 0),
('7220', -3, 0, 0, 0, 0),
('7220', -2, 0, 0, 0, 0),
('7220', -1, 0, 0, 0, 0),
('7220', 0, 0, 0, 0, 0),
('7220', 1, 0, 0, 0, 0),
('7220', 2, 0, 0, 0, 0),
('7220', 3, 0, 0, 0, 0),
('7220', 4, 0, 0, 0, 0),
('7220', 5, 0, 0, 0, 0),
('7220', 6, 0, 0, 0, 0),
('7220', 7, 0, 0, 0, 0),
('7220', 8, 0, 0, 0, 0),
('7220', 9, 0, 0, 0, 0),
('7220', 10, 0, 0, 0, 0),
('7220', 11, 0, 0, 0, 0),
('7220', 12, 0, 0, 0, 0),
('7220', 13, 0, 0, 0, 0),
('7220', 14, 0, 0, 0, 0),
('7220', 15, 0, 0, 0, 0),
('7220', 16, 0, 0, 0, 0),
('7220', 17, 0, 0, 0, 0),
('7220', 18, 0, 0, 0, 0),
('7220', 19, 0, 0, 0, 0),
('7220', 20, 0, 0, 0, 0),
('7220', 21, 0, 0, 0, 0),
('7220', 22, 0, 0, 0, 0),
('7220', 23, 0, 0, 0, 0),
('7220', 24, 0, 0, 0, 0),
('7220', 25, 0, 0, 0, 0),
('7220', 26, 0, 0, 0, 0),
('7220', 27, 0, 0, 0, 0),
('7220', 28, 0, 0, 0, 0),
('7220', 29, 0, 0, 0, 0),
('7220', 30, 0, 0, 0, 0),
('7220', 31, 0, 0, 0, 0),
('7220', 32, 0, 0, 0, 0),
('7220', 33, 0, 0, 0, 0),
('7220', 34, 0, 0, 0, 0),
('7220', 35, 0, 0, 0, 0),
('7220', 36, 0, 0, 0, 0),
('7220', 37, 0, 0, 0, 0),
('7220', 38, 0, 0, 0, 0),
('7220', 39, 0, 0, 0, 0),
('7220', 40, 0, 0, 0, 0),
('7220', 41, 0, 0, 0, 0),
('7220', 42, 0, 0, 0, 0),
('7220', 43, 0, 0, 0, 0),
('7220', 44, 0, 0, 0, 0),
('7220', 45, 0, 0, 0, 0),
('7220', 46, 0, 0, 0, 0),
('7220', 47, 0, 0, 0, 0),
('7220', 48, 0, 0, 0, 0),
('7220', 49, 0, 0, 0, 0),
('7220', 50, 0, 0, 0, 0),
('7220', 51, 0, 0, 0, 0),
('7220', 52, 0, 0, 0, 0),
('7220', 53, 0, 0, 0, 0),
('7220', 54, 0, 0, 0, 0),
('7220', 55, 0, 0, 0, 0),
('7220', 56, 0, 0, 0, 0),
('7220', 57, 0, 0, 0, 0),
('7220', 58, 0, 0, 0, 0),
('7220', 59, 0, 0, 0, 0),
('7230', -15, 0, 0, 0, 0),
('7230', -14, 0, 0, 0, 0),
('7230', -13, 0, 0, 0, 0),
('7230', -12, 0, 0, 0, 0),
('7230', -11, 0, 0, 0, 0),
('7230', -10, 0, 0, 0, 0),
('7230', -9, 0, 0, 0, 0),
('7230', -8, 0, 0, 0, 0),
('7230', -7, 0, 0, 0, 0),
('7230', -6, 0, 0, 0, 0),
('7230', -5, 0, 0, 0, 0),
('7230', -4, 0, 0, 0, 0),
('7230', -3, 0, 0, 0, 0),
('7230', -2, 0, 0, 0, 0),
('7230', -1, 0, 0, 0, 0),
('7230', 0, 0, 0, 0, 0),
('7230', 1, 0, 0, 0, 0),
('7230', 2, 0, 0, 0, 0),
('7230', 3, 0, 0, 0, 0),
('7230', 4, 0, 0, 0, 0),
('7230', 5, 0, 0, 0, 0),
('7230', 6, 0, 0, 0, 0),
('7230', 7, 0, 0, 0, 0),
('7230', 8, 0, 0, 0, 0),
('7230', 9, 0, 0, 0, 0),
('7230', 10, 0, 0, 0, 0),
('7230', 11, 0, 0, 0, 0),
('7230', 12, 0, 0, 0, 0),
('7230', 13, 0, 0, 0, 0),
('7230', 14, 0, 0, 0, 0),
('7230', 15, 0, 0, 0, 0),
('7230', 16, 0, 0, 0, 0),
('7230', 17, 0, 0, 0, 0),
('7230', 18, 0, 0, 0, 0),
('7230', 19, 0, 0, 0, 0),
('7230', 20, 0, 0, 0, 0),
('7230', 21, 0, 0, 0, 0),
('7230', 22, 0, 0, 0, 0),
('7230', 23, 0, 0, 0, 0),
('7230', 24, 0, 0, 0, 0),
('7230', 25, 0, 0, 0, 0),
('7230', 26, 0, 0, 0, 0),
('7230', 27, 0, 0, 0, 0),
('7230', 28, 0, 0, 0, 0),
('7230', 29, 0, 0, 0, 0),
('7230', 30, 0, 0, 0, 0),
('7230', 31, 0, 0, 0, 0),
('7230', 32, 0, 0, 0, 0),
('7230', 33, 0, 0, 0, 0),
('7230', 34, 0, 0, 0, 0),
('7230', 35, 0, 0, 0, 0),
('7230', 36, 0, 0, 0, 0),
('7230', 37, 0, 0, 0, 0),
('7230', 38, 0, 0, 0, 0),
('7230', 39, 0, 0, 0, 0),
('7230', 40, 0, 0, 0, 0),
('7230', 41, 0, 0, 0, 0),
('7230', 42, 0, 0, 0, 0),
('7230', 43, 0, 0, 0, 0),
('7230', 44, 0, 0, 0, 0),
('7230', 45, 0, 0, 0, 0),
('7230', 46, 0, 0, 0, 0),
('7230', 47, 0, 0, 0, 0),
('7230', 48, 0, 0, 0, 0),
('7230', 49, 0, 0, 0, 0),
('7230', 50, 0, 0, 0, 0),
('7230', 51, 0, 0, 0, 0),
('7230', 52, 0, 0, 0, 0),
('7230', 53, 0, 0, 0, 0),
('7230', 54, 0, 0, 0, 0),
('7230', 55, 0, 0, 0, 0),
('7230', 56, 0, 0, 0, 0),
('7230', 57, 0, 0, 0, 0),
('7230', 58, 0, 0, 0, 0),
('7230', 59, 0, 0, 0, 0),
('7240', -15, 0, 0, 0, 0),
('7240', -14, 0, 0, 0, 0),
('7240', -13, 0, 0, 0, 0),
('7240', -12, 0, 0, 0, 0),
('7240', -11, 0, 0, 0, 0),
('7240', -10, 0, 0, 0, 0),
('7240', -9, 0, 0, 0, 0),
('7240', -8, 0, 0, 0, 0),
('7240', -7, 0, 0, 0, 0),
('7240', -6, 0, 0, 0, 0),
('7240', -5, 0, 0, 0, 0),
('7240', -4, 0, 0, 0, 0),
('7240', -3, 0, 0, 0, 0),
('7240', -2, 0, 0, 0, 0),
('7240', -1, 0, 0, 0, 0),
('7240', 0, 0, 0, 0, 0),
('7240', 1, 0, 0, 0, 0),
('7240', 2, 0, 0, 0, 0),
('7240', 3, 0, 0, 0, 0),
('7240', 4, 0, 0, 0, 0),
('7240', 5, 0, 0, 0, 0),
('7240', 6, 0, 0, 0, 0),
('7240', 7, 0, 0, 0, 0),
('7240', 8, 0, 0, 0, 0),
('7240', 9, 0, 0, 0, 0),
('7240', 10, 0, 0, 0, 0),
('7240', 11, 0, 0, 0, 0),
('7240', 12, 0, 0, 0, 0),
('7240', 13, 0, 0, 0, 0),
('7240', 14, 0, 0, 0, 0),
('7240', 15, 0, 0, 0, 0),
('7240', 16, 0, 0, 0, 0),
('7240', 17, 0, 0, 0, 0),
('7240', 18, 0, 0, 0, 0),
('7240', 19, 0, 0, 0, 0),
('7240', 20, 0, 0, 0, 0),
('7240', 21, 0, 0, 0, 0),
('7240', 22, 0, 0, 0, 0),
('7240', 23, 0, 0, 0, 0),
('7240', 24, 0, 0, 0, 0),
('7240', 25, 0, 0, 0, 0),
('7240', 26, 0, 0, 0, 0),
('7240', 27, 0, 0, 0, 0),
('7240', 28, 0, 0, 0, 0),
('7240', 29, 0, 0, 0, 0),
('7240', 30, 0, 0, 0, 0),
('7240', 31, 0, 0, 0, 0),
('7240', 32, 0, 0, 0, 0),
('7240', 33, 0, 0, 0, 0),
('7240', 34, 0, 0, 0, 0),
('7240', 35, 0, 0, 0, 0),
('7240', 36, 0, 0, 0, 0),
('7240', 37, 0, 0, 0, 0),
('7240', 38, 0, 0, 0, 0),
('7240', 39, 0, 0, 0, 0),
('7240', 40, 0, 0, 0, 0),
('7240', 41, 0, 0, 0, 0),
('7240', 42, 0, 0, 0, 0),
('7240', 43, 0, 0, 0, 0),
('7240', 44, 0, 0, 0, 0),
('7240', 45, 0, 0, 0, 0),
('7240', 46, 0, 0, 0, 0),
('7240', 47, 0, 0, 0, 0),
('7240', 48, 0, 0, 0, 0),
('7240', 49, 0, 0, 0, 0),
('7240', 50, 0, 0, 0, 0),
('7240', 51, 0, 0, 0, 0),
('7240', 52, 0, 0, 0, 0),
('7240', 53, 0, 0, 0, 0),
('7240', 54, 0, 0, 0, 0),
('7240', 55, 0, 0, 0, 0),
('7240', 56, 0, 0, 0, 0),
('7240', 57, 0, 0, 0, 0),
('7240', 58, 0, 0, 0, 0),
('7240', 59, 0, 0, 0, 0),
('7260', -15, 0, 0, 0, 0),
('7260', -14, 0, 0, 0, 0),
('7260', -13, 0, 0, 0, 0),
('7260', -12, 0, 0, 0, 0),
('7260', -11, 0, 0, 0, 0),
('7260', -10, 0, 0, 0, 0),
('7260', -9, 0, 0, 0, 0),
('7260', -8, 0, 0, 0, 0),
('7260', -7, 0, 0, 0, 0),
('7260', -6, 0, 0, 0, 0),
('7260', -5, 0, 0, 0, 0),
('7260', -4, 0, 0, 0, 0),
('7260', -3, 0, 0, 0, 0),
('7260', -2, 0, 0, 0, 0),
('7260', -1, 0, 0, 0, 0),
('7260', 0, 0, 0, 0, 0),
('7260', 1, 0, 0, 0, 0),
('7260', 2, 0, 0, 0, 0),
('7260', 3, 0, 0, 0, 0),
('7260', 4, 0, 0, 0, 0),
('7260', 5, 0, 0, 0, 0),
('7260', 6, 0, 0, 0, 0),
('7260', 7, 0, 0, 0, 0),
('7260', 8, 0, 0, 0, 0),
('7260', 9, 0, 0, 0, 0),
('7260', 10, 0, 0, 0, 0),
('7260', 11, 0, 0, 0, 0),
('7260', 12, 0, 0, 0, 0),
('7260', 13, 0, 0, 0, 0),
('7260', 14, 0, 0, 0, 0),
('7260', 15, 0, 0, 0, 0),
('7260', 16, 0, 0, 0, 0),
('7260', 17, 0, 0, 0, 0),
('7260', 18, 0, 0, 0, 0),
('7260', 19, 0, 0, 0, 0),
('7260', 20, 0, 0, 0, 0),
('7260', 21, 0, 0, 0, 0),
('7260', 22, 0, 0, 0, 0),
('7260', 23, 0, 0, 0, 0),
('7260', 24, 0, 0, 0, 0),
('7260', 25, 0, 0, 0, 0),
('7260', 26, 0, 0, 0, 0),
('7260', 27, 0, 0, 0, 0),
('7260', 28, 0, 0, 0, 0),
('7260', 29, 0, 0, 0, 0),
('7260', 30, 0, 0, 0, 0),
('7260', 31, 0, 0, 0, 0),
('7260', 32, 0, 0, 0, 0),
('7260', 33, 0, 0, 0, 0),
('7260', 34, 0, 0, 0, 0),
('7260', 35, 0, 0, 0, 0),
('7260', 36, 0, 0, 0, 0),
('7260', 37, 0, 0, 0, 0),
('7260', 38, 0, 0, 0, 0),
('7260', 39, 0, 0, 0, 0),
('7260', 40, 0, 0, 0, 0),
('7260', 41, 0, 0, 0, 0),
('7260', 42, 0, 0, 0, 0),
('7260', 43, 0, 0, 0, 0),
('7260', 44, 0, 0, 0, 0),
('7260', 45, 0, 0, 0, 0),
('7260', 46, 0, 0, 0, 0),
('7260', 47, 0, 0, 0, 0),
('7260', 48, 0, 0, 0, 0),
('7260', 49, 0, 0, 0, 0),
('7260', 50, 0, 0, 0, 0),
('7260', 51, 0, 0, 0, 0),
('7260', 52, 0, 0, 0, 0),
('7260', 53, 0, 0, 0, 0),
('7260', 54, 0, 0, 0, 0),
('7260', 55, 0, 0, 0, 0),
('7260', 56, 0, 0, 0, 0),
('7260', 57, 0, 0, 0, 0),
('7260', 58, 0, 0, 0, 0),
('7260', 59, 0, 0, 0, 0),
('7280', -15, 0, 0, 0, 0),
('7280', -14, 0, 0, 0, 0),
('7280', -13, 0, 0, 0, 0),
('7280', -12, 0, 0, 0, 0),
('7280', -11, 0, 0, 0, 0),
('7280', -10, 0, 0, 0, 0),
('7280', -9, 0, 0, 0, 0),
('7280', -8, 0, 0, 0, 0),
('7280', -7, 0, 0, 0, 0),
('7280', -6, 0, 0, 0, 0),
('7280', -5, 0, 0, 0, 0),
('7280', -4, 0, 0, 0, 0),
('7280', -3, 0, 0, 0, 0),
('7280', -2, 0, 0, 0, 0),
('7280', -1, 0, 0, 0, 0),
('7280', 0, 0, 0, 0, 0),
('7280', 1, 0, 0, 0, 0),
('7280', 2, 0, 0, 0, 0),
('7280', 3, 0, 0, 0, 0),
('7280', 4, 0, 0, 0, 0),
('7280', 5, 0, 0, 0, 0),
('7280', 6, 0, 0, 0, 0),
('7280', 7, 0, 0, 0, 0),
('7280', 8, 0, 0, 0, 0),
('7280', 9, 0, 0, 0, 0),
('7280', 10, 0, 0, 0, 0),
('7280', 11, 0, 0, 0, 0),
('7280', 12, 0, 0, 0, 0),
('7280', 13, 0, 0, 0, 0),
('7280', 14, 0, 0, 0, 0),
('7280', 15, 0, 0, 0, 0),
('7280', 16, 0, 0, 0, 0),
('7280', 17, 0, 0, 0, 0),
('7280', 18, 0, 0, 0, 0),
('7280', 19, 0, 0, 0, 0),
('7280', 20, 0, 0, 0, 0),
('7280', 21, 0, 0, 0, 0),
('7280', 22, 0, 0, 0, 0),
('7280', 23, 0, 0, 0, 0),
('7280', 24, 0, 0, 0, 0),
('7280', 25, 0, 0, 0, 0),
('7280', 26, 0, 0, 0, 0),
('7280', 27, 0, 0, 0, 0),
('7280', 28, 0, 0, 0, 0),
('7280', 29, 0, 0, 0, 0),
('7280', 30, 0, 0, 0, 0),
('7280', 31, 0, 0, 0, 0),
('7280', 32, 0, 0, 0, 0),
('7280', 33, 0, 0, 0, 0),
('7280', 34, 0, 0, 0, 0),
('7280', 35, 0, 0, 0, 0),
('7280', 36, 0, 0, 0, 0),
('7280', 37, 0, 0, 0, 0),
('7280', 38, 0, 0, 0, 0),
('7280', 39, 0, 0, 0, 0),
('7280', 40, 0, 0, 0, 0),
('7280', 41, 0, 0, 0, 0),
('7280', 42, 0, 0, 0, 0),
('7280', 43, 0, 0, 0, 0),
('7280', 44, 0, 0, 0, 0),
('7280', 45, 0, 0, 0, 0),
('7280', 46, 0, 0, 0, 0),
('7280', 47, 0, 0, 0, 0),
('7280', 48, 0, 0, 0, 0),
('7280', 49, 0, 0, 0, 0),
('7280', 50, 0, 0, 0, 0),
('7280', 51, 0, 0, 0, 0),
('7280', 52, 0, 0, 0, 0),
('7280', 53, 0, 0, 0, 0),
('7280', 54, 0, 0, 0, 0),
('7280', 55, 0, 0, 0, 0),
('7280', 56, 0, 0, 0, 0),
('7280', 57, 0, 0, 0, 0),
('7280', 58, 0, 0, 0, 0),
('7280', 59, 0, 0, 0, 0),
('7300', -15, 0, 0, 0, 0),
('7300', -14, 0, 0, 0, 0),
('7300', -13, 0, 0, 0, 0),
('7300', -12, 0, 0, 0, 0),
('7300', -11, 0, 0, 0, 0),
('7300', -10, 0, 0, 0, 0),
('7300', -9, 0, 0, 0, 0),
('7300', -8, 0, 0, 0, 0),
('7300', -7, 0, 0, 0, 0),
('7300', -6, 0, 0, 0, 0),
('7300', -5, 0, 0, 0, 0),
('7300', -4, 0, 0, 0, 0),
('7300', -3, 0, 0, 0, 0),
('7300', -2, 0, 0, 0, 0),
('7300', -1, 0, 0, 0, 0),
('7300', 0, 0, 0, 0, 0),
('7300', 1, 0, 0, 0, 0),
('7300', 2, 0, 0, 0, 0),
('7300', 3, 0, 0, 0, 0),
('7300', 4, 0, 0, 0, 0),
('7300', 5, 0, 0, 0, 0),
('7300', 6, 0, 0, 0, 0),
('7300', 7, 0, 0, 0, 0),
('7300', 8, 0, 0, 0, 0),
('7300', 9, 0, 0, 0, 0),
('7300', 10, 0, 0, 0, 0),
('7300', 11, 0, 0, 0, 0),
('7300', 12, 0, 0, 0, 0),
('7300', 13, 0, 0, 0, 0),
('7300', 14, 0, 0, 0, 0),
('7300', 15, 0, 0, 0, 0),
('7300', 16, 0, 0, 0, 0),
('7300', 17, 0, 0, 0, 0),
('7300', 18, 0, 0, 0, 0),
('7300', 19, 0, 0, 0, 0),
('7300', 20, 0, 0, 0, 0),
('7300', 21, 0, 0, 0, 0),
('7300', 22, 0, 0, 0, 0),
('7300', 23, 0, 0, 0, 0),
('7300', 24, 0, 0, 0, 0),
('7300', 25, 0, 0, 0, 0),
('7300', 26, 0, 0, 0, 0),
('7300', 27, 0, 0, 0, 0),
('7300', 28, 0, 0, 0, 0),
('7300', 29, 0, 0, 0, 0),
('7300', 30, 0, 0, 0, 0),
('7300', 31, 0, 0, 0, 0),
('7300', 32, 0, 0, 0, 0),
('7300', 33, 0, 0, 0, 0),
('7300', 34, 0, 0, 0, 0),
('7300', 35, 0, 0, 0, 0),
('7300', 36, 0, 0, 0, 0),
('7300', 37, 0, 0, 0, 0),
('7300', 38, 0, 0, 0, 0),
('7300', 39, 0, 0, 0, 0),
('7300', 40, 0, 0, 0, 0),
('7300', 41, 0, 0, 0, 0),
('7300', 42, 0, 0, 0, 0),
('7300', 43, 0, 0, 0, 0),
('7300', 44, 0, 0, 0, 0),
('7300', 45, 0, 0, 0, 0),
('7300', 46, 0, 0, 0, 0),
('7300', 47, 0, 0, 0, 0),
('7300', 48, 0, 0, 0, 0),
('7300', 49, 0, 0, 0, 0),
('7300', 50, 0, 0, 0, 0),
('7300', 51, 0, 0, 0, 0),
('7300', 52, 0, 0, 0, 0),
('7300', 53, 0, 0, 0, 0),
('7300', 54, 0, 0, 0, 0),
('7300', 55, 0, 0, 0, 0),
('7300', 56, 0, 0, 0, 0),
('7300', 57, 0, 0, 0, 0),
('7300', 58, 0, 0, 0, 0),
('7300', 59, 0, 0, 0, 0),
('7350', -15, 0, 0, 0, 0),
('7350', -14, 0, 0, 0, 0),
('7350', -13, 0, 0, 0, 0),
('7350', -12, 0, 0, 0, 0),
('7350', -11, 0, 0, 0, 0),
('7350', -10, 0, 0, 0, 0),
('7350', -9, 0, 0, 0, 0),
('7350', -8, 0, 0, 0, 0),
('7350', -7, 0, 0, 0, 0),
('7350', -6, 0, 0, 0, 0),
('7350', -5, 0, 0, 0, 0),
('7350', -4, 0, 0, 0, 0),
('7350', -3, 0, 0, 0, 0),
('7350', -2, 0, 0, 0, 0),
('7350', -1, 0, 0, 0, 0),
('7350', 0, 0, 0, 0, 0),
('7350', 1, 0, 0, 0, 0),
('7350', 2, 0, 0, 0, 0),
('7350', 3, 0, 0, 0, 0),
('7350', 4, 0, 0, 0, 0),
('7350', 5, 0, 0, 0, 0),
('7350', 6, 0, 0, 0, 0),
('7350', 7, 0, 0, 0, 0),
('7350', 8, 0, 0, 0, 0),
('7350', 9, 0, 0, 0, 0),
('7350', 10, 0, 0, 0, 0),
('7350', 11, 0, 0, 0, 0),
('7350', 12, 0, 0, 0, 0),
('7350', 13, 0, 0, 0, 0),
('7350', 14, 0, 0, 0, 0),
('7350', 15, 0, 0, 0, 0),
('7350', 16, 0, 0, 0, 0),
('7350', 17, 0, 0, 0, 0),
('7350', 18, 0, 0, 0, 0),
('7350', 19, 0, 0, 0, 0),
('7350', 20, 0, 0, 0, 0),
('7350', 21, 0, 0, 0, 0),
('7350', 22, 0, 0, 0, 0),
('7350', 23, 0, 0, 0, 0),
('7350', 24, 0, 0, 0, 0),
('7350', 25, 0, 0, 0, 0),
('7350', 26, 0, 0, 0, 0),
('7350', 27, 0, 0, 0, 0),
('7350', 28, 0, 0, 0, 0),
('7350', 29, 0, 0, 0, 0),
('7350', 30, 0, 0, 0, 0),
('7350', 31, 0, 0, 0, 0),
('7350', 32, 0, 0, 0, 0),
('7350', 33, 0, 0, 0, 0),
('7350', 34, 0, 0, 0, 0),
('7350', 35, 0, 0, 0, 0),
('7350', 36, 0, 0, 0, 0),
('7350', 37, 0, 0, 0, 0),
('7350', 38, 0, 0, 0, 0),
('7350', 39, 0, 0, 0, 0),
('7350', 40, 0, 0, 0, 0),
('7350', 41, 0, 0, 0, 0),
('7350', 42, 0, 0, 0, 0),
('7350', 43, 0, 0, 0, 0),
('7350', 44, 0, 0, 0, 0),
('7350', 45, 0, 0, 0, 0),
('7350', 46, 0, 0, 0, 0),
('7350', 47, 0, 0, 0, 0),
('7350', 48, 0, 0, 0, 0),
('7350', 49, 0, 0, 0, 0),
('7350', 50, 0, 0, 0, 0),
('7350', 51, 0, 0, 0, 0),
('7350', 52, 0, 0, 0, 0),
('7350', 53, 0, 0, 0, 0),
('7350', 54, 0, 0, 0, 0),
('7350', 55, 0, 0, 0, 0),
('7350', 56, 0, 0, 0, 0),
('7350', 57, 0, 0, 0, 0),
('7350', 58, 0, 0, 0, 0),
('7350', 59, 0, 0, 0, 0),
('7390', -15, 0, 0, 0, 0),
('7390', -14, 0, 0, 0, 0),
('7390', -13, 0, 0, 0, 0),
('7390', -12, 0, 0, 0, 0),
('7390', -11, 0, 0, 0, 0),
('7390', -10, 0, 0, 0, 0),
('7390', -9, 0, 0, 0, 0),
('7390', -8, 0, 0, 0, 0),
('7390', -7, 0, 0, 0, 0),
('7390', -6, 0, 0, 0, 0),
('7390', -5, 0, 0, 0, 0),
('7390', -4, 0, 0, 0, 0),
('7390', -3, 0, 0, 0, 0),
('7390', -2, 0, 0, 0, 0),
('7390', -1, 0, 0, 0, 0),
('7390', 0, 0, 0, 0, 0),
('7390', 1, 0, 0, 0, 0),
('7390', 2, 0, 0, 0, 0),
('7390', 3, 0, 0, 0, 0),
('7390', 4, 0, 0, 0, 0),
('7390', 5, 0, 0, 0, 0),
('7390', 6, 0, 0, 0, 0),
('7390', 7, 0, 0, 0, 0),
('7390', 8, 0, 0, 0, 0),
('7390', 9, 0, 0, 0, 0),
('7390', 10, 0, 0, 0, 0),
('7390', 11, 0, 0, 0, 0),
('7390', 12, 0, 0, 0, 0),
('7390', 13, 0, 0, 0, 0),
('7390', 14, 0, 0, 0, 0),
('7390', 15, 0, 0, 0, 0),
('7390', 16, 0, 0, 0, 0),
('7390', 17, 0, 0, 0, 0),
('7390', 18, 0, 0, 0, 0),
('7390', 19, 0, 0, 0, 0),
('7390', 20, 0, 0, 0, 0),
('7390', 21, 0, 0, 0, 0),
('7390', 22, 0, 0, 0, 0),
('7390', 23, 0, 0, 0, 0),
('7390', 24, 0, 0, 0, 0),
('7390', 25, 0, 0, 0, 0),
('7390', 26, 0, 0, 0, 0),
('7390', 27, 0, 0, 0, 0),
('7390', 28, 0, 0, 0, 0),
('7390', 29, 0, 0, 0, 0),
('7390', 30, 0, 0, 0, 0),
('7390', 31, 0, 0, 0, 0),
('7390', 32, 0, 0, 0, 0),
('7390', 33, 0, 0, 0, 0),
('7390', 34, 0, 0, 0, 0),
('7390', 35, 0, 0, 0, 0),
('7390', 36, 0, 0, 0, 0),
('7390', 37, 0, 0, 0, 0),
('7390', 38, 0, 0, 0, 0),
('7390', 39, 0, 0, 0, 0),
('7390', 40, 0, 0, 0, 0),
('7390', 41, 0, 0, 0, 0),
('7390', 42, 0, 0, 0, 0),
('7390', 43, 0, 0, 0, 0),
('7390', 44, 0, 0, 0, 0),
('7390', 45, 0, 0, 0, 0),
('7390', 46, 0, 0, 0, 0),
('7390', 47, 0, 0, 0, 0),
('7390', 48, 0, 0, 0, 0),
('7390', 49, 0, 0, 0, 0),
('7390', 50, 0, 0, 0, 0),
('7390', 51, 0, 0, 0, 0),
('7390', 52, 0, 0, 0, 0),
('7390', 53, 0, 0, 0, 0),
('7390', 54, 0, 0, 0, 0),
('7390', 55, 0, 0, 0, 0),
('7390', 56, 0, 0, 0, 0),
('7390', 57, 0, 0, 0, 0),
('7390', 58, 0, 0, 0, 0),
('7390', 59, 0, 0, 0, 0),
('7400', -15, 0, 0, 0, 0),
('7400', -14, 0, 0, 0, 0),
('7400', -13, 0, 0, 0, 0),
('7400', -12, 0, 0, 0, 0),
('7400', -11, 0, 0, 0, 0),
('7400', -10, 0, 0, 0, 0),
('7400', -9, 0, 0, 0, 0),
('7400', -8, 0, 0, 0, 0),
('7400', -7, 0, 0, 0, 0),
('7400', -6, 0, 0, 0, 0),
('7400', -5, 0, 0, 0, 0),
('7400', -4, 0, 0, 0, 0),
('7400', -3, 0, 0, 0, 0),
('7400', -2, 0, 0, 0, 0),
('7400', -1, 0, 0, 0, 0),
('7400', 0, 0, 0, 0, 0),
('7400', 1, 0, 0, 0, 0),
('7400', 2, 0, 0, 0, 0),
('7400', 3, 0, 0, 0, 0),
('7400', 4, 0, 0, 0, 0),
('7400', 5, 0, 0, 0, 0),
('7400', 6, 0, 0, 0, 0),
('7400', 7, 0, 0, 0, 0),
('7400', 8, 0, 0, 0, 0),
('7400', 9, 0, 0, 0, 0),
('7400', 10, 0, 0, 0, 0),
('7400', 11, 0, 0, 0, 0),
('7400', 12, 0, 0, 0, 0),
('7400', 13, 0, 0, 0, 0),
('7400', 14, 0, 0, 0, 0),
('7400', 15, 0, 0, 0, 0),
('7400', 16, 0, 0, 0, 0),
('7400', 17, 0, 0, 0, 0),
('7400', 18, 0, 0, 0, 0),
('7400', 19, 0, 0, 0, 0),
('7400', 20, 0, 0, 0, 0),
('7400', 21, 0, 0, 0, 0),
('7400', 22, 0, 0, 0, 0),
('7400', 23, 0, 0, 0, 0),
('7400', 24, 0, 0, 0, 0),
('7400', 25, 0, 0, 0, 0),
('7400', 26, 0, 0, 0, 0),
('7400', 27, 0, 0, 0, 0),
('7400', 28, 0, 0, 0, 0),
('7400', 29, 0, 0, 0, 0),
('7400', 30, 0, 0, 0, 0),
('7400', 31, 0, 0, 0, 0),
('7400', 32, 0, 0, 0, 0),
('7400', 33, 0, 0, 0, 0),
('7400', 34, 0, 0, 0, 0),
('7400', 35, 0, 0, 0, 0),
('7400', 36, 0, 0, 0, 0),
('7400', 37, 0, 0, 0, 0),
('7400', 38, 0, 0, 0, 0),
('7400', 39, 0, 0, 0, 0),
('7400', 40, 0, 0, 0, 0),
('7400', 41, 0, 0, 0, 0),
('7400', 42, 0, 0, 0, 0),
('7400', 43, 0, 0, 0, 0),
('7400', 44, 0, 0, 0, 0),
('7400', 45, 0, 0, 0, 0),
('7400', 46, 0, 0, 0, 0),
('7400', 47, 0, 0, 0, 0),
('7400', 48, 0, 0, 0, 0),
('7400', 49, 0, 0, 0, 0),
('7400', 50, 0, 0, 0, 0),
('7400', 51, 0, 0, 0, 0),
('7400', 52, 0, 0, 0, 0),
('7400', 53, 0, 0, 0, 0),
('7400', 54, 0, 0, 0, 0),
('7400', 55, 0, 0, 0, 0),
('7400', 56, 0, 0, 0, 0),
('7400', 57, 0, 0, 0, 0),
('7400', 58, 0, 0, 0, 0),
('7400', 59, 0, 0, 0, 0),
('7450', -15, 0, 0, 0, 0),
('7450', -14, 0, 0, 0, 0),
('7450', -13, 0, 0, 0, 0),
('7450', -12, 0, 0, 0, 0),
('7450', -11, 0, 0, 0, 0),
('7450', -10, 0, 0, 0, 0),
('7450', -9, 0, 0, 0, 0),
('7450', -8, 0, 0, 0, 0),
('7450', -7, 0, 0, 0, 0),
('7450', -6, 0, 0, 0, 0),
('7450', -5, 0, 0, 0, 0),
('7450', -4, 0, 0, 0, 0),
('7450', -3, 0, 0, 0, 0),
('7450', -2, 0, 0, 0, 0),
('7450', -1, 0, 0, 0, 0),
('7450', 0, 0, 0, 0, 0),
('7450', 1, 0, 0, 0, 0),
('7450', 2, 0, 0, 0, 0),
('7450', 3, 0, 0, 0, 0),
('7450', 4, 0, 0, 0, 0),
('7450', 5, 0, 0, 0, 0),
('7450', 6, 0, 0, 0, 0),
('7450', 7, 0, 0, 0, 0),
('7450', 8, 0, 0, 0, 0),
('7450', 9, 0, 0, 0, 0),
('7450', 10, 0, 0, 0, 0),
('7450', 11, 0, 0, 0, 0),
('7450', 12, 0, 0, 0, 0),
('7450', 13, 0, 0, 0, 0),
('7450', 14, 0, 0, 0, 0),
('7450', 15, 0, 0, 0, 0),
('7450', 16, 0, 0, 0, 0),
('7450', 17, 0, 0, 0, 0),
('7450', 18, 0, 0, 0, 0),
('7450', 19, 0, 0, 0, 0),
('7450', 20, 0, 0, 0, 0),
('7450', 21, 0, 0, 0, 0),
('7450', 22, 0, 0, 0, 0),
('7450', 23, 0, 0, 0, 0),
('7450', 24, 0, 0, 0, 0),
('7450', 25, 0, 0, 0, 0),
('7450', 26, 0, 0, 0, 0),
('7450', 27, 0, 0, 0, 0),
('7450', 28, 0, 0, 0, 0),
('7450', 29, 0, 0, 0, 0),
('7450', 30, 0, 0, 0, 0),
('7450', 31, 0, 0, 0, 0),
('7450', 32, 0, 0, 0, 0),
('7450', 33, 0, 0, 0, 0),
('7450', 34, 0, 0, 0, 0),
('7450', 35, 0, 0, 0, 0),
('7450', 36, 0, 0, 0, 0),
('7450', 37, 0, 0, 0, 0),
('7450', 38, 0, 0, 0, 0),
('7450', 39, 0, 0, 0, 0),
('7450', 40, 0, 0, 0, 0),
('7450', 41, 0, 0, 0, 0),
('7450', 42, 0, 0, 0, 0),
('7450', 43, 0, 0, 0, 0),
('7450', 44, 0, 0, 0, 0),
('7450', 45, 0, 0, 0, 0),
('7450', 46, 0, 0, 0, 0),
('7450', 47, 0, 0, 0, 0),
('7450', 48, 0, 0, 0, 0),
('7450', 49, 0, 0, 0, 0),
('7450', 50, 0, 0, 0, 0),
('7450', 51, 0, 0, 0, 0),
('7450', 52, 0, 0, 0, 0),
('7450', 53, 0, 0, 0, 0),
('7450', 54, 0, 0, 0, 0),
('7450', 55, 0, 0, 0, 0),
('7450', 56, 0, 0, 0, 0),
('7450', 57, 0, 0, 0, 0),
('7450', 58, 0, 0, 0, 0),
('7450', 59, 0, 0, 0, 0),
('7500', -15, 0, 0, 0, 0),
('7500', -14, 0, 0, 0, 0),
('7500', -13, 0, 0, 0, 0),
('7500', -12, 0, 0, 0, 0),
('7500', -11, 0, 0, 0, 0),
('7500', -10, 0, 0, 0, 0),
('7500', -9, 0, 0, 0, 0),
('7500', -8, 0, 0, 0, 0),
('7500', -7, 0, 0, 0, 0),
('7500', -6, 0, 0, 0, 0),
('7500', -5, 0, 0, 0, 0),
('7500', -4, 0, 0, 0, 0),
('7500', -3, 0, 0, 0, 0),
('7500', -2, 0, 0, 0, 0),
('7500', -1, 0, 0, 0, 0),
('7500', 0, 0, 0, 0, 0),
('7500', 1, 0, 0, 0, 0),
('7500', 2, 0, 0, 0, 0),
('7500', 3, 0, 0, 0, 0),
('7500', 4, 0, 0, 0, 0),
('7500', 5, 0, 0, 0, 0),
('7500', 6, 0, 0, 0, 0),
('7500', 7, 0, 0, 0, 0),
('7500', 8, 0, 0, 0, 0),
('7500', 9, 0, 0, 0, 0),
('7500', 10, 0, 0, 0, 0),
('7500', 11, 0, 0, 0, 0),
('7500', 12, 0, 0, 0, 0),
('7500', 13, 0, 0, 0, 0),
('7500', 14, 0, 0, 0, 0),
('7500', 15, 0, 0, 0, 0),
('7500', 16, 0, 0, 0, 0),
('7500', 17, 0, 0, 0, 0),
('7500', 18, 0, 0, 0, 0),
('7500', 19, 0, 0, 0, 0),
('7500', 20, 0, 0, 0, 0),
('7500', 21, 0, 0, 0, 0),
('7500', 22, 0, 0, 0, 0),
('7500', 23, 0, 0, 0, 0),
('7500', 24, 0, 0, 0, 0),
('7500', 25, 0, 0, 0, 0),
('7500', 26, 0, 0, 0, 0),
('7500', 27, 0, 0, 0, 0),
('7500', 28, 0, 0, 0, 0),
('7500', 29, 0, 0, 0, 0),
('7500', 30, 0, 0, 0, 0),
('7500', 31, 0, 0, 0, 0),
('7500', 32, 0, 0, 0, 0),
('7500', 33, 0, 0, 0, 0),
('7500', 34, 0, 0, 0, 0),
('7500', 35, 0, 0, 0, 0),
('7500', 36, 0, 0, 0, 0),
('7500', 37, 0, 0, 0, 0),
('7500', 38, 0, 0, 0, 0),
('7500', 39, 0, 0, 0, 0),
('7500', 40, 0, 0, 0, 0),
('7500', 41, 0, 0, 0, 0),
('7500', 42, 0, 0, 0, 0),
('7500', 43, 0, 0, 0, 0),
('7500', 44, 0, 0, 0, 0),
('7500', 45, 0, 0, 0, 0),
('7500', 46, 0, 0, 0, 0),
('7500', 47, 0, 0, 0, 0),
('7500', 48, 0, 0, 0, 0),
('7500', 49, 0, 0, 0, 0),
('7500', 50, 0, 0, 0, 0),
('7500', 51, 0, 0, 0, 0),
('7500', 52, 0, 0, 0, 0),
('7500', 53, 0, 0, 0, 0),
('7500', 54, 0, 0, 0, 0),
('7500', 55, 0, 0, 0, 0),
('7500', 56, 0, 0, 0, 0),
('7500', 57, 0, 0, 0, 0),
('7500', 58, 0, 0, 0, 0),
('7500', 59, 0, 0, 0, 0),
('7550', -15, 0, 0, 0, 0),
('7550', -14, 0, 0, 0, 0),
('7550', -13, 0, 0, 0, 0),
('7550', -12, 0, 0, 0, 0),
('7550', -11, 0, 0, 0, 0),
('7550', -10, 0, 0, 0, 0),
('7550', -9, 0, 0, 0, 0),
('7550', -8, 0, 0, 0, 0),
('7550', -7, 0, 0, 0, 0),
('7550', -6, 0, 0, 0, 0),
('7550', -5, 0, 0, 0, 0),
('7550', -4, 0, 0, 0, 0),
('7550', -3, 0, 0, 0, 0),
('7550', -2, 0, 0, 0, 0),
('7550', -1, 0, 0, 0, 0),
('7550', 0, 0, 0, 0, 0),
('7550', 1, 0, 0, 0, 0),
('7550', 2, 0, 0, 0, 0),
('7550', 3, 0, 0, 0, 0),
('7550', 4, 0, 0, 0, 0),
('7550', 5, 0, 0, 0, 0),
('7550', 6, 0, 0, 0, 0),
('7550', 7, 0, 0, 0, 0),
('7550', 8, 0, 0, 0, 0),
('7550', 9, 0, 0, 0, 0),
('7550', 10, 0, 0, 0, 0),
('7550', 11, 0, 0, 0, 0),
('7550', 12, 0, 0, 0, 0),
('7550', 13, 0, 0, 0, 0),
('7550', 14, 0, 0, 0, 0),
('7550', 15, 0, 0, 0, 0),
('7550', 16, 0, 0, 0, 0),
('7550', 17, 0, 0, 0, 0),
('7550', 18, 0, 0, 0, 0),
('7550', 19, 0, 0, 0, 0),
('7550', 20, 0, 0, 0, 0),
('7550', 21, 0, 0, 0, 0),
('7550', 22, 0, 0, 0, 0),
('7550', 23, 0, 0, 0, 0),
('7550', 24, 0, 0, 0, 0),
('7550', 25, 0, 0, 0, 0),
('7550', 26, 0, 0, 0, 0),
('7550', 27, 0, 0, 0, 0),
('7550', 28, 0, 0, 0, 0),
('7550', 29, 0, 0, 0, 0),
('7550', 30, 0, 0, 0, 0),
('7550', 31, 0, 0, 0, 0),
('7550', 32, 0, 0, 0, 0),
('7550', 33, 0, 0, 0, 0),
('7550', 34, 0, 0, 0, 0),
('7550', 35, 0, 0, 0, 0),
('7550', 36, 0, 0, 0, 0),
('7550', 37, 0, 0, 0, 0),
('7550', 38, 0, 0, 0, 0),
('7550', 39, 0, 0, 0, 0),
('7550', 40, 0, 0, 0, 0),
('7550', 41, 0, 0, 0, 0),
('7550', 42, 0, 0, 0, 0),
('7550', 43, 0, 0, 0, 0),
('7550', 44, 0, 0, 0, 0),
('7550', 45, 0, 0, 0, 0),
('7550', 46, 0, 0, 0, 0),
('7550', 47, 0, 0, 0, 0),
('7550', 48, 0, 0, 0, 0),
('7550', 49, 0, 0, 0, 0),
('7550', 50, 0, 0, 0, 0),
('7550', 51, 0, 0, 0, 0),
('7550', 52, 0, 0, 0, 0),
('7550', 53, 0, 0, 0, 0),
('7550', 54, 0, 0, 0, 0),
('7550', 55, 0, 0, 0, 0),
('7550', 56, 0, 0, 0, 0),
('7550', 57, 0, 0, 0, 0),
('7550', 58, 0, 0, 0, 0),
('7550', 59, 0, 0, 0, 0),
('7600', -15, 0, 0, 0, 0),
('7600', -14, 0, 0, 0, 0),
('7600', -13, 0, 0, 0, 0),
('7600', -12, 0, 0, 0, 0),
('7600', -11, 0, 0, 0, 0),
('7600', -10, 0, 0, 0, 0),
('7600', -9, 0, 0, 0, 0),
('7600', -8, 0, 0, 0, 0),
('7600', -7, 0, 0, 0, 0),
('7600', -6, 0, 0, 0, 0),
('7600', -5, 0, 0, 0, 0),
('7600', -4, 0, 0, 0, 0),
('7600', -3, 0, 0, 0, 0),
('7600', -2, 0, 0, 0, 0),
('7600', -1, 0, 0, 0, 0),
('7600', 0, 0, 0, 0, 0),
('7600', 1, 0, 0, 0, 0),
('7600', 2, 0, 0, 0, 0),
('7600', 3, 0, 0, 0, 0),
('7600', 4, 0, 0, 0, 0),
('7600', 5, 0, 0, 0, 0),
('7600', 6, 0, 0, 0, 0),
('7600', 7, 0, 0, 0, 0),
('7600', 8, 0, 0, 0, 0),
('7600', 9, 0, 0, 0, 0),
('7600', 10, 0, 0, 0, 0),
('7600', 11, 0, 0, 0, 0),
('7600', 12, 0, 0, 0, 0),
('7600', 13, 0, 0, 0, 0),
('7600', 14, 0, 0, 0, 0),
('7600', 15, 0, 0, 0, 0),
('7600', 16, 0, 0, 0, 0),
('7600', 17, 0, 0, 0, 0),
('7600', 18, 0, 0, 0, 0),
('7600', 19, 0, 0, 0, 0),
('7600', 20, 0, 0, 0, 0),
('7600', 21, 0, 0, 0, 0),
('7600', 22, 0, 0, 0, 0),
('7600', 23, 0, 0, 0, 0),
('7600', 24, 0, 0, 0, 0),
('7600', 25, 0, 0, 0, 0),
('7600', 26, 0, 0, 0, 0),
('7600', 27, 0, 0, 0, 0),
('7600', 28, 0, 0, 0, 0),
('7600', 29, 0, 0, 0, 0),
('7600', 30, 0, 0, 0, 0),
('7600', 31, 0, 0, 0, 0),
('7600', 32, 0, 0, 0, 0),
('7600', 33, 0, 0, 0, 0),
('7600', 34, 0, 0, 0, 0),
('7600', 35, 0, 0, 0, 0),
('7600', 36, 0, 0, 0, 0),
('7600', 37, 0, 0, 0, 0),
('7600', 38, 0, 0, 0, 0),
('7600', 39, 0, 0, 0, 0),
('7600', 40, 0, 0, 0, 0),
('7600', 41, 0, 0, 0, 0),
('7600', 42, 0, 0, 0, 0),
('7600', 43, 0, 0, 0, 0),
('7600', 44, 0, 0, 0, 0),
('7600', 45, 0, 0, 0, 0),
('7600', 46, 0, 0, 0, 0),
('7600', 47, 0, 0, 0, 0),
('7600', 48, 0, 0, 0, 0),
('7600', 49, 0, 0, 0, 0),
('7600', 50, 0, 0, 0, 0),
('7600', 51, 0, 0, 0, 0),
('7600', 52, 0, 0, 0, 0),
('7600', 53, 0, 0, 0, 0),
('7600', 54, 0, 0, 0, 0),
('7600', 55, 0, 0, 0, 0),
('7600', 56, 0, 0, 0, 0),
('7600', 57, 0, 0, 0, 0),
('7600', 58, 0, 0, 0, 0),
('7600', 59, 0, 0, 0, 0),
('7610', -15, 0, 0, 0, 0),
('7610', -14, 0, 0, 0, 0),
('7610', -13, 0, 0, 0, 0),
('7610', -12, 0, 0, 0, 0),
('7610', -11, 0, 0, 0, 0),
('7610', -10, 0, 0, 0, 0),
('7610', -9, 0, 0, 0, 0),
('7610', -8, 0, 0, 0, 0),
('7610', -7, 0, 0, 0, 0),
('7610', -6, 0, 0, 0, 0),
('7610', -5, 0, 0, 0, 0),
('7610', -4, 0, 0, 0, 0),
('7610', -3, 0, 0, 0, 0),
('7610', -2, 0, 0, 0, 0),
('7610', -1, 0, 0, 0, 0),
('7610', 0, 0, 0, 0, 0),
('7610', 1, 0, 0, 0, 0),
('7610', 2, 0, 0, 0, 0),
('7610', 3, 0, 0, 0, 0),
('7610', 4, 0, 0, 0, 0),
('7610', 5, 0, 0, 0, 0),
('7610', 6, 0, 0, 0, 0),
('7610', 7, 0, 0, 0, 0),
('7610', 8, 0, 0, 0, 0),
('7610', 9, 0, 0, 0, 0),
('7610', 10, 0, 0, 0, 0),
('7610', 11, 0, 0, 0, 0),
('7610', 12, 0, 0, 0, 0),
('7610', 13, 0, 0, 0, 0),
('7610', 14, 0, 0, 0, 0),
('7610', 15, 0, 0, 0, 0),
('7610', 16, 0, 0, 0, 0),
('7610', 17, 0, 0, 0, 0),
('7610', 18, 0, 0, 0, 0),
('7610', 19, 0, 0, 0, 0),
('7610', 20, 0, 0, 0, 0),
('7610', 21, 0, 0, 0, 0),
('7610', 22, 0, 0, 0, 0),
('7610', 23, 0, 0, 0, 0),
('7610', 24, 0, 0, 0, 0),
('7610', 25, 0, 0, 0, 0),
('7610', 26, 0, 0, 0, 0),
('7610', 27, 0, 0, 0, 0),
('7610', 28, 0, 0, 0, 0),
('7610', 29, 0, 0, 0, 0),
('7610', 30, 0, 0, 0, 0),
('7610', 31, 0, 0, 0, 0),
('7610', 32, 0, 0, 0, 0),
('7610', 33, 0, 0, 0, 0),
('7610', 34, 0, 0, 0, 0),
('7610', 35, 0, 0, 0, 0),
('7610', 36, 0, 0, 0, 0),
('7610', 37, 0, 0, 0, 0),
('7610', 38, 0, 0, 0, 0),
('7610', 39, 0, 0, 0, 0),
('7610', 40, 0, 0, 0, 0),
('7610', 41, 0, 0, 0, 0),
('7610', 42, 0, 0, 0, 0),
('7610', 43, 0, 0, 0, 0),
('7610', 44, 0, 0, 0, 0),
('7610', 45, 0, 0, 0, 0),
('7610', 46, 0, 0, 0, 0),
('7610', 47, 0, 0, 0, 0),
('7610', 48, 0, 0, 0, 0),
('7610', 49, 0, 0, 0, 0),
('7610', 50, 0, 0, 0, 0),
('7610', 51, 0, 0, 0, 0),
('7610', 52, 0, 0, 0, 0),
('7610', 53, 0, 0, 0, 0),
('7610', 54, 0, 0, 0, 0),
('7610', 55, 0, 0, 0, 0),
('7610', 56, 0, 0, 0, 0),
('7610', 57, 0, 0, 0, 0),
('7610', 58, 0, 0, 0, 0),
('7610', 59, 0, 0, 0, 0),
('7620', -15, 0, 0, 0, 0),
('7620', -14, 0, 0, 0, 0),
('7620', -13, 0, 0, 0, 0),
('7620', -12, 0, 0, 0, 0),
('7620', -11, 0, 0, 0, 0),
('7620', -10, 0, 0, 0, 0),
('7620', -9, 0, 0, 0, 0),
('7620', -8, 0, 0, 0, 0),
('7620', -7, 0, 0, 0, 0),
('7620', -6, 0, 0, 0, 0),
('7620', -5, 0, 0, 0, 0),
('7620', -4, 0, 0, 0, 0),
('7620', -3, 0, 0, 0, 0),
('7620', -2, 0, 0, 0, 0),
('7620', -1, 0, 0, 0, 0),
('7620', 0, 0, 0, 0, 0),
('7620', 1, 0, 0, 0, 0),
('7620', 2, 0, 0, 0, 0),
('7620', 3, 0, 0, 0, 0),
('7620', 4, 0, 0, 0, 0),
('7620', 5, 0, 0, 0, 0),
('7620', 6, 0, 0, 0, 0),
('7620', 7, 0, 0, 0, 0),
('7620', 8, 0, 0, 0, 0),
('7620', 9, 0, 0, 0, 0),
('7620', 10, 0, 0, 0, 0),
('7620', 11, 0, 0, 0, 0),
('7620', 12, 0, 0, 0, 0),
('7620', 13, 0, 0, 0, 0),
('7620', 14, 0, 0, 0, 0),
('7620', 15, 0, 0, 0, 0),
('7620', 16, 0, 0, 0, 0),
('7620', 17, 0, 0, 0, 0),
('7620', 18, 0, 0, 0, 0),
('7620', 19, 0, 0, 0, 0),
('7620', 20, 0, 0, 0, 0),
('7620', 21, 0, 0, 0, 0),
('7620', 22, 0, 0, 0, 0),
('7620', 23, 0, 0, 0, 0),
('7620', 24, 0, 0, 0, 0),
('7620', 25, 0, 0, 0, 0),
('7620', 26, 0, 0, 0, 0),
('7620', 27, 0, 0, 0, 0),
('7620', 28, 0, 0, 0, 0),
('7620', 29, 0, 0, 0, 0),
('7620', 30, 0, 0, 0, 0),
('7620', 31, 0, 0, 0, 0),
('7620', 32, 0, 0, 0, 0),
('7620', 33, 0, 0, 0, 0),
('7620', 34, 0, 0, 0, 0),
('7620', 35, 0, 0, 0, 0),
('7620', 36, 0, 0, 0, 0),
('7620', 37, 0, 0, 0, 0),
('7620', 38, 0, 0, 0, 0),
('7620', 39, 0, 0, 0, 0),
('7620', 40, 0, 0, 0, 0),
('7620', 41, 0, 0, 0, 0),
('7620', 42, 0, 0, 0, 0),
('7620', 43, 0, 0, 0, 0),
('7620', 44, 0, 0, 0, 0),
('7620', 45, 0, 0, 0, 0),
('7620', 46, 0, 0, 0, 0),
('7620', 47, 0, 0, 0, 0),
('7620', 48, 0, 0, 0, 0),
('7620', 49, 0, 0, 0, 0),
('7620', 50, 0, 0, 0, 0),
('7620', 51, 0, 0, 0, 0),
('7620', 52, 0, 0, 0, 0),
('7620', 53, 0, 0, 0, 0),
('7620', 54, 0, 0, 0, 0),
('7620', 55, 0, 0, 0, 0),
('7620', 56, 0, 0, 0, 0),
('7620', 57, 0, 0, 0, 0),
('7620', 58, 0, 0, 0, 0),
('7620', 59, 0, 0, 0, 0),
('7630', -15, 0, 0, 0, 0),
('7630', -14, 0, 0, 0, 0),
('7630', -13, 0, 0, 0, 0),
('7630', -12, 0, 0, 0, 0),
('7630', -11, 0, 0, 0, 0),
('7630', -10, 0, 0, 0, 0),
('7630', -9, 0, 0, 0, 0),
('7630', -8, 0, 0, 0, 0),
('7630', -7, 0, 0, 0, 0),
('7630', -6, 0, 0, 0, 0),
('7630', -5, 0, 0, 0, 0),
('7630', -4, 0, 0, 0, 0),
('7630', -3, 0, 0, 0, 0),
('7630', -2, 0, 0, 0, 0),
('7630', -1, 0, 0, 0, 0),
('7630', 0, 0, 0, 0, 0),
('7630', 1, 0, 0, 0, 0),
('7630', 2, 0, 0, 0, 0),
('7630', 3, 0, 0, 0, 0),
('7630', 4, 0, 0, 0, 0),
('7630', 5, 0, 0, 0, 0),
('7630', 6, 0, 0, 0, 0),
('7630', 7, 0, 0, 0, 0),
('7630', 8, 0, 0, 0, 0),
('7630', 9, 0, 0, 0, 0),
('7630', 10, 0, 0, 0, 0),
('7630', 11, 0, 0, 0, 0),
('7630', 12, 0, 0, 0, 0),
('7630', 13, 0, 0, 0, 0),
('7630', 14, 0, 0, 0, 0),
('7630', 15, 0, 0, 0, 0),
('7630', 16, 0, 0, 0, 0),
('7630', 17, 0, 0, 0, 0),
('7630', 18, 0, 0, 0, 0),
('7630', 19, 0, 0, 0, 0),
('7630', 20, 0, 0, 0, 0),
('7630', 21, 0, 0, 0, 0),
('7630', 22, 0, 0, 0, 0),
('7630', 23, 0, 0, 0, 0),
('7630', 24, 0, 0, 0, 0),
('7630', 25, 0, 0, 0, 0),
('7630', 26, 0, 0, 0, 0),
('7630', 27, 0, 0, 0, 0),
('7630', 28, 0, 0, 0, 0),
('7630', 29, 0, 0, 0, 0),
('7630', 30, 0, 0, 0, 0),
('7630', 31, 0, 0, 0, 0),
('7630', 32, 0, 0, 0, 0),
('7630', 33, 0, 0, 0, 0),
('7630', 34, 0, 0, 0, 0),
('7630', 35, 0, 0, 0, 0),
('7630', 36, 0, 0, 0, 0),
('7630', 37, 0, 0, 0, 0),
('7630', 38, 0, 0, 0, 0),
('7630', 39, 0, 0, 0, 0),
('7630', 40, 0, 0, 0, 0),
('7630', 41, 0, 0, 0, 0),
('7630', 42, 0, 0, 0, 0),
('7630', 43, 0, 0, 0, 0),
('7630', 44, 0, 0, 0, 0),
('7630', 45, 0, 0, 0, 0),
('7630', 46, 0, 0, 0, 0),
('7630', 47, 0, 0, 0, 0),
('7630', 48, 0, 0, 0, 0),
('7630', 49, 0, 0, 0, 0),
('7630', 50, 0, 0, 0, 0),
('7630', 51, 0, 0, 0, 0),
('7630', 52, 0, 0, 0, 0),
('7630', 53, 0, 0, 0, 0),
('7630', 54, 0, 0, 0, 0),
('7630', 55, 0, 0, 0, 0),
('7630', 56, 0, 0, 0, 0),
('7630', 57, 0, 0, 0, 0),
('7630', 58, 0, 0, 0, 0),
('7630', 59, 0, 0, 0, 0),
('7640', -15, 0, 0, 0, 0),
('7640', -14, 0, 0, 0, 0),
('7640', -13, 0, 0, 0, 0),
('7640', -12, 0, 0, 0, 0),
('7640', -11, 0, 0, 0, 0),
('7640', -10, 0, 0, 0, 0),
('7640', -9, 0, 0, 0, 0),
('7640', -8, 0, 0, 0, 0),
('7640', -7, 0, 0, 0, 0),
('7640', -6, 0, 0, 0, 0),
('7640', -5, 0, 0, 0, 0),
('7640', -4, 0, 0, 0, 0),
('7640', -3, 0, 0, 0, 0),
('7640', -2, 0, 0, 0, 0),
('7640', -1, 0, 0, 0, 0),
('7640', 0, 0, 0, 0, 0),
('7640', 1, 0, 0, 0, 0),
('7640', 2, 0, 0, 0, 0),
('7640', 3, 0, 0, 0, 0),
('7640', 4, 0, 0, 0, 0),
('7640', 5, 0, 0, 0, 0),
('7640', 6, 0, 0, 0, 0),
('7640', 7, 0, 0, 0, 0),
('7640', 8, 0, 0, 0, 0),
('7640', 9, 0, 0, 0, 0),
('7640', 10, 0, 0, 0, 0),
('7640', 11, 0, 0, 0, 0),
('7640', 12, 0, 0, 0, 0),
('7640', 13, 0, 0, 0, 0),
('7640', 14, 0, 0, 0, 0),
('7640', 15, 0, 0, 0, 0),
('7640', 16, 0, 0, 0, 0),
('7640', 17, 0, 0, 0, 0),
('7640', 18, 0, 0, 0, 0),
('7640', 19, 0, 0, 0, 0),
('7640', 20, 0, 0, 0, 0),
('7640', 21, 0, 0, 0, 0),
('7640', 22, 0, 0, 0, 0),
('7640', 23, 0, 0, 0, 0),
('7640', 24, 0, 0, 0, 0),
('7640', 25, 0, 0, 0, 0),
('7640', 26, 0, 0, 0, 0),
('7640', 27, 0, 0, 0, 0),
('7640', 28, 0, 0, 0, 0),
('7640', 29, 0, 0, 0, 0),
('7640', 30, 0, 0, 0, 0),
('7640', 31, 0, 0, 0, 0),
('7640', 32, 0, 0, 0, 0),
('7640', 33, 0, 0, 0, 0),
('7640', 34, 0, 0, 0, 0),
('7640', 35, 0, 0, 0, 0),
('7640', 36, 0, 0, 0, 0),
('7640', 37, 0, 0, 0, 0),
('7640', 38, 0, 0, 0, 0),
('7640', 39, 0, 0, 0, 0),
('7640', 40, 0, 0, 0, 0),
('7640', 41, 0, 0, 0, 0),
('7640', 42, 0, 0, 0, 0),
('7640', 43, 0, 0, 0, 0),
('7640', 44, 0, 0, 0, 0),
('7640', 45, 0, 0, 0, 0),
('7640', 46, 0, 0, 0, 0),
('7640', 47, 0, 0, 0, 0),
('7640', 48, 0, 0, 0, 0),
('7640', 49, 0, 0, 0, 0),
('7640', 50, 0, 0, 0, 0),
('7640', 51, 0, 0, 0, 0),
('7640', 52, 0, 0, 0, 0),
('7640', 53, 0, 0, 0, 0),
('7640', 54, 0, 0, 0, 0),
('7640', 55, 0, 0, 0, 0),
('7640', 56, 0, 0, 0, 0),
('7640', 57, 0, 0, 0, 0),
('7640', 58, 0, 0, 0, 0),
('7640', 59, 0, 0, 0, 0),
('7650', -15, 0, 0, 0, 0),
('7650', -14, 0, 0, 0, 0),
('7650', -13, 0, 0, 0, 0),
('7650', -12, 0, 0, 0, 0),
('7650', -11, 0, 0, 0, 0),
('7650', -10, 0, 0, 0, 0),
('7650', -9, 0, 0, 0, 0),
('7650', -8, 0, 0, 0, 0),
('7650', -7, 0, 0, 0, 0),
('7650', -6, 0, 0, 0, 0),
('7650', -5, 0, 0, 0, 0),
('7650', -4, 0, 0, 0, 0),
('7650', -3, 0, 0, 0, 0),
('7650', -2, 0, 0, 0, 0),
('7650', -1, 0, 0, 0, 0),
('7650', 0, 0, 0, 0, 0),
('7650', 1, 0, 0, 0, 0),
('7650', 2, 0, 0, 0, 0),
('7650', 3, 0, 0, 0, 0),
('7650', 4, 0, 0, 0, 0),
('7650', 5, 0, 0, 0, 0),
('7650', 6, 0, 0, 0, 0),
('7650', 7, 0, 0, 0, 0),
('7650', 8, 0, 0, 0, 0),
('7650', 9, 0, 0, 0, 0),
('7650', 10, 0, 0, 0, 0),
('7650', 11, 0, 0, 0, 0),
('7650', 12, 0, 0, 0, 0),
('7650', 13, 0, 0, 0, 0),
('7650', 14, 0, 0, 0, 0),
('7650', 15, 0, 0, 0, 0),
('7650', 16, 0, 0, 0, 0),
('7650', 17, 0, 0, 0, 0),
('7650', 18, 0, 0, 0, 0),
('7650', 19, 0, 0, 0, 0),
('7650', 20, 0, 0, 0, 0),
('7650', 21, 0, 0, 0, 0),
('7650', 22, 0, 0, 0, 0),
('7650', 23, 0, 0, 0, 0),
('7650', 24, 0, 0, 0, 0),
('7650', 25, 0, 0, 0, 0),
('7650', 26, 0, 0, 0, 0),
('7650', 27, 0, 0, 0, 0),
('7650', 28, 0, 0, 0, 0),
('7650', 29, 0, 0, 0, 0),
('7650', 30, 0, 0, 0, 0),
('7650', 31, 0, 0, 0, 0),
('7650', 32, 0, 0, 0, 0),
('7650', 33, 0, 0, 0, 0),
('7650', 34, 0, 0, 0, 0),
('7650', 35, 0, 0, 0, 0),
('7650', 36, 0, 0, 0, 0),
('7650', 37, 0, 0, 0, 0),
('7650', 38, 0, 0, 0, 0),
('7650', 39, 0, 0, 0, 0),
('7650', 40, 0, 0, 0, 0),
('7650', 41, 0, 0, 0, 0),
('7650', 42, 0, 0, 0, 0),
('7650', 43, 0, 0, 0, 0),
('7650', 44, 0, 0, 0, 0),
('7650', 45, 0, 0, 0, 0),
('7650', 46, 0, 0, 0, 0),
('7650', 47, 0, 0, 0, 0),
('7650', 48, 0, 0, 0, 0),
('7650', 49, 0, 0, 0, 0),
('7650', 50, 0, 0, 0, 0),
('7650', 51, 0, 0, 0, 0),
('7650', 52, 0, 0, 0, 0),
('7650', 53, 0, 0, 0, 0),
('7650', 54, 0, 0, 0, 0),
('7650', 55, 0, 0, 0, 0),
('7650', 56, 0, 0, 0, 0),
('7650', 57, 0, 0, 0, 0),
('7650', 58, 0, 0, 0, 0),
('7650', 59, 0, 0, 0, 0),
('7660', -15, 0, 0, 0, 0),
('7660', -14, 0, 0, 0, 0),
('7660', -13, 0, 0, 0, 0),
('7660', -12, 0, 0, 0, 0),
('7660', -11, 0, 0, 0, 0),
('7660', -10, 0, 0, 0, 0),
('7660', -9, 0, 0, 0, 0),
('7660', -8, 0, 0, 0, 0),
('7660', -7, 0, 0, 0, 0),
('7660', -6, 0, 0, 0, 0),
('7660', -5, 0, 0, 0, 0),
('7660', -4, 0, 0, 0, 0),
('7660', -3, 0, 0, 0, 0),
('7660', -2, 0, 0, 0, 0),
('7660', -1, 0, 0, 0, 0),
('7660', 0, 0, 0, 0, 0),
('7660', 1, 0, 0, 0, 0),
('7660', 2, 0, 0, 0, 0),
('7660', 3, 0, 0, 0, 0),
('7660', 4, 0, 0, 0, 0),
('7660', 5, 0, 0, 0, 0),
('7660', 6, 0, 0, 0, 0),
('7660', 7, 0, 0, 0, 0),
('7660', 8, 0, 0, 0, 0),
('7660', 9, 0, 0, 0, 0),
('7660', 10, 0, 0, 0, 0),
('7660', 11, 0, 0, 0, 0),
('7660', 12, 0, 0, 0, 0),
('7660', 13, 0, 0, 0, 0),
('7660', 14, 0, 0, 0, 0),
('7660', 15, 0, 0, 0, 0),
('7660', 16, 0, 0, 0, 0),
('7660', 17, 0, 0, 0, 0),
('7660', 18, 0, 0, 0, 0),
('7660', 19, 0, 0, 0, 0),
('7660', 20, 0, 0, 0, 0),
('7660', 21, 0, 0, 0, 0),
('7660', 22, 0, 0, 0, 0),
('7660', 23, 0, 0, 0, 0),
('7660', 24, 0, 0, 0, 0),
('7660', 25, 0, 0, 0, 0),
('7660', 26, 0, 0, 0, 0),
('7660', 27, 0, 0, 0, 0),
('7660', 28, 0, 0, 0, 0),
('7660', 29, 0, 0, 0, 0),
('7660', 30, 0, 0, 0, 0),
('7660', 31, 0, 0, 0, 0),
('7660', 32, 0, 0, 0, 0),
('7660', 33, 0, 0, 0, 0),
('7660', 34, 0, 0, 0, 0),
('7660', 35, 0, 0, 0, 0),
('7660', 36, 0, 0, 0, 0),
('7660', 37, 0, 0, 0, 0),
('7660', 38, 0, 0, 0, 0),
('7660', 39, 0, 0, 0, 0),
('7660', 40, 0, 0, 0, 0),
('7660', 41, 0, 0, 0, 0),
('7660', 42, 0, 0, 0, 0),
('7660', 43, 0, 0, 0, 0),
('7660', 44, 0, 0, 0, 0),
('7660', 45, 0, 0, 0, 0),
('7660', 46, 0, 0, 0, 0),
('7660', 47, 0, 0, 0, 0),
('7660', 48, 0, 0, 0, 0),
('7660', 49, 0, 0, 0, 0),
('7660', 50, 0, 0, 0, 0),
('7660', 51, 0, 0, 0, 0),
('7660', 52, 0, 0, 0, 0),
('7660', 53, 0, 0, 0, 0),
('7660', 54, 0, 0, 0, 0),
('7660', 55, 0, 0, 0, 0),
('7660', 56, 0, 0, 0, 0),
('7660', 57, 0, 0, 0, 0),
('7660', 58, 0, 0, 0, 0),
('7660', 59, 0, 0, 0, 0),
('7700', -15, 0, 0, 0, 0),
('7700', -14, 0, 0, 0, 0),
('7700', -13, 0, 0, 0, 0),
('7700', -12, 0, 0, 0, 0),
('7700', -11, 0, 0, 0, 0),
('7700', -10, 0, 0, 0, 0),
('7700', -9, 0, 0, 0, 0),
('7700', -8, 0, 0, 0, 0),
('7700', -7, 0, 0, 0, 0),
('7700', -6, 0, 0, 0, 0),
('7700', -5, 0, 0, 0, 0),
('7700', -4, 0, 0, 0, 0),
('7700', -3, 0, 0, 0, 0),
('7700', -2, 0, 0, 0, 0),
('7700', -1, 0, 0, 0, 0),
('7700', 0, 0, 0, 0, 0),
('7700', 1, 0, 0, 0, 0),
('7700', 2, 0, 0, 0, 0),
('7700', 3, 0, 0, 0, 0),
('7700', 4, 0, 0, 0, 0),
('7700', 5, 0, 0, 0, 0),
('7700', 6, 0, 0, 0, 0),
('7700', 7, 0, 0, 0, 0),
('7700', 8, 0, 0, 0, 0),
('7700', 9, 0, 0, 0, 0),
('7700', 10, 0, 0, 0, 0),
('7700', 11, 0, 0, 0, 0),
('7700', 12, 0, 0, 0, 0),
('7700', 13, 0, 0, 0, 0),
('7700', 14, 0, 0, 0, 0),
('7700', 15, 0, 0, 0, 0),
('7700', 16, 0, 0, 0, 0),
('7700', 17, 0, 0, 0, 0),
('7700', 18, 0, 0, 0, 0),
('7700', 19, 0, 0, 0, 0),
('7700', 20, 0, 0, 0, 0),
('7700', 21, 0, 0, 0, 0),
('7700', 22, 0, 0, 0, 0),
('7700', 23, 0, 0, 0, 0),
('7700', 24, 0, 0, 0, 0),
('7700', 25, 0, 0, 0, 0),
('7700', 26, 0, 0, 0, 0),
('7700', 27, 0, 0, 0, 0),
('7700', 28, 0, 0, 0, 0),
('7700', 29, 0, 0, 0, 0),
('7700', 30, 0, 0, 0, 0),
('7700', 31, 0, 0, 0, 0),
('7700', 32, 0, 0, 0, 0),
('7700', 33, 0, 0, 0, 0),
('7700', 34, 0, 0, 0, 0),
('7700', 35, 0, 0, 0, 0),
('7700', 36, 0, 0, 0, 0),
('7700', 37, 0, 0, 0, 0),
('7700', 38, 0, 0, 0, 0),
('7700', 39, 0, 0, 0, 0),
('7700', 40, 0, 0, 0, 0),
('7700', 41, 0, 0, 0, 0),
('7700', 42, 0, 0, 0, 0),
('7700', 43, 0, 0, 0, 0),
('7700', 44, 0, 0, 0, 0),
('7700', 45, 0, 0, 0, 0),
('7700', 46, 0, 0, 0, 0),
('7700', 47, 0, 0, 0, 0),
('7700', 48, 0, 0, 0, 0),
('7700', 49, 0, 0, 0, 0),
('7700', 50, 0, 0, 0, 0),
('7700', 51, 0, 0, 0, 0),
('7700', 52, 0, 0, 0, 0),
('7700', 53, 0, 0, 0, 0),
('7700', 54, 0, 0, 0, 0),
('7700', 55, 0, 0, 0, 0),
('7700', 56, 0, 0, 0, 0),
('7700', 57, 0, 0, 0, 0),
('7700', 58, 0, 0, 0, 0),
('7700', 59, 0, 0, 0, 0),
('7750', -15, 0, 0, 0, 0),
('7750', -14, 0, 0, 0, 0),
('7750', -13, 0, 0, 0, 0),
('7750', -12, 0, 0, 0, 0),
('7750', -11, 0, 0, 0, 0),
('7750', -10, 0, 0, 0, 0),
('7750', -9, 0, 0, 0, 0),
('7750', -8, 0, 0, 0, 0),
('7750', -7, 0, 0, 0, 0),
('7750', -6, 0, 0, 0, 0),
('7750', -5, 0, 0, 0, 0),
('7750', -4, 0, 0, 0, 0),
('7750', -3, 0, 0, 0, 0),
('7750', -2, 0, 0, 0, 0),
('7750', -1, 0, 0, 0, 0),
('7750', 0, 0, 0, 0, 0),
('7750', 1, 0, 0, 0, 0),
('7750', 2, 0, 0, 0, 0),
('7750', 3, 0, 0, 0, 0),
('7750', 4, 0, 0, 0, 0),
('7750', 5, 0, 0, 0, 0),
('7750', 6, 0, 0, 0, 0),
('7750', 7, 0, 0, 0, 0),
('7750', 8, 0, 0, 0, 0),
('7750', 9, 0, 0, 0, 0),
('7750', 10, 0, 0, 0, 0),
('7750', 11, 0, 0, 0, 0),
('7750', 12, 0, 0, 0, 0),
('7750', 13, 0, 0, 0, 0),
('7750', 14, 0, 0, 0, 0),
('7750', 15, 0, 0, 0, 0),
('7750', 16, 0, 0, 0, 0),
('7750', 17, 0, 0, 0, 0),
('7750', 18, 0, 0, 0, 0),
('7750', 19, 0, 0, 0, 0),
('7750', 20, 0, 0, 0, 0),
('7750', 21, 0, 0, 0, 0),
('7750', 22, 0, 0, 0, 0),
('7750', 23, 0, 0, 0, 0),
('7750', 24, 0, 0, 0, 0),
('7750', 25, 0, 0, 0, 0),
('7750', 26, 0, 0, 0, 0),
('7750', 27, 0, 0, 0, 0),
('7750', 28, 0, 0, 0, 0),
('7750', 29, 0, 0, 0, 0),
('7750', 30, 0, 0, 0, 0),
('7750', 31, 0, 0, 0, 0),
('7750', 32, 0, 0, 0, 0),
('7750', 33, 0, 0, 0, 0),
('7750', 34, 0, 0, 0, 0),
('7750', 35, 0, 0, 0, 0),
('7750', 36, 0, 0, 0, 0),
('7750', 37, 0, 0, 0, 0),
('7750', 38, 0, 0, 0, 0),
('7750', 39, 0, 0, 0, 0),
('7750', 40, 0, 0, 0, 0),
('7750', 41, 0, 0, 0, 0),
('7750', 42, 0, 0, 0, 0),
('7750', 43, 0, 0, 0, 0),
('7750', 44, 0, 0, 0, 0),
('7750', 45, 0, 0, 0, 0),
('7750', 46, 0, 0, 0, 0),
('7750', 47, 0, 0, 0, 0),
('7750', 48, 0, 0, 0, 0),
('7750', 49, 0, 0, 0, 0),
('7750', 50, 0, 0, 0, 0),
('7750', 51, 0, 0, 0, 0),
('7750', 52, 0, 0, 0, 0),
('7750', 53, 0, 0, 0, 0),
('7750', 54, 0, 0, 0, 0),
('7750', 55, 0, 0, 0, 0),
('7750', 56, 0, 0, 0, 0),
('7750', 57, 0, 0, 0, 0),
('7750', 58, 0, 0, 0, 0),
('7750', 59, 0, 0, 0, 0),
('7800', -15, 0, 0, 0, 0),
('7800', -14, 0, 0, 0, 0),
('7800', -13, 0, 0, 0, 0),
('7800', -12, 0, 0, 0, 0),
('7800', -11, 0, 0, 0, 0),
('7800', -10, 0, 0, 0, 0),
('7800', -9, 0, 0, 0, 0),
('7800', -8, 0, 0, 0, 0),
('7800', -7, 0, 0, 0, 0),
('7800', -6, 0, 0, 0, 0),
('7800', -5, 0, 0, 0, 0),
('7800', -4, 0, 0, 0, 0),
('7800', -3, 0, 0, 0, 0),
('7800', -2, 0, 0, 0, 0),
('7800', -1, 0, 0, 0, 0),
('7800', 0, 0, 0, 0, 0),
('7800', 1, 0, 0, 0, 0),
('7800', 2, 0, 0, 0, 0),
('7800', 3, 0, 0, 0, 0),
('7800', 4, 0, 0, 0, 0),
('7800', 5, 0, 0, 0, 0),
('7800', 6, 0, 0, 0, 0),
('7800', 7, 0, 0, 0, 0),
('7800', 8, 0, 0, 0, 0),
('7800', 9, 0, 0, 0, 0),
('7800', 10, 0, 0, 0, 0),
('7800', 11, 0, 0, 0, 0),
('7800', 12, 0, 0, 0, 0),
('7800', 13, 0, 0, 0, 0),
('7800', 14, 0, 0, 0, 0),
('7800', 15, 0, 0, 0, 0),
('7800', 16, 0, 0, 0, 0),
('7800', 17, 0, 0, 0, 0),
('7800', 18, 0, 0, 0, 0),
('7800', 19, 0, 0, 0, 0),
('7800', 20, 0, 0, 0, 0),
('7800', 21, 0, 0, 0, 0),
('7800', 22, 0, 0, 0, 0),
('7800', 23, 0, 0, 0, 0),
('7800', 24, 0, 0, 0, 0),
('7800', 25, 0, 0, 0, 0),
('7800', 26, 0, 0, 0, 0),
('7800', 27, 0, 0, 0, 0),
('7800', 28, 0, 0, 0, 0),
('7800', 29, 0, 0, 0, 0),
('7800', 30, 0, 0, 0, 0),
('7800', 31, 0, 0, 0, 0),
('7800', 32, 0, 0, 0, 0),
('7800', 33, 0, 0, 0, 0),
('7800', 34, 0, 0, 0, 0),
('7800', 35, 0, 0, 0, 0),
('7800', 36, 0, 0, 0, 0),
('7800', 37, 0, 0, 0, 0),
('7800', 38, 0, 0, 0, 0),
('7800', 39, 0, 0, 0, 0),
('7800', 40, 0, 0, 0, 0),
('7800', 41, 0, 0, 0, 0),
('7800', 42, 0, 0, 0, 0),
('7800', 43, 0, 0, 0, 0),
('7800', 44, 0, 0, 0, 0),
('7800', 45, 0, 0, 0, 0),
('7800', 46, 0, 0, 0, 0),
('7800', 47, 0, 0, 0, 0),
('7800', 48, 0, 0, 0, 0),
('7800', 49, 0, 0, 0, 0),
('7800', 50, 0, 0, 0, 0),
('7800', 51, 0, 0, 0, 0),
('7800', 52, 0, 0, 0, 0),
('7800', 53, 0, 0, 0, 0),
('7800', 54, 0, 0, 0, 0),
('7800', 55, 0, 0, 0, 0),
('7800', 56, 0, 0, 0, 0),
('7800', 57, 0, 0, 0, 0),
('7800', 58, 0, 0, 0, 0),
('7800', 59, 0, 0, 0, 0),
('7900', -15, 0, 0, 0, 0),
('7900', -14, 0, 0, 0, 0),
('7900', -13, 0, 0, 0, 0),
('7900', -12, 0, 0, 0, 0),
('7900', -11, 0, 0, 0, 0),
('7900', -10, 0, 0, 0, 0),
('7900', -9, 0, 0, 0, 0),
('7900', -8, 0, 0, 0, 0),
('7900', -7, 0, 0, 0, 0),
('7900', -6, 0, 0, 0, 0),
('7900', -5, 0, 0, 0, 0),
('7900', -4, 0, 0, 0, 0),
('7900', -3, 0, 0, 0, 0),
('7900', -2, 0, 0, 0, 0),
('7900', -1, 0, 0, 0, 0),
('7900', 0, 0, 0, 0, 0),
('7900', 1, 0, 0, 0, 0),
('7900', 2, 0, 0, 0, 0),
('7900', 3, 0, 0, 0, 0),
('7900', 4, 0, 0, 0, 0),
('7900', 5, 0, 0, 0, 0),
('7900', 6, 0, 0, 0, 0),
('7900', 7, 0, 0, 0, 0),
('7900', 8, 0, 0, 0, 0),
('7900', 9, 0, 0, 0, 0),
('7900', 10, 0, 0, 0, 0),
('7900', 11, 0, 0, 0, 0),
('7900', 12, 0, 0, 0, 0),
('7900', 13, 0, 0, 0, 0),
('7900', 14, 0, 0, 0, 0),
('7900', 15, 0, 0, 0, 0),
('7900', 16, 0, 0, 0, 0),
('7900', 17, 0, 0, 0, 0),
('7900', 18, 0, 0, 0, 0),
('7900', 19, 0, 0, 0, 0),
('7900', 20, 0, 0, 0, 0),
('7900', 21, 0, 0, 0, 0),
('7900', 22, 0, 0, 0, 0),
('7900', 23, 0, 0, 0, 0),
('7900', 24, 0, 0, 0, 0),
('7900', 25, 0, 0, 0, 0),
('7900', 26, 0, 0, 0, 0),
('7900', 27, 0, 0, 0, 0),
('7900', 28, 0, 0, 0, 0),
('7900', 29, 0, 0, 0, 0),
('7900', 30, 0, 0, 0, 0),
('7900', 31, 0, 0, 0, 0),
('7900', 32, 0, 0, 0, 0),
('7900', 33, 0, 0, 0, 0),
('7900', 34, 0, 0, 0, 0),
('7900', 35, 0, 0, 0, 0),
('7900', 36, 0, 0, 0, 0),
('7900', 37, 0, 0, 0, 0),
('7900', 38, 0, 0, 0, 0),
('7900', 39, 0, 0, 0, 0),
('7900', 40, 0, 0, 0, 0),
('7900', 41, 0, 0, 0, 0),
('7900', 42, 0, 0, 0, 0),
('7900', 43, 0, 0, 0, 0),
('7900', 44, 0, 0, 0, 0),
('7900', 45, 0, 0, 0, 0),
('7900', 46, 0, 0, 0, 0),
('7900', 47, 0, 0, 0, 0),
('7900', 48, 0, 0, 0, 0),
('7900', 49, 0, 0, 0, 0),
('7900', 50, 0, 0, 0, 0),
('7900', 51, 0, 0, 0, 0),
('7900', 52, 0, 0, 0, 0),
('7900', 53, 0, 0, 0, 0),
('7900', 54, 0, 0, 0, 0),
('7900', 55, 0, 0, 0, 0),
('7900', 56, 0, 0, 0, 0),
('7900', 57, 0, 0, 0, 0),
('7900', 58, 0, 0, 0, 0),
('7900', 59, 0, 0, 0, 0),
('8100', -15, 0, 0, 0, 0),
('8100', -14, 0, 0, 0, 0),
('8100', -13, 0, 0, 0, 0),
('8100', -12, 0, 0, 0, 0),
('8100', -11, 0, 0, 0, 0),
('8100', -10, 0, 0, 0, 0),
('8100', -9, 0, 0, 0, 0),
('8100', -8, 0, 0, 0, 0),
('8100', -7, 0, 0, 0, 0),
('8100', -6, 0, 0, 0, 0),
('8100', -5, 0, 0, 0, 0),
('8100', -4, 0, 0, 0, 0),
('8100', -3, 0, 0, 0, 0),
('8100', -2, 0, 0, 0, 0),
('8100', -1, 0, 0, 0, 0),
('8100', 0, 0, 0, 0, 0),
('8100', 1, 0, 0, 0, 0),
('8100', 2, 0, 0, 0, 0),
('8100', 3, 0, 0, 0, 0),
('8100', 4, 0, 0, 0, 0),
('8100', 5, 0, 0, 0, 0),
('8100', 6, 0, 0, 0, 0),
('8100', 7, 0, 0, 0, 0),
('8100', 8, 0, 0, 0, 0),
('8100', 9, 0, 0, 0, 0),
('8100', 10, 0, 0, 0, 0),
('8100', 11, 0, 0, 0, 0),
('8100', 12, 0, 0, 0, 0),
('8100', 13, 0, 0, 0, 0),
('8100', 14, 0, 0, 0, 0),
('8100', 15, 0, 0, 0, 0),
('8100', 16, 0, 0, 0, 0),
('8100', 17, 0, 0, 0, 0),
('8100', 18, 0, 0, 0, 0),
('8100', 19, 0, 0, 0, 0),
('8100', 20, 0, 0, 0, 0),
('8100', 21, 0, 0, 0, 0),
('8100', 22, 0, 0, 0, 0),
('8100', 23, 0, 0, 0, 0),
('8100', 24, 0, 0, 0, 0),
('8100', 25, 0, 0, 0, 0),
('8100', 26, 0, 0, 0, 0),
('8100', 27, 0, 0, 0, 0),
('8100', 28, 0, 0, 0, 0),
('8100', 29, 0, 0, 0, 0),
('8100', 30, 0, 0, 0, 0),
('8100', 31, 0, 0, 0, 0),
('8100', 32, 0, 0, 0, 0),
('8100', 33, 0, 0, 0, 0),
('8100', 34, 0, 0, 0, 0),
('8100', 35, 0, 0, 0, 0),
('8100', 36, 0, 0, 0, 0),
('8100', 37, 0, 0, 0, 0),
('8100', 38, 0, 0, 0, 0),
('8100', 39, 0, 0, 0, 0),
('8100', 40, 0, 0, 0, 0),
('8100', 41, 0, 0, 0, 0),
('8100', 42, 0, 0, 0, 0),
('8100', 43, 0, 0, 0, 0),
('8100', 44, 0, 0, 0, 0),
('8100', 45, 0, 0, 0, 0),
('8100', 46, 0, 0, 0, 0),
('8100', 47, 0, 0, 0, 0),
('8100', 48, 0, 0, 0, 0),
('8100', 49, 0, 0, 0, 0),
('8100', 50, 0, 0, 0, 0),
('8100', 51, 0, 0, 0, 0),
('8100', 52, 0, 0, 0, 0),
('8100', 53, 0, 0, 0, 0),
('8100', 54, 0, 0, 0, 0),
('8100', 55, 0, 0, 0, 0),
('8100', 56, 0, 0, 0, 0),
('8100', 57, 0, 0, 0, 0),
('8100', 58, 0, 0, 0, 0),
('8100', 59, 0, 0, 0, 0);
INSERT INTO `chartdetails` (`accountcode`, `period`, `budget`, `actual`, `bfwd`, `bfwdbudget`) VALUES
('8200', -15, 0, 0, 0, 0),
('8200', -14, 0, 0, 0, 0),
('8200', -13, 0, 0, 0, 0),
('8200', -12, 0, 0, 0, 0),
('8200', -11, 0, 0, 0, 0),
('8200', -10, 0, 0, 0, 0),
('8200', -9, 0, 0, 0, 0),
('8200', -8, 0, 0, 0, 0),
('8200', -7, 0, 0, 0, 0),
('8200', -6, 0, 0, 0, 0),
('8200', -5, 0, 0, 0, 0),
('8200', -4, 0, 0, 0, 0),
('8200', -3, 0, 0, 0, 0),
('8200', -2, 0, 0, 0, 0),
('8200', -1, 0, 0, 0, 0),
('8200', 0, 0, 0, 0, 0),
('8200', 1, 0, 0, 0, 0),
('8200', 2, 0, 0, 0, 0),
('8200', 3, 0, 0, 0, 0),
('8200', 4, 0, 0, 0, 0),
('8200', 5, 0, 0, 0, 0),
('8200', 6, 0, 0, 0, 0),
('8200', 7, 0, 0, 0, 0),
('8200', 8, 0, 0, 0, 0),
('8200', 9, 0, 0, 0, 0),
('8200', 10, 0, 0, 0, 0),
('8200', 11, 0, 0, 0, 0),
('8200', 12, 0, 0, 0, 0),
('8200', 13, 0, 0, 0, 0),
('8200', 14, 0, 0, 0, 0),
('8200', 15, 0, 0, 0, 0),
('8200', 16, 0, 0, 0, 0),
('8200', 17, 0, 0, 0, 0),
('8200', 18, 0, 0, 0, 0),
('8200', 19, 0, 0, 0, 0),
('8200', 20, 0, 0, 0, 0),
('8200', 21, 0, 0, 0, 0),
('8200', 22, 0, 0, 0, 0),
('8200', 23, 0, 0, 0, 0),
('8200', 24, 0, 0, 0, 0),
('8200', 25, 0, 0, 0, 0),
('8200', 26, 0, 0, 0, 0),
('8200', 27, 0, 0, 0, 0),
('8200', 28, 0, 0, 0, 0),
('8200', 29, 0, 0, 0, 0),
('8200', 30, 0, 0, 0, 0),
('8200', 31, 0, 0, 0, 0),
('8200', 32, 0, 0, 0, 0),
('8200', 33, 0, 0, 0, 0),
('8200', 34, 0, 0, 0, 0),
('8200', 35, 0, 0, 0, 0),
('8200', 36, 0, 0, 0, 0),
('8200', 37, 0, 0, 0, 0),
('8200', 38, 0, 0, 0, 0),
('8200', 39, 0, 0, 0, 0),
('8200', 40, 0, 0, 0, 0),
('8200', 41, 0, 0, 0, 0),
('8200', 42, 0, 0, 0, 0),
('8200', 43, 0, 0, 0, 0),
('8200', 44, 0, 0, 0, 0),
('8200', 45, 0, 0, 0, 0),
('8200', 46, 0, 0, 0, 0),
('8200', 47, 0, 0, 0, 0),
('8200', 48, 0, 0, 0, 0),
('8200', 49, 0, 0, 0, 0),
('8200', 50, 0, 0, 0, 0),
('8200', 51, 0, 0, 0, 0),
('8200', 52, 0, 0, 0, 0),
('8200', 53, 0, 0, 0, 0),
('8200', 54, 0, 0, 0, 0),
('8200', 55, 0, 0, 0, 0),
('8200', 56, 0, 0, 0, 0),
('8200', 57, 0, 0, 0, 0),
('8200', 58, 0, 0, 0, 0),
('8200', 59, 0, 0, 0, 0),
('8300', -15, 0, 0, 0, 0),
('8300', -14, 0, 0, 0, 0),
('8300', -13, 0, 0, 0, 0),
('8300', -12, 0, 0, 0, 0),
('8300', -11, 0, 0, 0, 0),
('8300', -10, 0, 0, 0, 0),
('8300', -9, 0, 0, 0, 0),
('8300', -8, 0, 0, 0, 0),
('8300', -7, 0, 0, 0, 0),
('8300', -6, 0, 0, 0, 0),
('8300', -5, 0, 0, 0, 0),
('8300', -4, 0, 0, 0, 0),
('8300', -3, 0, 0, 0, 0),
('8300', -2, 0, 0, 0, 0),
('8300', -1, 0, 0, 0, 0),
('8300', 0, 0, 0, 0, 0),
('8300', 1, 0, 0, 0, 0),
('8300', 2, 0, 0, 0, 0),
('8300', 3, 0, 0, 0, 0),
('8300', 4, 0, 0, 0, 0),
('8300', 5, 0, 0, 0, 0),
('8300', 6, 0, 0, 0, 0),
('8300', 7, 0, 0, 0, 0),
('8300', 8, 0, 0, 0, 0),
('8300', 9, 0, 0, 0, 0),
('8300', 10, 0, 0, 0, 0),
('8300', 11, 0, 0, 0, 0),
('8300', 12, 0, 0, 0, 0),
('8300', 13, 0, 0, 0, 0),
('8300', 14, 0, 0, 0, 0),
('8300', 15, 0, 0, 0, 0),
('8300', 16, 0, 0, 0, 0),
('8300', 17, 0, 0, 0, 0),
('8300', 18, 0, 0, 0, 0),
('8300', 19, 0, 0, 0, 0),
('8300', 20, 0, 0, 0, 0),
('8300', 21, 0, 0, 0, 0),
('8300', 22, 0, 0, 0, 0),
('8300', 23, 0, 0, 0, 0),
('8300', 24, 0, 0, 0, 0),
('8300', 25, 0, 0, 0, 0),
('8300', 26, 0, 0, 0, 0),
('8300', 27, 0, 0, 0, 0),
('8300', 28, 0, 0, 0, 0),
('8300', 29, 0, 0, 0, 0),
('8300', 30, 0, 0, 0, 0),
('8300', 31, 0, 0, 0, 0),
('8300', 32, 0, 0, 0, 0),
('8300', 33, 0, 0, 0, 0),
('8300', 34, 0, 0, 0, 0),
('8300', 35, 0, 0, 0, 0),
('8300', 36, 0, 0, 0, 0),
('8300', 37, 0, 0, 0, 0),
('8300', 38, 0, 0, 0, 0),
('8300', 39, 0, 0, 0, 0),
('8300', 40, 0, 0, 0, 0),
('8300', 41, 0, 0, 0, 0),
('8300', 42, 0, 0, 0, 0),
('8300', 43, 0, 0, 0, 0),
('8300', 44, 0, 0, 0, 0),
('8300', 45, 0, 0, 0, 0),
('8300', 46, 0, 0, 0, 0),
('8300', 47, 0, 0, 0, 0),
('8300', 48, 0, 0, 0, 0),
('8300', 49, 0, 0, 0, 0),
('8300', 50, 0, 0, 0, 0),
('8300', 51, 0, 0, 0, 0),
('8300', 52, 0, 0, 0, 0),
('8300', 53, 0, 0, 0, 0),
('8300', 54, 0, 0, 0, 0),
('8300', 55, 0, 0, 0, 0),
('8300', 56, 0, 0, 0, 0),
('8300', 57, 0, 0, 0, 0),
('8300', 58, 0, 0, 0, 0),
('8300', 59, 0, 0, 0, 0),
('8400', -15, 0, 0, 0, 0),
('8400', -14, 0, 0, 0, 0),
('8400', -13, 0, 0, 0, 0),
('8400', -12, 0, 0, 0, 0),
('8400', -11, 0, 0, 0, 0),
('8400', -10, 0, 0, 0, 0),
('8400', -9, 0, 0, 0, 0),
('8400', -8, 0, 0, 0, 0),
('8400', -7, 0, 0, 0, 0),
('8400', -6, 0, 0, 0, 0),
('8400', -5, 0, 0, 0, 0),
('8400', -4, 0, 0, 0, 0),
('8400', -3, 0, 0, 0, 0),
('8400', -2, 0, 0, 0, 0),
('8400', -1, 0, 0, 0, 0),
('8400', 0, 0, 0, 0, 0),
('8400', 1, 0, 0, 0, 0),
('8400', 2, 0, 0, 0, 0),
('8400', 3, 0, 0, 0, 0),
('8400', 4, 0, 0, 0, 0),
('8400', 5, 0, 0, 0, 0),
('8400', 6, 0, 0, 0, 0),
('8400', 7, 0, 0, 0, 0),
('8400', 8, 0, 0, 0, 0),
('8400', 9, 0, 0, 0, 0),
('8400', 10, 0, 0, 0, 0),
('8400', 11, 0, 0, 0, 0),
('8400', 12, 0, 0, 0, 0),
('8400', 13, 0, 0, 0, 0),
('8400', 14, 0, 0, 0, 0),
('8400', 15, 0, 0, 0, 0),
('8400', 16, 0, 0, 0, 0),
('8400', 17, 0, 0, 0, 0),
('8400', 18, 0, 0, 0, 0),
('8400', 19, 0, 0, 0, 0),
('8400', 20, 0, 0, 0, 0),
('8400', 21, 0, 0, 0, 0),
('8400', 22, 0, 0, 0, 0),
('8400', 23, 0, 0, 0, 0),
('8400', 24, 0, 0, 0, 0),
('8400', 25, 0, 0, 0, 0),
('8400', 26, 0, 0, 0, 0),
('8400', 27, 0, 0, 0, 0),
('8400', 28, 0, 0, 0, 0),
('8400', 29, 0, 0, 0, 0),
('8400', 30, 0, 0, 0, 0),
('8400', 31, 0, 0, 0, 0),
('8400', 32, 0, 0, 0, 0),
('8400', 33, 0, 0, 0, 0),
('8400', 34, 0, 0, 0, 0),
('8400', 35, 0, 0, 0, 0),
('8400', 36, 0, 0, 0, 0),
('8400', 37, 0, 0, 0, 0),
('8400', 38, 0, 0, 0, 0),
('8400', 39, 0, 0, 0, 0),
('8400', 40, 0, 0, 0, 0),
('8400', 41, 0, 0, 0, 0),
('8400', 42, 0, 0, 0, 0),
('8400', 43, 0, 0, 0, 0),
('8400', 44, 0, 0, 0, 0),
('8400', 45, 0, 0, 0, 0),
('8400', 46, 0, 0, 0, 0),
('8400', 47, 0, 0, 0, 0),
('8400', 48, 0, 0, 0, 0),
('8400', 49, 0, 0, 0, 0),
('8400', 50, 0, 0, 0, 0),
('8400', 51, 0, 0, 0, 0),
('8400', 52, 0, 0, 0, 0),
('8400', 53, 0, 0, 0, 0),
('8400', 54, 0, 0, 0, 0),
('8400', 55, 0, 0, 0, 0),
('8400', 56, 0, 0, 0, 0),
('8400', 57, 0, 0, 0, 0),
('8400', 58, 0, 0, 0, 0),
('8400', 59, 0, 0, 0, 0),
('8500', -15, 0, 0, 0, 0),
('8500', -14, 0, 0, 0, 0),
('8500', -13, 0, 0, 0, 0),
('8500', -12, 0, 0, 0, 0),
('8500', -11, 0, 0, 0, 0),
('8500', -10, 0, 0, 0, 0),
('8500', -9, 0, 0, 0, 0),
('8500', -8, 0, 0, 0, 0),
('8500', -7, 0, 0, 0, 0),
('8500', -6, 0, 0, 0, 0),
('8500', -5, 0, 0, 0, 0),
('8500', -4, 0, 0, 0, 0),
('8500', -3, 0, 0, 0, 0),
('8500', -2, 0, 0, 0, 0),
('8500', -1, 0, 0, 0, 0),
('8500', 0, 0, 0, 0, 0),
('8500', 1, 0, 0, 0, 0),
('8500', 2, 0, 0, 0, 0),
('8500', 3, 0, 0, 0, 0),
('8500', 4, 0, 0, 0, 0),
('8500', 5, 0, 0, 0, 0),
('8500', 6, 0, 0, 0, 0),
('8500', 7, 0, 0, 0, 0),
('8500', 8, 0, 0, 0, 0),
('8500', 9, 0, 0, 0, 0),
('8500', 10, 0, 0, 0, 0),
('8500', 11, 0, 0, 0, 0),
('8500', 12, 0, 0, 0, 0),
('8500', 13, 0, 0, 0, 0),
('8500', 14, 0, 0, 0, 0),
('8500', 15, 0, 0, 0, 0),
('8500', 16, 0, 0, 0, 0),
('8500', 17, 0, 0, 0, 0),
('8500', 18, 0, 0, 0, 0),
('8500', 19, 0, 0, 0, 0),
('8500', 20, 0, 0, 0, 0),
('8500', 21, 0, 0, 0, 0),
('8500', 22, 0, 0, 0, 0),
('8500', 23, 0, 0, 0, 0),
('8500', 24, 0, 0, 0, 0),
('8500', 25, 0, 0, 0, 0),
('8500', 26, 0, 0, 0, 0),
('8500', 27, 0, 0, 0, 0),
('8500', 28, 0, 0, 0, 0),
('8500', 29, 0, 0, 0, 0),
('8500', 30, 0, 0, 0, 0),
('8500', 31, 0, 0, 0, 0),
('8500', 32, 0, 0, 0, 0),
('8500', 33, 0, 0, 0, 0),
('8500', 34, 0, 0, 0, 0),
('8500', 35, 0, 0, 0, 0),
('8500', 36, 0, 0, 0, 0),
('8500', 37, 0, 0, 0, 0),
('8500', 38, 0, 0, 0, 0),
('8500', 39, 0, 0, 0, 0),
('8500', 40, 0, 0, 0, 0),
('8500', 41, 0, 0, 0, 0),
('8500', 42, 0, 0, 0, 0),
('8500', 43, 0, 0, 0, 0),
('8500', 44, 0, 0, 0, 0),
('8500', 45, 0, 0, 0, 0),
('8500', 46, 0, 0, 0, 0),
('8500', 47, 0, 0, 0, 0),
('8500', 48, 0, 0, 0, 0),
('8500', 49, 0, 0, 0, 0),
('8500', 50, 0, 0, 0, 0),
('8500', 51, 0, 0, 0, 0),
('8500', 52, 0, 0, 0, 0),
('8500', 53, 0, 0, 0, 0),
('8500', 54, 0, 0, 0, 0),
('8500', 55, 0, 0, 0, 0),
('8500', 56, 0, 0, 0, 0),
('8500', 57, 0, 0, 0, 0),
('8500', 58, 0, 0, 0, 0),
('8500', 59, 0, 0, 0, 0),
('8600', -15, 0, 0, 0, 0),
('8600', -14, 0, 0, 0, 0),
('8600', -13, 0, 0, 0, 0),
('8600', -12, 0, 0, 0, 0),
('8600', -11, 0, 0, 0, 0),
('8600', -10, 0, 0, 0, 0),
('8600', -9, 0, 0, 0, 0),
('8600', -8, 0, 0, 0, 0),
('8600', -7, 0, 0, 0, 0),
('8600', -6, 0, 0, 0, 0),
('8600', -5, 0, 0, 0, 0),
('8600', -4, 0, 0, 0, 0),
('8600', -3, 0, 0, 0, 0),
('8600', -2, 0, 0, 0, 0),
('8600', -1, 0, 0, 0, 0),
('8600', 0, 0, 0, 0, 0),
('8600', 1, 0, 0, 0, 0),
('8600', 2, 0, 0, 0, 0),
('8600', 3, 0, 0, 0, 0),
('8600', 4, 0, 0, 0, 0),
('8600', 5, 0, 0, 0, 0),
('8600', 6, 0, 0, 0, 0),
('8600', 7, 0, 0, 0, 0),
('8600', 8, 0, 0, 0, 0),
('8600', 9, 0, 0, 0, 0),
('8600', 10, 0, 0, 0, 0),
('8600', 11, 0, 0, 0, 0),
('8600', 12, 0, 0, 0, 0),
('8600', 13, 0, 0, 0, 0),
('8600', 14, 0, 0, 0, 0),
('8600', 15, 0, 0, 0, 0),
('8600', 16, 0, 0, 0, 0),
('8600', 17, 0, 0, 0, 0),
('8600', 18, 0, 0, 0, 0),
('8600', 19, 0, 0, 0, 0),
('8600', 20, 0, 0, 0, 0),
('8600', 21, 0, 0, 0, 0),
('8600', 22, 0, 0, 0, 0),
('8600', 23, 0, 0, 0, 0),
('8600', 24, 0, 0, 0, 0),
('8600', 25, 0, 0, 0, 0),
('8600', 26, 0, 0, 0, 0),
('8600', 27, 0, 0, 0, 0),
('8600', 28, 0, 0, 0, 0),
('8600', 29, 0, 0, 0, 0),
('8600', 30, 0, 0, 0, 0),
('8600', 31, 0, 0, 0, 0),
('8600', 32, 0, 0, 0, 0),
('8600', 33, 0, 0, 0, 0),
('8600', 34, 0, 0, 0, 0),
('8600', 35, 0, 0, 0, 0),
('8600', 36, 0, 0, 0, 0),
('8600', 37, 0, 0, 0, 0),
('8600', 38, 0, 0, 0, 0),
('8600', 39, 0, 0, 0, 0),
('8600', 40, 0, 0, 0, 0),
('8600', 41, 0, 0, 0, 0),
('8600', 42, 0, 0, 0, 0),
('8600', 43, 0, 0, 0, 0),
('8600', 44, 0, 0, 0, 0),
('8600', 45, 0, 0, 0, 0),
('8600', 46, 0, 0, 0, 0),
('8600', 47, 0, 0, 0, 0),
('8600', 48, 0, 0, 0, 0),
('8600', 49, 0, 0, 0, 0),
('8600', 50, 0, 0, 0, 0),
('8600', 51, 0, 0, 0, 0),
('8600', 52, 0, 0, 0, 0),
('8600', 53, 0, 0, 0, 0),
('8600', 54, 0, 0, 0, 0),
('8600', 55, 0, 0, 0, 0),
('8600', 56, 0, 0, 0, 0),
('8600', 57, 0, 0, 0, 0),
('8600', 58, 0, 0, 0, 0),
('8600', 59, 0, 0, 0, 0),
('8900', -15, 0, 0, 0, 0),
('8900', -14, 0, 0, 0, 0),
('8900', -13, 0, 0, 0, 0),
('8900', -12, 0, 0, 0, 0),
('8900', -11, 0, 0, 0, 0),
('8900', -10, 0, 0, 0, 0),
('8900', -9, 0, 0, 0, 0),
('8900', -8, 0, 0, 0, 0),
('8900', -7, 0, 0, 0, 0),
('8900', -6, 0, 0, 0, 0),
('8900', -5, 0, 0, 0, 0),
('8900', -4, 0, 0, 0, 0),
('8900', -3, 0, 0, 0, 0),
('8900', -2, 0, 0, 0, 0),
('8900', -1, 0, 0, 0, 0),
('8900', 0, 0, 0, 0, 0),
('8900', 1, 0, 0, 0, 0),
('8900', 2, 0, 0, 0, 0),
('8900', 3, 0, 0, 0, 0),
('8900', 4, 0, 0, 0, 0),
('8900', 5, 0, 0, 0, 0),
('8900', 6, 0, 0, 0, 0),
('8900', 7, 0, 0, 0, 0),
('8900', 8, 0, 0, 0, 0),
('8900', 9, 0, 0, 0, 0),
('8900', 10, 0, 0, 0, 0),
('8900', 11, 0, 0, 0, 0),
('8900', 12, 0, 0, 0, 0),
('8900', 13, 0, 0, 0, 0),
('8900', 14, 0, 0, 0, 0),
('8900', 15, 0, 0, 0, 0),
('8900', 16, 0, 0, 0, 0),
('8900', 17, 0, 0, 0, 0),
('8900', 18, 0, 0, 0, 0),
('8900', 19, 0, 0, 0, 0),
('8900', 20, 0, 0, 0, 0),
('8900', 21, 0, 0, 0, 0),
('8900', 22, 0, 0, 0, 0),
('8900', 23, 0, 0, 0, 0),
('8900', 24, 0, 0, 0, 0),
('8900', 25, 0, 0, 0, 0),
('8900', 26, 0, 0, 0, 0),
('8900', 27, 0, 0, 0, 0),
('8900', 28, 0, 0, 0, 0),
('8900', 29, 0, 0, 0, 0),
('8900', 30, 0, 0, 0, 0),
('8900', 31, 0, 0, 0, 0),
('8900', 32, 0, 0, 0, 0),
('8900', 33, 0, 0, 0, 0),
('8900', 34, 0, 0, 0, 0),
('8900', 35, 0, 0, 0, 0),
('8900', 36, 0, 0, 0, 0),
('8900', 37, 0, 0, 0, 0),
('8900', 38, 0, 0, 0, 0),
('8900', 39, 0, 0, 0, 0),
('8900', 40, 0, 0, 0, 0),
('8900', 41, 0, 0, 0, 0),
('8900', 42, 0, 0, 0, 0),
('8900', 43, 0, 0, 0, 0),
('8900', 44, 0, 0, 0, 0),
('8900', 45, 0, 0, 0, 0),
('8900', 46, 0, 0, 0, 0),
('8900', 47, 0, 0, 0, 0),
('8900', 48, 0, 0, 0, 0),
('8900', 49, 0, 0, 0, 0),
('8900', 50, 0, 0, 0, 0),
('8900', 51, 0, 0, 0, 0),
('8900', 52, 0, 0, 0, 0),
('8900', 53, 0, 0, 0, 0),
('8900', 54, 0, 0, 0, 0),
('8900', 55, 0, 0, 0, 0),
('8900', 56, 0, 0, 0, 0),
('8900', 57, 0, 0, 0, 0),
('8900', 58, 0, 0, 0, 0),
('8900', 59, 0, 0, 0, 0),
('9100', -15, 0, 0, 0, 0),
('9100', -14, 0, 0, 0, 0),
('9100', -13, 0, 0, 0, 0),
('9100', -12, 0, 0, 0, 0),
('9100', -11, 0, 0, 0, 0),
('9100', -10, 0, 0, 0, 0),
('9100', -9, 0, 0, 0, 0),
('9100', -8, 0, 0, 0, 0),
('9100', -7, 0, 0, 0, 0),
('9100', -6, 0, 0, 0, 0),
('9100', -5, 0, 0, 0, 0),
('9100', -4, 0, 0, 0, 0),
('9100', -3, 0, 0, 0, 0),
('9100', -2, 0, 0, 0, 0),
('9100', -1, 0, 0, 0, 0),
('9100', 0, 0, 0, 0, 0),
('9100', 1, 0, 0, 0, 0),
('9100', 2, 0, 0, 0, 0),
('9100', 3, 0, 0, 0, 0),
('9100', 4, 0, 0, 0, 0),
('9100', 5, 0, 0, 0, 0),
('9100', 6, 0, 0, 0, 0),
('9100', 7, 0, 0, 0, 0),
('9100', 8, 0, 0, 0, 0),
('9100', 9, 0, 0, 0, 0),
('9100', 10, 0, 0, 0, 0),
('9100', 11, 0, 0, 0, 0),
('9100', 12, 0, 0, 0, 0),
('9100', 13, 0, 0, 0, 0),
('9100', 14, 0, 0, 0, 0),
('9100', 15, 0, 0, 0, 0),
('9100', 16, 0, 0, 0, 0),
('9100', 17, 0, 0, 0, 0),
('9100', 18, 0, 0, 0, 0),
('9100', 19, 0, 0, 0, 0),
('9100', 20, 0, 0, 0, 0),
('9100', 21, 0, 0, 0, 0),
('9100', 22, 0, 0, 0, 0),
('9100', 23, 0, 0, 0, 0),
('9100', 24, 0, 0, 0, 0),
('9100', 25, 0, 0, 0, 0),
('9100', 26, 0, 0, 0, 0),
('9100', 27, 0, 0, 0, 0),
('9100', 28, 0, 0, 0, 0),
('9100', 29, 0, 0, 0, 0),
('9100', 30, 0, 0, 0, 0),
('9100', 31, 0, 0, 0, 0),
('9100', 32, 0, 0, 0, 0),
('9100', 33, 0, 0, 0, 0),
('9100', 34, 0, 0, 0, 0),
('9100', 35, 0, 0, 0, 0),
('9100', 36, 0, 0, 0, 0),
('9100', 37, 0, 0, 0, 0),
('9100', 38, 0, 0, 0, 0),
('9100', 39, 0, 0, 0, 0),
('9100', 40, 0, 0, 0, 0),
('9100', 41, 0, 0, 0, 0),
('9100', 42, 0, 0, 0, 0),
('9100', 43, 0, 0, 0, 0),
('9100', 44, 0, 0, 0, 0),
('9100', 45, 0, 0, 0, 0),
('9100', 46, 0, 0, 0, 0),
('9100', 47, 0, 0, 0, 0),
('9100', 48, 0, 0, 0, 0),
('9100', 49, 0, 0, 0, 0),
('9100', 50, 0, 0, 0, 0),
('9100', 51, 0, 0, 0, 0),
('9100', 52, 0, 0, 0, 0),
('9100', 53, 0, 0, 0, 0),
('9100', 54, 0, 0, 0, 0),
('9100', 55, 0, 0, 0, 0),
('9100', 56, 0, 0, 0, 0),
('9100', 57, 0, 0, 0, 0),
('9100', 58, 0, 0, 0, 0),
('9100', 59, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `chartmaster`
--

CREATE TABLE `chartmaster` (
  `accountcode` varchar(20) NOT NULL DEFAULT '0',
  `accountname` char(50) NOT NULL DEFAULT '',
  `group_` char(30) NOT NULL DEFAULT '',
  `cashflowsactivity` tinyint(1) NOT NULL DEFAULT '-1' COMMENT 'Cash flows activity'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `chartmaster`
--

INSERT INTO `chartmaster` (`accountcode`, `accountname`, `group_`, `cashflowsactivity`) VALUES
('1', 'Default Sales/Discounts', 'Sales', -1),
('1010', 'Petty Cash', 'Current Assets', -1),
('1020', 'Cash on Hand', 'Current Assets', -1),
('1030', 'Current  Accounts', 'Current Assets', -1),
('1040', 'Savings Accounts', 'Current Assets', -1),
('1050', 'Payroll Accounts', 'Current Assets', -1),
('1060', 'Special Accounts', 'Current Assets', -1),
('1070', 'Money Market Investments', 'Current Assets', -1),
('1080', 'Short-Term Investments (< 90 days)', 'Current Assets', -1),
('1090', 'Interest Receivable', 'Current Assets', -1),
('1100', 'Accounts Receivable', 'Current Assets', -1),
('1150', 'Allowance for Doubtful Accounts', 'Current Assets', -1),
('1200', 'Notes Receivable', 'Current Assets', -1),
('1250', 'Income Tax Receivable', 'Current Assets', -1),
('1300', 'Prepaid Expenses', 'Current Assets', -1),
('1350', 'Advances', 'Current Assets', -1),
('1400', 'Supplies Inventory', 'Current Assets', -1),
('1420', 'Raw Material Inventory', 'Current Assets', -1),
('1440', 'Work in Progress Inventory', 'Current Assets', -1),
('1460', 'Finished Goods Inventory', 'Current Assets', -1),
('1500', 'Land', 'Fixed Assets', -1),
('1550', 'Bonds', 'Fixed Assets', -1),
('1600', 'Buildings', 'Fixed Assets', -1),
('1620', 'Accumulated Depreciation of Buildings', 'Fixed Assets', -1),
('1650', 'Equipment', 'Fixed Assets', -1),
('1670', 'Accumulated Depreciation of Equipment', 'Fixed Assets', -1),
('1700', 'Furniture & Fixtures', 'Fixed Assets', -1),
('1710', 'Accumulated Depreciation of Furniture & Fixtures', 'Fixed Assets', -1),
('1720', 'Office Equipment', 'Fixed Assets', -1),
('1730', 'Accumulated Depreciation of Office Equipment', 'Fixed Assets', -1),
('1740', 'Software', 'Fixed Assets', -1),
('1750', 'Accumulated Depreciation of Software', 'Fixed Assets', -1),
('1760', 'Vehicles', 'Fixed Assets', -1),
('1770', 'Accumulated Depreciation Vehicles', 'Fixed Assets', -1),
('1780', 'Other Depreciable Property', 'Fixed Assets', -1),
('1790', 'Accumulated Depreciation of Other Depreciable Prop', 'Fixed Assets', -1),
('1800', 'Patents', 'Fixed Assets', -1),
('1850', 'Goodwill', 'Fixed Assets', -1),
('1900', 'Future Income Tax Receivable', 'Current Assets', -1),
('2010', 'Bank Indedebtedness (overdraft)', 'Liabilities', -1),
('2020', 'Retainers or Advances on Work', 'Liabilities', -1),
('2050', 'Interest Payable', 'Liabilities', -1),
('2100', 'Accounts Payable', 'Liabilities', -1),
('2150', 'Goods Received Suspense', 'Liabilities', -1),
('2200', 'Short-Term Loan Payable', 'Liabilities', -1),
('2230', 'Current Portion of Long-Term Debt Payable', 'Liabilities', -1),
('2250', 'Income Tax Payable', 'Liabilities', -1),
('2300', 'GST Payable', 'Liabilities', -1),
('2310', 'GST Recoverable', 'Liabilities', -1),
('2320', 'TDS Payable', 'Liabilities', -1),
('2330', 'TDS Recoverable', 'Liabilities', -1),
('2340', 'Payroll Tax Payable', 'Liabilities', -1),
('2360', 'Other Taxes Payable', 'Liabilities', -1),
('2400', 'Employee Salaries Payable', 'Liabilities', -1),
('2410', 'Management Salaries Payable', 'Liabilities', -1),
('2420', 'Director / Partner Fees Payable', 'Liabilities', -1),
('2450', 'Health Benefits Payable', 'Liabilities', -1),
('2460', 'Pension Benefits Payable', 'Liabilities', -1),
('2480', 'Employment Insurance Premiums Payable', 'Liabilities', -1),
('2500', 'Land Payable', 'Liabilities', -1),
('2550', 'Long-Term Bank Loan', 'Liabilities', -1),
('2560', 'Notes Payable', 'Liabilities', -1),
('2600', 'Building & Equipment Payable', 'Liabilities', -1),
('2700', 'Furnishing & Fixture Payable', 'Liabilities', -1),
('2720', 'Office Equipment Payable', 'Liabilities', -1),
('2740', 'Vehicle Payable', 'Liabilities', -1),
('2760', 'Other Property Payable', 'Liabilities', -1),
('2800', 'Shareholder Loans', 'Liabilities', -1),
('2900', 'Suspense', 'Liabilities', -1),
('3100', 'Capital Stock', 'Financed', -1),
('3200', 'Capital Surplus / Dividends', 'Financed', -1),
('3300', 'Dividend Taxes Payable', 'Financed', -1),
('3400', 'Dividend Taxes Refundable', 'Financed', -1),
('3500', 'Retained Earnings', 'Financed', -1),
('4100', 'Product / Service Sales', 'Revenue', -1),
('4200', 'Sales Exchange Gains/Losses', 'Revenue', -1),
('4500', 'Consulting Services', 'Revenue', -1),
('4600', 'Rentals', 'Revenue', -1),
('4700', 'Finance Charge Income', 'Revenue', -1),
('4800', 'Sales Returns & Allowances', 'Revenue', -1),
('4900', 'Sales Discounts', 'Revenue', -1),
('5000', 'Cost of Sales', 'Cost of Goods Sold', -1),
('5100', 'Production Expenses', 'Cost of Goods Sold', -1),
('5200', 'Purchases Exchange Gains/Losses', 'Cost of Goods Sold', -1),
('5500', 'Direct Labour Costs', 'Cost of Goods Sold', -1),
('5600', 'Freight Charges', 'Outward Freight', -1),
('5700', 'Inventory Adjustment', 'Cost of Goods Sold', -1),
('5800', 'Purchase Returns & Allowances', 'Cost of Goods Sold', -1),
('5900', 'Purchase Discounts', 'Cost of Goods Sold', -1),
('6100', 'Advertising', 'Marketing Expenses', -1),
('6150', 'Promotion', 'Promotions', -1),
('6200', 'Communications', 'Marketing Expenses', -1),
('6250', 'Meeting Expenses', 'Marketing Expenses', -1),
('6300', 'Travelling Expenses', 'Marketing Expenses', -1),
('6400', 'Delivery Expenses', 'Marketing Expenses', -1),
('6500', 'Sales Salaries & Commission', 'Marketing Expenses', -1),
('6550', 'Sales Salaries & Commission Deductions', 'Marketing Expenses', -1),
('6590', 'Benefits', 'Marketing Expenses', -1),
('6600', 'Other Selling Expenses', 'Marketing Expenses', -1),
('6700', 'Permits, Licenses & License Fees', 'Marketing Expenses', -1),
('6800', 'Research & Development', 'Marketing Expenses', -1),
('6900', 'Professional Services', 'Marketing Expenses', -1),
('7020', 'Support Salaries & Wages', 'Operating Expenses', -1),
('7030', 'Support Salary & Wage Deductions', 'Operating Expenses', -1),
('7040', 'Management Salaries', 'Operating Expenses', -1),
('7050', 'Management Salary deductions', 'Operating Expenses', -1),
('7060', 'Director / Partner Fees', 'Operating Expenses', -1),
('7070', 'Director / Partner Deductions', 'Operating Expenses', -1),
('7080', 'Payroll Tax', 'Operating Expenses', -1),
('7090', 'Benefits', 'Operating Expenses', -1),
('7100', 'Training & Education Expenses', 'Operating Expenses', -1),
('7150', 'Dues & Subscriptions', 'Operating Expenses', -1),
('7200', 'Accounting Fees', 'Operating Expenses', -1),
('7210', 'Audit Fees', 'Operating Expenses', -1),
('7220', 'Banking Fees', 'Operating Expenses', -1),
('7230', 'Credit Card Fees', 'Operating Expenses', -1),
('7240', 'Consulting Fees', 'Operating Expenses', -1),
('7260', 'Legal Fees', 'Operating Expenses', -1),
('7280', 'Other Professional Fees', 'Operating Expenses', -1),
('7300', 'Business Tax', 'Operating Expenses', -1),
('7350', 'Property Tax', 'Operating Expenses', -1),
('7390', 'Corporation Capital Tax', 'Operating Expenses', -1),
('7400', 'Office Rent', 'Operating Expenses', -1),
('7450', 'Equipment Rental', 'Operating Expenses', -1),
('7500', 'Office Supplies', 'Operating Expenses', -1),
('7550', 'Office Repair & Maintenance', 'Operating Expenses', -1),
('7600', 'Automotive Expenses', 'Operating Expenses', -1),
('7610', 'Communication Expenses', 'Operating Expenses', -1),
('7620', 'Insurance Expenses', 'Operating Expenses', -1),
('7630', 'Postage & Courier Expenses', 'Operating Expenses', -1),
('7640', 'Miscellaneous Expenses', 'Operating Expenses', -1),
('7650', 'Travel Expenses', 'Operating Expenses', -1),
('7660', 'Utilities', 'Operating Expenses', -1),
('7700', 'Ammortization Expenses', 'Operating Expenses', -1),
('7750', 'Depreciation Expenses', 'Operating Expenses', -1),
('7800', 'Interest Expense', 'Operating Expenses', -1),
('7900', 'Bad Debt Expense', 'Operating Expenses', -1),
('8100', 'Gain on Sale of Assets', 'Other Revenue and Expenses', -1),
('8200', 'Interest Income', 'Other Revenue and Expenses', -1),
('8300', 'Recovery on Bad Debt', 'Other Revenue and Expenses', -1),
('8400', 'Other Revenue', 'Other Revenue and Expenses', -1),
('8500', 'Loss on Sale of Assets', 'Other Revenue and Expenses', -1),
('8600', 'Charitable Contributions', 'Other Revenue and Expenses', -1),
('8900', 'Other Expenses', 'Other Revenue and Expenses', -1),
('9100', 'Income Tax Provision', 'Income Tax', -1);

-- --------------------------------------------------------

--
-- Table structure for table `cogsglpostings`
--

CREATE TABLE `cogsglpostings` (
  `id` int(11) NOT NULL,
  `area` char(3) NOT NULL DEFAULT '',
  `stkcat` varchar(6) NOT NULL DEFAULT '',
  `glcode` varchar(20) NOT NULL DEFAULT '0',
  `salestype` char(2) NOT NULL DEFAULT 'AN'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cogsglpostings`
--

INSERT INTO `cogsglpostings` (`id`, `area`, `stkcat`, `glcode`, `salestype`) VALUES
(5, '1', '2', '5000', '3'),
(6, '1', '2', '6100', '2');

-- --------------------------------------------------------

--
-- Table structure for table `companies`
--

CREATE TABLE `companies` (
  `coycode` int(11) NOT NULL DEFAULT '1',
  `coyname` varchar(50) NOT NULL DEFAULT '',
  `gstno` varchar(20) NOT NULL DEFAULT '',
  `companynumber` varchar(20) NOT NULL DEFAULT '0',
  `regoffice1` varchar(40) NOT NULL DEFAULT '',
  `regoffice2` varchar(40) NOT NULL DEFAULT '',
  `regoffice3` varchar(40) NOT NULL DEFAULT '',
  `regoffice4` varchar(40) NOT NULL DEFAULT '',
  `regoffice5` varchar(20) NOT NULL DEFAULT '',
  `regoffice6` varchar(15) NOT NULL DEFAULT '',
  `telephone` varchar(25) NOT NULL DEFAULT '',
  `fax` varchar(25) NOT NULL DEFAULT '',
  `email` varchar(55) NOT NULL DEFAULT '',
  `currencydefault` varchar(4) NOT NULL DEFAULT '',
  `debtorsact` varchar(20) NOT NULL DEFAULT '70000',
  `pytdiscountact` varchar(20) NOT NULL DEFAULT '55000',
  `creditorsact` varchar(20) NOT NULL DEFAULT '80000',
  `payrollact` varchar(20) NOT NULL DEFAULT '84000',
  `grnact` varchar(20) NOT NULL DEFAULT '72000',
  `exchangediffact` varchar(20) NOT NULL DEFAULT '65000',
  `purchasesexchangediffact` varchar(20) NOT NULL DEFAULT '0',
  `retainedearnings` varchar(20) NOT NULL DEFAULT '90000',
  `gllink_debtors` tinyint(1) DEFAULT '1',
  `gllink_creditors` tinyint(1) DEFAULT '1',
  `gllink_stock` tinyint(1) DEFAULT '1',
  `freightact` varchar(20) NOT NULL DEFAULT '0',
  `witholdingtaxexempted` tinyint(1) NOT NULL DEFAULT '1',
  `witholdingtaxglaccount` varchar(20) DEFAULT NULL,
  `supplier_returns_location` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `companies`
--

INSERT INTO `companies` (`coycode`, `coyname`, `gstno`, `companynumber`, `regoffice1`, `regoffice2`, `regoffice3`, `regoffice4`, `regoffice5`, `regoffice6`, `telephone`, `fax`, `email`, `currencydefault`, `debtorsact`, `pytdiscountact`, `creditorsact`, `payrollact`, `grnact`, `exchangediffact`, `purchasesexchangediffact`, `retainedearnings`, `gllink_debtors`, `gllink_creditors`, `gllink_stock`, `freightact`, `witholdingtaxexempted`, `witholdingtaxglaccount`, `supplier_returns_location`) VALUES
(1, 'Netelity Websolutions Pvt.Ltd', '29SSECN4476Q1ZQ', 'U72333KA5553PTC07449', '#1005, 3rd A main', 'E block, Subramanya Nagar', 'Bengaluru - 560010', 'Karnataka', '', '', '8213452789', '', 'nERP@nERPdemo.com', 'INR', '1100', '4900', '2100', '2400', '2150', '2320', '5200', '3500', 1, 1, 1, '5600', 0, '2330', 'BAN');

-- --------------------------------------------------------

--
-- Table structure for table `config`
--

CREATE TABLE `config` (
  `confname` varchar(35) NOT NULL DEFAULT '',
  `confvalue` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `config`
--

INSERT INTO `config` (`confname`, `confvalue`) VALUES
('AllowOrderLineItemNarrative', '1'),
('AllowSalesOfZeroCostItems', '0'),
('AutoAuthorisePO', '1'),
('AutoCreateWOs', '1'),
('AutoDebtorNo', '1'),
('AutoIssue', '1'),
('AutoSupplierNo', '1'),
('CheckCreditLimits', '1'),
('Check_Price_Charged_vs_Order_Price', '1'),
('Check_Qty_Charged_vs_Del_Qty', '1'),
('CountryOfOperation', 'IN'),
('CreditingControlledItems_MustExist', '1'),
('DB_Maintenance', '0'),
('DB_Maintenance_LastRun', '2015-08-14'),
('DefaultBlindPackNote', '1'),
('DefaultCreditLimit', '100000'),
('DefaultCustomerType', '1'),
('DefaultDateFormat', 'd/m/Y'),
('DefaultDisplayRecordsMax', '50'),
('DefaultFactoryLocation', 'BAN'),
('DefaultPriceList', 'De'),
('DefaultSupplierType', '1'),
('DefaultTaxCategory', '1'),
('Default_Shipper', '1'),
('DefineControlledOnWOEntry', '0'),
('DispatchCutOffTime', '17'),
('DoFreightCalc', '0'),
('EDIHeaderMsgId', 'D:01B:UN:EAN010'),
('EDIReference', 'nERP'),
('EDI_Incoming_Orders', 'companies/netellcm_business/EDI_Incoming_Orders'),
('EDI_MsgPending', 'companies/netellcm_business/EDI_Pending'),
('EDI_MsgSent', 'companies/netellcm_business/EDI_Sent'),
('ExchangeRateFeed', 'ECB'),
('Extended_CustomerInfo', '1'),
('Extended_SupplierInfo', '1'),
('FactoryManagerEmail', ''),
('FreightChargeAppliesIfLessThan', '0'),
('FreightTaxCategory', '1'),
('FrequentlyOrderedItems', '10'),
('geocode_integration', '0'),
('GoogleTranslatorAPIKey', ''),
('HTTPS_Only', '0'),
('InventoryManagerEmail', ''),
('InvoicePortraitFormat', '1'),
('InvoiceQuantityDefault', '1'),
('ItemDescriptionLanguages', '0,'),
('LogPath', 'companies/netellcm_business/'),
('LogSeverity', '3'),
('MaxImageSize', '300'),
('MaxSerialItemsIssued', '50'),
('MonthsAuditTrail', '1'),
('NumberOfMonthMustBeShown', '6'),
('NumberOfPeriodsOfStockUsage', '12'),
('OverChargeProportion', '0'),
('OverReceiveProportion', '0'),
('PackNoteFormat', '1'),
('PageLength', '48'),
('part_pics_dir', 'companies/netelity/part_pics'),
('PastDueDays1', '30'),
('PastDueDays2', '60'),
('PO_AllowSameItemMultipleTimes', '1'),
('ProhibitJournalsToControlAccounts', '1'),
('ProhibitNegativeStock', '1'),
('ProhibitPostingsBefore', '2017-04-30'),
('PurchasingManagerEmail', ''),
('QualityCOAText', ''),
('QualityLogSamples', '1'),
('QualityProdSpecText', ''),
('QuickEntries', '5'),
('RadioBeaconFileCounter', '/home/RadioBeacon/FileCounter'),
('RadioBeaconFTP_user_name', 'RadioBeacon ftp server user name'),
('RadioBeaconHomeDir', '/home/RadioBeacon'),
('RadioBeaconStockLocation', 'BL'),
('RadioBraconFTP_server', '192.168.2.2'),
('RadioBreaconFilePrefix', 'ORDXX'),
('RadionBeaconFTP_user_pass', 'Radio Beacon remote ftp server password'),
('reports_dir', 'companies/netelity/reports'),
('RequirePickingNote', '0'),
('RomalpaClause', 'Ownership will not pass to the buyer until the goods have been paid for in full.'),
('ShopAboutUs', 'This web-shop software has been developed by Logic Works Ltd for nERP. For support contact Phil Daintree by rn<a href=\\\"mailto:support@logicworks.co.nz\\\">email</a>rn'),
('ShopAllowBankTransfer', '1'),
('ShopAllowCreditCards', '1'),
('ShopAllowPayPal', '1'),
('ShopAllowSurcharges', '1'),
('ShopBankTransferSurcharge', '0.0'),
('ShopBranchCode', 'ANGRY'),
('ShopContactUs', 'For support contact Logic Works Ltd by rn<a href=\\\"mailto:support@logicworks.co.nz\\\">email</a>'),
('ShopCreditCardBankAccount', '1030'),
('ShopCreditCardGateway', 'SwipeHQ'),
('ShopCreditCardSurcharge', '2.95'),
('ShopDebtorNo', 'ANGRY'),
('ShopFreightMethod', 'NoFreight'),
('ShopFreightPolicy', 'Shipping information'),
('ShopManagerEmail', 'shopmanager@yourdomain.com'),
('ShopMode', 'test'),
('ShopName', 'nERP Demo Store'),
('ShopPayFlowMerchant', ''),
('ShopPayFlowPassword', ''),
('ShopPayFlowUser', ''),
('ShopPayFlowVendor', ''),
('ShopPayPalBankAccount', '1040'),
('ShopPaypalCommissionAccount', '1'),
('ShopPayPalPassword', ''),
('ShopPayPalProPassword', ''),
('ShopPayPalProSignature', ''),
('ShopPayPalProUser', ''),
('ShopPayPalSignature', ''),
('ShopPayPalSurcharge', '3.4'),
('ShopPayPalUser', ''),
('ShopPrivacyStatement', '<h2>We are committed to protecting your privacy.</h2><p>We recognise that your personal information is confidential and we understand that it is important for you to know how we treat your personal information. Please read on for more information about our Privacy Policy.</p><ul><li><h2>1. What information do we collect and how do we use it?</h2><br />We use the information it collects from you for the following purposes:<ul><li>To assist us in providing you with a quality service</li><li>To respond to, and process, your request</li><li>To notify competition winners or fulfil promotional obligations</li><li>To inform you of, and provide you with, new and existing products and services offered by us from time to time </li></ul><p>Any information we collect will not be used in ways that you have not consented to.</p><p>If you send us an email, we will store your email address and the contents of the email. This information will only be used for the purpose for which you have provided it. Electronic mail submitted to us is handled and saved according to the provisions of the the relevant statues.</p><p>When we offer contests and promotions, customers who choose to enter are asked to provide personal information. This information may then be used by us to notify winners, or to fulfil promotional obligations.</p><p>We may use the information we collect to occasionally notify you about important functionality changes to our website, new and special offers we think you will find valuable. If at any stage you no longer wish to receive these notifications you may opt out by sending us an email.</p><p>We do monitor this website in order to identify user trends and to improve the site if necessary. Any of this information, such as the type of site browser your computer has, will be used only in aggregate form and your individual details will not be identified.</p></li><li><h2>2. How do we store and protect your personal information and who has access to that information?</h2><p>As required by statute, we follow strict procedures when storing and using the information you have provided.</p><p>We do not sell, trade or rent your personal information to others. We may provide aggregate statistics about our customers and website trends. However, these statistics will not have any personal information which would identify you.</p><p>Only specific employees within our company are able to access your personal data.</p><p>This policy means that we may require proof of identity before we disclose any information to you.</p></li><li><h2>3. What should I do if I want to change my details or if I donÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬ÃƒÂ¢Ã¢â‚¬Å¾Ã‚Â¢t want to be contacted any more?</h2><p>At any stage you have the right to access and amend or update your personal details. If you do not want to receive any communications from us you may opt out by contacting us see <a href=\\\"index.php?Page=ContactUs\\\">the Contact Us Page</a></p></li><li><h2>4. What happens if we decide to change this Privacy Policy?</h2><p>If we change any aspect of our Privacy Policy we will post these changes on this page so that you are always aware of how we are treating your personal information.</p></li><li><h2>5. How can you contact us if you have any questions, comments or concerns about our Privacy Policy?</h2><p>We welcome any questions or comments you may have please email us via the contact details provided on our <a href=\\\"index.php?Page=ContactUs\\\">Contact Us Page</a></p></li></ul><p>Please also refer to our <a href=\\\"index.php?Page=TermsAndConditions\\\">Terms and Conditions</a> for more information.</p>'),
('ShopShowOnlyAvailableItems', '0'),
('ShopShowQOHColumn', '1'),
('ShopStockLocations', 'MEL,TOR'),
('ShopSurchargeStockID', 'PAYTSURCHARGE'),
('ShopSwipeHQAPIKey', ''),
('ShopSwipeHQMerchantID', ''),
('ShopTermsConditions', '<p>These terms cover the use of this website. Use includes visits to our sites, purchases on our sites, participation in our database and promotions. These terms of use apply to you when you use our websites. Please read these terms carefully - if you need to refer to them again they can be accessed from the link at the bottom of any page of our websites.</p><br /><ul><li><h2>1. Content</h2><p>While we endeavour to supply accurate information on this site, errors and omissions may occur. We do not accept any liability, direct or indirect, for any loss or damage which may directly or indirectly result from any advice, opinion, information, representation or omission whether negligent or otherwise, contained on this site. You are solely responsible for the actions you take in reliance on the content on, or accessed, through this site.</p><p>We reserve the right to make changes to the content on this site at any time and without notice.</p><p>To the extent permitted by law, we make no warranties in relation to the merchantability, fitness for purpose, freedom from computer virus, accuracy or availability of this web site or any other web site.</p></li><li><h2>2. Making a contract with us</h2><p>When you place an order with us, you are making an offer to buy goods. We will send you an e-mail to confirm that we have received and accepted your order, which indicates that a contract has been made between us. We will take payment from you when we accept your order. In the unlikely event that the goods are no longer available, we will refund your payment to the account it originated from, and advise that the goods are no longer available.</p><p>An order is placed on our website via adding a product to the shopping cart and proceeding through our checkout process. The checkout process includes giving us delivery and any other relevant details for your order, entering payment information and submitting your order. The final step consists of a confirmation page with full details of your order, which you are able to print as a receipt of your order. We will also email you with confirmation of your order.</p><p>We reserve the right to refuse or cancel any orders that we believe, solely by our own judgement, to be placed for commercial purposes, e.g. any kind of reseller. We also reserve the right to refuse or cancel any orders that we believe, solely by our own judgement, to have been placed fraudulently.</p><p>We reserve the right to limit the number of an item customers can purchase in a single transaction.</p></li><li><h2>3. Payment options</h2><p>We currently accept the following credit cards:</p><ul><li>Visa</li><li>MasterCard</li><li>American Express</li></ul>You can also pay using PayPal and internet bank transfer. Surcharges may apply for payment by PayPal or credit cards.</p></li><li><h2>4. Pricing</h2><p>All prices listed are inclusive of relevant taxes.  All prices are correct when published. Please note that we reserve the right to alter prices at any time for any reason. If this should happen after you have ordered a product, we will contact you prior to processing your order. Online and in store pricing may differ.</p></li><li><h2>5. Website and Credit Card Security</h2><p>We want you to have a safe and secure shopping experience online. All payments via our sites are processed using SSL (Secure Socket Layer) protocol, whereby sensitive information is encrypted to protect your privacy.</p><p>You can help to protect your details from unauthorised access by logging out each time you finish using the site, particularly if you are doing so from a public or shared computer.</p><p>For security purposes certain transactions may require proof of identification.</p></li><li><h2>6. Delivery and Delivery Charges</h2><p>We do not deliver to Post Office boxes.</p><p>Please note that a signature is required for all deliveries. The goods become the recipientÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬ÃƒÂ¢Ã¢â‚¬Å¾Ã‚Â¢s property and responsibility once they have been signed for at the time of delivery. If goods are lost or damaged in transit, please contact us within 7 business days <a href=\\\"index.php?Page=ContactUs\\\">see Contact Us page for contact details</a>. We will use this delivery information to make a claim against our courier company. We will offer you the choice of a replacement or a full refund, once we have received confirmation from our courier company that delivery was not successful.</p></li><li><h2>7. Restricted Products</h2><p>Some products on our site carry an age restriction, if a product you have selected is R16 or R18 a message will appear in the cart asking you to confirm you are an appropriate age to purchase the item(s).  Confirming this means that you are of an eligible age to purchase the selected product(s).  You are also agreeing that you are not purchasing the item on behalf of a person who is not the appropriate age.</p></li><li><h2>8. Delivery Period</h2><p>Delivery lead time for products may vary. Deliveries to rural addresses may take longer.  You will receive an email that confirms that your order has been dispatched.</p><p>To ensure successful delivery, please provide a delivery address where someone will be present during business hours to sign for the receipt of your package. You can track your order by entering the tracking number emailed to you in the dispatch email at the Courier\\\'s web-site.</p></li><li><h2>9. Disclaimer</h2><p>Our websites are intended to provide information for people shopping our products and accessing our services, including making purchases via our website and registering on our database to receive e-mails from us.</p><p>While we endeavour to supply accurate information on this site, errors and omissions may occur. We do not accept any liability, direct or indirect, for any loss or damage which may directly or indirectly result from any advice, opinion, information, representation or omission whether negligent or otherwise, contained on this site. You are solely responsible for the actions you take in reliance on the content on, or accessed, through this site.</p><p>We reserve the right to make changes to the content on this site at any time and without notice.</p><p>To the extent permitted by law, we make no warranties in relation to the merchantability, fitness for purpose, freedom from computer virus, accuracy or availability of this web site or any other web site.</p></li><li><h2>10. Links</h2><p>Please note that although this site has some hyperlinks to other third party websites, these sites have not been prepared by us are not under our control. The links are only provided as a convenience, and do not imply that we endorse, check, or approve of the third party site. We are not responsible for the privacy principles or content of these third party sites. We are not responsible for the availability of any of these links.</p></li><li><h2>11. Jurisdiction</h2><p>This website is governed by, and is to be interpreted in accordance with, the laws of  ????.</p></li><li><h2>12. Changes to this Agreement</h2><p>We reserve the right to alter, modify or update these terms of use. These terms apply to your order. We may change our terms and conditions at any time, so please do not assume that the same terms will apply to future orders.</p></li></ul>'),
('ShopTitle', 'Shop Home'),
('ShowStockidOnImages', '0'),
('ShowValueOnGRN', '0'),
('Show_Settled_LastMonth', '1'),
('SmtpSetting', '0'),
('SO_AllowSameItemMultipleTimes', '1'),
('StandardCostDecimalPlaces', '2'),
('TaxAuthorityReferenceName', 'GSTIN'),
('UpdateCurrencyRatesDaily', '2018-10-01'),
('VersionNumber', '4.15'),
('WeightedAverageCosting', '1'),
('WikiApp', 'DokuWiki'),
('WikiPath', 'wiki'),
('WorkingDaysWeek', '6'),
('YearEnd', '3');

-- --------------------------------------------------------

--
-- Table structure for table `contractbom`
--

CREATE TABLE `contractbom` (
  `contractref` varchar(20) NOT NULL DEFAULT '0',
  `stockid` varchar(20) NOT NULL DEFAULT '',
  `workcentreadded` char(5) NOT NULL DEFAULT '',
  `quantity` double NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `contractcharges`
--

CREATE TABLE `contractcharges` (
  `id` int(11) NOT NULL,
  `contractref` varchar(20) NOT NULL,
  `transtype` smallint(6) NOT NULL DEFAULT '20',
  `transno` int(11) NOT NULL DEFAULT '0',
  `amount` double NOT NULL DEFAULT '0',
  `narrative` text NOT NULL,
  `anticipated` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `contractreqts`
--

CREATE TABLE `contractreqts` (
  `contractreqid` int(11) NOT NULL,
  `contractref` varchar(20) NOT NULL DEFAULT '0',
  `requirement` varchar(40) NOT NULL DEFAULT '',
  `quantity` double NOT NULL DEFAULT '1',
  `costperunit` double NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `contracts`
--

CREATE TABLE `contracts` (
  `contractref` varchar(20) NOT NULL DEFAULT '',
  `contractdescription` text NOT NULL,
  `debtorno` varchar(10) NOT NULL DEFAULT '',
  `branchcode` varchar(10) NOT NULL DEFAULT '',
  `loccode` varchar(5) NOT NULL DEFAULT '',
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `categoryid` varchar(6) NOT NULL DEFAULT '',
  `orderno` int(11) NOT NULL DEFAULT '0',
  `customerref` varchar(20) NOT NULL DEFAULT '',
  `margin` double NOT NULL DEFAULT '1',
  `wo` int(11) NOT NULL DEFAULT '0',
  `requireddate` date NOT NULL DEFAULT '0000-00-00',
  `drawing` varchar(50) NOT NULL DEFAULT '',
  `exrate` double NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `currencies`
--

CREATE TABLE `currencies` (
  `currency` char(20) NOT NULL DEFAULT '',
  `currabrev` char(3) NOT NULL DEFAULT '',
  `country` char(50) NOT NULL DEFAULT '',
  `hundredsname` char(15) NOT NULL DEFAULT 'Cents',
  `decimalplaces` tinyint(3) NOT NULL DEFAULT '2',
  `rate` double NOT NULL DEFAULT '1',
  `webcart` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'If 1 shown in nERP cart. if 0 no show'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `currencies`
--

INSERT INTO `currencies` (`currency`, `currabrev`, `country`, `hundredsname`, `decimalplaces`, `rate`, `webcart`) VALUES
('Indian rupee', 'INR', 'India', 'paisa', 2, 1, 0),
('US Dollars', 'USD', 'United States', 'Cents', 2, 0.01379474712808, 0);

-- --------------------------------------------------------

--
-- Table structure for table `custallocns`
--

CREATE TABLE `custallocns` (
  `id` int(11) NOT NULL,
  `amt` decimal(20,4) NOT NULL DEFAULT '0.0000',
  `datealloc` date NOT NULL DEFAULT '0000-00-00',
  `transid_allocfrom` int(11) NOT NULL DEFAULT '0',
  `transid_allocto` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `custbranch`
--

CREATE TABLE `custbranch` (
  `branchcode` varchar(10) NOT NULL DEFAULT '',
  `debtorno` varchar(10) NOT NULL DEFAULT '',
  `brname` varchar(40) NOT NULL DEFAULT '',
  `braddress1` varchar(40) NOT NULL DEFAULT '',
  `braddress2` varchar(40) NOT NULL DEFAULT '',
  `braddress3` varchar(40) NOT NULL DEFAULT '',
  `braddress4` varchar(50) NOT NULL DEFAULT '',
  `braddress5` varchar(20) NOT NULL DEFAULT '',
  `braddress6` varchar(40) NOT NULL DEFAULT '',
  `lat` float(12,8) NOT NULL DEFAULT '0.00000000',
  `lng` float(12,8) NOT NULL DEFAULT '0.00000000',
  `estdeliverydays` smallint(6) NOT NULL DEFAULT '1',
  `area` char(3) NOT NULL,
  `salesman` varchar(4) NOT NULL DEFAULT '',
  `fwddate` smallint(6) NOT NULL DEFAULT '0',
  `phoneno` varchar(20) NOT NULL DEFAULT '',
  `faxno` varchar(20) NOT NULL DEFAULT '',
  `contactname` varchar(30) NOT NULL DEFAULT '',
  `email` varchar(55) NOT NULL DEFAULT '',
  `defaultlocation` varchar(5) NOT NULL DEFAULT '',
  `taxgroupid` tinyint(4) NOT NULL DEFAULT '1',
  `defaultshipvia` int(11) NOT NULL DEFAULT '1',
  `deliverblind` tinyint(1) DEFAULT '1',
  `disabletrans` tinyint(4) NOT NULL DEFAULT '0',
  `brpostaddr1` varchar(40) NOT NULL DEFAULT '',
  `brpostaddr2` varchar(40) NOT NULL DEFAULT '',
  `brpostaddr3` varchar(40) NOT NULL DEFAULT '',
  `brpostaddr4` varchar(50) NOT NULL DEFAULT '',
  `brpostaddr5` varchar(20) NOT NULL DEFAULT '',
  `brpostaddr6` varchar(40) NOT NULL DEFAULT '',
  `specialinstructions` text NOT NULL,
  `custbranchcode` varchar(30) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `custbranch`
--

INSERT INTO `custbranch` (`branchcode`, `debtorno`, `brname`, `braddress1`, `braddress2`, `braddress3`, `braddress4`, `braddress5`, `braddress6`, `lat`, `lng`, `estdeliverydays`, `area`, `salesman`, `fwddate`, `phoneno`, `faxno`, `contactname`, `email`, `defaultlocation`, `taxgroupid`, `defaultshipvia`, `deliverblind`, `disabletrans`, `brpostaddr1`, `brpostaddr2`, `brpostaddr3`, `brpostaddr4`, `brpostaddr5`, `brpostaddr6`, `specialinstructions`, `custbranchcode`) VALUES
('1', '1', 'AppleS', 'asdffa', 'sadfasdfa', '', '', '23423432', 'India', 0.00000000, 0.00000000, 15, '1', '1', 20, '3425342', '3425432', 'asdfadsf', 'admin@admin.com', 'BAN', 1, 1, 1, 0, '', '', '', '', '', '', '', ''),
('2', '1', 'AppleW', 'asfads', 'asdfads', 'asdfadsfads', '', '', 'India', 0.00000000, 0.00000000, 15, '4', '2', 0, '3425342', '3425432', 'asdfadsf', 'admin1@admin.com', 'MUM', 2, 1, 1, 0, '', '', '', '', '', '', 'Don&#039;t Deliver without PO', '');

-- --------------------------------------------------------

--
-- Table structure for table `custcontacts`
--

CREATE TABLE `custcontacts` (
  `contid` int(11) NOT NULL,
  `debtorno` varchar(10) NOT NULL,
  `contactname` varchar(40) NOT NULL,
  `role` varchar(40) NOT NULL,
  `phoneno` varchar(20) NOT NULL,
  `notes` varchar(255) NOT NULL,
  `email` varchar(55) NOT NULL,
  `statement` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `custitem`
--

CREATE TABLE `custitem` (
  `debtorno` char(10) NOT NULL DEFAULT '',
  `stockid` varchar(20) NOT NULL DEFAULT '',
  `cust_part` varchar(20) NOT NULL DEFAULT '',
  `cust_description` varchar(30) NOT NULL DEFAULT '',
  `customersuom` char(50) NOT NULL DEFAULT '',
  `conversionfactor` double NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `custnotes`
--

CREATE TABLE `custnotes` (
  `noteid` tinyint(4) NOT NULL,
  `debtorno` varchar(10) NOT NULL DEFAULT '0',
  `href` varchar(100) NOT NULL,
  `note` text NOT NULL,
  `date` date NOT NULL DEFAULT '0000-00-00',
  `priority` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `customerwitholdings`
--

CREATE TABLE `customerwitholdings` (
  `id` int(11) NOT NULL,
  `debtorno` varchar(10) NOT NULL,
  `debtortransid` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `witheldamount` decimal(10,2) NOT NULL,
  `certificate` varchar(200) DEFAULT NULL,
  `date_witheld` date DEFAULT NULL,
  `date_of_certificate` date DEFAULT NULL,
  `notes` text,
  `status` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `debtorsmaster`
--

CREATE TABLE `debtorsmaster` (
  `debtorno` varchar(10) NOT NULL DEFAULT '',
  `name` varchar(40) NOT NULL DEFAULT '',
  `address1` varchar(40) NOT NULL DEFAULT '',
  `address2` varchar(40) NOT NULL DEFAULT '',
  `address3` varchar(40) NOT NULL DEFAULT '',
  `address4` varchar(50) NOT NULL DEFAULT '',
  `address5` varchar(20) NOT NULL DEFAULT '',
  `address6` varchar(40) NOT NULL DEFAULT '',
  `currcode` char(3) NOT NULL DEFAULT '',
  `salestype` char(2) NOT NULL DEFAULT '',
  `clientsince` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `holdreason` smallint(6) NOT NULL DEFAULT '0',
  `paymentterms` char(2) NOT NULL DEFAULT 'f',
  `discount` double NOT NULL DEFAULT '0',
  `pymtdiscount` double NOT NULL DEFAULT '0',
  `lastpaid` double NOT NULL DEFAULT '0',
  `lastpaiddate` datetime DEFAULT NULL,
  `creditlimit` double NOT NULL DEFAULT '1000',
  `invaddrbranch` tinyint(4) NOT NULL DEFAULT '0',
  `discountcode` char(2) NOT NULL DEFAULT '',
  `ediinvoices` tinyint(4) NOT NULL DEFAULT '0',
  `ediorders` tinyint(4) NOT NULL DEFAULT '0',
  `edireference` varchar(20) NOT NULL DEFAULT '',
  `editransport` varchar(5) NOT NULL DEFAULT 'email',
  `ediaddress` varchar(50) NOT NULL DEFAULT '',
  `ediserveruser` varchar(20) NOT NULL DEFAULT '',
  `ediserverpwd` varchar(20) NOT NULL DEFAULT '',
  `taxref` varchar(20) NOT NULL DEFAULT '',
  `customerpoline` tinyint(1) NOT NULL DEFAULT '0',
  `typeid` tinyint(4) NOT NULL DEFAULT '1',
  `language_id` varchar(10) NOT NULL DEFAULT 'en_GB.utf8'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `debtorsmaster`
--

INSERT INTO `debtorsmaster` (`debtorno`, `name`, `address1`, `address2`, `address3`, `address4`, `address5`, `address6`, `currcode`, `salestype`, `clientsince`, `holdreason`, `paymentterms`, `discount`, `pymtdiscount`, `lastpaid`, `lastpaiddate`, `creditlimit`, `invaddrbranch`, `discountcode`, `ediinvoices`, `ediorders`, `edireference`, `editransport`, `ediaddress`, `ediserveruser`, `ediserverpwd`, `taxref`, `customerpoline`, `typeid`, `language_id`) VALUES
('1', 'Apple Computers', 'asdffa', 'sadfasdfa', '', '', '23423432', 'India', 'INR', 'De', '2018-09-03 00:00:00', 1, '20', 0.05, 0.02, 0, NULL, 10000000, 0, '', 0, 0, '', 'email', '', '', '', 'ADSFSD2423423ADS', 1, 4, 'en_IN.utf8');

-- --------------------------------------------------------

--
-- Table structure for table `debtortrans`
--

CREATE TABLE `debtortrans` (
  `id` int(11) NOT NULL,
  `transno` int(11) NOT NULL DEFAULT '0',
  `type` smallint(6) NOT NULL DEFAULT '0',
  `debtorno` varchar(10) NOT NULL DEFAULT '',
  `branchcode` varchar(10) NOT NULL DEFAULT '',
  `trandate` date NOT NULL DEFAULT '0000-00-00',
  `inputdate` datetime NOT NULL,
  `prd` smallint(6) NOT NULL DEFAULT '0',
  `settled` tinyint(4) NOT NULL DEFAULT '0',
  `reference` varchar(20) NOT NULL DEFAULT '',
  `tpe` char(2) NOT NULL DEFAULT '',
  `order_` int(11) NOT NULL DEFAULT '0',
  `rate` double NOT NULL DEFAULT '0',
  `ovamount` double NOT NULL DEFAULT '0',
  `ovgst` double NOT NULL DEFAULT '0',
  `ovfreight` double NOT NULL DEFAULT '0',
  `ovdiscount` double NOT NULL DEFAULT '0',
  `diffonexch` double NOT NULL DEFAULT '0',
  `alloc` double NOT NULL DEFAULT '0',
  `invtext` text,
  `shipvia` int(11) NOT NULL DEFAULT '0',
  `edisent` tinyint(4) NOT NULL DEFAULT '0',
  `consignment` varchar(20) NOT NULL DEFAULT '',
  `packages` int(11) NOT NULL DEFAULT '1' COMMENT 'number of cartons',
  `salesperson` varchar(4) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `debtortranstaxes`
--

CREATE TABLE `debtortranstaxes` (
  `debtortransid` int(11) NOT NULL DEFAULT '0',
  `taxauthid` tinyint(4) NOT NULL DEFAULT '0',
  `taxamount` double NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `debtortype`
--

CREATE TABLE `debtortype` (
  `typeid` tinyint(4) NOT NULL,
  `typename` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `debtortype`
--

INSERT INTO `debtortype` (`typeid`, `typename`) VALUES
(1, 'High Value'),
(2, 'SME'),
(3, 'High Volume'),
(4, 'Corporate');

-- --------------------------------------------------------

--
-- Table structure for table `debtortypenotes`
--

CREATE TABLE `debtortypenotes` (
  `noteid` tinyint(4) NOT NULL,
  `typeid` tinyint(4) NOT NULL DEFAULT '0',
  `href` varchar(100) NOT NULL,
  `note` varchar(200) NOT NULL,
  `date` date NOT NULL DEFAULT '0000-00-00',
  `priority` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `deliverynotes`
--

CREATE TABLE `deliverynotes` (
  `deliverynotenumber` int(11) NOT NULL,
  `deliverynotelineno` tinyint(4) NOT NULL,
  `salesorderno` int(11) NOT NULL,
  `salesorderlineno` int(11) NOT NULL,
  `qtydelivered` double NOT NULL DEFAULT '0',
  `printed` tinyint(4) NOT NULL DEFAULT '0',
  `invoiced` tinyint(4) NOT NULL DEFAULT '0',
  `deliverydate` date NOT NULL DEFAULT '0000-00-00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `departmentid` int(11) NOT NULL,
  `description` varchar(100) NOT NULL DEFAULT '',
  `authoriser` varchar(20) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`departmentid`, `description`, `authoriser`) VALUES
(1, 'Accounts', 'admin'),
(2, 'Sales', 'admin'),
(3, 'Production', 'admin'),
(4, 'Purchase', 'admin'),
(5, 'HR', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `discountmatrix`
--

CREATE TABLE `discountmatrix` (
  `salestype` char(2) NOT NULL DEFAULT '',
  `discountcategory` char(2) NOT NULL DEFAULT '',
  `quantitybreak` int(11) NOT NULL DEFAULT '1',
  `discountrate` double NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `discountmatrix`
--

INSERT INTO `discountmatrix` (`salestype`, `discountcategory`, `quantitybreak`, `discountrate`) VALUES
('2', '', 150, 0.03),
('De', '', 50, 0.02);

-- --------------------------------------------------------

--
-- Table structure for table `ediitemmapping`
--

CREATE TABLE `ediitemmapping` (
  `supporcust` varchar(4) NOT NULL DEFAULT '',
  `partnercode` varchar(10) NOT NULL DEFAULT '',
  `stockid` varchar(20) NOT NULL DEFAULT '',
  `partnerstockid` varchar(50) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `edimessageformat`
--

CREATE TABLE `edimessageformat` (
  `id` int(11) NOT NULL,
  `partnercode` varchar(10) NOT NULL DEFAULT '',
  `messagetype` varchar(6) NOT NULL DEFAULT '',
  `section` varchar(7) NOT NULL DEFAULT '',
  `sequenceno` int(11) NOT NULL DEFAULT '0',
  `linetext` varchar(70) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `edi_orders_segs`
--

CREATE TABLE `edi_orders_segs` (
  `id` int(11) NOT NULL,
  `segtag` char(3) NOT NULL DEFAULT '',
  `seggroup` tinyint(4) NOT NULL DEFAULT '0',
  `maxoccur` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `edi_orders_segs`
--

INSERT INTO `edi_orders_segs` (`id`, `segtag`, `seggroup`, `maxoccur`) VALUES
(1, 'UNB', 0, 1),
(2, 'UNH', 0, 1),
(3, 'BGM', 0, 1),
(4, 'DTM', 0, 35),
(5, 'PAI', 0, 1),
(6, 'ALI', 0, 5),
(7, 'FTX', 0, 99),
(8, 'RFF', 1, 1),
(9, 'DTM', 1, 5),
(10, 'NAD', 2, 1),
(11, 'LOC', 2, 99),
(12, 'FII', 2, 5),
(13, 'RFF', 3, 1),
(14, 'CTA', 5, 1),
(15, 'COM', 5, 5),
(16, 'TAX', 6, 1),
(17, 'MOA', 6, 1),
(18, 'CUX', 7, 1),
(19, 'DTM', 7, 5),
(20, 'PAT', 8, 1),
(21, 'DTM', 8, 5),
(22, 'PCD', 8, 1),
(23, 'MOA', 9, 1),
(24, 'TDT', 10, 1),
(25, 'LOC', 11, 1),
(26, 'DTM', 11, 5),
(27, 'TOD', 12, 1),
(28, 'LOC', 12, 2),
(29, 'PAC', 13, 1),
(30, 'PCI', 14, 1),
(31, 'RFF', 14, 1),
(32, 'DTM', 14, 5),
(33, 'GIN', 14, 10),
(34, 'EQD', 15, 1),
(35, 'ALC', 19, 1),
(36, 'ALI', 19, 5),
(37, 'DTM', 19, 5),
(38, 'QTY', 20, 1),
(39, 'RNG', 20, 1),
(40, 'PCD', 21, 1),
(41, 'RNG', 21, 1),
(42, 'MOA', 22, 1),
(43, 'RNG', 22, 1),
(44, 'RTE', 23, 1),
(45, 'RNG', 23, 1),
(46, 'TAX', 24, 1),
(47, 'MOA', 24, 1),
(48, 'LIN', 28, 1),
(49, 'PIA', 28, 25),
(50, 'IMD', 28, 99),
(51, 'MEA', 28, 99),
(52, 'QTY', 28, 99),
(53, 'ALI', 28, 5),
(54, 'DTM', 28, 35),
(55, 'MOA', 28, 10),
(56, 'GIN', 28, 127),
(57, 'QVR', 28, 1),
(58, 'FTX', 28, 99),
(59, 'PRI', 32, 1),
(60, 'CUX', 32, 1),
(61, 'DTM', 32, 5),
(62, 'RFF', 33, 1),
(63, 'DTM', 33, 5),
(64, 'PAC', 34, 1),
(65, 'QTY', 34, 5),
(66, 'PCI', 36, 1),
(67, 'RFF', 36, 1),
(68, 'DTM', 36, 5),
(69, 'GIN', 36, 10),
(70, 'LOC', 37, 1),
(71, 'QTY', 37, 1),
(72, 'DTM', 37, 5),
(73, 'TAX', 38, 1),
(74, 'MOA', 38, 1),
(75, 'NAD', 39, 1),
(76, 'CTA', 42, 1),
(77, 'COM', 42, 5),
(78, 'ALC', 43, 1),
(79, 'ALI', 43, 5),
(80, 'DTM', 43, 5),
(81, 'QTY', 44, 1),
(82, 'RNG', 44, 1),
(83, 'PCD', 45, 1),
(84, 'RNG', 45, 1),
(85, 'MOA', 46, 1),
(86, 'RNG', 46, 1),
(87, 'RTE', 47, 1),
(88, 'RNG', 47, 1),
(89, 'TAX', 48, 1),
(90, 'MOA', 48, 1),
(91, 'TDT', 49, 1),
(92, 'UNS', 50, 1),
(93, 'MOA', 50, 1),
(94, 'CNT', 50, 1),
(95, 'UNT', 50, 1);

-- --------------------------------------------------------

--
-- Table structure for table `edi_orders_seg_groups`
--

CREATE TABLE `edi_orders_seg_groups` (
  `seggroupno` tinyint(4) NOT NULL DEFAULT '0',
  `maxoccur` int(4) NOT NULL DEFAULT '0',
  `parentseggroup` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `edi_orders_seg_groups`
--

INSERT INTO `edi_orders_seg_groups` (`seggroupno`, `maxoccur`, `parentseggroup`) VALUES
(0, 1, 0),
(1, 9999, 0),
(2, 99, 0),
(3, 99, 2),
(5, 5, 2),
(6, 5, 0),
(7, 5, 0),
(8, 10, 0),
(9, 9999, 8),
(10, 10, 0),
(11, 10, 10),
(12, 5, 0),
(13, 99, 0),
(14, 5, 13),
(15, 10, 0),
(19, 99, 0),
(20, 1, 19),
(21, 1, 19),
(22, 2, 19),
(23, 1, 19),
(24, 5, 19),
(28, 200000, 0),
(32, 25, 28),
(33, 9999, 28),
(34, 99, 28),
(36, 5, 34),
(37, 9999, 28),
(38, 10, 28),
(39, 999, 28),
(42, 5, 39),
(43, 99, 28),
(44, 1, 43),
(45, 1, 43),
(46, 2, 43),
(47, 1, 43),
(48, 5, 43),
(49, 10, 28),
(50, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `emailsettings`
--

CREATE TABLE `emailsettings` (
  `id` int(11) NOT NULL,
  `host` varchar(30) NOT NULL,
  `port` char(5) NOT NULL,
  `heloaddress` varchar(20) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(30) DEFAULT NULL,
  `timeout` int(11) DEFAULT '5',
  `companyname` varchar(50) DEFAULT NULL,
  `auth` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `factorcompanies`
--

CREATE TABLE `factorcompanies` (
  `id` int(11) NOT NULL,
  `coyname` varchar(50) NOT NULL DEFAULT '',
  `address1` varchar(40) NOT NULL DEFAULT '',
  `address2` varchar(40) NOT NULL DEFAULT '',
  `address3` varchar(40) NOT NULL DEFAULT '',
  `address4` varchar(40) NOT NULL DEFAULT '',
  `address5` varchar(20) NOT NULL DEFAULT '',
  `address6` varchar(15) NOT NULL DEFAULT '',
  `contact` varchar(25) NOT NULL DEFAULT '',
  `telephone` varchar(25) NOT NULL DEFAULT '',
  `fax` varchar(25) NOT NULL DEFAULT '',
  `email` varchar(55) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `favourites`
--

CREATE TABLE `favourites` (
  `userid` varchar(20) NOT NULL DEFAULT '',
  `caption` varchar(50) NOT NULL DEFAULT '',
  `href` varchar(200) NOT NULL DEFAULT '#'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `fixedassetcategories`
--

CREATE TABLE `fixedassetcategories` (
  `categoryid` char(6) NOT NULL DEFAULT '',
  `categorydescription` char(20) NOT NULL DEFAULT '',
  `costact` varchar(20) NOT NULL DEFAULT '0',
  `depnact` varchar(20) NOT NULL DEFAULT '0',
  `disposalact` varchar(20) NOT NULL DEFAULT '80000',
  `accumdepnact` varchar(20) NOT NULL DEFAULT '0',
  `defaultdepnrate` double NOT NULL DEFAULT '0.2',
  `defaultdepntype` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `fixedassetcategories`
--

INSERT INTO `fixedassetcategories` (`categoryid`, `categorydescription`, `costact`, `depnact`, `disposalact`, `accumdepnact`, `defaultdepnrate`, `defaultdepntype`) VALUES
('COMP', 'Computers', '1720', '7750', '7700', '1730', 0.2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `fixedassetlocations`
--

CREATE TABLE `fixedassetlocations` (
  `locationid` char(6) NOT NULL DEFAULT '',
  `locationdescription` char(20) NOT NULL DEFAULT '',
  `parentlocationid` char(6) DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `fixedassetlocations`
--

INSERT INTO `fixedassetlocations` (`locationid`, `locationdescription`, `parentlocationid`) VALUES
('HO', 'Head Office', '');

-- --------------------------------------------------------

--
-- Table structure for table `fixedassets`
--

CREATE TABLE `fixedassets` (
  `assetid` int(11) NOT NULL,
  `serialno` varchar(30) NOT NULL DEFAULT '',
  `barcode` varchar(20) NOT NULL,
  `assetlocation` varchar(6) NOT NULL DEFAULT '',
  `cost` double NOT NULL DEFAULT '0',
  `accumdepn` double NOT NULL DEFAULT '0',
  `datepurchased` date NOT NULL DEFAULT '0000-00-00',
  `disposalproceeds` double NOT NULL DEFAULT '0',
  `assetcategoryid` varchar(6) NOT NULL DEFAULT '',
  `description` varchar(50) NOT NULL DEFAULT '',
  `longdescription` text NOT NULL,
  `depntype` int(11) NOT NULL DEFAULT '1',
  `depnrate` double NOT NULL,
  `disposaldate` date NOT NULL DEFAULT '0000-00-00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `fixedassets`
--

INSERT INTO `fixedassets` (`assetid`, `serialno`, `barcode`, `assetlocation`, `cost`, `accumdepn`, `datepurchased`, `disposalproceeds`, `assetcategoryid`, `description`, `longdescription`, `depntype`, `depnrate`, `disposaldate`) VALUES
(1, '1234354678', '', 'HO', 0, 0, '0000-00-00', 0, 'COMP', 'Lenevo5310', 'Lenevo Laptop 4GB', 0, 30, '0000-00-00'),
(2, '1234354678', '', 'HO', 0, 0, '0000-00-00', 0, 'COMP', 'Lenevo5310', 'Lenevo Laptop 4GB', 0, 30, '0000-00-00');

-- --------------------------------------------------------

--
-- Table structure for table `fixedassettasks`
--

CREATE TABLE `fixedassettasks` (
  `taskid` int(11) NOT NULL,
  `assetid` int(11) NOT NULL,
  `taskdescription` text NOT NULL,
  `frequencydays` int(11) NOT NULL DEFAULT '365',
  `lastcompleted` date NOT NULL,
  `userresponsible` varchar(20) NOT NULL,
  `manager` varchar(20) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `fixedassettasks`
--

INSERT INTO `fixedassettasks` (`taskid`, `assetid`, `taskdescription`, `frequencydays`, `lastcompleted`, `userresponsible`, `manager`) VALUES
(1, 1, 'Annual Maintenance', 30, '2018-09-02', 'admin', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `fixedassettrans`
--

CREATE TABLE `fixedassettrans` (
  `id` int(11) NOT NULL,
  `assetid` int(11) NOT NULL,
  `transtype` tinyint(4) NOT NULL,
  `transdate` date NOT NULL,
  `transno` int(11) NOT NULL,
  `periodno` smallint(6) NOT NULL,
  `inputdate` date NOT NULL,
  `fixedassettranstype` varchar(8) NOT NULL,
  `amount` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `freightcosts`
--

CREATE TABLE `freightcosts` (
  `shipcostfromid` int(11) NOT NULL,
  `locationfrom` varchar(5) NOT NULL DEFAULT '',
  `destinationcountry` varchar(40) NOT NULL,
  `destination` varchar(40) NOT NULL DEFAULT '',
  `shipperid` int(11) NOT NULL DEFAULT '0',
  `cubrate` double NOT NULL DEFAULT '0',
  `kgrate` double NOT NULL DEFAULT '0',
  `maxkgs` double NOT NULL DEFAULT '999999',
  `maxcub` double NOT NULL DEFAULT '999999',
  `fixedprice` double NOT NULL DEFAULT '0',
  `minimumchg` double NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `geocode_param`
--

CREATE TABLE `geocode_param` (
  `geocodeid` tinyint(4) NOT NULL,
  `geocode_key` varchar(200) NOT NULL DEFAULT '',
  `center_long` varchar(20) NOT NULL DEFAULT '',
  `center_lat` varchar(20) NOT NULL DEFAULT '',
  `map_height` varchar(10) NOT NULL DEFAULT '',
  `map_width` varchar(10) NOT NULL DEFAULT '',
  `map_host` varchar(50) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `glaccountusers`
--

CREATE TABLE `glaccountusers` (
  `accountcode` varchar(20) NOT NULL COMMENT 'GL account code from chartmaster',
  `userid` varchar(20) NOT NULL,
  `canview` tinyint(4) NOT NULL DEFAULT '0',
  `canupd` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `glaccountusers`
--

INSERT INTO `glaccountusers` (`accountcode`, `userid`, `canview`, `canupd`) VALUES
('1', 'demo', 1, 1),
('1010', 'demo', 1, 1),
('1020', 'demo', 1, 1),
('1030', 'demo', 1, 1),
('1040', 'demo', 1, 1),
('1050', 'demo', 1, 1),
('1060', 'demo', 1, 1),
('1070', 'demo', 1, 1),
('1080', 'demo', 1, 1),
('1090', 'demo', 1, 1),
('1100', 'demo', 1, 1),
('1150', 'demo', 1, 1),
('1200', 'demo', 1, 1),
('1250', 'demo', 1, 1),
('1300', 'demo', 1, 1),
('1350', 'demo', 1, 1),
('1400', 'demo', 1, 1),
('1420', 'demo', 1, 1),
('1440', 'demo', 1, 1),
('1460', 'demo', 1, 1),
('1500', 'demo', 1, 1),
('1550', 'demo', 1, 1),
('1600', 'demo', 1, 1),
('1620', 'demo', 1, 1),
('1650', 'demo', 1, 1),
('1670', 'demo', 1, 1),
('1700', 'demo', 1, 1),
('1710', 'demo', 1, 1),
('1720', 'demo', 1, 1),
('1730', 'demo', 1, 1),
('1740', 'demo', 1, 1),
('1750', 'demo', 1, 1),
('1760', 'demo', 1, 1),
('1770', 'demo', 1, 1),
('1780', 'demo', 1, 1),
('1790', 'demo', 1, 1),
('1800', 'demo', 1, 1),
('1850', 'demo', 1, 1),
('1900', 'demo', 1, 1),
('2010', 'demo', 1, 1),
('2020', 'demo', 1, 1),
('2050', 'demo', 1, 1),
('2100', 'demo', 1, 1),
('2150', 'demo', 1, 1),
('2200', 'demo', 1, 1),
('2230', 'demo', 1, 1),
('2250', 'demo', 1, 1),
('2300', 'demo', 1, 1),
('2310', 'demo', 1, 1),
('2320', 'demo', 1, 1),
('2330', 'demo', 1, 1),
('2340', 'demo', 1, 1),
('2360', 'demo', 1, 1),
('2400', 'demo', 1, 1),
('2410', 'demo', 1, 1),
('2420', 'demo', 1, 1),
('2450', 'demo', 1, 1),
('2460', 'demo', 1, 1),
('2480', 'demo', 1, 1),
('2500', 'demo', 1, 1),
('2550', 'demo', 1, 1),
('2560', 'demo', 1, 1),
('2600', 'demo', 1, 1),
('2700', 'demo', 1, 1),
('2720', 'demo', 1, 1),
('2740', 'demo', 1, 1),
('2760', 'demo', 1, 1),
('2800', 'demo', 1, 1),
('2900', 'demo', 1, 1),
('3100', 'demo', 1, 1),
('3200', 'demo', 1, 1),
('3300', 'demo', 1, 1),
('3400', 'demo', 1, 1),
('3500', 'demo', 1, 1),
('4100', 'demo', 1, 1),
('4200', 'demo', 1, 1),
('4500', 'demo', 1, 1),
('4600', 'demo', 1, 1),
('4700', 'demo', 1, 1),
('4800', 'demo', 1, 1),
('4900', 'demo', 1, 1),
('5000', 'demo', 1, 1),
('5100', 'demo', 1, 1),
('5200', 'demo', 1, 1),
('5500', 'demo', 1, 1),
('5600', 'demo', 1, 1),
('5700', 'demo', 1, 1),
('5800', 'demo', 1, 1),
('5900', 'demo', 1, 1),
('6100', 'demo', 1, 1),
('6150', 'demo', 1, 1),
('6200', 'demo', 1, 1),
('6250', 'demo', 1, 1),
('6300', 'demo', 1, 1),
('6400', 'demo', 1, 1),
('6500', 'demo', 1, 1),
('6550', 'demo', 1, 1),
('6590', 'demo', 1, 1),
('6600', 'demo', 1, 1),
('6700', 'demo', 1, 1),
('6800', 'demo', 1, 1),
('6900', 'demo', 1, 1),
('7020', 'demo', 1, 1),
('7030', 'demo', 1, 1),
('7040', 'demo', 1, 1),
('7050', 'demo', 1, 1),
('7060', 'demo', 1, 1),
('7070', 'demo', 1, 1),
('7080', 'demo', 1, 1),
('7090', 'demo', 1, 1),
('7100', 'demo', 1, 1),
('7150', 'demo', 1, 1),
('7200', 'demo', 1, 1),
('7210', 'demo', 1, 1),
('7220', 'demo', 1, 1),
('7230', 'demo', 1, 1),
('7240', 'demo', 1, 1),
('7260', 'demo', 1, 1),
('7280', 'demo', 1, 1),
('7300', 'demo', 1, 1),
('7350', 'demo', 1, 1),
('7390', 'demo', 1, 1),
('7400', 'demo', 1, 1),
('7450', 'demo', 1, 1),
('7500', 'demo', 1, 1),
('7550', 'demo', 1, 1),
('7600', 'demo', 1, 1),
('7610', 'demo', 1, 1),
('7620', 'demo', 1, 1),
('7630', 'demo', 1, 1),
('7640', 'demo', 1, 1),
('7650', 'demo', 1, 1),
('7660', 'demo', 1, 1),
('7700', 'demo', 1, 1),
('7750', 'demo', 1, 1),
('7800', 'demo', 1, 1),
('7900', 'demo', 1, 1),
('8100', 'demo', 1, 1),
('8200', 'demo', 1, 1),
('8300', 'demo', 1, 1),
('8400', 'demo', 1, 1),
('8500', 'demo', 1, 1),
('8600', 'demo', 1, 1),
('8900', 'demo', 1, 1),
('9100', 'demo', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `gltrans`
--

CREATE TABLE `gltrans` (
  `counterindex` int(11) NOT NULL,
  `type` smallint(6) NOT NULL DEFAULT '0',
  `typeno` bigint(16) NOT NULL DEFAULT '1',
  `chequeno` int(11) NOT NULL DEFAULT '0',
  `trandate` date NOT NULL DEFAULT '0000-00-00',
  `periodno` smallint(6) NOT NULL DEFAULT '0',
  `account` varchar(20) NOT NULL DEFAULT '0',
  `narrative` varchar(200) NOT NULL DEFAULT '',
  `amount` double NOT NULL DEFAULT '0',
  `posted` tinyint(4) NOT NULL DEFAULT '0',
  `jobref` varchar(20) NOT NULL DEFAULT '',
  `tag` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `grns`
--

CREATE TABLE `grns` (
  `grnbatch` smallint(6) NOT NULL DEFAULT '0',
  `grnno` int(11) NOT NULL,
  `podetailitem` int(11) NOT NULL DEFAULT '0',
  `itemcode` varchar(20) NOT NULL DEFAULT '',
  `deliverydate` date NOT NULL DEFAULT '0000-00-00',
  `itemdescription` varchar(100) NOT NULL DEFAULT '',
  `qtyrecd` double NOT NULL DEFAULT '0',
  `quantityinv` double NOT NULL DEFAULT '0',
  `supplierid` varchar(10) NOT NULL DEFAULT '',
  `stdcostunit` double NOT NULL DEFAULT '0',
  `supplierref` varchar(30) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `holdreasons`
--

CREATE TABLE `holdreasons` (
  `reasoncode` smallint(6) NOT NULL DEFAULT '1',
  `reasondescription` char(30) NOT NULL DEFAULT '',
  `dissallowinvoices` tinyint(4) NOT NULL DEFAULT '-1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `holdreasons`
--

INSERT INTO `holdreasons` (`reasoncode`, `reasondescription`, `dissallowinvoices`) VALUES
(1, 'Good History', 0),
(20, 'Watch', 2),
(51, 'In liquidation', 1);

-- --------------------------------------------------------

--
-- Table structure for table `hremployeeattendanceregister`
--

CREATE TABLE `hremployeeattendanceregister` (
  `attendance_id` int(11) NOT NULL,
  `employee_attendance_id` int(11) NOT NULL,
  `absent_date` date NOT NULL,
  `leave_type_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `hremployeecategories`
--

CREATE TABLE `hremployeecategories` (
  `employee_category_id` int(11) NOT NULL,
  `category_name` varchar(50) NOT NULL,
  `category_prefix` varchar(5) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `hremployeecategories`
--

INSERT INTO `hremployeecategories` (`employee_category_id`, `category_name`, `category_prefix`, `status`) VALUES
(1, 'Executive', 'EX', 1),
(2, 'Manager', 'MAN', 1);

-- --------------------------------------------------------

--
-- Table structure for table `hremployeegradings`
--

CREATE TABLE `hremployeegradings` (
  `employee_grading_id` int(11) NOT NULL,
  `grading_name` varchar(20) NOT NULL,
  `priority` int(11) NOT NULL,
  `grading_description` text NOT NULL,
  `grading_status` tinyint(4) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `hremployeegradings`
--

INSERT INTO `hremployeegradings` (`employee_grading_id`, `grading_name`, `priority`, `grading_description`, `grading_status`) VALUES
(1, 'A', 1, 'sdfsdfadsfadf', 1),
(2, 'B', 2, 'sdfsdfadsfadf', 1);

-- --------------------------------------------------------

--
-- Table structure for table `hremployeeleavegroups`
--

CREATE TABLE `hremployeeleavegroups` (
  `leavegroup_id` int(11) NOT NULL,
  `leavegroup_name` varchar(50) NOT NULL,
  `leavegroup_description` text NOT NULL,
  `leavegroup_status` tinyint(4) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `hremployeeleaves`
--

CREATE TABLE `hremployeeleaves` (
  `employee_leave_id` int(11) NOT NULL,
  `leaveemployee_id` int(11) NOT NULL,
  `leave_type_id` int(11) NOT NULL,
  `is_half` varchar(200) NOT NULL,
  `leave_start_date` date DEFAULT NULL,
  `leave_end_date` date DEFAULT NULL,
  `leave_reason` text NOT NULL,
  `leave_approved` tinyint(4) NOT NULL DEFAULT '0',
  `leave_viewed_by_manager` tinyint(4) NOT NULL DEFAULT '0',
  `leave_manager_remark` text NOT NULL,
  `leave_approving_manager` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `hremployeeleavetypes`
--

CREATE TABLE `hremployeeleavetypes` (
  `hrleavetype_id` int(11) NOT NULL,
  `leavetype_name` varchar(50) NOT NULL,
  `leavetype_code` varchar(50) NOT NULL,
  `leavetype_status` tinyint(1) NOT NULL DEFAULT '1',
  `leavetype_leavecount` varchar(100) NOT NULL,
  `carry_forward` tinyint(1) NOT NULL DEFAULT '1',
  `lop_enabled` tinyint(1) NOT NULL DEFAULT '1',
  `max_carry_forward_leaves` varchar(50) NOT NULL,
  `reset_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `hremployeeleavetypes`
--

INSERT INTO `hremployeeleavetypes` (`hrleavetype_id`, `leavetype_name`, `leavetype_code`, `leavetype_status`, `leavetype_leavecount`, `carry_forward`, `lop_enabled`, `max_carry_forward_leaves`, `reset_date`) VALUES
(1, 'Casual Leave', 'CL', 1, '5', 1, 1, '2', '2018-09-01'),
(2, 'Sick Leave', 'SL', 1, '5', 1, 1, '2', '2018-09-01');

-- --------------------------------------------------------

--
-- Table structure for table `hremployeeloanpayments`
--

CREATE TABLE `hremployeeloanpayments` (
  `loan_payment_id` int(11) NOT NULL,
  `loan_id` int(11) NOT NULL,
  `amount_paid` decimal(10,2) NOT NULL,
  `date_paid` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `hremployeeloans`
--

CREATE TABLE `hremployeeloans` (
  `loan_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `loan_type` int(11) NOT NULL,
  `is_approved` tinyint(1) NOT NULL DEFAULT '0',
  `approved_by` varchar(20) DEFAULT NULL,
  `loan_amount` decimal(10,2) NOT NULL,
  `number_of_installments` int(11) NOT NULL DEFAULT '1',
  `amount_per_installment` decimal(10,2) NOT NULL,
  `loan_status` tinyint(1) NOT NULL DEFAULT '0',
  `bank_account_to_use` varchar(20) DEFAULT NULL,
  `gl_posting_account` varchar(20) DEFAULT NULL,
  `finance_transaction_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `hremployeeloantypes`
--

CREATE TABLE `hremployeeloantypes` (
  `loan_type_id` int(11) NOT NULL,
  `loan_type_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `hremployeepayslips`
--

CREATE TABLE `hremployeepayslips` (
  `payslip_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `payslip_status` varchar(20) NOT NULL DEFAULT 'pending',
  `approver_id` varchar(20) DEFAULT NULL,
  `rejector_id` varchar(20) DEFAULT NULL,
  `rejecting_reason` text,
  `gross_salary` decimal(10,2) NOT NULL,
  `lop` decimal(10,2) DEFAULT NULL COMMENT 'standard lop per day',
  `lop_days` int(11) DEFAULT NULL COMMENT 'number of days for lop',
  `lop_amount` decimal(10,2) DEFAULT NULL COMMENT 'total amount for lop',
  `loan_deduction_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `days_worked` int(11) DEFAULT NULL,
  `total_earnings` decimal(10,2) NOT NULL DEFAULT '0.00',
  `total_deductions` decimal(10,2) NOT NULL DEFAULT '0.00',
  `payslip_date_range_id` int(11) NOT NULL,
  `net_pay` decimal(10,2) NOT NULL DEFAULT '0.00',
  `finance_transaction_id` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `hremployeepositions`
--

CREATE TABLE `hremployeepositions` (
  `employee_position_id` int(11) NOT NULL,
  `position_name` varchar(50) NOT NULL,
  `employee_category_id` int(11) NOT NULL,
  `position_status` tinyint(4) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `hremployeepositions`
--

INSERT INTO `hremployeepositions` (`employee_position_id`, `position_name`, `employee_category_id`, `position_status`) VALUES
(1, 'Junior', 1, 1),
(2, 'Senior', 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `hremployees`
--

CREATE TABLE `hremployees` (
  `empid` int(11) NOT NULL,
  `employee_id` varchar(50) NOT NULL,
  `user_id` varchar(20) DEFAULT NULL,
  `joining_date` date NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `middle_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `employee_position` int(11) NOT NULL,
  `employee_grade_id` int(11) DEFAULT NULL,
  `job_title` varchar(50) DEFAULT NULL,
  `resume` text,
  `employee_department` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `date_of_birth` date NOT NULL,
  `marital_status` varchar(10) NOT NULL,
  `children_count` int(11) NOT NULL DEFAULT '0',
  `father_name` varchar(100) DEFAULT NULL,
  `mother_name` varchar(100) DEFAULT NULL,
  `nationality` varchar(100) NOT NULL DEFAULT 'Uganda',
  `national_id` varchar(50) DEFAULT NULL,
  `passport_no` varchar(50) DEFAULT NULL,
  `home_address` text NOT NULL,
  `home_city` varchar(50) DEFAULT NULL,
  `mobile_phone` varchar(13) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `manager_id` int(11) DEFAULT NULL,
  `bank_name` varchar(100) DEFAULT NULL,
  `bank_account_no` varchar(50) DEFAULT NULL,
  `spouse_name` varchar(100) DEFAULT NULL,
  `spouse_phone_no` varchar(15) DEFAULT NULL,
  `social_security_no` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `hremployees`
--

INSERT INTO `hremployees` (`empid`, `employee_id`, `user_id`, `joining_date`, `first_name`, `middle_name`, `last_name`, `gender`, `employee_position`, `employee_grade_id`, `job_title`, `resume`, `employee_department`, `status`, `date_of_birth`, `marital_status`, `children_count`, `father_name`, `mother_name`, `nationality`, `national_id`, `passport_no`, `home_address`, `home_city`, `mobile_phone`, `email`, `manager_id`, `bank_name`, `bank_account_no`, `spouse_name`, `spouse_phone_no`, `social_security_no`) VALUES
(1, '1', 'steve', '2018-09-01', 'Steve', '', 'Jobs', 'male', 1, 2, 'Accounts Executive', '', 1, 1, '2018-09-01', 'single', 0, '', '', 'India', '', '', '324523452345sdasfdsfa', NULL, '3542345234', '', 0, 'asdfasd', '32423423', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `hremployeesalarystructure_components`
--

CREATE TABLE `hremployeesalarystructure_components` (
  `component_id` int(11) NOT NULL,
  `salary_structure_id` int(11) NOT NULL,
  `payroll_category_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `hrpaymentfrequency`
--

CREATE TABLE `hrpaymentfrequency` (
  `paymentfrequency_id` int(11) NOT NULL,
  `frequency_name` varchar(50) NOT NULL,
  `working_days` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `hrpayrollcategories`
--

CREATE TABLE `hrpayrollcategories` (
  `payroll_category_id` int(11) NOT NULL,
  `payroll_category_name` varchar(50) NOT NULL,
  `payroll_category_code` varchar(10) NOT NULL,
  `payroll_category_value` varchar(100) NOT NULL,
  `payroll_category_type` tinyint(50) NOT NULL DEFAULT '1',
  `additional_condition` varchar(50) NOT NULL,
  `general_ledger_account_id` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `hrpayrollcategories`
--

INSERT INTO `hrpayrollcategories` (`payroll_category_id`, `payroll_category_name`, `payroll_category_code`, `payroll_category_value`, `payroll_category_type`, `additional_condition`, `general_ledger_account_id`) VALUES
(1, 'Basic&amp;DA', 'BDA', '5000', 1, 'Nothing', '1050'),
(2, 'HRA', 'HRA', '3500', 1, 'Nothing', '1050'),
(3, 'Conveyance', 'CON', '1500', 1, 'Nothing', '1050'),
(4, 'PF', 'PF', '600', 0, '% of BDA', '2340'),
(5, 'Loan', 'LN', '-', 0, '', '1350');

-- --------------------------------------------------------

--
-- Table structure for table `hrpayrollgroups`
--

CREATE TABLE `hrpayrollgroups` (
  `payrollgroup_id` int(11) NOT NULL,
  `payrollgroup_name` varchar(100) NOT NULL,
  `payment_frequency` int(11) NOT NULL,
  `generation_date` int(11) NOT NULL DEFAULT '1',
  `enable_lop` tinyint(1) NOT NULL DEFAULT '0',
  `lop_value` varchar(200) NOT NULL,
  `bank_account_to_use` varchar(200) DEFAULT NULL,
  `gl_posting_account` varchar(200) DEFAULT NULL,
  `currency` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `hrpayrollgroups`
--

INSERT INTO `hrpayrollgroups` (`payrollgroup_id`, `payrollgroup_name`, `payment_frequency`, `generation_date`, `enable_lop`, `lop_value`, `bank_account_to_use`, `gl_posting_account`, `currency`) VALUES
(1, 'Executive', 0, 1, 1, '2', '', '1050', 'INR');

-- --------------------------------------------------------

--
-- Table structure for table `hrpayroll_groups_payroll_categories`
--

CREATE TABLE `hrpayroll_groups_payroll_categories` (
  `groups_categories_id` int(11) NOT NULL,
  `payroll_group_id` int(11) NOT NULL,
  `payroll_category_id` int(11) NOT NULL,
  `sort_order` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `hrpayroll_groups_payroll_categories`
--

INSERT INTO `hrpayroll_groups_payroll_categories` (`groups_categories_id`, `payroll_group_id`, `payroll_category_id`, `sort_order`) VALUES
(1, 1, 1, 1),
(2, 1, 2, 2),
(3, 1, 3, 3),
(4, 1, 4, 4),
(5, 1, 5, 5);

-- --------------------------------------------------------

--
-- Table structure for table `hrpayslipcategorydetails`
--

CREATE TABLE `hrpayslipcategorydetails` (
  `detail_id` int(11) NOT NULL,
  `payslip_id` int(11) NOT NULL,
  `payroll_category_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `hrpayslipdateranges`
--

CREATE TABLE `hrpayslipdateranges` (
  `daterange_id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `payrollgroup_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `hrpayslipextradetails`
--

CREATE TABLE `hrpayslipextradetails` (
  `extra_payslip_id` int(11) NOT NULL,
  `payslip_id` int(11) NOT NULL,
  `entry_type` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'either earning or deduction',
  `amount` decimal(10,2) NOT NULL,
  `comment` varchar(200) DEFAULT NULL,
  `user_id` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `internalstockcatrole`
--

CREATE TABLE `internalstockcatrole` (
  `categoryid` varchar(6) NOT NULL,
  `secroleid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `internalstockcatrole`
--

INSERT INTO `internalstockcatrole` (`categoryid`, `secroleid`) VALUES
('2', 1),
('4', 1),
('5', 1),
('1', 8),
('2', 8),
('3', 8),
('4', 8),
('5', 8);

-- --------------------------------------------------------

--
-- Table structure for table `labelfields`
--

CREATE TABLE `labelfields` (
  `labelfieldid` int(11) NOT NULL,
  `labelid` tinyint(4) NOT NULL,
  `fieldvalue` varchar(20) NOT NULL,
  `vpos` double NOT NULL DEFAULT '0',
  `hpos` double NOT NULL DEFAULT '0',
  `fontsize` tinyint(4) NOT NULL,
  `barcode` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `labels`
--

CREATE TABLE `labels` (
  `labelid` tinyint(11) NOT NULL,
  `description` varchar(50) NOT NULL,
  `pagewidth` double NOT NULL DEFAULT '0',
  `pageheight` double NOT NULL DEFAULT '0',
  `height` double NOT NULL DEFAULT '0',
  `width` double NOT NULL DEFAULT '0',
  `topmargin` double NOT NULL DEFAULT '0',
  `leftmargin` double NOT NULL DEFAULT '0',
  `rowheight` double NOT NULL DEFAULT '0',
  `columnwidth` double NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `lastcostrollup`
--

CREATE TABLE `lastcostrollup` (
  `stockid` char(20) NOT NULL DEFAULT '',
  `totalonhand` double NOT NULL DEFAULT '0',
  `matcost` decimal(20,4) NOT NULL DEFAULT '0.0000',
  `labcost` decimal(20,4) NOT NULL DEFAULT '0.0000',
  `oheadcost` decimal(20,4) NOT NULL DEFAULT '0.0000',
  `categoryid` char(6) NOT NULL DEFAULT '',
  `stockact` varchar(20) NOT NULL DEFAULT '0',
  `adjglact` varchar(20) NOT NULL DEFAULT '0',
  `newmatcost` decimal(20,4) NOT NULL DEFAULT '0.0000',
  `newlabcost` decimal(20,4) NOT NULL DEFAULT '0.0000',
  `newoheadcost` decimal(20,4) NOT NULL DEFAULT '0.0000'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE `locations` (
  `loccode` varchar(5) NOT NULL DEFAULT '',
  `locationname` varchar(50) NOT NULL DEFAULT '',
  `deladd1` varchar(40) NOT NULL DEFAULT '',
  `deladd2` varchar(40) NOT NULL DEFAULT '',
  `deladd3` varchar(40) NOT NULL DEFAULT '',
  `deladd4` varchar(40) NOT NULL DEFAULT '',
  `deladd5` varchar(20) NOT NULL DEFAULT '',
  `deladd6` varchar(15) NOT NULL DEFAULT '',
  `tel` varchar(30) NOT NULL DEFAULT '',
  `fax` varchar(30) NOT NULL DEFAULT '',
  `email` varchar(55) NOT NULL DEFAULT '',
  `contact` varchar(30) NOT NULL DEFAULT '',
  `taxprovinceid` tinyint(4) NOT NULL DEFAULT '1',
  `cashsalecustomer` varchar(10) DEFAULT '',
  `managed` int(11) DEFAULT '0',
  `cashsalebranch` varchar(10) DEFAULT '',
  `internalrequest` tinyint(4) NOT NULL DEFAULT '1' COMMENT 'Allow (1) or not (0) internal request from this location',
  `usedforwo` tinyint(4) NOT NULL DEFAULT '1',
  `glaccountcode` varchar(20) NOT NULL DEFAULT '' COMMENT 'GL account of the location',
  `allowinvoicing` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Allow invoicing of items at this location'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `locations`
--

INSERT INTO `locations` (`loccode`, `locationname`, `deladd1`, `deladd2`, `deladd3`, `deladd4`, `deladd5`, `deladd6`, `tel`, `fax`, `email`, `contact`, `taxprovinceid`, `cashsalecustomer`, `managed`, `cashsalebranch`, `internalrequest`, `usedforwo`, `glaccountcode`, `allowinvoicing`) VALUES
('BAN', 'Bangalore', ' Dummy', ' Dummy', ' Dummy', '', '', 'India', '2423423', '', 'dummy@dummy.com', 'XYZ', 1, '', 0, '', 1, 1, '', 1),
('MUM', 'Mumbai', '  Dummy', ' Dummy', ' Dummy', '', '', 'India', '23423423', '', 'dummy@dummy.com', ' Dummy', 1, '', 0, '', 1, 1, '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `locationusers`
--

CREATE TABLE `locationusers` (
  `loccode` varchar(5) NOT NULL,
  `userid` varchar(20) NOT NULL,
  `canview` tinyint(4) NOT NULL DEFAULT '0',
  `canupd` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `locationusers`
--

INSERT INTO `locationusers` (`loccode`, `userid`, `canview`, `canupd`) VALUES
('BAN', 'admin', 1, 1),
('BAN', 'demo', 1, 1),
('MUM', 'admin', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `locstock`
--

CREATE TABLE `locstock` (
  `loccode` varchar(5) NOT NULL DEFAULT '',
  `stockid` varchar(20) NOT NULL DEFAULT '',
  `quantity` double NOT NULL DEFAULT '0',
  `reorderlevel` bigint(20) NOT NULL DEFAULT '0',
  `bin` varchar(10) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `locstock`
--

INSERT INTO `locstock` (`loccode`, `stockid`, `quantity`, `reorderlevel`, `bin`) VALUES
('BAN', 'HDD128', 0, 0, ''),
('BAN', 'RAM4', 0, 0, ''),
('BAN', 'S9', 0, 0, ''),
('BAN', 'SCREEN6', 0, 0, ''),
('MUM', 'HDD128', 0, 0, ''),
('MUM', 'RAM4', 0, 0, ''),
('MUM', 'S9', 0, 0, ''),
('MUM', 'SCREEN6', 0, 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `loctransfercancellations`
--

CREATE TABLE `loctransfercancellations` (
  `reference` int(11) NOT NULL,
  `stockid` varchar(20) NOT NULL,
  `cancelqty` double NOT NULL,
  `canceldate` datetime NOT NULL,
  `canceluserid` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `loctransfers`
--

CREATE TABLE `loctransfers` (
  `reference` int(11) NOT NULL DEFAULT '0',
  `stockid` varchar(20) NOT NULL DEFAULT '',
  `shipqty` double NOT NULL DEFAULT '0',
  `recqty` double NOT NULL DEFAULT '0',
  `shipdate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `recdate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `shiploc` varchar(7) NOT NULL DEFAULT '',
  `recloc` varchar(7) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Stores Shipments To And From Locations';

-- --------------------------------------------------------

--
-- Table structure for table `mailgroupdetails`
--

CREATE TABLE `mailgroupdetails` (
  `groupname` varchar(100) NOT NULL,
  `userid` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `mailgroups`
--

CREATE TABLE `mailgroups` (
  `id` int(11) NOT NULL,
  `groupname` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `manufacturers`
--

CREATE TABLE `manufacturers` (
  `manufacturers_id` int(11) NOT NULL,
  `manufacturers_name` varchar(32) NOT NULL,
  `manufacturers_url` varchar(50) NOT NULL DEFAULT '',
  `manufacturers_image` varchar(64) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `mrpcalendar`
--

CREATE TABLE `mrpcalendar` (
  `calendardate` date NOT NULL,
  `daynumber` int(6) NOT NULL,
  `manufacturingflag` smallint(6) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `mrpcalendar`
--

INSERT INTO `mrpcalendar` (`calendardate`, `daynumber`, `manufacturingflag`) VALUES
('2017-08-25', 2, 1),
('2017-08-26', 3, 1),
('2017-08-27', 3, 0),
('2017-08-28', 4, 1),
('2017-08-29', 5, 1),
('2017-08-30', 6, 1),
('2017-08-31', 7, 1),
('2017-09-01', 8, 1),
('2017-09-02', 9, 1),
('2017-09-03', 9, 0),
('2017-09-04', 10, 1),
('2017-09-05', 11, 1),
('2017-09-06', 12, 1),
('2017-09-07', 13, 1),
('2017-09-08', 14, 1),
('2017-09-09', 15, 1),
('2017-09-10', 15, 0),
('2017-09-11', 16, 1),
('2017-09-12', 17, 1),
('2017-09-13', 18, 1),
('2017-09-14', 19, 1),
('2017-09-15', 20, 1),
('2017-09-16', 21, 1),
('2017-09-17', 21, 0),
('2017-09-18', 22, 1),
('2017-09-19', 23, 1),
('2017-09-20', 24, 1),
('2017-09-21', 25, 1),
('2017-09-22', 26, 1),
('2017-09-23', 27, 1),
('2017-09-24', 27, 0),
('2017-09-25', 28, 1),
('2017-09-26', 29, 1),
('2017-09-27', 30, 1),
('2017-09-28', 31, 1),
('2017-09-29', 32, 1),
('2017-09-30', 33, 1),
('2017-10-01', 33, 0),
('2017-10-02', 34, 1),
('2017-10-03', 35, 1),
('2017-10-04', 36, 1),
('2017-10-05', 37, 1),
('2017-10-06', 38, 1),
('2017-10-07', 39, 1),
('2017-10-08', 39, 0),
('2017-10-09', 40, 1),
('2017-10-10', 41, 1),
('2017-10-11', 42, 1),
('2017-10-12', 43, 1),
('2017-10-13', 44, 1),
('2017-10-14', 45, 1),
('2017-10-15', 45, 0),
('2017-10-16', 46, 1),
('2017-10-17', 47, 1),
('2017-10-18', 48, 1),
('2017-10-19', 49, 1),
('2017-10-20', 50, 1),
('2017-10-21', 51, 1),
('2017-10-22', 51, 0),
('2017-10-23', 52, 1),
('2017-10-24', 53, 1),
('2017-10-25', 54, 1),
('2017-10-26', 55, 1),
('2017-10-27', 56, 1),
('2017-10-28', 57, 1),
('2017-10-29', 57, 0),
('2017-10-30', 58, 1),
('2017-10-31', 59, 1),
('2017-11-01', 60, 1),
('2017-11-02', 61, 1),
('2017-11-03', 62, 1),
('2017-11-04', 63, 1),
('2017-11-05', 63, 0),
('2017-11-06', 64, 1),
('2017-11-07', 65, 1),
('2017-11-08', 66, 1),
('2017-11-09', 67, 1),
('2017-11-10', 68, 1),
('2017-11-11', 69, 1),
('2017-11-12', 69, 0),
('2017-11-13', 70, 1),
('2017-11-14', 71, 1),
('2017-11-15', 72, 1),
('2017-11-16', 73, 1),
('2017-11-17', 74, 1),
('2017-11-18', 75, 1),
('2017-11-19', 75, 0),
('2017-11-20', 76, 1),
('2017-11-21', 77, 1),
('2017-11-22', 78, 1),
('2017-11-23', 79, 1),
('2017-11-24', 80, 1),
('2017-11-25', 81, 1),
('2017-11-26', 81, 0),
('2017-11-27', 82, 1),
('2017-11-28', 83, 1),
('2017-11-29', 84, 1),
('2017-11-30', 85, 1),
('2017-12-01', 86, 1),
('2017-12-02', 87, 1),
('2017-12-03', 87, 0),
('2017-12-04', 88, 1),
('2017-12-05', 89, 1),
('2017-12-06', 90, 1),
('2017-12-07', 91, 1),
('2017-12-08', 92, 1),
('2017-12-09', 93, 1),
('2017-12-10', 93, 0),
('2017-12-11', 94, 1),
('2017-12-12', 95, 1),
('2017-12-13', 96, 1),
('2017-12-14', 97, 1),
('2017-12-15', 98, 1),
('2017-12-16', 99, 1),
('2017-12-17', 99, 0),
('2017-12-18', 100, 1),
('2017-12-19', 101, 1),
('2017-12-20', 102, 1),
('2017-12-21', 103, 1),
('2017-12-22', 104, 1),
('2017-12-23', 105, 1),
('2017-12-24', 105, 0),
('2017-12-25', 106, 1),
('2017-12-26', 107, 1),
('2017-12-27', 108, 1),
('2017-12-28', 109, 1),
('2017-12-29', 110, 1),
('2017-12-30', 111, 1),
('2017-12-31', 111, 0),
('2018-01-01', 112, 1),
('2018-01-02', 113, 1),
('2018-01-03', 114, 1),
('2018-01-04', 115, 1),
('2018-01-05', 116, 1),
('2018-01-06', 117, 1),
('2018-01-07', 117, 0),
('2018-01-08', 118, 1),
('2018-01-09', 119, 1),
('2018-01-10', 120, 1),
('2018-01-11', 121, 1),
('2018-01-12', 122, 1),
('2018-01-13', 123, 1),
('2018-01-14', 123, 0),
('2018-01-15', 124, 1),
('2018-01-16', 125, 1),
('2018-01-17', 126, 1),
('2018-01-18', 127, 1),
('2018-01-19', 128, 1),
('2018-01-20', 129, 1),
('2018-01-21', 129, 0),
('2018-01-22', 130, 1),
('2018-01-23', 131, 1),
('2018-01-24', 132, 1),
('2018-01-25', 133, 1),
('2018-01-26', 134, 1),
('2018-01-27', 135, 1),
('2018-01-28', 135, 0),
('2018-01-29', 136, 1),
('2018-01-30', 137, 1),
('2018-01-31', 138, 1),
('2018-02-01', 139, 1),
('2018-02-02', 140, 1),
('2018-02-03', 141, 1),
('2018-02-04', 141, 0),
('2018-02-05', 142, 1),
('2018-02-06', 143, 1),
('2018-02-07', 144, 1),
('2018-02-08', 145, 1),
('2018-02-09', 146, 1),
('2018-02-10', 147, 1),
('2018-02-11', 147, 0),
('2018-02-12', 148, 1),
('2018-02-13', 149, 1),
('2018-02-14', 150, 1),
('2018-02-15', 151, 1),
('2018-02-16', 152, 1),
('2018-02-17', 153, 1),
('2018-02-18', 153, 0),
('2018-02-19', 154, 1),
('2018-02-20', 155, 1),
('2018-02-21', 156, 1),
('2018-02-22', 157, 1),
('2018-02-23', 158, 1),
('2018-02-24', 159, 1),
('2018-02-25', 159, 0),
('2018-02-26', 160, 1),
('2018-02-27', 161, 1),
('2018-02-28', 162, 1),
('2018-03-01', 163, 1),
('2018-03-02', 164, 1),
('2018-03-03', 165, 1),
('2018-03-04', 165, 0),
('2018-03-05', 166, 1),
('2018-03-06', 167, 1),
('2018-03-07', 168, 1),
('2018-03-08', 169, 1),
('2018-03-09', 170, 1),
('2018-03-10', 171, 1),
('2018-03-11', 171, 0),
('2018-03-12', 172, 1),
('2018-03-13', 173, 1),
('2018-03-14', 174, 1),
('2018-03-15', 175, 1),
('2018-03-16', 176, 1),
('2018-03-17', 177, 1),
('2018-03-18', 177, 0),
('2018-03-19', 178, 1),
('2018-03-20', 179, 1),
('2018-03-21', 180, 1),
('2018-03-22', 181, 1),
('2018-03-23', 182, 1),
('2018-03-24', 183, 1),
('2018-03-25', 183, 0),
('2018-03-26', 184, 1),
('2018-03-27', 185, 1),
('2018-03-28', 186, 1),
('2018-03-29', 187, 1),
('2018-03-30', 188, 1),
('2018-03-31', 189, 1),
('2018-04-01', 189, 0),
('2018-04-02', 190, 1),
('2018-04-03', 191, 1),
('2018-04-04', 192, 1),
('2018-04-05', 193, 1),
('2018-04-06', 194, 1),
('2018-04-07', 195, 1),
('2018-04-08', 195, 0),
('2018-04-09', 196, 1),
('2018-04-10', 197, 1),
('2018-04-11', 198, 1),
('2018-04-12', 199, 1),
('2018-04-13', 200, 1),
('2018-04-14', 201, 1),
('2018-04-15', 201, 0),
('2018-04-16', 202, 1),
('2018-04-17', 203, 1),
('2018-04-18', 204, 1),
('2018-04-19', 205, 1),
('2018-04-20', 206, 1),
('2018-04-21', 207, 1),
('2018-04-22', 207, 0),
('2018-04-23', 208, 1),
('2018-04-24', 209, 1),
('2018-04-25', 210, 1),
('2018-04-26', 211, 1),
('2018-04-27', 212, 1),
('2018-04-28', 213, 1),
('2018-04-29', 213, 0),
('2018-04-30', 214, 1),
('2018-05-01', 215, 1),
('2018-05-02', 216, 1),
('2018-05-03', 217, 1),
('2018-05-04', 218, 1),
('2018-05-05', 219, 1),
('2018-05-06', 219, 0),
('2018-05-07', 220, 1),
('2018-05-08', 221, 1),
('2018-05-09', 222, 1),
('2018-05-10', 223, 1),
('2018-05-11', 224, 1),
('2018-05-12', 225, 1),
('2018-05-13', 225, 0),
('2018-05-14', 226, 1),
('2018-05-15', 227, 1),
('2018-05-16', 228, 1),
('2018-05-17', 229, 1),
('2018-05-18', 230, 1),
('2018-05-19', 231, 1),
('2018-05-20', 231, 0),
('2018-05-21', 232, 1),
('2018-05-22', 233, 1),
('2018-05-23', 234, 1),
('2018-05-24', 235, 1),
('2018-05-25', 236, 1),
('2018-05-26', 237, 1),
('2018-05-27', 237, 0),
('2018-05-28', 238, 1),
('2018-05-29', 239, 1),
('2018-05-30', 240, 1),
('2018-05-31', 241, 1),
('2018-06-01', 242, 1),
('2018-06-02', 243, 1),
('2018-06-03', 243, 0),
('2018-06-04', 244, 1),
('2018-06-05', 245, 1),
('2018-06-06', 246, 1),
('2018-06-07', 247, 1),
('2018-06-08', 248, 1),
('2018-06-09', 249, 1),
('2018-06-10', 249, 0),
('2018-06-11', 250, 1),
('2018-06-12', 251, 1),
('2018-06-13', 252, 1),
('2018-06-14', 253, 1),
('2018-06-15', 254, 1),
('2018-06-16', 255, 1),
('2018-06-17', 255, 0),
('2018-06-18', 256, 1),
('2018-06-19', 257, 1),
('2018-06-20', 258, 1),
('2018-06-21', 259, 1),
('2018-06-22', 260, 1),
('2018-06-23', 261, 1),
('2018-06-24', 261, 0),
('2018-06-25', 262, 1),
('2018-06-26', 263, 1),
('2018-06-27', 264, 1),
('2018-06-28', 265, 1),
('2018-06-29', 266, 1),
('2018-06-30', 267, 1),
('2018-07-01', 267, 0),
('2018-07-02', 268, 1),
('2018-07-03', 269, 1),
('2018-07-04', 270, 1),
('2018-07-05', 271, 1),
('2018-07-06', 272, 1),
('2018-07-07', 273, 1),
('2018-07-08', 273, 0),
('2018-07-09', 274, 1),
('2018-07-10', 275, 1),
('2018-07-11', 276, 1),
('2018-07-12', 277, 1),
('2018-07-13', 278, 1),
('2018-07-14', 279, 1),
('2018-07-15', 279, 0),
('2018-07-16', 280, 1),
('2018-07-17', 281, 1),
('2018-07-18', 282, 1),
('2018-07-19', 283, 1),
('2018-07-20', 284, 1),
('2018-07-21', 285, 1),
('2018-07-22', 285, 0),
('2018-07-23', 286, 1),
('2018-07-24', 287, 1),
('2018-07-25', 288, 1),
('2018-07-26', 289, 1),
('2018-07-27', 290, 1),
('2018-07-28', 291, 1),
('2018-07-29', 291, 0),
('2018-07-30', 292, 1),
('2018-07-31', 293, 1),
('2018-08-01', 294, 1),
('2018-08-02', 295, 1),
('2018-08-03', 296, 1),
('2018-08-04', 297, 1),
('2018-08-05', 297, 0),
('2018-08-06', 298, 1),
('2018-08-07', 299, 1),
('2018-08-08', 300, 1),
('2018-08-09', 301, 1),
('2018-08-10', 302, 1),
('2018-08-11', 303, 1),
('2018-08-12', 303, 0),
('2018-08-13', 304, 1),
('2018-08-14', 305, 1),
('2018-08-15', 306, 1),
('2018-08-16', 307, 1),
('2018-08-17', 308, 1),
('2018-08-18', 309, 1),
('2018-08-19', 309, 0),
('2018-08-20', 310, 1),
('2018-08-21', 311, 1),
('2018-08-22', 312, 1),
('2018-08-23', 313, 1),
('2018-08-24', 314, 1),
('2018-08-25', 315, 1),
('2018-08-26', 315, 0),
('2018-08-27', 316, 1),
('2018-08-28', 317, 1),
('2018-08-29', 318, 1),
('2018-08-30', 319, 1),
('2018-08-31', 320, 1),
('2018-09-01', 321, 1),
('2018-09-02', 321, 0),
('2018-09-03', 322, 1),
('2018-09-04', 323, 1),
('2018-09-05', 324, 1),
('2018-09-06', 325, 1),
('2018-09-07', 326, 1),
('2018-09-08', 327, 1),
('2018-09-09', 327, 0),
('2018-09-10', 328, 1),
('2018-09-11', 329, 1),
('2018-09-12', 330, 1),
('2018-09-13', 331, 1),
('2018-09-14', 332, 1),
('2018-09-15', 333, 1),
('2018-09-16', 333, 0),
('2018-09-17', 334, 1),
('2018-09-18', 335, 1),
('2018-09-19', 336, 1),
('2018-09-20', 337, 1),
('2018-09-21', 338, 1),
('2018-09-22', 339, 1),
('2018-09-23', 339, 0),
('2018-09-24', 340, 1),
('2018-09-25', 341, 1),
('2018-09-26', 342, 1),
('2018-09-27', 343, 1),
('2018-09-28', 344, 1),
('2018-09-29', 345, 1),
('2018-09-30', 345, 0),
('2018-10-01', 346, 1),
('2018-10-02', 347, 1),
('2018-10-03', 348, 1),
('2018-10-04', 349, 1),
('2018-10-05', 350, 1),
('2018-10-06', 351, 1),
('2018-10-07', 351, 0),
('2018-10-08', 352, 1),
('2018-10-09', 353, 1),
('2018-10-10', 354, 1),
('2018-10-11', 355, 1),
('2018-10-12', 356, 1),
('2018-10-13', 357, 1),
('2018-10-14', 357, 0),
('2018-10-15', 358, 1),
('2018-10-16', 359, 1),
('2018-10-17', 360, 1),
('2018-10-18', 361, 1),
('2018-10-19', 362, 1),
('2018-10-20', 363, 1),
('2018-10-21', 363, 0),
('2018-10-22', 364, 1),
('2018-10-23', 365, 1),
('2018-10-24', 366, 1),
('2018-10-25', 367, 1),
('2018-10-26', 368, 1),
('2018-10-27', 369, 1),
('2018-10-28', 369, 0),
('2018-10-29', 370, 1),
('2018-10-30', 371, 1),
('2018-10-31', 372, 1),
('2018-11-01', 373, 1),
('2018-11-02', 374, 1),
('2018-11-03', 375, 1),
('2018-11-04', 375, 0),
('2018-11-05', 376, 1),
('2018-11-06', 377, 1),
('2018-11-07', 378, 1),
('2018-11-08', 379, 1),
('2018-11-09', 380, 1),
('2018-11-10', 381, 1),
('2018-11-11', 381, 0),
('2018-11-12', 382, 1),
('2018-11-13', 383, 1),
('2018-11-14', 384, 1),
('2018-11-15', 385, 1),
('2018-11-16', 386, 1),
('2018-11-17', 387, 1),
('2018-11-18', 387, 0),
('2018-11-19', 388, 1),
('2018-11-20', 389, 1),
('2018-11-21', 390, 1),
('2018-11-22', 391, 1),
('2018-11-23', 392, 1),
('2018-11-24', 393, 1),
('2018-11-25', 393, 0),
('2018-11-26', 394, 1),
('2018-11-27', 395, 1),
('2018-11-28', 396, 1),
('2018-11-29', 397, 1),
('2018-11-30', 398, 1),
('2018-12-01', 399, 1),
('2018-12-02', 399, 0),
('2018-12-03', 400, 1),
('2018-12-04', 401, 1),
('2018-12-05', 402, 1),
('2018-12-06', 403, 1),
('2018-12-07', 404, 1),
('2018-12-08', 405, 1),
('2018-12-09', 405, 0),
('2018-12-10', 406, 1),
('2018-12-11', 407, 1),
('2018-12-12', 408, 1),
('2018-12-13', 409, 1),
('2018-12-14', 410, 1),
('2018-12-15', 411, 1),
('2018-12-16', 411, 0),
('2018-12-17', 412, 1),
('2018-12-18', 413, 1),
('2018-12-19', 414, 1),
('2018-12-20', 415, 1),
('2018-12-21', 416, 1),
('2018-12-22', 417, 1),
('2018-12-23', 417, 0),
('2018-12-24', 418, 1),
('2018-12-25', 419, 1),
('2018-12-26', 420, 1),
('2018-12-27', 421, 1),
('2018-12-28', 422, 1),
('2018-12-29', 423, 1),
('2018-12-30', 423, 0),
('2018-12-31', 424, 1),
('2019-01-01', 425, 1),
('2019-01-02', 426, 1),
('2019-01-03', 427, 1),
('2019-01-04', 428, 1),
('2019-01-05', 429, 1),
('2019-01-06', 429, 0),
('2019-01-07', 430, 1),
('2019-01-08', 431, 1),
('2019-01-09', 432, 1),
('2019-01-10', 433, 1),
('2019-01-11', 434, 1),
('2019-01-12', 435, 1),
('2019-01-13', 435, 0),
('2019-01-14', 436, 1),
('2019-01-15', 437, 1),
('2019-01-16', 438, 1),
('2019-01-17', 439, 1),
('2019-01-18', 440, 1),
('2019-01-19', 441, 1),
('2019-01-20', 441, 0),
('2019-01-21', 442, 1),
('2019-01-22', 443, 1),
('2019-01-23', 444, 1),
('2019-01-24', 445, 1),
('2019-01-25', 446, 1),
('2019-01-26', 447, 1),
('2019-01-27', 447, 0),
('2019-01-28', 448, 1),
('2019-01-29', 449, 1),
('2019-01-30', 450, 1),
('2019-01-31', 451, 1),
('2019-02-01', 452, 1),
('2019-02-02', 453, 1),
('2019-02-03', 453, 0),
('2019-02-04', 454, 1),
('2019-02-05', 455, 1),
('2019-02-06', 456, 1),
('2019-02-07', 457, 1),
('2019-02-08', 458, 1),
('2019-02-09', 459, 1),
('2019-02-10', 459, 0),
('2019-02-11', 460, 1),
('2019-02-12', 461, 1),
('2019-02-13', 462, 1),
('2019-02-14', 463, 1),
('2019-02-15', 464, 1),
('2019-02-16', 465, 1),
('2019-02-17', 465, 0),
('2019-02-18', 466, 1),
('2019-02-19', 467, 1),
('2019-02-20', 468, 1),
('2019-02-21', 469, 1),
('2019-02-22', 470, 1),
('2019-02-23', 471, 1),
('2019-02-24', 471, 0),
('2019-02-25', 472, 1),
('2019-02-26', 473, 1),
('2019-02-27', 474, 1),
('2019-02-28', 475, 1),
('2019-03-01', 476, 1),
('2019-03-02', 477, 1),
('2019-03-03', 477, 0),
('2019-03-04', 478, 1),
('2019-03-05', 479, 1),
('2019-03-06', 480, 1),
('2019-03-07', 481, 1),
('2019-03-08', 482, 1),
('2019-03-09', 483, 1),
('2019-03-10', 483, 0),
('2019-03-11', 484, 1),
('2019-03-12', 485, 1),
('2019-03-13', 486, 1),
('2019-03-14', 487, 1),
('2019-03-15', 488, 1),
('2019-03-16', 489, 1),
('2019-03-17', 489, 0),
('2019-03-18', 490, 1),
('2019-03-19', 491, 1),
('2019-03-20', 492, 1),
('2019-03-21', 493, 1),
('2019-03-22', 494, 1),
('2019-03-23', 495, 1),
('2019-03-24', 495, 0),
('2019-03-25', 496, 1),
('2019-03-26', 497, 1),
('2019-03-27', 498, 1),
('2019-03-28', 499, 1),
('2019-03-29', 500, 1),
('2019-03-30', 501, 1),
('2019-03-31', 501, 0),
('2019-04-01', 502, 1),
('2019-04-02', 503, 1),
('2019-04-03', 504, 1),
('2019-04-04', 505, 1),
('2019-04-05', 506, 1),
('2019-04-06', 507, 1),
('2019-04-07', 507, 0),
('2019-04-08', 508, 1),
('2019-04-09', 509, 1),
('2019-04-10', 510, 1),
('2019-04-11', 511, 1),
('2019-04-12', 512, 1),
('2019-04-13', 513, 1),
('2019-04-14', 513, 0),
('2019-04-15', 514, 1),
('2019-04-16', 515, 1),
('2019-04-17', 516, 1),
('2019-04-18', 517, 1),
('2019-04-19', 518, 1),
('2019-04-20', 519, 1),
('2019-04-21', 519, 0),
('2019-04-22', 520, 1),
('2019-04-23', 521, 1),
('2019-04-24', 522, 1),
('2019-04-25', 523, 1),
('2019-04-26', 524, 1),
('2019-04-27', 525, 1),
('2019-04-28', 525, 0),
('2019-04-29', 526, 1),
('2019-04-30', 527, 1),
('2019-05-01', 528, 1),
('2019-05-02', 529, 1),
('2019-05-03', 530, 1),
('2019-05-04', 531, 1),
('2019-05-05', 531, 0),
('2019-05-06', 532, 1),
('2019-05-07', 533, 1),
('2019-05-08', 534, 1),
('2019-05-09', 535, 1),
('2019-05-10', 536, 1),
('2019-05-11', 537, 1),
('2019-05-12', 537, 0),
('2019-05-13', 538, 1),
('2019-05-14', 539, 1),
('2019-05-15', 540, 1),
('2019-05-16', 541, 1),
('2019-05-17', 542, 1),
('2019-05-18', 543, 1),
('2019-05-19', 543, 0),
('2019-05-20', 544, 1),
('2019-05-21', 545, 1),
('2019-05-22', 546, 1),
('2019-05-23', 547, 1),
('2019-05-24', 548, 1),
('2019-05-25', 549, 1),
('2019-05-26', 549, 0),
('2019-05-27', 550, 1),
('2019-05-28', 551, 1),
('2019-05-29', 552, 1),
('2019-05-30', 553, 1),
('2019-05-31', 554, 1),
('2019-06-01', 555, 1),
('2019-06-02', 555, 0),
('2019-06-03', 556, 1),
('2019-06-04', 557, 1),
('2019-06-05', 558, 1),
('2019-06-06', 559, 1),
('2019-06-07', 560, 1),
('2019-06-08', 561, 1),
('2019-06-09', 561, 0),
('2019-06-10', 562, 1),
('2019-06-11', 563, 1),
('2019-06-12', 564, 1),
('2019-06-13', 565, 1),
('2019-06-14', 566, 1),
('2019-06-15', 567, 1),
('2019-06-16', 567, 0),
('2019-06-17', 568, 1),
('2019-06-18', 569, 1),
('2019-06-19', 570, 1),
('2019-06-20', 571, 1),
('2019-06-21', 572, 1),
('2019-06-22', 573, 1),
('2019-06-23', 573, 0),
('2019-06-24', 574, 1),
('2019-06-25', 575, 1),
('2019-06-26', 576, 1),
('2019-06-27', 577, 1),
('2019-06-28', 578, 1),
('2019-06-29', 579, 1),
('2019-06-30', 579, 0),
('2019-07-01', 580, 1),
('2019-07-02', 581, 1),
('2019-07-03', 582, 1),
('2019-07-04', 583, 1),
('2019-07-05', 584, 1),
('2019-07-06', 585, 1),
('2019-07-07', 585, 0),
('2019-07-08', 586, 1),
('2019-07-09', 587, 1),
('2019-07-10', 588, 1),
('2019-07-11', 589, 1),
('2019-07-12', 590, 1),
('2019-07-13', 591, 1),
('2019-07-14', 591, 0),
('2019-07-15', 592, 1),
('2019-07-16', 593, 1),
('2019-07-17', 594, 1),
('2019-07-18', 595, 1),
('2019-07-19', 596, 1),
('2019-07-20', 597, 1),
('2019-07-21', 597, 0),
('2019-07-22', 598, 1),
('2019-07-23', 599, 1),
('2019-07-24', 600, 1),
('2019-07-25', 601, 1),
('2019-07-26', 602, 1),
('2019-07-27', 603, 1),
('2019-07-28', 603, 0),
('2019-07-29', 604, 1),
('2019-07-30', 605, 1),
('2019-07-31', 606, 1),
('2019-08-01', 607, 1),
('2019-08-02', 608, 1),
('2019-08-03', 609, 1),
('2019-08-04', 609, 0),
('2019-08-05', 610, 1),
('2019-08-06', 611, 1),
('2019-08-07', 612, 1),
('2019-08-08', 613, 1),
('2019-08-09', 614, 1),
('2019-08-10', 615, 1),
('2019-08-11', 615, 0),
('2019-08-12', 616, 1),
('2019-08-13', 617, 1),
('2019-08-14', 618, 1),
('2019-08-15', 619, 1),
('2019-08-16', 620, 1),
('2019-08-17', 621, 1),
('2019-08-18', 621, 0),
('2019-08-19', 622, 1),
('2019-08-20', 623, 1),
('2019-08-21', 624, 1),
('2019-08-22', 625, 1),
('2019-08-23', 626, 1),
('2019-08-24', 627, 1),
('2019-08-25', 627, 0);

-- --------------------------------------------------------

--
-- Table structure for table `mrpdemands`
--

CREATE TABLE `mrpdemands` (
  `demandid` int(11) NOT NULL,
  `stockid` varchar(20) NOT NULL DEFAULT '',
  `mrpdemandtype` varchar(6) NOT NULL DEFAULT '',
  `quantity` double NOT NULL DEFAULT '0',
  `duedate` date NOT NULL DEFAULT '0000-00-00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `mrpdemandtypes`
--

CREATE TABLE `mrpdemandtypes` (
  `mrpdemandtype` varchar(6) NOT NULL DEFAULT '',
  `description` char(30) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `mrpdemandtypes`
--

INSERT INTO `mrpdemandtypes` (`mrpdemandtype`, `description`) VALUES
('FORCA', 'Forecast');

-- --------------------------------------------------------

--
-- Table structure for table `mrpplannedorders`
--

CREATE TABLE `mrpplannedorders` (
  `id` int(11) NOT NULL,
  `part` char(20) DEFAULT NULL,
  `duedate` date DEFAULT NULL,
  `supplyquantity` double DEFAULT NULL,
  `ordertype` varchar(6) DEFAULT NULL,
  `orderno` int(11) DEFAULT NULL,
  `mrpdate` date DEFAULT NULL,
  `updateflag` smallint(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `offers`
--

CREATE TABLE `offers` (
  `offerid` int(11) NOT NULL,
  `tenderid` int(11) NOT NULL DEFAULT '0',
  `supplierid` varchar(10) NOT NULL DEFAULT '',
  `stockid` varchar(20) NOT NULL DEFAULT '',
  `quantity` double NOT NULL DEFAULT '0',
  `uom` varchar(15) NOT NULL DEFAULT '',
  `price` double NOT NULL DEFAULT '0',
  `expirydate` date NOT NULL DEFAULT '0000-00-00',
  `currcode` char(3) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `orderdeliverydifferenceslog`
--

CREATE TABLE `orderdeliverydifferenceslog` (
  `orderno` int(11) NOT NULL DEFAULT '0',
  `invoiceno` int(11) NOT NULL DEFAULT '0',
  `stockid` varchar(20) NOT NULL DEFAULT '',
  `quantitydiff` double NOT NULL DEFAULT '0',
  `debtorno` varchar(10) NOT NULL DEFAULT '',
  `branch` varchar(10) NOT NULL DEFAULT '',
  `can_or_bo` char(3) NOT NULL DEFAULT 'CAN'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `paymentmethods`
--

CREATE TABLE `paymentmethods` (
  `paymentid` tinyint(4) NOT NULL,
  `paymentname` varchar(15) NOT NULL DEFAULT '',
  `paymenttype` int(11) NOT NULL DEFAULT '1',
  `receipttype` int(11) NOT NULL DEFAULT '1',
  `usepreprintedstationery` tinyint(4) NOT NULL DEFAULT '0',
  `opencashdrawer` tinyint(4) NOT NULL DEFAULT '0',
  `percentdiscount` double NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `paymentmethods`
--

INSERT INTO `paymentmethods` (`paymentid`, `paymentname`, `paymenttype`, `receipttype`, `usepreprintedstationery`, `opencashdrawer`, `percentdiscount`) VALUES
(1, 'Cheque', 1, 1, 1, 0, 0),
(2, 'Cash', 1, 1, 0, 0, 0.25),
(3, 'Direct Credit', 1, 1, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `paymentterms`
--

CREATE TABLE `paymentterms` (
  `termsindicator` char(2) NOT NULL DEFAULT '',
  `terms` char(40) NOT NULL DEFAULT '',
  `daysbeforedue` smallint(6) NOT NULL DEFAULT '0',
  `dayinfollowingmonth` smallint(6) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `paymentterms`
--

INSERT INTO `paymentterms` (`termsindicator`, `terms`, `daysbeforedue`, `dayinfollowingmonth`) VALUES
('20', 'Due 20th Of the Following Month', 0, 20),
('30', 'Due By End Of The Following Month', 0, 30),
('7', 'Payment due within 7 days', 7, 0),
('CA', 'Immediate', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `pcashdetails`
--

CREATE TABLE `pcashdetails` (
  `counterindex` int(20) NOT NULL,
  `tabcode` varchar(20) NOT NULL,
  `tag` int(11) NOT NULL DEFAULT '0',
  `date` date NOT NULL,
  `codeexpense` varchar(20) NOT NULL,
  `amount` double NOT NULL,
  `authorized` date NOT NULL COMMENT 'date cash assigment was revised and authorized by authorizer from tabs table',
  `posted` tinyint(4) NOT NULL COMMENT 'has (or has not) been posted into gltrans',
  `purpose` text,
  `notes` text NOT NULL,
  `receipt` text COMMENT 'Column redundant. Replaced by receipt file upload. Nov 2017.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pcashdetailtaxes`
--

CREATE TABLE `pcashdetailtaxes` (
  `counterindex` int(20) NOT NULL,
  `pccashdetail` int(20) NOT NULL DEFAULT '0',
  `calculationorder` tinyint(4) NOT NULL DEFAULT '0',
  `description` varchar(40) NOT NULL DEFAULT '',
  `taxauthid` tinyint(4) NOT NULL DEFAULT '0',
  `purchtaxglaccount` varchar(20) NOT NULL DEFAULT '',
  `taxontax` tinyint(4) NOT NULL DEFAULT '0',
  `taxrate` double NOT NULL DEFAULT '0',
  `amount` double NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pcexpenses`
--

CREATE TABLE `pcexpenses` (
  `codeexpense` varchar(20) NOT NULL COMMENT 'code for the group',
  `description` varchar(50) NOT NULL COMMENT 'text description, e.g. meals, train tickets, fuel, etc',
  `glaccount` varchar(20) NOT NULL DEFAULT '0',
  `tag` tinyint(4) NOT NULL DEFAULT '0',
  `taxcatid` tinyint(4) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pcexpenses`
--

INSERT INTO `pcexpenses` (`codeexpense`, `description`, `glaccount`, `tag`, `taxcatid`) VALUES
('BT', 'Bus Tickets', '1010', 1, 6),
('Petrol', 'Petrol', '1010', 1, 4);

-- --------------------------------------------------------

--
-- Table structure for table `pcreceipts`
--

CREATE TABLE `pcreceipts` (
  `counterindex` int(20) NOT NULL,
  `pccashdetail` int(20) NOT NULL DEFAULT '0' COMMENT 'Expenses record identity',
  `hashfile` varchar(32) NOT NULL DEFAULT '' COMMENT 'MD5 hash of uploaded receipt file',
  `type` varchar(80) NOT NULL DEFAULT '' COMMENT 'Mime type of uploaded receipt file',
  `extension` varchar(4) NOT NULL DEFAULT '' COMMENT 'File extension of uploaded receipt',
  `size` int(20) NOT NULL DEFAULT '0' COMMENT 'File size of uploaded receipt'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pctabexpenses`
--

CREATE TABLE `pctabexpenses` (
  `typetabcode` varchar(20) NOT NULL,
  `codeexpense` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pctabexpenses`
--

INSERT INTO `pctabexpenses` (`typetabcode`, `codeexpense`) VALUES
('TRAVEL', 'BT'),
('VEHICLE', 'Petrol');

-- --------------------------------------------------------

--
-- Table structure for table `pctabs`
--

CREATE TABLE `pctabs` (
  `tabcode` varchar(20) NOT NULL,
  `usercode` varchar(20) NOT NULL COMMENT 'code of user employee from www_users',
  `typetabcode` varchar(20) NOT NULL,
  `currency` char(3) NOT NULL,
  `tablimit` double NOT NULL,
  `assigner` varchar(100) DEFAULT NULL,
  `authorizer` varchar(100) DEFAULT NULL,
  `authorizerexpenses` varchar(20) NOT NULL,
  `glaccountassignment` varchar(20) NOT NULL DEFAULT '0',
  `glaccountpcash` varchar(20) NOT NULL DEFAULT '0',
  `defaulttag` tinyint(4) NOT NULL DEFAULT '0',
  `taxgroupid` tinyint(4) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pctabs`
--

INSERT INTO `pctabs` (`tabcode`, `usercode`, `typetabcode`, `currency`, `tablimit`, `assigner`, `authorizer`, `authorizerexpenses`, `glaccountassignment`, `glaccountpcash`, `defaulttag`, `taxgroupid`) VALUES
('Fuel', 'admin', 'Vehicle', 'INR', 10000, 'admin', 'admin', 'admin', '1030', '1010', 1, 1),
('Tickets', 'admin', 'Travel', 'INR', 10000, 'admin', 'admin', 'admin', '1030', '1010', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `pctypetabs`
--

CREATE TABLE `pctypetabs` (
  `typetabcode` varchar(20) NOT NULL COMMENT 'code for the type of petty cash tab',
  `typetabdescription` varchar(50) NOT NULL COMMENT 'text description, e.g. tab for CEO'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pctypetabs`
--

INSERT INTO `pctypetabs` (`typetabcode`, `typetabdescription`) VALUES
('Travel', 'Travelling Expenses'),
('Vehicle', 'Vehicles');

-- --------------------------------------------------------

--
-- Table structure for table `periods`
--

CREATE TABLE `periods` (
  `periodno` smallint(6) NOT NULL DEFAULT '0',
  `lastdate_in_period` date NOT NULL DEFAULT '0000-00-00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `periods`
--

INSERT INTO `periods` (`periodno`, `lastdate_in_period`) VALUES
(-15, '2017-04-30'),
(-14, '2017-05-31'),
(-13, '2017-06-30'),
(-12, '2017-07-31'),
(-11, '2017-08-31'),
(-10, '2017-09-30'),
(-9, '2017-10-31'),
(-8, '2017-11-30'),
(-7, '2017-12-31'),
(-6, '2018-01-31'),
(-5, '2018-02-28'),
(-4, '2018-03-31'),
(-3, '2018-04-30'),
(-2, '2018-05-31'),
(-1, '2018-06-30'),
(0, '2018-07-31'),
(1, '2018-08-31'),
(2, '2018-09-30'),
(3, '2018-10-31'),
(4, '2018-11-30'),
(5, '2018-12-31'),
(6, '2019-01-31'),
(7, '2019-02-28'),
(8, '2019-03-31'),
(9, '2019-04-30'),
(10, '2019-05-31'),
(11, '2019-06-30'),
(12, '2019-07-31'),
(13, '2019-08-31'),
(14, '2019-09-30'),
(15, '2019-10-31'),
(16, '2019-11-30'),
(17, '2019-12-31'),
(18, '2020-01-31'),
(19, '2020-02-29'),
(20, '2020-03-31'),
(21, '2020-04-30'),
(22, '2020-05-31'),
(23, '2020-06-30'),
(24, '2020-07-31'),
(25, '2020-08-31'),
(26, '2020-09-30'),
(27, '2020-10-31'),
(28, '2020-11-30'),
(29, '2020-12-31'),
(30, '2021-01-31'),
(31, '2021-02-28'),
(32, '2021-03-31'),
(33, '2021-04-30'),
(34, '2021-05-31'),
(35, '2021-06-30'),
(36, '2021-07-31'),
(37, '2021-08-31'),
(38, '2021-09-30'),
(39, '2021-10-31'),
(40, '2021-11-30'),
(41, '2021-12-31'),
(42, '2022-01-31'),
(43, '2022-02-28'),
(44, '2022-03-31'),
(45, '2022-04-30'),
(46, '2022-05-31'),
(47, '2022-06-30'),
(48, '2022-07-31'),
(49, '2022-08-31'),
(50, '2022-09-30'),
(51, '2022-10-31'),
(52, '2022-11-30'),
(53, '2022-12-31'),
(54, '2023-01-31'),
(55, '2023-02-28'),
(56, '2023-03-31'),
(57, '2023-04-30'),
(58, '2023-05-31'),
(59, '2023-06-30');

-- --------------------------------------------------------

--
-- Table structure for table `pickinglistdetails`
--

CREATE TABLE `pickinglistdetails` (
  `pickinglistno` int(11) NOT NULL DEFAULT '0',
  `pickinglistlineno` int(11) NOT NULL DEFAULT '0',
  `orderlineno` int(11) NOT NULL DEFAULT '0',
  `qtyexpected` double NOT NULL DEFAULT '0',
  `qtypicked` double NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pickinglists`
--

CREATE TABLE `pickinglists` (
  `pickinglistno` int(11) NOT NULL DEFAULT '0',
  `orderno` int(11) NOT NULL DEFAULT '0',
  `pickinglistdate` date NOT NULL DEFAULT '0000-00-00',
  `dateprinted` date NOT NULL DEFAULT '0000-00-00',
  `deliverynotedate` date NOT NULL DEFAULT '0000-00-00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pickreq`
--

CREATE TABLE `pickreq` (
  `prid` int(11) NOT NULL,
  `initiator` varchar(20) NOT NULL DEFAULT '',
  `shippedby` varchar(20) NOT NULL DEFAULT '',
  `initdate` date NOT NULL DEFAULT '0000-00-00',
  `requestdate` date NOT NULL DEFAULT '0000-00-00',
  `shipdate` date NOT NULL DEFAULT '0000-00-00',
  `status` varchar(12) NOT NULL DEFAULT '',
  `comments` text,
  `closed` tinyint(4) NOT NULL DEFAULT '0',
  `loccode` varchar(5) NOT NULL DEFAULT '',
  `orderno` int(11) NOT NULL DEFAULT '1',
  `consignment` varchar(15) NOT NULL DEFAULT '',
  `packages` int(11) NOT NULL DEFAULT '1' COMMENT 'number of cartons'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pickreqdetails`
--

CREATE TABLE `pickreqdetails` (
  `detailno` int(11) NOT NULL,
  `prid` int(11) NOT NULL DEFAULT '1',
  `orderlineno` int(11) NOT NULL DEFAULT '0',
  `stockid` varchar(20) NOT NULL DEFAULT '',
  `qtyexpected` double NOT NULL DEFAULT '0',
  `qtypicked` double NOT NULL DEFAULT '0',
  `invoicedqty` double NOT NULL DEFAULT '0',
  `shipqty` double NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pickserialdetails`
--

CREATE TABLE `pickserialdetails` (
  `serialmoveid` int(11) NOT NULL,
  `detailno` int(11) NOT NULL DEFAULT '1',
  `stockid` varchar(20) NOT NULL DEFAULT '',
  `serialno` varchar(30) NOT NULL DEFAULT '',
  `moveqty` double NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pricematrix`
--

CREATE TABLE `pricematrix` (
  `salestype` char(2) NOT NULL DEFAULT '',
  `stockid` varchar(20) NOT NULL DEFAULT '',
  `quantitybreak` int(11) NOT NULL DEFAULT '1',
  `price` double NOT NULL DEFAULT '0',
  `currabrev` char(3) NOT NULL DEFAULT '',
  `startdate` date NOT NULL DEFAULT '0000-00-00',
  `enddate` date NOT NULL DEFAULT '9999-12-31'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `prices`
--

CREATE TABLE `prices` (
  `stockid` varchar(20) NOT NULL DEFAULT '',
  `typeabbrev` char(2) NOT NULL DEFAULT '',
  `currabrev` char(3) NOT NULL DEFAULT '',
  `debtorno` varchar(10) NOT NULL DEFAULT '',
  `price` decimal(20,4) NOT NULL DEFAULT '0.0000',
  `branchcode` varchar(10) NOT NULL DEFAULT '',
  `startdate` date NOT NULL DEFAULT '0000-00-00',
  `enddate` date NOT NULL DEFAULT '9999-12-31'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `prices`
--

INSERT INTO `prices` (`stockid`, `typeabbrev`, `currabrev`, `debtorno`, `price`, `branchcode`, `startdate`, `enddate`) VALUES
('HDD128', 'De', 'INR', '', '3000.0000', '', '2018-09-04', '9999-12-31'),
('RAM4', 'De', 'INR', '', '1500.0000', '', '2018-09-04', '9999-12-31'),
('S9', '2', 'INR', '', '45000.0000', '', '2018-09-04', '9999-12-31'),
('S9', 'De', 'INR', '', '50000.0000', '', '2018-09-04', '9999-12-31'),
('SCREEN6', 'De', 'INR', '', '1500.0000', '', '2018-09-04', '9999-12-31');

-- --------------------------------------------------------

--
-- Table structure for table `prodspecs`
--

CREATE TABLE `prodspecs` (
  `keyval` varchar(25) NOT NULL,
  `testid` int(11) NOT NULL,
  `defaultvalue` varchar(150) NOT NULL DEFAULT '',
  `targetvalue` varchar(30) NOT NULL DEFAULT '',
  `rangemin` float DEFAULT NULL,
  `rangemax` float DEFAULT NULL,
  `showoncert` tinyint(11) NOT NULL DEFAULT '1',
  `showonspec` tinyint(4) NOT NULL DEFAULT '1',
  `showontestplan` tinyint(4) NOT NULL DEFAULT '1',
  `active` tinyint(4) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `prodspecs`
--

INSERT INTO `prodspecs` (`keyval`, `testid`, `defaultvalue`, `targetvalue`, `rangemin`, `rangemax`, `showoncert`, `showonspec`, `showontestplan`, `active`) VALUES
('DISPLAY SIZE', 1, '450-500', '475', 450, 500, 1, 1, 1, 1),
('DISPLAY SIZE', 2, '1200-1500', '1300', 1200, 1500, 1, 1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `purchdata`
--

CREATE TABLE `purchdata` (
  `supplierno` char(10) NOT NULL DEFAULT '',
  `stockid` char(20) NOT NULL DEFAULT '',
  `price` decimal(20,4) NOT NULL DEFAULT '0.0000',
  `suppliersuom` char(50) NOT NULL DEFAULT '',
  `conversionfactor` double NOT NULL DEFAULT '1',
  `supplierdescription` char(50) NOT NULL DEFAULT '',
  `leadtime` smallint(6) NOT NULL DEFAULT '1',
  `preferred` tinyint(4) NOT NULL DEFAULT '0',
  `effectivefrom` date NOT NULL,
  `suppliers_partno` varchar(50) NOT NULL DEFAULT '',
  `minorderqty` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `purchorderauth`
--

CREATE TABLE `purchorderauth` (
  `userid` varchar(20) NOT NULL DEFAULT '',
  `currabrev` char(3) NOT NULL DEFAULT '',
  `cancreate` smallint(2) NOT NULL DEFAULT '0',
  `authlevel` double NOT NULL DEFAULT '0',
  `offhold` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `purchorderauth`
--

INSERT INTO `purchorderauth` (`userid`, `currabrev`, `cancreate`, `authlevel`, `offhold`) VALUES
('admin', 'INR', 0, 1000000, 0);

-- --------------------------------------------------------

--
-- Table structure for table `purchorderdetails`
--

CREATE TABLE `purchorderdetails` (
  `podetailitem` int(11) NOT NULL,
  `orderno` int(11) NOT NULL DEFAULT '0',
  `itemcode` varchar(20) NOT NULL DEFAULT '',
  `deliverydate` date NOT NULL DEFAULT '0000-00-00',
  `itemdescription` varchar(100) NOT NULL,
  `glcode` varchar(20) NOT NULL DEFAULT '0',
  `qtyinvoiced` double NOT NULL DEFAULT '0',
  `unitprice` double NOT NULL DEFAULT '0',
  `actprice` double NOT NULL DEFAULT '0',
  `stdcostunit` double NOT NULL DEFAULT '0',
  `quantityord` double NOT NULL DEFAULT '0',
  `quantityrecd` double NOT NULL DEFAULT '0',
  `shiptref` int(11) NOT NULL DEFAULT '0',
  `jobref` varchar(20) NOT NULL DEFAULT '',
  `completed` tinyint(4) NOT NULL DEFAULT '0',
  `suppliersunit` varchar(50) DEFAULT NULL,
  `suppliers_partno` varchar(50) NOT NULL DEFAULT '',
  `assetid` int(11) NOT NULL DEFAULT '0',
  `conversionfactor` double NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `purchorders`
--

CREATE TABLE `purchorders` (
  `orderno` int(11) NOT NULL,
  `supplierno` varchar(10) NOT NULL DEFAULT '',
  `comments` longblob,
  `orddate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `rate` double NOT NULL DEFAULT '1',
  `dateprinted` datetime DEFAULT NULL,
  `allowprint` tinyint(4) NOT NULL DEFAULT '1',
  `initiator` varchar(20) DEFAULT NULL,
  `requisitionno` varchar(15) DEFAULT NULL,
  `intostocklocation` varchar(5) NOT NULL DEFAULT '',
  `deladd1` varchar(40) NOT NULL DEFAULT '',
  `deladd2` varchar(40) NOT NULL DEFAULT '',
  `deladd3` varchar(40) NOT NULL DEFAULT '',
  `deladd4` varchar(40) NOT NULL DEFAULT '',
  `deladd5` varchar(20) NOT NULL DEFAULT '',
  `deladd6` varchar(15) NOT NULL DEFAULT '',
  `tel` varchar(30) NOT NULL DEFAULT '',
  `suppdeladdress1` varchar(40) NOT NULL DEFAULT '',
  `suppdeladdress2` varchar(40) NOT NULL DEFAULT '',
  `suppdeladdress3` varchar(40) NOT NULL DEFAULT '',
  `suppdeladdress4` varchar(40) NOT NULL DEFAULT '',
  `suppdeladdress5` varchar(20) NOT NULL DEFAULT '',
  `suppdeladdress6` varchar(15) NOT NULL DEFAULT '',
  `suppliercontact` varchar(30) NOT NULL DEFAULT '',
  `supptel` varchar(30) NOT NULL DEFAULT '',
  `contact` varchar(30) NOT NULL DEFAULT '',
  `version` decimal(3,2) NOT NULL DEFAULT '1.00',
  `revised` date NOT NULL DEFAULT '0000-00-00',
  `realorderno` varchar(16) NOT NULL DEFAULT '',
  `deliveryby` varchar(100) NOT NULL DEFAULT '',
  `deliverydate` date NOT NULL DEFAULT '0000-00-00',
  `status` varchar(12) NOT NULL DEFAULT '',
  `stat_comment` text NOT NULL,
  `paymentterms` char(2) NOT NULL DEFAULT '',
  `port` varchar(40) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `qasamples`
--

CREATE TABLE `qasamples` (
  `sampleid` int(11) NOT NULL,
  `prodspeckey` varchar(25) NOT NULL DEFAULT '',
  `lotkey` varchar(25) NOT NULL DEFAULT '',
  `identifier` varchar(10) NOT NULL DEFAULT '',
  `createdby` varchar(15) NOT NULL DEFAULT '',
  `sampledate` date NOT NULL DEFAULT '0000-00-00',
  `comments` varchar(255) NOT NULL DEFAULT '',
  `cert` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `qasamples`
--

INSERT INTO `qasamples` (`sampleid`, `prodspeckey`, `lotkey`, `identifier`, `createdby`, `sampledate`, `comments`, `cert`) VALUES
(1, 'DISPLAY SIZE', '123', 'sadfsadfas', 'admin', '2018-09-02', 'ssafdasdfasd', 1),
(2, 'DISPLAY SIZE', '123', 'sadfsadfas', 'admin', '2018-09-02', 'ssafdasdfasd', 1),
(3, 'DISPLAY SIZE', '456', 'asdfasdfas', 'admin', '2018-09-02', 'sdafsdaadsfasas', 1);

-- --------------------------------------------------------

--
-- Table structure for table `qatests`
--

CREATE TABLE `qatests` (
  `testid` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `method` varchar(20) DEFAULT NULL,
  `groupby` varchar(20) DEFAULT NULL,
  `units` varchar(20) NOT NULL,
  `type` varchar(15) NOT NULL,
  `defaultvalue` varchar(150) NOT NULL DEFAULT '''''',
  `numericvalue` tinyint(4) NOT NULL DEFAULT '0',
  `showoncert` int(11) NOT NULL DEFAULT '1',
  `showonspec` int(11) NOT NULL DEFAULT '1',
  `showontestplan` tinyint(4) NOT NULL DEFAULT '1',
  `active` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `qatests`
--

INSERT INTO `qatests` (`testid`, `name`, `method`, `groupby`, `units`, `type`, `defaultvalue`, `numericvalue`, `showoncert`, `showonspec`, `showontestplan`, `active`) VALUES
(1, 'Display LED', 'ISO', '', 'candelas (cd) per sq', '4', '450-500', 1, 1, 1, 1, 1),
(2, 'Ram Speed', 'ISO', '', 'RPM', '4', '1200-1500', 1, 1, 1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `recurringsalesorders`
--

CREATE TABLE `recurringsalesorders` (
  `recurrorderno` int(11) NOT NULL,
  `debtorno` varchar(10) NOT NULL DEFAULT '',
  `branchcode` varchar(10) NOT NULL DEFAULT '',
  `customerref` varchar(50) NOT NULL DEFAULT '',
  `buyername` varchar(50) DEFAULT NULL,
  `comments` longblob,
  `orddate` date NOT NULL DEFAULT '0000-00-00',
  `ordertype` char(2) NOT NULL DEFAULT '',
  `shipvia` int(11) NOT NULL DEFAULT '0',
  `deladd1` varchar(40) NOT NULL DEFAULT '',
  `deladd2` varchar(40) NOT NULL DEFAULT '',
  `deladd3` varchar(40) NOT NULL DEFAULT '',
  `deladd4` varchar(40) DEFAULT NULL,
  `deladd5` varchar(20) NOT NULL DEFAULT '',
  `deladd6` varchar(15) NOT NULL DEFAULT '',
  `contactphone` varchar(25) DEFAULT NULL,
  `contactemail` varchar(25) DEFAULT NULL,
  `deliverto` varchar(40) NOT NULL DEFAULT '',
  `freightcost` double NOT NULL DEFAULT '0',
  `fromstkloc` varchar(5) NOT NULL DEFAULT '',
  `lastrecurrence` date NOT NULL DEFAULT '0000-00-00',
  `stopdate` date NOT NULL DEFAULT '0000-00-00',
  `frequency` tinyint(4) NOT NULL DEFAULT '1',
  `autoinvoice` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `recurrsalesorderdetails`
--

CREATE TABLE `recurrsalesorderdetails` (
  `recurrorderno` int(11) NOT NULL DEFAULT '0',
  `stkcode` varchar(20) NOT NULL DEFAULT '',
  `unitprice` double NOT NULL DEFAULT '0',
  `quantity` double NOT NULL DEFAULT '0',
  `discountpercent` double NOT NULL DEFAULT '0',
  `narrative` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `relateditems`
--

CREATE TABLE `relateditems` (
  `stockid` varchar(20) CHARACTER SET utf8 NOT NULL,
  `related` varchar(20) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reportcolumns`
--

CREATE TABLE `reportcolumns` (
  `reportid` smallint(6) NOT NULL DEFAULT '0',
  `colno` smallint(6) NOT NULL DEFAULT '0',
  `heading1` varchar(15) NOT NULL DEFAULT '',
  `heading2` varchar(15) DEFAULT NULL,
  `calculation` tinyint(1) NOT NULL DEFAULT '0',
  `periodfrom` smallint(6) DEFAULT NULL,
  `periodto` smallint(6) DEFAULT NULL,
  `datatype` varchar(15) DEFAULT NULL,
  `colnumerator` tinyint(4) DEFAULT NULL,
  `coldenominator` tinyint(4) DEFAULT NULL,
  `calcoperator` char(1) DEFAULT NULL,
  `budgetoractual` tinyint(1) NOT NULL DEFAULT '0',
  `valformat` char(1) NOT NULL DEFAULT 'N',
  `constant` double NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `reportfields`
--

CREATE TABLE `reportfields` (
  `id` int(8) NOT NULL,
  `reportid` int(5) NOT NULL DEFAULT '0',
  `entrytype` varchar(15) NOT NULL DEFAULT '',
  `seqnum` int(3) NOT NULL DEFAULT '0',
  `fieldname` varchar(80) NOT NULL DEFAULT '',
  `displaydesc` varchar(25) NOT NULL DEFAULT '',
  `visible` enum('1','0') NOT NULL DEFAULT '1',
  `columnbreak` enum('1','0') NOT NULL DEFAULT '1',
  `params` text
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `reportheaders`
--

CREATE TABLE `reportheaders` (
  `reportid` smallint(6) NOT NULL,
  `reportheading` varchar(80) NOT NULL DEFAULT '',
  `groupbydata1` varchar(15) NOT NULL DEFAULT '',
  `newpageafter1` tinyint(1) NOT NULL DEFAULT '0',
  `lower1` varchar(10) NOT NULL DEFAULT '',
  `upper1` varchar(10) NOT NULL DEFAULT '',
  `groupbydata2` varchar(15) DEFAULT NULL,
  `newpageafter2` tinyint(1) NOT NULL DEFAULT '0',
  `lower2` varchar(10) DEFAULT NULL,
  `upper2` varchar(10) DEFAULT NULL,
  `groupbydata3` varchar(15) DEFAULT NULL,
  `newpageafter3` tinyint(1) NOT NULL DEFAULT '0',
  `lower3` varchar(10) DEFAULT NULL,
  `upper3` varchar(10) DEFAULT NULL,
  `groupbydata4` varchar(15) NOT NULL DEFAULT '',
  `newpageafter4` tinyint(1) NOT NULL DEFAULT '0',
  `upper4` varchar(10) NOT NULL DEFAULT '',
  `lower4` varchar(10) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `reportlinks`
--

CREATE TABLE `reportlinks` (
  `table1` varchar(25) NOT NULL DEFAULT '',
  `table2` varchar(25) NOT NULL DEFAULT '',
  `equation` varchar(75) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `reportlinks`
--

INSERT INTO `reportlinks` (`table1`, `table2`, `equation`) VALUES
('accountgroups', 'accountsection', 'accountgroups.sectioninaccounts=accountsection.sectionid'),
('accountsection', 'accountgroups', 'accountsection.sectionid=accountgroups.sectioninaccounts'),
('bankaccounts', 'chartmaster', 'bankaccounts.accountcode=chartmaster.accountcode'),
('chartmaster', 'bankaccounts', 'chartmaster.accountcode=bankaccounts.accountcode'),
('banktrans', 'systypes', 'banktrans.type=systypes.typeid'),
('systypes', 'banktrans', 'systypes.typeid=banktrans.type'),
('banktrans', 'bankaccounts', 'banktrans.bankact=bankaccounts.accountcode'),
('bankaccounts', 'banktrans', 'bankaccounts.accountcode=banktrans.bankact'),
('bom', 'stockmaster', 'bom.parent=stockmaster.stockid'),
('stockmaster', 'bom', 'stockmaster.stockid=bom.parent'),
('bom', 'stockmaster', 'bom.component=stockmaster.stockid'),
('stockmaster', 'bom', 'stockmaster.stockid=bom.component'),
('bom', 'workcentres', 'bom.workcentreadded=workcentres.code'),
('workcentres', 'bom', 'workcentres.code=bom.workcentreadded'),
('bom', 'locations', 'bom.loccode=locations.loccode'),
('locations', 'bom', 'locations.loccode=bom.loccode'),
('buckets', 'workcentres', 'buckets.workcentre=workcentres.code'),
('workcentres', 'buckets', 'workcentres.code=buckets.workcentre'),
('chartdetails', 'chartmaster', 'chartdetails.accountcode=chartmaster.accountcode'),
('chartmaster', 'chartdetails', 'chartmaster.accountcode=chartdetails.accountcode'),
('chartdetails', 'periods', 'chartdetails.period=periods.periodno'),
('periods', 'chartdetails', 'periods.periodno=chartdetails.period'),
('chartmaster', 'accountgroups', 'chartmaster.group_=accountgroups.groupname'),
('accountgroups', 'chartmaster', 'accountgroups.groupname=chartmaster.group_'),
('contractbom', 'workcentres', 'contractbom.workcentreadded=workcentres.code'),
('workcentres', 'contractbom', 'workcentres.code=contractbom.workcentreadded'),
('contractbom', 'locations', 'contractbom.loccode=locations.loccode'),
('locations', 'contractbom', 'locations.loccode=contractbom.loccode'),
('contractbom', 'stockmaster', 'contractbom.component=stockmaster.stockid'),
('stockmaster', 'contractbom', 'stockmaster.stockid=contractbom.component'),
('contractreqts', 'contracts', 'contractreqts.contract=contracts.contractref'),
('contracts', 'contractreqts', 'contracts.contractref=contractreqts.contract'),
('contracts', 'custbranch', 'contracts.debtorno=custbranch.debtorno'),
('custbranch', 'contracts', 'custbranch.debtorno=contracts.debtorno'),
('contracts', 'stockcategory', 'contracts.branchcode=stockcategory.categoryid'),
('stockcategory', 'contracts', 'stockcategory.categoryid=contracts.branchcode'),
('contracts', 'salestypes', 'contracts.typeabbrev=salestypes.typeabbrev'),
('salestypes', 'contracts', 'salestypes.typeabbrev=contracts.typeabbrev'),
('custallocns', 'debtortrans', 'custallocns.transid_allocfrom=debtortrans.id'),
('debtortrans', 'custallocns', 'debtortrans.id=custallocns.transid_allocfrom'),
('custallocns', 'debtortrans', 'custallocns.transid_allocto=debtortrans.id'),
('debtortrans', 'custallocns', 'debtortrans.id=custallocns.transid_allocto'),
('custbranch', 'debtorsmaster', 'custbranch.debtorno=debtorsmaster.debtorno'),
('debtorsmaster', 'custbranch', 'debtorsmaster.debtorno=custbranch.debtorno'),
('custbranch', 'areas', 'custbranch.area=areas.areacode'),
('areas', 'custbranch', 'areas.areacode=custbranch.area'),
('custbranch', 'salesman', 'custbranch.salesman=salesman.salesmancode'),
('salesman', 'custbranch', 'salesman.salesmancode=custbranch.salesman'),
('custbranch', 'locations', 'custbranch.defaultlocation=locations.loccode'),
('locations', 'custbranch', 'locations.loccode=custbranch.defaultlocation'),
('custbranch', 'shippers', 'custbranch.defaultshipvia=shippers.shipper_id'),
('shippers', 'custbranch', 'shippers.shipper_id=custbranch.defaultshipvia'),
('debtorsmaster', 'holdreasons', 'debtorsmaster.holdreason=holdreasons.reasoncode'),
('holdreasons', 'debtorsmaster', 'holdreasons.reasoncode=debtorsmaster.holdreason'),
('debtorsmaster', 'currencies', 'debtorsmaster.currcode=currencies.currabrev'),
('currencies', 'debtorsmaster', 'currencies.currabrev=debtorsmaster.currcode'),
('debtorsmaster', 'paymentterms', 'debtorsmaster.paymentterms=paymentterms.termsindicator'),
('paymentterms', 'debtorsmaster', 'paymentterms.termsindicator=debtorsmaster.paymentterms'),
('debtorsmaster', 'salestypes', 'debtorsmaster.salestype=salestypes.typeabbrev'),
('salestypes', 'debtorsmaster', 'salestypes.typeabbrev=debtorsmaster.salestype'),
('debtortrans', 'custbranch', 'debtortrans.debtorno=custbranch.debtorno'),
('custbranch', 'debtortrans', 'custbranch.debtorno=debtortrans.debtorno'),
('debtortrans', 'systypes', 'debtortrans.type=systypes.typeid'),
('systypes', 'debtortrans', 'systypes.typeid=debtortrans.type'),
('debtortrans', 'periods', 'debtortrans.prd=periods.periodno'),
('periods', 'debtortrans', 'periods.periodno=debtortrans.prd'),
('debtortranstaxes', 'taxauthorities', 'debtortranstaxes.taxauthid=taxauthorities.taxid'),
('taxauthorities', 'debtortranstaxes', 'taxauthorities.taxid=debtortranstaxes.taxauthid'),
('debtortranstaxes', 'debtortrans', 'debtortranstaxes.debtortransid=debtortrans.id'),
('debtortrans', 'debtortranstaxes', 'debtortrans.id=debtortranstaxes.debtortransid'),
('discountmatrix', 'salestypes', 'discountmatrix.salestype=salestypes.typeabbrev'),
('salestypes', 'discountmatrix', 'salestypes.typeabbrev=discountmatrix.salestype'),
('freightcosts', 'locations', 'freightcosts.locationfrom=locations.loccode'),
('locations', 'freightcosts', 'locations.loccode=freightcosts.locationfrom'),
('freightcosts', 'shippers', 'freightcosts.shipperid=shippers.shipper_id'),
('shippers', 'freightcosts', 'shippers.shipper_id=freightcosts.shipperid'),
('gltrans', 'chartmaster', 'gltrans.account=chartmaster.accountcode'),
('chartmaster', 'gltrans', 'chartmaster.accountcode=gltrans.account'),
('gltrans', 'systypes', 'gltrans.type=systypes.typeid'),
('systypes', 'gltrans', 'systypes.typeid=gltrans.type'),
('gltrans', 'periods', 'gltrans.periodno=periods.periodno'),
('periods', 'gltrans', 'periods.periodno=gltrans.periodno'),
('grns', 'suppliers', 'grns.supplierid=suppliers.supplierid'),
('suppliers', 'grns', 'suppliers.supplierid=grns.supplierid'),
('grns', 'purchorderdetails', 'grns.podetailitem=purchorderdetails.podetailitem'),
('purchorderdetails', 'grns', 'purchorderdetails.podetailitem=grns.podetailitem'),
('locations', 'taxprovinces', 'locations.taxprovinceid=taxprovinces.taxprovinceid'),
('taxprovinces', 'locations', 'taxprovinces.taxprovinceid=locations.taxprovinceid'),
('locstock', 'locations', 'locstock.loccode=locations.loccode'),
('locations', 'locstock', 'locations.loccode=locstock.loccode'),
('locstock', 'stockmaster', 'locstock.stockid=stockmaster.stockid'),
('stockmaster', 'locstock', 'stockmaster.stockid=locstock.stockid'),
('loctransfers', 'locations', 'loctransfers.shiploc=locations.loccode'),
('locations', 'loctransfers', 'locations.loccode=loctransfers.shiploc'),
('loctransfers', 'locations', 'loctransfers.recloc=locations.loccode'),
('locations', 'loctransfers', 'locations.loccode=loctransfers.recloc'),
('loctransfers', 'stockmaster', 'loctransfers.stockid=stockmaster.stockid'),
('stockmaster', 'loctransfers', 'stockmaster.stockid=loctransfers.stockid'),
('orderdeliverydifferencesl', 'stockmaster', 'orderdeliverydifferenceslog.stockid=stockmaster.stockid'),
('stockmaster', 'orderdeliverydifferencesl', 'stockmaster.stockid=orderdeliverydifferenceslog.stockid'),
('orderdeliverydifferencesl', 'custbranch', 'orderdeliverydifferenceslog.debtorno=custbranch.debtorno'),
('custbranch', 'orderdeliverydifferencesl', 'custbranch.debtorno=orderdeliverydifferenceslog.debtorno'),
('orderdeliverydifferencesl', 'salesorders', 'orderdeliverydifferenceslog.branchcode=salesorders.orderno'),
('salesorders', 'orderdeliverydifferencesl', 'salesorders.orderno=orderdeliverydifferenceslog.branchcode'),
('prices', 'stockmaster', 'prices.stockid=stockmaster.stockid'),
('stockmaster', 'prices', 'stockmaster.stockid=prices.stockid'),
('prices', 'currencies', 'prices.currabrev=currencies.currabrev'),
('currencies', 'prices', 'currencies.currabrev=prices.currabrev'),
('prices', 'salestypes', 'prices.typeabbrev=salestypes.typeabbrev'),
('salestypes', 'prices', 'salestypes.typeabbrev=prices.typeabbrev'),
('purchdata', 'stockmaster', 'purchdata.stockid=stockmaster.stockid'),
('stockmaster', 'purchdata', 'stockmaster.stockid=purchdata.stockid'),
('purchdata', 'suppliers', 'purchdata.supplierno=suppliers.supplierid'),
('suppliers', 'purchdata', 'suppliers.supplierid=purchdata.supplierno'),
('purchorderdetails', 'purchorders', 'purchorderdetails.orderno=purchorders.orderno'),
('purchorders', 'purchorderdetails', 'purchorders.orderno=purchorderdetails.orderno'),
('purchorders', 'suppliers', 'purchorders.supplierno=suppliers.supplierid'),
('suppliers', 'purchorders', 'suppliers.supplierid=purchorders.supplierno'),
('purchorders', 'locations', 'purchorders.intostocklocation=locations.loccode'),
('locations', 'purchorders', 'locations.loccode=purchorders.intostocklocation'),
('recurringsalesorders', 'custbranch', 'recurringsalesorders.branchcode=custbranch.branchcode'),
('custbranch', 'recurringsalesorders', 'custbranch.branchcode=recurringsalesorders.branchcode'),
('recurrsalesorderdetails', 'recurringsalesorders', 'recurrsalesorderdetails.recurrorderno=recurringsalesorders.recurrorderno'),
('recurringsalesorders', 'recurrsalesorderdetails', 'recurringsalesorders.recurrorderno=recurrsalesorderdetails.recurrorderno'),
('recurrsalesorderdetails', 'stockmaster', 'recurrsalesorderdetails.stkcode=stockmaster.stockid'),
('stockmaster', 'recurrsalesorderdetails', 'stockmaster.stockid=recurrsalesorderdetails.stkcode'),
('reportcolumns', 'reportheaders', 'reportcolumns.reportid=reportheaders.reportid'),
('reportheaders', 'reportcolumns', 'reportheaders.reportid=reportcolumns.reportid'),
('salesanalysis', 'periods', 'salesanalysis.periodno=periods.periodno'),
('periods', 'salesanalysis', 'periods.periodno=salesanalysis.periodno'),
('salescatprod', 'stockmaster', 'salescatprod.stockid=stockmaster.stockid'),
('stockmaster', 'salescatprod', 'stockmaster.stockid=salescatprod.stockid'),
('salescatprod', 'salescat', 'salescatprod.salescatid=salescat.salescatid'),
('salescat', 'salescatprod', 'salescat.salescatid=salescatprod.salescatid'),
('salesorderdetails', 'salesorders', 'salesorderdetails.orderno=salesorders.orderno'),
('salesorders', 'salesorderdetails', 'salesorders.orderno=salesorderdetails.orderno'),
('salesorderdetails', 'stockmaster', 'salesorderdetails.stkcode=stockmaster.stockid'),
('stockmaster', 'salesorderdetails', 'stockmaster.stockid=salesorderdetails.stkcode'),
('salesorders', 'custbranch', 'salesorders.branchcode=custbranch.branchcode'),
('custbranch', 'salesorders', 'custbranch.branchcode=salesorders.branchcode'),
('salesorders', 'shippers', 'salesorders.debtorno=shippers.shipper_id'),
('shippers', 'salesorders', 'shippers.shipper_id=salesorders.debtorno'),
('salesorders', 'locations', 'salesorders.fromstkloc=locations.loccode'),
('locations', 'salesorders', 'locations.loccode=salesorders.fromstkloc'),
('securitygroups', 'securityroles', 'securitygroups.secroleid=securityroles.secroleid'),
('securityroles', 'securitygroups', 'securityroles.secroleid=securitygroups.secroleid'),
('securitygroups', 'securitytokens', 'securitygroups.tokenid=securitytokens.tokenid'),
('securitytokens', 'securitygroups', 'securitytokens.tokenid=securitygroups.tokenid'),
('shipmentcharges', 'shipments', 'shipmentcharges.shiptref=shipments.shiptref'),
('shipments', 'shipmentcharges', 'shipments.shiptref=shipmentcharges.shiptref'),
('shipmentcharges', 'systypes', 'shipmentcharges.transtype=systypes.typeid'),
('systypes', 'shipmentcharges', 'systypes.typeid=shipmentcharges.transtype'),
('shipments', 'suppliers', 'shipments.supplierid=suppliers.supplierid'),
('suppliers', 'shipments', 'suppliers.supplierid=shipments.supplierid'),
('stockcheckfreeze', 'stockmaster', 'stockcheckfreeze.stockid=stockmaster.stockid'),
('stockmaster', 'stockcheckfreeze', 'stockmaster.stockid=stockcheckfreeze.stockid'),
('stockcheckfreeze', 'locations', 'stockcheckfreeze.loccode=locations.loccode'),
('locations', 'stockcheckfreeze', 'locations.loccode=stockcheckfreeze.loccode'),
('stockcounts', 'stockmaster', 'stockcounts.stockid=stockmaster.stockid'),
('stockmaster', 'stockcounts', 'stockmaster.stockid=stockcounts.stockid'),
('stockcounts', 'locations', 'stockcounts.loccode=locations.loccode'),
('locations', 'stockcounts', 'locations.loccode=stockcounts.loccode'),
('stockmaster', 'stockcategory', 'stockmaster.categoryid=stockcategory.categoryid'),
('stockcategory', 'stockmaster', 'stockcategory.categoryid=stockmaster.categoryid'),
('stockmaster', 'taxcategories', 'stockmaster.taxcatid=taxcategories.taxcatid'),
('taxcategories', 'stockmaster', 'taxcategories.taxcatid=stockmaster.taxcatid'),
('stockmoves', 'stockmaster', 'stockmoves.stockid=stockmaster.stockid'),
('stockmaster', 'stockmoves', 'stockmaster.stockid=stockmoves.stockid'),
('stockmoves', 'systypes', 'stockmoves.type=systypes.typeid'),
('systypes', 'stockmoves', 'systypes.typeid=stockmoves.type'),
('stockmoves', 'locations', 'stockmoves.loccode=locations.loccode'),
('locations', 'stockmoves', 'locations.loccode=stockmoves.loccode'),
('stockmoves', 'periods', 'stockmoves.prd=periods.periodno'),
('periods', 'stockmoves', 'periods.periodno=stockmoves.prd'),
('stockmovestaxes', 'taxauthorities', 'stockmovestaxes.taxauthid=taxauthorities.taxid'),
('taxauthorities', 'stockmovestaxes', 'taxauthorities.taxid=stockmovestaxes.taxauthid'),
('stockserialitems', 'stockmaster', 'stockserialitems.stockid=stockmaster.stockid'),
('stockmaster', 'stockserialitems', 'stockmaster.stockid=stockserialitems.stockid'),
('stockserialitems', 'locations', 'stockserialitems.loccode=locations.loccode'),
('locations', 'stockserialitems', 'locations.loccode=stockserialitems.loccode'),
('stockserialmoves', 'stockmoves', 'stockserialmoves.stockmoveno=stockmoves.stkmoveno'),
('stockmoves', 'stockserialmoves', 'stockmoves.stkmoveno=stockserialmoves.stockmoveno'),
('stockserialmoves', 'stockserialitems', 'stockserialmoves.stockid=stockserialitems.stockid'),
('stockserialitems', 'stockserialmoves', 'stockserialitems.stockid=stockserialmoves.stockid'),
('suppallocs', 'supptrans', 'suppallocs.transid_allocfrom=supptrans.id'),
('supptrans', 'suppallocs', 'supptrans.id=suppallocs.transid_allocfrom'),
('suppallocs', 'supptrans', 'suppallocs.transid_allocto=supptrans.id'),
('supptrans', 'suppallocs', 'supptrans.id=suppallocs.transid_allocto'),
('suppliercontacts', 'suppliers', 'suppliercontacts.supplierid=suppliers.supplierid'),
('suppliers', 'suppliercontacts', 'suppliers.supplierid=suppliercontacts.supplierid'),
('suppliers', 'currencies', 'suppliers.currcode=currencies.currabrev'),
('currencies', 'suppliers', 'currencies.currabrev=suppliers.currcode'),
('suppliers', 'paymentterms', 'suppliers.paymentterms=paymentterms.termsindicator'),
('paymentterms', 'suppliers', 'paymentterms.termsindicator=suppliers.paymentterms'),
('suppliers', 'taxgroups', 'suppliers.taxgroupid=taxgroups.taxgroupid'),
('taxgroups', 'suppliers', 'taxgroups.taxgroupid=suppliers.taxgroupid'),
('supptrans', 'systypes', 'supptrans.type=systypes.typeid'),
('systypes', 'supptrans', 'systypes.typeid=supptrans.type'),
('supptrans', 'suppliers', 'supptrans.supplierno=suppliers.supplierid'),
('suppliers', 'supptrans', 'suppliers.supplierid=supptrans.supplierno'),
('supptranstaxes', 'taxauthorities', 'supptranstaxes.taxauthid=taxauthorities.taxid'),
('taxauthorities', 'supptranstaxes', 'taxauthorities.taxid=supptranstaxes.taxauthid'),
('supptranstaxes', 'supptrans', 'supptranstaxes.supptransid=supptrans.id'),
('supptrans', 'supptranstaxes', 'supptrans.id=supptranstaxes.supptransid'),
('taxauthorities', 'chartmaster', 'taxauthorities.taxglcode=chartmaster.accountcode'),
('chartmaster', 'taxauthorities', 'chartmaster.accountcode=taxauthorities.taxglcode'),
('taxauthorities', 'chartmaster', 'taxauthorities.purchtaxglaccount=chartmaster.accountcode'),
('chartmaster', 'taxauthorities', 'chartmaster.accountcode=taxauthorities.purchtaxglaccount'),
('taxauthrates', 'taxauthorities', 'taxauthrates.taxauthority=taxauthorities.taxid'),
('taxauthorities', 'taxauthrates', 'taxauthorities.taxid=taxauthrates.taxauthority'),
('taxauthrates', 'taxcategories', 'taxauthrates.taxcatid=taxcategories.taxcatid'),
('taxcategories', 'taxauthrates', 'taxcategories.taxcatid=taxauthrates.taxcatid'),
('taxauthrates', 'taxprovinces', 'taxauthrates.dispatchtaxprovince=taxprovinces.taxprovinceid'),
('taxprovinces', 'taxauthrates', 'taxprovinces.taxprovinceid=taxauthrates.dispatchtaxprovince'),
('taxgrouptaxes', 'taxgroups', 'taxgrouptaxes.taxgroupid=taxgroups.taxgroupid'),
('taxgroups', 'taxgrouptaxes', 'taxgroups.taxgroupid=taxgrouptaxes.taxgroupid'),
('taxgrouptaxes', 'taxauthorities', 'taxgrouptaxes.taxauthid=taxauthorities.taxid'),
('taxauthorities', 'taxgrouptaxes', 'taxauthorities.taxid=taxgrouptaxes.taxauthid'),
('workcentres', 'locations', 'workcentres.location=locations.loccode'),
('locations', 'workcentres', 'locations.loccode=workcentres.location'),
('worksorders', 'locations', 'worksorders.loccode=locations.loccode'),
('locations', 'worksorders', 'locations.loccode=worksorders.loccode'),
('worksorders', 'stockmaster', 'worksorders.stockid=stockmaster.stockid'),
('stockmaster', 'worksorders', 'stockmaster.stockid=worksorders.stockid'),
('www_users', 'locations', 'www_users.defaultlocation=locations.loccode'),
('locations', 'www_users', 'locations.loccode=www_users.defaultlocation'),
('accountgroups', 'accountsection', 'accountgroups.sectioninaccounts=accountsection.sectionid'),
('accountsection', 'accountgroups', 'accountsection.sectionid=accountgroups.sectioninaccounts'),
('bankaccounts', 'chartmaster', 'bankaccounts.accountcode=chartmaster.accountcode'),
('chartmaster', 'bankaccounts', 'chartmaster.accountcode=bankaccounts.accountcode'),
('banktrans', 'systypes', 'banktrans.type=systypes.typeid'),
('systypes', 'banktrans', 'systypes.typeid=banktrans.type'),
('banktrans', 'bankaccounts', 'banktrans.bankact=bankaccounts.accountcode'),
('bankaccounts', 'banktrans', 'bankaccounts.accountcode=banktrans.bankact'),
('bom', 'stockmaster', 'bom.parent=stockmaster.stockid'),
('stockmaster', 'bom', 'stockmaster.stockid=bom.parent'),
('bom', 'stockmaster', 'bom.component=stockmaster.stockid'),
('stockmaster', 'bom', 'stockmaster.stockid=bom.component'),
('bom', 'workcentres', 'bom.workcentreadded=workcentres.code'),
('workcentres', 'bom', 'workcentres.code=bom.workcentreadded'),
('bom', 'locations', 'bom.loccode=locations.loccode'),
('locations', 'bom', 'locations.loccode=bom.loccode'),
('buckets', 'workcentres', 'buckets.workcentre=workcentres.code'),
('workcentres', 'buckets', 'workcentres.code=buckets.workcentre'),
('chartdetails', 'chartmaster', 'chartdetails.accountcode=chartmaster.accountcode'),
('chartmaster', 'chartdetails', 'chartmaster.accountcode=chartdetails.accountcode'),
('chartdetails', 'periods', 'chartdetails.period=periods.periodno'),
('periods', 'chartdetails', 'periods.periodno=chartdetails.period'),
('chartmaster', 'accountgroups', 'chartmaster.group_=accountgroups.groupname'),
('accountgroups', 'chartmaster', 'accountgroups.groupname=chartmaster.group_'),
('contractbom', 'workcentres', 'contractbom.workcentreadded=workcentres.code'),
('workcentres', 'contractbom', 'workcentres.code=contractbom.workcentreadded'),
('contractbom', 'locations', 'contractbom.loccode=locations.loccode'),
('locations', 'contractbom', 'locations.loccode=contractbom.loccode'),
('contractbom', 'stockmaster', 'contractbom.component=stockmaster.stockid'),
('stockmaster', 'contractbom', 'stockmaster.stockid=contractbom.component'),
('contractreqts', 'contracts', 'contractreqts.contract=contracts.contractref'),
('contracts', 'contractreqts', 'contracts.contractref=contractreqts.contract'),
('contracts', 'custbranch', 'contracts.debtorno=custbranch.debtorno'),
('custbranch', 'contracts', 'custbranch.debtorno=contracts.debtorno'),
('contracts', 'stockcategory', 'contracts.branchcode=stockcategory.categoryid'),
('stockcategory', 'contracts', 'stockcategory.categoryid=contracts.branchcode'),
('contracts', 'salestypes', 'contracts.typeabbrev=salestypes.typeabbrev'),
('salestypes', 'contracts', 'salestypes.typeabbrev=contracts.typeabbrev'),
('custallocns', 'debtortrans', 'custallocns.transid_allocfrom=debtortrans.id'),
('debtortrans', 'custallocns', 'debtortrans.id=custallocns.transid_allocfrom'),
('custallocns', 'debtortrans', 'custallocns.transid_allocto=debtortrans.id'),
('debtortrans', 'custallocns', 'debtortrans.id=custallocns.transid_allocto'),
('custbranch', 'debtorsmaster', 'custbranch.debtorno=debtorsmaster.debtorno'),
('debtorsmaster', 'custbranch', 'debtorsmaster.debtorno=custbranch.debtorno'),
('custbranch', 'areas', 'custbranch.area=areas.areacode'),
('areas', 'custbranch', 'areas.areacode=custbranch.area'),
('custbranch', 'salesman', 'custbranch.salesman=salesman.salesmancode'),
('salesman', 'custbranch', 'salesman.salesmancode=custbranch.salesman'),
('custbranch', 'locations', 'custbranch.defaultlocation=locations.loccode'),
('locations', 'custbranch', 'locations.loccode=custbranch.defaultlocation'),
('custbranch', 'shippers', 'custbranch.defaultshipvia=shippers.shipper_id'),
('shippers', 'custbranch', 'shippers.shipper_id=custbranch.defaultshipvia'),
('debtorsmaster', 'holdreasons', 'debtorsmaster.holdreason=holdreasons.reasoncode'),
('holdreasons', 'debtorsmaster', 'holdreasons.reasoncode=debtorsmaster.holdreason'),
('debtorsmaster', 'currencies', 'debtorsmaster.currcode=currencies.currabrev'),
('currencies', 'debtorsmaster', 'currencies.currabrev=debtorsmaster.currcode'),
('debtorsmaster', 'paymentterms', 'debtorsmaster.paymentterms=paymentterms.termsindicator'),
('paymentterms', 'debtorsmaster', 'paymentterms.termsindicator=debtorsmaster.paymentterms'),
('debtorsmaster', 'salestypes', 'debtorsmaster.salestype=salestypes.typeabbrev'),
('salestypes', 'debtorsmaster', 'salestypes.typeabbrev=debtorsmaster.salestype'),
('debtortrans', 'custbranch', 'debtortrans.debtorno=custbranch.debtorno'),
('custbranch', 'debtortrans', 'custbranch.debtorno=debtortrans.debtorno'),
('debtortrans', 'systypes', 'debtortrans.type=systypes.typeid'),
('systypes', 'debtortrans', 'systypes.typeid=debtortrans.type'),
('debtortrans', 'periods', 'debtortrans.prd=periods.periodno'),
('periods', 'debtortrans', 'periods.periodno=debtortrans.prd'),
('debtortranstaxes', 'taxauthorities', 'debtortranstaxes.taxauthid=taxauthorities.taxid'),
('taxauthorities', 'debtortranstaxes', 'taxauthorities.taxid=debtortranstaxes.taxauthid'),
('debtortranstaxes', 'debtortrans', 'debtortranstaxes.debtortransid=debtortrans.id'),
('debtortrans', 'debtortranstaxes', 'debtortrans.id=debtortranstaxes.debtortransid'),
('discountmatrix', 'salestypes', 'discountmatrix.salestype=salestypes.typeabbrev'),
('salestypes', 'discountmatrix', 'salestypes.typeabbrev=discountmatrix.salestype'),
('freightcosts', 'locations', 'freightcosts.locationfrom=locations.loccode'),
('locations', 'freightcosts', 'locations.loccode=freightcosts.locationfrom'),
('freightcosts', 'shippers', 'freightcosts.shipperid=shippers.shipper_id'),
('shippers', 'freightcosts', 'shippers.shipper_id=freightcosts.shipperid'),
('gltrans', 'chartmaster', 'gltrans.account=chartmaster.accountcode'),
('chartmaster', 'gltrans', 'chartmaster.accountcode=gltrans.account'),
('gltrans', 'systypes', 'gltrans.type=systypes.typeid'),
('systypes', 'gltrans', 'systypes.typeid=gltrans.type'),
('gltrans', 'periods', 'gltrans.periodno=periods.periodno'),
('periods', 'gltrans', 'periods.periodno=gltrans.periodno'),
('grns', 'suppliers', 'grns.supplierid=suppliers.supplierid'),
('suppliers', 'grns', 'suppliers.supplierid=grns.supplierid'),
('grns', 'purchorderdetails', 'grns.podetailitem=purchorderdetails.podetailitem'),
('purchorderdetails', 'grns', 'purchorderdetails.podetailitem=grns.podetailitem'),
('locations', 'taxprovinces', 'locations.taxprovinceid=taxprovinces.taxprovinceid'),
('taxprovinces', 'locations', 'taxprovinces.taxprovinceid=locations.taxprovinceid'),
('locstock', 'locations', 'locstock.loccode=locations.loccode'),
('locations', 'locstock', 'locations.loccode=locstock.loccode'),
('locstock', 'stockmaster', 'locstock.stockid=stockmaster.stockid'),
('stockmaster', 'locstock', 'stockmaster.stockid=locstock.stockid'),
('loctransfers', 'locations', 'loctransfers.shiploc=locations.loccode'),
('locations', 'loctransfers', 'locations.loccode=loctransfers.shiploc'),
('loctransfers', 'locations', 'loctransfers.recloc=locations.loccode'),
('locations', 'loctransfers', 'locations.loccode=loctransfers.recloc'),
('loctransfers', 'stockmaster', 'loctransfers.stockid=stockmaster.stockid'),
('stockmaster', 'loctransfers', 'stockmaster.stockid=loctransfers.stockid'),
('orderdeliverydifferencesl', 'stockmaster', 'orderdeliverydifferenceslog.stockid=stockmaster.stockid'),
('stockmaster', 'orderdeliverydifferencesl', 'stockmaster.stockid=orderdeliverydifferenceslog.stockid'),
('orderdeliverydifferencesl', 'custbranch', 'orderdeliverydifferenceslog.debtorno=custbranch.debtorno'),
('custbranch', 'orderdeliverydifferencesl', 'custbranch.debtorno=orderdeliverydifferenceslog.debtorno'),
('orderdeliverydifferencesl', 'salesorders', 'orderdeliverydifferenceslog.branchcode=salesorders.orderno'),
('salesorders', 'orderdeliverydifferencesl', 'salesorders.orderno=orderdeliverydifferenceslog.branchcode'),
('prices', 'stockmaster', 'prices.stockid=stockmaster.stockid'),
('stockmaster', 'prices', 'stockmaster.stockid=prices.stockid'),
('prices', 'currencies', 'prices.currabrev=currencies.currabrev'),
('currencies', 'prices', 'currencies.currabrev=prices.currabrev'),
('prices', 'salestypes', 'prices.typeabbrev=salestypes.typeabbrev'),
('salestypes', 'prices', 'salestypes.typeabbrev=prices.typeabbrev'),
('purchdata', 'stockmaster', 'purchdata.stockid=stockmaster.stockid'),
('stockmaster', 'purchdata', 'stockmaster.stockid=purchdata.stockid'),
('purchdata', 'suppliers', 'purchdata.supplierno=suppliers.supplierid'),
('suppliers', 'purchdata', 'suppliers.supplierid=purchdata.supplierno'),
('purchorderdetails', 'purchorders', 'purchorderdetails.orderno=purchorders.orderno'),
('purchorders', 'purchorderdetails', 'purchorders.orderno=purchorderdetails.orderno'),
('purchorders', 'suppliers', 'purchorders.supplierno=suppliers.supplierid'),
('suppliers', 'purchorders', 'suppliers.supplierid=purchorders.supplierno'),
('purchorders', 'locations', 'purchorders.intostocklocation=locations.loccode'),
('locations', 'purchorders', 'locations.loccode=purchorders.intostocklocation'),
('recurringsalesorders', 'custbranch', 'recurringsalesorders.branchcode=custbranch.branchcode'),
('custbranch', 'recurringsalesorders', 'custbranch.branchcode=recurringsalesorders.branchcode'),
('recurrsalesorderdetails', 'recurringsalesorders', 'recurrsalesorderdetails.recurrorderno=recurringsalesorders.recurrorderno'),
('recurringsalesorders', 'recurrsalesorderdetails', 'recurringsalesorders.recurrorderno=recurrsalesorderdetails.recurrorderno'),
('recurrsalesorderdetails', 'stockmaster', 'recurrsalesorderdetails.stkcode=stockmaster.stockid'),
('stockmaster', 'recurrsalesorderdetails', 'stockmaster.stockid=recurrsalesorderdetails.stkcode'),
('reportcolumns', 'reportheaders', 'reportcolumns.reportid=reportheaders.reportid'),
('reportheaders', 'reportcolumns', 'reportheaders.reportid=reportcolumns.reportid'),
('salesanalysis', 'periods', 'salesanalysis.periodno=periods.periodno'),
('periods', 'salesanalysis', 'periods.periodno=salesanalysis.periodno'),
('salescatprod', 'stockmaster', 'salescatprod.stockid=stockmaster.stockid'),
('stockmaster', 'salescatprod', 'stockmaster.stockid=salescatprod.stockid'),
('salescatprod', 'salescat', 'salescatprod.salescatid=salescat.salescatid'),
('salescat', 'salescatprod', 'salescat.salescatid=salescatprod.salescatid'),
('salesorderdetails', 'salesorders', 'salesorderdetails.orderno=salesorders.orderno'),
('salesorders', 'salesorderdetails', 'salesorders.orderno=salesorderdetails.orderno'),
('salesorderdetails', 'stockmaster', 'salesorderdetails.stkcode=stockmaster.stockid'),
('stockmaster', 'salesorderdetails', 'stockmaster.stockid=salesorderdetails.stkcode'),
('salesorders', 'custbranch', 'salesorders.branchcode=custbranch.branchcode'),
('custbranch', 'salesorders', 'custbranch.branchcode=salesorders.branchcode'),
('salesorders', 'shippers', 'salesorders.debtorno=shippers.shipper_id'),
('shippers', 'salesorders', 'shippers.shipper_id=salesorders.debtorno'),
('salesorders', 'locations', 'salesorders.fromstkloc=locations.loccode'),
('locations', 'salesorders', 'locations.loccode=salesorders.fromstkloc'),
('securitygroups', 'securityroles', 'securitygroups.secroleid=securityroles.secroleid'),
('securityroles', 'securitygroups', 'securityroles.secroleid=securitygroups.secroleid'),
('securitygroups', 'securitytokens', 'securitygroups.tokenid=securitytokens.tokenid'),
('securitytokens', 'securitygroups', 'securitytokens.tokenid=securitygroups.tokenid'),
('shipmentcharges', 'shipments', 'shipmentcharges.shiptref=shipments.shiptref'),
('shipments', 'shipmentcharges', 'shipments.shiptref=shipmentcharges.shiptref'),
('shipmentcharges', 'systypes', 'shipmentcharges.transtype=systypes.typeid'),
('systypes', 'shipmentcharges', 'systypes.typeid=shipmentcharges.transtype'),
('shipments', 'suppliers', 'shipments.supplierid=suppliers.supplierid'),
('suppliers', 'shipments', 'suppliers.supplierid=shipments.supplierid'),
('stockcheckfreeze', 'stockmaster', 'stockcheckfreeze.stockid=stockmaster.stockid'),
('stockmaster', 'stockcheckfreeze', 'stockmaster.stockid=stockcheckfreeze.stockid'),
('stockcheckfreeze', 'locations', 'stockcheckfreeze.loccode=locations.loccode'),
('locations', 'stockcheckfreeze', 'locations.loccode=stockcheckfreeze.loccode'),
('stockcounts', 'stockmaster', 'stockcounts.stockid=stockmaster.stockid'),
('stockmaster', 'stockcounts', 'stockmaster.stockid=stockcounts.stockid'),
('stockcounts', 'locations', 'stockcounts.loccode=locations.loccode'),
('locations', 'stockcounts', 'locations.loccode=stockcounts.loccode'),
('stockmaster', 'stockcategory', 'stockmaster.categoryid=stockcategory.categoryid'),
('stockcategory', 'stockmaster', 'stockcategory.categoryid=stockmaster.categoryid'),
('stockmaster', 'taxcategories', 'stockmaster.taxcatid=taxcategories.taxcatid'),
('taxcategories', 'stockmaster', 'taxcategories.taxcatid=stockmaster.taxcatid'),
('stockmoves', 'stockmaster', 'stockmoves.stockid=stockmaster.stockid'),
('stockmaster', 'stockmoves', 'stockmaster.stockid=stockmoves.stockid'),
('stockmoves', 'systypes', 'stockmoves.type=systypes.typeid'),
('systypes', 'stockmoves', 'systypes.typeid=stockmoves.type'),
('stockmoves', 'locations', 'stockmoves.loccode=locations.loccode'),
('locations', 'stockmoves', 'locations.loccode=stockmoves.loccode'),
('stockmoves', 'periods', 'stockmoves.prd=periods.periodno'),
('periods', 'stockmoves', 'periods.periodno=stockmoves.prd'),
('stockmovestaxes', 'taxauthorities', 'stockmovestaxes.taxauthid=taxauthorities.taxid'),
('taxauthorities', 'stockmovestaxes', 'taxauthorities.taxid=stockmovestaxes.taxauthid'),
('stockserialitems', 'stockmaster', 'stockserialitems.stockid=stockmaster.stockid'),
('stockmaster', 'stockserialitems', 'stockmaster.stockid=stockserialitems.stockid'),
('stockserialitems', 'locations', 'stockserialitems.loccode=locations.loccode'),
('locations', 'stockserialitems', 'locations.loccode=stockserialitems.loccode'),
('stockserialmoves', 'stockmoves', 'stockserialmoves.stockmoveno=stockmoves.stkmoveno'),
('stockmoves', 'stockserialmoves', 'stockmoves.stkmoveno=stockserialmoves.stockmoveno'),
('stockserialmoves', 'stockserialitems', 'stockserialmoves.stockid=stockserialitems.stockid'),
('stockserialitems', 'stockserialmoves', 'stockserialitems.stockid=stockserialmoves.stockid'),
('suppallocs', 'supptrans', 'suppallocs.transid_allocfrom=supptrans.id'),
('supptrans', 'suppallocs', 'supptrans.id=suppallocs.transid_allocfrom'),
('suppallocs', 'supptrans', 'suppallocs.transid_allocto=supptrans.id'),
('supptrans', 'suppallocs', 'supptrans.id=suppallocs.transid_allocto'),
('suppliercontacts', 'suppliers', 'suppliercontacts.supplierid=suppliers.supplierid'),
('suppliers', 'suppliercontacts', 'suppliers.supplierid=suppliercontacts.supplierid'),
('suppliers', 'currencies', 'suppliers.currcode=currencies.currabrev'),
('currencies', 'suppliers', 'currencies.currabrev=suppliers.currcode'),
('suppliers', 'paymentterms', 'suppliers.paymentterms=paymentterms.termsindicator'),
('paymentterms', 'suppliers', 'paymentterms.termsindicator=suppliers.paymentterms'),
('suppliers', 'taxgroups', 'suppliers.taxgroupid=taxgroups.taxgroupid'),
('taxgroups', 'suppliers', 'taxgroups.taxgroupid=suppliers.taxgroupid'),
('supptrans', 'systypes', 'supptrans.type=systypes.typeid'),
('systypes', 'supptrans', 'systypes.typeid=supptrans.type'),
('supptrans', 'suppliers', 'supptrans.supplierno=suppliers.supplierid'),
('suppliers', 'supptrans', 'suppliers.supplierid=supptrans.supplierno'),
('supptranstaxes', 'taxauthorities', 'supptranstaxes.taxauthid=taxauthorities.taxid'),
('taxauthorities', 'supptranstaxes', 'taxauthorities.taxid=supptranstaxes.taxauthid'),
('supptranstaxes', 'supptrans', 'supptranstaxes.supptransid=supptrans.id'),
('supptrans', 'supptranstaxes', 'supptrans.id=supptranstaxes.supptransid'),
('taxauthorities', 'chartmaster', 'taxauthorities.taxglcode=chartmaster.accountcode'),
('chartmaster', 'taxauthorities', 'chartmaster.accountcode=taxauthorities.taxglcode'),
('taxauthorities', 'chartmaster', 'taxauthorities.purchtaxglaccount=chartmaster.accountcode'),
('chartmaster', 'taxauthorities', 'chartmaster.accountcode=taxauthorities.purchtaxglaccount'),
('taxauthrates', 'taxauthorities', 'taxauthrates.taxauthority=taxauthorities.taxid'),
('taxauthorities', 'taxauthrates', 'taxauthorities.taxid=taxauthrates.taxauthority'),
('taxauthrates', 'taxcategories', 'taxauthrates.taxcatid=taxcategories.taxcatid'),
('taxcategories', 'taxauthrates', 'taxcategories.taxcatid=taxauthrates.taxcatid'),
('taxauthrates', 'taxprovinces', 'taxauthrates.dispatchtaxprovince=taxprovinces.taxprovinceid'),
('taxprovinces', 'taxauthrates', 'taxprovinces.taxprovinceid=taxauthrates.dispatchtaxprovince'),
('taxgrouptaxes', 'taxgroups', 'taxgrouptaxes.taxgroupid=taxgroups.taxgroupid'),
('taxgroups', 'taxgrouptaxes', 'taxgroups.taxgroupid=taxgrouptaxes.taxgroupid'),
('taxgrouptaxes', 'taxauthorities', 'taxgrouptaxes.taxauthid=taxauthorities.taxid'),
('taxauthorities', 'taxgrouptaxes', 'taxauthorities.taxid=taxgrouptaxes.taxauthid'),
('workcentres', 'locations', 'workcentres.location=locations.loccode'),
('locations', 'workcentres', 'locations.loccode=workcentres.location'),
('worksorders', 'locations', 'worksorders.loccode=locations.loccode'),
('locations', 'worksorders', 'locations.loccode=worksorders.loccode'),
('worksorders', 'stockmaster', 'worksorders.stockid=stockmaster.stockid'),
('stockmaster', 'worksorders', 'stockmaster.stockid=worksorders.stockid'),
('www_users', 'locations', 'www_users.defaultlocation=locations.loccode'),
('locations', 'www_users', 'locations.loccode=www_users.defaultlocation'),
('accountgroups', 'accountsection', 'accountgroups.sectioninaccounts=accountsection.sectionid'),
('accountsection', 'accountgroups', 'accountsection.sectionid=accountgroups.sectioninaccounts'),
('bankaccounts', 'chartmaster', 'bankaccounts.accountcode=chartmaster.accountcode'),
('chartmaster', 'bankaccounts', 'chartmaster.accountcode=bankaccounts.accountcode'),
('banktrans', 'systypes', 'banktrans.type=systypes.typeid'),
('systypes', 'banktrans', 'systypes.typeid=banktrans.type'),
('banktrans', 'bankaccounts', 'banktrans.bankact=bankaccounts.accountcode'),
('bankaccounts', 'banktrans', 'bankaccounts.accountcode=banktrans.bankact'),
('bom', 'stockmaster', 'bom.parent=stockmaster.stockid'),
('stockmaster', 'bom', 'stockmaster.stockid=bom.parent'),
('bom', 'stockmaster', 'bom.component=stockmaster.stockid'),
('stockmaster', 'bom', 'stockmaster.stockid=bom.component'),
('bom', 'workcentres', 'bom.workcentreadded=workcentres.code'),
('workcentres', 'bom', 'workcentres.code=bom.workcentreadded'),
('bom', 'locations', 'bom.loccode=locations.loccode'),
('locations', 'bom', 'locations.loccode=bom.loccode'),
('buckets', 'workcentres', 'buckets.workcentre=workcentres.code'),
('workcentres', 'buckets', 'workcentres.code=buckets.workcentre'),
('chartdetails', 'chartmaster', 'chartdetails.accountcode=chartmaster.accountcode'),
('chartmaster', 'chartdetails', 'chartmaster.accountcode=chartdetails.accountcode'),
('chartdetails', 'periods', 'chartdetails.period=periods.periodno'),
('periods', 'chartdetails', 'periods.periodno=chartdetails.period'),
('chartmaster', 'accountgroups', 'chartmaster.group_=accountgroups.groupname'),
('accountgroups', 'chartmaster', 'accountgroups.groupname=chartmaster.group_'),
('contractbom', 'workcentres', 'contractbom.workcentreadded=workcentres.code'),
('workcentres', 'contractbom', 'workcentres.code=contractbom.workcentreadded'),
('contractbom', 'locations', 'contractbom.loccode=locations.loccode'),
('locations', 'contractbom', 'locations.loccode=contractbom.loccode'),
('contractbom', 'stockmaster', 'contractbom.component=stockmaster.stockid'),
('stockmaster', 'contractbom', 'stockmaster.stockid=contractbom.component'),
('contractreqts', 'contracts', 'contractreqts.contract=contracts.contractref'),
('contracts', 'contractreqts', 'contracts.contractref=contractreqts.contract'),
('contracts', 'custbranch', 'contracts.debtorno=custbranch.debtorno'),
('custbranch', 'contracts', 'custbranch.debtorno=contracts.debtorno'),
('contracts', 'stockcategory', 'contracts.branchcode=stockcategory.categoryid'),
('stockcategory', 'contracts', 'stockcategory.categoryid=contracts.branchcode'),
('contracts', 'salestypes', 'contracts.typeabbrev=salestypes.typeabbrev'),
('salestypes', 'contracts', 'salestypes.typeabbrev=contracts.typeabbrev'),
('custallocns', 'debtortrans', 'custallocns.transid_allocfrom=debtortrans.id'),
('debtortrans', 'custallocns', 'debtortrans.id=custallocns.transid_allocfrom'),
('custallocns', 'debtortrans', 'custallocns.transid_allocto=debtortrans.id'),
('debtortrans', 'custallocns', 'debtortrans.id=custallocns.transid_allocto'),
('custbranch', 'debtorsmaster', 'custbranch.debtorno=debtorsmaster.debtorno'),
('debtorsmaster', 'custbranch', 'debtorsmaster.debtorno=custbranch.debtorno'),
('custbranch', 'areas', 'custbranch.area=areas.areacode'),
('areas', 'custbranch', 'areas.areacode=custbranch.area'),
('custbranch', 'salesman', 'custbranch.salesman=salesman.salesmancode'),
('salesman', 'custbranch', 'salesman.salesmancode=custbranch.salesman'),
('custbranch', 'locations', 'custbranch.defaultlocation=locations.loccode'),
('locations', 'custbranch', 'locations.loccode=custbranch.defaultlocation'),
('custbranch', 'shippers', 'custbranch.defaultshipvia=shippers.shipper_id'),
('shippers', 'custbranch', 'shippers.shipper_id=custbranch.defaultshipvia'),
('debtorsmaster', 'holdreasons', 'debtorsmaster.holdreason=holdreasons.reasoncode'),
('holdreasons', 'debtorsmaster', 'holdreasons.reasoncode=debtorsmaster.holdreason'),
('debtorsmaster', 'currencies', 'debtorsmaster.currcode=currencies.currabrev'),
('currencies', 'debtorsmaster', 'currencies.currabrev=debtorsmaster.currcode'),
('debtorsmaster', 'paymentterms', 'debtorsmaster.paymentterms=paymentterms.termsindicator'),
('paymentterms', 'debtorsmaster', 'paymentterms.termsindicator=debtorsmaster.paymentterms'),
('debtorsmaster', 'salestypes', 'debtorsmaster.salestype=salestypes.typeabbrev'),
('salestypes', 'debtorsmaster', 'salestypes.typeabbrev=debtorsmaster.salestype'),
('debtortrans', 'custbranch', 'debtortrans.debtorno=custbranch.debtorno'),
('custbranch', 'debtortrans', 'custbranch.debtorno=debtortrans.debtorno'),
('debtortrans', 'systypes', 'debtortrans.type=systypes.typeid'),
('systypes', 'debtortrans', 'systypes.typeid=debtortrans.type'),
('debtortrans', 'periods', 'debtortrans.prd=periods.periodno'),
('periods', 'debtortrans', 'periods.periodno=debtortrans.prd'),
('debtortranstaxes', 'taxauthorities', 'debtortranstaxes.taxauthid=taxauthorities.taxid'),
('taxauthorities', 'debtortranstaxes', 'taxauthorities.taxid=debtortranstaxes.taxauthid'),
('debtortranstaxes', 'debtortrans', 'debtortranstaxes.debtortransid=debtortrans.id'),
('debtortrans', 'debtortranstaxes', 'debtortrans.id=debtortranstaxes.debtortransid'),
('discountmatrix', 'salestypes', 'discountmatrix.salestype=salestypes.typeabbrev'),
('salestypes', 'discountmatrix', 'salestypes.typeabbrev=discountmatrix.salestype'),
('freightcosts', 'locations', 'freightcosts.locationfrom=locations.loccode'),
('locations', 'freightcosts', 'locations.loccode=freightcosts.locationfrom'),
('freightcosts', 'shippers', 'freightcosts.shipperid=shippers.shipper_id'),
('shippers', 'freightcosts', 'shippers.shipper_id=freightcosts.shipperid'),
('gltrans', 'chartmaster', 'gltrans.account=chartmaster.accountcode'),
('chartmaster', 'gltrans', 'chartmaster.accountcode=gltrans.account'),
('gltrans', 'systypes', 'gltrans.type=systypes.typeid'),
('systypes', 'gltrans', 'systypes.typeid=gltrans.type'),
('gltrans', 'periods', 'gltrans.periodno=periods.periodno'),
('periods', 'gltrans', 'periods.periodno=gltrans.periodno'),
('grns', 'suppliers', 'grns.supplierid=suppliers.supplierid'),
('suppliers', 'grns', 'suppliers.supplierid=grns.supplierid'),
('grns', 'purchorderdetails', 'grns.podetailitem=purchorderdetails.podetailitem'),
('purchorderdetails', 'grns', 'purchorderdetails.podetailitem=grns.podetailitem'),
('locations', 'taxprovinces', 'locations.taxprovinceid=taxprovinces.taxprovinceid'),
('taxprovinces', 'locations', 'taxprovinces.taxprovinceid=locations.taxprovinceid'),
('locstock', 'locations', 'locstock.loccode=locations.loccode'),
('locations', 'locstock', 'locations.loccode=locstock.loccode'),
('locstock', 'stockmaster', 'locstock.stockid=stockmaster.stockid'),
('stockmaster', 'locstock', 'stockmaster.stockid=locstock.stockid'),
('loctransfers', 'locations', 'loctransfers.shiploc=locations.loccode'),
('locations', 'loctransfers', 'locations.loccode=loctransfers.shiploc'),
('loctransfers', 'locations', 'loctransfers.recloc=locations.loccode'),
('locations', 'loctransfers', 'locations.loccode=loctransfers.recloc'),
('loctransfers', 'stockmaster', 'loctransfers.stockid=stockmaster.stockid'),
('stockmaster', 'loctransfers', 'stockmaster.stockid=loctransfers.stockid'),
('orderdeliverydifferencesl', 'stockmaster', 'orderdeliverydifferenceslog.stockid=stockmaster.stockid'),
('stockmaster', 'orderdeliverydifferencesl', 'stockmaster.stockid=orderdeliverydifferenceslog.stockid'),
('orderdeliverydifferencesl', 'custbranch', 'orderdeliverydifferenceslog.debtorno=custbranch.debtorno'),
('custbranch', 'orderdeliverydifferencesl', 'custbranch.debtorno=orderdeliverydifferenceslog.debtorno'),
('orderdeliverydifferencesl', 'salesorders', 'orderdeliverydifferenceslog.branchcode=salesorders.orderno'),
('salesorders', 'orderdeliverydifferencesl', 'salesorders.orderno=orderdeliverydifferenceslog.branchcode'),
('prices', 'stockmaster', 'prices.stockid=stockmaster.stockid'),
('stockmaster', 'prices', 'stockmaster.stockid=prices.stockid'),
('prices', 'currencies', 'prices.currabrev=currencies.currabrev'),
('currencies', 'prices', 'currencies.currabrev=prices.currabrev'),
('prices', 'salestypes', 'prices.typeabbrev=salestypes.typeabbrev'),
('salestypes', 'prices', 'salestypes.typeabbrev=prices.typeabbrev'),
('purchdata', 'stockmaster', 'purchdata.stockid=stockmaster.stockid'),
('stockmaster', 'purchdata', 'stockmaster.stockid=purchdata.stockid'),
('purchdata', 'suppliers', 'purchdata.supplierno=suppliers.supplierid'),
('suppliers', 'purchdata', 'suppliers.supplierid=purchdata.supplierno'),
('purchorderdetails', 'purchorders', 'purchorderdetails.orderno=purchorders.orderno'),
('purchorders', 'purchorderdetails', 'purchorders.orderno=purchorderdetails.orderno'),
('purchorders', 'suppliers', 'purchorders.supplierno=suppliers.supplierid'),
('suppliers', 'purchorders', 'suppliers.supplierid=purchorders.supplierno'),
('purchorders', 'locations', 'purchorders.intostocklocation=locations.loccode'),
('locations', 'purchorders', 'locations.loccode=purchorders.intostocklocation'),
('recurringsalesorders', 'custbranch', 'recurringsalesorders.branchcode=custbranch.branchcode'),
('custbranch', 'recurringsalesorders', 'custbranch.branchcode=recurringsalesorders.branchcode'),
('recurrsalesorderdetails', 'recurringsalesorders', 'recurrsalesorderdetails.recurrorderno=recurringsalesorders.recurrorderno'),
('recurringsalesorders', 'recurrsalesorderdetails', 'recurringsalesorders.recurrorderno=recurrsalesorderdetails.recurrorderno'),
('recurrsalesorderdetails', 'stockmaster', 'recurrsalesorderdetails.stkcode=stockmaster.stockid'),
('stockmaster', 'recurrsalesorderdetails', 'stockmaster.stockid=recurrsalesorderdetails.stkcode'),
('reportcolumns', 'reportheaders', 'reportcolumns.reportid=reportheaders.reportid'),
('reportheaders', 'reportcolumns', 'reportheaders.reportid=reportcolumns.reportid'),
('salesanalysis', 'periods', 'salesanalysis.periodno=periods.periodno'),
('periods', 'salesanalysis', 'periods.periodno=salesanalysis.periodno'),
('salescatprod', 'stockmaster', 'salescatprod.stockid=stockmaster.stockid'),
('stockmaster', 'salescatprod', 'stockmaster.stockid=salescatprod.stockid'),
('salescatprod', 'salescat', 'salescatprod.salescatid=salescat.salescatid'),
('salescat', 'salescatprod', 'salescat.salescatid=salescatprod.salescatid'),
('salesorderdetails', 'salesorders', 'salesorderdetails.orderno=salesorders.orderno'),
('salesorders', 'salesorderdetails', 'salesorders.orderno=salesorderdetails.orderno'),
('salesorderdetails', 'stockmaster', 'salesorderdetails.stkcode=stockmaster.stockid'),
('stockmaster', 'salesorderdetails', 'stockmaster.stockid=salesorderdetails.stkcode'),
('salesorders', 'custbranch', 'salesorders.branchcode=custbranch.branchcode'),
('custbranch', 'salesorders', 'custbranch.branchcode=salesorders.branchcode'),
('salesorders', 'shippers', 'salesorders.debtorno=shippers.shipper_id'),
('shippers', 'salesorders', 'shippers.shipper_id=salesorders.debtorno'),
('salesorders', 'locations', 'salesorders.fromstkloc=locations.loccode'),
('locations', 'salesorders', 'locations.loccode=salesorders.fromstkloc'),
('securitygroups', 'securityroles', 'securitygroups.secroleid=securityroles.secroleid'),
('securityroles', 'securitygroups', 'securityroles.secroleid=securitygroups.secroleid'),
('securitygroups', 'securitytokens', 'securitygroups.tokenid=securitytokens.tokenid'),
('securitytokens', 'securitygroups', 'securitytokens.tokenid=securitygroups.tokenid'),
('shipmentcharges', 'shipments', 'shipmentcharges.shiptref=shipments.shiptref'),
('shipments', 'shipmentcharges', 'shipments.shiptref=shipmentcharges.shiptref'),
('shipmentcharges', 'systypes', 'shipmentcharges.transtype=systypes.typeid'),
('systypes', 'shipmentcharges', 'systypes.typeid=shipmentcharges.transtype'),
('shipments', 'suppliers', 'shipments.supplierid=suppliers.supplierid'),
('suppliers', 'shipments', 'suppliers.supplierid=shipments.supplierid'),
('stockcheckfreeze', 'stockmaster', 'stockcheckfreeze.stockid=stockmaster.stockid'),
('stockmaster', 'stockcheckfreeze', 'stockmaster.stockid=stockcheckfreeze.stockid'),
('stockcheckfreeze', 'locations', 'stockcheckfreeze.loccode=locations.loccode'),
('locations', 'stockcheckfreeze', 'locations.loccode=stockcheckfreeze.loccode'),
('stockcounts', 'stockmaster', 'stockcounts.stockid=stockmaster.stockid'),
('stockmaster', 'stockcounts', 'stockmaster.stockid=stockcounts.stockid'),
('stockcounts', 'locations', 'stockcounts.loccode=locations.loccode'),
('locations', 'stockcounts', 'locations.loccode=stockcounts.loccode'),
('stockmaster', 'stockcategory', 'stockmaster.categoryid=stockcategory.categoryid'),
('stockcategory', 'stockmaster', 'stockcategory.categoryid=stockmaster.categoryid'),
('stockmaster', 'taxcategories', 'stockmaster.taxcatid=taxcategories.taxcatid'),
('taxcategories', 'stockmaster', 'taxcategories.taxcatid=stockmaster.taxcatid'),
('stockmoves', 'stockmaster', 'stockmoves.stockid=stockmaster.stockid'),
('stockmaster', 'stockmoves', 'stockmaster.stockid=stockmoves.stockid'),
('stockmoves', 'systypes', 'stockmoves.type=systypes.typeid'),
('systypes', 'stockmoves', 'systypes.typeid=stockmoves.type'),
('stockmoves', 'locations', 'stockmoves.loccode=locations.loccode'),
('locations', 'stockmoves', 'locations.loccode=stockmoves.loccode'),
('stockmoves', 'periods', 'stockmoves.prd=periods.periodno'),
('periods', 'stockmoves', 'periods.periodno=stockmoves.prd'),
('stockmovestaxes', 'taxauthorities', 'stockmovestaxes.taxauthid=taxauthorities.taxid'),
('taxauthorities', 'stockmovestaxes', 'taxauthorities.taxid=stockmovestaxes.taxauthid'),
('stockserialitems', 'stockmaster', 'stockserialitems.stockid=stockmaster.stockid'),
('stockmaster', 'stockserialitems', 'stockmaster.stockid=stockserialitems.stockid'),
('stockserialitems', 'locations', 'stockserialitems.loccode=locations.loccode'),
('locations', 'stockserialitems', 'locations.loccode=stockserialitems.loccode'),
('stockserialmoves', 'stockmoves', 'stockserialmoves.stockmoveno=stockmoves.stkmoveno'),
('stockmoves', 'stockserialmoves', 'stockmoves.stkmoveno=stockserialmoves.stockmoveno'),
('stockserialmoves', 'stockserialitems', 'stockserialmoves.stockid=stockserialitems.stockid'),
('stockserialitems', 'stockserialmoves', 'stockserialitems.stockid=stockserialmoves.stockid'),
('suppallocs', 'supptrans', 'suppallocs.transid_allocfrom=supptrans.id'),
('supptrans', 'suppallocs', 'supptrans.id=suppallocs.transid_allocfrom'),
('suppallocs', 'supptrans', 'suppallocs.transid_allocto=supptrans.id'),
('supptrans', 'suppallocs', 'supptrans.id=suppallocs.transid_allocto'),
('suppliercontacts', 'suppliers', 'suppliercontacts.supplierid=suppliers.supplierid'),
('suppliers', 'suppliercontacts', 'suppliers.supplierid=suppliercontacts.supplierid'),
('suppliers', 'currencies', 'suppliers.currcode=currencies.currabrev'),
('currencies', 'suppliers', 'currencies.currabrev=suppliers.currcode'),
('suppliers', 'paymentterms', 'suppliers.paymentterms=paymentterms.termsindicator'),
('paymentterms', 'suppliers', 'paymentterms.termsindicator=suppliers.paymentterms'),
('suppliers', 'taxgroups', 'suppliers.taxgroupid=taxgroups.taxgroupid'),
('taxgroups', 'suppliers', 'taxgroups.taxgroupid=suppliers.taxgroupid'),
('supptrans', 'systypes', 'supptrans.type=systypes.typeid'),
('systypes', 'supptrans', 'systypes.typeid=supptrans.type'),
('supptrans', 'suppliers', 'supptrans.supplierno=suppliers.supplierid'),
('suppliers', 'supptrans', 'suppliers.supplierid=supptrans.supplierno'),
('supptranstaxes', 'taxauthorities', 'supptranstaxes.taxauthid=taxauthorities.taxid'),
('taxauthorities', 'supptranstaxes', 'taxauthorities.taxid=supptranstaxes.taxauthid');
INSERT INTO `reportlinks` (`table1`, `table2`, `equation`) VALUES
('supptranstaxes', 'supptrans', 'supptranstaxes.supptransid=supptrans.id'),
('supptrans', 'supptranstaxes', 'supptrans.id=supptranstaxes.supptransid'),
('taxauthorities', 'chartmaster', 'taxauthorities.taxglcode=chartmaster.accountcode'),
('chartmaster', 'taxauthorities', 'chartmaster.accountcode=taxauthorities.taxglcode'),
('taxauthorities', 'chartmaster', 'taxauthorities.purchtaxglaccount=chartmaster.accountcode'),
('chartmaster', 'taxauthorities', 'chartmaster.accountcode=taxauthorities.purchtaxglaccount'),
('taxauthrates', 'taxauthorities', 'taxauthrates.taxauthority=taxauthorities.taxid'),
('taxauthorities', 'taxauthrates', 'taxauthorities.taxid=taxauthrates.taxauthority'),
('taxauthrates', 'taxcategories', 'taxauthrates.taxcatid=taxcategories.taxcatid'),
('taxcategories', 'taxauthrates', 'taxcategories.taxcatid=taxauthrates.taxcatid'),
('taxauthrates', 'taxprovinces', 'taxauthrates.dispatchtaxprovince=taxprovinces.taxprovinceid'),
('taxprovinces', 'taxauthrates', 'taxprovinces.taxprovinceid=taxauthrates.dispatchtaxprovince'),
('taxgrouptaxes', 'taxgroups', 'taxgrouptaxes.taxgroupid=taxgroups.taxgroupid'),
('taxgroups', 'taxgrouptaxes', 'taxgroups.taxgroupid=taxgrouptaxes.taxgroupid'),
('taxgrouptaxes', 'taxauthorities', 'taxgrouptaxes.taxauthid=taxauthorities.taxid'),
('taxauthorities', 'taxgrouptaxes', 'taxauthorities.taxid=taxgrouptaxes.taxauthid'),
('workcentres', 'locations', 'workcentres.location=locations.loccode'),
('locations', 'workcentres', 'locations.loccode=workcentres.location'),
('worksorders', 'locations', 'worksorders.loccode=locations.loccode'),
('locations', 'worksorders', 'locations.loccode=worksorders.loccode'),
('worksorders', 'stockmaster', 'worksorders.stockid=stockmaster.stockid'),
('stockmaster', 'worksorders', 'stockmaster.stockid=worksorders.stockid'),
('www_users', 'locations', 'www_users.defaultlocation=locations.loccode'),
('locations', 'www_users', 'locations.loccode=www_users.defaultlocation'),
('accountgroups', 'accountsection', 'accountgroups.sectioninaccounts=accountsection.sectionid'),
('accountsection', 'accountgroups', 'accountsection.sectionid=accountgroups.sectioninaccounts'),
('bankaccounts', 'chartmaster', 'bankaccounts.accountcode=chartmaster.accountcode'),
('chartmaster', 'bankaccounts', 'chartmaster.accountcode=bankaccounts.accountcode'),
('banktrans', 'systypes', 'banktrans.type=systypes.typeid'),
('systypes', 'banktrans', 'systypes.typeid=banktrans.type'),
('banktrans', 'bankaccounts', 'banktrans.bankact=bankaccounts.accountcode'),
('bankaccounts', 'banktrans', 'bankaccounts.accountcode=banktrans.bankact'),
('bom', 'stockmaster', 'bom.parent=stockmaster.stockid'),
('stockmaster', 'bom', 'stockmaster.stockid=bom.parent'),
('bom', 'stockmaster', 'bom.component=stockmaster.stockid'),
('stockmaster', 'bom', 'stockmaster.stockid=bom.component'),
('bom', 'workcentres', 'bom.workcentreadded=workcentres.code'),
('workcentres', 'bom', 'workcentres.code=bom.workcentreadded'),
('bom', 'locations', 'bom.loccode=locations.loccode'),
('locations', 'bom', 'locations.loccode=bom.loccode'),
('buckets', 'workcentres', 'buckets.workcentre=workcentres.code'),
('workcentres', 'buckets', 'workcentres.code=buckets.workcentre'),
('chartdetails', 'chartmaster', 'chartdetails.accountcode=chartmaster.accountcode'),
('chartmaster', 'chartdetails', 'chartmaster.accountcode=chartdetails.accountcode'),
('chartdetails', 'periods', 'chartdetails.period=periods.periodno'),
('periods', 'chartdetails', 'periods.periodno=chartdetails.period'),
('chartmaster', 'accountgroups', 'chartmaster.group_=accountgroups.groupname'),
('accountgroups', 'chartmaster', 'accountgroups.groupname=chartmaster.group_'),
('contractbom', 'workcentres', 'contractbom.workcentreadded=workcentres.code'),
('workcentres', 'contractbom', 'workcentres.code=contractbom.workcentreadded'),
('contractbom', 'locations', 'contractbom.loccode=locations.loccode'),
('locations', 'contractbom', 'locations.loccode=contractbom.loccode'),
('contractbom', 'stockmaster', 'contractbom.component=stockmaster.stockid'),
('stockmaster', 'contractbom', 'stockmaster.stockid=contractbom.component'),
('contractreqts', 'contracts', 'contractreqts.contract=contracts.contractref'),
('contracts', 'contractreqts', 'contracts.contractref=contractreqts.contract'),
('contracts', 'custbranch', 'contracts.debtorno=custbranch.debtorno'),
('custbranch', 'contracts', 'custbranch.debtorno=contracts.debtorno'),
('contracts', 'stockcategory', 'contracts.branchcode=stockcategory.categoryid'),
('stockcategory', 'contracts', 'stockcategory.categoryid=contracts.branchcode'),
('contracts', 'salestypes', 'contracts.typeabbrev=salestypes.typeabbrev'),
('salestypes', 'contracts', 'salestypes.typeabbrev=contracts.typeabbrev'),
('custallocns', 'debtortrans', 'custallocns.transid_allocfrom=debtortrans.id'),
('debtortrans', 'custallocns', 'debtortrans.id=custallocns.transid_allocfrom'),
('custallocns', 'debtortrans', 'custallocns.transid_allocto=debtortrans.id'),
('debtortrans', 'custallocns', 'debtortrans.id=custallocns.transid_allocto'),
('custbranch', 'debtorsmaster', 'custbranch.debtorno=debtorsmaster.debtorno'),
('debtorsmaster', 'custbranch', 'debtorsmaster.debtorno=custbranch.debtorno'),
('custbranch', 'areas', 'custbranch.area=areas.areacode'),
('areas', 'custbranch', 'areas.areacode=custbranch.area'),
('custbranch', 'salesman', 'custbranch.salesman=salesman.salesmancode'),
('salesman', 'custbranch', 'salesman.salesmancode=custbranch.salesman'),
('custbranch', 'locations', 'custbranch.defaultlocation=locations.loccode'),
('locations', 'custbranch', 'locations.loccode=custbranch.defaultlocation'),
('custbranch', 'shippers', 'custbranch.defaultshipvia=shippers.shipper_id'),
('shippers', 'custbranch', 'shippers.shipper_id=custbranch.defaultshipvia'),
('debtorsmaster', 'holdreasons', 'debtorsmaster.holdreason=holdreasons.reasoncode'),
('holdreasons', 'debtorsmaster', 'holdreasons.reasoncode=debtorsmaster.holdreason'),
('debtorsmaster', 'currencies', 'debtorsmaster.currcode=currencies.currabrev'),
('currencies', 'debtorsmaster', 'currencies.currabrev=debtorsmaster.currcode'),
('debtorsmaster', 'paymentterms', 'debtorsmaster.paymentterms=paymentterms.termsindicator'),
('paymentterms', 'debtorsmaster', 'paymentterms.termsindicator=debtorsmaster.paymentterms'),
('debtorsmaster', 'salestypes', 'debtorsmaster.salestype=salestypes.typeabbrev'),
('salestypes', 'debtorsmaster', 'salestypes.typeabbrev=debtorsmaster.salestype'),
('debtortrans', 'custbranch', 'debtortrans.debtorno=custbranch.debtorno'),
('custbranch', 'debtortrans', 'custbranch.debtorno=debtortrans.debtorno'),
('debtortrans', 'systypes', 'debtortrans.type=systypes.typeid'),
('systypes', 'debtortrans', 'systypes.typeid=debtortrans.type'),
('debtortrans', 'periods', 'debtortrans.prd=periods.periodno'),
('periods', 'debtortrans', 'periods.periodno=debtortrans.prd'),
('debtortranstaxes', 'taxauthorities', 'debtortranstaxes.taxauthid=taxauthorities.taxid'),
('taxauthorities', 'debtortranstaxes', 'taxauthorities.taxid=debtortranstaxes.taxauthid'),
('debtortranstaxes', 'debtortrans', 'debtortranstaxes.debtortransid=debtortrans.id'),
('debtortrans', 'debtortranstaxes', 'debtortrans.id=debtortranstaxes.debtortransid'),
('discountmatrix', 'salestypes', 'discountmatrix.salestype=salestypes.typeabbrev'),
('salestypes', 'discountmatrix', 'salestypes.typeabbrev=discountmatrix.salestype'),
('freightcosts', 'locations', 'freightcosts.locationfrom=locations.loccode'),
('locations', 'freightcosts', 'locations.loccode=freightcosts.locationfrom'),
('freightcosts', 'shippers', 'freightcosts.shipperid=shippers.shipper_id'),
('shippers', 'freightcosts', 'shippers.shipper_id=freightcosts.shipperid'),
('gltrans', 'chartmaster', 'gltrans.account=chartmaster.accountcode'),
('chartmaster', 'gltrans', 'chartmaster.accountcode=gltrans.account'),
('gltrans', 'systypes', 'gltrans.type=systypes.typeid'),
('systypes', 'gltrans', 'systypes.typeid=gltrans.type'),
('gltrans', 'periods', 'gltrans.periodno=periods.periodno'),
('periods', 'gltrans', 'periods.periodno=gltrans.periodno'),
('grns', 'suppliers', 'grns.supplierid=suppliers.supplierid'),
('suppliers', 'grns', 'suppliers.supplierid=grns.supplierid'),
('grns', 'purchorderdetails', 'grns.podetailitem=purchorderdetails.podetailitem'),
('purchorderdetails', 'grns', 'purchorderdetails.podetailitem=grns.podetailitem'),
('locations', 'taxprovinces', 'locations.taxprovinceid=taxprovinces.taxprovinceid'),
('taxprovinces', 'locations', 'taxprovinces.taxprovinceid=locations.taxprovinceid'),
('locstock', 'locations', 'locstock.loccode=locations.loccode'),
('locations', 'locstock', 'locations.loccode=locstock.loccode'),
('locstock', 'stockmaster', 'locstock.stockid=stockmaster.stockid'),
('stockmaster', 'locstock', 'stockmaster.stockid=locstock.stockid'),
('loctransfers', 'locations', 'loctransfers.shiploc=locations.loccode'),
('locations', 'loctransfers', 'locations.loccode=loctransfers.shiploc'),
('loctransfers', 'locations', 'loctransfers.recloc=locations.loccode'),
('locations', 'loctransfers', 'locations.loccode=loctransfers.recloc'),
('loctransfers', 'stockmaster', 'loctransfers.stockid=stockmaster.stockid'),
('stockmaster', 'loctransfers', 'stockmaster.stockid=loctransfers.stockid'),
('orderdeliverydifferencesl', 'stockmaster', 'orderdeliverydifferenceslog.stockid=stockmaster.stockid'),
('stockmaster', 'orderdeliverydifferencesl', 'stockmaster.stockid=orderdeliverydifferenceslog.stockid'),
('orderdeliverydifferencesl', 'custbranch', 'orderdeliverydifferenceslog.debtorno=custbranch.debtorno'),
('custbranch', 'orderdeliverydifferencesl', 'custbranch.debtorno=orderdeliverydifferenceslog.debtorno'),
('orderdeliverydifferencesl', 'salesorders', 'orderdeliverydifferenceslog.branchcode=salesorders.orderno'),
('salesorders', 'orderdeliverydifferencesl', 'salesorders.orderno=orderdeliverydifferenceslog.branchcode'),
('prices', 'stockmaster', 'prices.stockid=stockmaster.stockid'),
('stockmaster', 'prices', 'stockmaster.stockid=prices.stockid'),
('prices', 'currencies', 'prices.currabrev=currencies.currabrev'),
('currencies', 'prices', 'currencies.currabrev=prices.currabrev'),
('prices', 'salestypes', 'prices.typeabbrev=salestypes.typeabbrev'),
('salestypes', 'prices', 'salestypes.typeabbrev=prices.typeabbrev'),
('purchdata', 'stockmaster', 'purchdata.stockid=stockmaster.stockid'),
('stockmaster', 'purchdata', 'stockmaster.stockid=purchdata.stockid'),
('purchdata', 'suppliers', 'purchdata.supplierno=suppliers.supplierid'),
('suppliers', 'purchdata', 'suppliers.supplierid=purchdata.supplierno'),
('purchorderdetails', 'purchorders', 'purchorderdetails.orderno=purchorders.orderno'),
('purchorders', 'purchorderdetails', 'purchorders.orderno=purchorderdetails.orderno'),
('purchorders', 'suppliers', 'purchorders.supplierno=suppliers.supplierid'),
('suppliers', 'purchorders', 'suppliers.supplierid=purchorders.supplierno'),
('purchorders', 'locations', 'purchorders.intostocklocation=locations.loccode'),
('locations', 'purchorders', 'locations.loccode=purchorders.intostocklocation'),
('recurringsalesorders', 'custbranch', 'recurringsalesorders.branchcode=custbranch.branchcode'),
('custbranch', 'recurringsalesorders', 'custbranch.branchcode=recurringsalesorders.branchcode'),
('recurrsalesorderdetails', 'recurringsalesorders', 'recurrsalesorderdetails.recurrorderno=recurringsalesorders.recurrorderno'),
('recurringsalesorders', 'recurrsalesorderdetails', 'recurringsalesorders.recurrorderno=recurrsalesorderdetails.recurrorderno'),
('recurrsalesorderdetails', 'stockmaster', 'recurrsalesorderdetails.stkcode=stockmaster.stockid'),
('stockmaster', 'recurrsalesorderdetails', 'stockmaster.stockid=recurrsalesorderdetails.stkcode'),
('reportcolumns', 'reportheaders', 'reportcolumns.reportid=reportheaders.reportid'),
('reportheaders', 'reportcolumns', 'reportheaders.reportid=reportcolumns.reportid'),
('salesanalysis', 'periods', 'salesanalysis.periodno=periods.periodno'),
('periods', 'salesanalysis', 'periods.periodno=salesanalysis.periodno'),
('salescatprod', 'stockmaster', 'salescatprod.stockid=stockmaster.stockid'),
('stockmaster', 'salescatprod', 'stockmaster.stockid=salescatprod.stockid'),
('salescatprod', 'salescat', 'salescatprod.salescatid=salescat.salescatid'),
('salescat', 'salescatprod', 'salescat.salescatid=salescatprod.salescatid'),
('salesorderdetails', 'salesorders', 'salesorderdetails.orderno=salesorders.orderno'),
('salesorders', 'salesorderdetails', 'salesorders.orderno=salesorderdetails.orderno'),
('salesorderdetails', 'stockmaster', 'salesorderdetails.stkcode=stockmaster.stockid'),
('stockmaster', 'salesorderdetails', 'stockmaster.stockid=salesorderdetails.stkcode'),
('salesorders', 'custbranch', 'salesorders.branchcode=custbranch.branchcode'),
('custbranch', 'salesorders', 'custbranch.branchcode=salesorders.branchcode'),
('salesorders', 'shippers', 'salesorders.debtorno=shippers.shipper_id'),
('shippers', 'salesorders', 'shippers.shipper_id=salesorders.debtorno'),
('salesorders', 'locations', 'salesorders.fromstkloc=locations.loccode'),
('locations', 'salesorders', 'locations.loccode=salesorders.fromstkloc'),
('securitygroups', 'securityroles', 'securitygroups.secroleid=securityroles.secroleid'),
('securityroles', 'securitygroups', 'securityroles.secroleid=securitygroups.secroleid'),
('securitygroups', 'securitytokens', 'securitygroups.tokenid=securitytokens.tokenid'),
('securitytokens', 'securitygroups', 'securitytokens.tokenid=securitygroups.tokenid'),
('shipmentcharges', 'shipments', 'shipmentcharges.shiptref=shipments.shiptref'),
('shipments', 'shipmentcharges', 'shipments.shiptref=shipmentcharges.shiptref'),
('shipmentcharges', 'systypes', 'shipmentcharges.transtype=systypes.typeid'),
('systypes', 'shipmentcharges', 'systypes.typeid=shipmentcharges.transtype'),
('shipments', 'suppliers', 'shipments.supplierid=suppliers.supplierid'),
('suppliers', 'shipments', 'suppliers.supplierid=shipments.supplierid'),
('stockcheckfreeze', 'stockmaster', 'stockcheckfreeze.stockid=stockmaster.stockid'),
('stockmaster', 'stockcheckfreeze', 'stockmaster.stockid=stockcheckfreeze.stockid'),
('stockcheckfreeze', 'locations', 'stockcheckfreeze.loccode=locations.loccode'),
('locations', 'stockcheckfreeze', 'locations.loccode=stockcheckfreeze.loccode'),
('stockcounts', 'stockmaster', 'stockcounts.stockid=stockmaster.stockid'),
('stockmaster', 'stockcounts', 'stockmaster.stockid=stockcounts.stockid'),
('stockcounts', 'locations', 'stockcounts.loccode=locations.loccode'),
('locations', 'stockcounts', 'locations.loccode=stockcounts.loccode'),
('stockmaster', 'stockcategory', 'stockmaster.categoryid=stockcategory.categoryid'),
('stockcategory', 'stockmaster', 'stockcategory.categoryid=stockmaster.categoryid'),
('stockmaster', 'taxcategories', 'stockmaster.taxcatid=taxcategories.taxcatid'),
('taxcategories', 'stockmaster', 'taxcategories.taxcatid=stockmaster.taxcatid'),
('stockmoves', 'stockmaster', 'stockmoves.stockid=stockmaster.stockid'),
('stockmaster', 'stockmoves', 'stockmaster.stockid=stockmoves.stockid'),
('stockmoves', 'systypes', 'stockmoves.type=systypes.typeid'),
('systypes', 'stockmoves', 'systypes.typeid=stockmoves.type'),
('stockmoves', 'locations', 'stockmoves.loccode=locations.loccode'),
('locations', 'stockmoves', 'locations.loccode=stockmoves.loccode'),
('stockmoves', 'periods', 'stockmoves.prd=periods.periodno'),
('periods', 'stockmoves', 'periods.periodno=stockmoves.prd'),
('stockmovestaxes', 'taxauthorities', 'stockmovestaxes.taxauthid=taxauthorities.taxid'),
('taxauthorities', 'stockmovestaxes', 'taxauthorities.taxid=stockmovestaxes.taxauthid'),
('stockserialitems', 'stockmaster', 'stockserialitems.stockid=stockmaster.stockid'),
('stockmaster', 'stockserialitems', 'stockmaster.stockid=stockserialitems.stockid'),
('stockserialitems', 'locations', 'stockserialitems.loccode=locations.loccode'),
('locations', 'stockserialitems', 'locations.loccode=stockserialitems.loccode'),
('stockserialmoves', 'stockmoves', 'stockserialmoves.stockmoveno=stockmoves.stkmoveno'),
('stockmoves', 'stockserialmoves', 'stockmoves.stkmoveno=stockserialmoves.stockmoveno'),
('stockserialmoves', 'stockserialitems', 'stockserialmoves.stockid=stockserialitems.stockid'),
('stockserialitems', 'stockserialmoves', 'stockserialitems.stockid=stockserialmoves.stockid'),
('suppallocs', 'supptrans', 'suppallocs.transid_allocfrom=supptrans.id'),
('supptrans', 'suppallocs', 'supptrans.id=suppallocs.transid_allocfrom'),
('suppallocs', 'supptrans', 'suppallocs.transid_allocto=supptrans.id'),
('supptrans', 'suppallocs', 'supptrans.id=suppallocs.transid_allocto'),
('suppliercontacts', 'suppliers', 'suppliercontacts.supplierid=suppliers.supplierid'),
('suppliers', 'suppliercontacts', 'suppliers.supplierid=suppliercontacts.supplierid'),
('suppliers', 'currencies', 'suppliers.currcode=currencies.currabrev'),
('currencies', 'suppliers', 'currencies.currabrev=suppliers.currcode'),
('suppliers', 'paymentterms', 'suppliers.paymentterms=paymentterms.termsindicator'),
('paymentterms', 'suppliers', 'paymentterms.termsindicator=suppliers.paymentterms'),
('suppliers', 'taxgroups', 'suppliers.taxgroupid=taxgroups.taxgroupid'),
('taxgroups', 'suppliers', 'taxgroups.taxgroupid=suppliers.taxgroupid'),
('supptrans', 'systypes', 'supptrans.type=systypes.typeid'),
('systypes', 'supptrans', 'systypes.typeid=supptrans.type'),
('supptrans', 'suppliers', 'supptrans.supplierno=suppliers.supplierid'),
('suppliers', 'supptrans', 'suppliers.supplierid=supptrans.supplierno'),
('supptranstaxes', 'taxauthorities', 'supptranstaxes.taxauthid=taxauthorities.taxid'),
('taxauthorities', 'supptranstaxes', 'taxauthorities.taxid=supptranstaxes.taxauthid'),
('supptranstaxes', 'supptrans', 'supptranstaxes.supptransid=supptrans.id'),
('supptrans', 'supptranstaxes', 'supptrans.id=supptranstaxes.supptransid'),
('taxauthorities', 'chartmaster', 'taxauthorities.taxglcode=chartmaster.accountcode'),
('chartmaster', 'taxauthorities', 'chartmaster.accountcode=taxauthorities.taxglcode'),
('taxauthorities', 'chartmaster', 'taxauthorities.purchtaxglaccount=chartmaster.accountcode'),
('chartmaster', 'taxauthorities', 'chartmaster.accountcode=taxauthorities.purchtaxglaccount'),
('taxauthrates', 'taxauthorities', 'taxauthrates.taxauthority=taxauthorities.taxid'),
('taxauthorities', 'taxauthrates', 'taxauthorities.taxid=taxauthrates.taxauthority'),
('taxauthrates', 'taxcategories', 'taxauthrates.taxcatid=taxcategories.taxcatid'),
('taxcategories', 'taxauthrates', 'taxcategories.taxcatid=taxauthrates.taxcatid'),
('taxauthrates', 'taxprovinces', 'taxauthrates.dispatchtaxprovince=taxprovinces.taxprovinceid'),
('taxprovinces', 'taxauthrates', 'taxprovinces.taxprovinceid=taxauthrates.dispatchtaxprovince'),
('taxgrouptaxes', 'taxgroups', 'taxgrouptaxes.taxgroupid=taxgroups.taxgroupid'),
('taxgroups', 'taxgrouptaxes', 'taxgroups.taxgroupid=taxgrouptaxes.taxgroupid'),
('taxgrouptaxes', 'taxauthorities', 'taxgrouptaxes.taxauthid=taxauthorities.taxid'),
('taxauthorities', 'taxgrouptaxes', 'taxauthorities.taxid=taxgrouptaxes.taxauthid'),
('workcentres', 'locations', 'workcentres.location=locations.loccode'),
('locations', 'workcentres', 'locations.loccode=workcentres.location'),
('worksorders', 'locations', 'worksorders.loccode=locations.loccode'),
('locations', 'worksorders', 'locations.loccode=worksorders.loccode'),
('worksorders', 'stockmaster', 'worksorders.stockid=stockmaster.stockid'),
('stockmaster', 'worksorders', 'stockmaster.stockid=worksorders.stockid'),
('www_users', 'locations', 'www_users.defaultlocation=locations.loccode'),
('locations', 'www_users', 'locations.loccode=www_users.defaultlocation'),
('accountgroups', 'accountsection', 'accountgroups.sectioninaccounts=accountsection.sectionid'),
('accountsection', 'accountgroups', 'accountsection.sectionid=accountgroups.sectioninaccounts'),
('bankaccounts', 'chartmaster', 'bankaccounts.accountcode=chartmaster.accountcode'),
('chartmaster', 'bankaccounts', 'chartmaster.accountcode=bankaccounts.accountcode'),
('banktrans', 'systypes', 'banktrans.type=systypes.typeid'),
('systypes', 'banktrans', 'systypes.typeid=banktrans.type'),
('banktrans', 'bankaccounts', 'banktrans.bankact=bankaccounts.accountcode'),
('bankaccounts', 'banktrans', 'bankaccounts.accountcode=banktrans.bankact'),
('bom', 'stockmaster', 'bom.parent=stockmaster.stockid'),
('stockmaster', 'bom', 'stockmaster.stockid=bom.parent'),
('bom', 'stockmaster', 'bom.component=stockmaster.stockid'),
('stockmaster', 'bom', 'stockmaster.stockid=bom.component'),
('bom', 'workcentres', 'bom.workcentreadded=workcentres.code'),
('workcentres', 'bom', 'workcentres.code=bom.workcentreadded'),
('bom', 'locations', 'bom.loccode=locations.loccode'),
('locations', 'bom', 'locations.loccode=bom.loccode'),
('buckets', 'workcentres', 'buckets.workcentre=workcentres.code'),
('workcentres', 'buckets', 'workcentres.code=buckets.workcentre'),
('chartdetails', 'chartmaster', 'chartdetails.accountcode=chartmaster.accountcode'),
('chartmaster', 'chartdetails', 'chartmaster.accountcode=chartdetails.accountcode'),
('chartdetails', 'periods', 'chartdetails.period=periods.periodno'),
('periods', 'chartdetails', 'periods.periodno=chartdetails.period'),
('chartmaster', 'accountgroups', 'chartmaster.group_=accountgroups.groupname'),
('accountgroups', 'chartmaster', 'accountgroups.groupname=chartmaster.group_'),
('contractbom', 'workcentres', 'contractbom.workcentreadded=workcentres.code'),
('workcentres', 'contractbom', 'workcentres.code=contractbom.workcentreadded'),
('contractbom', 'locations', 'contractbom.loccode=locations.loccode'),
('locations', 'contractbom', 'locations.loccode=contractbom.loccode'),
('contractbom', 'stockmaster', 'contractbom.component=stockmaster.stockid'),
('stockmaster', 'contractbom', 'stockmaster.stockid=contractbom.component'),
('contractreqts', 'contracts', 'contractreqts.contract=contracts.contractref'),
('contracts', 'contractreqts', 'contracts.contractref=contractreqts.contract'),
('contracts', 'custbranch', 'contracts.debtorno=custbranch.debtorno'),
('custbranch', 'contracts', 'custbranch.debtorno=contracts.debtorno'),
('contracts', 'stockcategory', 'contracts.branchcode=stockcategory.categoryid'),
('stockcategory', 'contracts', 'stockcategory.categoryid=contracts.branchcode'),
('contracts', 'salestypes', 'contracts.typeabbrev=salestypes.typeabbrev'),
('salestypes', 'contracts', 'salestypes.typeabbrev=contracts.typeabbrev'),
('custallocns', 'debtortrans', 'custallocns.transid_allocfrom=debtortrans.id'),
('debtortrans', 'custallocns', 'debtortrans.id=custallocns.transid_allocfrom'),
('custallocns', 'debtortrans', 'custallocns.transid_allocto=debtortrans.id'),
('debtortrans', 'custallocns', 'debtortrans.id=custallocns.transid_allocto'),
('custbranch', 'debtorsmaster', 'custbranch.debtorno=debtorsmaster.debtorno'),
('debtorsmaster', 'custbranch', 'debtorsmaster.debtorno=custbranch.debtorno'),
('custbranch', 'areas', 'custbranch.area=areas.areacode'),
('areas', 'custbranch', 'areas.areacode=custbranch.area'),
('custbranch', 'salesman', 'custbranch.salesman=salesman.salesmancode'),
('salesman', 'custbranch', 'salesman.salesmancode=custbranch.salesman'),
('custbranch', 'locations', 'custbranch.defaultlocation=locations.loccode'),
('locations', 'custbranch', 'locations.loccode=custbranch.defaultlocation'),
('custbranch', 'shippers', 'custbranch.defaultshipvia=shippers.shipper_id'),
('shippers', 'custbranch', 'shippers.shipper_id=custbranch.defaultshipvia'),
('debtorsmaster', 'holdreasons', 'debtorsmaster.holdreason=holdreasons.reasoncode'),
('holdreasons', 'debtorsmaster', 'holdreasons.reasoncode=debtorsmaster.holdreason'),
('debtorsmaster', 'currencies', 'debtorsmaster.currcode=currencies.currabrev'),
('currencies', 'debtorsmaster', 'currencies.currabrev=debtorsmaster.currcode'),
('debtorsmaster', 'paymentterms', 'debtorsmaster.paymentterms=paymentterms.termsindicator'),
('paymentterms', 'debtorsmaster', 'paymentterms.termsindicator=debtorsmaster.paymentterms'),
('debtorsmaster', 'salestypes', 'debtorsmaster.salestype=salestypes.typeabbrev'),
('salestypes', 'debtorsmaster', 'salestypes.typeabbrev=debtorsmaster.salestype'),
('debtortrans', 'custbranch', 'debtortrans.debtorno=custbranch.debtorno'),
('custbranch', 'debtortrans', 'custbranch.debtorno=debtortrans.debtorno'),
('debtortrans', 'systypes', 'debtortrans.type=systypes.typeid'),
('systypes', 'debtortrans', 'systypes.typeid=debtortrans.type'),
('debtortrans', 'periods', 'debtortrans.prd=periods.periodno'),
('periods', 'debtortrans', 'periods.periodno=debtortrans.prd'),
('debtortranstaxes', 'taxauthorities', 'debtortranstaxes.taxauthid=taxauthorities.taxid'),
('taxauthorities', 'debtortranstaxes', 'taxauthorities.taxid=debtortranstaxes.taxauthid'),
('debtortranstaxes', 'debtortrans', 'debtortranstaxes.debtortransid=debtortrans.id'),
('debtortrans', 'debtortranstaxes', 'debtortrans.id=debtortranstaxes.debtortransid'),
('discountmatrix', 'salestypes', 'discountmatrix.salestype=salestypes.typeabbrev'),
('salestypes', 'discountmatrix', 'salestypes.typeabbrev=discountmatrix.salestype'),
('freightcosts', 'locations', 'freightcosts.locationfrom=locations.loccode'),
('locations', 'freightcosts', 'locations.loccode=freightcosts.locationfrom'),
('freightcosts', 'shippers', 'freightcosts.shipperid=shippers.shipper_id'),
('shippers', 'freightcosts', 'shippers.shipper_id=freightcosts.shipperid'),
('gltrans', 'chartmaster', 'gltrans.account=chartmaster.accountcode'),
('chartmaster', 'gltrans', 'chartmaster.accountcode=gltrans.account'),
('gltrans', 'systypes', 'gltrans.type=systypes.typeid'),
('systypes', 'gltrans', 'systypes.typeid=gltrans.type'),
('gltrans', 'periods', 'gltrans.periodno=periods.periodno'),
('periods', 'gltrans', 'periods.periodno=gltrans.periodno'),
('grns', 'suppliers', 'grns.supplierid=suppliers.supplierid'),
('suppliers', 'grns', 'suppliers.supplierid=grns.supplierid'),
('grns', 'purchorderdetails', 'grns.podetailitem=purchorderdetails.podetailitem'),
('purchorderdetails', 'grns', 'purchorderdetails.podetailitem=grns.podetailitem'),
('locations', 'taxprovinces', 'locations.taxprovinceid=taxprovinces.taxprovinceid'),
('taxprovinces', 'locations', 'taxprovinces.taxprovinceid=locations.taxprovinceid'),
('locstock', 'locations', 'locstock.loccode=locations.loccode'),
('locations', 'locstock', 'locations.loccode=locstock.loccode'),
('locstock', 'stockmaster', 'locstock.stockid=stockmaster.stockid'),
('stockmaster', 'locstock', 'stockmaster.stockid=locstock.stockid'),
('loctransfers', 'locations', 'loctransfers.shiploc=locations.loccode'),
('locations', 'loctransfers', 'locations.loccode=loctransfers.shiploc'),
('loctransfers', 'locations', 'loctransfers.recloc=locations.loccode'),
('locations', 'loctransfers', 'locations.loccode=loctransfers.recloc'),
('loctransfers', 'stockmaster', 'loctransfers.stockid=stockmaster.stockid'),
('stockmaster', 'loctransfers', 'stockmaster.stockid=loctransfers.stockid'),
('orderdeliverydifferencesl', 'stockmaster', 'orderdeliverydifferenceslog.stockid=stockmaster.stockid'),
('stockmaster', 'orderdeliverydifferencesl', 'stockmaster.stockid=orderdeliverydifferenceslog.stockid'),
('orderdeliverydifferencesl', 'custbranch', 'orderdeliverydifferenceslog.debtorno=custbranch.debtorno'),
('custbranch', 'orderdeliverydifferencesl', 'custbranch.debtorno=orderdeliverydifferenceslog.debtorno'),
('orderdeliverydifferencesl', 'salesorders', 'orderdeliverydifferenceslog.branchcode=salesorders.orderno'),
('salesorders', 'orderdeliverydifferencesl', 'salesorders.orderno=orderdeliverydifferenceslog.branchcode'),
('prices', 'stockmaster', 'prices.stockid=stockmaster.stockid'),
('stockmaster', 'prices', 'stockmaster.stockid=prices.stockid'),
('prices', 'currencies', 'prices.currabrev=currencies.currabrev'),
('currencies', 'prices', 'currencies.currabrev=prices.currabrev'),
('prices', 'salestypes', 'prices.typeabbrev=salestypes.typeabbrev'),
('salestypes', 'prices', 'salestypes.typeabbrev=prices.typeabbrev'),
('purchdata', 'stockmaster', 'purchdata.stockid=stockmaster.stockid'),
('stockmaster', 'purchdata', 'stockmaster.stockid=purchdata.stockid'),
('purchdata', 'suppliers', 'purchdata.supplierno=suppliers.supplierid'),
('suppliers', 'purchdata', 'suppliers.supplierid=purchdata.supplierno'),
('purchorderdetails', 'purchorders', 'purchorderdetails.orderno=purchorders.orderno'),
('purchorders', 'purchorderdetails', 'purchorders.orderno=purchorderdetails.orderno'),
('purchorders', 'suppliers', 'purchorders.supplierno=suppliers.supplierid'),
('suppliers', 'purchorders', 'suppliers.supplierid=purchorders.supplierno'),
('purchorders', 'locations', 'purchorders.intostocklocation=locations.loccode'),
('locations', 'purchorders', 'locations.loccode=purchorders.intostocklocation'),
('recurringsalesorders', 'custbranch', 'recurringsalesorders.branchcode=custbranch.branchcode'),
('custbranch', 'recurringsalesorders', 'custbranch.branchcode=recurringsalesorders.branchcode'),
('recurrsalesorderdetails', 'recurringsalesorders', 'recurrsalesorderdetails.recurrorderno=recurringsalesorders.recurrorderno'),
('recurringsalesorders', 'recurrsalesorderdetails', 'recurringsalesorders.recurrorderno=recurrsalesorderdetails.recurrorderno'),
('recurrsalesorderdetails', 'stockmaster', 'recurrsalesorderdetails.stkcode=stockmaster.stockid'),
('stockmaster', 'recurrsalesorderdetails', 'stockmaster.stockid=recurrsalesorderdetails.stkcode'),
('reportcolumns', 'reportheaders', 'reportcolumns.reportid=reportheaders.reportid'),
('reportheaders', 'reportcolumns', 'reportheaders.reportid=reportcolumns.reportid'),
('salesanalysis', 'periods', 'salesanalysis.periodno=periods.periodno'),
('periods', 'salesanalysis', 'periods.periodno=salesanalysis.periodno'),
('salescatprod', 'stockmaster', 'salescatprod.stockid=stockmaster.stockid'),
('stockmaster', 'salescatprod', 'stockmaster.stockid=salescatprod.stockid'),
('salescatprod', 'salescat', 'salescatprod.salescatid=salescat.salescatid'),
('salescat', 'salescatprod', 'salescat.salescatid=salescatprod.salescatid'),
('salesorderdetails', 'salesorders', 'salesorderdetails.orderno=salesorders.orderno'),
('salesorders', 'salesorderdetails', 'salesorders.orderno=salesorderdetails.orderno'),
('salesorderdetails', 'stockmaster', 'salesorderdetails.stkcode=stockmaster.stockid'),
('stockmaster', 'salesorderdetails', 'stockmaster.stockid=salesorderdetails.stkcode'),
('salesorders', 'custbranch', 'salesorders.branchcode=custbranch.branchcode'),
('custbranch', 'salesorders', 'custbranch.branchcode=salesorders.branchcode'),
('salesorders', 'shippers', 'salesorders.debtorno=shippers.shipper_id'),
('shippers', 'salesorders', 'shippers.shipper_id=salesorders.debtorno'),
('salesorders', 'locations', 'salesorders.fromstkloc=locations.loccode'),
('locations', 'salesorders', 'locations.loccode=salesorders.fromstkloc'),
('securitygroups', 'securityroles', 'securitygroups.secroleid=securityroles.secroleid'),
('securityroles', 'securitygroups', 'securityroles.secroleid=securitygroups.secroleid'),
('securitygroups', 'securitytokens', 'securitygroups.tokenid=securitytokens.tokenid'),
('securitytokens', 'securitygroups', 'securitytokens.tokenid=securitygroups.tokenid'),
('shipmentcharges', 'shipments', 'shipmentcharges.shiptref=shipments.shiptref'),
('shipments', 'shipmentcharges', 'shipments.shiptref=shipmentcharges.shiptref'),
('shipmentcharges', 'systypes', 'shipmentcharges.transtype=systypes.typeid'),
('systypes', 'shipmentcharges', 'systypes.typeid=shipmentcharges.transtype'),
('shipments', 'suppliers', 'shipments.supplierid=suppliers.supplierid'),
('suppliers', 'shipments', 'suppliers.supplierid=shipments.supplierid'),
('stockcheckfreeze', 'stockmaster', 'stockcheckfreeze.stockid=stockmaster.stockid'),
('stockmaster', 'stockcheckfreeze', 'stockmaster.stockid=stockcheckfreeze.stockid'),
('stockcheckfreeze', 'locations', 'stockcheckfreeze.loccode=locations.loccode'),
('locations', 'stockcheckfreeze', 'locations.loccode=stockcheckfreeze.loccode'),
('stockcounts', 'stockmaster', 'stockcounts.stockid=stockmaster.stockid'),
('stockmaster', 'stockcounts', 'stockmaster.stockid=stockcounts.stockid'),
('stockcounts', 'locations', 'stockcounts.loccode=locations.loccode'),
('locations', 'stockcounts', 'locations.loccode=stockcounts.loccode'),
('stockmaster', 'stockcategory', 'stockmaster.categoryid=stockcategory.categoryid'),
('stockcategory', 'stockmaster', 'stockcategory.categoryid=stockmaster.categoryid'),
('stockmaster', 'taxcategories', 'stockmaster.taxcatid=taxcategories.taxcatid'),
('taxcategories', 'stockmaster', 'taxcategories.taxcatid=stockmaster.taxcatid'),
('stockmoves', 'stockmaster', 'stockmoves.stockid=stockmaster.stockid'),
('stockmaster', 'stockmoves', 'stockmaster.stockid=stockmoves.stockid'),
('stockmoves', 'systypes', 'stockmoves.type=systypes.typeid'),
('systypes', 'stockmoves', 'systypes.typeid=stockmoves.type'),
('stockmoves', 'locations', 'stockmoves.loccode=locations.loccode'),
('locations', 'stockmoves', 'locations.loccode=stockmoves.loccode'),
('stockmoves', 'periods', 'stockmoves.prd=periods.periodno'),
('periods', 'stockmoves', 'periods.periodno=stockmoves.prd'),
('stockmovestaxes', 'taxauthorities', 'stockmovestaxes.taxauthid=taxauthorities.taxid'),
('taxauthorities', 'stockmovestaxes', 'taxauthorities.taxid=stockmovestaxes.taxauthid'),
('stockserialitems', 'stockmaster', 'stockserialitems.stockid=stockmaster.stockid'),
('stockmaster', 'stockserialitems', 'stockmaster.stockid=stockserialitems.stockid'),
('stockserialitems', 'locations', 'stockserialitems.loccode=locations.loccode'),
('locations', 'stockserialitems', 'locations.loccode=stockserialitems.loccode'),
('stockserialmoves', 'stockmoves', 'stockserialmoves.stockmoveno=stockmoves.stkmoveno'),
('stockmoves', 'stockserialmoves', 'stockmoves.stkmoveno=stockserialmoves.stockmoveno'),
('stockserialmoves', 'stockserialitems', 'stockserialmoves.stockid=stockserialitems.stockid'),
('stockserialitems', 'stockserialmoves', 'stockserialitems.stockid=stockserialmoves.stockid'),
('suppallocs', 'supptrans', 'suppallocs.transid_allocfrom=supptrans.id'),
('supptrans', 'suppallocs', 'supptrans.id=suppallocs.transid_allocfrom'),
('suppallocs', 'supptrans', 'suppallocs.transid_allocto=supptrans.id'),
('supptrans', 'suppallocs', 'supptrans.id=suppallocs.transid_allocto'),
('suppliercontacts', 'suppliers', 'suppliercontacts.supplierid=suppliers.supplierid'),
('suppliers', 'suppliercontacts', 'suppliers.supplierid=suppliercontacts.supplierid'),
('suppliers', 'currencies', 'suppliers.currcode=currencies.currabrev'),
('currencies', 'suppliers', 'currencies.currabrev=suppliers.currcode'),
('suppliers', 'paymentterms', 'suppliers.paymentterms=paymentterms.termsindicator'),
('paymentterms', 'suppliers', 'paymentterms.termsindicator=suppliers.paymentterms'),
('suppliers', 'taxgroups', 'suppliers.taxgroupid=taxgroups.taxgroupid'),
('taxgroups', 'suppliers', 'taxgroups.taxgroupid=suppliers.taxgroupid'),
('supptrans', 'systypes', 'supptrans.type=systypes.typeid'),
('systypes', 'supptrans', 'systypes.typeid=supptrans.type'),
('supptrans', 'suppliers', 'supptrans.supplierno=suppliers.supplierid'),
('suppliers', 'supptrans', 'suppliers.supplierid=supptrans.supplierno'),
('supptranstaxes', 'taxauthorities', 'supptranstaxes.taxauthid=taxauthorities.taxid'),
('taxauthorities', 'supptranstaxes', 'taxauthorities.taxid=supptranstaxes.taxauthid'),
('supptranstaxes', 'supptrans', 'supptranstaxes.supptransid=supptrans.id'),
('supptrans', 'supptranstaxes', 'supptrans.id=supptranstaxes.supptransid'),
('taxauthorities', 'chartmaster', 'taxauthorities.taxglcode=chartmaster.accountcode'),
('chartmaster', 'taxauthorities', 'chartmaster.accountcode=taxauthorities.taxglcode'),
('taxauthorities', 'chartmaster', 'taxauthorities.purchtaxglaccount=chartmaster.accountcode'),
('chartmaster', 'taxauthorities', 'chartmaster.accountcode=taxauthorities.purchtaxglaccount'),
('taxauthrates', 'taxauthorities', 'taxauthrates.taxauthority=taxauthorities.taxid'),
('taxauthorities', 'taxauthrates', 'taxauthorities.taxid=taxauthrates.taxauthority'),
('taxauthrates', 'taxcategories', 'taxauthrates.taxcatid=taxcategories.taxcatid'),
('taxcategories', 'taxauthrates', 'taxcategories.taxcatid=taxauthrates.taxcatid'),
('taxauthrates', 'taxprovinces', 'taxauthrates.dispatchtaxprovince=taxprovinces.taxprovinceid'),
('taxprovinces', 'taxauthrates', 'taxprovinces.taxprovinceid=taxauthrates.dispatchtaxprovince'),
('taxgrouptaxes', 'taxgroups', 'taxgrouptaxes.taxgroupid=taxgroups.taxgroupid'),
('taxgroups', 'taxgrouptaxes', 'taxgroups.taxgroupid=taxgrouptaxes.taxgroupid'),
('taxgrouptaxes', 'taxauthorities', 'taxgrouptaxes.taxauthid=taxauthorities.taxid'),
('taxauthorities', 'taxgrouptaxes', 'taxauthorities.taxid=taxgrouptaxes.taxauthid'),
('workcentres', 'locations', 'workcentres.location=locations.loccode'),
('locations', 'workcentres', 'locations.loccode=workcentres.location'),
('worksorders', 'locations', 'worksorders.loccode=locations.loccode'),
('locations', 'worksorders', 'locations.loccode=worksorders.loccode'),
('worksorders', 'stockmaster', 'worksorders.stockid=stockmaster.stockid'),
('stockmaster', 'worksorders', 'stockmaster.stockid=worksorders.stockid'),
('www_users', 'locations', 'www_users.defaultlocation=locations.loccode'),
('locations', 'www_users', 'locations.loccode=www_users.defaultlocation'),
('accountgroups', 'accountsection', 'accountgroups.sectioninaccounts=accountsection.sectionid'),
('accountsection', 'accountgroups', 'accountsection.sectionid=accountgroups.sectioninaccounts'),
('bankaccounts', 'chartmaster', 'bankaccounts.accountcode=chartmaster.accountcode'),
('chartmaster', 'bankaccounts', 'chartmaster.accountcode=bankaccounts.accountcode'),
('banktrans', 'systypes', 'banktrans.type=systypes.typeid'),
('systypes', 'banktrans', 'systypes.typeid=banktrans.type'),
('banktrans', 'bankaccounts', 'banktrans.bankact=bankaccounts.accountcode'),
('bankaccounts', 'banktrans', 'bankaccounts.accountcode=banktrans.bankact'),
('bom', 'stockmaster', 'bom.parent=stockmaster.stockid'),
('stockmaster', 'bom', 'stockmaster.stockid=bom.parent'),
('bom', 'stockmaster', 'bom.component=stockmaster.stockid'),
('stockmaster', 'bom', 'stockmaster.stockid=bom.component'),
('bom', 'workcentres', 'bom.workcentreadded=workcentres.code'),
('workcentres', 'bom', 'workcentres.code=bom.workcentreadded'),
('bom', 'locations', 'bom.loccode=locations.loccode'),
('locations', 'bom', 'locations.loccode=bom.loccode'),
('buckets', 'workcentres', 'buckets.workcentre=workcentres.code'),
('workcentres', 'buckets', 'workcentres.code=buckets.workcentre'),
('chartdetails', 'chartmaster', 'chartdetails.accountcode=chartmaster.accountcode'),
('chartmaster', 'chartdetails', 'chartmaster.accountcode=chartdetails.accountcode'),
('chartdetails', 'periods', 'chartdetails.period=periods.periodno'),
('periods', 'chartdetails', 'periods.periodno=chartdetails.period'),
('chartmaster', 'accountgroups', 'chartmaster.group_=accountgroups.groupname'),
('accountgroups', 'chartmaster', 'accountgroups.groupname=chartmaster.group_'),
('contractbom', 'workcentres', 'contractbom.workcentreadded=workcentres.code'),
('workcentres', 'contractbom', 'workcentres.code=contractbom.workcentreadded'),
('contractbom', 'locations', 'contractbom.loccode=locations.loccode'),
('locations', 'contractbom', 'locations.loccode=contractbom.loccode'),
('contractbom', 'stockmaster', 'contractbom.component=stockmaster.stockid'),
('stockmaster', 'contractbom', 'stockmaster.stockid=contractbom.component'),
('contractreqts', 'contracts', 'contractreqts.contract=contracts.contractref'),
('contracts', 'contractreqts', 'contracts.contractref=contractreqts.contract'),
('contracts', 'custbranch', 'contracts.debtorno=custbranch.debtorno'),
('custbranch', 'contracts', 'custbranch.debtorno=contracts.debtorno'),
('contracts', 'stockcategory', 'contracts.branchcode=stockcategory.categoryid'),
('stockcategory', 'contracts', 'stockcategory.categoryid=contracts.branchcode'),
('contracts', 'salestypes', 'contracts.typeabbrev=salestypes.typeabbrev'),
('salestypes', 'contracts', 'salestypes.typeabbrev=contracts.typeabbrev'),
('custallocns', 'debtortrans', 'custallocns.transid_allocfrom=debtortrans.id'),
('debtortrans', 'custallocns', 'debtortrans.id=custallocns.transid_allocfrom'),
('custallocns', 'debtortrans', 'custallocns.transid_allocto=debtortrans.id'),
('debtortrans', 'custallocns', 'debtortrans.id=custallocns.transid_allocto'),
('custbranch', 'debtorsmaster', 'custbranch.debtorno=debtorsmaster.debtorno'),
('debtorsmaster', 'custbranch', 'debtorsmaster.debtorno=custbranch.debtorno'),
('custbranch', 'areas', 'custbranch.area=areas.areacode'),
('areas', 'custbranch', 'areas.areacode=custbranch.area'),
('custbranch', 'salesman', 'custbranch.salesman=salesman.salesmancode'),
('salesman', 'custbranch', 'salesman.salesmancode=custbranch.salesman'),
('custbranch', 'locations', 'custbranch.defaultlocation=locations.loccode'),
('locations', 'custbranch', 'locations.loccode=custbranch.defaultlocation'),
('custbranch', 'shippers', 'custbranch.defaultshipvia=shippers.shipper_id'),
('shippers', 'custbranch', 'shippers.shipper_id=custbranch.defaultshipvia'),
('debtorsmaster', 'holdreasons', 'debtorsmaster.holdreason=holdreasons.reasoncode'),
('holdreasons', 'debtorsmaster', 'holdreasons.reasoncode=debtorsmaster.holdreason'),
('debtorsmaster', 'currencies', 'debtorsmaster.currcode=currencies.currabrev'),
('currencies', 'debtorsmaster', 'currencies.currabrev=debtorsmaster.currcode'),
('debtorsmaster', 'paymentterms', 'debtorsmaster.paymentterms=paymentterms.termsindicator'),
('paymentterms', 'debtorsmaster', 'paymentterms.termsindicator=debtorsmaster.paymentterms'),
('debtorsmaster', 'salestypes', 'debtorsmaster.salestype=salestypes.typeabbrev'),
('salestypes', 'debtorsmaster', 'salestypes.typeabbrev=debtorsmaster.salestype'),
('debtortrans', 'custbranch', 'debtortrans.debtorno=custbranch.debtorno'),
('custbranch', 'debtortrans', 'custbranch.debtorno=debtortrans.debtorno'),
('debtortrans', 'systypes', 'debtortrans.type=systypes.typeid'),
('systypes', 'debtortrans', 'systypes.typeid=debtortrans.type'),
('debtortrans', 'periods', 'debtortrans.prd=periods.periodno'),
('periods', 'debtortrans', 'periods.periodno=debtortrans.prd'),
('debtortranstaxes', 'taxauthorities', 'debtortranstaxes.taxauthid=taxauthorities.taxid'),
('taxauthorities', 'debtortranstaxes', 'taxauthorities.taxid=debtortranstaxes.taxauthid'),
('debtortranstaxes', 'debtortrans', 'debtortranstaxes.debtortransid=debtortrans.id'),
('debtortrans', 'debtortranstaxes', 'debtortrans.id=debtortranstaxes.debtortransid'),
('discountmatrix', 'salestypes', 'discountmatrix.salestype=salestypes.typeabbrev'),
('salestypes', 'discountmatrix', 'salestypes.typeabbrev=discountmatrix.salestype'),
('freightcosts', 'locations', 'freightcosts.locationfrom=locations.loccode'),
('locations', 'freightcosts', 'locations.loccode=freightcosts.locationfrom'),
('freightcosts', 'shippers', 'freightcosts.shipperid=shippers.shipper_id'),
('shippers', 'freightcosts', 'shippers.shipper_id=freightcosts.shipperid'),
('gltrans', 'chartmaster', 'gltrans.account=chartmaster.accountcode'),
('chartmaster', 'gltrans', 'chartmaster.accountcode=gltrans.account'),
('gltrans', 'systypes', 'gltrans.type=systypes.typeid'),
('systypes', 'gltrans', 'systypes.typeid=gltrans.type'),
('gltrans', 'periods', 'gltrans.periodno=periods.periodno'),
('periods', 'gltrans', 'periods.periodno=gltrans.periodno'),
('grns', 'suppliers', 'grns.supplierid=suppliers.supplierid'),
('suppliers', 'grns', 'suppliers.supplierid=grns.supplierid'),
('grns', 'purchorderdetails', 'grns.podetailitem=purchorderdetails.podetailitem'),
('purchorderdetails', 'grns', 'purchorderdetails.podetailitem=grns.podetailitem'),
('locations', 'taxprovinces', 'locations.taxprovinceid=taxprovinces.taxprovinceid'),
('taxprovinces', 'locations', 'taxprovinces.taxprovinceid=locations.taxprovinceid'),
('locstock', 'locations', 'locstock.loccode=locations.loccode'),
('locations', 'locstock', 'locations.loccode=locstock.loccode'),
('locstock', 'stockmaster', 'locstock.stockid=stockmaster.stockid'),
('stockmaster', 'locstock', 'stockmaster.stockid=locstock.stockid'),
('loctransfers', 'locations', 'loctransfers.shiploc=locations.loccode'),
('locations', 'loctransfers', 'locations.loccode=loctransfers.shiploc'),
('loctransfers', 'locations', 'loctransfers.recloc=locations.loccode'),
('locations', 'loctransfers', 'locations.loccode=loctransfers.recloc'),
('loctransfers', 'stockmaster', 'loctransfers.stockid=stockmaster.stockid'),
('stockmaster', 'loctransfers', 'stockmaster.stockid=loctransfers.stockid'),
('orderdeliverydifferencesl', 'stockmaster', 'orderdeliverydifferenceslog.stockid=stockmaster.stockid'),
('stockmaster', 'orderdeliverydifferencesl', 'stockmaster.stockid=orderdeliverydifferenceslog.stockid'),
('orderdeliverydifferencesl', 'custbranch', 'orderdeliverydifferenceslog.debtorno=custbranch.debtorno'),
('custbranch', 'orderdeliverydifferencesl', 'custbranch.debtorno=orderdeliverydifferenceslog.debtorno'),
('orderdeliverydifferencesl', 'salesorders', 'orderdeliverydifferenceslog.branchcode=salesorders.orderno'),
('salesorders', 'orderdeliverydifferencesl', 'salesorders.orderno=orderdeliverydifferenceslog.branchcode'),
('prices', 'stockmaster', 'prices.stockid=stockmaster.stockid'),
('stockmaster', 'prices', 'stockmaster.stockid=prices.stockid'),
('prices', 'currencies', 'prices.currabrev=currencies.currabrev'),
('currencies', 'prices', 'currencies.currabrev=prices.currabrev'),
('prices', 'salestypes', 'prices.typeabbrev=salestypes.typeabbrev'),
('salestypes', 'prices', 'salestypes.typeabbrev=prices.typeabbrev'),
('purchdata', 'stockmaster', 'purchdata.stockid=stockmaster.stockid'),
('stockmaster', 'purchdata', 'stockmaster.stockid=purchdata.stockid'),
('purchdata', 'suppliers', 'purchdata.supplierno=suppliers.supplierid'),
('suppliers', 'purchdata', 'suppliers.supplierid=purchdata.supplierno'),
('purchorderdetails', 'purchorders', 'purchorderdetails.orderno=purchorders.orderno'),
('purchorders', 'purchorderdetails', 'purchorders.orderno=purchorderdetails.orderno'),
('purchorders', 'suppliers', 'purchorders.supplierno=suppliers.supplierid'),
('suppliers', 'purchorders', 'suppliers.supplierid=purchorders.supplierno'),
('purchorders', 'locations', 'purchorders.intostocklocation=locations.loccode'),
('locations', 'purchorders', 'locations.loccode=purchorders.intostocklocation'),
('recurringsalesorders', 'custbranch', 'recurringsalesorders.branchcode=custbranch.branchcode'),
('custbranch', 'recurringsalesorders', 'custbranch.branchcode=recurringsalesorders.branchcode'),
('recurrsalesorderdetails', 'recurringsalesorders', 'recurrsalesorderdetails.recurrorderno=recurringsalesorders.recurrorderno'),
('recurringsalesorders', 'recurrsalesorderdetails', 'recurringsalesorders.recurrorderno=recurrsalesorderdetails.recurrorderno'),
('recurrsalesorderdetails', 'stockmaster', 'recurrsalesorderdetails.stkcode=stockmaster.stockid'),
('stockmaster', 'recurrsalesorderdetails', 'stockmaster.stockid=recurrsalesorderdetails.stkcode'),
('reportcolumns', 'reportheaders', 'reportcolumns.reportid=reportheaders.reportid'),
('reportheaders', 'reportcolumns', 'reportheaders.reportid=reportcolumns.reportid'),
('salesanalysis', 'periods', 'salesanalysis.periodno=periods.periodno'),
('periods', 'salesanalysis', 'periods.periodno=salesanalysis.periodno'),
('salescatprod', 'stockmaster', 'salescatprod.stockid=stockmaster.stockid'),
('stockmaster', 'salescatprod', 'stockmaster.stockid=salescatprod.stockid'),
('salescatprod', 'salescat', 'salescatprod.salescatid=salescat.salescatid'),
('salescat', 'salescatprod', 'salescat.salescatid=salescatprod.salescatid'),
('salesorderdetails', 'salesorders', 'salesorderdetails.orderno=salesorders.orderno'),
('salesorders', 'salesorderdetails', 'salesorders.orderno=salesorderdetails.orderno'),
('salesorderdetails', 'stockmaster', 'salesorderdetails.stkcode=stockmaster.stockid'),
('stockmaster', 'salesorderdetails', 'stockmaster.stockid=salesorderdetails.stkcode'),
('salesorders', 'custbranch', 'salesorders.branchcode=custbranch.branchcode'),
('custbranch', 'salesorders', 'custbranch.branchcode=salesorders.branchcode'),
('salesorders', 'shippers', 'salesorders.debtorno=shippers.shipper_id'),
('shippers', 'salesorders', 'shippers.shipper_id=salesorders.debtorno'),
('salesorders', 'locations', 'salesorders.fromstkloc=locations.loccode'),
('locations', 'salesorders', 'locations.loccode=salesorders.fromstkloc'),
('securitygroups', 'securityroles', 'securitygroups.secroleid=securityroles.secroleid'),
('securityroles', 'securitygroups', 'securityroles.secroleid=securitygroups.secroleid'),
('securitygroups', 'securitytokens', 'securitygroups.tokenid=securitytokens.tokenid'),
('securitytokens', 'securitygroups', 'securitytokens.tokenid=securitygroups.tokenid'),
('shipmentcharges', 'shipments', 'shipmentcharges.shiptref=shipments.shiptref'),
('shipments', 'shipmentcharges', 'shipments.shiptref=shipmentcharges.shiptref'),
('shipmentcharges', 'systypes', 'shipmentcharges.transtype=systypes.typeid'),
('systypes', 'shipmentcharges', 'systypes.typeid=shipmentcharges.transtype'),
('shipments', 'suppliers', 'shipments.supplierid=suppliers.supplierid'),
('suppliers', 'shipments', 'suppliers.supplierid=shipments.supplierid'),
('stockcheckfreeze', 'stockmaster', 'stockcheckfreeze.stockid=stockmaster.stockid'),
('stockmaster', 'stockcheckfreeze', 'stockmaster.stockid=stockcheckfreeze.stockid'),
('stockcheckfreeze', 'locations', 'stockcheckfreeze.loccode=locations.loccode'),
('locations', 'stockcheckfreeze', 'locations.loccode=stockcheckfreeze.loccode'),
('stockcounts', 'stockmaster', 'stockcounts.stockid=stockmaster.stockid'),
('stockmaster', 'stockcounts', 'stockmaster.stockid=stockcounts.stockid'),
('stockcounts', 'locations', 'stockcounts.loccode=locations.loccode'),
('locations', 'stockcounts', 'locations.loccode=stockcounts.loccode'),
('stockmaster', 'stockcategory', 'stockmaster.categoryid=stockcategory.categoryid'),
('stockcategory', 'stockmaster', 'stockcategory.categoryid=stockmaster.categoryid'),
('stockmaster', 'taxcategories', 'stockmaster.taxcatid=taxcategories.taxcatid'),
('taxcategories', 'stockmaster', 'taxcategories.taxcatid=stockmaster.taxcatid'),
('stockmoves', 'stockmaster', 'stockmoves.stockid=stockmaster.stockid'),
('stockmaster', 'stockmoves', 'stockmaster.stockid=stockmoves.stockid'),
('stockmoves', 'systypes', 'stockmoves.type=systypes.typeid'),
('systypes', 'stockmoves', 'systypes.typeid=stockmoves.type'),
('stockmoves', 'locations', 'stockmoves.loccode=locations.loccode'),
('locations', 'stockmoves', 'locations.loccode=stockmoves.loccode'),
('stockmoves', 'periods', 'stockmoves.prd=periods.periodno'),
('periods', 'stockmoves', 'periods.periodno=stockmoves.prd'),
('stockmovestaxes', 'taxauthorities', 'stockmovestaxes.taxauthid=taxauthorities.taxid'),
('taxauthorities', 'stockmovestaxes', 'taxauthorities.taxid=stockmovestaxes.taxauthid'),
('stockserialitems', 'stockmaster', 'stockserialitems.stockid=stockmaster.stockid'),
('stockmaster', 'stockserialitems', 'stockmaster.stockid=stockserialitems.stockid');
INSERT INTO `reportlinks` (`table1`, `table2`, `equation`) VALUES
('stockserialitems', 'locations', 'stockserialitems.loccode=locations.loccode'),
('locations', 'stockserialitems', 'locations.loccode=stockserialitems.loccode'),
('stockserialmoves', 'stockmoves', 'stockserialmoves.stockmoveno=stockmoves.stkmoveno'),
('stockmoves', 'stockserialmoves', 'stockmoves.stkmoveno=stockserialmoves.stockmoveno'),
('stockserialmoves', 'stockserialitems', 'stockserialmoves.stockid=stockserialitems.stockid'),
('stockserialitems', 'stockserialmoves', 'stockserialitems.stockid=stockserialmoves.stockid'),
('suppallocs', 'supptrans', 'suppallocs.transid_allocfrom=supptrans.id'),
('supptrans', 'suppallocs', 'supptrans.id=suppallocs.transid_allocfrom'),
('suppallocs', 'supptrans', 'suppallocs.transid_allocto=supptrans.id'),
('supptrans', 'suppallocs', 'supptrans.id=suppallocs.transid_allocto'),
('suppliercontacts', 'suppliers', 'suppliercontacts.supplierid=suppliers.supplierid'),
('suppliers', 'suppliercontacts', 'suppliers.supplierid=suppliercontacts.supplierid'),
('suppliers', 'currencies', 'suppliers.currcode=currencies.currabrev'),
('currencies', 'suppliers', 'currencies.currabrev=suppliers.currcode'),
('suppliers', 'paymentterms', 'suppliers.paymentterms=paymentterms.termsindicator'),
('paymentterms', 'suppliers', 'paymentterms.termsindicator=suppliers.paymentterms'),
('suppliers', 'taxgroups', 'suppliers.taxgroupid=taxgroups.taxgroupid'),
('taxgroups', 'suppliers', 'taxgroups.taxgroupid=suppliers.taxgroupid'),
('supptrans', 'systypes', 'supptrans.type=systypes.typeid'),
('systypes', 'supptrans', 'systypes.typeid=supptrans.type'),
('supptrans', 'suppliers', 'supptrans.supplierno=suppliers.supplierid'),
('suppliers', 'supptrans', 'suppliers.supplierid=supptrans.supplierno'),
('supptranstaxes', 'taxauthorities', 'supptranstaxes.taxauthid=taxauthorities.taxid'),
('taxauthorities', 'supptranstaxes', 'taxauthorities.taxid=supptranstaxes.taxauthid'),
('supptranstaxes', 'supptrans', 'supptranstaxes.supptransid=supptrans.id'),
('supptrans', 'supptranstaxes', 'supptrans.id=supptranstaxes.supptransid'),
('taxauthorities', 'chartmaster', 'taxauthorities.taxglcode=chartmaster.accountcode'),
('chartmaster', 'taxauthorities', 'chartmaster.accountcode=taxauthorities.taxglcode'),
('taxauthorities', 'chartmaster', 'taxauthorities.purchtaxglaccount=chartmaster.accountcode'),
('chartmaster', 'taxauthorities', 'chartmaster.accountcode=taxauthorities.purchtaxglaccount'),
('taxauthrates', 'taxauthorities', 'taxauthrates.taxauthority=taxauthorities.taxid'),
('taxauthorities', 'taxauthrates', 'taxauthorities.taxid=taxauthrates.taxauthority'),
('taxauthrates', 'taxcategories', 'taxauthrates.taxcatid=taxcategories.taxcatid'),
('taxcategories', 'taxauthrates', 'taxcategories.taxcatid=taxauthrates.taxcatid'),
('taxauthrates', 'taxprovinces', 'taxauthrates.dispatchtaxprovince=taxprovinces.taxprovinceid'),
('taxprovinces', 'taxauthrates', 'taxprovinces.taxprovinceid=taxauthrates.dispatchtaxprovince'),
('taxgrouptaxes', 'taxgroups', 'taxgrouptaxes.taxgroupid=taxgroups.taxgroupid'),
('taxgroups', 'taxgrouptaxes', 'taxgroups.taxgroupid=taxgrouptaxes.taxgroupid'),
('taxgrouptaxes', 'taxauthorities', 'taxgrouptaxes.taxauthid=taxauthorities.taxid'),
('taxauthorities', 'taxgrouptaxes', 'taxauthorities.taxid=taxgrouptaxes.taxauthid'),
('workcentres', 'locations', 'workcentres.location=locations.loccode'),
('locations', 'workcentres', 'locations.loccode=workcentres.location'),
('worksorders', 'locations', 'worksorders.loccode=locations.loccode'),
('locations', 'worksorders', 'locations.loccode=worksorders.loccode'),
('worksorders', 'stockmaster', 'worksorders.stockid=stockmaster.stockid'),
('stockmaster', 'worksorders', 'stockmaster.stockid=worksorders.stockid'),
('www_users', 'locations', 'www_users.defaultlocation=locations.loccode'),
('locations', 'www_users', 'locations.loccode=www_users.defaultlocation'),
('accountgroups', 'accountsection', 'accountgroups.sectioninaccounts=accountsection.sectionid'),
('accountsection', 'accountgroups', 'accountsection.sectionid=accountgroups.sectioninaccounts'),
('bankaccounts', 'chartmaster', 'bankaccounts.accountcode=chartmaster.accountcode'),
('chartmaster', 'bankaccounts', 'chartmaster.accountcode=bankaccounts.accountcode'),
('banktrans', 'systypes', 'banktrans.type=systypes.typeid'),
('systypes', 'banktrans', 'systypes.typeid=banktrans.type'),
('banktrans', 'bankaccounts', 'banktrans.bankact=bankaccounts.accountcode'),
('bankaccounts', 'banktrans', 'bankaccounts.accountcode=banktrans.bankact'),
('bom', 'stockmaster', 'bom.parent=stockmaster.stockid'),
('stockmaster', 'bom', 'stockmaster.stockid=bom.parent'),
('bom', 'stockmaster', 'bom.component=stockmaster.stockid'),
('stockmaster', 'bom', 'stockmaster.stockid=bom.component'),
('bom', 'workcentres', 'bom.workcentreadded=workcentres.code'),
('workcentres', 'bom', 'workcentres.code=bom.workcentreadded'),
('bom', 'locations', 'bom.loccode=locations.loccode'),
('locations', 'bom', 'locations.loccode=bom.loccode'),
('buckets', 'workcentres', 'buckets.workcentre=workcentres.code'),
('workcentres', 'buckets', 'workcentres.code=buckets.workcentre'),
('chartdetails', 'chartmaster', 'chartdetails.accountcode=chartmaster.accountcode'),
('chartmaster', 'chartdetails', 'chartmaster.accountcode=chartdetails.accountcode'),
('chartdetails', 'periods', 'chartdetails.period=periods.periodno'),
('periods', 'chartdetails', 'periods.periodno=chartdetails.period'),
('chartmaster', 'accountgroups', 'chartmaster.group_=accountgroups.groupname'),
('accountgroups', 'chartmaster', 'accountgroups.groupname=chartmaster.group_'),
('contractbom', 'workcentres', 'contractbom.workcentreadded=workcentres.code'),
('workcentres', 'contractbom', 'workcentres.code=contractbom.workcentreadded'),
('contractbom', 'locations', 'contractbom.loccode=locations.loccode'),
('locations', 'contractbom', 'locations.loccode=contractbom.loccode'),
('contractbom', 'stockmaster', 'contractbom.component=stockmaster.stockid'),
('stockmaster', 'contractbom', 'stockmaster.stockid=contractbom.component'),
('contractreqts', 'contracts', 'contractreqts.contract=contracts.contractref'),
('contracts', 'contractreqts', 'contracts.contractref=contractreqts.contract'),
('contracts', 'custbranch', 'contracts.debtorno=custbranch.debtorno'),
('custbranch', 'contracts', 'custbranch.debtorno=contracts.debtorno'),
('contracts', 'stockcategory', 'contracts.branchcode=stockcategory.categoryid'),
('stockcategory', 'contracts', 'stockcategory.categoryid=contracts.branchcode'),
('contracts', 'salestypes', 'contracts.typeabbrev=salestypes.typeabbrev'),
('salestypes', 'contracts', 'salestypes.typeabbrev=contracts.typeabbrev'),
('custallocns', 'debtortrans', 'custallocns.transid_allocfrom=debtortrans.id'),
('debtortrans', 'custallocns', 'debtortrans.id=custallocns.transid_allocfrom'),
('custallocns', 'debtortrans', 'custallocns.transid_allocto=debtortrans.id'),
('debtortrans', 'custallocns', 'debtortrans.id=custallocns.transid_allocto'),
('custbranch', 'debtorsmaster', 'custbranch.debtorno=debtorsmaster.debtorno'),
('debtorsmaster', 'custbranch', 'debtorsmaster.debtorno=custbranch.debtorno'),
('custbranch', 'areas', 'custbranch.area=areas.areacode'),
('areas', 'custbranch', 'areas.areacode=custbranch.area'),
('custbranch', 'salesman', 'custbranch.salesman=salesman.salesmancode'),
('salesman', 'custbranch', 'salesman.salesmancode=custbranch.salesman'),
('custbranch', 'locations', 'custbranch.defaultlocation=locations.loccode'),
('locations', 'custbranch', 'locations.loccode=custbranch.defaultlocation'),
('custbranch', 'shippers', 'custbranch.defaultshipvia=shippers.shipper_id'),
('shippers', 'custbranch', 'shippers.shipper_id=custbranch.defaultshipvia'),
('debtorsmaster', 'holdreasons', 'debtorsmaster.holdreason=holdreasons.reasoncode'),
('holdreasons', 'debtorsmaster', 'holdreasons.reasoncode=debtorsmaster.holdreason'),
('debtorsmaster', 'currencies', 'debtorsmaster.currcode=currencies.currabrev'),
('currencies', 'debtorsmaster', 'currencies.currabrev=debtorsmaster.currcode'),
('debtorsmaster', 'paymentterms', 'debtorsmaster.paymentterms=paymentterms.termsindicator'),
('paymentterms', 'debtorsmaster', 'paymentterms.termsindicator=debtorsmaster.paymentterms'),
('debtorsmaster', 'salestypes', 'debtorsmaster.salestype=salestypes.typeabbrev'),
('salestypes', 'debtorsmaster', 'salestypes.typeabbrev=debtorsmaster.salestype'),
('debtortrans', 'custbranch', 'debtortrans.debtorno=custbranch.debtorno'),
('custbranch', 'debtortrans', 'custbranch.debtorno=debtortrans.debtorno'),
('debtortrans', 'systypes', 'debtortrans.type=systypes.typeid'),
('systypes', 'debtortrans', 'systypes.typeid=debtortrans.type'),
('debtortrans', 'periods', 'debtortrans.prd=periods.periodno'),
('periods', 'debtortrans', 'periods.periodno=debtortrans.prd'),
('debtortranstaxes', 'taxauthorities', 'debtortranstaxes.taxauthid=taxauthorities.taxid'),
('taxauthorities', 'debtortranstaxes', 'taxauthorities.taxid=debtortranstaxes.taxauthid'),
('debtortranstaxes', 'debtortrans', 'debtortranstaxes.debtortransid=debtortrans.id'),
('debtortrans', 'debtortranstaxes', 'debtortrans.id=debtortranstaxes.debtortransid'),
('discountmatrix', 'salestypes', 'discountmatrix.salestype=salestypes.typeabbrev'),
('salestypes', 'discountmatrix', 'salestypes.typeabbrev=discountmatrix.salestype'),
('freightcosts', 'locations', 'freightcosts.locationfrom=locations.loccode'),
('locations', 'freightcosts', 'locations.loccode=freightcosts.locationfrom'),
('freightcosts', 'shippers', 'freightcosts.shipperid=shippers.shipper_id'),
('shippers', 'freightcosts', 'shippers.shipper_id=freightcosts.shipperid'),
('gltrans', 'chartmaster', 'gltrans.account=chartmaster.accountcode'),
('chartmaster', 'gltrans', 'chartmaster.accountcode=gltrans.account'),
('gltrans', 'systypes', 'gltrans.type=systypes.typeid'),
('systypes', 'gltrans', 'systypes.typeid=gltrans.type'),
('gltrans', 'periods', 'gltrans.periodno=periods.periodno'),
('periods', 'gltrans', 'periods.periodno=gltrans.periodno'),
('grns', 'suppliers', 'grns.supplierid=suppliers.supplierid'),
('suppliers', 'grns', 'suppliers.supplierid=grns.supplierid'),
('grns', 'purchorderdetails', 'grns.podetailitem=purchorderdetails.podetailitem'),
('purchorderdetails', 'grns', 'purchorderdetails.podetailitem=grns.podetailitem'),
('locations', 'taxprovinces', 'locations.taxprovinceid=taxprovinces.taxprovinceid'),
('taxprovinces', 'locations', 'taxprovinces.taxprovinceid=locations.taxprovinceid'),
('locstock', 'locations', 'locstock.loccode=locations.loccode'),
('locations', 'locstock', 'locations.loccode=locstock.loccode'),
('locstock', 'stockmaster', 'locstock.stockid=stockmaster.stockid'),
('stockmaster', 'locstock', 'stockmaster.stockid=locstock.stockid'),
('loctransfers', 'locations', 'loctransfers.shiploc=locations.loccode'),
('locations', 'loctransfers', 'locations.loccode=loctransfers.shiploc'),
('loctransfers', 'locations', 'loctransfers.recloc=locations.loccode'),
('locations', 'loctransfers', 'locations.loccode=loctransfers.recloc'),
('loctransfers', 'stockmaster', 'loctransfers.stockid=stockmaster.stockid'),
('stockmaster', 'loctransfers', 'stockmaster.stockid=loctransfers.stockid'),
('orderdeliverydifferencesl', 'stockmaster', 'orderdeliverydifferenceslog.stockid=stockmaster.stockid'),
('stockmaster', 'orderdeliverydifferencesl', 'stockmaster.stockid=orderdeliverydifferenceslog.stockid'),
('orderdeliverydifferencesl', 'custbranch', 'orderdeliverydifferenceslog.debtorno=custbranch.debtorno'),
('custbranch', 'orderdeliverydifferencesl', 'custbranch.debtorno=orderdeliverydifferenceslog.debtorno'),
('orderdeliverydifferencesl', 'salesorders', 'orderdeliverydifferenceslog.branchcode=salesorders.orderno'),
('salesorders', 'orderdeliverydifferencesl', 'salesorders.orderno=orderdeliverydifferenceslog.branchcode'),
('prices', 'stockmaster', 'prices.stockid=stockmaster.stockid'),
('stockmaster', 'prices', 'stockmaster.stockid=prices.stockid'),
('prices', 'currencies', 'prices.currabrev=currencies.currabrev'),
('currencies', 'prices', 'currencies.currabrev=prices.currabrev'),
('prices', 'salestypes', 'prices.typeabbrev=salestypes.typeabbrev'),
('salestypes', 'prices', 'salestypes.typeabbrev=prices.typeabbrev'),
('purchdata', 'stockmaster', 'purchdata.stockid=stockmaster.stockid'),
('stockmaster', 'purchdata', 'stockmaster.stockid=purchdata.stockid'),
('purchdata', 'suppliers', 'purchdata.supplierno=suppliers.supplierid'),
('suppliers', 'purchdata', 'suppliers.supplierid=purchdata.supplierno'),
('purchorderdetails', 'purchorders', 'purchorderdetails.orderno=purchorders.orderno'),
('purchorders', 'purchorderdetails', 'purchorders.orderno=purchorderdetails.orderno'),
('purchorders', 'suppliers', 'purchorders.supplierno=suppliers.supplierid'),
('suppliers', 'purchorders', 'suppliers.supplierid=purchorders.supplierno'),
('purchorders', 'locations', 'purchorders.intostocklocation=locations.loccode'),
('locations', 'purchorders', 'locations.loccode=purchorders.intostocklocation'),
('recurringsalesorders', 'custbranch', 'recurringsalesorders.branchcode=custbranch.branchcode'),
('custbranch', 'recurringsalesorders', 'custbranch.branchcode=recurringsalesorders.branchcode'),
('recurrsalesorderdetails', 'recurringsalesorders', 'recurrsalesorderdetails.recurrorderno=recurringsalesorders.recurrorderno'),
('recurringsalesorders', 'recurrsalesorderdetails', 'recurringsalesorders.recurrorderno=recurrsalesorderdetails.recurrorderno'),
('recurrsalesorderdetails', 'stockmaster', 'recurrsalesorderdetails.stkcode=stockmaster.stockid'),
('stockmaster', 'recurrsalesorderdetails', 'stockmaster.stockid=recurrsalesorderdetails.stkcode'),
('reportcolumns', 'reportheaders', 'reportcolumns.reportid=reportheaders.reportid'),
('reportheaders', 'reportcolumns', 'reportheaders.reportid=reportcolumns.reportid'),
('salesanalysis', 'periods', 'salesanalysis.periodno=periods.periodno'),
('periods', 'salesanalysis', 'periods.periodno=salesanalysis.periodno'),
('salescatprod', 'stockmaster', 'salescatprod.stockid=stockmaster.stockid'),
('stockmaster', 'salescatprod', 'stockmaster.stockid=salescatprod.stockid'),
('salescatprod', 'salescat', 'salescatprod.salescatid=salescat.salescatid'),
('salescat', 'salescatprod', 'salescat.salescatid=salescatprod.salescatid'),
('salesorderdetails', 'salesorders', 'salesorderdetails.orderno=salesorders.orderno'),
('salesorders', 'salesorderdetails', 'salesorders.orderno=salesorderdetails.orderno'),
('salesorderdetails', 'stockmaster', 'salesorderdetails.stkcode=stockmaster.stockid'),
('stockmaster', 'salesorderdetails', 'stockmaster.stockid=salesorderdetails.stkcode'),
('salesorders', 'custbranch', 'salesorders.branchcode=custbranch.branchcode'),
('custbranch', 'salesorders', 'custbranch.branchcode=salesorders.branchcode'),
('salesorders', 'shippers', 'salesorders.debtorno=shippers.shipper_id'),
('shippers', 'salesorders', 'shippers.shipper_id=salesorders.debtorno'),
('salesorders', 'locations', 'salesorders.fromstkloc=locations.loccode'),
('locations', 'salesorders', 'locations.loccode=salesorders.fromstkloc'),
('securitygroups', 'securityroles', 'securitygroups.secroleid=securityroles.secroleid'),
('securityroles', 'securitygroups', 'securityroles.secroleid=securitygroups.secroleid'),
('securitygroups', 'securitytokens', 'securitygroups.tokenid=securitytokens.tokenid'),
('securitytokens', 'securitygroups', 'securitytokens.tokenid=securitygroups.tokenid'),
('shipmentcharges', 'shipments', 'shipmentcharges.shiptref=shipments.shiptref'),
('shipments', 'shipmentcharges', 'shipments.shiptref=shipmentcharges.shiptref'),
('shipmentcharges', 'systypes', 'shipmentcharges.transtype=systypes.typeid'),
('systypes', 'shipmentcharges', 'systypes.typeid=shipmentcharges.transtype'),
('shipments', 'suppliers', 'shipments.supplierid=suppliers.supplierid'),
('suppliers', 'shipments', 'suppliers.supplierid=shipments.supplierid'),
('stockcheckfreeze', 'stockmaster', 'stockcheckfreeze.stockid=stockmaster.stockid'),
('stockmaster', 'stockcheckfreeze', 'stockmaster.stockid=stockcheckfreeze.stockid'),
('stockcheckfreeze', 'locations', 'stockcheckfreeze.loccode=locations.loccode'),
('locations', 'stockcheckfreeze', 'locations.loccode=stockcheckfreeze.loccode'),
('stockcounts', 'stockmaster', 'stockcounts.stockid=stockmaster.stockid'),
('stockmaster', 'stockcounts', 'stockmaster.stockid=stockcounts.stockid'),
('stockcounts', 'locations', 'stockcounts.loccode=locations.loccode'),
('locations', 'stockcounts', 'locations.loccode=stockcounts.loccode'),
('stockmaster', 'stockcategory', 'stockmaster.categoryid=stockcategory.categoryid'),
('stockcategory', 'stockmaster', 'stockcategory.categoryid=stockmaster.categoryid'),
('stockmaster', 'taxcategories', 'stockmaster.taxcatid=taxcategories.taxcatid'),
('taxcategories', 'stockmaster', 'taxcategories.taxcatid=stockmaster.taxcatid'),
('stockmoves', 'stockmaster', 'stockmoves.stockid=stockmaster.stockid'),
('stockmaster', 'stockmoves', 'stockmaster.stockid=stockmoves.stockid'),
('stockmoves', 'systypes', 'stockmoves.type=systypes.typeid'),
('systypes', 'stockmoves', 'systypes.typeid=stockmoves.type'),
('stockmoves', 'locations', 'stockmoves.loccode=locations.loccode'),
('locations', 'stockmoves', 'locations.loccode=stockmoves.loccode'),
('stockmoves', 'periods', 'stockmoves.prd=periods.periodno'),
('periods', 'stockmoves', 'periods.periodno=stockmoves.prd'),
('stockmovestaxes', 'taxauthorities', 'stockmovestaxes.taxauthid=taxauthorities.taxid'),
('taxauthorities', 'stockmovestaxes', 'taxauthorities.taxid=stockmovestaxes.taxauthid'),
('stockserialitems', 'stockmaster', 'stockserialitems.stockid=stockmaster.stockid'),
('stockmaster', 'stockserialitems', 'stockmaster.stockid=stockserialitems.stockid'),
('stockserialitems', 'locations', 'stockserialitems.loccode=locations.loccode'),
('locations', 'stockserialitems', 'locations.loccode=stockserialitems.loccode'),
('stockserialmoves', 'stockmoves', 'stockserialmoves.stockmoveno=stockmoves.stkmoveno'),
('stockmoves', 'stockserialmoves', 'stockmoves.stkmoveno=stockserialmoves.stockmoveno'),
('stockserialmoves', 'stockserialitems', 'stockserialmoves.stockid=stockserialitems.stockid'),
('stockserialitems', 'stockserialmoves', 'stockserialitems.stockid=stockserialmoves.stockid'),
('suppallocs', 'supptrans', 'suppallocs.transid_allocfrom=supptrans.id'),
('supptrans', 'suppallocs', 'supptrans.id=suppallocs.transid_allocfrom'),
('suppallocs', 'supptrans', 'suppallocs.transid_allocto=supptrans.id'),
('supptrans', 'suppallocs', 'supptrans.id=suppallocs.transid_allocto'),
('suppliercontacts', 'suppliers', 'suppliercontacts.supplierid=suppliers.supplierid'),
('suppliers', 'suppliercontacts', 'suppliers.supplierid=suppliercontacts.supplierid'),
('suppliers', 'currencies', 'suppliers.currcode=currencies.currabrev'),
('currencies', 'suppliers', 'currencies.currabrev=suppliers.currcode'),
('suppliers', 'paymentterms', 'suppliers.paymentterms=paymentterms.termsindicator'),
('paymentterms', 'suppliers', 'paymentterms.termsindicator=suppliers.paymentterms'),
('suppliers', 'taxgroups', 'suppliers.taxgroupid=taxgroups.taxgroupid'),
('taxgroups', 'suppliers', 'taxgroups.taxgroupid=suppliers.taxgroupid'),
('supptrans', 'systypes', 'supptrans.type=systypes.typeid'),
('systypes', 'supptrans', 'systypes.typeid=supptrans.type'),
('supptrans', 'suppliers', 'supptrans.supplierno=suppliers.supplierid'),
('suppliers', 'supptrans', 'suppliers.supplierid=supptrans.supplierno'),
('supptranstaxes', 'taxauthorities', 'supptranstaxes.taxauthid=taxauthorities.taxid'),
('taxauthorities', 'supptranstaxes', 'taxauthorities.taxid=supptranstaxes.taxauthid'),
('supptranstaxes', 'supptrans', 'supptranstaxes.supptransid=supptrans.id'),
('supptrans', 'supptranstaxes', 'supptrans.id=supptranstaxes.supptransid'),
('taxauthorities', 'chartmaster', 'taxauthorities.taxglcode=chartmaster.accountcode'),
('chartmaster', 'taxauthorities', 'chartmaster.accountcode=taxauthorities.taxglcode'),
('taxauthorities', 'chartmaster', 'taxauthorities.purchtaxglaccount=chartmaster.accountcode'),
('chartmaster', 'taxauthorities', 'chartmaster.accountcode=taxauthorities.purchtaxglaccount'),
('taxauthrates', 'taxauthorities', 'taxauthrates.taxauthority=taxauthorities.taxid'),
('taxauthorities', 'taxauthrates', 'taxauthorities.taxid=taxauthrates.taxauthority'),
('taxauthrates', 'taxcategories', 'taxauthrates.taxcatid=taxcategories.taxcatid'),
('taxcategories', 'taxauthrates', 'taxcategories.taxcatid=taxauthrates.taxcatid'),
('taxauthrates', 'taxprovinces', 'taxauthrates.dispatchtaxprovince=taxprovinces.taxprovinceid'),
('taxprovinces', 'taxauthrates', 'taxprovinces.taxprovinceid=taxauthrates.dispatchtaxprovince'),
('taxgrouptaxes', 'taxgroups', 'taxgrouptaxes.taxgroupid=taxgroups.taxgroupid'),
('taxgroups', 'taxgrouptaxes', 'taxgroups.taxgroupid=taxgrouptaxes.taxgroupid'),
('taxgrouptaxes', 'taxauthorities', 'taxgrouptaxes.taxauthid=taxauthorities.taxid'),
('taxauthorities', 'taxgrouptaxes', 'taxauthorities.taxid=taxgrouptaxes.taxauthid'),
('workcentres', 'locations', 'workcentres.location=locations.loccode'),
('locations', 'workcentres', 'locations.loccode=workcentres.location'),
('worksorders', 'locations', 'worksorders.loccode=locations.loccode'),
('locations', 'worksorders', 'locations.loccode=worksorders.loccode'),
('worksorders', 'stockmaster', 'worksorders.stockid=stockmaster.stockid'),
('stockmaster', 'worksorders', 'stockmaster.stockid=worksorders.stockid'),
('www_users', 'locations', 'www_users.defaultlocation=locations.loccode'),
('locations', 'www_users', 'locations.loccode=www_users.defaultlocation'),
('accountgroups', 'accountsection', 'accountgroups.sectioninaccounts=accountsection.sectionid'),
('accountsection', 'accountgroups', 'accountsection.sectionid=accountgroups.sectioninaccounts'),
('bankaccounts', 'chartmaster', 'bankaccounts.accountcode=chartmaster.accountcode'),
('chartmaster', 'bankaccounts', 'chartmaster.accountcode=bankaccounts.accountcode'),
('banktrans', 'systypes', 'banktrans.type=systypes.typeid'),
('systypes', 'banktrans', 'systypes.typeid=banktrans.type'),
('banktrans', 'bankaccounts', 'banktrans.bankact=bankaccounts.accountcode'),
('bankaccounts', 'banktrans', 'bankaccounts.accountcode=banktrans.bankact'),
('bom', 'stockmaster', 'bom.parent=stockmaster.stockid'),
('stockmaster', 'bom', 'stockmaster.stockid=bom.parent'),
('bom', 'stockmaster', 'bom.component=stockmaster.stockid'),
('stockmaster', 'bom', 'stockmaster.stockid=bom.component'),
('bom', 'workcentres', 'bom.workcentreadded=workcentres.code'),
('workcentres', 'bom', 'workcentres.code=bom.workcentreadded'),
('bom', 'locations', 'bom.loccode=locations.loccode'),
('locations', 'bom', 'locations.loccode=bom.loccode'),
('buckets', 'workcentres', 'buckets.workcentre=workcentres.code'),
('workcentres', 'buckets', 'workcentres.code=buckets.workcentre'),
('chartdetails', 'chartmaster', 'chartdetails.accountcode=chartmaster.accountcode'),
('chartmaster', 'chartdetails', 'chartmaster.accountcode=chartdetails.accountcode'),
('chartdetails', 'periods', 'chartdetails.period=periods.periodno'),
('periods', 'chartdetails', 'periods.periodno=chartdetails.period'),
('chartmaster', 'accountgroups', 'chartmaster.group_=accountgroups.groupname'),
('accountgroups', 'chartmaster', 'accountgroups.groupname=chartmaster.group_'),
('contractbom', 'workcentres', 'contractbom.workcentreadded=workcentres.code'),
('workcentres', 'contractbom', 'workcentres.code=contractbom.workcentreadded'),
('contractbom', 'locations', 'contractbom.loccode=locations.loccode'),
('locations', 'contractbom', 'locations.loccode=contractbom.loccode'),
('contractbom', 'stockmaster', 'contractbom.component=stockmaster.stockid'),
('stockmaster', 'contractbom', 'stockmaster.stockid=contractbom.component'),
('contractreqts', 'contracts', 'contractreqts.contract=contracts.contractref'),
('contracts', 'contractreqts', 'contracts.contractref=contractreqts.contract'),
('contracts', 'custbranch', 'contracts.debtorno=custbranch.debtorno'),
('custbranch', 'contracts', 'custbranch.debtorno=contracts.debtorno'),
('contracts', 'stockcategory', 'contracts.branchcode=stockcategory.categoryid'),
('stockcategory', 'contracts', 'stockcategory.categoryid=contracts.branchcode'),
('contracts', 'salestypes', 'contracts.typeabbrev=salestypes.typeabbrev'),
('salestypes', 'contracts', 'salestypes.typeabbrev=contracts.typeabbrev'),
('custallocns', 'debtortrans', 'custallocns.transid_allocfrom=debtortrans.id'),
('debtortrans', 'custallocns', 'debtortrans.id=custallocns.transid_allocfrom'),
('custallocns', 'debtortrans', 'custallocns.transid_allocto=debtortrans.id'),
('debtortrans', 'custallocns', 'debtortrans.id=custallocns.transid_allocto'),
('custbranch', 'debtorsmaster', 'custbranch.debtorno=debtorsmaster.debtorno'),
('debtorsmaster', 'custbranch', 'debtorsmaster.debtorno=custbranch.debtorno'),
('custbranch', 'areas', 'custbranch.area=areas.areacode'),
('areas', 'custbranch', 'areas.areacode=custbranch.area'),
('custbranch', 'salesman', 'custbranch.salesman=salesman.salesmancode'),
('salesman', 'custbranch', 'salesman.salesmancode=custbranch.salesman'),
('custbranch', 'locations', 'custbranch.defaultlocation=locations.loccode'),
('locations', 'custbranch', 'locations.loccode=custbranch.defaultlocation'),
('custbranch', 'shippers', 'custbranch.defaultshipvia=shippers.shipper_id'),
('shippers', 'custbranch', 'shippers.shipper_id=custbranch.defaultshipvia'),
('debtorsmaster', 'holdreasons', 'debtorsmaster.holdreason=holdreasons.reasoncode'),
('holdreasons', 'debtorsmaster', 'holdreasons.reasoncode=debtorsmaster.holdreason'),
('debtorsmaster', 'currencies', 'debtorsmaster.currcode=currencies.currabrev'),
('currencies', 'debtorsmaster', 'currencies.currabrev=debtorsmaster.currcode'),
('debtorsmaster', 'paymentterms', 'debtorsmaster.paymentterms=paymentterms.termsindicator'),
('paymentterms', 'debtorsmaster', 'paymentterms.termsindicator=debtorsmaster.paymentterms'),
('debtorsmaster', 'salestypes', 'debtorsmaster.salestype=salestypes.typeabbrev'),
('salestypes', 'debtorsmaster', 'salestypes.typeabbrev=debtorsmaster.salestype'),
('debtortrans', 'custbranch', 'debtortrans.debtorno=custbranch.debtorno'),
('custbranch', 'debtortrans', 'custbranch.debtorno=debtortrans.debtorno'),
('debtortrans', 'systypes', 'debtortrans.type=systypes.typeid'),
('systypes', 'debtortrans', 'systypes.typeid=debtortrans.type'),
('debtortrans', 'periods', 'debtortrans.prd=periods.periodno'),
('periods', 'debtortrans', 'periods.periodno=debtortrans.prd'),
('debtortranstaxes', 'taxauthorities', 'debtortranstaxes.taxauthid=taxauthorities.taxid'),
('taxauthorities', 'debtortranstaxes', 'taxauthorities.taxid=debtortranstaxes.taxauthid'),
('debtortranstaxes', 'debtortrans', 'debtortranstaxes.debtortransid=debtortrans.id'),
('debtortrans', 'debtortranstaxes', 'debtortrans.id=debtortranstaxes.debtortransid'),
('discountmatrix', 'salestypes', 'discountmatrix.salestype=salestypes.typeabbrev'),
('salestypes', 'discountmatrix', 'salestypes.typeabbrev=discountmatrix.salestype'),
('freightcosts', 'locations', 'freightcosts.locationfrom=locations.loccode'),
('locations', 'freightcosts', 'locations.loccode=freightcosts.locationfrom'),
('freightcosts', 'shippers', 'freightcosts.shipperid=shippers.shipper_id'),
('shippers', 'freightcosts', 'shippers.shipper_id=freightcosts.shipperid'),
('gltrans', 'chartmaster', 'gltrans.account=chartmaster.accountcode'),
('chartmaster', 'gltrans', 'chartmaster.accountcode=gltrans.account'),
('gltrans', 'systypes', 'gltrans.type=systypes.typeid'),
('systypes', 'gltrans', 'systypes.typeid=gltrans.type'),
('gltrans', 'periods', 'gltrans.periodno=periods.periodno'),
('periods', 'gltrans', 'periods.periodno=gltrans.periodno'),
('grns', 'suppliers', 'grns.supplierid=suppliers.supplierid'),
('suppliers', 'grns', 'suppliers.supplierid=grns.supplierid'),
('grns', 'purchorderdetails', 'grns.podetailitem=purchorderdetails.podetailitem'),
('purchorderdetails', 'grns', 'purchorderdetails.podetailitem=grns.podetailitem'),
('locations', 'taxprovinces', 'locations.taxprovinceid=taxprovinces.taxprovinceid'),
('taxprovinces', 'locations', 'taxprovinces.taxprovinceid=locations.taxprovinceid'),
('locstock', 'locations', 'locstock.loccode=locations.loccode'),
('locations', 'locstock', 'locations.loccode=locstock.loccode'),
('locstock', 'stockmaster', 'locstock.stockid=stockmaster.stockid'),
('stockmaster', 'locstock', 'stockmaster.stockid=locstock.stockid'),
('loctransfers', 'locations', 'loctransfers.shiploc=locations.loccode'),
('locations', 'loctransfers', 'locations.loccode=loctransfers.shiploc'),
('loctransfers', 'locations', 'loctransfers.recloc=locations.loccode'),
('locations', 'loctransfers', 'locations.loccode=loctransfers.recloc'),
('loctransfers', 'stockmaster', 'loctransfers.stockid=stockmaster.stockid'),
('stockmaster', 'loctransfers', 'stockmaster.stockid=loctransfers.stockid'),
('orderdeliverydifferencesl', 'stockmaster', 'orderdeliverydifferenceslog.stockid=stockmaster.stockid'),
('stockmaster', 'orderdeliverydifferencesl', 'stockmaster.stockid=orderdeliverydifferenceslog.stockid'),
('orderdeliverydifferencesl', 'custbranch', 'orderdeliverydifferenceslog.debtorno=custbranch.debtorno'),
('custbranch', 'orderdeliverydifferencesl', 'custbranch.debtorno=orderdeliverydifferenceslog.debtorno'),
('orderdeliverydifferencesl', 'salesorders', 'orderdeliverydifferenceslog.branchcode=salesorders.orderno'),
('salesorders', 'orderdeliverydifferencesl', 'salesorders.orderno=orderdeliverydifferenceslog.branchcode'),
('prices', 'stockmaster', 'prices.stockid=stockmaster.stockid'),
('stockmaster', 'prices', 'stockmaster.stockid=prices.stockid'),
('prices', 'currencies', 'prices.currabrev=currencies.currabrev'),
('currencies', 'prices', 'currencies.currabrev=prices.currabrev'),
('prices', 'salestypes', 'prices.typeabbrev=salestypes.typeabbrev'),
('salestypes', 'prices', 'salestypes.typeabbrev=prices.typeabbrev'),
('purchdata', 'stockmaster', 'purchdata.stockid=stockmaster.stockid'),
('stockmaster', 'purchdata', 'stockmaster.stockid=purchdata.stockid'),
('purchdata', 'suppliers', 'purchdata.supplierno=suppliers.supplierid'),
('suppliers', 'purchdata', 'suppliers.supplierid=purchdata.supplierno'),
('purchorderdetails', 'purchorders', 'purchorderdetails.orderno=purchorders.orderno'),
('purchorders', 'purchorderdetails', 'purchorders.orderno=purchorderdetails.orderno'),
('purchorders', 'suppliers', 'purchorders.supplierno=suppliers.supplierid'),
('suppliers', 'purchorders', 'suppliers.supplierid=purchorders.supplierno'),
('purchorders', 'locations', 'purchorders.intostocklocation=locations.loccode'),
('locations', 'purchorders', 'locations.loccode=purchorders.intostocklocation'),
('recurringsalesorders', 'custbranch', 'recurringsalesorders.branchcode=custbranch.branchcode'),
('custbranch', 'recurringsalesorders', 'custbranch.branchcode=recurringsalesorders.branchcode'),
('recurrsalesorderdetails', 'recurringsalesorders', 'recurrsalesorderdetails.recurrorderno=recurringsalesorders.recurrorderno'),
('recurringsalesorders', 'recurrsalesorderdetails', 'recurringsalesorders.recurrorderno=recurrsalesorderdetails.recurrorderno'),
('recurrsalesorderdetails', 'stockmaster', 'recurrsalesorderdetails.stkcode=stockmaster.stockid'),
('stockmaster', 'recurrsalesorderdetails', 'stockmaster.stockid=recurrsalesorderdetails.stkcode'),
('reportcolumns', 'reportheaders', 'reportcolumns.reportid=reportheaders.reportid'),
('reportheaders', 'reportcolumns', 'reportheaders.reportid=reportcolumns.reportid'),
('salesanalysis', 'periods', 'salesanalysis.periodno=periods.periodno'),
('periods', 'salesanalysis', 'periods.periodno=salesanalysis.periodno'),
('salescatprod', 'stockmaster', 'salescatprod.stockid=stockmaster.stockid'),
('stockmaster', 'salescatprod', 'stockmaster.stockid=salescatprod.stockid'),
('salescatprod', 'salescat', 'salescatprod.salescatid=salescat.salescatid'),
('salescat', 'salescatprod', 'salescat.salescatid=salescatprod.salescatid'),
('salesorderdetails', 'salesorders', 'salesorderdetails.orderno=salesorders.orderno'),
('salesorders', 'salesorderdetails', 'salesorders.orderno=salesorderdetails.orderno'),
('salesorderdetails', 'stockmaster', 'salesorderdetails.stkcode=stockmaster.stockid'),
('stockmaster', 'salesorderdetails', 'stockmaster.stockid=salesorderdetails.stkcode'),
('salesorders', 'custbranch', 'salesorders.branchcode=custbranch.branchcode'),
('custbranch', 'salesorders', 'custbranch.branchcode=salesorders.branchcode'),
('salesorders', 'shippers', 'salesorders.debtorno=shippers.shipper_id'),
('shippers', 'salesorders', 'shippers.shipper_id=salesorders.debtorno'),
('salesorders', 'locations', 'salesorders.fromstkloc=locations.loccode'),
('locations', 'salesorders', 'locations.loccode=salesorders.fromstkloc'),
('securitygroups', 'securityroles', 'securitygroups.secroleid=securityroles.secroleid'),
('securityroles', 'securitygroups', 'securityroles.secroleid=securitygroups.secroleid'),
('securitygroups', 'securitytokens', 'securitygroups.tokenid=securitytokens.tokenid'),
('securitytokens', 'securitygroups', 'securitytokens.tokenid=securitygroups.tokenid'),
('shipmentcharges', 'shipments', 'shipmentcharges.shiptref=shipments.shiptref'),
('shipments', 'shipmentcharges', 'shipments.shiptref=shipmentcharges.shiptref'),
('shipmentcharges', 'systypes', 'shipmentcharges.transtype=systypes.typeid'),
('systypes', 'shipmentcharges', 'systypes.typeid=shipmentcharges.transtype'),
('shipments', 'suppliers', 'shipments.supplierid=suppliers.supplierid'),
('suppliers', 'shipments', 'suppliers.supplierid=shipments.supplierid'),
('stockcheckfreeze', 'stockmaster', 'stockcheckfreeze.stockid=stockmaster.stockid'),
('stockmaster', 'stockcheckfreeze', 'stockmaster.stockid=stockcheckfreeze.stockid'),
('stockcheckfreeze', 'locations', 'stockcheckfreeze.loccode=locations.loccode'),
('locations', 'stockcheckfreeze', 'locations.loccode=stockcheckfreeze.loccode'),
('stockcounts', 'stockmaster', 'stockcounts.stockid=stockmaster.stockid'),
('stockmaster', 'stockcounts', 'stockmaster.stockid=stockcounts.stockid'),
('stockcounts', 'locations', 'stockcounts.loccode=locations.loccode'),
('locations', 'stockcounts', 'locations.loccode=stockcounts.loccode'),
('stockmaster', 'stockcategory', 'stockmaster.categoryid=stockcategory.categoryid'),
('stockcategory', 'stockmaster', 'stockcategory.categoryid=stockmaster.categoryid'),
('stockmaster', 'taxcategories', 'stockmaster.taxcatid=taxcategories.taxcatid'),
('taxcategories', 'stockmaster', 'taxcategories.taxcatid=stockmaster.taxcatid'),
('stockmoves', 'stockmaster', 'stockmoves.stockid=stockmaster.stockid'),
('stockmaster', 'stockmoves', 'stockmaster.stockid=stockmoves.stockid'),
('stockmoves', 'systypes', 'stockmoves.type=systypes.typeid'),
('systypes', 'stockmoves', 'systypes.typeid=stockmoves.type'),
('stockmoves', 'locations', 'stockmoves.loccode=locations.loccode'),
('locations', 'stockmoves', 'locations.loccode=stockmoves.loccode'),
('stockmoves', 'periods', 'stockmoves.prd=periods.periodno'),
('periods', 'stockmoves', 'periods.periodno=stockmoves.prd'),
('stockmovestaxes', 'taxauthorities', 'stockmovestaxes.taxauthid=taxauthorities.taxid'),
('taxauthorities', 'stockmovestaxes', 'taxauthorities.taxid=stockmovestaxes.taxauthid'),
('stockserialitems', 'stockmaster', 'stockserialitems.stockid=stockmaster.stockid'),
('stockmaster', 'stockserialitems', 'stockmaster.stockid=stockserialitems.stockid'),
('stockserialitems', 'locations', 'stockserialitems.loccode=locations.loccode'),
('locations', 'stockserialitems', 'locations.loccode=stockserialitems.loccode'),
('stockserialmoves', 'stockmoves', 'stockserialmoves.stockmoveno=stockmoves.stkmoveno'),
('stockmoves', 'stockserialmoves', 'stockmoves.stkmoveno=stockserialmoves.stockmoveno'),
('stockserialmoves', 'stockserialitems', 'stockserialmoves.stockid=stockserialitems.stockid'),
('stockserialitems', 'stockserialmoves', 'stockserialitems.stockid=stockserialmoves.stockid'),
('suppallocs', 'supptrans', 'suppallocs.transid_allocfrom=supptrans.id'),
('supptrans', 'suppallocs', 'supptrans.id=suppallocs.transid_allocfrom'),
('suppallocs', 'supptrans', 'suppallocs.transid_allocto=supptrans.id'),
('supptrans', 'suppallocs', 'supptrans.id=suppallocs.transid_allocto'),
('suppliercontacts', 'suppliers', 'suppliercontacts.supplierid=suppliers.supplierid'),
('suppliers', 'suppliercontacts', 'suppliers.supplierid=suppliercontacts.supplierid'),
('suppliers', 'currencies', 'suppliers.currcode=currencies.currabrev'),
('currencies', 'suppliers', 'currencies.currabrev=suppliers.currcode'),
('suppliers', 'paymentterms', 'suppliers.paymentterms=paymentterms.termsindicator'),
('paymentterms', 'suppliers', 'paymentterms.termsindicator=suppliers.paymentterms'),
('suppliers', 'taxgroups', 'suppliers.taxgroupid=taxgroups.taxgroupid'),
('taxgroups', 'suppliers', 'taxgroups.taxgroupid=suppliers.taxgroupid'),
('supptrans', 'systypes', 'supptrans.type=systypes.typeid'),
('systypes', 'supptrans', 'systypes.typeid=supptrans.type'),
('supptrans', 'suppliers', 'supptrans.supplierno=suppliers.supplierid'),
('suppliers', 'supptrans', 'suppliers.supplierid=supptrans.supplierno'),
('supptranstaxes', 'taxauthorities', 'supptranstaxes.taxauthid=taxauthorities.taxid'),
('taxauthorities', 'supptranstaxes', 'taxauthorities.taxid=supptranstaxes.taxauthid'),
('supptranstaxes', 'supptrans', 'supptranstaxes.supptransid=supptrans.id'),
('supptrans', 'supptranstaxes', 'supptrans.id=supptranstaxes.supptransid'),
('taxauthorities', 'chartmaster', 'taxauthorities.taxglcode=chartmaster.accountcode'),
('chartmaster', 'taxauthorities', 'chartmaster.accountcode=taxauthorities.taxglcode'),
('taxauthorities', 'chartmaster', 'taxauthorities.purchtaxglaccount=chartmaster.accountcode'),
('chartmaster', 'taxauthorities', 'chartmaster.accountcode=taxauthorities.purchtaxglaccount'),
('taxauthrates', 'taxauthorities', 'taxauthrates.taxauthority=taxauthorities.taxid'),
('taxauthorities', 'taxauthrates', 'taxauthorities.taxid=taxauthrates.taxauthority'),
('taxauthrates', 'taxcategories', 'taxauthrates.taxcatid=taxcategories.taxcatid'),
('taxcategories', 'taxauthrates', 'taxcategories.taxcatid=taxauthrates.taxcatid'),
('taxauthrates', 'taxprovinces', 'taxauthrates.dispatchtaxprovince=taxprovinces.taxprovinceid'),
('taxprovinces', 'taxauthrates', 'taxprovinces.taxprovinceid=taxauthrates.dispatchtaxprovince'),
('taxgrouptaxes', 'taxgroups', 'taxgrouptaxes.taxgroupid=taxgroups.taxgroupid'),
('taxgroups', 'taxgrouptaxes', 'taxgroups.taxgroupid=taxgrouptaxes.taxgroupid'),
('taxgrouptaxes', 'taxauthorities', 'taxgrouptaxes.taxauthid=taxauthorities.taxid'),
('taxauthorities', 'taxgrouptaxes', 'taxauthorities.taxid=taxgrouptaxes.taxauthid'),
('workcentres', 'locations', 'workcentres.location=locations.loccode'),
('locations', 'workcentres', 'locations.loccode=workcentres.location'),
('worksorders', 'locations', 'worksorders.loccode=locations.loccode'),
('locations', 'worksorders', 'locations.loccode=worksorders.loccode'),
('worksorders', 'stockmaster', 'worksorders.stockid=stockmaster.stockid'),
('stockmaster', 'worksorders', 'stockmaster.stockid=worksorders.stockid'),
('www_users', 'locations', 'www_users.defaultlocation=locations.loccode'),
('locations', 'www_users', 'locations.loccode=www_users.defaultlocation'),
('accountgroups', 'accountsection', 'accountgroups.sectioninaccounts=accountsection.sectionid'),
('accountsection', 'accountgroups', 'accountsection.sectionid=accountgroups.sectioninaccounts'),
('bankaccounts', 'chartmaster', 'bankaccounts.accountcode=chartmaster.accountcode'),
('chartmaster', 'bankaccounts', 'chartmaster.accountcode=bankaccounts.accountcode'),
('banktrans', 'systypes', 'banktrans.type=systypes.typeid'),
('systypes', 'banktrans', 'systypes.typeid=banktrans.type'),
('banktrans', 'bankaccounts', 'banktrans.bankact=bankaccounts.accountcode'),
('bankaccounts', 'banktrans', 'bankaccounts.accountcode=banktrans.bankact'),
('bom', 'stockmaster', 'bom.parent=stockmaster.stockid'),
('stockmaster', 'bom', 'stockmaster.stockid=bom.parent'),
('bom', 'stockmaster', 'bom.component=stockmaster.stockid'),
('stockmaster', 'bom', 'stockmaster.stockid=bom.component'),
('bom', 'workcentres', 'bom.workcentreadded=workcentres.code'),
('workcentres', 'bom', 'workcentres.code=bom.workcentreadded'),
('bom', 'locations', 'bom.loccode=locations.loccode'),
('locations', 'bom', 'locations.loccode=bom.loccode'),
('buckets', 'workcentres', 'buckets.workcentre=workcentres.code'),
('workcentres', 'buckets', 'workcentres.code=buckets.workcentre'),
('chartdetails', 'chartmaster', 'chartdetails.accountcode=chartmaster.accountcode'),
('chartmaster', 'chartdetails', 'chartmaster.accountcode=chartdetails.accountcode'),
('chartdetails', 'periods', 'chartdetails.period=periods.periodno'),
('periods', 'chartdetails', 'periods.periodno=chartdetails.period'),
('chartmaster', 'accountgroups', 'chartmaster.group_=accountgroups.groupname'),
('accountgroups', 'chartmaster', 'accountgroups.groupname=chartmaster.group_'),
('contractbom', 'workcentres', 'contractbom.workcentreadded=workcentres.code'),
('workcentres', 'contractbom', 'workcentres.code=contractbom.workcentreadded'),
('contractbom', 'locations', 'contractbom.loccode=locations.loccode'),
('locations', 'contractbom', 'locations.loccode=contractbom.loccode'),
('contractbom', 'stockmaster', 'contractbom.component=stockmaster.stockid'),
('stockmaster', 'contractbom', 'stockmaster.stockid=contractbom.component'),
('contractreqts', 'contracts', 'contractreqts.contract=contracts.contractref'),
('contracts', 'contractreqts', 'contracts.contractref=contractreqts.contract'),
('contracts', 'custbranch', 'contracts.debtorno=custbranch.debtorno'),
('custbranch', 'contracts', 'custbranch.debtorno=contracts.debtorno'),
('contracts', 'stockcategory', 'contracts.branchcode=stockcategory.categoryid'),
('stockcategory', 'contracts', 'stockcategory.categoryid=contracts.branchcode'),
('contracts', 'salestypes', 'contracts.typeabbrev=salestypes.typeabbrev'),
('salestypes', 'contracts', 'salestypes.typeabbrev=contracts.typeabbrev'),
('custallocns', 'debtortrans', 'custallocns.transid_allocfrom=debtortrans.id'),
('debtortrans', 'custallocns', 'debtortrans.id=custallocns.transid_allocfrom'),
('custallocns', 'debtortrans', 'custallocns.transid_allocto=debtortrans.id'),
('debtortrans', 'custallocns', 'debtortrans.id=custallocns.transid_allocto'),
('custbranch', 'debtorsmaster', 'custbranch.debtorno=debtorsmaster.debtorno'),
('debtorsmaster', 'custbranch', 'debtorsmaster.debtorno=custbranch.debtorno'),
('custbranch', 'areas', 'custbranch.area=areas.areacode'),
('areas', 'custbranch', 'areas.areacode=custbranch.area'),
('custbranch', 'salesman', 'custbranch.salesman=salesman.salesmancode'),
('salesman', 'custbranch', 'salesman.salesmancode=custbranch.salesman'),
('custbranch', 'locations', 'custbranch.defaultlocation=locations.loccode'),
('locations', 'custbranch', 'locations.loccode=custbranch.defaultlocation'),
('custbranch', 'shippers', 'custbranch.defaultshipvia=shippers.shipper_id'),
('shippers', 'custbranch', 'shippers.shipper_id=custbranch.defaultshipvia'),
('debtorsmaster', 'holdreasons', 'debtorsmaster.holdreason=holdreasons.reasoncode'),
('holdreasons', 'debtorsmaster', 'holdreasons.reasoncode=debtorsmaster.holdreason'),
('debtorsmaster', 'currencies', 'debtorsmaster.currcode=currencies.currabrev'),
('currencies', 'debtorsmaster', 'currencies.currabrev=debtorsmaster.currcode'),
('debtorsmaster', 'paymentterms', 'debtorsmaster.paymentterms=paymentterms.termsindicator'),
('paymentterms', 'debtorsmaster', 'paymentterms.termsindicator=debtorsmaster.paymentterms'),
('debtorsmaster', 'salestypes', 'debtorsmaster.salestype=salestypes.typeabbrev'),
('salestypes', 'debtorsmaster', 'salestypes.typeabbrev=debtorsmaster.salestype'),
('debtortrans', 'custbranch', 'debtortrans.debtorno=custbranch.debtorno'),
('custbranch', 'debtortrans', 'custbranch.debtorno=debtortrans.debtorno'),
('debtortrans', 'systypes', 'debtortrans.type=systypes.typeid'),
('systypes', 'debtortrans', 'systypes.typeid=debtortrans.type'),
('debtortrans', 'periods', 'debtortrans.prd=periods.periodno'),
('periods', 'debtortrans', 'periods.periodno=debtortrans.prd'),
('debtortranstaxes', 'taxauthorities', 'debtortranstaxes.taxauthid=taxauthorities.taxid'),
('taxauthorities', 'debtortranstaxes', 'taxauthorities.taxid=debtortranstaxes.taxauthid'),
('debtortranstaxes', 'debtortrans', 'debtortranstaxes.debtortransid=debtortrans.id'),
('debtortrans', 'debtortranstaxes', 'debtortrans.id=debtortranstaxes.debtortransid'),
('discountmatrix', 'salestypes', 'discountmatrix.salestype=salestypes.typeabbrev'),
('salestypes', 'discountmatrix', 'salestypes.typeabbrev=discountmatrix.salestype'),
('freightcosts', 'locations', 'freightcosts.locationfrom=locations.loccode'),
('locations', 'freightcosts', 'locations.loccode=freightcosts.locationfrom'),
('freightcosts', 'shippers', 'freightcosts.shipperid=shippers.shipper_id'),
('shippers', 'freightcosts', 'shippers.shipper_id=freightcosts.shipperid'),
('gltrans', 'chartmaster', 'gltrans.account=chartmaster.accountcode'),
('chartmaster', 'gltrans', 'chartmaster.accountcode=gltrans.account'),
('gltrans', 'systypes', 'gltrans.type=systypes.typeid'),
('systypes', 'gltrans', 'systypes.typeid=gltrans.type'),
('gltrans', 'periods', 'gltrans.periodno=periods.periodno'),
('periods', 'gltrans', 'periods.periodno=gltrans.periodno'),
('grns', 'suppliers', 'grns.supplierid=suppliers.supplierid'),
('suppliers', 'grns', 'suppliers.supplierid=grns.supplierid'),
('grns', 'purchorderdetails', 'grns.podetailitem=purchorderdetails.podetailitem'),
('purchorderdetails', 'grns', 'purchorderdetails.podetailitem=grns.podetailitem'),
('locations', 'taxprovinces', 'locations.taxprovinceid=taxprovinces.taxprovinceid'),
('taxprovinces', 'locations', 'taxprovinces.taxprovinceid=locations.taxprovinceid'),
('locstock', 'locations', 'locstock.loccode=locations.loccode'),
('locations', 'locstock', 'locations.loccode=locstock.loccode'),
('locstock', 'stockmaster', 'locstock.stockid=stockmaster.stockid'),
('stockmaster', 'locstock', 'stockmaster.stockid=locstock.stockid'),
('loctransfers', 'locations', 'loctransfers.shiploc=locations.loccode'),
('locations', 'loctransfers', 'locations.loccode=loctransfers.shiploc'),
('loctransfers', 'locations', 'loctransfers.recloc=locations.loccode'),
('locations', 'loctransfers', 'locations.loccode=loctransfers.recloc'),
('loctransfers', 'stockmaster', 'loctransfers.stockid=stockmaster.stockid'),
('stockmaster', 'loctransfers', 'stockmaster.stockid=loctransfers.stockid'),
('orderdeliverydifferencesl', 'stockmaster', 'orderdeliverydifferenceslog.stockid=stockmaster.stockid'),
('stockmaster', 'orderdeliverydifferencesl', 'stockmaster.stockid=orderdeliverydifferenceslog.stockid'),
('orderdeliverydifferencesl', 'custbranch', 'orderdeliverydifferenceslog.debtorno=custbranch.debtorno'),
('custbranch', 'orderdeliverydifferencesl', 'custbranch.debtorno=orderdeliverydifferenceslog.debtorno'),
('orderdeliverydifferencesl', 'salesorders', 'orderdeliverydifferenceslog.branchcode=salesorders.orderno'),
('salesorders', 'orderdeliverydifferencesl', 'salesorders.orderno=orderdeliverydifferenceslog.branchcode'),
('prices', 'stockmaster', 'prices.stockid=stockmaster.stockid'),
('stockmaster', 'prices', 'stockmaster.stockid=prices.stockid'),
('prices', 'currencies', 'prices.currabrev=currencies.currabrev'),
('currencies', 'prices', 'currencies.currabrev=prices.currabrev'),
('prices', 'salestypes', 'prices.typeabbrev=salestypes.typeabbrev'),
('salestypes', 'prices', 'salestypes.typeabbrev=prices.typeabbrev'),
('purchdata', 'stockmaster', 'purchdata.stockid=stockmaster.stockid'),
('stockmaster', 'purchdata', 'stockmaster.stockid=purchdata.stockid'),
('purchdata', 'suppliers', 'purchdata.supplierno=suppliers.supplierid'),
('suppliers', 'purchdata', 'suppliers.supplierid=purchdata.supplierno'),
('purchorderdetails', 'purchorders', 'purchorderdetails.orderno=purchorders.orderno'),
('purchorders', 'purchorderdetails', 'purchorders.orderno=purchorderdetails.orderno'),
('purchorders', 'suppliers', 'purchorders.supplierno=suppliers.supplierid'),
('suppliers', 'purchorders', 'suppliers.supplierid=purchorders.supplierno'),
('purchorders', 'locations', 'purchorders.intostocklocation=locations.loccode'),
('locations', 'purchorders', 'locations.loccode=purchorders.intostocklocation'),
('recurringsalesorders', 'custbranch', 'recurringsalesorders.branchcode=custbranch.branchcode'),
('custbranch', 'recurringsalesorders', 'custbranch.branchcode=recurringsalesorders.branchcode'),
('recurrsalesorderdetails', 'recurringsalesorders', 'recurrsalesorderdetails.recurrorderno=recurringsalesorders.recurrorderno'),
('recurringsalesorders', 'recurrsalesorderdetails', 'recurringsalesorders.recurrorderno=recurrsalesorderdetails.recurrorderno'),
('recurrsalesorderdetails', 'stockmaster', 'recurrsalesorderdetails.stkcode=stockmaster.stockid'),
('stockmaster', 'recurrsalesorderdetails', 'stockmaster.stockid=recurrsalesorderdetails.stkcode'),
('reportcolumns', 'reportheaders', 'reportcolumns.reportid=reportheaders.reportid'),
('reportheaders', 'reportcolumns', 'reportheaders.reportid=reportcolumns.reportid'),
('salesanalysis', 'periods', 'salesanalysis.periodno=periods.periodno'),
('periods', 'salesanalysis', 'periods.periodno=salesanalysis.periodno'),
('salescatprod', 'stockmaster', 'salescatprod.stockid=stockmaster.stockid'),
('stockmaster', 'salescatprod', 'stockmaster.stockid=salescatprod.stockid'),
('salescatprod', 'salescat', 'salescatprod.salescatid=salescat.salescatid'),
('salescat', 'salescatprod', 'salescat.salescatid=salescatprod.salescatid'),
('salesorderdetails', 'salesorders', 'salesorderdetails.orderno=salesorders.orderno'),
('salesorders', 'salesorderdetails', 'salesorders.orderno=salesorderdetails.orderno'),
('salesorderdetails', 'stockmaster', 'salesorderdetails.stkcode=stockmaster.stockid'),
('stockmaster', 'salesorderdetails', 'stockmaster.stockid=salesorderdetails.stkcode'),
('salesorders', 'custbranch', 'salesorders.branchcode=custbranch.branchcode'),
('custbranch', 'salesorders', 'custbranch.branchcode=salesorders.branchcode'),
('salesorders', 'shippers', 'salesorders.debtorno=shippers.shipper_id'),
('shippers', 'salesorders', 'shippers.shipper_id=salesorders.debtorno'),
('salesorders', 'locations', 'salesorders.fromstkloc=locations.loccode'),
('locations', 'salesorders', 'locations.loccode=salesorders.fromstkloc'),
('securitygroups', 'securityroles', 'securitygroups.secroleid=securityroles.secroleid'),
('securityroles', 'securitygroups', 'securityroles.secroleid=securitygroups.secroleid'),
('securitygroups', 'securitytokens', 'securitygroups.tokenid=securitytokens.tokenid'),
('securitytokens', 'securitygroups', 'securitytokens.tokenid=securitygroups.tokenid'),
('shipmentcharges', 'shipments', 'shipmentcharges.shiptref=shipments.shiptref'),
('shipments', 'shipmentcharges', 'shipments.shiptref=shipmentcharges.shiptref'),
('shipmentcharges', 'systypes', 'shipmentcharges.transtype=systypes.typeid'),
('systypes', 'shipmentcharges', 'systypes.typeid=shipmentcharges.transtype'),
('shipments', 'suppliers', 'shipments.supplierid=suppliers.supplierid');
INSERT INTO `reportlinks` (`table1`, `table2`, `equation`) VALUES
('suppliers', 'shipments', 'suppliers.supplierid=shipments.supplierid'),
('stockcheckfreeze', 'stockmaster', 'stockcheckfreeze.stockid=stockmaster.stockid'),
('stockmaster', 'stockcheckfreeze', 'stockmaster.stockid=stockcheckfreeze.stockid'),
('stockcheckfreeze', 'locations', 'stockcheckfreeze.loccode=locations.loccode'),
('locations', 'stockcheckfreeze', 'locations.loccode=stockcheckfreeze.loccode'),
('stockcounts', 'stockmaster', 'stockcounts.stockid=stockmaster.stockid'),
('stockmaster', 'stockcounts', 'stockmaster.stockid=stockcounts.stockid'),
('stockcounts', 'locations', 'stockcounts.loccode=locations.loccode'),
('locations', 'stockcounts', 'locations.loccode=stockcounts.loccode'),
('stockmaster', 'stockcategory', 'stockmaster.categoryid=stockcategory.categoryid'),
('stockcategory', 'stockmaster', 'stockcategory.categoryid=stockmaster.categoryid'),
('stockmaster', 'taxcategories', 'stockmaster.taxcatid=taxcategories.taxcatid'),
('taxcategories', 'stockmaster', 'taxcategories.taxcatid=stockmaster.taxcatid'),
('stockmoves', 'stockmaster', 'stockmoves.stockid=stockmaster.stockid'),
('stockmaster', 'stockmoves', 'stockmaster.stockid=stockmoves.stockid'),
('stockmoves', 'systypes', 'stockmoves.type=systypes.typeid'),
('systypes', 'stockmoves', 'systypes.typeid=stockmoves.type'),
('stockmoves', 'locations', 'stockmoves.loccode=locations.loccode'),
('locations', 'stockmoves', 'locations.loccode=stockmoves.loccode'),
('stockmoves', 'periods', 'stockmoves.prd=periods.periodno'),
('periods', 'stockmoves', 'periods.periodno=stockmoves.prd'),
('stockmovestaxes', 'taxauthorities', 'stockmovestaxes.taxauthid=taxauthorities.taxid'),
('taxauthorities', 'stockmovestaxes', 'taxauthorities.taxid=stockmovestaxes.taxauthid'),
('stockserialitems', 'stockmaster', 'stockserialitems.stockid=stockmaster.stockid'),
('stockmaster', 'stockserialitems', 'stockmaster.stockid=stockserialitems.stockid'),
('stockserialitems', 'locations', 'stockserialitems.loccode=locations.loccode'),
('locations', 'stockserialitems', 'locations.loccode=stockserialitems.loccode'),
('stockserialmoves', 'stockmoves', 'stockserialmoves.stockmoveno=stockmoves.stkmoveno'),
('stockmoves', 'stockserialmoves', 'stockmoves.stkmoveno=stockserialmoves.stockmoveno'),
('stockserialmoves', 'stockserialitems', 'stockserialmoves.stockid=stockserialitems.stockid'),
('stockserialitems', 'stockserialmoves', 'stockserialitems.stockid=stockserialmoves.stockid'),
('suppallocs', 'supptrans', 'suppallocs.transid_allocfrom=supptrans.id'),
('supptrans', 'suppallocs', 'supptrans.id=suppallocs.transid_allocfrom'),
('suppallocs', 'supptrans', 'suppallocs.transid_allocto=supptrans.id'),
('supptrans', 'suppallocs', 'supptrans.id=suppallocs.transid_allocto'),
('suppliercontacts', 'suppliers', 'suppliercontacts.supplierid=suppliers.supplierid'),
('suppliers', 'suppliercontacts', 'suppliers.supplierid=suppliercontacts.supplierid'),
('suppliers', 'currencies', 'suppliers.currcode=currencies.currabrev'),
('currencies', 'suppliers', 'currencies.currabrev=suppliers.currcode'),
('suppliers', 'paymentterms', 'suppliers.paymentterms=paymentterms.termsindicator'),
('paymentterms', 'suppliers', 'paymentterms.termsindicator=suppliers.paymentterms'),
('suppliers', 'taxgroups', 'suppliers.taxgroupid=taxgroups.taxgroupid'),
('taxgroups', 'suppliers', 'taxgroups.taxgroupid=suppliers.taxgroupid'),
('supptrans', 'systypes', 'supptrans.type=systypes.typeid'),
('systypes', 'supptrans', 'systypes.typeid=supptrans.type'),
('supptrans', 'suppliers', 'supptrans.supplierno=suppliers.supplierid'),
('suppliers', 'supptrans', 'suppliers.supplierid=supptrans.supplierno'),
('supptranstaxes', 'taxauthorities', 'supptranstaxes.taxauthid=taxauthorities.taxid'),
('taxauthorities', 'supptranstaxes', 'taxauthorities.taxid=supptranstaxes.taxauthid'),
('supptranstaxes', 'supptrans', 'supptranstaxes.supptransid=supptrans.id'),
('supptrans', 'supptranstaxes', 'supptrans.id=supptranstaxes.supptransid'),
('taxauthorities', 'chartmaster', 'taxauthorities.taxglcode=chartmaster.accountcode'),
('chartmaster', 'taxauthorities', 'chartmaster.accountcode=taxauthorities.taxglcode'),
('taxauthorities', 'chartmaster', 'taxauthorities.purchtaxglaccount=chartmaster.accountcode'),
('chartmaster', 'taxauthorities', 'chartmaster.accountcode=taxauthorities.purchtaxglaccount'),
('taxauthrates', 'taxauthorities', 'taxauthrates.taxauthority=taxauthorities.taxid'),
('taxauthorities', 'taxauthrates', 'taxauthorities.taxid=taxauthrates.taxauthority'),
('taxauthrates', 'taxcategories', 'taxauthrates.taxcatid=taxcategories.taxcatid'),
('taxcategories', 'taxauthrates', 'taxcategories.taxcatid=taxauthrates.taxcatid'),
('taxauthrates', 'taxprovinces', 'taxauthrates.dispatchtaxprovince=taxprovinces.taxprovinceid'),
('taxprovinces', 'taxauthrates', 'taxprovinces.taxprovinceid=taxauthrates.dispatchtaxprovince'),
('taxgrouptaxes', 'taxgroups', 'taxgrouptaxes.taxgroupid=taxgroups.taxgroupid'),
('taxgroups', 'taxgrouptaxes', 'taxgroups.taxgroupid=taxgrouptaxes.taxgroupid'),
('taxgrouptaxes', 'taxauthorities', 'taxgrouptaxes.taxauthid=taxauthorities.taxid'),
('taxauthorities', 'taxgrouptaxes', 'taxauthorities.taxid=taxgrouptaxes.taxauthid'),
('workcentres', 'locations', 'workcentres.location=locations.loccode'),
('locations', 'workcentres', 'locations.loccode=workcentres.location'),
('worksorders', 'locations', 'worksorders.loccode=locations.loccode'),
('locations', 'worksorders', 'locations.loccode=worksorders.loccode'),
('worksorders', 'stockmaster', 'worksorders.stockid=stockmaster.stockid'),
('stockmaster', 'worksorders', 'stockmaster.stockid=worksorders.stockid'),
('www_users', 'locations', 'www_users.defaultlocation=locations.loccode'),
('locations', 'www_users', 'locations.loccode=www_users.defaultlocation'),
('accountgroups', 'accountsection', 'accountgroups.sectioninaccounts=accountsection.sectionid'),
('accountsection', 'accountgroups', 'accountsection.sectionid=accountgroups.sectioninaccounts'),
('bankaccounts', 'chartmaster', 'bankaccounts.accountcode=chartmaster.accountcode'),
('chartmaster', 'bankaccounts', 'chartmaster.accountcode=bankaccounts.accountcode'),
('banktrans', 'systypes', 'banktrans.type=systypes.typeid'),
('systypes', 'banktrans', 'systypes.typeid=banktrans.type'),
('banktrans', 'bankaccounts', 'banktrans.bankact=bankaccounts.accountcode'),
('bankaccounts', 'banktrans', 'bankaccounts.accountcode=banktrans.bankact'),
('bom', 'stockmaster', 'bom.parent=stockmaster.stockid'),
('stockmaster', 'bom', 'stockmaster.stockid=bom.parent'),
('bom', 'stockmaster', 'bom.component=stockmaster.stockid'),
('stockmaster', 'bom', 'stockmaster.stockid=bom.component'),
('bom', 'workcentres', 'bom.workcentreadded=workcentres.code'),
('workcentres', 'bom', 'workcentres.code=bom.workcentreadded'),
('bom', 'locations', 'bom.loccode=locations.loccode'),
('locations', 'bom', 'locations.loccode=bom.loccode'),
('buckets', 'workcentres', 'buckets.workcentre=workcentres.code'),
('workcentres', 'buckets', 'workcentres.code=buckets.workcentre'),
('chartdetails', 'chartmaster', 'chartdetails.accountcode=chartmaster.accountcode'),
('chartmaster', 'chartdetails', 'chartmaster.accountcode=chartdetails.accountcode'),
('chartdetails', 'periods', 'chartdetails.period=periods.periodno'),
('periods', 'chartdetails', 'periods.periodno=chartdetails.period'),
('chartmaster', 'accountgroups', 'chartmaster.group_=accountgroups.groupname'),
('accountgroups', 'chartmaster', 'accountgroups.groupname=chartmaster.group_'),
('contractbom', 'workcentres', 'contractbom.workcentreadded=workcentres.code'),
('workcentres', 'contractbom', 'workcentres.code=contractbom.workcentreadded'),
('contractbom', 'locations', 'contractbom.loccode=locations.loccode'),
('locations', 'contractbom', 'locations.loccode=contractbom.loccode'),
('contractbom', 'stockmaster', 'contractbom.component=stockmaster.stockid'),
('stockmaster', 'contractbom', 'stockmaster.stockid=contractbom.component'),
('contractreqts', 'contracts', 'contractreqts.contract=contracts.contractref'),
('contracts', 'contractreqts', 'contracts.contractref=contractreqts.contract'),
('contracts', 'custbranch', 'contracts.debtorno=custbranch.debtorno'),
('custbranch', 'contracts', 'custbranch.debtorno=contracts.debtorno'),
('contracts', 'stockcategory', 'contracts.branchcode=stockcategory.categoryid'),
('stockcategory', 'contracts', 'stockcategory.categoryid=contracts.branchcode'),
('contracts', 'salestypes', 'contracts.typeabbrev=salestypes.typeabbrev'),
('salestypes', 'contracts', 'salestypes.typeabbrev=contracts.typeabbrev'),
('custallocns', 'debtortrans', 'custallocns.transid_allocfrom=debtortrans.id'),
('debtortrans', 'custallocns', 'debtortrans.id=custallocns.transid_allocfrom'),
('custallocns', 'debtortrans', 'custallocns.transid_allocto=debtortrans.id'),
('debtortrans', 'custallocns', 'debtortrans.id=custallocns.transid_allocto'),
('custbranch', 'debtorsmaster', 'custbranch.debtorno=debtorsmaster.debtorno'),
('debtorsmaster', 'custbranch', 'debtorsmaster.debtorno=custbranch.debtorno'),
('custbranch', 'areas', 'custbranch.area=areas.areacode'),
('areas', 'custbranch', 'areas.areacode=custbranch.area'),
('custbranch', 'salesman', 'custbranch.salesman=salesman.salesmancode'),
('salesman', 'custbranch', 'salesman.salesmancode=custbranch.salesman'),
('custbranch', 'locations', 'custbranch.defaultlocation=locations.loccode'),
('locations', 'custbranch', 'locations.loccode=custbranch.defaultlocation'),
('custbranch', 'shippers', 'custbranch.defaultshipvia=shippers.shipper_id'),
('shippers', 'custbranch', 'shippers.shipper_id=custbranch.defaultshipvia'),
('debtorsmaster', 'holdreasons', 'debtorsmaster.holdreason=holdreasons.reasoncode'),
('holdreasons', 'debtorsmaster', 'holdreasons.reasoncode=debtorsmaster.holdreason'),
('debtorsmaster', 'currencies', 'debtorsmaster.currcode=currencies.currabrev'),
('currencies', 'debtorsmaster', 'currencies.currabrev=debtorsmaster.currcode'),
('debtorsmaster', 'paymentterms', 'debtorsmaster.paymentterms=paymentterms.termsindicator'),
('paymentterms', 'debtorsmaster', 'paymentterms.termsindicator=debtorsmaster.paymentterms'),
('debtorsmaster', 'salestypes', 'debtorsmaster.salestype=salestypes.typeabbrev'),
('salestypes', 'debtorsmaster', 'salestypes.typeabbrev=debtorsmaster.salestype'),
('debtortrans', 'custbranch', 'debtortrans.debtorno=custbranch.debtorno'),
('custbranch', 'debtortrans', 'custbranch.debtorno=debtortrans.debtorno'),
('debtortrans', 'systypes', 'debtortrans.type=systypes.typeid'),
('systypes', 'debtortrans', 'systypes.typeid=debtortrans.type'),
('debtortrans', 'periods', 'debtortrans.prd=periods.periodno'),
('periods', 'debtortrans', 'periods.periodno=debtortrans.prd'),
('debtortranstaxes', 'taxauthorities', 'debtortranstaxes.taxauthid=taxauthorities.taxid'),
('taxauthorities', 'debtortranstaxes', 'taxauthorities.taxid=debtortranstaxes.taxauthid'),
('debtortranstaxes', 'debtortrans', 'debtortranstaxes.debtortransid=debtortrans.id'),
('debtortrans', 'debtortranstaxes', 'debtortrans.id=debtortranstaxes.debtortransid'),
('discountmatrix', 'salestypes', 'discountmatrix.salestype=salestypes.typeabbrev'),
('salestypes', 'discountmatrix', 'salestypes.typeabbrev=discountmatrix.salestype'),
('freightcosts', 'locations', 'freightcosts.locationfrom=locations.loccode'),
('locations', 'freightcosts', 'locations.loccode=freightcosts.locationfrom'),
('freightcosts', 'shippers', 'freightcosts.shipperid=shippers.shipper_id'),
('shippers', 'freightcosts', 'shippers.shipper_id=freightcosts.shipperid'),
('gltrans', 'chartmaster', 'gltrans.account=chartmaster.accountcode'),
('chartmaster', 'gltrans', 'chartmaster.accountcode=gltrans.account'),
('gltrans', 'systypes', 'gltrans.type=systypes.typeid'),
('systypes', 'gltrans', 'systypes.typeid=gltrans.type'),
('gltrans', 'periods', 'gltrans.periodno=periods.periodno'),
('periods', 'gltrans', 'periods.periodno=gltrans.periodno'),
('grns', 'suppliers', 'grns.supplierid=suppliers.supplierid'),
('suppliers', 'grns', 'suppliers.supplierid=grns.supplierid'),
('grns', 'purchorderdetails', 'grns.podetailitem=purchorderdetails.podetailitem'),
('purchorderdetails', 'grns', 'purchorderdetails.podetailitem=grns.podetailitem'),
('locations', 'taxprovinces', 'locations.taxprovinceid=taxprovinces.taxprovinceid'),
('taxprovinces', 'locations', 'taxprovinces.taxprovinceid=locations.taxprovinceid'),
('locstock', 'locations', 'locstock.loccode=locations.loccode'),
('locations', 'locstock', 'locations.loccode=locstock.loccode'),
('locstock', 'stockmaster', 'locstock.stockid=stockmaster.stockid'),
('stockmaster', 'locstock', 'stockmaster.stockid=locstock.stockid'),
('loctransfers', 'locations', 'loctransfers.shiploc=locations.loccode'),
('locations', 'loctransfers', 'locations.loccode=loctransfers.shiploc'),
('loctransfers', 'locations', 'loctransfers.recloc=locations.loccode'),
('locations', 'loctransfers', 'locations.loccode=loctransfers.recloc'),
('loctransfers', 'stockmaster', 'loctransfers.stockid=stockmaster.stockid'),
('stockmaster', 'loctransfers', 'stockmaster.stockid=loctransfers.stockid'),
('orderdeliverydifferencesl', 'stockmaster', 'orderdeliverydifferenceslog.stockid=stockmaster.stockid'),
('stockmaster', 'orderdeliverydifferencesl', 'stockmaster.stockid=orderdeliverydifferenceslog.stockid'),
('orderdeliverydifferencesl', 'custbranch', 'orderdeliverydifferenceslog.debtorno=custbranch.debtorno'),
('custbranch', 'orderdeliverydifferencesl', 'custbranch.debtorno=orderdeliverydifferenceslog.debtorno'),
('orderdeliverydifferencesl', 'salesorders', 'orderdeliverydifferenceslog.branchcode=salesorders.orderno'),
('salesorders', 'orderdeliverydifferencesl', 'salesorders.orderno=orderdeliverydifferenceslog.branchcode'),
('prices', 'stockmaster', 'prices.stockid=stockmaster.stockid'),
('stockmaster', 'prices', 'stockmaster.stockid=prices.stockid'),
('prices', 'currencies', 'prices.currabrev=currencies.currabrev'),
('currencies', 'prices', 'currencies.currabrev=prices.currabrev'),
('prices', 'salestypes', 'prices.typeabbrev=salestypes.typeabbrev'),
('salestypes', 'prices', 'salestypes.typeabbrev=prices.typeabbrev'),
('purchdata', 'stockmaster', 'purchdata.stockid=stockmaster.stockid'),
('stockmaster', 'purchdata', 'stockmaster.stockid=purchdata.stockid'),
('purchdata', 'suppliers', 'purchdata.supplierno=suppliers.supplierid'),
('suppliers', 'purchdata', 'suppliers.supplierid=purchdata.supplierno'),
('purchorderdetails', 'purchorders', 'purchorderdetails.orderno=purchorders.orderno'),
('purchorders', 'purchorderdetails', 'purchorders.orderno=purchorderdetails.orderno'),
('purchorders', 'suppliers', 'purchorders.supplierno=suppliers.supplierid'),
('suppliers', 'purchorders', 'suppliers.supplierid=purchorders.supplierno'),
('purchorders', 'locations', 'purchorders.intostocklocation=locations.loccode'),
('locations', 'purchorders', 'locations.loccode=purchorders.intostocklocation'),
('recurringsalesorders', 'custbranch', 'recurringsalesorders.branchcode=custbranch.branchcode'),
('custbranch', 'recurringsalesorders', 'custbranch.branchcode=recurringsalesorders.branchcode'),
('recurrsalesorderdetails', 'recurringsalesorders', 'recurrsalesorderdetails.recurrorderno=recurringsalesorders.recurrorderno'),
('recurringsalesorders', 'recurrsalesorderdetails', 'recurringsalesorders.recurrorderno=recurrsalesorderdetails.recurrorderno'),
('recurrsalesorderdetails', 'stockmaster', 'recurrsalesorderdetails.stkcode=stockmaster.stockid'),
('stockmaster', 'recurrsalesorderdetails', 'stockmaster.stockid=recurrsalesorderdetails.stkcode'),
('reportcolumns', 'reportheaders', 'reportcolumns.reportid=reportheaders.reportid'),
('reportheaders', 'reportcolumns', 'reportheaders.reportid=reportcolumns.reportid'),
('salesanalysis', 'periods', 'salesanalysis.periodno=periods.periodno'),
('periods', 'salesanalysis', 'periods.periodno=salesanalysis.periodno'),
('salescatprod', 'stockmaster', 'salescatprod.stockid=stockmaster.stockid'),
('stockmaster', 'salescatprod', 'stockmaster.stockid=salescatprod.stockid'),
('salescatprod', 'salescat', 'salescatprod.salescatid=salescat.salescatid'),
('salescat', 'salescatprod', 'salescat.salescatid=salescatprod.salescatid'),
('salesorderdetails', 'salesorders', 'salesorderdetails.orderno=salesorders.orderno'),
('salesorders', 'salesorderdetails', 'salesorders.orderno=salesorderdetails.orderno'),
('salesorderdetails', 'stockmaster', 'salesorderdetails.stkcode=stockmaster.stockid'),
('stockmaster', 'salesorderdetails', 'stockmaster.stockid=salesorderdetails.stkcode'),
('salesorders', 'custbranch', 'salesorders.branchcode=custbranch.branchcode'),
('custbranch', 'salesorders', 'custbranch.branchcode=salesorders.branchcode'),
('salesorders', 'shippers', 'salesorders.debtorno=shippers.shipper_id'),
('shippers', 'salesorders', 'shippers.shipper_id=salesorders.debtorno'),
('salesorders', 'locations', 'salesorders.fromstkloc=locations.loccode'),
('locations', 'salesorders', 'locations.loccode=salesorders.fromstkloc'),
('securitygroups', 'securityroles', 'securitygroups.secroleid=securityroles.secroleid'),
('securityroles', 'securitygroups', 'securityroles.secroleid=securitygroups.secroleid'),
('securitygroups', 'securitytokens', 'securitygroups.tokenid=securitytokens.tokenid'),
('securitytokens', 'securitygroups', 'securitytokens.tokenid=securitygroups.tokenid'),
('shipmentcharges', 'shipments', 'shipmentcharges.shiptref=shipments.shiptref'),
('shipments', 'shipmentcharges', 'shipments.shiptref=shipmentcharges.shiptref'),
('shipmentcharges', 'systypes', 'shipmentcharges.transtype=systypes.typeid'),
('systypes', 'shipmentcharges', 'systypes.typeid=shipmentcharges.transtype'),
('shipments', 'suppliers', 'shipments.supplierid=suppliers.supplierid'),
('suppliers', 'shipments', 'suppliers.supplierid=shipments.supplierid'),
('stockcheckfreeze', 'stockmaster', 'stockcheckfreeze.stockid=stockmaster.stockid'),
('stockmaster', 'stockcheckfreeze', 'stockmaster.stockid=stockcheckfreeze.stockid'),
('stockcheckfreeze', 'locations', 'stockcheckfreeze.loccode=locations.loccode'),
('locations', 'stockcheckfreeze', 'locations.loccode=stockcheckfreeze.loccode'),
('stockcounts', 'stockmaster', 'stockcounts.stockid=stockmaster.stockid'),
('stockmaster', 'stockcounts', 'stockmaster.stockid=stockcounts.stockid'),
('stockcounts', 'locations', 'stockcounts.loccode=locations.loccode'),
('locations', 'stockcounts', 'locations.loccode=stockcounts.loccode'),
('stockmaster', 'stockcategory', 'stockmaster.categoryid=stockcategory.categoryid'),
('stockcategory', 'stockmaster', 'stockcategory.categoryid=stockmaster.categoryid'),
('stockmaster', 'taxcategories', 'stockmaster.taxcatid=taxcategories.taxcatid'),
('taxcategories', 'stockmaster', 'taxcategories.taxcatid=stockmaster.taxcatid'),
('stockmoves', 'stockmaster', 'stockmoves.stockid=stockmaster.stockid'),
('stockmaster', 'stockmoves', 'stockmaster.stockid=stockmoves.stockid'),
('stockmoves', 'systypes', 'stockmoves.type=systypes.typeid'),
('systypes', 'stockmoves', 'systypes.typeid=stockmoves.type'),
('stockmoves', 'locations', 'stockmoves.loccode=locations.loccode'),
('locations', 'stockmoves', 'locations.loccode=stockmoves.loccode'),
('stockmoves', 'periods', 'stockmoves.prd=periods.periodno'),
('periods', 'stockmoves', 'periods.periodno=stockmoves.prd'),
('stockmovestaxes', 'taxauthorities', 'stockmovestaxes.taxauthid=taxauthorities.taxid'),
('taxauthorities', 'stockmovestaxes', 'taxauthorities.taxid=stockmovestaxes.taxauthid'),
('stockserialitems', 'stockmaster', 'stockserialitems.stockid=stockmaster.stockid'),
('stockmaster', 'stockserialitems', 'stockmaster.stockid=stockserialitems.stockid'),
('stockserialitems', 'locations', 'stockserialitems.loccode=locations.loccode'),
('locations', 'stockserialitems', 'locations.loccode=stockserialitems.loccode'),
('stockserialmoves', 'stockmoves', 'stockserialmoves.stockmoveno=stockmoves.stkmoveno'),
('stockmoves', 'stockserialmoves', 'stockmoves.stkmoveno=stockserialmoves.stockmoveno'),
('stockserialmoves', 'stockserialitems', 'stockserialmoves.stockid=stockserialitems.stockid'),
('stockserialitems', 'stockserialmoves', 'stockserialitems.stockid=stockserialmoves.stockid'),
('suppallocs', 'supptrans', 'suppallocs.transid_allocfrom=supptrans.id'),
('supptrans', 'suppallocs', 'supptrans.id=suppallocs.transid_allocfrom'),
('suppallocs', 'supptrans', 'suppallocs.transid_allocto=supptrans.id'),
('supptrans', 'suppallocs', 'supptrans.id=suppallocs.transid_allocto'),
('suppliercontacts', 'suppliers', 'suppliercontacts.supplierid=suppliers.supplierid'),
('suppliers', 'suppliercontacts', 'suppliers.supplierid=suppliercontacts.supplierid'),
('suppliers', 'currencies', 'suppliers.currcode=currencies.currabrev'),
('currencies', 'suppliers', 'currencies.currabrev=suppliers.currcode'),
('suppliers', 'paymentterms', 'suppliers.paymentterms=paymentterms.termsindicator'),
('paymentterms', 'suppliers', 'paymentterms.termsindicator=suppliers.paymentterms'),
('suppliers', 'taxgroups', 'suppliers.taxgroupid=taxgroups.taxgroupid'),
('taxgroups', 'suppliers', 'taxgroups.taxgroupid=suppliers.taxgroupid'),
('supptrans', 'systypes', 'supptrans.type=systypes.typeid'),
('systypes', 'supptrans', 'systypes.typeid=supptrans.type'),
('supptrans', 'suppliers', 'supptrans.supplierno=suppliers.supplierid'),
('suppliers', 'supptrans', 'suppliers.supplierid=supptrans.supplierno'),
('supptranstaxes', 'taxauthorities', 'supptranstaxes.taxauthid=taxauthorities.taxid'),
('taxauthorities', 'supptranstaxes', 'taxauthorities.taxid=supptranstaxes.taxauthid'),
('supptranstaxes', 'supptrans', 'supptranstaxes.supptransid=supptrans.id'),
('supptrans', 'supptranstaxes', 'supptrans.id=supptranstaxes.supptransid'),
('taxauthorities', 'chartmaster', 'taxauthorities.taxglcode=chartmaster.accountcode'),
('chartmaster', 'taxauthorities', 'chartmaster.accountcode=taxauthorities.taxglcode'),
('taxauthorities', 'chartmaster', 'taxauthorities.purchtaxglaccount=chartmaster.accountcode'),
('chartmaster', 'taxauthorities', 'chartmaster.accountcode=taxauthorities.purchtaxglaccount'),
('taxauthrates', 'taxauthorities', 'taxauthrates.taxauthority=taxauthorities.taxid'),
('taxauthorities', 'taxauthrates', 'taxauthorities.taxid=taxauthrates.taxauthority'),
('taxauthrates', 'taxcategories', 'taxauthrates.taxcatid=taxcategories.taxcatid'),
('taxcategories', 'taxauthrates', 'taxcategories.taxcatid=taxauthrates.taxcatid'),
('taxauthrates', 'taxprovinces', 'taxauthrates.dispatchtaxprovince=taxprovinces.taxprovinceid'),
('taxprovinces', 'taxauthrates', 'taxprovinces.taxprovinceid=taxauthrates.dispatchtaxprovince'),
('taxgrouptaxes', 'taxgroups', 'taxgrouptaxes.taxgroupid=taxgroups.taxgroupid'),
('taxgroups', 'taxgrouptaxes', 'taxgroups.taxgroupid=taxgrouptaxes.taxgroupid'),
('taxgrouptaxes', 'taxauthorities', 'taxgrouptaxes.taxauthid=taxauthorities.taxid'),
('taxauthorities', 'taxgrouptaxes', 'taxauthorities.taxid=taxgrouptaxes.taxauthid'),
('workcentres', 'locations', 'workcentres.location=locations.loccode'),
('locations', 'workcentres', 'locations.loccode=workcentres.location'),
('worksorders', 'locations', 'worksorders.loccode=locations.loccode'),
('locations', 'worksorders', 'locations.loccode=worksorders.loccode'),
('worksorders', 'stockmaster', 'worksorders.stockid=stockmaster.stockid'),
('stockmaster', 'worksorders', 'stockmaster.stockid=worksorders.stockid'),
('www_users', 'locations', 'www_users.defaultlocation=locations.loccode'),
('locations', 'www_users', 'locations.loccode=www_users.defaultlocation'),
('accountgroups', 'accountsection', 'accountgroups.sectioninaccounts=accountsection.sectionid'),
('accountsection', 'accountgroups', 'accountsection.sectionid=accountgroups.sectioninaccounts'),
('bankaccounts', 'chartmaster', 'bankaccounts.accountcode=chartmaster.accountcode'),
('chartmaster', 'bankaccounts', 'chartmaster.accountcode=bankaccounts.accountcode'),
('banktrans', 'systypes', 'banktrans.type=systypes.typeid'),
('systypes', 'banktrans', 'systypes.typeid=banktrans.type'),
('banktrans', 'bankaccounts', 'banktrans.bankact=bankaccounts.accountcode'),
('bankaccounts', 'banktrans', 'bankaccounts.accountcode=banktrans.bankact'),
('bom', 'stockmaster', 'bom.parent=stockmaster.stockid'),
('stockmaster', 'bom', 'stockmaster.stockid=bom.parent'),
('bom', 'stockmaster', 'bom.component=stockmaster.stockid'),
('stockmaster', 'bom', 'stockmaster.stockid=bom.component'),
('bom', 'workcentres', 'bom.workcentreadded=workcentres.code'),
('workcentres', 'bom', 'workcentres.code=bom.workcentreadded'),
('bom', 'locations', 'bom.loccode=locations.loccode'),
('locations', 'bom', 'locations.loccode=bom.loccode'),
('buckets', 'workcentres', 'buckets.workcentre=workcentres.code'),
('workcentres', 'buckets', 'workcentres.code=buckets.workcentre'),
('chartdetails', 'chartmaster', 'chartdetails.accountcode=chartmaster.accountcode'),
('chartmaster', 'chartdetails', 'chartmaster.accountcode=chartdetails.accountcode'),
('chartdetails', 'periods', 'chartdetails.period=periods.periodno'),
('periods', 'chartdetails', 'periods.periodno=chartdetails.period'),
('chartmaster', 'accountgroups', 'chartmaster.group_=accountgroups.groupname'),
('accountgroups', 'chartmaster', 'accountgroups.groupname=chartmaster.group_'),
('contractbom', 'workcentres', 'contractbom.workcentreadded=workcentres.code'),
('workcentres', 'contractbom', 'workcentres.code=contractbom.workcentreadded'),
('contractbom', 'locations', 'contractbom.loccode=locations.loccode'),
('locations', 'contractbom', 'locations.loccode=contractbom.loccode'),
('contractbom', 'stockmaster', 'contractbom.component=stockmaster.stockid'),
('stockmaster', 'contractbom', 'stockmaster.stockid=contractbom.component'),
('contractreqts', 'contracts', 'contractreqts.contract=contracts.contractref'),
('contracts', 'contractreqts', 'contracts.contractref=contractreqts.contract'),
('contracts', 'custbranch', 'contracts.debtorno=custbranch.debtorno'),
('custbranch', 'contracts', 'custbranch.debtorno=contracts.debtorno'),
('contracts', 'stockcategory', 'contracts.branchcode=stockcategory.categoryid'),
('stockcategory', 'contracts', 'stockcategory.categoryid=contracts.branchcode'),
('contracts', 'salestypes', 'contracts.typeabbrev=salestypes.typeabbrev'),
('salestypes', 'contracts', 'salestypes.typeabbrev=contracts.typeabbrev'),
('custallocns', 'debtortrans', 'custallocns.transid_allocfrom=debtortrans.id'),
('debtortrans', 'custallocns', 'debtortrans.id=custallocns.transid_allocfrom'),
('custallocns', 'debtortrans', 'custallocns.transid_allocto=debtortrans.id'),
('debtortrans', 'custallocns', 'debtortrans.id=custallocns.transid_allocto'),
('custbranch', 'debtorsmaster', 'custbranch.debtorno=debtorsmaster.debtorno'),
('debtorsmaster', 'custbranch', 'debtorsmaster.debtorno=custbranch.debtorno'),
('custbranch', 'areas', 'custbranch.area=areas.areacode'),
('areas', 'custbranch', 'areas.areacode=custbranch.area'),
('custbranch', 'salesman', 'custbranch.salesman=salesman.salesmancode'),
('salesman', 'custbranch', 'salesman.salesmancode=custbranch.salesman'),
('custbranch', 'locations', 'custbranch.defaultlocation=locations.loccode'),
('locations', 'custbranch', 'locations.loccode=custbranch.defaultlocation'),
('custbranch', 'shippers', 'custbranch.defaultshipvia=shippers.shipper_id'),
('shippers', 'custbranch', 'shippers.shipper_id=custbranch.defaultshipvia'),
('debtorsmaster', 'holdreasons', 'debtorsmaster.holdreason=holdreasons.reasoncode'),
('holdreasons', 'debtorsmaster', 'holdreasons.reasoncode=debtorsmaster.holdreason'),
('debtorsmaster', 'currencies', 'debtorsmaster.currcode=currencies.currabrev'),
('currencies', 'debtorsmaster', 'currencies.currabrev=debtorsmaster.currcode'),
('debtorsmaster', 'paymentterms', 'debtorsmaster.paymentterms=paymentterms.termsindicator'),
('paymentterms', 'debtorsmaster', 'paymentterms.termsindicator=debtorsmaster.paymentterms'),
('debtorsmaster', 'salestypes', 'debtorsmaster.salestype=salestypes.typeabbrev'),
('salestypes', 'debtorsmaster', 'salestypes.typeabbrev=debtorsmaster.salestype'),
('debtortrans', 'custbranch', 'debtortrans.debtorno=custbranch.debtorno'),
('custbranch', 'debtortrans', 'custbranch.debtorno=debtortrans.debtorno'),
('debtortrans', 'systypes', 'debtortrans.type=systypes.typeid'),
('systypes', 'debtortrans', 'systypes.typeid=debtortrans.type'),
('debtortrans', 'periods', 'debtortrans.prd=periods.periodno'),
('periods', 'debtortrans', 'periods.periodno=debtortrans.prd'),
('debtortranstaxes', 'taxauthorities', 'debtortranstaxes.taxauthid=taxauthorities.taxid'),
('taxauthorities', 'debtortranstaxes', 'taxauthorities.taxid=debtortranstaxes.taxauthid'),
('debtortranstaxes', 'debtortrans', 'debtortranstaxes.debtortransid=debtortrans.id'),
('debtortrans', 'debtortranstaxes', 'debtortrans.id=debtortranstaxes.debtortransid'),
('discountmatrix', 'salestypes', 'discountmatrix.salestype=salestypes.typeabbrev'),
('salestypes', 'discountmatrix', 'salestypes.typeabbrev=discountmatrix.salestype'),
('freightcosts', 'locations', 'freightcosts.locationfrom=locations.loccode'),
('locations', 'freightcosts', 'locations.loccode=freightcosts.locationfrom'),
('freightcosts', 'shippers', 'freightcosts.shipperid=shippers.shipper_id'),
('shippers', 'freightcosts', 'shippers.shipper_id=freightcosts.shipperid'),
('gltrans', 'chartmaster', 'gltrans.account=chartmaster.accountcode'),
('chartmaster', 'gltrans', 'chartmaster.accountcode=gltrans.account'),
('gltrans', 'systypes', 'gltrans.type=systypes.typeid'),
('systypes', 'gltrans', 'systypes.typeid=gltrans.type'),
('gltrans', 'periods', 'gltrans.periodno=periods.periodno'),
('periods', 'gltrans', 'periods.periodno=gltrans.periodno'),
('grns', 'suppliers', 'grns.supplierid=suppliers.supplierid'),
('suppliers', 'grns', 'suppliers.supplierid=grns.supplierid'),
('grns', 'purchorderdetails', 'grns.podetailitem=purchorderdetails.podetailitem'),
('purchorderdetails', 'grns', 'purchorderdetails.podetailitem=grns.podetailitem'),
('locations', 'taxprovinces', 'locations.taxprovinceid=taxprovinces.taxprovinceid'),
('taxprovinces', 'locations', 'taxprovinces.taxprovinceid=locations.taxprovinceid'),
('locstock', 'locations', 'locstock.loccode=locations.loccode'),
('locations', 'locstock', 'locations.loccode=locstock.loccode'),
('locstock', 'stockmaster', 'locstock.stockid=stockmaster.stockid'),
('stockmaster', 'locstock', 'stockmaster.stockid=locstock.stockid'),
('loctransfers', 'locations', 'loctransfers.shiploc=locations.loccode'),
('locations', 'loctransfers', 'locations.loccode=loctransfers.shiploc'),
('loctransfers', 'locations', 'loctransfers.recloc=locations.loccode'),
('locations', 'loctransfers', 'locations.loccode=loctransfers.recloc'),
('loctransfers', 'stockmaster', 'loctransfers.stockid=stockmaster.stockid'),
('stockmaster', 'loctransfers', 'stockmaster.stockid=loctransfers.stockid'),
('orderdeliverydifferencesl', 'stockmaster', 'orderdeliverydifferenceslog.stockid=stockmaster.stockid'),
('stockmaster', 'orderdeliverydifferencesl', 'stockmaster.stockid=orderdeliverydifferenceslog.stockid'),
('orderdeliverydifferencesl', 'custbranch', 'orderdeliverydifferenceslog.debtorno=custbranch.debtorno'),
('custbranch', 'orderdeliverydifferencesl', 'custbranch.debtorno=orderdeliverydifferenceslog.debtorno'),
('orderdeliverydifferencesl', 'salesorders', 'orderdeliverydifferenceslog.branchcode=salesorders.orderno'),
('salesorders', 'orderdeliverydifferencesl', 'salesorders.orderno=orderdeliverydifferenceslog.branchcode'),
('prices', 'stockmaster', 'prices.stockid=stockmaster.stockid'),
('stockmaster', 'prices', 'stockmaster.stockid=prices.stockid'),
('prices', 'currencies', 'prices.currabrev=currencies.currabrev'),
('currencies', 'prices', 'currencies.currabrev=prices.currabrev'),
('prices', 'salestypes', 'prices.typeabbrev=salestypes.typeabbrev'),
('salestypes', 'prices', 'salestypes.typeabbrev=prices.typeabbrev'),
('purchdata', 'stockmaster', 'purchdata.stockid=stockmaster.stockid'),
('stockmaster', 'purchdata', 'stockmaster.stockid=purchdata.stockid'),
('purchdata', 'suppliers', 'purchdata.supplierno=suppliers.supplierid'),
('suppliers', 'purchdata', 'suppliers.supplierid=purchdata.supplierno'),
('purchorderdetails', 'purchorders', 'purchorderdetails.orderno=purchorders.orderno'),
('purchorders', 'purchorderdetails', 'purchorders.orderno=purchorderdetails.orderno'),
('purchorders', 'suppliers', 'purchorders.supplierno=suppliers.supplierid'),
('suppliers', 'purchorders', 'suppliers.supplierid=purchorders.supplierno'),
('purchorders', 'locations', 'purchorders.intostocklocation=locations.loccode'),
('locations', 'purchorders', 'locations.loccode=purchorders.intostocklocation'),
('recurringsalesorders', 'custbranch', 'recurringsalesorders.branchcode=custbranch.branchcode'),
('custbranch', 'recurringsalesorders', 'custbranch.branchcode=recurringsalesorders.branchcode'),
('recurrsalesorderdetails', 'recurringsalesorders', 'recurrsalesorderdetails.recurrorderno=recurringsalesorders.recurrorderno'),
('recurringsalesorders', 'recurrsalesorderdetails', 'recurringsalesorders.recurrorderno=recurrsalesorderdetails.recurrorderno'),
('recurrsalesorderdetails', 'stockmaster', 'recurrsalesorderdetails.stkcode=stockmaster.stockid'),
('stockmaster', 'recurrsalesorderdetails', 'stockmaster.stockid=recurrsalesorderdetails.stkcode'),
('reportcolumns', 'reportheaders', 'reportcolumns.reportid=reportheaders.reportid'),
('reportheaders', 'reportcolumns', 'reportheaders.reportid=reportcolumns.reportid'),
('salesanalysis', 'periods', 'salesanalysis.periodno=periods.periodno'),
('periods', 'salesanalysis', 'periods.periodno=salesanalysis.periodno'),
('salescatprod', 'stockmaster', 'salescatprod.stockid=stockmaster.stockid'),
('stockmaster', 'salescatprod', 'stockmaster.stockid=salescatprod.stockid'),
('salescatprod', 'salescat', 'salescatprod.salescatid=salescat.salescatid'),
('salescat', 'salescatprod', 'salescat.salescatid=salescatprod.salescatid'),
('salesorderdetails', 'salesorders', 'salesorderdetails.orderno=salesorders.orderno'),
('salesorders', 'salesorderdetails', 'salesorders.orderno=salesorderdetails.orderno'),
('salesorderdetails', 'stockmaster', 'salesorderdetails.stkcode=stockmaster.stockid'),
('stockmaster', 'salesorderdetails', 'stockmaster.stockid=salesorderdetails.stkcode'),
('salesorders', 'custbranch', 'salesorders.branchcode=custbranch.branchcode'),
('custbranch', 'salesorders', 'custbranch.branchcode=salesorders.branchcode'),
('salesorders', 'shippers', 'salesorders.debtorno=shippers.shipper_id'),
('shippers', 'salesorders', 'shippers.shipper_id=salesorders.debtorno'),
('salesorders', 'locations', 'salesorders.fromstkloc=locations.loccode'),
('locations', 'salesorders', 'locations.loccode=salesorders.fromstkloc'),
('securitygroups', 'securityroles', 'securitygroups.secroleid=securityroles.secroleid'),
('securityroles', 'securitygroups', 'securityroles.secroleid=securitygroups.secroleid'),
('securitygroups', 'securitytokens', 'securitygroups.tokenid=securitytokens.tokenid'),
('securitytokens', 'securitygroups', 'securitytokens.tokenid=securitygroups.tokenid'),
('shipmentcharges', 'shipments', 'shipmentcharges.shiptref=shipments.shiptref'),
('shipments', 'shipmentcharges', 'shipments.shiptref=shipmentcharges.shiptref'),
('shipmentcharges', 'systypes', 'shipmentcharges.transtype=systypes.typeid'),
('systypes', 'shipmentcharges', 'systypes.typeid=shipmentcharges.transtype'),
('shipments', 'suppliers', 'shipments.supplierid=suppliers.supplierid'),
('suppliers', 'shipments', 'suppliers.supplierid=shipments.supplierid'),
('stockcheckfreeze', 'stockmaster', 'stockcheckfreeze.stockid=stockmaster.stockid'),
('stockmaster', 'stockcheckfreeze', 'stockmaster.stockid=stockcheckfreeze.stockid'),
('stockcheckfreeze', 'locations', 'stockcheckfreeze.loccode=locations.loccode'),
('locations', 'stockcheckfreeze', 'locations.loccode=stockcheckfreeze.loccode'),
('stockcounts', 'stockmaster', 'stockcounts.stockid=stockmaster.stockid'),
('stockmaster', 'stockcounts', 'stockmaster.stockid=stockcounts.stockid'),
('stockcounts', 'locations', 'stockcounts.loccode=locations.loccode'),
('locations', 'stockcounts', 'locations.loccode=stockcounts.loccode'),
('stockmaster', 'stockcategory', 'stockmaster.categoryid=stockcategory.categoryid'),
('stockcategory', 'stockmaster', 'stockcategory.categoryid=stockmaster.categoryid'),
('stockmaster', 'taxcategories', 'stockmaster.taxcatid=taxcategories.taxcatid'),
('taxcategories', 'stockmaster', 'taxcategories.taxcatid=stockmaster.taxcatid'),
('stockmoves', 'stockmaster', 'stockmoves.stockid=stockmaster.stockid'),
('stockmaster', 'stockmoves', 'stockmaster.stockid=stockmoves.stockid'),
('stockmoves', 'systypes', 'stockmoves.type=systypes.typeid'),
('systypes', 'stockmoves', 'systypes.typeid=stockmoves.type'),
('stockmoves', 'locations', 'stockmoves.loccode=locations.loccode'),
('locations', 'stockmoves', 'locations.loccode=stockmoves.loccode'),
('stockmoves', 'periods', 'stockmoves.prd=periods.periodno'),
('periods', 'stockmoves', 'periods.periodno=stockmoves.prd'),
('stockmovestaxes', 'taxauthorities', 'stockmovestaxes.taxauthid=taxauthorities.taxid'),
('taxauthorities', 'stockmovestaxes', 'taxauthorities.taxid=stockmovestaxes.taxauthid'),
('stockserialitems', 'stockmaster', 'stockserialitems.stockid=stockmaster.stockid'),
('stockmaster', 'stockserialitems', 'stockmaster.stockid=stockserialitems.stockid'),
('stockserialitems', 'locations', 'stockserialitems.loccode=locations.loccode'),
('locations', 'stockserialitems', 'locations.loccode=stockserialitems.loccode'),
('stockserialmoves', 'stockmoves', 'stockserialmoves.stockmoveno=stockmoves.stkmoveno'),
('stockmoves', 'stockserialmoves', 'stockmoves.stkmoveno=stockserialmoves.stockmoveno'),
('stockserialmoves', 'stockserialitems', 'stockserialmoves.stockid=stockserialitems.stockid'),
('stockserialitems', 'stockserialmoves', 'stockserialitems.stockid=stockserialmoves.stockid'),
('suppallocs', 'supptrans', 'suppallocs.transid_allocfrom=supptrans.id'),
('supptrans', 'suppallocs', 'supptrans.id=suppallocs.transid_allocfrom'),
('suppallocs', 'supptrans', 'suppallocs.transid_allocto=supptrans.id'),
('supptrans', 'suppallocs', 'supptrans.id=suppallocs.transid_allocto'),
('suppliercontacts', 'suppliers', 'suppliercontacts.supplierid=suppliers.supplierid'),
('suppliers', 'suppliercontacts', 'suppliers.supplierid=suppliercontacts.supplierid'),
('suppliers', 'currencies', 'suppliers.currcode=currencies.currabrev'),
('currencies', 'suppliers', 'currencies.currabrev=suppliers.currcode'),
('suppliers', 'paymentterms', 'suppliers.paymentterms=paymentterms.termsindicator'),
('paymentterms', 'suppliers', 'paymentterms.termsindicator=suppliers.paymentterms'),
('suppliers', 'taxgroups', 'suppliers.taxgroupid=taxgroups.taxgroupid'),
('taxgroups', 'suppliers', 'taxgroups.taxgroupid=suppliers.taxgroupid'),
('supptrans', 'systypes', 'supptrans.type=systypes.typeid'),
('systypes', 'supptrans', 'systypes.typeid=supptrans.type'),
('supptrans', 'suppliers', 'supptrans.supplierno=suppliers.supplierid'),
('suppliers', 'supptrans', 'suppliers.supplierid=supptrans.supplierno'),
('supptranstaxes', 'taxauthorities', 'supptranstaxes.taxauthid=taxauthorities.taxid'),
('taxauthorities', 'supptranstaxes', 'taxauthorities.taxid=supptranstaxes.taxauthid'),
('supptranstaxes', 'supptrans', 'supptranstaxes.supptransid=supptrans.id'),
('supptrans', 'supptranstaxes', 'supptrans.id=supptranstaxes.supptransid'),
('taxauthorities', 'chartmaster', 'taxauthorities.taxglcode=chartmaster.accountcode'),
('chartmaster', 'taxauthorities', 'chartmaster.accountcode=taxauthorities.taxglcode'),
('taxauthorities', 'chartmaster', 'taxauthorities.purchtaxglaccount=chartmaster.accountcode'),
('chartmaster', 'taxauthorities', 'chartmaster.accountcode=taxauthorities.purchtaxglaccount'),
('taxauthrates', 'taxauthorities', 'taxauthrates.taxauthority=taxauthorities.taxid'),
('taxauthorities', 'taxauthrates', 'taxauthorities.taxid=taxauthrates.taxauthority'),
('taxauthrates', 'taxcategories', 'taxauthrates.taxcatid=taxcategories.taxcatid'),
('taxcategories', 'taxauthrates', 'taxcategories.taxcatid=taxauthrates.taxcatid'),
('taxauthrates', 'taxprovinces', 'taxauthrates.dispatchtaxprovince=taxprovinces.taxprovinceid'),
('taxprovinces', 'taxauthrates', 'taxprovinces.taxprovinceid=taxauthrates.dispatchtaxprovince'),
('taxgrouptaxes', 'taxgroups', 'taxgrouptaxes.taxgroupid=taxgroups.taxgroupid'),
('taxgroups', 'taxgrouptaxes', 'taxgroups.taxgroupid=taxgrouptaxes.taxgroupid'),
('taxgrouptaxes', 'taxauthorities', 'taxgrouptaxes.taxauthid=taxauthorities.taxid'),
('taxauthorities', 'taxgrouptaxes', 'taxauthorities.taxid=taxgrouptaxes.taxauthid'),
('workcentres', 'locations', 'workcentres.location=locations.loccode'),
('locations', 'workcentres', 'locations.loccode=workcentres.location'),
('worksorders', 'locations', 'worksorders.loccode=locations.loccode'),
('locations', 'worksorders', 'locations.loccode=worksorders.loccode'),
('worksorders', 'stockmaster', 'worksorders.stockid=stockmaster.stockid'),
('stockmaster', 'worksorders', 'stockmaster.stockid=worksorders.stockid'),
('www_users', 'locations', 'www_users.defaultlocation=locations.loccode'),
('locations', 'www_users', 'locations.loccode=www_users.defaultlocation'),
('accountgroups', 'accountsection', 'accountgroups.sectioninaccounts=accountsection.sectionid'),
('accountsection', 'accountgroups', 'accountsection.sectionid=accountgroups.sectioninaccounts'),
('bankaccounts', 'chartmaster', 'bankaccounts.accountcode=chartmaster.accountcode'),
('chartmaster', 'bankaccounts', 'chartmaster.accountcode=bankaccounts.accountcode'),
('banktrans', 'systypes', 'banktrans.type=systypes.typeid'),
('systypes', 'banktrans', 'systypes.typeid=banktrans.type'),
('banktrans', 'bankaccounts', 'banktrans.bankact=bankaccounts.accountcode'),
('bankaccounts', 'banktrans', 'bankaccounts.accountcode=banktrans.bankact'),
('bom', 'stockmaster', 'bom.parent=stockmaster.stockid'),
('stockmaster', 'bom', 'stockmaster.stockid=bom.parent'),
('bom', 'stockmaster', 'bom.component=stockmaster.stockid'),
('stockmaster', 'bom', 'stockmaster.stockid=bom.component'),
('bom', 'workcentres', 'bom.workcentreadded=workcentres.code'),
('workcentres', 'bom', 'workcentres.code=bom.workcentreadded'),
('bom', 'locations', 'bom.loccode=locations.loccode'),
('locations', 'bom', 'locations.loccode=bom.loccode'),
('buckets', 'workcentres', 'buckets.workcentre=workcentres.code'),
('workcentres', 'buckets', 'workcentres.code=buckets.workcentre'),
('chartdetails', 'chartmaster', 'chartdetails.accountcode=chartmaster.accountcode'),
('chartmaster', 'chartdetails', 'chartmaster.accountcode=chartdetails.accountcode'),
('chartdetails', 'periods', 'chartdetails.period=periods.periodno'),
('periods', 'chartdetails', 'periods.periodno=chartdetails.period'),
('chartmaster', 'accountgroups', 'chartmaster.group_=accountgroups.groupname'),
('accountgroups', 'chartmaster', 'accountgroups.groupname=chartmaster.group_'),
('contractbom', 'workcentres', 'contractbom.workcentreadded=workcentres.code'),
('workcentres', 'contractbom', 'workcentres.code=contractbom.workcentreadded'),
('contractbom', 'locations', 'contractbom.loccode=locations.loccode'),
('locations', 'contractbom', 'locations.loccode=contractbom.loccode'),
('contractbom', 'stockmaster', 'contractbom.component=stockmaster.stockid'),
('stockmaster', 'contractbom', 'stockmaster.stockid=contractbom.component'),
('contractreqts', 'contracts', 'contractreqts.contract=contracts.contractref'),
('contracts', 'contractreqts', 'contracts.contractref=contractreqts.contract'),
('contracts', 'custbranch', 'contracts.debtorno=custbranch.debtorno'),
('custbranch', 'contracts', 'custbranch.debtorno=contracts.debtorno'),
('contracts', 'stockcategory', 'contracts.branchcode=stockcategory.categoryid'),
('stockcategory', 'contracts', 'stockcategory.categoryid=contracts.branchcode'),
('contracts', 'salestypes', 'contracts.typeabbrev=salestypes.typeabbrev'),
('salestypes', 'contracts', 'salestypes.typeabbrev=contracts.typeabbrev'),
('custallocns', 'debtortrans', 'custallocns.transid_allocfrom=debtortrans.id'),
('debtortrans', 'custallocns', 'debtortrans.id=custallocns.transid_allocfrom'),
('custallocns', 'debtortrans', 'custallocns.transid_allocto=debtortrans.id'),
('debtortrans', 'custallocns', 'debtortrans.id=custallocns.transid_allocto'),
('custbranch', 'debtorsmaster', 'custbranch.debtorno=debtorsmaster.debtorno'),
('debtorsmaster', 'custbranch', 'debtorsmaster.debtorno=custbranch.debtorno'),
('custbranch', 'areas', 'custbranch.area=areas.areacode'),
('areas', 'custbranch', 'areas.areacode=custbranch.area'),
('custbranch', 'salesman', 'custbranch.salesman=salesman.salesmancode'),
('salesman', 'custbranch', 'salesman.salesmancode=custbranch.salesman'),
('custbranch', 'locations', 'custbranch.defaultlocation=locations.loccode'),
('locations', 'custbranch', 'locations.loccode=custbranch.defaultlocation'),
('custbranch', 'shippers', 'custbranch.defaultshipvia=shippers.shipper_id'),
('shippers', 'custbranch', 'shippers.shipper_id=custbranch.defaultshipvia'),
('debtorsmaster', 'holdreasons', 'debtorsmaster.holdreason=holdreasons.reasoncode'),
('holdreasons', 'debtorsmaster', 'holdreasons.reasoncode=debtorsmaster.holdreason'),
('debtorsmaster', 'currencies', 'debtorsmaster.currcode=currencies.currabrev'),
('currencies', 'debtorsmaster', 'currencies.currabrev=debtorsmaster.currcode'),
('debtorsmaster', 'paymentterms', 'debtorsmaster.paymentterms=paymentterms.termsindicator'),
('paymentterms', 'debtorsmaster', 'paymentterms.termsindicator=debtorsmaster.paymentterms'),
('debtorsmaster', 'salestypes', 'debtorsmaster.salestype=salestypes.typeabbrev'),
('salestypes', 'debtorsmaster', 'salestypes.typeabbrev=debtorsmaster.salestype'),
('debtortrans', 'custbranch', 'debtortrans.debtorno=custbranch.debtorno'),
('custbranch', 'debtortrans', 'custbranch.debtorno=debtortrans.debtorno'),
('debtortrans', 'systypes', 'debtortrans.type=systypes.typeid'),
('systypes', 'debtortrans', 'systypes.typeid=debtortrans.type'),
('debtortrans', 'periods', 'debtortrans.prd=periods.periodno'),
('periods', 'debtortrans', 'periods.periodno=debtortrans.prd'),
('debtortranstaxes', 'taxauthorities', 'debtortranstaxes.taxauthid=taxauthorities.taxid'),
('taxauthorities', 'debtortranstaxes', 'taxauthorities.taxid=debtortranstaxes.taxauthid'),
('debtortranstaxes', 'debtortrans', 'debtortranstaxes.debtortransid=debtortrans.id'),
('debtortrans', 'debtortranstaxes', 'debtortrans.id=debtortranstaxes.debtortransid'),
('discountmatrix', 'salestypes', 'discountmatrix.salestype=salestypes.typeabbrev'),
('salestypes', 'discountmatrix', 'salestypes.typeabbrev=discountmatrix.salestype'),
('freightcosts', 'locations', 'freightcosts.locationfrom=locations.loccode'),
('locations', 'freightcosts', 'locations.loccode=freightcosts.locationfrom'),
('freightcosts', 'shippers', 'freightcosts.shipperid=shippers.shipper_id'),
('shippers', 'freightcosts', 'shippers.shipper_id=freightcosts.shipperid'),
('gltrans', 'chartmaster', 'gltrans.account=chartmaster.accountcode'),
('chartmaster', 'gltrans', 'chartmaster.accountcode=gltrans.account'),
('gltrans', 'systypes', 'gltrans.type=systypes.typeid'),
('systypes', 'gltrans', 'systypes.typeid=gltrans.type'),
('gltrans', 'periods', 'gltrans.periodno=periods.periodno'),
('periods', 'gltrans', 'periods.periodno=gltrans.periodno'),
('grns', 'suppliers', 'grns.supplierid=suppliers.supplierid'),
('suppliers', 'grns', 'suppliers.supplierid=grns.supplierid'),
('grns', 'purchorderdetails', 'grns.podetailitem=purchorderdetails.podetailitem'),
('purchorderdetails', 'grns', 'purchorderdetails.podetailitem=grns.podetailitem'),
('locations', 'taxprovinces', 'locations.taxprovinceid=taxprovinces.taxprovinceid'),
('taxprovinces', 'locations', 'taxprovinces.taxprovinceid=locations.taxprovinceid'),
('locstock', 'locations', 'locstock.loccode=locations.loccode'),
('locations', 'locstock', 'locations.loccode=locstock.loccode'),
('locstock', 'stockmaster', 'locstock.stockid=stockmaster.stockid'),
('stockmaster', 'locstock', 'stockmaster.stockid=locstock.stockid'),
('loctransfers', 'locations', 'loctransfers.shiploc=locations.loccode'),
('locations', 'loctransfers', 'locations.loccode=loctransfers.shiploc'),
('loctransfers', 'locations', 'loctransfers.recloc=locations.loccode'),
('locations', 'loctransfers', 'locations.loccode=loctransfers.recloc'),
('loctransfers', 'stockmaster', 'loctransfers.stockid=stockmaster.stockid'),
('stockmaster', 'loctransfers', 'stockmaster.stockid=loctransfers.stockid'),
('orderdeliverydifferencesl', 'stockmaster', 'orderdeliverydifferenceslog.stockid=stockmaster.stockid'),
('stockmaster', 'orderdeliverydifferencesl', 'stockmaster.stockid=orderdeliverydifferenceslog.stockid'),
('orderdeliverydifferencesl', 'custbranch', 'orderdeliverydifferenceslog.debtorno=custbranch.debtorno'),
('custbranch', 'orderdeliverydifferencesl', 'custbranch.debtorno=orderdeliverydifferenceslog.debtorno'),
('orderdeliverydifferencesl', 'salesorders', 'orderdeliverydifferenceslog.branchcode=salesorders.orderno'),
('salesorders', 'orderdeliverydifferencesl', 'salesorders.orderno=orderdeliverydifferenceslog.branchcode'),
('prices', 'stockmaster', 'prices.stockid=stockmaster.stockid'),
('stockmaster', 'prices', 'stockmaster.stockid=prices.stockid'),
('prices', 'currencies', 'prices.currabrev=currencies.currabrev'),
('currencies', 'prices', 'currencies.currabrev=prices.currabrev'),
('prices', 'salestypes', 'prices.typeabbrev=salestypes.typeabbrev'),
('salestypes', 'prices', 'salestypes.typeabbrev=prices.typeabbrev'),
('purchdata', 'stockmaster', 'purchdata.stockid=stockmaster.stockid'),
('stockmaster', 'purchdata', 'stockmaster.stockid=purchdata.stockid'),
('purchdata', 'suppliers', 'purchdata.supplierno=suppliers.supplierid'),
('suppliers', 'purchdata', 'suppliers.supplierid=purchdata.supplierno'),
('purchorderdetails', 'purchorders', 'purchorderdetails.orderno=purchorders.orderno'),
('purchorders', 'purchorderdetails', 'purchorders.orderno=purchorderdetails.orderno'),
('purchorders', 'suppliers', 'purchorders.supplierno=suppliers.supplierid'),
('suppliers', 'purchorders', 'suppliers.supplierid=purchorders.supplierno'),
('purchorders', 'locations', 'purchorders.intostocklocation=locations.loccode'),
('locations', 'purchorders', 'locations.loccode=purchorders.intostocklocation'),
('recurringsalesorders', 'custbranch', 'recurringsalesorders.branchcode=custbranch.branchcode'),
('custbranch', 'recurringsalesorders', 'custbranch.branchcode=recurringsalesorders.branchcode'),
('recurrsalesorderdetails', 'recurringsalesorders', 'recurrsalesorderdetails.recurrorderno=recurringsalesorders.recurrorderno'),
('recurringsalesorders', 'recurrsalesorderdetails', 'recurringsalesorders.recurrorderno=recurrsalesorderdetails.recurrorderno'),
('recurrsalesorderdetails', 'stockmaster', 'recurrsalesorderdetails.stkcode=stockmaster.stockid'),
('stockmaster', 'recurrsalesorderdetails', 'stockmaster.stockid=recurrsalesorderdetails.stkcode'),
('reportcolumns', 'reportheaders', 'reportcolumns.reportid=reportheaders.reportid'),
('reportheaders', 'reportcolumns', 'reportheaders.reportid=reportcolumns.reportid'),
('salesanalysis', 'periods', 'salesanalysis.periodno=periods.periodno');
INSERT INTO `reportlinks` (`table1`, `table2`, `equation`) VALUES
('periods', 'salesanalysis', 'periods.periodno=salesanalysis.periodno'),
('salescatprod', 'stockmaster', 'salescatprod.stockid=stockmaster.stockid'),
('stockmaster', 'salescatprod', 'stockmaster.stockid=salescatprod.stockid'),
('salescatprod', 'salescat', 'salescatprod.salescatid=salescat.salescatid'),
('salescat', 'salescatprod', 'salescat.salescatid=salescatprod.salescatid'),
('salesorderdetails', 'salesorders', 'salesorderdetails.orderno=salesorders.orderno'),
('salesorders', 'salesorderdetails', 'salesorders.orderno=salesorderdetails.orderno'),
('salesorderdetails', 'stockmaster', 'salesorderdetails.stkcode=stockmaster.stockid'),
('stockmaster', 'salesorderdetails', 'stockmaster.stockid=salesorderdetails.stkcode'),
('salesorders', 'custbranch', 'salesorders.branchcode=custbranch.branchcode'),
('custbranch', 'salesorders', 'custbranch.branchcode=salesorders.branchcode'),
('salesorders', 'shippers', 'salesorders.debtorno=shippers.shipper_id'),
('shippers', 'salesorders', 'shippers.shipper_id=salesorders.debtorno'),
('salesorders', 'locations', 'salesorders.fromstkloc=locations.loccode'),
('locations', 'salesorders', 'locations.loccode=salesorders.fromstkloc'),
('securitygroups', 'securityroles', 'securitygroups.secroleid=securityroles.secroleid'),
('securityroles', 'securitygroups', 'securityroles.secroleid=securitygroups.secroleid'),
('securitygroups', 'securitytokens', 'securitygroups.tokenid=securitytokens.tokenid'),
('securitytokens', 'securitygroups', 'securitytokens.tokenid=securitygroups.tokenid'),
('shipmentcharges', 'shipments', 'shipmentcharges.shiptref=shipments.shiptref'),
('shipments', 'shipmentcharges', 'shipments.shiptref=shipmentcharges.shiptref'),
('shipmentcharges', 'systypes', 'shipmentcharges.transtype=systypes.typeid'),
('systypes', 'shipmentcharges', 'systypes.typeid=shipmentcharges.transtype'),
('shipments', 'suppliers', 'shipments.supplierid=suppliers.supplierid'),
('suppliers', 'shipments', 'suppliers.supplierid=shipments.supplierid'),
('stockcheckfreeze', 'stockmaster', 'stockcheckfreeze.stockid=stockmaster.stockid'),
('stockmaster', 'stockcheckfreeze', 'stockmaster.stockid=stockcheckfreeze.stockid'),
('stockcheckfreeze', 'locations', 'stockcheckfreeze.loccode=locations.loccode'),
('locations', 'stockcheckfreeze', 'locations.loccode=stockcheckfreeze.loccode'),
('stockcounts', 'stockmaster', 'stockcounts.stockid=stockmaster.stockid'),
('stockmaster', 'stockcounts', 'stockmaster.stockid=stockcounts.stockid'),
('stockcounts', 'locations', 'stockcounts.loccode=locations.loccode'),
('locations', 'stockcounts', 'locations.loccode=stockcounts.loccode'),
('stockmaster', 'stockcategory', 'stockmaster.categoryid=stockcategory.categoryid'),
('stockcategory', 'stockmaster', 'stockcategory.categoryid=stockmaster.categoryid'),
('stockmaster', 'taxcategories', 'stockmaster.taxcatid=taxcategories.taxcatid'),
('taxcategories', 'stockmaster', 'taxcategories.taxcatid=stockmaster.taxcatid'),
('stockmoves', 'stockmaster', 'stockmoves.stockid=stockmaster.stockid'),
('stockmaster', 'stockmoves', 'stockmaster.stockid=stockmoves.stockid'),
('stockmoves', 'systypes', 'stockmoves.type=systypes.typeid'),
('systypes', 'stockmoves', 'systypes.typeid=stockmoves.type'),
('stockmoves', 'locations', 'stockmoves.loccode=locations.loccode'),
('locations', 'stockmoves', 'locations.loccode=stockmoves.loccode'),
('stockmoves', 'periods', 'stockmoves.prd=periods.periodno'),
('periods', 'stockmoves', 'periods.periodno=stockmoves.prd'),
('stockmovestaxes', 'taxauthorities', 'stockmovestaxes.taxauthid=taxauthorities.taxid'),
('taxauthorities', 'stockmovestaxes', 'taxauthorities.taxid=stockmovestaxes.taxauthid'),
('stockserialitems', 'stockmaster', 'stockserialitems.stockid=stockmaster.stockid'),
('stockmaster', 'stockserialitems', 'stockmaster.stockid=stockserialitems.stockid'),
('stockserialitems', 'locations', 'stockserialitems.loccode=locations.loccode'),
('locations', 'stockserialitems', 'locations.loccode=stockserialitems.loccode'),
('stockserialmoves', 'stockmoves', 'stockserialmoves.stockmoveno=stockmoves.stkmoveno'),
('stockmoves', 'stockserialmoves', 'stockmoves.stkmoveno=stockserialmoves.stockmoveno'),
('stockserialmoves', 'stockserialitems', 'stockserialmoves.stockid=stockserialitems.stockid'),
('stockserialitems', 'stockserialmoves', 'stockserialitems.stockid=stockserialmoves.stockid'),
('suppallocs', 'supptrans', 'suppallocs.transid_allocfrom=supptrans.id'),
('supptrans', 'suppallocs', 'supptrans.id=suppallocs.transid_allocfrom'),
('suppallocs', 'supptrans', 'suppallocs.transid_allocto=supptrans.id'),
('supptrans', 'suppallocs', 'supptrans.id=suppallocs.transid_allocto'),
('suppliercontacts', 'suppliers', 'suppliercontacts.supplierid=suppliers.supplierid'),
('suppliers', 'suppliercontacts', 'suppliers.supplierid=suppliercontacts.supplierid'),
('suppliers', 'currencies', 'suppliers.currcode=currencies.currabrev'),
('currencies', 'suppliers', 'currencies.currabrev=suppliers.currcode'),
('suppliers', 'paymentterms', 'suppliers.paymentterms=paymentterms.termsindicator'),
('paymentterms', 'suppliers', 'paymentterms.termsindicator=suppliers.paymentterms'),
('suppliers', 'taxgroups', 'suppliers.taxgroupid=taxgroups.taxgroupid'),
('taxgroups', 'suppliers', 'taxgroups.taxgroupid=suppliers.taxgroupid'),
('supptrans', 'systypes', 'supptrans.type=systypes.typeid'),
('systypes', 'supptrans', 'systypes.typeid=supptrans.type'),
('supptrans', 'suppliers', 'supptrans.supplierno=suppliers.supplierid'),
('suppliers', 'supptrans', 'suppliers.supplierid=supptrans.supplierno'),
('supptranstaxes', 'taxauthorities', 'supptranstaxes.taxauthid=taxauthorities.taxid'),
('taxauthorities', 'supptranstaxes', 'taxauthorities.taxid=supptranstaxes.taxauthid'),
('supptranstaxes', 'supptrans', 'supptranstaxes.supptransid=supptrans.id'),
('supptrans', 'supptranstaxes', 'supptrans.id=supptranstaxes.supptransid'),
('taxauthorities', 'chartmaster', 'taxauthorities.taxglcode=chartmaster.accountcode'),
('chartmaster', 'taxauthorities', 'chartmaster.accountcode=taxauthorities.taxglcode'),
('taxauthorities', 'chartmaster', 'taxauthorities.purchtaxglaccount=chartmaster.accountcode'),
('chartmaster', 'taxauthorities', 'chartmaster.accountcode=taxauthorities.purchtaxglaccount'),
('taxauthrates', 'taxauthorities', 'taxauthrates.taxauthority=taxauthorities.taxid'),
('taxauthorities', 'taxauthrates', 'taxauthorities.taxid=taxauthrates.taxauthority'),
('taxauthrates', 'taxcategories', 'taxauthrates.taxcatid=taxcategories.taxcatid'),
('taxcategories', 'taxauthrates', 'taxcategories.taxcatid=taxauthrates.taxcatid'),
('taxauthrates', 'taxprovinces', 'taxauthrates.dispatchtaxprovince=taxprovinces.taxprovinceid'),
('taxprovinces', 'taxauthrates', 'taxprovinces.taxprovinceid=taxauthrates.dispatchtaxprovince'),
('taxgrouptaxes', 'taxgroups', 'taxgrouptaxes.taxgroupid=taxgroups.taxgroupid'),
('taxgroups', 'taxgrouptaxes', 'taxgroups.taxgroupid=taxgrouptaxes.taxgroupid'),
('taxgrouptaxes', 'taxauthorities', 'taxgrouptaxes.taxauthid=taxauthorities.taxid'),
('taxauthorities', 'taxgrouptaxes', 'taxauthorities.taxid=taxgrouptaxes.taxauthid'),
('workcentres', 'locations', 'workcentres.location=locations.loccode'),
('locations', 'workcentres', 'locations.loccode=workcentres.location'),
('worksorders', 'locations', 'worksorders.loccode=locations.loccode'),
('locations', 'worksorders', 'locations.loccode=worksorders.loccode'),
('worksorders', 'stockmaster', 'worksorders.stockid=stockmaster.stockid'),
('stockmaster', 'worksorders', 'stockmaster.stockid=worksorders.stockid'),
('www_users', 'locations', 'www_users.defaultlocation=locations.loccode'),
('locations', 'www_users', 'locations.loccode=www_users.defaultlocation');

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `id` int(5) NOT NULL,
  `reportname` varchar(30) NOT NULL DEFAULT '',
  `reporttype` char(3) NOT NULL DEFAULT 'rpt',
  `groupname` varchar(9) NOT NULL DEFAULT 'misc',
  `defaultreport` enum('1','0') NOT NULL DEFAULT '0',
  `papersize` varchar(15) NOT NULL DEFAULT 'A4,210,297',
  `paperorientation` enum('P','L') NOT NULL DEFAULT 'P',
  `margintop` int(3) NOT NULL DEFAULT '10',
  `marginbottom` int(3) NOT NULL DEFAULT '10',
  `marginleft` int(3) NOT NULL DEFAULT '10',
  `marginright` int(3) NOT NULL DEFAULT '10',
  `coynamefont` varchar(20) NOT NULL DEFAULT 'Helvetica',
  `coynamefontsize` int(3) NOT NULL DEFAULT '12',
  `coynamefontcolor` varchar(11) NOT NULL DEFAULT '0,0,0',
  `coynamealign` enum('L','C','R') NOT NULL DEFAULT 'C',
  `coynameshow` enum('1','0') NOT NULL DEFAULT '1',
  `title1desc` varchar(50) NOT NULL DEFAULT '%reportname%',
  `title1font` varchar(20) NOT NULL DEFAULT 'Helvetica',
  `title1fontsize` int(3) NOT NULL DEFAULT '10',
  `title1fontcolor` varchar(11) NOT NULL DEFAULT '0,0,0',
  `title1fontalign` enum('L','C','R') NOT NULL DEFAULT 'C',
  `title1show` enum('1','0') NOT NULL DEFAULT '1',
  `title2desc` varchar(50) NOT NULL DEFAULT 'Report Generated %date%',
  `title2font` varchar(20) NOT NULL DEFAULT 'Helvetica',
  `title2fontsize` int(3) NOT NULL DEFAULT '10',
  `title2fontcolor` varchar(11) NOT NULL DEFAULT '0,0,0',
  `title2fontalign` enum('L','C','R') NOT NULL DEFAULT 'C',
  `title2show` enum('1','0') NOT NULL DEFAULT '1',
  `filterfont` varchar(10) NOT NULL DEFAULT 'Helvetica',
  `filterfontsize` int(3) NOT NULL DEFAULT '8',
  `filterfontcolor` varchar(11) NOT NULL DEFAULT '0,0,0',
  `filterfontalign` enum('L','C','R') NOT NULL DEFAULT 'L',
  `datafont` varchar(10) NOT NULL DEFAULT 'Helvetica',
  `datafontsize` int(3) NOT NULL DEFAULT '10',
  `datafontcolor` varchar(10) NOT NULL DEFAULT 'black',
  `datafontalign` enum('L','C','R') NOT NULL DEFAULT 'L',
  `totalsfont` varchar(10) NOT NULL DEFAULT 'Helvetica',
  `totalsfontsize` int(3) NOT NULL DEFAULT '10',
  `totalsfontcolor` varchar(11) NOT NULL DEFAULT '0,0,0',
  `totalsfontalign` enum('L','C','R') NOT NULL DEFAULT 'L',
  `col1width` int(3) NOT NULL DEFAULT '25',
  `col2width` int(3) NOT NULL DEFAULT '25',
  `col3width` int(3) NOT NULL DEFAULT '25',
  `col4width` int(3) NOT NULL DEFAULT '25',
  `col5width` int(3) NOT NULL DEFAULT '25',
  `col6width` int(3) NOT NULL DEFAULT '25',
  `col7width` int(3) NOT NULL DEFAULT '25',
  `col8width` int(3) NOT NULL DEFAULT '25',
  `col9width` int(3) NOT NULL DEFAULT '25',
  `col10width` int(3) NOT NULL DEFAULT '25',
  `col11width` int(3) NOT NULL DEFAULT '25',
  `col12width` int(3) NOT NULL DEFAULT '25',
  `col13width` int(3) NOT NULL DEFAULT '25',
  `col14width` int(3) NOT NULL DEFAULT '25',
  `col15width` int(3) NOT NULL DEFAULT '25',
  `col16width` int(3) NOT NULL DEFAULT '25',
  `col17width` int(3) NOT NULL DEFAULT '25',
  `col18width` int(3) NOT NULL DEFAULT '25',
  `col19width` int(3) NOT NULL DEFAULT '25',
  `col20width` int(3) NOT NULL DEFAULT '25',
  `table1` varchar(25) NOT NULL DEFAULT '',
  `table2` varchar(25) DEFAULT NULL,
  `table2criteria` varchar(75) DEFAULT NULL,
  `table3` varchar(25) DEFAULT NULL,
  `table3criteria` varchar(75) DEFAULT NULL,
  `table4` varchar(25) DEFAULT NULL,
  `table4criteria` varchar(75) DEFAULT NULL,
  `table5` varchar(25) DEFAULT NULL,
  `table5criteria` varchar(75) DEFAULT NULL,
  `table6` varchar(25) DEFAULT NULL,
  `table6criteria` varchar(75) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `salesanalysis`
--

CREATE TABLE `salesanalysis` (
  `typeabbrev` char(2) NOT NULL DEFAULT '',
  `periodno` smallint(6) NOT NULL DEFAULT '0',
  `amt` double NOT NULL DEFAULT '0',
  `cost` double NOT NULL DEFAULT '0',
  `cust` varchar(10) NOT NULL DEFAULT '',
  `custbranch` varchar(10) NOT NULL DEFAULT '',
  `qty` double NOT NULL DEFAULT '0',
  `disc` double NOT NULL DEFAULT '0',
  `stockid` varchar(20) NOT NULL DEFAULT '',
  `area` varchar(3) NOT NULL,
  `budgetoractual` tinyint(1) NOT NULL DEFAULT '0',
  `salesperson` varchar(4) NOT NULL DEFAULT '',
  `stkcategory` varchar(6) NOT NULL DEFAULT '',
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `salescat`
--

CREATE TABLE `salescat` (
  `salescatid` tinyint(4) NOT NULL,
  `parentcatid` tinyint(4) DEFAULT NULL,
  `salescatname` varchar(50) DEFAULT NULL,
  `active` int(11) NOT NULL DEFAULT '1' COMMENT '1 if active 0 if inactive'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `salescatprod`
--

CREATE TABLE `salescatprod` (
  `salescatid` tinyint(4) NOT NULL DEFAULT '0',
  `stockid` varchar(20) NOT NULL DEFAULT '',
  `manufacturers_id` int(11) NOT NULL,
  `featured` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `salescattranslations`
--

CREATE TABLE `salescattranslations` (
  `salescatid` tinyint(4) NOT NULL DEFAULT '0',
  `language_id` varchar(10) NOT NULL DEFAULT 'en_GB.utf8',
  `salescattranslation` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `salesglpostings`
--

CREATE TABLE `salesglpostings` (
  `id` int(11) NOT NULL,
  `area` varchar(3) NOT NULL,
  `stkcat` varchar(6) NOT NULL DEFAULT '',
  `discountglcode` varchar(20) NOT NULL DEFAULT '0',
  `salesglcode` varchar(20) NOT NULL DEFAULT '0',
  `salestype` char(2) NOT NULL DEFAULT 'AN'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `salesglpostings`
--

INSERT INTO `salesglpostings` (`id`, `area`, `stkcat`, `discountglcode`, `salesglcode`, `salestype`) VALUES
(1, '1', '3', '4900', '4500', '3'),
(2, '1', '2', '1', '1', 'De');

-- --------------------------------------------------------

--
-- Table structure for table `salesman`
--

CREATE TABLE `salesman` (
  `salesmancode` varchar(4) NOT NULL DEFAULT '',
  `salesmanname` char(30) NOT NULL DEFAULT '',
  `smantel` char(20) NOT NULL DEFAULT '',
  `smanfax` char(20) NOT NULL DEFAULT '',
  `commissionrate1` double NOT NULL DEFAULT '0',
  `breakpoint` decimal(10,0) NOT NULL DEFAULT '0',
  `commissionrate2` double NOT NULL DEFAULT '0',
  `current` tinyint(4) NOT NULL COMMENT 'Salesman current (1) or not (0)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `salesman`
--

INSERT INTO `salesman` (`salesmancode`, `salesmanname`, `smantel`, `smanfax`, `commissionrate1`, `breakpoint`, `commissionrate2`, `current`) VALUES
('1', 'Sales Person1', '2423423', '', 0, '100000', 5, 1),
('2', 'Sales Person2', 'asdfads', '', 5, '50000', 10, 1);

-- --------------------------------------------------------

--
-- Table structure for table `salesorderdetails`
--

CREATE TABLE `salesorderdetails` (
  `orderlineno` int(11) NOT NULL DEFAULT '0',
  `orderno` int(11) NOT NULL DEFAULT '0',
  `stkcode` varchar(20) NOT NULL DEFAULT '',
  `qtyinvoiced` double NOT NULL DEFAULT '0',
  `unitprice` double NOT NULL DEFAULT '0',
  `quantity` double NOT NULL DEFAULT '0',
  `estimate` tinyint(4) NOT NULL DEFAULT '0',
  `discountpercent` double NOT NULL DEFAULT '0',
  `actualdispatchdate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `completed` tinyint(1) NOT NULL DEFAULT '0',
  `narrative` text,
  `itemdue` date DEFAULT NULL COMMENT 'Due date for line item.  Some customers require \r\nacknowledgements with due dates by line item',
  `poline` varchar(10) DEFAULT NULL COMMENT 'Some Customers require acknowledgements with a PO line number for each sales line'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `salesorderdetails`
--

INSERT INTO `salesorderdetails` (`orderlineno`, `orderno`, `stkcode`, `qtyinvoiced`, `unitprice`, `quantity`, `estimate`, `discountpercent`, `actualdispatchdate`, `completed`, `narrative`, `itemdue`, `poline`) VALUES
(0, 1, 'S9', 0, 50000, 10, 0, 0.12, '0000-00-00 00:00:00', 0, 'asdfhasdjfjkdas234232', '2018-09-04', '12312asdsd');

-- --------------------------------------------------------

--
-- Table structure for table `salesorders`
--

CREATE TABLE `salesorders` (
  `orderno` int(11) NOT NULL,
  `debtorno` varchar(10) NOT NULL DEFAULT '',
  `branchcode` varchar(10) NOT NULL DEFAULT '',
  `customerref` varchar(50) NOT NULL DEFAULT '',
  `buyername` varchar(50) DEFAULT NULL,
  `comments` longblob,
  `orddate` date NOT NULL DEFAULT '0000-00-00',
  `ordertype` char(2) NOT NULL DEFAULT '',
  `shipvia` int(11) NOT NULL DEFAULT '0',
  `deladd1` varchar(40) NOT NULL DEFAULT '',
  `deladd2` varchar(40) NOT NULL DEFAULT '',
  `deladd3` varchar(40) NOT NULL DEFAULT '',
  `deladd4` varchar(40) DEFAULT NULL,
  `deladd5` varchar(20) NOT NULL DEFAULT '',
  `deladd6` varchar(15) NOT NULL DEFAULT '',
  `contactphone` varchar(25) DEFAULT NULL,
  `contactemail` varchar(40) DEFAULT NULL,
  `deliverto` varchar(40) NOT NULL DEFAULT '',
  `deliverblind` tinyint(1) DEFAULT '1',
  `freightcost` double NOT NULL DEFAULT '0',
  `fromstkloc` varchar(5) NOT NULL DEFAULT '',
  `deliverydate` date NOT NULL DEFAULT '0000-00-00',
  `confirmeddate` date NOT NULL DEFAULT '0000-00-00',
  `printedpackingslip` tinyint(4) NOT NULL DEFAULT '0',
  `datepackingslipprinted` date NOT NULL DEFAULT '0000-00-00',
  `quotation` tinyint(4) NOT NULL DEFAULT '0',
  `quotedate` date NOT NULL DEFAULT '0000-00-00',
  `poplaced` tinyint(4) NOT NULL DEFAULT '0',
  `salesperson` varchar(4) NOT NULL,
  `internalcomment` blob
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `salesorders`
--

INSERT INTO `salesorders` (`orderno`, `debtorno`, `branchcode`, `customerref`, `buyername`, `comments`, `orddate`, `ordertype`, `shipvia`, `deladd1`, `deladd2`, `deladd3`, `deladd4`, `deladd5`, `deladd6`, `contactphone`, `contactemail`, `deliverto`, `deliverblind`, `freightcost`, `fromstkloc`, `deliverydate`, `confirmeddate`, `printedpackingslip`, `datepackingslipprinted`, `quotation`, `quotedate`, `poplaced`, `salesperson`, `internalcomment`) VALUES
(1, '1', '1', 'asdfasdf', NULL, 0x7361646661647366617364666173646661647366736461666173, '2018-09-04', 'De', 1, 'asdffa', 'sadfasdfa', '', '', '23423432', 'India', '3425342', 'admin@admin.com', 'AppleS', 1, 0, 'BAN', '2018-09-04', '2018-09-04', 0, '0000-00-00', 0, '2018-09-04', 0, '1', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `salestypes`
--

CREATE TABLE `salestypes` (
  `typeabbrev` char(2) NOT NULL DEFAULT '',
  `sales_type` varchar(40) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `salestypes`
--

INSERT INTO `salestypes` (`typeabbrev`, `sales_type`) VALUES
('De', 'Direct'),
('2', 'Distributor'),
('3', 'Special');

-- --------------------------------------------------------

--
-- Table structure for table `sampleresults`
--

CREATE TABLE `sampleresults` (
  `resultid` bigint(20) NOT NULL,
  `sampleid` int(11) NOT NULL,
  `testid` int(11) NOT NULL,
  `defaultvalue` varchar(150) NOT NULL,
  `targetvalue` varchar(30) NOT NULL,
  `rangemin` float DEFAULT NULL,
  `rangemax` float DEFAULT NULL,
  `testvalue` varchar(30) NOT NULL DEFAULT '',
  `testdate` date NOT NULL DEFAULT '0000-00-00',
  `testedby` varchar(15) NOT NULL DEFAULT '',
  `comments` varchar(255) NOT NULL DEFAULT '',
  `isinspec` tinyint(4) NOT NULL DEFAULT '0',
  `showoncert` tinyint(4) NOT NULL DEFAULT '1',
  `showontestplan` tinyint(4) NOT NULL DEFAULT '1',
  `manuallyadded` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `sampleresults`
--

INSERT INTO `sampleresults` (`resultid`, `sampleid`, `testid`, `defaultvalue`, `targetvalue`, `rangemin`, `rangemax`, `testvalue`, `testdate`, `testedby`, `comments`, `isinspec`, `showoncert`, `showontestplan`, `manuallyadded`) VALUES
(1, 1, 1, '450-500', '475', 450, 500, '', '0000-00-00', '', '', 0, 1, 1, 0),
(2, 1, 2, '1200-1500', '1300', 1200, 1500, '', '0000-00-00', '', '', 0, 1, 1, 0),
(4, 2, 1, '450-500', '475', 450, 500, '', '0000-00-00', '', '', 0, 1, 1, 0),
(5, 2, 2, '1200-1500', '1300', 1200, 1500, '', '0000-00-00', '', '', 0, 1, 1, 0),
(7, 3, 1, '450-500', '475', 450, 500, '', '0000-00-00', '', '', 0, 1, 1, 0),
(8, 3, 2, '1200-1500', '1300', 1200, 1500, '', '0000-00-00', '', '', 0, 1, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `scripts`
--

CREATE TABLE `scripts` (
  `script` varchar(78) NOT NULL DEFAULT '',
  `pagesecurity` int(11) NOT NULL DEFAULT '1',
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `scripts`
--

INSERT INTO `scripts` (`script`, `pagesecurity`, `description`) VALUES
('AccountGroups.php', 14, 'Defines the groupings of general ledger accounts'),
('AccountSections.php', 14, 'Defines the sections in the general ledger reports'),
('AddCustomerContacts.php', 1, 'Adds customer contacts'),
('AddCustomerNotes.php', 1, 'Adds notes about customers'),
('AddCustomerTypeNotes.php', 1, ''),
('AgedControlledInventory.php', 10, 'Report of Controlled Items and their age'),
('AgedDebtors.php', 2, 'Lists customer account balances in detail or summary in selected currency'),
('AgedSuppliers.php', 2, 'Lists supplier account balances in detail or summary in selected currency'),
('AnalysisHorizontalIncome.php', 14, 'Shows the horizontal analysis of the statement of comprehensive income'),
('AnalysisHorizontalPosition.php', 14, 'Shows the horizontal analysis of the statement of financial position'),
('Areas.php', 20, 'Defines the sales areas - all customers must belong to a sales area for the purposes of sales analysis'),
('AuditTrail.php', 20, 'Shows the activity with SQL statements and who performed the changes'),
('AutomaticTranslationDescriptions.php', 20, 'Translates via Google Translator all empty translated descriptions'),
('BankAccountBalances.php', 14, 'Shows bank accounts authorised for with balances'),
('BankAccounts.php', 14, 'Defines the general ledger code for bank accounts and specifies that bank transactions be created for these accounts for the purposes of reconciliation'),
('BankAccountUsers.php', 14, 'Maintains table bankaccountusers (Authorized users to work with a bank account in nERP)'),
('BankMatching.php', 13, 'Allows payments and receipts to be matched off against bank statements'),
('BankReconciliation.php', 13, 'Displays the bank reconciliation for a selected bank account'),
('BOMExtendedQty.php', 12, 'Shows the component requirements to make an item'),
('BOMIndented.php', 12, 'Shows the bill of material indented for each level'),
('BOMIndentedReverse.php', 12, ''),
('BOMInquiry.php', 12, 'Displays the bill of material with cost information'),
('BOMListing.php', 12, 'Lists the bills of material for a selected range of items'),
('BOMs.php', 12, 'Administers the bills of material for a selected item'),
('BOMs_SingleLevel.php', 12, 'Single Level BOM entry'),
('COGSGLPostings.php', 20, 'Defines the general ledger account to be used for cost of sales entries'),
('CollectiveWorkOrderCost.php', 12, 'Multiple work orders cost review'),
('CompanyPreferences.php', 20, 'Defines the settings applicable for the company, including name, address, tax authority reference, whether GL integration used etc.'),
('ConfirmDispatchControlled_Invoice.php', 7, 'Specifies the batch references/serial numbers of items dispatched that are being invoiced'),
('ConfirmDispatch_Invoice.php', 7, 'Creates sales invoices from entered sales orders based on the quantities dispatched that can be modified'),
('ContractBOM.php', 1, 'Creates the item requirements from stock for a contract as part of the contract cost build up'),
('ContractCosting.php', 2, 'Shows a contract cost - the components and other non-stock costs issued to the contract'),
('ContractOtherReqts.php', 1, 'Creates the other requirements for a contract cost build up'),
('Contracts.php', 1, 'Creates or modifies a customer contract costing'),
('CopyBOM.php', 12, 'Allows a bill of material to be copied between items'),
('CostUpdate', 10, 'NB Not a script but allows users to maintain item costs from withing StockCostUpdate.php'),
('CounterReturns.php', 1, 'Allows credits and refunds from the default Counter Sale account for an inventory location'),
('CounterSales.php', 1, 'Allows sales to be entered against a cash sale customer account defined in the users location record'),
('CreditItemsControlled.php', 3, 'Specifies the batch references/serial numbers of items being credited back into stock'),
('CreditStatus.php', 20, 'Defines the credit status records. Each customer account is given a credit status from this table. Some credit status records can prohibit invoicing and new orders being entered.'),
('Credit_Invoice.php', 3, 'Creates a credit note based on the details of an existing invoice'),
('Currencies.php', 20, 'Defines the currencies available. Each customer and supplier must be defined as transacting in one of the currencies defined here.'),
('CustEDISetup.php', 20, 'Allows the set up the customer specified EDI parameters for server, email or ftp.'),
('CustItem.php', 9, 'Customer Items'),
('CustLoginSetup.php', 2, ''),
('CustomerAccount.php', 1, 'Shows customer account/statement on screen rather than PDF'),
('CustomerAllocations.php', 3, 'Allows customer receipts and credit notes to be allocated to sales invoices'),
('CustomerBalancesMovement.php', 4, 'Allow customers to be listed in local currency with balances and activity over a date range'),
('CustomerBranches.php', 1, 'Defines the details of customer branches such as delivery address and contact details - also sales area, representative etc'),
('CustomerInquiry.php', 3, 'Shows the customers account transactions with balances outstanding, links available to drill down to invoice/credit note or email invoices/credit notes'),
('CustomerPurchases.php', 2, 'Shows the purchases a customer has made.'),
('CustomerReceipt.php', 3, 'Entry of both customer receipts against accounts receivable and also general ledger or nominal receipts'),
('Customers.php', 1, 'Defines the setup of a customer account, including payment terms, billing address, credit status, currency etc'),
('CustomerTransInquiry.php', 4, 'Lists in html the sequence of customer transactions, invoices, credit notes or receipts by a user entered date range'),
('CustomerTypes.php', 20, ''),
('CustomerWitholdingTax.php', 7, 'WitholdingTax receivable'),
('CustWhereAlloc.php', 4, 'Shows to which invoices a receipt was allocated to'),
('DailyBankTransactions.php', 14, 'Allows you to view all bank transactions for a selected date range, and the inquiry can be filtered by matched or unmatched transactions, or all transactions can be chosen'),
('DailySalesInquiry.php', 2, 'Shows the daily sales with GP in a calendar format'),
('Dashboard.php', 0, 'Display outstanding debtors, creditors etc'),
('DebtorsAtPeriodEnd.php', 2, 'Shows the debtors control account as at a previous period end - based on system calendar monthly periods'),
('DeliveryDetails.php', 1, 'Used during order entry to allow the entry of delivery addresses other than the defaulted branch delivery address and information about carrier/shipping method etc'),
('Departments.php', 20, 'Create business departments'),
('DiscountCategories.php', 20, 'Defines the items belonging to a discount category. Discount Categories are used to allow discounts based on quantities across a range of producs'),
('DiscountMatrix.php', 20, 'Defines the rates of discount applicable to discount categories and the customer groupings to which the rates are to apply'),
('EDIMessageFormat.php', 20, 'Specifies the EDI message format used by a customer - administrator use only.'),
('EDIProcessOrders.php', 20, 'Processes incoming EDI orders into sales orders'),
('EDISendInvoices.php', 20, 'Processes invoiced EDI customer invoices into EDI messages and sends using the customers preferred method either ftp or email attachments.'),
('EmailConfirmation.php', 2, ''),
('EmailCustStatements.php', 3, 'Email Customer Statements'),
('EmailCustTrans.php', 3, 'Emails selected invoice or credit to the customer'),
('ExchangeRateTrend.php', 20, 'Shows the trend in exchange rates as retrieved from ECB'),
('Factors.php', 20, 'Defines supplier factor companies'),
('FixedAssetCategories.php', 14, 'Defines the various categories of fixed assets'),
('FixedAssetDepreciation.php', 14, 'Calculates and creates GL transactions to post depreciation for a period'),
('FixedAssetItems.php', 13, 'Allows fixed assets to be defined'),
('FixedAssetLocations.php', 14, 'Allows the locations of fixed assets to be defined'),
('FixedAssetRegister.php', 13, 'Produces a csv, html or pdf report of the fixed assets over a period showing period depreciation, additions and disposals'),
('FixedAssetTransfer.php', 13, 'Allows the fixed asset locations to be changed in bulk'),
('FormDesigner.php', 20, ''),
('FormMaker.php', 20, 'Allows running user defined Forms'),
('FreightCosts.php', 20, 'Defines the setup of the freight cost using different shipping methods to different destinations. The system can use this information to calculate applicable freight if the items are defined with the correct kgs and cubic volume'),
('FTP_RadioBeacon.php', 20, 'FTPs sales orders for dispatch to a radio beacon software enabled warehouse dispatching facility'),
('GeneratePickingList.php', 20, 'Generate a picking list'),
('geocode.php', 20, ''),
('GeocodeSetup.php', 20, ''),
('geocode_genxml_customers.php', 20, ''),
('geocode_genxml_suppliers.php', 20, ''),
('geo_displaymap_customers.php', 20, ''),
('geo_displaymap_suppliers.php', 20, ''),
('GetStockImage.php', 20, ''),
('GLAccountCSV.php', 14, 'Produces a CSV of the GL transactions for a particular range of periods and GL account'),
('GLAccountGraph.php', 14, 'Shows a graph of GL account transactions'),
('GLAccountInquiry.php', 14, 'Shows the general ledger transactions for a specified account over a specified range of periods'),
('GLAccountReport.php', 14, 'Produces a report of the GL transactions for a particular account'),
('GLAccounts.php', 14, 'Defines the general ledger accounts'),
('GLAccountUsers.php', 14, 'Maintenance of users allowed to a GL Account'),
('GLBalanceSheet.php', 14, 'Shows the balance sheet for the company as at a specified date'),
('GLBudgets.php', 14, 'Defines GL Budgets'),
('GLCashFlowsIndirect.php', 14, 'Shows a statement of cash flows for the period using the indirect method'),
('GLCashFlowsSetup.php', 14, 'Setups the statement of cash flows sections'),
('GLCodesInquiry.php', 14, 'Shows the list of general ledger codes defined with account names and groupings'),
('GLJournal.php', 13, 'Entry of general ledger journals, periods are calculated based on the date entered here'),
('GLJournalInquiry.php', 13, 'General Ledger Journal Inquiry'),
('GLProfit_Loss.php', 14, 'Shows the profit and loss of the company for the range of periods entered'),
('GLTagProfit_Loss.php', 14, ''),
('GLTags.php', 14, 'Allows GL tags to be defined'),
('GLTransInquiry.php', 14, 'Shows the general ledger journal created for the sub ledger transaction specified'),
('GLTrialBalance.php', 14, 'Shows the trial balance for the month and the for the period selected together with the budgeted trial balances'),
('GLTrialBalance_csv.php', 14, 'Produces a CSV of the Trial Balance for a particular period'),
('GoodsReceived.php', 9, 'Entry of items received against purchase orders'),
('GoodsReceivedControlled.php', 9, 'Entry of the serial numbers or batch references for controlled items received against purchase orders'),
('GoodsReceivedNotInvoiced.php', 10, 'Shows the list of goods received but not yet invoiced, both in supplier currency and home currency. Total in home curency should match the GL Account for Goods received not invoiced. Any discrepancy is due to multicurrency errors.'),
('HistoricalTestResults.php', 12, 'Historical Test Results'),
('HrApproveLoan.php', 23, 'HR'),
('HrAttendanceRegister.php', 22, 'Add,Edit Employees Attendance Register'),
('HrDeductablesReports.php', 23, 'See Reports on Hr deductables'),
('HrEmployeeCategories.php', 20, 'Manage Employee Categories'),
('HrEmployeeGrades.php', 20, 'Manage Employee Grading'),
('HrEmployeeLoans.php', 22, 'Manage employee salary loans'),
('HrEmployeePayslips.php', 23, 'Employee to see Payslips'),
('HrEmployeePositions.php', 20, 'Manage Employee Job Positions'),
('HrEmployees.php', 22, 'Add,Edit employees'),
('HrGenerateAttendanceReport.php', 22, 'Generate Attendance Report'),
('HrGenerateEmployeePay.php', 23, 'Generate Payroll for Single Employee'),
('HrGenerateEstimatedSalary.php', 23, 'Salary Estimate Report'),
('HrGeneratePayroll.php', 23, 'Generate Payroll for Paygroups'),
('HrGlSettings.php', 20, 'General ledger settings'),
('HrLeaveApplications.php', 21, 'Add,Edit Employees Leaves'),
('HrLeaveGroups.php', 20, 'Manage Leave Groups'),
('HrLeaveTypes.php', 20, 'Manage Leave Types'),
('HrLoanTypes.php', 20, 'Loan Type settings'),
('HrMyLeave.php', 21, 'View Employee Leaves  '),
('HrNotificationSettings.php', 22, 'Notification settings'),
('HrPayrollCategories.php', 20, 'Manage Payroll Categories'),
('HrPayrollGroups.php', 20, 'Manage Payroll Groups'),
('HrPayrollMode.php', 20, 'Payroll mode settings'),
('HrPayslipSettings.php', 20, 'Payslip settings'),
('HrPrintPayslip.php', 22, 'Print Payslip'),
('HrSelectEmployee.php', 22, 'Search employees'),
('HrSelectLeave.php', 22, 'Search leaves'),
('HrWorkingDays.php', 20, 'Working days settings'),
('ImportBankTrans.php', 20, 'Imports bank transactions'),
('ImportBankTransAnalysis.php', 20, 'Allows analysis of bank transactions being imported'),
('index.php', 0, 'The main menu from where all functions available to the user are accessed by clicking on the links'),
('InternalStockCategoriesByRole.php', 20, 'Maintains the stock categories to be used as internal for any user security role'),
('InternalStockRequest.php', 0, 'Create an internal stock request'),
('InternalStockRequestAuthorisation.php', 0, 'Authorise internal stock requests'),
('InternalStockRequestFulfill.php', 9, 'Fulfill an internal stock request'),
('InternalStockRequestInquiry.php', 10, 'Internal Stock Request inquiry'),
('InventoryPlanning.php', 12, 'Creates a pdf report showing the last 4 months use of items including as a component of assemblies together with stock quantity on hand, current demand for the item and current quantity on sales order.'),
('InventoryPlanningPrefSupplier.php', 12, 'Produces a report showing the inventory to be ordered by supplier'),
('InventoryPlanningPrefSupplier_CSV.php', 12, 'Inventory planning spreadsheet'),
('InventoryQuantities.php', 12, ''),
('InventoryValuation.php', 12, 'Creates a pdf report showing the value of stock at standard cost for a range of product categories selected'),
('Labels.php', 20, 'Produces item pricing labels in a pdf from a range of selected criteria'),
('Locations.php', 20, 'Defines the inventory stocking locations or warehouses'),
('LocationUsers.php', 20, 'Allows users that have permission to access a location to be defined'),
('Logout.php', 0, 'Shows when the user logs out of nERP'),
('MailingGroupMaintenance.php', 20, 'Mainting mailing lists for items to mail'),
('MailInventoryValuation.php', 20, 'Meant to be run as a scheduled process to email the stock valuation off to a specified person. Creates the same stock valuation report as InventoryValuation.php'),
('MailSalesReport_csv.php', 20, 'Mailing the sales report'),
('MaintenanceReminders.php', 20, 'Sends email reminders for scheduled asset maintenance tasks'),
('MaintenanceTasks.php', 14, 'Allows set up and edit of scheduled maintenance tasks'),
('MaintenanceUserSchedule.php', 13, 'List users or managers scheduled maintenance tasks and allow to be flagged as completed'),
('Manufacturers.php', 20, 'Maintain brands of sales products'),
('MaterialsNotUsed.php', 10, 'Lists the items from Raw Material Categories not used in any BOM (thus, not used at all)'),
('menu_data.php', 0, 'Modified Menu'),
('MRP.php', 12, ''),
('MRPCalendar.php', 20, ''),
('MRPCreateDemands.php', 12, ''),
('MRPDemands.php', 12, ''),
('MRPDemandTypes.php', 20, ''),
('MRPPlannedPurchaseOrders.php', 12, ''),
('MRPPlannedWorkOrders.php', 12, ''),
('MRPReport.php', 12, ''),
('MRPReschedules.php', 12, ''),
('MRPShortages.php', 12, ''),
('NoSalesItems.php', 2, 'Shows the No Selling (worst) items'),
('OffersReceived.php', 4, ''),
('OrderDetails.php', 1, 'Shows the detail of a sales order'),
('OrderEntryDiscountPricing', 13, 'Not a script but an authority level marker - required if the user is allowed to enter discounts and special pricing against a customer order'),
('OutstandingGRNs.php', 10, 'Creates a pdf showing all GRNs for which there has been no purchase invoice matched off against.'),
('PageSecurity.php', 20, ''),
('PaymentAllocations.php', 7, ''),
('PaymentMethods.php', 20, ''),
('Payments.php', 3, 'Entry of bank account payments either against an AP account or a general ledger payment - if the AP-GL link in company preferences is set'),
('PaymentTerms.php', 20, 'Defines the payment terms records, these can be expressed as either a number of days credit or a day in the following month. All customers and suppliers must have a corresponding payment term recorded against their account'),
('PcAnalysis.php', 14, 'Creates an Excel with details of PC expnese for 24 months'),
('PcAssignCashTabToTab.php', 13, 'Assign cash from one tab to another'),
('PcAssignCashToTab.php', 13, ''),
('PcAuthorizeCash.php', 0, 'Authorisation of assigned cash'),
('PcAuthorizeExpenses.php', 0, ''),
('PcClaimExpensesFromTab.php', 0, ''),
('PcExpenses.php', 14, ''),
('PcExpensesTypeTab.php', 14, ''),
('PcReportExpense.php', 14, ''),
('PcReportTab.php', 14, ''),
('PcTabExpensesList.php', 14, 'Creates excel with all movements of tab between dates'),
('PcTabs.php', 14, ''),
('PcTypeTabs.php', 14, ''),
('PDFAck.php', 3, 'Print an acknowledgement'),
('PDFBankingSummary.php', 3, 'Creates a pdf showing the amounts entered as receipts on a specified date together with references for the purposes of banking'),
('PDFChequeListing.php', 3, 'Creates a pdf showing all payments that have been made from a specified bank account over a specified period. This can be emailed to an email account defined in config.php - ie a financial controller'),
('PDFCOA.php', 14, 'PDF of COA'),
('PDFCustomerList.php', 2, 'Creates a report of the customer and branch information held. This report has options to print only customer branches in a specified sales area and sales person. Additional option allows to list only those customers with activity either under or over a specified amount, since a specified date.'),
('PDFCustTransListing.php', 3, ''),
('PDFDeliveryDifferences.php', 2, 'Creates a pdf report listing the delivery differences from what the customer requested as recorded in the order entry. The report calculates a percentage of order fill based on the number of orders filled in full on time'),
('PDFDIFOT.php', 2, 'Produces a pdf showing the delivery in full on time performance'),
('PDFFGLabel.php', 14, 'Produces FG Labels'),
('PDFGLJournal.php', 14, 'General Ledger Journal Print'),
('PDFGLJournalCN.php', 14, 'Print GL Journal Chinese version'),
('PDFGrn.php', 9, 'Produces a GRN report on the receipt of stock'),
('PDFLowGP.php', 2, 'Creates a pdf report showing the low gross profit sales made in the selected date range. The percentage of gp deemed acceptable can also be entered'),
('PDFOrdersInvoiced.php', 3, 'Produces a pdf of orders invoiced based on selected criteria'),
('PDFOrderStatus.php', 2, 'Reports on sales order status by date range, by stock location and stock category - producing a pdf showing each line items and any quantites delivered'),
('PDFPeriodStockTransListing.php', 10, 'Allows stock transactions of a specific transaction type to be listed over a single day or period range'),
('PDFPickingList.php', 20, ''),
('PDFPriceList.php', 2, 'Creates a pdf of the price list applicable to a given sales type and customer. Also allows the listing of prices specific to a customer'),
('PDFPrintLabel.php', 20, ''),
('PDFProdSpec.php', 12, 'PDF OF Product Specification'),
('PDFQALabel.php', 11, 'Produces a QA label on receipt of stock'),
('PDFQuotation.php', 1, ''),
('PDFQuotationPortrait.php', 1, 'Portrait quotation'),
('PDFReceipt.php', 3, ''),
('PDFRemittanceAdvice.php', 8, ''),
('PDFSellThroughSupportClaim.php', 20, 'Reports the sell through support claims to be made against all suppliers for a given date range.'),
('PDFShipLabel.php', 8, 'Print a ship label'),
('PDFStockCheckComparison.php', 10, 'Creates a pdf comparing the quantites entered as counted at a given range of locations against the quantity stored as on hand as at the time a stock check was initiated.'),
('PDFStockLocTransfer.php', 10, 'Creates a stock location transfer docket for the selected location transfer reference number'),
('PDFStockNegatives.php', 10, 'Produces a pdf of the negative stocks by location'),
('PDFStockTransfer.php', 10, 'Produces a report for stock transfers'),
('PDFSuppTransListing.php', 4, ''),
('PDFTestPlan.php', 12, 'PDF of Test Plan'),
('PDFTopItems.php', 2, 'Produces a pdf report of the top items sold'),
('PDFWOPrint.php', 11, 'Produces W/O Paperwork'),
('PeriodsInquiry.php', 14, 'Shows a list of all the system defined periods'),
('PickingLists.php', 20, 'Picking List Maintenance'),
('PickingListsControlled.php', 20, 'Picking List Maintenance - Controlled'),
('POReport.php', 6, ''),
('PO_AuthorisationLevels.php', 20, ''),
('PO_AuthoriseMyOrders.php', 6, ''),
('PO_Header.php', 5, 'Entry of a purchase order header record - date, references buyer etc'),
('PO_Items.php', 5, 'Entry of a purchase order items - allows entry of items with lookup of currency cost from Purchasing Data previously entered also allows entry of nominal items against a general ledger code if the AP is integrated to the GL'),
('PO_OrderDetails.php', 5, 'Purchase order inquiry shows the quantity received and invoiced of purchase order items as well as the header information'),
('PO_PDFPurchOrder.php', 5, 'Creates a pdf of the selected purchase order for printing or email to one of the supplier contacts entered'),
('PO_SelectOSPurchOrder.php', 5, 'Shows the outstanding purchase orders for selecting with links to receive or modify the purchase order header and items'),
('PO_SelectPurchOrder.php', 5, 'Allows selection of any purchase order with links to the inquiry'),
('PriceMatrix.php', 2, 'Mantain stock prices according to quantity break and sales types'),
('Prices.php', 2, 'Entry of prices for a selected item also allows selection of sales type and currency for the price'),
('PricesBasedOnMarkUp.php', 10, ''),
('PricesByCost.php', 10, 'Allows prices to be updated based on cost'),
('Prices_Customer.php', 2, 'Entry of prices for a selected item and selected customer/branch. The currency and sales type is defaulted from the customer\'s record'),
('PrintCheque.php', 7, ''),
('PrintCustOrder.php', 2, 'Creates a pdf of the dispatch note - by default this is expected to be on two part pre-printed stationery to allow pickers to note discrepancies for the confirmer to update the dispatch at the time of invoicing'),
('PrintCustOrder_generic.php', 1, 'Creates two copies of a laser printed dispatch note - both copies need to be written on by the pickers with any discrepancies to advise customer of any shortfall and on the office copy to ensure the correct quantites are invoiced'),
('PrintCustStatements.php', 1, 'Creates a pdf for the customer statements in the selected range'),
('PrintCustTrans.php', 1, 'Creates either a html invoice or credit note or a pdf. A range of invoices or credit notes can be selected also.'),
('PrintCustTransPortrait.php', 1, ''),
('PrintSalesOrder_generic.php', 1, ''),
('PrintWOItemSlip.php', 11, 'PDF WO Item production Slip '),
('ProductSpecs.php', 12, 'Product Specification Maintenance'),
('PurchaseByPrefSupplier.php', 5, 'Purchase ordering by preferred supplier'),
('PurchasesReport.php', 6, 'Shows a report of purchases from suppliers for the range of selected dates'),
('PurchData.php', 5, 'Entry of supplier purchasing data, the suppliers part reference and the suppliers currency cost of the item'),
('QATests.php', 11, 'Quality Test Maintenance'),
('RecurringSalesOrders.php', 1, ''),
('RecurringSalesOrdersProcess.php', 2, 'Process Recurring Sales Orders'),
('RelatedItemsUpdate.php', 5, 'Maintains Related Items'),
('ReorderLevel.php', 6, 'Allows reorder levels of inventory to be updated'),
('ReorderLevelLocation.php', 6, ''),
('ReportCreator.php', 20, 'Report Writer and Form Creator script that creates templates for user defined reports and forms'),
('ReportMaker.php', 20, 'Produces reports from the report writer templates created'),
('reportwriter/admin/ReportCreator.php', 20, 'Report Writer'),
('ReprintGRN.php', 9, 'Allows selection of a goods received batch for reprinting the goods received note given a purchase order number'),
('ReverseGRN.php', 9, 'Reverses the entry of goods received - creating stock movements back out and necessary general ledger journals to effect the reversal'),
('RevisionTranslations.php', 20, 'Human revision for automatic descriptions translations'),
('SalesAnalReptCols.php', 20, 'Entry of the definition of a sales analysis report\'s columns.'),
('SalesAnalRepts.php', 20, 'Entry of the definition of a sales analysis report headers'),
('SalesAnalysis_UserDefined.php', 20, 'Creates a pdf of a selected user defined sales analysis report'),
('SalesByTypePeriodInquiry.php', 2, 'Shows sales for a selected date range by sales type/price list'),
('SalesCategories.php', 20, ''),
('SalesCategoryDescriptions.php', 20, 'Maintain translations for sales categories'),
('SalesCategoryPeriodInquiry.php', 20, 'Shows sales for a selected date range by stock category'),
('SalesGLPostings.php', 20, 'Defines the general ledger accounts used to post sales to based on product categories and sales areas'),
('SalesGraph.php', 2, ''),
('SalesInquiry.php', 2, ''),
('SalesPeople.php', 20, 'Defines the sales people of the business'),
('SalesTopCustomersInquiry.php', 2, 'Shows the top customers'),
('SalesTopItemsInquiry.php', 2, 'Shows the top item sales for a selected date range'),
('SalesTypes.php', 20, 'Defines the sales types - prices are held against sales types they can be considered price lists. Sales analysis records are held by sales type too.'),
('SecurityTokens.php', 20, 'Administration of security tokens'),
('SelectAsset.php', 13, 'Allows a fixed asset to be selected for modification or viewing'),
('SelectCompletedOrder.php', 2, 'Allows the selection of completed sales orders for inquiries - choices to select by item code or customer'),
('SelectContract.php', 1, 'Allows a contract costing to be selected for modification or viewing'),
('SelectCreditItems.php', 3, 'Entry of credit notes from scratch, selecting the items in either quick entry mode or searching for them manually'),
('SelectCustomer.php', 1, 'Selection of customer - from where all customer related maintenance, transactions and inquiries start'),
('SelectGLAccount.php', 14, 'Selection of general ledger account from where all general ledger account maintenance, or inquiries are initiated'),
('SelectOrderItems.php', 1, 'Entry of sales order items with both quick entry and part search functions'),
('SelectPickingLists.php', 20, 'Select a picking list'),
('SelectProduct.php', 1, 'Selection of items. All item maintenance, transactions and inquiries start with this script'),
('SelectQASamples.php', 11, 'Select  QA Samples'),
('SelectRecurringSalesOrder.php', 1, ''),
('SelectSalesOrder.php', 1, 'Selects a sales order irrespective of completed or not for inquiries'),
('SelectSupplier.php', 5, 'Selects a supplier. A supplier is required to be selected before any AP transactions and before any maintenance or inquiry of the supplier'),
('SelectWorkOrder.php', 11, ''),
('SellThroughSupport.php', 20, 'Defines the items, period and quantum of support for which supplier has agreed to provide.'),
('ShipmentCosting.php', 7, 'Shows the costing of a shipment with all the items invoice values and any shipment costs apportioned. Updating the shipment has an option to update standard costs of all items on the shipment and create any general ledger variance journals'),
('Shipments.php', 7, 'Entry of shipments from outstanding purchase orders for a selected supplier - changes in the delivery date will cascade into the different purchase orders on the shipment'),
('Shippers.php', 20, 'Defines the shipping methods available. Each customer branch has a default shipping method associated with it which must match a record from this table'),
('ShiptsList.php', 7, 'Shows a list of all the open shipments for a selected supplier. Linked from POItems.php'),
('Shipt_Select.php', 7, 'Selection of a shipment for displaying and modification or updating'),
('ShopParameters.php', 20, 'Maintain web-store configuration and set up'),
('SMTPServer.php', 20, ''),
('SpecialOrder.php', 20, 'Allows for a sales order to be created and an indent order to be created on a supplier for a one off item that may never be purchased again. A dummy part is created based on the description and cost details given.'),
('StockAdjustments.php', 10, 'Entry of quantity corrections to stocks in a selected location.'),
('StockAdjustmentsControlled.php', 10, 'Entry of batch references or serial numbers on controlled stock items being adjusted'),
('StockCategories.php', 20, 'Defines the stock categories. All items must refer to one of these categories. The category record also allows the specification of the general ledger codes where stock items are to be posted - the balance sheet account and the profit and loss effect of any adjustments and the profit and loss effect of any price variances'),
('StockCategorySalesInquiry.php', 2, 'Sales inquiry by stock category showing top items'),
('StockCheck.php', 10, 'Allows creation of a stock check file - copying the current quantites in stock for later comparison to the entered counts. Also produces a pdf for the count sheets.'),
('StockClone.php', 9, 'Script to copy a stock item and associated properties, image, price, purchase and cost data'),
('StockCostUpdate.php', 9, 'Allows update of the standard cost of items producing general ledger journals if the company preferences stock GL interface is active'),
('StockCounts.php', 9, 'Allows entry of stock counts'),
('StockDispatch.php', 9, ''),
('StockLocMovements.php', 10, 'Inquiry shows the Movements of all stock items for a specified location'),
('StockLocStatus.php', 10, 'Shows the stock on hand together with outstanding sales orders and outstanding purchase orders by stock location for all items in the selected stock category'),
('StockLocTransfer.php', 9, 'Entry of a bulk stock location transfer for many parts from one location to another.'),
('StockLocTransferReceive.php', 9, 'Effects the transfer and creates the stock movements for a bulk stock location transfer initiated from StockLocTransfer.php'),
('StockMovements.php', 10, 'Shows a list of all the stock movements for a selected item and stock location including the price at which they were sold in local currency and the price at which they were purchased for in local currency'),
('StockQties_csv.php', 10, 'Makes a comma separated values (CSV)file of the stock item codes and quantities'),
('StockQuantityByDate.php', 10, 'Shows the stock on hand for each item at a selected location and stock category as at a specified date'),
('StockReorderLevel.php', 10, 'Entry and review of the re-order level of items by stocking location'),
('Stocks.php', 9, 'Defines an item - maintenance and addition of new parts'),
('StockSerialItemResearch.php', 10, ''),
('StockSerialItems.php', 9, 'Shows a list of the serial numbers or the batch references and quantities of controlled items. This inquiry is linked from the stock status inquiry'),
('StockStatus.php', 10, 'Shows the stock on hand together with outstanding sales orders and outstanding purchase orders by stock location for a selected part. Has a link to show the serial numbers in stock at the location selected if the item is controlled'),
('StockTransferControlled.php', 9, 'Entry of serial numbers/batch references for controlled items being received on a stock transfer. The script is used by both bulk transfers and point to point transfers'),
('StockTransfers.php', 9, 'Entry of point to point stock location transfers of a single part'),
('StockUsage.php', 10, 'Inquiry showing the quantity of stock used by period calculated from the sum of the stock movements over that period - by item and stock location. Also available over all locations'),
('StockUsageGraph.php', 10, ''),
('SuppContractChgs.php', 7, ''),
('SuppCreditGRNs.php', 7, 'Entry of a supplier credit notes (debit notes) against existing GRN which have already been matched in full or in part'),
('SuppFixedAssetChgs.php', 7, ''),
('SuppInvGRNs.php', 7, 'Entry of supplier invoices against goods received'),
('SupplierAllocations.php', 7, 'Entry of allocations of supplier payments and credit notes to invoices'),
('SupplierBalsAtPeriodEnd.php', 8, ''),
('SupplierContacts.php', 5, 'Entry of supplier contacts and contact details including email addresses'),
('SupplierCredit.php', 6, 'Entry of supplier credit notes (debit notes)'),
('SupplierGRNAndInvoiceInquiry.php', 7, 'Supplier\'s delivery note and grn relationship inquiry'),
('SupplierInquiry.php', 8, 'Inquiry showing invoices, credit notes and payments made to suppliers together with the amounts outstanding'),
('SupplierInvoice.php', 7, 'Entry of supplier invoices'),
('SupplierPriceList.php', 5, 'Maintain Supplier Price Lists'),
('Suppliers.php', 5, 'Entry of new suppliers and maintenance of existing suppliers'),
('SupplierTenderCreate.php', 7, 'Create or Edit tenders'),
('SupplierTenders.php', 7, ''),
('SupplierTransInquiry.php', 8, ''),
('SupplierTypes.php', 20, ''),
('SuppLoginSetup.php', 6, ''),
('SuppPaymentRun.php', 8, 'Automatic creation of payment records based on calculated amounts due from AP invoices entered'),
('SuppPriceList.php', 5, ''),
('SuppShiptChgs.php', 6, 'Entry of supplier invoices against shipments as charges against a shipment'),
('SuppTransGLAnalysis.php', 14, 'Entry of supplier invoices against general ledger codes'),
('SuppWhereAlloc.php', 6, 'Suppliers Where allocated'),
('SystemParameters.php', 20, ''),
('Tax.php', 20, 'Creates a report of the ad-valoerm tax - GST/VAT - for the period selected from accounts payable and accounts receivable data'),
('TaxAuthorities.php', 20, 'Entry of tax authorities - the state intitutions that charge tax'),
('TaxAuthorityRates.php', 20, 'Entry of the rates of tax applicable to the tax authority depending on the item tax level'),
('TaxCategories.php', 20, 'Allows for categories of items to be defined that might have different tax rates applied to them'),
('TaxGroups.php', 20, 'Allows for taxes to be grouped together where multiple taxes might apply on sale or purchase of items'),
('TaxProvinces.php', 20, 'Allows for inventory locations to be defined so that tax applicable from sales in different provinces can be dealt with'),
('TestPlanResults.php', 12, 'Test Plan Results Entry'),
('TopItems.php', 2, 'Shows the top selling items'),
('UnitsOfMeasure.php', 20, 'Allows for units of measure to be defined'),
('UpgradeDatabase.php', 20, 'Allows for the database to be automatically upgraded based on currently recorded DBUpgradeNumber config option'),
('UserBankAccounts.php', 14, 'Maintains table bankaccountusers (Authorized users to work with a bank account in nERP)'),
('UserGLAccounts.php', 14, 'Maintenance of GL Accounts allowed for a user'),
('UserLocations.php', 20, 'Location User Maintenance'),
('UserSettings.php', 0, 'Allows the user to change system wide defaults for the theme - appearance, the number of records to show in searches and the language to display messages in'),
('WhereUsedInquiry.php', 12, 'Inquiry showing where an item is used ie all the parents where the item is a component of'),
('WOCanBeProducedNow.php', 12, 'List of WO items that can be produced with available stock in location'),
('WorkCentres.php', 12, 'Defines the various centres of work within a manufacturing company. Also the overhead and labour rates applicable to the work centre and its standard capacity'),
('WorkOrderCosting.php', 12, ''),
('WorkOrderEntry.php', 11, 'Entry of new work orders'),
('WorkOrderIssue.php', 9, 'Issue of materials to a work order'),
('WorkOrderReceive.php', 9, 'Allows for receiving of works orders'),
('WorkOrderStatus.php', 11, 'Shows the status of works orders'),
('WOSerialNos.php', 10, ''),
('WWW_Access.php', 20, ''),
('WWW_Users.php', 20, 'Entry of users and security settings of users'),
('Z_BottomUpCosts.php', 20, ''),
('Z_ChangeBranchCode.php', 20, 'Utility to change the branch code of a customer that cascades the change through all the necessary tables'),
('Z_ChangeCustomerCode.php', 20, 'Utility to change a customer code that cascades the change through all the necessary tables'),
('Z_ChangeGLAccountCode.php', 20, 'Script to change a GL account code accross all tables necessary'),
('Z_ChangeLocationCode.php', 20, 'Change a locations code and in all tables where the old code was used to the new code'),
('Z_ChangeSalesmanCode.php', 20, 'Utility to change a salesman code'),
('Z_ChangeStockCategory.php', 20, ''),
('Z_ChangeStockCode.php', 20, 'Utility to change an item code that cascades the change through all the necessary tables'),
('Z_ChangeSupplierCode.php', 20, 'Script to change a supplier code accross all tables necessary'),
('Z_CheckAllocationsFrom.php', 20, ''),
('Z_CheckAllocs.php', 20, ''),
('Z_CheckDebtorsControl.php', 20, 'Inquiry that shows the total local currency (functional currency) balance of all customer accounts to reconcile with the general ledger debtors account'),
('Z_CheckGLTransBalance.php', 20, 'Checks all GL transactions balance and reports problem ones'),
('Z_CreateChartDetails.php', 20, 'Utility page to create chart detail records for all general ledger accounts and periods created - needs expert assistance in use'),
('Z_CreateCompany.php', 20, 'Utility to insert company number 1 if not already there - actually only company 1 is used - the system is not multi-company'),
('Z_CreateCompanyTemplateFile.php', 20, ''),
('Z_CurrencyDebtorsBalances.php', 20, 'Inquiry that shows the total foreign currency together with the total local currency (functional currency) balances of all customer accounts to reconcile with the general ledger debtors account'),
('Z_CurrencySuppliersBalances.php', 20, 'Inquiry that shows the total foreign currency amounts and also the local currency (functional currency) balances of all supplier accounts to reconcile with the general ledger creditors account'),
('Z_DataExport.php', 20, ''),
('Z_DeleteCreditNote.php', 20, 'Utility to reverse a customer credit note - a desperate measure that should not be used except in extreme circumstances'),
('Z_DeleteInvoice.php', 20, 'Utility to reverse a customer invoice - a desperate measure that should not be used except in extreme circumstances'),
('Z_DeleteOldPrices.php', 20, 'Deletes all old prices'),
('Z_DeleteSalesTransActions.php', 20, 'Utility to delete all sales transactions, sales analysis the lot! Extreme care required!!!'),
('Z_DescribeTable.php', 20, ''),
('Z_Fix1cAllocations.php', 20, ''),
('Z_GLAccountUsersCopyAuthority.php', 20, 'Utility to copy authority of GL accounts from one user to another'),
('Z_ImportChartOfAccounts.php', 20, ''),
('Z_ImportDebtors.php', 20, 'Import debtors by csv file'),
('Z_ImportFixedAssets.php', 20, 'Allow fixed assets to be imported from a csv'),
('Z_ImportGLAccountGroups.php', 20, ''),
('Z_ImportGLAccountSections.php', 20, ''),
('Z_ImportGLTransactions.php', 20, 'Import General Ledger Transactions'),
('Z_ImportPartCodes.php', 20, 'Allows inventory items to be imported from a csv'),
('Z_ImportPriceList.php', 20, 'Loads a new price list from a csv file'),
('Z_ImportStocks.php', 20, ''),
('Z_index.php', 20, 'Utility menu page'),
('Z_ItemsWithoutPicture.php', 20, 'Shows the list of curent items without picture in nERP'),
('Z_MakeLocUsers.php', 20, 'Create User Location records'),
('Z_MakeNewCompany.php', 20, ''),
('Z_MakeStockLocns.php', 20, 'Utility to make LocStock records for all items and locations if not already set up.'),
('Z_poAddLanguage.php', 20, 'Allows a new language po file to be created'),
('Z_poAdmin.php', 20, 'Allows for a gettext language po file to be administered'),
('Z_poEditLangHeader.php', 20, ''),
('Z_poEditLangModule.php', 20, ''),
('Z_poEditLangRemaining.php', 20, ''),
('Z_poRebuildDefault.php', 20, ''),
('Z_PriceChanges.php', 20, 'Utility to make bulk pricing alterations to selected sales type price lists or selected customer prices only'),
('Z_ReApplyCostToSA.php', 20, 'Utility to allow the sales analysis table to be updated with the latest cost information - the sales analysis takes the cost at the time the sale was made to reconcile with the enteries made in the gl.'),
('Z_RemovePurchaseBackOrders.php', 20, 'Removes all purchase order back orders'),
('Z_RePostGLFromPeriod.php', 20, 'Utility to repost all general ledger transaction commencing from a specified period. This can take some time in busy environments. Normally GL transactions are posted automatically each time a trial balance or profit and loss account is run'),
('Z_ReverseSuppPaymentRun.php', 20, 'Utility to reverse an entire Supplier payment run'),
('Z_SalesIntegrityCheck.php', 20, ''),
('Z_UpdateChartDetailsBFwd.php', 20, 'Utility to recalculate the ChartDetails table B/Fwd balances - extreme care!!'),
('Z_UpdateItemCosts.php', 20, 'Use CSV of item codes and costs to update nERP item costs'),
('Z_UpdateSalesAnalysisWithLatestCustomerData.php', 20, 'Updates the salesanalysis table with the latest data from the customer debtorsmaster salestype and custbranch sales area and sales person irrespective of the sales type, area, salesperson at the time when the sale was made'),
('Z_UploadForm.php', 20, 'Utility to upload a file to a remote server'),
('Z_UploadResult.php', 20, 'Utility to upload a file to a remote server');

-- --------------------------------------------------------

--
-- Table structure for table `securitygroups`
--

CREATE TABLE `securitygroups` (
  `secroleid` int(11) NOT NULL DEFAULT '0',
  `tokenid` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `securitygroups`
--

INSERT INTO `securitygroups` (`secroleid`, `tokenid`) VALUES
(1, 0),
(1, 9),
(1, 10),
(2, 0),
(2, 9),
(4, 0),
(4, 1),
(4, 2),
(5, 0),
(5, 5),
(6, 0),
(6, 1),
(7, 0),
(7, 5),
(7, 6),
(8, 0),
(8, 1),
(8, 2),
(8, 3),
(8, 4),
(8, 5),
(8, 6),
(8, 7),
(8, 8),
(8, 9),
(8, 10),
(8, 11),
(8, 12),
(8, 13),
(8, 14),
(8, 15),
(8, 16),
(8, 18),
(8, 19),
(8, 20),
(8, 21),
(8, 22),
(8, 23),
(9, 0),
(9, 3),
(10, 0),
(10, 7),
(11, 0),
(11, 3),
(11, 4),
(12, 0),
(12, 7),
(12, 8),
(13, 0),
(13, 11),
(14, 11),
(14, 12),
(15, 0),
(15, 13),
(15, 15),
(15, 18),
(16, 0),
(16, 13),
(16, 14),
(16, 15),
(16, 16),
(16, 18),
(16, 19),
(17, 0),
(17, 21),
(17, 22),
(18, 0),
(18, 21),
(18, 22),
(18, 23),
(19, 0),
(19, 21);

-- --------------------------------------------------------

--
-- Table structure for table `securityroles`
--

CREATE TABLE `securityroles` (
  `secroleid` int(11) NOT NULL,
  `secrolename` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `securityroles`
--

INSERT INTO `securityroles` (`secroleid`, `secrolename`) VALUES
(1, 'Store Manager'),
(2, 'Store Executive'),
(4, 'Sales Manageer'),
(5, 'Purchase Executive'),
(6, 'Sales Executive'),
(7, 'Purchase Manager'),
(8, 'Owner/Director/CEO'),
(9, 'AR-Executive'),
(10, 'AP-Executive'),
(11, 'AR-Manager'),
(12, 'AP-Manager'),
(13, 'Production Executive'),
(14, 'Production Manager'),
(15, 'Accounts Executive'),
(16, 'Accounts Manager'),
(17, 'HR-Executive'),
(18, 'HR-Manager'),
(19, 'Employees');

-- --------------------------------------------------------

--
-- Table structure for table `securitytokens`
--

CREATE TABLE `securitytokens` (
  `tokenid` int(11) NOT NULL DEFAULT '0',
  `tokenname` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `securitytokens`
--

INSERT INTO `securitytokens` (`tokenid`, `tokenname`) VALUES
(0, 'Dashboard'),
(1, 'Sales-Ops'),
(2, 'Sales-Reports'),
(3, 'AR-Ops'),
(4, 'AR-Reports'),
(5, 'Purchase-Ops'),
(6, 'Purchase-Reports'),
(7, 'AP-Ops'),
(8, 'AP-Reports'),
(9, 'Inventory-Ops'),
(10, 'Inventory-Reports'),
(11, 'Manufacturing-Ops'),
(12, 'Manufacturing-Reports'),
(13, 'GL-Ops'),
(14, 'GL-Reports'),
(15, 'FA-Ops'),
(16, 'FA-Reports'),
(18, 'PC-Ops'),
(19, 'PC-Repots'),
(20, 'System Settings'),
(21, 'Employees'),
(22, 'HR-Ops'),
(23, 'HR-Reports');

-- --------------------------------------------------------

--
-- Table structure for table `sellthroughsupport`
--

CREATE TABLE `sellthroughsupport` (
  `id` int(11) NOT NULL,
  `supplierno` varchar(10) NOT NULL,
  `debtorno` varchar(10) NOT NULL DEFAULT '',
  `categoryid` char(6) NOT NULL DEFAULT '',
  `stockid` varchar(20) NOT NULL DEFAULT '',
  `narrative` varchar(20) NOT NULL DEFAULT '',
  `rebatepercent` double NOT NULL DEFAULT '0',
  `rebateamount` double NOT NULL DEFAULT '0',
  `effectivefrom` date NOT NULL,
  `effectiveto` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `shipmentcharges`
--

CREATE TABLE `shipmentcharges` (
  `shiptchgid` int(11) NOT NULL,
  `shiptref` int(11) NOT NULL DEFAULT '0',
  `transtype` smallint(6) NOT NULL DEFAULT '0',
  `transno` int(11) NOT NULL DEFAULT '0',
  `stockid` varchar(20) NOT NULL DEFAULT '',
  `value` double NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `shipments`
--

CREATE TABLE `shipments` (
  `shiptref` int(11) NOT NULL DEFAULT '0',
  `voyageref` varchar(20) NOT NULL DEFAULT '0',
  `vessel` varchar(50) NOT NULL DEFAULT '',
  `eta` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `accumvalue` double NOT NULL DEFAULT '0',
  `supplierid` varchar(10) NOT NULL DEFAULT '',
  `closed` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `shippers`
--

CREATE TABLE `shippers` (
  `shipper_id` int(11) NOT NULL,
  `shippername` char(40) NOT NULL DEFAULT '',
  `mincharge` double NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `shippers`
--

INSERT INTO `shippers` (`shipper_id`, `shippername`, `mincharge`) VALUES
(1, 'Default Shipper', 0);

-- --------------------------------------------------------

--
-- Table structure for table `stockcategory`
--

CREATE TABLE `stockcategory` (
  `categoryid` char(6) NOT NULL DEFAULT '',
  `categorydescription` char(20) NOT NULL DEFAULT '',
  `stocktype` char(1) NOT NULL DEFAULT 'F',
  `stockact` varchar(20) NOT NULL DEFAULT '0',
  `adjglact` varchar(20) NOT NULL DEFAULT '0',
  `issueglact` varchar(20) NOT NULL DEFAULT '0',
  `purchpricevaract` varchar(20) NOT NULL DEFAULT '80000',
  `materialuseagevarac` varchar(20) NOT NULL DEFAULT '80000',
  `wipact` varchar(20) NOT NULL DEFAULT '0',
  `defaulttaxcatid` tinyint(4) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `stockcategory`
--

INSERT INTO `stockcategory` (`categoryid`, `categorydescription`, `stocktype`, `stockact`, `adjglact`, `issueglact`, `purchpricevaract`, `materialuseagevarac`, `wipact`, `defaulttaxcatid`) VALUES
('1', 'Office Supplies', 'D', '1010', '1', '1', '1', '1', '1010', 1),
('2', 'Smart Phones', 'F', '1460', '5700', '5700', '5000', '5200', '1440', 6),
('3', 'Assembly Line', 'L', '5500', '5700', '5700', '5900', '5500', '1440', 2),
('4', 'Electronics', 'M', '1420', '5700', '5700', '5000', '5000', '1440', 1),
('5', 'Display', 'M', '1420', '5700', '5700', '5000', '5000', '1440', 1);

-- --------------------------------------------------------

--
-- Table structure for table `stockcatproperties`
--

CREATE TABLE `stockcatproperties` (
  `stkcatpropid` int(11) NOT NULL,
  `categoryid` char(6) NOT NULL,
  `label` text NOT NULL,
  `controltype` tinyint(4) NOT NULL DEFAULT '0',
  `defaultvalue` varchar(100) NOT NULL DEFAULT '''''',
  `maximumvalue` double NOT NULL DEFAULT '999999999',
  `reqatsalesorder` tinyint(4) NOT NULL DEFAULT '0',
  `minimumvalue` double NOT NULL DEFAULT '-999999999',
  `numericvalue` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `stockcatproperties`
--

INSERT INTO `stockcatproperties` (`stkcatpropid`, `categoryid`, `label`, `controltype`, `defaultvalue`, `maximumvalue`, `reqatsalesorder`, `minimumvalue`, `numericvalue`) VALUES
(1, '2', 'RAM', 0, '', 8, 0, 1, 1),
(2, '2', 'Memory', 1, '16,32,64,128,256', 256, 0, 1, 1),
(3, '2', 'Screen', 1, '4,5,6,7,8', 8, 0, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `stockcheckfreeze`
--

CREATE TABLE `stockcheckfreeze` (
  `stockid` varchar(20) NOT NULL DEFAULT '',
  `loccode` varchar(5) NOT NULL DEFAULT '',
  `qoh` double NOT NULL DEFAULT '0',
  `stockcheckdate` date NOT NULL DEFAULT '0000-00-00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `stockcounts`
--

CREATE TABLE `stockcounts` (
  `id` int(11) NOT NULL,
  `stockid` varchar(20) NOT NULL DEFAULT '',
  `loccode` varchar(5) NOT NULL DEFAULT '',
  `qtycounted` double NOT NULL DEFAULT '0',
  `reference` varchar(20) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `stockdescriptiontranslations`
--

CREATE TABLE `stockdescriptiontranslations` (
  `stockid` varchar(20) NOT NULL DEFAULT '',
  `language_id` varchar(10) NOT NULL DEFAULT 'en_GB.utf8',
  `descriptiontranslation` varchar(50) DEFAULT NULL COMMENT 'Item''s short description',
  `longdescriptiontranslation` text COMMENT 'Item''s long description',
  `needsrevision` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `stockitemproperties`
--

CREATE TABLE `stockitemproperties` (
  `stockid` varchar(20) NOT NULL,
  `stkcatpropid` int(11) NOT NULL,
  `value` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `stockitemproperties`
--

INSERT INTO `stockitemproperties` (`stockid`, `stkcatpropid`, `value`) VALUES
('S9', 3, '1'),
('S9', 2, '128'),
('S9', 1, '4');

-- --------------------------------------------------------

--
-- Table structure for table `stockmaster`
--

CREATE TABLE `stockmaster` (
  `stockid` varchar(20) NOT NULL DEFAULT '',
  `categoryid` varchar(6) NOT NULL DEFAULT '',
  `description` varchar(50) NOT NULL DEFAULT '',
  `longdescription` text NOT NULL,
  `hsn_code` varchar(20) NOT NULL,
  `units` varchar(20) NOT NULL DEFAULT 'each',
  `mbflag` char(1) NOT NULL DEFAULT 'B',
  `actualcost` decimal(20,4) NOT NULL DEFAULT '0.0000',
  `lastcost` decimal(20,4) NOT NULL DEFAULT '0.0000',
  `materialcost` decimal(20,4) NOT NULL DEFAULT '0.0000',
  `labourcost` decimal(20,4) NOT NULL DEFAULT '0.0000',
  `overheadcost` decimal(20,4) NOT NULL DEFAULT '0.0000',
  `lowestlevel` smallint(6) NOT NULL DEFAULT '0',
  `discontinued` tinyint(4) NOT NULL DEFAULT '0',
  `controlled` tinyint(4) NOT NULL DEFAULT '0',
  `eoq` double NOT NULL DEFAULT '0',
  `volume` decimal(20,4) NOT NULL DEFAULT '0.0000',
  `grossweight` decimal(20,4) NOT NULL DEFAULT '0.0000',
  `barcode` varchar(50) NOT NULL DEFAULT '',
  `discountcategory` char(2) NOT NULL DEFAULT '',
  `taxcatid` tinyint(4) NOT NULL DEFAULT '1',
  `serialised` tinyint(4) NOT NULL DEFAULT '0',
  `appendfile` varchar(40) NOT NULL DEFAULT 'none',
  `perishable` tinyint(1) NOT NULL DEFAULT '0',
  `decimalplaces` tinyint(4) NOT NULL DEFAULT '0',
  `pansize` double NOT NULL DEFAULT '0',
  `shrinkfactor` double NOT NULL DEFAULT '0',
  `nextserialno` bigint(20) NOT NULL DEFAULT '0',
  `netweight` decimal(20,4) NOT NULL DEFAULT '0.0000',
  `lastcostupdate` date NOT NULL DEFAULT '0000-00-00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `stockmaster`
--

INSERT INTO `stockmaster` (`stockid`, `categoryid`, `description`, `longdescription`, `hsn_code`, `units`, `mbflag`, `actualcost`, `lastcost`, `materialcost`, `labourcost`, `overheadcost`, `lowestlevel`, `discontinued`, `controlled`, `eoq`, `volume`, `grossweight`, `barcode`, `discountcategory`, `taxcatid`, `serialised`, `appendfile`, `perishable`, `decimalplaces`, `pansize`, `shrinkfactor`, `nextserialno`, `netweight`, `lastcostupdate`) VALUES
('HDD128', '4', 'HDD128', 'HDD128HDD128HDD128HDD128', '434234234', 'each', 'B', '0.0000', '0.0000', '2500.0000', '0.0000', '0.0000', 0, 0, 1, 0, '0.0000', '0.0000', '', '', 1, 1, 'none', 0, 0, 0, 0, 0, '0.0000', '2018-09-04'),
('RAM4', '4', '4GB RAM ', '4GB RAM 4GB RAM 4GB RAM 4GB RAM 4GB RAM ', '2314234', 'each', 'B', '0.0000', '0.0000', '1200.0000', '0.0000', '0.0000', 0, 0, 1, 10, '0.0000', '0.0000', '', '', 1, 1, 'none', 0, 0, 0, 0, 0, '0.0000', '2018-09-04'),
('S9', '2', 'Galaxy S9', '4 GB RAM,128GB HDD', 'adsfafasdfads', 'each', 'M', '0.0000', '0.0000', '5000.0000', '2500.0000', '5000.0000', 0, 0, 1, 0, '0.0000', '0.0000', '', '', 2, 1, 'none', 0, 0, 0, 0, 0, '0.0000', '2018-09-04'),
('SCREEN6', '5', 'Screen6', 'Screen6Screen6Screen6Screen6', '32423425432', 'each', 'B', '0.0000', '0.0000', '1000.0000', '0.0000', '0.0000', 0, 0, 1, 0, '0.0000', '0.0000', '', '', 1, 0, 'none', 0, 0, 0, 0, 0, '0.0000', '2018-09-04');

-- --------------------------------------------------------

--
-- Table structure for table `stockmoves`
--

CREATE TABLE `stockmoves` (
  `stkmoveno` int(11) NOT NULL,
  `stockid` varchar(20) NOT NULL DEFAULT '',
  `type` smallint(6) NOT NULL DEFAULT '0',
  `transno` int(11) NOT NULL DEFAULT '0',
  `loccode` varchar(5) NOT NULL DEFAULT '',
  `trandate` date NOT NULL DEFAULT '0000-00-00',
  `userid` varchar(20) NOT NULL,
  `debtorno` varchar(10) NOT NULL DEFAULT '',
  `branchcode` varchar(10) NOT NULL DEFAULT '',
  `price` decimal(21,5) NOT NULL DEFAULT '0.00000',
  `prd` smallint(6) NOT NULL DEFAULT '0',
  `reference` varchar(100) NOT NULL DEFAULT '',
  `qty` double NOT NULL DEFAULT '1',
  `discountpercent` double NOT NULL DEFAULT '0',
  `standardcost` double NOT NULL DEFAULT '0',
  `show_on_inv_crds` tinyint(4) NOT NULL DEFAULT '1',
  `newqoh` double NOT NULL DEFAULT '0',
  `hidemovt` tinyint(4) NOT NULL DEFAULT '0',
  `narrative` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `stockmovestaxes`
--

CREATE TABLE `stockmovestaxes` (
  `stkmoveno` int(11) NOT NULL DEFAULT '0',
  `taxauthid` tinyint(4) NOT NULL DEFAULT '0',
  `taxrate` double NOT NULL DEFAULT '0',
  `taxontax` tinyint(4) NOT NULL DEFAULT '0',
  `taxcalculationorder` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `stockrequest`
--

CREATE TABLE `stockrequest` (
  `dispatchid` int(11) NOT NULL,
  `loccode` varchar(5) NOT NULL DEFAULT '',
  `departmentid` int(11) NOT NULL DEFAULT '0',
  `despatchdate` date NOT NULL DEFAULT '0000-00-00',
  `authorised` tinyint(4) NOT NULL DEFAULT '0',
  `closed` tinyint(4) NOT NULL DEFAULT '0',
  `narrative` text NOT NULL,
  `initiator` varchar(20) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `stockrequestitems`
--

CREATE TABLE `stockrequestitems` (
  `dispatchitemsid` int(11) NOT NULL DEFAULT '0',
  `dispatchid` int(11) NOT NULL DEFAULT '0',
  `stockid` varchar(20) NOT NULL DEFAULT '',
  `quantity` double NOT NULL DEFAULT '0',
  `qtydelivered` double NOT NULL DEFAULT '0',
  `decimalplaces` int(11) NOT NULL DEFAULT '0',
  `uom` varchar(20) NOT NULL DEFAULT '',
  `completed` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `stockserialitems`
--

CREATE TABLE `stockserialitems` (
  `stockid` varchar(20) NOT NULL DEFAULT '',
  `loccode` varchar(5) NOT NULL DEFAULT '',
  `serialno` varchar(30) NOT NULL DEFAULT '',
  `expirationdate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `quantity` double NOT NULL DEFAULT '0',
  `qualitytext` text NOT NULL,
  `createdate` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `stockserialmoves`
--

CREATE TABLE `stockserialmoves` (
  `stkitmmoveno` int(11) NOT NULL,
  `stockmoveno` int(11) NOT NULL DEFAULT '0',
  `stockid` varchar(20) NOT NULL DEFAULT '',
  `serialno` varchar(30) NOT NULL DEFAULT '',
  `moveqty` double NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `suppallocs`
--

CREATE TABLE `suppallocs` (
  `id` int(11) NOT NULL,
  `amt` double NOT NULL DEFAULT '0',
  `datealloc` date NOT NULL DEFAULT '0000-00-00',
  `transid_allocfrom` int(11) NOT NULL DEFAULT '0',
  `transid_allocto` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `suppinvstogrn`
--

CREATE TABLE `suppinvstogrn` (
  `suppinv` int(11) NOT NULL,
  `grnno` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `suppliercontacts`
--

CREATE TABLE `suppliercontacts` (
  `supplierid` varchar(10) NOT NULL DEFAULT '',
  `contact` varchar(30) NOT NULL DEFAULT '',
  `position` varchar(30) NOT NULL DEFAULT '',
  `tel` varchar(30) NOT NULL DEFAULT '',
  `fax` varchar(30) NOT NULL DEFAULT '',
  `mobile` varchar(30) NOT NULL DEFAULT '',
  `email` varchar(55) NOT NULL DEFAULT '',
  `ordercontact` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `supplierdiscounts`
--

CREATE TABLE `supplierdiscounts` (
  `id` int(11) NOT NULL,
  `supplierno` varchar(10) NOT NULL,
  `stockid` varchar(20) NOT NULL,
  `discountnarrative` varchar(20) NOT NULL,
  `discountpercent` double NOT NULL,
  `discountamount` double NOT NULL,
  `effectivefrom` date NOT NULL,
  `effectiveto` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `supplierid` varchar(10) NOT NULL DEFAULT '',
  `suppname` varchar(40) NOT NULL DEFAULT '',
  `address1` varchar(40) NOT NULL DEFAULT '',
  `address2` varchar(40) NOT NULL DEFAULT '',
  `address3` varchar(40) NOT NULL DEFAULT '',
  `address4` varchar(50) NOT NULL DEFAULT '',
  `address5` varchar(20) NOT NULL DEFAULT '',
  `address6` varchar(40) NOT NULL DEFAULT '',
  `supptype` tinyint(4) NOT NULL DEFAULT '1',
  `lat` float(10,6) NOT NULL DEFAULT '0.000000',
  `lng` float(10,6) NOT NULL DEFAULT '0.000000',
  `currcode` char(3) NOT NULL DEFAULT '',
  `suppliersince` date NOT NULL DEFAULT '0000-00-00',
  `paymentterms` char(2) NOT NULL DEFAULT '',
  `lastpaid` double NOT NULL DEFAULT '0',
  `lastpaiddate` datetime DEFAULT NULL,
  `bankact` varchar(30) NOT NULL DEFAULT '',
  `bankref` varchar(12) NOT NULL DEFAULT '',
  `bankpartics` varchar(12) NOT NULL DEFAULT '',
  `remittance` tinyint(4) NOT NULL DEFAULT '1',
  `taxgroupid` tinyint(4) NOT NULL DEFAULT '1',
  `factorcompanyid` int(11) NOT NULL DEFAULT '1',
  `taxref` varchar(20) NOT NULL DEFAULT '',
  `phn` varchar(50) NOT NULL DEFAULT '',
  `port` varchar(200) NOT NULL DEFAULT '',
  `email` varchar(55) DEFAULT NULL,
  `fax` varchar(25) DEFAULT NULL,
  `telephone` varchar(25) DEFAULT NULL,
  `url` varchar(50) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`supplierid`, `suppname`, `address1`, `address2`, `address3`, `address4`, `address5`, `address6`, `supptype`, `lat`, `lng`, `currcode`, `suppliersince`, `paymentterms`, `lastpaid`, `lastpaiddate`, `bankact`, `bankref`, `bankpartics`, `remittance`, `taxgroupid`, `factorcompanyid`, `taxref`, `phn`, `port`, `email`, `fax`, `telephone`, `url`) VALUES
('1', 'Samsung Electronics', 'asdf', 'asdf', 'asdf', 'asdf', '2342343', 'India', 1, 0.000000, 0.000000, 'USD', '2018-09-03', '30', 0, NULL, '234143214214', '2341234123', 'ICICI', 1, 2, 0, 'SAFDDSFDS2344234ASD', '', '', 'dummy@dummy.com', '342343', '2423324', '');

-- --------------------------------------------------------

--
-- Table structure for table `suppliertype`
--

CREATE TABLE `suppliertype` (
  `typeid` tinyint(4) NOT NULL,
  `typename` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `suppliertype`
--

INSERT INTO `suppliertype` (`typeid`, `typename`) VALUES
(1, 'Stable'),
(2, 'Late Delivery');

-- --------------------------------------------------------

--
-- Table structure for table `supptrans`
--

CREATE TABLE `supptrans` (
  `transno` int(11) NOT NULL DEFAULT '0',
  `type` smallint(6) NOT NULL DEFAULT '0',
  `supplierno` varchar(10) NOT NULL DEFAULT '',
  `suppreference` varchar(20) NOT NULL DEFAULT '',
  `trandate` date NOT NULL DEFAULT '0000-00-00',
  `duedate` date NOT NULL DEFAULT '0000-00-00',
  `inputdate` datetime NOT NULL,
  `settled` tinyint(4) NOT NULL DEFAULT '0',
  `rate` double NOT NULL DEFAULT '1',
  `ovamount` double NOT NULL DEFAULT '0',
  `ovgst` double NOT NULL DEFAULT '0',
  `diffonexch` double NOT NULL DEFAULT '0',
  `alloc` double NOT NULL DEFAULT '0',
  `transtext` text,
  `hold` tinyint(4) NOT NULL DEFAULT '0',
  `chequeno` varchar(16) NOT NULL DEFAULT '',
  `void` tinyint(1) NOT NULL DEFAULT '0',
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `supptranstaxes`
--

CREATE TABLE `supptranstaxes` (
  `supptransid` int(11) NOT NULL DEFAULT '0',
  `taxauthid` tinyint(4) NOT NULL DEFAULT '0',
  `taxamount` double NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `systypes`
--

CREATE TABLE `systypes` (
  `typeid` smallint(6) NOT NULL DEFAULT '0',
  `typename` char(50) NOT NULL DEFAULT '',
  `typeno` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `systypes`
--

INSERT INTO `systypes` (`typeid`, `typename`, `typeno`) VALUES
(0, 'Journal - GL', 0),
(1, 'Payment - GL', 0),
(2, 'Receipt - GL', 0),
(3, 'Standing Journal', 0),
(10, 'Sales Invoice', 0),
(11, 'Credit Note', 0),
(12, 'Receipt', 0),
(15, 'Journal - Debtors', 0),
(16, 'Location Transfer', 0),
(17, 'Stock Adjustment', 0),
(18, 'Purchase Order', 0),
(19, 'Picking List', 0),
(20, 'Purchase Invoice', 0),
(21, 'Debit Note', 0),
(22, 'Creditors Payment', 0),
(23, 'Creditors Journal', 0),
(25, 'Purchase Order Delivery', 0),
(26, 'Work Order Receipt', 0),
(28, 'Work Order Issue', 0),
(29, 'Work Order Variance', 0),
(30, 'Sales Order', 1),
(31, 'Shipment Close', 0),
(32, 'Contract Close', 0),
(35, 'Cost Update', 0),
(36, 'Exchange Difference', 5),
(37, 'Tenders', 0),
(38, 'Stock Requests', 0),
(40, 'Work Order', 0),
(41, 'Asset Addition', 0),
(42, 'Asset Category Change', 0),
(43, 'Delete w/down asset', 0),
(44, 'Depreciation', 0),
(49, 'Import Fixed Assets', 0),
(50, 'Opening Balance', 0),
(500, 'Auto Debtor Number', 1),
(600, 'Auto Supplier Number', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE `tags` (
  `tagref` tinyint(4) NOT NULL,
  `tagdescription` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tags`
--

INSERT INTO `tags` (`tagref`, `tagdescription`) VALUES
(1, 'Sales'),
(2, 'Production');

-- --------------------------------------------------------

--
-- Table structure for table `taxauthorities`
--

CREATE TABLE `taxauthorities` (
  `taxid` tinyint(4) NOT NULL,
  `description` varchar(20) NOT NULL DEFAULT '',
  `taxglcode` varchar(20) NOT NULL DEFAULT '0',
  `purchtaxglaccount` varchar(20) NOT NULL DEFAULT '0',
  `bank` varchar(50) NOT NULL DEFAULT '',
  `bankacctype` varchar(20) NOT NULL DEFAULT '',
  `bankacc` varchar(50) NOT NULL DEFAULT '',
  `bankswift` varchar(30) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `taxauthorities`
--

INSERT INTO `taxauthorities` (`taxid`, `description`, `taxglcode`, `purchtaxglaccount`, `bank`, `bankacctype`, `bankacc`, `bankswift`) VALUES
(1, 'IGST', '2300', '2310', '', '', '', ''),
(5, 'GST', '2300', '2310', '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `taxauthrates`
--

CREATE TABLE `taxauthrates` (
  `taxauthority` tinyint(4) NOT NULL DEFAULT '1',
  `dispatchtaxprovince` tinyint(4) NOT NULL DEFAULT '1',
  `taxcatid` tinyint(4) NOT NULL DEFAULT '0',
  `taxrate` double NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `taxauthrates`
--

INSERT INTO `taxauthrates` (`taxauthority`, `dispatchtaxprovince`, `taxcatid`, `taxrate`) VALUES
(1, 1, 1, 0.05),
(1, 1, 2, 0.12),
(1, 1, 6, 0.18),
(1, 1, 7, 0.28),
(5, 1, 1, 0.05),
(5, 1, 2, 0.12),
(5, 1, 6, 0.18),
(5, 1, 7, 0.28);

-- --------------------------------------------------------

--
-- Table structure for table `taxcategories`
--

CREATE TABLE `taxcategories` (
  `taxcatid` tinyint(4) NOT NULL,
  `taxcatname` varchar(30) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `taxcategories`
--

INSERT INTO `taxcategories` (`taxcatid`, `taxcatname`) VALUES
(1, 'GST5'),
(2, 'GST12'),
(4, 'NIL'),
(6, 'GST18'),
(7, 'GST28');

-- --------------------------------------------------------

--
-- Table structure for table `taxgroups`
--

CREATE TABLE `taxgroups` (
  `taxgroupid` tinyint(4) NOT NULL,
  `taxgroupdescription` varchar(30) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `taxgroups`
--

INSERT INTO `taxgroups` (`taxgroupid`, `taxgroupdescription`) VALUES
(1, 'Inter State'),
(2, 'Intra State');

-- --------------------------------------------------------

--
-- Table structure for table `taxgrouptaxes`
--

CREATE TABLE `taxgrouptaxes` (
  `taxgroupid` tinyint(4) NOT NULL DEFAULT '0',
  `taxauthid` tinyint(4) NOT NULL DEFAULT '0',
  `calculationorder` tinyint(4) NOT NULL DEFAULT '0',
  `taxontax` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `taxgrouptaxes`
--

INSERT INTO `taxgrouptaxes` (`taxgroupid`, `taxauthid`, `calculationorder`, `taxontax`) VALUES
(1, 5, 0, 0),
(2, 1, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `taxprovinces`
--

CREATE TABLE `taxprovinces` (
  `taxprovinceid` tinyint(4) NOT NULL,
  `taxprovincename` varchar(30) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `taxprovinces`
--

INSERT INTO `taxprovinces` (`taxprovinceid`, `taxprovincename`) VALUES
(1, 'Default');

-- --------------------------------------------------------

--
-- Table structure for table `tenderitems`
--

CREATE TABLE `tenderitems` (
  `tenderid` int(11) NOT NULL DEFAULT '0',
  `stockid` varchar(20) NOT NULL DEFAULT '',
  `quantity` varchar(40) NOT NULL DEFAULT '',
  `units` varchar(20) NOT NULL DEFAULT 'each'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tenders`
--

CREATE TABLE `tenders` (
  `tenderid` int(11) NOT NULL DEFAULT '0',
  `location` varchar(5) NOT NULL DEFAULT '',
  `address1` varchar(40) NOT NULL DEFAULT '',
  `address2` varchar(40) NOT NULL DEFAULT '',
  `address3` varchar(40) NOT NULL DEFAULT '',
  `address4` varchar(40) NOT NULL DEFAULT '',
  `address5` varchar(20) NOT NULL DEFAULT '',
  `address6` varchar(15) NOT NULL DEFAULT '',
  `telephone` varchar(25) NOT NULL DEFAULT '',
  `closed` int(2) NOT NULL DEFAULT '0',
  `requiredbydate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tendersuppliers`
--

CREATE TABLE `tendersuppliers` (
  `tenderid` int(11) NOT NULL DEFAULT '0',
  `supplierid` varchar(10) NOT NULL DEFAULT '',
  `email` varchar(40) NOT NULL DEFAULT '',
  `responded` int(2) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `unitsofmeasure`
--

CREATE TABLE `unitsofmeasure` (
  `unitid` tinyint(4) NOT NULL,
  `unitname` varchar(15) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `unitsofmeasure`
--

INSERT INTO `unitsofmeasure` (`unitid`, `unitname`) VALUES
(1, 'each'),
(2, 'meters'),
(3, 'kgs'),
(4, 'litres'),
(5, 'length'),
(6, 'hours'),
(7, 'feet');

-- --------------------------------------------------------

--
-- Table structure for table `woitems`
--

CREATE TABLE `woitems` (
  `wo` int(11) NOT NULL,
  `stockid` char(20) NOT NULL DEFAULT '',
  `qtyreqd` double NOT NULL DEFAULT '1',
  `qtyrecd` double NOT NULL DEFAULT '0',
  `stdcost` double NOT NULL,
  `nextlotsnref` varchar(20) DEFAULT '',
  `comments` longblob
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `worequirements`
--

CREATE TABLE `worequirements` (
  `wo` int(11) NOT NULL,
  `parentstockid` varchar(20) NOT NULL,
  `stockid` varchar(20) NOT NULL,
  `qtypu` double NOT NULL DEFAULT '1',
  `stdcost` double NOT NULL DEFAULT '0',
  `autoissue` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `workcentres`
--

CREATE TABLE `workcentres` (
  `code` char(5) NOT NULL DEFAULT '',
  `location` char(5) NOT NULL DEFAULT '',
  `description` char(20) NOT NULL DEFAULT '',
  `capacity` double NOT NULL DEFAULT '1',
  `overheadperhour` decimal(10,0) NOT NULL DEFAULT '0',
  `overheadrecoveryact` varchar(20) NOT NULL DEFAULT '0',
  `setuphrs` decimal(10,0) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `workcentres`
--

INSERT INTO `workcentres` (`code`, `location`, `description`, `capacity`, `overheadperhour`, `overheadrecoveryact`, `setuphrs`) VALUES
('1s9', 'BAN', 'Assembly S9', 1, '100', '5000', '0');

-- --------------------------------------------------------

--
-- Table structure for table `workorders`
--

CREATE TABLE `workorders` (
  `wo` int(11) NOT NULL,
  `loccode` char(5) NOT NULL DEFAULT '',
  `requiredby` date NOT NULL DEFAULT '0000-00-00',
  `startdate` date NOT NULL DEFAULT '0000-00-00',
  `costissued` double NOT NULL DEFAULT '0',
  `closed` tinyint(4) NOT NULL DEFAULT '0',
  `closecomments` longblob,
  `reference` varchar(40) NOT NULL DEFAULT '',
  `remark` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `woserialnos`
--

CREATE TABLE `woserialnos` (
  `wo` int(11) NOT NULL,
  `stockid` varchar(20) NOT NULL,
  `serialno` varchar(30) NOT NULL,
  `quantity` double NOT NULL DEFAULT '1',
  `qualitytext` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `www_users`
--

CREATE TABLE `www_users` (
  `userid` varchar(20) NOT NULL DEFAULT '',
  `password` text NOT NULL,
  `realname` varchar(35) NOT NULL DEFAULT '',
  `customerid` varchar(10) NOT NULL DEFAULT '',
  `supplierid` varchar(10) NOT NULL DEFAULT '',
  `salesman` char(3) NOT NULL,
  `phone` varchar(30) NOT NULL DEFAULT '',
  `email` varchar(55) DEFAULT NULL,
  `defaultlocation` varchar(5) NOT NULL DEFAULT '',
  `fullaccess` int(11) NOT NULL DEFAULT '1',
  `cancreatetender` tinyint(1) NOT NULL DEFAULT '0',
  `lastvisitdate` datetime DEFAULT NULL,
  `branchcode` varchar(10) NOT NULL DEFAULT '',
  `pagesize` varchar(20) NOT NULL DEFAULT 'A4',
  `modulesallowed` varchar(25) NOT NULL,
  `showdashboard` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Display dashboard after login',
  `showpagehelp` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Turn off/on page help',
  `showfieldhelp` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Turn off/on field help',
  `blocked` tinyint(4) NOT NULL DEFAULT '0',
  `displayrecordsmax` int(11) NOT NULL DEFAULT '0',
  `theme` varchar(30) NOT NULL DEFAULT 'fresh',
  `language` varchar(10) NOT NULL DEFAULT 'en_GB.utf8',
  `pdflanguage` tinyint(1) NOT NULL DEFAULT '0',
  `department` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `www_users`
--

INSERT INTO `www_users` (`userid`, `password`, `realname`, `customerid`, `supplierid`, `salesman`, `phone`, `email`, `defaultlocation`, `fullaccess`, `cancreatetender`, `lastvisitdate`, `branchcode`, `pagesize`, `modulesallowed`, `showdashboard`, `showpagehelp`, `showfieldhelp`, `blocked`, `displayrecordsmax`, `theme`, `language`, `pdflanguage`, `department`) VALUES
('admin', '$2y$10$nzSdf/BWnM4Wkp2bk02zqOXJUBibgM8lA83UujOeOle.vFSjYm/aW', 'Super Admin', '', '', '', '', 'sales@netelity.com', 'BAN', 8, 1, '2018-10-01 10:30:27', '', 'A4', '1,1,1,1,1,1,1,1,1,1,1,1,', 1, 0, 0, 0, 50, 'fluid', 'en_IN.utf8', 0, 0),
('demo', '$2y$10$1f2JjAI.0NabFnT9mRxeX.bDC8HwxFPuz3boQXv8CEtqW.F72wbh.', 'Demo User', '', '', '', '', 'sales@netelity.com', 'BAN', 8, 1, '2018-09-18 13:52:58', '', 'A4', '1,1,1,1,1,1,1,1,1,1,1,0,', 1, 0, 0, 0, 50, 'fluid', 'en_IN.utf8', 0, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accountgroups`
--
ALTER TABLE `accountgroups`
  ADD PRIMARY KEY (`groupname`),
  ADD KEY `SequenceInTB` (`sequenceintb`),
  ADD KEY `sectioninaccounts` (`sectioninaccounts`),
  ADD KEY `parentgroupname` (`parentgroupname`);

--
-- Indexes for table `accountsection`
--
ALTER TABLE `accountsection`
  ADD PRIMARY KEY (`sectionid`);

--
-- Indexes for table `areas`
--
ALTER TABLE `areas`
  ADD PRIMARY KEY (`areacode`);

--
-- Indexes for table `assetmanager`
--
ALTER TABLE `assetmanager`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `audittrail`
--
ALTER TABLE `audittrail`
  ADD KEY `UserID` (`userid`),
  ADD KEY `transactiondate` (`transactiondate`),
  ADD KEY `transactiondate_2` (`transactiondate`),
  ADD KEY `transactiondate_3` (`transactiondate`);

--
-- Indexes for table `bankaccounts`
--
ALTER TABLE `bankaccounts`
  ADD PRIMARY KEY (`accountcode`),
  ADD KEY `currcode` (`currcode`),
  ADD KEY `BankAccountName` (`bankaccountname`),
  ADD KEY `BankAccountNumber` (`bankaccountnumber`);

--
-- Indexes for table `banktrans`
--
ALTER TABLE `banktrans`
  ADD PRIMARY KEY (`banktransid`),
  ADD KEY `BankAct` (`bankact`,`ref`),
  ADD KEY `TransDate` (`transdate`),
  ADD KEY `TransType` (`banktranstype`),
  ADD KEY `Type` (`type`,`transno`),
  ADD KEY `CurrCode` (`currcode`),
  ADD KEY `ref` (`ref`);

--
-- Indexes for table `bom`
--
ALTER TABLE `bom`
  ADD PRIMARY KEY (`parent`,`component`,`workcentreadded`,`loccode`),
  ADD KEY `Component` (`component`),
  ADD KEY `EffectiveAfter` (`effectiveafter`),
  ADD KEY `EffectiveTo` (`effectiveto`),
  ADD KEY `LocCode` (`loccode`),
  ADD KEY `Parent` (`parent`,`effectiveafter`,`effectiveto`,`loccode`),
  ADD KEY `Parent_2` (`parent`),
  ADD KEY `WorkCentreAdded` (`workcentreadded`);

--
-- Indexes for table `chartdetails`
--
ALTER TABLE `chartdetails`
  ADD PRIMARY KEY (`accountcode`,`period`),
  ADD KEY `Period` (`period`);

--
-- Indexes for table `chartmaster`
--
ALTER TABLE `chartmaster`
  ADD PRIMARY KEY (`accountcode`),
  ADD KEY `AccountName` (`accountname`),
  ADD KEY `Group_` (`group_`);

--
-- Indexes for table `cogsglpostings`
--
ALTER TABLE `cogsglpostings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `Area_StkCat` (`area`,`stkcat`,`salestype`),
  ADD KEY `Area` (`area`),
  ADD KEY `StkCat` (`stkcat`),
  ADD KEY `GLCode` (`glcode`),
  ADD KEY `SalesType` (`salestype`);

--
-- Indexes for table `companies`
--
ALTER TABLE `companies`
  ADD PRIMARY KEY (`coycode`);

--
-- Indexes for table `config`
--
ALTER TABLE `config`
  ADD PRIMARY KEY (`confname`);

--
-- Indexes for table `contractbom`
--
ALTER TABLE `contractbom`
  ADD PRIMARY KEY (`contractref`,`stockid`,`workcentreadded`),
  ADD KEY `Stockid` (`stockid`),
  ADD KEY `ContractRef` (`contractref`),
  ADD KEY `WorkCentreAdded` (`workcentreadded`);

--
-- Indexes for table `contractcharges`
--
ALTER TABLE `contractcharges`
  ADD PRIMARY KEY (`id`),
  ADD KEY `contractref` (`contractref`,`transtype`,`transno`),
  ADD KEY `contractcharges_ibfk_2` (`transtype`);

--
-- Indexes for table `contractreqts`
--
ALTER TABLE `contractreqts`
  ADD PRIMARY KEY (`contractreqid`),
  ADD KEY `ContractRef` (`contractref`);

--
-- Indexes for table `contracts`
--
ALTER TABLE `contracts`
  ADD PRIMARY KEY (`contractref`),
  ADD KEY `OrderNo` (`orderno`),
  ADD KEY `CategoryID` (`categoryid`),
  ADD KEY `Status` (`status`),
  ADD KEY `WO` (`wo`),
  ADD KEY `loccode` (`loccode`),
  ADD KEY `DebtorNo` (`debtorno`,`branchcode`);

--
-- Indexes for table `currencies`
--
ALTER TABLE `currencies`
  ADD PRIMARY KEY (`currabrev`),
  ADD KEY `Country` (`country`);

--
-- Indexes for table `custallocns`
--
ALTER TABLE `custallocns`
  ADD PRIMARY KEY (`id`),
  ADD KEY `DateAlloc` (`datealloc`),
  ADD KEY `TransID_AllocFrom` (`transid_allocfrom`),
  ADD KEY `TransID_AllocTo` (`transid_allocto`);

--
-- Indexes for table `custbranch`
--
ALTER TABLE `custbranch`
  ADD PRIMARY KEY (`branchcode`,`debtorno`),
  ADD KEY `BrName` (`brname`),
  ADD KEY `DebtorNo` (`debtorno`),
  ADD KEY `Salesman` (`salesman`),
  ADD KEY `Area` (`area`),
  ADD KEY `DefaultLocation` (`defaultlocation`),
  ADD KEY `DefaultShipVia` (`defaultshipvia`),
  ADD KEY `taxgroupid` (`taxgroupid`);

--
-- Indexes for table `custcontacts`
--
ALTER TABLE `custcontacts`
  ADD PRIMARY KEY (`contid`);

--
-- Indexes for table `custitem`
--
ALTER TABLE `custitem`
  ADD PRIMARY KEY (`debtorno`,`stockid`),
  ADD KEY `StockID` (`stockid`),
  ADD KEY `Debtorno` (`debtorno`);

--
-- Indexes for table `custnotes`
--
ALTER TABLE `custnotes`
  ADD PRIMARY KEY (`noteid`);

--
-- Indexes for table `customerwitholdings`
--
ALTER TABLE `customerwitholdings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `debtortransid` (`debtortransid`);

--
-- Indexes for table `debtorsmaster`
--
ALTER TABLE `debtorsmaster`
  ADD PRIMARY KEY (`debtorno`),
  ADD KEY `Currency` (`currcode`),
  ADD KEY `HoldReason` (`holdreason`),
  ADD KEY `Name` (`name`),
  ADD KEY `PaymentTerms` (`paymentterms`),
  ADD KEY `SalesType` (`salestype`),
  ADD KEY `EDIInvoices` (`ediinvoices`),
  ADD KEY `EDIOrders` (`ediorders`),
  ADD KEY `debtorsmaster_ibfk_5` (`typeid`);

--
-- Indexes for table `debtortrans`
--
ALTER TABLE `debtortrans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `DebtorNo` (`debtorno`,`branchcode`),
  ADD KEY `Order_` (`order_`),
  ADD KEY `Prd` (`prd`),
  ADD KEY `Tpe` (`tpe`),
  ADD KEY `Type` (`type`),
  ADD KEY `Settled` (`settled`),
  ADD KEY `TranDate` (`trandate`),
  ADD KEY `TransNo` (`transno`),
  ADD KEY `Type_2` (`type`,`transno`),
  ADD KEY `EDISent` (`edisent`),
  ADD KEY `salesperson` (`salesperson`);

--
-- Indexes for table `debtortranstaxes`
--
ALTER TABLE `debtortranstaxes`
  ADD PRIMARY KEY (`debtortransid`,`taxauthid`),
  ADD KEY `taxauthid` (`taxauthid`);

--
-- Indexes for table `debtortype`
--
ALTER TABLE `debtortype`
  ADD PRIMARY KEY (`typeid`);

--
-- Indexes for table `debtortypenotes`
--
ALTER TABLE `debtortypenotes`
  ADD PRIMARY KEY (`noteid`);

--
-- Indexes for table `deliverynotes`
--
ALTER TABLE `deliverynotes`
  ADD PRIMARY KEY (`deliverynotenumber`,`deliverynotelineno`),
  ADD KEY `deliverynotes_ibfk_2` (`salesorderno`,`salesorderlineno`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`departmentid`);

--
-- Indexes for table `discountmatrix`
--
ALTER TABLE `discountmatrix`
  ADD PRIMARY KEY (`salestype`,`discountcategory`,`quantitybreak`),
  ADD KEY `QuantityBreak` (`quantitybreak`),
  ADD KEY `DiscountCategory` (`discountcategory`),
  ADD KEY `SalesType` (`salestype`);

--
-- Indexes for table `ediitemmapping`
--
ALTER TABLE `ediitemmapping`
  ADD PRIMARY KEY (`supporcust`,`partnercode`,`stockid`),
  ADD KEY `PartnerCode` (`partnercode`),
  ADD KEY `StockID` (`stockid`),
  ADD KEY `PartnerStockID` (`partnerstockid`),
  ADD KEY `SuppOrCust` (`supporcust`);

--
-- Indexes for table `edimessageformat`
--
ALTER TABLE `edimessageformat`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `PartnerCode` (`partnercode`,`messagetype`,`sequenceno`),
  ADD KEY `Section` (`section`);

--
-- Indexes for table `edi_orders_segs`
--
ALTER TABLE `edi_orders_segs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `SegTag` (`segtag`),
  ADD KEY `SegNo` (`seggroup`);

--
-- Indexes for table `edi_orders_seg_groups`
--
ALTER TABLE `edi_orders_seg_groups`
  ADD PRIMARY KEY (`seggroupno`);

--
-- Indexes for table `emailsettings`
--
ALTER TABLE `emailsettings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `factorcompanies`
--
ALTER TABLE `factorcompanies`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `factor_name` (`coyname`);

--
-- Indexes for table `favourites`
--
ALTER TABLE `favourites`
  ADD PRIMARY KEY (`userid`,`caption`);

--
-- Indexes for table `fixedassetcategories`
--
ALTER TABLE `fixedassetcategories`
  ADD PRIMARY KEY (`categoryid`);

--
-- Indexes for table `fixedassetlocations`
--
ALTER TABLE `fixedassetlocations`
  ADD PRIMARY KEY (`locationid`);

--
-- Indexes for table `fixedassets`
--
ALTER TABLE `fixedassets`
  ADD PRIMARY KEY (`assetid`);

--
-- Indexes for table `fixedassettasks`
--
ALTER TABLE `fixedassettasks`
  ADD PRIMARY KEY (`taskid`),
  ADD KEY `assetid` (`assetid`),
  ADD KEY `userresponsible` (`userresponsible`);

--
-- Indexes for table `fixedassettrans`
--
ALTER TABLE `fixedassettrans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `assetid` (`assetid`,`transtype`,`transno`),
  ADD KEY `inputdate` (`inputdate`),
  ADD KEY `transdate` (`transdate`);

--
-- Indexes for table `freightcosts`
--
ALTER TABLE `freightcosts`
  ADD PRIMARY KEY (`shipcostfromid`),
  ADD KEY `Destination` (`destination`),
  ADD KEY `LocationFrom` (`locationfrom`),
  ADD KEY `ShipperID` (`shipperid`),
  ADD KEY `Destination_2` (`destination`,`locationfrom`,`shipperid`);

--
-- Indexes for table `geocode_param`
--
ALTER TABLE `geocode_param`
  ADD PRIMARY KEY (`geocodeid`);

--
-- Indexes for table `glaccountusers`
--
ALTER TABLE `glaccountusers`
  ADD UNIQUE KEY `useraccount` (`userid`,`accountcode`),
  ADD UNIQUE KEY `accountuser` (`accountcode`,`userid`);

--
-- Indexes for table `gltrans`
--
ALTER TABLE `gltrans`
  ADD PRIMARY KEY (`counterindex`),
  ADD KEY `Account` (`account`),
  ADD KEY `ChequeNo` (`chequeno`),
  ADD KEY `PeriodNo` (`periodno`),
  ADD KEY `Posted` (`posted`),
  ADD KEY `TranDate` (`trandate`),
  ADD KEY `TypeNo` (`typeno`),
  ADD KEY `Type_and_Number` (`type`,`typeno`),
  ADD KEY `JobRef` (`jobref`),
  ADD KEY `tag` (`tag`);

--
-- Indexes for table `grns`
--
ALTER TABLE `grns`
  ADD PRIMARY KEY (`grnno`),
  ADD KEY `DeliveryDate` (`deliverydate`),
  ADD KEY `ItemCode` (`itemcode`),
  ADD KEY `PODetailItem` (`podetailitem`),
  ADD KEY `SupplierID` (`supplierid`);

--
-- Indexes for table `holdreasons`
--
ALTER TABLE `holdreasons`
  ADD PRIMARY KEY (`reasoncode`),
  ADD KEY `ReasonDescription` (`reasondescription`);

--
-- Indexes for table `hremployeeattendanceregister`
--
ALTER TABLE `hremployeeattendanceregister`
  ADD PRIMARY KEY (`attendance_id`);

--
-- Indexes for table `hremployeecategories`
--
ALTER TABLE `hremployeecategories`
  ADD PRIMARY KEY (`employee_category_id`);

--
-- Indexes for table `hremployeegradings`
--
ALTER TABLE `hremployeegradings`
  ADD PRIMARY KEY (`employee_grading_id`);

--
-- Indexes for table `hremployeeleavegroups`
--
ALTER TABLE `hremployeeleavegroups`
  ADD PRIMARY KEY (`leavegroup_id`);

--
-- Indexes for table `hremployeeleaves`
--
ALTER TABLE `hremployeeleaves`
  ADD PRIMARY KEY (`employee_leave_id`);

--
-- Indexes for table `hremployeeleavetypes`
--
ALTER TABLE `hremployeeleavetypes`
  ADD PRIMARY KEY (`hrleavetype_id`);

--
-- Indexes for table `hremployeeloanpayments`
--
ALTER TABLE `hremployeeloanpayments`
  ADD PRIMARY KEY (`loan_payment_id`),
  ADD KEY `loan_id_payment` (`loan_id`);

--
-- Indexes for table `hremployeeloans`
--
ALTER TABLE `hremployeeloans`
  ADD PRIMARY KEY (`loan_id`);

--
-- Indexes for table `hremployeeloantypes`
--
ALTER TABLE `hremployeeloantypes`
  ADD PRIMARY KEY (`loan_type_id`);

--
-- Indexes for table `hremployeepayslips`
--
ALTER TABLE `hremployeepayslips`
  ADD PRIMARY KEY (`payslip_id`),
  ADD UNIQUE KEY `employee_id` (`employee_id`,`payslip_date_range_id`),
  ADD KEY `payslip_date_range_id` (`payslip_date_range_id`);

--
-- Indexes for table `hremployeepositions`
--
ALTER TABLE `hremployeepositions`
  ADD PRIMARY KEY (`employee_position_id`);

--
-- Indexes for table `hremployees`
--
ALTER TABLE `hremployees`
  ADD PRIMARY KEY (`empid`),
  ADD UNIQUE KEY `employee_id` (`employee_id`),
  ADD KEY `employee_department` (`employee_department`),
  ADD KEY `employee_position` (`employee_position`);

--
-- Indexes for table `hremployeesalarystructure_components`
--
ALTER TABLE `hremployeesalarystructure_components`
  ADD PRIMARY KEY (`component_id`),
  ADD KEY `salary_structure_id_component` (`salary_structure_id`);

--
-- Indexes for table `hrpaymentfrequency`
--
ALTER TABLE `hrpaymentfrequency`
  ADD PRIMARY KEY (`paymentfrequency_id`);

--
-- Indexes for table `hrpayrollcategories`
--
ALTER TABLE `hrpayrollcategories`
  ADD PRIMARY KEY (`payroll_category_id`);

--
-- Indexes for table `hrpayrollgroups`
--
ALTER TABLE `hrpayrollgroups`
  ADD PRIMARY KEY (`payrollgroup_id`),
  ADD KEY `payment_frequency` (`payment_frequency`);

--
-- Indexes for table `hrpayroll_groups_payroll_categories`
--
ALTER TABLE `hrpayroll_groups_payroll_categories`
  ADD PRIMARY KEY (`groups_categories_id`),
  ADD KEY `payroll_category_id` (`payroll_category_id`),
  ADD KEY `payroll_group_id` (`payroll_group_id`);

--
-- Indexes for table `hrpayslipcategorydetails`
--
ALTER TABLE `hrpayslipcategorydetails`
  ADD PRIMARY KEY (`detail_id`),
  ADD KEY `payslip_id` (`payslip_id`);

--
-- Indexes for table `hrpayslipdateranges`
--
ALTER TABLE `hrpayslipdateranges`
  ADD PRIMARY KEY (`daterange_id`);

--
-- Indexes for table `hrpayslipextradetails`
--
ALTER TABLE `hrpayslipextradetails`
  ADD PRIMARY KEY (`extra_payslip_id`),
  ADD KEY `payslip_id` (`payslip_id`);

--
-- Indexes for table `internalstockcatrole`
--
ALTER TABLE `internalstockcatrole`
  ADD PRIMARY KEY (`categoryid`,`secroleid`),
  ADD KEY `internalstockcatrole_ibfk_1` (`categoryid`),
  ADD KEY `internalstockcatrole_ibfk_2` (`secroleid`);

--
-- Indexes for table `labelfields`
--
ALTER TABLE `labelfields`
  ADD PRIMARY KEY (`labelfieldid`),
  ADD KEY `labelid` (`labelid`),
  ADD KEY `vpos` (`vpos`);

--
-- Indexes for table `labels`
--
ALTER TABLE `labels`
  ADD PRIMARY KEY (`labelid`);

--
-- Indexes for table `locations`
--
ALTER TABLE `locations`
  ADD PRIMARY KEY (`loccode`),
  ADD UNIQUE KEY `locationname` (`locationname`),
  ADD KEY `taxprovinceid` (`taxprovinceid`);

--
-- Indexes for table `locationusers`
--
ALTER TABLE `locationusers`
  ADD PRIMARY KEY (`loccode`,`userid`),
  ADD KEY `UserId` (`userid`);

--
-- Indexes for table `locstock`
--
ALTER TABLE `locstock`
  ADD PRIMARY KEY (`loccode`,`stockid`),
  ADD KEY `StockID` (`stockid`),
  ADD KEY `bin` (`bin`);

--
-- Indexes for table `loctransfercancellations`
--
ALTER TABLE `loctransfercancellations`
  ADD KEY `Index1` (`reference`,`stockid`),
  ADD KEY `Index2` (`canceldate`,`reference`,`stockid`),
  ADD KEY `refstockid` (`reference`,`stockid`),
  ADD KEY `cancelrefstockid` (`canceldate`,`reference`,`stockid`);

--
-- Indexes for table `loctransfers`
--
ALTER TABLE `loctransfers`
  ADD KEY `Reference` (`reference`,`stockid`),
  ADD KEY `ShipLoc` (`shiploc`),
  ADD KEY `RecLoc` (`recloc`),
  ADD KEY `StockID` (`stockid`);

--
-- Indexes for table `mailgroupdetails`
--
ALTER TABLE `mailgroupdetails`
  ADD KEY `userid` (`userid`),
  ADD KEY `groupname` (`groupname`);

--
-- Indexes for table `mailgroups`
--
ALTER TABLE `mailgroups`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `groupname` (`groupname`);

--
-- Indexes for table `manufacturers`
--
ALTER TABLE `manufacturers`
  ADD PRIMARY KEY (`manufacturers_id`),
  ADD KEY `manufacturers_name` (`manufacturers_name`);

--
-- Indexes for table `mrpcalendar`
--
ALTER TABLE `mrpcalendar`
  ADD PRIMARY KEY (`calendardate`),
  ADD KEY `daynumber` (`daynumber`);

--
-- Indexes for table `mrpdemands`
--
ALTER TABLE `mrpdemands`
  ADD PRIMARY KEY (`demandid`),
  ADD KEY `StockID` (`stockid`),
  ADD KEY `mrpdemands_ibfk_1` (`mrpdemandtype`);

--
-- Indexes for table `mrpdemandtypes`
--
ALTER TABLE `mrpdemandtypes`
  ADD PRIMARY KEY (`mrpdemandtype`);

--
-- Indexes for table `mrpplannedorders`
--
ALTER TABLE `mrpplannedorders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `offers`
--
ALTER TABLE `offers`
  ADD PRIMARY KEY (`offerid`),
  ADD KEY `offers_ibfk_1` (`supplierid`),
  ADD KEY `offers_ibfk_2` (`stockid`);

--
-- Indexes for table `orderdeliverydifferenceslog`
--
ALTER TABLE `orderdeliverydifferenceslog`
  ADD KEY `StockID` (`stockid`),
  ADD KEY `DebtorNo` (`debtorno`,`branch`),
  ADD KEY `Can_or_BO` (`can_or_bo`),
  ADD KEY `OrderNo` (`orderno`);

--
-- Indexes for table `paymentmethods`
--
ALTER TABLE `paymentmethods`
  ADD PRIMARY KEY (`paymentid`);

--
-- Indexes for table `paymentterms`
--
ALTER TABLE `paymentterms`
  ADD PRIMARY KEY (`termsindicator`),
  ADD KEY `DaysBeforeDue` (`daysbeforedue`),
  ADD KEY `DayInFollowingMonth` (`dayinfollowingmonth`);

--
-- Indexes for table `pcashdetails`
--
ALTER TABLE `pcashdetails`
  ADD PRIMARY KEY (`counterindex`),
  ADD UNIQUE KEY `tabcodedate` (`tabcode`,`date`,`codeexpense`,`counterindex`);

--
-- Indexes for table `pcashdetailtaxes`
--
ALTER TABLE `pcashdetailtaxes`
  ADD PRIMARY KEY (`counterindex`);

--
-- Indexes for table `pcexpenses`
--
ALTER TABLE `pcexpenses`
  ADD PRIMARY KEY (`codeexpense`),
  ADD KEY `glaccount` (`glaccount`);

--
-- Indexes for table `pcreceipts`
--
ALTER TABLE `pcreceipts`
  ADD PRIMARY KEY (`counterindex`),
  ADD KEY `pcreceipts_ibfk_1` (`pccashdetail`);

--
-- Indexes for table `pctabexpenses`
--
ALTER TABLE `pctabexpenses`
  ADD KEY `typetabcode` (`typetabcode`),
  ADD KEY `codeexpense` (`codeexpense`);

--
-- Indexes for table `pctabs`
--
ALTER TABLE `pctabs`
  ADD PRIMARY KEY (`tabcode`),
  ADD KEY `usercode` (`usercode`),
  ADD KEY `typetabcode` (`typetabcode`),
  ADD KEY `currency` (`currency`),
  ADD KEY `authorizer` (`authorizer`),
  ADD KEY `glaccountassignment` (`glaccountassignment`);

--
-- Indexes for table `pctypetabs`
--
ALTER TABLE `pctypetabs`
  ADD PRIMARY KEY (`typetabcode`);

--
-- Indexes for table `periods`
--
ALTER TABLE `periods`
  ADD PRIMARY KEY (`periodno`),
  ADD KEY `LastDate_in_Period` (`lastdate_in_period`);

--
-- Indexes for table `pickinglistdetails`
--
ALTER TABLE `pickinglistdetails`
  ADD PRIMARY KEY (`pickinglistno`,`pickinglistlineno`);

--
-- Indexes for table `pickinglists`
--
ALTER TABLE `pickinglists`
  ADD PRIMARY KEY (`pickinglistno`),
  ADD KEY `pickinglists_ibfk_1` (`orderno`);

--
-- Indexes for table `pickreq`
--
ALTER TABLE `pickreq`
  ADD PRIMARY KEY (`prid`),
  ADD KEY `orderno` (`orderno`),
  ADD KEY `requestdate` (`requestdate`),
  ADD KEY `shipdate` (`shipdate`),
  ADD KEY `status` (`status`),
  ADD KEY `closed` (`closed`),
  ADD KEY `loccode` (`loccode`);

--
-- Indexes for table `pickreqdetails`
--
ALTER TABLE `pickreqdetails`
  ADD PRIMARY KEY (`detailno`),
  ADD KEY `prid` (`prid`),
  ADD KEY `stockid` (`stockid`);

--
-- Indexes for table `pickserialdetails`
--
ALTER TABLE `pickserialdetails`
  ADD PRIMARY KEY (`serialmoveid`),
  ADD KEY `detailno` (`detailno`),
  ADD KEY `stockid` (`stockid`,`serialno`),
  ADD KEY `serialno` (`serialno`);

--
-- Indexes for table `pricematrix`
--
ALTER TABLE `pricematrix`
  ADD PRIMARY KEY (`salestype`,`stockid`,`currabrev`,`quantitybreak`,`startdate`,`enddate`),
  ADD KEY `SalesType` (`salestype`),
  ADD KEY `currabrev` (`currabrev`),
  ADD KEY `stockid` (`stockid`);

--
-- Indexes for table `prices`
--
ALTER TABLE `prices`
  ADD PRIMARY KEY (`stockid`,`typeabbrev`,`currabrev`,`debtorno`,`branchcode`,`startdate`,`enddate`),
  ADD KEY `CurrAbrev` (`currabrev`),
  ADD KEY `DebtorNo` (`debtorno`),
  ADD KEY `StockID` (`stockid`),
  ADD KEY `TypeAbbrev` (`typeabbrev`);

--
-- Indexes for table `prodspecs`
--
ALTER TABLE `prodspecs`
  ADD PRIMARY KEY (`keyval`,`testid`),
  ADD KEY `testid` (`testid`);

--
-- Indexes for table `purchdata`
--
ALTER TABLE `purchdata`
  ADD PRIMARY KEY (`supplierno`,`stockid`,`effectivefrom`),
  ADD KEY `StockID` (`stockid`),
  ADD KEY `SupplierNo` (`supplierno`),
  ADD KEY `Preferred` (`preferred`);

--
-- Indexes for table `purchorderauth`
--
ALTER TABLE `purchorderauth`
  ADD PRIMARY KEY (`userid`,`currabrev`);

--
-- Indexes for table `purchorderdetails`
--
ALTER TABLE `purchorderdetails`
  ADD PRIMARY KEY (`podetailitem`),
  ADD KEY `DeliveryDate` (`deliverydate`),
  ADD KEY `GLCode` (`glcode`),
  ADD KEY `ItemCode` (`itemcode`),
  ADD KEY `JobRef` (`jobref`),
  ADD KEY `OrderNo` (`orderno`),
  ADD KEY `ShiptRef` (`shiptref`),
  ADD KEY `Completed` (`completed`);

--
-- Indexes for table `purchorders`
--
ALTER TABLE `purchorders`
  ADD PRIMARY KEY (`orderno`),
  ADD KEY `OrdDate` (`orddate`),
  ADD KEY `SupplierNo` (`supplierno`),
  ADD KEY `IntoStockLocation` (`intostocklocation`),
  ADD KEY `AllowPrintPO` (`allowprint`);

--
-- Indexes for table `qasamples`
--
ALTER TABLE `qasamples`
  ADD PRIMARY KEY (`sampleid`),
  ADD KEY `prodspeckey` (`prodspeckey`,`lotkey`);

--
-- Indexes for table `qatests`
--
ALTER TABLE `qatests`
  ADD PRIMARY KEY (`testid`),
  ADD KEY `name` (`name`),
  ADD KEY `groupname` (`groupby`,`name`);

--
-- Indexes for table `recurringsalesorders`
--
ALTER TABLE `recurringsalesorders`
  ADD PRIMARY KEY (`recurrorderno`),
  ADD KEY `debtorno` (`debtorno`),
  ADD KEY `orddate` (`orddate`),
  ADD KEY `ordertype` (`ordertype`),
  ADD KEY `locationindex` (`fromstkloc`),
  ADD KEY `branchcode` (`branchcode`,`debtorno`);

--
-- Indexes for table `recurrsalesorderdetails`
--
ALTER TABLE `recurrsalesorderdetails`
  ADD KEY `orderno` (`recurrorderno`),
  ADD KEY `stkcode` (`stkcode`);

--
-- Indexes for table `relateditems`
--
ALTER TABLE `relateditems`
  ADD PRIMARY KEY (`stockid`,`related`),
  ADD UNIQUE KEY `Related` (`related`,`stockid`);

--
-- Indexes for table `reportcolumns`
--
ALTER TABLE `reportcolumns`
  ADD PRIMARY KEY (`reportid`,`colno`);

--
-- Indexes for table `reportfields`
--
ALTER TABLE `reportfields`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reportid` (`reportid`);

--
-- Indexes for table `reportheaders`
--
ALTER TABLE `reportheaders`
  ADD PRIMARY KEY (`reportid`),
  ADD KEY `ReportHeading` (`reportheading`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `name` (`reportname`,`groupname`);

--
-- Indexes for table `salesanalysis`
--
ALTER TABLE `salesanalysis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `CustBranch` (`custbranch`),
  ADD KEY `Cust` (`cust`),
  ADD KEY `PeriodNo` (`periodno`),
  ADD KEY `StkCategory` (`stkcategory`),
  ADD KEY `StockID` (`stockid`),
  ADD KEY `TypeAbbrev` (`typeabbrev`),
  ADD KEY `Area` (`area`),
  ADD KEY `BudgetOrActual` (`budgetoractual`),
  ADD KEY `Salesperson` (`salesperson`);

--
-- Indexes for table `salescat`
--
ALTER TABLE `salescat`
  ADD PRIMARY KEY (`salescatid`);

--
-- Indexes for table `salescatprod`
--
ALTER TABLE `salescatprod`
  ADD PRIMARY KEY (`salescatid`,`stockid`),
  ADD KEY `salescatid` (`salescatid`),
  ADD KEY `stockid` (`stockid`),
  ADD KEY `manufacturer_id` (`manufacturers_id`);

--
-- Indexes for table `salescattranslations`
--
ALTER TABLE `salescattranslations`
  ADD PRIMARY KEY (`salescatid`,`language_id`);

--
-- Indexes for table `salesglpostings`
--
ALTER TABLE `salesglpostings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `Area_StkCat` (`area`,`stkcat`,`salestype`),
  ADD KEY `Area` (`area`),
  ADD KEY `StkCat` (`stkcat`),
  ADD KEY `SalesType` (`salestype`);

--
-- Indexes for table `salesman`
--
ALTER TABLE `salesman`
  ADD PRIMARY KEY (`salesmancode`);

--
-- Indexes for table `salesorderdetails`
--
ALTER TABLE `salesorderdetails`
  ADD PRIMARY KEY (`orderlineno`,`orderno`),
  ADD KEY `OrderNo` (`orderno`),
  ADD KEY `StkCode` (`stkcode`),
  ADD KEY `Completed` (`completed`);

--
-- Indexes for table `salesorders`
--
ALTER TABLE `salesorders`
  ADD PRIMARY KEY (`orderno`),
  ADD KEY `DebtorNo` (`debtorno`),
  ADD KEY `OrdDate` (`orddate`),
  ADD KEY `OrderType` (`ordertype`),
  ADD KEY `LocationIndex` (`fromstkloc`),
  ADD KEY `BranchCode` (`branchcode`,`debtorno`),
  ADD KEY `ShipVia` (`shipvia`),
  ADD KEY `quotation` (`quotation`),
  ADD KEY `poplaced` (`poplaced`),
  ADD KEY `salesperson` (`salesperson`);

--
-- Indexes for table `salestypes`
--
ALTER TABLE `salestypes`
  ADD PRIMARY KEY (`typeabbrev`),
  ADD KEY `Sales_Type` (`sales_type`);

--
-- Indexes for table `sampleresults`
--
ALTER TABLE `sampleresults`
  ADD PRIMARY KEY (`resultid`),
  ADD KEY `sampleid` (`sampleid`),
  ADD KEY `testid` (`testid`);

--
-- Indexes for table `scripts`
--
ALTER TABLE `scripts`
  ADD PRIMARY KEY (`script`);

--
-- Indexes for table `securitygroups`
--
ALTER TABLE `securitygroups`
  ADD PRIMARY KEY (`secroleid`,`tokenid`),
  ADD KEY `secroleid` (`secroleid`),
  ADD KEY `tokenid` (`tokenid`);

--
-- Indexes for table `securityroles`
--
ALTER TABLE `securityroles`
  ADD PRIMARY KEY (`secroleid`);

--
-- Indexes for table `securitytokens`
--
ALTER TABLE `securitytokens`
  ADD PRIMARY KEY (`tokenid`);

--
-- Indexes for table `sellthroughsupport`
--
ALTER TABLE `sellthroughsupport`
  ADD PRIMARY KEY (`id`),
  ADD KEY `supplierno` (`supplierno`),
  ADD KEY `debtorno` (`debtorno`),
  ADD KEY `effectivefrom` (`effectivefrom`),
  ADD KEY `effectiveto` (`effectiveto`),
  ADD KEY `stockid` (`stockid`),
  ADD KEY `categoryid` (`categoryid`);

--
-- Indexes for table `shipmentcharges`
--
ALTER TABLE `shipmentcharges`
  ADD PRIMARY KEY (`shiptchgid`),
  ADD KEY `TransType` (`transtype`,`transno`),
  ADD KEY `ShiptRef` (`shiptref`),
  ADD KEY `StockID` (`stockid`),
  ADD KEY `TransType_2` (`transtype`);

--
-- Indexes for table `shipments`
--
ALTER TABLE `shipments`
  ADD PRIMARY KEY (`shiptref`),
  ADD KEY `ETA` (`eta`),
  ADD KEY `SupplierID` (`supplierid`),
  ADD KEY `ShipperRef` (`voyageref`),
  ADD KEY `Vessel` (`vessel`);

--
-- Indexes for table `shippers`
--
ALTER TABLE `shippers`
  ADD PRIMARY KEY (`shipper_id`);

--
-- Indexes for table `stockcategory`
--
ALTER TABLE `stockcategory`
  ADD PRIMARY KEY (`categoryid`),
  ADD KEY `CategoryDescription` (`categorydescription`),
  ADD KEY `StockType` (`stocktype`);

--
-- Indexes for table `stockcatproperties`
--
ALTER TABLE `stockcatproperties`
  ADD PRIMARY KEY (`stkcatpropid`),
  ADD KEY `categoryid` (`categoryid`);

--
-- Indexes for table `stockcheckfreeze`
--
ALTER TABLE `stockcheckfreeze`
  ADD PRIMARY KEY (`stockid`,`loccode`),
  ADD KEY `LocCode` (`loccode`);

--
-- Indexes for table `stockcounts`
--
ALTER TABLE `stockcounts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `StockID` (`stockid`),
  ADD KEY `LocCode` (`loccode`);

--
-- Indexes for table `stockdescriptiontranslations`
--
ALTER TABLE `stockdescriptiontranslations`
  ADD PRIMARY KEY (`stockid`,`language_id`);

--
-- Indexes for table `stockitemproperties`
--
ALTER TABLE `stockitemproperties`
  ADD PRIMARY KEY (`stockid`,`stkcatpropid`),
  ADD KEY `stockid` (`stockid`),
  ADD KEY `value` (`value`),
  ADD KEY `stkcatpropid` (`stkcatpropid`);

--
-- Indexes for table `stockmaster`
--
ALTER TABLE `stockmaster`
  ADD PRIMARY KEY (`stockid`),
  ADD KEY `CategoryID` (`categoryid`),
  ADD KEY `Description` (`description`),
  ADD KEY `MBflag` (`mbflag`),
  ADD KEY `StockID` (`stockid`,`categoryid`),
  ADD KEY `Controlled` (`controlled`),
  ADD KEY `DiscountCategory` (`discountcategory`),
  ADD KEY `taxcatid` (`taxcatid`);

--
-- Indexes for table `stockmoves`
--
ALTER TABLE `stockmoves`
  ADD PRIMARY KEY (`stkmoveno`),
  ADD KEY `DebtorNo` (`debtorno`),
  ADD KEY `LocCode` (`loccode`),
  ADD KEY `Prd` (`prd`),
  ADD KEY `StockID_2` (`stockid`),
  ADD KEY `TranDate` (`trandate`),
  ADD KEY `TransNo` (`transno`),
  ADD KEY `Type` (`type`),
  ADD KEY `Show_On_Inv_Crds` (`show_on_inv_crds`),
  ADD KEY `Hide` (`hidemovt`),
  ADD KEY `reference` (`reference`);

--
-- Indexes for table `stockmovestaxes`
--
ALTER TABLE `stockmovestaxes`
  ADD PRIMARY KEY (`stkmoveno`,`taxauthid`),
  ADD KEY `taxauthid` (`taxauthid`),
  ADD KEY `calculationorder` (`taxcalculationorder`);

--
-- Indexes for table `stockrequest`
--
ALTER TABLE `stockrequest`
  ADD PRIMARY KEY (`dispatchid`),
  ADD KEY `loccode` (`loccode`),
  ADD KEY `departmentid` (`departmentid`);

--
-- Indexes for table `stockrequestitems`
--
ALTER TABLE `stockrequestitems`
  ADD PRIMARY KEY (`dispatchitemsid`,`dispatchid`),
  ADD KEY `dispatchid` (`dispatchid`),
  ADD KEY `stockid` (`stockid`);

--
-- Indexes for table `stockserialitems`
--
ALTER TABLE `stockserialitems`
  ADD PRIMARY KEY (`stockid`,`serialno`,`loccode`),
  ADD KEY `StockID` (`stockid`),
  ADD KEY `LocCode` (`loccode`),
  ADD KEY `serialno` (`serialno`),
  ADD KEY `createdate` (`createdate`);

--
-- Indexes for table `stockserialmoves`
--
ALTER TABLE `stockserialmoves`
  ADD PRIMARY KEY (`stkitmmoveno`),
  ADD KEY `StockMoveNo` (`stockmoveno`),
  ADD KEY `StockID_SN` (`stockid`,`serialno`),
  ADD KEY `serialno` (`serialno`);

--
-- Indexes for table `suppallocs`
--
ALTER TABLE `suppallocs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `TransID_AllocFrom` (`transid_allocfrom`),
  ADD KEY `TransID_AllocTo` (`transid_allocto`),
  ADD KEY `DateAlloc` (`datealloc`);

--
-- Indexes for table `suppinvstogrn`
--
ALTER TABLE `suppinvstogrn`
  ADD PRIMARY KEY (`suppinv`,`grnno`),
  ADD KEY `suppinvstogrn_ibfk_1` (`grnno`);

--
-- Indexes for table `suppliercontacts`
--
ALTER TABLE `suppliercontacts`
  ADD PRIMARY KEY (`supplierid`,`contact`),
  ADD KEY `Contact` (`contact`),
  ADD KEY `SupplierID` (`supplierid`);

--
-- Indexes for table `supplierdiscounts`
--
ALTER TABLE `supplierdiscounts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `supplierno` (`supplierno`),
  ADD KEY `effectivefrom` (`effectivefrom`),
  ADD KEY `effectiveto` (`effectiveto`),
  ADD KEY `stockid` (`stockid`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`supplierid`),
  ADD KEY `CurrCode` (`currcode`),
  ADD KEY `PaymentTerms` (`paymentterms`),
  ADD KEY `SuppName` (`suppname`),
  ADD KEY `taxgroupid` (`taxgroupid`);

--
-- Indexes for table `suppliertype`
--
ALTER TABLE `suppliertype`
  ADD PRIMARY KEY (`typeid`);

--
-- Indexes for table `supptrans`
--
ALTER TABLE `supptrans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `DueDate` (`duedate`),
  ADD KEY `Hold` (`hold`),
  ADD KEY `SupplierNo` (`supplierno`),
  ADD KEY `Settled` (`settled`),
  ADD KEY `SupplierNo_2` (`supplierno`,`suppreference`),
  ADD KEY `SuppReference` (`suppreference`),
  ADD KEY `TranDate` (`trandate`),
  ADD KEY `TransNo` (`transno`),
  ADD KEY `Type` (`type`),
  ADD KEY `TypeTransNo` (`transno`,`type`);

--
-- Indexes for table `supptranstaxes`
--
ALTER TABLE `supptranstaxes`
  ADD PRIMARY KEY (`supptransid`,`taxauthid`),
  ADD KEY `taxauthid` (`taxauthid`);

--
-- Indexes for table `systypes`
--
ALTER TABLE `systypes`
  ADD PRIMARY KEY (`typeid`),
  ADD KEY `TypeNo` (`typeno`);

--
-- Indexes for table `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`tagref`);

--
-- Indexes for table `taxauthorities`
--
ALTER TABLE `taxauthorities`
  ADD PRIMARY KEY (`taxid`),
  ADD KEY `TaxGLCode` (`taxglcode`),
  ADD KEY `PurchTaxGLAccount` (`purchtaxglaccount`);

--
-- Indexes for table `taxauthrates`
--
ALTER TABLE `taxauthrates`
  ADD PRIMARY KEY (`taxauthority`,`dispatchtaxprovince`,`taxcatid`),
  ADD KEY `TaxAuthority` (`taxauthority`),
  ADD KEY `dispatchtaxprovince` (`dispatchtaxprovince`),
  ADD KEY `taxcatid` (`taxcatid`);

--
-- Indexes for table `taxcategories`
--
ALTER TABLE `taxcategories`
  ADD PRIMARY KEY (`taxcatid`);

--
-- Indexes for table `taxgroups`
--
ALTER TABLE `taxgroups`
  ADD PRIMARY KEY (`taxgroupid`);

--
-- Indexes for table `taxgrouptaxes`
--
ALTER TABLE `taxgrouptaxes`
  ADD PRIMARY KEY (`taxgroupid`,`taxauthid`),
  ADD KEY `taxgroupid` (`taxgroupid`),
  ADD KEY `taxauthid` (`taxauthid`);

--
-- Indexes for table `taxprovinces`
--
ALTER TABLE `taxprovinces`
  ADD PRIMARY KEY (`taxprovinceid`);

--
-- Indexes for table `tenderitems`
--
ALTER TABLE `tenderitems`
  ADD PRIMARY KEY (`tenderid`,`stockid`);

--
-- Indexes for table `tenders`
--
ALTER TABLE `tenders`
  ADD PRIMARY KEY (`tenderid`);

--
-- Indexes for table `tendersuppliers`
--
ALTER TABLE `tendersuppliers`
  ADD PRIMARY KEY (`tenderid`,`supplierid`);

--
-- Indexes for table `unitsofmeasure`
--
ALTER TABLE `unitsofmeasure`
  ADD PRIMARY KEY (`unitid`);

--
-- Indexes for table `woitems`
--
ALTER TABLE `woitems`
  ADD PRIMARY KEY (`wo`,`stockid`),
  ADD KEY `stockid` (`stockid`);

--
-- Indexes for table `worequirements`
--
ALTER TABLE `worequirements`
  ADD PRIMARY KEY (`wo`,`parentstockid`,`stockid`),
  ADD KEY `stockid` (`stockid`),
  ADD KEY `worequirements_ibfk_3` (`parentstockid`);

--
-- Indexes for table `workcentres`
--
ALTER TABLE `workcentres`
  ADD PRIMARY KEY (`code`),
  ADD KEY `Description` (`description`),
  ADD KEY `Location` (`location`);

--
-- Indexes for table `workorders`
--
ALTER TABLE `workorders`
  ADD PRIMARY KEY (`wo`),
  ADD KEY `LocCode` (`loccode`),
  ADD KEY `StartDate` (`startdate`),
  ADD KEY `RequiredBy` (`requiredby`);

--
-- Indexes for table `woserialnos`
--
ALTER TABLE `woserialnos`
  ADD PRIMARY KEY (`wo`,`stockid`,`serialno`);

--
-- Indexes for table `www_users`
--
ALTER TABLE `www_users`
  ADD PRIMARY KEY (`userid`),
  ADD KEY `CustomerID` (`customerid`),
  ADD KEY `DefaultLocation` (`defaultlocation`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `assetmanager`
--
ALTER TABLE `assetmanager`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `banktrans`
--
ALTER TABLE `banktrans`
  MODIFY `banktransid` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cogsglpostings`
--
ALTER TABLE `cogsglpostings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `contractcharges`
--
ALTER TABLE `contractcharges`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contractreqts`
--
ALTER TABLE `contractreqts`
  MODIFY `contractreqid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `custallocns`
--
ALTER TABLE `custallocns`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `custcontacts`
--
ALTER TABLE `custcontacts`
  MODIFY `contid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `custnotes`
--
ALTER TABLE `custnotes`
  MODIFY `noteid` tinyint(4) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customerwitholdings`
--
ALTER TABLE `customerwitholdings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `debtortrans`
--
ALTER TABLE `debtortrans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `debtortype`
--
ALTER TABLE `debtortype`
  MODIFY `typeid` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `debtortypenotes`
--
ALTER TABLE `debtortypenotes`
  MODIFY `noteid` tinyint(4) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `departmentid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `edimessageformat`
--
ALTER TABLE `edimessageformat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `edi_orders_segs`
--
ALTER TABLE `edi_orders_segs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=96;

--
-- AUTO_INCREMENT for table `emailsettings`
--
ALTER TABLE `emailsettings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `factorcompanies`
--
ALTER TABLE `factorcompanies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fixedassets`
--
ALTER TABLE `fixedassets`
  MODIFY `assetid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `fixedassettasks`
--
ALTER TABLE `fixedassettasks`
  MODIFY `taskid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `fixedassettrans`
--
ALTER TABLE `fixedassettrans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `freightcosts`
--
ALTER TABLE `freightcosts`
  MODIFY `shipcostfromid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `geocode_param`
--
ALTER TABLE `geocode_param`
  MODIFY `geocodeid` tinyint(4) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gltrans`
--
ALTER TABLE `gltrans`
  MODIFY `counterindex` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `grns`
--
ALTER TABLE `grns`
  MODIFY `grnno` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hremployeeattendanceregister`
--
ALTER TABLE `hremployeeattendanceregister`
  MODIFY `attendance_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hremployeecategories`
--
ALTER TABLE `hremployeecategories`
  MODIFY `employee_category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `hremployeegradings`
--
ALTER TABLE `hremployeegradings`
  MODIFY `employee_grading_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `hremployeeleavegroups`
--
ALTER TABLE `hremployeeleavegroups`
  MODIFY `leavegroup_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hremployeeleaves`
--
ALTER TABLE `hremployeeleaves`
  MODIFY `employee_leave_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hremployeeleavetypes`
--
ALTER TABLE `hremployeeleavetypes`
  MODIFY `hrleavetype_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `hremployeeloanpayments`
--
ALTER TABLE `hremployeeloanpayments`
  MODIFY `loan_payment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hremployeeloans`
--
ALTER TABLE `hremployeeloans`
  MODIFY `loan_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hremployeeloantypes`
--
ALTER TABLE `hremployeeloantypes`
  MODIFY `loan_type_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hremployeepayslips`
--
ALTER TABLE `hremployeepayslips`
  MODIFY `payslip_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hremployeepositions`
--
ALTER TABLE `hremployeepositions`
  MODIFY `employee_position_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `hremployees`
--
ALTER TABLE `hremployees`
  MODIFY `empid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `hremployeesalarystructure_components`
--
ALTER TABLE `hremployeesalarystructure_components`
  MODIFY `component_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hrpaymentfrequency`
--
ALTER TABLE `hrpaymentfrequency`
  MODIFY `paymentfrequency_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hrpayrollcategories`
--
ALTER TABLE `hrpayrollcategories`
  MODIFY `payroll_category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `hrpayrollgroups`
--
ALTER TABLE `hrpayrollgroups`
  MODIFY `payrollgroup_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `hrpayroll_groups_payroll_categories`
--
ALTER TABLE `hrpayroll_groups_payroll_categories`
  MODIFY `groups_categories_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `hrpayslipcategorydetails`
--
ALTER TABLE `hrpayslipcategorydetails`
  MODIFY `detail_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hrpayslipdateranges`
--
ALTER TABLE `hrpayslipdateranges`
  MODIFY `daterange_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hrpayslipextradetails`
--
ALTER TABLE `hrpayslipextradetails`
  MODIFY `extra_payslip_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `labelfields`
--
ALTER TABLE `labelfields`
  MODIFY `labelfieldid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `labels`
--
ALTER TABLE `labels`
  MODIFY `labelid` tinyint(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mailgroups`
--
ALTER TABLE `mailgroups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `manufacturers`
--
ALTER TABLE `manufacturers`
  MODIFY `manufacturers_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mrpdemands`
--
ALTER TABLE `mrpdemands`
  MODIFY `demandid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mrpplannedorders`
--
ALTER TABLE `mrpplannedorders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `offers`
--
ALTER TABLE `offers`
  MODIFY `offerid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `paymentmethods`
--
ALTER TABLE `paymentmethods`
  MODIFY `paymentid` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `pcashdetails`
--
ALTER TABLE `pcashdetails`
  MODIFY `counterindex` int(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pcashdetailtaxes`
--
ALTER TABLE `pcashdetailtaxes`
  MODIFY `counterindex` int(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pcreceipts`
--
ALTER TABLE `pcreceipts`
  MODIFY `counterindex` int(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pickreq`
--
ALTER TABLE `pickreq`
  MODIFY `prid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pickreqdetails`
--
ALTER TABLE `pickreqdetails`
  MODIFY `detailno` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pickserialdetails`
--
ALTER TABLE `pickserialdetails`
  MODIFY `serialmoveid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchorderdetails`
--
ALTER TABLE `purchorderdetails`
  MODIFY `podetailitem` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchorders`
--
ALTER TABLE `purchorders`
  MODIFY `orderno` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `qasamples`
--
ALTER TABLE `qasamples`
  MODIFY `sampleid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `qatests`
--
ALTER TABLE `qatests`
  MODIFY `testid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `recurringsalesorders`
--
ALTER TABLE `recurringsalesorders`
  MODIFY `recurrorderno` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reportfields`
--
ALTER TABLE `reportfields`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reportheaders`
--
ALTER TABLE `reportheaders`
  MODIFY `reportid` smallint(6) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `salesanalysis`
--
ALTER TABLE `salesanalysis`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `salescat`
--
ALTER TABLE `salescat`
  MODIFY `salescatid` tinyint(4) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `salesglpostings`
--
ALTER TABLE `salesglpostings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `sampleresults`
--
ALTER TABLE `sampleresults`
  MODIFY `resultid` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `securityroles`
--
ALTER TABLE `securityroles`
  MODIFY `secroleid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `sellthroughsupport`
--
ALTER TABLE `sellthroughsupport`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `shipmentcharges`
--
ALTER TABLE `shipmentcharges`
  MODIFY `shiptchgid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `shippers`
--
ALTER TABLE `shippers`
  MODIFY `shipper_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `stockcatproperties`
--
ALTER TABLE `stockcatproperties`
  MODIFY `stkcatpropid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `stockcounts`
--
ALTER TABLE `stockcounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stockmoves`
--
ALTER TABLE `stockmoves`
  MODIFY `stkmoveno` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stockrequest`
--
ALTER TABLE `stockrequest`
  MODIFY `dispatchid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stockserialmoves`
--
ALTER TABLE `stockserialmoves`
  MODIFY `stkitmmoveno` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `suppallocs`
--
ALTER TABLE `suppallocs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `supplierdiscounts`
--
ALTER TABLE `supplierdiscounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `suppliertype`
--
ALTER TABLE `suppliertype`
  MODIFY `typeid` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `supptrans`
--
ALTER TABLE `supptrans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tags`
--
ALTER TABLE `tags`
  MODIFY `tagref` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `taxauthorities`
--
ALTER TABLE `taxauthorities`
  MODIFY `taxid` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `taxcategories`
--
ALTER TABLE `taxcategories`
  MODIFY `taxcatid` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `taxgroups`
--
ALTER TABLE `taxgroups`
  MODIFY `taxgroupid` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `taxprovinces`
--
ALTER TABLE `taxprovinces`
  MODIFY `taxprovinceid` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `unitsofmeasure`
--
ALTER TABLE `unitsofmeasure`
  MODIFY `unitid` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `accountgroups`
--
ALTER TABLE `accountgroups`
  ADD CONSTRAINT `accountgroups_ibfk_1` FOREIGN KEY (`sectioninaccounts`) REFERENCES `accountsection` (`sectionid`);

--
-- Constraints for table `audittrail`
--
ALTER TABLE `audittrail`
  ADD CONSTRAINT `audittrail_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `www_users` (`userid`);

--
-- Constraints for table `bankaccounts`
--
ALTER TABLE `bankaccounts`
  ADD CONSTRAINT `bankaccounts_ibfk_1` FOREIGN KEY (`accountcode`) REFERENCES `chartmaster` (`accountcode`);

--
-- Constraints for table `banktrans`
--
ALTER TABLE `banktrans`
  ADD CONSTRAINT `banktrans_ibfk_1` FOREIGN KEY (`type`) REFERENCES `systypes` (`typeid`),
  ADD CONSTRAINT `banktrans_ibfk_2` FOREIGN KEY (`bankact`) REFERENCES `bankaccounts` (`accountcode`);

--
-- Constraints for table `bom`
--
ALTER TABLE `bom`
  ADD CONSTRAINT `bom_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `stockmaster` (`stockid`),
  ADD CONSTRAINT `bom_ibfk_2` FOREIGN KEY (`component`) REFERENCES `stockmaster` (`stockid`),
  ADD CONSTRAINT `bom_ibfk_3` FOREIGN KEY (`workcentreadded`) REFERENCES `workcentres` (`code`),
  ADD CONSTRAINT `bom_ibfk_4` FOREIGN KEY (`loccode`) REFERENCES `locations` (`loccode`);

--
-- Constraints for table `chartdetails`
--
ALTER TABLE `chartdetails`
  ADD CONSTRAINT `chartdetails_ibfk_1` FOREIGN KEY (`accountcode`) REFERENCES `chartmaster` (`accountcode`),
  ADD CONSTRAINT `chartdetails_ibfk_2` FOREIGN KEY (`period`) REFERENCES `periods` (`periodno`);

--
-- Constraints for table `chartmaster`
--
ALTER TABLE `chartmaster`
  ADD CONSTRAINT `chartmaster_ibfk_1` FOREIGN KEY (`group_`) REFERENCES `accountgroups` (`groupname`);

--
-- Constraints for table `contractbom`
--
ALTER TABLE `contractbom`
  ADD CONSTRAINT `contractbom_ibfk_1` FOREIGN KEY (`workcentreadded`) REFERENCES `workcentres` (`code`),
  ADD CONSTRAINT `contractbom_ibfk_3` FOREIGN KEY (`stockid`) REFERENCES `stockmaster` (`stockid`);

--
-- Constraints for table `contractcharges`
--
ALTER TABLE `contractcharges`
  ADD CONSTRAINT `contractcharges_ibfk_1` FOREIGN KEY (`contractref`) REFERENCES `contracts` (`contractref`),
  ADD CONSTRAINT `contractcharges_ibfk_2` FOREIGN KEY (`transtype`) REFERENCES `systypes` (`typeid`);

--
-- Constraints for table `contractreqts`
--
ALTER TABLE `contractreqts`
  ADD CONSTRAINT `contractreqts_ibfk_1` FOREIGN KEY (`contractref`) REFERENCES `contracts` (`contractref`);

--
-- Constraints for table `contracts`
--
ALTER TABLE `contracts`
  ADD CONSTRAINT `contracts_ibfk_1` FOREIGN KEY (`debtorno`,`branchcode`) REFERENCES `custbranch` (`debtorno`, `branchcode`),
  ADD CONSTRAINT `contracts_ibfk_2` FOREIGN KEY (`categoryid`) REFERENCES `stockcategory` (`categoryid`),
  ADD CONSTRAINT `contracts_ibfk_3` FOREIGN KEY (`loccode`) REFERENCES `locations` (`loccode`);

--
-- Constraints for table `custallocns`
--
ALTER TABLE `custallocns`
  ADD CONSTRAINT `custallocns_ibfk_1` FOREIGN KEY (`transid_allocfrom`) REFERENCES `debtortrans` (`id`),
  ADD CONSTRAINT `custallocns_ibfk_2` FOREIGN KEY (`transid_allocto`) REFERENCES `debtortrans` (`id`);

--
-- Constraints for table `custbranch`
--
ALTER TABLE `custbranch`
  ADD CONSTRAINT `custbranch_ibfk_1` FOREIGN KEY (`debtorno`) REFERENCES `debtorsmaster` (`debtorno`),
  ADD CONSTRAINT `custbranch_ibfk_2` FOREIGN KEY (`area`) REFERENCES `areas` (`areacode`),
  ADD CONSTRAINT `custbranch_ibfk_3` FOREIGN KEY (`salesman`) REFERENCES `salesman` (`salesmancode`),
  ADD CONSTRAINT `custbranch_ibfk_4` FOREIGN KEY (`defaultlocation`) REFERENCES `locations` (`loccode`),
  ADD CONSTRAINT `custbranch_ibfk_6` FOREIGN KEY (`defaultshipvia`) REFERENCES `shippers` (`shipper_id`),
  ADD CONSTRAINT `custbranch_ibfk_7` FOREIGN KEY (`taxgroupid`) REFERENCES `taxgroups` (`taxgroupid`);

--
-- Constraints for table `custitem`
--
ALTER TABLE `custitem`
  ADD CONSTRAINT ` custitem _ibfk_1` FOREIGN KEY (`stockid`) REFERENCES `stockmaster` (`stockid`),
  ADD CONSTRAINT ` custitem _ibfk_2` FOREIGN KEY (`debtorno`) REFERENCES `debtorsmaster` (`debtorno`);

--
-- Constraints for table `debtorsmaster`
--
ALTER TABLE `debtorsmaster`
  ADD CONSTRAINT `debtorsmaster_ibfk_1` FOREIGN KEY (`holdreason`) REFERENCES `holdreasons` (`reasoncode`),
  ADD CONSTRAINT `debtorsmaster_ibfk_2` FOREIGN KEY (`currcode`) REFERENCES `currencies` (`currabrev`),
  ADD CONSTRAINT `debtorsmaster_ibfk_3` FOREIGN KEY (`paymentterms`) REFERENCES `paymentterms` (`termsindicator`),
  ADD CONSTRAINT `debtorsmaster_ibfk_4` FOREIGN KEY (`salestype`) REFERENCES `salestypes` (`typeabbrev`),
  ADD CONSTRAINT `debtorsmaster_ibfk_5` FOREIGN KEY (`typeid`) REFERENCES `debtortype` (`typeid`);

--
-- Constraints for table `debtortrans`
--
ALTER TABLE `debtortrans`
  ADD CONSTRAINT `debtortrans_ibfk_2` FOREIGN KEY (`type`) REFERENCES `systypes` (`typeid`),
  ADD CONSTRAINT `debtortrans_ibfk_3` FOREIGN KEY (`prd`) REFERENCES `periods` (`periodno`);

--
-- Constraints for table `debtortranstaxes`
--
ALTER TABLE `debtortranstaxes`
  ADD CONSTRAINT `debtortranstaxes_ibfk_1` FOREIGN KEY (`taxauthid`) REFERENCES `taxauthorities` (`taxid`),
  ADD CONSTRAINT `debtortranstaxes_ibfk_2` FOREIGN KEY (`debtortransid`) REFERENCES `debtortrans` (`id`);

--
-- Constraints for table `deliverynotes`
--
ALTER TABLE `deliverynotes`
  ADD CONSTRAINT `deliverynotes_ibfk_1` FOREIGN KEY (`salesorderno`) REFERENCES `salesorders` (`orderno`),
  ADD CONSTRAINT `deliverynotes_ibfk_2` FOREIGN KEY (`salesorderno`,`salesorderlineno`) REFERENCES `salesorderdetails` (`orderno`, `orderlineno`);

--
-- Constraints for table `discountmatrix`
--
ALTER TABLE `discountmatrix`
  ADD CONSTRAINT `discountmatrix_ibfk_1` FOREIGN KEY (`salestype`) REFERENCES `salestypes` (`typeabbrev`);

--
-- Constraints for table `freightcosts`
--
ALTER TABLE `freightcosts`
  ADD CONSTRAINT `freightcosts_ibfk_1` FOREIGN KEY (`locationfrom`) REFERENCES `locations` (`loccode`),
  ADD CONSTRAINT `freightcosts_ibfk_2` FOREIGN KEY (`shipperid`) REFERENCES `shippers` (`shipper_id`);

--
-- Constraints for table `gltrans`
--
ALTER TABLE `gltrans`
  ADD CONSTRAINT `gltrans_ibfk_1` FOREIGN KEY (`account`) REFERENCES `chartmaster` (`accountcode`),
  ADD CONSTRAINT `gltrans_ibfk_2` FOREIGN KEY (`type`) REFERENCES `systypes` (`typeid`),
  ADD CONSTRAINT `gltrans_ibfk_3` FOREIGN KEY (`periodno`) REFERENCES `periods` (`periodno`);

--
-- Constraints for table `grns`
--
ALTER TABLE `grns`
  ADD CONSTRAINT `grns_ibfk_1` FOREIGN KEY (`supplierid`) REFERENCES `suppliers` (`supplierid`),
  ADD CONSTRAINT `grns_ibfk_2` FOREIGN KEY (`podetailitem`) REFERENCES `purchorderdetails` (`podetailitem`);

--
-- Constraints for table `hremployeeloanpayments`
--
ALTER TABLE `hremployeeloanpayments`
  ADD CONSTRAINT `fk_loan_payments_loans` FOREIGN KEY (`loan_id`) REFERENCES `hremployeeloans` (`loan_id`) ON UPDATE CASCADE;

--
-- Constraints for table `hremployeesalarystructure_components`
--
ALTER TABLE `hremployeesalarystructure_components`
  ADD CONSTRAINT `fk_salary_structure_components` FOREIGN KEY (`salary_structure_id`) REFERENCES `hremployeesalarystructures` (`salary_structure_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `hrpayroll_groups_payroll_categories`
--
ALTER TABLE `hrpayroll_groups_payroll_categories`
  ADD CONSTRAINT `fk_groups_2` FOREIGN KEY (`payroll_group_id`) REFERENCES `hrpayrollgroups` (`payrollgroup_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_groups_categories_payroll` FOREIGN KEY (`payroll_category_id`) REFERENCES `hrpayrollcategories` (`payroll_category_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `hrpayslipcategorydetails`
--
ALTER TABLE `hrpayslipcategorydetails`
  ADD CONSTRAINT `fk_payslip_category_detail` FOREIGN KEY (`payslip_id`) REFERENCES `hremployeepayslips` (`payslip_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `hrpayslipextradetails`
--
ALTER TABLE `hrpayslipextradetails`
  ADD CONSTRAINT `fk_payslips_extra_infomation` FOREIGN KEY (`payslip_id`) REFERENCES `hremployeepayslips` (`payslip_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `internalstockcatrole`
--
ALTER TABLE `internalstockcatrole`
  ADD CONSTRAINT `internalstockcatrole_ibfk_1` FOREIGN KEY (`categoryid`) REFERENCES `stockcategory` (`categoryid`),
  ADD CONSTRAINT `internalstockcatrole_ibfk_2` FOREIGN KEY (`secroleid`) REFERENCES `securityroles` (`secroleid`),
  ADD CONSTRAINT `internalstockcatrole_ibfk_3` FOREIGN KEY (`categoryid`) REFERENCES `stockcategory` (`categoryid`),
  ADD CONSTRAINT `internalstockcatrole_ibfk_4` FOREIGN KEY (`secroleid`) REFERENCES `securityroles` (`secroleid`);

--
-- Constraints for table `locations`
--
ALTER TABLE `locations`
  ADD CONSTRAINT `locations_ibfk_1` FOREIGN KEY (`taxprovinceid`) REFERENCES `taxprovinces` (`taxprovinceid`);

--
-- Constraints for table `locstock`
--
ALTER TABLE `locstock`
  ADD CONSTRAINT `locstock_ibfk_1` FOREIGN KEY (`loccode`) REFERENCES `locations` (`loccode`),
  ADD CONSTRAINT `locstock_ibfk_2` FOREIGN KEY (`stockid`) REFERENCES `stockmaster` (`stockid`);

--
-- Constraints for table `loctransfers`
--
ALTER TABLE `loctransfers`
  ADD CONSTRAINT `loctransfers_ibfk_1` FOREIGN KEY (`shiploc`) REFERENCES `locations` (`loccode`),
  ADD CONSTRAINT `loctransfers_ibfk_2` FOREIGN KEY (`recloc`) REFERENCES `locations` (`loccode`),
  ADD CONSTRAINT `loctransfers_ibfk_3` FOREIGN KEY (`stockid`) REFERENCES `stockmaster` (`stockid`);

--
-- Constraints for table `mailgroupdetails`
--
ALTER TABLE `mailgroupdetails`
  ADD CONSTRAINT `mailgroupdetails_ibfk_1` FOREIGN KEY (`groupname`) REFERENCES `mailgroups` (`groupname`),
  ADD CONSTRAINT `mailgroupdetails_ibfk_2` FOREIGN KEY (`userid`) REFERENCES `www_users` (`userid`);

--
-- Constraints for table `mrpdemands`
--
ALTER TABLE `mrpdemands`
  ADD CONSTRAINT `mrpdemands_ibfk_1` FOREIGN KEY (`mrpdemandtype`) REFERENCES `mrpdemandtypes` (`mrpdemandtype`),
  ADD CONSTRAINT `mrpdemands_ibfk_2` FOREIGN KEY (`stockid`) REFERENCES `stockmaster` (`stockid`);

--
-- Constraints for table `offers`
--
ALTER TABLE `offers`
  ADD CONSTRAINT `offers_ibfk_1` FOREIGN KEY (`supplierid`) REFERENCES `suppliers` (`supplierid`),
  ADD CONSTRAINT `offers_ibfk_2` FOREIGN KEY (`stockid`) REFERENCES `stockmaster` (`stockid`);

--
-- Constraints for table `orderdeliverydifferenceslog`
--
ALTER TABLE `orderdeliverydifferenceslog`
  ADD CONSTRAINT `orderdeliverydifferenceslog_ibfk_1` FOREIGN KEY (`stockid`) REFERENCES `stockmaster` (`stockid`),
  ADD CONSTRAINT `orderdeliverydifferenceslog_ibfk_2` FOREIGN KEY (`debtorno`,`branch`) REFERENCES `custbranch` (`debtorno`, `branchcode`),
  ADD CONSTRAINT `orderdeliverydifferenceslog_ibfk_3` FOREIGN KEY (`orderno`) REFERENCES `salesorders` (`orderno`);

--
-- Constraints for table `pcexpenses`
--
ALTER TABLE `pcexpenses`
  ADD CONSTRAINT `pcexpenses_ibfk_1` FOREIGN KEY (`glaccount`) REFERENCES `chartmaster` (`accountcode`);

--
-- Constraints for table `pcreceipts`
--
ALTER TABLE `pcreceipts`
  ADD CONSTRAINT `pcreceipts_ibfk_1` FOREIGN KEY (`pccashdetail`) REFERENCES `pcashdetails` (`counterindex`);

--
-- Constraints for table `pctabexpenses`
--
ALTER TABLE `pctabexpenses`
  ADD CONSTRAINT `pctabexpenses_ibfk_1` FOREIGN KEY (`typetabcode`) REFERENCES `pctypetabs` (`typetabcode`),
  ADD CONSTRAINT `pctabexpenses_ibfk_2` FOREIGN KEY (`codeexpense`) REFERENCES `pcexpenses` (`codeexpense`);

--
-- Constraints for table `pctabs`
--
ALTER TABLE `pctabs`
  ADD CONSTRAINT `pctabs_ibfk_1` FOREIGN KEY (`usercode`) REFERENCES `www_users` (`userid`),
  ADD CONSTRAINT `pctabs_ibfk_2` FOREIGN KEY (`typetabcode`) REFERENCES `pctypetabs` (`typetabcode`),
  ADD CONSTRAINT `pctabs_ibfk_3` FOREIGN KEY (`currency`) REFERENCES `currencies` (`currabrev`),
  ADD CONSTRAINT `pctabs_ibfk_5` FOREIGN KEY (`glaccountassignment`) REFERENCES `chartmaster` (`accountcode`);

--
-- Constraints for table `pickinglistdetails`
--
ALTER TABLE `pickinglistdetails`
  ADD CONSTRAINT `pickinglistdetails_ibfk_1` FOREIGN KEY (`pickinglistno`) REFERENCES `pickinglists` (`pickinglistno`);

--
-- Constraints for table `pickinglists`
--
ALTER TABLE `pickinglists`
  ADD CONSTRAINT `pickinglists_ibfk_1` FOREIGN KEY (`orderno`) REFERENCES `salesorders` (`orderno`);

--
-- Constraints for table `pickreq`
--
ALTER TABLE `pickreq`
  ADD CONSTRAINT `pickreq_ibfk_1` FOREIGN KEY (`loccode`) REFERENCES `locations` (`loccode`),
  ADD CONSTRAINT `pickreq_ibfk_2` FOREIGN KEY (`orderno`) REFERENCES `salesorders` (`orderno`);

--
-- Constraints for table `pickreqdetails`
--
ALTER TABLE `pickreqdetails`
  ADD CONSTRAINT `pickreqdetails_ibfk_1` FOREIGN KEY (`stockid`) REFERENCES `stockmaster` (`stockid`),
  ADD CONSTRAINT `pickreqdetails_ibfk_2` FOREIGN KEY (`prid`) REFERENCES `pickreq` (`prid`);

--
-- Constraints for table `pickserialdetails`
--
ALTER TABLE `pickserialdetails`
  ADD CONSTRAINT `pickserialdetails_ibfk_1` FOREIGN KEY (`detailno`) REFERENCES `pickreqdetails` (`detailno`),
  ADD CONSTRAINT `pickserialdetails_ibfk_2` FOREIGN KEY (`stockid`,`serialno`) REFERENCES `stockserialitems` (`stockid`, `serialno`);

--
-- Constraints for table `prices`
--
ALTER TABLE `prices`
  ADD CONSTRAINT `prices_ibfk_1` FOREIGN KEY (`stockid`) REFERENCES `stockmaster` (`stockid`),
  ADD CONSTRAINT `prices_ibfk_2` FOREIGN KEY (`currabrev`) REFERENCES `currencies` (`currabrev`),
  ADD CONSTRAINT `prices_ibfk_3` FOREIGN KEY (`typeabbrev`) REFERENCES `salestypes` (`typeabbrev`);

--
-- Constraints for table `prodspecs`
--
ALTER TABLE `prodspecs`
  ADD CONSTRAINT `prodspecs_ibfk_1` FOREIGN KEY (`testid`) REFERENCES `qatests` (`testid`);

--
-- Constraints for table `purchdata`
--
ALTER TABLE `purchdata`
  ADD CONSTRAINT `purchdata_ibfk_1` FOREIGN KEY (`stockid`) REFERENCES `stockmaster` (`stockid`),
  ADD CONSTRAINT `purchdata_ibfk_2` FOREIGN KEY (`supplierno`) REFERENCES `suppliers` (`supplierid`);

--
-- Constraints for table `purchorderdetails`
--
ALTER TABLE `purchorderdetails`
  ADD CONSTRAINT `purchorderdetails_ibfk_1` FOREIGN KEY (`orderno`) REFERENCES `purchorders` (`orderno`);

--
-- Constraints for table `purchorders`
--
ALTER TABLE `purchorders`
  ADD CONSTRAINT `purchorders_ibfk_1` FOREIGN KEY (`supplierno`) REFERENCES `suppliers` (`supplierid`),
  ADD CONSTRAINT `purchorders_ibfk_2` FOREIGN KEY (`intostocklocation`) REFERENCES `locations` (`loccode`);

--
-- Constraints for table `qasamples`
--
ALTER TABLE `qasamples`
  ADD CONSTRAINT `qasamples_ibfk_1` FOREIGN KEY (`prodspeckey`) REFERENCES `prodspecs` (`keyval`);

--
-- Constraints for table `recurringsalesorders`
--
ALTER TABLE `recurringsalesorders`
  ADD CONSTRAINT `recurringsalesorders_ibfk_1` FOREIGN KEY (`branchcode`,`debtorno`) REFERENCES `custbranch` (`branchcode`, `debtorno`);

--
-- Constraints for table `recurrsalesorderdetails`
--
ALTER TABLE `recurrsalesorderdetails`
  ADD CONSTRAINT `recurrsalesorderdetails_ibfk_1` FOREIGN KEY (`recurrorderno`) REFERENCES `recurringsalesorders` (`recurrorderno`),
  ADD CONSTRAINT `recurrsalesorderdetails_ibfk_2` FOREIGN KEY (`stkcode`) REFERENCES `stockmaster` (`stockid`);

--
-- Constraints for table `reportcolumns`
--
ALTER TABLE `reportcolumns`
  ADD CONSTRAINT `reportcolumns_ibfk_1` FOREIGN KEY (`reportid`) REFERENCES `reportheaders` (`reportid`);

--
-- Constraints for table `salesanalysis`
--
ALTER TABLE `salesanalysis`
  ADD CONSTRAINT `salesanalysis_ibfk_1` FOREIGN KEY (`periodno`) REFERENCES `periods` (`periodno`);

--
-- Constraints for table `salescatprod`
--
ALTER TABLE `salescatprod`
  ADD CONSTRAINT `salescatprod_ibfk_1` FOREIGN KEY (`stockid`) REFERENCES `stockmaster` (`stockid`),
  ADD CONSTRAINT `salescatprod_ibfk_2` FOREIGN KEY (`salescatid`) REFERENCES `salescat` (`salescatid`);

--
-- Constraints for table `salesorderdetails`
--
ALTER TABLE `salesorderdetails`
  ADD CONSTRAINT `salesorderdetails_ibfk_1` FOREIGN KEY (`orderno`) REFERENCES `salesorders` (`orderno`),
  ADD CONSTRAINT `salesorderdetails_ibfk_2` FOREIGN KEY (`stkcode`) REFERENCES `stockmaster` (`stockid`);

--
-- Constraints for table `salesorders`
--
ALTER TABLE `salesorders`
  ADD CONSTRAINT `salesorders_ibfk_1` FOREIGN KEY (`branchcode`,`debtorno`) REFERENCES `custbranch` (`branchcode`, `debtorno`),
  ADD CONSTRAINT `salesorders_ibfk_2` FOREIGN KEY (`shipvia`) REFERENCES `shippers` (`shipper_id`),
  ADD CONSTRAINT `salesorders_ibfk_3` FOREIGN KEY (`fromstkloc`) REFERENCES `locations` (`loccode`);

--
-- Constraints for table `sampleresults`
--
ALTER TABLE `sampleresults`
  ADD CONSTRAINT `sampleresults_ibfk_1` FOREIGN KEY (`testid`) REFERENCES `qatests` (`testid`);

--
-- Constraints for table `securitygroups`
--
ALTER TABLE `securitygroups`
  ADD CONSTRAINT `securitygroups_secroleid_fk` FOREIGN KEY (`secroleid`) REFERENCES `securityroles` (`secroleid`),
  ADD CONSTRAINT `securitygroups_tokenid_fk` FOREIGN KEY (`tokenid`) REFERENCES `securitytokens` (`tokenid`);

--
-- Constraints for table `shipmentcharges`
--
ALTER TABLE `shipmentcharges`
  ADD CONSTRAINT `shipmentcharges_ibfk_1` FOREIGN KEY (`shiptref`) REFERENCES `shipments` (`shiptref`),
  ADD CONSTRAINT `shipmentcharges_ibfk_2` FOREIGN KEY (`transtype`) REFERENCES `systypes` (`typeid`);

--
-- Constraints for table `shipments`
--
ALTER TABLE `shipments`
  ADD CONSTRAINT `shipments_ibfk_1` FOREIGN KEY (`supplierid`) REFERENCES `suppliers` (`supplierid`);

--
-- Constraints for table `stockcatproperties`
--
ALTER TABLE `stockcatproperties`
  ADD CONSTRAINT `stockcatproperties_ibfk_1` FOREIGN KEY (`categoryid`) REFERENCES `stockcategory` (`categoryid`);

--
-- Constraints for table `stockcheckfreeze`
--
ALTER TABLE `stockcheckfreeze`
  ADD CONSTRAINT `stockcheckfreeze_ibfk_1` FOREIGN KEY (`stockid`) REFERENCES `stockmaster` (`stockid`),
  ADD CONSTRAINT `stockcheckfreeze_ibfk_2` FOREIGN KEY (`loccode`) REFERENCES `locations` (`loccode`);

--
-- Constraints for table `stockcounts`
--
ALTER TABLE `stockcounts`
  ADD CONSTRAINT `stockcounts_ibfk_1` FOREIGN KEY (`stockid`) REFERENCES `stockmaster` (`stockid`),
  ADD CONSTRAINT `stockcounts_ibfk_2` FOREIGN KEY (`loccode`) REFERENCES `locations` (`loccode`);

--
-- Constraints for table `stockitemproperties`
--
ALTER TABLE `stockitemproperties`
  ADD CONSTRAINT `stockitemproperties_ibfk_1` FOREIGN KEY (`stockid`) REFERENCES `stockmaster` (`stockid`),
  ADD CONSTRAINT `stockitemproperties_ibfk_2` FOREIGN KEY (`stkcatpropid`) REFERENCES `stockcatproperties` (`stkcatpropid`),
  ADD CONSTRAINT `stockitemproperties_ibfk_3` FOREIGN KEY (`stockid`) REFERENCES `stockmaster` (`stockid`),
  ADD CONSTRAINT `stockitemproperties_ibfk_4` FOREIGN KEY (`stkcatpropid`) REFERENCES `stockcatproperties` (`stkcatpropid`),
  ADD CONSTRAINT `stockitemproperties_ibfk_5` FOREIGN KEY (`stockid`) REFERENCES `stockmaster` (`stockid`),
  ADD CONSTRAINT `stockitemproperties_ibfk_6` FOREIGN KEY (`stkcatpropid`) REFERENCES `stockcatproperties` (`stkcatpropid`);

--
-- Constraints for table `stockmaster`
--
ALTER TABLE `stockmaster`
  ADD CONSTRAINT `stockmaster_ibfk_1` FOREIGN KEY (`categoryid`) REFERENCES `stockcategory` (`categoryid`),
  ADD CONSTRAINT `stockmaster_ibfk_2` FOREIGN KEY (`taxcatid`) REFERENCES `taxcategories` (`taxcatid`);

--
-- Constraints for table `stockmoves`
--
ALTER TABLE `stockmoves`
  ADD CONSTRAINT `stockmoves_ibfk_1` FOREIGN KEY (`stockid`) REFERENCES `stockmaster` (`stockid`),
  ADD CONSTRAINT `stockmoves_ibfk_2` FOREIGN KEY (`type`) REFERENCES `systypes` (`typeid`),
  ADD CONSTRAINT `stockmoves_ibfk_3` FOREIGN KEY (`loccode`) REFERENCES `locations` (`loccode`),
  ADD CONSTRAINT `stockmoves_ibfk_4` FOREIGN KEY (`prd`) REFERENCES `periods` (`periodno`);

--
-- Constraints for table `stockmovestaxes`
--
ALTER TABLE `stockmovestaxes`
  ADD CONSTRAINT `stockmovestaxes_ibfk_1` FOREIGN KEY (`taxauthid`) REFERENCES `taxauthorities` (`taxid`),
  ADD CONSTRAINT `stockmovestaxes_ibfk_2` FOREIGN KEY (`stkmoveno`) REFERENCES `stockmoves` (`stkmoveno`),
  ADD CONSTRAINT `stockmovestaxes_ibfk_3` FOREIGN KEY (`stkmoveno`) REFERENCES `stockmoves` (`stkmoveno`),
  ADD CONSTRAINT `stockmovestaxes_ibfk_4` FOREIGN KEY (`stkmoveno`) REFERENCES `stockmoves` (`stkmoveno`);

--
-- Constraints for table `stockrequest`
--
ALTER TABLE `stockrequest`
  ADD CONSTRAINT `stockrequest_ibfk_1` FOREIGN KEY (`loccode`) REFERENCES `locations` (`loccode`),
  ADD CONSTRAINT `stockrequest_ibfk_2` FOREIGN KEY (`departmentid`) REFERENCES `departments` (`departmentid`);

--
-- Constraints for table `stockrequestitems`
--
ALTER TABLE `stockrequestitems`
  ADD CONSTRAINT `stockrequestitems_ibfk_1` FOREIGN KEY (`dispatchid`) REFERENCES `stockrequest` (`dispatchid`),
  ADD CONSTRAINT `stockrequestitems_ibfk_2` FOREIGN KEY (`stockid`) REFERENCES `stockmaster` (`stockid`),
  ADD CONSTRAINT `stockrequestitems_ibfk_3` FOREIGN KEY (`dispatchid`) REFERENCES `stockrequest` (`dispatchid`),
  ADD CONSTRAINT `stockrequestitems_ibfk_4` FOREIGN KEY (`stockid`) REFERENCES `stockmaster` (`stockid`);

--
-- Constraints for table `stockserialitems`
--
ALTER TABLE `stockserialitems`
  ADD CONSTRAINT `stockserialitems_ibfk_1` FOREIGN KEY (`stockid`) REFERENCES `stockmaster` (`stockid`),
  ADD CONSTRAINT `stockserialitems_ibfk_2` FOREIGN KEY (`loccode`) REFERENCES `locations` (`loccode`);

--
-- Constraints for table `stockserialmoves`
--
ALTER TABLE `stockserialmoves`
  ADD CONSTRAINT `stockserialmoves_ibfk_1` FOREIGN KEY (`stockmoveno`) REFERENCES `stockmoves` (`stkmoveno`),
  ADD CONSTRAINT `stockserialmoves_ibfk_2` FOREIGN KEY (`stockid`,`serialno`) REFERENCES `stockserialitems` (`stockid`, `serialno`);

--
-- Constraints for table `suppallocs`
--
ALTER TABLE `suppallocs`
  ADD CONSTRAINT `suppallocs_ibfk_1` FOREIGN KEY (`transid_allocfrom`) REFERENCES `supptrans` (`id`),
  ADD CONSTRAINT `suppallocs_ibfk_2` FOREIGN KEY (`transid_allocto`) REFERENCES `supptrans` (`id`);

--
-- Constraints for table `suppinvstogrn`
--
ALTER TABLE `suppinvstogrn`
  ADD CONSTRAINT `suppinvstogrn_ibfk_1` FOREIGN KEY (`grnno`) REFERENCES `grns` (`grnno`);

--
-- Constraints for table `suppliercontacts`
--
ALTER TABLE `suppliercontacts`
  ADD CONSTRAINT `suppliercontacts_ibfk_1` FOREIGN KEY (`supplierid`) REFERENCES `suppliers` (`supplierid`);

--
-- Constraints for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD CONSTRAINT `suppliers_ibfk_1` FOREIGN KEY (`currcode`) REFERENCES `currencies` (`currabrev`),
  ADD CONSTRAINT `suppliers_ibfk_2` FOREIGN KEY (`paymentterms`) REFERENCES `paymentterms` (`termsindicator`),
  ADD CONSTRAINT `suppliers_ibfk_3` FOREIGN KEY (`taxgroupid`) REFERENCES `taxgroups` (`taxgroupid`);

--
-- Constraints for table `supptrans`
--
ALTER TABLE `supptrans`
  ADD CONSTRAINT `supptrans_ibfk_1` FOREIGN KEY (`type`) REFERENCES `systypes` (`typeid`),
  ADD CONSTRAINT `supptrans_ibfk_2` FOREIGN KEY (`supplierno`) REFERENCES `suppliers` (`supplierid`);

--
-- Constraints for table `supptranstaxes`
--
ALTER TABLE `supptranstaxes`
  ADD CONSTRAINT `supptranstaxes_ibfk_1` FOREIGN KEY (`taxauthid`) REFERENCES `taxauthorities` (`taxid`),
  ADD CONSTRAINT `supptranstaxes_ibfk_2` FOREIGN KEY (`supptransid`) REFERENCES `supptrans` (`id`);

--
-- Constraints for table `taxauthorities`
--
ALTER TABLE `taxauthorities`
  ADD CONSTRAINT `taxauthorities_ibfk_1` FOREIGN KEY (`taxglcode`) REFERENCES `chartmaster` (`accountcode`),
  ADD CONSTRAINT `taxauthorities_ibfk_2` FOREIGN KEY (`purchtaxglaccount`) REFERENCES `chartmaster` (`accountcode`);

--
-- Constraints for table `taxauthrates`
--
ALTER TABLE `taxauthrates`
  ADD CONSTRAINT `taxauthrates_ibfk_1` FOREIGN KEY (`taxauthority`) REFERENCES `taxauthorities` (`taxid`),
  ADD CONSTRAINT `taxauthrates_ibfk_2` FOREIGN KEY (`taxcatid`) REFERENCES `taxcategories` (`taxcatid`),
  ADD CONSTRAINT `taxauthrates_ibfk_3` FOREIGN KEY (`dispatchtaxprovince`) REFERENCES `taxprovinces` (`taxprovinceid`);

--
-- Constraints for table `taxgrouptaxes`
--
ALTER TABLE `taxgrouptaxes`
  ADD CONSTRAINT `taxgrouptaxes_ibfk_1` FOREIGN KEY (`taxgroupid`) REFERENCES `taxgroups` (`taxgroupid`),
  ADD CONSTRAINT `taxgrouptaxes_ibfk_2` FOREIGN KEY (`taxauthid`) REFERENCES `taxauthorities` (`taxid`);

--
-- Constraints for table `woitems`
--
ALTER TABLE `woitems`
  ADD CONSTRAINT `woitems_ibfk_1` FOREIGN KEY (`stockid`) REFERENCES `stockmaster` (`stockid`),
  ADD CONSTRAINT `woitems_ibfk_2` FOREIGN KEY (`wo`) REFERENCES `workorders` (`wo`);

--
-- Constraints for table `worequirements`
--
ALTER TABLE `worequirements`
  ADD CONSTRAINT `worequirements_ibfk_1` FOREIGN KEY (`wo`) REFERENCES `workorders` (`wo`),
  ADD CONSTRAINT `worequirements_ibfk_2` FOREIGN KEY (`stockid`) REFERENCES `stockmaster` (`stockid`),
  ADD CONSTRAINT `worequirements_ibfk_3` FOREIGN KEY (`wo`,`parentstockid`) REFERENCES `woitems` (`wo`, `stockid`);

--
-- Constraints for table `workcentres`
--
ALTER TABLE `workcentres`
  ADD CONSTRAINT `workcentres_ibfk_1` FOREIGN KEY (`location`) REFERENCES `locations` (`loccode`);

--
-- Constraints for table `workorders`
--
ALTER TABLE `workorders`
  ADD CONSTRAINT `worksorders_ibfk_1` FOREIGN KEY (`loccode`) REFERENCES `locations` (`loccode`);

--
-- Constraints for table `www_users`
--
ALTER TABLE `www_users`
  ADD CONSTRAINT `www_users_ibfk_1` FOREIGN KEY (`defaultlocation`) REFERENCES `locations` (`loccode`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
