import React, { useRef, useState, useEffect, useContext } from "react";
import OrderDetailCard from "../components/common/OrderDetailCard";
import OrderSummaryCard from "../components/common/OrderSummaryCard";
import OrderType from "../components/ConfirmationComponents/common/OrderType";
import UserForm from "../components/ConfirmationComponents/form/UserForm";
import Pagination from "../components/ConfirmationComponents/ui/Pagination";
import PaymentMethods from "../components/ConfirmationComponents/common/PaymentMethods";
import Actions from "../components/ConfirmationComponents/common/Actions";
import UserInfoSummary from "../components/ConfirmationComponents/common/UserInfoSummary";
import { useNetworkStatus } from "../hooks/offlineFirst/useNetworkStatus";
import { useDispatch } from "react-redux";
import { syncOfflineOrders } from "../store/reducers/orderSlice";
import { saveOrder } from "../store/reducers/orderSlice";
import { FormDataContext } from "../contexts/FormDataContext";
import Receipt80mm from "../components/ui/Receipt80mm";
import { calculateTotals } from "../utils/calculateTotals";
import { useSelectedProducts } from "../contexts/SelectedProductsContext";
import { deleteDraft } from "../store/reducers/draftSlice";
import { useNavigate } from "react-router-dom";
import { useProducts } from "../contexts/ProductsContext";
import notify from "../hooks/Notification";

export default function OrderDetailsPage() {
  const invoiceSettings = JSON.parse(localStorage.getItem("Invoice Settings"))
  const navigate = useNavigate();
  const [currentPage, setCurrentPage] = useState(1);
  const [selectedPayment, setSelectedPayment] = useState(invoiceSettings?.defaultPaymentMethod);
  const [selectedOrder, setSelectedOrder] = useState(invoiceSettings?.defaultInvoiceType);
  const [phone, setPhone] = useState("");
  const [address, setAddress] = useState("");
  const formRef = useRef();
  const dispatch = useDispatch();
  const [error, setError] = useState("");
  const [remainingValue, setRemainingValue] = useState(0);
  const receiptRef = useRef();
  const [invoice, setInvoice] = useState(null);
  const [payload, setPayload] = useState(null);
  const now = new Date();
  const { selectedProducts, total, setSelectedProducts } =
    useSelectedProducts();
  const { formData, setFormData } = useContext(FormDataContext);
  const { setDraftFormData, setInvSerial } = useProducts();
  const { handleNew } = useProducts();

  const { subtotal } = calculateTotals(selectedProducts);

  useNetworkStatus();

  useEffect(() => {
    const handleOnline = () => dispatch(syncOfflineOrders());
    window.addEventListener("online", handleOnline);
    return () => window.removeEventListener("online", handleOnline);
  }, [dispatch]);

  const goToNextPage = () => {
    if (currentPage < 3) {
      setCurrentPage((prev) => prev + 1);
    }
  };
  const goToPrevPage = () => {
    if (currentPage > 1) {
      setCurrentPage((prev) => prev - 1);
    }
  };

  const printReceipt = (data) => {
    receiptRef?.current?.printReceipt(data || invoice);
  };

  const paymentMethodMap = {
    كاش: "cash",
    "بطاقة ائتمان": "credit_card",
    "انستا باى": "instapay",
    اجل: "remaining",
    محفظة: "wallet",
  };

  const convertedPaymentMethods = Object.entries(formData.paymentMethods || {})
    .filter(([_, value]) => value && Number(value) > 0)
    .map(([arabicName, value]) => ({
      key: paymentMethodMap[arabicName],
      amount: Number(value),
    }));

  const handleSubmitWithPrinting = async () => {
   try{
 
    // formRef.current?.submitForm();
    const paymentMethods = formData.paymentMethods || {};
    const hasPrice = Object.values(paymentMethods).some(
      (val) => val !== "" && Number(val) > 0,
    );
    if (!hasPrice) {
      setError("يجب إدخال قيمة لطريقة دفع واحدة على الأقل قبل إتمام الطلب");
      return;
    }
    if (remainingValue < 0) {
      setError("يجب دفع القيمة المطلوبة");
      return;
    }
    setError("");
    const isValid = await formRef?.current?.validateForm();
    if (isValid) {
      formRef?.current?.submitForm();
      const payload = {
        payment_methods:  convertedPaymentMethods,
        // payment_method: "cash",
        cashier_id: Number(formData.cashier_id) || 1,

        products: selectedProducts.map((product) => ({
          id: product.id,
          unit_conversion_factor: product.unit_conversion_factor ?? 1,
          quantity: product.number,
        })),
      };
      if (formData.address1?.trim()) {
  payload.address = formData.address1.trim();
}
      const returnedInvoice = await dispatch(saveOrder(payload));
      await receiptRef.current?.printReceipt();

      const invoiceData = {
        id: returnedInvoice?.id,
        type: "invoice",
        invoice: {
          userInfo: formData,
          items: selectedProducts,
          subTotal: subtotal,
          timestamp: `${String(now.getHours()).padStart(2, "0")}:${String(now.getMinutes()).padStart(2, "0")} ${now.getFullYear()}/${String(now.getMonth() + 1).padStart(2, "0")}/${String(now.getDate()).padStart(2, "0")}`,
        },
      };

      setInvoice(invoiceData);
      setPayload(returnedInvoice);
      let backendSerial = returnedInvoice?.id ? returnedInvoice?.id : 1;
      setInvSerial(backendSerial);
      localStorage.setItem("Invoice Serial", JSON.stringify(backendSerial || 1));
      // console.log(returnedInvoice.id)
      await dispatch(deleteDraft(invoiceData?.invoice?.userInfo?.serialInput));
      handleNew();
      sessionStorage.removeItem("selectedUser");
      sessionStorage.removeItem("draftFormData");
      sessionStorage.removeItem("Selected Products");
      setSelectedProducts([]);

      let date = new Date();
      date = `${date.getFullYear()}-${date.toLocaleDateString("en-US", {
        month: "2-digit",
      })}-${date.toLocaleDateString("en-US", {
        day: "2-digit",
      })}`;
      sessionStorage.removeItem("FormData");

      setDraftFormData({
        serialInput: ++backendSerial || "",
        dateInput: date || "",
        clientName: "",
        notes: "",
        paymentMethod: invoiceSettings?.defaultPaymentMethod,
        paymentMethods: {},
        invoiceType: invoiceSettings?.defaultInvoiceType,
        phone1: "",
        newPhone: "",
        optionalPhone: "",
        address1: "",
        newAddress: "",
        optionalAddress: "",
      });
      setFormData({
        serialInput: ++backendSerial || "",
        dateInput: date || "",
        clientName: "",
        notes: "",
        paymentMethod: "كاش",
        paymentMethods: {},
        invoiceType: "تيك أواى",
        phone1: "",
        newPhone: "",
        optionalPhone: "",
        address1: "",
        newAddress: "",
        optionalAddress: "",
      });

      
      // navigate("/", { replace: true });
    }
   }
   catch(error){
    notify("حدث مشكلة يرجى المحاولة مرة أخرى", "error" || error.message)
   }
  };

  const handleSubmitWithoutPrinting = async () => {
   try{
 
    // formRef.current?.submitForm();
    const paymentMethods = formData.paymentMethods || {};
    const hasPrice = Object.values(paymentMethods).some(
      (val) => val !== "" && Number(val) > 0,
    );
    if (!hasPrice) {
      setError("يجب إدخال قيمة لطريقة دفع واحدة على الأقل قبل إتمام الطلب");
      return;
    }
    if (remainingValue < 0) {
      setError("يجب دفع القيمة المطلوبة");
      return;
    }
    setError("");
    const isValid = await formRef?.current?.validateForm();
    if (isValid) {
      formRef?.current?.submitForm();
      const payload = {
        payment_methods:  convertedPaymentMethods,
        // payment_method: "cash",
        cashier_id: Number(formData.cashier_id) || 1,

        products: selectedProducts.map((product) => ({
          id: product.id,
          unit_conversion_factor: product.unit_conversion_factor ?? 1,
          quantity: product.number || product.quantity,
        })),
      };
      if (formData.address1?.trim()) {
  payload.address = formData.address1.trim();
}
      const returnedInvoice = await dispatch(saveOrder(payload));

      const invoiceData = {
        id: returnedInvoice?.id,
        type: "invoice",
        invoice: {
          userInfo: formData,
          items: selectedProducts,
          subTotal: subtotal,
          timestamp: `${String(now.getHours()).padStart(2, "0")}:${String(now.getMinutes()).padStart(2, "0")} ${now.getFullYear()}/${String(now.getMonth() + 1).padStart(2, "0")}/${String(now.getDate()).padStart(2, "0")}`,
        },
      };

      setInvoice(invoiceData);
      setPayload(returnedInvoice);
      let backendSerial = returnedInvoice?.id;
      setInvSerial(backendSerial);
      localStorage.setItem("Invoice Serial", JSON.stringify(backendSerial || 1));
      // console.log(returnedInvoice.id)
      await dispatch(deleteDraft(invoiceData?.invoice?.userInfo?.serialInput));
      handleNew();
      sessionStorage.removeItem("selectedUser");
      sessionStorage.removeItem("draftFormData");
      sessionStorage.removeItem("Selected Products");
      setSelectedProducts([]);

      let date = new Date();
      date = `${date.getFullYear()}-${date.toLocaleDateString("en-US", {
        month: "2-digit",
      })}-${date.toLocaleDateString("en-US", {
        day: "2-digit",
      })}`;
      sessionStorage.removeItem("FormData");

      setDraftFormData({
        serialInput: ++backendSerial || "",
        dateInput: date || "",
        clientName: "",
        notes: "",
        paymentMethod: invoiceSettings?.defaultPaymentMethod,
        paymentMethods: {},
        invoiceType: invoiceSettings?.defaultInvoiceType,
        phone1: "",
        newPhone: "",
        optionalPhone: "",
        address1: "",
        newAddress: "",
        optionalAddress: "",
      });
      setFormData({
        serialInput: ++backendSerial || "",
        dateInput: date || "",
        clientName: "",
        notes: "",
        paymentMethod: "كاش",
        paymentMethods: {},
        invoiceType: "تيك أواى",
        phone1: "",
        newPhone: "",
        optionalPhone: "",
        address1: "",
        newAddress: "",
        optionalAddress: "",
      });

      
      // navigate("/", { replace: true });
    }
   }
   catch(error){
    notify("حدث مشكلة يرجى المحاولة مرة أخرى", "error" || error.message)
   }
  };

  useEffect(() => {
    const handleKeyEnter = (e) => {
      if (e.key === "Enter") {
        handleSubmitWithPrinting();
      }
    };
    window.addEventListener("keydown", handleKeyEnter);
    return () => {
      window.removeEventListener("keydown", handleKeyEnter);
    };
  }, [handleSubmitWithPrinting]);

  useEffect(() => {
    const handleKeyEnter = (e) => {
      if (e.key === "F2") {
        handleSubmitWithoutPrinting();
      }
    };
    window.addEventListener("keydown", handleKeyEnter);
    return () => {
      window.removeEventListener("keydown", handleKeyEnter);
    };
  }, [handleSubmitWithoutPrinting]);

  return (
    <div className="w-full min-h-screen flex flex-col">
      <div className="w-full flex flex-col lg:flex-row flex-1">
        <div className="w-full lg:w-[65%] bg-blue-100 bg-opacity-30 h-full lg:overflow-y-auto pb-1 pt-2">
          <div className="flex flex-col mb-3 lg:h-[27rem]">
            <OrderType
              selectedOrder={selectedOrder}
              setSelectedOrder={setSelectedOrder}
              formData={formData}
            />
            <OrderDetailCard selectedProducts={selectedProducts} />
          </div>
          <OrderSummaryCard total={total} />
        </div>
        <div className="w-full lg:w-[35%] bg-white h-full pb-5 lg:pb-2 pt-2 flex flex-col justify-between">
          <div className="flex-1 ">
            <UserForm
              currentPage={currentPage}
              goToNextPage={goToNextPage}
              formData={formData}
              phone={phone}
              setPhone={setPhone}
              address={address}
              setAddress={setAddress}
              ref={formRef}
            />
            <PaymentMethods
              selectedPayment={selectedPayment}
              setSelectedPayment={setSelectedPayment}
              formData={formData}
              error={error}
              remainingValue={remainingValue}
              setRemainingValue={setRemainingValue}
            />
          </div>
          <Actions
            handleSubmitWithPrinting={handleSubmitWithPrinting}
            handleSubmitWithoutPrinting={handleSubmitWithoutPrinting}
          />
        </div>
      </div>

      <div style={{ position: "absolute", left: "-9999px", top: "-9999px" }}>
        <Receipt80mm ref={receiptRef} invoice={invoice} payload={payload} />
      </div>
    </div>
  );
}
