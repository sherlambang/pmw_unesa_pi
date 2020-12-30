$(function(){

    $('.ajax-form').ajaxForm({
        success : function(response){
            swal({
                title : (response.error === false) ? 'Berhasil' : 'Gagal',
                text : response.message,
                type : (response.error === false ) ? 'success' : 'error'
            },function(){
                window.location.reload()
            })
        }
    })

    $('.terima-undangan').click(function(e){
        e.preventDefault()

        var proposal = $(this).attr('data-proposal')
        var url = $(this).attr('href')

        $.ajax({
            type : 'POST',
            url : url,
            data : 'proposal=' + proposal,
            success : function(response){
                swal({
                    title : (response.error === 0) ? 'Berhasil' : 'Gagal',
                    text : response.message,
                    type : (response.error === 0 ) ? 'success' : 'error'
                },function(){
                    window.location.reload()
                })
            }
        })
    })

    $('.tolak-undangan').click(function(e){
        e.preventDefault()

        var proposal = $(this).attr('data-proposal')
        var url = $(this).attr('href')

        $.ajax({
            type : 'POST',
            url : url,
            data : 'proposal=' + proposal,
            success : function(response){
                swal({
                    title : (response.error === 0) ? 'Berhasil' : 'Gagal',
                    text : response.message,
                    type : (response.error === 0 ) ? 'success' : 'error'
                },function(){
                    window.location.reload()
                })
            }
        })
    })

})
