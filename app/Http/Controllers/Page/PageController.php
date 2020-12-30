<?php

namespace PMW\Http\Controllers\Page;

use Illuminate\Http\Request;
use PMW\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use PMW\Models\Fakultas;
use PMW\Models\Jurusan;
use PMW\Models\Prodi;
use PMW\Models\Proposal;
use PMW\Models\LogBook;

/**
 * Class ini berfungsi sebagai pengatur halaman yang bisa diakses oleh
 * lebih dari satu user, sebagai contoh, halaman pengaturan adalah
 * halama yang bisa diakses oleh seluurh user
 * 
 * @author BagasMuharom <bagashidayat@mhs.unesa.ac.id|bagashidayat45@gmail.com>
 * @package PMW\Http\Controllers\Page
 */
class PageController extends Controller
{

    /**
     * Menampilkan halaman detail proposal
     * tidak bisa dilihat oleh mahasiswa
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function detailProposal(Request $request)
    {
        return view('detailproposal', [
            'proposal' => Proposal::find($request->id)
        ]);
    }

    /**
     * Menampilkan halaman detail dari sebuah proposal
     *
     * @param int|null $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function proposalDetail($id = null)
    {
        $view = view('detailproposal');
        $proposal = Auth::user()->isMahasiswa() ? Auth::user()->mahasiswa()->proposal() : Proposal::find($id);
        $view->with('proposal', $proposal);

        if (!Auth::user()->isMahasiswa()) {
            $view->with('anggota', Proposal::find($id)->mahasiswa());
            $view->with('ketua', Proposal::find($id)->ketua());
        } else {
            if (!Auth::user()->mahasiswa()->punyaProposal())
                return view('mahasiswa.proposal');
        }

        return $view;
    }

    /**
     * Melihat hasil review dari sebuah proposal
     *
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function lihatHasilReview($id)
    {
        $proposal = Proposal::find($id);

        $review = [];

        if(!is_null($proposal->daftarReview(1))) {
            $review['tahap1'] = $proposal->daftarReview(1)->whereNotNull('komentar');
        }

        if(!is_null($proposal->daftarReview(2))) {
            $review['tahap2'] = $proposal->daftarReview(2)->whereNotNull('komentar');
        }

        if(count($review) == 0)
            $review = null;

        return view('hasilreview', [
            'review' => $review,
            'proposal' => $proposal
        ]);
    }

    /**
     * Halaman pengaturan user
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function pengaturan()
    {
        $fakultas = Fakultas::all();
        if (!is_null(Auth::user()->prodi()))
            $fakultas = Fakultas::where('id', '!=', Auth::user()->prodi()->jurusan()->fakultas()->id)->get();

        $jurusan = [];
        if (!is_null(Auth::user()->prodi())) {
            $jurusan = Jurusan::where('id', '!=', Auth::user()->prodi()->jurusan()->id)
                ->where('id_fakultas', Auth::user()->prodi()->jurusan()->fakultas()->id)
                ->get();
        }

        $prodi = [];
        if (!is_null(Auth::user()->prodi())) {
            $prodi = Prodi::where('id', '!=', Auth::user()->prodi()->id)
                ->where('id_jurusan', Auth::user()->prodi()->jurusan()->id)
                ->get();
        }

        return view('pengaturan', [
            'daftar_fakultas' => $fakultas,
            'daftar_jurusan' => $jurusan,
            'daftar_prodi' => $prodi
        ]);
    }

    /**
     * Menampilkan halaman logbook
     *
     * @return \Illuminate\View\View
     */
    public function lihatLogbook($proposal)
    {
        if(is_null(Proposal::find($proposal)))
            return abort(404);
            
        if(!Auth::user()->isDosenPembimbing() && !Auth::user()->isSuperAdmin())
            if(Proposal::find($proposal)->pembimbing()->id != Auth::user()->id)
                return abort(404);

        $logbook = Proposal::find($proposal)->logbook()->paginate(3);

        return view('logbook', [
            'proposal' => Proposal::find($proposal),
            'daftarlogbook' => $logbook
        ]);
    }


    public function detailLogbook($id_logbook)
    {
        $logbook = LogBook::find($id_logbook);
        $proposal = Proposal::find($logbook->id_proposal);
        $location = $this->get_image_location('upload/logbook/'.$logbook->direktori_foto);
        if(is_null($logbook))
            return abort(404);
            
        if(!Auth::user()->isDosenPembimbing() || !Auth::user()->isReviewer())
            return abort(404);


        return view('detaillogbook', [
            'logbook' => $logbook,
            'proposal' => $proposal,
            'location' => $location
        ]);
    }

    private function gps2Num($coordPart){
        $parts = explode('/', $coordPart);
        if(count($parts) <= 0)
        return 0;
        if(count($parts) == 1)
        return $parts[0];
        return floatval($parts[0]) / floatval($parts[1]);
    }

    private function get_image_location($image = ''){
        $exif = exif_read_data($image, 0, true);
        if($exif && isset($exif['GPS'])){
            $GPSLatitudeRef = $exif['GPS']['GPSLatitudeRef'];
            $GPSLatitude    = $exif['GPS']['GPSLatitude'];
            $GPSLongitudeRef= $exif['GPS']['GPSLongitudeRef'];
            $GPSLongitude   = $exif['GPS']['GPSLongitude'];
            
            $lat_degrees = count($GPSLatitude) > 0 ? $this->gps2Num($GPSLatitude[0]) : 0;
            $lat_minutes = count($GPSLatitude) > 1 ? $this->gps2Num($GPSLatitude[1]) : 0;
            $lat_seconds = count($GPSLatitude) > 2 ? $this->gps2Num($GPSLatitude[2]) : 0;
            
            $lon_degrees = count($GPSLongitude) > 0 ? $this->gps2Num($GPSLongitude[0]) : 0;
            $lon_minutes = count($GPSLongitude) > 1 ? $this->gps2Num($GPSLongitude[1]) : 0;
            $lon_seconds = count($GPSLongitude) > 2 ? $this->gps2Num($GPSLongitude[2]) : 0;
            
            $lat_direction = ($GPSLatitudeRef == 'W' or $GPSLatitudeRef == 'S') ? -1 : 1;
            $lon_direction = ($GPSLongitudeRef == 'W' or $GPSLongitudeRef == 'S') ? -1 : 1;
            
            $latitude = $lat_direction * ($lat_degrees + ($lat_minutes / 60) + ($lat_seconds / (60*60)));
            $longitude = $lon_direction * ($lon_degrees + ($lon_minutes / 60) + ($lon_seconds / (60*60)));

            return array('latitude'=>$latitude, 'longitude'=>$longitude);
        }else{
            return false;
        }
    }


}
