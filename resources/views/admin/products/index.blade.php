@extends('admin.layouts.app')
@section('title','Products')
@section('content')
    <h1 class="page-title">Products</h1>
    <div class="mb-3"><button id="btn-add-product" class="btn">Tambah Product</button></div>
    <table id="product-table" class="display" style="width:100%"><thead><tr><th>ID</th><th>Name</th><th>Image</th><th>Mitra</th><th>Category</th><th>Price</th><th>Aksi</th></tr></thead></table>

    <div class="modal" id="productModal"><div class="modal-dialog"><div class="modal-content"><form class="ajax-form" id="product-form" data-refresh-table="#product-table" enctype="multipart/form-data"><div class="modal-header"><h5 class="modal-title">Product</h5><button type="button" class="close" data-dismiss="modal">&times;</button></div><div class="modal-body"><input type="hidden" name="id" id="product-id"><div class="form-group"><label>Mitra</label><select name="mitra_id" id="product-mitra_id" class="form-control">@foreach(\App\Models\Mitra::all() as $m)<option value="{{ $m->id }}">{{ $m->name }}</option>@endforeach</select></div>
                        <div class="form-group"><label>Category</label><select name="category_id" id="product-category_id" class="form-control"><option value="">--None--</option>@foreach(\App\Models\Category::where('status','active')->get() as $c)<option value="{{ $c->id }}">{{ $c->name }}</option>@endforeach</select></div>
                        <div class="form-group"><label>Name</label><input name="name" id="product-name" class="form-control"></div>
                        <div class="form-group"><label>Image</label><input type="file" name="image" id="product-image" class="form-control"><div id="product-image-preview" style="margin-top:8px"></div></div>
                        <div class="form-group"><label>Price</label><input name="price" id="product-price" class="form-control" type="number"></div><div class="form-group"><label>Stock</label><input name="stock" id="product-stock" class="form-control" type="number"></div></div><div class="modal-footer"><button type="submit" class="btn">Simpan</button><button type="button" class="btn btn-outline" data-dismiss="modal">Batal</button></div></form></div></div></div>

<script>
document.addEventListener('DOMContentLoaded', function(){
    const table = initDataTable('#product-table','{{ route('admin.products.data') }}',[
        { data:0 },{ data:1 },{ data:2 },{ data:3 },{ data:4 },{ data:5 },{ data:6, orderable:false, searchable:false }
    ]);

    $('#btn-add-product').on('click', function(){
        $('#product-form').attr('data-url','{{ url('admin/products') }}').attr('data-method','POST');
        $('#product-form')[0].reset();
        $('#product-image-preview').html('');
        $('#productModal').modal('show');
    });

    $('#product-table').on('click','.btn-delete', function(){if(!confirm('Hapus product?')) return; let id=$(this).data('id'); $.ajax({url:'{{ url('admin/products') }}/'+id, method:'DELETE', headers:{'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')}, success:function(){table.ajax.reload()}});
    });

    $('#product-table').on('click','.btn-edit', function(){let id=$(this).data('id'); $.get('{{ url('admin/products') }}/'+id, function(res){$('#product-form').attr('data-url','{{ url('admin/products') }}/'+id).attr('data-method','PUT'); $('#product-mitra_id').val(res.mitra_id); $('#product-category_id').val(res.category_id); $('#product-name').val(res.name); $('#product-price').val(res.price); $('#product-stock').val(res.stock); if (res.image_url) { $('#product-image-preview').html('<img src="'+res.image_url+'" style="height:80px;border-radius:4px">'); } else { $('#product-image-preview').html(''); } $('#productModal').modal('show');});});
});
</script>
@endsection