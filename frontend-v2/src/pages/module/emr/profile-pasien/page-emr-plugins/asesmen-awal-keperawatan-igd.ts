export function detailSkriningNutrisi(): any {
    return [
        {
            child: [
                { type: "text", colspan: "2", caption: "Apakah pasien mengalami penurunan berat badan yang tidak direncanakan selama 6 bulan terakhir?", style: "background-color:lightgray" }
            ]
        },
        {
            child: [
                { type: "checkbox", caption: "Tidak" },
                { type: "nilai", caption: "0" }
            ]
        },
        {
            child: [
                { type: "checkbox", caption: "Tidak yakin (ada tanda: baju menjadi lebih longgar)" },
                { type: "nilai", caption: "2" }
            ]
        },
        {
            child: [
                { type: "checkbox", caption: "Ya, bila ya berapa penurunan berat badan" },
                { type: "nilai", caption: "" }
            ]
        },
        {
            child: [
                { type: "checkbox", caption: "1-5Kg" },
                { type: "nilai", caption: "1" }
            ]
        },
        {
            child: [
                { type: "checkbox", caption: "6-10Kg" },
                { type: "nilai", caption: "2" }
            ]
        },
        {
            child: [
                { type: "checkbox", caption: "11-15Kg" },
                { type: "nilai", caption: "3" }
            ]
        },
        {
            child: [
                { type: "checkbox", caption: ">15Kg" },
                { type: "nilai", caption: "4" }
            ]
        },
        {
            child: [
                { type: "text", colspan: "2", caption: "Apakah terjadi penurunan nafsu makan?", style: "background-color:lightgray" }
            ]
        },
        {
            child: [
                { type: "checkbox", caption: "Ya" },
                { type: "nilai", caption: "1" }
            ]
        },
        {
            child: [
                { type: "checkbox", caption: "Tidak" },
                { type: "nilai", caption: "0" }
            ]
        },
        {
            child: [
                { type: "text", caption: "Total Skor : " },
                { type: "textbox" }
            ]
        },
    ]
}

export function detailStatusFungsional(): any {
    return [
        {
            child: [
                { type: "text", caption: "Mengontrol BAB" },
                { type: "checkbox", caption: "Inkontinen/ tidak teratur (perlu enema)", value:"0" },
                { type: "checkbox", caption: "Kadang inkontinen (1x seminggu)", value:"1" },
                { type: "checkbox", caption: "Kontinen teratur", value:"2" },
                { type: "textbox", value:"3" },
                { type: "textbox", for:"skor" },
            ]
        },
        {
            child: [
                { type: "text", caption: "Mengontrol BAK" },
                { type: "checkbox", caption: "Inkontinen/ pakai kateter dan tidak terkontrol", value:"0" },
                { type: "checkbox", caption: "Kadang inkontinen (max 1x24 jam)", value:"1" },
                { type: "checkbox", caption: "Mandiri", value:"2" },
                { type: "textbox", value:"3" },
                { type: "textbox", for:"skor" },
            ]
        },
        {
            child: [
                { type: "text", caption: "Membersihkan diri (lap muka, sisir rambut, sikat gigi)"},
                { type: "checkbox", caption: "Butuh pertolongan orang lain", value:"0" },
                { type: "checkbox", caption: "Mandiri", value:"1" },
                { type: "textbox", value:"2" },
                { type: "textbox", value:"3" },
                { type: "textbox", for:"skor" },
            ]
        },
        {
            child: [
                { type: "text", caption: "Penggunaan toilet, pergi ke dalam dari WC (melepas, memakai, celana, menyeka, menyiram)" },
                { type: "checkbox", caption: "Tergantung pertolongan orang lain", value:"0" },
                { type: "checkbox", caption: "Perlu pertolongan pada beberapa aktivitas terapi dan dapat mengerjakan sendiri beberapa aktivitas lain", value:"1" },
                { type: "textbox", value:"2" },
                { type: "textbox", value:"3" },
                { type: "textbox", for:"skor" },
            ]
        },
        {
            child: [
                { type: "text", caption: "Makan" },
                { type: "checkbox", caption: "Tidak Mampu", value:"0" },
                { type: "checkbox", caption: "Perlu seseorang menolong memotong makanan", value:"1" },
                { type: "checkbox", caption: "Mandiri", value:"2" },
                { type: "textbox", value:"3" },
                { type: "textbox", for:"skor" },
            ]
        },
        {
            child: [
                { type: "text", caption: "Berpindah tempat dari tidur ke duduk" },
                { type: "checkbox", caption: "Tidak Mampu", value:"0" },
                { type: "checkbox", caption: "Perlu banyak bantuan untuk bisa duduk (2 orang)", value:"1" },
                { type: "checkbox", caption: "Bantuan 1 orang", value:"2" },
                { type: "checkbox", caption: "Mandiri", value:"3" },
                { type: "textbox", for:"skor" },
            ]
        },
        {
            child: [
                { type: "text", caption: "Mobilisasi/berjalan" },
                { type: "checkbox", caption: "Tidak Mampu", value:"0" },
                { type: "checkbox", caption: "Dengan kursi roda", value:"1" },
                { type: "checkbox", caption: "Bantuan 1 orang", value:"2" },
                { type: "checkbox", caption: "Mandiri", value:"3" },
                { type: "textbox", for:"skor" },
            ]
        },
        {
            child: [
                { type: "text", caption: "Berpakaian (memakai baju)" },
                { type: "checkbox", caption: "Tergantung pertolongan orang lain", value:"0" },
                { type: "checkbox", caption: "Sebagian dibantu (misal mengancing baju)", value:"1" },
                { type: "checkbox", caption: "Mandiri", value:"2" },
                { type: "textbox", value:"3" },
                { type: "textbox", for:"skor" },
            ]
        },
        {
            child: [
                { type: "text", caption: "Naik turun tangga" },
                { type: "checkbox", caption: "Tidak mampu", value:"0" },
                { type: "checkbox", caption: "Butuh pertolongan", value:"1" },
                { type: "checkbox", caption: "Mandiri", value:"2" },
                { type: "textbox", value:"3" },
                { type: "textbox", for:"skor" },
            ]
        },
        {
            child: [
                { type: "text", caption: "Mandi" },
                { type: "checkbox", caption: "Tergantung orang lain", value:"0" },
                { type: "checkbox", caption: "Mandiri", value:"1" },
                { type: "textbox", value:"2" },
                { type: "textbox", value:"3" },
                { type: "textbox", for:"skor" },
            ]
        }
    ]
}

export function statusFungsional():any{
    return{
      statusFungsional:[
        {
          fungsi:"Mengontrol BAB",
          detail:[
            {
              type:"checkbox",
              caption:"Inkontinen/ tidak teratur (perlu enema)",
              value:"0"
            },
            {
              type:"checkbox",
              caption:"Kadang inkontinen (1x seminggu)",
              value:"1"
            },
            {
              type:"checkbox",
              caption:"Kontinen teratur",
              value:"2"
            },
            {
              type:"textbox",
              caption:"Ket",
              value:"3"
            },
            {
              type:"skor",
              caption:"Skor",
            }
          ]
        },
        {
          fungsi:"Mengontrol BAK",
          detail:[
            {
              type:"checkbox",
              caption:"Inkontinen/ pakai kateter dan tidak terkontrol",
              value:"0"
            },
            {
              type:"checkbox",
              caption:"Kadang inkontinen (max 1x 24jam)",
              value:"1"
            },
            {
              type:"checkbox",
              caption:"Mandiri",
              value:"2"
            },
            {
              type:"textbox",
              caption:"Ket",
              value:"3"
            },
            {
              type:"skor",
              caption:"Skor",
            }
          ]
        },
        {
          fungsi:"Membersihkan diri(lap muka, sisir rambut, sikat gigi",
          detail:[
            {
              type:"checkbox",
              caption:"Butuh pertolongan orang lain",
              value:"0"
            },
            {
              type:"checkbox",
              caption:"Mandiri",
              value:"1"
            },
            {
              type:"textbox",
              caption:"Ket",
              value:"2"
            },
            {
              type:"textbox",
              caption:"ket",
              value:"3"
            },
            {
              type:"skor",
              caption:"Skor",
            }
          ]
        },
        {
          fungsi:"Penggunaan toilet, pergi ke dalam dari WC(melepas, memakai, celana, menyeka, menyiram)",
          detail:[
            {
              type:"checkbox",
              caption:"Tergantung pertolongan orang lain",
              value:"0"
            },
            {
              type:"checkbox",
              caption:"Perlu pertolongan pada beberapa aktivitas terapi dan dapat mengerjakan sendiri beberapa aktivitas lain",
              value:"1"
            },
            {
              type:"textbox",
              caption:"Ket",
              value:"2"
            },
            {
              type:"textbox",
              caption:"Ket",
              value:"3"
            },
            {
              type:"skor",
              caption:"Skor",
            }
          ]
        },
        {
          fungsi:"Makan",
          detail:[
            {
              type:"checkbox",
              caption:"Tidak Mampu",
              value:"0"
            },
            {
              type:"checkbox",
              caption:"Perlu seseorang menolong memotong makanan",
              value:"1"
            },
            {
              type:"checkbox",
              caption:"Mandiri",
              value:"2"
            },
            {
              type:"textbox",
              caption:"Ket",
              value:"3"
            },
            {
              type:"skor",
              caption:"Skor",
            }
          ]
        },
        {
          fungsi:"berpindah tempat dari tidur ke duduk",
          detail:[
            {
              type:"checkbox",
              caption:"Tidak Mampu",
              value:"0"
            },
            {
              type:"checkbox",
              caption:"Perlu banyak bantuan untuk bisa duduk (2 orang)",
              value:"1"
            },
            {
              type:"checkbox",
              caption:"Bantuan 1 orang",
              value:"2"
            },
            {
              type:"checkbox",
              caption:"Mandiri",
              value:"3"
            },
            {
              type:"skor",
              caption:"Skor",
            }
          ]
        },
        {
          fungsi:"Mobilisasi/berjalan",
          detail:[
            {
              type:"checkbox",
              caption:"Tidak Mampu",
              value:"0"
            },
            {
              type:"checkbox",
              caption:"Dengan kursi roda",
              value:"1"
            },
            {
              type:"checkbox",
              caption:"Bantuan Satu orang",
              value:"2"
            },
            {
              type:"checkbox",
              caption:"Mandiri",
              value:"3"
            },
            {
              type:"skor",
              caption:"Skor",
            },

          ]
        },
        {
          fungsi:"berpakaian",
          detail:[
            {
              type:"checkbox",
              caption:"Tergantung orang lain",
              value:"0"
            },
            {
              type:"checkbox",
              caption:"Sebagian dibantu misal mengancing baju",
              value:"1"
            },
            {
              type:"checkbox",
              caption:"Mandiri",
              value:"2"
            },
            {
              type:"textbox",
              caption:"Ket",
              value:"3"
            },
            {
              type:"skor",
              caption:"Skor",
            }
          ]
        },
        {
          fungsi:"naik turun tangga",
          detail:[
            {
              type:"checkbox",
              caption:"Tidak Mampu",
              value:"0"
            },
            {
              type:"checkbox",
              caption:"Butuh pertolongan",
              value:"1"
            },
            {
              type:"checkbox",
              caption:"Mandiri",
              value:"2"
            },
            {
              type:"textbox",
              caption:"Ket",
              value:"3"
            },
            {
              type:"skor",
              caption:"Skor",
            }
          ]
        },
        {
          fungsi:"Mandi",
          detail:[
            {
              type:"checkbox",
              caption:"Tidak Mampu",
              value:"0"
            },
            {
              type:"checkbox",
              caption:"Tegantung orang lain",
              value:"1"
            },
            {
              type:"checkbox",
              caption:"Mandiri",
              value:"2"
            },
            {
              type:"textbox",
              caption:"Ket",
              value:"3"
            },
            {
              type:"skor",
              caption:"Skor",
            }
          ]
        }
      ]
    }
}

export function detailDiagnosisKeperawatan(): any {
    return [
        {
            child: [
                { type: "checkbox", caption: "Bersihan jalan napas tidak efektif (D.0001) b.d spasme jalan napas, adanya benda asing pada jalan napas, respon alergi, adanya jalan napas buatan" },
                { type: "checkbox", caption: "Risiko aspirasi (D.0006) d.d faktor risiko penurunan tingkat kesadaran, penurunan refleks muntah dan/batuk" }
            ]
        },
        {
            child: [
                { type: "checkbox", caption: "Pola napas tidak efektif (D.0005) b.d gangguan neurologis, cedera medulla spinalis, kerusakan inervasi diafragma" },
                { type: "checkbox", caption: "Pola napas tidak efektif (D.0005) b.d gangguan neurologis, cedera medulla spinalis, kerusakan inervasi diafragma" }
            ]
        },
        {
            child: [
                { type: "checkbox", caption: "Gangguan ventilasi spontan (D.0004) b/d gangguan metabolisme, kelelahan otot pernapasan" },
                { type: "checkbox", caption: "Gangguan pertukaran gas (D.0003) b.d ketidakseimbangan ventilasi-perfusi, perubahan membrane alveolus" }
            ]
        },
        {
            child: [
                { type: "checkbox", caption: "Gangguan sirkulasi spontan (D.0007) b.d penurunan fungsi ventrikel, abnormalitas struktur jantung" },
                { type: "checkbox", caption: "Perfusi jaringan tidak efektif (D.0009) b.d kekurangan volume cairan, penurunan aliran arteri dan/atau vena, peningkatan tekanan darah, hiperglikemia, penurunan konsentrasi hemoglobin" },

            ]
        },
        {
            child: [
                { type: "checkbox", caption: "Penurunan curah jantung (D.0008) b.d. perubahan irama jantung, perubahan frekuensi jantung, perubahan kontraktilitas, perubahan preload, perubahan afterload." },
                { type: "checkbox", caption: "Risiko perdarahan (D.0012) d.d faktor risiko trauma, tindakan pembedahan, aneurisma, gangguan gastrointestinal, proses keganasan" },

            ]
        },
        {
            child: [
                { type: "checkbox", caption: "Diare (D.0020) b.d. proses infeksi, inflamasi gastrointestinal, iritasi gastrointestinal, malabsorpsi" },
                { type: "checkbox", caption: "Hipovolumia (D.0023) b.d kehilangan cairan aktif, kegagalan mekanisme regulasi, peningkatan permeabilitas kapiler, kekurangan intake cairan, evaporasi" },

            ]
        },
        {
            child: [
                { type: "checkbox", caption: "Retensi urin (D.0050) b.d. peningkatan tekanan uretra, kerusakan arkus refleks, blok sfingter, disfungsi neurologis, efek agen farmakologis" },
                { type: "checkbox", caption: "Konstipasi (D.0050) b.d penurunan motilitas gastrointestinal, ketidakcukupan diet, ketidakcukupan asupan serat, ketidakcukupan asupan cairan, aganglionik, kelemahan otot abdomen" },
            ]
        },
        {
            child: [
                { type: "checkbox", caption: "Nyeri akut (D.0077) b.d agen pencedera fisiologis. agen pencedera kimiawi, agen pencedera fisik" },
                { type: "checkbox", caption: "Perilaku kekerasan (D.0132) b.d ketidakmampuan mengendalikan dorongan marah, stimulus lingkungan, konflik interpersonal, perubahan status mental, putus obat, penyalahgunaan zat/alcohol" },
                // { type: "checkboxTB" },
            ]
        },
        {
            child: [
                { type: "checkbox", caption: "Hipertemia (D.0130) b.d proses penyakit, peningkatan laju metabolism, dehidrasi" },
                { type: "checkbox", caption: "Risiko perfusi serebral tidak efektif (D.0017) d.d faktor risiko hipertensi, embolisme, cedera kepala, tumor otak, neoplasma otak, infark miokard akut" },
                // { type: "checkboxTB" },
            ]
        },
        {
            child: [
                { type: "checkbox", caption: "Ketidakstabilan kadar glukosa darah (D.0027) b.d ketidaktepatan pemantauan glukosa darah, kurang patuh pada rencana manajemen diabetes, manajemenm medikasi tidak terkontrol" },
                { type: "checkbox", caption: "Nausea (D.0076) b.d Gangguan biokimiawi (mis. uremia, ketoasidosis diabetik), Gangguan pada esofagus, distensi lambung, Iritasi lambung, Gangguan pancreas, Peregangan kapsul limpa, peningkatan tekanan intraabdominal (mis. keganasan intraabdomen), Peningkatan tekanan intracranial, Peningkatan tekanan intraorbital (mis. glaukoma), Kehamilan, Faktor psikologis (mis. kecemasan, ketakutan, stres), Efek agen farmakologis, Efek toksin" },
                // { type: "checkboxTB" },
            ]
        },
        {
            child: [
                { type: "checkbox", caption: "Gangguan Integritas Kulit/Jaringan (D.0129) b.d Perubahan sirkulasi, Perubahan status nutrisi (kelebihan atau kekurangan), Kelebihan/kekurangan volume cairan, Penurunan mobilitas, Bahan kimia iritatif, Suhu lingkungan yang ekstrem, Faktor mekanis (mis. penekanan pada tonjolan tulang,gesekan), Efek samping terapi radiasi, Kelembaban, Proses penuaan, Neuropati perifer, Perubahan pigmentasi, Perubahan hormonal, Kurang terpapar informasi tentang upaya mempertahankan/melindungi integritas jaringan." },
                { type: "checkbox", caption: "Gangguan Mobilitas Fisik (D. 0054) b.d Kerusakan integritas struktur tulang, Perubahan metabolism, Ketidakbugaran fisik, Penurunan kendali otot, Penurunan massa otot, Penurunan kekuatan otot, Keterlambatan perkembangan, Kekakuan sendi, Kontraktur, Malnutrisi, Gangguan musculoskeletal, Gangguan neuromuscular, Indeks masa tubuh diatas persentil ke-75 sesuai usia, Efek agen farmakologis, Program pembatasan gerak, Nyeri, Kurang terpapar informasi tentang aktivitas fisik, Kecemasan, Gangguan kognitif, Keengganan melakukan pergerakan, Gangguan sensori-persepsi" },
                // { type: "checkboxTB" },
            ]
        },
        {
            child: [
                { type: "checkbox", caption: "Penurunan Kapasitas Adaptif Intrakranial (D.0066) b.d Lesi menempati ruang (mis: space-occupaying lesion – akibat tumor, abses), Gangguan metabolisme (mis: akibat hiponatremia, ensefalofati uremikum, ensefalopati hepatikum, ketoasidosis diabetic, septikemia), Edema serebral (mis: akibat cidera kepala [hematoma epidural, hematoma subdural, hematoma subarachnoid, hematoma intraserebral], stroke iskemik, stroke hemoragik, hipoksia, ensefalopati iskemik, pasca operasi), Peningkatan tekanan vena (mis: akibat thrombosis sinus vena serebral, gagal jantung, thrombosis/obstruksi vena jugularis atau vena kava superior), Obstruksi aliran cairan serebrospinalis, Hipertensi intrakranial idiopatik" },
                { type: "checkbox", caption: "Hipervolemia (D.0022) b.d Gangguan mekanisme regulasi, Kelebihan asupan cairan, Kelebihan asupan natrium, Gangguan aliran balik vena, Efek agen farmakologis (mis: kortikosteroid, chlorpropamide, tolbutamide, vincristine, tryptilinescarbamazepine)." },
                // { type: "checkboxTB" },
            ]
        },
        {
            child: [
                { type: "checkbox", caption: "Ikterik Neonatus (D.0024) b.d Penurunan berat badan abnormal ( > 7 – 8% pada bayi baru lahir yang menyusu ASI, > 15% pada bayi cukup bulan), Pola makan tidak ditetapkan dengan baik, Kesulitan transisi ke kehidupan ekstra uterin, Usia kurang dari 7 hari, Keterlambatan pengeluaran feses (mekonium)" },
                { type: "checkboxTB" },
            ]
        },
        {
          child: [
              { type: "checkbox2", caption: "Ikterik Neonatus (D.0024) b.d Penurunan berat badan abnormal ( > 7 – 8% pada bayi baru lahir yang menyusu ASI, > 15% pada bayi cukup bulan), Pola makan tidak ditetapkan dengan baik, Kesulitan transisi ke kehidupan ekstra uterin, Usia kurang dari 7 hari, Keterlambatan pengeluaran feses (mekonium)" },
              { type: "checkboxTB" },
          ]
      },
    ]
}

export function detailRencanaKeperawatan(): any {
    return [
        {
            child: [
                { type: "checkbox", caption: "Manajemen jalan nafas (I.01011). Monitor bunyi nafas tambahan, teknik head tilt chin lift, jaw thrust, suction kurang dari 15detik, keluarkan sumbatan benda padat dengan forcep magil, kolaborasi pemberian bronkodilator, ekspektoran, mukolitik." },
                { type: "checkbox", caption: "Manajemen jalan napas buatan (I.01012). Monitor posisi selang endotrakeal (ETT). Pasang OPA, cegah ETT terlipat, berikan preoksigenasi 100% selama 30 detik (3-6 kali ventilasi) sebelum dan sesudah pengisapan, lakukan penghisapan lender kurang dari 15 detik jika diperlukan" }
            ]
        },
        {
            child: [
                { type: "checkbox", caption: "Pemantauan respirasi (I.01014). Monitor frekuensi, irama, kedalaman dan upaya nafas, monitor saturasi oksigen." },
                { type: "checkbox", caption: "Terapi oksigen (I.01026). Siapkan dan atur peralatan pemberian oksigen, gunakan perangkat oksigen yang sesuai dengan tingkat mobilitas pasien, kolaborasi penentuan dosis oksigen." }
            ]
        },
        {
            child: [
                { type: "checkbox", caption: "Pencegahan aspirasi (I.01018). Monitor tingkat kesadaran, batuk, muntah, dan kemampuan menelan, monitor bunyi napas, lakukan penghisapan jalan napas jika produksi secret meningkat." },
                { type: "checkbox", caption: "Resusitasi cairan (I.03139). Identifikasi kelas syok untuk estimasi kehilangan darah, monitor status hemodinamik, pasang jalur IV berukuran besar G14 atau G16, kolaborasi penentuan jenis dan jumlah cairan, kolaborasi pemberian produk darah." },
            ]
        },
        {
            child: [
                { type: "checkbox", caption: "Perawatan sirkulasi (I.02079). Periksa sirkulasi perifer, hindari pemasangan infus atau pengambilan darah di area keterbatasan perfusi, hindari pengukuran tekanan darah pada ekstremitas dengan keterbatasan perfusi, lakukan hidrasi." },
                { type: "checkbox", caption: "Pencegahan perdarahan (I.02067). Monitor tanda dan gejala perdarahan, monitor tanda-tanda vital ortostatik, pertahankan bed rest selama perdarahan, batasi Tindakan invasive jika perlu, kolaborasi pemberian obat pengontrol perdarahan, kolaborasi pemberian produk darah jika perlu" },
            ]
        },
        {
            child: [
                { type: "checkbox", caption: "Dukungan ventilasi (I.01002). Identifikasi adamya kelelahan otot bantu nafas, ajarkan teknis nafas dalam, gunakan bag-valve mask." },
                { type: "checkbox", caption: "Perawatan jantung akut (I.020776). Identifikasi karakteristik nyeri dada, monitor EKG 12 sadapan, monitor elektrolit, monitor enzim jantung, monitor saturasi oksigen, identifikasi stratifikasi coroner akut, pertahankan tirah baring minimal 12 jam, pasang akses intra vena, kolaborasi pemberian anti platelet, kolaborasi pemberian anti angina, kolaborasi pemberian morfin, kolaborasi pemberian inotropic, kolaborasi pencegahan thrombus dengan anti koagulan" },
            ]
        },
        {
            child: [
                { type: "checkbox", caption: "Manajemen diare (I.03101). Monitor warna, volume, frekuensi dan konsistensi tinja, monitor tanda dan gejala hypovolemia, monitor jumlah pengeluaran diare, berikan asupan cairan oral, pasang jalur intravena, berikan cairan intravena (mis ringer asetat, ringer laktat, anjurkan melanjutkan pemberian ASI, kolaborasi pemberian obat antimotilitas, kolaborasi pemberian obat pengeras feses." },
                { type: "checkbox", caption: "Manajenen hypovolemia (I.03116). Periksa tanda dan gejala hopovolemia, monitor intake dan output cairan, hitung kebutuhan cairan, berikan posisi mofified Trendelenburg, berikan asupan cairan oral., kolaborasi pemberian cairan IV isotonis (mis NaCl, RL), kolaborasi pemberian IV hipotonis (mis glukosa 2,5%, NaCl 0,4%), kolaborasi pemberian cairan koloid (mis Albumin, Plasmanate), kolaborasi pemberian produk darah." },
            ]
        },
        {
            child: [
                { type: "checkbox", caption: "Kateterisasi urine (I.04148). Periksa kondis pasien, pasang kateter sesuai kewenangan dan prosedur yang benar mulai penyiapan sampai selesai." },
                { type: "checkbox", caption: "Manajemen konstipasi (I.04155). Periksa tanda dan gejala konstipasi, lakukan masase abdomen jika perlu, lakukan evakuasi fese secara manual jika perlu, berikan enema atau irigasi jika perlu, kolaborasi penggunaan obat pencahar jika perlu." },
            ]
        },
        {
            child: [
                { type: "checkbox", caption: "Manajemen nyeri (I.08238). Identifikasi lokasi, karakteristik, durasi, frekuensi, kualitas, intensitas nyeri, identifikasi skala nyeri, beri teknik nonfarmakologi untuk mengurangi rasa nyeri, kolaborasi pemberiananalgetik, monitor efek samping penggunaan analgetic." },
                { type: "checkbox", caption: "Manajemen keselamatan lingkungan (I.14513). Hilangkan bahaya keselamatan lingkungan, sediakan alat bantu keamanan." },
            ]
        },
        {
            child: [
                { type: "checkbox", caption: "Manajemen hipertermia (I.15506). Longgarkan pakaian, berikan cairan oral, kolaborasi pemberian cairan dan elektrolit intravena." },
                { type: "checkbox", caption: "Pencegahan syok (I.02068). Monitor tingkat kesadaran dan respon pupil, monitor status kardiopulmonal, berikan oksigen untuk mempertahankan saturasi >94%, kolaborasi pemberian IV, kolaborasi pemberian anti inflamasi, kolaborasi pemberian transfuse darah." },
            ]
        },
        {
            child: [
                { type: "checkbox", caption: "Manajemen hiperglikemia (I.03115). Monitor kadar glukosa darah, kolaborasi pemberian insulin,ckolaborasi pemberian cairan IV, koaborasi pemberian kalium" },
                { type: "checkbox", caption: "Manajemen hipoglikemia (I.03115). Pertahankan kepatenan jalan nafas, berikan karbohidrat sederhana, kolaborasi dalam pemberian dexstrosa." },
            ]
        },
        {
            child: [
                { type: "checkbox", caption: "Manajemen Hipertermia (I.15506). Identifikasi penyebab hipertermia (mis: dehidrasi, terpapar lingkungan panas, penggunaan inkubator), Monitor suhu tubuh, Berikan cairan oral, kompres hangat, Kolaborasi pemberian cairan dan elektrolit intravena, jika perlu" },
                { type: "checkbox", caption: "Manajemen Peningkatan Tekanan Intrakranial (I.06194). Identifikasi penyebab peningkatan TIK (misalnya: lesi, gangguan metabolism, edema serebral), Monitor tanda/gejala peningkatan TIK (misalnya: tekanan darah meningkat, tekanan nadi melebar, bradikardia, pola napas ireguler, kesadaran menurun), Berikan posisi semi fowler, Hindari manuver valsava, Kolaborasi pemberian pelunak tinja, jika perlu" },
            ]
        },
        {
            child: [
                { type: "checkbox", caption: "Manajemen Mual (I.03117). Identifikasi faktor penyebab mual (mis: pengobatan dan prosedur), Monitor mual (mis: frekuensi, durasi, dan tingkat keparahan), Kurangi atau hilangkan keadaan penyebab mual (mis: kecemasan, ketakutan, kelelahan), Berikan makanan dalam jumlah kecil dan menarik, Ajarkan penggunaan teknik non farmakologis untuk mengatasi mual (mis: biofeedback, hipnosis, relaksasi, terapi musik, akupresur), Kolaborasi pemberian obat antiemetik, jika perlu" },
                { type: "checkbox", caption: "Perawatan Luka (I.14564). Monitor karakteristik luka (mis: drainase, warna, ukuran , bau), Monitor tanda-tanda infeksi, lakukan perawatan luka, Jelaskan tanda dan gejala infeksi, Anjurkan mengkonsumsi makanan tinggi kalori dan protein, Ajarkan prosedur perawatan luka secara mandiri, Kolaborasi prosedur debridement (mis: enzimatik, biologis, mekanis, autolitik), jika perlu, Kolaborasi pemberian antibiotik, jika perlu" },
            ]
        },
        {
            child: [
                { type: "checkbox", caption: "Dukungan Mobilisasi (I.05173). Identifikasi adanya nyeri atau keluhan fisik lainnya, Identifikasi toleransi fisik melakukan pergerakan, Fasilitasi melakukan pergerakan, jika perlu, Fasilitasi melakukan pergerakan, jika perlu" },
                { type: "checkbox", caption: "Pemantauan Tekanan Intrakranial (I.06198). Identifikasi penyebab peningkatan TIK (mis: lesi menempati ruang, gangguan metabolisme, edema serebral, peningkatan tekanan vena, obstruksi cairan serebrospinal, hipertensi intracranial idiopatik), Pertahankan posisi kepala dan leher netral, Atur interval pemantauan sesuai kondisi pasien. Dokumentasikan hasil pemantauan, Jelaskan tujuan dan prosedur pemantauan, Informasikan hasil pemantauan, jika perlu" },
            ]
        },
        {
            child: [
                { type: "checkbox", caption: "Manajemen Hipervolemia (I.03114). Periksa tanda dan gejala hypervolemia (mis: ortopnea, dispnea, edema, JVP/CVP meningkat, refleks hepatojugular positif, suara napas tambahan), Identifikasi penyebab hypervolemia, Monitor status hemodinamik (mis: frekuensi jantung, tekanan darah, MAP, CVP, PAP, PCWP, CO, CI) jika tersedia, Monitor intake dan output cairan, Batasi asupan cairan dan garam, Tinggikan kepala tempat tidur 30 – 40 derajat,, Anjurkan melapor jika haluaran urin < 0,5 mL/kg/jam dalam 6 jam, Ajarkan cara membatasi cairan" },
                { type: "checkbox", caption: "Fototerapi Neonatus (I.03091). Monitor ikterik pada sklera dan kulit bayi, Identifikasi kebutuhan cairan sesuai dengan usia gestasi dan berat badan, berikan fototerapi sesuai indikasi, Anjurkan ibu menyusui sekitar 20 – 30 menit, Anjurkan ibu menyusui sesering mungkin" },
            ]
        },
        {
          child: [
              { type: "checkbox2", caption: "Manajemen Hipervolemia (I.03114). Periksa tanda dan gejala hypervolemia (mis: ortopnea, dispnea, edema, JVP/CVP meningkat, refleks hepatojugular positif, suara napas tambahan), Identifikasi penyebab hypervolemia, Monitor status hemodinamik (mis: frekuensi jantung, tekanan darah, MAP, CVP, PAP, PCWP, CO, CI) jika tersedia, Monitor intake dan output cairan, Batasi asupan cairan dan garam, Tinggikan kepala tempat tidur 30 – 40 derajat,, Anjurkan melapor jika haluaran urin < 0,5 mL/kg/jam dalam 6 jam, Ajarkan cara membatasi cairan" },
          ]
        },
        {
            child: [
                { type: "", caption: "" },
                { type: "checkboxTB" },
            ]
        },

    ]
}
