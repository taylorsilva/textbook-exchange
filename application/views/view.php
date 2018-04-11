<?php $this->load->view('header')?>
			<link rel="stylesheet" href="<?php echo base_url();?>bootflat/css/view.css">
    		<div class="container" id="body">
    			<div class="row">
					<h3 class="text-capitalize"><?php echo $title; ?></h3>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="h5"><?php echo $department.' '.$course_code;?></div>
						<table>
							<col width="25%">
							<col width="75%">
							<tr>
								<td><span class="h5">Seller: </span></td>
								<td><span><?php echo $seller; ?></span></td>
							</tr>
							<tr>
								<td><span class="h5">Price: </span></td>
								<td><span>$<?php echo $price; ?></span></td>
							</tr>
							<tr>
								<td><span class="h5">Edition: </span></td>
								<td><span><?php echo $edition; ?></span></td>
							</tr>
							<?php // The rest is all optional data the user may have submitted 
							if ($authors != '') 
							{
								echo '<tr>
									<td><span class="h5">Author(s): </span></td>
									<td><span>'.$authors.'</span></td>
									</tr>';
							}
							if ($condition != '') 
							{
								echo '<tr>
									<td><span class="h5">Condition: </span></td>
									<td><span>'.$condition.'</span></td>
									</tr>';
							}
							if ($isbn != '') 
							{
								echo '<tr>
									<td><span class="h5">ISBN: </span></td>
									<td><span>'.$isbn.'</span></td>
									</tr>';
							}
							if ($notes != '') 
							{
								echo '<tr>
									<td><span class="h5">Notes: </span></td>
									<td><span>'.$notes.'</span></td>
									</tr>';
							}
							if ($phone != '') 
							{
								echo '<tr>
									<td><span class="h5">Phone Number: </span></td>
									<td><span>'.$phone.'</span></td>
									</tr>';
							}
							?>
						</table>
					</div>
					<div class="col-md-6">
						<?php 
							if (($this->session->flashdata('form_errors') != '') || ($this->session->flashdata('captcha_error') != '')) {
								echo '<div class="alert alert-danger">'.
								$this->session->flashdata('form_errors'). //Display validation errors
								$this->session->flashdata('captcha_error').'</div>'; //Display captcha error
							}
							// echo var_dump($this->session->flashdata('form_errors'));
							$this->load->helper('form');
							echo form_open('contact', array('role' => 'form')); 
						?>
							<div class="h4"><u>Contact Seller</u></div>
							<label for="macid">Your MacID</label>
							<input type="text" id="macid" name="macid" class="form-control" value="<?php 
								$test_cookie_macid = $this->input->cookie('macid', TRUE);
								if ($test_cookie_macid != FALSE) //See if macid is stored in a cookie from a previous successful form post
								{
									echo $test_cookie_macid;
								}
								?>">
							<label for="message">Message</label>
							<textarea id="message" name="message" class="form-control" placeholder="Ask the seller about their other textbooks listed below"></textarea>
							<?php 
								echo $recaptcha_html								
							?>
							<input type="hidden" name="listing_id" id="listing_id" value="<?php echo $listing_id; ?>">
							<button type="submit" class="btn btn-primary btn-lg" >Contact</button>
						</form>
					</div>
				</div>
				<div class="row">
					<!-- Display other textbooks this seller is selling -->
					<h4><u>Other Textbooks <?php echo $seller; ?> is Selling</u></h4>
					<div class="list-group">
						<?php
							// Just loop through each listing from the controller 
							foreach ($other_seller_textbooks as $listing) {
								echo '<u><a href="'.base_url('view/'.$listing->listing_id).'" class="list-group-item">'
								.$listing->department.' '.$listing->course_code.'<span class="pull-right">$'.$listing->price.'</span></a></u>';
							}
						?>
					</div>
				</div>
    		</div>

    <?php $this->load->view('jsScripts') ?>

	<script>
	$('#department').change(function(){
		var selected_department = $('#department').val();
		//Get course codes for selected department 
		$.post('<?php echo base_url('home/course_code/')?>', {department: selected_department}, function(data)
			{
				//Remove current selection options and repopulate #course_code with courseCodes
				$('#course_code').children().remove().end().append(data);
			});
	});
	</script>
	
</body>
</html>