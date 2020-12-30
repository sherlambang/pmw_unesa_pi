$(function () {

    var inputCari = $('#cari-anggota')

    var undangAnggota = $('#undang-anggota')

    var hasilPencarian = $('#hasil-pencarian')

    var formTerimaUndangan = $('#terimaundangan')

    var formTolakUndangan = $('#tolakundangan')

    var formHapusUndangan = $('#form-hapus-undangan')

    var tabelUndanganDiterima = $('#undangan-yang-diterima')

    var tabelUndanganDikirim = $('#undangan-yang-dikirim')

    var formKirimUlang = $('#kirimulang-undangan')

    var undangan = {};

    /**
     * Fungsi untuk melakukan pembuatan button 'undang' saat proses pencarian
     *
     * @param  id
     * @return object
     */
    undangButton = function (id, nama) {
        var btn = $('<button></button>')
        btn.attr('id', id)
        btn.attr('class', 'undang-anggota btn btn-primary btn-sm')
        btn.text('undang')
        btn.click(function (e) {
            e.preventDefault()
            id = $(this).attr('id')
            undangAnggota.find('input[name="untuk"]').val(id)
            undangan.jenis = 'kirim'
            undangan.id = id
            undangan.nama = nama
            undangAnggota.submit()
        })

        return btn
    }

    triggerHapusUndangan = function(obj){
        undangan.id = obj.attr('data-id')
        undangan.jenis = 'hapus'

        formHapusUndangan.find('input[name="id"]').val(undangan.id)
        formHapusUndangan.submit()
    }

    updateUndanganTerikirim = function(){
        if($('.hapus-undangan').length == 0){
            var table = $('<table></table>')
            table.addClass('table')
            table.attr('id','undangan-yang-dikirim')
            var thead = $('<thead></thead>')
            thead.html('<tr><th>Penerima</th><th>Aksi</th></tr>')
            table.append(thead)
            table.append('<tbody></tbody>')
            $('#undangan-wrapper').find('.alert').hide()
            $('#undangan-wrapper').append(table)
            var tabelUndanganDikirim = $('#undangan-yang-dikirim')
        }
        var tr = $('<tr></tr>')
        tr.attr('id','dikirim-' + undangan.id)
        var penerima = $('<td></td>')
        var aksi = $('<td></td>')
        var btnGroup = $('<div></div>')
        btnGroup.addClass('btn-group btn-group-vertical')
        var btn = $('<button></button>')
        btn.text('Hapus')
        btn.addClass('btn btn-danger btn-sm hapus-undangan')
        btn.attr('data-id',undangan.id)
        btn.click(function(){
            triggerHapusUndangan($(this))
        })
        btnGroup.append(btn)
        penerima.html('<b>' + undangan.nama + '</b><br/>' + undangan.id)
        aksi.append(btnGroup)
        tr.append(penerima).append(aksi)
        $('#undangan-yang-dikirim').find('tbody').append(tr)
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
                    console.log(response[value])
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
     * Melakukan ajax ketika user mengklik tombol undang
     *
     * @type void
     */
    undangAnggota.ajaxForm({
        success : function (response) {
            if(response.error === 0){
                updateUndanganTerikirim()
                $('#hasil-' + undangan.id).remove()
                undangan = {}
            }
            swal({
                title : response.error == 0 ? 'Berhasil' : 'Gagal',
                text : response.message,
                type : response.error == 0 ? 'success' : 'error'
            })
        }
    })

    formTerimaUndangan.ajaxForm({
        success : function(response){
            swal({
                title : response.error == 0 ? 'Berhasil' : 'Gagal',
                text : response.message,
                type : response.error == 0 ? 'success' : 'error'
            }, function(){
                window.location.reload()
            })
        }
    })

    formTolakUndangan.ajaxForm({
        success : function(response){
            if(response.error === 0){
                $('#terima-' + undangan.id).remove()
                undangan = {}
            }
            swal({
                title : response.error == 0 ? 'Berhasil' : 'Gagal',
                text : response.message,
                type : response.error == 0 ? 'success' : 'error'
            })
        }
    })

    formKirimUlang.ajaxForm({
        success : function(response){
            if(response.error === 0){
                $('#dikirim-' + undangan.id).remove()
                var tr = $('<tr></tr>')
                tr.attr('id','dikirim-' + undangan.id)
                var penerima = $('<td></td>')
                var aksi = $('<td></td>')
                penerima.html('<b>' + undangan.nama + '</b><br/>' + undangan.id)
                aksi.html('<button class="btn btn-danger btn-sm hapus-undangan" data-id="' + undangan.id + '">Hapus</button>')
                tr.append(penerima).append(aksi)
                tabelUndanganDikirim.find('tbody').append(tr)
                $('#hasil-' + undangan.id).remove()
                undangan = {}
            }
            swal({
                title : response.error == 0 ? 'Berhasil' : 'Gagal',
                text : response.message,
                type : response.error == 0 ? 'success' : 'error'
            })
        }
    })

    formHapusUndangan.ajaxForm({
        success : function(response){
            if(response.error === 0){
                $('#dikirim-' + undangan.id).remove()
                undangan = {}
            }
            swal({
                title : response.error == 0 ? 'Berhasil' : 'Gagal',
                text : response.message,
                type : response.error == 0 ? 'success' : 'error'
            })
        }
    })

    $('.hapus-undangan').click(function(e){
        e.preventDefault()
        triggerHapusUndangan($(this))
    })

    $('.terima-undangan').click(function(e){
        e.preventDefault()
        undangan.id = $(this).attr('data-id')
        undangan.jenis = 'terima'

        formTerimaUndangan.find('input[name="dari"]').val(undangan.id)
        formTerimaUndangan.submit()
    })

    $('.tolak-undangan').click(function(e){
        e.preventDefault()
        undangan.id = $(this).attr('data-id')
        undangan.jenis = 'tolak'

        formTolakUndangan.find('input[name="dari"]').val(undangan.id)
        formTolakUndangan.submit()
    })

    $('.kirimulang-undangan').click(function(e){
        e.preventDefault()
        undangan.id = $(this).attr('data-id')
        undangan.jenis = 'kirimulang'
        undangan.nama = $(this).attr('data-nama')

        formKirimUlang.find('input[name="id"]').val(undangan.id)
        formKirimUlang.submit()
    })

})
