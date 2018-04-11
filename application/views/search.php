<?php $this->load->view('header');?>
<link rel="stylesheet" href="<?php echo base_url();?>bootflat/css/search.css">
<div class="container" id="container">
	<div class="row">
		<h3>Searching for <?php echo $this->session->userdata('department').' '.$this->session->userdata('course_code')?></h3>
		<table class="table table-hover table-striped">
			<tr>
				<th class="col-sm-1">Price</th>
				<th class="col-sm-1">Edition</th>
				<th class="col-sm-6">Title</th>
				<th class="col-sm-2">Condition</th>
			</tr>
		<?php
			foreach ($listings as $record) {
				$link_to_listing = base_url('view/'.$record->listing_id);
				echo '<tr class="clickableRow" href="'.$link_to_listing.'">'.
				'<td>$'.$record->price.'</td>'.
				'<td>'.$record->edition.'</td>'.
				'<td><a href="'.$link_to_listing.'" target="_blank" class="text-capitalize">'.$record->title.'</a></td>'.
				'<td>'.$record->condition.'</td>'.
				'</tr>';
			}
		?>
		</table>
	</div>
	<div class="row text-center">
		<?php echo $pagination_html;?>
	</div>

</div>

<?php $this->load->view('jsScripts') ?>
