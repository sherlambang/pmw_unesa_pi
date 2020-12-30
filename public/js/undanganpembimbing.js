$(function(){

    var inputCari = $('#cari-pembimbing')

    var undangPembimbing = $('#undang-pembimbing')

    var hasilPencarian = $('#hasil-pencarian')

    var undangan = {}

    undangButton = function (id, nama) {
        var btn = $('<button></button>')
        btn.attr('id', id)
        btn.attr('class', 'undang-pembimbing btn btn-primary btn-sm')
        btn.text('undang')
        btn.click(function (e) {
            e.preventDefault()
            id = $(this).attr('id')
            undangPembimbing.find('input[name="dosen"]').val(id)
            undangan.jenis = 'kirim'
            undangan.id = id
            undangan.nama = nama
            undangPembimbing.submit()
        })

        return btn
    }

    /**
     * Melakukan ajax ketika form disubmit
     */
    inputCari.ajaxForm({
        success: function (response) {
            $('#belum-cari').hide()
            hasilPencarian.find('tr').remove()
            if(response.length == 0){
                hasilPencarian.parent().hide()
                $('#not-found').show()
            }
            else{
                $('#not-found').hide()
                hasilPencarian.parent().show()
                for (value in response) {
                    var baris = $('<tr id="hasil-' + response[value].id + '"></tr>')
                    var nama = $('<td></td>').html(response[value].nama + '<br/><b>' + response[value].id + '</b>')
                    var asal = $('<td></td>').text(response[value].prodi)
                    var aksi = undangButton(response[value].id, response[value].nama)
                    baris.append(nama)
                    baris.append(asal)
                    baris.append(aksi)
                    hasilPencarian.append(baris)
                    // var fakultas = $('<td></td>').text(response[value].id)
                }
            }
        }
    });

    /**
     * Memberikan trigger submit ketika textboxt pencarian mulai diketik
     *
     * @return void
     */
    inputCari.find('input[type="text"]').on('keyup', function () {
        if ($(this).val().length > 1)
            $(this).parent().submit()
    })

    /**
     * Melakukan Ajax untuk mengirim undangan kepada
     * calon dosen pembimbing
     */
    undangPembimbing.ajaxForm({
        success : function (response) {
            swal({
                title : response.error === 0 ? 'Berhasil' : 'Gagal',
                text : response.message,
                type : response.error === 0 ? 'success' : 'error'
            }, function(){
                if(response.error === 0)
                    window.location.reload()
            })
        }
    })

    $('.ajax-form').submit(function (e) {
        e.preventDefault()

        var url = $(this).attr('action')
        var dosen = $(this).find('input[name="dosen"]').val()
        var hapus = false

        swal({
            title: "Apa anda yakin ?",
            type: "warning",
            showCancelButton: true,
            cancelButtonText: 'Batal',
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Ya, hapus!",
            closeOnConfirm: false,
            showLoaderOnConfirm: true
        }, function () {
            $.ajax({
                type : 'post',
                url : url,
                data : 'dosen=' + dosen,
                success : function(response){
                    swal({
                        title : (response.error === 0) ? 'Berhasil' : 'Gagal',
                        text : response.message,
                        type : (response.error === 0 ) ? 'success' : 'error'
                    },function(){
                        if(response.error === 0)
                            window.location.reload()
                    })
                }
            })
        })

    })

    // $('.ajax-form').ajaxForm({
    //     beforeSend : function(){
    //         var hapus = false;
    //         swal({
    //             title: "Apa anda yakin ?",
    //             type: "warning",
    //             showCancelButton: true,
    //             cancelButtonText: 'Batal',
    //             confirmButtonColor: "#DD6B55",
    //             confirmButtonText: "Ya, hapus!",
    //             closeOnConfirm: false,
    //             showLoaderOnConfirm: true
    //         }, function () {
    //             hapus = true
    //         })
    //         console.log(hapus)
    //         return hapus
    //     },
    //     success : function(response){
    //         swal({
    //             title : (response.error === 0) ? 'Berhasil' : 'Gagal',
    //             text : response.message,
    //             type : (response.error === 0 ) ? 'success' : 'error'
    //         },function(){
    //             if(response.error === 0)
    //                 window.location.reload()
    //         })
    //     }
    // })

})
