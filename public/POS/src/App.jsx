import { Routes, Route, useNavigate } from "react-router-dom";
import { UIPreferencesProvider } from "./contexts/UIPreferencesContext.jsx";
import Home from "./pages/Home";
import DraftPage from "./pages/DraftPage.jsx";
import { useState, useEffect, useContext } from "react";
import OrderDetailsPage from "./pages/OrderDetailsPage.jsx";
import {
  FormDataContext,
  FormDataProvider,
} from "./contexts/FormDataContext.jsx";
import { getDraftById } from "./services/indexedDB.js";
import {
  SelectedProductsProvider,
  useSelectedProducts,
} from "./contexts/SelectedProductsContext.jsx";
import { ProductsProvider, useProducts } from "./contexts/ProductsContext.jsx";
import PrintBarcodePage from "./pages/PrintBarcodePage.jsx";
import { ToastContainer } from "react-toastify";
import { useDispatch } from "react-redux";
// import { fetchProducts } from "./store/reducers/productSlice.js";
import { fetchCategory } from "./store/reducers/productSlice.js";
import { fetchClientsNames } from "./store/reducers/userSlice.js";

export default function App() {
  const { setSelectedProducts } = useSelectedProducts();
  const { setDraftFormData, draftFormData } = useProducts();
  const { setFormData } = useContext(FormDataContext);
  // const {setDraftFormData} = useProducts()
  const [draftData, setDraftData] = useState(null);
  const dispatch = useDispatch();
  const navigate = useNavigate();
  useEffect(() => {
    dispatch(fetchCategory());
  }, [dispatch]);

  useEffect(() => {
    dispatch(fetchClientsNames());
  }, [dispatch]);

  const handleReturn = async (id) => {
    const draft = await getDraftById(id);

    if (!draft) return;

    // clone the draft and its items
    const clonedDraft = {
      ...draft,
      items: draft.items ? JSON.parse(JSON.stringify(draft.items)) : [],
    };

    setDraftData(clonedDraft);
    setSelectedProducts(clonedDraft.items);

    setDraftFormData(clonedDraft);

    sessionStorage.setItem("draftFormData", JSON.stringify(clonedDraft));
    sessionStorage.setItem(
      "Selected Products",
      JSON.stringify(clonedDraft.items),
    );

    navigate("/");
    window.location.reload();
  };

  useEffect(() => {
    if (!draftFormData) return;

    setFormData((prev) => ({
      ...prev,
      serialInput: draftFormData?.serialInput ?? prev.serialInput,
      dateInput: draftFormData?.dateInput ?? prev.dateInput,
      clientName: draftFormData?.clientName ?? prev.clientName,
      notes: draftFormData?.notes ?? prev.notes,
      paymentMethod: draftFormData?.paymentMethod ?? prev.paymentMethod,
      paymentMethos: draftFormData?.paymentMethod ?? prev.paymentMethods,
      invoiceType: draftFormData?.invoiceType ?? prev.invoiceType,
      phone1: draftFormData?.phone1 ?? prev.phone1,
      newPhone: draftFormData?.newPhone ?? prev.newPhone,
      optionalPhone: draftFormData?.optionalPhone ?? prev.optionalPhone,
      address1: draftFormData?.address1 ?? prev.address1,
      newAddress: draftFormData?.newAddress ?? prev.newAddress,
      optionalAddress: draftFormData?.optionalAddress ?? prev.optionalAddress,
      warehouseName: draftFormData?.warehouseName ?? prev.warehouseName,
    }));
  }, [draftFormData]);

  return (
    <>
      <ToastContainer position="top-right" autoClose={3000} />

      <SelectedProductsProvider>
        <FormDataProvider>
          <ProductsProvider>
            <UIPreferencesProvider>
              <Routes>
                <Route path="/" element={<Home />} />
                <Route
                  path="/draft"
                  element={<DraftPage handleReturn={handleReturn} />}
                />
                <Route path="/order-details" element={<OrderDetailsPage />} />
                <Route path="/print-barcode" element={<PrintBarcodePage />} />
              </Routes>
            </UIPreferencesProvider>
          </ProductsProvider>
        </FormDataProvider>
      </SelectedProductsProvider>
    </>
  );
}
