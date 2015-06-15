



























//$(document).on('ready pjax:success', function(){
$('#lotsearch-city_id').multiselect({
    buttonClass: 'btn btn-link',
    includeSelectAllOption: true,
    //buttonContainer: '<div class="region-select-container"></div>',
   /* templates: {
      button: '<a class="multiselect dropdown-toggle" data-toggle="dropdown" href="#"></a>',
      filter: '<li class="multiselect-item filter"><div class="input-group input-group-sm"><input class="form-control multiselect-search" type="text"></div></li>',
      filterClearBtn: '<span class="input-group-btn input-group-sm"><button class="btn btn-default multiselect-clear-filter" type="button"><i class="fa fa-times"></i></button></span>'
    },*/
    allSelectedText: 'Все',
    //nonSelectedText: 'РќРёС‡РµРіРѕ РЅРµ РІС‹Р±СЂР°РЅРѕ',
    selectAllText: 'Выбрать все',
    //enableFiltering: true,
    //filterPlaceholder: 'РџРѕРёСЃРє',
    maxHeight: 300,
    onSelectAll: function() {
            alert('onSelectAll triggered.');
        },
    //enableCaseInsensitiveFiltering: true,
    buttonText: function(options, select) {
      if (options.length === 0) {
        return 'Ничего не выбрано';
      }
      else if (options.length > 1) {
        if (options.length == select.find('option').length) {
          return 'Все'
        } else {
          return options.length + ' ' + Text.plural(options.length, ['город', 'города', 'городов']);
        }
      }
      else {
        var labels = [];
        options.each(function() {
          if ($(this).attr('label') !== undefined) {
            labels.push($(this).attr('label'));
          }
          else {
            labels.push($(this).html());
          }
        });
        return labels.join(', ') + ' ';
      }
    }
  });
  
  
  $('#lotsearch-subjects').multiselect({
    buttonClass: 'btn btn-link',
    includeSelectAllOption: true,
    //buttonContainer: '<div class="region-select-container"></div>',
   /* templates: {
      button: '<a class="multiselect dropdown-toggle" data-toggle="dropdown" href="#"></a>',
      filter: '<li class="multiselect-item filter"><div class="input-group input-group-sm"><input class="form-control multiselect-search" type="text"></div></li>',
      filterClearBtn: '<span class="input-group-btn input-group-sm"><button class="btn btn-default multiselect-clear-filter" type="button"><i class="fa fa-times"></i></button></span>'
    },*/
    allSelectedText: 'Все',
    //nonSelectedText: 'РќРёС‡РµРіРѕ РЅРµ РІС‹Р±СЂР°РЅРѕ',
    selectAllText: 'Выбрать все',
    //enableFiltering: true,
    //filterPlaceholder: 'РџРѕРёСЃРє',
    maxHeight: 300,
    //enableCaseInsensitiveFiltering: true,
    buttonText: function(options, select) {
      if (options.length === 0) {
        return 'Ничего не выбрано';
      }
      else if (options.length > 1) {
        if (options.length == select.find('option').length) {
          return 'Все'
        } else {
          return options.length + ' ' + Text.plural(options.length, ['город', 'города', 'городов']);
        }
      }
      else {
        var labels = [];
        options.each(function() {
          if ($(this).attr('label') !== undefined) {
            labels.push($(this).attr('label'));
          }
          else {
            labels.push($(this).html());
          }
        });
        return labels.join(', ') + ' ';
      }
    }
  });
  
  
  $('#lotsearch-branchs').multiselect({
    buttonClass: 'btn btn-link',
    includeSelectAllOption: true,
    //buttonContainer: '<div class="region-select-container"></div>',
   /* templates: {
      button: '<a class="multiselect dropdown-toggle" data-toggle="dropdown" href="#"></a>',
      filter: '<li class="multiselect-item filter"><div class="input-group input-group-sm"><input class="form-control multiselect-search" type="text"></div></li>',
      filterClearBtn: '<span class="input-group-btn input-group-sm"><button class="btn btn-default multiselect-clear-filter" type="button"><i class="fa fa-times"></i></button></span>'
    },*/
    allSelectedText: 'Все',
    //nonSelectedText: 'РќРёС‡РµРіРѕ РЅРµ РІС‹Р±СЂР°РЅРѕ',
    selectAllText: 'Выбрать все',
    //enableFiltering: true,
    //filterPlaceholder: 'РџРѕРёСЃРє',
    maxHeight: 300,
    //enableCaseInsensitiveFiltering: true,
    buttonText: function(options, select) {
      if (options.length === 0) {
        return 'Ничего не выбрано';
      }
      else if (options.length > 1) {
        if (options.length == select.find('option').length) {
          return 'Все'
        } else {
          return options.length + ' ' + Text.plural(options.length, ['город', 'города', 'городов']);
        }
      }
      else {
        var labels = [];
        options.each(function() {
          if ($(this).attr('label') !== undefined) {
            labels.push($(this).attr('label'));
          }
          else {
            labels.push($(this).html());
          }
        });
        return labels.join(', ') + ' ';
      }
    }
  });

  
 var Text = {
  plural: function(n, forms) {
    var plural = 0;
    if (n % 10 == 1 && n % 100 != 11) {
      plural = 0;
    } else {
      if ((n % 10 >= 2 && n % 10<=4) && (n % 100 < 10 || n % 100 >= 20)) {
        plural = 1;
      } else {
        plural = 2;
      }
    }

    return forms[plural];
  }
};


function cityItems(options, select) {
      if (options.length === 0) {
        return 'Ничего не выбрано';
      }
      else if (options.length > 1) {
        if (options.length == select.find('option').length) {
          return 'Все'
        } else {
          return options.length + ' ' + Text.plural(options.length, ['город', 'города', 'городов']);
        }
      }
      else {
        var labels = [];
        options.each(function() {
          if ($(this).attr('label') !== undefined) {
            labels.push($(this).attr('label'));
          }
          else {
            labels.push($(this).html());
          }
        });
        return labels.join(', ') + ' ';
      }
    }
//});