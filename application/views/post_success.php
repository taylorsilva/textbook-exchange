<?php $this->load->view('header')?>

    		<div class="container" id="body">
    			<div class="row">
					<h1>Textbook added successfully!</h1>
					<div class="h6">You will get an email shortly with a link to activate your listing. This is to verify your McMaster email address.</div>
					<div class="h6">You can view your post here after activating it: <a href="<?php echo base_url('view/'.$listing_id); ?>">CLICK HERE</a> </div>
					<div class="h6">You will get an email with information about how to edit and delete your post to your McMaster email address.</div>
				</div>
    		</div>

    <?php $this->load->view('jsScripts') ?>
	
</body>
</html>