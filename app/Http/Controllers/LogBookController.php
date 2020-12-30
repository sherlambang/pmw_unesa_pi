<?php

namespace PMW\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use PMW\Models\LogBook;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use PMW\Http\Controllers\Page;
use PMW\Http\Controllers\Controller;

use PMW\Library\PEL\PelJpeg;
use PMW\Library\PEL\PelTiff;
use PMW\Library\PEL\PelExif;
use PMW\Library\PEL\PelIfd;
use PMW\Library\PEL\PelEntryUserComment;
use PMW\Library\PEL\PelEntryAscii;
use PMW\Library\PEL\PelTag;
use PMW\Library\PEL\PelEntryByte;
use PMW\Library\PEL\PelEntryRational;

/**
 * Controller ini berfungsi untuk melakukan aksi yang berkaitan dengan
 * logbook
 * 
 * @author BagasMuharom <bagashidayat@mhs.unesa.ac.id|bagashidayat45@gmail.com>
 * @package PMW\Http\Controllers
 */
class LogBookController extends Controller
{

    private $validationArr = [
        'catatan' => 'required',
        'biaya' => 'required|numeric'
    ];

    /**
     * Menambah LogBook
     *
     * @param Request $request
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function tambah(Request $request)
    {

        $this->validate($request, $this->validationArr);

        if ($this->bolehTambahLogBook()) {
            $file = $request->file('camera');
            $get = $this->get_geotag($file);
            $latitude = $get['latitude'];
            $longitude = $get['longitude'];
            $ext = $file->getClientOriginalExtension();
            if( $ext == 'jpg' || $ext == 'jpeg'){

                if(!($longitude == 0) || !($latitude == 0)){ 
                    $newName = date('YmdHis').'_'.rand(1000,9999).'.'.$ext;
                    $outputName = 'upload/logbook/'.$newName;

                    $this->addGpsInfo($file, $outputName, $longitude, $latitude, 0, date('Y-m-d H:i:s'));

                    // Menambah Logbook ke database
                    $tambah = LogBook::create([
                        'catatan' => $request->catatan,
                        'biaya' => $request->biaya,
                        'tanggal' => $request->tanggal,
                        'direktori_foto' => $newName,
                        'lat_foto' => $latitude,
                        'long_foto' => $longitude,
                        'id_proposal' => Auth::user()->mahasiswa()->proposal()->id
                    ]);
                    Session::flash('message', 'Berhasil menambah logbook !');
                    return redirect()->route('logbook');
                }else{
                    Session::flash('message_danger', 'Tidak dapat menambah logbook, tidak terdeteksi keterangan lokasi foto !');
                    return back()->withInput();
                }
            }else{
                Session::flash('message_danger', 'File foto kegaitan harus dengan format .jpg !');
                return back()->withInput();
            }
            
        }

        Session::flash('message', 'Anda tidak bisa menambah logbook !');
        return back()->withInput();
    }

    /**
     * Mengedit logbook tertentu
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function edit(Request $request)
    {
        $this->validate($request, $this->validationArr);

        $foto = $request->file('camera');
        $logbook = LogBook::find($request->id);
        $ext = $foto->getClientOriginalExtension();
        $image_path = 'upload/logbook/'.$logbook->direktori_foto;
        $newName = date('YmdHis').'_'.rand(1000,9999).'.'.$ext;
        $newimagepath = 'upload/logbook/'.$newName;
        $get = $this->get_geotag_($foto);
        $latitude = $get['latitude'];
        $longitude = $get['longitude'];
        if( $this->cekFormatFoto($foto)){
        // Menambah LogBook ke database
            if(!is_null($longitude) || !is_null($latitude)){ 
                $edit = LogBook::find($request->id)->update([
                    'catatan' => $request->catatan,
                    'biaya' => $request->biaya,
                    'tanggal' => $request->tanggal,
                    'direktori_foto' => $newName,
                    'lat_foto' => $latitude,
                    'long_foto' => $longitude
                ]);

                
                if($foto != ''){
                    $this->addGpsInfo($foto, $newimagepath, $longitude, $latitude, 0, date('Y-m-d H:i:s'));

                    if(File::exists($image_path)){
                        File::delete($image_path);
                    }
                    
                }
                Session::flash('message', 'Berhasil mengubah logbook !');
                return redirect()->route('logbook');
            }else{
                Session::flash('message_danger', 'Tidak dapat mengubah logbook, tidak terdeteksi keterangan lokasi foto !');
                    return redirect()->route('logbook');
            }
        }else{
            Session::flash('message_danger', 'Gagal mengubah logbook, Format foto harus jpg !');
            return redirect()->route('logbook');
        }
        
    }

    /**
     * Menghapus logbook tertentu
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function hapus(Request $request)
    {
        $logbook = LogBook::find($request->id);

        if(is_null($logbook)){
            return response()->json([
                'message' => 'Anda tidak bisa menghapus logbook tersebut !',
                'type' => 'error'
            ]);
        }

        if($logbook->id_proposal !== Auth::user()->mahasiswa()->proposal()->id){
            return response()->json([
                'message' => 'Anda tidak bisa menghapus logbook tersebut !',
                'type' => 'error'
            ]);
        }

        try {
            $image_path = 'upload/logbook/'.$logbook->direktori_foto;
            
            $logbook->delete();
            if(File::exists($image_path)){
                File::delete($image_path);
            }
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Anda tidak bisa menghapus logbook tersebut !',
                'type' => 'error'
            ]);
        }

        return response()->json([
            'message' => 'Berhasil menghapus logbook !',
            'type' => 'success'
        ]);
    }

    /**
     * Mengecek apakah user terkait bisa menambah logbook
     *
     * @return boolean
     */
    private function bolehTambahLogBook()
    {
        // Jika user adalah ketua tim dan
        // proposal dinyatakan lolos
        return (Auth::user()->isKetua() && Auth::user()->mahasiswa()->proposal()->lolos());
    }

    private function cekFormatFoto($foto){
        if($foto != ""){
            $ext = $foto->getClientOriginalExtension();
            if($ext == 'jpg' || $ext == 'jpeg'){
                return true;
            }else{
                return false;
            }
        }else{
            return true;
        }
    }

    private function convertDecimalToDMS($degree)
    {
        if ($degree > 180 || $degree < - 180) {
            return null;
        }

        $degree = abs($degree); // make sure number is positive
                                // (no distinction here for N/S
                                // or W/E).

        $seconds = $degree * 3600; // Total number of seconds.

        $degrees = floor($degree); // Number of whole degrees.
        $seconds -= $degrees * 3600; // Subtract the number of seconds
                                     // taken by the degrees.

        $minutes = floor($seconds / 60); // Number of whole minutes.
        $seconds -= $minutes * 60; // Subtract the number of seconds
                                   // taken by the minutes.

        $seconds = round($seconds * 100, 0); // Round seconds with a 1/100th
                                             // second precision.

        return [
            [
                $degrees,
                1
            ],
            [
                $minutes,
                1
            ],
            [
                $seconds,
                100
            ]
        ];
    }

    private function addGpsInfo($input, $output, $longitude, $latitude, $altitude, $date_time)
    {
        $jpeg = new PelJpeg(file_get_contents($input));

        
        $exif = new PelExif();
        $jpeg->setExif($exif);

        
        $tiff = new PelTiff();
        $exif->setTiff($tiff);

        $ifd0 = new PelIfd(PelIfd::IFD0);
        $tiff->setIfd($ifd0);

        $gps_ifd = new PelIfd(PelIfd::GPS);
        $ifd0->addSubIfd($gps_ifd);

        $inter_ifd = new PelIfd(PelIfd::INTEROPERABILITY);
        $ifd0->addSubIfd($inter_ifd);

        $ifd0->addEntry(new PelEntryAscii(PelTag::DATE_TIME, $date_time));

        $gps_ifd->addEntry(new PelEntryByte(PelTag::GPS_VERSION_ID, 2, 2, 0, 0));

        list ($hours, $minutes, $seconds) = $this->convertDecimalToDMS($latitude);

        $latitude_ref = ($latitude < 0) ? 'S' : 'N';

        $gps_ifd->addEntry(new PelEntryAscii(PelTag::GPS_LATITUDE_REF, $latitude_ref));
        $gps_ifd->addEntry(new PelEntryRational(PelTag::GPS_LATITUDE, $hours, $minutes, $seconds));

        /* The longitude works like the latitude. */
        list ($hours, $minutes, $seconds) = $this->convertDecimalToDMS($longitude);
        $longitude_ref = ($longitude < 0) ? 'W' : 'E';

        $gps_ifd->addEntry(new PelEntryAscii(PelTag::GPS_LONGITUDE_REF, $longitude_ref));
        $gps_ifd->addEntry(new PelEntryRational(PelTag::GPS_LONGITUDE, $hours, $minutes, $seconds));

        
        $gps_ifd->addEntry(new PelEntryRational(PelTag::GPS_ALTITUDE, [
            abs($altitude),
            1
        ]));
        
        $gps_ifd->addEntry(new PelEntryByte(PelTag::GPS_ALTITUDE_REF, (int) ($altitude < 0)));

        file_put_contents($output, $jpeg->getBytes());
    }


    private function get_geotag($file) {
      $data = exif_read_data($file, 0, true);

      if (isset($data['GPS']) and is_array($data['GPS'])) {
       $lat_ref = $data['GPS']['GPSLatitudeRef']; 
       $lat = $data['GPS']['GPSLatitude'];
       list($num, $dec) = explode('/', $lat[0]);
       $lat_s = $num / $dec;
       list($num, $dec) = explode('/', $lat[1]);
       $lat_m = $num / $dec;
       list($num, $dec) = explode('/', $lat[2]);
       $lat_v = $num / $dec;
       
       $lng_ref = $data['GPS']['GPSLongitudeRef'];
       $lng = $data['GPS']['GPSLongitude'];
       list($num, $dec) = explode('/', $lng[0]);
       $lng_s = $num / $dec;
       list($num, $dec) = explode('/', $lng[1]);
       $lng_m = $num / $dec;
       list($num, $dec) = explode('/', $lng[2]);
       $lng_v = $num / $dec;

       $lat_int = ($lat_s + $lat_m / 60.0 + $lat_v / 3600.0);
       $lat_int = ($lat_ref == 'S') ? '-'.$lat_int : $lat_int;

       $lng_int = ($lng_s + $lng_m / 60.0 + $lng_v / 3600.0);
       $lng_int = ($lng_ref == 'W') ? '-'.$lng_int : $lng_int;

       return array('latitude'=>$lat_int, 'longitude'=>$lng_int);
      } else {
       return array('latitude'=>0, 'longitude'=>0);
      }
    }

    private function get_geotag_($foto) {
      $data = exif_read_data($foto, 0, true);

      if (isset($data['GPS']) and is_array($data['GPS'])) {
       $lat_ref = $data['GPS']['GPSLatitudeRef']; 
       $lat = $data['GPS']['GPSLatitude'];
       list($num, $dec) = explode('/', $lat[0]);
       $lat_s = $num / $dec;
       list($num, $dec) = explode('/', $lat[1]);
       $lat_m = $num / $dec;
       list($num, $dec) = explode('/', $lat[2]);
       $lat_v = $num / $dec;
       
       $lng_ref = $data['GPS']['GPSLongitudeRef'];
       $lng = $data['GPS']['GPSLongitude'];
       list($num, $dec) = explode('/', $lng[0]);
       $lng_s = $num / $dec;
       list($num, $dec) = explode('/', $lng[1]);
       $lng_m = $num / $dec;
       list($num, $dec) = explode('/', $lng[2]);
       $lng_v = $num / $dec;

       $lat_int = ($lat_s + $lat_m / 60.0 + $lat_v / 3600.0);
       $lat_int = ($lat_ref == 'S') ? '-'.$lat_int : $lat_int;

       $lng_int = ($lng_s + $lng_m / 60.0 + $lng_v / 3600.0);
       $lng_int = ($lng_ref == 'W') ? '-'.$lng_int : $lng_int;

       return array('latitude'=>$lat_int, 'longitude'=>$lng_int);
      } else {
       return array('latitude'=>0, 'longitude'=>0);
      }
    }

}
