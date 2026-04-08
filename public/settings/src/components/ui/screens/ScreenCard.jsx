import { RiArrowLeftDoubleLine } from "react-icons/ri";
import { useState } from "react";
import { useNavigate } from "react-router-dom";
import useScreensLogic from "../../../hooks/useScreensLogic";
import { useSelectedScreens } from "../../../contexts/SelectedScreensContext";
import Pagination from "../Pagination";

export default function ScreenCard({ screens }) {
  const { selectedScreens, setSelectedScreens } = useSelectedScreens();
  const { handleSelectScreen } = useScreensLogic();
  const navigate = useNavigate();

  // const [selectedScreens, setSelectedScreens] = useState({});
  const navigationNames = {
    "شاشة البيع": "pos-permissions",
    "شاشة الدليفرى": "delivery-permissions",
    "شاشة الاعلانات": "ad-permissions",
    "شاشة الاشعارات": "notification-permissions",
    "شاشة البروموكود": "bromocode-permissions",
    "شاشة المخازن": "warehouse-permissions",
    "شاشة انشاء مستخدم": "create-permissions",
    "شاشة الاعدادات": "config-permissions",
    "شاشة المنتجات": "products-permissions",
    "شاشة الاقسام": "sections-permissions",
    "شاشة الطلبيات": "orders-permissions",
    "شاشة التقارير": "reports-permissions",
    "شاشة المطابخ": "kitchen-permissions",
    "شاشة الاحصائيات": "statistics-permissions",
    "شاشة الموردين": "suppliers-permissions",
    "شاشة الوحدات": "units-permissions",
    "شاشة العملاء": "customers-permissions",
    "شاشة شكاوى العملاء": "customers-complains-permissions",
    "شاشة التقييمات ": "evaluations-permissions",
    عننا: "aboutUs-permissions",
  };

  const goToPageSettings = (pageName) => {
    navigate(`/${navigationNames[pageName]}`);
  };
  return (
    <>
      {screens.map((screen) => (
        <div
          key={screen.id}
          className="relative bg-white border rounded-xl p-4 shadow-md hover:shadow-lg transition-shadow duration-200"
        >
          <div
            onClick={() => {
              handleSelectScreen(screen);
            }}
            className={`absolute top-3 start-3 w-5 h-5 rounded-full border-2 cursor-pointer transition-colors duration-200 ${
              selectedScreens[screen.name]
                ? "bg-blue-500 border-blue-500"
                : "bg-white border-gray-300"
            }`}
          />

          <h1 className="text-lg text-center font-semibold mt-2">
            {screen.name}
          </h1>

          <div className="flex justify-center items-center mt-4 gap-2">
            <button
              onClick={() => goToPageSettings(screen.name)}
              disabled={!selectedScreens[screen.name]}
              className={`flex items-center justify-center text-blue-500 font-medium hover:text-blue-700 transition-colors duration-200 ${!selectedScreens[screen.name] ? "cursor-not-allowed" : " cursor-pointer"}`}
            >
              الذهاب الى الاعدادات
              <RiArrowLeftDoubleLine className="ml-1 text-lg" />
            </button>
          </div>
        </div>
      ))}
    </>
  );
}
