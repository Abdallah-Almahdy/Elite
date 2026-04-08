import React, { createContext, useState, useEffect } from "react";
import { useSelector, useDispatch } from "react-redux";
import { setSelectedUser } from "../store/reducers/userSlice";
import notify from "../hooks/Notification";
import { fetchConfigs } from "../store/reducers/settingSlice";
export const FormDataContext = createContext();

export const FormDataProvider = ({ children }) => {
  const savedData = JSON.parse(sessionStorage.getItem("FormData")) || {};
  const dispatch = useDispatch();

  // const draftFormData = JSON.parse(sessionStorage.getItem("draftFormData"));
  const [draftFormData, setDraftFormData] = useState(
    JSON.parse(sessionStorage.getItem("draftFormData")),
  );
  // const invoiceSettings = JSON.parse(localStorage.getItem("Invoice Settings"))
  useEffect(() => {
    dispatch(fetchConfigs());
  }, [dispatch]);
  const invoiceSettings = useSelector(
    (state) => state?.setting?.invoiceSettings,
  );
  const warehouseName = useSelector((state) => state?.setting?.warehouseName);

  const paymentMapping = {
    cash: "كاش",
    credit_card: "بطاقة ائتمان",
    instapay: "انستا باى",
    wallet: "محفظة",
    remaining: "اجل",
  };

  const invoiceMapping = {
    take_away: "تيك أواى",
    delvery: "دليفرى",
    hall: "صالة",
  };
  const user = useSelector((state) => state?.user?.user);
  const date = new Date();
  function safeJSONParse(value, fallback = null) {
    try {
      if (!value) return fallback;
      return JSON.parse(value);
    } catch (error) {
      console.warn("Invalid JSON detected:", value);
      return fallback;
    }
  }
  const invSerial = safeJSONParse(localStorage.getItem("Invoice Serial"), null);
  useEffect(() => {
    const storedUser = sessionStorage.getItem("selectedUser");
    if (storedUser) {
      dispatch(setSelectedUser(JSON.parse(storedUser)));
    }
  }, []);
  const [formData, setFormData] = useState({
    serialInput: savedData?.serialInput || invSerial || 1,
    dateInput:
      savedData?.dateInput ||
      `${date.getFullYear()}-${date.toLocaleDateString("en-US", {
        month: "2-digit",
      })}-${date.toLocaleDateString("en-US", {
        day: "2-digit",
      })}` ||
      "",
    clientName: savedData?.clientName || user?.name || "",
    notes: savedData?.notes || "",
    invoiceType:
      savedData?.invoiceType ||
      invoiceMapping[invoiceSettings?.defaultInvoiceType],
    paymentMethod:
      savedData?.paymentMethod ||
      paymentMapping[invoiceSettings?.defaultPaymentMethod],
    paymentMethods: savedData?.paymentMethods || {},
    address1: savedData?.address1 || "",
    newAddress: savedData?.newAddress || "",
    optionalAddress: savedData?.optionalAddress || "",
    phone1: savedData?.phone1 || user?.customer_info?.phone || "",
    warehouseName: savedData?.warehouseName || warehouseName || "",
    newPhone: savedData?.newPhone || "",
    optionalPhone:
      savedData?.optionalPhone || user?.customer_info?.phone2 || "",
  });

  React.useEffect(() => {
    if (!draftFormData) return;

    setFormData((prev) => {
      const updated = {
        ...prev,
        serialInput: draftFormData.serialInput ?? prev.serialInput,
        dateInput: draftFormData.dateInput ?? prev.dateInput,
        clientName: draftFormData.clientName ?? prev.clientName,
        notes: draftFormData.notes ?? prev.notes,
        paymentMethod: draftFormData.paymentMethod ?? prev.paymentMethod,
        paymentMethods: draftFormData.paymentMethods ?? prev.paymentMethods,
        invoiceType: draftFormData.invoiceType ?? prev.invoiceType,
        phone1: draftFormData.phone1 ?? prev.phone1,
        newPhone: draftFormData.newPhone ?? prev.newPhone,
        optionalPhone: draftFormData.optionalPhone ?? prev.optionalPhone,
        address1: draftFormData.address1 ?? prev.address1,
        newAddress: draftFormData.newAddress ?? prev.newAddress,
        optionalAddress: draftFormData.optionalAddress ?? prev.optionalAddress,
        warehouseName: draftFormData.warehouseName ?? prev.warehouseName,
      };

      // prevent state update if nothing changed
      const isSame = Object.keys(updated).every(
        (key) => updated[key] === prev[key],
      );

      return isSame ? prev : updated;
    });
  }, [draftFormData]);

  // Persist to session storage whenever formData changes
  React.useEffect(() => {
    sessionStorage.setItem("FormData", JSON.stringify(formData));
  }, [formData]);

  return (
    <FormDataContext.Provider value={{ formData, setFormData }}>
      {children}
    </FormDataContext.Provider>
  );
};
