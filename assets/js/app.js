// SubTrack - Frontend helpers

document.addEventListener('DOMContentLoaded', function () {

    // Live search: filter subscription cards by name or category
    var searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', function () {
            var query = this.value.toLowerCase().trim();
            document.querySelectorAll('.sub-item').forEach(function (card) {
                var text = card.textContent.toLowerCase();
                card.style.display = text.indexOf(query) !== -1 ? '' : 'none';
            });
            updateEmptyState();
        });
    }

    // Category filter buttons
    document.querySelectorAll('[data-filter]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var category = this.getAttribute('data-filter');
            document.querySelectorAll('[data-filter]').forEach(function (b) {
                b.classList.remove('active');
            });
            this.classList.add('active');
            document.querySelectorAll('.sub-item').forEach(function (card) {
                var cardCategory = card.getAttribute('data-category');
                card.style.display = (category === 'All' || cardCategory === category) ? '' : 'none';
            });
            updateEmptyState();
        });
    });

    // Show or hide the empty state message
    function updateEmptyState() {
        var visible = document.querySelectorAll('.sub-item:not([style*="none"])').length;
        var emptyState = document.getElementById('emptyState');
        if (emptyState) {
            emptyState.style.display = visible === 0 ? 'block' : 'none';
        }
    }

    // Monthly cost preview on the add and edit forms
    var costField = document.getElementById('cost');
    var cycleField = document.getElementById('billing_cycle');
    var monthlyPreview = document.getElementById('monthlyPreview');
    function updatePreview() {
        if (!costField || !cycleField || !monthlyPreview) return;
        var cost = parseFloat(costField.value) || 0;
        var monthly = cycleField.value === 'Yearly' ? cost / 12 : cost;
        monthlyPreview.textContent = 'About $' + monthly.toFixed(2) + ' per month';
    }
    if (costField) costField.addEventListener('input', updatePreview);
    if (cycleField) cycleField.addEventListener('change', updatePreview);
    updatePreview();

    // Confirm before deleting a subscription
    document.querySelectorAll('.delete-sub').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            if (!confirm('Delete this subscription? This cannot be undone.')) {
                e.preventDefault();
            }
        });
    });
});
