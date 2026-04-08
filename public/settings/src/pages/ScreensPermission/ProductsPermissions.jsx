import useScreenSettingsPreferenceLogic from "../../hooks/settings/useScreenSettingsPreferenceLogic";
import PermissionToggle from "../../components/settings/user/PermissionSettings/PermissionToggle";
import { FaRegCheckCircle } from "react-icons/fa";
import { useInvoiceSettings } from "../../contexts/InvoiceSettingsContext";
import { useNavigate } from "react-router-dom";
import notify from "../../hooks/Notification";
import { useEffect } from "react";

export default function ProductsPermissions() {
  const navigate = useNavigate();
  const { updateScreenSettings } = useInvoiceSettings();
  const {
    productCreate,
    setProductCreate,
    productEdit,
    setProductEdit,
    productDelete,
    setProductDelete,
    productShowSidebar,
    setProductShowSidebar,
    productShowGeneral,
    setProductShowGeneral,
  } = useScreenSettingsPreferenceLogic();

  const settings = [
    {
      label: "السماح بانشاء منتج  ",
      value: productCreate,
      setter: setProductCreate,
    },
    {
      label: "السماح بتعديل منتج  ",
      value: productEdit,
      setter: setProductEdit,
    },
    {
      label: "السماح بمسح منتج  ",
      value: productDelete,
      setter: setProductDelete,
    },
    {
      label: "اظهار الشريط الجانبى",
      value: productShowSidebar,
      setter: setProductShowSidebar,
    },
    {
      label: "السماح بعرض المنتجات العامة  ",
      value: productShowGeneral,
      setter: setProductShowGeneral,
    },
  ];

  useEffect(() => {
    const screensSettingSubmit = () => {
      const payload = {
        productCreate: productCreate,
        productEdit: productEdit,
        productDelete: productDelete,
        productShowSidebar: productShowSidebar,
        productShowGeneral: productShowGeneral,
      };
      updateScreenSettings((prev) => ({
        ...prev,
        ...payload,
      }));
    };
    screensSettingSubmit();
  }, [
    productCreate,
    productEdit,
    productDelete,
    productShowSidebar,
    productShowGeneral,
  ]);

  return (
    <div className="min-h-screen bg-gray-100 p-4 lg:p-8 flex flex-col items-center">
      <h1 className="text-2xl font-bold mb-6 text-gray-800">
        إعدادات صلاحيات شاشة المنتجات
      </h1>

      <div className="grid gap-6 w-full lg:w-[70%]">
        {settings.map((setting, idx) => (
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
              onChange={(val) => setting.setter(val)}
            />
          </div>
        ))}
      </div>
    </div>
  );
}
