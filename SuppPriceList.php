<?php
/*  */

include('includes/session.php');

if (isset($_GET['SelectedSupplier'])) {
	$_POST['supplierid']=$_GET['SelectedSupplier'];
}

if (isset($_POST['PrintPDF']) OR isset($_POST['View'])) {

	include('includes/PDFStarter.php');

	$FontSize=9;
	$pdf->addInfo('Title',_('Supplier Price List'));
	$pdf->addInfo('Subject',_('Price List of goods from a Supplier'));

	$PageNumber=1;
	$line_height=12;

	//get supplier
	$sqlsup = "SELECT suppname,
					  currcode,
					  decimalplaces AS currdecimalplaces
				FROM suppliers INNER JOIN currencies
				ON suppliers.currcode=currencies.currabrev
				WHERE supplierid='" . $_POST['supplier'] . "'";
	$resultsup = DB_query($sqlsup);
	$RowSup = DB_fetch_array($resultsup);
	$SupplierName=$RowSup['suppname'];
	$CurrCode =$RowSup['currcode'];
	$CurrDecimalPlaces=$RowSup['currdecimalplaces'];

	//get category
	if ($_POST['category']!='all'){
		$sqlcat="SELECT categorydescription
				FROM `stockcategory`
				WHERE categoryid ='" . $_POST['category'] . "'";

		$resultcat = DB_query($sqlcat);
		$RowCat = DB_fetch_row($resultcat);
		$Categoryname=$RowCat['0'];
	} else {
		$Categoryname='ALL';
	}


	//get date price
	if ($_POST['price']=='all'){
		$CurrentOrAllPrices=_('All Prices');
	} else {
		$CurrentOrAllPrices=_('Current Price');
	}

	//price and category = all
	if (($_POST['price']=='all') AND ($_POST['category']=='all')){
		$sql = "SELECT 	purchdata.stockid,
					stockmaster.description,
					purchdata.price,
					purchdata.conversionfactor,
					(purchdata.effectivefrom)as dateprice,
					purchdata.supplierdescription,
					purchdata.suppliers_partno
				FROM purchdata,stockmaster
				WHERE supplierno='" . $_POST['supplier'] . "'
				AND stockmaster.stockid=purchdata.stockid
				ORDER BY stockid ASC ,dateprice DESC";
	} else {
	//category=all and price != all
		if (($_POST['price']!='all') AND ($_POST['category']=='all')){

			$sql = "SELECT purchdata.stockid,
							stockmaster.description,
							(SELECT purchdata.price
							 FROM purchdata
							 WHERE purchdata.stockid = stockmaster.stockid
							 ORDER BY effectivefrom DESC
							 LIMIT 0,1) AS price,
							purchdata.conversionfactor,
							(SELECT purchdata.effectivefrom
							 FROM purchdata
							 WHERE purchdata.stockid = stockmaster.stockid
							 ORDER BY effectivefrom DESC
							 LIMIT 0,1) AS dateprice,
							purchdata.supplierdescription,
							purchdata.suppliers_partno
					FROM purchdata, stockmaster
					WHERE supplierno = '" . $_POST['supplier'] . "'
					AND stockmaster.stockid = purchdata.stockid
					GROUP BY stockid
					ORDER BY stockid ASC , dateprice DESC";
		} else {
			//price = all category !=all
			if (($_POST['price']=='all')and($_POST['category']!='all')){

				$sql = "SELECT 	purchdata.stockid,
								stockmaster.description,
								purchdata.price,
								purchdata.conversionfactor,
								(purchdata.effectivefrom)as dateprice,
								purchdata.supplierdescription,
								purchdata.suppliers_partno
						FROM purchdata,stockmaster
						WHERE supplierno='" . $_POST['supplier'] . "'
						AND stockmaster.stockid=purchdata.stockid
						AND stockmaster.categoryid='" . $_POST['category'] .  "'
						ORDER BY stockid ASC ,dateprice DESC";
			} else {
			//price != all category !=all
				$sql = "SELECT 	purchdata.stockid,
								stockmaster.description,
								(SELECT purchdata.price
								 FROM purchdata
								 WHERE purchdata.stockid = stockmaster.stockid
								 ORDER BY effectivefrom DESC
								 LIMIT 0,1) AS price,
								purchdata.conversionfactor,
								(SELECT purchdata.effectivefrom
								FROM purchdata
								WHERE purchdata.stockid = stockmaster.stockid
								ORDER BY effectivefrom DESC
								LIMIT 0,1) AS dateprice,
								purchdata.supplierdescription,
								purchdata.suppliers_partno
						FROM purchdata,stockmaster
						WHERE supplierno='" . $_POST['supplier'] . "'
						AND stockmaster.stockid=purchdata.stockid
						AND stockmaster.categoryid='" . $_POST['category'] .  "'
						GROUP BY stockid
						ORDER BY stockid ASC ,dateprice DESC";
			}
		}
	}
	$result = DB_query($sql,'','',false,true);

	if (DB_error_no() !=0) {
		$Title = _('Price List') . ' - ' . _('Problem Report');
		include('includes/header.php');
		echo prnMsg( _('The Price List could not be retrieved by the querry because') . ' '  . DB_error_msg(),'error');
		echo '<br /><p align="left"><a href="' . $RootPath . '/menu_data.php?Application=PO" class="btn btn-default">' . _('Back to Menu') . '</a></p>';

		if ($debug==1){
			echo '<br />' . $sql;
		}
		include('includes/footer.php');
		exit;
	}

	if (DB_num_rows($result)==0) {

		$Title = _('Supplier Price List') . '-' . _('Report');
		include('includes/header.php');
		echo prnMsg(_('There are no result so the PDF is empty'));
		include('includes/footer.php');
		exit;
	}
	if (!isset($_POST['View'])) {
	PrintHeader($pdf,$YPos,$PageNumber,$Page_Height,$Top_Margin,$Left_Margin,
	            $Page_Width,$Right_Margin,$SupplierName,$Categoryname,$CurrCode,$CurrentOrAllPrices);

	$FontSize=8;
	$code='';
	while ($myrow = DB_fetch_array($result)){
		$YPos -=$line_height;

		$PriceDated=ConvertSQLDate($myrow[4]);

		//if item has more than 1 price, write only price, date and supplier code for the old ones
		if ($code==$myrow['stockid']){

			$pdf->addTextWrap(350,$YPos,50,$FontSize,locale_number_format($myrow['price'],$CurrDecimalPlaces),'right');
			$pdf->addTextWrap(410,$YPos,50,$FontSize,$PriceDated,'left');
			$pdf->addTextWrap(470,$YPos,90,$FontSize,$myrow['suppliers_partno'],'left');
			$code=$myrow['stockid'];
		} else {
			$code=$myrow['stockid'];
			$pdf->addTextWrap(30,$YPos,100,$FontSize,$myrow['stockid'],'left');
			$pdf->addTextWrap(135,$YPos,160,$FontSize,$myrow['description'],'left');
			$pdf->addTextWrap(300,$YPos,50,$FontSize,locale_number_format($myrow['conversionfactor'],'Variable'),'right');
			$pdf->addTextWrap(350,$YPos,50,$FontSize,locale_number_format($myrow['price'],$CurrDecimalPlaces),'right');
			$pdf->addTextWrap(410,$YPos,50,$FontSize,$PriceDated,'left');
			$pdf->addTextWrap(470,$YPos,90,$FontSize,$myrow['suppliers_partno'],'left');
		}


		if ($YPos < $Bottom_Margin + $line_height){

			PrintHeader($pdf,$YPos,$PageNumber,$Page_Height,$Top_Margin,$Left_Margin,$Page_Width,
			            $Right_Margin,$SupplierName,$Categoryname,$CurrCode,$CurrentOrAllPrices);
		}


	} /*end while loop  */


	if ($YPos < $Bottom_Margin + $line_height){
	       PrintHeader($pdf,$YPos,$PageNumber,$Page_Height,$Top_Margin,$Left_Margin,$Page_Width,
	                   $Right_Margin,$SupplierName,$Categoryname,$CurrCode,$CurrentOrAllPrices);
	}


	$pdf->OutputD( $_SESSION['DatabaseName'] . '_SupplierPriceList_' . Date('Y-m-d') . '.pdf');
	} else {
		$Title = _('View supplier price');
		include('includes/header.inc');
		echo '<br /><p align="right"><a href="'.htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUTOES,'UTF-8').'" class="btn btn-default">'._('<i class="fa fa-hand-o-left fa-fw"></i> Return').'</a></p>';
		echo '<div class="block-header"><a href="" class="header-title-link"><h1>'. _('Supplier Price List for').' : '.$CurrentOrAllPrices . '<br /><small>'
			._('Supplier').'   : '.$SupplierName.' , '._('Category').' : '.$Categoryname.
			'</small></h1></a></div>';

		echo '<div class="row gutter30">
<div class="col-xs-12">
<div class="table-responsive">
<table id="general-table" class="table table-bordered">
			<thead>
				<tr>
					<th class="ascending">' . _('Code') . '</th>
				<th>' . _('Description') . '</th>
				<th>' . _('Conv Factor') . '</th>
				<th>' . _('Price') . '</th>
				<th class="ascending">' . _('Date From') . '</th>
				<th>' . _('Supp Code') . '</th>
				</tr>
			</thead>
			<tbody>';

		while ($myrow = DB_fetch_array($result)){
			echo '<tr class="striped_row">
				<td>' . $myrow['stockid'] . '</td>
				<td>' . $myrow['description'] . '</td>
				<td>' . $myrow['conversionfactor'] . '</td>
				<td>' . $myrow['price'] . '</td>
				<td>' . ConvertSQLDate($myrow['dateprice']) . '</td>
				<td>' . $myrow['suppliers_partno'] . '</td>
				</tr>';

		}

		echo '</tbody></table></div></div></div>';
		include('includes/footer.inc');
	}

} else { /*The option to print PDF was not hit so display form */

	$Title=_('Supplier Price List');
	include('includes/header.php');
	echo '<div class="block-header"><a href="" class="header-title-link"><h1>' . ' ' . _('Supplier Price List') . '</h1></a></div>';

	echo '<div class="row gutter30">
<div class="col-xs-12">';
	echo '
		<form action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '" method="post">';

    echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';

	$sql = "SELECT supplierid,suppname FROM `suppliers`";
	$result = DB_query($sql);
	echo '<div class="row">
<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">' . _('Supplier') . '</label>
				<select name="supplier" class="form-control"> ';
	while ($myrow=DB_fetch_array($result)){
		if (isset($_POST['supplierid']) and ($myrow['supplierid'] == $_POST['supplierid'])) {
			 echo '<option selected="selected" value="' . $myrow['supplierid'] . '">' . $myrow['supplierid'].' - '.$myrow['suppname'] . '</option>';
		} else {
			 echo '<option value="' . $myrow['supplierid'] . '">' . $myrow['supplierid'].' - '.$myrow['suppname'] . '</option>';
		}
	}
	echo '</select></div>
		</div>';

	$sql="SELECT categoryid, categorydescription FROM stockcategory";
	$result = DB_query($sql);
	echo '<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">' . _('Category') . '</label>
			<select name="category" class="form-control"> ';
		echo '<option value="all">' . _('ALL') . '</option>';
	while ($myrow=DB_fetch_array($result)){
		if (isset($_POST['categoryid']) and ($myrow['categoryid'] == $_POST['categoryid'])) {
			 echo '<option selected="selected" value="' . $myrow['categoryid'] . '">' . $myrow['categoryid']-$myrow['categorydescription'] . '</option>';
		} else {
			 echo '<option value="' . $myrow['categoryid'] . '">' .$myrow['categoryid'].' - '. $myrow['categorydescription'] . '</option>';
		}
	}
	echo '</select></div>
		</div>';
	echo '<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">' . _('Price List') . '</label>
			<select name="price" class="form-control">
				<option value="all">' ._('All Prices') . '</option>
				<option value="current">' ._('Only Current Price') . '</option>
				</select>
			</div>
		</div>';
	echo '</div>
			
			<div class="row" align="center">
			<div>
<div class="form-group">
				<input type="submit" name="PrintPDF" class="btn btn-warning" value="' . _('Print PDF') . '" />
			</div></div>';

    echo '</div>
          </form></div></div>';
	include('includes/footer.php');

} /*end of else not PrintPDF */



function PrintHeader(&$pdf,&$YPos,&$PageNumber,$Page_Height,$Top_Margin,$Left_Margin,
                     $Page_Width,$Right_Margin,$SupplierName,$Categoryname,$CurrCode,$CurrentOrAllPrices) {


	/*PDF page header for Supplier price list */
	if ($PageNumber>1){
		$pdf->newPage();
	}
	$line_height=12;
	$FontSize=9;
	$YPos= $Page_Height-$Top_Margin;
	$YPos -=(3*$line_height);

	$pdf->addTextWrap($Left_Margin,$YPos,300,$FontSize+2,$_SESSION['CompanyRecord']['coyname']);
	$YPos -=$line_height;

	$pdf->addTextWrap($Left_Margin,$YPos,150,$FontSize,_('Supplier Price List for').' '.$CurrentOrAllPrices);

	$pdf->addTextWrap($Page_Width-$Right_Margin-150,$YPos,160,$FontSize,_('Printed') . ': ' .
		 Date($_SESSION['DefaultDateFormat']) . '   ' . _('Page') . ' ' . $PageNumber,'left');
	$YPos -= $line_height;
	$pdf->addTextWrap($Left_Margin,$YPos,50,$FontSize,_('Supplier').'   ');
	$pdf->addTextWrap(95,$YPos,150,$FontSize,': '.$SupplierName);

	$YPos -= $line_height;
	$pdf->addTextWrap($Left_Margin,$YPos,50,$FontSize,_('Category').' ');

	$pdf->addTextWrap(95,$YPos,150,$FontSize,': '.$Categoryname);
	$YPos -= $line_height;
	$pdf->addTextWrap($Left_Margin,$YPos,50,$FontSize,_('Currency').'  ');
	$pdf->addTextWrap(95,$YPos,50,$FontSize,': '.$CurrCode);
	$YPos -=(2*$line_height);
	/*set up the headings */

	$pdf->addTextWrap(30,$YPos,80,$FontSize,_('Code'), 'left');
	$pdf->addTextWrap(135,$YPos,80,$FontSize,_('Description'), 'left');
	$pdf->addTextWrap(300,$YPos,50,$FontSize,_('Conv Factor'), 'left');
	$pdf->addTextWrap(370,$YPos,50,$FontSize,_('Price'), 'left');
	$pdf->addTextWrap(410,$YPos,80,$FontSize,_('Date From'), 'left');
	$pdf->addTextWrap(470,$YPos,80,$FontSize,_('Supp Code'), 'left');

	$FontSize=8;
	$PageNumber++;
} // End of PrintHeader() function
?>
