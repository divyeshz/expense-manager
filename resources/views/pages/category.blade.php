{{-- Extends MainLayout --}}
@extends('layouts.mainLayout')

{{-- Change Title --}}
@section('title', 'Exp. Mgr. | Category')

{{-- Content Start --}}
@section('content')

    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Category</h1>
            {{-- Add Category Button --}}
            <a class="btn btn-primary btn-icon-split d-none d-sm-inline-block shadow-sm" id="openCategoryModal">
                <span class="icon text-white-50">
                    <i class="fas fa-plus"></i>
                </span>
                <span class="text">Add Category</span>
            </a>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">List Of Category</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    {{-- Category list Table --}}
                    <table class="table table-bordered" id="categoryListTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->

@endsection
{{-- Content end --}}

{{-- Js Content Start --}}
@section('jsContent')

    <script>
        $(document).ready(function() {

            // make yajra Table
            $('#categoryListTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('category.list') }}",
                columns: [{
                        data: '#',
                        name: '#'
                    },
                    {
                        data: 'name',
                        name: 'name',
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false
                    },
                ]
            });

            // Reset Category Modal Form
            $(document).on("click", "#openCategoryModal", function(event) {
                $("#addCategoryModalForm #name").val('');
                $('#addCategoryModal').modal('show');
            });
        });

        // Add Category Ajax call First Validate After That Submit In Response Show Toast Message  And ReDraw Datatable
        function addCategory() {
            $("#addCategoryModalForm").validate({
                rules: {
                    name: "required",
                },
                messages: {
                    name: "Please Specify your Category name",
                },
                submitHandler: function(form) {
                    $.ajax({
                        type: 'post',
                        data: $('#addCategoryModalForm').serialize(),
                        url: "{{ route('category.save') }}",
                        success: function(response) {

                            var categoryListTable = $('#categoryListTable').dataTable();
                            categoryListTable.fnDraw(false);

                            if (response.status == "success") {
                                toastr.success('' + response.message + '');
                            } else {
                                toastr.error('' + response.message + '');
                            }
                            $('#addCategoryModal').modal('hide');
                        }

                    });
                }
            });
        }

        // View Category Ajax call First Validate After That Put Data Into Edit Modal
        function viewCategory(id = "") {
            $.ajax({
                type: 'get',
                data: {
                    id: id,
                },
                url: "{{ route('category.view') }}",
                success: function(response) {
                    $('#editCategoryModalForm #name').val(response.name);
                    $('#editCategoryModalForm #edit_category_id').val(response.id);
                }
            })
        }

        // Edit Category Ajax call First Validate After That Submit In Response Show Toast Message  And ReDraw Datatable
        function editCategory() {
            $("#editCategoryModalForm").validate({
                rules: {
                    name: "required",
                },
                messages: {
                    name: "Please Specify your Category name",
                },
                submitHandler: function(form) {
                    $.ajax({
                        type: 'post',
                        data: $('#editCategoryModalForm').serialize(),
                        url: "{{ route('category.edit') }}",
                        success: function(response) {
                            if (response.status == "success") {
                                toastr.success('' + response.message + '');
                            } else {
                                toastr.error('' + response.message + '');
                            }
                            $('#editCategoryModal').modal('hide');

                            var categoryListTable = $('#categoryListTable').dataTable();
                            categoryListTable.fnDraw(false);
                        }

                    });
                }
            });
        }

        // Delete Category Ajax call First Validate After That Submit In Response Show Toast Message And ReDraw Datatable
        function deleteCategory(id = "") {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'post',
                        dataType: 'json',
                        data: {
                            id: id,
                            _token: "{{ csrf_token() }}"
                        },
                        url: "{{ route('category.delete') }}",
                        success: function(response) {
                            if (response.status == "success") {
                                toastr.success('' + response.message + '');
                            } else {
                                toastr.error('' + response.message + '');
                            }

                            var categoryListTable = $('#categoryListTable').dataTable();
                            categoryListTable.fnDraw(false);
                        }
                    })
                }
            })
        }
    </script>

@endsection
{{-- Js Content End --}}
