
@extends('layouts.adminlayout')
@section('content')
    <div class="header bg-primary pb-6">
        <div class="container-fluid">
            <div class="header-body">
                <div class="row align-items-center py-4">
                    <div class="col-lg-6 col-7">
                        <h6 class="h2 text-white d-inline-block mb-0">Menu</h6>
                    </div>
                    {{-- <div class="col-lg-6 col-5 text-right">
                        <a href="<?php echo route('admin.footerMenu'); ?>" class="btn btn-neutral"><i class="ni ni-bold-left"></i> Back</a>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
    <!-- Page content -->
    <div class="container-fluid mt--6" id="menu">
        <div class="row">
            <div class="col-xl-12 order-xl-1">
                <div class="card">
                    <!--!! FLAST MESSAGES !!-->
                    @include('admin.partials.flash_messages')
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">Header Menu</h3>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form @submit.prevent="submitForm">
                            <h6 class="heading-small text-muted mb-4">Header Menu Information</h6>
                            <div class="pl-lg-4">
                                <draggable v-model="menuItems" :options="{group:'header'}">
                                    <div class="row" v-for="(item, index) in menuItems" :key="index">
                                        <div class="col-lg-1 text-right">
                                            <i class="drag-point fa fa-grip-vertical" style="background: linen;padding: 4px;cursor: grab;font-size: 16px;margin-top:10px;"></i>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label class="form-control-label" for="input-title">Title</label>
                                                <input type="text" class="form-control" v-model="item.title" placeholder="Title" >
                                                <small class="text-danger" v-if="errors[`menuItems.${index}`] && errors[`menuItems.${index}`].title">
                                                    @{{ errors[`menuItems.${index}`].title[0] }}
                                                </small>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label class="form-control-label" for="input-link">Link</label>
                                                <input type="text" class="form-control" v-model="item.link" placeholder="Link" >
                                                <small class="text-danger" v-if="errors[`menuItems.${index}`] && errors[`menuItems.${index}`].link">
                                                    @{{ errors[`menuItems.${index}`].link[0] }}
                                                </small>
                                            </div>
                                        </div>

                                        <div class="col-lg-3 d-flex align-items-center">
                                            <button type="button" class="btn btn-danger" @click="addMegaMenuItem(index)" style="margin-top: 0;">
                                                <i class="fa fa-plus"></i>
                                            </button>
                                            <button type="button" class="btn btn-danger" @click="removeItem(index)" style="margin-top: 0;">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        </div>

                                        <div class="col-md-12 pl-lg-5" v-if="item.megaMenu && item.megaMenu.length > 0">
                                            <h6 class="heading-small text-muted mb-2">Mega Menu</h6>
                                            <draggable v-model="item.megaMenu" :options="{group:'header-inner'+index}">
                                                <div class="row" v-for="(v, k) in item.megaMenu">
                                                    <div class="col-lg-1 text-right">
                                                        <i class="drag-point fa fa-grip-vertical" style="background: linen;padding: 4px;cursor: grab;font-size: 16px;margin-top:10px;"></i>
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <div class="form-group">
                                                            <label class="form-control-label" for="input-title">Title</label>
                                                            <input type="text" class="form-control" v-model="v.title" placeholder="Title">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-5">
                                                        <div class="form-group">
                                                            <label class="form-control-label" for="input-link">Link</label>
                                                            <input type="text" class="form-control" v-model="v.link" placeholder="Link">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-2 d-flex align-items-center">
                                                        <button type="button" class="btn btn-danger" @click="removeMegaMenuItem(index, k)" style="margin-top: 0;">
                                                            <i class="fa fa-times"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            <draggable/>
                                            <hr>
                                        </div>
                                    </div>
                                <draggable/>
                            </div>
                            <button type="button" class="btn btn-sm py-2 px-3 btn-primary" @click="addItem">
                                <i class="fa fa-plus"></i> Add Menu
                            </button>
                            <hr class="my-4" />
                            @if (Permissions::hasPermission('menu', 'create'))
                                <button type="submit" :disabled="loading" class="btn btn-sm py-2 px-3 btn-success float-right">
                                    <i :class="loading ? 'fa fa-spinner fa-spin' : 'fa fa-save'"></i> Submit
                                </button>
                            @endif
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
