export function DaftarInformasi(): any {
    return [
        {
            "no": 1.,
            "Kriteria": "Diagnosa (WD & DD)",
            "keterangan": [
                {
                    "model" : "isiDiagnosa",
                    "type"  : "textArea"
                }
            ],
            "pilihan": [
                {
                    "label": "Ada",
                    "model": "AdaDiagnosa"
                },
            ],          
        },
        {
            "no": 2.,
            "Kriteria": "Tindakan Operasi",
            "keterangan": [
                {
                    "model" : "isiTindakan",
                    "type"  : "textArea"
                }
            ],
            "pilihan": [
                {
                    "label": "Ada",
                    "model": "AdaTindakan"
                },
            ],          
        },
        {
            "no": 3.,
            "Kriteria": "Rencana Tindakan Kedokteran",
            "keterangan": [
               
                {
                    "title" : "Anestesi umum dengan sedasi",
                    "model" : "infoAnastasi",
                    "type"  : "checkBox"
                },
                {
                    "title" : "Anestesi umum tanpa sedasi",
                    "model" : "infoAnastasi",
                    "type"  : "checkBox"
                },
                {
                    "title" : "Anestesi spinal/epidural dengan sedasi",
                    "model" : "infoAnastasi",
                    "type"  : "checkBox"
                },
                {
                    "title" : "Anestesi spinal/epidural tanpa sedasi",
                    "model" : "infoAnastasi",
                    "type"  : "checkBox"
                },
                     {
                    "title" : "Monitored anesthesia care dengan sedasi",
                    "model" : "infoAnastasi",
                    "type"  : "checkBox"
                },
                {
                    "title" : "Monitored anesthesia care tanpa sedasi",
                    "model" : "infoAnastasi",
                    "type"  : "checkBox"
                },

            ],
            "pilihan": [
                {
                    "label": "Ada",
                    "model": "adaTindakan"
                },
            ],          
        },
        {
            "no": 4.,
            "Kriteria": "Indikasi Tindakan",
            "keterangan": [
                {
                    "model" : "isiIndikasi",
                    "type"  : "textArea"
                }
            ],
            "pilihan": [
                {
                    "label": "Ada",
                    "model": "AdaIndikasi"
                },
            ],          
        },
        {
            "no": 5.,
            "Kriteria": "Tata Cara",
            "keterangan": [
                {
                    "title" : "Anestesi umum: total kehilangan kesadaran dengan alat bantu pernafasan.",
                    "model" : "infoCara",
                    "type"  : "checkBox"
                },
                {
                    "title" : "Anestesi spinal/epidural: penurunan sampai hilangnya sensasi dan kemampuan gerak mulai dari bagian pertengahan tubuh kebawah.",
                    "model" : "infoCara",
                    "type"  : "checkBox"
                },
                {
                    "title" : "Blok saraf mayor/minor: hilangnya sensasi dan kemampuan gerak anggota tubuh atau daerah tertentu dari pada tubuh.",
                    "model" : "infoCara",
                    "type"  : "checkBox"
                },
                {
                    "title" : "monitored anesthesia care: mengurangi kecemasan dan nyeri, amnesia parsial atau total.",
                    "model" : "infoCara",
                    "type"  : "checkBox"
                },
            ],
            "pilihan": [
                {
                    "label": "Ada",
                    "model": "adaTataCara"
                },
            ],          
        },
        {
            "no": 6.,
            "Kriteria": "Tujuan",
            "keterangan": [
                {
                    "model" : "isiTujuan",
                    "type"  : "textArea"
                }
            ],
            "pilihan": [
                {
                    "label": "Ada",
                    "model": "AdaTujuan"
                },
            ],          
        },
        {
            "no": 7.,
            "Kriteria": "Risiko",
            "keterangan": [
                {
                    "title" : "anestesi umum: sakit tenggorokan, suara sesak, mual muntah, trauma mata, trauma mulut dan gigi, luka pada lidah, gusi dan bibir, trauma pembulu darah, aspirasi sampai infeksi paru-paru, perubahan dan penurunan tekanan darah, masih ingat kejadian yang terjadi di kamar bedah.",
                    "model" : "infoResiko",
                    "type"  : "checkBox"
                },
                {
                    "title" : "anestesi spinal/epidural: nyeri kepala, nyeri tulang belakang, infeksi, kejang, perubahan dan penurunan tekanan darah, lemah atau mati rasa anggota gerak yang persisten sampai terjadi trauma saraf permanen, paralisis bahkan kematian, total spinal sehingga menggangu fungsi pernafasan sampai serangan jantung. ",
                    "model" : "infoCara",
                    "type"  : "checkBox"
                },
                {
                    "title" : "blok saraf mayor/minor: inspeksi kejang, lemah/mati rasa anggota gerak atau daerah tubuh sampai kerusakan saraf yang menetap.",
                    "model" : "infoCara",
                    "type"  : "checkBox"
                },
                {
                    "title" : "monitored anesthesia care: hilang kesadaran total, depresi nafas, trauma pembuluh darah.",
                    "model" : "infoCara",
                    "type"  : "checkBox"
                },
            ],
            "pilihan": [
                {
                    "label": "Ada",
                    "model": "adaRisiko"
                },
            ],          
        },
        {
            "no": 8.,
            "Kriteria": "Komplikasi",
            "keterangan": [
                {
                    "title" : "Anestesi umum: sakit tenggorokan, suara sesak, mual muntah, trauma mata, trauma mulut dan gigi, luka pada lidah, gusi dan bibir, trauma pembulu darah, aspirasi sampai infeksi paru-paru, perubahan dan penurunan tekanan darah, masih ingat kejadian yang terjadi di kamar bedah.",
                    "model" : "InfoKomplikasi",
                    "type"  : "checkBox"
                },
                {
                    "title" : "Anestesi spinal/epidural: nyeri kepala, nyeri tulang belakang, infeksi, kejang, perubahan dan penurunan tekanan darah, lemah atau mati rasa anggota gerak yang persisten sampai terjadi trauma saraf permanen, paralisis bahkan kematian, total spinal sehingga menggangu fungsi pernafasan sampai serangan jantung.",
                    "model" : "InfoKomplikasi",
                    "type"  : "checkBox"
                },
                {
                    "title" : "Blok saraf mayor/minor: inspeksi kejang, lemah/mati rasa anggota gerak atau daerah tubuh sampai kerusakan saraf yang menetap.",
                    "model" : "InfoKomplikasi",
                    "type"  : "checkBox"
                },
                {
                    "title" : "Monitored anesthesia care: hilang kesadaran total, depresi nafas, trauma pembuluh darah.",
                    "model" : "InfoKomplikasi",
                    "type"  : "checkBox"
                },
            ],
            "pilihan": [
                {
                    "label": "Ada",
                    "model": "adaKomplikasi"
                },
            ],          
        },
        {
            "no": 9.,
            "Kriteria": "Prognosis",
            "keterangan": [
                {
                    "model" : "isiPrognosis",
                    "type"  : "textArea"
                }
            ],
            "pilihan": [
                {
                    "label": "Ada",
                    "model": "adaPrognosis"
                },
            ],          
        },
        {
            "no": 10.,
            "Kriteria": "Alternatif & Risiko",
            "keterangan": [
                {
                    "model" : "isiAlternatif",
                    "type"  : "textArea"
                }
            ],
            "pilihan": [
                {
                    "label": "Ada",
                    "model": "adaAlternatif"
                },
            ],          
        },
        {
            "no": 11.,
            "Kriteria": "Manfaat",
            "keterangan": [
                {
                    "model" : "isiManfaat",
                    "type"  : "textArea"
                }
            ],
            "pilihan": [
                {
                    "label": "Ada",
                    "model": "adaManfaat"
                },
            ],          
        },
       
    ]
}



























