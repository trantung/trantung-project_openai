<!-- Popup Structure -->
<div class="popup-wrapper mform" id="link-popup">
    <div class="popup-content">
        <!-- Header -->
        <div class="popup-header">
            <h3 class="my-0">Create link</h3>
            <span class="popup-close" onclick="closePopupLink()">&times;</span>
        </div>

        <!-- Body -->
        <div class="popup-body">
            <label for="_atto_link_urlentry">Enter a URL</label>
            <div class="input-group input-append w-100 mb-1">
                <input type="hidden" name="textSelectedLink" id="textSelectedLink">
                <input class="form-control url atto_link_urlentry" type="url" id="_atto_link_urlentry">
                <span class="input-group-append">
                    <button class="btn btn-secondary openlinkbrowser" type="button">Browse repositories...</button>
                </span>
            </div>
            <div class="form-check">
                <input type="checkbox" class="form-check-input newwindow" id="_atto_link_openinnewwindow">
                <label class="form-check-label" for="_atto_link_openinnewwindow">Open in new window</label>
            </div>
        </div>

        <!-- Footer -->
        <div class="popup-footer">
            <div class="text-center">
                <button type="button" class="create-link btn btn-secondary submit">Create link</button>
            </div>
        </div>
    </div>
</div>

<script>
    function closePopupLink() {
        document.getElementById('link-popup').style.display = 'none';
    }

</script>