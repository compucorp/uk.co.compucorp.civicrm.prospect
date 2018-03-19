{if $activityTypeFile eq 'ChangeCaseStatus'}
  <table class="prospect-substatus-fields">
    {foreach from=$prospectSubstatusGroupTree.fields item=element key=field_id}
      {assign var="key" value=$element.element_name}
      {if $prospectSubstatusFieldsForm.$key.html}
        {include file="CRM/Custom/Form/CustomField.tpl" form=$prospectSubstatusFieldsForm}
      {/if}
    {/foreach}
  </table>

  <table class="prospect-financial-information-fields">
    {foreach from=$prospectFinancialInformationGroupTree.fields item=element key=field_id}
      {assign var="key" value=$element.element_name}
      {if $prospectFinancialInformationFieldsForm.$key.html}
        {include file="CRM/Custom/Form/CustomField.tpl" form=$prospectFinancialInformationFieldsForm}
      {/if}
    {/foreach}
  </table>

  {literal}
  <script>
  (function($) {
    $(document).ready(function() {
      var changeCaseStatusForm = $('.crm-case-changecasestatus-form-block-case_status_id');
      var prospectFinancialInformationFields = $('table.prospect-financial-information-fields tr');
      var prospectSubstatusFields = $('table.prospect-substatus-fields tr');

      changeCaseStatusForm.after(prospectFinancialInformationFields);
      changeCaseStatusForm.after(prospectSubstatusFields);

    });
  }(CRM.$));
  </script>
  {/literal}
{/if}
