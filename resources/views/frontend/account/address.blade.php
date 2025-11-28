<div class="tab-pane fade active show" id="address">
    @include('admin.partials.flash_messages')
    <div class="row">
        <div class="col-lg-12">
            <div class="card mb-3 mb-lg-0">
                <div class="card-header">
                    <h5 class="mb-0">Billing Address</h5>
                </div>
                <div class="card-body">
                <form method="post">
                {{ @csrf_field() }}
                <div class="form-group">
                    <p class="mb-10 font-sm">Title</p>
                    <input type="text" name="title" required="" value="{{$address->title}}">
                    @error('title')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="form-group">
                    <p class="mb-10 font-sm">Address</p>
                    <input type="text" name="address" required="" value="{{$address->address}}">
                    @error('address')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="form-group">
                    <p class="mb-10 font-sm">Address Line 2</p>
                    <input type="text" name="area" required="" value="{{$address->area}}">
                    @error('area')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="form-group">
                    <p class="mb-10 font-sm">City / Town</p>
                    <input required="" type="text" name="city" value="{{$address->city}}">
                    @error('city')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="form-group">
                    <p class="mb-10 font-sm">Postcode / ZIP</p>
                    <input required="" type="text" name="postcode" value="{{$address->postcode}}">
                    @error('postalcode')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <button type="submit" class="btn btn-fill-out btn-block mt-30 w-100">Save</button>
                </form>
                </div>
            </div>
        </div>
    </div>
</div>