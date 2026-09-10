export function kriteria(): any {
  return [
    {
      label: "KRITERIA KLINIS",
      detail: [
        { deskripsi: "Nyeri dada khas angina atau ekuivalen angina", model: "kriteriaKlinisAngina" },
        { deskripsi: "Sesak nafas yang terjadi saat istirahat, tidak membaik dengan posisi duduk", model: "kriteriaKlinisSesakNafas" },
        { deskripsi: "Angina class (sesuai kriteria Canadian Cardiovascular Society) yang memburuk", model: "kriteriaKlinisAnginaClass" },
        { deskripsi: "Palpitasi yang menyebabkan gejala ketidakstabilan hemodinamik", model: "kriteriaKlinisPalpitasi" },
        { deskripsi: "Sesak nafas atau dyspnea on effort dengan klas fungsional gagal jantung yang memburuk (sesuai klas fungsional NYHA)", model: "kriteriaKlinisDyspnea" }
      ]
    },
    {
      label: "KRITERIA VITAL SIGN",
      detail: [
        { deskripsi: "Nadi <40 atau >150 kali/menit", model: "kriteriaVitalSignNadi" },
        { deskripsi: "Tekanan darah sistolik < 90 mmHg atau penurunan 20 mmHg dari tekanan darah sistolik biasanya", model: "kriteriaVitalSignTekananDarah" },
        { deskripsi: "Mean Arterial Pressure <50 mmHg atau >150 mmHg", model: "kriteriaVitalSignMAP" },
        { deskripsi: "Laju respirasi > 35 kali/menit", model: "kriteriaVitalSignRespirasi" }
      ]
    },
    {
      label: "NILAI LABORATORIUM & RADIOLOGI",
      detail: [
        { deskripsi: "Peningkatan Troponin yang signifikan yang menyokong IMA atau Miokarditis (Troponin > 100 atau peningkatan 20% dari baseline dalam 6 jam)", model: "kriteriaLabTroponin" },
        { deskripsi: "Diseksi aorta dan aorta kritis dari CT scan", model: "kriteriaLabDiseksiAorta" },
        { deskripsi: "Kardiomegali dengan CTR >70% menyokong kecurigaan efusi perikard atau tamponade", model: "kriteriaLabKardiomegali" }
      ]
    },
    {
      label: "KRITERIA ECG",
      detail: [
        { deskripsi: "ST Elevasi spesifik 1mm pada lead II, III, Avf; V3-V6, I, aVL", model: "kriteriaECGSTElevasi" },
        { deskripsi: "ST Depresi horizontal atau downsloping 1mm pada lead yang kompleks bersesuaian pada SKA", model: "kriteriaECGSTDepresi" },
        { deskripsi: "S1 Q3 T3 yang menyokong klinis emboli paru", model: "kriteriaECSQ3T3" },
        { deskripsi: "PR depresi yang menyokong klinis pericarditis akut", model: "kriteriaECGPRDepresi" },
        { deskripsi: "LBBB pada IMA atau gagal jantung", model: "kriteriaECGLBBB" },
        { deskripsi: "RBBB dengan gambar saddle back", model: "kriteriaECGRBBB" },
        { deskripsi: "Syndrome Brugada type I dan II", model: "kriteriaECGBrugada" },
        { deskripsi: "VT stabil", model: "kriteriaECGVTStabil" },
        { deskripsi: "Bradicardi dengan HR < 50x/menit", model: "kriteriaECGBradicardi" },
        { deskripsi: "AF RVR >150x/menit", model: "kriteriaECGAFRVR" }
      ]
    },
    {
      label: "KRITERIA DIAGNOSIS",
      detail: [
        { deskripsi: "Syok kardiogenik", model: "kriteriaSyokKardiogenik" },
        { deskripsi: "Aritma jantung yang mengancam jiwa sebagai akiat dari penyakir jantung iskemik, kardiomiopati, penyakit jantung reumatik, gangguan elektrolit, efek obat atau keracunan", model: "kriteriaAritmaJantung" },
        { deskripsi: "Edema paru akut yang tidak teratasi dengan terapi awal dan tergantung dari penyakit dasarnya", model: "kriteriaEdemaParu" },
        { deskripsi: "Hipertensi emergency", model: "kriteriaHipertensi" },
        { deskripsi: "Emboli paru masif", model: "kriteriaEmboliParu" },
        { deskripsi: "Hipertensi pulmonal", model: "kriteriaHipertensiPulmonal" },
      ]
    }
  ];
}



export function JenisKelamin():any{
  return [
    { label: 'Laki-laki', value: 'Laki-laki' },
    { label: 'Perempuan', value: 'Perempuan' },
  ]
}
