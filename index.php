<?php		
		
		$error = "";
		$tracking_notes = "";
		
		if(isset($_POST['balance']) && $_POST['balance'] != ""){
			//Set All Money in Machine = 1,486,000 Baht [Can be changed: Index = type of banknotes, Value = amount of banknotes]
			$banknotes_array = ['1000'=>1200,'500'=>300,'100'=>800,'50'=>800,'20'=>800];
			$money_all = 0;
			foreach($banknotes_array as $index => $value){
				$money_all += (int)$index*$value;
			}
	
			$balance = $_POST['balance']; //Get input					
			end($banknotes_array);         // move the internal pointer to the end of the array
			$key_last = key($banknotes_array);
			
			//--- Start Loop Check Banknotes ---//
			$check_banknotes = $check_banknotes_even = 1;
			$check_banknotes_flow = $balance;
			
			//check flow
			foreach($banknotes_array as $k => $v){
				
				$kint = (int)$k;
				if($balance >= $kint){
					$current_amount_use = (int)($check_banknotes_flow/$kint);
					if($current_amount_use>$v){
						$temp_amount = $v;
					}else{
						$temp_amount = $current_amount_use;
					}		
					$check_banknotes_flow = ($check_banknotes_flow-($temp_amount*$kint));
				
				}
			}
			
			//check even/div 			
			foreach($banknotes_array as $k => $v){				
				$kint = (int)$k;
				if($balance >= $kint){
					if(is_int($balance/$kint)){
						$check_banknotes_even = 0;
						break;
					}				
				}
			}
			
			
			if($check_banknotes_flow == 0 || $check_banknotes_even == 0){
				$check_banknotes = 0;
				
			}
			
			//--- End Loop Check Banknotes ---//
			
			
			
			if($balance > $money_all){
				$error .= 'The machine does not have enough money.<br/>';
			}elseif($check_banknotes){ //check integer?
			
				$error .= 'The machine can dispense minimum 20 Baht banknote.<br/>';
				
			}elseif($balance <= $money_all && $balance>=((int)$key_last)){ //Check Balance and All Money
				$tracking_notes .= '<br/><br/><span style="color:blue;">Your balance is '.$balance.' Baht:</span><br /> ';
				
				// select way
				
				if($check_banknotes_flow == 0){
					foreach($banknotes_array as $index => $value){
						if($balance>=(int)$index){
													
								$current_amount_use = (int)($balance/((int)$index));						
								
								if($current_amount_use>$value){
									$temp_amount = $value;
								}else{
									$temp_amount = $current_amount_use;
								}		
								
								
								$balance = ($balance-($temp_amount*((int)$index)));
								//update money in machine
								$banknotes_array[((string)$index)] = $banknotes_array[((string)$index)]-$temp_amount;
								
								$tracking_notes .= '<span style="color:#606060;font-size:11px;">Banknotes '.((int)$index).' amount : '. $temp_amount .'  pcs. ';
								$tracking_notes .= '[The Remaining : '.$balance.']</span><br />';
								
							
						}
					}
				}elseif($check_banknotes_even == 0){
						foreach($banknotes_array as $index => $value){
							if($balance>=(int)$index){
								$current_amount_use = ($balance/((int)$index));			
								
								if(is_int($current_amount_use)){
								
									if($current_amount_use>$value){
										$temp_amount = $value;
									}else{
										$temp_amount = $current_amount_use;
									}		
									
									$balance = ($balance-($temp_amount*((int)$index)));
									//update money in machine
									$banknotes_array[((string)$index)] = $banknotes_array[((string)$index)]-$temp_amount;
									
									$tracking_notes .= '<br /><span style="color:#606060;font-size:11px;">Banknotes '.((int)$index).' amount : '. $temp_amount .'  pcs. ';
									$tracking_notes .= '[The Remaining : '.$balance.']</span><br />';
									break;
								}
							}
						}
							
						
				}else{
						$error .= 'Please withdraw a minimum of 20 baht.<br/>';
				}
				
				// End Loop Banknotes 


			}else{
				$error .= 'Please withdraw a minimum of 20 baht.<br/>';
			}
			
			$tracking_notes .= '<br/><br/><br/><span style="color:#CCCC;font-size:11px;">The money in machine is '.http_build_query($banknotes_array).'</span>';
		}
		
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>ATM - Test Excite Holidays</title>
		<link rel="favourites icon" href="favicon.ico" />
		<link href="style.css" rel="stylesheet" type="text/css" media="all">
	</head>
	<body>		

		
		
		<div class="mainw3-agile">
			<h1>Excite Holidays Banking</h1>
			<div class="main-agileinfo">
				<div class="w3pay-left">
					<div class="w3pay-left-text">
						<img src="logo.jpg" alt="">
						<h2>Excite Holidays ATM</h2>
						<p>Enter the amount you would like to withdraw. Some ATMs are restricted to certain denominations, which will be displayed on the screen.</p>
						<h3></h3> 
					</div>						
				</div>	
				<div class="w3pay-right wthree-pay-grid">
					<div class="card-bounding agileits"> 
						<form action="" method="post" name="form_balance" > 
							
							<div class="withdrawal card-details"> 
								<aside>Enter Your Withdrawal Amount:</aside>
								<input type="text" id="balance" name="balance" placeholder="฿ 100.00">
							</div>	
							<p style="color:red;font-size:11px;"><?php echo $error;?></p><br/>
							<input type="submit" value="Submit" id="process"> 
						</form> 
						<div id="tracking_notes"><p ><?php echo $tracking_notes;?></p></div>						
					</div>
				</div>	
				<div class="clear">	</div>		
			</div>	
		</div>
		
		
		
		
		
		
		
		
		
		
		
	</body>

</html>