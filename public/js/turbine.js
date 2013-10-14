/*
 * Turbine application specific Javascript/jQuery!
 */

function delete_rule(rule_id) {
    $.ajax({
        url: '/rules/' + rule_id,
        type: 'DELETE',
        success: function(result) {
            $('tr.rule_' + rule_id).fadeOut();
        }
    });
}


