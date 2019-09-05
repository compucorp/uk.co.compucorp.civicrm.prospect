{if $caseID}
<div class="prospect__case-info">
  <div class="case-id">
    {ts}Case ID{/ts}:
    <span>{$caseID}</span></div>
  <div class="restriction-code">
    {$prospectFinancialInformationFields->getLabelOf('Restricted_Code')}:
    <span>{$prospectFinancialInformationFields->getOptionLabelOf('Restricted_Code')}</span>
  </div>
</div>
{/if}
