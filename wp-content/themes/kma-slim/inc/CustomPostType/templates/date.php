<table width="100%" cellpadding="5px">
    <tr>
        <td width="15%" align="right" valign="top">
            <label for="{field-name}">{field-label}</label>
        </td>
        <td>
            <input type="text" name="custom_meta[{field-name}]" id="{field-name}" value="{field-value}" style="width: 100%" placeholder="Select a Date..." class="form-control flatpickr" />
        </td>
    </tr>
</table>

<script>
    jQuery(document).ready(function($) {
        $(".flatpickr").flatpickr();
    });
</script>