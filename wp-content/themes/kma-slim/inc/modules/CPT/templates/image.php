<table width="100%" cellpadding="5px">
    <tr>
        <td width="15%" align="right" valign="top">
            <label for="{field-name}">{field-label}</label>
        </td>
        <td>
            <input type="text" name="custom_meta[{field-name}]" id="{field-name}" value="{field-value}" style="width: 70%;" />
            <input type="button" id="button-{field-name}" class="button" value="Choose or upload an image" />
            <div id="preview-box" style="padding:5px 0;">
                <img id="preview-{field-name}" src="{field-value}" style="max-width: 100%;">
            </div>
        </td>
    </tr>
</table>

<script>
    jQuery(document).ready(function($){

        // Instantiates the variable that holds the media library frame.
        var meta_image_frame;
        var meta_image;

        // Runs when the image button is clicked.
        $('#button-{field-name}').click(function(e){

            // Prevents the default action from occuring.
            e.preventDefault();

            // If the frame already exists, re-open it.
            if ( meta_image_frame ) {
                meta_image_frame.open();
                return;
            }

            // Sets up the media library frame
            meta_image_frame = wp.media.frames.meta_image_frame = wp.media({
                //title: meta_image.title,
                //button: { text:  meta_image.button },
                //library: { type: 'image' }
            });

            // Runs when an image is selected.
            meta_image_frame.on('select', function(){

                // Grabs the attachment selection and creates a JSON representation of the model.
                var media_attachment = meta_image_frame.state().get('selection').first().toJSON();

                // Sends the attachment URL to our custom image input field.
                if(media_attachment != '') {
                    $('#{field-name}').val(media_attachment.url);
                    $('#preview-{field-name}').attr("src", media_attachment.url);
                }
            });

            // Opens the media library frame.
            meta_image_frame.open();
        });
    });
</script>