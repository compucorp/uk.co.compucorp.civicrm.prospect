<table class="prospect-substatus-fields">
    {foreach from=$prospectSubstatusGroupTree.fields item=element key=field_id}
        {assign var="key" value=$element.element_name}
        {if $prospectSubstatusFieldsForm.$key.html}
            {include file="CRM/Custom/Form/CustomField.tpl" form=$prospectSubstatusFieldsForm}
        {/if}
    {/foreach}
</table>

{literal}
    <script>
      (function($) {
        $(document).ready(function() {
          var prospectSubstatusFields = $('table.prospect-substatus-fields tr');
          $('.crm-case-opencase-form-block-status_id').after(prospectSubstatusFields);
        });
      }(CRM.$));
    </script>
{/literal}