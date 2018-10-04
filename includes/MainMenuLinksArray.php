
<?php
/* nERP menus with Captions and URLs. */

$ModuleLink = array('Sales', 'AR', 'PO', 'AP', 'stock', 'manuf', 'GL', 'FA', 'PC', 'HR', 'system', 'Utilities');
$ReportList = array('Sales' => 'ord', 'AR' => 'ar', 'PO' => 'prch', 'AP' => 'ap', 'stock' => 'inv', 'manuf' => 'man', 'GL' => 'gl', 'FA' => 'fa', 'PC' => 'pc', 'system' => 'sys', 'Utilities' => 'utils');

/*The headings showing on the tabs accross the main index used also in WWW_Users for defining what should be visible to the user */
$ModuleList = array(_('Sales'), _('Receivables'), _('Purchases'), _('Payables'), _('Inventory'), _('Manufacturing'), _('General Ledger'), _('Asset Manager'), _('Petty Cash'), _('HR Management'),  _('Setup'), _('Utilities'));

$MenuItems['Sales']['Transactions']['Caption'] = array(_('New Sales Order or Quotation'), _('New Counter Sales'), _('New Counter Returns'),_('New Contract'), _('Open Sales Orders/Quotations'), _('Open Recurring Orders'), _('Process Recurring Orders'), _('List Contracts'));

$MenuItems['Sales']['Transactions']['URL'] = array('/SelectOrderItems.php?NewOrder=Yes', '/CounterSales.php', '/CounterReturns.php','/Contracts.php', '/SelectSalesOrder.php', '/SelectRecurringSalesOrder.php', '/RecurringSalesOrdersProcess.php',  '/SelectContract.php');

$MenuItems['Sales']['Reports']['Caption'] = array(_('Sales Order Inquiry'),  _('Daily Sales Inquiry'), _('Sales By Sales Type Inquiry'), _('Sales By Category Inquiry'), _('Sales By Category By Item Inquiry'), _('Top Sellers Inquiry'), _('Sales Order Detail Or Summary Inquiries'), _('Top Sales Items Inquiry'), _('Top Customers Inquiry'), _('Customers Balances By Currency Totals'), _('Print Price Lists'), _('Order Status Report'), _('Orders Invoiced Reports'), _('Sales Graphs'),  _('Order Delivery Differences Report'), _('Delivery In Full On Time (DIFOT) Report'), _('Worst Sales Items Report'), _('Sales With Low Gross Profit Report'));

$MenuItems['Sales']['Reports']['URL'] = array('/SelectCompletedOrder.php', '/DailySalesInquiry.php', '/SalesByTypePeriodInquiry.php', '/SalesCategoryPeriodInquiry.php', '/StockCategorySalesInquiry.php', '/SalesTopItemsInquiry.php', '/SalesInquiry.php', '/TopItems.php', '/SalesTopCustomersInquiry.php', '/Z_CurrencyDebtorsBalances.php', '/PDFPriceList.php', '/PDFOrderStatus.php', '/PDFOrdersInvoiced.php', '/SalesGraph.php', '/PDFDeliveryDifferences.php', '/PDFDIFOT.php','/NoSalesItems.php', '/PDFLowGP.php');


$MenuItems['AR']['Transactions']['Caption'] = array(_('Orders to Invoice'), _('New Credit Note'), _('New Receipt'), _('Allocate Receipts or Credit Notes'));

$MenuItems['AR']['Transactions']['URL'] = array('/SelectSalesOrder.php', '/SelectCreditItems.php?NewCredit=Yes', '/CustomerReceipt.php?NewReceipt=Yes&amp;Type=Customer', '/CustomerAllocations.php');

$MenuItems['AR']['Reports']['Caption'] = array(_('Where Allocated Inquiry'), _('Customer Transaction Inquiries'), _('Customer Activity and Balances'), _('Print Invoices or Credit Notes'), _('Print Statements'), _('Aged Customer Balances/Overdues Report'), _('Re-Print A Deposit Listing'), _('Customer Balances At A Prior Month End'), _('Customer Listing By Area/Salesperson'), _('Daily Transactions Report'));

if ($_SESSION['InvoicePortraitFormat'] == 0) {
	$PrintInvoicesOrCreditNotesScript = '/PrintCustTrans.php';
} else {
	$PrintInvoicesOrCreditNotesScript = '/PrintCustTransPortrait.php';
}

$MenuItems['AR']['Reports']['URL'] = array('/CustWhereAlloc.php','/CustomerTransInquiry.php', '/CustomerBalancesMovement.php', $PrintInvoicesOrCreditNotesScript, '/PrintCustStatements.php', '/AgedDebtors.php', '/PDFBankingSummary.php', '/DebtorsAtPeriodEnd.php', '/PDFCustomerList.php', '/PDFCustTransListing.php');

$MenuItems['AP']['Transactions']['Caption'] = array(_('Allocate Receipts or Debit Notes'));

$MenuItems['AP']['Transactions']['URL'] = array('/SupplierAllocations.php');

$MenuItems['AP']['Reports']['Caption'] = array(_('Where Allocated Inquiry'), _('Supplier Transaction Inquiries'), _('Aged Supplier Report'), _('Payment Run Report'), _('Remittance Advices'), _('Outstanding GRNs Report'), _('Supplier Balances At A Prior Month End'), _('List Daily Transactions'));

$MenuItems['AP']['Reports']['URL'] = array('/SuppWhereAlloc.php', '/SupplierTransInquiry.php', '/AgedSuppliers.php', '/SuppPaymentRun.php', '/PDFRemittanceAdvice.php', '/OutstandingGRNs.php', '/SupplierBalsAtPeriodEnd.php', '/PDFSuppTransListing.php');



$MenuItems['PO']['Transactions']['Caption'] = array(_('New Purchase Order'), _('New Tender'), _('New Shipment'), _('Open Purchase Orders'), _('Open Tenders'), _('Process Tenders'), _('Open Shipments'), _('Orders to Authorise'), _('Supplier Price Lists'));

$MenuItems['PO']['Transactions']['URL'] = array('/PO_Header.php?NewOrder=Yes','/SupplierTenderCreate.php?New=Yes','/SelectSupplier.php', '/PO_SelectOSPurchOrder.php',  '/SupplierTenderCreate.php?Edit=Yes', '/OffersReceived.php', '/Shipt_Select.php', '/PO_AuthoriseMyOrders.php',  '/SupplierPriceList.php');

$MenuItems['PO']['Reports']['Caption'] = array(_('Purchase Order Inquiry'), _('Purchase Order Specific Inquiries'),_('Purchases from Suppliers'), _('Suppliers Balances By Currency Totals'), _('Print Supplier Price List'));

$MenuItems['PO']['Reports']['URL'] = array('/PO_SelectPurchOrder.php', '/POReport.php', '/PurchasesReport.php', '/Z_CurrencySuppliersBalances.php', '/SuppPriceList.php');

$MenuItems['stock']['Transactions']['Caption'] = array(_('Receive Purchase Orders'), _('Inventory Location Transfers'), //"Inventory Transfer - Item Dispatch"
_('Bulk Inventory Transfer') . ' - ' . _('Dispatch'), //"Inventory Transfer - Bulk Dispatch"
_('Bulk Inventory Transfer') . ' - ' . _('Receive'), //"Inventory Transfer - Receive"
_('Inventory Adjustments'), _('Reverse Goods Received'), _('Enter Stock Counts'), _('Create a New Internal Stock Request'), _('Authorise Internal Stock Requests'), _('Fulfill Internal Stock Requests'), _('Update Prices Based On Sales Type'), _('Update Prices Based On Costs'));

$MenuItems['stock']['Transactions']['URL'] = array('/PO_SelectOSPurchOrder.php', '/StockTransfers.php?New=Yes', '/StockLocTransfer.php', '/StockLocTransferReceive.php', '/StockAdjustments.php?NewAdjustment=Yes', '/ReverseGRN.php', '/StockCounts.php', '/InternalStockRequest.php?New=Yes', '/InternalStockRequestAuthorisation.php', '/InternalStockRequestFulfill.php', '/PricesBasedOnMarkUp.php', '/PricesByCost.php');

$MenuItems['stock']['Reports']['Caption'] = array(_('Serial Item Research'), _('Inventory Item Movements'), _('Inventory Item Status'), _('Inventory Item Usage'), _('All Inventory Movements By Location/Date'), _('List Inventory Status By Location/Category'), _('Historical Stock Quantity By Location/Category'), _('Aged Controlled Stock'), _('Internal stock request inquiry'), _('Print Price Labels'), _('Reprint GRN'), _('Inventory Quantities Report'), _('Reorder Level Report'), _('Stock Dispatch Report'), _('Inventory Valuation Report'), _('Inventory Planning Report'), _('Inventory Planning Based On Preferred Supplier Data'), _('Inventory Stock Check Sheets'), _('All Inventory Quantities-CSV'), _('Compare Counts Vs Stock Check Data'), _('List Negative Stocks'), _('Period Stock Transaction Report'), _('Print Stock Transfer Note'));

$MenuItems['stock']['Reports']['URL'] = array('/StockSerialItemResearch.php', '/StockMovements.php', '/StockStatus.php', '/StockUsage.php', '/StockLocMovements.php', '/StockLocStatus.php', '/StockQuantityByDate.php', '/AgedControlledInventory.php', '/InternalStockRequestInquiry.php', '/PDFPrintLabel.php', '/ReprintGRN.php', '/InventoryQuantities.php', '/ReorderLevel.php', '/StockDispatch.php', '/InventoryValuation.php', '/InventoryPlanning.php', '/InventoryPlanningPrefSupplier.php', '/StockCheck.php', '/StockQties_csv.php', '/PDFStockCheckComparison.php',  '/PDFStockNegatives.php', '/PDFPeriodStockTransListing.php', '/PDFStockTransfer.php');

$MenuItems['manuf']['Transactions']['Caption'] = array(_('New Work Order'), _('Open Work Orders'),  _('Work Centres'), _('Bills Of Material'), _('Copy a Bill Of Materials Between Items'), _('Master Schedule'), _('Auto Create Master Schedule'), _('MRP Calculation'), _('QA Samples and Tests'),_('Quality Tests'), _('Product Specifications'));

$MenuItems['manuf']['Transactions']['URL'] = array('/WorkOrderEntry.php', '/SelectWorkOrder.php',  '/WorkCentres.php', '/BOMs.php', '/CopyBOM.php', '/MRPDemands.php', '/MRPCreateDemands.php', '/MRP.php','/SelectQASamples.php', '/QATests.php', '/ProductSpecs.php');

$MenuItems['manuf']['Reports']['Caption'] = array(_('Costed Bill Of Material Inquiry'), _('Where Used Inquiry'), _('WO Items ready to produce'), _('MRP'), _('Multiple Work Orders Total Cost Inquiry'), _('Bill Of Material Report'), _('Indented Bill Of Material Report'), _('Components Required Report'), _('Materials Not Used Anywhere Report'), _('Indented Where Used Report'), _('MRP Shortages Report'), _('MRP Suggested Purchase Orders Report'), _('MRP Suggested Work Orders Report'), _('MRP Reschedules Required Report'), _('Print Product Specification'), _('Print Certificate of Analysis'), _('Historical QA Test Results Report'));

$MenuItems['manuf']['Reports']['URL'] = array('/BOMInquiry.php', '/WhereUsedInquiry.php', '/WOCanBeProducedNow.php', '/MRPReport.php', '/CollectiveWorkOrderCost.php', '/BOMListing.php', '/BOMIndented.php', '/BOMExtendedQty.php', '/MaterialsNotUsed.php', '/BOMIndentedReverse.php',  '/MRPShortages.php', '/MRPPlannedPurchaseOrders.php', '/MRPPlannedWorkOrders.php', '/MRPReschedules.php', '/PDFProdSpec.php', '/PDFCOA.php', '/HistoricalTestResults.php');

$MenuItems['GL']['Transactions']['Caption'] = array(_('New Bank Account Payment'), _('New Bank Account Receipt'), _('Bank Account Payments Matching'), _('Bank Account Receipts Matching'), _('Journal Entry'), _('GL Account Sections'), _('GL Account Groups'), _('GL Accounts'), _('GL Account Authorised Users'), _('GL Budgets'), _('GL Tags'), _('Bank Accounts'), _('Bank Account Authorised Users'));

$MenuItems['GL']['Transactions']['URL'] = array('/Payments.php?NewPayment=Yes', '/CustomerReceipt.php?NewReceipt=Yes&amp;Type=GL', '/BankMatching.php?Type=Payments', '/BankMatching.php?Type=Receipts', '/GLJournal.php?NewJournal=Yes', '/AccountSections.php', '/AccountGroups.php', '/GLAccounts.php', '/GLAccountUsers.php', '/GLBudgets.php', '/GLTags.php', '/BankAccounts.php', '/BankAccountUsers.php');

$MenuItems['GL']['Reports']['Caption'] = array(_('Bank Account Reconciliation Statement'), _('Daily Bank Transactions'), _('GL Account Inquiry'),  _('General Ledger Journal Inquiry'), _('Statement of Cash Flows'), _('GL Transactions That Do Not Balance'), _('Bank Account Balances'), _('Cheque Payments Listing'), _('GL Account Transactions-Graph'), _('GL Account Listing'), _('GL Account Listing-CSV'), _('Trial Balance'), _('Balance Sheet'), _('Profit and Loss Statement'), _('Horizontal Analysis of Statement of Financial Position'), _('Horizontal Analysis of Statement of Comprehensive Income'), _('Tag Reports'), _('Tax Reports'));

$MenuItems['GL']['Reports']['URL'] = array('/BankReconciliation.php', '/DailyBankTransactions.php', '/SelectGLAccount.php', '/GLJournalInquiry.php', '/GLCashFlowsIndirect.php', '/Z_CheckGLTransBalance.php', '/BankAccountBalances.php', '/PDFChequeListing.php', '/GLAccountGraph.php', '/GLAccountReport.php', '/GLAccountCSV.php', '/GLTrialBalance.php', '/GLBalanceSheet.php', '/GLProfit_Loss.php', '/AnalysisHorizontalPosition.php', '/AnalysisHorizontalIncome.php', '/GLTagProfit_Loss.php', '/Tax.php');

$MenuItems['FA']['Transactions']['Caption'] = array(_('New Asset'), _('Assets Manager'), _('Change Asset Location'), _('Fixed Asset Categories'), _('Fixed Asset Locations'), _('Fixed Asset Maintenance Tasks'));

$MenuItems['FA']['Transactions']['URL'] = array('/FixedAssetItems.php', '/SelectAsset.php', '/FixedAssetTransfer.php',  '/FixedAssetCategories.php', '/FixedAssetLocations.php', '/MaintenanceTasks.php');

$MenuItems['FA']['Reports']['Caption'] = array(_('Asset Register'), _('Maintenance Schedule'), _('Depreciation Journal'));

$MenuItems['FA']['Reports']['URL'] = array('/FixedAssetRegister.php', '/MaintenanceUserSchedule.php','/FixedAssetDepreciation.php');

$MenuItems['PC']['Transactions']['Caption'] = array(_('Assign Cash to PC Tab'), _('Transfer Assigned Cash Between PC Tabs'), _('Claim Expenses From PC Tab'), _('Authorise Expenses'), _('Authorise Assigned Cash'), _('Types of PC Tabs'), _('PC Tabs'), _('PC Expenses'), _('Expenses for Type of PC Tab'));

$MenuItems['PC']['Transactions']['URL'] = array('/PcAssignCashToTab.php', '/PcAssignCashTabToTab.php', '/PcClaimExpensesFromTab.php', '/PcAuthorizeExpenses.php', '/PcAuthorizeCash.php', '/PcTypeTabs.php', '/PcTabs.php', '/PcExpenses.php', '/PcExpensesTypeTab.php');

$MenuItems['PC']['Reports']['Caption'] = array(_('PC Expenses Analysis'), _('PC Tab General Report'), _('PC Expense General Report'), _('PC Tab Expenses Report'));

$MenuItems['PC']['Reports']['URL'] = array('/PcAnalysis.php', '/PcReportTab.php', '/PcReportExpense.php', '/PcTabExpensesList.php');

$MenuItems['HR']['Transactions']['Caption'] = array(_('Search'),
																									_('New'),
																									_('Payroll for Paygroup'),
																									_('Payroll for an Employee'),
																									_('Attendance Register'),
																									_('Apply for leave'),
																									//_('Approve Advance/Loan'),
																									_('New Loan/Advance'),
																									_('My Payslips'),
																									_('My Leaves'),
																									//_('GL Posting Settings'),
																									_('Employee Departments'),
																									_('Employee Categories'),
																									_('Employment Grades'),
																									_('Employee Positions'),
																									//_('Manage Working Days'),
																									//_('Manage Leave Groups'),
																									_('Leave Types'),
																									//_('Payroll Calculation Mode'),
																									_('Payroll Categories'),
																									_('Payroll Groups'),
																									//_('Payroll Slips Settings'),
																									//_('Manage Loan Types'),
																								/*_('Notification Settings')*/
																									);

												$MenuItems['HR']['Transactions']['URL'] = array('/HrSelectEmployee.php',
																								'/HrEmployees.php?New=Yes',
																								'/HrGeneratePayroll.php',
																								'/HrGenerateEmployeePay.php',
																								'/HrAttendanceRegister.php',
																								'/HrLeaveApplications.php?New=Yes',
																								//'/HrApproveLoan.php',
																								'/HrEmployeeLoans.php?New=Yes',
																								'/HrEmployeePayslips.php',
																								'/HrMyLeave.php',
																								//'/HrGlSettings.php',
																								'/Departments.php',
																								'/HrEmployeeCategories.php',
																								'/HrEmployeeGrades.php',
																								'/HrEmployeePositions.php',
																								//'/HrWorkingDays.php',
																								//'/HrLeaveGroups.php',
																								'/HrLeaveTypes.php',
																								//'/HrPayrollMode.php',
																								'/HrPayrollCategories.php',
																								'/HrPayrollGroups.php',
																								//'/HrPayslipSettings.php',
																								//'/HrLoanTypes.php',
																							/*'/HrNotificationSettings.php'*/);

												$MenuItems['HR']['Reports']['Caption'] = array(//	_('Payslips For Paygroup'),
																								//_('Payroll Category-wise Report'),
																								//_('Employee Payslip Report'),
																								//_('Overal Salary Report'),
																								//_('Overal Estimation Report'),
																								//_('Payslip Department Report'),
																								_('Employee Attendance Report'),
																								_('Employee Leave Applications'),
																								//_('Employee Loans/Advance'),
																								_('Deductables Payroll Reports'));

												$MenuItems['HR']['Reports']['URL'] = array(	//'/HrGeneratePayslipForGroup.php',
																							//'/HrGenerateCategoryWiseReport.php',
																							//'/HrGenerateEmployeePayslipReport.php',
																							//'/HrGenerateOveralSalaryReport.php',
																							//'/HrGenerateEstimatedSalary.php',
																							//'/HrGenerateDepartmentPayslipReport.php',
																							'/HrGenerateAttendanceReport.php',
																							'/HrLeaveApplications.php',
																							//'/HrEmployeeLoans.php',
																							'/HrDeductablesReports.php');

$MenuItems['system']['Transactions']['Caption'] = array(_('Company Preferences'), _('System Parameters'), _('Users'), _('Security Tokens'), _('Access Permissions'), _('Page Security Settings'), _('Currencies'), _('Tax Authorities and Rates '), _('Tax Group'), _('Dispatch Tax Province'), _('Tax Category'), _('View Audit Trail'), _('SMTP Server Details'), _('Inventory Categories'), _('Inventory Locations'), _('Inventory Location Authorised Users'), _('Discount Category'), _('Units of Measure'), _('MRP Available Production Days'), _('MRP Demand Types'), _('Internal Departments'), _('Internal Stock Categories to User Roles'));

$MenuItems['system']['Transactions']['URL'] = array('/CompanyPreferences.php', '/SystemParameters.php', '/WWW_Users.php', '/SecurityTokens.php', '/WWW_Access.php', '/PageSecurity.php', '/Currencies.php', '/TaxAuthorities.php', '/TaxGroups.php', '/TaxProvinces.php', '/TaxCategories.php', '/AuditTrail.php', '/SMTPServer.php', '/StockCategories.php', '/Locations.php', '/LocationUsers.php', '/DiscountCategories.php', '/UnitsOfMeasure.php', '/MRPCalendar.php', '/MRPDemandTypes.php', '/Departments.php', '/InternalStockCategoriesByRole.php');

$MenuItems['system']['Reports']['Caption'] = array(_('Sales Types/Price Lists'), _('Customer Types'), _('Supplier Types'), _('Credit Status'), _('Payment Terms'), _('Purchase Order Authorisation levels'), _('Payment Methods'), _('Sales People'), _('Sales Areas'), _('Shippers'), _('Sales GL Postings'), _('COGS GL Postings'), _('Discount Matrix'));

$MenuItems['system']['Reports']['URL'] = array('/SalesTypes.php', '/CustomerTypes.php', '/SupplierTypes.php', '/CreditStatus.php', '/PaymentTerms.php', '/PO_AuthorisationLevels.php', '/PaymentMethods.php', '/SalesPeople.php', '/Areas.php', '/Shippers.php', '/SalesGLPostings.php', '/COGSGLPostings.php', '/DiscountMatrix.php');


$MenuItems['Utilities']['Transactions']['Caption'] = array(_('Change A Customer Code'), _('Change A Customer Branch Code'), _('Change A GL Account Code'), _('Change An Inventory Item Code'), _('Change A Location Code'), _('Change A Salesman Code'), _('Change A Stock Category Code'), _('Change A Supplier Code'), _('Update costs for all BOM items, from the bottom up'), _('Re-apply costs to Sales Analysis'), _('Delete sales transactions'), _('Reverse all supplier payments on a specified date'), _('Update sales analysis with latest customer data'), _('Copy Authority of GL Accounts from one user to another'), _('Make New Company'), _('Data Export Options'), _('Import Customers from .csv file'), _('Import Stock Items from .csv file'), _('Import Price List from .csv file'), _('Import Fixed Assets from .csv file'), _('Import GL Payments Receipts Or Journals From .csv file'), _('Re-calculate brought forward amounts in GL'), _('Re-Post all GL transactions from a specified period'), _('Purge all old prices'), _('Remove all purchase back orders'));

$MenuItems['Utilities']['Transactions']['URL'] = array('/Z_ChangeCustomerCode.php', '/Z_ChangeBranchCode.php', '/Z_ChangeGLAccountCode.php', '/Z_ChangeStockCode.php', '/Z_ChangeLocationCode.php', '/Z_ChangeSalesmanCode.php', '/Z_ChangeStockCategory.php', '/Z_ChangeSupplierCode.php', '/Z_BottomUpCosts.php', '/Z_ReApplyCostToSA.php', '/Z_DeleteSalesTransActions.php', '/Z_ReverseSuppPaymentRun.php', '/Z_UpdateSalesAnalysisWithLatestCustomerData.php', '/Z_GLAccountUsersCopyAuthority.php', '/Z_MakeNewCompany.php', '/Z_DataExport.php', '/Z_ImportDebtors.php', '/Z_ImportStocks.php', '/Z_ImportPriceList.php', '/Z_ImportFixedAssets.php', '/Z_ImportGLTransactions.php', '/Z_UpdateChartDetailsBFwd.php', '/Z_RePostGLFromPeriod.php', '/Z_DeleteOldPrices.php', '/Z_RemovePurchaseBackOrders.php');

//$MenuItems['Utilities']['Reports']['Caption'] = array(_('Debtors Balances By Currency Totals'), _('Suppliers Balances By Currency Totals'), _('Show General Transactions That Do Not Balance'), _('List of items without picture'));

//$MenuItems['Utilities']['Reports']['URL'] = array('/Z_CurrencyDebtorsBalances.php', '/Z_CurrencySuppliersBalances.php', '/Z_CheckGLTransBalance.php', '/Z_ItemsWithoutPicture.php');


?>
