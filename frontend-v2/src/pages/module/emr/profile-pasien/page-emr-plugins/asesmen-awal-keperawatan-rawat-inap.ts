
export function alatBantu(): any {
    return [
        {
            "nama": "Kacamata",
            "model": "alatBantu",
            "type": "checkbox",
        },
        {
            "nama": "Lensa Kontak",
            "model": "alatBantu",
            "type": "checkbox",
        },
        {
            "nama": "Gigi Palsu",
            "model": "alatBantu",
            "type": "checkbox",
        },
        {
            "nama": "Alat Bantu Dengar",
            "model": "alatBantu",
            "type": "checkbox",
        },
        {
            "nama": "Lainnya",
            "model": "alatBantuLainnya",
            "type": "textbox",
        },
    ]
}
export function ttv(): any {
    return [
        {
            "title": "TB",
            "model": "tinggiBdn"
        },
        {
            "title": "BB",
            "model": "bb"
        },
        {
            "title": "TD",
            "model": "td"
        },
        {
            "title": "Nadi",
            "model": "nadi"
        },
        {
            "title": "Nafas",
            "model": "nafas"
        },
        {
            "title": "Suhu",
            "model": "suhu"
        },
        {
            "title": "SpO2",
            "model": "sp02"
        },
    ]
}
export function dataInTable(): any {
    return {
        "pertama": [{
            "no": 1,
            "parameter": "Mata",
            "pengkajian": [
                {
                    "model": "mata",
                    "title": "Terbuka Spontan",
                    "value": {
                        "desc" : "Terbuka Spontan",
                        "poin" : 4
                    }
                },
                {
                    "model": "mata",
                    "title": "Terbuka Saat diperintah/dipanggil",
                    "value": {
                        "desc" : "Terbuka Saat diperintah/dipanggil",
                        "poin" : 3
                    },
                },
                {
                    "model": "mata",
                    "title": "Terbuka terhadap rangsangan nyeri",
                    "value":  {
                        "desc" : "Terbuka terhadap rangsangan nyeri",
                        "poin" : 2
                    },
                },
                {
                    "model": "mata",
                    "title": "Tidak merespon",
                    "value": {
                        "desc" : "Tidak merespon",
                        "poin" : 1
                    }
                },
            ],
            "nilai": [4, 3, 2, 1]
        }],
        "kedua": [{
            "no": 2,
            "parameter": "Verbal",
            "pengkajian": [
                {
                    "model": "verbal",
                    "title": "Orientasi baik",
                    "value": {
                        "desc" : "Orientasi baik",
                        "poin" : 5
                    },
                },
                {
                    "model": "verbal",
                    "title": "Disoreintansi/bingung",
                    "value": {
                        "desc" : "Disoreintansi/bingung",
                        "poin" : 4
                    },
                },
                {
                    "model": "verbal",
                    "title": "Jawaban tidak sesuai",
                    "value": {
                        "desc" : "Jawaban tidak sesuai",
                        "poin" : 3
                    },
                },
                {
                    "model": "verbal",
                    "title": "Suara yang tidak dapat dimengerti (erangan,teriakan)",
                    "value": {
                        "desc" : "Suara yang tidak dapat dimengerti (erangan,teriakan)",
                        "poin" : 2
                    },
                },
                {
                    "model": "verbal",
                    "title": "Tidak Merespon",
                    "value": {
                        "desc" : "Tidak Merespon",
                        "poin" : 1
                    },
                },
            ],
            "nilai": [5, 4, 3, 2, 1]
        }],
        "keTiga": [{
            "no": 3,
            "parameter": "Pergerakan",
            "pengkajian": [
                {
                    "model": "pergerakan",
                    "title": "Mengikuti diperintah",
                    "value": {
                        "desc" : "Mengikuti diperintah",
                        "poin" : 6
                    }
                },
                {
                    "model": "pergerakan",
                    "title": "Melokalisasi nyeri",
                    "value": {
                        "desc" : "Melokalisasi nyeri",
                        "poin" : 5
                    },
                },
                {
                    "model": "pergerakan",
                    "title": "Menarik diri(withdrawn) dari rangsanga nyeri",
                    "value": {
                        "desc" : "Menarik diri(withdrawn) dari rangsanga nyeri",
                        "poin" : 4
                    },
                },
                {
                    "model": "pergerakan",
                    "title": "Fleksi abnormal anggota gerak terhadap rangsang",
                    "value": {
                        "desc" : "Fleksi abnormal anggota gerak terhadap rangsang",
                        "poin" : 3
                    },
                },
                {
                    "model": "pergerakan",
                    "title": "Ekstensi abnormal anggota gerak terhadap rangsang",
                    "value": {
                        "desc" : "Ekstensi abnormal anggota gerak terhadap rangsang",
                        "poin" : 2
                    },
                },
                {
                    "model": "pergerakan",
                    "title": "Tidak merespon",
                    "value": {
                        "desc" : "Tidak merespon",
                        "poin" : 1
                    }   
                },
            ],
            "nilai": [6, 5, 4, 3, 2, 1]
        }]
    }
}

export function imgNyeri(): any {
    return {
        "nama": "Hurts", "detail": [
            {
                "nama": "No Hurt", "descNilai": 0,
                "img": "/images/skalanyeri/1.png"
            },
            {
                "nama": "Hurts Little Bit", "descNilai": 2,
                "img": "/images/skalanyeri/2.png",
            },
            {
                "nama": "Hurts Little More", "descNilai": 4,
                "img": "/images/skalanyeri/3.png",
            },
            {
                "nama": "Hurts Even More", "descNilai": 6,
                "img": "/images/skalanyeri/4.png",
            },
            {
                "nama": "Hurts Whole Lot", "descNilai": 8,
                "img": "/images/skalanyeri/5.png",
            },
            {
                "nama": "Hurts whorts", "descNilai": 10,
                "img": "/images/skalanyeri/6.png",
            }]
    }
}

export function skoringNyeri(): any {
    return {
        "nama": "Score ", "detail": [
            { "nama": "0 - 1 = Tidak Ada Nyeri", "descNilai": 0 },
            { "nama": "2 - 3 = Sedikit Nyeri", "descNilai": 2 },
            { "nama": "4 - 5 = Cukup Nyeri", "descNilai": 4 },
            { "nama": "6 - 7 = Lumayan Nyeri", "descNilai": 6 },
            { "nama": "8 - 9 = Sangat Nyeri", "descNilai": 8 },
            { "nama": "10 = Amat Sangat Nyeri", "descNilai": 10 },
        ]
    }
}

export function riwayatPasien(): any {
    return [
        {
            "title": "Penyakit utama",
            "model": "riwayatPenyakit_"
        },
        {
            "title": "Operasi",
            "model": "riwayatPenyakit_"
        },
        {
            "title": "Cidera/mayor",
            "model": "riwayatPenyakit_"
        },
        {
            "title": "Hipertensi",
            "model": "riwayatPenyakit_"
        },
        {
            "title": "PPOK",
            "model": "riwayatPenyakit_"
        },
        {
            "title": "Diabetes",
            "model": "riwayatPenyakit_"
        },
        {
            "title": "Kanker",
            "model": "riwayatPenyakit_"
        },
        {
            "title": "Asma",
            "model": "riwayatPenyakit_"
        },
        {
            "title": "Infrak Miokard",
            "model": "riwayatPenyakit_"
        },
        {
            "title": "Hepatitis",
            "model": "riwayatPenyakit_"
        },
        {
            "title": "Kejang",
            "model": "riwayatPenyakit_"
        },
        {
            "title": "TB",
            "model": "riwayatPenyakit_"
        },
        {
            "title": "Jantung",
            "model": "riwayatPenyakit_"
        },
        {
            "title": "Ulkus",
            "model": "riwayatPenyakit_"
        },
        {
            "title": "Penyakit ginjal",
            "model": "riwayatPenyakit_"
        },
        {
            "title": "Ganguan Jiwa",
            "model": "riwayatPenyakit_"
        },
        {
            "title": "Penyakit paru lainnya",
            "model": "riwayatPenyakit_"
        },
    ]
}

export function riwayatKeluarga(): any {
    return [
        {
            "title": "Penyakit Jantung",
            "model": "riwayatKeluarga_"
        },
        {
            "title": "Hipertensi",
            "model": "riwayatKeluarga_"
        },
        {
            "title": "Stroke",
            "model": "riwayatKeluarga_"
        },
        {
            "title": "Asma",
            "model": "riwayatKeluarga_"
        },
        {
            "title": "Ganguan Jiwa",
            "model": "riwayatKeluarga_"
        },
        {
            "title": "Ginjal",
            "model": "riwayatKeluarga_"
        },
        {
            "title": "TB",
            "model": "riwayatKeluarga_"
        },
        {
            "title": "Ganguan Hematologi",
            "model": "riwayatKeluarga_"
        },
        {
            "title": "Anestesi",
            "model": "riwayatKeluarga_"
        },
        {
            "title": "Kanker",
            "model": "riwayatKeluarga_"
        },
        {
            "title": "Kejang",
            "model": "riwayatKeluarga_"
        },
        {
            "title": "Diabetes",
            "model": "riwayatKeluarga_"
        },
        {
            "title": "Tidak ada",
            "model": "riwayatKeluarga_"
        },
        {
            "title": "Lainnya",
            "model": "riwayatKeluarga_"
        },
    ]
}

export function riwayatLainnya(): any {
    return [
        {
            "title": "Riwayat (alergi)",
            "value": [
                {
                    "subTitle": "Ya (jelaskan)",
                    "model": "ketRiwayatAlergi",
                    "type": "textbox",
                },
                {
                    "subTitle": "Tidak",
                    "model": "riwayatAlergi",
                    "type": "checkbox",
                },
            ]
        },
        {
            "title": "Alkohol/obat",
            "value": [
                {
                    "subTitle": "Ya",
                    "model": "alkohol/obat",
                    "type": "checkbox",
                },
                {
                    "subTitle": "Tidak",
                    "model": "alkohol/obat",
                    "type": "checkbox",
                },
                {
                    "subTitle": "Berhenti",
                    "model": "alkohol/obat",
                    "type": "checkbox",
                },
                {
                    "subTitle": "Jenis",
                    "model": "jenisAlkohol/obat",
                    "type": "textbox",
                },
                {
                    "subTitle": "Jumlah/hari",
                    "model": "jumlahAlkohol/hari",
                    "type": "textbox",
                },
            ]
        },
        {
            "title": "Merokok",
            "value": [
                {
                    "subTitle": "Ya",
                    "model": "merokok",
                    "type": "checkbox",
                },
                {
                    "subTitle": "Tidak",
                    "model": "merokok",
                    "type": "checkbox",
                },
                {
                    "subTitle": "Berhenti",
                    "model": "merokok",
                    "type": "checkbox",
                },
                {
                    "subTitle": "Jenis",
                    "model": "jenisRokok",
                    "type": "textbox",
                },
                {
                    "subTitle": "Jumlah/hari",
                    "model": "jmlRokok/hari",
                    "type": "textbox",
                },
            ]
        },
    ]
}

export function listNutrisional(): any {
    return {
        "pertama": [{
            "no": 1,
            "parameter": "Apakah ada penurunan berat badan yang tidak diinginkan selama 6 bulan terakhir ?",
            "pengkajian": [
                {
                    "model": "penurunanBB",
                    "title": "Tidak",
                    "keterangan": "",
                    "poin": 0,
                    "value":
                    {
                        "poin": 0,
                        "keterangan": "Tidak",
                    }

                },
                {
                    "model": "penurunanBB",
                    "title": "Tidak Yakin",
                    "poin": 2,
                    "value":
                    {
                        "poin": 2,
                        "keterangan": "Tidak Yakin",
                    }

                },
                {
                    "model": "penurunanBB",
                    "title": "Ya,1-5 Kg",
                    "keterangan": "",
                    "poin": 1,
                    "value":
                    {
                        "poin": 1,
                        "keterangan": "Ya,1-5 Kg",
                    }

                },
                {
                    "model": "penurunanBB",
                    "title": "6-10 Kg",
                    "keterangan": "",
                    "poin": 2,
                    "value":
                    {
                        "poin": 2,
                        "keterangan": "6-10 Kg",
                    }

                },
                {
                    "model": "penurunanBB",
                    "title": "11-15 Kg",
                    "keterangan": "",
                    "poin": 3,
                    "value":
                    {
                        "poin": 3,
                        "keterangan": "11-15 Kg",
                    }

                },
                {
                    "model": "penurunanBB",
                    "title": "> 15 Kg",
                    "keterangan": "",
                    "poin": 4,
                    "value":
                    {
                        "poin": 4,
                        "keterangan": "> 15 Kg",
                    }

                },
            ],
        }],
        "kedua": [{
            "no": 2,
            "parameter": "Apakah asupan makan menurun yang dikarenakan adanya penurunan nafsu makan/kesulitan menerima makan ?",
            "pengkajian": [
                {
                    "model": "penurunanNafsuMakan",
                    "title": "Tidak",
                    "keterangan": "",
                    "poin": 0,
                    "value":
                    {
                        "poin": 0,
                        "keterangan": "Tidak",
                    }

                },
                {
                    "model": "penurunanNafsuMakan",
                    "title": "Ya",
                    "keterangan": "",
                    "poin": 1,
                    "value":
                    {
                        "poin": 1,
                        "keterangan": "Ya",
                    }

                },
            ],
        }],
    }
}

export function listResikoJatuh(): any {
    return {
        "pertama": [{
            "no": 1,
            "parameter": "Riwayat pasien : Apakah pasien pernah jatuh dalam 3 bulan terakhir ?",
            "pengkajian": [
                {
                    "model": "pasienPernahJatuh",
                    "title": "Tidak",
                    "poin": 0,
                    "value":
                    {
                        "poin": 0,
                        "keterangan": "Tidak",
                    }

                },
                {
                    "model": "pasienPernahJatuh",
                    "title": "Ya",
                    "poin": 25,
                    "value":
                    {
                        "poin": 25,
                        "keterangan": "Ya",
                    }

                },
            ],
        }],
        "kedua": [{
            "no": 2,
            "parameter": "Diagnosa Sekunder : Apakah pasien memiliki lebih dari satu penyakit ?",
            "pengkajian": [
                {
                    "model": "berpenyakitBanyak",
                    "title": "Tidak",
                    "poin": 0,
                    "value":
                    {
                        "poin": 0,
                        "keterangan": "Tidak",
                    }

                },
                {
                    "model": "berpenyakitBanyak",
                    "title": "Ya",
                    "poin": 15,
                    "value":
                    {
                        "poin": 15,
                        "keterangan": "Ya",
                    }

                },
            ],
        }],
        "ketiga": [{
            "no": 3,
            "parameter": "Alat Bantu Jalan",
            "pengkajian": [
                {
                    "model": "alatBantuJalan",
                    "title": "Bed rest dibantu perawat",
                    "poin": 0,
                    "value":
                    {
                        "poin": 0,
                        "keterangan": "Bed rest dibantu perawat",
                    }

                },
                {
                    "model": "alatBantuJalan",
                    "title": "Kruk/tongkat/walker",
                    "poin": 15,
                    "value":
                    {
                        "poin": 15,
                        "keterangan": "Kruk/tongkat/walker",
                    }

                },
                {
                    "model": "alatBantuJalan",
                    "title": "Berpegangan pada benda-benda disekitar",
                    "poin": 30,
                    "value":
                    {
                        "poin": 30,
                        "keterangan": "Berpegangan pada benda-benda disekitar",
                    }

                },
            ],
        }],
        "keempat": [{
            "no": 4,
            "parameter": "Terapi Intravena : Apakah saat ini pasien terpasang infus ?",
            "pengkajian": [
                {
                    "model": "terpasangInfus",
                    "title": "Tidak",
                    "poin": 0,
                    "value":
                    {
                        "poin": 0,
                        "keterangan": "Tidak",
                    }

                },
                {
                    "model": "terpasangInfus",
                    "title": "Ya",
                    "poin": 20,
                    "value":
                    {
                        "poin": 20,
                        "keterangan": "Ya",
                    }

                },
            ],
        }],
        "lima": [{
            "no": 5,
            "parameter": "Gaya berjalan/cara berpindah",
            "pengkajian": [
                {
                    "model": "gayaBerjalan",
                    "title": "Normal/Bed rest/immobile(tidak dapat bergerak sendiri)",
                    "poin": 0,
                    "value":
                    {
                        "poin": 0,
                        "keterangan": "Normal/Bed rest/immobile(tidak dapat bergerak sendiri)",
                    }

                },
                {
                    "model": "gayaBerjalan",
                    "title": "Kruk/tongkat/walker",
                    "poin": 15,
                    "value":
                    {
                        "poin": 15,
                        "keterangan": "Kruk/tongkat/walker",
                    }

                },
                {
                    "model": "gayaBerjalan",
                    "title": "Berpegangan pada benda-benda disekitar",
                    "poin": 30,
                    "value":
                    {
                        "poin": 30,
                        "keterangan": "Berpegangan pada benda-benda disekitar",
                    }

                },
            ],
        }],
        "enam": [{
            "no": 6,
            "parameter": "Status Mental",
            "pengkajian": [
                {
                    "model": "statusMental",
                    "title": "pasien Menyadari kondisinya",
                    "poin": 0,
                    "value":
                    {
                        "poin": 0,
                        "keterangan": "pasien Menyadari kondisinya",
                    }

                },
                {
                    "model": "statusMental",
                    "title": "Pasien mengalami keterbatasan daya ingat",
                    "poin": 15,
                    "value":
                    {
                        "poin": 15,
                        "keterangan": "Pasien mengalami keterbatasan daya ingat",
                    }

                },
            ],
        }],
    }
}

export function descripJumlah(): any {
    return [
        {
            "rangeNilai": 'Skor 0 - 1',
            "desc": ": Tidak Beresiko",
        },
        {
            "rangeNilai": 'Skor 2 - 3',
            "desc": ": Beresiko (Asuhan Gizi Oleh Dietizen)",
        },
        {
            "rangeNilai": 'Skor > 4',
            "desc": ": Malnutrisi (Asuhan Gizi oleh Dietisen)",
        },
    ]
}

export function descripJmlPoin(): any {
    return [
        {
            "model": "tingkatResiko",
            "title": "Tidak Beresiko",
            "nilai": "0-24",
            "value": {
                "keterangan": "Tidak Beresiko",
                "rangeNilai": 24
            },
            "tindakan": "Perawatan Dasar"
        },
        {
            "model": "tingkatResiko",
            "title": "Resiko Rendah",
            "nilai": "25-50",
            "value": {
                "keterangan": "Resiko Rendah",
                "rangeNilai": 50,
            },
            "tindakan": "Pelaksanaan intervensi pencegahan jatuh standar"
        },
        {
            "model": "tingkatResiko",
            "title": "Resiko Tinggi",
            "nilai": "> 50",
            "value": {
                "keterangan": "Resiko Tinggi",
                "rangeNilai": 51,
            },
            "tindakan": "pelaksanaan Intervensi pencegahan jatuh resiko tinggi"
        },
    ]
}

export function listFisikUmum(): any {
    return [
        {
            "title": "Pemeriksaan mata, telinga hidung dan tenggorokan",
            "detail": [
                {
                    "type": "checkBox",
                    "model": "pmrksFisikMTHT_",
                    "subTitle": "",
                    "value": ['Normal', 'Gusi', 'Tuli', 'Ganguan Visus', 'Kemerahan', 'Rasa Terbakar', 'Glaukoma', 'Drainase', 'Gigi', 'Sulit Mendengar', 'Buta', 'Luka']
                },
                {
                    "type": "textBox",
                    "subTitle": "Lainnya",
                    "model": "hasilMTHTLainnya",
                    "value": [],
                },
                {
                    "type": "textarea",
                    "subTitle": "Catatan",
                    "model": "catatanPemrksMTHT",
                    "value": [],
                }
            ]
        },
        {
            "title": "Pemeriksaan paru (kecepatan, kedalaman, pola, suara nafas)",
            "detail": [
                {
                    "type": "checkBox",
                    "model": "pmrksParu",
                    "subTitle": "",
                    "value": ['Normal']
                },
            ]
        },
        {
            "title": "Kiri",
            "detail": [
                {
                    "type": "checkBox",
                    "model": "pmrksParuKiri_",
                    "subTitle": "",
                    "value": ['Asimetris', 'Takipnea', 'Ronki', 'Barrel Chest', 'Bradibnea', 'Mengi/Wheezing', 'Sesak', 'Dangkal', 'Menghilang',]
                },
            ]
        },
        {
            "title": "Kanan",
            "detail": [
                {
                    "type": "checkBox",
                    "model": "pmrksParuKanan_",
                    "subTitle": "",
                    "value": ['Asimetris', 'Takipnea', 'Ronki', 'Barrel Chest', 'Bradibnea', 'Mengi/Wheezing', 'Sesak', 'Dangkal', 'Menghilang', 'Batuk', 'Warna Dahak']
                },
                {
                    "type": "textBox",
                    "subTitle": "Lainnya",
                    "model": "pmrksParuLainnya",
                    "value": [],
                },
                {
                    "type": "textarea",
                    "subTitle": "Catatan",
                    "model": "catatanPemrksParu",
                    "value": [],
                }
            ],
        },
        {
            "title": "Pemeriksaan kardiovaskuler (kecepatan, denyut, tekanan darah, sirkulasi, retensi cairan)",
            "detail": [
                {
                    "type": "checkBox",
                    "model": "pmrksKardiovakuler_",
                    "subTitle": "",
                    "value": ['Normal', 'Tarkikardi', 'Ireguler', 'Tingling', 'Edema', 'Denyut nadi lemah', 'Bardikardi', 'Murmur', 'Baal', 'Fatique', 'Denyut nadi tidak ada']
                },
                {
                    "type": "textBox",
                    "subTitle": "Lainnya",
                    "model": "pmrksKardiovakulerLainnya",
                    "value": [],
                },
                {
                    "type": "textarea",
                    "subTitle": "Catatan",
                    "model": "catatanPemrksKardiovakuler",
                    "value": [],
                }
            ],
        },
        {
            "title": "Pemeriksaan neurologi (orientasi, verbal, kekuatan), retensi cairan)",
            "detail": [
                {
                    "type": "checkBox",
                    "model": "pmrksNeurologi_",
                    "subTitle": "",
                    "value": ['Dalam Sedasi', 'Normal', 'Vertigo', 'Afasia', 'Tremor', 'Baal', 'Tidak Stabil', 'Letargi', 'Sakit kepala', 'Bicara tidak jelas', 'Semi koma', 'Paralisis', 'Pupil tidak efektif', 'Kejang', 'Tingling', 'Genggaman lemah']
                },
                {
                    "type": "textBox",
                    "subTitle": "Lainnya",
                    "model": "pmrksNeurologiLainnya",
                    "value": [],
                },
                {
                    "type": "textarea",
                    "subTitle": "Catatan",
                    "model": "catatanPemrksNeurologi",
                    "value": [],
                }
            ],
        },
        {
            "title": "Pemeriksaan gastrointestinal",
            "detail": [
                {
                    "type": "checkBox",
                    "model": "pmrksGastrointestinal_",
                    "subTitle": "",
                    "value": ['Normal', 'Distensi', 'Bising usus menurun', 'Anoreksia', 'Disfagia', 'Diare', 'Klisma spuit gliserin', 'Intolernasi diet', 'Bising usus meningkat', 'Tegang/Keras', 'Konstipasi', 'BAB Terakhir','Diet Khusus']
                },
                {
                    "type": "textBox",
                    "subTitle": "Lainnya",
                    "model": "pmrksGastrointestinalLain",
                    "value": [],
                },
                {
                    "type": "textarea",
                    "subTitle": "Catatan",
                    "model": "catatanPemrksGastrointestinal",
                    "value": [],
                }
            ],
        },
        {
            "title": "Pemeriksaan genitourinaria dan ginekologi",
            "detail": [
                {
                    "type": "checkBox",
                    "model": "pmrksGinekologi_",
                    "subTitle": "",
                    "value": ['Normal', 'Folley cateter', 'Hesitansi', 'Hematuria', 'Menopause', 'Sekret abnormal', 'Frekuensi', 'Urostomi', 'Inkontinensia', 'Nokturia', 'Disuria','Hamil']
                },
                {
                    "type": "textBox",
                    "subTitle": "Menstruasi Terakhir",
                    "model": "menstruasiTerakhir",
                    "value": [],
                },
                {
                    "type": "textarea",
                    "subTitle": "Catatan",
                    "model": "catatanPemrksGinekologi",
                    "value": [],
                }
            ],
        },
    ]
}

export function listMuskuloketal(): any {
    return [
        {
            "title": "Mobilitas",
            "detail": [
                {
                    "subTitle": "Normal",
                    "model": "muskolektalMobilitas",
                },
                {
                    "subTitle": "muskolektalMobilitas",
                    "model": "mobilitas",
                },
            ]
        },
        {
            "title": "Fungsi Sediri",
            "detail": [
                {
                    "subTitle": "Normal",
                    "model": "fungsiSendiri",
                },
                {
                    "subTitle": "Deformitas/atrofi",
                    "model": "fungsiSendiri",
                },
            ]
        },
        {
            "title": "Extremitas",
            "detail": [
                {
                    "subTitle": "Normal",
                    "model": "extremitas",
                },
                {
                    "subTitle": "Oedema",
                    "model": "extremitas",
                },
            ]
        },
        {
            "title": "Warna Kulit",
            "detail": [
                {
                    "subTitle": "Normal",
                    "model": "warnaKulit",
                },
                {
                    "subTitle": "Pucat",
                    "model": "warnaKulit",
                },
                {
                    "subTitle": "Kemerahan",
                    "model": "warnaKulit",
                },
            ]
        },
        {
            "title": "Turgor",
            "detail": [
                {
                    "subTitle": "Normal",
                    "model": "turgar",
                },
                {
                    "subTitle": "> 2 Detik",
                    "model": "turgar",
                },
            ]
        },
        {
            "title": "Permukaan Kulit",
            "detail": [
                {
                    "subTitle": "Normal",
                    "model": "permukaanKulit",
                },
                {
                    "subTitle": "Lembab",
                    "model": "permukaanKulit",
                },
                {
                    "subTitle": "Dingin",
                    "model": "permukaanKulit",
                },
                {
                    "subTitle": "Kering",
                    "model": "permukaanKulit",
                },
                {
                    "subTitle": "Panas",
                    "model": "permukaanKulit",
                },
            ]
        },
        {
            "title": "Kondisi Luka",
            "detail": [
                {
                    "subTitle": "Ada",
                    "model": "kondisiKulit",
                },
                {
                    "subTitle": "Luka bersih",
                    "model": "kondisiKulit",
                },
                {
                    "subTitle": "Luka Kotor",
                    "model": "kondisiKulit",
                },
                {
                    "subTitle": "Jahitan Luka",
                    "model": "kondisiKulit",
                },
                {
                    "subTitle": "Balutan Utuh",
                    "model": "kondisiKulit",
                },
                {
                    "subTitle": "Ganti balutan",
                    "model": "kondisiKulit",
                },
                {
                    "subTitle": "Tidak ada",
                    "model": "kondisiKulit",
                },
            ]
        },

    ]
}

export function listResikoKulit(): any {
    return {
        "pertama": [{
            "no": 1,
            "parameter": "Kondisi Fisik",
            "pengkajian": [
                {
                    "model": "kondisiFisik",
                    "title": "Baik",
                    "poin": 4,
                    "value":
                    {
                        "poin": 4,
                        "keterangan": "Baik",
                    }

                },
                {
                    "model": "kondisiFisik",
                    "title": "cukup",
                    "poin": 3,
                    "value":
                    {
                        "poin": 3,
                        "keterangan": "cukup",
                    }

                },
                {
                    "model": "kondisiFisik",
                    "title": "Buruk",
                    "poin": 2,
                    "value":
                    {
                        "poin": 2,
                        "keterangan": "Buruk",
                    }

                },
                {
                    "model": "kondisiFisik",
                    "title": "Sangat Buruk",
                    "poin": 1,
                    "value":
                    {
                        "poin": 1,
                        "keterangan": "Sangat Buruk",
                    }

                },
            ],
        }],
        "kedua": [{
            "no": 2,
            "parameter": "Kondisi Mental",
            "pengkajian": [
                {
                    "model": "kondisiMental",
                    "title": "Kompos Mentis",
                    "poin": 4,
                    "value":
                    {
                        "poin": 4,
                        "keterangan": "Kompos Mentis",
                    }

                },
                {
                    "model": "kondisiMental",
                    "title": "Apatis",
                    "poin": 3,
                    "value":
                    {
                        "poin": 3,
                        "keterangan": "Apatis",
                    }

                },
                {
                    "model": "kondisiMental",
                    "title": "Delirium",
                    "poin": 2,
                    "value":
                    {
                        "poin": 2,
                        "keterangan": "Delirium",
                    }

                },
                {
                    "model": "kondisiMental",
                    "title": "Stupor",
                    "poin": 1,
                    "value":
                    {
                        "poin": 1,
                        "keterangan": "Stupor",
                    }

                },
            ],
        }],
        "ketiga": [{
            "no": 3,
            "parameter": "Aktifitas",
            "pengkajian": [
                {
                    "model": "aktifitas",
                    "title": "Mandiri",
                    "poin": 4,
                    "value":
                    {
                        "poin": 4,
                        "keterangan": "Mandiri",
                    }

                },
                {
                    "model": "aktifitas",
                    "title": "Dipapah",
                    "poin": 3,
                    "value":
                    {
                        "poin": 3,
                        "keterangan": "Dipapah",
                    }

                },
                {
                    "model": "aktifitas",
                    "title": "Kursi Roda",
                    "poin": 2,
                    "value":
                    {
                        "poin": 2,
                        "keterangan": "Kursi Roda",
                    }

                },
                {
                    "model": "aktifitas",
                    "title": "Tirah Baring",
                    "poin": 1,
                    "value":
                    {
                        "poin": 1,
                        "keterangan": "Tirah Baring",
                    }

                },
            ],
        }],
        "empat": [{
            "no": 4,
            "parameter": "Mobilitas",
            "pengkajian": [
                {
                    "model": "mobilitas",
                    "title": "Baik",
                    "poin": 4,
                    "value":
                    {
                        "poin": 4,
                        "keterangan": "Baik",
                    }

                },
                {
                    "model": "mobilitas",
                    "title": "Agak Terbatas",
                    "poin": 3,
                    "value":
                    {
                        "poin": 3,
                        "keterangan": "Agak Terbatas",
                    }

                },
                {
                    "model": "mobilitas",
                    "title": "Sangat Terbatas",
                    "poin": 2,
                    "value":
                    {
                        "poin": 2,
                        "keterangan": "Sangat Terbatas",
                    }

                },
                {
                    "model": "mobilitas",
                    "title": "Imobilisasi",
                    "poin": 1,
                    "value":
                    {
                        "poin": 1,
                        "keterangan": "Imobilisasi",
                    }

                },
            ],
        }],
        "lima": [{
            "no": 5,
            "parameter": "Inkkonteneasi",
            "pengkajian": [
                {
                    "model": "inkkonteneasi",
                    "title": "Tidak",
                    "poin": 4,
                    "value":
                    {
                        "poin": 4,
                        "keterangan": "Tidak",
                    }

                },
                {
                    "model": "inkkonteneasi",
                    "title": "Terkadang",
                    "poin": 3,
                    "value":
                    {
                        "poin": 3,
                        "keterangan": "Terkadang",
                    }

                },
                {
                    "model": "inkkonteneasi",
                    "title": "Sering",
                    "poin": 2,
                    "value":
                    {
                        "poin": 2,
                        "keterangan": "Sering",
                    }

                },
                {
                    "model": "inkkonteneasi",
                    "title": "Selalu",
                    "poin": 1,
                    "value":
                    {
                        "poin": 1,
                        "keterangan": "Selalu",
                    }

                },
            ],
        }],
    }
}

export function listAHD(): any {
    return {
        "pertama": [{
            "no": 1,
            "parameter": "Makan/Memakai Baju",
            "pengkajian": [
                {
                    "model": "makanAtauPakaiBaju",
                    "title": "75% dibantu",
                    "poin": 3,
                    "value":
                    {
                        "poin": 3,
                        "keterangan": "75% dibantu",
                    }

                },
                {
                    "model": "makanAtauPakaiBaju",
                    "title": "50% dibantu",
                    "poin": 2,
                    "value":
                    {
                        "poin": 2,
                        "keterangan": "50% dibantu",
                    }

                },
                {
                    "model": "makanAtauPakaiBaju",
                    "title": "25% dibantu",
                    "poin": 1,
                    "value":
                    {
                        "poin": 1,
                        "keterangan": "25% dibantu",
                    }

                },
                {
                    "model": "makanAtauPakaiBaju",
                    "title": "Mandiri",
                    "poin": 0,
                    "value":
                    {
                        "poin": 0,
                        "keterangan": "Mandiri",
                    }

                },
            ],
        }],
        "kedua": [{
            "no": 2,
            "parameter": "Berjalan",
            "pengkajian": [
                {
                    "model": "berjalan",
                    "title": "75% dibantu",
                    "poin": 3,
                    "value":
                    {
                        "poin": 3,
                        "keterangan": "75% dibantu",
                    }

                },
                {
                    "model": "berjalan",
                    "title": "50% dibantu",
                    "poin": 2,
                    "value":
                    {
                        "poin": 2,
                        "keterangan": "50% dibantu",
                    }

                },
                {
                    "model": "berjalan",
                    "title": "25% dibantu",
                    "poin": 1,
                    "value":
                    {
                        "poin": 1,
                        "keterangan": "25% dibantu",
                    }

                },
                {
                    "model": "berjalan",
                    "title": "Mandiri",
                    "poin": 0,
                    "value":
                    {
                        "poin": 0,
                        "keterangan": "Mandiri",
                    }

                },
            ],
        }],
        "ketiga": [{
            "no": 3,
            "parameter": "Mandi",
            "pengkajian": [
                {
                    "model": "mandi",
                    "title": "75% dibantu",
                    "poin": 3,
                    "value":
                    {
                        "poin": 3,
                        "keterangan": "75% dibantu",
                    }

                },
                {
                    "model": "mandi",
                    "title": "50% dibantu",
                    "poin": 2,
                    "value":
                    {
                        "poin": 2,
                        "keterangan": "50% dibantu",
                    }

                },
                {
                    "model": "mandi",
                    "title": "25% dibantu",
                    "poin": 1,
                    "value":
                    {
                        "poin": 1,
                        "keterangan": "25% dibantu",
                    }

                },
                {
                    "model": "mandi",
                    "title": "Mandiri",
                    "poin": 0,
                    "value":
                    {
                        "poin": 0,
                        "keterangan": "Mandiri",
                    }

                },
            ],
        }],
    }
}

export function listKeperawatanKhusus():any{
    return [
        {
            "title": "Kondisi fisik dan mental",
            "detail": [
                {
                    "subTitle": "Pernah Jatuh",
                    "value": [
                        {
                            "model": "pernahJatuh",
                            "type": "checkBox",
                            "name": "Tidak",
                        },
                        {
                            "model": "descPernahJatuh",
                            "type": "textBox",
                            "name": "Ya",
                        },
                        {
                            "model": "pernahJatuh",
                            "type": "checkBox",
                            "name": "Bulan",
                        },
                        {
                            "model": "pernahJatuh",
                            "type": "checkBox",
                            "name": "Tahun yang lalu",
                        },
                    ]
                },
                {
                    "subTitle": "Kontraktur/Nyeri gerak",
                    "value": [
                        {
                            "model": "kontraktur",
                            "type": "checkBox",
                            "name": "Tidak",
                        },
                        {
                            "model": "descKontraktur",
                            "type": "textBox",
                            "name": "Ya, di",
                        },
                    ]
                },
                {
                    "subTitle": "Menggunakan alat bantu",
                    "value": [
                        {
                            "model": "alatBantu",
                            "type": "checkBox",
                            "name": "Tidak",
                        },
                        {
                            "model": "alatBantu",
                            "type": "checkBox",
                            "name": "Tongkat",
                        },
                        {
                            "model": "alatBantu",
                            "type": "checkBox",
                            "name": "Kursi roda",
                        },
                        {
                            "model": "descAlatBantu",
                            "type": "textBox",
                            "name": "lainnya",
                        },
                    ]
                },
                {
                    "subTitle": "Skala depresi",
                    "value": [
                        {
                            "model": "skalaDepresi",
                            "type": "checkBox",
                            "name": "Tidak depresi (1-4)",
                        },
                        {
                            "model": "skalaDepresi",
                            "type": "checkBox",
                            "name": "Resiko depresi (5-9)",
                        },
                        {
                            "model": "skalaDepresi",
                            "type": "checkBox",
                            "name": "Depresi (>9)",
                        },
                    ]
                },
                {
                    "subTitle": "Memori",
                    "value": [
                        {
                            "model": "memori",
                            "type": "checkBox",
                            "name": "Baik",
                        },
                        {
                            "model": "memori",
                            "type": "checkBox",
                            "name": "Sering lupa",
                        },
                        {
                            "model": "memori",
                            "type": "checkBox",
                            "name": "Tidak Ingat",
                        },
                    ]
                },
                {
                    "subTitle": "Status minimental",
                    "value": [
                        {
                            "model": "statusMinimental",
                            "type": "checkBox",
                            "name": "Normal (24-30)",
                        },
                        {
                            "model": "statusMinimental",
                            "type": "checkBox",
                            "name": "Ringan (17-23)",
                        },
                        {
                            "model": "statusMinimental",
                            "type": "checkBox",
                            "name": "Pasti (<=16)",
                        },
                    ]
                },
                {
                    "subTitle": "Edukasi",
                    "value": [
                        {
                            "model": "edukasi",
                            "type": "textarea",
                            "name": "",
                        },
                    ]
                },
                {
                    "subTitle": "Hambatan edukasi",
                    "value": [
                        {
                            "model": "hambatanEdukasiBhs",
                            "type": "textBox",
                            "name": "Bahasa",
                        },
                        {
                            "model": "hambatanEdukasiFisik",
                            "type": "textBox",
                            "name": "Cacat/Fisik/Kognitif",
                        },
                    ]
                },
            ]
        },
        {
            "title": "Psikososial/ekonomi",
            "detail": [
                {
                    "subTitle": "Pendidikan",
                    "value": [
                        {
                            "model": "pendidikan",
                            "type": "checkBox",
                            "name": "SD"
                        },
                        {
                            "model": "pendidikan",
                            "type": "checkBox",
                            "name": "SMP"
                        },
                        {
                            "model": "pendidikan",
                            "type": "checkBox",
                            "name": "SMA"
                        },
                        {
                            "model": "pendidikan",
                            "type": "checkBox",
                            "name": "S1"
                        },
                        {
                            "model": "pendidikan",
                            "type": "checkBox",
                            "name": "S2"
                        },
                        {
                            "model": "pendidikan",
                            "type": "checkBox",
                            "name": "S3"
                        },
                        {
                            "model": "pendidikan",
                            "type": "checkBox",
                            "name": "Lain-lain"
                        },
                    ]
                },
                {
                    "subTitle": "Pekerjaan",
                    "value": [
                        {
                            "model": "pekerjaan",
                            "type": "checkBox",
                            "name": "Swasta",
                        },
                        {
                            "model": "pekerjaan",
                            "type": "checkBox",
                            "name": "BUMN",
                        },
                        {
                            "model": "pekerjaan",
                            "type": "checkBox",
                            "name": "PNS",
                        },
                        {
                            "model": "pekerjaan",
                            "type": "checkBox",
                            "name": "Wiraswasta",
                        },
                        {
                            "model": "pekerjaan",
                            "type": "checkBox",
                            "name": "Profesi",
                        },
                        {
                            "model": "pekerjaan",
                            "type": "checkBox",
                            "name": "Lain-lain",
                        },
                    ]
                },
                {
                    "subTitle": "Agama Kepercayaan",
                    "value": [
                        {
                            "model": "agama",
                            "type": "checkBox",
                            "name": "Islam"
                        },
                        {
                            "model": "agama",
                            "type": "checkBox",
                            "name": "Katholik"
                        },
                        {
                            "model": "agama",
                            "type": "checkBox",
                            "name": "Kristen"
                        },
                        {
                            "model": "agama",
                            "type": "checkBox",
                            "name": "Hindu"
                        },
                        {
                            "model": "agama",
                            "type": "checkBox",
                            "name": "Budha"
                        },
                    ]
                },
                {
                    "subTitle": "Nilai-nilai yang dianut",
                    "value": [
                        {
                            "model": "nilaiDianut",
                            "name": "",
                            "type": "textarea",
                        }
                    ]
                },
                {
                    "subTitle": "Status Pernikahan",
                    "value": [
                        {
                            "model": "statusNikah",
                            "name": "Menikah",
                            "type": "checkBox",
                        },
                        {
                            "model": "statusNikah",
                            "name": "Belum Nikah",
                            "type": "checkBox",
                        },
                        {
                            "model": "statusNikah",
                            "name": "Duda / Janda",
                            "type": "checkBox",
                        },
    
                    ]
                },
                {
                    "subTitle": "Keluarga",
                    "value": [
                        {
                            "model": "keluarga",
                            "name": "Tinggal Sendiri",
                            "type": "checkBox",
                        },
                        {
                            "model": "keluarga",
                            "name": "Tinggal Serumah",
                            "type": "checkBox",
                        },
                    ]
                },
                {
                    "subTitle": "Tempat tinggal",
                    "value": [
                        {
                            "model": "tempatTinggal",
                            "name": "Rumah",
                            "type": "checkBox",
                        },
                        {
                            "model": "tempatTinggal",
                            "name": "Panti Asuhan",
                            "type": "checkBox",
                        },
                        {
                            "model": "tempatTinggalLain",
                            "name": "Lainnya",
                            "type": "textBox",
                        },
                    ]
                },
                {
                    "subTitle": "Status Psikologi",
                    "value": [
                        {
                            "model": "statusPsikologi",
                            "name": "Depresi",
                            "type": "checkBox",
                        },
                        {
                            "model": "statusPsikologi",
                            "name": "Takut",
                            "type": "checkBox",
                        },
                        {
                            "model": "statusPsikologi",
                            "name": "Agresif",
                            "type": "checkBox",
                        },
                        {
                            "model": "statusPsikologi",
                            "name": "Melukai Diri Sendiri",
                            "type": "checkBox",
                        },
                        {
                            "model": "statusPsikologi",
                            "name": "Tidak ada Gejala",
                            "type": "checkBox",
                        },
                    ]
                },
                {
                    "subTitle": "Diagnosa Keperawatan",
                    "value": [
                        {
                            "model": "diagnosaKeperawatan",
                            "name": "",
                            "type": "textarea",
                        },
                    ]
                },
                {
                    "subTitle": "Planning / Intervensi",
                    "value": [
                        {
                            "model": "intervensi",
                            "name": "",
                            "type": "textarea",
                        },
                    ]
                },
            ]
        }
    ]
}

export function glasgows():any {
    return ['Kompos Mentis', 'Apatis', 'Sopor Koma', 'Somnolen', 'Sopor', 'Koma']
}