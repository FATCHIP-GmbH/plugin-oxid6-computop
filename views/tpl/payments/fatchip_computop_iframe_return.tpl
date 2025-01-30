<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Direktes Einfügen der URL
        var finishUrl = [{$oView->getFinishUrl()}];
        if (window.top !== window.self) {
            window.top.location.href = finishUrl;
        }
    });
</script>

