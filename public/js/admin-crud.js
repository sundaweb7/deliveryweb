// Ensure Waves stub exists to prevent theme JS errors when Waves lib is missing
if (typeof window.Waves === 'undefined') {
    window.Waves = { init: function(){}, attach: function(){} };
}

// initDataTable helper available immediately so page scripts can call it in their DOMContentLoaded handlers
window.initDataTable = function(selector, ajaxUrl, columns, options = {}) {
    if (!window.jQuery || !$.fn.DataTable) {
        console.warn('DataTables not loaded yet for', selector);
        return null;
    }
    const defaults = {
        processing: true,
        serverSide: true,
        ajax: { url: ajaxUrl, type: 'GET' },
        columns: columns,
        responsive: true,
        autoWidth: false
    };
    const cfg = Object.assign({}, defaults, options);
    return $(selector).DataTable(cfg);
}

document.addEventListener('DOMContentLoaded', function () {
    // Setup AJAX CSRF for jQuery
    if (window.jQuery) {
        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') } });
    }

    // Sidebar toggle for mobile / responsive
    $(document).on('click', '.sidebar-toggle', function(e){
        e.preventDefault();
        $('.admin-sidebar').toggleClass('collapsed');
    });

    // Collapse sidebar by default on small screens
    if (window.innerWidth <= 800) {
        $('.admin-sidebar').addClass('collapsed');
    }

    // Generic create/edit modal submit handler (form must have data-url and data-method)
    $(document).on('submit', '.ajax-form', function(e){
        e.preventDefault();
        let form = $(this);
        let url = form.data('url') || form.attr('action');
        let method = (form.data('method') || form.attr('method') || 'POST').toUpperCase();
        let fd = new FormData(this);

        // For browsers and PHP, sending PUT/PATCH/DELETE with multipart FormData may not populate $_POST correctly.
        // Use method spoofing: send as POST and append _method when original method is not POST/GET.
        if (method !== 'POST' && method !== 'GET') {
            fd.append('_method', method);
            method = 'POST';
        }

        $.ajax({
            url: url,
            method: method,
            data: fd,
            processData: false,
            contentType: false,
            success: function(res){
                if (res.message) alert(res.message);
                // hide modal (Bootstrap or fallback)
                try { $('.modal').modal('hide'); } catch(e) { $('.modal').hide().removeClass('open'); $('body').removeClass('modal-open'); }
                if (form.data('refresh-table')) {
                    $(form.data('refresh-table')).DataTable().ajax.reload(null,false);
                } else {
                    location.reload();
                }
            },
            error: function(xhr){
                let json = xhr.responseJSON;
                if (json && json.errors) {
                    let errors = Object.values(json.errors).flat();
                    alert(errors.join('\n'));
                } else if (json && json.message) {
                    alert(json.message);
                } else {
                    alert('Terjadi error.');
                }
            }
        });
    });

    // jQuery modal polyfill (if Bootstrap modal plugin not loaded)
    if (window.jQuery && typeof $.fn.modal !== 'function') {
        $.fn.modal = function(action){
            if (action === 'show') {
                this.show();
                this.addClass('open');
                $('body').addClass('modal-open');
            } else if (action === 'hide') {
                this.hide();
                this.removeClass('open');
                $('body').removeClass('modal-open');
            }
            return this;
        }
    }

    // Delegate click for elements using data-dismiss="modal" so "Batal" and close buttons work even without Bootstrap's JS
    $(document).on('click', '[data-dismiss="modal"]', function(e){
        e.preventDefault();
        var $btn = $(this);
        var $modal = $btn.closest('.modal');
        if ($modal.length) {
            try { $modal.modal('hide'); } catch (err) { $modal.hide().removeClass('open'); $('body').removeClass('modal-open'); }
        } else {
            try { $('.modal').modal('hide'); } catch (err) { $('.modal').hide().removeClass('open'); $('body').removeClass('modal-open'); }
        }
    });

    // Close modal on ESC key
    $(document).on('keyup', function(e){
        if (e.key === 'Escape' || e.keyCode === 27) {
            try { $('.modal').modal('hide'); } catch (err) { $('.modal').hide().removeClass('open'); $('body').removeClass('modal-open'); }
        }
    });
});
