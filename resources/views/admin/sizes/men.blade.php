<div id="men">
    <div class="card">
        <div class="card-body">
            <form method="post" action="<?php echo route('admin.size.add') ?>" class="form-validation">
                <!--!! CSRF FIELD !!-->
                {{ @csrf_field() }}
                <h6 class="heading-small text-muted mb-4">Male Size information</h6>
                <div class="">
                    <div v-for="(men, index) in mens" :key="index" >
                        <div class=" mt-2 d-flex position-relative pe-0">
                            <input type="hidden" value="Male" name="type" >
                            <input type="hidden"  name="id" v-if="men.id"  :name="'mens[' + index + '][id]'" :value="men.id" >
                            <table class="row w-100 p-3">
                                <tr>
                                <td>
                                    <div class="form-group">
                                        <label class="form-control-label" for="input-first-name">Size Type</label>
                                        <input type="text" class="form-control" v-model="men.size_title" :name="'mens[' + index + '][size_title]'"   required placeholder="XL" value="{{ old('color_code') }}">
                                        @error('color_code')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <label class="form-control-label" for="input-first-name">From</label>
                                        <input type="number" class="form-control" v-model="men.from_cm" :name="'mens[' + index + '][from_cm]'"  placeholder="cm" value="{{ old('color_code') }}" min="0">
                                        @error('color_code')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <label class="form-control-label" for="input-first-name">To</label>
                                        <input type="number" class="form-control" v-model="men.to_cm" :name="'mens[' + index + '][to_cm]'"  placeholder="cm" value="{{ old('color_code') }}" min="0">
                                        @error('color_code')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <label class="form-control-label" for="input-first-name">Chest</label>
                                        <input type="number" class="form-control" v-model="men.chest" :name="'mens[' + index + '][chest]'"  placeholder="cm" value="{{ old('color_code') }}" min="0">
                                        @error('color_code')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <label class="form-control-label" for="input-first-name">Waist</label>
                                        <input type="number" class="form-control" v-model="men.waist" :name="'mens[' + index + '][waist]'"  placeholder="cm" value="{{ old('color_code') }}" min="0">
                                        @error('color_code')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <label class="form-control-label" for="input-first-name">Hip</label>
                                        <input type="number" class="form-control" v-model="men.hip" :name="'mens[' + index + '][hip]'"  placeholder="cm" value="{{ old('color_code') }}" min="0">
                                        @error('color_code')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <label class="form-control-label" for="input-first-name">Length</label>
                                        <input type="number" class="form-control" v-model="men.length" :name="'mens[' + index + '][length]'"  placeholder="cm" value="{{ old('color_code') }}" min="0">
                                        @error('color_code')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </td>
                                </tr>
                                <tr>
                                    <td colspan="7">
                                        <div class="custom-control">
                                            <label class="custom-toggle">
                                                <input type="hidden" :name="'mens[' + index + '][vat]'" value="0" />
                                                <input type="checkbox" v-model="men.vat" :name="'mens[' + index + '][vat]'" value="1" :checked="men.vat ? true : false">
                                                <span class="custom-toggle-slider rounded-circle" data-label-off="OFF" data-label-on="ON"></span>
                                            </label>
                                            <label class="custom-control-label">Vat Applicable?</label>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <a href="javascript:;" v-on:click="addForm" class="mr-0 mt-2 mb-2 btn btn-sm btn-primary">
                            <i class="fa fa-plus"></i> Add
                        </a>
                    </div>
                </div>
                <hr class="my-4" />
                <button href="#" class="btn btn-sm py-2 px-3 btn-primary float-right">
                    <i class="fa fa-save"></i> Submit
                </button>
            </form>
        </div>
    </div>
</div>