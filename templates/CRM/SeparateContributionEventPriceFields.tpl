<div id="separate-contribtuion">
    <table class="form-layout">
         <tr class='crm-event-manage-fee-form-block-currency'>
            <td class="label">{$form.separate_contribution_event_price_fields.label}</td>
      <td>{$form.separate_contribution_event_price_fields.html}<br />
        <span class="description">{ts}{/ts}</span>
      </td>
         </tr>
    </table>
</div>
{literal}
<script type="text/javascript">
CRM.$(function($) {
  $('#separate-contribtuion').insertAfter('#priceSet');
});
</script>
{/literal}