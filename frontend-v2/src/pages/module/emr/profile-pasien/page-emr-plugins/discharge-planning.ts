export function keadaanMasuk(): any {
    return[
        {
            "title": "",
            "type": "textarea",
            "value": [
                {
                    "subTitle": "Indikasi Rawat Inap",
                    "model": "indikasiRanap",
                },
                {
                    "subTitle": "Pemeriksaan Fisik",
                    "model": "pemeriksaanFisik",
                },
                {
                    "subTitle": "Pemeriksaan Penunjang",
                    "model": "pemeriksaanPenunjang",
                },
                {
                    "subTitle": "Diet",
                    "model": "diet",
                },
                {
                    "subTitle": "Terapi/ Pengobatan yang diberikan",
                    "model": "terapiPengobatan",
                },
                {
                    "subTitle": "Tindakan/ Prosedur operasi yang sudah dilakukan",
                    "model": "tindakanSudahdilakukan",
                },
            ]
        },
        {
            "title": "Alergi",
            "type": "checkBox",
            "value": [
                {
                    "subTitle": "Ya",
                    "model": "riwayatAlergi",
                },
                {
                    "subTitle": "Tidak",
                    "model": "riwayatAlergi",
                }
            ]
        }, 
    ]
}

export function keadaanKeluar(): any {
    return [
        {
            "title": "",
            "type": "textarea",
            "value": [
                {
                    "subTitle": "1. KU Pasien",
                    "model": "ku_Pasien",
                },
    
            ]
        },
        {
            "title": "Tanda Vital",
            "type": "textBox",
            "value": [
    
                {
                    "subTitle": "Takanan Darah",
                    "model": "tekananDarah",
                    "satuan": "mmHg"
                },
                {
                    "subTitle": "Berat Badan",
                    "model": "beratBadan",
                    "satuan": "kg"
                },
                {
                    "subTitle": "Tinggi Badan",
                    "model": "tinggiBadan",
                    "satuan": "cm"
                },
                {
                    "subTitle": "Frekuensi Nadi",
                    "model": "nadi",
                    "satuan": "x/menit"
                },
                {
                    "subTitle": "Suhu",
                    "model": "suhu",
                    "satuan": "°C"
                },
                {
                    "subTitle": "Frekuensi Nafas",
                    "model": "pernapasan",
                    "satuan": "x/menit"
                },
                {
                    "subTitle": "Skor Nyeri",
                    "model": "nyeri",
                    "satuan": ""
                },
                {
                    "subTitle": "IMT",
                    "model": "IMT",
                    "satuan": ""
                },
            ]
        },
        {
            "title": "Resiko Jatuh",
            "type": "checkBox",
            "value": [
                {
                    "subTitle": "Ya",
                    "model": "resikoJatuh",
                },
                {
                    "subTitle": "Tidak",
                    "model": "resikoJatuh",
                }
            ]
        }, 
    ]
}

export function edukasiKeadaanKeluar(): any {
    return [
        {
            "title": "",
            "type": "textarea",
            "value": [
                {
                    "subTitle": "2. Edukasi",
                    "model": "edukasi",
                },
    
            ]
        },
        {
            "title": "3. Tindakan Lanjutan",
            "type": "checkBox",
            "value": [
                {
                    "subTitle": "Pulang",
                    "model": "tindakanLanjutan_",
                },
                {
                    "subTitle": "Pulag Atas Permintaan Sendiri",
                    "model": "tindakanLanjutan_",
                },
                {
                    "subTitle": "Sembuh",
                    "model": "tindakanLanjutan_",
                },
                {
                    "subTitle": "Rujuk",
                    "model": "tindakanLanjutan_",
                },
                {
                    "subTitle": "Berobat Jalan / Kontrol",
                    "model": "tindakanLanjutan_",
                },
                {
                    "subTitle": "Pindah RS Lain",
                    "model": "tindakanLanjutan_",
                },
                {
                    "subTitle": "Meninggal",
                    "model": "tindakanLanjutan_",
                },
                {
                    "subTitle": "Meninggalkan RS tanpa Ijin",
                    "model": "tindakanLanjutan_",
                },
            ]
        },
        {
            "title": "4. Berkas Yang dibawakan Saat Pulang ",
            "type": "checkBox",
            "value": [
                {
                    "subTitle": "Surat Kontrol",
                    "model": "berkasPulang_",
                },
                {
                    "subTitle": "Laboratorium",
                    "model": "berkasPulang_",
                },
                {
                    "subTitle": "Surat Keterangan Sakit",
                    "model": "berkasPulang_",
                },
                {
                    "subTitle": "Dower Catheter",
                    "model": "berkasPulang_",
                },
                {
                    "subTitle": "EKG",
                    "model": "berkasPulang_",
                },
                {
                    "subTitle": "NGT",
                    "model": "berkasPulang_",
                },
                {
                    "subTitle": "Radiologi",
                    "model": "berkasPulang_",
                },
                {
                    "subTitle": "Surat Keterangan Meninggal",
                    "model": "berkasPulang_",
                },
                {
                    "subTitle": "Lain-lain",
                    "model": "berkasPulang_",
                },
            ]
        },
    ]
}