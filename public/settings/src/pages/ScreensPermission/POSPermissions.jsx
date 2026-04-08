import useScreenSettingsPreferenceLogic from "../../hooks/settings/useScreenSettingsPreferenceLogic";
import PermissionToggle from "../../components/settings/user/PermissionSettings/PermissionToggle";
import { FaRegCheckCircle } from "react-icons/fa";
import { useInvoiceSettings } from "../../contexts/InvoiceSettingsContext";
import { useNavigate } from "react-router-dom";
import notify from "../../hooks/Notification";
import { useEffect } from "react";
import { useDispatch, useSelector } from "react-redux";
import { sendConfigs } from "../../store/reducers/settingSlice";
import { fetchAdmin } from "../../store/reducers/adminSlice";

export default function POSPermissions() {
  const admin = useSelector((state) => state?.admin?.admin);
  const dispatch = useDispatch();
  const navigate = useNavigate();
  const { updateScreenSettings } = useInvoiceSettings();
  const {
    posShow,
    setPosShow,
    posPriceChangeAuth,
    setPosPriceChangeAuth,
    posChangeDiscount,
    setPosChangeDiscount,
    posDeleteProdWithPass,
    setPosDeleteProdWithPass,
    posPassword,
    setPosPassword,
    posInvoiceTypeChangeAuth,
    setPosInvoiceTypeChangeAuth,
    posPaymentMethodChangeAuth,
    setPosPaymentMethodChangeAuth,
    posSaveNoPrintAuth,
    setPosSaveNoPrintAuth,
    posEditDate,
    setPosEditDate,
    posChooseClient,
    setPosChooseClient,
    posInvoiceFreeze,
    setPosInvoiceFreeze,
    posInvoiceCall,
    setPosInvoiceCall,
    posPriceChange,
    setPosPriceChange,
    posChangeTax,
    setPosChangeTax,
    posInvoiceCancel,
    setPosInvoiceCancel,
    posShiftClose,
    setPosShiftClose,
  } = useScreenSettingsPreferenceLogic();

  const settings = [
    { label: "السماح بعرض شاشة البيع", value: posShow, setter: setPosShow },
    {
      label: "السماح بتغيير السعر",
      value: posPriceChangeAuth,
      setter: setPosPriceChangeAuth,
    },
    {
      label: "السماح بتغيير الخصم",
      value: posChangeDiscount,
      setter: setPosChangeDiscount,
    },
    {
      label: "حذف المنتجات بكلمة مرور",
      value: posDeleteProdWithPass,
      setter: setPosDeleteProdWithPass,
    },
    {
      label: "تغيير نوع الفاتورة",
      value: posInvoiceTypeChangeAuth,
      setter: setPosInvoiceTypeChangeAuth,
    },
    {
      label: "تغيير طريقة الدفع ",
      value: posPaymentMethodChangeAuth,
      setter: setPosPaymentMethodChangeAuth,
    },
    {
      label: "السماح بالحفظ بدون طباعة",
      value: posSaveNoPrintAuth,
      setter: setPosSaveNoPrintAuth,
    },
    {
      label: "تغيير تاريخ الفاتورة ",
      value: posEditDate,
      setter: setPosEditDate,
    },
    {
      label: "السماح باختيار عميل ",
      value: posChooseClient,
      setter: setPosChooseClient,
    },
    {
      label: "السماح بتجميد الفاتورة ",
      value: posInvoiceFreeze,
      setter: setPosInvoiceFreeze,
    },
    {
      label: "السماح باستدعاء فاتورة ",
      value: posInvoiceCall,
      setter: setPosInvoiceCall,
    },
    {
      label: "السماح بتعديل السعر ",
      value: posPriceChange,
      setter: setPosPriceChange,
    },
    {
      label: "السماح بتعديل الخصم ",
      value: posChangeTax,
      setter: setPosChangeTax,
    },
    {
      label: "السماح بالغاء فاتورة ",
      value: posInvoiceCancel,
      setter: setPosInvoiceCancel,
    },
    {
      label: "السماح باغلاق الشيفت",
      value: posShiftClose,
      setter: setPosShiftClose,
    },
  ];

  useEffect(() => {
      dispatch(fetchAdmin());
    }, [dispatch]);

  useEffect(() => {
    const screensSettingSubmit = () => {
      const payload = {
        posShow: posShow,
        posPriceChangeAuth: posPriceChangeAuth,
        posChangeDiscount: posChangeDiscount,
        posDeleteProdWithPass: posDeleteProdWithPass,
        posInvoiceTypeChangeAuth: posInvoiceTypeChangeAuth,
        posPaymentMethodChangeAuth: posPaymentMethodChangeAuth,
        posSaveNoPrintAuth: posSaveNoPrintAuth,
        posEditDate: posEditDate,
        posChooseClient: posChooseClient,
        posInvoiceFreeze: posInvoiceFreeze,
        posInvoiceCall: posInvoiceCall,
        posPriceChange: posPriceChange,
        posChangeTax: posChangeTax,
        posInvoiceCancel: posInvoiceCancel,
        posShiftClose: posShiftClose,
      };
      updateScreenSettings((prev) => ({
        ...prev,
        ...payload,
      }));
    };
    screensSettingSubmit();
  }, [
    posShow,
    posPriceChangeAuth,
    posChangeDiscount,
    posDeleteProdWithPass,
    posInvoiceTypeChangeAuth,
    posPaymentMethodChangeAuth,
    posSaveNoPrintAuth,
    posEditDate,
    posChooseClient,
    posInvoiceFreeze,
    posInvoiceCall,
    posPriceChange,
    posChangeTax,
    posInvoiceCancel,
    posShiftClose,
  ]);

  const handleSavePassword = () => {
    const payload = {
      user_id: admin?.id,
      password: posPassword,
    };
    dispatch(sendConfigs({ invoiceSettings: payload }));
  };

  return (
    <div className="min-h-screen bg-gray-100 p-4 lg:p-8 flex flex-col items-center">
      <h1 className="text-2xl font-bold mb-6 text-gray-800">
        إعدادات صلاحيات شاشة البيع
      </h1>

      <div className="grid gap-6 w-full lg:w-[70%]">
        {settings.map((setting, idx) => (
          <div key={idx}>
            <div
              key={idx}
              className="bg-white p-4 rounded-xl shadow-md hover:shadow-lg transition-shadow duration-300 flex justify-between items-center"
            >
              <div className="flex items-center gap-2">
                <FaRegCheckCircle className="text-blue-500 text-xl" />
                <h2 className="text-lg font-medium text-gray-700">
                  {setting.label}
                </h2>
              </div>
              <PermissionToggle
                value={setting.value}
                onChange={(val) => {
                  setting.setter(val);
                  updateScreenSettings((prev) => ({
                    ...prev,
                    [setting.value]: val,
                  }));
                }}
              />
            </div>
            {setting.label === "حذف المنتجات بكلمة مرور" &&
              posDeleteProdWithPass === true && (
                <div
                  key={settings.length + 1}
                  className="w-full flex items-center gap-y-2 p-3"
                >
                  <div className="flex items-center gap-x-3 w-full">
                    <input
                      type="password"
                      name="password"
                      id="password"
                      className="border rounded-md sm:w-[90%] px-2 py-1 focus:outline-blue-500  placeholder:text-base"
                      placeholder="يرجى كتابة كلمة السر"
                      //  required={passwordReq}
                      value={posPassword}
                      onChange={(e) => {
                        const value = e.target.value;
                        setPosPassword(value);
                      }}
                      // onBlur={(e)=>{
                      //   const value = e.target.value

                      //   let passwordError = "";
                      //   // let confirmError = "";

                      //   if(passwordReq && value.length < 8){
                      //     passwordError = "كلمة السر لا بد أن تكون 8 حروف على الأقل";
                      //   }

                      // if(confirmPassword && confirmPassword !== value){
                      //   confirmError = "كلمة السر غير متطابقة";
                      // }

                      //   setErrors({
                      //     password: passwordError,
                      //     // confirmPassword: confirmError
                      //   });
                      // }}
                    />
                    {/* {errors.password && (
  <p className="text-red-500 text-sm font-bold">{errors.password}</p>
)} */}
                  </div>
                  <div className="w-full">
                    <button
                      className="bg-blue-700 hover:bg-blue-600 text-white px-2 py-1.5 rounded-xl font-semibold transition-colors duration-200 shadow-md hover:shadow-lg"
                      onClick={() => {
                        handleSavePassword();
                        //  notify("تم حفظ الاعدادات بنجاح", "success")
                      }}
                    >
                      حفظ كلمة السر
                    </button>
                  </div>
                </div>
              )}
          </div>
        ))}
      </div>
    </div>
  );
}
