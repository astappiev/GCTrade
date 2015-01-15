$('#lotform-type_id').on('change', function() {
    console.log('change type');
});

$('#lotform-region_name').on('change', function() {
    var $region_name = $(this);
    var $metadata = $('#lotform-metadata');
    $.ajax({
        url: "https://api.greencubes.org/main/regions/" + $region_name.val(),
        type: "GET",
        dataType: 'json',
        success: function (data) {
            var region_data = {};
            region_data.name = data.name;
            region_data.coordinates = data.coordinates;
            $metadata.val(JSON.stringify(region_data));

            if(data.parent) {
                $region_name.parents('div.form-group').addClass('has-error').find('p.help-block').text('У региона не должно быть родительских регионов');
                $metadata.parents('div.form-group').addClass('has-error');
            } else if(data.full_access.indexOf(username) === -1) {
                $region_name.parents('div.form-group').addClass('has-error').find('p.help-block').text('Вы должны быть владельцем региона');
                $metadata.parents('div.form-group').addClass('has-error');
            } else if(data.name) {
                $region_name.parents('div.form-group').removeClass('has-error').addClass('has-success').find('p.help-block').empty();
                $metadata.parents('div.form-group').removeClass('has-error').addClass('has-success');
            } else {
                $region_name.parents('div.form-group').removeClass('has-error').find('p.help-block').empty();
                $metadata.parents('div.form-group').removeClass('has-error');
            }
        },
        error: function() {

        }
    });
});