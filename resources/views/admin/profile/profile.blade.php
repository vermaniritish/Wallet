<div class="card card-profile">
	<img src="<?php echo url('assets/img/theme/img-1-1000x600.jpg') ?>" alt="Image placeholder" class="card-img-top">
	<div class="row justify-content-center">
		<div class="col-12 text-center">
		<!-- <div class="col-lg-3 order-lg-2"> -->
			<div class="card-profile-image">
				<?php $image = $admin->getResizeImagesAttribute(); ?>
				<a class="prof_image_sidebar">
					<img src="<?php echo isset($image['medium']) ? url($image['medium']) : url('assets/img/noprofile.jpg') ?>" class="rounded-circle">
					<span><i id="loading-image" class="fa fa-spin fa-spinne"></i></span>
				</a>
				<span class="change_profile_icon">
					<a href="javascript:;" onclick="$(this).next().click()" data-title="Change Picture" data-toggle="tooltip">
						<i class="fas fa-camera"></i>
					</a>
					<input type="file" style="display:none" name="" id="profile_img" data-url="<?php echo route('admin.profile.updatePicture') ?>" data-id="<?php echo $admin->id ?>">
				</span>
			</div>
		</div>
	</div>
	
	<div class="card-body pt-0">
		<div class="text-left">
			<h5 class="h3">
				<?php echo $admin->first_name . ' ' . $admin->last_name; ?>
			</h5>
			<?php if($admin->address): ?>
			<div class="h5 font-weight-300">
				<i class="ni ni-pin-3 mr-2"></i> <?php echo $admin->address ?>
			</div>
			<?php endif; ?>
		</div>
	</div>
</div>