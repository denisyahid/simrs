export function example(): any {

}

export function ASESMEN_AWAL() : any {
  return [
    {
      label : "1",
      children :[
        {
          label : "1",
          type : "label",
          isLoop : false,
          rowspan : 4,
        },
        {
          label : "Riwayat Jatuh",
          type : "label",
          isLoop : false,
          rowspan : 4,
        },
        {
          label : "Apakah pasien datang ke Rumah Sakit karena jatuh?",
          type : "label",
          isLoop : false,
          rowspan : 2,
        },
        {
          label : "Salah Satu jawaban Ya = 6",
          type : "label",
          isLoop : false,
          rowspan : 4,
        },
        {
          model : "apakahPasienDatangKerumahSakitKarnaJatuh_",
          type : "checkbox",
          value : {
            label : "Ya",
            skor : 6
          },
          label : "Ya",
          isLoop:true
        },
      ]
    },
    {
      label : "2",
      children :[
        {
          model : "apakahPasienDatangKerumahSakitKarnaJatuh_",
          type : "checkbox",
          value : {
            skor : 0,
            label : "Tidak"
          },
          label : "Tidak",
          isLoop:true
        },
      ]
    },
    {
      label : "3",
      children :[
        {
          label : "Jika tidak, apakah pasien mengalami jatuh pada 2 bulan terakhir ini?",
          type : "label",
          isLoop : false,
          rowspan : 2,
        },
        {
          model : "apakahPaienMengalamiJatuhDalam2bulanTerakhir_",
          type : "checkbox",
          value : {
            label : "Ya",
            skor : 6
          },
          label : "Ya",
          isLoop:true
        },
      ]
    },
    {
      label : "4",
      children :[
        {
          model : "apakahPaienMengalamiJatuhDalam2bulanTerakhir_",
          type : "checkbox",
          value : {
            skor : 0,
            label : "Tidak"
          },
          label : "Tidak",
          isLoop:true
        },
      ]
    },
    {
      label : "5",
      children :[
        {
          label : "2",
          type : "label",
          isLoop : false,
          rowspan : 6,
        },
        {
          label : "Status mental",
          type : "label",
          isLoop : false,
          rowspan : 6,
        },
        {
          label : "Apakah pasien delirium? (tidak dapat membuat keputusan, pola pikir tidak terorganisir, gangguan daya ingat)",
          type : "label",
          isLoop : false,
          rowspan : 2,
        },
        {
          label : "Salah Satu jawaban Ya = 14",
          type : "label",
          isLoop : false,
          rowspan : 6,
        },
        {
          model : "apakahPasienDelirium_",
          type : "checkbox",
          value : {
            label : "Ya",
            skor : 14
          },
          label : "Ya",
          isLoop:true
        },
      ]
    },
    {
      label : "6",
      children :[
        {
          model : "apakahPasienDelirium_",
          type : "checkbox",
          value : {
            skor : 0,
            label : "Tidak"
          },
          label : "Tidak",
          isLoop:true
        },
      ]
    },
    {
      label : "7",
      children :[
        {
          label : "Apakah pasien disorientasi? (salah menyebutkan waktu, tempat, atau orang)",
          type : "label",
          isLoop : false,
          rowspan : 2,
        },
        {
          model : "apakahPasienDisorientasi_",
          type : "checkbox",
          value : {
            label : "Ya",
            skor : 14
          },
          label : "Ya",
          isLoop:true
        },
      ]
    },
    {
      label : "8",
      children :[
        {
          model : "apakahPasienDisorientasi_",
          type : "checkbox",
          value : {
            skor : 0,
            label : "Tidak"
          },
          label : "Tidak",
          isLoop:true
        },
      ]
    },
    {
      label : "9",
      children :[
        {
          label : "Apakah pasien mengalami agitasi? (ketakutan, gelisah dan cemas?)",
          type : "label",
          isLoop : false,
          rowspan : 2,
        },
        {
          model : "apakahPasienMengalamiAgitasi_",
          type : "checkbox",
          value : {
            label : "Ya",
            skor : 1
          },
          label : "Ya",
          isLoop:true
        },
      ]
    },
    {
      label : "9",
      children :[
        {
          model : "apakahPasienMengalamiAgitasi_",
          type : "checkbox",
          value : {
            skor : 0,
            label : "Tidak"
          },
          label : "Tidak",
          isLoop:true
        },
      ]
    },
    {
      label : "10",
      children :[
        {
          label : "3",
          type : "label",
          isLoop : false,
          rowspan : 6,
        },
        {
          label : "Penglihatan",
          type : "label",
          isLoop : false,
          rowspan : 6,
        },
        {
          label : "Apakah pasien memakai kacamata?",
          type : "label",
          isLoop : false,
          rowspan : 2,
        },
        {
          label : "Salah Satu jawaban Ya = 1",
          type : "label",
          isLoop : false,
          rowspan : 6,
        },
        {
          model : "apakahPasienDelirium_",
          type : "checkbox",
          value : {
            label : "Ya",
            skor : 1
          },
          label : "Ya",
          isLoop:true
        },
      ]
    },
    {
      label : "11",
      children :[
        {
          model : "apakahPasienDelirium_",
          type : "checkbox",
          value : {
            skor : 0,
            label : "Tidak"
          },
          label : "Tidak",
          isLoop:true
        },
      ]
    },
    {
      label : "12",
      children :[
        {
          label : "Apakah pasien mengeluh adanya penglihatan buram? ",
          type : "label",
          isLoop : false,
          rowspan : 2,
        },
        {
          model : "apakahPasienAdaKeluhanPengelihanBuram_",
          type : "checkbox",
          value : {
            label : "Ya",
            skor : 1
          },
          label : "Ya",
          isLoop:true
        },
      ]
    },
    {
      label :"13",
      children :[
        {
          model : "apakahPasienAdaKeluhanPengelihanBuram_",
          type : "checkbox",
          value : {
            skor : 0,
            label : "Tidak"
          },
          label : "Tidak",
          isLoop:true
        },
      ]
    },
    {
      label : "9",
      children :[
        {
          label : "Apakah pasien mempunyai glukoma/katarak/ degenerasi makula)",
          type : "label",
          isLoop : false,
          rowspan : 2,
        },
        {
          model : "apakahPasienMempunyaiGlukosa_",
          type : "checkbox",
          value : {
            label : "Ya",
            skor : 1
          },
          label : "Ya",
          isLoop:true
        },
      ]
    },
    {
      label :"13",
      children :[
        {
          model : "apakahPasienMempunyaiGlukosa_",
          type : "checkbox",
          value : {
            skor : 0,
            label : "Tidak"
          },
          label : "Tidak",
          isLoop:true
        },
      ]
    },
    {
      label : "14",
      children :[
        {
          label : "4",
          type : "label",
          isLoop : false,
          rowspan : 2,
        },
        {
          label : "Kebiasaan berkemih",
          type : "label",
          isLoop : false,
          rowspan : 2,
        },
        {
          label : "Apakah terdapat perubahan perilaku berkemih? (frekwensi, urgensi, inkontinesia, nokturia)",
          type : "label",
          isLoop : false,
          rowspan : 2,
        },
        {
          label : "Ya = 2",
          type : "label",
          isLoop : false,
          rowspan : 2,
        },
        {
          model : "apakahPasienTerdapatPerubahanBerkemih_",
          type : "checkbox",
          value : {
            label : "Ya",
            skor : 2
          },
          label : "Ya",
          isLoop:true
        },
      ]
    },
    {
      label : "12",
      children :[
        {
          model : "apakahPasienTerdapatPerubahanBerkemih_",
          type : "checkbox",
          value : {
            skor : 0,
            label : "Tidak"
          },
          label : "Tidak",
          isLoop:true
        },
      ]
    },
    {
      label : "13",
      children :[
        {
          label : "5",
          type : "label",
          isLoop : false,
          rowspan : 4,
        },
        {
          label : "Transfer (dari tempat tidur ke kursi dan kembali lagi ke tempat tidur)",
          type : "label",
          isLoop : false,
          rowspan : 4,
        },
        {
          label : "Mandiri (boleh memakai alat bantu jalan)",
          type : "label",
          isLoop : false,
          rowspan : 1,
        },
        {
          label : "Jumlah nilai transfer dan mobilitas Jika nilai total 0–3 maka skor = 0 Jika nilai Total 4–6 maka skor = 7",
          type : "label",
          isLoop : false,
          rowspan : 8,
        },
        {
          model : "transferDariTempatTidur_",
          type : "checkbox",
          value : {
            label : "Mandiri (boleh memakai alat bantu jalan)",
            skor : 0
          },
          label : "Ya",
          isLoop:true
        },
      ]
    },
    {
      label : "14",
      children :[
        {
          label : "Memerlukan bantuan (1orang) / dalam pengawasan",
          type : "label",
          isLoop : false,
          rowspan : 1,
        },
        {
          model : "transferDariTempatTidur_",
          type : "checkbox",
          value : {
            label : "Memerlukan bantuan (1orang) / dalam pengawasan",
            skor : 1
          },
          label : "Ya",
          isLoop:true
        },
      ]
    },
    {
      label : "15",
      children :[
        {
          label : "Memerlukan bantuan yang nyata (2 orang)",
          type : "label",
          isLoop : false,
          rowspan : 1,
        },
        {
          model : "transferDariTempatTidur_",
          type : "checkbox",
          value : {
            label : "Memerlukan bantuan yang nyata (2 orang)",
            skor : 2
          },
          label : "Ya",
          isLoop:true
        },
      ]
    },
    {
      label : "16",
      children :[
        {
          label : "Tidak dapat duduk dengan seimbang/ memerlukan bantuan total",
          type : "label",
          isLoop : false,
          rowspan : 1,
        },
        {
          model : "transferDariTempatTidur_",
          type : "checkbox",
          value : {
            label : "Tidak dapat duduk dengan seimbang/ memerlukan bantuan total",
            skor : 3
          },
          label : "Ya",
          isLoop:true
        },
      ]
    },
    {
      label : "17",
      children :[
        {
          label : "6",
          type : "label",
          isLoop : false,
          rowspan : 4,
        },
        {
          label : "Mobilitas",
          type : "label",
          isLoop : false,
          rowspan : 4,
        },
        {
          label : "Mandiri (boleh memakai alat bantu jalan) ",
          type : "label",
          isLoop : false,
          rowspan : 1,
        },
        {
          model : "mobilitas_",
          type : "checkbox",
          value : {
            label : "Mandiri (boleh memakai alat bantu jalan)",
            skor : 0
          },
          label : "Ya",
          isLoop:true
        },
      ]
    },
    {
      label : "18",
      children :[
        {
          label : "Berjalan dengan bantuan 1 orang (verbal/ fisik)",
          type : "label",
          isLoop : false,
          rowspan : 1,
        },
        {
          model : "mobilitas_",
          type : "checkbox",
          value : {
            label : "Berjalan dengan bantuan 1 orang (verbal/ fisik)",
            skor : 1
          },
          label : "Ya",
          isLoop:true
        },
      ]
    },
    {
      label : "19",
      children :[
        {
          label : "Menggunakan kursi roda",
          type : "label",
          isLoop : false,
          rowspan : 1,
        },
        {
          model : "mobilitas_",
          type : "checkbox",
          value : {
            label : "Menggunakan kursi roda",
            skor : 2
          },
          label : "Ya",
          isLoop:true
        },
      ]
    },
    {
      label : "19",
      children :[
        {
          label : "Imobilisasi",
          type : "label",
          isLoop : false,
          rowspan : 1,
        },
        {
          model : "mobilitas_",
          type : "checkbox",
          value : {
            label : "Imobilisasi",
            skor : 3
          },
          label : "Ya",
          isLoop:true
        },
      ]
    },
    {
      label : "20",
      children :[
        {
          label : "Skor Transfer & Mobilitas	",
          type : "label",
          isLoop : false,
          colspan : 4,
        },
        {
          model : "totalTransferMobilitas_",
          type : "input",
          placeholder : "Total",
          isLoop:true
        },
      ]
    },
    {
      label : "20",
      children :[
        {
          label : "Skor	",
          type : "label",
          isLoop : false,
          colspan : 4,
        },
        {
          model : "skor_",
          type : "input",
          placeholder : "skor",
          isLoop:true
        },
      ]
    },
    {
      label : "20",
      children :[
        {
          label : "Skor Total",
          type : "label",
          isLoop : false,
          colspan : 4,
        },
        {
          model : "skorTotal_",
          type : "input",
          placeholder : "Total Skor",
          isLoop:true
        },
      ]
    },
    {
      label : "20",
      children :[
        {
          label : "Keterangan	",
          type : "label",
          isLoop : false,
          colspan : 4,
        },
        {
          model : "keterangan_",
          type : "input",
          placeholder : "keterangan",
          isLoop:true
        },
      ]
    },
  ]
}

export function PROTOKOL_INTERVENSI(): any {
  return [
    {
      label : "1",
      children :[
        {
          label : "1",
          type : "label",
          isLoop : false,
        },
        {
          label : "Pakaikan tanda risiko jatuh pada gelang identitas pasien	",
          type : "label",
          isLoop : false,
        },
        {
          model : "pakaianTandResikoJatuhPadaGelang",
          type : "checkbox",
          value : {
            label : "Pakaikan tanda risiko jatuh pada gelang identitas pasien	",
            skor : "Ya"
          },
          label : "Ya",
          isLoop:true
        },
      ]
    },
    {
      label : "2",
      children :[
        {
          label : "2",
          type : "label",
        },
        {
          label : "Memasang tanda peringatan risiko jatuh, berupa tanda kuning yang dipasang pada bed dekat kaki pasien",
          type : "label",
          isLoop : false,
        },
        {
          model : "memasangTandaResikoJatuh",
          type : "checkbox",
          value : {
            label : "Memasang tanda peringatan risiko jatuh, berupa tanda kuning yang dipasang pada bed dekat kaki pasien	",
            skor : "Ya"
          },
          label : "Ya",
          isLoop:true
        },
      ]
    },
    {
      label : "3",
      children :[
        {
          label : "3",
          type : "label",
        },
        {
          label : "Orientasikan lingkungan kamar dan fasilitas dengan jelas pada pasien dan keluarga",
          type : "label",
          isLoop : false,
        },
        {
          model : "orientasiLingkunganKamar",
          type : "checkbox",
          value : {
            label : "Orientasikan lingkungan kamar dan fasilitas dengan jelas pada pasien dan keluarga",
            skor : "Ya"
          },
          label : "Ya",
          isLoop:true
        },
      ]
    },
    {
      label : "4",
      children :[
        {
          label : "4",
          type : "label",
        },
        {
          label : "Jelaskan cara penggunaan bel di kamar dan di kamar mandi",
          type : "label",
          isLoop : false,
        },
        {
          model : "jelaskanCaraPenggunaanBel",
          type : "checkbox",
          value : {
            label : "Jelaskan cara penggunaan bel di kamar dan di kamar mandi",
            skor : "Ya"
          },
          label : "Ya",
          isLoop:true
        },
      ]
    },
    {
      label : "5",
      children :[
        {
          label : "5",
          type : "label",
        },
        {
          label : "Cek penerangan (lampu) di kamar pasien",
          type : "label",
          isLoop : false,
        },
        {
          model : "caraPenerangan",
          type : "checkbox",
          value : {
            label : "Cek penerangan (lampu) di kamar pasien",
            skor : "Ya"
          },
          label : "Ya",
          isLoop:true
        },
      ]
    },
    {
      label : "6",
      children :[
        {
          label : "6",
          type : "label",
        },
        {
          label : "Cek tingkat kesadaran dan gangguan keseimbangan tiap hari",
          type : "label",
          isLoop : false,
        },
        {
          model : "cekTingkatKesadaran",
          type : "checkbox",
          value : {
            label : "Cek tingkat kesadaran dan gangguan keseimbangan tiap hari",
            skor : "Ya"
          },
          label : "Ya",
          isLoop:true
        },
      ]
    },
    {
      label : "7",
      children :[
        {
          label : "7",
          type : "label",
        },
        {
          label : "Atur tempat tidur pasien selalu dalam posisi rendah,pastikan rem tempat tidur berfungsi dengan baik",
          type : "label",
          isLoop : false,
        },
        {
          model : "aturTempatTidur",
          type : "checkbox",
          value : {
            label : "Atur tempat tidur pasien selalu dalam posisi rendah,pastikan rem tempat tidur berfungsi dengan baik",
            skor : "Ya"
          },
          label : "Ya",
          isLoop:true
        },
      ]
    },
    {
      label : "8",
      children :[
        {
          label : "8",
          type : "label",
        },
        {
          label : "Pagar tempat tidur (bed rail) agar selalu terpasang",
          type : "label",
          isLoop : false,
        },
        {
          model : "pagarTempatTidur",
          type : "checkbox",
          value : {
            label : "Pagar tempat tidur (bed rail) agar selalu terpasang",
            skor : "Ya"
          },
          label : "Ya",
          isLoop:true
        },
      ]
    },
    {
      label : "9",
      children :[
        {
          label : "9",
          type : "label",
        },
        {
          label : "Tempatkan barang yang selalu digunakan dekat dengan jangkauan pasien, instruksikan untuk memanggil bantuan bila perlu",
          type : "label",
          isLoop : false,
        },
        {
          model : "tempatBarangSelaluDigunakan",
          type : "checkbox",
          value : {
            label : "Tempatkan barang yang selalu digunakan dekat dengan jangkauan pasien, instruksikan untuk memanggil bantuan bila perlu",
            skor : "Ya"
          },
          label : "Ya",
          isLoop:true
        },
      ]
    },
    {
      label : "10",
      children :[
        {
          label : "10",
          type : "label",
        },
        {
          label : "Perhatikan/ amankan barang-barang yang ada di lantai ruang perawatan pasien",
          type : "label",
          isLoop : false,
        },
        {
          model : "perhatikanAmankanBarangBarang",
          type : "checkbox",
          value : {
            label : "Perhatikan/ amankan barang-barang yang ada di lantai ruang perawatan pasien",
            skor : "Ya"
          },
          label : "Ya",
          isLoop:true
        },
      ]
    },
    {
      label : "11",
      children :[
        {
          label : "11",
          type : "label",
        },
        {
          label : "Selalu kunci tempat tidur/ kursi roda/ brankar yang digunakan pasien",
          type : "label",
          isLoop : false,
        },
        {
          model : "selaluKunciTempat",
          type : "checkbox",
          value : {
            label : "Selalu kunci tempat tidur/ kursi roda/ brankar yang digunakan pasien",
            skor : "Ya"
          },
          label : "Ya",
          isLoop:true
        },
      ]
    },
    {
      label : "12",
      children :[
        {
          label : "12",
          type : "label",
        },
        {
          label : "Leaflet EDUKASI RISIKO JATUH diberikan setelah pasien dan keluarga diberikan edukasi tentang cara pencegahan pasien jatuh.	",
          type : "label",
          isLoop : false,
        },
        {
          model : "leafletEDUKASI",
          type : "checkbox",
          value : {
            label : "Leaflet EDUKASI RISIKO JATUH diberikan setelah pasien dan keluarga diberikan edukasi tentang cara pencegahan pasien jatuh.	",
            skor : "Ya"
          },
          label : "Ya",
          isLoop:true
        },
      ]
    },
    {
      label : "13",
      children :[
        {
          label : "13",
          type : "label",
        },
        {
          label : "Leaflet EDUKASI RISIKO JATUH harus berada pada rak leaflet di ruang perawata",
          type : "label",
          isLoop : false,
        },
        {
          model : "leafletEDUKASINBeradaDalamRak",
          type : "checkbox",
          value : {
            label : "Leaflet EDUKASI RISIKO JATUH harus berada pada rak leaflet di ruang perawata",
            skor : "Ya"
          },
          label : "Ya",
          isLoop:true
        },
      ]
    },
    {
      label : "14",
      children :[
        {
          label : "14",
          type : "label",
        },
        {
          label : "Ingatkan/ overkan kepada seluruh petugas tentang pasien yang berisiko jatuh",
          type : "label",
          isLoop : false,
        },
        {
          model : "leafletEDUKASINBeradaDalamRak",
          type : "checkbox",
          value : {
            label : "Ingatkan/ overkan kepada seluruh petugas tentang pasien yang berisiko jatuh",
            skor : "Ya"
          },
          label : "Ya",
          isLoop:true
        },
      ]
    },
    {
      label : "15",
      children :[
        {
          label : "15",
          type : "label",
        },
        {
          label : "Anjurkan keluarga untuk berpartisipasi dalam perawatan pasien",
          type : "label",
          isLoop : false,
        },
        {
          model : "anjuranKeluargaUntukBerpartisipasi",
          type : "checkbox",
          value : {
            label : "Anjurkan keluarga untuk berpartisipasi dalam perawatan pasien",
            skor : "Ya"
          },
          label : "Ya",
          isLoop:true
        },
      ]
    },
    {
      label : "16",
      children :[
        {
          label : "16",
          type : "label",
        },
        {
          label : "Petugas menjawab panggilan/ bel pasien dengan segera",
          type : "label",
          isLoop : false,
        },
        {
          model : "petugasMenjawabPanggilan",
          type : "checkbox",
          value : {
            label : "Petugas menjawab panggilan/ bel pasien dengan segera",
            skor : "Ya"
          },
          label : "Ya",
          isLoop:true
        },
      ]
    },
    {
      label : "17",
      children :[
        {
          label : "17",
          type : "label",
        },
        {
          label : "Memasangkan tanda peringatan risiko jatuh, berupa tanda kuning yang di pasang pada tempa tidu rdekat kaki pasien.",
          type : "label",
          isLoop : false,
        },
        {
          model : "memasangTandaPeringatan",
          type : "checkbox",
          value : {
            label : "Memasangkan tanda peringatan risiko jatuh, berupa tanda kuning yang di pasang pada tempa tidu rdekat kaki pasien.",
            skor : "Ya"
          },
          label : "Ya",
          isLoop:true
        },
      ]
    },
    {
      label : "18",
      children :[
        {
          label : "18",
          type : "label",
        },
        {
          label : "Monitor penggunaan dan efek IV. Terapi dan obat-obat pasien",
          type : "label",
          isLoop : false,
        },
        {
          model : "monitoringPengguna",
          type : "checkbox",
          value : {
            label : "Monitor penggunaan dan efek IV. Terapi dan obat-obat pasien",
            skor : "Ya"
          },
          label : "Ya",
          isLoop:true
        },
      ]
    },
    {
      label : "19",
      children :[
        {
          label : "19",
          type : "label",
        },
        {
          label : "Peninjauan obat-obat yang digunakan",
          type : "label",
          isLoop : false,
        },
        {
          model : "peninjauanObatObat",
          type : "checkbox",
          value : {
            label : "Peninjauan obat-obat yang digunakan",
            skor : "Ya"
          },
          label : "Ya",
          isLoop:true
        },
      ]
    },
    {
      label : "20",
      children :[
        {
          label : "20",
          type : "label",
        },
        {
          label : "Obat-obatan untuk proteks tulang : pertimbangkan suplemen vitamin D dankalsium	",
          type : "label",
          isLoop : false,
        },
        {
          model : "obatObatanUntukProteks",
          type : "checkbox",
          value : {
            label : "Obat-obatan untuk proteks tulang : pertimbangkan suplemen vitamin D dankalsium	",
            skor : "Ya"
          },
          label : "Ya",
          isLoop:true
        },
      ]
    },
    {
      label : "21",
      children :[
        {
          label : "21",
          type : "label",
        },
        {
          label : "Pastikan pasien menggunakan kaca mata dan alat bantu dengar bila diperlukan",
          type : "label",
          isLoop : false,
        },
        {
          model : "pastikanPasienMenggunakanKacaMata",
          type : "checkbox",
          value : {
            label : "Pastikan pasien menggunakan kaca mata dan alat bantu dengar bila diperlukan",
            skor : "Ya"
          },
          label : "Ya",
          isLoop:true
        },
      ]
    },
    {
      label : "22",
      children :[
        {
          label : "22",
          type : "label",
        },
        {
          label : "Tempatkan pasien pada kamar yang dekat dengan nursing station",
          type : "label",
          isLoop : false,
        },
        {
          model : "tempatkanPasienPada",
          type : "checkbox",
          value : {
            label : "Tempatkan pasien pada kamar yang dekat dengan nursing station",
            skor : "Ya"
          },
          label : "Ya",
          isLoop:true
        },
      ]
    },
  ]

}
export function STANDAR_RESIKO_SEDANG(): any {
  return [
    {
      label : "1",
      children :[
        {
          label : "1",
          type : "label",
        },
        {
          label : "Semua hal diatas di tambahkan (semua hal di atas harus dilakukan)",
          type : "label",
          isLoop : false,
        },
        {
          model : "semuaHalDiatasLilakukanSedang",
          type : "checkbox",
          value : {
            label : "Semua hal diatas di tambahkan (semua hal di atas harus dilakukan)",
            skor : "Ya"
          },
          label : "Ya",
          isLoop:true
        },
      ]
    },
    {
      label : "2",
      children :[
        {
          label : "2",
          type : "label",
        },
        {
          label : "Rujuk ke fisioterapi atau okupasional terapi untuk assesmen lebih lanjut",
          type : "label",
          isLoop : false,
        },
        {
          model : "rujukanFisiotrapiSedang",
          type : "checkbox",
          value : {
            label : "Rujuk ke fisioterapi atau okupasional terapi untuk assesmen lebih lanjut",
            skor : "Ya"
          },
          label : "Ya",
          isLoop:true
        },
      ]
    },
    {
      label : "3",
      children :[
        {
          label : "3",
          type : "label",
        },
        {
          label : "Bertanya secara berkala akan kebutuhan toileting pasien",
          type : "label",
          isLoop : false,
        },
        {
          model : "rujukanFisiotrapiSedang",
          type : "checkbox",
          value : {
            label : "Bertanya secara berkala akan kebutuhan toileting pasien",
            skor : "Ya"
          },
          label : "Ya",
          isLoop:true
        },
      ]
    },
    {
      label : "4",
      children :[
        {
          label : "4",
          type : "label",
        },
        {
          label : "Awasi pasien saat mobilisasi dan saat menggunakan kamar mandi",
          type : "label",
          isLoop : false,
        },
        {
          model : "rujukanFisiotrapiSedang",
          type : "checkbox",
          value : {
            label : "Awasi pasien saat mobilisasi dan saat menggunakan kamar mandi",
            skor : "Ya"
          },
          label : "Ya",
          isLoop:true
        },
      ]
    },
    {
      label : "5",
      children :[
        {
          label : "5",
          type : "label",
        },
        {
          label : "Pantau nutrisi dan hidrasi pasien",
          type : "label",
          isLoop : false,
        },
        {
          model : "pantauanNutrisiSedang",
          type : "checkbox",
          value : {
            label : "Pantau nutrisi dan hidrasi pasien",
            skor : "Ya"
          },
          label : "Ya",
          isLoop:true
        },
      ]
    },
  ]
}
export function STANDAR_RESIKO_TINGGI(): any {
  return [
    {
      label : "1",
      children :[
        {
          label : "1",
          type : "label",
        },
        {
          label : "Semua hal diatas di tambahkan ( semua hal di atas harus dilakukan )",
          type : "label",
          isLoop : false,
        },
        {
          model : "semuaHalDiatasTinggi",
          type : "checkbox",
          value : {
            label : "Semua hal diatas di tambahkan ( semua hal di atas harus dilakukan )",
            skor : "Ya"
          },
          label : "Ya",
          isLoop:true
        },
      ]
    },
    {
      label : "2",
      children :[
        {
          label : "2",
          type : "label",
        },
        {
          label : "Jangan tinggalkan pasien tanpa pengawasa saat dirawat",
          type : "label",
          isLoop : false,
        },
        {
          model : "janganTinggalkanPasien",
          type : "checkbox",
          value : {
            label : "Jangan tinggalkan pasien tanpa pengawasa saat dirawat",
            skor : "Ya"
          },
          label : "Ya",
          isLoop:true
        },
      ]
    },
    {
      label : "3",
      children :[
        {
          label : "3",
          type : "label",
        },
        {
          label : "Pastikan observasi konstan terutama jika pasien delirium",
          type : "label",
          isLoop : false,
        },
        {
          model : "janganTinggalkanPasien",
          type : "checkbox",
          value : {
            label : "Pastikan observasi konstan terutama jika pasien delirium",
            skor : "Ya"
          },
          label : "Ya",
          isLoop:true
        },
      ]
    },
    {
      label : "4",
      children :[
        {
          label : "4",
          type : "label",
        },
        {
          label : "Pertimbangkan penggunaan protektor panggul",
          type : "label",
          isLoop : false,
        },
        {
          model : "pertimbangkanPenggunaanProtektok",
          type : "checkbox",
          value : {
            label : "Pertimbangkan penggunaan protektor panggul",
            skor : "Ya"
          },
          label : "Ya",
          isLoop:true
        },
      ]
    },
  ]
}
