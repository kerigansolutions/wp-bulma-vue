<table width="100%" cellpadding="5px">
    <tr>
        <td width="15%" align="right" valign="top">
            <label for="{field-name}">{field-label}</label>
        </td>
        <td>
            <div class="flatpickr-{field-name}">
                <input type="text" name="custom_meta[{field-name}]" id="{field-name}" value="{field-value}" class="form-control" data-input>
                <a class="button input-button" data-toggle>Select a Date</a>
            </div>
        </td>
    </tr>
</table>

<script>
    jQuery(document).ready(function($) {
        $(".flatpickr-{field-name}").flatpickr({
            wrap: true,
            dateFormat : 'Ymd',
            clickOpens : false,
            altInput   : true,
            defaultDate : '{field-value}'
        });
    });
</script>