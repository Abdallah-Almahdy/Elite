import { useState, useEffect, useRef, useContext } from "react";
import { useSelector, useDispatch } from "react-redux";

import Actions from "../components/common/Actions";
import CategoryCard from "../components/common/CategoryCard";
import NumericKeypad from "../components/common/NumericKeypad";
import OrderSummary from "../components/orders/OrderSummary";
import OrderTable from "../components/orders/OrderTable";
import ProductGrid from "../components/products/ProductGrid";
import ProductsSearch from "../components/products/ProductsSearch";
import UserForm from "../forms/UserForm";
import Modal from "../components/confirm/Modal";
import { useUIPreferences } from "../contexts/UIPreferencesContext";
import { OrbitProgress } from "react-loading-indicators";
import { useNavigate } from "react-router-dom";
import { FormDataContext } from "../contexts/FormDataContext";
import { useNetworkStatus } from "../hooks/offlineFirst/useNetworkStatus";
// import { syncOfflineDrafts } from "../store/reducers/draftSlice"
import { closeShift, syncOfflineUsers } from "../store/reducers/userSlice";
import PrintBarcodePage from "./PrintBarcodePage";
import { getOfflineDrafts } from "../services/indexedDB";
import { useProducts } from "../contexts/ProductsContext";
import SettingsPage from "./SettingsPage";
import { useInvoiceSettings } from "../contexts/InvoiceSettingsContext";
import UserSettingsPage from "./UserSettingsPage";
import { useUserSettingsPreference } from "../contexts/UserSettingsPreferenceContext";
import notify from "../hooks/Notification";
import ShiftModal from "../components/confirm/ShiftModal";
export default function Home() {
  const {
    selectedProducts,
    isPopupOpen,
    setSelectedProducts,
    total,
    handleFreeze,
    formRef,
    formData,
    draftFormData,
    setDraftFormData,
  } = useProducts();
  const [isModalOpen, setIsModalOpen] = useState(false);
    const [isShiftModalOpen, setIsShiftModalOpen] = useState(false);

  
  const { preference, updatePreference } = useUIPreferences();
  const { invoiceSetting, updateUserSettings } = useInvoiceSettings();
  const {passwordReq, errors, password, confirmPassword, printerName} =  useUserSettingsPreference();
  const loading = useSelector((state) => state.product.loading);
  const isFirstRun = useRef(true);
  const dispatch = useDispatch();

  const quantityRefs = useRef({});
  const backSpace = useRef(null);

  const navigate = useNavigate();
  useEffect(() => {
    const loadDraft = async () => {
      const draftId = draftFormData?.id;
      if (!draftId) return;

      const drafts = await getOfflineDrafts();
      const draft = drafts.find((d) => d.id === draftId);

      if (draft) {
        setDraftFormData(draft);
        setSelectedProducts(draft.items);
        sessionStorage.setItem("draftFormData", JSON.stringify(draft));
        sessionStorage.setItem(
          "Selected Products",
          JSON.stringify(draft.items),
        );
      }
    };

    loadDraft();
  }, [draftFormData?.id]);

  useEffect(() => {
    if (isFirstRun.current) {
      isFirstRun.current = false;
      return;
    }

    handleFreeze();
  }, [selectedProducts, formData, total]);

  useEffect(() => {
    let products =
      JSON.parse(sessionStorage.getItem("Selected Products")) || null;
    if (products) {
      setSelectedProducts(products);
    }
  }, []);
  useEffect(() => {
    sessionStorage.setItem(
      "Selected Products",
      JSON.stringify(selectedProducts),
    );
    sessionStorage.setItem("Total", JSON.stringify(total));
  }, [selectedProducts, total]);

  useNetworkStatus();
  useEffect(() => {
    const handleOnline = () => dispatch(syncOfflineUsers());
    window.addEventListener("online", handleOnline);
    return () => window.removeEventListener("online", handleOnline);
  }, [dispatch]);

  useEffect(() => {
    const newTotal = selectedProducts?.reduce(
      (acc, product) => acc + product?.total,
      0,
    );
  }, [selectedProducts]);

  useEffect(() => {
    const handleKeyDown = async (e) => {
      if (e.key === "F8") {
        const isValid = await formRef?.current?.validateForm();

        if (isValid) {
          formRef?.current?.submitForm();
          navigate("/order-details");
        }
      }
    };
    window.addEventListener("keydown", handleKeyDown);
    return () => {
      window.removeEventListener("keydown", handleKeyDown);
    };
  }, [formData]);

  useEffect(() => {
    if (localStorage.getItem("uiPreference") === null) {
      setIsModalOpen(true);
    }
  }, []);

  const handleModalConfirm = (value) => {
    setIsModalOpen(false);
    updatePreference(value);
  };
  const handleShiftModalClose = () => {
    setIsShiftModalOpen(false);
  };

  const handleConfirmCloseShift = (amount) =>{
    try{
      dispatch(closeShift({amount}));
    handleShiftModalClose()
    notify("تم إغلاق الوردية بنجاح", "success")
    }
    catch(err){
      notify("حدثت مشكلة فى اغلاق الورديه!", "warn")
    }
  }


  if (loading) {
    return (
      <div className="w-full h-screen flex justify-center items-center">
        <OrbitProgress
          variant="spokes"
          color="#2563eb"
          size="large"
          text=""
          textColor=""
        />
      </div>
    );
  }

  return (
    <>
      <Modal isOpen={isModalOpen} onConfirm={handleModalConfirm} />
      <ShiftModal isOpen={isShiftModalOpen} onClose={handleShiftModalClose} onConfirm={handleConfirmCloseShift}/>
      <div
        className={`w-full p-1 lg:pb-0 flex flex-col-reverse md:flex-row ${!isModalOpen ? `min-h-screen overflow-auto` : `h-screen overflow-hidden`}  ${!isPopupOpen ? `min-h-screen overflow-auto` : `h-screen overflow-hidden`}`}
      >
        {/* Right Section */}
        <div className="w-full md:w-[65%] flex flex-col border border-gray-300 px-2 pt-1 lg:pb-0">
          <UserForm ref={formRef} />
          <div className="w-full mt-2 sm:mt-1 ">
            <ProductsSearch />
          </div>
          <div className="w-full mt-2 sm:mt-1 flex-1">
            <OrderTable backSpace={backSpace} quantityRefs={quantityRefs} />
          </div>
          <div className="w-full">
            <OrderSummary />
          </div>
        </div>

        {/* Left Section */}
        <div className="w-full md:w-[42%] flex flex-col border border-gray-300  pt-1 pb-1 lg:pb-0">
          <div className="w-full flex mx-auto gap-x-1 pr-2 mt-1">
            <CategoryCard />
          </div>
          <div className="w-full flex mx-auto gap-x-1 px-2 mt-1 flex-1">
            <ProductGrid userPreference={preference} />
          </div>
          <div
            className={`w-full flex flex-col lg:flex-row justify-between items-center md:justify-center pl-2 ${preference === "textWrap" ? `mt-0` : preference === "largeWrap" ? `mt-1` : `mt-0`}`}
          >
            <div className="w-full flex items-center">
              <Actions setIsShiftModalOpen={setIsShiftModalOpen} />
            </div>
            <div>
              <NumericKeypad userPreference={preference} />
            </div>
          </div>
        </div>
      </div>
    </>
  );
}
