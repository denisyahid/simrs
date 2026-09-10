export function anamnesis() : any {
    return  [
        {
            "title": "Keluhan saat ini",
            "model": "keluhanSaatIni",
        },
        {
            "title": "Riwayat Penyakit Dahulu",
            "model": "riwayatPenyakitDahulu",
        },
        {
            "title": "Riwayat Keluarga",
            "model": "riwayatKeluarga",
        },
    ]
}

export function pemeriksaanFisik() : any {
    return [
        {
            "title": "",
            "type": "textfield",
            "detail": [
                {
                    "subTitle": "TD",
                    "model": 'tekananDarah',
                    "type": "textbox",
                    "satuan": "mmHg"
                },
                {
                    "subTitle": "Nadi",
                    "model": 'frekuensiNadi',
                    "satuan": "x/m"
                },
                {
                    "subTitle": "TB",
                    "model": 'tinggiBadan',
                    "satuan": "cm"
                },
                {
                    "subTitle": "BB",
                    "model": 'beratBadan',
                    "satuan": "kg"
                },
                {
                    "subTitle": "RR",
                    "model": 'frekuensiNafas',
                    "satuan": "x/m"
                },
                {
                    "subTitle": "Suhu",
                    "model": 'suhu',
                    "satuan": "°C"
                },
                {
                    "subTitle": "IMT",
                    "model": 'imt',
                    "satuan": "imt"
                },
                {
                    "subTitle": "Lingkar Perut",
                    "model": 'lingkarPerut',
                    "satuan": "cm"
                },
            ],
        },
        {
            "title": "",
            "type": "textbox",
            "detail": [
                {
                    "subTitle": "Kesadaran",
                    "model": "kesadaran",
                },
                {
                    "subTitle": "Status Mentalis",
                    "model": "statusMentalis",
                },
                {
                    "subTitle": "Kepala",
                    "model": "kepala",
                },
                {
                    "subTitle": "Kulit",
                    "model": "kulit",
                },
                {
                    "subTitle": "Mata",
                    "model": "mata",
                },
                {
                    "subTitle": "Telinga",
                    "model": "telinga",
                },
                {
                    "subTitle": "Hidung",
                    "model": "hidung",
                },
                {
                    "subTitle": "Tenggorokan",
                    "model": "tenggorokan",
                },
                {
                    "subTitle": "Gigi dan Mulut",
                    "model": "gigiDanMulut",
                },
            ]
        },
        {
            "title": "Thorax",
            "type": "",
            "detail": [
                {
                    "subTitle": "Jantung",
                    "model": "jantung",
                },
                {
                    "subTitle": "Paru",
                    "model": "paru",
                },
            ]
        },
        {
            "title": "Perut",
            "type": "",
            "detail": [
                {
                    "subTitle": "Hati",
                    "model": "hati",
                },
                {
                    "subTitle": "Limpa",
                    "model": "limpa",
                },
                {
                    "subTitle": "Anus",
                    "model": "anus",
                },
                {
                    "subTitle": "Ginjal",
                    "model": "ginjal",
                },
            ]
        },
        {
            "title": "Anggota Gerak",
            "type": "",
            "detail": [
                {
                    "subTitle": "Ekstrimitas Atas",
                    "model": "ekstrimitasAtas",
                },
                {
                    "subTitle": "Ekstrimitas Bawah",
                    "model": "ekstrimitasBawah",
                },
            ]
        },
        {
            "title": "Saraf",
            "type": "",
            "detail": [
                {
                    "subTitle": "Refleks Fisiologis",
                    "model": "refleksFisiologis",
                },
                {
                    "subTitle": "Refleks Patologis",
                    "model": "refleksPatologis",
                },
                {
                    "subTitle": "Tulang Belakang",
                    "model": "tulangBelakang",
                },
                {
                    "subTitle": "Genitalia",
                    "model": "genitalia",
                },
                {
                    "subTitle": "Lain-lain",
                    "model": "sarafLain",
                },
            ]
        },
    ]
}

export function pemeriksaanPenunjang() : any {
    return [
        {
            "title": "Thorax Foto",
            "model": "thoraxFoto"
        },
        {
            "title": "EKG",
            "model": "ekg"
        },
        {
            "title": "Laboratorium",
            "model": "laboratorium"
        },
        {
            "title": "Treadmill",
            "model": "treadmill"
        },
        {
            "title": "Lain-lain",
            "model": "pemeriksaanLain"
        },
        {
            "title": "Catatan",
            "model": "catatan"
        },
    ]
}
