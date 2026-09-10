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
            "Kriteria": "Tindakan Anestesi",
            "keterangan": [
               
                {
                    "title" : "Umum",
                    "model" : "infoAnastasi",
                    "type"  : "checkBox"
                },
                {
                    "title" : "Regional",
                    "model" : "infoAnastasi",
                    "type"  : "checkBox"
                },
                {
                    "title" : "Sedasi",
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
                    "model" : "isiTatacara",
                    "type"  : "textArea"
                }
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
                    "model" : "isiRisiko",
                    "type"  : "textArea"
                }
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
                    "model" : "isiKomplikasi",
                    "type"  : "textArea"
                }
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