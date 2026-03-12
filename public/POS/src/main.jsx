import { createRoot } from 'react-dom/client'
import './index.css'
import App from './App.jsx'
import { BrowserRouter } from 'react-router-dom'
import { store } from './store/index.js'
import { Provider } from 'react-redux'
import { SelectedProductsProvider } from './contexts/SelectedProductsContext.jsx'
import { ProductsProvider } from './contexts/ProductsContext.jsx'
import { FormDataProvider } from './contexts/FormDataContext.jsx'

createRoot(document.getElementById("root")).render(
  <Provider store={store}>
    <BrowserRouter basename={import.meta.env.BASE_URL}>
      <FormDataProvider>
        <SelectedProductsProvider>
          <ProductsProvider>
            <App />
          </ProductsProvider>
        </SelectedProductsProvider>
      </FormDataProvider>
    </BrowserRouter>
  </Provider>,
);


