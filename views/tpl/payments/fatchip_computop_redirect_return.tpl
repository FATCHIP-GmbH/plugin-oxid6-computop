<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Direktes Einfügen der URL
        var finishUrl = [{$oView->getFinishUrl()}];
            window.location.href = finishUrl;
    });
</script>


