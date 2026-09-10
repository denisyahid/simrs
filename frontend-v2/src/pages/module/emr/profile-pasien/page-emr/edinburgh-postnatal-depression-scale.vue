<template>
  <div class="form-layout is-stacked-2">
    <div class="form-outer" style="margin-top: 15px">
      <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
        <div class="form-header-inner">
          <div class="left">
            <h3>The Edinburgh Postnatal Depression Scale</h3>
          </div>
          <div class="right">
            <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading"
              @simpan="simpan" @kembaliKeun="kembaliKeun" @simpanTemplate="simpanTemplate"></ButtonEmr>
          </div>
        </div>
      </div>

      <VModal :open="showModalTemplate" title="Riwayat" :noclose="true" size="large" actions="right"
        @close="showModalTemplate = false">
        <template #content>
          <form class="modal-form">
            <div class="column is-12 pt-0 pb-0">
              <span style="font-size:9pt;font-weight:bold">List Riwayat</span>
              <div style="overflow-y:auto;" class="mt-1">
                <table class="tg table-tg" v-if="listTemplate.length > 0">
                  <thead>
                    <tr>
                      <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;" width="15%">Tanggal Input</td>
                      <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;" width="15%">Tanggal Registrasi</td>
                      <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;" width="15%">No Registrasi</td>
                      <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;" width="15%">No EMR</td>
                      <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;" width="20%">Dokter</td>
                      <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;" width="15%">Section</td>
                      <td style="vertical-align: middle;text-align: center;border: 1px solid black;font-weight: bold;" width="5%">#</td>
                    </tr>
                  </thead>
                  <tbody v-for="resep in listTemplate">
                    <tr>
                      <td style="width:5%;text-align:center;border:1px solid black;vertical-align: middle;">
                        <span class="mb-2">{{ resep.created_at }}</span><br>
                      </td>
                      <td style="width:5%;text-align:center;border:1px solid black;vertical-align: middle;">
                        <span class="mb-2">{{ resep.registrasi.tglregistrasi }}</span><br>
                      </td>
                      <td style="width:5%;text-align:center;border:1px solid black;vertical-align: middle;">
                        <span class="mb-2">{{ resep.registrasi.noregistrasi }}</span><br>
                      </td>
                      <td style="width:5%;text-align:center;border:1px solid black;vertical-align: middle;">
                        <span class="mb-2">{{ resep.pasien.nocm }}</span><br>
                      </td>
                      <td style="width:5%;text-align:center;border:1px solid black;vertical-align: middle;">
                        <span class="mb-2">{{ resep.dpjpUtama }}</span><br>
                      </td>
                      <td style="width:5%;text-align:center;border:1px solid black;vertical-align: middle;">
                        <span class="mb-2">{{ resep.registrasi.namaruangan }}</span><br>
                      </td>
                      <td style="width:5%;text-align:center;border:1px solid black;vertical-align: middle;">
                        <VIconButton type="button" raised circle icon="fas fa-plus" @click="addRiwayat(resep)"
                          color="info" v-tooltip-prime.top="'Pilih'">
                        </VIconButton>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </form>
        </template>
      </VModal>

      <VModal :open="showModalTemplateFix" title="Template" :noclose="true" size="large" actions="right"
        @close="isAlltemplate = false; showModalTemplateFix = false">
        <template #content>
          <DataTable :pt="{
            table: { style: 'min-width: 50rem; min-height: 10rem;' },
            column: {
              bodycell: ({ state }) => ({
                class: [{ 'pt-0 pb-0': state['d_editing'] }]
              })
            }
          }" v-model:filters="filtersTemplate" :value="listTemplateFix" :metaKeySelection="false" :rows="10" paginator
            tableStyle="min-width: 50rem" dataKey="no" :totalRecords="listTemplateFix.length"
            :globalFilterFields="['namatemplate', 'registrasi.namaruangan']" responsiveLayout="stack"
            breakpoint="960px">
            <template #header>
              <div class="columns is-multiline">
                <div class="column is-8">
                  <VField>
                    <InputText v-model="filtersTemplate['global'].value" placeholder="Search Nama Template" />
                  </VField>
                </div>
                <div class="column is-4"></div>
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
                  <VIconButton color="danger" light raised circle icon="lucide:x"
                    @click="deleteTemplate(slotProps.data.id)" v-if="!isAlltemplate" v-tooltip-prime.top="'Hapus'" />
                  <VIconButton type="button" raised circle icon="fas fa-plus" @click="addTemplate(slotProps.data)"
                    color="info" v-tooltip-prime.top="'Pilih'">
                  </VIconButton>
                  <VIconButton type="button" raised circle icon="fas fa-pencil-alt"
                    @click="editTemplate(slotProps.data)" color="info" v-tooltip-prime.top="'Edit'"
                    v-if="!isAlltemplate">
                  </VIconButton>
                </VButtons>
              </template>
            </Column>
            <Column field="namatemplate" header="Nama" :sortable="true"></Column>
            <!-- <Column field="registrasi.namaruangan" header="Nama Ruangan" :sortable="true">
              <template #body="slotProps">
                {{ slotProps.data.registrasi.namaruangan }}
              </template>
            </Column> -->
            <Column field="created_at" header="Tanggal" :sortable="true">
              <template #body="slotProps">
                <span>{{ H.formatDateToLocalString(slotProps.data.created_at) }}</span>
              </template>
            </Column>
          </DataTable>
        </template>
      </VModal>

      <div class="column">
        <div class="columns is-multiline">
          <div class="column is-12 buttons mb-0 mt-0" style="margin:10px;vertical-align:middle">
            <VButton type="button" rounded outlined color="primary" raised icon="feather:folder" isLoading="false"
              @click="pilihTemplateFix(index)"> Pilih Template
            </VButton>
            <VButton type="button" rounded outlined color="info" raised icon="feather:file-text" isLoading="false"
              @click="pilihTemplate(index)"> Pilih Riwayat
            </VButton>
          </div>

          <div class="column is-12 pt-0 pb-0">
            <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">
          </div>

          <div class="column is-12">
            <div class="columns">
              <div class="column is-12">
                <h1><b>Nama Template</b>&emsp;&emsp;<span style="color:red">**Hanya diisi jika ingin membuat
                    template</span></h1>
                <VField>
                  <VControl>
                    <VTextarea v-model="input.namatemplate" rows="1">
                    </VTextarea>
                  </VControl>
                </VField>
              </div>
            </div>
          </div>

        </div>

        <div class="column is-12 pt-0 pb-0">
          <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">
        </div>

        <div class="column is-12">
          <div class="column is-12 has-text-centered is-size-5">
            <span>Early detection of postnatal depression : development of the 10-item Edinburgh Postnatal Depression Scale.</span> <br>
            <span>Reproduced with permission from UNFPA</span>
          </div>

          <div class="column is-12">
            <div class="columns is-multiline">
              <div class="column is-4">
                <div class="columns is-multiline">
                  <div class="column is-2 mt-2">
                    <span>(Facility):</span>
                  </div>
                  <div class="column is-10">
                    <VControl>
                        <VInput type="text" class="input" v-model="input.Fasilitas" />
                    </VControl>
                  </div>
                </div>
              </div>
              <div class="column is-4">
                <div class="columns is-multiline">
                  <div class="column is-4 mt-2">
                    <span>Tanggal (Date):</span>
                  </div>
                  <div class="column is-8">
                    <VDatePicker v-model="input.tanggalEPDS" mode="datetime" trim-weeks>
                        <template #default="{ inputValue, inputEvents }">
                            <VControl icon="feather:calendar" fullwidth>
                                <VInput :value="inputValue" v-on="inputEvents" />
                            </VControl>
                        </template>
                    </VDatePicker>
                  </div>
                </div>
              </div>
              <div class="column is-4">
                <div class="columns is-multiline">
                  <div class="column is-4 mt-2">
                    <span>Ruangan (Location):</span>
                  </div>
                  <div class="column is-8">
                    <VControl class="prime-auto">
                        <AutoComplete v-model="input.RuanganEPDS" :suggestions="d_Ruangan"
                            @complete="fetchRuangan($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                            :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                            class="mt-2" />
                    </VControl>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="column is-12">
            <b>
              <span>
                Contoh:
              </span>
            </b> <br>
            <span>
              Bagaimana perasaan Anda?
            </span>
            <br>
            <span>
              Karena Anda akan/sedang hamil dan suatu saat akan melahirkan, kami ingin mengetahui bagaimana perasaan Anda. Silakan centang (v) jawaban yang paling mendekati dengan perasaan Anda selama 7 hari terakhir, tidak hanya yang Anda rasakan hari ini. Berikut salah satu contoh yang sudah dijawab:
            </span>
          </div>

          <div class="column is-12">
              <span>
                Saya merasa senang:
              </span>
              <VField>
                <VControl raw subcontrol>
                    <VCheckbox
                        class="p-0"
                        color="primary"
                        square
                        true-value="Ya, selalu setiap waktu"
                        label="Ya, selalu setiap waktu"
                        v-model="input.CBcontohSenang"
                    />
                </VControl>
              </VField>
              <VField>
                <VControl raw subcontrol>
                    <VCheckbox
                        class="p-0"
                        color="primary"
                        square
                        true-value="Ya, sering"
                        label="Ya, sering"
                        v-model="input.CBcontohSenang"
                    />
                </VControl>
              </VField>
              <VField>
                <VControl raw subcontrol>
                    <VCheckbox
                        class="p-0"
                        color="primary"
                        square
                        true-value="Tidak, tidak terlalu"
                        label="Tidak, tidak terlalu"
                        v-model="input.CBcontohSenang"
                    />
                </VControl>
              </VField>
              <VField>
                <VControl raw subcontrol>
                    <VCheckbox
                        class="p-0"
                        color="primary"
                        square
                        true-value="Tidak sama sekali"
                        label="Tidak sama sekali"
                        v-model="input.CBcontohSenang"
                    />
                </VControl>
              </VField> <br>
              <span>
                Hal ini dapat berarti : Sepanjang 7 hari yang lalu sampai sekarang, saya merasa senang "{{ input.CBcontohSenang == false || input.CBcontohSenang == undefined ? '' : input.CBcontohSenang }}".
              </span>
          </div>

          <div class="column is-12 pt-0 pb-0">
            <hr style="border-top: 1px dashed lightgray;background-color:white" class="mt-0 mb-1">
          </div>

          <div class="column is-12">
            <span>
                Silakan menjawab pernyataan-pernyataan berikut sebagaimana contoh di atas.
            </span>
          </div>

          <div class="column is-12 has-text-centered">
            <b>
              <span>
                Selama 7 hari terakhir
              </span>
            </b>
          </div>

          <div class="column is-12">
            <div class="columns is-multiline">
              <div class="column is-6">
                <div class="column is-12">
                  <span class="has-text-centered">
                    1. Saya dapat tertawa dan melihat segi kelucuan pada hal-hal tertentu <br>
                    ANHEDONIA
                  </span>
                  <VField class="mt-2">
                    <VControl raw subcontrol>
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            :true-value="0"
                            label="Seperti biasanya"
                            v-model="input.CBTertawa"
                        />
                    </VControl>
                  </VField>
                  <VField>
                    <VControl raw subcontrol>
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            :true-value="1"
                            label="Sekarang tidak terlalu sering"
                            v-model="input.CBTertawa"
                        />
                    </VControl>
                  </VField>
                  <VField>
                    <VControl raw subcontrol>
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            :true-value="2"
                            label="Sekarang agak jarang"
                            v-model="input.CBTertawa"
                        />
                    </VControl>
                  </VField>
                  <VField>
                    <VControl raw subcontrol>
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            :true-value="3"
                            label="Tidak sama sekali"
                            v-model="input.CBTertawa"
                        />
                    </VControl>
                  </VField>
                </div>
      
                <div class="column is-12">
                  <span class="has-text-centered">
                    2. Saya menanti-nanti untuk melakukan sesuatu dengan penuh harap: <br>
                    ANHEDONIA
                  </span>
                  <VField class="mt-2">
                    <VControl raw subcontrol>
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            :true-value="0"
                            label="Hampir seperti biasanya"
                            v-model="input.CBMelakukanSesuatu"
                        />
                    </VControl>
                  </VField>
                  <VField>
                    <VControl raw subcontrol>
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            :true-value="1"
                            label="Agak berkurang dari biasanya"
                            v-model="input.CBMelakukanSesuatu"
                        />
                    </VControl>
                  </VField>
                  <VField>
                    <VControl raw subcontrol>
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            :true-value="2"
                            label="Jelas kurang dari biasanya"
                            v-model="input.CBMelakukanSesuatu"
                        />
                    </VControl>
                  </VField>
                  <VField>
                    <VControl raw subcontrol>
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            :true-value="3"
                            label="Hampir tidak sama sekali"
                            v-model="input.CBMelakukanSesuatu"
                        />
                    </VControl>
                  </VField>
                </div>
      
                <div class="column is-12">
                  <span class="has-text-centered">
                    3. Saya menyalahkan diri sendiri jika ada sesuatu yang tidak berjalan dengan semestinya: <br>
                    ANXIETY
                  </span>
                  <VField class="mt-2">
                    <VControl raw subcontrol>
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            :true-value="3"
                            label="Ya, hampir selalu"
                            v-model="input.CBMenyalahkanDiri"
                        />
                    </VControl>
                  </VField>
                  <VField>
                    <VControl raw subcontrol>
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            :true-value="2"
                            label="Ya, kadang-kadang"
                            v-model="input.CBMenyalahkanDiri"
                        />
                    </VControl>
                  </VField>
                  <VField>
                    <VControl raw subcontrol>
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            :true-value="1"
                            label="Tidak terlalu sering"
                            v-model="input.CBMenyalahkanDiri"
                        />
                    </VControl>
                  </VField>
                  <VField>
                    <VControl raw subcontrol>
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            :true-value="0"
                            label="Tidak pernah"
                            v-model="input.CBMenyalahkanDiri"
                        />
                    </VControl>
                  </VField>
                </div>
      
                <div class="column is-12">
                  <span class="has-text-centered">
                    4. Saya merasa cemas atau kawatir tanpa alasan yang jelas: <br>
                    ANXIETY
                  </span>
                  <VField class="mt-2">
                    <VControl raw subcontrol>
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            :true-value="0"
                            label="Tidak, tidak sama sekali"
                            v-model="input.CBKhawatir"
                        />
                    </VControl>
                  </VField>
                  <VField>
                    <VControl raw subcontrol>
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            :true-value="1"
                            label="Hampir tidak pernah"
                            v-model="input.CBKhawatir"
                        />
                    </VControl>
                  </VField>
                  <VField>
                    <VControl raw subcontrol>
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            :true-value="2"
                            label="Ya, kadang-kadang"
                            v-model="input.CBKhawatir"
                        />
                    </VControl>
                  </VField>
                  <VField>
                    <VControl raw subcontrol>
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            :true-value="3"
                            label="Ya, amat sering"
                            v-model="input.CBKhawatir"
                        />
                    </VControl>
                  </VField>
                </div>
      
                <div class="column is-12">
                  <span class="has-text-centered">
                    5. Saya merasa takut atau panik tanpa alasan: <br>
                    ANXIETY
                  </span>
                  <VField class="mt-2">
                    <VControl raw subcontrol>
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            :true-value="3"
                            label="Ya, sering sekali"
                            v-model="input.CBPanik"
                        />
                    </VControl>
                  </VField>
                  <VField>
                    <VControl raw subcontrol>
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            :true-value="2"
                            label="Ya, Kadang-kadang"
                            v-model="input.CBPanik"
                        />
                    </VControl>
                  </VField>
                  <VField>
                    <VControl raw subcontrol>
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            :true-value="1"
                            label="Tidak, tidak terlalu"
                            v-model="input.CBPanik"
                        />
                    </VControl>
                  </VField>
                  <VField>
                    <VControl raw subcontrol>
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            :true-value="0"
                            label="Tidak, tidak pernah sama sekali"
                            v-model="input.CBPanik"
                        />
                    </VControl>
                  </VField>
                </div>
              </div>
              <div class="column is-6">
                <div class="column is-12">
                  <span class="has-text-centered">
                    6. Banyak hal yang menjadi beban untuk saya: <br>
                    ANHEDONIA
                  </span>
                  <VField class="mt-2">
                    <VControl raw subcontrol>
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            :true-value="3"
                            label="Ya, sering sekali saya sama sekali tidak dapat mengatasinya"
                            v-model="input.CBBeban"
                        />
                    </VControl>
                  </VField>
                  <VField>
                    <VControl raw subcontrol>
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            :true-value="2"
                            label="Ya, kadang saya tidak dapat mengatasinya seperti biasa"
                            v-model="input.CBBeban"
                        />
                    </VControl>
                  </VField>
                  <VField>
                    <VControl raw subcontrol>
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            :true-value="1"
                            label="Tidak terlalu, sebagian besar dapat saya atasi dengan balk"
                            v-model="input.CBBeban"
                        />
                    </VControl>
                  </VField>
                  <VField>
                    <VControl raw subcontrol>
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            :true-value="0"
                            label="Tidak pernah, saya selalu dapat mengatasinya dengan baik seperti biasanya"
                            v-model="input.CBBeban"
                        />
                    </VControl>
                  </VField>
                </div>
      
                <div class="column is-12">
                  <span class="has-text-centered">
                    7. Saya merasa tidak bahagia sehingga susah tidur: <br>
                    DEPRESSION
                  </span>
                  <VField class="mt-2">
                    <VControl raw subcontrol>
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            :true-value="3"
                            label="Ya, hampir selalu"
                            v-model="input.CBTidakBahagia"
                        />
                    </VControl>
                  </VField>
                  <VField>
                    <VControl raw subcontrol>
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            :true-value="2"
                            label="Ya, sering"
                            v-model="input.CBTidakBahagia"
                        />
                    </VControl>
                  </VField>
                  <VField>
                    <VControl raw subcontrol>
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            :true-value="1"
                            label="Tidak, tidak sering"
                            v-model="input.CBTidakBahagia"
                        />
                    </VControl>
                  </VField>
                  <VField>
                    <VControl raw subcontrol>
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            :true-value="0"
                            label="Tidak, tidak pernah"
                            v-model="input.CBTidakBahagia"
                        />
                    </VControl>
                  </VField>
                </div>
      
                <div class="column is-12">
                  <span class="has-text-centered">
                    8. Saya merasa sedih atau susah: <br>
                    DEPRESSION
                  </span>
                  <VField class="mt-2">
                    <VControl raw subcontrol>
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            :true-value="3"
                            label="Ya, hampir selalu"
                            v-model="input.CBSedih"
                        />
                    </VControl>
                  </VField>
                  <VField>
                    <VControl raw subcontrol>
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            :true-value="2"
                            label="Ya, sering"
                            v-model="input.CBSedih"
                        />
                    </VControl>
                  </VField>
                  <VField>
                    <VControl raw subcontrol>
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            :true-value="1"
                            label="Jarang"
                            v-model="input.CBSedih"
                        />
                    </VControl>
                  </VField>
                  <VField>
                    <VControl raw subcontrol>
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            :true-value="0"
                            label="Tidak pernah"
                            v-model="input.CBSedih"
                        />
                    </VControl>
                  </VField>
                </div>
      
                <div class="column is-12">
                  <span class="has-text-centered">
                    9. Saya merasa sangat tidak bahagia sehingga saya sering menangis: <br>
                    DEPRESSION
                  </span>
                  <VField class="mt-2">
                    <VControl raw subcontrol>
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            :true-value="3"
                            label="Ya, hampir selalu"
                            v-model="input.CBMenangis"
                        />
                    </VControl>
                  </VField>
                  <VField>
                    <VControl raw subcontrol>
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            :true-value="2"
                            label="Ya, sering"
                            v-model="input.CBMenangis"
                        />
                    </VControl>
                  </VField>
                  <VField>
                    <VControl raw subcontrol>
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            :true-value="1"
                            label="Hanya sekall-sekali"
                            v-model="input.CBMenangis"
                        />
                    </VControl>
                  </VField>
                  <VField>
                    <VControl raw subcontrol>
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            :true-value="0"
                            label="Tidak pernah"
                            v-model="input.CBMenangis"
                        />
                    </VControl>
                  </VField>
                </div>
      
                <div class="column is-12">
                  <span class="has-text-centered">
                    10. Pikiran untuk mencelakai diri sendiri sering muncul : <br>
                    DEPRESSION
                  </span>
                  <VField class="mt-2">
                    <VControl raw subcontrol>
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            :true-value="3"
                            label="Ya, cukup sering"
                            v-model="input.CBCelakaiSendiri"
                        />
                    </VControl>
                  </VField>
                  <VField>
                    <VControl raw subcontrol>
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            :true-value="2"
                            label="Kadang-kadang"
                            v-model="input.CBCelakaiSendiri"
                        />
                    </VControl>
                  </VField>
                  <VField>
                    <VControl raw subcontrol>
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            :true-value="1"
                            label="Hampir tidak pernah"
                            v-model="input.CBCelakaiSendiri"
                        />
                    </VControl>
                  </VField>
                  <VField>
                    <VControl raw subcontrol>
                        <VCheckbox
                            class="p-0"
                            color="primary"
                            square
                            :true-value="0"
                            label="Tidak pernah"
                            v-model="input.CBCelakaiSendiri"
                        />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
          </div>

          <div class="column is-12">
            <VField label="Total Score">
              <VControl>
                <VInput class="input" type="text" v-model="input.totalScore" readonly/>
              </VControl>
            </VField>
            <table class="table is-fullwidth is-bordered" style="">
              <thead>
                <tr :style="input.totalScore < 5 ? 'background: green;' : ''">
                  <td style="width: 100px;">0 - 4</td>
                  <td>Tidak ada risiko depresi</td>
                  <td>Lanjutkan dukungan.</td>
                </tr>
                <tr :style="input.totalScore <= 9 && input.totalScore >= 5  ? 'background: yellow;' : ''">
                  <td style="width: 100px;">5 - 9</td>
                  <td>Risiko rendah depresi</td>
                  <td>Evaluasi ulang setelah 2 minggu.</td>
                </tr>
                <tr :style="input.totalScore <= 12 && input.totalScore >= 10  ? 'background: red;' : ''">
                  <td style="width: 100px;">10 - 12</td>
                  <td>Risiko sedang depresi</td>
                  <td>
                    Monitor, dukung, dan tawarkan konseling dan edukasi. Pertimbangkan
                    merujuk/rawat
                    bersama psikolog klinis untuk memulai CBT ( <i>Cognitive Behavioral Therapy</i> ).
                  </td>
                </tr>
                <tr :style="input.totalScore > 12  ? 'background: red;' : ''">
                  <td style="width: 100px;">Lebih dari 12</td>
                  <td>Risiko tinggi Depresi</td>
                  <td>
                    Penilaian diagnostik dan pengobatan oleh psikolog klinis dan/atau
                    spesialis kesehatan jiwa.
                  </td>
                </tr>
                <tr :style="input.CBCelakaiSendiri && input.CBCelakaiSendiri > 0  ? 'background: red; color: white !important;' : ''">
                  <td style="width: 100px;">Nilai lebih dari '0' pada item No. 10</td>
                  <td></td>
                  <td>
                    Diperlukan kolaborasi segera. Rujuk ke psikolog klinik dan/atau
                    spesialis kesehatan jiwa untuk penilaian dan intervensi lebih lanjut juka diperlukan.
                    Tingkat urgensi rujukan akan bergantung pada : <br>
                    <ul style="list-style-type: disc; margin-left: 20px">
                      <li> apakah Ide bunuh diri disertai dengan rencana. </li>
                      <li> apakah pernah ada riwayat upaya bunuh diri. </li>
                      <li> apakah ada gejala gangguan psikotik dan/atau ada kekhawatiran akan membahayakan bayi. </li>
                    </ul>
                  </td>
                </tr>
              </thead>
            </table>
          </div>


        </div>

      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { useWindowScroll } from "@vueuse/core";
import { useApi } from "/@src/composable/useApi";
import { h, reactive, ref, computed, watch, onBeforeMount } from 'vue'
import { useRoute, useRouter, onBeforeRouteLeave } from 'vue-router'
import { useHead } from "@vueuse/head";
import * as H from "/@src/utils/appHelper";
import { useViewWrapper } from "/@src/stores/viewWrapper";
import { useUserSession } from "/@src/stores/userSession";
import AutoComplete from "primevue/autocomplete";
import MultiSelect from "primevue/multiselect";
import * as EMR from "../page-emr-plugins/kriteria-masuk-hcu";
import TandaTangan from "../page-emr-plugins/tanda-tangan.vue";
import Fieldset from "primevue/fieldset";
import { FilterMatchMode } from 'primevue/api';
import InputText from 'primevue/inputtext';
import Column from 'primevue/column'
import DataTable from 'primevue/datatable'
import BerkasPasienView from './berkas-pasien-preview.vue'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import Dialog from 'primevue/dialog';

useHead({ title: props.FORM_NAME + " - " + import.meta.env.VITE_PROJECT, });
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT);
useViewWrapper().setFullWidth(true);
const filtersTemplate = ref({ global: { value: null, matchMode: FilterMatchMode.CONTAINS } });
let ID_PASIEN = useRoute().query.nocmfk as string;
let NOREC_PD = useRoute().query.norec_pd as string;
let JenisKelamin = ref(EMR.JenisKelamin());
let kriteria: any = ref(EMR.kriteria());
const listTemplate: any = ref([])
const listTemplateFix: any = ref([])
const showModalTemplate: any = ref(false)
const showModalTemplateFix: any = ref(false)

const props = withDefaults(
  defineProps<{
    pasien?: any;
    registrasi?: any;
    FORM_NAME?: string;
    FORM_URL?: string;
  }>(),
  {
    pasien: {},
    registrasi: {},
    FORM_NAME: "",
    FORM_URL: "",
  }
);
const RiwayatPsikososial: any = ref([
  { label: "Baik", value: "Baik" },
  { label: "Tidak Baik", value: "Tidak Baik" },
]);

const { y } = useWindowScroll();
const isStuck = computed(() => {
  return y.value > 30;
});
const route = useRoute()
const isLoading: any = ref(false);
const isDisabled: any = ref(false);
const checkTemplate: any = ref(false)
const isLoadingVitalSign: any = ref(false);
const idTemplate: any = ref('');
const dataTTD: any = ref([]);
const d_Perawat: any = ref([]);
const d_Dokter: any = ref([]);
const d_Ruangan: any = ref([]);
const dataSource: any = ref([]);
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : "",
  NOREC_APD: "",
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  selectedMenu: [false],
});
const COLLECTION: any = ref("EdinburghPostnatalDepressionScale"); //table mongodb
const NOREC_EMRPASIEN: any = ref("");
const input: any = ref({
  tanggal: new Date(),
  totalScore: 0
});

const loadRiwayat = async () => {
  isLoading.value = true
  await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${NOREC_PD}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`).then(async (response: any) => {
    if (response.length) {
      isLoading.value = false
      input.value = response[0] //set ke inputan
      if (NOREC_EMRPASIEN.value == '') {
        NOREC_EMRPASIEN.value = response[0].emrpasienfk
      }

      // dataTTD.value = response[0]
      // H.tandaTangan().set("dpjpPasien", dataTTD.value.dpjpPasien)
      // H.tandaTangan().set("konsultanHemato", dataTTD.value.konsultanHemato)
      // H.tandaTangan().set("wadir", dataTTD.value.wadir)
    } else {
      await setAutoFill();
      isLoading.value = false
    }
  })
}

const simpan = () => {
  if (checkTemplate.value == true) {
      H.alert('warning', 'Simpan template ya, bukan simpan data :)')
      return;
  }

  let ID = input.value.id ? input.value.id : "";
  let object: any = {};

  object = input.value;
  delete object.namatemplate
  object.pasien = H.setObjectPasien(props.pasien);
  object.registrasi = H.setObjectRegistrasi(props.registrasi);
  // object['dpjpPasien'] = H.tandaTangan().get("dpjpPasien");
  // object['konsultanHemato'] = H.tandaTangan().get("konsultanHemato");
  // object['wadir'] = H.tandaTangan().get("wadir");
  let json = {
    id: ID,
    norec_emr: NOREC_EMRPASIEN.value,
    collection: COLLECTION.value,
    url_form: props.FORM_URL,
    name_form: props.FORM_NAME,
    jenis_emr: "asesmen_medis",
    data: object,
  };
  isLoading.value = true;
  useApi().post(`/emr/simpan-emr`, json).then((response: any) => {
    isLoading.value = false;
    checkTemplate.value = false
    loadRiwayat();
  }).catch((e: any) => {
    isLoading.value = false;
  });
}


const setAutoFill = async () => {
  input.value.RuanganEPDS = {value: props.registrasi.objectruanganfk, label: props.registrasi.namaruangan};
  input.value.tanggalEPDS = new Date();
}

const fetchRuangan = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Ruangan.value = response
  })
}
const fetchDokter = async (filter: any) => {
  await useApi().get(`emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10&query=${filter.query}`).then((response) => {
    d_Dokter.value = response
  })
}

const simpanTemplate = () => {
  if (!input.value.namatemplate) {
    H.alert('warning', "Nama Template wajib diisi")
    console.log()
    return;
  }
  let ID = idTemplate.value ? idTemplate.value : ''
  let object: any = {}

  object = input.value
  object.pasien = H.setObjectPasien(props.pasien)
  object.registrasi = H.setObjectRegistrasi(props.registrasi)
  let json = {
    'id': ID,
    'norec_emr': NOREC_EMRPASIEN.value,
    'collection': COLLECTION.value,
    'url_form': props.FORM_URL,
    'name_form': props.FORM_NAME,
    'jenis_emr': 'asesmen_medis',
    'data': object
  }
  isLoading.value = true

  useApi().post(`/emr/simpan-emr-template`, json).then((response: any) => {
    isLoading.value = false
    input.value.namatemplate = null
    delete object.namatemplate;
    delete object['_id'];
    delete object.pasien;
    delete object.registrasi;
  }).catch((e: any) => {
    isLoading.value = false
  })
}
const pilihTemplate = async (index: any) => {
  isLoading.value = true
  useApi().get(`/emr/get-emr-history-terakhir?nocmfk=${ID_PASIEN}&collection=${COLLECTION.value}`).then((responselast: any) => {
    isLoading.value = false
    if (responselast.length) {
      listTemplate.value = responselast //set ke inputan
      showModalTemplate.value = true
    } else {
      H.alert('warning', 'Data tidak ada')
    }
  })
}

const addTemplate = (response: any) => {
  const skipKeys = ['id', '_id', 'namatemplate','Fasilitas','tanggalEPDS','RuanganEPDS']; // Keys to be skipped

  for (const key in response) {
    if (!skipKeys.includes(key)) {
      input.value[key] = response[key]; // Only update allowed keys
    }
  }
  showModalTemplateFix.value = false
  H.alert('info', 'Template berhasil ditambahkan')
}

const addRiwayat = (response: any) => {
  input.value = response //set ke inputan
  delete input.value.namatemplate;
  delete input.value['_id'];
  showModalTemplate.value = false
  showModalTemplateFix.value = false
}

const pilihTemplateFix = async (index: any) => {
  isLoading.value = true
  useApi().get(`/emr/get-emr-template?collection=${COLLECTION.value}&isAll=true`).then((responselast: any) => {
    isLoading.value = false
    if (responselast.length) {
      for (var x = 0; x < responselast.length; x++) {
        responselast[x].no = x + 1
      }
      listTemplateFix.value = responselast //set ke inputan
      showModalTemplateFix.value = true
    } else {
      H.alert('warning', 'Data tidak ada')
    }
  })
}

const deleteTemplate = (idTemplate) => {
  isLoading.value = true
  let json = {
    'id': idTemplate,
    'collection': COLLECTION.value
  }
  useApi().post(`/emr/hapus-template`, json).then((response: any) => {
    if (response.status !== 500) {
      isLoading.value = false;
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
  showModalTemplateFix.value = false;
}
const editTemplate = async (dt: any) => {
  if (!dt) return;
  H.alert('info', 'Silahkan ubah data dan Simpan Template Kembali');
  input.value = dt;
  idTemplate.value = dt.id;
  showModalTemplateFix.value = false;
  input.value.namatemplate = dt.namatemplate;
  checkTemplate.value = true
}

watch(
  input, 
  (val) => {
    console.log('input.value');
    let skor = 0;
    if(input.value.CBTertawa) {
      skor += parseInt(input.value.CBTertawa)
    }
    if(input.value.CBMelakukanSesuatu) {
      skor += parseInt(input.value.CBMelakukanSesuatu)
    }
    if(input.value.CBMenyalahkanDiri) {
      skor += parseInt(input.value.CBMenyalahkanDiri)
    }
    if(input.value.CBKhawatir) {
      skor += parseInt(input.value.CBKhawatir)
    }
    if(input.value.CBPanik) {
      skor += parseInt(input.value.CBPanik)
    }
    if(input.value.CBBeban) {
      skor += parseInt(input.value.CBBeban)
    }
    if(input.value.CBTidakBahagia) {
      skor += parseInt(input.value.CBTidakBahagia)
    }
    if(input.value.CBSedih) {
      skor += parseInt(input.value.CBSedih)
    }
    if(input.value.CBMenangis) {
      skor += parseInt(input.value.CBMenangis)
    }
    if(input.value.CBCelakaiSendiri) {
      skor += parseInt(input.value.CBCelakaiSendiri)
    }
    console.log('skor', skor);
    input.value.totalScore = skor;
  },
  { deep: true  }
)

onBeforeMount(async () => {
    try {
        await loadRiwayat()
        let cache = H.cacheEMR().get(`TAB~${props.registrasi.noregistrasi}~${route.name}`)
        if (cache) input.value = cache
    } catch (error) {
        console.error('Error mount cache TAB EMR:', error);
    }
});

onBeforeRouteLeave((to, from, next) => {
    try {
        let rouutename = from?.name
        H.cacheEMR().set(`TAB~${props.registrasi.noregistrasi}~${rouutename}`, input.value)
    } catch (error) {
        console.error('Error leave cache TAB EMR:', error);
    }
    next();
});
</script>
