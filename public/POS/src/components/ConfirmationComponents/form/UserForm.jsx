import React, { useEffect, useState } from "react";
import { LuUserRound } from "react-icons/lu";
import { FiPhone } from "react-icons/fi";
import { SlLocationPin } from "react-icons/sl";
import { useContext, useImperativeHandle, forwardRef } from "react";
import { FormDataContext } from "../../../contexts/FormDataContext";
import { IoPersonAdd } from "react-icons/io5";
import { MdOutlineAddHomeWork } from "react-icons/md";
import { useFormik } from "formik";
import * as Yup from "yup";
import { useSelectedProducts } from "../../../contexts/SelectedProductsContext";
import Select from "react-select";
import notify from "../../../hooks/Notification";
import { useDispatch, useSelector } from "react-redux";
import { fetchConfigs } from "../../../store/reducers/settingSlice";

const UserForm = forwardRef((props, ref) => {
  const dispatch = useDispatch();
  const { formData, setFormData } = useContext(FormDataContext);
  const draftFormData = JSON.parse(sessionStorage.getItem("draftFormData"));
  const { selectedProducts } = useSelectedProducts();
  const [showOptionalPhone, setShowOptionalPhone] = useState(false);
  const [showNewPhone, setShowNewPhone] = useState(false);
  const [showOptionalAddress, setShowOptionalAddress] = useState(false);
  const [showNewAddress, setShowNewAddress] = useState(false);
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
  useEffect(() => {
    dispatch(fetchConfigs());
  }, [dispatch]);
  const invSerial = safeJSONParse(localStorage.getItem("Invoice Serial"), null);
  const invoiceSettings = useSelector(
    (state) => state?.setting?.invoiceSettings,
  );
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
  const formik = useFormik({
    initialValues: {
      serialInput: draftFormData?.id || formData.serialInput || invSerial || "",
      dateInput:
        draftFormData?.date ||
        formData.dateInput ||
        `${date.getFullYear()}-${date.getMonth() + 1}-${date.getDate()}` ||
        "",
      notes: draftFormData?.notes || formData.notes || "",
      clientName: draftFormData?.clientName || formData?.clientName || "",
      phone1: draftFormData?.phone1 || formData?.phone1 || "",
      optionalPhone:
        draftFormData?.optionalPhone || formData?.optionalPhone || "",
      newPhone: draftFormData?.newPhone || formData?.newPhone || "",
      address1: draftFormData?.address1 || formData?.address1 || "",
      optionalAddress:
        draftFormData?.optionalAddress || formData?.optionalAddress || "",
      newAddress: draftFormData?.newAddress || formData?.newAddress || "",
      invoiceType:
        draftFormData?.invoiceType ||
        formData?.invoiceType ||
        invoiceMapping[invoiceSettings?.defaultInvoiceType],
      paymentMethod:
        draftFormData?.paymentMethod ||
        formData?.paymentMethod ||
        paymentMapping[invoiceSettings?.defaultPaymentMethod],
      paymentMethods:
        draftFormData?.paymentMethods || formData?.paymentMethods || {},
    },
    enableReinitialize: true,
    validationSchema: Yup.object({
      clientName: Yup.string()
        .max(50, "Must be 50 characters or less")
        .when(["invoiceType", "paymentMethod"], {
          is: (invoiceType, paymentMethod) =>
            invoiceType === "دليفرى" || paymentMethod === "اجل",
          then: (schema) => schema.required("اسم العميل مطلوب"),
          otherwise: (schema) => schema.notRequired(),
        }),
      phone1: Yup.string().when("invoiceType", {
        is: "دليفرى",
        then: (schema) => schema.required("الهاتف مطلوب للدليفرى"),
        otherwise: (schema) => schema.notRequired(),
      }),
      optionalPhone: Yup.string(),
      newPhone: Yup.string(),
      address1: Yup.string().when("invoiceType", {
        is: "دليفرى", // when invoiceType is "دليفرى"
        then: (schema) => schema.required("العنوان مطلوب للدليفرى"), // notes is required
        otherwise: (schema) => schema.notRequired(), // notes is optional
      }),
      optionalAddress: Yup.string(),
      newAddress: Yup.string(),
      notes: Yup.string(),
    }),
    onSubmit: (values) => {
      // setFormData(values);
      let invoice = {
        data: {
          type: "Invoice",
          userForm: values,
          items: selectedProducts,
          paymentMethods: formData.paymentMethods,
        },
      };
    },
  });

  useImperativeHandle(ref, () => ({
    submitForm: () => formik.handleSubmit(),
    getValues: () => formik.values,
    validateForm: async () => {
      const errors = await formik.validateForm();
      formik.setTouched({
        clientName: true,
        notes: true,
        phone1: true,
        optionalPhone: true,
        newPhone: true,
        address1: true,
        optionalAddress: true,
        newAddress: true,
      });
      return Object.keys(errors).length === 0;
    },
    isValid: formik.isValid,
  }));
  React.useEffect(() => {
    setFormData((prev) => ({
      ...prev,
      ...formik.values,
    }));
  }, [formik.values]);
  useEffect(() => {
    sessionStorage.setItem("Order Summary Form", JSON.stringify(formik.values));
  }, [formik.values]);

  return (
    <div className="w-full pr-5">
      <h1 className="text-base font-semibold">معلومات العميل</h1>
      <form className="w-[95%] rounded-lg" onSubmit={formik.handleSubmit}>
        <table className="w-full border-separate border-spacing-1">
          <tbody>
            {/* Row 1: Name + Phone */}
            <tr>
              {/* Name field */}
              <td className="w-1/3 align-top">
                <label className="block pb-1.5 text-sm font-medium text-heading mr-5">
                  الاسم
                </label>
                <div className="relative">
                  <LuUserRound className="absolute top-1/2 text-blue-600 -translate-y-1/2 right-3 pointer-events-none" />
                  <input
                    type="text"
                    id="clientName"
                    name="clientName"
                    value={formik.values.clientName}
                    onChange={formik.handleChange}
                    onBlur={formik.handleBlur}
                    className="block w-full pr-10 ps-8 py-1.5 bg-neutral-secondary-medium border text-heading text-sm rounded-md focus:ring-brand focus:border-brand outline-none shadow-xs placeholder:text-body focus:outline-blue-600"
                    placeholder="أدخل اسم العميل"
                  />
                  {formik.errors.clientName && formik.touched.clientName && (
                    <div className="text-red-600 text-xs">
                      {formik.errors.clientName}
                    </div>
                  )}
                </div>
              </td>

              {/* Phone field */}
              <td className="w-1/3 align-top">
                <label className="block pb-1.5 text-sm font-medium text-heading mr-5">
                  رقم الهاتف
                </label>
                <div className="relative">
                  <FiPhone className="absolute top-1/2 text-blue-600 -translate-y-1/2 right-3 pointer-events-none" />
                  <input
                    type="text"
                    id="phone1"
                    name="phone1"
                    value={formik.values.phone1}
                    onChange={formik.handleChange}
                    onBlur={formik.handleBlur}
                    className="block w-full pr-8 ps-10 py-1.5 bg-neutral-secondary-medium border text-heading text-sm rounded-md focus:ring-brand focus:border-brand outline-none shadow-xs placeholder:text-body  focus:outline-blue-600"
                    placeholder="أدخل رقم الهاتف"
                  />
                  {formik.errors.phone1 && formik.touched.phone1 && (
                    <div className="text-red-600 text-xs">
                      {formik.errors.phone1}
                    </div>
                  )}
                </div>
              </td>
              <td className="w-1/3">
                <button
                  type="button"
                  onClick={() => setShowOptionalPhone(!showOptionalPhone)}
                  className="p-2 bg-blue-100 rounded-md mb-1"
                >
                  <IoPersonAdd className="text-blue-600 text-lg" />
                </button>

                {/* NEW PHONE BUTTON */}
                <button
                  type="button"
                  onClick={() => setShowNewPhone(!showNewPhone)}
                  className="p-2 bg-blue-100 rounded-md"
                >
                  <IoPersonAdd className="text-green-600 text-lg" />
                </button>
              </td>
            </tr>
            <tr>
              {(showOptionalPhone || formData.optionalPhone !== "") && (
                <td>
                  <div className="relative">
                    <FiPhone className="absolute top-1/2 -translate-y-1/2 right-3 text-blue-600" />
                    <input
                      type="text"
                      id="optionalPhone"
                      name="optionalPhone"
                      value={formik.values.optionalPhone}
                      onChange={formik.handleChange}
                      onBlur={formik.handleBlur}
                      className="w-full pr-8 py-1.5 bg-neutral-secondary-medium border text-sm rounded-md focus:outline-blue-600"
                      placeholder="رقم إضافي (اختياري)"
                    />
                    {formik.errors.optionalPhone &&
                      formik.touched.optionalPhone && (
                        <div className="text-red-600 text-xs">
                          {formik.errors.optionalPhone}
                        </div>
                      )}
                  </div>
                </td>
              )}

              {/* NEW PHONE */}
              {showNewPhone && (
                <td>
                  <div className="relative">
                    <FiPhone className="absolute top-1/2 -translate-y-1/2 right-3 text-green-600" />
                    <input
                      type="text"
                      id="newPhone"
                      name="newPhone"
                      value={formik.values.newPhone}
                      // onChange={(e) => setNewPhone(e.target.value)}
                      onChange={formik.handleChange}
                      onBlur={formik.handleBlur}
                      className="w-full pr-8 py-1.5 bg-neutral-secondary-medium border text-sm rounded-md focus:outline-blue-600"
                      placeholder="رقم جديد"
                    />
                    {formik.errors.newPhone && formik.touched.newPhone && (
                      <div className="text-red-600 text-xs">
                        {formik.errors.newPhone}
                      </div>
                    )}
                  </div>
                </td>
              )}
            </tr>
            {/* Row 2: Address + Notes */}
            <tr>
              {/* Address */}
              <td className="w-1/2 align-top">
                <label className="block pb-1.5 text-sm font-medium text-heading mr-5">
                  العنوان
                </label>
                <div className="relative">
                  <SlLocationPin className="absolute top-1/2 text-blue-600 -translate-y-1/2 right-3 pointer-events-none" />
                  <input
                    type="text"
                    id="address1"
                    name="address1"
                    value={formik.values.address1}
                    onChange={formik.handleChange}
                    onBlur={formik.handleBlur}
                    className="resize-none w-full pr-8 bg-neutral-secondary-medium border text-heading text-sm rounded-md focus:ring-brand focus:border-brand outline-none p-1.5 shadow-xs placeholder:text-body  focus:outline-blue-600"
                    placeholder="أدخل العنوان بالتفصيل"
                  />
                  {formik.touched.address1 && formik.errors.address1 && (
                    <span className="absolute text-left font-semibold left-8 top-1/2 bg-transparent -translate-y-1/2 text-red-600 text-xs">
                      {formik.errors.address1}
                    </span>
                  )}
                </div>
              </td>

              {/* Notes */}
              <td className="w-1/2 align-top">
                <label className="block pb-1.5 text-sm font-medium text-heading mr-5">
                  ملاحظات
                </label>
                <textarea
                  rows="1"
                  id="notes"
                  name="notes"
                  value={formik.values.notes}
                  onChange={formik.handleChange}
                  onBlur={formik.handleBlur}
                  className="resize-none w-full bg-neutral-secondary-medium border text-heading text-sm rounded-md focus:ring-brand focus:border-brand outline-none p-1.5 shadow-xs placeholder:text-body  focus:outline-blue-600"
                  placeholder="أى ملاحظات إضافية"
                ></textarea>
                {formik.errors.notes && formik.touched.notes && (
                  <div className="text-red-600 text-xs">
                    {formik.errors.notes}
                  </div>
                )}
              </td>
              <td className="w-1/3">
                <button
                  type="button"
                  onClick={() => setShowOptionalAddress(!showOptionalAddress)}
                  className="p-2 bg-blue-100 rounded-md mb-1"
                >
                  <MdOutlineAddHomeWork className="text-blue-600 text-lg" />
                </button>

                {/* NEW ADDRESS BUTTON */}
                <button
                  type="button"
                  onClick={() => setShowNewAddress(!showNewAddress)}
                  className="p-2 bg-blue-100 rounded-md"
                >
                  <MdOutlineAddHomeWork className="text-green-600 text-lg" />
                </button>
              </td>
            </tr>
            <tr>
              {(showOptionalAddress || formData.optionalAddress !== "") && (
                <td>
                  <div className="relative">
                    <SlLocationPin className="absolute top-1/2 -translate-y-1/2 right-3 text-blue-600" />
                    <input
                      type="text"
                      id="optionalAddress"
                      name="optionalAddress"
                      value={formik.values.optionalAddress}
                      onChange={formik.handleChange}
                      onBlur={formik.handleBlur}
                      className="w-full pr-8 py-1.5 bg-neutral-secondary-medium border text-sm rounded-md focus:outline-blue-600"
                      placeholder="عنوان إضافي (اختياري)"
                    />
                    {formik.errors.optionalAddress &&
                      formik.touched.optionalAddress && (
                        <div className="text-red-600 text-xs">
                          {formik.errors.optionalAddress}
                        </div>
                      )}
                  </div>
                </td>
              )}

              {/* NEW ADDRESS */}
              {showNewAddress && (
                <td>
                  <div className="relative">
                    <SlLocationPin className="absolute top-1/2 -translate-y-1/2 right-3 text-green-600" />
                    <input
                      type="text"
                      id="newAddress"
                      name="newAddress"
                      value={formik.values.newAddress}
                      onChange={formik.handleChange}
                      onBlur={formik.handleBlur}
                      className="w-full pr-8 py-1.5 bg-neutral-secondary-medium border text-sm rounded-md focus:outline-blue-600"
                      placeholder="عنوان جديد"
                    />
                    {formik.errors.newAddress && formik.touched.newAddress && (
                      <div className="text-red-600 text-xs">
                        {formik.errors.newAddress}
                      </div>
                    )}
                  </div>
                </td>
              )}
            </tr>
          </tbody>
        </table>
      </form>
    </div>
  );
});
export default UserForm;
