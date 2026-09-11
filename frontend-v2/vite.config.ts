import { defineConfig } from 'vite'
import path from 'path'
import Vue from '@vitejs/plugin-vue'
import Pages from 'vite-plugin-pages'
import generateSitemap from 'vite-plugin-pages-sitemap'
import Components from 'unplugin-vue-components/vite'
import ViteFonts from 'vite-plugin-fonts'
import ViteRadar from 'vite-plugin-radar'
import PurgeIcons from 'vite-plugin-purge-icons'
// import { imagetools } from 'vite-imagetools'
// import ImageMin from 'vite-plugin-imagemin'
import VueroDocumentation from './vite-plugin-vuero-doc/index'
import { vueI18n } from '@intlify/vite-plugin-vue-i18n'
import { VitePWA } from 'vite-plugin-pwa'
import purgecss from 'rollup-plugin-purgecss'

const SILENT = Boolean(process.env.SILENT) ?? false
const SOURCE_MAP = Boolean(process.env.SOURCE_MAP) ?? false
const SITEMAP_HOST = process.env.SITEMAP_HOST || 'http://localhost:3000/'
/**
 * Base path aplikasi.
 * - Default `/` (dijalankan langsung, mis. http://localhost:2222/).
 * - Set ke `/simrs/` bila aplikasi dipasang di sub-folder / reverse proxy,
 *   mis. http://localhost/simrs/
 *
 * @example
 *   VITE_BASE_PATH=/simrs/ npm run dev
 *   VITE_BASE_PATH=/simrs/ npm run build
 */
const BASE_PATH = process.env.VITE_BASE_PATH || '/'
/**
 * Plugin optimasi gambar (`vite-imagetools`/sharp dan `vite-plugin-imagemin`)
 * membutuhkan binary native yang diunduh saat `npm install`.
 * Di mesin/CI tanpa akses unduh binary, set `SKIP_IMAGE_PLUGINS=true` agar
 * konfigurasi tetap bisa dimuat (gambar tidak dioptimasi).
 *
 * @example
 *   SKIP_IMAGE_PLUGINS=true npm run dev
 */
const SKIP_IMAGE_PLUGINS = process.env.SKIP_IMAGE_PLUGINS === 'true'

/** Muat plugin gambar secara malas; lewati bila binary-nya tidak tersedia. */
function loadImagePlugins(): any[] {
  if (SKIP_IMAGE_PLUGINS) {
    console.warn('[vite.config] SKIP_IMAGE_PLUGINS=true — optimasi gambar dilewati.')
    return []
  }
  try {
    /* eslint-disable @typescript-eslint/no-var-requires */
    const { imagetools } = require('vite-imagetools')
    const imagemod = require('vite-plugin-imagemin')
    const ImageMin = imagemod.default || imagemod
    return [
      imagetools({ silent: SILENT }),
      ImageMin({
        verbose: !SILENT,
        gifsicle: { optimizationLevel: 7, interlaced: false },
        optipng: { optimizationLevel: 7 },
        mozjpeg: { quality: 60 },
        pngquant: { quality: [0.8, 0.9], speed: 4 },
        svgo: {
          plugins: [
            { name: 'removeViewBox', active: false },
            { name: 'removeEmptyAttrs', active: false },
          ],
        },
      }),
    ]
  } catch (error) {
    console.warn(
      '[vite.config] Plugin optimasi gambar tidak dapat dimuat (binary native belum terpasang).',
      (error as Error).message
    )
    console.warn('[vite.config] Jalankan dengan SKIP_IMAGE_PLUGINS=true untuk melewati peringatan ini.')
    return []
  }
}

/**
 * This is the main configuration file for vitejs
 *
 * @see https://vitejs.dev/config
 */

export default defineConfig({
  // Project root directory (where index.html is located).
  root: process.cwd(),
  // Base public path when served in development or production.
  // Nilai diambil dari env VITE_BASE_PATH (default '/'), sehingga aplikasi bisa
  // dipasang di sub-folder tanpa mengubah kode. `src/router.ts` otomatis memakai
  // `import.meta.env.BASE_URL` sebagai base router.
  base: BASE_PATH,
  // Directory to serve as plain static assets.
  publicDir: 'public',
  // Adjust console output verbosity.
  logLevel: SILENT ? 'error' : 'info',
  /**
   * By default, Vite will crawl your index.html to detect dependencies that
   * need to be pre-bundled. If build.rollupOptions.input is specified,
   * Vite will crawl those entry points instead.
   *
   * @see https://vitejs.dev/config/#optimizedeps-entries
   */
  server: {
    hmr: true,
    watch: {
      usePolling: true,
    }
  },
  test: {
    silent:true,
  },
  optimizeDeps: {
    include: [
      '@ckeditor/ckeditor5-vue',
      '@ckeditor/ckeditor5-build-classic',
      '@iconify/iconify',
      '@mapbox/mapbox-gl-geocoder/dist/mapbox-gl-geocoder.min.js',
      '@vueuse/core',
      '@vueuse/head',
      '@vueform/multiselect',
      '@vueform/slider',
      'axios',
      'billboard.js',
      'dayjs',
      'dropzone',
      'dragula',
      'filepond',
      'filepond-plugin-file-validate-size',
      'filepond-plugin-file-validate-type',
      'filepond-plugin-image-exif-orientation',
      'filepond-plugin-image-crop',
      'filepond-plugin-image-edit',
      'filepond-plugin-image-preview',
      'filepond-plugin-image-resize',
      'filepond-plugin-image-transform',
      'imask',
      'nprogress',
      'notyf',
      'mapbox-gl',
      'photoswipe/dist/photoswipe',
      'photoswipe/dist/photoswipe-ui-default',
      'plyr',
      'v-calendar',
      'vee-validate',
      'vue',
      'vue-scrollto',
      'vue3-apexcharts',
      'vue-tippy',
      'simplebar',
      'simple-datatables',
      'tiny-slider/src/tiny-slider',
      'vue-accessible-color-picker',
    ],
    exclude: ['primevue'],
  },
  // Will be passed to @rollup/plugin-alias as its entries option.
  resolve: {
    alias: [
      {
        find: '/@src/',
        replacement: `/src/`,
      },
    ],
  },
  build: {
    minify: true,
    sourcemap: SOURCE_MAP,
    // Turning off brotliSize display can slightly reduce packaging time
    brotliSize: !SILENT,
    chunkSizeWarningLimit: Infinity,
    // minify: true,

    /**
     * Uncomment this section to build the demo with missing images
     * Don't forget to remove this section when you replaced assets with yours
     */
    rollupOptions: {
      external: [/\/demo\/.*/],
    },
  },
  plugins: [
    /**
     * plugin-vue plugin inject vue library and allow sfc files to work (*.vue)
     *
     * @see https://github.com/vitejs/vite/tree/main/packages/plugin-vue
     */
    Vue({
      include: [/\.vue$/],
    }),

    /**
     * vite-plugin-vue-i18n plugin does i18n resources pre-compilation / optimizations
     *
     * @see https://github.com/intlify/bundle-tools/tree/main/packages/vite-plugin-vue-i18n
     */
    vueI18n({
      include: path.resolve(__dirname, './src/locales/**'),
    }),

    /**
     * vite-plugin-pages plugin generate routes based on file system
     *
     * @see https://github.com/hannoeru/vite-plugin-pages
     */
    Pages({
      pagesDir: [
        {
          dir: 'src/pages',
          baseRoute: '',
        },
      ],
      onRoutesGenerated: (routes) =>
        generateSitemap({
          routes,
          hostname: SITEMAP_HOST,
        }),
    }),

    /**
     * This is an internal vite plugin that load markdown files as vue components.
     *
     * @see /documentation
     * @see /vite-plugin-vuero-doc
     * @see /src/components/partials/documentation/DocumentationItem.vue
     * @see /src/composable/useMarkdownToc.ts
     */
    VueroDocumentation(),

    /**
     * unplugin-vue-components plugin is responsible of autoloading components
     * documentation and md file are loaded for elements and components sections
     *
     * @see https://github.com/antfu/unplugin-vue-components
     */
    Components({
      dirs: ['documentation', 'src/components', 'src/layouts'],
      extensions: ['vue', 'md'],
      dts: true,
      include: [/\.vue$/, /\.vue\?vue/, /\.md$/],
    }),

    /**
     * vite-plugin-purge-icons plugin is responsible of autoloading icones from multiples providers
     *
     * @see https://icones.netlify.app/
     * @see https://github.com/antfu/purge-icons/tree/main/packages/vite-plugin-purge-icons
     */
    PurgeIcons(),

    /**
     * vite-plugin-fonts plugin inject webfonts from differents providers
     *
     * @see https://github.com/stafyniaksacha/vite-plugin-fonts
     */
    ViteFonts({
      google: {
        families: [
          {
            name: 'Fira Code',
            styles: 'wght@400;600',
          },
          {
            name: 'Montserrat',
            styles: 'wght@500;600;700;800;900',
          },
          {
            name: 'Roboto',
            styles: 'wght@300;400;500;600;700',
          },
        ],
      },
    }),

    /**
     * vite-plugin-radar plugin inject snippets from analytics providers
     *
     * @see https://github.com/stafyniaksacha/vite-plugin-radar
     */
    ViteRadar({
      enableDev: true,
      gtm: {
        id: 'GTM-N9P6H6B',
      },
    }),

    /**
     * vite-plugin-pwa generate manifest.json and register services worker to enable PWA
     *
     * @see https://github.com/antfu/vite-plugin-pwa
     */
    VitePWA({
      base: BASE_PATH,
      includeAssets: ['favicon.svg', 'favicon.ico', 'robots.txt', 'apple-touch-icon.png'],
      manifest: {
        name: 'Transmedic - Apps',
        short_name: 'transmedic',
        start_url: '/?utm_source=pwa',
        display: 'standalone',
        theme_color: '#ffffff',
        background_color: '#ffffff',
        icons: [
          {
            src: 'pwa-192x192.png',
            sizes: '192x192',
            type: 'image/png',
          },
          {
            src: 'pwa-512x512.png',
            sizes: '512x512',
            type: 'image/png',
          },
          {
            src: 'pwa-512x512.png',
            sizes: '512x512',
            type: 'image/png',
            purpose: 'any maskable',
          },
        ],
      },
      injectRegister: 'auto',
    }),

    /**
     * rollup-plugin-purgecss plugin is responsible of purging css rules
     * that are not used in the bundle
     *
     * @see https://github.com/FullHuman/purgecss/tree/main/packages/rollup-plugin-purgecss
     */
    purgecss({
      content: [`./src/**/*.vue`],
      variables: false,
      safelist: {
        standard: [
          /(autv|lnil|lnir|fas?)/,
          /-(leave|enter|appear)(|-(to|from|active))$/,
          /^(?!(|.*?:)cursor-move).+-move$/,
          /^router-link(|-exact)-active$/,
          /data-v-.*/,
        ],
      },
      defaultExtractor(content) {
        const contentWithoutStyleBlocks = content.replace(/<style[^]+?<\/style>/gi, '')
        return contentWithoutStyleBlocks.match(/[A-Za-z0-9-_/:]*[A-Za-z0-9-_/]+/g) || []
      },
    }),

    /**
     * Plugin optimasi gambar (vite-imagetools/`imagetools` + vite-plugin-imagemin/
     * `ImageMin`) dimuat secara malas lewat loadImagePlugins() karena membutuhkan
     * binary native (sharp/imagemin). Lewati dengan SKIP_IMAGE_PLUGINS=true.
     *
     * @see https://github.com/JonasKruckenberg/vite-imagetools
     * @see https://github.com/anncwb/vite-plugin-imagemin
     */
    ...loadImagePlugins(),
  ],
})
