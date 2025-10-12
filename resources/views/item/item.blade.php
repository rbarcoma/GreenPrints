@extends('adminlte::page')

@section('title', 'Menu')

@section('content_header')
    <h1>Item</h1>
@stop

@section('content')
    <p>Welcome to this beautiful admin panel.</p>

    <section class="border p-3 card">

         <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0">Menu List</h4>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#itemCreate">
                Create Item
            </button>
        </div>

        <div class="modal fade" id="itemCreate" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog  modal-lg modal-dialog-centered">
                <div class="modal-content">

                    <form action="" methods >
                        @csrf
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Menu creation</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">

                            <div class="mb-3">
                                <label  class="form-label">Item Name</label>
                                <input type="text" class="form-control" name="item_name" placeholder="Please input item name..">
                            </div>
                          <div class="form-floating">
                             <textarea class="form-control" placeholder="Leave a description here" id="floatingTextarea2" style="height: 100px"></textarea>
                             <label for="floatingTextarea2">Description</label>
                          </div>

                           <div class="mb-3">
                                <label  class="form-label">File</label>
                                <input type="file" class="form-control" name="item_name" placeholder="Please input item name..">
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                </form>

                </div>
            </div>
        </div>


    </section>

@stop



@section('css')
 <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/dataTables.bootstrap5.min.css') }}">
@stop

@section('js')


    <script src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/dataTables.bootstrap5.min.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js" integrity="sha384-G/EV+4j2dNv+tEPo3++6LCgdCROaejBqfUeNjuKAiuXbjrxilcCdDz6ZAVfHWe1Y" crossorigin="anonymous"></script>

    {{-- Initialize DataTable --}}
    <script>
        $(document).ready(function() {
            $('#example').DataTable();
        });
    </script>

@stop
