<template>
  <div class="form-layout is-stacked-2">
    <div class="form-outer" style="margin-top: 15px">
      <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
        <div class="form-header-inner">
          <div class="left">
            <h3>Penunjang Khusus Obgyn</h3>
          </div>
          <div class="right">
            <div class="buttons">
              <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading"
                @simpan="simpan" @kembaliKeun="kembaliKeun" isHideST></ButtonEmr>
            </div>
          </div>
        </div>
      </div>

      <div class="columns is-multiline p-2">
          <div class="column is-12">
              <div class="columns is-multiline">
                  <div class="column is-12 buttons mb-0 mt-0" style="margin:10px;vertical-align:middle">
                      <VButton rounded icon="feather:plus" raised bold
                      @click="addUpload()" color="warning" outlined
                      :loading="isLoading" class="mr-2">Upload</VButton>
                      <VButton rounded icon="feather:eye" raised bold @click="previewBerkas()" color="purple"
                          outlined :loading="isLoading" class="mr-2">Lihat Foto</VButton>
                  </div>
              </div>
          </div>
      </div>


      <div class="columns is-multiline p-2">
        <div class="column is-12">
          <div class="is-12">
            <Fieldset>
              <div class="columns is-multiline">

                <div class="column is-12 is-flex ml-5">
                  <div class="column is-3">
                    <h1>Status HPV (High Risk) :</h1>
                  </div>
                  <div class="column is-9">
                    <VField>
                      <VControl>
                        <VInput v-model="input.StatusHPV" class="input" type="text" />
                      </VControl>
                    </VField>
                  </div>
                </div>
                <div class="column is-12 is-flex ml-5">
                  <div class="column is-3">
                    <h1>Hasil Sitologi (tanggal) :</h1>
                  </div>
                  <div class="column is-5">
                    <VField>
                      <VDatePicker v-model="input.HasilSitologi" mode="date" trim-weeks :max-date="new Date()">
                        <template #default="{ inputValue, inputEvents }">
                          <VField>
                            <VControl icon="feather:calendar" fullwidth>
                              <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                            </VControl>
                          </VField>
                        </template>
                      </VDatePicker>
                    </VField>
                  </div>
                </div>
                <div class="column is-12 is-flex ml-5">
                  <div class="column is-3">
                    <h1>Hasil biopsy :</h1>
                  </div>
                  <div class="column is-9">
                    <VField>
                      <VControl>
                        <VInput v-model="input.Hasilbiopsy" class="input" type="text" />
                      </VControl>
                    </VField>
                  </div>
                </div>
                <div class="column is-12 is-flex ml-5">
                  <div class="column is-3">
                    <h1>Vulva (Normal/Kelainan) :</h1>
                  </div>
                  <div class="column is-9">
                    <VField>
                      <VControl>
                        <VInput v-model="input.Vulva" class="input" type="text" />
                      </VControl>
                    </VField>
                  </div>
                </div>
                <div class="column is-12 is-flex ml-5">
                  <div class="column is-3">
                    <h1>Vagina (Normal/Kelainan) :</h1>
                  </div>
                  <div class="column is-9">
                    <VField>
                      <VControl>
                        <VInput v-model="input.Vagina" class="input" type="text" />
                      </VControl>
                    </VField>
                  </div>
                </div>
                <div class="column is-12 is-flex ml-5">
                  <div class="column is-3">
                    <h1>Asesmen Kolposkopi :</h1>
                  </div>  
                  <div class="column is-9">
                    <VField>
                      <VControl>
                        <VInput v-model="input.AsesmenKolposkopi" class="input" type="text" />
                      </VControl>
                    </VField>
                  </div>
                </div>
                <div class="column is-12 is-flex ml-5">
                  <div class="column is-3">
                    <h1>Adekuat :</h1>
                  </div>
                  <div class="column is-2">
                    <VField>
                      <VControl>
                        <Multiselect v-model="input.Adekuat" :attrs="{ value }" placeholder="--Pilih--" label="label"
                          :options="d_adekuat" :searchable="true" track-by="label" mode="single" autocomplete="off"
                          :disabled="paramRiwayat">
                        </Multiselect>
                      </VControl>
                    </VField>
                  </div>
                </div>
                <div class="column is-12 is-flex ml-5">
                  <div class="column is-3">
                    <h1>Sambungan skuamokolumnar :</h1>
                  </div>
                  <div class="column is-2">
                    <VField>
                      <VControl>
                        <Multiselect v-model="input.skuamokolumnar" :attrs="{ value }" placeholder="--Pilih--" label="label"
                          :options="d_skuamokolumnar" :searchable="true" track-by="label" mode="single" autocomplete="off"
                          :disabled="paramRiwayat">
                        </Multiselect>
                      </VControl>
                    </VField>
                  </div>
                </div>

                <div class="column is-12 is-flex ml-5">
                  <div class="column is-2">
                    <h1>Zona transformasi :</h1>
                  </div>
                  <div class="column is-10">
                    <VField>
                      <VControl>
                        <Multiselect v-model="input.Zonatransfor" :attrs="{ value }" placeholder="--Pilih--" label="label"
                          :options="d_zonatransformasi" :searchable="true" track-by="label" mode="single" autocomplete="off"
                          :disabled="paramRiwayat">
                        </Multiselect>
                      </VControl>
                    </VField>
                  </div>
                </div>

                <div class="column is-12 is-flex">
                  <div class="column is-12">
                    <h1><b>Gambaran kolposkopi normal :</b></h1>
                  </div>
                </div>

                <div class="column is-12 is-flex ml-5">
                  <div class="column is-3">
                    <h1>Epitel skuamosa original :</h1>
                  </div>
                  <div class="column is-2">
                    <VField>
                      <VControl>
                        <Multiselect v-model="input.skuamosaoriginal" :attrs="{ value }" placeholder="--Pilih--" label="label"
                          :options="d_skuamosaoriginal" :searchable="true" track-by="label" mode="single" autocomplete="off"
                          :disabled="paramRiwayat">
                        </Multiselect>
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-7">
                    <ImgDraw elemenID="Kolposkopy" :valueImg="input.Kolposkopy" height="250" width="260" imageSrc="/images/simrs/kolposkopy.png" />
                  </div>
                </div>

                <div class="column is-12 is-flex ml-5">
                  <div class="column is-2">
                    <h1>Epitel Kolumnar :</h1>
                  </div>
                  <div class="column is-10">
                    <span>ectopy -/+</span>
                  </div>
                </div>
                <div class="column is-12 is-flex ml-5">
                  <div class="column is-3">
                    <h1>Epitel skuamosa metaplastic :</h1>
                  </div>
                  <div class="column is-2">
                    <VField>
                      <VControl>
                        <Multiselect v-model="input.skuamosametaplastic" :attrs="{ value }" placeholder="--Pilih--" label="label"
                          :options="d_skuamosametaplastic" :searchable="true" track-by="label" mode="single" autocomplete="off"
                          :disabled="paramRiwayat">
                        </Multiselect>
                      </VControl>
                    </VField>
                  </div>
                </div>

                <div class="column is-12 is-flex ml-5">
                  <div class="column is-3">
                    <h1>Gambaran desidua Kehamilan:</h1>
                  </div>
                  <div class="column is-2">
                    <VField>
                      <VControl>
                        <Multiselect v-model="input.desidua" :attrs="{ value }" placeholder="--Pilih--" label="label"
                          :options="d_desidua" :searchable="true" track-by="label" mode="single" autocomplete="off"
                          :disabled="paramRiwayat">
                        </Multiselect>
                      </VControl>
                    </VField>
                  </div>
                </div>

                <div class="column is-12 is-flex">
                  <div class="column is-12">
                    <h1><b>Kolposkopi abnormal :</b></h1>
                  </div>
                </div>

                <div class="column is-12 is-flex ml-5">
                  <div class="column is-2">
                    <h1>Lokasi lesi :</h1>
                  </div>
                  <div class="column is-3">
                    <VField>
                      <VControl>
                        <Multiselect v-model="input.lokasilesi" :attrs="{ value }" placeholder="--Pilih--" label="label"
                          :options="d_lokasilesi" :searchable="true" track-by="label" mode="single" autocomplete="off"
                          :disabled="paramRiwayat">
                        </Multiselect>
                      </VControl>
                    </VField>
                  </div>
                </div>
                <div class="column is-12 is-flex ml-5">
                  <div class="column is-2">
                    <h1>Lokasi arah jam :</h1>
                  </div>
                  <div class="column is-9">
                    <VField>
                      <VControl>
                        <VInput v-model="input.lokasiArahjam" class="input" type="text" />
                      </VControl>
                    </VField>
                  </div>
                </div>
                <div class="column is-12 is-flex ml-5">
                  <div class="column is-2">
                    <h1>Jumlah kuadran :</h1>
                  </div>
                  <div class="column is-3">
                    <VField>
                      <VControl>
                        <Multiselect v-model="input.jumlahkuadran" :attrs="{ value }" placeholder="--Pilih--" label="label"
                          :options="d_jumlahkuadran" :searchable="true" track-by="label" mode="single" autocomplete="off"
                          :disabled="paramRiwayat">
                        </Multiselect>
                      </VControl>
                    </VField>
                  </div>
                </div>
                <div class="column is-12 is-flex ml-5">
                  <div class="column is-2">
                    <h1>% serviks :</h1>
                  </div>
                  <div class="column is-9">
                    <VField>
                      <VControl>
                        <VInput v-model="input.serviks" class="input" type="text" />
                      </VControl>
                    </VField>
                  </div>
                </div>

                <div class="column is-12 is-flex">
                  <div class="column is-12">
                    <h1><b>Temuan</b></h1>
                  </div>
                </div>

                <div class="column is-12 is-flex ml-5">
                  <div class="column is-2">
                    <VControl raw subcontrol>
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            :true-value="GradeI"
                            label="Grade I"
                            v-model="input.temuangradei"
                        />
                    </VControl>
                  </div>
                  <div class="column is-2" v-if="input.temuangradei == true">
                    <VControl raw subcontrol>
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            :true-value="thinacetowhiteepithelium"
                            label="thin acetowhite epithelium"
                            v-model="input.thinacetowhiteepithelium"
                        />
                    </VControl>
                  </div>
                  <div class="column is-2" v-if="input.temuangradei == true">
                    <VControl raw subcontrol>
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            :true-value="irregularborder"
                            label="irregular border"
                            v-model="input.irregularborder"
                        />
                    </VControl>
                  </div>
                  <div class="column is-2" v-if="input.temuangradei == true">
                    <VControl raw subcontrol>
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            :true-value="geographicborder"
                            label="geographic border"
                            v-model="input.geographicborder"
                        />
                    </VControl>
                  </div>
                  <div class="column is-2" v-if="input.temuangradei == true">
                    <VControl raw subcontrol>
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            :true-value="finemosaic"
                            label="fine mosaic"
                            v-model="input.finemosaic"
                        />
                    </VControl>
                  </div>
                  <div class="column is-2" v-if="input.temuangradei == true">
                    <VControl raw subcontrol>
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            :true-value="finepunctuation"
                            label="fine punctuation"
                            v-model="input.finepunctuation"
                        />
                    </VControl>
                  </div>
                </div>

                <div class="column is-12 is-flex ml-5">
                  <div class="column is-2">
                    <VControl raw subcontrol>
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            :true-value="GradeII"
                            label="Grade II (mayor)"
                            v-model="input.temuangradeii"
                        />
                    </VControl>
                  </div>
                  <div class="column is-2" v-if="input.temuangradeii == true">
                    <VControl raw subcontrol>
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            :true-value="Denseacetowhiteepithelium"
                            label="Dense acetowhite epithelium"
                            v-model="input.Denseacetowhiteepithelium"
                        />
                    </VControl>
                  </div>
                  <div class="column is-2" v-if="input.temuangradeii == true">
                    <VControl raw subcontrol>
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            :true-value="distinctmargin"
                            label="distinct margin"
                            v-model="input.distinctmargin"
                        />
                    </VControl>
                  </div>
                  <div class="column is-2" v-if="input.temuangradeii == true">
                    <VControl raw subcontrol>
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            :true-value="rapidappearanceofacetowhite"
                            label="rapid appearance of acetowhite"
                            v-model="input.rapidappearanceofacetowhite"
                        />
                    </VControl>
                  </div>
                  <div class="column is-2" v-if="input.temuangradeii == true">
                    <VControl raw subcontrol>
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            :true-value="cuffedcryptglandopening"
                            label="cuffed crypt (gland) opening"
                            v-model="input.cuffedcryptglandopening"
                        />
                    </VControl>
                  </div>
                  <div class="column is-2" v-if="input.temuangradeii == true">
                    <VControl raw subcontrol>
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            :true-value="coarsemosaic"
                            label="coarse mosaic"
                            v-model="input.coarsemosaic"
                        />
                    </VControl>
                  </div>
                </div>
                <div class="column is-12 is-flex ml-5"  v-if="input.temuangradeii == true">
                  <div class="column is-2"></div>
                  <div class="column is-2">
                    <VControl raw subcontrol>
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            :true-value="coarsepunctuation"
                            label="coarse punctuation"
                            v-model="input.coarsepunctuation"
                        />
                    </VControl>
                  </div>
                  <div class="column is-2">
                    <VControl raw subcontrol>
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            :true-value="sharpbirder"
                            label="sharp birder"
                            v-model="input.sharpbirder"
                        />
                    </VControl>
                  </div>
                  <div class="column is-2">
                    <VControl raw subcontrol>
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            :true-value="innerbordersign"
                            label="inner border sign"
                            v-model="input.innerbordersign"
                        />
                    </VControl>
                  </div>
                  <div class="column is-2">
                    <VControl raw subcontrol>
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            :true-value="Pubdgesign"
                            label="Pubdge sign"
                            v-model="input.Pubdgesign"
                        />
                    </VControl>
                  </div>
                </div>
                <div class="column is-12 is-flex ml-5"  v-if="input.temuangradeii == true">
                  <div class="column is-2"></div>
                  <div class="column is-2">
                    <VControl raw subcontrol>
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            :true-value="Ragsign"
                            label="Rag sign"
                            v-model="input.Ragsign"
                        />
                    </VControl>
                  </div>
                </div>

                <div class="column is-12 is-flex ml-5">
                  <div class="column is-3">
                    <VControl raw subcontrol>
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            :true-value="Curigalesiinvasive"
                            label="Curiga lesi invasive"
                            v-model="input.temuanCurigalesiinvasive"
                        />
                    </VControl>
                  </div>
                  <div class="column is-2" v-if="input.temuanCurigalesiinvasive == true">
                    <VControl raw subcontrol>
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            :true-value="Atypicalvessels"
                            label="Atypical vessels"
                            v-model="input.Atypicalvessels"
                        />
                    </VControl>
                  </div>
                  <div class="column is-2" v-if="input.temuanCurigalesiinvasive == true">
                    <VControl raw subcontrol>
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            :true-value="garglevessles"
                            label="gargle vessles"
                            v-model="input.garglevessles"
                        />
                    </VControl>
                  </div>
                  <div class="column is-2" v-if="input.temuanCurigalesiinvasive == true">
                    <VControl raw subcontrol>
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            :true-value="irregularsurface"
                            label="irregular surface"
                            v-model="input.irregularsurface"
                        />
                    </VControl>
                  </div>
                  <div class="column is-2" v-if="input.temuanCurigalesiinvasive == true">
                    <VControl raw subcontrol>
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            :true-value="exophyticlesions"
                            label="exophytic lesions"
                            v-model="input.exophyticlesions"
                        />
                    </VControl>
                  </div>
                  <div class="column is-2" v-if="input.temuanCurigalesiinvasive == true">
                    <VControl raw subcontrol>
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            :true-value="necrosis"
                            label="necrosis"
                            v-model="input.necrosis"
                        />
                    </VControl>
                  </div>
                </div>
                <div class="column is-12 is-flex ml-5" v-if="input.temuanCurigalesiinvasive == true">
                  <div class="column is-3"></div>
                  <div class="column is-2">
                    <VControl raw subcontrol>
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            :true-value="ulceration"
                            label="ulceration"
                            v-model="input.ulceration"
                        />
                    </VControl>
                  </div>
                  <div class="column is-2">
                    <VControl raw subcontrol>
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            :true-value="tumor"
                            label="tumor"
                            v-model="input.tumor"
                        />
                    </VControl>
                  </div>
                  <div class="column is-2">
                    <VControl raw subcontrol>
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            :true-value="grossneoplasm"
                            label="gross neoplasm"
                            v-model="input.grossneoplasm"
                        />
                    </VControl>
                  </div>
                </div>

                <div class="column is-12 is-flex ml-5">
                  <div class="column is-2">
                    <VControl raw subcontrol>
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            :true-value="Nonspesifik"
                            label="Non spesifik"
                            v-model="input.temuanNonspesifik"
                        />
                    </VControl>
                  </div>
                  <div class="column is-2" v-if="input.temuanNonspesifik == true">
                    <VControl raw subcontrol>
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            :true-value="Leukoplakia"
                            label="Leukoplakia"
                            v-model="input.Leukoplakia"
                        />
                    </VControl>
                  </div>
                  <div class="column is-2" v-if="input.temuanNonspesifik == true">
                    <VControl raw subcontrol>
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            :true-value="erosi"
                            label="erosi"
                            v-model="input.erosi"
                        />
                    </VControl>
                  </div>
                </div>
                <div class="column is-12 is-flex ml-5">
                  <div class="column is-2">
                    <VControl raw subcontrol>
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            :true-value="Lainlain"
                            label="Lain-lain"
                            v-model="input.temuanLainlain"
                        />
                    </VControl>
                  </div>
                  <div class="column is-2" v-if="input.temuanLainlain == true">
                    <VControl raw subcontrol>
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            :true-value="CongenitalTZ"
                            label="Congenital TZ"
                            v-model="input.CongenitalTZ"
                        />
                    </VControl>
                  </div>
                  <div class="column is-2" v-if="input.temuanLainlain == true">
                    <VControl raw subcontrol>
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            :true-value="Condyloma"
                            label="Condyloma"
                            v-model="input.Condyloma"
                        />
                    </VControl>
                  </div>
                  <div class="column is-2" v-if="input.temuanLainlain == true">
                    <VControl raw subcontrol>
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            :true-value="Polypectocervical"
                            label="Polyp (ectocervical)"
                            v-model="input.Polypectocervical"
                        />
                    </VControl>
                  </div>
                  <div class="column is-2" v-if="input.temuanLainlain == true">
                    <VControl raw subcontrol>
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            :true-value="kylammation"
                            label="kylammation "
                            v-model="input.kylammation"
                        />
                    </VControl>
                  </div>
                  <div class="column is-2" v-if="input.temuanLainlain == true">
                    <VControl raw subcontrol>
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            :true-value="Stenosis"
                            label="Stenosis"
                            v-model="input.Stenosis"
                        />
                    </VControl>
                  </div>
                </div>
                <div class="column is-12 is-flex ml-5">
                  <div class="column is-2"></div>
                  <div class="column is-2" v-if="input.temuanLainlain == true">
                    <VControl raw subcontrol>
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            :true-value="CongenitalAnomaly"
                            label="Congenital Anomaly"
                            v-model="input.CongenitalAnomaly"
                        />
                    </VControl>
                  </div>
                  <div class="column is-2" v-if="input.temuanLainlain == true">
                    <VControl raw subcontrol>
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            :true-value="Endometriosis "
                            label="Endometriosis"
                            v-model="input.Endometriosis "
                        />
                    </VControl>
                  </div>
                  <div class="column is-2" v-if="input.temuanLainlain == true">
                    <VControl raw subcontrol>
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            :true-value="Polupendocervical"
                            label="Polup (endocervical)"
                            v-model="input.Polupendocervical"
                        />
                    </VControl>
                  </div>
                </div>

                <div class="column is-12 is-flex ml-5">
                  <table border="1" style="width: 100%; border: 1px solid black;">
                      <tr class="has-text-centered">
                          <th>
                            <span>Skor Swede</span>
                          </th>
                          <th>
                            <span>0</span>
                          </th>
                          <th>
                            <span>1</span>
                          </th>
                          <th>
                            <span>2</span>
                          </th>
                          <th>
                            <span>Skor</span>
                          </th>
                      </tr>
                      <tr class="has-text-centered">
                        <td>
                          <span>Acetouptake</span>
                        </td>
                        <td>
                          <span>Tidak ada/transparan</span>
                        </td>
                        <td>
                          <span>Putih susu</span>
                        </td>
                        <td>
                          <span>Putih padat</span>
                        </td>
                        <td>
                          <VControl>
                              <VInput type="number" class="input" placeholder=""
                                  v-model="input.skor1" />
                          </VControl>
                        </td>
                      </tr>
                      <tr class="has-text-centered">
                        <td>
                          <span>Batas</span>
                        </td>
                        <td>
                          <span>Tidak ada/menyebar</span>
                        </td>
                        <td>
                          <span>Tajam irregular, satelit</span>
                        </td>
                        <td>
                          <span>Berbatas tegas dan jelas</span>
                        </td>
                        <td>
                          <VControl>
                              <VInput type="number" class="input" placeholder=""
                                  v-model="input.skor2" />
                          </VControl>
                        </td>
                      </tr>
                      <tr class="has-text-centered">
                        <td>
                          <span>Pembuluh darah</span>
                        </td>
                        <td>
                          <span>Regular, halus</span>
                        </td>
                        <td>
                          <span>Tidak tampak</span>
                        </td>
                        <td>
                          <span>atipikal</span>
                        </td>
                        <td>
                          <VControl>
                              <VInput type="number" class="input" placeholder=""
                                  v-model="input.skor3" />
                          </VControl>
                        </td>
                      </tr>
                      <tr class="has-text-centered">
                        <td>
                          <span>Ukuran lesi</span>
                        </td>
                        <td>
                          <span>&lt;5mm</span>
                        </td>
                        <td>
                          <span>5-15 mm/2 kuadran</span>
                        </td>
                        <td>
                          <span>&gt;15 mm, 3-4 kuadran/endocervical</span>
                        </td>
                        <td>
                          <VControl>
                              <VInput type="number" class="input" placeholder=""
                                  v-model="input.skor4" />
                          </VControl>
                        </td>
                      </tr>
                      <tr class="has-text-centered">
                        <td>
                          <span>lodine uptake</span>
                        </td>
                        <td>
                          <span>Coklat</span>
                        </td>
                        <td>
                          <span>Kekuningan</span>
                        </td>
                        <td>
                          <span>Kuning</span>
                        </td>
                        <td>
                          <VControl>
                              <VInput type="number" class="input" placeholder=""
                                  v-model="input.skor5" />
                          </VControl>
                        </td>
                      </tr>
                      <tr class="has-text-centered">
                        <td colspan="4"><span>Total</span></td>
                        <td>
                          <VControl>
                              <VInput type="text" class="input" placeholder=""
                                  v-model="input.totalskor" />
                          </VControl>
                        </td>
                      </tr>
                  </table>
                </div>

                <div class="column is-12 is-flex ml-5">
                  <div class="column is-2">
                    <h1><b>Diagnosis</b></h1>
                  </div>
                  <div class="column is-3">
                    <VField>
                      <VControl>
                        <Multiselect v-model="input.diagnosis" :attrs="{ value }" placeholder="--Pilih--" label="label"
                          :options="d_diagnosis" :searchable="true" track-by="label" mode="single" autocomplete="off"
                          :disabled="paramRiwayat">
                        </Multiselect>
                      </VControl>
                    </VField>
                  </div>
                </div>

                <div class="column is-12 is-flex ml-5">
                  <div class="column is-2">
                    <h1><b>Manajemen :</b></h1>
                  </div>
                  <div class="column is-9">
                    <VField>
                        <VTextarea rows="2" v-model="input.Manajemen"></VTextarea>
                    </VField>
                  </div>
                </div>

                <div class="column is-12 is-flex ml-5">
                  <div class="column is-2">
                    <h1><b>Komentar :</b></h1>
                  </div>
                  <div class="column is-9">
                    <VField>
                        <VTextarea rows="2" v-model="input.Komentar"></VTextarea>
                    </VField>
                  </div>
                </div>
                

                <div class="column is-flex justify-content-end">
                  <h1 style="font-weight: bold" class="mr-5">Garut</h1>
                  <VField>
                    <VDatePicker v-model="input.tanggal" color="green" trim-weeks mode="dateTime"
                      :max-date="new Date()">
                      <template #default="{ inputValue, inputEvents }" class="pb-0">
                        <VField>
                          <VControl icon="feather:calendar">
                            <VInput type="text" placeholder="Select a date" :value="inputValue" v-on="inputEvents"
                              class="is-rounded_Z" />
                          </VControl>
                        </VField>
                      </template>
                    </VDatePicker>
                  </VField>
                </div>
                <div class="column is-12 is-flex ml-5 justify-content-end">
                  <div class="column is-4" style="text-align: center">
                    <!-- <TandaTangan :elemenID="'TTDdokter'" :width="'150'" :height="'150'" class="dek" /> -->
                    <VField>
                      <VControl>
                        <AutoComplete v-model="input.DDDokter" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                          :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                          :loadingIcon="'pi pi-spinner'" :field="'label'" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </Fieldset>
          </div>
        </div>
      </div>
    </div>
  </div>

  <VModal :open="modalBerkasPreview" title="Lihat Foto" :noclose="true" size="large" actions="right"
        @close="modalBerkasPreview = false">
        <template #content>
            <BerkasPasienView :data="dataSource" @edit="edit" @hapus="hapus" @lihat="lihat" :hide="false">
            </BerkasPasienView>
        </template>
    </VModal>

    <VModal :open="modalInput" title="Upload Foto" :noclose="true" size="medium" actions="right"
        @close="modalInput = false">
        <template #content>
            <div class="columns is-multiline">
                <div class="column is-12">
                    <VField>
                        <VLabel class="required-field">Author</VLabel>
                        <VControl>
                            <VInput type="text" class="input" v-model="item.author" />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-12">
                    <VField class="is-rounded-select is-autocomplete-select mt-0 pt-0" v-slot="{ id }">
                        <VLabel class="required-field">File</VLabel>
                        <VControl icon="fas fa-sticky-note" fullwidth class="prime-auto-select">
                            <Dropdown v-model="item.namafile" :options="d_Berkas" :optionLabel="'label'"
                                class="is-rounded" placeholder="File" style="width: 100%;" :filter="true" showClear />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-12">
                    <VField>
                        <VLabel class="required-field">Nama</VLabel>
                        <VControl icon="feather:bookmark">
                            <input v-model="item.nama" type="text" class="input is-rounded" placeholder="Nama " />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-12">
                    <VField>
                        <VLabel>Keterangan</VLabel>
                        <VControl>
                            <VTextarea v-model="item.keterangan" rows="3" placeholder="Keterangan">
                            </VTextarea>
                        </VControl>
                    </VField>
                </div>
                <div class="column is-12">
                    <FileUpload v-model="filePasien" mode="basic" name="demo" accept="image/jpeg,image/png"
                        @upload="onUpload" outlined
                        style=" background-color: transparent; color: var(--danger); border: 1px solid;"
                        :chooseLabel="filePasien ? filePasien.name : 'Unggah'" @select="onSelect($event)"
                        class="is-rounded w-100" />
                </div>
            </div>
        </template>
        <template #action>
            <VButton type="button" rounded outlined color="primary" raised icon="feather:save" :loading="isLoading"
                @click="simpanFile()"> Simpan
            </VButton>
        </template>
    </VModal>

</template>

<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, watch, watchEffect } from 'vue'
import { useRoute } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useUserSession } from '/@src/stores/userSession'
import AutoComplete from 'primevue/autocomplete'
import MultiSelect from 'primevue/multiselect'
import * as EMR from '../page-emr-plugins/lembaran-penyiaran-radioterapi'
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import Fieldset from 'primevue/fieldset'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import ImgDraw from '../page-emr-plugins/img-draw.vue'
import Dropdown from 'primevue/dropdown';
import FileUpload from 'primevue/fileupload';
import BerkasPasienView from './berkas-pasien-preview.vue'

let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string

let jenisTindakanRadioterapi = ref(EMR.jenisTindakanRadioterapi())
let JenisKelamin = ref(EMR.JenisKelamin())
let energy = ref(EMR.energy())
let accessories: any = ref(EMR.accessories())
let posisiMeja: any = ref(EMR.formField())
const user = useUserSession().getUser().pegawai

const props = withDefaults(
  defineProps<{
    pasien?: any
    registrasi?: any
    FORM_NAME?: string
    FORM_URL?: string
  }>(),
  {
    pasien: {},
    registrasi: {},
    FORM_NAME: '',
    FORM_URL: '',
  }
)
const RiwayatPsikososial: any = ref([
  { label: 'Baik', value: 'Baik' },
  { label: 'Tidak Baik', value: 'Tidak Baik' },
])

const { y } = useWindowScroll()
const isStuck = computed(() => {
  return y.value > 30
})
const isLoading: any = ref(false)
const i: any = ref(false)
const isLoadingVitalSign: any = ref(false)
const dataTTD: any = ref([])
const d_Perawat: any = ref([])
const d_Dokter: any = ref([])
const modalInput: any = ref(false)
const modalBerkasPreview: any = ref(false)
const d_Berkas: any = ref([])
const dataSource: any = ref([])
const filePasien: any = ref()
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: '',
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  selectedMenu: [false],
})
const COLLECTION: any = ref('PenunjangKhususObgyn') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
  tanggalKontrol: new Date(),
  tanggal: new Date(),
  skor1: 0,
  skor2: 0,
  skor3: 0,
  skor4: 0,
  skor5: 0,
  totalskor: 0,
})
const route = useRoute()
const setView = () => {
  useHead({
    title: 'Penunjang Khusus Obgyn' + ' - ' + import.meta.env.VITE_PROJECT,
  })
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
  useViewWrapper().setFullWidth(true)
}

const d_zonatransformasi: any = ref([{ value: 'Tipe I', label: 'Tipe I' }, { value: 'Tipe II', label: 'Tipe II' }, { value: 'Tipe III', label: 'Tipe III' }])
const d_lokasilesi: any = ref([{ value: 'dalam TZ', label: 'dalam TZ' }, { value: 'Luar TZ', label: 'Luar TZ' }, { value: 'Dalam luar TZ', label: 'Dalam luar TZ' }, { value: 'Pada polip', label: 'Pada polip' }])
const d_jumlahkuadran: any = ref([{ value: 1, label: '1' }, { value: 2, label: '2' }, { value: 3, label: '3' }, { value: 4, label: '4' }])
const d_adekuat: any = ref([{ value: 'Ya', label: 'Ya' }, { value: 'Tidak', label: 'Tidak' }])
const d_skuamokolumnar: any = ref([{ value: 'Tampak', label: 'Tampak' }, { value: 'Tidak', label: 'Tidak' }])
const d_skuamosaoriginal: any = ref([{ value: 'imatur', label: 'imatur' }, { value: 'matur', label: 'matur' }, { value: 'atrofi', label: 'atrofi' }])
const d_skuamosametaplastic : any = ref([{ value: 'kista naboti', label: 'kista naboti' }, { value: 'crypt (gland) opening', label: 'crypt (gland) opening' }])
const d_desidua: any = ref([{ value: 'Ya', label: 'Ya' }, { value: 'Tidak', label: 'Tidak' }])
const d_diagnosis: any = ref([{ value: 'Zona transformasi tipe I', label: 'Zona transformasi tipe I' }, { value: 'Zona transformasi tipe II', label: 'Zona transformasi tipe II' }, { value: 'Zona transformasi tipe III', label: 'Zona transformasi tipe III' }, { value: 'Normal', label: 'Normal' }, { value: 'LSIL', label: 'LSIL' }, { value: 'HSIL', label: 'HSIL' }, { value: 'Kanker', label: 'Kanker' }])



const loadRiwayat = async () => {
  // if (NOREC_EMRPASIEN.value == '') return
  let response = await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${NOREC_PD}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
    if (response.length) {
      input.value = response[0] //set ke inputan
      if (NOREC_EMRPASIEN.value == '') {
        NOREC_EMRPASIEN.value = response[0].emrpasienfk
      }
      dataTTD.value = response[0]
      await loadGambar2("Kolposkopy", dataTTD.value.Kolposkopy)
      H.tandaTangan().set('TTDdokter', dataTTD.value.TTDdokter)
      H.tandaTangan().set('TTDpasien', dataTTD.value.TTDpasien)
      H.tandaTangan().set('TTDadmission', dataTTD.value.TTDadmission)
     
      if (response[0].gambar) {
      input.value.gambar = response[0].gambar
    }

    }
    else {
      setAutoFill()
    }
  }

  const loadRiwayatBerkas = async () => {
    isLoading.value = true
    let param = `nocm=${props.pasien.nocm}&noregistrasi=${props.registrasi.noregistrasi}&dokumen=${53}`;
    await useApi().get(`/emr/berkas-pasien?${param}`).then((response: any) => {
        isLoading.value = false
        dataSource.value = response.data
    })
}

const onSelect = async (filez: any) => {
    const file = filez.files[0];
    filePasien.value = file
}

const edit = async (e: any) => {
    item.author = e.author
    item.norec = e.norec
    item.keterangan = e.deskripsi
    item.nama = e.nama
    d_Berkas.value.forEach((element: any) => {
        if (e.objectberkaspasien == element.id) {
            item.namafile = element
        }
    });
    let path = 'berkaspasien/' + e.nocm + '/' + e.namafile
    let file = await H.getFileBE(path);

    filePasien.value = file
    filePasien.value.name = e.namafile
    modalInput.value = true
}
const hapus = async (e: any) => {
    e.loadingHapus = true
    await useApi().post(`/emr/hapus-berkas-pasien`, { 'norec': e.norec, }).then((response: any) => {
        e.loadingHapus = false
        loadRiwayatBerkas()
    })
}

const lihat = async (e: any) => {
    H.openFile('berkaspasien/' + e.nocm + '/' + e.namafile);
}

const simpanFile = async () => {
    if (!item.namafile) {
        H.alert('error', 'Jenis File harus di isi')
        return
    }
    if (!item.nama) {
        H.alert('error', 'Nama harus di isi')
        return
    }
    if (!filePasien.value) {
        H.alert('error', 'File harus di unggah')
        return
    }
    const formData = new FormData()
    formData.append('filePasien', filePasien.value)
    formData.append('norec', item.norec ? item.norec : '')
    formData.append('noregistrasi', props.registrasi.noregistrasi)
    formData.append('nocm', props.pasien.nocm)
    formData.append('norec_apd', props.registrasi.norec_apd)
    formData.append('namafile', item.namafile.label)
    formData.append('keterangan', item.keterangan ? item.keterangan : null)
    formData.append('objectberkaspasien', item.namafile.value)
    formData.append('nama', item.nama)
    formData.append('author', item.author)
    // formData.append('halaman', parseInt(route.params.index_tabs))
    isLoading.value = true
    await useApi().post('/emr/simpan-berkas-pasien-old', formData).then((r) => {
        isLoading.value = false
        loadRiwayatBerkas()
        modalInput.value = false
    }).catch((e: any) => {
        isLoading.value = false
    })
}

const simpan = () => {
  let ID = input.value.id ? input.value.id : ''
  let object: any = {}

  object = input.value
  object.pasien = H.setObjectPasien(props.pasien)
  object.registrasi = H.setObjectRegistrasi(props.registrasi)
  object['TTDdokter'] = H.tandaTangan().get('TTDdokter')
  object['TTDpasien'] = H.tandaTangan().get('TTDpasien')
  object['TTDadmission'] = H.tandaTangan().get('TTDadmission')
  object['Kolposkopy'] = H.tandaTangan().get("Kolposkopy");

  object['gambar'] = input.value.gambar || ''

  let json = {
    id: ID,
    norec_emr: NOREC_EMRPASIEN.value,
    collection: COLLECTION.value,
    url_form: route.name,
    name_form: 'Penunjang Khusus Obgyn',
    jenis_emr: 'asesmen_medis',
    data: object,
  }
  isLoading.value = true
  useApi().post(`/emr/simpan-emr`, json).then((response: any) => {
        isLoading.value = false
        loadRiwayat();
    }).catch((e: any) => {
        isLoading.value = false
    })
}

const setAutoFill = async () => {
  input.value.petugasAddmision = { label: user.namaLengkap, value: user.id }
  input.value.namaPasien = props.pasien.namapasien
  input.value.CBPasien = props.pasien.namapasien
  input.value.jeniskelamin = props.pasien.jeniskelamin
  input.value.norm = props.pasien.nocm
  input.value.tanggalLahirPasien = props.pasien.tgllahir
  input.value.alamat = props.pasien.alamatlengkap
  input.value.pekerjaan = props.pasien.pekerjaan
  input.value.tanggalKunjunganPasien = props.registrasi.tglregistrasi
  input.value.DDDokter = { label: props.registrasi.dokter, value: props.registrasi.iddokter }
  input.value.ruangan = props.registrasi.namaruangan
  input.value.telepon = props.pasien.nohp
  input.value.kepalaPenanggungJawab = props.pasien.penanggungjawab ?? '-'
  input.value.tglPembuatan = new Date()
  input.value.tanggalAdmission = new Date()
  input.value.hubungan = props.pasien.hubungankeluarga ?? '-'
  input.value.tanggalTindakan = new Date()
  input.value.tanggalRawatInap = new Date()
  const response_AsmedRajal = await useApi().get("emr/auto-fill?nocmfk=" + ID_PASIEN + "&norec_pd=" + NOREC_PD + "&collection=AsesmenMedisRawatJalan" + `&field=TADiagnosa`)
  if (response_AsmedRajal != null) {
    input.value.diagnosa = response_AsmedRajal.TADiagnosa
  }
}

const loadGambar2 = async (element_id: string, value: string) => {
    let sigCanvas: any = document.getElementById(element_id);
    if (sigCanvas) {
        let context = sigCanvas.getContext("2d");
        context.clearRect(0, 0, sigCanvas.width, sigCanvas.height);
        let imagess = value
        let background = new Image();
        background.src = imagess
        background.onload = function () {
            context.drawImage(background, 0, 0, 260, 250);
        }
    }
}


const fetchDokter = async (filter: any) => {
  await useApi()
    .get(
      `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
    )
    .then((response) => {
      d_Dokter.value = response
    })
}

const d_Ruangan: any = ref([])
const fetchRuangan = async (filter: any) => {
  const response = await useApi().get(
    `/emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=10`
  )
  d_Ruangan.value = response
}

const d_Petugas = ref([]);

const fetchPetugas = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Petugas.value = response
  })
}

const fetchJenisFile = async (filter: any) => {
    const response = await useApi().get(`/emr/dropdown/berkaspasien_m?select=id,nama`)
    d_Berkas.value = response.filter(item => item.label.toLowerCase().includes('obgyn')).map(item => ({
        value: item.value,
        label: item.label
    }));
}

const addUpload = () => {
    modalInput.value = true
    item.author = user.namaLengkap
}
const previewBerkas = async () => {
    isLoading.value = true
    await loadRiwayatBerkas()
    isLoading.value = false
    modalBerkasPreview.value = true;
}

watchEffect(() => {
  if (!input.value) return; 
  
  let totalMasuk = 
    parseFloat(input.value.skor1 || 0) + 
    parseFloat(input.value.skor2 || 0) + 
    parseFloat(input.value.skor3 || 0) + 
    parseFloat(input.value.skor4 || 0) + 
    parseFloat(input.value.skor5 || 0);

  input.value.totalskor = totalMasuk;
});



setView()
loadRiwayat()
fetchJenisFile()
</script>
