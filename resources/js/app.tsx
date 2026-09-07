import '../css/app.css';
import '../css/stage160.css';
import '../css/auth.css';
import '../css/command-center.css';
import { createInertiaApp } from '@inertiajs/react';
import type { ComponentType } from 'react';
import { createRoot } from 'react-dom/client';

createInertiaApp({
  resolve: (name) => {
    const pages = import.meta.glob('./pages/**/*.tsx', { eager: true }) as Record<
      string,
      { default: ComponentType<any> }
    >;
    const page = pages[`./pages/${name}.tsx`];

    if (!page) {
      throw new Error(`Unknown Inertia page: ${name}`);
    }

    return page;
  },
  setup({ el, App, props }) {
    createRoot(el).render(<App {...props} />);
  },
});
