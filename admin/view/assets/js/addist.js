jQuery(document).ready(function(){
    jQuery('[data-toggle="tooltip"]').tooltip({html:true});
    
    jQuery('select.radio').each(function(){
        var select = this;
        var html = '<div class="btn-group" data-toggle="buttons">';
        jQuery(this).children('option').each(function(){
            html += '<label class="btn btn-info '+(this.hasAttribute('selected') ? 'active' : '')+'">';
            html += '<input type="radio" name="'+jQuery(select).attr('name')+'" id="'+jQuery(select).attr('id')+'-'+jQuery(this).attr('value')+'" value="'+jQuery(this).attr('value')+'" autocomplete="off" onchange="'+jQuery(select).attr('onchange')+'" '+(this.hasAttribute('selected') ? 'checked' : '')+'> '+jQuery(this).html();
            html += '</label>';
        });
        html += '</div>';
        jQuery(this).replaceWith(html);
    });
});

function initMultiSelect(selector,text_all,text_title)
{
    jQuery(selector).each(function(){
        var all = jQuery(this).attr('multiselect-text-all') ? jQuery(this).attr('multiselect-text-all') : text_all;
        var title = jQuery(this).attr('multiselect-text-button') ? jQuery(this).attr('multiselect-text-button') : text_title;
        jQuery(this).multiselect({
            maxHeight: 200,
            buttonWidth: '100%',
            includeSelectAllOption: !jQuery(this).hasClass('no-select-all'),
            selectAllText: all,
            buttonText: function(options, select) {
                return title;
            },
        });
    });
}