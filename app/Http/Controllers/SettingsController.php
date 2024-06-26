<?php

namespace App\Http\Controllers;

use App\Models\About;
use Illuminate\Http\Request;
use App\Models\Pengaturan;
use App\Models\Team;
use App\Models\Informasi_Contact;
use App\Models\Informasi_Sosmed;
use Illuminate\Support\Facades\DB;

class SettingsController extends Controller
{
    public function index()
    {
        $ApiToken = DB::table('pengaturan')->value('api_token', 'gateway');
        $pengaturan = Pengaturan::first();
        $about = About::first();
        $team =  Team::all();
        $contact =  Informasi_Contact::all();
        $sosmed =  Informasi_Sosmed::all();

        return view('admin.settings.index', compact('ApiToken', 'pengaturan', 'about', 'team', 'contact', 'sosmed'));
    }

    public function storeApi(Request $request)
    {
        // Validasi input
        $request->validate([
            'api_token' => 'required|string|max:255',
        ]);

        // Check if a Pengaturan record already exists
        $pengaturan = Pengaturan::first();

        if ($pengaturan) {
            // Update the existing record
            $pengaturan->api_token = $request->api_token;
            $pengaturan->save();

            // Redirect kembali ke halaman sebelumnya dengan pesan sukses
            return redirect()->back()->with('success', 'API Token berhasil diperbarui!');
        } else {
            // Create a new Pengaturan record with only the api_token
            Pengaturan::create([
                'api_token' => $request->api_token,
            ]);

            // Redirect kembali ke halaman sebelumnya dengan pesan sukses
            return redirect()->back()->with('success', 'API Token berhasil disimpan!');
        }
    }

    public function updateApi(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'api_token' => 'required|string|max:255',
        ]);

        // Temukan data pengaturan berdasarkan ID
        $ApiToken = Pengaturan::findOrFail($id);

        // Update data pengaturan
        $ApiToken->update([
            'api_token' => $request->api_token,
        ]);

        // Redirect kembali ke halaman sebelumnya dengan pesan sukses
        return redirect()->back()->with('success', 'API Token berhasil diperbarui!');
    }

    public function storegateway(Request $request)
    {
        $request->validate([
            'gateway' => 'required|integer',
        ]);

        $pengaturan = Pengaturan::first();

        if ($pengaturan) {
            $pengaturan->gateway = $request->gateway;
            $pengaturan->save();
        } else {
            Pengaturan::create([
                'gateway' => $request->gateway,
            ]);
        }

        // Redirect kembali ke halaman sebelumnya dengan pesan sukses
        return redirect()->back()->with('gateway', 'Gateway berhasil disimpan!');
    }

    public function updategateway(Request $request, $id)
    {
        $request->validate([
            'gateway' => 'required',
        ]);

        $pengaturan = Pengaturan::findOrFail($id);

        // Update data pengaturan
        $pengaturan->update([
            'gateway' => $request->gateway,
        ]);

        // Redirect kembali ke halaman sebelumnya dengan pesan sukses
        return redirect()->back()->with('gateway', 'gateway berhasil diperbarui!');
    }

    public function storepengaturan(Request $request)
    {
        // Validasi data yang diterima dari formulir
        $request->validate([
            'title_pengaturan' => 'required|string|max:25',
            'name_pengaturan' => 'required|string|max:50',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048|dimensions:min_width=280,min_height=280',
            'background' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048|dimensions:min_width=768,min_height=432',
        ], [
            'title_pengaturan.required' => 'Title harus diisi.',
            'title_pengaturan.string' => 'Title harus berupa teks.',
            'title_pengaturan.max' => 'Title tidak boleh lebih dari :max karakter.',
            'name_pengaturan.required' => 'Name harus diisi.',
            'name_pengaturan.string' => 'Name harus berupa teks.',
            'name_pengaturan.max' => 'Name tidak boleh lebih dari :max karakter.',
            'logo.image' => 'File harus berupa gambar.',
            'logo.mimes' => 'File harus berformat jpeg, png, jpg, gif, atau webp.',
            'logo.max' => 'File tidak boleh lebih dari :max KB.',
            'logo.dimensions' => 'Ukuran logo minimal harus 548x455 piksel.',
            'background.image' => 'File harus berupa gambar.',
            'background.mimes' => 'File harus berformat jpeg, png, jpg, gif, atau webp.',
            'background.max' => 'File tidak boleh lebih dari :max KB.',
            'background.dimensions' => 'Ukuran background minimal harus 768x432 piksel.',
        ]);

        // Buat instance baru dari model Pengaturan
        $pengaturan = Pengaturan::first();

        if ($pengaturan) {
            // Update existing Pengaturan
            $pengaturan->title_pengaturan = $request->input('title_pengaturan');
            $pengaturan->name_pengaturan = $request->input('name_pengaturan');
        } else {
            // Create a new Pengaturan instance
            $pengaturan = new Pengaturan([
                'title_pengaturan' => $request->input('title_pengaturan'),
                'name_pengaturan' => $request->input('name_pengaturan')
            ]);
        }

        // Upload dan simpan gambar logo jika ada
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('logos', 'public');
            $pengaturan->logo = $logoPath;
        }

        // Upload dan simpan gambar background jika ada
        if ($request->hasFile('background')) {
            $backgroundPath = $request->file('background')->store('backgrounds', 'public');
            $pengaturan->background = $backgroundPath;
        }

        // Simpan data ke dalam database
        $pengaturan->save();

        // Redirect pengguna kembali ke halaman yang sesuai
        return redirect()->back()->with('success', 'Pengaturan berhasil disimpan.');
    }

    public function updatepengaturan(Request $request, $id)
    {
        // Validasi data yang diterima dari formulir
        $request->validate([
            'title_pengaturan' => 'required|string|max:25',
            'name_pengaturan' => 'required|string|max:50',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048|dimensions:min_width=280,min_height=280',
            'background' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048|dimensions:min_width=768,min_height=432',
        ], [
            'title_pengaturan.required' => 'Title harus diisi.',
            'title_pengaturan.string' => 'Title harus berupa teks.',
            'title_pengaturan.max' => 'Title tidak boleh lebih dari :max karakter.',
            'name_pengaturan.required' => 'Name harus diisi.',
            'name_pengaturan.string' => 'Name harus berupa teks.',
            'name_pengaturan.max' => 'Name tidak boleh lebih dari :max karakter.',
            'logo.image' => 'File harus berupa gambar.',
            'logo.mimes' => 'File harus berformat jpeg, png, jpg, gif, atau webp.',
            'logo.max' => 'File tidak boleh lebih dari :max KB.',
            'logo.dimensions' => 'Ukuran logo minimal harus 548x455 piksel.',
            'background.image' => 'File harus berupa gambar.',
            'background.mimes' => 'File harus berformat jpeg, png, jpg, gif, atau webp.',
            'background.max' => 'File tidak boleh lebih dari :max KB.',
            'background.dimensions' => 'Ukuran background minimal harus 768x432 piksel.',
        ]);

        // Temukan entri pengaturan yang sesuai berdasarkan ID
        $pengaturan = Pengaturan::findOrFail($id);

        // Perbarui atribut-atribut yang sesuai dengan data yang dikirimkan dari formulir
        $pengaturan->title_pengaturan = $request->input('title_pengaturan');
        $pengaturan->name_pengaturan = $request->input('name_pengaturan');

        // Upload dan simpan gambar logo jika ada
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('logos', 'public');
            $pengaturan->logo = $logoPath;
        }

        // Upload dan simpan gambar background jika ada
        if ($request->hasFile('background')) {
            $backgroundPath = $request->file('background')->store('backgrounds', 'public');
            $pengaturan->background = $backgroundPath;
        }

        // Simpan perubahan ke dalam database
        $pengaturan->save();

        // Redirect pengguna kembali ke halaman yang sesuai
        return redirect()->back()->with('success', 'Pengaturan berhasil diperbarui.');
    }

    public function storeabout(Request $request)
    {
        // Validasi data yang diterima dari formulir
        $request->validate([
            'title_about' => 'required|string|max:25',
            'left_description' => 'required|string',
            'right_description' => 'nullable|string',
            'feature_1' => 'nullable|string|max:70',
            'feature_2' => 'nullable|string',
            'feature_3' => 'nullable|string',
        ], [
            'title_about.required' => 'Judul harus diisi.',
            'title_about.string' => 'Judul harus berupa teks.',
            'title_about.max' => 'Judul tidak boleh lebih dari :max karakter.',
            'left_description.required' => 'Deskripsi kiri harus diisi.',
            'left_description.string' => 'Deskripsi kiri harus berupa teks.',
        ]);

        try {
            // Buat instance baru dari model About
            $about = new About();

            // Isi data pada instance About
            $about->title_about = $request->input('title_about');
            $about->left_description = $request->input('left_description');
            $about->right_description = $request->input('right_description');
            $about->feature_1 = $request->input('feature_1');
            $about->feature_2 = $request->input('feature_2');
            $about->feature_3 = $request->input('feature_3');

            // Simpan data baru ke dalam database
            $about->save();

            // Redirect ke halaman yang sesuai dengan keberhasilan
            return redirect()->back()->with('success', 'Data About berhasil ditambahkan.');
        } catch (\Exception $e) {
            // Tangani jika terjadi kesalahan
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }


    public function updateabout(Request $request, $id)
    {
        // Validasi data yang diterima dari formulir
        $request->validate([
            'title_about' => 'required|string|max:25',
            'left_description' => 'required|string',
            'right_description' => 'required|string',
            'feature_1' => 'nullable|string|max:70',
            'feature_2' => 'nullable|string',
            'feature_3' => 'nullable|string',
        ], [
            'title_about.required' => 'Judul harus diisi.',
            'title_about.string' => 'Judul harus berupa teks.',
            'title_about.max' => 'Judul tidak boleh lebih dari :max karakter.',
            'left_description.required' => 'Deskripsi kiri harus diisi.',
            'left_description.string' => 'Deskripsi kiri harus berupa teks.',
            'right_description.required' => 'Deskripsi kanan harus diisi.',
            'right_description.string' => 'Deskripsi kanan harus berupa teks.',
        ]);

        try {
            // Temukan data About berdasarkan ID
            $about = About::findOrFail($id);

            // Update data About
            $about->title_about = $request->input('title_about');
            $about->left_description = $request->input('left_description');
            $about->right_description = $request->input('right_description');
            $about->feature_1 = $request->input('feature_1');
            $about->feature_2 = $request->input('feature_2');
            $about->feature_3 = $request->input('feature_3');
            $about->save();

            // Redirect ke halaman yang sesuai dengan keberhasilan
            return redirect()->back()->with('success', 'About berhasil diperbarui.');
        } catch (\Exception $e) {
            // Tangani jika terjadi kesalahan
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function storeteam(Request $request)
    {
        // Validasi input
        $validatedData = $request->validate([
            'informasi' => 'required',
            'username_1' => 'required',
            'posisi_1' => 'required',
            'deskripsi_1' => 'required',
            'username_2' => 'required',
            'posisi_2' => 'required',
            'deskripsi_2' => 'required',
            'username_3' => 'required',
            'posisi_3' => 'required',
            'deskripsi_3' => 'required',
            'username_4' => 'required',
            'posisi_4' => 'required',
            'deskripsi_4' => 'required',
        ]);

        // Simpan data
        $team = new Team();
        $team->informasi = $validatedData['informasi'];
        $team->username_1 = $validatedData['username_1'];
        $team->posisi_1 = $validatedData['posisi_1'];
        $team->deskripsi_1 = $validatedData['deskripsi_1'];
        $team->username_2 = $validatedData['username_2'];
        $team->posisi_2 = $validatedData['posisi_2'];
        $team->deskripsi_2 = $validatedData['deskripsi_2'];
        $team->username_3 = $validatedData['username_3'];
        $team->posisi_3 = $validatedData['posisi_3'];
        $team->deskripsi_3 = $validatedData['deskripsi_3'];
        $team->username_4 = $validatedData['username_4'];
        $team->posisi_4 = $validatedData['posisi_4'];
        $team->deskripsi_4 = $validatedData['deskripsi_4'];

        // Jika foto diunggah, simpan foto yang baru
        if ($request->hasFile('photo_1')) {
            $imagePath = $request->file('photo_1')->store('photos', 'public');
            $team->photo_1 = $imagePath;
        }

        // Jika foto diunggah, simpan foto yang baru
        if ($request->hasFile('photo_2')) {
            $imagePath = $request->file('photo_2')->store('photos', 'public');
            $team->photo_2 = $imagePath;
        }

        // Jika foto diunggah, simpan foto yang baru
        if ($request->hasFile('photo_3')) {
            $imagePath = $request->file('photo_3')->store('photos', 'public');
            $team->photo_3 = $imagePath;
        }

        // Jika foto diunggah, simpan foto yang baru
        if ($request->hasFile('photo_4')) {
            $imagePath = $request->file('photo_4')->store('photos', 'public');
            $team->photo_4 = $imagePath;
        }

        // Simpan informasi
        $team->save();

        // Redirect back
        return redirect()->back()->with('success', 'Data tim berhasil ditambahkan!');
    }

    public function informasi(Request $request, $id)
    {
        // Validasi input jika diperlukan
        $request->validate([
            'informasi' => 'required|string|max:1000',
        ], [
            'informasi.required' => 'Informasi harus diisi.',
            'informasi.string' => 'Informasi harus berupa teks.',
            'informasi.max' => 'Informasi tidak boleh lebih dari :max karakter.',
        ]);

        try {
            // Cari data tim yang akan diperbarui
            $team = Team::findOrFail($id);

            // Update data tim dengan data baru dari request
            $team->informasi = $request->input('informasi');

            $team->save();

            return redirect()->back()->with('success', ' Informasi Team berhasil diperbarui.');
        } catch (\Exception $e) {
            // Tangani jika terjadi kesalahan
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function updateTeam1(Request $request, $id)
    {
        // Validasi input jika diperlukan
        $request->validate([
            'username_1' => 'required|string',
            'posisi_1' => 'required|string',
            'deskripsi_1' => 'nullable|string',
            'photo_1' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            // Tambahkan validasi untuk field lain jika diperlukan
        ], [
            'username_1.required' => 'Username harus diisi.',
            'username_1.string' => 'Username harus berupa teks.',
            'posisi_1.required' => 'Posisi harus diisi.',
            'posisi_1.string' => 'Posisi harus berupa teks.',
            'deskripsi_1.string' => 'Deskripsi harus berupa teks.',
        ]);

        try {
            // Cari data tim yang akan diperbarui
            $team = Team::findOrFail($id);

            // Update data tim dengan data baru dari request
            $team->username_1 = $request->input('username_1');
            $team->posisi_1 = $request->input('posisi_1');
            $team->deskripsi_1 = $request->input('deskripsi_1');

            // Jika foto diunggah, simpan foto yang baru
            if ($request->hasFile('photo_1')) {
                $imagePath = $request->file('photo_1')->store('photos', 'public');
                $team->photo_1 = $imagePath;
            }

            $team->save();

            return redirect()->back()->with('success', 'Team 1 berhasil diperbarui.');
        } catch (\Exception $e) {
            // Tangani jika terjadi kesalahan
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function updateTeam2(Request $request, $id)
    {
        // Validasi input jika diperlukan
        $request->validate([
            'username_2' => 'required|string',
            'posisi_2' => 'required|string',
            'deskripsi_2' => 'nullable|string',
            'photo_2' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            // Tambahkan validasi untuk field lain jika diperlukan
        ], [
            'username_2.required' => 'Username harus diisi.',
            'username_2.string' => 'Username harus berupa teks.',
            'posisi_2.required' => 'Posisi harus diisi.',
            'posisi_2.string' => 'Posisi harus berupa teks.',
            'deskripsi_2.string' => 'Deskripsi harus berupa teks.',
        ]);

        try {
            // Cari data tim yang akan diperbarui
            $team = Team::findOrFail($id);

            // Update data tim dengan data baru dari request
            $team->username_2 = $request->input('username_2');
            $team->posisi_2 = $request->input('posisi_2');
            $team->deskripsi_2 = $request->input('deskripsi_2');

            // Jika foto diunggah, simpan foto yang baru
            if ($request->hasFile('photo_2')) {
                $imagePath = $request->file('photo_2')->store('photos', 'public');
                $team->photo_2 = $imagePath;
            }

            $team->save();

            return redirect()->back()->with('success', 'Team 2 berhasil diperbarui.');
        } catch (\Exception $e) {
            // Tangani jika terjadi kesalahan
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function updateTeam3(Request $request, $id)
    {
        // Validasi input jika diperlukan
        $request->validate([
            'username_3' => 'required|string',
            'posisi_3' => 'required|string',
            'deskripsi_3' => 'nullable|string',
            'photo_3' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            // Tambahkan validasi untuk field lain jika diperlukan
        ], [
            'username_3.required' => 'Username harus diisi.',
            'username_3.string' => 'Username harus berupa teks.',
            'posisi_3.required' => 'Posisi harus diisi.',
            'posisi_3.string' => 'Posisi harus berupa teks.',
            'deskripsi_3.string' => 'Deskripsi harus berupa teks.',
        ]);

        try {
            // Cari data tim yang akan diperbarui
            $team = Team::findOrFail($id);

            // Update data tim dengan data baru dari request
            $team->username_3 = $request->input('username_3');
            $team->posisi_3 = $request->input('posisi_3');
            $team->deskripsi_3 = $request->input('deskripsi_3');

            // Jika foto diunggah, simpan foto yang baru
            if ($request->hasFile('photo_3')) {
                $imagePath = $request->file('photo_3')->store('photos', 'public');
                $team->photo_3 = $imagePath;
            }

            $team->save();

            return redirect()->back()->with('success', 'Team 3 berhasil diperbarui.');
        } catch (\Exception $e) {
            // Tangani jika terjadi kesalahan
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }
    public function updateTeam4(Request $request, $id)
    {
        // Validasi input jika diperlukan
        $request->validate([
            'username_4' => 'required|string',
            'posisi_4' => 'required|string',
            'deskripsi_4' => 'nullable|string',
            'photo_4' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            // Tambahkan validasi untuk field lain jika diperlukan
        ], [
            'username_4.required' => 'Username harus diisi.',
            'username_4.string' => 'Username harus berupa teks.',
            'posisi_4.required' => 'Posisi harus diisi.',
            'posisi_4.string' => 'Posisi harus berupa teks.',
            'deskripsi_4.string' => 'Deskripsi harus berupa teks.',
        ]);

        try {
            // Cari data tim yang akan diperbarui
            $team = Team::findOrFail($id);

            // Update data tim dengan data baru dari request
            $team->username_4 = $request->input('username_4');
            $team->posisi_4 = $request->input('posisi_4');
            $team->deskripsi_4 = $request->input('deskripsi_4');

            // Jika foto diunggah, simpan foto yang baru
            if ($request->hasFile('photo_4')) {
                $imagePath = $request->file('photo_4')->store('photos', 'public');
                $team->photo_4 = $imagePath;
            }

            $team->save();

            return redirect()->back()->with('success', 'Team 4 berhasil diperbarui.');
        } catch (\Exception $e) {
            // Tangani jika terjadi kesalahan
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }
    public function updateinformasicontact(Request $request, $id)
    {
        // Validasi input jika diperlukan
        $request->validate([
            'name_location' => 'required|string',
            'email_informasi' => 'required|string',
            'call_informasi' => 'nullable|string|regex:/^[0-9]{0,14}$/',
        ], [
            'name_location.required' => 'Nama lokasi harus diisi.',
            'name_location.string' => 'Nama lokasi harus berupa teks.',
            'email_informasi.required' => 'Email harus diisi.',
            'email_informasi.string' => 'Email harus berupa teks.',
            'call_informasi.regex' => 'Nomor telepon harus berupa angka dan maksimal 14 digit.',
        ]);

        try {
            // Cari data tim yang akan diperbarui
            $informasi_contact = Informasi_Contact::findOrFail($id);

            // Update data tim dengan data baru dari request
            $informasi_contact->name_location = $request->input('name_location');
            $informasi_contact->email_informasi = $request->input('email_informasi');
            $informasi_contact->call_informasi = $request->input('call_informasi');

            $informasi_contact->save();

            return redirect()->back()->with('success', 'Informasi Contact berhasil diperbarui.');
        } catch (\Exception $e) {
            // Tangani jika terjadi kesalahan
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function storeinformasicontact(Request $request)
    {
        // Validasi data yang dikirim dari form
        $validatedData = $request->validate([
            'name_location' => 'required',
            'email_informasi' => 'required|email',
            'call_informasi' => 'required|numeric',
        ]);

        // Buat instance model InformasiContact untuk menyimpan data
        $informasiContact = new Informasi_Contact();
        $informasiContact->name_location = $request->name_location;
        $informasiContact->email_informasi = $request->email_informasi;
        $informasiContact->call_informasi = $request->call_informasi;

        // Simpan data ke dalam database
        $informasiContact->save();

        // Redirect kembali ke halaman sebelumnya dengan pesan sukses
        return back()->with('success', 'Informasi Contact berhasil disimpan.');
    }

    public function updateinformasisosmed(Request $request, $id)
    {
        // Validasi input jika diperlukan
        $request->validate([
            'title_sosmed' => 'required|string',
            'street_name' => 'required|string',
            'subdistrict' => 'nullable|string|max:255', // Maksimal 255 karakter
            'ward' => 'nullable|string|max:255', // Maksimal 255 karakter
            'call' => 'nullable|string|regex:/^[0-9]{0,14}$/',
            'email' => 'nullable|string|email|max:255', // Format email dan maksimal 255 karakter
        ], [
            'title_sosmed.required' => 'Judul sosial media harus diisi.',
            'title_sosmed.string' => 'Judul sosial media harus berupa teks.',
            'street_name.required' => 'Nama jalan harus diisi.',
            'street_name.string' => 'Nama jalan harus berupa teks.',
            'subdistrict.max' => 'Kecamatan maksimal 255 karakter.',
            'ward.max' => 'Desa/Kelurahan maksimal 255 karakter.',
            'call.regex' => 'Nomor telepon harus berupa angka dan maksimal 14 digit.',
            'email.email' => 'Format email tidak valid.',
            'email.max' => 'Email maksimal 255 karakter.',
        ]);

        try {
            // Cari data informasi sosmed yang akan diperbarui
            $informasi_sosmed = Informasi_Sosmed::find($id);

            // Periksa apakah data ditemukan
            if (!$informasi_sosmed) {
                return redirect()->back()->withErrors(['error' => 'Data informasi sosmed tidak ditemukan.']);
            }

            // Update data informasi sosmed dengan data baru dari request
            $informasi_sosmed->title_sosmed = $request->input('title_sosmed');
            $informasi_sosmed->street_name = $request->input('street_name');
            $informasi_sosmed->subdistrict = $request->input('subdistrict');
            $informasi_sosmed->ward = $request->input('ward');
            $informasi_sosmed->call = $request->input('call');
            $informasi_sosmed->email = $request->input('email');

            $informasi_sosmed->save();

            return redirect()->back()->with('success', 'Informasi Sosmed berhasil diperbarui.');
        } catch (\Exception $e) {
            // Tangani jika terjadi kesalahan
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function storeinformasisosmed(Request $request)
    {
        // Validasi data yang diterima dari formulir
        $validatedData = $request->validate([
            'title_sosmed' => 'required',
            'street_name' => 'required',
            'subdistrict' => 'required',
            'ward' => 'required',
            'call' => 'required|numeric',
            'email' => 'required|email',
        ]);

        // Buat objek informasi sosmed baru dengan data yang diverifikasi
        $informasiSosmed = new Informasi_Sosmed();
        $informasiSosmed->title_sosmed = $validatedData['title_sosmed'];
        $informasiSosmed->street_name = $validatedData['street_name'];
        $informasiSosmed->subdistrict = $validatedData['subdistrict'];
        $informasiSosmed->ward = $validatedData['ward'];
        $informasiSosmed->call = $validatedData['call'];
        $informasiSosmed->email = $validatedData['email'];

        // Simpan informasi sosmed baru ke dalam database
        $informasiSosmed->save();

        // Redirect ke halaman yang sesuai atau kembali ke halaman sebelumnya
        return redirect()->back()->with('success', 'Informasi Sosmed telah berhasil ditambahkan.');
    }
}
