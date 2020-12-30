<?php

namespace PMW;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use PMW\Models\Fakultas;
use PMW\Models\HakAkses;
use PMW\Support\RequestStatus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable
{

    use Notifiable;

    protected $table = 'pengguna';

    public $incrementing = false;

    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'id_prodi',
        'nama',
        'email',
        'alamat_asal',
        'alamat_tinggal',
        'no_telepon',
        'hak_akses',
        'password',
        'request'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Mendapatkan instance Prodi
     *
     * @param bool $relasi
     * @return \Illuminate\Database\Eloquent\Model|null|static
     */
    public function prodi()
    {
        return $this->belongsTo(
            'PMW\Models\Prodi',
            'id_prodi')
            ->first();
    }

    /**
     * Mendapatkan relasi prodi belongTo (Instance BelongTo)
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function relasiProdi()
    {
        return $this->belongsTo(
            'PMW\Models\Prodi',
            'id_prodi');
    }

    /**
     * Mendapatkan hak akses dari user tertentu
     *
     * @param null $hakAkses
     * @return $this|mixed
     */
    public function hakAksesPengguna($hakAkses = null)
    {
        if (!is_null($hakAkses)) {
            return $this->belongsToMany(
                'PMW\Models\HakAkses',
                'hak_akses_pengguna',
                'id_pengguna',
                'id_hak_akses')
                ->withPivot('status_request')
                ->where('id_hak_akses', $hakAkses->id)->first();
        }
        return $this->belongsToMany(
            'PMW\Models\HakAkses',
            'hak_akses_pengguna',
            'id_pengguna',
            'id_hak_akses')
            ->withPivot('status_request');
    }

    /**
     * Mengecek apakah permintaan hak akses user ditolak
     *
     * @param HakAkses $hakAkses
     * @return bool
     */
    public function hakAksesDitolak($hakAkses)
    {
        if (is_string($hakAkses))
            $hakAkses = HakAkses::where('nama', $hakAkses)->first();

        return ($this->hakAksesPengguna()
                ->where('id_hak_akses', $hakAkses->id)
                ->where('status_request', RequestStatus::REJECTED)
                ->count() > 0);
    }

    /**
     * Mendapatka daftar review dari user terkait
     *
     * @return BelongsToMany
     */
    public function review()
    {
        return $this->belongsToMany(
            'PMW\Models\Proposal',
            'review',
            'id_pengguna',
            'id_proposal')
            ->withPivot('tahap', 'komentar', 'id');
    }

    /**
     * Merelasikan dengan tabel mahasiswa dan mengambil data pertama
     *
     * @return mixed
     */
    public function mahasiswa()
    {
        return $this->hasOne('PMW\Models\Mahasiswa', 'id_pengguna')->first();
    }

    /**
     * Merelasikan dengan tabel mahasiswa
     * @return HasOne
     */
    public function relasiMahasiswa()
    {
        return $this->hasOne('PMW\Models\Mahasiswa', 'id_pengguna');
    }

    /**
     * Merelasikan dengan tabel bimbingan jika user adalah dosen pembimbing
     *
     * @return BelongsToMany
     */
    public function bimbingan($status = null)
    {
        $bimbingan = $this->belongsToMany(
            'PMW\Models\Proposal',
            'bimbingan',
            'id_pengguna',
            'id_tim')
            ->withPivot('status_request');

        if (!is_null($status))
            $bimbingan = $bimbingan->wherePivot('status_request', $status);

        return $bimbingan;
    }

    /**
     * Mengecek apakah user (dosen pembimbing) memiliki
     * undangan sebagai pembimbing sebuah tim
     *
     * @return boolean
     */
    public function punyaUndanganBimbingan()
    {
        return ($this->bimbingan(RequestStatus::REQUESTING)
                ->count() > 0);
    }

    /**
     * Mengecek apakah user memiliki tim untuk dibimbing
     *
     * @return boolean
     */
    public function punyaBimbingan()
    {
        return ($this->bimbingan(RequestStatus::APPROVED)
                ->count() > 0);
    }

    /**
     * Mengecek apakah user terkait memiliki salah satu diantara beberapa role yang diinginkan
     *
     * @param array $roles
     * @return bool
     */
    public function hasAnyRole(array $roles)
    {
        foreach ($roles as $role) {
            if ($this->hasRole($role))
                return true;
        }

        return false;
    }

    /**
     * Mengecek apakah user memiliki role tertentu
     *
     * @param string $role
     * @return boolean
     */
    public function hasRole($role)
    {
        return $this->hakAksesPengguna()
                ->where('nama', strtolower($role))
                ->where('status_request', RequestStatus::APPROVED)->count() > 0;
    }

    /**
     * Mengecek apakah user tidak memiliki hak akses apapun
     *
     * @return bool
     */
    public function hasNoRole()
    {
        return $this->hakAksesPengguna()
                ->where('status_request', RequestStatus::APPROVED)
                ->count() === 0;
    }

    /**
     * Mengecek apakah user adalah seorang ketua tim
     *
     * @return boolean
     */
    public function isKetua()
    {
        return ($this->hasRole(HakAkses::KETUA_TIM));
    }

    /**
     * Mengecek apakah user adalah seorang anggota tim atau belum menjadi
     * anggota dari sebuah tim
     *
     * @return boolean
     */
    public function isAnggota()
    {
        return ($this->hasRole(HakAkses::ANGGOTA));
    }

    /**
     * Mengecek apakah user adalah seorang mahasiswa
     *
     * @return boolean
     */
    public function isMahasiswa()
    {
        return ($this->isKetua() || $this->isAnggota());
    }

    /**
     * Mengecek apakah user adalah admin fakultas
     *
     * @return boolean
     */
    public function isAdminFakultas()
    {
        return ($this->hasRole(HakAkses::ADMIN_FAKULTAS));
    }

    /**
     * Mengecek apakah user adalah admin universitas
     *
     * @return boolean
     */
    public function isAdminUniversitas()
    {
        return ($this->hasRole(HakAkses::ADMIN_UNIVERSITAS));
    }

    /**
     * Mengecek apakah user adalah super admin
     *
     * @return boolean
     */
    public function isSuperAdmin()
    {
        return ($this->hasRole(HakAkses::SUPER_ADMIN));
    }

    /**
     * Mengecek apakah user adalah dosen pembimbing
     *
     * @return boolean
     */
    public function isDosenPembimbing()
    {
        return $this->hasRole(HakAkses::DOSEN_PEMBIMBING);
    }

    /**
     * Mengecek apakah user adalah reviewer
     *
     * @return boolean
     */
    public function isReviewer()
    {
        return $this->hasRole(HakAkses::REVIEWER);
    }

    /**
     * Mengecek apakah user sedang meminta hak akses tertentu
     *
     * @param string $hakAkses
     * @return boolean
     */
    public function requestingHakAkses($hakAkses)
    {
        return ($this->hakAksesPengguna()
                ->where('nama', $hakAkses)
                ->wherePivot('status_request', RequestStatus::REQUESTING)
                ->count() == 1);
    }

    /**
     * Mengecek apakah user bisa melakukan permintaan hak akses
     * tertentu
     *
     * @param [type] $role
     * @return bool
     */
    public function bisaRequestHakAkses($role)
    {
        return (!$this->hasRole($role) && !$this->requestingHakAkses($role));
    }

    /**
     * Melakukan pencarian mahasiswa yang bisa diundang bergabung
     * dalam tim
     *
     * @param string $nama
     * @return \Illuminate\Http\JsonResponse
     */
    public static function cariMahasiswaUntukUndanganTim($nama)
    {
        // Daftar undangan yang dikirim oleh user terkait
        $daftarUndangan = Auth::user()->mahasiswa()->undanganTimKetua()->pluck('id_anggota');

        $eloquent = static::whereHas('hakAksesPengguna', function ($query) {
            // melakukan sortir bahwa user yang dicari memiliki hak akses anggota
            $query->where('nama', HakAkses::ANGGOTA);
        })
            ->whereHas('relasiMahasiswa', function ($query) {
                // memastikan user belum memiliki tim atau proposal
                $query->whereNull('id_proposal');
            })
            ->where('nama', 'LIKE', '%' . strtolower($nama) . '%')
            ->orWhere('id', 'LIKE', '%'. $nama .'%')
            ->where('id', '!=', Auth::user()->id)
            ->whereNotNull('id_prodi')
            ->whereNotIn('id', $daftarUndangan);

        // Menambahkan field nama prodi
        $daftarMahasiswa = [];
        foreach ($eloquent->cursor() as $pengguna) {
            $tempArr = $pengguna->toArray();
            $tempArr['prodi'] = $pengguna->prodi()->nama;
            array_push($daftarMahasiswa, $tempArr);
        }

        return response()->json(collect($daftarMahasiswa)->toArray());
    }

    /**
     * Melakukan pencarian dosen pembimbing
     *
     * @param string $nama
     * @return \Illuminate\Http\JsonResponse
     */
    public static function cariDosenPembimbing($nama)
    {
        $nama = strtolower($nama);

        $eloquent = static::whereHas('hakAksesPengguna', function ($query) {
            $query->where('nama', HakAkses::DOSEN_PEMBIMBING)
                ->where('status_request', RequestStatus::APPROVED);
        })
            // ->whereHas('relasiProdi', function ($query) {
                // memastikan bahwa user dan calon dosen pembimbing berasal dari jurusan
                // yang sama
            //     $query->where('id_jurusan', Auth::user()->prodi()->jurusan()->id);
            // })
            ->where('nama', 'LIKE', '%' . strtolower($nama) . '%');

        // menambah field nama prodi
        $daftarDosen = [];
        foreach ($eloquent->cursor() as $pengguna) {
            $tempArr = $pengguna->toArray();
            $tempArr['prodi'] = $pengguna->prodi()->nama;
            array_push($daftarDosen, $tempArr);
        }

        return response()->json(collect($daftarDosen)->toArray());
    }
    
    /**
     * Menjadikan user terkait sebagai ketua tim
     *
     * @return void
     */
    public function jadikanKetua()
    {
        // Menghapus hak akses sebagai anggota dari pengirim undangan
        $this->hakAksesPengguna()->detach(
            HakAkses::where('nama', HakAkses::ANGGOTA)->first()
        );

        // menjadikan pengirim undangan sebagai ketua
        $this->hakAksesPengguna()->attach(
            HakAkses::where('nama', HakAkses::KETUA_TIM)->first(), [
                'status_request' => 'Approved'
            ]
        );
    }

    public static function perFakultas($nama_fakultas)
    {
        $idfakultas = Fakultas::where('nama', $nama_fakultas)->first()->id;
        return DB::table('pengguna')
            ->leftJoin(DB::raw('
            (
              SELECT prodi.id AS id_prodi, jurusan.id_fakultas
              FROM prodi LEFT JOIN jurusan ON prodi.id_jurusan = jurusan.id
            ) AS fk'
            ), 'fk.id_prodi', '=', 'pengguna.id_prodi')
            ->whereRaw('fk.id_fakultas = ' . $idfakultas)
            ->orderBy('nama')
            ->get();
    }

}
