
<template>
    <form class="form-layout is-separate" @submit.prevent="onSubmit">
      <div class="form-outer">
        <div class="form-body">
          <div class="form-section">
            <div class="form-section-inner has-padding-bottom">
              <h3 class="has-text-centered">Informasi Pasien</h3>
              <div class="columns is-multiline">
                <div class="column is-12">
                  <VField>
                    <VLabel>Nama Pasien</VLabel>
                    <VControl icon="feather:user">
                      <VInput type="text" v-model="item.namapasien" autocomplete="given-name" disabled/>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6">
                  <VField>
                    <VLabel>Nomor Registrasi</VLabel>
                    <VControl icon="feather:user">
                      <VInput type="text" v-model="item.noregistrasi" autocomplete="family-name" disabled/>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6">
                  <VField>
                    <VLabel>Kelompok Pasien</VLabel>
                    <VControl icon="feather:home">
                      <VInput type="text" v-model="item.namaruangan" autocomplete="family-name" disabled/>
                    </VControl>
                  </VField>
                </div>
                <div class="column is-12">
                  <VField>
                    <VLabel>Tanggal Pulang</VLabel>
                    <VControl icon="feather:map-pin">
                      <VInput type="text" v-model="item.tglPulang" autocomplete="family-name" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6">
                  <VField>
                    <VLabel>Kelas Rawat</VLabel>
                    <VControl icon="feather:bookmark">
                      <VInput type="text" v-model="item.kelasRawat" autocomplete="family-name" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6">
                  <VField>
                    <VLabel>Kelas Penjamin</VLabel>
                    <VControl icon="feather:bookmark">
                      <VInput type="text" v-model="item.kelasPenjamin" autocomplete="family-name" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6">
                  <VField>
                    <VLabel>Nomor Asuransi</VLabel>
                    <VControl icon="feather:bookmark">
                      <VInput type="text" v-model="item.noAsuransi" autocomplete="family-name" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-6">
                  <VField>
                    <VLabel>Nomor Telepon</VLabel>
                    <VControl icon="feather:phone">
                      <VInput type="text" v-model="item.nohp" autocomplete="family-name" />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
          </div>
          <div class="form-section">
            <div class="form-section-inner">
              <h3 class="has-text-centered">Detail Tagihan</h3>
              <div class="columns is-multiline">
                <div class="column is-12">
                  <VField>
                    <VLabel>Tagihan </VLabel>
                    <VControl>
                      <div class="radio-boxes">
                        <VControl class="radio-box" subcontrol>
                          <VInput type="radio" name="delivery_type" />
                          <div class="radio-box-inner">
                            <div class="fee">
                              <span>Layanan</span>
                            </div>
                          </div>
                        </VControl>
                        <VControl class="radio-box">
                          <VInput type="radio" name="delivery_type" checked />
                          <div class="radio-box-inner">
                            <div class="fee">
                              <span>Resep</span>
                            </div>
                          </div>
                        </VControl>
                      </div>
  
                      <p>
                        <span>Total Billing: <span>Rp. {{ formatRp(item.jumlahBayar, '') }}</span></span>
                        
                      </p>
                    
                    </VControl>
                  </VField>
                  <div class="checkboxes">
                    
                    <p>
                      <span>Total Klaim </span>
                    </p>
                  
                <VField>
                  <VFlex >
                    <VControl>
                      <VCheckbox
                        v-model="createAccount"
                        label="Multi Penjamin"
                        color="primary"
                        circle
                      />
                    </VControl>
                    <VControl subcontrol>
                      <VCheckbox
                        v-model="subscribe"
                        label="Diskon"
                        color="primary"
                        circle
                      />
                    </VControl>
                    <VControl subcontrol>
                      <VCheckbox
                        v-model="subscribe"
                        label="Iur Bayar"
                        color="primary"
                        circle
                      />
                    </VControl>
                  </VFlex>
                </VField>
                <div class="column is-12">
                  <VField>
                    <VControl icon="fas fa-money-check">
                      <VInput type="text" v-model="item.cek" autocomplete="family-name" />
                    </VControl>
                  </VField>
                </div>
                
              </div>
              <p>
                <span>Jumlah Bayar : <span>Rp 00</span></span>
              </p>
                </div>
              </div>
            </div>
            
            <div class="form-section-outer">
              <div class="checkboxes">
                <VField>
                  <VFlex column-gap="1rem">
                   
                  </VFlex>
                </VField>
              </div>
              <div class="button-wrap" column-gap="1rem">
                <VButton type="submit" color="primary" bold raised fullwidth>
                  Bayar Tagihan
                </VButton>
              </div>
            </div>
          </div>
        </div>
      </div>
    </form>
  </template>
  
  <script setup lang="ts">
  import { ref } from 'vue'
  import { useRoute, useRouter } from 'vue-router'
  import { useApi } from '/@src/composable/useApi'
  import { useUserSession } from '/@src/stores/userSession'
  import { useHead } from '@vueuse/head'
  import { formatRp } from '/@src/utils/appHelper'
  import { useViewWrapper } from '/@src/stores/viewWrapper'
  useHead({
      title: 'Pembayaran Pasien  - Transmedic',
  })
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
  useViewWrapper().setFullWidth(true)
  
  const country = ref('')
  const createAccount = ref(true)
  const subscribe = ref(false)
  const onSubmit = () => {
    console.log('Form submitted!')
  }
  let NOREC_PD = useRoute().query.norec_pd as string
    let dataSource: any = ref([])
    const isLoadingPasien: any = ref(false)
    const pasien: any = ref({})
    const kasir: any = ref({})
    const item: any = ref({
      NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
      NOREC_APD: '',
      registrasi: {},
      tglorder: new Date(),
      produkCeklis: [],
      pegawaiOrder: useUserSession().getUser().id,
    })
    const isDetail: any = ref(false)
  
    async function detailPasien() {
    await useApi().get(
    `/kasir/detail-tagihan?norec_pd=${NOREC_PD}`).then((response: any) => {
      item.value = response
    })
  }
  
  detailPasien()
  
  </script>
  
  <style lang="scss">
  @import '/@src/scss/abstracts/all';
  
  .is-navbar {
    .form-layout {
      margin-top: 30px;
    }
  }
  
  .form-layout {
    max-width: 740px;
    margin: 0 auto;
  
    &.is-separate {
      max-width: 1040px;
  
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
  
              > h3 {
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
                      + .radio-box-inner {
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
                    font-size: 1rem;
                    transition: color 0.3s, background-color 0.3s, border-color 0.3s,
                      height 0.3s, width 0.3s;
                    padding: 30px 20px;
  
                    .fee {
                      font-family: var(--font);
                      font-weight: 700;
                      color: var(--dark-text);
                      font-size: 1rem;
                      line-height: 1;
  
                      span {
                        &::after {
                          position: relative;
                          top: -10px;
                          font-size: 0.5rem;
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
                > p {
                  padding-top: 12px;
  
                  > span {
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
  
                > h3 {
                  color: var(--dark-dark-text);
                }
  
                .radio-boxes {
                  .radio-box {
                    input:checked + .radio-box-inner {
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
  