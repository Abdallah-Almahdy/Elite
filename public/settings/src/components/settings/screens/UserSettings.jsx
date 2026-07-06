import { FaUser } from "react-icons/fa";
import { useState, useEffect } from "react";
import { useDispatch, useSelector } from "react-redux";
import { useInvoiceSettings } from "../../../contexts/InvoiceSettingsContext";
import { IoCloseSharp } from "react-icons/io5";
import { fetchUserPermissions, resetUserPermissions } from "../../../store/reducers/settingSlice";
import { useScreensPermissions } from "../../../contexts/ScreensPermissionsContext";

export default function UserSettings({
  userName,
  userId,
  setUserName,
  setUserId,
  errors,
  userNameShowResult,
}) {
  const dispatch = useDispatch();
  const { updateScreenSettings } = useInvoiceSettings();
  const users = useSelector((state) => state.user?.users);
  const userPermissions = useSelector((state) => state?.setting?.userPermissions);
  const { setScreenSettings, screenSettings } = useScreensPermissions();

  useEffect(() => {
    if (!userPermissions) return;

    const mappedPermissions = {
      posShow: screenSettings?.posShow ?? userPermissions?.["pos.show"] ?? true,
      posPriceChangeAuth: screenSettings?.posPriceChangeAuth ?? userPermissions?.["pos.priceChangeAuth"] ?? true,
      posChangeDiscount: screenSettings?.posChangeDiscount ?? userPermissions?.["pos.changeDiscount"] ?? true,
      posDeleteProdWithPass: screenSettings?.posDeleteProdWithPass ?? userPermissions?.["pos.deleteProdWithPass"] ?? true,
      posInvoiceTypeChangeAuth: screenSettings?.posInvoiceTypeChangeAuth ?? userPermissions?.["pos.InvoiceTypeChangeAuth"] ?? true,
      posPaymentMethodChangeAuth: screenSettings?.posPaymentMethodChangeAuth ?? userPermissions?.["pos.paymentMethodChangeAuth"] ?? true,
      posSaveNoPrintAuth: screenSettings?.posSaveNoPrintAuth ?? userPermissions?.["pos.saveNoPrintAuth"] ?? true,
      posEditDate: screenSettings?.posEditDate ?? userPermissions?.["pos.editDate"] ?? true,
      posChooseClient: screenSettings?.posChooseClient ?? userPermissions?.["pos.chooseClient"] ?? true,
      posInvoiceFreeze: screenSettings?.posInvoiceFreeze ?? userPermissions?.["pos.InviceFreeze"] ?? true,
      posInvoiceCall: screenSettings?.posInvoiceCall ?? userPermissions?.["pos.InviceCall"] ?? true,
      posPriceChange: screenSettings?.posPriceChange ?? userPermissions?.["pos.priceChange"] ?? true,
      posChangeTax: screenSettings?.posChangeTax ?? userPermissions?.["pos.changeTax"] ?? true,
      posInvoiceCancel: screenSettings?.posInvoiceCancel ?? userPermissions?.["pos.InviceCancel"] ?? true,
      posShiftClose: screenSettings?.posShiftClose ?? userPermissions?.["pos.shiiftClose"] ?? true,

      adShow: screenSettings?.adShow ?? userPermissions?.["showAds"] ?? true,
      notificationShow: screenSettings?.notificationShow ?? userPermissions?.["showNotifications"] ?? true,
      bromocodeShow: screenSettings?.bromocodeShow ?? userPermissions?.["showPromoCodes"] ?? true,

      warehouseShow: screenSettings?.warehouseShow ?? userPermissions?.["warehouse.show"] ?? true,
      warehouseEdit: screenSettings?.warehouseEdit ?? userPermissions?.["warehouse.edit"] ?? true,
      warehouseDelete: screenSettings?.warehouseDelete ?? userPermissions?.["warehouse.delete"] ?? true,

      createUser: screenSettings?.createUser ?? userPermissions?.["user.create"] ?? true,
      configUpdate: screenSettings?.configUpdate ?? userPermissions?.["config.update"] ?? true,

      productCreate: screenSettings?.productCreate ?? userPermissions?.["product.create"] ?? true,
      productEdit: screenSettings?.productEdit ?? userPermissions?.["product.edit"] ?? true,
      productDelete: screenSettings?.productDelete ?? userPermissions?.["product.delete"] ?? true,
      productShowSidebar: screenSettings?.productShowSidebar ?? userPermissions?.["showProductsSidebar"] ?? true,
      productShowGeneral: screenSettings?.productShowGeneral ?? userPermissions?.["showGenralProducts"] ?? true,

      sectionCreate: screenSettings?.sectionCreate ?? userPermissions?.["section.create"] ?? true,
      sectionEdit: screenSettings?.sectionEdit ?? userPermissions?.["section.edit"] ?? true,
      sectionDelete: screenSettings?.sectionDelete ?? userPermissions?.["section.delete"] ?? true,
      sectionShowSidebar: screenSettings?.sectionShowSidebar ?? userPermissions?.["showSectionsSidebar"] ?? true,

      orderShow: screenSettings?.orderShow ?? userPermissions?.["order.show"] ?? true,
      orderPrepare: screenSettings?.orderPrepare ?? userPermissions?.["order.prepare"] ?? true,
      orderCancel: screenSettings?.orderCancel ?? userPermissions?.["order.cancel"] ?? true,
      orderFinish: screenSettings?.orderFinish ?? userPermissions?.["order.finish"] ?? true,
      orderShipment: screenSettings?.orderShipment ?? userPermissions?.["order.shipment"] ?? true,
      orderShowSidebar: screenSettings?.orderShowSidebar ?? userPermissions?.["showOrdersSidebar"] ?? true,

      reportShow: screenSettings?.reportShow ?? userPermissions?.["reports.show"] ?? true,

      deliveryShow: screenSettings?.deliveryShow ?? userPermissions?.["showDelevary"] ?? true,
      deliveryEdit: screenSettings?.deliveryEdit ?? userPermissions?.["delivery.edit"] ?? true,
      deliveryDelete: screenSettings?.deliveryDelete ?? userPermissions?.["delivery.delete"] ?? true,
      deliveryAddArea: screenSettings?.deliveryAddArea ?? userPermissions?.["delevary.addArea"] ?? true,
      deliveryFreeDelivery: screenSettings?.deliveryFreeDelivery ?? userPermissions?.["delevary.freeDelevary"] ?? true,

      aboutUsShow: screenSettings?.aboutUsShow ?? userPermissions?.["showAboutUs"] ?? true,
      evaluationShow: screenSettings?.evaluationShow ?? userPermissions?.["showClientsVote"] ?? true,
      customerShow: screenSettings?.customerShow ?? userPermissions?.["showCustomers"] ?? true,
      customerShowMessages: screenSettings?.customerShowMessages ?? userPermissions?.["showCustomersMessages"] ?? true,
      kitchenShow: screenSettings?.kitchenShow ?? userPermissions?.["showKitchen"] ?? true,
      statisticsShow: screenSettings?.statisticsShow ?? userPermissions?.["showStatistics"] ?? true,
      supplierShow: screenSettings?.supplierShow ?? userPermissions?.["showSuppliers"] ?? true,
      unitShow: screenSettings?.unitShow ?? userPermissions?.["showUnits"] ?? true,
    };

    updateScreenSettings((prev) => ({ ...prev, ...mappedPermissions }));
    setScreenSettings((prev) => ({ ...prev, ...mappedPermissions }));
  }, [userPermissions]);

  const [highlightIndex, setHighlightIndex] = useState(-1);

  return (
    <div className="w-full bg-white rounded-3xl">
      <div className="flex items-center border-b">
        <FaUser className="text-2xl text-blue-700 mr-5" />
        <h1 className=" p-5 pr-2 font-semibold text-xl">اعدادات المستخدم</h1>
      </div>
      <div className="flex items-center gap-x-10 p-3 text-lg py-5">
        <h2>اسم المستخدم :</h2>
        <div className="relative w-[50%]">
          <select
            className="w-full text-gray-900 px-2.5 py-1.5 text-base border rounded focus:outline-blue-500 appearance-none"
            value={userId ?? ""}
            onChange={(e) => {
              const value = e.target.value;
              const selectedUser = users?.find((user) => user?.id === Number(value));
              if (!selectedUser) return;

              setUserName(selectedUser.name ?? "");
              setUserId(selectedUser.id);

              updateScreenSettings((prev) => ({
                ...prev,
                userId: selectedUser.id,
                userName: selectedUser.name,
              }));
              
              setScreenSettings((prev) => ({
                ...prev,
                userId: selectedUser.id,
                userName: selectedUser.name,
              }));

              //dispatch(fetchUserPermissions({ id: selectedUser?.id }));
            }}
          >
            <option value="" disabled className="bg-white">
              يرجى اختيار اسم المستخدم
            </option>
            {users?.map((user) => (
              <option key={user?.id} value={user.id} className="bg-white">
                {user.name}
              </option>
            ))}
          </select>
          
          {userNameShowResult && userName.length > 0 && (
            <div className="border bg-white absolute w-full z-50 max-h-60 overflow-y-auto shadow">
              {users?.map((user, index) => (
                <div
                  key={user?.id}
                  className={`p-2 cursor-pointer ${
                    index === highlightIndex ? "bg-blue-100" : "hover:bg-gray-100"
                  }`}
                >
                  {user.name}
                </div>
              ))}
            </div>
          )}
          {errors?.userName && (
            <p className="text-red-500 text-sm font-bold">{errors?.userName}</p>
          )}
          <button
            className="absolute left-3 top-1/4"
            onClick={() => {
              setUserName("");
              setUserId("");
              dispatch(resetUserPermissions());
              sessionStorage.removeItem("Screens Settings");
              updateScreenSettings((prev) => ({
                ...prev,
                userName: "",
                userId: "",
              }));
              setScreenSettings(null);
            }}
          >
            <IoCloseSharp />
          </button>
        </div>
      </div>
      <hr className="w-[95%]" />
    </div>
  );
}