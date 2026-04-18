// Admin Panel JavaScript
function confirmDelete(message) {
    return confirm(message || 'Are you sure you want to delete this item?');
}

// Auto-hide alerts after 4 seconds
document.addEventListener('DOMContentLoaded', function() {
    var alerts = document.querySelectorAll('.admin-alert');
    for (var i = 0; i < alerts.length; i++) {
        (function(alert) {
            setTimeout(function() {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';
                setTimeout(function() { alert.remove(); }, 500);
            }, 4000);
        })(alerts[i]);
    }
});

// Image preview on file input change
function previewImage(input, previewId) {
    var preview = document.getElementById(previewId);
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = '<img src="' + e.target.result + '">';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// Add variant row
var variantIndex = 100;
function addVariantRow() {
    variantIndex++;
    var container = document.getElementById('variant-container');
    var html = '<div class="variant-row" id="variant-row-' + variantIndex + '">'
        + '<div class="d-flex justify-content-between align-items-center mb-2">'
        + '<strong class="small text-muted">New Variant</strong>'
        + '<button type="button" class="btn btn-admin-danger btn-admin-sm" onclick="removeVariantRow(' + variantIndex + ')">'
        + '<i class="fa-solid fa-trash"></i> Remove</button></div>'
        + '<div class="row g-2 mb-2">'
        + '<div class="col-sm-4"><label class="form-label">SKU</label>'
        + '<input type="text" name="variant_sku[]" class="form-control form-control-sm" placeholder="e.g. W60-ANSI"></div>'
        + '<div class="col-sm-4"><label class="form-label">Variant Name *</label>'
        + '<input type="text" name="variant_name[]" class="form-control form-control-sm" required placeholder="e.g. ANSI Layout"></div>'
        + '<div class="col-sm-2"><label class="form-label">Attribute 1</label>'
        + '<input type="text" name="variant_attr1[]" class="form-control form-control-sm" placeholder="e.g. Color"></div>'
        + '<div class="col-sm-2"><label class="form-label">Attribute 2</label>'
        + '<input type="text" name="variant_attr2[]" class="form-control form-control-sm" placeholder="e.g. Black"></div>'
        + '</div>'
        + '<div class="row g-2">'
        + '<div class="col-sm-3"><label class="form-label">Sell Price (₫) *</label>'
        + '<input type="number" name="variant_price[]" class="form-control form-control-sm" required></div>'
        + '<div class="col-sm-3"><label class="form-label">Original Price (₫)</label>'
        + '<input type="number" name="variant_price_orig[]" class="form-control form-control-sm"></div>'
        + '<div class="col-sm-2"><label class="form-label">Stock</label>'
        + '<input type="number" name="variant_stock[]" class="form-control form-control-sm" value="0"></div>'
        + '<div class="col-sm-4"><label class="form-label">Image</label>'
        + '<input type="file" name="variant_image_new[]" class="form-control form-control-sm" accept="image/*"></div>'
        + '</div>'
        + '<input type="hidden" name="variant_id[]" value="new"></div>';
    container.insertAdjacentHTML('beforeend', html);
}

function removeVariantRow(id) {
    var row = document.getElementById('variant-row-' + id);
    if (row) row.remove();
}

// Generate slug from name
function generateSlug(input, target) {
    var slug = input.value
        .toLowerCase()
        .replace(/[^a-z0-9\s-]/g, '')
        .replace(/\s+/g, '-')
        .replace(/-+/g, '-')
        .trim();
    document.getElementById(target).value = slug;
}
