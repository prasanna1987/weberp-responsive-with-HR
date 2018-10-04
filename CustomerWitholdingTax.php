<?php
/* $Id: Suppliers.php 7751 2018-04-13 16:34:26Z raymond $ */
include('includes/session.php');
$Title = _('TDS');
/* nERP manual links before header.php */
$ViewTopic= 'AccountsPayable';
$BookMark = 'TDS';
include('includes/header.php');
include('includes/SQL_CommonFunctions.inc');
include('includes/CountriesArray.php');
//js files
echo '<script type="text/javascript" src="plugins/datatables/datatables.min.js"></script>
    <script type="text/javascript" src="plugins/datatables/sum.js"></script>
<script type="text/javascript" src="plugins/select2/js/select2.min.js"></script>';

echo '<div class="block-header"><a href="" class="header-title-link"><h1>' . ' ' . $Title . '</h1></a></div>';

    if (isset($_POST['DebtorNo'])){
			$SelectedCustomer = mb_strtoupper($_POST['DebtorNo']);
		} elseif (isset($_GET['DebtorNo'])){
			$SelectedCustomer = mb_strtoupper($_GET['DebtorNo']);
		}
		if (isset($_POST['WhtID'])){
			$SelectedWitholding = mb_strtoupper($_POST['WhtID']);
			
		} elseif (isset($_GET['WhtID'])){
			$SelectedWitholding = mb_strtoupper($_GET['WhtID']);
		}
		if (isset($_POST['New'])){
			$New = mb_strtoupper($_POST['New']);
		} elseif (isset($_GET['New'])){
			$New = mb_strtoupper($_GET['New']);
		}
		if (isset($Errors)) {
			unset($Errors);
		}
    if(!isset($_POST['DateOfCertificate']))
    {
       $_POST['DateOfCertificate'] = date($_SESSION['DefaultDateFormat'],time());
    }
		$Errors = array();
if (isset($_POST['submit'])){
		$InputError = 0;
		$i=1;
		if (empty($_POST['DebtorTrans'])) {
			$InputError = 1;
			prnMsg(_('Please select an invoice'),'error');
			$Errors[$i] = 'DebtorTrans';
			$i++;
			$New='TRUE';
		}
    if (empty($_POST['WitholdingTax']) || !is_numeric($_POST['WitholdingTax'])) {
			$InputError = 1;
			prnMsg(_('TDS is not numeric'),'error');
			$Errors[$i] = 'WitholdingTax';
			$i++;
			$New='TRUE';
		}
		if(isset($_POST['Description']) && mb_strlen($_POST['Description']) < 5)
		{
			$InputError = 1;
			prnMsg(_('Description too short '),'error');
			$Errors[$i] = 'Description';
			$i++;
			$New='TRUE';
		}
    if($InputError == 0)
		{
        //get tranaction Details
        $sql_deb_trans = DB_query("SELECT ovamount,trandate FROM debtortrans WHERE transno='".$_POST['DebtorTrans']."' AND debtorno='".$SelectedCustomer."' AND type='12' limit 1");
        $result_trans = DB_fetch_array($sql_deb_trans);
        $invoiced_amount = ($result_trans['ovamount'] < 0) ? $result_trans['ovamount']*-1 : $result_trans['ovamount'];
        $date_witheld = $result_trans['trandate'];
        //check if invoice already inserted
        if(isset($SelectedWitholding))
        {
          //this is an update
          $sql = DB_query("UPDATE customerwitholdings
                  SET debtorno='" . $SelectedCustomer . "',
                  debtortransid='" . $_POST['DebtorTrans'] . "',
                  amount='" . $invoiced_amount . "',
                  witheldamount='" . $_POST['WitholdingTax'] . "',
                  certificate='" . (mb_strlen($_POST['Certificate'])>2 ? $_POST['Certificate'] : NULL) . "',
                  date_witheld='" . $date_witheld . "',
                  status='" . $_POST['WhtStatus'] . "',
                  date_of_certificate='" . (mb_strlen($_POST['Certificate'])>2 ? FormatDateForSQL($_POST['DateOfCertificate']) : '0000-00-00') . "',
                  notes='" . (mb_strlen($_POST['Notes'])>2 ? $_POST['Notes'] : NULL) . "'
                  WHERE id='".$SelectedWitholding."'");
                  prnMsg( _('successfully updated TDS'), 'success');
        }
        else if(!isset($SelectedWitholding))
        {
        $sql = "INSERT INTO customerwitholdings(debtorno,debtortransid,amount,witheldamount,certificate,date_witheld,date_of_certificate,status,notes)
                VALUES(
                  '" . $SelectedCustomer . "',
                  '" . $_POST['DebtorTrans'] . "',
                  '" . $invoiced_amount . "',
                  '" . $_POST['WitholdingTax'] . "',
                  '" . (mb_strlen($_POST['Certificate'])>2 ? $_POST['Certificate'] : NULL) . "',
                  '" . $date_witheld . "',
                  '" . (mb_strlen($_POST['Certificate'])>2 ? FormatDateForSQL($_POST['DateOfCertificate']) : '0000-00-00') . "',
                  '" . $_POST['WhtStatus'] . "',
                  '" . (mb_strlen($_POST['Notes'])>2 ? $_POST['Notes'] : NULL) . "'
                )";
                $ErrMsg = _('The witholding tax couldnot be inserted because');
               $DbgMsg = _('The SQL used to insert witholding tax and failed was');
               $result = DB_query($sql,$ErrMsg,$DbgMsg);
               prnMsg( _('successfully added TDS'), 'success');
               unset($_POST['WitholdingTax']);
               unset($_POST['WhtStatus']);
               unset($_POST['Notes']);
        }
    }
}elseif (isset($_GET['delete'])) {
    //check status first
    $checkSql = DB_query("SELECT status FROM customerwitholdings WHERE id='".$SelectedWitholding."'");
    $checkrow = DB_fetch_array($checkSql);
    if($checkrow['status'] = 1)
    {
      prnMsg( _('Cannot Delete a cleared certificate'), 'error');
    }
    else if($checkrow['status'] = 0)
    {
      $sql_delete = DB_query("DELETE FROM customerwitholdings where id='".$SelectedWitholding."'");
      prnMsg( _('successfully deleted entry'), 'error');
      unset($SelectedWitholding);
      unset($_POST['WhtID']);
      unset($_POST['WhtStatus']);
      unset($_POST['WitholdingTax']);
      unset($_POST['Notes']);
    }
}
if (!isset($SelectedWitholding)){
  //show customers witholding taxes and show form to insert new witholding tax
  $CustomerName = '';
	$SQL = "SELECT name
			FROM debtorsmaster
			WHERE debtorno ='" . $SelectedCustomer . "' ";
	$CustomerNameResult = DB_query($SQL);
	if (DB_num_rows($CustomerNameResult) == 1) {
		$myrow = DB_fetch_row($CustomerNameResult);
		$CustomerName = $myrow[0];
	}
  if (!isset($_GET['WhtID'])){
  }
  	echo '<h3>' . ' ' . _('Customer') . ' : <strong>' . $SelectedCustomer . ' - ' . $CustomerName . '</strong> </h3>';
  	echo '<div class="row gutter30">
<div class="col-xs-12">
<div class="table-responsive">
<table id="general-table" class="table table-bordered">
        <thead>
  			<tr>
          <th>', _('Customer'), '</th>
  				<th>', _('Invoice ID'), '</th>
  				<th>', _('Invoice Amount'), '</th>
  				<th>', _('TDS Amount'), '</th>
  				<th>', _('Status'), '</th>
  				<th>', _('Certificate'), '</th>
  				<th>', _('Date Deducted'), '</th>
          <th>', _('Date of certificate'), '</th>
          <th>', _('Comments'), '</th>
  				<th colspan="2">Actions</th>
  			</tr></thead><tbody>';
        $additional_sql = '';
        if(isset($_GET['DebtorNo']))
        {
          $additional_sql = " AND customerwitholdings.debtorno='".$_GET['DebtorNo']."'";
        }
        $sql = "SELECT customerwitholdings.id,
                       customerwitholdings.status,
                        debtorsmaster.name,
                        customerwitholdings.debtorno,
                        debtortransid,
                        customerwitholdings.amount,
                        witheldamount,
                        certificate,
                        date_witheld,
                        date_of_certificate,
                        notes FROM customerwitholdings JOIN debtorsmaster on customerwitholdings.debtorno=debtorsmaster.debtorno
                      ".$additional_sql." ";
        $result = DB_query($sql);
        while ($myrow = DB_fetch_array($result)) {
          echo '<tr>';
                echo '<td>'.$myrow['name'].'</td>
                      <td>'.$myrow['debtortransid'].'</td>
                      <td>'.$myrow['amount'].'</td>
                      <td>'.$myrow['witheldamount'].'</td>
                      <td>'.(($myrow['status'] == 0) ? 'pending' : 'cleared' ).'</td>
                      <td>'.$myrow['certificate'].'</td>
                      <td>'.ConvertSQLDate($myrow['date_witheld']).'</td>
                      <td>'.ConvertSQLDate($myrow['date_of_certificate']).'</td>
                      <td>'.$myrow['notes'].'</td>';
                if(isset($_GET['DebtorNo']))
                {
                  echo'<td><a href="CustomerWitholdingTax.php?DebtorNo='.$myrow['debtorno'].'&amp;WhtID='. $myrow['id']. '&amp;edit=1" class="btn btn-info">Edit</a></td>';
                  echo'<td><a href="CustomerWitholdingTax.php?WhtID='. $myrow['id']. '&amp;delete=1" onclick="return confirm(\'', _('Are you sure you wish to delete this entry?'), '\');" class="btn btn-danger">'. _('Delete').'</a></td>';
                }
                else if(!isset($_GET['DebtorNo']))
                {
                  echo '<td></td>';
				  echo '<td></td>';
                }
              echo ' </tr>';
        }
        echo '</tbody>
       
        </table></div></div></div>
         
       <br />';
}
if (isset($SelectedWitholding)) {
	//echo '<div class="row" align="center"><a href="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '" class="btn btn-info">' . _('Show All Witholding') . '</a></div><br />';
}
if (! isset($_GET['delete'])) {
        echo'<form id="witholding_form" action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '" method="post">
      
      		<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />
      		<input type="hidden" name="DebtorNo" value="' . $SelectedCustomer. '" />';
      // The user wish to EDIT an existing wht
    	if ( isset($SelectedWitholding) AND $SelectedWitholding!='' ) {
        $sql_edit = "SELECT id,
                       status,
                        debtorno,
                        debtortransid,
                        amount,
                        witheldamount,
                        certificate,
                        date_witheld,
                        date_of_certificate,
                        notes FROM customerwitholdings WHERE id='".$SelectedWitholding."'
                      ";
                      $ErrMsg = _('The witholding tax couldnot be retrived');
                     $DbgMsg = _('The SQL used to retrive witholding tax and failed was');
                     $result = DB_query($sql_edit,$ErrMsg,$DbgMsg);
                     $wht_row = DB_fetch_array($result);
                     $_POST['DebtorTrans'] = $wht_row['witheldamount'];
                     $_POST['WitholdingTax'] = $wht_row['debtortransid'];
                     $_POST['Notes'] = $wht_row['notes'];
                     $_POST['DateOfCertificate'] = $wht_row['date_of_certificate'];
                     $_POST['Certificate'] = $wht_row['certificate'];
                     $_POST['WhtStatus'] = $wht_row['status'];
                  //   echo'<input type="hidden" name="DebtorNo" value="'.$_SESSION['CustomerID'].'" />';
                     echo'<input type="hidden" name="WhtID" value="'.$SelectedWitholding.'" />';
      }
      echo'
      	<div class="row"><div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">' . _('Invoice ') . '</label>';
            if(isset($_GET['edit']) && $_GET['edit'] == 1)
            {
              $sql_edit = DB_query("SELECT debtortransid FROM customerwitholdings where id='".$_GET['WhtID']."'");
              $sql_edit_row = DB_fetch_array($sql_edit);
              $trans_id = $sql_edit_row['debtortransid'];
              $sql_trans = DB_query("SELECT transno,trandate,ovamount,reference from debtortrans  where type='12' and debtorno='".$_SESSION['CustomerID']."' and transno='".$trans_id."' limit 1");
            }
            else if(!isset($_GET['edit']) && $_GET['edit'] != 1)
            {
              $sql_trans = DB_query("SELECT transno,trandate,ovamount,reference from debtortrans where type='12' and debtorno='".$_SESSION['CustomerID']."'");
            }
      			echo '<select name="DebtorTrans" class="form-control">';
              while ($row_trans = DB_fetch_array($sql_trans)) {
                echo '<option value="'.$row_trans['transno'].'">NO:'.$row_trans['transno'].' Amount: '.$row_trans['ovamount'].' Received on: '.$row_trans['trandate'].' Reference: '.$row_trans['reference'].'</option>';
              }
                echo '</select>'.((DB_num_rows($sql_trans) < 1) ? '<a href="' . $RootPath . '/SelectCustomer.php" class="btn btn-info">Select Customer</a>' : '').'</div></div>';
        echo '<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">' ._('TDS Amount'). '</label>
            <input type="text" class="form-control" name="WitholdingTax" value="'.$_POST['WitholdingTax'].'" /></div>
        </div>';
        echo '<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">' ._('Status'). '</label>
              <select id="wht_status" name="WhtStatus" class="form-control">
                  <option value="0" '.(($_POST['WhtStatus'] == 0) ? 'selected' : '' ). '>Not Cleared With Tax Authority</option>
                  <option value="1" '.(($_POST['WhtStatus'] == 1) ? 'selected' : '' ). '>Cleared With Tax Authority</option>
              </select></div>
        </div></div>';
        echo '<div class="row"><div class="col-xs-4">
<div class="form-group cleared_wht_details"> <label class="col-md-12 control-label">Certificate Number</label>
                  <input ' . (in_array('Certificate',$Errors) ?  'class="inputerror"' : '' ) .' type="text" class="form-control"  title="' . _('Enter the Certificate number.') . '" name="Certificate" maxlength="50" value="' . $_POST['Certificate'] . '"  /></div>
              </div>';
        echo '
                   <div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">Date of Certificate</label>
                 <input ' . (in_array('DateOfCertificate',$Errors) ?  'class="inputerror date"' : 'class="form-control input-datepicker-close"' ) .'  type="text"  name="DateOfCertificate" id="datepicker"  maxlength="10" data-date-format="dd/mm/yyyy" placeholder="dd/mm/yyyy" value="' . ConvertSQLDate($_POST['DateOfCertificate']) . '"  alt="' . $_SESSION['DefaultDateFormat'] . '" /></div>
              </div>';
        echo '<div class="col-xs-4">
<div class="form-group"> <label class="col-md-12 control-label">' ._('Comments'). '</label>
             <textarea  name="Notes" class="form-control">'.$_POST['Notes'].'</textarea></div>
        </div></div>';
        echo '<div class="row" align="center">

            	';
              if(!isset($SelectedWitholding))
              {
            		echo '<input type="submit" class="btn btn-success" name="submit" value="' . _('Submit') . '" />';
              }
              else if(isset($SelectedWitholding))
              {
            		echo '<input type="submit" class="btn btn-success" name="submit" value="' . _('Update') . '" />';
              }
            	echo '</div><br />
				
              </form>';
}
  echo '<script>
  					$( document ).ready(function() {
              //add css for datatables
              $("head").append(\'<link rel="stylesheet" type="text/css" href="plugins/datatables/datatables.min.css"/>\');
              //datatables
              // Setup - add a text input to each footer cell
  $("#whtTable tfoot th").each( function () {
      var title = $(this).text();
      $(this).html( \'<input size="6"  type="text" placeholder=".. \'+title+\'" />\' );
  } );
          var table =  $("#whtTable").DataTable({
            drawCallback: function () {
                var api = this.api();
                $(".total_footer").html(
                  api.column( 3, {page:"current"} ).data().sum()
                );
              },
               responsive: false,
               buttons: [ "excel", "pdf", "colvis" ]
          });
    table.buttons().container().insertBefore( "#whtTable_filter" );
    // Apply the search
   table.columns().every( function () {
       var that = this;
       $( "input", this.footer() ).on( "keyup change", function () {
           if ( that.search() !== this.value ) {
               that
                   .search( this.value )
                   .draw();
           }
       } );
   } );
  						var wht_status = "'.$_POST["WhtStatus"].'";
  						if(wht_status != "1")
  						{
  							$(".cleared_wht_details").hide();
  						}
  						var date_format = "'.$_SESSION["DefaultDateFormat"].'";
  						var year_format = date_format.replace("Y", "yy");
  						var month_format = year_format.replace("m", "mm");
  						var new_date_format = month_format.replace("d", "dd");
  						$(".datepicker").datepicker({
  								changeMonth: true,
  								changeYear: true,
  								showButtonPanel: true,
  								dateFormat: new_date_format
  						});
  							$("#wht_status").change(function(){
  									var status = $(this).val();
  									if(status == "1")
  									{
  										$(".cleared_wht_details").show();
  									}
  									else if(status == "0")
  									{
  										$(".cleared_wht_details").hide();
  									}
  							});
  					});
  			</script>';
if (!isset($_GET['NEW1'])) {
// show all witholding tax
}
include ('includes/footer.php');
?>