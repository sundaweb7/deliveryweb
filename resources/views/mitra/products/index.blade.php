@extends('mitra.layouts.app')
@section('title','My Products')
@section('content')
    <h1 class="page-title">My Products</h1>
    <div class="mb-3"><button id="btn-add-product" class="btn">Tambah Product</button></div>
    <table id="product-table" class="display" style="width:100%"><thead><tr><th>ID</th><th>Name</th><th>Category</th><th>Price</th><th>Stock</th><th>Aksi</th></tr></thead></table>

    <div class="modal" id="productModal"><div class="modal-dialog"><div class="modal-content"><form class="ajax-form" id="product-form" data-refresh-table="#product-table"><div class="modal-header"><h5 class="modal-title">Product</h5><button type="button" class="close" data-dismiss="modal">&times;</button></div><div class="modal-body"><input type="hidden" name="id" id="product-id"><div class="form-group"><label>Category</label><select name="category_id" id="product-category_id" class="form-control"><option value="">--None--</option>@foreach(\App\Models\Category::where('status','active')->get() as $c)<option value="{{ $c->id }}">{{ $c->name }}</option>@endforeach</select></div>
                        <div class="form-group"><label>Name</label><input name="name" id="product-name" class="form-control"></div><div class="form-group"><label>Price</label><input name="price" id="product-price" class="form-control" type="number"></div><div class="form-group"><label>Stock</label><input name="stock" id="product-stock" class="form-control" type="number"></div></div><div class="modal-footer"><button type="submit" class="btn">Simpan</button><button type="button" class="btn btn-outline" data-dismiss="modal">Batal</button></div></form></div></div></div>

<script>
document.addEventListener('DOMContentLoaded', function(){
    const table = initDataTable('#product-table','{{ route('mitra.products.data') }}',[
        { data:0 },{ data:1 },{ data:2 },{ data:3 },{ data:4 },{ data:5, orderable:false, searchable:false }
    ]);

    $('#btn-add-product').on('click', function(){
        $('#product-form').attr('data-url','{{ url('mitra/products') }}').attr('data-method','POST');
        $('#product-form')[0].reset();
        $('#productModal').modal('show');
    });

    $('#product-table').on('click','.btn-delete', function(){if(!confirm('Hapus product?')) return; let id=$(this).data('id'); $.ajax({url:'{{ url('mitra/products') }}/'+id, method:'DELETE', headers:{'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')}, success:function(){table.ajax.reload()}});
    });

    $('#product-table').on('click','.btn-edit', function(){
        let id=$(this).data('id');
        $.get('{{ url('mitra/products') }}/'+id, function(res){
            $('#product-form').attr('data-url','{{ url('mitra/products') }}/'+id).attr('data-method','PUT');
            $('#product-id').val(res.id);
            $('#product-category_id').val(res.category_id);
            $('#product-name').val(res.name);
            $('#product-price').val(res.price);
            $('#product-stock').val(res.stock);
            $('#productModal').modal('show');
        });
    });
});
</script>
@endsection