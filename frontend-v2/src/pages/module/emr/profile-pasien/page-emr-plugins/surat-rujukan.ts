export function JSRujukan(): any {
    return ['Rawat Inap', 'Rawat Jalan', 'Parsial']
}


export function keadaanUmum_1(): any {
    return [
        {
            "title": "Anamnesis & Pemeriksaan Fisik",
            "model": "amnesis"
        },
        {
            "title": "Riwayat Alergi",
            "model": "riwayatAlergi"
        },
        {
            "title": "Keadaan Umum",
            "model": "keadaanUmum"
        },
    ]
}

export function kesadaran(): any {
    return [
        {
            "title": "Kesadaran",
            "detail": [
                {
                    "label": "Sadar",
                    "model": "kesadaran",
                },
                {
                    "label": "Tidak",
                    "model": "kesadaran",
                },
            ]
        }
    ]
}

export function keadaanUmum_2(): any {
    return [
        {
            "title": "Tekanan Darah",
            "model": "tekananDarah",
        },
        {
            "title": "Frekuensi Nadi",
            "model": "frekuensiNadi",
        },
        {
            "title": "Pernapasan",
            "model": "pernapasan",
        },
        {
            "title": "Suhu",
            "model": "suhu"
        },
        {
            "title": "Saturasi Oksigen",
            "model": "saturasiOksigen"
        },
    ]
}

export function nyeri(): any {
    return [
        {
            "label": "Tidak Nyeri",
            "model": "nyeri",
        },
        {
            "label": "Ringan",
            "model": "nyeri",
        },
        {
            "label": "Sedang",
            "model": "nyeri",
        },
        {
            "label": "Berat",
            "model": "nyeri",
        },
    ]
}

export function penunjang() : any{
    return [
        {
            "label" : "Hasil Lab",
            "model" : "hasilLab",
        },
        {
            "label" : "Hasil Radiologi",
            "model" : "hasilRadiologi",
        },
        {
            "label" : "Terapi / Tindakan yang diberikan",
            "model" : "terapi",
        },
    ]
}