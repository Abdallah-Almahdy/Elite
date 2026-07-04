import { createContext, useContext, useState } from "react";

const SelectedScreensContext = createContext(null);

export const SelectedScreensProvider = ({ children }) => {
  const savedData = JSON.parse(sessionStorage?.getItem("Selected Screens")) || {
    "شاشة البيع": true,
    "شاشة الدليفرى": true,
    "شاشة الاعلانات": true,
    "شاشة الاشعارات": true,
    "شاشة البروموكود": true,
    "شاشة المخازن": true,
    "شاشة انشاء مستخدم": true,
    "شاشة الاعدادات": true,
    "شاشة المنتجات": true,
    "شاشة الاقسام": true,
    "شاشة الطلبيات": true,
    "شاشة التقارير": true,
    "شاشة المطابخ": true,
    "شاشة الاحصائيات": true,
    "شاشة الموردين": true,
    "شاشة الوحدات": true,
    "شاشة العملاء": true,
    "شاشة شكاوى العملاء": true,
    "شاشة التقييمات ": true,
    عننا: true,
  };
  const [selectedScreens, setSelectedScreens] = useState(
    savedData || {
         "شاشة البيع": true,
    "شاشة الدليفرى": true,
    "شاشة الاعلانات": true,
    "شاشة الاشعارات": true,
    "شاشة البروموكود": true,
    "شاشة المخازن": true,
    "شاشة انشاء مستخدم": true,
    "شاشة الاعدادات": true,
    "شاشة المنتجات": true,
    "شاشة الاقسام": true,
    "شاشة الطلبيات": true,
    "شاشة التقارير": true,
    "شاشة المطابخ": true,
    "شاشة الاحصائيات": true,
    "شاشة الموردين": true,
    "شاشة الوحدات": true,
    "شاشة العملاء": true,
    "شاشة شكاوى العملاء": true,
    "شاشة التقييمات ": true,
    عننا: true,
    },
  );

  return (
    <SelectedScreensContext.Provider
      value={{
        selectedScreens,
        setSelectedScreens,
      }}
    >
      {children}
    </SelectedScreensContext.Provider>
  );
};

export const useSelectedScreens = () => {
  const context = useContext(SelectedScreensContext);
  if (!context) {
    throw new Error(
      "useSelectedScreens must be used inside SelectedScreensProvider",
    );
  }
  return context;
};
