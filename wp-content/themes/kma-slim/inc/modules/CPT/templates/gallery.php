<table width="100%" cellpadding="5px">
    <tr>
        <td width="15%" align="right" valign="top">
            <label for="{field-name}">{field-label}</label>
        </td>
        <td>
            <input type="hidden" name="custom_meta[{field-name}]" id="{field-name}" value="{field-value}" style="width: 70%;" />
            <input type="button" id="button-{field-name}" class="button" value="Choose or upload images" />
            <div id="preview-{field-name}" style="padding:5px; margin: 10px 0; border:1px solid #999; min-height:130px;">
{images}
                <div style="clear:both; "></div>
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
                title: 'Select or Upload Media',
                multiple: true
            });

            // Runs when an image is selected.
            meta_image_frame.on('select', function(){

                var length = meta_image_frame.state().get("selection").length;
                var images = meta_image_frame.state().get("selection").models;
                var input = '';

                if(length > 0){
                    $('#preview-{field-name}').html('<div style="clear:both; "></div>');
                }

                for(var i = 0; i < length; i++)
                {
                    console.info(images[i]);

                    var img = document.createElement("img");
                    img.src = images[i].changed.sizes.thumbnail.url;
                    img.alt = images[i].changed.title;
                    img.style = "width: 150px; max-width: 100%; float: left; margin:.5%"
                    $('#preview-{field-name}').prepend(img);

                    input = input!='' ? input + '|' + images[i].id : images[i].id;

                }

                $('#{field-name}').val(input);

            });

            // Opens the media library frame.
            meta_image_frame.open();
        });
    });
</script>