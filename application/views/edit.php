<?php $this->load->view('header')?>

			<div class="container" id="body">
    			<div class="row">
					<h3>Edit My Post</h3>
				</div>
				<div class="row">
					<?php 
					if (validation_errors()) {
						echo '<div class="alert alert-danger">'.validation_errors().'</div>';
					}
					if (isset($successful_message)) {
						echo '<div class="bg-success">'.$successful_message.'</div>';
					}?>
					<div class="col-md-8">
						<?php 
							echo form_open('edit/'.$listing_data->modifier_key, array('role' => 'form'));
						?>
						<label for="macid" class="h5">Mac ID<span class="h6">(macid@mcmaster.ca)</span>*</label>
							<input readonly type="text" class="form-control" placeholder="e.g. doej2" id="macid" name="macid" value="<?php 
								echo $listing_data->macid;
								?>">
							<label for="fname" class="h5">First Name*</label>
							<input type="text" class="form-control" placeholder="First Name" id="fname" name="fname" value="<?php 
								echo $listing_data->users_fname;
								?>">
							<label for="department" class="h5">Department*</label>
							<select class="form-control" id="department" name="department">
								<option><?php echo $listing_data->department; ?></option>
								<?php
									//Get list of departments
									$this->db->select('department')->from('courses')->distinct();
									$query = $this->db->get()->result();
									//Produce HTML code that populates the dropdown
									foreach ($query as $key => $value) {
										echo '<option>' . $value->department . '</option>';
									}
								?>
							</select>
							<label for="course_code" class="h5">Course Code*</label>
							<select class="form-control" id="course_code" name="course_code">
								<option><?php echo $listing_data->course_code; ?></option>
							</select>
							<label for="title" class="h5">Textbook Title*</label>
							<input type="text" class="form-control" placeholder="e.g. Intro to Macroeconomics" id="title" name="title" maxlength='100' value="<?php echo $listing_data->title; ?>">
							<label for="edition" class="h5">Edition*</label>
							<input type="text" class="form-control" placeholder="e.g. 4" id="edition" name="edition" value="<?php echo $listing_data->edition; ?>">
							<label for="price" class="h5">Price*</label>
							<input type="text" class="form-control" placeholder="0.00" id="price" name="price" value="<?php echo $listing_data->price; ?>">
							<!-- Optional inputs -->
							<label for="phone" class="h5">Phone Number</label>
							<input type="text" class="form-control" placeholder="647-123-4567" id="phone" name="phone" maxlength="16" value="<?php echo $listing_data->phone; ?>">
							<label for="authors" class="h5">Author(s)</label>
							<input type="text" class="form-control" placeholder="e.g. Mankiw, Jospeh" id="authors" name="authors" value="<?php echo $listing_data->authors; ?>">
							<label for="condition" class="h5">Condition</label>
							<select class="form-control" id="condition" name="condition">
								<option><?php echo $listing_data->condition; ?></option><!--Blank option if seller doesn't want to say -->
								<option>Heavy highlighting/notes</option>
								<option>Some highlighting/notes</option>
								<option>No highlighting/notes</option>
								<option>New/Unopened</option>
							</select>
							<label for="notes" class="h5">Notes</label>
							<input type="text" class="form-control" placeholder="Max 300 characters" id="notes" name="notes" maxlength="300" value="<?php echo $listing_data->notes; ?>">
							<label for="isbn" class="h5">ISBN</label>
							<input type="number" class="form-control" placeholder="e.g. 9783161484100 (Don't include dashes)" id="isbn" name="isbn" maxlength="13" value="<?php echo $listing_data->isbn; ?>">
							<button type="submit" class="btn btn-primary btn-lg" style="margin-top:20px;">Submit</button>
						</form>
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