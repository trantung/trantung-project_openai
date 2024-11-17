<!-- Popup Structure -->
<div class="popup-wrapper mform" id="h5p-popup">
    <div class="popup-content">
        <!-- Header -->
        <div class="popup-header">
            <h3 class="my-0">Insert H5P</h3>
            <span class="popup-close" onclick="closePopupH5P()">&times;</span>
        </div>

        <!-- Body -->
        <div class="popup-body">
            <p>You can insert H5P content by <b>either</b> entering a URL or by uploading an H5P file.</p>

            <div class="input-group input-append w-100">
                <input class="form-control atto_h5p_file" type="url" value="" data-region="h5pfile" size="32">
                <span class="input-group-append">
                    <button class="btn btn-secondary openh5pbrowser" type="button">Browse repositories...</button>
                </span>
            </div>
            <fieldset class="collapsible collapsed">
                <legend class="ftoggler" id="yui_3_17_2_1_1729933468482_851">
                    <a href="#" class="fheader" role="button" aria-expanded="false">H5P options</a>
                </legend>
                <div class="fcontainer">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input atto_h5p_option_download_button" aria-label="Allow download">
                        <label class="form-check-label">Allow download</label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input atto_h5p_option_embed_button" aria-label="Embed button">
                        <label class="form-check-label">Embed button</label>
                    </div>
                    <div class="form-check mb-2">
                        <input type="checkbox" class="form-check-input atto_h5p_option_copyright_button" aria-label="Copyright button">
                        <label class="form-check-label">Copyright button</label>
                    </div>
                </div>
            </fieldset>
        </div>

        <!-- Footer -->
        <div class="popup-footer">
            <div class="text-center"><button class="btn btn-secondary atto_h5p_urlentrysubmit" type="submit">Insert H5P</button></div>
        </div>
    </div>
</div>

<script>
    function closePopupH5P() {
        document.getElementById('h5p-popup').style.display = 'none';
    }

    $(document).ready(function() {
        $('#h5p-popup fieldset.collapsible legend a').click(function(e) {
            e.preventDefault(); // Ngăn chặn hành động mặc định
            $(this).closest('fieldset.collapsible').toggleClass('collapsed');
            // if($(this).closest('fieldset.collapsible').hasClass('collapsed')){
            //     $('#h5p-popup .popup-body').css('height', 'auto');
            // }else{
            //     $('#h5p-popup .popup-body').css('height', '300px');
            // }
        });
    })
</script>
