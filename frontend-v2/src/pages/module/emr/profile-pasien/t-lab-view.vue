<script setup lang="ts">
import { onceImageErrored } from '/@src/utils/via-placeholder'
import * as H from '/@src/utils/appHelper'
import { useApi } from '/@src/composable/useApi'
import { useRoute, useRouter } from 'vue-router'
const emit = defineEmits<{
    (e: 'gotoDetail', value: any): void,
}>()
const router = useRouter()
const props = withDefaults(
    defineProps<{
        title?: string
        straight?: boolean
        items?: any[]
        squared?: boolean
        colored?: boolean
        isPasienAktif?: boolean
        norec_pd?: string
        pasien?: any
    }>(),
    {
        title: 'List Widget',
        items: () => [],
    }
)
if (props.items.length) {
    for (let x = 0; x < props.items.length; x++) {
        const element = props.items[x];
        if (element.expertise != null || element.order_complete == 1) {
            element.isdetail = true
        }
    }
}
console.log(props.items)
const lihatHasil = (dataItem: any) => {
  // if (props.pasien.namapasien == 'NY. WIWIN WINARTI') {
  // }else{
  //   if (dataItem.order_complete == 0) {
  //       H.alert('warning', 'Hasil belum ada')
  //   } else {
  //       router.push({
  //           name: 'module-radiologi-hasil-pacs',
  //           query: {
  //               url: dataItem.url_pacs_hasil,
  //           },
  //       })
  //   }
  //   return
  // }


    if (dataItem.radiologiid === null || dataItem.radiologiid === '') {
        H.alert('warning', 'Hasil belum ada')
    } else {

        let viewer = null
        let patienIdMr = dataItem.radiologiid.replace('null', '1')
        dataItem.isLoading = true
        useApi().postNoMessage(`/general/api-tools`, {
            'method': 'get',
            'url': import.meta.env.VITE_URL_PACS_ENGINE + '/dcm4chee-arc/aets/TRANSMEDIC/rs/studies?limit=1&includefield=all&offset=0&PatientID=' + patienIdMr,
            'headers': {}
        }).then((response: any) => {
            dataItem.isLoading = false
            if (response.response == null) {
                H.alert('warning', 'Hasil foto belum dikirim ke PACS')
            } else {
                let data = response.response
                viewer = data[0]["0020000D"].Value[0]
                window.open(import.meta.env.VITE_URL_PACS_VIEWER
                    + "viewer/" + dataItem.objectruangantujuanfk
                    + "/" + dataItem.norec_pp
                    + "/" + props.norec_pd
                    + "/" + dataItem.noorder + "/" + viewer, "pacs");
            }
        })

    }

}
</script>

<template>
    <div class="project-files">
        <div class="list-widget" :class="[props.straight && 'is-straight']">
            <div class="widget-head" style="justify-content: space-between !important;">
                <h3 class="dark-inverted">{{ props.title }}</h3>
                <a v-if="props.isPasienAktif" class="action-link is-pulled-right" tabindex="0"
                    @click="$emit('gotoDetail')">View All</a>
            </div>

            <div class="inner-list panjang-250">

                <div class="icon-timeline mt-4-min">
                    <div v-for="item in props.items" :key="item.id" class=" mb-2">
                        <div :class="item.isdetail ? 'panjangaaaaaaa' : ''">
                            <div class="timeline-item" style="background-color: #EAF1FF; padding: 10px;border-radius: 10px"
                                :style="item.isdetail ? 'height:40%;border-radius: 10px 10px 0 0;' : ''">
                                <div class="timeline-icon is-clickable" @click="item.isdetail = !item.isdetail"
                                    :class="[props.squared && 'is-squared', props.colored && 'is-' + item.color]"
                                    style=" background:#193C88;  border-color:#193C88;">
                                    <!-- <i aria-hidden="true" class="iconify" :data-icon="item.icon"></i> -->
                                    <i aria-hidden="true" :class="item.icon"></i>
                                </div>
                                <div class="timeline-content" style="    margin-left: 10px;">
                                    <p>{{ item.namaproduk }}</p>
                                    <span>{{ item.tglorder }}</span>
                                </div>
                                <VTag :color="item.color_status" :label="item.status" />
                            </div>
                            <div v-if="item.isdetail == true"
                                style="border-radius: 0 0 10px 10px;background-color: #C8D3E9; padding: 10px;height:60%">
                                <div class="mt-1-min" style="    display: flex;"
                                    v-if="item.expertise != null || item.order_complete == 1">
                                    <div  style="width:50px;text-align:center;margin-right: 5px;" v-if="item.radiologiid == '00005448-1'"><img style="width:50px;height:40px"
                                            :src=" '/images/simrs/0000079.png'">
                                        <a href="https://app.rsjpparamarta.com/service/v-echo-00005448-1.html" class="action-link" tabindex="0" style="color:var(--danger);  font-size: 0.8rem;">Video </a>
                                    </div>
                                    <VButton color="info" icon="feather:eye" raised rounded @click="lihatHasil(item)"
                                        :loading="item.isLoading" class="btn-slim mt-3"> Hasil </VButton>
                                    <img style="width:30px;height:35px" class="ml-2 mt-2"
                                        :src="'/images/simrs/doc-rad.png'">
                                    <span class="mt-2 ml-2"
                                        style=" font-size: 0.8rem;font-style: inherit;font-weight: inherit;line-height: 1.1;height: 4.2em; width: 300px  !important; overflow: hidden !important;text-overflow: ellipsis;">
                                        {{ item.expertise ? item.expertise : 'Belum ada Expertise' }}
                                    </span>
                                </div>
                                <div v-else style="display: flex; flex-wrap: nowrap;">
                                    <span style="width:50px"></span>
                                    <img style="width:30px" :src="'/images/simrs/doc-rad.png'">
                                    <span class="mt-2 ml-2" style="
    font-size: 0.8rem;
    color: var(--light-text); font-style: inherit;
    font-weight: inherit;
">Belum ada Expertise</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style lang="scss">
@import '/@src/scss/abstracts/all';

.panjangaaaaaaa {
    height: 130px
}

.panjang-240 {
    height: 260px;
    overflow-x: hidden;
    overflow-y: auto;
}

.list-widget {

    @include vuero-l-card;

    padding: 30px;

    &:not(:last-child) {
        margin-bottom: 1.5rem;
    }

    &.is-straight {
        @include vuero-s-card;
        border-radius: 16px;
        // margin-top: 8px;
    }

    .widget-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        height: 32px;
        margin-bottom: 10px;

        h3 {
            color: var(--dark-text);
            font-size: 1.1rem;
            font-weight: 500;
        }
    }

    .inner-list {
        padding: 10px 0;

        .inner-list-item {
            +.inner-list-item {
                margin-top: 24px;
            }
        }
    }
}

.is-dark {
    .list-widget {
        @include vuero-card--dark;
    }
}

.list-widget {
    .icon-timeline {
        .timeline-item {
            position: relative;
            display: flex;
            padding-bottom: 30px;

            &::after {
                content: '';
                position: absolute;
                top: 36px;
                left: 18px;
                width: 1px;
                height: calc(100% - 36px);
                border-left: 1px solid var(--fade-grey-dark-3);
            }

            .timeline-icon {
                position: relative;
                // height: 36px;
                width: 56px;
                display: flex;
                justify-content: center;
                align-items: center;
                background: var(--white);
                border: 1px solid var(--fade-grey-dark-3);
                border-radius: var(--radius-rounded);
                color: var(--light-text);
                box-shadow: var(--light-box-shadow);

                &::after {
                    content: '';
                    position: absolute;
                    top: 17px;
                    left: 40px;
                    width: 20px;
                    height: 1px;
                    border-top: 1px solid var(--fade-grey-dark-3);
                }

                &.is-squared {
                    border-radius: 10px;

                    img {
                        border-radius: 10px;
                    }
                }

                &.is-primary {
                    background: var(--primary);
                    border-color: var(--primary);
                    box-shadow: var(--primary-box-shadow);

                    svg {
                        color: var(--smoke-white);
                    }
                }

                &.is-info {
                    background: var(--info);
                    border-color: var(--info);
                    box-shadow: var(--info-box-shadow);

                    svg {
                        color: var(--smoke-white);
                    }
                }

                &.is-success {
                    background: var(--success);
                    border-color: var(--success);
                    box-shadow: var(--success-box-shadow);

                    svg {
                        color: var(--smoke-white);
                    }
                }

                &.is-orange {
                    background: var(--orange);
                    border-color: var(--orange);
                    box-shadow: var(--orange-box-shadow);

                    svg {
                        color: var(--smoke-white);
                    }
                }

                &.is-yellow {
                    background: var(--yellow);
                    border-color: var(--yellow);

                    svg {
                        color: var(--smoke-white);
                    }
                }

                img {
                    display: block;
                    height: 28px;
                    width: 28px;
                    border-radius: var(--radius-rounded);
                }

                svg {
                    height: 16px;
                    width: 16px;
                    stroke-width: 1.6px;
                }
            }

            .timeline-content {
                margin-left: 34px;
                line-height: 1.2;

                span {
                    font-size: 0.8rem;
                    color: var(--light-text);
                }

                p {
                    font-family: var(--font-alt);
                    font-size: 0.75rem;
                    font-weight: 500;
                    color: var(--dark-text);
                    width: 260px;
                    white-space: nowrap;
                    overflow: hidden !important;
                    text-overflow: ellipsis;
                }
            }
        }
    }
}

.is-dark {
    .list-widget {
        .icon-timeline {
            .timeline-item {
                &::after {
                    border-color: var(--dark-sidebar-light-12) !important;
                }

                .timeline-icon:not(.is-primary):not(.is-info):not(.is-success):not(.is-orange):not(.is-yellow) {
                    background: var(--dark-sidebar-light-3) !important;
                    border-color: var(--dark-sidebar-light-12) !important;
                }

                .timeline-icon {
                    &::after {
                        border-color: var(--dark-sidebar-light-12) !important;
                    }

                    &.is-primary {
                        background: var(--primary);
                        border-color: var(--primary);
                        box-shadow: var(--primary-box-shadow);

                        svg {
                            color: var(--smoke-white);
                        }
                    }
                }

                .timeline-content {
                    p {
                        color: var(--dark-dark-text);
                    }
                }
            }
        }
    }
}

.list-widget .icon-timeline .timeline-item::after {
    content: none !important;

}

.list-widget .icon-timeline .timeline-item .timeline-icon::after {
    content: none !important;

}
</style>
