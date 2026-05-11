{{-- Page transition loader overlay (main content only, excludes 256px sidebar) --}}
<div id="page-loader"
     style="display:none; position:fixed; top:0; right:0; bottom:0; left:256px; z-index:9999; background:#fff7ed; align-items:center; justify-content:center; flex-direction:column; gap:12px;">
    <div style="
        width:48px; height:48px;
        border:4px solid #fed7aa;
        border-top-color:#ff6b35;
        border-radius:50%;
        animation:idap-spin 0.75s linear infinite;
    "></div>
    <p style="color:#ff6b35; font-size:0.875rem; font-weight:600; font-family:sans-serif; margin:0;">Loading...</p>
</div>

<style>
@keyframes idap-spin {
    to { transform: rotate(360deg); }
}
</style>

<script>
(function () {
    var loader = document.getElementById('page-loader');

    function show() {
        loader.style.display = 'flex';
    }

    function hide() {
        loader.style.display = 'none';
    }

    // Show on regular anchor navigation
    document.addEventListener('click', function (e) {
        var el = e.target.closest('a[href]');
        if (!el) return;

        var href = el.getAttribute('href');

        // Skip non-navigating links
        if (!href || href === '#' || href.startsWith('javascript:') ||
            href.startsWith('mailto:') || href.startsWith('tel:')) return;

        // Skip links that open in a new tab/window
        if (el.target === '_blank') return;

        // Skip download links
        if (el.hasAttribute('download')) return;

        // Skip external links
        if (/^https?:\/\//i.test(href) && !href.startsWith(window.location.origin)) return;

        show();
    });

    // Show on regular (non-AJAX) form submissions, e.g. logout
    document.addEventListener('submit', function (e) {
        // Forms with data-no-loader are excluded (used for AJAX-submitted forms if needed)
        if (e.target.dataset.noLoader !== undefined) return;

        // Only show loader if the form will cause a full page navigation
        // (method GET/POST without fetch intercept — i.e., no data-ajax attribute)
        if (e.target.dataset.ajax !== undefined) return;

        show();
    });

    // Hide the loader on bfcache restore (browser back/forward)
    window.addEventListener('pageshow', function (e) {
        hide();
    });
}());
</script>
