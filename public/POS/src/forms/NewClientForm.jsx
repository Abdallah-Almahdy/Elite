import React, { useContext, useEffect, useRef } from "react";
import { LuUserRound } from "react-icons/lu";
import { FiPhone } from "react-icons/fi";
import { SlLocationPin } from "react-icons/sl";
import { IoClose } from "react-icons/io5";
import { useFormik } from "formik";
import * as Yup from "yup";
import { FormDataContext } from "../contexts/FormDataContext";
import { saveUser } from "../store/reducers/userSlice";
import { useDispatch } from "react-redux";
import Select from "react-select";
import notify from "../hooks/Notification";
//  import { fetchClientsNames } from "../store/reducers/userSlice";
const govOptions = [
  { value: "القاهرة", label: "القاهرة" },
  { value: "الجيزة", label: "الجيزة" },
  { value: "الإسكندرية", label: "الإسكندرية" },
  { value: "الدقهلية", label: "الدقهلية" },
  { value: "البحر الأحمر", label: "البحر الأحمر" },
  { value: "البحيرة", label: "البحيرة" },
  { value: "الفيوم", label: "الفيوم" },
  { value: "الغربية", label: "الغربية" },
  { value: "المنوفية", label: "المنوفية" },
  { value: "المنيا", label: "المنيا" },
  { value: "القليوبية", label: "القليوبية" },
  { value: "الوادي الجديد", label: "الوادي الجديد" },
  { value: "السويس", label: "السويس" },
  { value: "أسوان", label: "أسوان" },
  { value: "أسيوط", label: "أسيوط" },
  { value: "الأقصر", label: "الأقصر" },
  { value: "البني سويف", label: "البني سويف" },
  { value: "بورسعيد", label: "بورسعيد" },
  { value: "دمياط", label: "دمياط" },
  { value: "الشرقية", label: "الشرقية" },
  { value: "شمال سيناء", label: "شمال سيناء" },
  { value: "جنوب سيناء", label: "جنوب سيناء" },
  { value: "سوهاج", label: "سوهاج" },
  { value: "مطروح", label: "مطروح" },
  { value: "قنا", label: "قنا" },
];

export default function NewClientForm({ setIsPopupOpen }) {
  const { formData, setFormData } = useContext(FormDataContext);
  const dispatch = useDispatch();
  const clientNameInputRef = useRef(null);
  const phoneInputRef = useRef(null);
  const govInputRef = useRef(null);
  const cityInputRef = useRef(null);
  const streetInputRef = useRef(null);
  const buildingRef = useRef(null);
  const floorRef = useRef(null);
  const apartmentRef = useRef(null);
  let formatAddress = () => {
    return `${formik?.values?.gov} ${formik?.values?.city} ${formik?.values?.street} ${formik?.values?.building} ${formik?.values?.floor} ${formik?.values?.apartment}`;
  };

  const formik = useFormik({
    initialValues: {
      clientName: "",
      phone1: "",
      //  optionalPhone: '',
      //  address1: {
      //   country: ''
      //  },
      gov: "",
      city: "",
      street: "",
      building: "",
      floor: "",
      apartment: "",
      //  optionalAddress: '',
    },
    validationSchema: Yup.object({
      clientName: Yup.string()
        .min(2, "يجب أن يكون الاسم على الأقل حرفين")
        .max(50, "يجب ألا يزيد الاسم عن 50 حرفًا")
        .required("الاسم مطلوب"),

      phone1: Yup.string()
        .matches(/^\d+$/, "رقم الهاتف يجب أن يحتوي على أرقام فقط")
        .min(7, "رقم الهاتف قصير جدًا")
        .max(15, "رقم الهاتف طويل جدًا")
        .required("رقم الهاتف الرئيسي مطلوب"),
      gov: Yup.string()
        .min(2, "اسم المحافظة قصير جدًا")
        .max(25, "اسم المحافظة طويل جدًا")
        .required("اسم المحافظة مطلوب"),
      city: Yup.string()
        .min(1, "اسم المدينة قصير جدًا")
        .max(25, "اسم المدينة طويل جدًا")
        .required("اسم المدينة مطلوب"),
      street: Yup.string()
        .min(1, "اسم الشارع قصير جدًا")
        .max(25, "اسم الشارع طويل جدًا")
        .required("اسم الشارع مطلوب"),
      building: Yup.string()
        .min(1, "رقم المبنى قصير جدًا")
        .max(15, "رقم المبنى طويل جدًا")
        .required("رقم المبنى مطلوب"),
      floor: Yup.string()
        .min(1, "رقم الدور قصير جدًا")
        .max(15, "رقم الدور طويل جدًا")
        .required("رقم الدور مطلوب"),
      apartment: Yup.string()
        .min(1, "رقم الشقة قصير جدًا")
        .max(15, "رقم الشقة طويل جدًا")
        .required("رقم الشقة مطلوب"),
    }),
    onSubmit: (values) => {
      try {
        const formData = new FormData();
        formData.append("name", values.clientName);
        formData.append("phonenum", values.phone1);
        formData.append("phonenum", values.phone1);
        formData.append("Country", values.gov);
        formData.append("city", values.city);
        formData.append("street", values.street);
        formData.append("building", values.building);
        formData.append("floor", values.floor);
        formData.append("apartment", values.apartment);
        dispatch(saveUser(formData));
        setFormData((prev) => ({
          ...prev,
          clientName: formik.values.clientName,
          phone1: formik.values.phone1,
          optionalPhone: formik.values.optionalPhone,
          // address1: formik.values.address1,
          address1: formatAddress(),
          optionalAddress: formik.values.optionalAddress,
        }));

        setIsPopupOpen(false);
      } catch (err) {
        notify("حدث مشكلة يرجى المحاولة مرة اخرى", "error");
      }
    },
  });

  useEffect(() => {
    const handleKeyDown = async (e) => {
      if (e.key === "Escape") {
        setIsPopupOpen(false);
      }
    };
    window.addEventListener("keydown", handleKeyDown);
    return () => {
      window.removeEventListener("keydown", handleKeyDown);
    };
  }, [setIsPopupOpen]);

  const selectedGov = govOptions.find((c) => c.value === formik.values.gov);
  useEffect(() => {
    clientNameInputRef.current?.focus();
  }, []);
  return (
    <div className="w-full min-h-screen flex justify-center items-center p-4">
      <div className="w-full max-w-md bg-white rounded-xl shadow-lg p-6">
        <div className="w-full flex relative">
          <div className="flex w-3/4 justify-center items-center">
            <h1 className="text-2xl  font-bold text-center mb-6 text-gray-800">
              ادخال بيانات عميل جديد
            </h1>
          </div>
          <div className="w-1/4 absolute inset-y-0 left-0 flex justify-end items-center">
            <button className="pb-5" onClick={() => setIsPopupOpen(false)}>
              <IoClose className="text-2xl " />
            </button>
          </div>
        </div>
        <form className="space-y-4" onSubmit={formik.handleSubmit}>
          {/* Name Input */}
          <div>
            <label
              htmlFor="clientName"
              className="block mb-1 text-sm font-medium text-gray-700"
            >
              اسم العميل
            </label>
            <div className="relative">
              <div className="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                <LuUserRound size={20} />
              </div>
              <input
                ref={clientNameInputRef}
                type="text"
                name="clientName"
                id="clientName"
                value={formik.values.clientName}
                onChange={formik.handleChange}
                onBlur={formik.handleBlur}
                placeholder="اسم العميل"
                className="w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm placeholder-gray-400"
                onKeyDown={(e) => {
                  if (e.key === "Enter") phoneInputRef.current?.focus();
                }}
              />
              {formik.touched.clientName && formik.errors.clientName && (
                <span className="absolute text-left font-semibold left-10 top-1/2 bg-transparent -translate-y-1/2 text-red-600 text-xs">
                  {formik.errors.clientName}
                </span>
              )}
            </div>
          </div>

          {/* Main Phone Input */}
          <div>
            <label
              htmlFor="phone1"
              className="block mb-1 text-sm font-medium text-gray-700"
            >
              رقم الهاتف
            </label>
            <div className="relative">
              <div className="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                <FiPhone size={20} />
              </div>
              <input
                ref={phoneInputRef}
                type="text"
                name="phone1"
                id="phone1"
                value={formik.values.phone1}
                onChange={formik.handleChange}
                onBlur={formik.handleBlur}
                placeholder="رقم الهاتف الرئيسي"
                className="w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm placeholder-gray-400"
                onKeyDown={(e) => {
                  if (e.key === "Enter") govInputRef.current?.focus();
                }}
              />
              {formik.touched.phone1 && formik.errors.phone1 && (
                <span className="absolute text-left font-semibold left-10 top-1/2 bg-transparent -translate-y-1/2 text-red-600 text-xs">
                  {formik.errors.phone1}
                </span>
              )}
            </div>
          </div>
          {/* Main City Input */}
          <div>
            <label
              htmlFor="gov"
              className="block mb-1 text-sm font-medium text-gray-700"
            >
              المحافظة
            </label>
            <div className="relative">
              <div className="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                <SlLocationPin size={20} />
              </div>
              <Select
                ref={govInputRef}
                options={govOptions}
                value={selectedGov}
                onBlur={formik.handleBlur}
                onChange={(selected) =>
                  formik.setFieldValue("gov", selected ? selected?.value : "")
                }
                placeholder="اختر المحافظة..."
                isClearable
                className="w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm placeholder-gray-400"
                onKeyDown={(e) => {
                  if (e.key === "Enter") cityInputRef.current?.focus();
                }}
              />
              {formik.touched.gov && formik.errors.gov && (
                <span className="absolute text-left font-semibold left-20 top-1/2 bg-transparent -translate-y-1/2 text-red-600 text-xs">
                  {formik.errors.gov}
                </span>
              )}
            </div>
          </div>

          <div className="">
            <label
              htmlFor="city"
              className="block mb-1 text-sm font-medium text-gray-700"
            >
              اسم الحى
            </label>
            <div className="relative">
              <div className="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                <SlLocationPin size={20} />
              </div>
              <input
                ref={cityInputRef}
                type="text"
                name="city"
                id="city"
                value={formik.values.city}
                onChange={formik.handleChange}
                onBlur={formik.handleBlur}
                placeholder="اكتب الحى "
                className="w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm placeholder-gray-400"
                onKeyDown={(e) => {
                  if (e.key === "Enter") streetInputRef.current?.focus();
                }}
              />
              {formik.touched.city && formik.errors.city && (
                <span className="absolute text-left font-semibold left-10 top-1/2 bg-transparent -translate-y-1/2 text-red-600 text-xs">
                  {formik.errors.city}
                </span>
              )}
            </div>
          </div>

          {/* Main Street Input */}
          <div>
            <label
              htmlFor="street"
              className="block mb-1 text-sm font-medium text-gray-700"
            >
              اسم الشارع
            </label>
            <div className="relative">
              <div className="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                <SlLocationPin size={20} />
              </div>
              <input
                ref={streetInputRef}
                type="text"
                name="street"
                id="street"
                value={formik.values.street}
                onChange={formik.handleChange}
                onBlur={formik.handleBlur}
                placeholder=" اكتب اسم الشارع"
                className="w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm placeholder-gray-400"
                onKeyDown={(e) => {
                  if (e.key === "Enter") buildingRef.current?.focus();
                }}
              />
              {formik.touched.street && formik.errors.street && (
                <span className="absolute text-left font-semibold left-10 top-1/2 bg-transparent -translate-y-1/2 text-red-600 text-xs">
                  {formik.errors.street}
                </span>
              )}
            </div>
          </div>
          <div className="w-full grid grid-cols-3 items-center gap-x-2">
            {/* Main Building Input */}
            <div>
              <div className="flex justify-between items-center">
                <label
                  htmlFor="building"
                  className="block mb-1 text-sm font-medium text-gray-700"
                >
                  المبنى
                </label>
                {formik.touched.building && formik.errors.building && (
                  <div className="text-red-600 text-xs font-semibold lg:whitespace-nowrap">
                    {formik.errors.building}
                  </div>
                )}
              </div>
              <div className="relative">
                <div className="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                  <SlLocationPin size={20} />
                </div>
                <input
                  ref={buildingRef}
                  type="text"
                  name="building"
                  id="building"
                  value={formik.values.building}
                  onChange={formik.handleChange}
                  onBlur={formik.handleBlur}
                  placeholder="رقم المبنى"
                  className="w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm placeholder-gray-400"
                  onKeyDown={(e) => {
                    if (e.key === "Enter") floorRef.current?.focus();
                  }}
                />
              </div>
            </div>

            {/* Main Floor Input */}
            <div>
              <div className="flex justify-between items-center">
                <label
                  htmlFor="floor"
                  className="block mb-1 text-sm font-medium text-gray-700"
                >
                  الدور
                </label>
                {formik.touched.floor && formik.errors.floor && (
                  <div className="text-red-600 text-xs font-semibold">
                    {formik.errors.floor}
                  </div>
                )}
              </div>
              <div className="relative">
                <div className="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                  <SlLocationPin size={20} />
                </div>
                <input
                  ref={floorRef}
                  type="text"
                  name="floor"
                  id="floor"
                  value={formik.values.floor}
                  onChange={formik.handleChange}
                  onBlur={formik.handleBlur}
                  placeholder=" رقم الدور"
                  className="w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm placeholder-gray-400"
                  onKeyDown={(e) => {
                    if (e.key === "Enter") apartmentRef.current?.focus();
                  }}
                />
              </div>
            </div>

            {/* Main Apartment Input */}
            <div>
              <div className="flex justify-between items-center">
                <label
                  htmlFor="apartment"
                  className="block mb-1 text-sm font-medium text-gray-700"
                >
                  الشقة
                </label>
                {formik.touched.apartment && formik.errors.apartment && (
                  <div className="text-red-600 text-xs font-semibold">
                    {formik.errors.apartment}
                  </div>
                )}
              </div>
              <div className="relative">
                <div className="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                  <SlLocationPin size={20} />
                </div>
                <input
                  ref={apartmentRef}
                  type="text"
                  name="apartment"
                  id="apartment"
                  value={formik.values.apartment}
                  onChange={formik.handleChange}
                  onBlur={formik.handleBlur}
                  placeholder=" رقم الشقة"
                  className="w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm placeholder-gray-400"
                />
              </div>
            </div>
          </div>
          {/* Submit Button */}
          <button
            type="submit"
            className="w-full py-2.5 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-md transition-all duration-200"
          >
            حفظ العميل
          </button>
        </form>
      </div>
    </div>
  );
}
