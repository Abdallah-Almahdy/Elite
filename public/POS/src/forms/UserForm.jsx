import React, {
  useState,
  forwardRef,
  useImperativeHandle,
  useEffect,
  useRef,
} from "react";
import { Formik, useFormik } from "formik";
import * as Yup from "yup";
import CreatableSelect from "react-select/creatable";
import {
  FaMoneyBill,
  FaCreditCard,
  FaUniversity,
  FaRegClock,
  FaFileInvoice,
} from "react-icons/fa"; // example icons
import { useContext } from "react";
import { FormDataContext } from "../contexts/FormDataContext";
import { IoIosArrowDown } from "react-icons/io";
import { IoPersonAdd } from "react-icons/io5";
import { components } from "react-select";
import NewClientForm from "./NewClientForm";
import { FiPhone } from "react-icons/fi";
import { SlLocationPin } from "react-icons/sl";
import Select from "react-select";
import { IoSearchSharp } from "react-icons/io5";
import SearchByPhone from "../components/ui/SearchByPhone";
import { useSelector, useDispatch } from "react-redux";
import { useProducts } from "../contexts/ProductsContext";
import { useSelectedProducts } from "../contexts/SelectedProductsContext";
import { fetchConfigs } from "../store/reducers/settingSlice";

const UserForm = forwardRef((props, ref) => {
  const users = useSelector((state) => state?.user?.user);
  const dispatch = useDispatch();
  const date = new Date();
  const {
    draftFormData,
    isPopupOpen,
    setIsPopupOpen,
    handleSearchClientName,
    searchClientName,
    searchClientNamesResult,
    setSearchClientName,
    setSearchClientNamesResult,
    handleSelectClient,
    isSearchPopupOpen,
    setIsSearchPopupOpen,
    searchClientPhone,
    setSearchClientPhone,
    searchClientPhoneResult,
    setSearchClientPhoneResult,
    handleSearchClientPhone,
    phoneSearchRef,
  } = useProducts();
  const { selectedProducts } = useSelectedProducts();
  const { formData, setFormData } = useContext(FormDataContext);
  const inputRef = useRef();
  const [showAddressEditor, setShowAddressEditor] = useState(false);
  const [showClientsResults, setShowClientsResults] = useState(false);
  const [highlightIndex, setHighlightIndex] = useState(-1);
  const streetInputRef = useRef(null);
  const clientResultRef = useRef(null);
  const selectedUser = JSON.stringify(sessionStorage.getItem("selectedUser"));
  function safeJSONParse(value, fallback = null) {
    try {
      if (!value) return fallback;
      return JSON.parse(value);
    } catch (error) {
      console.warn("Invalid JSON detected:", value);
      return fallback;
    }
  }
  const warehouseNames = ["مخزن 1", "مخزن 2", "مخزن 3", "مخزن 4", "مخزن 5"];
  const invSerial = safeJSONParse(localStorage.getItem("Invoice Serial"), null);
  const invoiceSettings = useSelector(
    (state) => state?.setting?.invoiceSettings,
  );
  const warehouseName = useSelector((state) => state?.setting?.warehouseName);
  useEffect(() => {
    dispatch(fetchConfigs());
  }, [dispatch]);
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
  // const invoiceSettings = JSON.parse(localStorage.getItem("Invoice Settings"))
  const userSettings = JSON.parse(localStorage.getItem("User Settings"));
  const formik = useFormik({
    initialValues: {
      serialInput:
        draftFormData?.serialInput || formData.serialInput || invSerial || "",
      dateInput:
        draftFormData?.date ||
        formData.dateInput ||
        `${date.getFullYear()}-${date.getMonth() + 1}-${date.getDate()}` ||
        "",
      warehouseName:
        draftFormData?.warehouseName ||
        formData.warehouseName ||
        warehouseName ||
        "",
      clientName: draftFormData?.clientName || formData.clientName || "",
      phone1: draftFormData?.phone1 || formData.phone1 || "",
      address1: draftFormData?.address1 || formData.address1 || "",
      notes: draftFormData?.notes || formData.notes || "",
      invoiceType:
        draftFormData?.invoiceType ||
        formData.invoiceType ||
        invoiceMapping[invoiceSettings?.defaultInvoiceType],
      paymentMethod:
        draftFormData?.paymentMethod ||
        formData?.paymentMethod ||
        (formData?.paymentMethods &&
          Object.keys(formData?.paymentMethods)[0]) ||
        paymentMapping[invoiceSettings?.defaultPaymentMethod],
    },

    enableReinitialize: true,
    validationSchema: Yup.object({
      serialInput: Yup.string()
        .max(15, "Must be 15 characters or less")
        .required("Required*"),
      warehouseName: Yup.string().max(15, "Must be 15 characters or less"),
      // .required("Required*"),
      dateInput: Yup.date()
        .typeError("Invalid date format")
        .min(new Date(2000, 0, 1), "Date must be after Jan 1, 2000")
        .required("Required*"),
      clientName: Yup.string()
        .max(50, "Must be 50 characters or less")
        .when(["invoiceType", "paymentMethod"], {
          is: (invoiceType, paymentMethod) =>
            invoiceType === "دليفرى" || paymentMethod === "اجل",
          then: (schema) => schema.required("اسم العميل مطلوب"),
          otherwise: (schema) => schema.notRequired(),
        }),

      notes: Yup.string().max(500, "Must be 500 characters or less"),
      invoiceType: Yup.string().required("اختر نوع الفاتورة"),
      paymentMethod: Yup.string().required("اختر طريقة الدفع"),
      phone1: Yup.string().when("invoiceType", {
        is: "دليفرى",
        then: (schema) => schema.required("الهاتف مطلوب للدليفرى"),
        otherwise: (schema) => schema.notRequired(),
      }),
      address1: Yup.string().when("invoiceType", {
        is: "دليفرى", // when invoiceType is "دليفرى"
        then: (schema) => schema.required("العنوان مطلوب للدليفرى"), // notes is required
        otherwise: (schema) => schema.notRequired(), // notes is optional
      }),
    }),
    onSubmit: (values) => {
      let invoice = {
        data: { type: "Invoice", userForm: values, items: selectedProducts },
      };
    },
  });
  useImperativeHandle(ref, () => ({
    submitForm: () => {
      formik.handleSubmit();
    },
    getValues: () => {
      return formik.values;
    },
    validateForm: async () => {
      const errors = await formik.validateForm();
      formik.setTouched({
        serialInput: true,
        dateInput: true,
        warehouseName: true,
        clientName: true,
        notes: true,
        invoiceType: true,
        paymentMethod: true,
        phone1: true,
        address1: true,
      });
      return Object.keys(errors)?.length === 0;
    },
    isValid: formik.isValid,
  }));
  React.useEffect(() => {
    setFormData((prev) => ({
      ...prev,
      ...formik.values,
    }));
  }, [formik.values, setFormData]);

  const paymentMethodOptions = [
    { id: 1, code: "cash", label: "كاش" },
    { id: 2, code: "credit_card", label: "بطاقة ائتمان" },
    { id: 3, code: "instapay", label: "انستا باى" },
    { id: 4, code: "wallet", label: "محفظة" },
    { id: 5, code: "remaining", label: "اجل" },
  ];
  const invoiceTypeOptions = [
    { id: 1, code: "take_away", label: "تيك أواى" },
    { id: 2, code: "delvery", label: "دليفرى" },
    { id: 3, code: "hall", label: "صالة" },
  ];

  const allowedPaymentMethodCodes = invoiceSettings?.allowedPaymentMethods || [
    "كاش",
  ];
  const allowedInvoiceTypeCodes = invoiceSettings?.allowedInvoiceTypes || [
    "تيك أواى",
  ];
  const allowedPaymentMethodLabels = paymentMethodOptions
    .filter((method) => allowedPaymentMethodCodes.includes(method.code))
    .map((method) => method.label);
  const allowedInvoiceTypeLabels = invoiceTypeOptions
    .filter((method) => allowedInvoiceTypeCodes.includes(method.code))
    .map((method) => method.label);

  const invoiceTypes = allowedInvoiceTypeLabels || ["تيك أواى"];
  const paymentMethods = allowedPaymentMethodLabels || ["كاش"];
  // const invoiceTypes = ["تيك أواى", "دليفرى", "صالة"];
  // const paymentMethods = ["كاش", "بطاقة ائتمان", "انستا باى", "اجل", "محفظة"];
  useEffect(() => {
    sessionStorage.setItem("FormData", JSON.stringify(formik.values));
  }, [formik.values]);
  useEffect(() => {
    if (formData?.invoiceType === "دليفرى") {
      inputRef?.current?.focus();
    }
  }, [formData?.invoiceType]);

  useEffect(() => {
    if (showAddressEditor && streetInputRef.current) {
      streetInputRef.current.focus();
    }
  }, [showAddressEditor]);

  useEffect(() => {
    const results = handleSearchClientName(searchClientName);
    setSearchClientNamesResult(results);
    if (results?.length > 0) {
      setShowClientsResults(true);
    }
  }, [users, searchClientName]);

  useEffect(() => {
    function handleClickOutsidePopover(event) {
      if (
        clientResultRef.current &&
        !clientResultRef.current.contains(event.target)
      ) {
        setShowClientsResults(false);
      }
    }
    document.addEventListener("mousedown", handleClickOutsidePopover);
    return () => {
      document.removeEventListener("mousedown", handleClickOutsidePopover);
    };
  }, [clientResultRef]);

  return (
    <div className="w-full">
      <form onSubmit={formik.handleSubmit}>
        <table className="w-full h-auto border border-gray-300 border-collapse table-fixed">
          <tbody className="w-full">
            <tr className="border-b border-gray-300 bg-white">
              <td className="pr-2 ">مسلسل</td>
              <td className="relative bg-white" colSpan={2}>
                {/* Serial Input */}
                <input
                  type="text"
                  id="serialInput"
                  name="serialInput"
                  className="text-gray-900 placeholder-gray-600 text-sm block w-full px-2.5 py-1.5 focus:outline-blue-500 bg-white focus:bg-white"
                  placeholder="ds-10-80-8888"
                  value={formik.values.serialInput}
                  onChange={formik.handleChange}
                  onBlur={formik.handleBlur}
                  autoComplete="off"
                  // disabled={
                  //   JSON.parse(sessionStorage.getItem("draftFormData"))
                  //     ?.serialInput === formik?.values?.serialInput
                  // }
                  disabled
                />
                {formik.touched.serialInput && formik.errors.serialInput && (
                  <span className="absolute text-left font-semibold left-5 top-1/2 bg-transparent -translate-y-1/2 text-red-600 text-xs">
                    {formik.errors.serialInput}
                  </span>
                )}
              </td>
              <td className=" pr-2 ">تاريخ</td>
              <td className="" colSpan={2}>
                <div className="w-full max-w-full relative flex justify-between">
                  {/* Date Input */}
                  <input
                    id="dateInput"
                    type="date"
                    name="dateInput"
                    className="text-gray-900 placeholder-gray-600 placeholder:text-gray-600 text-right placeholder:text-right text-xs block w-full px-2.5 py-1.5 focus:outline-blue-500 rounded "
                    value={formik.values.dateInput}
                    onChange={formik.handleChange}
                    onBlur={formik.handleBlur}
                  />
                  {formik.touched.dateInput && formik.errors.dateInput && (
                    <span className="absolute text-left font-semibold left-5 top-1/2 bg-transparent -translate-y-1/2 text-red-600 text-xs">
                      {formik.errors.dateInput}
                    </span>
                  )}
                </div>
              </td>
              {/* makhazeeeen */}
              <td className=" pr-2 ">المخزن</td>
              <td className="" colSpan={3}>
                <div className="w-full max-w-full relative flex justify-between">
                  {/* WareHouse Input */}
                  <select
                    className="
   text-black block w-full pr-8 ps-7 lg:ps-2 py-3 lg:py-1 
  border text-heading text-sm rounded-md shadow-xs placeholder:text-body
  focus:border-blue-600 focus:ring-1 outline-none
"
                    value={formik.values.warehouseName}
                    onBlur={() => formik.setFieldTouched("warehouseName", true)}
                    onChange={(e) => {
                      const value = e.target.value;
                      formik.setFieldValue("warehouseName", value);
                      // setSearchClientName(value);

                      // setHighlightIndex(-1);
                      // if (value === "") {
                      //   formik.setValues({
                      //     ...formik.values,
                      //     clientName: "",
                      //     phone1: "",
                      //     address1: "",
                      //     optionalPhone: "",
                      //     optionalAddress: "",
                      //   });
                      // }
                    }}
                    // onChange={(e) =>
                    //   formik.setFieldValue("paymentMethod", e.target.value)
                    // }
                  >
                    <option value="" disabled className="bg-white">
                      اختر اسم المخزن
                    </option>
                    {warehouseNames.map((method) => (
                      <option key={method} value={method} className="bg-white">
                        {method}
                      </option>
                    ))}
                  </select>

                  {formik.touched.warehouseName &&
                    formik.errors.warehouseName && (
                      <span className="absolute text-left font-semibold left-5 top-1/2 bg-transparent -translate-y-1/2 text-red-600 text-xs">
                        {formik.errors.warehouseName}
                      </span>
                    )}
                </div>
              </td>
            </tr>
            <tr className="border-b border-gray-300">
              <td className="pr-2 bg-blue-100 bg-opacity-50">نوع الفاتورة</td>
              <td className="relative bg-blue-100 bg-opacity-50" colSpan={5}>
                <div className="flex justify-evenly items-center gap-2 ">
                  {invoiceTypes?.map((type) => (
                    <button
                      key={type}
                      type="button"
                      className={`px-3 py-1 text-xs font-bold rounded-lg border ${formik.values.invoiceType === type ? "bg-blue-600 text-white" : `bg-white text-gray-900 ${!userSettings?.invoiceTypeChangeAuth ? `` : `hover:bg-blue-400 hover:text-white`}`}`}
                      onClick={() => formik.setFieldValue("invoiceType", type)}
                      disabled={!userSettings?.invoiceTypeChangeAuth}
                    >
                      {type}
                    </button>
                  ))}
                </div>
                {formik.touched.invoiceType && formik.errors.invoiceType && (
                  <span className="text-red-600 text-xs font-semibold absolute top-full left-0">
                    {formik.errors.invoiceType}
                  </span>
                )}
              </td>
              <td className="pr-2 bg-blue-100 bg-opacity-50">طريقة الدفع</td>
              <td colSpan={3} className="relative">
                <select
                  className="w-full text-gray-900 px-2.5 py-1.5 text-sm border rounded focus:outline-blue-500 bg-blue-100 bg-opacity-50 appearance-none"
                  value={formik.values.paymentMethod}
                  disabled={!userSettings?.methodChangeAuth}
                  // onChange={(e) =>
                  //   formik.setFieldValue("paymentMethod", e.target.value)
                  // }
                  onChange={(e) => {
                    const value = e.target.value;

                    // Update Formik field
                    formik.setFieldValue("paymentMethod", value);

                    setFormData((prev) => {
                      // If key already exists → keep paymentMethods unchanged
                      if (prev.paymentMethods?.hasOwnProperty(value)) {
                        return prev;
                      }

                      // Otherwise → reset paymentMethods with the new key
                      return {
                        ...prev,
                        paymentMethods: {
                          [value]: "",
                        },
                      };
                    });
                  }}
                  onBlur={() => formik.setFieldTouched("paymentMethod", true)}
                >
                  <option value="" disabled className="bg-white">
                    اختر طريقة الدفع
                  </option>
                  {paymentMethods.map((method) => (
                    <option key={method} value={method} className="bg-white">
                      {method}
                    </option>
                  ))}
                </select>
                <div className="absolute left-2 top-2">
                  <IoIosArrowDown className="text-xl text-gray-400" />
                </div>
                {formik.touched.paymentMethod &&
                  formik.errors.paymentMethod && (
                    <span className="text-red-600 text-xs font-semibold absolute left-10 top-2">
                      {formik.errors.paymentMethod}
                    </span>
                  )}
              </td>
            </tr>
            <tr className="border-b border-gray-300 relative">
              <td className="pr-2 w-3/4" colSpan={1}>
                اسم العميل
              </td>
              <td className="w-3/4" colSpan={9}>
                <input
                  ref={inputRef}
                  type="text"
                  placeholder="اختر العميل..."
                  value={formik.values.clientName}
                  onBlur={() => formik.setFieldTouched("clientName", true)}
                  onChange={(e) => {
                    const value = e.target.value;
                    formik.setFieldValue("clientName", value);
                    setSearchClientName(value);

                    setHighlightIndex(-1);
                    if (value === "") {
                      formik.setValues({
                        ...formik.values,
                        clientName: "",
                        phone1: "",
                        address1: "",
                        optionalPhone: "",
                        optionalAddress: "",
                      });
                    }
                  }}
                  onKeyDown={(e) => {
                    if (e.key === "ArrowDown") {
                      e.preventDefault();
                      setHighlightIndex((prev) =>
                        prev < searchClientNamesResult?.length - 1
                          ? prev + 1
                          : 0,
                      );
                    }
                    if (e.key === "ArrowUp") {
                      e.preventDefault();
                      setHighlightIndex((prev) =>
                        prev > 0
                          ? prev - 1
                          : searchClientNamesResult?.length - 1,
                      );
                    }
                    if (e.key === "Enter") {
                      if (
                        highlightIndex >= 0 &&
                        searchClientNamesResult[highlightIndex]
                      ) {
                        handleSelectClient(
                          searchClientNamesResult[highlightIndex],
                        );
                      }
                    }
                  }}
                  className="
   text-black block w-full pr-8 ps-7 lg:ps-10 py-3 lg:py-1 
  border text-heading text-sm rounded-md shadow-xs placeholder:text-body
  focus:border-blue-600 focus:ring-0 outline-none
"
                />
                <div className="flex items-center absolute left-0 top-0">
                  <button
                    type="button"
                    onClick={() => setIsPopupOpen(true)}
                    className="p-2 rounded "
                  >
                    <IoPersonAdd
                      className="text-blue-600 hover:text-blue-400"
                      size={20}
                    />
                  </button>
                  <button
                    type="button"
                    onClick={() => {
                      setIsSearchPopupOpen(true);
                    }}
                    className="p-2 rounded "
                  >
                    <IoSearchSharp
                      className="text-blue-600 hover:text-blue-400"
                      size={20}
                    />
                  </button>
                </div>
                {showClientsResults &&
                  selectedUser?.name !== formik.values.clientName && (
                    <div
                      ref={clientResultRef}
                      className="border bg-white absolute w-[30%] z-50 max-h-60 overflow-y-auto shadow"
                    >
                      {searchClientNamesResult?.map((user, index) => (
                        <div
                          key={index}
                          className={`p-2 cursor-pointer ${
                            index === highlightIndex
                              ? "bg-blue-100"
                              : "hover:bg-gray-100"
                          }`}
                          onClick={() => {
                            handleSelectClient(user);
                          }}
                        >
                          {user?.name}
                        </div>
                      ))}
                    </div>
                  )}
                {formik.touched.clientName && formik.errors.clientName && (
                  <span className="absolute text-left font-semibold left-[4.5rem] top-1/2 bg-transparent -translate-y-1/2 text-red-600 text-xs">
                    {formik.errors.clientName}
                  </span>
                )}
              </td>
            </tr>
            <tr className="relative bg-blue-100 bg-opacity-50">
              <td className=" pr-2">رقم الهاتف</td>
              <td className=" align-top" colSpan={4}>
                <div className="relative">
                  <FiPhone className="absolute top-1/2 text-blue-600 -translate-y-1/2 right-3 pointer-events-none" />
                  <input
                    type="text"
                    id="phone1"
                    name="phone1"
                    value={formik.values.phone1}
                    onChange={formik.handleChange}
                    onBlur={formik.handleBlur}
                    className="
  bg-blue-100 bg-opacity-50 text-black block w-full pr-8 ps-7 lg:ps-10 py-3 lg:py-1 
  border text-heading text-sm rounded-md shadow-xs placeholder:text-body
  focus:border-blue-600 focus:ring-0 outline-none
"
                    placeholder="أدخل رقم الهاتف"
                  />

                  <button
                    type="button"
                    className="p-1 rounded absolute left-0 top-2 lg:top-0"
                  >
                    <IoPersonAdd
                      className="text-blue-600 hover:text-blue-400 text-base"
                      size={20}
                    />
                  </button>
                  {formik.touched.phone1 && formik.errors.phone1 && (
                    <span className="absolute text-left font-semibold left-8 top-1/2 bg-transparent -translate-y-1/2 text-red-600 text-xs">
                      {formik.errors.phone1}
                    </span>
                  )}
                </div>
              </td>
              <td className=" pr-2"> العنوان</td>
              <td className=" align-top" colSpan={4}>
                <div className="relative">
                  <SlLocationPin className="absolute top-1/2 text-blue-600 -translate-y-1/2 right-3 pointer-events-none" />
                  <input
                    type="text"
                    id="address1"
                    name="address1"
                    value={formik.values.address1}
                    onChange={formik.handleChange}
                    onBlur={formik.handleBlur}
                    // disabled
                    className="
  bg-blue-100 bg-opacity-50 text-black block w-full pr-8 ps-7 lg:ps-10 py-3 lg:py-1 
  border text-heading text-sm rounded-md shadow-xs placeholder:text-body
  focus:border-blue-600 focus:ring-0 outline-none
"
                    placeholder="أدخل العنوان "
                  />
                  {formik.touched.address1 && formik.errors.address1 && (
                    <span className="absolute text-left font-semibold left-8 top-1/2 bg-transparent -translate-y-1/2 text-red-600 text-xs">
                      {formik.errors.address1}
                    </span>
                  )}
                </div>
              </td>
            </tr>
            <tr className="relative">
              <td className=" pr-2">ملاحظات</td>
              <td colSpan={9}>
                {/* Notes Input */}
                <textarea
                  id="notes"
                  name="notes"
                  rows={1}
                  className="resize-none text-gray-900 placeholder-gray-600 text-xs block w-full px-2.5 py-1.5 focus:outline-blue-500 border"
                  // placeholder="هذا النص هو مثال لنص  يمكن أن يستبدل فى نفس المساحة, لقد تم توليد هذا النص من مولد النص العربية حيث يمكنك أن تولد مثل هذا النص أو العديد من النصوص الأخرى اضافة الى زيادة عدد الحروف التى يولدها التطبيق."
                  value={formik.values.notes}
                  onChange={formik.handleChange}
                  onBlur={formik.handleBlur}
                />
                {formik.touched.notes && formik.errors.notes && (
                  <span className="absolute text-left font-semibold left-5 top-1/2 bg-transparent -translate-y-1/2 text-red-600 text-xs">
                    {formik.errors.notes}
                  </span>
                )}
              </td>
            </tr>
          </tbody>
        </table>
      </form>
      {isPopupOpen && (
        <div className="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
          <NewClientForm setIsPopupOpen={setIsPopupOpen} />
        </div>
      )}
      {isSearchPopupOpen && (
        <div className="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
          <SearchByPhone
            setIsSearchPopupOpen={setIsSearchPopupOpen}
            searchClientPhone={searchClientPhone}
            setSearchClientPhone={setSearchClientPhone}
            searchClientPhoneResult={searchClientPhoneResult}
            setSearchClientPhoneResult={setSearchClientPhoneResult}
            handleSearchClientPhone={handleSearchClientPhone}
            handleSelectClient={handleSelectClient}
            phoneSearchRef={phoneSearchRef}
          />
        </div>
      )}
    </div>
  );
});
export default UserForm;
