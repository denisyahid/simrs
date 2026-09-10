<style>
.tabels thead tr td {
  border: 1px solid black;
}

.tabels tbody tr td {
  border: 1px solid black;
}
</style>
<template>
  <div class="form-layout is-stacked-2">
    <div class="form-outer" style="margin-top:15px">
      <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
        <div class="form-header-inner">
          <div class="left">
            <h3> {{ props.FORM_NAME }}</h3>
          </div>
          <!-- <div class="right" v-if="props.registrasi.objectruanganlastfk != 212">
                        <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading"
                            @simpan="simpan" @kembaliKeun="kembaliKeun"></ButtonEmr>
                    </div> -->
          <div class="right">
            <div class="buttons">
              <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="kembaliKeun()">
                Kembali
              </VButton>
              <VButton type="button" rounded outlined color="primary" raised icon="feather:save" :loading="isLoading"
                @click="simpan()" :disabled="disabledSave">
                Simpan
              </VButton>
              <VButton type="button" rounded outlined color="warning" raised icon="feather:save" :loading="isLoading"
                @click="updateTemplate()"> Simpan Template
              </VButton>
            </div>
          </div>
        </div>
      </div>

      <div class="form-body p-2">
        <div class="columns is-multiline" style="margin-top: 50px;">
          <div class="column is-12">
            <TabMenu :model="[]" :scrollable="true" />
            <div class="p-tabmenu-uy">
              <div class="p-tabmenu p-component" ref="contentRefScrollMenu">
                <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                  <li class="p-tabmenuitem" :class="[TAB_ITEMS == 'CPPT' || TAB_DEFAULT == 'CPPT' ? 'active' : '']"
                    role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                      @click="isRiwayat = true, isInputData = false, loadRiwayat()"><span
                        class="p-menuitem-icon fas fa-book-medical"></span><span
                        class="p-menuitem-text">Riwayat</span></a>
                  </li>
                </ul>
                <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                  <li class="p-tabmenuitem"
                    :class="[TAB_ACTIVE == 'Dashboard' || TAB_DEFAULT == 'Dashboard' ? 'active' : '']"
                    role="presentation"><a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                      @click="isInputData = true, isRiwayat = false, disabledSave = false">
                      <span class="p-menuitem-icon fas fa-laptop-medical"></span><span class="p-menuitem-text">Input
                        Data</span></a>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>

        <div class="column is-12" v-if="isRiwayat === true && isInputData === false">
          <div class="columns is-multiline">
            <!-- Perulangan untuk setiap jenis tindakan -->
            <div v-for="(items, jenisTindakan) in listTemplate.value" :key="jenisTindakan" class="column is-12">
              <!-- <pre>{{ listTemplate }} </pre> -->
              <!-- Judul Jenis Tindakan -->
              <div class="box">
                <h2 class="title is-5">{{ jenisTindakan }}</h2>
                <!-- Tabel untuk jenis tindakan -->
                <table class="table-pri" v-if="items.length > 0" width="100%">
                  <thead style="border-bottom: 1px solid #c7c8c9">
                    <tr>
                      <td class="td-pri text-center" width="10%">Cetak</td>
                      <td class="td-pri text-center" width="10%">Nomor</td>
                      <td class="td-pri text-center" width="15%">Tanggal Input</td>
                      <td class="td-pri text-center" width="15%">Tanggal Registrasi</td>
                      <td class="td-pri text-center" width="10%">No Registrasi</td>
                      <td class="td-pri text-center" width="15%">Dokter</td>
                      <td class="td-pri text-center" width="15%">Section</td>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="resep in items" :key="resep.noemr">
                      <td style="text-align: center" class="td-pri">
                        <!-- <VButtons> -->
                        <VIconButton type="button" raised circle icon="feather:printer" @click="cetakSalahSatu(resep)"
                          color="warning" v-tooltip.bubble="'Cetak Penunjang'"></VIconButton>
                        <VIconButton type="button" raised circle icon="feather:edit" @click="editData(resep)"
                          color="info" v-tooltip.bubble="'Edit Data'"></VIconButton>
                        <VIconButton type="button" raised circle icon="feather:trash" @click="deleteSalahSatu(resep)"
                          color="danger" v-tooltip.bubble="'Hapus Data'"></VIconButton>
                        <!-- </VButtons> -->
                      </td>
                      <td style="text-align: center" class="td-pri">
                        <span class="mb-2">{{ resep.noemr }}</span>
                      </td>
                      <td style="text-align: center" class="td-pri">
                        <span class="mb-2">{{ resep.created_at }}</span>
                      </td>
                      <td style="text-align: center" class="td-pri">
                        <span class="mb-2">{{ resep.registrasi.tglregistrasi }}</span>
                      </td>
                      <td style="text-align: center" class="td-pri">
                        <span class="mb-2">{{ resep.registrasi.noregistrasi }}</span>
                      </td>
                      <td style="text-align: center" class="td-pri">
                        <span class="mb-2">{{ resep.registrasi.dokter }}</span>
                      </td>
                      <td style="text-align: center" class="td-pri">
                        <span class="mb-2">{{ resep.registrasi.namaruangan }}</span>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>

        <div class="column is-12 pt-0" v-if="isInputData === true && isRiwayat === false">
          <!-- <div class="columns is-multiline">
            <div class="column is-9 pt-0">
              <h1>Nama Template&emsp;&emsp;<span style="color: rgb(230, 41, 100);">**Hanya diisi jika ingin
                  membuat template</span></h1>
              <VField>
                <VControl>
                  <VInput type="text" class="input" v-model="input.namatemplate" />
                </VControl>
              </VField>
            </div>
            <div class="column is-3 mt-auto">
              <VButton type="button" rounded outlined color="primary" raised icon="feather:folder" :loading="isLoading"
                @click="pilihTemplateFix(index)"> Pilih Template
              </VButton>
            </div>
          </div> -->
        </div>
        <!-- RUANGAN INPUT PENUNJANG INTERNA -->
        <div class="column is-12"
          v-if="isInputData === true && isRiwayat === false && props.registrasi.objectruanganlastfk == 201">
          <VCard>
            <div class="column is-12">
              <div class="columns is-multiline">
                <div class="column is-6" style="display: none !important">
                  <div class="columns is-multiline">
                    <div class="column is-6">
                      <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> NRM </h1>
                      <VField>
                        <VField>
                          <VControl>
                            <VInput type="text" class="input" placeholder="NRM" v-model="input.nrm" />
                          </VControl>
                        </VField>
                      </VField>
                    </div>
                    <div class="column is-6">
                      <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> Age </h1>
                      <VField>
                        <VField addons>
                          <VControl expanded>
                            <VInput type="text" class="input" placeholder="Age" v-model.number="input.age" />
                          </VControl>
                          <VControl class="field-addon-body">
                            <VButton static>Tahun</VButton>
                          </VControl>
                        </VField>
                      </VField>
                    </div>
                  </div>
                </div>

                <br>
                <hr>
                <br>

                <div class="column is-10">
                  <h1 style="font-weight: bold; margin-bottom: 0.5rem;">Pemeriksaan EKG Penunjang Poli Interna</h1>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl>
                      <VCheckbox class="fontcheckbox" v-model="input.batasnormalkepala" true-value="Dalam Batas Normal"
                        label="Dalam Batas Normal" color="primary" circle @click="normalEKG" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6" style="margin-top: 20px;">
                  <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> P wave </h1>
                  <VField>
                    <VField>
                      <VControl>
                        <VInput type="text" class="input" placeholder="P wave" v-model="input.Pwave" />
                      </VControl>
                    </VField>
                  </VField>
                </div>
                <div class="column is-6" style="margin-top: 20px;">
                  <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> HR </h1>
                  <VField>
                    <VField>
                      <VControl>
                        <VInput type="text" class="input" placeholder="HR" v-model="input.HR" />
                      </VControl>
                    </VField>
                  </VField>
                </div>
                <div class="column is-6">
                  <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> PR Interval </h1>
                  <VField>
                    <VField>
                      <VControl>
                        <VInput type="text" class="input" placeholder="PR Interval" v-model="input.PRInterval" />
                      </VControl>
                    </VField>
                  </VField>
                </div>
                <div class="column is-6">
                  <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> Axis </h1>
                  <VField>
                    <VField>
                      <VControl>
                        <VInput type="text" class="input" placeholder="Axis" v-model="input.Axis" />
                      </VControl>
                    </VField>
                  </VField>
                </div>
                <div class="column is-6">
                  <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> QRS </h1>
                  <VField>
                    <VField>
                      <VControl>
                        <VInput type="text" class="input" placeholder="QRS" v-model="input.QRS" />
                      </VControl>
                    </VField>
                  </VField>
                </div>
                <div class="column is-6">
                  <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> Other </h1>
                  <VField>
                    <VField>
                      <VControl>
                        <VInput type="text" class="input" placeholder="Other" v-model="input.Other" />
                      </VControl>
                    </VField>
                  </VField>
                </div>
                <div class="column is-6">
                  <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> ST Segment </h1>
                  <VField>
                    <VField>
                      <VControl>
                        <VInput type="text" class="input" placeholder="ST Segment" v-model="input.STSegment" />
                      </VControl>
                    </VField>
                  </VField>
                </div>
                <div class="column is-6">
                  <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> T wave </h1>
                  <VField>
                    <VField>
                      <VControl>
                        <VInput type="text" class="input" placeholder="T wave" v-model="input.Twave" />
                      </VControl>
                    </VField>
                  </VField>
                </div>
                <div class="column is-6">
                  <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> QT Interval </h1>
                  <VField>
                    <VField>
                      <VControl>
                        <VInput type="text" class="input" placeholder="QT Interval" v-model="input.QTInterval" />
                      </VControl>
                    </VField>
                  </VField>
                </div>
                <div class="column is-6">
                  <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> Conclusion </h1>
                  <VField>
                    <VField>
                      <VControl>
                        <VTextarea class="textarea" v-model="input.conclusion" rows="5" placeholder="Conclusion"
                          autocomplete="off" autocapitalize="off" spellcheck="true" />
                      </VControl>
                    </VField>
                  </VField>
                </div>
              </div>
            </div>
          </VCard>
        </div>
        <div class="column is-12"
          v-if="isInputData === true && isRiwayat === false && props.registrasi.objectruanganlastfk == 401">
          <VCard>
            <div class="column is-12">
              <div class="columns is-multiline">
                <div class="column is-6" style="display: none !important">
                  <div class="columns is-multiline">
                    <div class="column is-6">
                      <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> NRM </h1>
                      <VField>
                        <VField>
                          <VControl>
                            <VInput type="text" class="input" placeholder="NRM" v-model="input.nrm" />
                          </VControl>
                        </VField>
                      </VField>
                    </div>
                    <div class="column is-6">
                      <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> Age </h1>
                      <VField>
                        <VField addons>
                          <VControl expanded>
                            <VInput type="text" class="input" placeholder="Age" v-model.number="input.age" />
                          </VControl>
                          <VControl class="field-addon-body">
                            <VButton static>Tahun</VButton>
                          </VControl>
                        </VField>
                      </VField>
                    </div>
                  </div>
                </div>

                <br>
                <hr>
                <br>

                <div class="column is-10">
                  <h1 style="font-weight: bold; margin-bottom: 0.5rem;">Pemeriksaan EKG Penunjang Poli Interna</h1>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl>
                      <VCheckbox class="fontcheckbox" v-model="input.batasnormalkepala" true-value="Dalam Batas Normal"
                        label="Dalam Batas Normal" color="primary" circle @click="normalEKG" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6" style="margin-top: 20px;">
                  <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> P wave </h1>
                  <VField>
                    <VField>
                      <VControl>
                        <VInput type="text" class="input" placeholder="P wave" v-model="input.Pwave" />
                      </VControl>
                    </VField>
                  </VField>
                </div>
                <div class="column is-6" style="margin-top: 20px;">
                  <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> HR </h1>
                  <VField>
                    <VField>
                      <VControl>
                        <VInput type="text" class="input" placeholder="HR" v-model="input.HR" />
                      </VControl>
                    </VField>
                  </VField>
                </div>
                <div class="column is-6">
                  <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> PR Interval </h1>
                  <VField>
                    <VField>
                      <VControl>
                        <VInput type="text" class="input" placeholder="PR Interval" v-model="input.PRInterval" />
                      </VControl>
                    </VField>
                  </VField>
                </div>
                <div class="column is-6">
                  <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> Axis </h1>
                  <VField>
                    <VField>
                      <VControl>
                        <VInput type="text" class="input" placeholder="Axis" v-model="input.Axis" />
                      </VControl>
                    </VField>
                  </VField>
                </div>
                <div class="column is-6">
                  <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> QRS </h1>
                  <VField>
                    <VField>
                      <VControl>
                        <VInput type="text" class="input" placeholder="QRS" v-model="input.QRS" />
                      </VControl>
                    </VField>
                  </VField>
                </div>
                <div class="column is-6">
                  <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> Other </h1>
                  <VField>
                    <VField>
                      <VControl>
                        <VInput type="text" class="input" placeholder="Other" v-model="input.Other" />
                      </VControl>
                    </VField>
                  </VField>
                </div>
                <div class="column is-6">
                  <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> ST Segment </h1>
                  <VField>
                    <VField>
                      <VControl>
                        <VInput type="text" class="input" placeholder="ST Segment" v-model="input.STSegment" />
                      </VControl>
                    </VField>
                  </VField>
                </div>
                <div class="column is-6">
                  <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> T wave </h1>
                  <VField>
                    <VField>
                      <VControl>
                        <VInput type="text" class="input" placeholder="T wave" v-model="input.Twave" />
                      </VControl>
                    </VField>
                  </VField>
                </div>
                <div class="column is-6">
                  <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> QT Interval </h1>
                  <VField>
                    <VField>
                      <VControl>
                        <VInput type="text" class="input" placeholder="QT Interval" v-model="input.QTInterval" />
                      </VControl>
                    </VField>
                  </VField>
                </div>
                <div class="column is-6">
                  <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> Conclusion </h1>
                  <VField>
                    <VField>
                      <VControl>
                        <VTextarea class="textarea" v-model="input.conclusion" rows="5" placeholder="Conclusion"
                          autocomplete="off" autocapitalize="off" spellcheck="true" />
                      </VControl>
                    </VField>
                  </VField>
                </div>
              </div>
            </div>
          </VCard>
        </div>
        <div class="column is-12"
          v-if="isInputData === true && isRiwayat === false && props.registrasi.objectruanganlastfk == 365">
          <VCard>
            <div class="column is-12">
              <div class="columns is-multiline">
                <div class="column is-6" style="display: none !important">
                  <div class="columns is-multiline">
                    <div class="column is-6">
                      <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> NRM </h1>
                      <VField>
                        <VField>
                          <VControl>
                            <VInput type="text" class="input" placeholder="NRM" v-model="input.nrm" />
                          </VControl>
                        </VField>
                      </VField>
                    </div>
                    <div class="column is-6">
                      <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> Age </h1>
                      <VField>
                        <VField addons>
                          <VControl expanded>
                            <VInput type="text" class="input" placeholder="Age" v-model.number="input.age" />
                          </VControl>
                          <VControl class="field-addon-body">
                            <VButton static>Tahun</VButton>
                          </VControl>
                        </VField>
                      </VField>
                    </div>
                  </div>
                </div>

                <br>
                <hr>
                <br>

                <div class="column is-10">
                  <h1 style="font-weight: bold; margin-bottom: 0.5rem;">Pemeriksaan EKG Penunjang Poli Interna</h1>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl>
                      <VCheckbox class="fontcheckbox" v-model="input.batasnormalkepala" true-value="Dalam Batas Normal"
                        label="Dalam Batas Normal" color="primary" circle @click="normalEKG" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6" style="margin-top: 20px;">
                  <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> P wave </h1>
                  <VField>
                    <VField>
                      <VControl>
                        <VInput type="text" class="input" placeholder="P wave" v-model="input.Pwave" />
                      </VControl>
                    </VField>
                  </VField>
                </div>
                <div class="column is-6" style="margin-top: 20px;">
                  <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> HR </h1>
                  <VField>
                    <VField>
                      <VControl>
                        <VInput type="text" class="input" placeholder="HR" v-model="input.HR" />
                      </VControl>
                    </VField>
                  </VField>
                </div>
                <div class="column is-6">
                  <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> PR Interval </h1>
                  <VField>
                    <VField>
                      <VControl>
                        <VInput type="text" class="input" placeholder="PR Interval" v-model="input.PRInterval" />
                      </VControl>
                    </VField>
                  </VField>
                </div>
                <div class="column is-6">
                  <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> Axis </h1>
                  <VField>
                    <VField>
                      <VControl>
                        <VInput type="text" class="input" placeholder="Axis" v-model="input.Axis" />
                      </VControl>
                    </VField>
                  </VField>
                </div>
                <div class="column is-6">
                  <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> QRS </h1>
                  <VField>
                    <VField>
                      <VControl>
                        <VInput type="text" class="input" placeholder="QRS" v-model="input.QRS" />
                      </VControl>
                    </VField>
                  </VField>
                </div>
                <div class="column is-6">
                  <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> Other </h1>
                  <VField>
                    <VField>
                      <VControl>
                        <VInput type="text" class="input" placeholder="Other" v-model="input.Other" />
                      </VControl>
                    </VField>
                  </VField>
                </div>
                <div class="column is-6">
                  <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> ST Segment </h1>
                  <VField>
                    <VField>
                      <VControl>
                        <VInput type="text" class="input" placeholder="ST Segment" v-model="input.STSegment" />
                      </VControl>
                    </VField>
                  </VField>
                </div>
                <div class="column is-6">
                  <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> T wave </h1>
                  <VField>
                    <VField>
                      <VControl>
                        <VInput type="text" class="input" placeholder="T wave" v-model="input.Twave" />
                      </VControl>
                    </VField>
                  </VField>
                </div>
                <div class="column is-6">
                  <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> QT Interval </h1>
                  <VField>
                    <VField>
                      <VControl>
                        <VInput type="text" class="input" placeholder="QT Interval" v-model="input.QTInterval" />
                      </VControl>
                    </VField>
                  </VField>
                </div>
                <div class="column is-6">
                  <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> Conclusion </h1>
                  <VField>
                    <VField>
                      <VControl>
                        <VTextarea class="textarea" v-model="input.conclusion" rows="5" placeholder="Conclusion"
                          autocomplete="off" autocapitalize="off" spellcheck="true" />
                      </VControl>
                    </VField>
                  </VField>
                </div>
              </div>
            </div>
          </VCard>
        </div>
        <!-- RUANGAN INPUT PENUNJANG INTERNA -->

        <!-- RUANGAN INPUT PENUNJANG JANTUNG SORE -->
        <div class="column is-12"
          v-if="isInputData === true && isRiwayat === false && props.registrasi.objectruanganlastfk == 402">
          <VCard>
            <div class="column is-12">
              <div class="columns is-multiline">
                <div class="column is-6" style="display: none !important">
                  <div class="columns is-multiline">
                    <div class="column is-6">
                      <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> NRM </h1>
                      <VField>
                        <VField>
                          <VControl>
                            <VInput type="text" class="input" placeholder="NRM" v-model="input.nrm" />
                          </VControl>
                        </VField>
                      </VField>
                    </div>
                    <div class="column is-6">
                      <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> Age </h1>
                      <VField>
                        <VField addons>
                          <VControl expanded>
                            <VInput type="text" class="input" placeholder="Age" v-model.number="input.age" />
                          </VControl>
                          <VControl class="field-addon-body">
                            <VButton static>Tahun</VButton>
                          </VControl>
                        </VField>
                      </VField>
                    </div>
                  </div>
                </div>

                <br>
                <hr>
                <br>

                <div class="column is-10">
                  <h1 style="font-weight: bold; margin-bottom: 0.5rem;">Pemeriksaan EKG Penunjang Poli Jantung Sore</h1>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl>
                      <VCheckbox class="fontcheckbox" v-model="input.batasnormalkepala" true-value="Dalam Batas Normal"
                        label="Dalam Batas Normal" color="primary" circle @click="normalEKG" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6" style="margin-top: 20px;">
                  <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> P wave </h1>
                  <VField>
                    <VField>
                      <VControl>
                        <VInput type="text" class="input" placeholder="P wave" v-model="input.Pwave" />
                      </VControl>
                    </VField>
                  </VField>
                </div>
                <div class="column is-6" style="margin-top: 20px;">
                  <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> HR </h1>
                  <VField>
                    <VField>
                      <VControl>
                        <VInput type="text" class="input" placeholder="HR" v-model="input.HR" />
                      </VControl>
                    </VField>
                  </VField>
                </div>
                <div class="column is-6">
                  <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> PR Interval </h1>
                  <VField>
                    <VField>
                      <VControl>
                        <VInput type="text" class="input" placeholder="PR Interval" v-model="input.PRInterval" />
                      </VControl>
                    </VField>
                  </VField>
                </div>
                <div class="column is-6">
                  <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> Axis </h1>
                  <VField>
                    <VField>
                      <VControl>
                        <VInput type="text" class="input" placeholder="Axis" v-model="input.Axis" />
                      </VControl>
                    </VField>
                  </VField>
                </div>
                <div class="column is-6">
                  <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> QRS </h1>
                  <VField>
                    <VField>
                      <VControl>
                        <VInput type="text" class="input" placeholder="QRS" v-model="input.QRS" />
                      </VControl>
                    </VField>
                  </VField>
                </div>
                <div class="column is-6">
                  <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> Other </h1>
                  <VField>
                    <VField>
                      <VControl>
                        <VInput type="text" class="input" placeholder="Other" v-model="input.Other" />
                      </VControl>
                    </VField>
                  </VField>
                </div>
                <div class="column is-6">
                  <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> ST Segment </h1>
                  <VField>
                    <VField>
                      <VControl>
                        <VInput type="text" class="input" placeholder="ST Segment" v-model="input.STSegment" />
                      </VControl>
                    </VField>
                  </VField>
                </div>
                <div class="column is-6">
                  <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> T wave </h1>
                  <VField>
                    <VField>
                      <VControl>
                        <VInput type="text" class="input" placeholder="T wave" v-model="input.Twave" />
                      </VControl>
                    </VField>
                  </VField>
                </div>
                <div class="column is-6">
                  <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> QT Interval </h1>
                  <VField>
                    <VField>
                      <VControl>
                        <VInput type="text" class="input" placeholder="QT Interval" v-model="input.QTInterval" />
                      </VControl>
                    </VField>
                  </VField>
                </div>
                <div class="column is-6">
                  <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> Conclusion </h1>
                  <VField>
                    <VField>
                      <VControl>
                        <VTextarea class="textarea" v-model="input.conclusion" rows="5" placeholder="Conclusion"
                          autocomplete="off" autocapitalize="off" spellcheck="true" />
                      </VControl>
                    </VField>
                  </VField>
                </div>
              </div>
            </div>
          </VCard>
        </div>
        <!-- RUANGAN INPUT PENUNJANG JANTUNG SORE -->
        <!-- RUANGAN INPUT PENUNJANG JANTUNG VIP -->
        <div class="column is-12"
          v-if="isInputData === true && isRiwayat === false && props.registrasi.objectruanganlastfk == 377">
          <VCard>
            <div class="column is-12">
              <div class="columns is-multiline">
                <div class="column is-6" style="display: none !important">
                  <div class="columns is-multiline">
                    <div class="column is-6">
                      <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> NRM </h1>
                      <VField>
                        <VField>
                          <VControl>
                            <VInput type="text" class="input" placeholder="NRM" v-model="input.nrm" />
                          </VControl>
                        </VField>
                      </VField>
                    </div>
                    <div class="column is-6">
                      <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> Age </h1>
                      <VField>
                        <VField addons>
                          <VControl expanded>
                            <VInput type="text" class="input" placeholder="Age" v-model.number="input.age" />
                          </VControl>
                          <VControl class="field-addon-body">
                            <VButton static>Tahun</VButton>
                          </VControl>
                        </VField>
                      </VField>
                    </div>
                  </div>
                </div>

                <br>
                <hr>
                <br>

                <div class="column is-10">
                  <h1 style="font-weight: bold; margin-bottom: 0.5rem;">Pemeriksaan EKG Penunjang Poli Jnatung VIP</h1>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl>
                      <VCheckbox class="fontcheckbox" v-model="input.batasnormalkepala" true-value="Dalam Batas Normal"
                        label="Dalam Batas Normal" color="primary" circle @click="normalEKG" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6" style="margin-top: 20px;">
                  <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> P wave </h1>
                  <VField>
                    <VField>
                      <VControl>
                        <VInput type="text" class="input" placeholder="P wave" v-model="input.Pwave" />
                      </VControl>
                    </VField>
                  </VField>
                </div>
                <div class="column is-6" style="margin-top: 20px;">
                  <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> HR </h1>
                  <VField>
                    <VField>
                      <VControl>
                        <VInput type="text" class="input" placeholder="HR" v-model="input.HR" />
                      </VControl>
                    </VField>
                  </VField>
                </div>
                <div class="column is-6">
                  <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> PR Interval </h1>
                  <VField>
                    <VField>
                      <VControl>
                        <VInput type="text" class="input" placeholder="PR Interval" v-model="input.PRInterval" />
                      </VControl>
                    </VField>
                  </VField>
                </div>
                <div class="column is-6">
                  <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> Axis </h1>
                  <VField>
                    <VField>
                      <VControl>
                        <VInput type="text" class="input" placeholder="Axis" v-model="input.Axis" />
                      </VControl>
                    </VField>
                  </VField>
                </div>
                <div class="column is-6">
                  <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> QRS </h1>
                  <VField>
                    <VField>
                      <VControl>
                        <VInput type="text" class="input" placeholder="QRS" v-model="input.QRS" />
                      </VControl>
                    </VField>
                  </VField>
                </div>
                <div class="column is-6">
                  <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> Other </h1>
                  <VField>
                    <VField>
                      <VControl>
                        <VInput type="text" class="input" placeholder="Other" v-model="input.Other" />
                      </VControl>
                    </VField>
                  </VField>
                </div>
                <div class="column is-6">
                  <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> ST Segment </h1>
                  <VField>
                    <VField>
                      <VControl>
                        <VInput type="text" class="input" placeholder="ST Segment" v-model="input.STSegment" />
                      </VControl>
                    </VField>
                  </VField>
                </div>
                <div class="column is-6">
                  <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> T wave </h1>
                  <VField>
                    <VField>
                      <VControl>
                        <VInput type="text" class="input" placeholder="T wave" v-model="input.Twave" />
                      </VControl>
                    </VField>
                  </VField>
                </div>
                <div class="column is-6">
                  <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> QT Interval </h1>
                  <VField>
                    <VField>
                      <VControl>
                        <VInput type="text" class="input" placeholder="QT Interval" v-model="input.QTInterval" />
                      </VControl>
                    </VField>
                  </VField>
                </div>
                <div class="column is-6">
                  <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> Conclusion </h1>
                  <VField>
                    <VField>
                      <VControl>
                        <VTextarea class="textarea" v-model="input.conclusion" rows="5" placeholder="Conclusion"
                          autocomplete="off" autocapitalize="off" spellcheck="true" />
                      </VControl>
                    </VField>
                  </VField>
                </div>
              </div>
            </div>
          </VCard>
        </div>
        <!-- RUANGAN INPUT PENUNJANG JANTUNG VIP -->
        <!-- RUANGAN INPUT PENUNJANG THT VIP -->
        <div class="column is-12"
          v-if="isInputData === true && isRiwayat === false && props.registrasi.objectruanganlastfk == 378">
          <VCard>
            <div class="column is-12">
              <div class="columns is-multiline">
                <div class="column is-6" style="display: none !important">
                  <div class="columns is-multiline">
                    <div class="column is-6">
                      <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> NRM </h1>
                      <VField>
                        <VField>
                          <VControl>
                            <VInput type="text" class="input" placeholder="NRM" v-model="input.nrm" />
                          </VControl>
                        </VField>
                      </VField>
                    </div>
                    <div class="column is-6">
                      <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> Age </h1>
                      <VField>
                        <VField addons>
                          <VControl expanded>
                            <VInput type="text" class="input" placeholder="Age" v-model.number="input.age" />
                          </VControl>
                          <VControl class="field-addon-body">
                            <VButton static>Tahun</VButton>
                          </VControl>
                        </VField>
                      </VField>
                    </div>
                  </div>
                </div>

                <br>
                <hr>
                <br>

                <div class="column is-10">
                  <h1 style="font-weight: bold; margin-bottom: 0.5rem;">Pemeriksaan EKG Penunjang Poli THT VIP</h1>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl>
                      <VCheckbox class="fontcheckbox" v-model="input.batasnormalkepala" true-value="Dalam Batas Normal"
                        label="Dalam Batas Normal" color="primary" circle @click="normalEKG" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6" style="margin-top: 20px;">
                  <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> P wave </h1>
                  <VField>
                    <VField>
                      <VControl>
                        <VInput type="text" class="input" placeholder="P wave" v-model="input.Pwave" />
                      </VControl>
                    </VField>
                  </VField>
                </div>
                <div class="column is-6" style="margin-top: 20px;">
                  <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> HR </h1>
                  <VField>
                    <VField>
                      <VControl>
                        <VInput type="text" class="input" placeholder="HR" v-model="input.HR" />
                      </VControl>
                    </VField>
                  </VField>
                </div>
                <div class="column is-6">
                  <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> PR Interval </h1>
                  <VField>
                    <VField>
                      <VControl>
                        <VInput type="text" class="input" placeholder="PR Interval" v-model="input.PRInterval" />
                      </VControl>
                    </VField>
                  </VField>
                </div>
                <div class="column is-6">
                  <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> Axis </h1>
                  <VField>
                    <VField>
                      <VControl>
                        <VInput type="text" class="input" placeholder="Axis" v-model="input.Axis" />
                      </VControl>
                    </VField>
                  </VField>
                </div>
                <div class="column is-6">
                  <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> QRS </h1>
                  <VField>
                    <VField>
                      <VControl>
                        <VInput type="text" class="input" placeholder="QRS" v-model="input.QRS" />
                      </VControl>
                    </VField>
                  </VField>
                </div>
                <div class="column is-6">
                  <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> Other </h1>
                  <VField>
                    <VField>
                      <VControl>
                        <VInput type="text" class="input" placeholder="Other" v-model="input.Other" />
                      </VControl>
                    </VField>
                  </VField>
                </div>
                <div class="column is-6">
                  <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> ST Segment </h1>
                  <VField>
                    <VField>
                      <VControl>
                        <VInput type="text" class="input" placeholder="ST Segment" v-model="input.STSegment" />
                      </VControl>
                    </VField>
                  </VField>
                </div>
                <div class="column is-6">
                  <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> T wave </h1>
                  <VField>
                    <VField>
                      <VControl>
                        <VInput type="text" class="input" placeholder="T wave" v-model="input.Twave" />
                      </VControl>
                    </VField>
                  </VField>
                </div>
                <div class="column is-6">
                  <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> QT Interval </h1>
                  <VField>
                    <VField>
                      <VControl>
                        <VInput type="text" class="input" placeholder="QT Interval" v-model="input.QTInterval" />
                      </VControl>
                    </VField>
                  </VField>
                </div>
                <div class="column is-6">
                  <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> Conclusion </h1>
                  <VField>
                    <VField>
                      <VControl>
                        <VTextarea class="textarea" v-model="input.conclusion" rows="5" placeholder="Conclusion"
                          autocomplete="off" autocapitalize="off" spellcheck="true" />
                      </VControl>
                    </VField>
                  </VField>
                </div>
              </div>
            </div>
          </VCard>
        </div>
        <!-- RUANGAN INPUT PENUNJANG THT VIP -->

        <!-- RUANGAN INPUT PENUNJANG OBGYN -->
        <div class="column is-12"
          v-if="isInputData === true && isRiwayat === false && props.registrasi.objectruanganlastfk == 202 || isInputData === true && isRiwayat === false && props.registrasi.objectruanganlastfk == 314 || isInputData === true && isRiwayat === false && props.registrasi.objectruanganlastfk == 310 || isInputData === true && isRiwayat === false && props.registrasi.objectruanganlastfk == 323">
          <VCard>
            <div class="column is-12">
              <div class="columns is-multiline">
                <div class="column is-2">
                  <h1 class="mb-3 emr">NRM</h1>
                </div>
                <div class="column is-4">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.norm" placeholder="NRM" class="is-rounded" disabled />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Tanggal/Jam</h1>
                </div>
                <div class="column is-4">
                  <!-- <VField>
                    <VControl>
                      <VInput type="text" v-model="input.tglPembuatan" placeholder="Tanggal/Jam" class="is-rounded"/>
                    </VControl>
                  </VField> -->
                  <VDatePicker v-model="input.tglPembuatan" mode="date" trim-weeks>
                    <template #default="{ inputValue, inputEvents }">
                      <VControl icon="feather:calendar" fullwidth>
                        <VInput :value="inputValue" v-on="inputEvents" />
                      </VControl>
                    </template>
                  </VDatePicker>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Nama Pasien</h1>
                </div>
                <div class="column is-4">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.namaPasien" placeholder="Nama Pasien" class="is-rounded"
                        disabled />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Dokter Pemeriksa</h1>
                </div>
                <div class="column is-4">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl icon="feather:search">
                      <AutoComplete v-model="input.dokterPemeriksa" :suggestions="d_Dokter"
                        @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                        :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder=""
                        class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Tanggal Lahir</h1>
                </div>
                <div class="column is-1">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.tanggalLahirPasien" placeholder="Tanggal Lahir"
                        class="is-rounded" disabled />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Umur</h1>
                </div>
                <div class="column is-1">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.umur" placeholder="Umur" class="is-rounded" disabled />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">HPHT</h1>
                </div>
                <div class="column is-4">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.hpht" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Alamat</h1>
                </div>
                <div class="column is-4">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.alamat" placeholder="Alamat" class="is-rounded" disabled />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Diagnosa</h1>
                </div>
                <div class="column is-4">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.diagnosa" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>

              </div>
            </div>
            <div class="column is-flex p-0">
              <div class="column is-2">
                <h1>Jenis Pemeriksaan:</h1>
              </div>
              <div class="column is-10">
                <VField class="is-autocomplete-select" v-slot="{ id }">
                  <VControl>
                    <Multiselect v-model="input.jenisPemeriksaanObgyn" placeholder="--Pilih--" label="label"
                      :options="jenisPemeriksaanObgyn" :searchable="true" track-by="label" mode="single"
                      autocomplete="off">
                    </Multiselect>
                  </VControl>
                </VField>
              </div>
            </div>

            <div class="column is-12 mt-5" v-if="input.jenisPemeriksaanObgyn == 'PemeriksaanUSGFetal'">
              <div class="columns is-multiline">
                <div class="column is-2">
                  <h1 class="mb-3 emr">Kondisi Teknis</h1>
                </div>
                <div class="column is-2">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.kondisiTeknisFetal" :attrs="{ value }" placeholder="" label="label"
                        :options="d_kondisiteknis" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Karena</h1>
                </div>
                <div class="column is-6">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.karenaFetal" placeholder="Karena" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Janin</h1>
                </div>
                <div class="column is-2">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.janin" :attrs="{ value }" placeholder="" label="label"
                        :options="d_janin" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Jumlah Janin</h1>
                </div>
                <div class="column is-6">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.jumlahJanin" placeholder="Karena" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Khorionisitas</h1>
                </div>
                <div class="column is-2">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.khorionisitas" :attrs="{ value }" placeholder="" label="label"
                        :options="d_khorionisitas" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-1">
                  <h1 class="mb-3 emr">DJJ</h1>
                </div>
                <div class="column is-2">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.djj" :attrs="{ value }" placeholder="" label="label" :options="d_djj"
                        class="is-rounded" :searchable="true" track-by="label" mode="single" autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField addons>
                    <VControl expanded>
                      <VInput type="text" class="input is-rounded" placeholder="" v-model="input.ketDJJ" />
                    </VControl>
                    <VControl class="field-addon-body">
                      <VButton static>x/menit</VButton>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-1">
                  <h1 class="mb-3 emr">Fetal Movement</h1>
                </div>
                <div class="column is-2">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.fetalmovement" :attrs="{ value }" placeholder="" label="label"
                        :options="d_djj" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-12">
                  <h1 class="mb-3 emr">Biometri</h1>
                  <table class="w-100">
                    <thead>
                      <tr>
                        <th>Pengukuran</th>
                        <th>mm</th>
                        <th>Usia Kehamilan</th>
                        <th>Keterangan</th>
                      </tr>
                    </thead>
                    <thead>
                      <tr>
                        <td>
                          <h1 class="mb-3 emr">Gestasional sac</h1>
                        </td>
                        <td>
                          <VField>
                            <VControl>
                              <VInput type="text" v-model="input.gestasionalsac" placeholder="" class="is-rounded" />
                            </VControl>
                          </VField>
                        </td>
                        <td>
                          <VField>
                            <VControl>
                              <VInput type="text" v-model="input.usiaGestasional" placeholder="" class="is-rounded" />
                            </VControl>
                          </VField>
                        </td>
                        <td>
                          <div class="column is-12">
                            <div class="columns is-multiline">
                              <div class="column is-2">
                                <h1 class="mb-3 emr">AVE</h1>
                              </div>
                              <div class="column is-10">
                                <VField>
                                  <VControl>
                                    <VInput type="text" v-model="input.ketGestasional" placeholder=""
                                      class="is-rounded" />
                                  </VControl>
                                </VField>
                              </div>
                            </div>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <h1 class="mb-3 emr">Crown-rump lenght</h1>
                        </td>
                        <td>
                          <VField>
                            <VControl>
                              <VInput type="text" v-model="input.crown" placeholder="" class="is-rounded" />
                            </VControl>
                          </VField>
                        </td>
                        <td>
                          <VField>
                            <VControl>
                              <VInput type="text" v-model="input.usiaCrown" placeholder="" class="is-rounded" />
                            </VControl>
                          </VField>
                        </td>
                        <td>
                          <div class="column is-12">
                            <div class="columns is-multiline">
                              <div class="column is-2">
                                <h1 class="mb-3 emr">EDD</h1>
                              </div>
                              <div class="column is-10">
                                <VField>
                                  <VControl>
                                    <VInput type="text" v-model="input.ketCrown" placeholder="" class="is-rounded" />
                                  </VControl>
                                </VField>
                              </div>
                            </div>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <h1 class="mb-3 emr">Biparietal Diameter</h1>
                        </td>
                        <td>
                          <VField>
                            <VControl>
                              <VInput type="text" v-model="input.biparietal" placeholder="" class="is-rounded" />
                            </VControl>
                          </VField>
                        </td>
                        <td>
                          <VField>
                            <VControl>
                              <VInput type="text" v-model="input.usiaBiparietal" placeholder="" class="is-rounded" />
                            </VControl>
                          </VField>
                        </td>
                        <td>
                          <div class="column is-12">
                            <div class="columns is-multiline">
                              <div class="column is-2">
                                <h1 class="mb-3 emr">EFW</h1>
                              </div>
                              <div class="column is-10">
                                <VField>
                                  <VControl>
                                    <VInput type="text" v-model="input.ketBiparietal" placeholder=""
                                      class="is-rounded" />
                                  </VControl>
                                </VField>
                              </div>
                            </div>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <h1 class="mb-3 emr">Head Circumference</h1>
                        </td>
                        <td>
                          <VField>
                            <VControl>
                              <VInput type="text" v-model="input.headcircum" placeholder="" class="is-rounded" />
                            </VControl>
                          </VField>
                        </td>
                        <td>
                          <VField>
                            <VControl>
                              <VInput type="text" v-model="input.usiaHeadcircum" placeholder="" class="is-rounded" />
                            </VControl>
                          </VField>
                        </td>
                        <td>
                          <div class="column is-12">
                            <h1 class="mb-3 emr">Temuan Abnormal:</h1>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <h1 class="mb-3 emr">Abdominal Circumference</h1>
                        </td>
                        <td>
                          <VField>
                            <VControl>
                              <VInput type="text" v-model="input.abdominalcircum" placeholder="" class="is-rounded" />
                            </VControl>
                          </VField>
                        </td>
                        <td>
                          <VField>
                            <VControl>
                              <VInput type="text" v-model="input.usiaAbdominalcircum" placeholder=""
                                class="is-rounded" />
                            </VControl>
                          </VField>
                        </td>
                        <td rowspan="2">
                          <div class="column is-12">
                            <VField>
                              <VTextarea rows="8" placeholder="" v-model="input.Ketabdominalcircum"></VTextarea>
                            </VField>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <h1 class="mb-3 emr">Femoral Lenght</h1>
                        </td>
                        <td>
                          <VField>
                            <VControl>
                              <VInput type="text" v-model="input.femoral" placeholder="" class="is-rounded" />
                            </VControl>
                          </VField>
                        </td>
                        <td>
                          <VField>
                            <VControl>
                              <VInput type="text" v-model="input.usiaFemoral" placeholder="" class="is-rounded" />
                            </VControl>
                          </VField>
                        </td>
                      </tr>
                    </thead>
                  </table>
                </div>
                <div class="column is-1">
                  <h1 class="mb-3 emr">Plasenta</h1>
                </div>
                <div class="column is-2">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.plasenta" :attrs="{ value }" placeholder="" label="label"
                        :options="d_plasenta" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Menutupi</h1>
                </div>
                <div class="column is-2">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.menutupi" :attrs="{ value }" placeholder="" label="label"
                        :options="d_menutupi" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField addons>
                    <VControl expanded>
                      <VInput type="text" class="input is-rounded" placeholder="" v-model="input.ukuranMenutupi" />
                    </VControl>
                    <VControl class="field-addon-body">
                      <VButton static>mm dari OUI</VButton>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-1">
                  <h1 class="mb-3 emr">Maturasi</h1>
                </div>
                <div class="column is-2">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.maturasi" :attrs="{ value }" placeholder="" label="label"
                        :options="d_maturasi" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-1">
                  <h1 class="mb-3 emr">Cairan aminion</h1>
                </div>
                <div class="column is-2">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.cairanaminion" :attrs="{ value }" placeholder="" label="label"
                        :options="d_cairanaminion" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-1">
                  <h1 class="mb-3 emr">AFI</h1>
                </div>
                <div class="column is-1">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.AFI" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-1">
                  <h1 class="mb-3 emr">SDP</h1>
                </div>
                <div class="column is-1">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.SDP" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Temuan Abnormal</h1>
                </div>
                <div class="column is-3">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.temuanAbnormal" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-3">
                  <h1 class="mb-3 emr">Kelainan kongenital mayor</h1>
                </div>
                <div class="column is-3">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.kongenitalMayor" :attrs="{ value }" placeholder="" label="label"
                        :options="d_djj" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Temuan Abnormal</h1>
                </div>
                <div class="column is-4">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.temuanAbnormalKongenital" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-3">
                  <h1 class="mb-3 emr">Adneksa</h1>
                </div>
                <div class="column is-3">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.adneksa" :attrs="{ value }" placeholder="" label="label"
                        :options="d_cairanaminion" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Temuan Abnormal</h1>
                </div>
                <div class="column is-4">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.temuanAbnormalAdneksa" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>

                <br>
                <hr><br>

                <div class="column is-12">
                  <h1 class="mb-3 emr">Doppler Velocimetry</h1>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Arteri Uterina</h1>
                </div>
                <div class="column is-4">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.arteriUterina" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Arteri Umbilicalis</h1>
                </div>
                <div class="column is-4">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.arteriUmbilicalis" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">RI</h1>
                </div>
                <div class="column is-4">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.riUterina" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">RI</h1>
                </div>
                <div class="column is-4">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.riUmbilicalis" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">PI</h1>
                </div>
                <div class="column is-4">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.piUterina" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">PI</h1>
                </div>
                <div class="column is-4">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.piUmbilicalis" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">S/D Ratio</h1>
                </div>
                <div class="column is-4">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.ratioUterina" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">S/D Ratio</h1>
                </div>
                <div class="column is-4">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.ratioUmbilicalis" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>

                <div class="column is-2 mt-5">
                  <h1 class="mb-3 emr">Ductus Venosus</h1>
                </div>
                <div class="column is-4 mt-5">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.ductusVenosus" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2 mt-5">
                  <h1 class="mb-3 emr">Arteri Serebi Media</h1>
                </div>
                <div class="column is-4 mt-5">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.arteriSerebi" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">RI</h1>
                </div>
                <div class="column is-4">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.riDuctus" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">RI</h1>
                </div>
                <div class="column is-4">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.riSerebi" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">PI</h1>
                </div>
                <div class="column is-4">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.piDuctus" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">PI</h1>
                </div>
                <div class="column is-4">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.piSerebi" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">S/D Ratio</h1>
                </div>
                <div class="column is-4">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.ratioDuctus" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">S/D Ratio</h1>
                </div>
                <div class="column is-4">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.ratioSerebi" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-10">
                  <h1 class="mb-3 emr">Kepala</h1>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl>
                      <VCheckbox class="fontcheckbox" v-model="input.batasnormalfsk" true-value="Dalam Batas Normal"
                        label="Dalam Batas Normal" color="primary" circle @click="batasnormalfisik" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Bentuk</h1>
                </div>
                <div class="column is-4">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.bentukkepala" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Ventrikel lateral</h1>
                </div>
                <div class="column is-4">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.ventrikellateral" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Cavum septum pellucidum</h1>
                </div>
                <div class="column is-4">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.cavumseptum" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Cerebellum</h1>
                </div>
                <div class="column is-4">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.cerebellum" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Falx cerebri</h1>
                </div>
                <div class="column is-4">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.falx" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Cistema magna</h1>
                </div>
                <div class="column is-4">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.cistema" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Thalamus</h1>
                </div>
                <div class="column is-4">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.thalamus" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6"></div>
                <div class="column is-12">
                  <h1 class="mb-3 emr">Wajah</h1>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Bibir atas</h1>
                </div>
                <div class="column is-4">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.bibiratas" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Hidung</h1>
                </div>
                <div class="column is-4">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.hidung" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Profil Mediana</h1>
                </div>
                <div class="column is-4">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.mediana" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Lubang Hidung</h1>
                </div>
                <div class="column is-4">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.lubanghidung" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Orbita</h1>
                </div>
                <div class="column is-4">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.orbita" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Leher</h1>
                </div>
                <div class="column is-4">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.leher" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6"></div>
                <div class="column is-12">
                  <h1 class="mb-3 emr">Thorax</h1>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Bentuk</h1>
                </div>
                <div class="column is-4">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.bentukThorax" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Tidak Tampak Massa</h1>
                </div>
                <div class="column is-4">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.tidakTampakMassa" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-12">
                  <h1 class="mb-3 emr">Jantung</h1>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Aktifitas Jantung</h1>
                </div>
                <div class="column is-4">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.aktifitasJantung" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Faur chamber view</h1>
                </div>
                <div class="column is-4">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.faur" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Ukuran</h1>
                </div>
                <div class="column is-4">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.ukuranJantung" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Left verticular outlow</h1>
                </div>
                <div class="column is-4">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.leftVerticular" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Axis Jantung</h1>
                </div>
                <div class="column is-4">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.axisJantung" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Right verticular outlow</h1>
                </div>
                <div class="column is-4">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.rightVerticular" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-12">
                  <h1 class="mb-3 emr">Abdomen</h1>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Gaster</h1>
                </div>
                <div class="column is-4">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.gaster" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Vesica Urinaria</h1>
                </div>
                <div class="column is-4">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.vesicaUrinaria" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Usus</h1>
                </div>
                <div class="column is-4">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.usus" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Insersi Umbilicilus</h1>
                </div>
                <div class="column is-4">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.insersiUmbilicilus" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Ginjal</h1>
                </div>
                <div class="column is-4">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.ginjal" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Vescular Umbilicilus</h1>
                </div>
                <div class="column is-4">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.vescularUmbilicilus" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Vertebra</h1>
                </div>
                <div class="column is-4">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.vertebra" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6"></div>
                <div class="column is-12">
                  <h1 class="mb-3 emr">Ekstremitas</h1>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Tangan Kanan (termasuk telapak)</h1>
                </div>
                <div class="column is-4">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.eksTanganKanan" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Tangan Kiri (termasuk telapak)</h1>
                </div>
                <div class="column is-4">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.eksTanganKiri" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Kaki Kanan (termasuk telapak)</h1>
                </div>
                <div class="column is-4">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.eksKakiKanan" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Kaki Kiri (termasuk telapak)</h1>
                </div>
                <div class="column is-4">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.eksKakiKiri" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Jenis Kelamin</h1>
                </div>
                <div class="column is-4">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.jenisKelamin" :attrs="{ value }" placeholder="" label="label"
                        :options="d_jeniskelamin" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.jenisKelamin2" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Lain-Lain</h1>
                </div>
                <div class="column is-10">
                  <VField>
                    <VTextarea rows="8" placeholder="" v-model="input.fetalLainnya"></VTextarea>
                  </VField>
                </div>


                <div class="column is-2">
                  <h1 class="mb-3 emr">Kesimpulan & Saran</h1>
                </div>
                <div class="column is-10">
                  <VField>
                    <VTextarea rows="8" placeholder="" v-model="input.fetalSaran"></VTextarea>
                  </VField>
                </div>
              </div>

            </div>

            <div class="column is-12 mt-5" v-if="input.jenisPemeriksaanObgyn == 'PemeriksaanUSGObstetri'">
              <div class="columns is-multiline">
                <div class="column is-2">
                  <h1 class="mb-3 emr">Kondisi Teknis</h1>
                </div>
                <div class="column is-2">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.kondisiTeknisFetal" :attrs="{ value }" placeholder="" label="label"
                        :options="d_kondisiteknis" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Karena</h1>
                </div>
                <div class="column is-6">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.karenaFetal" placeholder="Karena" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Janin</h1>
                </div>
                <div class="column is-2">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.janin" :attrs="{ value }" placeholder="" label="label"
                        :options="d_janin" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Jumlah Janin</h1>
                </div>
                <div class="column is-6">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.jumlahJanin" placeholder="Karena" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Khorionisitas</h1>
                </div>
                <div class="column is-2">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.khorionisitas" :attrs="{ value }" placeholder="" label="label"
                        :options="d_khorionisitas" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-1">
                  <h1 class="mb-3 emr">DJJ</h1>
                </div>
                <div class="column is-2">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.djj" :attrs="{ value }" placeholder="" label="label" :options="d_djj"
                        class="is-rounded" :searchable="true" track-by="label" mode="single" autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField addons>
                    <VControl expanded>
                      <VInput type="text" class="input is-rounded" placeholder="" v-model="input.ketDJJ" />
                    </VControl>
                    <VControl class="field-addon-body">
                      <VButton static>x/menit</VButton>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-1">
                  <h1 class="mb-3 emr">Fetal Movement</h1>
                </div>
                <div class="column is-2">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.fetalmovement" :attrs="{ value }" placeholder="" label="label"
                        :options="d_djj" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-12">
                  <h1 class="mb-3 emr">Biometri</h1>
                  <table class="w-100">
                    <thead>
                      <tr>
                        <th>Pengukuran</th>
                        <th>mm</th>
                        <th>Usia Kehamilan</th>
                        <th>Keterangan</th>
                      </tr>
                    </thead>
                    <thead>
                      <tr>
                        <td>
                          <h1 class="mb-3 emr">Gestasional sac</h1>
                        </td>
                        <td>
                          <VField>
                            <VControl>
                              <VInput type="text" v-model="input.gestasionalsac" placeholder="" class="is-rounded" />
                            </VControl>
                          </VField>
                        </td>
                        <td>
                          <VField>
                            <VControl>
                              <VInput type="text" v-model="input.usiaGestasional" placeholder="" class="is-rounded" />
                            </VControl>
                          </VField>
                        </td>
                        <td>
                          <div class="column is-12">
                            <div class="columns is-multiline">
                              <div class="column is-2">
                                <h1 class="mb-3 emr">AVE</h1>
                              </div>
                              <div class="column is-10">
                                <VField>
                                  <VControl>
                                    <VInput type="text" v-model="input.ketGestasional" placeholder=""
                                      class="is-rounded" />
                                  </VControl>
                                </VField>
                              </div>
                            </div>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <h1 class="mb-3 emr">Crown-rump lenght</h1>
                        </td>
                        <td>
                          <VField>
                            <VControl>
                              <VInput type="text" v-model="input.crown" placeholder="" class="is-rounded" />
                            </VControl>
                          </VField>
                        </td>
                        <td>
                          <VField>
                            <VControl>
                              <VInput type="text" v-model="input.usiaCrown" placeholder="" class="is-rounded" />
                            </VControl>
                          </VField>
                        </td>
                        <td>
                          <div class="column is-12">
                            <div class="columns is-multiline">
                              <div class="column is-2">
                                <h1 class="mb-3 emr">EDD</h1>
                              </div>
                              <div class="column is-10">
                                <VField>
                                  <VControl>
                                    <VInput type="text" v-model="input.ketCrown" placeholder="" class="is-rounded" />
                                  </VControl>
                                </VField>
                              </div>
                            </div>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <h1 class="mb-3 emr">Biparietal Diameter</h1>
                        </td>
                        <td>
                          <VField>
                            <VControl>
                              <VInput type="text" v-model="input.biparietal" placeholder="" class="is-rounded" />
                            </VControl>
                          </VField>
                        </td>
                        <td>
                          <VField>
                            <VControl>
                              <VInput type="text" v-model="input.usiaBiparietal" placeholder="" class="is-rounded" />
                            </VControl>
                          </VField>
                        </td>
                        <td>
                          <div class="column is-12">
                            <div class="columns is-multiline">
                              <div class="column is-2">
                                <h1 class="mb-3 emr">EFW</h1>
                              </div>
                              <div class="column is-10">
                                <VField>
                                  <VControl>
                                    <VInput type="text" v-model="input.ketBiparietal" placeholder=""
                                      class="is-rounded" />
                                  </VControl>
                                </VField>
                              </div>
                            </div>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <h1 class="mb-3 emr">Head Circumference</h1>
                        </td>
                        <td>
                          <VField>
                            <VControl>
                              <VInput type="text" v-model="input.headcircum" placeholder="" class="is-rounded" />
                            </VControl>
                          </VField>
                        </td>
                        <td>
                          <VField>
                            <VControl>
                              <VInput type="text" v-model="input.usiaHeadcircum" placeholder="" class="is-rounded" />
                            </VControl>
                          </VField>
                        </td>
                        <td>
                          <div class="column is-12">
                            <h1 class="mb-3 emr">Temuan Abnormal:</h1>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <h1 class="mb-3 emr">Abdominal Circumference</h1>
                        </td>
                        <td>
                          <VField>
                            <VControl>
                              <VInput type="text" v-model="input.abdominalcircum" placeholder="" class="is-rounded" />
                            </VControl>
                          </VField>
                        </td>
                        <td>
                          <VField>
                            <VControl>
                              <VInput type="text" v-model="input.usiaAbdominalcircum" placeholder=""
                                class="is-rounded" />
                            </VControl>
                          </VField>
                        </td>
                        <td rowspan="2">
                          <div class="column is-12">
                            <VField>
                              <VTextarea rows="8" placeholder="" v-model="input.Ketabdominalcircum"></VTextarea>
                            </VField>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <h1 class="mb-3 emr">Femoral Lenght</h1>
                        </td>
                        <td>
                          <VField>
                            <VControl>
                              <VInput type="text" v-model="input.femoral" placeholder="" class="is-rounded" />
                            </VControl>
                          </VField>
                        </td>
                        <td>
                          <VField>
                            <VControl>
                              <VInput type="text" v-model="input.usiaFemoral" placeholder="" class="is-rounded" />
                            </VControl>
                          </VField>
                        </td>
                      </tr>
                    </thead>
                  </table>
                </div>
                <div class="column is-1">
                  <h1 class="mb-3 emr">Plasenta</h1>
                </div>
                <div class="column is-2">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.plasenta" :attrs="{ value }" placeholder="" label="label"
                        :options="d_plasenta" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Menutupi</h1>
                </div>
                <div class="column is-2">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.menutupi" :attrs="{ value }" placeholder="" label="label"
                        :options="d_menutupi" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField addons>
                    <VControl expanded>
                      <VInput type="text" class="input is-rounded" placeholder="" v-model="input.ukuranMenutupi" />
                    </VControl>
                    <VControl class="field-addon-body">
                      <VButton static>mm dari OUI</VButton>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-1">
                  <h1 class="mb-3 emr">Maturasi</h1>
                </div>
                <div class="column is-2">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.maturasi" :attrs="{ value }" placeholder="" label="label"
                        :options="d_maturasi" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-1">
                  <h1 class="mb-3 emr">Cairan aminion</h1>
                </div>
                <div class="column is-2">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.cairanaminion" :attrs="{ value }" placeholder="" label="label"
                        :options="d_cairanaminion" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-1">
                  <h1 class="mb-3 emr">AFI</h1>
                </div>
                <div class="column is-1">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.AFI" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-1">
                  <h1 class="mb-3 emr">SDP</h1>
                </div>
                <div class="column is-1">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.SDP" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Temuan Abnormal</h1>
                </div>
                <div class="column is-3">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.temuanAbnormal" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-3">
                  <h1 class="mb-3 emr">Kelainan kongenital mayor</h1>
                </div>
                <div class="column is-3">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.kongenitalMayor" :attrs="{ value }" placeholder="" label="label"
                        :options="d_djj" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Temuan Abnormal</h1>
                </div>
                <div class="column is-4">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.temuanAbnormalKongenital" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-3">
                  <h1 class="mb-3 emr">Adneksa</h1>
                </div>
                <div class="column is-3">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.adneksa" :attrs="{ value }" placeholder="" label="label"
                        :options="d_cairanaminion" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Temuan Abnormal</h1>
                </div>
                <div class="column is-4">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.temuanAbnormalAdneksa" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>

                <br>
                <hr><br>

                <div class="column is-12" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Doppler Velocimetry</h1>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Arteri Uterina</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.arteriUterina" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Arteri Umbilicalis</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.arteriUmbilicalis" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">RI</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.riUterina" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">RI</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.riUmbilicalis" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">PI</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.piUterina" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">PI</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.piUmbilicalis" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">S/D Ratio</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.ratioUterina" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">S/D Ratio</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.ratioUmbilicalis" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>

                <div class="column is-2 mt-5" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Ductus Venosus</h1>
                </div>
                <div class="column is-4 mt-5" v-if="isFetal == true">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.ductusVenosus" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2 mt-5" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Arteri Serebi Media</h1>
                </div>
                <div class="column is-4 mt-5" v-if="isFetal == true">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.arteriSerebi" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">RI</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.riDuctus" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">RI</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.riSerebi" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">PI</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.piDuctus" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">PI</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.piSerebi" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">S/D Ratio</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.ratioDuctus" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">S/D Ratio</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.ratioSerebi" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-10" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Kepala</h1>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <VField>
                    <VControl>
                      <VCheckbox class="fontcheckbox" v-model="input.batasnormalfsk" true-value="Dalam Batas Normal"
                        label="Dalam Batas Normal" color="primary" circle @click="batasnormalfisik" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Bentuk</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.bentukkepala" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Ventrikel lateral</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.ventrikellateral" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Cavum septum pellucidum</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.cavumseptum" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Cerebellum</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.cerebellum" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Falx cerebri</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.falx" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Cistema magna</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.cistema" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Thalamus</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.thalamus" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6" v-if="isFetal == true"></div>
                <div class="column is-12" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Wajah</h1>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Bibir atas</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.bibiratas" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Hidung</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.hidung" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Profil Mediana</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.mediana" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Lubang Hidung</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.lubanghidung" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Orbita</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.orbita" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Leher</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.leher" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6" v-if="isFetal == true"></div>
                <div class="column is-12" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Thorax</h1>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Bentuk</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.bentukThorax" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Tidak Tampak Massa</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.tidakTampakMassa" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-12" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Jantung</h1>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Aktifitas Jantung</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.aktifitasJantung" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Faur chamber view</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.faur" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Ukuran</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.ukuranJantung" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Left verticular outlow</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.leftVerticular" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Axis Jantung</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.axisJantung" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Right verticular outlow</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.rightVerticular" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-12" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Abdomen</h1>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Gaster</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.gaster" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Vesica Urinaria</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.vesicaUrinaria" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Usus</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.usus" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Insersi Umbilicilus</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.insersiUmbilicilus" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Ginjal</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.ginjal" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Vescular Umbilicilus</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.vescularUmbilicilus" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Vertebra</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.vertebra" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6" v-if="isFetal == true"></div>
                <div class="column is-12" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Ekstremitas</h1>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Tangan Kanan (termasuk telapak)</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.eksTanganKanan" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Tangan Kiri (termasuk telapak)</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.eksTanganKiri" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Kaki Kanan (termasuk telapak)</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.eksKakiKanan" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Kaki Kiri (termasuk telapak)</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.eksKakiKiri" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Jenis Kelamin</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.jenisKelamin" :attrs="{ value }" placeholder="" label="label"
                        :options="d_jeniskelamin" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6" v-if="isFetal == true">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.jenisKelamin2" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Lain-Lain</h1>
                </div>
                <div class="column is-10" v-if="isFetal == true">
                  <VField>
                    <VTextarea rows="8" placeholder="" v-model="input.fetalLainnya"></VTextarea>
                  </VField>
                </div>


                <div class="column is-2">
                  <h1 class="mb-3 emr">Kesimpulan & Saran</h1>
                </div>
                <div class="column is-10">
                  <VField>
                    <VTextarea rows="8" placeholder="" v-model="input.fetalSaran"></VTextarea>
                  </VField>
                </div>
              </div>
            </div>

            <div class="column is-12 mt-5" v-if="input.jenisPemeriksaanObgyn == 'PemeriksaanKardiotokografi'">
              <div class="columns is-multiline">
                <div class="column is-2">
                  <h1 class="mb-3 emr">TD Awal</h1>
                </div>
                <div class="column is-4">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.tdawal" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">TD Menit Ke-15</h1>
                </div>
                <div class="column is-4">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.tg15" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Cara Pantau</h1>
                </div>
                <div class="column is-4">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.carapantau" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Kecepatan Kertas</h1>
                </div>
                <div class="column is-4">
                  <VField addons>
                    <VControl expanded>
                      <VInput type="text" class="input is-rounded" placeholder="" v-model="input.kecepatankertas" />
                    </VControl>
                    <VControl class="field-addon-body">
                      <VButton static>cm/menit</VButton>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Periksa Dalam</h1>
                </div>
                <div class="column is-4">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.periksaDalam" :attrs="{ value }" placeholder="" label="label"
                        :options="d_periksadalam" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Dengan Hasil</h1>
                </div>
                <div class="column is-4">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.denganhasil" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Diagnosis</h1>
                </div>
                <div class="column is-4">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.diagnosis" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6"></div>

                <br>
                <hr><br>

                <div class="column is-2">
                  <h1 class="mb-3 emr">Denyut Jantung Janin</h1>
                </div>
                <div class="column is-4">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.denyutjantung" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6"></div>

                <div class="column is-12" style="margin-top: 100px;">
                  <div class="columns is-multiline">
                    <div class="column is-4">
                      <div class="columns is-multiline">
                        <div class="column is-4">
                          <h1 class="mb-3 emr">Frekuensi Dasar</h1>
                        </div>
                        <div class="column is-8">
                          <VField addons>
                            <VControl expanded>
                              <VInput type="text" class="input is-rounded" placeholder=""
                                v-model="input.frekuensidasar" />
                            </VControl>
                            <VControl class="field-addon-body">
                              <VButton static>dpm</VButton>
                            </VControl>
                          </VField>
                        </div>
                      </div>
                    </div>
                    <div class="column is-4">
                      <div class="columns is-multiline">
                        <div class="column is-4">
                          <h1 class="mb-3 emr">Akselerasi</h1>
                        </div>
                        <div class="column is-8">
                          <VField class="is-autocomplete-select" v-slot="{ id }">
                            <VControl>
                              <Multiselect v-model="input.akselerasi" :attrs="{ value }" placeholder="" label="label"
                                :options="d_akselerasi" class="is-rounded" :searchable="true" track-by="label"
                                mode="single" autocomplete="off">
                              </Multiselect>
                            </VControl>
                          </VField>
                        </div>
                      </div>
                    </div>
                    <div class="column is-4">
                      <div class="columns is-multiline">
                        <div class="column is-4">
                          <h1 class="mb-3 emr">Deselerasi</h1>
                        </div>
                        <div class="column is-8">
                          <VField class="is-autocomplete-select" v-slot="{ id }">
                            <VControl>
                              <Multiselect v-model="input.deselerasi" :attrs="{ value }" placeholder="" label="label"
                                :options="d_akselerasi" class="is-rounded" :searchable="true" track-by="label"
                                mode="single" autocomplete="off">
                              </Multiselect>
                            </VControl>
                          </VField>
                        </div>
                      </div>
                    </div>
                    <div class="column is-4">
                      <div class="columns is-multiline">
                        <div class="column is-4">
                          <h1 class="mb-3 emr">Variabilitas</h1>
                        </div>
                        <div class="column is-8">
                          <VField class="is-autocomplete-select" v-slot="{ id }">
                            <VControl>
                              <Multiselect v-model="input.variabilitas" :attrs="{ value }" placeholder="" label="label"
                                :options="d_variabilitas" class="is-rounded" :searchable="true" track-by="label"
                                mode="single" autocomplete="off">
                              </Multiselect>
                            </VControl>
                          </VField>
                        </div>
                      </div>
                    </div>
                    <div class="column is-4">
                      <div class="columns is-multiline">
                        <div class="column is-4">
                          <h1 class="mb-3 emr">Jenisnya</h1>
                        </div>
                        <div class="column is-8">
                          <VField class="is-autocomplete-select" v-slot="{ id }">
                            <VControl>
                              <Multiselect v-model="input.jenisnya" :attrs="{ value }" placeholder="" label="label"
                                :options="d_jenisnya" class="is-rounded" :searchable="true" track-by="label"
                                mode="single" autocomplete="off">
                              </Multiselect>
                            </VControl>
                          </VField>
                        </div>
                      </div>
                    </div>
                    <div class="column is-4">
                      <div class="columns is-multiline">
                        <div class="column is-4">
                          <h1 class="mb-3 emr">Beratnya</h1>
                        </div>
                        <div class="column is-8">
                          <VField class="is-autocomplete-select" v-slot="{ id }">
                            <VControl>
                              <Multiselect v-model="input.beratnya" :attrs="{ value }" placeholder="" label="label"
                                :options="d_beratnya" class="is-rounded" :searchable="true" track-by="label"
                                mode="single" autocomplete="off">
                              </Multiselect>
                            </VControl>
                          </VField>
                        </div>
                      </div>
                    </div>
                    <div class="column is-4">
                      <div class="columns is-multiline">
                        <div class="column is-4">
                          <h1 class="mb-3 emr">Pola disfungsi SSP</h1>
                        </div>
                        <div class="column is-8">
                          <VField class="is-autocomplete-select" v-slot="{ id }">
                            <VControl>
                              <Multiselect v-model="input.ssp" :attrs="{ value }" placeholder="" label="label"
                                :options="d_akselerasi" class="is-rounded" :searchable="true" track-by="label"
                                mode="single" autocomplete="off">
                              </Multiselect>
                            </VControl>
                          </VField>
                        </div>
                      </div>
                    </div>
                    <div class="column is-4">
                      <div class="columns is-multiline">
                        <div class="column is-4">
                          <h1 class="mb-3 emr">Yaitu</h1>
                        </div>
                        <div class="column is-8">
                          <VField class="is-autocomplete-select" v-slot="{ id }">
                            <VControl>
                              <Multiselect v-model="input.yaitu" :attrs="{ value }" placeholder="" label="label"
                                :options="d_yaitu" class="is-rounded" :searchable="true" track-by="label" mode="single"
                                autocomplete="off">
                              </Multiselect>
                            </VControl>
                          </VField>
                        </div>
                      </div>
                    </div>
                    <div class="column is-4"></div>

                    <br>
                    <hr><br>

                    <div class="column is-4">
                      <div class="columns is-multiline">
                        <div class="column is-4">
                          <h1 class="mb-3 emr">Kontraksi Uterus/His</h1>
                        </div>
                        <div class="column is-8">
                          <VField class="is-autocomplete-select" v-slot="{ id }">
                            <VControl>
                              <Multiselect v-model="input.kontraksi" :attrs="{ value }" placeholder="" label="label"
                                :options="d_akselerasi" class="is-rounded" :searchable="true" track-by="label"
                                mode="single" autocomplete="off">
                              </Multiselect>
                            </VControl>
                          </VField>
                        </div>
                      </div>
                    </div>
                    <div class="column is-8"></div>
                    <div class="column is-4">
                      <div class="columns is-multiline">
                        <div class="column is-4">
                          <h1 class="mb-3 emr">Frekuensi</h1>
                        </div>
                        <div class="column is-8">
                          <VField>
                            <VField addons>
                              <VControl expanded>
                                <VInput type="text" class="input" placeholder="" v-model.number="input.frekuensi" />
                              </VControl>
                              <VControl class="field-addon-body">
                                <VButton static>/10menit</VButton>
                              </VControl>
                            </VField>
                          </VField>
                        </div>
                      </div>
                    </div>
                    <div class="column is-4">
                      <div class="columns is-multiline">
                        <div class="column is-4">
                          <h1 class="mb-3 emr">Kekuatan</h1>
                        </div>
                        <div class="column is-8">
                          <VField>
                            <VField addons>
                              <VControl expanded>
                                <VInput type="text" class="input" placeholder="" v-model.number="input.kekuatan" />
                              </VControl>
                              <VControl class="field-addon-body">
                                <VButton static>mmHg</VButton>
                              </VControl>
                            </VField>
                          </VField>
                        </div>
                      </div>
                    </div>
                    <div class="column is-4">
                      <div class="columns is-multiline">
                        <div class="column is-4">
                          <h1 class="mb-3 emr">Lamanya</h1>
                        </div>
                        <div class="column is-8">
                          <VField>
                            <VField addons>
                              <VControl expanded>
                                <VInput type="text" class="input" placeholder="" v-model.number="input.lamanya" />
                              </VControl>
                              <VControl class="field-addon-body">
                                <VButton static>menit</VButton>
                              </VControl>
                            </VField>
                          </VField>
                        </div>
                      </div>
                    </div>
                    <div class="column is-4">
                      <div class="columns is-multiline">
                        <div class="column is-4">
                          <h1 class="mb-3 emr">Relaksasi</h1>
                        </div>
                        <div class="column is-8">
                          <VField>
                            <VControl>
                              <VInput type="text" v-model="input.relaksasi" placeholder="" class="is-rounded" />
                            </VControl>
                          </VField>
                        </div>
                      </div>
                    </div>
                    <div class="column is-4">
                      <div class="columns is-multiline">
                        <div class="column is-4">
                          <h1 class="mb-3 emr">Konfigurasi</h1>
                        </div>
                        <div class="column is-8">
                          <VField>
                            <VControl>
                              <VInput type="text" v-model="input.konfigurasi" placeholder="" class="is-rounded" />
                            </VControl>
                          </VField>
                        </div>
                      </div>
                    </div>
                    <div class="column is-4">
                      <div class="columns is-multiline">
                        <div class="column is-4">
                          <h1 class="mb-3 emr">Tumus Dasar</h1>
                        </div>
                        <div class="column is-8">
                          <VField>
                            <VField addons>
                              <VControl expanded>
                                <VInput type="text" class="input" placeholder="" v-model.number="input.tumusdasar" />
                              </VControl>
                              <VControl class="field-addon-body">
                                <VButton static>mmHg</VButton>
                              </VControl>
                            </VField>
                          </VField>
                        </div>
                      </div>
                    </div>

                    <br>
                    <hr><br>

                    <div class="column is-4">
                      <div class="columns is-multiline">
                        <div class="column is-4">
                          <h1 class="mb-3 emr">Gerak Janin</h1>
                        </div>
                        <div class="column is-8">
                          <VField>
                            <VField addons>
                              <VControl expanded>
                                <VInput type="text" class="input" placeholder="" v-model.number="input.gerakjanin" />
                              </VControl>
                              <VControl class="field-addon-body">
                                <VButton static>kali</VButton>
                              </VControl>
                            </VField>
                          </VField>
                        </div>
                      </div>
                    </div>
                    <div class="column is-4">
                      <div class="columns is-multiline">
                        <div class="column is-4">
                          <h1 class="mb-3 emr">dalam</h1>
                        </div>
                        <div class="column is-8">
                          <VField>
                            <VField addons>
                              <VControl expanded>
                                <VInput type="text" class="input" placeholder="" v-model.number="input.lamagerak" />
                              </VControl>
                              <VControl class="field-addon-body">
                                <VButton static>menit</VButton>
                              </VControl>
                            </VField>
                          </VField>
                        </div>
                      </div>
                    </div>
                    <div class="column is-4"></div>
                    <div class="column is-4">
                      <div class="columns is-multiline">
                        <div class="column is-4">
                          <h1 class="mb-3 emr">Diagnosis KTG</h1>
                        </div>
                        <div class="column is-8">
                          <VField>
                            <VControl>
                              <VInput type="text" v-model="input.diagnosisktg" placeholder="" class="is-rounded" />
                            </VControl>
                          </VField>
                        </div>
                      </div>
                    </div>
                    <div class="column is-4">
                      <div class="columns is-multiline">
                        <div class="column is-4">
                          <h1 class="mb-3 emr">Kategori</h1>
                        </div>
                        <div class="column is-8">
                          <VField class="is-autocomplete-select" v-slot="{ id }">
                            <VControl>
                              <Multiselect v-model="input.kategoridiagnosisktg" :attrs="{ value }" placeholder=""
                                label="label" :options="d_kategori" class="is-rounded" :searchable="true"
                                track-by="label" mode="single" autocomplete="off">
                              </Multiselect>
                            </VControl>
                          </VField>
                        </div>
                      </div>
                    </div>
                    <div class="column is-4"></div>
                    <div class="column is-12">
                      <h1 class="mb-3 emr">Saran</h1>
                      <VField>
                        <VTextarea rows="8" placeholder="Saran..." v-model="input.saran"></VTextarea>
                      </VField>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="column is-12 mt-5" v-if="input.jenisPemeriksaanObgyn == 'PemeriksaanUSGGynekologi'">
              <div class="columns is-multiline">
                <div class="column is-2">
                  <h1 class="mb-3 emr">Kondisi Teknis</h1>
                </div>
                <div class="column is-4">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.kondisiTeknis" :attrs="{ value }" placeholder="" label="label"
                        :options="d_kondisiteknis" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Karena</h1>
                </div>
                <div class="column is-4">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.karena" placeholder="Karena" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Vesica Urinaria</h1>
                </div>
                <div class="column is-4">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.vesicaUrinaria" :attrs="{ value }" placeholder="" label="label"
                        :options="d_vesicaurinaria" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Cairan Bebas</h1>
                </div>
                <div class="column is-4">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.cairanBebas" :attrs="{ value }" placeholder="" label="label"
                        :options="d_akselerasi" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Uterus</h1>
                </div>
                <div class="column is-4">
                  <VField>
                    <VTextarea rows="8" placeholder="" v-model="input.uterus"></VTextarea>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Adnexa</h1>
                </div>
                <div class="column is-4">
                  <VField>
                    <VTextarea rows="8" placeholder="" v-model="input.adnexa"></VTextarea>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Lain-Lain</h1>
                </div>
                <div class="column is-4">
                  <VField>
                    <VTextarea rows="8" placeholder="" v-model="input.obstetriLainnya"></VTextarea>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Kesimpulan & Saran</h1>
                </div>
                <div class="column is-4">
                  <VField>
                    <VTextarea rows="8" placeholder="" v-model="input.kesimpulansaran"></VTextarea>
                  </VField>
                </div>
              </div>
            </div>
          </VCard>
        </div>
        <div class="column is-12"
          v-if="isInputData === true && isRiwayat === false && props.registrasi.objectruanganlastfk == 370">
          <VCard>
            <div class="column is-12">
              <div class="columns is-multiline">
                <div class="column is-2">
                  <h1 class="mb-3 emr">NRM</h1>
                </div>
                <div class="column is-4">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.norm" placeholder="NRM" class="is-rounded" disabled />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Tanggal/Jam</h1>
                </div>
                <div class="column is-4">
                  <VDatePicker v-model="input.tglPembuatan" mode="date" trim-weeks>
                    <template #default="{ inputValue, inputEvents }">
                      <VControl icon="feather:calendar" fullwidth>
                        <VInput :value="inputValue" v-on="inputEvents" />
                      </VControl>
                    </template>
                  </VDatePicker>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Nama Pasien</h1>
                </div>
                <div class="column is-4">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.namaPasien" placeholder="Nama Pasien" class="is-rounded"
                        disabled />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Dokter Pemeriksa</h1>
                </div>
                <div class="column is-4">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl icon="feather:search">
                      <AutoComplete v-model="input.dokterPemeriksa" :suggestions="d_Dokter"
                        @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                        :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder=""
                        class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Tanggal Lahir</h1>
                </div>
                <div class="column is-1">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.tanggalLahirPasien" placeholder="Tanggal Lahir"
                        class="is-rounded" disabled />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Umur</h1>
                </div>
                <div class="column is-1">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.umur" placeholder="Umur" class="is-rounded" disabled />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">HPHT</h1>
                </div>
                <div class="column is-4">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.hpht" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Alamat</h1>
                </div>
                <div class="column is-4">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.alamat" placeholder="Alamat" class="is-rounded" disabled />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Diagnosa</h1>
                </div>
                <div class="column is-4">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.diagnosa" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>

              </div>
            </div>
            <div class="column is-flex p-0">
              <div class="column is-2">
                <h1>Jenis Pemeriksaan:</h1>
              </div>
              <div class="column is-10">
                <VField class="is-autocomplete-select" v-slot="{ id }">
                  <VControl>
                    <Multiselect v-model="input.jenisPemeriksaanObgyn" placeholder="--Pilih--" label="label"
                      :options="jenisPemeriksaanObgyn" :searchable="true" track-by="label" mode="single"
                      autocomplete="off">
                    </Multiselect>
                  </VControl>
                </VField>
              </div>
            </div>

            <div class="column is-12 mt-5" v-if="input.jenisPemeriksaanObgyn == 'PemeriksaanUSGFetal'">
              <div class="columns is-multiline">
                <div class="column is-2">
                  <h1 class="mb-3 emr">Kondisi Teknis</h1>
                </div>
                <div class="column is-2">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.kondisiTeknisFetal" :attrs="{ value }" placeholder="" label="label"
                        :options="d_kondisiteknis" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Karena</h1>
                </div>
                <div class="column is-6">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.karenaFetal" placeholder="Karena" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Janin</h1>
                </div>
                <div class="column is-2">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.janin" :attrs="{ value }" placeholder="" label="label"
                        :options="d_janin" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Jumlah Janin</h1>
                </div>
                <div class="column is-6">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.jumlahJanin" placeholder="Karena" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Khorionisitas</h1>
                </div>
                <div class="column is-2">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.khorionisitas" :attrs="{ value }" placeholder="" label="label"
                        :options="d_khorionisitas" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-1">
                  <h1 class="mb-3 emr">DJJ</h1>
                </div>
                <div class="column is-2">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.djj" :attrs="{ value }" placeholder="" label="label" :options="d_djj"
                        class="is-rounded" :searchable="true" track-by="label" mode="single" autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField addons>
                    <VControl expanded>
                      <VInput type="text" class="input is-rounded" placeholder="" v-model="input.ketDJJ" />
                    </VControl>
                    <VControl class="field-addon-body">
                      <VButton static>x/menit</VButton>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-1">
                  <h1 class="mb-3 emr">Fetal Movement</h1>
                </div>
                <div class="column is-2">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.fetalmovement" :attrs="{ value }" placeholder="" label="label"
                        :options="d_djj" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-12">
                  <h1 class="mb-3 emr">Biometri</h1>
                  <table class="w-100">
                    <thead>
                      <tr>
                        <th>Pengukuran</th>
                        <th>mm</th>
                        <th>Usia Kehamilan</th>
                        <th>Keterangan</th>
                      </tr>
                    </thead>
                    <thead>
                      <tr>
                        <td>
                          <h1 class="mb-3 emr">Gestasional sac</h1>
                        </td>
                        <td>
                          <VField>
                            <VControl>
                              <VInput type="text" v-model="input.gestasionalsac" placeholder="" class="is-rounded" />
                            </VControl>
                          </VField>
                        </td>
                        <td>
                          <VField>
                            <VControl>
                              <VInput type="text" v-model="input.usiaGestasional" placeholder="" class="is-rounded" />
                            </VControl>
                          </VField>
                        </td>
                        <td>
                          <div class="column is-12">
                            <div class="columns is-multiline">
                              <div class="column is-2">
                                <h1 class="mb-3 emr">AVE</h1>
                              </div>
                              <div class="column is-10">
                                <VField>
                                  <VControl>
                                    <VInput type="text" v-model="input.ketGestasional" placeholder=""
                                      class="is-rounded" />
                                  </VControl>
                                </VField>
                              </div>
                            </div>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <h1 class="mb-3 emr">Crown-rump lenght</h1>
                        </td>
                        <td>
                          <VField>
                            <VControl>
                              <VInput type="text" v-model="input.crown" placeholder="" class="is-rounded" />
                            </VControl>
                          </VField>
                        </td>
                        <td>
                          <VField>
                            <VControl>
                              <VInput type="text" v-model="input.usiaCrown" placeholder="" class="is-rounded" />
                            </VControl>
                          </VField>
                        </td>
                        <td>
                          <div class="column is-12">
                            <div class="columns is-multiline">
                              <div class="column is-2">
                                <h1 class="mb-3 emr">EDD</h1>
                              </div>
                              <div class="column is-10">
                                <VField>
                                  <VControl>
                                    <VInput type="text" v-model="input.ketCrown" placeholder="" class="is-rounded" />
                                  </VControl>
                                </VField>
                              </div>
                            </div>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <h1 class="mb-3 emr">Biparietal Diameter</h1>
                        </td>
                        <td>
                          <VField>
                            <VControl>
                              <VInput type="text" v-model="input.biparietal" placeholder="" class="is-rounded" />
                            </VControl>
                          </VField>
                        </td>
                        <td>
                          <VField>
                            <VControl>
                              <VInput type="text" v-model="input.usiaBiparietal" placeholder="" class="is-rounded" />
                            </VControl>
                          </VField>
                        </td>
                        <td>
                          <div class="column is-12">
                            <div class="columns is-multiline">
                              <div class="column is-2">
                                <h1 class="mb-3 emr">EFW</h1>
                              </div>
                              <div class="column is-10">
                                <VField>
                                  <VControl>
                                    <VInput type="text" v-model="input.ketBiparietal" placeholder=""
                                      class="is-rounded" />
                                  </VControl>
                                </VField>
                              </div>
                            </div>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <h1 class="mb-3 emr">Head Circumference</h1>
                        </td>
                        <td>
                          <VField>
                            <VControl>
                              <VInput type="text" v-model="input.headcircum" placeholder="" class="is-rounded" />
                            </VControl>
                          </VField>
                        </td>
                        <td>
                          <VField>
                            <VControl>
                              <VInput type="text" v-model="input.usiaHeadcircum" placeholder="" class="is-rounded" />
                            </VControl>
                          </VField>
                        </td>
                        <td>
                          <div class="column is-12">
                            <h1 class="mb-3 emr">Temuan Abnormal:</h1>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <h1 class="mb-3 emr">Abdominal Circumference</h1>
                        </td>
                        <td>
                          <VField>
                            <VControl>
                              <VInput type="text" v-model="input.abdominalcircum" placeholder="" class="is-rounded" />
                            </VControl>
                          </VField>
                        </td>
                        <td>
                          <VField>
                            <VControl>
                              <VInput type="text" v-model="input.usiaAbdominalcircum" placeholder=""
                                class="is-rounded" />
                            </VControl>
                          </VField>
                        </td>
                        <td rowspan="2">
                          <div class="column is-12">
                            <VField>
                              <VTextarea rows="8" placeholder="" v-model="input.Ketabdominalcircum"></VTextarea>
                            </VField>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <h1 class="mb-3 emr">Femoral Lenght</h1>
                        </td>
                        <td>
                          <VField>
                            <VControl>
                              <VInput type="text" v-model="input.femoral" placeholder="" class="is-rounded" />
                            </VControl>
                          </VField>
                        </td>
                        <td>
                          <VField>
                            <VControl>
                              <VInput type="text" v-model="input.usiaFemoral" placeholder="" class="is-rounded" />
                            </VControl>
                          </VField>
                        </td>
                      </tr>
                    </thead>
                  </table>
                </div>
                <div class="column is-1">
                  <h1 class="mb-3 emr">Plasenta</h1>
                </div>
                <div class="column is-2">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.plasenta" :attrs="{ value }" placeholder="" label="label"
                        :options="d_plasenta" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Menutupi</h1>
                </div>
                <div class="column is-2">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.menutupi" :attrs="{ value }" placeholder="" label="label"
                        :options="d_menutupi" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField addons>
                    <VControl expanded>
                      <VInput type="text" class="input is-rounded" placeholder="" v-model="input.ukuranMenutupi" />
                    </VControl>
                    <VControl class="field-addon-body">
                      <VButton static>mm dari OUI</VButton>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-1">
                  <h1 class="mb-3 emr">Maturasi</h1>
                </div>
                <div class="column is-2">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.maturasi" :attrs="{ value }" placeholder="" label="label"
                        :options="d_maturasi" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-1">
                  <h1 class="mb-3 emr">Cairan aminion</h1>
                </div>
                <div class="column is-2">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.cairanaminion" :attrs="{ value }" placeholder="" label="label"
                        :options="d_cairanaminion" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-1">
                  <h1 class="mb-3 emr">AFI</h1>
                </div>
                <div class="column is-1">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.AFI" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-1">
                  <h1 class="mb-3 emr">SDP</h1>
                </div>
                <div class="column is-1">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.SDP" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Temuan Abnormal</h1>
                </div>
                <div class="column is-3">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.temuanAbnormal" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-3">
                  <h1 class="mb-3 emr">Kelainan kongenital mayor</h1>
                </div>
                <div class="column is-3">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.kongenitalMayor" :attrs="{ value }" placeholder="" label="label"
                        :options="d_djj" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Temuan Abnormal</h1>
                </div>
                <div class="column is-4">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.temuanAbnormalKongenital" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-3">
                  <h1 class="mb-3 emr">Adneksa</h1>
                </div>
                <div class="column is-3">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.adneksa" :attrs="{ value }" placeholder="" label="label"
                        :options="d_cairanaminion" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Temuan Abnormal</h1>
                </div>
                <div class="column is-4">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.temuanAbnormalAdneksa" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>

                <br>
                <hr><br>

                <div class="column is-12">
                  <h1 class="mb-3 emr">Doppler Velocimetry</h1>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Arteri Uterina</h1>
                </div>
                <div class="column is-4">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.arteriUterina" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Arteri Umbilicalis</h1>
                </div>
                <div class="column is-4">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.arteriUmbilicalis" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">RI</h1>
                </div>
                <div class="column is-4">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.riUterina" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">RI</h1>
                </div>
                <div class="column is-4">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.riUmbilicalis" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">PI</h1>
                </div>
                <div class="column is-4">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.piUterina" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">PI</h1>
                </div>
                <div class="column is-4">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.piUmbilicalis" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">S/D Ratio</h1>
                </div>
                <div class="column is-4">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.ratioUterina" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">S/D Ratio</h1>
                </div>
                <div class="column is-4">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.ratioUmbilicalis" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>

                <div class="column is-2 mt-5">
                  <h1 class="mb-3 emr">Ductus Venosus</h1>
                </div>
                <div class="column is-4 mt-5">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.ductusVenosus" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2 mt-5">
                  <h1 class="mb-3 emr">Arteri Serebi Media</h1>
                </div>
                <div class="column is-4 mt-5">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.arteriSerebi" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">RI</h1>
                </div>
                <div class="column is-4">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.riDuctus" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">RI</h1>
                </div>
                <div class="column is-4">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.riSerebi" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">PI</h1>
                </div>
                <div class="column is-4">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.piDuctus" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">PI</h1>
                </div>
                <div class="column is-4">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.piSerebi" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">S/D Ratio</h1>
                </div>
                <div class="column is-4">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.ratioDuctus" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">S/D Ratio</h1>
                </div>
                <div class="column is-4">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.ratioSerebi" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-10">
                  <h1 class="mb-3 emr">Kepala</h1>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl>
                      <VCheckbox class="fontcheckbox" v-model="input.batasnormalfsk" true-value="Dalam Batas Normal"
                        label="Dalam Batas Normal" color="primary" circle @click="batasnormalfisik" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Bentuk</h1>
                </div>
                <div class="column is-4">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.bentukkepala" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Ventrikel lateral</h1>
                </div>
                <div class="column is-4">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.ventrikellateral" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Cavum septum pellucidum</h1>
                </div>
                <div class="column is-4">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.cavumseptum" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Cerebellum</h1>
                </div>
                <div class="column is-4">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.cerebellum" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Falx cerebri</h1>
                </div>
                <div class="column is-4">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.falx" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Cistema magna</h1>
                </div>
                <div class="column is-4">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.cistema" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Thalamus</h1>
                </div>
                <div class="column is-4">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.thalamus" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6"></div>
                <div class="column is-12">
                  <h1 class="mb-3 emr">Wajah</h1>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Bibir atas</h1>
                </div>
                <div class="column is-4">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.bibiratas" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Hidung</h1>
                </div>
                <div class="column is-4">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.hidung" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Profil Mediana</h1>
                </div>
                <div class="column is-4">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.mediana" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Lubang Hidung</h1>
                </div>
                <div class="column is-4">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.lubanghidung" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Orbita</h1>
                </div>
                <div class="column is-4">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.orbita" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Leher</h1>
                </div>
                <div class="column is-4">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.leher" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6"></div>
                <div class="column is-12">
                  <h1 class="mb-3 emr">Thorax</h1>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Bentuk</h1>
                </div>
                <div class="column is-4">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.bentukThorax" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Tidak Tampak Massa</h1>
                </div>
                <div class="column is-4">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.tidakTampakMassa" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-12">
                  <h1 class="mb-3 emr">Jantung</h1>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Aktifitas Jantung</h1>
                </div>
                <div class="column is-4">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.aktifitasJantung" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Faur chamber view</h1>
                </div>
                <div class="column is-4">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.faur" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Ukuran</h1>
                </div>
                <div class="column is-4">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.ukuranJantung" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Left verticular outlow</h1>
                </div>
                <div class="column is-4">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.leftVerticular" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Axis Jantung</h1>
                </div>
                <div class="column is-4">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.axisJantung" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Right verticular outlow</h1>
                </div>
                <div class="column is-4">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.rightVerticular" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-12">
                  <h1 class="mb-3 emr">Abdomen</h1>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Gaster</h1>
                </div>
                <div class="column is-4">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.gaster" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Vesica Urinaria</h1>
                </div>
                <div class="column is-4">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.vesicaUrinaria" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Usus</h1>
                </div>
                <div class="column is-4">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.usus" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Insersi Umbilicilus</h1>
                </div>
                <div class="column is-4">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.insersiUmbilicilus" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Ginjal</h1>
                </div>
                <div class="column is-4">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.ginjal" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Vescular Umbilicilus</h1>
                </div>
                <div class="column is-4">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.vescularUmbilicilus" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Vertebra</h1>
                </div>
                <div class="column is-4">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.vertebra" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6"></div>
                <div class="column is-12">
                  <h1 class="mb-3 emr">Ekstremitas</h1>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Tangan Kanan (termasuk telapak)</h1>
                </div>
                <div class="column is-4">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.eksTanganKanan" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Tangan Kiri (termasuk telapak)</h1>
                </div>
                <div class="column is-4">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.eksTanganKiri" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Kaki Kanan (termasuk telapak)</h1>
                </div>
                <div class="column is-4">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.eksKakiKanan" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Kaki Kiri (termasuk telapak)</h1>
                </div>
                <div class="column is-4">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.eksKakiKiri" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Jenis Kelamin</h1>
                </div>
                <div class="column is-4">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.jenisKelamin" :attrs="{ value }" placeholder="" label="label"
                        :options="d_jeniskelamin" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.jenisKelamin2" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Lain-Lain</h1>
                </div>
                <div class="column is-10">
                  <VField>
                    <VTextarea rows="8" placeholder="" v-model="input.fetalLainnya"></VTextarea>
                  </VField>
                </div>


                <div class="column is-2">
                  <h1 class="mb-3 emr">Kesimpulan & Saran</h1>
                </div>
                <div class="column is-10">
                  <VField>
                    <VTextarea rows="8" placeholder="" v-model="input.fetalSaran"></VTextarea>
                  </VField>
                </div>
              </div>

            </div>

            <div class="column is-12 mt-5" v-if="input.jenisPemeriksaanObgyn == 'PemeriksaanUSGObstetri'">
              <div class="columns is-multiline">
                <div class="column is-2">
                  <h1 class="mb-3 emr">Kondisi Teknis</h1>
                </div>
                <div class="column is-2">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.kondisiTeknisFetal" :attrs="{ value }" placeholder="" label="label"
                        :options="d_kondisiteknis" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Karena</h1>
                </div>
                <div class="column is-6">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.karenaFetal" placeholder="Karena" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Janin</h1>
                </div>
                <div class="column is-2">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.janin" :attrs="{ value }" placeholder="" label="label"
                        :options="d_janin" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Jumlah Janin</h1>
                </div>
                <div class="column is-6">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.jumlahJanin" placeholder="Karena" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Khorionisitas</h1>
                </div>
                <div class="column is-2">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.khorionisitas" :attrs="{ value }" placeholder="" label="label"
                        :options="d_khorionisitas" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-1">
                  <h1 class="mb-3 emr">DJJ</h1>
                </div>
                <div class="column is-2">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.djj" :attrs="{ value }" placeholder="" label="label" :options="d_djj"
                        class="is-rounded" :searchable="true" track-by="label" mode="single" autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField addons>
                    <VControl expanded>
                      <VInput type="text" class="input is-rounded" placeholder="" v-model="input.ketDJJ" />
                    </VControl>
                    <VControl class="field-addon-body">
                      <VButton static>x/menit</VButton>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-1">
                  <h1 class="mb-3 emr">Fetal Movement</h1>
                </div>
                <div class="column is-2">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.fetalmovement" :attrs="{ value }" placeholder="" label="label"
                        :options="d_djj" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-12">
                  <h1 class="mb-3 emr">Biometri</h1>
                  <table class="w-100">
                    <thead>
                      <tr>
                        <th>Pengukuran</th>
                        <th>mm</th>
                        <th>Usia Kehamilan</th>
                        <th>Keterangan</th>
                      </tr>
                    </thead>
                    <thead>
                      <tr>
                        <td>
                          <h1 class="mb-3 emr">Gestasional sac</h1>
                        </td>
                        <td>
                          <VField>
                            <VControl>
                              <VInput type="text" v-model="input.gestasionalsac" placeholder="" class="is-rounded" />
                            </VControl>
                          </VField>
                        </td>
                        <td>
                          <VField>
                            <VControl>
                              <VInput type="text" v-model="input.usiaGestasional" placeholder="" class="is-rounded" />
                            </VControl>
                          </VField>
                        </td>
                        <td>
                          <div class="column is-12">
                            <div class="columns is-multiline">
                              <div class="column is-2">
                                <h1 class="mb-3 emr">AVE</h1>
                              </div>
                              <div class="column is-10">
                                <VField>
                                  <VControl>
                                    <VInput type="text" v-model="input.ketGestasional" placeholder=""
                                      class="is-rounded" />
                                  </VControl>
                                </VField>
                              </div>
                            </div>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <h1 class="mb-3 emr">Crown-rump lenght</h1>
                        </td>
                        <td>
                          <VField>
                            <VControl>
                              <VInput type="text" v-model="input.crown" placeholder="" class="is-rounded" />
                            </VControl>
                          </VField>
                        </td>
                        <td>
                          <VField>
                            <VControl>
                              <VInput type="text" v-model="input.usiaCrown" placeholder="" class="is-rounded" />
                            </VControl>
                          </VField>
                        </td>
                        <td>
                          <div class="column is-12">
                            <div class="columns is-multiline">
                              <div class="column is-2">
                                <h1 class="mb-3 emr">EDD</h1>
                              </div>
                              <div class="column is-10">
                                <VField>
                                  <VControl>
                                    <VInput type="text" v-model="input.ketCrown" placeholder="" class="is-rounded" />
                                  </VControl>
                                </VField>
                              </div>
                            </div>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <h1 class="mb-3 emr">Biparietal Diameter</h1>
                        </td>
                        <td>
                          <VField>
                            <VControl>
                              <VInput type="text" v-model="input.biparietal" placeholder="" class="is-rounded" />
                            </VControl>
                          </VField>
                        </td>
                        <td>
                          <VField>
                            <VControl>
                              <VInput type="text" v-model="input.usiaBiparietal" placeholder="" class="is-rounded" />
                            </VControl>
                          </VField>
                        </td>
                        <td>
                          <div class="column is-12">
                            <div class="columns is-multiline">
                              <div class="column is-2">
                                <h1 class="mb-3 emr">EFW</h1>
                              </div>
                              <div class="column is-10">
                                <VField>
                                  <VControl>
                                    <VInput type="text" v-model="input.ketBiparietal" placeholder=""
                                      class="is-rounded" />
                                  </VControl>
                                </VField>
                              </div>
                            </div>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <h1 class="mb-3 emr">Head Circumference</h1>
                        </td>
                        <td>
                          <VField>
                            <VControl>
                              <VInput type="text" v-model="input.headcircum" placeholder="" class="is-rounded" />
                            </VControl>
                          </VField>
                        </td>
                        <td>
                          <VField>
                            <VControl>
                              <VInput type="text" v-model="input.usiaHeadcircum" placeholder="" class="is-rounded" />
                            </VControl>
                          </VField>
                        </td>
                        <td>
                          <div class="column is-12">
                            <h1 class="mb-3 emr">Temuan Abnormal:</h1>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <h1 class="mb-3 emr">Abdominal Circumference</h1>
                        </td>
                        <td>
                          <VField>
                            <VControl>
                              <VInput type="text" v-model="input.abdominalcircum" placeholder="" class="is-rounded" />
                            </VControl>
                          </VField>
                        </td>
                        <td>
                          <VField>
                            <VControl>
                              <VInput type="text" v-model="input.usiaAbdominalcircum" placeholder=""
                                class="is-rounded" />
                            </VControl>
                          </VField>
                        </td>
                        <td rowspan="2">
                          <div class="column is-12">
                            <VField>
                              <VTextarea rows="8" placeholder="" v-model="input.Ketabdominalcircum"></VTextarea>
                            </VField>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <h1 class="mb-3 emr">Femoral Lenght</h1>
                        </td>
                        <td>
                          <VField>
                            <VControl>
                              <VInput type="text" v-model="input.femoral" placeholder="" class="is-rounded" />
                            </VControl>
                          </VField>
                        </td>
                        <td>
                          <VField>
                            <VControl>
                              <VInput type="text" v-model="input.usiaFemoral" placeholder="" class="is-rounded" />
                            </VControl>
                          </VField>
                        </td>
                      </tr>
                    </thead>
                  </table>
                </div>
                <div class="column is-1">
                  <h1 class="mb-3 emr">Plasenta</h1>
                </div>
                <div class="column is-2">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.plasenta" :attrs="{ value }" placeholder="" label="label"
                        :options="d_plasenta" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Menutupi</h1>
                </div>
                <div class="column is-2">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.menutupi" :attrs="{ value }" placeholder="" label="label"
                        :options="d_menutupi" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField addons>
                    <VControl expanded>
                      <VInput type="text" class="input is-rounded" placeholder="" v-model="input.ukuranMenutupi" />
                    </VControl>
                    <VControl class="field-addon-body">
                      <VButton static>mm dari OUI</VButton>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-1">
                  <h1 class="mb-3 emr">Maturasi</h1>
                </div>
                <div class="column is-2">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.maturasi" :attrs="{ value }" placeholder="" label="label"
                        :options="d_maturasi" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-1">
                  <h1 class="mb-3 emr">Cairan aminion</h1>
                </div>
                <div class="column is-2">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.cairanaminion" :attrs="{ value }" placeholder="" label="label"
                        :options="d_cairanaminion" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-1">
                  <h1 class="mb-3 emr">AFI</h1>
                </div>
                <div class="column is-1">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.AFI" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-1">
                  <h1 class="mb-3 emr">SDP</h1>
                </div>
                <div class="column is-1">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.SDP" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Temuan Abnormal</h1>
                </div>
                <div class="column is-3">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.temuanAbnormal" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-3">
                  <h1 class="mb-3 emr">Kelainan kongenital mayor</h1>
                </div>
                <div class="column is-3">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.kongenitalMayor" :attrs="{ value }" placeholder="" label="label"
                        :options="d_djj" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Temuan Abnormal</h1>
                </div>
                <div class="column is-4">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.temuanAbnormalKongenital" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-3">
                  <h1 class="mb-3 emr">Adneksa</h1>
                </div>
                <div class="column is-3">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.adneksa" :attrs="{ value }" placeholder="" label="label"
                        :options="d_cairanaminion" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Temuan Abnormal</h1>
                </div>
                <div class="column is-4">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.temuanAbnormalAdneksa" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>

                <br>
                <hr><br>

                <div class="column is-12" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Doppler Velocimetry</h1>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Arteri Uterina</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.arteriUterina" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Arteri Umbilicalis</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.arteriUmbilicalis" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">RI</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.riUterina" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">RI</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.riUmbilicalis" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">PI</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.piUterina" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">PI</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.piUmbilicalis" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">S/D Ratio</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.ratioUterina" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">S/D Ratio</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.ratioUmbilicalis" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>

                <div class="column is-2 mt-5" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Ductus Venosus</h1>
                </div>
                <div class="column is-4 mt-5" v-if="isFetal == true">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.ductusVenosus" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2 mt-5" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Arteri Serebi Media</h1>
                </div>
                <div class="column is-4 mt-5" v-if="isFetal == true">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.arteriSerebi" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">RI</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.riDuctus" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">RI</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.riSerebi" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">PI</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.piDuctus" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">PI</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.piSerebi" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">S/D Ratio</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.ratioDuctus" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">S/D Ratio</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.ratioSerebi" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-10" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Kepala</h1>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <VField>
                    <VControl>
                      <VCheckbox class="fontcheckbox" v-model="input.batasnormalfsk" true-value="Dalam Batas Normal"
                        label="Dalam Batas Normal" color="primary" circle @click="batasnormalfisik" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Bentuk</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.bentukkepala" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Ventrikel lateral</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.ventrikellateral" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Cavum septum pellucidum</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.cavumseptum" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Cerebellum</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.cerebellum" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Falx cerebri</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.falx" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Cistema magna</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.cistema" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Thalamus</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.thalamus" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6" v-if="isFetal == true"></div>
                <div class="column is-12" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Wajah</h1>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Bibir atas</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.bibiratas" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Hidung</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.hidung" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Profil Mediana</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.mediana" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Lubang Hidung</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.lubanghidung" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Orbita</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.orbita" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Leher</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.leher" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6" v-if="isFetal == true"></div>
                <div class="column is-12" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Thorax</h1>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Bentuk</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.bentukThorax" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Tidak Tampak Massa</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.tidakTampakMassa" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-12" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Jantung</h1>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Aktifitas Jantung</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.aktifitasJantung" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Faur chamber view</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.faur" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Ukuran</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.ukuranJantung" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Left verticular outlow</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.leftVerticular" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Axis Jantung</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.axisJantung" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Right verticular outlow</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.rightVerticular" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-12" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Abdomen</h1>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Gaster</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.gaster" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Vesica Urinaria</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.vesicaUrinaria" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Usus</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.usus" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Insersi Umbilicilus</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.insersiUmbilicilus" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Ginjal</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.ginjal" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Vescular Umbilicilus</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.vescularUmbilicilus" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Vertebra</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.vertebra" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6" v-if="isFetal == true"></div>
                <div class="column is-12" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Ekstremitas</h1>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Tangan Kanan (termasuk telapak)</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.eksTanganKanan" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Tangan Kiri (termasuk telapak)</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.eksTanganKiri" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Kaki Kanan (termasuk telapak)</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.eksKakiKanan" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Kaki Kiri (termasuk telapak)</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.eksKakiKiri" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Jenis Kelamin</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.jenisKelamin" :attrs="{ value }" placeholder="" label="label"
                        :options="d_jeniskelamin" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6" v-if="isFetal == true">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.jenisKelamin2" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Lain-Lain</h1>
                </div>
                <div class="column is-10" v-if="isFetal == true">
                  <VField>
                    <VTextarea rows="8" placeholder="" v-model="input.fetalLainnya"></VTextarea>
                  </VField>
                </div>


                <div class="column is-2">
                  <h1 class="mb-3 emr">Kesimpulan & Saran</h1>
                </div>
                <div class="column is-10">
                  <VField>
                    <VTextarea rows="8" placeholder="" v-model="input.fetalSaran"></VTextarea>
                  </VField>
                </div>
              </div>
            </div>

            <div class="column is-12 mt-5" v-if="input.jenisPemeriksaanObgyn == 'PemeriksaanKardiotokografi'">
              <div class="columns is-multiline">
                <div class="column is-2">
                  <h1 class="mb-3 emr">TD Awal</h1>
                </div>
                <div class="column is-4">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.tdawal" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">TD Menit Ke-15</h1>
                </div>
                <div class="column is-4">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.tg15" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Cara Pantau</h1>
                </div>
                <div class="column is-4">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.carapantau" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Kecepatan Kertas</h1>
                </div>
                <div class="column is-4">
                  <VField addons>
                    <VControl expanded>
                      <VInput type="text" class="input is-rounded" placeholder="" v-model="input.kecepatankertas" />
                    </VControl>
                    <VControl class="field-addon-body">
                      <VButton static>cm/menit</VButton>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Periksa Dalam</h1>
                </div>
                <div class="column is-4">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.periksaDalam" :attrs="{ value }" placeholder="" label="label"
                        :options="d_periksadalam" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Dengan Hasil</h1>
                </div>
                <div class="column is-4">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.denganhasil" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Diagnosis</h1>
                </div>
                <div class="column is-4">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.diagnosis" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6"></div>

                <br>
                <hr><br>

                <div class="column is-2">
                  <h1 class="mb-3 emr">Denyut Jantung Janin</h1>
                </div>
                <div class="column is-4">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.denyutjantung" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6"></div>

                <div class="column is-12" style="margin-top: 100px;">
                  <div class="columns is-multiline">
                    <div class="column is-4">
                      <div class="columns is-multiline">
                        <div class="column is-4">
                          <h1 class="mb-3 emr">Frekuensi Dasar</h1>
                        </div>
                        <div class="column is-8">
                          <VField addons>
                            <VControl expanded>
                              <VInput type="text" class="input is-rounded" placeholder=""
                                v-model="input.frekuensidasar" />
                            </VControl>
                            <VControl class="field-addon-body">
                              <VButton static>dpm</VButton>
                            </VControl>
                          </VField>
                        </div>
                      </div>
                    </div>
                    <div class="column is-4">
                      <div class="columns is-multiline">
                        <div class="column is-4">
                          <h1 class="mb-3 emr">Akselerasi</h1>
                        </div>
                        <div class="column is-8">
                          <VField class="is-autocomplete-select" v-slot="{ id }">
                            <VControl>
                              <Multiselect v-model="input.akselerasi" :attrs="{ value }" placeholder="" label="label"
                                :options="d_akselerasi" class="is-rounded" :searchable="true" track-by="label"
                                mode="single" autocomplete="off">
                              </Multiselect>
                            </VControl>
                          </VField>
                        </div>
                      </div>
                    </div>
                    <div class="column is-4">
                      <div class="columns is-multiline">
                        <div class="column is-4">
                          <h1 class="mb-3 emr">Deselerasi</h1>
                        </div>
                        <div class="column is-8">
                          <VField class="is-autocomplete-select" v-slot="{ id }">
                            <VControl>
                              <Multiselect v-model="input.deselerasi" :attrs="{ value }" placeholder="" label="label"
                                :options="d_akselerasi" class="is-rounded" :searchable="true" track-by="label"
                                mode="single" autocomplete="off">
                              </Multiselect>
                            </VControl>
                          </VField>
                        </div>
                      </div>
                    </div>
                    <div class="column is-4">
                      <div class="columns is-multiline">
                        <div class="column is-4">
                          <h1 class="mb-3 emr">Variabilitas</h1>
                        </div>
                        <div class="column is-8">
                          <VField class="is-autocomplete-select" v-slot="{ id }">
                            <VControl>
                              <Multiselect v-model="input.variabilitas" :attrs="{ value }" placeholder="" label="label"
                                :options="d_variabilitas" class="is-rounded" :searchable="true" track-by="label"
                                mode="single" autocomplete="off">
                              </Multiselect>
                            </VControl>
                          </VField>
                        </div>
                      </div>
                    </div>
                    <div class="column is-4">
                      <div class="columns is-multiline">
                        <div class="column is-4">
                          <h1 class="mb-3 emr">Jenisnya</h1>
                        </div>
                        <div class="column is-8">
                          <VField class="is-autocomplete-select" v-slot="{ id }">
                            <VControl>
                              <Multiselect v-model="input.jenisnya" :attrs="{ value }" placeholder="" label="label"
                                :options="d_jenisnya" class="is-rounded" :searchable="true" track-by="label"
                                mode="single" autocomplete="off">
                              </Multiselect>
                            </VControl>
                          </VField>
                        </div>
                      </div>
                    </div>
                    <div class="column is-4">
                      <div class="columns is-multiline">
                        <div class="column is-4">
                          <h1 class="mb-3 emr">Beratnya</h1>
                        </div>
                        <div class="column is-8">
                          <VField class="is-autocomplete-select" v-slot="{ id }">
                            <VControl>
                              <Multiselect v-model="input.beratnya" :attrs="{ value }" placeholder="" label="label"
                                :options="d_beratnya" class="is-rounded" :searchable="true" track-by="label"
                                mode="single" autocomplete="off">
                              </Multiselect>
                            </VControl>
                          </VField>
                        </div>
                      </div>
                    </div>
                    <div class="column is-4">
                      <div class="columns is-multiline">
                        <div class="column is-4">
                          <h1 class="mb-3 emr">Pola disfungsi SSP</h1>
                        </div>
                        <div class="column is-8">
                          <VField class="is-autocomplete-select" v-slot="{ id }">
                            <VControl>
                              <Multiselect v-model="input.ssp" :attrs="{ value }" placeholder="" label="label"
                                :options="d_akselerasi" class="is-rounded" :searchable="true" track-by="label"
                                mode="single" autocomplete="off">
                              </Multiselect>
                            </VControl>
                          </VField>
                        </div>
                      </div>
                    </div>
                    <div class="column is-4">
                      <div class="columns is-multiline">
                        <div class="column is-4">
                          <h1 class="mb-3 emr">Yaitu</h1>
                        </div>
                        <div class="column is-8">
                          <VField class="is-autocomplete-select" v-slot="{ id }">
                            <VControl>
                              <Multiselect v-model="input.yaitu" :attrs="{ value }" placeholder="" label="label"
                                :options="d_yaitu" class="is-rounded" :searchable="true" track-by="label" mode="single"
                                autocomplete="off">
                              </Multiselect>
                            </VControl>
                          </VField>
                        </div>
                      </div>
                    </div>
                    <div class="column is-4"></div>

                    <br>
                    <hr><br>

                    <div class="column is-4">
                      <div class="columns is-multiline">
                        <div class="column is-4">
                          <h1 class="mb-3 emr">Kontraksi Uterus/His</h1>
                        </div>
                        <div class="column is-8">
                          <VField class="is-autocomplete-select" v-slot="{ id }">
                            <VControl>
                              <Multiselect v-model="input.kontraksi" :attrs="{ value }" placeholder="" label="label"
                                :options="d_akselerasi" class="is-rounded" :searchable="true" track-by="label"
                                mode="single" autocomplete="off">
                              </Multiselect>
                            </VControl>
                          </VField>
                        </div>
                      </div>
                    </div>
                    <div class="column is-8"></div>
                    <div class="column is-4">
                      <div class="columns is-multiline">
                        <div class="column is-4">
                          <h1 class="mb-3 emr">Frekuensi</h1>
                        </div>
                        <div class="column is-8">
                          <VField>
                            <VField addons>
                              <VControl expanded>
                                <VInput type="text" class="input" placeholder="" v-model.number="input.frekuensi" />
                              </VControl>
                              <VControl class="field-addon-body">
                                <VButton static>/10menit</VButton>
                              </VControl>
                            </VField>
                          </VField>
                        </div>
                      </div>
                    </div>
                    <div class="column is-4">
                      <div class="columns is-multiline">
                        <div class="column is-4">
                          <h1 class="mb-3 emr">Kekuatan</h1>
                        </div>
                        <div class="column is-8">
                          <VField>
                            <VField addons>
                              <VControl expanded>
                                <VInput type="text" class="input" placeholder="" v-model.number="input.kekuatan" />
                              </VControl>
                              <VControl class="field-addon-body">
                                <VButton static>mmHg</VButton>
                              </VControl>
                            </VField>
                          </VField>
                        </div>
                      </div>
                    </div>
                    <div class="column is-4">
                      <div class="columns is-multiline">
                        <div class="column is-4">
                          <h1 class="mb-3 emr">Lamanya</h1>
                        </div>
                        <div class="column is-8">
                          <VField>
                            <VField addons>
                              <VControl expanded>
                                <VInput type="text" class="input" placeholder="" v-model.number="input.lamanya" />
                              </VControl>
                              <VControl class="field-addon-body">
                                <VButton static>menit</VButton>
                              </VControl>
                            </VField>
                          </VField>
                        </div>
                      </div>
                    </div>
                    <div class="column is-4">
                      <div class="columns is-multiline">
                        <div class="column is-4">
                          <h1 class="mb-3 emr">Relaksasi</h1>
                        </div>
                        <div class="column is-8">
                          <VField>
                            <VControl>
                              <VInput type="text" v-model="input.relaksasi" placeholder="" class="is-rounded" />
                            </VControl>
                          </VField>
                        </div>
                      </div>
                    </div>
                    <div class="column is-4">
                      <div class="columns is-multiline">
                        <div class="column is-4">
                          <h1 class="mb-3 emr">Konfigurasi</h1>
                        </div>
                        <div class="column is-8">
                          <VField>
                            <VControl>
                              <VInput type="text" v-model="input.konfigurasi" placeholder="" class="is-rounded" />
                            </VControl>
                          </VField>
                        </div>
                      </div>
                    </div>
                    <div class="column is-4">
                      <div class="columns is-multiline">
                        <div class="column is-4">
                          <h1 class="mb-3 emr">Tumus Dasar</h1>
                        </div>
                        <div class="column is-8">
                          <VField>
                            <VField addons>
                              <VControl expanded>
                                <VInput type="text" class="input" placeholder="" v-model.number="input.tumusdasar" />
                              </VControl>
                              <VControl class="field-addon-body">
                                <VButton static>mmHg</VButton>
                              </VControl>
                            </VField>
                          </VField>
                        </div>
                      </div>
                    </div>

                    <br>
                    <hr><br>

                    <div class="column is-4">
                      <div class="columns is-multiline">
                        <div class="column is-4">
                          <h1 class="mb-3 emr">Gerak Janin</h1>
                        </div>
                        <div class="column is-8">
                          <VField>
                            <VField addons>
                              <VControl expanded>
                                <VInput type="text" class="input" placeholder="" v-model.number="input.gerakjanin" />
                              </VControl>
                              <VControl class="field-addon-body">
                                <VButton static>kali</VButton>
                              </VControl>
                            </VField>
                          </VField>
                        </div>
                      </div>
                    </div>
                    <div class="column is-4">
                      <div class="columns is-multiline">
                        <div class="column is-4">
                          <h1 class="mb-3 emr">dalam</h1>
                        </div>
                        <div class="column is-8">
                          <VField>
                            <VField addons>
                              <VControl expanded>
                                <VInput type="text" class="input" placeholder="" v-model.number="input.lamagerak" />
                              </VControl>
                              <VControl class="field-addon-body">
                                <VButton static>menit</VButton>
                              </VControl>
                            </VField>
                          </VField>
                        </div>
                      </div>
                    </div>
                    <div class="column is-4"></div>
                    <div class="column is-4">
                      <div class="columns is-multiline">
                        <div class="column is-4">
                          <h1 class="mb-3 emr">Diagnosis KTG</h1>
                        </div>
                        <div class="column is-8">
                          <VField>
                            <VControl>
                              <VInput type="text" v-model="input.diagnosisktg" placeholder="" class="is-rounded" />
                            </VControl>
                          </VField>
                        </div>
                      </div>
                    </div>
                    <div class="column is-4">
                      <div class="columns is-multiline">
                        <div class="column is-4">
                          <h1 class="mb-3 emr">Kategori</h1>
                        </div>
                        <div class="column is-8">
                          <VField class="is-autocomplete-select" v-slot="{ id }">
                            <VControl>
                              <Multiselect v-model="input.kategoridiagnosisktg" :attrs="{ value }" placeholder=""
                                label="label" :options="d_kategori" class="is-rounded" :searchable="true"
                                track-by="label" mode="single" autocomplete="off">
                              </Multiselect>
                            </VControl>
                          </VField>
                        </div>
                      </div>
                    </div>
                    <div class="column is-4"></div>
                    <div class="column is-12">
                      <h1 class="mb-3 emr">Saran</h1>
                      <VField>
                        <VTextarea rows="8" placeholder="Saran..." v-model="input.saran"></VTextarea>
                      </VField>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="column is-12 mt-5" v-if="input.jenisPemeriksaanObgyn == 'PemeriksaanUSGGynekologi'">
              <div class="columns is-multiline">
                <div class="column is-2">
                  <h1 class="mb-3 emr">Kondisi Teknis</h1>
                </div>
                <div class="column is-4">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.kondisiTeknis" :attrs="{ value }" placeholder="" label="label"
                        :options="d_kondisiteknis" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Karena</h1>
                </div>
                <div class="column is-4">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.karena" placeholder="Karena" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Vesica Urinaria</h1>
                </div>
                <div class="column is-4">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.vesicaUrinaria" :attrs="{ value }" placeholder="" label="label"
                        :options="d_vesicaurinaria" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Cairan Bebas</h1>
                </div>
                <div class="column is-4">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.cairanBebas" :attrs="{ value }" placeholder="" label="label"
                        :options="d_akselerasi" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Uterus</h1>
                </div>
                <div class="column is-4">
                  <VField>
                    <VTextarea rows="8" placeholder="" v-model="input.uterus"></VTextarea>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Adnexa</h1>
                </div>
                <div class="column is-4">
                  <VField>
                    <VTextarea rows="8" placeholder="" v-model="input.adnexa"></VTextarea>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Lain-Lain</h1>
                </div>
                <div class="column is-4">
                  <VField>
                    <VTextarea rows="8" placeholder="" v-model="input.obstetriLainnya"></VTextarea>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Kesimpulan & Saran</h1>
                </div>
                <div class="column is-4">
                  <VField>
                    <VTextarea rows="8" placeholder="" v-model="input.kesimpulansaran"></VTextarea>
                  </VField>
                </div>
              </div>
            </div>
          </VCard>
        </div>
        <div class="column is-12"
          v-if="isInputData === true && isRiwayat === false && props.registrasi.objectruanganlastfk == 402">
          <VCard>
            <div class="column is-12">
              <div class="columns is-multiline">
                <div class="column is-2">
                  <h1 class="mb-3 emr">NRM</h1>
                </div>
                <div class="column is-4">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.norm" placeholder="NRM" class="is-rounded" disabled />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Tanggal/Jam</h1>
                </div>
                <div class="column is-4">
                  <VDatePicker v-model="input.tglPembuatan" mode="date" trim-weeks>
                    <template #default="{ inputValue, inputEvents }">
                      <VControl icon="feather:calendar" fullwidth>
                        <VInput :value="inputValue" v-on="inputEvents" />
                      </VControl>
                    </template>
                  </VDatePicker>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Nama Pasien</h1>
                </div>
                <div class="column is-4">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.namaPasien" placeholder="Nama Pasien" class="is-rounded"
                        disabled />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Dokter Pemeriksa</h1>
                </div>
                <div class="column is-4">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl icon="feather:search">
                      <AutoComplete v-model="input.dokterPemeriksa" :suggestions="d_Dokter"
                        @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                        :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder=""
                        class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Tanggal Lahir</h1>
                </div>
                <div class="column is-1">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.tanggalLahirPasien" placeholder="Tanggal Lahir"
                        class="is-rounded" disabled />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Umur</h1>
                </div>
                <div class="column is-1">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.umur" placeholder="Umur" class="is-rounded" disabled />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">HPHT</h1>
                </div>
                <div class="column is-4">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.hpht" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Alamat</h1>
                </div>
                <div class="column is-4">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.alamat" placeholder="Alamat" class="is-rounded" disabled />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Diagnosa</h1>
                </div>
                <div class="column is-4">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.diagnosa" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>

              </div>
            </div>
            <div class="column is-flex p-0">
              <div class="column is-2">
                <h1>Jenis Pemeriksaan:</h1>
              </div>
              <div class="column is-10">
                <VField class="is-autocomplete-select" v-slot="{ id }">
                  <VControl>
                    <Multiselect v-model="input.jenisPemeriksaanObgyn" placeholder="--Pilih--" label="label"
                      :options="jenisPemeriksaanObgyn" :searchable="true" track-by="label" mode="single"
                      autocomplete="off">
                    </Multiselect>
                  </VControl>
                </VField>
              </div>
            </div>

            <div class="column is-12 mt-5" v-if="input.jenisPemeriksaanObgyn == 'PemeriksaanUSGFetal'">
              <div class="columns is-multiline">
                <div class="column is-2">
                  <h1 class="mb-3 emr">Kondisi Teknis</h1>
                </div>
                <div class="column is-2">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.kondisiTeknisFetal" :attrs="{ value }" placeholder="" label="label"
                        :options="d_kondisiteknis" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Karena</h1>
                </div>
                <div class="column is-6">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.karenaFetal" placeholder="Karena" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Janin</h1>
                </div>
                <div class="column is-2">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.janin" :attrs="{ value }" placeholder="" label="label"
                        :options="d_janin" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Jumlah Janin</h1>
                </div>
                <div class="column is-6">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.jumlahJanin" placeholder="Karena" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Khorionisitas</h1>
                </div>
                <div class="column is-2">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.khorionisitas" :attrs="{ value }" placeholder="" label="label"
                        :options="d_khorionisitas" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-1">
                  <h1 class="mb-3 emr">DJJ</h1>
                </div>
                <div class="column is-2">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.djj" :attrs="{ value }" placeholder="" label="label" :options="d_djj"
                        class="is-rounded" :searchable="true" track-by="label" mode="single" autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField addons>
                    <VControl expanded>
                      <VInput type="text" class="input is-rounded" placeholder="" v-model="input.ketDJJ" />
                    </VControl>
                    <VControl class="field-addon-body">
                      <VButton static>x/menit</VButton>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-1">
                  <h1 class="mb-3 emr">Fetal Movement</h1>
                </div>
                <div class="column is-2">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.fetalmovement" :attrs="{ value }" placeholder="" label="label"
                        :options="d_djj" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-12">
                  <h1 class="mb-3 emr">Biometri</h1>
                  <table class="w-100">
                    <thead>
                      <tr>
                        <th>Pengukuran</th>
                        <th>mm</th>
                        <th>Usia Kehamilan</th>
                        <th>Keterangan</th>
                      </tr>
                    </thead>
                    <thead>
                      <tr>
                        <td>
                          <h1 class="mb-3 emr">Gestasional sac</h1>
                        </td>
                        <td>
                          <VField>
                            <VControl>
                              <VInput type="text" v-model="input.gestasionalsac" placeholder="" class="is-rounded" />
                            </VControl>
                          </VField>
                        </td>
                        <td>
                          <VField>
                            <VControl>
                              <VInput type="text" v-model="input.usiaGestasional" placeholder="" class="is-rounded" />
                            </VControl>
                          </VField>
                        </td>
                        <td>
                          <div class="column is-12">
                            <div class="columns is-multiline">
                              <div class="column is-2">
                                <h1 class="mb-3 emr">AVE</h1>
                              </div>
                              <div class="column is-10">
                                <VField>
                                  <VControl>
                                    <VInput type="text" v-model="input.ketGestasional" placeholder=""
                                      class="is-rounded" />
                                  </VControl>
                                </VField>
                              </div>
                            </div>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <h1 class="mb-3 emr">Crown-rump lenght</h1>
                        </td>
                        <td>
                          <VField>
                            <VControl>
                              <VInput type="text" v-model="input.crown" placeholder="" class="is-rounded" />
                            </VControl>
                          </VField>
                        </td>
                        <td>
                          <VField>
                            <VControl>
                              <VInput type="text" v-model="input.usiaCrown" placeholder="" class="is-rounded" />
                            </VControl>
                          </VField>
                        </td>
                        <td>
                          <div class="column is-12">
                            <div class="columns is-multiline">
                              <div class="column is-2">
                                <h1 class="mb-3 emr">EDD</h1>
                              </div>
                              <div class="column is-10">
                                <VField>
                                  <VControl>
                                    <VInput type="text" v-model="input.ketCrown" placeholder="" class="is-rounded" />
                                  </VControl>
                                </VField>
                              </div>
                            </div>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <h1 class="mb-3 emr">Biparietal Diameter</h1>
                        </td>
                        <td>
                          <VField>
                            <VControl>
                              <VInput type="text" v-model="input.biparietal" placeholder="" class="is-rounded" />
                            </VControl>
                          </VField>
                        </td>
                        <td>
                          <VField>
                            <VControl>
                              <VInput type="text" v-model="input.usiaBiparietal" placeholder="" class="is-rounded" />
                            </VControl>
                          </VField>
                        </td>
                        <td>
                          <div class="column is-12">
                            <div class="columns is-multiline">
                              <div class="column is-2">
                                <h1 class="mb-3 emr">EFW</h1>
                              </div>
                              <div class="column is-10">
                                <VField>
                                  <VControl>
                                    <VInput type="text" v-model="input.ketBiparietal" placeholder=""
                                      class="is-rounded" />
                                  </VControl>
                                </VField>
                              </div>
                            </div>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <h1 class="mb-3 emr">Head Circumference</h1>
                        </td>
                        <td>
                          <VField>
                            <VControl>
                              <VInput type="text" v-model="input.headcircum" placeholder="" class="is-rounded" />
                            </VControl>
                          </VField>
                        </td>
                        <td>
                          <VField>
                            <VControl>
                              <VInput type="text" v-model="input.usiaHeadcircum" placeholder="" class="is-rounded" />
                            </VControl>
                          </VField>
                        </td>
                        <td>
                          <div class="column is-12">
                            <h1 class="mb-3 emr">Temuan Abnormal:</h1>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <h1 class="mb-3 emr">Abdominal Circumference</h1>
                        </td>
                        <td>
                          <VField>
                            <VControl>
                              <VInput type="text" v-model="input.abdominalcircum" placeholder="" class="is-rounded" />
                            </VControl>
                          </VField>
                        </td>
                        <td>
                          <VField>
                            <VControl>
                              <VInput type="text" v-model="input.usiaAbdominalcircum" placeholder=""
                                class="is-rounded" />
                            </VControl>
                          </VField>
                        </td>
                        <td rowspan="2">
                          <div class="column is-12">
                            <VField>
                              <VTextarea rows="8" placeholder="" v-model="input.Ketabdominalcircum"></VTextarea>
                            </VField>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <h1 class="mb-3 emr">Femoral Lenght</h1>
                        </td>
                        <td>
                          <VField>
                            <VControl>
                              <VInput type="text" v-model="input.femoral" placeholder="" class="is-rounded" />
                            </VControl>
                          </VField>
                        </td>
                        <td>
                          <VField>
                            <VControl>
                              <VInput type="text" v-model="input.usiaFemoral" placeholder="" class="is-rounded" />
                            </VControl>
                          </VField>
                        </td>
                      </tr>
                    </thead>
                  </table>
                </div>
                <div class="column is-1">
                  <h1 class="mb-3 emr">Plasenta</h1>
                </div>
                <div class="column is-2">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.plasenta" :attrs="{ value }" placeholder="" label="label"
                        :options="d_plasenta" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Menutupi</h1>
                </div>
                <div class="column is-2">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.menutupi" :attrs="{ value }" placeholder="" label="label"
                        :options="d_menutupi" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField addons>
                    <VControl expanded>
                      <VInput type="text" class="input is-rounded" placeholder="" v-model="input.ukuranMenutupi" />
                    </VControl>
                    <VControl class="field-addon-body">
                      <VButton static>mm dari OUI</VButton>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-1">
                  <h1 class="mb-3 emr">Maturasi</h1>
                </div>
                <div class="column is-2">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.maturasi" :attrs="{ value }" placeholder="" label="label"
                        :options="d_maturasi" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-1">
                  <h1 class="mb-3 emr">Cairan aminion</h1>
                </div>
                <div class="column is-2">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.cairanaminion" :attrs="{ value }" placeholder="" label="label"
                        :options="d_cairanaminion" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-1">
                  <h1 class="mb-3 emr">AFI</h1>
                </div>
                <div class="column is-1">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.AFI" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-1">
                  <h1 class="mb-3 emr">SDP</h1>
                </div>
                <div class="column is-1">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.SDP" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Temuan Abnormal</h1>
                </div>
                <div class="column is-3">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.temuanAbnormal" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-3">
                  <h1 class="mb-3 emr">Kelainan kongenital mayor</h1>
                </div>
                <div class="column is-3">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.kongenitalMayor" :attrs="{ value }" placeholder="" label="label"
                        :options="d_djj" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Temuan Abnormal</h1>
                </div>
                <div class="column is-4">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.temuanAbnormalKongenital" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-3">
                  <h1 class="mb-3 emr">Adneksa</h1>
                </div>
                <div class="column is-3">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.adneksa" :attrs="{ value }" placeholder="" label="label"
                        :options="d_cairanaminion" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Temuan Abnormal</h1>
                </div>
                <div class="column is-4">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.temuanAbnormalAdneksa" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>

                <br>
                <hr><br>

                <div class="column is-12">
                  <h1 class="mb-3 emr">Doppler Velocimetry</h1>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Arteri Uterina</h1>
                </div>
                <div class="column is-4">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.arteriUterina" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Arteri Umbilicalis</h1>
                </div>
                <div class="column is-4">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.arteriUmbilicalis" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">RI</h1>
                </div>
                <div class="column is-4">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.riUterina" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">RI</h1>
                </div>
                <div class="column is-4">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.riUmbilicalis" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">PI</h1>
                </div>
                <div class="column is-4">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.piUterina" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">PI</h1>
                </div>
                <div class="column is-4">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.piUmbilicalis" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">S/D Ratio</h1>
                </div>
                <div class="column is-4">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.ratioUterina" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">S/D Ratio</h1>
                </div>
                <div class="column is-4">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.ratioUmbilicalis" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>

                <div class="column is-2 mt-5">
                  <h1 class="mb-3 emr">Ductus Venosus</h1>
                </div>
                <div class="column is-4 mt-5">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.ductusVenosus" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2 mt-5">
                  <h1 class="mb-3 emr">Arteri Serebi Media</h1>
                </div>
                <div class="column is-4 mt-5">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.arteriSerebi" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">RI</h1>
                </div>
                <div class="column is-4">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.riDuctus" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">RI</h1>
                </div>
                <div class="column is-4">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.riSerebi" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">PI</h1>
                </div>
                <div class="column is-4">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.piDuctus" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">PI</h1>
                </div>
                <div class="column is-4">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.piSerebi" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">S/D Ratio</h1>
                </div>
                <div class="column is-4">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.ratioDuctus" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">S/D Ratio</h1>
                </div>
                <div class="column is-4">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.ratioSerebi" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-10">
                  <h1 class="mb-3 emr">Kepala</h1>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl>
                      <VCheckbox class="fontcheckbox" v-model="input.batasnormalfsk" true-value="Dalam Batas Normal"
                        label="Dalam Batas Normal" color="primary" circle @click="batasnormalfisik" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Bentuk</h1>
                </div>
                <div class="column is-4">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.bentukkepala" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Ventrikel lateral</h1>
                </div>
                <div class="column is-4">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.ventrikellateral" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Cavum septum pellucidum</h1>
                </div>
                <div class="column is-4">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.cavumseptum" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Cerebellum</h1>
                </div>
                <div class="column is-4">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.cerebellum" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Falx cerebri</h1>
                </div>
                <div class="column is-4">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.falx" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Cistema magna</h1>
                </div>
                <div class="column is-4">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.cistema" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Thalamus</h1>
                </div>
                <div class="column is-4">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.thalamus" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6"></div>
                <div class="column is-12">
                  <h1 class="mb-3 emr">Wajah</h1>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Bibir atas</h1>
                </div>
                <div class="column is-4">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.bibiratas" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Hidung</h1>
                </div>
                <div class="column is-4">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.hidung" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Profil Mediana</h1>
                </div>
                <div class="column is-4">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.mediana" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Lubang Hidung</h1>
                </div>
                <div class="column is-4">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.lubanghidung" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Orbita</h1>
                </div>
                <div class="column is-4">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.orbita" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Leher</h1>
                </div>
                <div class="column is-4">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.leher" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6"></div>
                <div class="column is-12">
                  <h1 class="mb-3 emr">Thorax</h1>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Bentuk</h1>
                </div>
                <div class="column is-4">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.bentukThorax" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Tidak Tampak Massa</h1>
                </div>
                <div class="column is-4">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.tidakTampakMassa" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-12">
                  <h1 class="mb-3 emr">Jantung</h1>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Aktifitas Jantung</h1>
                </div>
                <div class="column is-4">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.aktifitasJantung" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Faur chamber view</h1>
                </div>
                <div class="column is-4">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.faur" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Ukuran</h1>
                </div>
                <div class="column is-4">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.ukuranJantung" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Left verticular outlow</h1>
                </div>
                <div class="column is-4">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.leftVerticular" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Axis Jantung</h1>
                </div>
                <div class="column is-4">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.axisJantung" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Right verticular outlow</h1>
                </div>
                <div class="column is-4">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.rightVerticular" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-12">
                  <h1 class="mb-3 emr">Abdomen</h1>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Gaster</h1>
                </div>
                <div class="column is-4">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.gaster" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Vesica Urinaria</h1>
                </div>
                <div class="column is-4">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.vesicaUrinaria" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Usus</h1>
                </div>
                <div class="column is-4">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.usus" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Insersi Umbilicilus</h1>
                </div>
                <div class="column is-4">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.insersiUmbilicilus" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Ginjal</h1>
                </div>
                <div class="column is-4">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.ginjal" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Vescular Umbilicilus</h1>
                </div>
                <div class="column is-4">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.vescularUmbilicilus" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Vertebra</h1>
                </div>
                <div class="column is-4">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.vertebra" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6"></div>
                <div class="column is-12">
                  <h1 class="mb-3 emr">Ekstremitas</h1>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Tangan Kanan (termasuk telapak)</h1>
                </div>
                <div class="column is-4">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.eksTanganKanan" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Tangan Kiri (termasuk telapak)</h1>
                </div>
                <div class="column is-4">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.eksTanganKiri" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Kaki Kanan (termasuk telapak)</h1>
                </div>
                <div class="column is-4">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.eksKakiKanan" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Kaki Kiri (termasuk telapak)</h1>
                </div>
                <div class="column is-4">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.eksKakiKiri" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Jenis Kelamin</h1>
                </div>
                <div class="column is-4">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.jenisKelamin" :attrs="{ value }" placeholder="" label="label"
                        :options="d_jeniskelamin" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.jenisKelamin2" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Lain-Lain</h1>
                </div>
                <div class="column is-10">
                  <VField>
                    <VTextarea rows="8" placeholder="" v-model="input.fetalLainnya"></VTextarea>
                  </VField>
                </div>


                <div class="column is-2">
                  <h1 class="mb-3 emr">Kesimpulan & Saran</h1>
                </div>
                <div class="column is-10">
                  <VField>
                    <VTextarea rows="8" placeholder="" v-model="input.fetalSaran"></VTextarea>
                  </VField>
                </div>
              </div>

            </div>

            <div class="column is-12 mt-5" v-if="input.jenisPemeriksaanObgyn == 'PemeriksaanUSGObstetri'">
              <div class="columns is-multiline">
                <div class="column is-2">
                  <h1 class="mb-3 emr">Kondisi Teknis</h1>
                </div>
                <div class="column is-2">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.kondisiTeknisFetal" :attrs="{ value }" placeholder="" label="label"
                        :options="d_kondisiteknis" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Karena</h1>
                </div>
                <div class="column is-6">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.karenaFetal" placeholder="Karena" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Janin</h1>
                </div>
                <div class="column is-2">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.janin" :attrs="{ value }" placeholder="" label="label"
                        :options="d_janin" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Jumlah Janin</h1>
                </div>
                <div class="column is-6">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.jumlahJanin" placeholder="Karena" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Khorionisitas</h1>
                </div>
                <div class="column is-2">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.khorionisitas" :attrs="{ value }" placeholder="" label="label"
                        :options="d_khorionisitas" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-1">
                  <h1 class="mb-3 emr">DJJ</h1>
                </div>
                <div class="column is-2">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.djj" :attrs="{ value }" placeholder="" label="label" :options="d_djj"
                        class="is-rounded" :searchable="true" track-by="label" mode="single" autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField addons>
                    <VControl expanded>
                      <VInput type="text" class="input is-rounded" placeholder="" v-model="input.ketDJJ" />
                    </VControl>
                    <VControl class="field-addon-body">
                      <VButton static>x/menit</VButton>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-1">
                  <h1 class="mb-3 emr">Fetal Movement</h1>
                </div>
                <div class="column is-2">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.fetalmovement" :attrs="{ value }" placeholder="" label="label"
                        :options="d_djj" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-12">
                  <h1 class="mb-3 emr">Biometri</h1>
                  <table class="w-100">
                    <thead>
                      <tr>
                        <th>Pengukuran</th>
                        <th>mm</th>
                        <th>Usia Kehamilan</th>
                        <th>Keterangan</th>
                      </tr>
                    </thead>
                    <thead>
                      <tr>
                        <td>
                          <h1 class="mb-3 emr">Gestasional sac</h1>
                        </td>
                        <td>
                          <VField>
                            <VControl>
                              <VInput type="text" v-model="input.gestasionalsac" placeholder="" class="is-rounded" />
                            </VControl>
                          </VField>
                        </td>
                        <td>
                          <VField>
                            <VControl>
                              <VInput type="text" v-model="input.usiaGestasional" placeholder="" class="is-rounded" />
                            </VControl>
                          </VField>
                        </td>
                        <td>
                          <div class="column is-12">
                            <div class="columns is-multiline">
                              <div class="column is-2">
                                <h1 class="mb-3 emr">AVE</h1>
                              </div>
                              <div class="column is-10">
                                <VField>
                                  <VControl>
                                    <VInput type="text" v-model="input.ketGestasional" placeholder=""
                                      class="is-rounded" />
                                  </VControl>
                                </VField>
                              </div>
                            </div>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <h1 class="mb-3 emr">Crown-rump lenght</h1>
                        </td>
                        <td>
                          <VField>
                            <VControl>
                              <VInput type="text" v-model="input.crown" placeholder="" class="is-rounded" />
                            </VControl>
                          </VField>
                        </td>
                        <td>
                          <VField>
                            <VControl>
                              <VInput type="text" v-model="input.usiaCrown" placeholder="" class="is-rounded" />
                            </VControl>
                          </VField>
                        </td>
                        <td>
                          <div class="column is-12">
                            <div class="columns is-multiline">
                              <div class="column is-2">
                                <h1 class="mb-3 emr">EDD</h1>
                              </div>
                              <div class="column is-10">
                                <VField>
                                  <VControl>
                                    <VInput type="text" v-model="input.ketCrown" placeholder="" class="is-rounded" />
                                  </VControl>
                                </VField>
                              </div>
                            </div>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <h1 class="mb-3 emr">Biparietal Diameter</h1>
                        </td>
                        <td>
                          <VField>
                            <VControl>
                              <VInput type="text" v-model="input.biparietal" placeholder="" class="is-rounded" />
                            </VControl>
                          </VField>
                        </td>
                        <td>
                          <VField>
                            <VControl>
                              <VInput type="text" v-model="input.usiaBiparietal" placeholder="" class="is-rounded" />
                            </VControl>
                          </VField>
                        </td>
                        <td>
                          <div class="column is-12">
                            <div class="columns is-multiline">
                              <div class="column is-2">
                                <h1 class="mb-3 emr">EFW</h1>
                              </div>
                              <div class="column is-10">
                                <VField>
                                  <VControl>
                                    <VInput type="text" v-model="input.ketBiparietal" placeholder=""
                                      class="is-rounded" />
                                  </VControl>
                                </VField>
                              </div>
                            </div>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <h1 class="mb-3 emr">Head Circumference</h1>
                        </td>
                        <td>
                          <VField>
                            <VControl>
                              <VInput type="text" v-model="input.headcircum" placeholder="" class="is-rounded" />
                            </VControl>
                          </VField>
                        </td>
                        <td>
                          <VField>
                            <VControl>
                              <VInput type="text" v-model="input.usiaHeadcircum" placeholder="" class="is-rounded" />
                            </VControl>
                          </VField>
                        </td>
                        <td>
                          <div class="column is-12">
                            <h1 class="mb-3 emr">Temuan Abnormal:</h1>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <h1 class="mb-3 emr">Abdominal Circumference</h1>
                        </td>
                        <td>
                          <VField>
                            <VControl>
                              <VInput type="text" v-model="input.abdominalcircum" placeholder="" class="is-rounded" />
                            </VControl>
                          </VField>
                        </td>
                        <td>
                          <VField>
                            <VControl>
                              <VInput type="text" v-model="input.usiaAbdominalcircum" placeholder=""
                                class="is-rounded" />
                            </VControl>
                          </VField>
                        </td>
                        <td rowspan="2">
                          <div class="column is-12">
                            <VField>
                              <VTextarea rows="8" placeholder="" v-model="input.Ketabdominalcircum"></VTextarea>
                            </VField>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <h1 class="mb-3 emr">Femoral Lenght</h1>
                        </td>
                        <td>
                          <VField>
                            <VControl>
                              <VInput type="text" v-model="input.femoral" placeholder="" class="is-rounded" />
                            </VControl>
                          </VField>
                        </td>
                        <td>
                          <VField>
                            <VControl>
                              <VInput type="text" v-model="input.usiaFemoral" placeholder="" class="is-rounded" />
                            </VControl>
                          </VField>
                        </td>
                      </tr>
                    </thead>
                  </table>
                </div>
                <div class="column is-1">
                  <h1 class="mb-3 emr">Plasenta</h1>
                </div>
                <div class="column is-2">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.plasenta" :attrs="{ value }" placeholder="" label="label"
                        :options="d_plasenta" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Menutupi</h1>
                </div>
                <div class="column is-2">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.menutupi" :attrs="{ value }" placeholder="" label="label"
                        :options="d_menutupi" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField addons>
                    <VControl expanded>
                      <VInput type="text" class="input is-rounded" placeholder="" v-model="input.ukuranMenutupi" />
                    </VControl>
                    <VControl class="field-addon-body">
                      <VButton static>mm dari OUI</VButton>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-1">
                  <h1 class="mb-3 emr">Maturasi</h1>
                </div>
                <div class="column is-2">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.maturasi" :attrs="{ value }" placeholder="" label="label"
                        :options="d_maturasi" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-1">
                  <h1 class="mb-3 emr">Cairan aminion</h1>
                </div>
                <div class="column is-2">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.cairanaminion" :attrs="{ value }" placeholder="" label="label"
                        :options="d_cairanaminion" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-1">
                  <h1 class="mb-3 emr">AFI</h1>
                </div>
                <div class="column is-1">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.AFI" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-1">
                  <h1 class="mb-3 emr">SDP</h1>
                </div>
                <div class="column is-1">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.SDP" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Temuan Abnormal</h1>
                </div>
                <div class="column is-3">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.temuanAbnormal" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-3">
                  <h1 class="mb-3 emr">Kelainan kongenital mayor</h1>
                </div>
                <div class="column is-3">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.kongenitalMayor" :attrs="{ value }" placeholder="" label="label"
                        :options="d_djj" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Temuan Abnormal</h1>
                </div>
                <div class="column is-4">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.temuanAbnormalKongenital" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-3">
                  <h1 class="mb-3 emr">Adneksa</h1>
                </div>
                <div class="column is-3">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.adneksa" :attrs="{ value }" placeholder="" label="label"
                        :options="d_cairanaminion" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Temuan Abnormal</h1>
                </div>
                <div class="column is-4">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.temuanAbnormalAdneksa" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>

                <br>
                <hr><br>

                <div class="column is-12" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Doppler Velocimetry</h1>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Arteri Uterina</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.arteriUterina" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Arteri Umbilicalis</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.arteriUmbilicalis" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">RI</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.riUterina" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">RI</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.riUmbilicalis" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">PI</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.piUterina" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">PI</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.piUmbilicalis" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">S/D Ratio</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.ratioUterina" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">S/D Ratio</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.ratioUmbilicalis" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>

                <div class="column is-2 mt-5" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Ductus Venosus</h1>
                </div>
                <div class="column is-4 mt-5" v-if="isFetal == true">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.ductusVenosus" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2 mt-5" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Arteri Serebi Media</h1>
                </div>
                <div class="column is-4 mt-5" v-if="isFetal == true">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.arteriSerebi" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">RI</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.riDuctus" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">RI</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.riSerebi" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">PI</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.piDuctus" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">PI</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.piSerebi" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">S/D Ratio</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.ratioDuctus" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">S/D Ratio</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.ratioSerebi" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-10" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Kepala</h1>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <VField>
                    <VControl>
                      <VCheckbox class="fontcheckbox" v-model="input.batasnormalfsk" true-value="Dalam Batas Normal"
                        label="Dalam Batas Normal" color="primary" circle @click="batasnormalfisik" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Bentuk</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.bentukkepala" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Ventrikel lateral</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.ventrikellateral" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Cavum septum pellucidum</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.cavumseptum" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Cerebellum</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.cerebellum" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Falx cerebri</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.falx" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Cistema magna</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.cistema" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Thalamus</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.thalamus" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6" v-if="isFetal == true"></div>
                <div class="column is-12" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Wajah</h1>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Bibir atas</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.bibiratas" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Hidung</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.hidung" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Profil Mediana</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.mediana" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Lubang Hidung</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.lubanghidung" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Orbita</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.orbita" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Leher</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.leher" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6" v-if="isFetal == true"></div>
                <div class="column is-12" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Thorax</h1>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Bentuk</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.bentukThorax" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Tidak Tampak Massa</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.tidakTampakMassa" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-12" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Jantung</h1>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Aktifitas Jantung</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.aktifitasJantung" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Faur chamber view</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.faur" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Ukuran</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.ukuranJantung" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Left verticular outlow</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.leftVerticular" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Axis Jantung</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.axisJantung" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Right verticular outlow</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.rightVerticular" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-12" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Abdomen</h1>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Gaster</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.gaster" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Vesica Urinaria</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.vesicaUrinaria" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Usus</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.usus" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Insersi Umbilicilus</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.insersiUmbilicilus" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Ginjal</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.ginjal" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Vescular Umbilicilus</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.vescularUmbilicilus" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Vertebra</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.vertebra" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6" v-if="isFetal == true"></div>
                <div class="column is-12" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Ekstremitas</h1>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Tangan Kanan (termasuk telapak)</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.eksTanganKanan" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Tangan Kiri (termasuk telapak)</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.eksTanganKiri" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Kaki Kanan (termasuk telapak)</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.eksKakiKanan" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Kaki Kiri (termasuk telapak)</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.eksKakiKiri" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Jenis Kelamin</h1>
                </div>
                <div class="column is-4" v-if="isFetal == true">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.jenisKelamin" :attrs="{ value }" placeholder="" label="label"
                        :options="d_jeniskelamin" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6" v-if="isFetal == true">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.jenisKelamin2" :attrs="{ value }" placeholder="" label="label"
                        :options="d_bentuk" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2" v-if="isFetal == true">
                  <h1 class="mb-3 emr">Lain-Lain</h1>
                </div>
                <div class="column is-10" v-if="isFetal == true">
                  <VField>
                    <VTextarea rows="8" placeholder="" v-model="input.fetalLainnya"></VTextarea>
                  </VField>
                </div>


                <div class="column is-2">
                  <h1 class="mb-3 emr">Kesimpulan & Saran</h1>
                </div>
                <div class="column is-10">
                  <VField>
                    <VTextarea rows="8" placeholder="" v-model="input.fetalSaran"></VTextarea>
                  </VField>
                </div>
              </div>
            </div>

            <div class="column is-12 mt-5" v-if="input.jenisPemeriksaanObgyn == 'PemeriksaanKardiotokografi'">
              <div class="columns is-multiline">
                <div class="column is-2">
                  <h1 class="mb-3 emr">TD Awal</h1>
                </div>
                <div class="column is-4">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.tdawal" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">TD Menit Ke-15</h1>
                </div>
                <div class="column is-4">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.tg15" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Cara Pantau</h1>
                </div>
                <div class="column is-4">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.carapantau" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Kecepatan Kertas</h1>
                </div>
                <div class="column is-4">
                  <VField addons>
                    <VControl expanded>
                      <VInput type="text" class="input is-rounded" placeholder="" v-model="input.kecepatankertas" />
                    </VControl>
                    <VControl class="field-addon-body">
                      <VButton static>cm/menit</VButton>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Periksa Dalam</h1>
                </div>
                <div class="column is-4">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.periksaDalam" :attrs="{ value }" placeholder="" label="label"
                        :options="d_periksadalam" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Dengan Hasil</h1>
                </div>
                <div class="column is-4">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.denganhasil" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Diagnosis</h1>
                </div>
                <div class="column is-4">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.diagnosis" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6"></div>

                <br>
                <hr><br>

                <div class="column is-2">
                  <h1 class="mb-3 emr">Denyut Jantung Janin</h1>
                </div>
                <div class="column is-4">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.denyutjantung" placeholder="" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6"></div>

                <div class="column is-12" style="margin-top: 100px;">
                  <div class="columns is-multiline">
                    <div class="column is-4">
                      <div class="columns is-multiline">
                        <div class="column is-4">
                          <h1 class="mb-3 emr">Frekuensi Dasar</h1>
                        </div>
                        <div class="column is-8">
                          <VField addons>
                            <VControl expanded>
                              <VInput type="text" class="input is-rounded" placeholder=""
                                v-model="input.frekuensidasar" />
                            </VControl>
                            <VControl class="field-addon-body">
                              <VButton static>dpm</VButton>
                            </VControl>
                          </VField>
                        </div>
                      </div>
                    </div>
                    <div class="column is-4">
                      <div class="columns is-multiline">
                        <div class="column is-4">
                          <h1 class="mb-3 emr">Akselerasi</h1>
                        </div>
                        <div class="column is-8">
                          <VField class="is-autocomplete-select" v-slot="{ id }">
                            <VControl>
                              <Multiselect v-model="input.akselerasi" :attrs="{ value }" placeholder="" label="label"
                                :options="d_akselerasi" class="is-rounded" :searchable="true" track-by="label"
                                mode="single" autocomplete="off">
                              </Multiselect>
                            </VControl>
                          </VField>
                        </div>
                      </div>
                    </div>
                    <div class="column is-4">
                      <div class="columns is-multiline">
                        <div class="column is-4">
                          <h1 class="mb-3 emr">Deselerasi</h1>
                        </div>
                        <div class="column is-8">
                          <VField class="is-autocomplete-select" v-slot="{ id }">
                            <VControl>
                              <Multiselect v-model="input.deselerasi" :attrs="{ value }" placeholder="" label="label"
                                :options="d_akselerasi" class="is-rounded" :searchable="true" track-by="label"
                                mode="single" autocomplete="off">
                              </Multiselect>
                            </VControl>
                          </VField>
                        </div>
                      </div>
                    </div>
                    <div class="column is-4">
                      <div class="columns is-multiline">
                        <div class="column is-4">
                          <h1 class="mb-3 emr">Variabilitas</h1>
                        </div>
                        <div class="column is-8">
                          <VField class="is-autocomplete-select" v-slot="{ id }">
                            <VControl>
                              <Multiselect v-model="input.variabilitas" :attrs="{ value }" placeholder="" label="label"
                                :options="d_variabilitas" class="is-rounded" :searchable="true" track-by="label"
                                mode="single" autocomplete="off">
                              </Multiselect>
                            </VControl>
                          </VField>
                        </div>
                      </div>
                    </div>
                    <div class="column is-4">
                      <div class="columns is-multiline">
                        <div class="column is-4">
                          <h1 class="mb-3 emr">Jenisnya</h1>
                        </div>
                        <div class="column is-8">
                          <VField class="is-autocomplete-select" v-slot="{ id }">
                            <VControl>
                              <Multiselect v-model="input.jenisnya" :attrs="{ value }" placeholder="" label="label"
                                :options="d_jenisnya" class="is-rounded" :searchable="true" track-by="label"
                                mode="single" autocomplete="off">
                              </Multiselect>
                            </VControl>
                          </VField>
                        </div>
                      </div>
                    </div>
                    <div class="column is-4">
                      <div class="columns is-multiline">
                        <div class="column is-4">
                          <h1 class="mb-3 emr">Beratnya</h1>
                        </div>
                        <div class="column is-8">
                          <VField class="is-autocomplete-select" v-slot="{ id }">
                            <VControl>
                              <Multiselect v-model="input.beratnya" :attrs="{ value }" placeholder="" label="label"
                                :options="d_beratnya" class="is-rounded" :searchable="true" track-by="label"
                                mode="single" autocomplete="off">
                              </Multiselect>
                            </VControl>
                          </VField>
                        </div>
                      </div>
                    </div>
                    <div class="column is-4">
                      <div class="columns is-multiline">
                        <div class="column is-4">
                          <h1 class="mb-3 emr">Pola disfungsi SSP</h1>
                        </div>
                        <div class="column is-8">
                          <VField class="is-autocomplete-select" v-slot="{ id }">
                            <VControl>
                              <Multiselect v-model="input.ssp" :attrs="{ value }" placeholder="" label="label"
                                :options="d_akselerasi" class="is-rounded" :searchable="true" track-by="label"
                                mode="single" autocomplete="off">
                              </Multiselect>
                            </VControl>
                          </VField>
                        </div>
                      </div>
                    </div>
                    <div class="column is-4">
                      <div class="columns is-multiline">
                        <div class="column is-4">
                          <h1 class="mb-3 emr">Yaitu</h1>
                        </div>
                        <div class="column is-8">
                          <VField class="is-autocomplete-select" v-slot="{ id }">
                            <VControl>
                              <Multiselect v-model="input.yaitu" :attrs="{ value }" placeholder="" label="label"
                                :options="d_yaitu" class="is-rounded" :searchable="true" track-by="label" mode="single"
                                autocomplete="off">
                              </Multiselect>
                            </VControl>
                          </VField>
                        </div>
                      </div>
                    </div>
                    <div class="column is-4"></div>

                    <br>
                    <hr><br>

                    <div class="column is-4">
                      <div class="columns is-multiline">
                        <div class="column is-4">
                          <h1 class="mb-3 emr">Kontraksi Uterus/His</h1>
                        </div>
                        <div class="column is-8">
                          <VField class="is-autocomplete-select" v-slot="{ id }">
                            <VControl>
                              <Multiselect v-model="input.kontraksi" :attrs="{ value }" placeholder="" label="label"
                                :options="d_akselerasi" class="is-rounded" :searchable="true" track-by="label"
                                mode="single" autocomplete="off">
                              </Multiselect>
                            </VControl>
                          </VField>
                        </div>
                      </div>
                    </div>
                    <div class="column is-8"></div>
                    <div class="column is-4">
                      <div class="columns is-multiline">
                        <div class="column is-4">
                          <h1 class="mb-3 emr">Frekuensi</h1>
                        </div>
                        <div class="column is-8">
                          <VField>
                            <VField addons>
                              <VControl expanded>
                                <VInput type="text" class="input" placeholder="" v-model.number="input.frekuensi" />
                              </VControl>
                              <VControl class="field-addon-body">
                                <VButton static>/10menit</VButton>
                              </VControl>
                            </VField>
                          </VField>
                        </div>
                      </div>
                    </div>
                    <div class="column is-4">
                      <div class="columns is-multiline">
                        <div class="column is-4">
                          <h1 class="mb-3 emr">Kekuatan</h1>
                        </div>
                        <div class="column is-8">
                          <VField>
                            <VField addons>
                              <VControl expanded>
                                <VInput type="text" class="input" placeholder="" v-model.number="input.kekuatan" />
                              </VControl>
                              <VControl class="field-addon-body">
                                <VButton static>mmHg</VButton>
                              </VControl>
                            </VField>
                          </VField>
                        </div>
                      </div>
                    </div>
                    <div class="column is-4">
                      <div class="columns is-multiline">
                        <div class="column is-4">
                          <h1 class="mb-3 emr">Lamanya</h1>
                        </div>
                        <div class="column is-8">
                          <VField>
                            <VField addons>
                              <VControl expanded>
                                <VInput type="text" class="input" placeholder="" v-model.number="input.lamanya" />
                              </VControl>
                              <VControl class="field-addon-body">
                                <VButton static>menit</VButton>
                              </VControl>
                            </VField>
                          </VField>
                        </div>
                      </div>
                    </div>
                    <div class="column is-4">
                      <div class="columns is-multiline">
                        <div class="column is-4">
                          <h1 class="mb-3 emr">Relaksasi</h1>
                        </div>
                        <div class="column is-8">
                          <VField>
                            <VControl>
                              <VInput type="text" v-model="input.relaksasi" placeholder="" class="is-rounded" />
                            </VControl>
                          </VField>
                        </div>
                      </div>
                    </div>
                    <div class="column is-4">
                      <div class="columns is-multiline">
                        <div class="column is-4">
                          <h1 class="mb-3 emr">Konfigurasi</h1>
                        </div>
                        <div class="column is-8">
                          <VField>
                            <VControl>
                              <VInput type="text" v-model="input.konfigurasi" placeholder="" class="is-rounded" />
                            </VControl>
                          </VField>
                        </div>
                      </div>
                    </div>
                    <div class="column is-4">
                      <div class="columns is-multiline">
                        <div class="column is-4">
                          <h1 class="mb-3 emr">Tumus Dasar</h1>
                        </div>
                        <div class="column is-8">
                          <VField>
                            <VField addons>
                              <VControl expanded>
                                <VInput type="text" class="input" placeholder="" v-model.number="input.tumusdasar" />
                              </VControl>
                              <VControl class="field-addon-body">
                                <VButton static>mmHg</VButton>
                              </VControl>
                            </VField>
                          </VField>
                        </div>
                      </div>
                    </div>

                    <br>
                    <hr><br>

                    <div class="column is-4">
                      <div class="columns is-multiline">
                        <div class="column is-4">
                          <h1 class="mb-3 emr">Gerak Janin</h1>
                        </div>
                        <div class="column is-8">
                          <VField>
                            <VField addons>
                              <VControl expanded>
                                <VInput type="text" class="input" placeholder="" v-model.number="input.gerakjanin" />
                              </VControl>
                              <VControl class="field-addon-body">
                                <VButton static>kali</VButton>
                              </VControl>
                            </VField>
                          </VField>
                        </div>
                      </div>
                    </div>
                    <div class="column is-4">
                      <div class="columns is-multiline">
                        <div class="column is-4">
                          <h1 class="mb-3 emr">dalam</h1>
                        </div>
                        <div class="column is-8">
                          <VField>
                            <VField addons>
                              <VControl expanded>
                                <VInput type="text" class="input" placeholder="" v-model.number="input.lamagerak" />
                              </VControl>
                              <VControl class="field-addon-body">
                                <VButton static>menit</VButton>
                              </VControl>
                            </VField>
                          </VField>
                        </div>
                      </div>
                    </div>
                    <div class="column is-4"></div>
                    <div class="column is-4">
                      <div class="columns is-multiline">
                        <div class="column is-4">
                          <h1 class="mb-3 emr">Diagnosis KTG</h1>
                        </div>
                        <div class="column is-8">
                          <VField>
                            <VControl>
                              <VInput type="text" v-model="input.diagnosisktg" placeholder="" class="is-rounded" />
                            </VControl>
                          </VField>
                        </div>
                      </div>
                    </div>
                    <div class="column is-4">
                      <div class="columns is-multiline">
                        <div class="column is-4">
                          <h1 class="mb-3 emr">Kategori</h1>
                        </div>
                        <div class="column is-8">
                          <VField class="is-autocomplete-select" v-slot="{ id }">
                            <VControl>
                              <Multiselect v-model="input.kategoridiagnosisktg" :attrs="{ value }" placeholder=""
                                label="label" :options="d_kategori" class="is-rounded" :searchable="true"
                                track-by="label" mode="single" autocomplete="off">
                              </Multiselect>
                            </VControl>
                          </VField>
                        </div>
                      </div>
                    </div>
                    <div class="column is-4"></div>
                    <div class="column is-12">
                      <h1 class="mb-3 emr">Saran</h1>
                      <VField>
                        <VTextarea rows="8" placeholder="Saran..." v-model="input.saran"></VTextarea>
                      </VField>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="column is-12 mt-5" v-if="input.jenisPemeriksaanObgyn == 'PemeriksaanUSGGynekologi'">
              <div class="columns is-multiline">
                <div class="column is-2">
                  <h1 class="mb-3 emr">Kondisi Teknis</h1>
                </div>
                <div class="column is-4">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.kondisiTeknis" :attrs="{ value }" placeholder="" label="label"
                        :options="d_kondisiteknis" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Karena</h1>
                </div>
                <div class="column is-4">
                  <VField>
                    <VControl>
                      <VInput type="text" v-model="input.karena" placeholder="Karena" class="is-rounded" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Vesica Urinaria</h1>
                </div>
                <div class="column is-4">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.vesicaUrinaria" :attrs="{ value }" placeholder="" label="label"
                        :options="d_vesicaurinaria" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Cairan Bebas</h1>
                </div>
                <div class="column is-4">
                  <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl>
                      <Multiselect v-model="input.cairanBebas" :attrs="{ value }" placeholder="" label="label"
                        :options="d_akselerasi" class="is-rounded" :searchable="true" track-by="label" mode="single"
                        autocomplete="off">
                      </Multiselect>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Uterus</h1>
                </div>
                <div class="column is-4">
                  <VField>
                    <VTextarea rows="8" placeholder="" v-model="input.uterus"></VTextarea>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Adnexa</h1>
                </div>
                <div class="column is-4">
                  <VField>
                    <VTextarea rows="8" placeholder="" v-model="input.adnexa"></VTextarea>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Lain-Lain</h1>
                </div>
                <div class="column is-4">
                  <VField>
                    <VTextarea rows="8" placeholder="" v-model="input.obstetriLainnya"></VTextarea>
                  </VField>
                </div>
                <div class="column is-2">
                  <h1 class="mb-3 emr">Kesimpulan & Saran</h1>
                </div>
                <div class="column is-4">
                  <VField>
                    <VTextarea rows="8" placeholder="" v-model="input.kesimpulansaran"></VTextarea>
                  </VField>
                </div>
              </div>
            </div>
          </VCard>
        </div>
        <!-- RUANGAN INPUT PENUNJANG OBGYN -->

        <!-- RUANGAN INPUT PENUNJANG JANTUNG -->
        <div class="column is-12"
          v-if="isInputData === true && isRiwayat === false && props.registrasi.objectruanganlastfk == 212">

          <div>
            <div class="is-flex">
              <div class="column is-2">
                <h1 style="font-weight: bold">NAMA PASIEN:</h1>
              </div>
              <div class="column is-10">
                <VField>
                  <VControl>
                    <VInput v-model="input.namaPasien" class="input" type="text" disabled />
                  </VControl>
                </VField>
              </div>
            </div>
            <div class="is-flex">
              <div class="column is-2">
                <h1 style="font-weight: bold">TANGGAL LAHIR PASIEN:</h1>
              </div>
              <div class="column is-10">
                <VField>
                  <VDatePicker v-model="input.tanggalLahirPasien" mode="date" trim-weeks :max-date="new Date()">
                    <template #default="{ inputValue, inputEvents }">
                      <VField>
                        <VControl icon="feather:calendar" fullwidth>
                          <VInput :value="inputValue" placeholder="Tanggal" disabled />
                        </VControl>
                      </VField>
                    </template>
                  </VDatePicker>
                </VField>
              </div>
            </div>
            <div class="is-flex">
              <div class="column is-2">
                <h1 style="font-weight: bold">JENIS KELAMIN:</h1>
              </div>
              <div class="column is-10" style="display: flex">
                <VField v-for="items in JenisKelamin" :key="items.value">
                  <VControl raw subcontrol>
                    <VCheckbox v-model="input.jeniskelamin" class="pt-1 pb-1" :true-value="items.label"
                      :label="items.label" color="primary" disabled circle />
                  </VControl>
                </VField>
              </div>
            </div>
            <div class="is-flex">
              <div class="column is-2">
                <h1 style="font-weight: bold">No. Rekam Medis:</h1>
              </div>
              <div class="column is-10">
                <VField>
                  <VControl>
                    <VInput type="text" class="input" placeholder="No. Rekam Medis" v-model="input.norm" disabled />
                  </VControl>
                </VField>
              </div>
            </div>
            <div class="is-flex">
              <div class="column is-2">
                <h1 style="font-weight: bold">Indikasi:</h1>
              </div>
              <div class="column is-10">
                <VField>
                  <VControl>
                    <VInput type="text" class="input" placeholder="Indikasi" v-model="input.indikasi" />
                  </VControl>
                </VField>
              </div>
            </div>

            <div class="is-flex">
              <div class="column is-2">
                <h1 style="font-weight: bold">Study Type:</h1>
              </div>
              <div class="column is-10">
                <VField class="is-autocomplete-select" v-slot="{ id }">
                  <VControl>
                    <Multiselect v-model="input.studyType" placeholder="--Pilih--" label="label" :options="studyType"
                      :searchable="true" track-by="label" mode="single" autocomplete="off">
                    </Multiselect>
                  </VControl>
                </VField>
              </div>
            </div>

            <div class="is-flex">
              <div class="column is-2">
                <h1 style="font-weight: bold">Sonographer:</h1>
              </div>
              <div class="column is-10">
                <VField class="is-autocomplete-select pt-3">
                  <VControl icon="feather:search">
                    <AutoComplete v-model="input.DDDokter" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                      :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                      :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="ketik Nama Dokter" />
                  </VControl>
                </VField>
              </div>
            </div>

            <div class="is-flex">
              <div class="column is-2">
                <h1 style="font-weight: bold">Reviewer:</h1>
              </div>
              <div class="column is-10">
                <VField class="is-autocomplete-select pt-3">
                  <VControl icon="feather:search">
                    <AutoComplete v-model="input.DDDokter" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                      :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                      :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="ketik Nama Dokter" />
                  </VControl>
                </VField>
              </div>
            </div>

            <div class="is-flex">
              <div class="column is-2">
                <h1 style="font-weight: bold">Tanggal Eksaminasi:</h1>
              </div>
              <div class="column is-10">
                <VField>
                  <VDatePicker v-model="input.tglEksaminasi" mode="date" trim-weeks :max-date="new Date()">
                    <template #default="{ inputValue, inputEvents }">
                      <VField>
                        <VControl icon="feather:calendar" fullwidth>
                          <VInput :value="inputValue" v-on="inputEvents" placeholder="Tanggal" />
                        </VControl>
                      </VField>
                    </template>
                  </VDatePicker>
                </VField>
              </div>
            </div>

            <div class="column is-12 is-multiline" v-if="input.studyType === 'TransThoracaEchoBayi'">
              <Fieldset :toggleable="true" legend="Trans Thoraca Echo Bayi">
                <div class="columns">
                  <div class="column is-one-third">
                    <div v-for="(item, index) in resultTransThoracaEchoBayi.slice(0, 6)" :key="index" class="mb-3">
                      <h1 style="font-weight: bold">{{ item.label }}:</h1>
                      <VField class="ml-2" addons>
                        <VControl>
                          <VInput v-model="input[item.model]" type="text" :placeholder="item.label" />
                        </VControl>
                        <VControl class="field-addon-body">
                          <VButton static>{{ item.addons }}</VButton>
                        </VControl>
                      </VField>
                    </div>
                  </div>

                  <div class="column is-one-third">
                    <div v-for="(item, index) in resultTransThoracaEchoBayi.slice(6, 12)" :key="index" class="mb-3">
                      <h1 style="font-weight: bold">{{ item.label }}:</h1>
                      <VField class="ml-2" addons>
                        <VControl>
                          <VInput v-model="input[item.model]" type="text" :placeholder="item.label" />
                        </VControl>
                        <VControl class="field-addon-body">
                          <VButton static>{{ item.addons }}</VButton>
                        </VControl>
                      </VField>
                    </div>
                  </div>

                  <div class="column is-one-third">
                    <div v-for="(item, index) in resultTransThoracaEchoBayi.slice(12)" :key="index" class="mb-3">
                      <h1 style="font-weight: bold">{{ item.label }}:</h1>
                      <VField class="ml-2" addons>
                        <VControl>
                          <VInput v-model="input[item.model]" type="text" :placeholder="item.label" />
                        </VControl>
                        <VControl class="field-addon-body">
                          <VButton static>{{ item.addons }}</VButton>
                        </VControl>
                      </VField>
                    </div>
                  </div>
                </div>

                <div class="columns is-12">
                  <VField>
                    <VControl>
                      <VCheckbox type="text" v-model="input.dalamBatasNormalEchoBayi" label="Dalam Batas Normal"
                        true-value="Dalam Batas Normal" @click="normalEchoBayi" />
                    </VControl>
                  </VField>
                </div>

                <div class="is-12 is-flex">
                  <div class="column is-14">
                    <h1 style="font-weight: bold">Finding:</h1>
                  </div>
                  <div class="column is-10">
                    <VField>
                      <VControl>
                        <VTextarea v-model="input.finding" type="text" placeholder="Finding" />
                      </VControl>
                    </VField>
                  </div>
                </div>

                <div class="is-12 is-flex">
                  <div class="column is-14">
                    <h1 style="font-weight: bold">Conclusion:</h1>
                  </div>
                  <div class="column is-10">
                    <VField>
                      <VControl>
                        <VTextarea v-model="input.conclusion" type="text" placeholder="Conclusion" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </Fieldset>
            </div>

            <div class="column is-12 " v-if="input.studyType === 'TransThoracaEchoDewasa'">
              <Fieldset :toggleable="true" legend="Trans Thoraca Echo Dewasa">
                <div class="columns">
                  <div class="column is-one-third">
                    <div v-for="(item, index) in resultTransThoracaEchoDewasa.slice(0, 6)" :key="index" class="mb-3">
                      <h1 style="font-weight: bold">{{ item.label }}:</h1>
                      <VField class="ml-2" addons>
                        <VControl>
                          <VInput v-model="input[item.model]" type="text" :placeholder="item.label" />
                        </VControl>
                        <VControl class="field-addon-body">
                          <VButton static>{{ item.addons }}</VButton>
                        </VControl>
                      </VField>
                    </div>
                  </div>

                  <div class="column is-one-third">
                    <div v-for="(item, index) in resultTransThoracaEchoDewasa.slice(6, 12, 24)" :key="index"
                      class="mb-3">
                      <h1 style="font-weight: bold">{{ item.label }}:</h1>
                      <VField class="ml-2" addons>
                        <VControl>
                          <VInput v-model="input[item.model]" type="text" :placeholder="item.label" />
                        </VControl>
                        <VControl class="field-addon-body">
                          <VButton static>{{ item.addons }}</VButton>
                        </VControl>
                      </VField>
                    </div>
                  </div>

                  <div class="column is-one-third">
                    <div v-for="(item, index) in resultTransThoracaEchoDewasa.slice(12, 24)" :key="index" class="mb-3">
                      <h1 style="font-weight: bold">{{ item.label }}:</h1>
                      <VField class="ml-2" addons>
                        <VControl>
                          <VInput v-model="input[item.model]" type="text" :placeholder="item.label" />
                        </VControl>
                        <VControl class="field-addon-body">
                          <VButton static>{{ item.addons }}</VButton>
                        </VControl>
                      </VField>
                    </div>
                  </div>
                </div>

                <div class="column is-12">
                  <Fieldset :toggleable="true" legend="Finding">
                    <div class="is-12 is-end">
                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.dalamBatasNormalEchoDewasa" label="Dalam Batas Normal"
                            @click="normalEchoDewasa" true-value="Dalam Batas Normal" />
                        </VControl>
                      </VField>
                    </div>
                    <div class="is-12" style="display: flex; flex-wrap: wrap;">
                      <!-- Kolom Kiri -->
                      <div class="column is-6">
                        <div v-for="(item, index) in leftColumnItems" :key="index"
                          class="is-flex align-items-center mb-2">
                          <div class="column is-4">
                            <h1 style="font-weight: bold">{{ item.label }}:</h1>
                          </div>
                          <div class="column is-8">
                            <VField>
                              <VControl>
                                <VInput v-model="input[item.model]" :placeholder="item.label" />
                              </VControl>
                            </VField>
                          </div>
                        </div>
                      </div>

                      <!-- Kolom Kanan -->
                      <div class="column is-6">
                        <h1 style="font-weight: bold">Heart Valves:</h1>
                        <div v-for="(item, index) in rightColumnItems" :key="index"
                          class="is-flex align-items-center mb-2">
                          <div class="column is-4">
                            <h1 style="font-weight: bold">{{ item.label }}:</h1>
                          </div>
                          <div class="column is-8">
                            <VField>
                              <VControl>
                                <VInput v-model="input[item.model]" :placeholder="item.label" />
                              </VControl>
                            </VField>
                          </div>
                        </div>

                        <h1 style="font-weight: bold; margin-top: 1rem;">Pericardium</h1>
                        <div class="is-flex align-items-center">
                          <div class="column is-4">
                            <h1 style="font-weight: bold">Pericardium:</h1>
                          </div>
                          <div class="column is-8">
                            <VField>
                              <VControl>
                                <VInput v-model="input.pericardium" placeholder="Pericardium" />
                              </VControl>
                            </VField>
                          </div>
                        </div>
                      </div>
                    </div>
                  </Fieldset>
                </div>

                <div class="is-12 is-flex">
                  <div class="column is-14">
                    <h1 style="font-weight: bold">Conclusion:</h1>
                  </div>
                  <div class="column is-10">
                    <VField>
                      <VControl>
                        <VTextarea v-model="input.conclusion" type="text" placeholder="Conclusion" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </Fieldset>
            </div>

            <div class="column is-12" v-if="input.studyType === 'LowerExtermityDuplexUltrasoundUSGDoppler'">
              <Fieldset :toggleable="true" legend="Lower Extermity Duplex Ultrasound USG Doppler">
                <div class="is-12 is-flex">
                  <div class="column is-14">
                    <h1 style="font-weight: bold">Finding:</h1>
                  </div>
                  <div class="column is-10">
                    <VField>
                      <VControl>
                        <VTextarea v-model="input.finding" type="text" placeholder="Finding" />
                      </VControl>
                    </VField>
                  </div>
                </div>

                <div class="is-12 is-flex">
                  <div class="column is-14">
                    <h1 style="font-weight: bold">Conclusion:</h1>
                  </div>
                  <div class="column is-10">
                    <VField>
                      <VControl>
                        <VTextarea v-model="input.conclusion" type="text" placeholder="Conclusion" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </Fieldset>
            </div>

            <div class="column is-12" v-if="input.studyType === 'CarotidDuplexUltrasound'">
              <Fieldset :toggleable="true" legend="Carotid Duplex Ultrasound">
                <div class="is-12 is-flex">
                  <div class="column is-14">
                    <h1 style="font-weight: bold">Finding:</h1>
                  </div>
                  <div class="column is-10">
                    <VField>
                      <VControl>
                        <VTextarea v-model="input.finding" type="text" placeholder="Finding" />
                      </VControl>
                    </VField>
                  </div>
                </div>

                <div class="is-12 is-flex">
                  <div class="column is-14">
                    <h1 style="font-weight: bold">Conclusion:</h1>
                  </div>
                  <div class="column is-10">
                    <VField>
                      <VControl>
                        <VTextarea v-model="input.conclusion" type="text" placeholder="Conclusion" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </Fieldset>
            </div>

            <div class="column is-12" v-if="input.studyType === 'EKGJantung'">
              <div class="column is-12">
                <VField>
                  <VControl>
                    <VCheckbox class="fontcheckbox" v-model="input.batasNormal" true-value="Dalam Batas Normal"
                      label="Dalam Batas Normal" color="primary" @click="normalEKGJantung" />
                  </VControl>
                </VField>
              </div>

              <div class="columns is-multiline">
                <div class="column is-4" v-for="(items, index) in formField">
                  <div>
                    <h1 style="font-weight: bold">{{ items.label }}:</h1>
                  </div>
                  <div>
                    <VField v-if="items.value !== 'Conclusion'">
                      <VControl>
                        <VInput type="text" class="input" v-model="input[items.value]" :placeholder="items.label" />
                      </VControl>
                    </VField>

                    <VField v-if="items.value === 'Conclusion'">
                      <VControl>
                        <VTextarea type="text" class="input" v-model="input[items.value]" :placeholder="items.label" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
          </div>

        </div>

        <div class="column is-12"
          v-if="isInputData === true && isRiwayat === false && props.registrasi.objectruanganlastfk == 377">

          <div>
            <div class="is-flex">
              <div class="column is-2">
                <h1 style="font-weight: bold">NAMA PASIEN:</h1>
              </div>
              <div class="column is-10">
                <VField>
                  <VControl>
                    <VInput v-model="input.namaPasien" class="input" type="text" disabled />
                  </VControl>
                </VField>
              </div>
            </div>
            <div class="is-flex">
              <div class="column is-2">
                <h1 style="font-weight: bold">TANGGAL LAHIR PASIEN:</h1>
              </div>
              <div class="column is-10">
                <VField>
                  <VDatePicker v-model="input.tanggalLahirPasien" mode="date" trim-weeks :max-date="new Date()">
                    <template #default="{ inputValue, inputEvents }">
                      <VField>
                        <VControl icon="feather:calendar" fullwidth>
                          <VInput :value="inputValue" placeholder="Tanggal" disabled />
                        </VControl>
                      </VField>
                    </template>
                  </VDatePicker>
                </VField>
              </div>
            </div>
            <div class="is-flex">
              <div class="column is-2">
                <h1 style="font-weight: bold">JENIS KELAMIN:</h1>
              </div>
              <div class="column is-10" style="display: flex">
                <VField v-for="items in JenisKelamin" :key="items.value">
                  <VControl raw subcontrol>
                    <VCheckbox v-model="input.jeniskelamin" class="pt-1 pb-1" :true-value="items.label"
                      :label="items.label" color="primary" disabled circle />
                  </VControl>
                </VField>
              </div>
            </div>
            <div class="is-flex">
              <div class="column is-2">
                <h1 style="font-weight: bold">No. Rekam Medis:</h1>
              </div>
              <div class="column is-10">
                <VField>
                  <VControl>
                    <VInput type="text" class="input" placeholder="No. Rekam Medis" v-model="input.norm" disabled />
                  </VControl>
                </VField>
              </div>
            </div>
            <div class="is-flex">
              <div class="column is-2">
                <h1 style="font-weight: bold">Indikasi:</h1>
              </div>
              <div class="column is-10">
                <VField>
                  <VControl>
                    <VInput type="text" class="input" placeholder="Indikasi" v-model="input.indikasi" />
                  </VControl>
                </VField>
              </div>
            </div>

            <div class="is-flex">
              <div class="column is-2">
                <h1 style="font-weight: bold">Study Type:</h1>
              </div>
              <div class="column is-10">
                <VField class="is-autocomplete-select" v-slot="{ id }">
                  <VControl>
                    <Multiselect v-model="input.studyType" placeholder="--Pilih--" label="label" :options="studyType"
                      :searchable="true" track-by="label" mode="single" autocomplete="off">
                    </Multiselect>
                  </VControl>
                </VField>
              </div>
            </div>

            <div class="is-flex">
              <div class="column is-2">
                <h1 style="font-weight: bold">Sonographer:</h1>
              </div>
              <div class="column is-10">
                <VField class="is-autocomplete-select pt-3">
                  <VControl icon="feather:search">
                    <AutoComplete v-model="input.DDDokter" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                      :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                      :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="ketik Nama Dokter" />
                  </VControl>
                </VField>
              </div>
            </div>

            <div class="is-flex">
              <div class="column is-2">
                <h1 style="font-weight: bold">Reviewer:</h1>
              </div>
              <div class="column is-10">
                <VField class="is-autocomplete-select pt-3">
                  <VControl icon="feather:search">
                    <AutoComplete v-model="input.DDDokter" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                      :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                      :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="ketik Nama Dokter" />
                  </VControl>
                </VField>
              </div>
            </div>

            <div class="is-flex">
              <div class="column is-2">
                <h1 style="font-weight: bold">Tanggal Eksaminasi:</h1>
              </div>
              <div class="column is-10">
                <VField>
                  <VDatePicker v-model="input.tglEksaminasi" mode="date" trim-weeks :max-date="new Date()">
                    <template #default="{ inputValue, inputEvents }">
                      <VField>
                        <VControl icon="feather:calendar" fullwidth>
                          <VInput :value="inputValue" v-on="inputEvents" placeholder="Tanggal" />
                        </VControl>
                      </VField>
                    </template>
                  </VDatePicker>
                </VField>
              </div>
            </div>

            <div class="column is-12 is-multiline" v-if="input.studyType === 'TransThoracaEchoBayi'">
              <Fieldset :toggleable="true" legend="Trans Thoraca Echo Bayi">
                <div class="columns is-12">
                  <VField>
                    <VControl>
                      <VCheckbox type="text" class="input" v-model="input.dalamBatasNormalEchoBayi"
                        label="Dalam Batas Normal" true-value="Dalam Batas Normal" @click="normalEchoBayi" />
                    </VControl>
                  </VField>
                </div>
                <div class="columns">
                  <div class="column is-one-third">
                    <div v-for="(item, index) in resultTransThoracaEchoBayi.slice(0, 6)" :key="index" class="mb-3">
                      <h1 style="font-weight: bold">{{ item.label }}:</h1>
                      <VField class="ml-2" addons>
                        <VControl>
                          <VInput v-model="input[item.model]" type="text" :placeholder="item.label" />
                        </VControl>
                        <VControl class="field-addon-body">
                          <VButton static>{{ item.addons }}</VButton>
                        </VControl>
                      </VField>
                    </div>
                  </div>

                  <div class="column is-one-third">
                    <div v-for="(item, index) in resultTransThoracaEchoBayi.slice(6, 12)" :key="index" class="mb-3">
                      <h1 style="font-weight: bold">{{ item.label }}:</h1>
                      <VField class="ml-2" addons>
                        <VControl>
                          <VInput v-model="input[item.model]" type="text" :placeholder="item.label" />
                        </VControl>
                        <VControl class="field-addon-body">
                          <VButton static>{{ item.addons }}</VButton>
                        </VControl>
                      </VField>
                    </div>
                  </div>

                  <div class="column is-one-third">
                    <div v-for="(item, index) in resultTransThoracaEchoBayi.slice(12)" :key="index" class="mb-3">
                      <h1 style="font-weight: bold">{{ item.label }}:</h1>
                      <VField class="ml-2" addons>
                        <VControl>
                          <VInput v-model="input[item.model]" type="text" :placeholder="item.label" />
                        </VControl>
                        <VControl class="field-addon-body">
                          <VButton static>{{ item.addons }}</VButton>
                        </VControl>
                      </VField>
                    </div>
                  </div>
                </div>

                <div class="is-12 is-flex">
                  <div class="column is-14">
                    <h1 style="font-weight: bold">Finding:</h1>
                  </div>
                  <div class="column is-10">
                    <VField>
                      <VControl>
                        <VTextarea v-model="input.finding" type="text" placeholder="Finding" />
                      </VControl>
                    </VField>
                  </div>
                </div>

                <div class="is-12 is-flex">
                  <div class="column is-14">
                    <h1 style="font-weight: bold">Conclusion:</h1>
                  </div>
                  <div class="column is-10">
                    <VField>
                      <VControl>
                        <VTextarea v-model="input.conclusion" type="text" placeholder="Conclusion" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </Fieldset>
            </div>

            <div class="column is-12 " v-if="input.studyType === 'TransThoracaEchoDewasa'">
              <Fieldset :toggleable="true" legend="Trans Thoraca Echo Dewasa">
                <div class="columns">
                  <div class="column is-one-third">
                    <div v-for="(item, index) in resultTransThoracaEchoDewasa.slice(0, 6)" :key="index" class="mb-3">
                      <h1 style="font-weight: bold">{{ item.label }}:</h1>
                      <VField class="ml-2" addons>
                        <VControl>
                          <VInput v-model="input[item.model]" type="text" :placeholder="item.label" />
                        </VControl>
                        <VControl class="field-addon-body">
                          <VButton static>{{ item.addons }}</VButton>
                        </VControl>
                      </VField>
                    </div>
                  </div>

                  <div class="column is-one-third">
                    <div v-for="(item, index) in resultTransThoracaEchoDewasa.slice(6, 12)" :key="index" class="mb-3">
                      <h1 style="font-weight: bold">{{ item.label }}:</h1>
                      <VField class="ml-2" addons>
                        <VControl>
                          <VInput v-model="input[item.model]" type="text" :placeholder="item.label" />
                        </VControl>
                        <VControl class="field-addon-body">
                          <VButton static>{{ item.addons }}</VButton>
                        </VControl>
                      </VField>
                    </div>
                  </div>

                  <div class="column is-one-third">
                    <div v-for="(item, index) in resultTransThoracaEchoDewasa.slice(12)" :key="index" class="mb-3">
                      <h1 style="font-weight: bold">{{ item.label }}:</h1>
                      <VField class="ml-2" addons>
                        <VControl>
                          <VInput v-model="input[item.model]" type="text" :placeholder="item.label" />
                        </VControl>
                        <VControl class="field-addon-body">
                          <VButton static>{{ item.addons }}</VButton>
                        </VControl>
                      </VField>
                    </div>
                  </div>
                </div>

                <div class="column is-12">
                  <Fieldset :toggleable="true" legend="Finding">
                    <div class="is-12 is-end">
                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.dalamBatasNormalEchoDewasa" label="Dalam Batas Normal"
                            @click="normalEchoDewasa" true-value="Dalam Batas Normal" />
                        </VControl>
                      </VField>
                    </div>
                    <div class="is-12" style="display: flex; flex-wrap: wrap;">
                      <!-- Kolom Kiri -->
                      <div class="column is-6">
                        <div v-for="(item, index) in leftColumnItems" :key="index"
                          class="is-flex align-items-center mb-2">
                          <div class="column is-4">
                            <h1 style="font-weight: bold">{{ item.label }}:</h1>
                          </div>
                          <div class="column is-8">
                            <VField>
                              <VControl>
                                <VInput v-model="input[item.model]" :placeholder="item.label" />
                              </VControl>
                            </VField>
                          </div>
                        </div>
                      </div>

                      <!-- Kolom Kanan -->
                      <div class="column is-6">
                        <h1 style="font-weight: bold">Heart Valves:</h1>
                        <div v-for="(item, index) in rightColumnItems" :key="index"
                          class="is-flex align-items-center mb-2">
                          <div class="column is-4">
                            <h1 style="font-weight: bold">{{ item.label }}:</h1>
                          </div>
                          <div class="column is-8">
                            <VField>
                              <VControl>
                                <VInput v-model="input[item.model]" :placeholder="item.label" />
                              </VControl>
                            </VField>
                          </div>
                        </div>

                        <h1 style="font-weight: bold; margin-top: 1rem;">Pericardium</h1>
                        <div class="is-flex align-items-center">
                          <div class="column is-4">
                            <h1 style="font-weight: bold">Pericardium:</h1>
                          </div>
                          <div class="column is-8">
                            <VField>
                              <VControl>
                                <VInput v-model="input.pericardium" placeholder="Pericardium" />
                              </VControl>
                            </VField>
                          </div>
                        </div>
                      </div>
                    </div>
                  </Fieldset>
                </div>

                <div class="is-12 is-flex">
                  <div class="column is-14">
                    <h1 style="font-weight: bold">Conclusion:</h1>
                  </div>
                  <div class="column is-10">
                    <VField>
                      <VControl>
                        <VTextarea v-model="input.conclusion" type="text" placeholder="Conclusion" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </Fieldset>
            </div>

            <div class="column is-12" v-if="input.studyType === 'LowerExtermityDuplexUltrasoundUSGDoppler'">
              <Fieldset :toggleable="true" legend="Lower Extermity Duplex Ultrasound USG Doppler">
                <div class="is-12 is-flex">
                  <div class="column is-14">
                    <h1 style="font-weight: bold">Finding:</h1>
                  </div>
                  <div class="column is-10">
                    <VField>
                      <VControl>
                        <VTextarea v-model="input.finding" type="text" placeholder="Finding" />
                      </VControl>
                    </VField>
                  </div>
                </div>

                <div class="is-12 is-flex">
                  <div class="column is-14">
                    <h1 style="font-weight: bold">Conclusion:</h1>
                  </div>
                  <div class="column is-10">
                    <VField>
                      <VControl>
                        <VTextarea v-model="input.conclusion" type="text" placeholder="Conclusion" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </Fieldset>
            </div>

            <div class="column is-12" v-if="input.studyType === 'CarotidDuplexUltrasound'">
              <Fieldset :toggleable="true" legend="Carotid Duplex Ultrasound">
                <div class="is-12 is-flex">
                  <div class="column is-14">
                    <h1 style="font-weight: bold">Finding:</h1>
                  </div>
                  <div class="column is-10">
                    <VField>
                      <VControl>
                        <VTextarea v-model="input.finding" type="text" placeholder="Finding" />
                      </VControl>
                    </VField>
                  </div>
                </div>

                <div class="is-12 is-flex">
                  <div class="column is-14">
                    <h1 style="font-weight: bold">Conclusion:</h1>
                  </div>
                  <div class="column is-10">
                    <VField>
                      <VControl>
                        <VTextarea v-model="input.conclusion" type="text" placeholder="Conclusion" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </Fieldset>
            </div>

            <div class="column is-12" v-if="input.studyType === 'EKGJantung'">
              <div class="column is-12">
                <VField>
                  <VControl>
                    <VCheckbox class="fontcheckbox" v-model="input.batasNormal" true-value="Dalam Batas Normal"
                      label="Dalam Batas Normal" color="primary" @click="normalEKGJantung" />
                  </VControl>
                </VField>
              </div>

              <div class="columns is-multiline">
                <div class="column is-4" v-for="(items, index) in formField">
                  <div>
                    <h1 style="font-weight: bold">{{ items.label }}:</h1>
                  </div>
                  <div>
                    <VField v-if="items.value !== 'Conclusion'">
                      <VControl>
                        <VInput type="text" class="input" v-model="input[items.value]" :placeholder="items.label" />
                      </VControl>
                    </VField>

                    <VField v-if="items.value === 'Conclusion'">
                      <VControl>
                        <VTextarea type="text" class="input" v-model="input[items.value]" :placeholder="items.label" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="column is-12"
          v-if="isInputData === true && isRiwayat === false && props.registrasi.objectruanganlastfk == 409">

          <div>
            <div class="is-flex">
              <div class="column is-2">
                <h1 style="font-weight: bold">NAMA PASIEN:</h1>
              </div>
              <div class="column is-10">
                <VField>
                  <VControl>
                    <VInput v-model="input.namaPasien" class="input" type="text" disabled />
                  </VControl>
                </VField>
              </div>
            </div>
            <div class="is-flex">
              <div class="column is-2">
                <h1 style="font-weight: bold">TANGGAL LAHIR PASIEN:</h1>
              </div>
              <div class="column is-10">
                <VField>
                  <VDatePicker v-model="input.tanggalLahirPasien" mode="date" trim-weeks :max-date="new Date()">
                    <template #default="{ inputValue, inputEvents }">
                      <VField>
                        <VControl icon="feather:calendar" fullwidth>
                          <VInput :value="inputValue" placeholder="Tanggal" disabled />
                        </VControl>
                      </VField>
                    </template>
                  </VDatePicker>
                </VField>
              </div>
            </div>
            <div class="is-flex">
              <div class="column is-2">
                <h1 style="font-weight: bold">JENIS KELAMIN:</h1>
              </div>
              <div class="column is-10" style="display: flex">
                <VField v-for="items in JenisKelamin" :key="items.value">
                  <VControl raw subcontrol>
                    <VCheckbox v-model="input.jeniskelamin" class="pt-1 pb-1" :true-value="items.label"
                      :label="items.label" color="primary" disabled circle />
                  </VControl>
                </VField>
              </div>
            </div>
            <div class="is-flex">
              <div class="column is-2">
                <h1 style="font-weight: bold">No. Rekam Medis:</h1>
              </div>
              <div class="column is-10">
                <VField>
                  <VControl>
                    <VInput type="text" class="input" placeholder="No. Rekam Medis" v-model="input.norm" disabled />
                  </VControl>
                </VField>
              </div>
            </div>
            <div class="is-flex">
              <div class="column is-2">
                <h1 style="font-weight: bold">Indikasi:</h1>
              </div>
              <div class="column is-10">
                <VField>
                  <VControl>
                    <VInput type="text" class="input" placeholder="Indikasi" v-model="input.indikasi" />
                  </VControl>
                </VField>
              </div>
            </div>

            <div class="is-flex">
              <div class="column is-2">
                <h1 style="font-weight: bold">Study Type:</h1>
              </div>
              <div class="column is-10">
                <VField class="is-autocomplete-select" v-slot="{ id }">
                  <VControl>
                    <Multiselect v-model="input.studyType" placeholder="--Pilih--" label="label" :options="studyType"
                      :searchable="true" track-by="label" mode="single" autocomplete="off">
                    </Multiselect>
                  </VControl>
                </VField>
              </div>
            </div>

            <div class="is-flex">
              <div class="column is-2">
                <h1 style="font-weight: bold">Sonographer:</h1>
              </div>
              <div class="column is-10">
                <VField class="is-autocomplete-select pt-3">
                  <VControl icon="feather:search">
                    <AutoComplete v-model="input.DDDokter" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                      :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                      :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="ketik Nama Dokter" />
                  </VControl>
                </VField>
              </div>
            </div>

            <div class="is-flex">
              <div class="column is-2">
                <h1 style="font-weight: bold">Reviewer:</h1>
              </div>
              <div class="column is-10">
                <VField class="is-autocomplete-select pt-3">
                  <VControl icon="feather:search">
                    <AutoComplete v-model="input.DDDokter" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                      :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                      :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="ketik Nama Dokter" />
                  </VControl>
                </VField>
              </div>
            </div>

            <div class="is-flex">
              <div class="column is-2">
                <h1 style="font-weight: bold">Tanggal Eksaminasi:</h1>
              </div>
              <div class="column is-10">
                <VField>
                  <VDatePicker v-model="input.tglEksaminasi" mode="date" trim-weeks :max-date="new Date()">
                    <template #default="{ inputValue, inputEvents }">
                      <VField>
                        <VControl icon="feather:calendar" fullwidth>
                          <VInput :value="inputValue" v-on="inputEvents" placeholder="Tanggal" disabled />
                        </VControl>
                      </VField>
                    </template>
                  </VDatePicker>
                </VField>
              </div>
            </div>

            <div class="column is-12 is-multiline" v-if="input.studyType === 'TransThoracaEchoBayi'">
              <Fieldset :toggleable="true" legend="Trans Thoraca Echo Bayi">
                <div class="columns">
                  <div class="column is-one-third">
                    <div v-for="(item, index) in resultTransThoracaEchoBayi.slice(0, 6)" :key="index" class="mb-3">
                      <h1 style="font-weight: bold">{{ item.label }}:</h1>
                      <VField class="ml-2" addons>
                        <VControl>
                          <VInput v-model="input[item.model]" type="text" :placeholder="item.label" />
                        </VControl>
                        <VControl class="field-addon-body">
                          <VButton static>{{ item.addons }}</VButton>
                        </VControl>
                      </VField>
                    </div>
                  </div>

                  <div class="column is-one-third">
                    <div v-for="(item, index) in resultTransThoracaEchoBayi.slice(6, 12)" :key="index" class="mb-3">
                      <h1 style="font-weight: bold">{{ item.label }}:</h1>
                      <VField class="ml-2" addons>
                        <VControl>
                          <VInput v-model="input[item.model]" type="text" :placeholder="item.label" />
                        </VControl>
                        <VControl class="field-addon-body">
                          <VButton static>{{ item.addons }}</VButton>
                        </VControl>
                      </VField>
                    </div>
                  </div>

                  <div class="column is-one-third">
                    <div v-for="(item, index) in resultTransThoracaEchoBayi.slice(12)" :key="index" class="mb-3">
                      <h1 style="font-weight: bold">{{ item.label }}:</h1>
                      <VField class="ml-2" addons>
                        <VControl>
                          <VInput v-model="input[item.model]" type="text" :placeholder="item.label" />
                        </VControl>
                        <VControl class="field-addon-body">
                          <VButton static>{{ item.addons }}</VButton>
                        </VControl>
                      </VField>
                    </div>
                  </div>
                </div>

                <div class="columns is-12">
                  <VField>
                    <VControl>
                      <VCheckbox type="text" class="input" v-model="input.dalamBatasNormalEchoBayi"
                        label="Dalam Batas Normal" true-value="Dalam Batas Normal" @click="normalEchoBayi" />
                    </VControl>
                  </VField>
                </div>

                <div class="is-12 is-flex">
                  <div class="column is-14">
                    <h1 style="font-weight: bold">Finding:</h1>
                  </div>
                  <div class="column is-10">
                    <VField>
                      <VControl>
                        <VTextarea v-model="input.finding" type="text" placeholder="Finding" />
                      </VControl>
                    </VField>
                  </div>
                </div>

                <div class="is-12 is-flex">
                  <div class="column is-14">
                    <h1 style="font-weight: bold">Conclusion:</h1>
                  </div>
                  <div class="column is-10">
                    <VField>
                      <VControl>
                        <VTextarea v-model="input.conclusion" type="text" placeholder="Conclusion" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </Fieldset>
            </div>

            <div class="column is-12 " v-if="input.studyType === 'TransThoracaEchoDewasa'">
              <Fieldset :toggleable="true" legend="Trans Thoraca Echo Dewasa">
                <div class="columns">
                  <div class="column is-one-third">
                    <div v-for="(item, index) in resultTransThoracaEchoDewasa.slice(0, 6)" :key="index" class="mb-3">
                      <h1 style="font-weight: bold">{{ item.label }}:</h1>
                      <VField class="ml-2" addons>
                        <VControl>
                          <VInput v-model="input[item.model]" type="text" :placeholder="item.label" />
                        </VControl>
                        <VControl class="field-addon-body">
                          <VButton static>{{ item.addons }}</VButton>
                        </VControl>
                      </VField>
                    </div>
                  </div>

                  <div class="column is-one-third">
                    <div v-for="(item, index) in resultTransThoracaEchoDewasa.slice(6, 12)" :key="index" class="mb-3">
                      <h1 style="font-weight: bold">{{ item.label }}:</h1>
                      <VField class="ml-2" addons>
                        <VControl>
                          <VInput v-model="input[item.model]" type="text" :placeholder="item.label" />
                        </VControl>
                        <VControl class="field-addon-body">
                          <VButton static>{{ item.addons }}</VButton>
                        </VControl>
                      </VField>
                    </div>
                  </div>

                  <div class="column is-one-third">
                    <div v-for="(item, index) in resultTransThoracaEchoDewasa.slice(12)" :key="index" class="mb-3">
                      <h1 style="font-weight: bold">{{ item.label }}:</h1>
                      <VField class="ml-2" addons>
                        <VControl>
                          <VInput v-model="input[item.model]" type="text" :placeholder="item.label" />
                        </VControl>
                        <VControl class="field-addon-body">
                          <VButton static>{{ item.addons }}</VButton>
                        </VControl>
                      </VField>
                    </div>
                  </div>
                </div>

                <div class="column is-12">
                  <Fieldset :toggleable="true" legend="Finding">
                    <div class="is-12 is-end">
                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.dalamBatasNormalEchoDewasa" label="Dalam Batas Normal"
                            @click="normalEchoDewasa" />
                        </VControl>
                      </VField>
                    </div>
                    <div class="is-12" style="display: flex; flex-wrap: wrap;">
                      <!-- Kolom Kiri -->
                      <div class="column is-6">
                        <div v-for="(item, index) in leftColumnItems" :key="index"
                          class="is-flex align-items-center mb-2">
                          <div class="column is-4">
                            <h1 style="font-weight: bold">{{ item.label }}:</h1>
                          </div>
                          <div class="column is-8">
                            <VField>
                              <VControl>
                                <VInput v-model="input[item.model]" :placeholder="item.label" />
                              </VControl>
                            </VField>
                          </div>
                        </div>
                      </div>

                      <!-- Kolom Kanan -->
                      <div class="column is-6">
                        <h1 style="font-weight: bold">Heart Valves:</h1>
                        <div v-for="(item, index) in rightColumnItems" :key="index"
                          class="is-flex align-items-center mb-2">
                          <div class="column is-4">
                            <h1 style="font-weight: bold">{{ item.label }}:</h1>
                          </div>
                          <div class="column is-8">
                            <VField>
                              <VControl>
                                <VInput v-model="input[item.model]" :placeholder="item.label" />
                              </VControl>
                            </VField>
                          </div>
                        </div>

                        <h1 style="font-weight: bold; margin-top: 1rem;">Pericardium</h1>
                        <div class="is-flex align-items-center">
                          <div class="column is-4">
                            <h1 style="font-weight: bold">Pericardium:</h1>
                          </div>
                          <div class="column is-8">
                            <VField>
                              <VControl>
                                <VInput v-model="input.pericardium" placeholder="Pericardium" />
                              </VControl>
                            </VField>
                          </div>
                        </div>
                      </div>
                    </div>
                  </Fieldset>
                </div>

                <div class="is-12 is-flex">
                  <div class="column is-14">
                    <h1 style="font-weight: bold">Conclusion:</h1>
                  </div>
                  <div class="column is-10">
                    <VField>
                      <VControl>
                        <VTextarea v-model="input.conclusion" type="text" placeholder="Conclusion" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </Fieldset>
            </div>

            <div class="column is-12" v-if="input.studyType === 'LowerExtermityDuplexUltrasoundUSGDoppler'">
              <Fieldset :toggleable="true" legend="Lower Extermity Duplex Ultrasound USG Doppler">
                <div class="is-12 is-flex">
                  <div class="column is-14">
                    <h1 style="font-weight: bold">Finding:</h1>
                  </div>
                  <div class="column is-10">
                    <VField>
                      <VControl>
                        <VTextarea v-model="input.finding" type="text" placeholder="Finding" />
                      </VControl>
                    </VField>
                  </div>
                </div>

                <div class="is-12 is-flex">
                  <div class="column is-14">
                    <h1 style="font-weight: bold">Conclusion:</h1>
                  </div>
                  <div class="column is-10">
                    <VField>
                      <VControl>
                        <VTextarea v-model="input.conclusion" type="text" placeholder="Conclusion" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </Fieldset>
            </div>

            <div class="column is-12" v-if="input.studyType === 'CarotidDuplexUltrasound'">
              <Fieldset :toggleable="true" legend="Carotid Duplex Ultrasound">
                <div class="is-12 is-flex">
                  <div class="column is-14">
                    <h1 style="font-weight: bold">Finding:</h1>
                  </div>
                  <div class="column is-10">
                    <VField>
                      <VControl>
                        <VTextarea v-model="input.finding" type="text" placeholder="Finding" />
                      </VControl>
                    </VField>
                  </div>
                </div>

                <div class="is-12 is-flex">
                  <div class="column is-14">
                    <h1 style="font-weight: bold">Conclusion:</h1>
                  </div>
                  <div class="column is-10">
                    <VField>
                      <VControl>
                        <VTextarea v-model="input.conclusion" type="text" placeholder="Conclusion" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </Fieldset>
            </div>

            <div class="column is-12" v-if="input.studyType === 'EKGJantung'">
              <div class="column is-12">
                <VField>
                  <VControl>
                    <VCheckbox class="fontcheckbox" v-model="input.batasNormal" true-value="Dalam Batas Normal"
                      label="Dalam Batas Normal" color="primary" @click="normalEKGJantung" />
                  </VControl>
                </VField>
              </div>

              <div class="columns is-multiline">
                <div class="column is-4" v-for="(items, index) in formField">
                  <div>
                    <h1 style="font-weight: bold">{{ items.label }}:</h1>
                  </div>
                  <div>
                    <VField v-if="items.value !== 'Conclusion'">
                      <VControl>
                        <VInput type="text" class="input" v-model="input[items.value]" :placeholder="items.label" />
                      </VControl>
                    </VField>

                    <VField v-if="items.value === 'Conclusion'">
                      <VControl>
                        <VTextarea type="text" class="input" v-model="input[items.value]" :placeholder="items.label" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- RUANGAN INPUT PENUNJANG JANTUNG -->

        <!-- RUANGAN INPUT PENUNJANG THT -->
        <div class="column is-12"
          v-if="isInputData === true && isRiwayat === false && props.registrasi.objectruanganlastfk == 204">
          <VCard>
            <div class="is-flex">
              <div class="column is-2">
                <h1 style="font-weight: bold">NAMA PASIEN:</h1>
              </div>
              <div class="column is-10">
                <VField>
                  <VControl>
                    <VInput v-model="input.namaPasien" class="input" type="text" disabled />
                  </VControl>
                </VField>
              </div>
            </div>
            <div class="is-flex">
              <div class="column is-2">
                <h1 style="font-weight: bold">TANGGAL LAHIR PASIEN:</h1>
              </div>
              <div class="column is-10">
                <VField>
                  <VDatePicker v-model="input.tanggalLahirPasien" mode="date" trim-weeks :max-date="new Date()">
                    <template #default="{ inputValue, inputEvents }">
                      <VField>
                        <VControl icon="feather:calendar" fullwidth>
                          <VInput :value="inputValue" placeholder="Tanggal" disabled />
                        </VControl>
                      </VField>
                    </template>
                  </VDatePicker>
                </VField>
              </div>
            </div>
            <div class="is-flex">
              <div class="column is-2">
                <h1 style="font-weight: bold">JENIS KELAMIN:</h1>
              </div>
              <div class="column is-10" style="display: flex">
                <VField v-for="items in JenisKelamin" :key="items.value">
                  <VControl raw subcontrol>
                    <VCheckbox v-model="input.jeniskelamin" class="pt-1 pb-1" :true-value="items.label"
                      :label="items.label" color="primary" disabled circle />
                  </VControl>
                </VField>
              </div>
            </div>
            <div class="is-flex">
              <div class="column is-2">
                <h1 style="font-weight: bold">No. Rekam Medis:</h1>
              </div>
              <div class="column is-10">
                <VField>
                  <VControl>
                    <VInput type="text" class="input" placeholder="No. Rekam Medis" v-model="input.norm" disabled />
                  </VControl>
                </VField>
              </div>
            </div>

            <div class="is-flex">
              <div class="column is-2">
                <h1 style="font-weight: bold">Jenis Tindakan:</h1>
              </div>
              <div class="column is-10">
                <VField>
                  <VControl>
                    <VInput type="text" class="input" placeholder="Jenis Tindakan" v-model="input.jenistindakan" />
                  </VControl>
                </VField>
              </div>
            </div>

            <div class="is-flex">
              <div class="column is-2">
                <h1 style="font-weight: bold">Dokter Pemeriksa:</h1>
              </div>
              <div class="column is-10">
                <VField>
                  <VControl>
                    <AutoComplete v-model="input.DDDokter" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                      :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                      :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Cari Pegawai" />
                  </VControl>
                </VField>
              </div>
            </div>

            <div class="column is-12">
              <Fieldset :toggleable="true" legend="Telingan Kanan">
                <div class="is-12 is-end">
                  <VField>
                    <VControl>
                      <VCheckbox v-model="input.normalthtkanan" label="Dalam Batas Normal"
                        true-value="Dalam Batas Normal" />
                    </VControl>
                  </VField>
                </div>
                <div class="is-flex">
                  <div class="column is-2">
                    <h1 style="font-weight: bold">Keterangan:</h1>
                  </div>
                  <div class="column is-10">
                    <VField>
                      <VControl>
                        <VInput type="text" class="input" placeholder="Keterangan Telinga Kanan"
                          v-model="input.keteranganTelingaKanan" />
                      </VControl>
                    </VField>
                  </div>
                </div>
                <div class="column is-12 is-flex">
                  <div class="columns is-multiline">
                    <div class="column is-12">
                      <div class="columns is-multiline">
                        <div class="column is-12" style="margin-top: 0.5rem">
                          <span>AC : </span>
                        </div>
                        <div class="column is-12">
                          <VField addons>
                            <VControl>
                              <VInput type="text" class="input" placeholder="AC Telinga Kanan" label="Tekanan Darah"
                                v-model="input.acTelingakanan" />
                            </VControl>
                            <VControl class="field-addon-body">
                              <VButton static>dB</VButton>
                            </VControl>
                          </VField>
                        </div>
                      </div>

                      <div class="columns is-multiline">
                        <div class="column is-12" style="margin-top: 0.5rem">
                          <span>BC : </span>
                        </div>
                        <div class="column is-12">
                          <VField addons>
                            <VControl>
                              <VInput type="text" class="input" placeholder="BC Telinga Kanan" label="Tekanan Darah"
                                v-model="input.bcTelingakanan" />
                            </VControl>
                            <VControl class="field-addon-body">
                              <VButton static>dB</VButton>
                            </VControl>
                          </VField>
                        </div>
                      </div>
                    </div>
                  </div>

                </div>
              </Fieldset>
            </div>

            <div class="column is-12">
              <Fieldset :toggleable="true" legend="Telingan Kiri">
                <div class="is-12 is-end">
                  <VField>
                    <VControl>
                      <VCheckbox v-model="input.normalthtkiri" label="Dalam Batas Normal"
                        true-value="Dalam Batas Normal" />
                    </VControl>
                  </VField>
                </div>
                <div class="is-flex">
                  <div class="column is-2">
                    <h1 style="font-weight: bold">Keterangan:</h1>
                  </div>
                  <div class="column is-10">
                    <VField>
                      <VControl>
                        <VInput type="text" class="input" placeholder="Keterangan Telinga Kiri"
                          v-model="input.keteranganTelingaKiri" />
                      </VControl>
                    </VField>
                  </div>
                </div>
                <div class="column is-12 is-flex">
                  <div class="columns is-multiline">
                    <div class="column is-12">
                      <div class="columns is-multiline">
                        <div class="column is-12" style="margin-top: 0.5rem">
                          <span>AC : </span>
                        </div>
                        <div class="column is-12">
                          <VField addons>
                            <VControl>
                              <VInput type="text" class="input" placeholder="AC Telinga Kiri" label="Tekanan Darah"
                                v-model="input.acTelingaKiri" />
                            </VControl>
                            <VControl class="field-addon-body">
                              <VButton static>dB</VButton>
                            </VControl>
                          </VField>
                        </div>
                      </div>

                      <div class="columns is-multiline">
                        <div class="column is-12" style="margin-top: 0.5rem">
                          <span>BC : </span>
                        </div>
                        <div class="column is-12">
                          <VField addons>
                            <VControl>
                              <VInput type="text" class="input" placeholder="BC Telinga Kiri" label="Tekanan Darah"
                                v-model="input.bcTelingaKiri" />
                            </VControl>
                            <VControl class="field-addon-body">
                              <VButton static>dB</VButton>
                            </VControl>
                          </VField>
                        </div>
                      </div>
                    </div>
                  </div>

                </div>
              </Fieldset>
            </div>
          </VCard>
        </div>
        <div class="column is-12"
          v-if="isInputData === true && isRiwayat === false && props.registrasi.objectruanganlastfk == 404">
          <VCard>
            <div class="is-flex">
              <div class="column is-2">
                <h1 style="font-weight: bold">NAMA PASIEN:</h1>
              </div>
              <div class="column is-10">
                <VField>
                  <VControl>
                    <VInput v-model="input.namaPasien" class="input" type="text" disabled />
                  </VControl>
                </VField>
              </div>
            </div>
            <div class="is-flex">
              <div class="column is-2">
                <h1 style="font-weight: bold">TANGGAL LAHIR PASIEN:</h1>
              </div>
              <div class="column is-10">
                <VField>
                  <VDatePicker v-model="input.tanggalLahirPasien" mode="date" trim-weeks :max-date="new Date()">
                    <template #default="{ inputValue, inputEvents }">
                      <VField>
                        <VControl icon="feather:calendar" fullwidth>
                          <VInput :value="inputValue" placeholder="Tanggal" disabled />
                        </VControl>
                      </VField>
                    </template>
                  </VDatePicker>
                </VField>
              </div>
            </div>
            <div class="is-flex">
              <div class="column is-2">
                <h1 style="font-weight: bold">JENIS KELAMIN:</h1>
              </div>
              <div class="column is-10" style="display: flex">
                <VField v-for="items in JenisKelamin" :key="items.value">
                  <VControl raw subcontrol>
                    <VCheckbox v-model="input.jeniskelamin" class="pt-1 pb-1" :true-value="items.label"
                      :label="items.label" color="primary" disabled circle />
                  </VControl>
                </VField>
              </div>
            </div>
            <div class="is-flex">
              <div class="column is-2">
                <h1 style="font-weight: bold">No. Rekam Medis:</h1>
              </div>
              <div class="column is-10">
                <VField>
                  <VControl>
                    <VInput type="text" class="input" placeholder="No. Rekam Medis" v-model="input.norm" disabled />
                  </VControl>
                </VField>
              </div>
            </div>

            <div class="is-flex">
              <div class="column is-2">
                <h1 style="font-weight: bold">Jenis Tindakan:</h1>
              </div>
              <div class="column is-10">
                <VField>
                  <VControl>
                    <VInput type="text" class="input" placeholder="Jenis Tindakan" v-model="input.jenistindakan" />
                  </VControl>
                </VField>
              </div>
            </div>

            <div class="is-flex">
              <div class="column is-2">
                <h1 style="font-weight: bold">Dokter Pemeriksa:</h1>
              </div>
              <div class="column is-10">
                <VField>
                  <VControl>
                    <AutoComplete v-model="input.DDDokter" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                      :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                      :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Cari Pegawai" />
                  </VControl>
                </VField>
              </div>
            </div>

            <div class="column is-12">
              <Fieldset :toggleable="true" legend="Telingan Kanan">
                <div class="is-flex">
                  <div class="column is-2">
                    <h1 style="font-weight: bold">Keterangan:</h1>
                  </div>
                  <div class="column is-10">
                    <VField>
                      <VControl>
                        <VInput type="text" class="input" placeholder="Keterangan Telinga Kanan"
                          v-model="input.keteranganTelingaKanan" />
                      </VControl>
                    </VField>
                  </div>
                </div>
                <div class="column is-12 is-flex">
                  <div class="columns is-multiline">
                    <div class="column is-12">
                      <div class="columns is-multiline">
                        <div class="column is-12" style="margin-top: 0.5rem">
                          <span>AC : </span>
                        </div>
                        <div class="column is-12">
                          <VField addons>
                            <VControl>
                              <VInput type="text" class="input" placeholder="AC Telinga Kanan" label="Tekanan Darah"
                                v-model="input.acTelingakanan" />
                            </VControl>
                            <VControl class="field-addon-body">
                              <VButton static>dB</VButton>
                            </VControl>
                          </VField>
                        </div>
                      </div>

                      <div class="columns is-multiline">
                        <div class="column is-12" style="margin-top: 0.5rem">
                          <span>BC : </span>
                        </div>
                        <div class="column is-12">
                          <VField addons>
                            <VControl>
                              <VInput type="text" class="input" placeholder="BC Telinga Kanan" label="Tekanan Darah"
                                v-model="input.bcTelingakanan" />
                            </VControl>
                            <VControl class="field-addon-body">
                              <VButton static>dB</VButton>
                            </VControl>
                          </VField>
                        </div>
                      </div>
                    </div>
                  </div>

                </div>
              </Fieldset>
            </div>

            <div class="column is-12">
              <Fieldset :toggleable="true" legend="Telingan Kiri">
                <div class="is-flex">
                  <div class="column is-2">
                    <h1 style="font-weight: bold">Keterangan:</h1>
                  </div>
                  <div class="column is-10">
                    <VField>
                      <VControl>
                        <VInput type="text" class="input" placeholder="Keterangan Telinga Kiri"
                          v-model="input.keteranganTelingaKiri" />
                      </VControl>
                    </VField>
                  </div>
                </div>
                <div class="column is-12 is-flex">
                  <div class="columns is-multiline">
                    <div class="column is-12">
                      <div class="columns is-multiline">
                        <div class="column is-12" style="margin-top: 0.5rem">
                          <span>AC : </span>
                        </div>
                        <div class="column is-12">
                          <VField addons>
                            <VControl>
                              <VInput type="text" class="input" placeholder="AC Telinga Kiri" label="Tekanan Darah"
                                v-model="input.acTelingaKiri" />
                            </VControl>
                            <VControl class="field-addon-body">
                              <VButton static>dB</VButton>
                            </VControl>
                          </VField>
                        </div>
                      </div>

                      <div class="columns is-multiline">
                        <div class="column is-12" style="margin-top: 0.5rem">
                          <span>BC : </span>
                        </div>
                        <div class="column is-12">
                          <VField addons>
                            <VControl>
                              <VInput type="text" class="input" placeholder="BC Telinga Kiri" label="Tekanan Darah"
                                v-model="input.bcTelingaKiri" />
                            </VControl>
                            <VControl class="field-addon-body">
                              <VButton static>dB</VButton>
                            </VControl>
                          </VField>
                        </div>
                      </div>
                    </div>
                  </div>

                </div>
              </Fieldset>
            </div>
          </VCard>
        </div>
        <div class="column is-12"
          v-if="isInputData === true && isRiwayat === false && props.registrasi.objectruanganlastfk == 378">
          <VCard>
            <div class="is-flex">
              <div class="column is-2">
                <h1 style="font-weight: bold">NAMA PASIEN:</h1>
              </div>
              <div class="column is-10">
                <VField>
                  <VControl>
                    <VInput v-model="input.namaPasien" class="input" type="text" disabled />
                  </VControl>
                </VField>
              </div>
            </div>
            <div class="is-flex">
              <div class="column is-2">
                <h1 style="font-weight: bold">TANGGAL LAHIR PASIEN:</h1>
              </div>
              <div class="column is-10">
                <VField>
                  <VDatePicker v-model="input.tanggalLahirPasien" mode="date" trim-weeks :max-date="new Date()">
                    <template #default="{ inputValue, inputEvents }">
                      <VField>
                        <VControl icon="feather:calendar" fullwidth>
                          <VInput :value="inputValue" placeholder="Tanggal" disabled />
                        </VControl>
                      </VField>
                    </template>
                  </VDatePicker>
                </VField>
              </div>
            </div>
            <div class="is-flex">
              <div class="column is-2">
                <h1 style="font-weight: bold">JENIS KELAMIN:</h1>
              </div>
              <div class="column is-10" style="display: flex">
                <VField v-for="items in JenisKelamin" :key="items.value">
                  <VControl raw subcontrol>
                    <VCheckbox v-model="input.jeniskelamin" class="pt-1 pb-1" :true-value="items.label"
                      :label="items.label" color="primary" disabled circle />
                  </VControl>
                </VField>
              </div>
            </div>
            <div class="is-flex">
              <div class="column is-2">
                <h1 style="font-weight: bold">No. Rekam Medis:</h1>
              </div>
              <div class="column is-10">
                <VField>
                  <VControl>
                    <VInput type="text" class="input" placeholder="No. Rekam Medis" v-model="input.norm" disabled />
                  </VControl>
                </VField>
              </div>
            </div>

            <div class="is-flex">
              <div class="column is-2">
                <h1 style="font-weight: bold">Jenis Tindakan:</h1>
              </div>
              <div class="column is-10">
                <VField>
                  <VControl>
                    <VInput type="text" class="input" placeholder="Jenis Tindakan" v-model="input.jenistindakan" />
                  </VControl>
                </VField>
              </div>
            </div>

            <div class="is-flex">
              <div class="column is-2">
                <h1 style="font-weight: bold">Dokter Pemeriksa:</h1>
              </div>
              <div class="column is-10">
                <VField>
                  <VControl>
                    <AutoComplete v-model="input.DDDokter" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                      :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                      :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Cari Pegawai" />
                  </VControl>
                </VField>
              </div>
            </div>

            <div class="column is-12">
              <Fieldset :toggleable="true" legend="Telingan Kanan">
                <div class="is-flex">
                  <div class="column is-2">
                    <h1 style="font-weight: bold">Keterangan:</h1>
                  </div>
                  <div class="column is-10">
                    <VField>
                      <VControl>
                        <VInput type="text" class="input" placeholder="Keterangan Telinga Kanan"
                          v-model="input.keteranganTelingaKanan" />
                      </VControl>
                    </VField>
                  </div>
                </div>
                <div class="column is-12 is-flex">
                  <div class="columns is-multiline">
                    <div class="column is-12">
                      <div class="columns is-multiline">
                        <div class="column is-12" style="margin-top: 0.5rem">
                          <span>AC : </span>
                        </div>
                        <div class="column is-12">
                          <VField addons>
                            <VControl>
                              <VInput type="text" class="input" placeholder="AC Telinga Kanan" label="Tekanan Darah"
                                v-model="input.acTelingakanan" />
                            </VControl>
                            <VControl class="field-addon-body">
                              <VButton static>dB</VButton>
                            </VControl>
                          </VField>
                        </div>
                      </div>

                      <div class="columns is-multiline">
                        <div class="column is-12" style="margin-top: 0.5rem">
                          <span>BC : </span>
                        </div>
                        <div class="column is-12">
                          <VField addons>
                            <VControl>
                              <VInput type="text" class="input" placeholder="BC Telinga Kanan" label="Tekanan Darah"
                                v-model="input.bcTelingakanan" />
                            </VControl>
                            <VControl class="field-addon-body">
                              <VButton static>dB</VButton>
                            </VControl>
                          </VField>
                        </div>
                      </div>
                    </div>
                  </div>

                </div>
              </Fieldset>
            </div>

            <div class="column is-12">
              <Fieldset :toggleable="true" legend="Telingan Kiri">
                <div class="is-flex">
                  <div class="column is-2">
                    <h1 style="font-weight: bold">Keterangan:</h1>
                  </div>
                  <div class="column is-10">
                    <VField>
                      <VControl>
                        <VInput type="text" class="input" placeholder="Keterangan Telinga Kiri"
                          v-model="input.keteranganTelingaKiri" />
                      </VControl>
                    </VField>
                  </div>
                </div>
                <div class="column is-12 is-flex">
                  <div class="columns is-multiline">
                    <div class="column is-12">
                      <div class="columns is-multiline">
                        <div class="column is-12" style="margin-top: 0.5rem">
                          <span>AC : </span>
                        </div>
                        <div class="column is-12">
                          <VField addons>
                            <VControl>
                              <VInput type="text" class="input" placeholder="AC Telinga Kiri" label="Tekanan Darah"
                                v-model="input.acTelingaKiri" />
                            </VControl>
                            <VControl class="field-addon-body">
                              <VButton static>dB</VButton>
                            </VControl>
                          </VField>
                        </div>
                      </div>

                      <div class="columns is-multiline">
                        <div class="column is-12" style="margin-top: 0.5rem">
                          <span>BC : </span>
                        </div>
                        <div class="column is-12">
                          <VField addons>
                            <VControl>
                              <VInput type="text" class="input" placeholder="BC Telinga Kiri" label="Tekanan Darah"
                                v-model="input.bcTelingaKiri" />
                            </VControl>
                            <VControl class="field-addon-body">
                              <VButton static>dB</VButton>
                            </VControl>
                          </VField>
                        </div>
                      </div>
                    </div>
                  </div>

                </div>
              </Fieldset>
            </div>
          </VCard>
        </div>
        <!-- RUANGAN INPUT PENUNJANG THT -->

        <!-- RUANGAN INPUT PENUNJANG MCU -->
        <div class="column is-12"
          v-if="isInputData === true && isRiwayat === false && props.registrasi.objectruanganlastfk == 217">
          <VCard>
            <div class="is-flex">
              <div class="column is-2">
                <h1 style="font-weight: bold">NAMA PASIEN:</h1>
              </div>
              <div class="column is-10">
                <VField>
                  <VControl>
                    <VInput v-model="input.namaPasien" class="input" type="text" disabled />
                  </VControl>
                </VField>
              </div>
            </div>
            <div class="is-flex">
              <div class="column is-2">
                <h1 style="font-weight: bold">TANGGAL LAHIR PASIEN:</h1>
              </div>
              <div class="column is-10">
                <VField>
                  <VDatePicker v-model="input.tanggalLahirPasien" mode="date" trim-weeks :max-date="new Date()">
                    <template #default="{ inputValue, inputEvents }">
                      <VField>
                        <VControl icon="feather:calendar" fullwidth>
                          <VInput :value="inputValue" placeholder="Tanggal" disabled />
                        </VControl>
                      </VField>
                    </template>
                  </VDatePicker>
                </VField>
              </div>
            </div>
            <div class="is-flex">
              <div class="column is-2">
                <h1 style="font-weight: bold">JENIS KELAMIN:</h1>
              </div>
              <div class="column is-10" style="display: flex">
                <VField v-for="items in JenisKelamin" :key="items.value">
                  <VControl raw subcontrol>
                    <VCheckbox v-model="input.jeniskelamin" class="pt-1 pb-1" :true-value="items.label"
                      :label="items.label" color="primary" disabled circle />
                  </VControl>
                </VField>
              </div>
            </div>
            <div class="is-flex">
              <div class="column is-2">
                <h1 style="font-weight: bold">No. Rekam Medis:</h1>
              </div>
              <div class="column is-10">
                <VField>
                  <VControl>
                    <VInput type="text" class="input" placeholder="No. Rekam Medis" v-model="input.norm" disabled />
                  </VControl>
                </VField>
              </div>
            </div>
            <div class="column is-12 is-flex">
              <div class="column is-2">
                <h1 style="font-weight: bold">TANGGAL Eksaminasi</h1>
              </div>
              <div class="column is-10">
                <VField>
                  <VDatePicker v-model="input.tglEksaminasi" mode="dateTime" style="width: 100%" trim-weeks
                    :max-date="new Date()">
                    <template #default="{ inputValue, inputEvents }">
                      <VField>
                        <VControl icon="feather:calendar" fullwidth>
                          <VInput :value="inputValue" placeholder="Tanggal" />
                        </VControl>
                      </VField>
                    </template>
                  </VDatePicker>
                </VField>
              </div>
            </div>

            <div class="column is-12">
              <VField>
                <VControl>
                  <VCheckbox class="fontcheckbox" v-model="input.batasNormal" true-value="Dalam Batas Normal"
                    label="Dalam Batas Normal" color="primary" @click="normalEKGJantung" />
                </VControl>
              </VField>
            </div>

            <div class="column is-12 is-flex" v-for="(items, index) in formField">
              <div class="column is-2">
                <h1 style="font-weight: bold">{{ items.label }}:</h1>
              </div>
              <div class="column is-10">
                <VField v-if="items.value !== 'Conclusion'">
                  <VControl>
                    <VInput type="text" class="input" v-model="input[items.value]" :placeholder="items.label" />
                  </VControl>
                </VField>

                <VField v-if="items.value === 'Conclusion'">
                  <VControl>
                    <VTextarea type="text" class="input" v-model="input[items.value]" :placeholder="items.label" />
                  </VControl>
                </VField>
              </div>
            </div>

          </VCard>
        </div>
        <!-- RUANGAN INPUT PENUNJANG MCU -->

        <!-- RUANGAN INPUT PENUNJANG POLI UROLOGI -->
        <div class="column is-12"
          v-if="isInputData === true && isRiwayat === false && props.registrasi.objectruanganlastfk == 224">
          <VCard>
            <div class="columns is-multiline">
              <div class="column is-9 pt-0">
                <h1>Nama Template&emsp;&emsp;<span style="color: rgb(230, 41, 100);">**Hanya diisi jika ingin
                    membuat template</span></h1>
                <VField>
                  <VControl>
                    <VInput type="text" class="input" v-model="input.namatemplate" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-3 mt-auto">
                <VButton type="button" rounded outlined color="primary" raised icon="feather:folder"
                  :loading="isLoading" @click="pilihTemplateFix(index)"> Pilih Template
                </VButton>
              </div>
            </div>
            <div class="is-flex">
              <div class="column is-2">
                <h1 style="font-weight: bold">NAMA PASIEN:</h1>
              </div>
              <div class="column is-10">
                <VField>
                  <VControl>
                    <VInput v-model="input.namaPasien" class="input" type="text" disabled />
                  </VControl>
                </VField>
              </div>
            </div>
            <div class="is-flex">
              <div class="column is-2">
                <h1 style="font-weight: bold">TANGGAL LAHIR PASIEN:</h1>
              </div>
              <div class="column is-10">
                <VField>
                  <VDatePicker v-model="input.tanggalLahirPasien" mode="date" trim-weeks :max-date="new Date()">
                    <template #default="{ inputValue, inputEvents }">
                      <VField>
                        <VControl icon="feather:calendar" fullwidth>
                          <VInput :value="inputValue" placeholder="Tanggal" disabled />
                        </VControl>
                      </VField>
                    </template>
                  </VDatePicker>
                </VField>
              </div>
            </div>
            <div class="is-flex">
              <div class="column is-2">
                <h1 style="font-weight: bold">JENIS KELAMIN:</h1>
              </div>
              <div class="column is-10" style="display: flex">
                <VField v-for="items in JenisKelamin" :key="items.value">
                  <VControl raw subcontrol>
                    <VCheckbox v-model="input.jeniskelamin" class="pt-1 pb-1" :true-value="items.label"
                      :label="items.label" color="primary" disabled circle />
                  </VControl>
                </VField>
              </div>
            </div>
            <div class="is-flex">
              <div class="column is-2">
                <h1 style="font-weight: bold">No. Rekam Medis:</h1>
              </div>
              <div class="column is-10">
                <VField>
                  <VControl>
                    <VInput type="text" class="input" placeholder="No. Rekam Medis" v-model="input.norm" disabled />
                  </VControl>
                </VField>
              </div>
            </div>

            <div class="is-flex">
              <div class="column is-2">
                <h1 style="font-weight: bold">Diagnosa:</h1>
              </div>
              <div class="column is-10">
                <VField>
                  <VControl>
                    <VTextarea type="text" class="input" placeholder="Diagnosa" v-model="input.diagnosa" />
                  </VControl>
                </VField>
              </div>
            </div>

            <div class="is-flex">
              <div class="column is-2">
                <h1 style="font-weight: bold">Ginjal Kanan:</h1>
              </div>
              <div class="column is-10">
                <VField>
                  <VControl>
                    <VTextarea type="text" class="input" placeholder="Ginjal Kanan" v-model="input.ginjalKanan" />
                  </VControl>
                </VField>
              </div>
            </div>

            <div class="is-flex">
              <div class="column is-2">
                <h1 style="font-weight: bold">Ginjal Kiri:</h1>
              </div>
              <div class="column is-10">
                <VField>
                  <VControl>
                    <VTextarea type="text" class="input" placeholder="Ginjal Kiri" v-model="input.ginjalKiri" />
                  </VControl>
                </VField>
              </div>
            </div>

            <div class="is-flex">
              <div class="column is-2">
                <h1 style="font-weight: bold">Buli / Vesica:</h1>
              </div>
              <div class="column is-10">
                <VField>
                  <VControl>
                    <VTextarea type="text" class="input" placeholder="Buli / Vesica" v-model="input.buliVesica" />
                  </VControl>
                </VField>
              </div>
            </div>

            <div class="is-flex">
              <div class="column is-2">
                <h1 style="font-weight: bold">Prostat (Pasien Laki-laki):</h1>
              </div>
              <div class="column is-10">
                <VField>
                  <VControl>
                    <VTextarea type="text" class="input" placeholder="Prostat" v-model="input.prostat" />
                  </VControl>
                </VField>
              </div>
            </div>

            <div class="is-flex">
              <div class="column is-2">
                <h1 style="font-weight: bold">Kesimpulan dan Saran:</h1>
              </div>
              <div class="column is-10">
                <VField>
                  <VControl>
                    <VTextarea type="text" class="input" placeholder="Kesimpulan dan Saran"
                      v-model="input.kesimpulansaran" />
                  </VControl>
                </VField>
              </div>
            </div>
          </VCard>
        </div>
        <div class="column is-12"
          v-if="isInputData === true && isRiwayat === false && props.registrasi.objectruanganlastfk == 375">
          <VCard>
            <div class="columns is-multiline">
              <div class="column is-9 pt-0">
                <h1>Nama Template&emsp;&emsp;<span style="color: rgb(230, 41, 100);">**Hanya diisi jika ingin
                    membuat template</span></h1>
                <VField>
                  <VControl>
                    <VInput type="text" class="input" v-model="input.namatemplate" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-3 mt-auto">
                <VButton type="button" rounded outlined color="primary" raised icon="feather:folder"
                  :loading="isLoading" @click="pilihTemplateFix(index)"> Pilih Template
                </VButton>
              </div>
            </div>
            <div class="is-flex">
              <div class="column is-2">
                <h1 style="font-weight: bold">NAMA PASIEN:</h1>
              </div>
              <div class="column is-10">
                <VField>
                  <VControl>
                    <VInput v-model="input.namaPasien" class="input" type="text" disabled />
                  </VControl>
                </VField>
              </div>
            </div>
            <div class="is-flex">
              <div class="column is-2">
                <h1 style="font-weight: bold">TANGGAL LAHIR PASIEN:</h1>
              </div>
              <div class="column is-10">
                <VField>
                  <VDatePicker v-model="input.tanggalLahirPasien" mode="date" trim-weeks :max-date="new Date()">
                    <template #default="{ inputValue, inputEvents }">
                      <VField>
                        <VControl icon="feather:calendar" fullwidth>
                          <VInput :value="inputValue" placeholder="Tanggal" disabled />
                        </VControl>
                      </VField>
                    </template>
                  </VDatePicker>
                </VField>
              </div>
            </div>
            <div class="is-flex">
              <div class="column is-2">
                <h1 style="font-weight: bold">JENIS KELAMIN:</h1>
              </div>
              <div class="column is-10" style="display: flex">
                <VField v-for="items in JenisKelamin" :key="items.value">
                  <VControl raw subcontrol>
                    <VCheckbox v-model="input.jeniskelamin" class="pt-1 pb-1" :true-value="items.label"
                      :label="items.label" color="primary" disabled circle />
                  </VControl>
                </VField>
              </div>
            </div>
            <div class="is-flex">
              <div class="column is-2">
                <h1 style="font-weight: bold">No. Rekam Medis:</h1>
              </div>
              <div class="column is-10">
                <VField>
                  <VControl>
                    <VInput type="text" class="input" placeholder="No. Rekam Medis" v-model="input.norm" disabled />
                  </VControl>
                </VField>
              </div>
            </div>

            <div class="is-flex">
              <div class="column is-2">
                <h1 style="font-weight: bold">Diagnosa:</h1>
              </div>
              <div class="column is-10">
                <VField>
                  <VControl>
                    <VTextarea type="text" class="input" placeholder="Diagnosa" v-model="input.diagnosa" />
                  </VControl>
                </VField>
              </div>
            </div>

            <div class="is-flex">
              <div class="column is-2">
                <h1 style="font-weight: bold">Ginjal Kanan:</h1>
              </div>
              <div class="column is-10">
                <VField>
                  <VControl>
                    <VTextarea type="text" class="input" placeholder="Ginjal Kanan" v-model="input.ginjalKanan" />
                  </VControl>
                </VField>
              </div>
            </div>

            <div class="is-flex">
              <div class="column is-2">
                <h1 style="font-weight: bold">Ginjal Kiri:</h1>
              </div>
              <div class="column is-10">
                <VField>
                  <VControl>
                    <VTextarea type="text" class="input" placeholder="Ginjal Kiri" v-model="input.ginjalKiri" />
                  </VControl>
                </VField>
              </div>
            </div>

            <div class="is-flex">
              <div class="column is-2">
                <h1 style="font-weight: bold">Buli / Vesica:</h1>
              </div>
              <div class="column is-10">
                <VField>
                  <VControl>
                    <VTextarea type="text" class="input" placeholder="Buli / Vesica" v-model="input.buliVesica" />
                  </VControl>
                </VField>
              </div>
            </div>

            <div class="is-flex">
              <div class="column is-2">
                <h1 style="font-weight: bold">Prostat (Pasien Laki-laki):</h1>
              </div>
              <div class="column is-10">
                <VField>
                  <VControl>
                    <VTextarea type="text" class="input" placeholder="Prostat" v-model="input.prostat" />
                  </VControl>
                </VField>
              </div>
            </div>

            <div class="is-flex">
              <div class="column is-2">
                <h1 style="font-weight: bold">Kesimpulan dan Saran:</h1>
              </div>
              <div class="column is-10">
                <VField>
                  <VControl>
                    <VTextarea type="text" class="input" placeholder="Kesimpulan dan Saran"
                      v-model="input.kesimpulansaran" />
                  </VControl>
                </VField>
              </div>
            </div>
          </VCard>
        </div>
        <!-- RUANGAN INPUT PENUNJANG POLI UROLOGI -->

        <!-- RUANGAN INPUT PENUNJANG POLI PARU -->
        <div class="column is-12"
          v-if="isInputData === true && isRiwayat === false && props.registrasi.objectruanganlastfk == 206">
          <VCard>
            <div class="is-flex">
              <div class="column is-2">
                <h1 style="font-weight: bold">NAMA PASIEN:</h1>
              </div>
              <div class="column is-10">
                <VField>
                  <VControl>
                    <VInput v-model="input.namaPasien" class="input" type="text" disabled />
                  </VControl>
                </VField>
              </div>
            </div>
            <div class="is-flex">
              <div class="column is-2">
                <h1 style="font-weight: bold">UMUR PASIEN:</h1>
              </div>
              <div class="column is-10">
                <VField>
                  <VControl>
                    <VInput v-model="input.umurPasien" class="input" type="text" disabled />
                  </VControl>
                </VField>
              </div>
            </div>
            <div class="is-flex">
              <div class="column is-2">
                <h1 style="font-weight: bold">TANGGAL LAHIR PASIEN:</h1>
              </div>
              <div class="column is-10">
                <VField>
                  <VDatePicker v-model="input.tanggalLahirPasien" mode="date" trim-weeks :max-date="new Date()">
                    <template #default="{ inputValue, inputEvents }">
                      <VField>
                        <VControl icon="feather:calendar" fullwidth>
                          <VInput :value="inputValue" placeholder="Tanggal" disabled />
                        </VControl>
                      </VField>
                    </template>
                  </VDatePicker>
                </VField>
              </div>
            </div>
            <div class="is-flex">
              <div class="column is-2">
                <h1 style="font-weight: bold">JENIS KELAMIN:</h1>
              </div>
              <div class="column is-10" style="display: flex">
                <VField v-for="items in JenisKelamin" :key="items.value">
                  <VControl raw subcontrol>
                    <VCheckbox v-model="input.jeniskelamin" class="pt-1 pb-1" :true-value="items.label"
                      :label="items.label" color="primary" disabled circle />
                  </VControl>
                </VField>
              </div>
            </div>
            <div class="is-flex">
              <div class="column is-2">
                <h1 style="font-weight: bold">Dokter:</h1>
              </div>
              <div class="column is-10">
                <VField>
                  <VControl>
                    <AutoComplete v-model="input.DDDokter" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                      :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                      :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                  </VControl>
                </VField>
              </div>
            </div>
            <div class="is-flex">
              <div class="column is-2">
                <h1 style="font-weight: bold">No. Rekam Medis:</h1>
              </div>
              <div class="column is-10">
                <VField>
                  <VControl>
                    <VInput type="text" class="input" placeholder="No. Rekam Medis" v-model="input.norm" disabled />
                  </VControl>
                </VField>
              </div>
            </div>
            <div class="column is-12 mt-5">
              <TabMenu :model="[]" :scrollable="true" />
              <div class="p-tabmenu-uy">
                <div class="p-tabmenu p-component" ref="contentRefScrollMenu">
                  <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                    <li class="p-tabmenuitem" role="presentation">
                      <a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                        @click="Bodyplethysmograph"><span class="p-menuitem-icon"></span><span
                          class="p-menuitem-text">Hasil
                          Pemeriksaan Bodyplethysmograph</span></a>
                    </li>
                  </ul>
                  <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                    <li class="p-tabmenuitem" role="presentation">
                      <a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                        @click="dlcoBodyPlethy">
                        <span class="p-menuitem-icon"></span><span class="p-menuitem-text">Hasil Pemeriksaan DLCO -
                          Bodyplethysmograph</span></a>
                    </li>
                  </ul>
                  <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                    <li class="p-tabmenuitem" role="presentation">
                      <a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                        @click="sprirometri">
                        <span class="p-menuitem-icon"></span><span class="p-menuitem-text">Hasil Pemeriksaan
                          Spirometri</span></a>
                    </li>
                  </ul>
                  <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                    <li class="p-tabmenuitem" role="presentation">
                      <a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0" @click="ekgParu">
                        <span class="p-menuitem-icon"></span><span class="p-menuitem-text">Hasil Pemeriksaan
                          EKG</span></a>
                    </li>
                  </ul>
                </div>
              </div>
            </div>

            <div class="column is-12" v-if="isBodyPlethySmograph == true">
              <div class="columns is-multiline">
                <div class="column is-12" style="font-weight: bold;">
                  Hasil Pemeriksaan Bodyplethysmograph:
                </div>
                <div class="column is-6">
                  <VField label="VC Max">
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" v-model="input.TAVCmax" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>%</VButton>
                      </VControl>
                    </VField>
                  </VField>
                </div>
                <div class="column is-6">
                  <VField label="FVC/pred">
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" v-model="input.TAFVCpred" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>%</VButton>
                      </VControl>
                    </VField>
                  </VField>
                </div>
                <div class="column is-6">
                  <VField label="FEV1/pred">
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" v-model="input.TAFEV1pred" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>%</VButton>
                      </VControl>
                    </VField>
                  </VField>
                </div>
                <div class="column is-6">
                  <VField label="FEV1/FVC">
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" v-model="input.TAFEV1FVCpred" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>%</VButton>
                      </VControl>
                    </VField>
                  </VField>
                </div>
                <div class="column is-6">
                  <VField label="PEF/pred">
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" v-model="input.TAPEFpred" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>%</VButton>
                      </VControl>
                    </VField>
                  </VField>
                </div>
                <div class="column is-6">
                  <VField label="FEF25/50/75/pred">
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" v-model="input.FEF255075pred" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>%</VButton>
                      </VControl>
                    </VField>
                  </VField>
                </div>
                <div class="column is-6">
                  <VField label="TLC">
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" v-model="input.ylc" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>%</VButton>
                      </VControl>
                    </VField>
                  </VField>
                </div>
                <div class="column is-6">
                  <VField label="Berat Badan">
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" v-model="input.TBSBeratBadan" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>Kg</VButton>
                      </VControl>
                    </VField>
                  </VField>
                </div>
                <div class="column is-6">
                  <VField label="Tinggi Badan">
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" v-model="input.TBSTinggiBadan" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>Cm</VButton>
                      </VControl>
                    </VField>
                  </VField>
                </div>
                <div class="column is-12">
                  <VField label="Kesimpulan">
                    <VTextarea rows="2" v-model="input.TAKesimpulan"></VTextarea>
                  </VField>
                </div>
              </div>
            </div>

            <div class="column is-12" v-if="isDlcoBodyPlethy == true">
              <div class="columns is-multiline">
                <div class="column is-12" style="font-weight: bold;">
                  Hasil Pemeriksaan DLCO Bodyplethysmograph:
                </div>
                <div class="column is-6">
                  <VField label="VC Max">
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" v-model="input.TAVCmax" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>%</VButton>
                      </VControl>
                    </VField>
                  </VField>
                </div>
                <div class="column is-6">
                  <VField label="FVC/pred">
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" v-model="input.TAFVCpred" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>%</VButton>
                      </VControl>
                    </VField>
                  </VField>
                </div>
                <div class="column is-6">
                  <VField label="FEV1/pred">
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" v-model="input.TAFEV1pred" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>%</VButton>
                      </VControl>
                    </VField>
                  </VField>
                </div>
                <div class="column is-6">
                  <VField label="FEV1/FVC">
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" v-model="input.TAFEV1FVCpred" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>%</VButton>
                      </VControl>
                    </VField>
                  </VField>
                </div>
                <div class="column is-6">
                  <VField label="PEF/pred">
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" v-model="input.TAPEFpred" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>%</VButton>
                      </VControl>
                    </VField>
                  </VField>
                </div>
                <div class="column is-6">
                  <VField label="FEF25/50/75/pred">
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" v-model="input.FEF255075pred" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>%</VButton>
                      </VControl>
                    </VField>
                  </VField>
                </div>
                <div class="column is-6">
                  <VField label="TLC">
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" v-model="input.TATCC" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>%</VButton>
                      </VControl>
                    </VField>
                  </VField>
                </div>
                <div class="column is-6">
                  <VField label="DLCO">
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" v-model="input.TADLCCO" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>%</VButton>
                      </VControl>
                    </VField>
                  </VField>
                </div>
                <div class="column is-6">
                  <VField label="Berat Badan">
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" v-model="input.TBSBeratBadan" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>Kg</VButton>
                      </VControl>
                    </VField>
                  </VField>
                </div>
                <div class="column is-6">
                  <VField label="Tinggi Badan">
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" v-model="input.TBSTinggiBadan" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>Cm</VButton>
                      </VControl>
                    </VField>
                  </VField>
                </div>
                <div class="column is-12">
                  <VField label="Kesimpulan">
                    <VTextarea rows="2" v-model="input.TAKesimpulan"></VTextarea>
                  </VField>
                </div>
              </div>
            </div>

            <div class="column is-12" v-if="isSprirometri == true">
              <div class="columns is-multiline">
                <div class="column is-12" style="font-weight: bold;">
                  Hasil Pemeriksaan Spirometri:
                </div>
                <div class="column is-6">
                  <VField label="SVC/pred">
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" v-model="input.TASVCpred" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>%</VButton>
                      </VControl>
                    </VField>
                  </VField>
                </div>
                <div class="column is-6">
                  <VField label="FVC/pred">
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" v-model="input.TAFVCpred" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>%</VButton>
                      </VControl>
                    </VField>
                  </VField>
                </div>
                <div class="column is-6">
                  <VField label="FEV1/pred">
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" v-model="input.TAFEV1pred" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>%</VButton>
                      </VControl>
                    </VField>
                  </VField>
                </div>
                <div class="column is-6">
                  <VField label="FEV1/FVC">
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" v-model="input.TAFEV1FVCpred" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>%</VButton>
                      </VControl>
                    </VField>
                  </VField>
                </div>
                <div class="column is-6">
                  <VField label="PEF/pred">
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" v-model="input.TAPEFpred" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>%</VButton>
                      </VControl>
                    </VField>
                  </VField>
                </div>
                <div class="column is-6">
                  <VField label="FEF25/50/75/pred">
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" v-model="input.FEF255075pred" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>%</VButton>
                      </VControl>
                    </VField>
                  </VField>
                </div>
                <div class="column is-6">
                  <VField label="Berat Badan">
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" v-model="input.TBSBeratBadan" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>Kg</VButton>
                      </VControl>
                    </VField>
                  </VField>
                </div>
                <div class="column is-6">
                  <VField label="Tinggi Badan">
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" v-model="input.TBSTinggiBadan" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>Cm</VButton>
                      </VControl>
                    </VField>
                  </VField>
                </div>
                <div class="column is-12">
                  <VField label="Kesimpulan">
                    <VTextarea rows="2" v-model="input.TAKesimpulan"></VTextarea>
                  </VField>
                </div>
              </div>
            </div>

            <div class="column is-12" v-if="isEKGParu == true">
              <div class="column is-2">
                <VField>
                  <VControl>
                    <VCheckbox class="fontcheckbox" v-model="input.batasnormalkepala" true-value="Dalam Batas Normal"
                      label="Dalam Batas Normal" color="primary" circle @click="normalEKG" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-6" style="margin-top: 20px;">
                <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> P wave </h1>
                <VField>
                  <VField>
                    <VControl>
                      <VInput type="text" class="input" placeholder="P wave" v-model="input.Pwave" />
                    </VControl>
                  </VField>
                </VField>
              </div>
              <div class="column is-6" style="margin-top: 20px;">
                <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> HR </h1>
                <VField>
                  <VField>
                    <VControl>
                      <VInput type="text" class="input" placeholder="HR" v-model="input.HR" />
                    </VControl>
                  </VField>
                </VField>
              </div>
              <div class="column is-6">
                <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> PR Interval </h1>
                <VField>
                  <VField>
                    <VControl>
                      <VInput type="text" class="input" placeholder="PR Interval" v-model="input.PRInterval" />
                    </VControl>
                  </VField>
                </VField>
              </div>
              <div class="column is-6">
                <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> Axis </h1>
                <VField>
                  <VField>
                    <VControl>
                      <VInput type="text" class="input" placeholder="Axis" v-model="input.Axis" />
                    </VControl>
                  </VField>
                </VField>
              </div>
              <div class="column is-6">
                <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> QRS </h1>
                <VField>
                  <VField>
                    <VControl>
                      <VInput type="text" class="input" placeholder="QRS" v-model="input.QRS" />
                    </VControl>
                  </VField>
                </VField>
              </div>
              <div class="column is-6">
                <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> Other </h1>
                <VField>
                  <VField>
                    <VControl>
                      <VInput type="text" class="input" placeholder="Other" v-model="input.Other" />
                    </VControl>
                  </VField>
                </VField>
              </div>
              <div class="column is-6">
                <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> ST Segment </h1>
                <VField>
                  <VField>
                    <VControl>
                      <VInput type="text" class="input" placeholder="ST Segment" v-model="input.STSegment" />
                    </VControl>
                  </VField>
                </VField>
              </div>
              <div class="column is-6">
                <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> T wave </h1>
                <VField>
                  <VField>
                    <VControl>
                      <VInput type="text" class="input" placeholder="T wave" v-model="input.Twave" />
                    </VControl>
                  </VField>
                </VField>
              </div>
              <div class="column is-6">
                <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> QT Interval </h1>
                <VField>
                  <VField>
                    <VControl>
                      <VInput type="text" class="input" placeholder="QT Interval" v-model="input.QTInterval" />
                    </VControl>
                  </VField>
                </VField>
              </div>
              <div class="column is-6">
                <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> Conclusion </h1>
                <VField>
                  <VField>
                    <VControl>
                      <VTextarea class="textarea" v-model="input.conclusion" rows="5" placeholder="Conclusion"
                        autocomplete="off" autocapitalize="off" spellcheck="true" />
                    </VControl>
                  </VField>
                </VField>
              </div>
            </div>
          </VCard>
        </div>
        <div class="column is-12"
          v-if="isInputData === true && isRiwayat === false && props.registrasi.objectruanganlastfk == 382">
          <VCard>
            <div class="is-flex">
              <div class="column is-2">
                <h1 style="font-weight: bold">NAMA PASIEN:</h1>
              </div>
              <div class="column is-10">
                <VField>
                  <VControl>
                    <VInput v-model="input.namaPasien" class="input" type="text" disabled />
                  </VControl>
                </VField>
              </div>
            </div>
            <div class="is-flex">
              <div class="column is-2">
                <h1 style="font-weight: bold">UMUR PASIEN:</h1>
              </div>
              <div class="column is-10">
                <VField>
                  <VControl>
                    <VInput v-model="input.umurPasien" class="input" type="text" disabled />
                  </VControl>
                </VField>
              </div>
            </div>
            <div class="is-flex">
              <div class="column is-2">
                <h1 style="font-weight: bold">TANGGAL LAHIR PASIEN:</h1>
              </div>
              <div class="column is-10">
                <VField>
                  <VDatePicker v-model="input.tanggalLahirPasien" mode="date" trim-weeks :max-date="new Date()">
                    <template #default="{ inputValue, inputEvents }">
                      <VField>
                        <VControl icon="feather:calendar" fullwidth>
                          <VInput :value="inputValue" placeholder="Tanggal" disabled />
                        </VControl>
                      </VField>
                    </template>
                  </VDatePicker>
                </VField>
              </div>
            </div>
            <div class="is-flex">
              <div class="column is-2">
                <h1 style="font-weight: bold">JENIS KELAMIN:</h1>
              </div>
              <div class="column is-10" style="display: flex">
                <VField v-for="items in JenisKelamin" :key="items.value">
                  <VControl raw subcontrol>
                    <VCheckbox v-model="input.jeniskelamin" class="pt-1 pb-1" :true-value="items.label"
                      :label="items.label" color="primary" disabled circle />
                  </VControl>
                </VField>
              </div>
            </div>
            <div class="is-flex">
              <div class="column is-2">
                <h1 style="font-weight: bold">Dokter:</h1>
              </div>
              <div class="column is-10">
                <VField>
                  <VControl>
                    <AutoComplete v-model="input.DDDokter" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                      :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                      :loadingIcon="'pi pi-spinner'" :field="'label'" class="mt-2" />
                  </VControl>
                </VField>
              </div>
            </div>
            <div class="is-flex">
              <div class="column is-2">
                <h1 style="font-weight: bold">No. Rekam Medis:</h1>
              </div>
              <div class="column is-10">
                <VField>
                  <VControl>
                    <VInput type="text" class="input" placeholder="No. Rekam Medis" v-model="input.norm" disabled />
                  </VControl>
                </VField>
              </div>
            </div>
            <div class="column is-12 mt-5">
              <TabMenu :model="[]" :scrollable="true" />
              <div class="p-tabmenu-uy">
                <div class="p-tabmenu p-component" ref="contentRefScrollMenu">
                  <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                    <li class="p-tabmenuitem" role="presentation">
                      <a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                        @click="Bodyplethysmograph"><span class="p-menuitem-icon"></span><span
                          class="p-menuitem-text">Hasil
                          Pemeriksaan Bodyplethysmograph</span></a>
                    </li>
                  </ul>
                  <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                    <li class="p-tabmenuitem" role="presentation">
                      <a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                        @click="dlcoBodyPlethy">
                        <span class="p-menuitem-icon"></span><span class="p-menuitem-text">Hasil Pemeriksaan DLCO -
                          Bodyplethysmograph</span></a>
                    </li>
                  </ul>
                  <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                    <li class="p-tabmenuitem" role="presentation">
                      <a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0"
                        @click="sprirometri">
                        <span class="p-menuitem-icon"></span><span class="p-menuitem-text">Hasil Pemeriksaan
                          Spirometri</span></a>
                    </li>
                  </ul>
                  <ul class="p-tabmenu-nav p-reset content-scroll" role="menubar">
                    <li class="p-tabmenuitem" role="presentation">
                      <a role="menuitem" class="p-menuitem-link" aria-label="Dashboard" tabindex="0" @click="ekgParu">
                        <span class="p-menuitem-icon"></span><span class="p-menuitem-text">Hasil Pemeriksaan
                          EKG</span></a>
                    </li>
                  </ul>
                </div>
              </div>
            </div>

            <div class="column is-12" v-if="isEKGParu == true">
              <div class="column is-2">
                <VField>
                  <VControl>
                    <VCheckbox class="fontcheckbox" v-model="input.batasnormalkepala" true-value="Dalam Batas Normal"
                      label="Dalam Batas Normal" color="primary" circle @click="normalEKG" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-6" style="margin-top: 20px;">
                <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> P wave </h1>
                <VField>
                  <VField>
                    <VControl>
                      <VInput type="text" class="input" placeholder="P wave" v-model="input.Pwave" />
                    </VControl>
                  </VField>
                </VField>
              </div>
              <div class="column is-6" style="margin-top: 20px;">
                <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> HR </h1>
                <VField>
                  <VField>
                    <VControl>
                      <VInput type="text" class="input" placeholder="HR" v-model="input.HR" />
                    </VControl>
                  </VField>
                </VField>
              </div>
              <div class="column is-6">
                <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> PR Interval </h1>
                <VField>
                  <VField>
                    <VControl>
                      <VInput type="text" class="input" placeholder="PR Interval" v-model="input.PRInterval" />
                    </VControl>
                  </VField>
                </VField>
              </div>
              <div class="column is-6">
                <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> Axis </h1>
                <VField>
                  <VField>
                    <VControl>
                      <VInput type="text" class="input" placeholder="Axis" v-model="input.Axis" />
                    </VControl>
                  </VField>
                </VField>
              </div>
              <div class="column is-6">
                <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> QRS </h1>
                <VField>
                  <VField>
                    <VControl>
                      <VInput type="text" class="input" placeholder="QRS" v-model="input.QRS" />
                    </VControl>
                  </VField>
                </VField>
              </div>
              <div class="column is-6">
                <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> Other </h1>
                <VField>
                  <VField>
                    <VControl>
                      <VInput type="text" class="input" placeholder="Other" v-model="input.Other" />
                    </VControl>
                  </VField>
                </VField>
              </div>
              <div class="column is-6">
                <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> ST Segment </h1>
                <VField>
                  <VField>
                    <VControl>
                      <VInput type="text" class="input" placeholder="ST Segment" v-model="input.STSegment" />
                    </VControl>
                  </VField>
                </VField>
              </div>
              <div class="column is-6">
                <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> T wave </h1>
                <VField>
                  <VField>
                    <VControl>
                      <VInput type="text" class="input" placeholder="T wave" v-model="input.Twave" />
                    </VControl>
                  </VField>
                </VField>
              </div>
              <div class="column is-6">
                <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> QT Interval </h1>
                <VField>
                  <VField>
                    <VControl>
                      <VInput type="text" class="input" placeholder="QT Interval" v-model="input.QTInterval" />
                    </VControl>
                  </VField>
                </VField>
              </div>
              <div class="column is-6">
                <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> Conclusion </h1>
                <VField>
                  <VField>
                    <VControl>
                      <VTextarea class="textarea" v-model="input.conclusion" rows="5" placeholder="Conclusion"
                        autocomplete="off" autocapitalize="off" spellcheck="true" />
                    </VControl>
                  </VField>
                </VField>
              </div>
            </div>

            <div class="column is-12" v-if="isBodyPlethySmograph == true">
              <div class="columns is-multiline">
                <div class="column is-12" style="font-weight: bold;">
                  Hasil Pemeriksaan Bodyplethysmograph:
                </div>
                <div class="column is-6">
                  <VField label="VC Max">
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" v-model="input.TAVCmax" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>%</VButton>
                      </VControl>
                    </VField>
                  </VField>
                </div>
                <div class="column is-6">
                  <VField label="FVC/pred">
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" v-model="input.TAFVCpred" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>%</VButton>
                      </VControl>
                    </VField>
                  </VField>
                </div>
                <div class="column is-6">
                  <VField label="FEV1/pred">
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" v-model="input.TAFEV1pred" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>%</VButton>
                      </VControl>
                    </VField>
                  </VField>
                </div>
                <div class="column is-6">
                  <VField label="FEV1/FVC">
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" v-model="input.TAFEV1FVCpred" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>%</VButton>
                      </VControl>
                    </VField>
                  </VField>
                </div>
                <div class="column is-6">
                  <VField label="PEF/pred">
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" v-model="input.TAPEFpred" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>%</VButton>
                      </VControl>
                    </VField>
                  </VField>
                </div>
                <div class="column is-6">
                  <VField label="FEF25/50/75/pred">
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" v-model="input.FEF255075pred" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>%</VButton>
                      </VControl>
                    </VField>
                  </VField>
                </div>
                <div class="column is-6">
                  <VField label="TLC">
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" v-model="input.ylc" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>%</VButton>
                      </VControl>
                    </VField>
                  </VField>
                </div>
                <div class="column is-6">
                  <VField label="Berat Badan">
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" v-model="input.TBSBeratBadan" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>Kg</VButton>
                      </VControl>
                    </VField>
                  </VField>
                </div>
                <div class="column is-6">
                  <VField label="Tinggi Badan">
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" v-model="input.TBSTinggiBadan" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>Cm</VButton>
                      </VControl>
                    </VField>
                  </VField>
                </div>
                <div class="column is-12">
                  <VField label="Kesimpulan">
                    <VTextarea rows="2" v-model="input.TAKesimpulan"></VTextarea>
                  </VField>
                </div>
              </div>
            </div>

            <div class="column is-12" v-if="isDlcoBodyPlethy == true">
              <div class="columns is-multiline">
                <div class="column is-12" style="font-weight: bold;">
                  Hasil Pemeriksaan DLCO Bodyplethysmograph:
                </div>
                <div class="column is-6">
                  <VField label="VC Max">
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" v-model="input.TAVCmax" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>%</VButton>
                      </VControl>
                    </VField>
                  </VField>
                </div>
                <div class="column is-6">
                  <VField label="FVC/pred">
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" v-model="input.TAFVCpred" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>%</VButton>
                      </VControl>
                    </VField>
                  </VField>
                </div>
                <div class="column is-6">
                  <VField label="FEV1/pred">
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" v-model="input.TAFEV1pred" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>%</VButton>
                      </VControl>
                    </VField>
                  </VField>
                </div>
                <div class="column is-6">
                  <VField label="FEV1/FVC">
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" v-model="input.TAFEV1FVCpred" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>%</VButton>
                      </VControl>
                    </VField>
                  </VField>
                </div>
                <div class="column is-6">
                  <VField label="PEF/pred">
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" v-model="input.TAPEFpred" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>%</VButton>
                      </VControl>
                    </VField>
                  </VField>
                </div>
                <div class="column is-6">
                  <VField label="FEF25/50/75/pred">
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" v-model="input.FEF255075pred" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>%</VButton>
                      </VControl>
                    </VField>
                  </VField>
                </div>
                <div class="column is-6">
                  <VField label="TLC">
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" v-model="input.TATCC" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>%</VButton>
                      </VControl>
                    </VField>
                  </VField>
                </div>
                <div class="column is-6">
                  <VField label="DLCO">
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" v-model="input.TADLCCO" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>%</VButton>
                      </VControl>
                    </VField>
                  </VField>
                </div>
                <div class="column is-6">
                  <VField label="Berat Badan">
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" v-model="input.TBSBeratBadan" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>Kg</VButton>
                      </VControl>
                    </VField>
                  </VField>
                </div>
                <div class="column is-6">
                  <VField label="Tinggi Badan">
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" v-model="input.TBSTinggiBadan" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>Cm</VButton>
                      </VControl>
                    </VField>
                  </VField>
                </div>
                <div class="column is-12">
                  <VField label="Kesimpulan">
                    <VTextarea rows="2" v-model="input.TAKesimpulan"></VTextarea>
                  </VField>
                </div>
              </div>
            </div>

            <div class="column is-12" v-if="isSprirometri == true">
              <div class="columns is-multiline">
                <div class="column is-12" style="font-weight: bold;">
                  Hasil Pemeriksaan Spirometri:
                </div>
                <div class="column is-6">
                  <VField label="SVC/pred">
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" v-model="input.TASVCpred" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>%</VButton>
                      </VControl>
                    </VField>
                  </VField>
                </div>
                <div class="column is-6">
                  <VField label="FVC/pred">
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" v-model="input.TAFVCpred" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>%</VButton>
                      </VControl>
                    </VField>
                  </VField>
                </div>
                <div class="column is-6">
                  <VField label="FEV1/pred">
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" v-model="input.TAFEV1pred" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>%</VButton>
                      </VControl>
                    </VField>
                  </VField>
                </div>
                <div class="column is-6">
                  <VField label="FEV1/FVC">
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" v-model="input.TAFEV1FVCpred" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>%</VButton>
                      </VControl>
                    </VField>
                  </VField>
                </div>
                <div class="column is-6">
                  <VField label="PEF/pred">
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" v-model="input.TAPEFpred" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>%</VButton>
                      </VControl>
                    </VField>
                  </VField>
                </div>
                <div class="column is-6">
                  <VField label="FEF25/50/75/pred">
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" v-model="input.FEF255075pred" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>%</VButton>
                      </VControl>
                    </VField>
                  </VField>
                </div>
                <div class="column is-6">
                  <VField label="Berat Badan">
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" v-model="input.TBSBeratBadan" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>Kg</VButton>
                      </VControl>
                    </VField>
                  </VField>
                </div>
                <div class="column is-6">
                  <VField label="Tinggi Badan">
                    <VField addons>
                      <VControl>
                        <VInput type="text" class="input" v-model="input.TBSTinggiBadan" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>Cm</VButton>
                      </VControl>
                    </VField>
                  </VField>
                </div>
                <div class="column is-12">
                  <VField label="Kesimpulan">
                    <VTextarea rows="2" v-model="input.TAKesimpulan"></VTextarea>
                  </VField>
                </div>
              </div>
            </div>
          </VCard>
        </div>
        <!-- RUANGAN INPUT PENUNJANG POLI PARU -->

        <!-- RUANGAN INPUT PENUNJANG PERINA -->
        <div class="column is-12"
          v-if="isInputData === true && isRiwayat === false &&
          (props.registrasi.objectruanganlastfk == 303 ||
           props.registrasi.objectruanganlastfk == 317 ||
           props.registrasi.objectruanganlastfk == 318 ||
           props.registrasi.objectruanganlastfk == 319 ||
           props.registrasi.objectruanganlastfk == 320)">

          <div>
            <div class="is-flex">
              <div class="column is-2">
                <h1 style="font-weight: bold">NAMA PASIEN:</h1>
              </div>
              <div class="column is-10">
                <VField>
                  <VControl>
                    <VInput v-model="input.namaPasien" class="input" type="text" disabled />
                  </VControl>
                </VField>
              </div>
            </div>
            <div class="is-flex">
              <div class="column is-2">
                <h1 style="font-weight: bold">TANGGAL LAHIR PASIEN:</h1>
              </div>
              <div class="column is-10">
                <VField>
                  <VDatePicker v-model="input.tanggalLahirPasien" mode="date" trim-weeks :max-date="new Date()">
                    <template #default="{ inputValue, inputEvents }">
                      <VField>
                        <VControl icon="feather:calendar" fullwidth>
                          <VInput :value="inputValue" placeholder="Tanggal" disabled />
                        </VControl>
                      </VField>
                    </template>
                  </VDatePicker>
                </VField>
              </div>
            </div>
            <div class="is-flex">
              <div class="column is-2">
                <h1 style="font-weight: bold">JENIS KELAMIN:</h1>
              </div>
              <div class="column is-10" style="display: flex">
                <VField v-for="items in JenisKelamin" :key="items.value">
                  <VControl raw subcontrol>
                    <VCheckbox v-model="input.jeniskelamin" class="pt-1 pb-1" :true-value="items.label"
                      :label="items.label" color="primary" disabled circle />
                  </VControl>
                </VField>
              </div>
            </div>
            <div class="is-flex">
              <div class="column is-2">
                <h1 style="font-weight: bold">No. Rekam Medis:</h1>
              </div>
              <div class="column is-10">
                <VField>
                  <VControl>
                    <VInput type="text" class="input" placeholder="No. Rekam Medis" v-model="input.norm" disabled />
                  </VControl>
                </VField>
              </div>
            </div>
            <div class="is-flex">
              <div class="column is-2">
                <h1 style="font-weight: bold">Indikasi:</h1>
              </div>
              <div class="column is-10">
                <VField>
                  <VControl>
                    <VInput type="text" class="input" placeholder="Indikasi" v-model="input.indikasi" />
                  </VControl>
                </VField>
              </div>
            </div>

            <div class="is-flex">
              <div class="column is-2">
                <h1 style="font-weight: bold">Study Type:</h1>
              </div>
              <div class="column is-10">
                <VField class="is-autocomplete-select" v-slot="{ id }">
                  <VControl>
                    <Multiselect v-model="input.studyType" placeholder="--Pilih--" label="label" :options="studyType"
                      :searchable="true" track-by="label" mode="single" autocomplete="off">
                    </Multiselect>
                  </VControl>
                </VField>
              </div>
            </div>

            <div class="is-flex">
              <div class="column is-2">
                <h1 style="font-weight: bold">Sonographer:</h1>
              </div>
              <div class="column is-10">
                <VField class="is-autocomplete-select pt-3">
                  <VControl icon="feather:search">
                    <AutoComplete v-model="input.DDDokter" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                      :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                      :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="ketik Nama Dokter" />
                  </VControl>
                </VField>
              </div>
            </div>

            <div class="is-flex">
              <div class="column is-2">
                <h1 style="font-weight: bold">Reviewer:</h1>
              </div>
              <div class="column is-10">
                <VField class="is-autocomplete-select pt-3">
                  <VControl icon="feather:search">
                    <AutoComplete v-model="input.DDDokter" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                      :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                      :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="ketik Nama Dokter" />
                  </VControl>
                </VField>
              </div>
            </div>

            <div class="is-flex">
              <div class="column is-2">
                <h1 style="font-weight: bold">Tanggal Eksaminasi:</h1>
              </div>
              <div class="column is-10">
                <VField>
                  <VDatePicker v-model="input.tglEksaminasi" mode="date" trim-weeks :max-date="new Date()">
                    <template #default="{ inputValue, inputEvents }">
                      <VField>
                        <VControl icon="feather:calendar" fullwidth>
                          <VInput :value="inputValue" v-on="inputEvents" placeholder="Tanggal" />
                        </VControl>
                      </VField>
                    </template>
                  </VDatePicker>
                </VField>
              </div>
            </div>

            <div class="column is-12 is-multiline" v-if="input.studyType === 'TransThoracaEchoBayi'">
              <Fieldset :toggleable="true" legend="Trans Thoraca Echo Bayi">
                <div class="columns">
                  <div class="column is-one-third">
                    <div v-for="(item, index) in resultTransThoracaEchoBayi.slice(0, 6)" :key="index" class="mb-3">
                      <h1 style="font-weight: bold">{{ item.label }}:</h1>
                      <VField class="ml-2" addons>
                        <VControl>
                          <VInput v-model="input[item.model]" type="text" :placeholder="item.label" />
                        </VControl>
                        <VControl class="field-addon-body">
                          <VButton static>{{ item.addons }}</VButton>
                        </VControl>
                      </VField>
                    </div>
                  </div>

                  <div class="column is-one-third">
                    <div v-for="(item, index) in resultTransThoracaEchoBayi.slice(6, 12)" :key="index" class="mb-3">
                      <h1 style="font-weight: bold">{{ item.label }}:</h1>
                      <VField class="ml-2" addons>
                        <VControl>
                          <VInput v-model="input[item.model]" type="text" :placeholder="item.label" />
                        </VControl>
                        <VControl class="field-addon-body">
                          <VButton static>{{ item.addons }}</VButton>
                        </VControl>
                      </VField>
                    </div>
                  </div>

                  <div class="column is-one-third">
                    <div v-for="(item, index) in resultTransThoracaEchoBayi.slice(12)" :key="index" class="mb-3">
                      <h1 style="font-weight: bold">{{ item.label }}:</h1>
                      <VField class="ml-2" addons>
                        <VControl>
                          <VInput v-model="input[item.model]" type="text" :placeholder="item.label" />
                        </VControl>
                        <VControl class="field-addon-body">
                          <VButton static>{{ item.addons }}</VButton>
                        </VControl>
                      </VField>
                    </div>
                  </div>
                </div>

                <div class="columns is-12">
                  <VField>
                    <VControl>
                      <VCheckbox type="text" v-model="input.dalamBatasNormalEchoBayi" label="Dalam Batas Normal"
                        true-value="Dalam Batas Normal" @click="normalEchoBayi" />
                    </VControl>
                  </VField>
                </div>

                <div class="is-12 is-flex">
                  <div class="column is-14">
                    <h1 style="font-weight: bold">Finding:</h1>
                  </div>
                  <div class="column is-10">
                    <VField>
                      <VControl>
                        <VTextarea v-model="input.finding" type="text" placeholder="Finding" />
                      </VControl>
                    </VField>
                  </div>
                </div>

                <div class="is-12 is-flex">
                  <div class="column is-14">
                    <h1 style="font-weight: bold">Conclusion:</h1>
                  </div>
                  <div class="column is-10">
                    <VField>
                      <VControl>
                        <VTextarea v-model="input.conclusion" type="text" placeholder="Conclusion" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </Fieldset>
            </div>

            <div class="column is-12 " v-if="input.studyType === 'TransThoracaEchoDewasa'">
              <Fieldset :toggleable="true" legend="Trans Thoraca Echo Dewasa">
                <div class="columns">
                  <div class="column is-one-third">
                    <div v-for="(item, index) in resultTransThoracaEchoDewasa.slice(0, 6)" :key="index" class="mb-3">
                      <h1 style="font-weight: bold">{{ item.label }}:</h1>
                      <VField class="ml-2" addons>
                        <VControl>
                          <VInput v-model="input[item.model]" type="text" :placeholder="item.label" />
                        </VControl>
                        <VControl class="field-addon-body">
                          <VButton static>{{ item.addons }}</VButton>
                        </VControl>
                      </VField>
                    </div>
                  </div>

                  <div class="column is-one-third">
                    <div v-for="(item, index) in resultTransThoracaEchoDewasa.slice(6, 12, 24)" :key="index"
                      class="mb-3">
                      <h1 style="font-weight: bold">{{ item.label }}:</h1>
                      <VField class="ml-2" addons>
                        <VControl>
                          <VInput v-model="input[item.model]" type="text" :placeholder="item.label" />
                        </VControl>
                        <VControl class="field-addon-body">
                          <VButton static>{{ item.addons }}</VButton>
                        </VControl>
                      </VField>
                    </div>
                  </div>

                  <div class="column is-one-third">
                    <div v-for="(item, index) in resultTransThoracaEchoDewasa.slice(12, 24)" :key="index" class="mb-3">
                      <h1 style="font-weight: bold">{{ item.label }}:</h1>
                      <VField class="ml-2" addons>
                        <VControl>
                          <VInput v-model="input[item.model]" type="text" :placeholder="item.label" />
                        </VControl>
                        <VControl class="field-addon-body">
                          <VButton static>{{ item.addons }}</VButton>
                        </VControl>
                      </VField>
                    </div>
                  </div>
                </div>

                <div class="column is-12">
                  <Fieldset :toggleable="true" legend="Finding">
                    <div class="is-12 is-end">
                      <VField>
                        <VControl>
                          <VCheckbox v-model="input.dalamBatasNormalEchoDewasa" label="Dalam Batas Normal"
                            @click="normalEchoDewasa" true-value="Dalam Batas Normal" />
                        </VControl>
                      </VField>
                    </div>
                    <div class="is-12" style="display: flex; flex-wrap: wrap;">
                      <!-- Kolom Kiri -->
                      <div class="column is-6">
                        <div v-for="(item, index) in leftColumnItems" :key="index"
                          class="is-flex align-items-center mb-2">
                          <div class="column is-4">
                            <h1 style="font-weight: bold">{{ item.label }}:</h1>
                          </div>
                          <div class="column is-8">
                            <VField>
                              <VControl>
                                <VInput v-model="input[item.model]" :placeholder="item.label" />
                              </VControl>
                            </VField>
                          </div>
                        </div>
                      </div>

                      <!-- Kolom Kanan -->
                      <div class="column is-6">
                        <h1 style="font-weight: bold">Heart Valves:</h1>
                        <div v-for="(item, index) in rightColumnItems" :key="index"
                          class="is-flex align-items-center mb-2">
                          <div class="column is-4">
                            <h1 style="font-weight: bold">{{ item.label }}:</h1>
                          </div>
                          <div class="column is-8">
                            <VField>
                              <VControl>
                                <VInput v-model="input[item.model]" :placeholder="item.label" />
                              </VControl>
                            </VField>
                          </div>
                        </div>

                        <h1 style="font-weight: bold; margin-top: 1rem;">Pericardium</h1>
                        <div class="is-flex align-items-center">
                          <div class="column is-4">
                            <h1 style="font-weight: bold">Pericardium:</h1>
                          </div>
                          <div class="column is-8">
                            <VField>
                              <VControl>
                                <VInput v-model="input.pericardium" placeholder="Pericardium" />
                              </VControl>
                            </VField>
                          </div>
                        </div>
                      </div>
                    </div>
                  </Fieldset>
                </div>

                <div class="is-12 is-flex">
                  <div class="column is-14">
                    <h1 style="font-weight: bold">Conclusion:</h1>
                  </div>
                  <div class="column is-10">
                    <VField>
                      <VControl>
                        <VTextarea v-model="input.conclusion" type="text" placeholder="Conclusion" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </Fieldset>
            </div>

            <div class="column is-12" v-if="input.studyType === 'LowerExtermityDuplexUltrasoundUSGDoppler'">
              <Fieldset :toggleable="true" legend="Lower Extermity Duplex Ultrasound USG Doppler">
                <div class="is-12 is-flex">
                  <div class="column is-14">
                    <h1 style="font-weight: bold">Finding:</h1>
                  </div>
                  <div class="column is-10">
                    <VField>
                      <VControl>
                        <VTextarea v-model="input.finding" type="text" placeholder="Finding" />
                      </VControl>
                    </VField>
                  </div>
                </div>

                <div class="is-12 is-flex">
                  <div class="column is-14">
                    <h1 style="font-weight: bold">Conclusion:</h1>
                  </div>
                  <div class="column is-10">
                    <VField>
                      <VControl>
                        <VTextarea v-model="input.conclusion" type="text" placeholder="Conclusion" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </Fieldset>
            </div>

            <div class="column is-12" v-if="input.studyType === 'CarotidDuplexUltrasound'">
              <Fieldset :toggleable="true" legend="Carotid Duplex Ultrasound">
                <div class="is-12 is-flex">
                  <div class="column is-14">
                    <h1 style="font-weight: bold">Finding:</h1>
                  </div>
                  <div class="column is-10">
                    <VField>
                      <VControl>
                        <VTextarea v-model="input.finding" type="text" placeholder="Finding" />
                      </VControl>
                    </VField>
                  </div>
                </div>

                <div class="is-12 is-flex">
                  <div class="column is-14">
                    <h1 style="font-weight: bold">Conclusion:</h1>
                  </div>
                  <div class="column is-10">
                    <VField>
                      <VControl>
                        <VTextarea v-model="input.conclusion" type="text" placeholder="Conclusion" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </Fieldset>
            </div>

            <div class="column is-12" v-if="input.studyType === 'EKGJantung'">
              <div class="column is-12">
                <VField>
                  <VControl>
                    <VCheckbox class="fontcheckbox" v-model="input.batasNormal" true-value="Dalam Batas Normal"
                      label="Dalam Batas Normal" color="primary" @click="normalEKGJantung" />
                  </VControl>
                </VField>
              </div>

              <div class="columns is-multiline">
                <div class="column is-4" v-for="(items, index) in formField">
                  <div>
                    <h1 style="font-weight: bold">{{ items.label }}:</h1>
                  </div>
                  <div>
                    <VField v-if="items.value !== 'Conclusion'">
                      <VControl>
                        <VInput type="text" class="input" v-model="input[items.value]" :placeholder="items.label" />
                      </VControl>
                    </VField>

                    <VField v-if="items.value === 'Conclusion'">
                      <VControl>
                        <VTextarea type="text" class="input" v-model="input[items.value]" :placeholder="items.label" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>



    </div>
  </div>

  <Dialog v-model:visible="showModalTemplateFix" modal header="Template" :style="{ width: '60vw' }">
    <DataTable :pt="{
      table: { style: 'min-width: 50rem; min-height: 10rem;' },
      column: {
        bodycell: ({ state }) => ({
          class: [{ 'pt-0 pb-0': state['d_editing'] }]
        })
      }
    }" v-model:filters="filtersTemplate" :value="listTemplateFix" :metaKeySelection="false" :rows="10" paginator
      tableStyle="min-width: 50rem" dataKey="no" :totalRecords="listTemplateFix.length"
      :globalFilterFields="['namatemplate', 'registrasi.namaruangan']" responsiveLayout="stack" breakpoint="960px">
      <template #header>
        <div class="columns is-multiline">
          <div class="column is-8">
            <VField>
              <InputText v-model="filtersTemplate['global'].value" placeholder="Search Nama Template" />
            </VField>
          </div>
          <div class="column is-4">
            <VField>
              <VControl>
                <VSwitchBlock v-model="isAlltemplate" color="success" label="Semua Template" />
              </VControl>
            </VField>
          </div>
        </div>
      </template>
      <template #empty> No customers found. </template>
      <template #loading>
        <img src="/images/other/loadingspin.gif" alt="Loading..." width="100" />
        <p style="color:white">Loading data, please wait...</p>
      </template>
      <Column headerStyle="width: 8rem">
        <template #body="slotProps">
          <VButtons>
            <VIconButton color="danger" light raised circle icon="lucide:x" @click="deleteTemplate(slotProps.data.id)"
              v-if="!isAlltemplate" v-tooltip-prime.top="'Hapus'" />
            <VIconButton type="button" raised circle icon="fas fa-plus" @click="addTemplate(slotProps.data)"
              color="info" v-tooltip-prime.top="'Pilih'">
            </VIconButton>
            <VIconButton type="button" raised circle icon="fas fa-pencil-alt" @click="editTemplate(slotProps.data)"
              color="info" v-tooltip-prime.top="'Edit'" v-if="!isAlltemplate">
            </VIconButton>
          </VButtons>
        </template>
      </Column>
      <Column field="namatemplate" header="Nama" :sortable="true"></Column>
      <Column field="registrasi.namaruangan" header="Nama Ruangan" :sortable="true">
        <template #body="slotProps">
          {{ slotProps.data.registrasi.namaruangan }}
        </template>
      </Column>
      <Column field="created_at" header="Tanggal" :sortable="true">
        <template #body="slotProps">
          <span>{{ H.formatDateToLocalString(slotProps.data.created_at) }}</span>
        </template>
      </Column>
    </DataTable>

  </Dialog>

  <!-- <VModal :open="showModalTemplateFix" title="Template" :noclose="true" size="large" actions="right"
    @close="isAlltemplate = false; showModalTemplateFix = false">
    <template #content>

    </template>
  </VModal> -->


</template>

<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import AutoComplete from 'primevue/autocomplete';
// import Fieldset from 'primevue/fieldset';
// import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'
import * as EMR from '../page-emr-plugins/laporan-operasi'
import * as EMR2 from '../page-emr-plugins/echo-poli-jantung'
import * as EMR3 from '../page-emr-plugins/hasil-pemeriksaan-ekg'
import Fieldset from 'primevue/fieldset'
import Dialog from 'primevue/dialog';
import { FilterMatchMode } from 'primevue/api';
import InputText from 'primevue/inputtext';
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'


const filtersTemplate = ref({
  global: { value: null, matchMode: FilterMatchMode.CONTAINS }
});

let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string

let JenisKelamin = ref(EMR2.JenisKelamin())
let studyType: any = ref(EMR2.studyType())
let resultTransThoracaEchoBayi: any = ref(EMR2.resultTransThoracaEchoBayi())
let resultTransThoracaEchoDewasa: any = ref(EMR2.resultTransThoracaEchoDewasa())
let rightColumnItems: any = ref(EMR2.rightColumnItems())
let leftColumnItems: any = ref(EMR2.leftColumnItems())
let jenisPemeriksaanObgyn: any = ref(EMR2.jenisPemeriksaanObgyn())
let findingTransThoracaEchoDewasa: any = ref(EMR2.findingTransThoracaEchoDewasa())
let formField: any = ref(EMR3.formField())

let laporan = ref(EMR.laporan())

const props = withDefaults(
  defineProps<{
    pasien?: any
    registrasi?: any
    FORM_NAME?: string
    FORM_URL?: string
    COLLECTION?: string
  }>(),
  {
    pasien: {},
    registrasi: {},
    FORM_NAME: '',
    FORM_URL: '',
    COLLECTION: '',
  }
)
const { y } = useWindowScroll()
const isStuck = computed(() => { return y.value > 30 })
const isLoading: any = ref(false)
const d_Pegawai: any = ref([])
const d_Diagnosa: any = ref([])
const isRiwayat: any = ref(true)
const isInputData: any = ref(false)
const listTemplate: any = reactive({})
const d_Dokter = ref([])
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: '',
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  selectedMenu: [false]
})
const strforS: any = ref('');
const strforO: any = ref('');
const dataAsesmen: any = ref({});
const isEcho: any = ref(false)
const isEkg: any = ref(false)
const isGynekologi: any = ref(false)
const isKardiotokografi: any = ref(false)
const isFetal: any = ref(false)
const isObstetri: any = ref(false)
const COLLECTION: any = ref(props.COLLECTION) //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const d_periksadalam: any = ref([{ value: 'Tidak Dilakukan', label: 'Tidak Dilakukan' }, { value: 'Dilakukan', label: 'Dilakukan' }]);
const d_akselerasi: any = ref([{ value: 'Tidak Ada', label: 'Tidak Ada' }, { value: 'Ada', label: 'Ada' }]);
const d_jeniskelamin: any = ref([{ value: 'Laki-Laki', label: 'Laki-Laki' }, { value: 'Perempuan', label: 'Perempuan' }]);
const d_bentuk: any = ref([{ value: 'Normal', label: 'Normal' }, { value: 'Abnormal', label: 'Abnormal' }, { value: 'Tidak dapat ditampakkan', label: 'Tidak dapat ditampakkan' }, { value: 'Opsional', label: 'Opsional' }]);
const d_kondisiteknis: any = ref([{ value: 'Baik', label: 'Baik' }, { value: 'Terbatas', label: 'Terbatas' }]);
const d_janin: any = ref([{ value: 'Tunggal', label: 'Tunggal' }, { value: 'Kembar', label: 'Kembar' }]);
const d_vesicaurinaria: any = ref([{ value: 'Isi Cukup', label: 'Isi Cukup' }, { value: 'Kurang', label: 'Kurang' }]);
const d_djj: any = ref([{ value: '(+)', label: '(+)' }, { value: '(-)', label: '(-)' }]);
const d_menutupi: any = ref([{ value: 'SBR', label: 'SBR' }, { value: 'QUI', label: 'QUI' }]);
const d_cairanaminion: any = ref([{ value: 'Normal', label: 'Normal' }, { value: 'Abnormal', label: 'Abnormal' }]);
const d_plasenta: any = ref([{ value: 'Letak fundus', label: 'Letak fundus' }, { value: 'Anterior', label: 'Anterior' }, { value: 'Posterior', label: 'Posterior' }]);
const d_maturasi: any = ref([{ value: 'Grade 0', label: 'Grade 0' }, { value: 'Grade I', label: 'Grade I' }, { value: 'Grade II', label: 'Grade II' }, { value: 'Grade III', label: 'Grade III' }]);
const d_khorionisitas: any = ref([{ value: 'Presentasi letkep', label: 'Presentasi letkep' }, { value: 'letsu', label: 'letsu' }, { value: 'letli', label: 'letli' }]);
const d_kategori: any = ref([{ value: 'I', label: 'I' }, { value: 'II', label: 'II' }, { value: 'III+', label: 'III+' }]);
const d_yaitu: any = ref([{ value: 'Flat FHR', label: 'Flat FHR' }, { value: 'Blunted Patterns', label: 'Blunted Patterns' }, { value: 'Unstable Baseline', label: 'Unstable Baseline' }, { value: 'Overshoot', label: 'Overshoot' }, { value: 'Sinuisonal Patterns', label: 'Sinuisonal Patterns' }, { value: 'Chermark Patterns', label: 'Chermark Patterns' }]);
const d_beratnya: any = ref([{ value: 'Ringan', label: 'Ringan' }, { value: 'Sedang', label: 'Sedang' }, { value: 'Berat', label: 'Berat' }]);
const d_jenisnya: any = ref([{ value: 'Dini', label: 'Dini' }, { value: 'Lambat', label: 'Lambat' }, { value: 'Variable', label: 'Variable' }, { value: 'Prolonged', label: 'Prolonged' }]);
const d_variabilitas: any = ref([{ value: 'Tidak Ada', label: 'Tidak Ada' }, { value: 'Minimal (1-5 dbm)', label: 'Minimal (1-5 dbm)' }, { value: 'Moderat (5-25 dbm)', label: 'Moderat (5-25 dbm)' }, { value: 'Meningkat (>25 dbm)', label: 'Meningkat (>25 dbm)' }]);
const isDisabled: any = ref(false)
const IDEMR: any = ref('')

const COLLECTION_TransThoracaEchoBayi: any = ref('TransThoracaEchoBayi')
const COLLECTION_TransThoracaEchoDewasa: any = ref('TransThoracaEchoDewasa')
const COLLECTION_LowerExtermityDuplexUltrasoundUSGDoppler: any = ref('LowerExtermityDuplexUltrasoundUSGDoppler')
const COLLECTION_CarotidDuplexUltrasound: any = ref('CarotidDuplexUltrasound')
const COLLECTION_EKG: any = ref('FormulirHasilPemeriksaanEkg')
const COLLECTION_PEMERIKSAAN_KARDIOTOKOGRAFI: any = ref('PemeriksaanKardiotokografi')
const COLLECTION_OBSTETRI: any = ref('PemeriksaanObstetri')
const COLLECTION_THT: any = ref('PemeriksaanTHT')
const COLLECTION_GYNEKOLOGI: any = ref('PemeriksaanGynekologi')
const COLLECTION_FETAL: any = ref('PemeriksaanFetal')
const COLLECTION_EKG_MCU: any = ref('PemeriksaanEkgMcu')
const COLLECTION_UROLOGI: any = ref('PemeriksaanUrologi')
const COLLECTION_BODYPLETHYSMOGRAPH: any = ref('HasilPemeriksaanBodyplethy')
const COLLECTION_DLCO: any = ref('HasilPemeriksaanDLCOBodyplethy')
const COLLECTION_SPIROMETRI: any = ref('HasilPemeriksaanSpirometri')
const COLLECTION_SPRIROMETRI: any = ref('HasilPemeriksaanSpirometri')
const COLLECTION_INTERNA: any = ref('PemeriksaanEkgInterna')
const COLLECTION_EKGPARU: any = ref('PemeriksaanEkgParu')

// 'TransThoracaEchoBayi','TransThoracaEchoDewasa','LowerExtermityDuplexUltrasoundUSGDoppler','CarotidDuplexUltrasound','FormulirHasilPemeriksaanEkg','PemeriksaanKardiotokografi','PemeriksaanObstetri','PemeriksaanTHT','PemeriksaanGynekologi','PemeriksaanFetal','PemeriksaanEkgMcu','PemeriksaanUrologi','HasilPemeriksaanBodyplethy','HasilPemeriksaanDLCOBodyplethy','HasilPemeriksaanSpirometri','HasilPemeriksaanSpirometri','PemeriksaanEkgInterna'

const isRiwayatTransThoracaEchoBayi: any = ref(false)
const isRiwayatTransThoracaEchoDewasa: any = ref(false)
const isRiwayatLowerExtermityDuplexUltrasoundUSGDoppler: any = ref(false)
const isRiwayatCarotidDuplexUltrasound: any = ref(false)
const isRiwayatEKG: any = ref(false)
const isRiwayatPemeriksaanKardiotokografi: any = ref(false)
const isRiwayatPemeriksaanObstetri: any = ref(false)
const isRiwayatPemeriksaanTHT: any = ref(false)
const isRiwayatPemeriksaanGynekologi: any = ref(false)
const isRiwayatPemeriksaanFetal: any = ref(false)

const isBodyPlethySmograph: any = ref(false)
const isDlcoBodyPlethy: any = ref(false)
const isSprirometri: any = ref(false)
const isRiwayatBodyPlethySmograph: any = ref(false)
const isRiwayatDlcoBodyPlethy: any = ref(false)
const isRiwayatSprirometri: any = ref(false)
const isEKGParu: any = ref(false)
const isRiwayatEKGParu: any = ref(false)
const userLogin = useUserSession().getUser()
const kelompokUser = userLogin.kelompokUser.kelompokUser
const isResumeMedis: any = ref(false);

const input: any = ref({
  jamStartOperasi: new Date(),
  jamEndOperasi: new Date(),
  tglOperasi: new Date(),
  jamPenulisanLaporan: new Date(),
  studyType: null,
  // tglEksaminasi: new Date(),
  batasnormalkepala: false,
  batasnormalfsk: false,
  batasNormal: false,
  dalamBatasNormalEchoDewasa: false,
  dalamBatasNormalEchoBayi: false,
})
const setView = () => {
  useHead({
    title: 'Penunjang' + ' - ' + import.meta.env.VITE_PROJECT,
  })
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
  useViewWrapper().setFullWidth(true)
}

function riwayatEchoBayi() {
  isRiwayatTransThoracaEchoBayi.value = true
  isRiwayatTransThoracaEchoDewasa.value = false
  isRiwayatLowerExtermityDuplexUltrasoundUSGDoppler.value = false
  isRiwayatCarotidDuplexUltrasound.value = false
  isRiwayatEKG.value = false
  isRiwayatPemeriksaanKardiotokografi.value = false
  isRiwayatPemeriksaanObstetri.value = false
  isRiwayatPemeriksaanTHT.value = false
  isRiwayatPemeriksaanGynekologi.value = false
  isRiwayatPemeriksaanFetal.value = false

  COLLECTION.value = COLLECTION_TransThoracaEchoBayi.value
  loadRiwayat()
}
function riwayatEchoDewasa() {
  isRiwayatTransThoracaEchoBayi.value = false
  isRiwayatTransThoracaEchoDewasa.value = true
  isRiwayatLowerExtermityDuplexUltrasoundUSGDoppler.value = false
  isRiwayatCarotidDuplexUltrasound.value = false
  isRiwayatEKG.value = false
  isRiwayatPemeriksaanKardiotokografi.value = false
  isRiwayatPemeriksaanObstetri.value = false
  isRiwayatPemeriksaanTHT.value = false
  isRiwayatPemeriksaanGynekologi.value = false
  isRiwayatPemeriksaanFetal.value = false

  COLLECTION.value = COLLECTION_TransThoracaEchoDewasa.value
  loadRiwayat()
}

function riwayatLowerExtermityDuplexUltrasoundUSGDoppler() {
  isRiwayatTransThoracaEchoBayi.value = false
  isRiwayatTransThoracaEchoDewasa.value = false
  isRiwayatLowerExtermityDuplexUltrasoundUSGDoppler.value = true
  isRiwayatCarotidDuplexUltrasound.value = false
  isRiwayatEKG.value = false
  isRiwayatPemeriksaanKardiotokografi.value = false
  isRiwayatPemeriksaanObstetri.value = false
  isRiwayatPemeriksaanTHT.value = false
  isRiwayatPemeriksaanGynekologi.value = false
  isRiwayatPemeriksaanFetal.value = false

  COLLECTION.value = COLLECTION_LowerExtermityDuplexUltrasoundUSGDoppler.value
  loadRiwayat()
}

function riwayatCarotidDuplexUltrasound() {
  isRiwayatTransThoracaEchoBayi.value = false
  isRiwayatTransThoracaEchoDewasa.value = false
  isRiwayatLowerExtermityDuplexUltrasoundUSGDoppler.value = false
  isRiwayatCarotidDuplexUltrasound.value = true
  isRiwayatEKG.value = false
  isRiwayatPemeriksaanKardiotokografi.value = false
  isRiwayatPemeriksaanObstetri.value = false
  isRiwayatPemeriksaanTHT.value = false
  isRiwayatPemeriksaanGynekologi.value = false
  isRiwayatPemeriksaanFetal.value = false

  COLLECTION.value = COLLECTION_CarotidDuplexUltrasound.value
  loadRiwayat()
}

function riwayatEKG() {
  isRiwayatTransThoracaEchoBayi.value = false
  isRiwayatTransThoracaEchoDewasa.value = false
  isRiwayatLowerExtermityDuplexUltrasoundUSGDoppler.value = false
  isRiwayatCarotidDuplexUltrasound.value = false
  isRiwayatEKG.value = true
  isRiwayatPemeriksaanKardiotokografi.value = false
  isRiwayatPemeriksaanObstetri.value = false
  isRiwayatPemeriksaanTHT.value = false
  isRiwayatPemeriksaanGynekologi.value = false
  isRiwayatPemeriksaanFetal.value = false

  COLLECTION.value = COLLECTION_EKG.value
  loadRiwayat()
}

function riwayatPemeriksaanKardiotokografi() {
  isRiwayatTransThoracaEchoBayi.value = false
  isRiwayatTransThoracaEchoDewasa.value = false
  isRiwayatLowerExtermityDuplexUltrasoundUSGDoppler.value = false
  isRiwayatCarotidDuplexUltrasound.value = false
  isRiwayatEKG.value = false
  isRiwayatPemeriksaanKardiotokografi.value = true
  isRiwayatPemeriksaanObstetri.value = false
  isRiwayatPemeriksaanTHT.value = false
  isRiwayatPemeriksaanGynekologi.value = false
  isRiwayatPemeriksaanFetal.value = false

  COLLECTION.value = COLLECTION_PEMERIKSAAN_KARDIOTOKOGRAFI.value
  loadRiwayat()
}

function riwayatPemeriksaanObstetri() {
  isRiwayatTransThoracaEchoBayi.value = false
  isRiwayatTransThoracaEchoDewasa.value = false
  isRiwayatLowerExtermityDuplexUltrasoundUSGDoppler.value = false
  isRiwayatCarotidDuplexUltrasound.value = false
  isRiwayatEKG.value = false
  isRiwayatPemeriksaanKardiotokografi.value = false
  isRiwayatPemeriksaanObstetri.value = true
  isRiwayatPemeriksaanTHT.value = false
  isRiwayatPemeriksaanGynekologi.value = false
  isRiwayatPemeriksaanFetal.value = false

  COLLECTION.value = COLLECTION_OBSTETRI.value
  loadRiwayat()
}

function riwayatPemeriksaanGynekologi() {
  isRiwayatTransThoracaEchoBayi.value = false
  isRiwayatTransThoracaEchoDewasa.value = false
  isRiwayatLowerExtermityDuplexUltrasoundUSGDoppler.value = false
  isRiwayatCarotidDuplexUltrasound.value = false
  isRiwayatEKG.value = false
  isRiwayatPemeriksaanKardiotokografi.value = false
  isRiwayatPemeriksaanObstetri.value = false
  isRiwayatPemeriksaanTHT.value = false
  isRiwayatPemeriksaanGynekologi.value = true
  isRiwayatPemeriksaanFetal.value = false

  COLLECTION.value = COLLECTION_GYNEKOLOGI.value
  loadRiwayat()
}

function riwayatPemeriksaanFetal() {
  isRiwayatTransThoracaEchoBayi.value = false
  isRiwayatTransThoracaEchoDewasa.value = false
  isRiwayatLowerExtermityDuplexUltrasoundUSGDoppler.value = false
  isRiwayatCarotidDuplexUltrasound.value = false
  isRiwayatEKG.value = false
  isRiwayatPemeriksaanKardiotokografi.value = false
  isRiwayatPemeriksaanObstetri.value = false
  isRiwayatPemeriksaanTHT.value = false
  isRiwayatPemeriksaanGynekologi.value = false
  isRiwayatPemeriksaanFetal.value = true

  COLLECTION.value = COLLECTION_FETAL.value
  loadRiwayat()
}

function echo() {
  isEcho.value = true
  isEkg.value = false
}

function ekg() {
  isEcho.value = false
  isEkg.value = true

  COLLECTION.value = COLLECTION_EKG.value
  loadRiwayat()
}

function kardiotokografi() {
  resetTabValues()
  input.value.jenistindakan = 'Pemeriksaan Kardiotokografi'
  isKardiotokografi.value = true
  isGynekologi.value = false
  isFetal.value = false
  isObstetri.value = false
}

function gynekologi() {
  resetTabValues()
  input.value.jenistindakan = 'Pemeriksaan USG Gynekologi'
  isKardiotokografi.value = false
  isGynekologi.value = true
  isFetal.value = false
  isObstetri.value = false
}

function fetal() {
  resetTabValues()
  input.value.jenistindakan = 'Pemeriksaan USG Fetal'
  isKardiotokografi.value = false
  isGynekologi.value = false
  isFetal.value = true
  isObstetri.value = false

  COLLECTION.value = COLLECTION_FETAL.value
  console.log(COLLECTION.value)
}

function obstetri() {
  resetTabValues()
  input.value.jenistindakan = 'Pemeriksaan USG Obstetri'
  isKardiotokografi.value = false
  isGynekologi.value = false
  isFetal.value = false
  isObstetri.value = true
}

function resetTabValues() {
  isBodyPlethySmograph.value = false;
  isDlcoBodyPlethy.value = false;
  isSprirometri.value = false;

  isKardiotokografi.value = false
  isGynekologi.value = false
  isFetal.value = false
  isObstetri.value = false
}

function Bodyplethysmograph() {
  resetTabValues()
  // resetInput()
  isBodyPlethySmograph.value = true
  isRiwayatBodyPlethySmograph = true
  COLLECTION.value = COLLECTION_BODYPLETHYSMOGRAPH.value
  loadRiwayat()
}

function dlcoBodyPlethy() {
  resetTabValues()
  isDlcoBodyPlethy.value = true
  isRiwayatDlcoBodyPlethy.value = true
  COLLECTION.value = COLLECTION_DLCO.value
  loadRiwayat()
}

function sprirometri() {
  resetTabValues()
  isSprirometri.value = true;
  isRiwayatSprirometri.value = true
  COLLECTION.value = COLLECTION_SPRIROMETRI.value
  loadRiwayat()
}

function ekgParu() {
  resetTabValues()
  isEKGParu.value = true;
  isRiwayatEKGParu.value = true
  COLLECTION.value = COLLECTION_EKGPARU.value
  loadRiwayat()
}

function riwayatBodyPlethy() {
  isRiwayatBodyPlethySmograph.value = true
  isRiwayatDlcoBodyPlethy.value = false
  isRiwayatSprirometri.value = false

  COLLECTION.value = COLLECTION_BODYPLETHYSMOGRAPH.value
  loadRiwayat()
}

function riwayatDlcoBodyPlethy() {
  isRiwayatBodyPlethySmograph.value = false
  isRiwayatDlcoBodyPlethy.value = true
  isRiwayatSprirometri.value = false

  COLLECTION.value = COLLECTION_DLCO.value
  loadRiwayat()
}

function riwayatSprirometri() {
  isRiwayatBodyPlethySmograph.value = false
  isRiwayatDlcoBodyPlethy.value = false
  isRiwayatSprirometri.value = true

  COLLECTION.value = COLLECTION_SPRIROMETRI.value
  loadRiwayat()
}

function editData(dt: any) {
  console.log("DATA EDIT ", JSON.stringify(dt, null, 2));
  isInputData.value = true, isRiwayat.value = false, disabledSave.value = false
  input.value = dt;
  // delete input.value.pasien
  // delete input.value.registrasi
}

const fetchPegawai = async (filter: any) => {
  const response = await useApi().get(
    `/emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`)
  d_Pegawai.value = response
}
const fetchDiagnosa = async (filter: any) => {
  const response = await useApi().get(
    `/emr/dropdown/diagnosa_m?select=kddiagnosa,namadiagnosa&param_search=kddiagnosa&query=${filter.query}&limit=10`)
  d_Diagnosa.value = response
}

const fetchDokter = async (filter: any) => {
  let query = ''
  if (filter) {
    query = filter.query
  }
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
  ).then((response) => {
    d_Dokter.value = response
  })
}

const updateCollection = (value: any) => {
  switch (value) {
    case 'TransThoracaEchoBayi':
      COLLECTION.value = COLLECTION_TransThoracaEchoBayi.value
      break
    case 'TransThoracaEchoDewasa':
      COLLECTION.value = COLLECTION_TransThoracaEchoDewasa.value
      break
    case 'LowerExtermityDuplexUltrasoundUSGDoppler':
      COLLECTION.value = COLLECTION_LowerExtermityDuplexUltrasoundUSGDoppler.value
      break
    case 'CarotidDuplexUltrasound':
      COLLECTION.value = COLLECTION_CarotidDuplexUltrasound.value
      break
    default:
      COLLECTION.value = ''
      break
  }
  loadRiwayat()
}

const printByRoomType = (emrId, collection) => {
  H.printBlade(`emr/cetak-detail/${collection}?id=${emrId}&pdf=true`);
};

function normalEKG() {
  if (input.value.batasnormalkepala == false) {
    input.value.Pwave = "Normal"
    input.value.HR = "75 x/m"
    input.value.PRInterval = "Normal"
    input.value.Axis = "Normal"
    input.value.QRS = "<0.12 dtk"
    input.value.Other = "None"
    input.value.STSegment = "Normal"
    input.value.Twave = "Normal"
    input.value.QTInterval = "Normal"
  } else {
    input.value.Pwave = undefined
    input.value.HR = undefined
    input.value.PRInterval = undefined
    input.value.Axis = undefined
    input.value.QRS = undefined
    input.value.Other = undefined
    input.value.STSegment = undefined
    input.value.Twave = undefined
    input.value.QTInterval = undefined
  }
}

function normalEchoBayi() {
  if (input.value.dalamBatasNormalEchoBayi == false) {
    input.value.finding = "Atrial situs solitus, normal systemic & pulmonal veins drain, AV-VA concordant, balanced 4 chambers, LA/Ao mm, no PFO, No ASD, no VSD, No PDA, no CoA, left Ao arch,  no pericardial effusion, normal systolic LV function (EF %)"
    input.value.conclusion = "Normal Systolic LV Function"
  } else {
    input.value.finding = undefined
    input.value.conclusion = undefined
  }
}

function normalEchoDewasa() {
  if (input.value.dalamBatasNormalEchoDewasa == false) {
    input.value.cardiacChamber = "Normal"
    input.value.lvh = "None"
    input.value.systolicLv = "Normal"
    input.value.diastolicLv = "Normal"
    input.value.heartWave = "Normal"
    input.value.aorticValve = "Normal"
    input.value.mitralValve = "Normal"
    input.value.tricuspidValve = "Normal"
    input.value.pulmonaryValve = "Normal"
    input.value.rvContractility = "Normal"
    input.value.lvWallMotion = "Global Normokinetic"
    input.value.conclusion = "Normal LV Systolic Function and RV Contractility"
    input.value.pericardium = "Normal"
  } else {
    input.value.cardiacChamber = undefined
    input.value.lvh = undefined
    input.value.systolicLv = undefined
    input.value.diastolicLv = undefined
    input.value.heartWave = undefined
    input.value.aorticValve = undefined
    input.value.mitralValve = undefined
    input.value.tricuspidValve = undefined
    input.value.pulmonaryValve = undefined
    input.value.rvContractility = undefined
    input.value.lvWallMotion = undefined
    input.value.conclusion = undefined
    input.value.pericardium = undefined
  }
}

function normalthtkiri(isnorm) {
  if (isnorm == 'Dalam Batas Normal') {
    input.value.keteranganTelingaKiri = 'Normal';
    input.value.acTelingaKiri = '0 - 20'
    input.value.bcTelingaKiri = '0 - 20'
  } else {
    input.value.keteranganTelingaKiri = '';
    input.value.acTelingaKiri = ''
    input.value.bcTelingaKiri = ''
  }
}

function normalthtkanan(isnorm) {
  if (isnorm == 'Dalam Batas Normal') {
    input.value.keteranganTelingaKanan = 'Normal';
    input.value.acTelingakanan = '0 - 20'
    input.value.bcTelingakanan = '0 - 20'
  } else {
    input.value.keteranganTelingaKanan = '';
    input.value.acTelingakanan = ''
    input.value.bcTelingakanan = ''
  }
}


function normalEKGJantung() {
  if (input.value.batasNormal == false) {
    input.value.PWave = "Normal"
    input.value.PRInterval = "Normal"
    input.value.QRS = "<0.12 dtk"
    input.value.STSegment = "Normal"
    input.value.QTInterval = "Normal"
    input.value.HR = "75 x/m"
    input.value.Other = "None"
    input.value.Axis = "Normal"
    input.value.TWave = "Normal"
  } else {
    input.value.PWave = undefined
    input.value.PRInterval = undefined
    input.value.QRS = undefined
    input.value.STSegment = undefined
    input.value.QTInterval = undefined
    input.value.HR = undefined
    input.value.Other = undefined
    input.value.Axis = undefined
    input.value.TWave = undefined
  }
}

function batasnormalfisik() {
  console.log(input.value.batasnormalfsk)
  if (input.value.batasnormalfsk == false) {
    let d = input.value
    d.bentukkepala = "Normal"
    d.ventrikellateral = "Normal"
    d.cavumseptum = "Normal"
    d.cerebellum = "Normal"
    d.falx = "Normal"
    d.cistema = "Normal"
    d.thalamus = "Normal"
    d.bibiratas = "Normal"
    d.hidung = "Normal"
    d.mediana = "Normal"
    d.lubanghidung = "Normal"
    d.orbita = "Normal"
    d.leher = "Normal"
    d.bentukThorax = "Normal"
    d.tidakTampakMassa = "Normal"
    d.aktifitasJantung = "Normal"
    d.faur = "Normal"
    d.ukuranJantung = "Normal"
    d.leftVerticular = "Normal"
    d.axisJantung = "Normal"
    d.rightVerticular = "Normal"
    d.gaster = "Normal"
    d.vesicaUrinaria = "Normal"
    d.usus = "Normal"
    d.insersiUmbilicilus = "Normal"
    d.ginjal = "Normal"
    d.vescularUmbilicilus = "Normal"
    d.vertebra = "Normal"
    d.eksTanganKanan = "Normal"
    d.eksTanganKiri = "Normal"
    d.eksKakiKanan = "Normal"
    d.eksKakiKiri = "Normal"
    d.jenisKelamin2 = "Normal"

    // input.value.bentukkepala = 1
    // input.value.ventrikellateral = 1
    // input.value.cavumseptum = 1
    // input.value.cerebellum = 1
    // input.value.falx = 1
    // input.value.cistema = 1
    // input.value.thalamus = 1
    // input.value.bibiratas = 1
    // input.value.hidung = 1
    // input.value.mediana = 1
    // input.value.lubanghidung = 1
    // input.value.orbita = 1
    // input.value.leher = 1
    // input.value.bentukThorax = 1
    // input.value.tidakTampakMassa = 1
    // input.value.aktifitasJantung = 1
    // input.value.faur = 1
    // input.value.ukuranJantung = 1
    // input.value.leftVerticular = 1
    // input.value.axisJantung = 1
    // input.value.rightVerticular = 1
    // input.value.gaster = 1
    // input.value.vesicaUrinaria = 1
    // input.value.usus = 1
    // input.value.insersiUmbilicilus = 1
    // input.value.ginjal = 1
    // input.value.vescularUmbilicilus = 1
    // input.value.vertebra = 1
    // input.value.eksTanganKanan = 1
    // input.value.eksTanganKiri = 1
    // input.value.eksKakiKanan = 1
    // input.value.eksKakiKiri = 1
    // input.value.jenisKelamin2 = 1
  } else {
    input.value.bentukkepala = undefined
    input.value.ventrikellateral = undefined
    input.value.cavumseptum = undefined
    input.value.cerebellum = undefined
    input.value.falx = undefined
    input.value.cistema = undefined
    input.value.thalamus = undefined
    input.value.bibiratas = undefined
    input.value.hidung = undefined
    input.value.mediana = undefined
    input.value.lubanghidung = undefined
    input.value.orbita = undefined
    input.value.leher = undefined
    input.value.bentukThorax = undefined
    input.value.tidakTampakMassa = undefined
    input.value.aktifitasJantung = undefined
    input.value.faur = undefined
    input.value.ukuranJantung = undefined
    input.value.leftVerticular = undefined
    input.value.axisJantung = undefined
    input.value.rightVerticular = undefined
    input.value.gaster = undefined
    input.value.vesicaUrinaria = undefined
    input.value.usus = undefined
    input.value.insersiUmbilicilus = undefined
    input.value.ginjal = undefined
    input.value.vescularUmbilicilus = undefined
    input.value.vertebra = undefined
    input.value.eksTanganKanan = undefined
    input.value.eksTanganKiri = undefined
    input.value.eksKakiKanan = undefined
    input.value.eksKakiKiri = undefined
    input.value.jenisKelamin2 = undefined
  }
}
const disabledSave = ref(false)

const simpanTemplate = (id: string, collection: any, object: any) => {
  if (!input.value.namatemplate || input.value.namatemplate == '') {
    H.alert('warning', 'Nama Template wajib diisi');
    return;
  }
  let ID = idTemplateData.value ? idTemplateData.value : ''

  object.pasien = H.setObjectPasien(props.pasien)
  object.registrasi = H.setObjectRegistrasi(props.registrasi)
  let json = {
    'id': ID,
    'norec_emr': NOREC_EMRPASIEN.value,
    'collection': COLLECTION.value,
    'url_form': props.FORM_URL,
    'name_form': 'Penunjang Khusus',
    'jenis_emr': 'asesmen_medis',
    'data': object
  }
  isLoading.value = true

  useApi().post(
    `/emr/simpan-emr-template`, json).then((response: any) => {
      isLoading.value = false
      input.valuen.namatemplate = null
    }).catch((e: any) => {
      isLoading.value = false
    })
  input.value.namatemplate = ''
}

const listTemplateFix: any = ref([])
const showModalTemplateFix: any = ref(false)
const isAlltemplate: any = ref(false);
const idTemplateData: any = ref('');

const deleteTemplate = (idTemplate) => {
  isLoading.value = true
  let json = {
    'id': idTemplate,
    'collection': COLLECTION.value
  }
  useApi().post(
    `/emr/hapus-template`, json).then((response: any) => {
      if (response.status !== 500) {
        isLoading.value = false
        isAlltemplate.value = false;
        H.alert('sucess', response.message);
        pilihTemplateFix();
      } else {
        H.alert('danger', response.message);
      }
    }).catch((e: any) => {
      isLoading.value = false
      H.alert('danger', e);
    })
}

const editTemplate = async (dt: any) => {
  if (!dt) return;
  console.log("DT EDIT", dt);
  H.alert('info', 'Silahkan ubah data dan Simpan Template Kembali');
  // await addTemplate(dt);
  // input.value.namatemplate = null
  input.value = dt //set ke inputan
  isAlltemplate.value = false;
  idTemplateData.value = dt.id;
  showModalTemplateFix.value = false;
  input.value.namatemplate = dt.namatemplate;
  console.log("ID Template", idTemplateData)
  // showModalTemplateFix.value = false
}

const addTemplate = (response: any) => {
  console.log(response)
  input.value = response //set ke inputan
  input.value.namatemplate = null
  isAlltemplate.value = false;
  delete input.value._id
  delete input.value.pasien
  delete input.value.registrasi
  delete input.value.namaPasien
  delete input.value.tanggalLahirPasien
  delete input.value.jeniskelamin
  delete input.value.norm

  setAutoFill()
  showModalTemplateFix.value = false
  H.alert('success', 'Berhasil di tambahkan');
}

const simpan = () => {
  let ID = input.value.id ? input.value.id : '';
  let object: any = {};
  object = input.value;
  // object.pasien = H.setObjectPasien(props.pasien);
  // object.registrasi = H.setObjectRegistrasi(props.registrasi);
  if (!ID || ID == '' || ID == null) {
    object.pasien = H.setObjectPasien(props.pasien);
    object.registrasi = H.setObjectRegistrasi(props.registrasi);
  } else {
    delete object.pasien;
    delete object.registrasi;
  }
  let selectedCollection = null;
  H.cacheHelper().set('cachePenunjang', undefined);

  switch (props.registrasi.objectruanganlastfk) {

    case 212:
      switch (input.value.studyType) {
        case 'TransThoracaEchoBayi':
          selectedCollection = COLLECTION_TransThoracaEchoBayi.value;
          object.jenistindakan = 'Trans Toracha Echo Bayi';
          break;
        case 'TransThoracaEchoDewasa':
          selectedCollection = COLLECTION_TransThoracaEchoDewasa.value;
          object.jenistindakan = 'Trans Toracha Echo Dewasa';
          break;
        case 'LowerExtermityDuplexUltrasoundUSGDoppler':
          selectedCollection = COLLECTION_LowerExtermityDuplexUltrasoundUSGDoppler.value;
          object.jenistindakan = 'Lower Extermity Duplex Ultrasound USG Doppler';
          break;
        case 'CarotidDuplexUltrasound':
          selectedCollection = COLLECTION_CarotidDuplexUltrasound.value;
          object.jenistindakan = 'Carotid Duplex Ultrasound';
          break;
        case 'EKGJantung':
          selectedCollection = COLLECTION_EKG.value;
          object.jenistindakan = 'EKG Jantung';
          break;
        default:
          break;
      }
      if (selectedCollection) {
        console.log(`Selected Collection for 212: ${selectedCollection}`);
        sendRequest(ID, selectedCollection, object);
      }
      break;

    case 377:
      switch (input.value.studyType) {
        case 'TransThoracaEchoBayi':
          selectedCollection = COLLECTION_TransThoracaEchoBayi.value;
          object.jenistindakan = 'Trans Toracha Echo Bayi';
          break;
        case 'TransThoracaEchoDewasa':
          selectedCollection = COLLECTION_TransThoracaEchoDewasa.value;
          object.jenistindakan = 'Trans Toracha Echo Dewasa';
          break;
        case 'LowerExtermityDuplexUltrasoundUSGDoppler':
          selectedCollection = COLLECTION_LowerExtermityDuplexUltrasoundUSGDoppler.value;
          object.jenistindakan = 'Lower Extermity Duplex Ultrasound USG Doppler';
          break;
        case 'CarotidDuplexUltrasound':
          selectedCollection = COLLECTION_CarotidDuplexUltrasound.value;
          object.jenistindakan = 'Carotid Duplex Ultrasound';
          break;
        default:
          break;
      }
      if (selectedCollection) {
        console.log(`Selected Collection for 212: ${selectedCollection}`);
        sendRequest(ID, selectedCollection, object);
      } else if (selectedCollection === 'FormulirHasilPemeriksaanEkg') {
        object.jenistindakan = 'Pemeriksaan EKG Poli Jantung';
        console.log(`Selected Collection for EKG Poli Jantung: ${COLLECTION_EKG.value}`);
        sendRequest(ID, COLLECTION_EKG.value, object);
      }
      break;
    case 409:
      switch (input.value.studyType) {
        case 'TransThoracaEchoBayi':
          selectedCollection = COLLECTION_TransThoracaEchoBayi.value;
          object.jenistindakan = 'Trans Toracha Echo Bayi';
          break;
        case 'TransThoracaEchoDewasa':
          selectedCollection = COLLECTION_TransThoracaEchoDewasa.value;
          object.jenistindakan = 'Trans Toracha Echo Dewasa';
          break;
        case 'LowerExtermityDuplexUltrasoundUSGDoppler':
          selectedCollection = COLLECTION_LowerExtermityDuplexUltrasoundUSGDoppler.value;
          object.jenistindakan = 'Lower Extermity Duplex Ultrasound USG Doppler';
          break;
        case 'CarotidDuplexUltrasound':
          selectedCollection = COLLECTION_CarotidDuplexUltrasound.value;
          object.jenistindakan = 'Carotid Duplex Ultrasound';
          break;
        default:
          break;
      }
      if (selectedCollection) {
        console.log(`Selected Collection for 212: ${selectedCollection}`);
        sendRequest(ID, selectedCollection, object);
      } else if (selectedCollection === 'FormulirHasilPemeriksaanEkg') {
        object.jenistindakan = 'Pemeriksaan EKG Poli Jantung';
        console.log(`Selected Collection for EKG Poli Jantung: ${COLLECTION_EKG.value}`);
        sendRequest(ID, COLLECTION_EKG.value, object);
      }
      break;
    case 317:
      switch (input.value.studyType) {
        case 'TransThoracaEchoBayi':
          selectedCollection = COLLECTION_TransThoracaEchoBayi.value;
          object.jenistindakan = 'Trans Toracha Echo Bayi';
          break;
        case 'TransThoracaEchoDewasa':
          selectedCollection = COLLECTION_TransThoracaEchoDewasa.value;
          object.jenistindakan = 'Trans Toracha Echo Dewasa';
          break;
        case 'LowerExtermityDuplexUltrasoundUSGDoppler':
          selectedCollection = COLLECTION_LowerExtermityDuplexUltrasoundUSGDoppler.value;
          object.jenistindakan = 'Lower Extermity Duplex Ultrasound USG Doppler';
          break;
        case 'CarotidDuplexUltrasound':
          selectedCollection = COLLECTION_CarotidDuplexUltrasound.value;
          object.jenistindakan = 'Carotid Duplex Ultrasound';
          break;
        case 'EKGJantung':
          selectedCollection = COLLECTION_EKG.value;
          object.jenistindakan = 'EKG Jantung';
          break;
        default:
          break;
      }
      if (selectedCollection) {
        console.log(`Selected Collection for 212: ${selectedCollection}`);
        sendRequest(ID, selectedCollection, object);
      } else if (selectedCollection === 'FormulirHasilPemeriksaanEkg') {
        object.jenistindakan = 'Pemeriksaan EKG Poli Jantung';
        console.log(`Selected Collection for EKG Poli Jantung: ${COLLECTION_EKG.value}`);
        sendRequest(ID, COLLECTION_EKG.value, object);
      }
      break;
    case 318:
      switch (input.value.studyType) {
        case 'TransThoracaEchoBayi':
          selectedCollection = COLLECTION_TransThoracaEchoBayi.value;
          object.jenistindakan = 'Trans Toracha Echo Bayi';
          break;
        case 'TransThoracaEchoDewasa':
          selectedCollection = COLLECTION_TransThoracaEchoDewasa.value;
          object.jenistindakan = 'Trans Toracha Echo Dewasa';
          break;
        case 'LowerExtermityDuplexUltrasoundUSGDoppler':
          selectedCollection = COLLECTION_LowerExtermityDuplexUltrasoundUSGDoppler.value;
          object.jenistindakan = 'Lower Extermity Duplex Ultrasound USG Doppler';
          break;
        case 'CarotidDuplexUltrasound':
          selectedCollection = COLLECTION_CarotidDuplexUltrasound.value;
          object.jenistindakan = 'Carotid Duplex Ultrasound';
          break;
        default:
          break;
      }
      if (selectedCollection) {
        console.log(`Selected Collection for 212: ${selectedCollection}`);
        sendRequest(ID, selectedCollection, object);
      } else if (selectedCollection === 'FormulirHasilPemeriksaanEkg') {
        object.jenistindakan = 'Pemeriksaan EKG Poli Jantung';
        console.log(`Selected Collection for EKG Poli Jantung: ${COLLECTION_EKG.value}`);
        sendRequest(ID, COLLECTION_EKG.value, object);
      }
      break;
    case 319:
      switch (input.value.studyType) {
        case 'TransThoracaEchoBayi':
          selectedCollection = COLLECTION_TransThoracaEchoBayi.value;
          object.jenistindakan = 'Trans Toracha Echo Bayi';
          break;
        case 'TransThoracaEchoDewasa':
          selectedCollection = COLLECTION_TransThoracaEchoDewasa.value;
          object.jenistindakan = 'Trans Toracha Echo Dewasa';
          break;
        case 'LowerExtermityDuplexUltrasoundUSGDoppler':
          selectedCollection = COLLECTION_LowerExtermityDuplexUltrasoundUSGDoppler.value;
          object.jenistindakan = 'Lower Extermity Duplex Ultrasound USG Doppler';
          break;
        case 'CarotidDuplexUltrasound':
          selectedCollection = COLLECTION_CarotidDuplexUltrasound.value;
          object.jenistindakan = 'Carotid Duplex Ultrasound';
          break;
        case 'EKGJantung':
          selectedCollection = COLLECTION_EKG.value;
          object.jenistindakan = 'EKG Jantung';
          break;
        default:
          break;
      }
      if (selectedCollection) {
        console.log(`Selected Collection for 212: ${selectedCollection}`);
        sendRequest(ID, selectedCollection, object);
      } else if (selectedCollection === 'FormulirHasilPemeriksaanEkg') {
        object.jenistindakan = 'Pemeriksaan EKG Poli Jantung';
        console.log(`Selected Collection for EKG Poli Jantung: ${COLLECTION_EKG.value}`);
        sendRequest(ID, COLLECTION_EKG.value, object);
      }
      break;
    case 320:
      switch (input.value.studyType) {
        case 'TransThoracaEchoBayi':
          selectedCollection = COLLECTION_TransThoracaEchoBayi.value;
          object.jenistindakan = 'Trans Toracha Echo Bayi';
          break;
        case 'TransThoracaEchoDewasa':
          selectedCollection = COLLECTION_TransThoracaEchoDewasa.value;
          object.jenistindakan = 'Trans Toracha Echo Dewasa';
          break;
        case 'LowerExtermityDuplexUltrasoundUSGDoppler':
          selectedCollection = COLLECTION_LowerExtermityDuplexUltrasoundUSGDoppler.value;
          object.jenistindakan = 'Lower Extermity Duplex Ultrasound USG Doppler';
          break;
        case 'CarotidDuplexUltrasound':
          selectedCollection = COLLECTION_CarotidDuplexUltrasound.value;
          object.jenistindakan = 'Carotid Duplex Ultrasound';
          break;
        default:
          break;
      }
      if (selectedCollection) {
        console.log(`Selected Collection for 212: ${selectedCollection}`);
        sendRequest(ID, selectedCollection, object);
      } else if (selectedCollection === 'FormulirHasilPemeriksaanEkg') {
        object.jenistindakan = 'Pemeriksaan EKG Poli Jantung';
        console.log(`Selected Collection for EKG Poli Jantung: ${COLLECTION_EKG.value}`);
        sendRequest(ID, COLLECTION_EKG.value, object);
      }
      break;
    case 303:
      switch (input.value.studyType) {
        case 'TransThoracaEchoBayi':
          selectedCollection = COLLECTION_TransThoracaEchoBayi.value;
          object.jenistindakan = 'Trans Toracha Echo Bayi';
          break;
        case 'TransThoracaEchoDewasa':
          selectedCollection = COLLECTION_TransThoracaEchoDewasa.value;
          object.jenistindakan = 'Trans Toracha Echo Dewasa';
          break;
        case 'LowerExtermityDuplexUltrasoundUSGDoppler':
          selectedCollection = COLLECTION_LowerExtermityDuplexUltrasoundUSGDoppler.value;
          object.jenistindakan = 'Lower Extermity Duplex Ultrasound USG Doppler';
          break;
        case 'CarotidDuplexUltrasound':
          selectedCollection = COLLECTION_CarotidDuplexUltrasound.value;
          object.jenistindakan = 'Carotid Duplex Ultrasound';
          break;
        case 'EKGJantung':
          selectedCollection = COLLECTION_EKG.value;
          object.jenistindakan = 'EKG Jantung';
          break;
        default:
          break;
      }
      if (selectedCollection) {
        console.log(`Selected Collection for 212: ${selectedCollection}`);
        sendRequest(ID, selectedCollection, object);
      } else if (selectedCollection === 'FormulirHasilPemeriksaanEkg') {
        object.jenistindakan = 'Pemeriksaan EKG Poli Jantung';
        console.log(`Selected Collection for EKG Poli Jantung: ${COLLECTION_EKG.value}`);
        sendRequest(ID, COLLECTION_EKG.value, object);
      }
      break;
    case 202:
      switch (input.value.jenisPemeriksaanObgyn) {
        case 'PemeriksaanKardiotokografi':
          object.jenistindakan = 'Pemeriksaan Kardiotokografi';
          selectedCollection = COLLECTION_PEMERIKSAAN_KARDIOTOKOGRAFI.value;
          // ID = '';
          ID = input.value.id ? input.value.id : '';
          break;
        case 'PemeriksaanUSGObstetri':
          object.jenistindakan = 'Pemeriksaan USG Obstetri';
          selectedCollection = COLLECTION_OBSTETRI.value;
          // ID = '';
          ID = input.value.id ? input.value.id : '';
          break;
        case 'PemeriksaanUSGGynekologi':
          object.jenistindakan = 'Pemeriksaan USG Gynekologi';
          selectedCollection = COLLECTION_GYNEKOLOGI.value;
          // ID = '';
          ID = input.value.id ? input.value.id : '';
          break;
        case 'PemeriksaanUSGFetal':
          object.jenistindakan = 'Pemeriksaan USG Fetal';
          selectedCollection = COLLECTION_FETAL.value;
          // ID = '';
          ID = input.value.id ? input.value.id : '';
          break;
        default:
          break;
      }
      if (selectedCollection) {
        console.log(`Selected Collection for 202: ${selectedCollection}`);
        sendRequest(ID, selectedCollection, object);
      }
      break;
    case 314:
      switch (input.value.jenisPemeriksaanObgyn) {
        case 'PemeriksaanKardiotokografi':
          object.jenistindakan = 'Pemeriksaan Kardiotokografi';
          selectedCollection = COLLECTION_PEMERIKSAAN_KARDIOTOKOGRAFI.value;
          // ID = '';
          ID = input.value.id ? input.value.id : '';
          break;
        case 'PemeriksaanUSGObstetri':
          object.jenistindakan = 'Pemeriksaan USG Obstetri';
          selectedCollection = COLLECTION_OBSTETRI.value;
          // ID = '';
          ID = input.value.id ? input.value.id : '';
          break;
        case 'PemeriksaanUSGGynekologi':
          object.jenistindakan = 'Pemeriksaan USG Gynekologi';
          selectedCollection = COLLECTION_GYNEKOLOGI.value;
          // ID = '';
          ID = input.value.id ? input.value.id : '';
          break;
        case 'PemeriksaanUSGFetal':
          object.jenistindakan = 'Pemeriksaan USG Fetal';
          selectedCollection = COLLECTION_FETAL.value;
          // ID = '';
          ID = input.value.id ? input.value.id : '';
          break;
        default:
          break;
      }
      if (selectedCollection) {
        console.log(`Selected Collection for 202: ${selectedCollection}`);
        sendRequest(ID, selectedCollection, object);
      }
      break;
    case 310:
      switch (input.value.jenisPemeriksaanObgyn) {
        case 'PemeriksaanKardiotokografi':
          object.jenistindakan = 'Pemeriksaan Kardiotokografi';
          selectedCollection = COLLECTION_PEMERIKSAAN_KARDIOTOKOGRAFI.value;
          // ID = '';
          ID = input.value.id ? input.value.id : '';
          break;
        case 'PemeriksaanUSGObstetri':
          object.jenistindakan = 'Pemeriksaan USG Obstetri';
          selectedCollection = COLLECTION_OBSTETRI.value;
          // ID = '';
          ID = input.value.id ? input.value.id : '';
          break;
        case 'PemeriksaanUSGGynekologi':
          object.jenistindakan = 'Pemeriksaan USG Gynekologi';
          selectedCollection = COLLECTION_GYNEKOLOGI.value;
          // ID = '';
          ID = input.value.id ? input.value.id : '';
          break;
        case 'PemeriksaanUSGFetal':
          object.jenistindakan = 'Pemeriksaan USG Fetal';
          selectedCollection = COLLECTION_FETAL.value;
          // ID = '';
          ID = input.value.id ? input.value.id : '';
          break;
        default:
          break;
      }
      if (selectedCollection) {
        console.log(`Selected Collection for 202: ${selectedCollection}`);
        sendRequest(ID, selectedCollection, object);
      }
      break;
    case 323:
      switch (input.value.jenisPemeriksaanObgyn) {
        case 'PemeriksaanKardiotokografi':
          object.jenistindakan = 'Pemeriksaan Kardiotokografi';
          selectedCollection = COLLECTION_PEMERIKSAAN_KARDIOTOKOGRAFI.value;
          // ID = '';
          ID = input.value.id ? input.value.id : '';
          break;
        case 'PemeriksaanUSGObstetri':
          object.jenistindakan = 'Pemeriksaan USG Obstetri';
          selectedCollection = COLLECTION_OBSTETRI.value;
          // ID = '';
          ID = input.value.id ? input.value.id : '';
          break;
        case 'PemeriksaanUSGGynekologi':
          object.jenistindakan = 'Pemeriksaan USG Gynekologi';
          selectedCollection = COLLECTION_GYNEKOLOGI.value;
          // ID = '';
          ID = input.value.id ? input.value.id : '';
          break;
        case 'PemeriksaanUSGFetal':
          object.jenistindakan = 'Pemeriksaan USG Fetal';
          selectedCollection = COLLECTION_FETAL.value;
          // ID = '';
          ID = input.value.id ? input.value.id : '';
          break;
        default:
          break;
      }
      if (selectedCollection) {
        console.log(`Selected Collection for 202: ${selectedCollection}`);
        sendRequest(ID, selectedCollection, object);
      }
      break;
    case 370:
      switch (input.value.jenisPemeriksaanObgyn) {
        case 'PemeriksaanKardiotokografi':
          object.jenistindakan = 'Pemeriksaan Kardiotokografi';
          selectedCollection = COLLECTION_PEMERIKSAAN_KARDIOTOKOGRAFI.value;
          // ID = '';
          ID = input.value.id ? input.value.id : '';
          break;
        case 'PemeriksaanUSGObstetri':
          object.jenistindakan = 'Pemeriksaan USG Obstetri';
          selectedCollection = COLLECTION_OBSTETRI.value;
          // ID = '';
          ID = input.value.id ? input.value.id : '';
          break;
        case 'PemeriksaanUSGGynekologi':
          object.jenistindakan = 'Pemeriksaan USG Gynekologi';
          selectedCollection = COLLECTION_GYNEKOLOGI.value;
          // ID = '';
          ID = input.value.id ? input.value.id : '';
          break;
        case 'PemeriksaanUSGFetal':
          object.jenistindakan = 'Pemeriksaan USG Fetal';
          selectedCollection = COLLECTION_FETAL.value;
          // ID = '';
          ID = input.value.id ? input.value.id : '';
          break;
        default:
          break;
      }
      if (selectedCollection) {
        console.log(`Selected Collection for 202: ${selectedCollection}`);
        sendRequest(ID, selectedCollection, object);
      }
      break;
    case 402:
      switch (input.value.jenisPemeriksaanObgyn) {
        case 'PemeriksaanKardiotokografi':
          object.jenistindakan = 'Pemeriksaan Kardiotokografi';
          selectedCollection = COLLECTION_PEMERIKSAAN_KARDIOTOKOGRAFI.value;
          // ID = '';
          ID = input.value.id ? input.value.id : '';
          break;
        case 'PemeriksaanUSGObstetri':
          object.jenistindakan = 'Pemeriksaan USG Obstetri';
          selectedCollection = COLLECTION_OBSTETRI.value;
          // ID = '';
          ID = input.value.id ? input.value.id : '';
          break;
        case 'PemeriksaanUSGGynekologi':
          object.jenistindakan = 'Pemeriksaan USG Gynekologi';
          selectedCollection = COLLECTION_GYNEKOLOGI.value;
          // ID = '';
          ID = input.value.id ? input.value.id : '';
          break;
        case 'PemeriksaanUSGFetal':
          object.jenistindakan = 'Pemeriksaan USG Fetal';
          selectedCollection = COLLECTION_FETAL.value;
          // ID = '';
          ID = input.value.id ? input.value.id : '';
          break;
        default:
          break;
      }
      if (selectedCollection) {
        console.log(`Selected Collection for 202: ${selectedCollection}`);
        sendRequest(ID, selectedCollection, object);
      }
      break;

    case 204:
      selectedCollection = COLLECTION_THT.value;
      console.log(`Selected Collection for THT: ${selectedCollection}`);
      ID = '';
      sendRequest(ID, selectedCollection, object);
      break;
    case 378:
      selectedCollection = COLLECTION_THT.value;
      console.log(`Selected Collection for THT: ${selectedCollection}`);
      ID = '';
      sendRequest(ID, selectedCollection, object);
      break;
    case 404:
      selectedCollection = COLLECTION_THT.value;
      console.log(`Selected Collection for THT: ${selectedCollection}`);
      ID = '';
      sendRequest(ID, selectedCollection, object);
      break;

    case 201:
      selectedCollection = COLLECTION_INTERNA.value;
      object.jenistindakan = 'Tindakan EKG Poli Interna';
      console.log(`Selected Collection for Interna: ${selectedCollection}`);
      sendRequest(ID, selectedCollection, object);
      break;
    case 365:
      selectedCollection = COLLECTION_INTERNA.value;
      object.jenistindakan = 'Tindakan EKG Poli Interna';
      console.log(`Selected Collection for Interna: ${selectedCollection}`);
      sendRequest(ID, selectedCollection, object);
      break;
    case 401:
      selectedCollection = COLLECTION_INTERNA.value;
      object.jenistindakan = 'Tindakan EKG Poli Interna';
      console.log(`Selected Collection for Interna: ${selectedCollection}`);
      sendRequest(ID, selectedCollection, object);
      break;
    case 217:
      selectedCollection = COLLECTION_EKG_MCU.value;
      object.jenistindakan = 'Tindakan EKG Poli MCU';
      console.log(`Selected Collection for MCU: ${selectedCollection}`);
      sendRequest(ID, selectedCollection, object);
      break;

    case 224:
      selectedCollection = COLLECTION_UROLOGI.value;
      object.jenistindakan = 'Tindakan EKG Poli USG Urologi';
      console.log(`Selected Collection for Urologi: ${selectedCollection}`);
      ID = '';
      sendRequest(ID, selectedCollection, object);
      break;
    case 375:
      selectedCollection = COLLECTION_UROLOGI.value;
      object.jenistindakan = 'Tindakan EKG Poli USG Urologi';
      console.log(`Selected Collection for Urologi: ${selectedCollection}`);
      ID = '';
      sendRequest(ID, selectedCollection, object);
      break;

    case 206:
      switch (true) {
        case isBodyPlethySmograph.value:
          selectedCollection = COLLECTION_BODYPLETHYSMOGRAPH.value;
          object.jenistindakan = 'Body Plethysmograph';
          break;
        case isDlcoBodyPlethy.value:
          selectedCollection = COLLECTION_DLCO.value;
          object.jenistindakan = 'Dlco Body Plethysmograph';
          break;
        case isSprirometri.value:
          selectedCollection = COLLECTION_SPIROMETRI.value;
          object.jenistindakan = 'Spirometri';
          break;
        case isEKGParu.value:
          selectedCollection = COLLECTION_EKGPARU.value;
          object.jenistindakan = 'EKG Paru';
          break;
        default:
          break;
      }
      if (selectedCollection) {
        console.log(`Selected Collection for 206: ${selectedCollection}`);
        sendRequest(ID, selectedCollection, object);
      }
      break;
    case 382:
      switch (true) {
        case isBodyPlethySmograph.value:
          selectedCollection = COLLECTION_BODYPLETHYSMOGRAPH.value;
          object.jenistindakan = 'Body Plethysmograph';
          break;
        case isDlcoBodyPlethy.value:
          selectedCollection = COLLECTION_DLCO.value;
          object.jenistindakan = 'Dlco Body Plethysmograph';
          break;
        case isSprirometri.value:
          selectedCollection = COLLECTION_SPIROMETRI.value;
          object.jenistindakan = 'Spirometri';
          break;
        case isEKGParu.value:
          selectedCollection = COLLECTION_EKGPARU.value;
          object.jenistindakan = 'EKG Paru';
          break;
        default:
          break;
      }
      if (selectedCollection) {
        console.log(`Selected Collection for 206: ${selectedCollection}`);
        sendRequest(ID, selectedCollection, object);
      }
      break;
    default:
      object.jenistindakan = 'Pemeriksaan EKG Poli Jantung';
      break;
  }
};

const updateTemplate = () => {
  let ID = input.value.id ? input.value.id : '';
  let object: any = {};
  object = input.value;
  object.pasien = H.setObjectPasien(props.pasien);
  object.registrasi = H.setObjectRegistrasi(props.registrasi);
  let selectedCollection = null;

  switch (props.registrasi.objectruanganlastfk) {

    case 212:
      switch (input.value.studyType) {
        case 'TransThoracaEchoBayi':
          selectedCollection = COLLECTION_TransThoracaEchoBayi.value;
          object.jenistindakan = 'Trans Toracha Echo Bayi';
          break;
        case 'TransThoracaEchoDewasa':
          selectedCollection = COLLECTION_TransThoracaEchoDewasa.value;
          object.jenistindakan = 'Trans Toracha Echo Dewasa';
          break;
        case 'LowerExtermityDuplexUltrasoundUSGDoppler':
          selectedCollection = COLLECTION_LowerExtermityDuplexUltrasoundUSGDoppler.value;
          object.jenistindakan = 'Lower Extermity Duplex Ultrasound USG Doppler';
          break;
        case 'CarotidDuplexUltrasound':
          selectedCollection = COLLECTION_CarotidDuplexUltrasound.value;
          object.jenistindakan = 'Carotid Duplex Ultrasound';
          break;
        case 'EKGJantung':
          selectedCollection = COLLECTION_EKG.value;
          object.jenistindakan = 'EKG Jantung';
          break;
        default:
          break;
      }
      if (selectedCollection) {
        console.log(`Selected Collection for 212: ${selectedCollection}`);
        simpanTemplate(ID, selectedCollection, object);
      }
      break;

    case 377:
      switch (input.value.studyType) {
        case 'TransThoracaEchoBayi':
          selectedCollection = COLLECTION_TransThoracaEchoBayi.value;
          object.jenistindakan = 'Trans Toracha Echo Bayi';
          break;
        case 'TransThoracaEchoDewasa':
          selectedCollection = COLLECTION_TransThoracaEchoDewasa.value;
          object.jenistindakan = 'Trans Toracha Echo Dewasa';
          break;
        case 'LowerExtermityDuplexUltrasoundUSGDoppler':
          selectedCollection = COLLECTION_LowerExtermityDuplexUltrasoundUSGDoppler.value;
          object.jenistindakan = 'Lower Extermity Duplex Ultrasound USG Doppler';
          break;
        case 'CarotidDuplexUltrasound':
          selectedCollection = COLLECTION_CarotidDuplexUltrasound.value;
          object.jenistindakan = 'Carotid Duplex Ultrasound';
          break;
        default:
          break;
      }
      if (selectedCollection) {
        console.log(`Selected Collection for 212: ${selectedCollection}`);
        simpanTemplate(ID, selectedCollection, object);
      } else if (selectedCollection === 'FormulirHasilPemeriksaanEkg') {
        object.jenistindakan = 'Pemeriksaan EKG Poli Jantung';
        console.log(`Selected Collection for EKG Poli Jantung: ${COLLECTION_EKG.value}`);
        simpanTemplate(ID, COLLECTION_EKG.value, object);
      }
      break;
    case 409:
      switch (input.value.studyType) {
        case 'TransThoracaEchoBayi':
          selectedCollection = COLLECTION_TransThoracaEchoBayi.value;
          object.jenistindakan = 'Trans Toracha Echo Bayi';
          break;
        case 'TransThoracaEchoDewasa':
          selectedCollection = COLLECTION_TransThoracaEchoDewasa.value;
          object.jenistindakan = 'Trans Toracha Echo Dewasa';
          break;
        case 'LowerExtermityDuplexUltrasoundUSGDoppler':
          selectedCollection = COLLECTION_LowerExtermityDuplexUltrasoundUSGDoppler.value;
          object.jenistindakan = 'Lower Extermity Duplex Ultrasound USG Doppler';
          break;
        case 'CarotidDuplexUltrasound':
          selectedCollection = COLLECTION_CarotidDuplexUltrasound.value;
          object.jenistindakan = 'Carotid Duplex Ultrasound';
          break;
        default:
          break;
      }
      if (selectedCollection) {
        console.log(`Selected Collection for 212: ${selectedCollection}`);
        simpanTemplate(ID, selectedCollection, object);
      } else if (selectedCollection === 'FormulirHasilPemeriksaanEkg') {
        object.jenistindakan = 'Pemeriksaan EKG Poli Jantung';
        console.log(`Selected Collection for EKG Poli Jantung: ${COLLECTION_EKG.value}`);
        simpanTemplate(ID, COLLECTION_EKG.value, object);
      }
      break;
    case 202:
      switch (input.value.jenisPemeriksaanObgyn) {
        case 'PemeriksaanKardiotokografi':
          object.jenistindakan = 'Pemeriksaan Kardiotokografi';
          selectedCollection = COLLECTION_PEMERIKSAAN_KARDIOTOKOGRAFI.value;
          ID = '';
          break;
        case 'PemeriksaanUSGObstetri':
          object.jenistindakan = 'Pemeriksaan USG Obstetri';
          selectedCollection = COLLECTION_OBSTETRI.value;
          ID = '';
          break;
        case 'PemeriksaanUSGGynekologi':
          object.jenistindakan = 'Pemeriksaan USG Gynekologi';
          selectedCollection = COLLECTION_GYNEKOLOGI.value;
          ID = '';
          break;
        case 'PemeriksaanUSGFetal':
          object.jenistindakan = 'Pemeriksaan USG Fetal';
          selectedCollection = COLLECTION_FETAL.value;
          ID = '';
          break;
        default:
          break;
      }
      if (selectedCollection) {
        console.log(`Selected Collection for 202: ${selectedCollection}`);
        simpanTemplate(ID, selectedCollection, object);
      }
      break;
    case 310:
      switch (input.value.jenisPemeriksaanObgyn) {
        case 'PemeriksaanKardiotokografi':
          object.jenistindakan = 'Pemeriksaan Kardiotokografi';
          selectedCollection = COLLECTION_PEMERIKSAAN_KARDIOTOKOGRAFI.value;
          ID = '';
          break;
        case 'PemeriksaanUSGObstetri':
          object.jenistindakan = 'Pemeriksaan USG Obstetri';
          selectedCollection = COLLECTION_OBSTETRI.value;
          ID = '';
          break;
        case 'PemeriksaanUSGGynekologi':
          object.jenistindakan = 'Pemeriksaan USG Gynekologi';
          selectedCollection = COLLECTION_GYNEKOLOGI.value;
          ID = '';
          break;
        case 'PemeriksaanUSGFetal':
          object.jenistindakan = 'Pemeriksaan USG Fetal';
          selectedCollection = COLLECTION_FETAL.value;
          ID = '';
          break;
        default:
          break;
      }
      if (selectedCollection) {
        console.log(`Selected Collection for 202: ${selectedCollection}`);
        sendRequest(ID, selectedCollection, object);
      }
      break;
    case 323:
      switch (input.value.jenisPemeriksaanObgyn) {
        case 'PemeriksaanKardiotokografi':
          object.jenistindakan = 'Pemeriksaan Kardiotokografi';
          selectedCollection = COLLECTION_PEMERIKSAAN_KARDIOTOKOGRAFI.value;
          ID = '';
          break;
        case 'PemeriksaanUSGObstetri':
          object.jenistindakan = 'Pemeriksaan USG Obstetri';
          selectedCollection = COLLECTION_OBSTETRI.value;
          ID = '';
          break;
        case 'PemeriksaanUSGGynekologi':
          object.jenistindakan = 'Pemeriksaan USG Gynekologi';
          selectedCollection = COLLECTION_GYNEKOLOGI.value;
          ID = '';
          break;
        case 'PemeriksaanUSGFetal':
          object.jenistindakan = 'Pemeriksaan USG Fetal';
          selectedCollection = COLLECTION_FETAL.value;
          ID = '';
          break;
        default:
          break;
      }
      if (selectedCollection) {
        console.log(`Selected Collection for 202: ${selectedCollection}`);
        sendRequest(ID, selectedCollection, object);
      }
      break;
    case 314:
      switch (input.value.jenisPemeriksaanObgyn) {
        case 'PemeriksaanKardiotokografi':
          object.jenistindakan = 'Pemeriksaan Kardiotokografi';
          selectedCollection = COLLECTION_PEMERIKSAAN_KARDIOTOKOGRAFI.value;
          ID = '';
          break;
        case 'PemeriksaanUSGObstetri':
          object.jenistindakan = 'Pemeriksaan USG Obstetri';
          selectedCollection = COLLECTION_OBSTETRI.value;
          ID = '';
          break;
        case 'PemeriksaanUSGGynekologi':
          object.jenistindakan = 'Pemeriksaan USG Gynekologi';
          selectedCollection = COLLECTION_GYNEKOLOGI.value;
          ID = '';
          break;
        case 'PemeriksaanUSGFetal':
          object.jenistindakan = 'Pemeriksaan USG Fetal';
          selectedCollection = COLLECTION_FETAL.value;
          ID = '';
          break;
        default:
          break;
      }
      if (selectedCollection) {
        console.log(`Selected Collection for 202: ${selectedCollection}`);
        sendRequest(ID, selectedCollection, object);
      }
      break;
    case 370:
      switch (input.value.jenisPemeriksaanObgyn) {
        case 'PemeriksaanKardiotokografi':
          object.jenistindakan = 'Pemeriksaan Kardiotokografi';
          selectedCollection = COLLECTION_PEMERIKSAAN_KARDIOTOKOGRAFI.value;
          ID = '';
          break;
        case 'PemeriksaanUSGObstetri':
          object.jenistindakan = 'Pemeriksaan USG Obstetri';
          selectedCollection = COLLECTION_OBSTETRI.value;
          ID = '';
          break;
        case 'PemeriksaanUSGGynekologi':
          object.jenistindakan = 'Pemeriksaan USG Gynekologi';
          selectedCollection = COLLECTION_GYNEKOLOGI.value;
          ID = '';
          break;
        case 'PemeriksaanUSGFetal':
          object.jenistindakan = 'Pemeriksaan USG Fetal';
          selectedCollection = COLLECTION_FETAL.value;
          ID = '';
          break;
        default:
          break;
      }
      if (selectedCollection) {
        console.log(`Selected Collection for 202: ${selectedCollection}`);
        simpanTemplate(ID, selectedCollection, object);
      }
      break;
    case 402:
      switch (input.value.jenisPemeriksaanObgyn) {
        case 'PemeriksaanKardiotokografi':
          object.jenistindakan = 'Pemeriksaan Kardiotokografi';
          selectedCollection = COLLECTION_PEMERIKSAAN_KARDIOTOKOGRAFI.value;
          ID = '';
          break;
        case 'PemeriksaanUSGObstetri':
          object.jenistindakan = 'Pemeriksaan USG Obstetri';
          selectedCollection = COLLECTION_OBSTETRI.value;
          ID = '';
          break;
        case 'PemeriksaanUSGGynekologi':
          object.jenistindakan = 'Pemeriksaan USG Gynekologi';
          selectedCollection = COLLECTION_GYNEKOLOGI.value;
          ID = '';
          break;
        case 'PemeriksaanUSGFetal':
          object.jenistindakan = 'Pemeriksaan USG Fetal';
          selectedCollection = COLLECTION_FETAL.value;
          ID = '';
          break;
        default:
          break;
      }
      if (selectedCollection) {
        console.log(`Selected Collection for 202: ${selectedCollection}`);
        simpanTemplate(ID, selectedCollection, object);
      }
      break;

    case 204:
      selectedCollection = COLLECTION_THT.value;
      console.log(`Selected Collection for THT: ${selectedCollection}`);
      ID = '';
      simpanTemplate(ID, selectedCollection, object);
      break;
    case 378:
      selectedCollection = COLLECTION_THT.value;
      console.log(`Selected Collection for THT: ${selectedCollection}`);
      ID = '';
      simpanTemplate(ID, selectedCollection, object);
      break;
    case 404:
      selectedCollection = COLLECTION_THT.value;
      console.log(`Selected Collection for THT: ${selectedCollection}`);
      ID = '';
      simpanTemplate(ID, selectedCollection, object);
      break;

    case 201:
      selectedCollection = COLLECTION_INTERNA.value;
      object.jenistindakan = 'Tindakan EKG Poli Interna';
      console.log(`Selected Collection for Interna: ${selectedCollection}`);
      simpanTemplate(ID, selectedCollection, object);
      break;
    case 365:
      selectedCollection = COLLECTION_INTERNA.value;
      object.jenistindakan = 'Tindakan EKG Poli Interna';
      console.log(`Selected Collection for Interna: ${selectedCollection}`);
      simpanTemplate(ID, selectedCollection, object);
      break;
    case 401:
      selectedCollection = COLLECTION_INTERNA.value;
      object.jenistindakan = 'Tindakan EKG Poli Interna';
      console.log(`Selected Collection for Interna: ${selectedCollection}`);
      simpanTemplate(ID, selectedCollection, object);
      break;
    case 217:
      selectedCollection = COLLECTION_EKG_MCU.value;
      object.jenistindakan = 'Tindakan EKG Poli MCU';
      console.log(`Selected Collection for MCU: ${selectedCollection}`);
      simpanTemplate(ID, selectedCollection, object);
      break;

    case 224:
      selectedCollection = COLLECTION_UROLOGI.value;
      object.jenistindakan = 'Tindakan EKG Poli USG Urologi';
      console.log(`Selected Collection for Urologi: ${selectedCollection}`);
      ID = '';
      simpanTemplate(ID, selectedCollection, object);
      break;
    case 375:
      selectedCollection = COLLECTION_UROLOGI.value;
      object.jenistindakan = 'Tindakan EKG Poli USG Urologi';
      console.log(`Selected Collection for Urologi: ${selectedCollection}`);
      ID = '';
      simpanTemplate(ID, selectedCollection, object);
      break;

    case 206:
      switch (true) {
        case isBodyPlethySmograph.value:
          selectedCollection = COLLECTION_BODYPLETHYSMOGRAPH.value;
          object.jenistindakan = 'Body Plethysmograph';
          break;
        case isDlcoBodyPlethy.value:
          selectedCollection = COLLECTION_DLCO.value;
          object.jenistindakan = 'Dlco Body Plethysmograph';
          break;
        case isSprirometri.value:
          selectedCollection = COLLECTION_SPIROMETRI.value;
          object.jenistindakan = 'Spirometri';
          break;
        case isEKGParu.value:
          selectedCollection = COLLECTION_EKGPARU.value;
          object.jenistindakan = 'EKG Paru';
          break;
        default:
          break;
      }
      if (selectedCollection) {
        console.log(`Selected Collection for 206: ${selectedCollection}`);
        simpanTemplate(ID, selectedCollection, object);
      }
      break;
    case 382:
      switch (true) {
        case isBodyPlethySmograph.value:
          selectedCollection = COLLECTION_BODYPLETHYSMOGRAPH.value;
          object.jenistindakan = 'Body Plethysmograph';
          break;
        case isDlcoBodyPlethy.value:
          selectedCollection = COLLECTION_DLCO.value;
          object.jenistindakan = 'Dlco Body Plethysmograph';
          break;
        case isSprirometri.value:
          selectedCollection = COLLECTION_SPIROMETRI.value;
          object.jenistindakan = 'Spirometri';
          break;
        case isEKGParu.value:
          selectedCollection = COLLECTION_EKGPARU.value;
          object.jenistindakan = 'EKG Paru';
          break;
        default:
          break;
      }
      if (selectedCollection) {
        console.log(`Selected Collection for 206: ${selectedCollection}`);
        simpanTemplate(ID, selectedCollection, object);
      }
      break;
    default:
      object.jenistindakan = 'Pemeriksaan EKG Poli Jantung';
      break;
  }
}

const pilihTemplateFix = async (index: any) => {
  isLoading.value = true
  useApi().get(
    `/emr/get-emr-template?collection=${COLLECTION.value}`).then((responselast: any) => {
      isLoading.value = false
      console.log(responselast)
      if (responselast.length) {
        for (var x = 0; x < responselast.length; x++) {
          responselast[x].no = x + 1
          // responselast[x].id = ''
        }
        listTemplateFix.value = responselast //set ke inputan
        showModalTemplateFix.value = true
      } else {
        H.alert('warning', 'Data tidak ada')
      }
    })
}

function defineCOLLECTION() {
  let object: any = {};
  let ID = '';
  let anudipake = '';
  switch (props.registrasi.objectruanganlastfk) {
    case 212:
      switch (input.value.studyType) {
        case 'TransThoracaEchoBayi':
          anudipake = COLLECTION_TransThoracaEchoBayi.value;
          object.jenistindakan = 'Trans Toracha Echo Bayi';
          break;
        case 'TransThoracaEchoDewasa':
          anudipake = COLLECTION_TransThoracaEchoDewasa.value;
          object.jenistindakan = 'Trans Toracha Echo Dewasa';
          break;
        case 'LowerExtermityDuplexUltrasoundUSGDoppler':
          anudipake = COLLECTION_LowerExtermityDuplexUltrasoundUSGDoppler.value;
          object.jenistindakan = 'Lower Extermity Duplex Ultrasound USG Doppler';
          break;
        case 'CarotidDuplexUltrasound':
          anudipake = COLLECTION_CarotidDuplexUltrasound.value;
          object.jenistindakan = 'Carotid Duplex Ultrasound';
          break;
        case 'EKGJantung':
          anudipake = COLLECTION_EKG.value;
          object.jenistindakan = 'EKG Jantung';
          break;
        default:
          break;
      }
      if (anudipake) {
        console.log(`Selected Collection for 212: ${anudipake}`);
        return anudipake;
      }
      break;

    case 377:
      switch (input.value.studyType) {
        case 'TransThoracaEchoBayi':
          anudipake = COLLECTION_TransThoracaEchoBayi.value;
          object.jenistindakan = 'Trans Toracha Echo Bayi';
          break;
        case 'TransThoracaEchoDewasa':
          anudipake = COLLECTION_TransThoracaEchoDewasa.value;
          object.jenistindakan = 'Trans Toracha Echo Dewasa';
          break;
        case 'LowerExtermityDuplexUltrasoundUSGDoppler':
          anudipake = COLLECTION_LowerExtermityDuplexUltrasoundUSGDoppler.value;
          object.jenistindakan = 'Lower Extermity Duplex Ultrasound USG Doppler';
          break;
        case 'CarotidDuplexUltrasound':
          anudipake = COLLECTION_CarotidDuplexUltrasound.value;
          object.jenistindakan = 'Carotid Duplex Ultrasound';
          break;
        default:
          break;
      }
      if (anudipake) {
        console.log(`Selected Collection for 212: ${anudipake}`);
        return anudipake;
      } else if (anudipake === 'FormulirHasilPemeriksaanEkg') {
        object.jenistindakan = 'Pemeriksaan EKG Poli Jantung';
        console.log(`Selected Collection for EKG Poli Jantung: ${COLLECTION_EKG.value}`);
        anudipake = COLLECTION_EKG.value;
        return anudipake;
      }
      break;
    case 409:
      switch (input.value.studyType) {
        case 'TransThoracaEchoBayi':
          anudipake = COLLECTION_TransThoracaEchoBayi.value;
          object.jenistindakan = 'Trans Toracha Echo Bayi';
          break;
        case 'TransThoracaEchoDewasa':
          anudipake = COLLECTION_TransThoracaEchoDewasa.value;
          object.jenistindakan = 'Trans Toracha Echo Dewasa';
          break;
        case 'LowerExtermityDuplexUltrasoundUSGDoppler':
          anudipake = COLLECTION_LowerExtermityDuplexUltrasoundUSGDoppler.value;
          object.jenistindakan = 'Lower Extermity Duplex Ultrasound USG Doppler';
          break;
        case 'CarotidDuplexUltrasound':
          anudipake = COLLECTION_CarotidDuplexUltrasound.value;
          object.jenistindakan = 'Carotid Duplex Ultrasound';
          break;
        default:
          break;
      }
      if (anudipake) {
        console.log(`Selected Collection for 212: ${anudipake}`);
        return anudipake;
      } else if (selectedCollection === 'FormulirHasilPemeriksaanEkg') {
        object.jenistindakan = 'Pemeriksaan EKG Poli Jantung';
        console.log(`Selected Collection for EKG Poli Jantung: ${COLLECTION_EKG.value}`);
        return COLLECTION_EKG.value
      }
      break;
    case 202:
      switch (input.value.jenisPemeriksaanObgyn) {
        case 'PemeriksaanKardiotokografi':
          object.jenistindakan = 'Pemeriksaan Kardiotokografi';
          anudipake = COLLECTION_PEMERIKSAAN_KARDIOTOKOGRAFI.value;
          ID = '';
          break;
        case 'PemeriksaanUSGObstetri':
          object.jenistindakan = 'Pemeriksaan USG Obstetri';
          anudipake = COLLECTION_OBSTETRI.value;
          ID = '';
          break;
        case 'PemeriksaanUSGGynekologi':
          object.jenistindakan = 'Pemeriksaan USG Gynekologi';
          anudipake = COLLECTION_GYNEKOLOGI.value;
          ID = '';
          break;
        case 'PemeriksaanUSGFetal':
          object.jenistindakan = 'Pemeriksaan USG Fetal';
          anudipake = COLLECTION_FETAL.value;
          ID = '';
          break;
        default:
          break;
      }
      if (anudipake) {
        console.log(`Selected Collection for 202: ${anudipake}`);
        return anudipake;
      }
      break;
    case 370:
      switch (input.value.jenisPemeriksaanObgyn) {
        case 'PemeriksaanKardiotokografi':
          object.jenistindakan = 'Pemeriksaan Kardiotokografi';
          anudipake = COLLECTION_PEMERIKSAAN_KARDIOTOKOGRAFI.value;
          ID = '';
          break;
        case 'PemeriksaanUSGObstetri':
          object.jenistindakan = 'Pemeriksaan USG Obstetri';
          anudipake = COLLECTION_OBSTETRI.value;
          ID = '';
          break;
        case 'PemeriksaanUSGGynekologi':
          object.jenistindakan = 'Pemeriksaan USG Gynekologi';
          anudipake = COLLECTION_GYNEKOLOGI.value;
          ID = '';
          break;
        case 'PemeriksaanUSGFetal':
          object.jenistindakan = 'Pemeriksaan USG Fetal';
          anudipake = COLLECTION_FETAL.value;
          ID = '';
          break;
        default:
          break;
      }
      if (anudipake) {
        console.log(`Selected Collection for 202: ${anudipake}`);
        return anudipake;
      }
      break;
    case 402:
      switch (input.value.jenisPemeriksaanObgyn) {
        case 'PemeriksaanKardiotokografi':
          object.jenistindakan = 'Pemeriksaan Kardiotokografi';
          anudipake = COLLECTION_PEMERIKSAAN_KARDIOTOKOGRAFI.value;
          ID = '';
          break;
        case 'PemeriksaanUSGObstetri':
          object.jenistindakan = 'Pemeriksaan USG Obstetri';
          anudipake = COLLECTION_OBSTETRI.value;
          ID = '';
          break;
        case 'PemeriksaanUSGGynekologi':
          object.jenistindakan = 'Pemeriksaan USG Gynekologi';
          anudipake = COLLECTION_GYNEKOLOGI.value;
          ID = '';
          break;
        case 'PemeriksaanUSGFetal':
          object.jenistindakan = 'Pemeriksaan USG Fetal';
          anudipake = COLLECTION_FETAL.value;
          ID = '';
          break;
        default:
          break;
      }
      if (anudipake) {
        console.log(`Selected Collection for 202: ${anudipake}`);
        return anudipake;
      }
      break;

    case 204:
      anudipake = COLLECTION_THT.value;
      console.log(`Selected Collection for THT: ${anudipake}`);
      ID = '';
      return anudipake
      break;
    case 378:
      anudipake = COLLECTION_THT.value;
      console.log(`Selected Collection for THT: ${anudipake}`);
      ID = '';
      return anudipake
      break;
    case 404:
      anudipake = COLLECTION_THT.value;
      console.log(`Selected Collection for THT: ${anudipake}`);
      ID = '';
      return anudipake
      break;

    case 201:
      anudipake = COLLECTION_INTERNA.value;
      object.jenistindakan = 'Tindakan EKG Poli Interna';
      console.log(`Selected Collection for Interna: ${anudipake}`);
      return anudipake
      break;
    case 365:
      anudipake = COLLECTION_INTERNA.value;
      object.jenistindakan = 'Tindakan EKG Poli Interna';
      console.log(`Selected Collection for Interna: ${anudipake}`);
      return anudipake
      break;
    case 401:
      anudipake = COLLECTION_INTERNA.value;
      object.jenistindakan = 'Tindakan EKG Poli Interna';
      console.log(`Selected Collection for Interna: ${anudipake}`);
      return anudipake
      break;
    case 217:
      anudipake = COLLECTION_EKG_MCU.value;
      object.jenistindakan = 'Tindakan EKG Poli MCU';
      console.log(`Selected Collection for MCU: ${anudipake}`);
      return anudipake
      break;

    case 224:
      anudipake = COLLECTION_UROLOGI.value;
      object.jenistindakan = 'Tindakan EKG Poli USG Urologi';
      console.log(`Selected Collection for Urologi: ${anudipake}`);
      ID = '';
      return anudipake
      break;
    case 375:
      anudipake = COLLECTION_UROLOGI.value;
      object.jenistindakan = 'Tindakan EKG Poli USG Urologi';
      console.log(`Selected Collection for Urologi: ${anudipake}`);
      ID = '';
      return anudipake
      break;

    case 206:
      switch (true) {
        case isBodyPlethySmograph.value:
          anudipake = COLLECTION_BODYPLETHYSMOGRAPH.value;
          object.jenistindakan = 'Body Plethysmograph';
          break;
        case isDlcoBodyPlethy.value:
          anudipake = COLLECTION_DLCO.value;
          object.jenistindakan = 'Dlco Body Plethysmograph';
          break;
        case isSprirometri.value:
          anudipake = COLLECTION_SPIROMETRI.value;
          object.jenistindakan = 'Spirometri';
          break;
        case isEKGParu.value:
          anudipake = COLLECTION_EKGPARU.value;
          object.jenistindakan = 'EKG Paru';
          break;
        default:
          break;
      }
      if (anudipake) {
        console.log(`Selected Collection for 206: ${anudipake}`);
        return anudipake
      }
      break;
    case 382:
      switch (true) {
        case isBodyPlethySmograph.value:
          anudipake = COLLECTION_BODYPLETHYSMOGRAPH.value;
          object.jenistindakan = 'Body Plethysmograph';
          break;
        case isDlcoBodyPlethy.value:
          anudipake = COLLECTION_DLCO.value;
          object.jenistindakan = 'Dlco Body Plethysmograph';
          break;
        case isSprirometri.value:
          anudipake = COLLECTION_SPIROMETRI.value;
          object.jenistindakan = 'Spirometri';
          break;
        case isEKGParu.value:
          anudipake = COLLECTION_EKGPARU.value;
          object.jenistindakan = 'EKG Paru';
          break;
        default:
          break;
      }
      if (anudipake) {
        console.log(`Selected Collection for 206: ${anudipake}`);
        return anudipake
      }
      break;
    default:
      object.jenistindakan = 'Pemeriksaan EKG Poli Jantung';
      break;
  }
}


function sendRequest(id: string, collection: any, object: any) {
  id = input.value.id ?? null;
  if (collection === COLLECTION_OBSTETRI.value && collection == COLLECTION_GYNEKOLOGI.value && collection == COLLECTION_FETAL.value && collection == COLLECTION_PEMERIKSAAN_KARDIOTOKOGRAFI.value && collection == COLLECTION_UROLOGI.value) {
    // id = null;
    id = input.value.id ? input.value.id : '';
  }
  object.registrasi = props.registrasi
  object.pasien = props.pasien

  if (!object.registrasi) {
    object.registrasi = props.registrasi;
  }

  if (!object.pasien) {
    object.pasien = props.pasien;
  }
  // if (!id || id == '' || id == null) {
  //   object.pasien = H.setObjectPasien(props.pasien);
  //   object.registrasi = H.setObjectRegistrasi(props.registrasi);
  // } else {
  //   delete object.pasien;
  //   delete object.registrasi;
  // }
  let json = {
    id: id,
    norec_emr: NOREC_EMRPASIEN.value,
    collection: collection,
    url_form: props.FORM_URL,
    name_form: 'Penunjang Khusus',
    jenis_emr: 'asesmen_medis',
    data: object,
  };
  console.log(JSON.stringify(json.data._id, null, 2));
  delete json.data._id;
  // console.log(JSON.stringify(json.data, null, 2));
  // return
  isLoading.value = true;
  if (id && NOREC_EMRPASIEN.value) isResumeMedis.value = false;
  useApi()
    .post(`/emr/simpan-emr`, json)
    .then((response: any) => {
      if (isResumeMedis.value) {
        makeRingkasanData(json)
        saveKlaimSEP();
      }
      isLoading.value = false;
      disabledSave.value = true;
      IDEMR.value = response.id;
      NOREC_EMRPASIEN.value = response.norec_emr;
      isRiwayat.value = true;
      isInputData.value = false;
      resetInput()
      loadRiwayat();
    })
    .catch((e: any) => {
      console.error("Failed to save:", e);
      isLoading.value = false;
    });
}

const saveKlaimSEP = async () => {
  isLoading.value = true
  await useApi().post('/bridging/inacbgs/collect-dokumen', {
    'norec_pd': props.registrasi.norec_pd,
    'documentklaimfk': 207,
    'namafile': "resume_medis_rj",
    'tglregistrasi': props.registrasi.tglregistrasi,
    'api': "EMR-ReportEMRCtrl@cetakEMR-RingkasanKeluar"
  }).then((r) => {
    isLoading.value = false
  }).catch((er) => {
    isLoading.value = false
  })
}

const loadRiwayat = async () => {
  let collectionsMap = {
    204: ["PemeriksaanTHT"],
    404: ["PemeriksaanTHT"],
    378: ["PemeriksaanTHT"],
    202: [
      "PemeriksaanKardiotokografi",
      "PemeriksaanObstetri",
      "PemeriksaanGynekologi",
      "PemeriksaanFetal",
    ],
    314: [
      "PemeriksaanKardiotokografi",
      "PemeriksaanObstetri",
      "PemeriksaanGynekologi",
      "PemeriksaanFetal",
    ],
    310: [
      "PemeriksaanKardiotokografi",
      "PemeriksaanObstetri",
      "PemeriksaanGynekologi",
      "PemeriksaanFetal",
    ],
    323: [
      "PemeriksaanKardiotokografi",
      "PemeriksaanObstetri",
      "PemeriksaanGynekologi",
      "PemeriksaanFetal",
    ],
    370: [
      "PemeriksaanKardiotokografi",
      "PemeriksaanObstetri",
      "PemeriksaanGynekologi",
      "PemeriksaanFetal",
    ],
    402: [
      "PemeriksaanKardiotokografi",
      "PemeriksaanObstetri",
      "PemeriksaanGynekologi",
      "PemeriksaanFetal",
    ],
    212: [
      "TransThoracaEchoBayi",
      "TransThoracaEchoDewasa",
      "LowerExtermityDuplexUltrasoundUSGDoppler",
      "CarotidDuplexUltrasound",
      "FormulirHasilPemeriksaanEkg"
    ],
    303: [
      "TransThoracaEchoBayi",
      "TransThoracaEchoDewasa",
      "LowerExtermityDuplexUltrasoundUSGDoppler",
      "CarotidDuplexUltrasound",
      "FormulirHasilPemeriksaanEkg",
    ],
    377: [
      "TransThoracaEchoBayi",
      "TransThoracaEchoDewasa",
      "LowerExtermityDuplexUltrasoundUSGDoppler",
      "CarotidDuplexUltrasound",
      "FormulirHasilPemeriksaanEkg",
    ],
    409: [
      "TransThoracaEchoBayi",
      "TransThoracaEchoDewasa",
      "LowerExtermityDuplexUltrasoundUSGDoppler",
      "CarotidDuplexUltrasound",
      "FormulirHasilPemeriksaanEkg",
    ],
    317: [
      "TransThoracaEchoBayi",
      "TransThoracaEchoDewasa",
      "LowerExtermityDuplexUltrasoundUSGDoppler",
      "CarotidDuplexUltrasound",
      "FormulirHasilPemeriksaanEkg",
    ],
    318: [
      "TransThoracaEchoBayi",
      "TransThoracaEchoDewasa",
      "LowerExtermityDuplexUltrasoundUSGDoppler",
      "CarotidDuplexUltrasound",
      "FormulirHasilPemeriksaanEkg",
    ],
    319: [
      "TransThoracaEchoBayi",
      "TransThoracaEchoDewasa",
      "LowerExtermityDuplexUltrasoundUSGDoppler",
      "CarotidDuplexUltrasound",
      "FormulirHasilPemeriksaanEkg",
    ],
    320: [
      "TransThoracaEchoBayi",
      "TransThoracaEchoDewasa",
      "LowerExtermityDuplexUltrasoundUSGDoppler",
      "CarotidDuplexUltrasound",
      "FormulirHasilPemeriksaanEkg",
    ],
    201: ["PemeriksaanEkgInterna"],
    401: ["PemeriksaanEkgInterna"],
    365: ["PemeriksaanEkgInterna"],
    217: ["PemeriksaanEkgMcu"],
    224: ["PemeriksaanUrologi"],
    375: ["PemeriksaanUrologi"],
    206: [
      "HasilPemeriksaanBodyplethy",
      "HasilPemeriksaanDLCOBodyplethy",
      "HasilPemeriksaanSpirometri",
      "PemeriksaanEkgParu"
    ],
    382: [
      "HasilPemeriksaanBodyplethy",
      "HasilPemeriksaanDLCOBodyplethy",
      "HasilPemeriksaanSpirometri",
      "PemeriksaanEkgParu"
    ],
  };

  isLoading.value = true;
  listTemplate.value = [];
  const loadedCollections = new Set();

  try {
    const responsex = await useApi().get(
      `/dashboard/get-norecapd-ruangan?norec_pd=${props.registrasi.norec_pd}`
    );

    for (let record of responsex) {
      const ruanganId = record.objectruanganfk;
      const collections = collectionsMap[ruanganId] || [];
      console.log("Processing ruanganId:", ruanganId);

      // if (collections.length > 0) {
      //   // Panggil fungsi untuk mengambil data dan detail hanya sekali
      //   await fetchCollectionsData(collections, ruanganId);
      //   // await fetchCollectionsDetails(collections, ruanganId);
      // }
      for (let collection of collections) {
        if (!loadedCollections.has(collection)) {
          console.log(`Fetching ${collection} from ruangan ${ruanganId}`);
          await fetchCollectionsData([collection], ruanganId);
          loadedCollections.add(collection);
        } else {
          console.log(`Skipping already loaded collection: ${collection}`);
        }
      }
    }
  } catch (error) {
    console.error("Error loading riwayat:", error);
  } finally {
    isLoading.value = false;
  }

  if (props.registrasi.objectruanganlastfk == 201) {
    input.value.jenistindakan = "Pemeriksaan EKG";
  }
};

const fetchCollectionsData = async (collections, ruanganId) => {
  try {
    for (let collection of collections) {
      const response = await useApi().get(
        `/emr/get-emr?nocmfk=${ID_PASIEN}&collection=${collection}`
      );
      if (response.length) {
        response.forEach((item) => {
          let arrkey = item.studyType;
          const jenisTindakan = item.jenistindakan || "Lainnya";

          // jika collection sudah di panggil, maka jangan panggil ulang lagi
          if (!listTemplate.value[jenisTindakan]) {
            // panggil list template berdasarkan jenis tindakan
            listTemplate.value = { ...listTemplate.value, [jenisTindakan]: [] };
          }
          // Tambahkan item ke jenis tindakan yang sesuai
          listTemplate.value[jenisTindakan].push({ ...item, collection });
        });
      }
    }
    setAutoFill();

  } catch (error) {
    console.error(`Error fetching collections data for ruangan ${ruanganId}:`, error);
  }
};

const fetchCollectionsDetails = async (collections, ruanganId) => {
  try {
    for (let collection of collections) {
      const response = await useApi().get(
        `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${collection}&emrpasienfk=${NOREC_EMRPASIEN.value}`
      );

      if (response.length) {
        response.forEach((item) => {
          if (!input.value.studyType && input.value.studyType == null) {
            input.value = item;
          }
          if (NOREC_EMRPASIEN.value === "") {
            NOREC_EMRPASIEN.value = item.emrpasienfk;
          }
        });
      } else {
        isDisabled.value = true;
        resetInput();
        setAutoFill();
      }
    }
  } catch (error) {
    console.error(`Error fetching collection details for ruangan ${ruanganId}:`, error);
  }
};


const kembaliKeun = () => {
  window.history.back()
}
const setAutoFill = async () => {
  if (H.cacheHelper().get('cachePenunjang')) {
    input.value = H.cacheHelper().get('cachePenunjang')
  }
  input.value.umur = props.pasien.umur
  input.value.alamat = props.pasien.alamatlengkap
  input.value.namaPasien = props.pasien.namapasien
  input.value.jeniskelamin = props.pasien.jeniskelamin
  input.value.norm = props.pasien.nocm
  input.value.tanggalLahirPasien = props.pasien.tgllahir
  input.value.umurPasien = props.pasien.umur
  input.value.DDDokter = props.registrasi.dokter
  input.value.DDDokter = props.registrasi.dokter
  input.value.dokterPemeriksa = props.registrasi.dokter
  input.value.tglPembuatan = new Date()
}

const resetInput = () => {
  input.value = {
    tanggal: new Date(),
    tglEksaminasi: new Date(),
    studyType: input.value.studyType,
    namaPasien: props.pasien.namapasien,
    jeniskelamin: props.pasien.jeniskelamin,
    norm: props.pasien.nocm,
    tanggalLahirPasien: props.pasien.tgllahir,
    tanggalKunjunganPasien: props.registrasi.tglregistrasi,
    DDDokter: input.value.DDDokter,
    umurPasien: props.pasien.umur,
    jenistindakan: undefined,
    keteranganTelingaKanan: undefined,
    keteranganTelingaKiri: undefined,
    acTelingakanan: undefined,
    acTelingaKiri: undefined,
    bcTelingakanan: undefined,
    bcTelingaKiri: undefined
  }
}

watch(
  () => input.value.normalthtkanan,
  (newValue, oldValue) => {
    normalthtkanan(newValue)
  }
)

watch(
  () => input.value.normalthtkiri,
  (newValue, oldValue) => {
    normalthtkiri(newValue)
  }
)

watch(
  () => input,
  () => {
    H.cacheHelper().set('cachePenunjang', input.value);
  },
  { deep: true }
)

// watch(
//   () => input.value,
//   (newValue, oldValue) => {
//     console.log("input.value", input.value);

//     H.cacheHelper().set('cachePenunjang', input.value);
//   }
// )

const print = async () => {
  let selectedCollection = COLLECTION.value;

  switch (input.value.studyType) {
    case 'TransThoracaEchoBayi':
      selectedCollection = COLLECTION_TransThoracaEchoBayi.value;
      break;
    case 'TransThoracaEchoDewasa':
      selectedCollection = COLLECTION_TransThoracaEchoDewasa.value;
      break;
    case 'LowerExtermityDuplexUltrasoundUSGDoppler':
      selectedCollection = COLLECTION_LowerExtermityDuplexUltrasoundUSGDoppler.value;
      break;
    case 'UpperExtermityDuplexUltrasoundUSGDoppler':
      selectedCollection = COLLECTION_UpperExtermityDuplexUltrasoundUSGDoppler.value;
      break;
    default:
      break;
  }
  if (selectedCollection) {
    H.printBlade(`emr/cetak/${selectedCollection}?emrpasienfk=${NOREC_EMRPASIEN.value}&pdf=true`)
  }

  switch (props.registrasi.objectruanganlastfk) {
    case 204:
      H.printBlade(`emr/cetak/${COLLECTION_THT.value}?emrpasienfk=${NOREC_EMRPASIEN.value}&pdf=true`)
      break;
    case 217:
      H.printBlade(`emr/cetak/${COLLECTION_EKG_MCU.value}?emrpasienfk=${NOREC_EMRPASIEN.value}&pdf=true`)
      break;
    case 224:
      H.printBlade(`emr/cetak/${COLLECTION_UROLOGI.value}?emrpasienfk=${NOREC_EMRPASIEN.value}&pdf=true`)
      break;
    case 201:
      H.printBlade(`emr/cetak/${COLLECTION_INTERNA.value}?emrpasienfk=${NOREC_EMRPASIEN.value}&pdf=true`)
      break;
    case 206:
      switch (true) {
        case isBodyPlethySmograph.value:
          H.printBlade(`emr/cetak/${COLLECTION_BODYPLETHYSMOGRAPH.value}?emrpasienfk=${NOREC_EMRPASIEN.value}&pdf=true`)
          break;
        case isDlcoBodyPlethy.value:
          H.printBlade(`emr/cetak/${COLLECTION_DLCO.value}?emrpasienfk=${NOREC_EMRPASIEN.value}&pdf=true`)
          break;
        case isSprirometri.value:
          H.printBlade(`emr/cetak/${COLLECTION_SPIROMETRI.value}?emrpasienfk=${NOREC_EMRPASIEN.value}&pdf=true`)
          break;
        default:
          break;
      }
      break;
    default:
      break;
  }

};



const cetakSalahSatu = async (emrId) => {
  console.log(emrId);
  H.printBlade(`emr/cetak-detail/${emrId.collection}?id=${emrId.id}&pdf=true`);

  return

  let selectedCollection = COLLECTION.value;

  switch (input.value.studyType) {
    case 'TransThoracaEchoBayi':
      selectedCollection = COLLECTION_TransThoracaEchoBayi.value;
      break;
    case 'TransThoracaEchoDewasa':
      selectedCollection = COLLECTION_TransThoracaEchoDewasa.value;
      break;
    case 'LowerExtermityDuplexUltrasoundUSGDoppler':
      selectedCollection = COLLECTION_LowerExtermityDuplexUltrasoundUSGDoppler.value;
      break;
    case 'UpperExtermityDuplexUltrasoundUSGDoppler':
      selectedCollection = COLLECTION_UpperExtermityDuplexUltrasoundUSGDoppler.value;
      break;
    default:
      break;
  }

  if (selectedCollection) {
    H.printBlade(`emr/cetak-detail/${selectedCollection}?id=${emrId}&pdf=true`);
  }

  switch (props.registrasi.objectruanganlastfk) {
    case 204:
      H.printBlade(`emr/cetak-detail/${COLLECTION_THT.value}?id=${emrId}&pdf=true`);
      break;
    case 217:
      H.printBlade(`emr/cetak-detail/${COLLECTION_EKG_MCU.value}?id=${emrId}&pdf=true`);
      break;
    case 224:
      H.printBlade(`emr/cetak-detail/${COLLECTION_UROLOGI.value}?id=${emrId}&pdf=true`);
      break;
    case 201:
      H.printBlade(`emr/cetak-detail/${COLLECTION_INTERNA.value}?id=${emrId}&pdf=true`);
      break;
    case 206:
      switch (true) {
        case isBodyPlethySmograph.value:
          H.printBlade(`emr/cetak-detail/${COLLECTION_BODYPLETHYSMOGRAPH.value}?id=${emrId}&pdf=true`);
          break;
        case isDlcoBodyPlethy.value:
          H.printBlade(`emr/cetak-detail/${COLLECTION_DLCO.value}?id=${emrId}&pdf=true`);
          break;
        case isSprirometri.value:
          H.printBlade(`emr/cetak-detail/${COLLECTION_SPIROMETRI.value}?id=${emrId}&pdf=true`);
          break;
        case isEKGParu.value:
          H.printBlade(`emr/cetak-detail/${COLLECTION_EKGPARU.value}?id=${emrId}&pdf=true`);
          break;
        default:
          break;
      }
      break;
    default:
      break;
  }
};

// const deleteSalahSatu = async (emrId) => {
//   try{
//     console.log(emrId);
//     const urlDelete = useApi().post(`emr/hapus-penunjang/${emrId.collection}?id=${emrId.id}`).then((res) => {
//       console.log(res);
//       H.alert('success', 'Data Berhasil Dihapus');
//       loadRiwayat();
//     }).catch((err) => {
//       console.log(err);
//       H.alert('error', 'Data Gagal Dihapus');
//     });
//   } catch (error) {
//     console.log(error);
//   }
// }
const deleteSalahSatu = async (emrId) => {
  const url = await useApi().post(`emr/hapus-penunjang/${emrId.collection}?id=${emrId.id}`);
  console.log(url);
  H.alert('success', 'Data Berhasil Dihapus');
  loadRiwayat();
}
// const deleteSalahSatu = async (emrId) => {
//   try {
//     if (!emrId || !emrId.collection || !emrId.id) {
//       console.error('emrId invalid:', emrId);
//       H.alert('error', 'Data tidak lengkap untuk penghapusan.');
//       return;
//     }

//     console.log('Sending delete request with:', emrId);

//     const res = await useApi().post(`emr/hapus-penunjang/${emrId.collection}?id=${emrId.id}`);

//     console.log('API raw response:', res);

//     const responseData = res?.data ?? res; // Fallback kalau res.data undefined

//     if (!responseData || responseData.status !== 200) {
//       throw new Error('Respon dari server tidak sesuai atau gagal');
//     }

//     H.alert('success', 'Data Berhasil Dihapus');
//     loadRiwayat();
//   } catch (err) {
//     console.error('Caught error:', err);
//     H.alert('error', 'Data Gagal Dihapus');
//     loadRiwayat();
//   }
// };




function checkResume() {
  // if not dokter just end
  console.log(`res Check Resume kelompok`, kelompokUser);
  if (kelompokUser && kelompokUser.toUpperCase() != 'DOKTER') {
    return;
  }

  let params = `?norec_pd=${props.registrasi.norec_pd}&norec_apd=${props.registrasi.norec_apd}`
  let uri = `/emr/check-resume-medis${params}`;
  useApi().get(uri).then((res) => {
    console.log(`res Check Resume`, res);
    if (res) {
      isResumeMedis.value = true;
    }
  })
}

const checkAsesmen = async () => {
  isLoading.value = true;
  if (props.registrasi.namaruangan.toUpperCase().indexOf('IGD') > -1) {
    let data = ''
    let datax = ''


    let dataCollection = 'AsesmenAwalMedisGawatDarurat';
    let fieldCollection = 'TARiwayatPenyakitSekarang,TARiwayatPenyakitDahulu,TARiwayatPenggunaanObat,TARiwayatAlergi,TARiwayatVaksin,TAMOI,TArpp,TAInstruksi,TADiagnosis,perlukontrol,riwayatkeluar,statuskeluar'
    const data_AMI = await useApi().get("emr/auto-fill?nocmfk=" + ID_PASIEN + "&norec_pd=" + NOREC_PD +
      `&collection=${dataCollection}` +
      `&field=${fieldCollection}`
    )
    console.log("DATA AMI", data_AMI);
    const data_TPI = await useApi().get("emr/auto-fill?nocmfk=" + ID_PASIEN + "&norec_pd=" + NOREC_PD + "&collection=TriagePasienIGD" + `&field=keadaanumum,TBeGCS,TBvGCS,TBmGCS,TBcelciusTTV,TBPernafasanTTV,TBberatBadanTTV,TBnadiTTV,TBtekananDarahTTV,TBnspo2TTV,TBtinggiBadanTTV`)

    if (data_TPI && props.registrasi.namaruangan.trim() != 'RUANG VK - IGD') {
      data += data_TPI.TBcelciusTTV ? `Suhu : ${data_TPI.TBcelciusTTV} °C\n` : ''
      data += data_TPI.TBnadiTTV ? `Nadi : ${data_TPI.TBnadiTTV} x/mnt\n` : ''
      data += data_TPI.TBPernafasanTTV ? `Pernafasan : ${data_TPI.TBPernafasanTTV} x/mnt\n` : ''
      data += data_TPI.TBtekananDarahTTV ? `Tekanan Darah : ${data_TPI.TBtekananDarahTTV} mmHg\n` : ''
      data += data_TPI.TBtinggiBadanTTV ? `Tinggi Badan : ${data_TPI.TBtinggiBadanTTV} Cm\n` : ''
      data += data_TPI.TBberatBadanTTV ? `Berat Badan : ${data_TPI.TBberatBadanTTV} Kg\n` : ''
      data += data_TPI.TBnspo2TTV ? `SPO2 : ${data_TPI.TBnspo2TTV} %\n` : ''

      dataAsesmen.value = {
        gcse: data_TPI.TBeGCS ?? '',
        gcsv: data_TPI.TBvGCS ?? '',
        gcsm: data_TPI.TBmGCS ?? '',
        nadi: data_TPI.TBnadiTTV ?? '',
        nafas: data_TPI.TBPernafasanTTV ?? '',
        suhu: data_TPI.TBcelciusTTV ?? '',
        beratBadan: data_TPI.TBberatBadanTTV ?? '',
        tinggiBadan: data_TPI.TBtinggiBadanTTV ?? '',
        keadaanumum: data_TPI.keadaanumum ?? '',
      }
    }

    if (data_AMI) {
      if (kelompokUser.toUpperCase().indexOf('DOKTER') > -1) {
        datax += data_AMI.TARiwayatPenyakitSekarang ? `Riwayat Penyakit Sekarang : ${data_AMI.TARiwayatPenyakitSekarang}\n` : 'Riwayat Penyakit Sekarang : -\n'
        datax += data_AMI.TARiwayatPenyakitDahulu ? `Riwayat Penyakit Dahulu : ${data_AMI.TARiwayatPenyakitDahulu}\n` : 'Riwayat Penyakit Dahulu : -\n'
        datax += data_AMI.TARiwayatPenggunaanObat ? `Riwayat Penggunaan Obat : ${data_AMI.TARiwayatPenggunaanObat}\n` : 'Riwayat Pengobatan : -\n'
        datax += data_AMI.TARiwayatAlergi ? `Riwayat Alergi : ${data_AMI.TARiwayatAlergi}\n` : 'Riwayat Alergi : -\n'
        datax += data_AMI.TARiwayatVaksin ? `Riwayat Vaksin : ${data_AMI.TARiwayatVaksin}\n` : 'Riwayat Vaksin : -\n'
        datax += data_AMI.TAMOI ? `MOI : ${data_AMI.TAMOI}\n` : 'MOI : -\n'

        dataAsesmen.value.perlukontrol = data_AMI.perlukontrol ?? '';
        dataAsesmen.value.riwayatkeluar = data_AMI.riwayatkeluar ?? '';
        dataAsesmen.value.statuskeluar = data_AMI.statuskeluar ?? '';
        dataAsesmen.value.diagnosa = data_AMI.TADiagnosa ?? '';
        // dataAsesmen.value = {
        //   gcse: data_TPI.TBeGCS ?? '',
        //   gcsv: data_TPI.TBvGCS ?? '',
        //   gcsm: data_TPI.TBmGCS ?? '',
        //   nadi: data_TPI.TBnadiTTV ?? '',
        //   nafas: data_TPI.TBPernafasanTTV ?? '',
        //   suhu: data_TPI.TBcelciusTTV ?? '',
        //   beratBadan: data_TPI.TBberatBadanTTV ?? '',
        //   tinggiBadan: data_TPI.TBtinggiBadanTTV ?? '',
        //   keadaanumum: data_TPI.keadaanumum ?? '',
        // }
      }
    }
    // datax += strper != ''? strper + "\n" + strdok : strper + strdok;
    strforO.value = data;
    strforS.value = datax;
    isLoading.value = false;
  } else if (props.registrasi.namaruangan.toUpperCase().indexOf('HEMO') > -1) {
    await useApi().get(`/emr/check-asesmen-hemo?nocmfk=${ID_PASIEN}`).then(async (res) => {
      isLoading.value = false;
      getDataForHemo(res);
    })
  } else {
    await useApi().get(`/emr/check-asesmen-cppt?nocmfk=${ID_PASIEN}`).then(async (res) => {
      isLoading.value = false;

      let data = ''
      let datax = ''

      data += res.result.celciusObgyn ? `Suhu : ${res.result.celciusObgyn} °C\n` : ''
      data += res.result.nadiObgyn ? `Nadi : ${res.result.nadiObgyn} x/mnt\n` : ''
      data += res.result.nafasObgyn ? `Pernafasan : ${res.result.nafasObgyn} x/mnt\n` : ''
      data += res.result.tekananDarahObgyn ? `Tekanan Darah : ${res.result.tekananDarahObgyn} mmHg\n` : ''
      data += res.result.tinggibadanObgyn ? `Tinggi Badan : ${res.result.tinggibadanObgyn} Cm\n` : ''
      data += res.result.beratbadanObgyn ? `Berat Badan : ${res.result.beratbadanObgyn} Kg\n` : ''
      data += res.result.sao2Obgyn ? `SPO2 : ${res.result.sao2Obgyn} %\n` : ''
      data += res.result.IMT ? `IMT : ${res.result.IMT}\n` : ''

      datax += res.result.keluhanutama ? `Keluhan Utama : ${res.result.keluhanutama}\n` : 'Keluhan Utama : -\n'
      datax += res.result.riwayatpenyakit ? `Riwayat Penyakit Sekarang : ${res.result.riwayatpenyakit}\n` : 'Riwayat Penyakit Sekarang : -\n'
      datax += res.result.riwayatpenyakitdahulu ? `Riwayat Penyakit Dahulu : ${res.result.riwayatpenyakitdahulu}\n` : 'Riwayat Penyakit Dahulu : -\n'
      datax += res.result.riwayatpengobatan ? `Riwayat Pengobatan : ${res.result.riwayatpengobatan}\n` : 'Riwayat Pengobatan : -\n'
      datax += res.result.riwayatpenyakitkeluarga ? `Riwayat Penyakit Keluarga : ${res.result.riwayatpenyakitkeluarga}\n` : 'Riwayat Penyakit Keluarga : -\n'
      datax += res.result.isalergi ? `Alergi : ${res.result.isalergi} ${res.result.riwayatalergi}\n` : 'Alergi : -\n'

      dataAsesmen.value = {
        gcse: res.result.gcse ?? '',
        gcsv: res.result.gcsv ?? '',
        gcsm: res.result.gcsm ?? '',
        nadi: res.result.nadiObgyn ?? '',
        nafas: res.result.nafasObgyn ?? '',
        suhu: res.result.celciusObgyn ?? '',
        beratBadan: res.result.beratbadanObgyn ?? '',
        tinggiBadan: res.result.tinggibadanObgyn ?? '',
        keadaanumum: res.result.keadaanumumobgyn ?? '',
        tekananDarah: res.result.tekananDarahObgyn ?? '',
        perlukontrol: res.result.perlukontrol ?? '',
        riwayatkeluar: res.result.riwayatkeluar ?? '',
        statuskeluar: res.result.statuskeluar ?? '',
        keadaanumum: res.result.keadaanumumobgyn ?? '',
        diagnosa: res.result.TADiagnosa ?? '',
      }
      strforO.value = data;
      strforS.value = datax;


    })
  }
}

async function makeRingkasanData(json: any) {
  return new Promise((resolve) => {
    let tanggaldatang = '';
    let dpjpUtamas: any = {
      value: userLogin.pegawai.id,
      label: userLogin.pegawai.namaLengkap,
    };

    let object = {
      "detailDS": [
        {
          "no": 1,
          "TADiagnosaSekunder": ""
        }
      ],
      "detailDT": [
        {
          "no": 1,
          "TADeskripsiTindakan": ""
        }
      ],
      "waktuTataLaksana": props.registrasi.tglregistrasi,
      "waktuKontrol": props.registrasi.tglregistrasi,
      "jamKedatangan": props.registrasi.tglregistrasi,
      "jamAsesmenAwal": props.registrasi.tglregistrasi,
      "riwayatkeluar": dataAsesmen.value.riwayatkeluar,
      "statuskeluar": dataAsesmen.value.statuskeluar,
      "perlukontrol": dataAsesmen.value.perlukontrol,
      "tanggalKedatangan": props.registrasi.tglregistrasi,
      "dpjpUtama": dpjpUtamas,
      "TAKondisiSaatMasuk": '',
      "TADiagnosisPrimer": dataAsesmen.value.TADiagnosa ?? null,
      "gcse": dataAsesmen.value.gcse ?? '',
      "gcsv": dataAsesmen.value.gcsv ?? '',
      "gcsm": dataAsesmen.value.gcsm ?? '',
      "kesanUmum": dataAsesmen.value.keadaanumum ?? '',
      "nadi": dataAsesmen.value.nadi ?? '',
      "nafas": dataAsesmen.value.nafas ?? '',
      "celcius": dataAsesmen.value.suhu ?? '',
      "tekananDarah": dataAsesmen.value.tekananDarah ?? '',
      "anamnesis": strforS.value ?? '',
      "pemeriksaanfisik": strforO.value ?? '',
      "intruksi": json.data.instruksiAsesmen ?? '',
      "hasilpemeriksaanpenunjang": json.data.hasilpemeriksaanpenunjang ?? '',
      "sumber": "Penunjang"
    }
    object.nocm = json.data.pasien.nocm
    object.pasien = json.data.pasien
    object.registrasi = json.data.registrasi
    let sendData = {
      'id': '',
      'norec_emr': '',
      'collection': 'RingkasanKeluar',
      'url_form': 'module-emr-profile-pasien-page-emr-ringkasan-keluar',
      'name_form': 'Ringkasan Keluar',
      'jenis_emr': 'asesmen_medis',
      'data': object
    }

    useApi().postNoMessage(
      `/emr/simpan-emr`, sendData).then(async (response: any) => {
        isLoading.value = false
        H.alert('success', 'Ringkasan keluar berhasil dibuat');
        return resolve(true)
      }).catch((e: any) => {
        isLoading.value = true
        H.alert('error', 'Ringkasan keluar gagal dibuat');
        return resolve(false)
      })

  })

}

async function getDataForHemo(res: any) {
  let data = ''
  let datax = ''

  data += res.result.celciusObgyn ? `Suhu : ${res.result.celciusObgyn} °C\n` : ''
  data += res.result.nadiObgyn ? `Nadi : ${res.result.nadiObgyn} x/mnt\n` : ''
  data += res.result.nafasObgyn ? `Pernafasan : ${res.result.nafasObgyn} x/mnt\n` : ''
  data += res.result.tekananDarahObgyn ? `Tekanan Darah : ${res.result.tekananDarahObgyn} mmHg\n` : ''
  data += res.result.tinggibadanObgyn ? `Tinggi Badan : ${res.result.tinggibadanObgyn} Cm\n` : ''
  data += res.result.beratbadanObgyn ? `Berat Badan : ${res.result.beratbadanObgyn} Kg\n` : ''
  data += res.result.sao2Obgyn ? `SPO2 : ${res.result.sao2Obgyn} %\n` : ''
  data += res.result.IMT ? `IMT : ${res.result.IMT}\n` : ''

  datax += res.result.keluhanutama ? `Keluhan Utama : ${res.result.keluhanutama}\n` : 'Keluhan Utama : -\n'
  datax += res.result.riwayatpenyakit ? `Riwayat Penyakit Sekarang : ${res.result.riwayatpenyakit}\n` : 'Riwayat Penyakit Sekarang : -\n'
  datax += res.result.riwayatpenyakitdahulu ? `Riwayat Penyakit Dahulu : ${res.result.riwayatpenyakitdahulu}\n` : 'Riwayat Penyakit Dahulu : -\n'
  datax += res.result.riwayatpengobatan ? `Riwayat Pengobatan : ${res.result.riwayatpengobatan}\n` : 'Riwayat Pengobatan : -\n'
  datax += res.result.riwayatpenyakitkeluarga ? `Riwayat Penyakit Keluarga : ${res.result.riwayatpenyakitkeluarga}\n` : 'Riwayat Penyakit Keluarga : -\n'
  datax += res.result.isalergi ? `Alergi : ${res.result.isalergi} ${res.result.riwayatalergi}\n` : 'Alergi : -\n'

  dataAsesmen.value = {
    gcse: res.result.gcse ?? '',
    gcsv: res.result.gcsv ?? '',
    gcsm: res.result.gcsm ?? '',
    nadi: res.result.nadiObgyn ?? '',
    nafas: res.result.nafasObgyn ?? '',
    suhu: res.result.celciusObgyn ?? '',
    beratBadan: res.result.beratbadanObgyn ?? '',
    tinggiBadan: res.result.tinggibadanObgyn ?? '',
    keadaanumum: res.result.keadaanumumobgyn ?? '',
    tekananDarah: res.result.tekananDarahObgyn ?? '',
    perlukontrol: res.result.perlukontrol ?? '',
    riwayatkeluar: res.result.riwayatkeluar ?? '',
    statuskeluar: res.result.statuskeluar ?? '',
    keadaanumumobgyn: res.result.keadaanumumobgyn ?? '',
    diagnosa: res.result.TADiagnosa ?? '',
  }
  strforO.value = data;
  strforS.value = datax;

}

onMounted(() => {
  setView()
  setAutoFill()
  loadRiwayat()
  fetchDokter()
  checkAsesmen();
  checkResume();
  COLLECTION.value = defineCOLLECTION();
})
</script>
