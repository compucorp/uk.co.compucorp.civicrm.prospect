{if not $notConfigured and not $redirectToCaseAdmin and $action eq 4}
  {assign var="campaignId" value=$prospectFinancialInformationFields->getValueOf('Campaign_Id')}
  <div id="prospect-financial-information-custom-fields-table">
    <table class="report crm-entity" data-entity="case" data-id="{$caseID}">
      <tr>
        <td data-label="{$prospectFinancialInformationFields->getLabelOf('Campaign_Id')}">
          {$prospectFinancialInformationFields->getLabelOf('Campaign_Id')}:
          <span class="crmf-{$prospectFinancialInformationFields->getMachineNameOf('Campaign_Id')} crm-editable no-select2" data-type="select">{$campaignLabel}</span>
        </td>
        <td data-label="{$prospectFinancialInformationFields->getLabelOf('Budgeted')}">
          {$prospectFinancialInformationFields->getLabelOf('Budgeted')}:
          {$currency} <span class="crmf-{$prospectFinancialInformationFields->getMachineNameOf('Budgeted')} crm-editable">{$prospectFinancialInformationFields->getValueOf('Budgeted')}</span>
        </td>
        <td data-label="{$prospectFinancialInformationFields->getLabelOf('Prospect_Amount')}">
          {$prospectFinancialInformationFields->getLabelOf('Prospect_Amount')}:
          {$currency} <span class="crmf-{$prospectFinancialInformationFields->getMachineNameOf('Prospect_Amount')} crm-editable prospect-amount">{$prospectFinancialInformationFields->getValueOf('Prospect_Amount')}</span>
        </td>
        <td data-label="{$prospectFinancialInformationFields->getLabelOf('Restricted_Code')}">
          {$prospectFinancialInformationFields->getLabelOf('Restricted_Code')}:
          <span class="crmf-{$prospectFinancialInformationFields->getMachineNameOf('Restricted_Code')} crm-editable no-select2" data-type="select">{$prospectFinancialInformationFields->getOptionLabelOf('Restricted_Code')}</span>
        </td>
      </tr>
      <tr>
        <td data-label="{$prospectFinancialInformationFields->getLabelOf('Probability')}">
          {$prospectFinancialInformationFields->getLabelOf('Probability')}:
          <span class="crmf-{$prospectFinancialInformationFields->getMachineNameOf('Probability')} crm-editable prospect-probability">{$prospectFinancialInformationFields->getValueOf('Probability')}</span> %
        </td>
        <td data-label="{$prospectFinancialInformationFields->getLabelOf('Expectation')}">
          {$prospectFinancialInformationFields->getLabelOf('Expectation')}:
          {$currency} <span class="crmf-{$prospectFinancialInformationFields->getMachineNameOf('Expectation')} prospect-expectation">{$prospectFinancialInformationFields->getValueOf('Expectation')}</span>
        </td>
        <td data-label="{$prospectFinancialInformationFields->getLabelOf('Expected_Date')}">
          {$prospectFinancialInformationFields->getLabelOf('Expected_Date')}:
          <span class="expected-date-wrapper" data-field-machine-name="{$prospectFinancialInformationFields->getMachineNameOf('Expected_Date')}">
            <span class="expected-date-value-wrapper crm-editable-enabled">
              <span class="value prospect-expected-date">{$prospectFinancialInformationFields->getValueOf('Expected_Date')|crmDate:"%m/%d/%Y"}</span>
              <i class="crm-i fa-pencil crm-editable-placeholder"></i>
            </span>
            <input type="text" class="prospect-expected-date-input" value="{$prospectFinancialInformationFields->getValueOf('Expected_Date')}">
          </span>
        </td>
        <td data-label="">
          {if not $isCaseConverted}
            {ts}Convert Prospect{/ts}
            <select class="prospect-convert" data-contact-id="{$contactID}">
              <option value="">-- select --</option>
              <option value="pledge">{ts}Pledge{/ts}</option>
              <option value="contribution">{ts}Contribution{/ts}</option>
            </select>
            <a href="" class="open-prospect-convert-window button"></a>
          {else}
            <div class="converted-case-payment-info">
              {if $paymentInfo.payment_completed}
                <div class="payment-completed">

                  {ts}Payment {$paymentInfo.payment_status}:{/ts} <span>{$paymentInfo.payment_completed}</span>
                </div>
              {/if}
              {if $paymentInfo.pledge_balance}
                <div class="pledge-balance">
                  {ts}Pledge balance{/ts}: <span>{$paymentInfo.pledge_balance}</span>
                </div>
              {/if}
              <div class="payment-link">
                <a class="action-item" href="{$paymentInfo.payment_url}&cid={$contactId}">{ts}View {if $paymentInfo.payment_entity == 'pledge'}Pledge{elseif $paymentInfo.payment_entity == 'contribute'}Contribution{/if}{/ts}</a>
              </div>
            </div>
          {/if}
        </td>
      </tr>
    </table>
  </div>
{literal}
  <script>
  CRM.$(function () {
    var caseProspectFinancialInformation = new CRM.Prospect.Page.CaseView();
  });
</script>
{/literal}
{/if}
