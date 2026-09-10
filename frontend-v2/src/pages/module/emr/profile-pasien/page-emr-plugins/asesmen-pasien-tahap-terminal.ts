export function table1(): any {
    return [
      {
        "title": "",
        "value": [
            {
                label: "a. Kegawatan pernafasan",
                type: "label",
                column: 12,
            },
            {
                label: "Pola Nafas :",
                type: "label",
                column: 2,
            },
            {
                subTitle: "Nafas tidak teratur",
                type: "checkbox",
                model: "nafasTidakTeratur1",
                column: 4,
            },
            {
                subTitle: "Penggunaan retraksi otot nafas",
                type: "checkbox",
                model: "penggunaanRetraksiOtotNafas1",
                column: 6,
            },
            {
                label: "Jenis :",
                type: "label",
                column: 2,
            },
            {
                subTitle: "Dispnoe",
                type: "checkbox",
                model: "dispnoe1",
                column: 2,
            },
            {
                subTitle: "Kusmaul",
                type: "checkbox",
                model: "kusmaul1",
                column: 2,
            },
            {
                subTitle: "Cyne Strok",
                type: "checkbox",
                model: "cyneStrok1",
                column: 2,
            },
            {
                subTitle: "Lainnya",
                type: "checkboxText",
                model: "lainnya1",
                model2: "lainnyaText1",
                column: 4,
                column1: 5,
                column2: 7,
            },
            {
                label: "Suara nafas :",
                type: "label",
                column: 2,
            },
            {
                subTitle: "Vesikuler",
                type: "checkbox",
                model: "vesikuler1",
                column: 2,
            },
            {
                subTitle: "Stridor",
                type: "checkbox",
                model: "stridor1",
                column: 2,
            },
            {
                subTitle: "Wheezing",
                type: "checkbox",
                model: "wheezing1",
                column: 2,
            },
            {
                subTitle: "Ronchi",
                type: "checkbox",
                model: "ronchi1",
                column: 2,
            },
            {
                type: "kosong",
                column: 2,
            },
            {
                label: "Sesak nafas :",
                type: "label",
                column: 2,
            },
            {
                subTitle: "Ada",
                type: "checkbox",
                model: "ada1",
                column: 2,
            },
            {
                subTitle: "SpO2 < 95%",
                type: "checkbox",
                model: "SpO21",
                column: 2,
            },
            {
                subTitle: "Mulut Kering",
                type: "checkbox",
                model: "mulutKering1",
                column: 2,
            },
            {
                type: 'kosong',
                column: 4,
            },
            {
                label: "b. Kehilangan tonus otot",
                type: "label",
                column: 12,
            },
            {
                subTitle: "Sulit menelan",
                type: "checkbox",
                model: "sulitMenelan1",
                column: 3,
            },
            {
                subTitle: "Sulit berbicara",
                type: "checkbox",
                model: "sulitBerbicara1",
                column: 3,
            },
            {
                subTitle: "Mual",
                type: "checkbox",
                model: "mual1",
                column: 3,
            },
            {
                subTitle: "Distensi abdomen",
                type: "checkbox",
                model: "distensiAbdomen1",
                column: 3,
            },
            {
                subTitle: "Inkontinensia urine",
                type: "checkbox",
                model: "inkontinensiaUrine1",
                column: 3,
            },
            {
                subTitle: "Inkontinensia feses",
                type: "checkbox",
                model: "inkontinensiaFeses1",
                column: 3,
            },
            {
                subTitle: "Penurunan gerakan tubuh",
                type: "checkbox",
                model: "penurunanGerakanTubuh1",
                column: 3,
            },
            {
                type: "kosong",
                column: 3,
            },
            {
                label: "c. Nyeri",
                type: "label",
                column: 12,
            },
            {
                label: "Nyeri :",
                type: "label",
                column: 1,
            },
            {
                subTitle: "Tidak",
                type: "checkbox",
                model: "tidakNyeri1",
                column: 2,
            },
            {
                subTitle: "Ada",
                type: "checkbox",
                model: "adaNyeri1",
                column: 2,
            },
            {
                subTitle: "Skala Nyeri NRS :",
                type: "textBox",
                model: "skalaNyeriNRS1",
                column: 3,
                column1: 6,
                column2: 6,
            },
            {
                subTitle: "WBS :",
                type: "textBox",
                model: "skalaNyeriWBS1",
                column: 2,
                column1: 4,
                column2: 8,
            },
            {
                subTitle: "BPS :",
                type: "textBox",
                model: "skalaNyeriBPS1",
                column: 2,
                column1: 4,
                column2: 8,
            },
            {
                label: "d. Perlambatan sirkulasi",
                type: "label",
                column: 12,
            },
            {
                subTitle: "Sianosis pada extremitas",
                type: "checkbox",
                model: "sianosisPadaExtremitas1",
                column: 4,
            },
            {
                subTitle: "Kulit dingin dan berkeringat",
                type: "checkbox",
                model: "kulitDinginDanBerkeringat1",
                column: 4,
            },
            {
                subTitle: "Agitasi - gelisah",
                type: "checkbox",
                model: "agitasiGelisah1",
                column: 4,
            },
            {
                subTitle: "Tekanan darah menurun",
                type: "checkbox",
                model: "tekananDarahMenurun1",
                column: 4,
            },
            {
                subTitle: "Nadi lambat dan lemah",
                type: "checkbox",
                model: "nadiLambatDanLemah1",
                column: 4,
            },
            {
                subTitle: "CRT > 2 dtk",
                type: "checkbox",
                model: "CRT2Dtk1",
                column: 4,
            },
        ]
      },
    ]
  }

export function table2(): any {
    return [
      {
        "title": "",
        "value": [
            {
                subTitle: "Melakukan aktifitas fisik",
                type: "checkbox",
                model: "melakukanAktifitasFisik2",
                column: 3,
            },
            {
                subTitle: "Pemberian posisi",
                type: "checkbox",
                model: "pemberianPosisi2",
                column: 3,
            },
            {
                subTitle: "",
                type: "checkboxText",
                model: "checkboxTextFree2_1",
                model: "checkboxTextFreeText2_1",
                column: 6,
                column1: 1,
                column2: 11,
            },
        ]
      },
    ]
  }

export function table3(): any {
    return [
      {
        "title": "",
        "value": [
            {
                subTitle: "Manajemen airway",
                type: "checkbox",
                model: "manajemenAirway3",
                column: 3,
            },
            {
                label: "Respon Pasien :",
                type: "label",
                column: 2,
            },
            {
                subTitle: "Paten",
                type: "checkbox",
                model: "paten3",
                column: 3,
            },
            {
                subTitle: "Tidak paten",
                type: "checkbox",
                model: "tidakPaten3",
                column: 3,
            },
            {
                type: "kosong",
                column: 1,
            },
            {
                subTitle: "Oksigenasi",
                type: "checkbox",
                model: "oksigenasi3",
                column: 3,
            },
            {
                label: "Respon Pasien :",
                type: "label",
                column: 2,
            },
            {
                subTitle: "Spontan dengan oksigen",
                type: "checkboxText",
                model: "spontanDenganOksigen3",
                model2: "spontanDenganOksigenText3",
                column: 7,
                column1: 5,
                column2: 7,
            },
        ]
      },
    ]
  }

export function table4(): any {
    return [
      {
        "title": "",
        "value": [
            {
                label: "Apakah pasien dan keluarga perlu pelayanan spiritual?",
                type: "label",
                column: 4,
            },
            {
                subTitle: "Tidak",
                type: "checkbox",
                model: "tidak4",
                column: 8,
            },
            {
                type: "kosong",
                column: 4,
            },
            {
                subTitle: "Ya, Jelaskan",
                type: "checkboxText",
                model: "ya4",
                model2: "yaText4",
                column: 8,
                column1: 3,
                column2: 9,
            },
        ]
      },
    ]
  }

export function table5(): any {
    return [
      {
        "title": "",
        "value": [
            {
                label: "Perlu didoakan :",
                type: "label",
                column: 3,
            },
            {
                subTitle: "Tidak",
                type: "checkbox",
                model: "tidak5",
                column: 2,
            },
            {
                subTitle: "Ya, Jelaskan",
                type: "checkboxText",
                model: "ya5",
                model2: "yaText5",
                column: 7,
                column1: 3,
                column2: 9,
            },
            {
                label: "Perlu bimbingan/pendampingan rohani :",
                type: "label",
                column: 3,
            },
            {
                subTitle: "Tidak",
                type: "checkbox",
                model: "tidak5_2",
                column: 2,
            },
            {
                subTitle: "Ya, Jelaskan",
                type: "checkboxText",
                model: "ya5_2",
                model2: "yaText5_2",
                column: 7,
                column1: 3,
                column2: 9,
            },
        ]
      },
    ]
  }

export function table6(): any {
    return [
      {
        "title": "",
        "value": [
            {
                label: "a. Apakah ada orang yang ingin dihubungi saat ini ?",
                type: "label",
                column: 12,
            },
            {
                subTitle: "Tidak",
                type: "checkbox",
                model: "tidak6",
                column: 2,
            },
            {
                subTitle: "Ya, Jelaskan nama :",
                type: "checkboxText",
                model: "ya6",
                model2: "yaText6",
                column: 5,
                column1: 6,
                column2: 6,
            },
            {
                subTitle: "Hubungan dengan pasien :",
                type: "textBox",
                model: "hubunganDenganPasien6",
                column: 5,
                column1: 5,
                column2: 7,
            },
            {
                subTitle: "No telp/hp :",
                type: "textBox",
                model: "noTelpHP6",
                column: 6,
                column1: 3,
                column2: 9,
            },
            {
                subTitle: "Email :",
                type: "textBox",
                model: "email6",
                column: 6,
                column1: 2,
                column2: 10,
            },
            {
                label: "b. Bagaimana rencana perawatan selanjutnya ?",
                type: "label",
                column: 12,
            },
            {
                subTitle: "Tetap dirawat di RSUD Bali Mandara",
                type: "checkbox",
                model: "tetapDirawatDiRSBalimandara6",
                column: 12,
            },
            {
                subTitle: "Dirawat di rumah, alasan",
                type: "checkboxText",
                model: "dirawatDiRumahAlasan6",
                model2: "dirawatDiRumahAlasanText6",
                column: 6,
                column1: 5,
                column2: 7,
            },
            {
                label: "Lingkungan rumah sudah disiapkan ?",
                type: "label",
                column: 3,
            },
            {
                subTitle: "Tidak",
                type: "checkbox",
                model: "tidak6_2",
                column: 2,
            },
            {
                subTitle: "Ya",
                type: "checkbox",
                model: "ya6_2",
                column: 1,
            },
            {
                subTitle: "Yang bertanggung jawab memberi perawatan, nama dan TT :",
                type: "textBoxTTD",
                model: "penanggungJawabPerawatanNama6",
                ttd: "penanggungJawabPerawatanNamaTTD6",
                column: 12,
                column1: 5,
                column2: 4,
                column3: 3,
            },
            {
                label: "c. Reaksi pasien atas penyakitnya",
                type: "label",
                column: 12,
            },
            {
                label: "Bisa dilakukan pengkajian :",
                type: "label",
                column: 3,
            },
            {
                subTitle: "Tidak",
                type: "checkbox",
                model: "tidak6_3",
                column: 9,
            },
            {
                subTitle: "Ya,",
                type: "checkbox",
                model: "ya6_3",
                column: 2,
            },
            {
                subTitle: "Menyangkal",
                type: "checkbox",
                model: "menyangkal6",
                column: 2,
            },
            {
                subTitle: "Takut",
                type: "checkbox",
                model: "takut6",
                column: 2,
            },
            {
                subTitle: "Rasa bersalah",
                type: "checkbox",
                model: "rasaBersalah6",
                column: 3,
            },
            {
                subTitle: "Marah",
                type: "checkbox",
                model: "marah6",
                column: 2,
            },
            {
                subTitle: "Sedih / menangis",
                type: "checkbox",
                model: "sedihMenangis6",
                column: 3,
            },
            {
                subTitle: "Ketidakberdayaan",
                type: "checkbox",
                model: "ketidakberdayaan6",
                column: 3,
            },
            {
                label: "d. Reaksi keluarga atas penyakit pasien",
                type: "label",
                column: 12,
            },
            {
                subTitle: "Marah",
                type: "checkbox",
                model: "marah6_2",
                column: 3,
            },
            {
                subTitle: "Letih / lelah",
                type: "checkbox",
                model: "letihLelah6",
                column: 3,
            },
            {
                subTitle: "Rasa bersalah",
                type: "checkbox",
                model: "rasaBersalah6_2",
                column: 3,
            },
            {
                type: "kosong",
                column: 3,
            },
            {
                subTitle: "Gangguan tidur",
                type: "checkbox",
                model: "gangguanTidur6",
                column: 3,
            },
            {
                subTitle: "Sedih / menangis",
                type: "checkbox",
                model: "sedihMenangis6",
                column: 3,
            },
            {
                subTitle: "Ketidakmampuan memenuhi peran yang diharapkan",
                type: "checkbox",
                model: "ketidakmampuanMemenuhiPeran6",
                column: 6,
            },
            {
                subTitle: "Keluarga kurang berpartisipasi membuat keputusan dalam perawatan pasien",
                type: "checkbox",
                model: "keluargaKurangBerpartisipasi6",
                column: 12,
            },
        ]
      },
    ]
  }

export function table7(): any {
    return [
      {
        "title": "",
        "value": [
            {
                subTitle: "Pasien perlu didampingi keluarga",
                type: "checkbox",
                model: "pasienPerluDiDampingiKeluarga7",
                column: 12,
            },
            {
                subTitle: "Keluarga dapat mengunjungi pasien diluar waktu berkunjung",
                type: "checkbox",
                model: "keluargaDapatMengunjungiPasien7",
                column: 12,
            },
            {
                subTitle: "Teman / sahabat pasien dapat mengunjungi pasien diluar waktu berkunjung",
                type: "checkbox",
                model: "temanSahabatPasienDapatMengunjungi7",
                column: 12,
            },
            {
                subTitle: "",
                type: "checkboxText",
                model: "checkboxFree7",
                model2: "checkboxFreeText7",
                column: 12,
                column1: 1,
                column2: 11,
            },
        ]
      },
    ]
  }

export function table8(): any {
    return [
      {
        "title": "",
        "value": [
            {
                subTitle: "Tidak",
                type: "checkbox",
                model: "tidak8",
                column: 2,
            },
            {
                subTitle: "Ya, jelaskan :",
                type: "checkbox",
                model: "yaJelaskan8",
                column: 2,
            },
            {
                subTitle: "Autopsi",
                type: "checkbox",
                model: "Autopsi8",
                column: 2,
            },
            {
                subTitle: "Donasi organ",
                type: "checkbox",
                model: "donasiOrgan8",
                column: 2,
            },
            {
                subTitle: "Lain-lain :",
                type: "checkboxText",
                model: "lainLain8",
                model2: "lainLainText8",
                column: 4,
                column1: 5,
                column2: 7,
            },
        ]
      },
    ]
  }

export function table9(): any {
    return [
      {
        "title": "",
        "value": [
            {
                subTitle: "Marah",
                type: "checkbox",
                model: "marah9",
                column: 2,
            },
            {
                subTitle: "Depresi",
                type: "checkbox",
                model: "depresi9",
                column: 2,
            },
            {
                subTitle: "Rasa bersalah",
                type: "checkbox",
                model: "rasaBersalah9",
                column: 8,
            },
            {
                subTitle: "Gangguan tidur",
                type: "checkbox",
                model: "gangguanTidur9",
                column: 2,
            },
            {
                subTitle: "Sedih/menangis",
                type: "checkbox",
                model: "sedihMenangis9",
                column: 2,
            },
            {
                subTitle: "Ketidakmampuan memenuhi peran yang diharapkan",
                type: "checkbox",
                model: "ketidakmampuanMemenuhiPeran9",
                column: 8,
            },
            {
                subTitle: "Ketidakmampuan ekonomi",
                type: "checkbox",
                model: "ketidakmampuanEkonomi9",
                column: 12,
            },
        ]
      },
    ]
  }

export function table10(): any {
    return [
      {
        "title": "",
        "value": [
            {
                subTitle: "Koping individu tidak efektif",
                type: "checkbox",
                model: "kopingIndividu10",
                column: 12,
            },
            {
                subTitle: "Ansietas kematian",
                type: "checkbox",
                model: "ansietasKematian10",
                column: 12,
            },
            {
                subTitle: "Perubahan proses keluarga",
                type: "checkbox",
                model: "perubahanProsesKeluarga10",
                column: 12,
            },
            {
                subTitle: "Distress spiritual",
                type: "checkbox",
                model: "distressSpiritual10",
                column: 12,
            },
        ]
      },
    ]
  }