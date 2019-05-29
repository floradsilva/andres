(function($) {

    function getDataInit(callback){
        var time_data_url = data_url + '?t='+ Date.now();
        if($('body').hasClass('processing'))
        {
            callback();
        }else {
            $.ajax({
                url : time_data_url,
                type: 'post',
                dataType: 'json',
                data: {action: 'get_data',warehouse: data_warehouse_id},
                beforeSend:function(){
                    $('body').addClass('processing');
                },
                success: function(response){
                    $('#kitchen-table-body').empty();
                    if(response.length > 0)
                    {
                        for(var i =0; i< response.length; i++)
                        {
                            var template = ejs.compile(data_template, {});
                            var html = template(response[i]);

                            $('#kitchen-table-body').append(html);
                        }
                    }
                    $('body').removeClass('processing');
                    callback();
                },
                error: function(){
                    $('body').removeClass('processing');
                    callback();
                }
            });
        }

    }
    function getData(){
        getDataInit(function(){

            setTimeout(function() {
                getData();
            }, 3000);

        });
    }

    $(document).ready(function(){

        getData();

        $(document).on('click','.is_cook_ready',function(){
            var current = $(this);
            var time_data_url = data_url + '?t='+ Date.now();
            $.ajax({
                url : time_data_url,
                type: 'post',
                dataType: 'json',
                data: {action: 'update_ready',id: $(this).data('id')},
                beforeSend:function(){
                    $('body').addClass('processing');
                },
                success: function(response){
                    //$('#kitchen-table-body').empty();
                    current.hide();
                    $('body').removeClass('processing');
                },
                error: function(){
                    $('body').removeClass('processing');
                }
            });
        });

    });



}(jQuery));