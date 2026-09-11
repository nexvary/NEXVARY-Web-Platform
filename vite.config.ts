import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
  plugins: [
    laravel({
      input: [
        'resources/css/app.css',
        'resources/js/app.tsx',
        'resources/css/public.css',
        'resources/css/command-center-v4.css',
        'resources/css/site-command-v9.css',
        'resources/js/public.ts',
        'resources/js/home-command-v4.ts',
      ],
      refresh: true,
    }),
    react(),
    tailwindcss(),
  ],
  build: { sourcemap: false },
});
