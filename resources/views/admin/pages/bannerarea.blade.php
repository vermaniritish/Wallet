<?php use App\Models\Admin\HomePage; ?>
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <label class="form-control-label">Label</label>
            <input type="text" class="form-control"  placeholder="Label" name="<?php echo $key . '_label' ?>" value="<?php echo HomePage::get($key . '_label') ?>">
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group">
            <label class="form-control-label">Heading</label>
            <textarea rows="2" class="form-control" placeholder="Heading" name="<?php echo $key . '_heading' ?>"><?php echo HomePage::get($key . '_heading') ?></textarea>
            @error('sub_heading')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
    </div>
    @if(isset($subheading) && $subheading)
    <div class="col-md-12">
        <div class="form-group">
            <label class="form-control-label">Sub Heading</label>
            <textarea rows="2" class="form-control" placeholder="Sub Heading" name="<?php echo $key . '_subheading' ?>"><?php echo HomePage::get($key . '_subheading') ?></textarea>
            @error('sub_heading')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
    </div>
    @endif
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <div 
                class="upload-image-section"
                data-type="image"
                data-multiple="false"
                data-path="home"
                data-resize-large="{{$imagesize}}"
            >
                <div class="upload-section">
                    <div class="button-ref mb-3">
                        <button class="btn btn-icon btn-primary btn-lg" type="button">
                            <span class="btn-inner--icon"><i class="fas fa-upload"></i></span>
                            <span class="btn-inner--text">Upload Image</span>
                        </button>
                        <p><small>Recomeded Size: {{$imagesize}}</small></p>
                    </div>
                    <!-- PROGRESS BAR -->
                    <div class="progress d-none">
                    <div class="progress-bar bg-default" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                    </div>
                </div>
                <!-- INPUT WITH FILE URL -->
                <?php $image = HomePage::get($key . '_image') ?>
                <textarea class="d-none" name="<?php echo $key . '_image' ?>"><?php echo $image ?></textarea>
                <div class="show-section <?php echo !$image ? 'd-none' : "" ?>">
                    @include('admin.partials.previewFileRender', ['file' =>  $image])
                </div>
            </div>
        </div>
    </div>
    <?php if(!isset($nobuttons) || !$nobuttons): ?>
    <div class="col-md-6">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="form-control-label" for="input-first-name">Button (on/off)</label>
                    <div class="custom-control mt-2">
                        <label class="custom-toggle">
                            <input type="hidden" name="<?php echo $key . '_button_status' ?>" value="0" />
                            <input type="checkbox" id="buttonStatus" value="1" name="<?php echo $key . '_button_status' ?>" <?php echo (HomePage::get($key . '_button_status') ? 'checked' : '') ?>>
                            <span class="custom-toggle-slider rounded-circle"
                                data-label-off="No" data-label-on="Yes"></span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="form-control-label" for="input-first-name">Button Title</label>
                    <input type="text" class="form-control" placeholder="Button Title" name="<?php echo $key . '_button_title' ?>" value="{{ HomePage::get($key . '_button_title') }}">
                    @error('button_title')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label class="form-control-label" for="input-first-name">Button Url</label>
                    <input type="text" class="form-control" placeholder="Button Url" name="<?php echo $key . '_button_url' ?>" value="{{ HomePage::get($key . '_button_url') }}">
                    @error('button_url')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
</div>