
<template>
    <VCard>
        <div class="columns">
            <Transition name="translate-page-y" mode="in-out">
                <div class="column is-12">
                    <div class="form-layout is-separate">
                        <div class="form-outer">
                            <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
                                <div class="form-header-inner">
                                    <div class="left">
                                        <h3>Pasien Baru</h3>
                                    </div>
                                    <div class="right">
                                        <div class="buttons">
                                            <!-- :to="{ name: 'sidebar-layouts-profile-view' }" -->
                                            <VButton icon="lnir lnir-arrow-left rem-100"
                                                :to="{ name: 'module-registrasi-pasien-lama' }" light dark-outlined>
                                                Cancel
                                            </VButton>
                                            <VButton type="button" color="primary" raised @click="savePasien()"> Save
                                            </VButton>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-body">

                                <div class="form-section">
                                    <div class="form-section-inner has-padding-bottom">
                                        <h3 class="has-text-centered">Demografi</h3>
                                        <div class="columns is-multiline">

                                            <div class="column is-6">
                                                <VField>
                                                    <VLabel>NIK</VLabel>
                                                    <VControl icon="feather:book">
                                                        <VInput type="text" v-model="item.nik" placeholder="" />
                                                    </VControl>
                                                </VField>
                                            </div>
                                            <div class="column is-6">
                                                <VField>
                                                    <VLabel>No BPJS</VLabel>
                                                    <VControl icon="feather:book">
                                                        <VInput type="text" v-model="item.nobpjs" placeholder="" />
                                                    </VControl>
                                                </VField>
                                            </div>
                                            <div class="column is-12">
                                                <VField>
                                                    <VLabel>Nama Pasien</VLabel>
                                                    <VControl icon="feather:user">
                                                        <VInput type="text" v-model="item.namapasien" placeholder="" />
                                                    </VControl>
                                                </VField>
                                            </div>
                                            <div class="column is-6">
                                                <VField>
                                                    <VLabel>Tempat Lahir</VLabel>
                                                    <VControl icon="feather:map-pin">
                                                        <VInput type="text" v-model="item.tempatlahir" placeholder="" />
                                                    </VControl>
                                                </VField>
                                            </div>
                                            <div class="column is-6">

                                                <VDatePicker v-model="item.tglahir" color="green" trim-weeks>
                                                    <template #default="{ inputValue, inputEvents }">
                                                        <VField>
                                                            <VLabel>Tgl Lahir</VLabel>
                                                            <VControl icon="feather:calendar">
                                                                <VInput type="text" placeholder="Select a date"
                                                                    :value="inputValue" v-on="inputEvents" />
                                                            </VControl>
                                                        </VField>
                                                    </template>
                                                </VDatePicker>
                                            </div>

                                            <div class="column is-12">
                                                <VField>
                                                    <VLabel>Jenis Kelamin</VLabel>
                                                    <VControl>
                                                        <div class="columns">
                                                            <div class="column is-12" v-if="listJK.length == 0">
                                                                <VPlaceloadText :lines="1" />
                                                            </div>
                                                            <div class="column is-6" v-for="items in listJK"
                                                                :key="items.id">
                                                                <VRadio v-model="item.jenisKel" :value="items.id"
                                                                    :label="items.jeniskelamin" name="{{items.id}}"
                                                                    square color="primary" />
                                                            </div>
                                                        </div>

                                                    </VControl>
                                                </VField>
                                            </div>
                                            <div class="column is-6">
                                                <VField class="is-autocomplete-select" v-slot="{ id }">
                                                    <VLabel>Agama</VLabel>
                                                    <VControl icon="feather:search">
                                                        <Multiselect mode="single" v-model="item.agama"
                                                            :options="listAgama" placeholder="Pilih data"
                                                            :searchable="true" :attrs="{ id }" />
                                                    </VControl>
                                                </VField>
                                            </div>
                                            <div class="column is-6">
                                                <VField class="is-autocomplete-select" v-slot="{ id }">
                                                    <VLabel>Status Perkawinan</VLabel>
                                                    <VControl icon="feather:search">
                                                        <Multiselect mode="single" v-model="item.statusPerkawinan"
                                                            :options="listStatusPerkawinan" placeholder="Pilih data"
                                                            :searchable="true" :attrs="{ id }" />
                                                    </VControl>
                                                </VField>
                                            </div>
                                            <div class="column is-6">
                                                <VField class="is-autocomplete-select" v-slot="{ id }">
                                                    <VLabel>Golongan Darah</VLabel>
                                                    <VControl icon="feather:search">
                                                        <Multiselect mode="single" v-model="item.golDar"
                                                            :options="listGolonganDarah" placeholder="Pilih data"
                                                            :searchable="true" :attrs="{ id }" />
                                                    </VControl>
                                                </VField>
                                            </div>
                                            <div class="column is-6">
                                                <VField class="is-autocomplete-select" v-slot="{ id }">
                                                    <VLabel>Pendidikan</VLabel>
                                                    <VControl icon="feather:search">
                                                        <Multiselect mode="single" v-model="item.pendidikan"
                                                            :options="listPendidikan" placeholder="Pilih data"
                                                            :searchable="true" :attrs="{ id }" />
                                                    </VControl>
                                                </VField>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-section">
                                    <div class="form-section-inner has-padding-bottom">
                                        <h3 class="has-text-centered">Geografi</h3>
                                        <div class="columns is-multiline">
                                            <div class="column is-12">
                                                <VField>
                                                    <VLabel>Alamat Lengkap</VLabel>
                                                    <VControl>
                                                        <VTextarea v-model="item.alamat" rows="4"
                                                            placeholder="Alamat Lengkap">
                                                        </VTextarea>
                                                    </VControl>
                                                </VField>
                                            </div>
                                            <div class="column is-12">
                                                <VField>
                                                    <VLabel>No HP</VLabel>
                                                    <VControl icon="feather:phone">
                                                        <VInput type="text" v-model="item.nohp" placeholder="" />
                                                    </VControl>
                                                </VField>
                                            </div>
                                            <div class="column is-12">
                                                <VField>
                                                    <VLabel>Email </VLabel>
                                                    <VControl icon="feather:mail">
                                                        <VInput type="email" v-model="item.email" placeholder=""
                                                            inputmode="email" />
                                                    </VControl>
                                                </VField>
                                            </div>
                                            <div class="column is-6">
                                                <VField class="is-autocomplete-select" v-slot="{ id }">
                                                    <VLabel>Pekerjaan</VLabel>
                                                    <VControl icon="feather:search">
                                                        <Multiselect mode="single" v-model="item.pekerjaan"
                                                            :options="listPekerjaan" placeholder="Pilih data"
                                                            :searchable="true" :attrs="{ id }" />
                                                    </VControl>
                                                </VField>
                                            </div>

                                            <div class="column is-6">
                                                <VField class="is-autocomplete-select" v-slot="{ id }">
                                                    <VLabel>Etnis</VLabel>
                                                    <VControl icon="feather:search">
                                                        <Multiselect mode="single" v-model="item.suku"
                                                            :options="listEtnis" placeholder="Pilih data"
                                                            :searchable="true" :attrs="{ id }" />
                                                    </VControl>
                                                </VField>
                                            </div>
                                            <div class="column is-6">
                                                <VField class="is-autocomplete-select" v-slot="{ id }">
                                                    <VLabel>Kebangsaan</VLabel>
                                                    <VControl icon="feather:search">
                                                        <Multiselect mode="single" v-model="item.kebangsaan"
                                                            :options="listKebangsaan" placeholder="Pilih data"
                                                            :searchable="true" :attrs="{ id }" />
                                                    </VControl>
                                                </VField>
                                            </div>
                                            <div class="column is-6">
                                                <VField class="is-autocomplete-select" v-slot="{ id }">
                                                    <VLabel>Negara</VLabel>
                                                    <VControl icon="feather:search">
                                                        <Multiselect mode="single" v-model="item.negara"
                                                            :options="listNegara" placeholder="Pilih data"
                                                            :searchable="true" :attrs="{ id }" />
                                                    </VControl>
                                                </VField>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- <div class="form-section">
                        <div class="form-section-inner">
                          <h3 class="has-text-centered">Delivery</h3>
                          <div class="columns is-multiline">
                            <div class="column is-12">
                              <VField>
                                <VLabel>Delivery Fee</VLabel>
                                <VControl>
                                  <div class="radio-boxes">
                                    <VControl class="radio-box" subcontrol>
                                      <VInput type="radio" name="delivery_type" />
                                      <div class="radio-box-inner">
                                        <div class="fee">
                                          <span>0</span>
                                        </div>
                                        <p>3-4 weeks</p>
                                      </div>
                                    </VControl>
                                    <VControl class="radio-box">
                                      <VInput type="radio" name="delivery_type" checked />
                                      <div class="radio-box-inner">
                                        <div class="fee">
                                          <span>5</span>
                                        </div>
                                        <p>2-5 days</p>
                                      </div>
                                    </VControl>
                                  </div>

                                  <p>
                                    <span>Estimated delivery date: <span>Oct 23</span></span>
                                    <span>Each package has a tracking number</span>
                                  </p>
                                </VControl>
                              </VField>
                            </div>
                          </div>
                        </div>

                        <div class="form-section-outer">
                          <div class="checkboxes">
                            <VField>
                              <VFlex column-gap="1rem">
                                <VControl>
                                  <VCheckbox v-model="createAccount" label="Create an account" color="primary" circle />
                                </VControl>
                                <VControl subcontrol>
                                  <VCheckbox v-model="subscribe" label="Subscribe to our Newsletter" color="primary"
                                    circle />
                                </VControl>
                              </VFlex>
                            </VField>
                          </div>
                          <div class="button-wrap">
                            <VButton type="submit" color="primary" bold raised fullwidth>
                              Confirm My Order
                            </VButton>
                          </div>
                        </div>
                      </div> -->

                            </div>
                        </div>
                    </div>
                </div>
            </Transition>
        </div>
    </VCard>
</template>
<route lang="yaml">
meta:
  requiresAuth: true
</route>
<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { reactive, ref, computed, defineComponent, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import type { VFlexTableWrapperDataResolver } from '/@src/components/base/table/VFlexTableWrapper.vue'
import { useHead } from '@vueuse/head'
import { useToaster } from '/@src/composable/toaster'
import { useViewWrapper } from '/@src/stores/viewWrapper'
useHead({
    title: 'Pasien - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
const date = ref(new Date())
const item: any = {}
let listJK: any = ref([])
let listAgama: any = ref([])
let listGolonganDarah: any = ref([])
let listStatusPerkawinan: any = ref([])
let listPendidikan: any = ref([])
let listPekerjaan: any = ref([])
let listEtnis: any = ref([])
let listKebangsaan: any = ref([])
let listNegara: any = ref([])
let isLoading = ref(false)
const { y } = useWindowScroll()
const isStuck = computed(() => {
    return y.value > 30
})

async function listDropdown() {
    const response = await useApi().get(
        `/registrasi/list-dropdown`)
    listJK.value = []
    for (let x = 0; x < response.jk.length; x++) {
        const element = response.jk[x];
        if (element.jeniskelamin != '-') {
            listJK.value.push(element)
        }
    }
    listAgama.value = response.agama.map((e): any => { return { label: e.agama, value: e.id } })
    listGolonganDarah.value = response.golongandarah.map((e): any => { return { label: e.golongandarah, value: e.id } })
    listStatusPerkawinan.value = response.statusperkawinan.map((e): any => { return { label: e.statusperkawinan, value: e.id } })
    listPendidikan.value = response.pendidikan.map((e): any => { return { label: e.pendidikan, value: e.id } })
    listPekerjaan.value = response.pekerjaan.map((e): any => { return { label: e.pekerjaan, value: e.id } })
    listEtnis.value = response.etnis.map((e): any => { return { label: e.suku, value: e.id } })
    listKebangsaan.value = response.kebangsaan.map((e): any => { return { label: e.name, value: e.id } })
    listNegara.value = response.negara.map((e): any => { return { label: e.namanegara, value: e.id } })

}
async function savePasien() {
    if (!item.nik) {
        useToaster().error('NIK harus di isi')
        return
    }
    if (!item.nobpjs) {
        useToaster().error('No BPJS harus di isi')
        return
    }
    if (!item.namapasien) {
        useToaster().error('Nama harus di isi')
        return
    }
    let json = item
    isLoading.value = true
    const { data: response } = await useApi().post(
        `/registrasi/save-pasien`, json)
    isLoading.value = false
}
function resetForm() {

}



listDropdown()

</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';

@import '/@src/scss/components/forms-outer';

.fs-075 {
    font-size: 0.9rem;
}

.page-content-wrapper {
    width: 100%;
    max-width: 100%;
    margin: 0 auto;
}

.is-navbar {
    .form-layout {
        margin-top: 30px;
    }
}

.form-layout {
    width: 100%;
    margin: 0 auto;

    &.is-separate {
        // max-width: 1040px;

        .form-outer {
            background: none;
            border: none;

            .form-body {
                display: flex;

                .form-section {
                    flex-grow: 2;
                    padding: 10px;
                    width: 50%;

                    .form-section-inner {
                        @include vuero-s-card;

                        padding: 40px;

                        &.has-padding-bottom {
                            padding-bottom: 60px;
                            height: 100%;
                        }

                        >h3 {
                            font-family: var(--font-alt);
                            font-size: 1.2rem;
                            font-weight: 600;
                            color: var(--dark-text);
                            margin-bottom: 30px;
                        }

                        .columns {
                            .column {
                                padding-top: 0.25rem;
                                padding-bottom: 0.25rem;
                            }
                        }

                        .radio-boxes {
                            display: flex;
                            justify-content: space-between;
                            margin-left: -8px;
                            margin-right: -8px;

                            .radio-box {
                                position: relative;
                                width: calc(50% - 16px);
                                margin: 8px;

                                &:focus-within {
                                    border-radius: 3px;
                                    outline-offset: var(--accessibility-focus-outline-offset);
                                    outline-width: var(--accessibility-focus-outline-width);
                                    outline-style: var(--accessibility-focus-outline-style);
                                    outline-color: var(--primary);
                                }

                                input {
                                    position: absolute;
                                    top: 0;
                                    left: 0;
                                    height: 100%;
                                    width: 100%;
                                    opacity: 0;
                                    cursor: pointer;

                                    &:checked {
                                        +.radio-box-inner {
                                            background: var(--primary);
                                            border-color: var(--primary);
                                            box-shadow: var(--primary-box-shadow);

                                            .fee,
                                            p {
                                                color: var(--smoke-white);
                                            }
                                        }
                                    }
                                }

                                .radio-box-inner {
                                    background: var(--white);
                                    border: 1px solid var(--fade-grey-dark-3);
                                    text-align: center;
                                    border-radius: var(--radius);
                                    font-family: var(--font);
                                    font-weight: 600;
                                    font-size: 0.9rem;
                                    transition: color 0.3s, background-color 0.3s, border-color 0.3s,
                                        height 0.3s, width 0.3s;
                                    padding: 30px 20px;

                                    .fee {
                                        font-family: var(--font);
                                        font-weight: 700;
                                        color: var(--dark-text);
                                        font-size: 2.4rem;
                                        line-height: 1;

                                        span {
                                            &::after {
                                                content: '$';
                                                position: relative;
                                                top: -10px;
                                                font-size: 1.5rem;
                                            }
                                        }
                                    }

                                    p {
                                        font-family: var(--font-alt);
                                    }
                                }
                            }
                        }

                        .control {
                            >p {
                                padding-top: 12px;

                                >span {
                                    display: block;
                                    font-size: 0.9rem;

                                    span {
                                        font-weight: 500;
                                        color: var(--dark-text);
                                    }
                                }
                            }
                        }
                    }

                    .form-section-outer {
                        .checkboxes {
                            padding: 16px 0;

                            .checkbox {
                                padding: 0;
                                font-size: 0.9rem;
                            }
                        }

                        .button-wrap {
                            .button {
                                min-height: 60px;
                                font-size: 1.05rem;
                                font-weight: 600;
                                font-family: var(--font-alt);
                            }
                        }
                    }
                }
            }
        }
    }
}

.is-dark {
    .form-layout {
        &.is-separate {
            .form-outer {
                background: none !important;

                .form-body {
                    .form-section {
                        .form-section-inner {
                            @include vuero-card--dark;

                            >h3 {
                                color: var(--dark-dark-text);
                            }

                            .radio-boxes {
                                .radio-box {
                                    input:checked+.radio-box-inner {
                                        background: var(--primary);
                                        border-color: var(--primary);
                                        box-shadow: var(--primary-box-shadow);

                                        .fee,
                                        p {
                                            color: var(--smoke-white);
                                        }
                                    }

                                    .radio-box-inner {
                                        background: var(--dark-sidebar-light-2);
                                        border-color: var(--dark-sidebar-light-12);

                                        .fee {
                                            color: var(--dark-dark-text);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}

@media only screen and (max-width: 767px) {
    .form-layout {
        &.is-separate {
            .form-outer {
                .form-body {
                    padding-left: 0;
                    padding-right: 0;
                    flex-direction: column;

                    .form-section {
                        width: 100%;

                        .form-section-inner {
                            padding: 30px;
                        }
                    }
                }
            }
        }
    }
}

@media only screen and (min-width: 768px) and (max-width: 1024px) and (orientation: portrait) {
    .form-layout {
        &.is-separate {
            .form-outer {
                .form-body {
                    padding-left: 0;
                    padding-right: 0;

                    // flex-direction: column;

                    .form-section {
                        // width: 100%;

                        .form-section-inner {
                            padding: 30px;
                        }
                    }
                }
            }
        }
    }
}
</style>
