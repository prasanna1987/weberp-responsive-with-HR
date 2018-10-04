<?php

include('includes/session.php');
include('includes/barcodepack/class.code128.php');

$PtsPerMM = 2.83464567; //pdf points per mm (72 dpi / 25.4 mm per inch)

if ((isset($_POST['ShowLabels']) OR isset($_POST['SelectAll']))
	AND isset($_POST['StockCategory'])
	AND mb_strlen($_POST['StockCategory'])>=1){

	$Title = _('Print Labels');
	include('includes/header.php');

	$SQL = "SELECT prices.stockid,
					stockmaster.description,
					stockmaster.barcode,
					prices.price,
					currencies.decimalplaces
			FROM stockmaster INNER JOIN	stockcategory
   			     ON stockmaster.categoryid=stockcategory.categoryid
			INNER JOIN prices
				ON stockmaster.stockid=prices.stockid
			INNER JOIN currencies
				ON prices.currabrev=currencies.currabrev
			WHERE stockmaster.categoryid = '" . $_POST['StockCategory'] . "'
			AND prices.typeabbrev='" . $_POST['SalesType'] . "'
			AND prices.currabrev='" . $_POST['Currency'] . "'
			AND prices.startdate<='" . FormatDateForSQL($_POST['EffectiveDate']) . "'
			AND (prices.enddate='0000-00-00' OR prices.enddate>'" . FormatDateForSQL($_POST['EffectiveDate']) . "')
			AND prices.debtorno=''
			ORDER BY prices.currabrev,
				stockmaster.categoryid,
				stockmaster.stockid,
				prices.startdate";

	$LabelsResult = DB_query($SQL,'','',false,false);

	if (DB_error_no() !=0) {
		echo '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>' , prnMsg( _('The Price Labels could not be retrieved by the SQL because'). ' - ' . DB_error_msg(), 'error');
		echo '<br /><p align="right"><a href="' . $RootPath . '/index.php" class="btn btn-default">' . _('<i class="fa fa-hand-o-left fa-fw"></i> Menu') . '</a></p>';
		if ($debug==1){
			prnMsg(_('For debugging purposes the SQL used was:') . $SQL,'error');
		}
		include('includes/footer.php');
		exit;
	}
	if (DB_num_rows($LabelsResult)==0){
		echo '<div class="alert alert-warning alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>' , prnMsg(_('There were no price labels to print out for the category specified'),'warn');
		echo '<br /><p align="right"><a href="'.htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '" class="btn btn-default">' .  _('Back') . '</a></p>';
		include('includes/footer.php');
		exit;
	}


	echo '<form action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '" method="post">';
	echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';
	echo '<div class="row gutter30">
<div class="col-xs-12">
<div class="table-responsive">
<table id="general-table" class="table table-bordered">
			<tr>
				<th>' . _('Item Code') . '</th>
				<th>' . _('Item Description') . '</th>
				<th>' . _('Price') . '</th>
				<th>' . _('Print') . ' ?</th>
			</tr>
			<tr>
			
                                       
                                    
				<th colspan="4"><input type="submit" class="btn btn-info" name="SelectAll" value="' . _('Select All Labels') . '" /><label class="checkbox-inline" id="example-inline-checkbox1" for="example-inline-checkbox1">&nbsp;&nbsp; <input type="checkbox" name="CheckAll" ';
	if (isset($_POST['CheckAll'])){
		echo 'checked="checked" ';
	}
	echo 'onchange="ReloadForm(SelectAll)" /></label></td>
		</tr>';

	$i=0;
	while ($LabelRow = DB_fetch_array($LabelsResult)){
		echo '<tr>
				<td>' . $LabelRow['stockid'] . '</td>
				<td>' . $LabelRow['description'] . '</td>
				<td class="number">' . locale_number_format($LabelRow['price'],$LabelRow['decimalplaces']) . '</td>
				<td>';
		if (isset($_POST['SelectAll']) AND isset($_POST['CheckAll'])) {
			echo '<input type="checkbox" checked="checked" name="PrintLabel' . $i .'" />';
		} else {
			echo '<input type="checkbox" name="PrintLabel' . $i .'" />';
		}
		echo '</td>
			</tr>';
		echo '<input type="hidden" name="StockID' . $i . '" value="' . $LabelRow['stockid'] . '" />
			<input type="hidden" name="Description' . $i . '" value="' . $LabelRow['description'] . '" />
			<input type="hidden" name="Barcode' . $i . '" value="' . $LabelRow['barcode'] . '" />
			<input type="hidden" name="Price' . $i . '" value="' . locale_number_format($LabelRow['price'],$LabelRow['decimalplaces']) . '" />';
		$i++;
	}
	$i--;
	echo '</table></div></div></div>
		<input type="hidden" name="NoOfLabels" value="' . $i . '" />
		<input type="hidden" name="LabelID" value="' . $_POST['LabelID'] . '" />
		<input type="hidden" name="StockCategory" value="' . $_POST['StockCategory'] . '" />
		<input type="hidden" name="SalesType" value="' . $_POST['SalesType'] . '" />
		<input type="hidden" name="Currency" value="' . $_POST['Currency'] . '" />
		<input type="hidden" name="EffectiveDate" value="' . $_POST['EffectiveDate'] . '" />
		<input type="hidden" name="LabelsPerItem" value="' . $_POST['LabelsPerItem'] . '" />
		<br />
		<div class="row">
<div class="col-xs-4">
<div class="form-group">
			<input type="submit" class="btn btn-warning" name="PrintLabels" value="'. _('Print Labels'). '" />
		</div></div>
		<div class="col-xs-4">
<div class="form-group">
				<a href="'. $RootPath . '/Labels.php" class="btn btn-default">' . _('Label Template Maintenance'). '</a>
			</div></div>
			</div>
		</form>';
	include('includes/footer.php');
	exit;
}

$NoOfLabels = 0;
if (isset($_POST['PrintLabels']) AND isset($_POST['NoOfLabels']) AND $_POST['NoOfLabels']>0){

	for ($i=0;$i < $_POST['NoOfLabels'];$i++){
		if (isset($_POST['PrintLabel'.$i])){
			$NoOfLabels++;
		}
	}
	if ($NoOfLabels ==0){
		echo '<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>' , prnMsg(_('There are no labels selected to print'),'info');
	}
}
if (isset($_POST['PrintLabels']) AND $NoOfLabels>0) {

	$result = DB_query("SELECT 	description,
								pagewidth*" . $PtsPerMM . " as page_width,
								pageheight*" . $PtsPerMM . " as page_height,
								width*" . $PtsPerMM . " as label_width,
								height*" . $PtsPerMM . " as label_height,
								rowheight*" . $PtsPerMM . " as label_rowheight,
								columnwidth*" . $PtsPerMM . " as label_columnwidth,
								topmargin*" . $PtsPerMM . " as label_topmargin,
								leftmargin*" . $PtsPerMM . " as label_leftmargin
						FROM labels
						WHERE labelid='" . $_POST['LabelID'] . "'");
	$LabelDimensions = DB_fetch_array($result);

	$result = DB_query("SELECT fieldvalue,
								vpos,
								hpos,
								fontsize,
								barcode
						FROM labelfields
						WHERE labelid = '" . $_POST['LabelID'] . "'");
	$LabelFields = array();
	$i=0;
	while ($LabelFieldRow = DB_fetch_array($result)){
		if ($LabelFieldRow['fieldvalue'] == 'itemcode'){
			$LabelFields[$i]['FieldValue'] = 'stockid';
		} elseif ($LabelFieldRow['fieldvalue'] == 'itemdescription'){
			$LabelFields[$i]['FieldValue'] = 'description';
		} else {
			$LabelFields[$i]['FieldValue'] = $LabelFieldRow['fieldvalue'];
		}
		$LabelFields[$i]['VPos'] = $LabelFieldRow['vpos']*$PtsPerMM;
		$LabelFields[$i]['HPos'] = $LabelFieldRow['hpos']*$PtsPerMM;
		$LabelFields[$i]['FontSize'] = $LabelFieldRow['fontsize'];
		$LabelFields[$i]['Barcode'] = $LabelFieldRow['barcode'];
		$i++;
	}

	$PaperSize = 'Custom'; // so PDF starter wont default the DocumentPaper
	$DocumentPaper = array($LabelDimensions['page_width'],$LabelDimensions['page_height']);
	include('includes/PDFStarter.php');
	$Top_Margin = $LabelDimensions['label_topmargin'];
	$Left_Margin = $LabelDimensions['label_leftmargin'];
	$Page_Height = $LabelDimensions['page_height'];
	$Page_Width = $LabelDimensions['page_width'];
	$Right_Margin =0;
	$Bottom_Margin =0;

	$pdf->addInfo('Title', $LabelDimensions['description'] . ' ' . _('Price Labels') );
	$pdf->addInfo('Subject', $LabelDimensions['description'] . ' ' . _('Price Labels') );
	$pdf->setPrintHeader(false);
	$pdf->setPrintFooter(false);


	$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
	$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
	$pdf->setPrintHeader(false);
	$pdf->setPrintFooter(false);

	$PageNumber=1;
	//go down first then accross
	$YPos = $Page_Height - $Top_Margin; //top of current label
	$XPos = $Left_Margin; // left of current label

	$TotalLabels = $NoOfLabels * $_POST['LabelsPerItem'];
	$LabelsPrinted = 0;
	for ($i=0;$i < $_POST['NoOfLabels'];$i++){
		if (isset($_POST['PrintLabel'.$i])){
			$NoOfLabels--;
			for ($LabelNumber=0; $LabelNumber < $_POST['LabelsPerItem'];$LabelNumber++){
				foreach ($LabelFields as $Field){
					//print_r($Field);
					if ($Field['FieldValue']== 'price'){
						$Value = $_POST['Price' . $i] . ' '. $_POST['Currency'];
					} elseif ($Field['FieldValue']== 'stockid'){
						$Value = $_POST['StockID' . $i];
					} elseif ($Field['FieldValue']== 'description'){
						$Value = $_POST['Description' . $i];
					} elseif ($Field['FieldValue']== 'barcode'){
						$Value = $_POST['Barcode' . $i];
					}
					if ($Field['FieldValue'] == 'price'){ //need to format for the number of decimal places
						$LeftOvers = $pdf->addTextWrap($XPos+$Field['HPos'],$YPos-$LabelDimensions['label_height']+$Field['VPos'],$LabelDimensions['label_width']-$Field['HPos'],$Field['FontSize'],$Value);
					}elseif ($Field['FieldValue'] == 'logo'){
						$pdf->addJpegFromFile($_SESSION['LogoFile'],$XPos+$Field['HPos'],$YPos-$LabelDimensions['label_height']+$Field['VPos'],'', $Field['FontSize']);

					}elseif($Field['Barcode']==1) {

						$BarcodeImage = new code128(str_replace('_','',$Value));

						ob_start();
						imagepng(imagepng($BarcodeImage->draw()));
						$Image_String = ob_get_contents();
						ob_end_clean();

						$pdf->addJpegFromFile('@' . $Image_String,$XPos+$Field['HPos'],$YPos-$LabelDimensions['label_height']+$Field['VPos'],'', $Field['FontSize']);

					} else {
						$LeftOvers = $pdf->addTextWrap($XPos+$Field['HPos'],$YPos-$LabelDimensions['label_height']+$Field['VPos'],$LabelDimensions['label_width']-$Field['HPos'],$Field['FontSize'],$Value);
					}
				} // end loop through label fields
				$LabelsPrinted++;
				if ($LabelsPrinted < $TotalLabels){ // if there is another label to print
					//setup $YPos and $XPos for the next label
					if (($YPos - $LabelDimensions['label_rowheight']) < $LabelDimensions['label_height']){
						/* not enough space below the above label to print a new label
						 * so the above was the last label in the column
						 * need to start either a new column or new page
						 */
						if (($Page_Width - $XPos - $LabelDimensions['label_columnwidth']) < $LabelDimensions['label_width']) {
							/* Not enough space to start a new column so we are into a new page
							 */
							$pdf->newPage();
							$PageNumber++;
							$YPos = $Page_Height - $Top_Margin; //top of next label
							$XPos = $Left_Margin; // left of next label
						} else {
							/* There is enough space for another column */
							$YPos = $Page_Height - $Top_Margin; //back to the top of next label column
							$XPos += $LabelDimensions['label_columnwidth']; // left of next label
						}
					} else {
						/* There is space below to print a label
						 */
						$YPos -= $LabelDimensions['label_rowheight']; //Top of next label
					}
				}//end if there is another label to print
			}
		} //this label is set to print
	} //loop through labels selected to print


	$FileName='nERP'. '_' . _('Price_Labels') . '_' . date('Y-m-d').'.pdf';
	ob_clean();
	$pdf->OutputI($FileName);
	$pdf->__destruct();

} else { /*The option to print PDF was not hit */

	$Title= _('Price Labels');
	include('includes/header.php');

	echo '<div class="block-header"><a href="" class="header-title-link"><h1>
         ' . ' ' . _('Print Price Labels') . '</h1></a></div>';

	if (!function_exists('gd_info')) {
		echo '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>' , prnMsg(_('The GD module for PHP is required to print barcode labels. Your PHP installation is not capable currently. You will most likely experience problems with this script until the GD module is enabled.'),'error');
	}


	if (!isset($_POST['StockCategory'])) {

	/*if $StockCategory is not set then show a form to allow input	*/
echo '<div class="row gutter30">
<div class="col-xs-12">';
		echo '<form action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '" method="post">
				<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />
				<div class="row">
<div class="col-xs-4">
<div class="form-group has-error"> <label class="col-md-8 control-label">' . _('Label to print') . '</label>
					<select required="required" class="form-control" autofocus="autofocus" name="LabelID">';

		$LabelResult = DB_query("SELECT labelid, description FROM labels");
		while ($LabelRow = DB_fetch_array($LabelResult)){
			echo '<option value="' . $LabelRow['labelid'] . '">' . $LabelRow['description'] . '</option>';
		}
		echo '</select></div>
			</div>
			<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">' .  _('For Stock Category') .'</label>
				<select name="StockCategory" class="form-control">';

		$CatResult= DB_query("SELECT categoryid, categorydescription FROM stockcategory ORDER BY categorydescription");
		while ($myrow = DB_fetch_array($CatResult)){
			echo '<option value="' . $myrow['categoryid'] . '">' . $myrow['categorydescription'] . '</option>';
		}
		echo '</select></div></div>';

		echo '<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">' . _('For Sales Type/Price List').'</label>
                 <select name="SalesType" class="form-control">';
		$sql = "SELECT sales_type, typeabbrev FROM salestypes";
		$SalesTypesResult=DB_query($sql);

		while ($myrow=DB_fetch_array($SalesTypesResult)){
			if ($_SESSION['DefaultPriceList']==$myrow['typeabbrev']){
				echo '<option selected="selected" value="' . $myrow['typeabbrev'] . '">' . $myrow['sales_type'] . '</option>';
			} else {
				echo '<option value="' . $myrow['typeabbrev'] . '">' . $myrow['sales_type'] . '</option>';
			}
		}
		echo '</select></div></div></div>';

		echo '<div class="row"><div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">' . _('For Currency').'</label>
                 <select name="Currency" class="form-control">';
		$sql = "SELECT currabrev, country, currency FROM currencies";
		$CurrenciesResult=DB_query($sql);

		while ($myrow=DB_fetch_array($CurrenciesResult)){
			if ($_SESSION['CompanyRecord']['currencydefault']==$myrow['currabrev']){
				echo '<option selected="selected" value="' . $myrow['currabrev'] . '">' . $myrow['country'] . ' - ' .$myrow['currency'] . '</option>';
			} else {
				echo '<option value="' . $myrow['currabrev'] . '">' . $myrow['country'] . ' - ' .$myrow['currency'] . '</option>';
			}
		}
		echo '</select></div>
		</div>
		<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">' . _('Effective As At') . '</label>
			<input type="text" maxlength="10" size="11" class="form-control input-datepicker-close" data-date-format="dd/mm/yyyy" id="example-datepicker" name="EffectiveDate" value="' . Date($_SESSION['DefaultDateFormat']) . '" />';
        echo '</div></div>';

		echo'<div class="col-xs-4">
<div class="form-group"> <label class="col-md-8 control-label">' . _('Number of labels per item') . '</label>
			<input type="text" class="form-control" name="LabelsPerItem" size="3" value="1" /></div>';

		echo '</div></div>
				<br />
				<div class="row">
				<div class="col-xs-4">
<div class="form-group"><br />
					<input type="submit" class="btn btn-info" name="ShowLabels" value="'. _('Show Labels'). '" />
				</div>
				</div>
				
				<div class="col-xs-4">
<div class="form-group"><br />
					<a href="'. $RootPath . '/Labels.php" class="btn btn-default">' . _('Label Template Maintenance'). '</a>
				</div></div></div>
				</form>
				</div></div>
				';

	}
	include('includes/footer.php');

} /*end of else not PrintPDF */

?>