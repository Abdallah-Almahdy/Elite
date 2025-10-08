import React from 'react';
import { createRoot } from 'react-dom/client';
import { createInertiaApp } from '@inertiajs/react'; // ✅ updated import
import '../css/app.css';
import '../css/font.css';

createInertiaApp({
  resolve: (name) => import(`./Pages/${name}.jsx`),
  setup({ el, App, props }) {
    createRoot(el).render(<App {...props} />);
  },
});
