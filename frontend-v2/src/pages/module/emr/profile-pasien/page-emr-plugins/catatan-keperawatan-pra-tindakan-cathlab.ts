export function rencanaTindakan(): any {
    return [
        { "nama": "Coronary Angiography", "model": "rencanaTindakan_"},
        { "nama": "DSA Cerebral", "model": "rencanaTindakan_"},
        { "nama": "Peripheral Angiography", "model": "rencanaTindakan_"},
        { "nama": "Left and Right Heart Catheterization", "model": "rencanaTindakan_"},
        { "nama": "Elective PCI", "model": "rencanaTindakan_"},
        { "nama": "Primary PCI", "model": "rencanaTindakan_"},
        { "nama": "Peripheral PTA", "model": "rencanaTindakan_"},
        { "nama": "PDA/ASD/VSD*) Closure", "model": "rencanaTindakan_"},
        { "nama": "TPM", "model": "rencanaTindakan_"},
        { "nama": "PPM/ICD/CRT*)", "model": "rencanaTindakan_"},
        { "nama": "EP Study", "model": "rencanaTindakan_"},
        { "nama": "Cardiac Arrhythmia", "model": "rencanaTindakan_"},
        { "nama": "EVAR/TEVAR*)", "model": "rencanaTindakan_" },
        { "nama": "TAE/TACE", "model": "rencanaTindakan_" },
        { "nama": "Embolization Cerebral", "model": "rencanaTindakan_" },
        { "nama": "Lain-lain","model": "rencanaTindakan_"},
       
    ]
}
export function checkList(): any {
    return [
        {
            "title": "Gelang identitas",
            "value": [
                {
                    "subTitle": "Ada",
                    "model": "gelangIdentitas",
                    "type": "checkbox",
                },
                {
                    "subTitle": "Tidak Ada",
                    "model": "gelangIdentitas",
                    "type": "checkbox",
                },
                {
                    "subTitle": "",
                    "model": "keteranganGelangIdentitas",
                    "type": "textbox",
                },
            ]
        },
        {
            "title": "Persetujuan Biaya",
            "value": [
                {
                    "subTitle": "Ada",
                    "model": "gelangIdentitas",
                    "type": "checkbox",
                },
                {
                    "subTitle": "Tidak Ada",
                    "model": "gelangIdentitas",
                    "type": "checkbox",
                },
                {
                    "subTitle": "",
                    "model": "keteranganGelangIdentitas",
                    "type": "textbox",
                },
            ]
        },
        {
            "title": "Pemberian Informasi Medis (Informed)",
            "value": [
                {
                    "subTitle": "Ada",
                    "model": "infoMedis",
                    "type": "checkbox",
                },
                {
                    "subTitle": "Tidak Ada",
                    "model": "infoMedis",
                    "type": "checkbox",
                },
                {
                    "subTitle": "",
                    "model": "keteranganinfoMedis",
                    "type": "textbox",
                },
            ]
        },
        {
            "title": "Persetujuan Tindakan Medis (Consent)",
            "value": [
                {
                    "subTitle": "Ada",
                    "model": "Consent",
                    "type": "checkbox",
                },
                {
                    "subTitle": "Tidak Ada",
                    "model": "Consent",
                    "type": "checkbox",
                },
                {
                    "subTitle": "",
                    "model": "keteranganConsent",
                    "type": "textbox",
                },
            ]
        },
        {
            "title": "Persetujuan Masuk Ruangan Intensif (jika diperlukan)",
            "value": [
                {
                    "subTitle": "Ada",
                    "model": "persetujuanIntensif",
                    "type": "checkbox",
                },
                {
                    "subTitle": "Tidak Ada",
                    "model": "persetujuanIntensif",
                    "type": "checkbox",
                },
                {
                    "subTitle": "",
                    "model": "keteranganpersetujuanIntensif",
                    "type": "textbox",
                },
            ]
        },
        {
            "title": "Rekam Medis",
            "value": [
                {
                    "subTitle": "Ada",
                    "model": "rekamMedis",
                    "type": "checkbox",
                },
                {
                    "subTitle": "Tidak Ada",
                    "model": "rekamMedis",
                    "type": "checkbox",
                },
                {
                    "subTitle": "",
                    "model": "keteranganrekamMedis",
                    "type": "textbox",
                },
            ]
        },
    ]
}

export function generalAnestesi(): any {
    return[
        {
            "title": "Konsul Dokter Anestesi",
            "value": [
                {
                    "subTitle": "Ada",
                    "model": "konsulDokter",
                    "type": "checkbox",
                },
                {
                    "subTitle": "Tidak Ada",
                    "model": "konsulDokter",
                    "type": "checkbox",
                },
                {
                    "subTitle": "",
                    "model": "keterangankonsulDokter",
                    "type": "textbox",
                },
            ]
        }, 
        {
            "title": "Pemberian Informasi Tindakan Anestesi",
            "value": [
                {
                    "subTitle": "Ada",
                    "model": "informasitindakanAnestesi",
                    "type": "checkbox",
                },
                {
                    "subTitle": "Tidak Ada",
                    "model": "tindakanAnestesi",
                    "type": "checkbox",
                },
                {
                    "subTitle": "",
                    "model": "tindakanAnestesi",
                    "type": "textbox",
                },
            ]
        }, 
        {
            "title": "Persetujuan Tindakan Anestesi",
            "value": [
                {
                    "subTitle": "Ada",
                    "model": "persetujuantindakanAnestesi",
                    "type": "checkbox",
                },
                {
                    "subTitle": "Tidak Ada",
                    "model": "persetujuantindakanAnestesi",
                    "type": "checkbox",
                },
                {
                    "subTitle": "",
                    "model": "persetujuantindakanAnestesi",
                    "type": "textbox",
                },
            ]
        },
        {
            "title": "Konfirmasi Jadwal Tindakan ke Kamar Operasi",
            "value": [
                {
                    "subTitle": "Ada",
                    "model": "persetujuantindakanAnestesi",
                    "type": "checkbox",
                },
                {
                    "subTitle": "Tidak Ada",
                    "model": "persetujuantindakanAnestesi",
                    "type": "checkbox",
                },
                {
                    "subTitle": "",
                    "model": "persetujuantindakanAnestesi",
                    "type": "textbox",
                },
            ]
        },
    ]
}

export function fisikPasien(): any {
    return [
        {
            "title": "Cukur Daerah Inguinal Kiri dan Kanan",
            "value": [
                {
                    "subTitle": "Ya",
                    "model": "fisikPasien",
                    "type": "checkbox",
                },
                {
                    "subTitle": "Tidak",
                    "model": "konsulDokter",
                    "type": "checkbox",
                },
                {
                    "subTitle": "",
                    "model": "keteranganfisikPasien",
                    "type": "textbox",
                },
            ]
        }, 
        {
            "title": "Pasang Infus Lengan (Kiri / Kanan*)(utamakan tangan kiri)",
            "value": [
                {
                    "subTitle": "Ya",
                    "model": "pasangInfus",
                    "type": "checkbox",
                },
                {
                    "subTitle": "Tidak",
                    "model": "pasangInfus",
                    "type": "checkbox",
                },
                {
                    "subTitle": "",
                    "model": "keteranganpasangInfus",
                    "type": "textbox",
                },
            ]
        }, 
        {
            "title": "Pasang Kondom Kateter / Diapers*",
            "value": [
                {
                    "subTitle": "Ya",
                    "model": "pasangInfus",
                    "type": "checkbox",
                },
                {
                    "subTitle": "Tidak",
                    "model": "pasangInfus",
                    "type": "checkbox",
                },
                {
                    "subTitle": "",
                    "model": "keteranganpasangInfus",
                    "type": "textbox",
                },
            ]
        }, 
        {
            "title": "Memakai Baju Khusus Pasien (Pakaian Dalam Dilepas)",
            "value": [
                {
                    "subTitle": "Ya",
                    "model": "pasangInfus",
                    "type": "checkbox",
                },
                {
                    "subTitle": "Tidak",
                    "model": "pasangInfus",
                    "type": "checkbox",
                },
                {
                    "subTitle": "",
                    "model": "keteranganpasangInfus",
                    "type": "textbox",
                },
            ]
        },
    ]
}
export function pemeriksaanPenunjang(): any {
    return[
        {
            "title": "Laboratorium (Darah Rutin, PT, APTT, Ureum, Kreatinin, HbsAg, GDS)",
            "value": [
                {
                    "subTitle": "Ya",
                    "model": "laboratorium",
                    "type": "checkbox",
                },
                {
                    "subTitle": "Tidak",
                    "model": "laboratorium",
                    "type": "checkbox",
                },
                {
                    "subTitle": "",
                    "model": "keteranganLaboratorium",
                    "type": "textbox",
                },
            ]
        },
        {
            "title": "EKG 12 Lead",
            "value": [
                {
                    "subTitle": "Ya",
                    "model": "EKG",
                    "type": "checkbox",
                },
                {
                    "subTitle": "Tidak",
                    "model": "EKG",
                    "type": "checkbox",
                },
                {
                    "subTitle": "",
                    "model": "keteranganEKG",
                    "type": "textbox",
                },
            ]
        },
    ]
}
export function pemeriksaanTambahan(): any {
    return[
        {
            "title": "Angiografi",
            "value": [
                {
                    "subTitle": "Ya",
                    "model": "Angiografi",
                    "type": "checkbox",
                },
                {
                    "subTitle": "Tidak",
                    "model": "Angiografi",
                    "type": "checkbox",
                },
                {
                    "subTitle": "",
                    "model": "keteranganAngiografi",
                    "type": "textbox",
                },
            ]
        },
        {
            "title": "Ekokardiografi",
            "value": [
                {
                    "subTitle": "Ya",
                    "model": "Ekokardiografi",
                    "type": "checkbox",
                },
                {
                    "subTitle": "Tidak",
                    "model": "Ekokardiografi",
                    "type": "checkbox",
                },
                {
                    "subTitle": "",
                    "model": "keteranganEkokardiografi",
                    "type": "textbox",
                },
            ]
        },
        {
            "title": "CT Scan Cardiac/Kepala/Peripheral*",
            "value": [
                {
                    "subTitle": "Ya",
                    "model": "Peripheral",
                    "type": "checkbox",
                },
                {
                    "subTitle": "Tidak",
                    "model": "Peripheral",
                    "type": "checkbox",
                },
                {
                    "subTitle": "",
                    "model": "keteranganPeripheral",
                    "type": "textbox",
                },
            ]
        },
        {
            "title": "Treadmill Test",
            "value": [
                {
                    "subTitle": "Ya",
                    "model": "Treadmill",
                    "type": "checkbox",
                },
                {
                    "subTitle": "Tidak",
                    "model": "Treadmill",
                    "type": "checkbox",
                },
                {
                    "subTitle": "",
                    "model": "keteranganTreadmill",
                    "type": "textbox",
                },
            ]
        },
        {
            "title": "Sidik Perfusi Miokard (SPM)",
            "value": [
                {
                    "subTitle": "Ya",
                    "model": "SPM",
                    "type": "checkbox",
                },
                {
                    "subTitle": "Tidak",
                    "model": "SPM",
                    "type": "checkbox",
                },
                {
                    "subTitle": "",
                    "model": "keteranganSPM",
                    "type": "textbox",
                },
            ]
        },
        {
            "title": "Foto Thorax",
            "value": [
                {
                    "subTitle": "Ya",
                    "model": "Thorax",
                    "type": "checkbox",
                },
                {
                    "subTitle": "Tidak",
                    "model": "Thorax",
                    "type": "checkbox",
                },
                {
                    "subTitle": "",
                    "model": "keteranganThorax",
                    "type": "textbox",
                },
            ]
        },
        {
            "title": "USG",
            "value": [
                {
                    "subTitle": "Ya",
                    "model": "USG",
                    "type": "checkbox",
                },
                {
                    "subTitle": "Tidak",
                    "model": "USG",
                    "type": "checkbox",
                },
                {
                    "subTitle": "",
                    "model": "keteranganUSG",
                    "type": "textbox",
                },
            ]
        },
    ]
}
export function farmakologi(): any {
    return[
        {
            "title": "Metformin Sudah di Stop/Tunda",
            "value": [
                {
                    "subTitle": "Ya",
                    "model": "stopMetformin",
                    "type": "checkbox",
                },
                {
                    "subTitle": "Tidak",
                    "model": "stopMetformin",
                    "type": "checkbox",
                },
                {
                    "subTitle": "",
                    "model": "keteranganstopMetformin",
                    "type": "textbox",
                },
            ]
        },
        {
            "title": "Anti koagulan Sudah di Stop/Tunda (tindakan PPM, ICD, CRT)",
            "value": [
                {
                    "subTitle": "Ya",
                    "model": "stopAntikoagulan",
                    "type": "checkbox",
                },
                {
                    "subTitle": "Tidak",
                    "model": "stopAntikoagulan",
                    "type": "checkbox",
                },
                {
                    "subTitle": "",
                    "model": "keteranganstopAntikoagulan",
                    "type": "textbox",
                },
            ]
        },
        {
            "title": "Antibiotik Profilaksis Sudah Diberikan (tindakan PPM, ICD, CRT)",
            "value": [
                {
                    "subTitle": "Ya",
                    "model": "AntibiotikProfilaksis",
                    "type": "checkbox",
                },
                {
                    "subTitle": "Tidak",
                    "model": "AntibiotikProfilaksis",
                    "type": "checkbox",
                },
                {
                    "subTitle": "",
                    "model": "keteranganAntibiotikProfilaksis",
                    "type": "textbox",
                },
            ]
        },
    ]
}