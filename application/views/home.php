<?php $this->load->view('header')?>
<link rel="stylesheet" href="<?php echo base_url();?>bootflat/css/home.css">
<!--[if lte IE 7]><link rel="stylesheet" href="<?php echo base_url();?>bootflat/css/home-ie.css"/><![endif]-->;
		<div class="outer">
		<div class="middle">
    		<div class="inner">
    			<div class="row">
					<h3 class="pull-left" style="margin-top:0px;margin-bottom:0px">Buy and Sell textbooks from other students</h3>
				</div>
				<div class="row">
					<h1 style="margin-top:0px"><small style="padding-bottom:0px">Select a class</small></h1>
				</div>
				<div class="row">
					<?php 
						$this->load->helper('form');
						echo form_open('search', array('class' => 'form-inline', 'role' => 'form'));
					?>
						<label for="department" class="pull-left h5" style="margin-right:10px;">Department:</label>
						<select class="form-control pull-left" id="department" name="department" style="margin-right:20px;">
							<option>--------------------------------------------------</option>
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
						<label class="pull-left h5" style="margin-right:10px;">Course Code: </label>
						<select class="form-control pull-left" id="course_code" name="course_code" style="margin-right:20px;">
							<option>----------</option>
						</select>
						<button type="submit" class="btn btn-danger pull-left">Search</button>
					</form>
				</div>
				<a href="<?php echo base_url();?>view/pidvr45mMwK4">Click here to view a sample listing!</a>
				<?php 
					if (isset($error_msg)) {
						echo '<div class="alert alert-danger" role="alert">'.$error_msg.'</div>';
					}
				?>
    		</div>
		</div>



	<!-- Bootstrap -->
    <script src="https://code.jquery.com/jquery-1.11.0.min.js"></script>
    <script src="https://netdna.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
	<!-- Bootflat's JS files.-->
	<script src="<?php echo base_url();?>bootflat/js/icheck.min.js"></script>
	<script src="<?php echo base_url();?>bootflat/js/jquery.fs.selecter.min.js"></script>
	<script src="<?php echo base_url();?>bootflat/js/jquery.fs.stepper.min.js"></script>

	<script>
	// If users searchs then clicks the back it will load the course_code
	window.onload = function() {
		var selected_department = $('#department').val();
		//Get course codes for selected department 
		if (selected_department != '--------------------------------------------------') {
		$.post('<?php echo base_url('home/course_code/')?>', {department: selected_department}, function(data)
			{
				//Remove current selection options and repopulate #course_code with courseCodes
				$('#course_code').children().remove().end().append(data);
			});
		};
	};
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