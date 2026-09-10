<template>
  <section>

    <div class="tile-grid tile-grid-v1" v-if="props.data.length > 0">
      <TransitionGroup name="list" tag="div" class="columns is-multiline">
        <div v-for="(item, key) in props.data" :key="key" :class="[props.hide ? 'column is-12':'column is-6']" >
          <div class="tile-grid-item">
            <div class="tile-grid-item-inner">
              <VAvatar picture="/images/icons/files/pdf.svg" size="small" @click="$emit('lihat', item)" />
              <div class="meta is-clickable" @click="$emit('lihat', item)"  v-tooltip-prime.right="'Lihat File'">
                <span class="dark-inverted">{{ item.namafile }}</span>
                <span v-if="item.nama != 'undefined'">{{ item.nama }}</span>
                <span style="color: #A2A5B9;" v-if="item.deskripsi != 'null'">{{ item.deskripsi }}</span>
                <span style="color: #A2A5B9;">{{ item.author }}</span>
                <span style="color: #A2A5B9;">{{ item.tglemr }}</span>
              </div>
              <VDropdown icon="feather:more-vertical" spaced right v-if="!props.hide">
                <template #content>
                  <a @click="$emit('lihat', item)" role="menuitem" class="dropdown-item is-media">
                    <div class="icon">
                      <i aria-hidden="true" class="lnil lnil-eye"></i>
                    </div>
                    <div class="meta">
                      <span>Lihat</span>
                    </div>
                  </a>
                  <a @click="$emit('edit', item)" role="menuitem" class="dropdown-item is-media">
                    <div class="icon">
                      <i aria-hidden="true" class="lnil lnil-pencil"></i>
                    </div>
                    <div class="meta">
                      <span>Edit</span>
                    </div>
                  </a>

                  <a @click="$emit('hapus', item)" role="menuitem" class="dropdown-item is-media">
                    <div class="icon">
                      <i aria-hidden="true" class="lnil lnil-trash"></i>
                    </div>
                    <div class="meta">
                      <span>Hapus</span>
                    </div>
                  </a>

                </template>
              </VDropdown>
            </div>
          </div>
        </div>
      </TransitionGroup>
    </div>

    <VPlaceholderPage v-else-if="props.data.length === 0" :title="H.assets().notFound"
      :subtitle="H.assets().notFoundSubtitle" larger>
      <template #image>
        <img class="light-image" :src="H.assets().iconNotFound_rev" alt="" />
        <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg" alt="" />
      </template>
    </VPlaceholderPage>
  </section>
</template>
<script setup lang="ts">
import * as H from '/@src/utils/appHelper'
import { useRoute, useRouter } from 'vue-router'
import { h, reactive, ref, computed, defineComponent, watch, PropType } from 'vue'
import { useThemeColors } from '/@src/composable/useThemeColors'
const router = useRouter()
const emit = defineEmits<{
  (e: 'lihat', value: any): void,
  (e: 'edit', value: any): void,
  (e: 'hapus', value: any): void,
}>()
const props = defineProps({
  data: {
    type: Array as PropType<any>,
  },
  hide: Boolean

})
console.log(props.data)
</script>

<style  lang="scss">
@import '/@src/scss/abstracts/all';

.tile-grid {
  .columns {
    margin-left: -0.5rem !important;
    margin-right: -0.5rem !important;
    margin-top: -0.5rem !important;
  }

  .column {
    padding: 0.5rem !important;
  }
}

.is-dark {
  .tile-grid {
    .tile-grid-item {
      @include vuero-card--dark;
    }
  }
}

.tile-grid-v1 {
  .tile-grid-item {
    @include vuero-s-card;

    border-radius: 14px;
    padding: 16px;

    .tile-grid-item-inner {
      display: flex;
      align-items: center;

      .meta {
        margin-left: 10px;
        line-height: 1.2;

        span {
          display: block;
          font-family: var(--font);

          &:first-child {
            color: var(--dark-text);
            font-family: var(--font-alt);
            font-weight: 600;
            font-size: 1rem;
          }

          &:nth-child(2) {
            color: var(--light-text);
            font-size: 0.9rem;
          }
        }
      }

      .dropdown {
        position: relative;
        margin-left: auto;
      }
    }
  }
}
</style>
