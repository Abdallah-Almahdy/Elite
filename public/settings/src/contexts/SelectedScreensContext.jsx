import { createContext, useContext, useState } from "react";

const SelectedScreensContext = createContext(null);

export const SelectedScreensProvider = ({ children }) => {
  const savedData = JSON.parse(sessionStorage?.getItem("Selected Screens")) || {
    "شاشة البيع": false,
    "شاشة الاعلانات": false,
    "شاشة الاشعارات": false,
    "شاشة البروموكود": false,
    "شاشة المخازن": false,
    "انشاء مستخدم": false,
    الاعدادات: false,
    المنتجات: false,
    الاقسام: false,
    الطلبيات: false,
    التقارير: false,
  };
  const [selectedScreens, setSelectedScreens] = useState(
    savedData || {
      "شاشة البيع": false,
      "شاشة الاعلانات": false,
      "شاشة الاشعارات": false,
      "شاشة البروموكود": false,
      "شاشة المخازن": false,
      "انشاء مستخدم": false,
      الاعدادات: false,
      المنتجات: false,
      الاقسام: false,
      الطلبيات: false,
      التقارير: false,
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
