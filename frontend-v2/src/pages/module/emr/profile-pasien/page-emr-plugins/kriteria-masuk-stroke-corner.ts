export function kriteria(): any {
  return [
    {
      label:"Post terapi trombolisis intravena",
      model:"postTerapiTrombolidIntravena"
    },
    {
      label:"Stroke akut tanpa ancaman gagal nafas: saturasi O₂ ≤ 84%, respirasi ≥ 30, PaO₂ < 50 mmHg, PaCO₂ > 50 mmHg, GCS ≥ 8",
      model:"strokeAkutTanpaAncamanGagalNafas"
    },
    {
      label:"Defisit neurologi tidak stabil atau progesif",
      model:"defisitNeurologi"
    },
    {
      label:"Membutuhkan rehabilitasi dini atau memerlukan penanganan multidisipliner ilmu",
      model:"penangananMultiDisiplinerIlmu"
    }
  ];
}





export function JenisKelamin():any{
  return [
    { label: 'Laki-laki', value: 'Laki-laki' },
    { label: 'Perempuan', value: 'Perempuan' },
  ]
}
