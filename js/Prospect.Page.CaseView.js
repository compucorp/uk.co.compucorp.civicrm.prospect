CRM.Prospect = CRM.Prospect || {};
CRM.Prospect.Page = CRM.Prospect.Page || {};

CRM.Prospect.Page.CaseView = (function($) {
  function CaseView() {
    this._alignProspectFinancialInformationCustomFieldsTable();
    this._setupExpectedDateInput();
    this._bindProspectConvertAction();
    this._bindUnlinkContributionAction();
    this._hideDuplicateFinancialInformationFields();
    this._bindExpectationUpdate();
    this._bindExpectedDateUpdate();
  };

  CaseView.prototype._alignProspectFinancialInformationCustomFieldsTable = function() {
    var caseControlPanel = $('.case-control-panel');
    var prospectFinancialInformationCustomFieldsTable = $('#prospect-financial-information-custom-fields-table');

    prospectFinancialInformationCustomFieldsTable.appendTo(caseControlPanel);

    $('.crm-editable').crmEditable();
  };

  CaseView.prototype._setupExpectedDateInput = function() {
    var that = this;
    var expectedDateWrapper = $('.expected-date-wrapper');

    this._setExpectedDateInputPlaceholderVisibility(expectedDateWrapper);

    $('input', expectedDateWrapper).crmDatepicker({
      time: false
    }).on('change', function() {
      that._updateExpectedDateValue($(this).val(), expectedDateWrapper);

      that._setExpectedDateInputPlaceholderVisibility(expectedDateWrapper);
    });

    $('.crm-form-date-wrapper', expectedDateWrapper).hide();

    $('.expected-date-value-wrapper', expectedDateWrapper).click(function() {
      $('.expected-date-value-wrapper', expectedDateWrapper).removeClass('crm-editable-enabled').hide();
      $('.crm-form-date-wrapper', expectedDateWrapper).show();
      $('.crm-form-date-wrapper input.hasDatepicker', expectedDateWrapper).focus();
    });
  };

  CaseView.prototype._setExpectedDateInputPlaceholderVisibility = function(wrapper) {
    var placeholder = $('.crm-editable-placeholder', wrapper);

    if ($('span.value', wrapper).text().length) {
      placeholder.hide();
    } else {
      placeholder.show();
    }
  };

  CaseView.prototype._updateExpectedDateValue = function(value, wrapper) {
    var caseId = $('#prospect-financial-information-custom-fields-table table:first').data('id');
    var fieldMachineName = $('.expected-date-wrapper').data('field-machine-name');
    var params = {
      'id': caseId
    };

    params[fieldMachineName] = value;

    $.ajax({
      url: CRM.url('civicrm/ajax/rest'),
      method: 'POST',
      data: {
        entity: 'Case',
        action: 'create',
        json: JSON.stringify(params)
      },
      async: false,
      success: function(data) {
        $('span.value', wrapper).text(value);
        $('.expected-date-value-wrapper', wrapper).addClass('crm-editable-enabled').show();
        $('.crm-form-date-wrapper', wrapper).hide();
      }
    });
  };

  CaseView.prototype._bindProspectConvertAction = function() {
    var that = this;

    $('a.open-prospect-convert-window').hide();

    $('select.prospect-convert').change(function() {
      that._convertProspect($(this).data('contact-id'), $(this).val());
    });
  };

  CaseView.prototype._bindUnlinkContributionAction = function() {
    $('.contribution-unlink').click(function(e) {
      CRM.confirm({message: 'Are you sure?' })
        .on('crmConfirm:yes', function() {
          var prospectID = $('.contribution-unlink').data('prospect-id');
          var contributionId = $('.contribution-unlink').data('contribution-id');

          CRM.api3([
            ['ProspectConverted', 'delete', { id: prospectID }],
            ['Contribution', 'create', { id: contributionId, contribution_status_id: 'Cancelled' }]
          ]).then(function () {
            location.reload();
          });
        });
    });
  };

  CaseView.prototype._convertProspect = function(contactId, type) {
    if (type !== 'contribution' && type !== 'pledge') {
      return;
    }

    var caseId = $('#prospect-financial-information-custom-fields-table table:first').data('id');
    var url = '/civicrm/contact/view/' + type + '?reset=1&amp;action=add&amp;cid=' + contactId + '&amp;context=' + type + '&amp;caseid=' + caseId;

    $('a.open-prospect-convert-window').attr('href', url).click();
  };

  /**
   * Hides (Prospect Financial Information) custom fields from the custom fields tab view
   * if they appear in the table view above them
   *
   */
  CaseView.prototype._hideDuplicateFinancialInformationFields = function() {
    $('#prospect-financial-information-custom-fields-table td').each(function (index, customFieldRow) {
      var fieldLabel = $(customFieldRow).data('label');
      if (fieldLabel) {
        $("#Prospect_Financial_Information .label:contains('" + fieldLabel + "')").closest('table').hide();
      }
    });
  };

  /**
   * Updates Expectation field on changing Amount or Probability values.
   */
  CaseView.prototype._bindExpectationUpdate = function() {
    var $prospectFI = $('#prospect-financial-information-custom-fields-table');

    $('.prospect-amount, .prospect-probability', $prospectFI).on('change', function(e) {
      var amountSpan = parseFloat(+$('.prospect-amount', $prospectFI).text());
      var probabilitySpan = parseFloat(+$('.prospect-probability', $prospectFI).text());
      var amountInput = parseFloat(+$('.prospect-amount form input[name="value"]', $prospectFI).val() || 0);
      var probabilityInput = parseFloat(+$('.prospect-probability form input[name="value"]', $prospectFI).val() || 0);

      var amount = amountSpan + amountInput;
      var probability = probabilitySpan + probabilityInput;

      $('.prospect-expectation', $prospectFI).text(parseFloat((amount * (probability / 100) * 100) / 100).toFixed(2));
    });
  };

  /**
   * Updates Expected Date field format on changing Expected Date value.
   */
  CaseView.prototype._bindExpectedDateUpdate = function() {
    var $prospectFI = $('#prospect-financial-information-custom-fields-table');

    $('.prospect-expected-date-input', $prospectFI).on('change', function(e) {
      $('.prospect-expected-date', $prospectFI).text($(this).val());
    });
  };

  return CaseView;
})(CRM.$);
