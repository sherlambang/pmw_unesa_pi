<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TabelPMWUNESA extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fakultas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nama', 50)->unique();
        });

        Schema::create('jurusan', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_fakultas')->unsigned()->nullable();
            $table->foreign('id_fakultas')
                ->references('id')
                ->on('fakultas')
                ->onUpdate('CASCADE')
                ->onDelete('SET NULL');
            $table->string('nama', 50)->unique();
        });

        Schema::create('prodi', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_jurusan')->unsigned()->nullable();
            $table->foreign('id_jurusan')
                ->references('id')
                ->on('jurusan')
                ->onUpdate('CASCADE')
                ->onDelete('SET NULL');
            $table->string('nama', 50)->unique();
        });

        Schema::create('hak_akses', function (Blueprint $table){
            $table->increments('id');
            $table->string('nama', 50);
        });

        Schema::create('pengguna', function (Blueprint $table) {
            $table->string('id', 25)->unique()->primary();
            $table->integer('id_prodi')->nullable()->unsigned();
            $table->foreign('id_prodi')
                ->references('id')
                ->on('prodi')
                ->onUpdate('CASCADE')
                ->onDelete('SET NULL');
            $table->string('nama')->nullable();
            $table->string('email', 50);
            $table->string('alamat_asal')->nullable();
            $table->string('alamat_tinggal')->nullable();
            $table->string('no_telepon', 15)->nullable();
            $table->string('password')->nullable();
            $table->boolean('request')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('hak_akses_pengguna', function (Blueprint $table){
            $table->integer('id_hak_akses')->unsigned();
            $table->foreign('id_hak_akses')
                ->references('id')
                ->on('hak_akses')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');
            $table->string('id_pengguna', 25);
            $table->foreign('id_pengguna')
                ->references('id')->on('pengguna')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');
            $table->string('status_request');
        });

        Schema::create('jenis', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nama');
            $table->softDeletes();
        });

        Schema::create('proposal', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->boolean('lolos')->nullable();
            $table->string('judul')->nullable();
            $table->string('direktori')->nullable();
            $table->string('direktori_final')->nullable();
            $table->bigInteger('usulan_dana')->nullable();
            $table->text('abstrak')->nullable();
            $table->text('keyword')->nullable();
            $table->integer('jenis_id')->unsigned()->nullable();
            $table->foreign('jenis_id')->references('id')->on('jenis')->onUpdate('CASCADE')->onDelete('RESTRICT');
            $table->boolean('konfirmasi_tim')->default(false);
            $table->timestamps();
        });

        Schema::create('mahasiswa', function (Blueprint $table){
            $table->string('id_pengguna',25)->unique();
            $table->foreign('id_pengguna')
                ->references('id')
                ->on('pengguna')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');
            $table->bigInteger('id_proposal')->unsigned()->nullable();
            $table->foreign('id_proposal')
                ->references('id')
                ->on('proposal')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');
            $table->float('ipk', 4, 2)->default('0');
        });

        Schema::create('undangan_tim', function (Blueprint $table){
            $table->string('id_ketua', 25);
            $table->foreign('id_ketua')
                ->references('id_pengguna')
                ->on('mahasiswa')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');
            $table->string('id_anggota', 25);
            $table->foreign('id_anggota')
                ->references('id_pengguna')
                ->on('mahasiswa')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');
            $table->boolean('ditolak')->default(false);
        });

        Schema::create('bimbingan', function (Blueprint $table){
            $table->string('id_pengguna',25);
            $table->foreign('id_pengguna')
                ->references('id')
                ->on('pengguna')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');
            $table->bigInteger('id_tim')->unsigned();
            $table->foreign('id_tim')
                ->references('id')
                ->on('proposal')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');
            $table->string('status_request');
        });

        Schema::create('review', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('id_pengguna', 25);
            $table->foreign('id_pengguna')
                ->references('id')->on('pengguna')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');
            $table->bigInteger('id_proposal')->unsigned();
            $table->foreign('id_proposal')
                ->references('id')
                ->on('proposal')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');
            $table->integer('tahap');
            $table->text('komentar')->nullable();
            $table->timestamps();
        });

        Schema::create('aspek', function (Blueprint $table){
            $table->increments('id');
            $table->text('nama');
        });

        Schema::create('penilaian', function (Blueprint $table){
            $table->integer('id_aspek')->unsigned();
            $table->foreign('id_aspek')
                ->references('id')
                ->on('aspek')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');
            $table->bigInteger('id_review')->unsigned();
            $table->foreign('id_review')
                ->references('id')
                ->on('review')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');
            $table->integer('nilai')->unsigned()->nullable();
            $table->timestamps();
        });

        Schema::create('logbook', function (Blueprint $table){
            $table->bigIncrements('id');
            $table->bigInteger('id_proposal')->unsigned();
            $table->foreign('id_proposal')
                ->references('id')
                ->on('proposal')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');
            $table->text('catatan');
            $table->timestamp('tanggal');
            $table->bigInteger('biaya')->nullable();
            $table->timestamps();
        });

        Schema::create('laporan', function (Blueprint $table){
            $table->bigIncrements('id');
            $table->bigInteger('id_proposal')->unsigned();
            $table->foreign('id_proposal')
                ->references('id')
                ->on('proposal')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');
            $table->string('jenis',25);
            $table->text('direktori');
            $table->text('keterangan');
            $table->timestamps();
        });

        Schema::create('pengaturan', function (Blueprint $table){
            $table->increments('id');
            $table->string('nama');
            $table->string('keterangan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
