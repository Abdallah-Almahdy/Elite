import { Routes, Route, useNavigate } from "react-router-dom";
import { InvoiceSettingsProvider } from "./contexts/InvoiceSettingsContext.jsx";
import { useState, useEffect, useContext } from "react";
import { ToastContainer } from "react-toastify";
import { useDispatch } from "react-redux";
import { fetchClientsNames } from "./store/reducers/userSlice.js";
import { SettingsPreferenceProvider } from "./contexts/SettingsPreferenceContext.jsx";
import { UserSettingsPreferenceProvider } from "./contexts/UserSettingsPreferenceContext.jsx";
import SettingsPage from "./pages/SettingsPage.jsx";
import UserSettingsPage from "./pages/UserSettingsPage.jsx";
import { PrinterSettingsPreferenceProvider } from "./contexts/PrinterSettingsContext.jsx";
import PrinterSettingsPage from "./pages/PrinterSettingsPage.jsx";
import "flowbite";
import ScreensHome from "./pages/ScreensHome.jsx";
import POSPermissions from "./pages/ScreensPermission/POSPermissions.jsx";
import { ScreenSettingsPreferenceProvider } from "./contexts/ScreenSettingsPreferenceContext.jsx";
import ADPermissions from "./pages/ScreensPermission/ADPermissions.jsx";
import NotificationPermissions from "./pages/ScreensPermission/NotificationPermissions.jsx";
import BromocodePermissions from "./pages/ScreensPermission/BromocodePermissions.jsx";
import WarehousePermissions from "./pages/ScreensPermission/WarehousePermissions.jsx";
import CreatePermissions from "./pages/ScreensPermission/CreatePermissions.jsx";
import ConfigPermissions from "./pages/ScreensPermission/ConfigPermissions.jsx";
import ProductsPermissions from "./pages/ScreensPermission/ProductsPermissions.jsx";
import SectionsPermissions from "./pages/ScreensPermission/SectionsPermissions.jsx";
import OrdersPermissions from "./pages/ScreensPermission/OrdersPermissions.jsx";
import ReportsPermissions from "./pages/ScreensPermission/ReportsPermissions.jsx";
import DeliveryPermissions from "./pages/ScreensPermission/DeliveryPermissions.jsx";
import KitchenPermissions from "./pages/ScreensPermission/KitchenPermissions.jsx";
import SuppliersPermissions from "./pages/ScreensPermission/SuppliersPermissions.jsx";
import UnitsPermissions from "./pages/ScreensPermission/UnitsPermissions.jsx";
import ClientsPermissions from "./pages/ScreensPermission/ClientsPermissions.jsx";
import StatisticsPermissions from "./pages/ScreensPermission/StatisticsPermissions.jsx";
import CustomersComplainsPermissions from "./pages/ScreensPermission/CustomersComplainsPermissions.jsx";
import EvaluationsPermissions from "./pages/ScreensPermission/EvaluationsPermissions.jsx";
import AboutUsPermissions from "./pages/ScreensPermission/AboutUsPermissions.jsx";

export default function App() {
  const dispatch = useDispatch();
  const navigate = useNavigate();

  useEffect(() => {
    dispatch(fetchClientsNames());
  }, [dispatch]);

  return (
    <>
      <ToastContainer position="top-right" autoClose={3000} />

      <ScreenSettingsPreferenceProvider>
        <SettingsPreferenceProvider>
          <PrinterSettingsPreferenceProvider>
            <UserSettingsPreferenceProvider>
              <InvoiceSettingsProvider>
                <Routes>
                  <Route path="/" element={<ScreensHome />} />
                  {/* <Route path="/print-barcode" element={<PrintBarcodePage />} /> */}
                  <Route path="/invoice-settings" element={<SettingsPage />} />
                  <Route path="/user-settings" element={<UserSettingsPage />} />
                  <Route
                    path="/printer-settings"
                    element={<PrinterSettingsPage />}
                  />
                  <Route path="/pos-permissions" element={<POSPermissions />} />
                  <Route
                    path="/delivery-permissions"
                    element={<DeliveryPermissions />}
                  />
                  <Route path="/ad-permissions" element={<ADPermissions />} />
                  <Route
                    path="/notification-permissions"
                    element={<NotificationPermissions />}
                  />
                  <Route
                    path="/bromocode-permissions"
                    element={<BromocodePermissions />}
                  />
                  <Route
                    path="/warehouse-permissions"
                    element={<WarehousePermissions />}
                  />
                  <Route
                    path="/create-permissions"
                    element={<CreatePermissions />}
                  />
                  <Route
                    path="/config-permissions"
                    element={<ConfigPermissions />}
                  />
                  <Route
                    path="/products-permissions"
                    element={<ProductsPermissions />}
                  />
                  <Route
                    path="/sections-permissions"
                    element={<SectionsPermissions />}
                  />
                  <Route
                    path="/orders-permissions"
                    element={<OrdersPermissions />}
                  />
                  <Route
                    path="/reports-permissions"
                    element={<ReportsPermissions />}
                  />
                  <Route
                    path="/kitchen-permissions"
                    element={<KitchenPermissions />}
                  />
                  <Route
                    path="/suppliers-permissions"
                    element={<SuppliersPermissions />}
                  />
                  <Route
                    path="/units-permissions"
                    element={<UnitsPermissions />}
                  />
                  <Route
                    path="/customers-permissions"
                    element={<ClientsPermissions />}
                  />
                  <Route
                    path="/customers-complains-permissions"
                    element={<CustomersComplainsPermissions />}
                  />
                  <Route
                    path="/statistics-permissions"
                    element={<StatisticsPermissions />}
                  />
                  <Route
                    path="/evaluations-permissions"
                    element={<EvaluationsPermissions />}
                  />
                  <Route
                    path="/aboutUs-permissions"
                    element={<AboutUsPermissions />}
                  />
                </Routes>
              </InvoiceSettingsProvider>
            </UserSettingsPreferenceProvider>
          </PrinterSettingsPreferenceProvider>
        </SettingsPreferenceProvider>
      </ScreenSettingsPreferenceProvider>
    </>
  );
}
