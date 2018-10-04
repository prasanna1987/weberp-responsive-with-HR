<?php

include ('includes/session.php');

$Title = _('Customer Item Data');

include ('includes/header.php');

if (isset($_GET['DebtorNo'])) {
    $DebtorNo = trim(mb_strtoupper($_GET['DebtorNo']));
} elseif (isset($_POST['DebtorNo'])) {
    $DebtorNo = trim(mb_strtoupper($_POST['DebtorNo']));
}

if (isset($_GET['StockID'])) {
    $StockID = trim(mb_strtoupper($_GET['StockID']));
} elseif (isset($_POST['StockID'])) {
    $StockID = trim(mb_strtoupper($_POST['StockID']));
}

if (isset($_GET['Edit'])) {
    $Edit = true;
} elseif (isset($_POST['Edit'])) {
    $Edit = true;
} else {
	$Edit = false;
}

if (isset($_POST['StockUOM'])) {
	$StockUOM=$_POST['StockUOM'];
}

$NoCustItemData=0;

echo '<br /><p align="right"><a href="' . $RootPath . '/SelectProduct.php" class="btn btn-default">' . _('<i class="fa fa-hand-o-left fa-fw"></i> Items') . '</a></p><br />';

if (isset($_POST['cust_description'])) {
    $_POST['cust_description'] = trim($_POST['cust_description']);
}
if (isset($_POST['cust_part'])) {
    $_POST['cust_part'] = trim($_POST['cust_part']);
}

if ((isset($_POST['AddRecord']) OR isset($_POST['UpdateRecord'])) AND isset($DebtorNo)) { /*Validate Inputs */
	$InputError = 0; /*Start assuming the best */

	if ($StockID == '' OR !isset($StockID)) {
		$InputError = 1;
		echo prnMsg(_('There is no stock item set up.Enter the stock code or select a stock item using the search page'), 'error');
	}

	if (!is_numeric(filter_number_format($_POST['ConversionFactor']))) {
		$InputError = 1;
		unset($_POST['ConversionFactor']);
		echo prnMsg(_('The conversion factor entered was not numeric') . ' (' . _('a number is expected') . '). ' . _('The conversion factor is the number which the price must be divided by to get the unit price in our unit of measure') . '. <br />' . _('E.g.') . ' ' . _('The customer sells an item by the tonne and we hold stock by the kg') . '. ' . _('The debtorsmaster.price must be divided by 1000 to get to our cost per kg') . '. ' . _('The conversion factor to enter is 1000') . '. <br /><br />' . _('No changes will be made to the database'), 'error');
	}

    if ($InputError == 0 AND isset($_POST['AddRecord'])) {
        $sql = "INSERT INTO custitem (debtorno,
										stockid,
										customersuom,
										conversionfactor,
										cust_description,
										cust_part)
						VALUES ('" . $DebtorNo . "',
							'" . $StockID . "',
							'" . $_POST['customersUOM'] . "',
							'" . filter_number_format($_POST['ConversionFactor']) . "',
							'" . $_POST['cust_description'] . "',
							'" . $_POST['cust_part'] . "')";
        $ErrMsg = _('The customer Item details could not be added to the database because');
        $DbgMsg = _('The SQL that failed was');
        $AddResult = DB_query($sql, $ErrMsg, $DbgMsg);
        echo prnMsg(_('This customer data has been added to the system'), 'success');
		unset($debtorsmasterResult);
    }
    if ($InputError == 0 AND isset($_POST['UpdateRecord'])) {
        $sql = "UPDATE custitem SET customersuom='" . $_POST['customersUOM'] . "',
										conversionfactor='" . filter_number_format($_POST['ConversionFactor']) . "',
										cust_description='" . $_POST['cust_description'] . "',
										custitem.cust_part='" . $_POST['cust_part'] . "'
							WHERE custitem.stockid='" . $StockID . "'
							AND custitem.debtorno='" . $DebtorNo . "'";
        $ErrMsg = _('The customer details could not be updated because');
        $DbgMsg = _('The SQL that failed was');
        $UpdResult = DB_query($sql, $ErrMsg, $DbgMsg);
        echo prnMsg(_('customer data has been updated'), 'success');
        unset($Edit);
		unset($debtorsmasterResult);
		unset($DebtorNo);
    }

    if ($InputError == 0 AND isset($_POST['AddRecord'])) {
	/*  insert took place and need to clear the form  */
        unset($DebtorNo);
        unset($_POST['customersUOM']);
        unset($_POST['ConversionFactor']);
        unset($_POST['cust_description']);
        unset($_POST['cust_part']);

    }
}

if (isset($_GET['Delete'])) {
    $sql = "DELETE FROM custitem
	   				WHERE custitem.debtorno='" . $DebtorNo . "'
	   				AND custitem.stockid='" . $StockID . "'";
    $ErrMsg = _('The customer details could not be deleted because');
    $DelResult = DB_query($sql, $ErrMsg);
    echo prnMsg(_('This customer data record has been successfully deleted'), 'success');
    unset($DebtorNo);
}


if ($Edit == false) {

	$ItemResult = DB_query("SELECT description FROM stockmaster WHERE stockid='" . $StockID . "'");
	$DescriptionRow = DB_fetch_array($ItemResult);
	echo '<div class="block-header"><a href="" class="header-title-link"><h1>' . ' ' . $Title . ' ' . _('For Stock Code') . ' - ' . $StockID . ' - ' . $DescriptionRow['description'] . '</h1></a></div>';

    $sql = "SELECT custitem.debtorno,
				debtorsmaster.name,
				debtorsmaster.currcode,
				custitem.customersUOM,
				custitem.conversionfactor,
				custitem.cust_description,
				custitem.cust_part,
				currencies.decimalplaces AS currdecimalplaces
			FROM custitem INNER JOIN debtorsmaster
				ON custitem.debtorno=debtorsmaster.DebtorNo
			INNER JOIN currencies
				ON debtorsmaster.currcode=currencies.currabrev
			WHERE custitem.stockid = '" . $StockID . "'";
    $ErrMsg = _('The customer details for the selected part could not be retrieved because');
    $custitemResult = DB_query($sql, $ErrMsg);
    if (DB_num_rows($custitemResult) == 0 and $StockID != '') {
		echo prnMsg(_('There is no customer data set up for the item selected'), 'info');
		$NoCustItemData=1;
    } else if ($StockID != '') {

		echo '<div class="row gutter30">
<div class="col-xs-12">
<div class="table-responsive">
<table id="general-table" class="table table-bordered">
			<thead>
				<tr>
							<th class="ascending">' . _('Customer') . '</th>
							<th>' . _('Customer Unit') . '</th>
							<th>' . _('Conversion Factor') . '</th>
							<th class="ascending">' . _('Customer Item') . '</th>
							<th class="ascending">' . _('Customer Description') . '</th>
				</tr>
			</thead>
			<tbody>';

		while ($myrow = DB_fetch_array($custitemResult)) {
			printf('<tr class="striped_row">
					<td>%s</td>
					<td>%s</td>
					<td class="number">%s</td>
					<td>%s</td>
					<td>%s</td>
					<td><a href="%s?StockID=%s&amp;DebtorNo=%s&amp;Edit=1" class="btn btn-info">' . _('Edit') . '</a></td>
					<td><a href="%s?StockID=%s&amp;DebtorNo=%s&amp;Delete=1" class="btn btn-danger" onclick=\'return confirm("' . _('Are you sure you wish to delete this customer data?') . '");\'>' . _('Delete') . '</a></td>
					</tr>',
					$myrow['name'],
					$myrow['customersUOM'],
					locale_number_format($myrow['conversionfactor'],'Variable'),
					$myrow['cust_part'],
					$myrow['cust_description'],
					htmlspecialchars($_SERVER['PHP_SELF']),
					$StockID,
					$myrow['debtorno'],
					htmlspecialchars($_SERVER['PHP_SELF']),
					$StockID,
					$myrow['debtorno']);
        } //end of while loop
        echo '</tbody>
			</table></div></div></div><br />';
    } // end of there are rows to show
   
} /* Only show the existing records if one is not being edited */

if (isset($DebtorNo) AND $DebtorNo != '' AND !isset($_POST['Searchcustomer'])) {
	/*NOT EDITING AN EXISTING BUT customer selected OR ENTERED*/

    $sql = "SELECT debtorsmaster.name,
					debtorsmaster.currcode,
					currencies.decimalplaces AS currdecimalplaces
			FROM debtorsmaster
			INNER JOIN currencies
			ON debtorsmaster.currcode=currencies.currabrev
			WHERE DebtorNo='".$DebtorNo."'";
    $ErrMsg = _('The customer details for the selected customer could not be retrieved because');
    $DbgMsg = _('The SQL that failed was');
    $SuppSelResult = DB_query($sql, $ErrMsg, $DbgMsg);
    if (DB_num_rows($SuppSelResult) == 1) {
        $myrow = DB_fetch_array($SuppSelResult);
        $name = $myrow['name'];
        $CurrCode = $myrow['currcode'];
        $CurrDecimalPlaces = $myrow['currdecimalplaces'];
    } else {
        echo prnMsg(_('The customer code') . ' ' . $DebtorNo . ' ' . _('is not an existing customer in the system') . '. ' . _('You must enter an alternative customer code or select a customer using the search facility below'), 'error');
        unset($DebtorNo);
    }
} else {
	if ($NoCustItemData==0) {
		echo '<div class="block-header"><a href="" class="header-title-link"><h1>' . ' ' . $Title . ' ' . _('For Stock Code') . ' - ' . $StockID . '</h1></a></div>';
	}
    if (!isset($_POST['Searchcustomer'])) {
        echo '<form action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '" method="post">
				
					<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />
					<input type="hidden" name="StockID" value="' . $StockID . '" />
					<div class="row">
<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">' . _('Customer name') . ' ' . _('-part or full') . '</label>
					<input type="text" name="Keywords" class="form-control" size="20" maxlength="25" /></div></div>
					<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">' . _('Customer Code') . ' ' . _('-part or full') . '</label>
					<input type="text" class="form-control" name="cust_no" data-type="no-illegal-chars" size="20" maxlength="50" /></div>
				</div>
				
				<div class="col-xs-4">
<div class="form-group"><br />
					<input type="submit" class="btn btn-info" name="Searchcustomer" value="' . _('Search') . '" />
				</div></div></div><br />

			</form>';
        include ('includes/footer.php');
        exit;
    }
}

if ($Edit == true) {
	$ItemResult = DB_query("SELECT description FROM stockmaster WHERE stockid='" . $StockID . "'");
	$DescriptionRow = DB_fetch_array($ItemResult);
	echo '<div class="block-header"><a href="" class="header-title-link"><h1>' . ' ' . $Title . ' ' . _('For Stock Code') . ' - ' . $StockID . ' - ' . $DescriptionRow['description'] . '</h1></a></div>';
}

if (isset($_POST['Searchcustomer'])) {
    if (isset($_POST['Keywords']) AND isset($_POST['cust_no'])) {
        echo prnMsg( _('Customer Name keywords have been used in preference to the customer Code extract entered') . '.', 'info' );
        echo '<br />';
    }
    if ($_POST['Keywords'] == '' AND $_POST['cust_no'] == '') {
        $_POST['Keywords'] = ' ';
    }
    if (mb_strlen($_POST['Keywords']) > 0) {
        //insert wildcard characters in spaces
		$SearchString = '%' . str_replace(' ', '%', $_POST['Keywords']) . '%';

		$SQL = "SELECT debtorsmaster.DebtorNo,
						debtorsmaster.name,
						debtorsmaster.currcode,
						debtorsmaster.address1,
						debtorsmaster.address2,
						debtorsmaster.address3
				FROM debtorsmaster
				WHERE debtorsmaster.name " . LIKE  . " '".$SearchString."'";

    } elseif (mb_strlen($_POST['cust_no']) > 0) {
        $SQL = "SELECT debtorsmaster.DebtorNo,
						debtorsmaster.name,
						debtorsmaster.currcode,
						debtorsmaster.address1,
						debtorsmaster.address2,
						debtorsmaster.address3
				FROM debtorsmaster
				WHERE debtorsmaster.DebtorNo " . LIKE . " '%" . $_POST['cust_no'] . "%'";

    } //one of keywords or cust_part was more than a zero length string
    $ErrMsg = _('The cuswtomer matching the criteria entered could not be retrieved because');
    $DbgMsg = _('The SQL to retrieve customer details that failed was');
    $debtorsmasterResult = DB_query($SQL, $ErrMsg, $DbgMsg);
} //end of if search
if (isset($debtorsmasterResult) AND DB_num_rows($debtorsmasterResult) > 0) {
	if (isset($StockID)) {
        $result = DB_query("SELECT stockmaster.description,
								stockmaster.units,
								stockmaster.mbflag
						FROM stockmaster
						WHERE stockmaster.stockid='".$StockID."'");
		$myrow = DB_fetch_row($result);
		$StockUOM = $myrow[1];
		if (DB_num_rows($result) <> 1) {
			echo prnMsg(_('Stock Item') . ' - ' . $StockID . ' ' . _('is not defined in the system'), 'warn');
		}
	} else {
		$StockID = '';
		$StockUOM = 'each';
	}
	echo '<form action="' . htmlspecialchars($_SERVER['PHP_SELF']) . '" method="post">
			<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />
			<div class="row gutter30">
<div class="col-xs-12">
<div class="table-responsive">
<table id="general-table" class="table table-bordered">
			<thead>
				<tr>
						<th class="ascending">' . _('Code') . '</th>
	                	<th class="ascending">' . _('Customer Name') . '</th>
						<th class="ascending">' . _('Currency') . '</th>
						<th class="ascending">' . _('Address 1') . '</th>
						<th class="ascending">' . _('Address 2') . '</th>
						<th class="ascending">' . _('Address 3') . '</th>
				</tr>
			</thead>
			<tbody>';

    while ($myrow = DB_fetch_array($debtorsmasterResult)) {
		printf('<tr class="striped_row">
				<td><input type="submit" name="DebtorNo" class="btn btn-info" value="%s" /></td>
				<td>%s</td>
				<td>%s</td>
				<td>%s</td>
				<td>%s</td>
				<td>%s</td>
				</tr>',
				$myrow['DebtorNo'],
				$myrow['name'],
				$myrow['currcode'],
				$myrow['address1'],
				$myrow['address2'],
				$myrow['address3']);

        echo '<input type="hidden" name="StockID" value="' . $StockID . '" />';
        echo '<input type="hidden" name="StockUOM" value="' . $StockUOM . '" />';

    }
    //end of while loop
    echo '</tbody>
		</table>
			</div></div></div><br />

			</form>';
}
//end if results to show

/*Show the input form for new customer details */
if (!isset($debtorsmasterResult)) {
	if ($Edit == true OR isset($_GET['Copy'])) {

		 $sql = "SELECT custitem.debtorno,
						debtorsmaster.name,
						debtorsmaster.currcode,
						custitem.customersUOM,
						custitem.cust_description,
						custitem.conversionfactor,
						custitem.cust_part,
						stockmaster.units,
						currencies.decimalplaces AS currdecimalplaces
				FROM custitem INNER JOIN debtorsmaster
					ON custitem.debtorno=debtorsmaster.DebtorNo
				INNER JOIN stockmaster
					ON custitem.stockid=stockmaster.stockid
				INNER JOIN currencies
					ON debtorsmaster.currcode = currencies.currabrev
				WHERE custitem.debtorno='" . $DebtorNo . "'
				AND custitem.stockid='" . $StockID . "'";

		$ErrMsg = _('The customer purchasing details for the selected customer and item could not be retrieved because');
		$EditResult = DB_query($sql, $ErrMsg);
		$myrow = DB_fetch_array($EditResult);
		$name = $myrow['name'];

		$CurrCode = $myrow['currcode'];
		$CurrDecimalPlaces = $myrow['currdecimalplaces'];
		$_POST['customersUOM'] = $myrow['customersUOM'];
		$_POST['cust_description'] = $myrow['cust_description'];
		$_POST['ConversionFactor'] = locale_number_format($myrow['conversionfactor'],'Variable');
		$_POST['cust_part'] = $myrow['cust_part'];
		$StockUOM=$myrow['units'];
    }
    echo '<form action="' . htmlspecialchars($_SERVER['PHP_SELF']) . '" method="post">
		<div class="row">';
	echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';
    if (!isset($DebtorNo)) {
        $DebtorNo = '';
    }
	if ($Edit == true) {
        echo '<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">' . _('Customer Name') . '</label>
				<input type="hidden" name="DebtorNo" value="' . $DebtorNo . '" />' . $DebtorNo . ' - ' . $name . '</div>
			</div>';
    } else {
        echo '<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">' . _('Customer Name') . '</label>
				<input type="hidden" name="DebtorNo" maxlength="10" size="11" value="' . $DebtorNo . '" />';

		if ($DebtorNo!='') {
			echo '' . $name;
		}
		if (!isset($name) OR $name = '') {
			echo '(' . _('A search facility is available below if necessary') . ')';
		} else {
			echo '' . $name;
		}
		echo '</div></div>';
	}
	echo '<input type="hidden" name="StockID" maxlength="10" size="11" value="' . $StockID . '" />';
	if (!isset($CurrCode)) {
		$CurrCode = '';
	}

	if (!isset($_POST['customersUOM'])) {
		$_POST['customersUOM'] = '';
	}
	if (!isset($_POST['cust_description'])) {
		$_POST['cust_description'] = '';
	}
	if (!isset($_POST['cust_part'])) {
		$_POST['cust_part'] = '';
	}
	echo '<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">' . _('Currency') . '</label>
			<input type="hidden" name="CurrCode" . value="' . $CurrCode . '" />' . $CurrCode . '</div>
		</div></div>
		<div class="row">
			<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">' . _('Our Unit of Measure') . '</label>';

	if (isset($DebtorNo)) {
		echo '' . $StockUOM . '</div></div>';
	}
	echo '<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">' . _('Customer Unit of Measure') . '</label>
			<input type="text" class="form-control" name="customersUOM" size="20" maxlength="20" value ="' . $_POST['customersUOM'] . '"/></div>
		</div>';

	if (!isset($_POST['ConversionFactor']) OR $_POST['ConversionFactor'] == '') {
		$_POST['ConversionFactor'] = 1;
	}

	echo '<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">' . _('Conversion Factor (to our UOM)') . '</label>
			<input type="text" class="form-control" name="ConversionFactor" maxlength="12" size="12" value="' . $_POST['ConversionFactor'] . '" /></div>
		</div>
		</div>
		<div class="row">
			<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">' . _('Customer Stock Code') . '</label>
			<input type="text" class="form-control" name="cust_part" maxlength="20" size="20" value="' . $_POST['cust_part'] . '" /></div>
		</div>
		<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">' . _('Customer Stock Description') . '</label>
			<input type="text" class="form-control" name="cust_description" maxlength="30" size="30" value="' . $_POST['cust_description'] . '" /></div>
		</div>';


	

	if ($Edit == true) {
		echo '<div class="col-xs-4">
<div class="form-group"> <br /><input type="submit" class="btn btn-info" name="UpdateRecord" value="' . _('Update') . '" /></div></div>';
		echo '<input type="hidden" name="Edit" value="1" />';
	} else {
		echo '<div class="col-xs-4">
<div class="form-group"> <br /><input type="submit" class="btn btn-success" name="AddRecord" value="' . _('Add') . '" /></div></div>';
	}

	echo '</div><br />

		<div class="row">';

	if (isset($StockLocation) AND isset($StockID) AND mb_strlen($StockID) != 0) {
		echo '<div class="col-xs-3"><a href="' . $RootPath . '/StockStatus.php?StockID=' . $StockID . '" class="btn btn-info">' . _('Show Stock Status') . '</a></div>';
		echo '<div class="col-xs-3"><a href="' . $RootPath . '/StockMovements.php?StockID=' . $StockID . '&StockLocation=' . $StockLocation . '" class="btn btn-info">' . _('Show Stock Movements') . '</a></div>';
		echo '<div class="col-xs-3"><a href="' . $RootPath . '/SelectSalesOrder.php?SelectedStockItem=' . $StockID . '&StockLocation=' . $StockLocation . '" class="btn btn-info">' . _('Search Outstanding Sales Orders') . '</a></div>';
		echo '<div class="col-xs-3"><a href="' . $RootPath . '/SelectCompletedOrder.php?SelectedStockItem=' . $StockID . '" class="btn btn-info">' . _('Search Completed Sales Orders') . '</a></div>';
	}
	echo '</div><br />
</form>';
}

include ('includes/footer.php');
?>
