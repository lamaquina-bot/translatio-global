import { defineConfig } from 'astro/config';
import tailwind from '@astrojs/tailwind';

export default defineConfig({
  site: 'https://translatio.thefuckinggoat.cloud',
  i18n: {
    defaultLocale: 'es',
    locales: ['es', 'en', 'pt', 'zh', 'fr'],
    routing: {
      prefixDefaultLocale: false,
    },
  },
  integrations: [tailwind()],
  output: 'static',
});
